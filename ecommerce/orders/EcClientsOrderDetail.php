<?php
session_start();
include "../../lib/include.php";
require_once('../../ecommerce/products/AuthorizeNet-sdk/AuthorizeNet.php'); 
$objpackges = new Packges();
$objorder = new Orders();
$objproducts = new Products();
$objClient = new Clients();
$ObjMerchantAccount = new MerchantAccount();
$ObjGroup = new Groups();
$Group_ID = $ObjGroup->GetMemberGroups("UserID=".$_SESSION['Member']['ID']." AND GroupID = '2' OR GroupID = '3' ",array('GroupID'));

$Order_Array = $objorder->GetAllOrder("MemberID = '".$_REQUEST['id']."'",array("*"));
$MerchantData = $ObjMerchantAccount->GetAllMerchantAccount("MerchantId = '".$Order_Array[0]['MerchantAccID']."'",array("*"));
$merchantRecords = $MerchantData[0];
$orderid = $Order_Array[0]['ID'];
$Packge_array =   $objpackges->GetAllPackges(" ShowOnOrderForm = 1 ORDER BY PackagesTitle ASC",array("*"));

if(empty($Order_Array)){
	$orderid = $objorder->InsertWithPackage(array(
		"Created"		=> date("Y-m-d h:i:s"),
		"LastEdited"	=> date("Y-m-d h:i:s"),	 
		"MemberID"		=> $_REQUEST['id']
	));				  
}

if($_REQUEST['postback'] == 1 ){
	
	$objorder->UpdateWithPackage("MemberID = '".$_REQUEST['id']."'",array(
		"Status"  	 	  	=> "Refunded",
	));
	$added_member_id = $ObjMerchantAccount->InsertMerchantRefundedResponse(array(
		"Created"			=> date("Y-m-d h:i:s",time()),
		"ResponseID" 		=> $response_auth['response'],
		"Responsetext" 		=> $response_auth['responsetext'],
		"Authcode" 			=> $response_auth['authcode'],
		"TransactionID" 	=> $response_auth['transactionid'],
		"Avsresponse" 		=> $response_auth['avsresponse'],
		"Cvvresponse" 		=> $response_auth['cvvresponse'],
		"Orderid" 			=> $response_auth['orderid'],
		"Type" 				=> $response_auth['type'],
		"Response_code" 	=> $response_auth['response_code'],
	));
	
	# Packages in array
	foreach((array)$_REQUEST['product_array_id_package'] as $package_id => $package_array){
		foreach($package_array as $product_id){ 
			$objorder->UpdateOrderDetail("OrderID = $orderid AND PackagesID = '".$package_id."' AND ProductID =  $product_id",array(
				"Promo"	 	=> $_REQUEST['product_array_promos_package'][$package_id][$product_id],
				"Quantity"	=> $_REQUEST['product_array_qty_package'][$package_id][$product_id],
			));
		}
	}
	foreach((array)$_REQUEST['product_array_id'] as $product_id){
		$objorder->UpdateOrderDetail("PackagesID = 0 AND OrderID = '".$orderid."' AND ProductID =  $product_id",array(
			"Promo"	   		=> $_REQUEST['product_array_promos'][$product_id],
			"Quantity" 		=> $_REQUEST['product_array_qty'][$product_id],
		));
	}
	header("Location:EcClientsOrderDetail.php?id=".$_REQUEST['id']."&flag=update");	
}

if($_REQUEST['Task'] == 'RemovePackage'){
	$package_id = $_REQUEST['package_id']; 
	$objorder->DeletePackageFromOrder($package_id,$orderid);
}   
if($_REQUEST['Task']=="RemoveProduct"){
	$product_id = $_REQUEST['product_id'];
	$objorder->DeleteProductFromOrder($product_id,$orderid);
}

