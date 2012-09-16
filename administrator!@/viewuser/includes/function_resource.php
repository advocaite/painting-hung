<?php
define('INSIDE', true);
/**
 * @author Le Van Tu
 * @des Lay thong tin rs
 * @return thanh topnav
 */
function GetRSStatus(){
	global $db, $lang, $wg_village;
	//require_once("function_kill.php");
	
	executeConsumptionPeopleSoldiers($wg_village->id); 
	$parse=$lang;

	$parse["sum_cun"]=$wg_village->troop_keep+$wg_village->workers;
	$parse["speed_increase_lumber"]=$wg_village->speedIncreaseRS1;
	$parse["speed_increase_clay"]=$wg_village->speedIncreaseRS2;
	$parse["speed_increase_iron"]=$wg_village->speedIncreaseRS3;
	$parse["speed_increase_crop"]=$wg_village->speedIncreaseRS4;
	$parse["speed_increase_crop_real"]=$wg_village->speedIncreaseRS4Real;
	$parse["rs1"]=$wg_village->rs1;
	$parse["rs2"]=$wg_village->rs2;
	$parse["rs3"]=$wg_village->rs3;
	$parse["rs4"]=$wg_village->rs4;
	$parse['capacity_123']=$wg_village->capa123;
	$parse['capacity_4']=$wg_village->capa4;
	$result=parsetemplate(gettemplate('topnav'), $parse);
	return $result;
}

/**
 * @author Le Van Tu
 * @des tinh suc chua cac loai RS. tra ve mot mang.
 * @return $wg_village->capa123 suc chua rs1, rs2, rs3
 * * @return $wg_village->capa4 suc chua rs4
 */

function getSumCapacity(&$wg_village, &$wg_buildings){
	global $db;
	$capa123=0;
	$capa4=0;	
	if($wg_buildings)
	{
		foreach($wg_buildings as $building)
		{
			switch($building->type_id)
			{
				case 10:
					$capa123 += $building->product_hour;
					break;
				case 11:
					$capa4 += $building->product_hour;
					break;
			}
		}
		if($capa123>0){
			$wg_village->capa123=$capa123;
		}else{
			$wg_village->capa123=800;
		}
		if($capa4>0){
			$wg_village->capa4=$capa4;
		}else{
			$wg_village->capa4=800;
		}
	}
	else
	{
		header("Location:logout.php");
		exit();
	}		
}

//Toc do khai thac go:
function GetSpeedIncreaseLumber(&$wg_buildings){
	global $db;
	$sum=0;$i=0;
	if($wg_buildings)
	{
		foreach ($wg_buildings as $ptu)
		{
			if($ptu->index<19)
			{
				if($ptu->type_id==1)
				{
					$sum=$sum+$ptu->product_hour;
				}
				$i++;
			}
			if($i==19){break;}
		}
		return $sum;
	}
	else
	{
		header("Location:logout.php");
		exit();
	}
}

//Toc do khai thac sat
function GetSpeedIncreaseClay(&$wg_buildings){
	global $db;
	$sum=0;$i=0;
	if($wg_buildings)
	{
		foreach ($wg_buildings as $ptu)
		{
			if($ptu->index<19)
			{
				if($ptu->type_id==4)
				{
					$sum=$sum+$ptu->product_hour;
				}
				$i++;
			}
			if($i==19){break;}
		}
		return $sum;
	}
	else
	{
		header("Location:logout.php");
		exit();
	}
}

//Kim loai:
function GetSpeedIncreaseIron(&$wg_buildings){
	global $db;
	$sum=0;$i=0;
	if($wg_buildings)
	{
		foreach ($wg_buildings as $ptu)
		{
			if($ptu->index<19)
			{
				if($ptu->type_id==3)
				{
					$sum=$sum+$ptu->product_hour;
				}
				$i++;
			}
			if($i==19){break;}
		}
		return $sum;
	}
	else
	{
		header("Location:logout.php");
		exit();
	}
}
//Luong thuc:
function GetSpeedIncreaseCrop(&$wg_buildings){
	global $db;
	$sum=0;$i=0;
	if($wg_buildings)
	{
		foreach ($wg_buildings as $ptu)
		{
			if($ptu->index<19)
			{
				if($ptu->type_id==2)
				{
					$sum=$sum+$ptu->product_hour;
				}
				$i++;
			}
			if($i==19){break;}
		}
		return $sum;
	}
	else
	{
		header("Location:logout.php");
		exit();
	}
}

