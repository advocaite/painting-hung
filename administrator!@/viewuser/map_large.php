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
	includeLang('map_games');
	$parse = $lang;
	if($_GET['a']){
		$parse['mapx'] = $_GET['a'];
		$parse['mapy'] = $_GET['b']; 
		$max_x = loadConfig('max_x');
		$max_y = loadConfig('max_y');
		if($parse['mapx']>$max_x) $parse['mapx'] = $max_x;
		else if($parse['mapx']<-$max_x) $parse['mapx'] = -$max_x;
		if($parse['mapy']>$max_y) $parse['mapy'] = $max_y;
		else if($parse['mapy']<-$max_y) $parse['mapy'] = -$max_y;
	}else{
		$vila_id = $_COOKIE['villa_id_cookie'];
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
?>