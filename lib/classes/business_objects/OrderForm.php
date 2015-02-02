<?php  

	class OrderForm extends util {

		

		function GetAllOrderFormData($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". ORDERFORM ."  WHERE $strWhere "or die("Error in the consult.." . mysqli_error($link));
			
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
		

	

		function InsertVendors($array){

			if($array){

				$inserted_id = util::insertRecord(VENDORS,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		function InsertPayment($array){

			if($array){

				$inserted_id = util::insertRecord("Payments",$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		
		
		
             
               

		function DeleteVendors($ID){

			if($ID){

				$deleted_id = util::deleteRecord(VENDORS,"ID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}

	
                
		function UpdateOrderFormData($where,$array){

			if($array){

				$updated_id = util::updateRecord(ORDERFORM,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		
		
		
           


            
                
          
		
		
	// End of Function	
}
?>