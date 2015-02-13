<?php
include_once('../../../dbcon.php');
include "../../../lib/include.php";
/** MassPay NVP example; last modified 08MAY23.
 *
 *  Pay one or more recipients. 
*/

$environment = 'sandbox';	// or 'beta-sandbox' or 'live'

/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
function PPHttpPost($methodName_, $nvpStr_) {
	global $environment;

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode('sell_1289483504_biz_api1.nadsoftdev.com');
	$API_Password = urlencode('1289483516');
	$API_Signature = urlencode('A3fC2WL1u5OyJVviVqaetqdEcOCaASPvMGIxV8.FCgHVKSuBqF6D1lfW ');
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}
if(isset($_POST['action']))
{
	if($_POST['action']=='paytocoach')
	{
		$query_coach_detail='SELECT * FROM `Member` WHERE `ID`='.$_POST['coach_id'];
		$result_coach_detail=mysql_query($query_coach_detail) or die(" There is an error in query coach detail $query_coach_detail ".mysql_error());
		$row_coach_detail=mysql_fetch_array($result_coach_detail);
		
		// Set request-specific fields.
		$emailSubject =urlencode('Thank you');
		$receiverType = urlencode('EmailAddress');
		$currency = urlencode('USD');							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		
		// Add request-specific fields to the request string.
		$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";
		
		/*$receiversArray = array();
		$receiverData = array(	'receiverEmail' => "mebuyer@nadsoft.com",
									'amount' => "10.0",
									'uniqueID' => "5",
									'note' => "This is the payment");
			$receiversArray[0] = $receiverData;
			$row_coach_detail['Email']
			*/
			$receiverData = array(	'receiverEmail' => "shrikant@nadsoftdev.com",
									'amount' => $_POST['amount'],
									'uniqueID' => "1",
									'note' => "note 1");
			$receiversArray[0] = $receiverData;
		
		foreach($receiversArray as $i => $receiverData) {
			$receiverEmail = urlencode($receiverData['receiverEmail']);
			$amount = urlencode($receiverData['amount']);
			$uniqueID = urlencode($receiverData['uniqueID']);
			$note = urlencode($receiverData['note']);
			$nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
		}
		
		// Execute the API operation; see the PPHttpPost function above.
		$httpParsedResponseAr = PPHttpPost('MassPay', $nvpStr);
		
		$responce=array ( 'TIMESTAMP' => urldecode($httpParsedResponseAr["TIMESTAMP"]), 'CORRELATIONID' => urldecode($httpParsedResponseAr["CORRELATIONID"]), 'ACK' => strtoupper($httpParsedResponseAr["ACK"]) ,'VERSION' => urldecode($httpParsedResponseAr["VERSION"]), 'BUILD' => urldecode($httpParsedResponseAr["BUILD"]) ); 
		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			
			echo json_encode($responce);
		} else  {
			echo json_encode($responce);
		}
	}
	if($_POST['action']=='paytocoach_batch')
	{
		$coach_id_array=explode(',',$_POST['coach_ids']);
		$amount_array=explode(',',$_POST['amounts']);
		foreach($coach_id_array as $key=>$value)
		{
			$query_coach_detail='SELECT * FROM `Member` WHERE `ID`='.$value;
			$result_coach_detail=mysql_query($query_coach_detail) or die(" There is an error in query coach detail $query_coach_detail ".mysql_error());
			$row_coach_detail=mysql_fetch_array($result_coach_detail);
			$receiverData = array(	'receiverEmail' => $row_coach_detail['Email'],
									'amount' => $amount_array[$key],
									'uniqueID' => "1",
									'note' => "note 1");
			$receiversArray[$key] = $receiverData;
		}
		$emailSubject =urlencode('Thank you');
		$receiverType = urlencode('EmailAddress');
		$currency = urlencode('USD');							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		
		// Add request-specific fields to the request string.
		$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";
		foreach($receiversArray as $i => $receiverData) {
			$receiverEmail = urlencode($receiverData['receiverEmail']);
			$amount = urlencode($receiverData['amount']);
			$uniqueID = urlencode($receiverData['uniqueID']);
			$note = urlencode($receiverData['note']);
			$nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
		}
		// Execute the API operation; see the PPHttpPost function above.
		$httpParsedResponseAr = PPHttpPost('MassPay', $nvpStr);
		
		$responce=array ( 'TIMESTAMP' => urldecode($httpParsedResponseAr["TIMESTAMP"]), 'CORRELATIONID' => urldecode($httpParsedResponseAr["CORRELATIONID"]), 'ACK' => strtoupper($httpParsedResponseAr["ACK"]) ,'VERSION' => urldecode($httpParsedResponseAr["VERSION"]), 'BUILD' => urldecode($httpParsedResponseAr["BUILD"]) ); 
		echo json_encode($responce);
	}
}
?>