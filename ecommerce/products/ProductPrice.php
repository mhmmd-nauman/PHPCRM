<?php  include "../../lib/include.php";
$objproducts = new Products();
$Prodid = $_REQUEST['Productid'];
if($_REQUEST['Task']=='add' && $_REQUEST['postback']==1){
    $objproducts->InsertProductPrice(array(
                                    "ProductID" =>$Prodid,   
                                    "ProductPrice"=>$_REQUEST['ProductPrice'],

                                      ));
 header("Location:".SITE_ADDRESS."ecommerce/products/ProductAndSubscriptionPopup.php?flag=addprice&Prodid=$Prodid");
 exit;
}

?>
	

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 12px;}
</style>
   <!-- Tabs and button code -->
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.9.1.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/styles.css" />
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  
<script>
function goBack(){
    window.history.go(-1);
 }
</script>
<form action="?Productid=<?php echo $_REQUEST['Productid'];?>&Task=add"method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">

	<div class="containerpopup"> 
		<table width="100%" border="0" cellspacing="1" cellpadding="1">

                   <tr valign="top">
                   <td width="20%"   align="left"> Price:</td>
                   <td ><input name="ProductPrice" type="text" size="40" /></td>
                 </tr>
                     
                   <tr valign="top">
                   <td  align="left"colspan="2">&nbsp;  </td>
                 </tr>
                     </table>
		</div>
		<div style="height:25px;">&nbsp;</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
       <td align="center" colspan="2">
           <div align="center" style="float: left; margin-top:10px; bottom:0; background: none repeat scroll 0 0 #FFFFFF;  width: 100%;">
	   <input type="submit" name="Submit" value="Add Product Price" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()" /> 
	   <input type="button" name="Submit" value="Go Back" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="goBack()" />
	  
           
																																		<input type="hidden" name="oldimage"  value=""/></div>
           <input type="hidden" name="postback" value="1" />
        <input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" />
       </td>
        </tr>
		
     </table>
     
</form>



