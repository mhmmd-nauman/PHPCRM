<?php  
class Orders extends util {
	function GetAllOrder($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". ECORDERITEM ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr; 
	}	
	
	function GetAllOrderForHomePage($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". ECORDERITEM ." join `OrderDetail` on ".ECORDERITEM.".ID = `OrderDetail`.OrderID  WHERE $strWhere and OrderDetail.HideOrder = '0' " or die("Error in the consult.." . mysqli_error($link));
		
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr; 
	}
	
	function GetAllOrderStatus($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		}
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". ECORDERITEM ." join Clients on ".ECORDERITEM.".MemberID = Clients.ID  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr;
	}
	
	
	function updateOrderFields($where, $array){
		$updated_id = util::updateRecord(ECORDERITEM,$where,$array);
		return $updated_id;	
	}
	function returnproductdetails($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM `OrderDetail` WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr; 
	}
	
	function getSentEmailDetails($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM `OverdueInvoice_Email` WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr; 
	}
	
	function GetAllOrderWithCompanyMatch($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		
		if(in_array(16, (array)$_SESSION['user_in_groups'])){
			
		}
		$sql = "SELECT $strFields,Clients.FirstName,Clients.Surname,Clients.Email,Clients.CompanyName,Clients.Phone,Clients.AlternatePhone,Clients.City,Clients.State,Clients.ZipCode,Clients.SubmitedBy,OrderDetail.ProductPrice,OrderDetail.Quantity, OrderDetail.ProductID FROM ". ECORDERITEM ." join ".USERS." on ".USERS.".ID = ".ECORDERITEM.".UserID join Company on ".USERS.".CompanyID = Company.ID join `OrderDetail` on `OrderItem`.ID = `OrderDetail`.OrderID join Clients on `Clients`.ID = ".ECORDERITEM.".MemberID WHERE OrderDetail.HideOrder = '0' and $strWhere " or die("Error in the consult.." . mysqli_error($link));
	
	
		
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr;
	}
	
	function Getdetailsforpayment($OrderID){
		global $link;
		$arr = array();
		$sql = "SELECT ".ECORDERITEM.".*,OrderDetail.ProductID, OrderDetail.ProductName, OrderDetail.ProductPrice, OrderDetail.Quantity FROM ". ECORDERITEM ." join `OrderDetail` on OrderDetail.OrderID = ".ECORDERITEM.".ID  WHERE ".ECORDERITEM.".ID = '$OrderID' " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr;
	}
	
	
	function getDaysInWeek($weekNumber, $year){
		global $link;
		# Count from '0104' because January 4th is always in week 1
		# (according to ISO 8601).
		$time = strtotime($year . '0104 +' . ($weekNumber - 1). ' weeks');
		# Get the time of the first day of the week
		$mondayTime = strtotime('-' . (date('w', $time) + 0) . ' days', $time);
		# Get the times of days 0 -> 6
		$dayTimes = "";
		for ($i = 0; $i < 7; ++$i) {
			$dayTimes[] = date("d-m-Y",strtotime('+' . $i . ' days', $mondayTime))." ";
		}
		return $dayTimes;
	}
	
	function GetAllOrderDetail($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". ECORDERDETAIL ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
		
	function GetAllOrderDetailWithProduct($strWhere,$fieldaArray=""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		}
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". ECORDERITEM ." LEFT JOIN ".ECORDERDETAIL." ON ".ECORDERITEM.".ID = ".ECORDERDETAIL.".OrderID  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr; 
	}
		
	function InsertWithPackage($array){
		if($array){
			$inserted_id = util::insertRecord(ECORDERITEM,$array);
			return $inserted_id;
		} else {
			return 0;
		}
	}
	
	function InsertClientCCinfo($array){
		if($array){
			$inserted_id = util::insertRecord("ClientCCInformation",$array);
			return $inserted_id;
		} else {
			return 0;
		}
	}
		
	function UpdateWithPackage($where,$array){
		if($array){
			$updated_id = util::updateRecord(ECORDERITEM,$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
	
	
	
	function InsertOrderDetail($array){
		if($array){
			$inserted_id = util::insertRecord("OrderDetail",$array);
			return $inserted_id;
		} else {
			return 0;
		}
	}	
	
	function InsertInvoiceDueEmailDetail($array){
		if($array){
			$inserted_id = util::insertRecord("OverdueInvoice_Email",$array);
			return $inserted_id;
		} else {
			return 0;
		}
	}
	
	function UpdateOrderDetail($where,$array){
		if($array){
			$updated_id = util::updateRecord(ECORDERDETAIL,$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
	
	function UpdateOrderItem($where,$array){
		if($array){
			$updated_id = util::updateRecord('OrderItem',$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
	
	function UpdateOrderItemStatusRefunded($where,$array){
		if($array){
			$updated_id = util::updateRecord("OrderItem",$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
	
	function UpdateInvoiceDueEmailStatus($where,$array){
		if($array){
			$updated_id = util::updateRecord("OverdueInvoice_Email",$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}	
	
	function GetAllClientProducts($strWhere,$fieldaArray=""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		}
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". ECORDERDETAIL ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql); 
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
		
    function DeleteOrderDetail($ID){
		if($ID){
			$deleted_id = util::deleteRecord("OrderDetail","ID = $ID");
			return $deleted_id;
		} else {
			return 0;
		}
	}
	
	function DeletePackageFromOrder($ID,$OrderID){
		if($ID && $OrderID){
			$deleted_id = util::deleteRecord("OrderDetail","PackagesID = $ID AND OrderID = $OrderID ");
			return $deleted_id;
		} else {
			return 0;
		}
	}
	
	function DeleteProductFromOrder($ID,$OrderID){
		if($ID && $OrderID){
			$deleted_id = util::deleteRecord("OrderDetail","ProductID = $ID AND OrderID = $OrderID AND PackagesID = 0");
			return $deleted_id;
		} else {
			return 0;
		}
	}
	
	function GetAllClientOrderdedPackages($strWhere){
		global $link;
		$sql = "SELECT DISTINCT(PackagesID) FROM ". OrderItem ." LEFT JOIN OrderDetail ON OrderItem.ID = OrderDetail.OrderID WHERE      $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$PackagesID = $row['PackagesID'];
			$sql_package = "SELECT * FROM ". Packges ."  WHERE 	ID =  '$PackagesID'" or die("Error in the consult.." . mysqli_error($link));
			$result_package = mysqli_query($link,$sql_package);
			while($row_package = mysqli_fetch_array($result_package)){
				$arr[] = $row_package;
			}
		}
		return $arr; 
	}
		
	function GetAllClientOrderdedProducts($strWhere){
		global $link;
		$sql = "SELECT OrderDetail.* FROM ". OrderItem ." LEFT JOIN OrderDetail ON OrderItem.ID = OrderDetail.OrderID AND OrderDetail.PackagesID=0 WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	
	function DeleteOrder($ID){
		if($ID){
			$deleted_id = util::deleteRecord(ECORDERITEM,"ID = $ID");
			$deleted_orderdetail = util::deleteRecord("OrderDetail","OrderID = $ID");
			return $deleted_id;
		} else {
			return 0;
		}
	}
	
	function hideOrder($ID){
		global $link;
		if($ID){
			$updated_id = mysqli_query($link,"update `OrderDetail` set HideOrder = '1' where OrderID = $ID ");
			return 1;
		} else {
			return 0;
		}
	}
	
	function UpdatePaymentForOrders($OrderID,$Paidthrough,$Notes){
		global $link;
		if($OrderID){
			$updated_id = mysqli_query($link,"update `OrderItem` set `Status` = 'Paid', `PaidThrough` = '$Paidthrough', `NotesForPaidThrough` = '$Notes', `CardCharged` = 0, `ManuallyAddedPayment` = '1' where ID = '$OrderID' ");
			if($updated_id == 1){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	function UpdateRefundForManuallAddedPaymentOrders($OrderID){
		global $link;
		if($OrderID){
			$refunded_id = mysqli_query($link,"update `OrderItem` set `Status` = 'Refunded', `CardCharged` = 0, `PaidThrough` = 'Refunded' where ID = '$OrderID' ");
			if($refunded_id == 1){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	function GetMonthNameFromValue($Value){
		$Month = "";
		if(!empty($Value)){
			if($Value == "01"){
				$Month = "January";
			}elseif($Value == "02"){
				$Month = "February";
			}elseif($Value == "03"){
				$Month = "March";
			}elseif($Value == "04"){
				$Month = "April";
			}elseif($Value == "05"){
				$Month = "May";
			}elseif($Value == "06"){
				$Month = "June";
			}elseif($Value == "07"){
				$Month = "July";
			}elseif($Value == "08"){
				$Month = "August";
			}elseif($Value == "09"){
				$Month = "September";
			}elseif($Value == "10"){
				$Month = "October";
			}elseif($Value == "11"){
				$Month = "November";
			}elseif($Value == "12"){
				$Month = "December";
			}else{
				$Month = "";
			}
		}
		return $Month;
	}
	
	function UpdateOrdertoCancelled($OrderID){
		global $link;
		if($OrderID){
			$refunded_id = mysqli_query($link,"update `OrderItem` set `Status` = 'Cancelled', `Updation_Date` = '".date("Y-m-d h:i:s",time())."' where ID = '$OrderID' ");
			if($refunded_id == 1){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	function Update_Status($OrderID, $Status = "", $Notes){
		global $link;
		if($OrderID){
			if($Status == "Cancelled"){
				$status = mysqli_query($link,"update `OrderItem` set `Status` = 'Cancelled', `NotesForPaidThrough` = '$Notes'  where ID = '$OrderID' ");
			}elseif($Status == "Refunded"){
				$status = mysqli_query($link,"update `OrderItem` set `Status` = 'Refunded',`NotesForPaidThrough` = '$Notes' where ID = '$OrderID' ");
			}elseif($Status == "Unpaid"){
				$status = mysqli_query($link,"update `OrderItem` set `Status` = 'Unpaid',`NotesForPaidThrough` = '$Notes' where ID = '$OrderID' ");
			}elseif($Status == "ChargeBack"){
				$status = mysqli_query($link,"update `OrderItem` set `Status` = 'ChargeBack',`NotesForPaidThrough` = '$Notes' where ID = '$OrderID' ");
			}
			if($status == 1){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	function EPDAPI_Responses($Response_Code){
		$Text = "";
		$Response_Code = trim($Response_Code);
		if(!empty($Response_Code)){
			if($Response_Code == 100){
				$Text = "Transaction was approved.";
			}elseif($Response_Code == 200){
				$Text = "Transaction was declined by processor.";
			}elseif($Response_Code == 201){
				$Text = "Do not honor.";
			}elseif($Response_Code == 202){
				$Text = "Insufficient funds.";
			}elseif($Response_Code == 203){
				$Text = "Over limit.";
			}elseif($Response_Code == 204){
				$Text = "Transaction not allowed.";
			}elseif($Response_Code == 220){
				$Text = "Incorrect payment information.";
			}elseif($Response_Code == 221){
				$Text = "No such card issuer.";
			}elseif($Response_Code == 222){
				$Text = "No card number on file with issuer.";
			}elseif($Response_Code == 223){
				$Text = "Expired card.";
			}elseif($Response_Code == 224){
				$Text = "Invalid expiration date.";
			}elseif($Response_Code == 225){
				$Text = "Invalid card security code.";
			}elseif($Response_Code == 240){
				$Text = "Call issuer for further information.";
			}elseif($Response_Code == 250){
				$Text = "Pick up card.";
			}elseif($Response_Code == 251){
				$Text = "Lost card.";
			}elseif($Response_Code == 252){
				$Text = "Stolen card.";
			}elseif($Response_Code == 253){
				$Text = "Fraudulent card.";
			}elseif($Response_Code == 260){
				$Text = "Declined with further instructions available. (See response text)";
			}elseif($Response_Code == 261){
				$Text = "Declined-Stop all recurring payments.";
			}elseif($Response_Code == 262){
				$Text = "Declined-Stop this recurring program.";
			}elseif($Response_Code == 263){
				$Text = "Declined-Update cardholder data available.";
			}elseif($Response_Code == 264){
				$Text = "Declined-Retry in a few days.";
			}elseif($Response_Code == 265){
				$Text = "Transaction was rejected by gateway.";
			}elseif($Response_Code == 400){
				$Text = "Transaction error returned by processor.";
			}elseif($Response_Code == 410){
				$Text = "Invalid merchant configuration.";
			}elseif($Response_Code == 411){
				$Text = "Merchant account is inactive.";
			}elseif($Response_Code == 420){
				$Text = "Communication error.";
			}elseif($Response_Code == 421){
				$Text = "Communication error with issuer.";
			}elseif($Response_Code == 430){
				$Text = "Duplicate transaction at processor.";
			}elseif($Response_Code == 440){
				$Text = "Processor format error.";
			}elseif($Response_Code == 441){
				$Text = "Invalid transaction information.";
			}elseif($Response_Code == 460){
				$Text = "Processor feature not available.";
			}elseif($Response_Code == 461){
				$Text = "Unsupported card type.";
			}elseif($Response_Code == 300){
				$Text = "Transaction was declined by Credit Card Processor";
			}else{
				$Text = "Unknown Message. Rare case.";
			}
			return $Text;
		}else{
			return "Response Code not available.";
		}
	}
	
	function insertsendgridemaildetails($array){
		if($array){
			$inserted_id = util::insertRecord("SendGridEmail_Statuses",$array);
			return $inserted_id;
		} else {
			return 0;
		}
	}
	
	function FetchClientSendgridEmailStatuses($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM `SendGridEmail_Statuses` where $strWhere order by `SendGridEmail_Statuses`.`Timestamp` DESC " or die("Error in the consult.." . mysqli_error($link));
		
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr;
	}
	
	
}
?>