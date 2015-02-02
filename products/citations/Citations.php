<?php
include "../../include/header.php";
$objmember = new Member();
$ObjGroup = new Groups();

$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
 
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
if(isset($_SESSION['SearchText']) && $_SESSION['SearchText'] != ''){

 $member_search = " (".MEMBERS.".ID = '".$_SESSION['SearchText']."' OR FirstName LIKE '%".$_SESSION['SearchText']."%' OR  Email LIKE'%".$_SESSION['SearchText']."%'  OR Surname LIKE '%".$_SESSION['SearchText']."%' OR CompanyName LIKE '%".$_SESSION['SearchText']."%')" ;

}else{
//$search="1";

}
if(!empty($_SESSION['FromDate']) && !empty($_SESSION['ToDate'])){
	if(empty($search)){
	$member_search = " Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";

	}else{
		$member_search .= " AND Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	}
}

$skip_group =0;
//print_r($_SESSION['SelectedGroup']);
//print_r($_REQUEST['SelectedGroup']);
foreach($_SESSION['SelectedGroup'] as $selected_group){
    if($selected_group!='all'){
        $search_group_ids .= "$selected_group,"; 
    }else{
        $skip_group=1;
        $search_group_ids=",";
        break;
    }
}
if(empty($skip_group)){
    (!empty($member_search))?$member_search .=" AND ":"";
    $search_group_ids = substr($search_group_ids, 0, strlen($search_group_ids) - 1);
    $member_search .=" GroupID in ($search_group_ids)";
}
if(empty($member_search)){$member_search=" HasDeleted = 0 ";}else{$member_search.=" AND ".MEMBERS.".HasDeleted = 0 ";}
if($skip_group == 1){
    $info_arrayTotal = $objmember->GetAllMember( $member_search ,array("count(".MEMBERS.".ID) as Total"));
}else{
    $info_arrayTotal = $objmember->GetAllMemberWithGroup( $member_search ,array("count(".MEMBERS.".ID) as Total"));
}
$total_records   =  $info_arrayTotal[0]['Total'];
//print_r($info_arrayTotal);

if(!isset($_SESSION['limit'])){
	$limit = 10 ;
} else if($_SESSION['limit'] =="all" ){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'] ;
}

$ret = $objmember->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}
if($skip_group == 1){
    $HubFlxUserRows = $objmember->GetAllMember(" $member_search $sortText LIMIT $offset,$limit ",array(MEMBERS.".*"));
}else{
    $HubFlxUserRows = $objmember->GetAllMemberWithGroup(" $member_search $sortText LIMIT $offset,$limit ",array(MEMBERS.".*"));
}
//echo $member_search;
//print_r($HubFlxUserRows);
$HubFlxSessionName = $objmember->GetAllMember("ID='".$_SESSION['Member']['ID']."'",array("FirstName","Surname")); 
?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css" />
<link href="<?php echo SITE_ADDRESS;?>css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
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




