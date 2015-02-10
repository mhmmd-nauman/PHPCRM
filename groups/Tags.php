<?php 
 include "../include/header.php";
$objClient = new Clients();
$objWebsites = new WebSites();
$objtags = new Tags();

if($_REQUEST['Task']=='del'){
$objtags->DeleteTag($_REQUEST['id']);
}
if(isset($_REQUEST['SearchTextwebsite'])){
	$_SESSION['SearchTextwebsite'] = trim($_REQUEST['SearchTextwebsite']);
	
}
(isset($_REQUEST['SelectedGroup']))?$_SESSION['SelectedGroup']=$_REQUEST['SelectedGroup']:$_SESSION['SelectedGroup']=array('all');
if(isset($_SESSION['SearchTextwebsite']) && $_SESSION['SearchTextwebsite'] != ''){
    $search = " (".WEBSITES.".ID = '".$_SESSION['SearchTextwebsite']."' OR ".WEBSITES.".DomainName LIKE '%".$_SESSION['SearchTextwebsite']."%' OR  ".WEBSITES.".UserName LIKE'%".$_SESSION['SearchTextwebsite']."%' OR  ".CLIENTS.".CompanyName LIKE'%".$_SESSION['SearchTextwebsite']."%' OR  ".CLIENTS.".FirstName LIKE'%".$_SESSION['SearchTextwebsite']."%' OR  ".CLIENTS.".Surname LIKE'%".$_SESSION['SearchTextwebsite']."%' )" ;
}
if(!empty($_SESSION['FromDate']) && !empty($_SESSION['ToDate'])){
	if(empty($search)){
		$search = " Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	
	}else{
		$search .= " AND Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	}
}

switch($_SESSION['SiteStatus']){

        case "available":
            if(!empty($search)){$search .=" AND ";}
            $search .= WEBSITES.".Status = 4";
        break;
	case "assigned":
            if(!empty($search)){$search .=" AND ";}
            $search .= WEBSITES.".Status = 1 ";
        break;
	case"notready":
            if(!empty($search)){$search .=" AND ";}
            $search .= WEBSITES.".Status = 3 ";
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


if(empty($search)){$search= "Websites.HasDeleted = 0 ";}else{$search.=" AND ".WEBSITES.".HasDeleted = 0 ";}
$Websites = $objWebsites->GetAllWebsites(" $search ",array(WEBSITES.".*"));
$tagsarray=$objtags->GetAllTags("1 ORDER BY Sorting ASC",array("*"));
?>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<script type="text/javascript">
function updateOrder(){
	$('#Website_List tbody tr').each(function(){
		var id = $(this).attr("id");
		id=id.substring(4, id.length);
		//console.log(id);
		var params = $("#tagpage").serialize();
			$.ajax({
				url: '<?php echo SITE_ADDRESS;?>groups/updateTags.php?'+params,
				success:function(data){
					console.log(data);	
				}
			});
	});
}
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
                    
                 
                    $("#DomainAdd").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
                    <?php  foreach((array)$tagsarray as $tag){?>        
                       $("#TagEdit<?php echo $tag['ID'];?>").click(function(e){
                                     e.preventDefault();	
                                     modalbox(this.href,this.title,550,800);
                         });	 
                <?php } ?>
             });
</script>

<div id="headtitle">Tags</div>

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
    <div class="search_row"><br />&nbsp;&nbsp;Name/Email:<br />
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
				  
				  
				  
				  <tr>
				    <td>
                      <p>Status:<br />
					    <select name="SiteStatus" class="product">
					      <option value="all"  <?php if($_SESSION['SiteStatus'] == "all" )echo "selected";?>>All</option>
					      <option value="available" <?php if($_SESSION['SiteStatus'] == "available" )echo "selected";?>>Available</option>
					      <option value="notready" <?php if($_SESSION['SiteStatus'] == "notready" )echo "selected";?>>Not Ready</option>
					      <option value="assigned" <?php if($_SESSION['SiteStatus'] == "assigned" )echo "selected";?>>Assigned</option>
					      
					       <option value="canceled" <?php if($_SESSION['SiteStatus'] == "canceled" )echo "selected";?>>Website Canceled</option>					
					            
			            </select>
                      </p>
                     </td>
					 <td  width="5%">&nbsp;
                             
					</td>
				    <td valign="bottom">
					 <p>Hosted On:<br />
                        <select name="SiteStatus1" class="product">
                        <option value="all"  <?php if($_SESSION['SiteStatus1'] == "all" )echo "selected";?>>All</option>
                        <option value="noserver" <?php if($_SESSION['SiteStatus1'] == "noserver" )echo "selected";?>>No Server</option>
                        <option value="codero" <?php if($_SESSION['SiteStatus1'] == "codero" )echo "selected";?>>Codero.Com</option>
                        <option value="hostgator" <?php if($_SESSION['SiteStatus1'] == "hostgator" )echo "selected";?>>HostGator.Com</option>
                        </select>
                      </p>
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
     <!--<a href="download.php">Download</a>-->
            <a href="TagEdit.php"  id="DomainAdd" title="Add New Tag"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"  value="Add New Tag" id="Addnew"></a>
     </div>
  
    </td>
  </tr>
