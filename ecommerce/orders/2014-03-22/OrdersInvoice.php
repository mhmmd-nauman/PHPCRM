<?php
include "../../lib/include.php";
session_start();
$objorder = new Orders();
$objcompany = new Company();
$objproducts = new Products();
$orderid = $_REQUEST['OrderID'];
$objClient = new Clients();
$objusers = new Users();
$order_invioce_array = $objorder->GetAllOrderDetailWithProduct("OrderID=$orderid ",array("*"));



if(isset($_REQUEST['Task']) and $_REQUEST['Task'] == "CreatePreview"){
	extract($_REQUEST);
	$to = $order_invioce_array[0]['Email'];
	$client_name = $order_invioce_array[0]['FirstName']." ".$order_invioce_array[0]['Surname'];
	$subject = "Order from Xurli for ".$order_invioce_array[0]['FirstName']." ".$order_invioce_array[0]['Surname'];
    $Bcc = "billing@xerly.com";
    $BccName = "Xurli Billing";
    $Agent_ID = $order_invioce_array[0]['UserID'];
    $AgentDataArray = $objusers->GetAllUsers(USERS.".ID = $Agent_ID",array(USERS.".*"));
	$Phone = $AgentDataArray[0]['Phone']."-".$AgentDataArray[0]['Phone_Ext'];
	if(empty($Phone)){
		$Phone = $AgentDataArray[0]['AlternatePhone']."-".$AgentDataArray[0]['Phone_Ext'];
	}
	if(empty($Phone)){
		$Phone = "N/A";
	}
	$agent_email = $AgentDataArray[0]["Email"];
    $agent_name = $AgentDataArray[0]["FirstName"]." ".$AgentDataArray[0]["LastName"];
	# Start Building preview from here
	$html_message .= '<a href="#" style="float:right;" onclick="previewmailhide();">Close</a>';
	$html_message .= "<div style='margin:0 auto; width:90%; padding:20px; background-color:#fff;'>";
	$html_message .= "<br/>";
	$html_message .= "Dear ". $client_name.",";
	$html_message .= "<br/><br/>";
	$html_message .= "Thank you for you your order! <br/>";
	$html_message .= "See Invoice Included <br/>";
	if(!empty($order_invioce_array[0]['CPLID']) and $order_invioce_array[0]['Status'] == "Unpaid"){
		$html_message .= " You can complete your order securely by clicking on the link below.<br/><br/>"."<a class='showdetails' style='text-decoration:none;' target='_blank' href='https://www.xurlios.com/OrderForm.php?OrderID=".$OrderID."&MemberID=".$order_invioce_array[0]['MemberID']."&CPLID=".trim($order_invioce_array[0]['CPLID'])."'>Buy Now</a>";
	}
	$html_message .= "<br/><br/>";
	$html_message .= "Best regards,";
	$html_message .= "<br/>";
	$html_message .= $agent_name;
	$html_message .= "<br/>";
	$html_message .= "Online Presence Analyst";
	$html_message .= "<br/>";
	$html_message .= $Phone;
	$html_message .= "<br/>";
	$html_message .= $agent_email;
	$html_message .= "<br/><br/>";
	$html_message .= "CONFIDENTIALITY NOTICE: The Materials contained in this electronic mail<br/>
				transmission (including all attachments) are private and confidential and<br/>
				are the property of the sender. No information contained herein should be<br/>
				considered legal or tax advice. If you need legal or tax advice you should<br/>
				consult a licensed attorney or tax adviser. The information contained herein<br/>
				is privileged and is intended only for the use of the named addresses(s).<br/>
				You are hereby notified that any unauthorized dissemination, distribution,<br/>
				copying, disclosure, or the taking of any action in reliance of the contents<br/>
				of this material is strictly prohibited. If you have received this electronic<br/>
				mail transmission in error, please immediately notify the sender";
	$html_message .= "</div>";			
	echo $html_message;
	die();
}

