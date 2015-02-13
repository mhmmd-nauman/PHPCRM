<?php
include "../../include/header.php"; 
$objorder = new Orders();
$objproducts = new Products();
$objclient = new Clients();
$objCompany = new Company();
$objUser = new Users();
$objMerchant = new MerchantAccount();

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
	
	
		$arg = array(
			"ManualPaymentDate" => date("y-m-d")
		);
		$updatePmtDate = $objorder->updateOrderFields("ID = $OrderID", $arg);
	
	
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

if(isset($_REQUEST['reset'])){
	
		unset($_SESSION['MemSearchText']);

		unset($_SESSION['dateFilter']);
	
		unset($_SESSION['status']);
	
		unset($_SESSION['payLater']);
	
		unset($_SESSION['listings']);
		unset($_SESSION['bulkAction']);
	
		$_SESSION['OLFromDate'] = date("m/d/Y",strtotime("-1 Week"));
	
		$_SESSION['OLToDate'] = date("m/d/Y");
		unset($_SESSION['productFilter']);
	
}

if(isset($_REQUEST['SearchText'])){
	$_SESSION['MemSearchText'] = $_REQUEST['SearchText'];
}

if(isset($_REQUEST['dateFilter'])){
	$_SESSION['dateFilter'] = $_REQUEST['dateFilter'];
}
if(isset($_REQUEST['status'])){
	$_SESSION['status'] = $_REQUEST['status'];
}
if(isset($_REQUEST['payLater'])){
	$_SESSION['payLater'] = $_REQUEST['payLater'];
}
if(isset($_REQUEST['listings'])){
	$_SESSION['listings'] = $_REQUEST['listings'];
}
if(isset($_REQUEST['bulkAction'])){
	$_SESSION['bulkAction'] = $_REQUEST['bulkAction'];
} else {
	unset($_SESSION['bulkAction']);	
}

if(empty($_SESSION['OLFromDate']))
	$_SESSION['OLFromDate'] = date("m/d/Y",strtotime("-1 Week"));
if(empty($_SESSION['OLToDate']))
	$_SESSION['OLToDate'] = date("m/d/Y");
if(empty($_SESSION['productFilter']))
	$_SESSION['productFilter'] = $_REQUEST['productFilter'];

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
if($_SESSION['dateFilter']=="created"){

	if(!empty($Search)){
		$Search .= " AND ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' 
			 AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate'])) . "'";
	}else{
		$Search .= " ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' 
			 AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate'])) . "'";
	}

} elseif($_SESSION['dateFilter']=="paid"){
	if(!empty($Search)){
		$Search .= "AND ".ECORDERITEM.".Status NOT IN('Unpaid', 'Closed') AND if(OrderItem.ManualPaymentDate='0000-00-00', ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."',OrderItem.ManualPaymentDate >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND OrderItem.ManualPaymentDate <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate'])) . "')  ";
	}else{
		$Search .= " ".ECORDERITEM.".Status NOT IN('Unpaid', 'Closed') AND if(OrderItem.ManualPaymentDate='0000-00-00', ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."',OrderItem.ManualPaymentDate >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND OrderItem.ManualPaymentDate <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate'])) . "')  ";
	}
} else {
	// new filter - created or paid
	if(!empty($Search)){
		$Search .= "AND  if(OrderItem.ManualPaymentDate='0000-00-00', ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."',OrderItem.ManualPaymentDate >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND OrderItem.ManualPaymentDate <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate'])) . "')  ";
	}else{
		$Search .= " if(OrderItem.ManualPaymentDate='0000-00-00', ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."',OrderItem.ManualPaymentDate >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND OrderItem.ManualPaymentDate <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate'])) . "')  ";
	}

}



//"AND if(OrderItem.ManualPaymentDate='0000-00-00', ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."',OrderItem.ManualPaymentDate >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND OrderItem.ManualPaymentDate <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate'])) . "')  ";


//$sql = "1 AND ".ECORDERITEM.".Status = 'Paid' AND if(OrderItem.ManualPaymentDate='0000-00-00', ".ECORDERITEM.".Created >= '$last_week_start_for_admin' AND ".ECORDERITEM.".Created <= '$last_week_end_for_admin',OrderItem.ManualPaymentDate >= '$last_week_start_for_admin' AND OrderItem.ManualPaymentDate <= '$last_week_end_for_admin')  ";

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