if($_REQUEST['Task'] == 'AddToOrder'){
	$packageID =   $_REQUEST['PackageID'];
	$productID =   $_REQUEST['ProductID'];
	if($packageID){
		$package_products = $objpackges->GetAllProductToPackges("PackagesID = '$packageID'",array("*"));

		foreach((array)$package_products as $product_row){			
			$products_data = $objproducts->GetAllProduct(" ID = ".$product_row['ProductID'],array("ID","ProductName","ProductPrice","Description"));
			$products_data = $products_data[0];
			$objorder->InsertOrderDetail(array(
				"PackagesID"		=> $packageID,
				"OrderID"			=> $orderid,
				"ProductName"		=> $products_data['ProductName'],
				"ProductPrice"		=> $product_row['PriceInPackage'],
				"ProductID"			=> $product_row['ProductID'],
				"Description"		=> $products_data['Description'],
				"Promo"				=> 0,
				"Quantity"			=> 1,
				"Verified" 			=> 0
			));
		}
	}
	if($productID){
		$product_row_array = $objproducts->GetAllProduct(" ID = ".$productID,array("ID,ProductName,ProductPrice,Description"));
		$product_row = $product_row_array[0];
		$objorder->InsertOrderDetail(array(
			"PackagesID"			=> 0,
			"OrderID"				=> $orderid,
			"ProductName"			=> $product_row['ProductName'],
			"ProductPrice"			=> $product_row['ProductPrice'],
			"ProductID"				=> $productID,
			"Description"			=> $product_row['Description'],
			"Promo"					=> 0,
			"Quantity"				=> 1,
			"Verified" 				=> 0
		));
	}
}


$Packge_array = $objpackges->GetAllPackges(" ShowOnOrderForm = 1 ORDER BY PackagesTitle ASC",array("*"));
if($_REQUEST['Task'] == 'RemoveProduct'){
    $objproducts->Deleteproduct($_REQUEST['productid']);
}																		
$products_array = $objproducts->GetAllProduct(" ShowOnOrderForm = 1 ",array("ID,ProductName,ProductPrice,Description"));

$client_OredrPackage_array = $objorder->GetAllClientOrderdedPackages("MemberID = '".$_REQUEST['id']."'");

$client_OredrProductWithoutPackage_array = $objorder->GetAllClientOrderdedProducts("MemberID = '".$_REQUEST['id']."'",array("OrderDetail.*"));

$Transection_array = $ObjMerchantAccount->GetAllTransactionResponce("MemberID = '".$_REQUEST['id']."'",array("*"));
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
	$( "#tabs" ).tabs();
});
</script>
	<?php if($_REQUEST['Task'] == 'RemovePackage'){?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td id="message_success">Package has been Deleted successfully!</td>
            </tr>
        </table>
    <?php }
    if($_REQUEST['Task']=='RemoveProduct'){?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            	<td id="message_success">Product has been Deleted successfully!</td>      
            </tr>
        </table>
    <?php } ?>
<script type="text/javascript">
function confirmation() {
	var answer = confirm("Do you want to delete this record?");
		if(answer){
		return true;
	}else{
		return false;
	}
}

$(document).ready(function() {
	$("#timepicker").datepicker();
	$("#message_success").fadeOut(3000);
	$("#message_error").fadeOut(3000);
	$(".message_success").fadeOut(3000);
	$(".message_error").fadeOut(3000);
});

function PaymentMethod(TypeID){
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
<style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 12px;}
</style>
  <div class="Popupspace"></div>
<form action="EcClientsOrderDetail.php?id=<?php echo $_REQUEST['id'];?>&Task=UpdateOrder" method="post" target="_self" enctype="multipart/form-data" name="myForm">
	<?php if($_REQUEST['flag'] == 'update'){ ?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
            <tr>
            	<td align="left" colspan="3" id="message_success">Order Refunded successfully!</td>
            </tr>
        </table>
    <?php } ?>
<div id="tabs">
<ul>
	<li><a href="#tabs-order">Order Form </a></li>
<?php //if($Group_ID){?>
	<li><a href="#tabs-payment">Response</a></li>
    <li><a href="#tabs-extradetails">Extra Details</a></li>
<?php //} ?>
</ul>

<div id="tabs-order">
    
<table width="100%" border="0" cellspacing="1" cellpadding="2"align="center" >
<tr>
				   
