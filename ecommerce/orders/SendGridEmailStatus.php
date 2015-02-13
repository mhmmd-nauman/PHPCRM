<?php
include "../../include/header.php"; 
$objorder = new Orders();
$objclient = new Clients();
$Search = '';

if(isset($_REQUEST['SearchText'])){
	$_SESSION['MemSearchText'] = $_REQUEST['SearchText'];
}
if(isset($_REQUEST['status'])){
	$_SESSION['status'] = $_REQUEST['status'];
}
if(isset($_REQUEST['payLater'])){
	$_SESSION['payLater'] = $_REQUEST['payLater'];
}
if(empty($_SESSION['OLFromDate']))
	$_SESSION['OLFromDate'] = date("m/d/Y",strtotime("-1 Week"));
if(empty($_SESSION['OLToDate']))
	$_SESSION['OLToDate'] = date("m/d/Y");


if($_REQUEST['Task'] == 'SetFilter'){
	if(isset($_REQUEST['FromDate']) || isset($_REQUEST['ToDate'])){
		$_SESSION['OLFromDate'] = (isset($_REQUEST['FromDate'])) ? $_REQUEST['FromDate'] : "";
		$_SESSION['OLToDate'] = (isset($_REQUEST['ToDate'])) ? $_REQUEST['ToDate'] : "";
		$_SESSION['CompanyName'] = (isset($_REQUEST['CompanyName'])) ? $_REQUEST['CompanyName'] : "";	
	}
	
	if(isset($_REQUEST['hidebillingemails']) and $_REQUEST['hidebillingemails'] == "on"){
		$_SESSION['hidebillingemails'] = 1;
	}else{
		$_SESSION['hidebillingemails'] = 0;
	}
}

if(!empty($_SESSION['MemSearchText'])){
	if(!empty($Search)){
		$Search .= " AND `SendGridEmail_Statuses`.`Email` LIKE '%".trim($_SESSION['MemSearchText'])."%' ";
	}else{
		$Search .= " `SendGridEmail_Statuses`.`Email` LIKE '%".trim($_SESSION['MemSearchText'])."%' ";
	}
}
# Search filter for text search ends here

# Date Search starts here
if(!empty($Search)){
	$Search .= " AND `SendGridEmail_Statuses`.`Created` >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND `SendGridEmail_Statuses`.`Created` <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."' ";
}else{
	$Search .= " `SendGridEmail_Statuses`.`Created` >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND `SendGridEmail_Statuses`.`Created` <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."' ";
}
# Search filter for Date search ends here

# Filters the results for only the emails which are sent out to the clients and not the test or billing ones.
if(!empty($_SESSION['hidebillingemails']) and $_SESSION['hidebillingemails'] == 1){
	if(!empty($Search)){
		$Search .= " AND `SendGridEmail_Statuses`.`Email` NOT LIKE '%billing@xurli.com%' AND `SendGridEmail_Statuses`.`Email` NOT LIKE '%test%' AND `SendGridEmail_Statuses`.`Email` NOT LIKE '%support@xurli.com%' AND `SendGridEmail_Statuses`.`Email` NOT LIKE '%corbin%' ";
	}else{
		$Search .= " `SendGridEmail_Statuses`.`Email` NOT LIKE '%billing@xurli.com%' AND `SendGridEmail_Statuses`.`Email` NOT LIKE '%test%' AND `SendGridEmail_Statuses`.`Email` NOT LIKE '%support@xurli.com%' AND `SendGridEmail_Statuses`.`Email` NOT LIKE '%corbin%' ";
	}
}

if(!empty($_POST['select_events']) and isset($_POST['select_events'])){

	$all_events = $for_dropdown_preselected_all_events = "";
	$len = count($_POST['select_events']);
	$i = 0;
	foreach((array)$_POST['select_events'] as $events){
		if($i == $len - 1){
			$all_events .= "'".$events."'";
			$for_dropdown_preselected_all_events .= $events;
		}else{
			$all_events .= "'".$events."',";
			$for_dropdown_preselected_all_events .= $events.",";
		}
		$i++;
	}

	if(!empty($Search)){
		$Search .= " AND `SendGridEmail_Statuses`.`Event` IN ($all_events)";
		
	}else{
		$Search = " `SendGridEmail_Statuses`.`Event` IN ($all_events)";
	}
}

