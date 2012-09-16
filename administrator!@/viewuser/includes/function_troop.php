<?php
/*
	wg_attack -> type:
	- case 1: linh vien tro o lang khac
	- case 2: linh dang di vien tro lang khac
	- case 3: attack raid
	- case 4: attack normal
	- case 5: rut quan 
	- case 6: gui tho di tao lang moi
	- case 7: do tham kieu 1
	- case 8: do tham kieu 2
	- case 9: danh cata
	- case 10: danh o oasis
	- case 11: am binh danh ky dai
/**
 * Hien thi danh sach linh chua researh va trang thai research
 */
function Research($building){
	global $lang, $wg_village;
	$parse=$lang;	
	$parse['research_status']=ResearchTroopStatus($wg_village->id);	
	if($parse['research_status']){
		$parse['list_troop_research']=ShowListTroopResearch(1);
	}else{
		if(isset($_GET['tid']) && is_numeric($_GET['tid'])){
			$listTroop=ListTroopResearch();
			if(isset($listTroop[$_GET['tid']])){
				ResearchTroop($_GET['tid'], $listTroop);
				unset($_GET);
				return Research($building);
			}else{
				$parse['list_troop_research']=ShowListTroopResearch(0);
			}
		}else{
			$parse['list_troop_research']=ShowListTroopResearch(0);
		}
	}
	
	$parse['id']=$building->index;
	return parsetemplate(gettemplate('research_body'), $parse);
}

function Train($building, $status_type, $maxPalaceTrain=null){
	global $db, $lang, $wg_village;
	includeLang('train_troop');
	$parse=$lang;
	
	if(isset($_POST['t1'])){
		Training($building, $status_type, $maxPalaceTrain);
		unsetVillageParam($wg_village);
		$db->updateObject("wg_villages", $wg_village, "id");
		header("Location: build.php?id=$building->index");
	}
	
	$parse['train_troop_status']=TrainTroopStatus($building, $status_type);	

	if($building->type_id==18){//nha nay la palace
		$maxTroopTrain=GetMaxTroopPalaceTrain($building);
		//echo "<pre>"; print_r($maxPalaceTrain); die();
		if($maxTroopTrain['tho']>0 || $maxTroopTrain['thuyet_gia']>0){
			$parse["list_troops"]=ShowListTroops($building, $maxTroopTrain);
		}else{			
			$parse['no_troop']=$parse['khong_the_tao_them_tho'];
			$parse['list_troops']=parsetemplate(gettemplate("train_table_null"), $parse)."<br>";
		}
	}else{
		$parse["list_troops"]=ShowListTroops($building);
	}
	
	$parse['id']=$building->index;

	return parsetemplate(gettemplate('train_troop_body'), $parse);
}
/**
 * palace la truong hop dac biet de tao tho van thuyet gia.
 */
function ShowPalace($building){
	global $db, $lang, $wg_village;
	includeLang('troop');
	$parse=$lang;
	$parse['class_train']="class=selected";
	$parse['class_cp']="";
	$parse['class_loyaty']="";
	$parse['class_ex']="";
	switch($_GET['t']){
		case 1:
			//show thong tin diem danh vong
			$parse['class_train']="";
			$parse['class_cp']="class=selected";
			$parse['class_loyaty']="";
			$parse['class_ex']="";
			$parse['task_content']=ShowPalaceCP($building);
			break;
		case 2:
			//show thogn tin long trung thanh.
			$parse['class_train']="";
			$parse['class_cp']="";
			$parse['class_loyaty']="class=selected";
			$parse['class_ex']="";
			$parse['task_content']=ShowPalaceLoyalty();
			break;
		case 3:
			//thong tin mo rong.
			$parse['class_train']="";
			$parse['class_cp']="";
			$parse['class_loyaty']="";
			$parse['class_ex']="class=selected";
			$parse['task_content']=ShowPalaceExpansion();
			break;
		default:
			$parse['task_content']=ShowPalaceTrain($building);
			break;
	}	
	$parse['id']=$building->index;
	return parsetemplate(gettemplate('palace_body'), $parse);
}

function ShowPalaceTrain($building){
	global $db, $lang;
	includelang("troop");
	$parse=$lang;
	$maxPalaceTrain=GetMaxTroopPalaceTrain($building);
	$parse['list_troop']=Train($building, 13, $maxPalaceTrain);	
	$parse['message']='';//parsetemplate(gettemplate('palace_train_message'), $parse);
	return parsetemplate(gettemplate('palace_train_body'), $parse);
}

/**
 * hien thi thong tin diem danh vong.
 */
function ShowPalaceCP($building){
	global $db, $lang, $wg_village;
	includelang("troop");
	$parse=$lang;
	//compute point of village
	$parse['village_point']		= getCulturePointOfVillagePerDay1($wg_village->id);
	$parse['villages_point']	= pointsTotal();
	$parse['total_point']		= allvillageCulturepoint();
	$parse['next_village_point']= checkPointToAddVillage($wg_village->user_id);
	
	return parsetemplate(gettemplate('palace_cp_body'), $parse);
}

function ShowPalaceLoyalty(){
	global $db, $lang, $wg_village;
	includelang("troop");
	$parse=$lang;
	
	//include('func_faith.php');
	$parse['new_faith'] = udpFaithAVillagePerDay($wg_village->id);
	
	return parsetemplate(gettemplate('palace_loyalty_body'), $parse);
}

function ShowPalaceExpansion(){
	global $db, $lang, $wg_village;
	includelang("troop");
	$parse=$lang;
	
	$data_res = getChildVillage($wg_village->id);
	if($data_res)
	{
		$parse["palace_expansion"] = $data_res;
	}
	else
	{
		$parse["palace_expansion"] = "";
		$parse["father_village1"] = $parse["father_village2"] ;
	}
	
	return parsetemplate(gettemplate('palace_expansion_body'), $parse);
}

function ShowListTroops($building, $maxPalaceTrain=null){
	global $lang, $wg_village;
	$parse=$lang;
	$result="";
	$i=0;
	
	$listTroops=ListTroops($building, $maxPalaceTrain);
	if($listTroops){
		$row=gettemplate('train_troop_row');
		foreach($listTroops as $troop){
			$i++;
			$parse["name"]=$lang[$troop->name];
			$parse["icon"]=$troop->icon;
			$parse["rs1"]=$troop->rs1;
			$parse["rs2"]=$troop->rs2;
			$parse["rs3"]=$troop->rs3;
			$parse["rs4"]=$troop->rs4;
			$parse["keep_hour"]=$troop->keep_hour;
			$parse["time_train"]=TimeToString($troop->time_train);
			$parse["sum"]=$troop->sum;
			$parse["present_troop"]=$troop->present_troop>0?$troop->present_troop:"0";
			$parse["i"]=$i;
			$parse['link']=$troop->id;// Hien them vao
			$rows.=parsetemplate($row, $parse);
		}
		if($i>0){
			$parse['rows']=$rows;
			return parsetemplate(gettemplate('train_troop_table'), $parse);
		}else{
			return parsetemplate(gettemplate('train_table_nul'), $parse);
		}
	}else{
		return parsetemplate(gettemplate('train_table_null'), $parse);
	}
}

//lay danh sach troop co the research.
function ShowListTroopResearch($alr){
	global $lang, $wg_village;
	$village_id=$wg_village->id;
	includeLang('troop');
	$parse=$lang;
	$result="";
	$i=0;
	$listTroops=ListTroopResearch($village_id);
	
	$row=gettemplate('research_row');
	if($listTroops){
		foreach($listTroops as $troop){
			$i++;
			$parse['name']=$lang[$troop->name];
			$parse['icon']=$troop->icon;
			$parse['rs1']=$troop->rsrs1;
			$parse['rs2']=$troop->rsrs2;
			$parse['rs3']=$troop->rsrs3;
			$parse['rs4']=$troop->rsrs4;
			$parse['troop_id']=$troop->id;
			$parse['time_research']=TimeToString($troop->time_research);
			$parse["i"]=$i;
			if(!$alr){
				if($wg_village->rs1>=$troop->rsrs1 && $wg_village->rs2>=$troop->rsrs2 && $wg_village->rs3>=$troop->rsrs3 && $wg_village->rs4>=$troop->rsrs4){
					$parse['action']=parsetemplate(gettemplate("research_action_accept"), $parse);
				}else{
					$parse['action']=parsetemplate(gettemplate("research_action_too_few"), $parse);
				}
			}else{
				$parse['action']=parsetemplate(gettemplate("research_action_already"), $parse);
			}
			$parse['rows'].=parsetemplate($row, $parse);
		}		
		return parsetemplate(gettemplate("research_table"), $parse);
	}else{
		return parsetemplate(gettemplate("research_table_null"), $parse);
	}
	return false;
}

/**
 * xu ly yeu cau dao tao linh tu nguoi dung:
 */
function Training($building, $status_type, $maxPalaceTrain=null){
	global $wg_village;
	
	//Lay danh sach linh co the tao:
	$listTroops=ListTroops($building, $maxPalaceTrain);	
	
	if(count($listTroops)>0){
		$rs1Sub=0;
		$rs2Sub=0;
		$rs3Sub=0;
		$rs4Sub=0;
		$i=1;
		foreach($listTroops as $troop){
			//Kiem tra yeu cau cua nguoi choi co phu hop khong:
			if($_POST['t'.$i]>0 && $troop->sum>0){
				$max=min(intval($wg_village->rs1/$troop->rs1), intval($wg_village->rs2/$troop->rs2), intval($wg_village->rs3/$troop->rs3), intval($wg_village->rs4/$troop->rs4));
				$max=$max<=$troop->sum?$max:$troop->sum;
				if($max>0){
					$sumTrain=$_POST['t'.$i]>$max?$max:$_POST['t'.$i];
					$sumTrain=intval($sumTrain);
					$objectID=InsertTroopTrain($troop->id, $sumTrain);
					$time_begin=GetTimeBeginTrainTroop($status_type);
					
					$cost_time=$sumTrain*$troop->time_train;
					InsertStatus($wg_village->id, $objectID, date("Y-m-d H:i:s", $time_begin), date("Y-m-d H:i:s", $time_begin+$cost_time), $cost_time, $status_type);
					
					//tru rs cua lang:
					$wg_village->rs1 -=$sumTrain*$troop->rs1;
					$wg_village->rs2 -=$sumTrain*$troop->rs2;
					$wg_village->rs3 -=$sumTrain*$troop->rs3;
					$wg_village->rs4 -=$sumTrain*$troop->rs4;
					if($building->type_id==18){//nha nay la palace
						break;
					}
				}					
			}			
			$i++;
		}	
	}
}

//xu ly research troop.
/*
//- Kiem tra troop nay co nam trong danh sach troop du dieu kien de research khong.
//- Chen object vao bang wg_troop_train.
//- Chen status vao bang wg_status.
*/
function ResearchTroop($troop_id, $listTroop){
	global $wg_village;	
	if($wg_village->rs1>=$listTroop[$troop_id]->rsrs1 && $wg_village->rs2>=$listTroop[$troop_id]->rsrs2 && $wg_village->rs3>=$listTroop[$troop_id]->rsrs3 && $wg_village->rs4>=$listTroop[$troop_id]->rsrs4){
		//tru rs cua lang
		$wg_village->rs1 -= $listTroop[$troop_id]->rsrs1;
		$wg_village->rs2 -= $listTroop[$troop_id]->rsrs2;
		$wg_village->rs3 -= $listTroop[$troop_id]->rsrs3;
		$wg_village->rs4 -= $listTroop[$troop_id]->rsrs4;
		
		$objectID=InsertTroopResearch($wg_village->id, $troop_id);
		InsertStatus($wg_village->id, $objectID, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$listTroop[$troop_id]->time_research), $listTroop[$troop_id]->time_research, 9);
	}else{
		globalError2("Hacking form reearch troop");
	}	
}

/**
*	Lay danh sach linh co the tao ung voi moi nha.
*/
function ListTroops($building, $maxPalaceTrain=null){
	global $db, $wg_village, $game_config;
	if($building->type_id != 18){ 
		switch($building->level){
			case 1:
				$k=100/100;
				break;
			case 2:
				$k=90/100;
				break;
			case 3:
				$k=81/100;
				break;
			case 4:
				$k=73/100;
				break;
			case 5:
				$k=66/100;
				break;
			case 6:
				$k=59/100;
				break;
			case 7:
				$k=53/100;
				break;
			case 8:
				$k=48/100;
				break;
			case 9:
				$k=43/100;
				break;
			case 10:
				$k=39/100;
				break;
			case 11:
				$k=35/100;
				break;
			case 12:
				$k=31/100;
				break;
			case 13:
				$k=28/100;
				break;
			case 14:
				$k=25/100;
				break;
			case 15:
				$k=23/100;
				break;
			case 16:
				$k=21/100;
				break;
			case 17:
				$k=19/100;
				break;
			case 18:
				$k=17/100;
				break;
			case 19:
				$k=15/100;
				break;
			case 20:
				$k=14/100;
				break;
		}
	}else{
		$k=1;
	}
	$sql="SELECT
				wg_troops.*
			FROM
				wg_troops ,
				wg_troop_researched
			WHERE
				wg_troops.id =  wg_troop_researched.troop_id AND
				wg_troop_researched.status=1 AND 
				wg_troop_researched.village_id =  '$wg_village->id' AND
				wg_troops.building_type_id =  '$building->type_id'";
	$db->setQuery($sql);
	$troopList=$db->loadObjectList();
	if($troopList){
		foreach($troopList as &$troop){
			$troop->time_train=($troop->time_train*$k)/$game_config['k_train'];
			$troop->present_troop=GetSumPresentTroop($troop->id);
			$troop->sum=min(intval($wg_village->rs1/$troop->rs1), intval($wg_village->rs2/$troop->rs2), intval($wg_village->rs3/$troop->rs3), intval($wg_village->rs4/$troop->rs4));
			$troop->sum = $troop->sum>0?$troop->sum:0;
			switch($troop->name){
				//neu la tho hoac thuyet gia.
				//case "Thợ":
				case "sunda10":	
				case "arabia10":
				case "mongo10":
					if($maxPalaceTrain['tho']>0){
						$troop->sum=$troop->sum > $maxPalaceTrain['tho'] ? $maxPalaceTrain['tho'] : $troop->sum;
					}else{
						$troop->sum=0;
					}
					break;
				//case "Thuyết gia":
				case "sunda11":
				case "arabia11":
				case "mongo11":
					if($maxPalaceTrain['thuyet_gia']>0){
						$troop->sum=$troop->sum > $maxPalaceTrain['thuyet_gia'] ? $maxPalaceTrain['thuyet_gia'] : $troop->sum;
					}else{
						$troop->sum=0;
					}
					break;
			}
			
		}
		return $troopList;
	}
	return false;
}

//lay danh sach linh du dieu kien de research.
function ListTroopResearch(){
	global $db, $wg_village;
	//lay danh sach linh thuoc mot chung toc.
	$troopList=getTroopsOfNation($wg_village->nation_id);
	$troopResearched=getTroopsResearched();
	foreach($troopList as $troop){
		//kiem tra da duoc hoac dang duoc research hay ko.
		if($troopResearched[$troop->id] != 1){					
			//kiem tra yeu cau building.
			if(checkBuildingResearch($troop->requirement)){
				$result[$troop->id]=$troop;
			}
		}
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des kiem tra dieu kien ve building de research linh
 * @return true or false
 */
function checkBuildingResearch($requirement){
	global $wg_buildings;
	$count=0;
	$requirementList=explode(";", $requirement);
	foreach($requirementList as $requirement){
		$buildingRequirement=explode(",", $requirement);
		foreach($wg_buildings as $building){
			if($buildingRequirement[0]==$building->type_id && $building->level>=$buildingRequirement[1]){
				$count++;
			}
		}
	} 
	return ($count>=count($requirementList));
}

/**
 * @author Le Van Tu
 * @des lay danh sach linh thuoc mot chung toc
 * @param nation id
 * @return array object
 */
function getTroopsOfNation($nation_id){
	global $db, $game_config;
	$sql="SELECT * FROM wg_troops WHERE nation_id=$nation_id";
	$db->setQuery($sql);
	$listTroop = $db->loadObjectList();
	if($listTroop){
		foreach($listTroop as &$troop){
			$troop->time_research /= $game_config['k_research'];
			$troop->time_item /= $game_config['k_research'];
		}
	}		
	return $listTroop;
}

/**
 * @author Le Van Tu
 * @des Lay danh sach linh da va dang duoc tao
 */
function getTroopsResearched($status=null){
	global $db, $wg_village;
	$result=null;	
	$sql="SELECT
				wg_troop_researched.troop_id
			FROM
				wg_troop_researched
			WHERE
				wg_troop_researched.village_id =  '$wg_village->id'";
	if($status!=null){
		$sql.=" AND status=$status";
	}
	$db->setQuery($sql);
	$troopList = $db->loadObjectList();
	if($troopList){
		//luu thanh mot mang moi de thao tac
		foreach($troopList as $troop){
			$result[$troop->troop_id]=1;
		}
	}
	return $result;
}


/**
 * lay building_type->id (theo building->name)
 */
function GetBuildingTypeIDByName($building_name){
	global $db;
	$sql="SELECT id FROM wg_building_types WHERE name = '$building_name'";
	$db->setQuery($sql);
	$building_type = null;
	$db->loadObject($building_type);
	if($building_type){
		return $building_type->id;
	}
	return false;
}

//lay so linh co the tao cua mot lang (theo troop_name).
function SumTroops($name, $rs1, $rs2, $rs3, $rs4){
	global $db;
	$sum=0;
	$sql="SELECT * FROM wg_troops WHERE name = '$name'";
	$db->setQuery($sql);
	$troop = null;
	$db->loadObject($troop);
	if($troop && $troop->rs1>0 && $troop->rs2>0 && $troop->rs3>0 && $troop->rs4>0){
		$sum=min(variant_idiv($rs1, $troop->rs1), variant_idiv($rs2, $troop->rs2), variant_idiv($rs3, $troop->rs3), variant_idiv($rs4, $troop->rs4));
		$result["id"]=$troop->id;
		$result["name"]=$name;
		$result["image"]=$troop->image;
		$result["icon"]=$troop->icon;
		$result["sum"]=$sum;
		$result["rs1"]=$troop->rs1;
		$result["rs2"]=$troop->rs2;
		$result["rs3"]=$troop->rs3;
		$result["rs4"]=$troop->rs4;
		$result["keep_hour"]=$troop->keep_hour;
		$result["time_train"]=$troop->time_train*$game_config['k_train'];
		return $result;
	}
	return 0;
}

//lay thong tin cua troop va so luong co the tao theo id.
function GetTroopInfo($village_id, $troop_id, $rs1, $rs2, $rs3, $rs4, $level=1){
	global $db, $game_config;
	//Tinh toan he so giam thoi gian ung voi level
	switch($level){
		case 1:
			$k=100/100;
			break;
		case 2:
			$k=90/100;
			break;
		case 3:
			$k=81/100;
			break;
		case 4:
			$k=73/100;
			break;
		case 5:
			$k=66/100;
			break;
		case 6:
			$k=59/100;
			break;
		case 7:
			$k=53/100;
			break;
		case 8:
			$k=48/100;
			break;
		case 9:
			$k=43/100;
			break;
		case 10:
			$k=39/100;
			break;
		case 11:
			$k=35/100;
			break;
		case 12:
			$k=31/100;
			break;
		case 13:
			$k=28/100;
			break;
		case 14:
			$k=25/100;
			break;
		case 15:
			$k=23/100;
			break;
		case 16:
			$k=21/100;
			break;
		case 17:
			$k=19/100;
			break;
		case 18:
			$k=17/100;
			break;
		case 19:
			$k=15/100;
			break;
		case 20:
			$k=14/100;
			break;
	}
	$sql="SELECT * FROM wg_troops WHERE id = $troop_id";
	$db->setQuery($sql);
	$troop = null;
	$db->loadObject($troop);
	if($troop && $troop->rs1>0 && $troop->rs2>0 && $troop->rs3>0 && $troop->rs4>0){
		$result["id"]=$troop->id;
		$result["name"]=$troop->name;
		$result["image"]=$troop->image;
		$result["icon"]=$troop->icon;
		$result["rs1"]=$troop->rs1;
		$result["rs2"]=$troop->rs2;
		$result["rs3"]=$troop->rs3;
		$result["rs4"]=$troop->rs4;

		$result["rsrs1"]=$troop->rsrs1;
		$result["rsrs2"]=$troop->rsrs2;
		$result["rsrs3"]=$troop->rsrs3;
		$result["rsrs4"]=$troop->rsrs4;

		$result["keep_hour"]=$troop->keep_hour;
		$result["time_train"]=intval(($troop->time_train*$k)/$game_config['k_train']);
		$result["time_research"]=intval($troop->time_research)/$game_config['k_research'];
		$result["present_troop"]=GetSumPresentTroop($troop->id);
		if($troop->rs1>0 && $troop->rs2>0 && $troop->rs3>0 && $troop->rs4>0){
			$result["sum"]=min(intval($rs1/$troop->rs1), intval($rs2/$troop->rs2), intval($rs3/$troop->rs3), intval($rs4/$troop->rs4));
		}else{
			$result["sum"]=0;
		}
		return $result;
	}
	return false;
}

//lay nation_name cua mot lang.
function GetNationName($village_id){
	global $db;
	$sql="SELECT * FROM wg_villages WHERE id = $village_id";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village){
		$sql="SELECT * FROM wg_nations WHERE id = '$village->nation_id'";
		$db->setQuery($sql);
		$nation = null;
		$db->loadObject($nation);
		if($nation){
			return $nation->name;
		}
	}
	return false;
}

//lay nation_id cua mot lang.
function GetVillaNationID($village_id){
	global $db;
	$sql="SELECT nation_id FROM wg_villages WHERE id = $village_id";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village){
		return $village->nation_id;
	}
	return false;
}

//lay tong so linh da do trong lang theo id.
function GetSumPresentTroop($troop_id){
	global $db, $wg_village;
	$inVillage = GetSumTroopInVillage($troop_id);
	$underaway = 0;
	$sql="SELECT 
				(num-die_num) as sum
			FROM
				wg_attack ,
				wg_attack_troop
			WHERE
				wg_attack.id =  wg_attack_troop.attack_id AND
				wg_attack.`status` =  '0' AND
				wg_attack.village_attack_id =  '$wg_village->id' AND
				wg_attack_troop.troop_id =  '$troop_id'
			GROUP BY 
				wg_attack_troop.id";
	$db->setQuery($sql);
	$l = $db->loadObjectList();
	foreach($l as $t){
		$underaway += $t->sum;
	}
	return $inVillage+$underaway;
}

/**
 * @author Le Van Tu
 * @des Tinh tong so linh da do trong lang theo id.
 * @param troop id
 */
