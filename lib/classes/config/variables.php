<?php
//Define variables
define("SSL_INSTALLED",1);

if($_SERVER['SERVER_NAME'] == 'localhost'){
	define("SITE_ADDRESS","http://".$_SERVER['SERVER_NAME'].":8080/newxurlios/");
} else{
    if(SSL_INSTALLED == 1 && $_SERVER['SERVER_NAME'] != 'dev.xurlios.com' ){
        $http = "https";
    }else{
        $http = "http";
    }
    define("SITE_ADDRESS",$http."://".$_SERVER['SERVER_NAME']."/");
        
}
define('SALT', 'xerly_os_salt_by_intuchworl');


define("SITE_ICONS_PATH",SITE_ADDRESS."admin/images/");
define("SITE_HEADIMAGES_PATH",SITE_ICONS_PATH."header/");

define("SITE_NAME","BusinessSupportCenter.Com");

//define the database tables name
define("ORDERINVOICEHISTORY","OrderInvoiceHistory");
define("CLIENTSNOTES","ClientsNotes");
define("TAGS","Tag");
define("CLIENTTAGS","ClientTags");
define("ORDERFORM","OrderForm");
define("TIMEZONES","TimeZones");
define("ECTASKPRODUCTDATA","EcTaskProductData");
define("ECMERCHANTACCTRANSACTIONS","MerchantAccTransactions");
define("ECMANAGEMERCHANTACC","ManageMerchantAcc");
define("ECORDERDETAIL","OrderDetail");
define("ECORDERITEM","OrderItem");
define("PRODUCT","Product");
define("PRODUCTSTASKS","ProductsTasks");
define("PACKGESPRODUCTS","PackgesProducts");
define("TASKS","Tasks");
define("ECCHECKLIST","Ecchecklist");
define("TASKTOCHECKLIST","TaskChecklist");
define("PACKGES","Packges");
define("VENDORS","Vendors");
define("USERS","Users");
define("MEMBERS","Member");
define("GROUPS","Group");
define("GROUPUSERS","Group_Users");
define("REVISION","Revision");
define("HUBFLXMEMBERS","HubFlxMember");
define("WEBSITES","Websites");
define("DEBUG","0");
define("RUNTIME_ERROR_FUNCTION","mysql_die");
define("DEBUG_FUNCTION","log_file");
define("COMPANY","Company");
define("ECCITATIONDATA","EcCitationData");
define("CLIENTS","Clients");
define("PAYMENTACCEPTED","PaymentAccepted");
define("WEBSITES","Websites");
define("WEBSITESERVER","WebsiteServer");
define("ECGYBDATA","EcGYBData");
define("CLIENTCCINFORMATION","ClientCCInformation");
define("PRODUCTPRICE","ProductPrice");
define("LOGINDETAIL","LoginDetail");
define("PRODUCTCATEGORY","ProductCategory");
define("USERTIMECARD","TimeCardUser");
define("BUSINESSCATEGORY","BusinessCategory");
define("ZONES","Zones");
#added on 19th march 2014
define("SENDGRID_USERNAME","220mg");
define("SENDGRID_PASSWORD","09*&poLKmn");
define("PRODUCTSUBSCRIPTION","ProductSubscription");
#upto here

?>