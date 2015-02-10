<?php include "../lib/include.php";
$ObjGroup = new Groups();
$gid = $_REQUEST['id'];
$GroupsRows = $ObjGroup->GetAllGroups("ID = '$gid'",array("*"));
$objcompany = new Company();
$Company_array = $objcompany->GetAllCompany("1 ORDER BY ID ASC",array("*")); 


if($_REQUEST['Task']=="Add")
{

if(empty($_REQUEST['salepersonflag'])){
     $flag=$_REQUEST['salepersonflag']=0;
 }else{
     $flag=$_REQUEST['salepersonflag'];
 }

$code = strtolower($_REQUEST['Title']);
    $grpcode = str_replace(' ', '-', $code);
    $added= $ObjGroup->InsertGroup(array(
                                           "ClassName"=>'Group',
				          				   "Created"=>date("Y-m-d H:i:s"),
                                           "LastEdited"=>date("Y-m-d H:i:s"),
                                           "Title"=>$code,
				           				   "Description"=>$_REQUEST['Description'],
                                           "IsGroupSale"=>$flag,
                                           "Code"=>$grpcode,
                                           "SiteType"=>$_REQUEST['SiteType'],
                                           "Membership"=>$_REQUEST['membership_status'],
										   "CompanyAttached"=>$_REQUEST['CompanyAttached']
                                           ));
										   
	 header("Location:Groups.php?flag=add_Group");
	 				   
}
if($_REQUEST['Task']=="Update1")
{
$gid = $_REQUEST['id'];
     $_REQUEST['Program']."*";

if(empty($_REQUEST['salepersonflag'])){
     $flag=$_REQUEST['salepersonflag']=0;
 }else{
     $flag=$_REQUEST['salepersonflag'];
 }

 $updated= $ObjGroup->UpdateGroup("ID = '$gid' ",array(
                                               "Title"=>$_REQUEST['Title'],
												"Description"=>$_REQUEST['Description'],
                                                "IsGroupSale"=>$flag,
												"CompanyAttached"=>$_REQUEST['CompanyAttached'],
                                           )); 

	     header("Location:Groups.php?flag=update");	

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Members</title>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">

<script type="text/javascript" language="javascript">

function ValidateForm(){

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

</head>

<body>
<?php 
if($_REQUEST['id']!=''){
	$task = 'Update1';
}else{
	$task = 'Add';
}

?>  
<!-- Tabs and button code -->
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  

<!-- End of Tabs and button code --> 
   
<form action="?Task=<?php echo $task;?>&id=<?php echo $_REQUEST['id'];?>" method="post" target="_top" enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">
      <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center">
        <tr>
          <?php if($_REQUEST['flag']=='update') {?>
          <td style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="2"><?php echo " Record has been Updated Sucessfully! "; ?> </td>
          <?php }  ?>
        </tr>

      </table>
	<div class="Popupspace"></div>
    <div class="containerpopup">
        <div class="subcontainer">
      <table width="100%" height="100%" border="0" cellspacing="1" cellpadding="1">

        
		<tr >
				  <td colspan="3" id="tabsubheading" >Group Information</td>
		</tr>
		<tr >
		  <td colspan="3"  >&nbsp;</td>
	    </tr>
        <tr >

          <td id="tdleft">Title:</td>
          <td id="tdmiddle" ><input name="Title" type="text" value="<?php echo $GroupsRows[0]['Title'];?>" class="product"></td>
		  <td id="tdright">&nbsp;</td>
        </tr>
        <tr >
          <td colspan="3"  >&nbsp;</td>
        </tr>

        <tr>

          <td>Description:</td>

        <td><textarea name="Description" class="product" style="height:170px;"><?php echo $GroupsRows[0]['Description'];?></textarea>  </td>
		<td>&nbsp;</td>
		    </tr>
          <tr >
            <td  >Company</td>
            <td  ><select name="CompanyAttached" class="product">
	  <?php // echo $taskatachedid=$ProductRec['TaskAttached'];?>
		 <option value="">Please Slect One</option>
		 <?php foreach((array)$Company_array as $comp){?>
		 	<option value="<?php echo $comp['ID'];?>"<?php if($comp['ID']==$GroupsRows[0]['CompanyAttached']){echo "selected"; }?>><?php echo $comp['CompanyName'];?></option>
			<?php } ?>
		 </select></td>
		 <td >&nbsp;</td>
          </tr>
          <tr >
          <td colspan="3"  >&nbsp;</td>
        </tr>
           <tr >

          <td >Is Sale Person:</td>

          <td><input type="checkbox" name="salepersonflag" value="1"<?php if($GroupsRows[0]['IsGroupSale']==1){echo"checked";}?>/></td>
		  <td >&nbsp;</td>
        </tr>
            
        <?php if($_REQUEST['id']==''){?>
        
        <?php }?>
        
       <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
      </table>
	  </div>
</div>
<div style="height:25px;">&nbsp;</div>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
       <td  colspan="3">
	   <div align="center">
		<input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()" />
			<input type="hidden" name="oldimage"  value=""/>
          	 <input type="hidden" name="postback" value="1" />
        	<input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" /></div>
       </td>
		</tr>
		</table>

</body>
</html>

