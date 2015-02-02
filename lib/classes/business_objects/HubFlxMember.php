<?php  
class HubFlxMember extends util
{

	function GetAllHubFlxMember($strWhere,$fieldaArray=""){
	global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		//remove the last comma
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		  $sql="SELECT $strFields FROM  HubFlxMember
		  LEFT JOIN Member ON HubFlxMember.MemberID = Member.ID
		   WHERE $strWhere" or die("Error in the consult.." . mysqli_error($link));
		 
		$result=mysqli_query($link,$sql) ;
		//$row=$this->FetchObject($result);
		while($row=mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	function GetAllHubFlxMemberWithGroup($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);

			foreach ($fieldaArray as $field){

				$strFields .=  "".$field . " ,";

			} 

			//remove the last comma

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

		  $sql="SELECT $strFields FROM HubFlxMember  LEFT JOIN Group_Users ON HubFlxMember.MemberID = Group_Users.UserID LEFT JOIN Member ON HubFlxMember.MemberID = Member.ID WHERE $strWhere "or die("Error in the consult.." . mysqli_error($link));
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;
			

			while($row=mysqli_fetch_array($result))
			{
		
				$arr[] = $row;

			}
			
			log_error($encoded_query);
			
			return $arr; 

	}
        function InsertHubFlxMember($array){
		if($array){
	   		$inserted_id = util::insertRecord("HubFlxMember",$array);
		 	return $inserted_id;
		} else {
	   		return 0;
	   }
	}
	function InsertMember($array){

			if($array){

				$inserted_id = util::insertRecord(MEMBERS,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}

	function DeleteHubFlxMember($id){
		if($id){
		 $deleted_id = util::deleteRecord("HubFlxMember","ID = $id");
		 return $deleted_id;
		} else {
		return 0;
	   }
	 }

	function UpdateHubFlxMember($strWhere,$array){
	 if($array){
	 		$updated_id = util::updateRecord('HubFlxMember',$strWhere,$array);
			return $updated_id;
		} else {
	   		return 0;
	   }
	}
        
        // Temp functions to fix product data
        function GetAllWebSiteData($strWhere,$fieldaArray=""){
			global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		//remove the last comma
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		  $sql="SELECT $strFields FROM  ".ECTASKPRODUCTDATA."
		   WHERE $strWhere"or die("Error in the consult.." . mysqli_error($link));
		 
		$result=mysqli_query($link,$sql) ;
		//$row=$this->FetchObject($result);
		while($row=mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	function InsertWebSiteData($array){

			if($array){

				$inserted_id = util::insertRecord(ECTASKPRODUCTDATA,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
       function UpdateWebSiteData($strWhere,$array){
	 if($array){
	 		$updated_id = util::updateRecord(ECTASKPRODUCTDATA,$strWhere,$array);
			return $updated_id;
		} else {
	   		return 0;
	   }
	}
	

 }
?>