<?php
session_start();
//print_r($_SESSION['Member']);
include "../lib/include.php";
$objpackges = new Packges();
$objorder = new Orders();
$objproducts = new Products();
$ObjMerchantAccount = new MerchantAccount();
$ObjGroup = new Groups();
$Group_ID=$ObjGroup->GetMemberGroups("UserID=".$_SESSION['Member']['ID']." AND GroupID=2 OR GroupID=3 ",array('GroupID'));
$Order_Array=$objorder->GetAllOrder("MemberID = '".$_REQUEST['id']."'",array("*"));
//print_r($Order_Array);
$orderid=$Order_Array[0]['ID'];
$Packge_array =   $objpackges->GetAllPackges(" ShowOnOrderForm = 1 ORDER BY PackagesTitle ASC",array("*"));

if(empty($Order_Array)){
	$orderid=$objorder->InsertWithPackage(array(
						      "Created"=>date("Y-m-d h:i:s"),
                                                      "LastEdited"=>date("Y-m-d h:i:s"),	 
                                                      "MemberID"=>$_REQUEST['id']
									  
						));
  						  
}

//print_r($_REQUEST['product_array_promos_package']);
if($_REQUEST['postback'] == 1 ){

																				
	$objorder->UpdateWithPackage("MemberID='".$_REQUEST['id']."'",array(
											"CredetCardNumber"			    =>$_REQUEST['Cnumber'],	
											"CredetCardType"				=>$_REQUEST['creditcardtype'],
											"ExpirationMonth"				=>$_REQUEST['ExpiryMonth'],
											"ExpirationYear"				=>$_REQUEST['ExpiryYears'],
											"SecurityCode"					=>$_REQUEST['Ccode'],
											"CompanyName"	                  =>$_REQUEST['cname'],
											"FirstName"             		  =>$_REQUEST['fName'],
											"Surname"               	      =>$_REQUEST['sureName'],
											"Email"		            		  =>$_POST['Email'],
											"Phone"                 		  =>$_REQUEST['phone'],
											"AlternatePhone"	               =>$_REQUEST['alternatephone'],
											"Notes"                 	 	  =>$_REQUEST['OthersNotes'],
											
	
	));		
	
        
        // Packages in array
		    foreach((array)$_REQUEST['product_array_id_package'] as $package_id => $package_array){
            foreach($package_array as $product_id){ 
                $objorder->UpdateOrderDetail("OrderID = $orderid AND PackagesID = '".$package_id."' AND ProductID =  $product_id",array(
											"Promo"	 =>$_REQUEST['product_array_promos_package'][$package_id][$product_id],
											"Quantity"	 =>$_REQUEST['product_array_qty_package'][$package_id][$product_id],
											
											));
  
            }
            
        }
        foreach((array)$_REQUEST['product_array_id'] as $product_id){
            $objorder->UpdateOrderDetail("PackagesID = 0 AND OrderID = '".$orderid."' AND ProductID =  $product_id",array(
											"Promo"	   =>$_REQUEST['product_array_promos'][$product_id],
											"Quantity" =>$_REQUEST['product_array_qty'][$product_id],
											
											));
        }
	header("Location:EcClientsOrderDetail.php?id=".$_REQUEST['id']."&flag=update");																			
											
 }

if($_REQUEST['Task']=='RemovePackage'){
      $package_id = $_REQUEST['package_id']; 
      $objorder->DeletePackageFromOrder($package_id,$orderid);
}   
if($_REQUEST['Task']=="RemoveProduct"){
    $product_id = $_REQUEST['product_id'];
    $objorder->DeleteProductFromOrder($product_id,$orderid);
}
if($_REQUEST['Task'] == 'AddToOrder'){
	$packageID =   $_REQUEST['PackageID'];
	$productID =   $_REQUEST['ProductID'];
	if($packageID){
		$package_products = $objpackges->GetAllProductToPackges("PackagesID ='$packageID'",array("*"));
							
		foreach((array)$package_products as $product_row){			
			$products_data = $objproducts->GetAllProduct(" ID = ".$product_row['ProductID'],array("ID","ProductName","ProductPrice","Description"));
			$products_data = $products_data[0];
                        $objorder->InsertOrderDetail(array(
									   
                                                            "PackagesID"=>$packageID,
                                                            "OrderID"=>$orderid,
                                                            "ProductName"=>$products_data['ProductName'],
                                                            "ProductPrice"=>$product_row['PriceInPackage'],
                                                            "ProductID"=>$product_row['ProductID'],
                                                            "Description"=>$products_data['Description'],
                                                            "Promo"=>0,
                                                            "Quantity"=>1,
															"Verified"=>0
							  ));
		}
        }
        if($productID){
            $product_row_array = $objproducts->GetAllProduct(" ID = ".$productID,array("ID,ProductName,ProductPrice,Description"));
            $product_row = $product_row_array[0];
            $objorder->InsertOrderDetail(array(
									   
                                                "PackagesID"=>0,
                                                "OrderID"=>$orderid,
                                                "ProductName"=>$product_row['ProductName'],
                                                "ProductPrice"=>$product_row['ProductPrice'],
                                                "ProductID"=>$productID,
                                                "Description"=>$product_row['Description'],
                                                "Promo"=>0,
                                                "Quantity"=>1,
												"Verified"=>0
					));
        }
	 
	 
}


