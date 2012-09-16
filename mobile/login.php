<?php
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_plus.php');
include($ugamela_root_path . 'includes/function_profile.php');
include($ugamela_root_path . 'includes/function_xml.php');
include_once ($ugamela_root_path . 'includes/usersOnline.class.php');
include_once ($ugamela_root_path . 'includes/func_build.php');
include_once($ugamela_root_path . 'soap/call.'.$phpEx);
Time_Wait(time());
checkRequestTime();
if(check_user()){ header("Location: village1.php"); }
includeLang('login');
includeLang('lang_Wap');
global $db,$game_config;
$parse=$lang;
$parse['active']='';
$parse['failuser']='';
if(isset($_GET))
{ 
	if(empty($_REQUEST["username"]) || empty($_REQUEST["password"]) )
	{
		$parse['error1']=$lang['error1'];
		$parse['error2']=$lang['error2'];
		$parse['username']='';
		$parse['password']='';
		if(!empty($_REQUEST["username"]))
		{
			$parse['username']=$_REQUEST["username"];
			$parse['error1']='';
		}
		if(!empty($_REQUEST["password"]))
		{
			$parse['password']=$_REQUEST["password"];
			$parse['error2']='';
		}
		$parse['active']='';
		$parse['error3']='';
		$page = parsetemplate(gettemplate('login_body'), $parse);
		display1($page,$lang['Login']);
	}
	else
	{
		if (!get_magic_quotes_gpc())
		{
			$username=str_replace('$', '\$',addslashes($_REQUEST["username"]));
			$password=str_replace('$', '\$',addslashes($_REQUEST["password"]));
		}
		else
		{
			$username=str_replace('$', '\$',$_REQUEST["username"]);
			$password=str_replace('$', '\$',$_REQUEST["password"]);
		}
		$username=$db->getEscaped($username);	
		$password=$db->getEscaped($password);
		$password_md5 = md5($password);
		$login=false;
		if(login_remote($username, $password_md5)) // dang nhap thanh cong
		{	
			$sql = "SELECT id,username,villages_id,alliance_id,actived FROM wg_users WHERE username='$username'";
			$db->setQuery($sql);
			$wg_users =NULL;
			$db->loadObject($wg_users);			
			if($wg_users && $wg_users->actived==1)
			{	
				$login=true;				
			}
			else // chua kich hoat
			{
				$parse['active']=$lang['not active'];
				//header("Location:active_user.php");
				//exit();
			}
		}
		else  // tai khoan xai chung
		{
			$accountSister=getAccountSisterInfo($username);
			if($accountSister)
			{
				if(login_remote($accountSister->sister1,$password))
				{
					$sql = "SELECT id,username,villages_id,alliance_id FROM wg_users WHERE username='$username'";
					$db->setQuery($sql);
					$wg_users =NULL;
					$db->loadObject($wg_users);			
					if($wg_users)
					{
						$login=true;
					}
				}
			}			
		}
		if($login)
		{
			$expiretime = 0;$rememberme = 0;
			@include('config.php');
			$password_new=md5($password."--" .$dbsettings["secretword"]);
			$cookie = $wg_users->id . " " . $wg_users->username . " " .$password_new. " " . $rememberme;
			setcookie($game_config['COOKIE_NAME'], $cookie, $expiretime, "/", "", 0);
			$_SESSION['villa_id_cookie']=$wg_users->villages_id;
			$_SESSION['password']=$password_new;
			$_SESSION['alliance_id']=$wg_users->alliance_id;
			$_SESSION['username'] = $wg_users->username;
			$_SESSION['userid'] = $wg_users->id;
			$_SESSION['time_check_online'] = time()-1000;
			$_SESSION['last_login'] = time();
			$numUserOnline = new usersOnline();
			executeXML($numUserOnline->count_users());	
			returnWorkersLogin($wg_users->id);
			/* -> chi su dung khi co ky dai level >90 tro len */			
			//checkWorldFinished();				
						
			if(!insertFeature($_REQUEST['username'],"Login"))
			{
				globalError('Error!!!Login');
			}
			unset($dbsettings);	
			updateAllPlus();
			header("Location:list_villages.php"); 
			exit();
		}
		else
		{
			$parse['error1']='';
			$parse['error2']='';
			$parse['failuser']=$lang['no info'];
			$parse['username']='';
			$parse['password']='';
			$parse['active']='';	
			$parse['code']=$_SESSION['security_login']=md5(time());
			$page = parsetemplate(gettemplate('login_body'), $parse);
			display1($page,$lang['title']);
		}
	}
}
else
{	
	$parse['active']='';
	$parse['password']='';	
	$parse['username']='';
	$parse['error1']='';
	$parse['error2']='';
	$parse['error3']='';
	$parse['code']=$_SESSION['security_login']=md5(time());
	$page = parsetemplate(gettemplate('login_body'), $parse);
	display1($page,$lang['title']);
}
ob_end_flush();
?>
