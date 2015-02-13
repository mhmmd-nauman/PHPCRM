<?php 
include_once('../../dbcon.php');
if(!isset($_REQUEST['export']) && $_REQUEST['export'] != '1'){
include "../../include/header.php";
}else{include "../../lib/include.php";}
//print_r($_SESSION);

$utilObj = new util();
$objmember = new Member(); 


/*unset($_SESSION['FDateMyComm']);
unset($_SESSION['TDateMyComm']);*/
if(!isset($_REQUEST['FDateMySales']))
{
	$strWhere='Type="CochingCommission"';
}
else
{
	if(!empty($_REQUEST['FDateMySales']))$_SESSION['FDateMyComm']=$_REQUEST['FDateMySales'];
	if(!empty($_REQUEST['TDateMySales']))$_SESSION['TDateMyComm']=$_REQUEST['TDateMySales'];
	$strWhere='date(Created)>="'.date('Y-m-d',strtotime($_SESSION['FDateMyComm'])).'" and  date(Created)<="'.date('Y-m-d',strtotime($_SESSION['TDateMyComm'])).'" and Type="CochingCommission"';
}

/*---------Get all records---------*/

 
$CommissionDetailsRecord=$utilObj->getMultipleRow('CommissionDetail' , $strWhere);


/*--------------------coding start for pagination----------------*/
if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
}
if(isset($_REQUEST['numberOfcall'])){
	$_SESSION['numberOfcall'] = $_REQUEST['numberOfcall'];
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


$total_records =  count($CommissionDetailsRecord);

 if(!isset($_SESSION['limit']) && $_SESSION['OneDaySession']==''){
	$limit = 10 ;
} else if($_SESSION['limit'] =="all" || $_SESSION['OneDaySession']!=''){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'] ;
}

$ret = $utilObj->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}

$strWhere.='LIMIT '.$offset.','.$limit.'';
 
$CommissionRows=$utilObj->getMultipleRow('CommissionDetail' , $strWhere);

if(isset($_REQUEST['export']) && $_REQUEST['export'] == 1){
include 'ExportCoachCommAll.php';
exit;
}	
?>
<link href="<?php echo SITE_ADDRESS;?>/co_op/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />

<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>admintti/js/search.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>admintti/css/search.css" />


<script type="text/javascript">
		$(function() {
		$("#fromdatepicker").datepicker();
		});
		
		$(function() {
			$("#todatepicker").datepicker();
		});
</script>

<script type="text/javascript">
		$(document).ready(function() {
		$(".total_amt").fancybox({
						   'width'                       : 580,
						   'height'						 : 350,
						   'autoScale'                   : false,
						   'transitionIn'                : 'none',
						   'transitionOut'               : 'none',
						   'href'                        : this.href,
						   'type'                        : 'iframe',
						   'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); } 
		});
	/*		$(".total_Sales").fancybox({
							   'width'                       : 850,
                               'height'						 : 500,
                               'autoScale'                   : false,
                               'transitionIn'                : 'none',
                               'transitionOut'               : 'none',
                               'href'                        : this.href,
                               'type'                        : 'iframe',
							   'onComplete': function() { $("#fancybox-title").css({'top':'-20px', 'bottom':'auto'}); } 
			});*/
		});
		function pay_to_coach(coach_id,amount,id)
		{
			$.ajax({
				url:'PayPal/MassPay.php',
				type:'POST',
				dataType:"json",
				data:'action=paytocoach&coach_id='+coach_id+'&amount='+amount,
				success: function(msg)
				{
					
					if(msg['ACK']=='SUCCESS' || msg['ACK']=='SUCCESSWITHWARNING')
					{
						$("#pay_span_"+id).html('Amount Paid');
						$("#pay_span_"+id).removeAttr('onclick');
						$('#check_pay_'+id).attr('disabled','disabled');
					}
					
				}
				
				});
		}
		</script>
        