<td  colspan="2">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr id="headerbarpopup">
	<td>Product Name</td>
	<td>Promo</td>
	<td>Price</td>
	<td>&nbsp;</td>
	<td>Quantity</td>
	<td>Total</td>
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
   	$sum = 0;
	$total = 0;
	if($client_OredrPackage_array){
	foreach((array)$client_OredrPackage_array as $package_row){
		$package_id = $package_row['ID'];
		if( $package_id > 0  ){
		$package_products = $objorder->GetAllOrderDetail("PackagesID = '".$package_row['ID']."' AND OrderID = $orderid",array("*"));
		
		$Packge_Data =   $objpackges->GetAllPackges(" ID = '$package_id' ORDER BY PackagesTitle ASC",array("*"));
		if(count($package_products)){
		?>
		<tr id="package-heading">
             <td><?php echo $Packge_Data[0]['PackagesTitle'];?></td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
		 </tr>
        <?php
	    $product_price = 0;
        foreach((array)$package_products as $product_row){
        if($product_row['ProductID'] > 0 ){
        $qty = $product_row['Quantity'];
        if($qty < 1){
            $qty = 1;
        }
        $promo = $product_row['Promo'];
        if($promo < 1){
            $promo = 0;
        }
        $product_price = $product_row['ProductPrice'] * $qty - $promo;
	 	$subtotal = $subtotal + $product_price;
	?>
	
	<tr id="<?php echo $row_class; ?>">
	 <td><?php echo $product_row['ProductName']; ?></td>
	 <td>$<?php echo $promo;?></td>
	 <td>$<?php echo number_format($product_row['ProductPrice'],2); ?></td>
	 <td>&nbsp;</td>
	 <td><?php echo $qty; ?></td>
	 <td>$<?php echo number_format($product_price,2); ?>
	   <input type="hidden" name="product_array_id_package[<?php echo $package_id;?>][<?php echo $product_row['ProductID'];?>]" value="<?php echo $product_row['ProductID']; ?>" /></td>
	</tr>
	<?php 
        }	
        }
		$subtotal =  $subtotal - $Packge_Data[0]['PackageDiscount'];
		//
		?>

		<?php
		 $packagetotal = $subtotal + $total;
        $subtotal = 0;
		}
       }
      
	  } 
	  ?>
	    <tr id="package-heading">
		 <td colspan="3"> </td>
		 <td >&nbsp;</td>
		 <td>Package Discount:</td>
		 <td>$<?php echo number_format($Packge_Data[0]['PackageDiscount'],2); ?></td>
     </tr>
	 <tr id="totalamount">
	 <td colspan="3"> </td>
	 <td>&nbsp;</td>
	 <td>Package Total:</td>
	 <td>$<?php echo number_format($packagetotal,2); ?></td>
	 </tr> 
	  <?php 
	
	   
    }else{
	?>
	  <tr>
            <td colspan="6" align="center">
                <br>No Package In Order            </td>
        </tr> 
    <?php }?>
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
		if($client_OredrProductWithoutPackage_array){
			foreach((array)$client_OredrProductWithoutPackage_array as $product_row){
				$product_id = $product_row['ProductID'];
				if($product_id > 0 ){
					$qty = $product_row['Quantity'];
					if($qty < 1){
						$qty = 1;
					}
					$promo = $product_row['Promo'];
					if($promo < 1){
						$promo = 0;
					}
					$product_price = $product_row['ProductPrice'];
					$product_total = "";
					$product_total = $product_row['ProductPrice'] * $qty;
					?>
					<tr id="<?php echo $row_class; ?>">
						<td><?php echo $product_row['ProductName']; ?></td>
						<td>$<?php echo number_format($product_row['Promo'],2); ?>
                        </td>
						<td>$<?php echo number_format($product_row['ProductPrice'],2); ?>
                       
                        </td>
						<td>&nbsp;</td>
						<td><?php echo $qty;?></td>
						<td>$<?php echo number_format($product_total,2); ?>
						
						</td>
					</tr>
					<?php 
					
					$total = $total + $product_total;
					$productTotal_Final = $productTotal_Final + $product_price;
				}
			}
		}else{
		?>
		<tr>
            <td colspan="6" align="center"><br>No Product In Order </td>
        </tr>
 		<?php 
 		}
 if(!empty($package_products)){ ?>
<tr id="totalamount">
    <td colspan="3"> </td>
    <td>&nbsp;</td>
    <td>Product Total:</td>
    <td>$<?php echo number_format($productTotal_Final,2); ?></td>
</tr>
<?php } ?> 
<tr>
<td align="center" colspan="6">&nbsp;</td>
</tr>	
<tr id="totalamount">
<td colspan="3" >Total:</td>
<td colspan="2">&nbsp;</td>
<td >$<?php echo number_format($total + $packagetotal,2);?>

</td>
</tr>

<tr>
<td align="right" colspan="6"></td>
</tr>		
</table>
</td>
</tr>

