<?php
ob_start(); 

$sSql = "SELECT * FROM wg_user_bans WHERE user_id =".$user['id'];
$db->setQuery($sSql);
$objUserBan=null;
$db->loadObject($objUserBan);

$endTime = 	strtotime($objUserBan->end_date);
$timeNow = time();

if($endTime<$timeNow){
	$sql = "UPDATE wg_users SET anounment= '' WHERE id =".$user['id'];
	$db->setQuery($sql);
	$db->query($sql);
	
	$sql = "DELETE FROM wg_user_bans WHERE user_id =".$user['id'];
	$db->setQuery($sql);
	$db->query($sql);
	return true;
}
else{
	$parse = array();
	$parse['ban_date'] = $objUserBan->ban_date;
	$parse['ban_time'] = $objUserBan->ban_time;
	$parse['end_date'] = $objUserBan->end_date;
	$parse['reason'] = nl2br($objUserBan->reason);
	$page = parsetemplate(gettemplate('anounments/ban'),$parse);
	display3($page,$lang['Title']);
	return false;
}

ob_end_flush();
?>