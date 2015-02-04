<?php  include "../lib/include.php";
$objcheckList = new Checklist();
$utilObj = new util();
$objMember = new Member();
$ObjGroup = new Groups();
$objproducts = new Products();
$group_array = $ObjGroup->GetAllGroups(" 1 ORDER BY Sort",array("*"));
$Payment_Method_array = $objMember->GetAllPaymentMethod(" MemberID = '".$_REQUEST['id']."'",array("*"));
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
$Tasks_array = $objcheckList->GetAllCheckList("ID = '".$_REQUEST['id']."'",array("*")); 
//print_r($Tasks_array);
$ProductToTask_array = $objcheckList->GetAllTasksAssigndToProduct("TasksID = '".$_REQUEST['id']."'",array("*"));
$Product_Array = $objproducts->GetAllProduct("1",array("ID,ProductName"));
$checkList_array = $objcheckList->GetAllCheckList("1 ORDER BY ID ASC",array("*")); 
//print_r($Product_Array);

if($_REQUEST['Task']=='AddSave'){
	if(empty($_REQUEST['Ttitle']))$_REQUEST['Ptitle']="New Tasks Title";
	if(empty($_REQUEST['Tdescription']))$_REQUEST['Pdescription']= "New Tasks Description Here ";

	   $added= $objcheckList->InsertEcChecklist(array(
												 // "Created"                =>date("Y-m-d h:i:s",time()),
												 
												  "ChecklistName"          =>$_REQUEST['Ttitle'],
												  "ChecklistDescription"    =>$_REQUEST['Tdescription']
											
													  ));
													  
	
										  													  
	header("Location:".SITE_ADDRESS."product-tasks/EcTasksCheckListConfiguration.php?flag=add");
	exit;    


	}else{
    			//header("location:Packges.php?flag=error"); 
	}

//print_r($Users_array);
//exit;


if($_REQUEST['Task']=='UpdateSave'){
//echo"jjjjjjjj";
	$tasksid = $_REQUEST['id'];
  $updated= $objcheckList->UpdateEcChecklist("ID = '$tasksid' ",array(
															  //"Created"               =>date("Y-m-d h:i:s",time()),                                                             
															   "ChecklistName"            =>$_REQUEST['Ttitle'],
                                                              "ChecklistDescription"	  =>$_REQUEST['Tdescription']
                                                                             
                                                                               
                                                          ));
 				                                       
 		 
                                     
       header("Location:".SITE_ADDRESS."product-tasks/EcTasksCheckListConfiguration.php?flag=success"); 
		exit;     
}
if(	$_REQUEST['Task']=='Update' ){
	$Task="UpdateSave";
}else{
	$Task="AddSave";
}
if($_REQUEST['Task']=='del'){
///echo "kkkkk";
	$objcheckList->DeleteEcChecklist($_REQUEST['id']);
	//exit;
 header("Location:".SITE_ADDRESS."ecommerce/products/Checklist.php?flag=del");    	
}


?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">

 	   
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
  $(document).ready(function() {
 	$("#datepicker").datepicker();
  });
 
  </script>
   <!-- Tabs and button code -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/style.css" />
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  

<!-- End of Tabs and button code --> 
	
<style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 13px;}
</style>
<form action="?id=<?php echo $_REQUEST['id'];?>&Task=<?php echo $Task;?>" target="_top"method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">

<?php if($_REQUEST['flag']=='update'){?>
   <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;Packges updated successfully!
	   <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
   </table>
   <?php }?>
        <div class="container1">
		
	<div id="tabs">
			<ul>
			<li><a href="#tabs-1">CheckList Update</a></li>
            </ul>
   		<div id="tabs-1">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
                       <tr valign="top">
                   <td  align="left"colspan="2" class="tabsubheading"> Basic Information </td>
                 </tr>
                     <tr valign="top">
                   <td   align="left"colspan="2">&nbsp;  </td>
                 </tr>
                   <tr valign="top">
                   <td width="40%"   align="left"> Name:</td>
                   <td ><input name="Ttitle" type="text" value="<?php echo $Tasks_array[0]['ChecklistName'];?>" size="40" /></td>
                 </tr>
                     <tr valign="top">
                   <td   width="40%">  Description:</td>
                   <td ><textarea name="Tdescription" id="notes" cols="38" rows="5"><?php echo $Tasks_array[0]['ChecklistDescription'];?></textarea></td>
                 </tr>
                   <tr valign="top">
                   <td  align="left"colspan="2">&nbsp;  </td>
                 </tr>
                     </table>
		</div>
       	 <tr>
       <td align="center" colspan="2">
           <div align="center" style="float: left; margin-top:10px; bottom:0; background: none repeat scroll 0 0 #FFFFFF;  width: 100%;">
	   <input type="submit" name="Submit" value="Save Changes" class="MOSGLButton" style="height:29px;" onclick="return spon_check()" id="javed" />
	   <!--<input type="button" id="deleteID" name="delete" value="Delete" class="MOSGLButton" style="height:29px;" onclick="return deletedataPost('<?php echo $_REQUEST['id'];?>')" />-->
           <div id="delwaitmsg" style="margin-left:165px;"></div>
																																		<input type="hidden" name="oldimage"  value=""/></div>
           <input type="hidden" name="postback" value="1" />
        <input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" />
       </td>
        </tr>
		</div>
</div>
</form>
