<?php 
$signup_page = 1;
$login_page = 1;
include "../lib/include.php";

$objusers = new Users();
$ObjGroup = new Groups();
$objcompany = new Company();
$objzone = new Zones();
$group_array = $ObjGroup->GetAllGroups(" 1 ORDER BY Sort",array("*"));

$Users_array = $objusers->GetAllUsers(USERS.".ID = '".$_REQUEST['id']."'",array(USERS.".*")); 
if($_REQUEST['Task'] == 'Add'){
	$Created = "";
} else{
	$Created = date("m/d/Y",strtotime($Users_array[0]['Created']));
}
if(isset($_REQUEST['id'])){
    $Task = "Update";
    $group_Users = $objusers->GetAllUserWithGroup(" Users.ID = '".$_REQUEST['id']."'",array("Group_Users.GroupID"));

} else{
    $Task="add";
    $member_in_gropus[] = 5;
}
foreach((array)$group_Users as $group_row){
	$Users_in_gropus[] = $group_row['GroupID'];
}

$Company_array = $objcompany->GetAllCompany("1 ORDER BY ID ASC",array("*"));

$Zones_array = $objzone->GetAllZones("1 ORDER BY Created ASC",array("*")); 
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css">
 <link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/styles.css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Signup Form</title>
<script type="text/javascript" language="javascript">
function ValidateForm(){
	if((document.frmSample.UserFName.value == null) || (document.frmSample.UserFName.value == "")){
		alert("Please Enter Name");
		document.frmSample.UserFName.focus();
		return false;
	}
	if((document.frmSample.UserSureName.value == null) || (document.frmSample.UserSureName.value == "")){
		alert("Please Enter Surname");
		document.frmSample.UserSureName.focus();
		return false;
	}
	if((document.frmSample.Userpassword.value == null) || (document.frmSample.Userpassword.value == "")){
			alert("Please Enter The Password");
			document.frmSample.Userpassword.focus();
			return false;
		}
			
	if((document.frmSample.Userpassword.value != document.frmSample.Retypepassword.value)){
		alert("Password does not match");
		document.frmSample.Userpassword.focus();
	}
	return false;
}

$(document).ready(function() {
	$("#datepicker").datepicker();
	$(".message_error").fadeOut(5000);
	$(".message_success").fadeOut(5000);
});

$(function() {
	$( "#tabs" ).tabs();
});
</script>  
<style type="text/css">
.heading{
	padding:4px 4px 4px 4px;
	background-color:#EEEEEE;
	font-size:14px;
	font-weight:bold;
}
table tr td {
	font-size: 13px;
}
</style>
</head>
<body>
    <form action="<?php echo SITE_ADDRESS;?>users/Users.php?Task=usersignup"method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">
        <?php
        if($_REQUEST['flag'] == 'email_exist'){ ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="message_error">Email Already Exists. Please login!</td>
                </tr>
            </table> 
        <?php }
        if($_REQUEST['flag'] == 'usersignup'){?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <td class="message_success">User Added Successfully!</td>
                </tr>
            </table> 
        <?php } ?>
        <div class="containerpopup">
            <div class="subcontainer">
                <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center" class="order_details">
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td id="tdleft">Company:</td>
                        <td id="tdmiddle">
                            <select name="UserCompany" class="product">		 
                            <?php
                            foreach((array)$Company_array as $comp){ ?>
                                <option value="<?php echo $comp['ID'];?>"<?php if($comp['ID'] == $Users_array[0]['CompanyID']){ echo "selected"; }?>><?php echo $comp['CompanyName']; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                        <td id="tdright">&nbsp;</td>
                    </tr>
                    <tr>
                    <td>Time Zone:</td>
                    <td>
                    <?php
                    $TimeZonesArray = $objzone->GetAllTimeZones("1", array("*"));
                    ?>
                    <select id="zone" name="ZoneTime" class="product">
                        <option value="">Please Select One</option>
                        <?php 
                        foreach((array)$TimeZonesArray as $time_zone_row){ ?>
                        <option value="<?php echo $time_zone_row['ZoneName'];?>" <?php if($Zones_array[0]['ZoneTime'] == $time_zone_row['ZoneName']) echo "selected"; ?>><?php echo $time_zone_row['ZoneName'];?></option>
                    <?php } ?>
                    </select>
                    </td>
                </tr> 
                    <tr valign="top">
                    <td>First Name:</td>
                    <td><input name="UserFName" id="UserFName" type="text" value="<?php echo $Users_array[0]['FirstName'];?>" class="product" /></td>
                    <td>&nbsp;</td>
                </tr>
                    <tr>
                        <td>Last Name:</td>
                        <td><input name="UserSureName" id="UserSureName" type="text" value="<?php echo $Users_array[0]['LastName'];?>" class="product" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input name="UserEmail" id="UserEmail" type="text" value="<?php echo $Users_array[0]['Email'];?>" class="product" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input name="Userpassword"  id="Userpassword" type="password" value="<?php echo $Users_array[0]['Password'];?>" class="product" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Retype Password:</td>
                        <td><input name="Retypepassword" id="Retypepassword" type="password" value="<?php echo $Users_array[0]['Password'];?>" class="product" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    
        <div style="height:25px;">&nbsp;</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="3">
                    <div align="center">
                        <input type="submit" name="Submit" value="Create Account" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"/>
                        <input type="hidden" name="oldimage"  value=""/>
                        <input type="hidden" name="postback" value="1" />
                    </div>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>