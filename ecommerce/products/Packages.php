<?php include "../../include/header.php"; 
$objpackges = new Packges();
//$objmember = new Member();
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
$objHubFlxMembers = new WebSites();
//$HubFlxSiteStatus = $objHubFlxMembers->GetAllHubFlxMember(" 1",array("HubFlxMember.Status"));

if(isset($_REQUEST['sortBy']))$_SESSION['sortBy']=$_REQUEST['sortBy'];
if(isset($_REQUEST['sortValue']))$_SESSION['sortValue']=$_REQUEST['sortValue'];
$sortBy = $_SESSION['sortBy'];
$sortValue = $_SESSION['sortValue'];
if(empty($sortBy)) $sortBy = "Date";
if(empty($sortValue))$sortValue = "DESC";
switch($sortBy){
  case "Date":
  	switch($sortValue){
	
	case "ASC":
	
	$sortText = "ORDER BY Created ASC";
	$sortName = "DESC";
	break;
	case "DESC":
	
	$sortText = "ORDER BY Created DESC";
	$sortName = "ASC";
	break;
	}
  break;
}



 //$group_array = $ObjGroup->GetAllGroups(" ID ='$mid' ORDER BY Sort",array("*"));



if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
	
}
if(isset($_REQUEST['SearchText'])){
	$_SESSION['SearchText'] = trim($_REQUEST['SearchText']);

}
(isset($_REQUEST['SelectedGroup']))?$_SESSION['SelectedGroup']=$_REQUEST['SelectedGroup']:"";
if(!isset($_SESSION['SelectedGroup'])){
    $_SESSION['SelectedGroup']=array("5");
}

if(!empty($_REQUEST['page'])) {
   $page=$_REQUEST['page'];
} else {
  $page=1;
}
$_SESSION['page'] = $page;





$total_records   =  $info_arrayTotal[0]['Total'];
//print_r($info_arrayTotal);

if(!isset($_SESSION['limit'])){
	$limit = 10 ;
} else if($_SESSION['limit'] =="all" ){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'] ;
}

//$ret = $objmember->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}

$Packge_array = $objpackges->GetAllPackges("1 ORDER BY Created ASC",array("*")); 


//print_r($Packge_array);
?>

<script type="text/javascript" src="js/search.js"></script>
<link rel="stylesheet" type="text/css" href="css/search.css" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="<?php echo SITE_ADDRESS;?>css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />

<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Tooltip/js/tooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>js/Tooltip/css/tooltip.css" media="screen" />

<!--<script type="text/javascript" src="js/search.js"></script>
<link rel="stylesheet" type="text/css" href="css/search.css" />-->
<script type="text/javascript">
$(document).ready(function() {
    $(".hubopus_popup").fancybox({
        'width'                       : 820,
        'height'                      : 520,
        'autoScale'                   : false,
        'transitionIn'                : 'none',
        'transitionOut'               : 'none',
        'href'                        : this.href,
        'type'                        : 'iframe'
    });

});

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
			$("#fromdatepicker").datepicker();
		});
		$(function() {
			$(".fromdatepicker").datepicker();
		});
		$(function() {
			$("#todatepicker").datepicker();
		});
		$(function() {
			$(".todatepicker").datepicker();
		});
</script>




<div id="headtitle"> Packages</div>
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
    <td align="right">
    <div style="width:200px;" >
	<!--<a href="RevisionNotes.php?kid=6" class="hubopus_popup" id="addhubopususer"><img src="images/36_tags_script.png"  title="Revision Notes" /></a>-->
            <a href="PackagesEdit.php?Task=Add" class="hubopus_popup Ecom_Link" id="addhubopususer">Add New Package</a>
     </div>
    </td>
  </tr>
