<?php  
class SystemSetting extends util
{
	# Below function returns the settings of the individual companies.
	# Such as the Logo which shows up in the header area, is different
	# for different companies and few more things.
	function GetAllSystemSetting($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM `SystemSettings` WHERE $strWhere" or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	
	function GetCompanyDetails($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM `Company` WHERE $strWhere" or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr;
	}
	
	function InsertSystemSetting($array){
		if($array){
		$inserted_id = util::insertRecord("SystemSettings",$array);
			return $inserted_id;
		} else {
			return 0;
		}
	}

	function DeleteSystemSetting($id){
		if($id){
			$deleted_id = util::deleteRecord("SystemSettings","ID = $id");
			return $deleted_id;
		} else {
			return 0;
		}
	}

	function UpdateSystemSetting($strWhere,$array){
		if($array){
			$updated_id = util::updateRecord('SystemSettings',$strWhere,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
}
?>