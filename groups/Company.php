<?php 
include "../include/header.php"; 
$objcompany = new Company();
$objmerchant=new MerchantAccount();
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
   $page = $_REQUEST['page'];
} else {
  $page = 1;
}
$_SESSION['page'] = $page;


$Company_array = $objcompany->GetAllCompany("1 ORDER BY ID ASC",array("*")); 

?>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
<script type="text/javascript">
function confirmation() {
	var answer = confirm("Do you want to delete this record?");
	if(answer){
		return true;
	}else{
		return false;
	}
}

$(document).ready(function(){
	$(".message_success").fadeOut(3000);
	$(".message_error").fadeOut(3000);
});

$(function() {
	$("#CompanyAdd").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	       
	$(".CompanyEdit").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
});

$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	jQuery("#show_options").show();
	$('#changename').attr('name', '');
});
</script>
<div id="headtitle">Companies</div>
<div class="filtercontainer">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="40%">
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
        </form>
      </td>
      <td >
          <div align="right"> <a id="CompanyAdd" href="CompanyEdit.php?Task=Add"  title="Add New Company">
              <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"  value="Add New Company" id="Addnew">
              </a>
          </div>
      </td>
    </tr>
  </table>
</div>
<?php if($_REQUEST['flag'] == 'add'){ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3" class="message_success">Company Record Added Successfully!</td>
  </tr>
</table>
<?php }
if($_REQUEST['flag'] == 'del'){ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3" class="message_success">Company Record Has Been Deleted Successfully!</td>
  </tr>
</table>
<?php }
if($_REQUEST['flag'] == 'success'){ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3" class="message_success">Company Record Has Been Updated Successfully!</td>
  </tr>
</table>
<?php }
if($_REQUEST['flag'] == 'error'){ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3" id="message_error">Email Already Exists!</td>
  </tr>
</table>
<?php } ?>
<div class="subcontainer">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr id="headerbar">
    <td height="45" width="44" >ID</td>
    <td width="200" >Title </td>
    <td >Description</td>
    <td >Merchant Assign </td>
    <td class="Action">Actions</td>
  </tr>
  <?php
  foreach((array)$Company_array as $Company_row){
  	$merchants = $objmerchant->GetAllMerchantAccount("MerchantId='".$Company_row['MerchantID']."'",array("*"));
	if($flag == 0){
		$flag = 1;
		$row_class = "row-white";
	}else{
		$flag = 0;
		$row_class = "row-tan";
	}  
	   	  
  ?>
    <tr id="<?php echo $row_class;?>">
        <td><?php echo $Company_row['ID'];?></td>
        <td width="200"><?php echo $Company_row['CompanyName'];?> </td>
        <td><?php echo $Company_row['CompanyDescription'];?> </td>
        <td><?php echo $merchants[0]['AccountName'];?> </td>
        <td class="Action" >
            <a class="CompanyEdit" id="CompanyEdit<?php echo $Company_row['ID'];?>" href="CompanyEdit.php?id=<?php echo $Company_row['ID'];?>&Task=Update"title="<?php echo $Company_row['CompanyName'];?>">
                <img src="../images/icon_page_edit.png" border="0" title="Edit Details"/>
            </a>
            <a href="CompanyEdit.php?id=<?php echo $Company_row['ID'];?>&Task=del" onclick="return confirmation();">
                <img title="Delete From Database" src="../images/icon_delete.png" border="0"/>
            </a>
        </td>
    </tr>
  <?php }?>
</table>
<div align="center"> </div>
<?php include "../include/footer.php" ?>
