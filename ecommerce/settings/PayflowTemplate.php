<table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" height="400px">
    <tr>
      <td  colspan="2" id="name"><b><?php echo $MerchantVal;?> </b></td>
    </tr>
    <tr>
      <td  colspan="2"><b>Account Information</b></td>
    </tr>
   
    <tr>
      <td align="right">Name:</td>
      <td><input name="MerchantAccName" type="text" value="<?php echo $merchantRecords['AccountName']; ?>" size="40" style="height:20px;">
      
      </td>
    </tr>
    <tr>
      <td align="right">User:</td>
      <td><input name="MerchantUser" type="text" value="<?php echo $merchantRecords['PayFlowUser']; ?>" size="40" style="height:20px;"></td>
    </tr>
    <tr>
      <td align="right">Vendor:</td>
      <td><input name="MerchantVendor"  type="text"  id="MerchantTransactionKey" value="<?php echo $merchantRecords['PayFlowVendor']; ?>"  size="40" style="height:20px;">
       
      </td>
    </tr>
    <tr>
      <td align="right">Partner:</td>
      <td><input name="MerchantPartner"  id="MerchantAccountAccountLimit" type="text" value="<?php echo $merchantRecords['PayFlowPartner']; ?>" size="40" style="height:20px;"></td>
    </tr>
    
 
      <tr>
      <td align="right">Password:</td>
     <td> 
     <input name="MerchantPassword" id="MerchantPassword" type="text" value="<?php echo $merchantRecords['PayFlowPassword']; ?>" size="40" style="height:20px;">
        
        </td>   
       </tr>
       
       <tr>
      <td align="right">Account Limit:</td>
      <td><input name="MerchantAccountAccountLimit"  id="MerchantAccountAccountLimit" type="text" value="<?php echo $merchantRecords['AccountLimit']; ?>" size="40" style="height:20px;"></td>
    </tr>
       
       <tr>
      <td align="right">Currency:</td>
      <td><Select name="Currency" id="Currency" style="width:268px; height:25px; padding:2px;">
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
        <input type="submit" name="Submit" class="Ecom_Link" style="margin-top:6px;" value="Save">
        <input type="button" name="DeleteMerchant" class="Ecom_Link" style="margin-top:6px;" id="DeleteMerchant" value="Delete"  />
      </td>
    </tr>
   </table>