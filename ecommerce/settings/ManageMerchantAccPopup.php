<?php 
require_once "../../lib/include.php";
$utilObj = new util();
$MerchantAccTypeArr=array("1"=>"PowerPay w/Authorize.NET","2"=>"PayLeap","3"=>"Authorize.NET","4"=>"Authorize.NET (Card Present Method)","5"=>"Beanstream", "6"=>"Blue Pay", "7"=>"CommWeb", "8"=>"EWay", "9"=>"eProcessing Network", "10"=>"IntelliPay", "11"=>"PriMerchants", "12"=>"Round Robin", "13"=>"Moneris","14"=>"Network Merchants","15"=>"Optimal", "16"=>"Pay Pal Payflow Pro","17"=>"Pay Net Secure", "18"=>"Sagepay (Protx)", "19"=>"PayGate", "20"=>"SafeCharge", "21"=>"USA EPay", "22"=>"USight","23"=>"VeloCT", "24"=>"Verisign", "25"=>"DPS", "26"=>"ICS", "27"=>"Web Advantage", "28"=>"Internet Secure (Authorize Emulation)","29"=>"CartConnect","30"=>"WorldPay");

if(!empty($_GET['type'])){
$type=$_GET['type'];
$MerchantVal='Manage '.$MerchantAccTypeArr[$type];
}else{
$strwhere='MerchantId='.$_REQUEST['id'].'';
$merchantRecords=$utilObj->getSingleRow('ManageMerchantAcc',$strwhere);
$type=$merchantRecords['AccountType'];
$MerchantVal='Manage '.$MerchantAccTypeArr[$merchantRecords['AccountType']];
//print_r($merchantRecords);
}


?>

<script type="text/javascript">
$(document).ready(function() {
$("#DeleteMerchant").click(function(){
 $('#deleteform').submit();
});
});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.9.1.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>

<form name="frmSample" method="post" action="ManageMerchantAcc.php?Task=add" target="_parent">
<input name="AccountType" type="hidden" value="<?php echo $type; ?>" size="40" style="height:20px;">
<input name="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" style="height:20px;">
<input name="id" type="hidden" value="<?php echo $_REQUEST['id'];?>" size="40" style="height:20px;">

 <?php if($type==16){
    require_once('PayflowTemplate.php');
	}else { ?>
	<table width="100%" border="0" cellspacing="3" cellpadding="1" >
	<tr>
      <td  colspan="2" id="headerbarpopup"s><b><?php echo $MerchantVal;?> </b></td>
    </tr></table>
	<div class="containerpopup">
			<div class="subcontainer"> 
    <table width="100%" border="0" cellspacing="3" cellpadding="1" >
    
    <tr>
      <td align="right">Name:</td>
      <td><input name="MerchantAccName" type="text" value="<?php echo $merchantRecords['AccountName']; ?>" size="40"></td>
    </tr>
    <tr>
      <td align="right">Login ID:</td>
      <td><input name="MerchantAuthorizeLoginId" type="text" value="<?php echo $merchantRecords['LoginID']; ?>" size="40"></td>
    </tr>
    <tr>
      <td align="right">Transaction Key:</td>
      <td><input name="MerchantTransactionKey"  type="text"  id="MerchantTransactionKey" value="<?php echo $merchantRecords['TransactionKey']; ?>"  size="40" >
       
      </td>
    </tr>
    <tr>
      <td align="right">Account Limit:</td>
      <td><input name="MerchantAccountAccountLimit"  id="MerchantAccountAccountLimit" type="text" value="<?php echo "$".number_format($merchantRecords['AccountLimit'],2); ?>" size="40" ></td>
    </tr>
    
     <?php if($type==4){?>
      <tr>
      <td align="right">Device Type:</td>
     <td> <select name="AuthorizeCPDeviceType" id="AuthorizeCPDeviceType" class="inf-select default-input" style="width:268px; height:25px; padding:2px;">
         <option value="">Please select a device type</option>
        <option value="1" <?php if($merchantRecords['DeviceType']=='1')echo 'selected';  ?>>Unknown</option>
        <option value="2" <?php if($merchantRecords['DeviceType']=='2')echo 'selected';  ?>>Unattended Terminal</option>
        <option value="3" <?php if($merchantRecords['DeviceType']=='3')echo 'selected';  ?>>Self Service Terminal</option>
        <option value="4" <?php if($merchantRecords['DeviceType']=='4')echo 'selected';  ?>>Electronic Cash Register</option>
        <option value="5" <?php if($merchantRecords['DeviceType']=='5')echo 'selected';  ?>>Personal Computer-Based Terminal</option>
        <option value="6" <?php if($merchantRecords['DeviceType']=='6')echo 'selected';  ?>>AirPay</option>
        <option value="7" <?php if($merchantRecords['DeviceType']=='7')echo 'selected';  ?>>Wireless POS</option>
        <option value="8" <?php if($merchantRecords['DeviceType']=='8')echo 'selected';  ?>>Website</option>
        <option value="9" <?php if($merchantRecords['DeviceType']=='9')echo 'selected';  ?>>Dial Terminal</option>
        <option value="10" <?php if($merchantRecords['DeviceType']=='10')echo 'selected';  ?>>Virtual Terminal</option>
        </select>
        </td>   
       </tr>
     <?php } ?>
    <tr>
      <td align="right">Test Mode:</td>
      <td><Select name="MerchantAccountMode" id="MerchantAccountMode" style="width:268px; height:25px; padding:2px;">
       <option  value="">Please select Mode</option>
       <option value="-1" <?php if($merchantRecords['Mode']=='-1')echo 'selected';  ?>>Live Mode</option>
       <option value="0" <?php if($merchantRecords['Mode']=='0')echo 'selected';  ?>>Test Mode</option>
       <option value="1" <?php if($merchantRecords['Mode']=='1')echo 'selected';  ?>>Simulator Mode</option>
      
      </Select></td>
    </tr>
    <tr>
      <td colspan="2" height="20" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        
       
      </td>
    </tr>
   </table>
   </div>   </div>
   <?php } ?>
   <div style="height:25px;">&nbsp;</div>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
       <td  colspan="2">
	   <div align="center">
		<input type="submit" name="Submit" value="Save" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()" />
		 
			<input type="hidden" name="oldimage"  value=""/></div>
          	 <input type="hidden" name="postback" value="1" />
        	<input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" /></div>
       </td>
		</tr>
		</table>
</form>

<form name="deleteform" id="deleteform" action="ManageMerchantAcc.php" method="post">
  <input type="hidden" name="deleterecord" class="MOSGLsmButton"  id="deleterecord" value="Delete" />
  <input name="id" type="hidden" value="<?php echo $_REQUEST['id'];?>" size="40" style="height:20px;">
</form>
