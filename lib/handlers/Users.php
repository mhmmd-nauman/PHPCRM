<?php
extract($_REQUEST);
$objusers = new Users();
$Users_array = $objusers->GetAllUsers(USERS.".ID = '".$_REQUEST['id']."'",array(USERS.".*")); 
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

	
$checked_value=$_REQUEST['MemberGroup'];

if($_REQUEST['Task'] == 'add'){
	if($_FILES['ProfileImg']['size'] > 0){
		$profile_image = "../user_pics/";
		$profile_image = $profile_image . basename( $_FILES['ProfileImg']['name']);
		move_uploaded_file($_FILES['ProfileImg']['tmp_name'], $profile_image);
	}
	
	if(empty($_REQUEST['fName']))
		$_REQUEST['fName'] = "New Member Name";
	if(empty($_REQUEST['Email']))
		$_REQUEST['Email'] = time()."@email.com";
	if(empty($_REQUEST['password'])){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$_REQUEST['password'] = substr(str_shuffle($chars),0,8);
	}
	
	if(!empty($_REQUEST['createddate'])){
		$createddate = date("Y-m-d",strtotime($_REQUEST['createddate']));
	}else{
		$createddate = date("Y-m-d h:i:s",time());            
	}
	
	$Members_array = $objusers->GetAllUsers(USERS.".HasDeleted = 0",array(USERS.".*"));
	foreach($Members_array as $Member_email){
		if($Member_email['Email'] == $_REQUEST['Email']){
		}
	}
		
	$Users_array = $objusers->GetAllUsers("Email= '".$_REQUEST['Email']."'",array(USERS.".ID","Email"));
	if($Users_array[0]['Email']!= $_REQUEST['Email']){
	$added = $objusers->InsertUsers(array(
		"Created"	   				=> $createddate,
		"FirstName"    				=> $_REQUEST['fName'],
		"LastName"      			=> $_REQUEST['sureName'],
		"Email"	       				=> $_REQUEST['Email'],
		"LastLogin"     			=> date("Y-m-d h:i:s",time()),
		"Phone"        				=> $_REQUEST['phone'],
		"Password"	   				=> $_REQUEST['password'],
		"AlternatePhone"	   		=> $_REQUEST['alternatephone'],
		"SkypeName"	   				=> $_REQUEST['skypename'],
		"Address"	       			=> $_REQUEST['address'],
		"City"	       				=> $_REQUEST['city'],
		"ZipCode"	       			=> $_REQUEST['ZipCode'],
		"State"	       				=> $_REQUEST['state'],
		"Address2"	   				=> $_REQUEST['Address2'],
		"ProfileImage" 				=> $profile_image,
		"CompanyID"    				=> $_REQUEST['Company'],
		"ZoneID"	       			=> $_REQUEST['timezone'],
		"Phone_Ext"	       			=> $_REQUEST['extension'],			
	));

	foreach((array)$checked_value as $value){
		$insertGroup = $objusers->InsertUsersGroup(array(
			"UserID"				=>$added,
			"GroupID"				=>$value
		));
	}
	header("location:Users.php?flag=add"); 
}else{
    header("location:Users.php?flag=error"); 
}
    
    
}
if($_REQUEST['Task'] == 'Update'){
	$userid = $_REQUEST['id'];
	if($_FILES['ProfileImg']['size'] > 0){
		$profile_image = "../user_pics/";
		$profile_image = $profile_image . basename( $_FILES['ProfileImg']['name']); 
		move_uploaded_file($_FILES['ProfileImg']['tmp_name'], $profile_image);
	} else{
		$profile_image = $Users_array[0]['ProfileImage'];
	}
		
	$objusers->UpdateUsers("ID = '$userid' ",array(
		"Created"				=> date("Y-m-d h:i:s",strtotime($_REQUEST['createddate'])),																        "FirstName"    			=> $_REQUEST['fName'],
		"LastName"      		=> $_REQUEST['sureName'],
		"Email"	       			=> $_REQUEST['Email'],
		"Phone"        			=> $_REQUEST['phone'],
		"Password"	   			=> $_REQUEST['password'],
		"AlternatePhone"	   	=> $_REQUEST['alternatephone'],
		"SkypeName"	   			=> $_REQUEST['skypename'],
		"Address"	       		=> $_REQUEST['address'],
		"City"	       			=> $_REQUEST['city'],
		"ZipCode"	       		=> $_REQUEST['ZipCode'],
		"State"	       			=> $_REQUEST['state'],
		"Address2"	       		=> $_REQUEST['Address2'],
		"CompanyID"    			=> $_REQUEST['Company'],
		"ProfileImage"	       	=> $profile_image,
		"ZoneID"	       		=> $_REQUEST['timezone'],
		"Phone_Ext"	       		=> $_REQUEST['extension'],										  
	));
	$objusers->DeleteUserGroup($_REQUEST['id']);
	foreach((array)$checked_value as $value){
		$insertGroup = $objusers->InsertUsersGroup(array(
			"UserID"				=> $_REQUEST['id'],
			"GroupID"				=> $value
		));
	}				 
	header("Location:Users.php?id=".$_REQUEST['id']."&flag=update");                                
}


 if($_REQUEST['updateProfile'] =='updateProfile'){
   $userid = $_SESSION['Member']['ID'];

if($_FILES['ProfileImg']['size'] > 0){
	$profile_image = "../user_pics/";
	$profile_image = $profile_image . basename( $_FILES['ProfileImg']['name']); 
	move_uploaded_file($_FILES['ProfileImg']['tmp_name'], $profile_image);
	
} else{
    $profile_image = $Users_array[0]['ProfileImage'];
}

		
    
 $objusers->UpdateUsers("ID = '$userid' ",array(
                    "Created"=>date("Y-m-d h:i:s",strtotime($_REQUEST['createddate'])),																                                            									"FirstName"    =>$_REQUEST['fName'],
                    "LastName"      =>$_REQUEST['sureName'],
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
                    "CompanyID"    => $_REQUEST['Company'],
                    "ProfileImage"	       =>$profile_image,
                    "ZoneID"	       =>$_REQUEST['timezone']
                                                                  
        )); 
 header("Location:Users.php?id=".$_SESSION['Member']['ID']."&flag=update"); 
 }
 
