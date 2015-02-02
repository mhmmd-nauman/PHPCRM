<?php
$ObjGroup = new Groups();

 if(empty($_REQUEST['salepersonflag'])){
     $flag=$_REQUEST['salepersonflag']=0;
 }else{
     $flag=$_REQUEST['salepersonflag'];
 }
//print_r($salepersonflag);
if($_REQUEST['Task']=='Add'){
    $code = strtolower($_REQUEST['Title']);
    $grpcode = str_replace(' ', '-', $code);
    $added= $ObjGroup->InsertGroup(array(
                                           "ClassName"=>'Group',
				          				   "Created"=>date("Y-m-d H:i:s"),
                                           "LastEdited"=>date("Y-m-d H:i:s"),
                                           "Title"=>$code,
				           				   "Description"=>$_REQUEST['Description'],
                                           "IsGroupSale"=>$flag,
                                           "Code"=>$grpcode,
                                           "SiteType"=>$_REQUEST['SiteType'],
                                           "Membership"=>$_REQUEST['membership_status'],
										   "CompanyAttached"=>$_REQUEST['CompanyAttached']
                                           ));
										  
   header("Location:Groups.php?flag=add_Group");
}

/*
 $code = strtolower($_REQUEST['Title']);
 $grpcode = str_replace(' ', '-', $code);

 echo $group_query=mysql_query("INSERT INTO `Group` (ID,ClassName,Created,LastEdited,Title,Description,Code,SiteType,Membership,ProgramID) VALUES('','Group','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','".$_REQUEST['Title']."','".$_REQUEST['Description']."','".$grpcode."','".$_REQUEST['SiteType']."','".$_REQUEST['membership_status']."','".$_REQUEST['Program']."')");
*/
if($_REQUEST['Task']=='Update'){
//echo"ssss";
      $gid = $_REQUEST['id'];
     $_REQUEST['Program']."*";
 $updated= $ObjGroup->UpdateGroup("ID = '$gid' ",array(
                                               "Title"=>$_REQUEST['Title'],
												"Description"=>$_REQUEST['Description'],
                                                "IsGroupSale"=>$flag,
												"CompanyAttached"=>$_REQUEST['CompanyAttached'],
                                           )); 

	     header("Location:Groups.php?flag=update");		
}				
if($_REQUEST['Task']=='del'){
    $ObjGroup->DeleteGroup($_REQUEST['id']);
}
?>