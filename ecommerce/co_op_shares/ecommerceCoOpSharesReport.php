<?php 
include_once '../../include/header.php'; 

$TagsMembers = new TagsMember();
/*
if($super_admin != 1){
	//echo "No Access";
	exit;
}*/

$objMember = new Member();
$_SESSION['MemberStatusArray']=array();



if($_REQUEST['Action'] == "Rotate"){
	$objMember->UpdateMembersInRotator($_REQUEST['ID']);
} 

// Set AweberList = '' if they have NULL which help to sort them
/*$update_AweberList_query=" UPDATE Member SET AweberList = '' WHERE AweberList IS NULL OR AweberList LIKE 'NULL';";
mysql_query($update_AweberList_query)or die('Query '.$update_AweberList_query.' has Some Errors '.mysql_error());*/


if(isset($_REQUEST['AC'])){

	$_SESSION['AC'] = $_REQUEST['AC'];

}

if(isset($_REQUEST['AtZero'])){

	$_SESSION['AtZero'] = trim($_REQUEST['AtZero']);

}

if($_REQUEST['Action'] == "OneStepUp"){

	    $sql= "UPDATE Member SET NextDispalyCounter= 0 WHERE ID='".$_REQUEST['ID']."'";

	    mysql_query($sql) or die("Query '$sql' failed with error message: \"" . mysql_error () . '"');

		$objMember->ResetRotator();

}

if(isset($_REQUEST['SearchText'])){

	$_SESSION['SearchText'] = trim($_REQUEST['SearchText']);

}

if($_REQUEST['action']=='SetFilter'){
	if(isset($_REQUEST['FromDate']) && isset($_REQUEST['ToDate'])){
		 $_SESSION['FromDate'] = trim($_REQUEST['FromDate']);
		 $_SESSION['ToDate'] = trim($_REQUEST['ToDate']);
		 
	
	}elseif(isset($_GET['month']) && isset($_GET['year'])){
	  
		 $_SESSION['FromDate'] = trim($_GET['month'].'/01/'.$_GET['year']);
		 $_SESSION['ToDate'] = trim($_GET['month'].'/31/'.$_GET['year']);
		 
	
	}elseif(isset($_GET['date_start']) && isset($_GET['date_end']))
	{
		$_SESSION['FromDate'] = trim(date("m/d/Y",$_GET['date_start']));
		 $_SESSION['ToDate'] = trim(date("m/d/Y",$_GET['date_end']));
	}else
	{
		unset($_SESSION['FromDate']);
		 unset($_SESSION['ToDate']);
	}
}


if(isset($_SESSION['SearchText']) && $_SESSION['SearchText'] != ''){

 $SearchQuery = " (Member.ID LIKE '%".$_SESSION['SearchText']."%' OR FirstName LIKE '%".$_SESSION['SearchText']."%' OR  Surname LIKE'%".$_SESSION['SearchText']."%' OR Email LIKE '%".$_SESSION['SearchText']."%') " ;

}

if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
}

if(isset($_REQUEST['MemberStatusFilterRall'])){
$_SESSION['MemberStatusFilterR']='';
$_SESSION['MemberStatusArray']=array();
	
}
else{
	if(isset($_REQUEST['MemberStatusFilterR'])){
		
		   $_SESSION['MemberStatusFilterR'] =implode(',',$_REQUEST['MemberStatusFilterR']);
		   $_SESSION['MemberStatusFilterR']=stripslashes($_SESSION['MemberStatusFilterR']);
		   $_SESSION['MemberStatusArray']=explode(',',$_SESSION['MemberStatusFilterR']);
			  //print_r($_SESSION['MemberStatusArray']);
		 
			
			//$_SESSION['MemberStatusFilterR'] = trim($_REQUEST['MemberStatusFilterR']);
	 }else{
	    $_SESSION['MemberStatusFilterR']='';
        $_SESSION['MemberStatusArray']=array();
	 
	 }
  
}

$TotalSearch = "";
if($_SESSION['MemberStatusFilterR']){
	if(empty($SearchQuery)){
		$SearchQuery .= "MemberStatus IN (".$_SESSION['MemberStatusFilterR'].")";
		$TotalSearch = " MemberStatus  IN (".$_SESSION['MemberStatusFilterR'].")";
	}else{
		$TotalSearch = " MemberStatus IN (".$_SESSION['MemberStatusFilterR'].")";
		$SearchQuery .= "AND MemberStatus IN (".$_SESSION['MemberStatusFilterR'].")";

}
}
// echo $DateQueryTable."*****";



$DateQueryTable = 'MemberPurchasedShare';
if(empty($SearchQuery) || empty($TotalSearch)){
   if(!empty($_SESSION['ToDate']) &&  !empty($_SESSION['FromDate'])){
  $SearchDate = " STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s')>=STR_TO_DATE('".date('Y-m-d',strtotime($_SESSION['FromDate']))." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s')<=STR_TO_DATE('".date('Y-m-d',strtotime($_SESSION['ToDate']))." 23:59:59', '%Y-%m-%d %H:%i:%s')";
  $SearchDatelead = " STR_TO_DATE(MemberLead.Created, '%Y-%m-%d %H:%i:%s')>=STR_TO_DATE('".date('Y-m-d',strtotime($_SESSION['FromDate']))." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(MemberLead.Created, '%Y-%m-%d %H:%i:%s')<=STR_TO_DATE('".date('Y-m-d',strtotime($_SESSION['ToDate']))." 23:59:59', '%Y-%m-%d %H:%i:%s')";
	
	}
}else{
    if(!empty($_SESSION['ToDate']) &&  !empty($_SESSION['FromDate'])){
	 $SearchDate = " AND STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s')>=STR_TO_DATE('".date('Y-m-d',strtotime($_SESSION['FromDate']))." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(MemberPurchasedShare.Created, '%Y-%m-%d %H:%i:%s')<=STR_TO_DATE('".date('Y-m-d',strtotime($_SESSION['ToDate']))." 23:59:59', '%Y-%m-%d %H:%i:%s')";
	$SearchDatelead .= " AND STR_TO_DATE(MemberLead.Created, '%Y-%m-%d %H:%i:%s')>=STR_TO_DATE('".date('Y-m-d',strtotime($_SESSION['FromDate']))." 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(MemberLead.Created, '%Y-%m-%d %H:%i:%s')<=STR_TO_DATE('".date('Y-m-d',strtotime($_SESSION['ToDate']))." 23:59:59', '%Y-%m-%d %H:%i:%s')";
	}
} 



/*------checking for search varibles--------*/
if($TotalSearch  ||  $SearchDate){
  if($TotalSearch)
  $searcharea='AND '.$TotalSearch.' '.$SearchDate.'';
  else
  $searcharea='AND '.$TotalSearch.' '.$SearchDate.'';
}
 
if($TotalSearch  ||  $SearchDatelead) {
	 
  if($TotalSearch)
   $SearchDateleadarea='AND '.$TotalSearch.' '.$SearchDatelead.'';
   else
  $SearchDateleadarea='AND '.$TotalSearch.' '.$SearchDatelead.'';
 }
  
