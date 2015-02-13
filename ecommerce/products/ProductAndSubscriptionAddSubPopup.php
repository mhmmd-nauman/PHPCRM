<?php 
require_once "../../lib/include.php";
$utilObj = new util();
if(!empty($_REQUEST['ProdSubid'])){
    $strWhere='ID='.$_REQUEST['ProdSubid'].'';
    $SubscriptionRec=$utilObj->getSingleRow('ProductSubscription', $strWhere);
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.9.1.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$("#DeleteSub").click(function(){
if(confirm('Do tou really want to delete'))
$('#Deleteform').submit();
else 
return false;
 });
});

</script>
<script>
function goBack(){
    window.history.go(-1);
 }
</script>

  <form name="form1" method="post" action="ProductAndSubscriptionPopup.php">
    <input name="Tasksub" type="hidden" value="<?php echo $_REQUEST['Task'];?>"  >
    <input name="Subscriptionid" type="hidden" value="<?php echo $_REQUEST['ProdSubid'];?>" class="product"">
    
    <input name="Prodid" type="hidden" value="<?php echo $_REQUEST['Productid'];?>" class="product"">
    <input name="Productid" type="hidden" value="<?php echo $_REQUEST['Productid'];?>" class="product"">
	<div class="containerpopup">
<table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
     <tr>
    <td id="headerbarpopup" colspan='2'><? if($_REQUEST['Task']=='add') echo "Add Product Subscription "; else echo "Edit Product Subscription"; ?></td>
    </tr>
     <tr>
      <td >Subscription Price:</td>
      <td><input name="Subscription_Price" type="text" value="<?php echo $SubscriptionRec['SubscriptionPrice']; ?>" class="product"></td>
    </tr>
      <tr>
      <td colspan="2" height="5" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    
    
     <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    
    <tr>
      <td  valign="top">Days Before  Start:</td>
      <td>
      <input name="DaysBeforeStart" type="text" value="<?php echo $SubscriptionRec['DaysBeforeStart']; ?>" class="product">
      </td>
    </tr>
         <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
	<tr>
      <td valign="top">Bill Every:</td>
      <td>
      <select  name="billevery" style=" height: 27px; padding: 3px;" class="product">
      <option  value="1" <?php if($SubscriptionRec['BillEvery']==1) echo 'selected'; ?>>Month</option>
       <option  value="2" <?php if($SubscriptionRec['BillEvery']==2) echo 'selected'; ?>>Year</option>
       <option  value="3" <?php if($SubscriptionRec['BillEvery']==3) echo 'selected'; ?>>Week</option>
       </select>
      </td>
    </tr>
    
    <tr>
      <td  valign="top">Duration:</td>
      <td>
       <select  name="Duration" style=" height: 27px;padding: 3px;" class="product">
      
        <option  value="" <?php if(empty($SubscriptionRec['Duration'])) echo 'selected'; ?>> Does Not End</option>
        <option  value="12" <?php if($SubscriptionRec['Duration']==12) echo 'selected'; ?>>12 Months</option>
       <option  value="6" <?php if($SubscriptionRec['Duration']==6) echo 'selected'; ?>>6 Months</option>
       <option  value="30" <?php if($SubscriptionRec['Duration']==30) echo 'selected'; ?>>30 Days</option>
       </select>
      </td>
    </tr>
    
    
      
    </table>
	</div>
	<div style="height:25px;">&nbsp;</div>
	
    <table border="0" width="100%" cellspacing="1" cellpadding="1">
<tr>

        <td colspan="2"><div align="center"><input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()"/>&nbsp;
		 <input type="button" name="Submit" value="Go Back" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="goBack()" />
         </div>
        </td>
      </tr>
	  </table>
    </form>

