<?php
include "../../../lib/include.php";
function get_Num_orders($memberId='',$month='',$year='',$date_start='',$date_end='',$select='',$type='')
{
	$where='`Status`="Paid" ';
	if($memberId!='')
	{
		$where.=' AND `MemberID`='.$memberId;
	}
	if($month!='' && $year!='')
	{
		$where.="AND month(Created)=$month AND year(Created) =$year";
	}
	if($date_start!='' && $date_end!='')
	{
		$where.="AND `Created` >= '".date("Y-m-d",strtotime($date_start))." 00:00:00' AND `Created` <= '".date("Y-m-d",strtotime($date_end))." 23:59:59'";
	}
	
	if($type=='SUM')
	{
		$select_cond=' SUM(BillingAmount) as result ';
		$fetch_single=1;
	}
	elseif($type=='COUNT')
	{
		$select_cond=' COUNT(*) as result ';
		$fetch_single=1;
	}elseif($type=='ALL')
	{
		$select_cond=' * ';
		$fetch_single=0;
	}else
	{
		$select_cond='distinct ProductID';
		$fetch_single=0;
	}
	$query_get_orders="SELECT $select_cond FROM `OrderItem` WHERE $where";
	$result=mysql_query($query_get_orders) or die ("Error in get num orders by Query $query_get_orders ".mysql_error());
	if($fetch_single==1)
	{
		$row_result=mysql_fetch_array($result);
		return $row_result['result'];
	}else
	{
		return $result;
	}
}
function get_product_name_by_id($product_id)
{
	$query_products="SELECT `ProductName` FROM `Product` WHERE `ID`=$product_id";
	$result_product_name=mysql_query($query_products) or die("Error in get product name at query $query_products by mysql error ".mysql_error());
	$row_product_name=mysql_fetch_array($result_product_name);
	return $row_product_name['ProductName'];
	
}
function get_member_by_id($memberid,$select='')
{
	if($select=='Name')
	{
		$selct_para='`FirstName`,`Surname`';
	}
	elseif($select=='AppCode')
	{
		$selct_para=' AppCode ';
	}
	$query_member="SELECT `FirstName`,`Surname` FROM `Member` WHERE `ID`=$memberid";
	$result_member_name=mysql_query($query_member) or die("Error in get member name at query $query_member by mysql error ".mysql_error());
	$row_member_name=mysql_fetch_array($result_member_name);
	if($select=='Name')
	{
		return $row_member_name['FirstName'].' '.$row_member_name['Surname'];
	}
	elseif($select=='AppCode')
	{
		return $row_member_name['AppCode'];
	}
	
}
$result_dates=mysql_query('SELECT MIN(`Created`) as first_date , MAX(`Created`) as last_date FROM `OrderItem` WHERE 1');
	$row_dates=mysql_fetch_array($result_dates);
if(isset($_GET['month']) && isset($_GET['year']))
{
	$first_date=date("01-".$_GET['month'].'-'.$_GET['year']);
	$last_date=date("t-".$_GET['month'].'-'.$_GET['year']);
}elseif(isset($_GET['date_start']) && isset($_GET['date_end']))
{
	$first_date=$_GET['date_start'];
	$last_date=$_GET['date_end'];
}
else
{
	$first_date=$row_dates['first_date'];
	$last_date=$row_dates['last_date'];
}
	
	
	$first_date_time=strtotime($first_date);
	$last_date_time=strtotime($last_date);
	$first_year=date("Y",strtotime($first_date));
	$last_year=date("Y",strtotime($last_date));

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

