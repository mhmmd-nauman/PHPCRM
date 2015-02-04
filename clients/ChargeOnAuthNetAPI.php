<?php
if( $_REQUEST['id'] > 0 ){
    $backurl = "AddClientOrder";
}else{
    $backurl = "AddClients";
}
$order_charged = 0;
define("AUTHORIZENET_API_LOGIN_ID", $LoginID);
define("AUTHORIZENET_TRANSACTION_KEY", $TransKey);
define("AUTHORIZENET_SANDBOX", $SandBox);
$CreditCard0ExpirationYear = $_REQUEST['ExpiryYears'];
$CreditCard0ExpirationMonth = $_REQUEST['ExpiryMonth'];
$year = substr((string)$CreditCard0ExpirationYear,-2);

$transaction_auth = new AuthorizeNetAIM;
$creditcardtype                       = $_REQUEST['creditcardtype'];
$transaction_auth->amount             = $_REQUEST['totalprice'];
$transaction_auth->card_num           = $_REQUEST['Cnumber'];
$transaction_auth->exp_date	      = $CreditCard0ExpirationMonth.'/'. $year;

$response_auth = $transaction_auth->authorizeOnly();

# Added the Insertion into the database here outside of any of the conditions
if($response_auth->response_code != "1") {
	require_once('SaveOrderDB.php');
}

if ($response_auth->approved || $response_auth->response_code == "1") {
    $invoice_no = $_SESSION['Member']['ID']."-".$added_member_id."-".$inserted_order;
    $transaction_charge = new AuthorizeNetAIM;
    
    $creditcardtype                       	= $_REQUEST['creditcardtype'];
    $transaction_charge->amount             = $_REQUEST['totalprice'];
    $transaction_charge->card_num           = $_REQUEST['Cnumber'];
    $transaction_charge->exp_date	    	= $CreditCard0ExpirationMonth.'/'. $year;
    $transaction_charge->description        = $_REQUEST['OthersNotes'];
    $transaction_charge->first_name         = $_REQUEST['fName'];
    $transaction_charge->last_name          = $_REQUEST['sureName'];
    $transaction_charge->company            = $_REQUEST['cname'];
    $transaction_charge->address            = "";
    $transaction_charge->city               = "";
    $transaction_charge->state              = "";
    $transaction_charge->zip                = "";
    $transaction_charge->country            = $_REQUEST['BillingCountry'];
    $transaction_charge->phone              = $_REQUEST['phone'];
    $transaction_charge->fax                = $fax = "";
    $transaction_charge->email              = $_REQUEST['Email'];
    $transaction_charge->cust_id            = 0;
    $transaction_charge->customer_ip        = $_SERVER['REMOTE_ADDR'];
    $transaction_charge->invoice_num        = $invoice_no;
    $transaction_charge->ship_to_first_name = $_REQUEST['fName'];
    $transaction_charge->ship_to_last_name  = $_REQUEST['sureName'];
    $transaction_charge->ship_to_company    = $_REQUEST['cname'];
    $transaction_charge->ship_to_address    = "";
    $transaction_charge->ship_to_city       = "";
    $transaction_charge->ship_to_state      = "";
    $transaction_charge->ship_to_zip        = "";
    $transaction_charge->ship_to_country    = $ship_to_country = $_REQUEST['BillingCountry'];
    $transaction_charge->tax                = $tax = "0.00";
    $transaction_charge->freight            = $freight = "";
    $transaction_charge->duty               = $duty = "";
    $transaction_charge->tax_exempt         = $tax_exempt = "";
    $transaction_charge->po_num             = "PO".time() ;
	# Below function was set to $transaction_charge->authorizeAndCapture();
	# Now changed to captureOnly()
    $response_charge = $transaction_charge->captureOnly();
	$Transaction = "";
	require_once('SaveOrderDB.php');
	
    if ($response_charge->approved || $response_charge->response_code == "1") {
		$ResponseMessage = $response_charge->response_reason_text;
		$response_code = $response_charge->response_code;
		$authcode = $response_charge->authorization_code;
		$transaction_id = $response_charge->transaction_id;
		$order_charged = 1;
		$Transaction = "Successfull";
		# $inserted_order is the last Inserted ID in the OrderDetails table which can be now used to update
		# the Status and the Card Charged in this table to set the status as paid as till now it is set as unpaid
		require_once('UpdatePaymentDetails.php');
		require_once('SaveMerchantReturnInfo.php');
    }else{
        # roll back the order
    }
 
} else {
     $_SESSION['OrderDataInRequest'] = $_REQUEST;
    switch($response_auth->response_code){
        case "1":
            # $ResponseMessage = $response_auth->response_reason_text."<br>This transaction has been approved.";
            # header("location:AddClients.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage");
            break;
        case "2":
            $ResponseMessage = $response_auth->response_reason_text."<br>This transaction has been declined.";
            header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
            exit;
            break;
        case "3":
            $ResponseMessage = $response_auth->response_reason_text."<br>There has been an error processing this transaction.";
           
            header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
            echo "it shoud not come here";
            exit;
            break;
        case "4":
            $ResponseMessage = $response_auth->response_reason_text."<br>This transaction is being held for review.";
            header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
            exit;
            break;
        default:
            $ResponseMessage = $response_auth->response_reason_text."<br>Default Error!";
            header("location:$backurl.php?tab=1&flag=card_faild_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['id']);
            exit;
            break;
      }
}
?>