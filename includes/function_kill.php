<?php

/*
	Plugin Name: includes
	Plugin URI: 
	Description: the level of consumption corresponding to the number of people and soldiers
	Version: 
	Author: Manhhx
	Author URI:
*/

/**
* @Author: ManhHX
* @Des: get village info
* @param: $vId village id
* @return: $objRes:object village
*/
function getVillageAllInfoById($vId){
	global $db;	
	$query="SELECT * FROM wg_villages WHERE id=$vId";
	$db->setQuery($query);	
	$objRes = $db->loadObjectList();
	
	return $objRes;
}

/**
* @Author: ManhHX
* @Des: get troops
* @param: $vId village id
* @return: $objRes:object troops
*/
function getTroopsByNationId($nId){
	global $db;	
	$query="SELECT * FROM wg_troops WHERE nation_id=$nId ORDER BY keep_hour DESC";
	$db->setQuery($query);	
	$objRes = $db->loadObjectList();
	
	return $objRes;
}

/**
* @Author: ManhHX
* @Des: get troop village by vilage id
* @param: $vId village id
* @return: $objRes:object troop village
*/
function getTroopVilageByVilageId($vId){
	global $db;	
	$query="SELECT * FROM wg_troop_villa WHERE village_id=$vId";
	$db->setQuery($query);	
	$objRes = $db->loadObjectList();
	
	return $objRes;
}

/**
* @Author: ManhHX
* @Des: execute the level of consumption corresponding
* to the number of people and soldiers
* @param: $vId village id
* @return: null
*/
function executeConsumptionPeopleSoldiers($vId){
	global $db, $wg_village, $wg_buildings;	
	include_once("function_resource.php");
	include_once("function_troop.php");
	
	$vOject = getVillageAllInfoById($vId);
	//$sumCun = $vOject[0]->troop_keep + $vOject[0]->workers;
	$workers = $vOject[0]->workers;
	$totalCrop = GetSpeedIncreaseCrop($wg_buildings);
	$nationId = $vOject[0]->nation_id;
	
	$arrTroops = getTroopsByNationId($nationId);
	$arrTroopVillage = getTroopVilageByVilageId($vId);
	
	$soldierKeep = 0;
	$arrCheck = array();
	$tmpObj = NULL;
	$is_Hero = true;
	$indexTroop = 0;
	$productionOfHero = 0;
	if($arrTroops){
		foreach($arrTroops as $index1 => $troop){
			foreach($arrTroopVillage as $index2 => $troopVillage){
				if( (!$troopVillage->troop_id) && ($is_Hero) ){ //truong hop la hero	
					//Lay suc an cua tuong
					$query="SELECT keep_hour FROM wg_heros WHERE id=$troopVillage->hero_id";
					$db->setQuery($query);
					$objSuperSoldier = $db->loadObjectList();
					$productionOfHero = $objSuperSoldier[0]->keep_hour;
					
					$soldierKeep = $soldierKeep + ($troopVillage->num * $productionOfHero );
					$arrCheck[$indexTroop]["production"] = $productionOfHero;
					$tmpObj[$indexTroop] = 	$troopVillage;
					$indexTroop++;
					$is_Hero = false;
				}
				elseif($troop->id == $troopVillage->troop_id ){
					$soldierKeep = $soldierKeep + ($troopVillage->num * $troop->keep_hour );
					$arrCheck[$indexTroop]["production"] = $troop->keep_hour;
					$tmpObj[$indexTroop] = 	$troopVillage;
					$indexTroop++;
					break;
				}
			}
		}
	}
	//Cap nhat lai troop keep cho linh. Vi khi danh nhau linh chet Tu chua cap nhat	
	/*$updSql="UPDATE wg_villages SET troop_keep=$soldierKeep WHERE id=$vId";		
	$db->setQuery($updSql);		
	if(!$db->query()){
		globalError('Error executeConsumptionPeopleSoldiers function update sql');
	}*/
	
	$sumCun = $workers + $soldierKeep; // Suc an cua lang
	$productionPerHour = $totalCrop - $sumCun; // Suc san xuat trong 1 gio cua lang
	$overRice = 0;
	$objTroopVillageHero = NULL;
	$troopKeepSubtract = 0;
	//echo "<pre>"; print_r($tmpObj);	
	if($productionPerHour < 0){ // So sanh Suc san xuat trong 1 gio nho hon 0
		//Kiem tra luc resource cua lua ve khong tai thoi diem nao
		$timeOff = strtotime($vOject[0]->time_update_rs4);		
		$timeOn = time();		
		$rs4 = $vOject[0]->rs4;
		$timeCheck = $timeOff + $rs4 ;
		
		if($timeCheck > $timeOn){ //Thoi diem trong kho(resource) ve 0 vuot qua thoi diem hien tai
			return;
		}
		//Tra quan cho lang den ho tro
		reinforceReturn($vId, $timeCheck);
		
		$overRice = $sumCun - $totalCrop; //Suc an vuot qua tong suc san xuat cua lang					
		//if($rs4 <= 0){ //Khi trong resource khong con thi giet linh					
			foreach($tmpObj as $k => $v){
				$kSoldiers =  $v->num;
				$kDieSoldiers =  $v->die_num;								
				//Khi gia tri thieu nay van lon hon suc an cua linh lon nhat thi giet linh lon nhat
				while( ($overRice >= $arrCheck[$k]["production"]) && ($kSoldiers > 0) ) {	
					if(!$v->hero_id){ //Neu khong phai hero thi giet truoc	
						$overRice = $overRice - $arrCheck[$k]["production"];
						$kSoldiers = $kSoldiers - 1;
						$kDieSoldiers = $kDieSoldiers + 1;
						$troopKeepSubtract = $troopKeepSubtract + $arrCheck[$k]["production"];
					}else{ //Lay id cua hero de kiem tra sau
						$objTroopVillageHero = $v;
						break;
					}					
				}	
				
				if($v->num != $kSoldiers){ // Co giet linh
					$updSql="UPDATE wg_troop_villa SET num=$kSoldiers, die_num=$kDieSoldiers WHERE id=$v->id";		
					$db->setQuery($updSql);		
					if(!$db->query()){
						globalError('Error executeConsumptionPeopleSoldiers function update sql');
					}
				}
			} //End foreach
		//} //Khi trong resource khong con thi giet linh
	} //So sanh Suc san xuat trong 1 gio nho hon 0
	
	//Khi $overRice van con va co hero thi giet hero
	if($overRice && $objTroopVillageHero){
		$troopKeepSubtract = troopKeepSubtract + $productionOfHero;
		$updSql="UPDATE wg_troop_villa SET num=0, die_num=$objTroopVillageHero->num WHERE id=$objTroopVillageHero->id";		
		$db->setQuery($updSql);		
		if(!$db->query()){
			globalError('Error executeConsumptionPeopleSoldiers function update sql');
		}
	}
		
	//Cap nhat lai suc an cua linh
	$updSql="UPDATE wg_villages SET troop_keep=(troop_keep-$troopKeepSubtract) WHERE id=$vId";		
	$db->setQuery($updSql);		
	if(!$db->query()){
		globalError('Error executeConsumptionPeopleSoldiers function update sql');
	}
}

?>