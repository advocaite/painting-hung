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
function executeSubTroop($vId, $numTroopKill){
	global $db;	
	//include_once("function_resource.php");
	include_once("function_troop.php");
	//die("abc");
	$vOject = getVillageAllInfoById($vId);	
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
					
					$arrCheck[$indexTroop]["production"] = $productionOfHero;					
					$tmpObj[$indexTroop] = 	$troopVillage;
					$indexTroop++;
					$is_Hero = false;					
				}
				elseif($troop->id == $troopVillage->troop_id ){					
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
			
	
	$troopKeepSubtract = 0;
	//$numKill = 100;	
	if($tmpObj){
		foreach($tmpObj as $k => $v){
			$kSoldiers =  $v->num;
			$kDieSoldiers =  $v->die_num;								
			//Khi gia tri thieu nay van lon hon suc an cua linh lon nhat thi giet linh lon nhat
			while( ($kSoldiers > 0) && ($numTroopKill > 0) ) {	
				if(!$v->hero_id){ //Neu khong phai hero thi giet truoc					
					$kSoldiers = $kSoldiers - 1;
					$kDieSoldiers = $kDieSoldiers + 1;
					$troopKeepSubtract = $troopKeepSubtract + $arrCheck[$k]["production"];
					$numTroopKill--;
				}else{ //Lay id cua hero de kiem tra sau				
					break;
				}					
			}	
			
			if($v->num != $kSoldiers){ // Co giet linh
				$updSql="UPDATE wg_troop_villa SET num=$kSoldiers, die_num=$kDieSoldiers WHERE id=$v->id";		
				$db->setQuery($updSql);		
				if(!$db->query()){
					globalError2('Error executeConsumptionPeopleSoldiers function update sql');
				}
			}
		} //End foreach
	}			
			
	//Cap nhat lai suc an cua linh
	$updSql="UPDATE wg_villages SET troop_keep=(troop_keep-$troopKeepSubtract) WHERE id=$vId";		
	$db->setQuery($updSql);		
	if(!$db->query()){
		globalError2('Error executeConsumptionPeopleSoldiers function update sql');
	}
}
/*
* @Author: tdnquang
* @Des: xoa lang cua user bi phat
* @param: + $villageId: id cua village
* @return: 
*/
function deleteVillage($villageId)
{
	global $db;
	$sql="DELETE FROM wg_villages WHERE id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_attack
	$sql="DELETE FROM wg_attack WHERE village_attack_id = ".$villageId." OR village_defend_id = ".$villageId."";
	$db->setQuery($sql);
	$db->query();
	//wg_buildings
	$sql="DELETE FROM wg_buildings WHERE vila_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_enforce
	$sql="DELETE FROM wg_enforce WHERE vila_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();		
	//wg_heros
	$sql="DELETE FROM wg_heros WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();	
	//wg_merchant_villa
	$sql="DELETE FROM wg_merchant_villa WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_registration_village_list
	$sql="DELETE FROM wg_registration_village_list WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_resource_orders
	$sql="DELETE FROM wg_resource_orders WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_resource_sends
	$sql="DELETE FROM wg_resource_sends WHERE village_id_from = ".$villageId." OR village_id_to = ".$villageId."";
	$db->setQuery($sql);
	$db->query();
	//wg_status
	$sql="DELETE FROM wg_status WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_troop_armour
	$sql="DELETE FROM wg_troop_armour WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_troop_items
	$sql="DELETE FROM wg_troop_items WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_troop_researched
	$sql="DELETE FROM wg_troop_researched WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_troop_train
	$sql="DELETE FROM wg_troop_train WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//wg_troop_villa
	$sql="DELETE FROM wg_troop_villa WHERE village_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
	//update wg_users
	$sql="UPDATE wg_users SET sum_villages = sum_villages - 1 WHERE villages_id = ".$villageId;
	$db->setQuery($sql);
	$db->query();
}
/*
* @Author: tdnquang
* @Des: lay name of village by id
* @param: $villageId: id cua village
* @return: name of village
*/
function getVillageNameByVillageId($villageId)
{
	global $db;
	$sql="SELECT name FROM wg_villages WHERE id = ".$villageId;	
	$db->setQuery($sql);
	$query=null;
	$db->loadObject($query);
	return $query->name;
}
/*
* @Author: tdnquang
* @Des: xoa lien minh cua user bi phat
* @param: + $userId: id cua user
* @return: 
*/
function deleteAlly($userId)
{
	global $db;
	$ally = getInfoFromAllyMember("ally_id", "user_id = ".$userId."");
	$allyId = $ally->ally_id;
	//kiem tra cac member con trong lien minh ko
	$sql = "SELECT count(id) FROM wg_ally_members WHERE ally_id = ".$allyId." AND right_ = 1";
	$db->setQuery($sql);
	$sumAlly=(int)$db->loadResult();
	if($sumAlly > 1){//neu con cac thanh vien trong lien minh
		//neu user bi phat la minh chu cua lien minh do
		$sql = "SELECT user_id FROM wg_allies WHERE user_id = ".$userId;
		$db->setQuery($sql);
		$allyFounder = null;
		$db->loadObject($allyFounder);		
		if(!empty($allyFounder)){//neu la minh chu
			// Chon minh chu moi trong ally member
			$sql = "SELECT user_id FROM wg_ally_members WHERE ally_id=".$allyId." 
					AND user_id != ".$allyFounder->user_id." AND right_ = 1 ORDER BY RAND() LIMIT 1";
			$db->setQuery($sql);
			$newAllyFounder = null;
			$db->loadObject($newAllyFounder);						
			
			//cap nhat minh chu moi cho ally
			$sql = "UPDATE wg_ally_members
							SET right_=1,privilege = '11111111', position_name = 'Minh chủ'
							WHERE user_id=".$newAllyFounder->user_id;
			$db->setQuery($sql);
			$db->query();
			
			$sql = "UPDATE wg_allies SET user_id = ".$newAllyFounder->user_id." WHERE id=".$allyId;
			$db->setQuery($sql);
			$db->query();
			//delete user member
			$sql = "DELETE FROM wg_ally_members WHERE user_id=".$userId." AND right_ = 1";
			$db->setQuery($sql);
			$db->query();
			//update table wg_users
			$sql="UPDATE wg_users SET alliance_id = 0 WHERE id=".$userId;
			$db->setQuery($sql);
			$db->query();
		}else{//khong phai la minh chu
			$sql = "DELETE FROM wg_ally_members WHERE user_id = ".$userId." AND right_ = 1";
			$db->setQuery($sql);
			$db->query();
			// update table wg_users
			$sql="UPDATE wg_users SET alliance_id = 0 WHERE id = ".$userId;
			$db->setQuery($sql);
			$db->query();
		}		
	}elseif($sumAlly == 1){//khong con thanh vien trong lien minh
		//xoa user do
		$sql = "DELETE FROM wg_ally_members WHERE user_id = ".$userId." AND right_ = 1";
		$db->setQuery($sql);
		$db->query();
		//xoa tat ca nhung loi moi chua duoc chap nhan cua user do
		$sql = "DELETE FROM wg_ally_members WHERE ally_id = ".$allyId." AND right_ = 0";
		$db->setQuery($sql);
		$db->query();
		// update table wg_users
		$sql="UPDATE wg_users SET alliance_id = 0 WHERE id = ".$userId;
		$db->setQuery($sql);
		$db->query();
		// delete wg_allies
		$sql = "DELETE FROM wg_allies WHERE id = ".$allyId;
		$db->setQuery($sql);
		$db->query();
		// delete wg_ally_relation
		$sql = "DELETE FROM wg_ally_relation WHERE ally_id_1 = ".$allyId." OR ally_id_2 = ".$allyId;
		$db->setQuery($sql);
		$db->query();
		
	}	
}

