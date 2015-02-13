<?php 
require_once "../../lib/include.php";
 $utilObj = new util();
$accessflag!='no';
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
if(!empty($_REQUEST['levelid']) ){

 $strWhere='ID='.$_REQUEST['levelid'];
 $SponRec=$utilObj->getSingleRow('SponsorCommissionLevel', $strWhere);
}


?>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/Tooltip/css/tooltip.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../css/styles.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../../co_op/css/styles.css">
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/Tooltip/js/tooltip.js"></script>
<script src="../../../javascript/jquery.js"></script>-->
<script type="text/javascript">
$(document).ready(function() {
$("#DeleteCommissionprogram").click(function(){
if(confirm('Do tou really want to delete'))
$('#deleteform').submit();
else 
return false;
 });



});
function ShowPerc(a)
{
	if(a=='Doller')
	{
	
	document.getElementById("Level1Percentage").style.display='none';
	document.getElementById("Level2Percentage").style.display='none';
	document.getElementById("Level1Price").style.display='block';
	document.getElementById("Level2Price").style.display='block';
	}
	if(a=='Percentage')
	{
	document.getElementById("Level1Percentage").style.display='block';
	document.getElementById("Level2Percentage").style.display='block';
	document.getElementById("Level1Price").style.display='none';
	document.getElementById("Level2Price").style.display='none';
	}
}
</script>
<style>
td {
	font-size:12px;
	}
</style>
<body onload='ShowPerc("<?= $SponRec['CommissionnType'] ?>")'>




<div >
  <form name="form1" id="form1" method="post" action="CommissionsSponsorprogramSetupPopup.php">
    <input name="Tasklevel" id="Tasklevel" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="commissionlevelid" id="commissionlevelid" type="hidden" value="<?php echo $_REQUEST['levelid'];?>" size="40">
     <input name="Type" type="hidden" value="<?php echo $_REQUEST['type'];?>" size="40">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
      <tr>
        <td colspan="2" id="name"><b>
          <?php if($_REQUEST['Task']=='addlevel') echo 'Add Product Name And Commission Levels';else echo 'Edit Product Name And Commission Levels'?>
          </b></td>
      </tr>
      <tr>
        <td colspan="2" height="10" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td >Product Name:</td>
        <td><select name="Product_name" id="Product_name" class="inf-select default-input" style="width:200px; height:25px; padding:2px;">
      <? 
	  if($_REQUEST['type']=='2'){
	  $SubProductRec=$utilObj->getMultipleRow('ProductSubscription', 1); 
	  	 foreach($SubProductRec as $SubProdVal) {
		  $strwhere="ID=".$SubProdVal['ProductID'];
		
		 $ProductRec=$utilObj->getSingleRow('Product', $strwhere);
		  
		 if($ProductRec['ID']== $SponRec['ProductName'])
		 {
		  $selected='selected';
		 }
		 else  $selected='';
		 ?>
         <option  value="<?php echo $ProductRec['ID']; ?>" <? echo $selected; ?>><? echo $ProductRec['ProductName'] ?></option>
       <? } 
	   
	   }
	   else
	   {
	   $ProductRec=$utilObj->getMultipleRow('Product', 1); 
	  	 foreach($ProductRec as $ProdVal) {
		 
		 if($ProdVal['ID']== $SponRec['ProductName'])
		 {
		  $selected='selected';
		 }
		 else  $selected='';
		 ?>
         <option  value="<?php echo $ProdVal['ID']; ?>" <? echo $selected; ?>><? echo $ProdVal['ProductName'] ?></option>
       <? } 
	   }?>     
            </select></td>
      </tr>
      <? if($_REQUEST['type']=='2') {?>
      <tr><td> Pay Commissions For:</td><td><input name="Commission_Cycle" type="text" value="<?php echo $SponRec['CommissionCycle']; ?>" size="10" style="height:20px;"> &nbsp; Cycles:(0=unlimited)</td></tr>
      <? } else {?>
      <tr><td>&nbsp; </td><td></td></tr> <? } ?>
      <tr><td>&nbsp; </td><td></td></tr>
       <tr><td> Commissions</td><td></td></tr>
      <tr>
        <td colspan='2' valign="top">
        <table cellpadding="2" cellspacing="2"  border="0" width="100%">
    <tr id="headerbar">
      <td >Level</td>
      <td style='width:10px;'> </td>
      <td >Level1</td>
      <td >Level2</td>
     
    </tr>
    <? //$doller=''; $perc='checked';  if($SponRec['CommissionnType']=='Doller') { $doller='checked'; $perc=''; } elseif($SponRec['CommissionnType']=='Percentage')  {$doller=''; $perc='checked';} ?>
    <?php echo $SponRec['CommissionnType'];
