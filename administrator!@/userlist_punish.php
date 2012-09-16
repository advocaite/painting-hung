<?php
/*
	Plugin Name: userlist_punish.php
	Plugin URI: http://asuwa.net/administrator/userlist.php
	Description: 
	+ Xu phat user
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);
ob_start();
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include('includes/function_punish.php');
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_allian.php');
include($ugamela_root_path . 'includes/func_build.php');


if(!check_user()){ header("Location: login.php"); }
global $db,$game_config;
function deleteAllOfferPunish($vl_id)
{
	global $db;
	$sql = "DELETE FROM wg_resource_orders WHERE village_id=$vl_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}
if($user['authlevel']<1)
{ 
	header("Location:index.php");
}
else
{
	global $db, $user,$lang;
	$parse = $lang;
	$parse['msg_village']=$parse['msg_ally']=$parse['msg_troop']=$parse['msg_population']='';
	if(isset($_GET['uid']) && is_numeric($_GET['uid']))
	{
		$userId = $_GET['uid'];
	}
	//get username 
	$userName = getUserNameByUserId($userId);
	$parse['user_name'] = $userName;	
	//START: list village of user
	$sql="SELECT id, name FROM wg_villages WHERE user_id=".$userId." AND kind_id <=6 ORDER BY workers DESC";
	$db->setQuery($sql); 
	$villagesInfo = $db->loadObjectList();
	if($villagesInfo){
		foreach ($villagesInfo as $villageInfo)
		{  
			$parse['village'] = $villageInfo->name; // Drop down list of village name 
			$parse['village_id'] = $villageInfo->id;
			$villageList .= parsetemplate(gettemplate('/admin/userlist_punish_village'), $parse);
		}
		$parse['vilage_name_list'] = $villageList;
	}
	//END: list village of user

	$allyId = getInfoFromAllyMember("ally_id", "user_id = ".$userId."");	
	$allyName = getAllyNameByAllyId($allyId->ally_id);
	if(empty($allyName)) {
		$parse['ally_name'] = "";
	}else {
		$parse['ally_name'] = $allyName;
		$parse['checkbox_ally'] = "<input type=\"checkbox\" name=\"chk_ally\" id=\"chk_ally\" />";
	}
	//END: ally
	
	/* xoa thanh`*/
	if($_POST['delete_village'] && $_POST['vilage_name_listbox'] >0)
	{
		$village_id=$_POST['vilage_name_listbox'];
		if(!checkCapitalAdmin($village_id,$userId))
		{
			delete_wg_villages_admin($village_id,$userId);
			deleteAllOfferPunish($village_id);	
			returnWorkersLogin($userId);		
			header("Location:userlist_punish.php?uid=".$userId."");
			exit();
		}
	}
	/* Làm rỗng kho lương */
	if($_POST['empty_rs'] && $_POST['del_population_vilage'] >0)
	{
		$time_now=date("Y-m-d H:i:s",time());
		$sql="UPDATE wg_villages SET rs1=0,rs2=0,rs3=0,time_update_rs1='".$time_now."',
		time_update_rs2='".$time_now."',time_update_rs3='".$time_now."'
		 WHERE id=".$_POST['del_population_vilage'];
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==1)
		{
			$sql="SELECT name FROM wg_villages WHERE id=".$_POST['del_population_vilage'];
			$db->setQuery($sql);
			$villageName=$db->loadResult();
			$reason='Ban quản trị giảm 3 loại tài nguyên (gỗ,sắt,đá) của thành ['.$villageName.'] =0';
			$subject = "Thành ".$villageName." bị giảm tài nguyên";
			sendMessageToUser($user['id'],$userId,$subject,$reason);
			$parse['msg_population']='Giảm tài nguyên thành công !</br>';
		}		
	}	
	/* giam level cong tring */
	if($_POST['delete_building'] && $_POST['del_population_vilage'] >0)
	{
		$count=0;
		$sql="SELECT name FROM wg_villages WHERE id=".$_POST['del_population_vilage'];
		$db->setQuery($sql);
		$villageName=$db->loadResult();
		if($_POST['outside']!='' && is_numeric($_POST['outside']))
		{
			$sql="SELECT * FROM wg_buildings WHERE `index` <=18 
			AND vila_id=".$_POST['del_population_vilage']." ORDER BY `index` ASC";
			$db->setQuery($sql);
			$wg_buildings=NULL;
			$wg_buildings=$db->loadObjectList();		
			if($wg_buildings)
			{
				foreach($wg_buildings as $key=>$v)
				{
					if($v->level >0)
					{
						$count++;
						$v->level=$v->level-$_POST['outside'];
						if($v->level <=0)
						{
							$v->level=0;							
						}						
						$sql="UPDATE wg_buildings SET level=".$v->level." WHERE id=".$v->id;
						$db->setQuery($sql);
						$db->query();
					}								
				}
				if($count >0)
				{
					$sql="DELETE FROM wg_status WHERE village_id=".$_POST['del_population_vilage']." 
								AND type =3 AND status=0";
					$db->setQuery($sql);
					$db->query();
					$reason='Ban quản trị giảm tất cả các công trình bên ngoài thành ,mỗi công trình đã bị trừ đi '.$_POST['outside'].' level';
					$subject = "Thành ".$villageName." bị giảm level";
					sendMessageToUser($user['id'],$userId,$subject,$reason);
					$msg_population='Giảm công trình ngoại thành thành công </br>';
				}
			}
		}
		if($_POST['inside']!='' && is_numeric($_POST['inside']))
		{
			$sql="SELECT * FROM wg_buildings WHERE `index` >18 
			AND vila_id=".$_POST['del_population_vilage']." ORDER BY `index` ASC";
			$db->setQuery($sql);
			$wg_buildings=NULL;
			$wg_buildings=$db->loadObjectList();		
			if($wg_buildings)
			{
				foreach($wg_buildings as $v)
				{
					if($v->level >0 && $v->type_id !=13)
					{
						$count++;
						$v->level=$v->level-$_POST['inside'];
						$string="SET level=".$v->level."";
						if($v->level <=0)
						{
							$string="SET name='',img='',level=0,product_hour=0,cp=0,type_id=0";
							if($v->index==38)
							{
								$string="SET name='City_Wall',img='',level=0,product_hour=0,cp=0";
							}
							if($v->type_id==14)
							{
								$sql="UPDATE wg_users SET embassy=0 WHERE villages_id=".$v->vila_id;
								$db->setQuery($sql);
								$db->query();
							}							
						}						
						$sql="UPDATE wg_buildings ".$string." WHERE id=".$v->id;
						$db->setQuery($sql);
						$db->query();											
					}
				}
				if($count >0)
				{
					$sql="DELETE FROM wg_status WHERE village_id=".$_POST['del_population_vilage']." 
							AND type <=2 AND status=0";
					$db->setQuery($sql);
					$db->query();
					$reason='Ban quản trị giảm tất cả các công trình bên trong thành (ngoại trừ Chợ) ,mỗi công trình đã bị trừ đi '.$_POST['inside'].' level';
					$subject = "Thành ".$villageName." bị giảm level";
					sendMessageToUser($user['id'],$userId,$subject,$reason);
					$msg_population.='Giảm công trình nội thành thành công [ngoại trừ chợ]';
				}
			}
		}
		$parse['msg_population']=$msg_population;
		if($count >0)
		{			
			returnWorkersLogin($userId);				
		}					
	}	
	/* xoa so 1inh cua thanh */
	if($_POST['delete_troop'])
	{
		if($_POST['del_troop_vilage'] !='' && $_POST['percent_troop'] !='')
		{
			$value=getTroopPunish($_POST['del_troop_vilage'],$_POST['percent_troop']);	
			if($value >0)
			{
				$reason='Ban quản trị giảm tất cả số lượng các loại lính ( trong thành + viện trợ bộ lạc + viện trợ thành khác ) ,mỗi loại lính bị giảm đi '.$_POST['percent_troop'].' % số lượng';				
				$sql="SELECT name FROM wg_villages WHERE id=".$_POST['del_troop_vilage'];
				$db->setQuery($sql);
				$subject = "Thành  ".$db->loadResult()." bị giảm ".$_POST['percent_troop']." % số lính";
				sendMessageToUser($user['id'],$userId,$subject,$reason);
			}			
		}
	}
	
	$page = parsetemplate(gettemplate('/admin/userlist_punish'), $parse);
	display2($page,$lang['userlist_punish']);
}
function getArrayOfTroopsPunish($game_config)
{
	global $db;
	$sql="SELECT * FROM wg_troops";
	$db->setQuery($sql);
	$troopList=$db->loadObjectList();
	//luu lai duoi dang mot mang.
	foreach($troopList as $troop)
	{
		$result[$troop->id]['keep_hour']= $troop->keep_hour;		  	
	}
	return $result;
}
function GetHeroVillagePunish($village_id)
{
	global $db;
	$sql="SELECT keep_hour FROM wg_heros WHERE village_id=$village_id AND status=1";
	$db->setQuery($sql);
	$keep_hour=$db->loadResult();
	if($keep_hour)
	{
		return $keep_hour;
	}	
	return 0;
}
function getTroopPunish($vl_id,$percent_troop)
{
	global $db;
	$art=getArrayOfTroopsPunish($game_config);
	$rs =$check=0;	
	
	//linh trong thanh:
	$sql = "SELECT id,troop_id, num 
	FROM wg_troop_villa WHERE num >0 AND wg_troop_villa.village_id=".$vl_id;
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts)
	{
		foreach($ts as $t)
		{
			$t->num=$t->num-intval($t->num*$percent_troop*0.01);
			if($t->num==1)
			{
				$t->num=0;
			}
			$rs += $t->num * $art[$t->troop_id]['keep_hour'];			
		}
		//cap nhat so linh moi'
		foreach($ts as $t)
		{
			$sql="UPDATE wg_troop_villa SET num=".$t->num." WHERE id=".$t->id;
			$db->setQuery($sql);
			$db->query();	
		}
		$check++;
	}	
	//linh tang vien cho bo lac:
	$sql = "SELECT
						wg_attack_troop.id,
						wg_attack_troop.num,
						wg_attack_troop.troop_id
					FROM
						wg_attack ,
						wg_attack_troop ,
						wg_villages
					WHERE
						wg_attack.`status` =  '0' AND
						wg_attack.`type` =  '1' AND
						wg_attack.id =  wg_attack_troop.attack_id AND
						wg_attack.village_defend_id =  wg_villages.id AND
						wg_villages.kind_id >=  '7' AND
						wg_attack.village_attack_id =  '$vl_id'
					GROUP BY
						wg_attack_troop.id";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts)
	{	
		foreach($ts as $t)
		{
			$t->num=$t->num-intval($t->num*$percent_troop*0.01);
			$rs += $t->num * $art[$t->troop_id]['keep_hour'];			
		}
		//cap nhat so linh moi'
		foreach($ts as $t)
		{
			$sql="UPDATE wg_attack_troop SET num=".$t->num." WHERE id=".$t->id;
			$db->setQuery($sql);
			$db->query();	
		}
		$check++;
	}
	
	/* linh tang vien cho thanh khac */
	$sql="SELECT
				wg_attack.village_attack_id,
				wg_attack.village_defend_id,
				wg_attack.id AS id 
			FROM
				wg_attack
			WHERE 
				wg_attack.`status` =  '0' AND 
				wg_attack.`type` =  '1' AND 
				(wg_attack.village_attack_id =".$vl_id." OR
				wg_attack.village_defend_id =".$vl_id.")";
	$db->setQuery($sql);
	$attackList=$db->loadObjectList();
	if($attackList)
	{
		require_once('../includes/function_troop.php');
		$arrayTroop=getArrayOfTroops();		
		foreach($attackList as $attack)
		{
			GetListAttackTroopAdmin($attack->id,$percent_troop);
			$troopKeep=getTroopKeep($attack->village_defend_id,$arrayTroop);
			updateTroopKeep($attack->village_defend_id,$troopKeep);	
		}
		$check++;
	}
	
	//cap nhat lai suc an cua linh 
	if($check >0)
	{	
		//hero trong thanh:
		$rs+=GetHeroVillagePunish($vl_id);	
	
		//hero thanh khac den vien tro:
		$sql = "SELECT
						wg_heros.keep_hour
					FROM
						wg_attack ,
						wg_attack_hero ,
						wg_heros
					WHERE
						wg_attack_hero.attack_id =  wg_attack.id AND
						wg_attack_hero.hero_id =  wg_heros.id AND
						wg_attack.`type` =  '1' AND
						wg_attack.`status` =  '0' AND
						wg_attack.village_defend_id =$vl_id
					GROUP BY
						wg_heros.id";
		$db->setQuery($sql);
		$hs = $db->loadObjectList();
		if($hs)
		{
			foreach($hs as $h)
			{
				$rs += $h->keep_hour;
			}
		}
		
		//linh thanh khac den vien tro:
		$sql = "SELECT
							wg_attack_troop.troop_id,
							wg_attack_troop.num
						FROM
							wg_attack ,
							wg_attack_troop
						WHERE
							wg_attack_troop.attack_id =  wg_attack.id AND
							wg_attack.`status` =  '0' AND
							wg_attack.`type` =  '1' AND
							wg_attack.village_defend_id =  '$vl_id'
						GROUP BY
							wg_attack_troop.id";
		$db->setQuery($sql);
		$ts = $db->loadObjectList();
		if($ts)
		{
			foreach($ts as $t)
			{
				$rs += $t->num * $art[$t->troop_id]['keep_hour'];
			}
		}
		
		//hero cua thanh dang di va ve:
		$sql = "SELECT
						wg_heros.keep_hour
					FROM
						wg_attack ,
						wg_attack_hero ,
						wg_heros
					WHERE
						wg_attack_hero.attack_id =  wg_attack.id AND
						wg_attack_hero.hero_id =  wg_heros.id AND
						(wg_attack.`type` =  '2' OR wg_attack.`type` =  '5') AND
						wg_attack.`status` =  '0' AND
						wg_attack.village_attack_id = '$vl_id'
					GROUP BY
						wg_heros.id";
		$db->setQuery($sql);
		$hs = $db->loadObjectList();
		if($hs){
			foreach($hs as $h){
				$rs += $h->keep_hour;
			}
		}
		
		//linh cua thanh dang di va ve:
		$sql = "SELECT
							wg_attack_troop.troop_id,
							wg_attack_troop.num
						FROM
							wg_attack ,
							wg_attack_troop
						WHERE
							wg_attack_troop.attack_id =  wg_attack.id AND
							wg_attack.`status` =  '0' AND
							(wg_attack.`type` =  '2' OR wg_attack.`type` =  '5') AND
							wg_attack.village_attack_id =  '$vl_id'
						GROUP BY
							wg_attack_troop.id";
		$db->setQuery($sql);
		$ts = $db->loadObjectList();
		if($ts){
			foreach($ts as $t){
				$rs += $t->num * $art[$t->troop_id]['keep_hour'];
			}
		}
		
		//hero tang vien cho bo lac:
		$sql = "SELECT
						wg_heros.keep_hour
					FROM
						wg_attack ,
						wg_villages ,
						wg_heros ,
						wg_attack_hero
					WHERE
						wg_attack.village_defend_id =  wg_villages.id AND
						wg_villages.kind_id >=  '7' AND
						wg_attack.id =  wg_attack_hero.attack_id AND
						wg_attack_hero.hero_id =  wg_heros.id AND
						wg_attack.`status` =  '0' AND
						wg_attack.village_attack_id =  '$vl_id'
					GROUP BY
						wg_heros.id";
		$db->setQuery($sql);
		$hs = $db->loadObjectList();
		if($hs){
			foreach($hs as $h){
				$rs += $h->keep_hour;
			}
		}
		$sql="UPDATE wg_villages SET troop_keep=".$rs." WHERE id=".$vl_id;
		$db->setQuery($sql);
		$db->query();
	}
	return $check;
}
//Lay danh sach linh cua mot attack.
function GetListAttackTroopAdmin($attack_id,$percent_troop)
{
	global $db;
	$sql="SELECT id,num FROM wg_attack_troop WHERE status=0 AND attack_id=".$attack_id;
	$db->setQuery($sql);
	$troopList=NULL;
	$troopList=$db->loadObjectList();
	if($troopList)
	{	
		foreach($troopList as $t)
		{
			$t->num=$t->num-intval($t->num*$percent_troop*0.01);
			if($t->num==1)
			{
				$t->num=0;
			}
			//cap nhat so linh moi'
			$sql="UPDATE wg_attack_troop SET num=".$t->num." WHERE id=".$t->id;
			$db->setQuery($sql);
			$db->query();
		}	
	}
	return true;
}
function delete_wg_villages_admin($village_id,$userId)
{
	global $db;	
	include('../includes/function_troop.php');	
	reinforceReturn($village_id,time());
	delete_wg_troop_villa_admin($village_id);
	delete_wg_resource_sends_admin($village_id);	
	delete_wg_resource_orders_admin($village_id);	
	delete_wg_buildings_admin($village_id);	
	update_wg_registration_village_list_admin($village_id);
	update_wg_villages_map_admin($village_id);	

	$sql = "DELETE FROM wg_villages WHERE id=".$village_id;	
	$db->setQuery ($sql);
	$db->query();	
	
	$sql = "UPDATE wg_users SET sum_villages=sum_villages-1 WHERE id=".$userId;	
	$db->setQuery ($sql);
	$db->query();
}
function delete_wg_resource_sends_admin($villageId)
{
	global $db;
	$sql = "DELETE FROM wg_resource_sends 
				   WHERE village_id_from=".$villageId." OR village_id_to=".$villageId;
	$db->setQuery($sql);
	$db->query();	
}
function delete_wg_troop_villa_admin($villageId)
{
	global $db;
	$sql = "DELETE FROM wg_troop_villa WHERE village_id=".$villageId;
	$db->setQuery($sql);
	$db->query();	
}
function delete_wg_resource_orders_admin($villageId)
{
	global $db;
	$sql = "DELETE FROM wg_resource_orders WHERE village_id=".$villageId;
	$db->setQuery($sql);
	$db->query();	
}
function delete_wg_buildings_admin($villageId)
{
	global $db;
	$sql = "DELETE FROM wg_buildings WHERE vila_id=".$villageId;
	$db->setQuery ($sql);			
	$db->query();		
}
function update_wg_registration_village_list_admin($villageId)
{
	global $db;	
	$sql = "UPDATE wg_registration_village_list SET registed=0 WHERE village_id=".$villageId;
	$db->setQuery ($sql);
	$db->query();
}
function update_wg_villages_map_admin($villageId)
{
	global $db;	
	$sql = "UPDATE wg_villages_map SET user_id=0 WHERE id=".$villageId;		
	$db->setQuery ($sql);
	$db->query();
}
function checkCapitalAdmin($villages_id,$userId)
{
	global $db;
	$sql="SELECT villages_id FROM wg_users WHERE id=".$userId." AND villages_id=".$villages_id;
	$db->setQuery($sql);
	$result=$db->loadResult();
	if($result !='')
	{
		return true;
	}
	return false;
}
?>