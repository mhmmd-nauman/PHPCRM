<?php
include "../lib/include.php";
global $link;
$objpackges = new Packges();
$objorder = new Orders();
$objproducts = new Products();
$objClient = new Clients();
$objorderform = new OrderForm();
$ObjMerchantAccount = new MerchantAccount();
$ObjGroup = new Groups();
$objuser = new Users();
$objCompany = new Company();

$OrderID = $_REQUEST['OrderID'];

//$GetDetailsForPayment = $objorder->Getdetailsforpayment($OrderID);

$GetDetailsForPayment = $objorder->GetAllOrderWithCompanyMatch(" `OrderItem`.ID = '$OrderID' ", array(ECORDERITEM.".*"));

$CompanyID = $objuser->GetAllUsers("Users.ID = '".$GetDetailsForPayment[0]['UserID']."'",array("CompanyID")); 

$MerchantID = $objCompany->GetAllCompany("ID='".$CompanyID[0]['CompanyID']."'",array("MerchantID"));

# Lets begin the Payment Process (Make payment later will be processed here)
if(isset($_REQUEST['PurchaseNow'])){
	//print_r($_REQUEST);
	$ObjMerchantAccount = new MerchantAccount();
    $MerchantData = $ObjMerchantAccount->GetAllMerchantAccount("MerchantId = '".$MerchantID[0]['MerchantID']."'",array("*"));
	# Fetch Agent Details
	$AgentDet = $objuser->GetAllUsers("Users.ID = '".$GetDetailsForPayment[0]['UserID']."'",array("Users.*"));
	$ClientID = $GetDetailsForPayment[0]['MemberID'];
	
	if(empty($MerchantData[0])){
		$ResponseMessage = "Transaction Error:  Check gateway for potential processing issue";
		header("location:CollectPayment.php?Merchant=notselected&ResponseMessage=$ResponseMessage&id=".$_REQUEST['OrderID']); 
	}
	
	

		if(empty($_REQUEST['totalprice'])){
			$totalprice_p = $GetDetailsForPayment[0]['TotalPrice'];
		}
		
	
		//$_REQUEST['totalprice'] = $_REQUEST['Camount'];
	//$_REQUEST['totalprice'] = $GetDetailsForPayment[0]['TotalPrice'];
	
	
	$merchantRecords = $MerchantData[0];
	$merchant_to_charge = $MerchantData[0]['AccountType'];
	$LoginID = trim($merchantRecords['LoginID']);
	$TransKey = trim($merchantRecords['TransactionKey']);
	$SandBox = true;
	
	$invoice_no = $_SESSION['Member']['ID']."-".time();
	$_SESSION['invoice'] = $invoice_no;
	if($merchantRecords['Mode'] == '-1')
	   $SandBox = false;
	else
	   $SandBox = true;
	require_once('../ecommerce/products/AuthorizeNet-sdk/AuthorizeNet.php'); 
	
	$CollectingPayment = "Later";
	
	switch($merchant_to_charge){
		case "14":
			 # Eay Direct Pay
			 require_once('ChargeOnGWAPI.php');
		break;
		default:
			# authorize.net code will go there
			 require_once('ChargeOnAuthNetAPI.php');
		break;
	}
	
	if($order_charged == 1) {
		require_once('SendInvoice.php');
		unset($_SESSION['OrderData']['Products']);
		$ResponseMessage = "Success! Credit card has been charged! Transaction ID: " . $transaction_id;
		
		$date = date("Y-m-d");
		$amount = $_REQUEST['Camount'];
		$clientID= $_REQUEST['ClientID'];
		$sql1 = "INSERT INTO `Payments` (Date, Amount, ClientID) VALUES('".$date."','".$amount."','".$clientID."')"; 
		mysqli_query($link, $sql1);
		
		/*
		$sql2;
		$arg = array(
			"ManualPaymentDate"=>date("Y-m-d")
		);
		
		$update = $objorder->updateOrderFields("ID = " . $_REQUEST['OrderID'], $arr);
		*/
	//$last_inserted_id = mysqli_insert_id($link);
	
		
		header("location:CollectPayment.php?flag=client_add&tras_approved=1&invoice_email_sent=$invoice_email_sent&ResponseMessage=$ResponseMessage&id=".$_REQUEST['OrderID']); 
		unset($_SESSION['OrderDataInRequest']);
	}else{
		//$ResponseMessage = " System Error! <br>Credit Card was not charged.";
		$_SESSION['OrderDataInRequest'] = $_REQUEST;
		
		header("location:CollectPayment.php?flag=card_failed_error&ResponseMessage=$ResponseMessage&id=".$_REQUEST['OrderID']."&OrderID=".$_REQUEST['OrderID']);
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Collect Payment Due</title>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<script type="text/javascript">
	$(function() {
		$( "#tabs" ).tabs();
	});
	
	$(document).ready(function(){
		$("#orderform").validate({
			errorElement:'label',
			
			messages:{
				Email:{
					required : "Enter your email address",
					email 	 : "Enter valid email address",
					remote	 : "Email already in use."
				}
			}
		});
	});
</script>
<style type="text/css">
label.error {
	background: none repeat scroll 0 0 #FFDBDB;
    border: 1px solid #F6846C;
    color: #FF0000;
    font-weight: normal;
    margin-top: 2px;
    padding: 5px 10px;
    width: 157px;
}
.inputsapp, .product{
	width:350px !important;
}
.button_success{
    background-color: #78CD51;
    border: 1px solid #72A53B;
    color: #FFFFFF;
    cursor: pointer;
    font-family: "OpenSansSemiBold",Helvetica,Arial,sans-serif;
    font-size: 13px;
    font-style: normal;
    font-weight: normal;
    line-height: 20px;
    margin: 0;
    padding: 7px 14px;
    text-align: center;
    text-decoration: none;
    text-rendering: optimizelegibility;
    text-transform: none;
    transition: all 0.15s ease 0s;
    vertical-align: middle;
    white-space: normal;
}
.button_success:hover{
    background-color: #9BC969;
	border: 1px solid #82BC43;
}
body{
	margin:0;
	padding:0;
	background-color:#fff !important;
}

</style>
</head>

<body>
	<?php
	$message = $_REQUEST['ResponseMessage'];
    if($_REQUEST['flag'] == 'client_add'){
		echo "<div class='message_success' style='width:94%;'>$message<br/>Please wait. You will be redirected in a moment.</div>"; 
	?>
		<script type="application/javascript">
			window.setInterval(function(){
				parent.location.reload();
			},5000);
		</script>
	<?php
	}elseif($_REQUEST['flag'] == 'card_failed_error'){
		echo "<div class='message_error' style='width:94%;'>$message</div>"; 
	}
	?>
	<div>&nbsp;</div>
	<div id="tabsubheading" style="padding:4px 0 10px 10px;">Order Details</div>
    <div>&nbsp;</div>
	<form action="CollectPayment.php?OrderID=<?php echo $OrderID; ?>&ClientID=<?php echo $_REQUEST['ClientID'];?>" method="post" id="orderform">
        <table width="100%"  border="0" cellspacing="0" cellpadding="2">
            <tr id="headerbarpopup">
                <td>Product Name</td>
                <td>Price</td>
                <td>Quantity</td>
                <td>Total</td>
            </tr>
            <tbody>
                <tr>
                    <td><?php echo $GetDetailsForPayment[0]['ProductName']; ?></td>
                    <td>$<?php echo number_format($GetDetailsForPayment[0]['ProductPrice'],2); ?></td>
                    <td><?php echo $GetDetailsForPayment[0]['Quantity']; ?></td>
                    <td>$<?php echo number_format((int)$GetDetailsForPayment[0]['ProductPrice'] * (int)$GetDetailsForPayment[0]['Quantity'],2); ?></td>
                </tr>
            </tbody>
        </table>
        <div>&nbsp;</div>
        <table width="100%"  border="0">
            <tr >
            	<td colspan="3" id="tabsubheading"> Billing Information</td>
            </tr>
            <tr>
                <td width="30%">Business Name:</td>
                <td width="65%">
                    <input class="inputsapp required" name="cname" value="<?php echo $GetDetailsForPayment[0]['CompanyName']; ?>" style="width:100%;" type="text"/>
                </td>
                <td width="5%">&nbsp;</td>
            </tr>
            
            <tr valign="top">
                <td>First Name:</td>
                <td>
                    <input class="inputsapp required" name="fName" value="<?php echo $GetDetailsForPayment[0]['FirstName']; ?>" type="text" style="width:100%;" />
                </td>
                <td >&nbsp;</td>
            </tr>
            <tr valign="top">
                <td > Last Name:</td>
                <td allign="left">
                    <input  class="inputsapp required" name="sureName" value="<?php echo $GetDetailsForPayment[0]['Surname']; ?>" type="text" style="width:100%;" />
                </td>
                <td>&nbsp;</td>
            </tr>
            
            <tr valign="top">
                <td  >Customer Email:</td>
                <td allign="left">
                    <input class="inputsapp" name="Email" readonly="readonly" value="<?php echo $GetDetailsForPayment[0]['Email']; ?>" type="email" style="width:100%;" />
                </td>
                <td>&nbsp;</td>
            </tr>
            
            <tr>
                <td >Best Phone: </td>
                <td allign="left">
                    <input class="inputsapp required" name="phone" value="<?php echo $GetDetailsForPayment[0]['Phone']; ?>" type="text" style="width:100%;" />
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td >Alternate Phone: </td>
                <td allign="left">
                    <input  class="inputsapp"name="alternatephone" value="<?php echo $GetDetailsForPayment[0]['AlternatePhone']; ?>" type="text" style="width:100%;" />
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><label>Others Notes:</label></td>
                <td> 
                    <textarea class="inputsapp" name="OthersNotes"  rows="5" style="width:100%; height: auto;"><?php echo $GetDetailsForPayment[0]['Notes']; ?></textarea>   
                </td>
                <td>&nbsp;</td>
            </tr>
            
            <tr >
                <td colspan="3" id="tabsubheading">Billing Address</td>
            </tr>
            <tr>
                <td><label>Street Address1:</label></td>
                <td> 
                    <input class="inputsapp required" name="Streetaddress1" value="<?php echo $GetDetailsForPayment[0]['StreetAddress1']; ?>" type="text" style="width:100%;" />   
                </td>
                <td>&nbsp;</td>
            </tr>
            
            <tr>
                <td><label> Street Address 2:</label></td>
                <td> 
                    <input class="inputsapp" name="Streetaddress2" value="<?php echo $GetDetailsForPayment[0]['StreetAddress2']; ?>" type="text" style="width:100%;" />   
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><label> City:</label></td>
                <td> 
                    <input class="inputsapp required" name="Bcity" value="<?php echo $GetDetailsForPayment[0]['City']; ?>" type="text" style="width:100%;" />   
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><label> State:</label></td>
                <td> 
                    <input class="inputsapp required" name="Bstate" value="<?php echo $GetDetailsForPayment[0]['State']; ?>" type="text" style="width:100%;" />   
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><label>Postal Code:</label></td>
                <td> 
                    <input class="inputsapp required" name="Bpostalcode" value="<?php echo $GetDetailsForPayment[0]['ZipCode']; ?>" type="text" style="width:100%;" />   
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><label>Country:</label></td>
                <td> 
                    <select class="product" name="BillingCountry">
                        <option value="US"><?php echo $GetDetailsForPayment[0]['BillingCountry']; ?></option>
                    </select>   
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr >
                <td colspan="3" id="tabsubheading">Payment Information</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <div class="creditcarddetails">
            <?php
				if(isset($_GET['ClientID'])){
					$client = $_GET['ClientID'];
				} else {
					$client	= $_GET['MemberID'];
				}
					$sql1 = "select * from `Payments` WHERE ClientID=" . $client; 
					$result = mysqli_query($link,$sql1);
					$tot = 0;
					if($result){
					while($row = mysqli_fetch_array($result)){
						$tot += $row['Amount'];
					}
					}
					$totalPrice = $GetDetailsForPayment[0]['ProductPrice'] * $GetDetailsForPayment[0]['Quantity'];
					$bal = $totalPrice - $tot;
					//echo $bal;
					?>
            	<tr class="creditcarddetails">
                	<td>Amount Billed</td>
                    <td><input type="text" disabled id="billed" name="billed"  value="<?php echo $totalPrice;?>" class="inputsapp" />
                    <input type="hidden" name="totalprice" value="<?php echo $totalPrice;?>" />
                    </td>
                    <td>&nbsp;</td>
                </tr>
            	<tr class="creditcarddetails">
                	<td>Balance</td>
                    <td>
                    
                    <input type="text" disabled id="balance" name="balance"  value="<?php echo $bal;?>" class="inputsapp" />
                    </td>
                    <td>&nbsp;</td>
                </tr>
            	<tr class="creditcarddetails">
                	<td>Amount To Charge:</td>
                    <td><input name="Camount" id="Camount" value="<?php echo $bal; ?>" class="inputsapp required" /></td>
                	<td>&nbsp;</td>
                </tr>
                <tr class="creditcarddetails">
                    <td width="30%">Credit Card Number:</td>
                    <td width="40%"><input name="Cnumber" value="" type="text" class="inputsapp required number" id="Cnumber" /></td>
                    <td width="30%">&nbsp;</td>
                </tr>
                <tr class="creditcarddetails">
                    <td>Expiration Date:</td>
                    <td>
                        <select name="ExpiryMonth" id="ExpiryMonth" class="product required" style="margin-bottom:5px;">
                            <option value="">Select Expiry Month</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August </option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </td>
                </tr>
                <tr class="creditcarddetails">
                    <td>Expiration Year:</td>
                    <td>
                        <select name="ExpiryYears" id="ExpiryYear" class="product required">
                            <option value="">Select Expiry Year</option>
                            <option value="2010">2010 </option>
                            <option value="2011">2011 </option>
                            <option value="2012">2012 </option>
                            <option value="2013">2013 </option>
                            <option value="2014">2014 </option>
                            <option value="2015">2015 </option>
                            <option value="2016">2016 </option>
                            <option value="2017">2017 </option>
                            <option value="2018">2018 </option>
                            <option value="2019">2019 </option>
                            <option value="2020">2020 </option>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="creditcarddetails">
                    <td>Credit Card Type:</td>
                    <td>
                        <select name="creditcardtype" class="product required">
                            <option value="">Select Card Type</option>
                            <option value="American Express">American Express</option>
                            <option value="American Express">Master Card</option>
                            <option value="Visa">Visa</option>
                            <option value="Discover">Discover</option>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="creditcarddetails">
                    <td>Security Code:</td>
                    <td> 
                        <input value="" name="Ccode" type="text" class="product required" id="Ccode"/> 
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </div>
        </table>
        
        <div style="height:25px;">&nbsp;</div>
            <div  align="center" style="background: none; bottom: 5px; left: 0; padding: 2px 0; width: 100%;">
            <input type="submit" name="PurchaseNow" value="Place Order Now" class="button_success" />
            <input type="hidden" name="OrderID"  value="<?php echo $OrderID; ?>"/>
            <input type="hidden" name="postback" value="1" />
        </div>
    </form>
</body>
</html>
