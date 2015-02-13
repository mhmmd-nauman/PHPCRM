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
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/styles.css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/style.css" />
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script> 

<div>
  <form action="PromotionalCodeList.php?catid=<?php echo $_REQUEST['catid']; ?>&Task=<?php echo $Task;?>" method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);" target="_parent" >
    <input name="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="id" type="hidden" value="<?php echo $_REQUEST['catid'];?>" size="40">
	  <div class="container1">
		
	<div id="tabs">
		      
		<div id="tabs-1">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center">
      
	   <tr id="headerbarpopup">
                   <td  align="left"colspan="2" class="tabsubheading"> Promos Information </td>
            </tr>
			<tr valign="top">
                   <td  align="left"colspan="2">&nbsp; </td>
                 </tr>
      <tr>
        <td align="left"> Name:</td>
        <td><input name="Category_Name" type="text" value="<?php echo $CatRecords[0]['Category_Name']; ?>" class="product"></td>
      </tr>
      <tr>
        <td colspan="2" height="5" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="left" valign="top">Description:</td>
        <td><textarea name="Cat_Description"  style="height:170px;" class="product" ><?php echo $CatRecords[0]['Description']; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td align="left">Price $ </td>
        <td align="left"><input name="Price" type="text" value="<?php echo $CatRecords[0]['Price']; ?>" class="product"></td>
      </tr>
	  
	  <tr valign="top">
                   <td  align="left"colspan="2">&nbsp; </td>
                 </tr>
    </table>
	  </div>
	   <div align="center" style="float: left; margin-top:10px; bottom:0; background: none repeat scroll 0 0 #FFFFFF;  width: 100%;">
	   <input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"  />
  </div>
  </div>
	
  </form>
</div>
