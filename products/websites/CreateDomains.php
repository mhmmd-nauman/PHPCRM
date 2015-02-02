<?php 
include "../../lib/include.php";
require(dirname(__FILE__)."/../../lib/classes/business_objects/xmlapi.php");

//$xmlapi = new xmlapi('127.0.0.1'); 
//$xmlapi->password_auth('root','password'); 
//$xmlapi->set_debug(1); 
//print $xmlapi->api1_query('accountname','SubDomain','addsubdomain',array('sub','domain.com',0,0,'/public_html/folder'));

$objWebSiteServer = new WebSiteServer();
$WebServerData = $objWebSiteServer->GetAllWebSiteServer( "id = 2 and isreseller = 0 AND HasDeleted = 0" ,array("*"));
$WebResellerServerData = $objWebSiteServer->GetAllWebSiteServer( "isreseller = 1 AND HasDeleted = 0" ,array("*"));

$ip             = $WebServerData[0]['internal_ip']; 
    
$reseller 	= $WebResellerServerData[0]['username'];
$reseller_pass 	= $WebResellerServerData[0]['password'];
$root       = $WebServerData[0]['username'];
$root_pass  = $WebServerData[0]['password'];
$xmlapi = new xmlapi($ip);
$objWebsites = new WebSites();
$xmlapi->password_auth($root,$root_pass);
//$xmlapi->password_auth($reseller,$reseller_pass);

$xmlapi->set_debug(1); 
//print $xmlapi->api1_query('ezb2650a','SubDomain','addsubdomain',array('sub1','ezb2650.info',0,0,'/public_html/sub1'));

print $xmlapi->api1_query('ezb2599b','SubDomain','delsubdomain',array('domain'=>'nauman.smallbusiness.info'));

//smallbusiness.info/index.php
