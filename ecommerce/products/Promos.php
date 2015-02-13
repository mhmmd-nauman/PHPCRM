<?php 
extract($_REQUEST);
//$objmember = new Member();
include "../../include/header.php";
$ObjPromotionalCode = new PromotionalCode();
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));

$pid = $_REQUEST['catid'];
//print_r($_SESSION);
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

if($_REQUEST['Task']=='add'){

$ObjPromotionalCode->InsertPromotionalCode(array(
					   "Category_Name"=>$_REQUEST['Category_Name'],
					   "Description"=>$_REQUEST['Cat_Description'],
					   "Price"=>$_REQUEST['Price'],
					   "Created"=>date("Y-m-d h:i:s"),
					   "PromosShowsOnOrderForm"=>$_REQUEST['PromosShowOnOrderForm'],
					));

 	//header("Location:Promos.php?flag=added");
    //exit;

}
if($_REQUEST['Task']=='update')
{
	//echo "jjjj";
	$updated= $ObjPromotionalCode->UpdatePromotionalCode("ID = '$pid' ",array(
						"Price"=>$_REQUEST['Price'],				   
					   "Category_Name"=>$_REQUEST['Category_Name'],
					   "Description"=>$_REQUEST['Cat_Description'],
					   "PromosShowsOnOrderForm"=>$_REQUEST['PromosShowOnOrderForm'],
					   
						));
	//header("Location:Promos.php?flag=updated");
    //exit;
}


if($_REQUEST['Task']=='del'){

    $updated= $ObjPromotionalCode->UpdatePromotionalCode("ID = '".$_REQUEST['id']."' ",array(
						"HasDeleted"=>1,
					   
						));
}

$CatRecords=$ObjPromotionalCode->GetAllPromotionalCodes("HasDeleted=0",array("*"));
//print_r($CatRecords);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css" />

<script type="text/javascript">
 $(function() {
		   $("#PromosAdd").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
         <?php  foreach((array)$CatRecords as $catcval){?>        
                $("#PromosEdit<?php echo $catcval['ID'];?>").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                  });	 
         <?php } ?>
             });
</script>

</head>
<body>
<div id="headtitle" align="right" style="margin-right:-220px;">Promotional Codes</div>
<div class="filtercontainer" style="padding-top:15px; padding-bottom:15px;">
  <!---->
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
      <td>
	  <div align="right">
	  <a  href="PromosEdit.php?Task=add"  id="PromosAdd" title="Add New Promo" ><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" id="Addnew" value="Add New Promo"> </a> 
		  </div></td>
    </tr>
  </table>
  <!---->
</div>
<div class="subcontainer">
  <div  style="float: left; padding: 10px 0 0;text-align: center; width: 905px;" >
    <?php if($Flag=='added') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Added Sucessfully! "; ?> </div>
    <?php } 
	  else if($Flag=='update') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Updated Sucessfully! "; ?> </div>
    <?php } 
	   else if($_SESSION['flag']=='Delete') {?>
    <div style="font-size:14px; font-weight:bold; color:green;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Deleted Sucessfully! "; ?> </div>
    <?php $_SESSION['flag']='';} ?>
  </div>
   
  <table cellpadding="2" cellspacing="0"  border="0" width="100%">
    <tr id="headerbar">
      <td height="45" >ID</td>
      <td width="200">Promo Name</td>
      <td >Description</td>
      <td >Price</td>
      <td class="Action">Actions</td>
    </tr>
    <?php 

$color=1;
if(count($CatRecords)>0){
foreach($CatRecords as $catcval):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

?>
    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $catcval['ID']; ?> </td>
      <td ><?php echo $catcval['Category_Name']; ?></td>
      <td ><?php echo $catcval['Description']; ?></td>
      <td ><?php echo $catcval['Price']; ?></td>
      <!--ProductCategoryAddPopup.php?Task=update&catid=<?php //echo $catcval['id']; ?>-->
      <td >
	  <div align="right">
    <a id="PromosEdit<?php echo $catcval['ID'];?>" href="PromosEdit.php?Task=update&catid=<?php echo $catcval['ID'];?>" title="Edit <?php echo $catcval['Category_Name']; ?>"> 
<img border="0" title="Edit Package Name" src="../../images/icon_page_edit.png"> </a> &nbsp;
<a  href="PromotionalCodeList.php?id=<?php echo $catcval['ID'];?>&Task=del" rel="Delete" onclick="return confirmation();"> <img border="0" src="../../images/icon_delete.png" title="Delete Package"></a> 
</div>
</td>
    </tr>
    <?php $color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="6">No Promos Found</td>
    </tr>
    <?php } ?>
    
    
  </table>

  
</div>
<div id="divId" title="Enter Admin Password" style="display:none;">
  <div align="center" style="color:#FF0000;" id="wrongpassword"></div>
  <form name="passwordsubmit" id="passwordsubmit" method="post"  onsubmit="return PasswordsubmitForm();">
    <input type="password" name="adminpassword"  size="30" />
    <input type="hidden" name="fancyboxid" id="fancyboxid"  value=""/>
    <input type="hidden" name="DeleteRecId" id="DeleteRecId"  value=""/>
    <input type="submit" value="Submit" style="margin-top:6px;" class="MOSGLsmButton" name="adminPassSubmit">
  </form>
</div>
<!--confirm dialog box-->
<div id="modal_confirm_yes_no" title="Confirm" style=" display:none;"> <strong>Are you sure you want to delete this category?</strong></div>
</body>
</html>
<script type="text/javascript">
function create_confirmation(){
    var answer = confirm("Do you want to create this website on server?");
	if(answer){
		return true;
	}else{
		return false;
	}
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
