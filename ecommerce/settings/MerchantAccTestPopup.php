<?php 
require_once "../../lib/include.php";
require_once('../products/AuthorizeNet-sdk/AuthorizeNet.php'); 

$objUsers = new Users();
$utilObj = new util();

if(is_array($_SESSION['M_Group_ID']) && in_array("1",$_SESSION['M_Group_ID'])){
 $MemberRows = $objUsers->GetAllUsers(USERS.".ID = '".$_SESSION['Member']['ID']."'",array(USERS.".*"));
 // echo "<pre>";
 // print_r($MemberRows);
}

if($_REQUEST['id']){
$strwhere='MerchantId='.$_REQUEST['id'].'';
$merchantRecords=$utilObj->getSingleRow('ManageMerchantAcc',$strwhere);
}

extract($_POST);
if(!empty($Submit)){
//print_r($_POST);
  if($merchantRecords['AccountType']==16){

	   if($merchantRecords['Mode']=='-1')
		$mode=0;
		else
		$mode=1;
	
	$PayFlowUser=trim($merchantRecords['PayFlowUser']);
	$PayFlowVendor=trim($merchantRecords['PayFlowVendor']);
	$PayFlowPartner=trim($merchantRecords['PayFlowPartner']);
	$PayFlowPassword=trim($merchantRecords['PayFlowPassword']);
	$PayFlowCurrency=trim($merchantRecords['PayFlowCurrency']);
	
	$name=explode(' ',$BillingName);
	$year = substr((string)$CreditCard0ExpirationYear,-2);  
	$CreditCard0ExMonth = $CreditCard0ExpirationMonth.$year;
	
	$totalAmtPaid=$TransAMT;
	require_once('../products/PayflowApi/payflow.php'); 
	
	if ($result['RESPMSG']=='Approved') {
		   echo  $ResponseMessage="Success! The test credit card has been charged! Transaction ID: " .$result['PNREF'];
		} else {
			echo $error;
		}
  
  }else{
       
	if($merchantRecords['Mode']=='-1')
	  $mode=false;
	  else
	   $mode=true;
	   
	   $LoginID=trim($merchantRecords['LoginID']);
	   $TransKey=trim($merchantRecords['TransactionKey']);
	   
	   if($merchantRecords['AccountType'] == 4){
		$transaction = new AuthorizeNetCP($LoginID,$TransKey);
		$transaction->device_type =''.$merchantRecords['DeviceType'].'';
		
	  }
	   else
		$transaction = new AuthorizeNetAIM($LoginID,$TransKey);
		
		$transaction->setSandbox($mode);
		$year = substr((string)$CreditCard0ExpirationYear,-2);  
		
		$transaction->amount = $TransAMT;
		$transaction->card_num = $CreditCard0CardNumber;
		$transaction->exp_date = $CreditCard0ExpirationMonth.'/'. $year;
		
			$name = explode(' ',$BillingName);
			$transaction->description        = $description = "";
			$transaction->first_name         = $first_name = $name[0];
			$transaction->last_name          = $last_name = $name[1];
			$transaction->company            = $company = "";
			$transaction->address            = $address = $CreditCard0BillAddress1;
			$transaction->city               = $city = $CreditCard0BillCity;
			$transaction->state              = $state = $CreditCard0BillState;
			$transaction->zip                = $zip = $CreditCard0BillZip;
			$transaction->country            = $country = $CreditCard0BillCountry;
			$transaction->phone              = $phone = $CreditCard0PhoneNumber;
			$transaction->fax                = $fax = "";
			$transaction->email              = $email = $CreditCard0Email;
			$transaction->cust_id            = $customer_id = " ";
			$transaction->customer_ip        = $_SERVER['REMOTE_ADDR'];
			/*$transaction->invoice_num        = $invoice_number = $CreditCard0VerificationCode;*/
			$transaction->invoice_num        = "12345";
			$transaction->ship_to_first_name = $ship_to_first_name = $name[0];
			$transaction->ship_to_last_name  = $ship_to_last_name = $name[1];
			$transaction->ship_to_company    = $ship_to_company = "";
			$transaction->ship_to_address    = $ship_to_address = $CreditCard0BillAddress1;;
			$transaction->ship_to_city       = $ship_to_city = $CreditCard0BillCity;;
			$transaction->ship_to_state      = $ship_to_state = $CreditCard0BillState;
			$transaction->ship_to_zip        = $ship_to_zip_code = $CreditCard0BillZip;
			$transaction->ship_to_country    = $ship_to_country = "US";
			$transaction->tax                = $tax = "0.00";
			$transaction->freight            = $freight = "";
			$transaction->duty               = $duty = "";
			$transaction->tax_exempt         = $tax_exempt = "";
			$transaction->po_num             = $po_num = "";
			
	
		$response = $transaction->authorizeAndCapture();
		if ($response->approved) {
		   echo  $ResponseMessage = "Success! The test credit card has been charged! Transaction ID: " . $response->transaction_id;
		} else {
			echo $ResponseMessage = $response->response_reason_text;
		}
	}
  	
   exit;
 }
