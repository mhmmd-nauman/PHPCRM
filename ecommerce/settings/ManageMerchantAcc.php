<?php 
include "../../include/header.php"; 
$utilObj = new util();
$objGroups = new Groups();

if($_REQUEST['Submit']=='Save'){
	switch($_REQUEST['Task']){
	case"add":
		  $arrValue=array('AccountName'=>$_REQUEST['MerchantAccName'],'AccountType'=>$_REQUEST['AccountType'],'LoginID'=>$_REQUEST['MerchantAuthorizeLoginId'],'TransactionKey'=>$_REQUEST['MerchantTransactionKey'],'AccountLimit'=>$_REQUEST['MerchantAccountAccountLimit'],'Mode'=>$_REQUEST['MerchantAccountMode']
		  ,'DeviceType'=>$_REQUEST['AuthorizeCPDeviceType'],'PayFlowUser'=>$_REQUEST['MerchantUser'],'PayFlowVendor'=>$_REQUEST['MerchantVendor'],'PayFlowPartner'=>$_REQUEST['MerchantPartner'],'PayFlowPassword'=>$_REQUEST['MerchantPassword'],'PayFlowCurrency'=>$_REQUEST['Currency']);
			$insertedId=$utilObj->insertRecord('ManageMerchantAcc', $arrValue);
			if($insertedId)
			 $Flag='added';
		 break;
	case"update":
	          if($_REQUEST['AccountType']==16){
			  $arrValue=array('AccountName'=>$_REQUEST['MerchantAccName'],'PayFlowUser'=>$_REQUEST['MerchantUser'],'PayFlowVendor'=>$_REQUEST['MerchantVendor'],'PayFlowPartner'=>$_REQUEST['MerchantPartner'],'PayFlowPassword'=>$_REQUEST['MerchantPassword'],'PayFlowCurrency'=>$_REQUEST['Currency'],'Mode'=>$_REQUEST['MerchantAccountMode'],'AccountLimit'=>$_REQUEST['MerchantAccountAccountLimit']);
			   }else{
				  if($_REQUEST['AccountType']==4){
					$arrValue=array('AccountName'=>$_REQUEST['MerchantAccName'],'LoginID'=>$_REQUEST['MerchantAuthorizeLoginId'],'TransactionKey'=>$_REQUEST['MerchantTransactionKey'],'AccountLimit'=>$_REQUEST['MerchantAccountAccountLimit'],'Mode'=>$_REQUEST['MerchantAccountMode'],
					'DeviceType'=>$_REQUEST['AuthorizeCPDeviceType'] );
				  }else{
				   $arrValue=array('AccountName'=>$_REQUEST['MerchantAccName'],'LoginID'=>$_REQUEST['MerchantAuthorizeLoginId'],'TransactionKey'=>$_REQUEST['MerchantTransactionKey'],'AccountLimit'=>$_REQUEST['MerchantAccountAccountLimit'],'Mode'=>$_REQUEST['MerchantAccountMode'].$updatitem );
				   }
			  
			  //print_r($arrValue);
			  //exit;
			  }
			  
			  $strWhere='MerchantId='.$_REQUEST['id'].'';
			  
			  $Updaterec=$utilObj->updateRecord('ManageMerchantAcc', $strWhere, $arrValue);
			  if($Updaterec)
			  $Flag='update';
		break;	
	}	
}
elseif($_REQUEST['Task']=='del'){
      $strCriteria='MerchantId='.$_REQUEST['id'].'';
      $DeleteRec=$utilObj->deleteRecord('ManageMerchantAcc', $strCriteria);
	  if($DeleteRec)
	   $Flag='delete';

}
 $_REQUEST['Task'];
$strwhere='AccountType!=0';
$merchantRecords=$utilObj->getMultipleRow('ManageMerchantAcc',$strwhere);

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

$total_records =  count($merchantRecords);

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
 

$strWhere='AccountType!=0 LIMIT '.$offset.','.$limit;
$merchantRecords=$utilObj->getMultipleRow('ManageMerchantAcc',$strWhere);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
<!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />-->
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css" />

<script type="text/javascript">
		 $(function() {
		   $("#marchanteditAcc").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
			$(".editBtn").click(function(e) {
						e.preventDefault();	
                        modalbox2(this.href,this.title,550,800);
					});
					
		<?php  //foreach((array)$merchantRecords as $mercval){?>        
                /*
				$("#marchantAccClick<?php echo $mercval['MerchantId']; ?>").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                  });
				  $("#marchanteditAcc1<?php echo $mercval['MerchantId']; ?>").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                  });
				  */
		<?php //} ?>	
		});
           function loadGateWay(type){
               var wholeurl = "ManageMerchantAccPopup.php"+'?type='+type+'&Task=add';
               //alert(wholeurl);
                modalbox(wholeurl , this.title,550,800);
           }
	</script>

<!--<script type="text/javascript">
$(document).ready(function() {

$("#addMaccount").bind('change', function() {
   var type=$(this).val();
	var urllink =$("a#merchantlink").attr("href");
     var wholeurl=urllink+'?type='+type;
	 $("a#merchantlink").attr("href", wholeurl);
	 
	if(type!=''){
       $.fancybox({'href':wholeurl});
	}
	 $("a#merchantlink").attr("href", urllink);
});

$('#addMaccount').change(function() {

    var type=$(this).val();
	var urllink =$("a#merchantlink").attr("href");
     var wholeurl=urllink+'?type='+type+'&Task=add';
	 $("a#merchantlink").attr("href", wholeurl);
	if(type!=''){
	$('a#merchantlink').live('click', function() {
			$(this).fancybox({'hideOnOverlayClick':false});
		});
	$("a#merchantlink").fancybox({'hideOnOverlayClick':false}).trigger('click'); 
	}
	 $("a#merchantlink").attr("href", urllink)						   
    //$(".merchantedit").trigger('click');
});

 $('#operationmsg').fadeOut(8000);
});

