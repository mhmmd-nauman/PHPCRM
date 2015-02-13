<?php
include "../../include/header.php"; 
$objorder = new Orders();
$objproducts = new Products();
$objclient = new Clients();
$objCompany = new Company();
$objUser = new Users();

if($_REQUEST['Task'] == 'del'){
	if($_REQUEST['todo'] == 'hidefromlist'){
		$updateOrder = $objorder->hideOrder($_REQUEST['id']); 
		echo "@@@@@@@@@@Hidden";
		exit;
	}elseif($_REQUEST['todo'] == 'deletepermanently'){
		$deletedorder = $objorder->DeleteOrder($_REQUEST['id']); 
		echo "@@@@@@@@@@DeletedPer";
		exit;
	}
}

if(!empty($_REQUEST['Task']) and $_REQUEST['Task'] == 'UpdatePayment'){
	extract($_REQUEST);
	$updateOrder = $objorder->UpdatePaymentForOrders($OrderID,$Paidthrough,$Notes); 
	if($updateOrder == 1){
		echo "@@@@@@@@@@Success";
		exit;
	}else{
		echo "@@@@@@@@@@Failed";
		exit;
	}
	exit;
}

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
}

# Search Filter For text starts here

if(!empty($_SESSION['MemSearchText'])){
	if(!empty($Search)){
		$Search .= " AND ".ECORDERITEM.".CompanyName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".FirstName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".SurName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".Email LIKE '%".trim($_SESSION['MemSearchText'])."%' ";
	}else{
		$Search .= " ".ECORDERITEM.".CompanyName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".FirstName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".SurName LIKE '%".trim($_SESSION['MemSearchText'])."%' OR ".ECORDERITEM.".Email LIKE '%".trim($_SESSION['MemSearchText'])."%' ";
	}
}
# Search filter for text search ends here

# Date Search starts here
if(!empty($Search)){
	$Search .= " AND ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."' ";
}else{
	$Search .= " ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."' ";
}
# Date Search ends Here

#pay later search starts
/*
if(!empty($_SESSION['payLater'])){
	if($_SESSION['payLater']=="1"){
		if(!empty($Search)){
			$Search .= " AND ".ECORDERITEM. ".Status='Unpaid' ";
		}else{
			$Search .= " ".ECORDERITEM.".Status='Unpaid' ";
		}
	}
}
*/

if(!empty($_SESSION['status'])){
	if($_SESSION['status']!="0") {
		if($_SESSION['status']=="1") {
			$matchIt = "Unpaid";
		} else if($_SESSION['status']=="2"){
			$matchIt = "Paid";
		}
		else if($_SESSION['status']=="3"){
			$matchIt = "Refunded";
		}
		else if($_SESSION['status']=="4"){
			$matchIt = "Cancelled";
		}
		else if($_SESSION['status']=="5"){
			$matchIt = "ChargeBack";
		}
		if(!empty($Search)){
			$Search .= " AND ".ECORDERITEM. ".Status='" . $matchIt . "' ";
		}else{
			$Search .= " ".ECORDERITEM.".Status='" . $matchIt ."' ";
		}
	}
	

}
#pay later search ends


# Company Wise search starts here
if(!empty($_SESSION['CompanyName'])){
	if(!empty($Search)){
		$Search .= " AND Company.ID = '".$_SESSION['CompanyName']."'";
	}else{
		$Search = " Company.`ID` = '".$_SESSION['CompanyName']."' ";
	}
}
# Company Wise search ends here
if($_REQUEST['Task'] == 'SetFilter'){

# Agent Wise search starts here
	if(!empty($_POST['select_agent'])){
		$all_agents = $for_dropdown_preselected_agents = "";
		$len = count($_POST['select_agent']);
		$i = 0;
		foreach((array)$_POST['select_agent'] as $agents){
			if($i == $len - 1){
				$all_agents .= "'".$agents."'";
				$for_dropdown_preselected_agents .= $agents;
			}else{
				$all_agents .= "'".$agents."',";
				$for_dropdown_preselected_agents .= $agents.",";
			}
			$i++;
		}

		if(!empty($search)){
			$Search .= " AND Users.`ID` IN ($all_agents)";
			
		}else{
			$Search = " Users.`ID` IN ($all_agents)";
		}
	   
	}
}

