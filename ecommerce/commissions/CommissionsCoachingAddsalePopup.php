<?php 
require_once "../../lib/include.php";
 $utilObj = new util();
$accessflag!='no';
if(!empty($_REQUEST['commissionlevelsaleid'])){
 $strWhere='ID='.$_REQUEST['commissionlevelsaleid'].' AND CommissionProgramID='.$_REQUEST['commissionprogramid'];
 $ComsaleRec=$utilObj->getSingleRow('CommissionLevelSale', $strWhere);
}

if($_REQUEST["Tasksale"]=='addsale'){
			
			if($_REQUEST['Sale_op1']!="")
			{
			$criteria1=$_REQUEST['Commission_operator1'].''.$_REQUEST['Sale_op1'];
			}
			if($_REQUEST['Sale_op2']!="")
			{
			$criteria2=$_REQUEST['Commission_operator2'].''.$_REQUEST['Sale_op2'];
			}
			
						
		    $arrValue=array('CommissionLevelSaleName'=>$_REQUEST['CommissionLevel_Name'],'Description'=>$_REQUEST['Commission_Description'],'Sale'=>$_REQUEST['Sale'],'CommissionBasedOn'=>$_REQUEST['Commission_based'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'CommissionProgramID'=>$_REQUEST['commissionprogramid'],'Criteria1'=>$criteria1,'Criteria2'=>$criteria2);
			$insertedId=$utilObj->insertRecord('CommissionLevelSale', $arrValue);
			if($insertedId)
			$_SESSION['task']='add';
			
			echo "<script type='text/javascript'> 
			parent.$.fancybox.close();
			 </script> ";
		}
		elseif($_REQUEST["Tasksale"]=='updatesale'){
		if($_REQUEST['Sale_op1']!="")
			{
			$criteria1=$_REQUEST['Commission_operator1'].''.$_REQUEST['Sale_op1'];
			}
			if($_REQUEST['Sale_op2']!="")
			{
			$criteria2=$_REQUEST['Commission_operator2'].''.$_REQUEST['Sale_op2'];
			}
			
						
		    $arrValue=array('CommissionLevelSaleName'=>$_REQUEST['CommissionLevel_Name'],'Description'=>$_REQUEST['Commission_Description'],'Sale'=>$_REQUEST['Sale'],'CommissionBasedOn'=>$_REQUEST['Commission_based'],'LastEdited'=>date('Y-m-d H:i:s'),'CommissionProgramID'=>$_REQUEST['commissionprogramid'],'Criteria1'=>$criteria1,'Criteria2'=>$criteria2);
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
}
?>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles_ecommerce.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search_ecommerce.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">

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
  <form name="form1" method="post" action="CommissionsCoachingAddsalePopup.php">
    <input name="Tasksale" id="Tasksale" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="commissionlevelsaleid" id="commissionlevelsaleid" type="hidden" value="<?php echo $_REQUEST['commissionlevelsaleid'];?>" size="40">
     <input name="commissionprogramid" type="hidden" value="<?php echo $_REQUEST['commissionprogramid'];?>" size="40">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
      <tr>
        <td colspan="2" id="name"><b>
               
          <?php if($_REQUEST['Task']=='addsale'){?> 
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;Add Commissions Sale % 
	  	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
   
    </table>
              <?php }else{?>
               
                   <?php }?>
          </b></td>
      </tr>
      <tr>
        <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td >Commission Level Name:</td>
        <td><input name="CommissionLevel_Name" id="CommissionLevel_Name" type="text" value="<?php echo $ComsaleRec['CommissionLevelSaleName']; ?>" size="40" ></td>
      </tr>
      
      <tr>
        <td  valign="top">Description:</td>
        <td><textarea name="Commission_Description"  id="Commission_Description" style="width: 263px; height: 103px;"><?php echo $ComsaleRec['Description']; ?></textarea></td>
      </tr>
      <tr>
        <td  valign="top">Sale %:</td>
        <td><input name="Sale" id="Sale" type="text" value="<?php echo $ComsaleRec['Sale']; ?>" size="20" ></td>
      </tr>
      <tr>
        <td  colspan='2'><br />Add Criteria Below to this commission sale %</td>
      </tr>
      <tr>
      
      <td>Based On</td>
      <td ><select name="Commission_based" id="Commission_based" class="inf-select default-input" style="width:128px; height:25px; padding:2px;">
      
        	<option <?php if($ComsaleRec['CommissionBasedOn']=='Weekly') echo "selected";  ?> value="Weekly" >Weekly</option>
            <option <?php if($ComsaleRec['CommissionBasedOn']=='Monthly') echo "selected";  ?> value="Monthly">Monthly</option>
            <option <?php if($ComsaleRec['CommissionBasedOn']=='Daily') echo "selected";  ?> value="Daily">Daily</option>
            </select> &nbsp;Sales  <a class="toolTip note_blue blue_blank MemberApp_Popup" id="various<?php echo $ComsaleRec['ID'];?>note1" title="<?php echo "Testing Text";?>" style="color:#000000;padding-left:170px;float:right;"></a></td>
      </tr>
      <? 
	  $criteria1=$ComsaleRec['Criteria1'];
	  $sale1=preg_replace("/[^0-9]/", '', $criteria1);
	  $oprator1=preg_replace("/[0-9]/", '', $criteria1);
	  $criteria2=$ComsaleRec['Criteria2'];
	  $sale2=preg_replace("/[^0-9]/", '', $criteria2);
	  $oprator2=preg_replace("/[0-9]/", '', $criteria2);
	  ?>
      <tr>
      <td>Operator 1</td>
      <td><select name="Commission_operator1" id="Commission_operator1"  class="inf-select default-input" style="width:168px; height:25px; padding:2px;">
        	<option  value='<' <?php if($oprator1=='<') echo "selected";  ?>> less than </option>
            <option  value='>' <?php if($oprator1=='>') echo "selected";  ?>> greater than</option>
            <option value='<=' <?php if($oprator1=='<=') echo "selected";  ?>>less than or equal to</option>
            <option value='>=' <?php if($oprator1=='>=') echo "selected";  ?>> greater than or equal to</option>
            </select>  &nbsp;&nbsp;&nbsp;&nbsp;<input name="Sale_op1" type="text" value="<?=$sale1?>" size="7"> &nbsp;Sales</td>
      </tr>
      <tr><td  colspan="2" align="center">AND</td></tr>
      <tr>
      <td>Operator 2(optional)</td>
      <td><select name="Commission_operator2" id="Commission_operator2"   class="inf-select default-input" style="width:168px; height:25px; padding:2px;">
        	<option  value='<' <?php if($oprator1=='<') echo "selected";  ?>> less than </option>
            <option  value='>' <?php if($oprator1=='>') echo "selected";  ?>> greater than</option>
            <option value='<=' <?php if($oprator1=='<=') echo "selected";  ?>>less than or equal to</option>
            <option value='>=' <?php if($oprator1=='>=') echo "selected";  ?>> greater than or equal to</option>
            </select>  &nbsp;&nbsp;&nbsp;&nbsp;<input name="Sale_op2" type="text" value="<?=$sale2?>" size="7" > &nbsp;Sales</td>
      
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="Submit"  id='saverecord'  class="Ecom_Link" style="margin-top:6px;" value="Save">
       <?php if($_REQUEST['Task']=='updatesale')  {?> <input type="button" name="DeleteCommissionprogram" id="DeleteCommissionprogram"  class="Ecom_Link" style="margin-top:6px;" value="Delete"> <?php } ?>
        </td>
      </tr>
    </table>
  </form>
  <form name="deleteform" id="deleteform" action="CommissionsCoachingAddsalePopup.php" method="post">
  <input type="hidden" name="deleterecord" class="MOSGLsmButton"  id="deleterecord" value="Delete" />
  <input name="Tasksale" id="Tasksale" type="hidden" value="delete" size="40" >
  <input name="commissionprogramid" type="hidden" value="<?php echo $_REQUEST['commissionprogramid'];?>" size="40">
  <input name="commissionlevelsaleid" id='commissionlevelsaleid' type="hidden" value="<?php  echo $_REQUEST['commissionlevelsaleid']; ?>" size="40" style="height:20px;">
  </form>
</div>

