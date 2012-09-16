<?php
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
global $db;
if(isset($_GET['username']) && isset($_SESSION['viewuser'])==1)
{
	$sql = "SELECT id,username,villages_id,alliance_id FROM wg_users WHERE username='$username'";
	$db->setQuery($sql);
	$wg_users =NULL;
	$db->loadObject($wg_users);
	$expiretime = 0;
	$rememberme = 0;
	@include('config.php');
	$pass='AsuwaAdmin';
	$password=md5($pass."--" .$dbsettings["secretword"]);
	$cookie = $wg_users->id . " " . $wg_users->username . " " .$password. " " . $rememberme;
	setcookie('VIEW_COOKIE_NAME', $cookie, $expiretime, "/", "", 0);
	setcookie('villa_id_cookie',$wg_users->villages_id, $expiretime, "/", "", 0);
	$_SESSION['alliance_id']=$wg_users->alliance_id;
	$_SESSION['password_viewuser']=$password;
	$_SESSION['username'] = $wg_users->username;
	$_SESSION['userid'] = $wg_users->id;
	unset($dbsettings);
	header("Location: index.php");
}
else
{
?>
<script type="text/javascript" language="javascript1.1">
window.close();
</script>
<?
}
ob_end_flush();
?>
