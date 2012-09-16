<?php
define('INSIDE', true);
$ugamela_root_path = './../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
$theuser = explode(" ",$_COOKIE[$game_config['COOKIE_NAME']]);
($userId = $theuser[1])or die('Hacking attempt');
global $game_config;
includeLang('map_games');
$MAX_X=$game_config['max_x'];
$MAX_Y=$game_config['max_y'];
$x=intval($_GET['x']);
$y=intval($_GET['y']);
if($x>$MAX_X) $x= -($MAX_X*2+1)+$x;	
else if($x<-$MAX_X) $x= ($MAX_X*2+1)+$x;	
if($y>$MAX_Y) $y= -($MAX_Y*2+1)+$y;	
else if($y<-$MAX_Y) $y= ($MAX_Y*2+1)+$y;
$v_id = ($x+($MAX_X+6))*($MAX_X*2+13)+($y+($MAX_Y+7));

$sql="SELECT `name`,`workers`,wg_users.username,
	(SELECT wg_allies.name FROM wg_allies WHERE id =wg_users.alliance_id) as name_aly 
	FROM `wg_villages` INNER JOIN wg_users ON wg_users.id=wg_villages.user_id
	 WHERE wg_villages.`id`=".$v_id;
$db->setQuery($sql);
$userInfo=NULL;
$db->loadObject($userInfo); 
if($userInfo)
{
	$lang['username'] = $userInfo->username;
	$lang['ally_value'] = $userInfo->name_aly;
	$lang['pop_value'] = $userInfo->workers;
	$lang['details']=$userInfo->name;
	$page = parsetemplate(gettemplate('map_tooltip'), $lang);	
	echo $page;
}
?>
