<?php
error_reporting(E_ERROR | E_WARNING);
include "../lib/include.php";
$objpackges = new Packges();
$objorder = new Orders();
$objproducts = new Products();
$objClient = new Clients();
$objorderform = new OrderForm();
$ObjMerchantAccount = new MerchantAccount();
$ObjGroup = new Groups();
$objuser = new Users();
$objCompany = new Company();
$utilObj = new util();
$CompanyID = $objuser->GetAllUsers("Users.ID = '".$_SESSION['Member']['ID']."'",array("CompanyID")); 
$MerchantID = $objCompany->GetAllCompany(" ID = '".$CompanyID[0]['CompanyID']."'",array("MerchantID"));

$States = $utilObj->getMultipleRow('States',"1 ORDER BY state_name ASC");

if($_GET['Email'] != ''){
	$Members_array = $objClient->GetAllClients("Email= '".$_GET['Email']."' ",array("ID,Email"));
	$clientcount = count($Members_array);
	if($clientcount > 0){
		echo 'false';
	}
	else{
		echo 'true';
	}
	exit;	
}


$ObjPromotionalCode = new PromotionalCode();
if($_REQUEST['Task'] == 'RemovePackage'){
	$key = array_search($_REQUEST['package_id'], $_SESSION['OrderData']['Packages']);
	if (false !== $key) {
		unset($_SESSION['OrderData']['Packages'][$key]);
		header("location:AddClients.php?flag=package_remove");
	}
}
if($_REQUEST['Task'] == 'RemoveProduct'){
	$key = array_search($_REQUEST['product_id'], $_SESSION['OrderData']['Products']);
	if (false !== $key) {
		unset($_SESSION['OrderData']['Products'][$key]);
		header("location:AddClients.php?flag=product_remove");
	}
}
if($_REQUEST['Task'] == 'AddToOrder'){
	
    $_SESSION['OrderData']['Packages'][]  		=   $_REQUEST['PackageID'];
    $_SESSION['OrderData']['Products'][]  		=   $_REQUEST['ProductID'];
	$_SESSION['OrderDataInRequest']['ProductID'] = $_REQUEST['ProductID'];
}

if(isset($_REQUEST['CalculateTotal'])){
    $_SESSION['product_array_prices_package'] 	= $_REQUEST['product_array_prices_package'];
    $_SESSION['product_array_promos_package'] 	= $_REQUEST['product_array_promos_package'];
    $_SESSION['product_array_qty_package'] 		= $_REQUEST['product_array_qty_package'];
    $_SESSION['product_array_promos'] 			= $_REQUEST['product_array_promos'];
    $_SESSION['product_array_prices'] 			= $_REQUEST['product_array_prices']; 
    $_SESSION['product_array_qty'] 				= $_REQUEST['product_array_qty'];
}

$Packge_array = $objpackges->GetAllPackges(" ShowOnOrderForm = 1 ORDER BY PackagesTitle ASC",array("*"));

if(isset($_REQUEST['PurchaseNow'])){
	
	$order_charged = "";
	$_REQUEST['Cnumber'] = trim(str_replace("-","",trim($_REQUEST['Cnumber'])));
	
	if(!empty($_REQUEST['createddate'])){
		$createddate = date("Y-m-d",strtotime($_REQUEST['createddate']));
	}else{
		$createddate = "";
	}
	
	if(!empty($_REQUEST['BestCallTime'])){
		$BestCallTime = date("Y-m-d ",strtotime($_REQUEST['BestCallTime'])).$_REQUEST['hour'].":".$_REQUEST['minuts'].":00";
	}else{
		$BestCallTime = ""; 
	}
	
	$Members_array = $objClient->GetAllClients("Email= '".$_REQUEST['Email']."' ",array("ID,Email"));
	
	$clientcount = count($Members_array);
	
	$ObjMerchantAccount = new MerchantAccount();
	$MerchantData = $ObjMerchantAccount->GetAllMerchantAccount("MerchantId = '".$MerchantID[0]['MerchantID']."'",array("*"));
	
	$merchantRecords = $MerchantData[0];
	if(empty($MerchantData[0])){
		$ResponseMessage = "No merchant selected for credit card payments!";
		header("location:AddClients.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage"); 
	}
	
	$merchant_to_charge = $MerchantData[0]['AccountType'];
	
	# Get the invoice number for order agent-client-number
	$invoice_no = $_SESSION['Member']['ID']."-".time();
	$_SESSION['invoice'] = $invoice_no;
	$CollectingPayment = "";
	if(($_POST['voidtransaction'] == "on" || $_POST['voidtransaction'] == 1) and !empty($_POST['voidtransaction'])){
		# Added the Insertion into the database here outside of any of the conditions
		require_once('SaveOrderDB.php');
		
		if($Order_created_without_charge == 1){
			if($_REQUEST['send_email_invoice'] == "on" and !empty($_REQUEST['send_email_invoice'])){
				require_once('SendInvoice.php');
			}
			$ResponseMessage = "Order and client record created successfully! Unpaid order.";
			unset($_SESSION['OrderDataInRequest']);
			header("location:AddClients.php?tab=1&flag=client_add&ResponseMessage=$ResponseMessage");  
		}
	}else{
		# first authenticate the Credit Card info
		# $OrderFormMerchantData=$objorderform->GetAllOrderFormData("ID = $order_form_id",array("MerchantAccID"));
		
		$LoginID = trim($merchantRecords['LoginID']);
		$TransKey = trim($merchantRecords['TransactionKey']);
		$SandBox = true;
		
		if($merchantRecords['Mode'] == '-1')
			$SandBox = false;
		else
			$SandBox = true;
		require_once('../ecommerce/products/AuthorizeNet-sdk/AuthorizeNet.php'); 

		switch($merchant_to_charge){
		case "14":
			# EDP
			require_once('ChargeOnGWAPI.php');
		break;
		default:
			# authorize.net code will go there
			require_once('ChargeOnAuthNetAPI.php');
		break;
		}
		
		if(!empty($order_charged) and $order_charged == 1) {
			if($_REQUEST['send_email_invoice'] == "on" and !empty($_REQUEST['send_email_invoice'])){
				require_once('SendInvoice.php');
			}
			unset($_SESSION['OrderData']['Products']);
			$ResponseMessage = "Success! Credit card has been charged! Transaction ID: " . $transaction_id;
			header("location:AddClients.php?tab=1&flag=client_add&tras_approved=1&invoice_email_sent=$invoice_email_sent&ResponseMessage=$ResponseMessage");  
			unset($_SESSION['OrderDataInRequest']);
		}else{
			# Since the Order failed. So We have hardcoded the varaible below
			# $Order_created_without_charge = 1;
			# if($_REQUEST['send_email_invoice'] == "on" and !empty($_REQUEST['send_email_invoice'])){
				# require_once('SendInvoice.php');
			# }
			if(isset($response_sale)){
			$ResponseMessage = $response_sale['responsetext'];
			if(empty($response_sale['responsetext']))
				$ResponseMessage = "System Error! Order was made. Credit card was not charged.";
			elseif(!empty($response_sale['responsetext']))
				$ResponseMessage = $response_sale['responsetext'].". Order was made. Credit card was not charged.";
			}else {
				if(!empty($response_sale['responsetext']))
					$ResponseMessage = $response_sale['responsetext'].". Order was made. Credit card was not charged.";
				else
					$ResponseMessage = "Unknown Error! Order was made. Credit card was not charged.";
			}
			
			$_SESSION['OrderDataInRequest'] = $_REQUEST;
			header("location:AddClients.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage");
		}
	}
}