function GetSumTroopInVillage($troop_id){
	global $db, $wg_village;
	$sql="SELECT num FROM wg_troop_villa WHERE troop_id = '$troop_id' AND village_id='$wg_village->id'";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * Lay danh sach linh cua mot lang (11 linh).
 */
function GetListTroopVilla($village){
	global $db, $lang, $game_config;
	includelang("troop");
	
	$troopNationList=getTroopsOfNation($village->nation_id);
	
	$sql="SELECT troop_id, num FROM wg_troop_villa WHERE village_id=$village->id";
	$db->setQuery($sql);
	$troopVillaList=$db->loadObjectList();	
	if($troopVillaList){
		//luu danh sach linh theo dinh dang moi de thao tac.
		foreach($troopVillaList as $troopVilla){
			$temp[$troopVilla->troop_id]=$troopVilla->num;
		}		
	}
	
	if($troopNationList){
		foreach($troopNationList as &$troop){
			if($temp[$troop->id]>0){
				$troop->sum=$temp[$troop->id];
			}else{
				$troop->sum=0;
			}
			$troop->speed *= $game_config['k_speed'];
		}
	}		
	return $troopNationList;
}


//them vao bang wg_troop_train.
function InsertTroopTrain($troop_id, $num){
	global $db, $wg_village;
	$num	= $db->getEscaped($num);
	$sql="INSERT INTO wg_troop_train (village_id, troop_id, num, num_trained, status) VALUES ($wg_village->id, '$troop_id', $num, 0, 0)";
	$db->setQuery($sql);
	if($db->query()){
		return $db->insertid();
	}
	return false;
}

//chen thong tin troop research vao bang wg_troop_train (tan dung bang wg_troop_train).
function InsertTroopResearch($village_id, $troop_id){
	global $db;
	$troop_id = $db->getEscaped($troop_id);
	$sql="INSERT INTO wg_troop_researched (village_id, troop_id, status) VALUES ($village_id, $troop_id, 0)";
	$db->setQuery($sql);
	$result=$db->query();
	if($result){
		return $db->insertid();
	}
	return false;
}




//kiem tra so linh muon tao va so linh co the tao co phu hop ko?
function CheckSum($sum_request, $sum_available, $troop_rs1, $troop_rs2, $troop_rs3, $troop_rs4, $rs1, $rs2, $rs3, $rs4){
	return ($sum_request>0 && $sum_request<=$sum_available && $sum_request*$troop_rs1<=$rs1 && $sum_request*$troop_rs2<=$rs2 && $sum_request*$troop_rs3<=$rs3 && $sum_request*$troop_rs4<=$rs4);
}

/**
 * lay thoi gian bat dau de tao mot loai linh cung loai.
 */
function GetTimeBeginTrainTroop($status_type){
	global $db, $wg_village;
	$sql="SELECT time_end FROM wg_status WHERE village_id=$wg_village->id AND wg_status.type=$status_type AND wg_status.status=0 ORDER BY time_end DESC";
	$db->setQuery($sql);
	$timeEnd=$db->loadResult();
	if($timeEnd){
		return strtotime($timeEnd)>time()?strtotime($timeEnd):time();
	}else{
		return time();
	}	
}


/**
 * @author Le Va Tu
 * @des Tinh so tho co the tao trong palace.
/*
	- (palace level 10)  +  (so lang con =0) -> co the tao duoc 5 tho hoac 1 thuyet gia. (palace level 20)  +  (so lang con =0) -> co the tao duoc 10 tho hoac 2 thuyet gia.
	- Co it nhat mot tho thi ko duoc tao thuyet gia va nguoc lai.
*/
function GetMaxTroopPalaceTrain($building){
	global $nationTroopList, $wg_village;
		
	$soldierName = get_soldier($wg_village->nation_id);
	
	$soldierName1 = $soldierName.'10';
	$soldierName2 = $soldierName.'11';
	$troopId1 = $nationTroopList[$wg_village->nation_id]['type_name10'];
	$troopId2 = $nationTroopList[$wg_village->nation_id]['type_name11'];
	
	//tinh tong so tho da co.
	$sumThoInVillage=GetSumPresentTroop($troopId1);
	
	//tinh tong so thuyet gia da co.
	$sumThuyetGiaInVillage=GetSumPresentTroop(GetTroopIDByName($wg_village->nation_id, $soldierName2));
	
	//Tinh so tho dang duoc tao.
	$sumThoTraining=CheckTrainTroop($wg_village->id, $troopId1);
	
	//Tinh so thuyet gia dang duoc tao.	
	$sumThuyetGiaTraining=CheckTrainTroop($wg_village->id, $troopId2);
	
	//Tong so tho da va dang duoc tao.
	$sumThoTrained=$sumThoInVillage+$sumThoTraining;
	
	//Tong so thuyer gia da va dang duoc tao
	$sumThuyetGiaTrained=$sumThuyetGiaInVillage+$sumThuyetGiaTraining;

	//Kiem tra xem lang nay co dang di tao lang moi hay khong:
	$sumChidFounding=CheckFoundingVillage($wg_village->id);
	//tinh so con cua lang.
	$sumChild=GetSumChildOfVillage($wg_village);
	//Tinh so lang con co the tao.
	$sumChildExpansion=2-$sumChild-$sumChidFounding;
	
	//Gia su moi thuyet gia tuong ung voi 5 linh binh thuong:
	//Tong so linh da va dang duoc tao:
	$sumTroopTrained=$sumThoTrained + $sumThuyetGiaTrained*5;
	
	//Tinh tong so tho va thuyet gia co the tao (xet theo dieu kien ve so lang con va diem danh vong).	
	if($building->level>=20){
		switch($sumChildExpansion){
			case 0:
				$sumTroopAvailable=0;
				break;
			case 1:
				$sumTroopAvailable=5-$sumTroopTrained;
				break;
			case 2:
				$sumTroopAvailable=10-$sumTroopTrained;
				break;			
		}
	}else{
		if($building->level>=10){
			switch($sumChildExpansion){
				case 0:
					$sumTroopAvailable=0;
					break;
				case 1:
					$sumTroopAvailable=0;
					break;
				case 2:
					$sumTroopAvailable=5-$sumTroopTrained;
					break;
			}
		}
	}
	
	$result['tho']=$sumTroopAvailable;
	$result['thuyet_gia']=intval($sumTroopAvailable/5);
	return $result;
}

/**
 * Kiem tra xem lang nay co dang di tao lang moi nay khong.
 */
function CheckFoundingVillage($village_id){
	global $db;
	$sql="SELECT COUNT(*) FROM wg_status WHERE village_id=$village_id AND type=15 AND wg_status.status=0";
	$db->setQuery($sql);
	return $db->loadResult();
}


/**
 * Kiem tra co linh nao cung loai dang duoc tao khong:
 * neu co tra ve so linh dang duoc tao.
 */
function CheckTrainTroop($village_id, $troop_id){
	global $db;
	$sql="SELECT sum(num-num_trained) FROM wg_troop_train WHERE wg_troop_train.status=0 AND troop_id = $troop_id AND village_id=$village_id"; 
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * Tinh so lang con cua mot lang.
 */
function GetChildOfVillage($child){
	if($child){
		$childIDList=explode("|", $child);
		return $childIDList;
	}
	return false;
}

function GetSumChildOfVillage($village){
	$sum=0;
	$childList=GetChildOfVillage($village->child_id);
	if($childList){
		foreach($childList as $child){
			if($child && is_numeric($child)){
				$sum++;
			}
		}
	}
	return $sum;
}


function GetTroopIDByName($nation_id, $troop_name){
	global $db;
	$sql="SELECT id FROM wg_troops WHERE name='$troop_name' AND nation_id=$nation_id";
	$db->setQuery($sql);
	$troop=null;
	$db->loadObject($troop);
	if($troop){
		return $troop->id;
	}
	return false;
}

/**
 * Them lang con cho mot lang
 */
function AddChildForVillage($village_id, $village_child_id){
	global $db;
	//$sql="SELECT id, child_id FROM wg_villages id=$village_id"; ->old
	$sql="SELECT id, child_id FROM wg_villages WHERE id=$village_id";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village->child_id == ''){
		$sql="UPDATE wg_villages SET child_id=$village_child_id WHERE id=$village_id";
	}else{
		$sql="UPDATE wg_villages SET child_id='".$village->child_id."|".$village_child_id."' WHERE id=$village_id";
	}
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Error!!!AddChildForVillage");
	}else{
		return true;
	}
}

/**
 * bot lang con cho mot lang
 */
function SubChildForVillage($village_id, $village_child_id){
	global $db;
	$sql="SELECT child_id FROM wg_villages WHERE id=$village_id";
	$db->setQuery($sql);
	$village=null;
	$child=$db->loadResult();
	$listId=explode("|", $child);
	if(count($listId)<=1){
		$newChild="";
	}else{
		if($listId[0]==$village_child_id){
			$newChild=$listId[1];
		}else{
			$newChild=$listId[0];
		}
	}
	$sql="UPDATE wg_villages SET child_id='$newChild' WHERE id=$village_id";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * kiem tra so lang co the mo rong doi voi 1 user (kiem tra diem danh vong).
 */
function CheckSumVillageExpansion($village_id){
	global $db;
	$sum=0;
	$sql="SELECT user_id FROM wg_villages WHERE id=$village_id";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village){
		$sql="SELECT cp FROM wg_villages WHERE user_id=$village->user_id";
		$db->setQuery($sql);
		$villageList=null;
		$villageList=$db->loadObjectList();
		if($villageList){
			foreach($villageList as $village){
				$cp+=$village->cp;
			}
		}else{
			return false;
		}
		
		//so sanh voi bang culture_point.
		$sql="SELECT * FROM wg_culture_point ORDER BY sum_village ASC";
		$db->setQuery($sql);
		$culturePointList=null;
		$culturePointList=$db->loadObject();
		if($culturePointList){
			foreach($culturePointList as $culturePoint){
				if($cp>=$culturePoint->point){
					$sum=$culturePoint->sum_village;
				}else{
					break;
				}
			}
			return $sum;
		}
	}
	return false;
}

//-----------cac ham lien quan toi send troop------------>
function ShowRallyPoint($building){
	global $db, $lang, $wg_village;
	includelang("troop");	
	$parse=$lang;
	$parse['class_overview']="selected";
	$parse['class_send_troop']="";
	
	if(isset($_GET['t'])){
		switch($_GET['t']){
			case 1:				
				$parse['task_content']=SendTroops($building);
				$parse['class_overview']="";
				$parse['class_send_troop']="selected";
				break;
			case 2:
				$parse['task_content']=Overview($building);
				break;
			case 3:
				$parse['task_content']=showInfoJoinCTC();
				break;
			case 4:
				$parse['task_content']=traQuanVienTro($building);
				$parse['class_overview']="";
				$parse['class_send_troop']="selected";
				break;
			case 5:
				$parse['task_content']=rutQuanVienTro($building);
				$parse['class_overview']="";
				$parse['class_send_troop']="selected";
				break;
			default:
				$parse['task_content']=Overview($building);
				break;
		}
	}else{
		$parse['task_content']=Overview($building);
	}
	
	$parse['index']=$building->index;
	$parse['id']=$building->index;
	$parse['stt']=$building->index;
	return parsetemplate(gettemplate('rally_point_body'), $parse);
}

function showInfoJoinCTC()
{
	global $db,$lang;
	$parse=$lang;
	return parsetemplate(gettemplate('rally_point_ctc'), $parse);
}
/**
 * Hien thi trang thai quan doi trong bo chi huy
 */
function Overview($building){
	include_once('function_status.php');
	global $db, $lang, $wg_village;
	includeLang('rally_point');
	$parse=$lang;
	
	//updateTroopKeep($wg_village->id, getTroopKeep($wg_village->id, getArrayOfTroops()));
	
	//xu ly su kien hoan binh:
	cancelAttack();
	
	$timer=0;
	//Lay danh sach attack co lien quan toi lang hien tai:
	$sql="SELECT
				wg_attack.village_attack_id,
				wg_attack.village_defend_id,
				wg_attack.`type` AS `type`,
				wg_attack.id AS id, 
				wg_status.id AS status_id, 
				wg_status.`status` AS status, 
				wg_status.time_end AS time_end, 
				wg_status.time_begin AS time_begin
			FROM
				wg_status ,
				wg_attack
			WHERE
				wg_status.object_id =  wg_attack.id AND
				wg_status.`status` =  '0' AND 
				wg_attack.`status` =  '0' AND 
				wg_status.`type` =  '7' AND 
				(wg_attack.village_attack_id =  '$wg_village->id' OR
				wg_attack.village_defend_id =  '$wg_village->id') AND
				wg_status.time_end > now()  
			GROUP BY
				wg_status.id
			ORDER BY
				wg_status.time_end ASC";
	$db->setQuery($sql);
	$attackList=$db->loadObjectList();
	if($attackList){
		//co it nhat mo cuoc tan cong:
		$listTroop=GetListTroopVilla($wg_village);
		foreach($attackList as $attack){
			if($wg_village->id==$attack->village_attack_id){
				//Linh di toi lang khac:
				$villageDefend=getVillageDefend($attack->village_defend_id);				
				$listAttackTroop=GetListAttackTroop($attack->id);
				$heroAttack=GetAttackHero($attack->id);
				$onTheWay.=ShowTroopOnTheWay($attack, $wg_village, $villageDefend, $listTroop, $listAttackTroop, $heroAttack, &$timer);					
			}else{
				if($attack->type==12){
					//Am binh danh ky dai
					$incoming.=showAmBinhAttack($attack, $timer);
				}else{
					//Linh lang khac den (ko hien thi trang thai linh den do tham):
					if($attack->type!=7 && $attack->type!=8){
						$villageAttack=getVillage($attack->village_attack_id);
						$listTroopOther=GetListTroopVilla($villageAttack);
						$incoming.=ShowTroopIncoming($attack, $villageAttack, $listTroopOther, &$timer);
					}	
				}									
			}
		}
	}	
	//Linh ve lang:
	$incoming.=ShowTroopComeBack($timer);
	
	//---------------Tho di tao lang moi:-------------->
	$onTheWay.=ShowThoDiTaoLangMoi($timer);
		
	//Danh sach linh cua lang
	$own=ShowOwnTroop();
	
	//
	$reinforce=ShowTroopReinforce($building);
		
	if($incoming){
		//$result.="<p class=\"b\">".$lang['Incoming troops'].$incoming;
		$parse['title'] = $lang['Incoming troops'];
		$result .= parsetemplate(gettemplate("troop_title"), $parse).$incoming;
	}
	
	if($onTheWay){
		//$result.="<p class=\"b\">".$lang['Troops on the way'].$onTheWay;
		$parse['title'] = $lang['Troops on the way'];
		$result .= parsetemplate(gettemplate("troop_title"), $parse).$onTheWay;
	}
	
	//$result.="<p class=\"b\">".$lang['Troops in the village']."</p>".$own;
	$parse['title'] = $lang['Troops in the village'];
	$result .= parsetemplate(gettemplate("troop_title"), $parse).$own;
	
	if($reinforce){
		$result .= $reinforce;
	}
		
	return $result;	
}

/**
 * @author Le Van Tu
 * @des rut quan vien tro o lang khac ve
 * @param $village_id id cua lang hien tai
 * @return void
 */
function rutQuanVienTro($building){
	global $db,$lang, $wg_village;
	includelang("send_troop");
	$parse=$lang;
	//Kiem tra xem co tac vu rut linh vien tro ko.
	if($_GET['t']==5 && ($_GET['atid'])){
		$village_defend_id=CheckReinforceForID($wg_village->id, $_GET['atid']);
		if($village_defend_id){
			$villageDefend=getVillageDefend($village_defend_id);
			
			$listTroop=GetListTroopVilla($wg_village);
			$listAttackTroop=GetListAttackTroop($_GET['atid']);
			$heroAttack=GetAttackHero($_GET['atid']);
			
			for($i=0; $i<11; $i++){
				$parse['icon'.($i+1)]=$listTroop[$i]->icon;
				$parse['title'.($i+1)]=$lang[$listTroop[$i]->name];
				
				if($listAttackTroop[$listTroop[$i]->id]['sum']>0){
					//$parse['t'.($i+1)]="<input class=\"fm\" type=\"text\" name=\"t[".$listTroop[$i]->id."]\" value=\"".$listAttackTroop[$listTroop[$i]->id]['sum']."\" size=\"1\" maxlength=\"5\">";
					$parse['t'.($i+1)] = parsetemplate(gettemplate("input_text"), array("name"=>"t[".$listTroop[$i]->id."]", "value" => $listAttackTroop[$listTroop[$i]->id]['sum']));
					$parse['class'.($i+1)]="";
					$tempSpeed[]=$listTroop[$i]->speed;
				}else{
					$parse['t'.($i+1)]=0;
					$parse['class'.($i+1)]="c";
				}
			}
			
			if($heroAttack){
				//$parse['t'.($i+1)]="<input class=\"fm\" type=\"text\" name=\"h\" value=\"1\" size=\"1\" maxlength=\"1\">";
				$parse['t'.($i+1)] = parsetemplate(gettemplate("input_text"), array("name"=>"h", "value" => "1"));
				$parse['class'.($i+1)]="";
				$tempSpeed[]=$heroAttack->speed;
			}else{
				$parse['t'.($i+1)]=0;
				$parse['class'.($i+1)]="c";
			}
			$parse['title'.($i+1)]=$lang['hero'];
			
			$speed=min($tempSpeed);
			$s=S($wg_village->x, $wg_village->y, $villageDefend->x, $villageDefend->y);
			$duration=($s/$speed)*3600;
			$parse['duration']=TimeToString($duration);
			$parse['time_at']=date("H:i:s", time()+$duration);
			$parse['date_at']=date("d-m-Y", time()+$duration);
			$parse['attack_id']=$_GET['atid'];
			$parse['uid']=$village->user_id;
			$parse['village_attack_name']=$wg_village->name;
			$parse['village_attack_x']=$wg_village->x;
			$parse['village_attack_y']=$wg_village->y;
			$parse['player_attack_name']=GetPlayerName($wg_village->id);
			$parse['send_type_string']=$lang['rut_quan_tu'];
			$parse['village_defend_name']=$villageDefend->name;
			$parse['village_name']=$villageDefend->name;
			$parse['task']=5;
			return parsetemplate(gettemplate("send_troop_home"), $parse);
		}
	}
	
	//xu ly buoc cuoi cung:
	if(count($_POST)>0){
		$arrayTroop=getArrayOfTroops();
		$listAttackTroop=GetListAttackTroop($_POST['aid']);
		$heroAttack=GetAttackHero($_POST['aid']);
		if($heroAttack && $_POST['h']==1){
			$coHero=true;
		}else{
			$coHero=false;
		}
		
		if(count($listAttackTroop)>0){
			$t= $_POST["t"];
			$coQuan=false;
			foreach($t as $troopId=>$sum){
				$sum = intval($sum);
				$sum = $db->getEscaped($sum);
				if($sum>0 && $listAttackTroop[$troopId]['sum']>0){
					$input[$troopId]['sum']=$sum<=$listAttackTroop[$troopId]['sum']?$sum:$listAttackTroop[$troopId]['sum'];
					$input[$troopId]['attack_troop_id']=$listAttackTroop[$troopId]['attack_troop_id'];
					$tempSpeed[]=$arrayTroop[$troopId]['speed'];
					$coQuan=true;
				}
			}
		}
			
		
		if($coQuan || $coHero){
			//Lay thong tin attack
			$attack=getAttack($_POST['aid']);
			//chen mot attack moi co type = 5 (linh vien tro ve lang):
			$attack_id=InsertAttack($attack->village_attack_id, $attack->village_defend_id, 5);
		
			if($coQuan){
				//chen cac loai linh va so luong tuong ung vao bang wg_attack_troop:
				foreach($input as $troopId=>$troop){
					InsertAttackTroop($troopId, $attack_id, $troop['sum'], $troop['hero_id']);
					//tru so linh trong bang wg_attack_troop
					changeAttackTroop($troop['attack_troop_id'], -$troop['sum']);
				}
			}
			
			if($coHero){
				InsertAttackHero($heroAttack->id, $attack_id, 1);
				deleteAttackHero($heroAttack->attack_hero_id);
				$tempSpeed[]=$heroAttack->speed;
			}
			
			$speed=min($tempSpeed);
			//Tinh khoang cach giua 2 lang:
			$villageDefend=getVillage($attack->village_defend_id);
			$s=S($wg_village->x, $wg_village->y, $villageDefend->x, $villageDefend->y);
			$duration=($s/$speed)*3600;
			InsertStatus($attack->village_attack_id, $attack_id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$duration), $duration, 10);
			
			//cap nhat troop_keep cho 2 thanh:
			updateTroopKeep($attack->village_defend_id, getTroopKeep($attack->village_defend_id, $arrayTroop));
			updateTroopKeep($wg_village->id, getTroopKeep($wg_village->id, $arrayTroop));
		}
	}
	header("Location: build.php?id=37");
}

/**
 * @author Le Van Tu
 * @des tra quan vien tro ve
 * @param $village_id id cua lang hien tai
 * @return void
 */
function traQuanVienTro($building){
	global $lang, $wg_village, $db;
	includelang("send_troop");
	$parse=$lang;
	//Kiem tra xem co tac vu tra linh vien tro ko.
	if($_GET['t']==4 && ($_GET['atid'])){
		$village_attack_id=CheckReinforceID($wg_village->id, $_GET['atid']);
		if($village_attack_id){
			$villageAttack=getVillage($village_attack_id);
			
			$listTroop=GetListTroopVilla($villageAttack);
			$listAttackTroop=GetListAttackTroop($_GET['atid']);
			$heroAttack=GetAttackHero($_GET['atid']);
						
			for($i=0; $i<11; $i++){
				$parse['icon'.($i+1)]=$listTroop[$i]->icon;
				$parse['title'.($i+1)]=$lang[$listTroop[$i]->name];
			
				if($listAttackTroop[$listTroop[$i]->id]['sum']>0){
					$parse['t'.($i+1)] = parsetemplate(gettemplate("input_text"), array("name" => "t[".$listTroop[$i]->id."]", "value" => $listAttackTroop[$listTroop[$i]->id]['sum'])); 
					$parse['class'.($i+1)]="";
					$tempspeed[]=$listTroop[$i]->speed;
				}else{
					$parse['t'.($i+1)]=0;
					$parse['class'.($i+1)]="c";
				}
			}
			
			if($heroAttack){
				$parse['t'.($i+1)] = parsetemplate(gettemplate("input_text"), array("name" => "h", "value" => "1")); 
				$parse['class'.($i+1)]="";
				$tempspeed[]=$heroAttack->speed;
			}else{
				$parse['t'.($i+1)]=0;
				$parse['class'.($i+1)]="c";
			}
			$parse['title'.($i+1)]=$lang['hero'];
			
			$speed=min($tempspeed);
			$s=S($wg_village->x, $wg_village->y, $villageAttack->x, $villageAttack->y);
			$duration=($s/$speed)*3600;
			$parse['duration']=TimeToString($duration);
			$parse['time_at']=date("H:i:s", time()+$duration);
			$parse['date_at']=date("d-m-Y", time()+$duration);
			
			$parse['attack_id']=$_GET['atid'];
			$parse['uid']=$villageAttack->user_id;
			$parse['village_attack_name']=$villageAttack->name;
			$parse['village_attack_x']=$villageAttack->x;
			$parse['village_attack_y']=$villageAttack->y;
			$parse['player_attack_name']=GetPlayerName($village_attack_id);
			$parse['send_type_string']=$lang['tra_quan_ve'];
			$parse['village_defend_name']=$villageAttack->name;
			$parse['village_name']=$wg_village->name;
			$parse['task']=4;
			return parsetemplate(gettemplate("send_troop_home"), $parse);
		}
	}
	
	//Xu ly buoc cuoi cung:
	if(count($_POST)>0){
		$arrayTroop=getArrayOfTroops();
		$listAttackTroop=GetListAttackTroop($_POST['aid']);
		$heroAttack=GetAttackHero($_POST['aid']);
		
		if($heroAttack && $_POST['h']==1){
			$coHero=true;
		}else{
			$coHero=false;
		}
		
		$coQuan=false;
		if(count($listAttackTroop)>0){
			$t= $_POST["t"];
			foreach($t as $troopId=>$sum){
				$sum = intval($sum);
				$sum = $db->getEscaped($sum);
				if($sum>0 && $listAttackTroop[$troopId]['sum']>0){
					$input[$troopId]['sum']=$sum<=$listAttackTroop[$troopId]['sum']?$sum:$listAttackTroop[$troopId]['sum'];
					$input[$troopId]['attack_troop_id']=$listAttackTroop[$troopId]['attack_troop_id'];
					$tempspeed[]=$arrayTroop[$troopId]['speed'];
					$coQuan=true;
				}
			}
		}
		
		if($coQuan || $coHero){
			//Lay thong tin attack
			$attack=getAttack($_POST['aid']);
			//chen mot attack moi co type = 5 (linh vien tro ve lang):
			$attack_id=InsertAttack($attack->village_attack_id, $attack->village_defend_id, 5);
			if($coQuan){
				//chen cac loai linh va so luong tuong ung vao bang wg_attack_troop:
				foreach($input as $troopId=>$troop){
					InsertAttackTroop($troopId, $attack_id, $troop['sum']);
					changeAttackTroop($troop['attack_troop_id'], -$troop['sum']);
				}
			}
				
			if($coHero){
				InsertAttackHero($heroAttack->id, $attack_id, 1);
				deleteAttackHero($heroAttack->attack_hero_id);
				$tempspeed[]=$heroAttack->speed;
			}
			
			$speed=min($tempspeed);
			//Tinh khoang cach giua 2 lang:
			$villageAttack=getVillage($attack->village_attack_id);
			$s=S($villageAttack->x, $villageAttack->y, $wg_village->x, $wg_village->y);
			$duration=($s/$speed)*3600;
			InsertStatus($attack->village_attack_id, $attack_id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$duration), $duration, 10);
			//cap nhat lai troop_keep cho 2 thanh
			updateTroopKeep($wg_village->id, getTroopKeep($wg_village->id, $arrayTroop));
			updateTroopKeep($attack->village_attack_id, getTroopKeep($attack->village_attack_id, $arrayTroop));
		}
	}	
	header("Location: build.php?id=37");
}

