<?php
$objSubDomains = new SubDomains();
$objWebSite = new WebSites();
if(empty($_SESSION['SiteStatus']))$_SESSION['SiteStatus']="all";
if(isset($_REQUEST['ToDate']))$_SESSION['ToDate']=$_REQUEST['ToDate'];
if(isset($_REQUEST['FromDate']))$_SESSION['FromDate']=$_REQUEST['FromDate'];
if(isset($_REQUEST['SiteStatus']))$_SESSION['SiteStatus']=$_REQUEST['SiteStatus'];
if(isset($_REQUEST['SiteStatus1']))$_SESSION['SiteStatus1']=$_REQUEST['SiteStatus1'];
if(isset($_REQUEST['sortBy']))$_SESSION['sortBy']=$_REQUEST['sortBy'];
if(isset($_REQUEST['sortValue']))$_SESSION['sortValue']=$_REQUEST['sortValue'];
extract($_REQUEST);
$objClient = new Clients();

$sortBy = $_SESSION['sortBy'];
$sortValue = $_SESSION['sortValue'];
if(empty($sortBy)) $sortBy = "Date";
if(empty($sortValue))$sortValue = "DESC";
switch($sortBy){
  case "Date":
  	switch($sortValue){
	
	case "ASC":
	
	$sortText = "ORDER BY SaleDate ASC";
	$sortName = "DESC";
	break;
	case "DESC":
	
	$sortText = "ORDER BY Status ASC ,SiteAsignDate DESC, SaleDate DESC";
	$sortName = "ASC";
	break;
	}
  break;
}

//if($_REQUEST['postback']=='1')DateSent SaleDate

if($_REQUEST['Task']=='AddSubdomains'){
$WebsiteResellerData = $objSubDomains->GetAllSubDomains(" 1 ORDER BY `SubDomains`.`ID` DESC LIMIT 0,1 ",array("`SubDomains`.`ID`"));
$domain_from=(double)$WebsiteResellerData[0]['ID']+1;
$domain_to=(double)$WebsiteResellerData[0]['ID']+20;
for($i=$domain_from;$i<$domain_to;$i++){
    //echo " comming herre $domain_from  $domain_to<br>";
    $dbpassword = $_REQUEST['dbpassword'];
    
    if(empty($dbpassword)){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $dbpassword = substr(str_shuffle($chars),0,8);
     }
    //echo "comes here".$_REQUEST['domain'].$i.".info <br>";
    $objSubDomains->InsertSubDomains(array(
                                "Created"=>date("Y-m-d h:i:s"),
                                "SubDomain"=>"sub".$i,
                                "Status"=>3,
                                "DbPassword"=>$dbpassword,
                                
                            ));

                         $userpassword="";
					
}
	//exit;
	
header("Location:SubDomains.php?flag=add_hubflex_member");
exit;
}