<div id="headtitle">Weekly Coaching Commission Report</div>
<div class="filtercontainer">
 <table cellpadding="0" cellspacing="0" width="100%" border="0">
   <tr>
    <form name="TagSearchForm" id="TagSearchForm" action="" method="post">
      <td width="750">
      <div class="adv_search">
          <div class="adv_search_sub">
              <div class="input_box_1">
                <input class="input_box_2_1" type="text" name="SearchText" value="<?php echo $_SESSION['SearchText'];?>" />
                <div style="display: block;" data-tooltip="Show search options" aria-label="Show search options" role="button" gh="sda" tabindex="0" class="aoo" id="show_options">
                  <div class="aoq"></div>
                </div>
              </div>
              <div class="adv_btn">
                <input class="adv_btn_2" type="submit" value="" name="">
              </div>
          </div>
          <div style="color:#235793; font-size:18px; margin-top:11px; float:left; margin-right:20px;"><?php if(isset($_REQUEST['view'])){ if($_REQUEST['view']=='month'){ echo "Monthly Report";}elseif($_REQUEST['view']=='week'){ echo "Weekly Report";}}else{ echo date("M d",strtotime($_SESSION['FDateMyComm']))." to ".date("M d",strtotime($_SESSION['TDateMyComm'])); } ?><input type="button" name="pay" value="&nbsp;" class="pay_btn" style="margin-right:8px;" onclick="pay_comm_batch();" /></div>
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
                            <input type="text" id="fromdatepicker" name="FDateMySales" size="8" style="padding: 1px 4px; width: 85px;" value="<?php echo $_SESSION['FDateMyComm'];?>" />
                          </td>
                          <td> To:<br />
                            <input type="text" id="todatepicker" name="TDateMySales" size="8" style="padding: 1px 4px; width: 85px;" value="<?php echo $_SESSION['TDateMyComm'];?>"/>
                          </td>    
                          <td valign="bottom">
                            <div>
                              <input type="button" onClick="$('#TagSearchForm').attr('action','?'); $('#TagSearchForm').submit();" name="Filter" class="adv_btn_2" value="" align="absmiddle" border="0" />
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
      <td align="right" valign="center"><a id="MembersSheet" href="<?php if(isset($_REQUEST['view'])){ if($_REQUEST['view']=='month'){ echo "ExportCoachCommAll.php?export=1&view=month";}elseif($_REQUEST['view']=='week'){ echo "ExportCoachCommAll.php?export=1&view=week";}}else{ echo "ExportWeeklyCoachComm.php?export=1";} ?>" ><img src="/admintti/images/icon_download_excel.png" height="30" width="30" border="0" title="Export Excel Sheet" /></a></td>
        <script>
		$("#MembersSheet").fancybox();
		function setview(val)
		{
			if(val=='reset')
			{
				$('#TagSearchForm').attr('action','?');
				$('#TagSearchForm').submit();
			}else
			{
				$('#TagSearchForm').attr('action','?view='+val);
				$('#TagSearchForm').submit();
			}

		}
		function check_all()
		{
			if($('#checkall').is(':checked'))
			{
				
				$('.check_all_pay').each(function(){
					if(!$(this).is(':disabled'))
					{
						$(this).attr('checked','checked');
					}
					
					});
			}
			else
			{
				
				$('.check_all_pay').removeAttr('checked');
			}
		}
		$('.check_all_pay').click(function(){
			if($(this).is(':checked'))
			{
				$('#checkall').attr('checked','checked');
			}
			else
			{
				$('#checkall').removeAttr('checked');
			}
			});
		function pay_comm_batch()
		{
			if(!$('.check_all_pay').is(':checked'))
			{
				alert('you must check atleast one check box');
			}
			else
			{
				var end_proc=0;
				var array_pay_amt = [];
				var array_pay_sponsor = [];
				var array_pay_record = [];
				$('.check_all_pay:checked').each(function(){
					array_pay_amt.push( $('#pay_amount_'+$(this).val()).val() );
					array_pay_sponsor.push( $('#pay_sponsor_'+$(this).val()).val() );
					array_pay_record.push( $('#pay_record_'+$(this).val()).val() );
					if($('#pay_amount_'+$(this).val()).val()=='0')
					{
						alert('Please deselct the record with 0 commission');
						end_proc=1;
					}
					
					});
					if(end_proc==1)
					{
						
					}
					else
					{
						$.ajax({
						url:'PayPal/MassPay.php',
						type:'POST',
						dataType:"json",
						data:'action=paytocoach_batch&coach_ids='+array_pay_sponsor+'&amounts='+array_pay_amt+'&recordids='+array_pay_record,
						success: function(msg)
						{
							
							if(msg['ACK']=='SUCCESS' || msg['ACK']=='SUCCESSWITHWARNING')
							{
								$.each(array_pay_record,function(index,value){
									
									$("#pay_span_"+value).html('Amount Paid');
									$("#pay_span_"+value).removeAttr('onclick');
									});
								
							}
							
						}
						
						});
						
					}
			}
		}
		</script>
      </form>  
    </tr>
  </table>
