<?php
error_reporting(0);
$objorder = new Orders();
$orderid = $_SESSION['orderid'];
$objClient = new Clients();
$objcompany = new Company();
$objinvoicehistory = new OrdersInvoiceHistory();
$objMerchant = new MerchantAccount();
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
$order_invioce_array = $objorder->GetAllOrderDetailWithProduct("OrderID=$orderid ",array("*"));
$added = $objinvoicehistory->InsertInvoice(array(
	"Created"            => date("Y-m-d h:i:s",time()),
	"OrderID"            => $orderid,
	"DescriptionText"    => 'The invoice genrated by agent',
	"ByID"               => $_SESSION['Member']['ID'],
));

?>
<style type="text/css">
.subcontainer1 {
	padding: 10px;
	padding-bottom:20px;
	border-color:#E4DBC5;
	border-radius: 8px;
	margin-top:12px;
	margin-right:10px;
	margin-left:10px;
	margin-bottom:10px;
	background-color:#fff;
}
.table_heading {
	font-weight:bold;
	font-size:15px;
}
.invoice {
	width:99%;
	text-align:right;
	color:#8394c9;
	font-size:20px;
	font-weight:bold;
	margin-top: 10px;
}
.border_replace {
	border: none;
	padding-left: 10px;
}
.table_heading {
	font-weight:bold;
	font-size:15px;
}
.headerbar {
	/*background-image:url(../images/headerbar.png);background-repeat:repeat-x;*/
	background-color:#F8F8F8;
	text-indent:4px;
	font-size:12px;
	font-weight:bold;
	color:#333;
	height:45px;/*higher for main pages*/
	letter-spacing:.2px;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-family:Sans-Serif;
	font-size:9px;
	color:#666;
	
}

</style>

<div class="body" style="border-radius: 5px;">
  <br>
  <br>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" >
        <?php 
            $user_Company_array = $objClient->FetchAgentCompanyDetails($order_invioce_array[0]['UserID']);
            ?>
          <tr>
            <td width="60%" valign="top" ><img src="<?php echo $_SERVER['DOCUMENT_ROOT']."/".$user_Company_array[0]['InvoiceImage']; ?>" /></td>
            <td align="right" valign="top"><div class="invoice">INVOICE</div></td>
          </tr>
        </table>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="65%" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <?php 
		  		
				$objusers = new Users();
				$AgentID = $order_invioce_array[0]['UserID'];
				$AgentData = $objusers->GetAllUsers(USERS.".ID =".$AgentID,array(USERS.".CompanyID"));
				$user_Company_array = $objcompany->GetAllCompany("ID ='".$AgentData[0]['CompanyID']."' ",array("*"));
				if($user_Company_array[0]['ID'] == '2'){
		  ?>
          <tr>
            <td  class="border_replace"><b><span style=" font-size: 14px;"><?php echo $user_Company_array[0]['CompanyName'];?></span></b><br />
              <?php echo $user_Company_array[0]['Address1']." ".$user_Company_array[0]['Address2'];?> <br>
              <?php echo $user_Company_array[0]['City'].", ".$user_Company_array[0]['State']." ".$user_Company_array[0]['Zip'];?><br/>
              <?php echo $user_Company_array[0]['Phone'];?></td>
          </tr>
          <?php }else{?>
          <tr>
            <td  class="border_replace"><b> <span style=" font-size: 14px;"><?php echo $user_Company_array[0]['CompanyName'];?></span></b><br />
              <?php echo $user_Company_array[0]['Address1']." ".$user_Company_array[0]['Address2'];?> <br>
              <?php echo $user_Company_array[0]['City'].", ".$user_Company_array[0]['State']." ".$user_Company_array[0]['Zip'];?><br/>
              <?php echo $user_Company_array[0]['Phone'];?></td>
          </tr>
          <?php } ?>
         
        </table></td>
      <td align="right" valign="top">&nbsp;
        <table width='190' border='0' align="right" cellpadding='2' cellspacing='0'>
          <tr>
            <td >Invoice Date</td>
            <td  align="right"><b><?php echo date("<b>M d</b>, Y",strtotime($order_invioce_array[0]['Created'])); ?></b></td>
          </tr>
          <tr>
            <td >Invoice #</td>
            <td align="right"><b><?php echo $order_invioce_array[0]['UserID']."-".$order_invioce_array[0]['MemberID']."-".$order_invioce_array[0]['OrderID']; ?></b></td>
          </tr>
          <tr>
            <td >Customer ID</td>
            <td align="right"><b><?php echo $order_invioce_array[0]['MemberID']; ?></b></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="90%" border="0" align="center">
    <tr>
      <td width="70%"><strong>Ship To.</strong><br/>
        <?php echo $order_invioce_array[0]['CompanyName'];?><br/>
		<?php echo $order_invioce_array[0]['FirstName']." ".$order_invioce_array[0]['Surname'];?><br/>
        <?php echo $order_invioce_array[0]['StreetAddress1']." ".$order_invioce_array[0]['StreetAddress2'];?><br/>
        <?php echo $order_invioce_array[0]['BillingCity'].", ".$order_invioce_array[0]['BillingState']." ".$order_invioce_array[0]['BillingPostalCode']; ?><br/>
        <?php echo $order_invioce_array[0]['BillingCountry'];?></td>
      <td><strong>Bill To.</strong><br/>
      <?php echo $order_invioce_array[0]['CompanyName'];?><br/>
        <?php echo $order_invioce_array[0]['FirstName']." ".$order_invioce_array[0]['Surname'];?><br/>
        <?php echo $order_invioce_array[0]['StreetAddress1']." ".$order_invioce_array[0]['StreetAddress2'];?><br/>
        <?php echo $order_invioce_array[0]['BillingCity'].", ".$order_invioce_array[0]['BillingState']." ".$order_invioce_array[0]['BillingPostalCode']; ?><br/>
        <?php echo $order_invioce_array[0]['BillingCountry'];?></td>
    </tr>
  </table>
  <br>
  <br>
  <table width="90%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr bgcolor="#E3E3E3" style="background-color:#E3E3E3; ">
      <td style="font-size: 10px; color: #000; font-weight: bold;">Qty</td>
      <td style="font-size: 10px;color: #000; font-weight: bold;">Product</td>
      <td style="font-size: 10px;color: #000; font-weight: bold;">Description</td>
      <td style="font-size: 10px;color: #000; font-weight: bold;">Unit Price</td>
      <td align="right" style="border: none;font-size: 10px;color: #000; font-weight: bold;">Total Price</td>
    </tr>
	<?php 
    foreach ((array)$order_invioce_array as $Invoice_data){
    ?>
    <tr>
        <td><?php echo $Invoice_data['Quantity']; ?></td>
        <td><?php echo $Invoice_data['ProductName']; ?></td>
        <td><?php echo $Invoice_data['Description']; ?></td>
        <td>$<?php echo number_format($Invoice_data['ProductPrice'],2); ?></td>
        <td align="right" style="border: none;font-size: 10px;color: #000; font-weight: bold;">$<?php echo number_format($Invoice_data['Quantity'] * $Invoice_data['ProductPrice'],2); ?></td>
    </tr>
        <?php 
        $totalAmtPaid = $Invoice_data['Quantity'] * $Invoice_data['ProductPrice'];
        $totalPrice = $totalPrice + $totalAmtPaid;
        $_SESSION['totalamtpaid'] = $totalPrice;
    }
    ?>
  </table>
  <br>
