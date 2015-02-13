<?php
include "../../lib/outer_include.php";
$objorder = new Orders();
$id = $_REQUEST['OrderID'];
//print_r($_REQUEST);

			if($_REQUEST['arrVal']=="Chargeback"){
				$chargeback  = $_REQUEST['val'];

				
				$array = array(
				'Chargeback'=>$chargeback
				);
			} else {

				$chargebackNotes = $_REQUEST['val'];
				$array = array(
				'ChargebackNotes'=>$chargebackNotes
				);
			}	
			
			//echo $_REQUEST[$charge] . " | " . $_REQUEST[$notes] .  "<br />";
			$where = 'ID= "' . $id . '"';
			$update = $objorder->updateOrderFields($where, $array);
			echo $update;

?>
