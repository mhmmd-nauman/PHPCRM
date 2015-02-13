<?php include "../../include/header.php"; 
	$utilObj = new util();
	$objGroups = new Groups();

$strWhere='Type ="Product"';
$SponRecords=$utilObj->getMultipleRow('SponsorCommissionLevel',$strWhere);

if($_REQUEST["Tasklevel"]=='addlevel'){
					if($_REQUEST['sale']=='Doller')
					{
					$Level1=$_REQUEST['Level1Price'];
					$Level2=$_REQUEST['Level2Price'];
					}
					else {
					$Level1=$_REQUEST['Level1Percentage'];
					$Level2=$_REQUEST['Level2Percentage'];
					
					}
		    $arrValue=array('ProductName'=>$_REQUEST['Product_name'],'Level1'=>$Level1,'Level2'=>$Level2,'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>$_REQUEST['Type'],'CommissionCycle'=>$_REQUEST['Commission_Cycle'],'CommissionnType'=>$_REQUEST['sale']);
			$insertedId=$utilObj->insertRecord('SponsorCommissionLevel', $arrValue);
			if($insertedId)
			$_SESSION['task']='add';
			echo "<script type='text/javascript'> 
			parent.$.fancybox.close();
			
			 </script> ";		}
		elseif($_REQUEST["Tasklevel"]=='updatelevel'){
				if($_REQUEST['sale']=='Doller')
					{
					$Level1=$_REQUEST['Level1Price'];
					$Level2=$_REQUEST['Level2Price'];
					}
					else {
					$Level1=$_REQUEST['Level1Percentage'];
					$Level2=$_REQUEST['Level2Percentage'];
					
					}
              $arrValue=array('ProductName'=>$_REQUEST['Product_name'],'Level1'=>$Level1,'Level2'=>$Level2,'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>$_REQUEST['Type'],'CommissionCycle'=>$_REQUEST['Commission_Cycle'],'CommissionnType'=>$_REQUEST['sale']);
			   $strWhere='ID='.$_REQUEST['commissionlevelid'];
			   $Updaterec=$utilObj->updateRecord('SponsorCommissionLevel', $strWhere, $arrValue);
			  if($Updaterec)
			 $_SESSION['task']='update';
			 echo "<script type='text/javascript'> 
			parent.$.fancybox.close();
			
			 </script> ";
			
	}	

elseif($_REQUEST["Tasklevel"]=='delete'){
      $strCriteria='ID='.$_REQUEST["commissionlevelid"];
      $DeleteRec=$utilObj->deleteRecord('SponsorCommissionLevel', $strCriteria);
	  if($DeleteRec)
	  $_SESSION['task']='delete';
     echo "<script type='text/javascript'> 
			parent.$.fancybox.close();
			
			 </script> ";
}

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

$total_records =  count($SponRecords);

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
 

$strWhere='Type ="Product" LIMIT '.$offset.','.$limit;
$SponRecords=$utilObj->getMultipleRow('SponsorCommissionLevel',$strWhere);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/Commissionslevel.js"></script>
</head>
<body>
<div id="headtitle">Coaching Commissions Level</div>
<div class="filtercontainer" style="padding-top:15px; padding-bottom:15px;">
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
      <td align="right" valign="center" ><a href="CommissionsSposorAddLevelPopup.php?Task=addlevel&type=1"  id="CoachinglevelADD"  class="Categoryedit Ecom_Link"> Add New Coaching Commissions Level</a> </td>
    </tr>
  </table> 
</div>
<div class="subcontainer">
  <div  style="float: left; padding: 10px 0 0;text-align: center; width: 905px;" >
    <?php if($Flag=='added') {?>
  <!-- <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Added Sucessfully! "; ?> </div>-->
    <?php } 
	  else if($Flag=='update') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Updated Sucessfully! "; ?> </div>
    <?php } 
	   else if($Flag=='delete') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Deleted Sucessfully! "; ?> </div>
    <?php $_SESSION['flag']='';} ?>
  </div>
  <table cellpadding="2" cellspacing="0"  border="0" width="100%" style='margin-top:10px;'>
    <tr id="headerbar">
      <td >Product</td>
      <td >Price</td>
      <td >Level 1</td>
      <td >Level 2</td>
      <td >Actions</td>
    </tr>
    <?php 

$color=1;
if(count($SponRecords)>0){
foreach($SponRecords as $SponlevVal):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';
$strWhere='ID='.$SponlevVal['ProductName'].'';
 $ProductRec=$utilObj->getSingleRow('Product', $strWhere);
  if($SponlevVal1['CommissionnType']=='Doller') { $a='$';} else $a='';
?>
    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $ProductRec['ProductName']; ?> </td>
      <td ><?php echo $ProductRec['ProductPrice']; ?></td>
      <td ><?php if($a!='') { echo $a."".$SponlevVal['Level1']; } else { echo $SponlevVal['Level1']." %"; } ?></td>
      <td ><?php if($a!='') { echo $a."".$SponlevVal['Level2']; } else { echo $SponlevVal['Level2']." %"; }?></td>
      <td >
    
<a href="CommissionsSposorAddLevelPopup.php?Task=updatelevel&type=1&levelid=<?php echo $SponlevVal['ID'];?>" class="Commissionleveledit" >
<img border="0" title="Edit Sale %" src="../../images/icon_page_edit.png"> </a>
</td>
    </tr>
    <?php 
	$color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Commissions Program Found</td>
    </tr>
    <?php  } ?>
  </table>
   <div align="center">

<?php include "../../lib/bottomnav.php" ?>

</div>
</div>

</body>
</html>