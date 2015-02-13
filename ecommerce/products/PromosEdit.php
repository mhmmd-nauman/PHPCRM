<?php 
require_once "../../lib/include.php";
$utilObj = new util();
$accessflag!='no';
$ObjPromotionalCode = new PromotionalCode();
$CatRecords=$ObjPromotionalCode->GetAllPromotionalCodes("ID='".$_REQUEST['catid']."'",array("*"));
//print_r($CatRecords);
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
if(!empty($_REQUEST['catid'])){
 $strWhere='ID='.$_REQUEST['catid'].'';
 $categoryRec=$utilObj->getSingleRow('ProductCategory', $strWhere);
}
if($_REQUEST['Task']=='update')
{
$Task="Update";
}
else
{
$Task="add";
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.9.1.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script> 

  <form action="Promos.php?catid=<?php echo $_REQUEST['catid']; ?>&Task=<?php echo $Task;?>" method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);" target="_parent" >
    <input name="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="id" type="hidden" value="<?php echo $_REQUEST['catid'];?>" size="40">
	<div class="Popupspace"></div>
	  <div class="containerpopup">
	  			<div class="subcontainer"> 
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center">
      
      <tr>
        <td id="tdleft"> Name:</td>
        <td id="tdmiddle"><input name="Category_Name" type="text" value="<?php echo $CatRecords[0]['Category_Name']; ?>" class="product"></td>
		<td id="tdright" >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" height="5" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="left" valign="top">Description:</td>
        <td><textarea name="Cat_Description"  style="height:90px;" class="product" ><?php echo $CatRecords[0]['Description']; ?></textarea></td>
		 <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="left">Price:</td>
        <td align="left"><input name="Price" type="text" value="<?php echo "$".number_format($CatRecords[0]['Price'],2); ?>" class="product" size="8"></td>
		 <td>&nbsp;</td>
      </tr>
	  <tr valign="top">
                   <td > Show on Order Form:</td>
                   <td >
                       <input type="checkbox" name="PromosShowOnOrderForm" id="PromosShowOnOrderForm" value="1"  <?php if ($CatRecords[0]['PromosShowsOnOrderForm'] == 1) { ?> checked="checked"<?php } ?> />
                   </td>
                 </tr>
	  <tr>
                   <td  align="left"colspan="3">&nbsp; </td>
      </tr>
    </table>
	 </div>
	 </div>
    <div align="center" style="float: left; margin-top:10px; bottom:0; background: none repeat scroll 0 0 #FFFFFF;  width: 100%;">
 	<input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()"/>
	
  </form>

