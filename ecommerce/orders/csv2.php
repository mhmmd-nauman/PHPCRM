<?php

$file = 'file.csv';
 	header("Content-Disposition: attachment; filename=" . urlencode($file));   
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Description: File Transfer");            
	header("Content-Length: " . filesize($file));

	
@session_start();
require_once "../../dbcon.php";
require_once "../../lib/classes/config/variables.php";
require_once "../../lib/classes/util_objects/util.php";
require_once "../../lib/classes/business_objects/Orders.php";
$objorder = new Orders();
//print_r($_REQUEST);
if($_REQUEST['Task']=='ExportOrders')
{

//$where  =$_SESSION['download_file'];
//$where="ClassName=OrderItem";
//$row = $objorder->GetAllOrder($where,array("*"));

$Search = '';

$searchText = $_REQUEST['MemSearchText'];
$_REQUEST['payLater'];
$_REQUEST['OLFromDate'] = date("m/d/Y",strtotime("-1 Week"));
$_REQUEST['OLToDate'] = date("m/d/Y");

# Search Filter For text starts here

if($searchText!=""){
	if(!empty($Search)){
		$Search .= " AND ".ECORDERITEM.".CompanyName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".FirstName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".SurName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".Email LIKE '%".trim($_SESSION['MemSearchText'])."%' ";
	}else{
		$Search .= " ".ECORDERITEM.".CompanyName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".FirstName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".SurName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".Email LIKE '%".trim($_SESSION['MemSearchText'])."%' ";
	}
}
# Search filter for text search ends here

# Date Search starts here
if(!empty($Search)){
	$Search .= " AND ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."' ";
}else{
	$Search .= " ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."' ";
}
# Date Search ends Here


#pay later search starts
if(!empty($_SESSION['payLater'])){
	if($_SESSION['payLater']=="1"){
		if(!empty($Search)){
			$Search .= " AND ".ECORDERITEM. ".Status='Unpaid' ";
		}else{
			$Search .= " ".ECORDERITEM.".Status='Unpaid' ";
		}
	}
}
#pay later search ends

# Company Wise search starts here
if(!empty($_SESSION['CompanyName'])){
	if(!empty($Search)){
		$Search .= " AND Company.ID = '".$_SESSION['CompanyName']."'";
	}else{
		$Search = " Company.`ID` = '".$_SESSION['CompanyName']."' ";
	}
}
# Company Wise search ends here
if($_REQUEST['Task'] == 'SetFilter'){

# Agent Wise search starts here
	if(!empty($_POST['select_agent'])){
		$all_agents = $for_dropdown_preselected_agents = "";
		$len = count($_POST['select_agent']);
		$i = 0;
		foreach((array)$_POST['select_agent'] as $agents){
			if($i == $len - 1){
				$all_agents .= "'".$agents."'";
				$for_dropdown_preselected_agents .= $agents;
			}else{
				$all_agents .= "'".$agents."',";
				$for_dropdown_preselected_agents .= $agents.",";
			}
			$i++;
		}

		if(!empty($search)){
			$Search .= " AND Users.`ID` IN ($all_agents)";
			
		}else{
			$Search = " Users.`ID` IN ($all_agents)";
		}
	   
	}
}

#

if($Search == ''){
	$Search = ' 1 ';
}

$Search .= " ORDER BY ".ECORDERITEM.".`Created` DESC";

$ClientOrdersRows = $objorder->GetAllOrderWithCompanyMatch($Search, array(ECORDERITEM.".*"));


$sql1 = "select * from `OrderItem` WHERE" . $Search;
$result = mysqli_query($link,$sql1);
while($row = mysqli_fetch_array($result)){
	$popular[] = $row;
	
}

$fp = fopen('file.csv', 'w');

$flag = false;
//echo "clients order rows:<br />";
//var_dump($ClientOrdersRows);
//echo "<hr /><table>";
//var_dump($popular);
   //$result = $orderDetails; 
  foreach((array)$popular as $val) {
  
     $arr =array('FirstName'=>$val['FirstName'],'Surname'=>$val['Surname'],'Email'=>$val['Email'],'Phone'=>$val['Phone'],'StreetAddress1'=>$val['StreetAddress1'],'StreetAddress2'=>$val['StreetAddress2'],'Price'=>$val['TotalPrice'],'Date'=>$val['Created']);

		//echo "<tr><td>" . $val['FirstName'] . "</td><td> " . $val['Surname'] . "</td><td> " . $val['Status'] . " </td></tr>";
     
	 if(!$flag) { 
       // display field/column names as first row 
       fputcsv($fp, array_keys($arr), ',', '"');
       $flag = true; 
     } 
	

     fputcsv($fp, array_values($arr), ',', '"'); 
   } 
//echo "</table>";

	flush(); # This doesn't really matter.
	$fp = fopen($file, "r");
	while (!feof($fp))
	{
		echo fread($fp, 65536);
		flush(); # this is essential for large downloads
	}
	
fclose($fp);
exit;
}

?>