/*------Ends--------*/
   
  

		  
if($_SESSION['page'] > 0 && !isset($_REQUEST['page'])){

  $page = $_SESSION['page'] ;

  $_SESSION['page'] = "";

  unset($_SESSION['page']);

}elseif(!isset($_REQUEST['page'])) {

  $page=1;

  $_SESSION['page'] = 1 ;

} else {

  $page=$_REQUEST['page'];

  $_SESSION['page'] = $page; 

}


//$SearchQuery .=" ( AweberList <> '' AND AweberList <> 'NULL' AND `AppCode` <> '' AND `AppCode` <> 'NULL' AND member_aweber_code <> '' ) AND StopDisplay <> 1 AND HasCredit = 1 ";
if(empty($SearchQuery)){
$SearchQuery .=" StopDisplay <> 1 ";
}else{
$SearchQuery .=" AND StopDisplay <> 1";
}
//echo $SearchQuery;
$sortText = 'GROUP BY MemberPurchasedShare.MemberID ORDER BY Member.ID DESC';
$info_arrayTotal = $objMember->GetAllMemberFromCoOp("$SearchQuery $sortText",array("Member.*"));
$rows2 = $objMember->GetAllMemberFromCoOp(" $SearchQuery $sortText",array("Member.ID","NextDispalyCounter","AweberList","AppId","AppCode","Surname", "Email", "FirstName", "WMILevel", "MemberStatus"));
if(isset($_GET['view']))
{
	unset($_SESSION['FromDate']);
	unset($_SESSION['ToDate']);
}
else
{

$rows = $objMember->GetAllMemberFromCoOp(" $SearchQuery $sortText ",array("Member.ID","NextDispalyCounter","AweberList","AppId","AppCode","Surname", "Email", "FirstName", "WMILevel", "MemberStatus")); 

$total_records =  count($rows);   
 if(!empty($_SESSION['FromDate']))
 {

$rows1 = $objMember->GetAllMemberFromCoOp(" $SearchQuery $searcharea $sortText ",array("Member.ID","NextDispalyCounter","AweberList","AppId","AppCode","Surname", "Email", "FirstName", "WMILevel", "MemberStatus"));
$total_records =  count($rows1);       
 }
}

if(!isset($_REQUEST['export'])){
 if(!isset($_SESSION['limit'])){

	$limit = 10 ;

} else if($_SESSION['limit'] =="all" ){

	$limit = $total_records;

} else {

	$limit = $_SESSION['limit'] ;

}
}
else {
$limit = $total_records;
}

$ret = $objMember->getPagerData($total_records , $limit, $page);

$offset = $ret->offset;

if( $offset < 1 ){
	$offset = 0;
}
$rows2 = $objMember->GetAllMemberFromCoOp(" $SearchQuery $sortText",array("Member.ID","NextDispalyCounter","AweberList","AppId","AppCode","Surname", "Email", "FirstName", "WMILevel", "MemberStatus"));  
//LIMIT $offset,$limit
if(isset($_GET['view']))
{
	unset($_SESSION['FromDate']);
		 unset($_SESSION['ToDate']);
}
else
{
$rows = $objMember->GetAllMemberFromCoOp(" $SearchQuery $sortText LIMIT $offset,$limit",array("Member.ID","NextDispalyCounter","AweberList","AppId","AppCode","Surname", "Email", "FirstName", "WMILevel", "MemberStatus")); 
 
 if(!empty($_SESSION['FromDate']))
 {

$rows1 = $objMember->GetAllMemberFromCoOp(" $SearchQuery $searcharea $sortText LIMIT $offset,$limit",array("Member.ID","NextDispalyCounter","AweberList","AppId","AppCode","Surname", "Email", "FirstName", "WMILevel", "MemberStatus"));    
 }
 //print_r($rows); 
 
}

 
 
/*--------------get pagewise ids------------*/
if(is_array($rows2)){
 foreach($rows2 as $rotator ){
  $MemberIdIN[]=$rotator['ID'];
 }
  $PagewiseMemberIds=implode(',',$MemberIdIN);
}

/*-------------end-----------*/



 if($_REQUEST['Task']=='Delete'){
  	$delete_query = " DELETE FROM MemberPurchasedShare WHERE ID='".$_REQUEST['id']."'";
	mysql_query($delete_query)or die('Query '.$delete_query.' has Some Errors '.mysql_error());
}  

if($_REQUEST['Task']=='Add'){
	$query="INSERT INTO MemberPurchasedShare (ClassName, Created,  Description, MemberID, ContactID, AdjustmentAmount, Type) VALUES('MemberPurchasedShare', '".date("Y-m-d")."', '".$_REQUEST['Decription']."', '".$_REQUEST['userId']."', '".$_REQUEST['contact']."', '".$_REQUEST['Amount']."' ,'".$_REQUEST['Type']."')";
	mysql_query($query)or die('Query '.$update_query.' has Some Errors '.mysql_error());
}

		//Start for total Purchase	
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
				$query_credit_share_available  = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond  $searcharea AND TYPE IN ( 1 ) LIMIT 0 , 1";
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
				$query_credit_adjustment 	= "SELECT SUM(AdjustmentAmount) AS Adjustments FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond $searcharea AND Type = 2 LIMIT 0,1";
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
				$query_credit_share_bonus = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond $searcharea  AND TYPE IN ( 3,4 ) LIMIT 0,1";
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
				$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond $SearchDateleadarea AND IsAdmin = 0 LIMIT 0,1";
			
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
			$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond $SearchDateleadarea AND IsAdmin = 0 LIMIT 0,1";
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
		
		$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond $SearchDateleadarea AND IsAdmin = 0 LIMIT 0,1";
		 
		 $result_credit_used = mysql_query($query_credit_used) or die("Query '$query_credit_used' failed with error message: \"" . mysql_error () . '"');
		 $row_credit_used    = mysql_fetch_array($result_credit_used);
		 $total_credit_used = $row_credit_used['AmountUsed'];
		 if(empty($total_credit_used))$total_credit_used=0;

			  return $total_credit_used;
}			 //End for total Balance used

function GetMemberAdjustmentPurchasedN1($MemberID = 0 ,$without_date = ''){
		
		global $searcharea,$PagewiseMemberIds,$member_ids_in_date_range; //$TotalSearch, $SearchDate;
		$memberidcond = '';
		
		if($MemberID == 0){
			
			if(!empty($member_ids_in_date_range) && $without_date==1)
			{
				$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$member_ids_in_date_range.') And Member.StopDisplay <> 1 '; 
			}else
			{
				$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$PagewiseMemberIds.') And Member.StopDisplay <> 1 ';
			}
			
		}else{
			$memberidcond = 'Member.ID = '.$MemberID;
		}
		
		 
	
	 $query_credit_share_available  = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond   AND TYPE IN ( 1 ) LIMIT 0 , 1";
	 
	  //die();
			 $result_credit_share_available = mysql_query($query_credit_share_available) or die("Query '$query_credit_share_available' failed with error message: \"" . mysql_error () . '"');
			 
			 $row_credit_share_available    = mysql_fetch_array($result_credit_share_available);
			 
			 
           $query_credit_adjustment 	= "SELECT SUM(AdjustmentAmount) AS Adjustments FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond  AND Type = 2 LIMIT 0,1";
			 
			 $result_credit_adjustment 	= mysql_query($query_credit_adjustment) or die("Query '$query_credit_adjustment' failed with error message: \"" . mysql_error () . '"');
			 $row_credit_adjustment    	= mysql_fetch_array($result_credit_adjustment);


			 $available_balance 		= $row_credit_share_available['ShareAmountAvailable']-$row_credit_adjustment['Adjustments'];
			 
			 
			 if(empty($available_balance)){$available_balance=0;}
             return $available_balance;
}//End for total Purchase
		
		
		
		//Start for total Bonus
