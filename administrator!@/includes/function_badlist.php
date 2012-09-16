<?php
/*
	Plugin Name: function_badlist.php
	Plugin URI: http://asuwa.net/administrator/function_badlist.php
	Description: dung cho muc dich theo doi, nghi van
	+ dung chung PC
	+ dung chung tai khoan qua 30 ngay
	+ tan cong cung lien minh
	+ tan cong cung dung chung tai khoan
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/

define('INSIDE', true);
include($ugamela_root_path . 'includes/function_allian.php');

/*
* @Author: tdnquang
* @Des: + kiem tra dung chung PC
* @param: 		  
* @return: 
*/
function checkSameIP()
{
	global $db;
	
	$sql = "SELECT username,ip,count(id) as num FROM wg_securities 
			WHERE username != '' GROUP BY ip,username ORDER BY ip DESC";	
	$db->setQuery($sql);
	$elements = $db->loadObjectList();
	if($elements)
	{
		$arraySameIP = array();
		$i = 1;	
		foreach ($elements as $element)
		{
			$arraySameIP[$i]['ip'] = $element->ip;
			$arraySameIP[$i]['username'] = $element->username;
			$i++;
		}
	}		
	if(count($arraySameIP) > 1)
	{
		foreach($arraySameIP as $record)
		{
			$reason = "Dùng chung PC với user khác bởi địa chỉ IP 
						<a href=\"bad_list_detail.php?ip=".$record['ip']."\">".$record['ip']."</a>";
			insertBadList($record['username'], $reason);		
		}
	}
}

/*
* @Author: tdnquang
* @Des: + kiem tra tai khoan dung chung qua 30 ngay
* @param: 		  
* @return: 
*/
function checkAccountSister()
{
	global $db;
	//START: filter account sister
	$sql = "SELECT * FROM wg_sister";
	$db->setQuery($sql);
	$accountSisters = null;
	$accountSisters = $db->loadObjectList();
	
	if($accountSisters)
	{
		foreach($accountSisters as $row)
		{
			if(strtotime(date("Y-m-d H:i:s",time())) - strtotime($row->time) > 30*24*60*60)//30 days
			{
				$userName = getUserNameByUserId($row->user_id);			
				$reason1 = "Tài khoản <b>".$userName." </b> được sử dụng chung với 
					<b>".$row->sister1."</b> quá 30 ngày";
				$reason2 = "Đã sử dụng chung tài khoản <b>".$userName."</b> quá 30 ngày";
				//dua vao danh sach nghi van user chinh
				insertBadList($userName, $reason1);
				//dua vao danh sach nghi van user phu
				insertBadList($row->sister1, $reason2);
			}
		}
	}
	//END: filter account sister
}


/*
* @Author: tdnquang
* @Des: + kiem tra chiem lang
* @param: 		  
* @return: 
*/
function checkOccupyVillage()
{
	global $db;
	$sql = "SELECT * FROM wg_attack WHERE type = 4 OR type = 9";
	$db->setQuery($sql);	
	$occupyVillage = $db->loadObjectList();
	if($occupyVillage)
	{
		foreach($occupyVillage as $row)
		{
			//user attack
			$userIdAttack 	= getUserIdByVillageId($row->village_attack_id);
			$userNameAttack = getUserNameByUserId($userIdAttack);			
			//user defend
			$userIdDefend 	= getUserIdByVillageId($row->village_defend_id);
			$userNameDefend = getUserNameByUserId($userIdDefend);			

			//START: cung lien minh
			$isSameAlly = checkSameAlly($userIdAttack, $usreIdDefend);
			if($isSameAlly)
			{
				$reason = "Tấn công cùng liên minh:
							Hai user ".$userNameAttack." và ".$userNameDefend." cùng liên minh";
				//dua vao danh sach nghi van user attack
				insertBadList($userNameAttack, $reason);
				//dua vao danh sach nghi van user defend
				insertBadList($userNameDefend, $reason);		
			}
			//END: cung lien minh				
			
			//START: dung chung tai khoan
			$isSameAccountSister = checkSameAccountSister($userIdAttack, $userNameDefend);
			if($isSameAccountSister)
			{
				$reason = "Tấn công cùng tài khoản dùng chung:
								Hai user ".$userNameAttack." và ".$userNameDefend." dùng chung tài khoản";
				//dua vao danh sach nghi van user attack
				insertBadList($userNameAttack, $reason);
				//dua vao danh sach nghi van user defend
				insertBadList($userNameDefend, $reason);		
			}
			//END: dung chung tai khoan
		}
	}	
}

