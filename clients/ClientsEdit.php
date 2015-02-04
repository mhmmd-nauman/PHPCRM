<?php
include "../lib/include.php";
$objClient = new Clients();
$objtasks = new Tasks();
$objorder = new Orders();
$objproducts = new Products();

# Due to the previous developers I had to do this inorder to make the ajax call update
# working even when the text fields values are entered for the first time. The script
# is written such that until and unless we hit the save button for the first time a record
# is not created in the database to save the different values in the database on change of the
# values inside the text boxes. The previous developers have done it very badly due to which
# at this moment of time this is the best possible thing which I did as I did not wanted the
# others functionality to get affected due to some major changes in the code and script.
# Basically I am creating a blank record in the database when there is no records so that 
# the data could be saved on ajax calls as the agents wants them to be saved as easily as possible
# so that they can do their work as fast as they can.
$ClientID = $_REQUEST['id'];
$GYB_Data = $objClient->GetAllClientGYBData("MemberID ='".$_REQUEST['id']."'",array("ID"));
if(count($GYB_Data) <= 0){
	$objClient->InsertClientGYBData(array(
		"MemberID"	=> $ClientID
	));
}

$Question_Tab_data = $objClient->GetAllClientTaskData("MemberID = '".$_REQUEST['id']."' ",array("ID"));
if(count($Question_Tab_data) <= 0){
	$objClient->InsertClientQuestionTaskData(array(
		"MemberID"	=> $ClientID
	));
}
# Upto here

$TaskID = $_REQUEST['TaskID'];
$Members_array = $objClient->GetAllClients(CLIENTS.".ID = '".$_REQUEST['id']."'",array("*"));

if(isset($_REQUEST['id'])){
    $Task = "Update";
    $time_array = $Members_array[0]['BestCallTime'];
    $time = explode(" ", $time_array);
    $besttime = $time[1];
    $calltime = explode(":", $besttime);
    $hours = $calltime[0];
    $am_pm = "AM";
    if($hours >= 12){
		$am_pm = "PM";
		$hours = $hours - 12;
    }
}else{
    $Task="add";
    $Members_array[0]['Created'] = date("Y-m-d");
    $Members_array[0]['BestCallTime'] = date("Y-m-d");
    $Members_array[0]['TimeBilling'] = date("Y-m-d");
}

$Order_Array = $objorder->GetAllOrder("MemberID = '".$_REQUEST['id']."'",array("*"));
$orderid = $Order_Array[0]['ID'];
$client_OredrPackage_array = $objorder->GetAllClientOrderdedPackages("MemberID = '".$_REQUEST['id']."'");
$client_OredrProductWithoutPackage_array = $objorder->GetAllClientOrderdedProducts("MemberID = '".$_REQUEST['id']."' AND PackagesID = 0");
foreach((array)$client_OredrPackage_array as $package_row){
	$package_products = $objorder->GetAllOrderDetail("PackagesID = '".$package_row['ID']."' AND OrderID = $orderid",array("ProductID"));
		foreach((array)$package_products as $product_row){
		$product_row_array = $objproducts->GetAllProduct("ID = '".$product_row['ProductID']."'", array("TaskAttached"));
		if($product_row_array[0]['TaskAttached'] > 0 ){
			$client_tasks_array[$product_row['ProductID']] = $product_row_array[0]['TaskAttached'];
		}
	}
}
foreach((array)$client_OredrProductWithoutPackage_array as $product_row){
    $product_row_array = $objproducts->GetAllProduct("ID = '".$product_row['ProductID']."'", array("TaskAttached"));
    if($product_row_array[0]['TaskAttached'] > 0 ){
        $client_tasks_array[$product_row['ProductID']] = $product_row_array[0]['TaskAttached'];
    }
}
/* code  For icon check list  */
$un_completed_task_checklist = array();
$completed_task_checklist = array();
$CheckList_date_array = $objtasks->GetTaskAllCheckListData("ClientID ='".$_REQUEST['id']."'",array("*"));

foreach((array)$CheckList_date_array as $check_list_row ){
    if($check_list_row['SaveDate'] == '0000-00-00 00:00:00' || date("Y",strtotime($check_list_row['SaveDate']))=="1970" || date("Y",strtotime($check_list_row['SaveDate']))=="1969"){
        $un_completed_task_checklist[$check_list_row['TaskID']] = $check_list_row['TaskID'];
    }else{
        $completed_task_checklist[$check_list_row['TaskID']] = $check_list_row['TaskID'];
    }
}
foreach((array)$client_tasks_array as $ass_task){
    if(!in_array($ass_task, $completed_task_checklist)){
        $CheckList_array = $objtasks->GetAllTaskToChecklist("TaskID = $ass_task",array("*"));
        if(count($CheckList_array) > 0){
             $un_completed_task_checklist[$ass_task] = $ass_task;
        }
    }
}

