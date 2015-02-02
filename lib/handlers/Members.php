<?php
extract($_REQUEST);
$objmember = new Member();
$ObjGroup = new Groups();
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
//print_r($_SESSION);
if(isset($_REQUEST['sortBy']))$_SESSION['sortBy']=$_REQUEST['sortBy'];
if(isset($_REQUEST['sortValue']))$_SESSION['sortValue']=$_REQUEST['sortValue'];
$sortBy = $_SESSION['sortBy'];
$sortValue = $_SESSION['sortValue'];
if(empty($sortBy)) $sortBy = "Date";
if(empty($sortValue))$sortValue = "DESC";
switch($sortBy){
  case "Date":
  	switch($sortValue){
	
	case "ASC":
	
	$sortText = "ORDER BY Created ASC";
	$sortName = "DESC";
	break;
	case "DESC":
	
	$sortText = "ORDER BY Created DESC";
	$sortName = "ASC";
	break;
	}
  break;
}


$cash_method=$_REQUEST['PaymentAccepted'];

	foreach((array)$cash_method as $cash){
		
		}
$checked_value=$_REQUEST['MemberGroup'];
$Done=$_REQUEST['Done'];

foreach((array)$checked_value as $value){
	//print_r( $value);
}
 $group_array = $ObjGroup->GetAllGroups(" ID ='$mid' ORDER BY Sort",array("*"));
