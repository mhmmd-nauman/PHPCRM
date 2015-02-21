<?php
include dirname(__FILE__)."/../lib/include.php";
$objusers = new Users();
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
$CompanyID = $_SESSION['Member']['CompanyID'];

$CompanyDetails = $objSystemSettings->GetCompanyDetails(" ID = '$CompanyID' ORDER BY ID DESC LIMIT 0,1",array("*"));

if($_REQUEST['Task'] == 'ClockedIn'){
    if(empty($_REQUEST['zone'])){
        $clock_in_time = date("Y-m-d H:i:s");
    }else{
        $date = new DateTime(null, new DateTimeZone($_REQUEST['zone']));
        $Date = object_to_array($date);
        $clock_in_time = date("Y-m-d H:i:s",strtotime($Date['date']));
    }
    $added = $objusers->InsertUserClockInTime(array(
		"UserID"		=> $_SESSION['Member']['ID'],
		"ClockInTime"   => $clock_in_time,
	));
    $_SESSION['UserClockID'] = $added;
}

if($_REQUEST['Task'] == 'ClockedOut'){    
    if(empty($_REQUEST['zone_out'])){
        $clock_out_time = date("Y-m-d H:i:s");
    }else{
        $date = new DateTime(null, new DateTimeZone($_REQUEST['zone_out']));
        $Date = object_to_array($date);
        $clock_out_time = date("Y-m-d H:i:s",strtotime($Date['date']));
    }
    
    $objusers->UpdateUserClockTime("ID = '".$_SESSION['UserClockID']."' ",array(
		"ClockoutTime" => $clock_out_time,
	));
    unset($_SESSION['UserClockID']);
}

$Users_InOutTime_array = $objusers->GetUserClockInOutTime("UserID = '".$_SESSION['Member']['ID']."' ORDER BY ID DESC LIMIT 0,1 ",array("*"));
 
if($_SESSION['UserClockID']){
    $time_clockin_array  = $Users_InOutTime_array[0]['ClockInTime']; 
    $time_clockin = explode(" ", $time_clockin_array);
    $time_in = $time_clockin[1];
    $time_in_hour = explode(":", $time_in);
    $hours_in = $time_in_hour[0];
    $min_in = $time_in_hour[1];
    $am_pm_in="AM";
    if($hours_in >= 12){
            $am_pm_in = "PM";
            $hours_in = $hours_in - 12;
    }
    if($hours_in == 0){
        $hours_in = 12;
    }
}else{
    $time_clockin_array  = $Users_InOutTime_array[0]['ClockoutTime']; 
    $time_clockin = explode(" ", $time_clockin_array);
    $time_in = $time_clockin[1];
    $time_in_hour = explode(":", $time_in);
    $hours_in = $time_in_hour[0];
    $min_in = $time_in_hour[1];
    $am_pm_in = "AM";
    if($hours_in >= 12){
		$am_pm_in = "PM";
		$hours_in = $hours_in - 12;
    }
    if($hours_in == 0){
        $hours_in = 12;
    }
}

if($_SESSION['Member']['ZoneID'] > 0 ){
  $objzone = new Zones();
  $Zones_array = $objzone->GetAllZones("ID ='".$_SESSION['Member']['ZoneID']."'",array("*")); 
  $date = new DateTime(null, new DateTimeZone($Zones_array[0]['ZoneTime']));
  $Date = object_to_array($date);
  $clock_time = date("Y-m-d H:i:s",strtotime($Date['date']));
}else{
    $clock_time = date("Y-m-d H:i:s");
}
 
$user_local_time = date("h:i a",strtotime($clock_time));
$current_time = explode(":",$user_local_time);

if($current_time[0] == 0){
	$current_time[0] == 12;
	$current_local_time = $current_time[0]." ".$current_time[1];
}else{
	$current_local_time=$user_local_time = date("h:i a",strtotime($clock_time));
}

