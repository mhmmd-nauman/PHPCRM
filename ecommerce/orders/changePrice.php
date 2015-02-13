<html>

<head>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script>
$(document).ready(function(){
	$("#prodID").on("change", function(){
		$(".priceSelect").hide().attr("disabled", "disabled");
		var val = $(this).val();
		$("#price_" + val).show().removeAttr("disabled");
	});
	$("#prodID").change();
	$("#subby").click(function(){
		var value = $("#prodID option[value="+ $("#prodID").val() + "]").html();
		$("#changeprice").append('<input type="hidden" name="prodName" value="' + value + '" />');
		$("#changeprice").submit();
	});
});

</script>
</head>
<body>
<form name="changeprice" id="changeprice" action="#">

<?php
include "../../lib/include.php";

if(isset($_REQUEST['prodID'])){
	// form success
	$orderID = $_REQUEST['orderID'];
	$objOrder = new Orders();
	$objClient = new Clients();
	$arr = array(
		"ProductID" => $_REQUEST['prodID'],
		"ProductPrice" => $_REQUEST['prodPrice'],
		"ProductName" => $_REQUEST['prodName']
	);
	$last = $objOrder->UpdateOrderDetail("OrderID = '" . $orderID . "'", $arr);
	echo "<h3>Updated record for Order #" . $orderID . "</h3>";
	
	$arr2 = array(
		"TotalPrice"=>$_REQUEST['prodPrice']
	);
	$one = $objOrder->UpdateOrderItem("ID = '" . $orderID . "'", $arr2);
	
} else {
	// show form
	if(isset($_REQUEST['order'])){
		$orderID = $_REQUEST['order'];
	} else {
		$orderID = "";	
	}
	echo "<input type='hidden' name='orderID' value='" . $orderID . "' />";
	
	$objproducts = new Products();
	$list = $objproducts->GetAllProduct("1", array("*"));
	echo "<select name='prodID' id='prodID'>";
	foreach($list as $prod){
		echo "<option value='" . $prod['ID'] . "'>" . $prod['ProductName'] . "</option>";
	}
	echo "</select><br />";
	
	foreach($list as $product){
		//echo $product['ProductName'] . ":  ";
		echo "<select class='priceSelect' name='prodPrice' disabled id='price_" . $product['ID'] . "'>";	
		$prices = $objproducts->GetAllProductPrice(" ProductID = '" . $product['ID'] . "'",array("*"));
		foreach($prices as $price){
			echo "<option value='" . $price['ProductPrice'] . "'";
			if($price['DefaultPrice'] == "1"){
				echo " selected";	
			}
			echo ">" . $price['ProductPrice'] .  "</option>";
		}
		echo "</select>";
	}
	?>
	<br /><br />
	<a href="javascript:;" id="subby">Update Order</a>
	</form>

<?php } ?>
</body>
</html>
