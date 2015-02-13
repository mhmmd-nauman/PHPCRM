<?php


$file = 'file.csv';
 	
	header("Content-Disposition: attachment; filename=" . urlencode($file));   
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Description: File Transfer");            
	header("Content-Length: " . filesize($file));
	

@session_start();
require_once "../../dbcon.php";
require_once "../../lib/classes/config/variables.php";
require_once "../../lib/classes/util_objects/util.php";
require_once "../../lib/classes/business_objects/Orders.php";
require_once "../../lib/classes/business_objects/Tags.php";
require_once "../../lib/classes/business_objects/Clients.php";
$objorder = new Orders();
$objClient = new Clients();
$objTag = new Tags();
$arr;
$strwhere = "1";
//print_r($_REQUEST);
if($_REQUEST['Task']=='ExportOrders')
{

//$where  =$_SESSION['download_file'];
//$where="ClassName=OrderItem";
//$row = $objorder->GetAllOrder($where,array("*"));

$Search = '';

$searchText = $_REQUEST['MemSearchText'];
//$_SESSION['status'] = $_REQUEST['status'];
if(empty($_SESSION['OLFromDate']))
	$_SESSION['OLFromDate'] = date("m/d/Y",strtotime("-1 Week"));
if(empty($_SESSION['OLToDate']))
	$_SESSION['OLToDate'] = date("m/d/Y");



//print_r($_SESSION['CompanyName']);
$inquery="";
if(!empty($_SESSION['status'])){
	foreach((array)$_SESSION['status'] as $status){
		switch($status){
			case 1:
				
				$inquery.= "'Unpaid',";
				
				break;
			case 2:
				
				$inquery.= "'Paid',";
				
				break;
			case 3:
				//$matchIt.= "3,";
				$inquery.= "'Refunded',";
				//$for_dropdown_preselected_status.="Refunded,";
				break;
			case 4:
				//$matchIt.= "4,";
				$inquery.= "'Cancelled',";
				//$for_dropdown_preselected_status.="Cancelled,";
				break;
			case 5:
				//$matchIt.= "5,";
				$inquery.= "'ChargeBack',";
				//$for_dropdown_preselected_status.="ChargeBack,";
				break;
			case 6:
				//$matchIt.= "6,";
				$inquery.= "'Closed',";
				//$for_dropdown_preselected_status.="Closed,";
				break;
			
		}
	}
		
		
	$inquery = substr($inquery, 0, -1);
	//$for_dropdown_preselected_status = substr($for_dropdown_preselected_status, 0, -1);
	if(!empty($Search)){
		$Search .= " AND ".ECORDERITEM. ".Status IN(" . $inquery . ")";
	}else{
		$Search .= " ".ECORDERITEM.".Status IN (" . $inquery .")";
	}
	
	

}


if($_REQUEST['Task'] == 'SetFilter'){
	if(isset($_REQUEST['FromDate']) || isset($_REQUEST['ToDate'])){
		$_SESSION['OLFromDate'] = (isset($_REQUEST['FromDate'])) ? $_REQUEST['FromDate'] : "";
		$_SESSION['OLToDate'] = (isset($_REQUEST['ToDate'])) ? $_REQUEST['ToDate'] : "";
		//$_SESSION['CompanyName'] = (isset($_REQUEST['CompanyName'])) ? $_REQUEST['CompanyName'] : "";	
	}
}

# Search Filter For text starts here

# Search filter for text search ends here

# Date Search starts here
if(!empty($Search)){
	$Search .= " AND ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."' ";
}else{
	$Search .= " ".ECORDERITEM.".Created >= '".date("Y-m-d 00:00:00",strtotime($_SESSION['OLFromDate']))."' AND ".ECORDERITEM.".Created <= '".date("Y-m-d 23:59:59",strtotime($_SESSION['OLToDate']))."' ";
}
# Date Search ends Here


#pay later search starts
/*
if(!empty($_SESSION['payLater'])){
	if($_SESSION['payLater']=="1"){
		if(!empty($Search)){
			$Search .= " AND ".ECORDERITEM. ".Status='Unpaid' ";
		}else{
			$Search .= " ".ECORDERITEM.".Status='Unpaid' ";
		}
	}
}
*/


#pay later search ends

# Company Wise search starts here
if(!empty($_SESSION['CompanyName'])){
	$all_companies = $for_dropdown_preselected_companies = "";
		$leng = count($_SESSION['CompanyName']);
		$i = 0;
		foreach((array)$_SESSION['CompanyName'] as $companies){
			if($i == $leng - 1){
				$all_companies .= "'".$companies."'";
				//$for_dropdown_preselected_companies .= $companies;
			}else{
				$all_companies .= "'".$companies."',";
				//$for_dropdown_preselected_companies .= $companies.",";
			}
			$i++;
		}
		
	if(!empty($Search)){
		$Search .= " AND Company.`ID` IN(" . $all_companies . ")";
	} else {
		$Search .= " Company.`ID` IN(" . $all_companies . ")";
	}
}
if(!empty($_SESSION['payLater'])){
	if($_SESSION['payLater']=="later"){
		
			if(!empty($search)){
				$Search .= " AND OrderItem.PaidThrough ='Credit Card (Process Later)'";
				
			}else{
				$Search = " OrderItem.PaidThrough ='Credit Card (Process Later)'";
			}	
	}
}
if(!empty($_SESSION['productFilter'])) {
	// need a join	
	$all_products = $for_dropdown_preselected_products = "";
		$leng = count($_SESSION['productFilter']);
		$i = 0;
		foreach((array)$_SESSION['productFilter'] as $products){
			if($i == $leng - 1){
				$all_products .= "'".$products."'";
				$for_dropdown_preselected_products .= $products;
			}else{
				$all_products .= "'".$products."',";
				$for_dropdown_preselected_products .= $products.",";
			}
			$i++;
		}
	
	//$prods = $_REQUEST['productFilter'];
	
	if(!empty($Search)){
		$Search .= " AND OrderDetail.ProductID IN(" . $all_products . ")";
	} else {
		$Search .= " OrderDetail.ProductID IN(" . $all_products . ")";
	}
}
# Company Wise search ends here
if($_REQUEST['Task'] == 'SetFilter'){

# Agent Wise search starts here
	if(!empty($_POST['select_agent'])){
		$all_agents = $for_dropdown_preselected_agents = "";
		$len = count($_POST['select_agent']);
		$i = 0;
		foreach((array)$_POST['select_agent'] as $agents){
			if($i == $len - 1){
				$all_agents .= "'".$agents."'";
				$for_dropdown_preselected_agents .= $agents;
			}else{
				$all_agents .= "'".$agents."',";
				$for_dropdown_preselected_agents .= $agents.",";
			}
			$i++;
		}

		if(!empty($search)){
			$Search .= " AND Users.`ID` IN ($all_agents)";
			
		}else{
			$Search = " Users.`ID` IN ($all_agents)";
		}
	   
	}
}

#

if($Search == ''){
	$Search = ' 1 ';
}

//$Search .= " ORDER BY ".ECORDERITEM.".`Created` DESC";


	$sql = "SELECT OrderItem.ID, Clients.FirstName, Clients.Surname, Clients.Email, Clients.Phone, Clients.Address, Clients.Address2, Clients.City, Clients.State, Clients.ZipCode, Clients.CompanyName, OrderDetail.ProductName, OrderItem.TotalPrice, OrderItem.Created, OrderItem.ManualPaymentDate, OrderItem.Status,OrderItem.PaidThrough, ClientCCInformation.CVV, ClientCCInformation.Exp_Month, ClientCCInformation.Exp_Year, ClientCCInformation.CreditCardNumber, OrderItem.StreetAddress1, OrderItem.StreetAddress2, OrderItem.BillingCity, OrderItem.BillingState, OrderItem.BillingPostalCode, OrderItem.RoutingNumber, OrderItem.CredetCardType, OrderItem.Bank_Name, OrderItem.AccountNumber, ClientTags.TagID, Clients.Services, Clients.BusinessType, Clients.Founded, EcGYBData.GYB_SpecialtiesKeywords, EcGYBData.GYB_Services, EcGYBData.GYB_YearFounded  FROM `OrderItem` OrderItem LEFT JOIN `Clients` Clients ON Clients.ID = OrderItem.MemberID LEFT JOIN `OrderDetail` OrderDetail on OrderItem.ID =OrderDetail.OrderID  join ".USERS." on ".USERS.".ID = ".ECORDERITEM.".UserID join Company on ".USERS.".CompanyID = Company.ID LEFT JOIN `ClientTags` ClientTags on ClientTags.ClientID = Clients.ID LEFT JOIN `ClientCCInformation` ClientCCInformation ON ClientCCInformation.ClientID = Clients.ID JOIN `EcGYBData` EcGYBData on EcGYBData.MemberID = Clients.ID WHERE $Search ORDER BY OrderItem.ID DESC  " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arg[] = $row;
				//print_r($row) . "<hr />";
			}
		}
