<?php
$objHubopususer = new HubFlxMember();
//$asigndate= $_REQUEST['siteasigndate'];
//if(empty($_SESSION['FromDate']))$_SESSION['FromDate']=date("m/d/Y",strtotime(" -1 year"));
//if(empty($_SESSION['ToDate']))$_SESSION['ToDate']=date("m/d/Y");
if(empty($_SESSION['SiteStatus']))$_SESSION['SiteStatus']="all";

if(isset($_REQUEST['ToDate']))$_SESSION['ToDate']=$_REQUEST['ToDate'];
if(isset($_REQUEST['FromDate']))$_SESSION['FromDate']=$_REQUEST['FromDate'];
if(isset($_REQUEST['SiteStatus']))$_SESSION['SiteStatus']=$_REQUEST['SiteStatus'];
if(isset($_REQUEST['SiteStatus1']))$_SESSION['SiteStatus1']=$_REQUEST['SiteStatus1'];
if(isset($_REQUEST['sortBy']))$_SESSION['sortBy']=$_REQUEST['sortBy'];
if(isset($_REQUEST['sortValue']))$_SESSION['sortValue']=$_REQUEST['sortValue'];
extract($_REQUEST);
$objmember = new Member();

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
   
    $info_arrayTotal = $objHubopususer->GetAllHubFlxMember( "UserName = '".$_REQUEST['domain'].$i."'" ,array("count(*) as Total"));
    $total_records   =  $info_arrayTotal[0]['Total'];
    //echo "total found ".$total_records;
    if(!empty($total_records)){
        $UserName=$_REQUEST['domain'].$i."a";
        $info_arrayTotal = $objHubopususer->GetAllHubFlxMember( "UserName = '".$_REQUEST['domain'].$i."a"."'" ,array("count(*) as Total"));
        $total_records   =  $info_arrayTotal[0]['Total'];
        if(!empty($total_records)){
            $UserName=$_REQUEST['domain'].$i."b";
            $info_arrayTotal = $objHubopususer->GetAllHubFlxMember( "UserName = '".$_REQUEST['domain'].$i."b"."'" ,array("count(*) as Total"));
            $total_records   =  $info_arrayTotal[0]['Total'];
            if(!empty($total_records)){
                $UserName=$_REQUEST['domain'].$i."c"; 
                $info_arrayTotal = $objHubopususer->GetAllHubFlxMember( "UserName = '".$_REQUEST['domain'].$i."c"."'" ,array("count(*) as Total"));
                $total_records   =  $info_arrayTotal[0]['Total'];
                if(!empty($total_records)){
                     $UserName=$_REQUEST['domain'].$i."d"; 
                }
            }
        }
    }else{
        $UserName=$_REQUEST['domain'].$i;
    }
    
    
    $objHubopususer->InsertHubFlxMember(array(
					   "Created"=>date("Y-m-d h:i:s"),
                        "SaleDate"=>date("Y-m-d h:i:s"),
					   "LastEdited"=>date("Y-m-d h:i:s"),
					   "DomainName"=>$_REQUEST['domain'].$i.".info",
					   "UserName"=>$UserName,
					   "UserPassword"=>$userpassword,
					   "Version"=>$_REQUEST['Version'],
                       "HostedOn"=>$_REQUEST['HostedOn'],
					   "Ready"=>$_REQUEST['ready'],
					   "Status"=>3,
					   "Type"=>$_REQUEST['Type'],
					   "DbPassword"=>$dbpassword,
					   "SiteAsignDate"=>date("Y-m-d h:i:s"),
        
																						
					));

