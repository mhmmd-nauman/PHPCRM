<?php  

	class Products extends util {
		function GetAllProduct($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". PRODUCT ."  WHERE $strWhere" or die("Error in the consult.." . mysqli_error($link));
			
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
		
		
		function GetAllProductWithSubscription($strWhere,$fieldaArray = ""){
			global $link;
			reset($fieldaArray);
			foreach ($fieldaArray as $field){
				$strFields .=  "".$field . " ,";
			}			
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql = "SELECT $strFields FROM ". PRODUCTSUBSCRIPTION ." WHERE $strWhere" or die("Error in the consult.." . mysqli_error($link));
			$result = mysqli_query($link,$sql);
			if($result){
				while($row = mysqli_fetch_array($result)){
					$arr[] = $row;
				}
			}
			return $arr;
		}
		
		function UpdateProduct($where,$array){
			if($array){
				$updated_id = util::updateRecord(PRODUCT,$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}
                
                
			function GetAllProductSubscription($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". ProductSubscription ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
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
		
		  function Deleteproduct($ID){
		global $link;
			if($ID){

				$deleted_id = util::deleteRecord(PRODUCT,"ID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}
		function GetAllProductPrice($strWhere,$fieldaArray=""){
			global $link;	
			reset($fieldaArray);
			foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". PRODUCTPRICE ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql);

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		function InsertProductPrice($array){
		global $link;
			if($array){

				$inserted_id = util::insertRecord(PRODUCTPRICE,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
	function UpdateProductPrice($where,$array){
    global $link;
			if($array){

				$updated_id = util::updateRecord(PRODUCTPRICE,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		function DeleteproductPrice($ID){
         global $link;
			if($ID){

				$deleted_id = util::deleteRecord(PRODUCTPRICE,"ID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}
}
?>