if($Search == ''){
	$Search = ' 1 ';
}

$ClientSendgridEmail = $objorder->FetchClientSendgridEmailStatuses($Search, array("*"));

?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
<script type='text/javascript' src="<?php echo SITE_ADDRESS;?>js/jquery-multiselect.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery.multiselect.css" />
<script type="text/javascript">
$(function(){
	$("#fromdatepicker").datepicker();
	$("#todatepicker").datepicker();
	
	$("#select_event").multiselect();
	$(".ui-helper-reset li").first().remove();
});
</script>
<style type="text/css">
/*div.DTTT_container {
    float: right !important;
    margin: 0 auto 0 40% !important;
    position: absolute !important;
    text-align: center !important;
    width: 20% !important;
}*/
.ui-multiselect{
	height:30px !important;
	width:320px !important;
}
</style>

<div id="headtitle">Sendgrid Email</div>
<div class="filtercontainer">
    <form name="SearchForm" id="SearchForm" action="<?php $_SERVER['PHP_SELF'];?>?Task=SetFilter" method="post">
        <div class="adv_search">
          <div class="adv_search_sub">
            <div class="input_box">
              <input name="SearchText" id="mainsearch" class="input_box_2" type="text" value="<?php echo $_SESSION['MemSearchText'];?>" />
              <div id="show_options" class="aoo" tabindex="0" gh="sda" role="button" aria-label="Show search options" data-tooltip="Show search options" style="">
                <div class="aoq"></div>
              </div>
            </div>
            <div class="adv_btn">
              <input name="Submit" type="submit" class="adv_btn_2" value="" />
            </div>
            <div style="color:#235793; font-size:18px; float:left; margin-left:60px;">
                <?php echo date("M d",strtotime($_SESSION['OLFromDate']))." to ".date("M d",strtotime($_SESSION['OLToDate'])); ?>
            </div>
          </div>
          <div class="cate_main" id="cate_main" style="display:none;position:absolute; top:164px; z-index: 100000;">
            <div id="search_close" tabindex="0" role="button" class="Zy"></div>
            <div class="search_row" style="margin-top:10px;">
              <table cellpadding="0" cellspacing="0" width="100%" border="0">
                <tr>
                  <td>
                    From:<br />
                    <input type="text" id="fromdatepicker" name="FromDate" size="8" style="padding: 0px 4px; width: 139px;" value="<?php echo $_SESSION['OLFromDate']; ?>" />
                  </td>
                  <td valign="bottom"> To:<br />
                    <input type="text" id="todatepicker" name="ToDate" size="8" style="padding: 0px 4px; width: 139px;" value="<?php echo $_SESSION['OLToDate']; ?>"/>
                  </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                    <?php
                    $preselect = array();
                    if(!empty($for_dropdown_preselected_all_events)){
                        $preselect = explode(",",$for_dropdown_preselected_all_events);
                    }
                    ?>
                    <select name="select_events[]" id="select_event" multiple="multiple">
                        <option value="">Select Type</option>
                        <option value="processed" <?php if(in_array("processed",$preselect)){ echo "selected"; } ?>>Processed</option>
                        <option value="click" <?php if(in_array("click",$preselect)){ echo "selected"; } ?>>Clicks</option>
                        <option value="delivered" <?php if(in_array("delivered",$preselect)){ echo "selected"; } ?>>Delivered</option>
                        <option value="open" <?php if(in_array("open",$preselect)){ echo "selected"; } ?>>Opens</option>
                        <option value="dropped" <?php if(in_array("dropped",$preselect)){ echo "selected"; } ?>>Dropped</option>
                        <option value="bounce" <?php if(in_array("bounce",$preselect)){ echo "selected"; } ?>>Bounce</option>
                        <option value="unsubscribe" <?php if(in_array("unsubscribe",$preselect)){ echo "selected"; } ?>>Unsubscribes</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                	<td colspan="2"><input type="checkbox" name="hidebillingemails" <?php if($_SESSION['hidebillingemails'] == 1) echo "checked"; ?> />Show only clients emails</td>
                </tr>
            </table>
            <br />
            <div style="margin-bottom:5px;">
              <input type="submit" name="Submit" class="adv_btn_2" style="float:right; margin-right: 25px;" value="" align="absmiddle" border="0" />
              <div style="clear:both;"></div>
            </div>
          </div>
        </div> 
        <div style="clear:both;"></div>
    </form>
