<?php 
require_once "../../lib/include.php";
 $utilObj = new util();
$accessflag!='no';
if(!empty($_REQUEST['commissionlevelsaleid'])){
 $strWhere='ID='.$_REQUEST['commissionlevelsaleid'].' AND CommissionProgramID='.$_REQUEST['commissionprogramid'];
 $ComsaleRec=$utilObj->getSingleRow('CommissionLevelSale', $strWhere);
}

/*if($_REQUEST["Tasksale"]=='addsale'){
			
			if($_REQUEST['Commission_operator1']=='<' && $_REQUEST['Commission_operator2']=='>' && $_REQUEST['Sale_op1']!="" && $_REQUEST['Sale_op2']!="")
			{
			$comsale=$_REQUEST['Sale_op1']." To ".$_REQUEST['Sale_op2']." Sales";
			}
			elseif($_REQUEST['Commission_operator1']=='>' && $_REQUEST['Sale_op1']!="")
			{
			$comsale="Over ".$_REQUEST['Sale_op1']." Sales";
			}
			elseif($_REQUEST['Commission_operator1']=='<' && $_REQUEST['Sale_op1']!="")
			{
			$comsale="Less than ".$_REQUEST['Sale_op1']." Sales";
			}
			
			$CommissionLevelSaleName=$comsale." ".$_REQUEST['CommissionLevel_Name'];	
		    $arrValue=array('CommissionLevelSaleName'=>$CommissionLevelSaleName,'Description'=>$_REQUEST['Commission_Description'],'Sale'=>$_REQUEST['Sale'],'CommissionBasedOn'=>$_REQUEST['Commission_based'],'CommissionProgramID'=>$_REQUEST['commissionprogramid']);
			$insertedId=$utilObj->insertRecord('CommissionLevelSale', $arrValue);
			if($insertedId)
			$_SESSION['task']='add';
			
			echo "<script type='text/javascript'> 
			parent.$.fancybox.close();
			 </script> ";
		}
		elseif($_REQUEST["Tasksale"]=='updatesale'){
		if($_REQUEST['Commission_operator1']=='<' && $_REQUEST['Commission_operator2']=='>' && $_REQUEST['Sale_op1']!="" && $_REQUEST['Sale_op2']!="")
			{
			$comsale=$_REQUEST['Sale_op1']." To ".$_REQUEST['Sale_op2']." Sales";
			}
			elseif($_REQUEST['Commission_operator1']=='>' && $_REQUEST['Sale_op1']!="")
			{
			$comsale="Over ".$_REQUEST['Sale_op1']." Sales";
			}
			elseif($_REQUEST['Commission_operator1']=='<' && $_REQUEST['Sale_op1']!="")
			{
			$comsale="Less than ".$_REQUEST['Sale_op1']." Sales";
			}
			
			$CommissionLevelSaleName=$comsale." ".$_REQUEST['CommissionLevel_Name'];	
              $arrValue=array('CommissionLevelSaleName'=>$CommissionLevelSaleName,'Description'=>$_REQUEST['Commission_Description'],'Sale'=>$_REQUEST['Sale'],'CommissionBasedOn'=>$_REQUEST['Commission_based']);
			   $strWhere='ID='.$_REQUEST['commissionlevelsaleid'].' AND CommissionProgramID='.$_REQUEST['commissionprogramid'];
			   $Updaterec=$utilObj->updateRecord('CommissionLevelSale', $strWhere, $arrValue);
			  if($Updaterec)
			 $_SESSION['task']='update';
			  echo "<script type='text/javascript'> 
			  
			parent.$.fancybox.close();
			
            </script> ";
			
	}	

elseif($_REQUEST["Tasksale"]=='delete'){
      $strCriteria='ID='.$_REQUEST["commissionlevelsaleid"];
      $DeleteRec=$utilObj->deleteRecord('CommissionLevelSale', $strCriteria);
	  if($DeleteRec)
	  $_SESSION['task']='delete';
       echo "<script type='text/javascript'> 
	   		
			parent.$.fancybox.close();
			
            </script> ";
}*/
?>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/Tooltip/css/tooltip.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../css/styles.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../../co_op/css/styles.css">
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/Tooltip/js/tooltip.js"></script>
<script src="../../../javascript/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$("#DeleteCommissionprogram").click(function(){
if(confirm('Do tou really want to delete'))
$('#deleteform').submit();
else 
return false;
 });
});

