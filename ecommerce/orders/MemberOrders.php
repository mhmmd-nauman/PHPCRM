<?php 
//include_once($_SERVER['DOCUMENT_ROOT']."/common/autoload.php");
//include_once('dbcon.php');
include $_SERVER['DOCUMENT_ROOT']."/include/header.php"; 

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
$_SESSION['SearchMember']=$SearchText;
}else{
     //need to umset
	 unset($_SESSION['SearchMember']);
}

if(!empty($FDateMySales) && !empty($TDateMySales) ){
$_SESSION['FDateOrder']=date('Y-m-d',strtotime($FDateMySales));
$_SESSION['TDateOrder']=date('Y-m-d',strtotime($TDateMySales));
}else{
  // need to unset
    unset($_SESSION['FDateOrder']);
	unset($_SESSION['TDateOrder']);
  }
  
}

if(!empty($_SESSION['SearchMember'])){
$wherecnd = " and (M1.ID = '".$_SESSION['SearchMember']."' or M1.Email like '%".$_SESSION['SearchMember']."%' or M1.FirstName like '%".$_SESSION['SearchMember']."%' or M1.Surname like '%".$_SESSION['SearchMember']."%' or M1.AppCode like '%".$_SESSION['SearchMember']."%')";
}

if(!empty($_SESSION['FDateOrder']) && !empty($_SESSION['TDateOrder'])){
 if($wherecnd != ''){
 $wherecnd .= " and M1.Created >='".$_SESSION['FDateOrder']."' and M1.Created <='".$_SESSION['TDateOrder']."'";
 }else{
  $wherecnd = " and M1.Created >='".$_SESSION['FDateOrder']."' and M1.Created <='".$_SESSION['TDateOrder']."'";
 }
}
//

// DB Query
$sql =	"select M1.*, count(M1.ID) as MemberCount, 
		(select (P.ID) as SpecialP from Product P join MemberOrders SMO on P.ID = SMO.ProductID where SMO.MemberID = M1.ID and P.SpecialProduct = '1') as SpecialProduct,
		(select count(M5.ID) as SMembers from Member M5 join MemberOrders MO3 on M5.ID = MO3.MemberID join Product P2 on P2.ID = MO3.MemberID where P2.SpecialProduct = '1' and M5.SponsorAppCode = M1.AppCode) as SpecialMembers,
		(select (if(SpecialProduct <> '' or SpecialMembers >= 5 , sum((MO.ProductAmt*50)/100), sum(if(SCL.CommissionnType = 'Percentage', (MO.ProductAmt*SCL.Level1)/100, SCL.Level1)))) as PaidAmt 
				from MemberOrders MO join Member M3 on MO.MemberID = M3.ID
                join SponsorCommissionLevel SCL on SCL.ProductName = MO.ProductID
                where SCL.Type = 'Product' and M3.SponsorAppCode = M1.AppCode and MO.CommPaidSts = '1' and M3.SponsorAppCode = MO.withAC) as TotalPaidAmt, 
		(select (if(SpecialProduct <> '' or SpecialMembers >= 5 , sum((MO2.ProductAmt*50)/100), sum(if(SCL2.CommissionnType = 'Percentage', (MO2.ProductAmt*SCL2.Level1)/100, SCL2.Level1)))) as PendingAmt 
				from MemberOrders MO2 join Member M4 on MO2.MemberID = M4.ID
                join SponsorCommissionLevel SCL2 on SCL2.ProductName = MO2.ProductID
                where SCL2.Type = 'Product' and M4.SponsorAppCode = M1.AppCode and MO2.CommPaidSts = '0' and M4.SponsorAppCode = MO2.withAC) as TotalDueAmt,
		(select sum(MO4.ProductAmt) as PaidTAmt from MemberOrders MO4 join Member M6 on MO4.MemberID = M6.ID 
		where M6.SponsorAppCode = M1.AppCode and M6.SponsorAppCode = MO4.withAC) as TotalSaleAmt		
		from Member M1 join Member M2 on M1.AppCode = M2.SponsorAppCode
		where M1.AppCode is not null and M1.AppCode <> '' $wherecnd group by M1.ID order by M1.ID DESC";

