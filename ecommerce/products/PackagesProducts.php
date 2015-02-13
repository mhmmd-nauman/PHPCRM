<?php //include "../../include/header.php"; 
require_once "../../lib/include.php";
$objpackges = new Packges();
$utilObj = new util();
$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
$strwher=1;
$Product_Array=$utilObj->getMultipleRow('Product',$strwher);

$Packge_array = $objpackges->GetAllPackges("1 ORDER BY Created ASC",array("*")); 


//print_r($Product_Array);
//exit;
?>

<script type="text/javascript" src="js/search.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/search.css" />
<link rel="stylesheet" type="text/css" href="../../css/styles.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="<?php echo SITE_ADDRESS;?>css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />

<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Tooltip/js/tooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>js/Tooltip/css/tooltip.css" media="screen" />

<!--<script type="text/javascript" src="js/search.js"></script>
<link rel="stylesheet" type="text/css" href="css/search.css" />-->
<script type="text/javascript">
$(document).ready(function() {
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


<script type="text/javascript">
		$(function() {
			$("#fromdatepicker").datepicker();
		});
		$(function() {
			$(".fromdatepicker").datepicker();
		});
		$(function() {
			$("#todatepicker").datepicker();
		});
		$(function() {
			$(".todatepicker").datepicker();
		});
</script>


<script type="text/javascript">
$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	jQuery("#show_options").show();
	$('#changename').attr('name', '');
});
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td  colspan="2" id="name" style="background-color:<?php echo "#".$SystemSettingsArray[0]['PopupColor']; ?>"><b> Products Assign to Packge </b></td>
	</tr>
	</table>
<div class="subcontainer">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  
 
    
  <tr>
                         <td  colspan="2">
                             
                               <div id="ScrollCB" style="width:550px;overflow:auto;border:0px solid #E4DBC5; float:left; margin-right:0px;">

                   <?php
                   //print_r($member_in_gropus);
                   for($i=0;$i<count($Product_Array);$i++){
                       //print_r($Product_Array);

                       ?>
                         <input type="checkbox" id="packagesProducts<?php echo $Product_Array[$i]['ID'];  ?>" value="<?php echo $Product_Array[$i]['ID'];  ?>" name="packagesProducts[]" >
                                <label for="packagesProducts<?php echo $Product_Array[$i]['ID'];?>"> <?php echo $group_array[$i]['ID'];?> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Product_Array[$i]['ProductName']; ?>                                                    </label>
                                <br />
                                <?php }?>
                      </div>							  </td>
                     </tr>
					 <tr><td colspan="2">&nbsp;</td></tr>
					<tr>
       <td align="center" colspan="2">
           <div align="center" style="float: left; margin-top:10px; bottom:0; background: none repeat scroll 0 0 #FFFFFF;  width: 100%;">
	   <input type="submit" name="Submit" value="Save Changes" class="MOSGLButton" style="height:29px;" onclick="return spon_check()" id="javed" />
	   <!--<input type="button" id="deleteID" name="delete" value="Delete" class="MOSGLButton" style="height:29px;" onclick="return deletedataPost('<?php echo $_REQUEST['id'];?>')" />-->
           <div id="delwaitmsg" style="margin-left:165px;"></div>
																																		<input type="hidden" name="oldimage"  value=""/></div>
           <input type="hidden" name="postback" value="1" />
        <input type="hidden" name="page" value="<?php echo $_SESSION['page'];?>" />
       </td>
        </tr> 
					 

</table>
<div align="center">
<?php include "../../lib/bottomnav.php" ?>
<script type="text/javascript">

	$("#SelectAll").click(function(){

		if($(this).is(':checked')){

			$(".MemberSelectcheckBox").attr('checked','checked');

		}else{

			$(".MemberSelectcheckBox").removeAttr('checked');

		}

	});


</script>
</div>

<? include "../../include/footer.php" ?>
