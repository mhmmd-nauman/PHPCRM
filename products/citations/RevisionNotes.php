<?php
include dirname(__FILE__)."/lib/include.php";
$objmember = new Member();



?>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link href="<?php echo SITE_ADDRESS;?>css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/Tooltip/tooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>js/Tooltip/tooltip.css" media="screen" />
 <script type="text/javascript">
$(document).ready(function() {

$(".MemberApp_Popup").fancybox({

			'width'                       : 870,

			'height'                      : 520,

			'autoScale'                   : false,

			'transitionIn'                : 'none',

			'transitionOut'               : 'none',

			'href'                        : this.href,

			'type'                        : 'iframe'

	});

    $(".hubopus_popup").fancybox({
        'width'                       : 820,
        'height'                      : 520,
        'autoScale'                   : false,
        'transitionIn'                : 'none',
        'transitionOut'               : 'none',
        'href'                        : this.href,
        'type'                        : 'iframe'
    });

});

function confirmation() {
	var answer = confirm("Do you want to delete this record?");
	if(answer){
		return true;
	}else{
		return false;
	}
}
</script>
 	   
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
<form action="RevisionEdit.php?kid=6" method="post" target="_top" enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">

<table width="100%" border="0" cellspacing="0" cellpadding="2"align="center"  >
     
    
              <tr >
                <td  colspan="6" id="name" >Revision Notes</td>
              </tr>
              <tr id="headerbar">
 		<td width="12%" > Date</td>
  		<td >Revision Notes</td>
   		<td width="20%">Name</td>
   		<td width="20%">Added By</td>
   		<td width="3%">Done</td>
   		<td width="3%">Actions</td>
	      </tr>
                 
               
                   
				   <?php
  
  //$revision_array = $objMember->GetAllRevision(" MemberID = '".$_REQUEST['id']."'",array("*"));

$revision_array = $objmember->GetAllMemberWithRevision("  Revision.HasDeleted = 0 AND Revision.Done = 0 AND Member.HasDeleted=0 ORDER BY CurrentDate ASC",array("Revision.*","Member.FirstName"));
 

   if($revision_array){
   foreach((array)$revision_array as $revisionrow){
  		$Members_array_client = $objmember->GetAllMember("ID = '".$revisionrow['MemberID']."'",array("*"));
		$Members_array_by = $objmember->GetAllMember("ID = '".$revisionrow['ByID']."'",array("*"));
	 if($flag==0){
        $flag=1;
        $row_class="row-white";
        }else{
        $flag=0;
        $row_class = "row-tan";}
   ?>
   <tr id="<?php echo $row_class;?>">
   <td><?php echo date("<b>M d</b>, Y",strtotime($revisionrow['CurrentDate']));?></td>
   <td><?php if(strlen($revisionrow['RevisionNote'])<=40) echo $revisionrow['RevisionNote']; else {?>
     <span class="tooltip" onmouseover="tooltip.pop(this, '<?php  {echo $revisionrow['RevisionNote'];}?>')"><?php $newstring = substr($revisionrow['RevisionNote'],0,40);
echo $newstring."...";?></span><?php }?></td>
   
   <td align="left"><?php echo $Members_array_client[0]['CompanyName']." ".$Members_array_client[0]['FirstName']." ".$Members_array_client[0]['Surname'];?> </td>
   <td><?php echo $Members_array_by[0]['FirstName']." ".$Members_array_by[0]['Surname'];?></td>
   <td align="center"><input name="checkbox" type="checkbox" id="Done<?php echo($revisionrow['ID']);?>"  onclick="UpdateRevision('<?php echo($revisionrow['ID']);?>');" value="1" <?php if($revisionrow['Done']==1){echo"checked";}?> />   
   <td align="center"><a class="hubopus_popup" href="RevisionNotesEdit.php?id=<?php echo $revisionrow['MemberID'];?>&amp;rid=<?php echo $revisionrow['ID']; ?>&kid=6"> <img src="images/icon_page_edit.png" border="0" title="Edit Details"/></a> 
   
   <a href="MembersEdit.php?id=<?php echo $_REQUEST['id'];?>&amp;rid=<?php echo $revisionrow['ID'];?>&amp;kid=6&amp;Task=del" onclick="return confirmation();"> <img title="Delete From Database" src="images/icon_delete.png" border="0"/></a> </td>
   </tr>
 <?php
   } 
 }else{
?>

   <tr>
        <td colspan="6"  align="left">&nbsp; </td>
   </tr>
   <tr>
       <td colspan="6" align="center">No Revision Found</td>
   </tr>  
   <tr>
        <td colspan="6"  align="left">&nbsp; </td>
   </tr>
 <?php
 
 }
 ?>
    </table>
                    </td>
                   </tr>
                   <tr valign="top">
                    <td align="center" colspan="2">
                    <div align="center" style="float: left;  bottom:0; background: none repeat scroll 0 0 #FFFFFF;  width: 100%; margin-top: 30px;">
                      <input type="hidden" name="oldimage"  value="3"/></div>
                        
                         
            

       </td>
<input type="hidden" name="postback" value="1" />
     </tr>
	
	 
    
  </table>   
</form>
</div>	

<script type="text/javascript">
   
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
