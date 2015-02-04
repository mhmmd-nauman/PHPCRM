<?php 
include dirname(__FILE__)."/../lib/include.php"; 
//$objtasks = new Tasks();
$objClient = new Clients();
$utilObj = new util();
if($_REQUEST['Task']=='Bname'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("CompanyName"			=>$_REQUEST['Bname'],																                            
                        ));
echo 1;
exit;}
if($_REQUEST['Task']=='BizType'){
$objClient->UpdateClients("Clients.ID='".$_REQUEST['id']."'",
                array("BusinessType"			=>$_REQUEST['BizType'],																                            
                        ));
echo 1;
exit;}
if($_REQUEST['Task']=='Pemail'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("PublicEmail"                =>$_REQUEST['Pemail'],));
echo 1;
exit;}
if($_REQUEST['Task']=='Mphone'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("MobilePhone"                =>$_REQUEST['Mphone'],));
echo 1;
exit;}
if($_REQUEST['Task']=='Bphone'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("BestPhone"                  =>$_REQUEST['Bphone'],));
echo 1;
exit;}
if($_REQUEST['Task']=='Aphone'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("AlternatePhone"             =>$_REQUEST['Aphone'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='Address'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Address"                    =>$_REQUEST['Address'],));
echo 1;
exit;}
if($_REQUEST['Task']=='CitationCity'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("City"                       =>$_REQUEST['city'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='CitationState'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("State"                      =>$_REQUEST['state'],));
echo 1;
exit;}			
if($_REQUEST['Task']=='CitationZip'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Zip"                        =>$_REQUEST['Zip'],));
echo 1;
exit;}		   
if($_REQUEST['Task']=='fax'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Fax"                        =>$_REQUEST['fax'],));
echo 1;
exit;}
if($_REQUEST['Task']=='websiteurl'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("WebsiteUrl"                 =>$_REQUEST['websiteurl'],));
echo 1;
exit;}
if($_REQUEST['Task']=='country'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Country"                    =>$_REQUEST['country'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='Description'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Description"                =>$_REQUEST['Description'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='keywords'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Keywords"                   =>$_REQUEST['keywords'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='Keywordsnotes'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("KeywordsNotes"              =>$_REQUEST['Keywordsnotes'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='Mcatagory'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("MainCatagory"               =>$_REQUEST['Mcatagory'],));
echo 1;
exit;}	
if($_REQUEST['Task']=='startedyear'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("BusinessListingYearStarted" =>date("Y-m-d",strtotime($_REQUEST['startedyear'])),));
echo 1;
exit;}
if($_REQUEST['Task']=='Cserved'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("CitiesServed"               =>$_REQUEST['Cserved'],));
echo 1;
exit;}
if($_REQUEST['Task']=='HOperation'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("HoursOperation"             =>$_REQUEST['HOperation'],));
echo 1;
exit;}
if($_REQUEST['Task']=='Daddress'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("DisplayAddress"             =>$_REQUEST['Daddress'],));
echo 1;
exit;}
if($_REQUEST['Task']=='Dhours'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("DisplayHours" =>$_REQUEST['Dhours'],));
echo 1;
exit;}
if($_REQUEST['Task']=='Slocation'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("ServiceAtLocation"          =>$_REQUEST['Slocation'],));
echo 1;
exit;}
if($_REQUEST['Task']=='SAradius'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("ServiceAreaRadius"          =>$_REQUEST['SAradius'],));
echo 1;
exit;}
if($_REQUEST['Task']=='LSpoken'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("LanguageSpoken"             =>$_REQUEST['LSpoken'],));
echo 1;
exit;}
if($_REQUEST['Task']=='image1'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Image1"                     =>$_REQUEST['image1'],));
echo 1;
exit;}
if($_REQUEST['Task']=='image2'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Image2"                     =>$_REQUEST['image2'],));
echo 1;
exit;}
if($_REQUEST['Task']=='image3'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array( "Image3"                     =>$_REQUEST['image3'],));
echo 1;
exit;}
if($_REQUEST['Task']=='image4'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Image4"                     =>$_REQUEST['image4'],));
echo 1;
exit;}
if($_REQUEST['Task']=='image5'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Image5"                     =>$_REQUEST['image5'],));
echo 1;
exit;}
if($_REQUEST['Task']=='video1'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("BizListingVideo"            =>$_REQUEST['video1'],));
echo 1;
exit;}
if($_REQUEST['Task']=='Citationcash'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Cash"            			 =>$_REQUEST['Citationcash'],));
echo 1;
exit;}
if($_REQUEST['Task']=='Citationcheque'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Cheque"                     =>$_REQUEST['Citationcheque'],));
echo 1;
exit;}
if($_REQUEST['Task']=='CitationAmericanExpress'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("AmericanExpress"            =>$_REQUEST['CitationAmericanExpress'],));
echo 1;
exit;}
if($_REQUEST['Task']=='Citationdinersclub'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("DinerClub"                  =>$_REQUEST['Citationdinersclub'],));
echo 1;
exit;}
if($_REQUEST['Task']=='CitationMastercard'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("MasterCard"                 =>$_REQUEST['CitationMastercard'],));
echo 1;
exit;}
if($_REQUEST['Task']=='CitationVisa'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Visa"                       =>$_REQUEST['CitationVisa'],));
echo 1;
exit;}
if($_REQUEST['Task']=='CitationDiscoverd'){
$objClient->UpdateClientCitationData("MemberID='".$_REQUEST['id']."'",
                array("Discoverd"                  =>$_REQUEST['CitationDiscoverd'],));
echo 1;
exit;}									   		
echo 0;
exit;
?>