/*
* @Author: tdnquang
* @Des: tru dan so ngoai thanh cua user (giam level tai nguyen)
* @param: + $villageId: id cua village
* @return: 
*/
function subtractOutsideCitadel($villageId, $percentage)
{
	global $db;
	$sql="UPDATE wg_buildings SET level = level/$percentage WHERE vila_id = ".$villageId." AND type_id <= 4";	
	$db->setQuery($sql);
	return $db->query();
}

/*
* @Author: tdnquang
* @Des: tru dan so noi thanh thanh cua user (giam level cong trinh)
* @param: + $villageId: id cua village
* @return: 
*/
function subtractInsideCitadel($villageId, $percentage)
{
	global $db;
	$sql="UPDATE wg_buildings SET level = level/$percentage WHERE vila_id = ".$villageId." AND type_id >= 5";	
	$db->setQuery($sql);
	return $db->query();
}


/*
* @Author: tdnquang
* @Des: get level of building or rs
* @param: + $villageId: id cua village
* @return: level of building or rs
*/
function getLevelOfBuilding($villageId)
{
	global $db;
	$sql="SELECT id,level,type_id FROM wg_buildings WHERE vila_id = ".$villageId."";	
	$db->setQuery($sql);	
	$levels = $db->loadObjectList();
	return $levels;
}
/*
* @Author: tdnquang
* @Des: update worker
* @param: + $villageId: id cua village
		  + $worker: so luong worker
* @return: 
*/

