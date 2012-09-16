<?php
/*
	Plugin Name: function_profile.php
	Plugin URI: http://asuwa.net/includes/function_profile.php
	Description: 
	+ Cac ham dung cho profile
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/

define('INSIDE', true);

/*
* @Author: tdnquang
* @Des: + cap nhat profile cua user
* @param: + $birthday: ngay sinh
		  + $sex: gioi tinh
		  + $location: den tu
		  + $description: thong tin gioi thieu
		  + $sign: thong tin gioi thieu khac	
  		  + $userId: id cua user
* @return: cap nhat vao database profile moi cua user
*/
function updateProfile($birthday, $sex, $location, $description, $sign,$phone, $username)
{
	global $db;
	$sql = "UPDATE wg_profiles SET birthday='".$birthday."', sex='".$sex."',
			birthplace='".$location."', description='".$description."', sign='".$sign."',phone='".$phone."'
		 	WHERE username='".$username."'";						
	$db->setQuery($sql);
	$db->query();
	
}

/*
* @Author: tdnquang
* @Des: + cap nhat village name cua user
* @param: + $villageName: ten thanh
		  + $villageId: id cua thanh		 
* @return: cap nhat vao database village name moi
*/
function updateVillageName($villageName, $villageId)
{
	global $db;
	$sql = "UPDATE wg_villages SET name = '".$villageName."' WHERE id = ".$villageId." AND kind_id < 7";								
	$db->setQuery($sql);
	$db->query();		
}
/*
* @Author: tdnquang
* @Des: insert status khi user thuc hien xoa account
* @param: + $userid: id cua user		  
* @return: insert vao database status xoa account voi type = 18
*/
function insertStatusDeleteAccount($userId)
{
	global $db;
	//$costTime = 20;
	$costTime = 3600*24*3; // 3 days
	$timeBegin = laythoigian(time());
	$timeEnd = laythoigian(time() + $costTime);
	
	$sql="INSERT INTO wg_status(object_id, type, time_begin, time_end, cost_time) 
		VALUES('".$userId."','18', '".$timeBegin."','".$timeEnd ."', '".$costTime."')"; 
	$db->setQuery($sql); 
	$db->query();		
}

/*
* @Author: tdnquang
* @Des: xoa account
* @param: + $userid: id cua user		  
* @return: xoa hoac cap nhat tat ca nhung table nao lien quan den user do
*/
function deleteAccount($userId)
{
	global $db,$lang,$game_config;
	$parse=$lang;
	$status = getTimeEndDeleteAccount($userId); 
	if($status)
	{
		if(strtotime($status->time_end) < time())
		{
			$sql="SELECT id FROM wg_users WHERE username='Admin'";
			$db->setQuery($sql);
			$id_user=$db->loadResult();
			
			$username=deleteTable($userId);		
			
			$topic=$username.' '.$lang['Del_account'];
			$content=$topic.' ['.date("Y-m-d H:i:s").']';
			$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) 
			VALUES (".$id_user.",".$userId.",0,'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."');";
			$db->setQuery($sql);
			$db->query();
						
			$sql ="INSERT INTO `wg_messages_backup` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) 
			VALUES (".$id_user.",".$userId.",0,'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."');";
			$db->setQuery($sql);
			$db->query();
			
			header("Location:logout.php"); 	
			exit();	
		}
	}
}

/*
* @Author: tdnquang
* @Des: xoa account
* @param: + $userid: id cua user		  
* @return: xoa hoac cap nhat tat ca nhung table nao lien quan den user do
*/
function executeAccountDelete($userId)
{
	global $db,$game_config;
	deleteTable($userId);	
}

