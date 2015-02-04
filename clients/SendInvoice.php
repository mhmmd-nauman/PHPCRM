<?php
if($SelfPayment == 1){
	require_once $_SERVER['DOCUMENT_ROOT']."/lib/outer_include.php";
}else{
	include $_SERVER['DOCUMENT_ROOT']."/lib/include.php";
}

$invoice_email_sent = 0;
# Client/Recipient Email
$to = $_REQUEST['Email'];
$client_name = $_REQUEST['fName']." ".$_REQUEST['sureName'];

$AgentID = $_SESSION['Member']['ID'];
$Bcc = "orders@xurli.com";
$BccName = "Xurli";
# Agent contact info
if($SelfPayment == 1 || $CollectingPayment == "Later"){
	$AgentID = $AgentDet[0]['ID'];
	$agent_email = $AgentDet[0]['Email'];
	$agent_name = $AgentDet[0]['FirstName']." ".$AgentDet[0]['LastName'];
	$agent_first =$AgentDet[0]['FirstName'];
	if(!empty($AgentDet[0]['Phone']) and !empty($AgentDet[0]['Phone_Ext'])){
		$agent_phone = $AgentDet[0]['Phone']." Ext:".$AgentDet[0]['Phone_Ext'];
	}elseif(!empty($AgentDet[0]['Phone']) and empty($AgentDet[0]['Phone_Ext'])){
		$agent_phone = $AgentDet[0]['Phone'];
	}
}else{
	
	$AgentDet = $objuser->GetAgent("ID = '$AgentID'",array("*"));
	$agent_email = $AgentDet[0]['Email'];
	$agent_name = $AgentDet[0]['FirstName']." ".$AgentDet[0]['LastName'];
	$agent_first =$AgentDet[0]['FirstName'];
	
	if(!empty($AgentDet[0]['Phone']) and !empty($AgentDet[0]['Phone_Ext'])){
		$agent_phone = $AgentDet[0]['Phone']." Ext:".$AgentDet[0]['Phone_Ext'];
	}elseif(!empty($AgentDet[0]['Phone']) and empty($AgentDet[0]['Phone_Ext'])){
		$agent_phone = $AgentDet[0]['Phone'];
	}
}

# Get the details of Agents Company so that we can set the name of the agents company and the
# company details in the mail below which will sent the email based on the details of the 
# company to which the agent belongs to. Lets begin with the function to fetch the details 
# of the comany
$AgentCompanyDetails = $objClient->FetchAgentCompanyDetailsforInvoice($AgentID);

# Agent Phone
if(!empty($agent_phone)){
	$Phone = $agent_phone;
}elseif(!empty($agent_name[0]['Phone']) and !empty($agent_name[0]['Phone_Ext'])){
	$Phone = $agent_name[0]['Phone']." Ext: ".$agent_name[0]['Phone_Ext'];
}elseif(!empty($agent_name[0]['Phone']) and empty($agent_name[0]['Phone_Ext'])){
	$Phone = $agent_name[0]['Phone'];
}elseif(!empty($agent_name[0]['AlternatePhone']) and !empty($agent_name[0]['Phone_Ext'])){
	$Phone = $agent_name[0]['AlternatePhone']." Ext: ".$agent_name[0]['Phone_Ext'];
}elseif(!empty($agent_name[0]['AlternatePhone']) and empty($agent_name[0]['Phone_Ext'])){
	$Phone = $agent_name[0]['AlternatePhone'];
}else{
	$Phone = "";
}



$verification = $objorder->returnproductdetails(" OrderID = '" . $inserted_order . "' ",array("*"));

$order_invoice_array = $objorder->GetAllOrderDetailWithProduct("OrderID='".$inserted_order."' ",array("*"));

$_SESSION['orderid'] = $inserted_order;

$forcePaid=true;

$dir = dirname(__FILE__)."/../";
ob_start();
if($Order_created_without_charge == 1){
	require_once($dir.'/ecommerce/orders/pdf_email/SendOnlyInvoice.php');
}else{
	require_once($dir.'/ecommerce/orders/pdf_email/pdf.php');
}
$pdf_html = ob_get_contents();
ob_end_clean();

//require_once($dir.'/ecommerce/orders/pdf_email/dompdf/dompdf_config.inc.php');
require_once($dir.'/chargebacks/dompdf/dompdf_config.inc.php');
		
