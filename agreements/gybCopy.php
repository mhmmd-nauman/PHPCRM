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


$client = $objClient->GetAllClients("ID='" . $clientID . "'",array( "*"));
$Client_CCData_array = $objClient->GetAllInformationCCData("ClientID = '".$clientID."'",array("*"));
$secure_credit_card = $objClient->decrypt($Client_CCData_array[0]['CreditCardNumber']);
//if(!empty($Client_CCData_array[0]['CreditCardNumber']))
$credit_card_number = substr($secure_credit_card, -4, 4);

$Order = $objorder->GetAllOrder("MemberID = $clientID",array("*"));
$made = date("m/d/Y", strtotime($Order[0]['Created']));


$status = $objorder->GetAllOrderDetail("OrderId = '".$Order[0]['ID']. "'", array("*"));
$GetDetailsForPayment = $objorder->Getdetailsforpayment($Order[0]['ID']);

?>
<html>
<head>
<title>Agreement</title>
<link rel="stylesheet" href="http://xurli.com/themes/Responsive/css/typography.css?m=1395352376">
<style>
div.iframeWrap {
	width: 96%;
	padding: 2%;
	max-width: 1000px;
	margin-top: 25px;
	font-size: 15px;
	margin:0 auto;

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

body.embedded {
	background: #fff;	
}
</style>

<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/styles.css" />
<link href="http://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff" rel="stylesheet" type="text/css" >
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
<body class="<?php if($embed ==true) {echo "embedded";} ?>">
<div class="iframeWrap subcontainer">

<?php 
//var_dump($GetDetailsForPayment);
//echo "<hr>";
?>
<?php 
if($Order[0]['TotalPrice'] == "" || $Order[0]['TotalPrice'] == "0" || $status[0]['Verified'] =="1") {
	echo "The Agreement has already been signed";
} else {
	$user_Company_array = $objClient->FetchAgentCompanyDetailsforInvoice($GetDetailsForPayment[0]['UserID']);
	//echo $user_Company_array[0]['InvoiceImage'].">>>>>>>>>>>>>>>>>>>>";
	
if($embed == false){ ?>
<div class="header" id="header" >
<table width="100%"  border="0" cellspacing="0" cellpadding="2" >
    	<tr  class="tables">
        	<td width="33%"><img src="<?php echo SITE_ADDRESS;?><?php echo $user_Company_array[0]['InvoiceImage'];?>" height="70" /></td>
            <td width="33%">&nbsp;</td>
            
            <td align="right" width="33%"><h2>Fulfillment Authorization</h2></td>
        </tr>
	<tr>
    <td colspan="2"><br /></td>
    </tr>
    <tr>
        <td>
            <span class='table_heading'><?php echo $user_Company_array[0]['CompanyName']; ?></span><br />
            <?php echo $user_Company_array[0]['Address1']; ?><br />
            <?php echo $user_Company_array[0]['Address2']; ?><br />
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

/*
$array_header = array(
'invoiceImg'=> SITE_ADDRESS . $user_Company_array[0]['InvoiceImage'], 
'addy1'=>$user_Company_array[0]['Address1'], 
'addy2'=>$user_Company_array[0]['Address2'],
'city'=>$user_Company_array[0]['City'],
'state'=> $user_Company_array[0]['State'],
'zip'=>$user_Company_array[0]['Zip'],
'phone'=>$user_Company_array[0]['Phone'],
'made'=>date("M d, Y",strtotime($GetDetailsForPayment[0]['Created'])),
'customer'=>$GetDetailsForPayment[0]['MemberID'],
);


function formatSerialize(&$strItem, $strKey)
{
    $strItem = str_replace('&', '[amp;]',$strItem);
}
function formatSerializeRev(&$strItem, $strKey)
{
    $strItem = str_replace('[amp;]', '&',$strItem);
}

array_walk_recursive($array_header,'formatSerialize');
$strSerialized = serialize($array_header);
*/
?>

<form enctype="multipart/form-data" action="submit.php" method="POST" id="agreementform">
<?php if(isset($_GET['unsigned'])){?>
	<input type="hidden" name="unsigned" value="true" />
<?php } ?>
    <input type="hidden" name="TotalPrice" value="<?php echo $Order[0]['TotalPrice']; ?>" />
    <input type="hidden" name="CCnum" value="<?php echo $credit_card_number; ?>" />
    <input type="hidden" name="Created" value="<?php echo $made;?>" />
    <input type="hidden" name="IP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
    <input type="hidden" name="type" value="GYB" />
    <input type="hidden" name="company" value="<?php echo $user_Company_array[0]['Email'];?>" />
    <input type="hidden" name="orderID" value="<?php echo $Order[0]['ID']; ?>" />
    <input type="hidden" name="clientID" value="<?php echo $_GET['client']; ?>" />
    <input type="hidden" name="embedded" value="<?php echo $embed;?>" />
    
    <input type="hidden" name="clientName" value="<?php echo $client[0]['FirstName'] . " ". $client[0]['Surname']; ?>" />
    <input type="hidden" name="CCType" value="<?php echo $GetDetailsForPayment[0]['CredetCardType']; ?>" />
    <input type="hidden" name="companyName" value="<?php echo $user_Company_array[0]['CompanyName']; ?>" />
    <input type="hidden" name='invoiceImg' value="<?php echo $user_Company_array[0]['InvoiceImage']; ?>" /> 
    <input type="hidden" name='addy1' value="<?php echo $user_Company_array[0]['Address1'];?>" /> 
    <input type="hidden" name='addy2' value="<?php echo $user_Company_array[0]['Address2'];?>" /> 
    <input type="hidden" name='city' value="<?php echo $user_Company_array[0]['City'];?>" /> 
    <input type="hidden" name='state' value="<?php echo $user_Company_array[0]['State'];?>" /> 
    <input type="hidden" name='zip' value="<?php echo $user_Company_array[0]['Zip'];?>" /> 
    <input type="hidden" name='phone' value="<?php echo $user_Company_array[0]['Phone'];?>" /> 
    <input type="hidden" name='made' value="<?php echo date("M d, Y",strtotime($GetDetailsForPayment[0]['Created']));?>" /> 
    <input type="hidden" name='customer' value="<?php echo $GetDetailsForPayment[0]['MemberID'];?>" />
    <input type="hidden" name="productName" value="<?php echo $GetDetailsForPayment[0]['ProductName']; ?>" /> 
	<input type="hidden" name='comp' value="<?php if($user_Company_array[0]['ID'] == "2") {
		echo "2:20 Marketing Group dba Xurli dba EZBizSites";
	 } else {
		echo "Xerly, LLC";
	 }?>" />
	<p>Congratulations on completing your order (<?php echo $GetDetailsForPayment[0]['ProductName']; ?>) with 
	<?php
    if($user_Company_array[0]['ID'] == "2") {
    	echo "2:20 Marketing Group dba Xurli dba EZBizSites.";
    } else {
    	echo "Xerly, LLC.";
    }
	
    ?>
    You are on your way to securing your place as a verified local business with the major search engines.</p>
    <p>In order to fulfill your order and verify/reverify your online listings with Google, Yahoo, and/or Bing <em>we need your permission</em>.  <strong>We are not Google, Yahoo, Bing, or any other search engine</strong>, but rather we are a 3rd party who works directly with the search engines to claim, verify, and build out your business's listing to the specifications provided by the search engines.  The listings we are creating for you are YOUR listings. They belong to you and are yours to do with as you please for the life of your business.  In order for us to act as your agent and claim, verify, and manage your listing we need your authorization. Note this authorization gives us permission to create and/or edit your listing as well as speak to the search engines (Google, Yahoo, and/or Bing) on your behalf. <u>It does not change the fact that YOU are the owner of your listing</u>.</p>
    
    <p><strong>Please sign below indicating that you grant us permission to create or edit your listing as needed and to speak with the search engines on your behalf.</strong>  We build and manage your listing to the highest standards according to the exact specifications of each search engine and work directly with the search engines on any issues that arise with your listing.  Claiming, verifying, and maintaining your listing are essential parts of having a presence with the search engines and a top local business ranking is rarely achieved without doing so, however <strong><em>
    <?php
    if($user_Company_array[0]['ID']=="2") {
    echo "2:20 Marketing Group dba Xurli dba EZBizSites";
    } else {
    echo "Xerly, LLC";
    }
    ?>
    makes no guarantee regarding a particular placement or search results ranking.  There is too much ongoing variability between categories, markets, and the search engines themselves for any company to guarantee a particular placement.</em></strong></p>
    
    
   <!-- <p><strong>Please sign below indicating that you grant us permission to create or edit your listing as needed and to speak with the search engines on your behalf.</strong></p>-->
    
    
    <p>By signing you acknowledge that you authorize us to charge the <strong><?php echo $GetDetailsForPayment[0]['CredetCardType']; ?></strong> card ending in <strong><?php echo $credit_card_number; ?></strong> on <strong><?php echo $made;?></strong> for <strong>$<?php echo $Order[0]['TotalPrice']; ?></strong>. This is a one-time charge that covers all work related to creating/editing/managing your online listing. 
    By signing you acknowledge that you are covered by our 100% commitment to customer satisfaction and agree that in the event you are dissatisfied for any reason with your purchase you will call our <strong>customer service department within 6 months of your purchase at <span style="white-space:nowrap"><?php echo $user_Company_array[0]['Phone'];?></span></strong> to discuss a resolution.</p>
   
    <p><?php
    if($user_Company_array[0]['ID'] == "2") {
    	echo "2:20 Marketing Group dba Xurli dba EZBizSites";
    } else {
    	echo "Xerly, LLC";
    }
    ?> is 100% committed to your satisfaction as our customer and will work with you to resolve any customer satisfaction issues.  By signing you agree that you will not initiate any dispute through your credit card company or bank without first contacting our support department to resolve any concerns.</p>
    <p><strong>Please sign below</strong></p>
    
    <!-- removed for consistency in the emails/pdf onKeyUp="addsignature(this);" onBlur="addsignature(this);" -->
    <p><input type="text" name="signature" value="" id="signature"  class="inputsapp required" onKeyUp="addsignature(this);" onBlur="addsignature(this);" /><span class="grumpy Dancing-Script-normal-400"></span><span style="font-family: 'Herr Von Muellerhoff', cursive; text-align:center; margin-left: 28px; color:#346599; font-size:35px;" class="cursivesignature">&nbsp;</span><br>
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
    <div style="clear: both"></div>
</form>

<?php } ?>
</div>
<div class="footer" style="height:30px;">&nbsp;</div>
<?php if(isset($_GET['unsigned'])){
	
} else {?>
<script type="text/javascript">
function addsignature(what){
	if(what != ""){
		$(".cursivesignature").html("");
		$(".cursivesignature").html($(what).val());
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
<?php } ?>
</body>
</html>