/*
* @Author: tdnquang
* @Des: xoa hoac update cac table lien quan den user
* @param: + $userid: id cua user		  
* @return: xoa hoac cap nhat tat ca nhung table nao lien quan den user do
*/
function deleteTable($userId)
{
	global $db;
	delete_wg_status($userId);	
	delete_wg_villages($userId);
	
	// Bo sung cac DK xoa tai khoan cho Lien Minh
	$sql="SELECT ally_id FROM wg_ally_members WHERE user_id=$userId";
	$db->setQuery($sql);
	$ally_id=$db->loadResult();
	if($ally_id !=NULL)
	{
		$sql="SELECT user_id FROM wg_ally_members WHERE ally_id=".$ally_id." AND right_=1 ORDER BY id ASC";
		$db->setQuery($sql);
		$wg_ally_members=null;
		$wg_ally_members=$db->loadObjectList();
		if(count($wg_ally_members)==1) // lien minh do user tao ra khong co thanh vien con
		{
			delete_wg_allies($userId);
		}
		else
		{
			$sql="SELECT COUNT(DISTINCT(id)) FROM wg_allies WHERE user_id=$userId";//die($sql);
			$db->setQuery($sql);
			$count = (int)$db->loadResult();
			if($count==1) // nguoi tao ra lien minh
			{
				foreach ($wg_ally_members as $key=>$result)
				{
					if($result->user_id != $userId)
					{
						// cap nhat cho Lien Minh nguoi Quan Ly' moi ( lay theo tu tu gia nhap va Ally som nhat)
						$sql="UPDATE `wg_allies` SET user_id=".$result->user_id." WHERE id=$ally_id";
						$db->setQuery($sql);
						$db->query();
						// cap day du quyen cho nguoi Quan Ly moi
						$sql="UPDATE `wg_ally_members` SET privilege='11111111',position_name='Minh chủ' WHERE user_id=".$result->user_id."";
						$db->setQuery($sql);
						$db->query();
						break;	
					}
				}
			}					
		}
		delete_wg_ally_members($userId);
		
	}
	delete_wg_user_bans($userId);
	
	delete_wg_reports($userId);
	
	delete_wg_plus($userId);	
	
	delete_wg_top10($userId);
	
	delete_wg_messages($userId);
	
	delete_wg_gold_logs($userId);
			
	$username=delete_wg_users($userId);
	
	delete_wg_profiles($username);
	
	update_wg_villages_map($userId);
	
	return $username;
}
function delete_wg_ally_relation($key1,$key2,$id_ally)
{
	global $db;
	$sql="SELECT $key1 AS ally_id FROM `wg_ally_relation` WHERE $key2=$id_ally";//die($sql);
	$db->setQuery($sql);
	$wg_ally_relation=null;
	$db->loadObject($wg_ally_relation);
	$sql="DELETE FROM `wg_ally_relation` WHERE $key1=".$id_ally."";
	$db->setQuery($sql);
	$db->query();
	return $wg_ally_relation;
}
function delete_wg_ally_news($array)
{
	global $db;
	foreach ($array as $result)
	{
		$sql="DELETE FROM `wg_ally_news` WHERE `ally_id`=".$result->ally_id."";
		$db->setQuery($sql);
		$db->query();
	}
}
function delete_wg_top10($userId)
{
	global $db;
	$sql="DELETE FROM wg_top10 WHERE user_id=".$userId;
	$db->setQuery($sql);
	$db->query();
}
/*
* @Author: tdnquang
* @Des: xoa table wg_status
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_status($userId)
{
	global $db;
	$sql = "DELETE FROM wg_status WHERE type = 18 AND object_id=".$userId;
	$db->setQuery ( $sql );
	$db->query();
}

function delete_wg_profiles($username)
{
	global $db;
	$sql = "DELETE FROM wg_profiles WHERE username ='".$username."'";
	$db->setQuery ( $sql );
	$db->query();
}
/*
* @Author: tdnquang
* @Des: xoa table wg_resource_sends
* @param: + $villageId: id cua village
* @return: 
*/
function delete_wg_resource_sends($villageId)
{
	global $db;
	$sql = "DELETE FROM wg_resource_sends 
				   WHERE village_id_from=".$villageId." OR village_id_to=".$villageId;
	$db->setQuery($sql);
	$db->query();	
}

/*
* @Author: tdnquang
* @Des: xoa table wg_resource_orders
* @param: + $villageId: id cua village
* @return: 
*/
function delete_wg_resource_orders($villageId)
{
	global $db;
	$sql = "DELETE FROM wg_resource_orders WHERE village_id=".$villageId;
	$db->setQuery($sql);
	$db->query();	
}
/*
* @Author: tdnquang
* @Des: xoa table wg_buildings
* @param: + $villageId: id cua village
* @return: 
*/
function delete_wg_buildings($villageId)
{
	global $db;
	$sql = "DELETE FROM wg_buildings WHERE vila_id=".$villageId;		
	$db->setQuery ($sql);			
	$db->query();		
}

