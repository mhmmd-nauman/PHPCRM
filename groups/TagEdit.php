<?php 
include "../lib/include.php";
$objtags = new Tags();
$tag=$objtags->GetAllTags("ID='".$_REQUEST['id']."'",array("*"));

if($_REQUEST['Task']=="Add")
{

if(empty($_REQUEST['statusflag'])){
     $flag=$_REQUEST['statusflag']=0;
 }else{
     $flag=$_REQUEST['statusflag'];
 }

$code = $_REQUEST['Title'];
    $grpcode = str_replace(' ', '-', $code);
    $added= $objtags->InsertTag(array(
  				          				   "Created"=>date("Y-m-d H:i:s"),
                                           "LastEdited"=>date("Y-m-d H:i:s"),
                                           "Title"=>$code,
				           				   "TagDescription"=>$_REQUEST['Description'],
                                           "InActive"=>$flag,
                                           "ParentID"=>0,
                                            ));
										   
 header("Location:Tags.php?flag=add");
	 				   
}
if($_REQUEST['Task']=="Update1")
{
$gid = $_REQUEST['id'];
     $_REQUEST['Program']."*";

if(empty($_REQUEST['statusflag'])){
     $flag=$_REQUEST['statusflag']=0;
 }else{
     $flag=$_REQUEST['statusflag'];
 }

 $updated= $objtags->UpdateTag("ID = '$gid' ",array(
                                               "Title"=>$_REQUEST['Title'],
												"TagDescription"=>$_REQUEST['Description'],
                                                "InActive"=>$flag,
												
                                           )); 

header("Location:Tags.php?flag=update");	

}

?>



<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">

<script type="text/javascript" language="javascript">

function ValidateForm(){

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

</head>

<body>
<?php 
if($_REQUEST['id']!=''){
	$task = 'Update1';
}else{
	$task = 'Add';
}

?>  
<!-- Tabs and button code -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>  

<!-- End of Tabs and button code --> 
   
<form action="?Task=<?php echo $task;?>&id=<?php echo $_REQUEST['id'];?>" method="post" target="_top" enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">
      <table width="100%" border="0" cellspacing="3" cellpadding="1" align="center">
        <tr>
          <?php if($_REQUEST['flag']=='update') {?>
          <td style="font-size:14px; font-weight:bold; color:#FF0000;" colspan="2"><?php echo " Record has been Updated Sucessfully! "; ?> </td>
          <?php }  ?>
        </tr>

      </table>
	<div class="Popupspace"></div>
    <div class="containerpopup">

      <table width="100%"  border="0" cellspacing="1" cellpadding="1">

        
		<tr >
				  <td colspan="3" id="tabsubheading" >Tag Information</td>
		</tr>
	
        <tr >

          <td id="tdleft">Title:</td>
          <td id="tdmiddle" ><input name="Title" type="text" value="<?php echo $tag[0]['Title'];?>" class="product"></td>
		  <td id="tdright">&nbsp;</td>
        </tr>
        
        <tr>

          <td>Description:</td>

        <td><textarea name="Description" class="product" style="height:170px;"><?php echo $tag[0]['TagDescription'];?></textarea>  </td>
		<td>&nbsp;</td>
		    </tr>
          
        
           <tr >

          <td >Is Active:</td>

          <td><input type="checkbox" name="statusflag" value="1"<?php if($tag[0]['InActive']==1){echo"checked";}?> checked="checked"/></td>
		  <td >&nbsp;</td>
        </tr>
            
        <?php if($_REQUEST['id']==''){?>
        
        <?php }?>
      </table>
	  
</div>
<div style="height:25px;">&nbsp;</div>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
       <td  colspan="3">
	   <div align="center">
		<input type="submit" name="Submit" value="Save Changes" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="return spon_check()" />
			<input type="hidden" name="oldimage"  value=""/>
          	 <input type="hidden" name="postback" value="1" />
        	<input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" /></div>
       </td>
		</tr>
		</table>

</form>
</body>
</html>