function GetMemberBonusOtherN1($MemberID = 0 ,$without_date = ''){
		global $searcharea,$PagewiseMemberIds,$member_ids_in_date_range; //$TotalSearch, $SearchDate;
		$memberidcond = '';
		
		if($MemberID == 0){
			if(!empty($member_ids_in_date_range) && $without_date==1)
			{
				$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$member_ids_in_date_range.') And Member.StopDisplay <> 1 '; 
			}else
			{
				$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$PagewiseMemberIds.') And Member.StopDisplay <> 1 ';
			}
			
		}else{
			$memberidcond = 'Member.ID = '.$MemberID;
		}
		
			 $query_credit_share_bonus = "SELECT SUM( AdjustmentAmount ) AS ShareAmountAvailable FROM  MemberPurchasedShare INNER JOIN Member ON MemberPurchasedShare.MemberID = Member.ID WHERE $memberidcond   AND TYPE IN ( 3,4 ) LIMIT 0,1";
			 $result_query_credit_share_bonus = mysql_query($query_credit_share_bonus) or die("Query '$query_credit_share_bonus' failed with error message: \"" . mysql_error () . '"');
			 $row_result_query_credit_share_bonus   = mysql_fetch_array($result_query_credit_share_bonus);

			 $available_bonus		= $row_result_query_credit_share_bonus['ShareAmountAvailable'];
			 if(empty($available_bonus))$available_bonus=0;

			 return $available_bonus;
}//End for total Bonus	


//Start for total Balance used
function GetMemberUsedCredit1($MemberID = 0){
		global $SearchDatelead, $TotalSearch,$PagewiseMemberIds, $SearchDateleadarea,$member_ids_in_date_range; //$SearchDate, $SearchDatelead;
		$memberidcond = '';
		
		if($MemberID == 0){
			
				$memberidcond = 'Member.ID != 0 AND Member.ID IN('.$PagewiseMemberIds.') And Member.StopDisplay <> 1 ';
			
		}else{
			$memberidcond = 'Member.ID = '.$MemberID;
		}
		
		 $query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond  AND IsAdmin = 0 LIMIT 0,1";
		 //die();
		 $result_credit_used = mysql_query($query_credit_used) or die("Query '$query_credit_used' failed with error message: \"" . mysql_error () . '"');
		 $row_credit_used    = mysql_fetch_array($result_credit_used);
		 $total_credit_used = $row_credit_used['AmountUsed'];
		 if(empty($total_credit_used))$total_credit_used=0;

			  return $total_credit_used;
}			 //End for total Balance used


function GetMemberCancelAmt1($MemberID = 0, $without_date=''){
		global $SearchDatelead, $TotalSearch,$PagewiseMemberIds, $SearchDateleadarea,$member_ids_in_date_range; //$SearchDate, $SearchDatelead;
		$memberidcond = '';
		
		if($MemberID == 0){
			if(!empty($member_ids_in_date_range) && $without_date==1)
			{
				$memberidcond = 'Member.ID != 0 And Member.ID IN('.$member_ids_in_date_range.') And Member.MemberStatus="canceled" And StopDisplay <> 1';
			}else
			{
				$memberidcond = 'Member.ID != 0 And Member.ID IN('.$PagewiseMemberIds.') And Member.MemberStatus="canceled" And StopDisplay <> 1';
			}
			
			
		}else{
			$memberidcond = 'Member.ID = '.$MemberID.'And MemberStatus="canceled" ';
		}
		
		$query_credit_used  = "SELECT SUM(AmountCharged) AS AmountUsed FROM  MemberLead INNER JOIN Member ON MemberLead.MemberID = Member.ID WHERE $memberidcond  AND IsAdmin = 0 LIMIT 0,1";
		 $result_credit_used = mysql_query($query_credit_used) or die("Query '$query_credit_used' failed with error message: \"" . mysql_error () . '"');
		 $row_credit_used    = mysql_fetch_array($result_credit_used);
		 $total_credit_used = $row_credit_used['AmountUsed'];
		 if(empty($total_credit_used))$total_credit_used=0;

	    return $total_credit_used;
}//End for total Bonus	




			unset($Total_Array);
			unset($Total_Array1);
			unset($Total_Array2);
			 
			 
			
			 $Total_Array[0] = GetMemberAdjustmentPurchasedN(0,'','','','','','',1); 
			 $Total_Array[1] = GetMemberBonusOtherN();
			 $Total_Array[2] = GetMemberUsedCredit(0,'','','','','',1);
	 		 $Total_Array[3] = (($Total_Array[0]+ $Total_Array[1]) - $Total_Array[2]);
			 $Total_Array[5] = $Total_Array[0]+ $Total_Array[1];
			 $Total_Array[6]=GetMemberCancelAmt();
			 //print_r($member_ids_in_date_range);
			 $Total_Array[7]=GetMemberUsedCreditByDuration();
			 
			 
			 $Total_Array1[0] = GetMemberAdjustmentPurchasedN1(); 
			 $Total_Array1[1] = GetMemberBonusOtherN1();
			 $Total_Array1[2] = GetMemberUsedCredit1();
	 		 $Total_Array1[3] = (($Total_Array1[0]+ $Total_Array1[1]) - $Total_Array1[2]);
			 $Total_Array1[5] = $Total_Array1[0]+ $Total_Array1[1];
			 $Total_Array1[6]=GetMemberCancelAmt1();
			 
			 $Total_Array2[0] = GetMemberAdjustmentPurchasedN1(0,1); 
			 $Total_Array2[1] = GetMemberBonusOtherN1(0,1);
			 $Total_Array2[2] = GetMemberUsedCredit(0,'','','','','',1,1);
	 		 $Total_Array2[3] = (($Total_Array2[0]+ $Total_Array2[1]) - $Total_Array2[2]);
			 $Total_Array2[5] = $Total_Array2[0]+ $Total_Array2[1];
			 $Total_Array2[6]=GetMemberCancelAmt1(0,1);
			 //print_r($member_ids_in_date_range);
			 
			 				 
/*if(isset($_REQUEST['export']) && ($_REQUEST['export'] == 2)){
include '../admintti/ExportTopCoopShareReport.php';
exit;
}*/
			 
?>
<script type="text/javascript" src="../../js/search.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/search.css" />
<link href="<?php echo SITE_ADDRESS;?>/co_op/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/Tooltip/js/tooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/Tooltip/css/tooltip.css" media="screen" />
<script type="text/javascript">
		$(function() {
			$("#fromdatepicker").datepicker();
		});		
		$(function() {
			$("#todatepicker").datepicker();
		});
	$('#date_selection').click(function(){
		alert($(this).val());
		
	});
