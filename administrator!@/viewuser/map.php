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

if(!check_user()){ header("Location: login.php"); }
doAnounment();
global $user,$wg_village,$wg_buildings;
if(!empty($_GET['id']) && is_numeric($_GET['id']))
{
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE id=".$_GET['id']." AND user_id=".$user["id"]."";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	if($count==1)
	{
		setcookie('villa_id_cookie',$_GET['id'],time()+31536000, "/", "", 0);
		$village=$_GET['id'];
	}else{
		$village=$_COOKIE['villa_id_cookie'];
	}
}else{
	if(isset($_COOKIE['villa_id_cookie'])){
		$village=$_COOKIE['villa_id_cookie'];
	}else{
		$village=$user['villages_id'];
	}
}
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
	if($_GET['a']){
		$parse['mapx'] = $_GET['a'];
		$parse['mapy'] = $_GET['b']; 
		if($parse['mapx']>$max_x) $parse['mapx'] = $max_x;
		else if($parse['mapx']<-$max_x) $parse['mapx'] = -$max_x;
		if($parse['mapy']>$max_y) $parse['mapy'] = $max_y;
		else if($parse['mapy']<-$max_y) $parse['mapy'] = -$max_y;
	}else{
		// kiem tra tinh bao mat cho lang
		if(!empty($_GET['id']) && is_numeric($_GET['id']))
		{
			$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE id=".$_GET['id']." AND user_id=".$user["id"]." LIMIT 1";
			$db->setQuery($sql);
			$count = (int)$db->loadResult();
			if($count==1)
			{
				setcookie('villa_id_cookie',$_GET['id'],time()+31536000, "/", "", 0);
				$village=$_GET['id'];
			}else{
				$village=$_COOKIE['villa_id_cookie'];
			}
		}else{
			if(isset($_COOKIE['villa_id_cookie'])){
				$village=$_COOKIE['villa_id_cookie'];
			}else{
				$village=$user['villages_id'];
			}
		}
		//$village =$_COOKIE['villa_id_cookie'];
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
	$parse['plus']='<a href="map.php" target="_blank" onclick="return popLarge();"><img class="map_link_to_xxlmap" src="images/un/m/max.gif" alt="'.$lang['large'].'" title="'.$lang['large'].'"></a>';
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