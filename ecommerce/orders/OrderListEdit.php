<?php
include "../../lib/include.php";
require_once('../../ecommerce/products/AuthorizeNet-sdk/AuthorizeNet.php'); 
$ObjMerchantAccount = new MerchantAccount();
$objClient = new Clients();
$objorder = new Orders();
$gw = new gwapi();

$Order_Array = $objorder->GetAllOrder("ID = '".$_REQUEST['OrderID']."'",array("*"));

$TransactionID = $ObjMerchantAccount->GetAllTransactionResponce("OrderID = '".$_REQUEST['OrderID']."'",array("TransactionID"));

$MerchantData = $ObjMerchantAccount->GetAllMerchantAccount("MerchantId = '".$Order_Array[0]['MerchantAccID']."'",array("*"));

$merchantRecords = $MerchantData[0];
$LoginID = trim($merchantRecords['LoginID']);
$TransKey = trim($merchantRecords['TransactionKey']);
$gw->setLogin($LoginID, $TransKey);

if($_REQUEST['Task'] == 'del'){
	$objorder->UpdateOrderDetail("OrderID = '".$_REQUEST['OrderID']."' AND ID='".$_REQUEST['id']."'",array("IsCancel"=>1,));
}
if($_REQUEST['Task'] == 'Refund'){
    
    header("location:OrderListEdit.php?tab=1&flag=sucessrefund&ResponseMessage=$ResponseMessage&OrderID=".$_REQUEST['OrderID']);
    exit;
    $order_details_response = $objorder->GetAllOrderDetail("OrderID='".$_REQUEST['OrderID']."' AND ID='".$_REQUEST['id']."'",array("*"));

    $totalprice = number_format($order_details_response[0]['ProductPrice'], 2, ".", "");

	switch($response_auth['response']){
		case 3:
			$ResponseMessage = $response_auth['responsetext'];
			header("location:OrderListEdit.php?tab=1&flag=fail&ResponseMessage=$ResponseMessage&OrderID=".$_REQUEST['OrderID']);
		break;
	}

	if($response_auth['response'] == 1){
		$added_member_id = $ObjMerchantAccount->InsertMerchantRefundedResponse(array(
			"Created"				=> date("Y-m-d h:i:s",time()),
			"ResponseID"			=> $response_auth,
			"Responsetext" 			=> $response_auth['responsetext'],
			"Authcode"				=> "",
			"TransactionID" 		=> $TransactionID[0]['TransactionID'],
			"Avsresponse"			=> "",
			"Cvvresponse"			=> "",
			"Orderid"				=> $_REQUEST['OrderID'],
			"Type"					=> "refund",
			"Response_code"			=> $response_auth['response_code'],
			"ProductPrice"			=> $totalprice,
			"OrderDetailID"			=> $_REQUEST['id'],				 
		));
		$objorder->UpdateOrderDetail("OrderID='".$_REQUEST['OrderID']."' AND ID='".$_REQUEST['id']."'",array(
			"IsCancel"		=> 1,
			"IsRefund"		=> 1,
		)); 
		header("location:OrderListEdit.php?tab=1&flag=sucessrefund&ResponseMessage=$ResponseMessage&OrderID=".$_REQUEST['OrderID']);
	}
}

if($_REQUEST['Task'] == 'CancelOrder'){
	$OrderID = $_REQUEST['OrderID'];
	$order_details = $objorder->GetAllOrderDetail("OrderID=".$_REQUEST['OrderID']."",array("*"));
	foreach((array)$order_details as $order_detail){
		$objorder->UpdateOrderDetail("OrderID='".$_REQUEST['OrderID']."' AND ID='".$order_detail['ID']."'",array(
			"IsCancel"			=> 1,
		));
	}
	
	$updateOrder_to_Cancelled = $objorder->UpdateOrdertoCancelled($OrderID); 
}

if($_REQUEST['Task'] == 'ReundOrder'){
	# Fetch the details of the Order to be cancelled
	$order_details = $objorder->GetAllOrderDetail("OrderID = '".$_REQUEST['OrderID']."' ",array("*"));
	
	$response_auth = $gw->doRefund($TransactionID[0]['TransactionID'],$order_details[0]['ProductPrice']);
	
	if($response_auth['response'] == 1 and $response_auth['type'] == "refund"){
		foreach((array)$order_details as $order_detail){
		
			$objorder->UpdateOrderDetail("OrderID='".$_REQUEST['OrderID']."' AND ID='".$order_detail['ID']."'",array(
				"IsCancel"		=> 1,
				"IsRefund"		=> 1,
			));
			
			$objorder->UpdateOrderItemStatusRefunded(" ID = '".$_REQUEST['OrderID']."' ",array(
				"Status"		=> "Refunded",
				"CardCharged"	=> 0,
				"Updation_Date"	=> date("Y-m-d h:i:s",time())
			));
			
			$added_member_id = $ObjMerchantAccount->InsertMerchantRefundedResponse(array(
				"Created"				=> date("Y-m-d h:i:s",time()),
				"ResponseID"			=> $response_auth['response'],
				"Responsetext" 			=> $response_auth['responsetext'],
				"Authcode"				=> $response_auth['authcode'],
				"TransactionID" 		=> $TransactionID[0]['TransactionID'],
				"Avsresponse"			=> $response_auth['avsresponse'],
				"Cvvresponse"			=> $response_auth['cvvresponse'],
				"Orderid"				=> $_REQUEST['OrderID'],
				"Type"					=> $response_auth['type'],
				"Response_code"			=> $response_auth['response_code'],
				"ProductPrice"			=> $order_detail['ProductPrice'],
				"OrderDetailID"			=> $order_detail['ID'],						 
			));
		}
	}
	# header("location:OrderListEdit.php?tab=1&flag=sucessrefund&ResponseMessage=$ResponseMessage&OrderID=".$_REQUEST['OrderID']);
}

