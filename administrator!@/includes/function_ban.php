<?php
/*
	Plugin Name: function_ban.php
	Plugin URI: http://asuwa.net/administrator/function_ban.php
	Description: cac ham cho viec ban user
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);
/*
* @Author: tdnquang
* @Des: check tinh trang user da bi ban hay chua
* @param: + $userId: id cua user		 
* @return: + true: da bi ban, false: chua bi ban
*/
function checkUserBanned($userId)
{
	global $db;
	$sql = "SELECT id FROM wg_users WHERE id = ".$userId." AND anounment != ''";
	$db->setQuery($sql);
	$checkBan = null;
	$db->loadObject($checkBan);
	if(!empty($checkBan)){
		return true;
	}else {
		return false;
	}
}

/*
* @Author: tdnquang
* @Des: kiem tra user co trong talbe wg_user_bans hay chua
* @param: + $userId: id cua user		 
* @return: + true: co, false: khong
*/
function checkUserInTablewg_user_bans($userId)
{
	global $db;
	$sql = "SELECT id FROM wg_user_bans WHERE user_id = ".$userId;
	$db->setQuery($sql);
	$check = null;
	$db->loadObject($check);
	if(!empty($check)){
		return true;
	}else {
		return false;
	}
}


/*
* @Author: tdnquang
* @Des: update user trong table wg_users o trang thai bi ban
* @param: + $userId: id cua user
* @return:
*/
function updateUserBanned($userId)
{
	global $db;
	$sql="UPDATE wg_users SET anounment='ban' WHERE id=".$userId;
	$db->setQuery($sql);
	$db->query();
}

/* @Author: tdnquang
* @Des:	get time
* @param: $seconds
* @return: Y-m-d H:i:s
*/
function getTime($seconds){
	$out_date=date("Y-m-d H:i:s",$seconds);
	return $out_date;
}

?>