<?php $objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
//print_r($SystemSettingsArray);

 //$oldimage=$SystemSettingsArray[0]['Path'];
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
$sortBy = $_SESSION['sortBy'];
$sortValue = $_SESSION['sortValue'];
if(empty($sortBy)) $sortBy = "Date";
if(empty($sortValue))$sortValue = "DESC";
switch($sortBy){
  case "Name":
  	switch($sortValue){
	
	case "ASC":
	
	$sortText = "ORDER BY FullName ASC";
	$sortName = "DESC";
	break;
	case "DESC":
	
	$sortText = "ORDER BY FullName DESC";
	$sortName = "ASC";
	break;
	}
  break;
  case "Domain":
  	switch($sortValue){
	
	case "ASC":
	
	$sortText = "ORDER BY DomainName ASC";
	$sortName = "DESC";
	break;
	case "DESC":
	
	$sortText = "ORDER BY DomainName DESC";
	$sortName = "ASC";
	break;
	}
  break;
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
  case "DateSent":
  	switch($sortValue){
	
	case "ASC":
	
	$sortText = "ORDER BY SaleDate ASC";
	$sortName = "DESC";
	break;
	case "DESC":
	
	$sortText = "ORDER BY SaleDate DESC";
	$sortName = "ASC";
	break;
	}
  break;
}

if($_REQUEST['Task']=='add'){

   if($_FILES['ProfileImg']['size'] > 0){
            $profile_image = "user_pics/";
            $profile_image = $profile_image . basename( $_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], $profile_image);
        }
    $objSystemSettings->InsertSystemSetting(array(
					   "DateCreated"=>date("Y-m-d h:i:s"),
					   "Path"=>$profile_image,
					   "Title"=>$_REQUEST['Title'],
					   "HasDeleted"=>0,
					   "Email"=>$_REQUEST['Email'],
					   "EmailTo"=>$_REQUEST['EmailTo'],
					   
																						
					));

 	header("Location:SystemSetting.php?flag=setting_add");
    exit;
					
}
	
	


if($_REQUEST['Task']=='Update' ){

$pid = $_REQUEST['id'];
if($_FILES['file']['size'] > 0){
	$profile_image = "../user_pics/";
	$profile_image = $profile_image . basename( $_FILES['file']['name']); 
	move_uploaded_file($_FILES['file']['tmp_name'], $profile_image);
}
if(empty($profile_image)){
$profile_image = $SystemSettingsArray[0]['Path'];
}

$updated= $objSystemSettings->UpdateSystemSetting("ID = '$pid' ",array(
										   "DateCreated"=>date("Y-m-d h:i:s"),
					  						//"PeofileImg"=>"upload/" . $_FILES["PeofileImg"]["name"],
					  						"HasDeleted"=>0,
	                                        "Title"=>$_REQUEST['Title'],
											"Email"=>$_REQUEST['Email'],
											"EmailTo"=>$_REQUEST['EmailTo'],
											"Path"=>$profile_image,
											//"Path"=>"upload/" . $_FILES["file"]["name"],
											"HeaderColor"=>$_REQUEST['HeaderColor'],
											"PopupColor"=>$_REQUEST['PopupColor'],
											
                                                          ));
  
   	

 
header("Location:SystemSetting.php?flag=updat_setting");
    exit;
} 
 /*end update hubopus */ 
if($_REQUEST['Task']=='del'){
//echo "kkkkk";
    $objSystemSettings->UpdateSystemSetting("ID = '".$_REQUEST['id']."'",array("HasDeleted"=>"1"));
}



?>
