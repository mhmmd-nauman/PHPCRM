<?php 
include "../../lib/include.php";
$objWebSiteServer = new WebSiteServer();
$WebsiteServer = $objWebSiteServer->GetAllWebSiteServer(" HasDeleted = 0 ",array("*"));
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
<form action="WebSites.php?Task=add" method="post" enctype="multipart/form-data" name="form1" target="_top">
<div style="padding-top:10px;"></div>
 <div id="tabs">
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
    
     <tr>
       <td colspan="3" id="tabsubheading">Domain Informantion</td>
     </tr>
     <tr>
       <td colspan="3" >&nbsp;</td>
     </tr>
     <tr>
       <td width="30%">Domain:</td>
       <td ><input name="domain" type="text"  class="product" value="ezb" />&nbsp;mos,ezb,etc</td>
       
       <td width="30%">&nbsp;</td>
     </tr>
     <tr>
       <td >Domain From:</td>
       <td ><input name="domain_from" type="text"  class="product" />&nbsp;2550 etc</td>
     </tr>
     <tr>
       <td >Domain To:</td>
       <td ><input name="domain_to" type="text"  class="product" />&nbsp;2570 etc</td>
     </tr>

  
     <tr>
       <td >User Password: </td>
       <td><input name="userpassword" type="text" value=""  class="product"/></td>
     </tr>
     <tr>
       <td >Db Password:</td>
       <td><label for="textfield"></label>
       <input type="text" name="dbpassword" id="textfield"  class="product"/></td>
     </tr>
	 
	 
     <tr>
       <td >Hosted On:</td>
       <td > 
           
           <select name="HostedOn" class="product" style="width:230px;">
               <option value="0" <?php if($HubopususerRows[0]['HostedOnID']==0)echo"selected";?>>No Server</option>         
               <?php foreach($WebsiteServer as $server){?>
                    <option value="<?php echo $server['id'];?>" <?php if($HubopususerRows[0]['HostedOnID']==0)echo"selected";?>><?php echo $server['name'];?></option>
               <?php }?>       
            </select>
       </td>
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
	   <input type="submit" name="Submit" value="Add Temp Domains" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
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
