<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.php'); 
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/func_security.php');
require_once($ugamela_root_path . 'includes/function_resource.php');
checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
doAnounment();
global $user,$wg_village,$wg_buildings,$timeAgain,$game_config;
$timeAgain=$user['amount_time']-(time()-$_SESSION['last_login']);
$_SESSION['url']='map.php';
if(isset($_GET['vid']) && is_numeric($_GET['vid']))
{
	$get_id=$_GET['vid'];
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE id=".$get_id." AND user_id=".$user["id"]." LIMIT 1";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	if($count==1)
	{
		$_SESSION['villa_id_cookie']=$get_id;
		$village=$get_id;
	}else{
		$village=$_SESSION['villa_id_cookie'];
	}
}else{
	if(isset($_SESSION['villa_id_cookie']))
	{
		$village=$_SESSION['villa_id_cookie'];
	}else{
		$village=$user['villages_id'];
	}
}

$wg_village=$wg_buildings=NULL;
$wg_village=getVillage($village);
$wg_buildings=getBuildings($village);
getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());

includeLang('map_games');
$parse = $lang;
$max_x = $game_config['max_x'];
$max_y = $game_config['max_y'];
$parse['max_x'] = $max_x;
$parse['max_y'] = $max_y;
if(isset($_GET['a']) && is_numeric($_GET['a']) && isset($_GET['b']) && is_numeric($_GET['b']))
{
	$parse['mapx'] = $_GET['a'];
	$parse['mapy'] = $_GET['b']; 
	if($parse['mapx']>$max_x) $parse['mapx'] = $max_x;
	else if($parse['mapx']<-$max_x) $parse['mapx'] = -$max_x;
	if($parse['mapy']>$max_y) $parse['mapy'] = $max_y;
	else if($parse['mapy']<-$max_y) $parse['mapy'] = -$max_y;
}
else
{
	$sql = "SELECT x,y FROM wg_villages WHERE id = $village ";
	$db->setQuery($sql);
	$villa = null;
	$db->loadObject($villa);
	if($villa){
		$parse['mapx'] = $villa->x;
		$parse['mapy'] = $villa->y; 
	}else{
		$parse['mapx'] = 0;
		$parse['mapy'] = 0; 
	}
}
$parse['plus']='';
$sql = "SELECT COUNT(map_large) FROM `wg_plus` WHERE user_id = ".$user["id"]." AND map_large>now()";
$db->setQuery($sql);
$count = (int)$db->loadResult();
if($count>0){
	$parse['plus']="<a href=\"map.php\" target=\"_blank\" onclick=\"return popLarge();\"><img class=\"map_link_to_xxlmap\" src=\"images/un/m/max.gif\" onmouseover=\"ddrivetip('".$lang['large']."');\" onmouseout=\"hideddrivetip()\"></a>";
}
$page = parsetemplate(gettemplate('map_body'), $parse);
display($page);

function loadConfig($name){
	global $db;
	$sql="SELECT value FROM wg_game_configs WHERE name = '$name'";
	$db->setQuery($sql);
	$n= $db->loadResult();
	return $n;
}
?>