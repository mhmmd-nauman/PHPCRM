<?php include "../../include/header.php"; 
require_once "../../lib/include.php";
 $utilObj = new util();
 $objGroups = new Groups();
 $strWhere='Type ="Product"';
$SponRecords=$utilObj->getMultipleRow('SponsorCommissionLevel',$strWhere);

$strWhere1='Type ="Subscription"';
$SponRecords1=$utilObj->getMultipleRow('SponsorCommissionLevel',$strWhere1);

if($_REQUEST["Tasklevel"]=='addlevel'){
					if($_REQUEST['sale']=='Doller')
					{
					$Level1=$_REQUEST['Level1Price'];
					$Level2=$_REQUEST['Level2Price'];
					}
					else {
					$Level1=$_REQUEST['Level1Percentage'];
					$Level2=$_REQUEST['Level2Percentage'];
					
					}
		    $arrValue=array('ProductName'=>$_REQUEST['Product_name'],'Level1'=>$Level1,'Level2'=>$Level2,'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>$_REQUEST['Type'],'CommissionCycle'=>$_REQUEST['Commission_Cycle'],'CommissionnType'=>$_REQUEST['sale']);
			$insertedId=$utilObj->insertRecord('SponsorCommissionLevel', $arrValue);
			if($insertedId)
			$_SESSION['task']='add';
			echo "<script type='text/javascript'> 
			parent.$.fancybox.close();
			
			 </script> ";		}
		elseif($_REQUEST["Tasklevel"]=='updatelevel'){
				if($_REQUEST['sale']=='Doller')
					{
					$Level1=$_REQUEST['Level1Price'];
					$Level2=$_REQUEST['Level2Price'];
					}
					else {
					$Level1=$_REQUEST['Level1Percentage'];
					$Level2=$_REQUEST['Level2Percentage'];
					
					}
              $arrValue=array('ProductName'=>$_REQUEST['Product_name'],'Level1'=>$Level1,'Level2'=>$Level2,'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>$_REQUEST['Type'],'CommissionCycle'=>$_REQUEST['Commission_Cycle'],'CommissionnType'=>$_REQUEST['sale']);
			   $strWhere='ID='.$_REQUEST['commissionlevelid'];
			   $Updaterec=$utilObj->updateRecord('SponsorCommissionLevel', $strWhere, $arrValue);
			  if($Updaterec)
			 $_SESSION['task']='update';
			 echo "<script type='text/javascript'> 
			parent.$.fancybox.close();
			
			 </script> ";
			
	}	

elseif($_REQUEST["Tasklevel"]=='delete'){
      $strCriteria='ID='.$_REQUEST["commissionlevelid"];
      $DeleteRec=$utilObj->deleteRecord('SponsorCommissionLevel', $strCriteria);
	  if($DeleteRec)
	  $_SESSION['task']='delete';
     echo "<script type='text/javascript'> 
			parent.$.fancybox.close();
			
			 </script> ";
}

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

$total_records =  count($SponRecords);

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
 

$strWhere='Type ="Product" LIMIT '.$offset.','.$limit;
$SponRecords=$utilObj->getMultipleRow('SponsorCommissionLevel',$strWhere);

?>
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/Commissionslevel.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../../css/styles.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../../co_op/css/styles.css">

<script type="text/javascript" src="../../../javascript/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="../../../co_op/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<!--<script type="text/javascript" src="js/Commissionslevel.js"></script>-->
<script type="text/javascript" language="javascript">
$(document).ready(function() {
$('#operationmsg').fadeOut(8000);

$('#CommissionSetupAddProduct').fancybox({
			/*'width'                       : 450,

			'height'                      : 300,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'                : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			'hideOnOverlayClick'          :false,
			//onClosed: function() { parent.location.reload();  },
		    'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
              $.fancybox.resize();
		    }*/
			
			});
			
			<?php foreach($SponRecords as $SponlevVal){
?>
			$('#CommissionSetupeditProduct_<?php echo $SponlevVal['ID'];?>').fancybox({
			/*'width'                       : 450,

			'height'                      : 300,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'               : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			'hideOnOverlayClick'          :  false,
			
			//onClosed: function() {  parent.location.reload(); },
			'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
              $.fancybox.resize();
		    }*/
			
			});
			<?php } ?>
			
			
			$('#CommissionSetupAddSubscription').fancybox({
			/*'width'                       : 470,

			'height'                      : 300,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'                : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			 'hideOnOverlayClick'                       :false,
			//onClosed: function() { parent.location.reload();  },
		    'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
              $.fancybox.resize();
		    }*/
			
			});
			
			<?php foreach($SponRecords1 as $SponlevVal1){
?>
			$('#CommissionSetupeditSubscription_<?php echo $SponlevVal1['ID'];?>').fancybox({
			/*'width'                       : 470,

			'height'                      : 300,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'               : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe',
			'hideOnOverlayClick'          :  false,
			
//onClosed: function() {  parent.location.reload();  },
			'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); 
              $.fancybox.resize();
		    }*/
			
			});
			<?php } ?>
				});
</script>
<style>
td {
	font-size:12px;
}
</style>

</head>
<body><div id="headtitle">Commission Program</div>

<form name="form1" method="post" action="CommissionsSponsorprogramlist.php">
 <div class="filtercontainer" style="padding-top:15px; padding-bottom:15px;">   
 <table cellpadding="0" cellspacing="0" width="100%" border="0">
    
    <tr><td align="left" valign="center">Product Commission</td>
      <td align="right" valign="center" ><a href="CommissionsSposorAddLevelPopup.php?Task=addlevel&type=1"  id="CommissionSetupAddProduct"  class="Categoryedit Ecom_Link" style='font-size:13px;'> Add New Commission </a> </td>
    </tr>
   
  </table>
  </div>
<div class="subcontainer">
  <div   >
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
  </div>
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
</div>
    <?php } ?>
    <?php $_SESSION['task']=''; ?>
  </div>
  <table cellpadding="2" cellspacing="0"  border="0" width="100%" style='margin-top:10px;'>
    <tr id="headerbar">
      <td >Product</td>
      <td >Price</td>
      <td >Level 1</td>
      <td >Level 2</td>
      <td >Actions</td>
    </tr>
    <?php 

$color=1;
if(count($SponRecords)>0){
foreach($SponRecords as $SponlevVal):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';
$strWhere='ID='.$SponlevVal['ProductName'].'';
 $ProductRec=$utilObj->getSingleRow('Product', $strWhere);
  if($SponlevVal['CommissionnType']=='Doller') { $a='$';} else $a='';
?>
    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $ProductRec['ProductName']; ?> </td>
      <td ><?php echo $ProductRec['ProductPrice']; ?></td>
      <td ><?php if($a!='') { echo $a."".$SponlevVal['Level1']; } else { echo $SponlevVal['Level1']." %"; } ?></td>
      <td ><?php if($a!='') { echo $a."".$SponlevVal['Level2']; } else { echo $SponlevVal['Level2']." %"; }?></td>
      <td >
    
<a href="CommissionsSposorAddLevelPopup.php?Task=updatelevel&type=1&levelid=<?php echo $SponlevVal['ID'];?>" id="CommissionSetupeditProduct_<?php echo $SponlevVal['ID'];?>"  >
<img border="0" title="Edit Sale %" src="../../images/icon_page_edit.png"> </a>
</td>
    </tr>
    <?php 
	$color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Commissions Program Found</td>
    </tr>
    <?php  } ?>
  </table>
   <div align="center">

<?php include "../../lib/bottomnav.php" ?>

</div>
</div>
</form>

<form name="form1" method="post" action="">
  
    <div class="filtercontainer" style="padding-top:15px; padding-bottom:15px;">   
 <table cellpadding="0" cellspacing="0" width="100%" border="0">
     <tr>
     <td align="left" valign="center">Subscription Commissions</td>
      <td align="right" valign="center" ><a href="CommissionsSposorAddLevelPopup.php?Task=addlevel&type=2"  id="CommissionSetupAddSubscription"  class="Categoryedit Ecom_Link" style='font-size:13px;'> Add New Commission </a> </td>
    </tr>

  </table></div>
<div class="subcontainer">
  <div  >
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
      <img src="images/accept.png"/>&nbsp;&nbsp;Record has been deleted Sucessfully!
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
      <td >Product</td>
      <td >Price</td>
      <td >Level 1</td>
      <td >Level 2</td>
      <td >Actions</td>
    </tr>
    <?php 

if(isset($_REQUEST['record_perpage1'])){
	$_SESSION['limit1'] = $_REQUEST['record_perpage1'];
}
if($_SESSION['page1'] > 0 && !isset($_REQUEST['page1'])){
  $page = $_SESSION['page1'] ;
  $_SESSION['page1'] = "";
  unset($_SESSION['page1']);
}elseif(!isset($_REQUEST['page1'])) {
  $page1=1;
  $_SESSION['page1'] = 1 ;
} else {
  $page1=$_REQUEST['page1'];
  $_SESSION['page1'] = $page1; 
}

$total_records =  count($SponRecords1);

 if(!isset($_SESSION['limit1'])){
	$limit1 = 10 ;
} else if($_SESSION['limit1'] =="all" ){
	$limit1 = $total_records;
} else {
	$limit1 = $_SESSION['limit1'];
} 

$ret = $objGroups->getPagerData($total_records , $limit1, $page1);
$offset1 = $ret->offset;
if( $offset1 < 1 ){
	$offset1 = 0;
}
 

$strWhere='Type ="Subscription" LIMIT '.$offset1.','.$limit1;
$SponRecords1=$utilObj->getMultipleRow('SponsorCommissionLevel',$strWhere);


$color=1;
if(count($SponRecords1)>0){
foreach($SponRecords1 as $SponlevVal1):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';
$strWhere='ID='.$SponlevVal1['ProductName'].'';
 $ProductRec1=$utilObj->getSingleRow('Product', $strWhere);
 if($SponlevVal1['CommissionnType']=='Doller') { $a='$';} else $a='';
?>
    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $ProductRec1['ProductName']; ?> </td>
      <td ><?php echo $ProductRec1['ProductPrice']; ?></td>
      <td><?php if($a!='') { echo $a."".$SponlevVal1['Level1']; } else { echo $SponlevVal1['Level1']." %"; } ?></td>
      <td><?php if($a!='') { echo $a."".$SponlevVal1['Level2']; } else { echo $SponlevVal1['Level2']." %"; } ?></td>
      <td >
    
<a href="CommissionsSposorAddLevelPopup.php?Task=updatelevel&type=2&levelid=<?php echo $SponlevVal1['ID'];?>" id="CommissionSetupeditSubscription_<?php echo $SponlevVal1['ID'];?>" >
<img border="0" title="Edit Sale %" src="../../images/icon_page_edit.png"> </a>
</td>
    </tr>
    <?php 
	$color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Commissions Program Found</td>
    </tr>
    <?php  } ?>
  </table>
  <div align="center">

<?
  //print_r($_SERVER['QUERY_STRING']);
  $paramString = "";
  parse_str($_SERVER['QUERY_STRING'], $paramArray);
  
  foreach($paramArray as $key => $value){
   
   if($key != "page1"){
    if($key !="record_perpage1"){
		if($key !="action"){
		  if($key !="Task"){
			$paramString .="$key=$value&";
			}
		}
	}
   }
  }
  $paramString = substr($paramString, 0, strlen($paramString) - 1);
  ?>
  
  
  <table width="100%" align='center' border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td >&nbsp;</td>
		  <td>&nbsp;</td>
    </tr>
		<tr>
		<td nowrap="nowrap"><? if ($total_records > 10) { ?>
		  <?=$ret->offset+1?>-<?=(($ret->offset)+$ret->limit)?> Records<br>
		  of <?=$total_records?> total		
		  <? } ?></td>
		<td align="center" >
		<?php
	
	 if($ret->numPages <=1){
	 
	 
	 
	 }
	  else  {
      
	   ?>
		<table border=0 align=center cellpadding=0 cellspacing=0 >
		  <tr>
		   <td nowrap="nowrap">
		   <? if($ret->page-1 > 0){ ?>
			<a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?page1=1&<?=$paramString?>'><span>First</span></a>
		   <? }?>
		   <? if($ret->page-1 > 0){ ?>
			<a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?page1=<?=$ret->page-1;?>&<?=$paramString?>'><span>Prev</span></a>		    
		    <? } else { ?>
		   <a ><span style="cursor:default; color:#999999;">Prev</span></a>
		  <? } ?>
			 
			 <!--<a class="button-mid" href='<?=$_SERVER['PHP_SELF']?>?page=1&<?=$paramString?>' ><span>1...</span></a>-->
		   
		 <? if(($ret->page-1) > 0 ){ ?>
		
		  <a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?page1=<? echo $ret->page - 1;?>&<?=$paramString?>'>
		  <span><?=$ret->page-1?></span>		  </a>
		  <? } ?>
		  
		  <!--on page-->
		 <a ><span><b><?=$ret->page?></b></span></a> 
		 
		 
		 <? if(($ret->page+1) <= $ret->numPages){ ?>
		  <a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?page1=<? echo $ret->page +1;?>&<?=$paramString?>'>
		  <span><?=$ret->page+1?></span>		  </a>
		  <? } ?> 
		  <? if(($ret->page+2) <=  $ret->numPages){ ?>
		  <a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?page1=<? echo $ret->page +2;?>&<?=$paramString?>'>
		   <span><?=$ret->page+2?></span>		  </a>
		  <? } ?>
		  <? if(($ret->page+3) <=  $ret->numPages){ ?>
		  <a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?page1=<? echo $ret->page +3;?>&<?=$paramString?>'>
		   <span><?=$ret->page+3?></span>		  </a>
		  <? } ?>		  
		 <? if($ret->page+1 <= $ret->numPages){ ?>
			<a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?page1=<?=$ret->page+1;?>&<?=$paramString?>'><span>Next</span></a>		   
		   <? } else { ?>
		   <a  ><span style="cursor:default; color:#999999;">Next</span></a>		   
		   <? } ?>		   
		   
		<? if($ret->page+1 <= $ret->numPages){ ?>
			<a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?page1=<? echo $ret->numPages;?>&<?=$paramString?>'><span>Last</span></a>
		 <? }?>
		   
		   </td>
		  </tr>
		  </table>
		<?
		}
		?>		</td>
		<td align="right" nowrap="nowrap">
		<? if ($total_records > 10) { ?>
		Entries Per Page<br>
		<a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?&record_perpage1=10&<?=$paramString?>'>10		</a>
		<a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?record_perpage1=25&<?=$paramString?>'>
		25		</a>
        <a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?record_perpage1=50&<?=$paramString?>'>
		50		</a>
		<a  class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?record_perpage1=100&<?=$paramString?>'>
		100		</a>
        <a  class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?record_perpage1=250&<?=$paramString?>'>
		250		</a>
        <a  class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?record_perpage=500&<?=$paramString?>'>
		500		</a>
        <a  class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?record_perpage1=1000&<?=$paramString?>'>
		1000		</a>
		<a class="fancybox" rel="gallery1" href='<?=$_SERVER['PHP_SELF']?>?record_perpage1=all&<?=$paramString?>' onclick="return showall_confirm()" >
		All		</a>		
		<? } ?>		</td>
		</tr>
</table>
<script type="text/javascript">
function showall_confirm(){
	if(!confirm('Warning: Clicking OK can take a long time to load and may lockup your computer.')){
	return false;
	}
}
</script>

</div>
</div>
</form>
</body>