$Packge_array =   $objpackges->GetAllPackges(" ShowOnOrderForm = 1 ORDER BY PackagesTitle ASC",array("*"));
if($_REQUEST['Task']=='RemoveProduct'){
    $objproducts->Deleteproduct($_REQUEST['productid']);
}																		
$products_array = $objproducts->GetAllProduct(" ShowOnOrderForm = 1 ",array("ID,ProductName,ProductPrice,Description"));
$client_OredrPackage_array = $objorder->GetAllClientOrderdedPackages("MemberID = '".$_REQUEST['id']."'");
$client_OredrProductWithoutPackage_array = $objorder->GetAllClientOrderdedProducts("MemberID = '".$_REQUEST['id']."'",array("OrderDetail.*"));
//echo "<pre>";
//print_r($client_OredrProductWithoutPackage_array);
//echo "</pre>";
$Transection_array = $ObjMerchantAccount->GetAllTransactionResponce("MemberID = '".$_REQUEST['id']."'",array("*"));
//print_r($Transection_array);

?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>
<?php if($_REQUEST['Task']=='RemovePackage'){?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td id="message_success">Package has been Deleted successfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
</table>
<?php if($_REQUEST['Task']=='RemoveProduct'){?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td id="message_success">Product has been Deleted successfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    <?php }?>
</table>
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
<script>
function validateForm()
{
var x=document.forms["myForm"]["cname"].value;
if (x==null || x=="")
  {
  alert("Please Enter The Business Name");
  document.forms.myForm.cname.focus();
  return false;
  }
  
  var x=document.forms["myForm"]["fName"].value;
if (x==null || x=="")
  {
  alert("Please Enter The First Name");
  document.forms.myForm.fName.focus();
  return false;
  }
   var x=document.forms["myForm"]["phone"].value;
if (x==null || x=="")
  {
  alert("Please Enter The Phone Number ");
  document.forms.myForm.phone.focus();
  return false;
  }
var x=document.forms["myForm"]["Email"].value;
var atpos=x.indexOf("@");
var dotpos=x.lastIndexOf(".");
if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
  {
  alert("Please Enter The valid Email Address");
  document.forms.myForm.Email.focus();
  return false;
  }
 
}
</script> 

<script>
  $(document).ready(function() {
    
	$("#timepicker").datepicker();

  });

  </script>	
<script>
function PaymentMethod(TypeID)
  {
  //alert(TypeID);
  //document.getElementById(visacard).style.visibility= 'visible' ;
  if(TypeID === "CredetCard"){
           document.getElementById("visacard").style.visibility = 'visible' ;
           document.getElementById("visacard").style.height = 'auto' ;
	   	   document.getElementById("cheque").style.visibility = 'hidden' ;
            document.getElementById("cheque").style.height = '0px' ;
  }else{
            document.getElementById("cheque").style.visibility = 'visible' ;
            document.getElementById("cheque").style.height = 'auto' ;
            document.getElementById("visacard").style.visibility = 'hidden' ;
            document.getElementById("visacard").style.height = '0px' ;
  }
  }
</script>		
<style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 12px;}
</style>
  <div class="Popupspace"></div>
<form action="EcClientsOrderDetail.php?id=<?php echo $_REQUEST['id'];?>&Task=UpdateOrder" method="post" target="_self" enctype="multipart/form-data" name="myForm">
<?php if($_REQUEST['Task']=='UpdateOrder'){?>
   <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center"> 
    <tr>
  <td align="left" colspan="3" id="message_success">
      &nbsp;&nbsp;Order Updated successfully!
	   <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
   </table>
   <?php }?>
