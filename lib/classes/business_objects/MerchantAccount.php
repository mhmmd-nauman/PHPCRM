<?php  

	class MerchantAccount extends util {
            function GetAllMerchantAccount($strWhere,$fieldaArray=""){
				global $link;
                reset($fieldaArray);
		foreach ($fieldaArray as $field){
                    $strFields .=  "".$field . " ,";
                } 
		//echo "remove the last comma";
                $strFields = substr($strFields, 0, strlen($strFields) - 1);	
                $sql="SELECT $strFields FROM ". ECMANAGEMERCHANTACC ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result=mysqli_query($link,$sql); 
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $arr[] = $row;
                    }
                }
		return $arr; 
            }
            function GetAllTransactionID($strWhere,$fieldaArray=""){
                reset($fieldaArray);
		foreach ($fieldaArray as $field){
                    $strFields .=  "".$field . " ,";
                } 
		//echo "remove the last comma";
                $strFields = substr($strFields, 0, strlen($strFields) - 1);	
                $sql="SELECT $strFields FROM ". ECMANAGEMERCHANTACC ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result=mysqli_query($link,$sql);
                while($row=mysqli_fetch_array($result)){
                    $arr[] = $row;
                }
		return $arr; 
            }
			
	  function GetAllMerchantResponse($strWhere,$fieldaArray=""){
				global $link;
                reset($fieldaArray);
				foreach ($fieldaArray as $field){
                    $strFields .=  "".$field . " ,";
                } 
		//echo "remove the last comma";
                $strFields = substr($strFields, 0, strlen($strFields) - 1);	
                $sql="SELECT $strFields FROM MerchantRefundedResponse  WHERE $strWhere " or die("Error in the consult.." . 		                mysqli_error($link));
		        $result=mysqli_query($link,$sql); 
                if($result){
                    while($row=mysqli_fetch_array($result)){
                        $arr[] = $row;
                    }
                }
		return $arr; 
            }		
			
			
            function InsertMerchantAccount($array){

			if($array){

				$inserted_id = util::insertRecord(ECMANAGEMERCHANTACC,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
            function DeleteMerchantAccount($ID){

			if($ID){

				$deleted_id = util::deleteRecord(ECMANAGEMERCHANTACC,"MemberID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}
            function UpdateMerchantAccount($where,$array){

			if($array){

				$updated_id = util::updateRecord(ECMANAGEMERCHANTACC,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}
		 function InsertTransacions($array){

			if($array){

				$inserted_id = util::insertRecord(ECMERCHANTACCTRANSACTIONS,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		function InsertMerchantRefundedResponse($array){

			if($array){

				$inserted_id = util::insertRecord("MerchantRefundedResponse",$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		function GetAllTransactionResponce($strWhere,$fieldaArray=""){
		global $link;
                reset($fieldaArray);
		foreach ($fieldaArray as $field){
                    $strFields .=  "".$field . " ,";
                } 
		//echo "remove the last comma";
                $strFields = substr($strFields, 0, strlen($strFields) - 1);	
                 $sql="SELECT $strFields FROM ". ECMERCHANTACCTRANSACTIONS ."  WHERE $strWhere " or die("Error in the consult.." . 		                mysqli_error($link));
		$result=mysqli_query($link,$sql);
                while($row=mysqli_fetch_array($result)){
                    $arr[] = $row;
                }
		return $arr; 
            }
	// End of Function	
}
?>