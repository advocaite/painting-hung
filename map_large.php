<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.php'); 
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
global $db,$user;
$sql="SELECT map_large FROM wg_plus WHERE user_id=".$user['id'];
$db->setQuery($sql);
$second=strtotime($db->loadResult()) - time();

if($second <=0)
{
	echo '<script>window.close();</script>';		
}
else
{
	includeLang('map_games');
	$parse = $lang;
	$max_x = $game_config['max_x'];
	$max_y = $game_config['max_y'];
	$parse['max_x'] = $max_x;
	$parse['max_y'] = $max_y;
	if($_GET['a']){
		$parse['mapx'] = $_GET['a'];
		$parse['mapy'] = $_GET['b']; 
		if($parse['mapx']>$max_x) $parse['mapx'] = $max_x;
		else if($parse['mapx']<-$max_x) $parse['mapx'] = -$max_x;
		if($parse['mapy']>$max_y) $parse['mapy'] = $max_y;
		else if($parse['mapy']<-$max_y) $parse['mapy'] = -$max_y;
	}else{
		$vila_id =$db->getEscaped($_SESSION['villa_id_cookie']);
		$sql = "SELECT x,y FROM wg_villages WHERE id = $vila_id ";
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

	$page = parsetemplate(gettemplate('map_body_large'), $parse);
	display2($page);
	
	function loadConfig($name){
		global $db;
		$sql="SELECT value FROM wg_game_configs WHERE name = '$name'";
		$db->setQuery($sql);
		$n= $db->loadResult();
		return $n;
	}
}
?>
