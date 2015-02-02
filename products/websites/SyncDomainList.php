<?php 
include "../../lib/include.php";
require(dirname(__FILE__)."/../../lib/classes/business_objects/xmlapi.php");
$objWebSiteServer = new WebSiteServer();
$objWebsites = new WebSites();
$WebServerData = $objWebSiteServer->GetAllWebSiteServer( "isreseller = 0 AND HasDeleted = 0" ,array("*"));

foreach($WebServerData as $WebServerRow){
    $ip         = $WebServerRow['internal_ip'];
    $root       = $WebServerRow['username'];
    $root_pass  = $WebServerRow['password'];
    $xmlapi = new xmlapi($ip);
    $xmlapi->password_auth($root,$root_pass);
    $xml = $xmlapi->listaccts(".info");
    
    foreach($xml->acct as $acct){
        $domain = substr($acct->domain, -5);
        if($domain != ".info"){
            $user = substr($acct->user,0, 7);
            //echo "UserName LIKE '$acct->user%' - $acct->domain <br>";
            $WebsiteExist = $objWebsites->GetAllWebsites(" UserName = '$user' ",array(WEBSITES.".ID"));
            $objWebsites->UpdateWebsite("UserName = '$acct->user' AND HostedOnID = '".$WebServerRow['id']."'", 
                    array("DomainName"=>$acct->domain,
                          "Status"=>5
                        )
                    );
        }
    }
    
    //echo " $ip $root $root_pass <br>";
    
}

/*
$ip         = "192.168.0.5"; // hostgator//EZBHOST2
    
$reseller 	= "ezb2554";
$reseller_pass 	= "Auy3!2qN";
$root="root";
$root_pass="BX7Wr{2)5ZJc";
$xmlapi = new xmlapi($ip);
$objWebsites = new WebSites();
$xmlapi->password_auth($root,$root_pass);
//$xmlapi->password_auth($reseller,$reseller_pass);
$xml = $xmlapi->listaccts(".info");


foreach($xml->acct as $acct){
    $domain = substr($acct->domain, -5);
    if($domain != ".info"){
        $user = substr($acct->user,0, 7);
        //echo "UserName LIKE '$acct->user%' - $acct->domain <br>";
        $WebsiteExist = $objWebsites->GetAllWebsites(" UserName = '$user' ",array(WEBSITES.".ID"));
        $objWebsites->UpdateWebsite("UserName = '$acct->user' AND HostedOn = 'ezbhost3'", 
                array("DomainName"=>$acct->domain,
                      "Status"=>5
                    )
                );
    }
}

*/
?>