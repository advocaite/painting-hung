<?php  //reg.php :: Registro v1.0 beta build.3

define('INSIDE', true);
$ugamela_root_path = './../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
$theuser = explode(" ",$_COOKIE[$game_config['COOKIE_NAME']]);
($userId = $theuser[1])or die('hack');
includeLang('map_games');
$MAX_X=$game_config['max_x'];
$MAX_Y=$game_config['max_y'];
$_POST['x'];
$_POST['y'];
if($x>$MAX_X) $x= -($MAX_X*2+1)+$x;	
else if($x<-$MAX_X) $x= ($MAX_X*2+1)+$x;	
if($y>$MAX_Y) $y= -($MAX_Y*2+1)+$y;	
else if($y<-$MAX_Y) $y= ($MAX_Y*2+1)+$y;
//$v_id = ($x+400)*801+($y+401);
$v_id = ($x+($MAX_X+6))*($MAX_X*2+13)+($y+($MAX_Y+7));
$sql = "SELECT v.user_id, v.name, v.workers FROM wg_villages as v WHERE v.id=$v_id";//die($sql);
$db->setQuery($sql);
$vInfo=null;
$db->loadObject($vInfo);
if($vInfo->user_id){
	$sql = "SELECT username,id FROM wg_users as u WHERE (id=$vInfo->user_id)";
	$db->setQuery($sql);
	$userInfo=null;
	$db->loadObject($userInfo);
	$lang['username'] = $userInfo->username;
	$sql = "SELECT name FROM wg_allies as a, wg_ally_members as m WHERE (m.user_id=$userInfo->id) and (m.right_=1) and (m.ally_id=a.id)";
	$db->setQuery($sql);
	$allyInfo=null;
	$db->loadObject($allyInfo);
	$lang['ally_value'] = $allyInfo->name;
	$lang['pop_value'] = $vInfo->workers;
	$lang['details']=$vInfo->name;
	$page = parsetemplate(gettemplate('map_tooltip'), $lang);	
	echo $page;
}
?>