if($_REQUEST['Task']=='CreateCpanel' ){
    require(dirname(__FILE__)."/../../lib/classes/business_objects/xmlapi.php");
    $objWebSiteServer = new WebSiteServer();
    $WebServerData = $objWebSiteServer->GetAllWebSiteServer( "id = 2 and isreseller = 0 AND HasDeleted = 0" ,array("*"));
    
    //print_r($WebServerData);
    
    $SubDomain = $_REQUEST['id'];
    $HubopususerRows = $objWebSite->GetAllWebsites(WEBSITES.".ID = 22798 LIMIT 0,1 ",array(WEBSITES.".*"));
    
    $ip         = $WebServerData[0]['internal_ip']; //"192.168.0.5"; //EZBHOST2
    
    $root       = $WebServerData[0]['username'];
    $root_pass  = $WebServerData[0]['password'];
    
    $domain             = $HubopususerRows[0]['DomainName'];
    $username           = $HubopususerRows[0]['UserName'];
    $password           = $HubopususerRows[0]['UserPassword'];
    
    if(empty($HubopususerRows[0]['CustomerEmail'])){
        $contactemail	= 'mhmmd.nauman@gmail.com';
    }else{
        $contactemail	= $HubopususerRows[0]['CustomerEmail'];
    }
    //print_r($WebsiteResellerData);
    //exit;
    //echo " $ip ";
    //exit;
    $xmlapi = new xmlapi($ip);
    $xmlapi->password_auth($root,$root_pass);
    $xmlapi->api1_query($username,'SubDomain','addsubdomain',array($SubDomain,$domain,0,0,'/public_html/'.$SubDomain));
    
    $WebsiteRootData = $objWebSiteServer->GetAllWebSiteServer(" isreseller = 0 AND isDefaultReseller = 1 AND HasDeleted = 0 ",array("*"));
    $xmlapi->password_auth($WebsiteRootData[0]['username'],$WebsiteRootData[0]['password']);
    //$xmlapi->password_auth("root","BX7Wr{2)5ZJc");
    $xmlapi->api1_query($username, "Mysql", "adddb", array($username.'_'.$SubDomain));
    $xmlapi->api1_query($username, "Mysql", "adduser", array($username.'_'.$SubDomain,$_REQUEST['DbPassword']));
    $xmlapi->api1_query($username, "Mysql", "adduserdb", array(
                                                            $username.'_'.$SubDomain,
                                                            $username.'_'.$SubDomain,
                                                            'all'));
         
    $updated= $objSubDomains->UpdateSubDomains("SubDomain = '$SubDomain' ",array(
                                                                        "Status"=>1,
                                                                      //  "CpanelCreatedDate"=>date("Y-m-d h:i:s"),
                                                                      //  "HostedOnID"=>$WebsiteRootData[0]['id'],
                                                                            ));	
     
       ob_start();
        ?>
        <!doctype html>
        <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>Please wait..</title>
          <script src="<?php echo SITE_ADDRESS;?>third-party/pace-bar/pace.js"></script>
          <link href="<?php echo SITE_ADDRESS;?>third-party/pace-bar/themes/pace-theme-barber-shop.css" rel="stylesheet" />
          <link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
        </head>
        <body style="background-color: #fff;">

        <table width="70%" align="center" style="margin-top: 100px; height: 500px;">
            <tr>
                <td align="center">
                    <h1>Please wait.....</hr>
                </td>
            </tr>
        </table>


        </body>
        </html>
        
        <?php
        ob_flush();
        flush();
        sleep(10);
        ob_end_flush();
	//exit;
        $url="http://".$domain."/CopyFiles.php?Task=ConfigureSubDomainSystem&UserName=".$username."&SubDomain=". $SubDomain."&DbPassword=".$_REQUEST['DbPassword'];
        //header("Location:".$url);
        ?>
        <script>
        window.location.href = "<?php echo $url; ?>";
        </script>
        
        <?php
        exit;
}

