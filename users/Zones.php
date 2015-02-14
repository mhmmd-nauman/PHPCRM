<?php 
// This File is Developed by Irsa Shoudhary
include "../include/header.php";
$objzone = new Zones();
$Zones_array = $objzone->GetAllZones("1 ORDER BY Created ASC",array("*")); 
//print_r($project_array);
//print_r($Vendors_array );
//exit;
 if (defined('DateTimeZone::AMERICA')) {
    // PHP 5.3+
    $timezoneIdentifiers = timezone_identifiers_list(DateTimeZone::AMERICA);
} else {
    // PHP 5.2
    $timezoneIdentifiers = timezone_identifiers_list();
    $timezoneIdentifiers = array_filter($timezoneIdentifiers, create_function('$tz', 'return preg_match("/^America\//", $tz);'));
}
//echo "<pre>";
//print_r($timezoneIdentifiers);
//echo "</pre>";
/*foreach($timezoneIdentifiers as $timezon){
$added= $objzone->InsertZones1(array(
                                              
                                              "ZoneName"=>$timezon,
					      "ZoneAttach" =>2,
                                             
					      
											  //"HasDeleted"            =>$_REQUEST['clientID'],
                                                                                                                             


                        ));
}
 
 */
?>
<script type="text/javascript" src="../js/search.js"></script>

<link rel="stylesheet" type="text/css" href="../css/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<script type="text/javascript">
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
                    
                  
                    $("#ZoneAdd").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
                    <?php  foreach((array)$Zones_array as $Zones_row){?>        
                       $("#ZoneEdit<?php echo $Zones_row['ID'];?>").click(function(e){
                                     e.preventDefault();	
                                     modalbox(this.href,this.title,550,800);
                         });	 
                <?php } ?>
             });
		
	
		
</script>




<div id="headtitle">Zones</div>
<div class="filtercontainer">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="HubUserSearchForm" id="HubUserSearchForm" action="" method="post" enctype="multipart/form-data">

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
    </td>
    <td style=" display: none;">
    <div align="right">
            <a href="<?php echo SITE_ADDRESS;?>users/ZonesEdit.php" id="ZoneAdd" title="Add New Zone"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"  value="Add New Zone" ></a>
     </div>
    </td>
  </tr>
</table>
</div>

<?php if($_REQUEST['flag']=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      A Zone record has been deleted successfully!
  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
	<?php if($_REQUEST['flag']=='add'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  id="message_success">
      A Zone record has been Added successfully!
  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<?php if($_REQUEST['flag']=='update'){?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      A Zone record has been updated successfully!
	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
			
    </table>
	
	<?php if($_REQUEST['flag']=='error'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
   
  <td id="message_error">
      This email already exist in the database!
      <script>
		$(document).ready(function(){
  
        $("#message_error").fadeOut(3000);
  });
		</script>
</td>
  </tr>
     
    <?php }?>
</table>
	
<div class="subcontainer">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr id="headerbar" >
  <td height="45" width="44" >ID</td>
   <td  > Name </td>
   <td width="100">Time Zone:</td>
   <td width="100">Server time +/-</td>
    <td class="Action">Actions</td>

  </tr>
   <?php  foreach((array)$Zones_array as $Zones_row){
  
		if($flag==0){
                    $flag=1;
                    $row_class="row-white";
                }else{
                    $flag=0;
                    $row_class = "row-tan";
               }     	  
  ?>
   <tr id="<?php echo $row_class;?>" >
   <td> <?php echo  $Zones_row['ID'];?></td>
  <td> <?php echo $Zones_row['Name'];?></td>
  <td> <?php echo $Zones_row['ZoneTime'];?></td>
   <td> <?php echo $Zones_row['AdjustHours'];?></td>
  
    <td  class="Action">
        <a id="ZoneEdit<?php echo $Zones_row['ID'];?>" href="<?php echo SITE_ADDRESS;?>users/ZonesEdit.php?ID=<?php echo $Zones_row['ID'];?>" title="Edit <?php echo $Zones_row['Name'];?>"> <img src="../images/icon_page_edit.png" border="0" title="Edit Details"/></a>
        <a href="?id=<?php echo $Zones_row['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../images/icon_delete.png" border="0"/></a></td>
  </tr>
 <?php }?>
</table>
<div align="center">
<?php include "../lib/bottomnav.php"; ?>
</div>

<?php include "../include/footer.php"; ?>

