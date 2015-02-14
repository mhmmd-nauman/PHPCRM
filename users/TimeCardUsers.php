<?php 
include "../include/header.php";
$objusers = new Users();


$Users_Array = $objusers->GetAllUsers(USERS.".HasDeleted = 0",array(USERS.".*"));
(isset($_REQUEST['weekNo']))?$weekNo = $_REQUEST['weekNo']:$weekNo = date('W');
//$dateofweek = date("w");
$weekDate = $objusers->getDaysInWeek($weekNo,date("Y"));
//print_r($weekDate);

?>
<html>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/search.css" />
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/search.js"></script>

<script type="text/javascript">
 $(function() {
                    
                  
                    
                    <?php  foreach((array)$Users_Array as $Users_row){?>        
                       $("#AgentDetail<?php echo $Users_row['ID'];?>").click(function(e){
                                     e.preventDefault();	
                                     modalbox(this.href,this.title,550,800);
                         });	 
                <?php } ?>
             });
</script>



<script type="text/javascript">
$(document).ready(function(){
	var oTable = $('#Client_List').dataTable({
		"iDisplayLength": 10,	
	});
	oTable.fnSort( [ [0,'desc']] );

});
$(window).load(function(){
	$('#Client_List').show();
});
</script>
<script type="text/javascript">
$('#mainsearch').focus(function(){
	jQuery("#cate_main").hide();
	jQuery("#show_options").show();
	$('#changename').attr('name', '');
});
</script>
<body>
<div id="headtitle"> Users Time Card </div>


 <div class="filtercontainer">
 <div style="margin-top:46px;">
 
 </div>
 </div>






<div class="subcontainer">

<div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
           <td width="50%"><a href="?weekNo=<?php echo $weekNo-1; ?>">Previous Week</a></td>
           <td style="text-align: right;"><a href="?weekNo=<?php echo $weekNo+1; ?>">Next Week</a></td>
       </tr> 
       <tr>
           <td colspan="2">&nbsp;</td>
       </tr>
    </table>
  
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="Client_List" style="display:none;">
    <thead>
          
    <tr id="headerbar">
    <td>ID</td>
    <td width="200">User Name</td>
    <?php 
      for ($i = 0; $i < 7; ++$i) {
          ?>
    <td width="200"><?php echo date("D",strtotime($weekDate[$i])). date("/ M d, Y",strtotime($weekDate[$i]));?></td>
      <?php }?>
	<td width="100">Weekly Hours</td>
  </tr>  
		  
		  
      </thead>
  	
   <tbody>    
   <?php  
   $flag=0;
   foreach((array)$Users_Array as $User_Row){
       if($flag==0){
                $flag=1;
                $row_class="row-white";
         }else{
                $flag=0;
                $row_class = "row-tan";
       }
			
  ?>
<tr id="<?php echo $row_class;?>">
  <td><?php echo $User_Row['ID'];?></td>
   
   <td><?php echo $User_Row['FirstName']." ".$User_Row['LastName'];?></td>
   
   <?php 
     $global_hours=0;
     $global_min=0;
      for ($i = 0; $i <7; ++$i) {
          $day_hours=0;
          $day_munits =0;
          ?><td valign="top">
             
              <?php
            $Users_TimeCard_array = $objusers->GetUserClockInOutTime("UserID=".$User_Row['ID']." AND Date(ClockInTime) = '".date("Y-m-d",strtotime($weekDate[$i]))."' AND Date(ClockoutTime) <> '0000-00-00' AND Date(ClockoutTime) >= Date(ClockInTime) AND Time(ClockoutTime) >= Time(ClockInTime)",array("ClockInTime","ClockoutTime"));         
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
            
            
            }
                  $day_hours += intval($day_munits/60,0);
                  $day_munits = $day_munits%60;
                  $global_hours = $global_hours + $day_hours;
                  $global_min = $global_min + $day_munits;
                 if($day_hours  > 0 || $day_munits > 0){
                  ?>
                  <b><?php echo $day_hours.":".$day_munits." "; ?></b>
                <?php }?>
          </td>
       <?php }
        $global_hours += intval($global_min/60,0);
        $global_min = $global_min%60;
       ?>	
    
<td style="font-size:12px; font-weight:bold;" align="center"><a id="AgentDetail<?php echo $User_Row['ID'];?>" title="<?php echo $User_Row['FirstName']." ".$User_Row['LastName']." - ".$User_Row['Phone'];?>" href="SaleAgentTimeCardDetails.php?USER_ID=<?php echo $User_Row['ID'];?>&weekNo=<?php echo $weekNo;?>&name=<?php echo $User_Row['FirstName'];?>&lastname=<?php echo $User_Row['LastName'];?>&phone=<?php echo $User_Row['Phone'];?>"><?php echo $global_hours;?>:<?php echo $global_min;?></a></td>
  </tr>
    


<?php }?>	
	

 </tbody>
</table>
<br/>
</div>
</div>
<script type="text/javascript" src="<?php echo SITE_ADDRESS;?>js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_table.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADDRESS;?>css/demo_page.css" />
</body>
</html>
<?php include "../include/footer.php" ?>