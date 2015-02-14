<?php 
include "../lib/include.php";
$objusers = new Users();
$name=$_REQUEST['name'];
$surename=$_REQUEST['surname'];
(isset($_REQUEST['weekNo']))?$weekNo = $_REQUEST['weekNo']:$weekNo = date('W');
//$dateofweek = date("w");
$weekDate = $objusers->getDaysInWeek($weekNo,date("Y"));
$report_data .= "Sale Agent time card weekly report .$name $surename  \n";
      for ($i = 0; $i < 7; ++$i) {
          
         $report_data .= date("D",strtotime($weekDate[$i])).",";
       }
      $report_data .=" \n ";
   
   
     $global_hours=0;
     $global_min=0;
     $per_day_array = array();
      for ($i = 0; $i < 7; ++$i) {
          $day_hours=0;
          $day_munits =0;
          
            $Users_TimeCard_array = $objusers->GetUserClockInOutTime("UserID=".$_REQUEST['USER_ID']." AND Date(ClockInTime) = '".date("Y-m-d",strtotime($weekDate[$i]))."' AND Date(ClockoutTime) <> '0000-00-00' AND Date(ClockoutTime) >= Date(ClockInTime) AND Time(ClockoutTime) >= Time(ClockInTime)",array("ClockInTime","ClockoutTime"));         
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
            
             $report_data .= $hoursin." :".$munitsin." ".$am_pm_in." - ".$hours_out." :".$munits_out." ".$am_pm_out;
           }
                  $day_hours += intval($day_munits/60,0);
                  $day_munits = $day_munits%60;
                  $global_hours = $global_hours + $day_hours;
                  $global_min = $global_min + $day_munits;
                  $report_data .=",";
                  
            $per_day_array[$i]['day_hours'] = $day_hours;
            $per_day_array[$i]['day_munits'] = $day_munits;
         }
          $report_data .=" \n ";
        $global_hours += intval($global_min/60,0);
        $global_min = $global_min%60;
        for ($i = 0; $i < 7; ++$i) {
       $day_hours = $per_day_array[$i]['day_hours'];
       $day_munits = $per_day_array[$i]['day_munits'] ;
         } 
         
         
        $fileD = " Agent-$name.$surename.".date('m-d-Y').".csv";
    	
    $report_data.="\n";
    $report_data.="\n";
    $report_data .=	" Total hours in week = $global_hours : $global_min";
    header("Content-type: text/x-csv");		
    header("Content-Disposition: attachment; filename=\"" . $fileD . "\"");
    echo $report_data;
    exit;