#
$AgentCompanyID = $_SESSION['Member']['CompanyID'];
if(!in_array(2, (array)$_SESSION['user_in_groups'])){
	if(!empty($Search)){
		$Search .= " and `Company`.ID = '$AgentCompanyID' ";
	}else{
		$Search .= " `Company`.ID = '$AgentCompanyID' ";
	}
}elseif(in_array(2, (array)$_SESSION['user_in_groups'])){
	//
}

// $Users_array = $objusers->GetAllUsersForUsersPage(" $Search ORDER BY Created DESC ",array(USERS.".*",ZONES.".Name")); 

if($Search == ''){
	$Search = ' 1 ';
}

$Search .= " ORDER BY ".ECORDERITEM.".`Created` DESC";

$ClientOrdersRows = $objorder->GetAllOrderWithCompanyMatch($Search, array(ECORDERITEM.".*"));

?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
<script type='text/javascript' src="<?php echo SITE_ADDRESS;?>js/jquery-multiselect.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery.multiselect.css" />
<style type="text/css">
.paid{
	background-color:#78CD51;
	color:#fff;
	font-weight: bold;
}
.unpaid{
	background-color:#F6846C;
	color:#fff;
	font-weight: bold;
}
.refunded{
	background-color:#FFEA6A;
	color:inherit;
	font-weight: bold;
}
#Order_List a{
	color:#fff !important;
}
.con_name{
	background-color: #FFFFFF;
    border: 1px solid #CCCCCC;
    border-radius: 2px;
    color: #000000;
    font-size: 12px;
    font-weight: normal;
    margin: 5px;
    padding: 2%;
    text-align: center;
}
.con_details{
	color: #444444;
    font-family: 'Trebuchet MS';
    font-size: 14px;
    font-weight: normal;
    margin-left: 6px;
    padding: 1%;
    text-align: center;
    width: auto;
}

.mynewclass{
	background-color: #e1e1e1;
    border-radius: 5px;
    display: none;
    height: 120px;
    margin-left: 60px;
    margin-top: -6px;
    position: absolute;
    width: 210px;
    z-index: 1;
	text-align: center;
	border: 1px solid #9598BC;
}
.showdetails{
	background-color: #78CD51;
    border: 1px solid #72A53B;
    color: #FFFFFF;
    cursor: pointer;
    font-family: "OpenSansSemiBold",Helvetica,Arial,sans-serif;
    font-size: 13px;
    font-style: normal;
    font-weight: normal;
    line-height: 20px;
    margin-top: 5px;
    padding: 3px 24px;
    text-align: center;
    text-decoration: none;
    text-rendering: optimizelegibility;
    text-transform: none;
    transition: all 0.15s ease 0s;
    vertical-align: middle;
    white-space: normal;
}
.showdetails:hover{
	background-color: #6BB24A;
}
.mynewclass a{
	color:#fff !important;
	text-decoration:none !important;
}

.ui-multiselect{
	height:30px !important;
	width:320px !important;
}
.agent_dropdown
{
	font-size:12px;
	padding-left:13px;
	padding-top:6px;
}
.mynewclass_unpaid{
	height: auto !important;
    padding: 5px !important;
	width:210px !important;
}
.showtime{
	display:none;
	background: none repeat scroll 0 0 lightyellow;
	border:1px solid #C9C95C;
    font-size: 15px;
    left: 80px;
    padding: 7px;
    position: absolute;
}
</style>

<script type="text/javascript">
$(function(){
	$("#fromdatepicker").datepicker();
	$("#todatepicker").datepicker();
	
	$("#userAdd").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	
	$(".openclientedit").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	
	$(".orderdetail").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	
	$(".orderdetail1").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	
	$(".orderinvoice").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	$(".ordercc").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	
	$(".CollectPayment").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	
	$(".mark_as_unpaid").click(function(e){
		e.preventDefault();
	});
	
	<?php
	if(!empty($for_dropdown_preselected_agents)){
	?>
		var agents_selected = '<?php echo trim($for_dropdown_preselected_agents,","); ?>';
		$.each(agents_selected.split(","), function(i,e){
			$("#select_agent option[value='"+e+"']").prop("selected", true);
		});
	<?php
	}
	?>
});	