$Order_data = $objpdo->fetch($sql);
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

$total_records =  count($Order_data);

 if(!isset($_SESSION['limit'])){
	$limit = 10 ;
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
$Order_details = $utilOjb->create_pgntion($offset, $limit, $Order_data);

// Ajax Request
if(isset($_REQUEST['Task']) && $_REQUEST['Task'] == 'UpdateComm'){
		echo 'done';
		exit;
/*		if($_REQUEST['mAp'] != ''){ 
			$status_query = "update MemberOrders set CommPaidSts = '1' where 
							withAC = :appcode ";
			if (!DB::exec($status_query, array(':appcode' => $_REQUEST['mAp']))){
				error_log("Failed to save notes: " . DB::error_info());				
			}else{
				echo 'done';
			}	
		}
*/
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sponsor Tracking</title>

<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>admintti/js/search.js"></script>
<link href="<?php echo SITE_ADDRESS;?>/co_op/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>admintti/css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/Tooltip/js/tooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/Tooltip/css/tooltip.css" media="screen" />
<script language="javascript" type="text/javascript">

$(document).ready(function() {

<?php foreach((array) $Order_details as $OrderVal){ ?>
$("#CommPopup<?php echo $OrderVal['ID']; ?>").fancybox({
		'width'                         : 1000,
		'height'                        : 500,
		'autoScale'                   : false,
		'transitionIn'                : 'none',
		'transitionOut'                : 'none',
		'href'                        : this.href,
		'type'                        : 'iframe',
		'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); }
});
<?php } ?>

});

</script>
</head>
<body>
<div id="headtitle">Sponsor Commissions</div>
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
          <div class="cate_main" id="cate_main" style="display:none;position:absolute; top:154px; z-index: 100000; width:380px;">
              <div id="search_close" tabindex="0" role="button" class="Zy"></div>
              <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                  <td>
                    <div class="search_row" style=" padding-bottom:20px;">
                      <table cellpadding="0" cellspacing="0" width="95%" border="0">   
                        <tr>
                          <td>
                          	&nbsp;From:<br />
                            <input type="text" id="fromdatepicker" name="FDateMySales" size="8" style="padding: 1px 4px; width: 85px;" value="<?php  if($_SESSION['FDateOrder']) echo date('m/d/Y',strtotime($_SESSION['FDateOrder']));?>" />
                          </td>
                          <td> To:<br />
                            <input type="text" id="todatepicker" name="TDateMySales" size="8" style="padding: 1px 4px; width: 85px;" value="<?php if($_SESSION['TDateOrder']) echo date('m/d/Y',strtotime($_SESSION['TDateOrder']));?>"/>
                          </td>    
                          <td valign="bottom">
                            <div>
                              <input type="submit" name="Filter" class="adv_btn_2" value="&nbsp;" align="absmiddle" border="0" />
                            </div>
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
      <td>&nbsp;</td>   
      </form>  
    </tr>
  </table>
</div>
<div class="subcontainer">
   <table width="100%" border="0" cellspacing="0" cellpadding="2" align="center"  > 
     <tr id="headerbar">
     <td>ID</td>
     <td>Sponsor Name</td>
     <td>Email</td>
     <td align="center">Total Sales</td>
     <td align="center">Total Sales Value</td>
     <td align="center">Total Commission Value</td>
     <td align="center">Commission Paid</td>
     <td align="center">Commission Due</td>
     <td align="left"><input type="checkbox" name="check_all" id="" class="" onclick="toggleCheckedComm(this.checked)" title="Check this to mark commission paid to all below sponsors."  />&nbsp;Comm. Status </td>
     <td align="center">Member Status</td>
     <tr>
     <?php 
	 
	 $color=1;
if(count($Order_details)>0){
	 $i = $TotalSales = $TotalComm = $CommP = $CommD = 0;
	 foreach((array) $Order_details as $OrderVal){
	 	
		if($color%2==0)
	 	$colors='row-tan';
	 	else
	 	$colors='row-white';
		
		$Smember = $Stitle = '';	
		if( $OrderVal['SpecialProduct'] != '' || $OrderVal['SpecialMembers'] >= 5 ){ 
			$Smember = '<span style="color:#00FD00;">*</span>';	$Stitle = 'title="Special Commission"'; 
		}
	
	 ?>
     <tr id="<?php echo $colors; ?>">
         <td> <?php echo $OrderVal['ID']; ?></td>
         <td> <?php echo "<span $Stitle>".ucwords(strtolower(trim($OrderVal['FirstName']." ". $OrderVal['Surname']))).'</span> '.$Smember; ?></td>
         <td><span class="toolTip" style="background-image:url(<?php echo SITE_ADDRESS; ?>/images/email_icon.png);" title="<?php echo $OrderVal['Email'];?>">
         	  </span></td>    
         <td align="center" > 
		 <?php if ($OrderVal['MemberCount'] > 0){ ?>
        	<a href="<?php echo SITE_ADDRESS; ?>/admintti/MemberMBPopup.php?id=<?php echo $OrderVal['ID']; ?>" class="MbMember"><?php echo $OrderVal['MemberCount'];?></a>
         <?php } else { 
			echo $MemArray[0]['Total'];
		  } ?>
		 </td>
         <td align="center" > <?php echo '$'.number_format($OrderVal['TotalSaleAmt'],2); $TotalSales = $OrderVal['TotalSaleAmt'] + $TotalSales; ?></td>
         <?php
		 	$checkUrlData = $OrderVal['TotalPaidAmt']+$OrderVal['TotalDueAmt'];
         	//echo "Atul--->".$checkUrlData."</br>";
			if($checkUrlData==0){
         ?>
         <td align="center" > <?php echo '$'.number_format($OrderVal['TotalPaidAmt']+$OrderVal['TotalDueAmt'],2); $TotalComm = $TotalComm + ($OrderVal['TotalPaidAmt']+$OrderVal['TotalDueAmt']); ?></td>
         <?php }else{ ?>
         <td align="center"><a id="CommPopup<?php echo $OrderVal['ID']; ?>" href="<?php echo SITE_ADDRESS; ?>/admintti/ecommerce/payment/paymentdetails.php?mid=<?php echo $OrderVal['ID']; ?>&mo_Flag=1" class="MbMember"><?php echo '$'.number_format($OrderVal['TotalPaidAmt']+$OrderVal['TotalDueAmt'],2); $TotalComm = $TotalComm + ($OrderVal['TotalPaidAmt']+$OrderVal['TotalDueAmt']); ?></a></td>
         <?php } ?>
         <td align="center"> <?php echo '$'.number_format($OrderVal['TotalPaidAmt'],2); $CommP = $CommP + $OrderVal['TotalPaidAmt']; ?></td>
         <td align="center" >
         <?php if ($OrderVal['TotalDueAmt'] > 0){ ?>
         	<span id="<?php echo $OrderVal['ID']; ?>_due" >	
            <a href="<?php echo SITE_ADDRESS; ?>/admintti/Comm_tfer_Popup.php?id=<?php echo $OrderVal['ID']; ?>&comm_amt=<?php echo number_format($OrderVal['TotalDueAmt'],2); ?>" class="comm_trans" ><?php echo '$'.number_format($OrderVal['TotalDueAmt'],2); ?></a>
            </span>
         <?php }else{ ?>
         	<?php echo '$'.number_format($OrderVal['TotalDueAmt'],2); ?>
         <?php } 
		 	$CommD = $CommD + $OrderVal['TotalDueAmt'];
		 ?>   
         </td>
         <td align="left"> 
         <?php 
		 $chk_dible = ''; $chk_title = "title='Check this to mark commission paid for this sponsors.'";
		 $chk_class = "class='changestatus'";
		 if($OrderVal['TotalDueAmt'] <= 0 ){
		 	$chk_dible = "disabled='disabled'";
			$chk_title = "title='Commission paid!'";
			$chk_class = "";
		 }?>
         <input type="checkbox"  name="changestatus[]" id="<?php echo $OrderVal['ID']; ?>_chkdue" <?php echo $chk_dible.' '.$chk_title.' '.$chk_class; ?> 
         value="<?php echo $OrderVal['ID']; ?>" />
         <span id="loading_img">
            <img align="absbottom" title="Loading..." src="../../../assets/images/ajax-loader.gif" id='load_<?php echo $OrderVal['ID']; ?>' style="display:none;" >
         </span>
         </td>
         <td align="center" > 
		 <?php if($OrderVal['MemberStatus'] == 'active'){ ?>
        	<img src="<?php echo  SITE_ADDRESS;?>admintti/images/icon_tick.png"  title="Active"/>
         <?php }elseif($OrderVal['MemberStatus'] == 'canceled'){ ?>
        	<img src="<?php echo  SITE_ADDRESS;?>admintti/images/icon_cancel.png"  title="Canceled" />
         <?php } elseif($OrderVal['MemberStatus'] == 'paused'){ ?>
        	<img src="<?php echo  SITE_ADDRESS;?>admintti/images/icon_error.png"  title="Paused"  />
         <?php } else { ?>
        	<img src="<?php echo  SITE_ADDRESS;?>admintti/images/icon_flag_red.png"  title="Card Failed" />
         <?php } ?> 
		</td>
     </tr>  
     <input type="hidden" value="<?php echo $OrderVal['AppCode']; ?>" class="" id="AppCode_<?php echo $OrderVal['ID']; ?>"  />
     <?php 
	 $color++; $i++; 
	 } ?>
	 <tr>
        <td align="left" colspan="4"><b>Totals :</b></td>
        <td align="center"><b><?php echo '$'.number_format($TotalSales,2); ?></b></td>
        <td align="center"><b><?php echo '$'.number_format($TotalComm,2); ?></b></td>
        <td align="center"><b><?php echo '$'.number_format($CommP,2); ?></b></td>
        <td align="center"><b><?php echo '$'.number_format($CommD,2); ?></b></td>
        <td align="center" colspan="2">&nbsp;</td>
     </tr>	 
<?php }else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Order Detail Found</td>
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
	
/*	function UpdateStatus(memberid,status){
		checked = parseInt($('.changestatus').val());
		if($('.changestatus').checked){
			alert('Checked');	
		}else{
			alert('Un');
		}
		//alert(checked);
	}
*/	

	// Check All
	function toggleCheckedComm(status) {
		$(".changestatus").each( function() {
			$(this).attr("checked",status);
		})
	}
	
	$(document).ready(function(){
	
		$('.changestatus').click(function(){
		var memberid = parseInt($(this).val());
		var memberAppCode = $('#AppCode_'+memberid).val();

			if(this.checked == true && memberAppCode != ''){
				
				$('#load_'+memberid).show();
				var dataString = 'UpdateComm=true&mAp='+memberAppCode;
		   
				$.ajax({
				   type: "POST",
				   url: "../../../wizard/updateTables.php",
				   data: dataString,
				   success: function(data){
				   		if($.trim(data) != '' && $.trim(data) == 'done'){
							$('#load_'+memberid).hide();
							$('#'+memberid+'_chkdue').attr('disabled','disabled');
							$('#'+memberid+'_due').text('$0.00');	
						}
				   }
				});
			}
		});
	});
	
	// Date Picker
	$(function() {
		$("#fromdatepicker").datepicker();
	});
	
	// Date Picker	
	$(function() {
		$("#todatepicker").datepicker();
	});
	
	// Fancybox functions
	$(".MbMember").fancybox();
	$(".comm_trans").fancybox({
		'width'             : 500,
        'height'			: 290,
        'autoScale'         : false,
        'transitionIn'      : 'none',
        'transitionOut'     : 'none',
        'href'              : this.href,
        'type'              : 'iframe',
	});
</script>