if($_REQUEST['view']=='month')
{
	$exp_arr=array('Month/Year','Total Orders','Total Amount Sold','Product Names');
	$obj = new ExcelGenerater();		

$filename = "../../../excel/ExportMonthlySalesReport.csv";

$count = count($exp_arr);
//unset($rotator);
$fh = $obj->generate_csv($filename,$exp_arr);		
	
	
	$total_sale_order='';
	$total_num_order='';
    $start = $month = $first_date_time;
$end = strtotime("+1 month", $last_date_time);
while($month < $end)
{
	$j=date("m",$month);
	$i=date("Y",$month);	
			$list[] = $j.'/'.$i;
			 $list[] = $total_num_order_result=get_Num_orders('',$j,$i,'','','','COUNT'); 
			 $list[]= '$'.number_format($total_amount_order_result=get_Num_orders('',$j,$i,'','','','SUM'),2);
			 
	 		 $result_product_ids=get_Num_orders('',$j,$i,'','','','');
			 $product_name='';
	  		while($row_product_id=mysql_fetch_array($result_product_ids))
			{
				$product_name.=get_product_name_by_id($row_product_id['ProductID']).' ,';
			}
			 $list[] =$product_name;
			 
$month = strtotime("+1 month", $month);
$total_sale_order=$total_sale_order+$total_amount_order_result;
$total_num_order=$total_num_order+$total_num_order_result; 
$count1=$count1++;
}
if(in_array('Month/Year', $exp_arr)){$list[] = '';}
			if(in_array('Total Orders', $exp_arr)){$list[] = "";}
			if(in_array('Total Amount Sold', $exp_arr)){$list[] = "";}
			if(in_array('Product Names', $exp_arr)){$list[] = "";}
			
			if(in_array('Month/Year', $exp_arr)){$list[] = '';}
			if(in_array('Total Orders', $exp_arr)){$list[] = "SUM Of total order";}
			if(in_array('Total Amount Sold', $exp_arr)){$list[] = "SUM of total amount";}
			if(in_array('Product Names', $exp_arr)){$list[] = "";}
			
			if(in_array('Month/Year', $exp_arr)){$list[] = '';}
			if(in_array('Total Orders', $exp_arr)){$list[] = $total_num_order;}
			if(in_array('Total Amount Sold', $exp_arr)){$list[] = number_format($total_sale_order,2);}
			if(in_array('Product Names', $exp_arr)){$list[] = "";}
$list=array_chunk($list,$count);
if(is_array($list)){			

	foreach ($list as $fields) {

	$obj->insert_csv($fh,$fields); 

	}

}

if(fclose($fh))
{ ?>
	<script type="text/javascript">
	top.window.open('http://themillionaireos.com/admintti/excel/ExportMonthlySalesReport.csv');
	$.fancybox.close();
	</script>
<?php }

}elseif($_REQUEST['view']=='week')
{
	$exp_arr=array('Week dates','Total Orders','Total Amount Sold','Product Names');
	$obj = new ExcelGenerater();		

$filename = "../../../excel/ExportWeeklySalesReport.csv";

$count = count($exp_arr);
//unset($rotator);
$fh = $obj->generate_csv($filename,$exp_arr);		
	
	$fisrdate_time=$first_date_time;
	
$next_week_time=strtotime("+6 day",$fisrdate_time);
$lastdate_time=$last_date_time;
switch(date("D",$lastdate_time))
{
	case "Mon":{ $last_week_time=strtotime("-1 day",$lastdate_time);  break; 	}
	case "Tue":{ $last_week_time=strtotime("-2 day",$lastdate_time); break;}
	case "Wed":{ $last_week_time=strtotime("-3 day",$lastdate_time);  break;}
	case "Thu":{ $last_week_time=strtotime("-4 day",$lastdate_time);  break;}
	case "Fri":{ $last_week_time=strtotime("-5 day",$lastdate_time);  break;}
	case "Sat":{ $last_week_time=strtotime("-6 day",$lastdate_time);  break;}
	case "Sun":{ $last_week_time=$lastdate_time;  break;}
}
$j=date("d-m-Y",$fisrdate_time);
$i=date("d-m-Y",strtotime("-1 day",$next_week_time));
$total_sale_order='';
$total_num_order='';
$list[] = date("d-m-Y",$fisrdate_time).' to '.date("d-m-Y",strtotime("-1 day",$next_week_time));
$list[] = $total_num_order_result=get_Num_orders('','','',$j,$i,'','COUNT');
$list[]= '$'.number_format($total_amount_order_result=get_Num_orders('','','',$j,$i,'','SUM'),2);

$result_product_ids=$result_product_ids=get_Num_orders('','','',$j,$i,'','');
$product_name='';
while($row_product_id=mysql_fetch_array($result_product_ids))
{
$product_name.=get_product_name_by_id($row_product_id['ProductID']).' ,';
}
$list[] =$product_name;
$total_sale_order=$total_sale_order+$total_amount_order_result;
$total_num_order=$total_num_order+$total_num_order_result;
 $start = $week = $next_week_time;
$end = strtotime("+6 day", $last_week_time);
while($week < $end)
{
	$j=date("d-m-Y",$week);
	$i=date("d-m-Y",strtotime("+6 day",$week));		 
	$list[] = date("d-m-Y",$week).' to '.date("d-m-Y",strtotime("+6 day",$week));
$list[] = $total_num_order_result=get_Num_orders('','','',$j,$i,'','COUNT');
$list[]= '$'.number_format($total_amount_order_result=get_Num_orders('','','',$j,$i,'','SUM'),2);

$result_product_ids=$result_product_ids=get_Num_orders('','','',$j,$i,'','');
$product_name='';
while($row_product_id=mysql_fetch_array($result_product_ids))
{
$product_name.=get_product_name_by_id($row_product_id['ProductID']).' ,';
}
$list[] =$product_name;
$total_sale_order=$total_sale_order+$total_amount_order_result;
$total_num_order=$total_num_order+$total_num_order_result;

	  $week = strtotime("+7 day", $week);
}
if(strtotime("+1 day",$end)<$lastdate_time)
	{
	$j=date("d-m-Y",strtotime("+1 day",$end));
$i=date("d-m-Y",$lastdate_time);
$list[] = date("d-m-Y",strtotime("+1 day",$end)).' to '.date("d-m-Y",$lastdate_time);
$list[] = $total_num_order_result=get_Num_orders('','','',$j,$i,'','COUNT');
$list[]= '$'.number_format($total_amount_order_result=get_Num_orders('','','',$j,$i,'','SUM'),2);

$result_product_ids=$result_product_ids=get_Num_orders('','','',$j,$i,'','');
$product_name='';
while($row_product_id=mysql_fetch_array($result_product_ids))
{
$product_name.=get_product_name_by_id($row_product_id['ProductID']).' ,';
}
$list[] =$product_name;
$total_sale_order=$total_sale_order+$total_amount_order_result;
$total_num_order=$total_num_order+$total_num_order_result;
	}
			if(in_array('Week dates', $exp_arr)){$list[] = '';}
			if(in_array('Total Orders', $exp_arr)){$list[] = "";}
			if(in_array('Total Amount Sold', $exp_arr)){$list[] = "";}
			if(in_array('Product Names', $exp_arr)){$list[] = "";}
			
			if(in_array('Week dates', $exp_arr)){$list[] = '';}
			if(in_array('Total Orders', $exp_arr)){$list[] = "SUM Of total order";}
			if(in_array('Total Amount Sold', $exp_arr)){$list[] = "SUM of total amount";}
			if(in_array('Product Names', $exp_arr)){$list[] = "";}
			
			if(in_array('Week dates', $exp_arr)){$list[] = '';}
			if(in_array('Total Orders', $exp_arr)){$list[] = $total_num_order;}
			if(in_array('Total Amount Sold', $exp_arr)){$list[] = number_format($total_sale_order,2);}
			if(in_array('Product Names', $exp_arr)){$list[] = "";}
			
			

		   $list=array_chunk($list,$count);
if(is_array($list)){			

	foreach ($list as $fields) {

	$obj->insert_csv($fh,$fields); 

	}

}

if(fclose($fh))
{ ?>
	<script type="text/javascript">
	top.window.open('http://themillionaireos.com/admintti/excel/ExportWeeklySalesReport.csv');
	$.fancybox.close();
	</script>
<?php }
		   
}elseif($_GET['view']=='daily')
{
	$exp_arr=array('Date','Total Orders','Total Amount Sold','Product Names');
	$obj = new ExcelGenerater();		

$filename = "../../../excel/ExportDailySalesReport.csv";

$count = count($exp_arr);
//unset($rotator);
$fh = $obj->generate_csv($filename,$exp_arr);		
	$total_sale_order='';
$total_num_order='';
$start = $day = $first_date_time;
$end = strtotime("+1 day", $last_date_time);
while($day < $end)
{
	$j=date("d-m-Y",$day);
	$i=date("d-m-Y",$day);
	$list[] = $j;
			 $list[] = $total_num_order_result=get_Num_orders('','','',$j,$i,'','COUNT'); 
			 $list[]= '$'.number_format($total_amount_order_result=get_Num_orders('','','',$j,$i,'','SUM'),2);
			 
	 		 $result_product_ids=get_Num_orders('','','',$j,$i,'','');
			 $product_name='';
	  		while($row_product_id=mysql_fetch_array($result_product_ids))
			{
				$product_name.=get_product_name_by_id($row_product_id['ProductID']).' ,';
			}
			 $list[] =$product_name;
			 
$day = strtotime("+1 day", $day); 
$total_sale_order=$total_sale_order+$total_amount_order_result;
$total_num_order=$total_num_order+$total_num_order_result; 
$count1=$count1++;
}
if(in_array('Date', $exp_arr)){$list[] = '';}
			if(in_array('Total Orders', $exp_arr)){$list[] = "";}
			if(in_array('Total Amount Sold', $exp_arr)){$list[] = "";}
			if(in_array('Product Names', $exp_arr)){$list[] = "";}
			
			if(in_array('Date', $exp_arr)){$list[] = '';}
			if(in_array('Total Orders', $exp_arr)){$list[] = "SUM Of total order";}
			if(in_array('Total Amount Sold', $exp_arr)){$list[] = "SUM of total amount";}
			if(in_array('Product Names', $exp_arr)){$list[] = "";}
			
			if(in_array('Date', $exp_arr)){$list[] = '';}
			if(in_array('Total Orders', $exp_arr)){$list[] = $total_num_order;}
			if(in_array('Total Amount Sold', $exp_arr)){$list[] = number_format($total_sale_order,2);}
			if(in_array('Product Names', $exp_arr)){$list[] = "";}
			$list=array_chunk($list,$count);
if(is_array($list)){			

	foreach ($list as $fields) {

	$obj->insert_csv($fh,$fields); 

	}

}

if(fclose($fh))
{ ?>
	<script type="text/javascript">
	top.window.open('http://themillionaireos.com/admintti/excel/ExportDailySalesReport.csv');
	$.fancybox.close();
	</script>
<?php }	
}
elseif($_GET['view']=='all')
{
	$exp_arr=array('Date','Order Id','Type','Name','Refferal Partner','Invoice Total','Status','Product Name');
	$obj = new ExcelGenerater();		

$filename = "../../../excel/ExportAllSalesReport.csv";

$count = count($exp_arr);
//unset($rotator);
$fh = $obj->generate_csv($filename,$exp_arr);		
$total_sale_order='';
$total_num_order='';
$start = $day = $first_date_time;
$end = $last_date_time;
while($day <= $end)
{
	$j=date("d-m-Y",$day);
	$i=date("d-m-Y",$day);
	$result_for_day=get_Num_orders('','','',$j,$i,'','ALL');
	$count_result=mysql_num_rows($result_for_day);
	if($count_result>0)
	{
		while($row_for_day=mysql_fetch_array($result_for_day))
		{ 
			$list[] = date("l jS F Y",$day);
			$list[] = $row_for_day['ID']; 
			$list[]= $row_for_day['Type'];
			$list[]=get_member_by_id($row_for_day['MemberID'],'Name');
			$list[]=get_member_by_id($row_for_day['CoachId'],'AppCode');
			$list[]='$'.number_format($total_amount_order_result=$row_for_day['BillingAmount'],2);
			$list[]=$row_for_day['Status'];
			$list[]=get_product_name_by_id($row_for_day['ProductID']);
			$total_sale_order=$total_sale_order+$total_amount_order_result;
			$total_num_order=$total_num_order+1; 
			 }}elseif($count_result==0){}
			 $day = strtotime("+1 day", $day);
}
if(in_array('Date', $exp_arr)){$list[] = '';}
			if(in_array('Order Id', $exp_arr)){$list[] = "";}
			if(in_array('Type', $exp_arr)){$list[] = "";}
			if(in_array('Name', $exp_arr)){$list[] = "";}
			if(in_array('Refferal Partner', $exp_arr)){$list[] = "";}
			if(in_array('Invoice Total', $exp_arr)){$list[] = "";}
			if(in_array('Status', $exp_arr)){$list[] = "";}
			if(in_array('Product Name', $exp_arr)){$list[] = "";}
			
			if(in_array('Date', $exp_arr)){$list[] = '';}
			if(in_array('Order Id', $exp_arr)){$list[] = "";}
			if(in_array('Type', $exp_arr)){$list[] = "";}
			if(in_array('Name', $exp_arr)){$list[] = "";}
			if(in_array('Refferal Partner', $exp_arr)){$list[] = "SUM Of total order";}
			if(in_array('Invoice Total', $exp_arr)){$list[] = "SUM of total amount";}
			if(in_array('Status', $exp_arr)){$list[] = "";}
			if(in_array('Product Name', $exp_arr)){$list[] = "";}
			
			if(in_array('Date', $exp_arr)){$list[] = '';}
			if(in_array('Order Id', $exp_arr)){$list[] = "";}
			if(in_array('Type', $exp_arr)){$list[] = "";}
			if(in_array('Name', $exp_arr)){$list[] = "";}
			if(in_array('Refferal Partner', $exp_arr)){$list[] =$total_num_order;}
			if(in_array('Invoice Total', $exp_arr)){$list[] = "$".number_format($total_sale_order,2);}
			if(in_array('Status', $exp_arr)){$list[] = "";}
			if(in_array('Product Name', $exp_arr)){$list[] = "";}
			
			
			$list=array_chunk($list,$count);
if(is_array($list)){			

	foreach ($list as $fields) {

	$obj->insert_csv($fh,$fields); 

	}

}

if(fclose($fh))
{ ?>
	<script type="text/javascript">
	top.window.open('http://themillionaireos.com/admintti/excel/ExportAllSalesReport.csv');
	$.fancybox.close();
	</script>
<?php }	
	
}




?>

