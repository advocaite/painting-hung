<?php
/*
	Plugin Name: mission.php
	Plugin URI: http://asuwa.net/administrator/mission.php
	Description: 
	+ Hien thi danh sach mission
	+ thuc hien update roi ghi ra file
	Version: 1.0.0
	Author: 
	Author URI: 
*/

define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ('includes/db_connect.' . $phpEx);
include ('includes/common.' . $phpEx);

if(!check_user()){header("Location: login.php");}
if($user['authlevel'] <5)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang ('quest');
	$parse = $lang;
	global $db;
	define('MAXROW',50);
	
	//START: danh sach mission
	$sql = "SELECT * FROM wg_mission WHERE id>1 LIMIT 0,".MAXROW;
	$db->setQuery($sql);
	$mission = null;
	$mission = $db->loadObjectList();
	$no = 1;
	foreach($mission as $row){
		$parse['no'] = $no;
		$parse['name'] = $row->name;
		$parse['lumber'] = $row->rs1;
		$parse['clay'] = $row->rs2;
		$parse['iron'] = $row->rs3;
		$parse['crop'] = $row->rs4;	
		$parse['asu'] = $row->gold;	
		$parse['id'] = $row->id;
		$listMission .=parsetemplate(gettemplate('/admin/mission_row'),$parse);
		$no ++;
	}
	$parse['mission_list'] = $listMission;
	//END: danh sach mission
	
	//START: edit
	if(isset($_GET['s'])){
		$task = $_GET['s'];
	}
	switch ($task) {
		case "1":
			$missionId = $_GET['id'];
			$sql = "SELECT * FROM wg_mission WHERE id = ".$missionId;
			$db->setQuery($sql);
			$missionRow = null;
			$db->loadObject($missionRow);
			$parse['mission_number'] = $missionId-1;
			$parse['content'] =$lang['mission'.$missionId].'<hr>'.$lang['reward'.$missionId];
			$parse['lumber_edit'] = $missionRow->rs1;
			$parse['clay_edit'] = $missionRow->rs2;
			$parse['iron_edit'] = $missionRow->rs3;
			$parse['crop_edit'] = $missionRow->rs4;
			$parse['asu_edit'] = $missionRow->gold;
			if($_POST['update']){
				$sql = "UPDATE wg_mission SET rs1 = ".$_POST['lumber_edit'].", rs2 = ".$_POST['clay_edit'].", 
						rs3 = ".$_POST['iron_edit'].", rs4 = ".$_POST['crop_edit'].", gold = ".$_POST['asu_edit']." 
						WHERE id = ".$missionId;
				$db->setQuery($sql);
				$db->query();
				header("Location: mission.php");
			}				
			$page = parsetemplate(gettemplate('/admin/mission_edit'), $parse);
			displayAdmin($page,$lang['mission']);		
		break;	
	}
	
	//END: edit
	
	$page = parsetemplate(gettemplate('/admin/mission'), $parse);
	displayAdmin($page);
}
?>