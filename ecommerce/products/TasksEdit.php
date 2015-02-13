<?php  include "../../lib/include.php";
$objtasks = new Tasks();
$objcheckList = new Checklist();
$utilObj = new util();
$objMember = new Member();
$ObjGroup = new Groups();
$group_array = $ObjGroup->GetAllGroups(" 1 ORDER BY Sort",array("*"));
$Payment_Method_array = $objMember->GetAllPaymentMethod(" MemberID = '".$_REQUEST['id']."'",array("*"));
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
$Tasks_array = $objtasks->GetAllTasks("ID = '".$_REQUEST['id']."'",array("*")); 
$TaskToChecklist_array = $objtasks->GetAllTaskToChecklist("TaskID = '".$_REQUEST['id']."'",array("*"));
$checkList_array = $objcheckList->GetAllCheckList("1 ORDER BY ID ASC",array("*")); 
//print_r($checkList_array);
$ProductToTask_array = $objtasks->GetAllTasksAssigndToProduct("TasksID = '".$_REQUEST['id']."'",array("*"));

$checked_value=$_REQUEST['TaskToChecklist'];

if($_REQUEST['Task']=='AddSave'){
	if(empty($_REQUEST['Ttitle']))$_REQUEST['Ptitle']="New Tasks Title";
	if(empty($_REQUEST['Tdescription']))$_REQUEST['Pdescription']= "New Tasks Description Here ";

	   $added= $objtasks->InsertTasks(array(
												  "Created"                =>date("Y-m-d h:i:s",time()),                                                  "TasksTitle"          =>$_REQUEST['Ttitle'],
												  "TasksDescription"    =>$_REQUEST['Tdescription']
											
													  ));
													  
	foreach((array)$checked_value as $value){
		
	 		$objtasks->InsertTaskToChecklist(array(
												  "TaskID"           =>$added,   
												   "ChecklistID"          =>$value,
												  											
													  ));
		}	
										  													  
	header("location:".SITE_ADDRESS."ecommerce/products/Tasks.php?flag=add"); 
	exit;    


	}else{
    			//header("location:Packges.php?flag=error"); 
	}

//print_r($Users_array);
//exit;


if($_REQUEST['Task']=='UpdateSave'){
 
 $tasksid = $_REQUEST['id'];
  $updated= $objtasks->UpdateTasks("ID = '$tasksid' ",array(
															  "Created"               =>date("Y-m-d h:i:s",time()),                                                              "TasksTitle"            =>$_REQUEST['Ttitle'],
                                                              "TasksDescription"	  =>$_REQUEST['Tdescription']
                                                                             
                                                                               
                                                          ));
 		//print_r($checked_value);	 
		                                
 		$objtasks->DeleteTaskToChecklist($_REQUEST['id']);	
		foreach((array)$checked_value as $value){
			
		 		$objtasks->InsertTaskToChecklist(array(
												  "TaskID"           =>$tasksid,   
												   "ChecklistID"          =>$value,
												  											
													  ));
		}		 
                                     
       header("Location:".SITE_ADDRESS."ecommerce/products/Tasks.php?flag=success"); 
		exit;     
}
if(	$_REQUEST['Task']=='Update' ){
	$Task="UpdateSave";
}else{
	$Task="AddSave";
}
if($_REQUEST['Task']=='del'){
///echo "kkkkk";
	$objtasks->DeleteTasks($_REQUEST['id']);
	//exit;
 header("Location:".SITE_ADDRESS."ecommerce/products/Tasks.php?flag=del");    	
}
?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
 	   
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


//$(document).ready(function(){
	function deletedata(){
		var passW = prompt("Please enter password","Password");
		if(passW == "09polkmn"){
			document.frmSample.submit();
		}else{
			alert("Password does not match!");
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
}​
</script>
   <!-- Tabs and button code -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

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
			<li><a href="#tabs-1">Tasks</a></li>
            <li><a href="#tabs-2">CheckList</a></li>
			
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
                   <td width="40%"   align="left"> Tasks Title:</td>
                   <td ><input name="Ttitle" type="text" value="<?php echo $Tasks_array[0]['TasksTitle'];?>" size="40" /></td>
                 </tr>
                     <tr valign="top">
                   <td   width="40%"> Tasks Description:</td>
                   <td ><textarea name="Tdescription" id="notes" cols="37" rows="5"><?php echo $Tasks_array[0]['TasksDescription'];?></textarea></td>
                 </tr>
                  

                 <tr valign="top">
                   <td  align="left"colspan="2">&nbsp;  </td>
                 </tr>
                     </table>
		</div>
        <div id="tabs-2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  
 
    
  <tr>
                         <td  colspan="2">
                             
                         <table width="100%" border="0" cellspacing="0" cellpadding="1">
						 
					
					<tr >
					<td class="tabsubheading">&nbsp;</td>
					<td class="tabsubheading">Product Name</td>
					</tr>
					<tr>
					<td colspan="2">&nbsp;</td>
					</tr>
                   <?php
				   foreach((array)$TaskToChecklist_array as $TaskToChecklist){
						$checklistid[]=$TaskToChecklist['ChecklistID'];
				   }
				  
                   for($i=0;$i<count($checkList_array);$i++){
			
                       ?>
					    <tr>
					   <td width="5%">
<input type="checkbox" id="TaskToChecklist<?php echo $checkList_array[$i]['ID'];  ?>" value="<?php echo $checkList_array[$i]['ID'];  ?>" name="TaskToChecklist[]" <?php if(in_array($checkList_array[$i]['ID'],(array)$checklistid)){echo"checked";};?> >
						</td>
						<td width="95%" align="left">
							<?php echo $checkList_array[$i]['ChecklistName']; ?>                                                   
						</td>
						</tr>
						<?php }?>
								
							</table>
								
						
                    						 
						 </td>
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
   
                function SendRevEmail1(){
					var notes =$('#notes').val();
					     $.ajax({
						type:"post",
						url:"MembersEdit.php",
	
						data:"id="+<?php echo $_REQUEST['id']; ?>+"&Task=AjaxSaveNauman&RevisionNote="+notes,
						success:function(data){
							alert("Revision updated!"+data);
					   
					   }
				  });

         }
function confirmation() {
	var answer = confirm("Do you want to delete this record?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
</script>