if(!empty($_REQUEST['status'])){
	foreach((array)$_REQUEST['status'] as $status){
		switch($status){
			case 1:
				$matchIt.= "1,";
				$inquery.= "'Unpaid',";
				$for_dropdown_preselected_status.="Unpaid,";
				break;
			case 2:
				$matchIt.= "2,";
				$inquery.= "'Paid',";
				$for_dropdown_preselected_status.="Paid,";
				break;
			case 3:
				$matchIt.= "3,";
				$inquery.= "'Refunded',";
				$for_dropdown_preselected_status.="Refunded,";
				break;
			case 4:
				$matchIt.= "4,";
				$inquery.= "'Cancelled',";
				$for_dropdown_preselected_status.="Cancelled,";
				break;
			case 5:
				$matchIt.= "5,";
				$inquery.= "'ChargeBack',";
				$for_dropdown_preselected_status.="ChargeBack,";
				break;
			case 6:
				$matchIt.= "6,";
				$inquery.= "'Closed',";
				$for_dropdown_preselected_status.="Closed,";
				break;
			
		}
		
		
		
		/*
		if($status!="0") {
			
		if($status=="1") {
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
		*/
		
		
		
		}
		
		
	$inquery = substr($inquery, 0, -1);
		$for_dropdown_preselected_status = substr($for_dropdown_preselected_status, 0, -1);
	if(!empty($Search)){
		$Search .= " AND ".ECORDERITEM. ".Status IN(" . $inquery . ")";
	}else{
		$Search .= " ".ECORDERITEM.".Status IN (" . $inquery .")";
	}
	
	

}
#pay later search ends


# Company Wise search starts here
/*
if(!empty($_SESSION['CompanyName'])){
	if(!empty($Search)){
		$Search .= " AND Company.ID = '".$_SESSION['CompanyName']."'";
	}else{




		$Search = " Company.`ID` = '".$_SESSION['CompanyName']."' ";
	}
}
*/
# Company Wise search ends here
if($_REQUEST['Task'] == 'SetFilter'){



if(!empty($_REQUEST['CompanyName'])){
	$all_companies = $for_dropdown_preselected_companies = "";
		$leng = count($_POST['CompanyName']);
		$i = 0;
		foreach((array)$_POST['CompanyName'] as $companies){
			if($i == $leng - 1){
				$all_companies .= "'".$companies."'";
				$for_dropdown_preselected_companies .= $companies;
			}else{
				$all_companies .= "'".$companies."',";
				$for_dropdown_preselected_companies .= $companies.",";
			}
			$i++;
		}
		
	if(!empty($Search)){
		$Search .= " AND Company.`ID` IN(" . $all_companies . ")";
	} else {
		$Search .= " Company.`ID` IN(" . $all_companies . ")";
	}
}
if(!empty($_REQUEST['productFilter'])) {
	// need a join	
	$all_products = $for_dropdown_preselected_products = "";
		$leng = count($_POST['productFilter']);
		$i = 0;
		foreach((array)$_POST['productFilter'] as $products){
			if($i == $leng - 1){
				$all_products .= "'".$products."'";
				$for_dropdown_preselected_products .= $products;
			}else{
				$all_products .= "'".$products."',";
				$for_dropdown_preselected_products .= $products.",";
			}
			$i++;
		}
	
	//$prods = $_REQUEST['productFilter'];
	
	if(!empty($Search)){
		$Search .= " AND OrderDetail.ProductID IN(" . $all_products . ")";
	} else {
		$Search .= " OrderDetail.ProductID IN(" . $all_products . ")";
	}
}

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
	   
	} else {
		$id = $_SESSION['Member']['ID'];
		//echo $id;
		if(in_array(16, (array)$_SESSION['user_in_groups'])){
			
			
			if(!empty($search)){
				$Search .= " AND Users.`ID` =" .$id;
				
			}else{
				$Search = " Users.`ID` =" .$id;
			}	
		}	
	}
} else {
	
	//echo $id;
		if(in_array(16, (array)$_SESSION['user_in_groups'])){
			$id = $_SESSION['Member']['ID'];
			
			if(!empty($search)){
				$Search .= " AND Users.`ID` =" .$id;
				
			}else{
				$Search = " Users.`ID` =" .$id;
			}	
		}	
		
}


