<?php 
include "../lib/include.php";
$objcompany = new Company();
$objmerchant = new MerchantAccount();
# Fetch the details of the company which is to be edited
$Company_array = $objcompany->GetAllCompany("ID = '".$_REQUEST['id']."'",array("*")); 

$merchants = $objmerchant->GetAllMerchantAccount("1",array("*"));

if($_REQUEST['Task'] == 'AddSave'){
	if($_FILES['invoicelogo']['size'] > 0){
		$invoice_logo = "invoice_logo/";
		$invoice_logo = $invoice_logo . basename( $_FILES['invoicelogo']['name']);
		move_uploaded_file($_FILES['invoicelogo']['tmp_name'], "../".$invoice_logo);
	}
	$Company_array = $objcompany->GetAllCompany("Email= '".$_REQUEST['email']."'",array(COMPANY.".ID","Email"));
	if($Company_array[0]['Email'] != $_REQUEST['email']){
		$objcompany->InsertCompany(array(
			"CompanyName"           => $_REQUEST['CompanyName'],   
			"CompanyDescription"    => $_REQUEST['CompanyDescription'],
			"Address1"              => $_REQUEST['address1'],
			"Address2"              => $_REQUEST['address2'],
			"City"                  => $_REQUEST['city'],
			"State"                 => $_REQUEST['state'],
			"Zip"                   => $_REQUEST['zip'],
			"Phone"                 => $_REQUEST['phone'],
			"Email"                 => $_REQUEST['email'],
			"InvoiceImage"          => $invoice_logo,
			"MerchantID"            => $_REQUEST['MerchantAttached']
		));	
		header("Location:".SITE_ADDRESS."groups/Company.php?flag=success");        
	}else{
	header("Location:".SITE_ADDRESS."groups/Company.php?flag=email_exist");  
	}    
}

if($_REQUEST['Task'] == 'UpdateSave'){
	$companyid = $_REQUEST['id'];
	if($_FILES['invoicelogo']['size'] > 0){
		$invoice_logo = "invoice_logo/";
		$invoice_logo = $invoice_logo . basename( $_FILES['invoicelogo']['name']); 
		move_uploaded_file($_FILES['invoicelogo']['tmp_name'],"../".$invoice_logo);
	} else{
		$invoice_logo = $Company_array[0]['InvoiceImage'];
	}

	$updated = $objcompany->UpdateCompany("ID = '$companyid' ",array(
		"CompanyName"           => $_REQUEST['CompanyName'],   
		"CompanyDescription"    => $_REQUEST['CompanyDescription'],
		"Address1"              => $_REQUEST['address1'],
		"Address2"              => $_REQUEST['address2'],
		"City"                  => $_REQUEST['city'],
		"State"                 => $_REQUEST['state'],
		"Zip"                   => $_REQUEST['zip'],
		"Phone"                 => $_REQUEST['phone'],
		"Email"                 => $_REQUEST['email'],
		"InvoiceImage"          => $invoice_logo,
		"MerchantID"    	  	=> $_REQUEST['MerchantAttached'],
	));
	header("Location:".SITE_ADDRESS."groups/Company.php?flag=success&id=".$_REQUEST['id'].""); 
}

if($_REQUEST['Task'] == 'Update' ){
	$Task = "UpdateSave";
}else{
	$Task = "AddSave";
}

if($_REQUEST['Task'] == 'del'){
	$objcompany->DeleteCompany($_REQUEST['id']);
	header("Location:".SITE_ADDRESS."groups/Company.php?flag=del");    	
}

if($_REQUEST['Task'] == 'delProduct'){
	$objpackges->DeletePackageProduct($_REQUEST['DelId']);
	header("location:".SITE_ADDRESS."packages/PackagesEdit.php?id=".$_REQUEST['id']."&flag=delete");
}

