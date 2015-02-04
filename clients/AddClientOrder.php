<?php
include "../lib/include.php";
//include_once(dirname(__FILE__).'/../lib/classes/infusionData.php');
//$infObject = new iNFUSION();
$objpackges = new Packges();
$objorder = new Orders();
$objproducts = new Products();
$objClient = new Clients();
$objorderform = new OrderForm();
$ObjMerchantAccount = new MerchantAccount();
$ObjGroup = new Groups();
$objuser = new Users();
$objCompany=new Company();
$CompanyID = $objuser->GetAllUsers("Users.ID = '".$_SESSION['Member']['ID']."'",array("CompanyID")); 
$MerchantID=$objCompany->GetAllCompany("ID='".$CompanyID[0]['CompanyID']."'",array("MerchantID"));

$ObjPromotionalCode = new PromotionalCode();
if($_REQUEST['Task']=='RemovePackage'){
    $key = array_search($_REQUEST['package_id'], $_SESSION['OrderData']['Packages']);
    if (false !== $key) {
        unset($_SESSION['OrderData']['Packages'][$key]);
		header("location:AddClientOrder.php?flag=package_remove&id=".$_REQUEST['id']);
    }
 }
 if($_REQUEST['Task']=='RemoveProduct'){
    $key = array_search($_REQUEST['product_id'], $_SESSION['OrderData']['Products']);
    if (false !== $key) {
        unset($_SESSION['OrderData']['Products'][$key]);
		header("location:AddClientOrder.php?flag=product_remove&id=".$_REQUEST['id']);
    }
 }
