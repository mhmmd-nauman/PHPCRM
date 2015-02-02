<?php
extract($_REQUEST);
$objClient = new Clients();
$ObjGroup = new Groups();
$ObjTasks = new Tasks();
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
if(isset($_REQUEST['sortBy']))
	$_SESSION['sortBy'] = $_REQUEST['sortBy'];
if(isset($_REQUEST['sortValue']))
	$_SESSION['sortValue'] = $_REQUEST['sortValue'];
if(isset($_REQUEST['FromDate_Client']))
	$_SESSION['FromDate_Client'] = $_REQUEST['FromDate_Client'];
if(isset($_REQUEST['ToDate_Client']))
	$_SESSION['ToDate_Client'] = $_REQUEST['ToDate_Client'];

if(isset($_REQUEST['active_tab_on_submit']))
	$_SESSION['tab_activated'] = $_REQUEST['active_tab_on_submit'];

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

$cash_method = $_REQUEST['PaymentAccepted'];
$checked_value = $_REQUEST['MemberGroup'];
$Done = $_REQUEST['Done'];

if($_REQUEST['Task'] == 'add'){
        if($_FILES['ProfileImg']['size'] > 0){
            $profile_image = "user_pics/";
            $profile_image = $profile_image . basename( $_FILES['ProfileImg']['name']);
            move_uploaded_file($_FILES['ProfileImg']['tmp_name'], $profile_image);
        }
        if(empty($_REQUEST['fName']))$_REQUEST['fName'] = "New Member Name";
        if(empty($_REQUEST['Email']))$_REQUEST['Email'] = time()."@email.com";
        if(empty($_REQUEST['password'])){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $_REQUEST['password'] = substr(str_shuffle($chars),0,8);
        }
        if(!empty($_REQUEST['WelCallComDate'])){
            $WelCallComDate = date("Y-m-d",strtotime($_REQUEST['WelCallComDate']));
			$WelCallComID = $_SESSION['Member']['ID'];
        }else{
            $WelCallComDate = "";
			 $WelCallComID = "";
            
        }
         if(!empty($_REQUEST['DesignUpdateCall'])){
        $DesignUpdateCall = date("Y-m-d",strtotime($_REQUEST['DesignUpdateCall']));
        }else{
            $DesignUpdateCall = "";
            
        }
        
        if(!empty($_REQUEST['ContentReceived'])){
        $ContentReceived = date("Y-m-d",strtotime($_REQUEST['DesignUpdateCall']));
        }else{
            $ContentReceived = "";
        }
        if(!empty($_REQUEST['DesignComplete'])){
        	$DesignComplete = date("Y-m-d",strtotime($_REQUEST['DesignComplete']));
        }else{
            $DesignComplete = "";
        }
        
        if(!empty($_REQUEST['ContentComplete'])){
        	$ContentComplete = date("Y-m-d",strtotime($_REQUEST['ContentComplete']));
        }else{
            $ContentComplete = ""; 
        }
        if(!empty($_REQUEST['SiteAcceptanceFinal'])){
        	$SiteAcceptanceFinal = date("Y-m-d",strtotime($_REQUEST['SiteAcceptanceFinal']));
        }else{
            $SiteAcceptanceFinal = ""; 
        }
        if(!empty($_REQUEST['WorkProcessDate'])){
        	$WorkProcessDate = date("Y-m-d",strtotime($_REQUEST['WorkProcessDate']));
        }else{
            $WorkProcessDate = ""; 
        }
        if(!empty($_REQUEST['timebilling'])){
        	$timebilling = date("Y-m-d",strtotime($_REQUEST['timebilling']));
        }else{
            $timebilling = ""; 
        }
      
        if(!empty($_REQUEST['BestCallTime'])){
            $BestCallTime = date("Y-m-d ",strtotime($_REQUEST['BestCallTime'])).$_REQUEST['hour'].":".$_REQUEST['minuts'].":00";
        }else{
            $BestCallTime = ""; 
        }
		
	$Members_array = $objClient->GetAllClients("Email= '".$_REQUEST['Email']."'",array(CLIENTS.".ID,Email"));
	if($Members_array[0]['Email'] != $_REQUEST['Email']){
		$added = $objClient->InsertClients(array(
			"Created"	   			=> date("Y-m-d h:i:s",time()),
			"LastEdited"			=> date("Y-m-d h:i:s",time()),
			"FirstName"    			=> $_REQUEST['fName'],
			"Surname"      			=> $_REQUEST['sureName'],
			"Email"	       			=> $_REQUEST['Email'],
			"Phone"        			=> $_REQUEST['phone'],
			"MobilePhone"        	=> $_REQUEST['mobile_phone'],
			"Password"     			=> $_REQUEST['password'],
			"SkypeName"	   			=> $_REQUEST['skypename'],
			"Address"	   			=> $_REQUEST['address'],
			"Address2"	   			=> $_REQUEST['Address2'],
			"City"	       			=> $_REQUEST['city'],
			"State"	       			=> $_REQUEST['state'],
			"member_profile_image"	=> $profile_image,
			"State"	      	 		=> $_REQUEST['state'],
			"CompanyName"	       	=> $_REQUEST['cname'],
			"TimeBilling"			=> $timebilling,
			"AlternatePhone"		=> $_REQUEST['alternatephone'],
			/*"SubmitedBy"	       	=> $_SESSION['Member']['ID'],*/
			"BestCallTime"	       	=> $BestCallTime,
			"TaskID"				=> $_REQUEST['task_assign'],	
		));
	
		$objClient->InsertClientCCData(array(
			"ClientID"				=> $added,
			"AccountName"			=> $_REQUEST['AccName'],
			"BankName"				=> $_REQUEST['Bname'],
			"RoutingNumber"			=> $_REQUEST['Rnumber'],
			"AccountNumber"			=> $_REQUEST['AccNumber'],
			"AccHolderType"			=> $_REQUEST['AccHolderType'],
			"ChequeType"			=> $_REQUEST['ChequeType'],
			"ChequeNumber"			=> $_REQUEST['ChequeNumber'],
			"AccountType"			=> $_REQUEST['AccType'],
			"Status"				=> $_REQUEST['Status'],
			"CCType"				=> $_REQUEST['cctype'],
			"CCStatus"				=> $_REQUEST['ccstatus'],
			"CCNumber"				=> $_REQUEST['ccnumber'],
			"ExpirationDate"		=> date("Y-m-d",strtotime($_REQUEST['expirationdate'])),
		));
		header("location:ClientsEdit.php?id=".$added."&flag=update&Task=Update"); 
	}else{
		header("location:ClientsEdit.php?flag=errorUpdate&Task=add"); 
	}
}
if($_REQUEST['Task'] == 'Update'){
	
    $mid = $_REQUEST['id'];
    if($_FILES['ProfileImg']['size'] > 0){
            $profile_image = "user_pics/";
            $profile_image = $profile_image . basename( $_FILES['ProfileImg']['name']); 
            move_uploaded_file($_FILES['ProfileImg']['tmp_name'], $profile_image);
    } else{
            $Members_array = $objClient->GetAllClients("Clients.ID = '".$_REQUEST['id']."'",array("*"));
            $profile_image = $Members_array[0]['member_profile_image'];
    }
    if(empty($_REQUEST['password'])){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $_REQUEST['password'] = substr(str_shuffle($chars),0,8);
        }
        if($_REQUEST['am_pm'] == 'PM'){
            $_REQUEST['hour'] = $_REQUEST['hour']+12;
        }
	if($_REQUEST['followup_am_pm'] == 'PM'){
            $_REQUEST['followup_hour'] = $_REQUEST['followup_hour']+12;
        }
        
	
        if(!empty($_REQUEST['timebilling'])){
            $timebilling = date("Y-m-d",strtotime($_REQUEST['timebilling']));
        }else{
            $timebilling = ""; 
        }
		
        if(!empty($_REQUEST['BestCallTime'])){
            $BestCallTime = date("Y-m-d ",strtotime($_REQUEST['BestCallTime'])).$_REQUEST['hour'].":".$_REQUEST['minuts'].":00";
        }else{
            $BestCallTime = ""; 
        }
        
		if(!empty($_REQUEST['select'])){
			$SubmitedBy = $_REQUEST['select'];
		}
		
		$objClient->UpdateClients("Clients.ID = '$mid' ",array(
			"LastEdited"			=> date("Y-m-d h:i:s",time()),
			"FirstName"				=> $_REQUEST['fName'],
			"Surname"				=> $_REQUEST['sureName'],
			"Email"					=> $_POST['Email'],
			"Phone"					=> $_REQUEST['phone'],
			"MobilePhone"        	=> $_REQUEST['mobile_phone'],
			"Password"				=> $_REQUEST['password'],
			"SkypeName"				=> $_REQUEST['skypename'],
			"Address"				=> $_REQUEST['address'],
			"Address2"				=> $_REQUEST['Address2'],
			"City"					=> $_REQUEST['city'],
			"State"					=> $_REQUEST['state'],
			"ZipCode"				=> $_REQUEST['ZipCode'],
			"Notes"					=> $_REQUEST['OthersNotes'],
			"member_profile_image"	=> $profile_image,
			"CompanyName"			=> $_REQUEST['cname'],
			"AlternatePhone"		=> $_REQUEST['alternatephone'],
			# "SubmitedBy"			=> $SubmitedBy,
			"CustomersTimeZone"		=> $_REQUEST['timezone'],
			"BestCallTime"			=> $BestCallTime,
			"TimeBilling"			=> $timebilling,	
			"TaskID"				=> $_REQUEST['task_assign'],
		));
		
	if($_REQUEST['website_loaded'] == 1){
		$website_Tab_id = $objClient->GetAllClientTaskData("MemberID ='".$_REQUEST['id']."'",array("*"));
		if(empty($website_Tab_id[0]['MemberID'])){
			$objClient->InsertClientTaskData(array(
				"MemberID"					=> $_REQUEST['id'],
				"UserUpgrade" 				=> $_REQUEST['UserUpgrade'],
				"AdditionalWebsite" 		=> $_REQUEST['AdditionalWebsite'],
				"BlogSetup" 				=> $_REQUEST['BlogSetup'],
				"LocalOptimizationPackage" 	=> $_REQUEST['LocalOptimizationPackage'],
				"LeadCapturePackage" 		=> $_REQUEST['LeadCapturePluginPackage'],
				"SalesFunnelPackage" 		=> $_REQUEST['SalesFunnelDevelopmentPackage'],
				"MicrositeAddOn" 			=> $_REQUEST['MicrositeAddOn'],
				"LogoDesign" 				=> $_REQUEST['LogoDesign'],
				"TransferDomain" 			=> $_REQUEST['TransferDomain'],
			));									
		}else{
			$objClient->UpdateClientTaskData("MemberID='".$_REQUEST['id']."'",array(
				"UserUpgrade" 				=> $_REQUEST['UserUpgrade'],
				"AdditionalWebsite" 		=> $_REQUEST['AdditionalWebsite'],
				"BlogSetup" 				=> $_REQUEST['BlogSetup'],
				"LocalOptimizationPackage" 	=> $_REQUEST['LocalOptimizationPackage'],
				"LeadCapturePackage" 		=> $_REQUEST['LeadCapturePluginPackage'],
				"SalesFunnelPackage" 		=> $_REQUEST['SalesFunnelDevelopmentPackage'],
				"MicrositeAddOn" 			=> $_REQUEST['MicrositeAddOn'],
				"LogoDesign" 				=> $_REQUEST['LogoDesign'],
				"TransferDomain" 			=> $_REQUEST['TransferDomain'],
			));
		}
	}
	
	# CC Stand for credit card information
	if($_REQUEST['CCData_loaded'] == 1){
		$CCData_exist = $objClient->GetAllInformationCCData("ClientID ='".$_REQUEST['id']."'",array("*"));
		if(empty($CCData_exist[0]['ClientID'])){
			$objClient->InsertClientCCData(array(
				"ClientID"					=> $_REQUEST['id'],
				"AccountName"				=> $_REQUEST['AccName'],
				"BankName"					=> $_REQUEST['Bname'],
				"RoutingNumber"				=> $_REQUEST['Rnumber'],
				"AccountNumber"				=> $_REQUEST['AccNumber'],
				"AccHolderType"				=> $_REQUEST['AccHolderType'],
				"ChequeType"				=> $_REQUEST['ChequeType'],
				"ChequeNumber"				=> $_REQUEST['ChequeNumber'],
				"AccountType"				=> $_REQUEST['AccType'],
				"Status"					=> $_REQUEST['Status'],
				"CCType"					=> $_REQUEST['cctype'],
				"CCStatus"					=> $_REQUEST['ccstatus'],
				"CCNumber"					=> $_REQUEST['ccnumber'],
				"ExpirationDate"			=> date("Y-m-d",strtotime($_REQUEST['expirationdate'])),
			));
		}else{
			
			$objClient->UpdateClientCCData("ClientID = '".$_REQUEST['id']."'",
			array(
				"AccountName"				=> $_REQUEST['AccName'],
				"BankName"					=> $_REQUEST['Bname'],
				"RoutingNumber"				=> $_REQUEST['Rnumber'],
				"AccountNumber"				=> $_REQUEST['AccNumber'],
				"AccHolderType"				=> $_REQUEST['AccHolderType'],
				"ChequeType"				=> $_REQUEST['ChequeType'],
				"ChequeNumber"				=> $_REQUEST['ChequeNumber'],
				"AccountType"				=> $_REQUEST['AccType'],
				"Status"					=> $_REQUEST['Status'],
				"CCType"					=> $_REQUEST['cctype'],
				"CCStatus"					=> $_REQUEST['ccstatus'],
				"CCNumber"					=> $_REQUEST['ccnumber'],
				"ExpirationDate"			=> date("Y-m-d",strtotime($_REQUEST['expirationdate'])),
			));
		}
	}

 
	if($_REQUEST['checklist_loaded'] == 1){
		$ChecklistDate = $_REQUEST['ChecklistDate'];
		foreach((array)$ChecklistDate as $list_id => $date){
			if(empty($date)){
				$date = "";
			}else{
				$date = date("Y-m-d",strtotime($date));
			}
			$CheckList_exist = $ObjTasks->GetTaskCheckListData("ClientID ='".$_REQUEST['id']."' AND ID = $list_id",array("*"));
			if(empty($CheckList_exist['ID'])){
				$ObjTasks->InsertTaskCheckListData(array(
					"ClientID"			=> $_REQUEST['id'],
					"ID"				=> $list_id,
					"SaveDate"			=> $date,
				));
			}else{
				$ObjTasks->UpdateTaskCheckListData("ID = $list_id AND ClientID = '".$_REQUEST['id']."'",
				array(
					"SaveDate"			=> $date,
				));
			}
		}
	}
 
 
	if($_REQUEST['gyb_loaded'] == 1){
		$gyb_Tab_id = $objClient->GetAllClientGYBData("MemberID ='".$_REQUEST['id']."'",array("*"));
		if(empty($gyb_Tab_id[0]['MemberID'])){
			if(!empty($_REQUEST['GYB_FileAttachments'])){
				$profile_image = "user_pics/";
				$profile_image = $profile_image . basename( $_REQUEST['GYB_FileAttachments']); 
				move_uploaded_file($_FILES['GYB_FileAttachments']['tmp_name'], $profile_image);
			}else{
				$Members_array = $objClient->GetAllClients("Clients.ID = '".$_REQUEST['id']."'",array("*"));
				$profile_image = $Members_array[0]['member_profile_image'];
			}
			$objClient->InsertClientGYBData(array(
				"MemberID"                   		=> $_REQUEST['id'],
				"GYB_BusinessName"	   		 		=> $_REQUEST['GYB_BusinessName'],
				"GYB_AddressLine1"   		 		=> $_REQUEST['GYB_AddressLine1'],
				"GYB_AddressLine2"           		=> $_REQUEST['GYB_AddressLine2'],
				"GYB_City"              	 		=> $_REQUEST['GYB_City'],
				"GYB_State"                  		=> $_REQUEST['GYB_State'],
				"GYB_ZipCode"                		=> $_REQUEST['GYB_ZipCode'],
				"GYB_MainBusinessPhone"     		=> $_REQUEST['GYB_MainBusinessPhone'],
				"GYB_TollFreeNumber"         		=> $_REQUEST['GYB_TollFreeNumber'],
				"GYB_FaxNumber"              		=> $_REQUEST['GYB_FaxNumber'],
				"GYB_AlternatePhone"         		=> $_REQUEST['GYB_AlternatePhone'],
				"GYB_MobilePhone"            		=> $_REQUEST['GYB_MobilePhone'],
				"GYB_BusinessEmailAddress"   		=> $_REQUEST['GYB_BusinessEmailAddress'],
				"GYB_BusinessWebSiteURL"     		=> $_REQUEST['GYB_BusinessWebSiteURL'],
				"GYB_BusinessDescription"    		=> $_REQUEST['GYB_BusinessDescription'],
				"GYB_FacebookURL"            		=> $_REQUEST['GYB_FacebookURL'],
				"GYB_TwitterURL"             		=> $_REQUEST['GYB_TwitterURL'],
				"GYB_LogoURL"				 		=> $_REQUEST['GYB_LogoURL'],
				"GYB_PhotoURL1"             		=> $_REQUEST['GYB_PhotoURL1'],
				"GYB_PhotoURL2"              		=> $_REQUEST['GYB_PhotoURL2'],
				"GYB_PhotoURL3"              		=> $_REQUEST['GYB_PhotoURL3'],
				"GYB_PhotoURL4"              		=> $_REQUEST['GYB_PhotoURL4'],
				"GYB_VideoURL"               		=> $_REQUEST['GYB_VideoURL'],
				"GYB_HoursOfOperations"      		=> $_REQUEST['GYB_HoursOfOperations'],
				"GYB_PaymentTypesAccepted"   		=> $_REQUEST['GYB_PaymentTypesAccepted'],
				"GYB_YearFounded"            		=> $_REQUEST['GYB_YearFounded'],
				"GYB_Products"               		=> $_REQUEST['GYB_Products'],
				"GYB_Services"               		=> $_REQUEST['GYB_Services'],
				"GYB_Brands"                 		=> $_REQUEST['GYB_Brands'],
				"GYB_SpecialtiesKeywords"    		=> $_REQUEST['GYB_SpecialtiesKeywords'],
				"GYB_ProfessionalAssociations"    	=> $_REQUEST['GYB_ProfessionalAssociations'],
				"GYB_BusinessLanguages"           	=> $_REQUEST['GYB_BusinessLanguages'],
				"GYB_PrimaryContactName"          	=> $_REQUEST['GYB_PrimaryContactName'],
				"GYB_PrimaryContactEmail"         	=> $_REQUEST['GYB_PrimaryContactEmail'],
				"GYB_AdditionalNotes"             	=> $_REQUEST['GYB_AdditionalNotes'],
				"GYB_FulfillmentRep"              	=> $_REQUEST['GYB_FulfillmentRep'],
				"GYB_DateEntered"                 	=> $_REQUEST['GYB_DateEntered'],
				"GYB_GrossRevenue"                	=> $_REQUEST['GYB_GrossRevenue'],
				"GYB_TypesOfMarketing"            	=> $_REQUEST['GYB_TypesOfMarketing'],
				"GYB_FileAttachments"             	=> $profile_image,
			));
		}else{
			if(!empty($_REQUEST['GYB_FileAttachments'])){
				$profile_image = "user_pics/";
				$profile_image = $profile_image . basename( $_REQUEST['GYB_FileAttachments']); 
				move_uploaded_file($_FILES['GYB_FileAttachments']['tmp_name'], $profile_image);
			}else{
				$Members_array = $objClient->GetAllClients("Clients.ID = '".$_REQUEST['id']."'",array("*"));
				$profile_image = $Members_array[0]['member_profile_image'];
			}
			$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",array(
			"GYB_BusinessName"	   		 		=> $_REQUEST['GYB_BusinessName'],
			"GYB_AddressLine1"   		 		=> $_REQUEST['GYB_AddressLine1'],
			"GYB_AddressLine2"           		=> $_REQUEST['GYB_AddressLine2'],
			"GYB_City"              	 		=> $_REQUEST['GYB_City'],
			"GYB_State"                  		=> $_REQUEST['GYB_State'],
			"GYB_ZipCode"                		=> $_REQUEST['GYB_ZipCode'],
			"GYB_MainBusinessPhone"      		=> $_REQUEST['GYB_MainBusinessPhone'],
			"GYB_TollFreeNumber"         		=> $_REQUEST['GYB_TollFreeNumber'],
			"GYB_FaxNumber"             		=> $_REQUEST['GYB_FaxNumber'],
			"GYB_AlternatePhone"         		=> $_REQUEST['GYB_AlternatePhone'],
			"GYB_MobilePhone"            		=> $_REQUEST['GYB_MobilePhone'],
			"GYB_BusinessEmailAddress"   		=> $_REQUEST['GYB_BusinessEmailAddress'],
			"GYB_BusinessWebSiteURL"     		=> $_REQUEST['GYB_BusinessWebSiteURL'],
			"GYB_BusinessDescription"   		=> $_REQUEST['GYB_BusinessDescription'],
			"GYB_FacebookURL"            		=> $_REQUEST['GYB_FacebookURL'],
			"GYB_TwitterURL"             		=> $_REQUEST['GYB_TwitterURL'],
			"GYB_LogoURL"				 		=> $_REQUEST['GYB_LogoURL'],
			"GYB_PhotoURL1"              		=> $_REQUEST['GYB_PhotoURL1'],
			"GYB_PhotoURL2"              		=> $_REQUEST['GYB_PhotoURL2'],
			"GYB_PhotoURL3"              		=> $_REQUEST['GYB_PhotoURL3'],
			"GYB_PhotoURL4"              		=> $_REQUEST['GYB_PhotoURL4'],
			"GYB_VideoURL"               		=> $_REQUEST['GYB_VideoURL'],
			"GYB_HoursOfOperations"      		=> $_REQUEST['GYB_HoursOfOperations'],
			"GYB_PaymentTypesAccepted"   		=> $_REQUEST['GYB_PaymentTypesAccepted'],
			"GYB_YearFounded"            		=> $_REQUEST['GYB_YearFounded'],
			"GYB_Products"               		=> $_REQUEST['GYB_Products'],
			"GYB_Services"               		=> $_REQUEST['GYB_Services'],
			"GYB_Brands"                 		=> $_REQUEST['GYB_Brands'],
			"GYB_SpecialtiesKeywords"    		=> $_REQUEST['GYB_SpecialtiesKeywords'],
			"GYB_ProfessionalAssociations"    	=> $_REQUEST['GYB_ProfessionalAssociations'],
			"GYB_BusinessLanguages"           	=> $_REQUEST['GYB_BusinessLanguages'],
			"GYB_PrimaryContactName"          	=> $_REQUEST['GYB_PrimaryContactName'],
			"GYB_PrimaryContactEmail"         	=> $_REQUEST['GYB_PrimaryContactEmail'],
			"GYB_AdditionalNotes"             	=> $_REQUEST['GYB_AdditionalNotes'],
			"GYB_FulfillmentRep"              	=> $_REQUEST['GYB_FulfillmentRep'],
			"GYB_DateEntered"                 	=> $_REQUEST['GYB_DateEntered'],
			"GYB_GrossRevenue"                	=> $_REQUEST['GYB_GrossRevenue'],
			"GYB_TypesOfMarketing"            	=> $_REQUEST['GYB_TypesOfMarketing'],
			"GYB_FileAttachments"             	=> $profile_image,
			));
		}
	}
 

								
	if($_REQUEST['question_loded'] == 1){
		$Question_Tab_id = $objClient->GetAllClientTaskData("MemberID ='".$_REQUEST['id']."'",array("*"));
		if(empty($Question_Tab_id[0]['MemberID'])){
			$objClient->InsertClientQuestionTaskData(array(
				"ClientTaskBName"              => $_REQUEST['QuestionBname'],
				"ClientCity"                   => $_REQUEST['city'],
				"ClientState"                  => $_REQUEST['state'],
				"ClientZipCode"                => $_REQUEST['ZipCode'],
				"ClientTaskPhone"              => $_REQUEST['Cphone'],
				"ClientAlternatePhone"         => $_REQUEST['Calternatephone'],
				"ClientAddress"	               => $_REQUEST['Caddress'],
				"ClintAddress2"	               => $_REQUEST['CAddress2'],
				"AddressOnWebsite"	           => $_REQUEST['AddressDisplayWebsite'],
				"BusinessHoursOperation"       => $_REQUEST['BusinessHoursOperation'],
				"ExistingWebsiteUrl"	       => $_REQUEST['ExistingWebsite'],
				"BusinessStarted"	  		   => $_REQUEST['BusinessStartedYear'],
				"AdminExsistingSite"	  	   => $_REQUEST['ExistingSiteControls'],
				"NewDomainName"	  		       => $_REQUEST['NOSiteNewDomain'],
				"BusinessLogo"	  		       => $_REQUEST['BusinessLogo'],
				"BusinessImage"	  		       => $_REQUEST['BusinessImages'],
				"WebsiteToCallAction"	  	   => $_REQUEST['WebsiteIncludeCallAction'],
				"BusinessType"	  		       => $_REQUEST['TypeOfBusiness'],
				"BusinessTagline"	  		   => $_REQUEST['BusinessTagLine'],
				"WhoYouAreAsBusiness"	  	   => $_REQUEST['WhoYouAreAsBusiness'],
				"TopThingsOfBusiness"	  	   => $_REQUEST['T0pBusinessSpecializes'],
				"BusinessInMainCities"	  	   => $_REQUEST['BusinessServesInCities'],
				"BusinessKeyWords"	  		   => $_REQUEST['KeywordsRelevantBusiness'],
				"BusinessTestiminal"	  	   => $_REQUEST['BusinessReviews'],
				"SocialMediaLinks"	  		   => $_REQUEST['BusinessSocialMediaLinks'],
				"AnalyticsLoginDetail"	  	   => $_REQUEST['GoogleAccountInformation'],
				"Cash"                         => $_REQUEST['Cash'],
				"Cheque"                       => $_REQUEST['Cheque'],
				"CardType"                     => $_REQUEST['CardType'],
				"AmericanExpress"              => $_REQUEST['AmericanExpress'],
				"OnlinePayments"               => $_REQUEST['OnlinePayments'],
				"BrandingColors1"	  		   => $_REQUEST['BrandingColors1'],
				"BrandingColors2"	  		   => $_REQUEST['BrandingColors2'],
				"BrandingColors3"	  		   => $_REQUEST['BrandingColors3'],
			));
		}else{
		$objClient->UpdateClientQuestionTaskData("MemberID='".$_REQUEST['id']."'",array(
				"ClientTaskBName"              	=> $_REQUEST['QuestionBname'],
				"ClientCity"                   	=> $_REQUEST['city'],
				"ClientState"                  	=> $_REQUEST['state'],
				"ClientZipCode"                	=> $_REQUEST['ZipCode'],
				"ClientTaskPhone"              	=> $_REQUEST['Cphone'],
				"ClientAlternatePhone"         	=> $_REQUEST['Calternatephone'],
				"ClientAddress"	               	=> $_REQUEST['Caddress'],
				"ClintAddress2"	               	=> $_REQUEST['CAddress2'],
				"AddressOnWebsite"	         	=> $_REQUEST['AddressDisplayWebsite'],
				"BusinessHoursOperation"     	=> $_REQUEST['BusinessHoursOperation'],
				"ExistingWebsiteUrl"	     	=> $_REQUEST['ExistingWebsite'],
				"BusinessStarted"	  		 	=> $_REQUEST['BusinessStartedYear'],
				"AdminExsistingSite"	     	=> $_REQUEST['ExistingSiteControls'],
				"NewDomainName"	  		     	=> $_REQUEST['NOSiteNewDomain'],
				"BusinessLogo"	  		     	=> $_REQUEST['BusinessLogo'],
				"BusinessImage"	  		     	=> $_REQUEST['BusinessImages'],
				"WebsiteToCallAction"	  	 	=> $_REQUEST['WebsiteIncludeCallAction'],
				"BusinessType"	  		     	=> $_REQUEST['TypeOfBusiness'],
				"BusinessTagline"	  		 	=> $_REQUEST['BusinessTagLine'],
				"WhoYouAreAsBusiness"	  	 	=> $_REQUEST['WhoYouAreAsBusiness'],
				"TopThingsOfBusiness"	  	 	=> $_REQUEST['T0pBusinessSpecializes'],
				"BusinessInMainCities"	  	 	=> $_REQUEST['BusinessServesInCities'],
				"BusinessKeyWords"	  		 	=> $_REQUEST['KeywordsRelevantBusiness'],
				"BusinessTestiminal"	  	 	=> $_REQUEST['BusinessReviews'],
				"SocialMediaLinks"	  		 	=> $_REQUEST['BusinessSocialMediaLinks'],
				"AnalyticsLoginDetail"	  	 	=> $_REQUEST['GoogleAccountInformation'],
				"Cash"                       	=> $_REQUEST['Cash'],
				"Cheque"                     	=> $_REQUEST['Cheque'],
				"CardType"                   	=> $_REQUEST['CardType'],
				"AmericanExpress"            	=> $_REQUEST['AmericanExpress'],
				"OnlinePayments"             	=> $_REQUEST['OnlinePayments'],
				"BrandingColors"	  		 	=> $_REQUEST['BrandingColors'],
				"BrandingColors1"	  		 	=> $_REQUEST['BrandingColors1'],
				"BrandingColors2"	  		 	=> $_REQUEST['BrandingColors2'],
				"BrandingColors3"	  		 	=> $_REQUEST['BrandingColors3'],
			));
		}
	}

 if($_REQUEST['citation_loaded'] == 1){
    $Citation_Tab_id = $objClient->GetAllClientCitationData("MemberID ='".$_REQUEST['id']."'",array("*"));
    if(empty($Citation_Tab_id[0]['MemberID'])){
        $added= $objClient->InsertClientCitationData(array(
			"CompanyName"	   			 => $_REQUEST['Bname'],
			"BestPhone"   				 => $_REQUEST['Bphone'],
			"AlternatePhone"             => $_REQUEST['Aphone'],
			"MobilePhone"                => $_REQUEST['Mphone'],
			"Fax"                        => $_REQUEST['fax'],
			"PublicEmail"                => $_REQUEST['Pemail'],
			"WebsiteUrl"                 => $_REQUEST['websiteurl'],
			"Address"                    => $_REQUEST['Address'],
			"City"                       => $_REQUEST['city'],
			"State"                      => $_REQUEST['state'],
			"Zip"                        => $_REQUEST['Zip'],
			"Country"                    => $_REQUEST['country'],
			"Description"                => $_REQUEST['Description'],
			"Keywords"                   => $_REQUEST['keywords'],
			"KeywordsNotes"              => $_REQUEST['Keywordsnotes'],
			"MainCatagory"               => $_REQUEST['Mcatagory'],
			"BusinessListingYearStarted" => $_REQUEST['startedyear'],
			"CitiesServed"               => $_REQUEST['Cserved'],
			"HoursOperation"             => $_REQUEST['HOperation'],
			"DisplayAddress"             => $_REQUEST['Daddress'],
			"DisplayHours"               => $_REQUEST['Dhours'],
			"ServiceAtLocation"          => $_REQUEST['Slocation'],
			"ServiceAreaRadius"          => $_REQUEST['SAradius'],
			"LanguageSpoken"             => $_REQUEST['LSpoken'],
			"Image1"                     => $_REQUEST['image1'],
			"Image2"                     => $_REQUEST['image2'],
			"Image3"                     => $_REQUEST['image3'],
			"Image4"                     => $_REQUEST['image4'],
			"Image5"                     => $_REQUEST['image5'],
			"BizListingVideo"            => $_REQUEST['video1'],
			"MemberID"                   => $_REQUEST['id'],
			"Cash"            			 => $_REQUEST['Citationcash'],
			"Cheque"                     => $_REQUEST['Citationcheque'],
			"Visa"                       => $_REQUEST['CitationVisa'],
			"MasterCard"                 => $_REQUEST['CitationMastercard'],
			"Discoverd"                  => $_REQUEST['CitationDiscoverd'],
			"DinerClub"                  => $_REQUEST['Citationdinersclub'],
			"AmericanExpress"            => $_REQUEST['CitationAmericanExpress'],
			));   
   }else{
        $objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("CompanyName"=> $_REQUEST['Bname'],
                        "BestPhone"                  => $_REQUEST['Bphone'],
                        "AlternatePhone"             => $_REQUEST['Aphone'],
                        "MobilePhone"                => $_REQUEST['Mphone'],
                        "Fax"                        => $_REQUEST['fax'],
                        "PublicEmail"                => $_REQUEST['Pemail'],
                        "WebsiteUrl"                 => $_REQUEST['websiteurl'],
                        "Address"                    => $_REQUEST['Address'],
                        "City"                       => $_REQUEST['city'],
                        "State"                      => $_REQUEST['state'],
                        "Zip"                        => $_REQUEST['Zip'],
                        "Country"                    => $_REQUEST['country'],
                        "Description"                => $_REQUEST['Description'],
                        "Keywords"                   => $_REQUEST['keywords'],
                        "KeywordsNotes"              => $_REQUEST['Keywordsnotes'],
                        "MainCatagory"               => $_REQUEST['Mcatagory'],
                        "BusinessListingYearStarted" => $_REQUEST['startedyear'],
                        "CitiesServed"               => $_REQUEST['Cserved'],
                        "HoursOperation"             => $_REQUEST['HOperation'],
                        "DisplayAddress"             => $_REQUEST['Daddress'],
                        "DisplayHours"               => $_REQUEST['Dhours'],
                        "ServiceAtLocation"          => $_REQUEST['Slocation'],
                        "ServiceAreaRadius"          => $_REQUEST['SAradius'],
                        "LanguageSpoken"             => $_REQUEST['LSpoken'],
                        "Image1"                     => $_REQUEST['image1'],
                        "Image2"                     => $_REQUEST['image2'],
                        "Image3"                     => $_REQUEST['image3'],
                        "Image4"                     => $_REQUEST['image4'],
                        "Image5"                     => $_REQUEST['image5'],
                        "BizListingVideo"            => $_REQUEST['video1'],
						"Cash"            			 => $_REQUEST['Citationcash'],
						"Cheque"                     => $_REQUEST['Citationcheque'],
						"Visa"                       => $_REQUEST['CitationVisa'],
						"MasterCard"                 => $_REQUEST['CitationMastercard'],
						"Discoverd"                  => $_REQUEST['CitationDiscoverd'],
						"DinerClub"                  => $_REQUEST['Citationdinersclub'],
						"AmericanExpress"            => $_REQUEST['CitationAmericanExpress'],
                    ));
	}		
					
 }
 
