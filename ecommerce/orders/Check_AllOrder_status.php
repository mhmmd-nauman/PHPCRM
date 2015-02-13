<?php

$utilObj = new util();
$objGroups = new Groups();
/* include "ecommerce/products/PayflowApi/payflow_curl.php";
  include ("ecommerce/Auth_Recurring-curl/data.php");
  include ("ecommerce/Auth_Recurring-curl/authnetfunction.php"); */
//require('../products/PayflowApi/payflow_curl.php');
//get status of Recurring Order from PayFlow order wich are processed automaticly
$RecurOrderRec1 = $utilObj->getMultipleRow('RecurringOrder', $where);

$member_ids = array();
$product_ids = array();
$merchant_ids = array();
foreach($RecurOrderRec1 as $rVals) {
	$member_ids[] = $rVals['MemberID'];
	$product_ids[] = $rVals['ProductID'];
	$merchant_ids[] = $rVals['MerchantAccountId'];
}

$members_str = implode(',', $member_ids);
$product_str = implode(',', $product_ids);
$merchant_str = implode(',', $merchant_ids);



// all members array
$allMembersWhere = 'ID IN(' . $members_str . ') ';
$MembersRecords = $utilObj->getMultipleRow('Member', $allMembersWhere);
$mArr = array();
foreach($MembersRecords as $m) {
	$mArr[$m['ID']] = $m;
}

// all products array
$allProductsWhere = 'ID IN(' . $product_str . ') ';
$ProductsRecords = $utilObj->getMultipleRow('Product', $allProductsWhere);
$pArr = array();
foreach($ProductsRecords as $p) {
	$pArr[$p['ID']] = $p;
}

// all products subscribtions array - TODO: make single call
$allProductsWhere = 'ProductID IN(' . $product_str . ') ';
$ProductsSubRecords = $utilObj->getMultipleRow('ProductSubscription', $allProductsWhere);
$psArr = array();
foreach($ProductsSubRecords as $ps) {
	$psArr[$ps['ProductID']] = $ps;
}

// all merchants array
$allMerchantsWhere = 'MerchantId IN(' . $merchant_str . ') ';
$MerchantsRecords = $utilObj->getMultipleRow('ManageMerchantAcc', $allMerchantsWhere);
$mrArr = array();
foreach($MerchantsRecords as $mrs) {
	$mrArr[$mrs['MerchantId']] = $mrs;
}


foreach ($RecurOrderRec1 as $RecurOrderVal1) {
	
//	$strWhere = 'ID= "' . $RecurOrderVal1['MemberID'] . '" ';
//	$MemberRecords = $utilObj->getSingleRow('Member', $strWhere);
//	echo 'SELECT * FROM Member WHERE '.$strWhere.'<br><br>';
	$MemberRecords = $mrArr[$RecurOrderVal1['MemberID']];
//	
//	$Strwhere = 'ID =' . $RecurOrderVal1['ProductID'] . '';
//	$ProductRecords = $utilObj->getSingleRow('Product', $Strwhere);
//	echo 'SELECT * FROM Product WHERE '.$Strwhere.'<br><br>';
	
	$ProductRecords = $pArr[$RecurOrderVal1['ProductID']];

//	$Strwhere = 'ProductID =' . $RecurOrderVal1['ProductID'] . '';
//	$ProdSubRecords = $utilObj->getSingleRow('ProductSubscription', $Strwhere);
//	echo 'SELECT * FROM ProductSubscription WHERE '.$Strwhere.'<br><br>';
	
	$ProdSubRecords = $psArr[$RecurOrderVal1['ProductID']];

//	$strwhere = 'MerchantId=' . $RecurOrderVal1["MerchantAccountId"] . '';
//	$merchantRecords = $utilObj->getSingleRow('ManageMerchantAcc', $strwhere);
//	echo 'SELECT * FROM ManageMerchantAcc WHERE '.$strwhere.'<br><br>';
	
	$merchantRecords = $mrArr[$RecurOrderVal1["MerchantAccountId"]];
	
	$PayFlowUser = trim($merchantRecords['PayFlowUser']);
	$PayFlowVendor = trim($merchantRecords['PayFlowVendor']);
	$PayFlowPartner = trim($merchantRecords['PayFlowPartner']);
	$PayFlowPassword = trim($merchantRecords['PayFlowPassword']);
	$PayFlowCurrency = trim($merchantRecords['PayFlowCurrency']);
	if ($merchantRecords['Mode'] == '-1')
		$mode = 0;
	else
		$mode = 1;

	if (trim($PayFlowVendor) != '' || trim($PayFlowUser) != '' || trim($PayFlowPartner) != '' || trim($PayFlowPassword) != '') {
		$payflow = new payflow($PayFlowVendor, $PayFlowUser, $PayFlowPartner, $PayFlowPassword, $mode);

		//echo "<pre>";
		//print_r($payflow);

		if ($payflow->get_errors()) {
			echo $payflow->get_errors();
			exit;
		}
	}
	$profileid = $RecurOrderVal1['ProfileId'];
	$action = 'I';



	$TRXTYPE = 'R';
	$data_array = array(
		'ORIGPROFILEID' => $profileid,
		'ACTION' => $action,
		'clientip' => '0.0.0.0',
	);

	$result = $payflow->View_Recurring($data_array);
	$count = count($result) - 2;
	$i = ($count / 6);
	for ($j = 1; $j <= $i; $j++) {
		$transaction_id = $result['P_PNREF' . $j . ''];
		$status = $result['P_TRANSTATE' . $j . ''];
		if ($status == '8') {
			$status_order = "Paid";
		} elseif ($status == '6') {
			$status_order = "Pending";
		} elseif ($status == '11') {
			$status_order = "Failed";
		} elseif ($status == '1') {
			$status_order = "Error";
		} elseif ($status == '7') {
			$status_order = "Process";
		} elseif ($status == '14') {
			$status_order = "Incomplete";
		}
		$strwhere = "TransactionId='" . $transaction_id . "' ";
		$OrderRecords = $utilObj->getMultipleRow('OrderItem', $strwhere);
		if ($OrderRecords == 0) {
			$type = "Recurring Order";

			$arrValue2 = array('MemberID' => $RecurOrderVal1['MemberID'], 'ProductID' => $RecurOrderVal1['ProductID'], 'SponsorId' => $MemberRecords['SponsorAppId'],
				'CoachId' => $MemberRecords['CoachID'], 'BillingAmount' => $ProductRecords['ProductPrice'], 'Quantity' => '1',
				'PromoCodeId' => '0', 'ProductSubscriptionId' => $ProdSubRecords['ID'], 'TransactionId' => $transaction_id, 'Status' => $status_order, 'Type' => $type, 'Created' => date('Y-m-d H:i:s'), 'LastEdited' => date('Y-m-d H:i:s'));
			$insertedId = $utilObj->insertRecord('OrderItem', $arrValue2);

			$lastbilldate = $RecurOrderVal1['NextBillDate'];
			$cycle = $RecurOrderVal1['BillingCycle'];
			if ($cycle == 'Month') {
				$days = 30;
			} elseif ($cycle == 'Year') {
				$days = 365;
			} else {
				$days = 7;
			}
			$nextbilldate = date('Y-m-d', strtotime("+" . $days . " days", $lastbilldate));
			$arrValue = array('NextBillDate' => $nextbilldate, 'LastBillDate' => $lastbilldate, 'LastEdited' => date('Y-m-d H:i:s'));
			$strWhere = 'SubscriptionTransactionId=' . $RecurOrderVal1['SubscriptionTransactionId'];
			$Updaterec = $utilObj->updateRecord('RecurringOrder', $strWhere, $arrValue);
		}
	}
	// print_r($result);
}

