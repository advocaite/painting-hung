<?php
/*
	Plugin Name: function_plus.php
	Plugin URI: http://asuwa.net/includes/function_plus.php
	Description: 
	+ Cac ham dung cho tai khoan Asu
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/

define('INSIDE', true);
/*
* @Author: tdnquang
* @Des: lay thoi gian va asu cua tung goi plus
* @param: + $namePlus: goi plus nao
* @return: 
*/
function getDuringTimeAndAsu($namePlus){
	global $db;
	$sql = "SELECT duration, asu FROM wg_config_plus WHERE name = '".$namePlus."'";
	$db->setQuery($sql);
	$info = null;	
	$db->loadObject($info);
	return $info;
}

/*
* @Author: tdnquang
* @Des: cap nhat rs khi plus da qua han
* @param: 
* @return: 
*/
function updateAllPlus()
{
	global $db;
	$sql = "SELECT user_id FROM wg_plus";	
	$db->setQuery($sql);	
	$arrUserIds = $db->loadObjectList();	
	
	foreach($arrUserIds as $userId)
	{
		$sql = "SELECT lumber, clay, iron, crop FROM wg_plus WHERE user_id = ".$userId->user_id;
		$plusExpired = null;
		$db->setQuery($sql);	
		$db->loadObject($plusExpired);	
				
		if(strtotime($plusExpired->lumber) != 0
			&& strtotime($plusExpired->lumber) < strtotime(date("Y-m-d H:i:s",time())))
		{
			subtractCoefficient('krs1', $userId->user_id);
			updateTimePlus('lumber', $userId->user_id);		
		}
		if(strtotime($plusExpired->clay) != 0 
			&& strtotime($plusExpired->clay) < strtotime(date("Y-m-d H:i:s",time())))
		{
			subtractCoefficient('krs2', $userId->user_id);
			updateTimePlus('clay', $userId->user_id);		
		}
		if(strtotime($plusExpired->iron) != 0 
			&& strtotime($plusExpired->iron) < strtotime(date("Y-m-d H:i:s",time())))
		{
			subtractCoefficient('krs3', $userId->user_id);
			updateTimePlus('iron', $userId->user_id);	
		}
		if(strtotime($plusExpired->crop) != 0 
			&& strtotime($plusExpired->crop) < strtotime(date("Y-m-d H:i:s",time())))
		{
			subtractCoefficient('krs4', $userId->user_id);
			updateTimePlus('crop', $userId->user_id);	
		}					
	}	
}

/*
* @Author: tdnquang
* @Des: cong he so k trong table wg_villages
* @param: + $field: truong nao trong wg_plus
		  + $userId: id cua user
* @return: 
*/
function updateCoefficient($field, $userId)
{
	global $db;
	$sql="UPDATE wg_villages SET $field = $field*1.25 WHERE user_id = ".$userId." AND kind_id < 7";
	$db->setQuery($sql);
	$db->query();
}

