<?php 
include "../../include/header.php";
$objusers = new Users();
$ObjGroup = new Groups();
$objClient=new Clients();
$objOrder=new Orders();
$objProduct=new Products();
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

}
if(!empty($_SESSION['FromDate']) && !empty($_SESSION['ToDate'])){
	if(empty($search)){
	$member_search = " Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";

	}else{
		$member_search .= " AND Date(Created) >= '".date("Y-m-d",strtotime($_SESSION['FromDate']))."' AND Date(Created) <= '".date("Y-m-d",strtotime($_SESSION['ToDate']))."' ";
	}
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

$ret = $objusers->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}

$Users_array = $objusers->GetAllUserWithGroup("Group_Users.GroupID = 16",array("*"));

//print_r($Users_array);
?>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css" />

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

<div id="headtitle"> Agent Commissions</div>
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
                    <td >
                      Status:<br />
					  <select name="SiteStatus">
					  
                        <option value="all"  <?php if($_SESSION['SiteStatus'] == "all" )echo "selected";?>>All</option>
                      </select>

                    </td>
                    <td> 
                     Groups:<br />
                   <div style="margin-left:4px; height:87px;width:190px;overflow:auto;border:1px solid #CCCCCC; margin-top:4px;" id="ScrollCB">
                   
                   
                      <?PHP $membership_group = $ObjGroup->GetAllGroups("1 ORDER BY Title, ID ",array("*")); ?>
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
    <td>
    
    </td>
  </tr>
</table>
</div>

<?php if($_REQUEST['flag']=='add'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  colspan="3" id="message_success">
      User record has been Aded successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
</table>
    <?php }?>
    

<?php if($_REQUEST['flag']=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  colspan="3" id="message_success">
      User record has been deleted successfully!
	  
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
</table>
    <?php }?>
    

<?php if($_REQUEST['flag']=='update'){?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  colspan="3" id="message_success">
      User record has been updated successfully!
	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
</table>
    <?php }?>
			
   
	
	<?php if($_REQUEST['flag']=='error'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
   
  <td  colspan="3" id="message_error">
      This Email already exists in the database!
        <script>
		$(document).ready(function(){
  
        $("#message_error").fadeOut(3000);
  });
		</script>
</td>
  </tr>
</table>
    <?php }?>

	
<div class="subcontainer">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr id="headerbar">
   <td>ID</td>
   <td> Order Date </td>
   <td>Sales Persons </td>
    <td>Quantity</td>
    <td>Commision</td>
    <td></td>
  </tr>
  <?php  foreach((array)$Users_array as $Users_row){
           if($flag==0){
                $flag=1;
                $row_class="row-white";
            }else{
                $flag=0;
                $row_class = "row-tan";
            }
			$client_submitted_array =  $objClient->GetAllClients("Clients.SubmitedBy = '".$Users_row['UserID']."'",array("count(*) as total"));
				$comminsion = 0;
				$TotalComission = 0;
				$Quantity=0;
				$OrderID= $objOrder->GetAllOrder("UserID=".$Users_row['UserID']."",array("*"));
				foreach((array)$OrderID as $Order){
				$Order_Detail= $objOrder->GetAllOrderDetailWithProduct("OrderID=".$Order['ID']."",array("*"));
				foreach((array)$Order_Detail as $OrderDetail){
				$ProductPerc=$objProduct->GetAllProduct("ID=".$OrderDetail['ProductID']."",array("ProductPrecentage"));
				if($ProductPerc[0]['ProductPrecentage']==0)
				$ProductPerc[0]['ProductPrecentage']=1;
				$comminsion = ($OrderDetail['ProductPrice']*$OrderDetail['Quantity'])*($ProductPerc[0]['ProductPrecentage']/100);
				$TotalComission+=$comminsion;
			        $Quantity+=$OrderDetail['Quantity'];
			  ?>
 
  
 <?php }}?>

   <tr>
 <td> <?php echo $Users_row['UserID'];?></td>
 <td><?php echo date("<b>M d</b>, Y",strtotime( $Order['Created']));?></td>
 <td><?php echo $Users_row['FirstName']." ".$Users_row['Surname'];?></td>
 <td><?php echo $Quantity;?></td>
 
 <td width="220"><?php echo "$".number_format($TotalComission,2);?> </td></tr>
<? }?>
</table>
<div align="center">
<? //include "../lib/bottomnav.php" ?>
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
