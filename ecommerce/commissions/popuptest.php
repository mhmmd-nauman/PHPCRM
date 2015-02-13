<?php 
require_once "../../lib/include.php";
 $utilObj = new util();
 $objGroups = new Groups();
 $strWhere='CommissionProgramID ='.$_REQUEST['commissionprogramid'].'';
$ComsaleRecords=$utilObj->getMultipleRow(' CommissionLevelSale',$strWhere);

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

$total_records =  count($ComsaleRecords);

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
 

$strWhere='CommissionProgramID ='.$_REQUEST['commissionprogramid'].' LIMIT '.$offset.','.$limit;
$ComsaleRecords=$utilObj->getMultipleRow('CommissionLevelSale',$strWhere);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="../../../javascript/jquery.min.js"></script>

<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../../css/styles.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../../co_op/css/styles.css">
<script type="text/javascript" language="javascript">
$(document).ready(function() {
$('#operationmsg').fadeOut(8000);

$('#CommissionSetupAdd').fancybox({
			'width'                       : 550,

			'height'                      : 430,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'                : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			 'hideOnOverlayClick'                       :false,
			onClosed: function() {
			if(!$("#fancybox-close").click())
			{
    window.location.reload(); }
  },
		    'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
              $.fancybox.resize();
		    }
			
			});
			
			<?php foreach($ComsaleRecords as $ComsaleVal){
?>
			$('#CommissionSetupedit_<?php echo $ComsaleVal['ID'];?>').fancybox({
			'width'                       : 550,

			'height'                      : 430,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'               : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			'hideOnOverlayClick'          :  false,
			 onClosed					  : function() { window.location.reload(); },

			'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
              $.fancybox.resize();
		    }
			
			});
			<?php } ?>
				});
</script>
<style>
td {
	font-size:12px;
}
</style>
</head>
<body>
<div style="width:700px;" id='setupdiv' >
<form name="form1" method="post" action="CommissionsCoachingprogramlist.php">
    <input name="commissionprogram_id" type="hidden" value="<?php echo $_REQUEST['commissionprogramid'];?>" size="40">
<table cellpadding="2" cellspacing="0"  border="0" width="100%" style='margin-top:10px;' >
    <tr>
      <td colspan="2" id="name"><b> Add Coaching Commissions Level</b> </td>
    </tr>
    <tr>
      <td align="right" valign="center" height="50px;">
      <a href="CommissionsCoachingAddsalePopup.php?Task=addsale&commissionprogramid=<?php echo $_REQUEST['commissionprogramid']; ?>"  id="CommissionSetupAdd"  class="Categoryedit Ecom_Link" style='font-size:13px;'> Add Sale % </a> </td>
    </tr>
  </table>
<div class="subcontainer">
  <div  style="float: left; padding: 10px 0 0;text-align: center; " >
    <?php if($_SESSION['task']=='add') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Added Sucessfully! "; ?> </div>
    <?php } 
	  else if($_SESSION['task']=='update') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Updated Sucessfully! "; ?> </div>
    <?php } 
	   else if($_SESSION['task']=='delete') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Deleted Sucessfully! "; ?> </div>
    <?php } ?>
    <?php //$_SESSION['task']=''; ?>
  </div>
  <table cellpadding="2" cellspacing="0"  border="0" width="100%" style='margin-top:10px;'>
    <tr id="headerbar">
      <td >Id</td>
      <td >Commission Level Name</td>
      <td >Description</td>
      <td >Sale %</td>
      <td >Time</td>
      <td >Actions</td>
    </tr>
    <?php 

$color=1;
if(count($ComsaleRecords)>0){
foreach($ComsaleRecords as $ComsaleVal):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

?>
    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $ComsaleVal['ID']; ?> </td>
      <td ><?php echo $ComsaleVal['CommissionLevelSaleName']; ?></td>
      <td ><?php echo $ComsaleVal['Description']; ?></td>
      <td><?php echo $ComsaleVal['Sale']; ?></td>
      <td><?php echo $ComsaleVal['CommissionBasedOn']; ?></td>
      <!--ProductCategoryAddPopup.php?Task=update&catid=<?php //echo $catcval['id']; ?>-->
      <td >
    
<a href="CommissionsCoachingAddsalePopup.php?Task=updatesale&commissionlevelsaleid=<?php echo $ComsaleVal['ID'];?>&commissionprogramid=<?php echo $_REQUEST['commissionprogramid']; ?>" id="CommissionSetupedit_<?php echo $ComsaleVal['ID'];?>"  >
<img border="0" title="Edit Sale %" src="../../images/icon_page_edit.png"> </a>
</td>
    </tr>
    <?php 
	$color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Coaching Commissions Level Found</td>
    </tr>
    <?php  } ?>
  </table>
  <div align="center">

<?php include "../../lib/bottomnav.php" ?>

</div>
</div>
</form>
</div>
</body>