if($login_page != 1){
?>
<!doctype html>
<html lang="us">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Xurli Manager</title>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css">
<link rel="stylesheet" media="screen" href="<?php echo SITE_ADDRESS;?>css/superfish.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css"> 
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/hoverIntent.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/superfish.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/supersubs.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jstz-1.0.4.min.js"></script>
<script type="text/javascript">
function modalbox2(linkt,setTitle,SetHeight,SetWidth){
$('<iframe id="some-dialog" class="window-Frame" src='+linkt+' />').dialog({
		autoOpen: true,
		width: SetWidth,
		height: SetHeight,
		modal: true,
		resizable: true ,
					title:setTitle 
	}).width(SetWidth-20).height(SetHeight-20);
}
function modalbox(linkt,setTitle,SetHeight,SetWidth){
$('<iframe id="some-dialog" class="window-Frame" src='+linkt+' />').dialog({
		autoOpen: true,
		width: SetWidth,
		height: SetHeight,
		modal: true,
		resizable: true ,
					title:setTitle 
	}).width(SetWidth-20).height(SetHeight-20);
}
 
$(document).ready(function(){ 
	$("ul.sf-menu").supersubs({ 
		minWidth:    12,   // minimum width of sub-menus in em units 
		maxWidth:    27,   // maximum width of sub-menus in em units 
		extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
						   // due to slight rounding differences and font-family 
	}).superfish();  // call supersubs first, then superfish, so that subs are 
					 // not display:none when measuring. Call before initialising 
					 // containing tabs for same reason. 
	$("#updateprofile").click(function(e){
		e.preventDefault();	
		modalbox2(this.href,this.title,550,800);
	});
}); 
 

</script>
    
</head>

<body style="background-color:#fff;">
<div style="height:70px;margin-left:10px;">
<?php
$companylogo = SITE_ADDRESS.$CompanyDetails[0]['InvoiceImage'];
$file = $_SERVER['DOCUMENT_ROOT']."/".$CompanyDetails[0]['InvoiceImage'];
if(!empty($CompanyDetails[0]['InvoiceImage']) and file_exists($file)){
?>
    <img style="height:70px;" src="<?php echo $companylogo; ?>" />
<?php
}else{
?>
    <img style="height:70px;" src="<?php echo SITE_ADDRESS;?><?php echo $SystemSettingsArray[0]['Path']?>" />
<?php
}
?>
<div style="float:right; margin:13px; border:1px solid #e1e1e1; margin-top:10px;">
        <div style="color: #797979; float: left; font-size: 15px;padding-left: 5px; padding-right: 5px; padding-top: 16px; "><?php if($_SESSION['Member']['ID'] > 0){ ?>Welcome<?php }?>&nbsp;&nbsp;<?php echo $_SESSION['Member']['FirstName']." ".$_SESSION['Member']['LastName']; ?>
	
	</div>
          <?php if($_SESSION['Member']['ID'] > 0){?>  
	<span style="padding:5px 5px 5px 5px;">
            <a href="<?php echo SITE_ADDRESS;?>users/UsersEdit.php?Task=updateprofile" id="updateprofile" title="Update Profile"><img  src="<?php echo SITE_ADDRESS;?><?php if($_SESSION['Member']['ProfileImage'] != ""){  echo $_SESSION['Member']['ProfileImage']; }else{ echo "images/pic-blank.png"; }?>" width="40" height="40" style="border:none; padding-top:5px;" title="<?php echo $_SESSION['Member']['FirstName']." ".$_SESSION['Member']['LastName']; ?>" /></a></span>
          <?php }else{ ?>
           <span style="padding:5px 5px 5px 5px;">
            <img  src="<?php echo SITE_ADDRESS;?><?php if($_SESSION['Member']['ProfileImage'] != ""){  echo $_SESSION['Member']['ProfileImage']; }else{ echo "images/pic-blank.png"; }?>" width="40" height="40" style="border:none; padding-top:5px;" title="<?php echo $_SESSION['Member']['FirstName']." ".$_SESSION['Member']['LastName']; ?>" /></span> 
          <?php } ?>
	</div>
	<?php if($_SESSION['Member']['ID']>0){?>
	<?php if(empty($_SESSION['UserClockID'])){?>
	<div style="float:right;height:38px; margin-top: 10px;" id="Clockout">
            <form action="?Task=ClockedIn" method="post" >
           <div><img src="<?php echo SITE_ADDRESS;?>user_pics/clockout.png"/></div>
		 <div style="clear:both;"></div>
	<div style="margin-top:-25px; margin-left:30px; "><span style="font-size:14px; color:#FFFFFF; font-weight:bold; padding-left:15px;padding-top:5px;">Clocked Out  <?PHP echo $hours_in.":".$min_in." ".$am_pm_in;?></span><span style="font-size:14px; color:#FFFFFF; font-weight:bold; padding-left:30px;padding-top:5px;">Current Time:</span><span style="font-size:14px; color:#FFFFFF; font-weight:bold; padding-left:5px;" id="current_time"><?PHP echo $current_local_time;?></span> <span style=" padding-top:5px;  float:right; margin-top:-15px;"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit"  value="Clock In" ></span>
	
	 
	</div>
             <input type="hidden" name="zone" id="zone" value="" />
        </form>
	</div>

	<?php }else{?>
	<div style="float:right; height:38px; margin-top: 10px;" id="Clockin">
        <form action="?Task=ClockedOut" method="post" >
		<div><img src="<?php echo SITE_ADDRESS;?>user_pics/clockin.png"/></div>
		 <div style="clear:both;"></div>
	<div style="margin-top:-25px; margin-left:30px; "><span style="font-size:14px; color:#FFFFFF; font-weight:bold; padding-left:15px;padding-top:5px;">Clocked In <?PHP echo $hours_in.":".$min_in." ".$am_pm_in;?></span><span style="font-size:14px; color:#FFFFFF; font-weight:bold; padding-left:30px;padding-top:5px;">Current Time:</span><span style="font-size:14px; color:#FFFFFF; font-weight:bold; padding-left:5px;" id="current_time"><?PHP echo $current_local_time;?></span> <span style=" padding-top:5px; float:right; margin-top:-15px;" ><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="submit"  value="Clock Out"></span>
	
	</div>
        <input type="hidden" name="zone_out" id="zone_out" value="" />
        </form>
	</div>
	<?php } 
	
	}?>
	
</div>
<div class="container">
<?php
include dirname(__FILE__)."/../include/nav.php" ;
}
function object_to_array($data){
    if (is_array($data) || is_object($data)){
        $result = array();
        foreach ($data as $key => $value){
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}
?>