if($Members_array[0]['TaskID'] > 0 && !in_array($Members_array[0]['TaskID'], (array)$client_tasks_array)){
    $client_tasks_array[] = $Members_array[0]['TaskID']; 
}
if(empty($TaskID) && $Members_array[0]['TaskID'] > 0 ){
    $TaskID = $Members_array[0]['TaskID'];
    $_REQUEST['TaskID'] = $TaskID;
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/bootstrapReset.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
<!-- End of Tabs and button code --> 
<style type="text/css">
.message_wait{
	background-color: #BEE1F1;
	border: 1px solid #9ACFEA;
	color: #31708F;
	float: left;
	font-weight: normal;
	padding: 5px;
	text-align: center;
	width: 100%;
	display:none;
	margin-top: 10px;
}
</style>
<script type="text/javascript" language="javascript">
function ValidateForm(){
return true;
if ((document.frmSample.FName.value == null) || (document.frmSample.FName.value == "")){
		alert("Please Enter Name")
		document.frmSample.FName.focus()
		return false;
	}
if ((document.frmSample.Email.value == null) || (document.frmSample.Email.value == "")){
		alert("Please Enter Email")
		document.frmSample.Email.focus()
		return false;
	}
}

function displayDate() {
   var now = new Date();
   var day = ("0" + now.getDate()).slice(-2);
   var month = ("0" + (now.getMonth() + 1)).slice(-2);
   var today = now.getFullYear() + "-" + (month) + "-" + (day);
   document.getElementById("revision").value = today;
}

function GetTaskTabs(TaskID){ 
    var url = "<?php echo SITE_ADDRESS;?>clients/ClientsEdit.php?TaskID="+TaskID+"&client_id=<?php echo $_REQUEST['id'];?>&id=<?php echo $_REQUEST['id'];?>";
    window.document.location.href = url;
    return 1;
    $('#main_body').hide();
    $('#ajax_wait').show();
    $.ajax({
    url: url,
    success: function(data) {
        $('#ajax_wait').hide();
        $('#main_body').html(data);
        $('#main_body').show();			
     }
    });
}
$(document).ready(function(){
	 $(".message_success").fadeOut(5000);
	 $(".message_error").fadeOut(5000);
});
</script>

<div id="ajax_wait" align="center" style="font-weight:bold; font-size:18px; color:#CCCCCC;">Please Wait...<br />
  <img name="ajax_loading" id="ajax_loading" border="0" src="../images/ajax-loader_wait.gif" />
</div>
<?php
if($_REQUEST['flag'] == 'add'){ ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="message_success">Record Added Successfully! </td>
    </tr>
   </table>
<?php }
if($_REQUEST['flag'] == 'update'){ ?>
    <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
        <tr>
        <td class="message_success">Record Updated Successfully!</td>
        </tr>
    </table>
<?php
} 
?>
<div class="Popupspace"></div>
  
 <div id="tabs">
 <!-- <a href='http://xurlios.com/aweber.php' target="_blank" style="position: absolute; top: 3px; right: 3px;" class='btn btn-default'>Aweber</a>-->
<table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
   <?php if($_REQUEST['Task'] != 'add'){?>
   <tr>
        <td>
		
            <select name="select" class="product" onchange="GetTaskTabs(this.value)">
                <option value="0" <?php if($TaskID == 0)echo"selected";?>>Select One</option>
                <?php foreach($client_tasks_array as $Task_ID){
                  $Task_array = $objtasks->GetAllTasks("ID = '$Task_ID'",array("*"));
                  ?>
                <option value="<?php echo $Task_array[0]['ID'];?>" <?php if($TaskID == $Task_ID)echo"selected";?>><?php echo $Task_array[0]['TasksTitle'];?> </option>
                <?php } ?>
            </select>
        </td>
		
        <td width="40%">
            <table width="10" border="0" cellspacing="1" cellpadding="1" align="center">
			
                <tr>
                    <?php 
                    $CheckList_array = $objtasks->GetAllTaskToChecklist("TaskID = '".$_REQUEST['TaskID']."'",array("*"));
                    foreach((array)$CheckList_array as $check_list_row ){
                    $ChecklistDateID = $check_list_row['ID'];

                    $CheckList_Date_array = $objtasks->GetAllCheckListDate("ID = '$ChecklistDateID' AND ClientID ='".$_REQUEST['id']."' ",array("*"));

                        ?>
                        <td>
						 
                           <table width="10" border="0" cellspacing="1" cellpadding="1" align="center">
                               <tr>
                                   <td align="center">
                                      <?php echo $check_list_row['CheckListLetter'];?> 
                                   </td>
                               </tr>
                               <tr>
                                   <td align="center">
								   <?php if(empty($CheckList_Date_array)){?>
								   
								    <img src="../images/stop.png" title="<?php echo $check_list_row['CheckListName'];?>" /> 
									   <?php 
									   }?>
									   <?php if(!empty($CheckList_Date_array)){
                                       if($CheckList_Date_array[0]['SaveDate'] == '0000-00-00 00:00:00'){
									   if(!empty($check_list_row['CheckListLetter'])){?>
                                            <img src="../images/stop.png" title="<?php echo $check_list_row['CheckListName'];?>" /> 
											<?php }
                                       } else {
									   if(!empty($check_list_row['CheckListLetter'])){?>
                                            <img src="../images/start.png" title="<?php echo $check_list_row['CheckListName'];?>" /> 
											<?php }
                                      } 
									   
									   }?>
									   
                                   </td>
                               </tr> 
                           </table>


                        </td>
                    <?php } ?>
                </tr>
            </table>
        </td>
   </tr>
   <tr>
        <td  colspan="2"></td>  
   </tr>
   <?php } ?>
</table>

<div id="main_body" style="display:block; margin-top: 5px;">
<form action="Clients.php?id=<?php echo $_REQUEST['id'];?>&Task=<?php echo $Task; ?>" method="post"  enctype="multipart/form-data" name="frmSample" >

        <ul>

        <li><a href="#tabs-contact">Contact</a></li>
        <?php if( $TaskID > 0 ){?>
            
       
            <?php switch($TaskID){
                    case 1:?>
						<li><a href="#tabs-checklist">Checklist</a></li>
                        <li><a href="#tabs-<?php echo $TaskID;?>-2">Questions</a></li>
						<li><a href="#tabs-<?php echo $TaskID;?>-1">Upgrades</a></li>
                       
                    <?php break;
                    case 2:?>
						<li><a href="#tabs-checklist">Checklist</a></li>
                        <li><a href="#tabs-<?php echo $TaskID;?>">Questions</a></li> 
                        <li><a href="#tabs-login">Listing Detail</a></li>
                    <?php break;
                    case 6:?>
					    <li><a href="#tabs-checklist">Checklist</a></li>
                        <li><a href="#tabs-<?php echo $TaskID;?>">Questions</a></li>
						<li><a href="#tabs-login">Listing Detail</a></li>
                        
                    <?php break;
					case 7:?>
					    <li><a href="#tabs-checklist">Checklist</a></li>
                        <li><a href="#tabs-<?php echo $TaskID;?>">Questions</a></li>
						<li><a href="#tabs-login">Listing Detail</a></li>
                        
                    <?php break;
            } ?>
        <?php }?>
		 <li><a href="#tabs-cheque">Check/Card</a></li>
		 <li><a href="#tabs-revision">Revisions</a></li>
         <li><a href="#tabs-notes">Notes</a></li>
          <li><a href="#tabs-payments">Payments</a></li>
                <!-- <li><a href="#tabs-tags">Tags</a></li>-->
                 
                  <li><a href="#tabs-tags">Tags</a></li>
        </ul>
        <div id="tabs-tags">
        	<table width="100%" id="tagsTable">
                <tr>
                	<td id="tabsubheading">Tags </td>
                </tr>
                <?php 
					
					$tags = new Tags();
					$clientTags = $tags->GetClientTags("ClientID='". $_REQUEST['id'] . "'", array("TagID"));
					if($clientTags){
					
					
					
					foreach($clientTags as $tagItem){
						$tagAr = $tagItem['TagID'];	
					}
					
					$tagArray = explode(",", $tagAr);
					} else {
						$tagArray = array("");	
					}
					//var_dump($tagArray);
					$tagList = $tags->GetAllTags("1 Order By Sorting", array("*"));
					
					//print_r($tagList);
					foreach($tagList as $tag) {
						echo "<tr><td><label><input type='checkbox' name='ClientTags' value='" . $tag['ID'] . "'";
						if(in_array($tag['ID'], $tagArray)){ echo " checked";}
						echo " / >&nbsp;" . $tag['Title'] . "</label></td></tr>";
					}
				?>
            	<tr>
            		<td>
                    <?php if($superLame===false){?>
            			<a href="javascript:updateTags();" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="padding: 10px; margin-top: 10px;">Save Checked Tags</a>  
                        <?php } ?>      
                    </td>
            	</tr>
            </table>

            <div align="center">
            	
            </div>
        </div>
        <div id="tabs-contact">
           <?php require_once dirname(__FILE__)."/../tabs/EcContactTab.php";?>
        </div>
        <?php if( $TaskID > 0 ){?>
            
        
        <?php switch($TaskID ){
                case 1:?>
				 <div id="tabs-checklist">
                        <?php include_once dirname(__FILE__)."/../tabs/EcTaskFullfilment.php";?>
                    </div>
                    <div id="tabs-<?php echo $TaskID;?>-2">
                        <?php require_once dirname(__FILE__)."/../product-tasks/websites/EcTaskQuestion.php";?>
                    </div>
                   
                    <div id="tabs-<?php echo $TaskID;?>-1">
                        <?php require_once dirname(__FILE__)."/../product-tasks/websites/WebSites.php";?>
                    </div>
                    <?php break;
					
                    case 2:?>
					<div id="tabs-checklist">
                        <?php include_once dirname(__FILE__)."/../tabs/EcTaskFullfilment.php";?>
                    </div>
                    <div id="tabs-<?php echo $TaskID;?>">
                        <?php require_once dirname(__FILE__)."/../product-tasks/citations/Citation.php";?>
                    </div>
					<div id="tabs-login">
              			  <?php include_once dirname(__FILE__)."/../tabs/LoginDetail.php";?>
           			 </div>
                    
                    <?php break;
					
                    case 6:
					?>
					<div id="tabs-checklist">
                        <?php include_once dirname(__FILE__)."/../tabs/EcTaskFullfilment.php";?>
                    </div>
                    <div id="tabs-<?php echo $TaskID;?>">
                        <?php require_once dirname(__FILE__)."/../product-tasks/gyb/Gyb.php";?>
                    </div>
                    <div id="tabs-login">
              			  <?php include_once dirname(__FILE__)."/../tabs/LoginDetail.php";?>
           			 </div>
                   <?php break;
				   
				    case 7:?>
					<div id="tabs-checklist">
                        <?php include_once dirname(__FILE__)."/../tabs/EcTaskFullfilment.php";?>
                    </div>
                    <div id="tabs-<?php echo $TaskID;?>">
                        <?php require_once dirname(__FILE__)."/../product-tasks/go/Go.php";?>
                    </div>
                    <div id="tabs-login">
              			  <?php include_once dirname(__FILE__)."/../tabs/LoginDetail.php";?>
           			 </div>
                   <?php break;
                }
             ?>
            <?php }?>
 	   <div id="tabs-cheque">
                <?php include_once dirname(__FILE__)."/../tabs/ChequeCard.php";?>
            </div>
            <div id="tabs-revision">
                <?php include_once dirname(__FILE__)."/../tabs/EcTaskRevision.php";?>
            </div>
            
            <div id="tabs-payments">
            	<?php include_once dirname(__FILE__)."/../tabs/payments.php";?>
            </div>
	<div id="tabs-notes">
                <?php include_once dirname(__FILE__)."/../tabs/Notes.php";?>
        </div>
            <div style="height:25px;">&nbsp;</div>
            <?php
                if(empty($_SESSION['tab_activated'])){
                   $tab_activated = 0; 
                }else{
                	$tab_activated = $_SESSION['tab_activated']; 
                }
             ?>
        <div align="center" class="bottom_fixed">
            <input type="hidden" name="tab_activated" value="0" />
           <?php if($superLame===false) { ?>
            <input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
            <?php } ?>
            <input type="hidden" name="TaskID" value="<?php echo $TaskID; ?>" />
            <input type="hidden" name="oldimage"  value=""/>
            <input type="hidden" name="postback" value="1" />
            <input type="hidden" name="active_tab_on_submit"  id="active_tab_on_submit" value="<?php echo $tab_activated; ?>" />
            <input type="hidden" name="page" value="<?php echo $_SESSION['page']; ?>" />
        </div>
	</form>
</div>	
  
</div>	
<?php
if($_REQUEST['tabsactivenotes'] != "") {
	$tab_activated = $_REQUEST['tabsactivenotes']; /* For the revision tab */
}
?>
<script type="text/javascript">
$(function() {
    $( "#tabs" ).tabs({active: <?php echo $tab_activated; ?> });
    $('#ajax_wait').hide();
});

$( "#tabs" ).on( "tabsactivate", function( event, ui ) {
   var selectedTab = $("#tabs").tabs('option', 'active');
   $("#active_tab_on_submit").val(selectedTab);   
});

function updateTags(){
	var list = "";
	var inputs = $("#tagsTable input");
	
	for(var i = 0; i < inputs.length; i++) {
		if(inputs[i].checked){
			list += inputs[i].value + ",";
		}
	}
	// get rid of last comma
	list = list.substring(0, list.length - 1);
	
	$.ajax({
		url: "<?php echo SITE_ADDRESS; ?>clients/populateTags.php?id=<?php echo $_REQUEST['id']; ?>&tags="+list,
		success:function(html){
			console.log(html);
			if(html!=""){
					alert("updated successfully");
			}
		},
		error: function(e){
			alert("failed to update tags");	
		}
	});
	
	
}
</script>