$ClientOrdersRows = $arg;	
// $ClientOrdersRows = $objorder->GetAllOrderWithCompanyMatch($Search, array(ECORDERITEM.".*"));


/*
$sql1 = "select * from `" . ECORDERITEM . "` OrderItem WHERE" . $Search;
$result = mysqli_query($link,$sql1);
while($row = mysqli_fetch_array($result)){
	$popular[] = $row;
	
}
*/

/*
<br /><br />
<table border="1">
<tr>
<th>ID</th>
<th>FirstName</th>
<th>Surname</th>
<th>Email</th>
<th>Phone</th>
<th>BusinessAddress1</th>
<th>BusinessAddress2</th>
<th>City</th>
<th>State</th>
<th>Zip</th>
<th>CompanyName</th>
<th>ProductName</th>
<th>Price</th>
<th>Date</th>
<th>Status</th>
<th>Order Type (cc, echeck)</th>
<th>Full Credit Card #</th>
<th>Exp date</th>
<th>cvv code</th>
<th>Billing Address</th>
<th>Billing City</th>
<th>Billing State</th>
<th>Billing Zip</th>
<th>Check routing number</th>
<th>Check acct number</th>
<th>Tags</th>
<th>Services</th>
<th>Business Type</th>
<th>Year Founded</th>

</tr>
*/
//echo $Search;
$fp = fopen('file.csv', 'w');