<div id="headtitle"> Citations </div>
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
    <div class="search_row">&nbsp;&nbsp;Name/Email:<br />
                <input name="SearchText" type="text" class="in_put2" id="changename" value="<?php echo $_SESSION['SearchText'];?>" size="30" maxlength="30"/>
                <!--<input type="text" name="SearchText" class="in_put2" value="<?php echo $_SESSION['SearchText'];?>"/>-->
        </div>
	  <div class="search_row in_put2" style="width:307px;">
	  <table cellpadding="0" cellspacing="0" width="100%" border="0">
                  <tr>
                    <td><!--<input type="radio" name="SearchText"  value="daterange" <?php if($_SESSION['SearchText']=='daterange')echo"checked";?> />-->
                      From:<br />
                      &nbsp;
                      <input type="text" id="fromdatepicker" name="FromDate" size="8" style="padding: 1px 4px; width: 130px;" value="<?php echo $_SESSION['FromDate'];?>" />
                    </td>
                    <td valign="bottom"> To:<br />
                      <input type="text" id="todatepicker" name="ToDate" size="8" style="padding: 1px 4px; width: 130px;" value="<?php echo $_SESSION['ToDate'];?>"/>
                    </td>
                  </tr>

				  <tr>
                    <td valign="top">
                      Status:<br />
					  <select name="SiteStatus">
					  
                        <option value="all"  <?php if($_SESSION['SiteStatus'] == "all" )echo "selected";?>>All</option>
                      </select>

                    </td>
                    <td> 
                     Groups:<br />
                   <div style="margin-left:4px; height:87px;width:190px;overflow:auto;border:1px solid #CCCCCC; margin-top:4px;" id="ScrollCB">
                   
                   
                      <?PHP $membership_group = $ObjGroup->GetAllGroups("1 ORDER BY Title, ID ",array("*"));?>
                         <input  name="SelectedGroup[]"  id="SelectAll" <?php if(is_array($_SESSION['SelectedGroup'])){if(in_array('all' , $_SESSION['SelectedGroup'])){ echo "checked='checked'";}} ?>  type="checkbox" value="all" >All<br/>
                         
                         <?php foreach($membership_group as $mem_grp) {?>
                         
                           <input  name="SelectedGroup[]"  id="SelectedGroup_<?php echo $mem_grp['ID']; ?>" Class="MemberSelectcheckBox" type="checkbox" value="<?php echo $mem_grp['ID'];?>" 
						   <?php if(is_array($_SESSION['SelectedGroup'])){if(in_array($mem_grp['ID'] , $_SESSION['SelectedGroup'])){ echo "checked='checked'";}} ?>>
                          
                           
						    <label for="SelectedGroup_<?php echo $mem_grp['ID']; ?>"><?php echo $mem_grp['Title'];?></label> <br/>
                            <?php } ?>
                      </div>
                    </td>
                   
                  </tr>

          </table></div>
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
    </td>
    <td align="right">
    <div style="width:200px;" >
	<a href="RevisionNotes.php?kid=6" class="hubopus_popup" id="addhubopususer"><img border="0" src="../../images/36_tags_script.png"  title="Revision Notes" /></a>
            <!--<a href="CitationsEdit.php" class="hubopus_popup" id="addhubopususer"><img src="../../images/36_new_member.png" border="0" title="Add New Citation" /></a>-->
     </div>
    </td>
  </tr>
</table>
</div>

