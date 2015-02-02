<?php  

	class Checklist extends util {
		

	function GetAllCheckList($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". ECCHECKLIST ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		
		function GetAllCheckListDate($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". EcTaskChecklist ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
	function InsertCheckListDate($array){

			if($array){

				$inserted_id = util::insertRecord(EcTaskChecklist,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
function DeleteChecklistDate($ID){

			if($ID){

				$deleted_id = util::deleteRecord(EcTaskChecklist,"ID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}
		function InsertTasks($array){

			if($array){

				$inserted_id = util::insertRecord(TASKS,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		
		function InsertEcChecklist($array){

			if($array){

				$inserted_id = util::insertRecord(ECCHECKLIST,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		function InsertProductToTasks($array){

			if($array){

				$inserted_id = util::insertRecord(PRODUCTSTASKS,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		
             
               

		function DeleteEcChecklist($ID){

			if($ID){

				$deleted_id = util::deleteRecord(ECCHECKLIST,"ID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}

	
		function DeleteAssignTask($ID){

			if($ID){

				$deleted_id = util::deleteRecord(PRODUCTSTASKS,"ProductID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}
	
	
                
		function UpdateTasks($where,$array){

			if($array){

				$updated_id = util::updateRecord(TASKS,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	

    function UpdateEcChecklist($where,$array){

			if($array){

				$updated_id = util::updateRecord(EcTaskChecklist,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}

		
			
		function GetAllTasksAssigndToProduct($strWhere,$fieldaArray=array("*")){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". PRODUCTSTASKS ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}	
		
			function GetAllAssigndProductTasks($strWhere,$fieldaArray=array("*")){
			global $link;
			reset($fieldaArray);
			
			
			foreach ((array)$fieldaArray as $field){

			$strFields .=  "".$field . " ,";



			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ".PRODUCTSTASKS."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}

		
	// End of Function	
}

?>