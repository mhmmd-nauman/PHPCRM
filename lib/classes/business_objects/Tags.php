<?php  

	class Tags extends util {

		

		function GetAllTags($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			//$sql="SELECT $strFields FROM ". USERS ."   WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$sql="SELECT $strFields FROM ". TAGS ."  WHERE $strWhere" or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql) ;
                        if($result){
                            while($row=mysqli_fetch_array($result)){

                                    $arr[] = $row;

                            }
                        }
			//log_error($encoded_query);
			
			return $arr; 

		}
		
		function GetAllTagsList($fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			//$sql="SELECT $strFields FROM ". USERS ."   WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$sql="SELECT $strFields FROM ". TAGS ."  ORDER BY 'Sorting'" or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql) ;
                        if($result){
                            while($row=mysqli_fetch_array($result)){

                                    $arr[] = $row;

                            }
                        }
			//log_error($encoded_query);
			
			return $arr; 

		}
		
		
		function GetClientTags($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){

			$strFields .=  "".$field . " ,";

			} 
			
			//echo "remove the last comma";

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			//$sql="SELECT $strFields FROM ". USERS ."   WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$sql="SELECT $strFields FROM ClientTags WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql) ;
                        if($result){
                            while($row=mysqli_fetch_array($result)){

                                    $arr[] = $row;

                            }
                        }
			//log_error($encoded_query);
			
			return $arr; 

		}

		
		function InsertClientTag($array){

			if($array){

				$inserted_id = util::insertRecord(CLIENTTAGS,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		function InsertTag($array){

			if($array){

				$inserted_id = util::insertRecord(TAGS,$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		function AddTagToClient($array){

			if($array){

				$inserted_id = util::insertRecord("ClientTags",$array);

				return $inserted_id;

			} else {

				return 0;

		   }

		}
		
		
		function UpdateClientTag($where,$array){
			if($array){
				$updated_id = util::updateRecord("ClientTags",$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}
			
		
		
		
	
		
             

		function DeleteTag($ID){

			if($ID){

				$deleted_id = util::deleteRecord(TAGS,"ID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}

	
       	
		function UpdateTag($where,$array){

			if($array){

				$updated_id = util::updateRecord(TAGS,$where,$array);

				return $updated_id;

			} else {

				return 0;

			}

		}	
		
		
			
		
           

            
		
		function GetAllClientWithTags($strWhere,$fieldaArray=""){
		global $link;

			reset($fieldaArray);

			foreach ($fieldaArray as $field){

				$strFields .=  "".$field . " ,";

			} 

			//remove the last comma

			$strFields = substr($strFields, 0, strlen($strFields) - 1);	

			$sql="SELECT $strFields FROM ". TAGS ."  LEFT JOIN CLIENTTAGS ON Tags.ID = CLIENTTAGS.TagID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;
			while($row=mysqli_fetch_array($result)){

				$arr[] = $row;

			}
			
			
			
			return $arr; 

		}
		
		
		 function DeleteClientTag($ID){

			if($ID){

				$deleted_id = util::deleteRecord("CLIENTTAGS","TagID = $ID");

				return $deleted_id;

			} else {

				return 0;

			}

		}
		
   
		
	// End of Function	
}
?>