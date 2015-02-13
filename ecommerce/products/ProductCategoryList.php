<?php include "../../include/header.php"; 
$utilObj = new util();
if($_REQUEST['Submit']=='Save' && $_REQUEST['Task']){
	switch($_REQUEST['Task']){
	case"add":
		    $arrValue=array('CategoryName'=>$_REQUEST['Category_Name'],'Description'=>$_REQUEST['Cat_Description']);
			$insertedId=$utilObj->insertRecord('ProductCategory', $arrValue);
			if($insertedId)
			 $Flag='added';
		 break;
	case"update":
               $arrValue=array('CategoryName'=>$_REQUEST['Category_Name'],'Description'=>$_REQUEST['Cat_Description']);
			   $strWhere='id='.$_REQUEST['id'];
			   $Updaterec=$utilObj->updateRecord('ProductCategory', $strWhere, $arrValue);
			  if($Updaterec)
			  $Flag='update';
		break;
		

	}	
	 
}
$CatRecords=$utilObj->getMultipleRow('ProductCategory',1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="../js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="../css/search.css" />
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
		   $("#AddCategory").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
			$(".editBtn").click(function(e) {
						e.preventDefault();	
                        modalbox2(this.href,this.title,550,800);
					});
					
     
             });
 </script>
</head>
<body>
<div id="headtitle">Category</div>
<div class="filtercontainer">
&nbsp;
<table cellpadding="" cellspacing="0" width="100%" border="0" style="margin-top:-8px;">
    <tr >  
		  <td ><div align="right"><a href="ProductCategoryAddPopup.php?Task=add"  id="AddCategory" title="Add New Category" ><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" id="Addnew" value="Add New Category">  </a></div> </td>
			
    </tr>
  </table>
  
  <!---->
</div>
<div class="subcontainer">
  <div >
    <?php if($Flag=='added') {?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_success">
      Record has been sdded sucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    
    </table>
    <?php } 
	  else if($Flag=='update') {?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  id="message_success">
      Record has been updated aucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     </table>
    <?php } 
	   else if($_SESSION['flag']=='Delete') {?>
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Record has been deleted sucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
    </table>
    <?php $_SESSION['flag']='';} ?>
  </div>
  
  <table cellpadding="2" cellspacing="0"  border="0" width="100%">
    <tr id="headerbarpopup">
      <td height="45" >ID</td>
      <td width="200"  >Category Name</td>
      <td >Description</td>
      <td >&nbsp;</td>
      <td width="45" >Actions</td>
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
      <td ><?php echo $catcval['CategoryName']; ?></td>
      <td ><?php echo $catcval['Description']; ?></td>
      <td >&nbsp;</td>
       <td >
     
   <a id="EditCategory<?php echo $catcval['ID'];?>" title="Edit <?php echo $catcval['CategoryName']; ?>" href="ProductCategoryAddPopup.php?Task=update&catid=<?php echo $catcval['ID'];?>" class="editBtn"> 
<img border="0" title="Edit Category" src="../../images/icon_page_edit.png"> </a> &nbsp;<a  href="ProductAndCategoryDelete.php?id=<?php echo $catcval['ID']; ?>&Task=Delete"class="deletecat"> <img border="0" src="../../images/icon_delete.png" title="Delete Category" onclick="return confirmation();"></a>




</td>
    </tr>
    <?php $color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="6">No Categories Found</td>
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