if($_REQUEST['Task']=='del'){
///echo "kkkkk";
	$objusers->DeleteUsers($_REQUEST['id']);
	//exit;
 header("Location:Users.php?flag=del");    	
}
     if($_REQUEST['Task'] == 'usersignup'){

         $Users_array = $objusers->GetAllUsers("Email= '".$_REQUEST['UserEmail']."'",array(USERS.".ID","Email"));
        if($Users_array[0]['Email']!= $_REQUEST['UserEmail']){
         
         
         
                                          $added= $objusers->InsertUsers(array(
                                                                                "Created"	   =>date("Y-m-d h:i:s",time()),
                                                                                "FirstName"    =>$_REQUEST['UserFName'],
                                                                                "LastName"      =>$_REQUEST['UserSureName'],
                                                                                "Email"	       =>$_REQUEST['UserEmail'],
                                                                                "LastLogin"     =>date("Y-m-d h:i:s",time()),
                                                                               
                                                                                "Password"	   =>$_REQUEST['Userpassword'],
                                                                             	
                                                                            	"CompanyID"    => $_REQUEST['UserCompany'],
                                                                                "ZoneID"  =>$_REQUEST['ZoneTime']
                                                                           
                                                          ));
                                          if($_REQUEST['UserCompany']==2){
                                          $insertGroup= $objusers->InsertUsersGroup(array(
                                                                                "UserID"=>$added,
                                                                                "GroupID"=>16
                                                           ));
         
                                              }else{
                                                  
                                               $insertGroup= $objusers->InsertUsersGroup(array(
                                                                                "UserID"=>$added,
                                                                                "GroupID"=>21
                                                           ));   
                                              }							  
	
 
                        
header("location:SignUp.php?flag=usersignup"); 

}else{
    header("location:SignUp.php?flag=email_exist"); 
}
}

?>