/* Added by Amol on 17th March for shoing the agent drop down for multi select */
$(function(){
	$("#select_agent").multiselect();
	$(".ui-helper-reset li").first().remove();
});
</script>

<div id="headtitle">Orders</div>

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
     	</table>
        </div>
        <div class="search_row" style="margin-top:10px;">
        	Select Company:<br/>
        	<?php
				# If you want to get the drop down already made then pass "html" as
				# the parameter to this fucntion else it will retun array of records.
            	echo $CompanyDrop = $objCompany->getAllComapnyDropdown("html",$_SESSION['CompanyName'], "319");
			?>
        </div>
        <!-- added on 17th march -->
        <div class="agent_dropdown">
        <label>Agents:</label><br />
		<?php
			$AllAgents = $objUser->GetAllUsers(" 1 order by FirstName ASC ", array("Users.ID","Users.FirstName","Users.LastName","Users.Email"));
			$agent_drop = "";
			$agent_drop .= "<select name='select_agent[]' id='select_agent' multiple='multiple'>";
			foreach((array)$AllAgents as $Single_agent){
				$Agent_ID = $Single_agent['ID'];
				$Agent_name = $Single_agent['FirstName']." ".$Single_agent['LastName'];
				$agent_drop .= "<option value='$Agent_ID'>$Agent_name</option>";
			}
			$agent_drop .= "</select>";
			echo $agent_drop;
        ?>
        
        <select name="status" class="product" style="width:319px; margin-top: 10px;">
        	<option value="0" <?php if(empty($_SESSION['status'])){ echo "selected"; } ?>>Select Status</option>
            <option value="1" <?php if(!empty($_SESSION['status'])){if($_SESSION['status'] == "1"){echo "selected"; }} ?>>Unpaid</option>
            <option value="2" <?php if(!empty($_SESSION['status'])){if($_SESSION['status'] == "2"){echo "selected"; }} ?>>Paid</option>
            <option value="3" <?php if(!empty($_SESSION['status'])){if($_SESSION['status'] == "3"){echo "selected"; }} ?>>Refunded</option>
			<option value="4" <?php if(!empty($_SESSION['status'])){if($_SESSION['status'] == "4"){echo "selected"; }} ?>>Cancelled</option>
            <option value="5" <?php if(!empty($_SESSION['status'])){if($_SESSION['status'] == "5"){echo "selected"; }} ?>>ChargeBack</option>
        </select>
        </div>
        <!-- upto here -->
        <br />
        
        <!--
        
        <label> <input name="payLater" value="0" type="radio" checked />Show all orders</label>
        <label> <input name="payLater" value="1" type="radio" />Show unpaid only</label>
        -->
        <div style="margin-bottom:5px;">
          <input type="submit" name="Submit" class="adv_btn_2" style="float:right; margin-right: 25px;" value="" align="absmiddle" border="0" />
          <div style="clear:both;"></div>
        </div>
      </div>
    </div> 

    <!--<div style="float:right; position: absolute; right: 60px; top: 133px;">
      <a href="#" id="filterBtn" title="Filter ">Filter</a>
    </div>
    -->
    <div style="float:right; position: absolute; right: 20px; top: 133px;" title="Export&Download">
    	<a href="OrderExportPopup.php?Task=Add" id="userAdd" title="Export&Download"><img src="../../images/icon_download_excel.png" title="Export&Download" /></a>
    </div>
    <div style="clear:both;"></div>
</form>
</div>

