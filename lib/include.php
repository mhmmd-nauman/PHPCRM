<?php
@session_start();
# User will be logged in for 5 hours if the user is ideal
@ini_set('session.gc_maxlifetime', 18000);
@session_set_cookie_params(18000);
require_once dirname(__FILE__)."/../dbcon.php";
require_once dirname(__FILE__)."/../lib/classes/config/variables.php";
require_once dirname(__FILE__)."/../lib/classes/util_objects/util.php";
require_once dirname(__FILE__)."/../lib/classes/util_objects/class.Email.php";
require_once dirname(__FILE__)."/../lib/classes/util_objects/paging.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/WebSites.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/WebSiteServer.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Zones.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Groups.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Users.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Vendors.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Packges.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Tasks.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Products.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Orders.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/SystemSetting.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/PromotionalCode.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Checklist.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/MerchantAccount.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Company.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Clients.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/OrderForm.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/Tags.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/OrdersInvoiceHistory.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/SubDomains.php";

$MemberID = $_SESSION['Member']['ID'];
if(empty($_SESSION['Member']) && $login_page != 1 && $signup_page != 1 ){	
	# try to get it from cookie
	$member_email = $_COOKIE["MemberLogedIn"];
	if(!empty($member_email)){
		$objusers = new Users();
		$MemberArray = $objusers->GetAllUsers("Email = '".$member_email."' AND ".USERS.".HasDeleted = 0",array(USERS.".*"));
		$_SESSION['Member'] = $MemberArray[0];						
	}
	  
	if(empty($MemberID)){
		if($_SERVER['SERVER_NAME'] == "localhost"){
			$url = "http://".$_SERVER['SERVER_NAME'].":8080/newxurlios/";
		}else{
			$url = SITE_ADDRESS;
		}
		@header("Location:".$url."Login.php");
		exit();
	}
}
	
	if(!empty($_SESSION['Member']['ID'])){
		if($_SESSION['Member']['ID']=="159"){
			if($_SERVER['SCRIPT_NAME'] =="/Home.php"){
			@header("location:http://xurlios.com/kales-report.php"); 
			}
		}
		
		$ObjGroup = new Groups();
		$Group_Array = $ObjGroup->GetMemberGroups(" UserID = ".$MemberID." ",array('GroupID'));
		foreach((array)$Group_Array as $group_row){
			$groups[] = $group_row['GroupID'];
		}
		$_SESSION['mblgn'] = $_REQUEST['loginhid'];
		
		$_SESSION['user_in_groups'] = $groups;
		
		if(in_array(16,(array)$_SESSION['user_in_groups']) || in_array(23,(array)$_SESSION['user_in_groups'])){
			$_SESSION['isSaleGroup'] = 1;
		}
		if(in_array(2,(array)$_SESSION['user_in_groups'])){
			$_SESSION['isAdmin'] = 1;
		}
		if(in_array(3,(array)$_SESSION['user_in_groups'])){
			$_SESSION['isAdmin'] = 1;
		}
		if(in_array(17,(array)$_SESSION['user_in_groups'])){
			$_SESSION['isGYBFulfillment'] = 1;
		}
		if(in_array(18,(array)$_SESSION['user_in_groups'])){
			$_SESSION['isWebFulfillment'] = 1;
		}
		
		if(in_array(16,(array)$_SESSION['user_in_groups']) and $_SESSION['Member']['CompanyID']=='5'){
			if($_SERVER['SCRIPT_NAME'] =="/Home.php"){
				@header("location:http://xurlios.com/Loyal9.php?refresh"); 
			}
		}
	}


$superDuper = false;
if($_SESSION['Member']['ID']=="6" || $_SESSION['Member']['ID']=="53"){
	$superDuper = true;
}
$superLame = false;
if($_SESSION['Member']['readOnly']=="1"){
	$superLame = true;
}

$FileNameArray = explode("/",$_SERVER['SCRIPT_NAME']);
$FileName = $FileNameArray[count($FileNameArray)-1];
if(file_exists(dirname(__FILE__)."/../lib/handlers/".$FileName)){
	include dirname(__FILE__)."/../lib/handlers/".$FileName;
}

$objusers = new Users();
# Lets fetch the details of the User so that we can set the time zone for the user.
$user = $objusers->GetAllUsers(" Users.ID = '".$MemberID."' ",array("ZoneTime"));
# If the Time zone is saved empty for the Users then I have set "America/Denver"
# as a default since the agents mostly work from this time zone.

# $MemberID 7 means Yunus Aslam -> Developer of Xerly OS
# I just want my time zone to be set to Asia/Calcutta
# so that I can track few things.
$TimeZone = (!empty($user[0]['ZoneTime'])) ? ($MemberID == 7) ? "Asia/Calcutta" : $user[0]['ZoneTime'] : "America/Denver";
# Set the Time Zone of the logged in user so that the queries which are fired based
# on the date and time most importantly search filters.
date_default_timezone_set($TimeZone);
?>