$dompdf = new DOMPDF(); # Create new instance of dompdf
$dompdf->load_html($pdf_html); # Load the html
$dompdf->set_paper("letter", "portrait");
$dompdf->render(); # Parse the html, convert to PDF
$pdf_content = $dompdf->output(); # Put contents of pdf into variable for later

# Get the contents of the HTML email into a variable for later
ob_start();
$html_message = "Dear ". $client_name.",";
$html_message .= "<br/><br/>";
$html_message .= "Congratulations! You have taken the first step in <br/>";
$html_message .= "properly placing your business listing on the Internet.<br/><br/>";
$html_message .= "One of our listing fulfillment experts will be contacting<br/>";
$html_message .= "you shortly to go over your business listing details.<br/><br/>";
if($Order_created_without_charge == 1){
	$html_message .= "An invoice showing your order is attached.<br/><br/>";
}else{
	
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
					$path = SITE_ADDRESS."agreements/gyb.php";
					$params .="&embed=false";
				} else {
					$path = "http://xurli.com/gyb-consent";	
					$params .="&embed=true";
				}
				
			} else if($isWebsite==true) {
				if($user_Company_array[0]['ID']!="2") {
					$path = SITE_ADDRESS."agreements/websites.php";
					$params .="&embed=false";

				} else {
					$path = "http://xurli.com/website-consent";
					$params .="&embed=true";
				}
				
			}
			
			$html_message .= "Building and/or verifying your listing(s) requires written<br />
authorization for us to proceed on your behalf.<br /><br />Please click the button below authorizing us to<br />
begin work on your listing(s):<br /><br />";
			
			$html_message .= "<a href='" . $path . $params . "' target='_blank' style='background: green; display: inline-block; color: #fff; padding: 10px; text-decoration: none; margin: 5px 0;'>Authorize order</a><br /><br/>"; 
			
		} 
	}
	
	
}

if($_REQUEST['select_pay_process'] == 2){
	$html_message .="As we discussed with you there is currently<br />
an issue that is potentially limiting <br />
the exposure your business is receiving online.<br /><br />
Click the link below to complete your order and repair the issue.<br /><br /><a href='".SITE_ADDRESS.".OrderForm.php?OrderID=".$inserted_order."&MemberID=".$added_member_id."&CPLID=".trim($UniqueCPLID)."' style='background-color: #78CD51; border: 1px solid #72A53B; color: #FFFFFF; cursor: pointer; font-size: 13px; font-style: normal; font-weight: normal; line-height: 20px; margin-top: 5px; padding: 3px 24px; text-align: center; text-decoration: none; vertical-align: middle; white-space: normal;'>Repair Listing Now</a><br/><br/>";
}
$html_message .= "An invoice showing your order is attached.<br/><br/>";

$html_message .= "<br/>";
$html_message .= "Best regards,";
$html_message .= "<br/><br/>";
$html_message .= $agent_name;
$html_message .= "<br/>";
$html_message .= "Online Presence Specialist";
$html_message .= "<br/>";
$html_message .= $agent_email;
$html_message .= "<br/>";
$html_message .= $Phone;


$html_message .= "<br/><br/>";
$html_message .= '<div>

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
and Google AdWords Certified.<br>

<br>
<a href="http://www.bbb.org/utah/business-reviews/web-design/220-marketing-group-in-st-george-ut-22360109#bbbseal" target="_blank"><img alt="BBB Seal" src="http://seal-utah.bbb.org/logo/erhzbus/220-marketing-group-22360109.png"></a><br>
<br>
<a href="http://www.ripoffreport.com/r/xurli-xerly-small-business-online-presense-marketing-promotion-st-george-utah-1143128" target="_blank"><img alt="Ripoff Report" src="http://xurli.com/assets/assets/icon-135x61-ripoff-report-verified.jpg"></a><br>
<br>
<a href="https://www.google.com/partners/?hl=en-US#i_profile;idtf=100439723964053260930;" target="_blank"><img alt="Google Partner" src="http://xurli.com/assets/Uploads/_resampled/resizedimage12642-adwords-certified-header.png"><br>
</a>ID # 100439723964053260930

