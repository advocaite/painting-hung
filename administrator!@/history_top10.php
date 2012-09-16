<?php
/*
	Plugin Name: rank.php
	Plugin URI: http://asuwa.net/administrator/rank.php
	Description: 
	+ thuc hien cap nhat rank
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.php');
include('includes/common.php'); 

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']!=5 && $user['authlevel']!=4 && $user['authlevel']!=3){ header("Location: login.php"); }
includeLang('status');
$parse = $lang;

$parse['msg_info']="Hãy tạo history top10 vào tối chủ nhật hàng tuần lúc 23h 58 phút.";
if($_POST['cmd']){
	$week_nd=date("W");
	$msg_info = "";
	$attack_point=$defend_point=$resource=true;
	
	if(!historyTop10("attack_point", $week_nd)){
		$msg_info.= "<p>Chưa có top 10 attack_point</p>";
		$attack_point=false;
	}
	if(!historyTop10("defend_point", $week_nd)){
		$msg_info.= "<p>Chưa có top 10 defend_point</p>";
		$defend_point=false;
	}
	if(!historyTop10("resource", $week_nd)){
		$msg_info.= "<p>Chưa có top 10 resource</p>";
		$resource=false;
	}
	if($attack_point&&$defend_point&&$resource){
		$parse['msg_info'] = "Tạo historyTop10 thành công!";
	}else{
		$parse['msg_info'] = $msg_info;
	}
}

$page = parsetemplate(gettemplate('/admin/history_top10'), $parse);
displayAdmin($page);	



?>