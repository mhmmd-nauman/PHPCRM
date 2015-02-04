<?php include "../include/header.php"; 
$objcheckList = new Checklist();
//$objmember = new Member();
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
$objwebsites = new WebSites();
//$HubFlxSiteStatus = $objHubFlxMembers->GetAllHubFlxMember(" 1",array("HubFlxMember.Status"));
if(isset($_REQUEST['sortBy']))$_SESSION['sortBy']=$_REQUEST['sortBy'];
if(isset($_REQUEST['sortValue']))$_SESSION['sortValue']=$_REQUEST['sortValue'];
$sortBy = $_SESSION['sortBy'];
$sortValue = $_SESSION['sortValue'];
if(empty($sortBy)) $sortBy = "Date";
if(empty($sortValue))$sortValue = "DESC";
switch($sortBy){
  case "Date":
  	switch($sortValue){
	
	case "ASC":
	
	$sortText = "ORDER BY Created ASC";
	$sortName = "DESC";
	break;
	case "DESC":
	
	$sortText = "ORDER BY Created DESC";
	$sortName = "ASC";
	break;
	}
  break;
}

if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
	
}
if(isset($_REQUEST['SearchText'])){
	$_SESSION['SearchText'] = trim($_REQUEST['SearchText']);

}
(isset($_REQUEST['SelectedGroup']))?$_SESSION['SelectedGroup']=$_REQUEST['SelectedGroup']:"";
if(!isset($_SESSION['SelectedGroup'])){
    $_SESSION['SelectedGroup']=array("5");
}

if(!empty($_REQUEST['page'])) {
   $page=$_REQUEST['page'];
} else {
  $page=1;
}
$_SESSION['page'] = $page;

$total_records   =  $info_arrayTotal[0]['Total'];
//print_r($info_arrayTotal);

if(!isset($_SESSION['limit'])){
	$limit = 10 ;
} else if($_SESSION['limit'] =="all" ){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'] ;
}

//$ret = $objmember->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}
$checkList_array = $objcheckList->GetAllCheckList("1 ORDER BY ID ASC",array("*")); 
?>

<script type="text/javascript" src="js/search.js"></script>
<link rel="stylesheet" type="text/css" href="css/search.css" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
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
		 $(function() {
		   $("#AddTask").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
         <?php  foreach((array)$checkList_array as $checkList_row){?>        
                $("#EditTask<?php echo $checkList_row['ID'];?>").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                  });	 
         <?php } ?>
             });
</script>
<div id="headtitle"> Product Tasks</div>
<div class="filtercontainer">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="HubUserSearchForm" id="HubUserSearchForm" action="?Task=SetFilter" method="post" enctype="multipart/form-data">

<div class="adv_search">
  <div class="adv_search_sub">
    <div class="input_box">
      <input name="SearchText" id="mainsearch" class="input_box_2" type="text" value="<?php echo $_SESSION['SearchText'];?>" />
      <div id="show_options" class="aoo" tabindex="0" gh="sda" role="button" aria-label="Show search options" data-tooltip="Show search options" style="">
        <div class="aoq"></div>
      </div>
      
      
    </div>
    
    <div class="adv_btn">
      <input name="Submit" type="submit" class="adv_btn_2" value="" />
    </div>
  </div>
  
  </div>
  <div style="clear:both;"></div>
<script type="text/javascript">
$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	jQuery("#show_options").show();
	$('#changename').attr('name', '');
});
</script>
</form>
    </td>
    <td align="right">
    <div style="width:200px;" >
	<a href="ChecklistPopup.php?Task=Add" class="hubopus_popup  Ecom_Link" id="AddTask" title="Add New CheckList">Add New CheckList</a>
     </div>
    </td>
  </tr>
</table>
</div>
<?php if($_REQUEST['flag']=='add'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_success">
      Tasks record has been Aded successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<?php if($_REQUEST['flag']=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_success">
      Tasks record has been deleted successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    </table>
<?php } ?>

    <?php if($_REQUEST['flag']=='success'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_success">
      Tasks record has been Updated successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    </table>
<?php } ?>
	
	<?php if($_REQUEST['flag']=='error'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
   
  <td align="left" colspan="3" id="message_error">
      This Email already exists in the database!
        <script>
		$(document).ready(function(){
  
        $("#message_error").fadeOut(3000);
  });
		</script>
</td>
  </tr>
     
    <?php }?>
</table>
	
<div class="subcontainer">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr id="headerbar"  style="background-color:<?php echo "#".$SystemSettingsArray[0]['HeaderColor']; ?>">
  	<td height="45" width="44" >ID</td>
  	
  	<td width="200" >CheckList Title </td>
    
    <td >Tasks Description</td>
    <td >&nbsp;</td>
    <td width="46" >Actions</td>
  </tr>
  
       
  <?php  foreach((array)$checkList_array as $checkList_row){
	  if($flag==0){
	$flag=1;
	$row_class="row-white";
	}else{
	$flag=0;
	$row_class = "row-tan";
	}
	   	  
  ?>
  <tr id="<?php echo $row_class;?>">
   <td> <?php echo $checkList_row['ID'];?></td>
          <td width="200">
		  <?php echo $checkList_row['ChecklistName'];?>
                       </td>
      
	   
    <td>  <?php echo $checkList_row['ChecklistDescription'];?>   </td>
    <td>&nbsp;</td>
     <td>
    
    <a id="EditTask<?php echo $checkList_row['ID'];?>" href="ChecklistPopup.php?id=<?php echo $checkList_row['ID'];?>&Task=Update" title="<?php echo $checkList_row['ChecklistName'];?>"> <img src="../../images/icon_page_edit.png" border="0" title="Edit Details"/></a><a href="ChecklistPopup.php?id=<?php echo $checkList_row['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../../images/icon_delete.png" border="0"/></a></td>
  </tr>
 <?php }?>
</table>
<div align="center">
<?php include "../../lib/bottomnav.php" ?>

</div>

<? include "../../include/footer.php" ?>
