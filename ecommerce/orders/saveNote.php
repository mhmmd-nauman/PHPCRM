<?php 
include "../../lib/outer_include.php";
$OrderID = $_REQUEST['order'];
	  $status = mysqli_query($link,"update `OrderDetail` set `Description` = '". $_REQUEST['extraNotes'] . "' where OrderID = '$OrderID' ");
echo $_REQUEST['extraNotes'];
?>