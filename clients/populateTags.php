<?php
include "../lib/include.php";
$objClient = new Clients();
$tags = new Tags();

$existing = $tags->GetClientTags("ClientID = '" . $_REQUEST['id'] . "'", array("*"));
$tagList = explode(",",$_REQUEST['tags']);
print_r($exiting);
if($existing){
	// update existing
	foreach((array)$tagList as $tag){
		$arr = array(
			"TagID"=>$_REQUEST['tags']
		);	
		$where = "ClientID='".$_REQUEST['id']."'";
		$list = $tags->UpdateClientTag($where, $arr);
		echo $list;
	}
	
} else {
	
	
	// if no client
	$arr = array(
		"ClientID"=>$_REQUEST['id'],
		"TagID"=>$_REQUEST['tags']
	);
	$lid = $tags->AddTagToClient($arr);
	echo $lid;
}
/*
Used to backfill DB when this method was put in place 5-29-2014

$clients = $objClient->GetAllClients("1", array("*"));
echo "<table>";
foreach($clients as $client){
	echo "<tr><td>" .$client['ID']. "</td><td>140</td></tr>"; 
	$arr = array(
		"ClientID"=>$client['ID'],
		"TagID"=>"140"
	);
	$list = $tags->AddTagToClient($arr);
}
echo "</table>";
*/

?>