if($_REQUEST['Task']=='CreateCpanelReal' ){
    
    if(empty($_REQUEST['ToSubDomain'])){
        $url="SubDomains.php?flag=empty_real_domain";
        header("Location:".$url);
        exit;
    }
    
    require(dirname(__FILE__)."/../../lib/classes/business_objects/xmlapi.php");
    $objWebSiteServer = new WebSiteServer();
    $WebServerData = $objWebSiteServer->GetAllWebSiteServer( "id = 2 and isreseller = 0 AND HasDeleted = 0" ,array("*"));
    
    //print_r($WebServerData);
    
    $SubDomain = $_REQUEST['id'];
    $HubopususerRows = $objWebSite->GetAllWebsites(WEBSITES.".ID = 22798 LIMIT 0,1 ",array(WEBSITES.".*"));
    
    $ip         = $WebServerData[0]['internal_ip']; //"192.168.0.5"; //EZBHOST2
    
    $root       = $WebServerData[0]['username'];
    $root_pass  = $WebServerData[0]['password'];
    
    $domain             = $HubopususerRows[0]['DomainName'];
    $username           = $HubopususerRows[0]['UserName'];
    $password           = $HubopususerRows[0]['UserPassword'];
    
    if(empty($HubopususerRows[0]['CustomerEmail'])){
        $contactemail	= 'mhmmd.nauman@gmail.com';
    }else{
        $contactemail	= $HubopususerRows[0]['CustomerEmail'];
    }
    //print_r($WebsiteResellerData);
    //exit;
    //echo " $ip ";
    //exit;
    $xmlapi = new xmlapi($ip);
    $xmlapi->password_auth($root,$root_pass);
    $xmlapi->api1_query($username,'SubDomain','addsubdomain',array($_REQUEST['ToSubDomain'],$domain,0,0,'/public_html/'.$_REQUEST['ToSubDomain']));
    
    //$WebsiteRootData = $objWebSiteServer->GetAllWebSiteServer(" isreseller = 0 AND isDefaultReseller = 1 AND HasDeleted = 0 ",array("*"));
    //$xmlapi->password_auth($WebsiteRootData[0]['username'],$WebsiteRootData[0]['password']);
    
       
    $updated= $objSubDomains->UpdateSubDomains("SubDomain = '$SubDomain' ",array(
                                                                        "IsCPanelRenameRun"=>1,
                                                                      
                                                                            ));	
    
       ob_start();
        ?>
        <!doctype html>
        <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>Please wait..</title>
          <script src="<?php echo SITE_ADDRESS;?>third-party/pace-bar/pace.js"></script>
          <link href="<?php echo SITE_ADDRESS;?>third-party/pace-bar/themes/pace-theme-barber-shop.css" rel="stylesheet" />
          <link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
        </head>
        <body style="background-color: #fff;">

        <table width="70%" align="center" style="margin-top: 100px; height: 500px;">
            <tr>
                <td align="center">
                    <h1>Please wait.....</hr>
                </td>
            </tr>
        </table>


        </body>
        </html>
        
        <?php
        ob_flush();
        flush();
        sleep(10);
        ob_end_flush();
	//exit;
        $url="http://".$domain."/MoveFiles.php?Task=ConfigureSubDomainSystem&ToSubDomain=".$_REQUEST['ToSubDomain']."&FromSubDomain=". $_REQUEST['FromSubDomain'];
        //header("Location:".$url);
        ?>
        <script>
        window.location.href = "<?php echo $url; ?>";
        </script>
        
        <?php
        exit;
}


if($_REQUEST['Task']=='Update' ){
    //echo "come here for update";
    $pid = $_REQUEST['id'];
    $updated= $objSubDomains->UpdateSubDomains("ID = '$pid' ",
                                            array(
                                                "Created"        =>date("Y-m-d h:i:s",strtotime($_REQUEST['sentdate'])),
                                                "Status"         =>$_REQUEST['Status'],
                                                "ClientID"       =>$_REQUEST['SelectedMember'],
                                                "SubDomain"      =>$_REQUEST['domain'],
                                                "Notes"          =>$_REQUEST['Notes'],
                                                "DbPassword"     =>$_REQUEST['dbpassword'],
                                                "UserName"       =>$_REQUEST['username'],
                                                "VanityDomain"       =>$_REQUEST['vanitydomain'],
                                                ));
    	
	header("Location:SubdomainEdit.php?id=".$_REQUEST['id']."&flag=update");
													 
}
  
   	
 
if($_REQUEST['Task']=='del'){

    $data_array=$objWebSite->GetAllWebsites(WEBSITES.".ID = '".$_REQUEST['id']."'",array("UserName"));
    $objWebSite->UpdateWebsite("ID = '".$_REQUEST['id']."'",array("HasDeleted"=>"1","UserName"=>$data_array[0]['UserName']."_".time()));
}
?>