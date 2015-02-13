<?php
include "../../lib/include.php";
$TagsMembers = new TagsMember();
$objMember = new Member();
$SearchDatelead=$TotalSearch=$PagewiseMemberIds=$SearchDateleadarea=$member_ids_in_date_range=0;
$rows2 = $objMember->GetAllMemberFromCoOp(" 1 ",array("Member.ID","NextDispalyCounter","AweberList","AppId","AppCode","Surname", "Email", "FirstName", "WMILevel", "MemberStatus"));
if(is_array($rows2)){
 foreach($rows2 as $rotator ){
  $MemberIdIN[]=$rotator['ID'];
 }
  $PagewiseMemberIds=implode(',',$MemberIdIN);
}

function GetMemberAdjustmentPurchasedN($MemberID = 0,$view = '',$month = '', $year='',$date_start='',$date_end='',$without_date='',$without_adj=''){
		global $searcharea,$PagewiseMemberIds,$member_ids_in_date_range; //$TotalSearch, $SearchDate;
		$memberidcond = '';
		
		if($MemberID == 0){
			$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$PagewiseMemberIds.') And Member.StopDisplay <> 1 ';
		}else{
			$memberidcond = 'Member.ID = '.$MemberID;
		}
		
		if($view=='month')
		{
	 $query_credit_share_available  = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond AND month(MemberPurchasedShare.Created)=$month AND year(MemberPurchasedShare.Created) =$year AND TYPE IN ( 1 ) LIMIT 0 , 1";
		}
		elseif($view=='week')
		{
			
			$query_credit_share_available  = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond AND STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('".date('Y-m-d',strtotime($date_start))." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s')<=STR_TO_DATE('".date('Y-m-d',strtotime($date_end))." 23:59:59', '%Y-%m-%d %H:%i:%s') AND TYPE IN ( 1 ) LIMIT 0 , 1";
			
		}
		else
		{
			if($without_date==1)
			{
				$query_credit_share_available  = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond   AND TYPE IN ( 1 ) LIMIT 0 , 1";
			}else
			{
				$query_credit_share_available  = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond  AND TYPE IN ( 1 ) LIMIT 0 , 1";
			}
		}
	  //die();
			 $result_credit_share_available = mysql_query($query_credit_share_available) or die("Query '$query_credit_share_available' failed with error message: \"" . mysql_error () . '"');
			 
			 $row_credit_share_available    = mysql_fetch_array($result_credit_share_available);
			 
		
		if($view=='month')
		{	 
           $query_credit_adjustment 	= "SELECT SUM(AdjustmentAmount) AS Adjustments FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond AND month(MemberPurchasedShare.Created)=$month AND year(MemberPurchasedShare.Created) =$year  AND Type = 2 LIMIT 0,1";
		}elseif($view=='week')
		{
			$query_credit_adjustment  = "SELECT SUM(AdjustmentAmount) AS Adjustments FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond AND STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('".date('Y-m-d',strtotime($date_start))." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s')<=STR_TO_DATE('".date('Y-m-d',strtotime($date_end))." 23:59:59', '%Y-%m-%d %H:%i:%s') AND Type = 2  LIMIT 0 , 1";
			
		}
		else
		{
			if($without_date==1)
			{
				$query_credit_adjustment 	= "SELECT SUM(AdjustmentAmount) AS Adjustments FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond AND Type = 2 LIMIT 0,1";
			}else
			{
				$query_credit_adjustment 	= "SELECT SUM(AdjustmentAmount) AS Adjustments FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond  AND Type = 2 LIMIT 0,1";
			}
		}
			 $result_credit_adjustment 	= mysql_query($query_credit_adjustment) or die("Query '$query_credit_adjustment' failed with error message: \"" . mysql_error () . '"');
			 $row_credit_adjustment    	= mysql_fetch_array($result_credit_adjustment);

			if($without_adj==1)
			{
				$available_balance 		= $row_credit_share_available['ShareAmountAvailable'];
			}else{
			 $available_balance 		= $row_credit_share_available['ShareAmountAvailable']-$row_credit_adjustment['Adjustments'];
			}
			 if(!empty($_SESSION['FromDate']))
			 {
				 
			 $query_memberid_in_date_range  = "SELECT distinct MemberID FROM MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond  $searcharea AND Type IN (1,2)";
			 
			  $result_memberid_in_date_range 	= mysql_query($query_memberid_in_date_range) or die("Query '$query_memberid_in_date_range' failed with error message: \"" . mysql_error () . '"');
			
			 
			if($result_memberid_in_date_range){
				$MemberIdIN_date_range = array();
			 while($row_memberid_in_date_range=mysql_fetch_array($result_memberid_in_date_range)){
			  $MemberIdIN_date_range[]=$row_memberid_in_date_range['MemberID'];
			 }
			
			  $member_ids_in_date_range=implode(',',$MemberIdIN_date_range);
			}
			 }
			 
			 
			 if(empty($available_balance)){$available_balance=0;}
             return $available_balance;
}//End for total Purchase
		