</table>
</div>


<?php if($_REQUEST['flag']=='add'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      A Tag has been added successfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<?php if($_REQUEST['Task']=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Tag record has been deleted successfully!
	    <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>
<?php if($_REQUEST['Task']=='update'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Tag record has been updated successfully!
	    <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
    </table>

<div class="subcontainer">
<div class="" style="margin:0px 0px 20px 0px;">
<form name="tagpage" id="tagpage" action="#" method="get">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Website_List">
<thead>
  <tr id="headerbar" style=" height:46px;">
  <td>Sort</td>
  <td>ID</td>
  
  <td> Created </td>
   <td>Title</td>
   <td>	Tag Description</td>
    <td title= Status>InActive</td>
    <td class="Action">Actions</td>
  </tr>
  </thead>
  <tbody>
 <?php foreach((array)$tagsarray as $tag){
            if(empty($tag)){} 
			
			else{
                if($flag==0){
                    $flag=1;
                    $row_class="row-white";
                }else{
                    $flag=0;
                    $row_class = "row-tan";
                }
            }
    
  ?>
  
 

  <tr class="<?php echo $row_class;?>" id="tag_<?php  echo $tag['ID'];?>">
  <td><input type="hidden" name="sort_<?php  echo $tag['ID'];?>" value="<?php echo $tag['Sorting']; ?>" /><div class="sort"><?php echo $tag['Sorting']; ?></div></td>
  <td><span class="ui-icon ui-icon-arrowthick-2-n-s" style="display: inline-block"></span><?php  echo $tag['ID'];?></td> 
  <td><?php echo date("<b>M d</b>, Y",strtotime( $tag['Created']));?>  </td>
  <td><?php echo $tag['Title'];?></td>
  <td><?php echo $tag['TagDescription'];?></td> 
  <td><?php echo $tag['InActive'];?></td>
  <td class="Action"> <a id="TagEdit<?php echo $tag['ID'];?>" href="TagEdit.php?id=<?php echo $tag['ID'];?>&Task=Update" title="Edit <?php echo $Website['UserName'];?>"> <img src="../images/icon_page_edit.png" border="0" title="Edit Hubopus Details"/></a>
        <a href="Tags.php?id=<?php echo $tag['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../images/icon_delete.png" border="0"/></a></td>
  </tr>
 <?php }?>

 </tbody>
</table>
<br />
<a href="javascript:updateOrder();" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="padding: 10px">Save Tag Order</a>
</form>
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

/*$(document).ready(function(){
		var arr = adata = bdata = new Array();
		var rowCount = $('#Website_List tbody tr').length;
		$('#row_count').val(rowCount);
		var i = j = 0;
		var final_arr =  '';
		$('#Website_List').dataTable({
			"bPaginate": false,
			"bFilter": false,
			"bInfo": false,
			"bLengthChange": false,
			"aoColumnDefs": [
            { "bVisible": false, "aTargets": [0] }
        	],
			"fnRowCallback": function( nRow, aData, iDisplayIndex) {			
				i++;
				adata = aData[0];
				bdata = aData[2];
				arr.push(adata+'=>'+bdata);
				final_arr += adata+'=>'+bdata+',';
				console.log(final_arr);
				
				if(i == rowCount){
					$.ajax({
						type: "GET",
						async :false,
						url : 'SortProducts.php?Task=SortProducts&data='+final_arr,
					});
				j++;
				i = 0;
				}
				
			}
		}).rowReordering().disableSelection();	
		j = 0;
		final_arr = '';
	});
	*/
$(document).ready(function(){
	/*
	var oTable = $('#Website_List').dataTable({
		"iDisplayLength": 20,	
	});
	oTable.fnSort( [ [0,'desc']] );
	*/
	
	var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};

$("#Website_List tbody").sortable({
    helper: fixHelper,
	update: function( event, ui ) {
		$("#Website_List tbody tr").each(function(index, elem) {
			if(index%2==0){
				$(this).removeClass().addClass("row-white");	
			} else {
				$(this).removeClass().addClass("row-tan");
			}
			var temp = index+1;
			temp*=10;
			$(this).find("div.sort").text(temp);
			$(this).find("input").val(temp);
			
		});
	}
}).disableSelection();
});
$(window).load(function(){
	$('#Website_List').show();
});



</script>
