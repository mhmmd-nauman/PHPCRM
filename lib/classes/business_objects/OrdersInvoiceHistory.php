<?php  

	class OrdersInvoiceHistory extends util {

	function GetAllOrderInvoice($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
                        //echo "SELECT $strFields FROM ". ECORDERITEM ."  WHERE $strWhere <br>";
			$sql="SELECT $strFields FROM ". ORDERINVOICEHISTORY ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql);
                        if($result){
                            while($row=mysqli_fetch_array($result)){

                                    $arr[] = $row;

                            }
                        }
			//log_error($encoded_query);
			
			return $arr; 

	}
	function InsertInvoice($array){
                   global $link;
			if($array){

				$inserted_id = util::insertRecord(ORDERINVOICEHISTORY,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		


	// End of Function	
}
?>