</table>

<table width="100%"  border="0" >
  <tr>
		<td colspan="3" id="tabsubheading"> Billing Information</td>
  </tr>
  
	<tr>
        <td id="tdleft"> Business Name: </td>
        <td id="tdmiddle">
			 <?php echo $Order_Array[0]['CompanyName'];?></td>
		<td id="tdright">&nbsp;</td>
			
   </tr>
    <tr >
	    <td > First Name:</td>
        <td allign="left"><?php echo $Order_Array[0]['FirstName'];?></td>
		<td>&nbsp;</td>
    </tr>
    <tr >
	   <td >Surname:</td>
                   <td allign="left"><?php echo $Order_Array[0]['Surname'];?></td>
				   <td>&nbsp;</td>
                 </tr>

                     <tr >
                   <td  >Customer Email:</td>
                   <td allign="left"><?php echo $Order_Array[0]['Email'];?></td>
				   <td>&nbsp;</td>
                 </tr>
            
                     <tr>
                   <td >Best Phone: </td>
                   <td allign="left"><?php echo $Order_Array[0]['Phone'];?></td>
				   <td>&nbsp;</td>
                 </tr>
                 <tr>
                   <td >Alternate Phone: </td>
                   <td allign="left"><?php echo $Order_Array[0]['AlternatePhone'];?></td>
				   <td>&nbsp;</td>
                 </tr>
                 <tr>
                <td ><label> Others Notes:</label></td>
                 <td> <?php echo $Order_Array[0]['Notes'];?> </td>
				 <td>&nbsp;</td>
        </tr>
  </table>
  <tr>
  <td  colspan="3">&nbsp;
		
  </td>
  </tr>