?>
<!-- Tabs and button code -->
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".message_success").fadeOut(3000);
	$(".message_error").fadeOut(3000);
});
function confirmation() {
	var answer = confirm("Do you want to delete this record?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
	
$(function() {
	$("#PackageProducts").click(function(e){
		e.preventDefault();	
		modalbox(this.href,this.title,550,800);
	});
});

$(function() {
	$( "#tabs" ).tabs();
});
</script>
<style type="text/css">
.heading {
	padding:4px 4px 4px 4px;
	background-color:#EEEEEE;
	font-size:14px;
	font-weight:bold;
}
-->
table tr td {
	font-size: 12px;
}
</style>
<form action="?id=<?php echo $_REQUEST['id']; ?>&Task=<?php echo $Task;?>" target="_top"  method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">
  <?php if($_REQUEST['flag'] == 'delete'){ ?>
  <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
    <tr>
      <td colspan="3" class="message_success"> Company Removed From Packages Successfully!
      </td>
    </tr>
  </table>
  <?php }
  if($_REQUEST['flag'] == 'add'){ ?>
  <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
    <tr>
      <td colspan="3" class="message_success">Company Added In Packages Successfully!</td>
    </tr>
  </table>
  <?php }
  if($_REQUEST['flag'] == 'email_exist'){ ?>
  <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
    <tr>
      <td colspan="3" id="message_error">Email Already Exist!</td>
    </tr>
  </table>
  <?php } ?>
  <div class="subcontainer">
    <table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr >
        <td  colspan="3" id="tabsubheading" >Basic Company Information </td>
      </tr>
      <tr valign="top">
        <td   colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft"> Company Name:</td>
        <td id="tdmiddle"><input name="CompanyName" type="text" value="<?php echo $Company_array[0]['CompanyName'];?>" class="product" /></td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft">Email:</td>
        <td id="tdmiddle"><input name="email" type="text" value="<?php echo $Company_array[0]['Email'];?>" class="product" /></td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr>
        <td > Company Description:</td>
        <td ><textarea name="CompanyDescription" style="height:100px;" class="product"><?php echo $Company_array[0]['CompanyDescription'];?></textarea></td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft"> Merchant:</td>
        <td id="tdmiddle">
        
        <select name="MerchantAttached" class="product">
        	<option value="">Please Slect One</option>
        	<?php foreach((array)$merchants as $merchant){?>
        	<option value="<?php echo $merchant['MerchantId'];?>" <?php if($merchant['MerchantId'] == $Company_array[0]['MerchantID']){ echo "selected"; }?>><?php echo $merchant['AccountName'];?></option>
        	<?php } ?>
        </select>
          </td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr valign="top">
        <td   colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td  colspan="3" id="tabsubheading">Invoice Image </td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td >Invoice Logo:</td>
        <td ><input type="file" name="invoicelogo" id="invoicelogo"></td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td></td>
        <td><img src="../<?php if(!empty($Company_array[0]['InvoiceImage'])){echo $Company_array[0]['InvoiceImage'];} else {echo "../images/my-profile.gif";}?>" width="130" height="130" /></td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr >
        <td  colspan="3" id="tabsubheading" >Contact Information </td>
      </tr>
      <tr valign="top">
        <td   colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft">Address 1:</td>
        <td id="tdmiddle"><input name="address1" type="text" value="<?php echo $Company_array[0]['Address1'];?>" class="product" /></td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft">Address 2:</td>
        <td id="tdmiddle"><input name="address2" type="text" value="<?php echo $Company_array[0]['Address2'];?>" class="product" /></td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft">City:</td>
        <td id="tdmiddle"><input name="city" type="text" value="<?php echo $Company_array[0]['City'];?>" class="product" /></td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft">State:</td>
        <td id="tdmiddle"><input name="state" type="text" value="<?php echo $Company_array[0]['State'];?>" class="product" /></td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft">Zip:</td>
        <td id="tdmiddle"><input name="zip" type="text" value="<?php echo $Company_array[0]['Zip'];?>" class="product" /></td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr>
        <td id="tdleft">Phone:</td>
        <td id="tdmiddle"><input name="phone" type="text" value="<?php echo $Company_array[0]['Phone'];?>" class="product" /></td>
        <td id="tdright">&nbsp;</td>
      </tr>
      <tr valign="top">
        <td   colspan="3">&nbsp;</td>
      </tr>
    </table>
  </div>
  <div style="height:5px;">&nbsp;</div>
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td  colspan="2" style=" border: none;"><div align="center">
          <input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()" />
          <input type="hidden" name="oldimage"  value=""/>
        </div>
        <input type="hidden" name="postback" value="1" />
        <input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" />
        </div>
      </td>
    </tr>
  </table>
</form>