<?php
if($_REQUEST['flag']=='add'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<tr>
    		<td colspan="3" id="message_success">Package Record Added Successfully!</td>
    	</tr>
    </table>
<?php } 
if($flag == 'del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td  colspan="3" id="message_success">Order Record Seleted Successfully!<td>
        </tr>
    </table>
<?php }
if($_REQUEST['flag']=='success'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td colspan="3" id="message_success">Package Record Updated Successfully!</td>
        </tr>
    </table>
<?php }
if($_REQUEST['flag'] == 'error'){ ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td colspan="3" id="message_error">&nbsp;</td>
        </tr>
    </table>
<?php } ?>
<div class="subcontainer">
<div class="" style="margin:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Order_List" style="display:none;">
    <thead>
        <tr id="headerbar">
            <td>Date</td>
            <td>Order ID</td>
            <td>Business Name</td>
            <td>Full Name</td>
            <td>Email</td>
            <td>Phone</td>
            <td>Product Name</td>
            <td>Status</td>
            <td>Agent</td>
            <td>Company</td>
            <td>Total Price</td>
            <td align="center">Actions</td>
        </tr>
    </thead>
   <tbody> 
       
  <?php
	foreach((array)$ClientOrdersRows as $OrdersRows){
	$orderid = $OrdersRows['ID'];
	$productID = $objorder->GetAllOrderDetail("OrderID = $orderid ",array("*"));
	if($flag == 0){
		$flag = 1;
		$row_class = "row-white";
	}else{
		$flag = 0;
		$row_class = "row-tan";
	}
  ?>
  <tr id="<?php echo $row_class;?>" class="remove_<?php echo $OrdersRows['ID']; ?>">
   <td  style="width:100px;" onMouseOver="showtime(this);" onMouseOut="hidetime(this);"><span class="showtime"><?php echo date('H:i A', strtotime($OrdersRows['Created'])); ?></span><?php echo date("<b>M d</b>, Y",strtotime($OrdersRows['Created'])); ?> </td>
   <!--<td  style="width:100px;"><?php echo date("<b>M d</b>, Y",strtotime($OrdersRows['Created'])); ?> </td>-->
   <td><?php echo $OrdersRows['ID']; ?> </td>
   <td><?php echo "<a style='color:#238FDB !important;' class='openclientedit' href='../../clients/ClientsEdit.php?id=".$OrdersRows['MemberID']."'>".$OrdersRows['CompanyName']."</a>"; ?> </td>
   <td><?php echo $OrdersRows['FirstName']." ".$OrdersRows['Surname'];?> </td>
   <td><?php echo $OrdersRows['Email'];?> </td>
   <td><?php echo $OrdersRows['Phone'];?> </td>
   <td><?php echo $productID[0]['ProductName'];?></td>
   <td style="width:100px; text-align:center; color:#666666;" onmouseout='hidetooltip(this);' class="<?php if($OrdersRows['Status'] == 'Paid' || $OrdersRows['Status'] == "Active" || $OrdersRows['Status'] == 1) { ?>paid<?php }elseif($OrdersRows['Status'] == 'Unpaid' || $OrdersRows['Status'] == 'ChargeBack'){ ?>unpaid<?php } elseif($OrdersRows['Status'] == 'Refunded' || $OrdersRows['Status'] == 'Cancelled'){ ?>refunded<?php }else{ ?>unpaid<?php } ?>" >
   
   
   <?php if($OrdersRows['Status'] == "Unpaid"){ ?>
   		<div onmouseout='hidetooltip(this);' onmouseover='showtooltip(this);' class="showonlyone">
            <div class="mynewclass" style="display: none;" >
                <div class="con_name"><?php echo $OrdersRows['CompanyName']; ?></div>
                <div class="con_details">
                    <?php echo $OrdersRows['FirstName']." ".$OrdersRows['Surname'];?>
                </div>
                <a href="#" class="openpopup" id="<?php echo $OrdersRows['ID']; ?>"><input type="button" value="Mark As Paid" class="showdetails"></a>
            </div>
        </div>
   		<a title="Payment For Order ID - <?php echo $OrdersRows['ID']; ?>" onmouseover='showtooltip(this);' class="CollectPayment" href="../../clients/CollectPayment.php?OrderID=<?php echo $OrdersRows['ID'];?>&MemberID=<?php echo $OrdersRows['MemberID']; ?>">
   <?php
   }elseif($OrdersRows['Status'] == "Paid" || $OrdersRows['Status'] == "Cancelled" || $OrdersRows['Status'] == "Refunded" || $OrdersRows['Status'] == "ChargeBack"){
   ?>
   		<div onmouseout='hidetooltip(this);' onmouseover='showtooltip(this);' class="showonlyone">
            <div class="mynewclass mynewclass_unpaid" style="display: none;" >
                <div class="con_name"><?php echo $OrdersRows['CompanyName']; ?></div>
                <div class="con_details" style="text-align:left !important;">
                   Client Name: <?php echo $OrdersRows['FirstName']." ".$OrdersRows['Surname']; ?><br/>
                   &nbsp;Date Ordered: <?php echo date("<b>M d</b>, Y", strtotime($OrdersRows['Created'])); ?><br/>
                   &nbsp;Date Paid   : <?php echo date("<b>M d</b>, Y", strtotime($OrdersRows['Created'])); ?><br/>
                   &nbsp;Payment Time: <?php echo date('H:i A', strtotime($OrdersRows['Created'])); ?><br/>
                   <?php
                   if($OrdersRows['Status'] == "Refunded"){
				   ?>
                   		&nbsp;Date Refunded: <?php if(!empty($OrdersRows['Updation_Date']) and $OrdersRows['Updation_Date'] != "0000-00-00 00:00:00") { echo date("<b>M d</b>, Y", strtotime($OrdersRows['Updation_Date'])); } else echo date("<b>M d</b>, Y", strtotime($OrdersRows['Created'])); ?><br/>
                   <?php
				   }
				   ?>
                </div>
                <a href="#" class="openpopup_unpaid" id="<?php echo $OrdersRows['ID']; ?>"><input type="button" value="Mark As" class="showdetails"></a>
            </div>
        </div>
   		<a title="Payment For Order ID - <?php echo $OrdersRows['ID']; ?>" <?php if($OrdersRows['Status'] == "Cancelled" || $OrdersRows['Status'] == "Refunded") { ?> style="color:#666666 !important;" <?php } ?> onmouseover='showtooltip(this);' class="mark_as_unpaid" href="javascript:void(0);">
   <?php
   }
   if($OrdersRows['Status'] == "Active" || $OrdersRows['Status'] == 1 || $OrdersRows['Status'] == "Paid") echo "Paid"; else echo $OrdersRows['Status']; ?>
   </a>

   </td>
   <!-- Used the User ID to fetch the name of the Agent and show in this column here. -->
   <td>
	<?php
		$AgentID = "";
		$AgentID = trim($OrdersRows['SubmitedBy']);
        echo $GetAgent = $objclient->FetchAgentName($AgentID);
	?>
   </td>
   <td>
   	<?php 
	if(isset($CompanyID)){
		// get query;
		//echo $CompanyID;
	} 
	
	echo $objclient->FetchAgentCompanyName($AgentID);

	 ?>
   
   </td>
   
   <!--<td > <a id="orderdetail<?php echo $OrdersRows['ID'];?>" href="<?php echo SITE_ADDRESS."ecommerce/orders/EcClientsOrderDetail.php"?>?id=<?php echo $OrdersRows['MemberID'];?>&Task=configration" title="<?php echo $OrdersRows['CompanyName']." - ".$OrdersRows['FirstName']." ".$OrdersRows['Surname']." - ".$OrdersRows['Phone'];?>"> $<?php echo number_format($OrdersRows['TotalPrice'],2);?></a> </td>-->
   <td>$<?php echo number_format($OrdersRows['TotalPrice'],2);?></td>
   <td  align="center" style="width:130px;">
       <?php if($_SESSION['isAdmin']  == 1){?>
   <a href="<?php echo SITE_ADDRESS."ecommerce/orders/ViewCC.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>" class="ordercc" id="ordercc<?php echo $OrdersRows['ID'];?>" title="View CC for <?php echo $OrdersRows['ID'];?>"> <img src="../../images/icon_cc_view.png" border="0" /></a>
       <?php }?>
   <a href="<?php echo SITE_ADDRESS."ecommerce/orders/DownloadInvoice.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>" target="_blank" title=""> <img src="../../images/icon_find.png" border="0" /></a> 
   <a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrderListEdit.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>&Task=configration&MemberID=<?php echo $OrdersRows['MemberID'];?>" id="orderdetail1<?php echo $OrdersRows['ID'];?>" class="orderdetail1" title="<?php echo $OrdersRows['CompanyName']." - ".$OrdersRows['FirstName']." ".$OrdersRows['Surname']." - ".$OrdersRows['Phone'];?>"> <img src="../../images/icon_settings.png" border="0" /></a> 
   
   <a id="orderdetail<?php echo $OrdersRows['ID'];?>" class="orderdetail" href="<?php echo SITE_ADDRESS."ecommerce/orders/EcClientsOrderDetail.php"?>?id=<?php echo $OrdersRows['MemberID'];?>&Task=configration" title="<?php echo $OrdersRows['CompanyName']." - ".$OrdersRows['FirstName']." ".$OrdersRows['Surname']." - ".$OrdersRows['Phone'];?>"><img src="../../images/icon_page_edit.png" border="0" /></a>
   
   <a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersInvoice.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>&Task=configration&AgentID=<?php echo $AgentID; ?>" class="orderinvoice" id="orderinvoice<?php echo $OrdersRows['ID'];?>" title="Open Invoice"><img src="../../images/icon_print.png" border="0" /></a>
   
  
 	<a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersAndSubscriptionList.php"?>?id=<?php echo $OrdersRows['ID']; ?>&Task=del" class="deleteorhide" id="<?php echo $OrdersRows['ID']; ?>"> <img title="Delete From Database" src="../../images/icon_delete.png" border="0"/></a>
   
   </td>
       
  </tr>
 <?php } ?>
