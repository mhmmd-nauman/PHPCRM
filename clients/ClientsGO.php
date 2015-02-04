<?php 
include "../include/header.php";

$utilObj = new util();
$CatRecords = $utilObj->getMultipleRow('BusinessCategory',"1 ORDER BY CategoryName ASC");
$objClient = new Clients();
$objorder = new Orders();
$objtasks = new Tasks();
$ObjGroup = new Groups();
$objproducts = new Products();
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

	if(isset($_REQUEST['SearchEmailName'])){
		 $searchTextName = trim($_REQUEST['SearchEmailName']);
	}
	if(isset($_REQUEST['SearchColorsName1']) && trim($_REQUEST['SearchColorsName1'] != 0)){
		  echo  $SearchTextColors1 = trim($_REQUEST['SearchColorsName1']);
	}

	if(isset($_REQUEST['SearchTextclient'])){
		 $searchText = trim($_REQUEST['SearchTextclient']);
     }
	
	
	if(isset($_REQUEST['FromDate_Client'])){
		 $SearchTextFromDate = trim($_REQUEST['FromDate_Client']);
	}	
		
	if(isset($_REQUEST['ToDate_Client'])){
		 $SearchTextToDate = trim($_REQUEST['ToDate_Client']);
	}
		
	if(isset($_REQUEST['ToDate_Client']) && $_REQUEST['ToDate_Client'] != ''){
		if(!empty($member_search))
			$member_search .=" AND ";
		$member_search = " Date(Created) >= '".date("Y-m-d",strtotime($SearchTextFromDate))."' AND Date(Created) <= '".date("Y-m-d",strtotime($SearchTextToDate))."' ";
	}	
		
	if(isset($searchTextName) && $searchTextName != ''){
		if(!empty($member_search))
			$member_search .= " AND ";
		$member_search = " (".CLIENTS.".FirstName = '".$searchTextName."' OR Surname = '".$searchTextName."' OR Email = '".$searchTextName."')" ; 
	}	
	
	if(isset($searchText) && $searchText != ''){
		if(!empty($member_search))
			$member_search .=" AND ";
		$member_search .= " (".CLIENTS.".ID = '".$searchText."')" ;
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

if($is_sale_agent and !in_array(18, (array)$_SESSION['user_in_groups']) and !in_array(17, (array)$_SESSION['user_in_groups'])){
	if(empty($member_search)){
	 	$member_search = "  SubmitedBy = ".$_SESSION['Member']['ID'];
	}else{
		$member_search .= " AND SubmitedBy = ".$_SESSION['Member']['ID'];
	}
}


if(!in_array(2, (array)$_SESSION['user_in_groups']) and !in_array(17, (array)$_SESSION['user_in_groups']) and !in_array(18, (array)$_SESSION['user_in_groups'])){
	if(!empty($member_search)){
		$member_search .= " and Company.ID = '$AgentCompanyID' ";
	}else{
		$member_search .= " Company.ID = '$AgentCompanyID' ";
	}
}

if(in_array(18, (array)$_SESSION['user_in_groups']) and !in_array(2, (array)$_SESSION['user_in_groups']) and !in_array(3, (array)$_SESSION['user_in_groups'])){
	if(!empty($member_search)){
		$member_search .= " and ".CLIENTS.".TaskID = '1' ";
	}else{
		$member_search .= " ".CLIENTS.".TaskID = '1' ";
	}
}

if(empty($member_search)){
	$member_search .= " 1 ";
}
	

if(!empty($_REQUEST['SearchCategory']) || !empty($_REQUEST['brandingcolors'])){
	$ClientRows = $objClient->GetAllCategoryWithClients(" $member_search  $sortText ",array(CLIENTS.".*,EcTaskProductData.BrandingColors1,EcTaskProductData.BrandingColors2,EcTaskProductData.BrandingColors3"));
}
else{
	$ClientRows = $objClient->GetAllClientsForClientsPage(" $member_search ",array(CLIENTS.".*"));
}

$GetTaskWhere = "";

if(empty($GetTaskWhere)){
	$GetTaskWhere = " 1 ";
}

$tasks_array = $objtasks->GetAllTasks(" $GetTaskWhere ORDER BY SortID ASC ",array("*")); 

function createBrandingDrop($Selectname, $selectID, $ShowSelected){
	$select = $selectedVal = "";
	$ColorsArray = array("black","blue","blueaqua","Bluedark","Bluelight","Bluelight","Gold","GrayDark","GrayLight","Green","GreenArmy","GreenDark","GreenLight","GreenLime","Maroon","MaroonDark","MaroonLight","Orange","OrangeDark","OrangeLight","red","RedDark","Tan","TanDark","TanLight","white","Yellow","YellowDark","YellowLight");
	
	natcasesort($ColorsArray);
	
	$select .= "<select name='brandingcolors[]' id='$selectID' class='product' multiple='multiple'>";
	foreach((array)$ColorsArray as $Colors){
		if($ShowSelected == $Colors)
			$selectedVal = "selected";
		else
			$selectedVal = "";
			
		$Colors_Label = ucfirst($Colors);				
		$select .= "<option value='$Colors'>$Colors_Label</option>";
    }
	$select .= "</select>";
	return $select;
}

?>
<html>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
<script type='text/javascript' src="<?php echo SITE_ADDRESS;?>js/jquery-multiselect.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery.multiselect.css" />

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
		modalbox(this.href,this.title,"large");
	});
	
	$(".CollectPayment").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});
});

