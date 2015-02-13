<?php
  /////////////////code for add order detail and creditcard detail 
  
include "../admintti/ecommerce/Auth_Recurring-curl/data.php";
include "../admintti/ecommerce/Auth_Recurring-curl/authnetfunction.php";


$utilObj = new util();
function gettime($dt)
{
   if($TimeOfComm=='Weekly')
  {
		$fdate = date('Y-m-d', strtotime($dt.'last saturday'));
		$SixDAgo = strtotime ( '-6 day' , strtotime ( $fdate ) ) ;				
		$tdate = date( 'Y-m-d' , $SixDAgo );
		$dt=$fdate;
	}
	elseif($TimeOfComm=='Monthly')
	{
	$fdate = date("Y-m-01", strtotime($dt."last month"));
	$tdate = date("Y-m-t", strtotime($dt."last month"));
	$dt=$fdate;
	}
	else
	{
	$fdate = $dt;
	$tdate = $dt;
	$dt= date('Y-m-d', $dt-86400);
	}
// Code to get Total Sale For above Coach between $fdate And $tdate 


					
					$count_wmi = mysql_query("SELECT * FROM OrderItem WHERE CoachID = '".$CoachID."' AND Created >='".date("Y-m-d",strtotime($fdate))."' AND Created <='".date("Y-m-d",strtotime($tdate))."' ");
					
 	                while($row=mysql_fetch_array($count_wmi))
					{
					$TotalSale += $row['Quantity'];
					$TotalAmount +=$row['BillingAmount'];
					}
					
					if($TotalSale=='0')
					{
						gettime($dt);
					}
					
}



/*-----------for order-----variables-------------*/
$subscription_id=trim($_REQUEST['SubTransId']);
$transaction_id=trim($_REQUEST['TransId']);
$ProfileID=trim($_REQUEST['ProfileID']);
$amt_val=trim($_REQUEST['amt_val']);
$samt_val=trim($_REQUEST['samt_val']);
$AccType=trim($_REQUEST['AccountType']);
$status_subscription=trim($_REQUEST['status_subscription']);
$statusorder=trim($_REQUEST['status_order']);
/*--------------end-------------------*/
          
		 $fetch_member = mysql_query("SELECT * FROM `Member` WHERE ID = '".$memberId."'");
	     $MemberRecords = mysql_fetch_assoc($fetch_member); 

	     $Strwhere='ID ='.$ProductId.'';
	     $ProductRecords=$utilObj->getSingleRow('Product',$Strwhere);
	   
	    $Strwhere='ProductID ='.$ProductId.'';
	    $ProdSubRecords=$utilObj->getSingleRow('ProductSubscription',$Strwhere);
		
		
		/*----------get coach Id--------------*/
		$ReferrerCode=$MemberRecords['ReferrerCode'];
		$Coach_member = mysql_query("SELECT ID FROM `Member` WHERE AppCode = '".$ReferrerCode."'");
		$CoachRecords = mysql_fetch_assoc($Coach_member); 
		$CoachID=$CoachRecords['ID'];
		/*--------------end---------------*/	 