if($_REQUEST['Task']=='add'){
    
 
    //exit;
        if($_FILES['ProfileImg']['size'] > 0){
            $profile_image = "user_pics/";
            $profile_image = $profile_image . basename( $_FILES['ProfileImg']['name']);
            move_uploaded_file($_FILES['ProfileImg']['tmp_name'], $profile_image);
        }
        if(empty($_REQUEST['fName']))$_REQUEST['fName']="New Member Name";
        if(empty($_REQUEST['Email']))$_REQUEST['Email']= time()."@email.com";
        if(empty($_REQUEST['password'])){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $_REQUEST['password'] = substr(str_shuffle($chars),0,8);
        }
      // echo $_REQUEST['WelCallComDate'];
        if(!empty($_REQUEST['WelCallComDate'])){
            $WelCallComDate= date("Y-m-d",strtotime($_REQUEST['WelCallComDate']));
			$WelCallComID=$_SESSION['Member']['ID'];
        }else{
            $WelCallComDate="";
			 $WelCallComID="";
            
        }
        //echo $_REQUEST['DesignUpdateCall'];
         if(!empty($_REQUEST['DesignUpdateCall'])){
        $DesignUpdateCall= date("Y-m-d",strtotime($_REQUEST['DesignUpdateCall']));
        }else{
            $DesignUpdateCall="";
            
        }
        
        if(!empty($_REQUEST['ContentReceived'])){
        $ContentReceived = date("Y-m-d",strtotime($_REQUEST['DesignUpdateCall']));
        }else{
            $ContentReceived="";
            
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
         if(!empty($_REQUEST['WorkProcessDate'])){
        $WorkProcessDate = date("Y-m-d",strtotime($_REQUEST['WorkProcessDate']));
        }else{
            $WorkProcessDate=""; 
        }
        if(!empty($_REQUEST['timebilling'])){
        $timebilling = date("Y-m-d",strtotime($_REQUEST['timebilling']));
        }else{
            $timebilling=""; 
        }
      $Members_array = $objmember->GetAllMember("1",array("*"));
      //print_r($Members_array);
      //echo $Members_array[0]['Email'];
      foreach($Members_array as $Member_email){
          //print_r($Member_email);
       if($Member_email['Email'] == $_REQUEST['Email']){
         //  echo "here ";
           //header("location:MembersEdit.php?flag=email_error");
       }
      }
	   if(!empty($_REQUEST['BestCallTime'])){
        $BestCallTime = date("Y-m-d",strtotime($_REQUEST['BestCallTime']));
        }else{
            $BestCallTime=""; 
        }
		
	
		
	$Members_array = $objmember->GetAllMember("Email= '".$_REQUEST['Email']."'",array("ID,Email"));
        if($Members_array[0]['Email']!= $_REQUEST['Email']){
                                          $added= $objmember->InsertMember(array(
                                                                                "Created"	   =>date("Y-m-d h:i:s",time()),
                                                                                "FirstName"    =>$_REQUEST['fName'],
                                                                                "Surname"      =>$_REQUEST['sureName'],
                                                                                "Email"	       =>$_REQUEST['Email'],
                                                                                "Phone"        =>$_REQUEST['phone'],
                                                                                "Password"     =>$_REQUEST['password'],
                                                                                "SkypeName"	   =>$_REQUEST['skypename'],
                                                                                "Address"	   =>$_REQUEST['address'],
                                                                                "Address2"	   =>$_REQUEST['Address2'],
                                                                                "City"	       =>$_REQUEST['city'],
                                                                                "State"	       =>$_REQUEST['state'],
                                                                                "member_profile_image"=>$profile_image,
                                                                                "State"	       =>$_REQUEST['state'],
                                                                                "CompanyName"	       =>$_REQUEST['cname'],
                                                                               "TimeBilling"=> $timebilling,
                                                                                "WelCallComDate"    =>$WelCallComDate,
                                                                                "SubmitedBy"	       =>$_SESSION['Member']['ID'],
                                                                                "DesignCompletedBy"   =>$_SESSION['Member']['ID'],
                                                                                "ContentCompletedBy"   =>$_SESSION['Member']['ID'],
                                                                                "WorkCompletedBy"   =>$_SESSION['Member']['ID'],
                                                                                "SiteAcceptedBy"   =>$_SESSION['Member']['ID'],
                                                                                "WelCallComID"      =>$WelCallComID,
                                                                                "DesignUpdateCall"  =>$DesignUpdateCall,
                                                                                "ContentReceived"   =>$ContentReceived,
                                                                                "DesignComplete"    =>$DesignComplete,
                                                                                "ContentComplete"   =>$ContentComplete,
                                                                                "DNSRecordSet"      =>date("Y-m-d",strtotime($_REQUEST['DNSRecordSet'])),
                                                                                "SiteReviewID"      =>$_REQUEST['SiteReviewID'],
                                                                                "SiteReviewDate"    =>date("Y-m-d",strtotime($_REQUEST['SiteReviewDate'])),
                                                                                "RevisionsNeeded"   =>date("Y-m-d",strtotime($_REQUEST['RevisionsNeeded'])),
                                                                                "RevisionsComplete" =>date("Y-m-d",strtotime($_REQUEST['RevisionsComplete'])),
                                                                                "RevisionsAcceptedFinal"     =>$SiteAcceptanceFinal,
                                                                                "SiteAcceptanceFinal"=>$SiteAcceptanceFinal,
                                                                                "SetFormID"      =>$_REQUEST['SetFormID'],
                                                                                "BestCallTime"	       => $BestCallTime,
                                                                                "WorkProcessDate"=>$WorkProcessDate,
                                                          ));
                         foreach((array)$checked_value as $value){

                          $insertGroup= $ObjGroup->insertMemberGroup(array(
                                                                                "MemberID"=>$added,
                                                                                "GroupID"=>$value
                                                           ));
                                                                }
                        //$payment_array = $_REQUEST['PaymentAccepted'];        
                        
                                                //print_r($payment_method);
                                   
                                    
                                    $objmember->InsertPaymentMethod(array(
                                                                                "MemberID"=>$added,
                                                                                "Cash"=>$_REQUEST['Cash'],
                                                                                "Cheque"=>$_REQUEST['Cheque'],
                                                                                "MastercardVisa"=>$_REQUEST['MastercardVisa'],
                                                                                "AmericanExpress"=>$_REQUEST['AmericanExpress'],
                                                                                "OnlinePayments"=>$_REQUEST['OnlinePayments'], 
                                        ));


}else{
    header("location:Clients.php?flag=error"); 
}
    
    
}
if($_REQUEST['Task']=='Update')
{

$mid = $_REQUEST['id'];
$objMember = new Member();
//print_r($group_array);
//extract($_POST);
if($_FILES['ProfileImg']['size'] > 0){
	$profile_image = "user_pics/";
	$profile_image = $profile_image . basename( $_FILES['ProfileImg']['name']); 
	move_uploaded_file($_FILES['ProfileImg']['tmp_name'], $profile_image);
	list($width, $height, $type, $attr) = getimagesize("user_pics/" . $_FILES["ProfileImg"]["name"]);
		if($width<=130&&$height<=130){
		 
		  } else {
		  									
				
				$newwidth = 130;
				$newheight = 130;
				
				$thumbnail = "user_pics/".time()."medium_x.jpg";
                        
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				$source = imagecreatefromjpeg("user_pics/".basename( $_FILES['ProfileImg']['name']));

				// Resize
				imagecopyresized($thumb,$source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				
				// Output
				imagejpeg($thumb,$thumbnail,100);
		  		
				$profile_image=$thumbnail;
				  				
  				unlink("user_pics/" . $_FILES["ProfileImg"]["name"]);
  			
  		}
} else{
	$Members_array = $objMember->GetAllMember(" ID = '".$_REQUEST['id']."'",array("*"));
	//print_r($Members_array);
    $profile_image = $Members_array[0]['member_profile_image'];
}
    if(empty($_REQUEST['password'])){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $_REQUEST['password'] = substr(str_shuffle($chars),0,8);
        }
        if($_REQUEST['am_pm']== 'PM'){
            $_REQUEST['hour'] = $_REQUEST['hour']+12;
        }
		if($_REQUEST['followup_am_pm']== 'PM'){
            $_REQUEST['followup_hour'] = $_REQUEST['followup_hour']+12;
        }
        
		
         if(!empty($_REQUEST['WelCallComDate'])){
            $WelCallComDate = date("Y-m-d",strtotime($_REQUEST['WelCallComDate']));
        }else{
            $WelCallComDate=""; 
			$WelCallComID="";
        }
         if(!empty($_REQUEST['WorkProcessDate'])){
        $WorkProcessDate = date("Y-m-d",strtotime($_REQUEST['WorkProcessDate']));
        }else{
            $WorkProcessDate=""; 
        }
         if(!empty($_REQUEST['SiteAcceptanceFinal'])){
        $SiteAcceptanceFinal = date("Y-m-d",strtotime($_REQUEST['SiteAcceptanceFinal']));
        }else{
            $SiteAcceptanceFinal=""; 
        }
        if(!empty($_REQUEST['ContentComplete'])){
        $ContentComplete = date("Y-m-d",strtotime($_REQUEST['ContentComplete']));
        }else{
            $ContentComplete=""; 
        }
        if(!empty($_REQUEST['DesignComplete'])){
        $DesignComplete = date("Y-m-d",strtotime($_REQUEST['DesignComplete']));
        }else{
            $DesignComplete=""; 
        }
        if(!empty($_REQUEST['ContentReceived'])){
        $ContentReceived = date("Y-m-d",strtotime($_REQUEST['ContentReceived']));
        }else{
            $ContentReceived=""; 
        }
        if(!empty($_REQUEST['DesignUpdateCall'])){
        $DesignUpdateCall = date("Y-m-d",strtotime($_REQUEST['DesignUpdateCall']));
        }else{
            $DesignUpdateCall=""; 
        }
         if(!empty($_REQUEST['timebilling'])){
        $timebilling = date("Y-m-d",strtotime($_REQUEST['timebilling']));
        }else{
            $timebilling=""; 
        }
		
         if(!empty($_REQUEST['BestCallTime'])){
        $BestCallTime = date("Y-m-d",strtotime($_REQUEST['BestCallTime']));
        }else{
            $BestCallTime=""; 
        }
        
	if(!empty($_REQUEST['SubmitedBy'])){
        $SubmitedBy = $_REQUEST['SubmitedBy'];
        }else{
            $SubmitedBy=$_SESSION['Member']['ID']; 
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
        if(!empty($_REQUEST['workcompletedby'])){
        $workcompletedby = $_REQUEST['workcompletedby'];
        }else{
            $workcompletedby =$_SESSION['Member']['ID']; 
        }
        if(!empty($_REQUEST['WelCallComID'])){
        $WelCallComID = $_REQUEST['WelCallComID'];
        }else{
            $WelCallComID =$_SESSION['Member']['ID']; 
        }
        if(!empty($_REQUEST['siteacceptedby'])){
        $siteacceptedby = $_REQUEST['siteacceptedby'];
        }else{
            $siteacceptedby =$_SESSION['Member']['ID']; 
        }
		
	        $updated= $objMember->UpdateMember("ID = '$mid' ",array(
											"Created"	       		=>date("Y-m-d h:i:s",strtotime($_REQUEST['createddate'])),																                                            "FirstName"             		  =>$_REQUEST['fName'],
											"Surname"               	      =>$_REQUEST['sureName'],
											"Notes"                 	 	  =>$_REQUEST['Notes'],
											"Email"		            		  =>$_POST['Email'],
											"Phone"                 		  =>$_REQUEST['phone'],
											"Password"		            	  =>$_REQUEST['password'],
											"SkypeName"	            		  =>$_REQUEST['skypename'],
											"Address"	            		  =>$_REQUEST['address'],
											"Address2"          	   		  =>$_REQUEST['Address2'],
										  	"City"	                		  =>$_REQUEST['city'],
											 "State"	                      =>$_REQUEST['state'],
											 "ZipCode"	                      =>$_REQUEST['ZipCode'],
											 "member_profile_image"           =>$profile_image,
											 "CompanyName"	                  =>$_REQUEST['cname'],
											 "AlternatePhone"	               =>$_REQUEST['alternatephone'],
											 "SubmitedBy"                      =>$SubmitedBy,					 
                                             "DesignCompletedBy"        	   =>$designcompletedby,
                                             "ContentCompletedBy"              =>$contentcompletedby,
                                             "WorkCompletedBy"                 =>$workcompletedby,
                                             "SiteAcceptedBy"    		       =>$siteacceptedby,
											 "CustomersTimeZone"		       =>$_REQUEST['timezone'],
											 "BestCallTime"	       		       =>$BestCallTime,
											 "TimeBilling"			           =>$timebilling,	
											 "WelCallComID"             	   =>$WelCallComID,
											 "WelCallComDate"    		       =>$WelCallComDate,
											 "SetFormID"     			       =>$_REQUEST['SetFormID'],
											 "SetFormDate"                 	   =>date("Y-m-d h:i:s",strtotime($_REQUEST['SetFormDate'])),
											 "DesignUpdateCall"  	           =>$DesignUpdateCall,
											 "ContentReceived"                 =>$ContentReceived,
											 "DesignComplete"   		       =>$DesignComplete,
											 "ContentComplete"   	           =>$ContentComplete,
											 "DNSRecordSet"      	           =>date("Y-m-d h:i:s",strtotime($_REQUEST['DNSRecordSet'])), 
											 "SiteReviewID"     		       =>$_REQUEST['SiteReviewID'],
											 "SiteReviewDate"   		       =>date("Y-m-d h:i:s",strtotime($_REQUEST['SiteReviewDate'])),
											 "RevisionsNeeded"   	           =>date("Y-m-d h:i:s",strtotime($_REQUEST['RevisionsNeeded'])),						 "RevisionsComplete"			   =>date("Y-m-d h:i:s",strtotime($_REQUEST['RevisionsComplete'])),						 "RevisionsAcceptedFinal"          =>date("Y-m-d h:i:s",strtotime($_REQUEST['RevisionsAcceptedFinal'])),				 "SiteAcceptanceFinal"			   =>$SiteAcceptanceFinal,
											 "WorkProcessDate"				   =>$WorkProcessDate,
										     "ContactForBusiness"	   		   =>$_REQUEST['ContactForBusiness'],
									     	 "BusinessDBAs"             	   =>$_REQUEST['BusinessDBAs'],
										 	 "BusinessAddress"          	   =>$_REQUEST['BusinessAddress'],
										 	 "AddressDisplayWebsite"		   =>$_REQUEST['AddressDisplayWebsite'],
											 "BusinessEmail"             	   =>$_REQUEST['BusinessEmail'],
										 	 "BusinessPhone"             	   =>$_REQUEST['BusinessPhone'],
											 "AdditionalBusinessPhone"	       =>$_REQUEST['AdditionalBusinessPhone'],
											 "BusinessHoursOperation"	       =>$_REQUEST['BusinessHoursOperation'],
											 "PaymentAccepted"                 =>$_REQUEST['PaymentAccepted'],
											 "ExistingWebsite"	       		   =>$_REQUEST['ExistingWebsite'],
										 	 "ExistingSiteControls"	           =>$_REQUEST['ExistingSiteControls'],
											 "NOSiteNewDomain"                 =>$_REQUEST['NOSiteNewDomain'],
											 "BusinessLogo"	           		   =>$_REQUEST['BusinessLogo'],
											 "BusinessImages"	       		   =>$_REQUEST['BusinessImages'],
											 "BrandingColors"	       		   =>$_REQUEST['BrandingColors'],
										 	 "WebsiteIncludeCallAction"		   =>$_REQUEST['WebsiteIncludeCallAction'],
										 	 "TypeOfBusiness"          		   =>$_REQUEST['TypeOfBusiness'],	
											 "BusinessTagLine"          	   =>$_REQUEST['BusinessTagLine'],
											 "WhoYouAreAsBusiness"      	   =>$_REQUEST['WhoYouAreAsBusiness'],
											 "T0pBusinessSpecializes"      	   =>$_REQUEST['T0pBusinessSpecializes'],
										 	 "BusinessServesInCities"     	   =>$_REQUEST['BusinessServesInCities'],
											 "KeywordsRelevantBusiness"        =>$_REQUEST['KeywordsRelevantBusiness'],
										 	 "BusinessReviews"             	   =>$_REQUEST['BusinessReviews'],
											 "BusinessSocialMediaLinks"        =>$_REQUEST['BusinessSocialMediaLinks'],
											 "GoogleAccountInformation"        =>$_REQUEST['GoogleAccountInformation'],
											 "GoogleAnalyticsSetup"            =>$_REQUEST['GoogleAnalyticsSetup'],
											 "BlogSetup"                       =>$_REQUEST['BlogSetup'],
											 "LocalOptimizationPackage"        =>$_REQUEST['LocalOptimizationPackage'],
											 "LeadCapturePluginPackage"        =>$_REQUEST['LeadCapturePluginPackage'],
											 "SalesFunnelDevelopmentPackage"   =>$_REQUEST['SalesFunnelDevelopmentPackage'],
											 "MicrositeAddOn"                  =>$_REQUEST['MicrositeAddOn'],
											 "LogoDesign"                  	   =>$_REQUEST['LogoDesign'],
											 "TransferDomain"                  =>$_REQUEST['TransferDomain'],
											 "UserUpdates"                     =>$_REQUEST['UserUpdates'],
										 	 "PaymentAccepted"                 => $cash,
										 	 "BusinessStartedYear"	  		   =>date("Y-m-d ",strtotime($_REQUEST['BusinessStartedYear'])),
										 	"FollowUpAppointment"             	 =>date("Y-m-d ",strtotime($_REQUEST['followup_timepicker'])).$_REQUEST['hour'].":".$_REQUEST['minuts'].":00",
                                                          ));
 					  $objMember->DeleteMemberGroup($_REQUEST['id']);
                                          foreach((array)$checked_value as $value){
                                          $insertGroup= $ObjGroup->insertMemberGroup(array(
                                                                                        "MemberID"=>$_REQUEST['id'],
                                                                                        "GroupID"=>$value
							));
                                         
                                         $objmember->UpdatePaymentMethod("MemberID='".$_REQUEST['id']."'",array(
                                                                                
                                                                                "Cash"=>$_REQUEST['Cash'],
                                                                                "Cheque"=>$_REQUEST['Cheque'],
                                                                                "MastercardVisa"=>$_REQUEST['MastercardVisa'],
                                                                                "AmericanExpress"=>$_REQUEST['AmericanExpress'],
                                                                                "OnlinePayments"=>$_REQUEST['OnlinePayments'], 
                                        ));

 }



}


if(isset($_REQUEST['SendRevEmail'])){
$Members_array = $objMember->GetAllMember(" ID = '".$_REQUEST['id']."'",array("*"));

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
mail($to, $subject, $body, $headers, $From);
 
 $added= $objmember->InsertRevision(array(
					    "CurrentDate"  =>date("Y-m-d h:i:s",time()),
                        "RevisionNote" =>$_REQUEST['RevisionNote'],
					    "MemberID" =>$_REQUEST['id'],
					    "ByID"    =>$_SESSION['Member']['ID'],
					    "Done"    =>0,
											
                                          ));

}

//
 										

header("Location:Clients.php?id=".$_REQUEST['id']."&flag=update");
exit;


//
 /*end update hubopus */ 
if($_REQUEST['Task']=='del'){
//echo "kkkkk";
	$objmember->UpdateMember("ID = '".$_REQUEST['id']."'",array("HasDeleted"=>"1"));
	
}

if($_REQUEST['Task']=='UpdateAjaxRevision'){
    $Done=0;
    if($_REQUEST['checked']=='true'){
        $Done=1;
    }
$objmember->UpdateRevision("ID = '".$_REQUEST['ID']."'",array(
								"Done"=>$Done,	
											
							));
											
echo "Done!";

exit;
}



if($_REQUEST['Task']=='assign'){
	
	

	
foreach((array)$checked_value as $value){
 
 
   $insertGroup= $ObjGroup->insertMemberGroup(array(
						"MemberID"=>$_REQUEST['id'],
						"GroupID"=>$value
						));
						
						

}
header("Location:Members.php?page=".$_REQUEST['page']);
}



?>