/**
 * @author Le Van Tu
 * @des update cac thong so ve rs
 * @param1 object village (lang can update);
 * @param2 Thoi diem update
 * @return $wg_village->speedIncreaseRS1, $wg_village->speedIncreaseRS2, $wg_village->speedIncreaseRS3, $wg_village->speedIncreaseRS1 toc do tang rs 
 * @return $wg_village->rs1, $wg_village->rs2, $wg_village->rs3, $wg_village->rs4 tai nguyen da cap nhat
 * @return $wg_village->time_update_rs1, $wg_village->time_update_rs2, $wg_village->time_update_rs3, $wg_village->time_update_rs4 thoi diem da up date cac loai rs
 */

function UpdateRS(&$wg_village, &$wg_buildings, $timenow){
	global $db;	
	include_once("function_trade.php");
	$sumCun=($wg_village->troop_keep+$wg_village->workers);
	//Lay toc do tang ung voi moi rs:
	$wg_village->speedIncreaseRS1=round(GetSpeedIncreaseLumber($wg_buildings)*$wg_village->krs1);
	$wg_village->speedIncreaseRS2=round(GetSpeedIncreaseClay($wg_buildings)*$wg_village->krs2);
	$wg_village->speedIncreaseRS3=round(GetSpeedIncreaseIron($wg_buildings)*$wg_village->krs3);
	$wg_village->speedIncreaseRS4=round(GetSpeedIncreaseCrop($wg_buildings)*$wg_village->krs4);
	$wg_village->speedIncreaseRS4Real=round(GetSpeedIncreaseCrop($wg_buildings)*$wg_village->krs4)-$sumCun;

	$timeUpdateRS1=strtotime($wg_village->time_update_rs1);
	$timeUpdateRS2=strtotime($wg_village->time_update_rs2);
	$timeUpdateRS3=strtotime($wg_village->time_update_rs3);
	$timeUpdateRS4=strtotime($wg_village->time_update_rs4);
	
	$ktTime1=$timenow - $timeUpdateRS1;
	$ktTime2=$timenow - $timeUpdateRS2;
	$ktTime3=$timenow - $timeUpdateRS3;
	$ktTime4=$timenow - $timeUpdateRS4;
	
	$temp1=intval(($ktTime1*$wg_village->speedIncreaseRS1)/3600);
	if($temp1>0)
	{
		$wg_village->time_update_rs1=date("Y-m-d H:i:s",$timenow);
		$wg_village->rs1=CheckRSUpdate($wg_village->capa123 ,$wg_village->rs1 + $temp1);
	}
	$temp2=intval(($ktTime2*$wg_village->speedIncreaseRS2)/3600);
	if($temp2>0)
	{
		$wg_village->time_update_rs2=date("Y-m-d H:i:s",$timenow);
		$wg_village->rs2=CheckRSUpdate($wg_village->capa123 ,$wg_village->rs2 + $temp2);
	}
	$temp3=intval(($ktTime3*$wg_village->speedIncreaseRS3)/3600);
	if($temp3>0)
	{
		$wg_village->time_update_rs3=date("Y-m-d H:i:s",$timenow);
		$wg_village->rs3=CheckRSUpdate($wg_village->capa123 ,$wg_village->rs3 + $temp3);
	}
	$temp4=intval(($ktTime4*$wg_village->speedIncreaseRS4Real)/3600);
	if(abs($temp4)>0)
	{
		$wg_village->time_update_rs4=date("Y-m-d H:i:s",$timenow);
		$wg_village->rs4=CheckRSUpdate($wg_village->capa4 ,$wg_village->rs4 + $temp4);
	}
}


