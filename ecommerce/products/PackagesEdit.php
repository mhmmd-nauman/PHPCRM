<?php  include "../../lib/include.php";
$objpackges = new Packges();
$objproducts = new Products();
$Packge_array = $objpackges->GetAllPackges("ID = '".$_REQUEST['id']."'",array("*")); 
$ProductToPackge_array = $objpackges->GetAllProductToPackges("PackagesID = '".$_REQUEST['id']."'",array("*"));
$Product_Array = $objproducts->GetAllProduct("1",array("ID,ProductName"));
$checked_value=$_REQUEST['packagesProducts'];
if($_REQUEST['Task']=='AddSave'){
	if(empty($_REQUEST['Ptitle']))$_REQUEST['Ptitle']="New Package Title";
	if(empty($_REQUEST['Pdescription']))$_REQUEST['Pdescription']= "New Package Description Here ";

	   $added= $objpackges->InsertPackge(array(
									 			"Created"  =>date("Y-m-d h:i:s",time()),
                                                "PackagesTitle"          =>$_REQUEST['Ptitle'],
												"PackgesDescription"    =>$_REQUEST['Pdescription'],
                                                 "ShowOnOrderForm"    =>$_REQUEST['ShowOnOrderForm'],
                                                                                                 
													  ));
		foreach((array)$checked_value as $value){
		
	 		$objpackges->InsertProductToPackage(array(
												  "ProductID"           =>$value,   
												   "PackagesID"          =>$added,
												  
											
													  ));
		}											  
	header("location:".SITE_ADDRESS."ecommerce/products/Packages.php?flag=add"); 
	exit;    


	}else{
    			//header("location:Packges.php?flag=error"); 
	}



if($_REQUEST['Task']=='UpdateSave'){
//echo"jjjjjjjj";
    
	$packgeid = $_REQUEST['id'];
    $updated= $objpackges->UpdatePackge("ID = '$packgeid' ",array(
			                                              "PackagesTitle"      =>$_REQUEST['Ptitle'],
                                                                      "PackgesDescription"	       =>$_REQUEST['Pdescription'],
                                                                       "ShowOnOrderForm"    =>$_REQUEST['ShowOnOrderForm'],
                                                                             
                                                                               
                                                          ));
	$objpackges->DeletePackageProduct($_REQUEST['id']);									  
 	//echo "<pre>";
        //print_r($_REQUEST);
	//echo "</pre>";
        foreach((array)$_REQUEST['packagesProducts'] as $product_id){
		
	 $objpackges->InsertProductToPackage(array(
                                                    "ProductID"           =>$product_id,   
                                                    "PackagesID"          =>$packgeid,
                                                    "PriceInPackage"      =>$_REQUEST['PackagePrice'][$product_id],

                                                 ));
                                         }
       header("Location:".SITE_ADDRESS."ecommerce/products/Packages.php?flag=success"); 
		exit;     
}
if(	$_REQUEST['Task']=='Update' ){
	$Task="UpdateSave";
}else{
	$Task="AddSave";
}

if($_REQUEST['Task']=='del'){
///echo "kkkkk";
	$objpackges->DeletePackge($_REQUEST['id']);
	//exit;
 header("Location:".SITE_ADDRESS."ecommerce/products/Packages.php?flag=del");    	
}


?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
 	   
<script type="text/javascript" language="javascript">
function ValidateForm(){
return true;
if ((document.frmSample.FName.value==null)||(document.frmSample.FName.value=="")){
		alert("Please Enter Name")
		document.frmSample.FName.focus()
		return false;
	}
if ((document.frmSample.Email.value==null)||(document.frmSample.Email.value=="")){
		alert("Please Enter Email")
		document.frmSample.Email.focus()
		return false;
	}


}
       
</script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/style.css" />
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  

<!-- End of Tabs and button code --> 
	
<style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 13px;}
</style>
<form action="?id=<?php echo $_REQUEST['id'];?>&Task=<?php echo $Task;?>" target="_top"method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">

