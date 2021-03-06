<?php
# include $_SERVER['DOCUMENT_ROOT']."/lib/include.php";
error_reporting(0);
$objorder = new Orders();

$orderid = $_SESSION['orderid'];

$objClient = new Clients();
$objcompany = new Company();
$objinvoicehistory = new OrdersInvoiceHistory();
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
$order_invioce_array = $objorder->GetAllOrderDetailWithProduct("OrderID = '$orderid' ",array("*"));
$added = $objinvoicehistory->InsertInvoice(array(
	"Created"            => date("Y-m-d h:i:s",time()),
	"OrderID"            => $orderid,
	"DescriptionText"    => 'The invoice genrated by agent',
	"ByID"               => $_SESSION['Member']['ID'],
));

$invoice_no = $_SESSION['Member']['ID']."-".$order_invioce_array[0]['MemberID']."-".$orderid;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
	color:#666;
}
</style>
</head>

<body>
    <div class="subcontainer" style="width:89%; padding:0 50px; margin: 0 auto;">
        
        <p>&nbsp;</p>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
        <?php 
            $user_Company_array = $objClient->FetchAgentCompanyDetails($order_invioce_array[0]['UserID']);
            ?>
          <tr>
            <td width="60%" valign="top" ><img src="https://www.xurlios.com/<?php echo $user_Company_array[0]['InvoiceImage'];?>" height="70" /></td>
            <td align="right" valign="top"><div class="invoice">ORDER FORM</div></td>
          </tr>
        </table>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="60%" valign="top" >
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
        <table width='230' border='0' align="left" cellpadding='2' cellspacing='0'>
          <tr>
            <td >Order Date</td>
            <td align="right"><b><?php echo date("<b>M d</b>, Y",strtotime($order_invioce_array[0]['Created'])); ?></b></td>
          </tr>
          <tr>
            <td >Customer ID</td>
            <td align="right"><b><?php echo $order_invioce_array[0]['MemberID']; ?></b></td>
          </tr>
        </table>
       </td>
    </tr>
  </table>
        <div style="height:25px;">&nbsp;</div>
        <table width="100%"  border="0" cellspacing="0" cellpadding="2" style="table-layout:fixed;">
        	<?php
			$MemberID = $order_invioce_array[0]['MemberID'];
            $Clientsarray = $objClient->GetAllClients(CLIENTS.".ID = '".$MemberID."'",array("*"));
			?>
        	<tr>
            	<td width="30%">
                    <strong>Ship To:</strong><br/>
                    <?php
                    echo $Clientsarray[0]['CompanyName']."<br/>";
					echo $Clientsarray[0]['FirstName']." ".$Clientsarray[0]['Surname']."<br/>";
                    echo $Clientsarray[0]['Address']." ".$Clientsarray[0]['Address2']."<br/>";
                    echo $Clientsarray[0]['City'].", ".$Clientsarray[0]['State']." ".$Clientsarray[0]['ZipCode']."<br/>";
                    echo "United Sates";
                    ?>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="width:60px !important;">&nbsp;</td>
                <td width="30%">   	
                    <strong>Bill To:</strong><br/>
                    <?php
                    echo $Clientsarray[0]['CompanyName']."<br/>";
					echo $Clientsarray[0]['FirstName']." ".$Clientsarray[0]['Surname']."<br/>";
                    echo $Clientsarray[0]['Address']." ".$Clientsarray[0]['Address2']."<br/>";
                    echo $Clientsarray[0]['City'].", ".$Clientsarray[0]['State']." ".$Clientsarray[0]['ZipCode']."<br/>";
                    echo "United Sates";
                    ?>
                </td>
            </tr>
        </table>
        <div style="height:25px;">&nbsp;</div>
        <table width="100%" border="0" cellspacing="0" cellpadding="5" >
            <tr style=" background-color:#E3E3E3;" bgcolor="#E3E3E3">
                <td style="border: none;font-size: 10px;color: #000; font-weight: bold;">Qty</td>
                <td style="border: none;font-size: 10px;color: #000; font-weight: bold;">Product</td>
                <td style="border: none;font-size: 10px;color: #000; font-weight: bold;">Description</td>
                <td style="border: none;font-size: 10px;color: #000; font-weight: bold;">Unit Price</td>
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
                <td align="right" style="font-size: 10px;color: #000; font-weight: bold;">$<?php echo number_format($Invoice_data['Quantity'] * $Invoice_data['ProductPrice'],2); ?></td>
            </tr>
            <?php
            }
			?>
        </table>
        <div style="height:25px;">&nbsp;</div>
        <table width="100%">
            <tr style="background-color:#E3E3E3;" bgcolor="#E3E3E3">
                <td colspan="6" style="border: none; font-size: 10px; color: #000; font-weight: bold;">Payments</td>
            </tr>
            <tr>
                <td>Amount Due</td>
                <td colspan="4">&nbsp;</td>
                <td align="right">$<?php echo number_format($Invoice_data['ProductPrice'],2); ?></td>
            </tr>
        </table>
        <div style="height:25px;">&nbsp;</div>
        <table width="100%" style="background-color:#E3E3E3; ">
            <tr style=" background-color:#E3E3E3;">
                <td  style="border: none; font-size: 10px;color: #000; font-weight: bold;">Total Amount Paid</td>
                <td style="border: none;  background-color:#E3E3E3;">&nbsp;</td>
                <td style="border: none; background-color:#E3E3E3; ">&nbsp;</td>
                <td style="border: none; background-color:#E3E3E3; ">&nbsp;</td>
                <td style="border: none;font-size: 10px;color: #000; background-color:#E3E3E3; font-weight: bold;" align="right">$<?php if($order_invioce_array[0]['Status'] == "Unpaid") { echo "0.00"; } else { echo number_format($_SESSION['totalamtpaid'], 2); } ?></td>
            </tr>
        </table>
  	<p>&nbsp;</p>
    </div>
</body>
</html>