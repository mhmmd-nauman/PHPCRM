<?php 
 include "../../include/header.php";

$objWebSiteServer = new WebSiteServer();


if(isset($_REQUEST['SearchTextwebsite'])){
	$_SESSION['SearchTextwebsite'] = trim($_REQUEST['SearchTextwebsite']);
	
}
//(isset($_REQUEST['SelectedGroup']))?$_SESSION['SelectedGroup']=$_REQUEST['SelectedGroup']:$_SESSION['SelectedGroup']=array('all');
if(isset($_SESSION['SearchTextwebsite']) && $_SESSION['SearchTextwebsite'] != ''){
    $search = " (".WEBSITESERVER.".id = '".$_SESSION['SearchTextwebsite']."' OR ".WEBSITESERVER.".name LIKE '%".$_SESSION['SearchTextwebsite']."%' OR  ".WEBSITESERVER.".ip LIKE'%".$_SESSION['SearchTextwebsite']."%' OR  ".WEBSITESERVER.".internal_ip LIKE'%".$_SESSION['SearchTextwebsite']."%' OR  ".WEBSITESERVER.".username LIKE'%".$_SESSION['SearchTextwebsite']."%' )" ;
}
if(!empty($_SESSION['FromDate']) && !empty($_SESSION['ToDate'])){
	if(empty($search)){
		$search = " Date(created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	
	}else{
		$search .= " AND Date(created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	}
}



if(empty($search)){
	$search = " HasDeleted = 0 ";
}else{
	$search .= " AND HasDeleted = 0 ";
}
//echo $search;
$Websites = $objWebSiteServer->GetAllWebSiteServer( $search ,array("*"));

?>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />


<script type="text/javascript">

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
		 $(function() {
                    
                    // 
                    
                    $("#EditDomainList").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
                    $("#DomainAdd").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
					$(".editBtn").click(function(e) {
						e.preventDefault();	
                        modalbox2(this.href,this.title,550,800);
					});
                    <?php // foreach((array)$Websites as $Website){?>   
					 
                       $("#DomainEdit<?php echo $Website['ID'];?>").click(function(e){
                                     e.preventDefault();	
                                     modalbox2(this.href,this.title,550,800);
                         });
						  
                <?php // } ?>
             });
</script>

<div id="headtitle"> Server Accounts </div>

<div class="filtercontainer">

<table width="100%" border="0" cellspacing="0" cellpadding="0" >
  <tr>
    <td>
    <form name="HubUserSearchForm" id="HubUserSearchForm" action="?Task=SetFilter" method="post" enctype="multipart/form-data">
    
    <div class="adv_search">
  <div class="adv_search_sub">
    <div class="input_box">
      <input name="SearchTextwebsite" id="mainsearch" class="input_box_2" type="text" value="<?php echo $_SESSION['SearchTextwebsite'];?>" />
      <div id="show_options" class="aoo" tabindex="0" gh="sda" role="button" aria-label="Show search options" data-tooltip="Show search options">
        <div class="aoq"></div>
      </div>
    </div>
    <div class="adv_btn">
      <input name="Submit" type="submit" class="adv_btn_2" value="" style="margin-top:-3px;" />
    </div>
  </div>
  <div class="cate_main" id="cate_main" style="display:none;position:absolute;top:167px; z-index: 100000;">
    <div id="search_close" tabindex="0" role="button" class="Zy"></div>
    <div class="search_row"><br />&nbsp;&nbsp;Name/IP:<br />
                <input name="SearchTextwebsite" type="text" class="in_put2" id="changename" value="<?php echo $_SESSION['SearchTextwebsite'];?>" size="32"/>
                <!--<input type="text" name="SearchText" class="in_put2" value="<?php echo $_SESSION['SearchTextwebsite'];?>"/>-->
        </div>
	  <div  class="search_row in_put2" style="width:307px;">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" style="padding-left:8px;">
                  <tr>
                    <td><!--<input type="radio" name="SearchText"  value="daterange" <?php if($_SESSION['SearchTextwebsite']=='daterange')echo"checked";?> />-->
                      From<br />
                      <input type="text" id="fromdatepicker" name="FromDate" size="21" style="padding: 1px 4px;" value="<?php echo $_SESSION['FromDate'];?>" />                     </td>
					<td  width="10%">&nbsp;
                             
					</td>
                    <td  valign="bottom"> To:<br />
                       <input type="text" id="todatepicker" name="ToDate" size="21" style="padding: 1px 4px; " value="<?php echo $_SESSION['ToDate'];?>"/>                    </td>
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
    <td>
    <div align="right">
            <a href="WebSiteServerAdd.php"  id="DomainAdd" title="Add New Account"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"  value="Add New Account" id="Addnew"></a>
     </div>
  
    </td>
  </tr>
</table>
</div>


<?php if($_REQUEST['flag']=='add_server'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      A Record has been added successfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     </table>
    <?php }?>
    
<?php if($_REQUEST['Task']=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Record has been deleted successfully!
	   <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
    </table>  
    <?php }?>
  <?php if($_REQUEST['flag']=='access_denied'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
   
  <td align="left" colspan="3" id="message_error">
      Error from API!<br>Access Denied.
        <script>
		$(document).ready(function(){
  
        $("#message_error").fadeOut(3000);
  });
		</script>
</td>
  </tr>
      </table>
    <?php }?>
  
   
<?php if($_REQUEST['flag']=='update'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Record has been updated successfully!
	   <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
      </table>
    <?php }?>
   

<div class="subcontainer">
<div class="" style="margin:0px 0px 20px 0px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Website_List" style="display:none;">
<thead>
  <tr id="headerbar" style=" height:46px;">
  <td>ID</td>
  <td> Created </td>
  <td>Name</td>
   <td>IP</td>
    <td>User</td>
    <td>Password</td>
    <td>Type</td>
    <td>Is Default</td>
    <td class="Action">Actions</td>
  </tr>
  </thead>
  <tbody>
 <?php foreach((array)$Websites as $Website){
                if($flag==0){
                    $flag=1;
                    $row_class="row-white";
                }else{
                    $flag=0;
                    $row_class = "row-tan";
                }
            
    
  ?>
  
 

  <tr id="<?php echo $row_class;?>" >
  <td>
     <?php  echo $Website['id'];?> 
  </td>
  <td >
  <?php echo date("<b>M d</b>, Y",strtotime( $Website['created']));?>  </td>
    <td><?php  echo $Website['name'];?></td>
    <td><?php  echo $Website['ip']."<br>&nbsp;".$Website['internal_ip'];?></td>
    <td><?php  echo $Website['username'];?></td>
     <td><?php  echo $Website['password'];?></td>
    <td align="left">
	<?php switch($Website['isreseller']){
		case 1:
   	?>
	Reseller
	<?php
		break;
		case 0:
	?>
	Root
	<?php
		break;
	 }
	 ?>   
    </td>
    <td>
        <?php switch($Website['isDefaultReseller']){
		case 1:
   	?>
	Y
	<?php
		break;
		case 0:
	?>
	N
	<?php
		break;
	 }
	 ?> 
    </td>
    <td class="Action">
        <a class="editBtn" id="DomainEdit<?php echo $Website['ID'];?>" href="WebSiteServerAdd.php?id=<?php echo $Website['id'];?>&Task=Update" title="Edit <?php echo $Website['UserName'];?>"> <img src="../../images/icon_page_edit.png" border="0" title="Edit Hubopus Details"/></a>
        <a href="?id=<?php echo $Website['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../../images/icon_delete.png" border="0"/></a>
    </td>
  </tr>
 
 <?php }?>
 </td>
</table>
</div>

<div align="center">
<? //include "../../lib/bottomnav.php" ?>
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
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />
<script type="text/javascript">
$(document).ready(function(){
	var oTable = $('#Website_List').dataTable({
		"iDisplayLength": 10,	
	});
	oTable.fnSort( [ [0,'desc']] );

});
$(window).load(function(){
	$('#Website_List').show();
});
</script>