if($_REQUEST['LoginDetail'] == 1){
	$Client_Login_array = $objClient->GetUserLoginDetail("ID='".$_REQUEST['id']."'",array("*"));
	if(empty($Client_Login_array[0]['ID'])){
		$objClient->InsertUserLoginDetail(array(
			"ID"					=> $_REQUEST['id'],
			"Username"				=> $_REQUEST['Username'],
			"Password"				=> $_REQUEST['Password'],
			"GoogleLocalUrlLink"	=> $_REQUEST['GoogleLocalUrlLink'],
			"GoogleLocalUname"		=> $_REQUEST['GoogleLocalUname'],
			"GoogleLocalPassword"	=> $_REQUEST['GoogleLocalPassword'],
			"GoogleLocalPin"		=> $_REQUEST['GoogleLocalPin'],
			"YahooLocalUrlLink"		=> $_REQUEST['YahooLocalUrlLink'],
			"YahooLocalUname"		=> $_REQUEST['YahooLocalUname'],
			"YahooLocalPassword"	=> $_REQUEST['YahooLocalPassword'],
			"YahooLocalPin"			=> $_REQUEST['YahooLocalPin'],
			"BingLocalUrlLink"		=> $_REQUEST['BingLocalUrlLink'],
			"BingLocalUname"		=> $_REQUEST['BingLocalUname'],
			"BingLocalPassword"		=> $_REQUEST['BingLocalPassword'],
			"BingLocalPin"			=> $_REQUEST['BingLocalPin'],
			"HasDeleted"			=> 0,
			"Editedby"				=> $_SESSION['Member']['ID'],
			"Created"	   			=> date("Y-m-d h:i:s",time()),
		));
	}
	else{
		$objClient->UpdateUserLoginDetail("ID = '".$_REQUEST['id']."' ",array(
			"Username"				=> $_REQUEST['Username'],
			"Password"				=> $_REQUEST['Password'],
			"GoogleLocalUrlLink"	=> $_REQUEST['GoogleLocalUrlLink'],
			"GoogleLocalUname"		=> $_REQUEST['GoogleLocalUname'],
			"GoogleLocalPassword"	=> $_REQUEST['GoogleLocalPassword'],
			"GoogleLocalPin"		=> $_REQUEST['GoogleLocalPin'],
			"YahooLocalUrlLink"		=> $_REQUEST['YahooLocalUrlLink'],
			"YahooLocalUname"		=> $_REQUEST['YahooLocalUname'],
			"YahooLocalPassword"	=> $_REQUEST['YahooLocalPassword'],
			"YahooLocalPin"			=> $_REQUEST['YahooLocalPin'],
			"BingLocalUrlLink"		=> $_REQUEST['BingLocalUrlLink'],
			"BingLocalUname"		=> $_REQUEST['BingLocalUname'],
			"BingLocalPassword"		=> $_REQUEST['BingLocalPassword'],
			"BingLocalPin"			=> $_REQUEST['BingLocalPin'],
		));
	}
}


