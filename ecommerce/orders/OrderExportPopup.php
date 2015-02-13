<?php 
include "../../lib/include.php";

?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>

<style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 13px;}
</style>
<form action="csv.php?id=<?php echo $_REQUEST['id'];?>&Task=ExportOrders" target="_top" method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">


<div class="subcontainer">
    <div>
		 <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
                 
				<tr>
                         <td width="40%">&nbsp;</td>
                         <td width="5%"><input type="checkbox" name="All" value="All" checked="checked" id="SelectAll1" class="MemberSelectcheckBox1" /></td>
			 <td><strong>Field Name</strong>:</td> 
                                                  
				 </tr>	
				
				  <tr>
                          <td >&nbsp;</td>
                         <td>
                             <input type="checkbox" name="FirstName" value="1" id="FirstName" class="MemberSelectcheckBox1" checked="checked"></td>
				   <td>First Name</td>
                                                                                      
				 </tr>			       
				 <tr>
                                      <td >&nbsp;</td> 
                         <td><input type="checkbox" name="Surname" value="1" id="Surname" class="MemberSelectcheckBox1" checked="checked"></td>
						 <td>Surname</td> 
                                                
				 </tr>					  
				 <tr>
                                     <td>&nbsp;</td>
                          <td><input type="checkbox" name="Email" value="1" id="Email" class="MemberSelectcheckBox1" checked="checked"></td>
						 <td>Email</td>
                                                                                                   
				 </tr>	
				 <tr>
                                      <td>&nbsp;</td> 
                         <td><input type="checkbox" name="Phone" value="1" id="Phone" class="MemberSelectcheckBox1" checked="checked"></td>
						 <td>Phone</td> 
                                                
				 </tr>
				<tr>
                                     <td>&nbsp;</td> 
                        <td><input type="checkbox" name="StreetAddress1" value="1" id="StreetAddress1" class="MemberSelectcheckBox1" checked="checked"></td>
						 <td>Street Address1</td>
                                                                                                
				 </tr>
				<tr>
                                     <td>&nbsp;</td> 
                         <td><input type="checkbox" name="StreetAddress2" value="1" id="StreetAddress2" class="MemberSelectcheckBox1" checked="checked"></td>
						 <td >Street Address2</td> 
                                                 
				 </tr>
				<tr>
                                     <td>&nbsp;</td>  
                         <td><input type="checkbox" name="Price" value="1" id="Price" class="MemberSelectcheckBox1" checked="checked"></td>
						 <td>Price</td> 
                                                
				 </tr>
				<tr>
                                    <td>&nbsp;</td> 
                         <td><input type="checkbox" name="TotalPrice" value="1" id="TotalPrice" class="MemberSelectcheckBox1" checked="checked"></td>
						 <td>Total Price</td> 
                                                  
				 </tr>
				 <tr>
                                     <td>&nbsp;</td>  
                         <td><input type="checkbox" name="Created" value="1" id="Created" class="MemberSelectcheckBox1" checked="checked"></td>
						 <td>Order Date</td> 
                                                 
				 </tr>
                  <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                     </table>
        </div>
  </div>

	   <div style="height:25px;">&nbsp;</div>
	   <table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
		<tr>
       <td colspan="3" style=" border: none;">
	   <div align="center">
		<input type="submit" name="Submit" value="Export & DownLoad" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()"/>
			<input type="hidden" name="oldimage"  value=""/>
          	 <input type="hidden" name="postback" value="1" />
        	<input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" />
			</div>
       </td>
		</tr>
		</table>
</form>
<script type="text/javascript">

	$("#SelectAll1").click(function(){

		if($(this).is(':checked')){
	
			//$(".MemberSelectcheckBox1").attr('checked','checked');
			 $(".MemberSelectcheckBox1").prop('checked', $(this).prop('checked'));

		}else{

			$(".MemberSelectcheckBox1").removeAttr('checked');

		}

	});
	</script>
