<?php
include "../../include/header.php"; 
$objorder = new Orders();

if($_REQUEST['Task']=='del'){
    $deletedorder=$objorder->DeleteOrder($_REQUEST['id']); 
    if($deletedorder==1){
       $flag="del";
    }  	
}

$ClientOrdersRows = $objorder->GetAllOrder(" 1 ",array("*"));
?>

<script type="text/javascript" src="../js/search.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="../css/search.css" />

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
		   $("#orderdetaila").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
         <?php  foreach((array)$ClientOrdersRows as $OrdersRows){?>        
                $("#orderdetail<?php echo $OrdersRows['MemberID'];?>").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                  });
				    
         <?php } ?>
             });
</script>

<div id="headtitle">Orders</div>
<div class="filtercontainer">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="40%">
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
</form>    </td>
        <td><div align="right"> 
		
	 </div>
		</td>
  </tr>
</table>
</div>
<?php if($_REQUEST['flag']=='add'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  colspan="3" id="message_success">
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
<?php if($flag=='del'){?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td  colspan="3" id="message_success">
      Order record has been deleted successfully!
	  
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
  <td  colspan="3" id="message_success">
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
   
  <td  colspan="3" id="message_error">
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
<div class="" style="margin:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Order_List" style="display:none;">
    <thead>
    <tr id="headerbar">
     <td height="45" width="44" >ID</td>
     <td width="200" >Order Date </td>
     <td >Client Name</td>
     <td width="100">Status</td>
     <td width="100">Order Total</td>
    <td class="Action">Actions</td>
  </tr>
  </thead>
   <tbody> 
       
  <?php  foreach((array)$ClientOrdersRows as $OrdersRows){
  
			 if($flag==0){
		$flag=1;
		$row_class="row-white";
		}else{
		$flag=0;
		$row_class = "row-tan";
		}  
	   	// $status=0; 
  ?>
  <tr id="<?php echo $row_class;?>">
   <td> <?php echo $OrdersRows['ID'];?></td>
   <td width="200">
   <?php
   echo date("<b>M d</b>, Y",strtotime($OrdersRows['Created']));
     ?>
   </td>
   <td>  <?php echo $OrdersRows['FirstName']." ".$OrdersRows['Surname'];?>   </td>
    <td><?php if($OrdersRows['Status']==1){?><img src="../../images/icon_tick.png"/><?php }else{?><img src="../../images/cancel.png" border="0"/><?php } ?> </td>
    <td >
	$<?php echo number_format($OrdersRows['TotalPrice'],2);?> 
   </td>
   <td class="Action"> <!--<a id="PackageEdit<?php echo $Packge_row['ID'];?>" href="?id=<?php echo $Packge_row['ID'];?>&Task=Update" title="Edit <?php echo $Packge_row['PackagesTitle'];?>"> <img src="../../images/icon_page_edit.png" border="0" title="Edit Details"/></a>-->
   <a id="orderdetail<?php echo $OrdersRows['MemberID'];?>" href="<?php echo SITE_ADDRESS."clients/EcClientsOrderDetail.php"?>?id=<?php echo $OrdersRows['MemberID'];?>&Task=configration" title="<?php echo $OrdersRows['CompanyName']." - ".$OrdersRows['FirstName']." ".$OrdersRows['Surname']." - ".$OrdersRows['Phone'];?>"> <img src="../../images/icon_settings.png" border="0" title="<?php echo $OrdersRows['CompanyName']." - ".$OrdersRows['FirstName']." ".$OrdersRows['Surname']."'s Order Detail ";?>"/></a>

        <a href="<?php echo SITE_ADDRESS."ecommerce/orders/OrdersList.php"?>?id=<?php echo $OrdersRows['ID'];?>&Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="../../images/icon_delete.png" border="0"/></a></td>
  </tr>
 <?php }?>
</tbody>
</table>
</div>
<div align="center">
<?php //include "../../lib/bottomnav.php" ?>

</div>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />
<?php include "../../include/footer.php" ?>
<script type="text/javascript">
$(document).ready(function(){
	var oTable = $('#Order_List').dataTable({
		"iDisplayLength": 10,	
	});
	oTable.fnSort( [ [0,'desc']] );

});
$(window).load(function(){
	$('#Order_List').show();
});
</script>