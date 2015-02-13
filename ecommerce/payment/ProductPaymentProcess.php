<?php 
 include "../Auth_Recurring-curl/data.php";
include "../Auth_Recurring-curl/authnetfunction.php";
if ($merchantRecords['AccountType']==16){
include "../../../wizard/modules/infusionData.php";
$infObject = new iNFUSION();
}else
include "../products/inf_api_src/isdk.php";

			//  $Strwhere = "Email='".$_REQUEST["Contact0Email"]."' ";
			 //  $strWhere='Email='.$_REQUEST['Contact0Email'].'';
			 // $MemberRecords=$utilObj->getSingleRow('Member', $strWhere);
		     //$Strwhere='ID = 94';
				 
			  $strWhere='Email= "'.$_REQUEST['Contact0Email'].'" ';
			  $MemberRecords=$utilObj->getSingleRow('Member',$strWhere);
			  
			  $Strwhere='ID ='.$_POST['ProductId'].'';
			  $ProductRecords=$utilObj->getSingleRow('Product',$Strwhere);
			 
			  $Strwhere='ProductID ='.$_POST['ProductId'].'';
			  $ProdSubRecords=$utilObj->getSingleRow('ProductSubscription',$Strwhere);
			  
			  $ProductName= $ProductRecords['ProductName'];
			  