# Below if condition will run when the Send Invoice button is clicked.
if (($_REQUEST['pstback'] == 1)) {
    $to = $order_invioce_array[0]['Email'];
    
    $client_name = $order_invioce_array[0]['FirstName']." ".$order_invioce_array[0]['Surname'];

	$subject = "Order from Xurli for ".$order_invioce_array[0]['FirstName']." ".$order_invioce_array[0]['Surname'];

    $Bcc = "billing@xerly.com";
    $BccName = "Xurli Billing";
   
    $Agent_ID = $order_invioce_array[0]['UserID'];
    $AgentDataArray = $objusers->GetAllUsers(USERS.".ID = $Agent_ID",array(USERS.".*"));
    $agent_email = $AgentDataArray[0]["Email"];
  
    $agent_name = $AgentDataArray[0]["FirstName"]." ".$AgentDataArray[0]["LastName"];
	$Phone = $AgentDataArray[0]['Phone']." Ext ".$AgentDataArray[0]['Phone_Ext'];
	if(empty($Phone)){
		$Phone = $AgentDataArray[0]['AlternatePhone']." Ext ".$AgentDataArray[0]['Phone_Ext'];
	}
	if(empty($Phone)){
		$Phone = "N/A";
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
    require_once($dir.'/pdf_email/dompdf/dompdf_config.inc.php');

    $dompdf = new DOMPDF();
    $dompdf->load_html($pdf_html);
    $dompdf->render();
    $pdf_content = $dompdf->output(); 
	
	ob_start();
	$html_message = "Dear ". $client_name.",";
	$html_message .= "<br/><br/>";
	$html_message .= "Thank you for you your order!<br/>";
	$html_message .= "See Invoice Included <br/>";
	
	if($_REQUEST['paymentnotmadeyet'] == 1){
		$html_message .= " You can complete your order securely by clicking on the link below.<br/><br/>"."<a class='showdetails' target='_blank' href='https://www.xurlios.com/OrderForm.php?OrderID=".$orderid."&MemberID=".$order_invioce_array[0]['MemberID']."&CPLID=".trim($order_invioce_array[0]['CPLID'])."'>Buy Now</a>";
	}
	$html_message .= "<br/><br/>";
	$html_message .= "Best regards,";
	$html_message .= "<br/>";
	$html_message .= $agent_name;
	$html_message .= "<br/>";
	$html_message .= "Online Presence Analyst";
	$html_message .= "<br/>";
	$html_message .= $Phone;
	$html_message .= "<br/>";
	$html_message .= $agent_email;
	$html_message .= "<br/><br/>";
	$html_message .= "CONFIDENTIALITY NOTICE: The Materials contained in this electronic mail<br/>
				transmission (including all attachments) are private and confidential and<br/>
				are the property of the sender. No information contained herein should be<br/>
				considered legal or tax advice. If you need legal or tax advice you should<br/>
				consult a licensed attorney or tax adviser. The information contained herein<br/>
				is privileged and is intended only for the use of the named addresses(s).<br/>
				You are hereby notified that any unauthorized dissemination, distribution,<br/>
				copying, disclosure, or the taking of any action in reliance of the contents<br/>
				of this material is strictly prohibited. If you have received this electronic<br/>
				mail transmission in error, please immediately notify the sender";
	ob_end_clean();
	
    $invoice_no = $order_invioce_array[0]['UserID']."-".$order_invioce_array[0]['MemberID']."-".$order_invioce_array[0]['ID'];

    #added on 19th march 2014
	require 'sendgrid-php/sendgrid-php/vendor/autoload.php';
	require 'sendgrid-php/sendgrid-php/lib/SendGrid.php';
	
	$sendgrid = new SendGrid(SENDGRID_USERNAME, SENDGRID_PASSWORD);
	$mail = new SendGrid\Email();
	$pdf_file = $dompdf->output();
	file_put_contents('attachments/'.$invoice_no.'.pdf', $pdf_file);
	$mailling_list = array($to);
	//$mailling_bcc = array($agent_email,  'snyhof@the220companies.com');
	$mail->addTo($to)->
			addTo($agent_email)->
			addTo($Bcc)->
			setFrom('billing@xerly.com')->//setFrom('admin@xurlios.com')->
			setFromName('Xurli Billing')->
			setSubject($subject);
	$mail->setHtml($html_message)->
			addAttachment('attachments/'.$invoice_no.'.pdf');//$filePath."/uploads/".$_FILES['uploadFile']['name']);	
	#upto here	
  //  $mailer = new Swift_Mailer(new Swift_MailTransport()); // Create new instance of SwiftMailer
    
   /* $message = Swift_Message::newInstance()
			   ->setSubject($subject) // Message subject
			   ->setTo(array($to =>$client_name )) // Array of people to send to $client_email =>$client_name
			   ->setFrom(array('admin@xurlios.com' => 'xerlyos'))
			   ->setBcc(array($Bcc => $BccName))
			   ->setCc(array($agent_email => $agent_name))// From:
			   ->setBody($html_message, 'text/html') // Attach that HTML message from earlier
			   ->attach(Swift_Attachment::newInstance($pdf_content, $invoice_no.'.pdf', 'application/pdf')); // Attach the generated PDF from earlier
    */
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
	margin-top: 10px;
}
table tr td {
	font-size: 12px;
}
.border_replace {
	border: none;
	padding-left: 10px;
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
<body>
<div class="subcontainer">
  <table width='100%' border='0' cellspacing='0' cellpadding='5'>
	<?php 
		$user_Company_array = $objClient->FetchAgentCompanyDetailsforInvoice($order_invioce_array[0]['UserID']);
		
		if($user_Company_array[0]['ID'] == '2'){
    ?>
    <tr>
      <td width="50%" class="border_replace"><img src="<?php echo SITE_ADDRESS;?><?php echo $user_Company_array[0]['InvoiceImage'];?>" height="70" /> </td>
      <td class="invoice">INVOICE</td>
    </tr>
    <tr>
      <td  width="50%" valign="top"class="border_replace"><span class='table_heading'><?php echo $user_Company_array[0]['CompanyName'];?></span><br />
        <?php echo $user_Company_array[0]['Address1']." ".$user_Company_array[0]['Address2'];?><br /> <?php echo $user_Company_array[0]['City'].", ".$user_Company_array[0]['State']." ".$user_Company_array[0]['Zip'];?><br/>
        <?php echo $user_Company_array[0]['Phone'];?> </td>
      <td width="50%" valign="top"class="border_replace" align="right">
            <table width='250' border='0' cellspacing='0' cellpadding='0'align="right">
              <tr>
                <td align="left">Invoice Date &nbsp; </td>
                <td align="right"><b><?php echo date("<b>M d</b>, Y",strtotime($order_invioce_array[0]['Created'])); ?></b></td>
              </tr>
              <tr>
                <td align="left">Invoice#</td>
                <td align="right"><b><?php echo $order_invioce_array[0]['UserID']."-".$order_invioce_array[0]['MemberID']."-".$order_invioce_array[0]['ID']; ?></b></td>
              </tr>
              <tr>
                <td align="left">Customer ID</td>
                <td align="right"><b><?php echo $order_invioce_array[0]['MemberID']; ?></b></td>
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
                <td align="right"><b><?php echo date("<b>M d</b>, Y",strtotime($order_invioce_array[0]['Created'])); ?></b></td>
              </tr>
              <tr>
                <td align="left">Invoice#</td>
                <td align="right"><b><?php echo $order_invioce_array[0]['UserID']."-".$order_invioce_array[0]['MemberID']."-".$order_invioce_array[0]['ID']; ?></b></td>
              </tr>
              <tr>
                <td align="left">Customer ID</td>
                <td align="right"><b><?php echo $order_invioce_array[0]['MemberID']; ?></b></td>
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
        <?php echo $order_invioce_array[0]['CompanyName']; ?><br />
        <?php echo $order_invioce_array[0]['FirstName']." ".$order_invioce_array[0]['Surname'];?><br/>
        <?php echo $order_invioce_array[0]['StreetAddress1']." ".$order_invioce_array[0]['StreetAddress2'];?><br/>
        <?php echo $order_invioce_array[0]['BillingCity'].", ".$order_invioce_array[0]['BillingState']." ".$order_invioce_array[0]['BillingPostalCode'];?><br/>
        <?php echo $order_invioce_array[0]['BillingCountry'];?><br/>
      </td>
      <td class="border_replace" ><strong>Bill To:</strong><br/>
        <?php echo $order_invioce_array[0]['CompanyName']; ?><br />
        <?php echo $order_invioce_array[0]['FirstName']." ".$order_invioce_array[0]['Surname'];?><br/>
        <?php echo $order_invioce_array[0]['StreetAddress1']." ".$order_invioce_array[0]['StreetAddress2'];?><br/>
        <?php echo $order_invioce_array[0]['BillingCity'].", ".$order_invioce_array[0]['BillingState']." ".$order_invioce_array[0]['BillingPostalCode'];?><br/>
        <?php echo $order_invioce_array[0]['BillingCountry'];?><br/></td>
    </tr>
  </table>
  <table width="100%" cellpadding="5" cellspacing="0">
  	<tr style="height:25px;">
    	<td>&nbsp;</td>
    </tr>
  </table>
  <table width="100%" cellpadding="5" cellspacing="0">
    <tr style=" background-color:#E3E3E3;" bgcolor="#E3E3E3">
      <td style="border: none;font-size: 10px;color: #000; font-weight: bold;font-family:Sans-Serif; background:#E3E3E3; ">Qty</td>
      <td style="border: none;font-size: 10px; color: #000; font-weight: bold;font-family:Sans-Serif; background:#E3E3E3; ">Product</td>
      <td style="border: none;font-size: 10px;color: #000; font-weight: bold;font-family:Sans-Serif;  background:#E3E3E3; ">Description</td>
      <td style="border: none; font-size: 10px;color: #000; font-weight: bold;font-family:Sans-Serif; background:#E3E3E3; ">Unit Price</td>
      <td style="border: none; font-size: 10px;color: #000; font-weight: bold;font-family:Sans-Serif; background:#E3E3E3; " align="right">Total Price</td>
    </tr>
  
    <?php 
	foreach ((array)$order_invioce_array as $Invoice_data){
	?>
        <tr>
            <td><?php echo $Invoice_data['Quantity'];?></td>
            <td><?php echo $Invoice_data['ProductName'];?></td>
            <td><?php echo $Invoice_data['Description'];?></td>
            <td>$<?php echo number_format($Invoice_data['ProductPrice'],2);?></td>
            <td align="right">$<?php echo number_format($Invoice_data['Quantity'] * $Invoice_data['ProductPrice'],2); ?></td>
        </tr>
        <?php 
		$totalPrice = "";
        $totalAmtPaid = $Invoice_data['Quantity'] * $Invoice_data['ProductPrice'];
        $totalPrice = $totalPrice + $totalAmtPaid;
        $_SESSION['totalamtpaid'] = $totalPrice;
    }
    ?>
  </table>
  <br>
  <table width="100%" style="background-color:#E3E3E3;">
    <tr>
      <td calspan="5" style="border: none; font-size: 10px; color: #000; font-weight: bold;" >Payments</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
  	<?php
    if(empty($order_invioce_array[0]['CredetCardNumber']) and $order_invioce_array[0]['Status'] == "Unpaid"){
	?>
        <tr>
          <td>Amount Due</td>
          <td colspan="4">&nbsp;</td>
          <td align="right">$<?php echo number_format($_SESSION['totalamtpaid'], 2); ?></td>
        </tr>
    <?php
	}elseif(!empty($order_invioce_array[0]['CredetCardNumber']) and $order_invioce_array[0]['Status'] == "Paid"){
	?>
    <tr>
      <td><b><?php echo date("<b>M d</b>, Y",strtotime($order_invioce_array[0]['Created'])); ?></b></td>
      <td>Credit card approved</td>
      <td><?php echo $order_invioce_array[0]['CredetCardType'];?></td>
      <td>
	  <?php 
        $unsecure_credit_card = $objClient->decrypt($order_invioce_array[0]['CredetCardNumber']);
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
	}elseif(empty($order_invioce_array[0]['CredetCardNumber']) and $order_invioce_array[0]['Status'] == "Paid" and !empty($order_invioce_array[0]['PaidThrough'])){
	?>
	<tr>
      <td><b><?php echo date("<b>M d</b>, Y",strtotime($order_invioce_array[0]['Created'])); ?></b></td>
      <td>Paid Through</td>
      <td>&nbsp;</td>
      <td>
	  <?php 
        if($order_invioce_array[0]['PaidThrough'] == "cash"){
			echo "Cash";
		}elseif($order_invioce_array[0]['PaidThrough'] == "check"){
			echo "Check";
		}elseif($order_invioce_array[0]['PaidThrough'] == "echeck"){
			echo "Electronic Check";
		}elseif($order_invioce_array[0]['PaidThrough'] == "cc"){
			echo "Credit Card";
		}elseif($order_invioce_array[0]['PaidThrough'] == "other"){
			echo "Other Modes of Payment";
		}elseif($order_invioce_array[0]['PaidThrough'] == "edebitDirect"){
			echo "Electronic Debit Card";
		}
	  ?>
      </td>
      <td><?php echo $order_invioce_array[0]['NotesForpaidThrough']; ?></td>
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
  </table>
  <table width="100%" style="background-color:#E3E3E3; ">
    <tr style=" background-color:#E3E3E3;">
      <td  style="border: none; font-size: 10px;color: #000; font-weight: bold;">Total Amount Paid</td>
      <td style="border: none;  background-color:#E3E3E3;">&nbsp;</td>
      <td style="border: none; background-color:#E3E3E3; ">&nbsp;</td>
      <td style="border: none; background-color:#E3E3E3; ">&nbsp;</td>
      <td style="border: none;font-size: 10px;color: #000; background-color:#E3E3E3; font-weight: bold;" align="right">$<?php if($order_invioce_array[0]['Status'] == "Unpaid") { echo "0.00"; } else { echo number_format($_SESSION['totalamtpaid'], 2); } ?></td>
    </tr>
  </table>
  <p></p>
  <div style="float:right; margin-top:25px; font-size:15px; cursor:pointer;"><a onClick="print_me();"><img src="images/print_icon.gif" title="Print this order page." /></a></div>
  <form action="?OrderID=<?php echo $_REQUEST['OrderID'];?>" method="post" target="_self">
  <div align="center" style="bottom: 23px; position: fixed; margin-top: 10px; width: 100%;">
    <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onClick="close_popup()" type="button" value="Close">
    &nbsp;
    <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit" value="Send Invoice">
    &nbsp;
    <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" onClick="mailpreview('<?php echo $_REQUEST['OrderID'];?>')" value="Preview Mail">
    <?php
    if(empty($order_invioce_array[0]['CredetCardNumber']) and $order_invioce_array[0]['Status'] == "Unpaid"){
		echo "<input type='hidden' name='paymentnotmadeyet' value='1' />";
	}
	?>
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
