<?php 
//include dirname(__FILE__)."/lib/include.php";
switch($_REQUEST['action']){
	case "Login":
		$objusers = new Users();
		$User = $objusers->ValidateUser($_REQUEST['user'],$_REQUEST['password']);      
            if($User['ID'] > 0){
                setcookie("MemberLogedIn", $User['Email']);
                $_SESSION['Member'] = $User;
				$url = "Location:Home.php";
                header($url);				
		}else{
                    header("Location:".SITE_ADDRESS."Login.php?error=1");
		}
	break;
}
?>