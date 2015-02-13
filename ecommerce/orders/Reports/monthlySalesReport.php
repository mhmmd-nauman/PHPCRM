<?php 
include_once '../../../include/header.php';

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
	$query_member="SELECT `FirstName`,`Surname`,`ReferrerCode` FROM `Member` WHERE `ID`=$memberid";
	$result_member_name=mysql_query($query_member) or die("Error in get member name at query $query_member by mysql error ".mysql_error());
	$row_member_name=mysql_fetch_array($result_member_name);
	if($select=='Name')
	{
		return $row_member_name['FirstName'].' '.$row_member_name['Surname'];
	}
	elseif($select=='AppCode')
	{
		return $row_member_name['ReferrerCode'];
	}
	
}
if(isset($_GET['view']))
{
	if($_GET['view']=='month')
	{
		
	}
}
?>
<script type="text/javascript" src="../../../js/search.js"></script>
<link rel="stylesheet" type="text/css" href="../../../css/search.css" />
<link href="<?php echo SITE_ADDRESS;?>/co_op/css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/Tooltip/js/tooltip.js"></script>
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
	$(function() {
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
</script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/Tooltip/css/tooltip.css" media="screen" />
<div id="headtitle"> E commerce Sales Report</div>
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
            <div style="color:#235793; font-size:18px; margin-top:8px;"><?php echo $DateInterval; ?>
            <input type="button" class="monthly_btn" value="&nbsp;" onclick="setview('month');" id="monthly" style="margin-right:8px;" />&nbsp;&nbsp;&nbsp;<input type="button" class="weekly_btn" value="&nbsp;" onclick="setview('week');"  style="margin-right:8px;" />&nbsp;&nbsp;&nbsp;<input type="button" class="daily_btn" value="&nbsp;" onclick="setview('daily');" style="margin-right:8px;"  />&nbsp;&nbsp;&nbsp;<input type="button" class="reset_btn" value="&nbsp;" onclick="setview('reset');" style="margin-right:8px;"  />&nbsp;&nbsp;&nbsp;
            <a id="MembersSheet" target="_blank" href="<?php if(isset($_GET['view'])){ if($_GET['view']=='month'){ echo "exportsalesreport.php?view=month"; }elseif($_GET['view']=='week'){ echo "exportsalesreport.php?view=week"; }elseif($_GET['view']=='daily'){ echo "exportsalesreport.php?view=daily";} }else{ echo "exportsalesreport.php?view=all"; } ?>"  style="float:right;" ><img src="/admintti/images/icon_download_excel.png" border="0" title="Export Excel Sheet" /></a></div>
            <script>
				$("#MembersSheet").fancybox();
				function setview(val)
				{
					if(val=="reset")
					{
						$('#MemberSearchForm').attr('action','?');
					}else
					{
						$('#MemberSearchForm').attr('action','?view='+val);
					}
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
              
              <div style="margin-bottom:5px;">
                <input type="button" onclick="setsearch();" class="adv_btn_2" style="float:right; margin-right: 25px;" value="" align="absmiddle" border="0" />
                <script>
				
				function setsearch(val)
				{
					$('#MemberSearchForm').attr('action','?date_start='+$('#fromdatepicker').val()+'&date_end='+$('#todatepicker').val());
					$('#MemberSearchForm').submit();
				}
			</script>
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
<?php

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
if(isset($_GET['view']))
{
	
	if($_GET['view']=='month')
	{ 
	?>
<div class="subcontainer" style="margin-bottom:10px;"> 
<div align="center" style="color:#993300;">Total Sales Data Monthly</div>

  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr id="headerbar">
      <td>Total Orders </td>
      <td>Total Amount Sold</td>
      <td>Product Names</td>
      <td>&nbsp;</td>
    </tr>
    <?php
	$total_sale_order='';
	$total_num_order='';
    $start = $month = $first_date_time;
$end = strtotime("+1 month", $last_date_time);
while($month < $end)
{
	$j=date("m",$month);
	$i=date("Y",$month);		  ?>
        <tr>
        	<td colspan="3"> For the month and year (<?php echo date("m",$month).'/'.date("Y",$month); ?>)</td>
        </tr>      
    <tr id="row-tan">
      
      <td><?php echo $total_num_order_result=get_Num_orders('',$j,$i,'','','','COUNT'); ?></td>
      <td>$<?php echo number_format($total_amount_order_result=get_Num_orders('',$j,$i,'','','','SUM'),2);?></td>
      <td><?php $result_product_ids=get_Num_orders('',$j,$i,'','','','');
	  		while($row_product_id=mysql_fetch_array($result_product_ids))
			{
				echo $product_name=get_product_name_by_id($row_product_id['ProductID']).' ,';
			}
	  ?></td>
      <td><a href="?month=<?php echo date("m",$month); ?>&year=<?php echo date("Y",$month); ?>" style="float:right;" >Details</a></td>
      <?php $total_sale_order=$total_sale_order+$total_amount_order_result;
	  $total_num_order=$total_num_order+$total_num_order_result; ?>
    </tr>
    <?php $month = strtotime("+1 month", $month); }?>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>
        <table>
        	<tr>
            	<td style="font-weight:bold;">Total amount of sale : $<?php echo number_format($total_sale_order,2); ?></td>
                <td style="font-weight:bold;">Total Number of orders : <?php echo $total_num_order; ?></td>
            </tr>
        </table>
        </td>
        
    <tr>
  </table>
</div>
<?php		
	}
	if($_GET['view']=='week')
	{ 
	$total_sale_order='';
	$total_num_order='';
	?>
<div class="subcontainer" style="margin-bottom:10px;"> 
<div align="center" style="color:#993300;">Total Sales Data Weekly</div>

  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr id="headerbar">
      <td>Total Orders </td>
      <td>Total Amount Sold</td>
      <td>Product Names</td>
      <td>&nbsp;</td>
    </tr>
    <?php
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
?>
<tr>
        	<td colspan="3"> For the week (<?php echo date("d-m-Y",$fisrdate_time).' to '.date("d-m-Y",strtotime("-1 day",$next_week_time)); ?>)</td>
        </tr>      
    <tr id="row-tan">
      
      <td><?php echo $total_num_order_result=get_Num_orders('','','',$j,$i,'','COUNT'); ?></td>
      <td>$<?php echo number_format($total_amount_order_result=get_Num_orders('','','',$j,$i,'','SUM'),2);?></td>
      <td><?php $result_product_ids=get_Num_orders('','','',$j,$i,'','');
	  		while($row_product_id=mysql_fetch_array($result_product_ids))
			{
				echo $product_name=get_product_name_by_id($row_product_id['ProductID']).' ,';
			}
	  ?></td>
      <td><a href="?date_start=<?php echo date("d-m-Y",$fisrdate_time); ?>&date_end=<?php echo date("d-m-Y",strtotime("-1 day",$next_week_time)); ?>" style="float:right;" >Details</a></td>
      <?php $total_sale_order=$total_sale_order+$total_amount_order_result;
	  $total_num_order=$total_num_order+$total_num_order_result; ?>
    </tr>
<?php
    $start = $week = $next_week_time;
$end = strtotime("+6 day", $last_week_time);
while($week < $end)
{
	$j=date("d-m-Y",$week);
	$i=date("d-m-Y",strtotime("+6 day",$week));		  ?>
        <tr>
        	<td colspan="3"> For the week (<?php echo date("d-m-Y",$week).' to '.date("d-m-Y",strtotime("+6 day",$week)); ?>)</td>
        </tr>      
    <tr id="row-tan">
      
      <td><?php echo $total_num_order_result=get_Num_orders('','','',$j,$i,'','COUNT'); ?></td>
      <td>$<?php echo number_format($total_amount_order_result=get_Num_orders('','','',$j,$i,'','SUM'),2);?></td>
      <td><?php $result_product_ids=get_Num_orders('','','',$j,$i,'','');
	  		while($row_product_id=mysql_fetch_array($result_product_ids))
			{
				echo $product_name=get_product_name_by_id($row_product_id['ProductID']).' ,';
			}
	  ?></td>
      <td><a href="?date_start=<?php echo $j; ?>&date_end=<?php echo $i; ?>" style="float:right;" >Details</a></td>
      <?php $total_sale_order=$total_sale_order+$total_amount_order_result;
	  $total_num_order=$total_num_order+$total_num_order_result; ?>
    </tr>
    <?php $week = strtotime("+7 day", $week); }
	if(strtotime("+1 day",$end)<$lastdate_time)
	{
	$j=date("d-m-Y",strtotime("+1 day",$end));
$i=date("d-m-Y",$lastdate_time);
	?>
    
<tr>
        	<td colspan="3"> For the week (<?php echo date("d-m-Y",strtotime("+1 day",$end)).' to '.date("d-m-Y",$lastdate_time); ?>)</td>
        </tr>      
    <tr id="row-tan">
      
      <td><?php echo $total_num_order_result=get_Num_orders('','','',$j,$i,'','COUNT'); ?></td>
      <td>$<?php echo number_format($total_amount_order_result=get_Num_orders('','','',$j,$i,'','SUM'),2);?></td>
      <td><?php $result_product_ids=get_Num_orders('','','',$j,$i,'','');
	  		while($row_product_id=mysql_fetch_array($result_product_ids))
			{
				echo $product_name=get_product_name_by_id($row_product_id['ProductID']).' ,';
			}
	  ?></td>
      <td><a href="?date_start=<?php echo $j; ?>&date_end=<?php echo $i; ?>" style="float:right;" >Details</a></td>
      <?php $total_sale_order=$total_sale_order+$total_amount_order_result;
	  $total_num_order=$total_num_order+$total_num_order_result; ?>
    </tr>
    <?php }?>
    <tr>
    	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>
        <table>
        	<tr>
            	<td style="font-weight:bold;">Total amount of sale : $<?php echo number_format($total_sale_order,2); ?></td>
                <td style="font-weight:bold;">Total Number of orders : <?php echo $total_num_order; ?></td>
            </tr>
        </table>
        </td>
        
    <tr>

  </table>
</div>
<?php		
	}
elseif($_GET['view']=='daily')
{?>
<div class="subcontainer" style="margin-bottom:10px;"> 
<div align="center" style="color:#993300;">Total Sales Data Daily</div>

  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr id="headerbar">
      <td>Total Orders </td>
      <td>Total Amount Sold</td>
      <td>Product Names</td>
      <td>&nbsp;</td>
    </tr>
<?php 
$total_sale_order='';
$total_num_order='';
$start = $day = $first_date_time;
$end = strtotime("+1 day", $last_date_time);
while($day < $end)
{
	$j=date("d-m-Y",$day);
	$i=date("d-m-Y",$day);
?>    
	<tr id="row-tan">
      <td><?php echo $total_num_order_result=get_Num_orders('','','',$j,$i,'','COUNT'); ?></td>
      <td>$<?php echo number_format($total_amount_order_result=get_Num_orders('','','',$j,$i,'','SUM'),2);?></td>
      <td><?php $result_product_ids=get_Num_orders('','','',$j,$i,'','');
	  		while($row_product_id=mysql_fetch_array($result_product_ids))
			{
				echo $product_name=get_product_name_by_id($row_product_id['ProductID']).' ,';
			}
	  ?></td>
      <td><a href="?date_start=<?php echo $j; ?>&date_end=<?php echo $i; ?>" style="float:right;" ><?php echo $j; ?></a></td>
      
    </tr>
    <?php $total_sale_order=$total_sale_order+$total_amount_order_result;
	  $total_num_order=$total_num_order+$total_num_order_result; ?>
<?php $day = strtotime("+1 day", $day); 
} ?> 
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td colspan="3"><span style="font-size:18px; font-weight:bold; color:#666;">Totals</span></td>
</tr>

<tr style="background-color: #99CC00; border-bottom: 1px solid #CCCCCC; color: #333333; font-size: 17px; font-style: normal;   font-weight: bold; height: 25px;text-decoration: none;text-indent: 4px;">
	<td><?php echo $total_num_order; ?></td>
    <td>$<?php echo number_format($total_sale_order,2);?></td>
    <td></td>
    <td></td>
</tr>   
 </table>
 </div>
 
<?php
}}else {
?>
<div class="subcontainer" style="margin-bottom:10px;"> 
<div align="center" style="color:#993300;">Total Sales Data Daily</div>
<?php $total_sale_order='';
$total_num_order='';
$start = $day = $first_date_time;
$end = $last_date_time;
while($day <= $end)
{
	$j=date("d-m-Y",$day);
	$i=date("d-m-Y",$day);?>	<span style="font-weight:bold"><?php

	echo date("l jS F Y",$day);
	?><br/></span><br/>
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr id="headerbar">
      <td width="82">Order Id</td>
      <td width="97">Type</td>
      <td width="162">Name</td>
      <td width="123">Refferal Partner</td>
      <td width="158">Invoice Total</td>
      <td width="70">Date</td>
      <td width="111">Status</td>
      <td width="279">Product Name</td>
    </tr>
    <?php $result_for_day=get_Num_orders('','','',$j,$i,'','ALL');
	$count_result=mysql_num_rows($result_for_day);
	if($count_result>0)
	{
		
		
		while($row_for_day=mysql_fetch_array($result_for_day))
		{ ?>
    <tr id="row-tan">
      <td><?php echo $row_for_day['ID']; ?></td>
      <td><?php echo $row_for_day['Type']; ?></td>
      <td><?php echo get_member_by_id($row_for_day['MemberID'],'Name'); ?></td>
      <td><?php echo get_member_by_id($row_for_day['CoachId'],'Name'); ?></td>
      <td>$<?php echo number_format($row_for_day['BillingAmount'],2); ?></td>
      <td><?php echo date("d/m/Y",strtotime($row_for_day['Created'])); ?></td>
      <td style="color:#FF0000"><?php echo $row_for_day['Status']; ?></td>
      <td><?php echo get_product_name_by_id($row_for_day['ProductID']); ?></td>
    </tr>
    <?php }}elseif($count_result==0){
		
		?>
    <tr>
    	<td colspan="7" align="center"><span style="color:#F00;">NO ORDER</span></td>
    </tr>
    <?php }?>
    </table><br/>
<?php $day = strtotime("+1 day", $day);} }?>
<? include "../../../lib/bottomnav.php" ?>
<? include "../../../include/footer.php"; ?>