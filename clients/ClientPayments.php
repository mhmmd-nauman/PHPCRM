<?php
include "../lib/include.php";
$payment = new OrderForm();

$amount = $_REQUEST['amt'];
$clientID = $_REQUEST['id'];
$date = $_REQUEST['date'];
$notes = $_REQUEST['notes'];
$task = $_REQUEST['task'];

/*
$arr = array($date, $amount, $clientID, $notes);
$return = $payment->InsertPayment($arr);
echo $return;
*/

if($task=="add"){
	$sql1 = "INSERT INTO `Payments` (Date, Amount, ClientID, Notes) VALUES('".$date."','".$amount."','".$clientID."','".$notes."')"; 
	mysqli_query($link, $sql1);
	$last_inserted_id = mysqli_insert_id($link);
	echo $last_inserted_id;
	
	$result = "SELECT * FROM `Payments`  WHERE ID='". $last_inserted_id ."'";
	$table = mysqli_query($link, $result);
	while($row = mysqli_fetch_array($table)){
	echo "<tr><td><a href='javascript:update(". $row['ID']. ",\"delete\");' id='del_" . $row['ID'] . "'>x</a></td><td>" . $row['ID'] . "</td><td>" . $row['Date'] . "</td><td>" . $row['Notes'] . "</td><td align='right' class='last'>" . $row['Amount'] . "</td></tr>";
	}
} else {
	
	if(isset($_REQUEST['rowID'])){
		$rowID = $_REQUEST['rowID'];
	}
	$result = "DELETE FROM `Payments`  WHERE ID='". $rowID  ."'";
	$table = mysqli_query($link, $result);
	echo "deleted " .  $rowID;
}
?>