</div></div>
<div style="clear:both;"></div>
<div class="subcontainer" style="margin-top:22px;">
<div style="margin:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Order_List" style="display:none;">
    <thead>
        <tr id="headerbar">
            <td>Email</td>
            <td>Date</td>
            <td>Status</td>
            <td>IP</td>
            <td>URL</td>
        </tr>
    </thead>
    <tbody> 
    	<?php
		$flag = 0;
		if(count($ClientSendgridEmail) > 0){
			foreach((array)$ClientSendgridEmail as $each){
				if($flag == 0){
				$flag = 1;
				$row_class = "row-white";
				}else{
					$flag = 0;
					$row_class = "row-tan";
				}
				
				$img = "";
				$p = "../../images";
				if(!empty($each['Event']) and $each['Event'] == "open"){
					$img = "<img src='$p/open.png'>";
				}elseif(!empty($each['Event']) and $each['Event'] == "processed"){
					$img = "<img src='$p//processed.png'>";
				}elseif(!empty($each['Event']) and $each['Event'] == "delivered"){
					$img = "<img src='$p/delivered.png'>";
				}elseif(!empty($each['Event']) and $each['Event'] == "click"){
					$img = "<img src='$p/click.png'>";
				}elseif(!empty($each['Event']) and $each['Event'] == "dropped"){
					$img = "<img src='$p//drop.png'>";
				}elseif(!empty($each['Event']) and $each['Event'] == "bounce"){
					$img = "<img src='$p/bounce.png'>";
				}elseif(!empty($each['Event']) and $each['Event'] == "unsubscribe"){
					$img = "<img src='$p//unsubscribe.png'>";
				}
				$img_email = "<img src='$p/email_sendgrid.gif' height='30' width='30'>";

			?>
				<tr id="<?php echo $row_class;?>">
					<td><?php echo $img_email."&nbsp&nbsp;&nbsp;<span style='vertical-align:super;'>". $each['Email']."</span>"; ?></td>
					<td><?php echo date("<b>M d</b>, Y",strtotime($each['Timestamp']))."&nbsp;&nbsp;". date('H:i A', strtotime($each['Timestamp']) - 60 * 60 * 4); ?> EST</td>
					<td><?php echo $img."&nbsp&nbsp;&nbsp;<span style='vertical-align:super;'>". ucfirst($each['Event'])."</span>"; ?></td>
					<td><?php echo ucfirst($each['IPAddress']); ?></td>
					<td><?php echo $each['ClickedURL']; ?></td>
				</tr>
			<?php
			}
		}else{
		?>
        	<tr>
            	<td colspan="5" align="center">No Records</td>
            </tr>
        <?php
		}
		?>
    </tbody>
</table>
</div>
</div>

<link rel="stylesheet" href="https://datatables.net/release-datatables/extensions/TableTools/css/dataTables.tableTools.css">
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/dataTables/media/js/jquery.dataTables.js"></script>
<script src="https://datatables.net/release-datatables/extras/TableTools/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" src="https://datatables.net/release-datatables/extensions/TableTools/js/dataTables.tableTools.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />
<script type="text/javascript">

$(document).ready(function(){
<?php
if(count($ClientSendgridEmail) > 0){
?>

/*
$("#Order_List tbody tr td:nth-child(2)").each(function(){
	var html = $(this).html();
	var change;
	console.log($(this).html());
});
*/
var oTable = $('#Order_List').dataTable({
	"aLengthMenu": [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
	"iDisplayLength": 25,	
	"aaSorting": [],
	"sDom": 'T<"clear">lfrtip',
		
		"oTableTools": {
			"sSwfPath": "https://datatables.net/release-datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
			"aButtons": [
				"xls",
				{
					"sExtends": "pdf",
					"sPdfOrientation": "landscape",
					"sPdfMessage": "Your custom message would go here."
				},
				"print"
			]
		}
});
<?php
}
?>
//oTable.fnSort( [ [0,'desc']] );
});
$(window).load(function(){
	$('#Order_List').show();
});

function showtime(point){
	$this = $(point);
	$this.find(".showtime").css({"display":"block"});
}
function hidetime(point){
	$this = $(point);
	$this.find(".showtime").css({"display":"none"});
}
</script>
