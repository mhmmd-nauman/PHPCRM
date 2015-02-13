<?php
include "../../lib/include.php";
session_start();
$objorder = new Orders();
$objcompany = new Company();
$objproducts = new Products();
$orderid = $_REQUEST['OrderID'];
$objClient = new Clients();
$objusers = new Users();
$order_invoice_array = $objorder->GetAllOrderDetailWithProduct("OrderID=$orderid ",array("*"));
$user_Company_array = $objClient->FetchAgentCompanyDetailsforInvoice($order_invoice_array[0]['UserID']);
$verification = $objorder->returnproductdetails(" OrderID = '" . $orderid . "' ",array("*"));


	extract($_REQUEST);
	$to = $order_invoice_array[0]['Email'];
	$client_name = $order_invoice_array[0]['FirstName']." ".$order_invoice_array[0]['Surname'];
	if($order_invoice_array[0]['Status']=="Paid"){
		$start = "Paid";	
	} else {
		$start = "Open";	
	}
	$subject = $start . " invoice for ".$order_invoice_array[0]['FirstName']." ".$order_invoice_array[0]['Surname'];
    if($user_Company_array[0]['ID']=="3") {
		$from = "billing@xerly.com";
		$fromName = "Xerly.com";
	} else {
		$from = "billing@xurli.com";
		$fromName = "Xurli.com";
	}
    $Agent_ID = $order_invoice_array[0]['UserID'];
    $AgentDataArray = $objusers->GetAllUsers(USERS.".ID = $Agent_ID",array(USERS.".*"));
	
	if(!empty($AgentDataArray[0]['Phone']) and !empty($AgentDataArray[0]['Phone_Ext'])){
		$Phone = $AgentDataArray[0]['Phone']."Ext: ".$AgentDataArray[0]['Phone_Ext'];
	}elseif(!empty($AgentDataArray[0]['AlternatePhone']) and !empty($AgentDataArray[0]['Phone_Ext'])){
		$Phone = $AgentDataArray[0]['AlternatePhone']."Ext: ".$AgentDataArray[0]['Phone_Ext'];
	}elseif(!empty($AgentDataArray[0]['Phone']) and empty($AgentDataArray[0]['Phone_Ext'])){
		$Phone = $AgentDataArray[0]['Phone'];
	}else{
		$Phone = $AgentDataArray[0]['AlternatePhone'];
	}

	$agent_email = $AgentDataArray[0]["Email"];
    $agent_name = $AgentDataArray[0]["FirstName"]." ".$AgentDataArray[0]["LastName"];
	$agent_first = $AgentDataArray[0]["FirstName"];
	# Start Building preview from here
	
	$html_message .= "Dear ". $client_name.",";
	$html_message .= "<br/><br/>";
	
	if($start=="Open"){
		$html_message .="Congratulations!  The link below allows you to take the first step in <br />";
		$html_message .="properly placing your business on the Internet.<br/><br />";
		$html_message .="As we discussed with you there is currently ";
		$html_message .="an issue that is potentially limiting <br />the exposure your business is receiving online.";
	} else {
		$html_message .= "Congratulations! You have taken the first step in <br/>";
		$html_message .= "properly placing your business listing on the Internet.<br/><br/>";
		$html_message .= "One of our listing fulfillment experts will be contacting<br/>";
		$html_message .= "you shortly to go over your business listing details.<br/><br/>";
	}
	
	
	
	
	/* adding section to show link to consent form */
	
	//echo "verify status: " . $verification[0]['Verified'] . "<hr />";
	if($verification[0]['Verified']=="0") {
		
		$productList = array();
		foreach((array) $order_invoice_array as $row) {
			$productList[] = $row['ProductID'];
		}
		$isGYB = false;
		$gybArray = array("20", "30", "39", "40", "41", "42", "44", "45", "54", "55");
		$isWebsite = false;
		$webArray = array("46", "47", "48", "49", "50");
		foreach($productList as $name) {
			//echo $name;
			if(in_array($name, $gybArray)) {
				$isGYB = true;	
			} 
			if(in_array($name, $webArray)) {
				$isWebsite = true;	
			} 
		}
		if($isGYB==true||$isWebsite==true) {
			//echo "and<br />";
			if(!empty($order_invoice_array[0]['CPLID']) ) {
				$CPLID = trim($order_invoice_array[0]['CPLID']);
			} else {
				$CPLID = "we234ad";
			}
			$params = "?order=".$OrderID."&client=".$order_invoice_array[0]['MemberID']."&CPLID=".$CPLID;
			if($isGYB==true) {
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
			if($order_invoice_array[0]['Status']=="Paid"){
			$html_message .= "Building and/or verifying your listing(s) requires written <br />
			authorization for us to proceed on your behalf.<br /><br />
			Please click the button below authorizing us to
			begin work on your listing(s):<br /><br />";
			
			$html_message .= "<a href='" . $path . $params . "' target='_blank' style='background: green; display: inline-block; color: #fff; padding: 10px; text-decoration: none; margin: 5px 0;'>Authorize order</a><br />"; 
			}
		} 
	}
	/* end consent form button section */
	
	
	if($order_invoice_array[0]['Status'] == "Unpaid"){
		$html_message .= "Click the link below to complete your order and repair the issue. <br /><br />"."<a class='showdetails2' style='text-decoration:none; background: green; display: inline-block; color: #fff; padding: 10px; text-decoration: none; margin: 5px 0;' target='_blank' href='https://xurlios.com/OrderForm.php?OrderID=".$OrderID."&MemberID=".$order_invoice_array[0]['MemberID']."&CPLID=".trim($order_invoice_array[0]['CPLID'])."'>Repair Listing Now</a><br/>";
	}
	
	$html_message .= "<br/>";
	if(!empty($order_invoice_array[0]['Status']) and $order_invoice_array[0]['Status'] == "Unpaid"){
		$html_message .= "An invoice is attached for your records.&nbsp;&nbsp;Once you process the 
		open Order a new invoice will be sent to you showing the confirmed Order.<br/><br/>";
	}elseif(!empty($order_invoice_array[0]['Status']) and $order_invoice_array[0]['Status'] == "Paid"){
		$html_message .= "An invoice showing your order is attached.<br/><br />";
	}
	
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
	$html_message .= "<br /><br />Thank you for your business!";
	$html_message .= "<br/><br/>";
	//$html_message .= "CONFIDENTIALITY NOTICE: The Materials contained in this electronic mail transmission (including all attachments) are private and confidential and are the property of the sender. No information contained herein should be considered legal or tax advice. If you need legal or tax advice you should consult a licensed attorney or tax adviser. The information contained herein is privileged and is intended only for the use of the named addresses(s). 				You are hereby notified that any unauthorized dissemination, distribution, copying, disclosure, or the taking of any action in reliance of the contents of this material is strictly prohibited. If you have received this electronic 				mail transmission in error, please immediately notify the sender";
if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "CreatePreview"){
	
	echo "<div style='margin:0 auto; width:90%; padding:20px; background-color:#fff;'>";
	echo "<br/><em>(From: <strong>". $from . "</strong> |  To:  <strong>" . $to . "</strong>)</em>";
	echo '<a href="#" style="float:right;" onclick="previewmailhide();">Close</a><hr /><br />';		
	echo $html_message;
	echo "</div>";
	die();
}

