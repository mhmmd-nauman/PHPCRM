<?php 
require_once "../../lib/include.php";
 $utilObj = new util();
 $objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
$accessflag!='no';
if(!empty($_REQUEST['commissionid'])){
 $strWhere='ID='.$_REQUEST['commissionid'].'';
 $CommissionRec=$utilObj->getSingleRow('CommissionProgram', $strWhere);
}

?>

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
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<div>
  <form name="form1" method="post" action="CommissionsCoachingprogramlist.php">
    <input name="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="id" type="hidden" value="<?php echo $_REQUEST['commissionid'];?>" size="40">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
          <tr valign="top">
                   <td  align="left"colspan="2" class="tabsubheading"><strong>Commission Coaching </strong></td>
            </tr>
         <tr>
        <td colspan="2" height="10" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="left">Coaching Commission Name:</td>
        <td><input name="Commission_Name" type="text" value="<?php echo $CommissionRec['CommissionName']; ?>" size="100" ></td>
      </tr>
      <tr>
        <td colspan="2" height="5" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="left" valign="top">Description:</td>
        <td><textarea name="Commission_Description"   style="width: 635px; height: 330px;"><?php echo $CommissionRec['Description']; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="Submit" class="Ecom_Link" style="margin-top:6px;" value="Save">
       <?php if($_REQUEST['Task']=='update')  echo '<input type="button" name="DeleteCommissionprogram" id="DeleteCommissionprogram" class="Ecom_Link" style="margin-top:6px;" value="Delete">'; ?>
        </td>
      </tr>
    </table>
  </form>
  <form name="deleteform" id="deleteform" action="CommissionsCoachingprogramlist.php" method="post">
  <input type="hidden" name="deleterecord" class="MOSGLsmButton"  id="deleterecord" value="Delete" />
  <input name="id" type="hidden" value="<?php  echo $_REQUEST['commissionid']; ?>" size="40" style="height:20px;">
  </form>
</div>
