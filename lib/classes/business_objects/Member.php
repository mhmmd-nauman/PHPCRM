<?php  

	class Member extends util {

		

		function GetAllMember($strWhere,$fieldaArray=""){

			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". MEMBERS ."  WHERE $strWhere ";
			
			$result=mysql_query($sql) or die("Query '$sql' failed with error message: \"" . mysql_error () . '"');

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		
		
		function GetAllMemberData($strWhere,$fieldaArray=""){

			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". MemberData ."  WHERE $strWhere ";
			
			$result=mysql_query($sql) or die("Query '$sql' failed with error message: \"" . mysql_error () . '"');

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		
		
		function GetAllClientsOrder($strWhere,$fieldaArray=""){

			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". MEMBERS ."  WHERE $strWhere ";
			
			$result=mysql_query($sql) or die("Query '$sql' failed with error message: \"" . mysql_error () . '"');

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		
		
		
		function GetAllRevision($strWhere,$fieldaArray=""){

			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". REVISION ."  WHERE $strWhere ";
			
			//$encoded_query = base64_encode($sql);
			$result=mysql_query($sql) or eval(RUNTIME_ERROR_FUNCTION."($encoded_query);");

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}

		function GetAllPaymentMethod($strWhere,$fieldaArray=""){

			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". PaymentAccepted ."  WHERE $strWhere ";
			
			//$encoded_query = base64_encode($sql);
			$result=mysql_query($sql) or eval(RUNTIME_ERROR_FUNCTION."($encoded_query);");

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}


		function GetAllMemberWithGroup($strWhere,$fieldaArray=""){

			reset($fieldaArray);

			foreach ($fieldaArray as $field){

				$strFields .=  "".$field . " ,";

			} 

			//remove the last comma

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	


			 $sql="SELECT $strFields FROM ". MEMBERS ."  LEFT JOIN Group_Members ON Member.ID = Group_Members.MemberID WHERE $strWhere ";

			   $sql="SELECT $strFields FROM ". MEMBERS ."  LEFT JOIN Group_Users ON Member.ID = Group_Users.UserID WHERE $strWhere ";

			
			//$encoded_query = base64_encode($sql);
			$result=mysql_query($sql) or die("Query '$sql' failed with error message: \"" . mysql_error () . '"');

			

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			log_error($encoded_query);
			
			return $arr; 

		}

		function GetAllMemberWithRevision($strWhere,$fieldaArray=""){

			reset($fieldaArray);

			foreach ($fieldaArray as $field){

				$strFields .=  "".$field . " ,";

			} 

			//remove the last comma

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

		$sql="SELECT $strFields FROM ". REVISION ."  LEFT JOIN ".MEMBERS." ON Member.ID = Revision.MemberID WHERE $strWhere ";
			//$encoded_query = base64_encode($sql);
			$result=mysql_query($sql) or eval(RUNTIME_ERROR_FUNCTION."($encoded_query);");

			

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			log_error($encoded_query);
			
			return $arr; 

		}

		function GetAllMemberWithHubFlexMenber($strWhere,$fieldaArray=""){

			reset($fieldaArray);

			foreach ($fieldaArray as $field){

				$strFields .=  "".$field . " ,";

			} 

			//remove the last comma

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			 $sql="SELECT $strFields FROM ". MEMBERS ."  LEFT JOIN ".HUBFLXMEMBERS." ON Member.ID = HubFlxMember.MemberID WHERE $strWhere ";
			//$encoded_query = base64_encode($sql);
			$result=mysql_query($sql) or eval(RUNTIME_ERROR_FUNCTION."($encoded_query);");

			

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			log_error($encoded_query);
			
			return $arr; 

		}

		function InsertMember($array){

			if($array){

				$inserted_id = util::insertRecord(MEMBERS,$array);

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
		
		function InsertPaymentMethod($array){

			if($array){

				$inserted_id = util::insertRecord(PaymentAccepted,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
            function DeleteMemberGroup($ID){

			if($ID){

				$deleted_id = util::deleteRecord("Group_Users","MemberID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}
                function DeletePaymentMethod($ID){

			if($ID){

				$deleted_id = util::deleteRecord("PaymentAccepted","MemberID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}

		function DeleteMember($ID){

			if($ID){

				$deleted_id = util::deleteRecord(MEMBERS,"ID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}

	
                function UpdatePaymentMethod($where,$array){

			if($array){

				$updated_id = util::updateRecord(PaymentAccepted,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		function UpdateMember($where,$array){

			if($array){

				$updated_id = util::updateRecord(MEMBERS,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		
		
		function UpdateRevision($where,$array){

			if($array){

				$updated_id = util::updateRecord(REVISION,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		
            function ValidateMember($login,$password){

			if($login != "" and $password !="" ){
                            $Member = util::getSingleRow(MEMBERS,"Email = '".trim($login)."'");
                            //print_r($Member);
                            //exit;
							if($Member){
                                if($password == $Member['Password']){
                                    return $Member;
                                }
                             }
                          }

			return 0;

		  }


            function GetMemberPermissions($member){

			$query_member_in_group  = "SELECT `Group`.ID  FROM  `Group`  

										LEFT JOIN Group_Users ON `Group`.ID = Group_Users.GroupID 

										WHERE Group_Users.`UserID` = '".$member."'";

			 $result_query_member_in_group = mysql_query($query_member_in_group) or die("Query '$query_member_in_group' failed with error message: \"" . mysql_error () . '"');

			 $member_in_group[]= array();

			 while($row=mysql_fetch_array($result_query_member_in_group)){

			 	$member_in_group[]= $row['ID'];

			 }

			 $member_permissions_array = array();

			 foreach($member_in_group as $group){

			 	$query_permissions_in_group  = "SELECT `Permission`.Code  FROM  `Permission`  WHERE Permission.`GroupID` = '".$group."'";

			 	$result_permissions_in_group = mysql_query($query_permissions_in_group) or die("Query '$query_permissions_in_group' failed with error message: \"" . mysql_error () . '"');

			 	while($row_permission=mysql_fetch_array($result_permissions_in_group)){

			 		$member_permissions_array[]= $row_permission['Code'];

			 	}	

			 

			 }

			 

			 return $member_permissions_array;

		 }
                 function UpdateReview($where,$array){

			if($array){

				$updated_id = util::updateRecord(MEMBERS,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}
            function GetAllSaleGroup($strWhere,$fieldaArray=""){

			reset($fieldaArray);

			foreach ($fieldaArray as $field){

				$strFields .=  "".$field . " ,";

			} 

			//remove the last comma

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". Group_Users ."  LEFT JOIN `".Group."` ON `Group`.ID = Group_Users.GroupID LEFT JOIN Member ON Member.ID =  Group_Users.UserID WHERE $strWhere ";
			//$encoded_query = base64_encode($sql);
			$result=mysql_query($sql) or eval(RUNTIME_ERROR_FUNCTION."($encoded_query);");

			

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			log_error($encoded_query);
			
			return $arr; 

		}
		
		
		
		/* Client Task Data Function   */
		
		function GetAllClientTaskData($strWhere,$fieldaArray=""){

			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". ECTASKPRODUCTDATA ."  WHERE $strWhere ";
			
			//$encoded_query = base64_encode($sql);
			$result=mysql_query($sql) or eval(RUNTIME_ERROR_FUNCTION."($encoded_query);");

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		
		
		
		function GetAllClientCitationData($strWhere,$fieldaArray=""){

			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". ECCITATIONDATA ."  WHERE $strWhere ";
			
			//$encoded_query = base64_encode($sql);
			$result=mysql_query($sql) or die("Query '$sql' failed with error message: \"" . mysql_error () . '"');

			while($row=mysql_fetch_array($result)){

				$arr[] = $row;

			}
			
			//log_error($encoded_query);
			
			return $arr; 

		}
		
	function InsertClientTaskData($array){

			if($array){

				$inserted_id = util::insertRecord(ECTASKPRODUCTDATA,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		function InsertClientQuestionTaskData($array){

			if($array){

				$inserted_id = util::insertRecord(ECTASKPRODUCTDATA,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		function UpdateClientQuestionTaskData($where,$array){

			if($array){

				$updated_id = util::updateRecord(ECTASKPRODUCTDATA,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		
	function UpdateClientTaskData($where,$array){

			if($array){

				$updated_id = util::updateRecord(ECTASKPRODUCTDATA,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		
		function UpdateClientCitationData($where,$array){

			if($array){

				$updated_id = util::updateRecord(ECCITATIONDATA,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		
	function InsertClientCitationData($array){

			if($array){

				$inserted_id = util::insertRecord(ECCITATIONDATA,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}	
	// End of Function	
}
?>