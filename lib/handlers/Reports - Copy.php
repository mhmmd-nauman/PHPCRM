<?php
//if($_REQUEST['Task']=='Download'){
//ob_end_flush();
//ob_flush();
//flush();
//ob_start(); 
//$objmember = new Member();
//$objHubFlxMembers = new HubFlxMember();
//$assign_site_array = $objHubFlxMembers->GetAllHubFlxMember("Status = 1",array("*"));
/*//print_r($assign_site_array);

$filename="MySaleReport.xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
//print_r($row);
 //exit;
// Pick a filename and destination directory for the file
// Remember that the folder where you want to write the file has to be writable
 $filename = "db_user_export_".time().".csv";

 
// Actually create the file
// The w+ parameter will wipe out and overwrite any existing file with the same name
$handle = fopen($filename, 'w+');
 
// Write the spreadsheet column titles / labels  
fputcsv($handle, array('ID','Date Purchased','Name','Email','Domain','Site','Setup','Subscription','Date Live'));
 
// Write all the user records to the spreadsheet
foreach($assign_site_array as $assign_site)
{
//print_r($assign_site);

  if( $assign_site['MemberID'] > 0){
    $Member_row=$objmember->GetAllMember("ID='".$assign_site['MemberID']."'", array("Email,FirstName,Surname"));
    //print_r($Member_row[0]);
    $assign_site['MemberName']=$Member_row[0]['FirstName']." ".$Member_row[0]['Surname'];
    $assign_site['CustomerEmail'] = $Member_row[0]['Email'];
    
  }

    fputcsv($handle, array(
	 $assign_site['ID'],
	 $assign_site['SaleDate'],
	 $assign_site['MemberName'],
	 $assign_site['CustomerEmail'],
	 $assign_site['DomainName'],
	 
	 
	 
	 ));
}
fclose($handle);
readfile($filename);
 //header("location:Reports.php?flag=report_gernated");
// Finish writing the file
//fclose($handle);
 exit;
}
*/

if($_REQUEST['Task']=='Download'){
$objmember = new Member();
$objHubFlxMembers = new HubFlxMember();
//$slae_report = $objHubFlxMembers->GetAllHubFlxMember(" HasDeleted = 0",array("*"));
//$utilOjb = new util();
//echo"fdfdfdfdfdfd";


$XML = "Full Name,Business Name,Email,Status \n";
$file ="report_". date("Y-m-d"). ".csv";

$slae_report_array = $objHubFlxMembers->GetAllHubFlxMember("1",array("*"));
print_r($slae_report_array);

foreach($slae_report_array as $sale_report){
    $XML.= $sale_report['FirstName']. ",";
    $XML.= $sale_report['Surname']. ",";
    $XML.= $sale_report['CompanyName']. ",";
    $XML.= $sale_report['Email']. ",";
    
}

//exit;
?>