</div>

<script type="text/javascript" language="javascript">
$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	jQuery("#show_options").show();
	$('#changename').attr('name', ''); 
});
</script>
<?php 
if(isset($_GET['view']))
{}else{?>
<div class="subcontainer"><br>

<table width="100%" cellspacing="0" cellpadding="3" border="0">
    <tbody><tr id="headerbar">
    <td><input type="checkbox" value="" id="checkall" name="checkall" onclick="check_all();"  /></td>
    <td>ID</td>
        <td>Assigned Coach</td>
        
         <td>Coach Type </td>

        <td>Total Members </td>

        <td>Program Name</td>
        
        <td>Total Sale Amt</td> 

        <td>Percentage</td>
        
        <td>Commission</td>
        
        <td>Staus</td>
        <td>Action</td>
        
       </tr>
 <?php foreach($CommissionRows as $CommRowsVal){
 
	if($flag==0){
	
	$flag=1;
	
	$row_class="row-white";
	
	}else{
	
	$flag=0;
	
	$row_class = "row-tan";
	
	}

 ?>
   <tr class="tblrow_14138" id="<?php echo $row_class;?>">
		<td><input type="checkbox" <?php if($CommRowsVal['PaymentStatus']=='Unpaid'){ if($CommRowsVal['ID']=='38'){ echo 'disabled="disabled"';} }else{echo 'disabled="disabled"'; } ?>  value="<?php echo $CommRowsVal['ID'];?>" name="check_pay[]" id="check_pay_<?php echo $CommRowsVal['ID']; ?>" class="check_all_pay" /></td>
        <td><?php echo $CommRowsVal['SponsorID'];?></td>
        <?php 
		$CoachWhere="ID='".$CommRowsVal['SponsorID']."'";
	    $Coachdetails=$utilObj->getSingleRow('Member' , $CoachWhere);
		
		
		
	$Coach_type = $objmember->GetAllMemberWithGroup("MemberID = '".$CommRowsVal['SponsorID']."' ORDER BY Group_Users.GroupID",array("*"));
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
	
		
		
	/*	echo "<pre/>";
		print_r($Coachdetails);*/
		
		$strMembercnd='date(Created)>="'.date('Y-m-d',strtotime($_SESSION['FDateMyComm'])).'" AND  date(created)<="'.date('Y-m-d',strtotime($_SESSION['TDateMyComm'])).'"';
		$MemberWhere="CoachId='".$Coachdetails['ID']."' And ".$strMembercnd." ";
		//print_r($MemberWhere);
		$MembertailsCnt=$utilObj->getMultipleRowWithFields('OrderItem' , $MemberWhere,'MemberID');
	    /*echo "<pre>";
		print_r($MembertailsCnt);*/
		
	  //Get Program name
	  
	    $commWhere="ID='".$CommRowsVal['CommissionProgramID']."'";
	    $CommProgramdetails=$utilObj->getSingleRow('CommissionProgram' , $commWhere);
		?>
        
        <td><?php echo $Coachdetails['FirstName'].'&nbsp;'.$Coachdetails['Surname'];?></td>
        <td><?php echo $coach_type_value ?></td>

        <td>
        <a style="color: #235793; text-decoration:none;" 
        href="<?php echo SITE_ADDRESS;?>admintti/MemberPopupCoachComm.php?CoachID=<?php echo $Coachdetails['ID'];?>" class="total_amt" name="total_amt">
	    <img src="../../images/group.png" title="">&nbsp;<span style="width:10px; text-align:center; color: #235793;">
	     <?php echo count($MembertailsCnt);?></span>
        </a>
        
       </td>
       <td>
		
     <!--   <a style="color: #235793; text-decoration:none;" href="Coaching_Comm_Popup.php?CoachID=3720" class="total_amt" name="total_amt">-->

        &nbsp;<span style="width:10px; text-align:center; margin-left: -8px;"><?php echo $CommProgramdetails['CommissionName'];?></span><!--</a>--></td>
		         <td>
		<!-- <a style="color: #235793; text-decoration:none;" href="Coaching_Comm_Popup.php?CoachID=3720" class="total_amt" name="total_amt">-->
        &nbsp;<span style="width:10px; text-align:center; color: #235793;">$<?php echo $CommRowsVal['TotalSaleAmt'];?> 	</span><!--</a>--></td>
		        <td>
	<!--	<a style="color: #235793; text-decoration:none;" href="Coaching_Comm_Popup.php?CoachID=3720" class="total_amt" name="total_amt">-->
        &nbsp;<span style="width:10px; text-align:center; color: #235793;"><?php echo ($CommRowsVal['Comission']/$CommRowsVal['TotalSaleAmt'])*100; ?>%</span><!--</a>--></td>	
        
       <td><span style="width:10px; text-align:center; color: #235793;">$<?php echo $CommRowsVal['Comission'];?></span></td>
       
         <td><span style="width:10px; text-align:center; color: red;"><?php echo $CommRowsVal['PaymentStatus'];?></span></td>
         <td><?php if($CommRowsVal['PaymentStatus']=='Unpaid'){ ?> <span id="pay_span_<?php echo $CommRowsVal['ID'];?>" onClick="pay_to_coach(<?php echo $CommRowsVal['SponsorID'];?>,<?php echo $CommRowsVal['Comission'];?>,<?php echo $CommRowsVal['ID'];?>);">Pay</span> <?php }else{ echo 'Already Paid';} ?></td>
         <input type="hidden" name="pay_amount[<?php echo $CommRowsVal['ID'];?>]" value="<?php echo $CommRowsVal['Comission'];?>" id="pay_amount_<?php echo $CommRowsVal['ID'];?>"  />
         <input type="hidden" name="pay_record[<?php echo $CommRowsVal['ID'];?>]" value="<?php echo $CommRowsVal['ID'];?>" id="pay_record_<?php echo $CommRowsVal['ID'];?>"  />
         <input type="hidden" name="pay_sponsor[<?php echo $CommRowsVal['ID'];?>]" value="<?php echo $CommRowsVal['SponsorID'];?>" id="pay_sponsor_<?php echo $CommRowsVal['ID'];?>"  />

     </tr>
<?php 
$totalamt+=$CommRowsVal['TotalSaleAmt'];
$totalComm+=$CommRowsVal['Comission'];

}//end of foreach ?>

	<tr id="">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><b>&nbsp;Total:</b></td>
	<td>&nbsp;&nbsp;$<b><?php echo $totalamt;?></b></td>
	<td>&nbsp;</td>
	<td>&nbsp;&nbsp;$<b><?php echo $totalComm;?></b></td>
	<td>&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
	</tr>
	</tbody></table>

</div>
<?php } ?>
<div align="center">

<?php include "../../lib/bottomnav.php"; ?>

</div>



