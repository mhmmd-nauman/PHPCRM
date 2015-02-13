<?php
include "../../lib/include.php";
$objorder = new Orders();
$orderid = $_REQUEST['OrderID'];

if(empty($orderid)){
	$orderid = $_POST['OrderID'];
}

$order_invioce_array = $objorder->GetAllOrderDetailWithProduct("OrderID = $orderid ",array("*"));

if($_SESSION['isAdmin']  != 1){ 
	echo "Access Denied";
	exit;
}

$objClient = new Clients();
$isAdmin = $isSuperadmin = "";
if(isset($_POST['submitagain'])){
	if(!empty($_POST['emailagain']) and !empty($_POST['passagain'])){
		if($_SESSION['isAdmin'] != 1){ 
			echo "You do not have full permissions to view full details.";
			exit;
		}
		$objusers = new Users();
		$User = $objusers->ValidateUser(trim($_POST['emailagain']),trim($_POST['passagain']));       
		if($User['ID'] > 0){
			$ObjGroup = new Groups();
			$Group_Array = $ObjGroup->GetMemberGroups("UserID=".$User['ID']." ",array('GroupID'));
			foreach((array)$Group_Array as $group_row){
				$groups[] = $group_row['GroupID'];
			}

			if(in_array(2,(array)$groups)){
				$isAdmin = 1;
				$isSuperadmin = 1;
			}elseif(in_array(3,(array)$groups)){
				$isAdmin = 1;
				$isSuperadmin = 0;
			}else{
				$isSuperadmin = 0;
				$isAdmin = 0;
				echo "You do not have full permissions to view full details.";
				die();
			}
		}else{
			$message = "Either the Email or Password You Entered is Incorrect. Please Try Again.";
		}
	}else{
		$isAdmin = 0;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
.heading {
    background-color: #EEEEEE;
    font-size: 14px;
    font-weight: bold;
    padding: 4px;
}

.notapplicable{
	background-color:#CCCCCC;
}
.notapplicable:hover{
	background-color:#CCCCCC;
}
.total{
	background:#FFFF00;
}
.rows{
	height:25px;
}
.rows_notes{
	word-wrap:break-word;
}
.order_details{
	border:1px solid #CCCCCC;
	background:#F1EFEF;
}
.order_details tr td{
	border-bottom:1px solid #CCCCCC;
	border-right:1px solid #CCCCCC;
	text-align:center;
	padding:0; margin:0;
}
.order_details tr td:first-child{
	text-align:left;
	padding: 0 0 0 5px;
}
.order_details tr td:hover{
	background-color:#DFE8F2;
}

.table_heading{
	font-weight:bold;
	font-size:15px;
}
.hover{
	background-color:#DFE8F2;
}
.invoice{
	width:99%;
	text-align:right;
	color:#8394c9;
	font-size:20px;
	font-weight:bold; 
        margin-top: 10px;
}
table tr td{
    font-size: 12px;
}
.border_replace{
        border: none; 
        padding-left: 10px;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Order Details</title>
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/styles.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>
<script src="<?php echo SITE_ADDRESS;?>js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/Dialogue.js"></script>
</head>
<body>
<div class="subcontainer">
    <table width="100%" border="0" cellspacing="2" cellpadding="5">
    	<?php
        if(!empty($message)){
			echo "<div class='message_error' style='width:94%;'>$message</div>";
		}
		if(!empty($order_invioce_array[0]['CredetCardNumber'])){
		?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp; </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td align="right"><?php echo $order_invioce_array[0]['CredetCardType'];?>:</td>
            <td>
            <?php 
            	$unsecure_credit_card = $objClient->decrypt($order_invioce_array[0]['CredetCardNumber']);
				if($isAdmin === 1 and !empty($isAdmin) and $isAdmin != NULL){
					echo $unsecure_credit_card;
				}else{
					echo $unsecure_credit_card = "XXXX-XXXX-XXXX-".substr($unsecure_credit_card, -4, 4);
				}
			?>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td align="right">Expire:</td>
            <td>
            <?php 
            	echo $order_invioce_array[0]['ExpirationMonth']."/".$order_invioce_array[0]['ExpirationYear'];
            ?>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td align="right">Security Code:</td>
            <td>
            <?php 
				if($isSuperadmin === 1 and !empty($isSuperadmin) and $isSuperadmin != NULL){
					echo $order_invioce_array[0]['SecurityCode'];
				}else{
					# echo $new_scode = "XXX-".substr($order_invioce_array[0]['SecurityCode'], -1, 1);
					echo '<span style="background-color: rgb(204, 204, 204); padding: 3px 23px;">&nbsp;</span>';
				}
            ?>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" align="center">
<?php    
$showhide = ($isAdmin === 1 and !empty($isAdmin) and $isAdmin != NULL) ? '<a href="#" class="hidedetails">Hide Details</a>' : '<a href="#" class="fulldetails">Reveal Credit Card Details</a>';
echo $showhide;
?>
            </td>
        </tr>
        <?php
        }else{
		?>
        <tr>
            <td colspan="3" align="center">Credit Card Details Not Available.</td>
        </tr>
        <?php
		}
		?>
	</table>  
  <p>&nbsp;</p>
<div class="enterloginagain" style="display:none" class="subcontainer" >
	<form class="loginformagain" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    	<table width="60%" border="0" cellspacing="2" cellpadding="5" style="table-layout:fixed; margin:0 auto; border:1px solid #e1e1e1; box-shadow:0px 2px 3px 1px #ccc;">
        	<tr>
                <td align="center"><label><h3>Due to security concerns you will need to<br/>correctly enter your email and password</h3></label></td>
            </tr>
        	<tr>
                <td align="center"><input type="text" name="emailagain" placeholder="Enter Email" required autocomplete="off" style="width:60%;"></td>
            </tr>
            <tr>
                <td align="center"><input type="password" name="passagain" placeholder="Enter Password" required autocomplete="off" style="width:60%;"></td>
            </tr>
            <tr>
                <td align="center">
                <input type="submit" name="submitagain" value="View" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                <input type="hidden" value="<?php echo $orderid; ?>" name="OrderID">
                &nbsp;
                <input type="button" name="cancel" value="Cancel" class="cancel ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"></td>
            </tr>
        </table>
    </form>
</div>
</div>
<script type="text/javascript">
function print_me(){
	window.print();
}
$(document).ready(function(){
	
	$('.order_details td').css('background-color', '#fff');
	$('.order_details td:empty').css('background-color', '#F1EFEF');
	$('.total').css('background-color', '#FFFF00');
	
	$(".fulldetails").click(function(e){
		$(".enterloginagain").show("fast");
	});
	$(".cancel").click(function(){
		$(".enterloginagain").hide("fast");
	});
	
	$(".hidedetails").click(function(){
		location.href = "ViewCC.php?OrderID=<?php echo $orderid; ?>";
	});
	
	$(".message_success").fadeOut(10000);
	$(".message_error").fadeOut(10000);
});

function confirmation(){
  var didConfirm = confirm("Are you sure you want to delete the order?");
  if(didConfirm == true){
  	return true;
  }
  return false;
}

function close_popup(){
	$(window.top.document).find('.ui-widget-overlay, .ui-dialog').remove();
}

</script>

</body>
</html>
