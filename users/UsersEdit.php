<?php include "../lib/include.php";

$objusers = new Users();
$ObjGroup = new Groups();
$objcompany = new Company();
$objzone = new Zones();
//echo $_SESSION['Member']['ID'];
$group_array = $ObjGroup->GetAllGroups(" 1 ORDER BY Sort",array("*"));

$Users_array = $objusers->GetAllUsers(USERS.".ID = '".$_REQUEST['id']."'",array(USERS.".*")); 
//print_r($Users_array);
if($_REQUEST['Task']=='Add'){
    $Created = "";
} else{
    $Created = date("m/d/Y",strtotime($Users_array[0]['Created']));
}
if(isset($_REQUEST['id'])){
    $Task="Update";
    $group_Users = $objusers->GetAllUserWithGroup(" Users.ID = '".$_REQUEST['id']."'",array("Group_Users.GroupID"));

} else{
    $Task="add";
    $member_in_gropus[]=5;
}
 foreach((array)$group_Users as $group_row){
     $Users_in_gropus[]= $group_row['GroupID'];
 }
if($_REQUEST['Task']=='updateprofile'){
  
  $Users_array = $objusers->GetAllUsers(USERS.".ID = '".$_SESSION['Member']['ID']."'",array(USERS.".*"));
  $Created = date("m/d/Y",strtotime($Users_array[0]['Created']));
//print_r($Users_array);
  $task=$_REQUEST['Task']=='updateprofile';
  //$Task="Update";
}	

$Company_array = $objcompany->GetAllCompany("1 ORDER BY ID ASC",array("*"));

$Zones_array = $objzone->GetAllZones("1 ORDER BY Created ASC",array("*")); 
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>


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
	</script>
<script>
function displayDate() {

   var now = new Date();

   var day = ("0" + now.getDate()).slice(-2);
   var month = ("0" + (now.getMonth() + 1)).slice(-2);
   var today = now.getFullYear() + "-" + (month) + "-" + (day);

   document.getElementById("revision").value = today;
}
</script>


<script>
  $(document).ready(function() {
	$("#datepicker").datepicker();
  });
 
  </script>