<br>
  <table width="90%" align="center" style=" background-color:#E3E3E3; ">
    <tr>
      <td calspan="5" style=" font-size: 10px; font-weight: bold; color: #000;">Payments</td>
    </tr>
  </table>
  <table width="90%" align="center">
    <?php
    if(empty($order_invioce_array[0]['CredetCardNumber']) and $order_invioce_array[0]['Status'] == "Unpaid"){
	?>
        <tr>
          <td>Amount Due</td>
          <td colspan="4">&nbsp;</td>
          <td align="right">$<?php echo number_format($_SESSION['totalamtpaid'], 2); ?></td>
        </tr>
    <?php
	}elseif(!empty($order_invioce_array[0]['CredetCardNumber'])){
	?>
    <tr>
      <td><b><?php echo date("<b>M d</b>, Y",strtotime($order_invioce_array[0]['Created'])); ?></b></td>
      <td>Credit card approved</td>
      <td><?php echo $order_invioce_array[0]['CredetCardType']; ?></td>
      <td>
	  <?php 
        $unsecure_credit_card = $objClient->decrypt($order_invioce_array[0]['CredetCardNumber']);
        $cardReplase = "************";
        $encriptcard = substr_replace($unsecure_credit_card,$cardReplase,0,12);
        echo $encriptcard;
	  ?>
      </td>
      <td align="right">
      Trans. ID #
      <?php 
	  ///echo $orderid . "<br />";
	  $transaction = $objMerchant->GetAllTransactionResponce("OrderID ='". $orderid. "'", array("*"));
	  if($transaction){
		echo $transaction[0]['TransactionID'];  
	  }
	  ?>
      </td>
    </tr>
  <?php
	}elseif(empty($order_invioce_array[0]['CredetCardNumber']) and $order_invioce_array[0]['Status'] == "Paid" and !empty($order_invioce_array[0]['PaidThrough'])){
	?>
		<tr>
		  <td><b><?php echo date("<b>M d</b>, Y",strtotime($order_invioce_array[0]['Created'])); ?></b></td>
		  <td>Paid Through</td>
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
		  <td>
		  <?php
          	echo $order_invioce_array[0]['NotesForpaidThrough'];
		  ?>
		  </td>
		  <td>&nbsp;</td>
		</tr>
	<?php
	}
	?>
   
  </table>
  <br>
<br>
  <table width="90%" align="center" style=" background-color:#E3E3E3; ">
    <tr>
      <td style=" font-size: 10px; font-weight: bold; color: #000;">Total Amount Paid </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right"style=" font-size: 10px; font-weight: bold; color: #000;">$<?php 
	  if ($order_invioce_array[0]['Status'] == "Paid"){
	  	echo number_format($_SESSION['totalamtpaid'],2);
	  }else{
	  	echo "0.00";
	  }
	  ?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="90%" align="center" >
  <tr style=" background-color:#E3E3E3; ">
  <td width="50%" style=" font-size: 10px; font-weight: bold; color: #000;">Terms
  </td>
  <td>&nbsp;</td>
  </tr>
  <tr>
  <td>
  Payable on Receipt
  </td>
  </tr>
  </table>
  <br />
  <div style="width: 100%; position: fixed; bottom: 70px; left:0; height: 70px;">
     <table width="90%" align="center" >
   <tr>
   <td>
  Thank you for doing business with us.  Our goal is to deliver exceptional service and earn our place as your permanent partner for managing your business online.  For more information about how Xurli can help you expand your online presence and attract more customers visit our website, www.Xurli.com.  If you have any questions about your order please call 800-873-4373 - we are here to assist you.
	</td>
    </tr>
    </table>
    </div>
</div>

<?php if($order_invioce_array[0]['Status'] != "Unpaid"){
	?>
    <style>
	.body {background-image:url("http://xurlios.com/ecommerce/orders/watermark.png");
	background-repeat:no-repeat;
	background-position:top center;
	font-size: 13px
	}
	</style>
    <?php 
} ?>

