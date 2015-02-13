<?php
include "../../lib/outer_include.php";
//$objClient = new Clients();
//$objOrder = new Orders();
if ($_FILES["file"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
} else {
    //echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    //echo "Type: " . $_FILES["file"]["type"] . "<br>";
    //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
	//$_REQUEST['agent']."-"
	
	$invoice_no = $_REQUEST['id']."-".$_REQUEST['order'] . ".pdf";
    if (file_exists("../../agreements/attachments/" . $invoice_no)) {
      echo $invoice_no . " already exists. ";
    } else {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "../../agreements/attachments/" . $invoice_no);
	  
	  $OrderID = $_REQUEST['order'];
	  $status = mysqli_query($link,"update `OrderDetail` set `Verified` = '1' where OrderID = '$OrderID' ");
	  
	  
      echo  "saved to agreements/attachments/" . $invoice_no;
	  //echo "<script>parent.location.reload();</script>";
    }
}

?>