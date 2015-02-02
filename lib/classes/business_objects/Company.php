<?php  
	class Company extends util {
	
	function GetAllCompany($strWhere,$fieldaArray=array("*")){
		global $link;
		reset($fieldaArray);
		$strFields = "";
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		}
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". COMPANY ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr; 
	}
		
	function getAllComapnyDropdown($type = "", $selected = "",$width = ""){
		$CompanyArray = self::GetAllCompany(" 1 ",array("*"));
		if($type == "html"){
		$select = "";
		if(!empty($width)){
			$width = $width."px";
		}
		$select .= "<select name='CompanyName' id='companyName' class='product' style='width:$width;'>";
		$select .= "<option value=''>Select a Company</option>";
		foreach((array)$CompanyArray as $SingleCompany){
			$CompanyID = $SingleCompany['ID'];
			$Companyname = $SingleCompany['CompanyName'];
			
			$show = ($CompanyID == $selected) ? "selected" : "";
			
			$select .= "<option value='$CompanyID' $show>$Companyname</option>";
			$CompanyID = $Companyname = "";
		}
		$select .= "</select>";
		}else{
			$select = array();
			$select = $CompanyArray;
		}
		return $select;
	}

	function InsertCompany($array){
		if($array){
			$inserted_id = util::insertRecord(COMPANY,$array);
			return $inserted_id;
		} else {
			return 0;	
		}
	}
			
	function DeleteCompany($ID){
		if($ID){
			$deleted_id = util::deleteRecord(COMPANY,"ID = $ID");
			return $deleted_id;
		} else {
			return 0;
		}
	}
	
	function UpdateCompany($where,$array){
		if($array){
			$updated_id = util::updateRecord(COMPANY,$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}	
}
?>