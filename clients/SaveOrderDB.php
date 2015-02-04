<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
if(!isset($orderStatus)){
	$orderStatus = "Unpaid";	
}

date_default_timezone_set(timezone_name_from_abbr("EST"));
# Only in case when the Collect payment later check box is checked.
# Generate Random number to save in the Collect Payment Later Id which will be sent along with the link
# to the new clients and this will be matched along with the Order ID which will confirm that the Order
# ID has not been manipulated before opening that up in the browser and so no harm can be caused anyhow.
$rand1 = generateRandomString();
$rand2 = generateRandomString();
$UniqueCPLID = $rand1.$rand2;


if($merchantType==2){
	$totalPrice = $_REQUEST['XOStotal'];
	$type = 'External';	
} else {
	$totalPrice = $_REQUEST['totalPrice'];
	$type = 'Internal';
}
if(!isset($contactID)){
	$contactID = 0;	
}
$contactID = (int)$contactID;

// if no transaction
if(($_POST['voidtransaction'] == "on" || $_POST['voidtransaction'] == 1) and !empty($_POST['voidtransaction']) || $isVendor===true){
	$added_member_id = $objClient->InsertClients(array(
		"Created"					=> date("Y-m-d H:i:s"),
		"LastEdited"			=> date("Y-m-d H:i:s"),
		"CompanyName" 				=> $_REQUEST['cname'],
		"FirstName" 				=> $_REQUEST['fName'],
		"Surname" 					=> $_REQUEST['sureName'],
		"Email" 					=> $_REQUEST['Email'],
		"Phone" 					=> $_REQUEST['phone'],
		"MobilePhone" 				=> $_REQUEST['mobilephone'],
		"AlternatePhone" 			=> $_REQUEST['alternatephone'],
		"TimeBilling"				=> date("Y-m-d H:i:s"),
		"Notes" 					=> $_REQUEST['OthersNotes'],
		"SubmitedBy" 				=> $_SESSION['Member']['ID'],
		"CustomersTimeZone" 		=> $_REQUEST['TimeZone'],
		"BestCallTime"				=> $BestCallTime,
		"Address"					=> $_REQUEST['Streetaddress1'],
		"Address2"					=> $_REQUEST['Streetaddress2'],
		"City"						=> $_REQUEST['Bcity'],
		"State"						=> $_REQUEST['Bstate'],
		"ZipCode"					=> $_REQUEST['Bpostalcode'],
		"Agent_IP"					=> $_SERVER['REMOTE_ADDR'],
		"Agent_Browser_Details"		=> $_REQUEST['browser_details'],
		"Time_Spent"				=> $_REQUEST['total_time_spend'],
		"AccountAddress"			=> $_REQUEST['accountAddress'],
		"WebsiteURL"				=> $_REQUEST['businessWebsite'],
		"HoursOfOperation"			=> $_REQUEST['businessHours'],
		"Services"					=> $_REQUEST['businessServices'],
		"Founded"					=> $_REQUEST['businessFounded'],
		"IScontactID"				=> $contactID
	));
	
	$pro_sel = $_REQUEST['select_pay_process'];
	$paid_through = "";
	if($pro_sel == 1){
		$paid_through = "Credit Card (Process Now)";
	}elseif($pro_sel == 2){
		$paid_through = "Credit Card (Process Later)";
	}elseif($pro_sel == 3){
		$paid_through = "eCheck";
	}elseif($pro_sel == 4){
		$paid_through = "Create Order and Client Only";
	}elseif($pro_sel == 5){
		$paid_through = "Check";
	}elseif($pro_sel == 6){
		$paid_through = "Cash";
	}elseif($pro_sel == 7){
		$paid_through = "Money Order";
	}elseif($pro_sel == 8){
		$paid_through = "Adjustment";
	}elseif($pro_sel == 9){
		$paid_through = "Credit";
	}elseif($pro_sel == 10){
		$paid_through = "Refund";
	}elseif($pro_sel == 11){
		$paid_through = "Write Off";
	} 
	
	if(!empty($_REQUEST['Cnumber'])){
		$encryptcard = $objClient->encrypt($_REQUEST['Cnumber']);
	}else{
		$encryptcard = "";
	}
	
	if($_REQUEST['select_pay_process'] == 2){
		$process_later_date = date("Y-m-d h:i:s",strtotime($_REQUEST['process_later_date']));
	}else{
		$process_later_date = "";
	}
	

	if(!empty($added_member_id)){
		$inserted_order = $objorder->InsertWithPackage(array(
			"Created"				=> date("Y-m-d H:i:s"),
			"LastEdited"			=> date("Y-m-d H:i:s"),
			"MemberID"				=> $added_member_id,
			"CredetCardType"		=> $_REQUEST['creditcardtype'],
			"CredetCardNumber"		=> $encryptcard,
			"ExpirationMonth"		=> $_REQUEST['ExpiryMonth'],
			"ExpirationYear"		=> $_REQUEST['ExpiryYears'],
			"SecurityCode"			=> $_REQUEST['Ccode'],
			
			"Bank_Name"				=> $_REQUEST['bank_name'],
			"AccountName"			=> $_REQUEST['name_on_account'],
			"RoutingNumber"			=> $_REQUEST['routing_number'],
			"AccountNumber"			=> $_REQUEST['account_number'],
			"AccountHolderType"		=> $_REQUEST['account_holder_type'],
			"AccountType"			=> $_REQUEST['account_type'],
			"Check_type"			=> $_REQUEST['check_type'],
			"Check_Number"			=> $_REQUEST['check_number'],
			"FirstName" 			=> $_REQUEST['fName'],
			"Surname" 				=> $_REQUEST['sureName'],
			"Email" 				=> $_REQUEST['Email'],
			"Phone" 				=> $_REQUEST['phone'],
			"MobilePhone" 			=> $_REQUEST['mobilephone'],
			"AlternatePhone" 		=> $_REQUEST['alternatephone'],
			"Notes" 				=> $_REQUEST['OthersNotes'],
			"Status"				=> $orderStatus,
			"Created"				=> date("Y-m-d H:i:s",time()),
			"TotalPrice"			=> $totalPrice,
			"Type"					=> $type,
			"CardCharged"			=> 0,
			"CompanyName"			=> $_REQUEST['cname'],
			"StreetAddress1"		=> $_REQUEST['Streetaddress1'],
			"StreetAddress2"		=> $_REQUEST['Streetaddress2'],
			"BillingCity"			=> $_REQUEST['Bcity'],
			"BillingState"			=> $_REQUEST['Bstate'],
			"BillingPostalCode"		=> $_REQUEST['Bpostalcode'],
			"BillingCountry"		=> $_REQUEST['BillingCountry'],
			"UserID"				=> $_SESSION['Member']['ID'],
			"MerchantAccID"			=> $merchantRecords['MerchantId'],
			"CPLID"					=> $UniqueCPLID,
			"PaidThrough"			=> $paid_through,
			"NotesForpaidThrough"	=> $_REQUEST['paymentnoteswhileorder'],
			"Process_Later_Date"	=> $process_later_date
		));
	}
	if(!empty($added_member_id)){
		$Order_created_without_charge = 1;
	}else{
		$Order_created_without_charge = 0;
	}	
	
	if($_REQUEST['select_pay_process'] == 2 || $_REQUEST['select_pay_process'] == 3){
		$inserted_cc_info = $objorder->InsertClientCCinfo(array(
			"AccountName"			=> $_REQUEST['name_on_account'],
			"BankName"				=> $_REQUEST['bank_name'],
			"RoutingNumber"			=> $_REQUEST['routing_number'],
			"AccountNumber"			=> $_REQUEST['account_number'],
			"AccHolderType"			=> $_REQUEST['account_holder_type'],
			"AccountType"			=> $_REQUEST['account_type'],
			"ChequeType"			=> $_REQUEST['check_type'],
			"ChequeNumber"			=> $_REQUEST['check_number'],
			"ClientID"				=> $added_member_id,
			"CCType"				=> $_REQUEST['creditcardtype'],
			"CreditCardNumber"		=> $encryptcard,
			"Exp_Month"				=> $_REQUEST['ExpiryMonth'],
			"Exp_Year"				=> $_REQUEST['ExpiryYears'],
			"CVV"					=> $_REQUEST['Ccode'],
			"AddedFrom"				=> "HomePageOrderForm",
			"CCStatus"				=> $CardStatus,
		));
	}
		
}else{
	// if there is a transaction
	if($_REQUEST['id'] > 0){
		$added_member_id = $_REQUEST['id'];
	}else{
		$Order_created_without_charge = "";	
		$added_member_id = $objClient->InsertClients(array(
			"Created"					=> date("Y-m-d H:i:s"),
			"LastEdited"			=> date("Y-m-d H:i:s"),
			"CompanyName" 				=> $_REQUEST['cname'],
			"FirstName" 				=> $_REQUEST['fName'],
			"Surname" 					=> $_REQUEST['sureName'],
			"Email" 					=> $_REQUEST['Email'],
			"Phone" 					=> $_REQUEST['phone'],
			"MobilePhone" 				=> $_REQUEST['mobilephone'],
			"AlternatePhone" 			=> $_REQUEST['alternatephone'],
			"TimeBilling"				=> date("Y-m-d H:i:s"),
			"Notes" 					=> $_REQUEST['OthersNotes'],
			"SubmitedBy" 				=> $_SESSION['Member']['ID'],
			"CustomersTimeZone" 		=> $_REQUEST['TimeZone'],
			"BestCallTime"				=> $BestCallTime,
			"Address"					=> $_REQUEST['Streetaddress1'],
			"Address2"					=> $_REQUEST['Streetaddress2'],
			"City"						=> $_REQUEST['Bcity'],
			"State"						=> $_REQUEST['Bstate'],
			"ZipCode"					=> $_REQUEST['Bpostalcode'],
			"Agent_IP"					=> $_SERVER['REMOTE_ADDR'],
			"Agent_Browser_Details"		=> $_REQUEST['browser_details'],
			"Time_Spent"				=> $_REQUEST['total_time_spend'],
			"WebsiteURL"				=> $_REQUEST['businessWebsite'],
			"HoursOfOperation"			=> $_REQUEST['businessHours'],
			"Services"					=> $_REQUEST['businessServices'],
			"Founded"					=> $_REQUEST['businessFounded'],
			"IScontactID"				=> $contactID 
		));
		
	}
	$encryptcard = $objClient->encrypt($_REQUEST['Cnumber']);
	
	$CardCharged = "";
	if(!empty($Transaction) and $Transaction == "Successfull"){
		$CardCharged = 1;
		$Paid = "Paid";
		$CardStatus = 1;
		
		$date = date("Y-m-d");
		if($CardCharged == "1"){
			$amount = $_REQUEST['totalprice'];
			$clientID=  $added_member_id;
		
		} else {
			$amount = "0";	
			$amount = $_REQUEST['totalprice'];
			
		}
		$sql1 = "INSERT INTO `Payments` (Date, Amount, ClientID) VALUES('".$date."','".$amount."','".$clientID."')"; 
			mysqli_query($link, $sql1);
			$last_inserted_id = mysqli_insert_id($link);

	}else{
		$CardCharged = 0;
		if(isset($orderStatus)){
			$Paid = $orderStatus;	
		} else{
			$Paid = "Unpaid";
		}
		$CardStatus = 0;
	}
	if(empty($CardCharged)){
		$CardCharged = 0;
	}
	
	$inserted_order = $objorder->InsertWithPackage(array(
		"Created"				=> date("Y-m-d H:i:s"),
		"LastEdited"			=> date("Y-m-d H:i:s"),
		"MemberID"				=> $added_member_id,
		"CredetCardType"		=> $_REQUEST['creditcardtype'],
		"CredetCardNumber"		=> $encryptcard,
		"ExpirationMonth"		=> $_REQUEST['ExpiryMonth'],
		"ExpirationYear"		=> $_REQUEST['ExpiryYears'],
		"SecurityCode"			=> $_REQUEST['Ccode'],
		"AccountName"			=> $_REQUEST['Aname'],
		"RoutingNumber"			=> $_REQUEST['Rnumber'],
		"AccountNumber"			=> $_REQUEST['Anumber'],
		"AccountHolderType"		=> $_REQUEST['AHtype'],
		"AccountType"			=> $_REQUEST['Atype'],
		"FirstName" 			=> $_REQUEST['fName'],
		"Surname" 				=> $_REQUEST['sureName'],
		"Email" 				=> $_REQUEST['Email'],
		"Phone" 				=> $_REQUEST['phone'],
		"MobilePhone" 			=> $_REQUEST['mobilephone'],
		"AlternatePhone" 		=> $_REQUEST['alternatephone'],
		"Notes" 				=> $_REQUEST['OthersNotes'],
		"Status"				=> $Paid,
		"Created"				=> date("Y-m-d H:i:s",time()),
		"TotalPrice"			=> $totalPrice,
		"Type"					=> $type,
		"CardCharged"			=> $CardCharged,
		"CompanyName"			=> $_REQUEST['cname'],
		"StreetAddress1"		=> $_REQUEST['Streetaddress1'],
		"StreetAddress2"		=> $_REQUEST['Streetaddress2'],
		"BillingCity"			=> $_REQUEST['Bcity'],
		"BillingState"			=> $_REQUEST['Bstate'],
		"BillingPostalCode"		=> $_REQUEST['Bpostalcode'],
		"BillingCountry"		=> $_REQUEST['BillingCountry'],
		"UserID"				=> $_SESSION['Member']['ID'],
		"MerchantAccID"			=> $merchantRecords['MerchantId'],
		"PaidThrough"			=> "Credit Card",
		"NotesForpaidThrough"	=> $_REQUEST['paymentnoteswhileorder']
	));
	
	$inserted_cc_info = $objorder->InsertClientCCinfo(array(
		"ClientID"				=> $added_member_id,
		"CCType"				=> $_REQUEST['creditcardtype'],
		"CreditCardNumber"		=> $encryptcard,
		"Exp_Month"				=> $_REQUEST['ExpiryMonth'],
		"Exp_Year"				=> $_REQUEST['ExpiryYears'],
		"CVV"					=> $_REQUEST['Ccode'],
		"AddedFrom"				=> "HomePageOrderForm",
		"CCStatus"				=> $CardStatus,
	));

}
# We might need to save that in orderItem Table
$product_array_name = $_REQUEST['product_array_name'];
$product_array_price = $_REQUEST['product_array_price'];
$product_array_id = $_REQUEST['product_array_id'];
$product_array_id_for_product = $_REQUEST['product_array_id_for_product'];
$product_array_descrption = $_REQUEST['product_array_descrption'];
$product_array_promos_package = $_REQUEST['product_array_promos_package'];
$product_array_prices_package = $_REQUEST['product_array_prices_package'];
$product_array_quantity_package = $_REQUEST['product_array_qty_package'];
  	    