if($SponRec['CommissionnType']=='Doller') {
	$doller='checked'; $perc=''; 
	$str2='style="display:none;"';} 
elseif($SponRec['CommissionnType']=='Percentage')  {
	$doller=''; $perc='checked';
	$str1='style="display:none;"';}
	?>
    
    
    
    <tr><td>Sale $ </td><td><input type='radio' name='sale' value='Doller' <?= $doller?> onclick='ShowPerc(this.value)'/></td>
    <td><input name="Level1Price" id="Level1Price" <?php echo $str1;?> type="text" value="<?php echo $SponRec['Level1']; ?>" size="10"></td>
    <td><input name="Level2Price" id="Level2Price" <?php echo $str1;?> type="text" value="<?php echo $SponRec['Level2']; ?>" size="10"></td>
    </tr>
    <tr><td>Sale %</td><td><input type='radio' name='sale' value='Percentage' <?=$perc?> onclick='ShowPerc(this.value)'  /></td>
    <td><input name="Level1Percentage" id="Level1Percentage" <?php echo $str2;?> type="text" value="<?php echo $SponRec['Level1']; ?>" size="10" ></td>
    <td><input name="Level2Percentage" id="Level2Percentage" <?php echo $str2;?> type="text" value="<?php echo $SponRec['Level2']; ?>" size="10" ></td>
    </tr>
    
<?php /*?>    <?php if($SponRec['CommissionnType']=='Doller') { ?>
    <tr><td>Sale $ </td><td><input type='radio' name='sale' value='Doller' <?= $doller?> onclick='ShowPerc(this.value)'/></td>
    <td><input name="Level1Price" id="Level1Price" type="text" value="<?php echo $SponRec['Level1']; ?>" size="10" style="height:20px;" ></td>
    <td><input name="Level2Price" id="Level2Price" type="text" value="<?php echo $SponRec['Level2']; ?>" size="10" style="height:20px;"></td>
    </tr><?php }if($SponRec['CommissionnType']=='Percentage') { ?>
    <tr><td>Sale %</td><td><input type='radio' name='sale' value='Percentage' <?=$perc?> onclick='ShowPerc(this.value)'  /></td>
    <td><input name="Level1Percentage" id="Level1Percentage"  type="text" value="<?php echo $SponRec['Level1']; ?>" size="10" style="height:20px;"></td>
    <td><input name="Level2Percentage" id="Level2Percentage"  type="text" value="<?php echo $SponRec['Level2']; ?>" size="10" style="height:20px;"></td>
    </tr><?php } ?><?php */?>
    
    
    
    
    </table>
        </td>
       
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="Submit"  id='saverecord'  class="Ecom_Link" style="margin-top:6px;" value="Save">
       <?php if($_REQUEST['Task']=='updatelevel')  {?> <input type="button" name="DeleteCommissionprogram" id="DeleteCommissionprogram"  class="Ecom_Link" style="margin-top:6px;" value="Delete"> <?php } ?>
        </td>
      </tr>
    </table>
  </form>
  <form name="deleteform" id="deleteform" action="CommissionsSponsorprogramSetupPopup.php" method="post">
  <input type="hidden" name="deleterecord" class="MOSGLsmButton"  id="deleterecord" value="Delete" />
  <input name="Tasklevel" id="Tasklevel" type="hidden" value="delete" size="40" >
  <input name="commissionlevelid" id="commissionlevelid" type="hidden" value="<?php echo $_REQUEST['levelid'];?>" size="40">
  <input name="Type" type="hidden" value="<?php echo $_REQUEST['type'];?>" size="40">
  
  </form>
</div>
</body>