</script>
<script type="text/javascript">

	$(document).ready(function() {
	$("#addmember_sponsor").fancybox();
		<?php foreach((array)$rows as $PRow){
		//print_r($PRow);?>
		$("#various<?php echo $PRow['ID'];?>").fancybox({

                               'width'                         : 900,

                               'height'							: 515,

                               'autoScale'                   : false,

                               'transitionIn'                : 'none',

                               'transitionOut'                : 'none',

                               'href'                        : this.href,

                               'type'                        : 'iframe',

							   'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); } 

               });

				

		$("#shares<?php echo $PRow['ID'];?>").fancybox();

		$("#member<?php echo $PRow['ID'];?>").fancybox();

		$("#lead<?php echo $PRow['ID'];?>").fancybox();

		<?php } ?>
		
		$("#date_selection").click(function(){
		 if($(this).is(':checked')){
	      $("#fromdatepicker").removeAttr("disabled");
		  $("#todatepicker").removeAttr("disabled");
		  
		 }
		
		 else{
		   $("#fromdatepicker").attr("disabled",true);
		   $("#todatepicker").attr("disabled",true);
		   $("#fromdatepicker").val('');
		   $("#todatepicker").val('');
		   }
		

		});

		

	});

function confirmation() {

	var answer = confirm("Do you want to delete this record?");

	if(answer){
		return true;
	}else{
		return false;
	}
}		

</script>
<div id="headtitle"> E commerce Co-Op Shares Report</div>

<?php 
if(!empty($_SESSION['FromDate']) && !empty($_SESSION['ToDate']))
$DateInterval = date("M d",strtotime($_SESSION['FromDate']))." to ".date("M d",strtotime($_SESSION['ToDate'])); 

?>
<div class="filtercontainer">
  <form name="MemberSearchForm" id="MemberSearchForm" action="?action=SetFilter" method="post">
    <table align="left" cellpadding="0" cellspacing="0" width="100%" border="0">
      <tr>
        <td align="left"><div class="adv_search">
            <div class="adv_search_sub">
              <div class="input_box">
                <input name="SearchText" id="mainsearch" class="input_box_2" type="text" value="<?php echo $_SESSION['SearchText'];?>" />
                 <div id="show_options" class="aoo" tabindex="0" gh="sda" role="button" aria-label="Show search options" data-tooltip="Show search options" style="">
                  <div class="aoq"></div>
                </div>
              </div>
              <div class="adv_btn">
                <input type="submit" class="adv_btn_2" value="&nbsp;" name="filter" />
              </div>
            </div>
            <div style="color:#235793; font-size:18px; margin-top:11px;"><?php echo $DateInterval; ?>
            <input type="button" class="monthly_btn" value="&nbsp;" onclick="setview('month');" id="monthly" style="margin-right:8px;" /><input type="button" class="weekly_btn" value="&nbsp;" onclick="setview('week');" style="margin-right:8px;" />
            <a id="MembersSheet" target="_blank" href="<?php if(isset($_GET['view'])){ if($_GET['view']=='month'){ echo "exportcoopshare.php?view=month"; }elseif($_GET['view']=='week'){ echo "exportcoopshare.php?view=week"; } }else{ echo "../../ExportMemberCoopShareReport.php?export=1&".$_SERVER['QUERY_STRING']; } ?>" style="float:right;" ><img src="/admintti/images/icon_download_excel.png" border="0" title="Export Excel Sheet" /></a></div>
            <?php if(isset($_GET['view'])){}else{ ?>
			<script>
			$('#MembersSheet').fancybox();
			</script>
			<?php }?>
            <script>
				
				function setview(val)
				{
					$('#MemberSearchForm').attr('action','?view='+val);
					$('#MemberSearchForm').submit();
					
				}
			</script>
            <div class="cate_main" id="cate_main" style="display:none;position:absolute; z-index: 100000;">
              <div id="search_close" tabindex="0" role="button" class="Zy"></div>
              <p>
              <div class="search_row">
                <input type="text" name="SearchText" class="in_put2" id="changename" value="<?php echo $_SESSION['SearchText'];?>" />
              </div>
              <?php /*?><div class="search_row">&nbsp;AC:<br />
                <select name="AC" class="in_put2" style="width:307px;">
                  <option value="">All</option>
                  <option value="YES" <?php if($_SESSION['AC'] == "YES" )echo "selected";?>>Yes</option>
                  <option value="NO" <?php if($_SESSION['AC'] == "NO" )echo "selected";?>>No</option>
                </select>
              </div><?php */?>
              <br />
              <div class="search_row">
                <table cellpadding="0" cellspacing="0" width="95%" border="0">
                  <tr>
                    <td><input type="checkbox" name="date_selection" id="date_selection" value="0"   <?php if(isset($_SESSION['FromDate']) && isset($_SESSION['FromDate'])) echo "checked"; ?>/>From:<br />
                      <input type="text" id="fromdatepicker" name="FromDate" size="8" style="padding: 1px 4px; width: 90%;" <?php if(empty($_SESSION['FromDate'])) {?> disabled="disabled" <?php } ?> value="<?php echo $_SESSION['FromDate'];?>" />
                    </td>
                    <td valign="bottom"> To:<br />
                      <input type="text" id="todatepicker" name="ToDate" size="8" style="padding: 1px 4px; width: 90%;" <?php if(empty($_SESSION['ToDate'])) {?>disabled="disabled" <?php } ?>  value="<?php echo $_SESSION['ToDate'];?>"/>
                    </td>
                  </tr>
                </table>
              </div>
              <br />
              <!-- <div class="search_row">&nbsp;&nbsp;&nbsp;Spend but now at $0:<br />
                      <select name="AtZero" class="in_put2_1" style="width:307px;">
                        <option value="">All</option>
                        <option value="YES" <?php if($_SESSION['AtZero'] == "YES" )echo "selected";?>>Yes</option>
                        <option value="NO" <?php if($_SESSION['AtZero'] == "NO" )echo "selected";?>>No</option>
                      </select>
                    </div>-->
                    <br />
              <div class="search_row">&nbsp;Member Status:<br />
               <div style="margin-left:-8px; height:100px;width:230px;overflow:auto;border:1px solid #CCCCCC;" id="ScrollCB">
             
                  <input  type="checkbox" value="1" name="MemberStatusFilterRall" id="MemberStatusFilterRall">All <br/>
                  <input  type="checkbox" value="'active'" name="MemberStatusFilterR[]"  
				  <?php if(in_array("'active'",$_SESSION['MemberStatusArray']))echo "checked";?>>Active<br/>
                  <input  type="checkbox" value="'canceled'" name="MemberStatusFilterR[]" 
				  <?php if(in_array("'canceled'",$_SESSION['MemberStatusArray']))echo "checked";?>>Canceled<br/>
                  <input  type="checkbox" value="'paused'" name="MemberStatusFilterR[]"
                  <?php if(in_array("'paused'",$_SESSION['MemberStatusArray']))echo "checked";?> >Paused<br/>
                  <input  type="checkbox" value="'credit card failed'" name="MemberStatusFilterR[]" 
                  <?php if(in_array("'credit card failed'",$_SESSION['MemberStatusArray'])) echo "checked";?>>credit card failed<br/>
              
                  </div>
                  
                  <!--<option value="active" <?php if($_SESSION['MemberStatusFilterR'] == "active" )echo "selected";?>>Active</option>
                  <option value="canceled" <?php if($_SESSION['MemberStatusFilterR'] == "canceled" )echo "selected";?>>Canceled</option>
                  <option value="paused" <?php if($_SESSION['MemberStatusFilterR'] == "paused" )echo "selected";?>>Paused</option>
                  <option value="credit card failed" <?php if($_SESSION['MemberStatusFilterR'] == "credit card failed" )echo "selected";?>>credit card failed</option>-->
                  
                <!--<select name="MemberStatusFilterR" class="in_put2" style="width:307px;">
                  <option value="">All</option>
                  <option value="active" <?php if($_SESSION['MemberStatusFilterR'] == "active" )echo "selected";?>>Active</option>
                  <option value="canceled" <?php if($_SESSION['MemberStatusFilterR'] == "canceled" )echo "selected";?>>Canceled</option>
                  <option value="paused" <?php if($_SESSION['MemberStatusFilterR'] == "paused" )echo "selected";?>>Paused</option>
                  <option value="credit card failed" <?php if($_SESSION['MemberStatusFilterR'] == "credit card failed" )echo "selected";?>>credit card failed</option>
                </select>-->
              </div>
              <div style="margin-bottom:5px;">
                <input type="submit" class="adv_btn_2" style="float:right; margin-right: 25px;" value="" align="absmiddle" border="0" />
                <div style="clear:both;"></div>
              </div>
            </div>
          </div></td>
      </tr>
    </table>
  </form>
  <div style="clear:both;"></div>
  <script type="text/javascript">

