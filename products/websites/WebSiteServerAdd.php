<?php 
include "../../lib/include.php";
$objWebSiteServer = new WebSiteServer();
if($_REQUEST['Task'] == 'Update'){
    $id = $_REQUEST['id'];
    $Websites = $objWebSiteServer->GetAllWebSiteServer(" id = $id",array("*"));
    $Task="Update";
}else{
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
<form action="WebSiteServer.php?Task=<?php echo $Task;?>&id=<?php echo $id;?>" method="post"  name="form1" target="_top">
<div style="padding-top:10px;"></div>
 <div id="tabs">
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
    
     <tr>
       <td colspan="3" id="tabsubheading">Account Information</td>
     </tr>
     <tr>
       <td colspan="3" >&nbsp;</td>
     </tr>
     <tr>
       <td width="30%">Name:</td>
       <td ><input name="name" type="text"  class="product" value="<?php echo $Websites[0]['name'];?>" />&nbsp;</td>
       
       <td width="30%">&nbsp;</td>
     </tr>
     <tr>
       <td width="30%">IP:</td>
       <td ><input name="IP" type="text"  class="product" value="<?php echo $Websites[0]['ip'];?>" />&nbsp;</td>
       
       <td width="30%">&nbsp;</td>
     </tr>
     
     <tr>
       <td width="30%">Internal IP:</td>
       <td ><input name="internal_ip" type="text"  class="product" value="<?php echo $Websites[0]['internal_ip'];?>" />&nbsp;</td>
       
       <td width="30%">&nbsp;</td>
     </tr>
     
     <tr>
       <td >User Name:</td>
       <td ><input name="username" type="text"  class="product" value="<?php echo $Websites[0]['username'];?>" />&nbsp;</td>
     </tr>
     

  
     <tr>
       <td >User Password: </td>
       <td><input name="password" type="text"  class="product" value="<?php echo $Websites[0]['password'];?>"/></td>
     </tr> 
     <tr>
       <td >Is Reseller:</td>
       <td > <select name="isreseller" class="product" >
               <option value="1" <?php if($Websites[0]['isreseller'] == 1)echo"selected";?> >Yes</option>
	       <option value="0" <?php if($Websites[0]['isreseller'] == 0)echo"selected";?> >No</option>
                        
      </select></td>
     </tr>
     <tr>
       <td >Is Default Account to Create Web sites:</td>
       <td > <select name="isDefaultReseller" class="product" >
               <option value="1" <?php if($Websites[0]['isDefaultReseller'] == 1)echo"selected";?> >Yes</option>
	       <option value="0" <?php if($Websites[0]['isDefaultReseller'] == 0)echo"selected";?> >No</option>
                        
      </select></td>
     </tr>
	 
	 
     <tr>
       <td >&nbsp;</td>
       <td ><label></label></td>
     </tr>
     
     <tr>
      <td colspan="2"><input type="hidden" name="postback" value="1" />        <div align="center"></div></td>
      </tr>
   </table>
  </div>
  <div style="height:25px;">&nbsp;</div>
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
       <td colspan="2">
	   <div align="center">
	   <input type="submit" name="Submit" value="Save" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
	   </div>
																																		<input type="hidden" name="oldimage"  value=""/>
           <input type="hidden" name="postback" value="1" />
		
	
  <input type="hidden" name="checkupdate" value="1">
  <input type="hidden" name="image" value="" />
  <input type="hidden" name="siteasigndate" value="<?php echo $HubopususerRows[0]['SiteAsignDate'];?>" />
 <?php //echo $HubopususerRows[0]['SiteAsignDate'];?>
 </td>
 </tr>
 </table>
	  
    
</form>
