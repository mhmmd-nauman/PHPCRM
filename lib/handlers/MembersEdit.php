<?php

extract($_REQUEST);
$objmember = new Member();

if($_REQUEST['Task']=='RUpdateAjaxRevision'){
    
$objmember->UpdateRevision("ID = '".$_REQUEST['rid']."'",array(
								"RevisionNote" =>$_REQUEST['RevisionNote'],	
								"ByID"    =>$_SESSION['Member']['ID'],			
							));
											
//echo "Done!";
if($_REQUEST['kid']==6)
{
header("Location:RevisionNotes.php?id=".$_REQUEST['kid']);
exit;
}
elseif($_REQUEST['kid']==7)
{
header("Location:HubFlxMembersEdit.php?id=".$_REQUEST['id']);
exit;
}
else{
header("Location:MembersEdit.php?id=".$_REQUEST['id']);
exit;
}
}
if($_REQUEST['Task']=='del'){
//echo "kkkkk";
    $objmember->UpdateRevision("ID = '".$_REQUEST['rid']."'",array("HasDeleted"=>"1"));
	if($_REQUEST['kid']==6)
	{
	header("Location:RevisionNotes.php?id=".$_REQUEST['kid']);
	exit;
	}
	else
	{
    header("Location:MembersEdit.php?id=".$_REQUEST['id']);
    exit;
	}
}

if($_REQUEST['Task']=='AjaxSaveNauman'){

$objSystemSettings = new SystemSetting();
$SystemSettingsArray = $objSystemSettings->GetAllSystemSetting("HasDeleted = 0 ORDER BY ID DESC LIMIT 0,1",array("*"));
//print_r("javed");
$Members_array = $objmember->GetAllMember(" ID = '".$_REQUEST['id']."'",array("*"));

$subject = "EzbManger - New Revision for ".$Members_array[0]['CompanyName']." - ".$Members_array[0]['FirstName']." ".$Members_array[0]['Surname'];
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=utf8\r\n";
    // More headers
$bcc = 'mhmmd.nauman@gmail.com';
$cc = '';
if($cc!=""){$headers .= 'CC: '. $cc . "\r\n";}
if($bcc!=""){$headers .= 'Bcc: '. $bcc . "\r\n";} 

$From=$SystemSettingsArray[0]['Email'];
$headers .= 'From: EzbManager<'.$From.'>';
$body =$_REQUEST['RevisionNote']."  <br>"."<br>Sincerely,<br>EZB Manager.<br><a href='http://mos2581.info'>http://mos2581.info</a>";
$to = $SystemSettingsArray[0]['EmailTo'];

 
 $added= $objmember->InsertRevision(array(
					    "CurrentDate"  =>date("Y-m-d h:i:s",time()),
                        "RevisionNote" =>$_REQUEST['RevisionNote'],
					    "MemberID" =>$_REQUEST['id'],
					    "ByID"    =>$_SESSION['Member']['ID'],
					    "Done"    =>0,
											
                                          ));

if(mail($to, $subject, $body, $headers, $From)){
    //echo "email sent";
}else{
    //echo "email failed";
}

exit;
}

?>