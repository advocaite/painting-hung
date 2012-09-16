<?php
ob_start();
define('INSIDE', true);
date_default_timezone_set("Asia/Saigon");
$ugamela_root_path = './../';
$image_path="./images/village/";
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/constants.php');
if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']!=5){ header("Location: login.php"); }

$sql="SELECT
			wg_attack_troop.*
		FROM
			wg_attack_troop ,
			wg_attack ,
			wg_status
		WHERE
			wg_attack_troop.hero_id !=  '0' AND
			wg_attack_troop.die_num =  '0' AND
			wg_attack_troop.`status` =  '0' AND
			wg_attack_troop.attack_id =  wg_attack.id AND
			wg_attack.id =  wg_status.object_id AND
			wg_attack.`status` =  '0' AND
			wg_status.`status` =  '0'
		GROUP BY
			wg_attack_troop.id";
$db->setQuery($sql);
$attackTroopList=$db->loadObjectList();
if($attackTroopList){
	echo "<pre>"; print_r($attackTroopList); 
	foreach($attackTroopList as $attackTroop){
		$sql="INSERT INTO wg_attack_hero (wg_attack_hero.hero_id, wg_attack_hero.num, wg_attack_hero.die_num, wg_attack_hero.attack_id, wg_attack_hero.`status`) VALUES ($attackTroop->hero_id, 1, 0, $attackTroop->attack_id, 0)";
		$db->setQuery($sql);
		$db->query();
	}
}

$sql="SELECT
			wg_attack.*,
			wg_status.id AS status_id 
		FROM
			wg_status ,
			wg_attack
		WHERE
			wg_status.object_id =  wg_attack.id AND
			wg_status.`status` =  '2' AND 
			wg_attack.`type` = '2' AND
			wg_attack.`status` =  '0' AND 
			wg_status.`type` =  '7'   
		GROUP BY
			wg_attack.id";
$db->setQuery($sql);
$attackList=$db->loadObjectList();
if($attackList){
	echo "<pre>"; print_r($attackList);
	foreach($attackList as $attack){
		$sql="UPDATE wg_attack SET wg_attack.type=1 WHERE id=$attack->id";
		$db->setQuery($sql);
		$db->query();
		
		$sql="UPDATE wg_status SET wg_status.`status` =  '1' WHERE id=$attack->status_id";
		$db->setQuery($sql);
		$db->query();
	}
}
?>