/*
* @Author: tdnquang
* @Des: tru he so k trong table wg_villages
* @param: + $field: truong nao trong wg_plus		  
* @return: 
*/
function subtractCoefficient($field, $userId)
{
	global $db;
	$sql="UPDATE wg_villages SET $field=$field/1.25 WHERE $field>=1.25 AND user_id = ".$userId." AND kind_id < 7";	
	$db->setQuery($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: cap nhat thoi gian cua tung field trong table wg_plus
* @param: + $field: truong nao trong wg_plus		  
* @return: 
*/
function updateTimePlus($field, $userId)
{
	global $db;
	$sql="UPDATE wg_plus SET $field = '0000-00-00 00:00:00' WHERE user_id = ".$userId;
	$db->setQuery($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: cap nhat thoi gian cua tung field trong table wg_plus cua user nao
* @param: + $field: truong nao trong wg_plus
		  + $userId: id cua user
* @return: 
*/
function updateTime($field, $duration, $userId)
{
	global $db;	
	$timeEnd = laythoigian(time() + $duration*24*60*60);
	
	$sql="UPDATE wg_plus SET $field = '".$timeEnd."' WHERE user_id=".$userId."";
	$db->setQuery($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: lay thoi gian het han cua tung field trong table wg_plus cua user nao
* @param: + $field: truong nao trong wg_plus
		  + $userId: id cua user
* @return: thoi gian het han
*/
function getTimeEnd($field, $userId)
{
	global $db;
	$sql = "SELECT $field FROM wg_plus WHERE user_id=".$userId."";
	$db->setQuery($sql);
	$timeEnd = null;
	$db->loadObject($timeEnd);
	return $timeEnd;
}

function getTimeEndAll($userId)
{
	global $db;
	$sql = "SELECT * FROM wg_plus WHERE user_id=".$userId;
	$db->setQuery($sql);
	$timeEnd = NULL;
	$db->loadObject($timeEnd);
	return $timeEnd;
}
/*
* @Author: tdnquang
* @Des: cong them thoi gian cua tung field trong table wg_plus cua user nao
* @param: + $field: truong nao trong wg_plus
		  + $userId: id cua user
* @return: 
*/
function addMoreTime($field, $duration, $userId)
{
	global $db;
	//
	$sql = "SELECT $field FROM wg_plus WHERE user_id=".$userId."";
	$db->setQuery($sql);
	$valueTime = null;
	$db->loadObject($valueTime);
	$currentValue = $valueTime->$field;
	//
	$timeEnd = laythoigian(strtotime($currentValue) + $duration*24*60*60);
	
	$sql="UPDATE wg_plus SET $field = '".$timeEnd."' WHERE user_id=".$userId."";
	$db->setQuery($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: show thoi gian dem nguoc con lai
* @param: + $field: truong nao trong wg_plus
		  + $timeType: loai nao 
		  + $goldType: gold loai nao
		  + $gold: so gold
		  + $actionType: loai hanh dong nao
		  + $userId: id cua user
* @return: thoi gian dem nguoc
*/
function showTimeAndAction($field, $timeType, $goldType, $gold, $actionType, $userId)
{
	global $lang;
	includeLang('plus');
	$parse=$lang;
	$page="";
	
	$parse[$gold] = $goldType; // type of gold, ex: PLUS_GOLD = 15, LUMBER_GOLD = 5 (in constant_plus.php)
	
	$current_gold = showGold($userId);
	$parse['total_gold'] = $current_gold; // show current gold
	
	$parse[$timeType] = "";
	$timeEnd = getTimeEnd($field, $userId);
	$second = strtotime($timeEnd->$field) - time();	
	if($second >0)
	{
		for($i=1;$i<2;$i++)
		{
			$parse[$timeType] ="<b><p class='c'>".$lang['Remainder time']." <span id='account'".$i.">".ReturnTime($second)."</span></p></b>";									
		}	
		if($current_gold >= $goldType)
		{
			$parse[$actionType] = $lang['Extend'];
		}
		else
		{
			$parse[$actionType] = "<span class=\"c t\">Không đủ Asu</span>";	
		}		
	}
	else
	{
		if($current_gold >= $goldType)
		{
			$parse[$actionType] = $lang['Active'];
		}
		else
		{
			$parse[$actionType] = "<span class=\"c t\">Không đủ Asu</span>";	
		}		
	}
	$plusTemplate .= parsetemplate (gettemplate ('plus'), $parse );
	return $plusTemplate;
}

/*
* @Author: tdnquang
* @Des: cong gold
* @param: + $gold: luong gold cong them 
		  + $userId: id cua user
* @return: 
*/
function depositGold($gold, $userId) 
{
	global $db;
	$sql="UPDATE wg_plus SET gold = gold + ".$gold." WHERE user_id='".$userId."'";
	$db->setQuery($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: lay so gold hien tai de hien thi
* @param: + $userId: id cua user
* @return: 
*/
function showGold($userId) 
{
	global $db;
	$sql = "SELECT gold FROM wg_plus WHERE user_id=".$userId."";
	$db->setQuery($sql);
	return $db->loadResult();	
}


/*
* @Author: tdnquang
* @Des: tru gold
* @param: + $gold: luong gold tru di
		  + $userId: id cua user
* @return: 
*/
function withdrawGold($gold,$userId,$asu_builling,$des)
{
	global $db,$user;
	$currentValue = showGold($userId);
	if($currentValue>=$gold)
	{
		$sql="UPDATE wg_plus SET gold = gold-".$gold." WHERE user_id=".$userId;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function withdrawGold:'.$sql);
		}
		return ($currentValue-$gold);		
	}
	else
	{
		if(($asu_builling+$currentValue)>=$gold)	
		{
			$sql="UPDATE wg_plus SET gold=0 WHERE user_id=".$userId;
			$db->setQuery($sql);
			$db->query();					
			withdraw_gold_remote($user['username'],$gold-$currentValue,$des);		
			return $asu_builling-($gold-$currentValue);
		}
		return $currentValue;		
	}
	return $currentValue;
}

/*
* @Author: tdnquang
* @Des: get index cua palace (cung dien)
* @param: + $village_id: id cua village
* @return: + index of palace
*/
function getIndexOfPalace($village_id)
{
	global $db,$wg_buildings;
	if($wg_buildings)
	{
		foreach($wg_buildings as $v)
		{
			if($v->type_id == 18)
			{
				return $v->index;
			}
		}
	}
	return NULL;
}

/*
* @Author: tdnquang
* @Des: get index cua hero mansion (tuong phu)
* @param: + $village_id: id cua village
* @return: + index of Hero_Mansion
*/

function getIndexOfHeroMansion($village_id)
{
	global $db,$wg_buildings;
	if($wg_buildings)
	{
		foreach($wg_buildings as $v)
		{
			if($v->type_id == 35)
			{
				return $v->index;				
			}
		}
	}
	return NULL;
}

/*
* @Author: tdnquang
* @Des: get index cua Wonder (ky dai)
* @param: + $village_id: id cua village
* @return: + index of Wonder
*/

function getIndexOfWonder($village_id)
{
	/*global $db;	
	$value=NULL;
	$sql="SELECT `index` FROM wg_buildings WHERE vila_id = ".$village_id." AND type_id = 37"; 
	$db->setQuery($sql);	
	$value=$db->loadResult();	
	return $value;*/
	global $db,$wg_buildings;
	if($wg_buildings)
	{
		foreach($wg_buildings as $v)
		{
			if($v->type_id == 37)
			{
				return $v->index;				
			}
		}
	}
	return NULL;
}
/*
* @Author: tdnquang
* @Des: kiem tra cong trinh dang nang cap hay xay dung moi
* @param: + $village_id: id cua village
* @return: 
*/
function checkBuildOrUpdateOrDeleteBuilding($village_id)
{
	global $db;	
	$indexPalace = getIndexOfPalace($village_id);
	$indexHeroMansion = getIndexOfHeroMansion($village_id);
	$indexWonder = getIndexOfWonder($village_id);
	
	$sql = "SELECT COUNT(DISTINCT(id)) FROM wg_status WHERE village_id=".$village_id." 
			AND (type <= 3 OR type = 17) AND status = 0";	
	if(!empty($indexPalace)){
		$sql .= " AND object_id != ".$indexPalace."";
	}
	if(!empty($indexHeroMansion)){
		$sql .= " AND object_id != ".$indexHeroMansion."";
	}
	if(!empty($indexWonder)){
		$sql .= " AND object_id != ".$indexWonder."";
	}
		
	$db->setQuery($sql);
	$sum = (int)$db->loadResult();
	return $sum;
}

/*
* @Author: tdnquang
* @Des: lay tat ca village_id cua 1 user
* @param: + $userId: id cua user
* @return: tat ca village_id cua user do
*/
function getAllVillageIdByUserId($userId)
{
	global $db;
	$sql = "SELECT id FROM wg_villages WHERE user_id = ".$userId." AND kind_id < 7";
	$db->setQuery($sql);
	$village = null;
	$village = $db->loadObjectList();	
	return $village;
}

/*
* @Author: tdnquang
* @Des: lay goi plus cua attack va defend
* @param: + $userId: id cua user
		  + $time: thoi gian
* @return: + 0: khong co
		   + 1: attack
		   + 2: defend
		   + 3: trade	
*/
function getAttDefPlus($userId, $time)
{
	global $db;
	$sql = "SELECT attack, defence, trade FROM wg_plus WHERE user_id = ".$userId;
	$expired = null;
	$db->setQuery($sql);	
	$db->loadObject($expired);	
	if($expired->attack < $time){
		return 0; // goi(package) attack da het han
	}else{
		return 1; // co goi(package) attack
	}
	if($expired->defence < $time){
		return 0; // goi(package) defence da het han
	}else{
		return 2; // co goi(package) defence
	}
	if($expired->trade < $time){
		return 0; // goi(package) trade da het han
	}else{
		return 3; // co goi(package) trade
	}			
}
/*
* @Author: tdnquang
* @Des: lay status for hoan thanh ngay lap tuc
* @param: + $village_id: id cua village
* @return: 
*/
function getStatusForActiveNow($village_id)
{
	global $db;	
	$indexPalace = getIndexOfPalace($village_id);	
	$indexHeroMansion = getIndexOfHeroMansion($village_id);	
	$indexWonder = getIndexOfWonder($village_id);
	$sql = "SELECT id,object_id,type FROM wg_status 
			WHERE village_id = ".$village_id." AND (type<=3 OR type=17) AND status = 0";	
	$db->setQuery($sql);
	$listStatus=NULL;	
	$listStatus = $db->loadObjectList();
	if($listStatus)
	{
		foreach($listStatus as $key=>$v)
		{
			if($v->object_id == $indexPalace || $v->object_id == $indexHeroMansion || $v->object_id == $indexWonder)
			{
				unset($listStatus[$key]);
			}
		}
	}
	return $listStatus;
}
?>