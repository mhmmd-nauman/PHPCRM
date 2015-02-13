<?php 
include_once('../../dbcon.php');
if(!isset($_REQUEST['export']) && $_REQUEST['export'] != '1'){
include "../../include/header.php";
}else{include "../../lib/include.php";}
//print_r($_SESSION);

$utilObj = new util();
$objmember = new Member(); 

$date=date('m/d/Y');
$tdate = date('m/d/Y', strtotime($date.'last saturday'));
$SixDAgo = strtotime ( '-6 day' , strtotime ( $tdate ) ) ;
$fdate = date( 'm/d/Y' , $SixDAgo );

/*unset($_SESSION['FDateMyComm']);
unset($_SESSION['TDateMyComm']);*/
if(!isset($_REQUEST['FDateMySales']))
{
	if(empty($_SESSION['FDateMyComm']))$_SESSION['FDateMyComm']=$fdate;
	if(empty($_SESSION['TDateMyComm']))$_SESSION['TDateMyComm']=$tdate;
}
else
{
	if(!empty($_REQUEST['FDateMySales']))$_SESSION['FDateMyComm']=$_REQUEST['FDateMySales'];
	if(!empty($_REQUEST['TDateMySales']))$_SESSION['TDateMyComm']=$_REQUEST['TDateMySales'];
}