$flag = false;
//echo "clients order rows:<br />";
//var_dump($ClientOrdersRows);
//echo "<hr />";

//echo "<hr /><table>";
//var_dump($popular);
   //$result = $orderDetails; 
 //$count = 0;
  foreach((array)$ClientOrdersRows as $val) {
  //$prods = $objorder->GetAllOrderForHomePage("OrderID='". $val['ID'] . "'", array("*"));
 // $prodName = $prods[0]['ProductName'];
	//$count++;
  	if($val['CreditCardNumber']!="" && !empty($val['CreditCardNumber'])){
	$secure = $objClient->decrypt($val['CreditCardNumber']);
	} else {
		$secure = "";
	}
	
	$tags=""; 
	$tagList = explode(",", $val['TagID']);
	foreach($tagList as $taggy){
		$tagName = $objTag->GetAllTags("ID='$taggy'", array("*"));
		//print_r($tagName);
		$tags.=$tagName[0]['Title'] . " / ";	
	}
	$tags = substr($tags, 0,-2);
	$exp = $val['Exp_Month'] . "/" . $val['Exp_Year'];
	$billingAddy = $val['StreetAddress1']  . " " . $val['StreetAddress2'];
	
	if($val['ManualPaymentDate']!="0000-00-00"){
		$paid = date("y-m-d", strtotime($val['ManualPaymentDate']));	
	} else {
		$paid = "";
	}
	switch($val['BusinessType']){
		case 0: $type = 'Unspecified'; break;
		case 1: $type = 'Sole proprietorship'; break;
		case 2: $type = 's corp'; break;
		case 3: $type = 'partnership'; break;
		case 4: $type = 'LLC'; break;
		case 5: $type = 'c corp'; break;
		default: $type = 'Unspecified'; break;
	}
		$arr = array(
		'ID' =>$val['ID'],
		'FirstName' =>  $val['FirstName'],
		'Last Name' =>$val['Surname'],
		'Email' => $val['Email'],
		'Phone' => $val['Phone'],
		'BusinessAddress1' => $val['Address'],
		'BusinessAddress2' => $val['Address2'],
		'City' => $val['City'],
		'State' => $val['State'],
		'Zip' => $val['ZipCode'],
		'CompanyName' => $val['CompanyName'],
		'ProductName' => $val['ProductName'],
		'Price' =>  $val['TotalPrice'],
		'Created Date' => $val['Created'],
		'Paid Date' => $paid,
		'Status' => $val['Status'],
		'Order Type' => $val['PaidThrough'],
		'Card Type'=>$val['CredetCardType'],
		'CreditCard#' =>  $secure,
		'Exp date' => $exp,
		'cvv code' => $val['CVV'],
		'Billing Address' => $billingAddy,
		'Billing City' => $val['BillingCity'],
		'Billing State' =>  $val['BillingState'],
		'Billing Zip' =>  $val['BillingPostalCode'],
		'Bank Name'=>$val['Bank_Name'],
		'Check routing number' => $val['RoutingNumber'],
		'Check acct number' => $val['AccountNumber'],
		'Tags' => $tags,
		'Services' => $val['GYB_Services'],
		'Business Type' =>  $type,
		'Year Founded' => $val['GYB_YearFounded'],
		'Categories/Keywords'=>$val['GYB_SpecialtiesKeywords']
		);

	
     //$arr =array('ID'=>$val['ID'],'FirstName'=>$val['FirstName'],'Surname'=>$val['Surname'],'Email'=>$val['Email'],'Phone'=>$val['Phone'],'StreetAddress1'=>$val['StreetAddress1'],'StreetAddress2'=>$val['StreetAddress2'],'CompanyName'=>$val['CompanyName'], 'ProductName'=>$prodName, 'Price'=>$val['TotalPrice'],'Date'=>$val['Created'], 'Status'=>$val['Status']);


	/*
	echo "<tr>";
	echo "<td>" . $val['OrderID'] . "</td>";
	echo "<td>" . $val['FirstName'] . "</td>";
	echo "<td>" . $val['Surname'] . "</td>";
	echo "<td>" . $val['Email'] . "</td>";
	echo "<td>" . $val['Phone'] . "</td>";
	echo "<td>" . $val['Address'] . "</td>";
	echo "<td>" . $val['Address2'] . "</td>";
	echo "<td>" . $val['City'] . "</td>";
	echo "<td>" . $val['State'] . "</td>";
	echo "<td>" . $val['ZipCode'] . "</td>";
	echo "<td>" . $val['CompanyName'] . "</td>";
	echo "<td>" . $val['ProductName'] . "</td>";
	echo "<td>" . $val['TotalPrice'] . "</td>";
	echo "<td>" . $val['Created'] . "</td>";
	echo "<td>" . $val['Status'] . "</td>";
	echo "<td>" . $val['PaidThrough'] . "</td>";
	echo "<td>" . $secure. "</td>";
	echo "<td>" . $val['Exp_Month'] . "/" . $val['Exp_Year'] . "</td>";
	echo "<td>" . $val['CVV'] . "</td>";
	echo "<td>" . $val['StreetAddress1']  . " " . $val['StreetAddress2'] . "</td>";
	echo "<td>" . $val['BillingCity'] . "</td>";
	echo "<td>" . $val['BillingState'] . "</td>";
	echo "<td>" . $val['BillingPostalCode'] . "</td>";
	echo "<td>" . $val['RoutingNumber'] . "</td>";
	echo "<td>" . $val['AccountNumber'] . "</td>";
	echo "<td>" . $tags . "</td>";
	echo "<td>" . $val['Services'] . "</td>";
	echo "<td>" . $val['BusinessType'] . "</td>";
	echo "<td>" . $val['Founded'] . "</td>";
	*/
	


	//echo "<tr><td>".$val['ID']."</td><td>" . $val['FirstName'] . "</td><td> " . $val['Surname'] . "</td><td> " . $val['Status'] . " </td><td>". $prodName . "</td></tr>";
     
	 if(!$flag) { 
       // display field/column names as first row 
       fputcsv($fp, array_keys($arr), ',', '"');
       $flag = true; 
     } 
	

     fputcsv($fp, array_values($arr), ',', '"'); 
   } 
//echo "</table>";

	flush(); # This doesn't really matter.
	$fp = fopen($file, "r");
	while (!feof($fp))
	{
		echo fread($fp, 65536);
		flush(); # this is essential for large downloads
	}
	
	
fclose($fp);
exit;
}

?>

<?php //echo count($ClientOrdersRows); ?>