<?php
extract($_REQUEST);
//$objpackges = new Packges();

//print_r($_SESSION);
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


$cash_method=$_REQUEST['PaymentAccepted'];

	foreach((array)$cash_method as $cash){
		
		}
$checked_value=$_REQUEST['MemberGroup'];
$Done=$_REQUEST['Done'];

foreach((array)$checked_value as $value){
	//print_r( $value);
}
 $group_array = $ObjGroup->GetAllGroups(" ID ='$mid' ORDER BY Sort",array("*"));
if($_REQUEST['Task']=='add'){

        if(empty($_REQUEST['Ptitle']))$_REQUEST['Ptitle']="New Package Title";
        if(empty($_REQUEST['Pdescription']))$_REQUEST['Pdescription']= "New Package Description Here ";
       
      // echo $_REQUEST['WelCallComDate'];
        if(!empty($_REQUEST['createddate'])){
            $createddate= date("Y-m-d",strtotime($_REQUEST['createddate']));
			
        }else{
            $createddate=date("Y-m-d h:i:s",time());
			 
            
        }
        //echo $_REQUEST['DesignUpdateCall'];
     
     
     
       
  
		
	
		
	
                                          $added= $objpackges->InsertPackge(array(
										  										"Created"=>date("Y-m-d h:i:s",strtotime($_REQUEST['createddate'])),                                                               "PackagesTitle"      =>$_REQUEST['Ptitle'],
                                                                                "PackgesDescription"	       =>$_REQUEST['Pdescription']
                                                                                
                                                          ));
 header("location:Packges.php?flag=add"); 
                        


}else{
    header("location:Packges.php?flag=error"); 
}
    
    
}
if($_REQUEST['Task']=='Update')
{

$packgeid = $_REQUEST['id'];


		
    
	        $updated= $objpackges->UpdatePackge("ID = '$packgeid' ",array(
																		"Created"=>date("Y-m-d h:i:s",strtotime($_REQUEST['createddate'])),                                                                        "PackagesTitle"      =>$_REQUEST['Ptitle'],
                                                                         "PackgesDescription"	       =>$_REQUEST['Pdescription']
                                                                             
                                                                               
                                                          ));
 					 
                                         
        header("Location:Packges.php?id=".$_REQUEST['id']."&flag=update");                                
 }
 
 
if($_REQUEST['Task']=='del'){
///echo "kkkkk";
	$objpackges->DeletePackge($_REQUEST['id']);
	//exit;
 header("Location:Packges.php?flag=del");    	
}


?>
