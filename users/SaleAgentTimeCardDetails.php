<?php 
include "../lib/include.php";
$objusers = new Users();

(isset($_REQUEST['weekNo']))?$weekNo = $_REQUEST['weekNo']:$weekNo = date('W');
//$dateofweek = date("w");
$weekDate = $objusers->getDaysInWeek($weekNo,date("Y"));

?>
<style type="text/css">
.heading {
    background-color: #EEEEEE;
    font-size: 14px;
    font-weight: bold;
    padding: 4px;
}
.subcontainer tr td{
	font-weight: bold;
}
.notapplicable{
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
}
.order_details tr td{
	border-bottom:1px solid #CCCCCC;
	border-right:1px solid #CCCCCC;
	text-align:center;
}
.order_details tr td:last{
	border-bottom:medium none;
	border-right:medium none;
}
.table_heading{
	font-weight:bold;
	font-size:10px;
}
.order_details tr td:first-child{
	text-align:left;
}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo SITE_ADDRESS;?>css/styles.css" />

  <div class="subcontainer">
      
      <table  width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
              <td>
                  <table  width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr>
              <td width="10%">Name:</td>
              <td><?php echo $_REQUEST['name']." ".$_REQUEST['lastname'];?></td>
              
                  </tr>
                  <tr>
              <td width="10%">Phone:</td>
              <td><?php echo $_REQUEST['phone'];?></td>
              
                  </tr>
                  </table>
              </td>
              <td>
                <form action="UserTimeCardExport.php?id=<?php echo $_REQUEST['USER_ID'];?>&Task=ExportUserTimeCard&name=<?php echo $_REQUEST['name'];?>&surname=<?php echo $_REQUEST['lastname'];?>&weekNo=<?php echo $_REQUEST['weekNo'];?>" target="_top"method="post"  enctype="multipart/form-data" name="frmSample" onsubmit="return ValidateForm(this);">
      <div style="float:right; margin-top:2px;">
          <input type="image" name="Submit" src="../../images/icon_download_excel.png" title="Export & Download" onclick="return spon_check()"/>
          </div>
          </form>  
              </td>
              
          </tr>
          
      </table>
      

	<table class="order_details" width="100%" border="0" cellspacing="0" cellpadding="5">
        
		<tr id="headerbar" style="color: #000;">
		  
          <?php 
      for ($i = 0; $i < 7; ++$i) {
          ?>
        <td width="200" style=" text-align:  center;"><?php echo date("D",strtotime($weekDate[$i]))?></td>
      <?php }?>
        </tr>
        <?php  
   $flag=0;
   
       if($flag==0){
                $flag=1;
                $row_class="row-white";
         }else{
                $flag=0;
                $row_class = "row-tan";
       }
			
  ?>
      <tr id="<?php echo $row_class;?>">
   
   <?php 
     $global_hours=0;
     $global_min=0;
     $per_day_array = array();
      for ($i = 0; $i < 7; ++$i) {
          $day_hours=0;
          $day_munits =0;
          ?><td valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <?php
            $Users_TimeCard_array = $objusers->GetUserClockInOutTime("UserID=".$_REQUEST['USER_ID']." AND Date(ClockInTime) = '".date("Y-m-d",strtotime($weekDate[$i]))."' AND Date(ClockoutTime) <> '0000-00-00' AND Date(ClockoutTime) >= Date(ClockInTime) AND Time(ClockoutTime) >= Time(ClockInTime)",array("ClockInTime","ClockoutTime"));   
            //print_r($Users_TimeCard_array);
            foreach((array)$Users_TimeCard_array as $Users_TimeCard_row ){
            $Users_TimeCard = $Users_TimeCard_row;
           
            // code to show enteries on table
            $time_array  = $Users_TimeCard['ClockInTime'];
            $clockin = explode(" ", $time_array);
            $clockin_array=$clockin[1];
            $clockinhour = explode(":", $clockin_array);
            $hoursin = $clockinhour[0];
            $munitsin= $clockinhour[1];
            $timeout_array=$Users_TimeCard['ClockoutTime'];
            $clockout = explode(" ", $timeout_array);
            $clockout_array=$clockout[1];
            $clockouthour = explode(":", $clockout_array);
            $hours_out     = $clockouthour[0];
            $munits_out    = $clockouthour[1];
            
            $startTimestamp = mktime($hoursin, $munitsin);
            $endTimestamp = mktime($hours_out, $munits_out);

            $seconds = $endTimestamp - $startTimestamp;
            
            $munits = ($seconds / 60) % 60;
            $remainder = $munits % 60;
            $hours_temp=explode('.',(($seconds / 60) / 60));
            $hours = $hours_temp[0];
            $munits = $remainder;
            
            $day_hours = $day_hours + $hours;
            $day_munits = $day_munits + $munits;
            
            $am_pm_in="AM";
            if($hoursin >= 12){
                    $am_pm_in="PM";
                    $hoursin = $hoursin - 12;
            }
            $am_pm_out="AM";
            if($hours_out >= 12){
                    $am_pm_out="PM";
                    $hours_out = $hours_out - 12;
            }
            if($hoursin<5){
                $hoursinnotzero=explode("0", $hoursin);
                $hoursin=$hoursinnotzero[1];
            }
            if($hours_out<5){
                $hoursinnotzero=explode("0", $hours_out);
                $hours_out=$hoursinnotzero[1];
            }
           //echo  $hoursin;
            ?>
             
                  <tr>
                      <td style="font-size:8px; border: none;"><?php echo $hoursin.":".$munitsin." ".$am_pm_in." - ".$hours_out.":".$munits_out." ".$am_pm_out; ?></td>
                  </tr>
              
                  <?php }
                  $day_hours += intval($day_munits/60,0);
                  $day_munits = $day_munits%60;
                  $global_hours = $global_hours + $day_hours;
                  $global_min = $global_min + $day_munits;
                 ?>
          </table>
          </td>
       <?php 
            $per_day_array[$i]['day_hours'] = $day_hours;
            $per_day_array[$i]['day_munits'] = $day_munits;
         }
        $global_hours += intval($global_min/60,0);
        $global_min = $global_min%60;
       ?>	
    

  </tr>
  <tr>
 <?php for ($i = 0; $i < 7; ++$i) {
       $day_hours = $per_day_array[$i]['day_hours'];
       $day_munits = $per_day_array[$i]['day_munits'] ;
    ?>
    
        <td style="font-size:11px;text-align:  center;"><b> <?php echo $day_hours.":".$day_munits." "; ?></b></td>
    
 <?php } ?>
</tr>
	</table>
  <p></p>
    <table  width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
              <td>
                  <table  width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr>
                      <td width="10%">&nbsp;</td>
              <td>&nbsp;</td>
              
                  </tr>
                  </table>
              </td>
              <td >
                 <table  width="100%" border="0" cellspacing="0" cellpadding="5" >
                  <tr>
              <td  align="right">Total Hours in week:</td>
              <td align="right"><?php echo $global_hours;?>:<?php echo $global_min;?></td>
              
                  </tr>
                  </table> 
              </td>
          </tr>
          
      </table>
  <div style="float:right; margin-top:25px; font-size:15px; cursor:pointer;"><a onclick="print_me();"><img src="../ecommerce/orders/images/print_icon.gif" title="Print this order page." /></a></div>
</div>
<script type="text/javascript">
function print_me(){
	window.print();
}
$(document).ready(function(){
	$('.order_details td').css('background-color', '#fff');
	$('.order_details td:empty').css('background-color', '#F1EFEF');
	$('.total').css('background-color', '#FFFF00');
});
</script>
