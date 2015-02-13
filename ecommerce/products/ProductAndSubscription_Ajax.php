<?php  include "../../lib/include.php";
$utilObj = new util();
$accessflag!='no';
if(!empty($_REQUEST['Prodid'])){
 $strWhere='ID='.$_REQUEST['Prodid'].'';
 $ProductRec=$utilObj->getSingleRow('Product', $strWhere);
 
 $DaysBeforeStart='0';
 
 $strWhereprod='ProductID='.$_REQUEST['Prodid'].'';
 $productassociatCat=$utilObj->getMultipleRow('Product_ProductCategories',$strWhereprod);
 $SubscriptionRec=$utilObj->getSingleRow('ProductSubscription',$strWhereprod);
 }
 
 $categoryRec=$utilObj->getMultipleRow('ProductCategory',1);




 if($SubscriptionRec['DaysBeforeStart'])
 $DaysBeforeStart=$SubscriptionRec['DaysBeforeStart'];

$strWhere='ProductID='.$_REQUEST['Prodid'].'';
 $ProductPromoRec=$utilObj->getMultipleRow('ProductPromoCode', $strWhere);
 
//print_r($productassociatCat);

?>
</head>
<body style=" font-family:Arial, Helvetica, sans-serif; font-size:13px;">
<div>
 <form  method="post" action="ProductAndSubscriptionList.php"  target="_top" >
<input name="Task" type="hidden" value="<?php echo $_REQUEST['Task'];?>" size="40" >
<input name="id" type="hidden" value="<?php echo $_REQUEST['Prodid'];?>" size="40"> 
   <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center">
     <tr>
        <td colspan="2" id="name"><b><?php if($_REQUEST['Task']=='add') echo 'Add Product';else echo $ProductRec['ProductName'];?></b></td>
      </tr>
    </table>
 <div id="dhtmlgoodies_tabView1" style="width:99%;">
    <!--bottom of Signature window-->
     <div class="dhtmlgoodies_aTab">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
    <tr>
      <td align="right">Product Name:</td>
      <td><input name="Product_Name"  id="Product_Name" type="text" value="<?php echo $ProductRec['ProductName']; ?>" size="40" style="height:20px;"></td>
    </tr>
      <tr>
      <td colspan="2" height="5" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    <tr>
      <td align="right" valign="top">Description:</td>
      <td><textarea name="Prod_Description"   style="width: 263px; height: 72px;"><?php echo $ProductRec['Description']; ?></textarea></td>
    </tr>
    
     <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
       <tr>
      <td align="right" valign="top">Product Price:</td>
      <td><input name="Product_Price" type="text" value="<?php echo $ProductRec['ProductPrice']; ?>" size="40" style="height:20px;"></td>
    </tr>
    
     <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    
      <tr>
      <td align="right" valign="top">Category:</td>
      <td>
      <div style="height:100px;width:263px;overflow:auto; border:1px solid gray;" id="ScrollCB">
      <?php 
	  /*-----------array recursive function---------*/
	  function in_array_r($needle, $haystack, $strict = true) {
        foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
       }

       return false;
	   
	   }
	 
	  $i=0; 
	
	  foreach($categoryRec as $categoryval):
	   if($_REQUEST['Task']=='update'){
		   if(in_array_r($categoryval['ID'],$productassociatCat)==true) 
			$chacked='checked';
			else
		   $chacked='';
	   }
	  
	  ?>
       <div style=" float:left; width:100%;"><input type="checkbox" name="ProductCategories[]" value="<?php echo $categoryval['ID'];?>" id="MemberGroup1"  <?php echo $chacked;?> >
      <?php echo $categoryval['CategoryName'];?></div>
      <?php $i++;
      endforeach;?>
      </div></td>
      </tr>
          <tr>
          <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
        </tr>
    
        <!--<tr>
          <td colspan="2" align="center">
            <input type="submit" name="Submit" class="MOSGLsmButton" style="margin-top:6px;" value="Save">
             
         </td>
        </tr>-->
    </table>
    </div>
    
    <div class="dhtmlgoodies_aTab">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
     <tr>
      <td align="right">Subscription Price:</td>
      <td><input name="Subscription_Price" type="text" value="<?php echo $SubscriptionRec['SubscriptionPrice']; ?>" size="40" style="height:20px;"></td>
    </tr>
      <tr>
      <td colspan="2" height="5" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    <tr>
      <td align="right" valign="top">Bill Every:</td>
      <td>
      <select  name="billevery" style=" height: 27px; padding: 3px;width: 266px;">
      <option  value="1" <?php if($SubscriptionRec['BillEvery']==1) echo 'selected'; ?>>Month</option>
       <option  value="2" <?php if($SubscriptionRec['BillEvery']==2) echo 'selected'; ?>>Year</option>
       <option  value="3" <?php if($SubscriptionRec['BillEvery']==3) echo 'selected'; ?>>Week</option>
       </select>
      </td>
    </tr>
    
     <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    
    <tr>
      <td align="right" valign="top">Days Before  Start:</td>
      <td>
      <input name="DaysBeforeStart" type="text" value="<?php echo $DaysBeforeStart; ?>" size="40" style="height:20px;">
      </td>
    </tr>
         <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
    
    <tr>
      <td align="right" valign="top">Duration:</td>
      <td>
       <select  name="Duration" style=" height: 27px;padding: 3px;width: 266px;">
      
        <option  value="" <?php if(empty($SubscriptionRec['Duration'])) echo 'selected'; ?>> Does Not End</option>
        <option  value="12" <?php if($SubscriptionRec['Duration']==12) echo 'selected'; ?>>12 Months</option>
       <option  value="6" <?php if($SubscriptionRec['Duration']==6) echo 'selected'; ?>>6 Months</option>
       <option  value="30" <?php if($SubscriptionRec['Duration']==30) echo 'selected'; ?>>30 Days</option>
       </select>
      </td>
    </tr>
    
    
      <tr>
      <td colspan="2"  id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
     
    <!--<tr>
      <td colspan="2" align="center">
        <input type="submit" name="Submit" id="submitbefore" class="MOSGLsmButton" style="margin-top:6px;" value="Save">
        
     </td>
      
         
      
    </tr>-->
    </table>
       </div>
       
       
    <!--new tab merchant account-->
    <div class="dhtmlgoodies_aTab">
    <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center" >
     <tr>
      <td align="right" valign="top">Merchant Account:</td>
      <td>
      <select  name="MerchantAccount" style=" height: 27px;padding: 3px;width: 266px;">
      <?php 
	  $strwhere='AccountType!=0';
       $merchantRecords=$utilObj->getMultipleRow('ManageMerchantAcc',$strwhere);
	   foreach($merchantRecords as $mercval):
	   if($mercval['MerchantId']==$ProductRec['ManageMerchantAccID'])
	   $seletcted="selected";
	   else
	   $seletcted='';
	   
	   echo " <option  value='".$mercval['MerchantId']."' ".$seletcted.">".$mercval['AccountName']."</option>";
	   endforeach;
	  ?>
       </select>
      </td>
    </tr>
    
     <!--<tr>
          <td colspan="2" align="center">
            <input type="submit" name="Submit" class="MOSGLsmButton" style="margin-top:6px;" value="Save">
             
         </td>
        </tr>-->  
    
    </table>
       </div>
    <!--new tab merchant account-->   
     
  <div class="dhtmlgoodies_aTab">
    <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center" >
    <tr><td colspan="4" align="right"><a  href='ProductAndSubscriptionPromoCodePopup.php?Task=add&Productid=<?=$_REQUEST['Prodid']?>' id='PromoCodeADD'>Add Promotional Code</a></td></tr>
     <tr id="headerbar">
     <td>Promotional Code</td>
     <td>Product Price</td>
     <td>Subscription Price</td>
     <td>Action</td>
     <tr>
     <? 
	 $color=1;
