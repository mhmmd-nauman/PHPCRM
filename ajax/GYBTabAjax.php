<?php 
include dirname(__FILE__)."/../lib/include.php"; 
//$objtasks = new Tasks();
$objClient = new Clients();
$utilObj = new util();
if($_REQUEST['Task']=='GYB_BusinessName'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array(
						"GYB_BusinessName"	   		 =>$_REQUEST['GYB_BusinessName'],																                               ));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_AddressLine1'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_AddressLine1"   		 =>$_REQUEST['GYB_AddressLine1'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_AddressLine2'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_AddressLine2"           =>$_REQUEST['GYB_AddressLine2'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_City'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_City"              	 =>$_REQUEST['GYB_City'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_State'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_State"                  =>$_REQUEST['GYB_State'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='GYB_ZipCode'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_ZipCode"                =>$_REQUEST['GYB_ZipCode'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_MainBusinessPhone'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_MainBusinessPhone"      =>$_REQUEST['GYB_MainBusinessPhone'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='GYB_TollFreeNumber'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_TollFreeNumber"         =>$_REQUEST['GYB_TollFreeNumber'],));
echo 1;
exit;}			
if($_REQUEST['Task']=='GYB_FaxNumber'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_FaxNumber"              =>$_REQUEST['GYB_FaxNumber'],));
echo 1;
exit;}		   
if($_REQUEST['Task']=='GYB_AlternatePhone'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_AlternatePhone"         =>$_REQUEST['GYB_AlternatePhone'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_MobilePhone'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_MobilePhone"            =>$_REQUEST['GYB_MobilePhone'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_BusinessEmailAddress'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_BusinessEmailAddress"   =>$_REQUEST['GYB_BusinessEmailAddress'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='GYB_BusinessWebSiteURL'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_BusinessWebSiteURL"     =>$_REQUEST['GYB_BusinessWebSiteURL'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='GYB_BusinessDescription'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_BusinessDescription"    =>$_REQUEST['GYB_BusinessDescription'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='GYB_FacebookURL'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_FacebookURL"            =>$_REQUEST['GYB_FacebookURL'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='GYB_TwitterURL'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_TwitterURL"             =>$_REQUEST['GYB_TwitterURL'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='GYB_LogoURL'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_LogoURL"				 =>$_REQUEST['GYB_LogoURL'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_PhotoURL1'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_PhotoURL1"              =>$_REQUEST['GYB_PhotoURL1'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_PhotoURL2'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_PhotoURL2"              =>$_REQUEST['GYB_PhotoURL2'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_PhotoURL3'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_PhotoURL3"              =>$_REQUEST['GYB_PhotoURL3'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_PhotoURL4'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_PhotoURL4"              =>$_REQUEST['GYB_PhotoURL4'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_VideoURL'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_VideoURL"               =>$_REQUEST['GYB_VideoURL'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_HoursOfOperations'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_HoursOfOperations"          =>$_REQUEST['GYB_HoursOfOperations'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_PaymentTypesAccepted'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_PaymentTypesAccepted"             =>$_REQUEST['GYB_PaymentTypesAccepted'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_YearFounded'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_YearFounded"           =>$_REQUEST['GYB_YearFounded'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_Products'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_Products"                     =>$_REQUEST['GYB_Products'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_Services'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array( "GYB_Services"                     =>$_REQUEST['GYB_Services'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_Brands'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_Brands"                     =>$_REQUEST['GYB_Brands'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_SpecialtiesKeywords'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_SpecialtiesKeywords"                     =>$_REQUEST['GYB_SpecialtiesKeywords'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_ProfessionalAssociations'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_ProfessionalAssociations"            =>$_REQUEST['GYB_ProfessionalAssociations'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_BusinessLanguages'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_BusinessLanguages"            			 =>$_REQUEST['GYB_BusinessLanguages'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_PrimaryContactName'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_PrimaryContactName"                     =>$_REQUEST['GYB_PrimaryContactName'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_PrimaryContactEmail'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_PrimaryContactEmail"            =>$_REQUEST['GYB_PrimaryContactEmail'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_AdditionalNotes'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_AdditionalNotes"                  =>$_REQUEST['GYB_AdditionalNotes'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_AdditionalNotes'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_AdditionalNotes"                 =>$_REQUEST['GYB_AdditionalNotes'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_FulfillmentRep'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_FulfillmentRep"                       =>$_REQUEST['GYB_FulfillmentRep'],));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_DateEntered'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_DateEntered"                  =>date("Y-m-d ",strtotime($_REQUEST['GYB_DateEntered'])),));
echo 1;
exit;}
if($_REQUEST['Task']=='GYB_TypesOfMarketing'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_TypesOfMarketing"                  =>$_REQUEST['GYB_TypesOfMarketing'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='GYB_FileAttachments'){

if(!empty($_REQUEST['GYB_FileAttachments'])){
echo $_REQUEST['GYB_FileAttachments'];
            $profile_image = "user_pics/";
           $profile_image = $profile_image . basename( $_REQUEST['GYB_FileAttachments']); 
            move_uploaded_file($_FILES['GYB_FileAttachments']['tmp_name'], $profile_image);
    } else{

            $Members_array = $objClient->GetAllClients("Clients.ID = '".$_REQUEST['id']."'",array("*"));

            $profile_image = $Members_array[0]['member_profile_image'];
    }

echo profile_image;
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_FileAttachments"                  =>$profile_image,));
echo 1;
exit;}	
if($_REQUEST['Task']=='GYB_GrossRevenue'){
$objClient->UpdateClientGYBData("MemberID='".$_REQUEST['id']."'",
                array("GYB_GrossRevenue"                  =>$_REQUEST['GYB_GrossRevenue'],));
echo 1;
exit;}										   		
echo 0;
exit;
?>