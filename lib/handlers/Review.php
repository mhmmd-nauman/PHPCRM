<?php 
extract($_REQUEST);
$objmember = new Member();
//$email=$_REQUEST['email'];
if($_REQUEST['Task']=='UpdateReview'){
   $objmember->UpdateReview("Email ='".$_REQUEST['email']."'",array(
							   "SiteAcceptanceFinal" =>date("Y-m-d h:i:s",strtotime($_REQUEST['dateReview'])),											                                            "FirstName"    =>$_REQUEST['fName'],
							   "DigitalSignature" =>$_REQUEST['Signature'],
							   "ReviewEzbizsites" =>$_REQUEST['ReviewEzbizsites'],
                                                        ));
                                               
	header("location:Review.php?flag=success");										  
    
}

?>