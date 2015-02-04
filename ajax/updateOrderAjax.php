<?php

include dirname(__FILE__)."/../lib/include.php"; 
$objClient = new Clients();
$objOrder = new Orders();

$mid = $_REQUEST['mid'];
$where = "MemberID = '"  .$mid . "'";

if(isset($_REQUEST['Email'])){
	
	$array = array(
		"Email" =>$_REQUEST['Email'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}

if(isset($_REQUEST['FirstName'])){
	
	$array = array(
		"FirstName" =>$_REQUEST['FirstName'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}


if(isset($_REQUEST['Surname'])){
	
	$array = array(
		"Surname" =>$_REQUEST['Surname'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}

if(isset($_REQUEST['Phone'])){
	
	$array = array(
		"Phone" =>$_REQUEST['Phone'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}

if(isset($_REQUEST['MobilePhone'])){
	
	$array = array(
		"MobilePhone" =>$_REQUEST['MobilePhone'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}

if(isset($_REQUEST['AlternatePhone'])){
	
	$array = array(
		"AlternatePhone" =>$_REQUEST['AlternatePhone'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}

if(isset($_REQUEST['createddate'])){
	
	$array = array(
		"Created" =>$_REQUEST['createddate'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}

if(isset($_REQUEST['Address'])){
	
	$array = array(
		"StreetAddress1" =>$_REQUEST['Address'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}
if(isset($_REQUEST['State'])){
	
	$array = array(
		"BillingState" =>$_REQUEST['State'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}

if(isset($_REQUEST['Zip'])){
	
	$array = array(
		"BillingPostalCode" =>$_REQUEST['Zip'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}
if(isset($_REQUEST['City'])){
	
	$array = array(
		"BillingCity" =>$_REQUEST['City'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;

}

if(isset($_REQUEST['cname'])){
	$array = array(
		"CompanyName" =>$_REQUEST['cname'] 
	);
	
	$id = $objOrder->updateOrderFields($where, $array);
	echo $id;
}
?>