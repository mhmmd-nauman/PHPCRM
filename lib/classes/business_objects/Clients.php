<?php 
class Clients extends util {

	function GetAllClients($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		}
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". CLIENTS ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	
	function GetBalance($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		}
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM `Payments` WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	
	function FetchCCInformation($ClientID){
		if(!empty($ClientID)){
			global $link;
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql = "SELECT * FROM `ClientCCInformation` WHERE `ClientID` = '$ClientID' " or die("Error in the consult.." . mysqli_error($link));
			$result = mysqli_query($link,$sql);
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			return $arr; 
		}
	}
	
	function FetchCCInformationAll($ClientID = 1){
		if(!empty($ClientID)){
			global $link;
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql = "SELECT * FROM `ClientCCInformation` WHERE 1 " or die("Error in the consult.." . mysqli_error($link));
			$result = mysqli_query($link,$sql);
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			return $arr; 
		}
	}
	# This is the function which is called from the Clients page to show the
	# list of the client. This function filters the clients based on the agents
	# under whom the clients were made. So this function only returns those clients
	# who belongs to the Company which the current logged in member belongs to. 
	function GetAllClientsForClientsPage($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		}
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		# 18 -> Is for Website Fullfilment Group
		# 2 -> Super Admin Group
		# 3 -> Admin Group
		if(in_array(18, (array)$_SESSION['user_in_groups']) and !in_array(2, (array)$_SESSION['user_in_groups']) and !in_array(3, (array)$_SESSION['user_in_groups'])){
			$sql = "SELECT $strFields FROM ". CLIENTS ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		}elseif(!in_array(2, (array)$_SESSION['user_in_groups']) and !in_array(16, (array)$_SESSION['user_in_groups'])){
			// not admin, i.e. sales
			$sql = "SELECT $strFields FROM ". CLIENTS ." join ".USERS." on ".CLIENTS.".SubmitedBy = ".USERS.".ID join `Company` on ".USERS.".CompanyID = Company.ID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		}elseif(in_array(2, (array)$_SESSION['user_in_groups'])){
			$sql = "SELECT $strFields FROM ". CLIENTS ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		} elseif (in_array(16, (array)$_SESSION['user_in_groups'])){
			
			$sql = "SELECT * FROM ". CLIENTS ." WHERE " . $strWhere;
		}
		
		
		
		$result = mysqli_query($link,$sql);
		
			while($row = mysqli_fetch_array($result)){
				$arr[] = $row;
			}
		
