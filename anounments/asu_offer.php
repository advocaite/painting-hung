<?php
ob_start(); 
$ok = $_POST['asu_offer_ok'];
if($ok=='1'){
	$sql = "UPDATE `wg_users` SET `anounment` = '' WHERE `wg_users`.`id` =".$user['id'];
	$db->setQuery($sql);
	$db->query($sql);
	
	$sql = "UPDATE `wg_plus` SET `gold` = (`gold`+2009) WHERE `user_id`=".$user['id'];
	$db->setQuery($sql);
	$db->query($sql);
	
	return true;
}else{
	$parse = array();
	$page = parsetemplate(gettemplate('anounments/asu_offer'),$parse);
	display2($page,$lang['Title']);
	return false;
}
ob_end_flush();
?>