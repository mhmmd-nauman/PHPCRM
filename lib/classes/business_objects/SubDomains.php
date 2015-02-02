<?php  
class SubDomains extends util
{

	function GetAllSubDomains($strWhere,$fieldaArray=""){
	global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		//remove the last comma
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		 
                  $sql="SELECT $strFields FROM  ".SubDomains."
		  LEFT JOIN ".CLIENTS." ON ".SubDomains.".ClientID = ".CLIENTS.".ID
		   WHERE $strWhere" ;
		 
		$result=mysqli_query($link,$sql);
		//$row=$this->FetchObject($result);
                if($result){
                    while($row=mysqli_fetch_array($result)){
                            $arr[] = $row;
                    }
                }
		return $arr; 
	}
	function InsertSubDomains($array){
		if($array){
	   		$inserted_id = util::insertRecord(SubDomains,$array);
		 	return $inserted_id;
		} else {
	   		return 0;
	   }
	}
	

	function DeleteSubDomains($id){
		if($id){
		 $deleted_id = util::deleteRecord(SubDomains,"ID = $id");
		 return $deleted_id;
		} else {
		return 0;
	   }
	 }
	 
	 
	
	function UpdateSubDomains($strWhere,$array){
	 if($array){
	 		$updated_id = util::updateRecord(SubDomains,$strWhere,$array);
			return $updated_id;
		} else {
	   		return 0;
	   }
	}

 }
?>