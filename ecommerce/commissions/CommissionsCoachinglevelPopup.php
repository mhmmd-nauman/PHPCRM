<?php 
require_once "../../lib/include.php";

$utilObj = new util();
$accessflag!='no';
if(!empty($_REQUEST['comlevid'])){
 $strWhere='ID='.$_REQUEST['comlevid'].'';
 $CommissionRec=$utilObj->getSingleRow('CommissionLevel', $strWhere);
}

$objmember = new Member();
$objGroups = new Groups();
$Coach_groupid = $objGroups->GetAllGroups("Code = 'coaching-staff' OR Code = 'call-center' ",array("*")); 

foreach((array)$Coach_groupid as $CoachGID){
	$GroupIds .= $CoachGID['ID'].",";
}
$GroupIds = trim($GroupIds,",");

//$Coach_staff = $objmember->GetAllMemberWithGroup("GroupID IN(".$GroupIds.") AND Member.AppCode != '' ORDER BY FirstName ASC ",array("*"));
?>
<script type="text/javascript">
$(document).ready(function() {
$("#DeleteCommissionLevel").click(function(){
 $('#deleteform').submit();
});
});
</script>
<div style="width:450px;">
  <form name="form1" method="post" action="CommissionsCoachinglevel.php">
    <input name="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="id" type="hidden" value="<?php echo $_REQUEST['comlevid'];?>" size="40">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
   <tr>
        <td colspan="2" id="name"><b>
          <?php if($_REQUEST['Task']=='add') echo 'Add New Commissions Level';else echo 'Edit Commissions Level'?>
          </b></td>
      </tr>
    <tr>
    <td align="right">Referring Coach :</td>
    <td><select name="CoachMember" id="CoachMember" class="inf-select default-input" style="width:268px; height:25px; padding:2px;">
                  <option value="">Select One</option>
                  <?php foreach($Coach_staff as $Coach ){

			  

			  ?>
                  <option value="<?php echo $Coach[0];?>" <?php if($CommissionRec['ReferenceCoachID'] == $Coach[0])echo'selected="selected"'; ?>><?php echo $Coach['FirstName']." ".$Coach['Surname']; ?></option>
                  <?php 



			   } ?>
                </select>
              </td>
    </tr>
      
      <tr>
        <td colspan="2" height="10" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="right">Commission Level:</td>
        <td><select name="Commission_level"  class="inf-select default-input" style="width:268px; height:25px; padding:2px;">
        <?php 
		$strWhere='ProgramsType= "Coaching" ';
		$CommRecords=$utilObj->getMultipleRow('CommissionProgram',$strWhere);
		foreach($CommRecords as $ComVal)
		{
		if($CommissionRec['CommissionLevelName']==$ComVal['ID']) $select="selected";
		else  $select="";
		 ?>
        	<option <?= $select; ?> value="<?= $ComVal['ID']; ?>" ><?= $ComVal['CommissionName']; ?></option>
          <? } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td colspan="2" height="5" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="right" valign="top">Over-ride Sale %:</td>
        <td><input name="Commission_overidesale" id="Commission_overidesale" type="text" value="<?php echo $CommissionRec['OverrideSale']; ?>" size="10" /></td>
      </tr>
      <tr>
        <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="Submit" class="Ecom_Link" style="margin-top:6px;" value="Save">
       <?php if($_REQUEST['Task']=='update')  echo '<input type="button" name="DeleteCommissionLevel" id="DeleteCommissionLevel" class="Ecom_Link" style="margin-top:6px;" value="Delete">'; ?>
         </td>
      </tr>
    </table>
  </form>
  <form name="deleteform" id="deleteform" action="CommissionsCoachinglevel.php" method="post">
  <input type="hidden" name="deleterecord" class="MOSGLsmButton"  id="deleterecord" value="Delete" />
  <input name="id" type="hidden" value="<?php  echo $_REQUEST['comlevid']; ?>" size="40" style="height:20px;">
  </form>
</div>
