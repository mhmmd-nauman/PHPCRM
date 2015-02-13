<?php 
require_once "../../lib/include.php";
 $utilObj = new util();
 $objGroups = new Groups();
 $strWhere='CommissionProgramID ='.$_REQUEST['commissionprogramid'].'';
$ComsaleRecords=$utilObj->getMultipleRow(' CommissionLevelSale',$strWhere);

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

$total_records =  count($ComsaleRecords);

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
 

$strWhere='CommissionProgramID ='.$_REQUEST['commissionprogramid'].' LIMIT '.$offset.','.$limit;
$ComsaleRecords=$utilObj->getMultipleRow('CommissionLevelSale',$strWhere);

?>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles_ecommerce.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search_ecommerce.css"> 

<script type="text/javascript" src="js/Commissionslevel.js"></script>





<script type="text/javascript" language="javascript">
$(document).ready(function() {
$('#operationmsg').fadeOut(8000);

$('#CommissionSetupAdd').fancybox({
			'width'                       : 550,

			'height'                      : 430,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'                : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			 'hideOnOverlayClick'                       :false,
			//onClosed: function() {    window.location.reload();  },
		    'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
              $.fancybox.resize();
		    }
			
			});
			
			<?php foreach($ComsaleRecords as $ComsaleVal){
?>
			$('#CommissionSetupedit_<?php echo $ComsaleVal['ID'];?>').fancybox({
			'width'                       : 550,

			'height'                      : 430,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'               : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			'hideOnOverlayClick'          :  false,
			 onClosed					  : function() { window.location.reload(); },

			'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
              $.fancybox.resize();
		    }
			
			});
			<?php } ?>
				});
</script>
<style>
td {
	font-size:12px;
}
</style>
<div style="width:700px;" id='setupdiv' >
<form name="form1" method="post" action="CommissionsCoachingprogramlist.php">
    <input name="commissionprogram_id" type="hidden" value="<?php echo $_REQUEST['commissionprogramid'];?>" size="40">
<table cellpadding="2" cellspacing="0"  border="0" width="100%" style='margin-top:10px;' >
    <tr>
      <td colspan="2" id="name"><b> Add Coaching Commissions Level</b> </td>
    </tr>
    <tr>
      <td align="right" valign="center" height="50px;"><a href="CommissionsCoachingAddsalePopup.php?Task=addsale&commissionprogramid=<?php echo $_REQUEST['commissionprogramid']; ?>"  id="CommissionSetupAdd"  class="Categoryedit Ecom_Link" style='font-size:13px;'> Add Sale % </a> </td>
    </tr>
  </table>
<div class="subcontainer">
  <div >
    <?php if($_SESSION['task']=='add') {?>
      
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;Record has been Added Sucessfully! 
	  	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
   
    </table>
      
    <?php } 
	  else if($_SESSION['task']=='update') {?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;Record has been Updated Sucessfully! 
	  	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
   
    </table>
    <?php } 
	   else if($_SESSION['task']=='delete') {?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;Record has been Deleted Sucessfully! 
	  	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
   
    </table>
    <?php } ?>
    <?php $_SESSION['task']=''; ?>
  </div>
  <table cellpadding="2" cellspacing="0"  border="0" width="100%" style='margin-top:10px;'>
    <tr id="headerbar">
      <td >Id</td>
      <td >Commission Level Name</td>
      <td >Description</td>
      <td >Sale %</td>
      <td >Time</td>
      <td >Actions</td>
    </tr>
    <?php 

$color=1;
if(count($ComsaleRecords)>0){
foreach($ComsaleRecords as $ComsaleVal):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

?>
    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $ComsaleVal['ID']; ?> </td>
      <td ><?php echo $ComsaleVal['CommissionLevelSaleName']; ?></td>
      <td ><?php echo $ComsaleVal['Description']; ?></td>
      <td><?php echo $ComsaleVal['Sale']; ?></td>
      <td><?php echo $ComsaleVal['CommissionBasedOn']; ?></td>
      <!--ProductCategoryAddPopup.php?Task=update&catid=<?php //echo $catcval['id']; ?>-->
      <td >
    
<a href="CommissionsCoachingAddsalePopup.php?Task=updatesale&commissionlevelsaleid=<?php echo $ComsaleVal['ID'];?>&commissionprogramid=<?php echo $_REQUEST['commissionprogramid']; ?>" id="CommissionSetupedit_<?php echo $ComsaleVal['ID'];?>"  >
<img border="0" title="Edit Sale %" src="../../images/icon_page_edit.png"> </a>
</td>
    </tr>
    <?php 
	$color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Coaching Commissions Level Found</td>
    </tr>
    <?php  } ?>
  </table>
  <div align="center">

<?php include "../../lib/bottomnav.php" ?>

</div>
</div>
</form>
</div>