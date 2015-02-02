<?php  
class Users extends util {
	function GetAllUsers($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". USERS ."  LEFT JOIN ".ZONES." ON ".ZONES.".ID = ".USERS.".ZoneID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}	
		return $arr; 
	}
	# Copied from above function and made the changes needed. Since this function was called from 
	# different places so I have made the changes to a new function and called this function in 
	# Users/Users.php page which will not affect any of the other pages.
	function GetAllUsersForUsersPage($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		if(!in_array(2, (array)$_SESSION['user_in_groups'])){
			$sql = "SELECT $strFields FROM ". USERS ." join `Company` on ".USERS.".CompanyID = `Company`.ID LEFT JOIN ".ZONES." ON ".ZONES.".ID = ".USERS.".ZoneID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
		}elseif(in_array(2, (array)$_SESSION['user_in_groups'])){
			$sql = "SELECT $strFields FROM ". USERS ."  LEFT JOIN ".ZONES." ON ".ZONES.".ID = ".USERS.".ZoneID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));		
		}
				
		$result = mysqli_query($link,$sql) ;
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}	
		return $arr; 
	}
	
	function GetAllUsersAndGroupsForUsersPage($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		if(!in_array(2, (array)$_SESSION['user_in_groups'])){
			$sql = "SELECT $strFields FROM ". USERS ." JOIN `Group_Users` on Group_Users.UserID = Users.ID join `Company` on ".USERS.".CompanyID = `Company`.ID LEFT JOIN ".ZONES." ON ".ZONES.".ID = ".USERS.".ZoneID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
		}elseif(in_array(2, (array)$_SESSION['user_in_groups'])){
			$sql = "SELECT $strFields FROM ". USERS ." JOIN `Group_Users` on Group_Users.UserID = Users.ID LEFT JOIN ".ZONES." ON ".ZONES.".ID = ".USERS.".ZoneID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));		
		}
				
		$result = mysqli_query($link,$sql) ;
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}	
		return $arr; 
	}
		
	function GetAgent($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". USERS ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}			
		return $arr; 
	}
	
	function InsertUsers($array){
		if($array){
			$inserted_id = util::insertRecord(USERS,$array);
			return $inserted_id;
		} else {
			return 0;
	   }
	}
	function InsertUsersGroup($array){
		if($array){
			$inserted_id = util::insertRecord("Group_Users",$array);
			return $inserted_id;
		} else {
			return 0;
	   }
	}
		
	function InsertRevision($array){
		if($array){
			$inserted_id = util::insertRecord(REVISION,$array);
			return $inserted_id;
		} else {
			return 0;
	   }
	}
	
	function DeleteUsers($ID){
		if($ID){
			$deleted_id = util::deleteRecord(USERS,"ID = $ID");
			return $deleted_id;
		} else {
			return 0;
		}
	}
       	
	function UpdateUsers($where,$array){
		if($array){
			$updated_id = util::updateRecord(USERS,$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}	
				
	function ValidateUser($login,$password){
		if($login != "" and $password != "" ){
			$User = Users::GetAllUsers("Email = '".trim($login)."'",array(USERS.".*",ZONES.".Name as ZoneName"));
			if($User[0]){
				if($password == trim($User[0]['Password'])){
					return $User[0];
				}
			}
		}
		return 0;
	}
	
	function ValidateUser_super($login,$password){
		if($login != "" and $password != "" ){
			$User = Users::GetAllUsers("Email = '".trim($login)."'",array("*"));
			if(!empty($User)){
				if($password == trim($User[0]['Password'])){
					return 1;
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}
	}
	
	function UpdateReview($where,$array){
		if($array){
			$updated_id = util::updateRecord(MEMBERS,$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
		
	function GetAllUserWithGroup($strWhere,$fieldaArray=""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		}
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". USERS ."  LEFT JOIN Group_Users ON Users.ID = Group_Users.UserID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
		
		
	function DeleteUserGroup($ID){
		if($ID){
			$deleted_id = util::deleteRecord("Group_Users","UserID = $ID");
			return $deleted_id;
		} else {
			return 0;
		}
	}
		
	function GetUserClockInOutTime($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);	
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". USERTIMECARD ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql) ;
		if($result){
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		}
		return $arr; 
	}
	
	function InsertUserClockInTime($array){
		if($array){
			$inserted_id = util::insertRecord(USERTIMECARD,$array);
			return $inserted_id;
		} else {
			return 0;
	   }
	}
		
	function UpdateUserClockTime($where,$array){
		if($array){
			$updated_id = util::updateRecord(USERTIMECARD,$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
                
	function getDaysInWeek ($weekNumber, $year) {
		$time = strtotime($year . '0104 +' . ($weekNumber - 1). ' weeks');
		$mondayTime = strtotime('-' . (date('w', $time) + 0) . ' days', $time);
		$dayTimes = "";
		for ($i = 0; $i < 7; ++$i) {
			$dayTimes[] = date("d-m-Y",strtotime('+' . $i . ' days', $mondayTime))." ";
		}
		return $dayTimes;
	}
	
	function InsertUserClockInTimeComments($array){
		if($array){
			$inserted_id = util::insertRecord('TimeCardUserComments',$array);
			return $inserted_id;
		} else {
			return 0;
		}
	}
                
	function UpdateUserClockInTimeComments($where,$array){
		if($array){
			$updated_id = util::updateRecord('TimeCardUserComments',$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
}
?>