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

$subject = "Order from Xurli for ".$_REQUEST['fName']." ".$_REQUEST['sureName'];

$Bcc = "billing@xurli.com";
$BccName = "Xurli Sales";
# Agent contact info
if($SelfPayment == 1 || $CollectingPayment == "Later"){
	$agent_email = $AgentDet[0]['Email'];
	$agent_name = $AgentDet[0]['FirstName']." ".$AgentDet[0]['LastName'];
	if(!empty($AgentDet[0]['Phone']) and !empty($AgentDet[0]['Phone_Ext'])){
		$agent_phone = $AgentDet[0]['Phone']." Ext:".$AgentDet[0]['Phone_Ext'];
	}elseif(!empty($AgentDet[0]['Phone']) and empty($AgentDet[0]['Phone_Ext'])){
		$agent_phone = $AgentDet[0]['Phone'];
	}
}else{
	$AgentID = $_SESSION['Member']['ID'];
	$AgentDet = $objuser->GetAgent("ID = '$AgentID'",array("*"));
	$agent_email = $AgentDet[0]['Email'];
	$agent_name = $AgentDet[0]['FirstName']." ".$AgentDet[0]['LastName'];
	
	if(!empty($AgentDet[0]['Phone']) and !empty($AgentDet[0]['Phone_Ext'])){
		$agent_phone = $AgentDet[0]['Phone']." Ext:".$AgentDet[0]['Phone_Ext'];
	}elseif(!empty($AgentDet[0]['Phone']) and empty($AgentDet[0]['Phone_Ext'])){
		$agent_phone = $AgentDet[0]['Phone'];
	}
}

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

$_SESSION['orderid'] = $inserted_order;

$dir = dirname(__FILE__)."/../";
ob_start();
if($Order_created_without_charge == 1){
	require_once($dir.'/ecommerce/orders/pdf_email/SendOnlyInvoice.php');
}else{
	require_once($dir.'/ecommerce/orders/pdf_email/pdf.php');
}
$pdf_html = ob_get_contents();
ob_end_clean();

require_once($dir.'/ecommerce/orders/pdf_email/dompdf/dompdf_config.inc.php');
		
$dompdf = new DOMPDF(); # Create new instance of dompdf
$dompdf->load_html($pdf_html); # Load the html
$dompdf->render(); # Parse the html, convert to PDF
$pdf_content = $dompdf->output(); # Put contents of pdf into variable for later

# Get the contents of the HTML email into a variable for later
ob_start();
$html_message = "Dear ". $client_name.",";
$html_message .= "<br/><br/>";
$html_message .= "Congratulations! You have taken the first step in <br/>";
$html_message .= "properly placing your business listing on the Internet.<br/><br/>";
$html_message .= "One of our friendly staff members will be contacting<br/>";
$html_message .= "you shortly to go over your business listing details.<br/><br/>";
if($Order_created_without_charge == 1){
	$html_message .= "An invoice showing your order is attached.<br/><br/>";
}else{
	$html_message .= "An invoice showing your order is attached.<br/><br/>";
}

