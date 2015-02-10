<?php 
include "../lib/include.php";

$objusers = new Users();
$Group_Member_List=$objusers->GetAllUserWithGroup("Group_Users.GroupID = '".$_REQUEST['id']."' AND HasDeleted = 0",array("*"));
//print_r($_REQUEST);
?>


<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css" />
<script src="<?php echo SITE_ADDRESS;?>js/jquery-1.10.2.min.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
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
<script type="text/javascript">
		
		$(function() {
		   $("#PackageProducts").click(function(e){
                              e.preventDefault();	
                              modalbox(this.href,this.title,550,800);
                    });
         
             });
			
		
            
</script>
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
table tr td { font-size: 12px;}
</style>
<?php //echo $Task;?>
<form action="?id=<?php echo $_REQUEST['id'];?>&Task=<?php echo $Task;?>" target="_top"  method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);"> 
	<div class="Popupspace"></div>			
	<div class="containerpopup">
		
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                   <td width="25"  colspan="2" id="tabsubheading"> Group Member List </td>
                 </tr>
                     <tr valign="top">
                   <td   colspan="2">&nbsp;  </td>
                 </tr>
                     </table>
						<table width="100%" border="0" cellspacing="1" cellpadding="1"> 
                  		  <tr>
                         <td  colspan="2" >
                         </td>
                    </tr>
                    <tr>
                        <td>
                   <table width="100%" border="0" cellspacing="0" cellpadding="1">
                   <tr id ="headerbarpopup">
					<td >ID</td>
					<td >Business</td>
					<td >Name</td>
					<td >Email</td>
					<td>Phone</td>
					</tr>
                    <?php 
                    if($Group_Member_List){?>
				   <?php
	                   foreach((array)$Group_Member_List as $ProductToPackge){
	
                                if($flag==0){
                                    $flag=1;
                                    $row_class="row-white";
                                }else{
                                    $flag=0;
                                    $row_class = "row-tan";
                                }  
	   	  	
                 ?>
	                       <tr id="<?php echo $row_class;?>">
                           <td ><?php echo $ProductToPackge['ID']; ?></td>
                            <td ><?php echo $ProductToPackge['CompanyName'];?></td>
                            <td ><?php echo $ProductToPackge['FirstName']." ".$ProductToPackge['Surname'];?></td>
                            <td><?php echo $ProductToPackge['Email'];?></td>
                            <td ><?php echo $ProductToPackge['Phone'];?></td>
                     </tr>
							<?php 
                        
                           }?>
							<?php }else{ ?>
							<tr >
							  <td colspan="5" align="center">No Member in Group </td>
							</tr>
                            <?php }?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
  								<td>&nbsp;</td>
  								<td>&nbsp;</td>
                            </tr>
                            
                            
							 <tr>
                              <td colspan="5">&nbsp;</td>
                             </tr>
                        </table>
                      </td>
                     </tr> 
                </table>
  </div>
		<div style="height:25px;">&nbsp;</div>
</form>


