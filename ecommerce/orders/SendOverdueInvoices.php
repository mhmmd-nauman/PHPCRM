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

# This will be added when the send email button will be clicked.
$to = $order_invoice_array[0]['Email'];
$client_name = $order_invoice_array[0]['FirstName']." ".$order_invoice_array[0]['Surname'];

$Bcc = "billing@xurli.com";
$BccName = "Xurli Sales";
$Agent_ID = $order_invoice_array[0]['UserID'];
$AgentDataArray = $objusers->GetAllUsers(USERS.".ID = $Agent_ID",array(USERS.".*"));
# Agent Company Details
$AgentCompanyDetails = $objClient->FetchAgentCompanyDetailsforInvoice($Agent_ID);
$CompanyMail = $AgentCompanyDetails[0]['Email'];
$CompanyMail = (!empty($CompanyMail)) ? $CompanyMail : "billing@xurli.com";
$CompanyName = $AgentCompanyDetails[0]['CompanyName'];
$CompanyName = (!empty($CompanyName)) ? $CompanyName : "Billing Dept";

$subject = "Order from ".$CompanyName." for ".$order_invoice_array[0]['FirstName']." ".$order_invoice_array[0]['Surname'];

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

# Get the details of email which is being sent to 
$html_message = "Dear ". $client_name.",";
$html_message .= "<br/><br/>";
$html_message .= "The order you placed on ".date("<b>M d</b>, Y",strtotime($AgentDataArray[0]['Created']))." to claim and verify your search engine<br/> business listings has been placed,
but your payment did not successfully process. On ".date("<b>M d</b>, Y",strtotime($AgentDataArray[0]['Created']))." you spoke with our agent <b>".$agent_name."</b> and discussed the current vulnerable status of your listing.<br/>Currently your listing is not receiving the maximum exposure on Google and other search<br/> engines and remains at risk for removal.";
$html_message .= "<br/><br/>";
$html_message .= "Please make payment right away so we can begin the process of claiming and verifying your listing 
and get your business 'back on the map'. You may pay now using the following link:";
$html_message .= "<br/>";
$html_message .= "<a class='showdetails' style='color:blue; cursor:pointer;' target='_blank' href='https://www.xurlios.com/OrderForm.php?OrderID=".$orderid."&MemberID=".$order_invoice_array[0]['MemberID']."&CPLID=".trim($order_invoice_array[0]['CPLID'])."'>Complete Process Now</a>";
$html_message .= "<br/><br/>";			
$html_message .= "For any questions please contact our verification's and fulfillment department at 800-873-4373.
				  Press 2 for Technical Support then press 1 for Listings.";
$html_message .= "<br/><br/>";
$html_message .= "Thank You,";
$html_message .= "<br/><br/>";
$fullfillment_person_name = ($CompanyName == "Xerly, LLC") ? "Judy Crowder" : "Amber Lutui";
$html_message .= $fullfillment_person_name."";
$html_message .= "<br/>";
$html_message .= "Fulfillment Manager<br/>";
$html_message .= "Listings & Verifications Department<br/>";
$html_message .= $CompanyName;
$html_message .= "<br/><br/>";
$html_message .= "P.S. Your business listing is vulnerable and receiving only minimal exposure.
Complete your order now using this link <a class='showdetails' style='color:blue; cursor:pointer;' target='_blank' href='https://www.xurlios.com/OrderForm.php?OrderID=".$orderid."&MemberID=".$order_invoice_array[0]['MemberID']."&CPLID=".trim($order_invoice_array[0]['CPLID'])."'>Complete Process Now</a> so we can properly
claim and verify your listing and get your business 'back on the map'.";

if(isset($_POST['send_email'])){
	
	$insert_email_id = $objorder->InsertInvoiceDueEmailDetail(array(
		"Created"	   			=> date("Y-m-d h:i:s",time()),
		"LastEdited"			=> date("Y-m-d h:i:s",time()),
		"OrderID"				=> $_POST['OrderID'],
		"ClientID"				=> $_POST['clientid'],
		"SentBy"				=> $_SESSION['Member']['ID'],
		"Subject"				=> $_POST['email_subject'],
		"body_content"			=> addslashes($_POST['email_body']),
		"SentTo"				=> $_POST['to_email'],
		"SentFrom"				=> $_POST['from_email'],
	));
	#added on 19th march 2014
	require 'sendgrid-php/sendgrid-php/vendor/autoload.php';
	require 'sendgrid-php/sendgrid-php/lib/SendGrid.php';
	
	$sendgrid = new SendGrid(SENDGRID_USERNAME, SENDGRID_PASSWORD);
	$mail = new SendGrid\Email();
	$mail->addTo(trim($_POST['to_email']))->
			setFrom(trim($_POST['from_email']))->
			setFromName($CompanyName)->
			setSubject(trim($_POST['email_subject']));
	$mail->setHtml($html_message);	
	#upto here	
    # Send the email, and show user message
	$s = $sendgrid->send($mail);
	if($s->message == "success"){
		$update_status = $objorder->UpdateInvoiceDueEmailStatus(" ID = '".$insert_email_id."' ",array(
			"Status" => ucfirst($s->message),
		));
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="message_success">Invoice Sent Successfully!</td>
			</tr>
		</table>
		<script type="text/javascript">
            window.scrollTo(0,0);
        </script>
	<?php
	}else{
		$update_status = $objorder->UpdateInvoiceDueEmailStatus(" ID = '".$insert_email_id."' ",array(
			"Status" => ucfirst($s->message),
		));
	?>
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="message_success"><?php echo ucfirst($s->message); ?></td>
			</tr>
		</table>
		<script type="text/javascript">
            window.scrollTo(0,0);
        </script>
    <?php
	}
}

