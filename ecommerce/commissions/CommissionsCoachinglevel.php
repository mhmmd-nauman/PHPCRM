<?php include "../../include/header.php"; 
	$utilObj = new util();
	$objGroups = new Groups();
if($_REQUEST['Submit']=='Save' && $_REQUEST['Task']){
	switch($_REQUEST['Task']){
	case"add":
		   $arrValue=array('CommissionLevelName'=>$_REQUEST['Commission_level'],'ReferenceCoachID'=>$_REQUEST['CoachMember'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'OverrideSale'=>$_REQUEST['Commission_overidesale']);
			$insertedId=$utilObj->insertRecord('CommissionLevel', $arrValue);
			if($insertedId)
			 $Flag='added';
		 break;
	case"update":
               $arrValue=array('CommissionLevelName'=>$_REQUEST['Commission_level'],'ReferenceCoachID'=>$_REQUEST['CoachMember'],'LastEdited'=>date('Y-m-d H:i:s'),'OverrideSale'=>$_REQUEST['Commission_overidesale']);
			   $strWhere='id='.$_REQUEST['id'];
			   $Updaterec=$utilObj->updateRecord('CommissionLevel', $strWhere, $arrValue);
			  if($Updaterec)
			  $Flag='update';
		break;	
	}	
}
elseif($_REQUEST['deleterecord']=='Delete'){
      $strCriteria='id='.$_REQUEST['id'].'';
      $DeleteRec=$utilObj->deleteRecord('CommissionLevel', $strCriteria);
	  if($DeleteRec)
	   $Flag='delete';

}
$CommissionRecords=$utilObj->getMultipleRow('CommissionLevel',1);

if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
}
if($_SESSION['page'] > 0 && !isset($_REQUEST['page'])){
  $page = $_SESSION['page'] ;
  $_SESSION['page'] = "";
  unset($_SESSION['page']);
}elseif(!isset($_REQUEST['page'])) {
  $page=1;
  $_SESSION['page'] = 1 ;
} else {
  $page=$_REQUEST['page'];
  $_SESSION['page'] = $page; 
}

$total_records =  count($CommissionRecords);

 if(!isset($_SESSION['limit'])){
	$limit = 10 ;
} else if($_SESSION['limit'] =="all" ){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'];
} 

$ret = $objGroups->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}
 

$strWhere='1 LIMIT '.$offset.','.$limit;
$CommissionRecords=$utilObj->getMultipleRow('CommissionLevel',$strWhere);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/ProductCategory.js"></script>
<script type="text/javascript">
 $(function() {
		   $("#CoachinglevelADD").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
         <?php  foreach((array)$CommissionRecords as $Commissioncval){?>        
                $("#Commissionleveledit<?php echo $Commissioncval['ID'];?>").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                  });	 
         <?php } ?>
             });
</script>

</head>
<body>
<div id="headtitle">Coaching Commissions Level</div>
<div class="filtercontainer" style="padding-top:15px; padding-bottom:15px;">
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
      <td align="right" valign="center" ><a href="CommissionsCoachinglevelPopup.php?Task=add"  id="CoachinglevelADD"  class="Categoryedit Ecom_Link" style="font-size:13px;"> Add New Coaching Commissions Level</a> </td>
    </tr>
  </table> 
</div>
<div class="subcontainer">
  <div >
    <?php if($Flag=='added') {?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;The Record has been Added Sucessfully! 
	  	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
   
    </table> 
    <?php } 
	  else if($Flag=='update') {?>
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;The Record has been Updated Sucessfully! 
	  	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
   
    </table> 
    <?php } 
	   else if($Flag=='delete') {?>
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;The Record has been Deleted Sucessfully! 
	  	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
   
    </table> 
    <?php $_SESSION['flag']='';} ?>
  </div>
  <table cellpadding="2" cellspacing="0"  border="0" width="100%">
    <tr id="headerbar">
      <td >Id</td>
      <td  >Referencing Coach</td>
      <td >Commission Level</td>
      <td> Override Sale %</td>
      <td >Actions</td>
    </tr>
    <?php 

$color=1;
if(count($CommissionRecords)>0){
foreach($CommissionRecords as $Commissioncval):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';
$objmember = new Member();
$Coach_staff = $objmember->GetAllMember("ID=".$Commissioncval['ReferenceCoachID']." ",array("*"));

?>
    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $Commissioncval['ID']; ?> </td>
      <td ><?php foreach($Coach_staff as $Coach) { echo $Coach['FirstName']." ".$Coach['Surname']; }?></td>
      <? $Comrecord=$utilObj->getSingleRow('CommissionProgram',"ID='".$Commissioncval['CommissionLevelName']."' ")?>
      <td ><?php echo $Comrecord['CommissionName']; ?></td>
      <td ><?php echo $Commissioncval['OverrideSale']; ?></td>
      
      <td >
    
<a href="CommissionsCoachinglevelPopup.php?Task=update&comlevid=<?php echo $Commissioncval['ID'];?>" class="Commissionleveledit" id="Commissionleveledit<?php echo $Commissioncval['ID'];?>"  > 
<img border="0" title="Edit Coaching Commission" src="../../images/icon_page_edit.png"> </a></td>
</tr>
    <?php 
	$color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Coaching Commission Level Found</td>
    </tr>
    <?php  } ?>
  </table>
   <div align="center">

<?php include "../../lib/bottomnav.php" ?>

</div>
</div>

</body>
</html>