$order_details = $objorder->GetAllOrderDetail("OrderID=".$_REQUEST['OrderID']."",array("*"));
$Transection_array = $ObjMerchantAccount->GetAllMerchantResponse("OrderDetailID='".$_REQUEST['id']."'",array("*"));
 
?>
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/bootstrap.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/bootstrap-dialog.min.js"></script>
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/bootstrap-dialog.min.css" />
<script type="text/javascript">
$(function() {
	$("#tabs").tabs();
});

function confirmation() {
	var answer = confirm("Do you want to cancel this service?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
function confirmation_refund_service() {
	var answer = confirm("Do you want to refund this service?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
function confirmation_cancelorder() {
	var answer = confirm("Do you want to cancel this order?");
	if(answer){
		return true;
	}else{
		return false;
	}
}

function confirmation_refundorder() {
	var answer = confirm("Do you want to refund this order?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
$(document).ready(function(){
	$("#message_success").fadeOut(3000);
	$("#message_error").fadeOut(3000);
	$(".message_success").fadeOut(3000);
	$(".message_error").fadeOut(3000);
});

function reundOrder_UpdateDbonly(OrderID){
	if(OrderID != ""){
		 BootstrapDialog.confirm("This is a Order which is not in EPD. This is a manually added payment Order.\n This will only update the status of Order in the database. Are you sure you want to refund this order ?", function(result){
            if(result){
				$(".message_wait").show();
				$.ajax({
					url : '../../ajax/AllAjaxCalls.php?Task=UpdateRefundStatus&OrderID='+OrderID,
				}).done(function(data){
					if(data == "Success"){
						$(".message_wait").hide();
						location.reload();
					}else{
						alert("There was some error while updating the records. Please try again.");
					}
				});
            }else {
                $(".modal-dialog").modal('hide');
            }
        });
	}
}
</script>
	<?php if($_REQUEST['Task'] == 'del'){ ?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
            <tr>
            	<td class="message_success">Service Cancelled Successfully!</td>
            </tr>
        </table>
   	<?php }
	if($_REQUEST['flag'] == 'sucessrefund'){ ?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
            <tr>
                <td class="message_success">Service Refunded And Cancelled Successfully!</td>
            </tr>
        </table>
   	<?php }
   	if($_REQUEST['flag'] == 'fail'){ ?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
            <tr>
                <td class="message_error">System Error! <?php echo $_REQUEST['ResponseMessage']; ?></td>
            </tr>
        </table>
   	<?php }
	if($_REQUEST['Task'] == 'CancelOrder' || $updateOrder_to_Cancelled == 1){ ?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
            <tr>
             <td class="message_success">Order Cancelled Successfully!</td>
            </tr>
        </table>
   	<?php }
   	if($_REQUEST['Task'] == 'ReundOrder'){ ?>
        <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
            <tr>
                <td class="message_success">Order Refunded And Cancelled Successfully!</td>
            </tr>
        </table>
    <?php } ?>	
<style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 12px;}
.message_wait{
	background-color: #BEE1F1;
    border: 1px solid #9ACFEA;
    color: #31708F;
    float: left;
    font-weight: normal;
    padding: 5px;
    text-align: center;
    width: 60%;
}
</style>
  <div class="Popupspace"></div>
<form action="EcClientsOrderDetail.php?id=<?php echo $_REQUEST['id'];?>&Task=UpdateOrder" method="post" target="_self" enctype="multipart/form-data" name="myForm">
<?php if($_REQUEST['flag']=='update'){?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
        <tr>
        	<td align="left" colspan="3" id="message_success">Order Refunded Successfully!</td>
        </tr>
    </table>
   <?php }?>
<div id="tabs">
<ul>
	<li><a href="#tabs-order">Order Form </a></li>
	<!--<li><a href="#tabs-payment">Response</a></li>-->
</ul>

<div id="tabs-order">
<div align="right">
	<div class="message_wait" style="display:none;">Please Wait...</div>
    <a href="OrderListEdit.php?OrderID=<?php echo $_REQUEST['OrderID'];?>&amp;Task=CancelOrder">
    	<input type="button" id="CancelOrder" name="CancelOrder" value="Cancel Order" onclick="return confirmation_cancelorder();" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"/></a>
        
    <?php
    if(!empty($Order_Array[0]['ManuallyAddedPayment']) and $Order_Array[0]['ManuallyAddedPayment'] == 1 and $Order_Array[0]['CardCharged'] == 0){
	?>
    	<input type="button" id="ReundOrder_UpdateDbonly" name="ReundOrder_UpdateDbonly" value="Refund Order" onclick="return reundOrder_UpdateDbonly('<?php echo $_REQUEST['OrderID']; ?>')" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"/>
    <?php
    
	}else{
	?>
    <a href="OrderListEdit.php?OrderID=<?php echo $_REQUEST['OrderID'];?>&amp;Task=ReundOrder">
    	<input type="button" id="ReundOrder" name="ReundOrder" value="Refund Order" onclick="return confirmation_refundorder();" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"/>
    </a>
    <?php
    }
	?>
</div>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2"align="center"  >
<tr id="headerbarpopup">
<td>ID</td>
<td >Name</td>
<td>Price</td>
<td>Quantity</td>
<td>Merchant Name </td>
<td>Cancel Status</td>
<td>Refund Status</td>
<!--<td width="65">Actions</td>-->
</tr>
<?php  
   foreach((array)$order_details as $order_detail){
  
	if($flag == 0){
            $flag = 1;
            $row_class = "row-white";
        }else{
            $flag = 0;
            $row_class = "row-tan";
        }
   ?>
   <tr id="<?php echo $row_class;?>">
    <td><?php echo $order_detail['ID'];?></td>
    <td><?php echo $order_detail['ProductName'];?></td>
    <td><?php echo $order_detail['ProductPrice'];?></td>
	<td><?php echo $order_detail['Quantity'];?></td>
    <td><?php echo $MerchantData[0]['AccountName'];?></td>
    <td align="center"><?php if($order_detail['IsCancel'] == 0) { ?><img src="../../images/start.png" title="Service is Active"> <?php }else {?><img src="../../images/stop.png" title="service has canceled"><?php }?> </td>
    <td align="center"><?php if($order_detail['IsRefund'] == 0) { ?><img src="../../images/start.png" title="Aervice is Active"> <?php }else {?><img src="../../images/stop.png" title="service has canceled"><?php }?> </td>
   <!-- <td> <a href="OrderListEdit.php?OrderID=<?php echo $_REQUEST['OrderID'];?>&amp;id=<?php echo $order_detail['ID'];?>&amp;Task=del"> <img title="Cancel Product - no refund." src="../../images/icon_cancel.png" border="0" onClick="return confirmation();"/></a> 
         <a href="OrderListEdit.php?OrderID=<?php echo $_REQUEST['OrderID'];?>&amp;id=<?php echo $order_detail['ID'];?>&amp;Task=Refund&amp;MemberID=<?php echo $_REQUEST['MemberID']; ?>" title="Refund Product - Product will be cancelled and be refunded." onClick="return confirmation_refund_service();"><img src="../../images/icon_refund.png" border="0"/></a></td>
   </tr>-->
 <?php
   } 
 
?>
     </table>
                   </td>
                   </tr>
                   <tr valign="top">
                   <td align="center" colspan="2">
                    <div align="center" style="float: left;  bottom:0; background: none repeat scroll 0 0 #FFFFFF;  width: 100%; margin-top: 30px;">
                      <input type="hidden" name="oldimage"  value="3"/></div>
       </td>
<input type="hidden" name="postback" value="1" />
     </tr>
  </table>   
</div>
<!--<div id="tabs-payment">
    <table width="100%" border="0" >
        <tr>
        	<td colspan="3" id="tabsubheading"> Payment Gateway Information </td>
        </tr>
        <tr valign="top">
            <td height="23" colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td id="tdleft">Response Text:</td>
            <td id="tdmiddle"><?php echo $response_auth['responsetext'];?></td>
            <td id="tdright">&nbsp;</td>
        </tr>
        <tr>
            <td>Transaction Type:</td>
            <td ><?php echo $response_auth['type'];?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Customer IP:</td>
            <td ><?php //echo $response_auth['response'];?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Response ID:</td>
            <td ><?php echo $response_auth['response'];?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Response Code:</td>
            <td ><?php echo $response_auth['response_code'];?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Refund Product Price:</td>
            <td ><?php echo $Transection_array[0]['ProductPrice'];?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td width="25%">Card Type:</td>
            <td ><?php //echo $Transection_array[0]['CardType'];?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td>Order Number:</td>
        	<td ><?php echo $Transection_array[0]['OrderDetailID'];?></td>
        	<td>&nbsp;</td>
        </tr>
    </table>
</div>-->
</div>
<div style="height:25px;">&nbsp;</div>