if(isset($_REQUEST['clearorderform'])){
    unset($_SESSION['OrderDataInRequest']);
    unset($_SESSION['OrderData']['Products']);
    unset($_SESSION['OrderData']['Packages']);
}

$products_array = $objproducts->GetAllProduct(" ShowOnOrderForm = 1 ",array("ID,ProductName,ProductPrice,Description"));

?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery.browser.min.js"></script>
<script type="text/javascript">
	$(function() {
		$( "#tabs" ).tabs();
	});
	
	function Validatetheform(){
		
		var price = $("#product_selected_or_not").val();
		if($("#selectproduct").val() == "" && price == 0){
			$(".selectproducterror").show();
			$('html, body', document).animate({scrollTop:0}, 'slow');
			return false;
		}else{
			$(".selectproducterror").hide();
		}

		$("#orderform").validate({
			errorElement:'label',
			rules: {
				Email:{
					required:true,
					email	: true,
					/*remote:	function() {
						return 'AddClients.php'
					}*/		
				}
			},
			messages:{
				Email:{
					required : "Enter email address",
					email 	 : "Enter valid email address",
					remote	 : "Email already in use."
				}
			},
			rules: {
			 routing_number:{
			 		required:true,
					number :true, 
					pat :true,
				    minlength : 9,
			 	}
			},
			messages:{
				routing_number:{
							 required  : "This field is required",
							 number    : "Enter valid routing number",
							 minlength : "Length should be 9 digits",
				}
			},	
		});
		
		jQuery.validator.addMethod("pat", function(value, element) {
			return this.optional(element) || /^[0-3]\d*$/.test(value);
		}, "Please start with 0, 1, 2 or 3");
		
		if(price == 0 || price == ""){
			$(".selectproducterror").show();
			$('html, body', document).animate({scrollTop:0}, 'slow');
			return false;	
		}else if($('.product_selected_or_not').val() > 0){
			
		}
		
		if($("#orderform").valid()){
			/* get the end time */
			try{
				var end_time = (new Date()).getTime();
				$("#end_time").val(end_time);
				var s = $("#start_time").val();
				var e = $("#end_time").val();
				var tot_el = (e - s) / 1000;
				$("#total_time_spend").val(tot_el);
			}
			catch(err){
				console.log("Could not find details.");
			}
			
			$(".loading_icon_order").show();
			$(".button_success").css({'background-color':'#e1e1e1','border':'1px solid #e1e1e1','color':'#666','pointer-events':'none'}).val("Please Wait...");
		}else{
			$(".loading_icon_order").hide();
		}
	}

	function confirmation() {
		var str = $("#orderform" ).serialize();
		$.ajax({
			type    : 'post',
			url     : '<?php echo SITE_ADDRESS; ?>ajax/AllAjaxCalls.php',
			data    : 'Task=AddAllToSession&AllData='+str,
			success : function(data){
				console.log("Added to Session");
			}	
		});
		var answer = confirm("Do you want to remove this product ?");
		if(answer){
			return true;
		}else{
			return false;
		}
	}

	$(document).ready(function() {
		$("#timepicker1, #process_later_date").datepicker();		
		
		try{
			var start = (new Date()).getTime();
			<?php if(empty($_SESSION['OrderDataInRequest']['start_time'])) { ?>
			$("#start_time").val(start);
			<?php } ?>
			
			var browser = $.browser.name;
			var browser_version = $.browser.version;
			var browser_layout = $.layout.name;
			var OS = $.os.name;
			var b_details = browser + ","+ browser_version + "," + browser_layout + ","+ OS;
			
			$("#browser_details").val(b_details);
		}
		catch(err){
			console.log("Could not find details.");
		}
	});

	function selectfirst_tab(){
		$("#tabs").tabs("option", "active", 1);
	}
	
	$(document).ready(function(){
		$("#Addnew").click(function(e){
			e.preventDefault();
			if($("#selectproduct").val() != ""){
				$(".message_wait").show();
				$(".selectproducterror").hide();
			}else{
				$(".selectproducterror").show();
				$(".message_wait").hide();
			}
			
			/* 
			  This ajax call is made to set all the below filled 
			  values in session so that the agents do not have to fill
			  in these details again.
			*/
			var str = $("#orderform" ).serialize();
			$.ajax({
				type    : 'post',
				url     : '<?php echo SITE_ADDRESS; ?>ajax/AllAjaxCalls.php',
				data    : 'Task=AddAllToSession&AllData='+str,
				success : function(data){
					if($("#selectproduct").val() == ""){
						return false;
					}else{
						$("#selectproduct_form").submit();
					}
				}	
			});	
		});
		
		$("#selectproduct").change(function(){
			var price = $("#product_selected_or_not").val();
			if($("#selectproduct").val() == "" && price == 0){
				$(".selectproducterror").show();
				$(".message_wait").hide();
			}else{
				$(".selectproducterror").hide();
				$(".message_wait").hide();
			}
		});
	});
	
	function hittotalcalculate(this_pointer){
		$(".loading_icon").show();
		var oldval = $.trim($(this_pointer).attr("data-old"));
		var new_val = $.trim($(this_pointer).val());
		if(oldval != new_val){
			var str = $("#orderform" ).serialize();
			$.ajax({
				type    : 'post',
				url     : '<?php echo SITE_ADDRESS; ?>ajax/AllAjaxCalls.php',
				data    : 'Task=AddAllToSession&AllData='+str,
				success : function(data){
					$("#Calculate_Total").click();
				}	
			});	
		}
	}

