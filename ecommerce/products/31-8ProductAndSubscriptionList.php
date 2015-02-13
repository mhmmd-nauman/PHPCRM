<?php include "../../include/header.php"; 
$utilObj = new util();

/*---------------this is product  code-------------*/
if($_REQUEST['Submit']=='Save' && $_REQUEST['Task']){
	switch($_REQUEST['Task']){
	case"add":
            $arrValue=array('ProductName'=>$_REQUEST['Product_Name'],'Description'=>$_REQUEST['Prod_Description'],'ProductPrice'=>$_REQUEST['Product_Price'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ManageMerchantAccID'=>$_REQUEST['MerchantAccount']);
			$insertedId=$utilObj->insertRecord('Product', $arrValue);
		     if($insertedId){
			    $Flag='added';
			     if(count($_REQUEST['ProductCategories'])>0){ 
				     foreach ($_REQUEST['ProductCategories'] as $values):
		              $arrValue=array('ProductID'=>$insertedId,'ProductCategoryID'=> $values);
			          $utilObj->insertRecord('Product_ProductCategories', $arrValue);
					  endforeach;
				      $Flag='added';
				 }
			 }
		 break;
	case"update":
               $arrValue=array('ProductName'=>$_REQUEST['Product_Name'],'Description'=>$_REQUEST['Prod_Description'],'ProductPrice'=>$_REQUEST['Product_Price'],'LastEdited'=>date('Y-m-d H:i:s'),'ManageMerchantAccID'=>$_REQUEST['MerchantAccount']);
			   $strWhere='ID='.$_REQUEST['id'];
			   $Updaterec=$utilObj->updateRecord('Product', $strWhere, $arrValue);
			  if($Updaterec){
			      $Flag='update';
			    if(count($_REQUEST['ProductCategories'])>0){ 
			    $deleted=$utilObj->deleteRecord('Product_ProductCategories', 'ProductID="'.$_REQUEST['id'].'"');
				foreach ($_REQUEST['ProductCategories'] as $values):
		         $arrValue=array('ProductID'=>$_REQUEST['id'],'ProductCategoryID'=> $values);
			     $utilObj->insertRecord('Product_ProductCategories', $arrValue);
				 endforeach;
				 $Flag='update';
			 }
		    break;	
	   }	
 }
}

/*---------------this is product with subscription code-------------*/
if($_REQUEST['Submit_Subscription']=='Save' && $_REQUEST['Task']){
	switch($_REQUEST['Task']){
	case"add":
	  $arrValue=array('ProductName'=>$_REQUEST['Product_Name'],'Description'=>$_REQUEST['Prod_Description'],'ProductPrice'=>$_REQUEST['Product_Price'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ManageMerchantAccID'=>$_REQUEST['MerchantAccount']);
			$insertedId=$utilObj->insertRecord('Product', $arrValue);
		     if($insertedId){
				  $Flag='added';
					 if(count($_REQUEST['ProductCategories'])>0){ 
						 foreach ($_REQUEST['ProductCategories'] as $values):
						  $arrValue=array('ProductID'=>$insertedId,'ProductCategoryID'=> $values);
						  $utilObj->insertRecord('Product_ProductCategories', $arrValue);
						  endforeach;
						  $Flag='added';
					 }
			$arrValueSubscription=array('ProductID'=>$insertedId,'SubscriptionPrice'=>$_REQUEST['Subscription_Price'],'BillEvery'=>$_REQUEST['billevery'],'DaysBeforeStart'=>$_REQUEST['DaysBeforeStart'],'Duration'=>$_REQUEST['Duration'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  $insertedIdSub=$utilObj->insertRecord('ProductSubscription', $arrValueSubscription);
			
		}
	   break;
	   case"update":
               $arrValue=array('ProductName'=>$_REQUEST['Product_Name'],'Description'=>$_REQUEST['Prod_Description'],'ProductPrice'=>$_REQUEST['Product_Price'],'LastEdited'=>date('Y-m-d H:i:s'),'ManageMerchantAccID'=>$_REQUEST['MerchantAccount']);
			   $strWhere='ID='.$_REQUEST['id'];
			   $Updaterec=$utilObj->updateRecord('Product', $strWhere, $arrValue);
			  if($Updaterec){
			    $Flag='update';
				 //Product category operation.
			    if(count($_REQUEST['ProductCategories'])>0){ 
						$deleted=$utilObj->deleteRecord('Product_ProductCategories', 'ProductID="'.$_REQUEST['id'].'"');
						foreach ($_REQUEST['ProductCategories'] as $values):
						 $arrValue=array('ProductID'=>$_REQUEST['id'],'ProductCategoryID'=> $values);
						 $utilObj->insertRecord('Product_ProductCategories', $arrValue);
						 endforeach;
						 $Flag='update';
				}
			 
		$arrValueSubscription=array('SubscriptionPrice'=>$_REQUEST['Subscription_Price'],'BillEvery'=>$_REQUEST['billevery'],'DaysBeforeStart'=>$_REQUEST['DaysBeforeStart'],'Duration'=>$_REQUEST['Duration'], 'LastEdited'=>date('Y-m-d H:i:s'));
		     $Subwher='ProductID='.$_REQUEST['id'].'';
			 $SubscriptionRec=$utilObj->getCount('ProductSubscription',$Subwher);
		    // echo $SubscriptionRec['total'];
			// exit;
	   	 if($SubscriptionRec['total'] > 0)
			 $UpdateSub=$utilObj->updateRecord('ProductSubscription', $Subwher,$arrValueSubscription);
			 else{
			 $arrValueSubscription=array('ProductID'=>$_REQUEST['id'],'SubscriptionPrice'=>$_REQUEST['Subscription_Price'],'BillEvery'=>$_REQUEST['billevery'],'DaysBeforeStart'=>$_REQUEST['DaysBeforeStart'],'Duration'=>$_REQUEST['Duration'], 'LastEdited'=>date('Y-m-d H:i:s'),'Created'=>date('Y-m-d H:i:s'));
			   $UpdateSub=$utilObj->insertRecord('ProductSubscription',$arrValueSubscription);
			   $Flag='SubAdded';
			 }
	    }	
	   break;	 
    }
}
/*----------------------------end--------------------------*/

$CatRecords=$utilObj->getMultipleRow('Product',1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>/../../js/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>/../../js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />

<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/ProductCategory.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#hubopus_addproduct").fancybox({
        'width'                       : 820,
        'height'                      : 520,
        'autoScale'                   : false,
        'transitionIn'                : 'none',
        'transitionOut'               : 'none',
        'href'                        : this.href,
        'type'                        : 'iframe'
    });

});
</script>
</head>
<body>

<div id="headtitle">Products</div>
<div class="filtercontainer" style="border:none; margin-top:15px;">&nbsp;</div>
<div class="subcontainer">
  <div  style="float: left; padding: 10px 0 0;text-align: center; width: 905px;" >
     <?php if($Flag=='added') {?>
      <div style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Added Sucessfully! "; ?> </div>
     <?php } 
	  else if($Flag=='update') {?>
       <div style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Updated Sucessfully! "; ?> </div>
      <?php } 
	   else if($_SESSION['flag']=='Delete') {?>
       <div style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="4" align="center" id="operationmsg"><?php echo " Record has been Deleted Sucessfully! "; ?> </div>
       <?php $_SESSION['flag']='';} 
	   else if($Flag=='SubAdded') {?>
       <div style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="4" align="center" id="operationmsg">
	    <?php echo " Subscription Added Sucessfully! "; ?> </div>
        <?php } ?>
 </div>
      
<div style="float:right; padding:10px 25px 5px 5px;">
<a href="ProductAndSubscriptionPopup.php?Task=add"  id="hubopus_addproduct"  class="Categoryedit">
<input type="button" name="addCategory" id="addCategory" value="Add New Product">
</a>
</div>
<div style="margin: 0 auto; padding-top: 10px;width: 1200px;">
<table cellpadding="2" cellspacing="1"  border="0" width="100%">

 

<tr id="headerbar">
<td align="center">Id</td>
<td align="center" >Product Name</td>
<td align="center">Description</td>
<td align="center">Subscription Status</td>
<td align="center">Actions</td>
</tr>
<?php 

$color=1;
if(count($CatRecords)>0){
foreach($CatRecords as $catcval):
$SubCiteare='ProductID='.$catcval['ID'].'';
$SubscriptionExits=$utilObj->getCount('ProductSubscription',$SubCiteare);
 
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

?>
<tr id="<?php echo $colors; ?>">
<td align="center"><?php echo $catcval['ID']; ?> </td>
<td align="center"><?php echo $catcval['ProductName']; ?></td>
<td align="center"><?php echo $catcval['Description']; ?></td>
<td align="center">
<?php if($SubscriptionExits['Total']>0){?> 
 <img title="Subscription Product" src="<?php echo SITE_ADDRESS;?>/admintti/images/icon_tick.png">
 <?php } else {?>
 <img title="Normal Product" src="<?php echo SITE_ADDRESS;?>/admintti/images/icon_cancel.png">
 <?php }?>
</td>

<!--ProductCategoryAddPopup.php?Task=update&catid=<?php //echo $catcval['id']; ?>-->
<td align="center"><a href="ProductAndSubscriptionPopup.php?Task=update&Prodid=<?php echo $catcval['ID'];?>" class="Categoryedit"  id="show_<?php echo $catcval['ID'];?>"
onclick="return showDialog('show_<?php echo $catcval['ID'];?>');">
<img border="0" title="Edit Category" src="../../images/icon_page_edit.png">
</a>
&nbsp;<a  href="ProductAndCategoryDelete.php" rel="Delete" id="Del_<?php echo $catcval['ID']; ?>" class="deletecat"> 
<img border="0" src="../../images/icon_delete.png" title="Delete Category"></a>
</td>
</tr>
<?php $color++; endforeach; 
}else{
?>
<tr id="<?php echo $colors; ?>">
<td align="center" colspan="5">No Product Found</td>
</tr>
<?php } ?>
<!--<tr id="row-tan">
<td align="center"><a href="MerchantAccTestPopup.php"  class="merchantAccTest">Click Here</a></td>
<td align="center">Authorize.NET Test TWO</td>
<td align="center">Authorize</td>
<td align="center">0</td>
<td align="center">$10,000</td>
<td align="center"><a href="ManageMerchantAccPopup.php" class="merchantedit"><img border="0" title="Edit Lead Details" src="../../images/icon_page_edit.png"></a></td>
</tr>-->
<tr>
<td colspan="9">&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
<td colspan="9"></td>
</tr>
<tr></tr>
</table>
</div>
</div>
<div id="divId" title="Enter Admin Password" style="display:none;">
<div align="center" style="color:#FF0000;" id="wrongpassword"></div>
<form name="passwordsubmit" id="passwordsubmit" method="post"  onsubmit="return PasswordsubmitForm();">
<input type="password" name="adminpassword"  size="30"  id="adminpassword"/>
<input type="hidden" name="fancyboxid" id="fancyboxid"  value=""/>
<input type="hidden" name="prodtype" id="prodtype"  value="iframe"/>
<input type="hidden" name="DeleteRecId" id="DeleteRecId"  value=""/>
<input type="hidden" name="page" id="page"  value="Product"/>
<input type="submit" value="Submit" style="margin-top:6px;" class="MOSGLsmButton" name="adminPassSubmit">
</form>
</div>
<!--confirm dialog box-->
<div id="modal_confirm_yes_no" title="Confirm" style=" display:none;">
<strong>Are you sure you want to delete this Product?</strong></div>

</body>
</html>