</div>
</div>';
$html_message .= "<br/><br/>Thank you for your business!";
$html_message .= "<br/><br/>";
//$html_message .= "NOTICE OF CONFIDENTIALITY: **This E-mail and any of its attachments may contain proprietary information, which is privileged, confidential, or subject to copyright. This E-mail is intended solely for the use of the individual or entity to which it is addressed. If you are not the intended recipient of this E-mail, you are hereby notified that any dissemination, distribution, copying, or action taken in relation to the contents of and attachments to this E-mail is strictly prohibited and may be unlawful. If you have received this E-mail in error, please notify the sender immediately and permanently delete the original and any copy of this E-mail and any printout. Thank You.**";
ob_end_clean();


# This is the Mail which will be sent to the Xurli Billing Only as a Separate Email.
# This Mail will include all the Contents above as well as few other details which 
# Must ONLY AND ONLY sent to the Xurli billing.. Neither the Agent nor the Client
# ONLY ONLY XURLI BILLING TEAM.
ob_start();
$html_message_billing = "Dear ". $client_name.",";
$html_message_billing .= "<br/><br/>";

if($_REQUEST['select_pay_process'] == 2){
	$html_message_billing .= "Click the button below to complete the payment noted below.<br/><br/>"."<a href='".SITE_ADDRESS."OrderForm.php?OrderID=".$inserted_order."&MemberID=".$added_member_id."&CPLID=".trim($UniqueCPLID)."' style='background-color: #78CD51; border: 1px solid #72A53B; color: #FFFFFF; cursor: pointer; font-size: 13px; font-style: normal; font-weight: normal; line-height: 20px; margin-top: 5px; padding: 3px 24px; text-align: center; text-decoration: none; vertical-align: middle; white-space: normal;'>Complete Verification Now</a><br/><br/>";
}

if($_REQUEST['select_pay_process'] == 2){
	$Process_Later_Date = date("<b>M d</b>, Y",strtotime($_REQUEST['process_later_date']));
	$html_message_billing .= "<b>Payment Method Selected: Credit Card (Process Later)</b><br/>";
	$html_message_billing .= "<b>Process Payment On: $Process_Later_Date</b><br/>";
	$html_message_billing .= "<b>Notes: ".$_REQUEST['paymentnoteswhileorder']."</b></br>";
}elseif($_REQUEST['select_pay_process'] == 3){
	$html_message_billing .= "<b>Payment Method Selected: eCheck</b><br/>";
	$html_message_billing .= "<b>Process Payment On: $Process_Later_Date</b><br/>";
	$html_message_billing .= "<b>Notes: ".$_REQUEST['paymentnoteswhileorder']."</b></br>";
}elseif($_REQUEST['select_pay_process'] == 4){
	$html_message_billing .= "<b>Payment Method Selected: Create Order & Client Only (Created Client and Order only without any payment details.)</b><br/>";
	$html_message_billing .= "<b>Notes: ".$_REQUEST['paymentnoteswhileorder']."</b></br>";
}

$html_message_billing .= "<br/><br/>Agent Name: ";
$html_message_billing .= $agent_name;
$html_message_billing .= "<br/>";
$html_message_billing .= "Online Presence Expert";
$html_message_billing .= "<br/>";
$html_message_billing .= $Phone;
$html_message_billing .= "<br/>";
$html_message_billing .= $agent_email;
$html_message_billing .= "<br/><br/>";
$html_message_billing .= "NOTICE OF CONFIDENTIALITY: **This E-mail and any of its attachments may contain proprietary information, which is privileged, confidential, or
subject to copyright. This E-mail is intended solely for the use of the individual or entity to which it is addressed. If you are not the intended recipient of this
E-mail, you are hereby notified that any dissemination, distribution, copying, or action taken in relation to the contents of and attachments to this E-mail is strictly
prohibited and may be unlawful. If you have received this E-mail in error, please notify the sender immediately and permanently delete the original and any copy of this E-mail
and any printout. Thank You.**";
ob_end_clean();

# Set the variables for company details to mention in the mail
$CompanyMail = $AgentCompanyDetails[0]['Email'];
$CompanyMail = (!empty($CompanyMail)) ? $CompanyMail : "orders@xurli.com";
$CompanyName = $AgentCompanyDetails[0]['CompanyName'];
$CompanyName = (!empty($CompanyName)) ? $CompanyName : "Xurli";
# Subject Line
if($GetDetailsForPayment[0]['Status']=="Paid"){
	$start = "Paid ";
} else {
	$start = "Open ";	
}
$start = "Paid ";
//$start = $CompanyName . " ";
$subject = $start . "invoice for ".$_REQUEST['fName']." ".$_REQUEST['sureName'];
//$subject = "Order from ".$CompanyName." for ".$_REQUEST['fName']." ".$_REQUEST['sureName'];
# Added this page to include more emails. Also the receipt which will be added along the email
# to the client
if(@$Order_created_without_charge != 1){
	require_once "SendReceipt.php";
}

