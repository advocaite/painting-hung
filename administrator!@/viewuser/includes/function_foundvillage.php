<?php
/**
 * Kiem tra mot lang con trong va co du settler de tao lang moi hay khong.
 */
function FoundVillageStatus($village_found_id){

	global $db, $lang, $wg_village,$nationTroopList;
	require_once('includes/nation_troop.php');
	includelang("found_village");
	$parse=$lang;
	$sql="SELECT user_id FROM wg_villages WHERE id=$village_found_id";
	$db->setQuery($sql);
	if($db->loadResult()==0){
		//-> lang nay con trong co the tao lang moi duoc.
		//Kiem tra co du tho hay khong.
		include_once("function_troop.php");
		
		$sumTroop=GetSumTroopInVillage($nationTroopList[$wg_village->nation_id]['type_name10']);
		$parse['sum_settlers']=$sumTroop;
		$parse['village_found_id']=$village_found_id;
		$rallyPoint=GetRallyPoint($wg_village->id);
		if($sumTroop>=5)
		{
			if(!$rallyPoint)
			{
				return parsetemplate(gettemplate("found_village_status_rally"), $parse);
			}else{
				return parsetemplate(gettemplate("found_village_status_ok"), $parse);
			}					
		}
		else
		{
			return parsetemplate(gettemplate("found_village_status_c"), $parse);
		}
	}
	return false;
}

/**
 * @author Le Van Tu
 * @des xu ly khi tho di tao lang moi da toi dich
 * @param object status
 */
function executeSendWorkers($status){
	$attack=getAttack($status->object_id);
	$village=getVillage($attack->village_attack_id);
	//Kiem tra xem co lang nao toi tao lang moi truoc ko:
	$village_found=getVillageFound($attack->village_defend_id);
	if($village_found->user_id==0){
		//lang chua co chu:
		SetAttackStatus($attack->id);
		SetAttackTroopStaus($attack->id);		
		include_once("function_active.php");
		//Tang dan so cho user chiem lang:
		ChangeUserPopulationSumVillage($village->user_id, 2, 1);
		KhoiTaoVillage($attack->village_defend_id, $village->user_id, "", $village->nation_id,"NewName");
		global $db;
		$sql="SELECT lumber,clay,iron,crop FROM wg_plus WHERE user_id=".$village->user_id;
		$db->setQuery($sql);
		$db->loadObject($wg_plus);
		$lumber=1;
		$clay=1;
		$iron=1;
		$crop=1;
		if($wg_plus)
		{
			$time_check=strtotime(date("Y-m-d H:i:s",time()));
			if(strtotime($wg_plus->lumber) > $time_check )	
			{
				$lumber=1.25;
			}
			if(strtotime($wg_plus->clay) > $time_check )	
			{
				$clay=1.25;
			}
			if(strtotime($wg_plus->iron) > $time_check )	
			{
				$iron=1.25;
			}
			if(strtotime($wg_plus->crop) > $time_check )	
			{
				$crop=1.25;
			}
			$sql="UPDATE wg_villages SET krs1=".$lumber.",krs2=".$clay.",krs3=".$iron.",krs4=".$crop."
			 WHERE id=".$attack->village_defend_id;
			$db->setQuery($sql);
			$db->query();		
		}
		KhoiTaoTroopResearch($attack->village_defend_id, $village->nation_id);
		UpdateRegVillageList($attack->village_defend_id);
		InsertDataBuilding_New($attack->village_defend_id, $village->user_id, $village_found->kind_id);
		AddChildForVillage($village->id, $attack->village_defend_id);
		reportFoundVillage($status, $village, $village_found, true);
	}else{
		//lang da co chu:
		FoundVillageBack($attack, $status, $village, $village_found);
		reportFoundVillage($status, $village, $village_found, false);
	}
}

/**
 * @author Le Van Tu
 * @des Tao va gui report cho user di tao lang moi
 * @param
 * @return void
 */
