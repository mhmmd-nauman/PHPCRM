<?php
if(empty($added_member_id)){
	$added_member_id = $ClientID;
}

$GetUser = $objClient->GetAllClients(" `ID` = '$added_member_id' ",array("SubmitedBy"));
$AgentID = $GetUser[0]['SubmitedBy'];
$GetUser = $objuser->GetAllUsers(" ".USERS.".`ID` = '$AgentID' ",array(ZONES.".*"));
$TZ = $GetUser[0]["ZoneTime"];
$Zone_Name = $GetUser[0]["Name"];

$date = new DateTime('now', new DateTimeZone($TZ));
$Current_date_time = $date->format('Y-m-d H:i:s'); # Only For Agents
if(empty($Current_date_time)){
	$Current_date_time = date('Y-m-d H:i:s');
}

# send the transaction to payament gateway            
$ObjMerchantAccount->InsertTransacions(array(
	"Created"				=> date('Y-m-d H:i:s'),
	"Agent_Time_Stamp"		=> $Current_date_time,
	"Agent_Zone"			=> $TZ,
	"Zone_Name"				=> $Zone_Name,
	"FirstName"				=> $_REQUEST['fName'],
	"LastName"				=> $_REQUEST['sureName'],
	"Company"				=> $_REQUEST['cname'],
	"Address"				=> $_REQUEST['Streetaddress1']." ".$_REQUEST['Streetaddress2'],
	"City"					=>  $_REQUEST['Bcity'],
	"State"					=> $_REQUEST['Bstate'],
	"Zip"					=> $_REQUEST['Bpostalcode'],
	"Country"				=> $_REQUEST['BillingCountry'],
	"Phone"					=> $_REQUEST['phone'],
	"Fax"					=> "",
	"Email"					=> $_REQUEST['Email'],
	"CustomerID"			=> $added_member_id,
	"InvoiceNum"			=> $invoice_no,
	"ShipToFirstName"		=> $_REQUEST['fName'],
	"ShipToLastName"		=> $_REQUEST['sureName'],
	"ShipToCompany"			=> $_REQUEST['cname'],
	"ShipToAddress"			=> $_REQUEST['Streetaddress1']." ".$_REQUEST['Streetaddress2'],
	"ShipToCity"			=> $_REQUEST['Bcity'],
	"ShipToState"			=> $_REQUEST['Bstate'],
	"ShipToZip"				=> $_REQUEST['Bpostalcode'],
	"ShipToCountry"			=> $_REQUEST['BillingCountry'],
	"Tax"					=> 0,
	"Freight"				=> 0,
	"Duty"					=> 0,
	"TaxExempt"				=> 0,
	"ResponseCode"			=> $response_sale['response_code'],
	"TransactionType"		=> $response_sale['type'],
	"ResponseSubcode"		=> $response_sale['response'], # This is the first parameter in the response.
	"ResponseReasonText"	=> $response_sale['responsetext'],
	"AuthorizationCode"		=> $response_sale['authcode'],
	"TransactionID"			=> $transaction_id,
	"MemberID"				=> $added_member_id,
	"OrderID"				=> $inserted_order,
));

?>