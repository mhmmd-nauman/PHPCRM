<?php  
	class Groups extends util {
	
		function GetAllMemberWithGroup($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);

			foreach ($fieldaArray as $field){

				$strFields .=  "".$field . " ,";

			} 

			//remove the last comma

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM Group_Users LEFT JOIN Users ON Users.ID = Group_Users.UserID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;

			

			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			log_error($encoded_query);
			
			return $arr; 

		}
		
		function GetAllGroupsUsersPage($strWhere,$fieldaArray=""){
			global $link;	
			reset($fieldaArray);
			foreach ($fieldaArray as $field){
				$strFields .=  "".$field . " ,";
				
				
			} 
			//remove the last commap
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
	   		$sql="SELECT $strFields FROM `". Group ."` JOIN Group_Users on Group.ID=Group_Users.GroupID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql) ;
			
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			return $arr; 
		}
		
		function GetAllUsersAndGroups($strWhere,$fieldaArray=""){
			global $link;	
			reset($fieldaArray);
			foreach ($fieldaArray as $field){
				$strFields .=  "".$field . " ,";
				
				
			} 
			//remove the last commap
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
	   		$sql="SELECT $strFields FROM `Users` JOIN `Group_Users` on `Users`.ID = `Group_Users`.UserID  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql) ;
			
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			return $arr; 
		}
		
		function GetAllGroups($strWhere,$fieldaArray=""){
			global $link;	
			reset($fieldaArray);
			foreach ($fieldaArray as $field){
				$strFields .=  "".$field . " ,";
				
				
			} 
			//remove the last commap
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
	   		$sql="SELECT $strFields FROM `". Group ."` WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql) ;
			
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			return $arr; 
		}
		
		function GetAllGroupsTheme($strWhere,$fieldaArray=""){
			reset($fieldaArray);
			foreach ($fieldaArray as $field){
				$strFields .=  "".$field . " ,";
			} 
			//remove the last comma
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql="SELECT $strFields FROM `".Group_Tag."` WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql) ;
			
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			return $arr; 
		}
		
		function GetMemberGroups($strWhere,$fieldaArray=""){
				global $link;
				reset($fieldaArray);

			foreach ($fieldaArray as $field){

				$strFields .=  "".$field . " ,";

			} 
			
			//remove the last comma
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
	    	$sql="SELECT $strFields FROM `". Group_Users ."` WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql) ;
			
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			return $arr; 
		}
		function UpdateGroup($where,$array){
			if($array){
				$updated_id = util::updateRecord("`Group`",$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}	
		function UpdateMemberGroup($where,$array){
			if($array){
				$updated_id = util::updateRecord("Group_Users",$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}	
		
		function insertMemberGroup($array){
			if($array){
				$inserted_id = util::insertRecord("Group_Users",$array);
				return $inserted_id;
			} else {
				return 0;
		   }
		}
		
		function UpdateGroupMember($where,$array){
			if($array){
				$updated_id = util::updateRecord("Group_Users",$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}	
		function UpdateGrouptheme($where,$array){
			if($array){
				$updated_id = util::updateRecord("`Group`",$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}	
	
		
		
		function InsertGroupTheme($array){
			if($array){
				$inserted_id = util::insertRecord("Group_Tag",$array);
				return $inserted_id;
			} else {
				return 0;
		   }
		}
		
		function DeleteGroup($ID){
			if($ID){
				$deleted_id = util::deleteRecord("`Group`","ID = $ID");
				return $deleted_id;
			} else {
				return 0;
			}

		}
                function InsertGroup($array){
			if($array){
			
				$inserted_id = util::insertRecord("`Group`",$array);
				
				return $inserted_id;
			} else {
				return 0;
		   }
		}
		
}
 
?>