</script>
<style type="text/css">
label.error {
	background: none repeat scroll 0 0 #FFDBDB;
    border: 1px solid #F6846C;
    color: #FF0000;
    font-weight: normal;
    margin-top: 2px;
    padding: 5px 10px;
    width: 157px;
	margin-left:5px;
}
.inputsapp, .product{
	width:350px !important;
}
.inputsapp.required.error {
    border: 1px dashed lightcoral;
}
.button_success{
    background-color: #78CD51;
    border: 1px solid #72A53B;
    color: #FFFFFF;
    cursor: pointer;
    font-family: "OpenSansSemiBold",Helvetica,Arial,sans-serif;
    font-size: 13px;
    font-style: normal;
    font-weight: normal;
    line-height: 20px;
    margin: 0;
    padding: 7px 14px;
    text-align: center;
    text-decoration: none;
    text-rendering: optimizelegibility;
    text-transform: none;
    transition: all 0.15s ease 0s;
    vertical-align: middle;
    white-space: normal;
}
.button_success:hover{
    background-color: #9BC969;
	border: 1px solid #82BC43;
}
.message_wait{
	background-color: #BEE1F1;
	border: 1px solid #9ACFEA;
	color: #31708F;
	float: left;
	font-weight: normal;
	padding: 5px;
	text-align: center;
	width: 100%;
	display:none;
	margin-top: 10px;
}
.submit_holder_div{
	background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
	bottom: 5px;
	display: inline-block;
	float: none;
	left: 0;
	margin: 0 auto 0 40%;
	padding: 2px 0;
}
</style>
	<?php if($_REQUEST['flag'] == 'product_remove'){?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
            <tr>
            	<td align="left" colspan="3" class="message_success">Product Removed successfully!</td>
            </tr>
        </table>
    <?php }
	if($_REQUEST['flag'] == 'payment_gatway_error'){?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
            <tr>
                <td align="left" colspan="3" id="message_error">Please Select Payment Method!</td>
            </tr>
        </table>
   <?php } ?>  
                                 