#added on 19th march 2014
	require 'sendgrid-php/sendgrid-php/vendor/autoload.php';
	require 'sendgrid-php/sendgrid-php/lib/SendGrid.php';
	$path_pdf = "";
	if(@$CollectingPayment == "Later"){
		$invoice_no = $genrated_invoice_number."-1";
	}
	
	$path_pdf = $_SERVER['DOCUMENT_ROOT']."/clients/attachments/";
	$sendgrid = new SendGrid(SENDGRID_USERNAME, SENDGRID_PASSWORD);
	$mail = new SendGrid\Email();
	$pdf_file = $dompdf->output();
	file_put_contents($path_pdf.$invoice_no.'.pdf', $pdf_file);
	//$mailling_list = array($to);
	$billing = "orders@xurli.com";
	if($user_Company_array[0]['ID']=="3"){
		$billing = "orders@xerly.com";	
	}
	
	if($AgentCompanyDetails[0]['SendInvoice']==1) {
		
		$mail->addTo($to)->
			addTo($agent_email)->
			addTo("gyb-orders@xurli.com")
			//addTo($Bcc)->
			//addTo($billing);
			;
		
	} else {
		$mail->
			addTo("gyb-orders@xurli.com");
			
	}
	
	$mail->
			# setBcc($mailling_bcc)->
			setFrom($CompanyMail)->
			setFromName($CompanyName)->
			setSubject($subject);
	$mail->setHtml($html_message)->
			addAttachment($path_pdf.$invoice_no.'.pdf')->
			addAttachment("../ecommerce/orders/Satisfaction_Guarantee_Refund_Policy.pdf");
#upto here

	# Mail Again to Client with the Text receipt and the attachment
	if(@$Order_created_without_charge == 1){
		# $mail_with_receipt_unpaid = new SendGrid\Email();
		# $mail_with_receipt_unpaid->addTo($to)->
				## Uncomment below line if we want the Summary email to sent to the agent as well
				## addTo($agent_email)->
				# setFrom($CompanyMail)->
				# setFromName($CompanyName)->
				# setSubject($subject);
		#$mail_with_receipt_unpaid->setHtml($html_message_receipt_unpaid);
				## addAttachment($path_pdf.$invoice_no.'.pdf');
	}else{
		# Mail the receipt after the order payment was fullfilled
		$mail_with_receipt_paid = new SendGrid\Email();
		
		if($AgentCompanyDetails[0]['SendInvoice']==0) {
			$mail_with_receipt_paid->
				addTo("gyb-orders@xurli.com");
		} else {
			$mail_with_receipt_paid->
				addTo($to)->
				addTo("gyb-orders@xurli.com");
		}
			$mail_with_receipt_paid->setFrom($CompanyMail)->
				
				setFromName($CompanyName)->
				setSubject($subject);
		$mail_with_receipt_paid->setHtml($html_message_receipt_paid);
			# addAttachment($path_pdf.$invoice_no.'.pdf');
	}

	# Mail Only To Billing @ Xurli Team
	$mail_only_billing = new SendGrid\Email();
	$mail_only_billing->addTo($billing)->
			addTo("gyb-orders@xurli.com")->
			setFrom($CompanyMail)->
			setFromName($CompanyName)->
			setSubject($subject);
	//$mail_only_billing->setHtml($html_message_billing);
	
# Send the mail only if the Order was created with charge.
if(@$Order_created_without_charge != 1){
	if (@$sendgrid->send($mail)){
		$invoice_email_sent = 1;
	}
	else{
		# print_r($failures);
	}
}

if(@$Order_created_without_charge == 1){
	# Mailed to Client when the Order is Unpaid along with the invoice also sent the receipt.
	//$sendgrid->send($mail_with_receipt_unpaid);
	# Mail to the Billing team the addition details including the notes and the payment method if pay now not selected
	//$sendgrid->send($mail_only_billing);
}else{
	$sendgrid->send($mail_with_receipt_paid);
}

unset($_SESSION['orderid']);
?>