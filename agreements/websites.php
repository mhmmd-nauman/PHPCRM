<?php 
include "../lib/outer_include.php";
$utilObj = new util();
$objClient = new Clients();
$objorder = new Orders();



if(isset($_GET['embed'])){
	if($_GET['embed'] == "true"){
		$embed = true;	
	} else {
		$embed = false;
	}
}

if(isset($_GET['client'])){
	$clientID = $_GET['client'];
} else{
	$clientID = "547";
}
$orderID = "64";


$verification = $objorder->GetAllOrderDetail("OrderID = '" . $clientID . "'", array("*"));

$client = $objClient->GetAllClients("ID='" . $clientID . "'",array( "*"));
$Client_CCData_array = $objClient->GetAllInformationCCData("ClientID = '".$clientID."'",array("*"));
if(!empty($Client_CCData_array[0]['CreditCardNumber'])) {
	$secure_credit_card = $objClient->decrypt($Client_CCData_array[0]['CreditCardNumber']);
	$credit_card_number = substr($secure_credit_card, -4, 4);
} else {
 	$credit_card_number = 0;		
	$accNum = substr($GetDetailsForPayment[0]['AccountNumber'], -4, 4);	
}


$Order = $objorder->GetAllOrder("MemberID = $clientID",array("*"));
$made = date("m/d/Y", strtotime($Order[0]['Created']));

$status = $objorder->GetAllOrderDetail("OrderId = '".$Order[0]['ID']. "'", array("*"));
$GetDetailsForPayment = $objorder->Getdetailsforpayment($Order[0]['ID']);

?>
<html>
<head>
<link rel="stylesheet" href="http://xurli.com/themes/Responsive/css/typography.css?m=1395352376">
<style>
div.iframeWrap {
	width: 96%;
	padding:2%;
	max-width: 1000px;
	margin-top: 25px;
	font-size: 15px;
	margin:0 auto;
	padding-bottom: 50px;

}
div.iframeWrap p {
	font-size: 15px;
}
td{
	font-size:inherit !important;
}
.fancyButton {
	display: inline-block;
	background: green;
	text-decoration:none;
	color: #fff;
	text-align:center;
	padding: 0.5em;	
}
.tables, .tables td {
	vertical-align: middle;
}
.header {
	padding: 10px;	
	margin-bottom: 15px;
}
.iframeWrap {
	margin: auto;
}
.button_success {
    background-color: #78CD51;
    border: 1px solid #72A53B;
    border-radius: 3px;
    color: #FFFFFF;
    cursor: pointer;
    font-family: "OpenSansSemiBold",Helvetica,Arial,sans-serif;
    font-size: 13px;
    font-style: normal;
    font-weight: bold;
    line-height: 20px;
    margin: 0;
    padding: 5px 35px;
    text-align: center;
    text-decoration: none;
    text-rendering: optimizelegibility;
    text-transform: none;
    transition: all 0.15s ease 0s;
    vertical-align: middle;
    white-space: normal;
}
.button_success:hover {
	background-color: #9BC969;
    border: 1px solid #72A53B;
}
h2 {margin: 0}
label.error {
	background: none repeat scroll 0 0 #FFDBDB;
    border: 1px solid #F6846C;
    color: #FF0000;
    font-weight: normal;
    margin-top: 2px;
    padding: 5px 10px;
    width: 157px;
}
.use .grumpy {
    display: inline;
    white-space: nowrap;
    width: 60%;
	float:left;
	
}
.Dancing-Script-normal-400 {
    font-family: 'Dancing Script' !important;
    font-style: normal;
    font-weight: 400;
	font-size:25px;margin-left: 10px;
}
</style>

<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/styles.css" />
<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
<script>
function set_frame() {
	var body_height = parseInt($(".iframeWrap").height());
	body_height += 100;
	parent.$('#mbr_parent iframe').attr("height",body_height);	

}
$(document).ready(function(){
	//set_frame();
	var stuff = $("header").html();
	//$("#headerHtml").val(stuff);
});
$(window).resize(function(){
	//set_frame();	
});

</script>
</head>
<body>
<div class="iframeWrap subcontainer">