if($_REQUEST['select_pay_process'] == 2){
	$html_message .= "Click the link below to complete your order and begin<br />activating your business presence on the Internet. <br /><br />$agent_name is standing by waiting to fulfill your listing.<br/><br/>"."<a href='https://www.xurlios.com/OrderForm.php?OrderID=".$inserted_order."&MemberID=".$added_member_id."&CPLID=".trim($UniqueCPLID)."' style='background-color: #78CD51; border: 1px solid #72A53B; color: #FFFFFF; cursor: pointer; font-size: 13px; font-style: normal; font-weight: normal; line-height: 20px; margin-top: 5px; padding: 3px 24px; text-align: center; text-decoration: none; vertical-align: middle; white-space: normal;'>Complete Verification Now</a><br/><br/>";
}
$html_message .= "<br/><br/>";
$html_message .= "Best regards,";
$html_message .= "<br/>";
$html_message .= $agent_name;
$html_message .= "<br/>";
$html_message .= "Online Presence Expert";
$html_message .= "<br/>";
$html_message .= $Phone;
$html_message .= "<br/>";
$html_message .= $agent_email;
$html_message .= "<br/><br/>";
$html_message .= "Thank you for your business!";
$html_message .= "<br/><br/>";
$html_message .= "NOTICE OF CONFIDENTIALITY: **This E-mail and any of its attachments may contain proprietary information, which is privileged, confidential, or
subject to copyright. This E-mail is intended solely for the use of the individual or entity to which it is addressed. If you are not the intended recipient of this
E-mail, you are hereby notified that any dissemination, distribution, copying, or action taken in relation to the contents of and attachments to this E-mail is strictly
prohibited and may be unlawful. If you have received this E-mail in error, please notify the sender immediately and permanently delete the original and any copy of this E-mail
and any printout. Thank You.**";
ob_end_clean();


# This is the Mail which will be sent to the Xurli Billing Only as a Separate Email.
# This Mail will include all the Contents above as well as few other details which 
# Must ONLY AND ONLY sent to the Xurli billing.. Neither the Agent nor the Client
# ONLY ONLY XURLI BILLING TEAM.
ob_start();
$html_message_billing = "Dear ". $client_name.",";
$html_message_billing .= "<br/><br/>";

if($_REQUEST['select_pay_process'] == 2){
	$html_message_billing .= "Click the button below to complete the payment noted below.<br/><br/>"."<a href='https://www.xurlios.com/OrderForm.php?OrderID=".$inserted_order."&MemberID=".$added_member_id."&CPLID=".trim($UniqueCPLID)."' style='background-color: #78CD51; border: 1px solid #72A53B; color: #FFFFFF; cursor: pointer; font-size: 13px; font-style: normal; font-weight: normal; line-height: 20px; margin-top: 5px; padding: 3px 24px; text-align: center; text-decoration: none; vertical-align: middle; white-space: normal;'>Complete Verification Now</a><br/><br/>";
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
	
	$mail->addTo($to)->
			addTo($agent_email)->
			addTo($Bcc)->
			# setBcc($mailling_bcc)->
			setFrom('billing@xurli.com')->
			setFromName('Xurli Sales')->
			setSubject($subject);
	$mail->setHtml($html_message)->
			addAttachment($path_pdf.$invoice_no.'.pdf');
#upto here

	# Mail Again to Client with the Text receipt and the attachment
	if(@$Order_created_without_charge == 1){
		# $mail_with_receipt_unpaid = new SendGrid\Email();
		# $mail_with_receipt_unpaid->addTo($to)->
				## Uncomment below line if we want the Summary email to sent to the agent as well
				## addTo($agent_email)->
				# setFrom('billing@xurli.com')->
				# setFromName('Xurli Sales')->
				# setSubject($subject);
		#$mail_with_receipt_unpaid->setHtml($html_message_receipt_unpaid);
				## addAttachment($path_pdf.$invoice_no.'.pdf');
	}else{
		# Mail the receipt after the order payment was fullfilled
		$mail_with_receipt_paid = new SendGrid\Email();
		$mail_with_receipt_paid->addTo($to)->
				# Uncomment below line if we want the Summary email to sent to the agent as well
				# addTo($agent_email)->
				setFrom('billing@xurli.com')->
				setFromName('Xurli Sales')->
				setSubject($subject);
		$mail_with_receipt_paid->setHtml($html_message_receipt_paid);
			# addAttachment($path_pdf.$invoice_no.'.pdf');
	}

	# Mail Only To Billing @ Xurli Team
	$mail_only_billing = new SendGrid\Email();
	$mail_only_billing->addTo('billing@xurli.com')->
			setFrom('billing@xurli.com')->
			setFromName('Xurli Sales')->
			setSubject($subject);
	$mail_only_billing->setHtml($html_message_billing);
	
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