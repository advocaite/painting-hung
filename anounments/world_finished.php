<?php
ob_start(); 
$ok = $_POST['world_finished'];
if($ok=='1'){
	$sql = "UPDATE `wg_users` SET `anounment` = '' WHERE `wg_users`.`id` =".$user['id'];
	$db->setQuery($sql);
	$db->query($sql);
	return true;
}else{
	$parse = array();	
	$sql = "SELECT * FROM  `wg_buildings` WHERE type_id =37 AND level=100";
	$db->setQuery($sql);
	$res = $db->loadObjectList();
	if(count($res)>0){
		$index = rand(0,count($res)-1);		
		$sql = "SELECT * FROM  `wg_villages` WHERE id=".$res[$index]->vila_id;
		$db->setQuery($sql);
		$db->loadObject($objVillage);
		$parse['village_name'] = $objVillage->name;
		
		$sql = "SELECT * FROM  `wg_users` WHERE id=".$objVillage->user_id;
		$db->setQuery($sql);
		$db->loadObject($objUser);
		$parse['user_name'] = $objUser->username;
		$parse['ally_name'] = '';
		$parse['thuoc_lien_minh'] = '';
		
		$sql = "SELECT * FROM  wg_ally_members, wg_allies";
		$sql.= " WHERE wg_ally_members.ally_id=wg_allies.id";
		$sql.= " AND wg_ally_members.user_id=".$objVillage->user_id;
		
		$db->setQuery($sql);
		$db->loadObject($objAlly);
		if($objAlly){
			$parse['ally_name'] = $objAlly->name;
			$parse['thuoc_lien_minh'] = 'thuộc liên minh';
		}
			
		$page = parsetemplate(gettemplate('anounments/world_finished'),$parse);
		display3($page,$lang['Title']);
		return false;
	}
}
ob_end_flush();
?>