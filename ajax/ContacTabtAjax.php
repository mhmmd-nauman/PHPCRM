<?php 
include dirname(__FILE__)."/../lib/include.php"; 
//$objtasks = new Tasks();
$objClient = new Clients();
$utilObj = new util();
if($_REQUEST['Savebtn'] == 111){
$arrValue = array(
	'CategoryName' => $_REQUEST['AddNewCategory'],
	"Created"	  => date("Y-m-d h:i:s",time()),
);
$insertedId = $utilObj->insertRecord('BusinessCategory', $arrValue);
$_SESSION['tab_activated'] = 2;
}

$mid = $_REQUEST['id'];

if($_REQUEST['Task'] == 'Updatetask_assign'){
	$objClient->UpdateClients("Clients.ID = '$mid' ",array(
		"TaskID"	=> $_REQUEST['task_assign'],                    
	));
	echo 1;
	exit;
}


if($_REQUEST['Task']=='Updatecreateddate'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"Created"=>date("Y-m-d h:i:s",strtotime($_REQUEST['createddate'])),																                            
                        ));
echo 1;
exit;
}

if($_REQUEST['Task'] == 'Updatecname'){
	$update = $objClient->UpdateClients(
		"Clients.ID = '$mid' ",array(
		"CompanyName" => addslashes($_REQUEST['cname']),																                            
	));
	echo $ret = (!empty($update) and $update == 1) ? 1 : 0;
	exit;
}


if($_REQUEST['Task']=='UpdatefName'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"FirstName"=>$_REQUEST['fName'],																                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='UpdatesureName'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"Surname"=>$_REQUEST['sureName'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='UpdateEmail'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"Email"=>$_REQUEST['Email'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='Updatephone'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"Phone"=>$_REQUEST['phone'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='UpdateBusinessType'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"BusinessType"=>$_REQUEST['BusinessType'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='UpdateBBB'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"BBB"=>$_REQUEST['bbb'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='UpdateNFIB'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"NFIB"=>$_REQUEST['nfib'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='UpdateAccountAddress'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"AccountAddress"=>$_REQUEST['AccountAddress'],															                      							             ));
echo 1;
exit;}

if($_REQUEST['Task'] == 'Updatealternatephone'){

$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"AlternatePhone"=>$_REQUEST['alternatephone'],															                      							             ));
echo 1;
exit;}


if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "UpdateMobilePhone" and !empty($_REQUEST['Task'])){
	extract($_REQUEST);
	$update = $objClient->UpdateClients("Clients.ID = '$ClientID' ",array(
		"MobilePhone" => $Newvalue,
	));
	
	$update_or = $objClient->UpdateOrderItem("MemberID = '$ClientID' ",array(
		"MobilePhone" => $Newvalue,
	));
	
	$echo = ($update == 1 and $update_or == 1) ? 1 : 0;
	echo $echo;
	exit;
}

if($_REQUEST['Task']=='Updateskypename'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"SkypeName"=>$_REQUEST['skypename'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='Updateaddress'){

$objClient->UpdateClients("Clients.ID = '$mid' ",array(
						"Address"=>$_REQUEST['address'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='Updatecity'){

$objClient->UpdateClients("Clients.ID = '$mid' ",array(
						"City"=>$_REQUEST['city'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='Updatestate'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
						"State"=>$_REQUEST['state'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='UpdateZipCode'){

$objClient->UpdateClients("Clients.ID = '$mid' ",array(
						"ZipCode"=>$_REQUEST['ZipCode'],															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='UpdateOthersNotes'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
						"Notes"=>$_REQUEST['OthersNotes'],															                      							             ));
echo 1;
exit;}

# Below Code is used to save the submitted by drop down on the clients page.
# Contact tab. This is only allowed by the Admin if the session "isadmin" is
# equal to 1 then the drop down will be enabled and the user can then save a 
# different submitted by if they want.
if($_REQUEST['Task'] == 'Updateselect'){
	$mid = $_REQUEST['id'];
	if(!empty($_REQUEST['SubmitedBy'])){
		$SubmitedBy = $_REQUEST['SubmitedBy'];
	}else{
		$SubmitedBy = $_SESSION['Member']['ID']; 
	}
	
	
	$update = $objClient->UpdateClients("Clients.ID = '$mid' ",array("SubmitedBy" => $SubmitedBy));
	$updateOrderItem = $objClient->UpdateOrderItem("MemberID = '$mid' ",array(
		"UserID" => $SubmitedBy,
	));
	
	if($update == 1 and $updateOrderItem == 1){
		echo "Success";
	}else{
		echo "Error";
	}
	exit;
}


if($_REQUEST['Task']=='UpdateBestCallTime'){

 if(!empty($_REQUEST['BestCallTime'])){
            $BestCallTime = date("Y-m-d ",strtotime($_REQUEST['BestCallTime'])).$_REQUEST['hour'].":".$_REQUEST['minuts'].":00";
        }else{
            $BestCallTime=""; 
        }

$objClient->UpdateClients("Clients.ID = '$mid' ",array("BestCallTime"=>$BestCallTime));
echo 1;
exit;}
if($_REQUEST['Task']=='Updatetimebilling'){

if(!empty($_REQUEST['timebilling'])){
        $timebilling = date("Y-m-d",strtotime($_REQUEST['timebilling']));
        }else{
            $timebilling=""; 
        }
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
						"TimeBilling"=>$timebilling,															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='UpdateProfileImg'){

//echo $_REQUEST['ProfileImg'];
if(!empty($_REQUEST['ProfileImg'])){

            $profile_image = "user_pics/";
           $profile_image = $profile_image . basename( $_REQUEST['ProfileImg']); 
            move_uploaded_file($_FILES['ProfileImg']['tmp_name'], $profile_image);
    } else{

            $Members_array = $objClient->GetAllClients("Clients.ID = '".$_REQUEST['id']."'",array("*"));

            $profile_image = $Members_array[0]['member_profile_image'];
    }

$objClient->UpdateClients("Clients.ID = '$mid' ",array(
						"member_profile_image"=>$profile_image,															                      							             ));
echo 1;
exit;}
if($_REQUEST['Task']=='Updatetimezone'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"CustomersTimeZone"=>$_REQUEST['timezone'],															                            
                        ));
echo 1;
exit;}	
if($_REQUEST['Task']=='UpdateEmail1'){
$objClient->UpdateClients("Clients.ID = '$mid' ",array(
							"Email"=>$_REQUEST['Email'],															                      							             ));
echo 1;
exit;}					   
										   		
echo 0;
exit;
?>