<?php
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

if($_REQUEST['Task']=='add'){
$domain_from=(double)$_REQUEST['domain_from'];
$domain_to=(double)$_REQUEST['domain_to']+1;
$userpassword = $_REQUEST['userpassword'];
for($i=$domain_from;$i<$domain_to;$i++){
    if(empty($userpassword)){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $userpassword = substr(str_shuffle($chars),0,8);
    }
    $domain_from=(double)$_REQUEST['domain_from'];
    $domain_to=(double)$_REQUEST['domain_to'];
    $dbpassword = $_REQUEST['dbpassword'];
    //echo "comes here";
    //echo "$i = $domain_from; $i < $domain_to ";
    if(empty($dbpassword)){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $dbpassword = substr(str_shuffle($chars),0,8);
     }
   
    $info_arrayTotal = $objWebSite->GetAllWebsites( "UserName = '".$_REQUEST['domain'].$i."'" ,array("count(*) as Total"));
    $total_records   =  $info_arrayTotal[0]['Total'];
    //echo "total found ".$total_records;
    if(!empty($total_records)){
        $UserName=$_REQUEST['domain'].$i."a";
        $info_arrayTotal = $objWebSite->GetAllWebsites( "UserName = '".$_REQUEST['domain'].$i."a"."'" ,array("count(*) as Total"));
        $total_records   =  $info_arrayTotal[0]['Total'];
        if(!empty($total_records)){
            $UserName=$_REQUEST['domain'].$i."b";
            $info_arrayTotal = $objWebSite->GetAllWebsites( "UserName = '".$_REQUEST['domain'].$i."b"."'" ,array("count(*) as Total"));
            $total_records   =  $info_arrayTotal[0]['Total'];
            if(!empty($total_records)){
                $UserName=$_REQUEST['domain'].$i."c"; 
                $info_arrayTotal = $objWebSite->GetAllWebsites( "UserName = '".$_REQUEST['domain'].$i."c"."'" ,array("count(*) as Total"));
                $total_records   =  $info_arrayTotal[0]['Total'];
                if(!empty($total_records)){
                     $UserName=$_REQUEST['domain'].$i."d"; 
                }
            }
        }
    }else{
        $UserName=$_REQUEST['domain'].$i;
    }
    
    //echo "comes here".$_REQUEST['domain'].$i.".info <br>";
    $objWebSite->InsertWebSite(array(
                                "Created"=>date("Y-m-d h:i:s"),
                                "SaleDate"=>date("Y-m-d h:i:s"),
                                "LastEdited"=>date("Y-m-d h:i:s"),
                                "DomainName"=>$_REQUEST['domain'].$i.".info",
                                "UserName"=>$UserName,
                                "UserPassword"=>$userpassword,
                                "Version"=>$_REQUEST['Version'],
                                "HostedOnID"=>$_REQUEST['HostedOn'],
                                "Ready"=>$_REQUEST['ready'],
                                "Status"=>3,
                                "Type"=>$_REQUEST['Type'],
                                "DbPassword"=>$dbpassword,
                                "SiteAsignDate"=>date("Y-m-d h:i:s"),
                            ));

                         $userpassword="";
					
}
	//exit;
	
header("Location:WebSites.php?flag=add_hubflex_member");
exit;
}
if($_REQUEST['Task']=='CreateTempDomains'){
    //print_r($_REQUEST['SelectedDomains']);
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    foreach((array)$_REQUEST['SelectedDomains'] as $domain_row){
        $domain_data_array = explode("_", $domain_row);
        $userpassword = substr(str_shuffle($chars),0,8);
        $dbpassword = substr(str_shuffle($chars),0,8);
         
        $objWebSite->InsertWebSite(array(
                                "Created"=>date("Y-m-d h:i:s"),
                                "SaleDate"=>date("Y-m-d h:i:s"),
                                "LastEdited"=>date("Y-m-d h:i:s"),
                                "DomainName"=>$domain_data_array[1],
                                "UserName"=>$domain_data_array[0],
                                "UserPassword"=>$userpassword,
                               // "Version"=>$_REQUEST['Version'],
                                //"HostedOnID"=>$_REQUEST['HostedOn'],
                                //"Ready"=>$_REQUEST['ready'],
                                "Status"=>3,
                                //"Type"=>$_REQUEST['Type'],
                                "DbPassword"=>$dbpassword,
                                //"SiteAsignDate"=>date("Y-m-d h:i:s"),
                            ));
        
    }
    header("Location:WebSites.php?flag=add_hubflex_member");
    exit;
}
if($_REQUEST['Task']=='CreateCpanel' ){
    require(dirname(__FILE__)."/../../lib/classes/business_objects/xmlapi.php");
    $objWebSiteServer = new WebSiteServer();
    $WebsiteResellerData = $objWebSiteServer->GetAllWebSiteServer(" isreseller = 1 AND isDefaultReseller = 1 AND HasDeleted = 0 ",array("*"));
    $pid = $_REQUEST['id'];
    $HubopususerRows = $objWebSite->GetAllWebsites(WEBSITES.".ID = $pid LIMIT 0,1 ",array(WEBSITES.".*"));
    $ip         = $WebsiteResellerData[0]['internal_ip']; //"192.168.0.5"; //EZBHOST2
    
    $reseller 	= $WebsiteResellerData[0]['username']; //"ezb2554";
    $reseller_pass 	= $WebsiteResellerData[0]['password']; //"09*&poLKmn";
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
    $xmlapi = new xmlapi($ip);
    $xmlapi->password_auth($reseller,$reseller_pass);
    $acct = array( "username" => $username , "password" => $password , "domain" => $domain,"contactemail"=>$contactemail,"plan"=>"HubFlx");
    $xml = $xmlapi->createacct($acct);
    //echo "<pre>";
    //print_r($xml);
    //echo "</pre>";
    //if($xml->data->result == 0){
        // access denied
       // header("Location:WebSites.php?flag=access_denied");
       // exit;
    //}
    $WebsiteRootData = $objWebSiteServer->GetAllWebSiteServer(" isreseller = 0 AND isDefaultReseller = 1 AND HasDeleted = 0 ",array("*"));
    $xmlapi->password_auth($WebsiteRootData[0]['username'],$WebsiteRootData[0]['password']);
    //$xmlapi->password_auth("root","BX7Wr{2)5ZJc");
    $xmlapi->api1_query($username, "Mysql", "adddb", array($username.'_ss'));
    $xmlapi->api1_query($username, "Mysql", "adduser", array($username.'_admin',$HubopususerRows[0]['DbPassword']));
    $xmlapi->api1_query($username, "Mysql", "adduserdb", array(
                                                            $username.'_ss',
                                                            $username.'_admin',
                                                            'all'));
         
    $updated= $objWebSite->UpdateWebsite("ID = '$pid' ",array(
                                                                        "Status"=>1,
                                                                        "CpanelCreatedDate"=>date("Y-m-d h:i:s"),
                                                                        "HostedOnID"=>$WebsiteRootData[0]['id'],
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
        sleep(100);
        ob_end_flush();
	//exit;
        $url="http://".$domain."/create_db.php?Task=ConfigureSystem&UserName=". $username."&DbPasword=".$HubopususerRows[0]['DbPassword'];
        //header("Location:".$url);
        ?>
        <script>
        window.location.href = "<?php echo $url; ?>";
        </script>
        
        <?php
        exit;
}

if($_REQUEST['Task']=='Update' ){
$pid = $_REQUEST['id'];

$Members_array = $objClient->GetAllClients(CLIENTS.".ID = '".$_REQUEST['']."'",array("Email,FirstName,Surname"));

if(($_REQUEST[''] > 0  || (!empty($_REQUEST['FName']) && !empty($_REQUEST['Email'])) ) ) $_REQUEST['Status']=4;
$ActualDomain=substr($_REQUEST['domain'], 0, 3);

if(strcmp($ActualDomain,"ezb")!==0&&$_REQUEST[''] > 0) $_REQUEST['Status']=5;
if($_REQUEST[''] > 0  ){
	$_REQUEST['FName'] = $Members_array[0]['FirstName']." ".$Members_array[0]['Surname'];
	$_REQUEST['Email'] = $Members_array[0]['Email'];
}

if($_REQUEST['']== 0){
    
    $SiteAsignDate = "";
}elseif(!empty($_REQUEST[''])){
    $SiteAsignDate = $_REQUEST['siteasigndate'];
    
}elseif($_REQUEST['']!= 0){
    $SiteAsignDate = date("Y-m-d h:i:s");
}
 if(!empty($_REQUEST['WelCallComDate'])){
            $WelCallComDate = date("Y-m-d",strtotime($_REQUEST['WelCallComDate']));
			$WelCallComID=$_REQUEST['WelCallComID'];
        }else{
            $WelCallComDate=""; 
			$WelCallComID=$_SESSION['Member']['ID'];
        }
		
if(!empty($_REQUEST['DesignComplete'])){
        $DesignComplete = date("Y-m-d",strtotime($_REQUEST['DesignComplete']));
        }else{
            $DesignComplete=""; 
        }
if(!empty($_REQUEST['ContentComplete'])){
        $ContentComplete = date("Y-m-d",strtotime($_REQUEST['ContentComplete']));
        }else{
            $ContentComplete=""; 
        }
		
 if(!empty($_REQUEST['SiteAcceptanceFinal'])){
        $SiteAcceptanceFinal = date("Y-m-d",strtotime($_REQUEST['SiteAcceptanceFinal']));
        }else{
            $SiteAcceptanceFinal=""; 
        }
if(!empty($_REQUEST['BestCallTime'])){
        $BestCallTime = date("Y-m-d",strtotime($_REQUEST['BestCallTime']));
        }else{
            $BestCallTime=""; 
        }
 if(!empty($_REQUEST['designcompletedby'])){
        $designcompletedby = $_REQUEST['designcompletedby'];
        }else{
            $designcompletedby =$_SESSION['Member']['ID']; 
        }
 if(!empty($_REQUEST['contentcompletedby'])){
        $contentcompletedby = $_REQUEST['contentcompletedby'];
        }else{
            $contentcompletedby =$_SESSION['Member']['ID']; 
        }
 if(!empty($_REQUEST['siteacceptedby'])){
        $SiteAcceptedBy = $_REQUEST['siteacceptedby'];
        }else{
         $SiteAcceptedBy =$_SESSION['Member']['ID']; 
        }	
			

$updated= $objWebSite->UpdateWebsite("ID = '$pid' ",
                                        array(
                                            "SaleDate"        =>date("Y-m-d h:i:s",strtotime($_REQUEST['sentdate'])),
                                            "LastEdited"      =>date("Y-m-d h:i:s"),
                                            "SiteAsignDate"   =>$SiteAsignDate,
                                            "DomainName"      =>$_REQUEST['domain'],
                                            "UserName"	      =>$_REQUEST['username'],
                                            "UserPassword"    =>$_REQUEST['userpassword'],
                                            "Version"	      =>$_REQUEST['Version'],
                                            //"BuiltBy"	      =>$_REQUEST['builtby'],
                                            "Ready"	      =>$_REQUEST['ready'],
                                            "Status"	      =>$_REQUEST['Status'],
                                            "Type"	      =>$_REQUEST['Type'],
                                            "EmailSite"       =>$_REQUEST['EmailSite'],
                                            "MemberID"        =>$_REQUEST['SelectedMember'],
                                            "Notes"           =>$_REQUEST['Notes'],
                                            "HostedOnID"      =>$_REQUEST['HostedOn'],
                                            "isRecycleAble"      	=>$_REQUEST['isRecycleAble'],
                                            //"WelCallComDate"   	  	=>$WelCallComDate,
                                            //"DesignComplete"    	=>$DesignComplete,
                                            //"ContentComplete"       	=>$ContentComplete,
                                            "SiteAcceptanceFinal"	=>$SiteAcceptanceFinal,
                                            "BestCallTime"	     	=>$BestCallTime,
                                            //"GoogleAccountInformation"  =>$_REQUEST['GoogleAccountInformation'],
                                            //"BusinessSocialMediaLinks"  =>$_REQUEST['BusinessSocialMediaLinks'],
                                           // "BusinessReviews"           =>$_REQUEST['BusinessReviews'],
                                            //"KeywordsRelevantBusiness"  =>$_REQUEST['KeywordsRelevantBusiness'],
                                           // "BusinessServesInCities"    =>$_REQUEST['BusinessServesInCities'],
                                          // "T0pBusinessSpecializes"    =>$_REQUEST['T0pBusinessSpecializes'],
                                           // "WhoYouAreAsBusiness"      	=>$_REQUEST['WhoYouAreAsBusiness'],
                                           // "BusinessTagLine"          	=>$_REQUEST['BusinessTagLine'],
                                            //"TypeOfBusiness"          	=>$_REQUEST['TypeOfBusiness'],
                                          	// "WebsiteIncludeCallAction"  =>$_REQUEST['WebsiteIncludeCallAction'],
                                           // "BrandingColors"	       	=>$_REQUEST['BrandingColors'],
                                            //"BusinessImages"	       	=>$_REQUEST['BusinessImages'],
                                            //"BusinessLogo"	        =>$_REQUEST['BusinessLogo'],
                                           //"NOSiteNewDomain"           =>$_REQUEST['NOSiteNewDomain'],
                                           // "ExistingSiteControls"	=>$_REQUEST['ExistingSiteControls'],
                                           // "ExistingWebsite"	        =>$_REQUEST['ExistingWebsite'],
                                           // "BusinessStartedYear"	=>$_REQUEST['BusinessStartedYear'],
                                          //  "BusinessHoursOperation"	=>$_REQUEST['BusinessHoursOperation'],
                                          //  "AddressDisplayWebsite"     =>$_REQUEST['AddressDisplayWebsite'],
                                           // "GoogleAnalyticsSetup"	=>$_REQUEST['GoogleAnalyticsSetup'],
                                           //"LocalOptimizationPackage"	=>$_REQUEST['LocalOptimizationPackage'],
                                            //"BlogSetup"		  	=>$_REQUEST['BlogSetup'],
                                           //"LeadCapturePluginPackage"	=>$_REQUEST['LeadCapturePluginPackage'],
                                            //"SalesFunnelDevelopmentPackage" =>$_REQUEST['SalesFunnelDevelopmentPackage'],
                                            //"MicrositeAddOn"	 	=>$_REQUEST['MicrositeAddOn'],
                                           //"LogoDesign"	  	=>$_REQUEST['LogoDesign'],
                                            //"TransferDomain"	 	=>$_REQUEST['TransferDomain'],
                                            //"UserUpdates"	 				=>$_REQUEST['UserUpdates'],
                                            //"DesignCompletedBy"        	=>$designcompletedby,
                                            //"ContentCompletedBy"        =>$contentcompletedby,
                                            //"SiteAcceptedBy"            =>$SiteAcceptedBy,
                                            "DbPassword"                =>$dbpassword
											
                                                          ));
    //header("Location:"WebSitesEdit.php?id=".$_REQUEST['id']."&flag=add");	
	header("Location:WebSitesEdit.php?id=".$_REQUEST['id']."&flag=update");
													 
}
  
   	
 
if($_REQUEST['Task']=='del'){

    $data_array=$objWebSite->GetAllWebsites(WEBSITES.".ID = '".$_REQUEST['id']."'",array("UserName"));
    $objWebSite->UpdateWebsite("ID = '".$_REQUEST['id']."'",array("HasDeleted"=>"1","UserName"=>$data_array[0]['UserName']."_".time()));
}

if($_REQUEST['Task']=='AjaxSaveNauman'){

$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
//print_r("javed");
$Members_array = $objClient->GetAllClients(CLIENTS.".ID = '".$_REQUEST['id']."'",array("*"));
$subject = "EzbManger - New Revision for ".$Members_array[0]['CompanyName']." - ".$Members_array[0]['FirstName']." ".$Members_array[0]['Surname'];
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=utf8\r\n";
    // More headers
$bcc = 'mhmmd.nauman@gmail.com';
$cc = '';
if($cc!=""){$headers .= 'CC: '. $cc . "\r\n";}
if($bcc!=""){$headers .= 'Bcc: '. $bcc . "\r\n";} 

$From=$SystemSettingsArray[0]['Email'];
$headers .= 'From: EzbManager<'.$From.'>';
$body =$_REQUEST['RevisionNote']."  <br>"."<br>Sincerely,<br>EZB Manager.<br><a href='http://mos2581.info'>http://mos2581.info</a>";
$to = $SystemSettingsArray[0]['EmailTo'];

 
 $added= $objClient->InsertRevision(array(
					    "CurrentDate"  =>date("Y-m-d h:i:s",time()),
                                            "RevisionNote" =>$_REQUEST['RevisionNote'],
					    "MemberID" =>$_REQUEST['id'],
					    "ByID"    =>$_SESSION['Member']['ID'],
					    "Done"    =>0,
											
                                          ));

if(mail($to, $subject, $body, $headers, $From)){
    //echo "email sent";
}else{
    //echo "email failed";
}

exit;
}

if($_REQUEST['Task']=='UpdateBusiness'){

$objWebSite->UpdateWebsite("ID = '".$_REQUEST['id']."'",array(
								"CompanyName"	       =>$_REQUEST['cname'],
											
							));
											
exit;
}

if($_REQUEST['Task']=='displayPhone'){
    
$objWebSite->UpdateWebsite("ID = '".$_REQUEST['id']."'",array(
								"Phone"	       =>$_REQUEST['phone'],
											
							));
											
exit;
}

if($_REQUEST['Task']=='displayAlternatePhone'){

echo $_REQUEST['AlternatePhone'];    
$objWebSite->UpdateWebsite("ID = '".$_REQUEST['id']."'",array(
								"AlternatePhone"	               =>$_REQUEST['AlternatePhone'],
											
							));
											
exit;
}

if($_REQUEST['Task']=='displayAddress'){

  
$objWebSite->UpdateWebsite("ID = '".$_REQUEST['id']."'",array(
								"Address"	               =>$_REQUEST['address'],
											
							));
											
exit;
}

if($_REQUEST['Task']=='displayAddress2'){

   
$objWebSite->UpdateWebsite("ID = '".$_REQUEST['id']."'",array(
								"Address2"	               =>$_REQUEST['Address2'],
											
							));
											
exit;
}

?>