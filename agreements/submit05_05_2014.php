<?php
include "../lib/outer_include.php";
?>
<html>
<link rel="stylesheet" href="../css/styles.css" />
<link rel="stylesheet" href="http://xurli.com/themes/Responsive/css/typography.css?m=1395352376">
<head>
<style>
.success {
	display: block;
	background: green;
	color: #fff;
	padding: 10px;
	border-radius: 5px;	
	border: 1px solid #0C5A0D;
}
.message_success{
	margin: auto;
	width: auto;
}
body { padding: 20px;}
body.embed {
	background:#fff;
	padding: 0px;
}
</style>

</head>
<body <?php if($_POST['embedded']=="1") {echo "class='embed'";} ?>>
<?php

$type = $_POST['type'];
$tot = $_POST['TotalPrice'];
if($tot == "") {
	echo "Sorry, this order is no longer valid";
} else {
    $html_message = '
    <div style="padding:3%;"><div class="header" id="header" style="background:#eee; padding: 10px;">
		<table width="100%"  border="0" cellspacing="0" cellpadding="2" >
    	<tr  class="tables">
        	<td width="33%"><img src="' .  $_POST['invoiceImg'] .'" height="70" /></td>
            <td width="33%">&nbsp;</td>
            <td align="right" width="33%"><h2>Fulfillment Authorization</h2></td>
        </tr>
	<tr>
    <td colspan="2"><br /></td>
    </tr>
  
    <tr>
        <td>
            <span class="table_heading">' . $_POST['companyName'] . '</span><br />' .
            $_POST['addy1'] .' <br /> ' .
            $_POST['addy2'].' <br /> ' .
            $_POST['city'].", ".$_POST['state']." ".$_POST['zip'].' <br /> ' .
            $_POST['phone'].' <br /> 
            United States
        </td>
        <td>&nbsp;</td>
		<td align="right">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td width="50%">Order Date</td>
					<td align="right"><b>' . $_POST['made'] . '</b></td>
				</tr>
				<tr>
					<td>Customer ID</td>
					<td align="right"><b>'. $_POST['customer'] . '</b></td>
				</tr>
			</table>
		</td>
    </tr>
</table>

</div>';

	$to = $_POST['email'];
	if($type == "GYB") {
		$subject = $_POST['companyName'] . " - Signed Fulfillment Authorization";
		$html_message .= "<p>Congratulations on completing your order (" . $_POST['companyName'] . ") with " . $_POST['comp'] . "!.  You are on your way to securing your place as a verified local business with the major search engines.</p>
<p>In order to fulfill your order and verify/reverify your online listings with Google, Yahoo, and/or Bing <em>we need your permission.</em>  <strong>We are not Google, Yahoo, Bing, or any other search engine</strong>, but rather we are a 3rd party who works directly with the search engines to claim, verify, and build out your business's listing to the specifications provided by the search engines.  The listings we are creating for you are YOUR listings. They belong to you and are yours to do with as you please for the life of your business.  In order for us to act as your agent and claim, verify, and manage your listing we need your authorization. Note this authorization gives us permission to create and/or edit your listing as well as speak to the search engines (Google, Yahoo, and/or Bing) on your behalf. <u>It does not change the fact that YOU are the owner of your listing.</u></p>
<p>Please sign below indicating that you grant us permission to create or edit your listing as needed and to speak with the search engines on your behalf.</p>
<p>By signing you also acknowledge that you authorize us to charge the " . $_POST['CCType'] . " card ending in <strong>" . $_POST['CCnum'] . "</strong> on <strong>" . $_POST['Created'] . "</strong> for <strong>$". $_POST['TotalPrice'] . "</strong>. This is a one-time charge that covers all work related to creating/editing/managing your online listing. By signing you also acknowledge that you are covered by a 100% satisfaction guarantee and agree that in the event you are dissatisfied for any reason with your purchase you will call our customer service department at " . $_POST['phone'] . " to discuss a resolution.</p>
<p>By signing you agree that you will not initiate any dispute through your credit card company or bank without first contacting our support department to resolve any issues. " . $_POST['comp'] . " is 100% committed to your satisfaction as our customer and will work with you to resolve any issues.</p>
<p><strong>Signed,</strong></p>
<p>" . $_POST['signature'] . "</p>
<p>" . $_POST['email'] . "</p>
<p>IP Address (recorded): <strong>" . $_POST['IP'] ."</strong></p>
<p><strong>Transaction Date: " . $_POST['Created'] . "</strong>";
	} else {
		$subject = $_POST['companyName'] . " - Signed Website Authorization";
		$html_message .= "<p>Congratulations on completing your order (" . $_POST['companyName'] . ") with " . $_POST['companyName'] . "!.  You are on your way to being the proud owner of a website that not only looks great but presents your business properly on every web-browsing device in the market including smartphones, tablets, laptop pcs, desktop pcs, etc.<br>
  <br>
  Please sign below indicating acceptance of our Terms of Service.<br>
  <strong><br>
  Terms of Service</strong><br>
  <br>
  You will be given the opportunity to sign off and accept your website upon completion.<br>
  <ol>
  <li>ERRORS.  " . $_POST['comp'] .  "('" . $_POST['compShort'] ."') takes every care but accepts no responsibility for spelling mistakes or other errors when the artwork proof has been signed off.<br>
  </li>
  <li>CORRECTIONS. Artwork costs include two revisions only, so please check all artwork thoroughly. Any additional corrections or design changes will incur a design fee of $100.00 per hour, at no less than $200 total per revision.<br>
  </li>
  <li>COLOR. Due to the nature of monitors, screen colors are NOT an indication of the final viewed product. If Pantone colors or CMYK values have been specified, then these colors will be as close as possible to the final viewed product. " . $_POST['compShort'] ." takes no responsibility for color variations in the final viewed or printed product when Pantone or CMYK values have been specified, used and approved in the design.<br>
  </li>
  <li>THIRD PARTY PRINT PRODUCTION. " . $_POST['compShort'] ." takes no responsibility for the final output, supply or completion time for the goods or services provided by a supplier arranged by yourself; the client. On approval of this final proof, " . $_POST['compShort'] ." is not liable and will take no responsibility for the goods or services provided to the client and/or print servicer. This includes the use of various print methods used (specifically color variations used for full color/digital output) by a third party to output the goods, " . $_POST['compShort'] ." is in no circumstance liable to any loss or expense resulting in an undesired outcome.<br>
  </li>
  <li>TRANSACTION IS FINAL. I understand I have 3 complete days from the purchase date to receive a full and prompt refund of the entire purchase amount if I request. Following the 3 days, I understand refunds will be considered on a case-by-case basis.  I understand no refund can be guaranteed for websites and other digital marketing services outside of 3 days as resources are committed well in advance of completion of any project.  I understand that by purchasing services from " . $_POST['compShort'] ." I am purchasing business-related services.  Any such purchase carries an implied risk, and I willingly accept that risk.<br>
  </li>
  </ol>";
  $html_message .= "<strong>Acceptance of Terms</strong></p>
  <p>By signing you also acknowledge that you authorize us to charge the " . $_POST['CCType'] . " card ending in <strong>" . $_POST['CCnum'] . "</strong> on <strong>" . $_POST['Created'] . "</strong> for <strong>$". $_POST['TotalPrice'] . "</strong>. This is a one-time charge that covers all work related to creating/editing/managing your online listing. By signing you also acknowledge that you are covered by a 100% satisfaction guarantee and agree that in the event you are dissatisfied for any reason with your purchase you will call our customer service department at 800-873-4373 to discuss a resolution.<br>
  <br>
  By signing you agree that you will not initiate any dispute through your credit card company or bank without first contacting our support department to resolve any issues.  " . $_POST['comp'] . " is 100% committed to your satisfaction as our customer and will work with you to resolve any issues.<br>
  <br>
  <strong>Signed,</strong><br>";
  $html_message .= "<p>" . $_POST['signature'] . "</p>";
  $html_message .= "<p>" . $_POST['email'] . "</p>
<p>IP Address (recorded): <strong>" . $_POST['IP'] ."</strong></p>
<p><strong>Transaction Date: " . $_POST['Created'] . "</strong></div>";
  	
	}

	$path_pdf = "";
	$invoice_no = $_POST['clientID']."-".$_POST['orderID'];
	
	$dir = dirname(__FILE__)."/../";

	require_once($dir.'/ecommerce/orders/pdf_email/dompdf/dompdf_config.inc.php');
	
	$dompdf = new DOMPDF(); # Create new instance of dompdf
	$dompdf->load_html($html_message); # Load the html
	$dompdf->render(); # Parse the html, convert to PDF
	$pdf_content = $dompdf->output(); # Put contents of pdf into variable for later
	
	$path_pdf = $_SERVER['DOCUMENT_ROOT']."/agreements/attachments/";
	$pdf_file = $dompdf->output();
	file_put_contents($path_pdf.$invoice_no.'.pdf', $pdf_file);
	$pdfAttachment = $path_pdf.$invoice_no.'.pdf';	
	
	# Lets add the sendgrid related files here
	require '../clients/sendgrid-php/sendgrid-php/vendor/autoload.php';
	require '../clients/sendgrid-php/sendgrid-php/lib/SendGrid.php';
	
	$sendgrid = new SendGrid(SENDGRID_USERNAME, SENDGRID_PASSWORD);
	$mail = new SendGrid\Email();
	
	$cc = trim($_POST['company']);
	$mail->addTo($to)->
			addTo($cc)->
			setFrom(trim($_POST['company']))->
			setFromName('Xurli Sales')->
			setSubject($subject);
	$mail->setHtml($html_message)->
	addAttachment($pdfAttachment);
    # Send the email, and show user message
	if ($sendgrid->send($mail)){ ?>

		<div class="message_success">Authorization Successful! Thank You.</div>
		<?php
		# Update orderItem table
		$objorder = new Orders();
		$updateOrderDetail = $objorder->UpdateOrderDetail(" OrderID = '" . $_POST['orderID'] . "' ",array(
			"Verified"		=> 1,
		));
	}
}
/*'Dancing Script' !important*/
?>