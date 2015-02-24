<?php 
 include "../../include/header.php";
$objClient = new Clients();
$objSubDomains = new SubDomains();


if(isset($_REQUEST['SearchTextwebsite'])){
	$_SESSION['SearchTextwebsite'] = trim($_REQUEST['SearchTextwebsite']);
	
}
(isset($_REQUEST['SelectedGroup']))?$_SESSION['SelectedGroup']=$_REQUEST['SelectedGroup']:$_SESSION['SelectedGroup']=array('all');
if(isset($_SESSION['SearchTextwebsite']) && $_SESSION['SearchTextwebsite'] != ''){
    $search = " (".WEBSITES.".ID = '".$_SESSION['SearchTextwebsite']."' OR ".WEBSITES.".SubDomain LIKE '%".$_SESSION['SearchTextwebsite']."%'     )" ;
}
if(!empty($_SESSION['FromDate']) && !empty($_SESSION['ToDate'])){
	if(empty($search)){
		$search = " Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	
	}else{
		$search .= " AND Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	}
}

switch($_SESSION['SiteStatus']){

        case "assigned":
            if(!empty($search)){$search .=" AND ";}
            //$search .= WEBSITES.".Status = 4";
        break;
	case "available":
            if(!empty($search)){$search .=" AND ";}
            //$search .= WEBSITES.".Status = 1 ";
        break;
	case"notready":
            if(!empty($search)){$search .=" AND ";}
            //$search .= WEBSITES.".Status = 3 ";
        break;
	case"canceled":
            if(!empty($search)){$search .=" AND ";}
            //$search .= WEBSITES.".Status = 2 ";
        break;
        case"domainassigned":
            if(!empty($search)){$search .=" AND ";}
            //$search .= WEBSITES.".Status = 5 ";
        break;
	

        
}

switch($_SESSION['SiteStatus1']){

        case"ezbhost1":
		if(!empty($search)){$search .=" AND ";}
		//$search .= "HostedOn = 'ezbhost1' ";
	break;
	
        case"noserver":
		if(!empty($search)){$search .=" AND ";}
		//$search .= "HostedOn = 'noserver'";
	break;
	
	case"ezbhost2":
		if(!empty($search)){$search .=" AND ";}
		//$search .= "HostedOn = 'ezbhost2'";
	break;
        case"ezbhost3":
		if(!empty($search)){$search .=" AND ";}
		//$search .= "HostedOn = 'ezbhost3'";
	break;
}

if(empty($search)){
    $search = " 1 ";
}
//echo $search;
$Websites = $objSubDomains->GetAllSubDomains(" $search ",array(SubDomains.".*"));

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
					
                    <?php  foreach((array)$Websites as $Website){?>   
					 
                       $("#DomainEdit<?php echo $Website['ID'];?>").click(function(e){
                                     e.preventDefault();	
                                     modalbox2(this.href,this.title,550,800);
                         });
						  
                <?php  } ?>
             });
</script>

<div id="headtitle">Sub Domain </div>

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
					      <option value="domainassigned" <?php if($_SESSION['SiteStatus'] == "domainassigned" )echo "selected";?>>Domain Assigned</option>
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
                        <option value="ezbhost1" <?php if($_SESSION['SiteStatus1'] == "ezbhost1" )echo "selected";?>>Ezbhost1</option>
                        <option value="ezbhost2" <?php if($_SESSION['SiteStatus1'] == "ezbhost2" )echo "selected";?>>Ezbhost2</option>
                        <option value="ezbhost3" <?php if($_SESSION['SiteStatus1'] == "ezbhost3" )echo "selected";?>>Ezbhost3</option>
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
        <a href="?Task=AddSubdomains"  title="Add Sub Domains"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"  value="Add Sub Domains"></a>
     </div>
  
    </td>
  </tr>
</table>
</div>

<?php if($_REQUEST['flag']=='empty_real_domain'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="left" colspan="3" id="message_error">
            Empty Real Sub Domain Name! Please fill using Edit Box!
              <script>
                      $(document).ready(function(){

                        $("#message_error").fadeOut(10000);
                        });
              </script>
        </td>
        </tr>
      </table>
    <?php }?>