<div id="tabs">
    <ul>
    	<li><a href="#tabs-order">Order Form </a></li>
    	<li><a href="#tabs-payment">Response</a></li>
    </ul>
	<div id="tabs-order">
        <form action="?Task=AddToOrder" method="post" target="_self" enctype="multipart/form-data" name="frmSample" id="selectproduct_form">  
            <table width="100%" border="0" cellspacing="0" cellpadding="2"align="center">    
                <tr>
                    <td>
                    <select name="PackageID" class="dropdwn" >
                    	<option value="1">Select Package</option>
						<?php
                        foreach ((array)$Packge_array as $packages){ ?>
                            <option  value="<?php echo $packages['ID'];?>"><?php echo $packages['PackagesTitle'];?></option>
                        <?php } ?>
                    </select>
                    </td>
                    <td>
                    <select name="ProductID" class="dropdwn" id="selectproduct"> 
                        <option value="">Select Product</option>
                        <?php
                        foreach((array)$products_array as $products_row){
							if($_SESSION['OrderDataInRequest']['ProductID'] == $products_row['ID'] || $_REQUEST['ProductID'] == $products_row['ID']){
								$selected = "selected";
							}else{
								$selected = "";
							}
						?>
                            <option value="<?php echo $products_row['ID'];?>" <?php echo $selected; ?>><?php echo $products_row['ProductName'];?></option>
                        <?php } ?>
                    </select>
                    &nbsp;
                    <label class="error selectproducterror" for="product" style="display:none;">Please select and add product.</label>
                    <label class="message_wait" style="float:right; margin-top:0; width:30%;">Please wait...</label>
                    </td>                
                    <td >
                        <div align="right">
                            <input type="submit" name="Submit" value="Add To Order" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="top:0 !important;" id="Addnew"/>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
        
 <form action="AddClients.php?Task=UpdateOrder" method="post" id="orderform"  target="_self" enctype="multipart/form-data" name="myForm">
        <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
        <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
        <tr>
                           
        <td colspan="3" align="center" >
        <table width="100%"  border="0" cellspacing="0" cellpadding="2">
            <tr id="headerbarpopup">
            <td>Product Name</td>
            <td>Promo</td>
            <td>Price</td>
            <td>Quantity</td>
            <td>Total</td>
            <td width="65" align="center">Remove</td>
            </tr>
            <?php
            $flag = 0; 
            if($flag == 0){
                    $flag = 1;
                    $row_class = "row-white";
                }else{
                    $flag = 0;
                    $row_class = "row-tan";
                }
           
            $total = 0;
            $PromoCodesArray = $ObjPromotionalCode->GetAllPromotionalCodes(" HasDeleted = 0 AND PromosShowsOnOrderForm = 1 ",array("*"));
            foreach((array)$_SESSION['OrderData']['Packages'] as $package_id){
                if( $package_id > 0  ){
                    $package_products = $objpackges->GetAllProductToPackges("PackagesID ='$package_id'",array("*"));

                    $Packge_Data = $objpackges->GetAllPackges(" ID = '$package_id' ORDER BY PackagesTitle ASC",array("*"));
                    if(count($package_products)){
                    ?>
                    <tr id="package-heading">
                     <td><?php echo $Packge_Data[0]['PackagesTitle'];?></td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td align="center"><a href="?Task=RemovePackage&package_id=<?php echo $package_id;?>"><img border="0" src="../images/icon_delete.png" title="Delete package" onclick="return confirmation();"></a></td>
                    </tr>
                <?php
                $product_price = 0;
                foreach((array)$package_products as $product_row){
                
                if($product_row['ProductID'] > 0 ){
                $package_products = $objproducts->GetAllProduct(" ID = ".$product_row['ProductID'],array("ID,ProductName,ProductPrice,Description,ShowCoupn"));
                $qty = $_SESSION['product_array_qty_package'][$package_id][$product_row['ProductID']];
                if($qty < 1){
                    $qty = 1;
                }
                $promo = $_SESSION['product_array_promos_package'][$package_id][$product_row['ProductID']];
                if($promo < 1){
                    $promo = 0;
                }
                $current_product_price = $_SESSION['product_array_prices_package'][$package_id][$product_row['ProductID']];
                if($current_product_price < 1){
                    // get the default price in
                    $price_array = $objproducts->GetAllProductPrice(" ProductID = ".$product_row['ProductID']." and DefaultPrice = 1",array("*"));
                    $current_product_price = $price_array[0]['ProductPrice'];
                }
                $product_price = $current_product_price * $qty - $promo;
            
                $subtotal = $subtotal + $product_price;
            
            ?>
            
            <tr id="<?php echo $row_class;?>">
             <td><?php echo $package_products[0]['ProductName'];?></td>
             <td>
             <?php if($package_products[0]['ShowCoupn'] == 1){ ?>
             <select name="product_array_promos_package[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID']; ?>]" class="dropdwn">
               <option value="0" <?php if($promo == 0) echo "selected"; ?>>Select One</option>
               <?php foreach((array)$PromoCodesArray as $promo_row){?>
               <option value="<?php echo $promo_row['Price'];?>" <?php if($promo_row['Price'] == $promo) echo"selected"; ?>><?php echo $promo_row['Category_Name'];?></option>
               <?php } ?>
             </select>
             <?php } ?>
             </td>
            <td>$
            <?php
            $price_array = $objproducts->GetAllProductPrice(" ProductID = ".$product_row['ProductID'],array("*"));
            $singleprice = count($price_array);
            if($singleprice > 1){
            ?>
            <select name="product_array_prices_package[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID']; ?>]" class="dropdwn">
            <?php
            foreach((array)$price_array as $price){?>
            	<option value="<?php echo $price['ProductPrice']; ?>" <?php if($price['ProductPrice'] == $current_product_price){ echo " selected "; } ?>><?php echo $price['ProductPrice']; ?>
            </option>
            <?php } ?>
            </select>
            <?php } else{ echo number_format($price_array[0]['ProductPrice'],2); }?>
            </td>
             <td><input type="text" name="product_array_qty_package[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID']; ?>]"value="<?php echo $qty;?>" size="3"/></td>
             <td>$<?php echo number_format($product_price,2); ?></td>
                 <td>
                     <input type="hidden" name="product_array_name[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID']; ?>]" value="<?php echo $package_products[0]['ProductName']; ?>" />
                    <input type="hidden" name="product_array_price[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID']; ?>]" value="<?php echo number_format($package_products[0]['ProductPrice'],2); ?>" />
                    <input type="hidden" name="product_array_id[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID']; ?>]" value="<?php echo $product_row['ProductID']; ?>" />
                    <input type="hidden" name="product_array_descrption[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID']; ?>]" value="<?php echo $package_products[0]['Description'];?>" />        </td>
            </tr>
            
            <?php 
                    }
                }
                $subtotal =  $subtotal - $Packge_Data[0]['PackageDiscount']
                ?>
            <tr id="package-heading">
                 <td colspan="3"> </td>
                 <td >Package Discount:</td>
                 <td>$<?php echo number_format($Packge_Data[0]['PackageDiscount'],2); ?></td>
                 <td>&nbsp;</td>
             </tr>
            <tr id="totalamount">
                 <td colspan="3"> </td>
                 <td>Package Total:</td>
                 <td>$<?php echo number_format($subtotal,2); ?></td>
                 <td>&nbsp;</td>
             </tr>
                <?php
                 $total = $subtotal + $total;
                         $subtotal = 0;
                }
               }
              } 
               ?>
              
            <tr>
           		<td colspan="6"><hr/></td>
            </tr>
            <tr id="package-heading">
                <td>Products</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
                <?php
               $promo = 0;
               $qty = 1;
               foreach((array)$_SESSION['OrderData']['Products'] as $product_id){
                 if($product_id > 0 ){
           		 $package_products = $objproducts->GetAllProduct(" ID = '".$product_id."'",array("ID,ProductName,ProductPrice,Description,ShowCoupn"));
                    $qty = $_SESSION['product_array_qty'][$product_id];
                    if($qty < 1){
                        $qty = 1;
                    }
                   
                    $promo = $_SESSION['product_array_promos'][$product_id];
                    if($promo < 1){
                        $promo = 0;
                    }
                    $current_product_price = $_SESSION['product_array_prices'][$product_id];
                    if($current_product_price < 1){
                       // get the default price in
                        $price_array = $objproducts->GetAllProductPrice(" ProductID = ".$product_id." and DefaultPrice = 1",array("*"));
                        $current_product_price = $price_array[0]['ProductPrice'];
                    }
                    
                    
                    $product_price = $current_product_price * $qty;
                    $product_price = $product_price - $promo;
                    
                    ?>
                     <tr id="<?php echo $row_class;?>">
                     <td><?php echo $package_products[0]['ProductName'];?></td>
                     <td>
                     <?php if($package_products[0]['ShowCoupn']== 1){?>
                     
                     <select name="product_array_promos[<?php echo $product_id; ?>]" class="dropdwn">
                       <option value="0" <?php if($promo == 0)echo"selected";?>>Select One</option>
                       <?php foreach((array)$PromoCodesArray as $promo_row){?>
                       <option value="<?php echo $promo_row['Price'];?>" <?php if($promo_row['Price'] == $promo) echo "selected"; ?>><?php echo $promo_row['Category_Name'];?></option>
                       <?php } ?>
                     </select>
                     <?php } ?>			 </td>
                     <td>
                     	<div style="float:left;">
                        $
						<?php
                        $price_array = $objproducts->GetAllProductPrice(" ProductID = $product_id",array("*"));
                        $singleprice = count($price_array);
                        if($singleprice > 1){
                        ?>
                        <select name="product_array_prices[<?php echo $product_id; ?>]" class="dropdwn" data-old="<?php echo $current_product_price; ?>" onchange="hittotalcalculate(this);">
                        <?php
                        foreach((array)$price_array as $price){?>
                        <option value="<?php echo $price['ProductPrice'];?>" <?php if($price['ProductPrice'] == $current_product_price){ echo "selected ";} ?>><?php echo number_format($price['ProductPrice'],2);?></option>
                        <?php } ?>
                        </select>
                        <?php } else{ echo number_format($price_array[0]['ProductPrice'],2); }?>
                        </div>
                        <div class="loading_icon" style="display:none; float:left; width:0px;"><img src="../images/loading.gif" style="width:18px; height:18px;" /></div>
                    </td>
                     <td>
                     <div style="float:left;">
                     	<input type="text" name="product_array_qty[<?php echo $product_id; ?>]" value="<?php echo $qty; ?>" size="3" data-old="<?php echo $qty; ?>" onblur="hittotalcalculate(this);"/>
                     </div>
                     <div class="loading_icon" style="display:none; float:left; width:0px;"><img src="../images/loading.gif" style="width:18px; height:18px; padding-top:5px;" /></div>
                     </td>
                     <td>$<?php echo number_format($product_price,2); ?></td>
                     <td align="center">
                         <input type="hidden" name="product_array_id_for_product[<?php echo $product_id; ?>]" value="<?php echo $product_id; ?>" />
                         <input type="hidden" name="product_array_price[<?php echo $product_id; ?>]" value="<?php echo $current_product_price; ?>" />
                         <input type="hidden" name="product_array_name[<?php echo $product_id; ?>]" value="<?php echo $package_products[0]['ProductName']; ?>" />
                         <a href="?Task=RemoveProduct&product_id=<?php echo $product_id; ?>"><img border="0" src="../images/icon_delete.png" title="Delete product or package" onclick="return confirmation();"></a></td>
                    </tr>
                    <?php 
                    $total = $total + $product_price;
                    $productTotal = $productTotal + $product_price;
                    }
                }
                ?>
        
         <?php if(!empty($productTotal)){?>
        <tr id="totalamount">
                 <td colspan="3"> </td>
                 <td>Products Total</td>
                 <td>$<?php echo number_format($productTotal,2);?></td>
                 <td>&nbsp;</td>
             </tr>
             <?php }?>
        <?php if(!empty($total)){?>	 
        <tr>
        <td align="center" colspan="6">&nbsp;</td>
        </tr>	
        <tr id="totalamount">
        <td >Total:</td>
        <td align="right" colspan="4">&nbsp;</td>
        <td align="center">$<?php echo number_format($total,2); ?></td>
        </tr>
        <?php  } ?>	
        <input type="hidden" name="totalprice"  value="<?php echo $total; ?>" id="product_selected_or_not" />
        <tr>
        <td align="right" colspan="6" valign="bottom">
        <div align="right">
        <input type="submit" name="CalculateTotal" id="Calculate_Total" value="Calculate Total"class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="display:none;" />
        <input type="hidden" name="addorder" value="1"/>
        <input type="hidden" name="clientorder"  value="1"/>
         <?php 
   
  
   if($_SESSION['Member']['CompanyID'] == "1"){
   		echo '<h3 class="blink_me" style="float:left; color: red; margin-top: 0">DEMO</h3>';
   }
   ?>   
        <input type="submit" name="clearorderform" value="Clear Order Form"class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
        </div></td>
        </tr>			
        </table>
        </td>
        </tr>
        </table>
        </td>
        </tr>

        </table> 
        <table width="100%"  border="0">
        <tr >
            <td colspan="3" id="tabsubheading"> Billing Information</td>
        </tr>
        <tr>
           <td width="30%"> Business Name: </td>
           <td width="65%">
               <input class="inputsapp required" name="cname" value="<?php echo $_SESSION['OrderDataInRequest']['cname'];?>" onblur="sendtoSession();" style="width:100%;" type="text"/>
           </td>
           <td width="30%">&nbsp;</td>
        </tr>
        
        <tr valign="top">
           <td > First Name:</td>
           <td >
               <input class="inputsapp required" name="fName" value="<?php echo $_SESSION['OrderDataInRequest']['fName'];?>" type="text" onblur="sendtoSession();" style="width:100%;" id="fName" />
           </td>
           <td >&nbsp;</td>
        </tr>
        <tr valign="top">
          <td > Last Name:</td>
          <td allign="left">
              <input  class="inputsapp required" name="sureName" value="<?php echo $_SESSION['OrderDataInRequest']['sureName'];?>" type="text" onblur="sendtoSession();" style="width:100%;" id="sureName" />
          </td>
          <td>&nbsp;</td>
        </tr>
        
        <tr valign="top">
          <td  >Customer Email:</td>
          <td allign="left">
              <input class="inputsapp required email" name="Email" value="<?php echo $_SESSION['OrderDataInRequest']['Email'];?>" type="email" onblur="sendtoSession();" style="width:100%;"  id="Email"/>
          </td>
          <td>&nbsp;</td>
        </tr>
                    
        <tr>
          <td >Best Phone: </td>
          <td allign="left">
              <input class="inputsapp required" name="phone" value="<?php echo $_SESSION['OrderDataInRequest']['phone'];?>" type="text" onblur="sendtoSession();" style="width:100%;" id="phone"/>
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td >Mobile Phone: </td>
          <td allign="left">
              <input class="inputsapp required" name="mobilephone" value="<?php echo $_SESSION['OrderDataInRequest']['mobilephone'];?>" type="text" onblur="sendtoSession();" style="width:100%;" />
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td >Alternate Phone: </td>
          <td allign="left">
              <input  class="inputsapp"name="alternatephone" value="<?php echo $_SESSION['OrderDataInRequest']['alternatephone'];?>" onblur="sendtoSession();" type="text" style="width:100%;" />
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
            <td><label> Others Notes:</label></td>
            <td> 
                <textarea class="inputsapp" name="OthersNotes" onblur="sendtoSession();" rows="5" style="width:100%;"><?php echo $_SESSION['OrderDataInRequest']['OthersNotes'];?></textarea>   
            </td>
            <td>&nbsp;</td>
        </tr>
        
        <tr >
            <td colspan="3" id="tabsubheading"> Billing Address</td>
        </tr>
        <tr>
            <td><label> Street Address1:</label></td>
            <td> 
                <input class="inputsapp required" name="Streetaddress1" onblur="sendtoSession();" value="<?php echo $_SESSION['OrderDataInRequest']['Streetaddress1'];?>" type="text" style="width:100%;" />   
            </td>
            <td>&nbsp;</td>
        </tr>
        
        <tr>
            <td><label> Street Address 2:</label></td>
            <td> 
                <input class="inputsapp" name="Streetaddress2" onblur="sendtoSession();" value="<?php echo $_SESSION['OrderDataInRequest']['Streetaddress2'];?>" type="text" style="width:100%;" />   
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><label> City:</label></td>
            <td> 
                <input class="inputsapp required" name="Bcity" onblur="sendtoSession();" value="<?php echo $_SESSION['OrderDataInRequest']['Bcity'];?>" type="text" style="width:100%;" />   
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><label>State:</label></td>
            <td> 
                <select name="Bstate" class="product required" id="Bstate" onchange="sendtoSession();">
                    <option value="USA"<?php if($Client_GYBData_array[0]['GYB_State']==''){echo "selected";}?>>Please Select One</option>
                    <?php foreach($States as $State){?>
                    <option value="<?php echo $State['state_name']; ?>"<?php if($State['state_name'] == $_SESSION['OrderDataInRequest']['Bstate']){ echo "selected";}?>><?php echo $State['state_name'];?></option>
                    <?php } ?>
                </select>
            	
                <!--<input class="inputsapp required" name="Bstate" onblur="sendtoSession();" value="<?php echo $_SESSION['OrderDataInRequest']['Bstate'];?>" type="text" style="width:100%;" />-->            </td>
            <td>&nbsp;</td>
        </tr>
    <tr>
        <td>
        	<label>Postal Code:</label>
        </td>
        	<td> 
        		<input class="inputsapp required" onblur="sendtoSession();" name="Bpostalcode" value="<?php echo $_SESSION['OrderDataInRequest']['Bpostalcode'];?>" type="text" style="width:100%;" />   
        	</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><label>Country:</label></td>
        <td> 
            <select class="product" name="BillingCountry">
            	<option value="US">USA</option>
            </select>   
        </td>
    	<td>&nbsp;</td>
    </tr>
    <tr >
    	<td colspan="3" id="tabsubheading">Payment Information</td>
    </tr>
    <tr>
    	<td colspan="3">&nbsp;</td>
    </tr>
    <tr>
    	<td>Select Payment Method:</td>
        <td>
        	<select name="select_pay_process" class="product required" id="select_pay_process" onchange="sendtoSession();">
            	<option value="">Select Payment Type</option>
                <option value="1" <?php if($_SESSION['OrderDataInRequest']['select_pay_process'] == "1" ) echo"selected"; ?>>Credit Card (Process Now)</option>
                <option value="2" <?php if($_SESSION['OrderDataInRequest']['select_pay_process'] == "2" ) echo"selected"; ?>>Credit Card (Process Later)</option>
                <option value="3" <?php if($_SESSION['OrderDataInRequest']['select_pay_process'] == "3" ) echo"selected"; ?>>eCheck</option>
                <option value="4" <?php if($_SESSION['OrderDataInRequest']['select_pay_process'] == "4" ) echo"selected"; ?>>Create Order & Client Only</option>
                <!--<option value="5">Check</option>
                <option value="6">Cash</option>
                <option value="7">Money Order</option>
                <option value="8">Adjustment</option>
                <option value="9">Credit</option>
                <option value="10">Refund</option>
                <option value="11">Write Off</option>-->
            </select>
        </td>
        <td>&nbsp;</td>
    </tr>
    
    <tr class="process_later">
        <td width="30%">Process Later Date:</td>
        <td width="65%">
            <input name="process_later_date" onblur="sendtoSession();" value="<?php echo $_SESSION['OrderDataInRequest']['process_later_date'];?>" type="text" class="inputsapp required" id="process_later_date" /></td>
        <td width="30%">&nbsp;</td>
    </tr>