function reportFoundVillage($status, $villageAttack, $villageDefend, $win){
	global $lang;
	includelang('rally_point');
	$parse=$lang;	
	$listTroop=GetListTroopVilla($villageAttack);
	
	for($i=0; $i<11; $i++){
		$parse['icon'.($i+1)]=$listTroop[$i]->icon;
		$parse['title'.($i+1)]=$lang[$listTroop[$i]->name];
		$parse['t'.($i+1)]=0;
		$parse['class'.($i+1)]="c";		
	}

	$parse['t10']=5;
	$parse['class10']="";

	$parse['t12']=0;
	$parse['class12']="c";	
	$parse['title12']=$lang['hero'];;
	$parse['icon12']="images/icon/hero4.ico";
	if($win){
		$parse['info_return']=$lang['da_tao_duoc_lang'];
	}else{
		$parse['info_return']=$lang['thanh_da_co_chu'];
	}
	
	$parse['village_name']=$villageAttack->name;
	$parse['x']=$villageAttack->x;
	$parse['y']=$villageAttack->y;
	$parse['x2']=$villageDefend->x;
	$parse['y2']=$villageDefend->y;
	$title=$lang['report_found_village_title'];
	
	$content=parsetemplate(gettemplate("report_found_village"), $parse);
	
	InsertReport($villageAttack->user_id, $title, $status->time_end, $content, REPORT_ATTACK);
} 

/**
*	Kiem tra lang toi lap lang moi som nhat.
*/
function GetFistFoundVillage($village_defend_id, $timeEnd){
	global $db, $wg_village;
	$sql="SELECT * FROM wg_attack WHERE village_defend_id=$village_defend_id AND wg_attack.type=6";
	$db->setQuery($sql);
	$attackList=null;
	$attackList=$db->loadObjectList($attackList);
	if($attackList){
		if(count($attackList)>1){
			//co nhieu lang.
			$i=0;			
			foreach($attackList as $attack){
				$sql="SELECT id, time_end FROM wg_status WHERE object_id=$attack->id AND wg_status.type=15";
				$db->setQuery($sql);
				$db->loadObject($status);
				$timeEndOther=strtotime($status->time_end);
				if($timeEnd>$timeEndOther){
					//lang nay toi truoc.
					$timeEnd=$timeEndOther;
					$result['village_id']=$attack->village_attack_id;
					$result['status_id']=$status->id;
				}
			}
			return $result;
		}else{
			$result['village_id']=$village_id;
			return $result;
		}
	}
	globalError2("Error!!!GetFistFoundVillage");
} 

/**
*	Kiem tra mot lang con trong hay khong (neu con trong tra ve toa do cua lang).
*/
function getVillageFound($village_found_id){
	global $db;
	$sql="SELECT * FROM wg_villages_map WHERE id=$village_found_id";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	return $village;
}


/**
 * Kiem Tra xem co the tao duoc bao nhieu lang moi ung voi diem danh vong dang co.
 * return 'true' or 'false'
 */
function CheckCulturePoint($village){
	global $db, $game_config;
	$sumCulturePoint=GetSumCulturePoint($village->user_id);
	$cpRequire=checkPointToAddVillage($village->user_id);
	return $sumCulturePoint >= $cpRequire?true:false;
}


/**
 * Kiem tra xem co du tho khong.
 */
function CheckSettlers(){
	global $db, $wg_village, $nationTroopList;
	$sumTroop=GetSumTroopInVillage($nationTroopList[$wg_village->nation_id]['type_name10']);
	if($sumTroop>=5){
		return true;
	}
	return false;
}

/**
 * Tinh tong diem danh vong cua mot user.
 */
function GetSumCulturePoint($user_id){
	global $db;
	$sql="SELECT cp FROM wg_villages WHERE user_id=$user_id";
	$db->setQuery($sql);
	$villageList=null;
	$villageList=$db->loadObjectList();
	if($villageList){
		foreach($villageList as $village){
			$sum+=$village->cp;
		}
		return $sum;
	}
	return false;
}


/**
 * Lay toc do cua settler
 */
function GetSettlerSpeed(){
	global $db, $wg_village, $game_config, $nationTroopList;
	$troop_id=$nationTroopList[$wg_village->nation_id]['type_name10'];
	$sql="SELECT speed FROM wg_troops WHERE id='$troop_id'";	
	$db->setQuery($sql);
	$db->loadObject($troop);
	if($troop){
		return $troop->speed*$game_config['k_speed'];
	}
	return false;
}



/**
*	xu ly su kien di tao lang moi nhung da co lang khac toi truoc.
* 	cho tho va tai nguyen quay ve.
*/
function FoundVillageBack($attack, $status, $village, $village_found){
	//Dua tai nguyen ve lai lang
	$rsSendID=InsertSendRS($village_found->id, $village->id, 750, 750, 750, 750);
	InsertBountyStatus($village->id, $rsSendID, $status->time_end, date("Y-m-d H:i:s", strtotime($status->time_end)+$status->cost_time), $status->cost_time);
	
	//keo quan ve:
	InsertStatus($village->id, $attack->id, $status->time_end, date("Y-m-d H:i:s", strtotime($status->time_end)+$status->cost_time), $status->cost_time, 10);
}
?>
