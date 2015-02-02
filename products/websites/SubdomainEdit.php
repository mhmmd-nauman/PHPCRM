<?php  
include "../../lib/include.php";
$objSubDomains = new SubDomains();

$objClient = new Clients();
$HubopususerRows = $objSubDomains->GetAllSubDomains(" SubDomains.ID = '".$_REQUEST['id']."'",array("SubDomains.*"));


$Members_array = $objClient->GetAllClients(CLIENTS.".HasDeleted = 0 ORDER BY ".CLIENTS.".CompanyName ASC ",array("DISTINCT  ".CLIENTS.".ID","".CLIENTS.".FirstName","".CLIENTS.".Surname","".CLIENTS.".Email,".CLIENTS.".CompanyName,Address"));
 ?>


<script>
  $(document).ready(function() {
    
	$("#hubopususersaledate").datepicker();

 });
 
 </script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.9.1.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  

<form action="SubDomains.php?id=<?php echo $_REQUEST['id'];?>&Task=Update" method="post" enctype="multipart/form-data" name="frmSample"  onsubmit="return ValidateForm(this);">

<?php if($_REQUEST['flag']=='update'){?>
   <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
    <tr>
  <td id="message_success">Sub Domain updated successfully!
	   <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
   </table>
   <?php }?>
   
    <div class="container1">
	 <div style="padding-top:10px;"></div>
	<div id="tabs">
			
		<div id="tabs-1">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
     
     <tr valign="top">
       <td colspan="4" id="tabsubheading"  >Sub Domain Information </td>
       </tr>
     <tr valign="top">
       <td colspan="4"  >&nbsp;</td>
     </tr>
     <tr valign="top">
       <td  > Date Created:</td>
       <td><input name="sentdate" type="text"  id="hubopususersaledate" value="<?php echo date("m/d/Y",strtotime($HubopususerRows[0]['Created']));?>" style="width:230px;" class="product" /></td>
       <td align="right">Client:</td>
       <td><select name="SelectedMember" style="width:230px;" class="product">
         <option value="0"  <?php if($HubopususerRows[0]['ClientID']== 0)echo"selected";?>>Non Client</option>
         <?php foreach($Members_array as $member){?>
         <option value="<?php echo $member['ID'];?>" <?php if($member['ID']== $HubopususerRows[0]['ClientID']){echo"selected";}?>><?php echo $member['CompanyName'];?>
           <?php if(!empty($member['CompanyName'])){?>
           &nbsp;&nbsp;-&nbsp;&nbsp;
           <?php }?>
           <?php  echo $member['FirstName']." ".$member['Surname']. "( ".$member['Email']." )";?>
           </option>
         <?php }?>
       </select></td>
     </tr>
     
     <tr>
       <td >Temp Sub Domain:</td>
       <td  >
           <input name="domain" type="text" value="<?php echo $HubopususerRows[0]['SubDomain'];?>" style="width:230px;"/></td>
       <td>
           Db Password:
       </td>
       <td>
           <input name="dbpassword" type="text" value="<?php echo $HubopususerRows[0]['DbPassword'];?>" style="width:230px;">
       </td>
     </tr>
     
     
     <tr>
       <td >Real Sub Domain Name: </td>
       <td><input name="username" type="text" value="<?php echo $HubopususerRows[0]['UserName'];?>" style="width:230px;"/></td>
       <td> </td>
       <td> </td>
     </tr>
     
	 
	
       <td >Status:</td>
       <td>
	   <select name="Status" class="product" style="width:230px;">
	   		 <option value="1" <?php if($HubopususerRows[0]['Status']==1)echo"selected";?>>Available</option> 
             <option value="2" <?php if($HubopususerRows[0]['Status']==2)echo"selected";?>>Canceled</option>
             <option value="3" <?php if($HubopususerRows[0]['Status']==3)echo"selected";?>>Not Ready</option>
			 <option value="4" <?php if($HubopususerRows[0]['Status']==4)echo"selected";?>>Assigned</option>
			 <option value="5" <?php if($HubopususerRows[0]['Status']==5)echo"selected";?>>Domain Assigned</option>
       </select></td>
	   
       <td ></td>
	   
       <td >
           
       </td>
     </tr>
   
	 <tr>
       <td >Notes:</td>
       <td colspan="3" ><label>
         <textarea name="Notes" rows="8" cols="82"><?php echo $HubopususerRows[0]['Notes'];?></textarea>
       </label></td>
     </tr>
     </table>
		
		</div>
		
		
		</div>
<div style="height:25px;">&nbsp;</div>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
       <td colspan="2">
	   <div align="center">
	   <input type="submit" name="Submit" value="Save Changes"  class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()"  />
	   <input type="button" id="deleteID" name="delete" value="Delete" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return deletedataPost('<?php echo $_REQUEST['id'];?>')" />
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
 
 
<script type="text/javascript">
initTabs('dhtmlgoodies_tabView1',Array('Domain Information','Fulfilled','Revision','Questions','Setup'),0,"","");
</script>

<script type="text/javascript">
   
                function SendRevEmail1(){
					var notes =$('#notes').val();
					     $.ajax({
						type:"post",
						url:"WebSites.php",
	
						data:"id="+<?php echo $HubopususerRows[0]['ID']; ?>+"&Task=AjaxSaveNauman&RevisionNote="+notes,
						success:function(data){
							alert("Revision updated!"+data);
					   
					   }
				  });

         }
		 
		</script>

<script type="text/javascript">
   
    function confirmation() {
	var answer = confirm("Do you want to delete this record?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
</script>