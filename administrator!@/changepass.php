<?php
define('INSIDE', true);
ob_start();
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include('includes/function_paging.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('password_admin');
	global $db,$lang;
	$parse=$lang;
	$parse['msg']='';	
	if(isset($_POST['update']))
	{
		$password=md5($_POST['pass_old']."--".$dbsettings["secretword"]);	
		$parse['msg']=$lang['error1'];				
		if($_SESSION['password']==$password)
		{
			$parse['msg']=$lang['error2'];	
			if($_POST['pass_new']!='')
			{
				$sql="UPDATE wg_admin SET pass='".md5($_POST['pass_new'])."' WHERE username='".$_SESSION['username_admin']."'";
				$db->setQuery($sql);	
				$db->query();
				$parse['msg']=$lang['update_success'];
			}	
		}		
	}	
	$page = parsetemplate(gettemplate('admin/changepass'), $parse);
	displayAdmin($page,'');
	ob_end_flush();
}
?>
