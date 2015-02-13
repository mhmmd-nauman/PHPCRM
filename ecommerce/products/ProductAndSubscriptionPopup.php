<?php  include "../../lib/include.php";
$utilObj = new util();
$objtasks = new Tasks();
$objGroups = new Groups();
$objproducts = new Products();
$categoryRec=$utilObj->getMultipleRow('ProductCategory',1);
if(!empty($_REQUEST['Prodid'])){
    $strWhere='ID='.$_REQUEST['Prodid'].'';
    $ProductRec=$utilObj->getSingleRow('Product', $strWhere);
    //print_r($ProductRec);
    $DaysBeforeStart='0';
    $strWhereprod='ProductID='.$_REQUEST['Prodid'].'';
    $productassociatCat=$utilObj->getMultipleRow('Product_ProductCategories',$strWhereprod);
    $SubscriptionRec=$utilObj->getSingleRow('ProductSubscription',$strWhereprod);
    if($SubscriptionRec['DaysBeforeStart'])
    $DaysBeforeStart=$SubscriptionRec['DaysBeforeStart'];

    $strWhere='ProductID='.$_REQUEST['Prodid'].'';
    $ProductPromoRec=$utilObj->getMultipleRow('ProductPromoCode', $strWhere);
 
    $strWhere='ProductID='.$_REQUEST['Prodid'].'';
    $ProductSubcRec=$utilObj->getMultipleRow('ProductSubscription', $strWhere);
 }
//print_r($productassociatCat);
if($_REQUEST["Taskpromo"]){
    $Show="3";
}elseif($_REQUEST["Tasksub"]) {
    $Show="1";
} else {
    $Show="0";
}
if($_REQUEST['Task']=='add'){
    //echo "ffffff";
}
if($_REQUEST['ProductTask'] == "Update" || $_REQUEST['Prodid'] > 0 ){
    $Task = "UpdateProduct";
}else{
   $Task = "AddProduct"; 
}