</script>-->

<script>

$("#merchantlink").dialog ({
  autoOpen : false
});

</script>
</head>
<body>
<div id="headtitle">Merchant Account</div>
<a href="ManageMerchantAccPopup.php"  id="merchantlink" >&nbsp;</a>
<div class="filtercontainer" style="padding-top:15px;">
  <!---->
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
	
      <td ><div align="right"><select name="addMAccount" onchange ="loadGateWay(this.value)" class="product" style="width:200px;">
          <!--
          <option value="">Add Merchant Account
          <option value="1">PowerPay w/Authorize.NET </option>
          <option value="2">PayLeap </option>
          -->
          <option value="3">Authorize.NET </option>
          <option value="4">Authorize.NET (Card Present Method)</option>
          <option value="14">Network Merchants </option>
          <!--
          <option value="5">Beanstream </option>
          <option value="6">Blue Pay </option>
          <option value="7">CommWeb </option>
          <option value="8">EWay </option>
          <option value="9">eProcessing Network </option>
          <option value="10">IntelliPay </option>
          <option value="11">PriMerchants </option>
          <option value="12">Round Robin </option>
          <option value="13">Moneris </option>
          
          <option value="15">Optimal </option>
          <option value="16">Pay Pal Payflow Pro</option>
          <option value="17">Pay Net Secure </option>
          <option value="18">Sagepay (Protx) </option>
          <option value="19">PayGate </option>
          <option value="20">SafeCharge </option>
          <option value="21">USA EPay </option>
          <option value="22">USight </option>
          <option value="23">VeloCT </option>
          <option value="24">Verisign </option>
          <option value="25">DPS </option>
          <option value="26">ICS </option>
          <option value="27">Web Advantage </option>
          <option value="28">Internet Secure (Authorize Emulation)</option>
          <option value="29">CartConnect</option>
          <option value="30">WorldPay </option>
          -->
          </option>
        </select>
    </div>  </td>
    </tr>
  </table>
  <!---->
</div>
 <div  style="float: left; padding: 10px 0 0;text-align: center; width: 100%;" >
 <?php if($Flag=='added') {?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Record has been Added Sucessfully!
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
  <td id="message_success">
      Record has been Updated Sucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    
    </table>
    <?php } 
	   else if($Flag=='delete') {?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Record has been Deleted Sucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    
    </table>
    <?php } ?></div>
<div class="subcontainer">
 
  <table cellpadding="0" cellspacing="0"  border="0" width="100%">
    <tr id="headerbar">
      
      <td  >Name</td>
      <td >Account Type</td>
      <td >Num Fail</td>
      <td >Account Limit</td>
	  <td >Test Account</td>
      <td class="Action" >Actions</td>
    </tr>
    <?php 
$MerchantAccTypeArr=array("1"=>"PowerPay w/Authorize.NET","2"=>"PayLeap","3"=>"Authorize.NET","4"=>"Authorize.NET (Card Present Method)","5"=>"Beanstream", "6"=>"Blue Pay", "7"=>"CommWeb", "8"=>"EWay", "9"=>"eProcessing Network", "10"=>"IntelliPay", "11"=>"PriMerchants", "12"=>"Round Robin", "13"=>"Moneris","14"=>"Network Merchants","15"=>"Optimal", "16"=>"Pay Pal Payflow Pro","17"=>"Pay Net Secure", "18"=>"Sagepay (Protx)", "19"=>"PayGate", "20"=>"SafeCharge", "21"=>"USA EPay", "22"=>"USight","23"=>"VeloCT", "24"=>"Verisign", "25"=>"DPS", "26"=>"ICS", "27"=>"Web Advantage", "28"=>"Internet Secure (Authorize Emulation)","29"=>"CartConnect","30"=>"WorldPay");

$color=1;
//print_r($merchantRecords);
foreach($merchantRecords as $mercval):
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

?>
    <tr id="<?php echo $colors; ?>">
     
      <td ><?php echo $mercval['AccountName']; ?></td>
      <td ><?php echo $MerchantAccTypeArr[$mercval['AccountType']]; ?></td>
      <td ><?php echo $mercval['NumFail']; ?></td>
      <td ><?php echo "$".number_format($mercval['AccountLimit'],2); ?></td>
	   <td ><a href="MerchantAccTestPopup.php?id=<?php echo $mercval['MerchantId']; ?>" id="marchantAccClick<?php echo $mercval['MerchantId']; ?>" title="<?php echo $mercval['AccountName']; ?>">Click Here</a></td>
      <td class="Action">
	  
	  <a class="editBtn" href="ManageMerchantAccPopup.php?id=<?php echo $mercval['MerchantId']; ?>&Task=update" id="marchanteditAcc1<?php echo $mercval['MerchantId']; ?>" title="<?php echo $mercval['AccountName']; ?>"><img border="0" title="Edit Merchant Account Details" src="../../images/icon_page_edit.png" ></a> 
	  <a href="?id=<?php echo $mercval['MerchantId']; ?>&Task=del" onclick="return confirmation();"> <img title="Delete Task" src="../../images/icon_delete.png" border="0"/></a>
	  </td>
    </tr>
    <?php $color++; endforeach; ?>
  </table>
   <div align="center">

<?php include "../../lib/bottomnav.php" ?>

</div>
</div>
</body>
</html>
