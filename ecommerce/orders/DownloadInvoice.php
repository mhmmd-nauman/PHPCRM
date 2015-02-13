<?php
include "../../lib/include.php";
$objorder = new Orders();
$order_invioce_array = $objorder->GetAllOrderDetailWithProduct("OrderID=".$_REQUEST['OrderID'],array("*"));
$_SESSION['orderid'] = $_REQUEST['OrderID'];
//$objClient->InvoiceMail(55);
$dir = dirname(__FILE__)."/";
ob_start();
require_once($dir.'/pdf_email/pdf.php');
$pdf_html = ob_get_contents();
ob_end_clean();

require_once($dir.'/pdf_email/dompdf/dompdf_config.inc.php');

$dompdf = new DOMPDF(); // Create new instance of dompdf
$dompdf->load_html($pdf_html); // Load the html
$dompdf->render(); // Parse the html, convert to PDF
$pdf_content = $dompdf->output(); 

$invoice_no = $order_invioce_array[0]['UserID']."-".$order_invioce_array[0]['MemberID']."-".$order_invioce_array[0]['ID'];
	
header("Content-Disposition: attachment; filename=" . urlencode($invoice_no.".pdf"));   
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Description: File Transfer");            

	
echo $pdf_content;			
exit(1);


?>