<tr class="creditcarddetails">
    <td>Credit Card Type:</td>
        <td>
            <select name="creditcardtype" id="creditcardtype" class="product required" onchange="sendtoSession();">
                <option value="">Select Card Type</option>
                <option value="American Express" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "American Express" ) echo "selected"; ?> >American Express</option>
                <option value="Master Card" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "Master Card" ) echo "selected"; ?> >Master Card</option>
                <option value="Visa" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "Visa" ) echo "selected"; ?>>Visa</option>
                <!--<option value="JCB" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "JCB" ) echo "selected"; ?>>Japan Credit Bureau</option>-->
                <option value="Discover" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "Discover" ) echo "selected"; ?>>Discover</option>
            </select>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="creditcarddetails">
        <td width="30%">Credit Card Number:</td>
        <td width="65%">           
        	<input type="text"class="inputsapp required" onblur="sendtoSession();" value="<?php echo $_SESSION['OrderDataInRequest']['Cnumber']; ?>" name="Cnumber" id="Cnumber" >
        </td>

        <td width="30%">&nbsp;</td>
    </tr>
    <tr class="creditcarddetails">
        <td>Expiration Date:</td>
        <td>
        <select name="ExpiryMonth" id="ExpiryMonth" class="product required" style="margin-bottom:5px;" onchange="sendtoSession();">
            <option value="">Select Expiry Month</option>
            <option value="01" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "01" ) echo"selected"; ?>>01 January</option>
            <option value="02" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "02" ) echo"selected"; ?>>02 February</option>
            <option value="03" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "03" ) echo"selected"; ?>>03 March</option>
            <option value="04" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "04" ) echo"selected"; ?>>04 April</option>
            <option value="05" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "05" ) echo"selected"; ?>>05 May</option>
            <option value="06" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "06" ) echo"selected"; ?>>06 June</option>
            <option value="07" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "07" ) echo"selected"; ?>>07 July</option>
            <option value="08" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "08" ) echo"selected"; ?>>08 August </option>
            <option value="09" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "09" ) echo"selected"; ?>>09 September</option>
            <option value="10" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "10" ) echo"selected"; ?>>10 October</option>
            <option value="11" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "11" ) echo"selected"; ?>>11 November</option>
            <option value="12" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "12" ) echo"selected"; ?>>12 December</option>
        </select>
        
        <select name="ExpiryYears" id="ExpiryYear" class="product required" onchange="sendtoSession();">
            <option value="">Select Expiry Year</option>
            <option value="2010" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2010" )echo"selected"; ?>>2010 </option>
            <option value="2011" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2011" )echo"selected"; ?>>2011 </option>
            <option value="2012" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2012" )echo"selected"; ?>>2012 </option>
            <option value="2013" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2013" )echo"selected"; ?>>2013 </option>
            <option value="2014" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2014" )echo"selected"; ?>>2014 </option>
            <option value="2015" <?php if(empty($_SESSION['OrderDataInRequest']) || $_SESSION['OrderDataInRequest']['ExpiryYears'] == "2015" ) echo"selected"; ?>>2015 </option>
            <option value="2016" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2016" )echo"selected"; ?>>2016 </option>
            <option value="2017" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2017" )echo"selected"; ?>>2017 </option>
            <option value="2018" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2018" )echo"selected"; ?>>2018 </option>
            <option value="2019" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2019" )echo"selected"; ?>>2019 </option>
            <option value="2020" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2020" )echo"selected"; ?>>2020 </option>
        </select>
        </td>
        <td>&nbsp;</td>
    </tr>
    
    <tr class="creditcarddetails">
        <td>Security Code:</td>
        <td> 
            <input value="<?php echo $_SESSION['OrderDataInRequest']['Ccode'];?>" name="Ccode" type="text" class="product required" id="Ccode" onblur="sendtoSession();"/> 
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="echeck">
        <td>Bank Name:</td>
        <td><input value="<?php echo $_SESSION['OrderDataInRequest']['bank_name']; ?>" name="bank_name" type="text" class="product required" onblur="sendtoSession();"/></td>
        <td>&nbsp;</td>
    </tr>
    <tr class="echeck">
        <td>Name On The Account:</td>
        <td><input value="<?php echo $_SESSION['OrderDataInRequest']['name_on_account']; ?>" name="name_on_account" type="text" class="product required" onblur="sendtoSession();"/></td>
        <td>&nbsp;</td>
    </tr>
    
    <tr class="echeck">
        <td>Account Holder Type:</td>
        <td>
            <select class="product required" name="account_holder_type" id="account_holder_type" onchange="sendtoSession();">
                <option value="">Select Account Holder Type</option>
                <option value="business" <?php if($_SESSION['OrderDataInRequest']['account_holder_type'] == "business") echo "selected"; ?>>Business</option>
                <option value="personal" <?php if($_SESSION['OrderDataInRequest']['account_holder_type'] == "personal") echo "selected"; ?>>Personal</option>
            </select> 
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="echeck">
        <td>Account Type:</td>
        <td>
            <select name="account_type" id="account_type" class="product required" onchange="sendtoSession();">
                <option value="">Select Account Type</option>
                <option value="business_checking" <?php if($_SESSION['OrderDataInRequest']['account_type'] == "business_checking") echo "selected"; ?>>Business Checking</option>
                <option value="business_savings" <?php if($_SESSION['OrderDataInRequest']['account_type'] == "business_savings") echo "selected"; ?>>Business Savings</option>
                 <option value="personal_checking" <?php if($_SESSION['OrderDataInRequest']['account_type'] == "personal_checking") echo "selected"; ?>>Personal Checking</option>
                <option value="personal_savings" <?php if($_SESSION['OrderDataInRequest']['account_type'] == "personal_savings") echo "selected"; ?>>Personal Savings</option>
            </select>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="echeck">
        <td>Check Type:</td>
        <td>
            <select name="check_type" id="check_type" class="product required" onchange="sendtoSession();">
                <option value="">Select Check Type</option>
                <option value="ARC" <?php if($_SESSION['OrderDataInRequest']['check_type'] == "ARC") echo "selected"; ?>>ARC - Accounts Receivable Conversion</option>
                <option value="BOC" <?php if($_SESSION['OrderDataInRequest']['check_type'] == "BOC") echo "selected"; ?>>BOC - Back Office Conversion</option>
                <option value="CCD" <?php if($_SESSION['OrderDataInRequest']['check_type'] == "CCD") echo "selected"; ?>>CCD - Cash Concentration or Disbursement</option>
                <option value="PPD" <?php if($_SESSION['OrderDataInRequest']['check_type'] == "PPD") echo "selected"; ?>>PPD - Prearranged Payment and Deposit Entry</option>
                <option value="TEL" <?php if($_SESSION['OrderDataInRequest']['check_type'] == "TEL") echo "selected"; ?>>TEL - Telephone-Initiated Entry</option>
                <option value="WEB" <?php if($_SESSION['OrderDataInRequest']['check_type'] == "WEB") echo "selected"; ?>>WEB - Internet-Initiated Entry</option>
            </select>
        </td>
    <td>&nbsp;</td>
    </tr>
    
    <tr class="echeck">
        <td>Check Number:</td>
        <td><input value="<?php echo $_SESSION['OrderDataInRequest']['check_number']; ?>" name="check_number" onblur="sendtoSession();" type="text" class="product required"/></td>
        <td>&nbsp;</td>
    </tr>
    <tr class="echeck">
        <td>Routing Number:</td>
        <td><input value="<?php echo $_SESSION['OrderDataInRequest']['routing_number']; ?>" name="routing_number" onblur="sendtoSession();" maxlength="9" type="text" class="product required"/></td>
        <td>&nbsp;</td>
    </tr>
    <tr class="echeck">
        <td>Account Number:</td>
        <td><input value="<?php echo $_SESSION['OrderDataInRequest']['account_number']; ?>" name="account_number" onblur="sendtoSession();" type="text" class="product required"/></td>
        <td>&nbsp;</td>
    </tr>
    
    
    
    
    <tr>
    	<td>Notes:</td>
        <td><textarea name="paymentnoteswhileorder" rows="5" style="width:59.8%;" onblur="sendtoSession();"><?php echo $_SESSION['OrderDataInRequest']['paymentnoteswhileorder']; ?></textarea></td>
        <td>&nbsp;<input type="hidden" name="voidtransaction" value="" id="voidtransaction"></td>
    </tr>
    <tr>
    	<td>Send Email/Invoice Now:</td>
        <td><input type="checkbox" checked="checked" name="send_email_invoice"></td>
        <td>&nbsp;</td>
    </tr>
