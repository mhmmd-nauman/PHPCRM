<?php 
//include "../../lib/include.php";
include "../../include/header.php";
$utilObj = new util();
//$CatRecords = $utilObj->getMultipleRow('BusinessCategory',"1 ORDER BY CategoryName ASC");
$objClient = new Clients();
$objorder = new Orders();
$objtasks = new Tasks();
$ObjGroup = new Groups();
$objproducts = new Products();
$objTags = new Tags();
$objUser = new Users();
$objCompany = new Company();
$is_sale_agent = true;
$Superadmin = "";
$AgentCompanyID = $_SESSION['Member']['CompanyID'];

if($_SESSION['isAdmin'] == 1){
    $is_sale_agent = false;	
}


$Group_Array = $ObjGroup->GetMemberGroups("UserID=".$_SESSION['Member']['ID']." ",array('GroupID'));
foreach((array)$Group_Array as $group_row){
	$groups[] = $group_row['GroupID'];
}


if($_REQUEST['Task'] == 'SetFilter'){
} else {

		
		 $SearchTextFromDate = trim(date("Y-m-d",strtotime("-6 Days")));
		 $_SESSION['FromDate_Client'] = $SearchTextFromDate;
	
		$SearchTextToDate = trim(date("Y-m-d"));
		$_SESSION['ToDate_Client'] = $SearchTextToDate;

		if(!empty($member_search)) {
			$member_search .=" AND ";
		}
		$member_search = CLIENTS . ".Created >= '".date("Y-m-d",strtotime($SearchTextFromDate))."' AND ". CLIENTS . ".Created <= '".date("Y-m-d",strtotime($SearchTextToDate . "+1 day"))."' ";
}



if(isset($_REQUEST['BusinessType'])){
	
	if(!empty($member_search)) {
			$member_search .=" AND BusinessType='" . $_REQUEST['BusinessType'] . "'";
	} else {
		$member_search = " BusinessType='" . $_REQUEST['BusinessType'] . "'";
	}
}


if(isset($_REQUEST['taskFilter'])){
	$_SESSION['taskFilter'] = $_REQUEST['taskFilter'];	
}

