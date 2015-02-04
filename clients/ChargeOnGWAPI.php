<?php
$order_charged = 0;
$gw = new gwapi;
$gw->setLogin($LoginID, $TransKey);
$CreditCard0ExpirationYear = $_REQUEST['ExpiryYears'];
$CreditCard0ExpirationMonth = $_REQUEST['ExpiryMonth'];
$year = substr((string)$CreditCard0ExpirationYear,-2);
$totalprice = number_format($_REQUEST['totalprice'], 2, ".", "");

if(empty($totalprice)){
	$totalprice = $totalprice_p;
}

//$response_auth = $gw->doAuth($totalprice, $_REQUEST['Cnumber'], $CreditCard0ExpirationMonth.$year);

# Added One more functionality to the below process. It contains Payments three ways.
# 1. While the Order is made and the payment is done from the home page itself
# 2. Order is made and the payment is done by the admin or teh agents later
# 3. Order is made and the payment is made later by the client themselves using the link sent to them in the email.
if(!empty($CollectingPayment) and $CollectingPayment == "Later"){
	# Since this part was added later So I have made it separate from the below and
	# added this inside if condition so that the previous functionality would not get
	# affected. Hence this is a long process but this keeps the collect payment later 
	# separate So there is no chances that the two functionality are mixed up together.
	# $CollectingPayment is coming from the page CollectPayment.php where the clients
	# can fill in the details later for the payment and then this part gets executed.
	//if($response_auth['response_code'] == "100" || $response_auth['response_code'] == 100){
		$gw->setBilling($_REQUEST['fName'],$_REQUEST['sureName'],
			$_REQUEST['cname'],
			$_REQUEST['Streetaddress1'],
			$_REQUEST['Streetaddress2'], 
			$_REQUEST['Bcity'],
			$_REQUEST['Bstate'],
			$_REQUEST['Bpostalcode'],
			$_REQUEST['BillingCountry'],
			$_REQUEST['phone'],
			$_REQUEST['alternatephone'],
			$_REQUEST['Email'],
			"");
		$gw->setShipping($_REQUEST['fName'],$_REQUEST['sureName'],
			$_REQUEST['cname'],
			$_REQUEST['Streetaddress1'],
			$_REQUEST['Streetaddress2'], 
			$_REQUEST['Bcity'],
			$_REQUEST['Bstate'],
			$_REQUEST['Bpostalcode'],
			$_REQUEST['BillingCountry'],
			$_REQUEST['Email']
		);

		# Below $ClientID -> Means Client ID from Client table and
		# $OrderID -> ID from OrderItem table which is foreign key to OrderDetails table
		$invoice_no = $_SESSION['Member']['ID']."-".$ClientID."-".$OrderID;
		if(empty($_SESSION['Member']['ID'])){
			$invoice_no = $AgentDet[0]['ID']."-".$added_member_id."-".$inserted_order;
		}
		$gw->setOrder($invoice_no, $_REQUEST['fName']." - Order" ,0, 0, "PO".time(),$_SERVER['REMOTE_ADDR']);
		$response_sale = $gw->doSale($totalprice,$_REQUEST['Cnumber'],$CreditCard0ExpirationMonth.$year);
		if($response_sale['response_code'] == 100){
			$encryptcard = $objClient->encrypt($_REQUEST['Cnumber']);
			# Update the details of the Order Item table which contains the details of the order.
			$update_orderitem = $objorder->UpdateWithPackage("ID = '$OrderID' ",array(
				"LastEdited"			=> date("Y-m-d h:i:s"),
				"CredetCardType"		=> $_REQUEST['creditcardtype'],
				"CredetCardNumber"		=> $encryptcard,
				"ExpirationMonth"		=> $_REQUEST['ExpiryMonth'],
				"ExpirationYear"		=> $_REQUEST['ExpiryYears'],
				"SecurityCode"			=> $_REQUEST['Ccode'],
				"Status"				=> "Paid",
				"ManualPaymentDate"		=>date("Y-m-d"),
				"TotalPrice"			=> $_REQUEST['totalprice'],
				"CardCharged"			=> 1,
				"BillingCountry"		=> "US",
				"MerchantAccID"			=> $merchantRecords['MerchantId'],
			));
			
			$order_charged = 1;
			$Transaction = "Successfull";
			# $inserted_order is the last Inserted ID in the OrderDetails table which can be now used to update
			# the Status and the Card Charged in this table to set the status as paid as till now it is set as unpaid
			require_once('UpdatePaymentDetails.php');
			$response_code = $response_sale['response_code'];
			$responsemsg = $response_sale['responsetext'];
			$transaction_id = $response_sale['transactionid'];
			require_once('SaveMerchantReturnInfo.php');
		}else{
			require_once('SaveMerchantReturnInfo.php');
			switch(trim($response_sale['response_code'])){
				case 200:
					$ResponseMessage = $response_sale['responsetext']." Transaction was declined by Credit Card Processor.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				# Added Newly
				case 201:
					$ResponseMessage = $response_sale['responsetext']." Do not honor.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 202:
					$ResponseMessage = $response_sale['responsetext']." Insufficient funds.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 202:
					$ResponseMessage = $response_sale['responsetext']." Transaction was declined by Credit Card Processor! Insufficient funds.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 203:
					$ResponseMessage = $response_sale['responsetext']." Over limit. Transaction was declined by Credit Card Processor!<br>Message from Credit Card Processor<br>Over Limit";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 204:
					$ResponseMessage = $response_sale['responsetext']." Transaction not allowed.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 220:
					$ResponseMessage = $response_sale['responsetext']." Incorrect payment information.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 221:
					$ResponseMessage = $response_sale['responsetext']." No such card issuer.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 222:
					$ResponseMessage = $response_sale['responsetext']." No card number on file with issuer.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 223:
					$ResponseMessage = $response_sale['responsetext']." Expired card.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 224:
					$ResponseMessage = $response_sale['responsetext']." Invalid expiration date.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 225:
					$ResponseMessage = $response_sale['responsetext']." Invalid card security code.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 240:
					$ResponseMessage = $response_sale['responsetext']." Call issuer for further information.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 250:
					$ResponseMessage = $response_sale['responsetext']." Pick up card.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 251:
					$ResponseMessage = $response_sale['responsetext']." Lost card.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 252:
					$ResponseMessage = $response_sale['responsetext']." Stolen card.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 253:
					$ResponseMessage = $response_sale['responsetext']." Fraudulent card.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 260:
					$ResponseMessage = $response_sale['responsetext']." Declined with further instructions available. (See response text)";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 261:
					$ResponseMessage = $response_sale['responsetext']." Declined-Stop all recurring payments.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 262:
					$ResponseMessage = $response_sale['responsetext']." Declined-Stop this recurring program.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 263:
					$ResponseMessage = $response_sale['responsetext']." Declined-Update cardholder data available.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 264:
					$ResponseMessage = $response_sale['responsetext']." Declined-Retry in a few days.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 300:
					$ResponseMessage = $response_sale['responsetext']." Transaction was rejected by gateway.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 400:
					$ResponseMessage = $response_sale['responsetext']." Transaction was declined by Credit Card Processor.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 410:
					$ResponseMessage = $response_sale['responsetext']." Invalid merchant configuration.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 411:
					$ResponseMessage = $response_sale['responsetext']." Merchant account is inactive.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 420:
					$ResponseMessage = $response_sale['responsetext']." Communication error.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 421:
					$ResponseMessage = $response_sale['responsetext']." Communication error with issuer.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 430:
					$ResponseMessage = $response_sale['responsetext']." Duplicate transaction at processor. Please try after some time.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 440:
					$ResponseMessage = $response_sale['responsetext']." Processor format error.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 441:
					$ResponseMessage = $response_sale['responsetext']." Invalid transaction information.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 460:
					$ResponseMessage = $response_sale['responsetext']." Processor feature not available.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				case 461:
					$ResponseMessage = $response_sale['responsetext']." Unsupported card type.";
					$_SESSION['OrderDataInRequest'] = $_REQUEST;
					header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
				break;
				# Upto Here
			
			}
		}
	//}

}else{
	if($_REQUEST['id'] > 0 ){
		$backurl = "AddClientOrder";
	}else{
		$backurl = "AddClients";
	}
	
	require_once('SaveOrderDB.php');

	$Transaction = "";
	
	//if($response_auth['response_code'] == 100){
		# now we need to store order in database
		# Before the Order was only saved into the database if the Payment was successfull
		# Moved outside of this condition because the Order details needed to be inserted
		# into the database whether or not the Payment was made successfull.
		# require_once('SaveOrderDB.php');
		$gw->setBilling($_REQUEST['fName'],$_REQUEST['sureName'],
			$_REQUEST['cname'],
			$_REQUEST['Streetaddress1'],
			$_REQUEST['Streetaddress2'], 
			$_REQUEST['Bcity'],
			$_REQUEST['Bstate'],
			$_REQUEST['Bpostalcode'],
			$_REQUEST['BillingCountry'],
			$_REQUEST['phone'],
			$_REQUEST['alternatephone'],
			$_REQUEST['Email'],
			"");
		$gw->setShipping($_REQUEST['fName'],$_REQUEST['sureName'],
			$_REQUEST['cname'],
			$_REQUEST['Streetaddress1'],
			$_REQUEST['Streetaddress2'], 
			$_REQUEST['Bcity'],
			$_REQUEST['Bstate'],
			$_REQUEST['Bpostalcode'],
			$_REQUEST['BillingCountry'],
			$_REQUEST['Email']
		);
		$Transaction = "";
		# require_once('SaveOrderDB.php');
		$invoice_no = $_SESSION['Member']['ID']."-".$added_member_id."-".$inserted_order;
		if(empty($_SESSION['Member']['ID'])){
			$invoice_no = $AgentDet[0]['ID']."-".$added_member_id."-".$inserted_order;
		}
		$gw->setOrder($invoice_no, $_REQUEST['fName']." - Order" ,0, 0, "PO".time(),$_SERVER['REMOTE_ADDR']);
		try {
			$response_sale = $gw->doSale($totalprice,$_REQUEST['Cnumber'],$CreditCard0ExpirationMonth.$year);
			if(!empty($response_sale['response_code']) and $response_sale['response_code'] == 100){
				$order_charged = 1;
				$Transaction = "Successfull";
				# $inserted_order is the last Inserted ID in the OrderDetails table which can be now used to update
				# the Status and the Card Charged in this table to set the status as paid as till now it is set as unpaid
				require_once('UpdatePaymentDetails.php');
				$response_code = $response_sale['response_code'];
				$responsemsg = $response_auth['responsetext'];
				$transaction_id = $response_sale['transactionid'];
				require_once('SaveMerchantReturnInfo.php');
			}elseif(!empty($response_sale['response_code']) and $response_sale['response_code'] != 100){
				require_once('SaveMerchantReturnInfo.php');
				switch(trim($response_sale['response_code'])){
					case 200:
						$ResponseMessage = $response_sale['responsetext']." Transaction was declined by Credit Card Processor.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					# Added Newly
					case 201:
						$ResponseMessage = $response_sale['responsetext']." Do not honor.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 202:
						$ResponseMessage = $response_sale['responsetext']." Insufficient funds.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 202:
						$ResponseMessage = $response_sale['responsetext']." Transaction was declined by Credit Card Processor! Insufficient funds.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 203:
						$ResponseMessage = $response_sale['responsetext']." Over limit. Transaction was declined by Credit Card Processor!<br>Message from Credit Card Processor<br>Over Limit";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 204:
						$ResponseMessage = $response_sale['responsetext']." Transaction not allowed.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 220:
						$ResponseMessage = $response_sale['responsetext']." Incorrect payment information.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 221:
						$ResponseMessage = $response_sale['responsetext']." No such card issuer.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 222:
						$ResponseMessage = $response_sale['responsetext']." No card number on file with issuer.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 223:
						$ResponseMessage = $response_sale['responsetext']." Expired card.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 224:
						$ResponseMessage = $response_sale['responsetext']." Invalid expiration date.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 225:
						$ResponseMessage = $response_sale['responsetext']." Invalid card security code.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 240:
						$ResponseMessage = $response_sale['responsetext']." Call issuer for further information.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 250:
						$ResponseMessage = $response_sale['responsetext']." Pick up card.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 251:
						$ResponseMessage = $response_sale['responsetext']." Lost card.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 252:
						$ResponseMessage = $response_sale['responsetext']." Stolen card.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 253:
						$ResponseMessage = $response_sale['responsetext']." Fraudulent card.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 260:
						$ResponseMessage = $response_sale['responsetext']." Declined with further instructions available. (See response text)";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 261:
						$ResponseMessage = $response_sale['responsetext']." Declined-Stop all recurring payments.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 262:
						$ResponseMessage = $response_sale['responsetext']." Declined-Stop this recurring program.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 263:
						$ResponseMessage = $response_sale['responsetext']." Declined-Update cardholder data available.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 264:
						$ResponseMessage = $response_sale['responsetext']." Declined-Retry in a few days.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 300:
						$ResponseMessage = $response_sale['responsetext']." Transaction was rejected by gateway.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 400:
						$ResponseMessage = $response_sale['responsetext']." Transaction was declined by Credit Card Processor.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 410:
						$ResponseMessage = $response_sale['responsetext']." Invalid merchant configuration.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 411:
						$ResponseMessage = $response_sale['responsetext']." Merchant account is inactive.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 420:
						$ResponseMessage = $response_sale['responsetext']." Communication error.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 421:
						$ResponseMessage = $response_sale['responsetext']." Communication error with issuer.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 430:
						$ResponseMessage = $response_sale['responsetext']." Duplicate transaction at processor. Please try after some time.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 440:
						$ResponseMessage = $response_sale['responsetext']." Processor format error.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 441:
						$ResponseMessage = $response_sale['responsetext']." Invalid transaction information.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 460:
						$ResponseMessage = $response_sale['responsetext']." Processor feature not available.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					case 461:
						$ResponseMessage = $response_sale['responsetext']." Unsupported card type.";
						$_SESSION['OrderDataInRequest'] = $_REQUEST;
						header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
					break;
					# Upto Here
				
				}
			}
		}catch (Exception $e) {
			$ResponseMessage = 'Caught exception: '. $e->getMessage(). "\n";
			header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
		}		
	//}
}
?>