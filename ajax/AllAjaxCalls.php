<?php 
include dirname(__FILE__)."/../lib/include.php";
$objorder = new Orders();
$objUser = new Users();
$objClient = new Clients();

if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "UpdateRefundStatus" and !empty($_REQUEST['Task'])){
	extract($_REQUEST);
	
	$updateOrderDetail = $objorder->UpdateOrderDetail(" OrderID = '".$OrderID."' ",array(
		"IsCancel"		=> 1,
		"IsRefund"		=> 1,
	));
	
	$updateOrder = $objorder->UpdateRefundForManuallAddedPaymentOrders($OrderID); 
	if($updateOrder == 1){
		echo "Success";
		exit;
	}else{
		echo "Failed";
		exit;
	}
	exit;
}

if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "MarkAsCanceledOrPaid" and !empty($_REQUEST['Task'])){
	extract($_REQUEST);
	if($MarkAs == 1){
		# 1 means Cancelled Only. Then only update the  Order Details table matching the Order ID
		$updateOrderDetail = $objorder->UpdateOrderDetail(" OrderID = '".$OrderID."' ",array(
			"IsCancel"		=> 1,
		));
		
		$status = $objorder->Update_Status($OrderID,"Cancelled",$Notes);
		
	}else if($MarkAs == 2){
		# 2 means Refunded Only. Also mark in the Order Item table as "Refunded"
		$updateOrderDetail = $objorder->UpdateOrderDetail(" OrderID = '".$OrderID."' ",array(
			"IsRefund"		=> 1,
		));
		$status = $objorder->Update_Status($OrderID,"Refunded",$Notes);
		
	}else if($MarkAs == 3){
		# 3 means unpaid
		$updateOrderDetail = $objorder->UpdateOrderDetail(" OrderID = '".$OrderID."' ",array(
			"IsCancel"		=> 0,
			"IsRefund"		=> 0,
		));
		$arg = array(
			"ManualPaymentDate" => 'NULL'
		);
		$updatePaidDate = $objorder->updateOrderFields("ID = $OrderID", $arg);
		
		$status = $objorder->Update_Status($OrderID,"Unpaid",$Notes);
		
	}else if($MarkAs == 4){
		# 3 means Charge Back
		$updateOrderDetail = $objorder->UpdateOrderDetail(" OrderID = '".$OrderID."' ",array(
			"IsCancel"		=> 0,
			"IsRefund"		=> 0,
		));
		
		$status = $objorder->Update_Status($OrderID,"ChargeBack",$Notes);
	}else{
		echo "Something Went Wrong.";
	}
	
	if($status == 1){
		echo "Success";
		exit;
	}else{
		echo "Failed";
		exit;
	}
	exit;
}


if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "ValidateLoginDetails" and !empty($_REQUEST['Task'])){
	extract($_REQUEST);
	$getdetails = $objUser->ValidateUser_super(trim($email),trim($pass));
	
	if($getdetails == 1 and !empty($getdetails)){
		$CCDetails = $objClient->FetchCCInformation($ClientID);
		$secure_credit_card = $objClient->decrypt($CCDetails[0]['CreditCardNumber']);
		$cvv = $CCDetails[0]['CVV'];
		if(!empty($secure_credit_card) and !empty($cvv)){
			echo $secure_credit_card."@@@@@".$cvv;
			exit;
		}
	}else{
		echo "Wrong Username or Password.";
	}
	exit;
}

if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "ValidateLoginDetails_ToAdd" and !empty($_REQUEST['Task'])){
	extract($_REQUEST);
	$getdetails = $objUser->ValidateUser_super(trim($email),trim($pass));
	if($getdetails == 1 and !empty($getdetails)){
		echo "Valid";
		exit;
	}else{
		echo "Not Valid";
		exit;
	}
	exit;
}

if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "UpdateValueCheckCard" and !empty($_REQUEST['Task'])){
	extract($_REQUEST);
	if($Field == "CreditCardNumber" || $FieldInOrderItemTable == "CredetCardNumber"){
		if(trim($NewValue) != ""){
			$char = "X";
			$pos = strpos($NewValue, $char);
			if($pos===false) {
				$NewValue = $objClient->encrypt(trim($NewValue));
			} else {
				$NewValue = "stop";	
			}
		}else{
			$NewValue = "";
		}
	}else{
		$NewValue = $NewValue;
	}
	if($NewValue!="stop") {
		$update = $objClient->UpdateClientCCDetails($ClientID,$Field,$NewValue,$FieldInOrderItemTable);
			if($update == 1){
				echo "Success";
				exit;
			}else{
				echo "Error";
				exit;
			}
		}
	die();
}

if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "AddAllToSession" and !empty($_REQUEST['Task'])){
	$_SESSION['OrderDataInRequest'] = $_REQUEST;
	echo "1";
	exit;
}


if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "UpdateFounded" and !empty($_REQUEST['Task'])){
	$founded = $_REQUEST['founded'];
	echo "updated $founded";
}
if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "UpdateServices" and !empty($_REQUEST['Task'])){
	$services = $_REQUEST['services'];
	echo "updated $services";
}

?>