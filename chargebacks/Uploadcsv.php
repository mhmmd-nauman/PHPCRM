<?php
include $_SERVER['DOCUMENT_ROOT']."/lib/include.php";
$objClient = new Clients();

if(isset($_POST['Task']) and $_POST['Task'] == "Updatepdfstatus"){
	extract($_POST);
	$objClient = new Clients();
	# Lets update the status of the pdf in the chargebacks.
	$up = $objClient->UpdateChargebacks(" ID = '".$ID."'", array("Status" => $Value));
	$m = ($up == 1) ? "Success" : "Error";
	echo $m;
	exit;
}
$getAllfiles = $objClient->FetchChargebackdetails();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Generate Agreement PDF</title>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<link rel="stylesheet" href="../css/styles.css"  />
</head>

<body>
<?php
if(isset($_GET['Message']) and !empty($_GET['Message'])){
	echo "<div class='".$_GET['message_class']."' style='width:96%;'>".$_GET['Message']."</div>";
}

?>
<div class="subcontainer">
    <form action="agreement.php" enctype="multipart/form-data" method="post">
    	<input name="Task" type="hidden" value="UploadCSV">
        <table width="50%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>Upload CSV:</td>
                <td>&nbsp;</td>
                <td><input type="file" name="csvuploaded"></td>
            </tr>
            <tr>           
                <td><input type="submit" name="uploadsubmit" value="Upload" /></td>
            </tr>
        </table>
    </form>
</div>
<div class="subcontainer">
<h3>Create a single pdf.</h3>
	<form action="agreement.php" method="post">
    	<input name="Task" type="hidden" value="CreteSinglescv">
        <table width="50%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>Name:</td>
                <td>&nbsp;</td>
                <td><input type="text" name="nametocreatepdf" value="" placeholder="Full Name" class="inputsapp" required /></td>
            </tr>
            <tr>
            	<td>Date:</td>
                <td>&nbsp;</td>
                <td><input type="text" name="datetocreatepdf" value="" placeholder="MM/DD/YYYY"  class="inputsapp" required /></td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
                <td>&nbsp;</td>
            	<td><input type="submit" name="createsingle" value="Print to PDF"></td>
            </tr>
        </table>
    </form>
</div>
<div class="subcontainer">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Order_List">
    <thead>
        <tr id="headerbar">
        	<td>S. No.</td>
            <td>Entered Date</td>
            <td>Created By</td>
            <td>File</td>
            <td>Status</td>
        </tr>
    </thead>
    <tbody> 
    <?php
    if(is_array($getAllfiles)){
		$flag = 0;
		$sr = 1;
		foreach((array)$getAllfiles as $eachfile){
			if($flag == 0){
				$flag = 1;
				$row_class = "row-white";
			}else{
				$flag = 0;
				$row_class = "row-tan";
			}
			$AgentID = trim($eachfile['uploadedby']);
			$GetName = $objClient->FetchAgentName($AgentID);
			?>
				<tr id="<?php echo $row_class; ?>">
                	<td><?php echo $sr; ?></td>
					<td><?php echo date("<b>M d</b>, Y",strtotime($eachfile['Created'])); ?></td>
					<td><?php echo $GetName; ?></td>
					<td><?php echo "<a target='_blank' href='agreements/".$eachfile['pdfname']."'>".$eachfile['pdfname']."</a>"; ?></td>
					<td><input type="checkbox" <?php if($eachfile['Status'] == 1) echo "checked"; ?> onclick="updatepdfstatus(this,'<?php echo $eachfile['ID']; ?>');"></td>
				</tr>
			<?php
			$sr++;
		}
	}else{
	?>
		<tr id="headerbar">
        	<td colspan="5" align="center">No PDF files.</td>
        </tr>
	<?php	
	}
	?>    	
   </tbody>
</table>
</div>
</body>
</html>
<script type="text/javascript">
function updatepdfstatus(point,ID){
	var check = ($(point).is(":checked")) ? 1 : 0;
	$.ajax({
		url  : 'Uploadcsv.php',
		data : {'Task' : 'Updatepdfstatus', 'ID': ID, 'Value': check},
		type : 'POST',
		success : function(data){
			alert(data);
		}
	});
}
</script>