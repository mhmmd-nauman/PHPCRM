<?php
include_once($_SERVER['DOCUMENT_ROOT']."/common/autoload.php");
admintti_autoload('');
$objMember = new Member();
$objGroups = new Groups();
$utilOjb = new util();
$objpdo = new DB();

$mid = (!empty($_REQUEST['mid'])) ? $_REQUEST['mid'] : '';
$mo_Flag = (!empty($_REQUEST['mo_Flag'])) ? $_REQUEST['mo_Flag'] : '';

$FDateMyComm = (!empty($_REQUEST['FDateMyComm'])) ? date('Y-m-d',strtotime($_REQUEST['FDateMyComm'])) : '';
$TDateMyComm = (!empty($_REQUEST['TDateMyComm'])) ? date('Y-m-d',strtotime($_REQUEST['TDateMyComm'])) : '';

if(!empty($mo_Flag) && $mo_Flag==1){

	if(empty($FDateMyComm) && empty($TDateMyComm)){
		$byDate = " AND MI.DateCreated < Now() and MI.DateCreated > DATE_ADD(Now(), INTERVAL- 12 MONTH) order by MI.DateCreated desc";
	}else{
		$byDate = " AND MI.DateCreated >='".$FDateMyComm."' and MI.DateCreated<='".$TDateMyComm."' order by MI.DateCreated desc";
	}
}else{
	$byDate = " order by MI.DateCreated desc";
}


if(!empty($mid)){
	$sql_rec = "select RO.*, P.ProductName as pname, M.FirstName as fn, M.Surname as sn from RecurringOrder RO join Member M on RO.MemberID = M.ID  join Product P on P.ID = RO.ProductID where RO.MemberID = $mid Order by RO.StartDate";
	$sql_inc = "select MI.*, P.ProductName as pname from MemberInvoice MI join Member M on MI.MemberID = M.ID join Product P on P.ID = MI.ProductID where MI.MemberID = $mid ".$byDate."";
	$Recc_data = $objpdo->fetch($sql_rec);
	$Inc_data = $objpdo->fetch($sql_inc);
} 
// if $Recc_data empty then only fetch members first & last name.
if(empty($Recc_data) && $mo_Flag==1)
{
	$mem_rec = "select FirstName as fn,Surname as sn from Member where ID = '".$mid."'";
	$mem_data = $objpdo->fetch($mem_rec);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Payment Details</title>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>/javascript/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>admintti/css/styles.css">
<link href="<?php echo SITE_ADDRESS;?>/co_op/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/Tooltip/js/tooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/Tooltip/css/tooltip.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>admintti/css/styles.css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600' rel='stylesheet' type='text/css'>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>admintti/css/search.css" />
<script type="text/javascript">
// Date Picker
$(function() {
	$("#fromdatepicker1").datepicker();
});

// Date Picker	
$(function() {
	$("#todatepicker1").datepicker();
});

</script>

<style>
.pending{ color:#FF0000; }
b { font-size: 12px; }
</style>
</head>
<body>
<?php if(empty($Recc_data) && $mo_Flag==1){ ?> 
<div id="name"><?php echo "<strong><span>".ucwords(strtolower(trim($mem_data[0]['fn']." ". $mem_data[0]['sn']))).'</span></strong>'; ?></div>
<?php }else{ ?>
<div id="name"><?php echo "<strong><span>".ucwords(strtolower(trim($Recc_data[0]['fn']." ". $Recc_data[0]['sn']))).'</span></strong>'; ?></div>
<?php } ?>
<div class="filtercontainer" style="margin-top: 10px; padding: 4px;">
	<div id="headtitle" style="float:none; margin:0px; padding:0px;">Subscriptions</div>
</div>
<?php if($mo_Flag==1){ ?>
<div id="dateFilter">
<form name="searchDate" id="searchDate" action="" method="post">
    <table>
        <tr>
            <td>From:&nbsp;<input type="text" id="fromdatepicker1" name="FDateMyComm" size="8" style="padding: 1px 4px; width: 85px;" value="<?php  if($_REQUEST['FDateMyComm']) echo date('m/d/Y',strtotime($_REQUEST['FDateMyComm']));?>" /></td>
            <td>To:&nbsp;<input type="text" id="todatepicker1" name="TDateMyComm" size="8" style="padding: 1px 4px; width: 85px;" value="<?php if($_REQUEST['TDateMyComm']) echo date('m/d/Y',strtotime($_REQUEST['TDateMyComm']));?>"/></td>
            <td><input type="submit" name="Filter" class="adv_btn_2" value="&nbsp;" align="absmiddle" border="0" /></td>
        </tr>
</table>
</form>
</div>
<?php } ?>
<div class="subcontainer">
   <table width="100%" border="0" cellspacing="0" cellpadding="2" align="center"  > 
     <tr id="headerbar">
     <td>Rec ID</td>
     <td>Rec. Product</td>
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
	 ?>
     <tr id="<?php echo $colors; ?>">
         <td> <?php echo $Rrow['OrderID']; ?></td>
         <td> <?php echo $Rrow['pname']; ?></td>    
         <td> <?php echo $sdate = ($Rrow['StartDate'] != '') ? date("M d, Y",strtotime($Rrow['StartDate'])) : 'N/A'; ?></td>
         <td> <?php echo $sdate = ($Rrow['EndDate'] != '') ? date("M d, Y",strtotime($Rrow['EndDate'])) : 'N/A'; ?></td>
         <td> <?php echo $sdate = ($Rrow['LastBillDate'] != '') ? date("M d, Y",strtotime($Rrow['LastBillDate'])) : 'N/A'; ?></td>
         <td> <?php echo $sdate = ($Rrow['NextBillDate'] != '') ? date("M d, Y",strtotime($Rrow['NextBillDate'])) : 'N/A'; ?></td>
         <td> <?php echo $bCycle[$Rrow['BillingCycle']]; ?></td>
         <td> <?php echo '$'.number_format($Rrow['BillingAmt'],2); $tbAmt = (int)$Rrow['BillingAmt'] + (int)$tbAmt; ?></td>
         <td> <?php echo $aCharge[$Rrow['AutoCharge']]; ?></td>
         <td> <?php echo $Rrow['Status']; ?></td>
     </tr>  
<?php  $color++; } ?>
 	 <tr>
        <td align="left" colspan="7"><b>Recurring Totals :</b></td>
        <td><b><?php echo '$'.number_format($tbAmt,2); ?></b></td>
        <td colspan="2">&nbsp;</td>
     </tr>	 
<?php }else{ ?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="10">No Order Detail Found</td>
    </tr>
    <?php }  ?>  
    </table>