</tbody>
</table>
</div>

<div class="confirmation" style="display:none;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
    	<tr style="height: 30px;">
        	<td>Delete permanently from database</td>
            <td>&nbsp;</td>
            <td><input type="radio" name="performaction" id="deletepermanently" value="deletepermanently"></td>
        </tr>
        <tr style="height: 30px;">
        	<td>Hide from the list</td>
            <td>&nbsp;</td>
            <td><input type="radio" name="performaction" id="hidefromlist" value="hidefromlist"></td>
        </tr>
    </table>
</div>

<div class="updatepaydetails" style="display:none;">
	<table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
    	<tr>
        	<td>Paid Through:</td>
            <td>&nbsp;</td>
            <td>
            	<select class="product" name="paidthrough" id="paidthrough" style="width: 300px;">
                	<option value="">Select Payment Method</option>
                    <option value="cash">Cash</option>
                    <option value="check">Check</option>
                    <option value="cc">Credit Card</option>
                    <option value="echeck">eCheck</option>
                    <option value="other">Other</option>
                </select>
            </td>
        </tr>
        <tr>
        	<td>Notes:</td>
            <td>&nbsp;</td>
            <td>
            	<textarea id="notesforpaidthrough" style="width: 290px; height: 65px;"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <input type="hidden" value="" id="keeporderidhere">
                <input type="button" value="Update Payment" class="showdetails" id="updatepaymenttopaid" style="padding:7px 15px;">
                <span style="color:#F6846C; display:none;" class="wait_span">Please Wait...</span>
            </td>
        </tr>
    </table>
