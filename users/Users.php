<?php 
include "../include/header.php";
$objusers = new Users();
$ObjGroup = new Groups();
$ObjClient = new Clients();
$Search = "";
# Company ID of the logged in member
$AgentCompanyID = $_SESSION['Member']['CompanyID'];
if(isset($_REQUEST['SiteStatus'])){
	$_SESSION['SiteStatus'] = $_REQUEST['SiteStatus'];
} else {
	$_SESSION['SiteStatus'] = "active";
}

if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
	
}
if(isset($_REQUEST['SearchText'])){
	$_SESSION['SearchText'] = trim($_REQUEST['SearchText']);

}
if(isset($_REQUEST['SelectedGroup'])) {
	$_SESSION['SelectedGroup'] = $_REQUEST['SelectedGroup'];
	//print_r($_SESSION['SelectedGroup']);
	$counter = 1;
	$max = count($_SESSION['SelectedGroup']);
	$inMix = "";
	foreach($_SESSION['SelectedGroup'] as $group){
		if($counter==$max) {
			$inMix .="$group";
		} else {
			$inMix .="$group,";
		}
		$counter++;	
	}
	
	
	$Search .=" GroupID in($inMix)";	

} else {
	//$_SESSION['SelectedGroup'] = array("5");
}
/*
if(!isset($_SESSION['SelectedGroup'])){
    $_SESSION['SelectedGroup'] = array("5");
}
*/

if(!empty($_REQUEST['page'])){
	$page = $_REQUEST['page'];
} else {
	$page = 1;
}

$_SESSION['page'] = $page;

$total_records   =  10;

if(!isset($_SESSION['limit'])){
	$limit = 10 ;
} else if($_SESSION['limit'] == "all" ){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'] ;
}

$ret = $objusers->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}

if($_SESSION['SiteStatus']=="active"){
	if(!empty($Search)){
		$Search .= " and Users.HasDeleted='0'";
	} else {
		$Search .= "Users.HasDeleted='0'";
	}
} else {
	if(!empty($Search)){
		$Search .= " and Users.HasDeleted='1'";
	} else {
		$Search .= " Users.HasDeleted='1'";
	}
}


# Not in Array 2 means not a Super Admin. In Array means is a Super Admin as
# well as may also belong to other group as well
if(!in_array(2, (array)$_SESSION['user_in_groups'])){
	if(!empty($Search)){
		$Search .= " and `Company`.ID = '$AgentCompanyID' ";
	}else{
		$Search .= " `Company`.ID = '$AgentCompanyID' ";
	}
}elseif(in_array(2, (array)$_SESSION['user_in_groups'])){
	if(!empty($Search)){
		$Search .= " and 1 ";
	}else{
		$Search .= " 1 ";
	}
}


//echo "normal one";
$Users_array = $objusers->GetAllUsersAndGroupsForUsersPage(" $Search ORDER BY Created DESC ",array(USERS.".*",ZONES.".Name")); 



?>

<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />

<script type="text/javascript">
//console.log("Agent company id: <?php echo $AgentCompanyID; ?>");
function confirmation() {
	var answer = confirm("Do you want to delete this record?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
</script>
<script type="text/javascript">
$(function() {           
	$("#userAdd").click(function(e){
			  e.preventDefault();	
			  modalbox(this.href,this.title,"large");
	});
	
	$(".UserEdit").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,"large");
	});	 

});

$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	jQuery("#show_options").show();
	$('#changename').attr('name', '');
});



</script>
<?php //echo $Search; ?>
<div id="headtitle"> Users</div>
<div class="filtercontainer">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
        <form name="HubUserSearchForm" id="HubUserSearchForm" action="?Task=SetFilter" method="post" enctype="multipart/form-data">
        	<div class="adv_search">
                <div class="adv_search_sub">
                    <div class="input_box">
                        <input name="SearchText" id="mainsearch" class="input_box_2" type="text" value="<?php echo $_SESSION['SearchText'];?>" />
                        <div id="show_options" class="aoo" tabindex="0" gh="sda" role="button" aria-label="Show search options" data-tooltip="Show search options" style="">
                       		<div class="aoq"></div>
                        </div>
                    </div>
                    <div class="adv_btn">
                    	<input name="Submit" type="submit" class="adv_btn_2" value="" />
                    </div>
                </div>
                <div class="cate_main" id="cate_main" style="display:none;position:absolute; z-index: 100000;">
                    <div id="search_close" tabindex="0" role="button" class="Zy"></div>
                    <br /><br />
                    <div class="search_rows">&nbsp;&nbsp;Name/Email:<br />
                    	<input name="SearchText" type="text" class="in_put2" id="changename" value="<?php echo $_SESSION['SearchText'];?>" size="30" maxlength="30"/>
                    </div>
                    
                    <div class="search_row in_put2" style="width:307px;">
                        <table cellpadding="0" cellspacing="0" width="100%" border="0">
                            <tr>
                                <td>From:<br />&nbsp;
                                    <input type="text" id="fromdatepicker" name="FromDate" size="8" style="padding: 1px 4px; width: 130px;" value="<?php echo $_SESSION['FromDate'];?>" />
                                </td>
                                <td valign="bottom"> To:<br />
                                    <input type="text" id="todatepicker" name="ToDate" size="8" style="padding: 1px 4px; width: 130px;" value="<?php echo $_SESSION['ToDate'];?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td  valign="top">Status:<br />
                                <select name="SiteStatus">
                                    <!--<option value="all"  <?php if($_SESSION['SiteStatus'] == "all" )echo "selected";?>>All</option>-->
                                    <option value="active" <?php if($_SESSION['SiteStatus'] == "active" )echo "selected";?>>Active</option>
                                    <option value="deactive" <?php if($_SESSION['SiteStatus'] == "deactive" )echo "selected";?>>Deactive</option>
                                </select>                
                                </td>
                                <td>Groups:<br />
                                    <div style="margin-left:4px; height:87px;width:190px;overflow:auto;border:1px solid #CCCCCC; margin-top:4px;" id="ScrollCB">
                                        <?php $membership_group = $ObjGroup->GetAllGroups("1 ORDER BY Title, ID ",array("*"));?>
                                        <input  name="SelectedGroup[]"  id="SelectAll" <?php if(is_array($_SESSION['SelectedGroup'])){if(in_array('all' , $_SESSION['SelectedGroup'])){ echo "checked='checked'";}} ?>  type="checkbox" value="all" >All<br/>
                                        <?php foreach($membership_group as $mem_grp) {?>
                                        <input  name="SelectedGroup[]"  id="SelectedGroup_<?php echo $mem_grp['ID']; ?>" Class="MemberSelectcheckBox" type="checkbox" value="<?php echo $mem_grp['ID'];?>" 
                                        <?php if(is_array($_SESSION['SelectedGroup'])){if(in_array($mem_grp['ID'] , $_SESSION['SelectedGroup'])){ echo "checked='checked'";}} ?>>
                                        <label for="SelectedGroup_<?php echo $mem_grp['ID']; ?>"><?php echo $mem_grp['Title'];?></label> <br/>
                                        <?php } ?>
                                    </div>
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
        </form>
    </td>
    <td>
    <div align="right">
            <a href="UsersEdit.php?Task=Add" id="userAdd" title="Add New User"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"  value="Add New User" id="Addnew"></a>
     </div>
    </td>
  </tr>