<?php if($_REQUEST['Task']=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="../../images/accept.png"/>&nbsp;&nbsp;A Citation record has been deleted successfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<?php if($_REQUEST['flag']=='update'){?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="../../images/accept.png"/>&nbsp;&nbsp;A Citation record has been updated successfully!
	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
			
    </table>
	
	<?php if($_REQUEST['flag']=='error'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
   
  <td align="left" colspan="3" id="error-message">
      <img src="../../images/cancel.png"/>&nbsp;&nbsp;This email already exists in the Database
        <script>
		$(document).ready(function(){
  
        $("#error-message").fadeOut(3000);
  });
		</script>
</td>
  </tr>
     
    <?php }?>
</table>
	
<div class="subcontainer">
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr id="headerbar"  style="background-color:<?php echo "#".$SystemSettingsArray[0]['HeaderColor']; ?>">
  <td width="44" >ID</td>
  <td width="136" >Date Created</td>
   <td width="215">Business Name</td>
   <td width="132" >Name </td>
   
   <!--<td width="50" title="Site Ready Status">Status</td>-->
    <td colspan="2"><!--<input type="checkbox" id="SelectAll"  class="MemberSelectcheckBox" />-->
    Email</td>
    
    <!--<td width="144"><a href='<?=$_SERVER['PHP_SELF']?>?sortBy=Domain&sortValue=<?=$sortName?>'><img src="../../images/icon_sort_order.png" title="Sort Order" border="0"/></a>Domain</td>-->
    
    <td width="157" >Best Phone Number</td>

	
    <td width="14"  align ="right"><span style="cursor:pointer;" title="Appointment Setup">A</span></td>
    <td width="14"  align ="right"><span style="cursor:pointer;" title="Welcome Call">W</span></td>
    <td width="14"  align ="right"><span style="cursor:pointer;" title="Site Attached">S</span></td>
    <td width="14"  align ="right"><span style="cursor:pointer;" title="Design Completed">D</span></td>
    <td width="14"  align ="right"><span style="cursor:pointer;" title="Content Completed">C</span></td>
    <td width="14"  align ="right"><span style="cursor:pointer;" title="Site Accepted">A</span></td>
    <td width="14"  align ="right"><span style="cursor:pointer;" title="Revisions">R</span></td>
    <td width="46"  align ="right">Actions</td>
  </tr>
  
  	
       
  <?php  foreach((array)$HubFlxUserRows as $HubFlxUserRow){
  
            $hubflexmeb_array = $objmember->GetAllMemberWithHubFlexMenber(" HubFlxMember.MemberID = '".$HubFlxUserRow['ID']."'",array("HubFlxMember.*,Member.FirstName"));// print_r($hubflexmeb_array);
            $revision_array =  $objmember->GetAllRevision(" MemberID = '".$HubFlxUserRow['ID']."' AND Done = 0",array("count(*) as total"));
			  
			   
		 $undone_total = $revision_array[0]['total'];  
    		 if($undone_total > 0 ){
                    $revision_array =  $objmember->GetAllRevision(" MemberID = '".$HubFlxUserRow['ID']."'AND Done = 1",array("count(*) as total"));
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
   <td> <?php echo $HubFlxUserRow['ID'];?></td>
   <td><?php 
   //echo date("Y",strtotime($HubFlxUserRow['WelCallComDate']));
   echo date("<b>M d</b>, Y",strtotime( $HubFlxUserRow['Created']));?></td>
   <td><?php echo $HubFlxUserRow['CompanyName'];?></td>
   
     
          
          <td>
              <?php echo $HubFlxUserRow['FirstName']." ".$HubFlxUserRow['Surname'];?>          </td>
      
	<td width="98"><?php echo $HubFlxUserRow['Email'];?>        </td>
    <td width="86">    </td>
    <td><?php echo $HubFlxUserRow['Phone'];?></td>
	
	<td align ="right"><?php
            if($HubFlxUserRow['BestCallTime']=="0000-00-00 00:00:00" || $HubFlxUserRow['BestCallTime']=="NULL"){
                $BestCallTime ="";
            }else{
                $BestCallTime = date("M d, Y",strtotime($HubFlxUserRow['BestCallTime']));
            }	 
				
	 if($BestCallTime=="") {?>
	    <img title="<?php echo  $BestCallTime;?>" src="../../images/stop.png" />
        <?php } else if(date("Y-m-d",strtotime($HubFlxUserRow['BestCallTime']))<=date("Y-m-d")){?>
	  <img title="<?php echo  $BestCallTime;?>" src="../../images/start.png" />
	  <? } else if(date("Y-m-d",strtotime($HubFlxUserRow['BestCallTime']))>date("Y-m-d")) {?>
	  <img title="<?php echo  $BestCallTime;?>" src="../../images/icon_date.png" />
        <?php }?></td>
	<td align ="right"><?php if((int)date("Y",strtotime($HubFlxUserRow['WelCallComDate'])) > 1970) {?>
        <img src="../../images/start.png" title="<?php echo date("M d, Y",strtotime($HubFlxUserRow['WelCallComDate'])) ?>" />
        <?php } else {;?>
        <img src="../../images/stop.png" />
        <?php }?>    </td>
	<td align ="right"><?php if(isset($hubflexmeb_array)){?>
        <img src="../../images/start.png"  />
	  <?php } else {;?>
        <img src="../../images/stop.png" />
	  <?php }  ?>        </td>
	  
	<td align ="right"><?php if((int)date("Y",strtotime($HubFlxUserRow['DesignComplete'])) > 1970) {?>
        <img src="../../images/start.png" title="<?php echo date("M d, Y",strtotime($HubFlxUserRow['DesignComplete'])) ?>" />
        <?php } else {;?>
        <img src="../../images/stop.png" />
        <?php }  ?>    </td>
	<td align ="right">
            <?php if((int)date("Y",strtotime($HubFlxUserRow['ContentComplete'])) > 1970) {?>
            <img src="../../images/start.png" title="<?php echo date("M d, Y",strtotime($HubFlxUserRow['ContentComplete'])) ?>" />
            <?php } else {;?>
            <img src="../../images/stop.png" />
            <?php }  ?>        </td>
	<td align ="right"><?php if((int)date("Y",strtotime($HubFlxUserRow['SiteAcceptanceFinal'])) > 1970) {?>
      <img src="../../images/start.png" title="<?php echo date("M d, Y",strtotime($HubFlxUserRow['SiteAcceptanceFinal'])) ?>"/>
      <?php } else {;?>
      <img src="../../images/stop.png" />
      <?php }  ?></td>
	<td align ="right">
        <?php if($FlagRevision == "green") {?>
        <img src="../../images/start.png" /><?php } else {;?> <img src="../../images/stop.png" /><?php } ?>    </td>
	
    <td align ="right">
        <a class="hubopus_popup" href="CitationsEdit.php?id=<?php echo $HubFlxUserRow['ID'];?>"> <img src="../../images/icon_page_edit.png" border="0" title="Edit Details"/></a>
        <a href="?id=<?php echo $HubFlxUserRow['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../../images/icon_delete.png" border="0"/></a></td>
  </tr>
 <?php }?>
</table>
<div align="center">
<? include "lib/bottomnav.php" ?>
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

<? include "include/footer.php" ?>