/**
 * @author Le Van Tu
 * @des Lay thong tin cua mot attack
 * @param $id id cua attack
 * @return $attack mot recode trong attack
 */
function getAttack($id){
	global $db;
	$id = $db->getEscaped($id);
	$sql="SELECT * FROM wg_attack WHERE id=$id";
	$db->setQuery($sql);
	$db->loadObject($result);
	return $result;
}

/**
 * @author Le Van Tu
 * @DES tra het quan vien tro cho lang khac khi thieu luong thuc
 * @param $village_id id cua lang da het luong thuc
 * @param int $time thoi diem tra ve (don vi = s)
 */
function reinforceReturn($village_id, $time){
	global $db;
	//Lay danh sach linh vien tro lang:
	$sql="SELECT
			wg_attack.id AS id,
			wg_attack.village_attack_id
		FROM
			wg_attack
		WHERE
			wg_attack.`type` =  '1' AND 			
			wg_attack.village_defend_id =  '$village_id'";
	$db->setQuery($sql);
	$reinforces=$db->loadObjectList();
	if($reinforces){
		$art = getArrayOfTroops();
		$village=getVillage($village_id);
		foreach($reinforces as $reinforce){
			$villageAttack=getVillage($reinforce->village_attack_id);
			$speed=GetSpeedAttackTroopBack($reinforce->id);
			$s=S($village->x, $village->y, $villageAttack->x, $villageAttack->y);
			if($speed>0){
				$duration=($s/$speed)*3600;
			}else{
				$duration=0;
			}
			InsertStatus($reinforce->village_attack_id, $reinforce->id, date("Y-m-d H:i:s", $time), date("Y-m-d H:i:s", $time+$duration), $duration, 10);
			SetReinforceType($reinforce->id);
			//cap nhat lai troop_keep:
			updateTroopKeep($villageAttack->id, getTroopKeep($villageAttack->id, $art));
		}
		updateTroopKeep($village_id, getTroopKeep($village_id, $art));
		return true;
	}
	return false;
}

/**
 * @author Le Van Tu
 * @des set status=1 trong bang wg_status khi quan vien tro duoc rut ve hoac duoc tra ve
 * @param $attack_id id cua attack tuong ung
 * @return void
 */
function setReinforceStatus($attack_id){
	global $db;
	$sql="UPDATE wg_status SET status=1 WHERE type=7 AND object_id=$attack_id";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * @author Le Van Tu
 * @des Hien thi danh sach linh dang toi va linh lang khac toi ho tro:
 */
function ShowTroopIncoming($attack, $villageAttack, $listTroop, $timer){
	global $lang, $wg_village;
	includeLang('rally_point');
	$parse=$lang;
	$soldierName = get_soldier($wg_village->nation_id);
	
	for($i=0; $i<11; $i++){
		$parse['sum'.($i+1)]="?";                                                   
		$parse['icon'.($i+1)]=$listTroop[$i]->icon;
		$parse['class'.($i+1)]="c";
		$parse['title'.($i+1)]=$lang[$soldierName.($i+1)];
	}
	$parse['sum12']="?";                                                   
	$parse['icon12']="images/icon/hero4.ico";
	$parse['class12']="c";
	$parse['title12']=$lang['hero'];
	switch($attack->type){                                                       
		case 2:                                                                 
			$parse['incoming_title']=$lang['Reinforcement for'];                
			break;                                                              
		case 3:                                                                 
			$parse['incoming_title']=$lang["Raid against"];                     
			break;                                                              
		case 4:                                                                 
			$parse['incoming_title']=$lang["Attack against"];                   
			break;                                                              
		case 9:                                                                 
			$parse['incoming_title']=$lang["Attack against"];                   
			break;                                                              
	}                                                                            
	$timer++;                                                                    
	$parse['timer']=$timer;	
	$parse['village_defend_name']=$wg_village->name;                                
	$parse['village_attack_name']=$villageAttack->name;
	$parse['uid']=$villageAttack->user_id;
	$parse['x_att']=$villageAttack->x;
	$parse['y_att']=$villageAttack->y;
	$parse['x_def']=$wg_village->x;
	$parse['y_def']=$wg_village->y;
	$parse['village_attack_x']=$villageAttack->x;
	$parse['village_attack_y']=$villageAttack->y;
	$parse['duration']=TimeToString(strtotime($attack->time_end)-time());                        
	$parse['time_at']=date("H:i:s", strtotime($attack->time_end));                               
	$parse['date_at']=date("d-m-Y", strtotime($attack->time_end));
	return parsetemplate(gettemplate("list_troop_status"), $parse)."<br>";
}

/**
 * @author Le Van Tu
 * @des hien thi trang thai am binh danh ky dai
 */
function showAmBinhAttack($attack, &$timer){
	global $wg_village, $lang;
	includeLang("rally_point");
	$parse=$lang;
	
	$listAmBinh=getAmBinh();
	
	if($listAmBinh){
		for($i=0; $i<10; $i++){
			$parse['icon'.$i]=$listAmBinh[$i]->icon;
			$parse['title'.$i]=$lang[$listAmBinh[$i]->name];
		}
	}
	
	$timer++;                                                                    
	$parse['timer']=$timer;
	$parse['duration']=TimeToString(strtotime($attack->time_end)-time());                        
	$parse['time_at']=date("H:i:s", strtotime($attack->time_end));                               
	$parse['date_at']=date("d-m-Y", strtotime($attack->time_end));
	$parse['village_defend_name']=$wg_village->name;
	return parsetemplate(gettemplate("am_binh_incoming"), $parse);
}

/**
 * @author Le Van Tu
 * @des Lay thong tin linh am binh
 */
function getAmBinh(){
	global $db;
	$sql="SELECT * FROM wg_am_binh";
	$db->setQuery($sql);
	return $db->loadObjectList();
}


/**
 * Hien Thi danh sach linh vien tro:
 */
function ShowTroopReinforce($building){
	global $db, $lang, $wg_village;
	includeLang('rally_point');
	$parse=$lang;
	$result="";	
	$sql="SELECT
				wg_attack.village_attack_id,
				wg_attack.village_defend_id,
				wg_attack.id AS id 
			FROM
				wg_attack
			WHERE 
				wg_attack.`status` =  '0' AND 
				wg_attack.`type` =  '1' AND 
				(wg_attack.village_attack_id =  '$wg_village->id' OR
				wg_attack.village_defend_id =  '$wg_village->id')";
	$db->setQuery($sql);
	$attackList=$db->loadObjectList();
	if($attackList){
		$listTroop=GetListTroopVilla($wg_village);
		$soldierName = get_soldier($wg_village->nation_id);		
		foreach($attackList as $attack){
			$listAttackTroop=GetListAttackTroop($attack->id);
			$heroAttack=GetAttackHero($attack->id);
			$sumUpkeep=0;
			$conQuan=false;
			if($wg_village->id==$attack->village_attack_id){
				//Linh o lang khac:
				$villageDefend=getVillageDefend($attack->village_defend_id);
				for($i=0; $i<11; $i++){
					$parse['icon'.($i+1)]=$listTroop[$i]->icon;
					if($listAttackTroop[$listTroop[$i]->id]['sum']>0){
						$parse['t'.($i+1)] =$listAttackTroop[$listTroop[$i]->id]['sum'];
						$parse['class'.($i+1)]="";
						$sumUpkeep+=$listTroop[$i]->keep_hour*$listAttackTroop[$listTroop[$i]->id]['sum'];
						$conQuan=true;
					}else{
						$parse['t'.($i+1)]=0;
						$parse['class'.($i+1)]="c";
					}
					$parse['title'.($i+1)]=$lang[$soldierName.($i+1)];													
				}
				if($heroAttack){
					$parse['t'.($i+1)]=1;
					$parse['class'.($i+1)]="";
					$conQuan=true;
				}else{
					$parse['t'.($i+1)]=0;
					$parse['class'.($i+1)]="c";
				}
				$parse['title12']=$lang['hero'];
				
				if($villageDefend->kind_id<7){
					$parse['list_troop_title']=$lang['Reinforcement for']." <a href=\"village_map.php?a=$villageDefend->x&b=$villageDefend->y\">".$villageDefend->name."</a>";
				}else{
					$parse['list_troop_title']=$lang['Reinforcement for']." ".parsetemplate(gettemplate("a"), array("href" => "village_map.php?a=$villageDefend->x&b=$villageDefend->y", "string" => $lang['oasis']." ($villageDefend->x|$villageDefend->y)"));
				}
				if($villageDefend->kind_id<7){
					$parse['upkeep']=0;
				}else{
					$parse['upkeep']=$sumUpkeep;
				}
				$parse['village_name']=$wg_village->name;
				$parse['x']=$wg_village->x;
				$parse['y']=$wg_village->y;				
				
				//t=5 -> rut quan vien tro o lang khac ve.
				$parse['action'] = parsetemplate(gettemplate("a"), array("href" => "build.php?id=$building->index&t=5&atid=$attack->id", "string"  => $lang['withdraw']));
				
				if($conQuan){
					$inOther.=parsetemplate(gettemplate('list_troop'), $parse);
				}else{
					//Linh di ho tro da chet het -> set status:
					SetAttackStatus($attack->id);
					deleteAllAttackTroop($attack->id);
				}				
			}else{
				//Linh tu lang khac toi ho tro:
				$villageAttack=getVillage($attack->village_attack_id);
				$listTroopVillageAttack=GetListTroopVilla($villageAttack);
				$soldierName = get_soldier($villageAttack->nation_id);
				
				for($i=0; $i<11; $i++){
					$parse['icon'.($i+1)]=$listTroopVillageAttack[$i]->icon;
					if($listAttackTroop[$listTroopVillageAttack[$i]->id]['sum']>0){
						$parse['t'.($i+1)] =$listAttackTroop[$listTroopVillageAttack[$i]->id]['sum'];
						$parse['class'.($i+1)]="";
						$sumUpkeep+=$listTroopVillageAttack[$i]->keep_hour*$listAttackTroop[$listTroopVillageAttack[$i]->id]['sum'];
						$conQuan=true;
					}else{
						$parse['t'.($i+1)]=0;
						$parse['class'.($i+1)]="c";
					}
					//$parse['title'.$i]=$listTroopVillageAttack[$i]['name'];
					$parse['title'.($i+1)]=$lang[$soldierName.($i+1)];												
				}
				
				if($heroAttack){
					$parse['t'.($i+1)]=1;
					$parse['class'.($i+1)]="";
					$sumUpkeep+=$heroAttack->keep_hour;
					$conQuan=true;
				}else{
					$parse['t'.($i+1)]=0;
					$parse['class'.($i+1)]="c";
				}
				$parse['title12']=$lang['hero'];
				
				$parse['list_troop_title'] = parsetemplate(gettemplate("list_troop_title"), array("title" => $lang['Reinforcement for'], "x" => $village->x, "y" => $village->y, "name" => GetPlayerName($village_id)));
				
				$parse['village_name']=$villageAttack->name;
				$parse['x']=$villageAttack->x;
				$parse['y']=$villageAttack->y;
				$parse['upkeep']=$sumUpkeep;
				//t=4 -> tra quan vien tro ve. 
				//$parse['action']='<a href="build.php?id='.$building->index.'&t=4&atid='.$attack->id.'">'.$lang['send back'].'</a>';
				
				$parse['action'] = parsetemplate(gettemplate('a'), array("href" => "build.php?id=$building->index&t=4&atid=$attack->id", 'string' => $lang['send back']));
				
				if($conQuan){
					$fromOther.=parsetemplate(gettemplate('list_troop'), $parse);
				}else{
					//Linh di ho tro da chet het -> set status:
					SetAttackStatus($attack->id);
				}				
			}				
		}			
	}
	
	if($fromOther){
		$result.=$fromOther;
	}
	
	if($inOther){
		$result.=parsetemplate(gettemplate("troop_title"), array("title" => $lang['Troops in other villages'])).$inOther;
	}	
	return $result;	
}

/**
 * Hien thi danh sach linh dang den lang khac
 */
function ShowTroopOnTheWay($attack, $village, $villageDefend, $listTroop, $listAttackTroop, $heroAttack, $timer){
	global $db, $lang, $game_config;
	includeLang('rally_point');
	$parse=$lang;
	$soldierName = get_soldier($village->nation_id);
	
	for($i=0; $i<11; $i++){
		$parse['icon'.($i+1)]=$listTroop[$i]->icon;
		if($listAttackTroop[$listTroop[$i]->id]['sum']>0){
			$parse['sum'.($i+1)] =$listAttackTroop[$listTroop[$i]->id]['sum'];
			$parse['class'.($i+1)]="";	
		}else{
			$parse['sum'.($i+1)]=0;
			$parse['class'.($i+1)]="c";
		}	
		$parse['title'.($i+1)]=$lang[$soldierName.($i+1)];			
	}
	
	if($heroAttack){
		$parse['sum12'] =1;
		$parse['class12']="";
	}else{
		$parse['sum12']=0;
		$parse['class12']="c";
	}
	$parse['title12']=$lang['hero'];
	
	switch($attack->type){
		case 2:
			$parse['incoming_title']=$lang['Reinforcement for'];
			break;
		case 3:
			$parse['incoming_title']=$lang['Raid against'];
			break;
		case 4:
		case 9:
		case 11:
			$parse['incoming_title']=$lang['Attack against'];
			break;
		case 7:
			$parse['incoming_title']=$lang['Scouting'];
			break;
		case 8:
			$parse['incoming_title']=$lang['Scouting'];
			break;
		case 10:
			$parse['incoming_title']=$lang['Raid against'];
			break;
	}
	
	if($villageDefend->kind_id<7){
		$parse['village_defend_name']=$villageDefend->name;
	}else{
		$parse['village_defend_name']=$lang['oasis']." ($villageDefend->x|$villageDefend->y)";
	}
	
	if((time()-strtotime($attack->time_begin))<$game_config["attack_cancel_time"]){
		$parse['cancel_button']=parsetemplate(gettemplate("cancel_button"), array("href" => "build.php?id=37&c=$attack->id", "alt" => $lang['cancel'], "title" => $lang['cancel'])); 
	}
	
	$timer++;
	$parse['timer']=$timer;
	
	$parse['x_def']=$villageDefend->x;
	$parse['y_def']=$villageDefend->y;
	$parse['village_attack_name']=$village->name;
	$parse['x_att']=$village->x;
	$parse['y_att']=$village->y;
	$parse['duration']=TimeToString(strtotime($attack->time_end)-time());
	$parse['time_at']=date("H:i:s", strtotime($attack->time_end));
	$parse['date_at']=date("d-m-Y", strtotime($attack->time_end));
	return parsetemplate(gettemplate("list_troop_status"), $parse);
}

/**
 * @author Le Van Tu
 * @des xu ly su kien hoan binh, neu con thoi gian.
 */
function cancelAttack(){
	global $db, $wg_village, $game_config;
	$id=$db->getEscaped($_GET['c']);
	$cancel_time=date("Y-m-d H:i:s", $game_config['attack_cancel_time']);
	if(is_numeric($id)){ 
		$sql="SELECT * FROM wg_status WHERE wg_status.type=7 AND  wg_status.status=0 AND village_id=$wg_village->id AND object_id=$id";
		$db->setQuery($sql);
		$db->loadObject($status);
		if($status && (strtotime($status->time_begin)+$game_config['attack_cancel_time'])>time()){
			setStatus($status->id);
			$duration=time()-strtotime($status->time_begin);
			InsertStatus($wg_village->id, $id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$duration), $duration, 10);
		}
	}
}

/**
 * Hien thi danh sach linh cua lang:
 */
function ShowOwnTroop(){
	global $lang, $wg_village;
	includeLang('rally_point');
	$parse=$lang;
	$soldierName = get_soldier($wg_village->nation_id);	
	//Danh sach linh cua lang
	$listTroop=GetListTroopVilla($wg_village);
	$hero=GetHeroVillage($wg_village->id);
	$upkeep=0;
	for($i=0; $i<11; $i++){
		if($listTroop[$i]->sum>0){
			$parse['t'.($i+1)]=$listTroop[$i]->sum;
			$parse['class'.($i+1)]="";
			$upkeep+=$listTroop[$i]->sum*$listTroop[$i]->keep_hour;
		}else{
			$parse['t'.($i+1)]=0;
			$parse['class'.($i+1)]="c";
		}
		$parse['icon'.($i+1)]=$listTroop[$i]->icon;
		$parse['title'.($i+1)]=$lang[$soldierName.($i+1)];
	}
	if($hero){
		$parse['t12']=1;
		$parse['class12']="";
		$upkeep+=$hero->keep_hour;
	}else{
		$parse['t12']=0;
		$parse['class12']="c";
	}
	$parse['title12']=$lang['hero'];
		
	$parse['village_name']=$wg_village->name;
	$parse['x']=$wg_village->x;
	$parse['y']=$wg_village->y;
	include_once('function_resource.php');
	$parse['upkeep']=$upkeep;
	$parse['list_troop_title']=$lang['Own troops'];
	$parse['action']='';
	return parsetemplate(gettemplate("list_troop"), $parse);
}

/**
 * Hien thi trang thai tho di tao lang moi:
 */
function ShowThoDiTaoLangMoi($timer){
	global $db, $lang, $wg_village;
	includeLang('rally_point');
	$parse=$lang;	
	$onTheWay="";
	$sql="SELECT * FROM wg_status WHERE village_id=$wg_village->id AND type=15 AND wg_status.status=0";
	$db->setQuery($sql);
	$statusList=null;
	$statusList=$db->loadObjectList();
	if($statusList){
		foreach($statusList as $status){			
			//Tho chua toi lang -> hien thi trang thai.
			$timeArrival=strtotime($status->time_end);
			$soldierName = get_soldier($wg_village->nation_id);
			$listTroop=GetListTroopVilla($wg_village);
			$listAttackTroop=GetListAttackTroop($status->object_id);
			for($i=0; $i<11; $i++){
				$parse['icon'.($i+1)]=$listTroop[$i]->icon;
				if($listAttackTroop[$listTroop[$i]->id]['sum']>0){
					$parse['sum'.($i+1)] =$listAttackTroop[$listTroop[$i]->id]['sum'];
					$parse['class'.($i+1)]="";
				}else{
					$parse['sum'.($i+1)]=0;
					$parse['class'.($i+1)]="c";
				}				
			    $parse['title'.($i+1)]=$lang[$soldierName.($i+1)];				
			}
			$parse['icon'.($i+1)]="images/icon/hero4.ico";
			$parse['sum'.($i+1)]=0;
			$parse['class'.($i+1)]="c";
			$parse['title12']=$lang['hero'];
			
			$parse['incoming_title']=$lang['tao_lang_moi'];
			$timer++;
			$parse['timer']=$timer;
			$parse['village_defend_name']="";
			$parse['village_attack_name']=$wg_village->name;
			
			$parse['x_att']=$wg_village->x;
			$parse['y_att']=$wg_village->y;
			$parse['duration']=TimeToString($timeArrival-time());
			$parse['time_at']=date("H:i:s", $timeArrival);
			$parse['date_at']=date("d-m-Y", $timeArrival);
			$onTheWay.=parsetemplate(gettemplate("list_troop_status"), $parse);			
		}
	}
	return $onTheWay;
}


/**
 * Lay danh sach linh di danh tran hoac vien tro tro ve:
 */
function ShowTroopComeBack(&$timer){
	global $db, $lang, $wg_village;
	includeLang('rally_point');
	$parse=$lang;
	$comeBack="";	
	$sql="SELECT
				wg_attack.village_attack_id,
				wg_attack.village_defend_id,
				wg_attack.`type` AS `type`,
				wg_attack.id AS id, 
				wg_status.id AS status_id, 
				wg_status.`status` AS status, 
				wg_status.time_end AS time_end 
			FROM
				wg_status ,
				wg_attack
			WHERE
				wg_status.object_id =  wg_attack.id AND
				wg_status.`status` =  '0' AND 
				wg_status.`type` =  '10' AND 
				wg_attack.village_attack_id =  '$wg_village->id' AND
				wg_status.time_end > now()  
			GROUP BY
				wg_status.id
			ORDER BY
				wg_status.time_end ASC";
	$db->setQuery($sql);
	$attackList=$db->loadObjectList();
	if($attackList){
		$listTroop=GetListTroopVilla($wg_village);
		foreach($attackList as $attack){
			$listAttackTroop=GetListAttackTroop($attack->id);
			$soldierName = get_soldier($wg_village->nation_id);
			
			for($i=0; $i<11; $i++){
				$parse['icon'.($i+1)]=$listTroop[$i]->icon;
				if($listAttackTroop[$listTroop[$i]->id]['sum']>0){
					$parse['sum'.($i+1)] =$listAttackTroop[$listTroop[$i]->id]['sum'];
					$parse['class'.($i+1)]="";	
				}else{
					$parse['sum'.($i+1)]=0;
					$parse['class'.($i+1)]="c";
				}	
				$parse['title'.($i+1)]=$lang[$soldierName.($i+1)];					
			}
			
			if($heroAttack=GetAttackHero($attack->id)){
				$parse['sum'.($i+1)]=1;
				$parse['class'.($i+1)]="";
			}else{
				$parse['sum'.($i+1)]=0;
				$parse['class'.($i+1)]="c";
			}
			
			
			$parse['title12']=$lang['hero'];			
			$villageDefend=getVillageDefend($attack->village_defend_id);
			if($villageDefend->kind_id<7){
				$parse['village_defend_name']=$villageDefend->name;
				$parse['incoming_title']=$lang['Return from']." ".$lang['village'];
			}else{
				$parse['village_defend_name']=$lang['oasis']." ($villageDefend->x|$villageDefend->y)";
				$parse['incoming_title']=$lang['Return from']." ";
			}
			
			$timer++;
			$parse['timer']=$timer;
			
			$parse['village_attack_name']=$wg_village->name;
			$parse['x_att']=$wg_village->x;
			$parse['y_att']=$wg_village->y;
			
			$parse['x_def']=$villageDefend->x;
			$parse['y_def']=$villageDefend->y;
			$parse['duration']=TimeToString(strtotime($attack->time_end)-time());
			$parse['time_at']=Date("H:i:s", strtotime($attack->time_end));
			$parse['date_at']=Date("d-m-Y", strtotime($attack->time_end));			
			$comeBack .= parsetemplate(gettemplate("list_troop_status"), $parse)."<br>";
		}
	}
	return $comeBack;	
}



/**
 * xu ly thao tac dieu quan
 */