$(document).ready(function(){
	var oTable = $('#Client_List').dataTable({
		"iDisplayLength": 700,	
		"sDom": 'T<"clear">lfrtip',
		
					"oTableTools": {
						"sSwfPath": "https://datatables.net/release-datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
						"aButtons": [
							"copy",
							"csv",
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
	
	oTable.fnSort( [ [0,'desc']] );
	/*
	var oTableTools = new TableTools( oTable, {
        "buttons": [
            "copy",
            "csv",
            "xls",
            "pdf",
            { "type": "print", "buttonText": "Print me!" }
        ]
    } );
     
    $('#Client_List').before( oTableTools.dom.container );
	*/
	
	
	$(function(){
		$("#BrandingColors1, #category_assign").multiselect();
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

});
$(window).load(function(){
	$('#Client_List').show();
});
</script>
<script type="text/javascript">
$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	jQuery("#show_options").show();
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
</style>
<body>
<div id="headtitle"> Clients </div>
<div class="filtercontainer">
<table  width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td >
    <form name="HubUserSearchForm" id="HubUserSearchForm" action="?Task=SetFilter" method="post" enctype="multipart/form-data">

<div class="adv_search">
  <div class="adv_search_sub">
    <div class="input_box">
      <input name="SearchTextclient" id="mainsearch" class="input_box_2" type="text" value="<?php echo $searchText;?>" />
      <div id="show_options" class="aoo" tabindex="0" gh="sda" role="button" aria-label="Show search options" data-tooltip="Show search options" style="">
        <div class="aoq"></div>
      </div>
    </div>
    
    <div class="adv_btn">
      <input name="SearchTextClient" type="submit" class="adv_btn_2" value="" />
    </div>
    </div>
  <div class="cate_main" id="cate_main" style="display:none;position:absolute; z-index: 100000; top:167px;">
    <div id="search_close" tabindex="0" role="button" class="Zy"></div>
    <div class="search_row"><br />&nbsp;&nbsp;Name/Email:<br />
      <input name="SearchEmailName" type="text" class="in_put2" id="changename" value="<?php echo $searchTextName;?>" size="30" maxlength="30" style="width: 306px;">         
    </div>
	  <div class="search_row in_put2" style="width:307px;">
                
    <table cellpadding="0" cellspacing="0" width="100%" border="0" style="padding-left:8px;">
        <tr>
            <td width="50%">From:<br />
            	<input type="text" id="fromdatepicker" name="FromDate_Client" size="12" style="padding: 1px 4px; width: 150px;" value="<?php echo $SearchTextToDate;?>" />
            </td>
            <td valign="bottom" >To:<br />
            	<input type="text" id="todatepicker" name="ToDate_Client" size="12" style="padding: 1px 4px; width: 150px;" value="<?php echo $SearchTextToDate;?>"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;Colors:<br />
				<?php
					$show = $_REQUEST['BrandingColors1'];
					echo createBrandingDrop("BrandingColors1", "BrandingColors1", $show);
            	?>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Category:<br />
                <select name="SearchCategory[]"  id="category_assign" class="product"  multiple='multiple'>
                    <?php
                    foreach((array)$CatRecords as $CatRecord){ ?>
                        <option value="<?php echo $CatRecord['ID']; ?>"><?php echo $CatRecord['CategoryName'];?></option>
                    <?php } ?>
                </select>
            </td>
    	</tr>	  
    
    </table>
</div>
    <div style="margin-bottom:5px;">
    	<input type="submit" name="Submit" class="adv_btn_2" style="float:right; margin-right: 25px;" value="" align="absmiddle" border="0" />
    <div style="clear:both;"></div>
	</div>
</div>
</div>
<div style="clear:both;"></div>
  
  <script type="text/javascript">
$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	jQuery("#show_options").show();
	$('#changename').attr('name', ''); 
});
</script>
</form>
<td>
    <?php if($_SESSION['isAdmin']  == 1){?>
    
     <div style="padding-top:5px; float:right;">
     <a href="javascript:;">Export</a>
     
	<a href="<?php echo SITE_ADDRESS;?>revision/RevisionNotes.php?kid=6" id="AddRevNotes" title="Revision Notes" ><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"  value="Revision Notes" ></a>	 	
    
    <a href="<?php echo SITE_ADDRESS;?>clients/ClientsEdit.php?Task=add" id="ClientAdd" title="Add New Client"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"  value="Add New Client"  ></a>
    </div>
    <?php }?>	
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

<div class="subcontainer">

<div class="" style="margin:0px 0px 20px 0px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Client_List" style="display:none;">
    <thead>
        <tr id="headerbar" style=" height:46px;">
            <th >ID</th>
            <th style="width:10% !important;">Entered</th>
            <th style="width:15% !important;">Business </th>
            <th style="width:10% !important;">Contact </th>
            <th style="width:15% !important;">Email</th>
            <th style="width:10% !important;">Phone</th>
            <th style="width:10% !important;">Agent</th>
            <th style="width:10% !important;">Company</th>
            <th >Website</th>
            <th >Welcome</th>
            <?php
			
            if($tasks_array){
                foreach((array)$tasks_array as $task){ ?>
                    <th style="width:25px !important;" title="<?php echo $task['Title']; ?>" ><?php echo $task['HeaderLetter']; ?></th>
                <?php
                }
            }
            ?>
            <th align="center" style="width:70px;">Status</th>
            <th class="Action" align="left" style="width:50px;" >Actions</th>
        </tr>
    </thead>
   <tbody>    
  <?php 
  	 foreach((array)$ClientRows as $Client){
	 		# Set the welcome date to blank at first or else it will take the last welcome date value.
			$wellcome_date = "";
            $website_assigned_array = $objClient->GetAllClientsWithWebsites(WEBSITES.".MemberID = '".$Client['ID']."' AND ".WEBSITES.".HasDeleted = 0",array(WEBSITES.".*,".CLIENTS.".FirstName"));
            $revision_array =  $objClient->GetAllRevision(" MemberID = '".$Client['ID']."' AND Done = 0 AND HasDeleted = 0",array("count(*) as total"));
			$total_revision_array =  $objClient->GetAllRevision(" MemberID = '".$Client['ID']."' AND HasDeleted=0",array("count(*) as total"));
     		
            $undone_total = $revision_array[0]['total'];  
            if($undone_total > 0 ){
               $revision_array =  $objClient->GetAllRevision(" MemberID = '".$Client['ID']."'AND Done = 1",array("count(*) as total"));

               $_total = $revision_array[0]['total'];
			   
               if($_total > 0 && $_total == $total_revision_array[0]['total']){
                   $FlagRevision = "green";
               }else{
                   $FlagRevision = "red";
               }
             }else{
               $FlagRevision = "green";
            }
            if(isset($website_assigned_array)){
                $FlagSite = "green";
             }else{
                $FlagSite = "red";
             }
            if(date("Y",strtotime($Client['BestCallTime'])) == date("Y") && date("m",strtotime($Client['BestCallTime'])) == date("m") && date("d",strtotime($Client['BestCallTime'])) < date("d")){
                    $FlagBestCallTime = "green";
            }else{
                    $FlagBestCallTime = "red";
            }
            if((int)date("Y",strtotime($Client['TimeBilling'])) > 1970){
                $TimeBilling = "green";
            } else{
                $TimeBilling = "red";
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
            
       $checklist_uncompleted = false;
	   $checklist_completed = false;
	   if($tasks_array){
                $task_images = array();
                foreach((array)$tasks_array as $task){
                        if(in_array($task['ID'], $client_tasks_array)) {
                            $un_completed_task_checklist = array();
                            $completed_task_checklist = array();
                            $CheckList_date_array = $objtasks->GetTaskAllCheckListData("ClientID ='".$Client['ID']."'",array("*"));
                            foreach((array)$CheckList_date_array as $check_list_row ){
							
							if($check_list_row['CheckListName'] == 'Welcome Call')
							$wellcome_date = $check_list_row['SaveDate'];
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
                                $task_images[$task['ID']]['path'] = "X";
                            } else {
                                $task_images[$task['ID']]['path'] = "0";
								foreach((array)$client_OredrProductWithoutPackage_array as $product_row){ 
									$completed_task_checklist1 = array();	
			   						 $product_row_array = $objproducts->GetAllProduct("ID = '".$product_row['ProductID']."'", array("TaskAttached"));
									if($product_row_array[0]['TaskAttached'] > 0 ){
                						$completed_task_checklist1[$product_row['ProductID']] = $product_row['IsCancel'];
									if($product_row_array[0]['TaskAttached'] == $task['ID']&&in_array(1, $completed_task_checklist1)){
										$task_images[$task['ID']]['path'] = "-";
									}	
                  				}
							}			
								                              
                            }   
                         } else {
                              $task_images[$task['ID']]['path'] = "-";     
                             // gray
                         }
                }
            }
					
           if($checklist_uncompleted === true && $checklist_completed === true){
                $row_class = "row-yellow";
           } elseif($FlagRevision=="green" && $checklist_uncompleted === false && $checklist_completed === true){
                $row_class = "row-green";
	   }else{
	  	$row_class = "row-red";
	   }
	?>
    <tr id="<?php echo $row_class;?>" class="<?php echo $Client['ID']; ?>">
        <td> <?php echo $Client['ID'];?></td>
        <td style="width:75px;"><?php echo date("<b>M d</b>, Y",strtotime( $Client['Created'])); ?></td>
        <td style="width:150px;"><?php echo $Client['CompanyName'];?></td>
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
        
		<td><a href="http://<?php echo $website_assigned_array[0]['DomainName'];?>" target="_blank"><img src="../images/clients_domain.png" title="Go to home page" /></a></td>
        
		<td style="width:100px;"><?php  
		if($wellcome_date == '0000-00-00 00:00:00' || date("Y",strtotime($wellcome_date)) == "1970" || date("Y",strtotime($wellcome_date)) == "1969"){
			$wellcome_date = "";
		}else{
		echo date("<b>M d</b>, Y",strtotime($wellcome_date));}?></td>
		<?php
        if($task_images){
			foreach((array)$task_images as $task){ ?>
			<td >
				<?php echo $task['path'];?>
			</td>
			<?php
			}
        }
		
		# Get the Payment Status of the Client ro show up inside this column.
		$Orders_available = $objorder->GetAllOrderStatus("OrderItem.MemberID = '".$Client['ID']."'",array("OrderItem.ID, OrderItem.Status"));
        ?>	
        
        <td align="center"  style="width:70px;" class="<?php if($Orders_available[0]['Status'] == 'Paid' || $Orders_available[0]['Status'] == "Active" || $Orders_available[0]['Status'] == "ChargeBack" || $Orders_available[0]['Status'] == 1) { ?>paid<?php }elseif($Orders_available[0]['Status'] == 'Unpaid'){ ?>unpaid<?php } elseif($Orders_available[0]['Status'] == 'Refunded' || $OrdersRows['Status'] == 'Cancelled'){ ?>refunded<?php }else{ ?>unpaid<?php } ?>">
        <?php if($Orders_available[0]['Status'] == "Unpaid"){ ?>
        <?php if($_SESSION['isSaleGroup']  != 1){?>
        <a class="CollectPayment" title="Payment For Order ID - <?php echo $Orders_available[0]['ID']; ?>" href="CollectPayment.php?OrderID=<?php echo $Orders_available[0]['ID']; ?>">
         <?php
		}
		}
		
        if($Orders_available[0]['Status'] == "Active" || $Orders_available[0]['Status'] == 1 || $Orders_available[0]['Status'] == "Paid") echo "Paid"; elseif($Orders_available[0]['Status'] == "") echo "No Order"; else echo $Orders_available[0]['Status'];
			
		if($Orders_available[0]['Status'] == "Unpaid"){ ?>
        </a>
        <?php
        }
		
		?>
        </td>
        
        
        
        <td class="Action" align="left">
        
        <?php $ClientOrdersRows = $objorder->GetAllOrder(" MemberID = ".$Client['ID'],array("MemberID"));
       // if(empty($ClientOrdersRows[0]['MemberID'])){
        
        ?>
        <!--<a id="addclientorder<?php //echo $Client['ID'];?>" class="addclientorder" href="AddClientOrder.php?id=<?php //echo $Client['ID'];?>&Task=AddclientOrder" title=" Add Order <?php //echo $Client['CompanyName']." - ".$Client['FirstName']." ".$Client['Surname']." - ".$Client['Phone'];?>"> <img src="../images/icon_settings.png" border="0" title="Add Client Order"/> </a>
        <?php //} ?>-->
        
        <?php if($_SESSION['isSaleGroup']  != 1){?>
        <a id="ClientEdit<?php echo $Client['ID'];?>" class="ClientEdit" href="ClientsEdit.php?id=<?php echo $Client['ID'];?>" title="<?php echo $Client['CompanyName']." - ".$Client['FirstName']." ".$Client['Surname']." - ".$Client['Phone'];?>"> <img src="../images/icon_page_edit.png" border="0" title="Edit Details"/></a>
        <?php }?>
        
        <?php if($_SESSION['isAdmin']  == 1){?>
        <a href="?id=<?php echo $Client['ID'];?>&Task=del" onClick="return confirmation();"> <img title="Delete From Database" src="../images/icon_delete.png" border="0"/></a><?php //echo $FlagRevision;?>
        <?php }?>
        
        </td>
    </tr>
 <?php 
 $row_class = "";
 }
 ?>
 </tbody>
</table>
<br/>
</div>
</div>
<link rel="stylesheet" href="https://datatables.net/release-datatables/extensions/TableTools/css/dataTables.tableTools.css">
<script type="text/javascript" src="../js/dataTables/media/js/jquery.dataTables.js"></script>
<script src="https://datatables.net/release-datatables/extras/TableTools/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" src="https://datatables.net/release-datatables/extensions/TableTools/js/dataTables.tableTools.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />
<script type="text/javascript">
$(document).ready(function(){
	$(".message_error").fadeOut(5000);
	$(".message_success").fadeOut(5000);
});
</script>
</body>
</html>