if(!empty($_SESSION['payLater'])){
	if($_SESSION['payLater']=="later"){
		
			if(!empty($search)){
				$Search .= " AND OrderItem.PaidThrough ='Credit Card (Process Later)'";
				
			}else{
				$Search = " OrderItem.PaidThrough ='Credit Card (Process Later)'";
			}	
	}
}
//payLater
		
		
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
tr.odd td.sorting_1.paid, tr.even td.sorting_1.paid, .paid {
	background-color:#78CD51;
	color:#fff;
	font-weight: bold;
}
tr.odd td.sorting_1.unpaid, tr.even td.sorting_1.unpaid, .unpaid{
	background-color:#F6846C;
	color:#fff;
	font-weight: bold;
}
tr.odd td.sorting_1.unpaid.chargeback, tr.even td.sorting_1.unpaid.chargeback, .unpaid.chargeback{
	background-color:#FFEA6A;
	color:#000;
	font-weight: bold;
}
tr.odd td.sorting_1.unpaid.closed, tr.even td.sorting_1.unpaid.closed, .unpaid.closed{
	background-color:#ccc;
	color:#000;
	font-weight: bold;
}
#Order_List tr.odd td.sorting_1.unpaid.closed a, #Order_List tr.even td.sorting_1.unpaid.closed a,#Order_List  .unpaid.closed a{
	color:#555!important;
	font-weight: bold;
}
tr.odd td.sorting_1.refunded, tr.even td.sorting_1.refunded, .refunded{
	background-color:#FFEA6A;
	color:inherit;
	font-weight: bold;
}
#Order_List a{
	color:#fff !important;
}
#Order_List .refunded a,
#Order_List .chargeback a {
	color: #666!important;	
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
    height: 165px;
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
.madePaid {width: 65px;}
</style>

<script type="text/javascript">
$(function(){
	$("#fromdatepicker").datepicker();
	$("#todatepicker").datepicker();
	
	
	$("#userAdd").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	
	$(".editProduct").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"medium");
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
	
	<?php
	if(!empty($for_dropdown_preselected_products)){
	?>
		var products_selected = '<?php echo trim($for_dropdown_preselected_products,","); ?>';
		$.each(products_selected.split(","), function(i,e){
			$("#productFilter option[value='"+e+"']").prop("selected", true);
		});
	<?php
	}
	
	?>
	
	<?php
	if(!empty($matchIt)){
	?>
		var status_selected = '<?php echo trim($matchIt,","); ?>';
		$.each(status_selected.split(","), function(i,e){
			$("#status option[value='"+e+"']").prop("selected", true);
		});
	<?php
	}
	
	?>
	
	<?php
	if(!empty($for_dropdown_preselected_companies)){
	?>
		var companies_selected = '<?php echo trim($for_dropdown_preselected_companies,","); ?>';
		$.each(companies_selected.split(","), function(i,e){
			$("#companyName option[value='"+e+"']").prop("selected", true);
		});
	<?php
	}
	
	?>
	
});	