// this code for pay flow 
if($AccType==16){
               $arrValue2=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorId'=>$MemberRecords['SponsorAppId'],
			  'CoachId'=>$CoachID,'BillingAmount'=>$amt_val,'Quantity'=>'1',
			  'PromoCodeId'=>'0','ProductSubscriptionId'=>$ProdSubRecords['ID'],'TransactionId'=>$transaction_id ,'Status'=>"".$statusorder."", 'Type'=>'One Time Order', 'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			// echo '------one time---'; 
			// echo "<pre/>";
			// print_r($arrValue2);
			 // exit;
			  $insertedId=$utilObj->insertRecord('OrderItem', $arrValue2);
			// die;
			 
			$Strwhere='TransactionId="'.$transaction_id.'" ' ;
			 $OrderRecords=$utilObj->getSingleRow('OrderItem',$Strwhere);
			 
		
          if($ProfileID!='' || $subscription_id!='')  //insert into reccurringOrder table.
		 {
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
			
	        $arrValue2=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'BillingAmt'=>$samt_val,'OrderID'=>$OrderRecords['ID'],'Qty'=>'1','PromoCodeId'=>'0','StartDate'=>$startDate,'EndDate'=>$startDate,'ProductSubscriptionId'=>$ProdSubRecords['ID'],'SubscriptionTransactionId'=>$subscription_id,'NextBillDate'=>$NextBillDate,'BillingCycle'=>$cycle,'Status'=>$status_subscription,'MerchantAccountId'=>$GetMerchantIdfromProduct['ManageMerchantAccID'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ProfileId'=>$ProfileID);
			
			//echo '------recurring---';
			 //echo "<pre/>";
			 //print_r($arrValue2);
			
			
			 $insertedId=$utilObj->insertRecord('RecurringOrder', $arrValue2);
			
			
	    }
		 require_once "order_email.php";
//die();	
	
}//end  of payflow order details with creating new member
else{
	     include "../admintti/ecommerce/Auth_Recurring-curl/order_get_status.php"; //status of order 1 = Approved 2=Declined 3=Error 4=Held for Review
		  
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
		  
		if(empty($amt_val) || $amt_val=='0')
		{
			$amt_val=$ProductRecords['ProductPrice'];
		}
	  
	  
	  /* after meber is created add its order to  orderitem table as well as subscription table*/
	  
	      	  $arrValue2=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorId'=>$MemberRecords['SponsorAppId'],
			  'CoachId'=>$CoachID,'BillingAmount'=>$amt_val,'Quantity'=>'1',
			  'PromoCodeId'=>'0','ProductSubscriptionId'=>$ProdSubRecords['ID'],'TransactionId'=>$transaction_id ,'Status'=>$status_order, 'Type'=>'One Time Order', 'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  
			  $insertedId=$utilObj->insertRecord('OrderItem', $arrValue2);
			
			 $Strwhere='TransactionId="'.$transaction_id.'" ' ;
			 $OrderRecords=$utilObj->getSingleRow('OrderItem',$Strwhere);
			
		if($ProfileID!='' || $subscription_id!='')  //insert into reccurringOrder table.
		{
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
			
			 include "../admintti/ecommerce/Auth_Recurring-curl/subscription_get_status.php";
			 
			 $status_subscription=$status;
			 
			
			$arrValue2=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'BillingAmt'=>$samt_val,'OrderID'=>$OrderRecords['ID'],'Qty'=>'1','PromoCodeId'=>'0','StartDate'=>$startDate,'EndDate'=>$startDate,'ProductSubscriptionId'=>$ProdSubRecords['ID'],'SubscriptionTransactionId'=>$subscription_id,'NextBillDate'=>$NextBillDate,'BillingCycle'=>$cycle,'Status'=>$status_subscription,'MerchantAccountId'=>$GetMerchantIdfromProduct['ManageMerchantAccID'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ProfileId'=>$ProfileID);
			
			  $insertedId=$utilObj->insertRecord('RecurringOrder', $arrValue2);
			  require_once "order_email.php";
			  
			}
			
 }//end of authorize.net order with creating new member	
			 require_once "includeCommission.php";
			/*-----insert Creadit Card  Details-----------*/
			/*	 $CreditcardRecords=$utilObj->getMultipleRow("CreditCard","Email='".$_REQUEST['Contact0Email']."' ");
				 if(count($CreditcardRecords)<1)
				 {			
				  $arrValue1=array('MemberId'=>$MemberRecords['ID'],'FirstName'=>$_REQUEST['Contact0FirstName'],'LastName'=>$_REQUEST['Contact0LastName'],
				  'PhoneNumber'=>$_REQUEST['Contact0Phone2'],'Email'=>$_REQUEST['Contact0Email'],'BillAddress1'=>$_REQUEST['Contact0StreetAddress1'],
				  'BillAddress2'=>$_REQUEST['Contact0StreetAddress2'],'BillCity'=>$_REQUEST['Contact0City'],'BillState'=>$_REQUEST['Contact0State'],
				  'BillZip'=>$_REQUEST['Contact0PostalCode'],'BillCountry'=>$_REQUEST['Contact0Country'],'CardType'=>$_REQUEST['CreditCard0CardType'],
				  'CardNumber'=>$_REQUEST['CreditCard0CardNumber'],'ExpirationMonth'=>$_REQUEST['CreditCard0ExpirationMonth'],
				  'ExpirationYear'=>$_REQUEST['CreditCard0ExpirationYear'],'Last4'=>$_REQUEST['CreditCard0VerificationCode'],'Created'=>date('Y-m-d H:i:s'),
				  'LastEdited'=>date('Y-m-d H:i:s'));
				  $insertedId=$utilObj->insertRecord('CreditCard', $arrValue1);
				  }*/
			  
	 /*-------------------End-------------*/  
			  
			  
  		//Code For Coaching Commission  ////////////////////////////////////////////////////////////////////////////////
/*			 $TotalCochingCommission=0;
			 
			 $CoachID=$MemberRecords['CoachID'];
			 
			 if($CoachID!="0" || $CoachID!="" )
			 {
			 $strWhere='ReferenceCoachID='.$CoachID.'';
			 
			 $CommissionRecord=$utilObj->getSingleRow(' CommissionLevel' , $strWhere); //getting Commission Level For Coach
			 $commissionProgId=$CommissionRecord['CommissionLevelName'];
			 
			 $strWhere='CommissionProgramID='.$commissionProgId.'';
			 $ComLevSaleRecord=$utilObj->getSingleRow('CommissionLevelSale' , $strWhere); //getting Commission sale for Commission Program
			 foreach($ComLevSaleRecord as $CommisssionVal)
			 {
			 	$TimeOfComm=$CommisssionVal['CommissionBasedOn'];
				
				$date=date('Y-m-d');
				gettime($date);
				
				if($CommisssionVal['Criteria1']!=="")
				{
					$cond=$TotalSale." ".$CommisssionVal['Criteria1'];
				}
				if($CommisssionVal['Criteria2']!=="")
				{
					$cond .=" && ".$TotalSale." ".$CommisssionVal['Criteria2'];
				}
				if($cond)
				{
				$CoachCommissionPercentage=$CommisssionVal['Sale'];//this is the commission percentage of Coach
				
				}
				 $TotalCochingCommission +=($TotalAmount*$CoachCommissionPercentage)/100;
				
				
				
			 }
		 }
		 
			 echo "Coaching Commission :".$TotalCochingCommission;*/
			 
			 
			 //Code For Sponsor Commission 
			 ///////////////////////////////////////////////////////////////////////////////////
			 
			 //$MemberRecords=$utilObj->getSingleRow('Member',$strWhere); //getting Sponsor ID of Member
			 
			 
			/* $SponsorID=$MemberRecords['SponsorAppId'];
			 
			 $strWhere='SponsorID='.$SponsorID.'';
			 
			 $ProductId=$ProductRecords['ID'];
			 echo "ProductId ".$ProductId;
			 
			 $SubRecords=$utilObj->getSingleRow("ProductSubscription", "ProductID='".$ProductId."' ");
			 if($SubRecords>0)
			 {
			 	$strWhere='ProductName='.$ProductId.' AND Type="Subscription" ';
			 }	
			 else*/ 
			 
			 /*--before subscription billdate start add sponsor commission for one time order-------
			 
			 $strWhere='ProductName='.$ProductId.' AND Type="Product" OR Type="Subscription" ';		 
			 $ComLevSaleRecord=$utilObj->getSingleRow('SponsorCommissionLevel' ,$strWhere); //getting Commission Level for Sponsor 
			 print_r($ComLevSaleRecord);
			 $CommissionType=$ComLevSaleRecord['CommissionnType'];
			 
			 $SponsorRecord=$utilObj->getSingleRow('Member',"AppId='".$SponsorID."' " );
			 print_r($SponsorRecord);
			 $SponsorIdLevel2=$SponsorRecord['SponsorAppId'];
			 
			 if($CommissionType=="Percentage")
			 {
			   
			 	$Level1Commission=($amt_val*$ComLevSaleRecord['Level1'])/100;
			    $Level2Commission=($amt_val*$ComLevSaleRecord['Level2'])/100;
				
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
			 
			
			
			/*--------getting second commission level sponor data
			if($Level1Commission>0){
			   $arrcommission=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorID'=>$SponsorID,'OrderItemID'=>$OrderRecords['ID'],'Comission'=>$comissionlevelone,'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>'Order','Earned'=>$Level1Commission,'Sold'=>$Level1Commission);
			   $commissionId=$utilObj->insertRecord('CommissionDetail', $arrcommission);
             }
			 
			if($Level2Commission > 0){
		   $arrcommission=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorID'=>$SponsorIdLevel2,'OrderItemID'=>$OrderRecords['ID'],'Comission'=>$comissionleveltwo,'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>'Order','Earned'=>$Level2Commission,'Sold'=>$Level2Commission);
		   
			  $commissionId=$utilObj->insertRecord('CommissionDetail', $arrcommission);
				
			  }
		  
			 
			 
			 
	         echo "Level1 -".$Level1Commission;
			 
			 echo "Level2 -".$Level2Commission;
			 
			 die;
			 */
		
			 
			 ///////////////////////////////////////////////////////////////////////////////////////////////////////
  ?>