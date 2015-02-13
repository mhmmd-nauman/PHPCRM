<?php


/********************************************
 PayReceipt.php

 This file is called after the user clicks on a button during
 the Pay process to use PayPal's AdaptivePayments Pay features'. The
 user logs in to their PayPal account.
 Called by SetPay.php
 Calls  CallerService.php,and APIError.php.
 ********************************************/

require_once '../../Lib/Config/Config.php';
require_once '../../Lib/CallerService.php';
require_once '../Common/NVP_SampleConstants.php';
session_start();

try {

	$serverName = $_SERVER['SERVER_NAME'];
	$serverPort = $_SERVER['SERVER_PORT'];
	$url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);
	$returnURL = $url."/../Common/WebflowReturnPage.php";
	$cancelURL = $url. "/Pay.php" ;
	$preapprovalKey=$_REQUEST["preapprovalkey"];
	$senderEmail  = $_REQUEST["email"];
	$request_array= array(
	Pay::$actionType => $_REQUEST['actionType'],
	Pay::$cancelUrl  => $cancelURL,
	Pay::$returnUrl=>   $returnURL,
	Pay::$currencyCode  => $_REQUEST['currencyCode'],

	Pay::$clientDetails_deviceId  => DEVICE_ID,
	Pay::$clientDetails_ipAddress  => '127.0.0.1',
	Pay::$clientDetails_applicationId =>APPLICATION_ID,
	RequestEnvelope::$requestEnvelopeErrorLanguage => 'en_US',
	Pay::$memo => $_REQUEST["memo"],
	Pay::$feesPayer => $_REQUEST["feesPayer"]

	);

	if(isset($_REQUEST['receiverEmail']))
	{
		$i = 0;
		$j = 0;
		$k = 0;

		foreach ($_REQUEST['receiverEmail'] as $value)
		{
				
			$request_array[Pay::$receiverEmail[$i]] = $value;
			$i++;
				
		}
		foreach ($_REQUEST['receiverAmount'] as $value)
		{
				
			$request_array[Pay::$receiverAmount[$j]] = $value;
			$j++;
				
		}
		foreach ($_REQUEST['primaryReceiver'] as $value)
		{
				
			$request_array[Pay::$primaryReceiver[$k]] = $value;
			$k++;
				
		}
	}

	if($preapprovalKey!= "")
	{
		$request_array[Pay::$preapprovalKey] = $preapprovalKey;
	}
	if($senderEmail!= "")
	{
		$request_array[Pay::$senderEmail]  = $senderEmail;
	}

	$nvpStr=http_build_query($request_array, '', '&');

	/* Make the call to PayPal to get the Pay token
	 If the API call succeded, then redirect the buyer to PayPal
	 to begin to authorize payment.  If an error occured, show the
	 resulting errors
	 */

	$resArray=hash_call('AdaptivePayments/Pay',$nvpStr);




	/* Display the API response back to the browser.
	 If the response from PayPal was a success, display the response parameters'
	 If the response was an error, display the errors received using APIError.php.
	 */
	$ack = strtoupper($resArray['responseEnvelope.ack']);

	if($ack!="SUCCESS"){
		$_SESSION['reshash']=$resArray;
		$location = "APIError.php";
		header("Location: $location");
	}
	else
	{
		$_SESSION['payKey'] = $resArray['payKey'];
		$payKey=$resArray['payKey'];
		if(($resArray['paymentExecStatus'] == "COMPLETED" ))
		{
			$case ="1";
		}
		else if(($_REQUEST['actionType']== "PAY") && ($resArray['paymentExecStatus'] == "CREATED" ))
		{
			$case ="2";
				
		}
		else if(($preapprovalKey!=null ) && ($_REQUEST['actionType']== "CREATE") && ($resArray['paymentExecStatus'] == "CREATED" ))
		{
			$case ="3";
		}
		else if(($_REQUEST['actionType']== "PAY_PRIMARY"))
		{
			$case ="4";
				
		}
		else if(($_REQUEST['actionType']== "CREATE") && ($resArray['paymentExecStatus'] == "CREATED" ))
		{
			$temp1=API_USERNAME;
			$temp2=str_replace('_api1.','@',$temp1);
			if($temp2==$_REQUEST["email"])
			{
				$case ="3";
			}
			else{
				$case ="2";
			}
		}
	}
}
catch(Exception $ex) {
	throw new Exception('Error occurred in PayReceipt method');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html>
<head>
<title>PayPal PHP SDK -Payment Details</title>
<link href="../Common/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../Common/tooltip.js">
    </script>
</head>

<body>
        <br/>
        <div id="jive-wrapper">
            <div id="jive-header">
                <div id="logo">
                    <span >You must be Logged in to <a href="<?php echo DEVELOPER_PORTAL;?>" target="_blank">PayPal sandbox</a></span>
                    <a title="Paypal X Home" href="#"><div id="titlex"></div></a>
                </div>
            </div>

		<?php 
require_once '../Common/menu.html';?>
<div id="request_form">

<center>
	<h3><b>Pay - Response</b></h3>


	<?php
	require_once 'ShowAllResponse.php';
	switch($case) {
		case "2" :
			$token = $resArray['payKey'];
			$payPalURL = PAYPAL_REDIRECT_URL.'_ap-payment&paykey='.$token;
			echo" <a href=$payPalURL><b>* Redirect URL to Complete Payment </b></a><br>";
			break;
		case "3" :
			$token = $resArray['payKey'];
			$payPalURL = PAYPAL_REDIRECT_URL.'_ap-payment&paykey='.$token;
			echo" <a href=$payPalURL><b>* Redirect URL to Complete Payment </b></a><br>";
			echo"<a href=SetPaymentOption.php?payKey=$payKey><b>* Set Payment Options(optional)</b></a><br>";
			echo"<a href=ExecutePaymentOption.php?payKey=$payKey><b>* Execute Payment Options</b></a><br>";

			break;
		case "4" :
			$token = $resArray['payKey'];
			$payPalURL = PAYPAL_REDIRECT_URL.'_ap-payment&paykey='.$token;
			echo"Payment to \"Primary Receiver\" is Complete<br/>";
			echo"<a href=ExecutePaymentOption.php?payKey=$payKey><b>* \"Execute Payment\" to pay to the secondary receivers</b></a><br>";
			break;

	}
	?>
</center>
</div>
</div>
</body>
</html>