if($_REQUEST['Task'] == 'SetFilter'){

	if(isset($_REQUEST['SearchEmailName'])){
		 $searchTextName = trim($_REQUEST['SearchEmailName']);
	}
	if(isset($_REQUEST['SearchColorsName1']) && trim($_REQUEST['SearchColorsName1'] != 0)){
		  echo  $SearchTextColors1 = trim($_REQUEST['SearchColorsName1']);
	}

	if(isset($_REQUEST['MemSearchText'])){
		 $MemSearchText = trim($_REQUEST['MemSearchText']);
     }
	
	
	if(!empty($_SESSION['FromDate_Client'])){
		$SearchTextFromDate = $_SESSION['FromDate_Client'];
	} else {
		if(isset($_REQUEST['FromDate_Client'])){
			 $SearchTextFromDate = trim($_REQUEST['FromDate_Client']);
		} else {
			 $SearchTextFromDate = trim(date("Y-m-d",strtotime("-45 Days")));
		}
	}
	
	if(!empty($_SESSION['ToDate_Client'])){
		$SearchTextToDate = $_SESSION['ToDate_Client'];
	} else {
		if(isset($_REQUEST['ToDate_Client'])){
			 $SearchTextToDate = trim($_REQUEST['ToDate_Client']);
		} else {
			$SearchTextToDate = trim(date("Y-m-d"));
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
	
	
	
	
	
		if(!empty($member_search)) {
			$member_search .=" AND ";
		}
		$member_search = CLIENTS . ".Created >= '".date("Y-m-d",strtotime($SearchTextFromDate))."' AND ".CLIENTS . ".Created <= '".date("Y-m-d",strtotime($SearchTextToDate . "+1 day"))."' ";
		
		//MemSearchText
	if(isset($searchTextName) && $searchTextName != ''){
		if(!empty($member_search))
			$member_search .= " AND ";
		$member_search .= " (".CLIENTS.".FirstName = '".$searchTextName."' OR Surname = '".$searchTextName."' OR Email = '".$searchTextName."')" ; 
	}	
	
	if(isset($MemSearchText) && $MemSearchText!=""){
		if(!empty($member_search)) {
			$member_search .= " AND ";
		}
			$parts = explode(" ",$MemSearchText);
			if(count($parts)>1){
				$member_search .= CLIENTS.".SurName LIKE '%".$parts[1]."%' ";
				$member_search .= "OR ".CLIENTS.".FirstName LIKE '%".$parts[0]."%' ";
			} else {
				$member_search .= CLIENTS.".FirstName LIKE '%" . trim($MemSearchText) . "%' ";
				$member_search .= "OR ".CLIENTS.".SurName LIKE '%".trim($MemSearchText)."%'";
			}
			$member_search .= "
			OR ".CLIENTS.".Phone LIKE '%".trim($MemSearchText)."%' 
			OR ".CLIENTS.".Email LIKE '%".trim($MemSearchText)."%' ";	
	}
	
	if(isset($searchText) && $searchText != ''){
		if(!empty($member_search))
			$member_search .=" AND ";
		$member_search .= " (".CLIENTS.".ID = '".$searchText."')" ;
	}
	
	$taskArr = array();
	if(!empty($_SESSION['taskFilter'])){
	$all_tasks = $for_dropdown_preselected_tasks = "";
		$len = count($_POST['select_company']);
		$i = 0;
		
		foreach((array)$_SESSION['taskFilter'] as $tasks){
			if($i == $len - 1){
				
				$all_tasks .= "'".$tasks."'";
				$for_dropdown_preselected_tasks .= $tasks;
			}else{
				$all_tasks .= "'".$tasks."',";
				$for_dropdown_preselected_tasks .= $tasks.",";
			}
			$taskArr[] = $tasks;
			$i++;
		}
	}
	
	
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

		if(!empty($member_search)){
			$member_search .= " AND SubmitedBy IN ($all_agents)";
			
		}else{
			$member_search = " SubmitedBy IN ($all_agents)";
		}
	   
	} else {
		//print_r($_SESSION);
		
		$id = $_SESSION['Member']['ID'];
		//echo $id;
		if(in_array(16, (array)$_SESSION['user_in_groups'])){
			
			
			if(!empty($search)){
				$Search .= " AND SubmitedBy =" .$id;
				
			}else{
				$Search = " SubmitedBy =" .$id;
			}	
		}
		
		
	}
	
	
	/* Let me start from here */
	
	if(isset($_REQUEST['brandingcolors']) && !empty($_REQUEST['brandingcolors'])){
		$cat = $formultiselect = "";
		if(!empty($member_search))
			$member_search .= " AND ";
		
		$len = count($_POST['brandingcolors']);
		$formultiselect = "";
		$i = 0;
		foreach((array)$_POST['brandingcolors'] as $SingleColor){
			$formultiselect .= $SingleColor.",";
			if($i == $len - 1){
				$member_search .= " ( ".ECTASKPRODUCTDATA.".BrandingColors1 = '$SingleColor' or ".ECTASKPRODUCTDATA.".BrandingColors2 = '$SingleColor' or ".ECTASKPRODUCTDATA.".BrandingColors3 = '$SingleColor' ) ";
			}else{
				$member_search .= " ( ".ECTASKPRODUCTDATA.".BrandingColors1 = '$SingleColor' or ".ECTASKPRODUCTDATA.".BrandingColors2 = '$SingleColor' or ".ECTASKPRODUCTDATA.".BrandingColors3 = '$SingleColor' ) and ";	
			}			
		$i++;
		}
	}
	
	if(isset($_REQUEST['SearchCategory']) && !empty($_REQUEST['SearchCategory'])){
		if(!empty($member_search))
			$member_search .= " AND ";
		
		$cat = $formultiselect_cat = "";
		foreach((array)$_POST['SearchCategory'] as $SingleColor){
			$cat .= "'".$SingleColor."',";
			$formultiselect_cat .= $SingleColor.",";
		}
		$inquery_cat = trim($cat,",");

		$member_search .= " ( ".ECTASKPRODUCTDATA.".CategoryID IN ($inquery_cat) or ".ECTASKPRODUCTDATA.".CategoryIDB IN ($inquery_cat) or ".ECTASKPRODUCTDATA.".CategoryIDB IN ($inquery_cat) ) " ;	
	}
	/* Up to here */	
}

if(empty($member_search)){
	 $member_search .= " ".CLIENTS.".HasDeleted = 0 ";
}else{
	$member_search .= " and ".CLIENTS.".HasDeleted = 0 ";
}


if(empty($member_search)){
	$member_search .= " 1 ";
}
//$member_search = " HasDeleted = 0 ORDER BY ID DESC ";	


$ClientRows = $objClient->GetAllClients($member_search,array(CLIENTS.".*"));
//echo "come here";
//print_r($ClientRows);
$GetTaskWhere = "";

if(empty($GetTaskWhere)){
	$GetTaskWhere = " 1 ";
}

$tasks_array = $objtasks->GetAllTasks(" HeaderLetter = 'W' ORDER BY SortID ASC ",array("*")); 



$SearchTextToDate = date("Y-m-d", strtotime($SearchTextToDate . "+1 day"));


if(empty($_SESSION['FromDate_Client']))
	$_SESSION['FromDate_Client'] = date("m/d/Y",strtotime("-45 days"));
if(empty($_SESSION['ToDate_Client']))
	$_SESSION['ToDate_Client'] = date("m/d/Y");
?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>

<script type='text/javascript' src="<?php echo SITE_ADDRESS;?>js/jquery-multiselect.js"></script>


<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery.multiselect.css" />
<style>
span.nowrap {white-space:nowrap}
.sortingVal {display:none}
</style>
<script type="text/javascript">
	function confirmation() {
		var answer = confirm("Do you want to delete this record?");
		if(answer){
			return true;
		}else{
			return false;
		}
	}

	$(function() {
		$("#fromdatepicker").datepicker();
	});
	$(function() {
		$("#todatepicker").datepicker();
	});
	

$(function() {
	$("#AddRevNotes").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
  
	$("#ClientAdd").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
	
	$(".addclientorder").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});

	$(".ClientEdit").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"xlarge");
	});
	
	$(".CollectPayment").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
});