<div id="tabs">
<ul>

<li><a href="#tabs-order">Order Form </a></li>
<?php //if($Group_ID){?>
<li><a href="#tabs-payment">Response</a></li>
<?php //} ?>
</ul>


<div id="tabs-order">
    
<table width="100%" border="0" cellspacing="1" cellpadding="2"align="center" >
<tr>
				   
<td  colspan="2">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr id="headerbarpopup">
	<td>Product Name</td>
	<td>Promo</td>
	<td>Price</td>
	<td>&nbsp;</td>
	<td>Quantity</td>
	<td>Total</td>
	</tr>
	<?php
	$flag=0; 
	if($flag==0){
            $flag=1;
            $row_class="row-white";
        }else{
            $flag=0;
            $row_class = "row-tan";
        }
   
	
	$sum=0;
	$total=0;
	//print_r($client_OredrPackage_array);
	if($client_OredrPackage_array){
	foreach($client_OredrPackage_array as $package_row){
            $package_id = $package_row['ID'];
            if( $package_id > 0  ){
            $package_products = $objorder->GetAllOrderDetail("PackagesID = '".$package_row['ID']."' AND OrderID = $orderid",array("*"));
			//print_r($package_products);
            
            $Packge_Data =   $objpackges->GetAllPackges(" ID = '$package_id' ORDER BY PackagesTitle ASC",array("*"));
            if(count($package_products)){
            ?>
            <tr id="package-heading">
             <td><?php echo $Packge_Data[0]['PackagesTitle'];?></td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
              </tr>
        <?PHP
	$product_price=0;
        foreach((array)$package_products as $product_row){
        //print_r($product_row);
        if($product_row['ProductID'] > 0 ){
        $qty = $product_row['Quantity'];
        if($qty < 1){
            $qty = 1;
        }
        $promo = $product_row['Promo'];
        if($promo < 1){
            $promo = 0;
        }
        $product_price=$product_row['ProductPrice']*$qty - $promo;
	
	 	$subtotal=$subtotal+$product_price;
	
	?>
	
	<tr id="<?php echo $row_class;?>">
	 <td><?php echo $product_row['ProductName'];?></td>
	 <td>$<?php echo $promo;?></td>
	 <td>$<?php echo number_format($product_row['ProductPrice'],2);?></td>
	 <td>&nbsp;</td>
	 <td><?php echo $qty;?></td>
	 <td>$<?php echo number_format($product_price,2);?>
	   <input type="hidden" name="product_array_id_package[<?php echo $package_id;?>][<?php echo $product_row['ProductID'];?>]" value="<?php echo $product_row['ProductID'];?>" /></td>
	</tr>
	
	
	<?php 
            }
			
        }
		$subtotal =  $subtotal - $Packge_Data[0]['PackageDiscount'];
		//
		?>

		<?php
		 $packagetotal=$subtotal+$total;
        $subtotal = 0;
		}
       }
      
	  } 
	  ?>
	    <tr id="package-heading">
		 <td colspan="3"> </td>
		 <td >&nbsp;</td>
		 <td>Package Discount:</td>
		 <td>$<?php echo number_format($Packge_Data[0]['PackageDiscount'],2); ?></td>
     </tr>
	 <tr id="totalamount">
	 <td colspan="3"> </td>
	 <td>&nbsp;</td>
	 <td>Package Total:</td>
	 <td>$<?php echo number_format($packagetotal,2); ?></td>
	 </tr> 
	  <?php 
	
	   
    }else{
	?>
	  <tr>
            <td colspan="6" align="center">
                <br>No Package In Order            </td>
        </tr> 
    <?php }?>
	      <tr>
            <td colspan="6">
                <hr/>            </td>
        </tr>
        <tr id="package-heading">
             <td>Products</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
             </tr>
		<?PHP
       $promo = 0;
       $qty = 1;
        if($client_OredrProductWithoutPackage_array){
	foreach((array)$client_OredrProductWithoutPackage_array as $product_row){
	//print_r($product_row);
         $product_id = $product_row['ProductID'];
		 if($product_id > 0 ){
           
            $qty = $product_row['Quantity'];
            if($qty < 1){
                $qty = 1;
            }
           
            $promo = $product_row['Promo'];
            if($promo < 1){
                $promo = 0;
            }
            $product_price = $product_row['ProductPrice'];
             //$product_price = $product_price;
            //$sum   =$total;
            
            ?>
            <tr id="<?php echo $row_class;?>">
             <td><?php echo $product_row['ProductName'];?></td>
             <td>$<?php echo number_format($product_row['Promo'],2);?></td>
             <td>$<?php echo number_format($product_row['ProductPrice'],2);?></td>
             <td>&nbsp;</td>
             <td><?php echo $qty;?></td>
             <td>$<?php echo number_format($product_price,2);?>
             <input type="hidden" name="product_array_id[<?php echo $product_id;?>]" value="<?php echo $product_id;?>" />             </td>
             </tr>
            <?php 
			$total = $total+$product_price;
			$productTotal=$productTotal+$product_price;
			
            }
        }
        }else{
		?>
		<tr>
            <td colspan="6" align="center">
				<br>No Product In Order            </td>
        </tr>
 		<?php 
 		}
 if(!empty($package_products)){?>
<tr id="totalamount">
		 <td colspan="3"> </td>
		 <td>&nbsp;</td>
		 <td>Product Total:</td>
		 <td>$<?php echo number_format($productTotal,2); ?></td>
		 </tr>
	 <?php }?>