		return $arr; 
	}
	
	function GetAllCategoryWithClients($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		# Separated the Queries on basis of the if conditipn, without disturbing the old one.
		if(in_array(18, (array)$_SESSION['user_in_groups']) and !in_array(2, (array)$_SESSION['user_in_groups']) and !in_array(3, (array)$_SESSION['user_in_groups'])){
			$sql = "SELECT $strFields FROM ". CLIENTS ."  LEFT JOIN ".ECTASKPRODUCTDATA." ON ".ECTASKPRODUCTDATA.".MemberID = ".CLIENTS.".ID LEFT JOIN ".PRODUCTCATEGORY." ON ".ECTASKPRODUCTDATA.".CategoryID = ".PRODUCTCATEGORY.".ID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
		}elseif(!in_array(2, (array)$_SESSION['user_in_groups'])){
			$sql = "SELECT $strFields FROM ". CLIENTS ."  LEFT JOIN ".ECTASKPRODUCTDATA." ON ".ECTASKPRODUCTDATA.".MemberID = ".CLIENTS.".ID LEFT JOIN ".PRODUCTCATEGORY." ON ".ECTASKPRODUCTDATA.".CategoryID = ".PRODUCTCATEGORY.".ID join ".USERS." on ".CLIENTS.".SubmitedBy = ".USERS.".ID join `Company` on ".USERS.".CompanyID = Company.ID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));			
			
		}elseif(in_array(2, (array)$_SESSION['user_in_groups'])){			
			$sql = "SELECT $strFields FROM ". CLIENTS ."  LEFT JOIN ".ECTASKPRODUCTDATA." ON ".ECTASKPRODUCTDATA.".MemberID = ".CLIENTS.".ID LEFT JOIN ".PRODUCTCATEGORY." ON ".ECTASKPRODUCTDATA.".CategoryID = ".PRODUCTCATEGORY.".ID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		}
		
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	
	# Fetch Name the agent using ID.
	function FetchAgentName($AgentID){
		global $link;
		
		$sql = "SELECT ID, FirstName, LastName, Email FROM ". USERS ." WHERE ID = '$AgentID' limit 0,1 " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$AgentName = $row['FirstName']." ".$row['LastName'];
		}
		return $AgentName;
	}
	
	function FetchAgentCompanyName($AgentID){
		global $link;
		$sql = "select `CompanyName` from `Company` C join ".Users." U on C.ID = U.CompanyID where U.ID = '$AgentID' limit 0,1 ";
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$AgentCompanyName = $row['CompanyName'];
		}
		return $AgentCompanyName;
	}
	
	function FetchAgentCompanyDetails($AgentID){
		global $link;
		if(empty($AgentID)){
			return "Please provide a valid Agent ID";
		}
		$sql = "select * from `Company` C join `Users` U on C.ID = U.CompanyID where U.ID = '$AgentID' limit 0,1 ";
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$AgentCompanyDetails[] = $row;
		}
		return $AgentCompanyDetails;
	}
	
	function FetchAgentCompanyDetailsforInvoice($AgentID){
		global $link;
		if(empty($AgentID)){
			return "Please provide a valid Agent ID";
		}
		$sql = "select C.* from `Company` C join `Users` U on C.ID = U.CompanyID where U.ID = '$AgentID' limit 0,1 ";
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$AgentCompanyDetails[] = $row;
		}
		return $AgentCompanyDetails;
	}
	
	function FetchAllAgentCompanyDetails($AgentID){
		global $link;
		$sql = "select C.* from `Company` C join `Users` U on C.ID = U.CompanyID where U.ID = '$AgentID' limit 0,1 ";
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	
	function GetAllClientsOrder($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". CLIENTS ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
	
		
	function GetAllRevision($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". REVISION ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}	
		return $arr; 
	}
    
	function GetAllPaymentMethod($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		} 
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". PAYMENTACCEPTED ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
		
	function GetAllClientsWithRevision($strWhere,$fieldaArray = ""){
		global $link;
		reset($fieldaArray);
		foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
		}
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT $strFields FROM ". REVISION ."  LEFT JOIN ".CLIENTS." ON ".CLIENTS.".ID = Revision.MemberID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
	}
		
    function GetAllClientsWithWebsites($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			foreach ($fieldaArray as $field){
				$strFields .=  "".$field . " ,";
			} 
			//remove the last comma
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			 $sql="SELECT $strFields FROM ". CLIENTS ."  LEFT JOIN ".WEBSITES." ON ".CLIENTS.".ID = ".WEBSITES.".MemberID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;
			
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			
			return $arr; 
		}
    function InsertClients($array){
			if($array){
				$inserted_id = util::insertRecord(CLIENTS,$array);
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
				$inserted_id = util::insertRecord(PAYMENTACCEPTED,$array);
				return $inserted_id;
			} else {
				return 0;
		   }
		}
    function DeleteClientsGroup($ID){
			if($ID){
				$deleted_id = util::deleteRecord(GROUPUSERS,"MemberID = $ID");
				return $deleted_id;
			} else {
				return 0;
			}
		}
    function DeletePaymentMethod($ID){
			if($ID){
				$deleted_id = util::deleteRecord(PAYMENTACCEPTED,"MemberID = $ID");
				return $deleted_id;
			} else {
				return 0;
			}
		}
    function DeleteClient($ID){
			if($ID){
				$deleted_id = util::deleteRecord(CLIENTS,"ID = $ID");
				return $deleted_id;
			} else {
				return 0;
			}
		}
    function UpdatePaymentMethod($where,$array){
			if($array){
				$updated_id = util::updateRecord(PAYMENTACCEPTED,$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}
			
    function UpdateClients($where,$array){
		if($array){
			$updated_id = util::updateRecord(CLIENTS,$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
	
	function UpdateOrderItem($where,$array){
		if($array){
			$updated_id = util::updateRecord('OrderItem',$where,$array);
			return $updated_id;
		} else {
			return 0;
		}
	}
	
	function UpdateChargebacks($where,$array){
		if($array){
			$updated_id = util::updateRecord('Chargebacks_Details',$where,$array);
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
    function UpdateReview($where,$array){
			if($array){
				$updated_id = util::updateRecord(CLIENTS,$where,$array);
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
        $sql="SELECT $strFields FROM ". GROUPUSERS ."  LEFT JOIN `".GROUPS."` ON `".GROUPS."`.ID = ".GROUPUSERS.".GroupID 
            LEFT JOIN ".CLIENTS." ON ".CLIENTS.".ID =  ".GROUPUSERS.".UserID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
        //$encoded_query = base64_encode($sql);
        $result=mysqli_query($link,$sql) ;
			
        while($row=mysqli_fetch_array($result)){
                $arr[] = $row;
        }
        return $arr; 
    }
    /* Client Task Data Function   */
    function GetAllClientTaskData($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
			} 
			
			//echo "remove the last comma";
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql="SELECT $strFields FROM ". ECTASKPRODUCTDATA ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			$result=mysqli_query($link,$sql);
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			
			return $arr; 
		}
    function GetAllClientCitationData($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
			} 
			
			//echo "remove the last comma";
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql="SELECT $strFields FROM ". ECCITATIONDATA ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql);
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			
			return $arr; 
		}
    function GetAllClientGYBData($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
			} 
			
			//echo "remove the last comma";
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql="SELECT $strFields FROM ". ECGYBDATA ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) or die("Query '$sql' failed with error message: \"" . mysql_error () . '"');
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			
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
	# This function inserts the details of the pdf file which is specially created vy Amber from the link
	# http://xurlios.com/chargebacks/Uploadcsv.php	
	function InsertChargebacksdetailspdf($array){
		if($array){
			$inserted_id = util::insertRecord("Chargebacks_Details",$array);
			return $inserted_id;
		} else {
			return 0;
	   }
	}
	
	function FetchChargebackdetails(){
		global $link;
		$strFields = substr($strFields, 0, strlen($strFields) - 1);	
		$sql = "SELECT * FROM `Chargebacks_Details` WHERE 1 order by Created DESC " or die("Error in the consult.." . mysqli_error($link));
		$result = mysqli_query($link,$sql);
		while($row = mysqli_fetch_array($result)){
			$arr[] = $row;
		}
		return $arr; 
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
    function InsertClientGYBData($array){
			if($array){
				$inserted_id = util::insertRecord(ECGYBDATA,$array);
				return $inserted_id;
			} else {
				return 0;
		   }
		}
    function UpdateClientGYBData($where,$array){
			if($array){
				$updated_id = util::updateRecord(ECGYBDATA,$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}
		/* Client Credit Card Information Function  Build BY Imran*/
		
		/*Credit Card Functions Start*/
		 function InsertClientCCData($array){
			if($array){
				$inserted_id = util::insertRecord(CLIENTCCINFORMATION,$array);
				return $inserted_id;
			} else {
				return 0;
		   }
		}
		 function UpdateClientCCData($where,$array){
			if($array){
				$updated_id = util::updateRecord("ClientCCInformation",$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}
		
	 function GetAllInformationCCData($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
			} 
			
			//echo "remove the last comma";
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql="SELECT $strFields FROM ". CLIENTCCINFORMATION ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			
			//$encoded_query = base64_encode($sql);
			$result=mysqli_query($link,$sql) ;
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			
			//log_error($encoded_query);
			
			return $arr; 
		}	
		/*Credit Card Functions end*/	
	// End of Function	
        function encrypt($text) { 
            //return $text;
            return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
        } 
        function decrypt($text) { 
                //return $text;
             return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
        }
		
		   function UpdateUserLoginDetail($where,$array){
			if($array){
				$updated_id = util::updateRecord(LOGINDETAIL,$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}
		 function InsertUserLoginDetail($array){
			if($array){
				$inserted_id = util::insertRecord(LOGINDETAIL,$array);
				return $inserted_id;
			} else {
				return 0;
		   }
		}
		
		function GetUserLoginDetail($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
			} 
			
			//echo "remove the last comma";
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql="SELECT $strFields FROM ". LOGINDETAIL ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql);
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
			
			return $arr; 
		}
		
		 function InsertClientNotes($array){
			if($array){
				$inserted_id = util::insertRecord(CLIENTSNOTES,$array);
				return $inserted_id;
			} else {
				return 0;
		   }
		}
                 function UpdateClientNotes($where,$array){
			if($array){
				$updated_id = util::updateRecord(CLIENTSNOTES,$where,$array);
				return $updated_id;
			} else {
				return 0;
			}
		}
                 function DeleteClientNotes($ID){
			if($ID){
				$deleted_id = util::deleteRecord(CLIENTSNOTES,"ID = $ID");
				return $deleted_id;
			} else {
				return 0;
			}
		}
                function GetAllClientsWithNotes($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			foreach ($fieldaArray as $field){
				$strFields .=  "".$field . " ,";
			} 
			//remove the last comma
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
                        $sql="SELECT $strFields FROM ". CLIENTSNOTES ."  LEFT JOIN ".CLIENTS." ON ".CLIENTS.".ID = ClientsNotes.MemberID WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			//$encoded_query = base64_encode($sql);
			
			$result=mysqli_query($link,$sql);
                        if($result){
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
                        }
			return $arr; 
		}
                	function GetAllClientsNotes($strWhere,$fieldaArray=""){
			global $link;
			reset($fieldaArray);
			
			
			foreach ($fieldaArray as $field){
			$strFields .=  "".$field . " ,";
			} 
			
			//echo "remove the last comma";
			$strFields = substr($strFields, 0, strlen($strFields) - 1);	
			$sql="SELECT $strFields FROM ". CLIENTSNOTES ."  WHERE $strWhere " or die("Error in the consult.." . mysqli_error($link));
			$result=mysqli_query($link,$sql);
                if($result){
			while($row=mysqli_fetch_array($result)){
				$arr[] = $row;
			}
                            }
			return $arr; 
		}
                
                
      function InvoiceMail($OrderID){
            global $link;        
            $htmlbody = "From xurlios.com ";
            $to = "client1@xurlios.com"; //Recipient Email Address 
            $Bcc = "billing@xurlios.com";
            $Cc = "agent1@xurlios.com";
            $subject = "xurlios.com"; //Email Subject
            $headers = "From: admin@xurlios.com\r\nReply-To: admin@xurlios.com";
            $random_hash = md5(date('r', time()));
            $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
            $attachment = chunk_split(base64_encode(file_get_contents('clients/pdf.php'))); // Set your file path here
            //define the body of the message.
            $message = "--PHP-mixed-$random_hash\r\n"."Content-Type: multipart/alternative; boundary=\"PHP-alt-$random_hash\"\r\n\r\n";
            $message .= "--PHP-alt-$random_hash\r\n"."Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";
            //Insert the html message.
            $message .= $htmlbody;
            $message .="\r\n\r\n--PHP-alt-$random_hash--\r\n\r\n";
            //include attachment
            $message .= "--PHP-mixed-$random_hash\r\n"."Content-Type: application/zip; name=\"logo.png\"\r\n"."Content-Transfer-Encoding: base64\r\n"."Content-Disposition: attachment\r\n\r\n";
            $message .= $attachment;
            $message .= "/r/n--PHP-mixed-$random_hash--";
            //send the email
            $mail = mail( $to,$Bcc,$Cc, $subject , $message, $headers );
            echo $mail ? "Mail sent" : "Mail failed";
      }
	  
	  
	function UpdateClientCCDetails($ClientID,$Field,$NewValue,$FieldInOrderItemTable){
		global $link; 
		$update_response = "";
		if(!empty($ClientID)){
			$check_in_cc = self::FetchCCInformation($ClientID);
			if(!empty($check_in_cc)){
				if(!empty($Field) and !empty($FieldInOrderItemTable)){
					$update_response = mysqli_query($link,"Update `ClientCCInformation` set `$Field` = '$NewValue' where `ClientID` = '$ClientID' ");
					$update_response_orderitem = mysqli_query($link,"Update `OrderItem` set `$FieldInOrderItemTable` = '$NewValue' where `MemberID` = '$ClientID' ");
				}else{
					$update_response = 1;
					$update_response_orderitem = mysqli_query($link,"Update `OrderItem` set `$FieldInOrderItemTable` = '$NewValue' where `MemberID` = '$ClientID' ");
				}
				if($update_response == 1 and $update_response_orderitem == 1){
					return 1;
				}else{
					return 0;
				}
			}else{
				# First Create a blank record with only ClientID in place, then update these details using the new record with client ID
				$array = array("ClientID" => $ClientID, "AccountName" => "", "AddedFrom" => "ClientEdit Popup", "CCStatus" => "0");
				$newrecord_in_cc = self::InsertClientCCDetails($array);
				if($newrecord_in_cc){
					$update_response = mysqli_query($link,"Update `ClientCCInformation` set `$Field` = '$NewValue' where `ClientID` = '$ClientID' ");
					$update_response_orderitem = mysqli_query($link,"Update `OrderItem` set `$FieldInOrderItemTable` = '$NewValue' where `MemberID` = '$ClientID' ");
					if($update_response == 1 and $update_response_orderitem == 1){
						return 1;
					}else{
						return 0;
					}
				}
			}
		}
		return 0;
	}
	
	function InsertClientCCDetails($array){
		if($array){
			$inserted_id = util::insertRecord("ClientCCInformation",$array);
			return $inserted_id;
		} else {
			return 0;
	   }
	}
}
?>