//Code to get Status of recurring order from authorized.Net
$OrderRec = $utilObj->getMultipleRow('OrderItem', $StrwhereOrder);
// print_r($OrderRec);

if (count($OrderRec) > 0) {


	foreach ($OrderRec as $OrderVal) {

		$status = '';
		$transaction_id = $OrderVal['TransactionId'];
		if ($OrderVal['Status'] == "Pending") {
			$strwhereProd = 'ID=' . $OrderVal['ProductID'] . '';
			$GetMerchantIdfromProduct = $utilObj->getSingleRow('Product', $strwhereProd);
			$strwhere = 'MerchantId=' . $GetMerchantIdfromProduct['ManageMerchantAccID'] . '';
			$merchantRecords = $utilObj->getSingleRow('ManageMerchantAcc', $strwhere);
			if ($merchantRecords['AccountType'] == 1 || $merchantRecords['AccountType'] == 3) {
				//get status from authorized .net and update it if only status is pending
//				include "../admintti/ecommerce/Auth_Recurring-curl/order_get_status.php";

				if ($status == '1') {
					$status_order = "Paid";
				} elseif ($status == '4') {
					$status_order = "Pending";
				} elseif ($status == '2') {
					$status_order = "Failed";
				} elseif ($status == '3') {
					$status_order = "Error";
				}
			} elseif ($merchantRecords['AccountType'] == 16) {
				//Code to get/check Status of One time order from Paypal if only status is pending

				$strwhere = 'AccountType=' . $merchantRecords['AccountType'];
				$merchantRecords = $utilObj->getSingleRow('ManageMerchantAcc', $strwhere);

				$PayFlowUser = trim($merchantRecords['PayFlowUser']);
				$PayFlowVendor = trim($merchantRecords['PayFlowVendor']);
				$PayFlowPartner = trim($merchantRecords['PayFlowPartner']);
				$PayFlowPassword = trim($merchantRecords['PayFlowPassword']);
				$PayFlowCurrency = trim($merchantRecords['PayFlowCurrency']);
				if ($merchantRecords['Mode'] == '-1')
					$mode = 0;
				else
					$mode = 1;
				$payflow = new payflow($PayFlowVendor, $PayFlowUser, $PayFlowPartner, $PayFlowPassword, $mode);


				if ($payflow->get_errors()) {
					echo $payflow->get_errors();
					exit;
				}
				$action = "I";
				$data_array = array(
					'ORIGID' => $transaction_id,
					'ACTION' => $action,
					'clientip' => '0.0.0.0',
				);

				$result = $payflow->View_Order($data_array);



				$status = $result['TRANSSTATE'];

				if ($status == '8') {
					$status_order = "Paid";
				} elseif ($status == '6') {
					$status_order = "Pending";
				} elseif ($status == '11') {
					$status_order = "Failed";
				} elseif ($status == '1') {
					$status_order = "Error";
				} elseif ($status == '7') {
					$status_order = "Process";
				} elseif ($status == '14') {
					$status_order = "Incomplete";
				}
			}



			$arrValue = array('Status' => $status_order, 'LastEdited' => date('Y-m-d H:i:s'));
			$strWhere = 'TransactionId= "' . $transaction_id . '" ';

			$Updaterec = $utilObj->updateRecord('OrderItem', $strWhere, $arrValue);
		}
	}
}
?>