if($_REQUEST['Task'] == 'AddToOrder'){
    $_SESSION['OrderData']['Packages'][]  =   $_REQUEST['PackageID'];
    $_SESSION['OrderData']['Products'][]  =   $_REQUEST['ProductID'];
}
if(isset($_REQUEST['CalculateTotal'])){
    $_SESSION['product_array_prices_package'] = $_REQUEST['product_array_prices_package'];
    $_SESSION['product_array_promos_package'] = $_REQUEST['product_array_promos_package'];
    $_SESSION['product_array_qty_package']=$_REQUEST['product_array_qty_package'];
    $_SESSION['product_array_promos'] = $_REQUEST['product_array_promos'];
    $_SESSION['product_array_prices'] = $_REQUEST['product_array_prices']; 
    $_SESSION['product_array_qty']=$_REQUEST['product_array_qty'];
    
}
$Packge_array =   $objpackges->GetAllPackges(" ShowOnOrderForm = 1 ORDER BY PackagesTitle ASC",array("*"));
if(isset($_REQUEST['PurchaseNow'])){
     if(!empty($_REQUEST['createddate'])){
        $createddate= date("Y-m-d",strtotime($_REQUEST['createddate']));
        }else{
            $createddate ="";
            
        }
     if(!empty($_REQUEST['BestCallTime'])){
        $BestCallTime = date("Y-m-d ",strtotime($_REQUEST['BestCallTime'])).$_REQUEST['hour'].":".$_REQUEST['minuts'].":00";
        }else{
            $BestCallTime=""; 
        }
	
        // first authenticate the cc info
        //$OrderFormMerchantData=$objorderform->GetAllOrderFormData("ID = $order_form_id",array("MerchantAccID"));
        $ObjMerchantAccount = new MerchantAccount();
        $MerchantData = $ObjMerchantAccount->GetAllMerchantAccount("MerchantId = '".$MerchantID[0]['MerchantID']."'",array("*"));
		
        $merchantRecords = $MerchantData[0];
        if(empty($MerchantData[0])){
            $ResponseMessage = "No Merchant selected for Credit Card Payments!";
            header("location:AddClientOrder.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']); 
        }
       
        $merchant_to_charge = $MerchantData[0]['AccountType'];
        
        $LoginID=trim($merchantRecords['LoginID']);
        $TransKey=trim($merchantRecords['TransactionKey']);
        $SandBox = true;
        // get the invoice number for order agent-client-number
        $invoice_no = $_SESSION['Member']['ID']."-".time();
        $_SESSION['invoice']=$invoice_no;
        if($merchantRecords['Mode']=='-1')
           $SandBox=false;
       else
           $SandBox=true;
        require_once('../ecommerce/products/AuthorizeNet-sdk/AuthorizeNet.php'); 
	
        switch($merchant_to_charge){
            case "14":
                 //EDP
                 require_once('ChargeOnGWAPI.php');
                break;
            default:
                // authorize.net code will go there
                 require_once('ChargeOnAuthNetAPI.php');
                break;
        }
        
        if ($order_charged == 1) {
            require_once('SendInvoice.php');
            //$objClient->InvoiceMail($inserted_order);  
            unset($_SESSION['OrderData']['Products']);
            // get an available website and assigned that to member if he has orderd the website
            //$objWebSites = new WebSites();
            //$IsAnySystemAvailableArray = $objWebSites->GetAllWebsites(WEBSITES.".Status = 1 ",array("*"));
            //$objWebSites->UpdateWebsite("ID = '".$IsAnySystemAvailableArray[0]['ID']."'",array("MemberID"=>$added_member_id));
            $ResponseMessage = "Success! Credit card has been charged! Transaction ID: " . $transaction_id;
            header("location:AddClientOrder.php?tab=1&flag=client_add&tras_approved=1&invoice_email_sent=$invoice_email_sent&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);  
            unset($_SESSION['OrderDataInRequest']);
        }else{
            //print_r($_REQUEST);
              $ResponseMessage = " System Error! <br>Credit Card was not charged.";
              $_SESSION['OrderDataInRequest'] = $_REQUEST;
              header("location:AddClientOrder.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
        }
      
}


$products_array = $objproducts->GetAllProduct(" ShowOnOrderForm = 1 ",array("ID,ProductName,ProductPrice,Description"));
//echo $response->response_reason_text;
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>

<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>
 <script type="text/javascript">
function confirmation() {
	var answer = confirm("Do you want to Remove this One?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
</script>
 <script>
function validateForm()
{

  
  var x=document.forms["myForm"]["fName"].value;
if (x==null || x=="")
  {
  alert("Please Enter The First Name");
  document.forms.myForm.fName.focus();
  return false;
  }
   var x=document.forms["myForm"]["phone"].value;
if (x==null || x=="")
  {
  alert("Please Enter The Phone Number ");
  document.forms.myForm.phone.focus();
  return false;
  }
var x=document.forms["myForm"]["Email"].value;
var atpos=x.indexOf("@");
var dotpos=x.lastIndexOf(".");
if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
  {
  alert("Please Enter The valid Email Address");
  document.forms.myForm.Email.focus();
  return false;
  }
  var x=document.forms["myForm"]["Streetaddress1"].value;
if (x==null || x=="")
  {
  alert("Please Enter The Street Address ");
  document.forms.myForm.Streetaddress1.focus();
  return false;
  }
   var x=document.forms["myForm"]["Bcity"].value;
if (x==null || x=="")
  {
  alert("Please Enter The City Name");
  document.forms.myForm.Bcity.focus();
  return false;
  }
  var x=document.forms["myForm"]["Bstate"].value;
if (x==null || x=="")
  {
  alert("Please Enter The State Name");
  document.forms.myForm.Bstate.focus();
  return false;
  }
  
  
}
</script> 
<script type="text/javascript" language="javascript">
function ValidateForm(){
//return true;
if ((document.frmSample.cname.value==null)||(document.frmSample.cname.value=="")){
		alert("Please Enter Business Name")
		document.frmSample.cname.focus()
		return false;
	}
 if ((document.frmSample.fName.value==null)||(document.frmSample.fName.value=="")){
		alert("Please Enter First Name")
		document.frmSample.fName.focus()
		return false;
	}
 if ((document.frmSample.sureName.value==null)||(document.frmSample.sureName.value=="")){
		alert("Please Enter Last Name")
		document.frmSample.sureName.focus()
		return false;
	}

}
</script>
<script>
  $(document).ready(function() {
    
	$("#timepicker1").datepicker();

  });

  </script>	
<script>
function PaymentMethod(TypeID)
  {
  //alert(TypeID);
  //document.getElementById(visacard).style.visibility= 'visible' ;
  if(TypeID === "CredetCard"){
           document.getElementById("visacard").style.visibility = 'visible' ;
           document.getElementById("visacard").style.height = 'auto' ;
	   	   document.getElementById("cheque").style.visibility = 'hidden' ;
            document.getElementById("cheque").style.height = '0px' ;
  }else{
            document.getElementById("cheque").style.visibility = 'visible' ;
            document.getElementById("cheque").style.height = 'auto' ;
            document.getElementById("visacard").style.visibility = 'hidden' ;
            document.getElementById("visacard").style.height = '0px' ;
  }
  }
</script>		
<script>
function goBack(){
    
    location.reload();
	<?php
	$tab_activated = 1;
	 if(isset($_REQUEST['tab'])){
    $tab_activated = 0;
}?>
 }
</script>
<script type="text/javascript">
		 $(function() {
		   $("#goback").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
					}
					</script>

    <?php if($_REQUEST['flag']=='product_remove'){?>
   <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
    <tr>
  <td align="left" colspan="3" id="message_success">
      &nbsp;&nbsp;Product Removed successfully!
	   <script type="text/javascript">
            $(document).ready(function(){

                    $("#message_success").fadeOut(3000);
              });
</script>
    </td>
    
  </tr>
   </table>
   <?php }?>
       <?php if($_REQUEST['flag']=='payment_gatway_error'){?>
   <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
    <tr>
  <td align="left" colspan="3" id="message_error">
      &nbsp;&nbsp;Please Select Payment Method!
	   <script type="text/javascript">
            $(document).ready(function(){

                    $("#message_success").fadeOut(3000);
              });
</script>
    </td>
    
  </tr>
   </table>
   <?php }?>                                    
   <div id="tabs">
<ul>

<li><a href="#tabs-order">Order Form </a></li>
<li><a href="#tabs-payment">Response</a></li>
</ul>
<div id="tabs-order">

 
<form action="?Task=AddToOrder" method="post" target="_self" enctype="multipart/form-data" name="frmSample">

<table width="100%" border="0" cellspacing="0" cellpadding="2"align="center">

<tr >
<td id="tdleft"><select name="PackageID" class="dropdwn" >
        <option value="1">Select Package</option>
        <?php  foreach ((array)$Packge_array as $packages){?>
        <option value="<?php echo $packages['ID'];?>"><?php echo $packages['PackagesTitle'];?></option>
        <?php } ?>
    </select>
	</td>
 <td id="tdmiddle">
     <select name="ProductID" class="dropdwn">
        <option value="">Select Product</option>
        <?php foreach ((array)$products_array as $products_row){?>
        <option value="<?php echo $products_row['ID'];?>"><?php echo $products_row['ProductName'];?></option>
		  
		  
        <?php } ?>
    </select>
	&nbsp;
  </td>
  <td id="tdright"> <div align="right"><input type="submit" name="Submit" value="Add To Order" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="Addnew"/></div>
     
  </td>
</tr>
</table>
<input type="hidden" name="id"  value="<?php echo $_REQUEST['id'];?>"/>
</form>

<form action="?Task=UpdateOrder" method="post" target="_self"  name="myForm">
<input type="hidden" name="id"  value="<?php echo $_REQUEST['id'];?>"/>
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
	$flag=0; 
	if($flag==0){
            $flag=1;
            $row_class="row-white";
        }else{
            $flag=0;
            $row_class = "row-tan";
        }
   
	$total=0;
	$PromoCodesArray = $ObjPromotionalCode->GetAllPromotionalCodes(" HasDeleted = 0 AND PromosShowsOnOrderForm = 1 ",array("*"));
	foreach((array)$_SESSION['OrderData']['Packages'] as $package_id){
        if( $package_id > 0  ){
            $package_products = $objpackges->GetAllProductToPackges("PackagesID ='$package_id'",array("*"));
			//print_r($package_products);
            $Packge_Data =   $objpackges->GetAllPackges(" ID = '$package_id' ORDER BY PackagesTitle ASC",array("*"));
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
        <?PHP
		$product_price=0;
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
        
        
        $product_price=$current_product_price*$qty - $promo;
	
	 	$subtotal=$subtotal+$product_price;
	
	?>
	
	<tr id="<?php echo $row_class;?>">
	 <td><?php echo $package_products[0]['ProductName'];?></td>
	 <td>
	 <?php if($package_products[0]['ShowCoupn']== 1){?>
	 <select name="product_array_promos_package[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID'];?>]" class="dropdwn">
       <option value="0" <?php if($promo == 0)echo"selected";?>>Select One</option>
       <?php foreach((array)$PromoCodesArray as $promo_row){?>
       <option value="<?php echo $promo_row['Price'];?>" <?php if($promo_row['Price'] == $promo)echo"selected";?>><?php echo $promo_row['Category_Name'];?></option>
       <?php } ?>
     </select>
	 <?php } ?>
	 </td>
	 <td>$
         <?php
            $price_array = $objproducts->GetAllProductPrice(" ProductID = ".$product_row['ProductID'],array("*"));
			$singleprice=count($price_array);
			if($singleprice>1){
            ?>
              <select name="product_array_prices_package[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID']; ?>]" class="dropdwn">

            <?php
              foreach((array)$price_array as $price){?>
                    <option value="<?php echo $price['ProductPrice'];?>" <?php if($price['ProductPrice']== $current_product_price){echo " selected ";} ?>><?php echo number_format($price['ProductPrice'],2);?></option>
             <?php } ?>
             </select>
			 <?php } else{ echo number_format($price_array[0]['ProductPrice'],2);}?>         </td>
	 <td><input type="text" name="product_array_qty_package[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID'];?>]"value="<?php echo $qty;?>" size="3"/></td>
	 <td>$<?php echo number_format($product_price,2);?></td>
         <td>
             <input type="hidden" name="product_array_name[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID'];?>]" value="<?php echo $package_products[0]['ProductName'];?>" />
            <input type="hidden" name="product_array_price[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID'];?>]" value="<?php echo number_format($package_products[0]['ProductPrice'],2);?>" />
            <input type="hidden" name="product_array_id[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID'];?>]" value="<?php echo $product_row['ProductID'];?>" />
            <input type="hidden" name="product_array_descrption[<?php echo $package_id; ?>][<?php echo $package_products[0]['ID'];?>]" value="<?php echo $package_products[0]['Description'];?>" />        </td>
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
		 $total=$subtotal+$total;
                 $subtotal = 0;
		}
       }
      
	  } 
       ?>
	  
        <tr>
            <td colspan="6">
                <hr/>            </td>
        </tr>
        <tr id="package-heading">
             <td>Products</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
        </tr>
		<?PHP
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
            
            
            $product_price =$current_product_price*$qty;
            $product_price =$product_price - $promo;
            //$sum   =$total;
            
            ?>
	         <tr id="<?php echo $row_class;?>">
             <td><?php echo $package_products[0]['ProductName'];?></td>
             <td>
			 <?php if($package_products[0]['ShowCoupn']== 1){?>
			 
			 <select name="product_array_promos[<?php echo $product_id; ?>]" class="dropdwn">
               <option value="0" <?php if($promo == 0)echo"selected";?>>Select One</option>
               <?php foreach((array)$PromoCodesArray as $promo_row){?>
               <option value="<?php echo $promo_row['Price'];?>" <?php if($promo_row['Price'] == $promo)echo"selected";?>><?php echo $promo_row['Category_Name'];?></option>
               <?php } ?>
             </select>
			 <?php } ?>			 </td>
             <td>$ <?php
			$price_array = $objproducts->GetAllProductPrice(" ProductID = $product_id",array("*"));
			 $singleprice=count($price_array);
			if($singleprice>1){
			//print_r($price_array);
			?>
			  <select name="product_array_prices[<?php echo $product_id; ?>]" class="dropdwn">

			<?php
			  foreach((array)$price_array as $price){?>
				<option value="<?php echo $price['ProductPrice'];?>" <?php if($price['ProductPrice']== $current_product_price){echo " selected ";} ?>><?php echo number_format($price['ProductPrice'],2);?></option>
			 <?php } ?>
			 </select>
			<?php } else{ echo number_format($price_array[0]['ProductPrice'],2);}?>			 </td>
             <td><input type="text" name="product_array_qty[<?php echo $product_id;?>]"value="<?php echo $qty;?>" size="3"/></td>
             <td>$<?php echo number_format($product_price,2);?></td>
             <td align="center">
                 <input type="hidden" name="product_array_id_for_product[<?php echo $product_id;?>]" value="<?php echo $product_id;?>" />
                 <input type="hidden" name="product_array_price[<?php echo $product_id;?>]" value="<?php echo number_format($product_price,2);?>" />
                 <input type="hidden" name="product_array_name[<?php echo $product_id;?>]" value="<?php echo $package_products[0]['ProductName'];?>" />
                 <a href="?Task=RemoveProduct&product_id=<?php echo $product_id;?>&id=<?php echo $_REQUEST['id'];?>"><img border="0" src="../images/icon_delete.png" title="Delete product or package" onclick="return confirmation();"></a></td>
            </tr>
            <?php 
			$total = $total+$product_price;
			$productTotal=$productTotal+$product_price;
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
<td align="center">$<?php echo number_format($total,2);?></td>
</tr>

<?php  } ?>	
<input type="hidden" name="totalprice"  value="<?php echo $total;?>"/>
<tr>
<td align="right" colspan="6" valign="bottom">
<div align="right">
<input type="submit" name="CalculateTotal" value="Calculate Total"class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"   /><input type="hidden" name="addorder"  value="1"/>
<input type="hidden" name="clientorder"  value="1"/>
<input type="hidden" name="id"  value="<?php echo $_REQUEST['id'];?>"/>
</div></td>
</tr>			
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<?php
 $objClient = new Clients();
 $CleintArray = $objClient->GetAllClients(CLIENTS.".ID = ".$_REQUEST['id'],array(CLIENTS.".*"));
 //print_r($CleintArray[0]); 
?>
<table width="100%"  border="0">
<tr >
    <td colspan="3" id="tabsubheading"> Billing Information</td>
</tr>
<tr>
   <td id="tdleft"> Business Name: </td>
   <td id="tdmiddle">
       <input class="inputsapp" name="cname" value="<?php echo $CleintArray[0]['CompanyName'];?>" type="text" style="width:100%;"/>
   </td>
   <td id="tdright" >&nbsp;</td>
</tr>

<tr valign="top">
   <td  width="30%"> First Name:</td>
   <td allign="left" width="40%">
       <input class="inputsapp" name="fName" value="<?php echo $CleintArray[0]['FirstName'];?>" type="text" style="width:100%;" id="fName" />
   </td>
   <td width="30%">&nbsp;</td>
</tr>
<tr valign="top">
  <td > Last Name:</td>
  <td allign="left">
      <input  class="inputsapp" name="sureName" value="<?php echo $CleintArray[0]['Surname'];?>" type="text" style="width:100%;" id="sureName" />
  </td>
  <td>&nbsp;</td>
</tr>

<tr valign="top">
  <td  >Customer Email:</td>
  <td allign="left">
      <input class="inputsapp" name="Email" value="<?php echo $CleintArray[0]['Email'];?>" type="text" style="width:100%;"  id="Email"/>
  </td>
  <td>&nbsp;</td>
</tr>
            
<tr>
  <td >Best Phone: </td>
  <td allign="left">
      <input class="inputsapp" name="phone" value="<?php echo $CleintArray[0]['Phone'];?>" type="text" style="width:100%;" id="phone"/>
  </td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td >Alternate Phone: </td>
  <td allign="left">
      <input  class="inputsapp"name="alternatephone" value="<?php echo $CleintArray[0]['AlternatePhone'];?>" type="text" style="width:100%;" />
  </td>
  <td>&nbsp;</td>
</tr>
<tr>
    <td><label> Others Notes:</label></td>
    <td> 
        <textarea class="inputsapp" name="OthersNotes"  rows="5" style="width:100%;"></textarea>   
    </td>
	<td>&nbsp;</td>
</tr>

<tr >
    <td colspan="3" id="tabsubheading"> Billing Address</td>
</tr>
<tr>
    <td><label> Street Address1:</label></td>
    <td> 
        <input class="inputsapp" name="Streetaddress1" value="<?php echo $CleintArray[0]['Address'];?>" type="text" style="width:100%;" />   
    </td>
	<td>&nbsp;</td>
</tr>

<tr>
    <td><label> Street Address 2:</label></td>
    <td> 
        <input class="inputsapp" name="Streetaddress2" value="<?php echo $CleintArray[0]['Address2'];?>" type="text" style="width:100%;" />   
    </td>
	<td>&nbsp;</td>
</tr>
<tr>
    <td><label> City:</label></td>
    <td> 
        <input class="inputsapp" name="Bcity" value="<?php echo $CleintArray[0]['City'];?>" type="text" style="width:100%;" />   
    </td>
	<td>&nbsp;</td>
</tr>
<tr>
    <td><label> State:</label></td>
    <td> 
        <input class="inputsapp" name="Bstate" value="<?php echo $CleintArray[0]['State'];?>" type="text" style="width:100%;" />   
    </td>
	<td>&nbsp;</td>
</tr>
<tr>
    <td><label> Postal Code:</label></td>
    <td> 
        <input class="inputsapp" name="Bpostalcode" value="<?php echo $CleintArray[0]['ZipCode'];?>" type="text" style="width:100%;" />   
    </td>
	<td>&nbsp;</td>
</tr>
<tr>
    <td><label> Country:</label></td>
    <td> 
        <select class="product" name="BillingCountry">
		<option value="US">USA</option>
		</select>   
    </td>
	<td>&nbsp;</td>
</tr>
<tr ><td colspan="3" id="tabsubheading"> Payment Information</td></tr>
<tr>
            <td width="30%">Credit Card Number:</td>
            <td width="40%"> <input name="Cnumber" value="<?php echo $_SESSION['OrderDataInRequest']['Cnumber'];?>" type="text" class="product" id="Cnumber" style="width:100%;" /></td>
	    <td width="30%">&nbsp;</td>
</tr>
<tr>
               <td>Expiration Date:</td>
               <td>
                <select name="ExpiryMonth" style="width:155px;" class="product">
                       <option value="01" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "01" )echo"selected"; ?>>January</option>
                       <option value="02" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "02" )echo"selected"; ?>>February</option>
                       <option value="03" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "03" )echo"selected"; ?>>March</option>
                       <option value="04" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "04" )echo"selected"; ?>>April</option>
                       <option value="05" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "05" )echo"selected"; ?>>May</option>
                       <option value="06" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "06" )echo"selected"; ?>>June</option>
                       <option value="07" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "07" )echo"selected"; ?>>July</option>
                       <option value="08" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "08" )echo"selected"; ?>>August </option>
                       <option value="09" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "09" )echo"selected"; ?>>September</option>
                       <option value="10" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "10" )echo"selected"; ?>>October</option>
                       <option value="11" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "11" )echo"selected"; ?>>November</option>
                       <option value="12" <?php if($_SESSION['OrderDataInRequest']['ExpiryMonth'] == "12" )echo"selected"; ?>>December</option>

               </select>


           <select name="ExpiryYears" style="width:155px;" class="product">
                         
                        <option value="2010" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2010" )echo"selected"; ?>>2010 </option>
                        <option value="2011" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2011" )echo"selected"; ?>>2011 </option>
                        <option value="2012" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2012" )echo"selected"; ?>>2012 </option>
                       <option value="2013" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2013" )echo"selected"; ?>>2013 </option>
                     
                       <option value="2014" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2014" )echo"selected"; ?>>2014 </option>
                       <option value="2015" <?php if(empty($_SESSION['OrderDataInRequest']) || $_SESSION['OrderDataInRequest']['ExpiryYears'] == "2015" )echo"selected"; ?>>2015 </option>
                       <option value="2016" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2016" )echo"selected"; ?>>2016 </option>
                       <option value="2017" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2017" )echo"selected"; ?>>2017 </option>
                       <option value="2018" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2018" )echo"selected"; ?>>2018 </option>
                       <option value="2019" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2019" )echo"selected"; ?>>2019 </option>
                       <option value="2020" <?php if($_SESSION['OrderDataInRequest']['ExpiryYears'] == "2020" )echo"selected"; ?>>2020 </option>

        </select>
		

       </td>
	   <td></td>
       </tr>



        <tr>
               <td>Credit Card Type:</td>
               <td> <select name="creditcardtype" class="product">
                       <option value="American Express" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "American Express" )echo"selected"; ?> >American Express</option>
                       <option value="American Express" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "Master Card" )echo"selected"; ?> >Master Card</option>
                       <option value="Visa" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "Visa" )echo"selected"; ?>>Visa</option>
                       <option value="JCB" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "JCB" )echo"selected"; ?>>Japan Credit Bureau</option>
                       <option value="Discover" <?php if($_SESSION['OrderDataInRequest']['creditcardtype'] == "Discover" )echo"selected"; ?>>Discover</option>
                    </select>
              </td>
			  <td>&nbsp;</td>
       </tr>
        