if(count($ProductPromoRec)>0){
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

	 
	 foreach($ProductPromoRec as $PromoVal)
	 {
	 ?>
     <tr id="<?php echo $colors; ?>">
     <td> <?= $PromoVal['PromoCodeName']?></td>
     <td> <?= $PromoVal['ProductPrice']?></td>
     <td> <?= $PromoVal['SubscriptionPrice']?></td>
     <td><a href='ProductAndSubscriptionPromoCodePopup.php?Task=update&promoid=<?= $PromoVal['ID']?>' id='PromoCodeEdit_<?=$PromoVal['ID']?>' /> <img border="0" title="Edit Category" src="../../images/icon_page_edit.png"></td>
     </tr>  
     <? }?>
     
      <?php $color++; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="5">No Product Promo Code Found</td>
    </tr>
    <?php }  ?>  
    </table>
       </div>
  
  <!--common save--->
   <table border="0" width="100%" cellspacing="1" cellpadding="1">
      <tbody><tr>
        <td align="left">&nbsp;</td>
        <td align="center">
          
          <input type="submit" name="Submit" id="submitbefore" class="Ecom_Link" style="margin-top:6px;" value="Save">       
        
        </td>
        <td>&nbsp;</td>
      </tr>
    </tbody></table>
  <!---common save-->
  
  
  
  
  </div>  
  </form>
  <script type="text/javascript">
initTabs('dhtmlgoodies_tabView1',Array('Product Information','Subscription','Merchant Accounts','Promo Code'),<?= $_REQUEST['showid'];?>,'100%','100%',"","");
</script>
</div>
<!--bottom of tabs script-->

<div id="Modal_Product" title="Alert" style=" display:none;">
<strong>Please Enter Product Name On Product Information Tab, Then Add Subscription And Save It.</strong></div>
</body>
</html>

