<?php
$objWebSiteServer = new WebSiteServer();
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
    
    $IP= $_REQUEST['IP'];
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    
    $objWebSiteServer->InsertWebSiteServer(array(
                                "created"=>date("Y-m-d h:i:s"),
                                "name"=>$_REQUEST['name'],
                                "ip"=>$IP,
                                "username"=>$username,
                                "password"=>$password,
                                "isreseller"=>$_REQUEST['isreseller'],
                                "isDefaultReseller"=>$_REQUEST['isDefaultReseller'],
                                "internal_ip"=>$_REQUEST['internal_ip'],
                                
                            ));
header("Location:WebSiteServer.php?flag=add_server");
exit;
}

if($_REQUEST['Task']=='Update' ){
    $pid = $_REQUEST['id'];
    $updated= $objWebSiteServer->UpdateWebSiteServer("id = '$pid' ",
                                        array(
                                            "ip"=>$IP,
                                            "name"=>$_REQUEST['name'],
                                            "username"=>$username,
                                            "password"=>$password,
                                            "isreseller"=>$_REQUEST['isreseller'],
                                            "isDefaultReseller"=>$_REQUEST['isDefaultReseller'],
                                            "internal_ip"=>$_REQUEST['internal_ip'],
                                            ));
    	
	header("Location:WebSiteServer.php?id=".$_REQUEST['id']."&flag=update");
	exit;												 
}
  
   	
 
if($_REQUEST['Task']=='del'){
    $objWebSiteServer->UpdateWebSiteServer("id = '".$_REQUEST['id']."'",array("HasDeleted"=>"1"));
    header("Location:WebSiteServer.php?id=".$_REQUEST['id']."&flag=update");
    exit;
}


?>