/*
* @Author: tdnquang
* @Des: + lay user_id boi village_id
* @param: + $villageId: id cua village		  
* @return: + user_id: id cua user
*/
function getUserIdByVillageId($villageId)
{
	global $db;
	$sql = "SELECT user_id FROM wg_villages WHERE id = ".$villageId;
	$db->setQuery($sql);
	$user = null;
	$db->loadObject($user);
	return $user->user_id;
}

/*
* @Author: tdnquang
* @Des: + kiem tra cung ally cua user
* @param: + $userIdAttack: id cua user tan cong	
		  + $usreIdDefend: id cua user phong thu				  
* @return: + true: 2 user cung ally, false: 2 user khac lien minh
*/
function checkSameAlly($userIdAttack, $usreIdDefend)
{
	global $db;
	$sql = "SELECT ally_id FROM wg_ally_members WHERE user_id = ".$userIdAttack;
	$db->setQuery($sql);
	$ally = null;
	$db->loadObject($ally);
	if(empty($ally))
	{
		return false;
	}
	else
	{
		$sql = "SELECT id FROM wg_ally_members WHERE ally_id = ".$ally->ally_id." AND user_id = ".$userIdDefend;
		$db->setQuery($sql);
		$check = null;
		$db->loadObject($check);	
		if(empty($check))
		{
			return false;
		}
		else
		{
			return true;
		}	
	}
}

/*
* @Author: tdnquang
* @Des: + kiem tra dung chung tai khoan
* @param: + $userIdAttack: id cua user tan cong	
		  + $userNameDefend: name cua user phong thu				  
* @return: + true: co dung chung, false: khong dung chung
*/
function checkSameAccountSister($userIdAttack, $userNameDefend)
{
	global $db;
	$sql = "SELECT user_id FROM wg_sister WHERE user_id = ".$userIdAttack." AND sister1 = '".$userNameDefend."'";
	$db->setQuery($sql);
	$accountSister = null;
	$db->loadObject($accountSister);
	if(empty($accountSister))
	{
		return false;
	}
	else
	{
		return true;
	}
}

/*
* @Author: tdnquang
* @Des: kiem tra so lan attack cua user moi ngay
* @param: 
* @return: 
*/
function checkNumAttackPerDay()
{
	global $db;
	$sql = "SELECT count(id) as num,title,time,type FROM wg_reports GROUP BY 
			title ORDER BY num ASC";
	$db->setQuery($sql);
	$attack = $db->loadObjectList();
	$arrayAttack = array();
	$i = 1;
	if($attack){
		foreach($attack as $row)
		{
			$arrayAttack[$i]['title'] = $row->title;
			$arrayAttack[$i]['time'] = $row->time;
			$arrayAttack[$i]['type'] = $row->type;
			$i++;
		}
	}
	//echo "<pre/>";
	//print_r($arrayAttack);
	for($j = 1; $j <=count($arrayAttack); $j++)
	{
		if(strtotime($arrayAttack[$j+1]['time']) - strtotime($arrayAttack[$j]['time']) 
						<= 1*24*60*60)
		{
			//echo "co ".$j;	
		}
	}
}

/*
* @Author: tdnquang
* @Des: insert vao bang wg_bad_list (danh sach nhung user bi nghi van)
* @param: + $userName: username
		  + $reason: ly do bi nghi van
* @return: 
*/
function insertBadList($userName, $reason)
{
	global $db;
	$sql = "INSERT INTO wg_bad_list(username,reason,time) 
			VALUES('".$userName."','".$reason."','".date("Y-m-d H:i:s")."')";
	$db->setQuery($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: xoa tat ca bad list
* @param:		 
* @return: 
*/
function deleteAllBadList()
{
	global $db;
	$sql = "DELETE FROM wg_bad_list";
	$db->setQuery($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: check bad list of user
* @param: + $userId: id cua user		 
* @return: + true: co trong dang dach bad list
*/
function checkBadList($userName)
{
	global $db;
	$sql = "SELECT id FROM wg_bad_list WHERE username = '".$userName."'";
	$db->setQuery($sql);
	$db->loadObject($checkBackList);
	if($checkBackList){
		return true;
	}else {
		return false;
	}
}
?>