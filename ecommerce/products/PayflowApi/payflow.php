<?php 
/*if (isset($_POST['Submit']) || isset($_POST['Order'])){*/
/*function payflow_payment_process(){*/
//echo "hii";
	try { 
		require_once dirname(__file__).'/Payflow-paypal-pro.php';
		$txn = new PayflowTransaction();
		//print_r($txn);

		
	    $txn->environment=$mode;

	  //print_r($txn);
		//these are provided by your payflow reseller
		$txn->PARTNER = trim($merchantRecords['PayFlowPartner']);
		$txn->USER = trim($merchantRecords['PayFlowUser']);
		$txn->PWD= trim($merchantRecords['PayFlowPassword']);
		$txn->VENDOR = trim($merchantRecords['PayFlowVendor']); //or your vendor name
		
 		$year = substr((string)$CreditCard0ExpirationYear,-2);  
		// transaction information
		$txn->ACCT 		=$CreditCard0CardNumber; //cc number
		$txn->AMT 		= $totalAmtPaid; //amount: 1 dollar
		$txn->EXPDATE	=$CreditCard0ExpirationMonth.$year;; //'0210'; //4 digit expiration date
	
	    $txn->FIRSTNAME = $Contact0FirstName;
		$txn->LASTNAME = $Contact0LastName;
		$txn->STREET = $Contact0StreetAddress1;
		$txn->CITY = $Contact0City;
		$txn->STATE = $Contact0State;
		$txn->ZIP = $Contact0PostalCode;
		$txn->COUNTRY = $Contact0Country;
	    
		//https://www.paypalobjects.com/en_US/vhelp/paypalmanager_help/transaction_type_codes.htm
		
		
		$txn->TRXTYPE =$TRXTYPE;
		
		//$txn->TRXTYPE = 'S'; //txn type: sale
		
		//$txn->TRXTYPE = 'R';
		
		
		$txn->TENDER = 'C'; //sets to a cc transaction
		
		if($txn->TRXTYPE == 'R'){
		
		if($action=='A'){
			 $txn->COMMENT1= $ProductName;
			 $txn->CLIENTIP= $_SERVER['REMOTE_ADDR'];
			 $txn->PAYPERIOD = $cycle;
			 $txn->TERM = '0';
			 //$txn->OPTIONALTRX = 'S';
			 //$txn->OPTIONALTRXAMT = $txn->AMT;
			 $txn->START = $startDate;
			 $txn->PROFILENAME = $txn->COMMENT1&nbsp;'subscription';
			 $txn->ACTION = $action;
			 }
			 elseif($action=='R'){
			$txn->ORIGPROFILEID=$ProfileID;
			$txn->ACTION=$action;
			$txn->AMT = $txn->AMT; //amount: 1 dolla
		    $txn->START = $startDate;
			
			 }
			 elseif($action=='C'){
			 $txn->ORIGPROFILEID=$ProfileID;
			 $txn->ACTION=$action;
			 }
			elseif($action=='I'){
			 $txn->ORIGPROFILEID=$ProfileID;
			 $txn->ACTION=$action;
			 }
			 
			 elseif($action=='M'){
			 $txn->ORIGPROFILEID=$ProfileID;
			 $txn->ACTION=$action;
			 $txn->PAYPERIOD = $cycle;
			 $txn->FIRSTNAME = $txn->FIRSTNAME ;
	     	 $txn->LASTNAME = $txn->LASTNAME;
			 $txn->START = $startDate;
			 $txn->AMT =$txn->AMT; //amount: 1 dollar
			 }
			
	     }elseif($txn->TRXTYPE=='S'){
		 
		  if($action=='I'){
			 $txn->ORIGID=$transaction_id;
			 $txn->ACTION='I';
			 $txn->TRXTYPE = 'I';
			
			 }
		 }
		
		
		
		
		//$this->environment = 'live';
 		//$txn->debug = true; //uncomment to see debugging information
		//$txn->avs_addr_required = 1; //set to 1 to enable AVS address checking, 2 to force "Y" response
		//$txn->avs_zip_required = 1; //set to 1 to enable AVS zip code checking, 2 to force "Y" response
		//$txn->cvv2_required = 1; //set to 1 to enable cvv2 checking, 2 to force "Y" response
		//$txn->fraud_protection = true; //uncomment to enable fraud protection

		$txn->process();
		$result=$txn->response_arr;
		
		//print_r($result);
		
		
		/*
		echo '<pre>';
		echo "success: " . $txn->txn_successful;
		echo "response was: " . print_r( $txn->response_arr, true );	
		echo '</pre>';
		die();*/

	}
	catch( TransactionDataException $tde ) {
		  $error= 'bad transaction data ' . $tde->getMessage();
	}
	catch( InvalidCredentialsException $e ) {
		 $error= 'Invalid credentials';
	}
	catch( InvalidResponseCodeException $irc ) {
		 $error= 'bad response code: ' . $irc->getMessage();
	}
	catch( AVSException $avse ) {
		 $error= 'AVS error: ' . $avse->getMessage();
	}
	catch( CVV2Exception $cvve ) {
		$error= 'CVV2 error: ' . $cvve->getMessage();
	}
	catch( FraudProtectionException $fpe ) {
		$error= 'Fraud Protection error: ' . $fpe->getMessage();
	}
	catch( Exception $e ) {
		 $error= $e->getMessage();
	}
//}	
?>