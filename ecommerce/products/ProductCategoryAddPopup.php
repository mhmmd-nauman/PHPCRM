<?php 
require_once "../../lib/include.php";
$utilObj = new util();
$accessflag!='no';
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
if(!empty($_REQUEST['catid'])){
 $strWhere='ID='.$_REQUEST['catid'].'';
 $categoryRec=$utilObj->getSingleRow('ProductCategory', $strWhere);
}

?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>

    <!-- Tabs and button code -->
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script> 
<div class="Popupspace"></div>

  <form name="form1" method="post" action="ProductCategoryList.php" target="_parent">
    <input name="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="id" type="hidden" value="<?php echo $_REQUEST['catid'];?>" size="40">

<div class="containerpopup" >
	<div class="subcontainer"> 
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center">
      
      <tr>
        <td colspan="3" id="tabsubheading">Category Information </td>
        </tr>
      <tr>
        <td colspan="3" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft">Category Name:</td>
        <td id="tdmiddle"><input name="Category_Name" type="text" value="<?php echo $categoryRec['CategoryName']; ?>" class="product"></td>
		<td id="tdright" >&nbsp;</td>
		
      </tr>
      <tr>
        <td colspan="3" height="5" id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
      <tr>
        <td >Description:</td>
        <td><textarea name="Cat_Description" class="product"   style="height:150px;"><?php echo $categoryRec['Description']; ?></textarea></td>
		 <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"  id="errorEmail" style="color:#FF0000;" ></td>
      </tr>
    </table>
	
	</div>
	</div>
	<div style="height:25px;">&nbsp;</div>
    <div align="center" style="float: left; margin-top:10px; bottom:0; background: none repeat scroll 0 0 #FFFFFF;  width: 100%;">
	   <input type="submit" name="Submit" value="Save" onclick="return spon_check()"  class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"/>
	   <?php if($_REQUEST['Task']=='addWebsiteQuestion')
	   {?>
	   <input type="button" name="Submit" value="Go Back" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="goBack()" />
	   <?php }?>
    </div>
	
  </form>