function SendTroops($building){
	global $db, $lang, $wg_village;
	includeLang("send_troop");
	//Buoc 1:
	if(isset($_POST['vn'])){
		if($_POST['vn']){
			//Kiem tra tien lang co ton tai hay khong:
			$villageDefend=CheckVillageName($_POST['vn']);
			if(!$villageDefend || $villageDefend->user_id==0){
				return DisplaySendTroop1(1);
			}else{
				if($villageDefend->id==$wg_village->id){
					return DisplaySendTroop1(5);
				}
			}
		}else{
			//Kiem tra toa do cua lang co ton tai hay ko:
			if(is_numeric($_POST['x']) && is_numeric($_POST['y'])){
				$villageDefend=checkVillageLocationSendTroop($_POST['x'], $_POST['y']);
				if(!$villageDefend){
					return DisplaySendTroop1(2);
				}else{
					if($villageDefend->id==$wg_village->id){
						return DisplaySendTroop1(5);
					}
				}
			}
		}
		
		//Ton tai lang nguoi choi nhập:
		if($villageDefend->kind_id<7){
			//Kiem tra xem lang nay co thuoc dang can duoc bao ve hay khong:
			if(($privateTime=isPrivateVillage($villageDefend->id)) && $_POST['st']!=2){
				return DisplaySendTroop1(6, $privateTime);
			}
			
			//Kiem tra xem thanh tan cong co dang trong thoi gian bao ho hay khong:
			if($privateTime=isPrivateVillage($wg_village->id)){
				return DisplaySendTroop1(9, $privateTime);
			}
		}
		
		//Kiem tra dinh chien:
		if($dct = checkDinhChien($wg_village->user_id)){
			return DisplaySendTroop1(11, $dct);
		}
		
		if($dct = checkDinhChien($villageDefend->user_id)){
			return DisplaySendTroop1(10, $dct);
		}
		
		if($villageDefend){
			$user=GetUserInfo($villageDefend->user_id, 'anounment');
			if($user->anounment!="ban"){//Kiem tra xem user co bi ban hay khong
				//Kiem tra thong tin nhập:
				$listTroop=GetListTroopVilla($wg_village);
				//Kiem tra da chon so linh hay chua.
				$t=0;//Tong so binh chung duoc cu di.
				$spy=false;			
				for($i=0; $i<11; $i++){
					//Dem so binh chung:
					if($listTroop[$i]->sum>0 && $_POST['t'.($i+1)]>0){
						$t++;					
					}
					//Kiem tra xem co phai di do tham hay khong:
					if($listTroop[$i]->sum>0 && $_POST['t'.($i+1)]>0 && ($listTroop[$i]->id=="8" || $listTroop[$i]->id=="19" || $listTroop[$i]->id=="30")){					
						$spy=true;					
					}
					//Kiem tra xem co cata khong:
					if($listTroop[$i]->sum>0 && $_POST['t'.($i+1)]>0 && ($listTroop[$i]->id==9 || $listTroop[$i]->id==20 || $listTroop[$i]->id==31)){
						$cata=true;
					}
				}
				
				if($t || $_POST['t12']){
					//kiem tra xem co phai danh bo lac tu do hay khong:
					if($villageDefend->kind_id<7){
						//Khong phai danh bo lac:
						if($t==1 && $spy && ($_POST['st']==3 || $_POST['st']==4)){
							//do tham
							return DisplaySendSpy($villageDefend, $listTroop);
						}else{
							//Neu co cata thi hien thi form cata:
							if($cata && ($_POST['st']==3 || $_POST['st']==4)){
								return DisplaySendCatapult($villageDefend, $listTroop);
							}else{
								return DisplaySendTroop2($villageDefend, $listTroop);
							}					
						}
					}else{
						//Danh bo lac:
						return DisplaySendTroop2($villageDefend, $listTroop);
					}
										
				}else{
					return DisplaySendTroop1(3);
				}
			}else{
				//User nay dang bi ban:
				return DisplaySendTroop1(7);
			}				
		}else{
			return DisplaySendTroop1(4);
		}
	}	
	
	//xu ly buoc cuoi cung.
	if(is_numeric($_POST['vdid'])){
		if(is_numeric($_POST['st']) && ($_POST['st']==1 || $_POST['st']==2 || $_POST['st']==3 || $_POST['st']==4)){
			$village_defend_id=$_POST['vdid'];
			$villageDefend =  getVillageDefend($village_defend_id);
			if($villageDefend->kind_id<7){
				//Kiem tra xem lang nay co duoc bao ve hay khong:
				if((isPrivateVillage($villageDefend->id) && $_POST['st']!=2) || isPrivateVillage($wg_village->id)){
					return Overview($building);
				}
			}				
			
			//Kiem tra dinh chien:
			if($dct = checkDinhChien($wg_village->user_id)){
				return Overview($building);
			}
			
			if($dct = checkDinhChien($villageDefend->user_id)){
				return Overview($building);
			}
			
			$sms = true;
			$listTroop=GetListTroopVilla($wg_village);
			$hero=GetHeroVillage($wg_village->id);
			
			if($listTroop){
				if($villageDefend && $listTroop){
					//Lay danh sach linh gui di.
					$j=0;
					for($i=0; $i<11; $i++){
						if(is_numeric($_POST['t'.($i+1)]) && $_POST['t'.($i+1)] >0 && $listTroop[$i]->sum>0){
							//Kiem tra xem co phai di do tham hay khong:
							if($listTroop[$i]->sum>0 && $_POST['t'.($i+1)]>0 && ($listTroop[$i]->id==8 || $listTroop[$i]->id==19 || $listTroop[$i]->id==30)){					
								$spy=true;
								$sms = false;
							}
							//Kiem tra xem co cata khong:
							if($listTroop[$i]->sum>0 && $_POST['t'.($i+1)]>0 && ($listTroop[$i]->id=="9" || $listTroop[$i]->id=="20" || $listTroop[$i]->id=="31")){				
								$cata=true;	
							}
							//Kiem tra so luong nhap:
							if($_POST['t'.($i+1)]<=$listTroop[$i]->sum){
								$listTroopSend[$j]->sum=$_POST['t'.($i+1)];
							}else{
								$listTroopSend[$j]->sum=$listTroop[$i]->sum;
							}
							$listTroopSend[$j]->id=$listTroop[$i]->id;
							$tempSpeed[$j]=$listTroop[$i]->speed;
							$j++;
						}	
					}
					
					//Kiem tra du lieu form dua ve co dung hay ko (neu j>0 thi dung).
					if($j>0 || ($_POST['t12'] && $hero)){
						//chen vao bang attack:
						//Kiem tra xem co phai danh o oasis tu do khong:
						if($villageDefend->kind_id<7){
							//Khong phai danh o oasis:
							switch($_POST['st']){
								case 1:
									$sms = false;
									//Do tham:
									//Kiem tra lai xem co dung la truong hop di do tham hay ko:
									if($j==1 && $spy){
										switch($_POST['spy']){
											case 1:
												//Lay thong tin quan doi va tai nguyen
												$attack_id=InsertAttack($wg_village->id, $village_defend_id, 7);
												break;
											case 2:
												//Lay thong tin quan doi va cong trinh
												$attack_id=InsertAttack($wg_village->id, $village_defend_id, 8);
												break;
										}
									}								
									break;
								case 2:
									//Reinforcement
									$sms = false;
									$attack_id=InsertAttack($wg_village->id, $village_defend_id, 2);
									break;
								case 3:
									//attack Raid.
									$attack_id=InsertAttack($wg_village->id, $village_defend_id, 3);
									break;
								case 4:
									//attack normal.
									$kata=null;
									//neu co kata thi luu them thong tin building can pha:								
									if(is_numeric($_POST['kata'])){
										$rallyPointLevel=GetBuildingLevel($wg_village->id, 27);
										if($rallyPointLevel<5){
											if($_POST['kata']==-2){
												//chi duoc pha huy tai nguyen
												$kata=rand(1, 4)."|-4";
											}										
										}else{
											if($rallyPointLevel<10){
												//chi duoc pha tai nguyen va kho
												if(($_POST['kata']>=1 && $_POST['kata']<=4) || $_POST['kata']==10 ||$_POST['kata']==11){
													$kata=$_POST['kata']."|-4";
												}
											}else{
												if($rallyPointLevel<20){
													//co the pha huy tat ca cac cong trinh
													$kata=$_POST['kata']."|-4";											
												}else{
													//Co the pha huy hai cong trinh trong mot tran danh
													$kata=$_POST['kata']."|";
													$kata.=$_POST['kata2'];											
												}
											}
										}
										$attack_id=InsertAttack($wg_village->id, $village_defend_id, 9, $kata);
									}else{
										//Khong co cata:
										$attack_id=InsertAttack($wg_village->id, $village_defend_id, 4);
									}								
									break;
							}
						}else{
							//đánh bo lac:
							$sms = false;
							switch($_POST['st']){
								case 2:
									$attack_id=InsertAttack($wg_village->id, $village_defend_id, 2);
									break;
								case 3:
									$attack_id=InsertAttack($wg_village->id, $village_defend_id, 10);
									break;
								case 4:
									$attack_id=InsertAttack($wg_village->id, $village_defend_id, 11);
									break;
							}
						}
						
						//Chen vao bang attack_troop va tru troop trong lang.
						if($listTroopSend){
							foreach($listTroopSend as $troopSend){
								InsertAttackTroop($troopSend->id, $attack_id, $troopSend->sum);
								changeTroopVillage($wg_village->id, $troopSend->id, -$troopSend->sum);
							}
						}
						
						if($hero && $_POST['t12']==1){
							$tempSpeed[]=$hero->speed;
							InsertAttackHero($hero->id, $attack_id, 1);
							changeVillageOfHero($hero->id, "0");
						}
						
						//Chen status.
						$s=S($wg_village->x, $wg_village->y, $villageDefend->x, $villageDefend->y);
						$speed=min($tempSpeed);					
						$duration=($s/$speed)*3600;
						InsertAttackStatus($wg_village->id, $attack_id, date("Y:m:d H:i:s"), date("Y:m:d H:i:s", time()+ $duration), $duration);
						
						//gui SMS:
						if($sms){
							sendSMS($wg_village, $villageDefend, $_POST['st'], time()+ $duration);
						}							
						
						//hoan thanh send troop -> quay ve trang overview.
						header("Location: build.php?id=37");
					}
				}else{
					globalError2("Error!SendTroops");
				}
			}else{
				header("Location: build.php?id=37");
			}
		}
	}
	return DisplaySendTroop1();
}

/**
 * @author Le Van Tu
 * @todo gui SMS khi co thang tan cong
 */
function sendSMS($vla, $vld, $type, $timeEnd){
	global $db, $lang, $game_config, $user;
	if($vla->user_id != $vld->user_id){
		//kiem tra user thu co goi SMS khong
		if(checkSMSAttack($vld->user_id, $vld->id)){
			//lay thong tin user:
			$ua = getUser($vla->user_id);
			$ud = getUser($vld->user_id);
					
			include_once("./soap/call.php");
			//lay so dien thoai			
			$p = get_phone_remote($ud->username);
			if($p){
				//kiem tra asu:
				$asu_sms = getAsuSms();
				include_once("function_plus.php");
				$asu = showGold($ud->id);
				if($asu<$asu_sms){
					$rmasu = get_gold_remote($ud->username);
					$asu += $rmasu;
				}else{
					$rmasu = 0;
				}
				
				if($asu>$asu_sms){
					//tru asu
					withdrawGold($asu_sms, $ud->id, $rmasu, 16);
					
					if($type == 3){
						$ms = $ua->username." ".$lang['sms_tu_thanh']." ".$vla->name." ".$lang['sms_dot_kich']." ".$vld->name.$lang['sms_at']." ".date("H:i:s", $timeEnd)." ".$lang['sms_date']." ".date("d-m-Y", $timeEnd).".";
					}else{
						$ms = $ua->username." ".$lang['sms_tu_thanh']." ".$vla->name." ".$lang['sms_dot_kich']." ".$vld->name." ".$lang['sms_at']." ".date("H:i:s", $timeEnd)." ".$lang['sms_date']." ".date("d-m-Y", $timeEnd).".";
					}
						
					$t = date("Y-m-d H:i:s");
					$w = $game_config['server_name'];
					
					//them recode vao bang
					$sql = "INSERT INTO wg_att2mobile_queue (`service`, `defender`, `attacker`, `phone`, `message`, `world`, `time`) VALUES ('8054', '$ud->username', '$ua->username', '$p', '$ms', '$w', '$t')";
					$db->setQuery($sql);
					if(!$db->query()){
						globalError2($sql);
					}
				}
			}								
		}
	}		
}

/**
 * @author Le Van Tu
 * @todo lay so asu cua goi sms
 */
function getAsuSms(){
	global $db;
	$sql = "SELECT asu FROM wg_config_plus WHERE name='sms_attack'";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @todo Lay thong tin user
 */
function getUser($id){
	global $db;
	$sql = "SELECT * FROM wg_users WHERE id=$id";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}

/**
 * @author Le Van Tu
 * @todo kiem tra user co kich hoat goi nhan tin SMS khong
 */
function checkSMSAttack($u_id, $vl_id){
	global $db;
	$sql = "SELECT sms_attack FROM wg_plus WHERE user_id=$u_id";
	$db->setQuery($sql);
	if($db->loadResult()){
		//kiem tra xem co cuoc chien nao ko
		$sql = "SELECT COUNT(*) FROM wg_attack WHERE wg_attack.`status` =  '0' AND (wg_attack.`type` =  '3' OR wg_attack.`type` =  '4') AND wg_attack.village_defend_id =  '$vl_id'";
		$db->setQuery($sql);
		if($db->loadResult()>1){
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}

/**
 * @author Le Van Tu
 * @des lay thong tin lang can gui linh toi
 */
function getVillageDefend($id){
	global $db;
	$id = intval($id);
	$id = $db->getEscaped($id);
	$sql="SELECT * FROM wg_villages WHERE id=$id";
	$db->setQuery($sql);
	$db->loadObject($village);
	if($village){
		return $village;
	}else{
		$sql="SELECT * FROM wg_villages_map WHERE id=$id";
		$db->setQuery($sql);
		$db->loadObject($village);
		if($village){
			return $village;
		}
	}
}

/**
 * @author Le Van Tu
 * @des kiem tra xem mot lang co phai la lang dang duoc bao ve hay khong
 * @param $village_id id cua lang
 * @return true or false
 */
function isPrivateVillage($village_id){
	global $db, $game_config;
	$sql="SELECT
				wg_users.active_time 
			FROM
				wg_users ,
				wg_villages 
			WHERE
				wg_users.id =  wg_villages.user_id AND 
				wg_villages.id =  '$village_id' 
			GROUP BY
				wg_users.id";
	$db->setQuery($sql);
	$activeTime=$db->loadResult();
	if((strtotime($activeTime)+$game_config['protect_days']*24*3600)>time()){
		return (strtotime($activeTime)+$game_config['protect_days']*24*3600);
	}else{
		return false;
	}
} 

/**
 * Hien thi form cata khi co cata di cung:
 */
function DisplaySendCatapult($villageDefend, $listTroop){
	global $db, $lang, $wg_village, $wg_buildings;
	includelang("catapult");
	includelang("building_name");
	$parse=$lang;
	$rallyPointLevel=GetBuildingLevel($wg_village->id, 27);
	
	
	$hero=GetHeroVillage($wg_village->id);
	
	$soldierName = get_soldier($wg_village->nation_id);
	
	for($i=0; $i<11; $i++){
		if($_POST['t'.($i+1)] > 0 && $listTroop[$i]->sum>0){
			if($_POST['t'.($i+1)]<=$listTroop[$i]->sum){
				$parse['t'.($i+1)]=$_POST['t'.($i+1)];
			}else{
				$parse['t'.($i+1)]=$listTroop[$i]->sum;
			}			
			$parse['class'.($i+1)]="";
			$tempSpeed[]=$listTroop[$i]->speed;
		}else{
			$parse['t'.($i+1)]=0;
			$parse['class'.($i+1)]="c";
		}
		$parse['icon'.($i+1)]=$listTroop[$i]->icon;
		$parse['title'.($i+1)]=$lang[$soldierName.($i+1)];			
	}
	
	if($hero && $_POST['t12']>0){
		$parse['t'.($i+1)]=1;
		$parse['class'.($i+1)]="";
		$tempSpeed[]=$hero->speed;
	}else{
		$parse['t'.($i+1)]=0;
		$parse['class'.($i+1)]="c";
	}
	$parse['icon12']="images/icon/hero4.ico";
	$parse['title12']=$lang['hero'];
	
	switch($_POST['st']){
		case 2:
			$parse['send_type_string']=$lang["Reinforcement for"];
			break;
		case 3:
			$parse['send_type_string']=$lang["Raiding"];
			break;
		case 4:
			$parse['send_type_string']=$lang["Attacking"];
			break;		
	}
	
	//lay toc do cua linh di cham nhat.
	$speed=min($tempSpeed);
	$s=S($wg_village->x, $wg_village->y, $villageDefend->x, $villageDefend->y);
	$duration=GetDuration($s, $speed);
	$parse['duration']=TimeToString($duration);
	$parse['time_at']=date("H:i:s", $duration+time());
	$parse['date_at']=date("d-m-Y", $duration+time());
	
	$parse['send_type']=$_POST['st'];
	$parse['village_attack_name']=$wg_village->name;
	$parse['village_defend_name']=$villageDefend->name;
	$parse['village_defend_id']=$villageDefend->id;
	$parse['player_defend_name']=GetPlayerName($villageDefend->id);
	$parse['village_defend_x']=$villageDefend->x;
	$parse['village_defend_y']=$villageDefend->y;
	$parse['x']=$villageDefend->x;
	$parse['y']=$villageDefend->y;
	$parse['uid']=$villageDefend->user_id;
	
	switch($_POST['st']){
		case 3:
			//dot kich thi cata ko co tac dung
			return parsetemplate(gettemplate("send_catapult_form"), $parse);
			break;
		case 4:
			//Tu chien thi cata co tac dung
			if($rallyPointLevel<5){
				//co the pha huy random cac cong trinh khai thac tai nguyen
				$parse['kata']=-2;				
				return parsetemplate(gettemplate("send_catapult_form_1"), $parse);
			}else{
				if($rallyPointLevel<10){
					return parsetemplate(gettemplate("send_catapult_form_2"), $parse);
				}else{
					if($rallyPointLevel<20){
						return parsetemplate(gettemplate("send_catapult_form_3"), $parse);
					}else{
						return parsetemplate(gettemplate("send_catapult_form_4"), $parse);
					}
				}
			}
			break;
	}
}


function CombatSimulator($village_id){

}

function DisplaySendSpy($villageDefend, $listTroop){
	global $db, $lang, $wg_village;
	includelang("troop");
	$parse=$lang;
	
	$soldierName = get_soldier($wg_village->nation_id);
	
	for($i=0; $i<11; $i++){
		if($_POST['t'.($i+1)] > 0 && $listTroop[$i]->sum>0){
			if($_POST['t'.($i+1)]<=$listTroop[$i]->sum){
				$parse['t'.($i+1)]=$_POST['t'.($i+1)];
			}else{
				$parse['t'.($i+1)]=$listTroop[$i]->sum;
			}			
			$parse['class'.($i+1)]="";
			$tempSpeed[]=$listTroop[$i]->speed;
			$j++;
		}else{
			$parse['t'.($i+1)]=0;
			$parse['class'.($i+1)]="c";
		}
		$parse['icon'.($i+1)]=$listTroop[$i]->icon;
		$parse['title'.($i+1)]=$lang[$soldierName.($i+1)];
	}
	
	$parse['t'.($i+1)]=0;
	$parse['class'.($i+1)]="c";
	
	$parse['icon12']="images/icon/hero4.ico";
	$parse['title12']=$lang['hero'];
	
	//lay toc do cua linh di cham nhat.
	$speed=min($tempSpeed);
	$s=S($wg_village->x, $wg_village->y, $villageDefend->x, $villageDefend->y);
	
	$duration=GetDuration($s, $speed);
	$parse['duration']=TimeToString($duration);
	$parse['time_at']=date("H:i:s", $duration+time());
	$parse['date_at']=date("d-m-Y", $duration+time());
	$parse['village_attack_name']=$wg_village->name;
	$parse['village_defend_name']=$villageDefend->name;
	$parse['village_defend_id']=$villageDefend->id;
	$parse['player_defend_name']=GetPlayerName($villageDefend->id);
	$parse['x']=$villageDefend->x;
	$parse['y']=$villageDefend->y;
	$parse['uid']=$villageDefend->user_id;
	$parse['send_type']=1;
	return parsetemplate(gettemplate("spy_form"), $parse); 
}

function DisplaySendTroop1($error=0, $privateTime=0){
	global $lang, $wg_village;
	includelang("troop");
	$parse=$lang;
	$listTroop=GetListTroopVilla($wg_village);
	$hero=GetHeroVillage($wg_village->id);
	
	switch($error){
		case 1:
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['There is no village with that name']));
			break;
		case 2:
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['There is no village at those coordinates']));
			break;
		case 3:
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['No troops have been selected']));
			break;
		case 4:
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['Enter coordinates or village name']));
			break;
		case 5:
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['quan_da_o_trong_lang_nay']));
			break;
		case 6:
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['private_village']."<br>".$lang['time_private'].date("H:i:s", $privateTime)." ".$lang['date']." ".date("d-m-Y", $privateTime)."."));
			break;
		case 7:
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['user_banned']));
			break;
		case 8:
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['hotro_free_oasis']));
			break;
		case 9:
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['thanh_tan_cong_dang_trong_thoi_gian_bao_ve'].date("H:i:s", $privateTime)." ".$lang['date']." ".date("d-m-Y", $privateTime)."."));
			break;
		case 10: //thanh bi danh dang dinh chien
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['thanh_bi_cong_dang_trong_thoi_gian_dinh_chien'].date("H:i:s", $privateTime)." ".$lang['date']." ".date("d-m-Y", $privateTime)."."));
			break;
		case 11:	//thanh tan cong dang dinh chien
			$parse['error_message'] = parsetemplate(gettemplate("error"), array("message" => $lang['thanh_tan_cong_dang_trong_thoi_gian_dinh_chien'].date("H:i:s", $privateTime)." ".$lang['date']." ".date("d-m-Y", $privateTime)."."));
			break;
	}
	
	if(isset($_POST['vn'])){
		$parse['t1']=$_POST['t1'];
		$parse['t2']=$_POST['t2'];
		$parse['t3']=$_POST['t3'];
		$parse['t4']=$_POST['t4'];
		$parse['t5']=$_POST['t5'];
		$parse['t6']=$_POST['t6'];
		$parse['t7']=$_POST['t7'];
		$parse['t8']=$_POST['t8'];
		$parse['t9']=$_POST['t9'];
		$parse['t10']=$_POST['t10'];
		$parse['t11']=$_POST['t11'];
		$parse['t12']=$_POST['t12'];
		$parse['x']=$_POST['x'];
		$parse['y']=$_POST['y'];
		$parse['vn']=$_POST['vn'];
		$parse['checked_2']="";
		$parse['checked_3']="";
		$parse['checked_4']="";
		switch($_POST['st']){
			case 2:
				$parse['checked_2']="checked";
				break;
			case 3:
				$parse['checked_3']="checked";
				break;
			case 4:
				$parse['checked_4']="checked";
				break;
		}
	}else{
		$parse['t1']="";
		$parse['t2']="";
		$parse['t3']="";
		$parse['t4']="";
		$parse['t5']="";
		$parse['t6']="";
		$parse['t7']="";
		$parse['t8']="";
		$parse['t9']="";
		$parse['t10']="";
		$parse['t11']="";
		$parse['t12']="";
		$parse['x']="";
		$parse['y']="";
		$parse['vn']="";
		$parse['checked_2']="checked";
		$parse['error_message']="";
	}
	
	for($i=0; $i<11; $i++){
		if($listTroop[$i]->sum>0){
			$parse['sum'.($i+1)] = "<a href=\"#\" onClick=\"document.snd.t".($i+1).".value=".$listTroop[$i]->sum."; return false;\">(".$listTroop[$i]->sum.")</a>";
			$parse['class'.($i+1)]="f8";
		}else{
			$parse['sum'.($i+1)]="<b>(0)</b>";
			$parse['class'.($i+1)]="f8 c b";
		}
		$parse['icon'.($i+1)]=$listTroop[$i]->icon;
		$parse['title'.($i+1)]=$lang[$listTroop[$i]->name];
	}
	
	if($hero){
		$parse['sum12']="<a href=\"#\" onClick=\"document.snd.t12.value=1; return false;\">(1)</a>";
		$parse['class12']="f8";
		$parse['title12']=$hero->name;
	}else{
		$parse['sum12']="<b>(0)</b>";
		$parse['class12']="f8 c b";
		$parse['title12']=$lang['hero'];
	}	
	
	if(isset($_GET['vdx'])){
		$parse['x']=$_GET['vdx'];
		$parse['y']=$_GET['vdy'];
	}
	
	return parsetemplate(gettemplate('send_troop_body'), $parse);
}

