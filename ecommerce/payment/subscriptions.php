<?php 
include_once($_SERVER['DOCUMENT_ROOT']."/common/autoload.php");
include_once('dbcon.php');
if(isset($_REQUEST['export']) && $_REQUEST['export']==1){
include $_SERVER['DOCUMENT_ROOT']."/admintti/lib/include.php"; 
}else{
include $_SERVER['DOCUMENT_ROOT']."/admintti/include/header.php"; 
}

$objMember = new Member();
$objleads = new Leads();
$objGroups = new Groups();
$objProgram = new Program();
$utilOjb = new util();
$objpdo = new DB();

//echo '<pre>';

// Filter Code
extract($_POST);
if(isset($Filter)){

if(!empty($SearchText)){
$_SESSION['SearchMember'] = trim($SearchText);
}else{
     //need to umset
	 unset($_SESSION['SearchMember']);
}

if(!empty($FDateMySales) && !empty($TDateMySales) ){
$_SESSION['FDateOrder']=date('Y-m-d H:i:s',strtotime($FDateMySales));
$_SESSION['TDateOrder']=date('Y-m-d H:i:s',strtotime($TDateMySales));
}else{
  // need to unset
    unset($_SESSION['FDateOrder']);
	unset($_SESSION['TDateOrder']);
  }
  
}

$wherecnd = "where 1";
if(!empty($_SESSION['SearchMember'])){
$wherecnd .= " and (M.ID = '".$_SESSION['SearchMember']."' or M.Email like '%".$_SESSION['SearchMember']."%' or M.FirstName like '%".$_SESSION['SearchMember']."%' or M.Surname like '%".$_SESSION['SearchMember']."%' or M.AppCode like '%".$_SESSION['SearchMember']."%')";
}

if(!empty($_SESSION['FDateOrder']) && !empty($_SESSION['TDateOrder'])){
 $wherecnd .= " and MI.DateCreated >='".$_SESSION['FDateOrder']."' and MI.DateCreated <='".$_SESSION['TDateOrder']."'";
}

// Pay Status 
$_SESSION['p_status'] = (isset($p_status)) ? $p_status : '0,1'; 
$Status_q = $_SESSION['p_status']; 
$wherecnd .= " and MI.PayStatus in ($Status_q)";
//
$p_statusArray = array('All'=>'0,1', 'Success'=>'1', 'Failed'=>'0');

// DB Query		
$sql = "select MI.*, M.ID as mid, M.FirstName as fn, M.Surname as sn, M.Email as Email, M.Phone as bPhone, M.Phone1 as cPhone, M.SkypeID as Skype from MemberInvoice MI join Member M on MI.MemberID = M.ID $wherecnd group by InvoiceID Order by DateCreated desc";		
$Invoice_data = $objpdo->fetch($sql);
//
if(isset($_REQUEST['export']) && $_REQUEST['export']==1){
	include '../payment/ExportMemberSubscription.php';
	exit;	
}

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

$total_records =  count($Invoice_data);

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
$Invoice_data = $utilOjb->create_pgntion($offset, $limit, $Invoice_data);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sponsor Tracking</title>

