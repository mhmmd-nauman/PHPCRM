<?php
date_default_timezone_set(@date_default_timezone_get());
@session_start();
require_once dirname(__FILE__)."/../dbcon.php";
require_once dirname(__FILE__)."/../lib/classes/config/variables.php";
require_once dirname(__FILE__)."/../lib/classes/util_objects/util.php";
require_once dirname(__FILE__)."/../lib/classes/util_objects/class.Email.php";
require_once dirname(__FILE__)."/../lib/classes/util_objects/paging.php";
require_once dirname(__FILE__)."/../lib/classes/business_objects/WebSites.php";
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

$FileNameArray = explode("/",$_SERVER['SCRIPT_NAME']);
$FileName = $FileNameArray[count($FileNameArray)-1];
if(file_exists(dirname(__FILE__)."/../lib/handlers/".$FileName)){
	include dirname(__FILE__)."/../lib/handlers/".$FileName;
}
?>