<table width="100%"  border="0">
  
   
<tr ><td colspan="3" id="tabsubheading">Payment Information</td></tr>
<?php
if(!empty($Order_Array[0]['PaidThrough']) and $Order_Array[0]['Status'] == "Paid" and $Order_Array[0]['CardCharged'] == "0"){
?>
    <tr >
        <td colspan="3">
            <table width="100%" border="0" cellspacing="1" cellpadding="2">
            	<tr>
                	<td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td width="30%">Paid Through:</td>
                    <td width="40%">
                    <?php
                    if($Order_Array[0]['PaidThrough'] == "cash"){
                        echo "Cash";
                    }elseif($Order_Array[0]['PaidThrough'] == "check"){
                        echo "Check";
                    }elseif($Order_Array[0]['PaidThrough'] == "echeck"){
                        echo "Electronic Check";
                    }elseif($Order_Array[0]['PaidThrough'] == "cc"){
                        echo "Credit Card";
                    }elseif($Order_Array[0]['PaidThrough'] == "other"){
                        echo "Other Modes of Payment";
                    }elseif($Order_Array[0]['PaidThrough'] == "edebitDirect"){
                        echo "Electronic Debit Card";
                    }
					?>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Notes:</td>
                    <td><?php echo $Order_Array[0]['NotesForpaidThrough']; ?></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
<?php
}else{
?>
	<tr >
   <td colspan="3">
	<div  id="visacard">
    <table width="100%" border="0" cellspacing="1" cellpadding="2">
	<tr >
	 <td id="tdleft">&nbsp;</td>
     <td id="tdmiddle">
     </td>
     <td>&nbsp;</td>
	</tr>
  		 <tr>
		 	<td>Credit Card Number:</td>
			<?php
			if(!empty($Order_Array[0]['CredetCardNumber'])){
            	$Cardnumber = $objClient->decrypt($Order_Array[0]['CredetCardNumber']);
				$cardReplase = "************";
				$encriptcard = substr_replace($Cardnumber,$cardReplase,0,10);
			}else{
				$encriptcard = "No details available";
			}
			?>
			<td><?php echo $encriptcard;?></td>
			<td>&nbsp;</td>
		</tr>
		 <tr>
            <td>Credit Card Type:</td>
            <td> 
            <?php if(!empty($Order_Array[0]['CredetCardType'])) { echo $Order_Array[0]['CredetCardType']; } else { echo "No details available"; } ?>
            </td>
            <td>&nbsp;</td>
		</tr>
		 <tr>
            <td>Expiration Date:</td>
                <td>
                <?php
				if(!empty($Order_Array[0]['ExpirationMonth']) and !empty($Order_Array[0]['ExpirationYear'])){
					if($Order_Array[0]['ExpirationMonth'] == '01'){ echo "January"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '02'){ echo "February"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '03'){ echo "March"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '04'){ echo "April"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '05'){ echo "May"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '06'){ echo "June"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '07'){ echo "July"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '08'){ echo "August"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '09') {echo "September"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '10'){ echo "October"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '11'){ echo "November"; }
					elseif($Order_Array[0]['ExpirationMonth'] == '12'){ echo "December"; }
					echo "&nbsp;&nbsp;";
					if(!empty($Order_Array[0]['ExpirationYear'])){
						echo $Order_Array[0]['ExpirationYear'];
					}
				}else{
					echo "No details available";
				}
				?>
                </td>
            	<td>&nbsp;</td>
		</tr>
		<tr>
            <td>Security Code:</td>
            <td>****</td>
            <td >&nbsp;</td>
		</tr>
		<tr>
            <td>Information Sent to Payment GateWay:</td>
            <td> 
				<?php
                    if($Order_Array[0]['CardCharged'] == 1){ echo"Yes"; } else { echo "No"; }
                ?>
            </td>
            <td >&nbsp;</td>
		</tr>
	</table>
    </div>
	</td>
</tr>
<?php
}
?>
</table>  
</div>
    <div id="tabs-payment">
        <table width="100%" border="0" >
            <tr>
            	<td colspan="3" id="tabsubheading">Payment Gateway Information</td>
            </tr>
            <tr valign="top">
            	<td height="23" colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td>Created Date(Server Time):</td>
                <td><?php if(!empty($Transection_array[0]['Created']) and $Transection_array[0]['Created'] != "0000-00-00 00:00:00") echo date("<b>M d</b>, Y  H:i:s",strtotime($Transection_array[0]['Created'])); ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Created Date(Agents Local Date Time):</td>
                <td><?php if(!empty($Transection_array[0]['Agent_Time_Stamp']) and $Transection_array[0]['Agent_Time_Stamp'] != "0000-00-00 00:00:00") echo date("<b>M d</b>, Y  H:i:s",strtotime($Transection_array[0]['Agent_Time_Stamp'])); ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Time Zone:</td>
                <td style="color:#FF0000; font-weight:bold;"><?php echo $Transection_array[0]['Zone_Name']; ?></td>
                <td>&nbsp;</td>
            </tr>
           <!-- <tr>
                <td>Agents Zone:</td>
                <td style="color:#FF0000; font-weight:bold;"><?php echo $Transection_array[0]['Agent_Zone']; ?></td>
                <td>&nbsp;</td>
            </tr>-->
            <tr>
                <td> Response Text:</td>
                <td style="color:#FF0000; font-weight:bold;"><?php if($Transection_array[0]['ResponseReasonText'] == "AP") echo "Approved"; else echo $Transection_array[0]['ResponseReasonText']; ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Transaction Type:</td>
                <td ><?php echo ucfirst($Transection_array[0]['TransactionType']); ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Transaction ID:</td>
                <td style="color:#FF0000; font-weight:bold;" ><?php echo $Transection_array[0]['TransactionID'];?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Invoice Number:</td>
                <td ><?php echo $Transection_array[0]['InvoiceNum'];?></td>
                <td>&nbsp;</td>
            </tr>
            <?php
				$Respose_Message = "";
				$Respose_Message = $objorder->EPDAPI_Responses(trim($Transection_array[0]['ResponseCode']));
			?>
            <tr>
                <td>Response Code:</td>
                <td style="color:#FF0000; font-weight:bold;"><?php echo $Transection_array[0]['ResponseCode']; ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Response Message:</td>
                <td style="color:#FF0000; font-weight:bold;"><?php echo $Respose_Message; ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Response Sub code:</td>
                <td ><?php echo $Transection_array[0]['ResponseSubcode'];?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td width="25%">Card Type:</td>
                <td ><?php echo $Order_Array[0]['CredetCardType']; ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Order ID:</td>
                <td ><?php echo $Transection_array[0]['OrderID']; ?></td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>