# fetch the details of the emails sent to the clients only for unpaid orders
$emailssent = $objorder->getSentEmailDetails("OrderID = '".$orderid."' Order by ID DESC",array("*"));
$subject_line = "";
$message = "";
if(count($emailssent) == 0){
	$subject_line = "Overdue Invoice 1st Notice";
}elseif(count($emailssent) == 1){
	$subject_line = "Overdue Invoice 2nd Notice";
}elseif(count($emailssent) == 2){
	$subject_line = "Overdue Invoice Final Notice";
}else{
	$message = "Three emails already sent.";
	$subject_line = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/styles.css" />
<link type="text/css" rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-te-1.4.0.css">
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<style type="text/css">
.jqte{
	margin:0 !important;
	border:medium none !important;
}
.jqte:focus{
	box-shadow:none !important;
}
#tabsubheading{
	padding:4px 4px 10px 10px !important;
}
</style>
</head>

<body>
    <div class="subcontainer">
        <table width='100%' border='0' cellspacing='0' cellpadding='5'>
            <tr id="headerbar">
                <td>ID</td>
                <td>Email Date</td>
                <td>Email Subject</td>
                <td>Sent By</td>
                <td>Sendgrid</td>
            </tr>
            <?php
            if(count($emailssent) > 0){
				foreach((array)$emailssent as $eachemail){
				?>
                	<tr <?php if($insert_email == $eachemail['ID']) { echo 'style="background-color:#AFFFAB;"'; } ?>>
                    	<td><?php echo $eachemail['ID']; ?></td>
                        <td><?php echo date("<b>M d</b>, Y",strtotime($eachemail['Created'])); ?></td>
                        <td><?php echo $eachemail['Subject']; ?></td>
                        <td><?php echo $objClient->FetchAgentName($eachemail['SentBy']); ?></td>
                        <td style="color:#006600; font-weight:bold;"><?php echo $eachemail['Status']; ?></td>
                    </tr>
                <?php
				}
			}else{
				echo "<tr style='background-color:#FAFAD2; text-align:center;'><td colspan='5'>No Emails Sent.</td><tr>";
			}
			?>
        </table>
        <p>&nbsp;</p>
        <p id="tabsubheading">New Email</p>
        <?php
        if(!empty($message) and count($emailssent) > 2){
		?>
        	<p style="color:#FF0000; background-color: #F9D5D1; padding: 6px;"><?php echo $message; ?></p>
        <?php
        }
		?>
        <form method="post" name="overdueinvoice" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
        	<table width='100%' border='0' cellspacing='0' cellpadding='5'>
            	<tr>
                	<td style="width:150px;">To:</td>
                    <td ><input style="width:250px;" type="text" name="to_email" class="product required" value="<?php echo $order_invoice_array[0]['Email']; ?>" /></td>
                    <td>&nbsp;</td>
                </tr>
            	<tr>
                	<td style="width:150px;">From:</td>
                    <td ><input style="width:250px;" type="text" name="from_email" class="product required" value="<?php echo $CompanyMail; ?>" /></td>
                    <td>&nbsp;</td>
                </tr>
            	<tr>
                	<td style="width:150px;">Email Subject:</td>
                    <td ><input style="width:250px;" type="text" name="email_subject" class="product required" value="<?php echo $subject_line; ?>" /></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td colspan="3">Email Body:</td>
                </tr>
                <tr>
                    <td colspan="3" ><textarea name="email_body" id="email_body" rows="20"><?php echo nl2br($html_message); ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="3" >
                    	<input type="hidden" value="<?php echo $orderid; ?>" name="OrderID" />
                        <input type="hidden" value="<?php echo $order_invoice_array[0]['MemberID']; ?>" name="clientid" />
                        <input type="submit" value="Send Email" name="send_email" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" /></td>
                </tr>
            </table>
        </form>
    </div>
<script type="text/javascript">
$(document).ready(function(){
	$('#email_body').jqte();
});
</script>
</body>
</html>
