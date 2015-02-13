<?php include "include/header.php"; 
$utilObj = new util();


if(isset($_REQUEST['SearchText'])){
	$_SESSION['SearchText'] = trim($_REQUEST['SearchText']);
}
$search = "1";
if(isset($_SESSION['SearchText']) && $_SESSION['SearchText'] != ''){
 $search = " CoOPName LIKE '%".$_SESSION['SearchText']."%'" ;
}

$allrec = $utilObj->getMultipleRow("CoOP","$search ORDER BY ID ASC");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript" src="js/search.js"></script>
<link rel="stylesheet" type="text/css" href="css/search.css" />
<script type="text/javascript">
$(document).ready(function() {
	$(".AddShare").fancybox();
	$(".AddCoOP").fancybox();
	
	<?php foreach((array)$allrec as $PRow){
		?>
		$("#sharepopup<?php echo $PRow['ID'];?>").fancybox({
		
		'height' : 700,
		'width'  : 1300,
		'autoScale' : false

		});
				
		<?php } ?>
});


function deletedata(){
		var passW = prompt("Please enter password","Password");
		if(passW == "09polkmn"){
			document.frmSample.submit();
			//alert('hello');
		}else{
			alert("Password does not match!");
			return false;
			
		}
	
}
</script>
</head>

<body>

<div id="headtitle">CoOP</div>
<div class="filtercontainer">
<table cellpadding="0" cellspacing="0" width="100%" border="0">

<tr>
    <td width="550"><div class="adv_search">
        <div class="adv_search_sub">
        <form name="CoOPSearchForm" id="CoOPSearchForm" action="" method="post">
       <div class="input_box_1">
        <input class="input_box_2_1" type="text" name="SearchText" value="<?php echo $_SESSION['SearchText'];?>" />
        <div style="display: block;" data-tooltip="Show search options" aria-label="Show search options" role="button" gh="sda" tabindex="0" class="aoo" id="show_options">
        <div class="aoq"></div>
        </div></div>
        <div class="adv_btn">
        <input class="adv_btn_2" type="submit" value="" name="">
        </div>
        </form>
        </div>
        </div>
	</td>
	<td> 
    <div style="float:right">
    	<a href="AddSharePopup.php?task=addshare" class="AddShare"><input type="submit" class="MOSGLsmButton" style="margin-bottom:-6px;" value="Add New Share" name="addcoopshare" /></a>
        
        <a href="AddCoOP.php?task=add" class="AddCoOP"><input type="submit" class="MOSGLsmButton" style="margin-bottom:-6px;" value="Add New CoOP" name="addshare" /></a>
	</div>
	</td>
	<div style="float:right;">
	<td>
		<!--<input type="submit" value="Delete Tag" name="deltag" id="deltag" />-->
	</td>
	</div>
</tr>

</table>
</div>

<div class="subcontainer"><br />
<table cellpadding="0" cellspacing="0" width="100%" border="0">

<tr id="headerbar">
<td>ID</td>
<td>CoOP Name</td>
<td>CoOP Description</td>
<td>Coach commission</td>
<td>Action</td>
</tr>
<?php
foreach($allrec as $rows)
{
if($flag==0){
$flag=1;
$row_class="row-white";
}else{
$flag=0;
$row_class = "row-tan";
}
?>
<form action="" method="post" name="frmSample">
<tr id="<?php echo $row_class;?>">
<td><?php echo $rows['ID']; ?></td>
<td><a href="CoopShares.php?id=<?php echo $rows['ID'];?>" id="sharepopup<?php echo $rows['ID'];?>"><?php echo $rows['CoOPName']; ?></a></td>
<td><?php echo $rows['CoOPeDescription']; ?></td>
<td align="center"><?php echo $rows['CoOPCoachCommission']; ?></td>
<td>

<a class="AddShare" href="AddCoOP.php?task=update&sid=<?php echo $rows['ID'];?>"><img border="0" title="Edit CoOP Details" src="images/icon_page_edit.png"></a>&nbsp;
<input type="image" src="images/icon_delete.png" title="Delete" border="0" onclick="return deletedata()" /></td>
</tr>
</form>
<?php } 
?>
<tr>
<td colspan="9">&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
<td colspan="9"></td>

</tr>
<tr></tr>

</table>
</div>
<br />
<div><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total&nbsp;<?php echo count($allrec);?>&nbsp;Shares</b></div>
</body>
</html>

MOS Default Co-Op