$userpassword="";
					
}
	//exit;
	header("Location:HubFlxMembers.php?flag=add_hubflex_member");

}
if($_REQUEST['Task']=='CreateCpanel' ){

        require("lib/classes/business_objects/xmlapi.php");
        
         
        //exit;
	$pid = $_REQUEST['id'];

	$HubopususerRows = $objHubopususer->GetAllHubFlxMember(" HubFlxMember.ID = $pid LIMIT 0,1 ",array("HubFlxMember.*"));
        if($HubopususerRows[0]['HostedOn'] == 'EZBHOST2'){
            $ip  = ""; // hostgator//EZBHOST2
            $root = "root";
            $root_pass = "rootpass";
        }else if($HubopususerRows[0]['HostedOn'] == 'rackspace'){
            $ip  = ""; // rackspace
        }else{
            $ip = ""; // codero//EZBHOST1
            $root = "root";
            $root_pass = "";
	}
		
        $reseller 	= "";

	$reseller_pass 	= "";

	$domain 	= $HubopususerRows[0]['DomainName'];

	$username 	= $HubopususerRows[0]['UserName'];

	$password 	= $HubopususerRows[0]['UserPassword'];

	if(empty($HubopususerRows[0]['CustomerEmail'])){

		$contactemail	= 'mhmmd.nauman@gmail.com';

	}else{

		$contactemail	= $HubopususerRows[0]['CustomerEmail'];

	}
        
	$xmlapi = new xmlapi($ip);

	$xmlapi->password_auth($reseller,$reseller_pass);

	$acct = array( username => $username , password => $password , domain => $domain,contactemail=>$contactemail,plan=>"HubFlx");

	

	$xml = $xmlapi->createacct($acct);

	

	$xmlapi->password_auth($root,$root_pass);
        
 	$xmlapi->api1_query($username, "Mysql", "adddb", array($username.'_ss'));
        
	$xmlapi->api1_query($username, "Mysql", "adduser", array($username.'_admin',$HubopususerRows[0]['DbPassword']));

	$xmlapi->api1_query($username, "Mysql", "adduserdb", array(

                    $username.'_ss',

                    $username.'_admin',

                    'all'));
         
	$updated= $objHubopususer->UpdateHubFlxMember("ID = '$pid' ",array(
                                                                        "Status"=>4,
                                                                        "CpanelCreatedDate"=>date("Y-m-d h:i:s")
            
                                                                            ));				



	//now redirect then to import db and configure it
       
        //sleep(180);
	
        $url="http://".$domain."/create_db.php?Task=ConfigureSystem&UserName=". $username."&DbPasword=".$HubopususerRows[0]['DbPassword'];
        header("Location:".$url);
        exit;
}