function DisplaySendTroop2($villageDefend, $listTroop){
	global $lang, $wg_village;
	includelang("troop");
	$parse=$lang;
	
	$listTroop=GetListTroopVilla($wg_village);
	$hero=GetHeroVillage($wg_village->id);
	$soldierName = get_soldier($wg_village->nation_id);
	
	$j=0;
	for($i=0; $i<11; $i++){
		if($_POST['t'.($i+1)] > 0 && $listTroop[$i]->sum>0){
			if(round($_POST['t'.($i+1)], 0)<=$listTroop[$i]->sum){
				$parse['t'.($i+1)]=round($_POST['t'.($i+1)], 0);
			}else{
				$parse['t'.($i+1)]=$listTroop[($i+1)]->sum;
			}			
			$parse['class'.($i+1)]="";
			$tempSpeed[$j]=$listTroop[$i]->speed;
			$j++;
		}else{
			$parse['t'.($i+1)]=0;
			$parse['class'.($i+1)]="c";
		}
		$parse['icon'.($i+1)]=$listTroop[$i]->icon;
		$parse['title'.($i+1)]=$lang[$soldierName.($i+1)];
	}
	
	if($hero && $_POST['t12']==1){
		$parse['t12']=1;
		$parse['class12']="";
		$tempSpeed[$j]=$hero->speed;
	}else{
		$parse['t12']=0;
		$parse['class12']="c";
	}		
	$parse['title12']=$lang['hero'];
	
	if($villageDefend->kind_id<7){
		//tấn công lang:
		$parse['village_defend_name']=$villageDefend->name;
		$parse['player_defend_name']=GetPlayerName($villageDefend->id);
	}else{
		//tan cong bo lac:
		if($villageDefend->user_id){
			//bo lac da co nguoi thu phuc:
			$parse['village_defend_name']=$villageDefend->name;
			$parse['player_defend_name']=GetPlayerName($villageDefend->id);
		}else{
			if($_POST['st'] == 2){
				return DisplaySendTroop1(8);
			}
			//$_POST['st']=4;
			$parse['player_defend_name']="";
			$parse['Player']="";
		}
		$parse['village_defend_name']=$lang['oasis'];
	}
	
	switch($_POST['st']){
		case 2:
			$parse['send_type_string']=$lang["Reinforcement for"];
			break;
		case 3:
			$parse['send_type_string']=$lang["Raid"];
			break;
		case 4:
			$parse['send_type_string']=$lang["Normal"];
			break;
		
	}
	
	//lay toc do cua linh di cham nhat:
	$speed=min($tempSpeed);
	
	$s=S($wg_village->x, $wg_village->y, $villageDefend->x, $villageDefend->y);
	
	$duration=GetDuration($s, $speed);
	$parse['duration']=TimeToString($duration);
	$parse['time_at']=date("H:i:s", $duration+time());
	$parse['date_at']=date("d-m-Y", $duration+time());
	
	$parse['send_type']=$_POST['st'];
	$parse['village_attack_name']=$wg_village->name;	
	$parse['uid']=$villageDefend->user_id;
	$parse['village_defend_id']=$villageDefend->id;
	
	$parse['village_defend_x']=$villageDefend->x;
	$parse['village_defend_y']=$villageDefend->y;
	return parsetemplate(gettemplate('send_troop_body2'), $parse);
}

/**
 * @author Le Van Tu
 * @des Kiem tra vi tri mot thanh de gui linh
 * @param toa do cua thanh
 */
function checkVillageLocationSendTroop($x, $y){
	global $db, $game_config;
	if((abs($x)>$game_config['max_x'])||(abs($y)>$game_config['max_y'])){
		return false;
	}
	
	$x = $db->getEscaped($x);
	$y = $db->getEscaped($y);
	$sql="SELECT * FROM wg_villages WHERE x=$x And y=$y AND user_id!=0";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village){
		return $village;
	}else{
		//Kiem tra xem co phai bo lac hay khong:
		$sql="SELECT * FROM wg_villages_map WHERE x=$x And y=$y AND kind_id>=7 AND user_id=0";
		$db->setQuery($sql);
		$village=null;
		$db->loadObject($village);
		if($village){
			return $village;
		}
	}
	return false;
}


/**
*	Chèn một record vao wg_addtack.
*/
function InsertAttack($village_attack_id, $village_defend_id, $type, $building_type_id=""){
	global $db;
	$building_type_id = $db->getEscaped($building_type_id);
	$sql = "INSERT INTO wg_attack (`village_attack_id` ,`village_defend_id` , wg_attack.type, building_type_id) VALUES ($village_attack_id, $village_defend_id, $type, '$building_type_id')";
	$db->setQuery($sql);
	if($db->query()){
		return $db->insertid();
	}else{
		globalError2("Error!!!InsertAttack");
	}	
}

/**
 * @author Le Van Tu
 * @des chen mot record vao bang wg_attack_troop
 * @param1 id troop
 * @param2 id wg_attack
 * @param3 sum troop
 */
function InsertAttackTroop($troop_id, $attack_id, $num){
	global $db;
	$sql="INSERT INTO wg_attack_troop (troop_id, num, attack_id) VALUES ($troop_id, $num, $attack_id)";
	$db->setQuery($sql);
	if($db->query()){
		return true;
	}else{
		globalError2("Error!!!InsertAttackTroop");
	}
}

/**
 * @author Le Van Tu
 * @des chen mot record vao bang wg_attack_hero
 * @param1 id hero
 * @param2 id wg_attack
 * @param3 sum troop
 */
function InsertAttackHero($hero_id, $attack_id, $num){
	global $db;
	$sql="INSERT INTO wg_attack_hero (hero_id, num, attack_id) VALUES ($hero_id, $num, $attack_id)";
	$db->setQuery($sql);
	if($db->query()){
		return true;
	}else{
		globalError2("Error!!!InsertAttackTroop");
	}
}

function InsertAttackStatus($village_id, $object_id, $time_begin, $time_end, $cost_time){
	global $db;
	$sql="INSERT INTO `wg_status` (`object_id`, `village_id`, wg_status.type, `time_begin`, `time_end`, `cost_time`, `status`) VALUES ($object_id, $village_id, 7, '$time_begin', '$time_end', $cost_time, 0)";
	$db->setQuery($sql);
	if($db->query()){
		return true;
	}else{
		globalError2("error!!!InsertAttackStatus");
	}
}

/**
 * @author Le Van Tu
 * @des thay doi so linh trong lang.
 * @param1 id village
 * @param2 id troop
 * @param3 sum troop
 */
function changeTroopVillage($village_id, $troop_id, $sum){
	global $db;
	$sql="UPDATE wg_troop_villa SET num=num+($sum) WHERE troop_id=$troop_id AND village_id=$village_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi cap nhat linh cua lang phong thu sau khi danh!");	
	}
} 

/**
 * @author Le Van Tu
 * @des cap nhat so linh bi chet.
 * @param1 id wg_attack_troop
 * @param2 sum troop
 */
function changeAttackTroop($id, $sum){
	global $db;
	$sql="UPDATE wg_attack_troop SET num=num+($sum) WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi cap nhat so linh trong bang attack_troop: id = $id  -  sum = $sum");
	}
} 

//Lay danh sach linh cua mot attack.
function GetListAttackTroop($attack_id){
	global $db;
	$result=array();
	$attack_id = $db->getEscaped($attack_id);
	$sql="SELECT * FROM wg_attack_troop WHERE wg_attack_troop.status=0 AND attack_id=$attack_id";
	$db->setQuery($sql);
	$troopList=null;
	$troopList=$db->loadObjectList();
	if($troopList){
		//luu lai duoi dinh dang khac.
		foreach($troopList as $troop){
			$result[$troop->troop_id]['id']=$troop->troop_id;
			$result[$troop->troop_id]['sum']=$troop->num - $troop->die_num;
			$result[$troop->troop_id]['attack_troop_id']=$troop->id;
		}		
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des lay hero cua mot attack.
 * @param id wg_attack
 */
function GetAttackHero($attack_id){
	global $db;
	$attack_id = $db->getEscaped($attack_id);
	$sql="SELECT
				wg_heros.*, 
				wg_attack_hero.num,
				wg_attack_hero.die_num, 
				wg_attack_hero.id AS attack_hero_id
			FROM
				wg_heros ,
				wg_attack_hero
			WHERE
				wg_attack_hero.attack_id = $attack_id AND
				wg_heros.id =  wg_attack_hero.hero_id  
			GROUP BY
				wg_heros.id";
	$db->setQuery($sql);
	$db->loadObject($hero);
	if($hero){
		return GetHeroInfoAttack($hero);
	}else{
		return false;
	}
}

//---------end cac ham lien quan toi send troop---------->


/**
*	lay danh sach tat ca linh trong bang wg_troop len de tinh toan attack.
* 	return: $result[$troop->id]['attack']=$troop->attack;
*/
function getArrayOfTroops(){
	global $db, $game_config;
	$sql="SELECT * FROM wg_troops";
	$db->setQuery($sql);
	$troopList=$db->loadObjectList();
	//luu lai duoi dang mot mang.
	foreach($troopList as $troop){
		$result[$troop->id]['name']				= $troop->name;
		$result[$troop->id]['icon']				= $troop->icon;
		$result[$troop->id]['keep_hour']		= $troop->keep_hour;
		$result[$troop->id]['attack']			= $troop->attack;
		$result[$troop->id]['melee_defense']	= $troop->melee_defense;
		$result[$troop->id]['ranger_defense']	= $troop->ranger_defense;
		$result[$troop->id]['magic_defense']	= $troop->magic_defense;
		$result[$troop->id]['hitpoint']			= $troop->hitpoint;
		$result[$troop->id]['type']				= $troop->type;
		$result[$troop->id]['time_train']		= $troop->time_train/$game_config['k_train'];
		$result[$troop->id]['speed']			= $troop->speed*$game_config['k_speed'];
		$result[$troop->id]['carry']			= $troop->carry;
		$result[$troop->id]['nation_id']			= $troop->nation_id;
		$result[$troop->id]['rs1']				= $troop->rs1;
		$result[$troop->id]['rs2']				= $troop->rs2;
		$result[$troop->id]['rs3']				= $troop->rs3;
		$result[$troop->id]['rs4']				= $troop->rs4;
		$result[$troop->id]['rsi1']				= $troop->rsi1;
		$result[$troop->id]['rsi2']				= $troop->rsi2;
		$result[$troop->id]['rsi3']				= $troop->rsi3;
		$result[$troop->id]['rsi4']				= $troop->rsi4;
		$result[$troop->id]['building_type_id']	= $troop->building_type_id;
		$result[$troop->id]['time_item']		= $troop->time_item/$game_config['k_research'];		  	
	}
	return $result;
}

/**
 * set status cho attack_troop trong truong hop linh danh chet het.
 * $sql="UPDATE wg_attack_troop SET wg_attack_troop.status=1 WHERE id=$id";
 */
function SetAttackTroopStaus($id){
	global $db;
	$sql="UPDATE wg_attack_troop SET wg_attack_troop.status=1 WHERE id=$id";
	$db->setQuery($sql);
	return $db->query();
}

function SetAllAttackTroopStaus($attack_id){
	global $db;
	$sql="UPDATE wg_attack_troop SET wg_attack_troop.status=1 WHERE attack_id=$attack_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi cap nhat status trong bang wg_attack_troop SetAllAttackTroopStaus($attack_id)");
	}
}

/**
 * Tính suc chua cua cac mat that trong lang.
 */
function GetHideRS($village_id){
	global $db;
	$sql="SELECT product_hour FROM wg_buildings WHERE vila_id=$village_id AND type_id=15";
	$db->setQuery($sql);
	$buildingList=$db->loadObjectList();
	if($buildingList){
		foreach($buildingList as $building){
			$result+=$building->product_hour;
		}
		return $result;
	}
	return 0;
}


function InsertStatusTroopAttackBack($attack_id, $village_id, $time_begin, $time_end, $cost_time){
	global $db;
	$sql="INSERT INTO wg_status (object_id, village_id, type, time_begin, time_end, cost_time, status) VALUES ($attack_id, $village_id, 10, '$time_begin', '$time_end', $cost_time, 0)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2('Error function InsertStatusTroopAttackBack'.$sql);
		return false;
	}
	return true;
}

function GetPlayerID($village_id){
	global $db;
	$sql="SELECT user_id FROM wg_villages WHERE id=$village_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @des Tang so linh trong lang
 * @param1 id cua lang
 * @param2 id cua troop
 * @param3 so linh
 * @param hero id neu la hero
 */
function addTroopVilla($village_id, $troop_id, $num, $hero_id=0){
	global $db;
	$sql="SELECT COUNT(*) FROM wg_troop_villa WHERE village_id=$village_id AND troop_id=$troop_id";
	$db->setQuery($sql);
	if($db->loadResult()){
		if($hero_id!=0){
			$sql="UPDATE wg_troop_villa SET num=num+($num), hero_id=$hero_id WHERE village_id=$village_id AND troop_id=$troop_id";
		}else{
			$sql="UPDATE wg_troop_villa SET num=num+($num) WHERE village_id=$village_id AND troop_id=$troop_id";
		}		
		$db->setQuery($sql);
		if(!$db->query()){
			globalError2("Loi khong up date linh addTroopVilla($village_id, $troop_id, $num, $hero_id)");
		}
	}else{
		$sql="INSERT INTO wg_troop_villa (village_id, troop_id, num, hero_id) VALUES ($village_id, $troop_id, $num, $hero_id)";
		$db->setQuery($sql);
		if(!$db->query()){
			globalError2("Loi khong insert duoc mot loai linh moi cho village addTroopVilla($village_id, $troop_id, $num, $hero_id)");
		}
	}	
}


function ChangeTroopKeepVillage($village_id, $sum){
	global $db;
	$sql="UPDATE wg_villages SET troop_keep=troop_keep+($sum) WHERE id=$village_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi khong cap nhat troop keep ChangeTroopKeepVillage($village_id, $sum)");
	}
}


/**
 * set staus=1 trong bang wg_attack sau khi danh xong.
 */
function SetAttackStatus($attack_id, $status=1){
	global $db;
	$sql = "UPDATE `wg_attack` SET `status` = $status WHERE id=$attack_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Error!!!!!!!!!!!SetAttackStatus");
	}
}

function GetBuildingTrainTroopLevel($village_id, $building_type_id){
	global $db;
	$sql="SELECT level FROM wg_buildings WHERE vila_id=$village_id AND type_id=$building_type_id";
	$db->setQuery($sql);
	return $db->loadResult($sql);	
}

/**
 * chen status du chiem loi pham ve lang.
 */
function InsertBountyStatus($village_id, $object_id, $time_begin, $time_end, $cost_time){
	global $db;
	$sql="INSERT INTO wg_status (object_id, village_id, wg_status.type, time_begin, time_end, cost_time, wg_status.status) VALUES ($object_id, $village_id, 16, '$time_begin', '$time_end', $cost_time, 0)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Error!!!!!InsertBountyStatus");
	}else{
		return true;
	}
}

//kiem tra co ton tai attack co id=$attack_id, village_attack_id=$village_id va type=5 ko
function CheckReinforceForID($village_id, $attack_id){
	global $db;
	$attack_id = $db->getEscaped($attack_id);
	$sql="SELECT village_defend_id FROM wg_attack WHERE id=$attack_id AND village_attack_id=$village_id AND wg_attack.status=0 AND type=1";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
*kiem tra co ton tai attack co id=$attack_id, village_defend_id=$village_id ko
*/
function CheckReinforceID($village_id, $attack_id){
	global $db;
	$attack_id = $db->getEscaped($attack_id);
	$sql="SELECT village_attack_id FROM wg_attack WHERE id=$attack_id AND village_defend_id=$village_id AND wg_attack.status=0 AND type=1";
	$db->setQuery($sql);
	return $db->loadResult();
}
/**
*	Lay toc do ve cua linh di ho tro lang khac
*/
function GetSpeedAttackTroopBack($attack_id){
	global $db;
	$result=null;
	$arrayTroop=GetArrayOfTroops();
	if($attackTroopList=GetListAttackTroop($attack_id)){
		foreach($attackTroopList as $tid=>$attackTroop){
			if($attackTroop['sum']>0){
				$tempSpeed[]=$arrayTroop[$tid]['speed'];
			}			
		}
		if(count($tempSpeed)>0){
			$result=min($tempSpeed);
		}
	}
	return $result;
}

/**
 * doi type=2 (linh di ho tro) thanh type=5 (linh ho tro ve lang)
 */
function SetReinforceType($attack_id){
	global $db;
	$sql="UPDATE wg_attack SET wg_attack.type=5 WHERE id=$attack_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Error!!!SetReinforceType");
	}else{
		return true;
	}
}

/**
 * hien thi trang thai di chuyen cua linh (o trang ngoai thanh)
 */
function GetTroopMoveStatus($village_id, $timer=0){
	global $db, $lang;
	includelang("move");
	$parse=$lang;
	include_once("function_foundvillage.php");
	$rallyPoint=GetRallyPoint($village_id);
	$parse['id']=$rallyPoint->index;
	$parse['stt']=$rallyPoint->id;
	
	//Lay danh sach linh dang toi danh
	$sql="SELECT * FROM wg_attack WHERE village_defend_id=$village_id AND (type=3 OR type=4 OR type=9 OR type=10 OR type=11 OR type=12) AND wg_attack.status=0";
	$db->setQuery($sql);
	$attackList=$db->loadObjectList();
	if($attackList){
		//co lang toi danh.
		$i=0;
		foreach($attackList as $attack){
			//lay thoi gian nho nhat va so lang toi danh
			$sql="SELECT time_end FROM wg_status WHERE object_id=$attack->id AND wg_status.type=7 AND wg_status.status=0";
			$db->setQuery($sql);
			$timeEnd=strtotime($db->loadResult());
			if($timeEnd>time()){
				$arrayTimeEnd[$i]=$timeEnd-time();
				$i++;
			}			
		}
		if($i>0){
			$timer++;
			$parse['duration']=TimeToString(min($arrayTimeEnd));
			$parse['mui_ten']='«';
			$parse['sum']=$i;
			$parse['timer']=$timer;
			$parse['class']='c5 f10';
			$parse['title']=$lang['Attack'];
			$parse['image']="images/un/a/att1.gif";
			$rows.=parsetemplate(gettemplate("troop_move_status_row"), $parse);			
		}		
	}
	
	//lay trang thai linh lang khac ho tro
	$sql="SELECT * FROM wg_attack WHERE village_defend_id=$village_id AND wg_attack.type=2 AND wg_attack.status=0";
	$db->setQuery($sql);
	$attackList=null;
	$attackList=$db->loadObjectList();
	if($attackList){
		//co lang toi danh.
		$i=0;
		foreach($attackList as $attack){
			//lay thoi gian nho nhat va so lang toi danh
			$sql="SELECT time_end FROM wg_status WHERE object_id=$attack->id AND wg_status.type=7 AND wg_status.status=0";
			$db->setQuery($sql);
			$timeEnd=strtotime($db->loadResult());
			if($timeEnd>time()){
				$arrayTimeEnd[$i]=$timeEnd-time();
				$i++;
			}			
		}
		if($i>0){
			$timer++;
			$parse['duration']=TimeToString(min($arrayTimeEnd));
			$parse['mui_ten']='«';
			$parse['sum']=$i;
			$parse['timer']=$timer;
			$parse['title']=$lang['ref'];
			$parse['class']='c3 f10';
			$parse['image']="images/un/a/def1.gif";
			$rows.=parsetemplate(gettemplate("troop_move_status_row"), $parse);			
		}		
	}
	
	
	//lay danh sach linh di danh lang khac.
	$sql="SELECT wg_status.time_end 
			FROM wg_status, wg_attack 
			WHERE wg_status.object_id=wg_attack.id AND
				 village_id=$village_id AND 
				 wg_status.status=0 AND 
				 wg_status.type=7 AND
				 (wg_attack.type=3 OR wg_attack.type=4 OR wg_attack.type=6 OR wg_attack.type=7 OR wg_attack.type=8 OR wg_attack.type=9 OR wg_attack.type=10 OR wg_attack.type=11)";
	$db->setQuery($sql);
	$statusList=null;
	$statusList=$db->loadObjectList();
	if($statusList){
		$arrayTimeEnd=null;
		$i=0;
		foreach($statusList as $status){
			if(strtotime($status->time_end)>time()){
				$arrayTimeEnd[$i]=strtotime($status->time_end)-time();
				$i++;
			}
		}
		if($i>0){
			$timer++;
			$parse['duration']=TimeToString(min($arrayTimeEnd));
			$parse['mui_ten']='»';
			$parse['sum']=$i;
			$parse['timer']=$timer;
			$parse['class']='c4 f10';
			$parse['title']=$lang['Attack'];
			$parse['image']="images/un/a/att2.gif";
			$rows.=parsetemplate(gettemplate("troop_move_status_row"), $parse);			
		}
	}
	
	//lay trang thai linh di ho tro lang khac
	$sql="SELECT * FROM wg_attack WHERE village_attack_id=$village_id AND wg_attack.type=2 AND wg_attack.status=0";
	$db->setQuery($sql);
	$attackList=null;
	$attackList=$db->loadObjectList();
	if($attackList){
		//co lang toi danh.
		$i=0;
		foreach($attackList as $attack){
			//lay thoi gian nho nhat va so lang toi danh
			$sql="SELECT time_end FROM wg_status WHERE object_id=$attack->id AND wg_status.type=7 AND wg_status.status=0";
			$db->setQuery($sql);
			$timeEnd=strtotime($db->loadResult());
			if($timeEnd>time()){
				$arrayTimeEnd[$i]=$timeEnd-time();
				$i++;
			}			
		}
		if($i>0){
			$timer++;
			$parse['duration']=TimeToString(min($arrayTimeEnd));
			$parse['mui_ten']='»';
			$parse['sum']=$i;
			$parse['timer']=$timer;
			$parse['class']='c4 f10';
			$parse['title']=$lang['ref'];
			$parse['image']="images/un/a/def2.gif";
			$rows.=parsetemplate(gettemplate("troop_move_status_row"), $parse);			
		}		
	}
	
	
	//lay trang thai linh tro ve lang
	$sql="SELECT * FROM wg_status WHERE village_id=$village_id AND wg_status.status=0 AND type=10";
	$db->setQuery($sql);
	$statusList=null;
	$statusList=$db->loadObjectList();
	if($statusList){
		$arrayTimeEnd=null;
		$i=0;
		foreach($statusList as $status){
			if(strtotime($status->time_end)>time()){
				$arrayTimeEnd[$i]=strtotime($status->time_end)-time();
				$i++;
			}
		}
		if($i>0){
			$timer++;
			$parse['duration']=TimeToString(min($arrayTimeEnd));
			$parse['mui_ten']='«';
			$parse['sum']=$i;
			$parse['timer']=$timer;
			$parse['class']='c3 f10';
			$parse['title']=$lang['return'];
			$parse['image']="images/un/a/def1.gif";
			$rows.=parsetemplate(gettemplate("troop_move_status_row"), $parse);			
		}
	}
	
	if($rows){
		$parse['rows']=$rows;
		$result=parsetemplate(gettemplate("troop_move_status"), $parse);
	}else{
		$result='';
	}
	return $result;
}
//-----------end cac ham lien quan den attack------------------------------>

//-----------Cac ham lien quan den nang cap vu khi cho quan doi------------>
/**
 * Hien thi trang thai nang cap vu khi.
 */
