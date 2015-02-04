<?php
include "../lib/outer_include.php";
$dir = $_SERVER['DOCUMENT_ROOT'];
require_once($dir.'/chargebacks/dompdf/dompdf_config.inc.php');
?>
<html>
<link rel="stylesheet" href="../css/styles.css" />
<link rel="stylesheet" href="http://xurli.com/themes/Responsive/css/typography.css?m=1395352376">
<link href="http://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff" rel="stylesheet" type="text/css" >
<title>Agreement</title>
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
body { padding: 20px; font-size: 12px;}
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
	$html_message .= '<html>
					  <head>
						<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff">
						<style type="text/css">
							@import url(http://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff);
							@font-face {
							  font-family: "Herr Von Muellerhoff";
							  font-style: normal;
							  font-weight: 400;
							  src: local("Herr Von Muellerhoff Regular"), local("HerrVonMuellerhoff-Regular"), url(http://themes.googleusercontent.com/static/fonts/herrvonmuellerhoff/v4/mmy24EUmk4tjm4gAEjUd7LdqIO0vRN7OFh44Vbf9XGo.woff) format("woff");

						  	.signfont{
								font-family: \'Herr Von Muellerhoff\', cursive !important;
								text-align:center;
								margin-left: 28px;
								color:#346599;
								font-size:35px;
							}
							.wrapper {font-size: 14px}
							';
							if($type == "GYB") {
								
							} else {
								$html_message .= '.wrapper {font-size: 12px}';
							}
						$html_message .='</style>
					  </head>';
	$html_message .= '<body>';
    $html_message .= '
    <div style="padding:3%;" class="wrapper"><div class="header" id="header" style="background:#eee; padding: 10px;">
		<table width="100%"  border="0" cellspacing="0" cellpadding="2" >
    	<tr  class="tables">
        	<td width="33%"><img src="' .$_SERVER['DOCUMENT_ROOT']."/".$_POST['invoiceImg'].'" /></td>
            <td width="33%">&nbsp;</td>
            <td align="right" width="33%"><h2>Fulfillment Authorization</h2></td>
        </tr>
	<tr>
    <td colspan="2"><br /></td>
    </tr>
  
    <tr>
        <td>
            <span class="table_heading">' . $_POST['companyName'] . '</span><br />' .
            $_POST['addy1'] .' <br /> ';
			if($_POST['addy2']!=""){
            	$html_message .= $_POST['addy2'].' <br /> ';
			}
            $html_message .= $_POST['city'].", ".$_POST['state']." ".$_POST['zip'].' <br /> ' .
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
		$html_message .= "<p>Congratulations on completing your order (" . $_POST['productName'] . ") with " . $_POST['comp'] . ".  You are on your way to securing your place as a verified local business with the major search engines.</p>
    <p>In order to fulfill your order and verify/reverify your online listings with Google, Yahoo, and/or Bing <em>we need your permission</em>.  <strong>We are not Google, Yahoo, Bing, or any other search engine</strong>, but rather we are a 3rd party who works directly with the search engines to claim, verify, and build out your business's listing to the specifications provided by the search engines.  The listings we are creating for you are YOUR listings. They belong to you and are yours to do with as you please for the life of your business.  In order for us to act as your agent and claim, verify, and manage your listing we need your authorization. Note this authorization gives us permission to create and/or edit your listing as well as speak to the search engines (Google, Yahoo, and/or Bing) on your behalf. <u>It does not change the fact that YOU are the owner of your listing</u>.</p>
	
	 <p><strong>Please sign below indicating that you grant us permission to create or edit your listing as needed and to speak with the search engines on your behalf.</strong>  We build and manage your listing to the highest standards according to the exact specifications of each search engine and work directly with the search engines on any issues that arise with your listing.  Claiming, verifying, and maintaining your listing are essential parts of having a presence with the search engines and a top local business ranking is rarely achieved and maintained without doing so, however <strong><em>" . $_POST['comp'] . "</em></strong> makes no guarantee regarding a particular placement or search results ranking.  There is too much ongoing variability between categories, markets, and the search engines themselves for any company to guarantee a particular placement.</em></strong></p>";
	 // check if CC or echeck
	 if($_POST['CCnum']!="0"){
		 $html_message.=" <p>By signing you also acknowledge that you authorize us to charge the " . $_POST['CCType'] . " card ending in <strong>" . $_POST['CCnum'] . "</strong> on <strong>" . $_POST['Created'] . "</strong>";
	 } else {
		 $html_message.="<p>By signing you acknowledge that you authorize us to process the <strong>" . $_POST['Bank_Name'] ."</strong> account  ending in <strong>" . $_POST['AccountNumber'] . "</strong> on <strong>" . $_POST['Created'] . "</strong>";
	 }
$html_message .=" for <strong>$". $_POST['TotalPrice'] . "</strong>. This is a one-time charge that covers all work related to creating/editing/managing your online listing. 

By signing you also acknowledge that you are covered by our 100% commitment to customer satisfaction and agree that in the event you are dissatisfied for any reason with your purchase you will call our <strong>customer service department at <span style='white-space:nowrap'>" . $_POST['phone'] . "</span></strong> to discuss a resolution.</p>

<p>" . $_POST['comp'] . " is 100% committed to your satisfaction as our customer and will work with you to resolve any customer satisfaction issues. By signing you agree that you will not initiate any dispute through your credit card company or bank without first contacting our support department to resolve any issues. </p>
<p><strong>Please sign below</strong></p>
<p>" . $_POST['signature'] . "";
$html_message .= '<link href="http://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff" rel="stylesheet" type="text/css" >';
$html_message .= '<span class="signfont" style="font-family: \'Herr Von Muellerhoff\', cursive; text-align:center; margin-left: 28px; color:#346599; font-size:35px;">'.$_POST['signature'].'</span></p>';
  if(isset($_POST['unsigned'])){
	  $html_message .="<p>________________________<br />(Signature - " . $_POST['clientName'] . ")</p><p>Fax to: 1-866-841-3879</p>";
  } else {
	  $html_message .= "<p>" . $_POST['email'] . "</p>";
   }
$html_message .= "<p>IP Address (recorded): <strong>" . $_POST['IP'] ."</strong></p>
<p><strong>Transaction Date: " . $_POST['Created'] . "</strong>";
	} else {
		
		// ////////////////////websites ///////////////////
		
		$html_message .= '<link href="http://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff" rel="stylesheet" type="text/css" >';
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
  $html_message .= "<strong>Acceptance of Terms</strong></p>";
  
   // check if CC or echeck
	 if($_POST['CCnum']!="0"){
		 $html_message.=" <p>By signing you also acknowledge that you authorize us to charge the " . $_POST['CCType'] . " card ending in <strong>" . $_POST['CCnum'] . "</strong> on <strong>" . $_POST['Created'] . "</strong>";
	 } else {
		 $html_message.="<p>By signing you acknowledge that you authorize us to process the <strong>" . $_POST['Bank_Name'] ."</strong> account  ending in <strong>" . $_POST['AccountNumber'] . "</strong> on <strong>" . $_POST['Created'] . "</strong>";
	 }
$html_message .=" for <strong>$". $_POST['TotalPrice'] . "</strong>. This is a one-time charge that covers all work related to creating/editing/managing your online listing.

  By signing you also acknowledge that you are covered by our 100% commitment to customer satisfaction and agree that in the event you are dissatisfied for any reason with your purchase you will call our <strong>customer service department at <span style='white-space:nowrap'>" . $_POST['phone'] . "</span></strong> to discuss a resolution.<br/ ></strong>
  <br>
  By signing you agree that you will not initiate any dispute through your credit card company or bank without first contacting our support department to resolve any issues.  " . $_POST['comp'] . " is 100% committed to your satisfaction as our customer and will work with you to resolve any issues.<br>
  <br><table width='100%'><tr><td width='50%'>
  <strong>Please sign below</strong><br>";
  $html_message .= "<p>" . $_POST['signature'];
  $html_message .= '<span style="font-family: \'Herr Von Muellerhoff\', cursive; text-align:center; margin-left: 28px; color:#346599; font-size:35px;">'.$_POST['signature'].'</span></p>';
  if(isset($_POST['unsigned'])){
	  $html_message .="<p>________________________<br />(Signature - " . $_POST['clientName'] . ")</p></td><td align='right'><p>Fax to: 1-866-841-3879</p>";
  } else {
	  $html_message .= "<p>" . $_POST['email'] . "</p>";
   }
$html_message .="<p>IP Address (recorded): <strong>" . $_POST['IP'] ."</strong><br />
<span>Transaction Date: <strong>" . $_POST['Created'] . "</strong></span></p></td></tr></table></div>";
  	
	}
$html_message .= '</body></html>';
	
	# Lets try to replace the relative path from html message and add absolute path
	# But make sure that no other images are there in the text other those images will
	# also be replaced with this one. Incase there are more than one image then add some
	# condition so that the required image is only replaced and all other are as it is.
	$imagepath = "http://xurlios.com/".$_POST['invoiceImg'];
	preg_match('/(<img[^>]+>)/i', $html_message, $matches);
	$html_message_text = str_replace($matches[0], '<img src="'.$imagepath.'" />' , $html_message);	
	
	$path_pdf = "";
	$invoice_no = $_POST['clientID']."-".$_POST['orderID'];
	
	$dir = dirname(__FILE__)."/../";	
	
	$dompdf = new DOMPDF(); # Create new instance of dompdf
	$dompdf->load_html($html_message); # Load the html
	$dompdf->set_paper("letter", "portrait");
	$dompdf->render(); # Parse the html, convert to PDF
	$pdf_content = $dompdf->output(); # Put contents of pdf into variable for later
	
	$path_pdf = $_SERVER['DOCUMENT_ROOT']."/agreements/attachments/";
	$pdf_file = $dompdf->output();
	file_put_contents($path_pdf.$invoice_no.'.pdf', $pdf_content);
	$pdfAttachment = $path_pdf.$invoice_no.'.pdf';	
	
	if(isset($_POST['unsigned'])){
		echo "<a href='http://xurlios.com/agreements/attachments/".$invoice_no.".pdf' target='_blank'>Download Unsigned PDF</a>";
	} else {
		# Lets add the sendgrid related files here
		require '../clients/sendgrid-php/sendgrid-php/vendor/autoload.php';
		require '../clients/sendgrid-php/sendgrid-php/lib/SendGrid.php';
		
		$sendgrid = new SendGrid(SENDGRID_USERNAME, SENDGRID_PASSWORD);
		$mail = new SendGrid\Email();
		
		
		$cc = trim($_POST['company']);
		$mail->addTo($to)->
				addTo($cc)->
				addTo("billing@xurli.com")->
				setFrom(trim($_POST['company']))->
				setFromName('Xurli')->
				setSubject($subject);
		$mail->setHtml($html_message_text)->
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
	} // end unsigned if
}
/*'Dancing Script' !important*/
?>