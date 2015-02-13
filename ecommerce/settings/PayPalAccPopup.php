<?php 
require_once "../../lib/include.php";
$utilObj = new util();

$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
if(isset($_REQUEST['id'])){
$strwhere='MerchantId='.$_REQUEST['id'].'';
$merchantRecords=$utilObj->getSingleRow('ManageMerchantAcc',$strwhere);
$MerchantVal='Edit Paypal Account';
}else{
$MerchantVal='Add Paypal Account';
}
$checked='';
 if($merchantRecords['Mode']=='0') $checked='Checked';
 
?>

<script type="text/javascript">
$(document).ready(function() {
$("#DeleteMerchant").click(function(){
 $('#deleteform').submit();
});

 $("#expressCheckoutEnabled").click(function(){
      var action=$("#Task").val();
	  var APIUserNamehid= $("#APIUserNamehidden").val();
	  var APIPasswordhid=$("#APIPasswordhidden").val();
	  var APISingnaturehid=$("#APISingnaturehidden").val();
	
		// If checked
		if ($("#expressCheckoutEnabled").is(":checked"))
		{
			//show the hidden div
			//$("#expresschek").show();
		   if(action=='update'){
				$("#APIUserName").val(APIUserNamehid);
				$("#APIPassword").val(APIPasswordhid);
				$("#APISingnature").val(APISingnaturehid);
			}
			
			$("#APIUserName").attr('disabled','');
			$("#APIPassword").attr('disabled','');
			$("#APISingnature").attr('disabled','');
			
		}
		else
		{
			//otherwise, hide it
			//$("#expresschek").hide();
			
			//if(action=='update'){
				$("#APIUserName").val('');
				$("#APIPassword").val('');
				$("#APISingnature").val('');
			//}
			
			$("#APIUserName").attr('disabled', 'disabled');
			$("#APIPassword").attr('disabled', 'disabled');
			$("#APISingnature").attr('disabled', 'disabled');
		}
	  });
	  
});
</script>

<div style="width:550px;">
<form name="form1" method="post" action="ManagePayPalAcc.php">
<input name="AccountType" type="hidden" value="<?php echo $type; ?>" size="40" style="height:20px;">
<input name="Task" id="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" style="height:20px;">
<input name="id" type="hidden" value="<?php echo $_REQUEST['id'];?>" size="40" style="height:20px;">

 <table width="100%" border="0" cellspacing="3" cellpadding="2" align="center">
    <tr>
      <td  colspan="2" id="name"id="name"style="background-color:<?php echo "#".$SystemSettingsArray[0]['PopupColor']; ?>"><b><?php echo $MerchantVal;?> </b></td>
    </tr>
    <tr>
      <td  colspan="2"><b>Account Information</b></td>
    </tr>
       <tr>
      <td  colspan="2">
      <b style="color:#FF0000">PayPal Standard and Express Checkout do not support auto-charging of or payment plans.
       These invoices will need to be billed manually. </b>
     </td>
    </tr>
    
    <tr>
      <td align="right">PayPal Email Address:</td>
      <td><input name="MerchantAccName" type="text" value="<?php echo $merchantRecords['AccountName']; ?>" size="40" style="height:20px;"></td>
    </tr>
    <tr>
      <td align="right">Currency:</td>
      <td>
        <Select name="Currency" id="Currency" style="width:268px; height:25px; padding:2px;">
        <option value="">Please select a currency</option>
        <option value="USD" <?php if($merchantRecords['PayFlowCurrency']=='USD')echo 'selected';  ?>>US Dollar</option>
        <option value="EUR" <?php if($merchantRecords['PayFlowCurrency']=='EUR')echo 'selected';  ?>>Euro</option>
        <option value="GBP" <?php if($merchantRecords['PayFlowCurrency']=='GBP')echo 'selected';  ?>>UK Pound</option>
        <option value="CAD" <?php if($merchantRecords['PayFlowCurrency']=='CAD')echo 'selected';  ?>>Canadian Dollar</option>
        <option value="JPY" <?php if($merchantRecords['PayFlowCurrency']=='JPY')echo 'selected';  ?>>Japanese Yen</option>
        <option value="AUD" <?php if($merchantRecords['PayFlowCurrency']=='AUD')echo 'selected';  ?>>Australian Dollar</option>
      </Select></td>
    </tr>
    <tr>
      <td align="right"><input type="checkbox" value="0" name="MerchantAccountMode" id="MerchantAccountMode"  <?php echo $checked; ?> /></td>
      <td>Put Account in Test Mode For Testing</td>
    </tr>
    <tr>
    <td align="right"><input type="checkbox" value="yes" name="expressCheckoutEnabled" id="expressCheckoutEnabled" <?php if(!empty($merchantRecords['PayFlowUser'])){?> checked="checked" <?php } ?>></td>
     <td>Use PayPal Express Checkout</td>  
     </tr>
     <tr id="expresschek">
     <td colspan="2"> 
        <table border="0" cellspacing="3" cellpadding="2" align="center" >
          <tr>
          <td align="right">User Name &nbsp;&nbsp;:</td>
          <td><input type="text" value="<?php echo $merchantRecords['PayFlowUser']; ?>" name="APIUserName" id="APIUserName" size="40" style="height:20px;"  <?php if($merchantRecords['PayFlowUser']==''){ ?> disabled="disabled" <?php } ?> ></td>
          </tr>
           <tr>
          <td align="right">Password:</td>
          <td><input type="text" value="<?php echo $merchantRecords['PayFlowPassword']; ?>" name="APIPassword" id="APIPassword" size="40" style="height:20px;" <?php if($merchantRecords['PayFlowPassword']==''){ ?> disabled="disabled" <?php } ?> ></td>
          </tr>
           <tr>
          <td align="right">Singnature:</td>
          <td><input type="text" value="<?php echo $merchantRecords['PayFlowVendor']; ?>" name="APISingnature" id="APISingnature" size="40" style="height:20px;" <?php if($merchantRecords['PayFlowVendor']==''){ ?> disabled="disabled" <?php } ?> >
          
          
          <input type="hidden" value="<?php echo $merchantRecords['PayFlowUser']; ?>"  id="APIUserNamehidden" />
          <input type="hidden" value="<?php echo $merchantRecords['PayFlowPassword']; ?>"  id="APIPasswordhidden" />
          <input type="hidden" value="<?php echo $merchantRecords['PayFlowVendor']; ?>"   id="APISingnaturehidden"/>
          </td>
          </tr>
            <!---Express checkout options---->
          </table>
        
        </td>
     </tr>
     <tr>
     
      <td colspan="2" height="20" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" name="Submit" class="Ecom_Link" style="margin-top:6px; margin-right:4px;" value="Save">
        <input type="button" name="DeleteMerchant" class="Ecom_Link" style="margin-top:6px;" id="DeleteMerchant" value="Delete"  />
      </td>
    </tr>
   </table>

</form>

<form name="deleteform" id="deleteform" action="ManagePayPalAcc.php" method="post">
  <input type="hidden" name="deleterecord" class="MOSGLsmButton"  id="deleterecord" value="Delete" />
  <input name="id" type="hidden" value="<?php echo $_REQUEST['id'];?>" size="40" style="height:20px;">
</form>
</div>