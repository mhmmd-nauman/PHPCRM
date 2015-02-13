<?php include "../../include/header.php"; 
$utilObj = new util();
$ComsaleRec=$utilObj->getMultipleRow('CommissionLevelSale', 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>wizard/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
<link type="text/css" href="<?php echo SITE_ADDRESS;?>/themes/site/css/jquery-ui/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/Commissionslevel.js"></script>
<script>

</script>

</head>
<body>
<div id="headtitle">Coaching Commissions</div>
<div class="filtercontainer" style="padding-top:15px; padding-bottom:15px;">
  <!---->
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
      <td align="right" valign="center" ><a href="CommissionsCoachingprogramAddPopup.php?Task=add"  id="CommissionADD"  class="Categoryedit Ecom_Link"> Add New Coaching Commissions </a> </td>
    </tr>
  </table>
  <!---->
</div>
<div class="subcontainer">
  <div  style="float: left; padding: 10px 0 0;text-align: center; width: 905px;" >
      <? 
	  $date=date('Y-m-d');
				gettime($date);
	  function gettime($dt)
				{
					
					echo "<br>".$fdate = date('Y-m-d', strtotime($dt.'last saturday'));
					$SixDAgo = strtotime ( '-6 day' , strtotime ( $fdate ) ) ;
					echo "<br>".$tdate = date( 'Y-m-d' , $SixDAgo );
					
					echo "<br>".$fdate = date("Y-m-01", strtotime($dt."last month"));
					echo "<br>".$tdate = date("Y-m-t", strtotime($dt."last month"));
					
					echo "<br>".$fdate = $dt;
					echo "<br>".$tdate = $dt;
					
				}
				?>
</div>

</body>
</html>