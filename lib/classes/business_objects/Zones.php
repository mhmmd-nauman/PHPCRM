<?php  

	class Zones extends util {

	function GetAllZones($strWhere,$fieldaArray=""){
		global $link;

			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". ZONES."  WHERE $strWhere "or die("Error in the consult.." . mysqli_error($link));
			 
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql);

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		

	

		function InsertZones($array){

			if($array){

				$inserted_id = util::insertRecord(ZONES,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
                function InsertZones1($array){

			if($array){

				$inserted_id = util::insertRecord(ZONES,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		
		
		
             
               

		function DeleteZones($ID){

			if($ID){

				$deleted_id = util::deleteRecord(ZONES,"ID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}

	
                
		function UpdateZones($where,$array){

			if($array){

				$updated_id = util::updateRecord(ZONES,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		
		
		
           
function GetAllTimeZones($strWhere,$fieldaArray=""){
		global $link;

			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". TIMEZONES."  WHERE $strWhere "or die("Error in the consult.." . mysqli_error($link));
			 
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql);

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}

            
                
          
		
		
	// End of Function	
}
?>