<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  
<style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 13px;}
</style>
<form action="Users.php?id=<?php echo $_REQUEST['id'];?>&Task=<?php echo $Task;?>" target="_top"method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">

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
 		
	</table>
    <div class="subcontainer">
		 <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
                       <tr >
                   <td  colspan="3" id="tabsubheading" > Basic Information </td>
                 </tr>
                     <tr >
                   <td   colspan="3">&nbsp;  </td>
                 </tr>
				 <tr >
            <td id="tdleft">Company:</td>
            <td id="tdmiddle"><select name="Company" class="product">
	  <?php // echo $taskatachedid=$ProductRec['TaskAttached'];?>
		 <option value="">Please Select One</option>
		 <?php foreach((array)$Company_array as $comp){?>
		 	<option value="<?php echo $comp['ID'];?>"<?php if($comp['ID']==$Users_array[0]['CompanyID']){echo "selected"; }?>><?php echo $comp['CompanyName'];?></option>
			<?php } ?>
		 </select></td>
		 <td id="tdright">&nbsp;</td>
          </tr>
           <tr >
            <td id="tdleft">User Time Zone:</td>
            <td id="tdmiddle"><select name="timezone" class="product">
	  <?php // echo $taskatachedid=$ProductRec['TaskAttached'];?>
		 <option value="">Please Select One</option>
		 <?php foreach((array)$Zones_array as $Zones){?>
		 	<option value="<?php echo $Zones['ID'];?>"<?php if($Zones['ID']==$Users_array[0]['ZoneID']){echo "selected"; }?>><?php echo $Zones['Name'];?></option>
			<?php } ?>
		 </select></td>
		 <td id="tdright">&nbsp;</td>
          </tr>
                 <tr >
                   <td> Date Created:</td>
                   <td><input name="createddate" type="text" value="<?php echo $Created ;?>"   id="datepicker" class="product"/></td>
				   <td >&nbsp;</td>
                 </tr>
				 
                 <tr valign="top">
                   <td   > First Name:</td>
                   <td ><input name="fName" type="text" value="<?php echo $Users_array[0]['FirstName'];?>" class="product" /></td>
				   <td >&nbsp;</td>
                 </tr>
                     <tr >
                   <td   > Last Name:</td>
                   <td ><input name="sureName" type="text" value="<?php echo $Users_array[0]['LastName'];?>" class="product" /></td>
				   <td >&nbsp;</td>
                 </tr>
                  <tr >
                   <td   > Email:</td>
                   <td ><input name="Email" type="text" value="<?php echo $Users_array[0]['Email'];?>" class="product" /></td>
				   <td >&nbsp;</td>
                 </tr>
                     <tr >
                   <td   > Password:</td>
                   <td ><input name="password" type="text" value="<?php echo $Users_array[0]['Password'];?>" class="product" /></td>
				   <td >&nbsp;</td>
                     </tr>
                 
                
                 <?php if($_REQUEST['Task']!='updateprofile'){?>
                  <?php 
					if(in_array(2, (array)$_SESSION['user_in_groups']) || in_array(3, (array)$_SESSION['user_in_groups'])){
					 ?>
                 <tr>
                         <td>
                             Users Groups :
							 </td>
							 <td>
                               <div id="ScrollCB" style="height:80px;width:100%;overflow:auto;border:1px solid #373737; float:left; margin-right:117px;border-radius:5px;border:solid 1px #DBDBDB; padding-left:5px; padding-top:5px;">

                   <?php
				   
				   if(in_array(3, (array)$_SESSION['user_in_groups'])){
					   ?>
                       
                       <input type="checkbox" 
                       id="MemberGroup<?php echo $group_array[1]['ID'];  ?>" 
                       value="<?php echo $group_array[1]['ID'];  ?>" 
                       name="MemberGroup[]" 
                       <?php if(in_array($group_array[1]['ID'],(array)$Users_in_gropus)){echo"checked";};?> />
                      
                      <label for="MemberGroup<?php echo $group_array[1]['ID'];?>"> 
					  <?php //echo $group_array[16]['Title']; ?>
                      Company Administrator
                       </label>
                       <br />
					   <input type="checkbox" 
                       id="MemberGroup<?php echo $group_array[2]['ID'];  ?>" 
                       value="<?php echo $group_array[2]['ID'];  ?>" 
                       name="MemberGroup[]" 
                       <?php if(in_array($group_array[2]['ID'],(array)$Users_in_gropus)){echo"checked";};?> />
                      
                      <label for="MemberGroup<?php echo $group_array[2]['ID'];?>"> 
					  <?php //echo $group_array[16]['Title']; ?>
                      Sales Agent
                       </label>
     
					   <?php
				   } else {
                   for($i=0;$i<count((array)$group_array);$i++){
                        ?>
                         <input type="checkbox" id="MemberGroup<?php echo $group_array[$i]['ID'];  ?>" value="<?php echo $group_array[$i]['ID'];  ?>" name="MemberGroup[]"  <?php if(in_array($group_array[$i]['ID'],(array)$Users_in_gropus)){echo"checked";};?> >
                                <label for="MemberGroup<?php echo $group_array[$i]['ID'];?>"> <?php //echo $group_array[$i]['ID'];?> <?php echo $group_array[$i]['Title']; ?>                                                    </label>
                                <br />
                                <?php } }?>
                      </div>							  </td>
					  <td >&nbsp;</td>
                    </tr>
                 <?php } ?>
                 <?php } ?>
                    <tr >
                        <td  colspan="3">&nbsp;  </td>
                    </tr>
                <?php if(!in_array(16,(array)$Users_in_gropus) || true){?>
                    
                    
                  
                 <tr>
                   <td width="340" id="tabsubheading"   colspan="3"> Contact Information </td>
                 </tr>
                     <tr >
                   <td width="340"  colspan="3">&nbsp;  </td>
                 </tr>

                     <tr>
                   <td >Best Phone: </td>
                   <td ><input style="width:192px;" name="phone" type="text" id="phone" value="<?php echo $Users_array[0]['Phone'];?>" onkeyup="displayPhone()" class="product"/>
                   &nbsp;Ext:&nbsp;
                   <input name="extension" style="width:80px;" type="text" value="<?php echo $Users_array[0]['Phone_Ext'];?>" class="product"/>
                   </td>
                   
				   <td >&nbsp;</td>
                 </tr>
                 <tr>
                   <td >Alternate Phone: </td>
                   <td ><input name="alternatephone" id="alternatephone" type="text" value="<?php echo $Users_array[0]['AlternatePhone'];?>" onkeyup="displayAlternatePhone()"class="product"/></td>
				   <td >&nbsp;</td>
                 </tr>
                     <tr>
                   <td > Skype Name: </td>
                   <td ><input name="skypename" type="text" value="<?php echo $Users_array[0]['SkypeName'];?>"class="product"/></td>
				   <td >&nbsp;</td>
                 </tr>
                     <tr>
                   <td > Address 1: </td>
                   <td ><input name="address" id="address" type="text" value="<?php echo $Users_array[0]['Address'];?>" class="product" onkeyup="displayAddress()"/></td>
				   <td >&nbsp;</td>
                 </tr>
                     <tr>
                   <td > Address 2:</td>
                   <td ><input name="Address2" id="address32" type="text" value="<?php echo $Users_array[0]['Address2'];?>" class="product" onkeyup="displayAddress2()"/></td>
				   <td >&nbsp;</td>
                 </tr>
                     <tr>
                   <td > City: </td>
                   <td ><input name="city" type="text" value="<?php echo $Users_array[0]['City'];?>" class="product"/></td>
				   <td >&nbsp;</td>
                 </tr>
                     <tr>
                       <td >State: </td>
                       <td ><input name="state" type="text" value="<?php echo $Users_array[0]['State'];?>" class="product"/></td>
					   <td >&nbsp;</td>
                     </tr>
                     <tr>
                   <td >ZIP Code: </td>
                   <td ><input name="ZipCode" type="text" value="<?php echo $Users_array[0]['ZipCode'];?>" class="product"/></td>
				   <td >&nbsp;</td>
                 </tr>
				 
                 <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                   <?php }
                  ?>
                     <tr>
                   <td  colspan="3" id="tabsubheading">Profile Image </td>
                 </tr>
                 <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                      <tr>
                   <td > Profile Image:</td>
                   <td ><input type="file" name="ProfileImg" id="ProfileImg"></td>
				   <td >&nbsp;</td>
                 </tr>
                 <tr>
                   <td></td>
                   <td><img src="../<?php if(!empty($Users_array[0]['ProfileImage'])){echo $Users_array[0]['ProfileImage'];} else {echo "../images/my-profile.gif";}?>" width="130" height="130" /></td>
				   <td >&nbsp;</td>
                 </tr>
                 <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                     </table>
		</div>

	   <div style="height:25px;">&nbsp;</div>
	   <table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
       <td colspan="3" style=" border: none;">
        <div align="center" class="bottom_fixed">
			<?php if($_REQUEST['Task']=='updateprofile'){?>
            <input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()"/>
            <input type="hidden" name="updateProfile"  value="updateProfile"/>
            <?php }else{?>
            <input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()"/>
            <?php } ?>
            <input type="hidden" name="postback" value="1" />
            <input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" />
        </div>
       </td>
		</tr>
		</table>
</form>