if($_REQUEST['Task']=='Update' ){

$pid = $_REQUEST['id'];
$objMember = new Member();
 $Members_array = $objMember->GetAllMember("ID = '".$_REQUEST['SelectedMember']."'",array("Email,FirstName,Surname"));
 $HubopususerRows1 = $objHubopususer->GetAllHubFlxMember(" HubFlxMember.ID = '".$_REQUEST['id']."'",array("HubFlxMember.*"));
if(($_REQUEST['SelectedMember'] > 0  || (!empty($_REQUEST['FName']) && !empty($_REQUEST['Email'])) ) ) $_REQUEST['Status']=4;
$ActualDomain=substr($_REQUEST['domain'], 0, 3);

if(strcmp($ActualDomain,"ezb")!==0&&$_REQUEST['SelectedMember'] > 0) $_REQUEST['Status']=5;
if($_REQUEST['SelectedMember'] > 0  ){
	$_REQUEST['FName'] = $Members_array[0]['FirstName']." ".$Members_array[0]['Surname'];
	$_REQUEST['Email'] = $Members_array[0]['Email'];
}

if($_REQUEST['SelectedMember']== 0){
    
    $SiteAsignDate = "";
}elseif(!empty($_REQUEST['SelectedMember'])){
    $SiteAsignDate = $_REQUEST['siteasigndate'];
    
}elseif($_REQUEST['SelectedMember']!= 0){
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

$updated= $objHubopususer->UpdateHubFlxMember("ID = '$pid' ",array(
											"SaleDate"				          =>date("Y-m-d h:i:s",strtotime($_REQUEST['sentdate'])),
											"LastEdited"                      =>date("Y-m-d h:i:s"),
											"SiteAsignDate"			          =>$SiteAsignDate,
											"DomainName"   		              =>$_REQUEST['domain'],
											"UserName"				          =>$_REQUEST['username'],
											"UserPassword" 			          =>$_REQUEST['userpassword'],
											"Version"				          =>$_REQUEST['Version'],
											"BuiltBy"				          =>$_REQUEST['builtby'],
											"Ready"					          =>$_REQUEST['ready'],
											"Status"				          =>$_REQUEST['Status'],
											"Type"							  =>$_REQUEST['Type'],
											"EmailSite"			        	  =>$_REQUEST['EmailSite'],
											"MemberID"					   	  =>$_REQUEST['SelectedMember'],
											"Notes"            			      =>$_REQUEST['Notes'],
                                            "HostedOn"     		   			  =>$_REQUEST['HostedOn'],
											"WelCallComID"      			  =>$WelCallComID,
											"WelCallComDate"   	  		      =>$WelCallComDate,
											"DesignComplete"    			  =>$DesignComplete,
											"ContentComplete"       		  =>$ContentComplete,
											"SiteAcceptanceFinal"			  =>$SiteAcceptanceFinal,
											"BestCallTime"	     			  => $BestCallTime,
											"GoogleAccountInformation"        =>$_REQUEST['GoogleAccountInformation'],
											"BusinessSocialMediaLinks"        =>$_REQUEST['BusinessSocialMediaLinks'],
											"BusinessReviews"             	  =>$_REQUEST['BusinessReviews'],
											"KeywordsRelevantBusiness"        =>$_REQUEST['KeywordsRelevantBusiness'],
											"BusinessServesInCities"     	  =>$_REQUEST['BusinessServesInCities'],
											"T0pBusinessSpecializes"      	  =>$_REQUEST['T0pBusinessSpecializes'],
											"WhoYouAreAsBusiness"      	 	  =>$_REQUEST['WhoYouAreAsBusiness'],
											"BusinessTagLine"          	  	  =>$_REQUEST['BusinessTagLine'],
											"TypeOfBusiness"          		  =>$_REQUEST['TypeOfBusiness'],
											"WebsiteIncludeCallAction"		  =>$_REQUEST['WebsiteIncludeCallAction'],
											"BrandingColors"	       		  =>$_REQUEST['BrandingColors'],
											"BusinessImages"	       		  =>$_REQUEST['BusinessImages'],
											"BusinessLogo"	           		   =>$_REQUEST['BusinessLogo'],
											"NOSiteNewDomain"                 =>$_REQUEST['NOSiteNewDomain'],
											"ExistingSiteControls"	          =>$_REQUEST['ExistingSiteControls'],
											"ExistingWebsite"	       		  =>$_REQUEST['ExistingWebsite'],
											"BusinessStartedYear"	  		  =>date("Y-m-d ",strtotime($_REQUEST['BusinessStartedYear'])),
											"BusinessHoursOperation"	      =>$_REQUEST['BusinessHoursOperation'],
											"AddressDisplayWebsite"		  	  =>$_REQUEST['AddressDisplayWebsite'],
											"GoogleAnalyticsSetup"		 	  =>$_REQUEST['GoogleAnalyticsSetup'],
											"LocalOptimizationPackage"		  =>$_REQUEST['LocalOptimizationPackage'],
											"BlogSetup"		  	  		 	  =>$_REQUEST['BlogSetup'],
											"LeadCapturePluginPackage"		  =>$_REQUEST['LeadCapturePluginPackage'],
											"SalesFunnelDevelopmentPackage"	  =>$_REQUEST['SalesFunnelDevelopmentPackage'],
											"MicrositeAddOn"	 			  =>$_REQUEST['MicrositeAddOn'],
											"LogoDesign"	  				  =>$_REQUEST['LogoDesign'],
											"TransferDomain"	 			  =>$_REQUEST['TransferDomain'],
											"UserUpdates"	 			 	  =>$_REQUEST['UserUpdates'],
											"DesignCompletedBy"        	      =>$designcompletedby,
											"ContentCompletedBy"              =>$contentcompletedby,
											 "SiteAcceptedBy"   			  =>$SiteAcceptedBy,
											
											/*
											"WelCallComDate"    =>date("Y-m-d h:i:s",strtotime($_REQUEST['WelCallComDate'])),
											"SetFormID"     	=>$_REQUEST['SetFormID'],
											"SetFormDate"       =>date("Y-m-d h:i:s",strtotime($_REQUEST['SetFormDate'])),
											"DesignUpdateCall"  =>date("Y-m-d h:i:s",strtotime($_REQUEST['DesignUpdateCall'])),
											"ContentReceived"   =>date("Y-m-d h:i:s",strtotime($_REQUEST['ContentReceived'])),
											
											"ContentComplete"   =>date("Y-m-d h:i:s",strtotime($_REQUEST['ContentComplete'])),
											"DNSRecordSet"      =>date("Y-m-d h:i:s",strtotime($_REQUEST['DNSRecordSet'])),
											"SiteReviewID"      =>$_REQUEST['SiteReviewID'],
											"SiteReviewDate"    =>date("Y-m-d h:i:s",strtotime($_REQUEST['SiteReviewDate'])),
											"RevisionsNeeded"   =>date("Y-m-d h:i:s",strtotime($_REQUEST['RevisionsNeeded'])),
											"RevisionsComplete" =>date("Y-m-d h:i:s",strtotime($_REQUEST['RevisionsComplete'])),
											"RevisionsAcceptedFinal"     =>date("Y-m-d h:i:s",strtotime($_REQUEST['RevisionsAcceptedFinal'])),
											"SetFormID"      =>$_REQUEST['SetFormID'],
											"WelCallComID"      =>$_REQUEST['WelCallComID'],
											"WorkProcessDate"=>date("Y-m-d h:i:s",strtotime($_REQUEST['WorkProcessDate'])),
	*/
                                             "DbPassword"    =>$dbpassword
											
                                                          ));
  
   	
	//echo "eeeeeeeeeeeeeeee";
//exit;
/*
if($_REQUEST['SendEmail'] == 1){
	if($_REQUEST['HostedServer'] == 0){
            $dns_servers="NS1.CODERO.COM<br />
          NS2.CODERO.COM<br />
          <br />";
        }else{
            $dns_servers="ns1.lnhi.net<br />
          ns2.lnhi.net<br />
          ns3.lnhi.net<br />";
        }
	$message='<div>
Dear '.$Members_array[0]['FirstName']." ".$Members_array[0]['Surname'].',<br />
      <br />
  Thank you for joining the hubOpus community. I am confident that in a short time you will<br />
  find that the hubOpus Website System is truly the easiest and most powerful system<br />
  for building and maintaining a marketing specific website that you will ever use.<br />
      <br />
  Your temporary website domain name is:<br />
  http://'.$_REQUEST['domain'].'/admin<br />
      <br />
  Email (username): me<br />
  Temp Password: letmein<br />
      <br />
  In the Basic Training tab, you will find a video on how to change your email and password.<br />
      <br />
  hubOpus Version: '.$_REQUEST['Version'].'<br />
      <br />
  ~~~<br />
      <br />
  Domain Name Research:<br />
  Make sure to do some research on what market you want your keywords<br />
  targeted toward before purchasing a domain name. If you are creating a<br />
  Branding site, you will want to find a domain name with your name or business<br />
  name that you want to promote.<br />
      <br />
      <br />
  Note: If you are a MOS member, follow the training for further instructions and<br />
  understanding before purchasing a domain name.<br />
      <br />
  Note: This does not need to be done right away. This is an important step<br />
  that will take some time to learn what domain name is best for your site content.<br />
      <br />
  You will access the administration area by adding /admin to the end of your new domain.<br />
  http://'.$_REQUEST['domain'].'/admin<br />
      <br />
  You can purchase your domains from<a href="http://godaddy.com/"> Godaddy.com</a> &quot;Highly Recommended!&quot;<br />
      <br />
  Nameservers:<br />
  Once you have your own domain name, you must point the Nameservers to:<br />
  '.$dns_servers.'
  Seek your domain hosting provider for help in setting up your nameservers.<br />
      <br />
      <br />
  Business System Only (Default Provided Website)<br />
  Once you have your Nameservers changed, let us know by opening a ticket at<br />
  <a href="http://mossupport.com/">http://mossupport.com</a><br />
  Takes up to 24 hours for domains to propagate - but typically 5 minutes.<br />
  Please include in your support ticket your temp domain...'.$_REQUEST['domain'].' so we can<br />
  find you quickly.<br />
      <br />
  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br />
      <br />
  Advanced:<br />
  Legion Only (Upgrade)<br />
  To add your main site domain:<br />
  <ul>
    <li>*WARNING* Do not change the domain name on the My Main Site record! You must submit</li>
    <li>a support ticket and have us change this for you or your site will be inaccessible. </li>
    <li>Once your main domain is changed, all new domains must Redirect to it. See below...</li>
  </ul>
  <br />
  To create additional websites within the Legion:<br />
  <ul>
    <li>Click on the Manage Sites page</li>
    <li>Click on Add A Site</li>
    <li>Fill in the details and Save</li>
    <ul>
      <li>You do not need to have a domain to create and work on a site.</li>
      <li>You must refresh your browser before the new domain will be listed in the dropdown</li>
      <li>on the left navigation menu - top left.</li>
    </ul>
    <li>Park the new alias domain in cPanel </li>
    <ul>
      <li>Note: All alias (pointers) domains need to be pointed to the same nameservers</li>
      <li>listed in this email (above) before they can be parked. Allow up to 24 hours to propagate.</li>
      <li>Your cPanel link: https://'.$_REQUEST['domain'].':2083/</li>
      <li>Username: '.$_REQUEST['username'].' (your temp domain username - this will never change - '.$_REQUEST['username'].' is your temp domain)</li>
      <li>Your Password: '.$_REQUEST['userpassword'].' (you can change your password under Preferences.</li>
      <li>If you do, please include it in all support tickets.</li>
    </ul>
    <li>Under Domains Group, click on Parked Domains</li>
    <li>Enter your alias domain and click on the Add Domain button</li>
    <li>Then click on Redirects To on the domain listed and enter your main site domain...</li>
    <li>ie<a href="http:/#"> mymaindomain.com</a></li>
    <li>Make sure to watch the videos on the Advanced Training tab in your Dashboard</li>
    <li>for a video tutorial on setting up your Legion websites.</li>
  </ul>
  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br />
      <br />
  Other:<br />
  Follow the instructions on the Welcome Section / Dashboard once logged in<br />
  to get your website setup and running quickly. Make sure to watch the videos on the<br />
  Basic Training tab in your Dashboard.<br />
      <br />
  If you need any help and support, please visit our Knowledgebase at<br />
  <a href="http://mossupport.com/">http://mossupport.com</a> - Please use the forum as much as possible as it<br />
  helps everyone who may have the same questions.<br />
      <br />
  <span style="color:#FF0000">Please keep this email for future reference.</span><br />
      <br />
  Thank you again,<br />
  Steve Nyhof<br />
  CEO; 220 Design Group, LLC.<br />
  <a href="http://hubopus.com/">http://hubOpus.com</a><br />
  <br />
</div>

';
	$subject = "Getting Started With HubOpus";
    $from = "admin@themillionaireos.com";
                    
	$to = $Members_array[0]['Email'];
	$msg = new Email($to, $from , $subject);
	$msg->TextOnly = false;
    $msg->Content = $message;
    $SendSuccess = $msg->Send();
	$updated= $objHubopususer->UpdateHubOpusUser("ID = '$pid' ",array(
                                           				"SaleDate"=>date("Y-m-d h:i:s"),
														"EmailSite"=>1,
														"Status"=>1,
								
                                                          ));
}*/
    header("Location:HubFlxMembersEdit.php?id=".$_REQUEST['id']."&flag=update");
    exit;
} 
 /*end update hubopus */ 
 
 
 
if($_REQUEST['Task']=='del'){

    $data_array=$objHubopususer->GetAllHubFlxMember("HubFlxMember.ID = '".$_REQUEST['id']."'",array("UserName"));
    $objHubopususer->UpdateHubFlxMember("ID = '".$_REQUEST['id']."'",array("HasDeleted"=>"1","UserName"=>$data_array[0]['UserName']."_".time()));
}

if($_REQUEST['Task']=='AjaxSaveNauman'){

$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
//print_r("javed");
$Members_array = $objmember->GetAllMember(" ID = '".$_REQUEST['id']."'",array("*"));

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

 
 $added= $objmember->InsertRevision(array(
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
    
$objmember->UpdateMember("ID = '".$_REQUEST['id']."'",array(
								"CompanyName"	       =>$_REQUEST['cname'],
											
							));
											
exit;
}

if($_REQUEST['Task']=='displayPhone'){
    
$objmember->UpdateMember("ID = '".$_REQUEST['id']."'",array(
								"Phone"	       =>$_REQUEST['phone'],
											
							));
											
exit;
}

if($_REQUEST['Task']=='displayAlternatePhone'){

echo $_REQUEST['AlternatePhone'];    
$objmember->UpdateMember("ID = '".$_REQUEST['id']."'",array(
								"AlternatePhone"	               =>$_REQUEST['AlternatePhone'],
											
							));
											
exit;
}

if($_REQUEST['Task']=='displayAddress'){

  
$objmember->UpdateMember("ID = '".$_REQUEST['id']."'",array(
								"Address"	               =>$_REQUEST['address'],
											
							));
											
exit;
}

if($_REQUEST['Task']=='displayAddress2'){

   
$objmember->UpdateMember("ID = '".$_REQUEST['id']."'",array(
								"Address2"	               =>$_REQUEST['Address2'],
											
							));
											
exit;
}

?>
