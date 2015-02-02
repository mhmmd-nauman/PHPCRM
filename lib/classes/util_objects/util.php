<?php
class util
{
    function util (){
	


    }
	   
      function insertRecord($strTable, $arrValue){
         global $link;
             $strQuery = "	INSERT INTO $strTable (";

            reset($arrValue);
                    while(list ($strKey, $strVal) = each($arrValue))
                    {
                    $strQuery .= $strKey . ",";
                    }

            // remove last comma
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 1);

            $strQuery .= ") VALUES (";

            reset($arrValue);
                    while(list ($strKey, $strVal) = each($arrValue))
                    {
                    $strQuery .= "'" . $this->fixString($strVal) . "',";
                    }

            // remove last comma
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 1);
            $strQuery .= ");" or die("Error in the consult.." . mysqli_error($link));
              // $strQuery."<br>";
            // execute query
             
            // echo $strQuery;

            // echo "<br/>";
            mysqli_query($link,$strQuery) ;

            $last_inserted_id = mysqli_insert_id($link);
            return ($last_inserted_id);


     }//endof function
     
	  function fixString($strString){
        
			$strString = trim($strString);
			$strString = str_replace("'", "''", $strString);
			$strString = str_replace("\'", "'", $strString);
			$strString = str_replace("", ",", $strString);
			$strString = str_replace("\\", "", $strString);
			$strString = str_replace("\"", "&#34;", $strString);
			$strString = str_replace('\"', '"', $strString);
			return $strString;

      }//endof function
	 
     
    function updateRecord($strTable, $strWhere, $arrValue){
        global $link;
	$strQuery = "	UPDATE $strTable SET ";
	
	reset($arrValue);
	
		while (list ($strKey, $strVal) = each ($arrValue))
		{
		$strQuery .= $strKey . "='" . $this->fixString($strVal) . "',";
		}
	
	// remove last comma
	$strQuery = substr($strQuery, 0, strlen($strQuery) - 1);
	
	 $strQuery .= " WHERE $strWhere;" or die("Error in the consult.." . mysqli_error($link));
	
	  //execute query
     //echo" hhhhfhdhfdfdhdhhj";
	 //echo $strQuery;
	 //echo "<br />";
	 //echo"quary fail";
	  mysqli_query($link,$strQuery) ;
	 
     
	 return mysqli_affected_rows( $link);
	
	}



      function deleteRecord($strTable, $strCriteria){
	  global $link;

	 $strQuery = "DELETE FROM $strTable WHERE $strCriteria" or die("Error in the consult.." . mysqli_error($link));
	//echo"vcvcvcvc";		
	 mysqli_query($link,$strQuery) ;
	
         return mysqli_affected_rows($link);
  }//endof function



  
    function getSingleRow($tbl,$condition){
	global $link;
        $query="SELECT * FROM $tbl WHERE $condition" or die("Error in the consult.." . mysqli_error($link));
		
		$result=mysqli_query($link,$query);
		$row = mysqli_fetch_array($result);
		return $row;
    }


   function getMultipleRowAssoc($tbl,$condition){
   global $link;
            $query="SELECT * FROM $tbl WHERE $condition" or die("Error in the consult.." . mysqli_error($link));
			// "$query";
			$result=mysqli_query($link,$query)  ;
			//$row=$this->FetchObject($result);
			$arr = array();
			while($row=mysqli_fetch_assoc($result)){
				array_push($arr,$row);
			}
			return $arr;
    }
	
	
       function getMultipleRow($tbl,$condition){
        global $link;
            $query="SELECT * FROM $tbl WHERE $condition" or die("Error in the consult.." . mysqli_error($link));
			//echo "$query";
			$result=mysqli_query($link,$query);
			//$row=$this->FetchObject($result);
			$arr = array();
			while($row=mysqli_fetch_array($result)){
			array_push($arr,$row);
			}
			return $arr;
    }
	
	
	

	 
	
	
	function FetchObject($result){
	global $link;
		$row=mysqli_fetch_object($result);
		return $row;
	}	 
	


	
	function sendMail($to,$from,$subject,$message){
	global $link;
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "To: $to <".$to.">\r\n";
	$headers .= "'From: WebsiteBuilder <".$from.">'\r\n";
	
	

	
	
	$message_body = util::getMailHeader();
	$message_body.= $message;
	$message_body.= util::getMailFooer();
	 //echo $message_body;
	 
	 
	  return mail($to,$subject,$message_body,$headers) ;
	 
	
	}
		
   function getMailHeader(){
	
	
	$header.="<table align='center' width='779'>";
	
	$header.="<tr>";
	$header.="<td>";
	$header.="<tr>";
	$header.="<td >";
	return $header;
	
  }
  
  	function getMailFooer(){
	
	$header.="</td>";
	$header.="</tr>";
	$header.="<tr>";
	$header.="<td>";
	$header.="&nbsp;</td>";
	$header.="</tr>";
	$header.="<tr>";
	$header.="<td>";
	$header.="&nbsp;</td>";
	$header.="</tr>";
	$header.="<tr>";
	$header.="<td >";
	$header.="Thanks,";
	$header.="</td>";
	$header.="</tr>";
	$header.="<tr>";
	$header.="<td>";
	$header.="&nbsp;</td>";
	$header.="</tr>";
	
	$header.="<tr>";
	$header.="<td >";
	$header.="Site Administrator ";
	$header.="</td>";
	$header.="</tr>";
	
	
	$header.="</table>";
	return $header;
	
  }
