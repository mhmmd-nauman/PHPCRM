<?php
  /////////////////code for add order detail and creditcard detail 
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
	  
	      	  $arrValue2=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorId'=>$MemberRecords['SponsorAppId'],
			  'CoachId'=>$MemberRecords['CoachID'],'BillingAmount'=>$ProductRecords['ProductPrice'],'Quantity'=>'1',
			  'PromoCodeId'=>'0','ProductSubscriptionId'=>$ProdSubRecords['ID'],'TransactionId'=>$transaction_id ,'Status'=>$status_order, 'Type'=>$type, 'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'));
			  $insertedId=$utilObj->insertRecord('OrderItem', $arrValue2);
			
			 $Strwhere='TransactionId="'.$transaction_id.'" ' ;
			  $OrderRecords=$utilObj->getSingleRow('OrderItem',$Strwhere);
			  			
			
			if($normalorder==0)  //insert into reccurringOrder table.
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
			
			$arrValue2=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'BillingAmt'=>$ProductRecords['ProductPrice'],'OrderID'=>$OrderRecords['ID'],'Qty'=>'1','PromoCodeId'=>'0','StartDate'=>$startDate,'EndDate'=>$startDate,'ProductSubscriptionId'=>$ProdSubRecords['ID'],'SubscriptionTransactionId'=>$subscription_id,'NextBillDate'=>$NextBillDate,'BillingCycle'=>$cycle,'Status'=>$status_subscription,'MerchantAccountId'=>$GetMerchantIdfromProduct['ManageMerchantAccID'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'ProfileId'=>$ProfileID);
			  $insertedId=$utilObj->insertRecord('RecurringOrder', $arrValue2);
			}
			 $CreditcardRecords=$utilObj->getMultipleRow("CreditCard","Email='".$_REQUEST['Contact0Email']."' ");
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
			  }
  		//Code For Coaching Commission  ////////////////////////////////////////////////////////////////////////////////
			 $TotalCochingCommission=0;
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
			 echo "Coaching Commission :".$TotalCochingCommission;
			 //Code For Sponsor Commission 
			 ///////////////////////////////////////////////////////////////////////////////////
			 
			 //$MemberRecords=$utilObj->getSingleRow('Member',$strWhere); //getting Sponsor ID of Member
			 $SponsorID=$MemeberRecords['SponsorAppId'];
			 
			 $strWhere='SponsorID='.$SponsorID.'';
			 $ProductId=$ProductRecords['ID'];
			 $SubRecords=$utilObj->getSingleRow("ProductSubscription", "ProductID='".$ProductId."' ");
			 if($SubRecords>0)
			 {
			 	$strWhere='ProductName='.$ProductId.' AND Type="Subscription" ';
			 }	
			 else $strWhere='ProductName='.$ProductId.' AND Type="Product" ';		 
			 
			 $ComLevSaleRecord=$utilObj->getSingleRow('SponsorCommissionLevel' ,$strWhere); //getting Commission Level for Sponsor Commission Program
			 
			 $CommissionType=$ComLevSaleRecord['CommissionnType'];
			 
			 $SponsorRecord=$utilObj->getSingleRow('Member',"AppId='".$SponsorID."' " );
			 $SponsorIdLevel2=$SponsorRecord['SponsorAppId'];
			 if($CommissionType=="Percentage")
			 {
			 	$Level1Commission=($ProductRecords['ProductPrice']*$ComLevSaleRecord['Level1'])/100;
			    $Level2Commission=($ProductRecords['ProductPrice']*$ComLevSaleRecord['Level2'])/100;
			 }
			 else
			 {
			    $Level1Commission=$ComLevSaleRecord['Level1'];
			    $Level2Commission=$ComLevSaleRecord['Level2'];
			 }
			 echo "Level1 -".$Level1Commission;
			 echo "Level2 -".$Level2Commission;
			 ///////////////////////////////////////////////////////////////////////////////////////////////////////
  ?>