function ShowImprovement($building){
	global $lang, $wg_village;
	includelang("improve");
	$parse=$lang;	
	if($_GET['t']==1){
		//show thong tin ao giap:
		$parse['class_weapon']="";
		$parse['class_armour']="class ='selected'";
		
		//lay trang thai nang cap ao giap
		$parse['develop_status']=GetDevelopmentArmourStatus();
		
		if($parse['develop_status']){
			//Co item dang duoc nang cap -> ko duoc research them
			$listItem=GetListArmour($wg_village, $building, false);
		}else{
			//Lay danh sach vi khi
			$listItem=GetListArmour($wg_village, $building);
			
			//kiem tra xem co yeu cau nang cap ko
			if(is_numeric($_GET['tid'])){
				//kiem tra xem co du dieu kien de nang cap hay ko
				if($listItem[$_GET['tid']]['ok']){
					//du dieu kien -> tien hanh nang cap
					//kiem tra level hien tai
					if($listItem[$_GET['tid']]['level']>0){
						//tang level trong bang items
						TroopArmourLevelUp($listItem[$_GET['tid']]['item_id']);
						$object_id=$listItem[$_GET['tid']]['item_id'];
					}else{
						//chen moi vao bang wg_troop_items voi level =1
						$object_id=InsertTroopArmour($wg_village->id, $_GET['tid']);
					}
					//chen status
					InsertTroopArmourLevelUpStatus($wg_village->id, $object_id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$listItem[$_GET['tid']]['time_item']), $listItem[$_GET['tid']]['time_item']);
					
					//Tru RS:
					$wg_village->rs1 -= $listItem[$_GET['tid']]['rsi1'];
					$wg_village->rs2 -= $listItem[$_GET['tid']]['rsi2'];
					$wg_village->rs3 -= $listItem[$_GET['tid']]['rsi3'];
					$wg_village->rs4 -= $listItem[$_GET['tid']]['rsi4'];
					
					//Lay lai danh sach vi khi
					$listItem=GetListArmour($wg_village, $building, false);
					$parse['develop_status']=GetDevelopmentArmourStatus($wg_village->id);
				}
			}		
		}
		
		//show bang ao giap:
		if($listItem){
			foreach($listItem as $item){
				$parse['rsi1']=$item['rsi1'];
				$parse['rsi2']=$item['rsi2'];
				$parse['rsi3']=$item['rsi3'];
				$parse['rsi4']=$item['rsi4'];
				$parse['duration']=TimeToString($item['time_item']);
				$parse['icon']=$item['icon'];
				$parse['item_level']=$item['level'];
				$parse['troop_name']=$item['troop_name'];
				$parse['action']=$item['action'];
				$rows.=parsetemplate(gettemplate("improve_row"), $parse);
			}
			$parse['Blacksmith']=$lang['amour'];
			$parse['rows']=$rows;
			$parse['task_content']=parsetemplate(gettemplate("improve_weapon_body"), $parse);	
		}
	}else{
		$parse['class_weapon']="class ='selected'";
		$parse['class_armour']="";
		//Show trang thai ao giap.
		
		$parse['develop_status']=GetDevelopmentStatus($wg_village->id);
		
		if($parse['develop_status']){
			$listItem=GetListItem($wg_village, $building, false);	
		}else{
			//Lay danh sach vi khi
			$listItem=GetListItem($wg_village, $building, true);
			
			//kiem tra xem co yeu cau nang cap ko
			if(is_numeric($_GET['tid'])){
				//kiem tra xem co du dieu kien de nang cap hay ko
				if($listItem[$_GET['tid']]['ok']){
					//du dieu kien -> tien hanh nang cap
					//kiem tra level hien tai
					if($listItem[$_GET['tid']]['level']>0){
						//tang level trong bang items
						TroopItemLevelUp($listItem[$_GET['tid']]['item_id']);
						$object_id=$listItem[$_GET['tid']]['item_id'];
					}else{
						//chen moi vao bang wg_troop_items voi level =1
						$object_id=InsertTroopItem($wg_village->id, $_GET['tid']);
					}
					//chen status
					InsertTroopItemLevelUpStatus($wg_village->id, $object_id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$listItem[$_GET['tid']]['time_item']), $listItem[$_GET['tid']]['time_item']);
					//Tru RS:
					$wg_village->rs1 -= $listItem[$_GET['tid']]['rsi1'];
					$wg_village->rs2 -= $listItem[$_GET['tid']]['rsi2'];
					$wg_village->rs3 -= $listItem[$_GET['tid']]['rsi3'];
					$wg_village->rs4 -= $listItem[$_GET['tid']]['rsi4'];
					
					//Lay lai danh sach vi khi
					$listItem=GetListItem($wg_village, $building, false);
					$parse['develop_status']=GetDevelopmentStatus($wg_village->id);
				}
			}		
		}
		
		//show bang vu khi:
		if($listItem){
			foreach($listItem as $item){
				$parse['rsi1']=$item['rsi1'];
				$parse['rsi2']=$item['rsi2'];
				$parse['rsi3']=$item['rsi3'];
				$parse['rsi4']=$item['rsi4'];
				$parse['duration']=TimeToString($item['time_item']);
				$parse['icon']=$item['icon'];
				$parse['item_level']=$item['level'];
				$parse['troop_name']=$item['troop_name'];
				$parse['action']=$item['action'];
				$rows.=parsetemplate(gettemplate("improve_row"), $parse);
			}
			$parse['rows']=$rows;
			$parse['task_content']=parsetemplate(gettemplate("improve_weapon_body"), $parse);	
		}
	}
	
	$parse['id']=$building->index;
	return parsetemplate(gettemplate("improve_body"), $parse);
}

/**
 * hien thi trang thai nang cap vu khi (type status = 5)
 */
function GetDevelopmentStatus($village_id){
	global $db, $lang;
	includelang("improve");
	$parse=$lang;
	$sql="SELECT * FROM wg_status WHERE village_id=$village_id AND wg_status.type=5 AND wg_status.status=0";
	$db->setQuery($sql);
	$statusList=$db->loadObjectList();
	if($statusList){
		foreach($statusList as $status){
			//Kiem tra xem tao xong chua:
			if(strtotime($status->time_end)>time()){
				//chua xong -> hien thi trang thai
				$sql="SELECT wg_troops.name AS name,wg_troops.icon AS icon,wg_troop_items.level AS level FROM wg_troop_items , wg_troops ";
				$sql.="WHERE wg_troop_items.troop_id =  wg_troops.id AND wg_troop_items.id =$status->object_id";
				$db->setQuery($sql);
				$db->loadObject($troop);
				$parse['troop_name']=$lang[$troop->name];
				$parse['item_level']=$troop->level;
				$parse['icon']=$troop->icon;
				$parse['cost_time']=TimeToString(strtotime($status->time_end)-time());
				$parse['time_at']=date("H:i:s", strtotime($status->time_end));
				$parse['date_at']=date("d-m-Y", strtotime($status->time_end));
				return parsetemplate(gettemplate("improve_status"), $parse);
			}else{
				//da xong -> tinh toan, cap nhat
				include_once("function_status.php");
				SetStatus($status->id);
				SetStatusDevelopment($status->object_id);
				return false;
			}
		}
	}
	return false;
}

/**
 * @author Le Van Tu
 * @des cap nhat tran thai vu khi cua linh khi hoan tat qua trinh research
 * @param object $status
 * @return true or false
 */
function executeTroopUpLevel($status){
	setStatus($status->id);
	return setStatusDevelopment($status->object_id);
}

/**
 * show trang thai nghien cua ao giap:
 */
function GetDevelopmentArmourStatus(){
	global $db, $lang, $wg_village;
	includelang("improve");
	$parse=$lang;
	$sql="SELECT * FROM wg_status WHERE village_id=$wg_village->id AND wg_status.type=14 AND wg_status.status=0";
	$db->setQuery($sql);
	$statusList=$db->loadObjectList();
	if($statusList){
		foreach($statusList as $status){
			//Kiem tra xem tao xong chua:
			if(strtotime($status->time_end)>time()){
				//chua xong -> hien thi trang thai
				$sql="SELECT wg_troops.name AS name,wg_troops.icon AS icon,wg_troop_armour.level AS level ";
				$sql.="FROM wg_troop_armour , wg_troops ";
				$sql.="WHERE wg_troop_armour.troop_id =  wg_troops.id AND wg_troop_armour.id =$status->object_id";
				$db->setQuery($sql);
				$db->loadObject($troop);
				$parse['troop_name']=$lang[$troop->name];
				$parse['item_level']=$troop->level;
				$parse['icon']=$troop->icon;
				$parse['cost_time']=TimeToString(strtotime($status->time_end)-time());
				$parse['time_at']=date("H:i:s", strtotime($status->time_end));
				$parse['date_at']=date("d-m-Y", strtotime($status->time_end));
				return parsetemplate(gettemplate("improve_status"), $parse);
			}else{
				//da xong -> tinh toan, cap nhat
				include_once("function_status.php");
				SetStatus($status->id);
				SetStatusDevelopmentArmour($status->object_id);
				return false;
			}
		}
	}
	return false;
}

/**
 * @author Le Van Tu
 * @des cap nhat tran thai ao giap cua linh khi hoan tat qua trinh research
 * @param object $status
 * @return true or false
 */
function executeUpClothings($status){
	setStatus($status->id);
	return setStatusDevelopmentArmour($status->object_id);
}

/**
 * Lanh danh thong tin Item
 * return: $result[$troopResearch->troop_id]['level']=$arrayItem[$troopResearch->troop_id]['level'];
 */
function GetListItem(&$village, $building, $ar=true){
	global $db, $nationTroopList;
	global $lang;
	includelang("improve");
	$parse=$lang;
	
	$arrayTroop=GetArrayOfTroops();
	
	$id_tho= $nationTroopList[$village->nation_id]['type_name10'];
	$id_thuyet_gia= $nationTroopList[$village->nation_id]['type_name11'];
	
	//Lay danh sach Item cua linh lang nay
	$sql="SELECT * FROM wg_troop_items WHERE village_id=$village->id";
	$db->setQuery($sql);
	$itemList=$db->loadObjectList();
	if($itemList){
		//lu thanh mot mang de thao tac
		foreach($itemList as $item){
			$arrayItem[$item->troop_id]['id']=$item->id;
			if($item->status){
				$arrayItem[$item->troop_id]['level']=$item->level;	
			}else{
				$arrayItem[$item->troop_id]['level']=$item->level-1;	
			}			
		}
	}
	
	$sql="SELECT * FROM wg_troop_researched WHERE village_id=$village->id AND wg_troop_researched.status=1 AND troop_id!=$id_tho AND troop_id!=$id_thuyet_gia ORDER BY troop_id ASC";
	$db->setQuery($sql);
	$troopReseachList=$db->loadObjectList();
	if($troopReseachList){
		foreach($troopReseachList as $troopResearch){
			//neu chua co trong bang wg_troop_item thi level=0
			if($arrayItem[$troopResearch->troop_id]['level']){
				$result[$troopResearch->troop_id]['level']=$arrayItem[$troopResearch->troop_id]['level'];
			}else{
				$result[$troopResearch->troop_id]['level']=0;
			}
			//Lay thong tin cua moi loai linh + item
			$result[$troopResearch->troop_id]['item_id']=$arrayItem[$troopResearch->troop_id]['id'];
			$result[$troopResearch->troop_id]['icon']=$arrayTroop[$troopResearch->troop_id]['icon'];
			$result[$troopResearch->troop_id]['troop_name']=$lang[$arrayTroop[$troopResearch->troop_id]['name']];
			//Tinh toan rs ung voi level:
			$result[$troopResearch->troop_id]['rsi1']=getRsImpIt($arrayTroop[$troopResearch->troop_id]['rsi1'], $result[$troopResearch->troop_id]['level'] + 1);
			$result[$troopResearch->troop_id]['rsi2']=getRsImpIt($arrayTroop[$troopResearch->troop_id]['rsi2'], $result[$troopResearch->troop_id]['level'] + 1);
			$result[$troopResearch->troop_id]['rsi3']=getRsImpIt($arrayTroop[$troopResearch->troop_id]['rsi3'], $result[$troopResearch->troop_id]['level'] + 1);
			$result[$troopResearch->troop_id]['rsi4']=getRsImpIt($arrayTroop[$troopResearch->troop_id]['rsi4'], $result[$troopResearch->troop_id]['level'] + 1);
			
			//Tinh toan thoi gian research:
			$time_item=$arrayTroop[$troopResearch->troop_id]['time_item'];
			//Thoi gian tang theo level cua item	(time=time_item*pow(level, 0.55))
			$time_item=$time_item*pow($result[$troopResearch->troop_id]['level']+1, 0.55);
			//thoi gian giam theo level cua lo ren (3%)
			$decrease_pc=$building->level*3/100;
			$decrease_time=$decrease_pc*$arrayTroop[$troopResearch->troop_id]['time_item'];
			$result[$troopResearch->troop_id]['time_item']=$time_item-$decrease_time;
			
			//Kiem tra level <=20
			if($result[$troopResearch->troop_id]['level']<20){
				//Kiem tra co nghien cuu nao dang thuc hien hay khong.			
				if($ar){
					//Kiem tra level cua item khong duoc vuot qua level cua lo ren:
					if($building->level>$result[$troopResearch->troop_id]['level']){
						//Kiem tra rs.
						if($village->rs1 >= $result[$troopResearch->troop_id]['rsi1'] && $village->rs2 >= $result[$troopResearch->troop_id]['rsi2'] && $village->rs3 >= $result[$troopResearch->troop_id]['rsi3'] && $village->rs4>=$result[$troopResearch->troop_id]['rsi4'] && $ar){
							//du rs
							$result[$troopResearch->troop_id]['action']="<a href=\"build.php?id=$building->index&tid=".$troopResearch->troop_id."\">".$lang['improve']."</a>";
							$result[$troopResearch->troop_id]['ok']=true;
						}else{
							//Thieu RS.
							$result[$troopResearch->troop_id]['action']="<div class=\"c\">".$lang['Too few']."<br>".$lang['resources']."</div>";
						}
					}else{
					//can nang cap lo ren.
					$result[$troopResearch->troop_id]['action']="<div class=\"c\">".$lang['level_builgin_max']."<br>".$lang['level_builgin_max_2']."</div>";
					}
				}else{
					//co mot nghien cuu dang duoc thuc hien
					$result[$troopResearch->troop_id]['action']="<div class=\"c\">".$lang['There is already a']."<br>".$lang['research going on']."</div>";
				}
			}else{
				//da dat toi cap cao nhat
				$result[$troopResearch->troop_id]['action']="<div class=\"c\">".$lang['level_max']."<br>".$lang['level_max_2']."</div>";
			}
		}
		return $result;
	}
}

/**
 * @author Le Van Tu
 * @des Tính toán lượng tài nguyên cần thiết để nâng cấp item ứng với 1 level nào đó.
 */
function getRsImpIt($a, $level){
	$result=$a;
	if($a>1){
		for($i=1; $i<$level; $i++){
			$result=$result*1.14;
		}
	}
	return round($result, 0);
}


/**
 * Lanh danh thong tin giap
 * return: $result[$troopResearch->troop_id]['level']=$arrayItem[$troopResearch->troop_id]['level'];
 */
function GetListArmour(&$village, $building=null, $ar=true){
	global $db, $nationTroopList;
	global $lang;
	includelang("improve");
	$parse=$lang;
	
	$arrayTroop=GetArrayOfTroops();
	
	$id_tho= $nationTroopList[$village->nation_id]['type_name10'];
	$id_thuyet_gia= $nationTroopList[$village->nation_id]['type_name11'];
	
	//Lay danh sach Item cua linh lang nay
	$sql="SELECT * FROM wg_troop_armour WHERE village_id=$village->id";
	$db->setQuery($sql);
	$itemList=$db->loadObjectList();
	if($itemList){
		//lu thanh mot mang de thao tac
		foreach($itemList as $item){
			$arrayItem[$item->troop_id]['id']=$item->id;
			if($item->status){
				$arrayItem[$item->troop_id]['level']=$item->level;	
			}else{
				$arrayItem[$item->troop_id]['level']=$item->level-1;	
			}			
		}
	}
	
	//lay danh sach linh da duoc research
	$sql="SELECT * FROM wg_troop_researched WHERE village_id=$village->id AND wg_troop_researched.status=1 AND troop_id!=$id_tho AND troop_id!=$id_thuyet_gia ORDER BY troop_id ASC";
	$db->setQuery($sql);
	$troopReseachList=$db->loadObjectList();
	if($troopReseachList){
		foreach($troopReseachList as $troopResearch){
			//neu chua co trong bang wg_troop_item thi level=0
			if($arrayItem[$troopResearch->troop_id]['level']){
				$result[$troopResearch->troop_id]['level']=$arrayItem[$troopResearch->troop_id]['level'];
			}else{
				$result[$troopResearch->troop_id]['level']=0;
			}
			
			//Tinh toan rs:
			$result[$troopResearch->troop_id]['rsi1']=getRsImpIt($arrayTroop[$troopResearch->troop_id]['rsi1'], $result[$troopResearch->troop_id]['level']+1);
			$result[$troopResearch->troop_id]['rsi2']=getRsImpIt($arrayTroop[$troopResearch->troop_id]['rsi2'], $result[$troopResearch->troop_id]['level']+1);
			$result[$troopResearch->troop_id]['rsi3']=getRsImpIt($arrayTroop[$troopResearch->troop_id]['rsi3'], $result[$troopResearch->troop_id]['level']+1);
			$result[$troopResearch->troop_id]['rsi4']=getRsImpIt($arrayTroop[$troopResearch->troop_id]['rsi4'], $result[$troopResearch->troop_id]['level']+1);
			//Lay thong tin cua moi loai linh + item
			$result[$troopResearch->troop_id]['item_id']=$arrayItem[$troopResearch->troop_id]['id'];
			$result[$troopResearch->troop_id]['icon']=$arrayTroop[$troopResearch->troop_id]['icon'];
			$result[$troopResearch->troop_id]['troop_name']=$lang[$arrayTroop[$troopResearch->troop_id]['name']];
			
			//Tinh toan thoi gian research:
			$time_item=$arrayTroop[$troopResearch->troop_id]['time_item'];
			//Thoi gian tang theo level cua item	(time=time_item*pow(level, 0.55))
			$time_item=$time_item*pow($result[$troopResearch->troop_id]['level']+1, 0.55);
			//thoi gian giam theo level cua lo ren (3%)
			$decrease_pc=$building->level*3/100;
			$decrease_time=$decrease_pc*$arrayTroop[$troopResearch->troop_id]['time_item'];
			$result[$troopResearch->troop_id]['time_item']=$time_item-$decrease_time;
			
			//Kiem tra level <=20
			if($result[$troopResearch->troop_id]['level']<20){
				//Kiem tra co nghien cuu nao dang thuc hien hay khong.			
				if($ar){
					//Kiem tra level cua item khong duoc vuot qua level cua lo ren:
					if($building->level>$result[$troopResearch->troop_id]['level']){
						//Kiem tra rs.
						if($village->rs1>=$result[$troopResearch->troop_id]['rsi1'] && $village->rs2>=$result[$troopResearch->troop_id]['rsi2'] && $village->rs1>=$result[$troopResearch->troop_id]['rsi3'] && $village->rs3>=$result[$troopResearch->troop_id]['rsi3'] && $village->rs4>=$result[$troopResearch->troop_id]['rsi4'] && $ar){
							//du rs
							$result[$troopResearch->troop_id]['action']="<a href=\"build.php?id=$building->index&t=1&tid=".$troopResearch->troop_id."\">".$lang['improve']."</a>";
							$result[$troopResearch->troop_id]['ok']=true;
						}else{
							//Thieu RS.
							$result[$troopResearch->troop_id]['action']="<div class=\"c\">".$lang['Too few']."<br>".$lang['resources']."</div>";
						}
					}else{
					//can nang cap lo ren.
					$result[$troopResearch->troop_id]['action']="<div class=\"c\">".$lang['level_builgin_max']."<br>".$lang['level_builgin_max_2']."</div>";
					}
				}else{
					//co mot nghien cuu dang duoc thuc hien
					$result[$troopResearch->troop_id]['action']="<div class=\"c\">".$lang['There is already a']."<br>".$lang['research going on']."</div>";
				}
			}else{
				//da dat toi cap cao nhat
				$result[$troopResearch->troop_id]['action']="<div class=\"c\">".$lang['level_max']."<br>".$lang['level_max_2']."</div>";
			}
		}
		return $result;
	}
}

/**
 * Lay level cua mot cong trinh theo id.
 * return: level neu co nha do nguoi lai return false.
 */