function updateWorker($villageId, $worker)
{
	global $db;
	$sql="UPDATE wg_villages SET workers = workers - ".$worker." WHERE id=".$villageId." AND workers > 2";
	$db->setQuery($sql);
	$db->query();
}

/*
* @Author: tdnquang
* @Des: update population
* @param: + $villageId: id cua village
		  + $population: so luong popluation
* @return: 
*/

function updatePopulation($userId, $population)
{
	global $db;
	$sql="UPDATE wg_users SET population = population - ".$population." WHERE id=".$userId." AND population > 2";
	$db->setQuery($sql);
	$db->query();
}
/*
* @Author: tdnquang
* @Des: gui message den user
* @param: + $from_id: id cua user gui
		  + $to_id: id cua user nhan
		  + $subject: tieu de thu
		  + $content: noi dung thu
* @return: 
*/
function sendMessageToUser($from_id, $to_id, $subject, $content)
{
	global $db;
	$sql ="INSERT INTO wg_messages(id_user, from_id, to_id, times, status, subject, content)
	   VALUES(".$to_id.",".$from_id.",0,'".date("Y-m-d H:i:s")."',0,'".$subject."','".$content."')";
	$db->setQuery($sql);
	$db->query();
}

/*
* @Author: tdnquang
* @Des: tru tong so lang cua user khi bi xoa lang
* @param: + $userId: id cua user
* @return: 
*/
function subSumVillage($userId)
{
	global $db;
	$sql="UPDATE wg_users SET sum_villages = sum_villages - 1 WHERE id=".$userId;
	$db->setQuery($sql);
	$db->query();
}