</table>
        
        <div style="height:25px;">&nbsp;</div>
        <div class="submit_holder_div">
        <?php 
		if($_SESSION['Member']['CompanyID']=="1"){
			?>
        <div style="float:left">
        	<span class="blink_me" style="color: red; margin-right: 10px; line-height: 35px;">DEMO</span>
        </div>
        <?php } ?>
        
            <div style="float:left;">
                <input type="submit" name="PurchaseNow" value="Place Order Now" onclick="return Validatetheform();" class="button_success">
            </div>
            <div style="float: left; display: none;" class="loading_icon_order">
                <img src="../images/loading.gif" style="margin: 4px 0px 0px 10px;">
            </div>
<input type="hidden" name="oldimage" value="">
<input type="hidden" name="postback" value="1">
<input type="hidden" name="browser_details" id="browser_details" value="" />
<input type="hidden" name="start_time" id="start_time" value="<?php if(!empty($_SESSION['OrderDataInRequest']['start_time'])) { echo $_SESSION['OrderDataInRequest']['start_time']; } ?>" />
<input type="hidden" name="end_time" id="end_time" value="" />
<input type="hidden" name="total_time_spend" id="total_time_spend" value="" />
        </div>
    </form>
    </div>
<div id="tabs-payment">
<?php 
if($_REQUEST['flag'] == 'email_exist_error'){ ?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr valign="top">
        	<td ><div class="message_error" style="width:95%"><?php echo $_REQUEST['ResponseMessage'];?> </div> </td>
        </tr>
    </table>
<?php } 
if($_REQUEST['flag'] == 'card_faild_error'){?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr valign="top">
        	<td ><div class="message_error" style="width:95%"><?php echo $_REQUEST['ResponseMessage'];?> </div> </td>
        </tr>
    </table>
<?php }
if($_REQUEST['flag'] == 'client_add'){?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr valign="top">
            <td ><div class="message_success"  style="width:95%"><?php echo $_REQUEST['ResponseMessage'];?> </div> </td>
        </tr>
    </table>
<?php 
} 
?>

    <div align="center" style="background: none; bottom: 5px; left: 0; padding: 2px 0; width: 100%;">
        <a href="AddClients.php" id="goback">
            <input type="button" value="Go Back" onclick="selectfirst_tab();" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"  />
        </a>
    </div>

