<?php 
/*
	Plugin Name: plus_config.php
	Plugin URI: http://asuwa.net/administrator/plus_config.php
	Description: dung cho viec update config of plus	
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel'] <5)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('plus');
	global $db;
	$parse = $lang;
	//START: danh sach plus
	$sql = "SELECT * FROM wg_config_plus";
	$db->setQuery($sql);
	$plusConfig = null;
	$plusConfig = $db->loadObjectList();
	$no = 1;
	foreach($plusConfig as $row){
		$parse['no'] = $no;
		$parse['name'] = $row->name;
		$parse['duration'] = $row->duration;
		$parse['asu'] = $row->asu;
		$listConfig .=parsetemplate(gettemplate('/admin/plus_config_row'),$parse);
		$no ++;
	}
	$parse['list_plus_config'] = $listConfig;
	//END: danh sach plus
	
	//START: edit
	if(isset($_GET['s'])){
		$task = $_GET['s'];
	}
	switch ($task) {
		case "1":
			$nameEdit = $_GET['name'];
			$sql = "SELECT * FROM wg_config_plus WHERE name = '".$nameEdit."'";
			$db->setQuery($sql);
			$plusRow = null;
			$db->loadObject($plusRow);
			$parse['name_edit'] = $nameEdit;
			$parse['duration_edit'] = $plusRow->duration;
			$parse['asu_edit'] = $plusRow->asu;
			if($_POST['update']){
				$sql = "UPDATE wg_config_plus SET duration = ".$_POST['duration_edit'].", 
							 asu = ".$_POST['asu_edit']." WHERE name = '".$nameEdit."'";
				$db->setQuery($sql);
				$db->query();
				header("Location: plus_config.php");
			}				
			$page = parsetemplate(gettemplate('/admin/plus_config_edit'), $parse);
			displayAdmin($page,$lang['plus']);		
		break;	
	}
	
	//END: edit
	$page = parsetemplate(gettemplate('/admin/plus_config'), $parse);
	displayAdmin($page,$lang['plus']);
}
?>