<div id="tabs-extradetails">
	<?php
	$ClientDetails = $objClient->GetAllClients(" ID = '".$_REQUEST['id']."' ",array("*"));
	?>
	<table width="100%" border="0" >
        <tr>
            <td colspan="3" id="tabsubheading">Other Information</td>
        </tr>
        <tr>
            <td>Agent Name:</td>
            <td><?php echo $objClient->FetchAgentName($ClientDetails[0]['SubmitedBy']); ?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Agent Zone:</td>
            <td><?php echo $Transection_array[0]['Agent_Zone']; ?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Agent IP:</td>
            <td><?php echo $ClientDetails[0]['Agent_IP']; ?></td>
            <td>&nbsp;</td>
        </tr>
        <?php
        $det = explode(",",$ClientDetails[0]['Agent_Browser_Details']);
		$seconds = round($ClientDetails[0]['Time_Spent']);
		function minutes($seconds){
			return sprintf("%02.2d:%02.2d", floor( $seconds / 60 ), $seconds % 60);
		}
		$timespent = minutes((int)$ClientDetails[0]['Time_Spent']);
		?>
        <tr>
            <td>Browser Details:</td>
            <td><?php echo "<b>Browser</b> - ".ucfirst($det[0]).", <b>Browser Version</b> - ".$det[1].", <br/><b>Browser Layout</b> - ".ucfirst($det[2]).", <b>Operating System</b> - ".ucfirst($det[3]); ?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Time Spent On This Order:</td>
            <td style="color:#FF0000; font-weight:bold;"><?php echo $timespent. " Minutes"; ?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td valign="top">Agreement Signed:</td>
            <td>
            <?php 
				$status = $objorder->GetAllOrderDetail("OrderId = '" .$orderid . "'", array("*"));
				$s = ($status[0]['Verified'] == "1") ? "<span style='color:green;'>Signed &nbsp;<img title='Signed' src='../../images/icon_tick.png'></span>" : "<span style='color:red;'>Unsigned</span>";
				echo $s."&nbsp;&nbsp;";
				if($status[0]['Verified'] == "1"){
					$pdf = SITE_ADDRESS . "agreements/attachments/" . $_REQUEST['id'] . "-" . $orderid . ".pdf";
					echo "<br/><a href='{$pdf}' target='_blank' style='color:blue;'>Download PDF</a>";	
				} else {
					$order_invoice_array = $objorder->GetAllOrderDetailWithProduct("OrderID=$orderid ",array("*"));
					$productList = array();
					foreach((array) $order_invoice_array as $row) {
						$productList[] = $row['ProductID'];
					}
					$isGYB = false;
					$gybArray = array("20", "30", "39", "40", "41",  "42", "44", "45", "54","55");
					$isWebsite = false;
					$webArray = array("46", "47", "48", "49", "50");
					foreach($productList as $name) {
						//echo $name;
						if(in_array($name, $gybArray)) {
							$isGYB = true;	
						} 
						if(in_array($name, $webArray)) {
							$isWebsite = true;	
						} 
					}
					echo "<br /><a href='http://xurlios.com/agreements/";
					if($isGYB==true){
						echo "gyb.php";
					}else {
						echo "websites.php";
					}
					echo "?unsigned=true&order=" . $orderid . "&client=". $_REQUEST['id'] . "'  target='_blank'>&raquo; Download Unsigned form</a>";
					echo "<br /><a href='javascript:showPDF();'>&raquo; Upload Signed Form</a>";
				}
			?>
            </td>
            <script>
			
			function showPDF(){
				document.getElementById("pdfRow").style.display="block";	
			}
			</script>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
            <td><iframe src="<?php echo SITE_ADDRESS; ?>ecommerce/orders/pdfUpload.php?id=<?php echo $_REQUEST['id'];?>&order=<?php echo $orderid;?>&agent=<?php echo $objClient->FetchAgentName($ClientDetails[0]['SubmitedBy']);?>" width="100%" height="90" frameborder="0" id="pdfRow" style="display: none" /></iframe></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td valign="top">Extra Notes:</td>
            <td> <iframe src="<?php echo SITE_ADDRESS; ?>ecommerce/orders/notes.php?order=<?php echo $orderid;?>" width="100%" height="140" name="notes" frameborder="0"></iframe></td>
            <td>&nbsp;
           
            </td>
        </tr>
    </table>
</div>
</div>

<div style="height:25px;">&nbsp;</div>
    <table width="100%" border="0" >
        <tr>
            <td align="center" colspan="3">
            	<div align="center"></div>
            	<input type="hidden" name="postback" value="1" />
            </td>
        </tr>
    </table>
 </form>