header("Location:ClientsEdit.php?client_id=".$_REQUEST['id']."&id=".$_REQUEST['id']."&flag=update&TaskID=".$_REQUEST['TaskID']);	
}

	if($_REQUEST['Task'] == 'UpdateChecklistData'){
		$CheckList_exist = $ObjTasks->GetTaskCheckListData("ClientID ='".$_REQUEST['ClientID']."' AND ID = '".$_REQUEST['ID']."'",array("*"));
		if(empty($CheckList_exist[0]['ID'])){
			$ObjTasks->InsertTaskCheckListData(array(
				"ClientID"				=> $_REQUEST['ClientID'],
				"ID"					=> $_REQUEST['ID'],
				"SaveDate"				=> date("Y-m-d",strtotime($_REQUEST['Checklistdate1'])),
			));
		}else{
			$ObjTasks->UpdateTaskCheckListData("ID = '".$_REQUEST['ID']."' AND ClientID = '".$_REQUEST['ClientID']."'",
			array(
				"SaveDate"				=> date("Y-m-d",strtotime($_REQUEST['Checklistdate1'])),
			));
		}
		exit;
	}
 
	if(isset($_REQUEST['SendRevEmail'])){
		$Members_array = $objClient->GetAllClients("Clients.ID = '".$_REQUEST['id']."'",array("*"));
		$subject = "EzbManger - New Revision for ".$Members_array[0]['CompanyName']." - ".$Members_array[0]['FirstName']." ".$Members_array[0]['Surname'];
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=utf8\r\n";
		$bcc = 'mhmmd.nauman@gmail.com';
		$cc = '';
		if($cc != ""){
			$headers .= 'CC: '. $cc . "\r\n";
		}
		if($bcc != ""){
			$headers .= 'Bcc: '. $bcc . "\r\n";
		} 
		$From = $SystemSettingsArray[0]['Email'];
		$headers .= 'From: EzbManager<'.$From.'>';
		$body = $_REQUEST['RevisionNote']."  <br>"."<br>Sincerely,<br>EZB Manager.<br><a href='http://mos2581.info'>http://mos2581.info</a>";
		$to = $SystemSettingsArray[0]['EmailTo'];
		mail($to, $subject, $body, $headers, $From);
		$added = $objClient->InsertRevision(array(
			"CurrentDate"  		=> date("Y-m-d h:i:s",time()),
			"RevisionNote" 		=> $_REQUEST['RevisionNote'],
			"MemberID"			=> $_REQUEST['id'],
			"ByID"    			=> $_SESSION['Member']['ID'],
			"Done"    			=> 0,
		));
		header("Location:ClientsEdit.php?id=".$_REQUEST['id']."&flag=update");
		exit;
	}


	if($_REQUEST['Task'] == 'del'){
		$objClient->UpdateClients("ID = '".$_REQUEST['id']."'",array("HasDeleted" => "1"));
	}

	if($_REQUEST['Task'] == 'UpdateAjaxRevision'){
		$Done = 0;
		if($_REQUEST['checked'] == 'true'){
			$Done = 1;
		}
		$objClient->UpdateRevision("ID = '".$_REQUEST['ID']."'",array(
			"Done"				=> $Done,	
		));
		echo "Done!";
		exit;
	}

	if($_REQUEST['Task'] == 'assign'){
		foreach((array)$checked_value as $value){
			$insertGroup = $ObjGroup->insertMemberGroup(array(
				"MemberID" 		=> $_REQUEST['id'],
				"GroupID" 		=> $value
			));
		}
		header("Location:Clients.php?page=".$_REQUEST['page']);
	}
?>