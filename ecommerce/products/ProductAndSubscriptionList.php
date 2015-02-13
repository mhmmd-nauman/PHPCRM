<?php 
include "../../include/header.php"; 
$utilObj = new util();
$objtasks = new Tasks();
$objproducts = new Products();
if(trim($_REQUEST['SpecialProduct']) == 'yes'){
				$SpecialProduct = '1';
}elseif(trim($_REQUEST['SpecialProduct']) == ''){	
				$SpecialProduct = '0';
}

/*---------------this is product  code-------------*/
//if($_REQUEST['Submit']=='Save' && $_REQUEST['Task']){
	switch($_REQUEST['Task']){
	case"add":
            //echo"here";
	    $arrValue=array('ProductName'=>$_REQUEST['Product_Name'],'Description'=>$_REQUEST['Prod_Description'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ManageMerchantAccID'=>$_REQUEST['MerchantAccount'],'SpecialProduct'=>$SpecialProduct,'SteelePID'=>$_REQUEST['Steele_Pid'], 'CoOPID'=>$_REQUEST['CoopType'],'ShowOnOrderForm'=>$_REQUEST['ShowOnOrderForm'],'ShowCoupn'=>$_REQUEST['Showcoupn'],'TaskAttached'=>$_REQUEST['TaskAttached'],'ProductPrecentage'=>$_REQUEST['ProductPrecentage']);
            //$arrValue=array('ProductName'=>$_REQUEST['Product_Name'],'Description'=>$_REQUEST['Prod_Description'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ManageMerchantAccID'=>$_REQUEST['MerchantAccount'],'SpecialProduct'=>$SpecialProduct,'SteelePID'=>$_REQUEST['Steele_Pid'], 'CoOPID'=>$_REQUEST['CoopType'],'TaskAttached'=>$_REQUEST['TaskAttached']);
			$insertedId=$utilObj->insertRecord('Product', $arrValue);
                        //print_r($arrValue);
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
		 break;
	case"update":
           // echo "it comes here";
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
				$Price_array=$_REQUEST['ProductPrice'];
				foreach((array)$Price_array as $id=>$Price){
				
				 $updated= $objproducts->UpdateProductPrice("ID = $id ",array(
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
//}
$wherecnd=1;
if(!empty($_POST['SearchText'])){
$_SESSION['search_product']=$_POST['SearchText'];
}elseif(!empty($_SESSION['search_product']) && !empty($_POST['SearchText'])){
     //need to umset
	$_SESSION['search_product']= $_SESSION['search_product'];
}else
{
	unset($_SESSION['search_product']);
}
if(!empty($_SESSION['search_product'])){
$wherecnd=" ID='".$_SESSION['search_product']."' OR ProductName LIKE '".$_SESSION['search_product']."%'";
}

$CatRecords=$utilObj->getMultipleRow('Product',$wherecnd);


$strwher=$wherecnd ." Order by ID DESC ";
$CatRecords=$utilObj->getMultipleRow('Product',$strwher);
?>

<html>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<script type="text/javascript">
 $(function() {
                    
                  
                    $("#ProductAdd").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
					
					$(".editBtn").click(function(e) {
						e.preventDefault();	
                        modalbox2(this.href,this.title,550,800);
					});
                    <?php  //foreach((array)$CatRecords as $Cat_row){?>        
                       /*
					   $("#ProductEdit<?php echo $Cat_row['ID'];?>").click(function(e){
                                     e.preventDefault();	
                                     modalbox(this.href,this.title,550,800);
                         });	 
						 $("#ProductToTask<?php echo $Cat_row['ID'];?>").click(function(e){
                                     e.preventDefault();	
                                     modalbox(this.href,this.title,550,800);
                         });	
						*/ 
                <?php //} ?>
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
</script>
<body>
<div id="headtitle">Products</div>

<div class="filtercontainer">
  <table cellpadding="0" cellspacing="0" width="100%" border="0" >
    <tr>
    <form name="TagSearchForm" id="TagSearchForm" action="" method="post">
      <td width="550">
      <div class="adv_search">
          <div class="adv_search_sub">
              <div class="input_box_1">
                <input class="input_box_2_1" type="text" name="SearchText" value="<?php echo $_SESSION['search_product'];?>" />
                <div style="display: block;" data-tooltip="Show search options" aria-label="Show search options" role="button" gh="sda" tabindex="0" class="aoo" id="show_options">
                
                </div>
              </div>
              <div class="adv_btn">
                <input class="adv_btn_2" type="submit" value="&nbsp;" name="Filter">
              </div>
          </div>
          
          
        </div>
        </td>
      <td>
	   <div align="right">

   	<a href="ProductAndSubscriptionPopup.php?Task=add" id="ProductAdd" title="Add New Product"><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" id="Addnew" value="Add New Product"></a>
	

	</div>
      </td>
         </form>  
    </tr>
  </table>
 
  <!---->
</div>



  
    <?php if($Flag=='added') {?>
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Record has been Added Sucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    
    </table>
        
    <?php }else if($Flag=='update') {?>
      
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Record has been Updated Sucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    
    </table>
    <?php } 
	   else if($_SESSION['flag']=='del') {?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Record has been Deleted Sucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    
    </table>
    <?php $_SESSION['flag']='';} 
	   else if($Flag=='SubAdded') {?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Subscription has been Added Sucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    
    </table>
    <?php } ?>
<?php if($_REQUEST['flag']=='del'){?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  <td id="message_success">
      Record has been Deleted Sucessfully!
	  	  <script>
$(document).ready(function(){
  
        $("#message_success").fadeOut(3000);
  });
</script>
    </td>
    
  </tr>
     
    
    </table>
	<?php } ?>
<div class="" style="margin:10px 0px 20px 0px;">
<div class="subcontainer">
  <table cellpadding="2" cellspacing="0"  border="0" width="100%" id="product_List" style="display:none;"class="display">
  <thead>
    <tr id="headerbar" style=" height:46px;">
      <td width="60">ID</td>
      <td width="234" >Product Name</td>
      <td >Description</td>
      <td>Price</td> 
      <td title="Show On Form">Show</td>
      <td class="Action">Actions</td>
    </tr>
	</thead>
	<tbody>
    <?php 

$color=1;
if(count($CatRecords)>0){
foreach($CatRecords as $catcval):
$productid=$catcval['ID'];
 $price_array = $objproducts->GetAllProductPrice("ProductID = $productid  AND DefaultPrice = 1 ",array("*"));

// print_r($price_array);
if($color%2==0)
$colors='row-tan';
else
$colors='row-white';


?>

    <tr id="<?php echo $colors; ?>">
      <td ><?php echo $catcval['ID']; ?> </td>
      <td ><?php echo $catcval['ProductName']; ?></td>
      <td ><?php echo $catcval['Description']; ?></td>
      <td >$<?php echo number_format($price_array[0]['ProductPrice'],2); ?></td>
     <td width="20">
         <?php if($catcval['ShowOnOrderForm'] == 1){
             $path="start.png";
         }else{
             $path="stop.png";
         }
?>
         <img src="../../images/<?php echo $path;?>" />
     </td> 
      <td class="Action">
      
<a class="editBtn" href="ProductAndSubscriptionPopup.php?Task=update&Prodid=<?php echo $catcval['ID'];?>"  id="ProductEdit<?php echo $catcval['ID'];?>" title="Edit <?php echo $catcval['ProductName']; ?>"> <img border="0" title="<?php echo $catcval['ProductName']; ?>" src="../../images/icon_page_edit.png"></a>


<a href="ProductToTask.php?Task=insertTask&Prodid=<?php echo $catcval['ID'];?>"  id="ProductToTask<?php echo $catcval['ID'];?>" title="Task - <?php echo $catcval['ProductName']; ?>"></a> 

  
<a  href="ProductAndCategoryDelete.php?Task=Delete&id=<?php echo $catcval['ID']; ?>" rel="Delete" id="Del_<?php echo $catcval['ID']; ?>" class="deletecat"> 
<img border="0" src="../../images/icon_delete.png" title="Delete Category" onClick="return confirmation();"></a></td>
    </tr>
    <?php $color++; endforeach; 
}else{
?>
    <tr id="<?php echo $colors; ?>">
      <td  colspan="6">No Products Found</td>
    </tr>
	
    <?php }  //?>
  </table>
  &nbsp;
  <br/>
</div>
</div>
<div id="divId" title="Enter Admin Password" style="display:none;">
  <div align="center" style="color:#FF0000;" id="wrongpassword"></div>
    <input type="password" name="adminpassword"  size="30"  id="adminpassword"/>
    <input type="hidden" name="fancyboxid" id="fancyboxid"  value=""/>
    <input type="hidden" name="prodtype" id="prodtype"  value="iframe"/>
    <input type="hidden" name="DeleteRecId" id="DeleteRecId"  value=""/>
    <input type="hidden" name="page" id="page"  value="Product"/>

</div>
<div id="modal_confirm_yes_no" title="Confirm" style=" display:none;"> <strong>Are you sure you want to delete this Product?</strong>
</div>




<div align="center">

<?php //include "../../lib/bottomnav.php" ?>

</div>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />
<script type="text/javascript">
$(document).ready(function(){
	var oTable = $('#product_List').dataTable({
		"iDisplayLength": 10,	
	});
	oTable.fnSort( [ [0,'desc']] );

});
$(window).load(function(){
	$('#product_List').show();
});
</script>
</body>
</html>