foreach((array)$product_array_id as $package_id => $package_array){			  
	foreach((array)$package_array as $product_id){ 
		$objorder->InsertOrderDetail(array(
			"PackagesID"		=> $package_id,
			"OrderID"			=> $inserted_order,
			"ProductName"		=> $product_array_name[$package_id][$product_id],
			"ProductPrice"		=> $product_array_prices_package[$package_id][$product_id],
			"ProductID"			=> $product_id,
			"Description"		=> $product_array_descrption[$package_id][$product_id],
			"Promo"				=> $product_array_promos_package[$package_id][$product_id],
			"Quantity"			=> $product_array_quantity_package[$package_id][$product_id],
		));
	}
}
             
unset($_SESSION['OrderData']['Packages']);
$product_array_promos  = $_REQUEST['product_array_promos'];
$product_array_qty     = $_REQUEST['product_array_qty'];
$product_array_price   = $_REQUEST['product_array_price'];
$product_array_name    = $_REQUEST['product_array_name'];
foreach((array)$product_array_id_for_product as $product_id){
	$objorder->InsertOrderDetail(array(
		"PackagesID"		=> 0,
		"OrderID"			=> $inserted_order,
		"ProductName"		=> $product_array_name[$product_id],
		"ProductPrice"		=> $product_array_price[$product_id],
		"ProductID"			=> $product_id,
		"Promo"				=> $product_array_promos[$product_id],
		"Quantity"			=> $product_array_qty[$product_id],
	));
}




?>