$(document).ready(function(){
	var oTable = $('#Client_List').dataTable({
		"aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
	"iDisplayLength": 10,
	"oLanguage": {
      "sSearch": "Quick Search:"
    },	
        "stateSave": true
	});
	oTable.fnSort( [ [0,'desc']] );
	
	$("#toggleDrop").click(function(){
		$("#dropdownArea").toggle();
	});
	
	$(function(){
		$("#BrandingColors1, #category_assign, #productFilter").multiselect();

		$(".ui-helper-reset li").first().remove();
	});
	
	<?php
	if(!empty($formultiselect)){
	?>
		var colors = '<?php echo trim($formultiselect,","); ?>';
		$.each(colors.split(","), function(i,e){
			$("#BrandingColors1 option[value='"+e+"']").prop("selected", true);
		});
	<?php
	}
	if(!empty($formultiselect_cat)){
	?>
		var category = '<?php echo trim($formultiselect_cat,","); ?>';
		$.each(category.split(","), function(i,e){
			$("#category_assign option[value='"+e+"']").prop("selected", true);
		});
	<?php
	}
	?>
	
	
	<?php if(!empty($for_dropdown_preselected_tasks)){
		?>
		var task_selected = '<?php echo trim($for_dropdown_preselected_tasks,","); ?>';
		$.each(task_selected.split(","), function(i,e){
			$("#taskFilter option[value='"+e+"']").prop("selected", true);
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
	

});
$(window).load(function(){
	$('#Client_List').show();
});
</script>
<script type="text/javascript">
$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	//jQuery("#show_options").show();
	$('#changename').attr('name', '');
});
</script>
<style type="text/css">
.ui-multiselect{
	height:30px !important;
	width:320px !important;
}
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
#Client_List a{
	color:#fff !important;
}
.showtime{
	display:none;
	background: none repeat scroll 0 0 lightyellow;
	border:1px solid #C9C95C;
    font-size: 15px;
    left: 115px;
    padding: 7px;
    position: absolute;
}
</style>

