<?php include "../../include/header.php"; 
$utilObj = new util();
if($_REQUEST['Submit']=='Save' && $_REQUEST['Task']){
	switch($_REQUEST['Task']){
	case"add":
		    $arrValue=array('CommissionName'=>$_REQUEST['Commission_Name'],'Description'=>$_REQUEST['Commission_Description']);
			$insertedId=$utilObj->insertRecord(' CommissionProgram', $arrValue);
			if($insertedId)
			 $Flag='added';
		 break;
	case"update":
               $arrValue=array('CommissionName'=>$_REQUEST['Commission_Name'],'Description'=>$_REQUEST['Commission_Description']);
			   $strWhere='id='.$_REQUEST['id'];
			   $Updaterec=$utilObj->updateRecord(' CommissionProgram', $strWhere, $arrValue);
			  if($Updaterec)
			  $Flag='update';
		break;	
	}	
}
elseif($_REQUEST['deleterecord']=='Delete'){
      $strCriteria='ID='.$_REQUEST['id'].'';
      $DeleteRec=$utilObj->deleteRecord('CommissionProgram', $strCriteria);
	  if($DeleteRec)
	   $Flag='delete';

}
$CommissionRecords=$utilObj->getMultipleRow('CommissionProgram',1);

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
<script>

</script>

</head>
<body>
<div id="headtitle">Commission Program</div>
<div class="filtercontainer" style="padding-top:15px; padding-bottom:15px;">
  <!---->
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
      <td align="right" valign="center" ><a href="CommissionsSponsorprogramAddPopup.php?Task=add"  id="CommissionADD"  class="Categoryedit Ecom_Link"> Add New Commission Program </a> </td>
    </tr>
  </table>
  <!---->
</div>
<div class="subcontainer">
  <div  style="float: left; padding: 10px 0 0;text-align: center; width: 905px;" >
    <?php if($Flag=='added') {?>
    <div style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Added Sucessfully! "; ?> </div>
    <?php } 
	  else if($Flag=='update') {?>
    <div style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Updated Sucessfully! "; ?> </div>
    <?php } 
	   else if($Flag=='delete') {?>
    <div style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Deleted Sucessfully! "; ?> </div>
    <?php $_SESSION['flag']='';} ?>
  </div>
  <table cellpadding="2" cellspacing="0"  border="0" width="100%">
    <tr id="headerbar">
      <td >Id</td>
      <td >Commission Program Name</td>
      <td >Description</td>
      <td >Actions</td>
    </tr>
    <?php 

$color=1;
if(count($CommissionRecords)>0){
foreach($CommissionRecords as $Commissioncval):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

?>
    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $Commissioncval['ID']; ?> </td>
      <td ><?php echo $Commissioncval['CommissionName']; ?></td>
      <td ><?php echo $Commissioncval['Description']; ?></td>
      <td >
    
<a href="CommissionsSponsorprogramAddPopup.php?Task=update&commissionid=<?php echo $Commissioncval['ID'];?>" class="Commissionedit"  > 
<img border="0" title="Edit Coaching Commission" src="../../images/icon_page_edit.png"> </a> &nbsp;<a  href="CommissionsSponsorprogramSetupPopup.php?Task=setup&commissionprogramid=<?php echo $Commissioncval['ID'];?>"  class="Commissionsetup" > <img border="0" src="../../images/icon_settings.png" title="Setup Commission Level"></a>

</td>
</tr>
    <?php 
	$color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Commissions Found</td>
    </tr>
    <?php  } ?>
  </table>
</div>

</body>
</html>