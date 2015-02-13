<?php
//$utilOjb = new util();

switch($_REQUEST['Task']){

case "Generate":

	$gen_val = $_REQUEST['Listfields'];

	//$gen_val = str_replace("fullname","FirstName,Surname",$gen_val);

	//$genVal = str_replace(",",",Member.",$gen_val);

	$exp_arr = explode(",",$gen_val);
	
	//print_r($exp_arr);
	//exit;
	
	
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

$filename = "../../excel/Excel_CoachingWeeklyCommission.csv";

 $count = count($exp_arr);
//exit;

$fh = $obj->generate_csv($filename,$exp_arr);

$total_member = 0;
$total_mp = 0; 
$total_m1 = 0;
$total_m2 = 0;
$total_m3 = 0;
$total_wmi = 0;
$total_FBGmember = 0;
$total_APPmember = 0;


//print_r($CommissionRows);

foreach ($CommissionRows as $CommRowsVal){
			
			if(in_array('ID', $exp_arr)){
				$list[] =$CommRowsVal['SponsorID'];
				
			}
			
			if(in_array('CoachType', $exp_arr)){
			
				$Coach_type = $objmember->GetAllMemberWithGroup("MemberID = '".$CommRowsVal['SponsorID']."' ORDER BY Group_Members.GroupID",array("*"));
				$coach_type_array = array("0"=>'10',"1"=>'18',"2"=>'9');
				$type_value_S = '';
				$type_value_R = '';
				$type_value_C = '';
				$Stitle = '';
				$Rtitle = '';
				$Ctitle = '';
	
			foreach((array)$Coach_type as $type){
				if(in_array($type['GroupID'],$coach_type_array)){
					
					if($type['GroupID'] == '10'){
						$type_value_S = "S";
						$Stitle = 'Success Coach';
					}
					
					if($type['GroupID'] == '18'){
						$type_value_R = "R";
						$Rtitle = 'Referring Coach';
					}
					
					if($type['GroupID'] == '9'){
						$type_value_C = "C";
						$Ctitle = 'Call Center';
					}
			   }	
				
				}
		
			  $coach_type_SR = $type_value_S."-".$type_value_R."-".$type_value_C;
			  $coach_type_value = trim($coach_type_SR,"-");
	
				$list[] = $coach_type_value;
			}
			
			if(in_array('Coach', $exp_arr)){
			   $CoachWhere="ID='".$CommRowsVal['SponsorID']."'";
	           $Coachdetails=$utilObj->getSingleRow('Member' , $CoachWhere);
			
				$list[] = $Coachdetails['FirstName'].' '.$Coachdetails['Surname'];
			}
			
		   if(in_array('ProgramName', $exp_arr)){
			   $commWhere="ID='".$CommRowsVal['CommissionProgramID']."'";
	           $CommProgramdetails=$utilObj->getSingleRow('CommissionProgram' , $commWhere);
			   $list[] = $CommProgramdetails['CommissionName'];
			
			}
			
			if(in_array('TotalMember', $exp_arr)){
			    $list[] = $CommRowsVal['TotalSale'];
				$total_member = $total_member + $CommRowsVal['TotalSale'];
			}
			
			
			if(in_array('TotalSaleAmt', $exp_arr)){
				$list[] = $CommRowsVal['TotalSaleAmt'];
				$total_Sale = $total_Sale + $CommRowsVal['TotalSaleAmt'];
			}
			
			if(in_array('Percentage', $exp_arr)){
				$list[] = ($CommRowsVal['Comission']/$CommRowsVal['TotalSaleAmt']*100);
			}
			
			if(in_array('Commission', $exp_arr)){
				$list[] = $CommRowsVal['Comission'];
				$total_Commission = $total_Commission + $CommRowsVal['Comission'];
			}
			
			if(in_array('Staus', $exp_arr)){
				$list[] =  $CommRowsVal['PaymentStatus'];
				/*$total_m1 = $total_m1 + $C_record['m1_counts'];*/
			}
			
			
		/*	if(in_array('WMIM2Sales', $exp_arr)){
				$list[] = $C_record['m2_counts'];
				$total_m2 = $total_m2 + $C_record['m2_counts'];
			}
			
			if(in_array('WMIM3Sales', $exp_arr)){
				$list[] = $C_record['m3_counts'];
				$total_m3 = $total_m3 + $C_record['m3_counts'];
			}
			
			if(in_array('TotalSale', $exp_arr)){
				$list[] = $C_record['t_count'];
				$total_wmi = $total_wmi + $C_record['t_count'];
			}*/
	
		
}

/*echo "<pre/>";
print_r($list);*/

//FOR TOTAL DISPLAY

			if(in_array('ID', $exp_arr)){$list[] ="";}
			
			if(in_array('CoachType', $exp_arr)){$list[] = "";}
			
			if(in_array('Coach', $exp_arr)){$list[] = "";}
			
			if(in_array('ProgramName', $exp_arr)){$list[] ="Total:";}
			
			if(in_array('TotalMember', $exp_arr)){$list[] =$total_member;}
		
			if(in_array('TotalSaleAmt', $exp_arr)){$list[] =$total_Sale;}
			
			if(in_array('Percentage', $exp_arr)){$list[] ="";}
			
			if(in_array('Commission', $exp_arr)){$list[] =$total_Commission;}
			
			if(in_array('Staus', $exp_arr)){$list[] = "";}
			
			/*if(in_array('WMIM2Sales', $exp_arr)){$list[] = $total_m2;}
			
			if(in_array('WMIM3Sales', $exp_arr)){$list[] = $total_m3;}
			
			if(in_array('TotalSale', $exp_arr)){$list[] = $total_wmi;}*/
			

				
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
<link rel="stylesheet" type="text/css" href="http://themillionaireos.com/admintti/co_op/css/styles.css">
<script type="text/javascript" src="http://themillionaireos.com/admintti/javascript/jquery.min.js"></script>
<style style="text/css">
.MOSGLsmLink {
	float:left;
	background:url("images/mos-small-button.png") repeat-x;
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
	background:url("images/mos-small-button.png") repeat-x #0f4c8d;
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
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="CoachType" id="coach_type" class="MemberSelectcheckBox1" /></td>
      <td>Coach Type</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Coach" id="coach" class="MemberSelectcheckBox1" /></td>
      <td>Coach</td>
    </tr>
    
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="ProgramName" id="m1sale" class="MemberSelectcheckBox1" /></td>
      <td>Program Name</td>
    </tr>
    
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="TotalMember" id="mosmembers" class="MemberSelectcheckBox1" /></td>
      <td>Total Members</td>
    </tr>
  
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="TotalSaleAmt" id="m2sale" class="MemberSelectcheckBox1" /></td>
      <td>Total Sale Amt</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Percentage" id="m3sale" class="MemberSelectcheckBox1" /></td>
      <td>Percentage</td>
    </tr>
    <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Commission" id="total_sale" class="MemberSelectcheckBox1" /></td>
      <td>Commission</td>
    </tr>
      <tr>
      <td align="center"><input type="checkbox" name="Listfield[]" checked="checked" value="Staus" id="total_sale" class="MemberSelectcheckBox1" /></td>
      <td>Staus</td>
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

		$.post("WeeklyCoachingCommission.php?export=1",{Task:"Generate", Listfields:''+ListChecks+''}, function(data){

			if(data == "done"){

				parent.window.open('http://themillionaireos.com/admintti/excel/Excel_CoachingWeeklyCommission.csv'); ///"MOSMembers.csv";

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