<div style="display: none" id="searchQuery"><?php echo $member_search;
// print_r($_REQUEST['productFilter']); 
// echo "<hr />"; 
// echo $sql6; 
 ?></div>
<?php //print_r($_REQUEST);?>

<div class="filtercontainer  panel">
<header class="panel-heading clearfix"> 
	<h2 class="pull-left">Web Orders</h2>
	<div class="pull-right">
       	
    </div>
</header>
<div class="row">

    <form name="HubUserSearchForm" id="HubUserSearchForm" action="?Task=SetFilter<?php if(isset($_REQUEST['BusinessType'])){echo"&BusinessType=".$_REQUEST['BusinessType'];}?>" method="post" enctype="multipart/form-data">


<div class="col-lg-6 col-sm-6">
   <div class="input-group">
          <input name="MemSearchText" id="mainsearch" class="input_box_2 form-control" type="text" style="height: 34px" value="<?php echo $MemSearchText;?>" />
          <div class="input-group-btn">
          	<input name="SearchTextClient" id="searchBtn" type="submit" class="adv_btn_2 btn" value="" style="display:none" />
            <button type="button" class="btn btn-default" onclick='$("#searchBtn").click();' tabindex="-1"><i class="fa fa-search"></i> Search </button>
            <button type="button" class="btn btn-default dropdown-toggle" id="toggleDrop" aria-expanded="false">
              <span class="caret"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            
            <div class="dropdown-menu dropdown-menu-right" id="dropdownArea" role="menu">
             <div class="search_row">
                
                <table cellpadding="0" cellspacing="0" width="100%" border="0" style="padding-left:8px;">
                    <tr>
                        <td width="50%">From:<br />
                            <input type="text" id="fromdatepicker" name="FromDate_Client" size="12" style="padding: 1px 4px; width: 150px;" value="<?php echo $SearchTextFromDate;?>" />
                        </td>
                        <td valign="bottom" >To:<br />
                            <input type="text" id="todatepicker" name="ToDate_Client" size="12" style="padding: 1px 4px; width: 150px;" value="<?php echo date("Y-m-d",strtotime($SearchTextToDate . "-1 day"));?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    
                    <tr>
                    <td colspan="2">
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
                    </div>
                    
                    
                    </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                   
                    
                    <tr>
                    <td colspan="2">
                        
                        <label>Product</label><br />
                    <select name="productFilter[]" id="productFilter" multiple="multiple"  class="product" style="width:319px;">
                        <option value="0">Select</option>
                        <?php
                        $allProds = $objproducts->GetAllProduct("1", array("*"));
                        foreach($allProds as $product){
                            echo "<option value='" . $product['ID'] . "'>". $product['ProductName'] . "</option>";
                        }
                        ?>
                    
                    </select>
                    
                    </td>
                    </tr>	  
                     <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                     <tr>
                    <td colspan="2">
                        
                        <label>Tasks</label><br />
                    <select name="taskFilter[]" id="taskFilter" multiple="multiple"  class="product" style="width:319px;">
                        <option value="0">Select</option>
                        <?php
                        $count = 0;
                         foreach((array)$tasks_array as $task){
                        
                            echo "<option value='" . $task['ID']. "'>". $task['Title'] . "</option>";
                            $count++;
                        }
                        ?>
                    
                    </select>
                    
                    </td>
                    </tr>	  
                
                </table>
                <input type="submit" name="Submit" class="adv_btn_2 btn  pull-right" value="" align="absmiddle" border="0" />
            </div>
            
          </div>
        </div>
  </div><!-- /.col-lg-6 -->
  </div>
  <div class="col-lg-6 col-sm-6">
  	<h4 class="pull-right"><?php echo date("M d",strtotime($SearchTextFromDate))." to ".date("M d",strtotime($SearchTextToDate . "-1 day")); ?></h4>
  
  </div>
  </div>


    </div>
     
  
 
   </div>
   
  
</div>
</div>
</div>
<div style="clear:both;"></div>
  
  <script type="text/javascript">
