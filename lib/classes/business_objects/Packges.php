<?php  

	class Packges extends util {

		

		function GetAllPackges($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". PACKGES ."  WHERE $strWhere "or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql);

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		
	function GetAllProductToPackges($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". PACKGESPRODUCTS ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql);

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		
		function GetAllClientProduct($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". PACKGESPRODUCTS ."  WHERE $strWhere "or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		
	

		function InsertPackge($array){

			if($array){

				$inserted_id = util::insertRecord(PACKGES,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		

		
		function InsertProductToPackage($array){

			if($array){

				$inserted_id = util::insertRecord("PackgesProducts",$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
			
			function InsertMemberID($array){

			if($array){

				$inserted_id = util::insertRecord("PackgesProducts",$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
			
		
             
               

		function DeletePackge($ID){

			if($ID){

				$deleted_id = util::deleteRecord(PACKGES,"ID = $ID");
				$deleted_id = util::deleteRecord(PACKGESPRODUCTS,"PackagesID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}

		function DeletePackageProduct($ID){

			if($ID){

				$deleted_id = util::deleteRecord(PACKGESPRODUCTS,"PackagesID = $ID");
				$deleted_id = util::deleteRecord(PACKGESPRODUCTS,"ID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}
	
                
		function UpdatePackge($where,$array){

			if($array){

				$updated_id = util::updateRecord(PACKGES,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		
}
?>