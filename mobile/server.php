<?php
/*
	Plugin Name: server.php
	Plugin URI: http://asuwa.net/server.php
	Description: 
	+ Hien thi thong tin server
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.php'); 
include($ugamela_root_path . 'includes/common.'.$phpEx);

global $db;
//START: tong so user
$sql = "SELECT COUNT(DISTINCT(id)) FROM wg_users";
$db->setQuery($sql);
$numUsers = null;
$numUsers = $db->loadResult();
//END: tong so user

//START: so user active
$sql = "SELECT COUNT(DISTINCT(id)) FROM wg_users WHERE population >= 2";
$db->setQuery($sql);
$numActive = null;
$numActive = $db->loadResult();
//END: so user active

//START: so user dang ky theo vung
$zone=array();
for($i=1; $i<=5; $i++){
	$sql = "SELECT COUNT(DISTINCT(id)) FROM wg_users WHERE position=$i";
	$db->setQuery($sql);	
	$zone[$i] = $db->loadResult();
}
//END: so user dang ky theo vung

//START: so user dang ky theo chung toc
$nation=array();
for($i=1; $i<=3; $i++){
	$sql = "SELECT COUNT(DISTINCT(id)) FROM wg_users WHERE nation_id=$i";
	$db->setQuery($sql);	
	$nation[$i] = $db->loadResult();
}
//END: so user dang ky theo chung toc

//START: so user online
$sql = "SELECT COUNT(DISTINCT(username)) FROM wg_sessions WHERE username != ''";
$db->setQuery($sql);
$numOnline = null;
$numOnline = $db->loadResult();
//END: so user online
/*body { background-color:#EFE7A5}  transparent*/

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
echo "<style type=\"text/css\">
  
   body { background-color:transparent}
</style>";
echo "<span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px\">Tổng số gamer: ". ($numUsers-1) ."</span>";
echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px\">Số người online ".$numOnline."</span>";
echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px\">Số người active: ".$numActive."</span>";

echo "<br/><br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px\">Số người chơi theo vùng: </span>";
for($i=1; $i<=5; $i++){
	switch($i){
	case 1:
		echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px\">Trung tâm: ".$zone[$i]."</span>";
		break;
	case 2:
		echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px\">Đông: ".$zone[$i]."</span>";
		break;
	case 3:
		echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px\">Tây: ".$zone[$i]."</span>";
		break;
	case 4:
		echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px\">Nam: ".$zone[$i]."</span>";
		break;
	case 5:
		echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px\">Bắc: ".$zone[$i]."</span>";
		break;
	default:
		break;
	}
}


echo "<br/><br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px\">Số người chơi theo chủng tộc: </span>";
for($i=1; $i<=3; $i++){
	switch($i){
	case 1:
		echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px\">Arabia: ".$nation[$i]."</span>";
		break;
	case 2:
		echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px\">Mongo: ".$nation[$i]."</span>";
		break;
	case 3:
		echo "<br/><span style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px\">Sunda: ".$nation[$i]."</span>";
		break;
	default:
		break;
	}
}

ob_end_flush();
?>