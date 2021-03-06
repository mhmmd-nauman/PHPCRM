<?php
require_once "../../../lib/include.php";
$utilObj = new util();
//-------------------------------------------------
// When you integrate this code,
// look for TODO as an indication
// that you may need to provide a value or take 
// action before executing this code.
//-------------------------------------------------

require_once ("paypalplatform.php");


// ==================================
// PayPal Platform Implicit Payment Module
// ==================================

// Request specific required fields
$senderEmail = "vishal_1348733217_biz_1348807435_biz@nadsoftdev.com"; // TODO - The PayPal account email address of the sender
                   // Think of it as required for an implicit payment and
                   // set to the same account that owns the API credentials
$actionType = "PAY";
$cancelUrl = "https://NoOp";	// There is no approval step for implicit payment
$returnUrl = "https://NoOp";	// There is no approval step for implicit payment
$currencyCode = "USD";


// An implicit payment can be a simple or parallel or chained payment
// TODO - Specify the receiver emails
//        Remove or set to an empty string the array entries for receivers 
//        that you do not have for a simple payment, specify only 
//        receiver0email, and remove the other array entries
$receiverEmailArray = array('vishal.gaikwad56@gmail.com');

// TODO - Specify the receiver amounts as the amount of money, 
//        for example, '5' or '5.55'. Remove or set to an empty 
//        string the array entries for receivers that you do not have
//        for a simple payment, specify only receiver0amount, and remove 
//        the other array entries
$receiverAmountArray = array('1');

// TODO - Specify values ONLY if you are doing a chained payment
//        If you are doing a chained payment, then set ONLY 1 receiver in the 
//        array to 'true' as the primary receiver, and set the other receivers
//        corresponding to those indicated in receiverEmailArray to 'false'
//        Make sure that you do NOT specify more values 
//        in this array than in the receiverEmailArray
$receiverPrimaryArray = array(
  '',
  '',
  '',
  '',
  '',
  ''
  );

// TODO - Set invoiceId to uniquely identify the transaction 
//        associated with each receiver
//        Set the array entries with value for receivers that you have
//        Each of the array values must be unique
$receiverInvoiceIdArray = array(
  '',
  '',
  '',
  '',
  '',
  ''
  );

// Request specific optional fields
//   Provide a value for each field that you want to include in the 
//   request; if left as an empty string, the field will not be passed 
//   in the request.
$feesPayer = ""; // For an implicit payment use case, this cannot be "SENDER"
$ipnNotificationUrl = "";
$memo = ""; // maxlength is 1000 characters
$pin = ""; // No pin for an implicit payment use case
$preapprovalKey = ""; // No preapprovalKey for an implicit use case
$reverseAllParallelPaymentsOnError = ""; // Only specify if you are doing a 
                                         //parallel payment as your implicit 
                                         //payment
$trackingId = generateTrackingID(); // generateTrackingID function is found 
                                    // in paypalplatform.php

//-------------------------------------------------
// Make the Pay API call
//
// The CallPay function is defined in the paypalplatform.php file,
// which is included at the top of this file.
//-------------------------------------------------
$resArray = CallPay ($actionType, $cancelUrl, $returnUrl, $currencyCode, 
      $receiverEmailArray, $receiverAmountArray, $receiverPrimaryArray, 
      $receiverInvoiceIdArray, $feesPayer, $ipnNotificationUrl, $memo, 
      $pin, $preapprovalKey, $reverseAllParallelPaymentsOnError, 
      $senderEmail, $trackingId
);

$ack = strtoupper($resArray["responseEnvelope.ack"]);
if($ack=="SUCCESS")
{
 // payKey is the key that you can use to identify the payment resulting 
 // from the Pay call.
 $coachid=$_POST['CoachId'];
 $amt=$_POST['amt'];
 $fdate=date("Y-m-d",strtotime($_POST['fdate']));
 $tdate=date("Y-m-d",strtotime($_POST['tdate']));
 $payKey = urldecode($resArray["payKey"]);
 $paymentExecStatus = urldecode($resArray["paymentExecStatus"]);
 $type="Coach";
 // paymentExecStatus is the status of the payment
 $arrValue2=array('CoachId'=>$coachid,'Payment'=>$amt,'FromDate'=>$fdate,'ToDate'=>$tdate,'PayKey'=>$payKey,'Status'=>$paymentExecStatus, 'Type'=>$type, 'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  $insertedId=$utilObj->insertRecord('CommissionPayment', $arrValue2);
 
echo $paymentExecStatus = urldecode($resArray["paymentExecStatus"]);

} 
else  
{
 //Display a user-friendly Error on the page using any of the following 
 //error information returned by PayPal.
 //TODO - There can be more than 1 error, so check for "error(1).errorId", 
 // then "error(2).errorId", and so on until you find no more errors.
 $ErrorCode = urldecode($resArray["error(0).errorId"]);
 $ErrorMsg = urldecode($resArray["error(0).message"]);
 $ErrorDomain = urldecode($resArray["error(0).domain"]);
 $ErrorSeverity = urldecode($resArray["error(0).severity"]);
 $ErrorCategory = urldecode($resArray["error(0).category"]);
 
 echo "Preapproval API call failed. ";
 echo "Detailed Error Message: " . $ErrorMsg;
 echo "Error Code: " . $ErrorCode;


 echo "Error Severity: " . $ErrorSeverity;
 echo "Error Domain: " . $ErrorDomain;
 echo "Error Category: " . $ErrorCategory;
}
?>