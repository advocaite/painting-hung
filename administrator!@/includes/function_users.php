<?php
/*
	Plugin Name: function_users.php
	Plugin URI: http://asuwa.net/administrator/includes/function_users.php
	Description: 
	+ Cac ham dung cho user trong administrator
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/

ob_start(); 
if ( !defined('INSIDE') )
{
	die("Hacking attempt");
}
/*
* @Author: tdnquang
* @Des: lay username by user_id cua user
* @param: $userId: id cua user
* @return: username cua user
*/
function admin_getUserNameByUserId($userId)
{
	global $db;
	$sql="SELECT username FROM wg_users WHERE id = ".$userId." LIMIT 1";
	$db->setQuery($sql);
	$username=$db->loadResult();
	return $username;
}
/*
* @Author: tdnquang
* @Des: lay user_id by username cua user
* @param: $userName: username cua user
* @return: user_id cua user
*/
function admin_getUserIdByUserName($userName)
{
	global $db;
	$sql="SELECT id,username FROM wg_users WHERE username LIKE '%".$userName."%'";
	$db->setQuery($sql);
	$wg_users=NULL;
	$wg_users=$db->loadObjectList();
	return $wg_users;
}
?>