<?php
# $Order_created_without_charge when 1, that menas any other option apart from
# Process now was selected.
# Fetch the details of the Agents Company
# Below both are same. Need to find out and separate them if needed.

if(!empty($AgentCompanyDetails))
	$agent_Company_array = $AgentCompanyDetails;
else
	$agent_Company_array = $objClient->FetchAgentCompanyDetailsforInvoice($AgentID);

/*if(@!empty($Order_created_without_charge) and @$Order_created_without_charge == 1){
	#Lets Begin the thrird mail here that is the receipt.
	$html_message_receipt_unpaid = "";
	ob_start();
	$html_message_receipt_unpaid = "Dear ". $client_name.",";
	$html_message_receipt_unpaid .= "<br/><br/>";
	$html_message_receipt_unpaid .= "Congratulations! You have taking the first step in <br/>";
	$html_message_receipt_unpaid .= "properly placing your business listing on the Internet.<br/><br/>";
	$html_message_receipt_unpaid .= "Your unpaid receipt is attached for your records.<br/><br/>";
	
	$html_message_receipt_unpaid .= "Click the button below to complete the first step to <br>activating your business presence on the Internet. <br>$agent_name is standing by waiting to fulfill your listing.<br/><br/>"."<a href='https://www.xurlios.com/OrderForm.php?OrderID=".$inserted_order."&MemberID=".$added_member_id."&CPLID=".trim($UniqueCPLID)."' style='background-color: #78CD51; border: 1px solid #72A53B; color: #FFFFFF; cursor: pointer; font-size: 13px; font-style: normal; font-weight: normal; line-height: 20px; margin-top: 5px; padding: 3px 24px; text-align: center; text-decoration: none; vertical-align: middle; white-space: normal;'>Complete Verification Now</a><br/><br/>";

	$html_message_receipt_unpaid .= "<br/><br/>";
	$html_message_receipt_unpaid .= "Best regards,";
	$html_message_receipt_unpaid .= "<br/>";
	$html_message_receipt_unpaid .= $agent_name;
	$html_message_receipt_unpaid .= "<br/>";
	$html_message_receipt_unpaid .= "Online Presence Expert";
	$html_message_receipt_unpaid .= "<br/>";
	$html_message_receipt_unpaid .= $Phone;
	$html_message_receipt_unpaid .= "<br/>";
	$html_message_receipt_unpaid .= $agent_email;
	$html_message_receipt_unpaid .= "<br/><br/>";
	$html_message_receipt_unpaid .= "------------------------------------------------------------------------------<br/>";
	$html_message_receipt_unpaid .= " UNPAID INVOICE SUMMARY <br/>";
	$html_message_receipt_unpaid .= "------------------------------------------------------------------------------<br/>";
	$html_message_receipt_unpaid .= $agent_Company_array[0]['CompanyName']."<br/>";
	$html_message_receipt_unpaid .= $agent_Company_array[0]['Address1']."<br/>";
	$html_message_receipt_unpaid .= $agent_Company_array[0]['Address2']."<br/>";
	$html_message_receipt_unpaid .= $agent_Company_array[0]['City'].", ".$agent_Company_array[0]['Zip']."<br/>";
	$html_message_receipt_unpaid .= $agent_Company_array[0]['Phone']."<br/>";
	# $invoice_no -> coming from ChargeOnGWAPI.php
	$html_message_receipt_unpaid .= "------------------------------------------------------------------------------<br/><br/>";
	$html_message_receipt_unpaid .= "Invoice ID: ".$invoice_no."<br/>";
	$html_message_receipt_unpaid .= "Invoice Date: ".date("<b>M d</b>, Y")."<br/>";
	# $added_member_id -> Coming from SaveOrderDB.php
	$html_message_receipt_unpaid .= "Customer ID: ".$added_member_id."<br/><br/>";
	# $client_name ->Coming from SendInvoice.php
	$ClientDetails = $objClient->GetAllClients(" ID = '$added_member_id' ",array("*"));
	
	$html_message_receipt_unpaid .= "Client: ".$client_name."<br/>";
	$html_message_receipt_unpaid .= $ClientDetails[0]['Address']."<br/>";
	$html_message_receipt_unpaid .= $ClientDetails[0]['Address2']."<br/>";
	$html_message_receipt_unpaid .= $ClientDetails[0]['City'].", ".$ClientDetails[0]['State']." ".$ClientDetails[0]['ZipCode']."<br/><br/>";
	
	if(!empty($_REQUEST['process_later_date']) and $_REQUEST['select_pay_process'] == 2){
		$html_message_receipt_unpaid .= "Amount Due: $".$_REQUEST['totalprice']. " by ".date("<b>M d</b>, Y",strtotime($_REQUEST['process_later_date']));
	}else{
		$html_message_receipt_unpaid .= "Amount Due: $".$_REQUEST['totalprice'];
	}
	
	$html_message_receipt_unpaid .= "<br/><br/>";
	$html_message_receipt_unpaid .= "NOTICE OF CONFIDENTIALITY: **This E-mail and any of its attachments may contain proprietary information, which is privileged, confidential, or
	subject to copyright. This E-mail is intended solely for the use of the individual or entity to which it is addressed. If you are not the intended recipient of this
	E-mail, you are hereby notified that any dissemination, distribution, copying, or action taken in relation to the contents of and attachments to this E-mail is strictly
	prohibited and may be unlawful. If you have received this E-mail in error, please notify the sender immediately and permanently delete the original and any copy of this E-mail
	and any printout. Thank You.**";
	ob_end_clean();
}else{*/
if(!empty($order_charged) and $order_charged == 1) {
	$html_message_receipt_paid = "";
	ob_start();
	$html_message_receipt_paid = "Dear ". $client_name.",";
	$html_message_receipt_paid .= "<br/><br/>";
	$html_message_receipt_paid .= "This is a text (copy) receipt in case the main invoice <br/>was delivered to your spam folder.";
	$html_message_receipt_paid .= "<br/><br/>";
	$html_message_receipt_paid .= "Congratulations! You have taken the first step in <br/>";
	$html_message_receipt_paid .= "properly placing your business listing on the Internet.<br/><br/>";
	$html_message_receipt_paid .= "One of our listing fulfillment experts will be contacting<br/>";
	$html_message_receipt_paid .= "you shortly to go over your business listing details.<br/><br/>";
	
	if($verification[0]['Verified'] == "0") {
		
		$productList = array();
		foreach((array) $order_invoice_array as $row) {
			$productList[] = $row['ProductID'];
		}
		$isGYB = false;
		$gybArray = array("20", "30", "39", "40", "41", "42", "44", "45", "54","55");
		$isWebsite = false;
		$webArray = array("46", "47", "48", "49", "50");
		foreach((array)$productList as $name) {
			//echo $name;
			if(in_array($name, $gybArray)) {
				$isGYB = true;	
			} 
			if(in_array($name, $webArray)) {
				$isWebsite = true;	
			} 
		}
		if($isGYB == true || $isWebsite == true) {
			if(!empty($order_invoice_array[0]['CPLID']) ) {
				$CPLID = trim($order_invoice_array[0]['CPLID']);
			} else {
				$CPLID = "we234ad";
			}
			$params = "?order=".$inserted_order."&client=".$added_member_id."&CPLID=".trim($UniqueCPLID);
			if($isGYB == true) {
				if($user_Company_array[0]['ID']!="2") {
					$path = "http://xurlios.com/agreements/gyb.php";
					$params .="&embed=false";
				} else {
					$path = "http://xurli.com/gyb-consent";	
					$params .="&embed=true";
				}
				
			} else if($isWebsite==true) {
				if($user_Company_array[0]['ID']!="2") {
					$path = "http://xurlios.com/agreements/websites.php";
					$params .="&embed=false";

				} else {
					$path = "http://xurli.com/website-consent";
					$params .="&embed=true";
				}
				
			}
			
			$html_message_receipt_paid .= "Building and/or verifying your listing(s) requires written<br />
authorization for us to proceed on your behalf.
<br /><br />
Please click the button below authorizing us to<br />
begin work on your listing(s):<br /><br />";
			
			$html_message_receipt_paid .= "<a href='" . $path . $params . "' target='_blank' style='background: green; display: inline-block; color: #fff; padding: 10px; text-decoration: none; margin: 5px 0;'>Authorize order</a><br /><br/>"; 
			
		} 
	}
	
	$html_message_receipt_paid .= "Your receipt is shown below for your records.<br/><br/>";

	$html_message_receipt_paid .= "<br/><br/>";
	
	$html_message_receipt_paid .= "Best regards,";
	$html_message_receipt_paid .= "<br/>";
	$html_message_receipt_paid .= $agent_name;
	$html_message_receipt_paid .= "<br/>";
	$html_message_receipt_paid .= "Online Presence Specialist";
	$html_message_receipt_paid .= "<br/>";
	$html_message_receipt_paid .= $agent_email;
	$html_message_receipt_paid .= "<br/>";
	$html_message_receipt_paid .= $Phone;
	

	$html_message_receipt_paid .= "<br/><br/>";
	
	$html_message_receipt_paid .= '<div>

    <div>
      <img src="http://xurli.com/assets/assets/icon-98x42-xurli-name.jpg"><br>
        <img src="http://xurli.com/assets/assets/icon-228x14-xurli-tagline.jpg"><br>
        <a href="tel:+18008734373" alt="Telephone" target="_blank">(800) 873-4373</a>  |  
        <a href="http://www.xurli.com/" target="_blank">www.xurli.com</a>  |  
        Mon-Fri 8-6 MST<br>
    </div>
    <div>
        <br>
        --<br>
        <br>
    
    </div>


<div>For tons of FREE tips on growing your local business\'<br>
  online presence follow us on any of these sites:<br>
  <br>
  <a href="https://www.facebook.com/XurliLocal" target="_blank"><img alt="Facebook" src="http://xurli.com/assets/assets/icon-50x50-facebook.jpg"></a>   
  
  <a href="https://twitter.com/XurliLocal" target="_blank"><img alt="Twitter" src="http://xurli.com/assets/assets/icon-50x50-twitter.jpg"></a>   
  
  <a href="https://www.linkedin.com/company/2-20-marketing-group-dba-xurli" target="_blank"><img alt="LinkedIn" src="http://xurli.com/assets/assets/icon-50x50-linked-in.jpg"></a>   
  
  <a href="http://google.com/+XurliLocal" target="_blank"><img alt="Google+" src="http://xurli.com/assets/assets/icon-50x50-google-plus.jpg"></a>   
  
  <a href="https://www.youtube.com/user/XurliLocal" target="_blank"><img alt="Youtube" src="http://xurli.com/assets/assets/icon-50x50-youtube.jpg"></a><br>
</div>
<div>
<br>
  --<br>
  <br>
</div>
<div>We love our clients and our track record shows it!<br>
  We are BBB accredited, A-rated, Ripoff Report Verified<br>
and a certified Google Partner.<br>

<br>
<a href="http://www.bbb.org/utah/business-reviews/web-design/220-marketing-group-in-st-george-ut-22360109#bbbseal" target="_blank"><img alt="BBB Seal" src="http://seal-utah.bbb.org/logo/erhzbus/220-marketing-group-22360109.png"></a><br>
<br>
<a href="http://www.ripoffreport.com/r/xurli-xerly-small-business-online-presense-marketing-promotion-st-george-utah-1143128" target="_blank"><img alt="Ripoff Report" src="http://xurli.com/assets/assets/icon-135x61-ripoff-report-verified.jpg"></a><br>
<br>
<a href="https://www.google.com/partners/?hl=en-US#i_profile;idtf=100439723964053260930;" target="_blank"><img alt="Google Partner" src="http://xurli.com/assets/assets/icon-150x51-google-partner.jpg"><br>
</a>ID # 100439723964053260930

</div>
</div>';
	# Add up t he spices here
	$html_message_receipt_paid .= "------------------------------------------------------------------------------<br/>";
	$html_message_receipt_paid .= " INVOICE SUMMARY <br/>";
	$html_message_receipt_paid .= "------------------------------------------------------------------------------<br/>";
	$html_message_receipt_paid .= $agent_Company_array[0]['CompanyName']."<br/>";
	$html_message_receipt_paid .= $agent_Company_array[0]['Address1']."<br/>";
	$html_message_receipt_paid .= $agent_Company_array[0]['Address2']."<br/>";
	$html_message_receipt_paid .= $agent_Company_array[0]['City'].", ".$agent_Company_array[0]['Zip']."<br/>";
	$html_message_receipt_paid .= $agent_Company_array[0]['Phone']."<br/>";
	
	$html_message_receipt_paid .= "------------------------------------------------------------------------------<br/><br/>";
	$html_message_receipt_paid .= "Invoice ID: ".$invoice_no."<br/>";
	$html_message_receipt_paid .= "Invoice Date: ".date("<b>M d</b>, Y")."<br/>";
	# $added_member_id -> Coming from SaveOrderDB.php
	$html_message_receipt_paid .= "Customer ID: ".$added_member_id."<br/><br/>";
	# $client_name ->Coming from SendInvoice.php
	$ClientDetails = $objClient->GetAllClients(" ID = '$added_member_id' ",array("*"));
	
	$html_message_receipt_paid .= "Client: ".$client_name."<br/>";
	$html_message_receipt_paid .= $ClientDetails[0]['Address']."<br/>";
	$html_message_receipt_paid .= $ClientDetails[0]['Address2']."<br/>";
	$html_message_receipt_paid .= $ClientDetails[0]['City'].", ".$ClientDetails[0]['State']." ".$ClientDetails[0]['ZipCode']."<br/><br/>";
	
	$ClientCCInfo = $objClient->FetchCCInformation(trim($added_member_id));
	
	$secure_credit_card = $objClient->decrypt($ClientCCInfo[0]['CreditCardNumber']);
	$credit_card = "XXXX-XXXX-XXXX-".substr($secure_credit_card, -4, 4);
	if(count($ClientCCInfo) > 0){
		$html_message_receipt_paid .= "Payment Details<br/>";
		$html_message_receipt_paid .= "Credit Card: ".$credit_card."<br/>";
		$html_message_receipt_paid .= "Amount Paid: $".$_REQUEST['totalprice']."<br/><br/>";
	}
	$html_message_receipt_paid .= "Thank you for your business!<br/><br/>";
	
	//$html_message_receipt_paid .= "NOTICE OF CONFIDENTIALITY: **This E-mail and any of its attachments may contain proprietary information, which is privileged, confidential, or subject to copyright. This E-mail is intended solely for the use of the individual or entity to which it is addressed. If you are not the intended recipient of this E-mail, you are hereby notified that any dissemination, distribution, copying, or action taken in relation to the contents of and attachments to this E-mail is strictly prohibited and may be unlawful. If you have received this E-mail in error, please notify the sender immediately and permanently delete the original and any copy of this E-mail and any printout. Thank You.**";
	ob_end_clean();
}
?>