?>
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>-->

<script>
  $(document).ready(function() {
   $("#submitform").submit(function(event){
    // setup some local variables
      var $form = $(this),
        // let's select and cache all the fields
        $inputs = $form.find("input, select, button, submit textarea"),
        // serialize the data in the form
        serializedData = $form.serialize();

    // let's disable the inputs for the duration of the ajax request
    $inputs.attr("disabled", "disabled");
	$('#waitingmsg').show();

    // fire off the request to /form.php
    $.ajax({
        url: "MerchantAccTestPopup.php",
        type: "post",
        data: serializedData,
        // callback handler that will be called on success
        success: function(response, textStatus, jqXHR){
            // log a message to the console
			
			
			$('#ResponseMsg').html('<td  align="center" style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="2" >'+response+'</td>');
          
        },
        // callback handler that will be called on error
        error: function(jqXHR, textStatus, errorThrown){
            // log the error to the console
            alert(
                "The following error occured: "+
                textStatus, errorThrown
            );
        },
        // callback handler that will be called on completion
        // which means, either on success or error
        complete: function(){
            // enable the inputs
            $inputs.removeAttr("disabled");
		    $('#waitingmsg').hide();
        }
    });

    // prevent default posting of form
    event.preventDefault();
});

});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css">

 <form method="POST" action="" id="submitform">
 <input type="hidden" name="CreditCard0Id" id="CreditCard0Id" value="0">
 <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>">
 <input type="hidden" name="Submit" value="Save">
 <input value="1" id="TransAMT" name="TransAMT" type="hidden" />
 <input name="TRXTYPE" type="hidden" value="S">
 <div class="containerpopup">
   <table width="100%" border="0" cellspacing="3" cellpadding="2" align="center">
     
      <tr>
	  <td>
      <table width="100%" border="0" cellspacing="3" cellpadding="2" align="center">
    <tr>
      <td  colspan="3" id="tabsubheading">Billing Information</td>
    </tr>
    <tr>
      <td id="tdleft">Billing Name:</td>
      <td id="tdmiddle"><input  id="BillingName" name="BillingName" type="text"  value="<?php echo $MemberRows[0]['FirstName'].'&nbsp;'.$MemberRows[0]['Surname'];?>" class="product"/></td>
	  <td id="tdright"></td>
    </tr>
    <tr>
      <td >Address1:</td>
      <td><input value="<?php echo $MemberRows[0]['Address']; ?>"  id="CreditCard0BillAddress1" name="CreditCard0BillAddress1" type="text" class="product"/></td>
	  <td>&nbsp;</td>
    </tr>
    <tr>
      <td >Address2:</td>
      <td><input   value="<?php echo $MemberRows[0]['AddressLine2']; ?>"  id="CreditCard0BillAddress2" name="CreditCard0BillAddress2" type="text" class="product"/>
         </td>
		 <td>&nbsp;</td>
    </tr>
    <tr>
      <td >City:</td>
      <td><input value="<?php echo $MemberRows[0]['City']; ?>"  id="CreditCard0BillCity" name="CreditCard0BillCity" type="text" class="product"/></td>
	  <td>&nbsp;</td>
    </tr>
    <tr>
      <td >State/Province</td>
      <td><input value="<?php echo $MemberRows[0]['member_state']; ?>"  id="CreditCard0BillState" name="CreditCard0BillState" type="text" class="product"/></td>
	  <td>&nbsp;</td>
    </tr>
    <tr>
      <td >Zip</td>
      <td><input value="<?php echo $MemberRows[0]['member_zip']; ?>"  id="CreditCard0BillZip" name="CreditCard0BillZip" type="text" class="product"/></td>
	  <td>&nbsp;</td>
    </tr>
    
     <tr>
      <td >Country</td>
      <td><input value="<?php echo $MemberRows[0]['Country']; ?>"  id="CreditCard0BillCountry" name="CreditCard0BillCountry" type="text" class="product"/></td>
	  <td>&nbsp;</td>
    </tr>
    
     <tr>
      <td >Email</td>
      <td><input value="<?php echo $MemberRows[0]['Email']; ?>"  id="CreditCard0Email" name="CreditCard0Email" type="text" class="product"/></td>
	  <td>&nbsp;</td>
    </tr>
    
     <tr>
      <td >Phone</td>
      <td><input value="<?php echo $MemberRows[0]['Phone']; ?>"  id="CreditCard0PhoneNumber" name="CreditCard0PhoneNumber" type="text" /></td>
	  <td>&nbsp;</td>
    </tr>
    </table>
	<table width="100%" border="0" cellspacing="3" cellpadding="2" align="center">
      <tr valign="top">
      <td  colspan="3" id="tabsubheading">      
      Credit Card Information</td>
      </tr>
      
      <tr>
          <td id="tdleft">Card Type</td>
           <td id="tdmiddles">
             <select id="CreditCard0CardType" name="CreditCard0CardType" class="product" >
             <option value="">Please select a card type</option>
             <option value="American Express">American Express</option>
            <option value="Discover">Discover</option>
            <option value="MasterCard">MasterCard</option>
            <option value="Visa">Visa</option>
            </select>
            </td>
			<td id="tdright">&nbsp;</td>
        </tr>
      
        <tr>
         <td class='label-td' >Card Number</td>
         <td><input  id="CreditCard0CardNumber" maxlength="16" name="CreditCard0CardNumber" type="text"  autocomplete="off" class="product"/></td>
		 <td>&nbsp;</td>
         </tr>
         
         <tr>
         <td class='label-td' >Expiration Month</td>
          <td>
          <select  id="CreditCard0ExpirationMonth" name="CreditCard0ExpirationMonth" class="product">
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            </select>
              </td>
			  <td>&nbsp;</td>
           </tr>
           <tr>
              <td class='label-td' >Expiration Year</td>
               <td>
                <select id="CreditCard0ExpirationYear" name="CreditCard0ExpirationYear"  class="product">
                <option value="2010">2010</option>
                <option value="2011">2011</option>
                <option value="2012">2012</option>
                <option value="2013">2013</option>
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                </select>
                </td>
				<td>&nbsp;</td>
              </tr>
              <tr>
              <td class='label-td' >CVC</td>
               <td><input  class="product" id="CreditCard0VerificationCode" name="CreditCard0VerificationCode" type="text" /></td>
			   <td>&nbsp;</td>
             </tr>
                           
              </table>
    </td>
    
     
           
          
          </td>
        </tr>
         <tr>
          <td colspan="3" align="center">
           <b>Note:</b> This will allow you to test the merchant
             account to see if it is working properly.Only $0.01 will be charged to the card.<br><br>
             <strong style="display:none; color:#0033FF;" id="waitingmsg">Please Wait.....</strong>
          </td>
        </tr>
      </table>
 </div>
  <div style="height:25px;">&nbsp;</div>
   <div align="center" class="bottom_fixed">
 <input type="submit" name="Submitfrm"  class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" value="Save Changes">
 </div>
</form>