</div>

<div class="update_as_unpaid" style="display:none;">
    <table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
    	<tr>
        	<td>Mark Order As:</td>
            <td>&nbsp;</td>
            <td>
            	<select class="product" id="refund_or_cancel" style="width: 300px;">
                	<option value="">Mark As</option>
                    <option value="1">Cancelled</option>
                    <option value="2">Refunded </option>
                    <option value="3">Unpaid</option>
                    <option value="4">ChargeBack</option>
                </select>
            </td>
        </tr>
        <tr>
        	<td>Notes:</td>
            <td>&nbsp;</td>
            <td>
            	<textarea id="reasonfor_refund_or_cancel" style="width: 290px; height: 65px;"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <input type="hidden" value="" id="keeporderidhere">
                <input type="button" value="Update Payment" class="showdetails" id="updatepaymentto_unpaid" style="padding:7px 15px;">
                <span style="color:#F6846C; display:none;" class="wait_span">Please Wait...</span>
            </td>
        </tr>
    </table>
</div>

<link rel="stylesheet" href="https://datatables.net/release-datatables/extensions/TableTools/css/dataTables.tableTools.css">
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/dataTables/media/js/jquery.dataTables.js"></script>
<script src="https://datatables.net/release-datatables/extras/TableTools/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" src="https://datatables.net/release-datatables/extensions/TableTools/js/dataTables.tableTools.js"></script>


<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />

