<?php  
class WebSites extends util
{

	function GetAllWebsites($strWhere,$fieldaArray=""){
	global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		//remove the last comma
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		 //echo  "SELECT $strFields FROM  ".WEBSITES."
		 // LEFT JOIN ".CLIENTS." ON ".WEBSITES.".MemberID = ".CLIENTS.".ID
		 //  WHERE $strWhere";
                 $sql="SELECT $strFields FROM  ".WEBSITES."
		  LEFT JOIN ".CLIENTS." ON ".WEBSITES.".MemberID = ".CLIENTS.".ID
		   WHERE $strWhere" or die("Error in the consult.." . mysqli_error($link));
		 
		$result=mysqli_query($link,$sql);
		//$row=$this->FetchObject($result);
		while($row=mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	function GetAllWebsitesWithGroup($strWhere,$fieldaArray=""){
global $link;
			reset($fieldaArray);

			foreach ($fieldaArray as $field){

				$strFields .=  "".$field . " ,";

			} 

			//remove the last comma

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

                        $sql="SELECT $strFields FROM ".WEBSITES."  
                            LEFT JOIN ".GROUPUSERS." ON ".WEBSITES.".MemberID = ".GROUPUSERS.".UserID 
                                LEFT JOIN ".CLIENTS." ON ".WEBSITES.".MemberID = ".CLIENTS.".ID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql);

			

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			return $arr; 

	}
        function InsertWebSite($array){
		if($array){
	   		$inserted_id = util::insertRecord(WEBSITES,$array);
		 	return $inserted_id;
		} else {
	   		return 0;
	   }
	}
	

	function DeleteWebSite($id){
		if($id){
		 $deleted_id = util::deleteRecord(WEBSITES,"ID = $id");
		 return $deleted_id;
		} else {
		return 0;
	   }
	 }
	 
	 function DeleteRevision($id){
		if($id){
		 $deleted_id = util::deleteRecord(REVISION,"ID = $id");
		 return $deleted_id;
		} else {
		return 0;
	   }
	 }
	
	function UpdateWebsite($strWhere,$array){
	 if($array){
	 		$updated_id = util::updateRecord(WEBSITES,$strWhere,$array);
			return $updated_id;
		} else {
	   		return 0;
	   }
	}

 }
?>