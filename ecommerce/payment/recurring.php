<?php 
include_once($_SERVER['DOCUMENT_ROOT']."/common/autoload.php");
include_once('dbcon.php');
if(isset($_REQUEST['export']) && $_REQUEST['export']==1){
include $_SERVER['DOCUMENT_ROOT']."/admintti/lib/include.php"; 
}else{
include $_SERVER['DOCUMENT_ROOT']."/admintti/include/header.php"; 
}

$objMember = new Member();
$objGroups = new Groups();
$utilOjb = new util();
$objpdo = new DB();

//echo '<pre>';

// Filter Code
extract($_POST);
if(isset($Filter)){

if(!empty($SearchText)){
$_SESSION['SearchMemberRec'] = trim($SearchText);
}else{
     //need to umset
	 unset($_SESSION['SearchMemberRec']);
}

if(!empty($FDateMySales) && !empty($TDateMySales) ){
$_SESSION['FDateRec']=date('Y-m-d H:i:s',strtotime($FDateMySales));
$_SESSION['TDateRec'] =date('Y-m-d H:i:s',strtotime($TDateMySales));
}else{
  // need to unset
    unset($_SESSION['FDateRec']);
	unset($_SESSION['TDateRec'] );
  }
  
}

$wherecnd = "where 1";
if(!empty($_SESSION['SearchMemberRec'])){
$wherecnd .= " and (M.ID = '".$_SESSION['SearchMemberRec']."' or M.Email like '%".$_SESSION['SearchMemberRec']."%' or M.FirstName like '%".$_SESSION['SearchMemberRec']."%' or M.Surname like '%".$_SESSION['SearchMemberRec']."%' or M.AppCode like '%".$_SESSION['SearchMemberRec']."%')";
}

if(!empty($_SESSION['FDateRec']) && !empty($_SESSION['TDateRec'] )){
 $wherecnd .= " and M.Created >='".$_SESSION['FDateRec']."' and M.Created <='".$_SESSION['TDateRec'] ."'";
}

// Pay Status 
unset($_SESSION['r_status']);
$_SESSION['r_status'] = (isset($p_status)) ? ($p_status == 'all') ? "'Active','Inctive'" : "'".$p_status."'" : "'Active','Inctive'"; 
$Status_q = $_SESSION['r_status'];
$wherecnd .= " and RO.Status in ($Status_q)";
//
$p_statusArray = array('All'=>"all", 'Active'=>"Active", 'Inactive'=>"Inactive");

// DB Query		
$sql = "select RO.*, M.ID as mid, M.Created as joind, M.FirstName as fn, M.Surname as sn, M.Email as Email, M.Phone as bPhone, M.Phone1 as cPhone, M.SkypeID as Skype,
(select ProductName from Product P where P.ID = RO.ProductID) lastproduct,
(select count(ID)-1 from RecurringOrder RO2 where RO2.MemberID = RO.MemberID) TotalRec from Member M join RecurringOrder RO on M.ID = RO.MemberID $wherecnd group by RO.MemberID Order by RO.MemberID desc";	
$Recc_data = $objpdo->fetch($sql);

if(isset($_REQUEST['export']) && $_REQUEST['export']==1){
	include '../payment/ExportMemberRecurring.php';
	exit;	
}
//

// Paggination 
if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
}
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

$total_records =  count($Recc_data);

 if(!isset($_SESSION['limit'])){
	$limit = 20 ;
} else if($_SESSION['limit'] =="all" ){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'];
} 

$ret = $objGroups->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}
$Recc_data = $utilOjb->create_pgntion($offset, $limit, $Recc_data);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Recurring Orders</title>