<?php include "../../include/footer.php" ?>
<script type="text/javascript">
$(".deleteorhide").click(function(e){
	e.preventDefault();
	var rowid = $(this).attr("id");
	var href = $(this).attr("href");
	
	$(".confirmation").dialog({
		resizable: true,
		height:200,
		width: 440,
		show: 'fade',
		title: 'Choose Action To Perform For Order ID - '+rowid,
		position: {my: "center", at: "center", of: window.top},
		modal: true,
		buttons: {
			"Delete": function() {
				var todo = $('input[name=performaction]:checked', '.confirmation').val();
				href = href+'&todo='+todo;
				$.ajax({
					url : href,
				}).done(function(data){
					if(data != ""){
						$(".remove_"+rowid+"").fadeOut(500);
					}
				});
				$(".confirmation").dialog( "close" );
			},
			Cancel: function() {
				$(".confirmation").dialog( "close" );
				return false;
			}
		}
	});
});



$(document).ready(function(){
var oTable = $('#Order_List').dataTable({
	"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
	"iDisplayLength": 10,	
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
//oTable.fnSort( [ [0,'desc']] );
});
$(window).load(function(){
	$('#Order_List').show();
});

$(document).ready(function(){
	$("#message_success").fadeOut(3000);
	$("#message_error").fadeOut(3000);
	$(".message_success").fadeOut(3000);
	$(".message_error").fadeOut(3000);
	
	$("#deletepermanently").click(function(){
		var $this = $(this);
		var $row = $this.closest('tr');
        $row.css('background-color', '#C7F4B2');
		$("#hidefromlist").closest('tr').css('background-color', '#fff');
	});
	
	$("#hidefromlist").click(function(){
		var $this = $(this);
		var $row = $this.closest('tr');
        $row.css('background-color', '#C7F4B2');
		$("#deletepermanently").closest('tr').css('background-color', '#fff');
	});
});

function showtooltip(this_pointer){
	$(".mynewclass").hide();
	$(this_pointer).parent().find(".mynewclass").show();
}
function hidetooltip(this_pointer){
	$(this_pointer).parent().find(".mynewclass").hide();
}

$(".openpopup").click(function(e){
	e.preventDefault();
	$("#paidthrough").val('');
	$("#notesforpaidthrough").val('');
	var OrderID = $(this).attr("id");
	$('.updatepaydetails').dialog({
    	width: "565",
    	height: "235",
		title : "Update payment status for Order ID "+OrderID+""
	});
	
	$("#keeporderidhere").val('');
	$("#keeporderidhere").val(OrderID);
});

$(".openpopup_unpaid").click(function(e){
	e.preventDefault();
	var OrderID = $(this).attr("id");
	$('.update_as_unpaid').dialog({
    	width: "565",
    	height: "235",
		title : "Update payment status for Order ID "+OrderID+""
	});
	
	$("#keeporderidhere").val('');
	$("#keeporderidhere").val(OrderID);
});

$("#updatepaymentto_unpaid").click(function(e){
	$('.wait_span').show();
	var OrderID = $("#keeporderidhere").val();
	var markas = $("#refund_or_cancel").val();
	var notes = $("#reasonfor_refund_or_cancel").val();
	if(OrderID != ""){
		$.ajax({
			url     : '<?php echo SITE_ADDRESS;?>/ajax/AllAjaxCalls.php',
			data    : {'Task': 'MarkAsCanceledOrPaid', 'OrderID': OrderID, 'Notes': notes, 'MarkAs': markas},
			success : function(data){
				if(data == "Success"){
					location.reload();
				}
			}
		});
	}
});

$("#updatepaymenttopaid").click(function(){
	$('.wait_span').show();
	var OrderID = $("#keeporderidhere").val();
	var paidthrough = $("#paidthrough").val();
	var notes = $("#notesforpaidthrough").val();
	if(OrderID != ""){
		$.ajax({
			url : 'OrdersAndSubscriptionList.php?Task=UpdatePayment&OrderID='+OrderID+'&Paidthrough='+paidthrough+'&Notes='+notes,
		}).done(function(data){
			var splitdata = data.split("@@@@@@@@@@");
			if(splitdata[1] == "Success"){
				$(".remove_"+OrderID+" td:nth-child(8)").removeClass("unpaid").addClass("paid").html("").html("Paid");
				$('.wait_span').hide();
				$(".updatepaydetails").dialog( "close" );
				location.reload();
			}
		});
	}
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