/*
* @Author: tdnquang
* @Des: xoa table wg_villages
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_villages($userId)
{
	global $db;
	//START: xoa nhung table co lien quan den village
	$villages = getVillageIdByUserId($userId);	
	if($villages)
	{
		foreach($villages as $village)
		{
			//delete table wg_resource_sends 
			delete_wg_resource_sends($village->id);
			//delete table wg_resource_orders
			delete_wg_resource_orders($village->id);
			//delete table wg_buildings
			delete_wg_buildings($village->id);
			
			update_wg_registration_village_list($village->id);				
		}			
	}
	//END: xoa nhung table co lien quan den village
	
	// Delete table wg_villages
	$sql = "DELETE FROM wg_villages WHERE user_id=".$userId;	
	$db->setQuery ($sql);
	$db->query();	
}

/*
* @Author: tdnquang
* @Des: xoa table wg_users
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_users($userId)
{
	global $db;
	$username=NULL;
	$sql = "SELECT username FROM wg_users WHERE id=".$userId;
	$db->setQuery($sql);
	$username=$db->loadResult();
	$sql = "DELETE FROM wg_users WHERE id=".$userId;
	$db->setQuery ( $sql );
	$db->query();
	return $username;
}

/*
* @Author: tdnquang
* @Des: xoa table wg_ally_members
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_ally_members($userId)
{
	global $db;
	// Delete account in table wg_ally_members
	$sql = "DELETE FROM wg_ally_members WHERE user_id=".$userId;//die($sql);
	$db->setQuery ($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: xoa table wg_allies
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_allies($userId)
{
	global $db;
	// Delete account in table wg_allies
	$sql = "DELETE FROM wg_allies WHERE user_id=".$userId;
	$db->setQuery ( $sql );
	$db->query();	
}

/*
* @Author: tdnquang
* @Des: xoa table wg_user_bans
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_user_bans($userId)
{
	global $db;
	// Delete account in table wg_user_bans
	$sql = "DELETE FROM wg_user_bans WHERE user_id=".$userId;
	$db->setQuery ( $sql );
	$db->query();	
}

/*
* @Author: tdnquang
* @Des: xoa table wg_reports
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_reports($userId)
{
	global $db;
	// Delete account in table wg_reports
	$sql = "DELETE FROM wg_reports WHERE user_id=".$userId;
	$db->setQuery ( $sql );
	$db->query();	
}

/*
* @Author: tdnquang
* @Des: xoa table wg_plus
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_plus($userId)
{
	global $db;
	// Delete account in table wg_plus
	$sql = "DELETE FROM wg_plus WHERE user_id=".$userId;
	$db->setQuery ( $sql );
	$db->query();	
}

/*
* @Author: tdnquang
* @Des: xoa table wg_messages
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_messages($userId)
{
	global $db;
	// Delete account in table wg_messages
	$sql = "DELETE FROM wg_messages WHERE id_user=".$userId;
	$db->setQuery ( $sql );
	$db->query();	
	$db->query();	
}

/*
* @Author: tdnquang
* @Des: xoa table wg_gold_logs
* @param: + $userid: id cua user		  
* @return: 
*/
function delete_wg_gold_logs($userId)
{
	global $db;
	// Delete account in table wg_gold_logs
	$sql = "DELETE FROM wg_gold_logs WHERE user_id=".$userId;
	$db->setQuery ( $sql );
	$db->query();
}

/*
* @Author: tdnquang
* @Des: tra lai table wg_villages_map
* @param: + $userid: id cua user		  
* @return: 
*/
function update_wg_villages_map($userId)
{
	global $db;	
	$sql = "UPDATE wg_villages_map SET user_id=0 WHERE user_id=".$userId;
	$db->setQuery ($sql);
	$db->query();
}