<?php if($_REQUEST['flag']=='update'){?>
   <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
    <tr>
  <td align="left" colspan="3" id="message_sucess">
      <img src="images/accept.png"/>&nbsp;&nbsp;Packges updated successfully!
	   <script>
$(document).ready(function(){
  
        $("#message_sucess").fadeOut(3000);
  });
</script>03066149342
    </td>
    
  </tr>
   </table>
   <?php }?>
        <div class="container1">
		
			<div id="tabs">
			<ul>
			<li><a href="#tabs-1">Package</a></li>
			<li><a href="#tabs-2">Products</a></li>
			</ul>
		<div id="tabs-1">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
                        
                        
                       <tr valign="top">
                   <td  align="left"colspan="2" class="tabsubheading"> Basic Information </td>
                 </tr>
                     <tr valign="top">
                   <td   align="left"colspan="2">&nbsp;  </td>
                 </tr>
                 
                   
                 
                 <tr valign="top">
                   <td width="40%"   align="left"> Package Title:</td>
                   <td ><input name="Ptitle" type="text" value="<?php echo $Packge_array[0]['PackagesTitle'];?>" size="40" /></td>
                 </tr>
                     <tr valign="top">
                   <td   width="40%"> Package Description:</td>
                   <td ><textarea name="Pdescription" id="notes" cols="37" rows="5"><?php echo $Packge_array[0]['PackgesDescription'];?></textarea></td>
                 </tr>
                 <tr valign="top">
                   <td   width="40%"> Show on Order Form:</td>
                   <td >
                       <input type="checkbox" name="ShowOnOrderForm" id="ShowOnOrderForm" value="1"  <?php if ($Packge_array[0]['ShowOnOrderForm'] == 1) { ?> checked="checked"<?php } ?> />
                   </td>
                 </tr> 

                 <tr valign="top">
                   <td  align="left"colspan="2">&nbsp;  </td>
                 </tr>
                     </table>
		</div>
		<div id="tabs-2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
                         <td  colspan="2">
                             
                         <table width="100%" border="0" cellspacing="0" cellpadding="1">
						 
					
					<tr >
					<td class="tabsubheading">&nbsp;</td>
					<td class="tabsubheading">Product</td>
					<td class="tabsubheading">Price</td>
					</tr>
					<tr>
					<td colspan="3">&nbsp;</td>
					</tr>
                   <?php
				  
                   foreach((array)$ProductToPackge_array as $ProductToPackge){
				  echo  $productid[]=$ProductToPackge['ProductID'];
                                    $package_price[$ProductToPackge['ProductID']]= $ProductToPackge['PriceInPackage'];
				   }
				  
                   for($i=0;$i<count($Product_Array);$i++){
			//print_r();
                       ?>
					    <tr>
					   <td width="15%">
<input type="checkbox" id="packagesProducts<?php echo $Product_Array[$i]['ID'];  ?>" value="<?php echo $Product_Array[$i]['ID'];  ?>" name="packagesProducts[]" <?php if(in_array($Product_Array[$i]['ID'],(array)$productid)){echo"checked";};?> >
						</td>
						<td width="15%" align="left">
							<?php echo $Product_Array[$i]['ProductName']; ?>                                                   
						</td>
						<td><input name="PackagePrice[<?php echo $Product_Array[$i]['ID'];  ?>]" type="text"  size="5" value="<?php echo $package_price[$Product_Array[$i]['ID']];?>"/></td>
                    	 </tr>
						<?php }?>
								
							</table>
								
						
                    						 
						 </td>
                     </tr> 
					 

</table>
		</div>
		<tr>
       <td align="center" colspan="2">
           <div align="center" style="float: left; margin-top:10px; bottom:0; background: none repeat scroll 0 0 #FFFFFF;  width: 100%;">
	   <input type="submit" name="Submit" value="Save Changes" class="MOSGLButton" style="height:29px;" onclick="return spon_check()" id="javed" />
           <div id="delwaitmsg" style="margin-left:165px;"></div>
																																		<input type="hidden" name="oldimage"  value=""/></div>
           <input type="hidden" name="postback" value="1" />
        <input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" />
       </td>
        </tr>
		</div>
   
</div>
     
</form>