if($merchantRecords['AccountType']==1 || $merchantRecords['AccountType']==3){
	   require_once('../products/AuthorizeNet-sdk/AuthorizeNet.php'); 
	   if($merchantRecords['Mode']=='-1')
		 $mode=false;
	     else
	     $mode=true;
		 
	       $LoginID=trim($merchantRecords['LoginID']);
		   $TransKey=trim($merchantRecords['TransactionKey']);
		   $transaction = new AuthorizeNetAIM($LoginID,$TransKey);
		   
		 //print_r($transaction);
		 //exit;
		  
		
		$transaction->setSandbox($mode);
		$year = substr((string)$CreditCard0ExpirationYear,-2);  
		
		$transaction->amount = $totalAmtPaid;
		$transaction->card_num = $CreditCard0CardNumber;
		$transaction->exp_date = $CreditCard0ExpirationMonth.'/'. $year;
		
		    $transaction->description        = $description =$ProductName;
			$transaction->first_name         = $first_name =$Contact0FirstName;
			$transaction->last_name          = $last_name =$Contact0LastName ;
			$transaction->company            = $company = "";
			$transaction->address            = $address = $Contact0StreetAddress1;
			$transaction->city               = $city = $Contact0City;
			$transaction->state              = $state = $Contact0State;
			$transaction->zip                = $zip = $Contact0PostalCode;
			$transaction->country            = $country = $Contact0Country;
			$transaction->phone              = $phone = $Contact0Phone2;
			$transaction->fax                = $fax = "";
			$transaction->email              = $email = $Contact0Email;
			$transaction->cust_id            = $customer_id = " ";
			$transaction->customer_ip        = $_SERVER['REMOTE_ADDR'];
			$transaction->invoice_num        = $invoice_number = $CreditCard0VerificationCode;
			$transaction->ship_to_first_name = $ship_to_first_name = $Contact0FirstName;
			$transaction->ship_to_last_name  = $ship_to_last_name = $Contact0LastName;
			$transaction->ship_to_company    = $ship_to_company = "";
			$transaction->ship_to_address    = $ship_to_address = $Contact0StreetAddress1;;
			$transaction->ship_to_city       = $ship_to_city = $Contact0City;;
			$transaction->ship_to_state      = $ship_to_state = $Contact0State;
			$transaction->ship_to_zip        = $ship_to_zip_code = $Contact0PostalCode;
			$transaction->ship_to_country    = $ship_to_country = $Contact0Country;
			$transaction->tax                = $tax = "0.00";
			$transaction->freight            = $freight = "";
			$transaction->duty               = $duty = "";
			$transaction->tax_exempt         = $tax_exempt = "";
			$transaction->po_num             = $po_num = "";
			$response = $transaction->authorizeAndCapture();
			$transaction_id = $response->transaction_id;
			
   			$arrValue_credit=array('response_code'=>$response->response_code, 
			  'response_reason_code'	=>$response->response_reason_code,
			  'response_reason_text'	=>$response->response_reason_text, 
			  'avs_code'				=>$response->avs_code, 
			  'auth_code'				=>$response->auth_code, 
			  'trans_id'				=>$response->transaction_id, 
			  'method'					=>$response->method, 
			  'card_type'				=>$response->card_type, 
			  'account_number'			=>$response->account_number, 
			  'first_name'				=>$response->first_name, 
			  'last_name'				=>$response->last_name, 
			  'company'					=>$response->company, 
			  'address'					=>$response->address, 
			  'city'					=>$response->city, 
			  'state'					=>$response->state, 
			  'zip'						=>$response->zip_code, 
			  'country'					=>$response->country, 
			  'phone'					=>$response->phone, 
			  'fax'						=>$response->fax, 
			  'email'					=>$response->email_address, 
			  'invoice_num'				=>$response->invoice_number, 
			  'description'				=>$response->description, 
			  'type'					=>$response->transaction_type, 
			  'cust_id'					=>$response->cust_id, 
			  'amount'					=>$response->amount, 
			  'tax'						=>$response->tax, 
			  'duty'					=>$response->duty, 
			  'cvv2_resp_code'			=>$response->cvv2_resp_code, 
			  'cavv_response'			=>$response->cavv_response, 
			  'subscription_id'			=>'', 
			  'subscription_paynum'		=>'', 
			  'MemberID'				=>$_SESSION['loggedInAs'],
			  'Created'					=>date('Y-m-d H:i:s'),
			  'LastEdited'				=>date('Y-m-d H:i:s'));

			$insertedId_card=$utilObj->insertRecord('PaymentResponse', $arrValue_credit);
			
			include "../Auth_Recurring-curl/order_get_status.php"; //status of order 1 = Approved 2=Declined 3=Error 4=Held for Review
			if(!isset($co_op_shares_ecommerce) && $response->approved && !isset($Type_paymnet))
			{
				$app = new iSDK;
				if($app->cfgCon("connectionName")) 
				{
						
					$contact=array('Email' => $Contact0Email,
					   'FirstName' => $Contact0FirstName,
					   'LastName' => $Contact0LastName,
					   'StreetAddress1'=>$Contact0StreetAddress1,
					   'StreetAddress2'=>$Contact0StreetAddress1,
					   'City'=>$Contact0City);
					
						if (!empty($contact['Email'])) {
						//check for existing contact;
						$returnFields = array('Id');
						$dups = $app->findByEmail($contact['Email'], $returnFields);
						
							if (!empty($dups)) {
						//update contact
								$oldCon=$app->updateCon($dups[0]['Id'],$contact);
								$currentDate = date("d-m-Y");
						$oDate = $app->infuDate($currentDate);
						$newOrder = $app->blankOrder($oldCon,"New Order for Contact ", $oDate,0,0);
						$newOrder=(int)$newOrder;
						$amount_for_inf=(float)$totalAmtPaid;
						$result = $app->addOrderItem($newOrder,220,4,$amount_for_inf,1,"JustinsStuff","new stuff!");
						$operation =$app->manualPmt($newOrder,$amount_for_inf,$oDate,"Check","test",false);
						
						
							} else {
						//Add new contact
								$newCon = $app->addCon($contact);
								$currentDate = date("d-m-Y");
						$oDate = $app->infuDate($currentDate);
						$newOrder = $app->blankOrder($newCon,"New Order for Contact $newCon", $oDate,0,0);
						$newOrder=(int)$newOrder;
						$amount_for_inf=(float)$totalAmtPaid;
						$result = $app->addOrderItem($newOrder,220,4,$amount_for_inf,1,"JustinsStuff","new stuff!");
						$operation =$app->manualPmt($newOrder,$amount_for_inf,$oDate,"Check","test",false);
						
						
							}
						}
						
						
						
				}
			}
			 
			 if($status=='1')
			 {
			  $status_order="Paid";
			 }
			 elseif($status=='4' )
			 {
			  $status_order="Pending";
			 }	
			 elseif($status=='2')
			 {
			  $status_order="Failed";
			 }
			  elseif($status=='3')
			 {
			  $status_order="Error";
			 }	
			
			 $type='One Time Order';
			 
	 if($normalorder==0){	
	 
	   //echo "yes";
		//exit;	
		 if ($response->approved) {
			/*--------Create subscription code---------*/
			$subscription = new AuthorizeNet_Subscription;
			$subscription->name =preg_replace("/[^a-z0-9+]/i", ' ', $ProductName);
			$startDate=date('Y-m-d', strtotime("+30 days"));
			$cycle='days';
			$Interval=1;
			if($GetSubscriptionRecords['BillEvery']==1)
			$cycle='months';
			elseif($GetSubscriptionRecords['BillEvery']==2)
			$Interval=365;
			else
			$Interval=7;
			
			
			$subscription->intervalLength =$Interval;
			$subscription->intervalUnit =$cycle;
			
			$subscription->startDate = $startDate;
			$totalOccurrences=$GetSubscriptionRecords['Duration'];
			if($GetSubscriptionRecords['Duration']==0)
			$totalOccurrences=9999;
			
			$subscription->totalOccurrences =$totalOccurrences;
			$subscription->trialOccurrences ="";
			if($getpromocode_discount)
			{
				if($getpromocode_discount['SubscriptionPrice']!='0')
				{
					$subscription->amount =$getpromocode_discount['SubscriptionPrice'];
					$sub_cond="&samt_val=".$getpromocode_discount['SubscriptionPrice'];
				}
				else
				{
					$subscription->amount =$GetSubscriptionRecords['SubscriptionPrice'];
					$sub_cond="&samt_val=".$GetSubscriptionRecords['SubscriptionPrice'];
				}
			}
			else
			{
				$subscription->amount =$GetSubscriptionRecords['SubscriptionPrice'];
				$sub_cond="&samt_val=".$GetSubscriptionRecords['SubscriptionPrice'];
			}
			
			$subscription->trialAmount = "";
			$subscription->creditCardCardNumber = $CreditCard0CardNumber;
			$subscription->creditCardExpirationDate = $CreditCard0ExpirationYear.'-'. $CreditCard0ExpirationMonth;
			$subscription->creditCardCardCode = $CreditCard0VerificationCode;
			$subscription->bankAccountAccountType = "";
			$subscription->bankAccountRoutingNumber = "";
			$subscription->bankAccountAccountNumber = "";
			$subscription->bankAccountNameOnAccount = "";
			$subscription->bankAccountEcheckType = "";
			$subscription->bankAccountBankName = "";
			$subscription->orderInvoiceNumber = "";
			$subscription->orderDescription = "";
			$subscription->customerId = $merchantRecords['ID'];
			$subscription->customerEmail = $Contact0Email;
			$subscription->customerPhoneNumber = "";
			$subscription->customerFaxNumber = "";
			$subscription->billToFirstName = $Contact0FirstName;
			$subscription->billToLastName = $Contact0LastName;
			$subscription->billToCompany = "";
			$subscription->billToAddress = $Contact0StreetAddress1;
			$subscription->billToCity = $Contact0City;
			$subscription->billToState = $Contact0State;
			$subscription->billToZip = $Contact0PostalCode;
			$subscription->billToCountry = $Contact0Country;
			$subscription->shipToFirstName = "";
			$subscription->shipToLastName = "";
			$subscription->shipToCompany = "";
			$subscription->shipToAddress = "";
			$subscription->shipToCity = "";
			$subscription->shipToState = "";
			$subscription->shipToZip = "";
			$subscription->shipToCountry = "";
			
			
			//echo "<pre/>";
			//print_r($subscription);
			//exit;
			// Create the subscription.
			$request = new AuthorizeNetARB($LoginID,$TransKey);
			
			$request->setSandbox($mode);
			
			$response = $request->createSubscription($subscription);
			
			//$subscription_id=1469356;
			//$response = $request->getSubscriptionStatus($subscription_id);
			//$response = $request->updateSubscription($subscription_id, $subscription);
			//$response = $request->cancelSubscription($subscription_id);
			
		  //echo "<pre/>";
		  //print_r($response);
		  
		   $check_member_isexist = mysql_query("SELECT * FROM `Member` WHERE Email = '".$_REQUEST['Contact0Email']."'");
			if($response->isOk()){
				if(mysql_num_rows($check_member_isexist) > 0)
				{
					if($Type_payment=='order_and_subscription')
					{
					
					  
				
						//$fetch_member = mysql_query("SELECT * FROM `Member` WHERE ID = '".$_SESSION['loggedInAs']."'");// this is ranjan code
						
						$fetch_member = mysql_query("SELECT * FROM `Member` WHERE Email = '".$_REQUEST['Contact0Email']."'"); //replace login session with  member email id by irfan
						
	     				$MemberRecords1 = mysql_fetch_assoc($fetch_member);
						$ReferrerCode1=$MemberRecords1['ReferrerCode'];
						$Coach_member1 = mysql_query("SELECT ID FROM `Member` WHERE AppCode = '".$ReferrerCode1."'");
						$CoachRecords1 = mysql_fetch_assoc($Coach_member1); 
						$CoachID1=$CoachRecords1['ID'];
						
						$arrValue2=array('MemberID'=>$MemberRecords1['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorId'=>$MemberRecords1['SponsorAppId'],
			  'CoachId'=>$CoachID1,'BillingAmount'=>$totalAmtPaid,'Quantity'=>'1',
			  'PromoCodeId'=>'0','ProductSubscriptionId'=>$ProdSubRecords['ID'],'TransactionId'=>$transaction_id ,'Status'=>$status_order, 'Type'=>'One Time Order', 'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  			$subscription_id = $response->getSubscriptionId();
			  			$insertedId1=$utilObj->insertRecord('OrderItem', $arrValue2);
						$Strwhere='TransactionId="'.$transaction_id.'" ' ;
			 			$OrderRecords=$utilObj->getSingleRow('OrderItem',$Strwhere);
						$startDate=date('Y-m-d');
						if($ProdSubRecords['BillEvery']==1) {
						$Interval=30;
						$cycle='Month'; }
						elseif($ProdSubRecords['BillEvery']==2) {
						$Interval=365;
						$cycle='Year'; }
						else{
						$Interval=7;
						$cycle='Week'; }
						$NextBillDate=date('Y-m-d', strtotime("+".$Interval." days"));
						
						include "../Auth_Recurring-curl/subscription_get_status.php";
			 
			 			$status_subscription=$status;
						
			  			$arrValue2=array('MemberID'=>$MemberRecords1['ID'],'ProductID'=>$ProductRecords['ID'],'BillingAmt'=>$GetSubscriptionRecords['SubscriptionPrice'],'OrderID'=>$OrderRecords['ID'],'Qty'=>'1','PromoCodeId'=>'0','StartDate'=>$startDate,'EndDate'=>$startDate,'ProductSubscriptionId'=>$ProdSubRecords['ID'],'SubscriptionTransactionId'=>$subscription_id,'NextBillDate'=>$NextBillDate,'BillingCycle'=>$cycle,'Status'=>$status_subscription,'MerchantAccountId'=>$GetMerchantIdfromProduct['ManageMerchantAccID'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ProfileId'=>$ProfileID);
			
			  			$insertedId=$utilObj->insertRecord('RecurringOrder', $arrValue2); 
			  
			  			$url="http://themillionaireos.com/ecommerce-member-thankyou-simple";
                        require_once "order_email.php";
						
					}
				}else
				{
			
			 $subscription_id = $response->getSubscriptionId();
			 
			   
		     include "../Auth_Recurring-curl/subscription_get_status.php";
			 
			 $status_subscription=$status;
           $url="http://themillionaireos.com/ecommerce-member-thankyou?prodId=".$_POST['ProductId']."&SubscriptionId=".$_POST['SubscriptionPlanId']."&Contact0Email=".$_POST['Contact0Email']."&Fname=".$_POST['Contact0FirstName']."&Lname=".$_POST['Contact0LastName']."&Contact0_SponsorAffiiliatecode=".$_POST['Contact0_SponsorAffiiliatecode']."&Contact0_ttrackingcode=".$_POST['Contact0_ttrackingcode']."&Phone=".$_POST['Contact0Phone1']."&Reffcode=".$_POST['Contact0_ReferrerCode']."&Tid=".$transaction_id."&SubTid=".$subscription_id."&amt_val=".$totalAmtPaid."".$sub_cond."";
			 
				}
			  //include 'CommissionCalc.php';
			   header("Location: $url");
			 //echo  $ResponseMessage="Success! The test credit card has been charged! subscription ID: " . $subscription_id;
			 } else {
			 
			      
				
				   header("Location: ".$_POST['failreturnurl']."?error=". $response->getMessageText()."&#errormsg");
					
				}
		   }else{
		     
		        header("Location: ".$_POST['failreturnurl']."?error=".$ResponseMessage=$response->response_reason_text."&#errormsg");
		       //echo $ResponseMessage=$response->response_reason_text;
			}
		 }//end subscription process check
		 else{
			 
			  /*print_r($response);
		 
				die();  	*/
			/*Code start for co op share entry in db in memberpurchaseshare table and orderitem table*/	
			if(isset($co_op_shares_ecommerce))
			{
			if($co_op_shares_ecommerce=='1')
			{	
			 
		     if ($response->approved) 
			 {
			  	$transaction_id = $response->transaction_id;
			  	$fetch_member = mysql_query("SELECT * FROM `Member` WHERE ID = '".$member_id_share."'");
				$MemberRecords_order = mysql_fetch_assoc($fetch_member); 
				$ReferrerCode=$MemberRecords_order['ReferrerCode'];
				$Coach_member = mysql_query("SELECT ID FROM `Member` WHERE AppCode = '".$ReferrerCode."'");
				$CoachRecords = mysql_fetch_assoc($Coach_member); 
				$CoachID=$CoachRecords['ID'];
		
			 	$type='One Time Order';
			 	$records_to_insert= array('ClassName'=>'MemberPurchasedShare','Created'=>date("Y-m-d"),'Description'=>$share_desc,'MemberID'=>$member_id_share,'ShareID'=>$share_id,'ContactID'=>$AppId,'AdjustmentAmount'=>$totalAmtPaid,'Type'=>'1');
			 	$records_to_insert_for_bonus= array('ClassName'=>'MemberPurchasedShare','Created'=>date("Y-m-d"),'Description'=>$share_desc,'MemberID'=>$member_id_share,'ShareID'=>$share_id,'ContactID'=>$AppId,'AdjustmentAmount'=>$bonus_calculated,'Type'=>'3');
			 	$co_op_shares_insert=$utilObj->insertRecord('MemberPurchasedShare',$records_to_insert);
			 
			 	$arrValue2=array('MemberID'=>$member_id_share,'ProductID'=>$ProductId,'SponsorId'=>$MemberRecords_order['SponsorAppId'],'CoachId'=>$CoachID,'BillingAmount'=>$totalAmtPaid,'Quantity'=>'1','PromoCodeId'=>'0','ProductSubscriptionId'=>'','TransactionId'=>$transaction_id ,'Status'=>$status_order, 'Type'=>'coop share order', 'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  
			  	$insertedId=$utilObj->insertRecord('OrderItem', $arrValue2);
			 
			 if($bonus_calculated!=0)
			 {
			 	$co_op_shares_insert_bonus=$utilObj->insertRecord('MemberPurchasedShare',$records_to_insert_for_bonus);
			 }
			 echo '<br/>
<span style="font-size:20px; color:#090; font-weight:bold; text-align:center; margin-left:316px;"> Thank You. </span><br/>
<span style="font-size:20px; color:#090; font-weight:bold; text-align:center; margin-left:188px;"> Please close the popup to explore more.</span><br/><br/><span onclick="parent.$.fancybox.close();" style="font-size:20px; font-weight:bold; text-align:center; margin-left:313px; cursor:pointer;">Close</span>';
			 
		//$url="http://themillionaireos.com/ecommerce-member-thankyou?prodId=".$_POST['ProductId']."&SubscriptionId=".$_POST['SubscriptionPlanId']."&Contact0Email=".$_POST['Contact0Email']."&Fname=".$_POST['Contact0FirstName']."&Lname=".$_POST['Contact0LastName']."&Contact0_SponsorAffiiliatecode=".$_POST['Contact0_SponsorAffiiliatecode']."&Contact0_ttrackingcode=".$_POST['Contact0_ttrackingcode']."&Phone=".$_POST['Contact0Phone1']."&Reffcode=".$_POST['Contact0_ReferrerCode']."&Tid=".$transaction_id."";
			
			//echo $url="http://themillionaireos.com/ecommerce-member-thankyou";
			 
			 //include 'CommissionCalc.php';
			 
			 //header("Location: $url");
		      }
			  else
			  {
				  header("Location: http://themillionaireos.com/co_op_shares_ecommerce/order_form.php?error=".$ResponseMessage=$response->response_reason_text."&product_id=".$ProductId);
				 /* echo '<br/>
<span style="font-size:20px; color:#C00; font-weight:bold; text-align:center"> Sorry the credit card info is wrong. </span><br/>
<span style="font-size:20px; color:#C00; font-weight:bold; text-align:center"> Please try again by closing the popup and reopen it again.</span>';*/
			    
			    //header("Location: ".$_POST['failreturnurl']."?error=".$ResponseMessage=$response->response_reason_text."&#errormsg");
			    //echo $ResponseMessage=$response->response_reason_text;
		 	  }
			}
			/*Code ends co op share*/
			}else
			{
		     if ($response->approved) 
			 {
			  $transaction_id = $response->transaction_id;
			  
			 $type='One Time Order';
			 $sub_cond="&samt_val=199";
		/*$url="http://themillionaireos.com/ecommerce-member-thankyou?prodId=".$_POST['ProductId']."&SubscriptionId=".$_POST['SubscriptionPlanId']."&Contact0Email=".$_POST['Contact0Email']."&Fname=".$_POST['Contact0FirstName']."&Lname=".$_POST['Contact0LastName']."&Contact0_SponsorAffiiliatecode=".$_POST['Contact0_SponsorAffiiliatecode']."&Contact0_ttrackingcode=".$_POST['Contact0_ttrackingcode']."&Phone=".$_POST['Contact0Phone1']."&Reffcode=".$_POST['Contact0_ReferrerCode']."&Tid=".$transaction_id."&amt_val=".$totalAmtPaid."".$sub_cond."";*/
		
		$url="http://themillionaireos.com/ecommerce-member-thankyou?prodId=".$_POST['ProductId']."&SubscriptionId=".$_POST['SubscriptionPlanId']."&Contact0Email=".$_POST['Contact0Email']."&Fname=".$_POST['Contact0FirstName']."&Lname=".$_POST['Contact0LastName']."&Contact0_SponsorAffiiliatecode=".$_POST['Contact0_SponsorAffiiliatecode']."&Contact0_ttrackingcode=".$_POST['Contact0_ttrackingcode']."&Phone=".$_POST['Contact0Phone1']."&Reffcode=".$_POST['Contact0_ReferrerCode']."&Tid=".$transaction_id."&SubTid=".$subscription_id."&amt_val=".$totalAmtPaid."".$sub_cond."";
			
			//echo $url="http://themillionaireos.com/ecommerce-member-thankyou";
			 
			 //include 'CommissionCalc.php';
			 
			 header("Location: $url");
		      }
			  else
			  {
			    
			    header("Location: ".$_POST['failreturnurl']."?error=".$ResponseMessage=$response->response_reason_text."&#errormsg");
			    //echo $ResponseMessage=$response->response_reason_text;
		 	  }
		 }
		 }
}//End of authorize.net 

//Code by Irfan
elseif($merchantRecords['AccountType']==16){
 if($merchantRecords['Mode']=='-1')
		$mode='live';
		else
		$mode='test';
		
    $TransAMT=$totalAmtPaid;
	
	$TRXTYPE='S';
	
    $comment1=$ProductName;
	
	require('../products/PayflowApi/payflow.php');
	
	$type='One Time Order';
	
    $transaction_id = $result[PNREF];
	//get status from transaction id of order payflow
	   $action="I";
		require('../products/PayflowApi/payflow.php'); 
		
         $status=$result['TRANSSTATE'];
			
			 if($status=='8')
			 {
			  $status_order="Paid";
			 }
			 elseif($status=='6')
			 {
			  $status_order="Pending";
			 }	
			 elseif($status=='11')
			 {
			  $status_order="Failed";
			 }
			  elseif($status=='1')
			 {
			  $status_order="Error";
			 }	
			  elseif($status=='7')
			 {
			  $status_order="Process";
			 }	
			  elseif($status=='14')
			 {
			  $status_order="Incomplete";
			 }	
			 
			 
    if($normalorder==0){
	
		if($GetSubscriptionRecords['BillEvery']==1)
			$cycle='MONTH';
			elseif($GetSubscriptionRecords['BillEvery']==2)
			$cycle='YEAR';
			else
			$cycle='WEEk';
			
			$TRXTYPE='R';
			
			$action="A";
			
			$totalOccurrences=$GetSubscriptionRecords['Duration'];
			
			$startDate=date('mdY', strtotime("+30 days"));
			
			$TransAMT=$GetSubscriptionRecords['SubscriptionPrice'];
			
         	require('../products/PayflowApi/payflow.php');  
			
			 //print_r($result);
			
			 $ProfileID=$result['PROFILEID'];
			 
			 $subscription_id=$result['RPREF'];
			 
           // exit;
			if (!$error) {
			
			
			/*existing member code*/
			$check_member_exist = mysql_query("SELECT * FROM `Member` WHERE Email = '".$_REQUEST['Contact0Email']."'");
				if(mysql_num_rows($check_member_exist) > 0)
				{
                        $ContactEmail='Email="'.$_REQUEST['Contact0Email'].'" ' ;
			 			$MemberRecords1=$utilObj->getSingleRow('Member',$ContactEmail);
						//$fetch_member = mysql_query("SELECT * FROM `Member` WHERE ID = '".$_REQUEST['Contact0Email']."'");
	     				//$MemberRecords1 = mysql_fetch_assoc($fetch_member);
						//echo "<pre/>";
						$ReferrerCode1=$MemberRecords1['ReferrerCode'];
						$Coach_member1 = mysql_query("SELECT ID FROM `Member` WHERE AppCode = '".$ReferrerCode1."'");
						$CoachRecords1 = mysql_fetch_assoc($Coach_member1); 
						$CoachID1=$CoachRecords1['ID'];
						
			
						$arrValue2=array('MemberID'=>$MemberRecords1['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorId'=>$MemberRecords1['SponsorAppId'],
			  'CoachId'=>$CoachID1,'BillingAmount'=>$totalAmtPaid,'Quantity'=>'1',
			  'PromoCodeId'=>'0','ProductSubscriptionId'=>$ProdSubRecords['ID'],'TransactionId'=>$transaction_id ,'Status'=>$status_order, 'Type'=>'One Time Order', 'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  			$subscription_id = $result['RPREF'];
			  			$insertedId1=$utilObj->insertRecord('OrderItem', $arrValue2);
						$Strwhere='TransactionId="'.$transaction_id.'" ' ;
			 			$OrderRecords=$utilObj->getSingleRow('OrderItem',$Strwhere);
						$startDate=date('Y-m-d');
						if($ProdSubRecords['BillEvery']==1) {
						$Interval=30;
						$cycle='Month'; }
						elseif($ProdSubRecords['BillEvery']==2) {
						$Interval=365;
						$cycle='Year'; }
						else{
						$Interval=7;
						$cycle='Week'; }
						$NextBillDate=date('Y-m-d', strtotime("+".$Interval." days"));
						
						$action ='I';
				require('../products/PayflowApi/payflow.php'); 
				
			    $status_subscription=strtolower($result['STATUS']);
						
					
						
			  			$arrValue2=array('MemberID'=>$MemberRecords1['ID'],'ProductID'=>$ProductRecords['ID'],'BillingAmt'=>$GetSubscriptionRecords['SubscriptionPrice'],'OrderID'=>$OrderRecords['ID'],'Qty'=>'1','PromoCodeId'=>'0','StartDate'=>$startDate,'EndDate'=>$startDate,'ProductSubscriptionId'=>$ProdSubRecords['ID'],'SubscriptionTransactionId'=>$subscription_id,'NextBillDate'=>$NextBillDate,'BillingCycle'=>$cycle,'Status'=>$status_subscription,'MerchantAccountId'=>$GetMerchantIdfromProduct['ManageMerchantAccID'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ProfileId'=>$ProfileID);
			
			  			$insertedId=$utilObj->insertRecord('RecurringOrder', $arrValue2);
							$condition = array('Email'=>$_REQUEST['Contact0Email']);
										    $emailexist  =  $infObject->getContactDetails($condition);
											
											if (is_array($emailexist)){
											//Update Query goes here
											$dataset=array('Email'=>trim($Email),'FirstName'=>trim($FirstName),'LastName'=>trim($Surname),'Phone1'=>$Phone);
				                             $addtoIs  =  $infObject->UpdateRecord('Contact',$emailexist[0]['Id'],$dataset);
											 
											 $condition1 = array('Email'=>$_REQUEST['Contact0Email']);
										    $getDetails  =  $infObject->getContactDetails($condition1);
											}
						
                       $url="http://themillionaireos.com/ecommerce-member-thankyou-simple";
						
				}else
				{
			
			$action ='I';
				require('../products/PayflowApi/payflow.php'); 
				
			    $status_subscription=strtolower($result['STATUS']);
           $url="http://themillionaireos.com/ecommerce-member-thankyou?prodId=".$_POST['ProductId']."&SubscriptionId=".$_POST['SubscriptionPlanId']."&Contact0Email=".$_POST['Contact0Email']."&Fname=".$_POST['Contact0FirstName']."&Lname=".$_POST['Contact0LastName']."&Contact0_SponsorAffiiliatecode=".$_POST['Contact0_SponsorAffiiliatecode']."&Contact0_ttrackingcode=".$_POST['Contact0_ttrackingcode']."&Phone=".$_POST['Contact0Phone1']."&Reffcode=".$_POST['Contact0_ReferrerCode']."&Tid=".$transaction_id."&SubTid=".$subscription_id."&amt_val=".$GetSubscriptionRecords['SubscriptionPrice']."&samt_val=".$GetSubscriptionRecords['SubscriptionPrice']."&AccountType=".$merchantRecords['AccountType']."&status_subscription=".$status_subscription."&status_order=".$status_order."&ProfID=".$ProfileID;
			
				}
				//This code is for the different product type for the existing member
				
				/*$memberId = $MemberRecords1['ID'];
				$ProductId = $_POST['ProductId'];
				$CommissionDetails = mysql_query("SELECT * FROM  `CommissionDetail` WHERE MemberID='".$memberId."' AND ProductID='".$ProductId."'");
				$CommissionDetails1 = mysql_fetch_assoc($CommissionDetails);
				if ($CommissionDetails1['ProductID'] !=  $ProductId)
				{
				     require_once "includeCommission.php";
				}*/
			
			  //include 'CommissionCalc.php';
			   header("Location: $url");
			 //echo  $ResponseMessage="Success! The test credit card has been charged! subscription ID: " . $subscription_id;
			 
			/*
			
				$action ='I';
				require('../products/PayflowApi/payflow.php'); 
				
			    $status_subscription=strtolower($result['STATUS']);
				
				
				
					
			 /*-------------------this url for one time order & Subscription-----------------
					  
			 $url="http://themillionaireos.com/ecommerce-member-thankyou?prodId=".$_POST['ProductId']."&SubscriptionId=".$_POST['SubscriptionPlanId']."&Contact0Email=".$_POST['Contact0Email']."&Fname=".$_POST['Contact0FirstName']."&Lname=".$_POST['Contact0LastName']."&Contact0_SponsorAffiiliatecode=".$_POST['Contact0_SponsorAffiiliatecode']."&Contact0_ttrackingcode=".$_POST['Contact0_ttrackingcode']."&Phone=".$_POST['Contact0Phone1']."&Reffcode=".$_POST['Contact0_ReferrerCode']."&Tid=".$transaction_id."&SubTid=".$subscription_id."&amt_val=".$GetSubscriptionRecords['SubscriptionPrice']."&samt_val=".$GetSubscriptionRecords['SubscriptionPrice']."&AccountType=".$merchantRecords['AccountType']."&status_subscription=".$status_subscription."&status_order=".$status_order."&ProfID=".$ProfileID;*/
			 
              //header("Location: $url");			
	
		  } 
			else 
			{  
			   header("Location: ".$_POST['failreturnurl']."?error=".$error."&#errormsg");
			   
			}
		    
			
	  }else{
          if (!$error) {
		  /*-------------------this url for one time order-----------------*/
              $type='One Time Order';
			 $sub_cond="&samt_val=".$totalAmtPaid;
			 $check_member_exist = mysql_query("SELECT * FROM `Member` WHERE Email = '".$_REQUEST['Contact0Email']."'");
				if(mysql_num_rows($check_member_exist) > 0)
				{
                        $ContactEmail='Email="'.$_REQUEST['Contact0Email'].'" ' ;
			 			$MemberRecords1=$utilObj->getSingleRow('Member',$ContactEmail);
						//$fetch_member = mysql_query("SELECT * FROM `Member` WHERE ID = '".$_REQUEST['Contact0Email']."'");
	     				//$MemberRecords1 = mysql_fetch_assoc($fetch_member);
						//echo "<pre/>";
						//print_r($MemberRecords1); 
						$ReferrerCode1=$MemberRecords1['ReferrerCode'];
						$Coach_member1 = mysql_query("SELECT ID FROM `Member` WHERE AppCode = '".$ReferrerCode1."'");
						$CoachRecords1 = mysql_fetch_assoc($Coach_member1); 
						$CoachID1=$CoachRecords1['ID'];
						
			
						$arrValue2=array('MemberID'=>$MemberRecords1['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorId'=>$MemberRecords1['SponsorAppId'],
			  'CoachId'=>$CoachID1,'BillingAmount'=>$totalAmtPaid,'Quantity'=>'1',
			  'PromoCodeId'=>'0','ProductSubscriptionId'=>$ProdSubRecords['ID'],'TransactionId'=>$transaction_id ,'Status'=>$status_order, 'Type'=>'One Time Order', 'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  			$subscription_id = $result['RPREF'];
			  			$insertedId1=$utilObj->insertRecord('OrderItem', $arrValue2);
						$url="http://themillionaireos.com/ecommerce-member-thankyou-simple";
		 }else{
        
		$url="http://themillionaireos.com/ecommerce-member-thankyou?prodId=".$_POST['ProductId']."&SubscriptionId=".$_POST['SubscriptionPlanId']."&Contact0Email=".$_POST['Contact0Email']."&Fname=".$_POST['Contact0FirstName']."&Lname=".$_POST['Contact0LastName']."&Contact0_SponsorAffiiliatecode=".$_POST['Contact0_SponsorAffiiliatecode']."&Contact0_ttrackingcode=".$_POST['Contact0_ttrackingcode']."&Phone=".$_POST['Contact0Phone1']."&Reffcode=".$_POST['Contact0_ReferrerCode']."&Tid=".$transaction_id."&SubTid=".$subscription_id."&status_order=".$status_order."&AccountType=".$merchantRecords['AccountType']."&amt_val=".$totalAmtPaid."".$sub_cond."";
			}
			//echo $url="http://themillionaireos.com/ecommerce-member-thankyou";
			 
			 //include 'CommissionCalc.php';
			 require_once "order_email.php";
			 
			 header("Location: $url");
		   
		   /*------one time order code here --->*/
		   
        }
	}
 }//End of payflow elseif 
?>