</div>
<div class="filtercontainer" style="margin-top: 10px; padding: 4px;">
	<div id="headtitle" style="float:none; margin:0px; padding:0px;">Recent Invoices</div>
</div>
<div class="subcontainer">
   <table width="100%" border="0" cellspacing="0" cellpadding="2" align="center"  > 
     <tr id="headerbar">
     <td>Invc. ID</td>
     <td>Invc. Created</td>
     <td>Invc. Product</td>
     <td>Invc. Desc.</td>
     <td>Invc. Total</td>
     <td>Amt Paid</td>
     <td>Remaining Bal.</td>
     <td>Pay Status</td>
     </tr>
     <?php 
	 $color_two = 1;
if(count($Inc_data)>0){
	 $tbAmt = 0;
	 foreach((array) $Inc_data as $Irow){
	 $colors_two = (($color_two%2)==0) ? 'row-tan' : 'row-white';
	 $pstat = ($Irow['PayStatus'] == 1) ? 'class=""' : 'class="pending"';
	 $simg = ($Irow['PayStatus'] == 1) ? 'title="Payment Success" src="'.SITE_ADDRESS.'admintti/images/start.png"' : 'title="Payment Fail" src="'.SITE_ADDRESS.'admintti/images/stop.png"';
	 $iTotal = $tPaid = $tDue = 0;
	 ?>
     <tr id="<?php echo $colors_two; ?>">
         <td> <?php echo $Irow['InvoiceID']; ?></td>
         <td> <?php echo $cdate = ($Irow['DateCreated'] != '') ? date("M d, Y",strtotime($Irow['DateCreated'])) : 'N/A'; ?></td>
         <td> <?php echo $Irow['pname']; ?> </td> 
         <td> <?php echo $Irow['Description']; ?> </td>
         <td> <?php echo '$'.number_format($Irow['InvoiceTotal']); $iTotal = $iTotal + $Irow['InvoiceTotal']; ?></td>
         <td> <?php echo '$'.number_format($Irow['TotalPaid']); $tPaid = $tPaid + $Irow['TotalPaid']; ?></td>
         <td> <?php echo "<span $pstat>$".number_format($tBalance = (int)$Irow['InvoiceTotal']-(int)$Irow['TotalPaid'])."</span>"; $tDue = $tDue + $tBalance; ?></td>
         <td> <img name="paystatus" border="0" <?php echo $simg; ?> /></td>
     </tr>  
<?php  $color_two ++; } ?>
 	 <tr>
        <td align="left" colspan="4"><b>Invoice Totals :</b></td>
        <td><b><?php echo '$'.number_format($iTotal,2); ?></b></td>
        <td><b><?php echo '$'.number_format($tPaid,2); ?></b></td>
        <td><b><?php echo '$'.number_format($tDue,2); ?></b></td>
        <td>&nbsp;</td>
     </tr>	 
<?php }else{ ?>
    <tr id="<?php echo $color_two; ?>">
      <td  colspan="8">No Order Detail Found</td>
    </tr>
    <?php }  ?>  
    </table>
</div>
</body>
</html>