function GetBuildingLevel($village_id, $building_type_id){
	global $db;
	$sql="SELECT level FROM wg_buildings WHERE vila_id=$village_id AND type_id=$building_type_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * tang level cho binh khi
 */
function TroopItemLevelUp($id){
	global $db;
	$sql="UPDATE wg_troop_items SET level=level+1, status=0 WHERE id=$id";
	$db->setQuery($sql);
	return $db->query();	
}

/**
 * tang level cho ao giap
 */
function TroopArmourLevelUp($id){
	global $db;
	$sql="UPDATE wg_troop_armour SET level=level+1, status=0 WHERE id=$id";
	$db->setQuery($sql);
	return $db->query();	
}

/**
 * Chen item vao bang items neu chua co
 */
 function InsertTroopItem($village_id, $troop_id){
 	global $db;
 	$troop_id = $db->getEscaped($troop_id);
 	$sql="INSERT INTO wg_troop_items (village_id, troop_id, level, wg_troop_items.status) VALUES ($village_id, $troop_id, 1, 0)";
 	$db->setQuery($sql);
 	if($db->query()){
 		return $db->insertid();
 	} 	
 }

/**
 * Chen item vao bang items neu chua co
 */
function InsertTroopArmour($village_id, $troop_id){
 	global $db;
 	$troop_id = $db->getEscaped($troop_id);
 	$sql="INSERT INTO wg_troop_armour (village_id, troop_id, level, wg_troop_armour.status) VALUES ($village_id, $troop_id, 1, 0)";
 	$db->setQuery($sql);
 	if($db->query()){
 		return $db->insertid();
 	} 	
}

/**
 * them trang thai nang cap binh khi
 */
function InsertTroopItemLevelUpStatus($village_id, $object_id, $time_begin, $time_end, $cost_time){
	global $db;
	$sql="INSERT INTO wg_status (village_id, object_id, type, time_begin, time_end, cost_time, status) VALUES ($village_id, $object_id, 5, '$time_begin', '$time_end', $cost_time, 0)";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * them trang thai nang cap vu khi
 */
function InsertTroopArmourLevelUpStatus($village_id, $object_id, $time_begin, $time_end, $cost_time){
	global $db;
	$sql="INSERT INTO wg_status (village_id, object_id, type, time_begin, time_end, cost_time, status) VALUES ($village_id, $object_id, 14, '$time_begin', '$time_end', $cost_time, 0)";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * @author Le Van Tu
 * @des set status=1 trong bang wg_troop_items
 * @return true or false
 */
function setStatusDevelopment($item_id){
	global $db;
	$sql="UPDATE wg_troop_items SET status=1 WHERE id=$item_id";
	$db->setQuery($sql);
	return $db->query();
} 

/**
 * set status2=1 trong bang wg_troop_items
 */
function setStatusDevelopmentArmour($item_id){
	global $db;
	$sql="UPDATE wg_troop_armour SET status=1 WHERE id=$item_id";
	$db->setQuery($sql);
	return $db->query();
} 

/**
 * Tinh suc tang attack cua linh ung voi vu khi
 */
function GetIncreaseAttack($attack, $item_level){
	$result = $attack;
	if($item_level>0){
		$x=$attack*pow(1.017, 1);
		$result=$x*(pow(1.017, $item_level));
	}
	return $result;
}


/**
 * Tinh suc tang defend cua linh ung voi ao giap
 */
function getIncreaseDefend($defend, $armour_level){
	$result = $defend;
	if($armour_level>0){
		$x=$defend*pow(1.017, 1);
		$result=$x*(pow(1.017, $armour_level));
	}
	return $result;
}
//----------End cac ham lien quan den nang cap vu khi cho quan doi----------->

/**
 * @author Le Van Tu
 * @des Hien thi danh sach linh o ngoai thanh
 */
function showTroopInVillage(){
	global $db, $wg_village, $lang;
	includeLang('troop');
	$parse=$lang;
	$row=gettemplate("own_troop_row");
	$own="";
	$reinforce="";
	$parse['task']='&t=1';
	//Lay thong tin hero cua lang:	
	$ownHero=GetHeroVillage($wg_village->id);
	if($ownHero){
		$parse['images']="images/icon/hero4.ico";
		$parse['sum']=1;
		$parse['name']=$ownHero->name;
		$rowsOwn=parsetemplate($row, $parse);
	}
	
	//Lay thong tin linh cua thanh:
	$sql="SELECT
				SUM(wg_troop_villa.num) AS sum,
				wg_troops.name,
				wg_troops.icon
			FROM
				wg_troop_villa ,
				wg_troops
			WHERE
				wg_troop_villa.troop_id =  wg_troops.id AND
				wg_troop_villa.village_id =  '$wg_village->id'
			GROUP BY
				wg_troops.id";
	$db->setQuery($sql);
	$listTroopOwn=$db->loadObjectList();
	if($listTroopOwn)
	{
		foreach($listTroopOwn as $troop)
		{
			if($troop->sum>0){
				$parse['images']=$troop->icon;
				$parse['sum']=$troop->sum;
				$parse['name']=$lang[$troop->name];
				$rowsOwn.=parsetemplate($row, $parse);
			}
		}
	}
	if($rowsOwn)
	{			
		$parse['rows']=$rowsOwn;
		$parse['table_title']=$lang['troop_own'];
		$own=parsetemplate(gettemplate('own_troop_table'),$parse);
	}
	//Lay thong tin hero vien tro:
	$sql="SELECT
				COUNT(wg_attack_hero.id) 
			FROM
				wg_attack ,
				wg_attack_hero ,
				wg_heros
			WHERE
				wg_attack.id =  wg_attack_hero.attack_id AND
				wg_attack_hero.hero_id =  wg_heros.id AND
				wg_attack.`type` =  '1' AND
				wg_attack.`status` =  '0' AND
				wg_attack.village_defend_id =  '$wg_village->id'
			GROUP BY
				wg_attack.village_defend_id";
	$db->setQuery($sql);
	$sumHeroReinforce=$db->loadResult();
	if($sumHeroReinforce>0){
		$parse['images']="images/icon/hero4.ico";
		$parse['sum']=$sumHeroReinforce;
		$parse['name']=$lang['hero'];
		$rows.=parsetemplate($row, $parse);
	}
	
	//Lay thong tin linh vien tro:
	$parse['task']='';
	$sql="SELECT
				Sum(wg_attack_troop.num) AS sum,
				wg_troops.name,
				wg_troops.icon
			FROM
				wg_attack ,
				wg_attack_troop ,
				wg_troops
			WHERE
				wg_attack.id =  wg_attack_troop.attack_id AND
				wg_attack_troop.troop_id =  wg_troops.id AND
				wg_attack.`type` =  '1' AND
				wg_attack.`status` =  '0' AND  
				wg_attack.village_defend_id =  '$wg_village->id'
			GROUP BY
				wg_troops.id";
	$db->setQuery($sql);
	$listReinforceTroop=$db->loadObjectList();
	if($listReinforceTroop){		
		foreach($listReinforceTroop as $troop){
			if($troop->sum>0){
				$parse['images']=$troop->icon;
				$parse['sum']=$troop->sum;
				$parse['name']=$lang[$troop->name];
				$rows.=parsetemplate($row, $parse);
			}
		}		
	}
	
	if($rows){		
		$parse['rows']=$rows;
		$parse['table_title']=$lang['troop_reinforce'];
		$reinforce=parsetemplate(gettemplate('own_troop_table'),$parse);
	}
	
	return $own.$reinforce;
}


//-----------Cac ham lien quan den Hero--------------->
function ShowHeroMansion($building)
{
	global $db, $user, $lang, $wg_village,$wordFilters;
	includelang("hero");
	$parse=$lang;
	
	//cap nhat mau cho hero (Neu co):
	updateLifeforce();
	
	$hero=GetHeroUser($wg_village->user_id);
	
	$parse['class_train']="class=selected";
	$parse['class_change']="";
	$parse['class_feudatory']="";
	switch($_GET['t']){
		case 1:
			//Khi tab 'thay đổi cung mệnh' được chọn:
			$parse['class_train']="";
			$parse['class_change']="class=selected";
			$parse['class_feudatory']="";
			if($hero)
			{
				if($hero->village_id==$wg_village->id)
				{
					$sql = "SELECT asu FROM wg_config_plus WHERE name='cung_menh'";
					$db->setQuery($sql);
					$asu_cung_menh=0;
					$asu_cung_menh=$db->loadResult();
					//Kiem tra xem co su kien thay doi cung menh hay khong:
					if(is_numeric($_POST['cung']) && $_POST['cung']>0 && $_POST['cung']<=5 && $_POST['cung']!= $hero->cung_menh)
					{
						require_once('function_plus.php');
						$gold_deposit=get_gold_remote($user['username']);					
						$currentGold = showGold($user['id']);										
						if($gold_deposit + $currentGold >= $asu_cung_menh)
						{
							$sql="UPDATE wg_heros SET cung_menh=".$_POST['cung']." WHERE id =$hero->id";
							$db->setQuery($sql);
							$db->query();
							if($db->getAffectedRows()==0)
							{
								globalError2($sql);
							}
							else
							{	
								withdrawGold($asu_cung_menh,$user['id'],$gold_deposit,17);
							}
							//Lay lai thông tin cua hero:
							$hero->cung_menh=$_POST['cung'];
						}
					} 
					$parse['asu_need']=$asu_cung_menh;
					$parse['checked_1']="";
					$parse['checked_2']="";
					$parse['checked_3']="";
					$parse['checked_4']="";
					$parse['checked_5']="";
					$parse['checked_'.$hero->cung_menh]="checked";
					$parse['image']="images/nguhanh/$hero->cung_menh.jpg";
					$parse['id']=$building->index;
					$parse['task_content']= parsetemplate(gettemplate("cung_menh_body"), $parse);
				}else{
					$parse['task_content']= $lang['hero_go_out'];
				}
			}else{
				$parse['task_content']= $lang['no_hero'];
			}
			break;
		case 2:
			//khi tab chu hau duoc chon:
			$parse['class_train']="";
			$parse['class_change']="";
			$parse['class_feudatory']="class=selected";
			
			$parse['task_content']= ShowFeudatory();
			break;
		case 3:
			//doi ten cho hero:
			$parse['class_train']="";
			$parse['class_change']="";
			$parse['class_feudatory']="";
			
			$parse['old_name']=$hero->name;
			$parse['task_content']= parsetemplate(gettemplate("change_hero_name"), $parse);
			break;
		default:
			//Khi tab 'chiêu mộ anh hùng' được chọn:
			if($hero)
			{
				//co hero
				if($hero->village_id==$wg_village->id)
				{
					$hero_new_name=$db->getEscaped(strip_tags($_POST['name']));
					if(isset($_POST['name']) && $hero_new_name != $hero->name && $wordFilters[$hero_new_name] !=1)
					{
						$hero->name=substr($hero_new_name,0,20);
						changeHeroName($hero->id,$hero->name);						
					}
					//Kiem tra xem co yêu cầu cộng điểm cho cung mệnh hay không:
					if(is_numeric($_GET['c']) && $_GET['c']>0 && $_GET['c']<=5){						
						//Kiem tra co diem de cong hay khong:
						if(($hero->level*5)>($hero->kim+$hero->thuy+$hero->moc+$hero->hoa+$hero->tho)){
							//co diem de cong -> cap nhat diem cho hero:
							addHeroPoint($hero->id, $_GET['c']);
							unset($_GET);
							unset($_POST);
							return ShowHeroMansion($building);
						}
					}
					
					//kiem tra xem du diem de tang level hay khong:
					//Tinh so diem can de tang them 1 level:
					$n=0;
					$levelUp=$hero->level+1;
					$pointLevelUp=100*$levelUp*($levelUp+1)/2;
					while($hero->kinh_nghiem>$pointLevelUp && $hero->level<100){
						$n++;
						$hero->level++;
						$levelUp=$hero->level+1;
						$pointLevelUp=100*$levelUp*($levelUp+1)/2;				
					}
					
					if($n>0){
						HeroLevelUp($hero->id, $n, $hero->kinh_nghiem);
					}
					
					$cung[1]['diem']=round($hero->kim, 2);
					$cung[1]['name']="Kim";
					$cung[2]['diem']=round($hero->thuy, 2);
					$cung[2]['name']="Thuỷ";
					$cung[3]['diem']=round($hero->moc, 2);
					$cung[3]['name']="Mộc";
					$cung[4]['diem']=round($hero->hoa, 2);
					$cung[4]['name']="Hỏa";
					$cung[5]['diem']=round($hero->tho, 2);
					$cung[5]['name']="Thổ";
					$tongDiem=$hero->kim+$hero->thuy+$hero->moc+$hero->hoa+$hero->tho;
					$parse['diem_cong']=($hero->level*5)-$tongDiem;
					$cungMenh=$hero->cung_menh;
					$i=1;
					$temp=$cungMenh+1;			
					$width=260;			
					while($temp<=5){
						$parse['diem_'.$i]=$cung[$temp]['diem'];
						$parse['cung_'.$i]=$cung[$temp]['name'];
						$parse['phan_tram_'.$i]=round(($cung[$temp]['diem']*0.2), 2)."%";
						$parse['width_'.$i]=$cung[$temp]['diem']*$width/100;
						$parse['color_'.$i]=GetColor($cung[$temp]['diem']);
						if($parse['diem_cong']>0 && $parse['diem_'.$i]<100){
							$parse['dau_cong_'.$i]="<a href=\"build.php?id=$building->index&c=$temp\">(<b>+</b>)</a>";
						}else{
							$parse['dau_cong_'.$i]="<span class=\"c\">(<b>+</b>)</span>";
						}
						$i++;
						$temp++;
					}
					$temp=1;
					while($temp<=$cungMenh){
						$parse['diem_'.$i]=$cung[$temp]['diem'];
						$parse['cung_'.$i]=$cung[$temp]['name'];
						$parse['phan_tram_'.$i]=round(($cung[$temp]['diem']*0.2), 2)."%";
						$parse['width_'.$i]=$cung[$temp]['diem']*$width/100;
						$parse['color_'.$i]=GetColor($cung[$temp]['diem']);
						if($parse['diem_cong']>0 && $parse['diem_'.$i]<100){
							$parse['dau_cong_'.$i]="<a href=\"build.php?id=$building->index&c=$temp\">(<b>+</b>)</a>";
						}else{
							$parse['dau_cong_'.$i]="<span class=\"c\">(<b>+</b>)</span>";
						}
						$i++;
						$temp++;
					}
					
					if($hero->level <100){
						$parse['width_6']=($hero->kinh_nghiem/$pointLevelUp)*$width;			
						$parse['color_6']=GetColor(100*$hero->kinh_nghiem/$pointLevelUp);
						$parse['phan_tram_6']=round((100*$hero->kinh_nghiem/$pointLevelUp), 1)."%";
					}else{
						$parse['width_6']=0;			
						$parse['color_6']=GetColor(100*$hero->kinh_nghiem/$pointLevelUp);
						$parse['phan_tram_6']="";
					}
						
					$parse['kinh_nghiem']=$hero->kinh_nghiem;
					$parse['width_7']=$hero->hitpoint*$width/100;			
					$parse['color_7']=GetColor($hero->hitpoint);
					$parse['phan_tram_7']=round($hero->hitpoint, 2)."%";
					$parse['hero_name']=$hero->name;
					$parse['troop_name']=$lang[getTroopName($hero->troop_id)];
					$parse['hero_level']=$hero->level;
					$parse['task_content']= parsetemplate(gettemplate("hero_info_body"), $parse);	
					//-------------------------end co hero---------------------------->				
				}else{
					$parse['task_content']= $lang['hero_go_out'];
				}
			}else{
				$parse['task_content']=GetHeroStatus($building);			
				if(!$parse['task_content']){
					//chua co hero da hoac dang duoc tao.
					$listTroopTrain=GetListTroopTrainHero($building);
					$listHeroDie=GetListHeroDie($building);
					//kiem tra co yeu cau tao hero khong:
					if(is_numeric($_GET['tid'])){
						//kiem tra xem co du dieu kien tao ko
						if($listTroopTrain[$_GET['tid']]['ok']){
							//Tru rs cua lang:
							$wg_village->rs1 -= $listTroopTrain[$_GET['tid']]['rs1'];
							$wg_village->rs2 -= $listTroopTrain[$_GET['tid']]['rs2'];
							$wg_village->rs3 -= $listTroopTrain[$_GET['tid']]['rs3'];
							$wg_village->rs4 -= $listTroopTrain[$_GET['tid']]['rs4'];
							
							//Them hero
							$object_id=InsertHero($wg_village->user_id, $wg_village->id, $_GET['tid'], $lang['hero'], rand(1,5));					
							
							//Them trang thai tao hero.
							InsertStatus($wg_village->id, $object_id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$listTroopTrain[$_GET['tid']]['time_train']), $listTroopTrain[$_GET['tid']]['time_train'], 19);
							unset($_GET);
							unset ($_POST);
							return ShowHeroMansion($building);
						}
					}
					
					//kiem tra co yeu cau hoi sinh hero khong:
					if(is_numeric($_GET['hid'])){
						foreach($listHeroDie as $heroDie){
							if($_GET['hid']==$heroDie->id){
								$hero=$heroDie;
								break;
							}
						}
						//kiem tra xem co du dieu kien tao ko
						if($hero->ok){	
							//Tru rs cua lang:
							$wg_village->rs1 -= $hero->rs1;
							$wg_village->rs2 -= $hero->rs2;
							$wg_village->rs3 -= $hero->rs3;
							$wg_village->rs4 -= $hero->rs4;
							
							//hoi sinh hero
							SetHeroStatus($_GET['hid'], 0);
							
							//Them trang thai tao hero.
							InsertStatus($wg_village->id, $_GET['hid'], date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$hero->time_train), $hero->time_train, 21);
							return ShowHeroMansion($building);
						}
					}
					
					//Hien thi danh sach hero da hi sinh va linh tao hero:
					$row=gettemplate("hero_train_row");
						
					if($listHeroDie){
						foreach($listHeroDie as $heroDie){
							$parse['icon']=$heroDie->icon;
							$parse['troop_name']=$heroDie->name;
							$parse['level']="(".$lang['level']." ".$heroDie->level.")";
							$parse['rs1']=$heroDie->rs1;
							$parse['rs2']=$heroDie->rs2;
							$parse['rs3']=$heroDie->rs3;
							$parse['rs4']=$heroDie->rs4;
							$parse['keep_hour']=$heroDie->keep_hour;
							$parse['duration']=TimeToString($heroDie->time_train);
							$parse['action']=$heroDie->action;
							$rows1.=parsetemplate($row, $parse);
						}
						$parse['rows']=$rows1;
						$parse['List of heroes']=$lang['list_hero_die'];
						$parse['task_content'].=parsetemplate(gettemplate("hero_table"), $parse)."<br>";
					}		
						
					foreach($listTroopTrain as $troop){
						$parse['icon']=$troop['icon'];
						$parse['troop_name']=$troop['name'];
						$parse['level']="";
						$parse['rs1']=$troop['rs1'];
						$parse['rs2']=$troop['rs2'];
						$parse['rs3']=$troop['rs3'];
						$parse['rs4']=$troop['rs4'];
						$parse['keep_hour']=$troop['keep_hour'];
						$parse['duration']=TimeToString($troop['time_train']);
						$parse['action']=$troop['action'];
						$rows.=parsetemplate($row, $parse);
					}
					$parse['rows']=$rows;
					$parse['List of heroes']=$lang['List of heroes'];
					$parse['task_content'].=parsetemplate(gettemplate("hero_table"), $parse);
				}
			}
			break;
	}

	$parse['id']=$building->index;
	return parsetemplate(gettemplate("hero_body"), $parse);
}

/**
 * @author Le Van Tu
 * @des hien thi thong tin chu hau
 */
function ShowFeudatory(){
	global $db, $wg_village, $lang;
	includeLang("hero");
	$parse=$lang;
	$sql="SELECT * FROM wg_villages WHERE kind_id >=7 AND child_id=$wg_village->id";
	$db->setQuery($sql);
	$listFeudatory=$db->loadObjectList();
	if(count($listFeudatory)>0){
		if(is_numeric($_REQUEST['f'])){
			//kiem tra bo bo lac
			$f = intval($_REQUEST['f']);
		}
		$i=1;
		$row=gettemplate("feudatory_row");
		foreach($listFeudatory as $feudatory){
			if($feudatory->id == $f){
				//bo bo lac:
				destroyFeudatory($feudatory);
				$time_check=strtotime(date("Y-m-d H:i:s",time()));
				$array=returnKrsForVillage($wg_village->user_id,$wg_village->id,$time_check);
				$wg_village->krs1=$array['krs1'];
				$wg_village->krs2=$array['krs2'];
				$wg_village->krs3=$array['krs3'];
				$wg_village->krs4=$array['krs4'];
			}else{
				$parse['i']=$i;
				$parse['x']=$feudatory->x;
				$parse['y']=$feudatory->y;
				$parse['f_id']=$feudatory->id;
				$parse['cong_pham_row']=getCongPham($feudatory->kind_id);
				$parse['faith_row']=$feudatory->faith;
				$rows.=parsetemplate($row, $parse);
				$i++;
			}				
		}
		$parse['rows']=$rows;
	}else{
		$parse['rows']="";
	}
	return parsetemplate(gettemplate("feudatory_table"), $parse);
}

/**
 * @author Le Van Tu
 * @todo bo bo lac cho mot lang
 */
function destroyFeudatory($f){
	global $db, $wg_village;	
	//duoi linh ve:
	$sql = "SELECT
					wg_attack.id,
					wg_attack.village_attack_id
				FROM
					wg_attack
				WHERE
					wg_attack.`status` =  '0' AND
					wg_attack.`type` =  '1' AND
					wg_attack.village_defend_id =  '$f->id'";
	$db->setQuery($sql);
	$ats = $db->loadObjectList();
	if($ats){
		foreach($ats as $at){
			//doi wg_attack.`type`=5
			$sql = "UPDATE wg_attack SET wg_attack.`type`=5 WHERE id=$at->id";
			$db->setQuery($sql);
			if(!$db->query()){
				globalError2($sql);
			}
			
			//chen trang thai rut linh:
			$villageAttack=getVillage($at->village_attack_id);
			$speed=GetSpeedAttackTroopBack($at->id);
			$s=S($f->x, $f->y, $villageAttack->x, $villageAttack->y);
			if($speed>0){
				$duration=($s/$speed)*3600;
			}else{
				$duration=0;
			}
			InsertStatus($at->village_attack_id, $at->id, date("Y-m-d H:i:s", $time), date("Y-m-d H:i:s", $time+$duration), $duration, 10);
		}
	}

	//xoa bo lac trong bang wg_villages:
	$sql = "DELETE FROM wg_villages WHERE id=$f->id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
	
	//update user trong bang wg_village_map
	$sql = "UPDATE wg_villages_map SET user_id=0 WHERE id=$f->id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}


/**
 * @author Le Van Tu
 * @todo lay thong tin ve cong pham khi chiem duoc bo lac
 */
function getCongPham($kind_id){
	global $lang;
	includeLang("attack");
	switch($kind_id){
		case 7:
			return " 25% ".$lang['suc_san_xuat']." ".$lang['go'].".";
			break;
		case 8:
			return " 25% ".$lang['suc_san_xuat']." ".$lang['go']." | 25% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
			break;
		case 9:
			return " 25% ".$lang['suc_san_xuat']." ".$lang['sat'].".";
			break;
		case 10:
			return " 25% ".$lang['suc_san_xuat']." ".$lang['sat']." | 25% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
			break;
		case 11:
			return " 25% ".$lang['suc_san_xuat']." ".$lang['da'].".";
			break;
		case 12:
			return " 25% ".$lang['suc_san_xuat']." ".$lang['da']." | 25% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
			break;
		case 13:
			return " 25% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
			break;
		case 14:
			return "50% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
			break;
	}
}

/**
 * Lay danh sach linh de tao hero:
 */
function GetListTroopTrainHero($building){
	global $db, $lang, $wg_village, $game_config;
	includelang("hero");
	$listTroopRS=getTroopsResearched(1);
	$listTroop=getTroopsOfNation($wg_village->nation_id);
	for($i=0; $i<7; $i++){
		//Kiem tra linh nay da duoc research hay chua:
		if($listTroopRS[$listTroop[$i]->id]==1){
			//da duoc rs -> dua vao mang.
			$result[$listTroop[$i]->id]['icon']=$listTroop[$i]->icon;
			$result[$listTroop[$i]->id]['name']=$lang[$listTroop[$i]->name];
			
			$result[$listTroop[$i]->id]['rs1']=$listTroop[$i]->rs1 * 3;
			$result[$listTroop[$i]->id]['rs2']=$listTroop[$i]->rs2 * 3;
			$result[$listTroop[$i]->id]['rs3']=$listTroop[$i]->rs3 * 3;
			$result[$listTroop[$i]->id]['rs4']=$listTroop[$i]->rs4 * 3;
			
			$result[$listTroop[$i]->id]['keep_hour']=$listTroop[$i]->keep_hour * 5;
			
			$result[$listTroop[$i]->id]['time_train']=$listTroop[$i]->time_train * 3/$game_config['k_train'];
			//Kiem tra du rs ko
			if($wg_village->rs1 >= $result[$listTroop[$i]->id]['rs1'] && $wg_village->rs2 >= $result[$listTroop[$i]->id]['rs2'] && $wg_village->rs3 >= $result[$listTroop[$i]->id]['rs3'] && $wg_village->rs4 >= $result[$listTroop[$i]->id]['rs4']){
				$result[$listTroop[$i]->id]['action']="<a href=\"build.php?id=$building->index&tid=".$listTroop[$i]->id."\">".$lang['train']."</a>";
				$result[$listTroop[$i]->id]['ok']=true;
			}else{
				//khong du rs:
				$result[$listTroop[$i]->id]['action']="<div class=\"c\">".$lang['too few rs']."</div>";
				$result[$listTroop[$i]->id]['ok']=false;
			}			
		}
	}
	return $result;
}

/**
 * Lay danh sach hero da chet:
 */
function GetListHeroDie($building){
	global $db, $lang, $wg_village, $game_config;
	includelang("hero");
	$sql="SELECT wg_heros.id AS id,
				wg_heros.troop_id AS 'troop_id', 
				wg_heros.name AS name,
				wg_heros.level AS level,
				wg_heros.hitpoint AS 'hitpoint',
				wg_heros.cung_menh,
				wg_heros.kim,
				wg_heros.thuy,
				wg_heros.moc,
				wg_heros.hoa,
				wg_heros.tho,
				wg_heros.keep_hour AS keep_hour,
				wg_heros.kinh_nghiem,
				wg_heros.`status`,
				wg_troops.name AS troop_name,
				wg_troops.rs1,
				wg_troops.rs2, 
				wg_troops.rs3, 
				wg_troops.rs4,
				wg_troops.time_train AS time_train,  
				wg_troops.icon 
			FROM 
				wg_heros ,
				wg_troops 
			WHERE 
				wg_heros.user_id = $wg_village->user_id
				AND	wg_heros.troop_id =  wg_troops.id
				AND	wg_heros.status = -1";
	$db->setQuery($sql);
	$heroList=$db->loadObjectList();
	if($heroList){
		foreach($heroList as $id=>&$hero){
			$hero->troop_name=$lang[$hero->troop_name];
			
			$hero->rs1=$hero->rs1 * 3 * $hero->level;
			$hero->rs2=$hero->rs2 * 3 * $hero->level;
			$hero->rs3=$hero->rs3 * 3 * $hero->level;
			$hero->rs4=$hero->rs4 * 3 * $hero->level;
			
			$hero->time_train=($hero->time_train * 3 * $hero->level)/$game_config['k_train'];
						
			//Kiem tra du rs ko
			if($wg_village->rs1>=$hero->rs1 && $wg_village->rs2>=$hero->rs2 && $wg_village->rs3>=$hero->rs3 &&$wg_village->rs4>=$hero->rs4){
				$hero->action="<a href=\"build.php?id=$building->index&hid=".$hero->id."\">".$lang['hoi_sinh']."</a>";
				$hero->ok=true;
			}else{
				//khong du rs:
				$hero->action="<div class=\"c\">".$lang['too few rs']."</div>";
				$hero->ok=false;
			}			
		}
	}		
	return $heroList;
}
 

/**
 * Them record vang bang hero
 */
function InsertHero($user_id, $village_id, $troop_id, $name, $cung_menh){
	global $db;
	$troop_id = $db->getEscaped($troop_id);
	//Tinh so luong thuc ma hero tieu thu:
	$troop=GetTroop($troop_id);
	$keep_hour=$troop->keep_hour * 5;
	$sql="INSERT INTO wg_heros ";
	$sql.="(user_id, village_id, troop_id, name, type, level, attack, melee_defense, ranger_defense, magic_defense, speed, keep_hour, cung_menh, hitpoint, kim, thuy, moc, hoa, tho, kinh_nghiem, status) ";
	$sql.="VALUES ($user_id, $village_id, $troop_id, '$name', $troop->type, 1, $troop->attack, $troop->melee_defense, $troop->ranger_defense, $troop->magic_defense, $troop->speed, $keep_hour, $cung_menh, 100, 0, 0, 0, 0, 0, 0, 0) ";
	$db->setQuery($sql);
	if($db->query()){
		return $db->insertid();
	}else{
		globalError("Error!!!InsertHero ");
	}
}

/**
 * lay trang thai tao hero:
 */
function GetHeroStatus($building){
	global $db, $lang, $wg_village;
	includelang("hero");
	$parse=$lang;
	//Kiem tra xem user cua lang co hero da hoac dang duoc tao hay khong:
	$sql="SELECT wg_heros.id AS id,
				wg_heros.troop_id, 
				wg_heros.name AS name,
				wg_heros.level,
				wg_heros.hitpoint AS 'hitpoint',
				wg_heros.cung_menh,
				wg_heros.kim,
				wg_heros.thuy,
				wg_heros.moc,
				wg_heros.hoa,
				wg_heros.tho,
				wg_heros.kinh_nghiem,
				wg_heros.keep_hour,
				wg_heros.`status`,
				wg_troops.name AS troop_name,
				wg_troops.icon 
			FROM 
				wg_villages ,
				wg_heros ,
				wg_troops 
			WHERE 
				wg_heros.status=0 AND 
				wg_heros.user_id = $wg_village->user_id 
				AND	wg_heros.troop_id =  wg_troops.id 
				AND	(wg_heros.status = 0 OR wg_heros.status = 1)
			GROUP BY wg_heros.user_id";
	$db->setQuery($sql);
	$db->loadObject($hero);
	if($hero){
		$sql="SELECT * FROM wg_status WHERE (type=19 OR type=21) AND status=0 AND object_id=$hero->id";
		$db->setQuery($sql);
		$db->loadObject($status);
		if($status){				
			$parse['icon']=$hero->icon;
			$parse['level']="";
			$parse['name']=$lang[$hero->troop_name];
			$parse['time_left']=TimeToString(strtotime($status->time_end)-time());
			$parse['time_finished']=date("H:i:s",strtotime($status->time_end));
			$parse['date_finished']=date("d-m-Y",strtotime($status->time_end));
			if($status->type==21){
				//Hoi sinh:
				$parse['name']=$hero->name;
				$parse['level']="(".$lang['level']." ".$hero->level.")";
				$parse['title_column_intraining']=$lang['dang_hoi_sinh'];
			}
			return parsetemplate(gettemplate("train_hero_table"), $parse);
		}
	}
	return "";
}

function getTroopName($id){
	global $db;
	$sql="SELECT name FROM wg_troops WHERE id=$id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @des thay doi ten cua hero
 */
function changeHeroName($id, $name){
	global $db;
	$name = $db->getEscaped($name);
	$sql="UPDATE wg_heros SET name='$name' WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError("Loi khong cap nhat duoc ten cua hero  changeHeroName($id, $name)");
	}
}

/**
 * @author Le Van Tu
 * @des Cap nhat khi hero da duoc tao xong
 * @param status object
 */
function executeCreateHero($status){
	global $db;
	$sql="SELECT keep_hour FROM wg_heros WHERE id=$status->object_id";
	$db->setQuery($sql);
	$keep_hour=$db->loadResult();
	SetHeroStatus($status->object_id);
	ChangeTroopKeepVillage($status->village_id, $keep_hour);
}

/**
 * @author Le Van Tu
 * @des Cap nhat khi hero da duoc hoi sinh xong
 * @param status object
 */
function executeHeroComeback($status){
	global $db;
	$sql="SELECT keep_hour FROM wg_heros WHERE id=$status->object_id";
	$db->setQuery($sql);
	$keep_hour=$db->loadResult();
	HoiSinhTuong($status->object_id, $status->village_id);
	ChangeTroopKeepVillage($status->village_id, $keep_hour);
}

/**
 * @author Le Van Tu
 * @des change village of hero
 * @param1 id hero
 * @param2 id new village
 */
function changeVillageOfHero($hero_id, $village_id){
	global $db;
	$sql="UPDATE wg_heros SET village_id=$village_id WHERE id=$hero_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Thay doi lang cho hero khong thanh cong: $hero_id - $village_id");
	}
}

