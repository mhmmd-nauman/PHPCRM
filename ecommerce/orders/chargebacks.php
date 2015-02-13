<?php
include "../../include/header.php"; 
$objorder = new Orders();
$Order_Array = $objorder->GetAllOrder("1",array("*"));

?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
<script type='text/javascript' src="<?php echo SITE_ADDRESS;?>js/jquery-multiselect.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery.multiselect.css" />
<div id="headtitle">Orders</div>
<div class="filtercontainer">
&nbsp;<br />
</div>
<div style="clear:both; margin-top: 40px;"></div>

<div class="subcontainer">

<style>
table textarea {width: 92%;}
.subby {width: 150px; display: inline-block; border: 1px solid #aaa; background: #eee; padding: 10px; position: relative; top:-3px; border-radius:4px; font-size:18px; }
h1.headers {display:inline-block;}

</style>
<link rel="stylesheet" href="https://datatables.net/release-datatables/extensions/TableTools/css/dataTables.tableTools.css">
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/dataTables/media/js/jquery.dataTables.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />


<script>
function saveVals(ID, arrayVal, val) {
	
	$.ajax({
		url: '<?php echo SITE_ADDRESS;?>ecommerce/orders/chargeBackAjax.php',
		data: {'OrderID': ID, 'arrVal': arrayVal, 'val': val},
		success: function(data, ID){
			console.log(ID + " | " +data);
			if(data=="1"){
				/*
				if(arrayVal=="ChargebackNotes"){
					$("#row_"+ID).find("textarea").css({border: "1px solid green"}).animate({border: "0px"}, 1000);	
				}
				*/
			}
		},
		error: function(){
			alert("did not save");
		}
	});
	
	
}
function saveRow(id){
	var $node = $("row_"+id);	
	var inputVal = $node.find("input").val();
	var textVal  = $node.find("textarea").val();
	saveVals(id, "Chargeback", inputVal);
	saveVals(id, "ChargebackNotes", textVal);
}

$(document).ready(function(){
var oTable = $('#Order_Lists').dataTable({
	"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
	"iDisplayLength": 10,	
});


$("#Order_List label").on("click", function(){
	var val = $(this).find("input").val();
	var order = $(this).parent().parent().attr("id");
	order = order.substring(4, order.length);
	//alert(order);
	saveVals(order, "Chargeback", val);
	
});
$("#Order_List textarea").on("blur", function(){
	var val = $(this).val();
	var order = $(this).parent().parent().attr("id");
	order = order.substring(4, order.length);
	//alert(order);
	saveVals(order, "ChargebackNotes", val);
});

});
</script>

<form action="#" method="POST">

<h1 class="headers" style="display: inline; float: left;">Chargeback orders:</h1>
<a href="chargebacksReport.php" style="float: right">see report</a>
<!--<input type="submit" name="storeVals" value="Save" class="subby ui ui-btn" />-->
<input type="hidden" name="rows"  value="<?php echo $count; ?>" />
<div style="clear: both"></div>
<table cellpadding=0 cellspacing=0 width="100%" id="Order_List">
<thead>
<tr id="headerbar">
<td>Order ID</td>
<td>Order Date</td>
<td>Chargeback Status Date</td>
<td>Customer</td>
<td>Company Name</td>
<td>State</td>
<td>Notes</td>
<td>Save</td>
</tr>
</thead>
<tbody>
<?php	
$count = 1;
foreach($Order_Array as $order){
	if($order['Status']=="ChargeBack"){
		$id= $order['ID'];
		if(isset($_REQUEST['storeVals'])){
			
			///print_r($array);
			$charge = "results" . $id;
			$notes = "notes" . $id;
			$array = array(
				'Chargeback'=>$_REQUEST[$charge],
				'ChargebackNotes'=>$_REQUEST[$notes]
			);
			//echo $_REQUEST[$charge] . " | " . $_REQUEST[$notes] .  "<br />";
			$where = 'ID= "' . $id . '"';
			$objorder->updateOrderFields($where, $array);
			
			$chargeback = $_REQUEST[$charge];
			$chargebackNotes = $_REQUEST[$notes];
		} else {
			$chargeback = $order['Chargeback'];
			$chargebackNotes = $order['ChargebackNotes'];
		}
		
		echo "<tr id='row_" . $order['ID'] . "'><td><a href='/ecommerce/orders/EcClientsOrderDetail.php?id=" . $order['ID'] . "&Task=configration' target='_blank'>" .  $order['ID'] . "</a></td><td>". date("m-d-Y",strtotime($order['Created'])). "</td>";
		echo "<td>";
		if($order['ManualPaymentDate']!="0000-00-00"){
			echo date("d-m-Y", strtotime($order['ManualPaymentDate']));
		}
		echo "</td><td>";
		echo $order['FirstName'] . " " . $order['Surname'];
		echo "</td><td><a href='/clients/ClientsEdit.php?id=1954' target='_blank'>";
		echo $order['CompanyName'];
		echo "</a></td><td nowrap>";
		echo "<label><input type='radio' name='results$id' value='1' ";
		if($chargeback=="1"){
			echo " checked";	
		}
		echo " />Won</label>";
		echo "<label><input type='radio' name='results$id' value='2' ";
		if($chargeback=="2"){
			echo " checked";	
		}
		echo "/>Lost</label>";
		echo "<label><input type='radio' name='results$id' value='null' ";
		if($chargeback=="null" ||empty($chargeback)){
			echo " checked";
		}
		echo "/>Pending</label>";
		echo "</td><td><textarea name='notes$id'>".$chargebackNotes."</textarea></td>";
		
		echo "<td><a href='javascript:saveRow(\"$id\");'>Save</a></td></tr>";
		$count++;
		
		
		
		
		
	}
	
}
?>
</tbody>
</table>

</form>
<br /><br /><br />
<div style="clear:both; margin-top: 40px;"></div>
</div>



<?php include "../../include/footer.php" ?>
</body>
</html>