/*
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
function subWorker($id,$level)
{
	global $db, $game_config;	
	//require_once 'func_build.php';
	$K=$game_config['k_game'];	
	$sql="SELECT name,vila_id,type_id FROM wg_buildings WHERE id=$id";
	$db->setQuery($sql);
	$building=null;
	$db->loadObject($building);
	$village=$building->vila_id;
	$ct=(1+($building->level-1)*0.05)/(1+$building->level*0.05); // level o day chinh la level truoc khi pha'
	if($building->type_id<=4)// 4 loai tai  nguyen o ngoai thanh
	{
		$cp=CPn_1($level);
		$product_hour_new=KTn_res($level)*$K;
		$Workers_1=Worker_1($level);
		if($building->type_id==4)//"Cropland")
		{
			$Workers_1=Worker_4($level);
		}
	}
	/*------------------Worker-Cp-ProducHour ------------------------------*/
	elseif($building->type_id==12)// main buiding
	{
		$cp=CPn_1($level);
		$product_hour_new=MBn($level)*100;
		$Workers_1=Worker_3($level);
	}
	elseif($building->type_id==15)//"Cranny")
	{
		$cp=CPn_1($level);
		$product_hour_new=SCn_eco_cra($level);
		$Workers_1=Worker_6($level);
	}
	elseif($building->type_id==14)// Embassy
	{
		$cp=CPn_3($level);
		$Workers_1=Worker_3($level);
		$product_hour_new=SCn_Embassy($level);
	}
	elseif($building->type_id==13)//"Marketplace"
	{
		$cp=CPn_2($level);
		$Workers_1=Worker_3($level);
		$product_hour_new=SCn_Marketplace($level);
	}
	elseif($building->type_id==20)//"Trade_Office")
	{
		$cp=CPn_2($level);
		$Workers_1=Worker_3($level);
		$product_hour_new=SCn_Trade_Office($level);
	}
	elseif($building->type_id==31)//"Academy")
	{
		$cp=CPn_3($level);
		$Workers_1=Worker_2($level);
		$product_hour_new=SCn_Embassy($level);
	}
	elseif($building->type_id==28)//"Barracks")
	{
		$cp=CPn_1($level);
		$Workers_1=Worker_2($level);
		$product_hour_new=SCn_Barracks($level);
	}
	elseif($building->type_id==24)//"Blacksmith")
	{
		$cp=CPn_1($level);
		$Workers_1=Worker_2($level);
		$product_hour_new=SCn_Embassy($level);
	}
	elseif($building->type_id==36)//"City_Wall")
	{
		$cp=CPn_1($level);
		$Workers_1=Worker_5($level);
		$product_hour_new=SCn_City_Wall($level);
	}
	elseif($building->type_id==35)//"Hero_Mansion")
	{
		$cp=CPn_1($level);
		$Workers_1=Worker_1($level);
		$product_hour_new=SCn_Embassy($level);
	}
	elseif($building->type_id==27)//"Rally_Point")
	{
		$cp=CPn_1($level);
		$Workers_1=Worker_5($level);
		$product_hour_new=SCn_Embassy($level);
	}
	elseif($building->type_id==29)//"Stable")
	{
		$Workers_1=Worker_2($level);
		$cp=CPn_1($level);
		$product_hour_new=SCn_Embassy($level);
	}
	elseif($building->type_id==30)//"Workshop")
	{
		$Workers_1=Worker_2($level);
		$cp=CPn_2($level);
		$product_hour_new=SCn_Embassy($level);
	}
	elseif($building->type_id==6)//"Sawmill")
	{
		$cp=CPn_1($level);
		$Workers_1=Worker_1($level);
		$product_hour_new=SCn_Brickyard($level);
		$sql="UPDATE wg_villages SET krs1=(krs1*".$ct.") WHERE id=".$village."";
		$db->setQuery($sql);
		$db->query();
	}
	elseif($building->type_id==7)//"Brickyard")
	{
		$cp=CPn_1($level);
		$Workers_1=Worker_1($level);
		$product_hour_new=SCn_Brickyard($level);
		$sql="UPDATE wg_villages SET krs2=(krs2*".$ct.") WHERE id=".$village."";
		$db->setQuery($sql);
		$db->query();
	}
	elseif($building->type_id==8)//"Iron_Foundry")
	{
		$cp=CPn_1($level);
		$Workers_1=Worker_1($level);
		$product_hour_new=SCn_Brickyard($level);
		$sql="UPDATE wg_villages SET krs3=(krs3*".$ct.") WHERE id=".$village."";
		$db->setQuery($sql);
		$db->query();
	}
	elseif($building->type_id==5 ||$building->type_id==9)//"Bakery" "Grain_Mill"
	{
		$Workers_1=Worker_1($level);
		$cp=CPn_1($level);
		$product_hour_new=SCn_Brickyard($level);
		$sql="UPDATE wg_villages SET krs4=(krs4*".$ct.") WHERE id=".$village."";
		$db->setQuery($sql);
		$db->query();
	}
	elseif($building->type_id==10 ||$building->type_id==11)//"Warehouse"  "Granary"
	{
		$Workers_1=Worker_1($level);
		$cp=CPn_1($level);
		$product_hour_new=SCn_eco_oth($level);
	}
	elseif($building->type_id==18)//"Palace")
	{
		$cp=CPn_3($level);
		$Workers_1=Worker_4($level);
		$product_hour_new=SCn_eco_oth($level);
	}
	$sql="UPDATE wg_villages SET workers=workers-$Workers_1 WHERE id=".$village." AND workers>2";
	$db->setQuery($sql);
	$db->query();
	$sql="SELECT user_id FROM wg_villages WHERE id=".$village."";
	$db->setQuery($sql);
	$user=null;
	$db->loadObject($user);
	$sql="UPDATE wg_users SET population=population-$Workers_1 WHERE id=".$user->user_id." AND population>2";
	$db->setQuery($sql);
	$db->query();
	if($level<=0)
	{
		if($building->type_id==14) //embassy
		{
			/*--cap nhat trang thai da ton tai embassy trong wg_user --*/
			$wg_users="UPDATE wg_users  SET embassy=0 WHERE id=".$user->user_id." LIMIT 1";
			$db->setQuery($wg_users);
			$db->query();
		}
		//$sql="UPDATE wg_buildings SET name='',img='$img',type_id=0,level=0,product_hour=0,cp=0 WHERE id=$id";
	}
	/*else
	{
		$sql="UPDATE wg_buildings SET level=$level,product_hour=$product_hour_new,cp=$cp WHERE id=$id";
	}
	$db->setQuery($sql);
	$db->query();*/
	return false;
}
?>