function update_wg_registration_village_list($Id)
{
	global $db;	
	$sql = "UPDATE wg_registration_village_list SET registed=0 WHERE village_id=".$Id;
	$db->setQuery ($sql);
	$db->query();
}
/*
* @Author: tdnquang
* @Des: lay thoi gian delete account cua user
* @param: + $userid: id cua user		  
* @return: time_end cua status delete account
*/
function getTimeEndDeleteAccount($userId)
{
	global $db;
	$sql = "SELECT id,object_id,time_end FROM wg_status WHERE object_id = ".$userId." AND type = 18";
	$db->setQuery($sql);
	$costTime = null;
	$db->loadObject($costTime);
	return $costTime;
}

/*
* @Author: tdnquang
* @Des: lay thong tin cua user
* @param: + $userid: id cua user		  
* @return: thong tin cua user do
*/
function getAllUserInfo($userId)
{
	global $db;
	$sql = "SELECT wg_users.username,wg_users.alliance_id,wg_users.villages_id,wg_users.sum_villages,
	wg_users.population,wg_users.nation_id,wg_profiles.* FROM wg_users LEFT JOIN wg_profiles ON 			wg_profiles.username=wg_users.username WHERE wg_users.id = ".$userId;
	$db->setQuery($sql); 
	$userInfo=NULL;
	$db->loadObject($userInfo);
	return $userInfo;
}
/*
* @Author: tdnquang
* @Des: lay thong tin profile cua user
* @param: + $userid: id cua user		  
* @return: thong tin profile cua user do
*/
function getUserProfileInfo($username)
{
	global $db;
	$userProfileInfo=null;
	$sql = "SELECT * FROM wg_profiles WHERE username ='".$username."'";
	$db->setQuery($sql); 	
	$db->loadObject($userProfileInfo);
	return $userProfileInfo;	
}

/*
* @Author: tdnquang
* @Des: lay thong tin cua user
* @param: + $userId: id cua user
		  + $sister1: tai khoan 1
		  + $sister2: tai khoan 2
		  + $time: thoi gian dung chung: 30 ngay
* @return: insert vao table wg_sister
*/
function insertAccountSister($userId, $sister1, $time)
{
	global $db;
	$sql = "INSERT INTO wg_sister(user_id, sister1, time) 
						VALUES(".$userId.",'".$sister1."','".$time."')";
	$db->setQuery ($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: lay thong tin cua account sister
* @param: + $userId: id cua user
* @return: thong tin cua account sister
*/
function getAccountSisterInfo($username)
{
	global $db;
	$sql = "SELECT tb1.user_id,tb1.sister1 FROM wg_sister as tb1,wg_users as tb2 WHERE tb2.username='".$username."' AND tb2.id=tb1.user_id";
	$db->setQuery($sql); 
	$accountSisterInfo = null;
	$db->loadObject($accountSisterInfo);	
	return $accountSisterInfo;
}

function getAccountSisterInfoByID($id)
{
	global $db;
	$sql = "SELECT tb2.id,tb2.username FROM wg_sister as tb1,wg_users as tb2 WHERE tb1.user_id=".$id." AND tb2.username=tb1.sister1";
	$db->setQuery($sql); 
	$accountSisterInfo = null;
	$db->loadObject($accountSisterInfo);	
	return $accountSisterInfo;
}
/*
* @Author: tdnquang
* @Des: delete account sister
* @param: + $userId: id cua user
* @return: delete
*/
function deleteAccountSister($userId)
{
	global $db,$user;
	if($user['id']==$userId)
	{	
		$sql = "DELETE FROM wg_sister WHERE user_id = ".$userId;
		$db->setQuery ($sql);
		$db->query();
		return true;
	}
	return false;
}

/*
* @Author: tdnquang
* @Des: lay village_id boi user_id
* @param: + $userId: id cua user
* @return: danh sach village_id cua user do
*/

function getVillageIdByUserId($userId)
{
	global $db;
	$sql = "SELECT id FROM wg_villages WHERE user_id = ".$userId;
	$db->setQuery ($sql);
	$villages = null;
	$villages = $db->loadObjectList();	
	return $villages;
}
function countaccountsister($id)
{
	global $db;
	$sql = "SELECT id FROM wg_sister WHERE user_id =".$id;
	$db->setQuery ($sql);
	$wg_sister = null;
	$wg_sister = $db->loadResult();	
	return $wg_sister;
}
?>