<?php 
if($Order[0]['TotalPrice'] == "" || $Order[0]['TotalPrice'] == "0" || $status[0]['Verified'] =="1") {
	echo "The Agreement has already been signed";
} else {
	$user_Company_array = $objClient->FetchAgentCompanyDetailsforInvoice($GetDetailsForPayment[0]['UserID']);
if($embed == false){ ?>
<div class="header" id="header" >
<table width="100%"  border="0" cellspacing="0" cellpadding="2" >
    	<tr  class="tables">
        	<td width="33%"><img src="<?php echo SITE_ADDRESS;?><?php echo $user_Company_array[0]['InvoiceImage'];?>" height="70" /></td>
            <td width="33%">&nbsp;</td>
            
            <td align="right" width="33%"><h2>ORDER AGREEMENT</h2></td>
        </tr>
	<tr>
    <td colspan="2"><br /></td>
    </tr>
    <tr>
        <td>
            <span class='table_heading'><?php echo $user_Company_array[0]['CompanyName']; ?></span><br />
            <?php echo $user_Company_array[0]['Address1']; ?><br />
            <?php if($user_Company_array[0]['Address2']!=""){echo $user_Company_array[0]['Address2'] . "<br />"; } ?>
            <?php echo $user_Company_array[0]['City'].", ".$user_Company_array[0]['State']." ".$user_Company_array[0]['Zip']; ?><br />
            <?php echo $user_Company_array[0]['Phone'];?><br />
            United States
        </td>
        <td></td>
            <td align="right">
                <table width='100%' border='0' cellspacing='0' cellpadding='2'>
                    <tr>
                        <td width="50%">Order Date</td>
                        <td align="right"><b><?php echo date("M d, Y",strtotime($GetDetailsForPayment[0]['Created'])); ?></b></td>
                    </tr>
                    <tr>
                        <td>Customer ID</td>
                        <td align="right"><b><?php echo $GetDetailsForPayment[0]['MemberID']; ?></b></td>
                    </tr>
                </table>
            </td>
    </tr>
</table>

</div>
<?php } 
?>
         



<form action="submit.php" method="POST" id="agreementform">
<?php if(isset($_GET['unsigned'])){?>
	<input type="hidden" name="unsigned" value="true" />
<?php } ?>
    <input type="hidden" name="TotalPrice" value="<?php echo $Order[0]['TotalPrice']; ?>" />
    <input type="hidden" name="CCnum" value="<?php echo $credit_card_number; ?>" />
    
    <input type="hidden" name="BankName" value="<?php echo $GetDetailsForPayment[0]['Bank_Name'];?>" />
    <input type="hidden" name="AccountNumber" value="<?php echo substr($GetDetailsForPayment[0]['AccountNumber'], -4); ?>" />
    
    <input type="hidden" name="Created" value="<?php echo $made;?>" />
    <input type="hidden" name="IP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
    <input type="hidden" name="type" value="Website" />
    <input type="hidden" name="company" value="<?php echo $user_Company_array[0]['Email'];?>" />
    <input type="hidden" name="orderID" value="<?php echo $Order[0]['ID']; ?>" />
    <input type="hidden" name="clientID" value="<?php echo $_GET['client']; ?>" />
    
    <input type="hidden" name="clientName" value="<?php echo $client[0]['FirstName'] . " ". $client[0]['Surname']; ?>" />
    <input type="hidden" name="companyName" value="<?php echo $user_Company_array[0]['CompanyName']; ?>" />
    <input type="hidden" name='invoiceImg' value="<?php echo $user_Company_array[0]['InvoiceImage'];?>" /> 
    <input type="hidden" name='addy1' value="<?php echo $user_Company_array[0]['Address1'];?>" /> 
    <input type="hidden" name='addy2' value="<?php echo $user_Company_array[0]['Address2'];?>" /> 
    <input type="hidden" name='city' value="<?php echo $user_Company_array[0]['City'];?>" /> 
    <input type="hidden" name='state' value="<?php echo $user_Company_array[0]['State'];?>" /> 
    <input type="hidden" name='zip' value="<?php echo $user_Company_array[0]['Zip'];?>" /> 
    <input type="hidden" name='phone' value="<?php echo $user_Company_array[0]['Phone'];?>" /> 
    <input type="hidden" name='made' value="<?php echo date("M d, Y",strtotime($GetDetailsForPayment[0]['Created']));?>" /> 
     <input type="hidden" name="productName" value="<?php echo $GetDetailsForPayment[0]['ProductName']; ?>" /> 
    <input type="hidden" name='customer' value="<?php echo $GetDetailsForPayment[0]['MemberID'];?>" /> 
    <?php
    if($user_Company_array[0]['ID'] == "2") {
    	$company =  "2:20 Marketing Group dba Xurli";
		$companyShort = "220MG";
    } else {
    	$company =  "Xerly, LLC";
		$companyShort = "Xerly";
    }
	?>
    <input type="hidden" name='compShort' value="<?php echo $companyShort; ?>" />
	<input type="hidden" name='comp' value="<?php echo $company; ?>" />

	<p>Congratulations on completing your order (<?php echo $GetDetailsForPayment[0]['ProductName']; ?>) with 
	
	
	<?php
	echo $company."!";
    ?>
   <strong><br><br />
  Terms of Service</strong><br>
  <br>
  You will be given the opportunity to sign off and accept your website upon completion.<br>
  <ol>
  <li>ERRORS.  <?php echo $company . " ('" . $companyShort . "')";?> takes every care but accepts no responsibility for spelling mistakes or other errors when the artwork proof has been signed off.<br>
  </li>
  <li>CORRECTIONS. Artwork costs include two revisions only, so please check all artwork thoroughly. Any additional corrections or design changes will incur a design fee of $100.00 per hour, at no less than $200 total per revision.<br>
  </li>
  <li>COLOR. Due to the nature of monitors, screen colors are NOT an indication of the final viewed product. If Pantone colors or CMYK values have been specified, then these colors will be as close as possible to the final viewed product. <?php echo $companyShort; ?> takes no responsibility for color variations in the final viewed or printed product when Pantone or CMYK values have been specified, used and approved in the design.<br>
  </li>
  <li>THIRD PARTY PRINT PRODUCTION. <?php echo $companyShort; ?> takes no responsibility for the final output, supply or completion time for the goods or services provided by a supplier arranged by yourself; the client. On approval of this final proof, <?php echo $companyShort; ?> is not liable and will take no responsibility for the goods or services provided to the client and/or print servicer. This includes the use of various print methods used (specifically color variations used for full color/digital output) by a third party to output the goods, <?php echo $companyShort; ?> is in no circumstance liable to any loss or expense resulting in an undesired outcome.<br>
  </li>
  <li>TRANSACTION IS FINAL. I understand I have 3 complete days from the purchase date to receive a full and prompt refund of the entire purchase amount if I request. Following the 3 days, I understand refunds will be considered on a case-by-case basis.  I understand no refund can be guaranteed for websites and other digital marketing services outside of 3 days as resources are committed well in advance of completion of any project.  I understand that by purchasing services from <?php echo $companyShort; ?> I am purchasing business-related services.  Any such purchase carries an implied risk, and I willingly accept that risk.<br>
  </li>
  </ol>
  
  
   <?php if($credit_card_number!="0"){
		// credit card
		?>
        
         <p>By signing you acknowledge that you authorize us to charge the <strong><?php echo $GetDetailsForPayment[0]['CredetCardType']; ?></strong> card ending in <strong><?php echo $credit_card_number; ?></strong> on <strong><?php echo $made;?></strong>
         <?php
	} else {
		// echeck 
		?>
		<p>By signing you acknowledge that you authorize us to process the <strong><?php echo $GetDetailsForPayment[0]['Bank_Name']; ?></strong> account  ending in <strong><?php echo substr($GetDetailsForPayment[0]['AccountNumber'], -4); ?></strong> on <strong><?php echo $made;?></strong>
		<?php
	}?>
  
  for <strong>$<?php echo $Order[0]['TotalPrice']; ?></strong>. This is a one-time charge that covers all work related to creating/editing/managing your online listing.  By signing you also acknowledge that you are covered by our 100% commitment to customer satisfaction and agree that in the event you are dissatisfied for any reason with your purchase you will call our <strong>customer service department at <span style="white-space:nowrap"><?php echo $user_Company_array[0]['Phone'];?></span></strong> to discuss a resolution.</p>
    <p><?php
    if($user_Company_array[0]['ID']=="2") {
    echo "2:20 Marketing Group dba Xurli";
    } else {
    echo "Xerly, LLC";
    }
    ?> is 100% committed to your satisfaction as our customer and will work with you to resolve any customer satisfaction issues.  By signing you agree that you will not initiate any dispute through your credit card company or bank without first contacting our support department to resolve any concerns.</p>
    <p><strong>Please sign below</strong></p>
    <p><input type="text" name="signature" value="" id="signature" class="inputsapp required" onKeyUp="addsignature(this);" onBlur="addsignature(this);"  /><span class="grumpy Dancing-Script-normal-400"></span><br>
    (Type Full Name)</p>
    <p><input type="email" class="inputsapp required" value="<?php echo $client[0]["Email"]; ?>"  name="email" size="50" /> <br />*A copy of this signed agreement will be emailed to you.</p>
    <p>IP Address (recorded): <strong><?php echo $_SERVER['REMOTE_ADDR']; ?></strong></p>
    <p><strong>Transaction Date: <?php echo $made;?> </strong>
    <br /></p>
    
    <div style="float: left;">
    	<input type="submit" class="button_success" <?php if(isset($_GET['unsigned'])){} else {echo 'onClick="return Validatetheform();"';}?> value="I Agree" />
    </div>
    <div style="float: left; display: none;" class="loading_icon_order">
        <img src="../images/loading.gif" style="margin: 4px 0px 0px 10px;">
    </div>
    <div style="clear: both"></div><br />
    <small>By typing your name in the indicated field, you are agreeing to conduct business electronically with 2:20 Marketing Group in accordance with the federal Electronic Signatures in Global and National Commerce Act (E-Sign), 15 U.S.C.A. &sect; 7001-7031 (Supp. 2001).  Understand that transactions and/or signatures in records may not be denied legal effect solely because they are conducted, executed, or prepared in electronic form, and that if a law requires a record or signature to be in writing, an electronic record or signature satisfies that requirement.</small>
</form>

<?php } ?>
</div>



<script type="text/javascript">
function addsignature(what){
	if(what != ""){
		$(".grumpy").html($(what).val());
	}
}
function Validatetheform(){
	$("#agreementform").validate({
		errorElement:'label',
		rules: {
			Email:{
				required:true,
				email	: true,	
			}
		},
		messages:{
			Email:{
				required : "Enter email address",
				email 	 : "Enter valid email address",
			}
		},
	});
	
	if($("#agreementform").valid()){
		$(".loading_icon_order").show();
		$(".button_success").css({'background-color':'#e1e1e1','border':'1px solid #e1e1e1','color':'#666','pointer-events':'none'}).val("Please Wait..");
	}else{
		$(".loading_icon_order").hide();
	}
}
</script>

</body>
</html>