/* Added by Amol on 17th March for shoing the agent drop down for multi select */
$(function(){
	$("#select_agent, #productFilter, #status, #companyName").multiselect();
	$(".ui-helper-reset li").first().remove();
});
</script>
<?php //print_r($_REQUEST);
//echo $for_dropdown_preselected_status;?>
<div id="headtitle">Orders</div>
<div style="display: none"><?php echo $Search; ?></div>
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
        <label><input type="radio" value="created" name='dateFilter' <?php if($_SESSION['dateFilter'] == 'created' || empty($_SESSION['dateFilter'])){echo " checked";} ?>/>Created Date</label>
        <label><input type="radio" value="paid" name='dateFilter'  <?php if($_SESSION['dateFilter'] == 'paid'){echo " checked";} ?> />Paid Date</label>
        <label><input type="radio" value="both" name='dateFilter'  <?php if($_SESSION['dateFilter'] == 'both'){echo " checked";} ?> />Both</label>
        </div>
        <hr color="#eeeeee" />
        <div class="search_row" style="margin-top:10px;">
        	Select Company:<br/>
        	<?php
				# If you want to get the drop down already made then pass "html" as
				# the parameter to this fucntion else it will retun array of records.
            	$CompanyDrop = $objCompany->getAllComapnyDropdown("",$_SESSION['CompanyName'], "319");
				
				$select .= "<select name='CompanyName[]' id='companyName' class='product' style='width:319px' multiple='multiple'>";
				$select .= "<option value=''>Select a Company</option>";
				foreach((array)$CompanyDrop as $SingleCompany){
					$CompanyID = $SingleCompany['ID'];
					$Companyname = $SingleCompany['CompanyName'];
					
					$show = ($CompanyID == $selected) ? "selected" : "";
					
					$select .= "<option value='$CompanyID' $show>$Companyname</option>";
					$CompanyID = $Companyname = "";
				}
				$select .= "</select>";
				echo $select;
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
        <br /><br />
        <label>Status</label><br />
        <select name="status[]" class="product" id="status" style="width:319px; margin-top: 10px;" multiple="multiple">
        	<!--<option value="0" <?php if(empty($_SESSION['status'])){ echo "selected"; } ?>>Select Status</option>-->
            <option value="1" >Unpaid</option>
            <option value="2" >Paid</option>
            <option value="3" >Refunded</option>
			<option value="4" >Cancelled</option>
            <option value="5"  >ChargeBack</option>
            <option value="6"  >Closed</option>
        </select>
        
         <br /><br />
        <table width="94%" style="width: 319px"><tr>
        <td width="30%"><label>Payment</label>
        <select name="payLater" class="product" style="width:100%;">
         	<option value="now" <?php if(!empty($_SESSION['payLater'])){if($_SESSION['payLater'] == "now"){echo "selected"; }} else {echo "selected"; } ?>>Show All</option>
        	<option value="later" <?php if(!empty($_SESSION['payLater'])){if($_SESSION['payLater'] == "later"){echo "selected"; }} ?>>Pay Later</option>
           
        </select>
        </td>
        <td>
        <label>Listings Preview</label>
        <select name="listings" class="product" style="width: 100%;">
         	<option value="all" <?php if(!empty($_SESSION['listings'])){if($_SESSION['listings'] == "all"){echo "selected"; }} else {echo "selected"; } ?>>Show All</option>
        	<option value="sent" <?php if(!empty($_SESSION['listings'])){if($_SESSION['listings'] == "sent"){echo "selected"; }} ?>>Listings Preview Sent</option>
           
        </select>
        </td>
        </tr></table>
        
       
      <label>Product</label>
        <select name="productFilter[]" id="productFilter" multiple="multiple"  class="product" style="width:319px;">
        	<option value="0">Select</option>
            <?php
			$allProds = $objproducts->GetAllProduct("1", array("*"));
			foreach($allProds as $product){
				echo "<option value='" . $product['ID'] . "'>". $product['ProductName'] . "</option>";
			}
			?>
        
        </select>
        
        
        
        <br />
      <label><input type='checkbox' name='bulkAction' <?php if(isset($_SESSION['bulkAction'])){echo "checked";} ?> />Bulk actions</label>
        </div>
        <!-- upto here -->
       
        
        <br />
        
        
        <!--
        
        <label> <input name="payLater" value="0" type="radio" checked />Show all orders</label>
        <label> <input name="payLater" value="1" type="radio" />Show unpaid only</label>
        -->
        <div style="margin-bottom:5px;">
        <input type="button" value="Reset" value="reset" id="resetBtn" style="float: left; margin-left: 15px; border: 1px solid red; background: #eee; padding: 3px 10px" />
          <input type="submit" name="Submit" class="adv_btn_2" style="float:right; margin-right: 25px;" value="" align="absmiddle" border="0" />
          <div style="clear:both;"></div>
        </div>
      </div>
    </div> 

    <!--<div style="float:right; position: absolute; right: 60px; top: 133px;">
      <a href="#" id="filterBtn" title="Filter ">Filter</a>
    </div>
    -->
    <div style="float:right; position: absolute; right: 20px; top: 173px;" title="Export&Download">
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
        	<td  colspan="3" id="message_success">Order Record Deleted Successfully!<td>
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
<?php if(!empty($_SESSION['bulkAction'])){ ?>
<a href='javascript:;' id="bulkinator" style="position: absolute;">Mark as (bulk)</a>
<?php } ?>
<div class="" style="margin:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Order_List" style="display:none;">
    <thead>
        <tr id="headerbar">
        	<?php if(!empty($_SESSION['bulkAction'])){ ?><td>&nbsp;</td><?php } ?>
            <td> Created </td>
            <td> Paid</td>
            <td>Order ID</td>
            <td>Business Name</td>
            <td>Contact</td>
            <!--<td>Email</td>-->
            <!--<td>Phone</td>-->
            <td>Product Name</td>
            <td>Link</td>
            <td>Auth</td>
            <td>Status</td>
            <td>Agent</td>
            <td>Company</td>
            <td>CC #</td>
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
  
  <?php if($_SESSION['dateFilter']=="paid"){
	  if($OrdersRows['ManualPaymentDate']=="0000-00-00"){
		  
	  }
	  
  }
  $Client_Login_array = $objclient->GetUserLoginDetail("ID='".$OrdersRows['MemberID']."'",array("*"));
  # listings preview
  $showRow = true;
if(empty($_SESSION['listings']) || $_SESSION['listings']=="all") {
	$showRow = true;
} else {
	if($Client_Login_array[0]['Username']==""){
	$showRow = false;
	}
	
}
	