//print_r("SELECT distinct MemberID FROM MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond  $searcharea AND TYPE IN ( 1,2,3,4 )");		
		
		//Start for total Bonus
function GetMemberBonusOtherN($MemberID = 0,$view = '',$month = '', $year='',$date_start='',$date_end='',$without_date=''){
		global $searcharea,$PagewiseMemberIds; //$TotalSearch, $SearchDate;
		$memberidcond = '';
		
		if($MemberID == 0){
			$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$PagewiseMemberIds.') And Member.StopDisplay <> 1  ';
		}else{
			$memberidcond = 'Member.ID = '.$MemberID;
		}
		if($view=='month')
		{	 
			 $query_credit_share_bonus = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond AND month(MemberPurchasedShare.Created)= $month AND year(MemberPurchasedShare.Created) = $year  AND TYPE IN ( 3,4 ) LIMIT 0,1";
		}elseif($view=='week')
		{
			$query_credit_share_bonus = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond AND STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('".date('Y-m-d',strtotime($date_start))." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s')<=STR_TO_DATE('".date('Y-m-d',strtotime($date_end))." 23:59:59', '%Y-%m-%d %H:%i:%s')  AND TYPE IN ( 3,4 ) LIMIT 0,1";
		}
		else	
		{
			if($without_date==1)
			{
				$query_credit_share_bonus = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond   AND TYPE IN ( 3,4 ) LIMIT 0,1";
			}else
			{
				$query_credit_share_bonus = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond  AND TYPE IN ( 3,4 ) LIMIT 0,1";
			}
		}
			 $result_query_credit_share_bonus = mysql_query($query_credit_share_bonus) or die("Query '$query_credit_share_bonus' failed with error message: \"" . mysql_error () . '"');
			 $row_result_query_credit_share_bonus   = mysql_fetch_array($result_query_credit_share_bonus);

			 $available_bonus		= $row_result_query_credit_share_bonus['ShareAmountAvailable'];
			 if(empty($available_bonus))$available_bonus=0;

			 return $available_bonus;
}//End for total Bonus	


