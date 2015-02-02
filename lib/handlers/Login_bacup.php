<?php 
//include dirname(__FILE__)."/lib/include.php";
switch($_REQUEST['action']){
	case"Login":
		$objMember = new Member();
                $ObjGroup = new Groups();
		
		$Member=$objMember->ValidateMember($_REQUEST['user'],$_REQUEST['password']);
                //echo"<pre>";
		//print_r($Member);
                 //echo"</pre>";
               // echo $memberid=$Member[0][ID];
                        
            if($Member['ID'] > 0 && $Member['HasDeleted'] == 0){
                //echo $memberid=$Member['ID'];
                        $Group_array = $objMember->GetAllMemberWithGroup(" Member.ID ='".$Member['ID']."'",array("*"));
		
                        //print_r($Group_array);
                         $_SESSION['member_group'] =$Group_array[0]['GroupID'];
                      
		
                        $_SESSION['mblgn']=$_REQUEST['loginhid'];
                         //print_r($_SESSION['mblgn']);
		     
			 
			// find the member group permissions
			$permissions=$objMember->GetMemberPermissions($Member['ID']); 
                        
			$_SESSION['Member'] = $Member;
                         //echo"<pre>";
                        //print_r($_SESSION['Member']);
                         //echo"</pre>";
			$_SESSION['Permissions'] = $permissions;
			
			setcookie("MemberLogedIn", $Member['Email']);
						
			
						
                         header("Location:".SITE_ADDRESS."Home.php");
                       
						
		}else{
                    header("Location:".SITE_ADDRESS."Login.php?error=1");
		}
	break;
	
	
}
?>