<tr>
		 	<td>Security Code:</td>
				<td> 
				 <input value="<?php echo $_SESSION['OrderDataInRequest']['Ccode'];?>" name="Ccode" type="text" class="product" id="Ccode"/> 
		        </td>
				<td>&nbsp;</td>
		</tr>
</table>
              
		</td>
 
</tr>
<tr valign="top">
<td align="center" colspan="3">
</td>

     </tr>
</table> 


<div style="height:25px;">&nbsp;</div>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
       <td align="center" colspan="3">
	<div align="center">
            <input type="submit" name="PurchaseNow" value="Place Order Now" onclick="return validateForm();"class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
            <input type="hidden" name="oldimage"  value=""/>
	<input type="hidden" name="postback" value="1" />
	</div>
	</td>
    </tr>
	</table>
</form>

</div>

<div id="tabs-payment">
<?php 
//print_r($_REQUEST);
if($_REQUEST['flag']=='email_exist_error'){?>

        
                <table width="100%" border="0" cellspacing="1" cellpadding="1">

                <tr valign="top">
                <td ><div id="message_error"   style="width:95%"><?php echo $_REQUEST['ResponseMessage'];?> </div> </td>

                </tr>
                </table>
<?php }  ?>
<?php 
if($_REQUEST['flag']=='card_faild_error'){?>

        
                <table width="100%" border="0" cellspacing="1" cellpadding="1">

                <tr valign="top">
                <td ><div id="message_error"   style="width:95%"><?php echo $_REQUEST['ResponseMessage'];?> </div> </td>

                </tr>
                </table>
<?php }  ?>
<?php if($_REQUEST['flag']=='client_add'){?>
                <table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr valign="top">
                <td ><div id="message_success"  style="width:95%"><?php echo $_REQUEST['ResponseMessage'];?> </div> </td>

                </tr>
                </table>
        <?php   
                
                } 
            
        ?>
  <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
                <tr valign="top">
				<td colspan="2" align="center">
				<div align="center">
 <a href="AddClients.php" id="goback"><input type="button" name="Submit" value="Go Back" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"  />
 </a></div>
  
</td>
</tr>
</table>
</div>
       
</div>

<script>
<?php
$tab_activated = 0;
if(isset($_REQUEST['tab'])){
    $tab_activated = 1;
}
?>
$(function() {
    $( "#tabs" ).tabs({active: <?php echo $tab_activated;?> });
    
});
</script>