//Start for total Balance used
function GetMemberUsedCredit($MemberID = 0 ,$view = '',$month = '', $year='',$date_start='',$date_end='',$without_date='', $all_member=''){
		global $SearchDatelead, $TotalSearch,$PagewiseMemberIds, $SearchDateleadarea,$member_ids_in_date_range; //$SearchDate, $SearchDatelead;
		$memberidcond = '';
		
		if($MemberID == 0){
			if(!empty($member_ids_in_date_range))
			{
				$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$member_ids_in_date_range.') And Member.StopDisplay <> 1 ';
			}else
			{
				$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$PagewiseMemberIds.') And Member.StopDisplay <> 1 ';
			}
			$remove_date=0;
			if($all_member=='')
			{
				$remove_date=0;
			}
			else
			{
				$remove_date=1;
			}
		}else{
			$memberidcond = 'Member.ID = '.$MemberID;
			$remove_date=1;
		}
		if($remove_date==1)
		{
		 $query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond  AND IsAdmin = 0 LIMIT 0,1";
		}else
		{
			if($view=='month')
			{	 
			$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond AND month(MemberLead.Created)= $month AND year(MemberLead.Created) = $year AND IsAdmin = 0 LIMIT 0,1";
			}
			elseif($view=='week')
			{
				$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond AND STR_TO_DATE(MemberLead.Created, '%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('".date('Y-m-d',strtotime($date_start))." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(MemberLead.Created, '%Y-%m-%d %H:%i:%s')<=STR_TO_DATE('".date('Y-m-d',strtotime($date_end))." 23:59:59', '%Y-%m-%d %H:%i:%s')  AND IsAdmin = 0 LIMIT 0,1";
				
			}
			else
			{
				$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond  AND IsAdmin = 0 LIMIT 0,1";
			
			}
		}
		 //die();
		 $result_credit_used = mysql_query($query_credit_used) or die("Query '$query_credit_used' failed with error message: \"" . mysql_error () . '"');
		 $row_credit_used    = mysql_fetch_array($result_credit_used);
		 $total_credit_used = $row_credit_used['AmountUsed'];
		 if(empty($total_credit_used))$total_credit_used=0;

			  return $total_credit_used;
}			 //End for total Balance used


function GetMemberCancelAmt($MemberID = 0 ,$view = '',$month = '', $year='',$date_start='',$date_end=''){
		global $SearchDatelead, $TotalSearch,$PagewiseMemberIds, $SearchDateleadarea,$member_ids_in_date_range; //$SearchDate, $SearchDatelead;
		$memberidcond = '';
		
		if($MemberID == 0){
			if(!empty($member_ids_in_date_range))
			{
				$memberidcond = 'Member.ID != 0 And Member.ID IN('.$member_ids_in_date_range.') And Member.MemberStatus="canceled" And StopDisplay <> 1';
				
			}else
			{
				$memberidcond = 'Member.ID != 0 And Member.ID IN('.$PagewiseMemberIds.') And Member.MemberStatus="canceled" And StopDisplay <> 1';
			}
			
		}else{
			$memberidcond = 'Member.ID = '.$MemberID.'And MemberStatus="canceled" ';
		}
		if($view=='month')
		{	 
			$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond AND month(MemberLead.Created)= $month AND year(MemberLead.Created) = $year AND IsAdmin = 0 LIMIT 0,1";
			
		}
		elseif($view=='week')
		{
			$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond AND STR_TO_DATE(MemberLead.Created, '%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('".date('Y-m-d',strtotime($date_start))." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(MemberLead.Created, '%Y-%m-%d %H:%i:%s')<=STR_TO_DATE('".date('Y-m-d',strtotime($date_end))." 23:59:59', '%Y-%m-%d %H:%i:%s')  AND IsAdmin = 0 LIMIT 0,1";
		}
		else
		{
			$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond  AND IsAdmin = 0 LIMIT 0,1";
		}
		 $result_credit_used = mysql_query($query_credit_used) or die("Query '$query_credit_used' failed with error message: \"" . mysql_error () . '"');
		 $row_credit_used    = mysql_fetch_array($result_credit_used);
		 $total_credit_used = $row_credit_used['AmountUsed'];
		 if(empty($total_credit_used))$total_credit_used=0;

	    return $total_credit_used;
}//End for total Bonus	


//Start for total Balance used
function GetMemberUsedCreditByDuration($MemberID = 0){
		global $SearchDatelead, $TotalSearch,$SearchDateleadarea,$PagewiseMemberIds,$member_ids_in_date_range; //$SearchDate, $SearchDatelead;
		$memberidcond = '';
		
		if($MemberID == 0){
			if(!empty($member_ids_in_date_range))
			{
				$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$member_ids_in_date_range.') And Member.StopDisplay <> 1 ';
			}else
			{
				$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$PagewiseMemberIds.') And Member.StopDisplay <> 1 ';
			}
			
		}else{
			$memberidcond = 'Member.ID = '.$MemberID;
		}
		
		$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond  AND IsAdmin = 0 LIMIT 0,1";
		 
		 $result_credit_used = mysql_query($query_credit_used) or die("Query '$query_credit_used' failed with error message: \"" . mysql_error () . '"');
		 $row_credit_used    = mysql_fetch_array($result_credit_used);
		 $total_credit_used = $row_credit_used['AmountUsed'];
		 if(empty($total_credit_used))$total_credit_used=0;

			  return $total_credit_used;
}			 //End for total Balance used


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
$query_get_first_last_date='SELECT  MAX(`Created`) as last_date ,MIN(`Created`) as first_date  FROM `MemberPurchasedShare` WHERE 1';
		$result_get_first_last_date=mysql_query($query_get_first_last_date);
		$row_get_first_last_date=mysql_fetch_array($result_get_first_last_date);
		$first_date_in_db=$row_get_first_last_date['first_date'];
		$last_date_in_db=$row_get_first_last_date['last_date'];
		$first_month=date('m',strtotime($row_get_first_last_date['first_date']));
		$first_year=date('Y',strtotime($row_get_first_last_date['first_date']));
		$last_month=date('m',strtotime($row_get_first_last_date['last_date']));
		$last_year=date('Y',strtotime($row_get_first_last_date['last_date']));
		$first_date_time=strtotime($first_date_in_db);
		$last_date_time=strtotime($last_date_in_db);	
if($_REQUEST['view']=='month')
{
	$exp_arr=array('Month/Year','TotalPurchased','TotalBonus','TotalPurchase&Bonus','TotalLSpend','TotalBalance','CanceledMembers','TotalLeadOwed');
	$obj = new ExcelGenerater();		

$filename = "../../excel/ExportMonthlyCoopShareReport.csv";

$count = count($exp_arr);
//unset($rotator);
$fh = $obj->generate_csv($filename,$exp_arr);		
	
	
	$start = $month = $first_date_time;
$end = strtotime("+1 month", $last_date_time);
$count1=0;
while($month < $end)
{
	$j=date("m",$month);
	$i=date("Y",$month);
			$list[] = $j.'/'.$i;
			 $list[] = '$'.number_format(GetMemberAdjustmentPurchasedN(0,'month',$j,$i),2); 
			 $list[]= '$'.number_format(GetMemberBonusOtherN(0,'month',$j,$i),2);
			 $list[]= '$'.number_format(GetMemberAdjustmentPurchasedN(0,'month',$j,$i)+GetMemberBonusOtherN(0,'month',$j,$i),2);
	 		 $list[] = '$'.number_format(GetMemberUsedCredit(0,'month',$j,$i),2);
			 $list[] = '$'.number_format(((GetMemberAdjustmentPurchasedN(0,'month',$j,$i)+GetMemberBonusOtherN(0,'month',$j,$i))-GetMemberUsedCredit(0,'month',$j,$i)),2);
			 $list[]='$'.number_format(GetMemberCancelAmt(0,'month',$j,$i),2);
			 $list[]='$'.number_format((((GetMemberAdjustmentPurchasedN(0,'month',$j,$i)+GetMemberBonusOtherN(0,'month',$j,$i))-GetMemberUsedCredit(0,'month',$j,$i))-GetMemberCancelAmt(0,'month',$j,$i)),2);
$month = strtotime("+1 month", $month); 
$count1=$count1++;
}

$list=array_chunk($list,$count);
if(is_array($list)){			

	foreach ($list as $fields) {

	$obj->insert_csv($fh,$fields); 

	}

}

if(fclose($fh))
{ ?>
	<script type="text/javascript">
	top.window.open('http://themillionaireos.com/admintti/excel/ExportMonthlyCoopShareReport.csv');
	</script>
<?php }

}elseif($_REQUEST['view']=='week')
{
	$exp_arr=array('Week dates','TotalPurchased','TotalBonus','TotalPurchase&Bonus','TotalLSpend','TotalBalance','CanceledMembers','TotalLeadOwed');
	$obj = new ExcelGenerater();		

$filename = "../../excel/ExportWeeklyCoopShareReport.csv";

$count = count($exp_arr);
//unset($rotator);
$fh = $obj->generate_csv($filename,$exp_arr);		
	
	$fisrdate_time=strtotime($first_date_in_db);
$next_week_time=strtotime("+6 day",$fisrdate_time);
$lastdate_time=strtotime($last_date_in_db);
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
$total_purchase_week_all='';
$total_bonus_week_all='';
$total_lead_spend_week_all='';
$total_member_cancel_week_all='';
$list[] = date('d-m-Y',$fisrdate_time).' to '.date('d-m-Y',strtotime("-1 day",$next_week_time));
			 $list[] = '$'.number_format($total_purchase_week=GetMemberAdjustmentPurchasedN(0,'week','','',date('d-m-Y',$fisrdate_time),date('d-m-Y',strtotime("-1 day",$next_week_time))),2); 
			 $list[]= '$'.number_format($total_bonus_week=GetMemberBonusOtherN(0,'week','','',date('d-m-Y',$fisrdate_time),date('d-m-Y',strtotime("-1 day",$next_week_time))),2);
			 $list[]= '$'.number_format($total_purchase_bonus_week=($total_purchase_week+$total_bonus_week),2);
	 		 $list[] = '$'.number_format($total_lead_spend_week=GetMemberUsedCredit(0,'week','','',date('d-m-Y',$fisrdate_time),date('d-m-Y',strtotime("-1 day",$next_week_time))),2);
			 $list[] = '$'.number_format($total_balance_week=($total_purchase_bonus_week-$total_lead_spend_week),2);
			 $list[]='$'.number_format($total_member_cancel_week=GetMemberCancelAmt(0,'week','','',date('d-m-Y',$fisrdate_time),date('d-m-Y',strtotime("-1 day",$next_week_time))),2);
			 $list[]='$'.number_format($total_leads_owed_week=($total_balance_week-$total_member_cancel_week),2);
 $total_purchase_week_all=$total_purchase_week_all+$total_purchase_week;
			$total_bonus_week_all=$total_bonus_week_all+$total_bonus_week;
			$total_lead_spend_week_all=$total_lead_spend_week_all+$total_lead_spend_week;
			$total_member_cancel_week_all=$total_member_cancel_week_all+$total_member_cancel_week;
			
for($i=$next_week_time;$i<=$last_week_time;$i=strtotime("+7 day", $i)){
		  $list[] = date('d-m-Y',$i).' to '.date('d-m-Y',strtotime("+6 day", $i));
			 $list[] = '$'.number_format($total_purchase_week=GetMemberAdjustmentPurchasedN(0,'week','','',date('d-m-Y',$i),date('d-m-Y',strtotime("+6 day", $i))),2); 
			 $list[]= '$'.number_format($total_bonus_week=GetMemberBonusOtherN(0,'week','','',date('d-m-Y',$i),date('d-m-Y',strtotime("+6 day", $i))),2);
			 $list[]= '$'.number_format($total_purchase_bonus_week=($total_purchase_week+$total_bonus_week),2);
	 		 $list[] = '$'.number_format($total_lead_spend_week=GetMemberUsedCredit(0,'week','','',date('d-m-Y',$i),date('d-m-Y',strtotime("+6 day", $i))),2);
			 $list[] = '$'.number_format($total_balance_week=($total_purchase_bonus_week-$total_lead_spend_week),2);
			 $list[]='$'.number_format($total_member_cancel_week=GetMemberCancelAmt(0,'week','','',date('d-m-Y',$i),date('d-m-Y',strtotime("+6 day", $i))),2);
			 $list[]='$'.number_format($total_leads_owed_week=($total_balance_week-$total_member_cancel_week),2);
		  $total_purchase_week_all=$total_purchase_week_all+$total_purchase_week;
			 $total_bonus_week_all=$total_bonus_week_all+$total_bonus_week;
			 $total_lead_spend_week_all=$total_lead_spend_week_all+$total_lead_spend_week;
			 $total_member_cancel_week_all=$total_member_cancel_week_all+$total_member_cancel_week;
			
} 
$list[] = date('d-m-Y',$i).' to '.date('d-m-Y',$lastdate_time);
			 $list[] = '$'.number_format($total_purchase_week=GetMemberAdjustmentPurchasedN(0,'week','','',date('d-m-Y',$i),date('d-m-Y',$lastdate_time)),2); 
			 $list[]= '$'.number_format($total_bonus_week=GetMemberBonusOtherN(0,'week','','',date('d-m-Y',$i),date('d-m-Y',$lastdate_time)),2);
			 $list[]= '$'.number_format($total_purchase_bonus_week=($total_purchase_week+$total_bonus_week),2);
	 		 $list[] = '$'.number_format($total_lead_spend_week=GetMemberUsedCredit(0,'week','','',date('d-m-Y',$i),date('d-m-Y',$lastdate_time)),2);
			 $list[] = '$'.number_format($total_balance_week=($total_purchase_bonus_week-$total_lead_spend_week),2);
			 $list[]='$'.number_format($total_member_cancel_week=GetMemberCancelAmt(0,'week','','',date('d-m-Y',$i),date('d-m-Y',$lastdate_time)),2);
			 $list[]='$'.number_format($total_leads_owed_week=($total_balance_week-$total_member_cancel_week),2);
		  $total_purchase_week_all=$total_purchase_week_all+$total_purchase_week;
			 $total_bonus_week_all=$total_bonus_week_all+$total_bonus_week;
			 $total_lead_spend_week_all=$total_lead_spend_week_all+$total_lead_spend_week;
			 $total_member_cancel_week_all=$total_member_cancel_week_all+$total_member_cancel_week;
             $total_purchase_week_all=$total_purchase_week_all+$total_purchase_week; 
		   $total_bonus_week_all=$total_bonus_week_all+$total_bonus_week;
		   $total_lead_spend_week_all=$total_lead_spend_week_all+$total_lead_spend_week;
		   $total_member_cancel_week_all=$total_member_cancel_week_all+$total_member_cancel_week;
		   $list=array_chunk($list,$count);
if(is_array($list)){			

	foreach ($list as $fields) {

	$obj->insert_csv($fh,$fields); 

	}

}

if(fclose($fh))
{ ?>
	<script type="text/javascript">
	top.window.open('http://themillionaireos.com/admintti/excel/ExportWeeklyCoopShareReport.csv');
	</script>
<?php }
		   
}elseif($_GET['view']=='all')
{
	$exp_arr=array('Members','Status','PurchasedDate','TotalPurchased','Bonus/Other','LastLead','LeadsdeliveredByDuration','TotalLeadsdelivered','Leadsowed');
	$obj = new ExcelGenerater();		

$filename = "../../excel/ExportTotalCoopShareReport.csv";

$count = count($exp_arr);
//unset($rotator);
$fh = $obj->generate_csv($filename,$exp_arr);		
	$row_flag=0;
$i = 0;
 
if(is_array($rows2)){
  foreach($rows2 as $rotator ){ 
  
  //echo $rotator['ID'];
  
     if($_SESSION['AC'] != ''){
		   		if($_SESSION['AC'] == "YES"){
					$ACSearch = " AND IsAC = '1'";

		   		}elseif($_SESSION['AC'] == "NO"){
					$ACSearch = " AND IsAC = '0'";
				}
		   }
		   

		 /*  $flag_query="SELECT count(*) as totalleads  FROM `MemberLead` WHERE `MemberID` ='".$rotator['ID']."'  AND `IsPersonal` = 0 AND IsAdmin = 0 $ACSearch";
		   $result_rotator_list = mysql_query($flag_query) or die("Query '$flag_query' failed with error message: \"" . mysql_error () . '"');
		   $rotator_leads=mysql_fetch_array($result_rotator_list);*/


  		   $total_purchase="SELECT Created as firstdate FROM MemberPurchasedShare  where `MemberID` ='".$rotator['ID']."' AND Type = 1 ORDER BY Created   LIMIT 0,1; ";
		   $result_purchase  = mysql_query($total_purchase)or die('Query '.$total_purchase.' has Some Errors '.mysql_error());

		   $purchase_row     = mysql_fetch_array($result_purchase);
		   //$total_purchase  = $purchase_row['totalpurchase'];

		   $purchase_date    = $purchase_row['firstdate'];
		  // $total_purchase;


  		   $total_balence=" SELECT sum(AdjustmentAmount )as totalbalence FROM MemberPurchasedShare  where MemberID ='".$rotator['ID']."' AND Type = 1";

		   $result  = mysql_query($total_balence)or die('Query '.$total_balence.' has Some Errors '.mysql_error());
		   $row=mysql_fetch_array($result);
		   
		   $total_purchase=$row['totalbalence'];
		   
		   
			$flag_query="SELECT Created FROM `MemberLead` WHERE `MemberID` ='".$rotator['ID']."'  AND `IsPersonal` = 0 AND IsAdmin = 0 $ACSearch order
			by ID DESC limit 1";
		    $result_rotator_list = mysql_query($flag_query) or die("Query '$flag_query' failed with error message: \"" . mysql_error () . '"');
		    $rotator_leads=mysql_fetch_array($result_rotator_list);
		   
		   
		   $members_credit = $objMember->GetMemberCredit($rotator['ID']);
		   
		   
			$AllMembers[$i]['MemberStatus'] = $rotator['MemberStatus'];
			$AllMembers[$i]['ID'] = $rotator['ID'];
			$AllMembers[$i]['FirstName'] = $rotator['FirstName'];
			$AllMembers[$i]['Surname'] = $rotator['Surname'];
			$AllMembers[$i]['totalleads'] = $rotator_leads['totalleads'];
			$AllMembers[$i]['purchase_date'] = $purchase_date;
			$AllMembers[$i]['total_purchased'] = GetMemberAdjustmentPurchasedN($rotator['ID']);
			$AllMembers[$i]['total_bonus_credit'] = GetMemberBonusOtherN($rotator['ID']);
			$AllMembers[$i]['members_credit_used'] = GetMemberUsedCredit($rotator['ID']);
		    $AllMembers[$i]['members_credit_used_byduration'] = GetMemberUsedCreditByDuration($rotator['ID']);
			$AllMembers[$i]['members_credit'] = $objMember->GetMemberCredit($rotator['ID']);
			/*$AllMembers[$i]['totalleads'] = $rotator_leads['totalleads'];*/
			$MemberIdIN['ID_'.$i] = $rotator['ID'];
			
		   
		   $AllMembers[$i]['LastleadDate'] = $rotator_leads['Created'];
			
		/*	if(isset($_SESSION['AtZero']) && $_SESSION['AtZero'] != ""){	
					
				if($_SESSION['AtZero'] == "YES" && !(($AllMembers[$i]['total_purchased'] + $AllMembers[$i]['total_bonus_credit']) <= 0 && $AllMembers[$i]['members_credit_used'] > 0)){
			
					unset($AllMembers[$i]);
				}elseif($_SESSION['AtZero'] == "NO" ){
				   echo "hi";
					if((($AllMembers[$i]['total_purchased'] + $AllMembers[$i]['total_bonus_credit']) <= 0 && $AllMembers[$i]['members_credit_used'] > 0) || ($AllMembers[$i]['members_credit_used'] <= 0)){
						unset($AllMembers[$i]);
				}
				}
			}*/
			

			
$i++;

}

  //$total_records=count($AllMembers);
  
  //echo $i;
 }
 $tot_total_purchased = $tot_total_bonus_credit = $tot_summery = $tot_leads_byduration = $tot_lead_paid = $tot_temp_blance = $tot_actual_bal = $caneclmemberTotlal= 0;

if(is_array($AllMembers)){
foreach($AllMembers as $rotator ){
			
			if(in_array('Members', $exp_arr)){
				$list[] = $rotator['FirstName']." ". $rotator['Surname'];
			}
			
			if(in_array('Status', $exp_arr)){
				$list[] = $rotator['MemberStatus'];
			}
			
			if(in_array('PurchasedDate', $exp_arr)){
				$list[] = $rotator['purchase_date'];
			}
			
			if(in_array('TotalPurchased', $exp_arr)){
			
				$tot_total_purchased = $tot_total_purchased + $rotator['total_purchased'];
	       		$list[] = "$".number_format($rotator['total_purchased'], 2);
			}
			
			if(in_array('Bonus/Other', $exp_arr)){
				 $tot_total_bonus = $tot_total_bonus + $rotator['total_bonus_credit'];
	      		 $list[] = "$".number_format($rotator['total_bonus_credit'], 2);		 
			}
			
			if(in_array('LastLead', $exp_arr)){
				$list[] = $rotator['LastleadDate'];
			}
			
			if(in_array('LeadsdeliveredByDuration', $exp_arr)){
				$tot_leads_byduration = $tot_leads_byduration + $rotator['members_credit_used_byduration'];
	      		 $list[] = "$".number_format($rotator['members_credit_used_byduration'], 2);
			}
			
			if(in_array('TotalLeadsdelivered', $exp_arr)){
				$tot_lead_paid = $tot_lead_paid + $rotator['members_credit_used'];
	        	$list[] = "$".number_format($rotator['members_credit_used'], 2);
			}
			
			if(in_array('Leadsowed', $exp_arr)){
				$Leadsoweds=(($rotator['total_purchased']+$rotator['total_bonus_credit'])-$rotator['members_credit_used']);
	  		 if($rotator['MemberStatus']=='canceled'){
	   		 	$caneclmemberTotlal=$caneclmemberTotlal+$rotator['members_credit_used'];
	   			}
	       	$Leadsowed=$Leadsowed+$Leadsoweds;
            $list[] = "$".number_format($Leadsoweds, 2);
				
			}
			
}
}

//FOR TOTAL DISPLAY
			if(in_array('Members', $exp_arr)){$list[] = '';}
			if(in_array('Status', $exp_arr)){$list[] = "";}
			if(in_array('PurchasedDate', $exp_arr)){$list[] = "";}
			if(in_array('TotalPurchased', $exp_arr)){$list[] = "Total Purchas";}
			if(in_array('Bonus/Other', $exp_arr)){$list[] = "Total Bonus";}
			if(in_array('LastLead', $exp_arr)){$list[] = "";}
			if(in_array('LeadsdeliveredByDuration', $exp_arr)){$list[] = "Total Lead Spen By Duration";}
			if(in_array('TotalLeadsdelivered', $exp_arr)){$list[] = "Total Spend";}
			if(in_array('Leadsowed', $exp_arr)){$list[] = "Balance Due";}
			
			if(in_array('Members', $exp_arr)){$list[] = '';}
			if(in_array('Status', $exp_arr)){$list[] = "";}
			if(in_array('PurchasedDate', $exp_arr)){$list[] = "";}
			if(in_array('TotalPurchased', $exp_arr)){$list[] = "$".number_format($tot_total_purchased, 2);}
			if(in_array('Bonus/Other', $exp_arr)){$list[] = "$".number_format($tot_total_bonus , 2);}
			if(in_array('LastLead', $exp_arr)){$list[] = "";}
			if(in_array('LeadsdeliveredByDuration', $exp_arr)){$list[] = "$".number_format($tot_leads_byduration, 2);}
			if(in_array('TotalLeadsdelivered', $exp_arr)){$list[] = "$".number_format($tot_lead_paid, 2);}
			if(in_array('Leadsowed', $exp_arr)){$list[] = "$".number_format($Leadsowed, 2);}
			
			if(in_array('Members', $exp_arr)){$list[] = '';}
			if(in_array('Status', $exp_arr)){$list[] = "";}
			if(in_array('PurchasedDate', $exp_arr)){$list[] = "";}
			if(in_array('TotalPurchased', $exp_arr)){$list[] = "";}
			if(in_array('Bonus/Other', $exp_arr)){$list[] = "";}
			if(in_array('LastLead', $exp_arr)){$list[] = "";}
			if(in_array('LeadsdeliveredByDuration', $exp_arr)){$list[] = "";}
			if(in_array('TotalLeadsdelivered', $exp_arr)){$list[] = "Cancelled members";}
			if(in_array('Leadsowed', $exp_arr)){$list[] = "-$".number_format($caneclmemberTotlal,2);}
			
			if(in_array('Members', $exp_arr)){$list[] = '';}
			if(in_array('Status', $exp_arr)){$list[] = "";}
			if(in_array('PurchasedDate', $exp_arr)){$list[] = "";}
			if(in_array('TotalPurchased', $exp_arr)){$list[] = "";}
			if(in_array('Bonus/Other', $exp_arr)){$list[] = "";}
			if(in_array('LastLead', $exp_arr)){$list[] = "";}
			if(in_array('LeadsdeliveredByDuration', $exp_arr)){$list[] = "";}
			if(in_array('TotalLeadsdelivered', $exp_arr)){$list[] = "Balance";}
			if(in_array('Leadsowed', $exp_arr)){
			$balnacAmt=$Leadsowed-$caneclmemberTotlal;
			$list[] = "$".number_format($balnacAmt,2);}
$list=array_chunk($list,$count);

if(is_array($list)){			

	foreach ($list as $fields) {

	$obj->insert_csv($fh,$fields); 

	}

}

if(fclose($fh))
{ ?>
	<script type="text/javascript">
	top.window.open('http://themillionaireos.com/admintti/excel/ExportTotalCoopShareReport.csv');
	</script>
<?php }
}



?>