<?php //if(!empty($total)){?>	 
<tr>
<td align="center" colspan="6">&nbsp;</td>
</tr>	
<tr id="totalamount">
<td colspan="3" >Total:</td>
<td colspan="2">&nbsp;</td>
<td >$ <?php  echo number_format($productTotal+$packagetotal,2);?></td>
</tr>


<input type="hidden" name="totalprice"  value="<?php echo number_format($total,2);?>"/>
<tr>

<td align="right" colspan="6"></td>
</tr>
<?php // } ?>			
</table>

</td>
</tr>

</table>

<table width="100%"  border="0" >
  <tr>
		<td colspan="3" id="tabsubheading"> Billing Information</td>
  </tr>
  
	<tr>
        <td id="tdleft"> Business Name: </td>
        <td id="tdmiddle">
			 <?php echo $Order_Array[0]['CompanyName'];?></td>
		<td id="tdright">&nbsp;</td>
			
   </tr>
    <tr >
	    <td > First Name:</td>
        <td allign="left"><?php echo $Order_Array[0]['FirstName'];?></td>
		<td>&nbsp;</td>
    </tr>
    <tr >
	   <td > Sure Name:</td>
                   <td allign="left"><?php echo $Order_Array[0]['Surname'];?></td>
				   <td>&nbsp;</td>
                 </tr>

                     <tr >
                   <td  >Customer Email:</td>
                   <td allign="left"><?php echo $Order_Array[0]['Email'];?></td>
				   <td>&nbsp;</td>
                 </tr>
            
                     <tr>
                   <td >Best Phone: </td>
                   <td allign="left"><?php echo $Order_Array[0]['Phone'];?></td>
				   <td>&nbsp;</td>
                 </tr>
                 <tr>
                   <td >Alternate Phone: </td>
                   <td allign="left"><?php echo $Order_Array[0]['AlternatePhone'];?></td>
				   <td>&nbsp;</td>
                 </tr>
                 <tr>
                <td ><label> Others Notes:</label></td>
                 <td> <?php echo $Order_Array[0]['Notes'];?> </td>
				 <td>&nbsp;</td>
        </tr>
  </table>
  <tr>
  <td  colspan="3">&nbsp;
		
  </td>
  </tr>
