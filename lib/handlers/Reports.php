<?php 

include "include/header.php";
$objmember = new Member();
$ObjGroup = new Groups();
$objHubFlxMembers = new HubFlxMember();
 $HubopususerRows = $objHubFlxMembers->GetAllHubFlxMember(" 1",array("*"));
 print_r($HubopususerRows);
exit;
if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
}
if(isset($_REQUEST['SearchTextwebsite'])){
	$_SESSION['SearchTextwebsite'] = trim($_REQUEST['SearchTextwebsite']);
	
}
(isset($_REQUEST['SelectedGroup']))?$_SESSION['SelectedGroup']=$_REQUEST['SelectedGroup']:$_SESSION['SelectedGroup']=array('all');

if($_SESSION['page'] > 0 && !isset($_REQUEST['page'])){
  $page = $_SESSION['page'] ;
  $_SESSION['page'] = "";
  unset($_SESSION['page']);

}elseif(!isset($_REQUEST['page'])) {
  $page=1;
  $_SESSION['page'] = 1 ;
} else {
  $page=$_REQUEST['page'];
  $_SESSION['page'] = $page; 
}

if(isset($_SESSION['SearchTextwebsite']) && $_SESSION['SearchTextwebsite'] != ''){

  $search = " (".HUBFLXMEMBERS.".ID = '".$_SESSION['SearchTextwebsite']."' OR HubFlxMember.DomainName LIKE '%".$_SESSION['SearchTextwebsite']."%' OR  HubFlxMember.UserName LIKE'%".$_SESSION['SearchTextwebsite']."%' OR  Member.CompanyName LIKE'%".$_SESSION['SearchTextwebsite']."%' OR  Member.FirstName LIKE'%".$_SESSION['SearchTextwebsite']."%' OR  Member.Surname LIKE'%".$_SESSION['SearchTextwebsite']."%' )" ;

}else{
//$search="1";


}

if(!empty($_SESSION['FromDate']) && !empty($_SESSION['ToDate'])){
	if(empty($search)){
		$search = " Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	
	}else{
		$search .= " AND Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	}
}

//

switch($_SESSION['SiteStatus']){

		case "available":
		if(!empty($search)){$search .=" AND ";}
		$search .= " Status = 4";
		break;
	
		case "assigned":
		if(!empty($search)){$search .=" AND ";}
		$search .= " Status = 1 ";
		break;
	
		case "canceled":
		if(!empty($search)){$search .=" AND ";}
		$search .= " Status = 2 ";
		break;
	
		/*case "member_canceled":
		if(!empty($search)){$search .=" AND ";}
		$search .= " Status = 3 ";
		break;*/
	
		case "Approved":
		if(!empty($search)){$search .=" AND ";}
		$search .= "Status=5";
		break;
	
		case "Approval Awaited":
		if(!empty($search)){$search .=" AND ";}
		$search .= " Status = 6 ";
		break;
	
		case "Inprocess":
		if(!empty($search)){$search .=" AND ";}
		$search .= " Status = 7 ";
		break;
	
		case"notready":
		//Ready
		if(!empty($search)){$search .=" AND ";}
		$search .= " Status = 3 ";
		break;
	
		case"notlive":
		//Ready
		if(!empty($search)){$search .=" AND ";}
		$search .= " IsWebsiteActive = 0 AND Ready = 1";
		break;
	
		case"notsync":
		if(!empty($search)){$search .=" AND ";}
		$search .= " MemberID = 0 AND Status = 1";
		break;
	
		case"hosting_change_request_sent":
		if(!empty($search)){$search .=" AND ";}
		$search .= " EmailedServerChang = 1 AND HostedServer = 1";
		break;
		
		
		case"hostgator":
		if(!empty($search)){$search .=" AND ";}
		$search .= " HostedOn = 'hostgator' ";
		break;
	
        case"noserver":
		if(!empty($search)){$search .=" AND ";}
		$search .= " HostedOn = 'noserver'";
		break;
	
		case"codero":
		if(!empty($search)){$search .=" AND ";}
		$search .= " HostedOn = 'codero'";
		break;

        
}

switch($_SESSION['SiteStatus1']){

case"hostgator":
		if(!empty($search)){$search .=" AND ";}
		$search .= "HostedOn = 'hostgator' ";
		break;
	
        case"noserver":
		if(!empty($search)){$search .=" AND ";}
		$search .= "HostedOn = 'noserver'";
		break;
	
		case"codero":
		if(!empty($search)){$search .=" AND ";}
		$search .= "HostedOn = 'codero'";
		break;
}
//if(!empty($search)){$search .=" AND ";}
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
    (!empty($search))?$search .=" AND ":"";
    $search_group_ids = substr($search_group_ids, 0, strlen($search_group_ids) - 1);
    $search .=" GroupID in ($search_group_ids)";
}