/*---------Get all records---------*/
$strWhere='date(Created)>="'.date('Y-m-d',strtotime($_SESSION['FDateMyComm'])).'" and  date(Created)<="'.date('Y-m-d',strtotime($_SESSION['TDateMyComm'])).'" and Type="CochingCommission"';
 
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
          <div style="color:#235793; font-size:18px; margin-top:11px; float:left; margin-right:20px;"><?php if(isset($_REQUEST['view'])){ if($_REQUEST['view']=='month'){ echo "Monthly Report";}elseif($_REQUEST['view']=='week'){ echo "Weekly Report";}}else{ echo date("M d",strtotime($_SESSION['FDateMyComm']))." to ".date("M d",strtotime($_SESSION['TDateMyComm'])); } ?></div><input type="button" onClick="setview('month')" value="Monthly" style="margin-top:10px;"/><input type="button" onClick="setview('week')" value="Weekly"/><input type="button" onClick="setview('reset')" value="Reset"/>
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
<?php $result_dates=mysql_query('SELECT MIN(`Created`) as first_date , MAX(`Created`) as last_date FROM `CommissionDetail` WHERE 1');
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
if(isset($_GET['view']))
{
	
	if($_GET['view']=='month')
	{ 
	?>
     <?php
	$total_sale_order='';
	$total_num_order='';
    $start = $month = $first_date_time;
$end = strtotime("+1 month", $last_date_time);
while($month < $end)
{
	$j=date("m",$month);
	$i=date("Y",$month);		 
	$strWhere="month(Created)=$j AND year(Created) =$i and Type='CochingCommission'";
 
$CommissionDetailsMonthly=$utilObj->getMultipleRow('CommissionDetail' , $strWhere);
$totalamt='';
$totalComm='';
	 ?>
<span style="color: #235793;font-size: 18px;font-weight: bold;">For the month and year (<?php echo $j.'/'.$i; ?>)</span><br/>
<div class="subcontainer"><br>
	<table width="100%" cellspacing="0" cellpadding="3" border="0">
    	<tbody>
        	<tr id="headerbar">
    			<td width="3%">ID</td>
        		<td width="15%">Assigned Coach</td>
         		<td width="12%">Coach Type </td>
        		<td width="13%">Total Members </td>
        		<td width="16%">Program Name</td>
        		<td width="12%">Total Sale Amt</td> 
        		<td width="11%">Percentage</td>
        		<td width="11%">Commission</td>
        		<td width="5%">Staus</td>
       		</tr>
 <?php foreach($CommissionDetailsMonthly as $CommRowsVal){
 
	if($flag==0){
	
	$flag=1;
	
	$row_class="row-white";
	
	}else{
	
	$flag=0;
	
	$row_class = "row-tan";
	
	}

 ?>
   <tr class="tblrow_14138" id="<?php echo $row_class;?>">

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
		
		$strMembercnd="month(Created)=$j AND year(Created) =$i";
		$MemberWhere="CoachId='".$Coachdetails['ID']."' And ".$strMembercnd." ";
		
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
        href="<?php echo SITE_ADDRESS;?>admintti/MemberPopupCoachComm.php?CoachID=<?php echo $Coachdetails['ID'];?>&from=<?php echo date($j."/01/".$i); ?>&to=<?php echo date($j."/t/".$i); ?>" class="total_amt" name="total_amt">
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
    <td width="2%">&nbsp;</td>
	</tr>
	</tbody></table>

</div>
<?php $month = strtotime("+1 month", $month); }?>
<?php }
if($_GET['view']=='week')
{ 
	
	$last_week_time=$last_date_time;
	if(date("D",$first_date_time)!="Mon")
	{
		$next_week_time = strtotime(date("m/d/Y",$first_date_time).'last monday');
	}
	else
	{
		$next_week_time=$first_date_time;
	}

$start = $week = $next_week_time;
$end = strtotime("+6 day", $last_week_time);

while($week < $end)
{
	$j=date("d-m-Y",$week);
	$i=date("d-m-Y",strtotime("+6 day",$week));
	$strWhere='date(Created)>="'.date('Y-m-d',strtotime($j)).'" and  date(Created)<="'.date('Y-m-d',strtotime($i)).'" and Type="CochingCommission"';
  
$CommissionDetailsWeeklyRecord=$utilObj->getMultipleRow('CommissionDetail' , $strWhere);
$totalamt='';
$totalComm='';	
?>
<span style="color: #235793;font-size: 18px;font-weight: bold;">For the week (<?php echo $j.' to '.$i; ?>)</span><br/>
<div class="subcontainer"><br>
	<table width="100%" cellspacing="0" cellpadding="3" border="0">
    	<tbody>
        	<tr id="headerbar">
    			<td width="3%">ID</td>
        		<td width="15%">Assigned Coach</td>
         		<td width="12%">Coach Type </td>
        		<td width="13%">Total Members </td>
        		<td width="16%">Program Name</td>
        		<td width="12%">Total Sale Amt</td> 
        		<td width="11%">Percentage</td>
        		<td width="11%">Commission</td>
        		<td width="5%">Staus</td>
       		</tr>
 <?php foreach($CommissionDetailsWeeklyRecord as $CommRowsVal){
 
	if($flag==0){
	
	$flag=1;
	
	$row_class="row-white";
	
	}else{
	
	$flag=0;
	
	$row_class = "row-tan";
	
	}

 ?>
   <tr class="tblrow_14138" id="<?php echo $row_class;?>">

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
		
		$strMembercnd='date(Created)>="'.date('Y-m-d',strtotime($j)).'" and  date(Created)<="'.date('Y-m-d',strtotime($i)).'"';
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
        href="<?php echo SITE_ADDRESS;?>admintti/MemberPopupCoachComm.php?CoachID=<?php echo $Coachdetails['ID'];?>&from=<?php echo date('Y-m-d',strtotime($j)); ?>&to=<?php echo date('Y-m-d',strtotime($i)); ?>" class="total_amt" name="total_amt">
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
    <td width="2%">&nbsp;</td>
	</tr>
	</tbody></table>

</div>
<?php
$week = strtotime("+7 day", $week); }	
}
 }else{?>
<div class="subcontainer"><br>

<table width="100%" cellspacing="0" cellpadding="3" border="0">
    <tbody><tr id="headerbar">
    
    <td>ID</td>
        <td>Assigned Coach</td>
        
         <td>Coach Type </td>

        <td>Total Members </td>

        <td>Program Name</td>
        
        <td>Total Sale Amt</td> 

        <td>Percentage</td>
        
        <td>Commission</td>
        
        <td>Staus</td>
        
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