function CheckRSUpdate($capacity, $rs){
	//Kiem tra RS<0;
	//$rs=$rs>0?$rs:0; // de cho ham giet linh xu ly
	//Kiem tra tran kho:
	return ($capacity<$rs?$capacity:$rs);
}


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
	global $db, $wg_village;	
	if($wg_village->rs4>0) { return;}
	//include_once("function_resource.php");
	include_once("function_troop.php");
	
	$vOject = &$wg_village;
	$workers = $vOject->workers;
	$totalCrop = $wg_village->speedIncreaseRS4;
	$nationId = $vOject->nation_id;
	
	$arrTroops = getTroopsByNationId($nationId);
	$arrTroopVillage = getTroopVilageByVilageId($vOject->id);
	
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
		globalError2('Error executeConsumptionPeopleSoldiers function update sql');
	}*/
	
	$sumCun = $workers + $soldierKeep; // Suc an cua lang
	$productionPerHour = $wg_village->speedIncreaseRS4Real; // Suc san xuat trong 1 gio cua lang
	$overRice = 0;
	$objTroopVillageHero = NULL;
	$troopKeepSubtract = 0;
	//echo "<pre>"; print_r($tmpObj);
	if($productionPerHour < 0){ // So sanh Suc san xuat trong 1 gio nho hon 0		
		//Kiem tra luc resource cua lua ve khong tai thoi diem nao
		$timeOff = strtotime($vOject->time_update_rs4);		
		$timeOn = time();		
		$rs4 = $vOject->rs4;
		$timeCheck = $timeOff + $rs4 ;
		//if($timeCheck > $timeOn){ //Thoi diem trong kho(resource) ve 0 vuot qua thoi diem hien tai
		if($rs4 > 0){	// Neu trong kho da ve 0
			return;
		}		

		//Tra quan cho lang den ho tro
		$reasonInforce=reinforceReturn($vId, $timeCheck); // chua co report
		// Neu trong kho am it hon suc sx trong 1 h thi giet theo kho, con khong giet trong 1 gio			
		$overRice = (abs($productionPerHour)<abs($rs4))? abs($productionPerHour):abs($rs4);
		if($overRice>6) $overRice = $overRice* 0.7;

		if($tmpObj){ //Neu ko phai vi ho tro va co linh thi giet linh	
		//echo "<pre>"; print_r($tmpObj);	
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
						globalError2('Error executeConsumptionPeopleSoldiers function update sql');
					}
				}
			} //End foreach
		} //Endif co linh thi giet linh
	} //So sanh Suc san xuat trong 1 gio nho hon 0

	//Khi $overRice van con va co hero thi giet hero
	if($overRice && $objTroopVillageHero){
		$troopKeepSubtract = troopKeepSubtract + $productionOfHero;
		$updSql="UPDATE wg_troop_villa SET num=0, die_num=$objTroopVillageHero->num WHERE id=$objTroopVillageHero->id";
		$db->setQuery($updSql);		
		if(!$db->query()){
			globalError2('Error executeConsumptionPeopleSoldiers function '.$updSql);
		}
	}
		
	if($troopKeepSubtract){//Cap nhat lai suc an cua linh
		
		$updSql="UPDATE wg_villages SET troop_keep=(troop_keep-$troopKeepSubtract) WHERE id=$vOject->id";		
		$db->setQuery($updSql);		
		if(!$db->query()){
			globalError2('Error executeConsumptionPeopleSoldiers function '.$updSql);
		}
		
		//$wg_village->troop_keep = $wg_village->troop_keep -$troopKeepSubtract;
		ChangeTroopKeepVillage($wg_village->id, -$troopKeepSubtract);
	}	
	// cap nhat lai kho
	$wg_village->rs4 = 0;
}


/**
* @Author: Le Van Tu
* @Des: Thay doi RS cua mot lang hoac ASU nap cua account
* @param: $village_id -> id cua lang can thay roi RS
* @param: rs1, rs2, rs3,rs4 luong rs can thay doi moi loai
* @return: true neu co thay doi, nguoc lai false
*/
function changeRSVillage($village_id, $rs1, $rs2, $rs3, $rs4, $asu=0)
{
	global $db;
	if($asu > 0)
	{
	//cong asu cho lang
		$sql=" UPDATE wg_plus SET gold = ( gold +$asu ) WHERE user_id = ( SELECT user_id FROM wg_villages WHERE id =$village_id)";
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2("function changeRSVillage:".$sql);
		}
	}
	else
	{	
		if($rs1!=0 || $rs2!=0 || $rs3!=0 || $rs4!=0){
			$sql="UPDATE wg_villages SET rs1=rs1+$rs1, rs2=rs2+$rs2, rs3=rs3+$rs3, rs4=rs4+$rs4 WHERE id=$village_id";
			$db->setQuery($sql);
			$db->query();
			if($db->getAffectedRows()==0)
			{
				globalError2("function changeRSVillage:".$sql);
			}
		}			
	}
}
?>
