<?php

 $fetch_member = mysql_query("SELECT * FROM `Member` WHERE ID = '".$memberId."'");
	     $MemberRecords = mysql_fetch_assoc($fetch_member); 

	     $Strwhere='ID ='.$ProductId.'';
	     $ProductRecords=$utilObj->getSingleRow('Product',$Strwhere);
	   
	    $Strwhere='ProductID ='.$ProductId.'';
	    $ProdSubRecords=$utilObj->getSingleRow('ProductSubscription',$Strwhere);
		 $amt_val=$ProductRecords['ProductPrice'];
		
	         $SponsorID=$MemberRecords['SponsorAppId'];
			 
			 $strWhere='SponsorID='.$SponsorID.'';
			 
			 $ProductId=$ProductRecords['ID'];
			$SubRecords=$utilObj->getSingleRow("ProductSubscription", "ProductID='".$ProductId."' ");
			 if($SubRecords>0)
			 {
			 	$strWhere='ProductName='.$ProductId.' AND Type="Subscription" ';
			 }	
			 else{
			 /*--before subscription billdate start add sponsor commission for one time order-------*/
			 $strWhere='ProductName='.$ProductId.' AND Type="Product"';		 
			 }
			 $ComLevSaleRecord=$utilObj->getSingleRow('SponsorCommissionLevel' ,$strWhere); //getting Commission Level for Sponsor 
			 $CommissionType=$ComLevSaleRecord['CommissionnType'];
			 $SponsorRecord=$utilObj->getSingleRow('Member',"AppId='".$SponsorID."' " );
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
			 
			
			
			/*--------getting second commission level sponor data*/
			if($Level1Commission>0){
			   $arrcommission=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorID'=>$SponsorID,'OrderItemID'=>$OrderRecords['ID'],'Comission'=>$comissionlevelone,'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>'Order','Earned'=>$Level1Commission,'Sold'=>$Level1Commission);
			   $commissionId=$utilObj->insertRecord('CommissionDetail', $arrcommission);
             }
			 
			if($Level2Commission > 0){
		   $arrcommission=array('MemberID'=>$MemberRecords['ID'],'ProductID'=>$ProductRecords['ID'],'SponsorID'=>$SponsorIdLevel2,'OrderItemID'=>$OrderRecords['ID'],'Comission'=>$comissionleveltwo,'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'Type'=>'Order','Earned'=>$Level2Commission,'Sold'=>$Level2Commission);
		   
			  $commissionId=$utilObj->insertRecord('CommissionDetail', $arrcommission);
				
			  }
		  
			 
			 
			 
	         //echo "Level1 -".$Level1Commission;
			 
			// echo "Level2 -".$Level2Commission;
			 
			// die;
			 
		
			 
			 ///////////////////////////////////////////////////////////////////////////////////////////////////////
  ?>