$('#mainsearch').focus(function(){

	jQuery("#cate_main").hide();

	jQuery("#show_options").show();

	$('#changename').attr('name', ''); 

});

</script>
</div>
<div class="subcontainer" style="margin-bottom:10px;"> 
<div align="center" style="color:#993300;">Total Data</div>

  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr id="headerbar">
      <td>Total Purchased </td>
      <td>Total Bonus</td>
      <td>Total Purchase & Bonus</td>
      
      <td>Total Lead Spend</td>
      <td>Total Balance</td>
      <td>Canceled Members</td>
      <td>Total Leads Owed</td>
      <td>&nbsp;</td>
    </tr>
    <tr id="row-tan">
      
      <td>$<?php echo number_format($Total_Array1[0], 2);?></td>
      <td>$<?php echo number_format($Total_Array1[1], 2);?></td>
      <td>$<?php echo number_format($Total_Array1[5], 2);?></td>
       
      <td>$<?php echo number_format($Total_Array1[2], 2);?></td>
      <td>$<?php echo number_format($Total_Array1[3], 2);?></td>
      <td style="color:#FF0000;">$<?php echo number_format($Total_Array1[6],2);?></td>
      <td>$<?php echo number_format(($Total_Array1[3]-$Total_Array1[6]), 2);?></td>
      <td><a id="MembersSheet1" href="../../ExportTopCoopShareReport.php?export=2" style="float:right;" ><img src="/admintti/images/icon_download_excel.png" border="0" title="Export Excel Sheet" /></a></td>
      <script>
				$("#MembersSheet1").fancybox();
	  </script>
    </tr>
  </table>
