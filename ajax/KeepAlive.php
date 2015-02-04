<?php 
include dirname(__FILE__)."/../lib/include.php"; 
$objusers = new Users();
if(empty($_REQUEST['zone'])){
	$clock_time = date("Y-m-d H:i:s");
}else{
	$date = new DateTime(null, new DateTimeZone($_REQUEST['zone']));
	$Date = object_to_array($date);
	$clock_time = date("Y-m-d H:i:s",strtotime($Date['date']));
}

$objusers->UpdateUserClockTime("ID = '".$_SESSION['UserClockID']."' ",array(
	"ClockoutTime" => $clock_time,
));

function object_to_array($data){
    if (is_array($data) || is_object($data)){
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}

echo date("h:i a",strtotime($clock_time));
exit;
?>