if($showRow===true){
 	?>
		  
  <tr id="<?php echo $row_class;?>" class="remove_<?php echo $OrdersRows['ID']; ?>" >
  	<?php if(!empty($_SESSION['bulkAction'])){ ?><td><input type='checkbox' class="bulk" name='select_<?php echo $OrdersRows['ID']; ?>' checked /></td><?php }?>
   <td  style="width:100px;" onMouseOver="showtime(this);" onMouseOut="hidetime(this);"><?php echo date("<b>M d</b> Y",strtotime($OrdersRows['Created'])); ?> <span class="showtime"><?php echo date('H:i A', strtotime($OrdersRows['Created'])); ?></span></td>
   <td nowrap>
   <?php //echo $OrdersRows['MemberID'];?>

	<?php 
		//echo date("y-m-d", strtotime($OrdersRows['Updation_Date']));
		$transaction = $objMerchant->GetAllTransactionResponce("CustomerID ='". $OrdersRows['MemberID'] . "' and ResponseCode='100'", array("*"));
		//print_r($transaction);
		
		if($OrdersRows['ManualPaymentDate']!="0000-00-00"){
			echo date("M d, Y", strtotime($OrdersRows['ManualPaymentDate']));
		} else if($transaction[0]['Created']!="" && $transaction[0]['Created']!="0000-00-00 00:00:00"){
				echo date("M d, Y", strtotime(	$transaction[0]['Created']));
			
		} else {
			if($OrdersRows['PaidThrough']=="echeck" and $OrdersRows['Status']!="Unpaid" ) {
				echo "<em>" . date("M d, Y", strtotime($OrdersRows['LastEdited'])) . "</em>";	
			}
		}
	?>
    
   </td>
   <!--<td  style="width:100px;"><?php echo date("<b>M d</b>, Y",strtotime($OrdersRows['Created'])); ?> </td>-->
   <td><?php echo $OrdersRows['ID']; ?> </td>
   <td><?php echo "<a style='color:#238FDB !important;' class='openclientedit' href='../../clients/ClientsEdit.php?id=".$OrdersRows['MemberID']."'>".$OrdersRows['CompanyName']."</a>"; ?> </td>
   <td onmouseout="hidetooltip(this);">
   <div onmouseout="hidetooltip(this);" onmouseover="showtooltip(this);" class="showonlyone">
   <?php echo $OrdersRows['FirstName']." ".$OrdersRows['Surname'];?> 
   <div class="mynewclass mynewclass_unpaid" style="display: none;">
                <div class="con_name">
					<?php echo $OrdersRows['Email'];?>
                </div>
                <div class="con_name">
                    <?php echo $OrdersRows['Phone'];?>
               	</div>
            </div>
   
   </div>
   
   

   </td>
   <!--<td> </td>-->
   <!--<td> </td>-->
   <td><a href="<?php echo SITE_ADDRESS; ?>ecommerce/orders/changePrice.php?order=<?php echo $OrdersRows['ID'];?>" class="editProduct" title="Edit Product Info" style="color: #000!important; text-decoration:none;"><?php echo $productID[0]['ProductName'];?></a>
   </td>
   
   <td align="center">
   <?php 
   if($Client_Login_array[0]['Username']!=""){
	   echo "<span style='display: none'>Link</span><a href='".$Client_Login_array[0]['Username']."' target='_blank'><img src='http://www.iboxsolutions.com/wp-content/uploads/2011/08/internet_icon1.png' width='15' /></a>";
   } else {
		echo "<span style='display: none'>None</span>";   
   }
   ?> 
   </td>
   <td>
   <?php 
   
   $status = $objorder->GetAllOrderDetail("OrderId = '" .$OrdersRows['ID'] . "'", array("*"));
   $verified = $status[0]['Verified'];
   if($verified=="1"){
	   $class = 'signed';
	   echo "<span style='display:none'>signed</span><img src='" . SITE_ADDRESS. "images/message_success.png' width='18'/>";
   } else {
		$class='unauthorized';   
		echo "<span style='display:none'>unsigned</span><img src='" . SITE_ADDRESS. "images/message_error.png' width='18'/>";
   }
   if($isPartial==true){
	$class .= " partial";   
   } 
   
   ?>
   </td>
   
   <?php 
   
   // get var for partial paid
   $sql1 = "select * from `Payments` WHERE ClientID=" . $OrdersRows['MemberID']; 
					$result = mysqli_query($link,$sql1);
					$tot = 0;
					while($row = mysqli_fetch_array($result)){
						$tot += $row['Amount'];
					}
					$bal = $OrdersRows['TotalPrice'] - $tot;
					if($bal>0){
						$isPartial=true;
					} else {
						$isPartial=false;
					}
					$isPartial=false;
   
   
   ?>
   <td style="width:100px; text-align:center; color:#666666;" onmouseout='hidetooltip(this);' class="<?php echo $class; ?> <?php if($OrdersRows['Status'] == 'Paid' || $OrdersRows['Status'] == "Active" || $OrdersRows['Status'] == 1) { ?>paid<?php }elseif($OrdersRows['Status'] == 'Unpaid' || $OrdersRows['Status'] == 'ChargeBack'){ ?>unpaid<?php } elseif($OrdersRows['Status'] == 'Refunded' || $OrdersRows['Status'] == 'Cancelled'){ ?>refunded<?php }else{ ?>unpaid<?php } if($OrdersRows['Status'] == 'ChargeBack'){ echo " chargeback";} if($OrdersRows['Status'] == 'Closed'){ echo " closed";} ?>" >
   
   <span style="display: none;" class="sortingValue"><?php if($OrdersRows['Status'] == "Active" || $OrdersRows['Status'] == 1 || $OrdersRows['Status'] == "Paid") echo "Paid"; else echo $OrdersRows['Status']; ?><br/>
   <?php echo $OrdersRows['ID'] . "<hr />"; 
   //print_r($status); 
   ?>
   </span>
   <span style="display: none" class="sortingValue"><?php echo $class;?></span>
   
   <span style="display: none"><?php echo $bal; ?></span>
   <?php if($OrdersRows['Status'] == "Unpaid" || $isPartial==true){ ?>
   		<div onmouseout='hidetooltip(this);' onmouseover='showtooltip(this);' class="showonlyone">
            <div class="mynewclass" style="display: none;" >
                <div class="con_name"><?php echo $OrdersRows['CompanyName']; ?></div>
                <div class="con_details">
                    <?php echo $OrdersRows['FirstName']." ".$OrdersRows['Surname'];?>
                </div>
                <a href="#" class="openpopup" id="<?php echo $OrdersRows['ID']; ?>"><input type="button" value="Mark As Paid" class="showdetails"></a>
                <br /><br />
                <a href="#" class="markasclosed" id="close_<?php echo $OrdersRows['ID']; ?>"><span class="showdetails">Close the File</span></a>
                  <br /><br />
                <a href="#" class="markascancelled" id="cancel_<?php echo $OrdersRows['ID']; ?>"><span class="showdetails">Cancelled- Never Paid </span></a>
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
                <?php date_default_timezone_set(timezone_name_from_abbr("EST")); ?>
                   Client Name: <?php echo $OrdersRows['FirstName']." ".$OrdersRows['Surname']; ?><br/>
                   &nbsp;Date Ordered: <?php echo date("<b>M d</b>, Y", strtotime($OrdersRows['Created'])); ?><br/>
                   <?php if($OrdersRows['PaidThrough']=="Credit Card"){
					   ?>
                      	&nbsp;Date Paid   : <?php echo date("<b>M d</b>, Y", strtotime($OrdersRows['Created'])); ?><br/>
                         &nbsp;Payment Time: <?php echo date('g:i a', strtotime($OrdersRows['Created'])); ?> EST<br/>
                       <?php
					} else { ?>
                   		<?php if($OrdersRows['PaidThrough']=="Credit Card (Process Later)") {
							echo 'Date Paid : ' . date("<b>M d</b>, Y", strtotime($OrdersRows['Process_Later_Date']));
							echo "<br />";
						} else {?>
                   		&nbsp;Date Paid   : <?php 
						if($OrdersRows['ManualPaymentDate']!="0000-00-00"){
							echo date("<b>M d</b>, Y", strtotime($OrdersRows['ManualPaymentDate']));
						} ?><br/>
                         &nbsp;Payment Time: <?php 
						  if($OrdersRows['ManualPaymentDate']!="0000-00-00"){ echo date('g:i a', strtotime($OrdersRows['Created'])) . " EST";} ?><br/>
                   <?php }} ?>
                  

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
   if($OrdersRows['Status'] == "Active" || $OrdersRows['Status'] == 1 || $OrdersRows['Status'] == "Paid") {
		if($isPartial==true){
			echo "Partial";
		} else {
			echo "Paid";
		}
	} else { 
		echo $OrdersRows['Status'];
	}
	?>
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
   <td>
   
   
   <?php 
   if($OrdersRows['PaidThrough']=="Credit Card" || $OrdersRows['PaidThrough']=="Credit Card (Process Later)") {
	   $encrypt = $OrdersRows['CredetCardNumber'];
	   if($encrypt){
	   $unsecure_credit_card = $objclient->decrypt($encrypt);
		// echo $encrypt;
		$unsecure_credit_card = "-".substr($unsecure_credit_card, -4, 4);
		echo $unsecure_credit_card;
	   } else {
		   
		   echo "";
	   }
   } else if($OrdersRows['PaidThrough']=="echeck") {
	   echo "echeck";
   }
   ?>
   
   </td>
   
   <!--<td > <a id="orderdetail<?php echo $OrdersRows['ID'];?>" href="<?php echo SITE_ADDRESS."ecommerce/orders/EcClientsOrderDetail.php"?>?id=<?php echo $OrdersRows['MemberID'];?>&Task=configration" title="<?php echo $OrdersRows['CompanyName']." - ".$OrdersRows['FirstName']." ".$OrdersRows['Surname']." - ".$OrdersRows['Phone'];?>"> $<?php echo number_format($OrdersRows['TotalPrice'],2);?></a> </td>-->
   <td>$<?php echo number_format($OrdersRows['TotalPrice'],2);?></td>
   <td  align="center" style="width:130px;">
   
       <?php if($_SESSION['isAdmin']  == 1){
		   if($OrdersRows['PaidThrough']=="echeck" || $OrdersRows['PaidThrough']=="check"){
			   ?>
               <a href="<?php echo SITE_ADDRESS."ecommerce/orders/ViewEcheck.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>" class="ordercc" id="ordercc<?php echo $OrdersRows['ID'];?>" title="View Echeck for <?php echo $OrdersRows['ID'];?>"> <img src="../../images/eCheck.png" border="0" /></a>
               <?php
		   } else {
		   ?>
   <a href="<?php echo SITE_ADDRESS."ecommerce/orders/ViewCC.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>" class="ordercc" id="ordercc<?php echo $OrdersRows['ID'];?>" title="View CC for <?php echo $OrdersRows['ID'];?>"> <img src="../../images/icon_cc_view.png" border="0" /></a>
       <?php }}?>
       
   <!--
   <a href="<?php echo SITE_ADDRESS."ecommerce/orders/DownloadInvoice.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>" target="_blank" title=""> <img src="../../images/icon_find.png" border="0" /></a> 
   -->
    <?php $invoice_no = $AgentID."-".$OrdersRows['MemberID']."-".$OrdersRows['ID']; ?>
   <!--
   <a href="<?php echo SITE_ADDRESS."clients/attachments/" . $invoice_no . ".pdf"; ?>" target="_blank" title=""> <img src="../../images/icon_find.png" border="0" /></a> 
   -->
   
   <a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrderTest.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>&Task=configration&AgentID=<?php echo $AgentID; ?>" title="" target="_blank"> <img src="../../images/icon_find.png" border="0" /></a> 
  
    <?php if($_SESSION['isAdmin']  == 1){?>
   <a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrderListEdit.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>&Task=configration&MemberID=<?php echo $OrdersRows['MemberID'];?>" id="orderdetail1<?php echo $OrdersRows['ID'];?>" class="orderdetail1" title="<?php echo $OrdersRows['CompanyName']." - ".$OrdersRows['FirstName']." ".$OrdersRows['Surname']." - ".$OrdersRows['Phone'];?>"> <img src="../../images/icon_settings.png" border="0" /></a> 
    <?php }?>
   
    <?php if($_SESSION['isAdmin']  == 1){?>
   <a id="orderdetail<?php echo $OrdersRows['ID'];?>" class="orderdetail" href="<?php echo SITE_ADDRESS."ecommerce/orders/EcClientsOrderDetail.php"?>?id=<?php echo $OrdersRows['MemberID'];?>&Task=configration" title="<?php echo $OrdersRows['CompanyName']." - ".$OrdersRows['FirstName']." ".$OrdersRows['Surname']." - ".$OrdersRows['Phone'];?>"><img src="../../images/icon_page_edit.png" border="0" /></a>
   <?php } ?>
   
   <a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersInvoice.php"?>?OrderID=<?php echo $OrdersRows['ID'];?>&Task=configration&AgentID=<?php echo $AgentID; ?>" class="orderinvoice" id="orderinvoice<?php echo $OrdersRows['ID'];?>" title="<?php if($OrdersRows['Status'] == 'Paid'){echo 'Paid Invoice'; } else {echo "Open Invoice"; } ?>"><img src="../../images/icon_print.png" border="0" /></a>
   
  <?php if($_SESSION['isAdmin']  == 1){?>
 	<a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersAndSubscriptionList.php"?>?id=<?php echo $OrdersRows['ID']; ?>&Task=del" class="deleteorhide" id="<?php echo $OrdersRows['ID']; ?>"> <img title="Delete From Database" src="../../images/icon_delete.png" border="0"/></a>
   <?php } ?>
   </td>
       
  </tr>
  <?php } } ?>
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

