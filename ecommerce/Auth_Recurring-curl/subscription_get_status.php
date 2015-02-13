<?php

/****NOTE***
Please download the PHP SDK available at https://developer.authorize.net/downloads/ for more current code.
*/

/*
D I S C L A I M E R                                                                                          
WARNING: ANY USE BY YOU OF THE SAMPLE CODE PROVIDED IS AT YOUR OWN RISK.                                                                                   
Authorize.Net provides this code "as is" without warranty of any kind, either express or implied, including but not limited to the implied warranties of merchantability and/or fitness for a particular purpose.   
Authorize.Net owns and retains all right, title and interest in and to the Automated Recurring Billing intellectual property.
*/

/*include ("data.php");
include ("authnetfunction.php");*/

//define variables to send

//$subscriptionId = 1469315; //$_POST["subscriptionId"];

$subscriptionId = $subscription_id;
//echo "get subscription status <br>";

//build xml to post
$content =
        "<?xml version=\"1.0\" encoding=\"utf-8\"?>".
        "<ARBGetSubscriptionStatusRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
        "<merchantAuthentication>".
        "<name>" . $loginname . "</name>".
        "<transactionKey>" . $transactionkey . "</transactionKey>".
        "</merchantAuthentication>" .
        "<subscriptionId>" . $subscriptionId . "</subscriptionId>".
        "</ARBGetSubscriptionStatusRequest>";

//send the xml via curl
      $responsestatus = send_request_via_curl($host,$path,$content);

//header ("Content-Type:text/xml");  
//echo $response;

//echo "<pre/>";
//print_r($response);

//if curl is unavilable you can try using fsockopen
/*
$response = send_request_via_fsockopen($host,$path,$content);
*/

//echo "<pre/>";
//print_r($response);


//if the connection and send worked $response holds the return from Authorize.net
if ($responsestatus)
{
		/*
	a number of xml functions exist to parse xml results, but they may or may not be avilable on your system
	please explore using SimpleXML in php 5 or xml parsing functions using the expat library
	in php 4
	parse_return is a function that shows how you can parse though the xml return if these other options are not avilable to you
	*/
	$array=list ( $refId,$resultCode,$code,$text,$responcesubscriptionId,$status) =parse_return($responsestatus);
	
/*	echo "<pre/>";
	print_r($array);
	*/
	

	
	/*echo " Response Code: $resultCode <br>";
	echo " Response Reason Code: $code<br>";
	echo " Response Text: $text<br>";
	echo " Subscription Id: $subscriptionId <br><br>";
	echo " status: $status <br><br>";
	echo " Data has been written to data.log<br><br>";
	

/* write data to log file or database */
/* $fp = fopen('data.log', "a");
fwrite($fp, "$subscriptionId\r\n");
fwrite($fp, "$text\r\n");
fclose($fp);*/

	
}
/*else
{
	echo "Transaction Failed. <br>";
}*/
?>