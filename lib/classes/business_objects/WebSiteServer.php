<?php  
class WebSiteServer extends util
{

	function GetAllWebSiteServer($strWhere,$fieldaArray=""){
            global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		//remove the last comma
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		 
                 $sql="SELECT $strFields FROM  ".WEBSITESERVER." WHERE $strWhere" or die("Error in the consult.." . mysqli_error($link));
		 
		$result=mysqli_query($link,$sql);
		
		while($row=mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	
        function InsertWebSiteServer($array){
		if($array){
	   		$inserted_id = util::insertRecord(WEBSITESERVER,$array);
		 	return $inserted_id;
		} else {
	   		return 0;
	   }
	}
	

	function DeleteWebSiteServer($id){
		if($id){
		 $deleted_id = util::deleteRecord(WEBSITESERVER,"ID = $id");
		 return $deleted_id;
		} else {
		return 0;
	   }
	 }
	
	function UpdateWebSiteServer($strWhere,$array){
	 if($array){
	 		$updated_id = util::updateRecord(WEBSITESERVER,$strWhere,$array);
			return $updated_id;
		} else {
	   		return 0;
	   }
	}

 }
?>