if(empty($search)){$search= "HubFlxMember.HasDeleted = 0 ";}else{$search.=" AND ".HubFlxMember.".HasDeleted = 0 ";}
if($skip_group == 1){

	$info_arrayTotal = $objHubFlxMembers->GetAllHubFlxMember( $search ,array("count(*) as Total"));
}else{
	$info_arrayTotal = $objHubFlxMembers->GetAllHubFlxMemberWithGroup( $search ,array("count(".HubFlxMember.".ID) as Total"));
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

$ret = $objHubFlxMembers->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}
//echo $search;
if($skip_group == 1){
	$HubFlxUserRows = $objHubFlxMembers->GetAllHubFlxMember(" $search $sortText LIMIT $offset,$limit ",array("HubFlxMember.*"));
}else{
	$HubFlxUserRows = $objHubFlxMembers->GetAllHubFlxMemberWithGroup(" $search $sortText LIMIT $offset,$limit ",array(HubFlxMember.".*"));
	
	
}
//print_r($HubFlxUserRows);


?>



<script type="text/javascript" src="js/search.js"></script>
<link rel="stylesheet" type="text/css" href="css/search.css" />
<link href="<?php echo SITE_ADDRESS;?>css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
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
 $("#mailsend").fancybox({
        'width'                       : 820,
        'height'                      : 520,
        'autoScale'                   : false,
        'transitionIn'                : 'none',
        'transitionOut'               : 'none',
        'href'                        : this.href,
        'type'                        : 'iframe'
    });
});
function create_confirmation(){
    var answer = confirm("Do you want to create this website on server?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
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
<div id="headtitle">Websites </div>

<div class="filtercontainer">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="HubUserSearchForm" id="HubUserSearchForm" action="?Task=SetFilter" method="post" enctype="multipart/form-data">
    
    <div class="adv_search">
  <div class="adv_search_sub">
    <div class="input_box">
      <input name="SearchTextwebsite" id="mainsearch" class="input_box_2" type="text" value="<?php echo $_SESSION['SearchTextwebsite'];?>" />
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
                <input name="SearchTextwebsite" type="text" class="in_put2" id="changename" value="<?php echo $_SESSION['SearchTextwebsite'];?>" size="30" maxlength="30"/>
                <!--<input type="text" name="SearchText" class="in_put2" value="<?php echo $_SESSION['SearchTextwebsite'];?>"/>-->
        </div>
	  <div  class="search_row in_put2" style="width:307px;">
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
                  <tr>
                    <td width="34%"><!--<input type="radio" name="SearchText"  value="daterange" <?php if($_SESSION['SearchTextwebsite']=='daterange')echo"checked";?> />-->
                      From<br />
                      <input type="text" id="fromdatepicker" name="FromDate" size="8" style="padding: 1px 4px; width: 130px;" value="<?php echo $_SESSION['FromDate'];?>" />                     </td>
					
                    <td width="66%" valign="bottom"> To:<br />
                       <input type="text" id="todatepicker" name="ToDate" size="8" style="padding: 1px 4px; width: 130px;" value="<?php echo $_SESSION['ToDate'];?>"/>                    </td>
                  </tr>
				  
				  
				  
				  <tr>
				    <td>
                      <p>Status:<br />
					    <select name="SiteStatus">
					      <option value="all"  <?php if($_SESSION['SiteStatus'] == "all" )echo "selected";?>>All</option>
					      <option value="available" <?php if($_SESSION['SiteStatus'] == "available" )echo "selected";?>>Available</option>
					      <option value="notready" <?php if($_SESSION['SiteStatus'] == "notready" )echo "selected";?>>Not Ready</option>
					      <option value="assigned" <?php if($_SESSION['SiteStatus'] == "assigned" )echo "selected";?>>Assigned</option>
					      
					       <option value="canceled" <?php if($_SESSION['SiteStatus'] == "canceled" )echo "selected";?>>Website Canceled</option>					
					            
			            </select>
                      </p>
                      <p>Hosted On:<br />
                        <select name="SiteStatus1">
                        <option value="all"  <?php if($_SESSION['SiteStatus1'] == "all" )echo "selected";?>>All</option>
                        <option value="noserver" <?php if($_SESSION['SiteStatus1'] == "noserver" )echo "selected";?>>No Server</option>
                        <option value="codero" <?php if($_SESSION['SiteStatus1'] == "codero" )echo "selected";?>>Codero.Com</option>
                        <option value="hostgator" <?php if($_SESSION['SiteStatus1'] == "hostgator" )echo "selected";?>>HostGator.Com</option>
                        </select>
                      </p></td>
					  
				    <td valign="bottom">Groups:
			          <div style="margin-left:4px; height:87px;width:190px;overflow:auto;border:1px solid #CCCCCC; margin-top:4px;" id="ScrollCB">
                      <?PHP $membership_group = $ObjGroup->GetAllGroups("1 ORDER BY Title, ID ",array("*"));?>
                      <input  name="SelectedGroup[]"  id="SelectAll" <?php if(is_array($_SESSION['SelectedGroup'])){if(in_array('all' , $_SESSION['SelectedGroup'])){ echo "checked='checked'";}} ?>  type="checkbox" value="all" >
			          All<br/>
                      <?php foreach($membership_group as $mem_grp) {?>
                      <input  name="SelectedGroup[]"  id="SelectedGroup_<?php echo $mem_grp['ID']; ?>" Class="MemberSelectcheckBox" type="checkbox" value="<?php echo $mem_grp['ID'];?>" 
						   <?php if(is_array($_SESSION['SelectedGroup'])){if(in_array($mem_grp['ID'] , $_SESSION['SelectedGroup'])){ echo "checked='checked'";}} ?>>
                      <label for="SelectedGroup_<?php echo $mem_grp['ID']; ?>"><?php echo $mem_grp['Title'];?></label>
                      <br/>
                      <?php } ?>
		            </div>			        </td>  
					  
					
				  </tr>
				 
				
				  
          </table>
</div>
	  <div style="margin-bottom:5px;">
	  <input type="submit" name="Submit" class="adv_btn_2" style="float:right; margin-right: 25px;" value="" align="absmiddle" border="0" />
	  <div style="clear:both;"></div>
	</div>  
	
  </div>
  </div>
     
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
    <div style="text-align:width;">
     <!--<a href="download.php">Download</a>-->
            <a href="HubFlxMembersAdd.php" class="hubopus_popup" id="addhubopususer"><img src="images/36_new_member.png" border="0" title="Add More Temp Domains" /></a>
     </div>
  <div style="clear:both;"></div>
    </td>
  </tr>
</table>
</div>


<?php if($_REQUEST['flag']=='add_hubflex_member'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;A  Website has been added successfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<?php if($_REQUEST['Task']=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;Website record has been deleded successfully!
	   <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<?php if($_REQUEST['Task']=='update'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;Website record has been updated successfully!
	   <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>

<div class="subcontainer">
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr id="headerbar">
  <td width="100">Date Created </td>
   <td width="140" >Business Name </td>
   <td width="140" >Name </td>
    <td width="96" align="center" title="Member Membership Status">Status</td>
    <td >Domain</td>
    <td width="74">User</td>
    <td width="169" >Ftp/Cp Password</td>
    <td width="124" >Hosted On</td>
    <td width="20" >Notes</td>
    <td width="100" align="center">Actions</td>
  </tr>
 <?php foreach((array)$HubFlxUserRows as $HubFlxUserRow){
 
	  if( $HubFlxUserRow['MemberID'] > 0){
	  
	  		if( $HubFlxUserRow['MemberID'] > 0)
	  		
			
			$Member_row=$objmember->GetAllMember("ID='".$HubFlxUserRow['MemberID']."'", array("*"));
			//echo($HubFlxUserRow['MemberID']." <br>");
			$HubFlxUserRow['MemberName']=$Member_row[0]['FirstName']." ".$Member_row[0]['Surname'];
			$HubFlxUserRow['CustomerEmail'] = $Member_row[0]['Email'];
			//$HubFlxUserRow['Status']=$Member_row[0]['Status'];
			$Member_row[0]['FirstName']="";
			$Member_row[0]['Surname']="";
			//$Member_row[0]['CompanyName']="";
			$Member_row[0]['Status']="";
                        
			
		  
		  	  
	   	 $hubflexmeb_array = $objmember->GetAllMemberWithHubFlexMenber(" HubFlxMember.MemberID = '".$HubFlxUserRow['MemberID']."'",array("HubFlxMember.*,Member.FirstName"));// print_r($hubflexmeb_array);
                $revision_array =  $objmember->GetAllRevision(" MemberID = '".$HubFlxUserRow['MemberID']."' AND Done = 0",array("count(*) as total"));
			  
			   
		$undone_total = $revision_array[0]['total'];  
    		 if($undone_total > 0 ){
                    $revision_array =  $objmember->GetAllRevision(" MemberID = '".$HubFlxUserRow['MemberID']."'",array("count(*) as total"));
                    $_total = $revision_array[0]['total'];
                    if($_total > 0){
                        $FlagRevision="green";
                    }else{
                        $FlagRevision="red";
                    }
		  }else{
                    $FlagRevision="green";
		 }
			 
			 if((int)date("Y",strtotime($Member_row[0]['ContentComplete'])) > 1970) 
			 {
			  $FlagContentComplete="green";
			 }
			 else
			 {
			  $FlagContentComplete="red";
			 }
			   
			 if((int)date("Y",strtotime($Member_row[0]['ContentReceived'])) > 1970) 
			 {
			  $FlagContentReceived="green";
			 }
			 else
			 {
			  $FlagContentReceived="red";
			 }
			 
			 if((int)date("Y",strtotime($Member_row[0]['WelCallComDate'])) > 1970)
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
				
			if($HubFlxUserRow['Status']==5)
			{
				$FlagStatus="green";
			}
			else
			{
				$FlagStatus="red";
			}
			
			if(date("Y",strtotime($Member_row[0]['BestCallTime']))==date("Y")&&date("m",strtotime($Member_row[0]['BestCallTime']))==date("m")&&date("d",strtotime($Member_row[0]['BestCallTime']))<date("d"))
			{
				$FlagBestCallTime="green";
			}
			else
			{
				$FlagBestCallTime="red";
			}
			 
			 
			 
	  
	   if($FlagStatus=="green")
	   {
	   
                $row_class = "row-green";
				
	   }
	   
	   elseif((int)date("Y",strtotime($HubFlxUserRow['WelCallComDate'])) > 1970){
	  	  $row_class = "row-yellow";
		
	   }
	   
	   else{
	  	 $row_class = "row-red";
		 
	   } 
	   
  }  else{
      if($flag==0){
        $flag=1;
        $row_class="row-white";
        }else{
        $flag=0;
        $row_class = "row-tan";
        }
  }       
  ?>
  
 

  <tr id="<?php echo $row_class;?>" >
  <td >
  <?php echo date("<b>M d</b>, Y",strtotime( $HubFlxUserRow['SaleDate']));?>  </td>
    <td><?php if($HubFlxUserRow['MemberID'] > 0){echo $Member_row[0]['CompanyName'];} else{echo $Member_row[0]['CompanyName']=""; echo $$Member_row['ID'];}?></td>
    <td><?php  echo $HubFlxUserRow['MemberName'];?></td>
    <td align="center">
	<?php switch($HubFlxUserRow['Status']){
		case 1:
   	?>
	<img src="images/icon_24assigncoach.png" title="Assigned" />
	<?php
		break;
		case 2:
	?>
	<img src="images/icon_cancel.png" title="Canceled" />
	<?php
		break;
		case 3:
	?>
	<img src="images/icon_24Warning.png" title="Not Ready" />
	<?php
		break;
		case 4:
                    if( $HubFlxUserRow['MemberID'] == 0){
	?>
            <img src="images/icon_tick.png" title="Available" />
	
                    <?php }else {?>
            <img  src="images/icon_24assigncoach.png" title="Assigned" />
                    
    <?php
			}                    
		break;
		case 5:
		
	 ?>	 
	 	 
	 <img  src="images/icon_24assigncoach.png" title="Domain Assigned" />
	 <?php
	 break;
	 }
	 ?>
   </td>
   <td><a href="http://<?php echo $HubFlxUserRow['DomainName'];?>/admin" target="_blank"><?php echo $HubFlxUserRow['DomainName'];?></a></td>
    <td><?php echo $HubFlxUserRow['UserName'];?></td>
    <td>
        
        <?php echo $HubFlxUserRow['UserPassword'];?>    </td>
     <td>
        
       
         <?php if($HubFlxUserRow['HostedOn']=='hostgator'){echo"HostGator.Com";}elseif($HubFlxUserRow['HostedOn']=='noserver'){echo"No Server";}elseif($HubFlxUserRow['HostedOn']=='codero'){echo"Codero.Com";}?>    </td>
   <td><?php $revision_array = $objmember->GetAllMemberWithRevision(" Revision.MemberID = '".$HubFlxUserRow['MemberID']."'",array("Revision.*,Member.FirstName")); if(!empty( $revision_array)) {?>  <img src="images/note.png" />     <?php }; ?>    </td>
      
    <td align="left">
        <a class="hubopus_popup" href="HubFlxMembersEdit.php?id=<?php echo $HubFlxUserRow['ID'];?>&Task=Update"> <img src="images/icon_page_edit.png" border="0" title="Edit Hubopus Details"/></a>&nbsp;&nbsp;&nbsp;&nbsp; 
        <a href="HubFlxMembers.php?id=<?php echo $HubFlxUserRow['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="images/icon_delete.png" border="0"/></a>&nbsp;&nbsp;&nbsp;&nbsp; <?php if(($HubFlxUserRow['Status']==3)){?>
        <a href="HubFlxMembers.php?id=<?php echo $HubFlxUserRow['ID'];?>&Task=CreateCpanel" target="_blank" onclick="return create_confirmation();"> <img  src="images/cpanel.png" border="0" title="Create Cpanel Account"/> </a><?php }?>    </td>
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
<?php include "include/footer.php" ?>
