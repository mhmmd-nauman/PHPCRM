<?php  
    class Tasks extends util {

	function GetAllTasks($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". TASKS ." WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr;
	}

	function GetAllTaskToChecklist($strWhere,$fieldaArray=array("*")){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". EcTaskChecklist ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
		
	function InsertTaskToChecklist($array){
		if($array){
			$inserted_id = util::insertRecord(EcTaskChecklist,$array);
			return $inserted_id;
		} else {
			return 0;
		}
	}
	
	function DeleteTaskToChecklist($ID){
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
		
	function InsertProductToTasks($array){
		if($array){
			$inserted_id = util::insertRecord(PRODUCTSTASKS,$array);
			return $inserted_id;
		} else {
			return 0;
	   }
	}
		
	function DeleteTasks($ID){
		if($ID){
			$deleted_id = util::deleteRecord(TASKS,"ID = $ID");
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

	function UpdateTasksTocheckList($where,$array){
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
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". PRODUCTSTASKS ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}	
		
	function GetAllAssigndProductTasks($strWhere,$fieldaArray = array("*")){
		global $link;
		reset($fieldaArray);
		foreach ((array)$fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ".PRODUCTSTASKS."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}			
		return $arr; 
	}

	function GetTaskCheckListData($strWhere,$fieldaArray=array("*")){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". EcTaskCheckListData ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));;
		$result = mysqli_query($link,$sql);
		return mysqli_fetch_array($result);    
	}
        
	function InsertTaskCheckListData($array){
		if($array){
			$inserted_id = util::insertRecord(EcTaskCheckListData,$array);
			return $inserted_id;
		} else {
			return 0;
		}
	}
	
	function UpdateTaskCheckListData($where,$array){
		if($array){
			$updated_id = util::updateRecord(EcTaskCheckListData,$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
	
	function GetTaskAllCheckListData($strWhere,$fieldaArray=array("*")){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". EcTaskCheckListData ." LEFT JOIN EcTaskChecklist ON EcTaskCheckListData.ID = EcTaskChecklist.ID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr;
	}


	function GetAllCheckListDate($strWhere,$fieldaArray=array("*")){
		global $link;
		reset($fieldaArray);
		foreach ((array)$fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 	
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ".EcTaskCheckListData."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
		}
	}
?>