# Below if condition will run when the Send Invoice button is clicked.
if (($_REQUEST['pstback'] == 1)) {
    $to = $order_invoice_array[0]['Email'];
    
    $client_name = $order_invoice_array[0]['FirstName']." ".$order_invoice_array[0]['Surname'];

	if($order_invoice_array[0]['Status']=="Paid"){
		$start = "Paid";	
	} else {
		$start = "Open";	
	}
	$subject = $start . " invoice for ".$order_invoice_array[0]['FirstName']." ".$order_invoice_array[0]['Surname'];

	if($user_Company_array[0]['ID']=="3") {
		$from = "billing@xerly.com";
		$fromName = "Xerly.com";
	} else {
		$from = "billing@xurli.com";
		$fromName = "Xurli.com";
	}
    
    
   
    $Agent_ID = $order_invoice_array[0]['UserID'];
    $AgentDataArray = $objusers->GetAllUsers(USERS.".ID = $Agent_ID",array(USERS.".*"));
    $agent_email = $AgentDataArray[0]["Email"];
  
    $agent_name = $AgentDataArray[0]["FirstName"]." ".$AgentDataArray[0]["LastName"];
	
	if(!empty($AgentDataArray[0]['Phone']) and !empty($AgentDataArray[0]['Phone_Ext'])){
		$Phone = $AgentDataArray[0]['Phone']."Ext: ".$AgentDataArray[0]['Phone_Ext'];
	}elseif(!empty($AgentDataArray[0]['AlternatePhone']) and !empty($AgentDataArray[0]['Phone_Ext'])){
		$Phone = $AgentDataArray[0]['AlternatePhone']."Ext: ".$AgentDataArray[0]['Phone_Ext'];
	}elseif(!empty($AgentDataArray[0]['Phone']) and empty($AgentDataArray[0]['Phone_Ext'])){
		$Phone = $AgentDataArray[0]['Phone'];
	}else{
		$Phone = $AgentDataArray[0]['AlternatePhone'];
	}
	
	$dir = dirname(__FILE__);
    # Get the contents of the pdf into a variable for later
    $_SESSION['orderid'] = $orderid;
    ob_start();
	if($_REQUEST['paymentnotmadeyet'] == 1){
		require_once($dir.'/pdf_email/SendOnlyInvoice.php');
	}else{
    	require_once($dir.'/pdf_email/pdf.php');
	}
    $pdf_html = ob_get_contents();		
    ob_end_clean();
    unset($_SESSION['orderid']);
	
    # Load the dompdf files
    //require_once($dir.'/pdf_email/dompdf/dompdf_config.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/chargebacks/dompdf/dompdf_config.inc.php');

    $dompdf = new DOMPDF();
    $dompdf->load_html($pdf_html);
	$dompdf->set_paper("letter", "portrait");
    $dompdf->render();
    $pdf_content = $dompdf->output(); 
	
	ob_start();
	$html_message;
	ob_end_clean();
	
    $invoice_no = $order_invoice_array[0]['UserID']."-".$order_invoice_array[0]['MemberID']."-".$order_invoice_array[0]['OrderID'];

    #added on 19th march 2014
	require 'sendgrid-php/sendgrid-php/vendor/autoload.php';
	require 'sendgrid-php/sendgrid-php/lib/SendGrid.php';
	
	$sendgrid = new SendGrid(SENDGRID_USERNAME, SENDGRID_PASSWORD);
	$mail = new SendGrid\Email();
	$pdf_file = $dompdf->output();
	file_put_contents('attachments/'.$invoice_no.'.pdf', $pdf_file);
	$mailling_list = array($to);
	$mail->addTo($to)->
			addTo($agent_email)->
			addTo($from)->
			setFrom($from)->//setFrom('admin@xurli.com')->
			setFromName($fromName)->
			setSubject($subject);
	$mail->setHtml($html_message)->
			addAttachment('attachments/'.$invoice_no.'.pdf');//$filePath."/uploads/".$_FILES['uploadFile']['name']);	
	#upto here	
    # Send the email, and show user message
	if ($sendgrid->send($mail)){?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td id="message_success">Invoice Sent Successfully!</td>

			</tr>
		</table>
	<?php
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
.heading {
	background-color: #EEEEEE;
	font-size: 14px;
	font-weight: bold;
	padding: 4px;
}
.notapplicable {
	background-color:#CCCCCC;
}
.notapplicable:hover {
	background-color:#CCCCCC;
}
.total {
	background:#FFFF00;
}
.rows {
	height:25px;
}
.rows_notes {
	word-wrap:break-word;
}
.order_details {
	border:1px solid #CCCCCC;
	background:#F1EFEF;
}
.order_details tr td {
	border-bottom:1px solid #CCCCCC;
	border-right:1px solid #CCCCCC;
	text-align:center;
	padding:0;
	margin:0;
}
.order_details tr td:first-child {
	text-align:left;
	padding: 0 0 0 5px;
}
.order_details tr td:hover {
	background-color:#DFE8F2;
}
.table_heading {
	font-weight:bold;
	font-size:15px;
}
.hover {
	background-color:#DFE8F2;
}
.invoice {
	width:99%;
	text-align:right;
	color:#8394c9;
	font-size:20px;
	font-weight:bold;
	margin-top: 13px;
}
table tr td {
	font-size: 12px;
}
.border_replace {
	border: none;
	padding-left: 13px;
}
.previewmail{
	background: none repeat scroll 0 0 #EFEFEF;
    height: 82vh;
    left: 5%;
    overflow: auto;
    position: absolute;
    top: 8%;
    width: 90vw;
    z-index: 1001;
	display:none;
	border:1px solid royalblue;
}
.showdetails{
	background-color: #78CD51;
    border: 1px solid #72A53B;
    color: #FFFFFF;
    cursor: pointer;
    font-family: "OpenSansSemiBold",Helvetica,Arial,sans-serif;
    font-size: 13px;
    font-style: normal;
    font-weight: normal;
    line-height: 20px;
    margin-top: 5px;
    padding: 3px 24px;
    text-align: center;
    text-decoration: none;
    text-rendering: optimizelegibility;
    text-transform: none;
    transition: all 0.15s ease 0s;
    vertical-align: middle;
    white-space: normal;
}
.showdetails:hover{
	background-color: #6BB24A;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Order Details</title>
</head>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/styles.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<body>
<div class="subcontainer">

  <table width='100%' border='0' cellspacing='0' cellpadding='5'>
	<?php 
		
		
		if($user_Company_array[0]['ID'] == '2'){
    ?>
    
    <tr>
      <td width="50%" class="border_replace"><img src="<?php echo SITE_ADDRESS;?><?php echo $user_Company_array[0]['InvoiceImage'];?>" height="70" /> </td>
      <td class="invoice">INVOICE</td>
    </tr>
    <tr>
      <td  width="50%" valign="top"class="border_replace"><span class='table_heading'><?php echo $user_Company_array[0]['CompanyName'];?></span><br /><?php echo $user_Company_array[0]['CompanyName'];?><br />
        <?php echo $user_Company_array[0]['Address1']." ".$user_Company_array[0]['Address2'];?><br /> <?php echo $user_Company_array[0]['City'].", ".$user_Company_array[0]['State']." ".$user_Company_array[0]['Zip'];?><br/>
        <?php echo $user_Company_array[0]['Phone'];?> </td>
      <td width="50%" valign="top"class="border_replace" align="right">
            <table width='250' border='0' cellspacing='0' cellpadding='0'align="right">
              <tr>
                <td align="left">Invoice Date &nbsp; </td>
                <td align="right"><b><?php echo date("<b>M d</b>, Y",strtotime($order_invoice_array[0]['Created'])); ?></b></td>
              </tr>
              <tr>
                <td align="left">Invoice#</td>
                <td align="right"><b><?php echo $order_invoice_array[0]['UserID']."-".$order_invoice_array[0]['MemberID']."-".$order_invoice_array[0]['OrderID']; ?></b></td>
              </tr>
              <tr>
                <td align="left">Customer ID</td>
                <td align="right"><b><?php echo $order_invoice_array[0]['MemberID']; ?></b></td>
              </tr>
            </table>
        </td>
    </tr>
    <?php }else{?>
    <tr>
      <td class="border_replace"><img src="<?php echo SITE_ADDRESS;?><?php echo $user_Company_array[0]['InvoiceImage'];?>" height="70" /> </td>
      <td class="invoice">INVOICE</td>
    </tr>
    <tr>
      <td width="50%" valign="top"class="border_replace">
      <span class='table_heading'><?php echo $user_Company_array[0]['CompanyName'];?></span><br />
        <?php echo $user_Company_array[0]['Address1']." ".$user_Company_array[0]['Address2'];?><br /> <?php echo $user_Company_array[0]['City'].", ".$user_Company_array[0]['State']." ".$user_Company_array[0]['Zip'];?><br/>
        <?php echo $user_Company_array[0]['Phone'];?> </td>
      <td width="50%" valign="top"class="border_replace" align="right">
            <table width='250' border='0' cellspacing='0' cellpadding='0'align="right">
              <tr>
                <td align="left">Invoice Date &nbsp; </td>
                <td align="right"><b><?php echo date("<b>M d</b>, Y",strtotime($order_invoice_array[0]['Created'])); ?></b></td>
              </tr>
              <tr>
                <td align="left">Invoice#</td>
                <td align="right"><b><?php echo $order_invoice_array[0]['UserID']."-".$order_invoice_array[0]['MemberID']."-".$order_invoice_array[0]['OrderID']; ?></b></td>
              </tr>
              <tr>
                <td align="left">Customer ID</td>
                <td align="right"><b><?php echo $order_invoice_array[0]['MemberID']; ?></b></td>
              </tr>
            </table>
        </td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <table width='100%' border='0' cellspacing='0' cellpadding='2'>
    <tr>
      <td width="65%" class="border_replace"><strong>Ship To:</strong><br/>
        <?php echo $order_invoice_array[0]['CompanyName']; ?><br />
        <?php echo $order_invoice_array[0]['FirstName']." ".$order_invoice_array[0]['Surname'];?><br/>
        <?php echo $order_invoice_array[0]['StreetAddress1']." ".$order_invoice_array[0]['StreetAddress2'];?><br/>
        <?php echo $order_invoice_array[0]['BillingCity'].", ".$order_invoice_array[0]['BillingState']." ".$order_invoice_array[0]['BillingPostalCode'];?><br/>
        <?php echo $order_invoice_array[0]['BillingCountry'];?><br/>
      </td>
      <td class="border_replace" ><strong>Bill To:</strong><br/>
        <?php echo $order_invoice_array[0]['CompanyName']; ?><br />
        <?php echo $order_invoice_array[0]['FirstName']." ".$order_invoice_array[0]['Surname'];?><br/>
        <?php echo $order_invoice_array[0]['StreetAddress1']." ".$order_invoice_array[0]['StreetAddress2'];?><br/>
        <?php echo $order_invoice_array[0]['BillingCity'].", ".$order_invoice_array[0]['BillingState']." ".$order_invoice_array[0]['BillingPostalCode'];?><br/>
        <?php echo $order_invoice_array[0]['BillingCountry'];?><br/></td>
    </tr>
  </table>
  <table width="100%" cellpadding="5" cellspacing="0">
  	<tr style="height:25px;">
    	<td>&nbsp;</td>
    </tr>
  </table>
  <table width="100%" cellpadding="5" cellspacing="0">
    <tr style=" background-color:#E3E3E3;" bgcolor="#E3E3E3">
      <td style="border: none;font-size: 13px;color: #000; font-weight: bold;font-family:Sans-Serif; background:#E3E3E3; ">Qty</td>
      <td style="border: none;font-size: 13px; color: #000; font-weight: bold;font-family:Sans-Serif; background:#E3E3E3; ">Product</td>
      <td style="border: none;font-size: 13px;color: #000; font-weight: bold;font-family:Sans-Serif;  background:#E3E3E3; ">Description</td>
      <td style="border: none; font-size: 13px;color: #000; font-weight: bold;font-family:Sans-Serif; background:#E3E3E3; ">Unit Price</td>
      <td style="border: none; font-size: 13px;color: #000; font-weight: bold;font-family:Sans-Serif; background:#E3E3E3; " align="right">Total Price</td>
    </tr>
  
    <?php 
	foreach ((array)$order_invoice_array as $Invoice_data){
	?>
        <tr>
            <td><?php echo $Invoice_data['Quantity']; ?></td>
            <td><?php echo $Invoice_data['ProductName']; ?></td>
            <td><?php echo $Invoice_data['Description']; ?></td>
            <td>$<?php echo number_format($Invoice_data['ProductPrice'], 2); ?></td>
            <td align="right">$<?php echo number_format($Invoice_data['TotalPrice'], 2); ?></td>
        </tr>
        <?php 
		$totalPrice = "";
        $totalAmtPaid = $Invoice_data['TotalPrice'];
        $totalPrice = $totalPrice + $totalAmtPaid;
        $_SESSION['totalamtpaid'] = $totalPrice;
    }
    ?>
	<tr>
    <td colspan="5"><br /></td>
    </tr>
    <tr style="background-color:#E3E3E3;">
      <td colspan="5" style="border: none; font-size: 13px; color: #000; font-weight: bold;" >Payments</td>
    </tr>

  	<?php
    if(empty($order_invoice_array[0]['CredetCardNumber']) and $order_invoice_array[0]['Status'] == "Unpaid"){
	?>
        <tr>
          <td>Amount Due</td>
          <td colspan="4">&nbsp;</td>
          <td align="right">$<?php echo number_format($_SESSION['totalamtpaid'], 2); ?></td>
        </tr>
    <?php
	}elseif(!empty($order_invoice_array[0]['CredetCardNumber']) and $order_invoice_array[0]['Status'] == "Paid"){
	?>
    <tr>
      <td><b><?php echo date("<b>M d</b>, Y",strtotime($order_invoice_array[0]['Created'])); ?></b></td>
      <td>Credit card approved</td>
      <td><?php echo $order_invoice_array[0]['CredetCardType'];?></td>
      <td>
	  <?php 
        $unsecure_credit_card = $objClient->decrypt($order_invoice_array[0]['CredetCardNumber']);
        $cardReplase = "************";
        $encriptcard = substr_replace($unsecure_credit_card,$cardReplase,0,12);
        echo $encriptcard;
	  ?>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  <?php
	}elseif(empty($order_invoice_array[0]['CredetCardNumber']) and $order_invoice_array[0]['Status'] == "Paid" and !empty($order_invoice_array[0]['PaidThrough'])){
	?>
	<tr>
      <td><b><?php echo date("<b>M d</b>, Y",strtotime($order_invoice_array[0]['Created'])); ?></b></td>
      <td>Paid Through</td>
      <td>&nbsp;</td>
      <td>
	  <?php 
        if($order_invoice_array[0]['PaidThrough'] == "cash"){
			echo "Cash";
		}elseif($order_invoice_array[0]['PaidThrough'] == "check"){
			echo "Check";
		}elseif($order_invoice_array[0]['PaidThrough'] == "echeck"){
			echo "Electronic Check";
		}elseif($order_invoice_array[0]['PaidThrough'] == "cc"){
			echo "Credit Card";
		}elseif($order_invoice_array[0]['PaidThrough'] == "other"){
			echo "Other Modes of Payment";
		}elseif($order_invoice_array[0]['PaidThrough'] == "edebitDirect"){
			echo "Electronic Debit Card";
		}
	  ?>
      </td>
      <td><?php echo $order_invoice_array[0]['NotesForpaidThrough']; ?></td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php
	}
	?>
 
    <tr style=" background-color:#E3E3E3;">
      <td  style="border: none; font-size: 13px;color: #000; font-weight: bold;">Total Amount Paid</td>
      <td style="border: none;  background-color:#E3E3E3;">&nbsp;</td>
      <td style="border: none; background-color:#E3E3E3; ">&nbsp;</td>
      <td style="border: none; background-color:#E3E3E3; ">&nbsp;</td>
      <td style="border: none;font-size: 13px;color: #000; background-color:#E3E3E3; font-weight: bold;" align="right">$<?php if($order_invoice_array[0]['Status'] == "Unpaid") { echo "0.00"; } else { echo number_format($totalAmtPaid, 2); } ?></td>
    </tr>

  
  <tr>
  <td colspan="5"><br /></td>
  </tr>
  <tr>
  <td style=" font-size: 13px; font-weight: bold; color: #000; background-color:#E3E3E3; "">Terms
  </td>
  <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
  <td>
  Payable on Receipt
  </td>
  <td colspan="4">&nbsp;</td>
  </tr>
  </table>
  <p></p>
  <div style="float:right; margin-top:25px; font-size:15px; cursor:pointer;"><a onClick="print_me();"><img src="images/print_icon.gif" title="Print this order page." /></a></div>
  <form action="?OrderID=<?php echo $_REQUEST['OrderID'];?>" method="post" target="_self">
  <div align="center" style="bottom: 23px; position: fixed; margin-top: 13px; width: 100%;">
    <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onClick="close_popup()" type="button" value="Close">
    &nbsp;
    <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit" value="Send Invoice">
    &nbsp;
    <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" onClick="mailpreview('<?php echo $_REQUEST['OrderID']; ?>')" value="Preview Mail">
    <?php
    if(empty($order_invoice_array[0]['CredetCardNumber']) and $order_invoice_array[0]['Status'] == "Unpaid"){
		echo "<input type='hidden' name='paymentnotmadeyet' value='1' />";
	}
	?>
    <a href="SendOverdueInvoices.php?OrderID=<?php echo $_REQUEST['OrderID']; ?>" id="send_email_overdue" title="Overdue Invoice"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" value="Overdue Invoice"></a>
    <input type="hidden" name="pstback" value="1" />
  </div>
</form>
</div>

<div class="previewmail">
	<!--<a href="#" style="float:right;" onclick="previewmailhide();">Close</a>-->
</div>
<script type="text/javascript">
function print_me(){
	window.print();
}

function previewmailhide(){
	$(".previewmail").hide();
}

function mailpreview(OrderID){
	$(".previewmail").html(" ");
	$(".previewmail").html('<div style="text-align:center; padding-top:20%;">Loading...</div>').show();
	if(OrderID != ""){
		$.ajax({
			url : 'OrdersInvoice.php?Task=CreatePreview&OrderID='+OrderID,
		}).done(function(data){
			if(data != ""){
				$(".previewmail").html(" ");
				$(".previewmail").html(data).show();
			}else{
				$(".previewmail").html('<div class="message_error" style="width:97%;">There was some error while creating preview.<br/>Please try again.</div>').show();
			}
		});
	}
}

$(document).ready(function(){
	$('.order_details td').css('background-color', '#fff');
	$('.order_details td:empty').css('background-color', '#F1EFEF');
	$('.total').css('background-color', '#FFFF00');
	
	$('#send_email_overdue').click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"750X450");
	});
});

function confirmation(){
  var didConfirm = confirm("Are you sure you want to delete the order?");
  if(didConfirm == true){
  	return true;
  }
  return false;
}

function close_popup(){
	$(window.top.document).find('.ui-widget-overlay, .ui-dialog').remove();
}

 $("#message_success").fadeOut(3000);

</script>
</body>
</html>
