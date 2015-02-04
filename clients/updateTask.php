<?php
include "../lib/outer_include.php";
$objClients = new Clients();
$objTasks = new Tasks();
$objTags = new Tags();

//print_r($_REQUEST);
?>
<?php if(isset($_REQUEST['Submit'])){
	$client =  $_REQUEST['clientID']; 
	$order = $_REQUEST['order'];
	
	
	echo "Task Updated";
} else {
	
	$client =  $_REQUEST['clientID']; 
	echo $client . "<br />";
	$order = $_REQUEST['order'];
	$tags = $objTags->GetClientTags("ClientID = '" . $client . "'", array("*"));
	if($tags){
		foreach($tags as $tag){
			$list = explode(",", $tag['TagID']);
			foreach($list as $id){
				echo $id . "<br />";	
			}
		}
	}
		
	//$status = mysqli_query($link,"SELECT * from `ClientTags`");		
	//var_dump($status);
?>


    <form action="#" name="updateTask">
    <input type="hidden" name="clientID" value="<?php echo $client;?>" />
    <input type="hidden" name="order" value="<?php echo $order;?>" />
    <input type="submit" name="Submit" 
        value="Mark <?php echo $_REQUEST['name'];?> Task Complete" 
        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
    </form>

<?php } ?>