</div>
       
</div>

<script type="text/javascript">
<?php
$tab_activated = 0;
if(isset($_REQUEST['tab'])){
    $tab_activated = 1;
}
?>
$(function() {
    $( "#tabs" ).tabs({active: <?php echo $tab_activated;?> });
});

$("#hidecreditcarddetails").click(function(){
	if($("#hidecreditcarddetails").is(":checked")){
		$(".creditcarddetails").hide("fast");
	}else{
		$(".creditcarddetails").show("fast");
	}
});

$(document).ready(function(){
	$(".creditcarddetails").hide();
	$(".echeck, .process_later").hide();
	$("#voidtransaction").val(1);
	
	// added 5/7/14 for cc type dropdown
	$("#creditcardtype").change(function(){
		if($(this).val()=="American Express") {
			$("#Cnumber").attr("placeholder", "xxxx-xxxxxx-xxxxx");
		} else {
			$("#Cnumber").attr("placeholder", "xxxx-xxxx-xxxx-xxxx");	
		}
	});
	
	$("#select_pay_process").change(function(){
		var selected = $(this).val();
		if(selected == 1 || selected == 2){
			if(selected == 2){
				$("#voidtransaction").val(1);
				$(".process_later").show("fast");
			}else{
				$("#voidtransaction").val(0);
				$(".process_later").hide("fast");
			}
			$(".creditcarddetails").show("fast");
		}else{
			$(".creditcarddetails").hide("fast");
			$(".process_later").hide("fast");
		}
		if(selected == 3){
			$(".echeck").show("fast");
		}else{
			$(".echeck").hide("fast");
		}
		/*
		   Whether Process Later is selected or Create Order/ Client Only is selected 
		   Turn on the Void transaction. Which avoids the payment gateway
		*/
		if(selected == 4 || selected == 2){
			$("#voidtransaction").val(1);
		}		
	});
	$("#select_pay_process").trigger("change");
});