</div>
<?php if(isset($_GET['view']))
{}else{?>
<div class="subcontainer" style="margin-bottom:10px;"> 
<div align="center" style="color:#993300;">Data By duration.</div>

  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr id="headerbar">
      <td>Total Purchased </td>
      <td>Total Bonus</td>
      <td>Total Purchase & Bonus</td>
      <td>Total Lead Spend</td>
      <td>Total Balance</td>
      <td>Canceled Members</td>
      <td>Total Leads Owed</td>
      <td>&nbsp;</td>
    </tr>
    <tr id="row-tan">
      
      <td>$<?php echo number_format($Total_Array[0], 2);?></td>
      <td>$<?php echo number_format($Total_Array[1], 2);?></td>
      <td>$<?php echo number_format($Total_Array[5], 2);?></td>
       
      <td>$<?php echo number_format($Total_Array[2], 2);?></td>
      <td>$<?php echo number_format($Total_Array[3], 2);?></td>
      <td style="color:#FF0000;">$<?php echo number_format($Total_Array[6],2);?></td>
      <td>$<?php echo number_format(($Total_Array[3]-$Total_Array[6]), 2);?></td>
      <td></td>
      <script>
				$("#MembersSheet1").fancybox();
	  </script>
    </tr>
  </table>
</div>
<?php }?>
<?php if(isset($_GET['view'])){
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
	if($_GET['view']=='month')
	{
		?>
<div class="subcontainer" style="margin-bottom:10px;"> 
	<div align="center" style="color:#993300;">Data By Month</div>
  		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
    		<tr id="headerbar">
      			<td>Total Purchased </td>
      			<td>Total Bonus</td>
      			<td>Total Purchase & Bonus</td>
      			<td>Total Lead Spend</td>
      			<td>Total Balance</td>
      			<td>Canceled Members</td>
      			<td>Total Leads Owed</td>
      			<td>&nbsp;</td>
    		</tr>
<?php
$start = $month = $first_date_time;
$end = strtotime("+1 month", $last_date_time);
while($month < $end)
{
	$j=date("m",$month);
	$i=date("Y",$month); 

				 ?>
      		<tr>
      			<td colspan="7">For the month and year (<?php echo $j.'/'.$i; ?>)</td>
      		</tr>           
			<tr id="row-tan">
      			<td>$<?php echo number_format(GetMemberAdjustmentPurchasedN(0,'month',$j,$i),2);?></td>
      			<td>$<?php echo number_format(GetMemberBonusOtherN(0,'month',$j,$i),2);?></td>
      			<td>$<?php echo number_format((GetMemberAdjustmentPurchasedN(0,'month',$j,$i)+GetMemberBonusOtherN(0,'month',$j,$i)),2);?></td>
      			<td>$<?php echo number_format(GetMemberUsedCredit(0,'month',$j,$i),2);?></td>
      			<td>$<?php echo number_format(((GetMemberAdjustmentPurchasedN(0,'month',$j,$i)+GetMemberBonusOtherN(0,'month',$j,$i))-GetMemberUsedCredit(0,'month',$j,$i)),2);?></td>
      			<td style="color:#FF0000;">$<?php echo number_format(GetMemberCancelAmt(0,'month',$j,$i),2);?></td>
      			<td>$<?php echo number_format((((GetMemberAdjustmentPurchasedN(0,'month',$j,$i)+GetMemberBonusOtherN(0,'month',$j,$i))-GetMemberUsedCredit(0,'month',$j,$i))-GetMemberCancelAmt(0,'month',$j,$i)),2);?></td>
      			<td><a href="ecommerceCoOpSharesReport.php?action=SetFilter&month=<?php echo $j; ?>&year=<?php echo $i; ?>">Details</a></td>
    		</tr> <?php $month = strtotime("+1 month", $month); }?>
             <?php
		
?>
		</table>
	</div>
<?php 
	}elseif($_GET['view']=='week')
	{ ?>
<div class="subcontainer" style="margin-bottom:10px;"> 
	<div align="center" style="color:#993300;">Data By Week</div>
    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr id="headerbar">
            <td>Total Purchased </td>
            <td>Total Bonus</td>
            <td>Total Purchase & Bonus</td>
            <td>Total Lead Spend <br/>&nbsp;By All Members</td>
            <td>Total Balance</td>
            <td>Canceled Members</td>
            <td>Total Leads Owed</td>
            <td>&nbsp;</td>
        </tr>
<?php
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
?>
		<tr>
            <td colspan="7">For the week (<?php echo date('d-m-Y',$fisrdate_time).' to '.date('d-m-Y',strtotime("-1 day",$next_week_time)); ?>)</td>
        </tr>
        <tr id="row-tan">
            <td>$<?php echo number_format($total_purchase_week=GetMemberAdjustmentPurchasedN(0,'week','','',date('d-m-Y',$fisrdate_time),date('d-m-Y',strtotime("-1 day",$next_week_time))),2);?></td>
           <td>$<?php echo number_format($total_bonus_week=GetMemberBonusOtherN(0,'week','','',date('d-m-Y',$fisrdate_time),date('d-m-Y',strtotime("-1 day",$next_week_time))),2);?></td>
             <td>$<?php echo number_format($total_purchase_bonus_week=($total_purchase_week+$total_bonus_week),2);?></td>
             <td>$<?php echo number_format($total_lead_spend_week=GetMemberUsedCredit(0,'week','','',date('d-m-Y',$fisrdate_time),date('d-m-Y',strtotime("-1 day",$next_week_time))),2);?></td>
            <td>$<?php echo number_format($total_balance_week=($total_purchase_bonus_week-$total_lead_spend_week),2);?></td>
            <td style="color:#FF0000;">$<?php echo number_format($total_member_cancel_week=GetMemberCancelAmt(0,'week','','',date('d-m-Y',$fisrdate_time),date('d-m-Y',strtotime("-1 day",$next_week_time))),2);?></td>
            <td>$<?php echo number_format($total_leads_owed_week=($total_balance_week-$total_member_cancel_week),2);?></td>
            <td><a href="ecommerceCoOpSharesReport.php?action=SetFilter&date_start=<?php echo $fisrdate_time; ?>&date_end=<?php echo strtotime("-1 day",$next_week_time); ?>">Details</a></td>
            <?php $total_purchase_week_all=$total_purchase_week_all+$total_purchase_week;
			$total_bonus_week_all=$total_bonus_week_all+$total_bonus_week;
			$total_lead_spend_week_all=$total_lead_spend_week_all+$total_lead_spend_week;
			$total_member_cancel_week_all=$total_member_cancel_week_all+$total_member_cancel_week;
			 ?>
        </tr> 
<? 
for($i=$next_week_time;$i<=$last_week_time;$i=strtotime("+7 day", $i)){
		  
		  ?>
        <tr>
            <td colspan="7">For the week (<?php echo date('d-m-Y',$i).' to '.date('d-m-Y',strtotime("+6 day", $i)); ?>)</td>
        </tr> 
        <tr id="row-tan">
            <td>$<?php echo number_format($total_purchase_week=GetMemberAdjustmentPurchasedN(0,'week','','',date('d-m-Y',$i),date('d-m-Y',strtotime("+6 day", $i))),2);?></td>
            <td>$<?php echo number_format($total_bonus_week=GetMemberBonusOtherN(0,'week','','',date('d-m-Y',$i),date('d-m-Y',strtotime("+6 day", $i))),2);?></td>
            <td>$<?php echo number_format($total_purchase_bonus_week=($total_purchase_week+$total_bonus_week),2);?></td>
            <td>$<?php echo number_format($total_lead_spend_week=GetMemberUsedCredit(0,'week','','',date('d-m-Y',$i),date('d-m-Y',strtotime("+6 day", $i))),2);?></td>
            <td>$<?php echo number_format($total_balance_week=($total_purchase_bonus_week-$total_lead_spend_week),2);?></td>
            <td style="color:#FF0000;">$<?php echo number_format($total_member_cancel_week=GetMemberCancelAmt(0,'week','','',date('d-m-Y',$i),date('d-m-Y',strtotime("+6 day", $i))),2);?></td>
            <td>$<?php echo number_format($total_leads_owed_week=($total_balance_week-$total_member_cancel_week),2);?></td>
            <td><a href="ecommerceCoOpSharesReport.php?action=SetFilter&date_start=<?php echo $i; ?>&date_end=<?php echo strtotime("+6 day", $i); ?>">Details</a></td>
            <?php $total_purchase_week_all=$total_purchase_week_all+$total_purchase_week;
			 $total_bonus_week_all=$total_bonus_week_all+$total_bonus_week;
			 $total_lead_spend_week_all=$total_lead_spend_week_all+$total_lead_spend_week;
			 $total_member_cancel_week_all=$total_member_cancel_week_all+$total_member_cancel_week;
			 ?>
           
        </tr>
<?php } ?>
<tr>
            <td colspan="7">For the week (<?php echo date('d-m-Y',$i).' to '.date('d-m-Y',$lastdate_time);?>)</td>
        </tr> 
        <tr id="row-tan">
            <td>$<?php echo number_format($total_purchase_week=GetMemberAdjustmentPurchasedN(0,'week','','',date('d-m-Y',$i),date('d-m-Y',$lastdate_time)),2);?></td>
            <td>$<?php echo number_format($total_bonus_week=GetMemberBonusOtherN(0,'week','','',date('d-m-Y',$i),date('d-m-Y',$lastdate_time)),2);?></td>
            <td>$<?php echo number_format($total_purchase_bonus_week=($total_purchase_week+$total_bonus_week),2);?></td>
            <td>$<?php echo number_format($total_lead_spend_week=GetMemberUsedCredit(0,'week','','',date('d-m-Y',$i),date('d-m-Y',$lastdate_time)),2);?></td>
            <td>$<?php echo number_format($total_balance_week=($total_purchase_bonus_week-$total_lead_spend_week),2);?></td>
            <td style="color:#FF0000;">$<?php echo number_format($total_member_cancel_week=GetMemberCancelAmt(0,'week','','',date('d-m-Y',$i),date('d-m-Y',$lastdate_time)),2);?></td>
            <td>$<?php echo number_format($total_leads_owed_week=($total_balance_week-$total_member_cancel_week),2);?></td>
            <td><a href="ecommerceCoOpSharesReport.php?action=SetFilter&date_start=<?php echo $i; ?>&date_end=<?php echo $lastdate_time; ?>">Details</a></td>
           <?php $total_purchase_week_all=$total_purchase_week_all+$total_purchase_week; 
		   $total_bonus_week_all=$total_bonus_week_all+$total_bonus_week;
		   $total_lead_spend_week_all=$total_lead_spend_week_all+$total_lead_spend_week;
		   $total_member_cancel_week_all=$total_member_cancel_week_all+$total_member_cancel_week;
		   ?> 
        </tr>                  
       <tr  >
       
       
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td align="left"><div style="margin-bottom:5px; margin-top:5px;">Total Purchas</div>
       <div align="center" style="text-align:left;font-weight:bold; width:100px;">$<?php echo number_format($total_purchase_week_all,2);  ?></div>
       </td>
       <td><div style="margin-bottom:5px; margin-top:5px;">Total Bonus</div> 
       <div align="center" style="text-align:left;font-weight:bold; width:100px;">$<?php echo number_format($total_bonus_week_all,2);    ?></div>
       </td>
       <td>&nbsp;</td>
       <td>
       </td>
       <td><div style="margin-bottom:5px;margin-top:5px;">Total Spend </div>
        <div align="center" style="text-align:left;font-weight:bold; width:100px;">$<?php echo number_format($total_lead_spend_week_all,2);  ?></div>
        </td>
        <td><div style="margin-bottom:5px;margin-top:5px;">Balance Due</div>
        <div align="center" style="text-align:left;font-weight:bold; width:100px;">$<?php echo number_format($total_balance_due_week_all=(($total_purchase_week_all+$total_bonus_week_all)-$total_lead_spend_week_all),2); ?></div></td>
       </tr>
       
       <tr>
       <td colspan="6">&nbsp;</td>
       </tr>
       
       <tr>
      
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td><span style="color:#FF0000;">Cancelled members</span></td>
       <td><span style="color:#FF0000;">-$<?php echo number_format($total_member_cancel_week_all,2); ?></span></td>
       </tr>
       
       <tr>
       <td colspan="6">&nbsp;</td>
       </tr>
       
       <tr>
       
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td><span style="font-weight:bold;">Balance</span></td>
       <td><span style="font-weight:bold;">$<?php echo number_format($total_balance_due_week_all-$total_member_cancel_week_all ,2);?></span></td>
       </tr>
	</table>
</div>		
	<?php }
	}else{?>
<div class="subcontainer"> <br>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr id="headerbar">
     <!-- <td width="60">ID</td>-->
     <!-- <td align="center" title="Account Status">Status</td>-->
      <td><a href='<?=$_SERVER['PHP_SELF']?>?sortBy=Name&sortValue=<?=$sortName?>'><img src="../../images/icon_sort_order.png" title="Sort Order" border="0"/></a>
       Members
      </td>
      
          <td>Status</td>
          
    <!-- <td>Leads</td>-->
      <td>Purchased Date</td>
      
      <td><span style="float:left;">Total Purchased<?php if(!empty($_SESSION['FromDate'])){ ?> <br/>&nbsp;(by duration/Total)<?php } ?></span><!--<div style="text-transform: none;"><span title="Total Purchased<br> within date <?php echo $DateInterval; ?>" style="background-image:url(/../images/icon_question.png); margin-top:-3px;" class="toolTip"></span></div>--></td>
      
      <td>Bonus/Other<?php if(!empty($_SESSION['FromDate'])){ ?> <br/>&nbsp;(by duration/Total)<?php } ?></td>
  
      
      <td title="Purchased Summery"><span style="float:left;">Last Lead</span>
      <!--<div style="text-transform: none;"><span title="Last Lead <br> within date <?php echo $DateInterval; ?>" style="background-image:url(/../images/icon_question.png); margin-top:-3px;" class="toolTip"></span></div>-->
      </td>
             <td><span style="float:left;">Leads delivered <br/>&nbsp;By Duration<?php if(!empty($_SESSION['FromDate'])){ ?> <br/>By Members in list<?php } ?></span><!--<div style="text-transform: none;"><span title="Leads delivered<br>within date <?php echo $DateInterval; ?>" style="background-image:url(/../images/icon_question.png); margin-top:-3px;" class="toolTip"></span></div>--></td>
             
      
      <td><span style="float:left;">Total Leads <br/> &nbsp;delivered</span><!--<div style="text-transform: none;"><span title="Leads delivered<br>within date <?php echo $DateInterval; ?>" style="background-image:url(/../images/icon_question.png); margin-top:-3px;" class="toolTip"></span></div>--></td>
      

       
      
      <td><span style="float:left;">Leads owed </span><!--<div style="text-transform: none;"><span title="Leads owed<br> within date <?php echo $DateInterval; ?>" style="background-image:url(/../images/icon_question.png); margin-top:-3px;" class="toolTip"></span></div>--></td>
      
      <!--<td><div style="text-transform: none;float:left;"><span title="Total balance<br> remaining in account" style="background-image:url(/../images/icon_question.png); margin-top:-3px;" class="toolTip"></span></div><span>Total Balance</span></td>-->
    </tr>
    <?php  

  $row_flag=0;
$i = 0;
if(is_array($rows1)){
	$result_array=$rows1;
}
else
{
	$result_array=$rows;
}
if(is_array($rows)){
  foreach($result_array as $rotator ){ 
  
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


  		   $total_purchase="SELECT Created as firstdate FROM MemberPurchasedShare  where `MemberID` ='".$rotator['ID']."' AND Type = 1 ORDER BY Created  DESC  LIMIT 0,1; ";
		   
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
			$AllMembers[$i]['total_purchased'] = GetMemberAdjustmentPurchasedN($rotator['ID'],'','','','','','',1);
			$AllMembers[$i]['total_purchased_without_date'] = GetMemberAdjustmentPurchasedN($rotator['ID'],'','','','','',1);
			$AllMembers[$i]['total_bonus_credit'] = GetMemberBonusOtherN($rotator['ID']);
			$AllMembers[$i]['total_bonus_credit_without_date'] = GetMemberBonusOtherN($rotator['ID'],'','','','','',1);
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



unset($rotator);
$tot_total_purchased = $tot_total_bonus_credit = $tot_summery = $tot_lead_paid = $tot_temp_blance = $tot_actual_bal = 0;



if(is_array($AllMembers)){
 $caneclmemberTotlal=0;
  foreach($AllMembers as $rotator ){ 
   $caneclstyle='';

/*$member_tags = mysql_query("SELECT Tag_Members.ID, Tag_Members.TagID, Tag.ID, Tag.Title FROM Tag_Members LEFT JOIN Tag ON Tag_Members.TagID = Tag.ID WHERE Tag.InActive != '1' AND `Title` LIKE '%COOP%' AND Tag_Members.`MemberID`= '".$rotator['ID']."' ");
		$total_tag =  mysql_num_rows($member_tags); */			

  	if($row_flag == 1){
		$row_flag=0;
		$row_class = "row-tan";
	  }else{
		$row_flag=1;
		$row_class = "row-white";
	  } 
	  
	  if($rotator['MemberStatus']=='canceled')
	   $caneclstyle="style='color:red'";
	
	   
	  
	  ?>
    <tr id="<?php echo $row_class; ?>" <?php echo $caneclstyle; ?>>
    <!--  <td><?php echo $rotator['ID'];?></td>-->
     
      <td><a id="member<?php echo $rotator['ID'];?>" href="../../Members_info.php?id=<?php echo $rotator['ID']; ?>"><img src="../../images/icon_profile.png" align="absmiddle"  border="0" /></a> <?php echo $rotator['FirstName'];?> <?php echo $rotator['Surname'];?></td>
      
      
         <td><?php if($rotator['MemberStatus']=='active'){?>
        <img src="/admintti/images/icon_tick.png"  title="Active"/>
        <?php }elseif($rotator['MemberStatus']=='canceled'){?>
        <img src="/admintti/images/icon_cancel.png"  title="Canceled" />
        <?php } elseif($rotator['MemberStatus']=='paused'){?>
        <img src="/admintti/images/icon_error.png"  title="Paused"  />
        <?php } else { ?>
        <img src="/admintti/images/icon_flag_red.png"  title="Card Failed" />
        <?php } ?>
        </td>
      
 
     <!-- <td><a  id="various<?php echo $rotator['ID'];?>" href="RotatorListDetail.php?id=<?php echo $rotator['ID'];?>"><img src="images/icon_magnifier.png" border="0" align="absmiddle" /> <?php echo $rotator['totalleads'];?></a></td>-->
     
      <td  ><?php if($rotator['purchase_date']!="" && $rotator['purchase_date']!="00-00-0000") {echo date("<b>M d</b>, Y",strtotime($rotator['purchase_date'])); } 
	  else { echo "N/A"; }?> </td>
      
       <td>$
        <?php
	       $tot_total_purchased = $tot_total_purchased + $rotator['total_purchased'];
	       echo number_format($rotator['total_purchased'], 2).' / $'.number_format($rotator['total_purchased_without_date'], 2);?>
       </td>
       
       
         <td>$
        <?php
		  $tot_total_bonus = $tot_total_bonus + $rotator['total_bonus_credit'];
	      echo number_format($rotator['total_bonus_credit'], 2).' / $'.number_format($rotator['total_bonus_credit_without_date'], 2); ?>
       </td>
        
    
      <td><?php if($rotator['LastleadDate']!="" && $rotator['LastleadDate']!="00-00-0000") {echo  date("<b>M d</b>, Y",strtotime($rotator['LastleadDate'])); }
	  else{ echo "N/A"; }?></td>
      
        <td>$
        <?php
		  $tot_leads_byduration = $tot_leads_byduration + $rotator['members_credit_used_byduration'];
	      echo number_format($rotator['members_credit_used_byduration'], 2); ?>
       </td>
      
      <td  >$<?php 
	  		$tot_lead_paid = $tot_lead_paid + $rotator['members_credit_used'];
	        echo number_format($rotator['members_credit_used'], 2);?>
      </td>
      
     <td > $<?php 
	   $Leadsoweds=(($rotator['total_purchased_without_date']+$rotator['total_bonus_credit_without_date'])-$rotator['members_credit_used']);
	   /*-------cancel member Total--------*/
	   if($rotator['MemberStatus']=='canceled'){
	    $caneclmemberTotlal=$caneclmemberTotlal+$rotator['members_credit_used'];
	   }
	   /*--------------end---------*/
	 
	     
	       $Leadsowed=$Leadsowed+$Leadsoweds;
            echo number_format($Leadsoweds, 2);
				 
				 
				 
			?>
     </td>
     
    </tr>
    <?php }	if($row_flag == 1){
		$row_flag=0;
		$row_class = "row-tan";
	  }else{
		$row_flag=1;
		$row_class = "row-white";
	  } ?>
    
      <tr  id="<?php echo $row_class;?>" >
       
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td align="left"><div style="margin-bottom:5px; margin-top:5px;">Total Purchase <br/>(By Duration/Total)</div>
       <div align="center" style="text-align:left;font-weight:bold; width:100px;">$<?php if(!empty($_SESSION['FromDate']))
 { echo number_format($Total_Array[0], 2).' / $'.number_format($Total_Array2[0], 2); }else{ echo  number_format($Total_Array1[0], 2); } ?></div>
       </td>
       <td><div style="margin-bottom:5px; margin-top:5px;">Total Bonus <br/> (By Duration/Total)</div> 
       <div align="center" style="text-align:left;font-weight:bold; width:100px;">$<?php if(!empty($_SESSION['FromDate']))
 { echo number_format($Total_Array[1], 2).' / $'.number_format($Total_Array2[1], 2); }else{ echo  number_format($Total_Array1[1], 2); } ?></div>
       </td>
       <td>&nbsp;</td>
       <td><div style="margin-bottom:5px; margin-top:5px;">Total Lead Spen By Duration</div>
       <div align="center" style="text-align:left;font-weight:bold; width:100px;">$<?php if(!empty($_SESSION['FromDate']))
 { echo number_format($Total_Array[7], 2); }else{ echo  number_format($Total_Array1[7], 2); } ?></div>
       </td>
       <td><div style="margin-bottom:5px;margin-top:5px;">Total Spend </div>
        <div align="center" style="text-align:left;font-weight:bold; width:100px;">$<?php if(!empty($_SESSION['FromDate']))
 { echo number_format($Total_Array2[2], 2); }else{ echo  number_format($Total_Array1[2], 2); } ?></div>
        </td>
        <td><div style="margin-bottom:5px;margin-top:5px;">Balance Due <br/> (By Duration/Total)</div>
        <div align="center" style="text-align:left;font-weight:bold; width:100px;">$<?php if(!empty($_SESSION['FromDate']))
 { echo number_format((($Total_Array[0]+$Total_Array[1])-$Total_Array[7]), 2).' / $'.number_format((($Total_Array2[0]+$Total_Array2[1])-$Total_Array2[2]), 2); }else{ echo number_format((($Total_Array1[0]+$Total_Array1[1])-$Total_Array1[2]), 2); } ?></div></td>
       </tr>
       
       <tr>
       <td colspan="6">&nbsp;</td>
       </tr>
       
       <tr>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td><span style="color:#FF0000;">Cancelled members <br/>(By Duration/Total)</span></td>
       <td><span style="color:#FF0000;">-$<?php if(!empty($_SESSION['FromDate']))
 { echo number_format($Total_Array[6], 2).' / $'.number_format($Total_Array2[6], 2); }else{ echo  number_format($Total_Array1[6], 2); } ?></span></td>
       </tr>
       
       <tr>
       <td colspan="6">&nbsp;</td>
       </tr>
       
       <tr>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td><span style="font-weight:bold;">Balance <br/> (By Duration/Total)</span></td>
       <td><span style="font-weight:bold;">$<?php if(!empty($_SESSION['FromDate']))
 { echo number_format(((($Total_Array[0]+$Total_Array[1])-$Total_Array[7])-$Total_Array[6]), 2).'/ $'.number_format(((($Total_Array2[0]+$Total_Array2[1])-$Total_Array2[2])-$Total_Array2[6]), 2); }else{ echo number_format(((($Total_Array1[0]+$Total_Array1[1])-$Total_Array1[2])-$Total_Array1[6]), 2); } ?></span></td>
       </tr>
       
       
       
    <?php } ?>
  </table>
  <div><strong>Note: Formula for Balance calculation:-(Balance Due - Cancelled members Amount = Balance).</strong> <br/><br/>
     <strong>Note: Formula for Balance Due calculation:- (Total Purchas + Total Bonus - Total Spend = Balance Due).</strong>
   </div>
</div>
<?php } ?>
<? include "../../lib/bottomnav.php" ?>
<? include "../../include/footer.php"; ?>