</script>
<style>
td {
	font-size:12px;
	}
</style>
<div >
  <form name="form1" method="post" action="">
    <input name="Tasksale" id="Tasksale" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="commissionlevelsaleid" id="commissionlevelsaleid" type="hidden" value="<?php echo $_REQUEST['commissionlevelsaleid'];?>" size="40">
     <input name="commissionprogramid" type="hidden" value="<?php echo $_REQUEST['commissionprogramid'];?>" size="40">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
      <tr>
        <td colspan="2" id="name"><b>
          <?php if($_REQUEST['Task']=='addsale') echo 'Add Product Name And Commission Levels';else echo 'Edit Product Name And Commission Levels'?>
          </b></td>
      </tr>
      <tr>
        <td colspan="2" height="10" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td >Product Name:</td>
        <td><select name="Commission_based" id="Commission_based" class="inf-select default-input" style="width:200px; height:25px; padding:2px;">
      
        	<option <?php if($ComsaleRec['CommissionBasedOn']=='Weekly') echo "selected";  ?> value="Weekly" >Weekly</option>
            <option <?php if($ComsaleRec['CommissionBasedOn']=='Monthly') echo "selected";  ?> value="Monthly">Monthly</option>
            <option <?php if($ComsaleRec['CommissionBasedOn']=='Daily') echo "selected";  ?> value="Daily">Daily</option>
            </select></td>
      </tr>
      <tr><td> &nbsp;</td><td></td></tr>
      <tr><td> &nbsp;</td><td></td></tr>
       <tr><td> Commissions</td><td></td></tr>
      <tr>
        <td colspan='2' valign="top">
        <table cellpadding="2" cellspacing="2"  border="0" width="100%">
    <tr id="headerbar">
      <td >Level</td>
      <td >Level1</td>
      <td >Level2</td>
     
    </tr>
    <tr><td>Sale $</td>
    <td><input name="Commission_Name" type="text" value="<?php echo $CommissionRec['CommissionName']; ?>" size="10" style="height:20px;"></td>
    <td><input name="Commission_Name" type="text" value="<?php echo $CommissionRec['CommissionName']; ?>" size="10" style="height:20px;"></td>
    </tr>
    <tr><td>Sale %</td>
    <td><input name="Commission_Name" type="text" value="<?php echo $CommissionRec['CommissionName']; ?>" size="10" style="height:20px;"></td>
    <td><input name="Commission_Name" type="text" value="<?php echo $CommissionRec['CommissionName']; ?>" size="10" style="height:20px;"></td>
    </tr>
    </table>
        </td>
       
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="Submit"  id='saverecord'  class="Ecom_Link" style="margin-top:6px;" value="Save">
       <?php if($_REQUEST['Task']=='updatesale')  {?> <input type="button" name="DeleteCommissionprogram" id="DeleteCommissionprogram"  class="Ecom_Link" style="margin-top:6px;" value="Delete"> <?php } ?>
        </td>
      </tr>
    </table>
  </form>
  <form name="deleteform" id="deleteform" action="" method="post">
  <input type="hidden" name="deleterecord" class="MOSGLsmButton"  id="deleterecord" value="Delete" />
  <input name="Tasksale" id="Tasksale" type="hidden" value="delete" size="40" >
  <input name="commissionprogramid" type="hidden" value="<?php echo $_REQUEST['commissionprogramid'];?>" size="40">
  <input name="commissionlevelsaleid" id='commissionlevelsaleid' type="hidden" value="<?php  echo $_REQUEST['commissionlevelsaleid']; ?>" size="40" style="height:20px;">
  </form>
</div>

