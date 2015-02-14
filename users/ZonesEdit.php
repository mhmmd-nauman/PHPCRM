<?php 
include "../lib/include.php";
$objzone = new Zones();

if(isset($_REQUEST['ID'])){
    $Task="Update";
	

}else{
    $Task="AddNew";
}

//print_r($Zones_array);
if($Zones_array[0]['Created']=="1969-12-31 12:00:00"){
    $Created = "";
}elseif($_REQUEST['Task']=='Add'){
		$Created = "";
		} else{
    $Created = date("m/d/Y",strtotime($Zones_array[0]['Created']));
}

$Zones_array = $objzone->GetAllZones("ID = '".$_REQUEST['ID']."'",array("*")); 
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>

<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  
<script type="text/javascript" language="javascript">
function ValidateForm(){
return true;
if ((document.frmSample.FName.value==null)||(document.frmSample.FName.value=="")){
		alert("Please Enter Name")
		document.frmSample.FName.focus()
		return false;
	}
if ((document.frmSample.Email.value==null)||(document.frmSample.Email.value=="")){
		alert("Please Enter Email")
		document.frmSample.Email.focus()
		return false;
	}


}
	
	
  $(document).ready(function() {
    
	$("#datepicker").datepicker();
	 });
 
  </script>
 
 <style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 12px;}
</style>
<form action="Zones.php?ID=<?php echo $_REQUEST['ID'];?>&Task=<?php echo $Task;?>" target="_top"method="post"  enctype="multipart/form-data" name="frmSample" onSubmit="return ValidateForm(this);">

<?php if($_REQUEST['flag']=='update'){?>
   <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
    <tr>
  <td  colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;System settings updated successfully!
	   <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
   </table>
   <?php }?>
   <div class="containerpopup">
 			<div class="subcontainer">	
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
                       
                     <tr >
                   <td   colspan="3">&nbsp;  </td>
                 </tr>
                 
			      <tr>
                 <td>Zone Name:</td>
                 <td><input type="text" name="Zname" value="<?php echo $Zones_array[0]['Name'];?>"></td>
                  <td >&nbsp;</td>
                   </tr>
                   <tr>
                   <td>Time Zone:</td>
                   
                   <td>
                       <?php
                       $TimeZonesArray = $objzone->GetAllTimeZones("1", array("*"));
                       ?>
                       <select id="zone" name="ZoneTime">
                   		 <option value="">Please Select One</option>

                   <?php foreach($TimeZonesArray as $time_zone_row){?>
                                 <option value="<?php echo $time_zone_row['ZoneName'];?>" <?php if($Zones_array[0]['ZoneTime']==$time_zone_row['ZoneName'])echo"selected";?>><?php echo $time_zone_row['ZoneName'];?></option>
                   <?php }?>
                       </select>
                      </td>
                   </tr> 
                    <tr>
                    <td>Server time +/-:</td>
                    <td><input type="text" name="adjusthours" value="<?php echo $Zones_array[0]['AdjustHours'];?>" /></td>
                    </tr>
                      
                  
                   </table>
                
	</div>
		</div>	   
        <div style="height:25px;">&nbsp;</div>
		
		
		
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
       <td colspan="3">
 
	   <div align="center">
	   <input type="submit" name="" value="Save" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onClick="return spon_check()" style="height:29px;"/>
<input type="hidden" name="by"  value="<?php echo $_SESSION['Member']['ID'];?>"/>
<input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" />
<input type="hidden" name="postback" value="1" />
		</div>																															
        </td>
</tr>
</table>

</form>
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