$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	//jQuery("#show_options").show();
	$('#changename').attr('name', ''); 
});
</script>
</form>

 
</td>
</table>
</div>
	<?php
    if($_REQUEST['flag'] == 'add'){ ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="message_success">Client Added Successfully!</td>
            </tr>
        </table>
	<?php }
    if($_REQUEST['Task'] == 'del'){ ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="message_success">Record Deleted Successfully!</td>
            </tr>
        </table>
	<?php }
    if($_REQUEST['flag'] == 'update'){ ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="message_success">Client Updated Successfully!</td>
            </tr>			
        </table>
	<?php } 
	if($_REQUEST['flag'] == 'error'){ ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td class="message_error">Email Already Exists In Database!</td>
            </tr>
        </table>
    <?php
    }
	?>
</div>

<div class="subcontainer panel">

<div class="bigTable" style="margin:0px 0px 20px 0px;" >
<div class="scroller">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Client_List" style="display:none;"  class="bigTable">
    <thead>
        <tr id="headerbar" style=" height:46px;">
            <th >ID</th>
            <th style="width:10% !important;">Entered</th>
            <th style="width:15% !important;">Business </th>
            <th style="width:10% !important;">Contact </th>
            <th style="width:15% !important;">Email</th>
            <th style="width:10% !important;">Phone</th>
            <th style="width:10% !important;">Agent</th>
            <th >Company</th>
            
            <?php
            if(!empty($_REQUEST['productFilter'])){
				?>
                <th>Prod</th>
            <? } ?>
            <th >Website/Subdomain</th>
            
            <?php
			
            if($tasks_array){
                foreach((array)$tasks_array as $task){ ?>
                    <th style="width:25px !important;" title="<?php echo $task['Title']; ?>" ><?php echo $task['HeaderLetter']; ?></th>
                <?php
                }
            }
            ?>
            <th></th>
           
            <th class="Action" align="left" style="width:50px;" >Actions</th>
        </tr>
    </thead>
   <tbody>    
  <?php 
  	 foreach((array)$ClientRows as $Client){
            # Set the welcome date to blank at first or else it will take the last welcome date value.
            $wellcome_date = "";
            $website_assigned_array = $objClient->GetAllClientsWithWebsites(WEBSITES.".MemberID = '".$Client['ID']."' AND ".WEBSITES.".HasDeleted = 0",array(WEBSITES.".*,".CLIENTS.".FirstName"));
            
            
            if(isset($website_assigned_array)){
                $FlagSite = "green";
             }else{
                $FlagSite = "red";
             }
            
            
		
            $client_tasks_array = array();
            $Order_Array = $objorder->GetAllOrder("MemberID = '".$Client['ID']."'",array("*"));
            $orderid = $Order_Array[0]['ID'];
            $client_OredrPackage_array = $objorder->GetAllClientOrderdedPackages("MemberID = '".$Client['ID']."'");
            
			$client_OredrProductWithoutPackage_array = $objorder->GetAllClientOrderdedProducts("MemberID = '".$Client['ID']."' AND PackagesID = 0");          

			foreach((array)$client_OredrPackage_array as $package_row){
				$package_products = $objorder->GetAllOrderDetail("PackagesID = '".$package_row['ID']."' AND OrderID = $orderid",array("*")); 
				foreach((array)$package_products as $product_row){
					$product_row_array = $objproducts->GetAllProduct("ID = '".$product_row['ProductID']."'", array("TaskAttached"));
					if($product_row_array[0]['TaskAttached'] > 0 ){
						$client_tasks_array[$product_row['ProductID']] = $product_row_array[0]['TaskAttached'];
					}
				}
			}
			
			foreach((array)$client_OredrProductWithoutPackage_array as $product_row){ 
				$completed_task_checklist1 = array();	
				$product_row_array=$objproducts->GetAllProduct("ID = '".$product_row['ProductID']."'", array("TaskAttached"));
				if($product_row_array[0]['TaskAttached'] > 0 ){
					$client_tasks_array[$product_row['ProductID']] = $product_row_array[0]['TaskAttached'];
				}
			}
            if($Client['TaskID'] > 0 && !in_array($Client['TaskID'], $client_tasks_array)){
                $client_tasks_array[] = $Client['TaskID'];
            }
      //print_r($tasks_array);
	 // echo "<hr />";
        $checklist_uncompleted = false;
        $checklist_completed = false;
	$showRow = false;
	  // print_r($for_dropdown_preselected_tasks);
	   if($tasks_array){
                $task_images = array();
                foreach((array)$tasks_array as $task){
					$title  = $task['TasksTitle'];
					
					    if(in_array($task['ID'], $client_tasks_array)) {
							$showRow = true;
							
                            $un_completed_task_checklist = array();
                            $completed_task_checklist = array();
                            $CheckList_date_array = $objtasks->GetTaskAllCheckListData("ClientID ='".$Client['ID']."'",array("*"));
                            foreach((array)$CheckList_date_array as $check_list_row ){
							//print_r($check_list_row);
							//echo "<hr />";
							//echo $check_list_row['CheckListName'] . "--" .$check_list_row['SaveDate'] . " <br /> ";
							if($check_list_row['CheckListName'] == 'Welcome Call') {
							$wellcome_date = $check_list_row['SaveDate'];
							}
							if($check_list_row['CheckListName'] == 'Welcome Packet Sent') {
								
							}
							
                                if($check_list_row['SaveDate'] == '0000-00-00 00:00:00' || date("Y",strtotime($check_list_row['SaveDate'])) == "1970" || date("Y",strtotime($check_list_row['SaveDate'])) == "1969"){
                                    $un_completed_task_checklist[$check_list_row['TaskID']] = $check_list_row['TaskID'];
                                    $checklist_uncompleted = true;
                                }else{
                                	$completed_task_checklist[$check_list_row['TaskID']] = $check_list_row['TaskID'];
                                    $checklist_completed = true;
                                }
							
                            } 
                            foreach($client_tasks_array as $ass_task){
                                if(!in_array($ass_task, $completed_task_checklist)){
                                    $CheckList_array = $objtasks->GetAllTaskToChecklist("TaskID = $ass_task",array("*"));
                                    if(count($CheckList_array) > 0){
                                         $un_completed_task_checklist[$ass_task] = $ass_task;
                                    }
                                }
                            }
							
                            if(in_array($task['ID'], $un_completed_task_checklist)){
                                $task_images[$task['ID']]['path'] = "stop.png";
                            } else {
                                $task_images[$task['ID']]['path'] = "start.png";
								foreach((array)$client_OredrProductWithoutPackage_array as $product_row){ 
									$completed_task_checklist1 = array();	
			   						 $product_row_array = $objproducts->GetAllProduct("ID = '".$product_row['ProductID']."'", array("TaskAttached"));
									if($product_row_array[0]['TaskAttached'] > 0 ){
                						$completed_task_checklist1[$product_row['ProductID']] = $product_row['IsCancel'];
									if($product_row_array[0]['TaskAttached'] == $task['ID']&&in_array(1, $completed_task_checklist1)){
										$task_images[$task['ID']]['path'] = "No.png";
									}	
                  				}
							}			
								                              
                            }   
                         } 
                }
            }
			
			//echo $checklist_uncompleted . " and ". $checklist_completed . "<br />";		
           if($checklist_uncompleted === true  && $checklist_completed === true
		   ){
			   if($wellcome_date == '0000-00-00 00:00:00' || date("Y",strtotime($wellcome_date)) == "1970" || date("Y",strtotime($wellcome_date)) == "1969"){
				   $row_class = "row-red";
			   } else {
				   $row_class = "row-yellow";
			   }
                //$row_class = "row-yellow";
           } elseif($FlagRevision=="green" && $checklist_uncompleted === false && $checklist_completed === true){
                $row_class = "row-green";
	   }else{
	  	$row_class = "row-red";
	   }
	   
	   if($showRow===true) {
	?>
    <tr id="<?php echo $row_class;?>" class="<?php echo $Client['ID']; ?>">
        <td> <?php echo $Client['ID'];?></td>
        <td  onMouseOver="showtime(this);" onMouseOut="hidetime(this);">
        <span class="sortingValue" style="display: none"><?php echo date("<b>M d</b>, Y",strtotime($Client['Created'])); ?></span>
        <span class="showtime nowrap"><?php echo date('H:i A', strtotime($Client['Created'])); ?></span><?php echo date("<b>M d</b>, Y",strtotime($Client['Created'])); ?></td>
        <td ><?php echo $Client['CompanyName'];?></td>
        <td style="width:100px;"><?php echo $Client['FirstName']." ".$Client['Surname'];?></td>   
        <td><?php echo $Client['Email'];?></td>
        <td><?php echo $Client['Phone'];?></td>
		<!-- Submitted By -> The name of teh agent under whom he order was made -->
        <?php
        $AgentDetails = $objClient->FetchAgentName($Client['SubmitedBy']);
		$AgentCompanyName = $objClient->FetchAgentCompanyName($Client['SubmitedBy']);
		?>
        <td style="width:75px;"><?php echo $AgentDetails; ?></td>
        <td style="width:75px;"><?php echo $AgentCompanyName; ?></td>
        
        <?php if(!empty($_REQUEST['productFilter'])){ ?>
        <td><?php echo $Client['ProductName'];?></td>
        <? } ?>
		<td>
                    <?php if($website_assigned_array[0]['DomainName']!=""){?><span class="sortingVal">B</span><a href="http://<?php echo $website_assigned_array[0]['DomainName'];?>" target="_blank"><img src="../../images/clients_domain.png" title="Go to home page" /></a><?php } else {?><span class="sortingVal">A</span><?php } ?></td>
        
		
		<?php
		$i = 0;
        if($task_images){
			foreach((array)$task_images as $task){  ?>

			<td >
				<?php /*
				<a href='<?php echo SITE_ADDRESS; ?>clients/updateTask.php?clientID=<?php echo $Client['ID']; ?>&order=<?php echo $i;?>' title="Task" class="taskEdit">*/ ?>
                <span class="sortingVal"><?php echo $task['path'];?></span>
                <img src="../../images/<?php echo $task['path'];?>" /> 
               
			</td>
			<?php
			$i++;
			}
        }
		
		
		?>	
        <td>
            &nbsp;         
        </td>
        
        
        <td class="Action" align="left">
            <a href="SubDomains.php?id=<?php echo $Website['SubDomain'];?>&DbPassword=<?php echo $Website['DbPassword'];?>&Task=CreateCpanel" target="_blank" onclick="return create_confirmation();"> <img  src="../../images/cpanel.png" border="0" title="Create Temp SubDomain"/> </a>
        </td>
    </tr>
 <?php 
 $row_class = "";
 }
	 }
 ?>
 </tbody>