<script src="<?php echo SITE_ADDRESS;?>javascript/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>admintti/js/search.js"></script>
<link href="<?php echo SITE_ADDRESS;?>/co_op/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>admintti/css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/Tooltip/js/tooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/Tooltip/css/tooltip.css" media="screen" />
<style>
.pending{ color:#FF0000; }
.payment{ text-decoration:none !important; }
</style>
</head>
<body>
<div id="headtitle">Recurring Orders</div>
  <div class="filtercontainer">
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
    <form name="TagSearchForm" id="TagSearchForm" action="" method="post">
      <td width="550">
      <div class="adv_search">
          <div class="adv_search_sub">
              <div class="input_box_1">
                <input class="input_box_2_1" type="text" name="SearchText" value="<?php echo $_SESSION['SearchMemberRec'];?>" />
                <div style="display: block;" data-tooltip="Show search options" aria-label="Show search options" role="button" gh="sda" tabindex="0" class="aoo" id="show_options">
                  <div class="aoq"></div>
                </div>
              </div>
              <div class="adv_btn">
                <input class="adv_btn_2" type="submit" value="&nbsp;" name="Filter">
              </div>
          </div>
          <div style="color:#235793; font-size:18px; margin-top:11px;">
		  <?php 
		  if($_SESSION['FDateRec'] && $_SESSION['TDateRec'] ) {
	      echo date("M d",strtotime($_SESSION['FDateRec']))." to ".date("M d",strtotime($_SESSION['TDateRec'] )); 
		  }?></div>
          <div class="cate_main" id="cate_main" style="display:none;position:absolute; top:154px; z-index: 100000; width:246px;">
              <div id="search_close" tabindex="0" role="button" class="Zy"></div>
              <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                  <td>
                    <div class="search_row" style=" padding-bottom:20px;">
                      <table cellpadding="0" cellspacing="4" width="95%" border="0">   
                        <tr>
                          <td width="120">
                          	&nbsp;From:<br />
                            <input type="text" id="fromdatepicker" name="FDateMySales" size="8" style="padding: 1px 4px; width: 85px;" value="<?php  if($_SESSION['FDateRec']) echo date('m/d/Y',strtotime($_SESSION['FDateRec']));?>" />
                          </td>
                          <td> To:<br />
                            <input type="text" id="todatepicker" name="TDateMySales" size="8" style="padding: 1px 4px; width: 85px;" value="<?php if($_SESSION['TDateRec'] ) echo date('m/d/Y',strtotime($_SESSION['TDateRec'] ));?>"/>
                          </td>  
                        </tr>
                        <tr>
                        <td> Rec. Status : </td>
                        <td>
                        	<select name="p_status" id="p_status" style="width:95px;">
                            	<?php foreach((array) $p_statusArray as $k=>$pSlist){ 
										$selected = '';
										if($_SESSION['r_status'] == $pSlist) $selected = 'selected="selected"';
										echo "<option $selected value='$pSlist'>$k</option>"; 
									}
								?>
                            </select>    
                        </td>
                        </tr>  
                        <tr>   
                          <td colspan="2" align="right" valign="bottom">
                              <input type="submit" name="Filter" class="adv_btn_2" value="&nbsp;" align="absmiddle" border="0" style="float:right;" />
                          </td>
                        </tr>
                      </table>
                    </div>
                    </td>
                </tr>
               </table>
            </div>
        </div>
        </td>
      <td align="right"><span style="color:#FF0000;">Note :</span> This Report is based on the records from Infusion Soft. It doesn't include members from Steele CRM.
      &nbsp;&nbsp;<a id="MembersRecurring" href="ExportMemberRecurring.php"><img width="30" height="30" border="0" title="Export Excel Sheet" src="../../images/icon_download_excel.png"></a></td>   
      </form>  
    </tr>
  </table>
</div>
<div class="subcontainer">
   <table width="100%" border="0" cellspacing="0" cellpadding="2" align="center"  > 
     <tr id="headerbar">
     <td>ID</td>
     <td>Joined</td>
     <td>Groups</td>
     <td>Member Name</td>
     <td>Rec. Products</td>
     <td>Start Dt.</td>
     <td>End Dt.</td>
     <td>Last Bill Dt.</td>
     <td>Next Bill Dt.</td>
     <td>Billing Cyl.</td>
     <td>Billing Amt</td>
     <td>Auto Chrg.</td>
     <td>Status</td>
     </tr>
     <?php 
	 $color=1;
if(count($Recc_data)>0){
	 $tbAmt = 0;
	 $bCycle = array('1'=>'Yearly','2'=>'Monthly','3'=>'Weekly','6'=>'Daily');
	 $aCharge = array('1'=>'On','0'=>'Off');
	 foreach((array) $Recc_data as $Rrow){
	 $colors = (($color%2)==0) ? 'row-tan' : 'row-white';
	 $details  = (!empty($Rrow['Email'])) ? "Email : ".$Rrow['Email'] : "";	
	 $details .= (!empty($Rrow['bPhone'])) ? "<br>Best Phone : ".$Rrow['bPhone'] : "";	
	 $details .= (!empty($Rrow['cPhone'])) ? "<br>Cell Phone : ".$Rrow['cPhone'] : "";	
	 $details .= (!empty($Rrow['Skype'])) ? "<br>Skype : ".$Rrow['Skype'] : "";		
	 $rTotal = ($Rrow['TotalRec'] > 0) ? "<b style='color:#194A7E'>+".$Rrow['TotalRec']."</b>" : "";
	 ?>
     <tr id="<?php echo $colors; ?>">
         <td> <?php echo $Rrow['mid']; ?></td>
         <td> <?php echo $cdate = ($Rrow['joind'] != '') ? date("M d, Y",strtotime($Rrow['joind'])) : 'N/A'; ?></td>
         <td> <div style="margin-top: -15px; margin-left: 6px;"><?php echo $imd_data = $objMember->membership_icons($Rrow['mid'], $assign_fbg = 0); ?></div></td>
         <td> <?php echo "<span>".ucwords(strtolower(trim($Rrow['fn']." ". $Rrow['sn']))).'</span> '; ?>
         	<span class="toolTip" style="background-image:url(<?php echo SITE_ADDRESS; ?>co_op/images/page.png);" title="<?php echo $details;?>">
         	</span>
         </td>
         <td> <?php echo "<a href='paymentdetails.php?mid=".$Rrow['mid']."' class='payment'>".$Rrow['lastproduct']." ".$rTotal.'</a>'; ?></td>    
         <td> <?php echo $sdate = ($Rrow['StartDate'] != '') ? date("M d, Y",strtotime($Rrow['StartDate'])) : 'N/A'; ?></td>
         <td> <?php echo $sdate = ($Rrow['EndDate'] != '') ? date("M d, Y",strtotime($Rrow['EndDate'])) : 'N/A'; ?></td>
         <td> <?php echo $sdate = ($Rrow['LastBillDate'] != '') ? date("M d, Y",strtotime($Rrow['LastBillDate'])) : 'N/A'; ?></td>
         <td> <?php echo $sdate = ($Rrow['NextBillDate'] != '') ? date("M d, Y",strtotime($Rrow['NextBillDate'])) : 'N/A'; ?></td>
         <td> <?php echo $bCycle[$Rrow['BillingCycle']]; ?></td>
         <td> <?php echo '$'.number_format($Rrow['BillingAmt'],2); $tbAmt = (int)$Rrow['BillingAmt'] + (int)$tbAmt; ?></td>
         <td align="center"> <?php echo $aCharge[$Rrow['AutoCharge']]; ?></td>
         <td> <?php echo $Rrow['Status']; ?></td>
     </tr>  
<?php  $color++; } ?>
 	 <tr>
        <td align="left" colspan="10"><b>Totals :</b></td>
        <td><b><?php echo '$'.number_format($tbAmt,2); ?></b></td>
        <td colspan="2">&nbsp;</td>
     </tr>	 
<?php }else{ ?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="13">No Order Detail Found</td>
    </tr>
    <?php }  ?>  
    </table>
   <div align="center">
		<?php include "../../lib/bottomnav.php" ?>
   </div>
</div>
</body>
</html>
<script type="text/javascript">	
	// Date Picker
	$(function() { $("#fromdatepicker").datepicker();  });	
	// Date Picker	
	$(function() { $("#todatepicker").datepicker(); });
	
	$(".payment").fancybox({
		'width'             : 900,
        'height'			: 450,
        'autoScale'         : false,
        'transitionIn'      : 'none',
        'transitionOut'     : 'none',
        'href'              : this.href,
        'type'              : 'iframe',
	});
	
	$('#MembersRecurring').fancybox();
</script>