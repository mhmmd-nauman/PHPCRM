<?php $objorder = new Orders();
if($_REQUEST['Task']=='Update')
{

$vendorid = $_REQUEST['id'];

//print_r($group_array);
//extract($_POST);
if($_FILES['ProfileImg']['size'] > 0){
	$profile_image = "user_pics/";
	$profile_image = $profile_image . basename( $_FILES['ProfileImg']['name']); 
	move_uploaded_file($_FILES['ProfileImg']['tmp_name'], $profile_image);

} else{
	
	//print_r($Members_array);
    $profile_image = $Vendors_array[0]['ProfileImage'];
}

		
    
	        $updated= $objvendor->UpdateVendors("ID = '$vendorid' ",array(
																				"Created"=>date("Y-m-d h:i:s",strtotime($_REQUEST['createddate'])),																                                            									"FirstName"    =>$_REQUEST['fName'],
                                                                                "Surname"      =>$_REQUEST['sureName'],
                                                                                "Email"	       =>$_REQUEST['Email'],
                                                                               
                                                                                "Phone"        =>$_REQUEST['phone'],
                                                                                "Password"	   =>$_REQUEST['password'],
                                                                                "AlternatePhone"	   =>$_REQUEST['alternatephone'],
                                                                                "SkypeName"	   =>$_REQUEST['skypename'],
                                                                                "Address"	       =>$_REQUEST['address'],
                                                                                "City"	       =>$_REQUEST['city'],
																				"ZipCode"	       =>$_REQUEST['ZipCode'],
																				"State"	       =>$_REQUEST['state'],
																				"Address2"	       =>$_REQUEST['Address2'],
																				"ProfileImage"	       =>$profile_image
                                                          ));
 					 
                                         
        header("Location:Vendors.php?id=".$_REQUEST['id']."&flag=update");                                
 }
 
 
if($_REQUEST['Task']=='del'){
echo "kkkkk";
	$objorder->DeleteOrder($_REQUEST['id']);
	//exit;
 header("Location:OrderList.php.php?flag=del");    	
}
?>