</table>
</div>
<?php if($_REQUEST['flag']=='add'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_success">
      Package record has been Aded successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<?php if($_REQUEST['flag']=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_success">
      Package record has been deleted successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    </table>
<?php } ?>

    <?php if($_REQUEST['flag']=='success'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_success">
      Package record has been Updated successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    </table>
<?php } ?>
	
	<?php if($_REQUEST['flag']=='error'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
   
  <td align="left" colspan="3" id="message_error">
      This Email already exists in the database!
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
  <tr id="headerbar"  style="background-color:<?php echo "#".$SystemSettingsArray[0]['HeaderColor']; ?>">
  	<td height="45" width="44" >ID</td>
  	
  	<td width="200" >Title </td>
    
    <td >Description</td>
	 <td >&nbsp;</td>
    
    <td width="46">Actions</td>
  </tr>
  
       
  <?php  foreach((array)$Packge_array as $Packge_row){
  
            //$hubflexmeb_array = $objmember->GetAllMemberWithHubFlexMenber(" HubFlxMember.MemberID = '".$HubFlxUserRow['ID']."'",array("HubFlxMember.*,Member.FirstName"));// print_r($hubflexmeb_array);
           // $revision_array =  $objmember->GetAllRevision(" MemberID = '".$HubFlxUserRow['ID']."' AND Done = 0",array("count(*) as total"));
			//echo($hubflexmeb_array[0]['Status']." ".$hubflexmeb_array[0]['ID']);    
		 $undone_total = $revision_array[0]['total'];  
    		 if($undone_total > 0 ){
                    //$revision_array =  $objmember->GetAllRevision(" MemberID = '".$HubFlxUserRow['ID']."'AND Done = 1",array("count(*) as total"));
                    $_total = $revision_array[0]['total'];
                    if($_total > 0){
                        $FlagRevision="green";
                    }else{
                        $FlagRevision="red";
                    }
		  }else{
                    $FlagRevision="green";
		 }
			 
			 if((int)date("Y",strtotime($HubFlxUserRow['ContentComplete'])) > 1970) 
			 {
			  $FlagContentComplete="green";
			 }
			 else
			 {
			  $FlagContentComplete="red";
			 }
			   
			 if((int)date("Y",strtotime($HubFlxUserRow['DesignComplete'])) > 1970) 
			 {
			  $FlagContentReceived="green";
			 }
			 else
			 {
			  $FlagContentReceived="red";
			 }
			 
			 if((int)date("Y",strtotime($HubFlxUserRow['WelCallComDate'])) > 1970)
			 {
			  $WelCallComDate="green";
			 }
			 else
			 {
			  $WelCallComDate="red";
			  }
			 
			 if(isset($hubflexmeb_array))
			 {
  				$FlagSite="green";
				}
				else
				{
				$FlagSite="red";
				}
				
			
			
			if(date("Y",strtotime($HubFlxUserRow['BestCallTime']))==date("Y")&&date("m",strtotime($HubFlxUserRow[		            'BestCallTime']))==date("m")&&date("d",strtotime($HubFlxUserRow['BestCallTime']))<date("d"))
			{
				$FlagBestCallTime="green";
			}
			else
			{
				$FlagBestCallTime="red";
			}
			 
			if((int)date("Y",strtotime($HubFlxUserRow['SiteAcceptanceFinal'])) > 1970) 
			 {
			  $SiteAcceptanceFinal="green";
			 }
			 else
			 {
			  $SiteAcceptanceFinal="red";
			 } 
			 
	  /* if($HubFlxUserRow['Status'] == '6'){
	  	 $row_class = "row-green";
	   }*/
	   if($FlagContentComplete=="green"&&$FlagContentReceived=="green"&&$WelCallComDate==="green"&&$FlagSite=="green"&&$FlagRevision=="green"&&$SiteAcceptanceFinal=="green")
	   {
	   
                $row_class = "row-green";
	   }
	   
	   elseif((int)date("Y",strtotime($HubFlxUserRow['WelCallComDate'])) > 1970){
	  	  $row_class = "row-yellow";
		 
	   }
	   
	   else{
	  	 $row_class = "row-red";
	   }
	   
	   	  
  ?>
  <tr id="<?php echo $row_class;?>">
   <td> <?php echo $Packge_row['ID'];?></td>
   
  
   
     
          
          <td width="200">
		  <?php echo $Packge_row['PackagesTitle'];?>
                       </td>
      
	   
    <td>  <?php echo $Packge_row['PackgesDescription'];?>   </td>
    <td> &nbsp;</td>
   
	
	
	
    <td >
	
        <a class="hubopus_popup" href="PackagesEdit.php?id=<?php echo $Packge_row['ID'];?>&Task=Update"> <img src="../../images/icon_page_edit.png" border="0" title="Edit Details"/></a>
        <a href="PackagesEdit.php?id=<?php echo $Packge_row['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../../images/icon_delete.png" border="0"/></a></td>
  </tr>
 <?php }?>
</table>
<div align="center">
<?php include "../../lib/bottomnav.php" ?>
<script type="text/javascript">

	$("#SelectAll").click(function(){

		if($(this).is(':checked')){

			$(".MemberSelectcheckBox").attr('checked','checked');

		}else{

			$(".MemberSelectcheckBox").removeAttr('checked');

		}

	});


</script>
</div>

<? include "../../include/footer.php" ?>
