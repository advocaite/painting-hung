<?php
ob_start(); 
define('INSIDE', true);
header("Location:login.php");
/*$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
global $lang,$user;
if(check_user())
{
	header("Location: village1.php");
}
includeLang('home');
$parse=$lang;
$sql="SELECT count(DISTINCT(id)) FROM wg_users";
$db->setQuery($sql);
$players= (int)$db->loadResult();
$parse['players']=$players;
$sql="SELECT count(DISTINCT(id)) FROM wg_users WHERE actived=1";
$db->setQuery($sql);
$active= (int)$db->loadResult();
$parse['active']=$active;
$sql="SELECT count(DISTINCT username) FROM wg_sessions";
$db->setQuery($sql);
$online= (int)$db->loadResult();
$parse['online']=$online;
$page = parsetemplate(gettemplate('body'),$parse); 
display1($page,$lang['title']);*/
ob_end_flush();

?>

