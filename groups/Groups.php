<?php 
include "../include/header.php";
$ObjGroup = new Groups();
$ObjMember = new Users();
if(isset($_REQUEST['SearchText'])){
	$_SESSION['SearchText'] = trim($_REQUEST['SearchText']);
}
$search = "1";
if(isset($_SESSION['SearchText']) && $_SESSION['SearchText'] != ''){
 $search = " ProgramName LIKE '%".$_SESSION['SearchText']."%'" ;
}
$Group_array = $ObjGroup->GetAllGroups("1",array("*"));
//print_r($Group_array);
?>

<script type="text/javascript" src="../js/search.js"></script>
<link rel="stylesheet" type="text/css" href="../css/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">

<style>

.dataTables_filter input{
	border: 1px solid #DBDBDB;
    border-radius: 5px;
    height: 28px;
    padding: 0 0 0 10px;
    width: 100px;
}
.dataTables_length select{
	border: 1px solid #DBDBDB;
    border-radius: 5px 5px 5px 5px;
    height: 28px;
    padding: 4px;
	width: 150px
}
</style>
<script type="text/javascript">
 $(function() {
 	$("#GroupAdd").click(function(e){
			  e.preventDefault();	
			  modalbox(this.href,this.title,550,800);
	});
	<?php  foreach((array)$Group_array as $Group){?>        
	   $("#GroupEdit<?php echo $Group['ID'];?>").click(function(e){
					 e.preventDefault();	
					 modalbox(this.href,this.title,550,800);
		 });
		 
		 $("#GroupMembersList<?php echo $Group['ID'];?>").click(function(e){
					 e.preventDefault();	
					 modalbox(this.href,this.title,550,800);
		 });	
<?php } ?>
});
function confirmation() {
	var answer = confirm("Do you want to delete this record?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
</script>
<div id="headtitle">Groups</div>
<div class="filtercontainer">
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
      <td width="550"><div class="adv_search">
          <div class="adv_search_sub">
            <form name="TagSearchForm" id="TagSearchForm" action="?action=SetFilter" method="post">
              <div class="input_box_1">
                <input class="input_box_2_1" type="text" name="SearchText" value="<?php echo $_SESSION['SearchText'];?>" />
                <div style="display: block;" data-tooltip="Show search options" aria-label="Show search options" role="button" gh="sda" tabindex="0" class="aoo" id="show_options">
                  <div class="aoq"></div>
                </div>
              </div>
              <div class="adv_btn">
                <input class="adv_btn_2" type="submit" value="" name="">
              </div>
            </form>
          </div>
        </div></td>

      <div align="right">
        <td><div style="float:right;"><a href="GroupEdit.php"  id="GroupAdd" title="Add Group" >
         <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"  value="Add New Group" id="Addnew"></a>
          </a></div>
		  
		 
        </td>
      </div>
    </tr>
  </table>
</div>
 	<?php if($_REQUEST['flag']=='add_Group'){?>
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  colspan="3" id="message_success">
      Group has been Adeded successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
	<?php if($_REQUEST['Task']=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  colspan="3" id="message_success">
      Group has been deleted successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<?php if($_REQUEST['flag']=='update'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  colspan="3" id="message_success">
      Group has been Updated successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<div class="subcontainer" >
   <?php /*?><a href="?id=<?php echo $program_record ['ID'];?>&task=del" id="deltag">
<input type="submit" value="Delete Tag" name="deltag" id="deltag" /></a><?php */?>
<div class="dataTables_length">
Show<select name="">
<option value="">10</option>
<option value="">25</option>
<option value="">50</option>
</select>
entries
</div> 
<br/>
  <table cellpadding="3" cellspacing="0" width="100%" border="0">
	<tr id="headerbar" >
  	<td height="45" width="44" >ID</td>
     <td width="220">
    Title</td>
    <td >Description</td>
    <td >Total Members</td>
    <td width="45" class="Action">Actions</td>
  </tr>
    <?php
if(count($Group_array) > 0)
{
foreach ($Group_array as $Group)
{
if($flag==0){
$flag=1;
$row_class="row-white";
}else{
$flag=0;
$row_class = "row-tan";
}
$id = $Group['ID'];
?>
    <form method="post" action="?id=<?php echo $Group['ID'];?>&Task=del" name="frmSample">
      <tr id="<?php echo $row_class;?>">
        <td ><!--<input type="checkbox" name="rowchk[]" value="<?php echo $Group['ID'];?>">-->
        <?php echo $Group['ID'];?></td>
        <td><?php echo $Group['Title'];?></td>
        <td><?php echo $Group['Description'];?></td>
		<td ><a class="groupmemberlists" href="GroupMembersList.php?id=<?php echo $Group['ID'];?>" id="GroupMembersList<?php echo $Group['ID'];?>" title="User List of <?php echo $Group['Title'];?>"><?php echo count($ObjMember->GetAllUserWithGroup("Group_Users.GroupID = '".$Group['ID']."' AND HasDeleted = 0",array("*"))); ?></a></td>
        <td class="Action"><a id="GroupEdit<?php echo $Group['ID'];?>" href="GroupEdit.php?id=<?php echo $Group['ID'];?>&Task=Update" title="Edit <?php echo $Group['Title'];?>"> <img src="../images/icon_page_edit.png" border="0" title="Edit Groups Details"/></a>&nbsp;<a href="?id=<?php echo $Group['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../images/icon_delete.png" border="0"/></a></td>
      </tr>
    </form>
    <?php } 
} ?>
  </table>
  <?php $arr_id = $_POST['rowchk'];?>
  <?php include "../include/footer.php" ?>
  
</div>
