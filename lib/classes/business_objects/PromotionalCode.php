<?php  
class PromotionalCode extends util
{

	function GetAllPromotionalCodes($strWhere,$fieldaArray=""){
	global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		//remove the last comma
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql="SELECT $strFields FROM  PromotionalCode WHERE $strWhere" or die("Error in the consult.." . mysqli_error($link));
		$result=mysqli_query($link,$sql);
		//$row=$this->FetchObject($result);
		while($row=mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	
 
	

	function DeletePromotionalCode($id){
	global $link;
		if($id){
		 $deleted_id = util::deleteRecord("PromotionalCode","ID = $id");
		 return $deleted_id;
		} else {
		return 0;
	   }
	 }

	
	function InsertPromotionalCode($array){
	global $link;
		if($array){
	   		$inserted_id = util::insertRecord("PromotionalCode",$array);
		 	return $inserted_id;
		} else {
	   		return 0;
	   }
	}
	
	function UpdatePromotionalCode($strWhere,$array){
	global $link;
	 if($array){
	 		$updated_id = util::updateRecord('PromotionalCode',$strWhere,$array);
			return $updated_id;
		} else {
	   		return 0;
	   }
	}
	

 }
?>