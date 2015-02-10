<?php
include "../lib/include.php";
$tags = new Tags();
//print_r($_GET);

foreach($_GET as $key=>$value){
	$catch = substr($key, 5);
	echo $catch . "=>". $value ."\r\n";
	
	$arr = array(
		"Sorting"=>$value
	);	
	$where = "ID='".$catch."'";
	$list = $tags->UpdateTag($where, $arr);
}
?>