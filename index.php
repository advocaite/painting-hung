<?php
//phpinfo();
ob_start();

define('INSIDE', true);
$ugamela_root_path='./';
//include($ugamela_root_path . 'mobileuseragent/detect_mobile_client.' . $phpEx);
//header ("Location:login.php");
include ($ugamela_root_path . 'bootstrap.php');
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include ($ugamela_root_path . 'includes/common.' . $phpEx);
global $lang, $user;

if (check_user())
    {
    header ("Location: village1.php");
    }

includeLang ('home');
$parse=$lang;
$players=(int)$db->count('from wg_users','id');
$parse['players']=$players;
$active=(int)$db->count('from wg_users where actived=1');
$parse['active']=$active;
$online         =(int)$db->count('from wg_sessions','username');
$parse['online']=$online;
$page           =parsetemplate(gettemplate('body'), $parse);
display1($page, $lang['title']);
ob_end_flush();
?>
