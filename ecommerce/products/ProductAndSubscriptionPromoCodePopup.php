<?php 
require_once "../../lib/include.php";
$utilObj = new util();
$accessflag!='no';
if(!empty($_REQUEST['promoid'])){
 $strWhere='ID='.$_REQUEST['promoid'].'';
 $PromoRec=$utilObj->getSingleRow('ProductPromoCode', $strWhere);
}


?>

<script type="text/javascript" src="../../../javascript/jquery.min.js"></script>

<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="../../css/styles.css" media="screen" />


<script type="text/javascript">
$(document).ready(function() {
$("#DeletePromo").click(function(){
if(confirm('Do tou really want to delete'))
$('#Deleteform').submit();
else 
return false;
 });
});

</script>
<div style="width:400px;">
  <form name="form1" method="post" action="">
    <input name="Taskpromo" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
    <input name="promoid" type="hidden" value="<?php echo $_REQUEST['promoid'];?>" size="40">
    <input name="Productid" type="hidden" value="<?php echo $_REQUEST['Productid'];?>" size="40">
    <?php
	if($_REQUEST['Task']=='add'&& $_REQUEST['Productid']!=''){
		$f=1;
	$StrWhere="ProductID=".$_REQUEST['Productid'];
	}
    else if($_REQUEST['Task']=='update' && $PromoRec['ProductID']!=''){
		$f=1;
    $StrWhere="ProductID=".$PromoRec['ProductID'];
	}
	
	if($f==1)
	$SubscripRecords=$utilObj->getMultipleRow("ProductSubscription" , $StrWhere);
	else
	echo "Please add product before promo code."; 
	if($f==1){ ?>
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
    <tr>
    <td id='name' colspan='2'><? if($_REQUEST['Task']=='add') echo "Add Promotional Code"; else echo "Edit Promotional Code"; ?></td>
    </tr>
      <tr>
      <td align="right" valign="top">Promotional Code:</td>
      <td> <input name="PromoCodename" type="text" value="<?= $PromoRec['PromoCodeName']; ?>" size="20" style="height:20px;">
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Product Price:</td>
      <td> <input name="Productprice" type="text" value="<?= $PromoRec['ProductPrice'];?>" size="20" style="height:20px;">
      </td>
    </tr>
    <?
	
	
	
	if($SubscripRecords)
	{
	echo '<tr><td align="right" valign="top">Select Subscription:</td>
	<td>
	<select name="Subscription" id="Subscription" style=" height: 27px;padding: 3px;width: 150px;" >';
	
	foreach($SubscripRecords as $subscripVal)
	{
	
		if($subscripVal["BillEvery"]=='1')
		{
		$Every="per Month";
		}
		elseif($subscripVal["BillEvery"]=='2')
		{
		$Every="per Year";
		}
		elseif($subscripVal["BillEvery"]=='3')
		{
		$Every="per Week";
		}
		if($subscripVal["Duration"]=="0")
		{
		$Duration=" ";
		}
		elseif($subscripVal["Duration"]=="30")
		{
		$Duration="for ".$subscripVal["Duration"]." Days";
		}
		else $Duration="for ".$subscripVal["Duration"]." Months";
		$Subscription=$subscripVal["SubscriptionPrice"]."$ ".$Every." ".$Duration;
	echo '<option value='.$subscripVal["ID"].'>'.$Subscription.'</option>';
	}
	?>
    </td></tr>
    <tr>
      <td align="right" valign="top">Subscription Price:</td>
      <td> <input name="Subscriptionprice" type="text" value="<?= $PromoRec['SubscriptionPrice'];?>" size="20" style="height:20px;">
      </td>
    </tr>
    <? } ?>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="Submit" class="Ecom_Link" style="margin-top:6px;" value="Save">
        <? if($_REQUEST['Task']=='update') echo '<input type="button" name="DeletePromo" id="DeletePromo" class="Ecom_Link" style="margin-top:6px;" value="Delete">'; ?> 
        </td>
      </tr>
    </table>
    <?php } ?>
  </form>
<form name="Deleteform" id="Deleteform" method="post" action="">
  <input name="Taskpromo" type="hidden" value="delete" size="40" >
    <input name="promoid" type="hidden" value="<?php echo $_REQUEST['promoid'];?>" size="40">
    <input name="Productpromoid" type="hidden" value="<?php echo $_REQUEST['Productid'];?>" size="40">
  </form>
  
</div>