</table>
</div>
<br/>
</div>
</div>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />
<script type="text/javascript"> 


$(document).ready(function(){
	$(".message_error").fadeOut(5000);
	$(".message_success").fadeOut(5000);
	
	$(".orderModal").on("click", function(e){
		e.preventDefault();
		modalbox(this.href, this.title, "large");
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
	
	$("#select_agent").multiselect();
	$("#select_company").multiselect();
	$("#taskFilter").multiselect();
	//$(".ui-helper-reset li").first().remove();
	
	$(".taskEdit").on("click",  function(e){
		e.preventDefault();
		$("td").removeClass("check");
		$(this).parent().addClass("check");
		var href=$(this).attr("href");
		$(this).parent().parent().find("td").each(function(index, elem){
			if($(this).hasClass("check")) {
				var child = index+1;
				var value = $("#Client_List thead tr th:nth-child(" + child + ")").html();	
				//console.log(value);
				href += "&name="+value;
			}
		});
		var img = $(this).find("img").attr("src");
		if(img.indexOf("product")>-1){
			
		} else {
			modalbox(href, $(this).attr("title"), "medium");
		}
		
	});
	
	$(".addToCal").bind("click", function(e){
		e.preventDefault();	
		var href= $(this).attr("href");
		href += "&calID=<?php echo $_SESSION['Member']['Email']; ?>&order=";
		modalbox(href, $(this).attr("title"), "medium");
	});
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
</body>
</html>
