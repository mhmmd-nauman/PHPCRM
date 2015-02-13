<?php 
require_once "../../lib/include.php";
$utilObj = new util();

/*function gettime($dt,$TimeOfComm,$CoachID)
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
	
   //$fdate=date('Y-m-d');
	
// Code to get Total Sale For above Coach between $fdate And $tdate 

echo "SELECT * FROM OrderItem WHERE CoachID = '".$CoachID."' AND date(Created) >='".date("Y-m-d",strtotime($tdate))."' AND date(Created) <='".date("Y-m-d",strtotime($fdate))."' ";

	$count_wmi = mysql_query("SELECT * FROM OrderItem WHERE CoachID = '".$CoachID."' AND date(Created) >='".date("Y-m-d",strtotime($tdate))."' AND date(Created) <='".date("Y-m-d",strtotime($fdate))."' ");
					
 	                while($row=mysql_fetch_array($count_wmi))
					{
					$TotalSale += $row['Quantity'];
					$TotalAmount +=$row['BillingAmount'];
					}
					
			
			$commmissionarray['TotalSale']=$TotalSale ;
			$commmissionarray['TotalAmount']=$TotalAmount;
			
			return $commmissionarray;
			
					
}*/


/*-------------------End-------------*/  
   // $memberId=3774;
        $date=date('Y-m-d');
        $fdate = date('Y-m-d', strtotime($date.'last saturday'));
		$SixDAgo = strtotime ( '-6 day' , strtotime ( $fdate ) ) ;				
		$tdate = date( 'Y-m-d' , $SixDAgo );
		
	
		
  /*$fetch_member = mysql_query("SELECT m1.*,g.GroupID FROM `Member` as m1 INNER JOIN  Group_Members as g ON  m1.ID=g.MemberID
	  And date(m1.Created) >='".date("Y-m-d",strtotime($tdate))."' AND date(m1.Created) <='".date("Y-m-d",strtotime($fdate))."' AND  GroupID IN(4,22,21) Group By (m1.ID)");*/
	  
	   echo "SELECT m1.*,o.Quantity,o.BillingAmount,o.CoachId,o.MemberID FROM `Member` as m1 INNER JOIN 
	  OrderItem as o ON  m1.ID=o.MemberID And date(o.Created) >='".date("Y-m-d",strtotime($tdate))."' AND date(o.Created) <='".date("Y-m-d",strtotime($fdate))."'";
	  
	  
	 $fetch_member = mysql_query("SELECT m1.*,o.Quantity,o.BillingAmount,o.CoachId,o.MemberID,o.ID as Oid  FROM `Member` as m1 INNER JOIN 
	  OrderItem as o ON  m1.ID=o.MemberID And date(o.Created) >='".date("Y-m-d",strtotime($tdate))."' AND date(o.Created) <='".date("Y-m-d",strtotime($fdate))."' AND o.Status='Paid'");
	  
  $no_of_reords = mysql_num_rows($fetch_member); 
	 	 
     /*echo "<pre/>";
	 print_r($MemberRecords);
	 exit;*/
	 
	 $i=0;
  if($no_of_reords>0){
	//Code For Coaching Commission  ////////////////////////////////////////////////////////////////////////////////
	while($MemberRecords = mysql_fetch_assoc($fetch_member)){
			 $TotalCochingCommission=0;
			/* if($i<=1){*/
			 $Coachesarray[$MemberRecords['CoachId']]['BillingAmount']+=$MemberRecords['BillingAmount'];
			 $Coachesarray[$MemberRecords['CoachId']]['Quantity']+=$MemberRecords['Quantity'];
			 $Coachesarray[$MemberRecords['CoachId']]['MemberID_'.$i]=$MemberRecords['MemberID'];
			 $Coachesarray[$MemberRecords['CoachId']]['joindate_'.$i]=$MemberRecords['Created'];
			
			/* }*/
			 $i++;
    }
	
 }

	/*echo "<pre/>";
	print_r($Coachesarray);
    exit;*/
	
$i=0;
if(count($Coachesarray )>0){
foreach($Coachesarray as $key=>$val){
        $CoachID=$key;
    
			 if($CoachID!="0" || $CoachID!="" )
			 {
			 
             $strWhere='ReferenceCoachID='.$CoachID.'';
			 
			 $CommissionRecord=$utilObj->getSingleRow('CommissionLevel' , $strWhere); //getting Commission Level For Coach
	 
		 if($CommissionRecord['CommissionLevelName']!=''){
			 
	         $commissionProgId=$CommissionRecord['CommissionLevelName'];
			 
			 $strWhere='CommissionProgramID='.$commissionProgId.'';
			 
			 $ComLevSaleRecord=$utilObj->getMultipleRow('CommissionLevelSale' , $strWhere);
			 
			//echo "<pre/>";
			//print_r($ComLevSaleRecord);
			 
             //getting Commission sale for Commission Program
			
			 
           if(count($ComLevSaleRecord)>0){
			 $l=1;
			 foreach($ComLevSaleRecord as $CommisssionVal)
			 {
				   $TimeOfComm=$CommisssionVal['CommissionBasedOn'];
					
				   //$commmissionarray=gettime($date,$TimeOfComm,$CoachID);
				   
			        $commmissionarray['TotalAmount']=$val['BillingAmount'];
					
				    $commmissionarray['TotalSale']=$val['Quantity'];
					
					
				
					if($CommisssionVal['Criteria1']!=="")
					{
						 $cond="return ".$commmissionarray['TotalSale']." ".$CommisssionVal['Criteria1'].";";
					}
					
					if($CommisssionVal['Criteria2']!=="")
					{
						$cond2="return ".$commmissionarray['TotalSale']."".$CommisssionVal['Criteria2'].";";
						
					}
				//echo "".$cond."<br/>";
				
				//echo "".$cond && $cond2."<br/>";
				
		           if(eval($cond)){
				    $CoachCommissionPercentage=$CommisssionVal['Sale'];
				   }/*else{
				     echo "not applicable";
				   }
				   */
				   if(eval($cond) && eval($cond2)){
				    $CoachCommissionPercentage=$CommisssionVal['Sale'];
				   }/*else{
				     echo "not applicable";
				   }*/
				   
		
					
                  }
				 // echo $commmissionarray['TotalAmount'];
				  
				  $TotalCochingCommission=($commmissionarray['TotalAmount']*$CoachCommissionPercentage)/100;
		    }
		
		  //Genrate new  commission array with value
		    $CommArray[$CoachID]['Commission']=$TotalCochingCommission;
			$CommArray[$CoachID]['CommissionProgId']=$commissionProgId;
			$CommArray[$CoachID]['TotalSale']=$commmissionarray['TotalSale'];
		    $CommArray[$CoachID]['TotalSaleAmt']= $commmissionarray['TotalAmount'];
		    $CommArray[$CoachID]['Status']='Unpaid';
			$CommArray[$CoachID]['ToDate']=$tdate;
			$CommArray[$CoachID]['FrmDate']=$fdate;
			
			//echo "Coaching Commission :".$CoachID."==".$TotalCochingCommission.'<br/>';
   	      }
	   }
	     $i++;;
	  }
}

/*------------insert commission to commission detail table-----------------*/

foreach($CommArray as $key=>$val){

$arrValue2=array('SponsorID'=>$key,'Comission'=>$val['Commission'],'Type'=>'CochingCommission','TotalSale'=>$val['TotalSale'],'TotalSaleAmt'=>$val['TotalSaleAmt'],'CommissionProgramID'=>$val['CommissionProgId'],'PaymentStatus'=>$val['Status'],'Created'=>$val['ToDate'],'LastEdited'=>$val['FrmDate']);

//$insertedId=$utilObj->insertRecord('CommissionDetail', $arrValue2);

}

//echo "<pre/>";
//print_r($arrValue2);

/*if(2>1 && 2>3){
echo 'if';
}else
 echo 'else';*/
	//echo $i;
?>
			 