<?php 
include dirname(__FILE__)."/../lib/include.php"; 
//$objtasks = new Tasks();
$objClient = new Clients();
$utilObj = new util();
$CatRecords=$utilObj->getMultipleRow('BusinessCategory',"1 ORDER BY CategoryName ASC");
//print_r($CatRecords);
if($_REQUEST['Savebtn']==111)
{
//echo $_REQUEST['AddNewCategory'];
foreach((array)$CatRecords as $check_list_row ){
if($check_list_row['CategoryName']==$_REQUEST['AddNewCategory'])
 {
 		$_REQUEST['AddNewCategory']='Null';
 
 }
}
if($_REQUEST['AddNewCategory']=='Null'||$_REQUEST['AddNewCategory']=='')
{
echo 0;
exit;
}else{
$arrValue=array(
'CategoryName'=>$_REQUEST['AddNewCategory'],
"Created"	  =>date("Y-m-d h:i:s",time()),
);
$insertedId=$utilObj->insertRecord('BusinessCategory', $arrValue);
$_SESSION['tab_activated']=2;}
//echo "inerted";
}

if($_REQUEST['Task']=='Update')
{

$objClient->UpdateClientQuestionTaskData("MemberID='".$_REQUEST['id']."'",array(
                                                                                
                                        "ClientTaskBName"              =>$_REQUEST['QuestionBname'],
										"ClientCity"                   =>$_REQUEST['city'],
										"ClientState"                  =>$_REQUEST['state'],
										"ClientZipCode"                =>$_REQUEST['ZipCode'],
 										"ClientTaskPhone"              =>$_REQUEST['Cphone'],
										"ClientAlternatePhone"         =>$_REQUEST['Calternatephone'],
										"ClientAddress"	               =>$_REQUEST['Caddress'],
										"ClintAddress2"	               =>$_REQUEST['CAddress2'],
										"AddressOnWebsite"	         =>$_REQUEST['AddressDisplayWebsite'],
										"BusinessHoursOperation"     =>$_REQUEST['BusinessHoursOperation'],
										"ExistingWebsiteUrl"	     =>$_REQUEST['ExistingWebsite'],
										"BusinessStarted"	  		 =>$_REQUEST['BusinessStartedYear'],
										"AdminExsistingSite"	     =>$_REQUEST['ExistingSiteControls'],
										"NewDomainName"	  		     =>$_REQUEST['NOSiteNewDomain'],
										"BusinessLogo"	  		     =>$_REQUEST['BusinessLogo'],
										"BusinessImage"	  		     =>$_REQUEST['BusinessImages'],
										//"BrandingColors"	  		 =>$_REQUEST['BrandingColors'],
										"WebsiteToCallAction"	  	 =>$_REQUEST['WebsiteIncludeCallAction'],
										"BusinessType"	  		     =>$_REQUEST['TypeOfBusiness'],
										"BusinessTagline"	  		 =>$_REQUEST['BusinessTagLine'],
										"WhoYouAreAsBusiness"	  	 =>$_REQUEST['WhoYouAreAsBusiness'],
										"TopThingsOfBusiness"	  	 =>$_REQUEST['T0pBusinessSpecializes'],
										"BusinessInMainCities"	  	 =>$_REQUEST['BusinessServesInCities'],
										"BusinessKeyWords"	  		 =>$_REQUEST['KeywordsRelevantBusiness'],
										"BusinessTestiminal"	  	 =>$_REQUEST['BusinessReviews'],
										"SocialMediaLinks"	  		 =>$_REQUEST['BusinessSocialMediaLinks'],
										"AnalyticsLoginDetail"	  	 =>$_REQUEST['GoogleAccountInformation'],
										"Cash"                       =>$_REQUEST['Cash'],
										"Cheque"                     =>$_REQUEST['Cheque'],
										"CardType"                   =>$_REQUEST['CardType'],
										"AmericanExpress"            =>$_REQUEST['AmericanExpress'],
										"OnlinePayments"             =>$_REQUEST['OnlinePayments'],
										"CategoryID"				 =>$_REQUEST['category_assign'],
										"BrandingColors1"	  	     =>$_REQUEST['BrandingColors1'],
										"BrandingColors2"	  		 =>$_REQUEST['BrandingColors2'],
										"BrandingColors3"	  		 =>$_REQUEST['BrandingColors3'],
										"CategoryIDB"				 =>$_REQUEST['category_assignb'],
										"CategoryIDC"				 =>$_REQUEST['category_assignc'],
																				

                                        ));
										//echo "save";
									
echo 1;
exit;
}

