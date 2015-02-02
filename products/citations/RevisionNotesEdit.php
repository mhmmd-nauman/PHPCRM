<?php
include dirname(__FILE__)."/lib/include.php";
$objmember = new Member();
$revision_array = $objmember->GetAllMemberWithRevision(" Revision.MemberID = '".$_REQUEST['id']."' AND Revision.ID='".$_REQUEST['rid']."' ORDER BY CurrentDate DESC",array("Revision.*","Member.FirstName"));
$revisionrow = $revision_array[0];

?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link href="<?php echo SITE_ADDRESS;?>css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
 
 	   
<script type="text/javascript" language="javascript">
function ValidateForm(){
//return true;
if ((document.frmSample.cname.value==null)||(document.frmSample.cname.value=="")){
		alert("Please Enter Business Name")
		document.frmSample.cname.focus()
		return false;
	}
 if ((document.frmSample.fName.value==null)||(document.frmSample.fName.value=="")){
		alert("Please Enter First Name")
		document.frmSample.fName.focus()
		return false;
	}
 if ((document.frmSample.sureName.value==null)||(document.frmSample.sureName.value=="")){
		alert("Please Enter Sure Name")
		document.frmSample.sureName.focus()
		return false;
	}

}


 //);

</script>



<script>
  $(document).ready(function() {
    
	$("#timepicker").datepicker();

  });
 
  </script>

		
<style type="text/css">
<!--
.heading{padding:4px 4px 4px 4px;background-color:#EEEEEE;font-size:14px; font-weight:bold;}
-->
table tr td { font-size: 13px;}
</style>
<div align="center">
<form action="MembersEdit.php?id=<?php echo $_REQUEST['id'];?>&rid=<?php echo $revision_array[0]['ID'];?>&kid=<?php echo $_REQUEST['kid'];?>&Task=RUpdateAjaxRevision" method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">

<table width="100%" border="0" cellspacing="0" cellpadding="2"align="center"  >
     
    
        <tr>
        <td  colspan="6" id="name" >Edit Revision Notes</td>
        </tr>
         <tr>
          <td colspan="2" class="heading" align="left">Revision Information</b> </td></tr>
	  <tr>
                     <td colspan="2"  align="left">&nbsp; </td>
	  </tr>    
                
	 <?php
   if($revisionrow){
   
  		   ?>
        
      <tr >
   
   <td width="15%"><p>Edit Notes:</p></td>
   
   <td>
       <textarea name="RevisionNote" id="textarea" cols="80" rows="15"><?php echo $revisionrow['RevisionNote'];?></textarea>
   </td>
   </tr>
  <tr>
             <td colspan="2"  align="left">&nbsp; </td>
  </tr>
   <tr>
       <td colspan="2" align="center">
           <input type="hidden" name="postback" value="1" />
           <input type="submit" name="RUpdateAjaxRevision" value="Update Revision" class="MOSGLButton"  onclick="return spon_check()" />&nbsp;<input type="button" value="Go Back" onclick="goBack()" class="MOSGLButton">
       </td>
   </tr>  
 <?php
  
 }else{
?>
   
   <tr>
       <td colspan="2" align="center">No Revision Found</td>
   </tr>  
   
 <?php
 
 }
 ?>
   
    </table>
               
</form>


<script type="text/javascript">
function goBack(){
    window.history.go(-1);
 }
                function UpdateRevision(ID){


                             var chkId = '';
                                      
                              //var checked = $("input[@id=Done" + ID + "]:checked");
                              var checked =$('#Done' + ID).is(":checked");
                              //alert(checked);

                      $.ajax({
                   type:"post",
                   url:"Members.php",

                                           data:"ID="+ID+"&checked="+checked+"&Task=UpdateAjaxRevision",
                                               success:function(data){
                   alert(data);
                   }

               });

         }
</script>