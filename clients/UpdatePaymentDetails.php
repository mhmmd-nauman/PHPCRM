<?php
global $link;

if(!empty($Transaction) and $Transaction == "Successfull"){
	$CardCharged = 1;
	$Paid = "Paid";
}else{
	$CardCharged = 0;
	$Paid = "Unpaid";
}
if(empty($CardCharged)){
	$CardCharged = 0;
}

# This will run only in the case when we are collecting the payment later than the order was made
# From the Collect payment later pop up which is there in the Order and Subscritption list when the
# status is unpaid.
if(empty($inserted_order)){
	$inserted_order = $OrderID;
}

# Using Plain Query to Update the details of the OrderItem tale
# Needs to be fixed fast asap So did like this
# For Some reasong the Object call was not working So I have used the simple mysql query.
# $inserted_order = $objorder->updateorderdetails($Paid,$CardCharged,$inserted_order);
//if(!empty($inserted_order)){
	mysqli_query($link,"UPDATE `OrderItem` SET `Status` = 'Paid', `CardCharged` = '1' WHERE `ID` = '$inserted_order' ");
//}

?>