<script src="<?php echo SITE_ADDRESS;?>javascript/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>admintti/js/search.js"></script>
<link href="<?php echo SITE_ADDRESS;?>/co_op/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>admintti/css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/Tooltip/js/tooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/Tooltip/css/tooltip.css" media="screen" />
<style>
.pending{ color:#FF0000; }
</style>
</head>
<body>
<div id="headtitle">Member Invoices</div>
  <div class="filtercontainer">
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
    <form name="TagSearchForm" id="TagSearchForm" action="" method="post">
      <td width="550">
      <div class="adv_search">
          <div class="adv_search_sub">
              <div class="input_box_1">
                <input class="input_box_2_1" type="text" name="SearchText" value="<?php echo $_SESSION['SearchMember'];?>" />
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
		  if($_SESSION['FDateOrder'] && $_SESSION['TDateOrder']) {
	      echo date("M d",strtotime($_SESSION['FDateOrder']))." to ".date("M d",strtotime($_SESSION['TDateOrder'])); 
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
                            <input type="text" id="fromdatepicker" name="FDateMySales" size="8" style="padding: 1px 4px; width: 85px;" value="<?php  if($_SESSION['FDateOrder']) echo date('m/d/Y',strtotime($_SESSION['FDateOrder']));?>" />
                          </td>
                          <td> To:<br />
                            <input type="text" id="todatepicker" name="TDateMySales" size="8" style="padding: 1px 4px; width: 85px;" value="<?php if($_SESSION['TDateOrder']) echo date('m/d/Y',strtotime($_SESSION['TDateOrder']));?>"/>
                          </td>  
                        </tr>
                        <tr>
                        <td> Pay Status : </td>
                        <td>
                        	<select name="p_status" id="p_status" style="width:95px;">
                            	<?php foreach((array) $p_statusArray as $k=>$pSlist){ 
										$selected = '';
										if($_SESSION['p_status'] == $pSlist) $selected = 'selected="selected"';
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
      <td align="right"><span style="color:#FF0000;">Note :</span> This Report is based on the records from Infusion Soft. It doesn't include members from Steele CRM.&nbsp;&nbsp;<a id="MembersSubscription" href="ExportMemberSubscription.php"><img width="30" height="30" border="0" title="Export Excel Sheet" src="../../images/icon_download_excel.png"></a></td>   
      </form>  
    </tr>
  </table>
</div>
<div class="subcontainer">
   <table width="100%" border="0" cellspacing="0" cellpadding="2" align="center"  > 
     <tr id="headerbar">
     <td>Invoice ID</td>
     <td>Invoice Created</td>
     <td>Groups</td>
     <td>Member Name</td>
     <td>Invoice Desc.</td>
     <td>Invoice Total</td>
     <td>Amt Paid</td>
     <td>Remaining Bal.</td>
     <td align="center">Pay Status</td>
     <tr>
     <?php 
	 $color=1;
if(count($Invoice_data)>0){
	 $iTotal = $tPaid = $tDue = 0;
	 foreach((array) $Invoice_data as $Irow){
	 $colors = (($color%2)==0) ? 'row-tan' : 'row-white';
	 $pstat = ($Irow['PayStatus'] == 1) ? 'class=""' : 'class="pending"';
	 $simg = ($Irow['PayStatus'] == 1) ? 'title="Payment Success" src="'.SITE_ADDRESS.'admintti/images/start.png"' : 'title="Payment Fail" src="'.SITE_ADDRESS.'admintti/images/stop.png"';
	 $details  = (!empty($Irow['Email'])) ? "Email : ".$Irow['Email'] : "";	
	 $details .= (!empty($Irow['bPhone'])) ? "<br>Best Phone : ".$Irow['bPhone'] : "";	
	 $details .= (!empty($Irow['cPhone'])) ? "<br>Cell Phone : ".$Irow['cPhone'] : "";	
	 $details .= (!empty($Irow['Skype'])) ? "<br>Skype : ".$Irow['Skype'] : "";		
	 ?>
     <tr id="<?php echo $colors; ?>">
         <td> <?php echo $Irow['InvoiceID']; ?></td>
         <td> <?php echo $cdate = ($Irow['DateCreated'] != '') ? date("M d, Y",strtotime($Irow['DateCreated'])) : 'N/A'; ?></td>
         <td> <div style="margin-top: -15px; margin-left: 6px;"><?php echo $imd_data = $objMember->membership_icons($Irow['mid'], $assign_fbg = 0); ?></div></td>
         <td> <?php echo "<span>".ucwords(strtolower(trim($Irow['fn']." ". $Irow['sn']))).'</span> '; ?>
         	<span class="toolTip" style="background-image:url(<?php echo SITE_ADDRESS; ?>co_op/images/page.png);" title="<?php echo $details;?>">
         	</span>
         </td>
         <td> <?php echo $Irow['Description']; ?></td>    
         <td> <?php echo '$'.number_format($Irow['InvoiceTotal']); $iTotal = $iTotal + $Irow['InvoiceTotal']; ?></td>
         <td> <?php echo '$'.number_format($Irow['TotalPaid']); $tPaid = $tPaid + $Irow['TotalPaid']; ?></td>
         <td> <?php echo "<span $pstat>$".number_format($tBalance = (int)$Irow['InvoiceTotal']-(int)$Irow['TotalPaid'])."</span>"; $tDue = $tDue + $tBalance; ?></td>
         <td align="center"> <img name="paystatus" border="0" <?php echo $simg; ?> /></td>
     </tr>  
<?php  $color++; } ?>
 	 <tr>
        <td align="left" colspan="5"><b>Totals :</b></td>
        <td><b><?php echo '$'.number_format($iTotal,2); ?></b></td>
        <td><b><?php echo '$'.number_format($tPaid,2); ?></b></td>
        <td><b><?php echo '$'.number_format($tDue,2); ?></b></td>
        <td>&nbsp;</td>
     </tr>	 
<?php }else{ ?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="8">No Order Detail Found</td>
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
	$('#MembersSubscription').fancybox();
</script>