/**
 * lay thong tin lien quan khi attack cua hero
 */
function GetHeroInfoAttack($hero){
	global $db, $game_config;
	$cung[1]=$hero->kim;
	$cung[2]=$hero->thuy;
	$cung[3]=$hero->moc;
	$cung[4]=$hero->hoa;
	$cung[5]=$hero->tho;
	$cungMenh=$hero->cung_menh;
	$i=1;
	$temp=$cungMenh+1;			
	while($temp<=5){
		$diem[$i]=$cung[$temp];
		$i++;
		$temp++;
	}
	$temp=1;
	while($temp<=$cungMenh){
		$diem[$i]=$cung[$temp];
		$i++;
		$temp++;
	}
	
	$hero->tuong_sinh_cong	=$diem[1]*0.002;
	$hero->tuong_khac_cong	=$diem[2]*0.002;
	$hero->tuong_sinh_thu	=$diem[3]*0.002;
	$hero->tuong_khac_thu	=$diem[4]*0.002;
	
	$hero->attack	=	$hero->attack * $hero->level * (1+$hero->tuong_khac_cong);
	$hero->melee_defense	=	$hero->melee_defense * $hero->level * (1+$hero->tuong_khac_thu);
	$hero->ranger_defense	=	$hero->ranger_defense * $hero->level * (1+$hero->tuong_khac_thu);
	$hero->magic_defense	=	$hero->magic_defense * $hero->level * (1+$hero->tuong_khac_thu);
	
	$hero->speed *= $game_config['k_speed'];
	return $hero;
}

/**
 * set status=1 cho bang wg_heros
 */
function SetHeroStatus($id, $status=1){
	global $db;
	$id = $db->getEscaped($id);
	$sql="UPDATE wg_heros SET status=$status WHERE id=$id";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * Cap nhat cac thong so cho tuong khi hoi sinh:
 */
function HoiSinhTuong($id, $village_id){
	global $db;
	$sql="UPDATE wg_heros SET hitpoint=100, status=1, village_id=$village_id WHERE id=$id";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * Lay mau cho thanh trang thai.
 */
function GetColor($n){
	$green=round((100-$n)*2.55);
	$blue=0;
	$red=round($n*2.55);
	return "rgb($red, $green, $blue)";
}

/**
 * @des Cong diem cho hero
 */
function addHeroPoint($id, $c){
	global $db;
	$sql="UPDATE wg_heros SET ";
	switch($c){
		case 1://Kim
			$sql.="kim=kim+1 WHERE kim<100 ";
			break;
		case 2://Thuy
			$sql.="thuy=thuy+1 WHERE thuy<100 ";
			break;
		case 3://Moc
			$sql.="moc=moc+1 WHERE moc<100 ";
			break;
		case 4://Hoa
			$sql.="hoa=hoa+1 WHERE hoa<100 ";
			break;
		case 5://Tho
			$sql.="tho=tho+1 WHERE tho<100 ";
			break;
	}
	$sql.="AND id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi tang diem cho hero addHeroPoint($id, $c)");
	}
}

/**
 * Kiem tra mot lang co Tuong hay ko:
 * lay thong tin cua hero
 */
function GetHeroVillage($village_id){
	global $db, $game_config;
	$sql="SELECT * FROM wg_heros WHERE village_id=$village_id AND status=1";
	$db->setQuery($sql);
	$db->loadObject($heroVillage);
	if($heroVillage){
		$heroVillage->speed *= $game_config['k_speed'];
	}	
	return $heroVillage;
}

/**
 * @author Le Van Tu
 * @des lay thong tin cua hero.
 * @param id user
 */
function GetHeroUser($user_id){
	global $db, $game_config;
	$sql="SELECT * FROM wg_heros WHERE user_id=$user_id AND wg_heros.`status`=1";
	$db->setQuery($sql);
	$hero=null;
	$db->loadObject($hero);
	if($hero){
		$hero->speed *= $game_config['k_speed'];
	}	
	return $hero;
}

/**
 * lay thong tin cua cac hero trong mot lang (gom cua lang va cua lang khac ho tro):
 */
function GetListHeroVillage($village_id){
	global $db, $user;
	$user_id=$user['id'];
	$sql="SELECT * FROM wg_heros WHERE in=1 AND status=1 AND village_id=$village_id";
	$db->setQuery($sql);
	$heroVillageList=$db->loadObjectList();
	if($heroVillageList){
		$i=0;
		foreach($heroVillageList as $hero){
			$heroList[$i]=GetHeroInfoAttack($hero);
			$i++;
		}
		return $heroList;
	}
	return false;
}

/**
 * lay thong tin cua hero
 */
function GetHeroInfo($hero_id){
	global $db;
	$sql="SELECT * FROM wg_heros WHERE status=1 AND id=$hero_id";
	$db->setQuery($sql);
	$db->loadObject($heroVillage);
	return $heroVillage;
}


/**
 * Kiem tra mot lang co Tuong hay ko:
 */
function CheckHeroVillage($village_id){
	global $db, $user;
	$user_id=$user['id'];
	$sql="SELECT COUNT(*) FROM wg_heros WHERE village_id=$village_id AND user_id=$user_id AND status=1";
	$db->setQuery($sql);
	return $db->loadResult();
	
}

/**
 * Lay so luong thuc ma hero tieu thu
 */
function GetTroop($troop_id){
	global $db;
	$sql="SELECT * FROM wg_troops WHERE id=$troop_id";
	$db->setQuery($sql);
	$db->loadObject($troop);
	return $troop;
}

/**
 * tang hoac giam cac thong so cho tuong
 */
function UpdateHero($hero_id, $kinh_nghiem, $hp){
	global $db;
	$sql="UPDATE wg_heros 
			SET	kinh_nghiem=$kinh_nghiem, hitpoint=$hp  
			WHERE id=$hero_id";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * Tuong bi chet (chuyen status=-1)
 */
function HeroDie($hero_id){
	global $db;
	$sql="UPDATE wg_heros 
			SET	wg_heros.status=-1   
			WHERE id=$hero_id";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * Kiem tra va tang level cho hero
 */
function HeroLevelUp($hero_id, $n){
	global $db;
	$sql="UPDATE wg_heros SET level=level+$n WHERE id=$hero_id";
	$db->setQuery($sql);
	return $db->query();
}
//---------End cac ham lien quan den Hero--------------->


/** =====================================================

BEGIN HXMANH ADD

=======================================================*/

/**
* @Author: ManhHX
* @Des: Update for Faith of a village per day
* @param: null
* @return: $cpRequire culture point
*/  
function checkPointToAddVillage($user_id){
	global $db, $game_config;
	$sumVillageOfUser=getSumVillageOfUser($user_id);
	
	$cpRequire=750 * pow($sumVillageOfUser + 1, 2.47) - 2155;
	
	$speed = $game_config['k_game'];
	$cpRequire=$cpRequire/$speed;	
	$cpRequire=round($cpRequire);	
	return $cpRequire;
	
}


/**
* @Author: ManhHX
* @Des: Sum villages of User
* @param: null
* @return: village number of user
*/ 
function getSumVillageOfUser($user_id){
	global $db;
	$sql="SELECT COUNT(*) FROM wg_villages WHERE user_id=$user_id AND (kind_id<7 OR kind_id>14) ";
	$db->setQuery($sql);	
	return $db->loadResult();
}


/**
* @Author: ManhHX
* @Des: Get points of villages
* @param: null
* @return: point
*/ 	  
function pointsTotal(){
	global $db, $user;
	$uId =  $user["id"];
	
	$sql="SELECT id FROM wg_villages WHERE user_id=$uId";
	$db->setQuery($sql);	
	$objRes = $db->loadObjectList();
	
	$pointTotal=0;	
	if(count($objRes)){
		foreach($objRes as $k =>$v){
			$pointTotal = $pointTotal + getCulturePointOfVillagePerDay1($v->id);
		}
	}
	return $pointTotal;
	
	/*$sql="SELECT SUM(cp) AS total FROM wg_villages WHERE user_id=$uId";
	$db->setQuery($sql);	
	$objRes = $db->loadObjectList();	
	return $objRes[0]->total;	*/
}


/**
* @Author: ManhHX
* @Des: Culture point of village per day
* @param: $vId village id
* @return: Culture point
*/ 	 
function getCulturePointOfVillagePerDay1($vId){
	global $db;
	$sql="SELECT SUM(cp) AS total FROM wg_buildings WHERE vila_id=$vId";
	$db->setQuery($sql);	
	$objRes = $db->loadObjectList();	
	
	$point = 0;
	if(count($objRes)){
		$point = $objRes[0]->total;
	}	
	return $point;	
}


/**
* @Author: ManhHX
* @Des: Culture point of all villages
* @param: null
* @return: $pointTotal production
*/ 	   
function allvillageCulturepoint(){
	global $db, $user, $wg_village;
	$uId =  $user["id"];
	$sql="SELECT id, cp, cpupdate_time FROM wg_villages WHERE user_id=$uId";
	$db->setQuery($sql);
	$arrRes = $db->loadObjectList();
	$pointTotal = 0;
	
	$curr_date = date("Y-m-d H:i:s");
	$curr_time = strtotime($curr_date);	
	
	if($arrRes){
		//sum culture point and update	
		foreach($arrRes as $k =>$v){
			//$pointTotal = $pointTotal + LevelUpvForCulturePoint($v->id);
			$singlePoint = getCulturePointOfVillagePerDay1($v->id);		
			
			$old_time = strtotime($v->cpupdate_time);				
			$time_distance = $curr_time - $old_time;
			
			$deltaCP = 0;
			$deltaCP = floor(($time_distance*$singlePoint)/86400);
			
			$cpNew = 0;
			$cpNew = $v->cp + $deltaCP;
			$pointTotal = $pointTotal + $cpNew;
			
			if($deltaCP >0){
				if($v->id == $wg_village->id){
					$wg_village->cp=$cpNew;
					$wg_village->cpupdate_time = $curr_date;
				}	
				$updSql="UPDATE wg_villages SET cpupdate_time='$curr_date', cp= $cpNew WHERE id=$v->id";		
				$db->setQuery($updSql);		
				if(!$db->query()){
					globalError2($lang['error_db_access']);
				}
			}	
		}
	}
	return $pointTotal;
}

	  
/**
* @Author: ManhHX
* @Des: Update for Faith of a village per day
* @param: $village village
* @return: $newFaith faith a village
*/ 	 
function udpFaithAVillagePerDay($vId){	
	global $db;
	$query="SELECT level FROM wg_buildings WHERE vila_id=$vId AND type_id=18";
	$db->setQuery($query);
	$objRes = $db->loadObjectList();
	
	if($objRes[0]->level){
		$palaceLevel = $objRes[0]->level;
	}
	else{
		$palaceLevel = 0;
	}
	
	$newFaith = udpData($vId, $palaceLevel);	
	
	return $newFaith;
}


/**
* @Author: ManhHX
* @Des: Compute and execute  faith a village
* @param: $vId village id; $palaceLevel palace level
* @return: $newFaith faith a village
*/ 	 
function udpData($vId, $palaceLevel){
	global $db, $game_config, $wg_village;
	
	$query1="SELECT faith, faith_time  FROM wg_villages WHERE id=$vId";
	$db->setQuery($query1);
	$objVillageRes = $db->loadObjectList();
	
	if($objVillageRes[0]->faith < 100){//When the faith small than 100
		
		$speed = $game_config['k_game'];
		
		$curr_date = date("Y-m-d H:i:s");
		$curr_time = strtotime($curr_date);
		
		$old_time = strtotime($objVillageRes[0]->faith_time);				
		$time_distance = $curr_time - $old_time;
		
		$deltaFaith = $time_distance * (($palaceLevel+1) * ($speed/100))/ 86400;
		$deltaFaith = round($deltaFaith);		
		$newFaith = $deltaFaith + $objVillageRes[0]->faith;
		
		if($newFaith > 100){
			$newFaith = 100;
		}
		
		if($deltaFaith >= 1){
			unset($wg_village->faith_time);
			unset($wg_village->faith);
			$updSql="UPDATE wg_villages SET faith_time='$curr_date', faith= $newFaith WHERE id=$vId";		
			$db->setQuery($updSql);
			if(!$db->query()){
				globalError2($lang['error_db_access']);
			}
		}	
	}else{ 
		$newFaith = 100;
	}//Endif When the faith small than 100
	return $newFaith;
}


/**
* @Author: ManhHX
* @Des: Update life force
* @param: null
* @return: null
*/ 	  
function updateLifeforce(){
	global $db, $user;
	$uId =  $user["id"];
	$sql ="SELECT id, hitpoint, cung_menh, kim, thuy, moc, hoa, tho, hitpoint_date ";
	$sql.="FROM wg_heros WHERE user_id=$uId AND status=1";	
	$db->setQuery($sql);
	$objRes = $db->loadObjectList();
	
	if(!$objRes[0]){
		return;
	}
	
	//init Destiny
	$arrDestiny = array();
	$arrDestiny[1] = "kim";
	$arrDestiny[2] = "thuy";
	$arrDestiny[3] = "moc";
	$arrDestiny[4] = "hoa";
	$arrDestiny[5] = "tho";
	
	$currDestiny = $arrDestiny[$objRes[0]->cung_menh];	
	
	//compute time distance
	$old_date = $objRes[0]->hitpoint_date;	
	$old_time = strtotime($old_date);
	$curr_date = date("Y-m-d H:i:s");
	$curr_time = strtotime($curr_date);
	
	$delta_time = $curr_time - $old_time;
	$num_date = $delta_time/86400;
	
	if($num_date){//time distance over a day
		$deltaLifeforce = ($objRes[0]->$currDestiny/5);			
		$deltaLifeforce = round($deltaLifeforce * $num_date);			
		if($deltaLifeforce >=1){	
			$newLifeforce = $objRes[0]->hitpoint + $deltaLifeforce;
			if($newLifeforce > 100){
				$newLifeforce =100;
			}		
			$updSql="UPDATE wg_heros SET hitpoint_date='$curr_date', hitpoint= $newLifeforce WHERE user_id=$uId AND status=1";		
			
			$db->setQuery($updSql);		
			if(!$db->query()){
				globalError2($lang['error_db_access']);
			}
		}
	}
}

 
/**
* @Author: ManhHX
* @Des: get child villages
* @param: $villageId village id
* @return: $data_row:string OR boolean=false
*/ 
function getChildVillage($villageId){
	global $db, $user;
	$uId =  $user["id"];
	
	$query="SELECT id, name, x, y, child_id, workers FROM wg_villages WHERE id=$villageId";
	$db->setQuery($query);	
	$objRes = $db->loadObjectList();
	
	//init array to save child village id
	$arrChild = array();
	if(strlen(trim($objRes[0]->child_id))){// has child village
		$arrChild = split("\|", $objRes[0]->child_id);
	}
	else{
		return false;
	}
			
	$data_row ="";
	$index_i = 1;
	
	if($arrChild){
		//get child village info
		foreach($arrChild as $k => $v){
			$query1="SELECT id, name, x, y, child_id, workers, user_id FROM wg_villages WHERE id=$v";
			$db->setQuery($query1);	
			$objRes1 = $db->loadObjectList();
			$tmpUserId = $objRes1[0]->user_id;		
			
			$query2="SELECT username, id FROM wg_users WHERE id=$tmpUserId";
			$db->setQuery($query2);	
			$objRes2 = $db->loadObjectList();
			
			$arrDataShow = array();
			$arrDataShow["index_i"] = $index_i;		
			$arrDataShow["village_name"] = $objRes1[0]->name;
			$arrDataShow["player_name"] = $objRes2[0]->username;
			$arrDataShow["player_id"] = $objRes2[0]->id;
			$arrDataShow["inhabitants_num"] = $objRes1[0]->workers;
			$arrDataShow["x"] = $objRes1[0]->x;
			$arrDataShow["y"] = $objRes1[0]->y;
			$index_i++;
			$data_row.= parsetemplate(gettemplate('palace_expansion_row'), $arrDataShow);
		}
	}		
	
	return $data_row;
}

 
/**
* @Author: ManhHX
* @Des: get soldier name
* @param: $nationId nation id
* @return: $soldierName:string
*/
function get_soldier($nationId){	
	$soldierName = "";
	switch ($nationId){
		case 1:
			$soldierName = "arabia";
			break;
		case 2:
			$soldierName = "mongo";
			break;
		case 3:
			$soldierName = "sunda";
			break;
		default:
			break;
	}
	return $soldierName;
}
/** =====================================================

END HXMANH ADD

=======================================================*/

/**
 * @author Le Van Tu
 * @todo cap nhat status cho hero
 */
function changeHeroStatus($id, $status){
	global $db;
	$sql="UPDATE wg_heros SET	wg_heros.status=$status	WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo tinh tong troop keep cua mot lang
 */
function getTroopKeep($vl_id, $art){
	global $db;
	$rs = 0;
	//hero trong thanh:
	$ownHero=GetHeroVillage($vl_id);
	if($ownHero){
		$rs += $ownHero->keep_hour;
	}
	
	//linh trong thanh:
	$sql = "SELECT wg_troop_villa.troop_id, wg_troop_villa.num FROM wg_troop_villa WHERE num>0 AND wg_troop_villa.village_id =  '$vl_id'";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){
		foreach($ts as $t){
			$rs += $t->num * $art[$t->troop_id]['keep_hour'];
		}
	}
	
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
					wg_attack.village_defend_id = '$vl_id'
				GROUP BY
					wg_heros.id";
	$db->setQuery($sql);
	$hs = $db->loadObjectList();
	if($hs){
		foreach($hs as $h){
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
	if($ts){
		foreach($ts as $t){
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
					wg_attack.`type` !=  '1' AND
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
						wg_attack.`type` != '1' AND
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
					wg_attack.`type` =  '1' AND 
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
	
	//linh tang vien cho bo lac:
	$sql = "SELECT
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
	if($ts){
		foreach($ts as $t){
			$rs += $t->num * $art[$t->troop_id]['keep_hour'];
		}
	}
	
	//hero cua thanh tham gia cong thanh chien:
	
	//linh cua thanh tham gia cong thanh chien:
	
	//echo "<pre>"; print_r($hs);die();
	
	//echo $rs;die();
	
	return $rs;
}

/**
 * @author Le Van Tu
 * @todo cap nhat troop_keep cho thanh
 */
function updateTroopKeep($village_id, $troopKeep){
	global $db;
	$sql="UPDATE wg_villages SET troop_keep=$troopKeep WHERE id=$village_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo Kiem tra va lay thoi gian dinh chien
 */
function checkDinhChien($u_id){
	global $db;
	$sql = "SELECT wg_plus.dinh_chien FROM wg_plus WHERE wg_plus.user_id = '$u_id'";
	$db->setQuery($sql);
	$t = $db->loadResult();
	if($t && (strtotime($t)>time())){
		return strtotime($t);
	}else{
		return 0;
	}
}

?>