switch($_REQUEST['ProductTask']){
	case "AddProduct":
            //echo"here";
	    $arrValue=array('ProductName'=>$_REQUEST['Product_Name'],'Description'=>$_REQUEST['Prod_Description'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ManageMerchantAccID'=>$_REQUEST['MerchantAccount'],'SpecialProduct'=>$SpecialProduct,'SteelePID'=>$_REQUEST['Steele_Pid'], 'CoOPID'=>$_REQUEST['CoopType'],'ShowOnOrderForm'=>$_REQUEST['ShowOnOrderForm'],'ShowCoupn'=>$_REQUEST['Showcoupn'],'TaskAttached'=>$_REQUEST['TaskAttached'],'ProductPrecentage'=>$_REQUEST['ProductPrecentage']);
            //$arrValue=array('ProductName'=>$_REQUEST['Product_Name'],'Description'=>$_REQUEST['Prod_Description'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ManageMerchantAccID'=>$_REQUEST['MerchantAccount'],'SpecialProduct'=>$SpecialProduct,'SteelePID'=>$_REQUEST['Steele_Pid'], 'CoOPID'=>$_REQUEST['CoopType'],'TaskAttached'=>$_REQUEST['TaskAttached']);
			$insertedId=$utilObj->insertRecord('Product', $arrValue);
                        //print_r($arrValue);
                        $_REQUEST['Prodid']=$insertedId;
			$checked_value=$_REQUEST['ProductsTasks'];
			 foreach((array)$checked_value as $value){
		
		 $objtasks->InsertProductToTasks(array(
							 "ProductID" =>$insertedId,  
							  "TasksID"  =>$value,
												  
											
													  ));
                                         }
		$objproducts->InsertProductPrice(array(
		                                 "ProductID"      =>$insertedId,
                                    		 "ProductPrice"=>$_REQUEST['ProductPrice'],
                                                 "DefaultPrice" =>1,

                                      ));
										 
			
		     if($insertedId){
			    $Flag='added';
			     if(count($_REQUEST['ProductCategories'])>0){ 
				     foreach ($_REQUEST['ProductCategories'] as $values):
		              $arrValue=array('ProductID'=>$insertedId,'ProductCategoryID'=> $values);
			          $utilObj->insertRecord('Product_ProductCategories', $arrValue);
					  endforeach;
				      $Flag='added';
				 }
				 
				 /****common save***/
				 if($_REQUEST['Subscription_Price'])
				 {
                                    $arrValueSubscription=array('ProductID'=>$insertedId,'SubscriptionPrice'=>$_REQUEST['Subscription_Price'],'BillEvery'=>$_REQUEST['billevery'],'DaysBeforeStart'=>$_REQUEST['DaysBeforeStart'],'Duration'=>$_REQUEST['Duration'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
                                    $insertedIdSub=$utilObj->insertRecord('ProductSubscription', $arrValueSubscription);
				 }
				 /****common save****/
			 }
                 header("location:ProductAndSubscriptionPopup.php?Prodid=".$insertedId."&flag=addedprduct&Task=Update"); 
		 break;
	case "UpdateProduct":
            //echo "it comes here"; 
            $checked_value=$_REQUEST['ProductsTasks'];
               $arrValue=array('ProductName'=>$_REQUEST['Product_Name'],'Description'=>$_REQUEST['Prod_Description'],'LastEdited'=>date('Y-m-d H:i:s'),'ManageMerchantAccID'=>$_REQUEST['MerchantAccount'],'SpecialProduct'=>$SpecialProduct,'SteelePID'=>$_REQUEST['Steele_Pid'], 'CoOPID'=>$_REQUEST['CoopType'],'ShowOnOrderForm'=>$_REQUEST['ShowOnOrderForm'],'ShowCoupn'=>$_REQUEST['Showcoupn'],'TaskAttached'=>$_REQUEST['TaskAttached'],'ProductPrecentage'=>$_REQUEST['ProductPrecentage']);
			    $strWhere='ID='.$_REQUEST['id'];
			   $Updaterec=$objproducts->UpdateProduct($strWhere, $arrValue);
			   //print_r($arrValue);
			   
			  $objtasks->DeleteAssignTask($_REQUEST['id']);
			 foreach((array)$checked_value as $value){
		 		$objtasks->InsertProductToTasks(array(
								"ProductID"           =>$_REQUEST['id'],                                                  	 "TasksID"             =>$value,
												  
											
								));
                                         }
				$Price_array = $_REQUEST['ProductPrice'];
				foreach((array)$Price_array as $id=>$Price){
				
				 $updated = $objproducts->UpdateProductPrice("ID = $id ",array(
							 "ProductPrice"=>$Price,
                                                            
                                                                               
                                                          ));
				
				
				}	

				$updated= $objproducts->UpdateProductPrice("ProductID =".$_REQUEST['id'],
                                         array(
                                             "DefaultPrice" =>0
                                              ));					 
			        $objproducts->UpdateProductPrice("ID =".$_REQUEST['defaultprice'],
                                         array(
                                             "DefaultPrice" =>1
                                              ));
                                 
                                 

										 
			  if($Updaterec){
			      $Flag='update';
				  $deleted=$utilObj->deleteRecord('Product_ProductCategories', 'ProductID="'.$_REQUEST['id'].'"');
			    if(count($_REQUEST['ProductCategories'])>0){ 
				foreach ($_REQUEST['ProductCategories'] as $values):
		         $arrValue=array('ProductID'=>$_REQUEST['id'],'ProductCategoryID'=> $values);
			     $utilObj->insertRecord('Product_ProductCategories', $arrValue);
				 endforeach;
				 $Flag='update';
			 }
			 
			 
			 
                     header("location:ProductAndSubscriptionPopup.php?Prodid=".$_REQUEST['id']."&flag=updatedprduct&Task=Update"); 
			 
		    break;	
	   }	
 
 	case"insertTask":
	//echo "it comes here";
	$checked_value=$_REQUEST['ProductsTasks'];
	$objtasks->DeleteAssignTask($_REQUEST['id']);
			 foreach((array)$checked_value as $value){
		
		 		$objtasks->InsertProductToTasks(array(
												 	 "ProductID"           =>$_REQUEST['id'],                                                  	 "TasksID"             =>$value,
												  
											
													  ));
           }
 
 }

if($_REQUEST['Submit']=='Save' && $_REQUEST['Taskpromo']){
	switch($_REQUEST['Taskpromo']){
	case"add":
            $arrValue=array('PromoCodeName'=>$_REQUEST['PromoCodename'],'ProductPrice'=>$_REQUEST['Productprice'],'SubscriptionPrice'=>$_REQUEST['Subscriptionprice'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ProductId'=>$_REQUEST['Productid'],'ProductSubscriptionID'=>$_REQUEST['Subscription'],'TaskAttached'=>$_REQUEST['TaskAttached']);
	   //print($_REQUEST);
            $insertedId=$utilObj->insertRecord('ProductPromoCode', $arrValue);
			
		     if($insertedId){
			    $Flag='added';
			     						 
			 }
		 break;
	case"update":
               $arrValue=array('PromoCodeName'=>$_REQUEST['PromoCodename'],'ProductPrice'=>$_REQUEST['Productprice'],'SubscriptionPrice'=>$_REQUEST['Subscriptionprice'],'LastEdited'=>date('Y-m-d H:i:s'),'ProductSubscriptionID'=>$_REQUEST['Subscription'],'TaskAttached'=>$_REQUEST['TaskAttached'],'ProductPrecentage'=>$_REQUEST['ProductPrecentage']);
			   $strWhere='ID='.$_REQUEST['promoid'];
			   $Updaterec=$utilObj->updateRecord('ProductPromoCode', $strWhere, $arrValue);
			  if($Updaterec){
			      $Flag='update';
				
			 }
			 break;		   	
 }
}
elseif($_REQUEST["Taskpromo"]=='delete'){
      $strCriteria='ID='.$_REQUEST["promoid"];
      $DeleteRec=$utilObj->deleteRecord('ProductPromoCode', $strCriteria);
	  if($DeleteRec)
	  $Flag='delete';
     
}
 /////////////////////////////For Subscription ADD//UPDATE//DELETE
 
// if($_REQUEST['Submit']=='Save' && $_REQUEST['Tasksub']){
	switch($_REQUEST['Tasksub']){
	case"add":
            $arrValueSubscription=array('ProductID'=>$_REQUEST['Productid'],'SubscriptionPrice'=>$_REQUEST['Subscription_Price'],'BillEvery'=>$_REQUEST['billevery'],'DaysBeforeStart'=>$_REQUEST['DaysBeforeStart'],'Duration'=>$_REQUEST['Duration'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  $insertedIdSub=$utilObj->insertRecord('ProductSubscription', $arrValueSubscription);
			  //header("location:ProductAndSubscriptionPopup.php?flag=add");
		     if($insertedIdSub){
			    $Flag='added';
			     						 
			 }
		 break;
	case"update":
               $arrValueSubscription=array('SubscriptionPrice'=>$_REQUEST['Subscription_Price'],'BillEvery'=>$_REQUEST['billevery'],'DaysBeforeStart'=>$_REQUEST['DaysBeforeStart'],'Duration'=>$_REQUEST['Duration'], 'LastEdited'=>date('Y-m-d H:i:s'));
		     $Subwher='ID='.$_REQUEST['Subscriptionid'].'';
			$UpdateSub=$utilObj->updateRecord('ProductSubscription', $Subwher,$arrValueSubscription);
			//header("location:ProductAndSubscriptionPopup.php?flag=update");
			  if($UpdateSub){
			      $Flag='update';
				
			 }
			 break;		   	
 }
//}
if($_REQUEST["Task"]=='del'){
      $strCriteria='ID='.$_REQUEST["ProdSubid"];
      $DeleteRec=$utilObj->deleteRecord('ProductSubscription', $strCriteria);
	  if($DeleteRec)
	  $Flag='delete';
     
}
if(!empty($_REQUEST['Prodid'])){
    $strWhere='ID='.$_REQUEST['Prodid'].'';
    $ProductRec=$utilObj->getSingleRow('Product', $strWhere);
 
    $DaysBeforeStart='0';
 
    $strWhereprod='ProductID='.$_REQUEST['Prodid'].'';
    $productassociatCat=$utilObj->getMultipleRow('Product_ProductCategories',$strWhereprod);
    $SubscriptionRec=$utilObj->getSingleRow('ProductSubscription',$strWhereprod);

    if($SubscriptionRec['DaysBeforeStart'])
    $DaysBeforeStart=$SubscriptionRec['DaysBeforeStart'];

    $strWhere='ProductID='.$_REQUEST['Prodid'].'';
    $ProductPromoRec=$utilObj->getMultipleRow('ProductPromoCode', $strWhere);

    $strWhere='ProductID='.$_REQUEST['Prodid'].'';
    $ProductSubcRec=$utilObj->getMultipleRow('ProductSubscription', $strWhere);
 }
$tasks_array = $objtasks->GetAllTasks("1 ORDER BY Created ASC",array("*"));
$Prodid = $_REQUEST['Prodid'];

if($_REQUEST['Task']=='AddProductPrice'){
    $objproducts->InsertProductPrice(array(
                                    "ProductID" =>$_REQUEST['Prodid'],   
                                    "ProductPrice"=>$_REQUEST['ProductPrice'],

                                      ));
    header("location:ProductAndSubscriptionPopup.php?&Prodid=".$_REQUEST['Prodid']."&flag=priceadd");
// 
 //exit;
}
if($_REQUEST["Task"]=='deleteprice'){
    $objproducts->DeleteproductPrice($_REQUEST['id']);
    header("location:ProductAndSubscriptionPopup.php?&Prodid=".$_REQUEST['Prodid']."&flag=deleteprice");
}
?>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.9.1.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  
<script>
		function modalbox(linkt,setTitle,SetHeight,SetWidth){
		$('<iframe id="some-dialog" class="window-Frame" src='+linkt+' />').dialog({
                    autoOpen: true,
                    width: SetWidth,
                    height: SetHeight,
                    modal: true,
                    resizable: true ,
                                title:setTitle 
                }).width(SetWidth-20).height(SetHeight-20);
              }
             
		
	
	</script>
	
<!-- End of Tabs and button code --> 
<script type="text/javascript">

				
				<? if($ProductSubcRec){  foreach($ProductSubcRec as $ProdSubVal)
	 { ?>
				   $("#ProdSubEdit_").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
			
			/*$('#ProdSubEdit_<?= $ProdSubVal['ID']?>').fancybox({
				'href':$('#ProdSubEdit_<?= $ProdSubVal['ID']?>').attr('href'),
			    'hideOnOverlayClick':false
				});
<? } }  ?>*/
				$("#ProdSubADD").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
					
			
			
			$("#addprice").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });	
						

</script>
<script type="text/javascript">
function confirmation() {
	var answer = confirm("Do you want to delete this record?");
	if(answer){
		return true;
	}else{
		return false;
	}
}

$('#add_form').show();
</script>

</head>
<style type="text/css">
#add_form{
	display:none;
	transition:all 0.7s ease 1s;
}
</style>
<body>




<div >
 
   <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
     
      <tr>
      <td>
       <?php if($_REQUEST['flag'] == "addedprduct"){?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td id="message_success">
                     Product Added successfully!
                  <script>
                    $(document).ready(function(){

                            $("#message_success").fadeOut(3000);
                      });
                    </script>
                </td>

            </tr>
          </table>
    <?php }?> 
      <?php if($_REQUEST['flag'] == "updatedprduct"){?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td id="message_success">
                     Product Updated successfully!
                  <script>
                    $(document).ready(function(){

                            $("#message_success").fadeOut(3000);
                      });
                    </script>
                </td>

            </tr>
          </table>
    <?php }?>    
          
      <!--PROGAMMERS FROM STEVE
      All styles below need to be replaced with the right styles from styles.css - they already exist
      NOTE: EVERY FILE YOU FIND THIS KIND OF THINGS IN - I WANT IT REPLACED AND CLEANED UP.
      REMOVE THIS COMMENT WHEN DONE.
      -->
      <?php if($_REQUEST['flag']=='deleteprice'){?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
     Price Deleted successfully!
	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
  </table>
    <?php }?>
      <?php if($_REQUEST['flag']=='add'){?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
     Add Product subscription successfully!
	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
  </table>
    <?php }?>
      <?php if($_REQUEST['flag']=='priceadd'){?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
     Add Product Price successfully!
	  <script> 
			$(document).ready(function(){
  
       	 $("#message_success").fadeOut(3000);
  	});
	
	
</script>
    </td>
    
  </tr>
  </table>
    <?php }?>	
    
      </td>
      </tr>
    </table>
	
	<div class="Popupspace"></div>
	<div id="tabs" >
            <ul>
                    <li><a href="#tabs-1">Product Information</a></li>
                    <li><a href="#tabs-2">Subscriptions</a></li>
                    <!--<li><a href="#tabs-3">Merchant Accounts</a></li>-->
            </ul>
	<div id="add_form" class="filtercontainer" style="padding:10px 0px 0px 0px; margin-top:10px;">
	<form action="?Prodid=<?php echo $_REQUEST['Prodid'];?>&Task=AddProductPrice"method="post"  enctype="multipart/form-data" name="frmSample" onSubmit="return ValidateForm(this);">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

                   <tr>
                   <td id="tdleft"> Price:</td>
                   <td id="tdmiddle"><input name="ProductPrice" type="text" class="product" size="10" /></td>
				   <td id="tdright"><div align="center"><input type="submit" name="Submit" value="Add Product Price" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" /></div> </td>
                 </tr>
           
                     </table>
        </form>

</div>

<div id="tabs-1">
<form  method="post" action="" >
    <?php //print_r($_REQUEST);?>
<input name="ProductTask" type="hidden" value="<?php echo $Task;?>" size="40" >
<input name="id" id="Pid" type="hidden" value="<?php echo $_REQUEST['Prodid'];?>" size="40"> 
  <table width="100%" border="0" cellspacing="2" cellpadding="0" align="center" >
  <?php if($_REQUEST['Prodid'] > 0){ ?>
  	<tr>
         <td colspan="3">&nbsp;</td>
	</tr>
	<tr>	 
            <td id="tdleft">&nbsp;</td>
            <td id="tdmiddle">&nbsp;</td> 
            <td id="tdright"><input id="AddNewSize" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" value="Add New Price" /></td>
        </tr>
<?php } ?>
		
		<script> 
		$(document).ready(function(){
 				 $("#AddNewSize").click(function(){
    			$("#add_form").slideToggle("slow");
				
 		 });
});
</script>

		<tr>
    <td colspan="3">&nbsp;</td></tr>


    <tr>
	  <td id="tdleft">Product Name:</td>
      <td id="tdmiddle"><input name="Product_Name"  id="Product_Name" type="text" value="<?php echo $ProductRec['ProductName']; ?>" class="product"></td>
	  <td id="tdright">&nbsp;</td>
    </tr>
	<tr>
      <td>Description:</td>
      <td cellpadding="0"><textarea name="Prod_Description" rows="3"><?php echo $ProductRec['Description']; ?></textarea></td>
	  <td>&nbsp;</td>
    </tr>
    <?php if($_REQUEST['Prodid'] > 0){ 
            $price_array = $objproducts->GetAllProductPrice("ProductID ='".$_REQUEST['Prodid']."' ORDER BY ProductPrice ASC ",array("*"));
                foreach((array)$price_array as $price){ 
                //print_r($price);
                ?>
                <tr>
                    <td>Product Price:</td>
                    <td><input name="ProductPrice[<?php echo $price['ID'];?>]" type="text" value="<?php echo $price['ProductPrice']; ?>" ><span> Default <input name="defaultprice" type="radio" value="<?php echo $price['ID'];?>" <?php if($price['DefaultPrice']== 1){echo" checked";}?>></span><a href="ProductAndSubscriptionPopup.php?id=<?php echo $price['ID'];?>&Task=deleteprice&Prodid=<?php echo $_REQUEST['Prodid'];?>" onClick="return confirmation();"> <img title="Delete From Database" src="../../images/icon_delete.png" border="0"/></a></td>
                    <td>&nbsp; </td>
                </tr>
                <?php 
                }
	} else{
	?>
         <tr>
            <td>Product Price:</td>
            <td><input name="ProductPrice" type="text" value="<?php echo number_format($price['ProductPrice'],2); ?>" ></td>
            <td>&nbsp; </td>
        </tr>
        <?php }?>
	<tr>
        <td>Task:</td>
        <td><select name="TaskAttached" class="product">
	  <?php  echo $taskatachedid=$ProductRec['TaskAttached'];?>
		 <option value="">Please Select One</option>
		 <?php foreach((array)$tasks_array as $task){?>
		 	<option value="<?php echo $task['ID'];?>"<?php if($task['ID']==$taskatachedid){echo "selected"; }?>><?php echo $task['TasksTitle'];?></option>
			<?php } ?>
		 </select> 
        </td>
        <td>&nbsp;</td>
    </tr>
      <tr>
      <td colspan="3" height="5" id="errorEmail" style="color:#FF0000;" ></td>
    </tr>
      <tr>
      <td   valign="top">Category:</td>
      <td>
      <div style="height:100px;width:432px;overflow:auto; border:1px solid #DBDBDB; border-radius:5px; padding-left:10px; padding-top:5px;" id="ScrollCB">
      <?php 
	  /*-----------array recursive function---------*/
	  
	 
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
      endforeach;?>      </td>
	<td>&nbsp;</td>
    </tr>
    

      <tr>
        <td  >Commission % on Sale:  </td>
        <td><input name="ProductPrecentage"  id="ProductPrecentage" type="text" value="<?php echo number_format($ProductRec['ProductPrecentage'],2); ?>" class="product"></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
      <td  >Show on Order Form:</td>
      <td>
          <input type="checkbox" name="ShowOnOrderForm" id="ShowOnOrderForm" value="1" <?php if ($ProductRec['ShowOnOrderForm'] == 1) { ?> checked="checked"<?php } ?>
      title="" />      </td>
	  <td>&nbsp;</td>
    </tr>
	<tr>
      <td  >Show Promotion Codes While Selling:</td>
      <td>
          <input type="checkbox" name="Showcoupn" id="Showcoupn" value="1" <?php if ($ProductRec['ShowCoupn'] == 1) { ?> checked="checked"<?php } ?>
      title="" />      </td>
	  <td>&nbsp;</td>
    </tr>
      </tr>
          <tr>
          <td colspan="3"  id="errorEmail" style="color:#FF0000;" ></td>
        </tr>
    </table>
 </div>    
	 <?php 
	 if(!empty($_REQUEST['Prodid'])){
 $strWhere='ProductID='.$_REQUEST['Prodid'].'';
 $ProductSubcRec=$utilObj->getMultipleRow('ProductSubscription', $strWhere);

if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
}
if($_SESSION['page'] > 0 && !isset($_REQUEST['page'])){
  $page = $_SESSION['page'] ;
  $_SESSION['page'] = "";
  unset($_SESSION['page']);
}elseif(!isset($_REQUEST['page'])) {
  $page=1;
  $_SESSION['page'] = 1 ;
} else {
  $page=$_REQUEST['page'];
  $_SESSION['page'] = $page; 
}

$total_records =  count($ProductSubcRec);
/*if(!isset($_REQUEST['export']) && $_REQUEST['export'] != '1'){*/
 if(!isset($_SESSION['limit'])){
	$limit = 10 ;
} else if($_SESSION['limit'] =="all" ){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'];
} 
/*} else {
$limit = $total_records;
}*/
if($_REQUEST['Tasksub']!='1')
 {
// $strWhere='ProductID='.$_REQUEST['Prodid'].' LIMIT 0, 10';
$page="";
  $_SESSION['page'] = "" ;
  $limit = 10 ;
  $_SESSION['limit']=$limit;
  $offset = 0;
 }
 
$ret = $objGroups->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}
 
 //else {
$strWhere='ProductID='.$_REQUEST['Prodid'].' LIMIT '.$offset.','.$limit;
//}


$ProductSubcRec=$utilObj->getMultipleRow('ProductSubscription',$strWhere);
$showstring="Tasksub=1";
 }?>	
 	
	<div id="tabs-2">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"  >
	
 
  </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"  >
     <tr>
         <td colspan="5">
         <div align="right">
             <a  href='ProductAndSubscriptionAddSubPopup.php?Task=add&Productid=<?php echo $_REQUEST['Prodid'];?>'><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" id="Addnew" value="Add Product Subscription"></a>
         </div>
		 
         </td>
        </tr>
	<tr>
    <td colspan="5">&nbsp;</td></tr>
     <tr id="headerbarpopup">
     <td>Subscription Price</td>
     <td>Bill Every</td>
     <td>Days Before Start</td>
     <td>Duration</td>
     <td  class ="Action">Action</td>
	 </tr>
     <tr>
     <?php 
	 $color=1;
if(count($ProductSubcRec)>0){

	 
	 foreach($ProductSubcRec as $ProdSubVal)
	 {
	 if($color%2==0)
$colors='row-tan';
else
$colors='row-white';

	 
	 
	 if($ProdSubVal["BillEvery"]=='1')
		{
		$Every="Month";
		}
		elseif($ProdSubVal["BillEvery"]=='2')
		{
		$Every="Year";
		}
		elseif($ProdSubVal["BillEvery"]=='3')
		{
		$Every="Week";
		}
		if($ProdSubVal["Duration"]=="0")
		{
		$Duration=" Does Not End";
		}
		elseif($ProdSubVal["Duration"]=="30")
		{
		$Duration=$ProdSubVal["Duration"]." Days";
		}
		else $Duration=$ProdSubVal["Duration"]." Months";
	 ?>
     <tr id="<?php echo $colors; ?>">
     <td> <?php echo $ProdSubVal['SubscriptionPrice'];?></td>
     <td> <?php echo  $Every; ?></td>
     <td> <?php echo $ProdSubVal['DaysBeforeStart'];?></td>
     <td> <?php echo $Duration; ?></td>
     <td align="right"><a href='ProductAndSubscriptionAddSubPopup.php?Task=update&ProdSubid=<?= $ProdSubVal['ID']?>&Productid=<?php echo $_REQUEST['Prodid'];?>' id='ProdSubEdit_<?=$ProdSubVal['ID']?>' /> <img border="0" title="Edit Category" src="../../images/icon_page_edit.png">
	 
	 <a href='ProductAndSubscriptionPopup.php?Task=del&ProdSubid=<?= $ProdSubVal['ID']?>&Prodid=<?php echo $_REQUEST['Prodid'];?>' id='ProdSubEdit_<?=$ProdSubVal['ID']?>'onclick="return confirmation();" />
<img border="0" src="../../images/icon_delete.png" title="Delete Subscription"></a>

	 </td>
     </tr>  
     <?php $color++; }?>
     
      <?php  
 }else{
?>
    <tr id="<?php echo $colors; ?>">
        <td  colspan="5" style="text-align:center;">&nbsp;</td>
    </tr>
     <tr id="<?php echo $colors; ?>">
      <td  colspan="5" style="text-align:center;">No Product Subscription Found</td>
    </tr>
    <tr id="<?php echo $colors; ?>">
        <td  colspan="5" style="text-align:center;">&nbsp;</td>
    </tr>
    <?php }  ?>  
    </table>
    <div align="center">

<?php include "../../lib/bottomnav.php" ?>

</div>
    
    </div>
     
	 <?php
	 if(!empty($_REQUEST['Prodid'])){
	  $strWhere='ProductID='.$_REQUEST['Prodid'].'';
 $ProductPromoRec=$utilObj->getMultipleRow('ProductPromoCode', $strWhere);
 //$Show="3";
 
 if(isset($_REQUEST['record_perpage'])){
	$_SESSION['limit'] = $_REQUEST['record_perpage'];
}
if($_SESSION['page'] > 0 && !isset($_REQUEST['page'])){
  $page = $_SESSION['page'] ;
  $_SESSION['page'] = "";
  unset($_SESSION['page']);
}elseif(!isset($_REQUEST['page'])) {
  $page=1;
  $_SESSION['page'] = 1 ;
} else {
  $page=$_REQUEST['page'];
  $_SESSION['page'] = $page; 
}

$total_records =  count($ProductPromoRec);
if(!isset($_REQUEST['export']) && $_REQUEST['export'] != '1'){
 if(!isset($_SESSION['limit'])){
	$limit = 10 ;
} else if($_SESSION['limit'] =="all" ){
	$limit = $total_records;
} else {
	$limit = $_SESSION['limit'];
} 
} else {
$limit = $total_records;
}
if($_REQUEST['Taskpromo']!='3')
 {
 //$strWhere='ProductID='.$_REQUEST['Prodid'].' LIMIT 0, 10';
 $page="";
  $_SESSION['page'] = "" ;
  $limit = 10 ;
  $_SESSION['limit']=$limit;
  $offset = 0;
 }
 
$ret = $objGroups->getPagerData($total_records , $limit, $page);
$offset = $ret->offset;
if( $offset < 1 ){
	$offset = 0;
}
$strWhere='ProductID='.$_REQUEST['Prodid'].' LIMIT '.$offset.','.$limit;
$ProductPromoRec=$utilObj->getMultipleRow('ProductPromoCode',$strWhere);
$showstring="Taskpromo=3";
 }?>

    </div>
 
	 <table border="0" width="100%" cellspacing="1" cellpadding="1" style="margin-top:20px;">
      <tbody><tr>
        <td align="left">&nbsp;</td>
        <td >
		<div align="center">
		 <input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onClick="return spon_check()" />      
        </div>
          	   

        </td>
        <td>&nbsp;</td>
      </tr>
    </tbody></table>
	 </div>
          </form>

<div id="Modal_Product" title="Alert" style=" display:none;">
<strong>Please Enter Product Name On Product Information Tab, Then Add Subscription And Save It.</strong></div>
</body>
</html>

<?php 
function in_array_r($needle, $haystack, $strict = true) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}
?>