function placeinhidden(){
	var cnum = $.trim($("#Cnumber").val());
	cnum = cnum.replace(/-/g, "");
	$("#hidden_Cnumber").val(cnum);
	sendtoSession();
}

$(".changeccnumreadonly").click(function(){
	var realval = $.trim($("#hidden_Cnumber").val());
	$("#Cnumber").attr("disabled",false).val("").val(realval);
	$("#Cnumber").val($("#Cnumber").val().toCardFormat());
	$(".changeccnumreadonly").fadeOut(200);
});

function sendtoSession(){
	var str = $("#orderform" ).serialize();
	$.ajax({
		type    : 'post',
		url     : '<?php echo SITE_ADDRESS; ?>ajax/AllAjaxCalls.php',
		data    : 'Task=AddAllToSession&AllData='+str,
		success : function(data){
		}	
	});
}


String.prototype.toCardFormat = function () {
	if(document.getElementById('creditcardtype').value=="American Express"){
		//alert("Amex");
		//console.log("iteration");
		return this.replace(/[^0-9]/g, "").substr(0, 15).split("").reduce(cardFormatAmex, "");
	} else {
		return this.replace(/[^0-9]/g, "").substr(0, 16).split("").reduce(cardFormat, "");
	}
    
    
	function cardFormat(str, l, i) {
        return str + ((!i || (i % 4)) ? "" : "-") + l;
    }
	
	function cardFormatAmex(str, l, i) {
		//console.log(str + " at " + i  + " and l: "+l);
		if(i==4 || i==10){
			return str + "-" + l;
		} else {
			return str + "" + l;	
		}
        //return str + ((!i || (i % 4)) ? "" : "-") + l;
    }
};

$(document).ready(function(){
    $("#Cnumber").keyup(function () {
        $(this).val($(this).val().toCardFormat());
    });
});

</script>