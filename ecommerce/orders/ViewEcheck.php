<?php
include "../../lib/include.php";
$objorder = new Orders();
$orderid = $_REQUEST['OrderID'];

if(empty($orderid)){
	$orderid = $_POST['OrderID'];
}

$orders = $objorder->GetAllOrderDetailWithProduct("OrderID = $orderid ",array("*"));

if($_SESSION['isAdmin']  != 1){ 
	echo "Access Denied";
	exit;
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Order Details</title>
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/styles.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
</head>
<body>
<div class="subcontainer">
   <h1>eCheck Information</h1>
   <table width="50%">
   <tr>
   <td>Bank_Name</td>
   <td><?php echo $orders[0]['Bank_Name'];?></td>
   </tr>
   <tr>
   <td>AccountName</td>
   <td><?php echo $orders[0]['AccountName'];?></td>
   </tr>
   <tr>
   <td>RoutingNumber</td>
   <td><?php echo $orders[0]['RoutingNumber'];?></td>
   </tr>
   <tr>
   <td>AccountNumber</td>
   <td><?php echo $orders[0]['AccountNumber'];?></td>
   </tr>
   <tr>
 
   <td>AccountHolderType</td>
   <td><?php echo $orders[0]['AccountHolderType'];?></td>
   </tr>
   
   <tr>
   	<td>AccountType</td>
    <td><?php echo $orders[0]['AccountType'];?></td>
   </tr>
   <tr>
   	<td>Check_type</td>
    <td><?php echo $orders[0]['Check_type'];?></td>
   </tr>
   <tr>
   	<td>Check_Number</td>
    <td><?php echo $orders[0]['Check_Number'];?></td>
   </tr>
   <tr>
   <td>TotalPrice</td>
   <td><?php echo $orders[0]['TotalPrice'];?></td>
   </tr>
   
   </table>
   <?php //print_r($orders); ?>
   
</div>
</body>
</html>
