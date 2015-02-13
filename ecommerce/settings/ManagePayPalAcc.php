<?php include "../../include/header.php"; 
$utilObj = new util();
$objGroups = new Groups();
if($_REQUEST['Submit']=='Save' && $_REQUEST['Task']){
	switch($_REQUEST['Task']){
	case"add":
	     if($_REQUEST['MerchantAccountMode']=='')
		  $mode=1;
		  else
		  $mode=0;
		  if($_REQUEST['expressCheckoutEnabled']=='yes'){
		  
			  if(!empty($_REQUEST['APIUserName']) && !empty($_REQUEST['APIPassword']) && !empty($_REQUEST['APISingnature']) ){
			   
				  $arrValue=array( 'PayFlowUser'=>$_REQUEST['APIUserName'] ,'PayFlowPassword 	'=>$_REQUEST['APIPassword'] ,'PayFlowVendor'=>$_REQUEST['APISingnature'],'AccountName'=>$_REQUEST['MerchantAccName'],'AccountType'=>0,'Mode'=>$mode,'PayFlowCurrency'=>$_REQUEST['Currency']);
			  
			  }
		  }
		  else{
		  	  $arrValue=array('AccountName'=>$_REQUEST['MerchantAccName'],'AccountType'=>0,'Mode'=>$mode,'PayFlowCurrency'=>$_REQUEST['Currency']);
		  }
		  
		    $insertedId=$utilObj->insertRecord('ManageMerchantAcc', $arrValue);
			if($insertedId)
			 $Flag='added';
		 break;
	case"update":
			if($_REQUEST['MerchantAccountMode']=='')
			  $mode=1;
			  else
			  $mode=0;
			  
			if($_REQUEST['expressCheckoutEnabled']=='yes'){
			  if(!empty($_REQUEST['APIUserName']) && !empty($_REQUEST['APIPassword']) && !empty($_REQUEST['APISingnature']) ){
		     $arrValue=array( 'PayFlowUser'=>$_REQUEST['APIUserName'] ,'PayFlowPassword 	'=>$_REQUEST['APIPassword'] ,'PayFlowVendor'=>$_REQUEST['APISingnature'],'AccountName'=>$_REQUEST['MerchantAccName'],'AccountType'=>0,'Mode'=>$mode,'PayFlowCurrency'=>$_REQUEST['Currency']);
		   }
		  }
		  else{
	        $arrValue=array('AccountName'=>$_REQUEST['MerchantAccName'],'AccountType'=>0,'Mode'=>$mode, 'PayFlowCurrency'=>$_REQUEST['Currency'],'PayFlowUser'=>'','PayFlowPassword 	'=>'' ,'PayFlowVendor'=>'');
			}
			  $strWhere='MerchantId='.$_REQUEST['id'].'';
			  $Updaterec=$utilObj->updateRecord('ManageMerchantAcc', $strWhere, $arrValue);
			  if($Updaterec)
			  $Flag='update';
		break;	
	}	
}
elseif($_REQUEST['deleterecord']=='Delete'){
      $strCriteria='MerchantId='.$_REQUEST['id'].' and AccountType=0';
      $DeleteRec=$utilObj->deleteRecord('ManageMerchantAcc', $strCriteria);
	  if($DeleteRec)
	   $Flag='delete';

}

$strwhere='AccountType=0';
$merchantRecords=$utilObj->getMultipleRow('ManageMerchantAcc',$strwhere);

if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
}
if($_SESSION['page'] > 0 && !isset($_REQUEST['page'])){
  $page = $_SESSION['page'] ;
  $_SESSION['page'] = "";
  unset($_SESSION['page']);
}elseif(!isset($_REQUEST['page'])) {
  $page=1;
  $_SESSION['page'] = 1 ;
} else {
  $page=$_REQUEST['page'];
  $_SESSION['page'] = $page; 
}

$total_records =  count($merchantRecords);

 if(!isset($_SESSION['limit'])){
	$limit = 10 ;
} else if($_SESSION['limit'] =="all" ){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'];
} 

$ret = $objGroups->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}
 

$strWhere=' AccountType=0 LIMIT '.$offset.','.$limit;
$merchantRecords=$utilObj->getMultipleRow('ManageMerchantAcc',$strWhere);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css" />

<script type="text/javascript">
$(document).ready(function() {
$(".merchantedit").fancybox({'hideOnOverlayClick':false});
$(".merchantAccTest").fancybox({'hideOnOverlayClick':false});
$("a#addMaccount").fancybox({'href' :this.href,'hideOnOverlayClick':false });

});
</script>
</head>
<body>
<div id="headtitle">PayPal Website Payments Standard Account</div>
<a href="PayPalAccPopup.php"  id="merchantlink" style="display:none">&nbsp;</a>
<div class="filtercontainer" style="padding-top:15px; padding-bottom:15px;">
  <!---->
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
      <td><div align="right"><a href="PayPalAccPopup.php?Task=add" id="addMaccount"  class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="height:25px; text-align:center; width:150px; padding-top:10px;">
      New Paypal Account        
        </a> </div></td>
    </tr>
  </table>
  <!---->
</div>
<div class="subcontainer">
  <div  style="float: left; padding: 10px 0 0;text-align: center; width: 905px;" >
    <?php if($Flag=='added') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Added Sucessfully! "; ?> </div>
    <?php } 
	  else if($Flag=='update') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Updated Sucessfully! "; ?> </div>
    <?php } 
	   else if($Flag=='delete') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Deleted Sucessfully! "; ?> </div>
    <?php } ?>
  </div>
  <table cellpadding="2" cellspacing="0"  border="0" width="100%">
    <tr id="headerbarpopup">
      <td >Email Address</td>
      <td  >Mode</td>
      <td >Edit</td>
    </tr>
    <?php 
$color=1;
foreach($merchantRecords as $mercval):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

?>
    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $mercval['AccountName']; ?></td>
      <td ><?php if($mercval['Mode']==0) echo "Test"; else echo "Live"; ?></td>
      <td ><a href="PayPalAccPopup.php?id=<?php echo $mercval['MerchantId']; ?>&Task=update" class="merchantedit" ><img border="0" title="Edit Lead Details" src="../../images/icon_page_edit.png"></a></td>
    </tr>
    <?php $color++; endforeach; ?>
  </table>
   <div align="center">

<?php include "../../lib/bottomnav.php" ?>

</div>
</div>
</body>
</html>