<?php if($_REQUEST['flag']=='add_hubflex_member'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      A web site has been added successfully!
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
      Web site record has been deleted successfully!
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
  
   
<?php if($_REQUEST['Task']=='update'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Web site record has been updated successfully!
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
   <td>Business  </td>
   <td>Contact </td>
    <td title= "Status">Status</td>
    <td >Temp</td>
    <!--<td>Agent Name</td>-->
    <td>Customer Subdomain<br>Vanity Domain</td>
    <td>Password</td>
    <td>Hosted On</td>
    <td>Notes</td>
    <td class="Action">Actions</td>
  </tr>
  </thead>
  <tbody>
 <?php foreach((array)$Websites as $Website){
        
        if( $Website['ClientID'] > 0){
		# Add the agent name or Submitted by name -> Uncomment the line below and add a new column as Agent name
		//$AgentDetails = $objClient->FetchAgentName(trim($Website['SubmitedBy']));
		//bc8mzP7Y CFqBlvcn
		$Member_row = $objClient->GetAllClients(CLIENTS.".ID='".$Website['ClientID']."'", array("*"));
		$Website['MemberName'] = $Member_row[0]['FirstName']." ".$Member_row[0]['Surname'];
		$Website['CustomerEmail'] = $Member_row[0]['Email'];
		$Member_row[0]['FirstName']="";
		$Member_row[0]['Surname']="";
		$Member_row[0]['Status']="";
                //$websites_array = $objClient->GetAllClientsWithWebsites(WEBSITES.".MemberID = '".$Website['MemberID']	."'",array(WEBSITES.".*,".CLIENTS.".FirstName"));
		if(isset($websites_array)){
                    $FlagSite="green";
                }else{
                    $FlagSite="red";
                }
		
                if(  $FlagSite == "green"){
                    $row_class = "row-green";
                }else{
                    $row_class = "row-red";
                }   
  
            } else{
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
  <td>
     <?php  echo $Website['ID'];?> 
  </td>
  <td >
  <?php echo date("<b>M d</b>, Y",strtotime( $Website['Created']));?>  </td>
    <td><?php if($Website['ClientID'] > 0){echo $Member_row[0]['CompanyName'];} else{echo $Member_row[0]['CompanyName']=""; echo $Member_row['ID'];}?></td>
    <td><?php  echo $Website['MemberName'];?></td>
    <td align="left">
	<?php switch($Website['Status']){
		case 1:
   	?>
	<img src="../../images/icon_tick.png" title="Availabe" />
	<?php
		break;
		case 2:
	?>
	<img src="../../images/icon_cancel.png" title="Canceled" />
	<?php
		break;
		case 3:
	?>
	<img src="../../images/icon_24Warning.png" title="Not Ready" />
	<?php
		break;
		case 4:
                    if( $Website['ClientID'] == 0){
	?>
            <img src="../../images/icon_24assigncoach.png" title="Assigned" />
	
                    <?php }else {?>
            <img  src="../../images/icon_24assigncoach.png" title="Assigned" />
                    
    <?php
			}                    
		break;
		case 5:
		
	 ?>	 
	 	 
	 <img  src="../../images/icon_24assigncoach.png" title="Domain Assigned" />
	 <?php
	 break;
	 }
	 ?>   </td>
   <td>
       <?php if(empty($Website['UserName']) && empty($Website['VanityDomain'])){?>
       <a href="http://<?php echo $Website['SubDomain'];?>.smallbusiness.info/" target="_blank"><?php echo $Website['SubDomain'];?></a>
       <?php }else{ ?>
       <?php echo $Website['SubDomain'];?>
       <?php }?>
   </td>
   
    <td>
        <?php if(empty($Website['VanityDomain'])){?>
        <a href="http://<?php echo $Website['UserName'];?>.smallbusiness.info/" target="_blank">
        <?php echo $Website['UserName'];?>
        </a>
        <?php } else{?>
        <a href="http://<?php echo $Website['VanityDomain'];?>/" target="_blank">
        <?php echo $Website['VanityDomain'];?>
        </a>
        <?php } ?>
    </td>
    <td>
        
        <?php echo $Website['UserPassword'];?>    </td>
     <td>
         <?php 
            switch($Website['HostedOnID']){
                case 1:
                        echo "EZB002";
                    break;
                case 2 :
                        echo "EZB003";
                    break;
                
                default:
                        echo"NA";
                    break;
            }
         ?>
     </td>
     <td>
     <?php 
   if(!empty( $Website['Notes'])) {?>  
       <img src="../../images/note.png" title="<?php echo $Website['Notes'];?>" />     
       <?php }; ?>    
   </td>
      
    
    <td class="Action">
        <a class="editBtn" id="DomainEdit<?php echo $Website['ID'];?>" href="SubdomainEdit.php?id=<?php echo $Website['ID'];?>&Task=Update" title="Edit <?php echo $Website['UserName'];?>"> <img src="../../images/icon_page_edit.png" border="0" title="Edit Subdomain Details"/></a>
        <a href="SubDomains.php?id=<?php echo $Website['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../../images/icon_delete.png" border="0"/></a>
        <?php if(($Website['Status']==3)){?>
        <a href="SubDomains.php?id=<?php echo $Website['SubDomain'];?>&DbPassword=<?php echo $Website['DbPassword'];?>&Task=CreateCpanel" target="_blank" onclick="return create_confirmation();"> <img  src="../../images/cpanel.png" border="0" title="Create Temp SubDomain"/> </a>
        <?php }else{?>
        <a href="SubDomains.php?id=<?php echo $Website['SubDomain'];?>&FromSubDomain=<?php echo $Website['SubDomain'];?>&ToSubDomain=<?php echo $Website['UserName'];?>&Task=CreateCpanelReal" target="_blank" onclick="return create_confirmation();"> 
             <?php if(($Website['IsCPanelRenameRun']==0) && empty($Website['VanityDomain'])){?>
            <img  src="../../images/cpanel.png" border="0" title="Create Real SubDomain"/> </a>
             <?php } ?>
        <?php }?>
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

<!--
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.css" />
-->
<script type="text/javascript">
$(document).ready(function(){
	var oTable = $('#Website_List').dataTable({
		"iDisplayLength": 10,
                stateSave: true
	});
	oTable.fnSort( [ [0,'desc']] );

});
$(window).load(function(){
	$('#Website_List').show();
});
</script>