if($_REQUEST['Task']=='UpdateCitation')
{

$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("CompanyName"					=>$_REQUEST['Bname'],
                        "BestPhone"                  =>$_REQUEST['Bphone'],
                        "AlternatePhone"             =>$_REQUEST['Aphone'],
                        "MobilePhone"                =>$_REQUEST['Mphone'],
                        "Fax"                        =>$_REQUEST['fax'],
                        "PublicEmail"                =>$_REQUEST['Pemail'],
                        "WebsiteUrl"                 =>$_REQUEST['websiteurl'],
                        "Address"                    =>$_REQUEST['Address'],
                        "City"                       =>$_REQUEST['city'],
                        "State"                      =>$_REQUEST['state'],
                        "Zip"                        =>$_REQUEST['Zip'],
                        "Country"                    =>$_REQUEST['country'],
                        "Description"                =>$_REQUEST['Description'],
                        "Keywords"                   =>$_REQUEST['keywords'],
                        "KeywordsNotes"              =>$_REQUEST['Keywordsnotes'],
                        "MainCatagory"               =>$_REQUEST['Mcatagory'],
                        "BusinessListingYearStarted" =>$_REQUEST['startedyear'],
                        "CitiesServed"               =>$_REQUEST['Cserved'],
                        "HoursOperation"             =>$_REQUEST['HOperation'],
                        "DisplayAddress"             =>$_REQUEST['Daddress'],
                        "DisplayHours"               =>$_REQUEST['Dhours'],
                        "ServiceAtLocation"          =>$_REQUEST['Slocation'],
                        "ServiceAreaRadius"          =>$_REQUEST['SAradius'],
                        "LanguageSpoken"             =>$_REQUEST['LSpoken'],
                        "Image1"                     =>$_REQUEST['image1'],
                        "Image2"                     =>$_REQUEST['image2'],
                        "Image3"                     =>$_REQUEST['image3'],
                        "Image4"                     =>$_REQUEST['image4'],
                        "Image5"                     =>$_REQUEST['image5'],
                        "BizListingVideo"            =>$_REQUEST['video1'],
						"Cash"            			 =>$_REQUEST['Citationcash'],
						"Cheque"                     =>$_REQUEST['Citationcheque'],
						"Visa"                       =>$_REQUEST['CitationVisa'],
						"MasterCard"                 =>$_REQUEST['CitationMastercard'],
						"Discoverd"                  =>$_REQUEST['CitationDiscoverd'],
						"DinerClub"                  =>$_REQUEST['Citationdinersclub'],
						"AmericanExpress"            =>$_REQUEST['CitationAmericanExpress'],
                    ));
	echo 1;
        exit;				
	}
	
	if($_REQUEST['Task']=='UpdateGYB')
   {
	$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",array(

                                        "CompanyName"	   			 =>$_REQUEST['GYBBname'],
										"BestPhone"   				 =>$_REQUEST['GYBBphone'],
										"AlternatePhone"             =>$_REQUEST['GYBAphone'],
										"MobilePhone"                =>$_REQUEST['GYBMphone'],
										"Fax"                        =>$_REQUEST['GYBfax'],
										"PublicEmail"                =>$_REQUEST['GYBPemail'],
										"WebsiteUrl"                 =>$_REQUEST['GYBwebsiteurl'],
										"Address"                    =>$_REQUEST['GYBAddress'],
										"City"                       =>$_REQUEST['GYBcity'],
										"State"                      =>$_REQUEST['GYBstate'],
										"Zip"                        =>$_REQUEST['GYBZip'],
										"Country"                    =>$_REQUEST['GYBcountry'],
										"Description"                =>$_REQUEST['GYBDescription'],
										"Keywords"                   =>$_REQUEST['GYBkeywords'],
										"KeywordsNotes"              =>$_REQUEST['GYBKeywordsnotes'],
										"MainCatagory"               =>$_REQUEST['GYBcountry'],
										"BusinessListingYearStarted" =>$_REQUEST['GYBstartedyear'],
										"CitiesServed"               =>$_REQUEST['GYBCserved'],
										"HoursOperation"             =>$_REQUEST['GYBHOperation'],
										"DisplayAddress"             =>$_REQUEST['GYBDaddress'],
										"DisplayHours"               =>$_REQUEST['GYBDhours'],
										"ServiceAtLocation"          =>$_REQUEST['GYBSlocation'],
										"ServiceAreaRadius"          =>$_REQUEST['GYBSAradius'],
										"LanguageSpoken"             =>$_REQUEST['GYBLSpoken'],
										"Image1"                     =>$_REQUEST['GYBimage1'],
										"Image2"                     =>$_REQUEST['GYBimage2'],
										"Image3"                     =>$_REQUEST['GYBimage3'],
										"Image4"                     =>$_REQUEST['GYBimage4'],
										"Image5"                     =>$_REQUEST['GYBimage5'],
										"BizListingVideo"            =>$_REQUEST['GYBvideo1'],
										"Cash"            			 =>$_REQUEST['GYBcash'],
										"Cheque"                     =>$_REQUEST['GYBCheque'],
										"Visa"                       =>$_REQUEST['GYBVisa'],
										"MasterCard"                 =>$_REQUEST['GYBMastercard'],
										"Discoverd"                  =>$_REQUEST['GYBDiscoverd'],
										"DinerClub"                  =>$_REQUEST['GYBdinersclub'],
										"AmericanExpress"            =>$_REQUEST['GYBAmericanExpress'],

                                           ));	
        echo 1;
        exit;
	}							   
										   		
echo 0;
exit;
?>