/*
function copyFile($FilePath,&$_FILES,$fieldName,$fileTypes=array('image/jpeg', 'image/pjpeg', 'image/png', 'image/gif')){

        //print_r($_FILES);
		
		if (!in_array($_FILES[$fieldName]['type'],$fileTypes)){
			
			
		
		 return "INVLAIDFILETYPE";
		
		}

         // check size
		if ($_FILES[$fieldName]['size'] > 125542 ){
			
	    return "INVLAIDFILESIZE";
		    
		}

          //security error
		if (strstr($_FILES[$fieldName]['name'], "..")!= ""){
			
			return "SECURITYERROR";
			
	    }
                
		//echo "it is there";
			 
		 $file_ext_array  = explode(".",$_FILES[$fieldName]['name']);
		 $file_ext = ".".$file_ext_array[1];
			

		 $pic_id = time();
		 $pic_id = $pic_id."_".$file_ext_array[0];
			
		 move_uploaded_file($_FILES[$fieldName]['tmp_name'], $FilePath.$pic_id.$file_ext) or die("Could not copy the file!");
			
				
		return $pic_id.$file_ext ;
		
		

 }
*/
function checkEmail($email){
  // checks proper syntax
  if( !preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
  {
    return false;
  }     

  return true;
  // gets domain name
  list($username,$domain)=split('@',$email);
  // checks for if MX records in the DNS
  $mxhosts = array();
  if(!getmxrr($domain, $mxhosts))
  {
    // no mx records, ok to check domain
    if (!fsockopen($domain,25,$errno,$errstr,30))
    {
      return false;
    }
    else
    {
      return true;
    }
  }
  else
  {
    // mx records found
    foreach ($mxhosts as $host)
    {
      if (fsockopen($host,25,$errno,$errstr,30))
      {
        return true;
      }
    }
    return false;
  }} 

  function getPagerData($numHits, $limit, $page){  
           $numHits  = (int) $numHits;  
           $limit    = max((int) $limit, 1);  
           $page     = (int) $page;  
           $numPages = ceil($numHits / $limit);  

           $page = max($page, 1);  
           $page = min($page, $numPages);  

           $offset = ($page - 1) * $limit;  

           $ret = new stdClass;  

           $ret->offset   = $offset;  
           $ret->limit    = $limit;  
           $ret->numPages = $numPages;  
           $ret->page     = $page;  

           return $ret;  
       } 
	   


}

?>