<div class="bulkupdatepaydetails" style="display:none;">
	<table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
    	<tr>
        	<td>Paid Through:</td>
            <td>&nbsp;</td>
            <td>
            	<select class="product" name="bulkpaidthrough" id="bulkpaidthrough" style="width: 300px;">
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
            	<textarea id="bulknotesforpaidthrough" style="width: 290px; height: 65px;"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                
                <input type="button" value="Update Payment" class="showdetails" id="bulkupdatepaymenttopaid" style="padding:7px 15px;">
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
	"aaSorting": [<?php if($_SESSION['dateFilter']=="paid"){echo '[ 1, "desc" ]';}?>],
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
var toChange = [];
$(document).ready(function(){
	$("#message_success").fadeOut(3000);
	$("#message_error").fadeOut(3000);
	$(".message_success").fadeOut(3000);
	$(".message_error").fadeOut(3000);
	
	$("#resetBtn").on("click", function(){
		window.location.href="<?php echo SITE_ADDRESS;?>ecommerce/orders/OrdersAndSubscriptionList.php?reset=true";
	});
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

$(".markasclosed").click(function(e){
	e.preventDefault();
	var which = $(this).attr("id");	
	which = which.substring(which.indexOf("_")+1, which.length);
	//alert(which);
	var r = confirm("Are you sure?");
	if (r == true) {
		//x = "You pressed OK!";
		var url = "<?php echo SITE_ADDRESS;?>ecommerce/orders/markClosed.php?id="+which;
		$.ajax({
			url: url,
			success: function(html){
				console.log(html);
				window.location=window.location;
			},
			error: function(e){
				alert("error updating order status");	
			}
		});
	} else {
		//x = "You pressed Cancel!";
	}
});
$("#bulkinator").click(function(){
	
	$(".dataTable input.bulk").each(function(){
		if(this.checked){
		var id = $(this).attr("name");
		id = id.substring(id.indexOf("_")+1, id.length);
		//console.log(id);
		//toChange[] = id;
		toChange.push(id);
		}
	});	
	
	$("#paidthrough").val('');
	$("#notesforpaidthrough").val('');
	var OrderID = $(this).attr("id");
	var title = "Update payment status for bulk group";
	
	$('.bulkupdatepaydetails').dialog({
    	width: "565",
    	height: "235",
		title : title
	});
	
	console.log(toChange);
});

$(".markascancelled").click(function(e){
	e.preventDefault();
	var which = $(this).attr("id");	
	which = which.substring(which.indexOf("_")+1, which.length);
	//alert(which);
	var r = confirm("Are you sure?");
	if (r == true) {
		//x = "You pressed OK!";
		//alert(which);
		$.ajax({
			url     : '<?php echo SITE_ADDRESS;?>/ajax/AllAjaxCalls.php',
			data    : {'Task': 'MarkAsCanceledOrPaid', 'OrderID': which, 'Notes': 'Never Paid', 'MarkAs': '1'},
			success : function(data){
				if(data == "Success"){
					location.reload();
				}
			}
		});
	} else {
		//x = "You pressed Cancel!";
	}
});

$(".openpopup").click(function(e){
	
	e.preventDefault();
	var isBulk = <?php if(isset($_SESSION['bulkAction'])){ echo 'true';} else {echo 'false';}?>;
	$("#paidthrough").val('');
	$("#notesforpaidthrough").val('');
	var OrderID = $(this).attr("id");
	if(isBulk){
		var title = "Update payment status for bulk group";
	} else {
		var title = "Update payment status for Order ID "+OrderID;
	}
	$('.updatepaydetails').dialog({
    	width: "565",
    	height: "235",
		title : title
	});
	
	$("#keeporderidhere").val('');
	$("#keeporderidhere").val(OrderID);
});

$(".openpopup_unpaid").click(function(e){
	e.preventDefault();
	var isBulk = <?php if(isset($_SESSION['bulkAction'])){ echo 'true';} else {echo 'false';}?>;
	var OrderID = $(this).attr("id");
	if(isBulk){
		var title = "Update payment status for bulk group";
	} else {
		var title = "Update payment status for Order ID "+OrderID;
	}
	$('.update_as_unpaid').dialog({
    	width: "565",
    	height: "235",
		title : title
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
	var notes = encodeURIComponent($("#notesforpaidthrough").val());
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

$("#bulkupdatepaymenttopaid").click(function(){
	$('.wait_span').show();
	var paidthrough = $("#bulkpaidthrough").val();
	var notes = encodeURIComponent($("#bulknotesforpaidthrough").val());
	$.each(toChange, function( index, value ) {
 	 	var OrderID = value;
		console.log(value);
		if(OrderID != ""){
			$.ajax({
				url : 'OrdersAndSubscriptionList.php?Task=UpdatePayment&OrderID='+OrderID+'&Paidthrough='+paidthrough+'&Notes='+notes,
			}).done(function(data){
				
			});
		}
	});
	location.reload();
	//alert("done");

	
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
