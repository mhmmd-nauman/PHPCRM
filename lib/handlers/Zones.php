<?php 
$objzone = new Zones();

if($_REQUEST['Task']=='Add'){
   
            $added= $objzone->InsertZones1(array(
                                                 "Created"=> date("Y-m-d h:i:s"),  
                                                  "Name"                  =>$_REQUEST['Zname'],
                                                  "ZoneTime"                  =>$_REQUEST['ZoneTime'],
                                                  "AdjustHours"            =>$_REQUEST['adjusthours'],
                                                 ));
           
            header("location:Zones.php?flag=add"); 
                        

}


if($_REQUEST['Task']=='Update')
{

    $zoneid = $_REQUEST['ID'];
    $updated= $objzone->UpdateZones("ID = '$zoneid' ",array(
			 
			                         "Name"=>$_REQUEST['Zname'],
                                            	 "ZoneTime"=>$_REQUEST['ZoneTime'],
						 "AdjustHours"=>$_REQUEST['adjusthours'],
						));
 					 
                                         
        header("Location:Zones.php?id=".$_REQUEST['id']."&flag=update");                                
 }
 
 
if($_REQUEST['Task']=='del'){
///echo "kkkkk";
	$objzone->DeleteZones($_REQUEST['id']);
	//exit;
 header("Location:Zones.php?flag=del");    	
}
?>