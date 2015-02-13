<?php
require_once "../../dbcon.php";
require_once "../../lib/classess/config/variables.php";
require_once "../../lib/classess/util_objects/util.php";
 include "data.php";
include "authnetfunction.php";
$utilObj = new util();
// Get the subscription ID if it is available. 
// Otherwise $subscription_id will be set to zero.
 $subscription_id = $_REQUEST['x_subscription_id'];

// Check to see if we got a valid subscription ID.
// If so, do something with it.
if ($subscription_id)
{
 //echo "<pre>";   // Get the response code. 1 is success, 2 is decline, 3 is error
 $response_code =  $_REQUEST['x_response_code'];
 
    // Get the reason code. 8 is expired card.
    $reason_code = $_POST['x_response_reason_code'];
 
    //if ($response_code == 1)
//    {
        // Approved!
 
        // Some useful fields might include:
        // $authorization_code = $_POST['x_auth_code'];
        // $avs_verify_result  = $_POST['x_avs_code'];
          $transaction_id         =    $_REQUEST['x_trans_id'];
          $subscription_id        = $_REQUEST['x_subscription_id'];
		 
		    $strwhere='SubscriptionTransactionId ='.$subscription_id.' ';
		    $SubscriptionRecords=$utilObj->getSingleRow("RecurringOrder", $strwhere);
			
			
		 
		      $strWhere='ID= "'.$SubscriptionRecords['MemberID'].'" ';
			  $MemberRecords=$utilObj->getSingleRow('Member',$strWhere);
			  
			  $Strwhere='ID ='.$SubscriptionRecords['ProductID'].'';
			  $ProductRecords=$utilObj->getSingleRow('Product',$Strwhere);
			 
			  $Strwhere='ProductID ='.$SubscriptionRecords['ProductID'].'';
			  $ProdSubRecords=$utilObj->getSingleRow('ProductSubscription',$Strwhere);
			
			 include "order_get_status.php"; //status of order 1 = Approved 2=Declined 3=Error 4=Held for Review
			 
			 $status=1;
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
			
			  $type="Recurring Order";
			  
		      $arrValue2=array('MemberID'=>$SubscriptionRecords['MemberID'],'ProductID'=>$SubscriptionRecords['ProductID'],'SponsorId'=>$MemberRecords['SponsorAppId'],
			  'CoachId'=>$MemberRecords['CoachID'],'BillingAmount'=>$ProductRecords['ProductPrice'],'Quantity'=>'1',
			  'PromoCodeId'=>'0','ProductSubscriptionId'=>$ProdSubRecords['ID'],'TransactionId'=>$transaction_id,'Status'=>$status_order, 'Type'=>$type,'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  
			  //print_r($arrValue2);
			  $insertedId=$utilObj->insertRecord('OrderItem', $arrValue2);
			  $arrValue_credit=array('response_code'=>$_REQUEST['x_response_code'], 
			  'response_reason_code'	=>$_REQUEST['x_response_reason_code'],
			  'response_reason_text'	=>$_REQUEST['x_response_reason_text'], 
			  'avs_code'				=>$_REQUEST['x_avs_code'], 
			  'auth_code'				=>$_REQUEST['x_auth_code'], 
			  'trans_id'				=>$_REQUEST['x_trans_id'], 
			  'method'					=>$_REQUEST['x_method'], 
			  'card_type'				=>$_REQUEST['x_card_type'], 
			  'account_number'			=>$_REQUEST['x_account_number'], 
			  'first_name'				=>$_REQUEST['x_first_name'], 
			  'last_name'				=>$_REQUEST['x_last_name'], 
			  'company'					=>$_REQUEST['x_company'], 
			  'address'					=>$_REQUEST['x_address'], 
			  'city'					=>$_REQUEST['x_city'], 
			  'state'					=>$_REQUEST['x_state'], 
			  'zip'						=>$_REQUEST['x_zip'], 
			  'country'					=>$_REQUEST['x_country'], 
			  'phone'					=>$_REQUEST['x_phone'], 
			  'fax'						=>$_REQUEST['x_fax'], 
			  'email'					=>$_REQUEST['x_email'], 
			  'invoice_num'				=>$_REQUEST['x_invoice_num'], 
			  'description'				=>$_REQUEST['x_description'], 
			  'type'					=>$_REQUEST['x_type'], 
			  'cust_id'					=>$_REQUEST['x_cust_id'], 
			  'amount'					=>$_REQUEST['x_amount'], 
			  'tax'						=>$_REQUEST['x_tax'], 
			  'duty'					=>$_REQUEST['x_duty'], 
			  'cvv2_resp_code'			=>$_REQUEST['x_cvv2_resp_code'], 
			  'cavv_response'			=>$_REQUEST['x_cavv_response'], 
			  'subscription_id'			=>$_REQUEST['x_subscription_id'], 
			  'subscription_paynum'		=>$_REQUEST['x_subscription_paynum'], 
			  'orderItemId'				=>$insertedId, 
			  'MemberID'				=>$SubscriptionRecords['MemberID'],
			  'Created'					=>date('Y-m-d H:i:s'),
			  'LastEdited'				=>date('Y-m-d H:i:s'));

			$insertedId_card=$utilObj->insertRecord('PaymentResponse', $arrValue_credit);
			$strwhere_credit="`CardNumber`='".$_REQUEST['x_account_number']."' AND `Status`='1'";
			$count_credit_card=$utilObj->getSingleRow('CreditCard', $strwhere_credit);
			
			if($count_credit_card)
			{
				if($_REQUEST['x_response_reason_code']=='6' || $_REQUEST['x_response_reason_code']=='7' || $_REQUEST['x_response_reason_code']=='8')
				{
					$arrValue=array('Status'=>'0');
			  		$strWhere='MemberID='.$SubscriptionRecords['MemberID'].' AND CardNumber="'.$_REQUEST['x_account_number'].'"';
			 		$update_credit=$utilObj->updateRecord('CreditCard', $strWhere, $arrValue);
				}
				
			}
			else
			{
				$arrValue=array('Status'=>'0');
			  	$strWhere='MemberID='.$SubscriptionRecords['MemberID'];
			 	$update_credit=$utilObj->updateRecord('CreditCard', $strWhere, $arrValue);
			  if($_REQUEST['x_response_reason_code']=='6' || $_REQUEST['x_response_reason_code']=='7' || $_REQUEST['x_response_reason_code']=='8')
				{
					$stat_credit=0;
				}
				else
				{
					$stat_credit=1;
				}
				
				$credit_card_record=array('Created'=>date('Y-m-d H:i:s'),
				'LastEdited'		=>date('Y-m-d H:i:s'), 
				'FirstName'			=>$_REQUEST['x_first_name'], 
				'LastName'			=>$_REQUEST['x_last_name'], 
				'PhoneNumber'		=>$_REQUEST['x_phone'], 
				'Email'				=>$_REQUEST['x_email'], 
				'BillAddress1'		=>$_REQUEST['x_address'], 
				'BillAddress2'		=>$_REQUEST['x_address'], 
				'BillCity'			=>$_REQUEST['x_city'], 
				'BillState'			=>$_REQUEST['x_state'], 
				'BillZip'			=>$_REQUEST['x_zip'], 
				'BillCountry'		=>$_REQUEST['x_country'], 
				'NameOnCard'		=>$_REQUEST['x_first_name'].' '.$_REQUEST['x_last_name'], 
				'CardNumber'		=>$_REQUEST['x_account_number'], 
				'Status'			=>$stat_credit, 
				'CardType'			=>$_REQUEST['x_card_type'], 
				'MemberID'			=>$SubscriptionRecords['MemberID']);
				$utilObj->insertRecord('CreditCard', $credit_card_record);
			}
			$arr_value_card_info=array();
			  
			 $lastbilldate=$SubscriptionRecords['NextBillDate'];
			  
			  $cycle=$SubscriptionRecords['BillingCycle'];
			  
			  if($cycle=='Month')
			  {
			  $days=30;
			  }
			  elseif($cycle=='Year')
			  {
			  $days=365;
			  }
			  else
			  {
			  $days=7;
			  }
			  
			 
			   $nextbilldate= date('Y-m-d', strtotime($lastbilldate. ' + '.$days.' days'));
			// $NextBillDate=date('Y-m-d', strtotime("+".$Interval." days"));
			  
			  $arrValue=array('NextBillDate'=>$nextbilldate,'LastBillDate'=>$lastbilldate,'LastEdited'=>date('Y-m-d H:i:s'));
			  
			  $strWhere='SubscriptionTransactionId='.$subscription_id;
			  
			  //print_r($arrValue);
			  
			 $Updaterec=$utilObj->updateRecord('RecurringOrder', $strWhere, $arrValue);
			  
			  
			  
			 $SponsorID=$MemberRecords['SponsorAppId'];
			 
			 $strWhere='SponsorID='.$SponsorID.'';
			 
			 $ProductId=$SubscriptionRecords['ProductID'];
			 
			/* $SubRecords=$utilObj->getSingleRow("ProductSubscription", "ProductID='".$ProductId."' ");
			 if($SubRecords>0)
			 {
			 	$strWhere='ProductName='.$ProductId.' AND Type="Subscription" ';
			 }	
			 else*/ 
			 
			 /*--before subscription billdate start add sponsor commission for one time order-------*/
			 
			 
			 $strWhere='ProductName='.$ProductId.' AND Type="Subscription" ';		 
			 
			 $ComLevSaleRecord=$utilObj->getSingleRow('SponsorCommissionLevel' ,$strWhere); //getting Commission Level for Sponsor 
			 
			 
			 $CommissionType=$ComLevSaleRecord['CommissionnType'];
			 
			 $SponsorRecord=$utilObj->getSingleRow('Member',"AppId='".$SponsorID."' " );
			 
			 $SponsorIdLevel2=$SponsorRecord['SponsorAppId'];
			 
			 if($CommissionType=="Percentage")
			 {
			   
			 	$Level1Commission=($ProductRecords['ProductPrice']*$ComLevSaleRecord['Level1'])/100;
			    $Level2Commission=($ProductRecords['ProductPrice']*$ComLevSaleRecord['Level2'])/100;
				
				$comissionlevelone="Level1 Sale : ".$ComLevSaleRecord['Level1']."% = <b>".$Level1Commission."</b>";
				$comissionleveltwo="Level2 Sale : ".$ComLevSaleRecord['Level2']."% = <b>".$Level2Commission."</b>";
				
				
			 }
			 else
			 {
			    $Level1Commission=$ComLevSaleRecord['Level1'];
			    $Level2Commission=$ComLevSaleRecord['Level2'];
				
				$comissionlevelone="Level1 Sale : ".$Level1Commission."$= <b>".$Level1Commission."</b>";
				$comissionleveltwo="Level2 Sale : ".$Level2Commission."$ =<b> ".$Level2Commission."</b>";
			 }
			 
			
			
			/*--------getting second commission level sponor data*/
			if($Level1Commission>0){
			   $arrcommission=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorID'=>$SponsorID,'OrderItemID'=>$OrderRecords['ID'],'Comission'=>$comissionlevelone,'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>'Order','Earned'=>$Level1Commission,'Sold'=>$Level1Commission);
			   //print_r($arrcommission);
			   $commissionId=$utilObj->insertRecord('CommissionDetail', $arrcommission);
             }
			 
			if($Level2Commission > 0){
			
			 /* echo 'hi';
			  exit;*/
			  
		   $arrcommission=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorID'=>$SponsorIdLevel2,'OrderItemID'=>$OrderRecords['ID'],'Comission'=>$comissionleveltwo,'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>'Order','Earned'=>$Level2Commission,'Sold'=>$Level2Commission);
		   //print_r($arrcommission);
		     $commissionId=$utilObj->insertRecord('CommissionDetail', $arrcommission);
				
			  }
		//echo "</pre>";  	  
			  
    //}
//    else if ($response_code == 2)
//    {
//        // Declined
//    }
//    else if ($response_code == 3 && $reason_code == 8)
//    {
//        // An expired card
//    }
//    else 
//    {
//        // Other error
//    }
}


$arr_ser_post=serialize($_REQUEST);
$arr_post=array('MemberUtilityValue'=>$arr_ser_post,'Created'=>date("Y-m-d H:i:s"), 'LastEdited'=>date("Y-m-d H:i:s"), 'MemberUtilityName'=>'TmpMemSt' );
//$utilObj->insertRecord('MemberUtilityTest', $arr_post); 
$post_values='';
foreach($_REQUEST as $key=>$value)
{
	$post_values.=$key.'=>'.$value.'<br/>';
}
mail('irfan@nadsoftdev.com','silentpostdata',$post_values);
?>