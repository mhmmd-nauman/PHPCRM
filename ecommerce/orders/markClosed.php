<?php 
include "../../lib/include.php";
$objorder = new Orders();

$arr = array(
	"Status" => "Closed"
);	
$id = $objorder->updateOrderFields("ID = '".  $_REQUEST['id'] . "'",  $arr);
echo $id;
?>