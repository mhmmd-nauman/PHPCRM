<?php 
require_once "../../lib/include.php";
 $utilObj = new util();
$accessflag!='no';
if(!empty($_REQUEST['commissionid'])){
 $strWhere='ID='.$_REQUEST['commissionid'].'';
 $CommissionRec=$utilObj->getSingleRow('CommissionProgram', $strWhere);
}

?>
<!--<link rel="stylesheet" type="text/css" href="../../css/styles.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../../co_op/css/styles.css">-->
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
<div style="width:450px;">
  <form name="form1" method="post" action="CommissionsSponsorprogramlist.php">
    <input name="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="id" type="hidden" value="<?php echo $_REQUEST['commissionid'];?>" size="40">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
      <tr>
        <td colspan="2" id="name"><b>
          <?php if($_REQUEST['Task']=='add') echo 'Add New Commissions Program';else echo $CommissionRec['CommissionName']; ?>
          </b></td>
      </tr>
      <tr>
        <td colspan="2" height="10" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="right">Commission Program Name:</td>
        <td><input name="Commission_Name" type="text" value="<?php echo $CommissionRec['CommissionName']; ?>" size="40" style="height:20px;"></td>
      </tr>
      <tr>
        <td colspan="2" height="5" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="right" valign="top">Description:</td>
        <td><textarea name="Commission_Description"   style="width: 263px; height: 103px;"><?php echo $CommissionRec['Description']; ?></textarea></td>
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
  <form name="deleteform" id="deleteform" action="CommissionsSponsorprogramlist.php" method="post">
  <input type="hidden" name="deleterecord" class="MOSGLsmButton"  id="deleterecord" value="Delete" />
  <input name="id" type="hidden" value="<?php  echo $_REQUEST['commissionid']; ?>" size="40" style="height:20px;">
  </form>
</div>
