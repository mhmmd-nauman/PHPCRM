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
$filename = "../../excel/MemberRecurringReport.csv";
$count = count($exp_arr);
$fh = $obj->generate_csv($filename,$exp_arr);
$tbAmt = 0;
$bCycle = array('1'=>'Yearly','2'=>'Monthly','3'=>'Weekly','6'=>'Daily');
$aCharge = array('1'=>'On','0'=>'Off');

foreach((array) $Recc_data as $res){
			if(in_array('ID', $exp_arr)){
				$list[] = $res['mid'];
			}
			if(in_array('Joined', $exp_arr)){
				$list[] = $res['joind'];
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
			if(in_array('Rec Products', $exp_arr)){
				$tp = "";
				if($res['TotalRec']>0){$tp = "+".$res['TotalRec'];}
				$list[] = $res['lastproduct']." ".$tp;
			}
			if(in_array('Start Date', $exp_arr)){
				$list[] = $res['StartDate'];
			}
			if(in_array('End Date', $exp_arr)){
				$list[] = $res['EndDate'];
			}
			if(in_array('Last Bill Date', $exp_arr)){
				$list[] = $res['LastBillDate'];
			}				
			if(in_array('Next Bill Date', $exp_arr)){
				$list[] = $res['NextBillDate'];
			}	
			if(in_array('Billing Cyl', $exp_arr)){
				$list[] = $bCycle[$res['BillingCycle']];
			}	
			if(in_array('Billing Amt', $exp_arr)){
				$list[] = '$'.number_format($res['BillingAmt'],2);
				$tbAmt = (int)$res['BillingAmt'] + (int)$tbAmt;
			}		
			if(in_array('Auto Chrg', $exp_arr)){
				$list[] = $aCharge[$res['AutoCharge']]; 
			}
			if(in_array('Status', $exp_arr)){
				$list[] = $res['Status'];
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
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="ID" id="ID" class="MemberSelectcheckBox1" /></td>
      <td>ID</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Joined" id="Joined" class="MemberSelectcheckBox1" /></td>
      <td>Joined</td>
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
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Rec Products" id="Rec Products" class="MemberSelectcheckBox1" /></td>
      <td>Rec Products</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Start Date" id="Start Date" class="MemberSelectcheckBox1" /></td>
      <td>Start Date</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="End Date" id="End Date" class="MemberSelectcheckBox1" /></td>
      <td>End Date</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Last Bill Date" id="Last Bill Date" class="MemberSelectcheckBox1" /></td>
      <td>Last Bill Date</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Next Bill Date" id="Next Bill Date" class="MemberSelectcheckBox1" /></td>
      <td>Next Bill Date</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Billing Cyl" id="Billing Cyl" class="MemberSelectcheckBox1" /></td>
      <td>Billing Cyl</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Billing Amt" id="Billing Amt" class="MemberSelectcheckBox1" /></td>
      <td>Billing Amt</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Auto Chrg" id="Auto Chrg" class="MemberSelectcheckBox1" /></td>
      <td>Auto Chrg</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Status" id="Status" class="MemberSelectcheckBox1" /></td>
      <td>Status</td>
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
		$.post("recurring.php?export=1",{Task:"Generate", Listfields:''+ListChecks+''}, function(data){
			if(data == "done"){
				parent.window.open('http://themillionaireos.com/admintti/excel/MemberRecurringReport.csv'); ///"MOSMembers.csv";
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
