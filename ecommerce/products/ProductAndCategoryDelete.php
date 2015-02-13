<?php 
require_once "../../lib/include.php";
$utilObj = new util();

if($_REQUEST['Task']=='Delete'){
   $strCriteria='ID='.$_REQUEST['id'].'';
 //print_r($_POST);
 //exit;
 $DeleteRec=$utilObj->deleteRecord('Product', $strCriteria);
 header("Location:".SITE_ADDRESS."ecommerce/products/ProductAndSubscriptionList.php?flag=del");
      if($_REQUEST['PageName']=='Product'){
	   $DeleteRec=$utilObj->deleteRecord('Product', $strCriteria);
	   	$Subwher='ProductID='.$_REQUEST['id'].'';
	    $SubscriptionRecCount=$utilObj->getCount('ProductSubscription',$Subwher);
        if($SubscriptionRecCount['total'] > 0){
	      $DeleteRec=$utilObj->deleteRecord('ProductSubscription', $Subwher);
		}
		
	  }else{
      $DeleteRec=$utilObj->deleteRecord('ProductCategory', $strCriteria);
	  }
	  
      if($DeleteRec){
	  echo $_SESSION['flag']='Delete';
	  }
	  exit;
}
/*
if($_REQUEST['adminpassword']){
if(is_array($_SESSION['M_Group_ID']) && in_array("1",$_SESSION['M_Group_ID'])){
$strwhere='ID = '.$_SESSION['Member']['ID'].'';
$MemberRows=$utilObj->getSingleRow('Member',$strwhere);
if($MemberRows['Password']==$_REQUEST['adminpassword'])
  echo "yes";
  else
  echo "no";
}
}
else
echo 'empty';
*/
?>