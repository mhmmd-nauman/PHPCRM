<?php
//$utilOjb = new util();
switch($_REQUEST['Task']){
case "Generate":
	$gen_val = $_REQUEST['Listfields'];
	//$gen_val = str_replace("fullname","FirstName,Surname",$gen_val);
	//$genVal = str_replace(",",",Member.",$gen_val);
	$exp_arr = explode(",",$gen_val);
		
class ExcelGenerater
	{
		function generate_csv($filename, $columnnames)
				{
				$fh = fopen($filename, 'w') or die("error creating file");
 				fputcsv($fh, $columnnames);
				return $fh;
				}
		function insert_csv($fh, $columnnames)
				{
  				fputcsv($fh, $columnnames);
				}
}
$obj = new ExcelGenerater();	
$objpdo = new DB();	
$filename = "../../excel/MemberSubscriptionReport.csv";
$count = count($exp_arr);
$fh = $obj->generate_csv($filename,$exp_arr);
$iTotal = $tPaid = 0;
$bCycle = array('1'=>'Yearly','2'=>'Monthly','3'=>'Weekly','6'=>'Daily');
$aCharge = array('1'=>'On','0'=>'Off');

foreach((array) $Invoice_data as $res){
			if(in_array('Invoice ID', $exp_arr)){
				$list[] = $res['InvoiceID'];
			}
			if(in_array('Invoice Created', $exp_arr)){
				$list[] = $res['DateCreated'];
			}
			
			if(in_array('Groups', $exp_arr)){
			$grps = "";
			$mem_grp = "SELECT * FROM Group_Members WHERE MemberID = '".$res['mid']."'";
			$mem_res = $objpdo->fetch($mem_grp);
			foreach((array)$mem_res as $grp){
				if($grp['GroupID']==4){
					$grps .= 'mos platinum,';
				}elseif($grp['GroupID']==22){
					$grps .= 'mos gold,';				
				}elseif($grp['GroupID']==21){
					$grps .= 'appkit,';
				}
			}
			$grps = trim($grps,',');
				$list[] = $grps;
			}
			
			if(in_array('Member Name', $exp_arr)){
				$list[] = ucwords(strtolower(trim($res['fn']." ". $res['sn'])));
			}
			if(in_array('Invoice Desc', $exp_arr)){
				$list[] = $res['Description'];
			}
			if(in_array('Invoice Total', $exp_arr)){
				$list[] = '$'.number_format($res['InvoiceTotal']); 
				$iTotal = $iTotal + $res['InvoiceTotal'];
			}
			if(in_array('Amt Paid', $exp_arr)){
				$list[] = '$'.number_format($res['TotalPaid']); 
				$tPaid = $tPaid + $res['TotalPaid'];
			}
			if(in_array('Remaining Bal', $exp_arr)){
				$list[] = "$".number_format($tBalance = (int)$res['InvoiceTotal']-(int)$res['TotalPaid']);
			}				
			if(in_array('Pay Status', $exp_arr)){
				$list[] = ($res['PayStatus'] == 1) ? 'Payment Success'  : 'Payment Fail';
			}	
								
}
//FOR TOTAL DISPLAY
					
$list=array_chunk($list,$count);
if(is_array($list)){			
	foreach ($list as $fields) {
	$obj->insert_csv($fh,$fields); 
	}
}
fclose($fh);
echo "done";		
	exit;
	break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Export Excel Sheet</title>
<link rel="stylesheet" type="text/css" href="../../../co_op/css/styles.css">
<script type="text/javascript" src="../../../../javascript/jquery.min.js"></script>
<style style="text/css">
.MOSGLsmLink {
	float:left;
	background:url("../../images/mos-small-button.png") repeat-x;
	text-align:center;
	border-radius:8px;
	height:24px;
	border:#0f4c8d 1px solid;
	font-family:Arial, Helvetica, sans-serif;
	color:#FFFFFF;
	padding:3px 15px 0 15px;
	font-size:18px;
	text-decoration:none
}
.MOSGLsmButton {
	float:left;
	background:url("../../images/mos-small-button.png") repeat-x #0f4c8d;
	text-align:center;
	border-radius:8px;
	height:30px;
	border:#0f4c8d 1px solid;
	font-family:Arial, Helvetica, sans-serif;
	color:#FFFFFF;
	padding:0px 7px 4px 6px;
	font-size:18px;
	text-decoration:none;
	cursor:pointer;
	outline:0 none;
	margin-left:10px;
	margin-bottom:6px;
}
</style>
</head>
<body >
<div id="name">Export Excel Sheet</div>
<div>
  <table cellpadding="0" cellspacing="0" width="250" >
    <tr>
      <td colspan="2" height="5px"></td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="All" value="All" checked="checked" id="SelectAll1" class="MemberSelectcheckBox1" /></td>
      <td><strong>Field Name</strong></td>
    </tr>
    <tr>
      <td colspan="2" height="5px"></td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Invoice ID" id="Invoice ID" class="MemberSelectcheckBox1" /></td>
      <td>Invoice ID</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Invoice Created" id="Invoice Created" class="MemberSelectcheckBox1" /></td>
      <td>Invoice Created</td>
    </tr>
     <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Groups" id="Groups" class="MemberSelectcheckBox1" /></td>
      <td>Groups</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Member Name" id="Member Name" class="MemberSelectcheckBox1" /></td>
      <td>Member Name</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Invoice Desc" id="Invoice Desc" class="MemberSelectcheckBox1" /></td>
      <td>Invoice Desc</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Invoice Total" id="Invoice Total" class="MemberSelectcheckBox1" /></td>
      <td>Invoice Total</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Amt Paid" id="Amt Paid" class="MemberSelectcheckBox1" /></td>
      <td>Amt Paid</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Remaining Bal" id="Remaining Bal" class="MemberSelectcheckBox1" /></td>
      <td>Remaining Bal</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Pay Status" id="Pay Status" class="MemberSelectcheckBox1" /></td>
      <td>Pay Status</td>
    </tr>
    
    <tr>
      <td colspan="2" height="5px"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="button" class="MOSGLsmButton" name="Download" id="download" value="Export &amp; Download" title="Click to download here"></td>
    </tr>
  </table>
</div>
<script type="text/javascript">
	$("#SelectAll1").click(function(){
		if($(this).is(':checked')){
			$(".MemberSelectcheckBox1").attr('checked','checked');
		}else{
			$(".MemberSelectcheckBox1").removeAttr('checked');
		}
	});
	
	$("#download").click(function(){
		var ListChecked = [];
		$("input[name='Listfield[]']:checked").each(function (){
			ListChecked.push($(this).val());
		});
		var ListChecks = ListChecked;
		//alert(ListChecks);
		$.post("subscriptions.php?export=1",{Task:"Generate", Listfields:''+ListChecks+''}, function(data){
			if(data == "done"){
				parent.window.open('http://themillionaireos.com/admintti/excel/MemberSubscriptionReport.csv'); ///"MOSMembers.csv";
				fnclose();
			}	
		});
	});
	
function fnclose(){
 $.fancybox.close();	
}
</script>
</body>
</html>