</table>
</div>

	<?php if($_REQUEST['flag'] == 'add'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td colspan="3" id="message_success">User Record Added Successfully!</td>
        </tr>
    </table>
    <?php }
	if($_REQUEST['flag'] == 'del'){
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td  colspan="3" id="message_success">User Record Deleted Successfully!</td>
        </tr>
    </table>
    <?php }
	if($_REQUEST['flag'] == 'update'){
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td  colspan="3" id="message_success">User Record Updated Successfully!</td>
        </tr>
    </table>
    <?php
    }
	if($_REQUEST['flag'] == 'error'){
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="3" id="message_error">This Email already exists in the database!</td>
        </tr>
    </table>
    <?php
    }
	
	?>

<div class="subcontainer">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="UserTable">
    <thead>
        <tr id="headerbar">
            <td height="45" width="44">ID</td>
            <td width="136">Created</td>
            <td>Company Name</td>
            <td>Name</td>
            <td>Email</td>
            <td>Phone Number</td>
            <!--<td >Password</td>-->
            <td >Zones</td>
            <td>Group</td>
            <td width="80" class="Action">Actions</td>
          
        </tr>
        </thead>
        <tbody>
    <?php
    foreach((array)$Users_array as $Users_row){
		if($flag == 0){
			$flag = 1;
			$row_class = "row-white";
		}else{
			$flag = 0;
			$row_class = "row-tan";
		}
		$CompanyName = $ObjClient->FetchAgentCompanyName(trim($Users_row['ID']));
		$group = $ObjGroup->GetAllGroupsUsersPage("UserID='".$Users_row['ID']."'", array("*"));
		# $Users_row['Name'] indicates the zone name. It would be better if database field name was created
		# as ZonName or something similar which would be more understandable for other developers also.
		?>
		<tr id="<?php echo $row_class; ?>">
		<td><?php echo $Users_row['ID']; ?></td>
		<td><?php echo date("<b>M d</b>, Y",strtotime( $Users_row['Created'])); ?></td>
        <td><?php echo $CompanyName; ?></td>
		<td><?php echo $Users_row['FirstName']." ".$Users_row['LastName']; ?></td>
		<td><?php echo $Users_row['Email']; ?></td>
		<td><?php if(!empty($Users_row['Phone'])) echo $Users_row['Phone']; else echo $Users_row['AlternatePhone']; ?></td>
		<!--<td><?php echo $Users_row['Password']; ?></td>-->
		<td><?php echo $Users_row['Name']; ?></td>
       <td><?php if(!empty($group[0]['Title'])) echo $group[0]['Title']; else echo "&nbsp;"; ?></td>
		<td class="Action">
		<a id="UserEdit<?php echo $Users_row['ID'];?>" class="UserEdit" title="<?php echo $Users_row['FirstName']." ".$Users_row['LastName']."".$Users_row['Phone'];?>" href="UsersEdit.php?id=<?php echo $Users_row['ID'];?>&Task=update"> <img src="../images/icon_page_edit.png" border="0" title="Edit Details"/></a>
		<a href="Users.php?id=<?php echo $Users_row['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../images/icon_delete.png" border="0"/></a>
        </td>
		
        </tr>
        </tbody>
    <?php
    }
	?>
    </table>

<?php include "../include/footer.php" ?>
</body>
<script type="text/javascript">
	$("#SelectAll").click(function(){
		if($(this).is(':checked')){
			$(".MemberSelectcheckBox").attr('checked','checked');
		}else{
			$(".MemberSelectcheckBox").removeAttr('checked');
		}
	});
	
	$(document).ready(function(){
		$("#message_success").fadeOut(3000);
		$("#message_error").fadeOut(3000);

    /*
	var oTable = $('#UserTable').dataTable({
		"iDisplayLength": 15

});*/

	});
</script>
</html>