<table width="100%"  border="0">
  
   
<tr ><td colspan="3" id="tabsubheading"> Payment Information</td></tr>
<tr >
   <td colspan="3">
   <?php if($_REQUEST['Pmethod']=='defult'){?>
  
		<?php }?>
		<div id="visacard">
    <table width="100%" border="0" cellspacing="1" cellpadding="2">
	<tr >
	 <td id="tdleft">&nbsp;</td>
     <td id="tdmiddle">Authorise.Net
     </td>
     <td>&nbsp;</td>
	</tr>
  		 <tr>
		 	<td>Credit Card Number:</td>
			<?php $Cardnumber = $Order_Array[0]['CredetCardNumber'];
			$cardReplase="XXXXXXX";
			$encriptcard=substr_replace($Cardnumber,$cardReplase,0,10);
			
			?>
			<td><?php echo $encriptcard;?></td>
			<td>&nbsp;</td>
		</tr>
		 <tr>
		 	<td>Credit Card Type:</td>
			<td> 
			<?php if($Order_Array[0]['CredetCardType']=='Visa'){echo "Visa";}?>
			<?php if($Order_Array[0]['CredetCardType']=='Mastercard'){echo "Master Card";}?>
			<?php if($Order_Array[0]['CredetCardType']=='Descover'){echo "Descover";}?>
			
				</td>
				<td>&nbsp;</td>
		</tr>
		 <tr>
		 	<td>Expiration Date:</td>
			<td>
			<?php if($Order_Array[0]['ExpirationMonth']=='January'){echo "January";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='February'){echo "February";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='March'){echo "March";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='April'){echo "April";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='may'){echo "May";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='June'){echo "June";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='July'){echo "July";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='August'){echo "August";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='September'){echo "September";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='October'){echo "October";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='November'){echo "November";}?>
			<?php if($Order_Array[0]['ExpirationMonth']=='December'){echo "December";}?>
			
			<?php if($Order_Array[0]['ExpirationYear']== 2013){echo "2013";}?>
			<?php if($Order_Array[0]['ExpirationYear']==  2014){echo "2014";}?>
			<?php if($Order_Array[0]['ExpirationYear']==  2015){echo "2015";}?>
			<?php if($Order_Array[0]['ExpirationYear']==  2016){echo "2016";}?>
			<?php if($Order_Array[0]['ExpirationYear']==  2017){echo "2017";}?>
			<?php if($Order_Array[0]['ExpirationYear']==  2018){echo "2018";}?>
			<?php if($Order_Array[0]['ExpirationYear']==  2019){echo "2019";}?>
			<?php if($Order_Array[0]['ExpirationYear']==  2020){echo "2020";}?>
		    	</td>
		 <td>&nbsp;</td>
		</tr>
		<tr>
		 	<td>Security Code:</td>
				<td><?php echo $Order_Array[0]['SecurityCode'];?></td>
				 <td >&nbsp;</td>
		</tr>
		<tr>
		 	<td>Information Sent to Payment GateWay:</td>
				<td> 
				<?php if($Order_Array[0]['CardCharged']==1){echo"Yes";}else{echo "No";}?>				</td>
				<td >&nbsp;</td>
		</tr>
	</table>
	</td>
</tr>
</table>  
</div>
<div id="tabs-payment">

	<table width="100%" border="0" >
                <tr>
                   <td  colspan="3" id="tabsubheading"> Payment Gateway Information </td>
                 </tr>
                     <tr valign="top">
                     <td height="23" colspan="3">&nbsp;</td>
                	 </tr>
                    <tr>
                   <td id="tdleft"> Responce Text:</td>
                   <td id="tdmiddle"><?php echo $Transection_array[0]['ResponseReasonText'];?></td>
				   <td id="tdright">&nbsp;</td>
                 </tr>
                     <tr>
                   <td>Transection Type :</td>
                   <td ><?php echo $Transection_array[0]['TransactionType'];?></td>
				    <td>&nbsp;</td>
                 </tr>
				 <tr>
                   <td>Customer ip :</td>
                   <td ><?php echo $Transection_array[0]['Customer_IP'];?></td>
				    <td>&nbsp;</td>
                 </tr>
				 <tr>
                   <td>Invice Number :</td>
                   <td ><?php echo $Transection_array[0]['InvoiceNum'];?></td>
				   <td>&nbsp;</td>
                 </tr>
				 <tr>
                   <td>Response Code :</td>
                   <td ><?php echo $Transection_array[0]['ResponseCode'];?></td>
				   <td>&nbsp;</td>
                 </tr>
				 <tr>
                   <td>Response Sub code :</td>
                   <td ><?php echo $Transection_array[0]['ResponseSubcode'];?></td>
				   <td>&nbsp;</td>
                 </tr>
                  <tr>
                   <td   width="25%">Card Type :</td>
                   <td ><?php echo $Transection_array[0]['CardType'];?></td>
				   <td>&nbsp;</td>
                 </tr>

                 <tr>
                   <td>Order Number :</td>
                   <td ><?php echo $Transection_array[0]['PurchaseOrderNumber'];?></td>
				   <td>&nbsp;</td>
                 </tr>
                     </table>
</div>



</div>


<div style="height:25px;">&nbsp;</div>
<table width="100%" border="0" >
		<tr>
       <td align="center" colspan="3">
	   <div align="center">
 <input type="submit" name="submit" value="Update Order" onclick="return validateForm();" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"/>	</div>
 <input type="hidden" name="postback" value="1" />
 </td>
 </tr>
 </table>
 </form>


