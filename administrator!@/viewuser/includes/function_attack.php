<?php
/**
 * @author vantu
 * @copyright 2008
 * @Des file nay chua cac ham lien quan den attack
 */

function historyTop10($name,$week_nd)
{
	global $db;	
	$sql="SELECT user_id,$name FROM wg_top10 WHERE $name>0 AND week_nd=$week_nd ORDER BY $name DESC LIMIT 10";
	$db->setQuery($sql);
	$wg_top10=NULL;	
	$wg_top10=$db->loadObjectList();
	if($wg_top10)
	{
		switch ($name)
		{
			case "attack_point":
				$type =1;// TOP10_ATTACK;
				break;
			case "defend_point":
				$type =2;//TOP10_DEFEND;
				break;
			case "resource":
				$type =3;// TOP10_TRADE;
				break;
			default:
				$type =0;
				break;
		}		
		foreach($wg_top10 as $key=>$result)
		{	
			$sql="INSERT INTO wg_history_top10 (`user_id`,`rank`,`week_nd`,`type`) VALUES (".$result->user_id.",".($key+1).", $week_nd,$type)";
			$db->setQuery($sql);			
			if(!$db->query())
			{
				globalError2("function historyTop10:".$sql);
			}	
		}
		return true;		
	}
	return false;
}
/* cap nhat thong tin top 10 */
function updateInfoTop10($userid,$column, $value)
{
	global $db;	
	$sqlCheck="SELECT week_nd FROM wg_top10 WHERE user_id=".$userid;	
	$db->setQuery($sqlCheck);
	$week_nd=$db->loadResult();
	$week_nd_new=date("W");	
	if($week_nd)
	{
		if($week_nd_new == $week_nd)
		{
			$sql="UPDATE wg_top10 SET $column=$column+".$value." WHERE user_id=".$userid;
		}
		else
		{
			historyTop10('attack_point',$week_nd);
			historyTop10('defend_point',$week_nd);
			historyTop10('resource',$week_nd);

			$sql="UPDATE wg_top10 SET attack_point=0,defend_point=0,resource=0,week_nd=$week_nd_new";
			$db->setQuery($sql);
			$db->query();
			if($db->getAffectedRows()==0)
			{
				globalError2("function updateInfoTop10:".$sql);
			}
			$sql="UPDATE wg_top10 SET $column=$value WHERE user_id=".$userid;
		}
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2("function updateInfoTop10:".$sql);
		}
		return true;		
	}
	else
	{
		globalError2("function updateInfoTop10:".$sqlCheck);
		return false;
	}
}


/**
 * @author Le Van Tu
 * @des xu ly cac tran danh cua mot lang
 * @param $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben
 * @return $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben
 */ 
function executeAttack($status){
	global $db, $game_config;
	$sql="SELECT
				* 
			FROM
				wg_attack 
			WHERE
				wg_attack.id=$status->object_id AND 
				wg_attack.`status` =  '0' ";
	$db->setQuery($sql);
	$db->loadObject($attack);
	if($attack){
		//Kiem tra xem co event hay khong:
		if(time()>=strtotime($game_config['event_att_time_begin']) && time()< strtotime($game_config['event_att_time_end'])){
			attackEvent($attack, $status);
			return;
		}
		
		//Kiem tra xem user nay bi ban hoac bi xoa hay khong:
		$villageDefend=getVillageDefend($attack->village_defend_id);
		$userDefend=GetUserInfo($villageDefend->user_id, "id, anounment");
		if((!$userDefend && $attack->type!=10 && $attack->type!=11) || $userDefend->anounment=="ban"){
			
			attackBannedUser($attack, $status, $userDefend, $villageDefend);
			
		}else{
			switch($attack->type){
				case 2:
					//truong hop vien tro:
					doReinforce($attack, $status);
					break;
				case 3://truong hop dot kich:
				case 4://truong hop tu chien:
					attack($attack, $status);
					break;
				case 7://Truong hop di do tham lay thong tin quan doi va tai nguyen:
				case 8://Truong hop di do tham lay thong tin quan doi va cong trinh:
					attackSpy($attack, $status);
					break;
				case 9:
					//truong hop co kata:
					attackCatapult($attack, $status);
					break;
				case 10:
				case 11:
					//truong hop danh bo lac:
					attackOasis($attack, $status);
					break;
				case 12:
					//truong hop danh ky dai:
					attackWonder($attack, $status);
					break;
			}
		}
	}else{
		globalError2("Loi: khong tim thay record trong bang attack id=".$sql);
	}
}


/**
 * @author Le Van Tu
 * @des xu ly danh nhau khi co event
 */
function attackEvent($attack, $status){
	global $lang, $game_config;
	includelang("attack");
	
	$villageAttack=getVillage($attack->village_attack_id);
	$villageDefend=getVillageDefend($attack->village_defend_id);
	
	$heroAttack=GetAttackHero($attack->id);
	
	$arrayTroop=getArrayOfTroops();	
	$attackTroopList=getAttackTroop($attack, $arrayTroop);
	
	$arrInfo['info']=$game_config['event_att_msg'];
	
	//cho linh quay ve:
	InsertStatus($attack->village_attack_id, $attack->id, $status->time_end, date("Y-m-d H:i:s", strtotime($status->time_end)+$status->cost_time), $status->cost_time, 10);
	
	writeAttackBannedUserReport($villageAttack, $villageDefend, $heroAttack, $attackTroopList, $attack->type, $status->time_end, $arrInfo);
}

/**
 * @author Le Van Tu
 * @des cho linh di danh quay ve khi mot user da bi ban hoac xoa
 */
function attackBannedUser($attack, $status, $userDefend, $villageDefend){
	global $lang;
	includelang("attack");
	
	$villageAttack=getVillage($attack->village_attack_id);
	
	$arrayTroop=getArrayOfTroops();
	
	$attackTroopList=getAttackTroop($attack, $arrayTroop);
	$heroAttack=GetAttackHero($attack->id);
	
	if(!$villageDefend){
		$arrInfo['info']=$lang['lang_bi_pha_huy'];
	}else{
		if($userDefend->anounment=="ban"){
			$arrInfo['info']=$lang['user_banned'];
		}
	}
	
	//cho linh quay ve:
	InsertStatus($attack->village_attack_id, $attack->id, $status->time_end, date("Y-m-d H:i:s", strtotime($status->time_end)+$status->cost_time), $status->cost_time, 10);
	
	writeAttackBannedUserReport($villageAttack, $villageDefend, $heroAttack, $attackTroopList, $attack->type, $status->time_end, $arrInfo);
}

/**
 * @author Le Van Tu
 * @des tao report khi di danh lang bi ban hoac bi xoa
 */
function writeAttackBannedUserReport($villageAttack, $villageDefend, $heroAttack, $arrAttacks, $type, $time_end, $arrInfos){
	global $lang;
	includeLang("attack");
	$parse=$lang;	
	$village_defend_name="";
	$listTroopVillageAttack=GetListTroopVilla($villageAttack);	
	for($i=0; $i<11; $i++){	
		$live_num = $arrAttacks[$villageAttack->id][$listTroopVillageAttack[$i]->id]['num'];
		$die_num = 	$arrAttacks[$villageAttack->id][$listTroopVillageAttack[$i]->id]['die_num'];
				
		if($live_num>0){
			$parse['st_'.($i+1)] = $live_num ;
			$parse['class_'.($i+1)]="";
		}
		else{
			$parse['st_'.($i+1)] = 0;
			$parse['class_'.($i+1)]="c";
		}
		
		if($die_num>0){
			$parse['casualties_'.($i+1)] = $die_num ;
			$parse['class_ca_'.($i+1)]="";
		}
		else{
			$parse['casualties_'.($i+1)] = 0;
			$parse['class_ca_'.($i+1)]="c";
		}
								
		$parse['icon_'.($i+1)]=$listTroopVillageAttack[$i]->icon;		
		$parse['title_'.($i+1)]=$lang[$listTroopVillageAttack[$i]->name];		
	}
	
	if($heroAttack){
		$parse['st_12']=1;
		$parse['class_12']="";
		if($heroAttack->die_num>0){
			$parse['casualties_12']=1;
			$parse['class_ca_12']="";
		}else{
			$parse['casualties_12']=0;
			$parse['class_ca_12']="c";
		}
	}else{
		$parse['st_12']=0;
		$parse['class_12']="c";
		$parse['casualties_12']=0;
		$parse['class_ca_12']="c";
	}
	$parse['icon_12']="images/icon/hero4.ico";
	$parse['title_12']=$lang['hero'];
	
	$parse['info_rows'] ='';
	
	if(count($arrInfos)>0){
		foreach($arrInfos as $vInfo){	
			$parse['title'] = $lang['info'];		
			$parse['text']=$vInfo;
			$parse['info_rows'].=parsetemplate(gettemplate("report_info_row"), $parse);			
		}
	}
	
	$parse['class_side']="c2 b";
	$parse['side']=$lang['Attacker'];
	$parse['player_name']=GetPlayerName($villageAttack->id);
	$parse['user_id']=$villageAttack->user_id;
	$parse['x']=$villageAttack->x;
	$parse['y']=$villageAttack->y;
	$parse['village_name']=$villageAttack->name;
	$content= parsetemplate(gettemplate("report_village_troop_table"), $parse);
	
	if($villageDefend->kind_id<7){
		$village_defend_name=$villageDefend->name;
	}else{
		$village_defend_name=$lang['oasis']." ($villageDefend->x|$villageDefend->y)";
	}
	
	switch($type){
		case 2:
			$reportTitle=$villageAttack->name.' '.$lang['do_tham'].' '.$village_defend_name;
			break;
		case 3:
			$reportTitle=$villageAttack->name.' '.$lang['dot_kich'].' '.$village_defend_name;
			break;
		case 4:
		case 9:
		case 10:
			$reportTitle=$villageAttack->name.' '.$lang['tu_chien'].' '.$village_defend_name;
			break;
		case 7:
		case 8:
			$reportTitle=$villageAttack->name.' '.$lang['do_tham'].' '.$village_defend_name;
			break;
	}
	
	InsertReport($villageAttack->user_id, $reportTitle, $time_end, $content, REPORT_ATTACK);
}

//---------------Cac ham lien quan den danh bo lac va cuop bau vat------------------------>
/**
 * @author Le Van Tu
 * @des danh o oasis chua co chu
 * @param attack object
 * @param status object
 */
function attackOasis($attack, $status){
	global $db;
	include_once("function_plus.php");
	$plusAtt=getAttDefPlus($villageAttack->user_id, $status->time_end);
	
	$villageAttack=getVillage($attack->village_attack_id, "`id`, `name`, `x`, `y`, `kind_id`, `user_id`, `rs1`, `rs2`, `rs3`, `rs4`, `nation_id`, `child_id`, `krs1`, `krs2`, `krs3`, `krs4`");
	$oasis=getOasis($attack->village_defend_id);
	$oasis_userId_old=$oasis->user_id;
	$arrayTroop=getArrayOfTroops();
		
	$heroAttack=GetAttackHero($attack->id);
	$attackTroopList=getAttackTroop($attack, $arrayTroop);
	
	if($oasis->user_id){
		attackOasisDepend($attack, $status, $villageAttack, $oasis, $heroAttack, $attackTroopList, $plusAtt, $arrayTroop);
		unsetVillageParam($oasis);
		$db->updateObject("wg_villages", $oasis, "id");
		$oasis_map->id			=$oasis->id;
		$oasis_map->user_id	=$oasis->user_id;
	}else{
		attackOasisFree($attack, $status, $villageAttack, $oasis, $heroAttack, $attackTroopList, $plusAtt, $arrayTroop);
		$oasis_map->id			=$oasis->id;
		$oasis_map->user_id	=$oasis->user_id;		
		$oasis_map->faith		=$oasis->faith;
	}
	
	//cap nhat so linh chet xuong database:
	updateTroopAttack($attackTroopList, $villageAttack, $arrayTroop);
	
	//Cap nhat hero attack:
	updateHeroAttack($heroAttack, $villageAttack);
	
	//cap nhat cho lang danh va oasis:
	unsetVillageParam($villageAttack);
	if($oasis_map->user_id != $oasis_userId_old )
	{
		$db->updateObject("wg_villages", $villageAttack, "id");
	}
	$db->updateObject("wg_villages_map", $oasis_map, "id");
}

/**
 * @author Le Van Tu
 * @des danh o oasis chua co chu
 * @param attack object
 * @param status object
 */
function attackOasisFree($attack, $status, &$villageAttack, &$oasis, &$heroAttack, &$attackTroopList, $plusAtt, $arrayTroop){
	global $lang, $db;
	includelang("attack");
	$kata=0;
	$coRc=false;
	$heroDefendList=array();
	
	$oasisTrpAtt=getOasisTrpAtt($oasis->id);
	if($oasisTrpAtt){
		$oasis->faith=$oasisTrpAtt->faith;
		$coRc=true;
	}else{
		$oasis->faith=100;
	}
	
	$oasisTroopList=getOasisTroop($oasis, $oasisTrpAtt, strtotime($status->time_end), $arrayTroop);
	
	phase($attackTroopList, $heroAttack, $oasisTroopList, $heroDefendList, 1, $kata, $attack->type, $plusAtt);
	
	$speedBack=getSpeedTroopBack($heroAttack, $attackTroopList);
	if($speedBack>0){
		//quan tan cong da thang tran:
		$s=S($villageAttack->x, $villageAttack->y, $oasis->x, $oasis->y);
		$duration=intval(GetDuration($s, $speedBack));
		
		if($attack->type == 11){
			//Kiem tra va lay bau vat:
			if($heroAttack->num==1 && $heroAttack->die_num==0){
				$bauVat=occupyRare($villageAttack, $oasis, $status->time_end, $duration);
				if($bauVat){
					$arrInfo['bau_vat']=$bauVat;
				}
				$chckFd=checkFeudatory($villageAttack, $oasis, $arrInfo);
				switch($chckFd){
					case 1:
						$arrInfo['thu_phuc']=$lang['da_thu_phuc_bo_lac'];
						break;
					case 2:
						$arrInfo['thu_phuc']=$lang['giam_long_tin_bo_lac'];
						break;
				}
			}
		}
			
		
		//keo linh ve
		InsertStatusTroopAttackBack($attack->id, $attack->village_attack_id, date("Y-m-d H:i:s", strtotime($status->time_end)), date("Y-m-d H:i:s", strtotime($status->time_end)+$duration), $duration);
	}else{
		//truong hop bai tran:
		SetAttackStatus($attack->id);
		$arrInfo['bai_tran']=$lang['that_bai'];
	}
	
	// Cho xep hang ally va User attack	
	$oasisTroopKeepDie = executeUserAttackOasisPoint($attackTroopList, $oasisTroopList);	
	executeAllyAttackOasis($attackTroopList, $heroAttack, $oasisTroopKeepDie);
	//echo "<pre>"; print_r($oasisTroopList);die();
	//Tao report:
	writeAttackOasisReport($villageAttack, $oasis, $attackTroopList, $heroAttack, $oasisTroopList, $arrInfo, $status->time_end, $attack->type);
	
	updateOasisTroop($oasis, $oasisTroopList, $status->time_end, $oasis->faith, $coRc, $chckFd);
}

/**
 * @author Le Van Tu
 * @des lay thong tinh cua oasis trong bang wg_oasis_troop_att
 */
function getOasisTrpAtt($oasis_id){
	global $db;
	$result=null;
	$sql="SELECT * FROM wg_oasis_troop_att WHERE village_id=$oasis_id";
	$db->setQuery($sql);
	$db->loadObject($result);
	return $result;	
}

/**
 * @author Le Van Tu
 * @des danh o oasis da co chu
 * @param attack object
 * @param status object
 */
function attackOasisDepend($attack, $status, &$villageAttack, &$oasis, &$heroAttack, &$attackTroopList, $plusAtt, $arrayTroop){
	
	global $lang, $db;
	includelang("attack");
	$kata=0;
	
	$heroDefendList=getReinforceHeroList($oasis->id);
	$oasisTroopList=getReinforeTroop($attack, $arrayTroop);
	
	phase($attackTroopList, $heroAttack, $oasisTroopList, $heroDefendList, 1, $kata, $attack->type, $plusAtt);
	
	$speedBack=getSpeedTroopBack($heroAttack, $attackTroopList);
	if($speedBack>0){
		//quan tan cong da thang tran:
		$s=S($villageAttack->x, $villageAttack->y, $oasis->x, $oasis->y);
		$duration=intval(GetDuration($s, $speedBack));
		
		//Kiem tra va chiem bo lac:
		if($attack->type == 11){
			if($heroAttack->num==1 && $heroAttack->die_num==0){
				switch(checkFeudatory($villageAttack, $oasis, $arrInfo)){
					case 1:
						$arrInfo['thu_phuc']=$lang['da_thu_phuc_bo_lac'];
						break;
					case 2:
						$arrInfo['thu_phuc']=$lang['giam_long_tin_bo_lac'];
						break;
				}
			}
		}
		
		//keo linh ve
		InsertStatusTroopAttackBack($attack->id, $attack->village_attack_id, date("Y-m-d H:i:s", strtotime($status->time_end)), date("Y-m-d H:i:s", strtotime($status->time_end)+$duration), $duration);
	}else{
		//truong hop bai tran:
		SetAttackStatus($attack->id);
		$arrInfo['bai_tran']=$lang['that_bai'];
	}
		
	//Tao report:
	writeAttackOasisDependReport($villageAttack, $oasis, $attackTroopList, $heroAttack, $oasisTroopList, $heroDefendList, $arrInfo, $status->time_end, $attack->type);
	
	//Cap nhat linh thu:
	updateHeroReinforceOasis($heroDefendList);
	updateTroopReinforceOasis($oasisTroopList, $arrayTroop);
}

/**
 * @author Le Van Tu
 * @des tao va luu report sau khi danh bo lac da co chu
 */
function writeAttackOasisReport($villageAttack, $villageDefend, $arrAttacks, $heroAttack, $arrDefs, $arrInfos, $time_end, $type){
	global $lang;
	includeLang("attack");
	$parse=$lang;		
	//Begin execute attack data report
	$vIdAttCheck = 0;
	$numAttSoldier = 0;
	$userAttackId =0;
	
	if($arrAttacks){
		foreach($arrAttacks as $k =>$v){
			if($vIdAttCheck ==0 ){
				$objAttackVillage = getVillageInfoById($k);
				$listTroopVillageAttack=GetListTroopVilla($objAttackVillage[0]);							
				$parse['player_attack_name']=GetPlayerName($k);			
				$parse['village_attack_name']=$objAttackVillage[0]->name;
				$parse['attack_x']=$objAttackVillage[0]->x;
				$parse['attack_y']=$objAttackVillage[0]->y;
				$userAttackId = GetPlayerID($k);
				$vIdAttCheck = $k;
			}
		}
	}		
			
	for($i=0; $i<11; $i++){	
		$live_num = $arrAttacks[$vIdAttCheck][$listTroopVillageAttack[$i]->id]['num'];
		$die_num = 	$arrAttacks[$vIdAttCheck][$listTroopVillageAttack[$i]->id]['die_num'];
		$numAttSoldier = $numAttSoldier + $live_num - $die_num;
		
		if($live_num=='' || !$live_num){
			$live_num = 0;
			$parse['class_att_'.($i+1)]="c";
		}
		else{
			$parse['class_att_'.($i+1)]="";
		}
		
		if($die_num=='' || !$die_num){
			$die_num = 0;
			$parse['class_at_ca_'.($i+1)]="c";
		}
		else{
			$parse['class_at_ca_'.($i+1)]="";
		}
		
		$parse['st_att_'.($i+1)] = $live_num ;
		$parse['casualties_att_'.($i+1)] = $die_num ;				
		$parse['iconat'.($i+1)]=$listTroopVillageAttack[$i]->icon;		
		$parse['titleat'.($i+1)]=$lang[$listTroopVillageAttack[$i]->name];		
	}
	
	if($heroAttack){
		$parse['st_att_12']=1;
		$parse['class_att_12']="";
		if($heroAttack->die_num>0){
			$parse['casualties_att_12']=1;
			$parse['class_at_ca_12']="";
		}else{
			$numAttSoldier = $numAttSoldier + 1;
			$parse['casualties_att_12']=0;
			$parse['class_at_ca_12']="c";
		}
	}else{
		$parse['st_att_12']=0;
		$parse['class_att_12']="c";
		$parse['casualties_att_12']=0;
		$parse['class_at_ca_12']="c";
	}
	$parse['iconat12']="images/icon/hero4.ico";
	$parse['titleat12']=$lang['hero'];
	
	$parse['info_rows'] ='';
	if($arrInfos){
		foreach($arrInfos as $kInfo =>$vInfo){	
			switch($kInfo){
				case 'bau_vat':
					$parse['cong_trinh'] = $lang['rare'];
					break;				
				default:	
					$parse['cong_trinh'] = $lang['info'];		
					break;			
			}
			$parse['building_info']=$vInfo;
			$parse['info_rows'].=parsetemplate(gettemplate("attack_report_destroyed_row"), $parse);	
			
		}
	}		
	
	
	$i=1;
	if($arrDefs){
		foreach($arrDefs as $listTroop){
			foreach($listTroop as $troop){
				$parse['icon_def_'.$i]=$troop['icon'];
				$parse['title_def_'.$i]=$lang[$troop['name']];
				$parse['st_def_'.$i]=$troop['num'];
				$parse['casualties_def_'.$i]=$troop['die_num'];
				$i++;
			}
		}
	}
		
	
	$parse['user_attack_id']=$villageAttack->user_id;
	$parse['defender_x']=$villageDefend->x;
	$parse['defender_y']=$villageDefend->y;
	$parse['village_defend_name']=$lang['oasis']." ($villageDefend->x|$villageDefend->y)";
	
	switch($type){
		case 10:
			$reportTitle=$parse['village_attack_name'].' '.$lang['dot_kich'].' '.$parse['village_defend_name'];
			break;
		case 11:
			$reportTitle=$parse['village_attack_name'].' '.$lang['tu_chien'].' '.$parse['village_defend_name'];
			break;
	}
	
	
	$content=parsetemplate(gettemplate("attack_oasis_report"), $parse);
	InsertReport($userAttackId, $reportTitle, $time_end, $content, REPORT_ATTACK);
}

/**
 * @author Le Van Tu
 * @des tao va luu report sau khi danh bo lac
 */
function writeAttackOasisDependReport($villageAttack, $villageDefend, $arrAttacks, $heroAttack, $arrDefs, $heroDefendList, $arrInfos, $time_end, $type){
	global $lang;
	includeLang("attack");
	$parse = $lang;		
	$listTroopVillageAttack = GetListTroopVilla($villageAttack);
	
	for($i=0; $i<11; $i++){	
		$live_num = $arrAttacks[$villageAttack->id][$listTroopVillageAttack[$i]->id]['num'];
		$die_num = 	$arrAttacks[$villageAttack->id][$listTroopVillageAttack[$i]->id]['die_num'];
				
		if($live_num>0){
			$parse['st_'.($i+1)] = $live_num ;
			$parse['class_'.($i+1)]="";
		}
		else{
			$parse['st_'.($i+1)] = 0;
			$parse['class_'.($i+1)]="c";
		}
		
		if($die_num>0){
			$parse['casualties_'.($i+1)] = $die_num ;
			$parse['class_ca_'.($i+1)]="";
		}
		else{
			$parse['casualties_'.($i+1)] = 0;
			$parse['class_ca_'.($i+1)]="c";
		}
								
		$parse['icon_'.($i+1)]=$listTroopVillageAttack[$i]->icon;		
		$parse['title_'.($i+1)]=$lang[$listTroopVillageAttack[$i]->name];		
	}
	
	if($heroAttack){
		$parse['st_12']=1;
		$parse['class_12']="";
		if($heroAttack->die_num>0){
			$parse['casualties_12']=1;
			$parse['class_ca_12']="";
		}else{
			$parse['casualties_12']=0;
			$parse['class_ca_12']="c";
		}
	}else{
		$parse['st_12']=0;
		$parse['class_12']="c";
		$parse['casualties_12']=0;
		$parse['class_ca_12']="c";
	}
	$parse['icon_12']="images/icon/hero4.ico";
	$parse['title_12']=$lang['hero'];
	
	$parse['info_rows'] ='';
	if(count($arrInfos)>0){
		foreach($arrInfos as $kInfo =>$vInfo){	
			$parse['cong_trinh'] = $lang['info'];		
			$parse['building_info']=$vInfo;
			$parse['info_rows'].=parsetemplate(gettemplate("attack_report_destroyed_row"), $parse);			
		}
	}
	
	$parse['class_side']="c2 b";
	$parse['side']=$lang['Attacker'];
	$parse['player_name']=GetPlayerName($villageAttack->id);
	$parse['user_id']=$villageAttack->user_id;
	$parse['x']=$villageAttack->x;
	$parse['y']=$villageAttack->y;
	$parse['village_name']=$villageAttack->name;
	$attackTable=parsetemplate(gettemplate("report_village_troop_table"), $parse);
	
	//tao cac bang quan vien tro:
	if(count($arrDefs)>0){
		foreach($arrDefs as $vid=>$troopReinforceList){
			$villageReinforce=getVillage($vid);
			$listTroopVillage=GetListTroopVilla($villageReinforce);
		
			for($i=0; $i<11; $i++){	
				$live_num = $troopReinforceList[$listTroopVillage[$i]->id]['num'];
				$die_num = 	$troopReinforceList[$listTroopVillage[$i]->id]['die_num'];
						
				if($live_num>0){
					$parse['st_'.($i+1)] = $live_num ;
					$parse['class_'.($i+1)]="";
				}
				else{
					$parse['st_'.($i+1)] = 0;
					$parse['class_'.($i+1)]="c";
				}
				
				if($die_num>0){
					$parse['casualties_'.($i+1)] = $die_num ;
					$parse['class_ca_'.($i+1)]="";
				}
				else{
					$parse['casualties_'.($i+1)] = 0;
					$parse['class_ca_'.($i+1)]="c";
				}
										
				$parse['icon_'.($i+1)]=$listTroopVillage[$i]->icon;		
				$parse['title_'.($i+1)]=$lang[$listTroopVillage[$i]->name];		
			}
			
			$heroDefend=$heroDefendList[$vid];
			if($heroDefend){
				$parse['st_12']=1;
				$parse['class_12']="";
				if($heroDefend->die_num>0){
					$parse['casualties_12']=1;
					$parse['class_ca_12']="";
				}else{
					$numAttSoldier = $numAttSoldier + 1;
					$parse['casualties_12']=0;
					$parse['class_ca_12']="c";
				}
				$heroDefendList[$vid]=null;
			}else{
				$parse['st_12']=0;
				$parse['class_12']="c";
				$parse['casualties_12']=0;
				$parse['class_ca_12']="c";
			}
			$parse['icon_12']="images/icon/hero4.ico";
			$parse['title_12']=$lang['hero'];
			
			$parse['info_rows'] ='';
			
			$parse['class_side']="c1 b";
			$parse['info_rows']='';
			$parse['side']=$lang['Sender'];
			$parse['player_name']=GetPlayerName($villageReinforce->id);
			$parse['village_name']=$villageReinforce->name;
			$parse['user_id']=$villageReinforce->user_id;
			$parse['x']=$villageReinforce->x;
			$parse['y']=$villageReinforce->y;
			$reinforceTable=parsetemplate(gettemplate("report_village_troop_table"), $parse);
			$reinforceTables.=$reinforceTable;
			if($villageReinforce->user_id != $villageDefend->user_id){
				$reinforceReportList[]=$reinforceTable;
				$reinforceUserIdList[]=$villageReinforce->user_id;
			}				
		}
	}
	
	//Tao bang nhung hero di mot minh:
	if(count($heroDefendList)>0){
		foreach($heroDefendList as $vid=>$heroDefend){
			if($heroDefend){
				$villageReinforce=getVillage($vid);
				$listTroopVillage=GetListTroopVilla($villageReinforce);
			
				for($i=0; $i<11; $i++){
					$parse['st_'.($i+1)] = 0;
					$parse['class_'.($i+1)]="c";
					
					$parse['casualties_'.($i+1)] = 0;
					$parse['class_ca_'.($i+1)]="c";
											
					$parse['icon_'.($i+1)]=$listTroopVillage[$i]->icon;		
					$parse['title_'.($i+1)]=$lang[$listTroopVillage[$i]->name];		
				}
				
				$parse['st_12']=1;
				$parse['class_12']="";
				if($heroDefend->die_num>0){
					$parse['casualties_12']=1;
					$parse['class_ca_12']="";
				}else{
					$numAttSoldier = $numAttSoldier + 1;
					$parse['casualties_12']=0;
					$parse['class_ca_12']="c";
				}
				$parse['icon_12']="images/icon/hero4.ico";
				$parse['title_12']=$lang['hero'];
				
				$parse['info_rows'] ='';
				
				$parse['class_side']="c1 b";
				$parse['info_rows']='';
				$parse['side']=$lang['Sender'];
				$parse['player_name']=GetPlayerName($villageReinforce->id);
				$parse['village_name']=$villageReinforce->name;
				$parse['user_id']=$villageReinforce->user_id;
				$parse['x']=$villageReinforce->x;
				$parse['y']=$villageReinforce->y;
				$reinforceTable=parsetemplate(gettemplate("report_village_troop_table"), $parse);
				$reinforceTables.=$reinforceTable;
				if($villageReinforce->user_id != $villageDefend->user_id){
					$reinforceReportList[]=$reinforceTable;
					$reinforceUserIdList[]=$villageReinforce->user_id;
				}				
			}				
		}
	}
	
	$content=$attackTable."<br>".$reinforceTables;	
	
	switch($type){
		case 10:
			$reportTitle=$villageAttack->name.' '.$lang['dot_kich'].' '.$lang['oasis']." ($villageDefend->x|$villageDefend->y)";
			break;
		case 11:
			$reportTitle=$villageAttack->name.' '.$lang['tu_chien'].' '.$lang['oasis']." ($villageDefend->x|$villageDefend->y)";
			break;
	}
	
	InsertReport($villageAttack->user_id, $reportTitle, $time_end, $content, REPORT_ATTACK);
	if($villageAttack->user_id != $villageDefend->user_id){
		InsertReport($villageDefend->user_id, $reportTitle, $time_end, $content, REPORT_ATTACK);
	}		
	
	if(count($reinforceReportList)>0){
		$reportTitle=$lang['reinforce_title_1'].' '.$lang['oasis']." ($villageDefend->x|$villageDefend->y) ".$lang['giup_do2'];
		$i=0;
		foreach($reinforceReportList as $content){
			InsertReport($reinforceUserIdList[$i], $reportTitle, $time_end, $content, REPORT_ATTACK);
			$i++;
		}
	}
		
}

/**
 * @author Le Van Tu
 * @des kiem tra va cap phat bo lac cho mot lang khi di danh o oasis
 * @return bool
 */
function checkFeudatory(&$villageAttack, &$oasis, &$arrInfo){	
	global $db;
	$result=0;
	//lay level cua tuong phu
	$heroMansionLevel=GetBuildingLevel($villageAttack->id, 35);
	$sumFeudation=getSumFeudatory($villageAttack->id);
	
	if($heroMansionLevel>=10){
		if($heroMansionLevel>=20){
			$maxFeudation=3;
		}else{
			if($heroMansionLevel>=15){
				$maxFeudation=2;
			}else{
				$maxFeudation=1;
			}
		}
	}else{
		$maxFeudation=0;
	}
	
	//Kiem tra xem co du dieu kien de chiem bo lac khong
	if($sumFeudation<$maxFeudation){
		//kiem tra xem con long trung thanh khong:
		if($oasis->faith<35){
			//da het long trung thanh->co the chiem duoc
			//Tang suc khac thac tai nguyen cho lang danh:
			$arrInfo['cong_pham']=tangKrs($villageAttack, $oasis);
			
			//neu bo lac nay da co chu -> tru suc tang RS cua lang chu:
			if($oasis->user_id){
				$mainVillageObj=getVillage($oasis->id, "child_id");
				$villageReinforce=getVillage($mainVillageObj->child_id, "id,user_id,krs1, krs2, krs3, krs4");
				giamKrs($villageReinforce, $oasis);
				unsetVillageParam($villageReinforce);
				$db->updateObject("wg_villages", $villageReinforce, "id");
			}		
			
			//cap nhat thong so ve quyen so huu:
			
			if($oasis->user_id){
				$oasis->child_id=$villageAttack->id;
				$oasis->faith=100;
			}else{
				$oasis->faith=100;
				insertOasis($oasis->id, $oasis->x, $oasis->y, $oasis->kind_id, $villageAttack->user_id, $villageAttack->id, 100, $villageAttack->nation_id);
			}
			$oasis->user_id=$villageAttack->user_id;
			$oasis->nation_id=$villageAttack->nation_id;
			
			$result=1;
		}else{
			//Giam long trung thanh cua bo lac nay:
			$oasis->faith-=35;
			$result=2;
		}			
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des chen o aosis vao bang wg_villages
 */
function insertOasis($oasis_id, $x, $y, $kind_id, $user_id, $village_id, $faith, $nation_id){
	global $db;
	$sql="INSERT INTO `wg_villages` (`id`, `x`, `y`, `kind_id`, `user_id`, `child_id`, `faith`, `nation_id`) VALUES
($oasis_id, $x, $y, $kind_id, $user_id, $village_id, $faith, $nation_id)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi nhap o oasis:".$sql);
	}
}

/**
 * @author Le Van Tu
 * @des tinh so chu hau da co cua mot lang
 */
function getSumFeudatory($village_id){
	global $db;
	$sql="SELECT COUNT(*) FROM wg_villages WHERE kind_id >=7 AND child_id=$village_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @des tang suc tang rs cua mot lang khi danh bo lac
 */
function tangKrs(&$villageAttack, $oasis){
	global $db,$lang;
	$sql="SELECT lumber,clay,iron,crop FROM wg_plus WHERE user_id=".$villageAttack->user_id;
	$db->setQuery($sql);
	$db->loadObject($wg_plus);
	$lumber=0;
	$clay=0;
	$iron=0;
	$crop=0;
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
	}
	switch($oasis->kind_id){
			case 7:				
				if($lumber >1)
				{
					$villageAttack->krs1 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs1 +=0.25;
				}
				return $lang['tang']." 25% ".$lang['suc_san_xuat']." ".$lang['go'].".";
				break;
			case 8:				
				if($lumber >1)
				{
					$villageAttack->krs1 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs1 +=0.25;
				}				
				if($crop >1)
				{
					$villageAttack->krs4 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs4 +=0.25;
				}
				return $lang['tang']." 25% ".$lang['suc_san_xuat']." ".$lang['go']." | 25% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
				break;
			case 9:				
				if($iron >1)
				{
					$villageAttack->krs3 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs3 +=0.25;
				}
				return $lang['tang']." 25% ".$lang['suc_san_xuat']." ".$lang['sat'].".";
				break;
			case 10:				
				if($iron >1)
				{
					$villageAttack->krs3 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs3 +=0.25;
				}
				if($crop >1)
				{
					$villageAttack->krs4 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs4 +=0.25;
				}
				return $lang['tang']." 25% ".$lang['suc_san_xuat']." ".$lang['sat']." | 25% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
				break;
			case 11:				
				if($clay >1)
				{
					$villageAttack->krs2 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs2 +=0.25;
				}
				return $lang['tang']." 25% ".$lang['suc_san_xuat']." ".$lang['da'].".";
				break;
			case 12:				
				if($clay >1)
				{
					$villageAttack->krs2 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs2 +=0.25;
				}				
				if($crop >1)
				{
					$villageAttack->krs4 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs4 +=0.25;
				}
				return $lang['tang']." 25% ".$lang['suc_san_xuat']." ".$lang['da']." | 25% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
				break;
			case 13:
				if($crop >1)
				{
					$villageAttack->krs4 +=1.25*0.25;
				}
				else
				{
					$villageAttack->krs4 +=0.25;
				}
				return $lang['tang']." 25% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
				break;
			case 14:				
				if($crop >1)
				{
					$villageAttack->krs4 +=1.25*0.5;
				}
				else
				{
					$villageAttack->krs4 +=0.5;
				}
				return $lang['tang']." 50% ".$lang['suc_san_xuat']." ".$lang['lua'].".";
				break;
	}
}

/**
 * @author Le Van Tu
 * @des tang suc tang rs cua mot lang khi danh bo lac
 */
function giamKrs(&$village, $oasis){
	global $db,$lang;
	$sql="SELECT lumber,clay,iron,crop FROM wg_plus WHERE user_id=".$village->user_id;
	$db->setQuery($sql);
	$db->loadObject($wg_plus);
	$lumber=0;
	$clay=0;
	$iron=0;
	$crop=0;
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
	}
	switch($oasis->kind_id){
			case 7:				
				if($lumber >1)
				{
					$village->krs1 -=1.25*0.25;
				}
				else
				{
					$village->krs1 -=0.25;
				}			
				break;
			case 8:				
				if($lumber >1)
				{
					$village->krs1 -=1.25*0.25;
				}
				else
				{
					$village->krs1 -=0.25;
				}				
				if($crop >1)
				{
					$village->krs4 -=1.25*0.25;
				}
				else
				{
					$village->krs4 -=0.25;
				}				
				break;
			case 9:				
				if($iron >1)
				{
					$village->krs3 -=1.25*0.25;
				}
				else
				{
					$village->krs3 -=0.25;
				}				
				break;
			case 10:				
				if($iron >1)
				{
					$village->krs3 -=1.25*0.25;
				}
				else
				{
					$village->krs3 -=0.25;
				}
				if($crop >1)
				{
					$village->krs4 -=1.25*0.25;
				}
				else
				{
					$village->krs4 -=0.25;
				}				
				break;
			case 11:				
				if($clay >1)
				{
					$village->krs2 -=1.25*0.25;
				}
				else
				{
					$village->krs2 -=0.25;
				}				
				break;
			case 12:				
				if($clay >1)
				{
					$village->krs2 -=1.25*0.25;
				}
				else
				{
					$village->krs2 -=0.25;
				}				
				if($crop >1)
				{
					$village->krs4 -=1.25*0.25;
				}
				else
				{
					$village->krs4 -=0.25;
				}				
				break;
			case 13:
				if($crop >1)
				{
					$village->krs4 -=1.25*0.25;
				}
				else
				{
					$village->krs4 -=0.25;
				}
				break;
			case 14:				
				if($crop >1)
				{
					$village->krs4 -=1.25*0.5;
				}
				else
				{
					$village->krs4 -=0.5;
				}
				break;
	}
}

/**
 * @author Le Van Tu
 */
function getOasis($id){
	global $db;
	$sql = "SELECT	id, x,	y, kind_id, user_id,	faith FROM	wg_villages	WHERE	id = $id";
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
		}else{
			globalError2("Loi oasis khong ton tai khi xu ly attack! getOasis($id)");
		}
	}		
}

/**
 * @author Le Van Tu
 * @des kiem tra lang bi danh co bau vat khong neu co thi chuyen cho lang di danh
 * @param $time_end la mot chuoi
 */
function occupyRare($villageAttack, $villageDefend, $time_end, $cost_time){
	global $lang;
	$result=null;
	$rare=checkRare($villageDefend->id);
	if($rare){
		//co bau vat
		moveRare($villageDefend->id, $villageAttack->id, $rare->kim, $rare->thuy, $rare->moc, $rare->hoa, $rare->tho, $time_end, $cost_time);
		if($rare->kim>0){
			$result.=$rare->kim." ".$lang['kim'];
		}
		
		if($rare->thuy>0){
			if($result){
				$result.=" | ";
			}
			$result.=$rare->thuy." ".$lang['thuy'];
		}
		
		if($rare->moc>0){
			if($result){
				$result.=" | ";
			}
			$result.=$rare->moc." ".$lang['moc'];
		}
		
		if($rare->hoa>0){
			if($result){
				$result.=" | ";
			}
			$result.=$rare->hoa." ".$lang['hoa'];
		}
		
		if($rare->tho>0){
			if($result){
				$result.=" | ";
			}
			$result.=$rare->tho." ".$lang['tho'];
		}
	}else{
		if($villageDefend>=7){
			$result=$lang['no_rare'];
		}
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des Chuyen bau vat tu lang nay toi lang khac
 * @param time_end la chuoi
 */
function moveRare($village_from_id, $village_to_id, $kim, $thuy, $moc, $hoa, $tho, $time_end, $cost_time){
	global $db;
	deleteRare($village_from_id);
	$object_id=insertRareSend($village_from_id, $village_to_id, $kim, $thuy, $moc, $hoa, $tho);
	InsertStatus($village_to_id, $object_id, $time_end, date("Y-m-d H:i:s", strtotime($time_end)+$cost_time), $cost_time, 25, 4);
}


/**
 * @author Le Van Tu
 * @des Kiem tra oasis co bau vat hay khong
 */
function checkRare($village_id){
	global $db;
	$sql="SELECT * FROM wg_rare WHERE vila_id=$village_id AND (kim>0 OR thuy>0 OR hoa>0 OR moc>0 OR tho>0)";
	$db->setQuery($sql);
	$db->loadObject($rare);
	return $rare;
}

/**
 * @author Le Van Tu
 * @des Them mot recode vao bang rare send
 */
function insertRareSend($village_from_id, $village_to_id, $kim, $thuy, $moc, $hoa, $tho){
	global $db;
	$sql="INSERT INTO wg_rare_sends (`village_id_from`, `village_id_to`, `kim`, `thuy`, `moc`, `hoa`, `tho`, `status`) VALUES ($village_from_id, $village_to_id, $kim, $thuy, $moc, $hoa, $tho, 0)";
	$db->setQuery($sql);
	if($db->query()){
		return $db->insertid();
	}else{	
		globalError2("Loi them mot recode vao bang wg_rare_sends".$sql);
	}
}

/**
 * @author Le Van Tu
 * @des xoa mot recode trong bang wg_rare
 */
function deleteRare($village_id){
	global $db;
	$sql="DELETE FROM wg_rare WHERE vila_id=$village_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi xoa mot recode trong bang wg_rare deleteRare($id)");
	}
}

//<<---------------END cac ham lien quan den danh bo lac va cuop bau vat---------------------------<<<<<

/**
 * @author Le Van Tu
 * @des xu ly su kiem linh di vien tro da den thanh khach
 * @param attack object
 */
function doReinforce($attack, $status){
	global $db;
	
	$villageAttack=getVillage($attack->village_attack_id);
	$villageDefend=getVillageDefend($attack->village_defend_id);
	
	$listTroop=GetListTroopVilla($villageAttack);
	$listAttackTroop=GetListAttackTroop($attack->id);
	
	$sumUpkeep=0;
	
	if($heroAttack=GetAttackHero($attack->id)){
		//kiem tra xem co phai tuong chuyen thanh khong:
		if($villageAttack->user_id == $villageDefend->user_id){
			$chuyenThanh=tuongChuyenLang($attack->village_defend_id, $heroAttack);
		}
		$sumUpkeep += $heroAttack->keep_hour;
	}
	
	
	$sql="SELECT *
			FROM 
				wg_attack
			WHERE
				wg_attack.`type` =  '1' AND
				wg_attack.village_attack_id =  '$attack->village_attack_id' AND
				wg_attack.village_defend_id =  '$attack->village_defend_id' ";
	$db->setQuery($sql);
	$db->loadObject($reinforce);
	if($reinforce){
		SetAttackStatus($attack->id);
		
		if($reinforce->status==1){
			SetAttackStatus($reinforce->id, 0);						
		}
		
		if($heroAttack && !$chuyenThanh){
			changeAttackIDInAttackHero($heroAttack->attack_hero_id, $reinforce->id);
		}
		
		if(count($listAttackTroop)>0){
			$listAttackTroopOld=GetListAttackTroop($reinforce->id);		
			foreach($listAttackTroop as $tid=>$troop){
				if(isset($listAttackTroopOld[$tid])){
					changeAttackTroop($listAttackTroopOld[$tid]['attack_troop_id'], $troop['sum']);
					deleteAttackTroop($troop['attack_troop_id']);
				}else{
					changeAttackIDInAttackTroop($troop['attack_troop_id'], $reinforce->id);
				}
			}
		}			
	}else{
		changeAttackType($attack->id);
	}
		
	if(count($listAttackTroop)>0){
		//tinh luong luong thuc linh tieu thu:
		foreach($listTroop as $troop){
			if($listAttackTroop[$troop->id]['sum']>0){
				$sumUpkeep+=$troop->keep_hour*$listAttackTroop[$troop->id]['sum'];
			}
		}
	}
	
	//Kiem tra xem co phai bo lac hay khong:
	if($villageDefend->kind_id<7){
		//giam so luong thuc tieu thu cua lang gui:
		ChangeTroopKeepVillage($attack->village_attack_id, (0-$sumUpkeep));
		
		//Tang so luong thuc tieu thu cua lang nhan:
		ChangeTroopKeepVillage($attack->village_defend_id, $sumUpkeep);
	}
		
	reportReinforce($status, $attack, $villageAttack, $villageDefend, $listAttackTroop, $listTroop, $heroAttack, $sumUpkeep);
}

/**
 * @author Le Van Tu
 * @des thay doi attack id trong bang wg_attack_troop
 * @param wg_attack_troop.id
 * @param2 new attack_id
 */
function changeAttackIDInAttackTroop($id, $attack_id){
	global $db;
	$sql="UPDATE wg_attack_troop SET attack_id=$attack_id WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi thay doi attack_id (changeAttackIDInAttackTroop): id: $id, attack_id: $attack_id");
	}
}

/**
 * @author Le Van Tu
 * @des thay doi attack id trong bang wg_attack_hero
 * @param wg_attack_hero.id
 * @param2 new attack_id
 */
function changeAttackIDInAttackHero($id, $attack_id){
	global $db;
	$sql="UPDATE wg_attack_hero SET attack_id=$attack_id WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi thay doi attack_id (changeAttackIDInAttackTroop): id: $id, attack_id: $attack_id");
	}
}

/**
 * @author Le Van Tu
 * @des doi attack type=1 (quan vien tro dang o lang khac)
 * @param $attack id
 */
function changeAttackType($id){
	global $db;
	$sql="UPDATE wg_attack SET type=1 WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi cap nhat wg_attack type=1");
	}
} 

/**
 * @author Le Van Tu
 * @des Cap nhat khi chien loi pham da ve den noi
 * @param object status
 * @return void
 */
function executeAttackGetResource($status){
	global $db;
	$sql="SELECT * FROM wg_resource_sends WHERE id=$status->object_id";
	$db->setQuery($sql);
	$db->loadObject($rssend);
	if($rssend){
		changeRSVillage($rssend->village_id_to, $rssend->rs1, $rssend->rs2, $rssend->rs3, $rssend->rs4);
		setSendResourceStatus($rssend->id);
	}
}


/**
 * @author Le Van Tu
 * @des cap nhat rs cho lang bi tan cong truoc khi tinh toan attack
 * @param1 object village
 * @param2 thoi diem cap nhat
 */
function updateRSOtherVillage(&$village, $time){
	global $db;
	$wg_buildings = getBuildings($village->id);
	getSumCapacity($village, $wg_buildings);
	UpdateRS($village, $wg_buildings, $time);
}

/**
 * @author Le Van Tu
 * @des Tao va gui report khi mot lang vien tro linh cho lang khac
 * @param
 * @return $sumUpKeep luong luong thuc ma quan vien tro tieu thu
 */
function reportReinforce($status, $attack, $villageAttack, $villageDefend, $listAttackTroop, $listTroop, $heroAttack, $sumUpkeep){
	global $lang;
	includelang('rally_point');
	$parse=$lang;
	
	
	for($i=0; $i<11; $i++){
		$parse['icon'.($i+1)]=$listTroop[$i]->icon;
		$parse['title'.($i+1)]=$lang[$listTroop[$i]->name];
		if($listAttackTroop[$listTroop[$i]->id]['sum']>0){
			$parse['t'.($i+1)]=$listAttackTroop[$listTroop[$i]->id]['sum'];
			$parse['class'.($i+1)]="";
		}else{
			$parse['t'.($i+1)]=0;
			$parse['class'.($i+1)]="c";
		}		
	}
	
	if($heroAttack){	
		$parse['t'.($i+1)]=1;
		$parse['class'.($i+1)]="";
	}else{
		$parse['t'.($i+1)]=0;
		$parse['class'.($i+1)]="c";
	}
	$parse['title'.($i+1)]=$lang['hero'];;
	$parse['icon'.($i+1)]="images/icon/hero4.ico";
	
	$parse['action']='';
	$parse['upkeep']=$sumUpkeep;
	$parse['village_name']=$villageAttack->name;
	$parse['x']=$villageAttack->x;
	$parse['y']=$villageAttack->y;
	if($villageDefend->kind_id<7){
		$title=$villageAttack->name." ".$lang['vien_tro']." ".$villageDefend->name;
		$parse['list_troop_title']=$lang['Reinforcement for']." "."<a href=\"village_map.php?a=$villageDefend->x&b=$villageDefend->y\">".$villageDefend->name."</a>";
	}else{
		$title=$villageAttack->name." ".$lang['vien_tro']." ".$lang['oasis']." ($villageDefend->x|$villageDefend->y)";
		$parse['list_troop_title']=$lang['Reinforcement for']." "."<a href=\"village_map.php?a=$villageDefend->x&b=$villageDefend->y\">".$lang['oasis']. " ($villageDefend->x|$villageDefend->y)"."</a>";
	}
	
	
	$content=parsetemplate(gettemplate("list_troop"), $parse);
	InsertReport($villageAttack->user_id, $title, $status->time_end, $content, REPORT_ATTACK);
	if($villageAttack->user_id != $villageDefend->user_id){
		InsertReport($villageDefend->user_id, $title, $status->time_end, $content, REPORT_ATTACK);
	}	
} 

/**
 * @author Le Van Tu
 * @des chuyen lang cho hero (giua cac lang cung user)
 * @param $village_id id lang hero toi
 * @param $heroId id cua hero
 * @return void
 */
function tuongChuyenLang($village_id, $ahr){
	global $db;
	
	$heroId = $ahr->id;
	
	//Kiem tra xem lang hero toi co tuong phu hay khong:
	$sql="SELECT COUNT(*) 
				FROM 
					wg_buildings
				WHERE
					wg_buildings.type_id =  '35' AND
					wg_buildings.vila_id =  '$village_id'";
	$db->setQuery($sql);
	$sumBuild=$db->loadResult();
	if($sumBuild>0){
		//co Tuong Phu:		
		//Cap nhat village_id moi cho hero:
		$sql="UPDATE wg_heros SET village_id=$village_id WHERE id=$heroId";
		$db->setQuery($sql);
		if(!$db->query()){
			globalError2("Loi doi id thanh cho hero:  id->$heroId, village_id->$village_id");
		}else{
			setAttackHeroStatus($ahr->attack_hero_id);
			return true;
		}
	}
	return false;
}

/**
 * @author Le Van Tu
 * @des set status trong bang wg_attack_hero
 */
function setAttackHeroStatus($id){
	global $db;
	$sql = "UPDATE wg_attack_hero SET wg_attack_hero.status=1 WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * Cap nhat linh ve lang
 */
function executeAttackReturn($status){
	global $db;
	$sql="SELECT
				wg_attack.village_attack_id,
				wg_attack.village_defend_id,
				wg_attack.`type`,
				wg_attack.`status`,
				wg_attack.id
			FROM
				wg_attack
			WHERE 
				wg_attack.id = $status->object_id";
	$db->setQuery($sql);
	$attack = null;
	$db->loadObject($attack);
	if($attack){
		if($attack->status==1){
			globalError2("Loi linh ve".$sql);
		}
		//cap nhat status=1 trong bang attack
		SetAttackStatus($attack->id);
		$listAttackTroop=GetListAttackTroop($attack->id);
		
		if($heroAttack=GetAttackHero($attack->id)){
			changeVillageOfHero($heroAttack->id, $attack->village_attack_id);			
			//deleteAttackHero($heroAttack->attack_hero_id);
			setAttackHeroStatus($heroAttack->attack_hero_id);
		}
		
		if(count($listAttackTroop)>0){
			foreach($listAttackTroop as $attackTroop){
				addTroopVilla($attack->village_attack_id, $attackTroop['id'], $attackTroop['sum']);
			}
			deleteAllAttackTroop($attack->id);
		}	
	}else{
		globalError2("Loi kong tim thay attack tuong ung voi status executeAttackReturn".$sql);
	}
}

function changeTroopKeep($village_id, $troop_id, $sum){
	global $db;
	$sql="SELECT keep_hour FROM wg_troops WHERE id=$troop_id";
	$db->setQuery($sql);
	$keep_hour=$sum*$db->loadResult();
	$sql="UPDATE wg_villages SET troop_keep=troop_keep+($keep_hour) WHERE id=$village_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Update troop_keep khong thanh cong village_id: $village_id , troop id: $troop_id");
	}
}

/**
 * @author Le Van Tu
 * @des tinh toan so linh chet moi ben trong mot pha danh
 * @param $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben truoc khi danh
 * @return $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben sau khi danh
 */
function attack($attack, $status){
	global $db, $lang, $game_config;
	include_once("function_plus.php");
	includeLang("attack");
	
	$villageAttack=getVillage($attack->village_attack_id, "`id`, `name`, `x`, `y`, `kind_id`, `user_id`, `rs1`, `rs2`, `rs3`, `rs4`, `cp`, `nation_id`, `child_id`, `krs1`, `krs2`, `krs3`, `krs4`");
	$villageDefend=getVillage($attack->village_defend_id);
	
	//update linh cho lang bi danh:
	updateTrainTroopStatus($villageDefend, strtotime($status->time_end));
	
	//cap nhat lai rs cho lang bi danh	
	updateRSOtherVillage($villageDefend,  strtotime($status->time_end));
	
	$arrayTroop=getArrayOfTroops();
	
	$attackTroopList=getAttackTroop($attack, $arrayTroop);
	$defendTroopList=getDefendTroop($attack, $arrayTroop);
	
	//echo "<pre>"; print_r($attackTroopList);die();
	
	$heroAttack=GetAttackHero($attack->id);
	$heroDefendList=getDefendHeroList($attack);
	
	//Tinh % thu cua tuong thanh:
	$wallLevel=GetBuildingLevel($attack->village_defend_id, 36);
	$wallDefend=$wallLevel>0?($wallLevel*0.05+1):1;
	
	//Kiem tra plus cua hai ben:
	$plusAtt=getAttDefPlus($villageAttack->user_id, $status->time_end);
	$plusDef=getAttDefPlus($villageDefend->user_id, $status->time_end);
	
	if($defendTroopList && $attackTroopList){
		$kata=0;
		phase($attackTroopList, $heroAttack, $defendTroopList, $heroDefendList, $wallDefend, $kata, $attack->type, $plusAtt, $plusDef);
	}

	$userDenfendOld=$villageDefend->user_id;
	if($chiemLang=kiemTraChiemLang($attackTroopList, $villageAttack, $villageDefend, $attack->type, strtotime($status->time_end))){
		switch($chiemLang){
			case 1:
				$arrInfo['chiem_lang']=$lang['da_chiem_duoc_lang'];
				$arrInfo['user_defend_id']=$userDenfendOld;
				break;
			case -1:
				//co dai dien -> long trung thanh khong bi giam
				$arrInfo['chiem_lang']=$lang['co_dai_dien'];
				break;
			case -2:
				//thanh nay la thu do
				$arrInfo['chiem_lang']=$lang['thanh_la_thu_do'];
				break;
			case -3:
				//khong du dien danh vong
				$arrInfo['chiem_lang']=$lang['khong_du_danh_vong'];
				break;
			default:
				$arrInfo['chiem_lang']=$lang['giam']." ".$chiemLang."% ".$lang['long_tin'];
				break;
		}
	}
	
	$speedBack=getSpeedTroopBack($heroAttack, $attackTroopList);	
	if($speedBack>0){
		//quan tan cong da thang tran:
		$s=S($villageAttack->x, $villageAttack->y, $villageDefend->x, $villageDefend->y);
		$duration=($s/$speedBack)*3600;
		
		//Kiem tra xem con dinh thu khong:
		$checkRare=(checkHaveMainBuilding($villageDefend->id)==0);		
		if($checkRare && $heroAttack->num==1 && $heroAttack->die_num==0 && $attack->type==4){
			$bauVat=occupyRare($villageAttack, $villageDefend, $attack->time_end, $duration);
			if($bauVat!=""){
				$arrInfo['bau_vat']=$bauVat;
			}			
		}
		
		//keo linh ve
		InsertStatusTroopAttackBack($attack->id, $attack->village_attack_id, date("Y-m-d H:i:s", strtotime($status->time_end)), date("Y-m-d H:i:s", strtotime($status->time_end)+$duration), $duration);
		
		$bounty=getBounty($attack, $attackTroopList, $villageDefend);
		if($bounty){			
			//Tru rs cua lang bi danh.
			$villageDefend->rs1 -= $bounty['rs1'];
			$villageDefend->rs2 -= $bounty['rs2'];
			$villageDefend->rs3 -= $bounty['rs3'];
			$villageDefend->rs4 -= $bounty['rs4'];
						
			//dua luong thuc ve.
			$objectID=InsertSendRS($attack->village_defend_id, $attack->village_attack_id, $bounty['rs1'], $bounty['rs2'], $bounty['rs3'], $bounty['rs4']);
			//Dua chien loi pham ve:
			InsertBountyStatus($attack->village_attack_id, $objectID, $status->time_end, date("Y-m-d H:i:s", strtotime($status->time_end)+$duration), $duration);
		
		}else{
			$bounty=array('rs1'=>0,'rs2'=>0,'rs3'=>0,'rs4'=>0);
		}
	}else{
		//bai tran:
		SetAttackStatus($attack->id);
		$bounty=array('rs1'=>0,'rs2'=>0,'rs3'=>0,'rs4'=>0);
	}
	
	
	//Tao report:	
	$arrInfo[0]=1;
	if(count($defendTroopList)<=0){
		$defendTroopList=array($attack->village_defend_id => 0);
	}
	// Cho xep hang ally va User attack	
	$arrEffectAVillage = executeUserAttackPoint($attackTroopList, $defendTroopList, $heroDefendList);
	$attackDiePoint = executeUserDefendPoint($attackTroopList, $heroAttack, $defendTroopList, $arrEffectAVillage);
	executeAllyEffect($attackTroopList, $defendTroopList, $attackDiePoint, $arrEffectAVillage[0]);

	writeAttackReport($attackTroopList, $heroAttack, $defendTroopList, $heroDefendList, $bounty, $arrInfo, $status->time_end);
	
	//cap nhat so linh chet xuong database:
	updateTroopAttack($attackTroopList, $villageAttack, $arrayTroop);	
	updateTroopDefend($defendTroopList, $villageDefend, $arrayTroop);
	
	updateHeroAttack($heroAttack, $villageAttack);
	updateHeroDefend($heroDefendList, $villageDefend);	
	
	//cap nhat cho lang danh:
	unsetVillageParam($villageAttack);
	$db->updateObject("wg_villages", $villageAttack, "id");
	
	//cap nhat cho lang bi danh:
	unsetVillageParam($villageDefend);
	$db->updateObject("wg_villages", $villageDefend, "id");
	
	//cap nhat troop_keep cho cac thanh:
	updateTroopKeep($villageAttack->id, getTroopKeep($villageAttack->id, $arrayTroop));
	foreach($defendTroopList as $v_id=>$t){
		updateTroopKeep($v_id, getTroopKeep($v_id, $arrayTroop));
	}
}


/**
 * @author Le Van Tu
 * @des tinh toan so linh chet moi ben va pha huy con trinh trong mot pha danh co kata di cung
 * @param $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben truoc khi danh
 * @return void
 */
function attackCatapult($attack, $status){
	global $db, $lang, $game_config;
	includelang("attack");
	//include_once("function_writeattackreport.php");
	include_once("function_plus.php");
	
	$checkRare=false;
	$arrInfo[0]=2;
	
	$villageAttack=getVillage($attack->village_attack_id, "`id`, `name`, `x`, `y`, `kind_id`, `user_id`, `rs1`, `rs2`, `rs3`, `rs4`, `cp`, `nation_id`, `child_id`, `krs1`, `krs2`, `krs3`, `krs4`");
	$villageDefend=getVillage($attack->village_defend_id);

	//update linh cho lang bi danh:
	updateTrainTroopStatus($villageDefend, strtotime($status->time_end));
	
	//cap nhat lai rs cho lang bi danh	
	updateRSOtherVillage($villageDefend,  strtotime($status->time_end));
	
	$arrayTroop=getArrayOfTroops();
	
	$attackTroopList=getAttackTroop($attack, $arrayTroop);
	$defendTroopList=getDefendTroop($attack, $arrayTroop);
	
	$heroAttack=GetAttackHero($attack->id);
	$heroDefendList=getDefendHeroList($attack);
	
	//Tinh % thu cua tuong thanh:
	$wallLevel=GetBuildingLevel($attack->village_defend_id, 36);
	$wallDefend=$wallLevel>0?($wallLevel*0.05+1):1;
	
	//Kiem tra plus cua hai ben:
	$plusAtt=getAttDefPlus($villageAttack->user_id, $status->time_end);
	$plusDef=getAttDefPlus($villageDefend->user_id, $status->time_end);
	
	$sumKata=0;
	phase($attackTroopList, $heroAttack, $defendTroopList, $heroDefendList, $wallDefend, $sumKata, $attack->type, $plusAtt, $plusDef);
	
	//Bat dau danh kata, tao report kata:
	$kataResult=kata($villageDefend, $attack, $sumKata);
	if($kataResult){
		$k=1;
		foreach($kataResult as $building){
			if($building['level_giam']>0){
				if($building['type_id']<=4){
					$arrInfo['cong_trinh_'.$k]=GetLangBuildingName($building['name'])." ".$lang['giam']." ".$building['level_giam']." ".$lang['level'];
				}else{
					if($building['level_giam']<$building['level']){
						$arrInfo['cong_trinh_'.$k]=GetLangBuildingName($building['name'])." ".$lang['giam']." ".$building['level_giam']." ".$lang['level'];	
					}else{
						//Cong trinh da bi pha huy
						$arrInfo['cong_trinh_'.$k]=GetLangBuildingName($building['name'])." ".$lang['level']." ".$building['level']." ".$lang['bi_pha_huy'];
						//kiem tra xem co phai nha chinh khong
						if($building['type_id']==12){
							$checkRare=true;
						}
						if($building['type_id']==37){
							updateRare($villageDefend->id, 1, 1, 1, 1, 1);
						}
					}						
				}
				$k++;
			}
		}		
	}else{
		if($sumKata>0){
			$arrInfo['cong_trinh_1'.$k]=$lang['khong_co_building'];
		}
	}	
	
	$userDenfendOld=$villageDefend->user_id;
	if($chiemLang=kiemTraChiemLang($attackTroopList, $villageAttack, $villageDefend, $attack->type, strtotime($status->time_end))){
		switch($chiemLang){
			case 1:
				$arrInfo['chiem_lang']=$lang['da_chiem_duoc_lang'];
				$arrInfo['user_defend_id']=$userDenfendOld;
				break;
			case -1:
				//co dai dien -> long trung thanh khong bi giam
				$arrInfo['chiem_lang']=$lang['co_dai_dien'];
				break;
			case -2:
				//thanh nay la thu do
				$arrInfo['chiem_lang']=$lang['thanh_la_thu_do'];
				break;
			case -3:
				//khong du dien danh vong
				$arrInfo['chiem_lang']=$lang['khong_du_danh_vong'];
				break;
			default:
				$arrInfo['chiem_lang']=$lang['giam']." ".$chiemLang."% ".$lang['long_tin'];
				break;
		}
	}
		
		
	$speedBack=getSpeedTroopBack($heroAttack, $attackTroopList);
	if($speedBack>0){
		//quan tan con da thang tran:
		$s=S($villageAttack->x, $villageAttack->y, $villageDefend->x, $villageDefend->y);
		$duration=intval(GetDuration($s, $speedBack));
		
		//Kiem tra xem con dinh thu khong:
		if(!$checkRare){
			$checkRare=(checkHaveMainBuilding($villageDefend->id)==0);
		}
		
		if($checkRare && $heroAttack->die_num==0 && $heroAttack->num==1){
			$bauVat=occupyRare($villageAttack, $villageDefend, $attack->time_end, $duration);
			if($bauVat!=""){
				$arrInfo['bau_vat']=$bauVat;
			}			
		}
		
		//keo linh ve
		InsertStatusTroopAttackBack($attack->id, $attack->village_attack_id, date("Y-m-d H:i:s", strtotime($status->time_end)), date("Y-m-d H:i:s", strtotime($status->time_end)+$duration), $duration);
		
		$bounty=getBounty($attack, $attackTroopList, $villageDefend);
		if($bounty){
			//Tru rs cua lang bi danh.
			$villageDefend->rs1 -= $bounty['rs1'];
			$villageDefend->rs2 -= $bounty['rs2'];
			$villageDefend->rs3 -= $bounty['rs3'];
			$villageDefend->rs4 -= $bounty['rs4'];
			
			//dua luong thuc ve.
			$objectID=InsertSendRS($attack->village_defend_id, $attack->village_attack_id, $bounty['rs1'], $bounty['rs2'], $bounty['rs3'], $bounty['rs4']);
			//Dua chien loi pham ve:
			InsertBountyStatus($attack->village_attack_id, $objectID, $status->time_end, date("Y-m-d H:i:s", strtotime($status->time_end)+$duration), $duration);
		
		}else{
			$bounty=array('rs1'=>0,'rs2'=>0,'rs3'=>0,'rs4'=>0);
		}
	}else{
		//bai tran:
		SetAttackStatus($attack->id);
		$bounty=array('rs1'=>0,'rs2'=>0,'rs3'=>0,'rs4'=>0);
	}
	
	
	//Tao report:
	if(count($defendTroopList)<=0){
		$defendTroopList=array($attack->village_defend_id => 0);
	}	
	// Cho xep hang ally va User attack	
	$arrEffectAVillage = executeUserAttackPoint($attackTroopList, $defendTroopList, $heroDefendList);
	$attackDiePoint = executeUserDefendPoint($attackTroopList, $heroAttack, $defendTroopList, $arrEffectAVillage);
	executeAllyEffect($attackTroopList, $defendTroopList, $attackDiePoint, $arrEffectAVillage[0]);
	
	writeAttackReport($attackTroopList, $heroAttack, $defendTroopList, $heroDefendList, $bounty, $arrInfo, $status->time_end);
	
	//cap nhat so linh chet xuong database:
	updateTroopAttack($attackTroopList, $villageAttack, $arrayTroop);
	updateTroopDefend($defendTroopList, $villageDefend, $arrayTroop);
		
	updateHeroAttack($heroAttack, $villageAttack);
	updateHeroDefend($heroDefendList, $villageDefend);
	
	//cap nhat cho lang danh:
	$db->updateObject("wg_villages", $villageAttack, "id");
	
	//cap nhat cho lang bi danh:
	returnWorkersLogin($villageDefend->user_id); // bao gom tat ca krs,worker,level.....
	//unsetVillageParam($villageDefend);
	//$db->updateObject("wg_villages", $villageDefend, "id");
	
	//cap nhat troop_keep cho cac thanh:
	updateTroopKeep($villageAttack->id, getTroopKeep($villageAttack->id, $arrayTroop));
	foreach($defendTroopList as $v_id=>$t){
		updateTroopKeep($v_id, getTroopKeep($v_id, $arrayTroop));
	}
}

/**
 * @author Le Van Tu
 * @des Kiem tra mot lang con nha chinh hay khong
 * @param id village
 * @return bool
 */
function checkHaveMainBuilding($village_id){
	global $db;
	$sql="SELECT COUNT(*) FROM wg_buildings WHERE type_id=12 AND level>0 AND vila_id=$village_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @des xoa nhung linh dang duoc tao khi nha bi pha
 * @param $type type cua statsus
 * @param $village_id id cua lang
 * @return void
 */
function deleteTrainTroop($village_id, $type) {
	global $db;
	$sql="SELECT
				wg_status.id,
				wg_status.object_id
			FROM
				wg_status 
			WHERE
				wg_status.`status` =  '0' AND
				wg_status.village_id =  '$village_id' AND 
				wg_status.`type` =  '$type'";
	$db->setQuery($sql);
	$statuss=$db->loadObjectList();
	if($statuss){
		//set status=1 cho tat ca nhung status nay
		$sql="UPDATE wg_status SET
					wg_status.`status` =  '1'
				WHERE
					wg_status.`status` =  '0' AND
					wg_status.village_id =  '$village_id' AND 
					wg_status.`type` =  '$type'";
		$db->setQuery($sql);
		$db->query();
		
		//xoa cac recode trong bang troop_train
		foreach($statuss as $status){
			DeleteTroopTrain($status->object_id);
		}
	}
}

/**
 * @author Le Van Tu
 * @des tinh toan so linh chet moi ben va lay thong tin do tham
 * @param $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben truoc khi danh
 * @return void
 */
function attackSpy($attack, $status){
	//include_once("function_writeattackreport.php");
	include_once("function_plus.php");
	global $db,$lang, $game_config;	
	includeLang("attack");
	$parse=$lang;
	
	$arrInfo[0]=3;
	
	$villageAttack=getVillage($attack->village_attack_id, "`id`, `name`, `x`, `y`, `kind_id`, `user_id`, `rs1`, `rs2`, `rs3`, `rs4`, `nation_id`, `child_id`, `krs1`, `krs2`, `krs3`, `krs4`");
	$villageDefend=getVillage($attack->village_defend_id);

	//update linh cho lang bi danh:
	updateTrainTroopStatus($villageDefend, strtotime($status->time_end));
	
	//cap nhat lai rs cho lang bi danh	
	updateRSOtherVillage($villageDefend,  strtotime($status->time_end));
	
	$arrayTroop=getArrayOfTroops();
	
	$attackTroopList=getAttackTroop($attack, $arrayTroop);
	$defendTroopList=getDefendTroop($attack, $arrayTroop);
	
	$heroAttack=GetAttackHero($attack->id);
	$heroDefendList=getDefendHeroList($attack);
	
	//Kiem tra plus cua hai ben:
	$plusAtt=getAttDefPlus($villageAttack->user_id, $status->time_end);
	$plusDef=getAttDefPlus($villageDefend->user_id, $status->time_end);
	
	if($defendTroopList){
		$kata=0;
		$h1=null;
		$h2=array();
		phase($attackTroopList, $h1, $defendTroopList, $h2, 1, $kata, $attack->type, $plusAtt, $plusDef);
	}

	$speedBack=getSpeedTroopBack($heroAttack, $attackTroopList);
	
	if($speedBack>0){
		//quan tan con da thang tran:
		$s=S($villageAttack->x, $villageAttack->y, $villageDefend->x, $villageDefend->y);
		$duration=intval(GetDuration($s, $speedBack));
		//keo linh ve
		InsertStatusTroopAttackBack($attack->id, $attack->village_attack_id, date("Y-m-d H:i:s", strtotime($status->time_end)), date("Y-m-d H:i:s", strtotime($status->time_end)+$duration), $duration);	
		
		switch($attack->type){
			case 7:
				//Lay thong tin RS cua lang bi do tham:
				$arrInfo['resource']="<img class=\"res\" src=\"images/un/r/1.gif\">$villageDefend->rs1 <img class=\"res\" src=\"images/un/r/2.gif\">$villageDefend->rs2 <img class=\"res\" src=\"images/un/r/3.gif\">$villageDefend->rs3  <img class=\"res\" src=\"images/un/r/4.gif\">$villageDefend->rs4</td>";				
				break;
			case 8:
				//lay thong tin building:
				$wallLevel=GetBuildingLevel($attack->village_defend_id, 36);
				if($wallLevel>0){
					$arrInfo['cong_trinh_1']=$lang['Earth Wall Level']." ".$wallLevel;
				}else{
					$arrInfo['cong_trinh_1']=$lang['chua_co_tuong_thanh'];
				}
				
				$residenceLevel=GetBuildingLevel($attack->village_defend_id, 18);
				if($residenceLevel>0){
					$arrInfo['cong_trinh_2']=$lang['Residence Level']." ".$residenceLevel;
				}else{
					$arrInfo['cong_trinh_2']=$lang['chua_co_dai_dien'];
				}
				break;
		}	
	}else{
		//bai tran:
		SetAttackStatus($attack->id);
	}
	
	//Tao report:
	if(count($defendTroopList)<=0){
		$defendTroopList=array($attack->village_defend_id => 0);
	}	
	
	// Cho xep hang ally va User attack	
	$arrEffectAVillage = executeUserAttackPoint($attackTroopList, $defendTroopList, $heroDefendList);
	$attackDiePoint = executeUserDefendPoint($attackTroopList, $heroAttack, $defendTroopList, $arrEffectAVillage);
	executeAllyEffect($attackTroopList, $defendTroopList, $attackDiePoint, $arrEffectAVillage[0]);
	
	writeAttackReport($attackTroopList, $heroAttack, $defendTroopList, $heroDefendList, $bounty, $arrInfo, $status->time_end);
	
	//cap nhat so linh chet xuong database:
	updateTroopAttack($attackTroopList, $villageAttack, $arrayTroop);
	updateTroopDefend($defendTroopList, $villageDefend, $arrayTroop);
	
	//cap nhat cho lang danh:
	$db->updateObject("wg_villages", $villageAttack, "id");
	
	//cap nhat cho lang bi danh:
	unsetVillageParam($villageDefend);	
	$db->updateObject("wg_villages", $villageDefend, "id");
}



/**
 * Tinh toan cap nhat cong trinh khi danh kata
 */
function kata(&$villageDefend ,$attack, $kata)
{
	$i=1;
	include_once("func_build.php");
	$hpPerLevel=100;
	$buildingArray=getKataBuilding($attack->village_attack_id, $attack->village_defend_id, $attack->building_type_id);
	if($buildingArray)
	{
		foreach($buildingArray as $building)
		{
			if($building->level>0)
			{
				$arrayBuildingID[$i]=$building->id;
				$result[$i]['id']=$building->id;
				$result[$i]['name']=$building->name;
				$result[$i]['type_id']=$building->type_id;
				$result[$i]['level']=$building->level;
				$result[$i]['level_giam']=0;
				while($kata>0 && $building->level>0)
				{
					$hp=$building->level*$hpPerLevel;
					if($kata>$hp)
					{
						//Cap nhat worker:
						destroyByCata($building->id, $building->type_id, $building->level, $attack->village_defend_id);
						$building->level--;
						$result[$i]['level_giam']++;					
						$kata-=$hp;					
					}
					else
					{
						break;
					}
				}
				if($result[$i]['level_giam']>0)
				{
					$i++;
				}
			}		
		}		
	}
	
		
	$rallyLevel=getBuildingLevel($attack->village_attack_id, 27);	
	if($rallyLevel>=20){
		$maxBuild=2;
	}else{
		$maxBuild=1;
	}
	$j=$i;
	while($kata>50 && $i<=$maxBuild && $j<=40){
		$building=getKataBuildingRandom($attack->village_defend_id, $rallyLevel, $arrayBuildingID);		
		if($building->level>0){
			$arrayBuildingID[$j]=$building->id;
			$result[$i]['id']=$building->id;
			$result[$i]['name']=$building->name;
			$result[$i]['type_id']=$building->type_id;
			$result[$i]['level']=$building->level;
			$result[$i]['level_giam']=0;
			while($kata>0 && $building->level>0){
				$hp=$building->level*$hpPerLevel;
				if($kata>$hp){
					//Cap nhat worker:
					destroyByCata($building->id, $building->type_id, $building->level, $attack->village_defend_id);
					$building->level--;
					$result[$i]['level_giam']++;
					$kata-=$hp;
				}else{
					break;
				}
			}			
			if($result[$i]['level_giam']>0){
				$i++;
			}			
		}
		$j++;	//Tim 40 lan ko co cong trinh nao co level>0 thi thoat
	}
	if($i>1){
		return $result;
	}else{
		return false;
	}	
}

/**
 * lay level cua building ma kata se danh:
 */
function getKataBuilding($village_attack_id, $village_id, $str){
	global $db;
	$ids=explode("|", $str);
	//Truong hop binh thuong:
	$sql="SELECT id, name, level, type_id 
		FROM wg_buildings 
		WHERE vila_id=$village_id 
			AND type_id!=0 
			AND (type_id=".$ids[0]." OR type_id=".$ids[1].")  
			AND level>0 
		GROUP BY type_id";
	$db->setQuery($sql);
	$buildingList=$db->loadObjectList();
	if($buildingList){
		return $buildingList;
	}else{
		//lay random		
		$rallyLevel=getBuildingLevel($village_attack_id, 27);
		$result[0]=getKataBuildingRandom($village_id, $rallyLevel);
		return $result;
	}	
}

/**
 * lay ngau nhien building ma kata se danh:
 * $arrayBuilding luu nhung building da danh
 */
function getKataBuildingRandom($village_id, $rallyLevel, $arrayBuildingID=null){
	global $db;
	$sql="SELECT id, name, level, type_id FROM wg_buildings 
				WHERE vila_id=$village_id 
					AND type_id!=0 
					AND level>0 ";
	if($arrayBuildingID){
		foreach($arrayBuildingID as $id){
			$sql.="AND id!=$id ";
		}		
	}
	if($rallyLevel<5){
		$sql.="AND (type_id=1 OR type_id=2 OR type_id=3 OR type_id=4) ";
	}else{
		if($rallyLevel<10){
			$sql.="AND (type_id=1 OR type_id=2 OR type_id=3 OR type_id=4 OR type_id=10 OR type_id=11) ";
		}
	}
	$sql.="GROUP BY type_id";
	$db->setQuery($sql);
	$buildingList=$db->loadObjectList();
	if($buildingList){
		$index=rand(0, count($buildingList));
		$result[0]=$buildingList[$index];
		return $buildingList[$index];
	}else{
		return false;
	}
}

/**
 * @author Le Van Tu
 * @des cap nhat linh ben tan cong sau khi danh
 * @param $attackTroops danh sach linh ben tan cong sau khi danh
 * @return void
 */
function updateTroopAttack($attackTroopList, &$villageAttack, $arrayTroop){
	if($attackTroopList){
		foreach($attackTroopList as $attackTroops){
			foreach($attackTroops as $troopId=>$attackTroop){
				//echo "<pre>"; print_r($attackTroop);
				if($attackTroop['die_num']>0){
	
					$tk = $arrayTroop[$troopId]['keep_hour']*$attackTroop['die_num'];
					//echo $tk;
					ChangeTroopKeepVillage($villageAttack->id, -$tk);
					
					//cap nhat so linh chet.
					changeAttackTroop($attackTroop['attack_troop_id'], -$attackTroop['die_num']);
				}					
			}				
		}
	}
}

/**
 * @author Le Van Tu
 * @des cap nhat hero ben tan cong sau khi danh
 * @param $heroAttack hero ben tan cong sau khi danh
 * @return void
 */
function updateHeroAttack($heroAttack, &$villageAttack){
	global $db;
	if($heroAttack){
		
		
		if($heroAttack->die_num>0){
			//deleteAttackHero($heroAttack->attack_hero_id);
			setAttackHeroStatus($heroAttack->attack_hero_id);
			
			ChangeTroopKeepVillage($villageAttack->id, -$heroAttack->keep_hour);
			$heroAttack->status=-1;
		}
		unset($heroAttack->attack);
		unset($heroAttack->melee_defense);
		unset($heroAttack->ranger_defense);
		unset($heroAttack->magic_defense);
			
		unset($heroAttack->attack_hero_id);
		unset($heroAttack->num);
		unset($heroAttack->die_num);
		
		unset($heroAttack->tuong_sinh_cong);
		unset($heroAttack->tuong_khac_cong);
		unset($heroAttack->tuong_sinh_thu);
		unset($heroAttack->tuong_khac_thu);
		unset($heroAttack->speed);
				
		if(!$db->updateObject("wg_heros", $heroAttack, "id")){
			globalError2("Loi update hero sau khi danh. hero id: $heroAttack->id");
		}
	}		
}

/**
 * @author Le Van Tu
 * @des cap nhat hero ben phong thu sau khi danh
 * @return void
 */
function updateHeroDefend($heroDefendList, &$villageDefend){
	global $db;
	if($heroDefendList){
		foreach($heroDefendList as $vid=>$heroDefend){
			if($heroDefend->die_num>0){
				if($villageDefend->id != $vid){
					//deleteAttackHero($heroDefend->attack_hero_id);
					setAttackHeroStatus($heroDefend->attack_hero_id);
				}				
				
				ChangeTroopKeepVillage($villageDefend->id, -$heroDefend->keep_hour);
				
				$heroDefend->status=-1;				
			}
			
			unset($heroDefend->attack);
			unset($heroDefend->melee_defense);
			unset($heroDefend->ranger_defense);
			unset($heroDefend->magic_defense);
			
			unset($heroDefend->attack_hero_id);
			unset($heroDefend->num);
			unset($heroDefend->die_num);
			
			unset($heroDefend->tuong_sinh_cong);
			unset($heroDefend->tuong_khac_cong);
			unset($heroDefend->tuong_sinh_thu);
			unset($heroDefend->tuong_khac_thu);
			
			unset($heroDefend->attack_hero_id);
			unset($heroDefend->village_attack_id);
			unset($heroDefend->speed);
			
			if(!$db->updateObject("wg_heros", $heroDefend, "id")){
				globalError2("Loi update hero sau khi danh. $heroDefend->id");
			}
		}
	}	
}

/**
 * @author Le Van Tu
 * @des cap nhat hero vien tro bo lac khi co lang toi danh
 * @param $heroDefendAttack hero ben phong thu sau khi danh
 * @return void
 */
function updateHeroReinforceOasis($heroDefendList){
	global $db;
	if($heroDefendList){
		foreach($heroDefendList as $vid=>$heroDefend){
			if($heroDefend->die_num>0){
				ChangeTroopKeepVillage($vid, $heroDefend->keep_hour);
				//deleteAttackHero($heroDefend->attack_hero_id);
				setAttackHeroStatus($heroDefend->attack_hero_id);
				
				$heroDefend->status=-1;				
			}			
			
			unset($heroDefend->attack_hero_id);
			unset($heroDefend->num);
			unset($heroDefend->die_num);
			
			unset($heroDefend->tuong_sinh_cong);
			unset($heroDefend->tuong_khac_cong);
			unset($heroDefend->tuong_sinh_thu);
			unset($heroDefend->tuong_khac_thu);
			
			unset($heroDefend->attack_hero_id);
			unset($heroDefend->village_attack_id);
			unset($heroDefend->speed);
			
			if(!$db->updateObject("wg_heros", $heroDefend, "id")){
				globalError2("Loi update hero sau khi danh. $heroDefend->id");
			}
		}
	}
	
}

/**
 * @author Le Van Tu
 * @des Xoa mot record trong bang wg_attack_hero
 * @param wg_attack_hero id
 */
function deleteAttackHero($id){
	global $db;
	$sql="DELETE FROM wg_attack_hero WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi Xoa record trong bang wg_attack_hero: id=$id");
	}
}

/**
 * @author Le Van Tu
 * @des Xoa cac record trong bang wg_attack_troop
 * @param attack_id id of wg_attack
 */
function deleteAllAttackTroop($attack_id){
	global $db;
	$sql="UPDATE wg_attack_troop SET wg_attack_troop.status=1 WHERE attack_id=$attack_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi Xoa record trong bang wg_attack_troop: attack_id=$attack_id");
	}
}

/**
 * @author Le Van Tu
 * @des Xoa 1 record trong bang wg_attack_troop
 * @param wg_attack_troop.id id of wg_attack_troop
 */
function deleteAttackTroop($id){
	global $db;
	$sql="UPDATE wg_attack_troop SET wg_attack_troop.status=1 WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi Xoa record trong bang wg_attack_troop: wg_attack_troop.id=$id");
	}
}

/**
 * @author Le Van Tu
 * @des cap nhat linh ben phong thu sau khi danh
 * @param $defendTroops danh sach linh ben phong thu sau khi danh
 * @return void
 */
function updateTroopDefend($defendTroopList, &$villageDefend, $arrayTroop){
	if($defendTroopList){
		foreach($defendTroopList as $vdid=>$defendTroops){
			foreach($defendTroops as $troop_id=>$defendTroop){
				if($defendTroop['die_num']>0){
					//thay doi troop keep cua lang:
					
					ChangeTroopKeepVillage($villageDefend->id, -$arrayTroop[$troop_id]['keep_hour']*$defendTroop['die_num']);
					
					//neu la cua lang bi tan cong.
					if($villageDefend->id==$vdid){
						changeTroopVillage($villageDefend->id, $troop_id, -$defendTroop['die_num']);
					}else{
						//lang ho tro.
						changeAttackTroop($defendTroop['attack_troop_id'], -$defendTroop['die_num']);
					}
				}
			}
		}		
	}
}

/**
 * @author Le Van Tu
 * @des cap nhat linh ben phong thu sau khi danh (truong hop danh bo lac)
 * @param $defendTroops danh sach linh ben phong thu sau khi danh
 * @return void
 */
function updateTroopReinforceOasis($reinforceTroopList, $arrayTroop){
	if($reinforceTroopList){
		foreach($reinforceTroopList as $vid=>$reinforceTroops){
			foreach($reinforceTroops as $troop_id=>$reinforceTroop){
				if($reinforceTroop['die_num']>0){
					changeAttackTroop($reinforceTroop['attack_troop_id'], -$reinforceTroop['die_num']);
					ChangeTroopKeepVillage($vid, -$arrayTroop[$troop_id]['keep_hour']*$reinforceTroop['die_num']);
				}
			}
		}
	}
}



/**
 * @author Le Van Tu
 * @des Tinh toan chien loi pham mang ve:
 * @param $attackTrooopList danh sach linh ben tan cong sau khi danh
 * @return void
 */
function getSpeedTroopBack($heroAttack, $attackTroopList){
	if($attackTroopList){
		foreach($attackTroopList as $attackTroops){
			foreach($attackTroops as $attackTroop){
				if($attackTroop['num']-$attackTroop['die_num']>0){
					$temp[]=$attackTroop['speed'];
				}
			}
		}
	}		
	
	if($heroAttack && $heroAttack->die_num==0){
		$temp[]=$heroAttack->speed;
	}
	
	if($temp){
		return min($temp);
	}else{
		return 0;
	}
}

/**
 * @author Le Van Tu
 * @des Tinh toan chien loi pham mang ve:
 * @param $attackTrooopList danh sach linh ben tan cong sau khi danh
 * @param $attack thong tin tran danh
 * @return void
 */
function getBounty($attack, $attackTroopList, $villageDefend){
	$sumCarry=0;
	foreach($attackTroopList as $attackTroops){
		foreach($attackTroops as $attackTroop){
			$sumCarry+=($attackTroop['num']-$attackTroop['die_num'])*$attackTroop['carry'];
		}
	}
	
	if($sumCarry>0){
		//Tinh suc chua cua mat that.
		$rsHide=GetHideRS($attack->village_defend_id);
		
		//tai nguyen toi da co the lay moi loai:
		$rs1Max=($villageDefend->rs1-$rsHide)>0?($villageDefend->rs1-$rsHide):0;
		$rs2Max=($villageDefend->rs2-$rsHide)>0?($villageDefend->rs2-$rsHide):0;
		$rs3Max=($villageDefend->rs3-$rsHide)>0?($villageDefend->rs3-$rsHide):0;
		$rs4Max=($villageDefend->rs4-$rsHide)>0?($villageDefend->rs4-$rsHide):0;
		
		$sumMax=$rs1Max+$rs2Max+$rs3Max+$rs4Max;
		$sumBounty=$sumCarry<$sumMax?$sumCarry:$sumMax;
		
		if(($rs1Max+$rs2Max+$rs3Max+$rs4Max)>0){
			$result['rs1']=round($sumBounty * ($rs1Max/$sumMax), 0);
			$result['rs2']=round($sumBounty * ($rs2Max/$sumMax), 0);
			$result['rs3']=round($sumBounty * ($rs3Max/$sumMax), 0);
			$result['rs4']=round($sumBounty * ($rs4Max/$sumMax), 0);			
		}else{
			$result['rs1']=0;
			$result['rs2']=0;
			$result['rs3']=0;
			$result['rs4']=0;
		}	
	}	
	return $result;
}

/**
 * @author Le Van Tu
 * @des tinh toan so linh chet moi ben trong mot pha danh
 * @param $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben truoc khi danh
 * @return $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben sau khi danh
 */
function phase(&$troopAttackList, &$heroAttack, &$troopDefendList, &$heroDefendList, $wallDefend=1, &$kata=0, $type=0, $plusAtt=0, $plusDef=0){
	$sumAttackTroop=0;	//Tong so linh attack.
	$sumDefendTroop=0;	//Tong so linh defend.
	
	$sumKeepHourAttack=0;
	$sumKeepHourDefend=0;
	
	$sumKeepHourAttackDie=0;
	$sumKeepHourDefendDie=0;
	
	//Tinh tong suc tan cong.
	$sumAttackMelee=0;	//Tong suc tan cong cua quan bo.
	$sumAttackMagic=0;	//Tong suc tan cong cua quan ngua.
	$sumAttackRanger=0;	//Tong suc tan cong cua quan cung.
	$sumAttackHP=0;
	$maxAttackHP=0;
	$tuong_sinh_cong=0;

	$sumAttackOld=0; 		//chi so attack chua co bonus
	$sumDefendOld=0;		//chi so phong thu chua co bonus
	
	foreach($troopAttackList as $villageAttackId=>$troopAttacks){
		foreach($troopAttacks as $troopId=>$troopAttack){
			switch($troopAttack['type']){
				case 1:
					//bo					
					//Tinh tong suc cong cua kata neu co:
					if($troopAttack['id']==9 || $troopAttack['id']==20 || $troopAttack['id']==31){					
						$kata+=$troopAttack['attack']*($troopAttack['num']-$troopAttack['die_num']);
						$sumAttackMelee += $troopAttack['attack']*($troopAttack['num']-$troopAttack['die_num'])/3;
					}else{
						$sumAttackMelee += $troopAttack['attack']*($troopAttack['num']-$troopAttack['die_num']);
					}
					$sumAttackTroop+=$troopAttack['num']-$troopAttack['die_num'];
					$sumKeepHourAttack+=$troopAttack['keep_hour']*($troopAttack['num']-$troopAttack['die_num']);
					break;
				case 2:
					//Ngua
					$sumAttackMagic += $troopAttack['attack']*($troopAttack['num']-$troopAttack['die_num']);
					$sumAttackTroop+=$troopAttack['num']-$troopAttack['die_num'];
					$sumKeepHourAttack+=$troopAttack['keep_hour']*($troopAttack['num']-$troopAttack['die_num']);
					break;
				case 3:
					//cung
					$sumAttackRanger += $troopAttack['attack']*($troopAttack['num']-$troopAttack['die_num']);
					$sumAttackTroop+=$troopAttack['num']-$troopAttack['die_num'];
					$sumKeepHourAttack+=$troopAttack['keep_hour']*($troopAttack['num']-$troopAttack['die_num']);
					break;
			}
		}			
	}
	
	$sumAttackOld = $sumAttackMelee+$sumAttackMagic+$sumAttackRanger;
	
	//neu co hero:
	if($heroAttack){
		if($heroAttack->num > $heroAttack->die_num){
			
			$tuong_sinh_cong = $heroAttack->tuong_sinh_cong;			
			$sumAttackMelee 	*= (1+$tuong_sinh_cong);
			$sumAttackMagic 	*= (1+$tuong_sinh_cong);
			$sumAttackRanger 	*= (1+$tuong_sinh_cong);			
			
			switch($heroAttack->type){
					case 1:
						$sumAttackMelee += $heroAttack->attack;
						break;
					case 2:
						$sumAttackMagic += $heroAttack->attack;
						break;
					case 3:
						$sumAttackRanger += $heroAttack->attack;
						break;
			}
		}		
	}
	
	//Tong cong.	
	$sumAttack = $sumAttackMelee + $sumAttackMagic + $sumAttackRanger;
	
	//Kiem tra xem co plus hay khong:
	if($plusAtt==1){
		$sumAttack*=1.1;
	}
	
	if($sumAttack<=0){
		return false;
	}

//-------------------tinh tong phong th?----------------->	
	if($troopDefendList && $sumAttack>0){		
		$sumDefendMelee_Melee=0;
		$sumDefendMelee_Ranger=0;//Tong suc phong thu cua quan bo doi voi cung.
		$sumDefendMelee_Magic=0;
		
		$sumDefendMagic_Melee=0;//Tong suc phong thu cua quan ngua doi voi bo.
		$sumDefendMagic_Ranger=0;
		$sumDefendMagic_Magic=0;
		
		$sumDefendRanger_Melee=0;
		$sumDefendRanger_Ranger=0;
		$sumDefendRanger_Magic=0;//Tong suc phong thu cua quan cung do voi ngua.
		
		$hero_melee_defense=0;
		$hero_magic_defense=0;
		$hero_ranger_defense=0;
		
		$tuong_sinh_thu=0;
		$soTuongThu=0;
		
		
		if($type==7 || $type==8){
			//truong hop do tham thi chi co linh do tham moi danh duoc linh do tham:
			foreach($troopDefendList as $troopDefends){
				foreach($troopDefends as $troopId=>$troopDefend){
					if($troopId==8 || $troopId==19 || $troopId==30){
						//Tong suc phong thu cua ngua doi voi bo:
						$sumDefendMagic_Melee += $troopDefend['melee_defense']*($troopDefend['num']-$troopDefend['die_num']);
						//Tong suc phong thu cua ngua doi voi cung:
						$sumDefendMagic_Ranger += $troopDefend['ranger_defense']*($troopDefend['num']-$troopDefend['die_num']);
						//Tong suc phong thu cua ngua doi voi ngua:
						$sumDefendMagic_Magic += $troopDefend['magic_defense']*($troopDefend['num']-$troopDefend['die_num']);
						$sumDefendTroop+=$troopDefend['num']-$troopDefend['die_num'];
					}
				}
			}
		}else{
			foreach($troopDefendList as $troopDefends){
				foreach($troopDefends as $troopId=>$troopDefend){
					switch($troopDefend['type']){
						case 1:
							//bo
							//suc thu cua bo doi voi cung:
							$sumDefendMelee_Ranger += $troopDefend['ranger_defense']*($troopDefend['num']-$troopDefend['die_num']);
							//suc thu cua bo doi voi ngua:
							$sumDefendMelee_Magic += $troopDefend['magic_defense']*($troopDefend['num']-$troopDefend['die_num']);
							//suc thu cua bo doi voi bo
							$sumDefendMelee_Melee += $troopDefend['melee_defense']*($troopDefend['num']-$troopDefend['die_num']);
							$sumDefendTroop+=$troopDefend['num']-$troopDefend['die_num'];
							$sumKeepHourDefend+=$troopDefend['keep_hour']*($troopDefend['num']-$troopDefend['die_num']);
							break;
						case 2:
							//Ngua
							//Tong suc phong thu cua ngua doi voi bo:
							$sumDefendMagic_Melee += $troopDefend['melee_defense']*($troopDefend['num']-$troopDefend['die_num']);
							//Tong suc phong thu cua ngua doi voi cung:
							$sumDefendMagic_Ranger += $troopDefend['ranger_defense']*($troopDefend['num']-$troopDefend['die_num']);
							//Tong suc phong thu cua ngua doi voi ngua:
							$sumDefendMagic_Magic += $troopDefend['magic_defense']*($troopDefend['num']-$troopDefend['die_num']);
							$sumDefendTroop+=$troopDefend['num']-$troopDefend['die_num'];
							
							$sumKeepHourDefend+=$troopDefend['keep_hour']*($troopDefend['num']-$troopDefend['die_num']);
							break;
						case 3:
							//Cung
							//T?ng s?c phng th? c?a cung d?i v?i ng?a
							$sumDefendRanger_Magic += $troopDefend['magic_defense']*($troopDefend['num']-$troopDefend['die_num']);
							//T?ng s?c phng th? c?a cung d?i v?i b?
							$sumDefendRanger_Melee += $troopDefend['melee_defense']*($troopDefend['num']-$troopDefend['die_num']);
							//T?ng s?c phng th? c?a cung d?i v?i cung
							$sumDefendRanger_Ranger += $troopDefend['ranger_defense']*($troopDefend['num']-$troopDefend['die_num']);
							$sumDefendTroop+=$troopDefend['num']-$troopDefend['die_num'];
							
							$sumKeepHourDefend+=$troopDefend['keep_hour']*($troopDefend['num']-$troopDefend['die_num']);
							break;
					}
				}					
			}
		}				
	}
	
	//Neu co hero:
	if($heroDefendList){
		foreach($heroDefendList as $heroDefend){
			if($heroDefend->num>$heroDefend->die_num){
				$hero_melee_defense		+= $heroDefend->melee_defense;
				$hero_magic_defense		+= $heroDefend->magic_defense;
				$hero_ranger_defense	+= $heroDefend->ranger_defense;				
				$tuong_sinh_thu			+= $heroDefend->tuong_sinh_thu;
				$soTuongThu++;
			}
		}
	}	
	
	//Tong thu:
	$sumDefend_Melee = (($sumDefendMelee_Melee+$sumDefendMagic_Melee+$sumDefendRanger_Melee)*(1+$tuong_sinh_thu)) +$hero_melee_defense;
	$sumDefend_Magic = (($sumDefendMelee_Magic+$sumDefendMagic_Magic+$sumDefendRanger_Magic)*(1+$tuong_sinh_thu))+$hero_magic_defense;
	$sumDefend_Ranger = (($sumDefendMelee_Ranger+$sumDefendMagic_Ranger+$sumDefendRanger_Ranger)*(1+$tuong_sinh_thu))+$hero_ranger_defense;
	
	//Tong thu doi voi bo = % cua b? bn cng * t?ng th? d?i v?i b?
	$phanTramCongBo=($sumAttackMelee/$sumAttack);
	$phanTramCongNgua=($sumAttackMagic/$sumAttack);
	$phanTramCongCung=($sumAttackRanger/$sumAttack);	
	
	$sumDefend_Melee= $phanTramCongBo*$sumDefend_Melee;
	//T?ng s?c phng th? d?i v?i ng?a:
	$sumDefend_Magic= $phanTramCongNgua*$sumDefend_Magic;
	//T?ng s?c phng th? d?i v?i cung:
	$sumDefend_Ranger= $phanTramCongCung*$sumDefend_Ranger;
	
	//T?ng th?:
	$sumDefend = $sumDefend_Melee + $sumDefend_Magic + $sumDefend_Ranger;
		
	//Tinh % thu cua tuong thanh:
	$sumDefend = $sumDefend*$wallDefend;
	
	//Kiem tra xem co plus hay khong:
	if($plusDef==2){
		$sumDefend*=1.1;
	}
	
	if($sumDefend>0){
		$hesoKata=($sumAttack/$sumDefend)>1?1:($sumAttack/$sumDefend);
		$kata=$kata*$hesoKata;
	}
	 
	//Tinh so linh bi chet moi ben:
	if($sumAttack>0 && $sumDefend>0){
		//Ap dung cong thuc cua travian:
		calculateCasualtie($sumAttack, $sumDefend, $sumAttackTroop, $sumDefendTroop, $attackDie, $defendDie, $type);
		
		//Cong thu cu:
		/*
		if($sumAttack>$sumDefend){
			$flag=1;
			$y=$sumDefend/$sumAttack;
			$sumWinTroop=$sumAttackTroop;
			$sumLoseTroop=$sumDefendTroop;
		}else{
			$flag=2;
			$y=$sumAttack/$sumDefend;
			$sumWinTroop=$sumDefendTroop;
			$sumLoseTroop=$sumAttackTroop;
		}
		
		
		
		if($type==3 || $type==10){
			//Truong hop dot kich:
			
			if($y<0.03){
				$x=round($sumLoseTroop*(1-$y)/2, 0);
			}else{
				if($y>0.5){
					$x=round(($sumWinTroop+$sumLoseTroop)/2, 0);
				}else{
					$x=round(($sumWinTroop+$sumLoseTroop)*$y, 0);
				}
			}
			
			
			$z = (($sumDefend*$sumDefendTroop)/($sumAttack*$sumAttackTroop))/100; echo " | ".$z;
			$z = $z>1?1:$z;
			
			if($x==0){
				$x=1;
			}
			
			switch($flag){
				case 1:
					$defendDie=$x/(1+$y);
					$attackDie=$x-$defendDie;
					break;
				case 2:
					$attackDie=$x/(1+$y);
					$defendDie=$x-$attackDie;
					break;
			}
		}else{
			//Truong hop tu chien
			switch($flag){
				case 1:
					$defendDie = $sumDefendTroop;
					//$attackDie=$y*$sumDefendTroop;
					$attackDie = $sumAttackTroop*pow($y, 1.5);
					break;
				case 2:
					$attackDie = $sumAttackTroop;
					//$defendDie=$y*$sumAttackTroop;
					$defendDie = $sumDefendTroop*pow($y, 1.5);
					break;
			}
		}
		*/
	}else{
		//so linh chet ben tan cong:
		$attackDie=0;
		//so linh chet ben phong thu:
		$defendDie=0;
		return false;
	}
	
//	echo $sumAttack."<br>";
//	echo $sumDefend."<br>";
		
	//cap nhat mang linh attack
	if($attackDie>0 && $sumAttackTroop>0){
		$newAttackDie=0;
		foreach($troopAttackList as &$troopAttacks){
			foreach($troopAttacks as $troopId=>&$troopAttack){
				//tinh so linh chet ung voi moi loai linh trong danh sach.
				$prAtt=$troopAttack['num']/$sumAttackTroop;	//phan tram suc cong moi loai
				if($flag==2){
					if($type==3 || $type==10){
						$die=round($attackDie*$prAtt, 0);
					}else{
						$die=$troopAttack['num'];
					}
				}else{
					$die=round($attackDie*$prAtt, 0);
				}
				
				//tang so linh chet.
				$troopAttack['die_num'] += $die;
				$troopAttack['die_num']=$troopAttack['die_num']<=$troopAttack['num']?$troopAttack['die_num']:$troopAttack['num'];
				//tinh luong luong thuc ma linh bi chet tieu thu moi gio:
				$sumKeepHourAttackDie+=$troopAttack['die_num']*$troopAttack['keep_hour'];
			}
		}
		//Tinh % HP ma tuong bi mat cho hero ben cong:
		if($sumKeepHourAttack>0){
			$mauTuongCongMat=($sumKeepHourAttackDie/$sumKeepHourAttack)*100;
		}else{
			$mauTuongCongMat=0;
		}
		
		//Tinh diem kinh nghiem cho hero ben thu:
		$diemTuongThu=$sumKeepHourAttackDie;
	}
	
	//cap nhat mang linh defend
	if($defendDie>0 && $sumDefendTroop>0){
		$newDefendDie=0;
		if($type==7 || $type==8){
			//Neu la danh do tham:
			foreach($troopDefendList as &$troopDefends){
				foreach($troopDefends as $troopId=>&$troopDefend){
					if($troopId==8 || $troopId==19 || $troopId==30){
						if($flag==1){
							if($type==3 || $type==10){
								$die=round($troopDefend['num']*($defendDie/$sumDefendTroop), 0);
							}else{
								$die=$troopDefend['num'];
							}
						}else{
							$die=round($troopDefend['num']*($defendDie/$sumDefendTroop), 0);
						}
							
						//tang so linh chet.
						$troopDefend['die_num'] += $die;
						$troopDefend['die_num']=$troopDefend['die_num']<=$troopDefend['num']?$troopDefend['die_num']:$troopDefend['num'];
						$sumKeepHourDefendDie+=$troopDefend['die_num']*$troopDefend['keep_hour'];
					}
				}
			}
		}else{
			foreach($troopDefendList as &$troopDefends){
				foreach($troopDefends as $troopId=>&$troopDefend){
					//tinh so linh chet ung voi moi loai linh trong danh sach.
					$prDf=$troopDefend['num']/$sumDefendTroop;
					if($flag==1){
						if($type==3 || $type==10){
							$die=round($defendDie*$prDf, 0);
						}else{
							$die=$troopDefend['num'];
						}
					}else{
						$die=round($defendDie*$prDf, 0);
					}
						
					//tang so linh chet.
					$troopDefend['die_num'] += $die;			
					$troopDefend['die_num']=$troopDefend['die_num']<=$troopDefend['num']?$troopDefend['die_num']:$troopDefend['num'];
					//tinh luong luong thuc ma linh bi chet tieu thu moi gio:
					$sumKeepHourDefendDie+=$troopDefend['die_num']*$troopDefend['keep_hour'];
				}
			}
			//Tinh so diem kinh nghiem cho hero ben cong
			$diemTuongCong=$sumKeepHourDefendDie;
			//Tinh % hp ma tuong
			if($sumKeepHourDefend>0){
				$mauTuongThuMat=($sumKeepHourDefendDie/$sumKeepHourDefend)*100;
			}else{
				$mauTuongThuMat=0;
			}				
		}	
	}
		
	//Cap nhat cac thong so cho Tuong hai ben:	
	if($heroAttack && $heroAttack->num>$heroAttack->die_num){
		$heroAttack->kinh_nghiem += $diemTuongCong;
		if($sumAttackTroop>0){
			//so sanh chi so mau bi mat voi chi so mau co:
			if($heroAttack->hitpoint>$mauTuongCongMat){
				//tuong con song:				
				$heroAttack->hitpoint -= $mauTuongCongMat;
			}else{
				//Tuong bi chet
				$heroAttack->hitpoint = 0;
				$heroAttack->die_num=1;
			}
		}else{
			if($sumAttack>$sumDefend){
				//tuong con song:
				$heroAttack->hitpoint -= $mauTuongCongMat;
			}else{
				//Tuong bi chet
				$heroAttack->hitpoint = 0;
				$heroAttack->die_num = 1;
			}
		}			
	}
	
	//Tuong ben thu:
	$sumHeroDenfendDie=0;
	if($soTuongThu>0){
		$diemTuongThu=$diemTuongThu/$soTuongThu;
		$mauTuongThuMat=$mauTuongThuMat/$soTuongThu;
		foreach($heroDefendList as &$heroDefend){
			if($heroDefend->num>$heroDefend->die_num){
				$heroDefend->kinh_nghiem +=$diemTuongThu;
				if($sumDefendTroop>0){
					if($heroDefend->hitpoint>$mauTuongThuMat){
						$heroDefend->hitpoint -=$mauTuongThuMat;
					}else{
						$heroDefend->hitpoint 	= 0;
						$heroDefend->die_num 	= 1;
					}
				}else{
					if($sumDefend>$sumAttack){
						$heroDefend->hitpoint -=$mauTuongThuMat;
					}else{
						$heroDefend->hitpoint 	= 0;
						$heroDefend->die_num	= 1;
					}
				}					
			}
		}
	}
}

/**
 * @author Le Van Tu
 * @des lay thong tin linh cua hai ben de chuan bi attack
 * @param $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben
 * @return $attackTroops, $defendTroops hai mang 3 chieu luu luc luong cua 2 ben
 */
function getAttackInfo(&$attackTroops, &$defendTroops){
	$attackTroops=getAttackTroop();
	$defendTroops=getDefendTroop();
}

/**
 * @author Le Van Tu
 * @des lay danh sach linh cua ben tan cong
 * @param $attack mot obj chua cac thong tin ve tran danh
 * @return $attackTroops hai mang 3 chieu luu luc luong cua 2 ben
 */
function getAttackTroop($attack, $arrayTroop){
	global $db;
	//lay danh sach vu khi de tinh toan attack cua moi linh
	$listItem=GetListItem($attack->village_attack_id, 1, false, 1,1,1);	
	$sql="SELECT id, troop_id, num FROM wg_attack_troop Where attack_id=$attack->id";
	$db->setQuery($sql);
	$attackTroopList=null;
	$attackTroopList=$db->loadObjectList();
	if($attackTroopList){
		foreach($attackTroopList as $attackTroop){			
			$result[$attack->village_attack_id][$attackTroop->troop_id]['id']=$attackTroop->troop_id;
			$result[$attack->village_attack_id][$attackTroop->troop_id]['attack_id']=$attack->id;
			$result[$attack->village_attack_id][$attackTroop->troop_id]['attack_troop_id']=$attackTroop->id;
			$result[$attack->village_attack_id][$attackTroop->troop_id]['num']=$attackTroop->num;
			$result[$attack->village_attack_id][$attackTroop->troop_id]['die_num']=0;			
			$result[$attack->village_attack_id][$attackTroop->troop_id]['keep_hour']=$arrayTroop[$attackTroop->troop_id]['keep_hour'];
			$result[$attack->village_attack_id][$attackTroop->troop_id]['melee_defense']=$arrayTroop[$attackTroop->troop_id]['melee_defense'];
			$result[$attack->village_attack_id][$attackTroop->troop_id]['ranger_defense']=$arrayTroop[$attackTroop->troop_id]['ranger_defense'];
			$result[$attack->village_attack_id][$attackTroop->troop_id]['magic_defense']=$arrayTroop[$attackTroop->troop_id]['magic_defense'];
			$result[$attack->village_attack_id][$attackTroop->troop_id]['hitpoint']=$arrayTroop[$attackTroop->troop_id]['hitpoint'];
			$result[$attack->village_attack_id][$attackTroop->troop_id]['speed']=$arrayTroop[$attackTroop->troop_id]['speed'];				
			$result[$attack->village_attack_id][$attackTroop->troop_id]['carry']=$arrayTroop[$attackTroop->troop_id]['carry'];
			$result[$attack->village_attack_id][$attackTroop->troop_id]['type']=$arrayTroop[$attackTroop->troop_id]['type'];
			//Tinh toan suc tan cong ung voi level cua binh khi:
			$result[$attack->village_attack_id][$attackTroop->troop_id]['attack']=GetIncreaseAttack($arrayTroop[$attackTroop->troop_id]['attack'], $listItem[$attackTroop->troop_id]['level']);			
		}
	}else{
		$result[$attack->village_attack_id][0]['num']=0;
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des lay thong tin hero tham gia tran danh (ben phong thu)
 * @param object wg_attack
 * @return list of hero object
 */
function getDefendHeroList($attack){
	global $db;
	$result=array();
	$heroVillageDefend=GetHeroVillage($attack->village_defend_id);
	if($heroVillageDefend){
		$heroVillageDefend->num		= 1;
		$heroVillageDefend->die_num	= 0;
		$result[$attack->village_defend_id]=GetHeroInfoAttack($heroVillageDefend);
	}
		
	$sql="SELECT
				wg_heros.*,
				wg_attack.village_attack_id, 
				wg_attack_hero.num,
				wg_attack_hero.id AS attack_hero_id, 
				wg_attack_hero.die_num
			FROM
				wg_heros ,
				wg_attack_hero ,
				wg_attack
			WHERE
				wg_attack.id =  wg_attack_hero.attack_id AND
				wg_heros.id =  wg_attack_hero.hero_id AND
				wg_attack.`status` =  '0' AND
				wg_attack.`type` =  '1' AND 
				wg_attack.village_defend_id = '$attack->village_defend_id' 
			GROUP BY
				wg_heros.id";
	$db->setQuery($sql);
	$heroList = $db->loadObjectList();
	if($heroList){
		foreach($heroList as $hero){
			$result[$hero->village_attack_id]=GetHeroInfoAttack($hero);
		}		
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des lay danh sach linh cua ben phong thu
 * @param $attack mot obj chua cac thong tin ve tran danh
 * @return $defendTroops hai mang 3 chieu luu luc luong cua 2 ben
 */
function getDefendTroop($attack, $arrayTroop){
	global $db;
	$sql="SELECT id, troop_id, num FROM wg_troop_villa WHERE num>0 AND village_id=$attack->village_defend_id";	
	$db->setQuery($sql);
	$troopVillaList=$db->loadObjectList();
	if($troopVillaList){
		$listArmour=GetListArmour($attack->village_defend_id, 1, false, 0, 0, 0);
		foreach($troopVillaList as $troopVilla){
			//Kiem tra xem co phai la hero hay khong.
			$result[$attack->village_defend_id][$troopVilla->troop_id]['id']=$troopVilla->troop_id;
			$result[$attack->village_defend_id][$troopVilla->troop_id]['troop_villa_id']=$troopVilla->id;//su dung khi cap nhat sau khi danh.
			$result[$attack->village_defend_id][$troopVilla->troop_id]['num']=$troopVilla->num;
			$result[$attack->village_defend_id][$troopVilla->troop_id]['die_num']=0;
			$result[$attack->village_defend_id][$troopVilla->troop_id]['keep_hour']=$arrayTroop[$troopVilla->troop_id]['keep_hour'];
			$result[$attack->village_defend_id][$troopVilla->troop_id]['attack']=$arrayTroop[$troopVilla->troop_id]['attack'];
			$result[$attack->village_defend_id][$troopVilla->troop_id]['melee_defense']=getIncreaseDefend($arrayTroop[$troopVilla->troop_id]['melee_defense'], $listArmour[$troopVilla->troop_id]['level']);
			$result[$attack->village_defend_id][$troopVilla->troop_id]['ranger_defense']=getIncreaseDefend($arrayTroop[$troopVilla->troop_id]['ranger_defense'], $listArmour[$troopVilla->troop_id]['level']);
			$result[$attack->village_defend_id][$troopVilla->troop_id]['magic_defense']=getIncreaseDefend($arrayTroop[$troopVilla->troop_id]['magic_defense'], $listArmour[$troopVilla->troop_id]['level']);
			$result[$attack->village_defend_id][$troopVilla->troop_id]['hitpoint']=$arrayTroop[$troopVilla->troop_id]['hitpoint'];
			$result[$attack->village_defend_id][$troopVilla->troop_id]['type']=$arrayTroop[$troopVilla->troop_id]['type'];
		}
	}else{
		$result[$attack->village_defend_id][0]["num"]=0;
	}
	
	//Lay danh sach linh reinforce.
	$sql="SELECT * FROM wg_attack WHERE village_defend_id=$attack->village_defend_id AND type=1 AND wg_attack.status=0";
	$db->setQuery($sql);
	$reinforceList=$db->loadObjectList();
	if($reinforceList){
		foreach($reinforceList as $reinforce){
			$sql="SELECT * FROM wg_attack_troop WHERE num>die_num AND wg_attack_troop.status=0 AND attack_id=$reinforce->id";
			$db->setQuery($sql);
			$troopReinforceList=$db->loadObjectList();
			foreach($troopReinforceList as $troopReinforce){
				$listArmour=GetListArmour($reinforce->village_attack_id, 1, false, 0, 0, 0);
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['id']=$troopReinforce->troop_id;
				//dung de cap nhat du lieu sau khi danh.--------------------->
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['attack_id']=$troopReinforce->attack_id;
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['attack_troop_id']=$troopReinforce->id;
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['reinforce_village_id']=$reinforce->village_attack_id;
				//------------------------------------------------------------/
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['num']=$troopReinforce->num;
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['die_num']=$troopReinforce->die_num;
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['keep_hour']=$arrayTroop[$troopReinforce->troop_id]['keep_hour'];
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['attack']=$arrayTroop[$troopReinforce->troop_id]['attack'];
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['melee_defense']=getIncreaseDefend($arrayTroop[$troopReinforce->troop_id]['melee_defense'], $listArmour[$troopReinforce->troop_id]['level']);
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['ranger_defense']=getIncreaseDefend($arrayTroop[$troopReinforce->troop_id]['ranger_defense'], $listArmour[$troopReinforce->troop_id]['level']);
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['magic_defense']=getIncreaseDefend($arrayTroop[$troopReinforce->troop_id]['magic_defense'], $listArmour[$troopReinforce->troop_id]['level']);
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['hitpoint']=$arrayTroop[$troopReinforce->troop_id]['hitpoint'];
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['type']=$arrayTroop[$troopReinforce->troop_id]['type'];					
			}
		}
	}
	return $result;		
}

/**
 * @author Le Van Tu
 * @des lay thong tin hero tham gia tran danh (ben phong thu)
 * @param object wg_attack
 * @return list of hero object
 */
function getReinforceHeroList($village_id){
	global $db;
	$result=array();
	$sql="SELECT
				wg_heros.*,
				wg_attack.village_attack_id, 
				wg_attack_hero.num,
				wg_attack_hero.id AS attack_hero_id, 
				wg_attack_hero.die_num
			FROM
				wg_heros ,
				wg_attack_hero ,
				wg_attack
			WHERE
				wg_attack.id =  wg_attack_hero.attack_id AND
				wg_heros.id =  wg_attack_hero.hero_id AND
				wg_attack.`status` =  '0' AND
				wg_attack.`type` =  '1' AND 
				wg_attack.village_defend_id = '$village_id' 
			GROUP BY
				wg_heros.id";
	$db->setQuery($sql);
	$heroList = $db->loadObjectList();
	if($heroList){
		foreach($heroList as $hero){
			$result[$hero->village_attack_id]=GetHeroInfoAttack($hero);
		}		
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des lay danh sach linh cua ben phong thu (linh vien tro)
 * @param $attack mot obj chua cac thong tin ve tran danh
 * @return $defendTroops mang 3 chieu luu luc luong cua 2 ben
 */
function getReinforeTroop($attack, $arrayTroop){
	global $db;	
	//Lay danh sach linh reinforce.
	$sql="SELECT * FROM wg_attack WHERE village_defend_id=$attack->village_defend_id AND type=1 AND wg_attack.status=0";
	$db->setQuery($sql);
	$reinforceList=$db->loadObjectList();
	if($reinforceList){
		foreach($reinforceList as $reinforce){
			$sql="SELECT * FROM wg_attack_troop WHERE num>die_num AND wg_attack_troop.status=0 AND attack_id=$reinforce->id";
			$db->setQuery($sql);
			$troopReinforceList=$db->loadObjectList();
			foreach($troopReinforceList as $troopReinforce){
				$listArmour=GetListArmour($reinforce->village_attack_id, 1, false, 0, 0, 0);
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['id']=$troopReinforce->troop_id;
				//dung de cap nhat du lieu sau khi danh.--------------------->
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['attack_id']=$troopReinforce->attack_id;
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['attack_troop_id']=$troopReinforce->id;
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['reinforce_village_id']=$reinforce->village_attack_id;
				//------------------------------------------------------------/
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['num']=$troopReinforce->num;
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['die_num']=$troopReinforce->die_num;
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['keep_hour']=$arrayTroop[$troopReinforce->troop_id]['keep_hour'];
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['attack']=$arrayTroop[$troopReinforce->troop_id]['attack'];
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['melee_defense']=getIncreaseDefend($arrayTroop[$troopReinforce->troop_id]['melee_defense'], $listArmour[$troopReinforce->troop_id]['level']);
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['ranger_defense']=getIncreaseDefend($arrayTroop[$troopReinforce->troop_id]['ranger_defense'], $listArmour[$troopReinforce->troop_id]['level']);
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['magic_defense']=getIncreaseDefend($arrayTroop[$troopReinforce->troop_id]['magic_defense'], $listArmour[$troopReinforce->troop_id]['level']);
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['hitpoint']=$arrayTroop[$troopReinforce->troop_id]['hitpoint'];
				$result[$reinforce->village_attack_id][$troopReinforce->troop_id]['type']=$arrayTroop[$troopReinforce->troop_id]['type'];					
			}
		}
	}
	return $result;		
}

/**
 * @author Le Van Tu
 * @param2 object
 * @param3 bigint (thoi diem attack)
 */
function getOasisTroop($oasis, $trpAtt, $time_end, $arrayTroop){
	include_once("func_build.php");
	
	$result=null;
	
	$trpLst=getOasisTroopNew($trpAtt->troop_list, $oasis->kind_id, $time_end, $trpAtt->att_time);
	
	foreach($trpLst as $tid=>$num){
		$result[$oasis->id][$tid]=$arrayTroop[$tid];
		$result[$oasis->id][$tid]['num']=$num;
		$result[$oasis->id][$tid]['die_num']=0;
	}
	
	return $result;
}

/**
 * @author Le Van Tu
 * @des chen thong tin linh cua oasis
 */
function insertOasisTroop($village_id, $troop_list, $att_time, $faith){
	global $db;
	$sql="INSERT INTO `wg_oasis_troop_att` (`village_id`, `troop_list`, `att_time`, `faith`) VALUES ($village_id, '$troop_list', '$att_time', $faith)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi chen record vao bang wg_oasis_troop_att");
	}
}

/**
 * @author Le Van Tu
 * @des update thong tin linh cua oasis
 */
function updateOasTrpAtt($village_id, $troop_list, $att_time, $faith){
	global $db;
	$sql="UPDATE `wg_oasis_troop_att` SET `troop_list` = '$troop_list', att_time='$att_time', faith=$faith WHERE village_id=$village_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi update bang wg_oasis_troop_att");
	}
}

/**
 * @author Le Van Tu
 * @des update thong tin linh cua oasis
 */
function deleteOasTrp($village_id){
	global $db;
	$sql="DELETE FROM `wg_oasis_troop_att` WHERE village_id=$village_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi delete bang wg_oasis_troop_att");
	}
}

/**
 * @author Le Van Tu
 * @DES Lay loai cua linh
 * @param $troop_id id cua linh
 * @return $type loai linh
 */
function getTroopType($troop_id){
	global $db;
	$sql="SELECT type FROM wg_troops WHERE id=$troop_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * Lay ten cong trinh theo ngon ngu
 */
function GetLangBuildingName($building_name){
	global $lang;
	includelang("build");
	return $lang[$building_name];
}

/**
 * Giam level cua mot cong trinh.
 */
function GiamLevel($building_id, $num){
	global $db;
	$sql="UPDATE wg_buildings SET level=level-$num WHERE id=$building_id";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * Xoa mot building trong lang.
 */
function DeleteBuildingKata($building_id){
	global $db;
	$sql="UPDATE `wg_buildings` SET `name`='', `img`='', `level`=0, `type_id`=0, `product_hour`=0 
				WHERE id=$building_id";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * @author Le Van Tu
 * @DES Kiem tra xem co thuyet gian di cung hay khong. neu co thi thuc hien chiem lamg
 * @param $attackTroopList danh sach linh attack
 * @param $villageAttack lang tan cong
 * @param $villageDefend lang phong thu
 * @param $type kieu danh
 */
function kiemTraChiemLang(&$attackTroopList, &$villageAttack, &$villageDefend, $type, $time){
	$chiemLang=0;
	if($attackTroopList){
		foreach($attackTroopList as &$attackTroops){
			foreach($attackTroops as $troopId=>&$attackTroop){
				if(($type==4 || $type==9) && ($troopId==11 || $troopId==22 || $troopId==33)){
					$chiemLang=ChiemLang($villageAttack, $villageDefend, $attackTroop['num']-$attackTroop['die_num'], $time);
					//Thuyet gia bien mat
					if($chiemLang==1){
						$attackTroop['die_num']++;
					}
				}
			}				
		}
	}
	return $chiemLang;
}

//---------Cac ham lien quan den chiem lang------------->
/**
 * Xu ly khi sang danh ma co thuyet gia
 */
function ChiemLang(&$village_attack, &$village_defend, $sumThuyetGia, $time){
	//Kiem tra xem co du diem danh vong hay khong:
	include_once("function_foundvillage.php");
	if(CheckCulturePoint($village_attack)){
		//Kiem tra xem day co phai la thu do hay khong:
		if(!IsCapital($village_defend->id)){
			//Kiem tra xem lang bi danh co dai dien hay khong			
			if(!CoDaiDien($village_defend->id)){
				//Tinh % long trung thanh se bi giam:
				switch($village_defend->nation_id){
					case 1:
						//Arabia
						$faithDe=$sumThuyetGia*25;
						break;
					case 2:
						//Mongo
						$faithDe=$sumThuyetGia*20;
						break;
					case 3:
						//Sunda
						$faithDe=$sumThuyetGia*20;
						break;
				}
				
				if($village_defend->faith<=$faithDe){
					//Thay doi so lang va so dan cua 2 user
					ChangeUserPopulationSumVillage($village_attack->user_id, $village_defend->workers, 1);
					ChangeUserPopulationSumVillage($village_defend->user_id, -$village_defend->workers, -1);
					
					//het long trung thanh -> bi chiem
					$village_defend->user_id=$village_attack->user_id;
					$village_defend->nation_id = $village_attack->nation_id;
					$village_defend->faith=0;
					$village_defend->faith_time=date("Y-m-d H:i:s", $time);	
					$village_defend->child_id='';
					
					//Bot so lang con cua lang bi mat con:
					$parent_id=GetParentVillage($village_defend->id);
					SubChildForVillage($parent_id, $village_defend->id);
					
					//Them so lang con cho lang thang tran:
					AddChildForVillage($village_attack->id, $village_defend->id);
					
					resetTroopInfo($village_defend->id, $village_attack->nation_id);

					return 1;
				}else{
					//tru long trung thanh cua lang:
					$village_defend->faith-=$faithDe;
					$village_defend->faith_time=date("Y-m-d H:i:s", $time);						
					return $faithDe;
				}
			}else{
				//co dai dien -> long trung thanh khong bi giam
				return -1;
			}
		}else{
			//Khong the chiem duoc thu do.
			return -2;
		}
	}else{
		//khong du diem danh vong
		return -3;
	}
				
}

/**
 * Thay doi dan so va so lang chu mot user
 * @param1 +popunation
 * @param2 +sum_village
 */
function ChangeUserPopulationSumVillage($user_id, $num=0, $num2=0){
	global $db;
	$sql="UPDATE wg_users SET population=population+$num, sum_villages=sum_villages+$num2 WHERE id=$user_id";
	$db->setQuery($sql);
	return $db->query();
}



/**
 * Kiem tra mot lang co phai la thu do hay khong
 */
function IsCapital($village_id){
	global $db;
	$sql="SELECT COUNT(*) FROM wg_users WHERE villages_id=$village_id";
	$db->setQuery($sql);
	return $db->loadResult();
}


/**
 * Kiem tra xem lang bi danh co dai dien hay khong
 */
function CoDaiDien($village_id){
	global $db;
	$sql="SELECT COUNT(*) FROM wg_buildings WHERE type_id=18 AND vila_id=$village_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * Tim lang cha cua mot lang
 */
function GetParentVillage($village_child_id){
	global $db;
	$sql="SELECT id FROM wg_villages WHERE child_id=$village_child_id";
	$db->setQuery($sql);
	return $db->loadResult();
}
//--------End cac ham lien quan den chiem lang---------->


/**
* @Author: ManhHX
* @Des: get village name
* @param: $vId village id
* @return: $objRes:object village
*/
function getVillageInfoById($vId){
	global $db;	
	$query="SELECT * FROM wg_villages WHERE id=$vId";
	$db->setQuery($query);	
	$objRes = $db->loadObjectList();
	//$vName = $objRes[0]->name;
	
	return $objRes;
}

/**
* @Author: ManhHX
* @Des: write all report
* @param: $nationId nation id
* @return: $soldierName:string
*/
function writeAttackReport($arrAttacks, $heroAttack, $arrDefs, $heroDefendList, $bounty, $arrInfos, $timeAttack){	
	global $lang;
	includeLang("attack");
	$parse=$lang;	
	$parseForAttack = $lang;	
	//Begin execute attack data report
	$vIdAttCheck = 0;
	$numAttSoldier = 0;
	$reinforceTables ='';
	$userAttackId =0;
	$userDefId =0;
	
	if($arrAttacks){
		foreach($arrAttacks as $k =>$v){
			if($vIdAttCheck ==0 ){
				$objAttackVillage = getVillageInfoById($k);
				$listTroopVillageAttack=GetListTroopVilla($objAttackVillage[0]);							
				$parse['player_attack_name']=GetPlayerName($k);	
				
				if($objAttackVillage[0]->name =="NewName"){
					$villageNameAtt = $lang[$objAttackVillage[0]->name];
				}else{
					$villageNameAtt = $objAttackVillage[0]->name;
				}			
				$parse['village_attack_name']=$villageNameAtt;
				$parse['attack_x']=$objAttackVillage[0]->x;
				$parse['attack_y']=$objAttackVillage[0]->y;
				$userAttackId = GetPlayerID($k);
				$vIdAttCheck = $k;				
			}
		}
	}
		
			
	for($i=0; $i<11; $i++){	
		$live_num = $arrAttacks[$vIdAttCheck][$listTroopVillageAttack[$i]->id]['num'];
		$die_num = 	$arrAttacks[$vIdAttCheck][$listTroopVillageAttack[$i]->id]['die_num'];
		$numAttSoldier = $numAttSoldier + $live_num - $die_num;
		
		if($live_num=='' || !$live_num){
			$live_num = 0;
			$parse['class_att_'.($i+1)]="c";
		}
		else{
			$parse['class_att_'.($i+1)]="";
		}
		
		if($die_num=='' || !$die_num){
			$die_num = 0;
			$parse['class_at_ca_'.($i+1)]="c";
		}
		else{
			$parse['class_at_ca_'.($i+1)]="";
		}
		
		$parse['st_att_'.($i+1)] = $live_num ;
		$parse['casualties_att_'.($i+1)] = $die_num ;				
		$parse['iconat'.($i+1)]=$listTroopVillageAttack[$i]->icon;		
		$parse['titleat'.($i+1)]=$lang[$listTroopVillageAttack[$i]->name];		
	}
	
	if($heroAttack){
		$parse['st_att_12']=1;
		$parse['class_att_12']="";
		if($heroAttack->die_num>0){
			$parse['casualties_att_12']=1;
			$parse['class_at_ca_12']="";
		}else{
			$numAttSoldier = $numAttSoldier + 1;
			$parse['casualties_att_12']=0;
			$parse['class_at_ca_12']="c";
		}
	}else{
		$parse['st_att_12']=0;
		$parse['class_att_12']="c";
		$parse['casualties_att_12']=0;
		$parse['class_at_ca_12']="c";
	}
	$parse['iconat12']="images/icon/hero4.ico";
	$parse['titleat12']=$lang['hero'];	
	//End execute attack data report
		
	
	//Begin execute defend data report
	$vIdDefCheck = 0;
	$mainVillageDefend = true;
	$indexReinforce = 0;
	$defendScanner = false;//has scanner (co do tham ko)
	$arrShowDataForAttack =  array();	
	
	foreach($heroDefendList as $keyVidTmp => $valueTmp){		
		if(!count($arrDefs[$keyVidTmp])){
			$arrDefs[$keyVidTmp][0]["num"]=0;
		}
	}
	
	if($arrDefs){
		foreach($arrDefs as $k =>$v){
			$objDefendVillage = getVillageInfoById($k);
			if($vIdDefCheck ==0 ){
				$listTroopVillageDefend=GetListTroopVilla($objDefendVillage[0]);
				$parse['player_defend_name']=GetPlayerName($k);
				
				if($objDefendVillage[0]->name =="NewName"){
					$villageNameDefend = $lang[$objDefendVillage[0]->name];
				}else{
					$villageNameDefend = $objDefendVillage[0]->name;
				}	
							
				$parse['village_defend_name']=$villageNameDefend;
				$parse['defender_x']=$objDefendVillage[0]->x;
				$parse['defender_y']=$objDefendVillage[0]->y;			
				$userDefId = GetPlayerID($k);
				$vIdDefCheck = $k;
			}
			else{
				$listTroopVillageDefend=GetListTroopVilla($objDefendVillage[0]);
				$parse['player_reinforce_name']=GetPlayerName($k);	
				
				if($objDefendVillage[0]->name =="NewName"){
					$villageNameReinforce = $lang[$objDefendVillage[0]->name];
				}else{
					$villageNameReinforce = $objDefendVillage[0]->name;
				}							
				$parse['village_reinforce_name']=$villageNameReinforce;
				$parse['sender_x']=$objDefendVillage[0]->x;
				$parse['sender_y']=$objDefendVillage[0]->y;				
			}
				
			if($mainVillageDefend){
				$className1 = 'class_defend_';
				$className2 = 'class_casualties_def_';
				$st_def = 'st_def_';
				$casualties_def = 'casualties_def_';
				$icon_def = 'icon_def_';
				$title_def = 'title_def_';
				
			}
			else{
				$className1 = 'class_re_';
				$className2 = 'class_re_die_';
				$st_def = 'st_re_';
				$casualties_def = 'st_re_die_';
				$icon_def = 're_icon_';
				$title_def = 're_title_';
			}
					
			for($i=0; $i<11; $i++){	
				$live_num = $arrDefs[$k][$listTroopVillageDefend[$i]->id]['num'];
				$die_num = 	$arrDefs[$k][$listTroopVillageDefend[$i]->id]['die_num'];
				if($live_num=='' || !$live_num){
					$live_num = 0;
					$parse[$className1.($i+1)]="c";
				}
				else{
					$parse[$className1.($i+1)]="";
				}
				
				if($die_num=='' || !$die_num){
					$die_num = 0;
					$parse[$className2.($i+1)]="c";
				}
				else{
					$parse[$className2.($i+1)]="";
				}
				
				$tmpTroopId = $listTroopVillageDefend[$i]->id;
				if(($tmpTroopId==8||$tmpTroopId==19||$tmpTroopId==30) && ($live_num>0) ){//village has scanner
					$defendScanner = true;
				}
				
				$parse[$st_def.($i+1)] = $live_num ;
				$parse[$casualties_def.($i+1)] = $die_num ;				
				$parse[$icon_def.($i+1)]=$listTroopVillageDefend[$i]->icon;		
				$parse[$title_def.($i+1)]=$lang[$listTroopVillageDefend[$i]->name];	
				
				if(isset($arrShowDataForAttack[$objDefendVillage[0]->nation_id])){
					$arrShowDataForAttack[$objDefendVillage[0]->nation_id][$i]['num']+= $live_num;
					$arrShowDataForAttack[$objDefendVillage[0]->nation_id][$i]['die_num']+= $die_num;					
				}else{
					$arrShowDataForAttack[$objDefendVillage[0]->nation_id][$i]['num']= $live_num;
					$arrShowDataForAttack[$objDefendVillage[0]->nation_id][$i]['die_num']= $die_num;
				}
				$arrShowDataForAttack[$objDefendVillage[0]->nation_id][$i]['icon']= $listTroopVillageDefend[$i]->icon;
				$arrShowDataForAttack[$objDefendVillage[0]->nation_id][$i]['title']= $lang[$listTroopVillageDefend[$i]->name];
			}
			
			$heroDefend=$heroDefendList[$k];	
			if($heroDefend){
				$parse[$st_def.'12']=1;
				$parse[$className1.'12']="";
				if($heroDefend->die_num>0){
					$parse[$casualties_def.'12']=1;
					$parse[$className2.'12']="";
				}else{
					$parse[$casualties_def.'12']=0;
					$parse[$className2.'12']="c";
				}
				$heroDefendList[$k]=null;
			}else{
				$parse[$st_def.'12']=0;
				$parse[$casualties_def.'12']=0;
				$parse[$className1.'12']="c";
				$parse[$className2.'12']="c";
			}
			if(isset($arrShowDataForAttack[$objDefendVillage[0]->nation_id][11])){
				$arrShowDataForAttack[$objDefendVillage[0]->nation_id][11]['num']+= $heroDefend->num;
				$arrShowDataForAttack[$objDefendVillage[0]->nation_id][11]['die_num']+= $heroDefend->die_num;
			}else{
				$arrShowDataForAttack[$objDefendVillage[0]->nation_id][11]['num']= $heroDefend->num;
				$arrShowDataForAttack[$objDefendVillage[0]->nation_id][11]['die_num']= $heroDefend->die_num;
			}
			
			$parse[$icon_def.'12']="images/icon/hero4.ico";
			$parse[$title_def.'12']=$lang['hero'];
			
			if(!$mainVillageDefend){//get templates reinforce report
				$arrReinforceVillage[$indexReinforce]['time'] = $arrDefs[$k][0]['time'];
				$arrReinforceVillage[$indexReinforce]['uId']= GetPlayerID($k);	
				$parse['user_sender_id'] = $arrReinforceVillage[$indexReinforce]['uId'];
				$parse['user_reinforce_id'] = $parse['user_sender_id'];
				if($arrReinforceVillage[$indexReinforce]['uId']){ // Neu ton tai user thi moi viet report
					$arrReinforceVillage[$indexReinforce]['content'] = parsetemplate(gettemplate('attack_report_reinforce'), $parse);		
					$reinforceTables.= parsetemplate(gettemplate("attack_report_reinforce_table"), $parse);
				}
				
			}	
			
			$indexReinforce++;
			$mainVillageDefend = false;
		}//End execute defend data report
	}
			
	//phan report moi cho ben attack	
	/*echo "<pre>"; print_r($arrShowDataForAttack);
	echo "<pre>"; print_r($arrDefs);
	die();*/	
	$parseForAttack = $parse;
	$defendHasScanLive=false;
	if($arrShowDataForAttack){
		$mainDefendCheck= true;
		foreach($arrShowDataForAttack as $kNationId => $vNo){
			for($i=0; $i<12; $i++){			
				if($i==7 && $vNo[$i]['num']){
					$defendHasScanLive=true;
				}	
				if($mainDefendCheck){
					$parseForAttack['st_def_'.($i+1)] = $vNo[$i]['num'] ;
					$parseForAttack['casualties_def_'.($i+1)] = $vNo[$i]['die_num'] ;				
					$parseForAttack['icon_def_'.($i+1)]=$vNo[$i]['icon'] ;		
					$parseForAttack['title_def_'.($i+1)]=$vNo[$i]['title'] ;
					
					$parseForAttack['class_defend_'.($i+1)]="";	
					if(!$vNo[$i]['num']){					
						$parseForAttack['class_defend_'.($i+1)]="c";
					}
					$parseForAttack['class_casualties_def_'.($i+1)]="";					
					if(!$vNo[$i]['die_num']){					
						$parseForAttack['class_casualties_def_'.($i+1)]="c";
					}					
				}else{
					$parseForAttack['st_re_'.($i+1)] = $vNo[$i]['num'] ;
					$parseForAttack['st_re_die_'.($i+1)] = $vNo[$i]['die_num'] ;				
					$parseForAttack['re_icon_'.($i+1)]=$vNo[$i]['icon'] ;		
					$parseForAttack['re_title_'.($i+1)]=$vNo[$i]['title'] ;
					
					$parseForAttack['class_re_'.($i+1)]="";	
					if(!$vNo[$i]['num']){					
						$parseForAttack['class_re_'.($i+1)]="c";
					}
					$parseForAttack['class_re_die_'.($i+1)]="";					
					if(!$vNo[$i]['die_num']){					
						$parseForAttack['class_re_die_'.($i+1)]="c";
					}
				}
			}			
			if(!$vNo[11]['num'] && !$vNo[11]['die_num'] && $mainDefendCheck){
				$parseForAttack['st_def_12'] = 0;
				$parseForAttack['casualties_def_12'] = 0;	
			}
			if(!$vNo[11]['num'] && !$vNo[11]['die_num'] && !$mainDefendCheck){
				$parseForAttack['st_re_12'] = 0;
				$parseForAttack['st_re_die_12'] = 0;	
			}
			
			$parseForAttack['icon_def_12']="images/icon/hero4.ico";
			$parseForAttack['title_def_12']=$lang['hero'];
			$parseForAttack['re_icon_12']="images/icon/hero4.ico";
			$parseForAttack['re_title_12']=$lang['hero'];
			
			switch($kNationId){
				case 1:
					$parseForAttack['soldier_name'] = NATION_NAME_ARABIA;
					break;
				case 2:
					$parseForAttack['soldier_name'] = NATION_NAME_MONGO;
					break;
				case 3:
					$parseForAttack['soldier_name'] = NATION_NAME_SUNDA;
					break;
				default:
					break;
			}
			if(!$mainDefendCheck){//parse to templates
				$reportForAttack.= parsetemplate(gettemplate("attack_report_reinforce_table1"), $parseForAttack);
			}else{
				$parseForAttack['soldier_name_main_def'] = $parseForAttack['soldier_name'];
			}
			$mainDefendCheck=false;
		}
	}

	$parse['info_rows'] ='';
	switch($arrInfos[0]){//get data for title
		case 4://occupied village				
			$lang['attack'] = $lang['tu_chien'];			
			break;
		case 2://			
			$lang['attack'] = $lang['tu_chien'];
			break;
		case 3://			
			$lang['attack'] = $lang['do_tham'];
			$parse['Attacker'] = $lang['ben_do_tham'];
			break;
		default:			
			break;			
	}
	
	if($arrInfos){
		foreach($arrInfos as $kInfo =>$vInfo){
			if($kInfo && $kInfo!="user_defend_id"){	
				switch($kInfo){
					case 'cong_trinh_1':
					case 'cong_trinh_2':
						$parse['cong_trinh'] = $lang['cong_trinh'];
						break;
					case 'bau_vat':
						$parse['cong_trinh'] = $lang['rare'];
						break;
					case 'resource':
						$parse['cong_trinh'] = $lang['resource'];
						break;				
					case 'soldiers':
						$parse['cong_trinh'] = $lang['soldiers'];
						break;				
					case 'workers':			
						$parse['cong_trinh'] = $lang['workers'];
						break;				
					default:	
						$parse['cong_trinh'] = $lang['info'];		
						break;			
				}
				$parse['building_info']=$vInfo;
				$parse['info_rows'].=parsetemplate(gettemplate("attack_report_destroyed_row"), $parse);	
			}else{
				$parse['building_info']="";
			}		
		}
	}
		
	if(!$bounty){
		$bounty=array('rs1'=>0,'rs2'=>0,'rs3'=>0,'rs4'=>0);
	}else{
		$resourceTotal = $bounty['rs1']+$bounty['rs2']+$bounty['rs3']+$bounty['rs4'];
		updateInfoTop10($userAttackId, "resource", $resourceTotal);
	}
	
	$parse['bounty_rs_1']=$bounty['rs1'];
	$parse['bounty_rs_2']=$bounty['rs2'];
	$parse['bounty_rs_3']=$bounty['rs3'];
	$parse['bounty_rs_4']=$bounty['rs4'];
			
	if($reinforceTables){
		$parse['reinforcement_table']=$reinforceTables;
	}else{
		$parse['reinforcement_table']='';
	}
	
	$parse['user_attack_id'] = $userAttackId;
	$parse['user_defender_id'] = $userDefId;
	
	if($arrInfos[0]==3){		
		if(!$defendScanner){
			$defendReportContent = parsetemplate(gettemplate("defend_scan_lose"), $parse);
		}else{			
			$defendReportContent = parsetemplate(gettemplate("attack_scan"), $parse);
		}
	}
	else{
		$defendReportContent = parsetemplate(gettemplate("attack_report"), $parse);	
	}
	
	$reportTitle=$parse['village_attack_name'].' '.$lang['attack'].' '.$parse['village_defend_name'];
	
	if($numAttSoldier > 0 || ($heroAttack->die_num==0 && $heroAttack->num==1) || isset($arrInfos['user_defend_id'])){//attack win
		//$attackReportContent = $defendReportContent;
		$parseForAttack['reinforcement_table1']='';
		$parseForAttack['bounty_rs_1']=$bounty['rs1'];
		$parseForAttack['bounty_rs_2']=$bounty['rs2'];
		$parseForAttack['bounty_rs_3']=$bounty['rs3'];
		$parseForAttack['bounty_rs_4']=$bounty['rs4'];
		$parseForAttack['user_attack_id']=$userAttackId;
		$parseForAttack['user_defender_id']=$userDefId;
		$parseForAttack['info_rows']=$parse['info_rows'];
		if($reportForAttack){
			$parseForAttack['reinforcement_table1']=$reportForAttack;
		}
		$attackReportContent = parsetemplate(gettemplate("attack_report1"), $parseForAttack);		
	}
	else{//fail
		$attackReportContent = parsetemplate(gettemplate("attack_report_lose"), $parse);	
	}
		
	//Attack
	InsertReport($userAttackId, $reportTitle, $timeAttack, $attackReportContent, REPORT_ATTACK);
	//Defend
	if($arrInfos[0]!=3 || $defendHasScanLive){
		if(isset($arrInfos['user_defend_id'])){
			$userDefId=$arrInfos['user_defend_id'];
		}
		InsertReport($userDefId, $reportTitle, $timeAttack, $defendReportContent, REPORT_DEFEND);
	}
	
	if($arrReinforceVillage){//insert reinforces report
		foreach($arrReinforceVillage as $reinforceReport){
			$userReinforceId = $reinforceReport['uId'];
			$titleReinforce = $lang['giup_do1'].' '.$parse['village_defend_name'].' '.$lang['giup_do2'];
			if($userReinforceId){ // Neu ton tai user thi moi viet report
				InsertReport($userReinforceId, $titleReinforce, $timeAttack, $reinforceReport['content'], REPORT_DEFEND);
			}
		}
	}
}

/**
* @Author: ManhHX
* @Des: effect for ally
* @param: $arrAttacks attack info; $arrDefs defends info
* $attackDiePoint so bat com ben cong, $defendDiePoint so bat com ben thu
* @return: null
*/
function executeAllyEffect($arrAttacks, $arrDefs, $attackDiePoint, $defendDiePoint){	
	global $db, $user, $lang;
	
	$mainAttVillageId = 0;
	foreach($arrAttacks as $kAtt =>$vAtt){
		$mainAttVillageId = $kAtt;
	}
	
	$mainVillageDefend = 0;
	foreach($arrDefs as $kDefend =>$vDefend){
		if(!$mainVillageDefend){
			$mainVillageDefend = $kDefend;
			break;
		}				
	}
	
	$deltaAttack = (int)($defendDiePoint - $attackDiePoint);	
	if($deltaAttack!=0){ // Khi khac 0 thi moi cap nhat Hieu suat
		$allyAttackId = findJoinedAlly(0, $mainAttVillageId);			
		if($allyAttackId){ // Ben attack co tham gia lien minh		
			$updSql="UPDATE wg_allies SET point=(point + $deltaAttack) WHERE id=$allyAttackId";			
			$db->setQuery($updSql);		
			if(!$db->query()){
				globalError2("executeAllyEffect deltaAttack ".$updSql);
			}
		} 
	}	
	
	$deltaDefend = (int)($attackDiePoint - $defendDiePoint);	
	if($deltaDefend!=0){ // Khi khac 0 thi moi cap nhat Hieu suat
		$allyDefendId = findJoinedAlly(0, $mainVillageDefend);			
		if($allyDefendId){ // Ben attack co tham gia lien minh		
			$updSql="UPDATE wg_allies SET point=(point + $deltaDefend) WHERE id=$allyDefendId";					
			$db->setQuery($updSql);		
			if(!$db->query()){
				globalError2("executeAllyEffect deltaDefend ".$updSql);
			}
		} 
	}	
}

/**
* @Author: ManhHX
* @Des: find user has joined ally
* @param: $uId: user id; $vId village id
* @return: null
*/
function findJoinedAlly($uId=0, $vId=0){	
	global $db;	
	
	if($uId){
		$query="SELECT ally_id FROM wg_ally_members WHERE user_id=".$uId ." AND right_=1";			
		$db->setQuery($query);		
		$db->loadObject($objAlly);		
		if($objAlly){
			return $objAlly->ally_id;
		}else{
			return false;
		}
	}
	else{
		$query="SELECT user_id FROM wg_villages WHERE id=".$vId;		
		$db->setQuery($query);		
		$db->loadObject($objVillage);
		
		$query="SELECT ally_id FROM wg_ally_members WHERE user_id=".$objVillage->user_id;	
		$query.=" AND right_=1";		
		$db->setQuery($query);
		$objAlly = null;
		$db->loadObject($objAlly);
		
		if($objAlly){
			return $objAlly->ally_id;
		}else{
			return false;
		}
	}
}

/**
* @Author: ManhHX
* @Des: effect attack
* @param: $arrAttacks attack info; $arrDefs defends info
* @return: null
*/
function executeUserAttackPoint($arrAttacks, $arrDefs, $heroDefendList){	
	global $db, $user;
		
	foreach($arrAttacks as $kAtt =>$vAtt){
		//Lay user id cua ben tan cong cua moi thanh trong defends
		$query="SELECT user_id FROM wg_villages WHERE id=$kAtt";		
		$db->setQuery($query);
		$objAtt = null;
		$db->loadObject($objAtt);		
	}
	
	$arrTroops = array();
	foreach($arrDefs as $kDefend =>$vDefend){
		//Lay nation cua moi thanh trong defends
		$query="SELECT nation_id FROM wg_villages WHERE id=$kDefend";		
		$db->setQuery($query);
		$objVillage = null;
		$db->loadObject($objVillage);
		$arrTroops[$kDefend] = getTroopsByNationId($objVillage->nation_id);		
	}
			
	$soldierKeep = 0;	
	$soldierKeepDie = 0;
	$arrSoldierKeep = array();
	$productionOfHero = 0;
	if($arrTroops){
		foreach($arrDefs as $kDefend =>$vDefend){ //Voi moi thanh co linh bi tan cong			
			foreach($arrTroops[$kDefend] as $index1 => $troop){ // Loai linh cua thanh do
				foreach($vDefend as $troopVId => $troopVItem){												
					if( ($troop->id == $troopVItem["id"]) && $troopVItem["num"] ){	
						$soldierKeepDie+= $troopVItem["die_num"] * $troop->keep_hour;		
						$soldierKeep+= $troopVItem["num"] * $troop->keep_hour;	
						$arrSoldierKeep[$kDefend]+= $troopVItem["die_num"] * $troop->keep_hour;				
						break;
					}			
				}				
			} // Endif loai linh cua thanh do
		} //Endif voi moi thanh co linh bi tan cong
	}
	
	if($heroDefendList){ // Co hero thi cong them so bat com theo hero
		foreach($heroDefendList as $kHero =>$vHero){
			$soldierKeepDie+= $vHero->die_num * $vHero->keep_hour;
			$soldierKeep+= $vHero->num * $vHero->keep_hour;
			$arrSoldierKeep[$kHero]+= $vHero->die_num * $vHero->keep_hour;
		}
	}
						
	$arrEffectAVillage=array(); // Moi thanh phong thu chiem bao nhieu % thu
	$arrEffectAVillage[0]=$soldierKeepDie;
	if($soldierKeepDie){
		foreach($arrSoldierKeep as $k => $v){
			$arrEffectAVillage[$k] = ($v/$soldierKeepDie)*100;
		}				
				
		$updSql="UPDATE wg_users SET attack_point=(attack_point + $soldierKeepDie) WHERE id=".$objAtt->user_id;					
		$db->setQuery($updSql);		
		if(!$db->query()){
			globalError2("executeUserAttackPoint ".$updSql);
		}
		if($objAtt->user_id == $user['id'])	{	
			$user["attack_point"]+=$soldierKeepDie;			
		}
		//week ranking	
		if(!updateInfoTop10($objAtt->user_id, "attack_point", $soldierKeepDie)){
			globalError2("updateInfoTop10 attack_point");
		}
	}
	return $arrEffectAVillage;
}

/**
* @Author: ManhHX
* @Des: effect defend
* @param: $arrAttacks attack info; $arrDefs defends info
* $arrEffectAVillage array defend effect
* @return: null
*/
function executeUserDefendPoint($arrAttacks, $heroAttack, $arrDefs, $arrEffectAVillage){	
	global $db, $user;
	
	$arrTroops = array();
	foreach($arrAttacks as $kAttack =>$vAttack){
		//Lay nation cua moi thanh trong defends
		$query="SELECT nation_id FROM wg_villages WHERE id=$kAttack";		
		$db->setQuery($query);
		$objVillage = null;
		$db->loadObject($objVillage);
		$arrTroops = getTroopsByNationId($objVillage->nation_id);		
	}
	
	$soldierKeepDie=0;		
	$soldierKeep = 0;			
	$productionOfHero = 0;
	if($arrTroops){
		foreach($arrAttacks as $kAttack =>$vAttack){ //Voi thanh di tan cong			
			foreach($arrTroops as $index1 => $troop){ // Loai linh cua thanh do
				foreach($vAttack as $troopVId => $troopVItem){													
					if(($troop->id == $troopVItem["id"]) && $troopVItem["num"]){			
						$soldierKeep+= $troopVItem["num"] * $troop->keep_hour;	
						$soldierKeepDie+= $troopVItem["die_num"] * $troop->keep_hour;				
						break;
					}			
				}				
			} // Endif loai linh cua thanh do
		} //Endif voi thanh di tan cong
	}
	
	if($heroAttack){ // Co hero thi cong them so bat com theo hero		
		$soldierKeep+= $heroAttack->die_num * $heroAttack->keep_hour;
		$soldierKeepDie+= $heroAttack->die_num * $heroAttack->keep_hour;			
	}
	
	if($soldierKeepDie){
		foreach($arrEffectAVillage as $k => $v){
			if($k){
				$query="SELECT user_id FROM wg_villages WHERE id=".$k;		
				$db->setQuery($query);	
				$objVillage = null;	
				$db->loadObject($objVillage);
				
				$aKeepPoint = round(($v*$soldierKeepDie)/100);		
				$updSql="UPDATE wg_users SET defend_point=(defend_point + $aKeepPoint) WHERE id=".$objVillage->user_id;					
				$db->setQuery($updSql);		
				if(!$db->query()){
					globalError2("executeUserDefendPoint ".$updSql);
				}
				if($objVillage->user_id == $user['id'])	{	
					$user["defend_point"]+=$aKeepPoint;			
				}
				//week ranking	
				if($aKeepPoint >0)
				{			
					if(!updateInfoTop10($objVillage->user_id, "defend_point", $aKeepPoint)){
						globalError2("updateInfoTop10 defend_point");
					}
				}
			}					
		}	
	}
	return $soldierKeepDie;
}


/**
* @Author: ManhHX
* @Des: tinh diem cho ben tan cong bo lac
* @param: $arrAttacks attack info; $heroAttack attack hero info
* $oasisTroopKeepDie So bat com thu hoang chet
* @return: null
*/
function executeAllyAttackOasis($arrAttacks, $heroAttack, $oasisTroopKeepDie){	
	global $db, $user;
	
	$mainAttVillageId=0;
	$arrTroops = array();
	foreach($arrAttacks as $kAttack =>$vAttack){
		//Lay nation cua thanh tan cong
		$mainAttVillageId=$kAttack;
		$query="SELECT nation_id FROM wg_villages WHERE id=$kAttack";		
		$db->setQuery($query);
		$objVillage = null;
		$db->loadObject($objVillage);
		$arrTroops = getTroopsByNationId($objVillage->nation_id);		
	}
	
	$soldierKeepDie=0;		
	$soldierKeep = 0;			
	$productionOfHero = 0;
	if($arrTroops){
		foreach($arrAttacks as $kAttack =>$vAttack){ //Voi thanh di tan cong			
			foreach($arrTroops as $index1 => $troop){ // Loai linh cua thanh do
				foreach($vAttack as $troopVId => $troopVItem){													
					if(($troop->id == $troopVItem["id"]) && $troopVItem["num"]){			
						$soldierKeep+= $troopVItem["num"] * $troop->keep_hour;	
						$soldierKeepDie+= $troopVItem["die_num"] * $troop->keep_hour;				
						break;
					}			
				}				
			} // Endif loai linh cua thanh do
		} //Endif voi thanh di tan cong
	}
	
	if($heroAttack){ // Co hero thi cong them so bat com theo hero			
		$soldierKeepDie+= $heroAttack->die_num * $heroAttack->keep_hour;			
	}	
	
	$deltaAttack = (int)($oasisTroopKeepDie - $soldierKeepDie);	
	if($deltaAttack!=0){ // Khi khac 0 thi moi cap nhat Hieu suat
		$allyAttackId = findJoinedAlly(0, $mainAttVillageId);			
		if($allyAttackId){ // Ben attack co tham gia lien minh		
			$updSql="UPDATE wg_allies SET point=(point + $deltaAttack) WHERE id=$allyAttackId";			
			$db->setQuery($updSql);		
			if(!$db->query()){
				globalError2("executeAllyAttackOasis deltaAttack ".$updSql);
			}
		} 
	}	
}


/**
* @Author: ManhHX
* @Des: effect attack oasis
* @param: $arrAttacks attack info; $arrDefs defends info
* @return: null
*/
function executeUserAttackOasisPoint($arrAttacks, $arrDefs){	
	global $db, $user;
		
	foreach($arrAttacks as $kAtt =>$vAtt){
		//Lay user id cua ben tan cong cua moi thanh trong defends
		$query="SELECT user_id FROM wg_villages WHERE id=$kAtt";		
		$db->setQuery($query);
		$objAtt = null;
		$db->loadObject($objAtt);		
	}
				
	$oasisTroopKeep = 0;	
	$oasisTroopKeepDie = 0;	
	if($arrDefs){
		foreach($arrDefs as $kDefend =>$vDefend){ 		
			foreach($vDefend as $troopVId => $troop){														
				if( $troop["die_num"]){	
					$oasisTroopKeepDie+= $troop["die_num"] * $troop["keep_hour"];		
					$oasisTroopKeep+= $troop["num"] * $troop["keep_hour"];
				}							
			} 
		} 
	}								
	
	if($oasisTroopKeepDie){				
		$updSql="UPDATE wg_users SET attack_point=(attack_point + $oasisTroopKeepDie) WHERE id=".$objAtt->user_id;					
		$db->setQuery($updSql);		
		if(!$db->query()){
			globalError2("executeUserAttackOasisPoint ".$updSql);
		}
		if($objAtt->user_id == $user['id'])	{	
			$user["attack_point"]+=$oasisTroopKeepDie;			
		}
		//week ranking		
		if(!updateInfoTop10($objAtt->user_id, "attack_point", $oasisTroopKeepDie)){
			globalError2("updateInfoTop10 attack_point oasis");
		}
	}
	return 	$oasisTroopKeepDie;
}


/**
 * @author Le Van Tu
 * @des Gui am binh danh thanh co ky dai
 */
function sendAttackWonder($village_id, $time){
	global $game_config;
	$cost_time = $game_config['cost_time_att_wonder'];
	$object_id=InsertAttack(0, $village_id, 12, 37);
	
	InsertStatus(0, $object_id, date("Y-m-d H:i:s", $time), date("Y-m-d H:i:s", $time+$cost_time), $cost_time, 7, 3);
}
/**
 * @author Le Van Tu
 * @des xu ly danh ky dai
 */
function attackWonder($attack, $status){
	include_once("function_plus.php");
	
	global $db, $lang, $game_config;
	includelang("attack");
	
	SetAttackStatus($attack->id);
	
	$vlgDf=getVillage($attack->village_defend_id, "`id`, `name`, `x`, `y`, `kind_id`, `user_id`, `rs1`, `rs2`, `rs3`, `rs4`, `time_update_rs1`, `time_update_rs2`, `time_update_rs3`, `time_update_rs4`, `nation_id`, `child_id`, `krs1`, `krs2`, `krs3`, `krs4`, `faith`, `faith_time`");

	//update linh cho lang bi danh:
	updateTrainTroopStatus($vlgDf, strtotime($status->time_end));
	
	$arrTrp=getArrayOfTroops();
	
	$attTrpLst=gnAmBinh($vlgDf);
	
	//echo "<pre>";print_r($attTrpLst);
	
	$dfTrpLst=getDefendTroop($attack, $arrTrp);
	//echo "<pre>"; print_r($dfTrpLst); die();
	$hrDfLst=getDefendHeroList($attack);
	
	$attInf=getAmBinhSideInfo($attTrpLst);
	$dfInf=getDefendSideInfo($dfTrpLst, $hrDfLst, $attInf, $vlgDf, $attack->time_end);
	
	//echo "<pre>";print_r($attInf);print_r($dfInf);die("OK");
	
	//Tinh so linh chet moi ben:
	$attResult=phaseAttWonder($attInf['sumAtt'], $dfInf['sumDf'], $attInf['sumTrp'], $dfInf['sumTrp']);
	
	$ktRslt=amBinhKata($vlgDf->id, $attInf['kt'], $attInf['sumAtt'], $dfInf['sumDf']);
	
	
	$hrInf=array('hp1'=>0, 'hp2'=>0, 'p1'=>0, 'p2'=>0);
	$attTrpLst=getAmBinhDie($attTrpLst, $attInf['sumTrp'], $attResult['att'], $hrInf);
	$dfTrpLst=getTroopDefendDie($dfTrpLst, $dfInf['sumTrp'], $attResult['df'], $hrInf, $attInf['sumAtt'], $dfInf['sumDf']);
	
	$hrDfLst = getHeroDefendAfter($hrDfLst, $hrInf);
	
	reportAttckWonder($vlgDf, $attTrpLst, $dfTrpLst, $hrDfLst, $ktRslt, $status->time_end);
	
	//cap nhat so linh chet xuong database:
	updateTroopDefend($dfTrpLst, $vlgDf, $arrTrp);
		
	updateHeroDefend($hrDfLst, $vlgDf);
	
	//cap nhat cho lang bi danh:
	unsetVillageParam($vlgDf);
	$db->updateObject("wg_villages", $vlgDf, "id");
}

/**
 * @author Le Van Tu
 * @des lay danh sach am binh
 */
function getAmBinhAttack($attack){
	global $db;
	$result=array();
	$sql="SELECT
				wg_am_binh.id,
				wg_am_binh.`type`,
				wg_am_binh.attack,
				wg_attack_troop.num
			FROM
				wg_am_binh ,
				wg_attack_troop
			WHERE
				wg_attack_troop.troop_id =  wg_am_binh.id AND
				wg_attack_troop.attack_id =  '$attack->id' 
			GROUP BY
				wg_am_binh.id";
	$db->setQuery($sql);
	$listTroop=$db->loadObjectList();
	if($listTroop){
		foreach($listTroop as $troop){
			$result[0][$troop->id]['attack']=$troop->attack;
			$result[0][$troop->id]['num']=$troop->num;
			$result[0][$troop->id]['keep_hour']=$troop->keep_hour;
		}
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des Tinh so linh chet moi ben
 */
function phaseAttWonder($sumAtt, $sumDf, $sumAttTrp, $sumDfTrp){
	$attDie	=0;
	$dfDie	=0;
	
	calculateCasualtie($sumAtt, $sumDf, $sumAttTrp, $sumDfTrp, $attDie, $dfDie, 4);
	
	$result=array('att'=>$attDie, 'df'=>$dfDie);
	return $result;
}

/**
 * @author Le Van Tu
 * @des Lay cac thong so ben cong
 */
function getTroopAttInfo($trpLst){
	$kt		=	0;	//suc cong pha cua xe ban da
	$smTrp	=	0;	//so linh
	$smKpH	=	0;	//luong thuc tieu thu
	$smMl	=	0;	//suc cong cua quan bo
	$smMg	=	0;	//suc cong cua quan ngua
	$smRg	=	0;	//suc cong cua quan cung
	$smAtt	=	0;	//tong suc cong
	if($trpLst){
		foreach($trpLst as $trps){
			foreach($trps as $id=>$trp){
				$smLf=($trp['num']-$trp['die_num']);; //so linh con song
				$smTrp += $smLf;
				$smKpH += $trp['keep_hour']*$smLf;
				switch($trp['type']){
					case 1:
						//bo					
						//Tinh tong suc cong cua kata neu co:
						if($trp['id']==8){					
							$kt+=$trp['attack'] * $smLf;
							$smMl += $trp['attack']*$smLf/3;
						}else{
							$smMl += $trp['attack']*$smLf;
						}
						break;
					case 2:
						//Ngua
						$smMg += $trp['attack']*$smLf;
						break;
					case 3:
						//cung
						$smRg += $trp['attack']*$smLf;
						break;
				}
			}			
		}
		
		//Tong cng.	
		$smAtt=$smMl+$smMg+$smRg;
	}
	$result=array('kt'=>$kt, 'sumAtt'=>$smAtt, 'sumMl'=>$smMl, 'smMg'=>$smMg, 'smRg'=>$smRg, 'kpH'=>$smKpH, 'sumTrp'=>$smTrp);
	return $result;
}



/**
 * @author Le Van Tu
 * @des Lay cac thong so ben thu
 */
function getTroopDefendInfo($trpDfLst, $attSdInf){
	$ml_Ml=0;
	$ml_Rg=0;
	$ml_Mg=0;	//Tong suc phong thu cua quan bo doi voi cung.
	
	$mg_Ml=0;
	$mg_Rg=0;
	$mg_Mg=0;	//Tong suc phong thu cua quan ngua doi voi bo.
	
	$rg_Ml=0;
	$rg_Rg=0;
	$rg_Mg=0;	//Tong suc phong thu cua quan cung do voi ngua.
	
	$hrMl=0;
	$hrMg=0;	//suc thu cua hero
	$hrRg=0;
	
	$smKpH=0;	//Tong luong luong thuc tieu thu
	$smHr=0;	//Tong so hero ben thu
	$smTrp=0;	//Tong so linh ben thu
	
	if($trpDfLst){
		foreach($trpDfLst as $trps){
			foreach($trps as $id=>$trp){
				$smLf=$trp['num']-$trp['die_num'];
				$smTrp += $smLf;
				$smKpH += $trp['keep_hour']*$smLf;
				switch($trp['type']){
					case 1:
						$ml_Rg += $trp['ranger_defense']*$smLf;
						$ml_Mg += $trp['magic_defense']*$smLf;
						$ml_Ml += $trp['melee_defense']*$smLf;						
						break;
					case 2:
						$mg_Ml += $trp['melee_defense']*$smLf;
						$mg_Rg += $trp['ranger_defense']*$smLf;
						$mg_Mg += $trp['magic_defense']*$smLf;						
						break;
					case 3:
						$rg_Mg += $trp['magic_defense']*$smLf;
						$rg_Ml += $trp['melee_defense']*$smLf;
						$rg_Rg += $trp['ranger_defense']*$smLf;
						break;
				}
			}					
		}				
	}
	
	$sumDfMl	= $ml_Ml+$mg_Ml+$rg_Ml;
	$sumDfMg	= $ml_Mg+$mg_Mg+$rg_Mg;
	$sumDfRg	= $ml_Rg+$mg_Rg+$rg_Rg;
	
	$pcMl	= $attSdInf['sumMl']/$attSdInf['sumAtt'];	//Phan tram cong cua quan bo
	$pcMg	= $attSdInf['smMg']/$attSdInf['sumAtt'];	//Phan tram cong cua quan bo
	$pcRg	= $attSdInf['smRg']/$attSdInf['sumAtt'];	//Phan tram cong cua quan bo
	
	$dfMl	= $pcMl*$sumDfMl;
	$dfMg	= $pcMg*$sumDfMg;
	$dfRg	= $pcRg*$sumDfRg;
	
	$sumDf=$dfMl+$dfMg+$dfRg;
	
	$result=array('sumDf'=>$sumDf, 'sumTrp'=>$smTrp, 'sumKpH'=>$smKpH);
	return $result;
}

/**
 * @author Le Van Tu
 * @des lay cao thong so cua tuong ben thu
 */
function getHeroDefendInfo($hrLst, $attSdInf){
	$ml	=0;
	$mg	=0;
	$rg	=0;
	$sum=0;
	$tst=0;
	
	if($hrLst){
		foreach($hrLst as $hr){
			if($hr->num > $hr->die_num){
				$ml += $heroDefend->melee_defense;
				$mg += $heroDefend->magic_defense;
				$rg += $heroDefend->ranger_defense;
				$tst+=$heroDefend->tuong_sinh_thu;
				$sum++;
			}
		}
	}
	
	$pcMl	= $attSdInf['sumMl']/$attSdInf['sumAtt'];	//Phan tram cong cua quan bo
	$pcMg	= $attSdInf['smMg']/$attSdInf['sumAtt'];	//Phan tram cong cua quan bo
	$pcRg	= $attSdInf['smRg']/$attSdInf['sumAtt'];	//Phan tram cong cua quan bo
	
	$ml	*= $pcMl;
	$mg	*= $pcMg;
	$rg	*= $pcRg;
	
	$df=$ml+$mg+$rg;
	
	$result = array('df'=>$df, 'tst'=>$tst, 'sum'=>$sum);
	
	return $result;
}

/**
 * @author Le Van Tu
 * @des Tinh toan so linh danh ky dai
 * @return array (object)
 */
function gnAmBinh(&$vllgDf){
	global $db, $game_config;
	$amBinhLst=getListAmBinh();
	$wdLvl=GetBuildingLevel($vllgDf->id, 37);
	
	$kt	= 	200 * $wdLvl;
	$kt += 	200 * ($wdLvl-1);
	
	$a=getWorkerVillage($vllgDf->id);
	
	$b=$game_config['k_am_binh']*$a/8;
	
	for($i=0; $i<7; $i++){
		$amBinhLst[$i]->sum=rand($a/8, $b);
	}
	
	$amBinhLst[7]->sum = round($kt/$amBinhLst[7]->attack, 0);
		
	$amBinhLst[8]->sum=1;	//so linh loai 10
	$amBinhLst[9]->sum=1;	//so linh loai 11
	
	return $amBinhLst;
}

/**
 * @author Le Van Tu
 * @todo Lay worker cua thanh
 */
function getWorkerVillage($vl_id){
	global $db;
	$sql = "SELECT workers FROM wg_villages WHERE id=$vl_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @des Lay thong tin linh am binh
 * @return array of object
 */
function getListAmBinh(){
	global $db;
	$sql="SELECT * FROM wg_am_binh";
	$db->setQuery($sql);
	return $db->loadObjectList();
}

/**
 * @author Le Van Tu
 * @des Lay thong tin am binh de tinh toan attack
 * @return array int
 */
function getAmBinhSideInfo($amBinhLst){
	$smTrp	=0;
	$smKpH	=0;
	$smAtt	=0;
	$smMl	=0;
	$smMg	=0;
	$smRg	=0;
	$kt		=0;
	
	foreach($amBinhLst as $amBinh){
		$smTrp += $amBinh->sum;
		$smKpH += $amBinh->keep_hour * $smTrp;
		switch($amBinh->type){
			case 1:
				if($amBinh->id==8){
					$kt = $amBinh->sum*$amBinh->attack;
					$smMl += ($amBinh->sum*$amBinh->attack)/3;
				}else{
					$smMl+=$amBinh->sum*$amBinh->attack;
				}				
				break;
			case 2:
				$smMg+=$amBinh->sum*$amBinh->attack;
				break;
			case 3:
				$smRg+=$amBinh->sum*$amBinh->attack;
				break;
		}
	}
	
	$smAtt = $smMl+$smMg+$smRg;	
	
	$result=array('kt'=>$kt, 'sumAtt'=>$smAtt, 'sumMl'=>$smMl, 'smMg'=>$smMg, 'smRg'=>$smRg, 'kpH'=>$smKpH, 'sumTrp'=>$smTrp);
	return $result;
}

/**
 * @author Le Van Tu
 * @des Lay % thu cua tuong thanh
 */
function getWallBonus($vlId){
	$wallLevel=GetBuildingLevel($vlId, 36);
	return $wallLevel>0?($wallLevel*0.05):0;
}

/**
 * @author Le Van tu
 * @des Cap nhat linh cua bo lac
 * @param array[ array [object]] 
 */
function updateOasisTroop($oasis, $troopList, $time_end, $faith, $coRc, $chkFd){
	if($chkFd==1){
		//da chiem duoc bo lac
		deleteOasTrp($oasis->id);
	}else{
		$str="";	
		foreach($troopList as $vlId=>$trps){
			foreach($trps as $trp){
				$str .= $trp['num'] - $trp['die_num'].",";
			}
		}
	
		if($coRc){
			updateOasTrpAtt($vlId, $str, $time_end, $faith);
		}else{
			insertOasisTroop($oasis->id, $str, $time_end, $faith);
		}
	}
}

/**
 * @author Le Van Tu
 * @des Lay % tang cong cua 1 lang co su dung plus
 */
function getAttackPlusBonus($user_id, $time){
	$bonus=getAttDefPlus($user_id, $time);
	if($bonus==1){
		return 0.1;
	}else{
		return 0;
	}
}

/**
 * @author Le Van Tu
 * @des Lay % tang thu cua 1 lang co su dung plus
 */
function getDefendPlusBonus($user_id, $time){
	$bonus=getAttDefPlus($user_id, $time);
	if($bonus==1){
		return 0.1;
	}else{
		return 0;
	}
}


/**
 * @author Le Van Tu
 * @des Lay thong tin bn cng de chuan bi cho tinh so linh chet moi ben
 */
function getAttackSideInfo($trpLst){
	$result=getTroopAttInfo($trpLst);
//	$plsBn=getat
	return $result;
}

/**
 * @author Le Van Tu
 * @des Lay thong tin bn cng de chuan bi cho tinh so linh chet moi ben
 */
function getDefendSideInfo($trpLst, $hrLst, $attSdInf, $vlgdf, $time){
	$result=getTroopDefendInfo($trpLst, $attSdInf);
	
	$hrIfo=getHeroDefendInfo($hrLst, $attSdInf);	
	$plsBn=getDefendPlusBonus($vlgdf->user_id, $time);
	$wllBn=getWallBonus($vlgdf->id);
	
	$result['sumDf'] *= (1+$plsBn+$wllBn+$hrIfo['tst']);
	$result['sumDf'] += $hrIfo['df'];
	
	$result['sumHr'] = $hrIfo['sum'];
	
	return $result;
}

/**
 * @author Le Van Tu
 * @des Tinh so linh chet ung voi moi loai quan
 */
function getTroopDefendDie($arrTrp, $sum, $sumDie, &$hrInf, $sumAtt, $sumDf){
	if($arrTrp){
		foreach($arrTrp as &$trpLst){
			foreach($trpLst as &$trp){
				if($sum==$sumDie){
					$trp['die_num']=$trp['num'];
				}else{
					$pc=$trp['num']/$sum;
					$trp['die_num']=round($pc*$sumDie, 0);
				}
				
				$kpH += $trp['num'] * $trp['keep_hour'];
				$kpHDie += $trp['die_num'] * $trp['keep_hour'];
			}
			if($kpH>0){
				$hrInf['hp2'] = ($kpHDie/$kpH) * 100;
			}else{
				if($sumAtt>$sumDf){
					$hrInf['hp2'] = 100;
				}else{
					$hrInf['hp2'] = ($sumAtt/$sumDf)*100;
				}				
			}
			
			$hrInf['p1'] = $kpHDie;
		}
	}
	return $arrTrp;
}

/**
 * @author Le Van Tu
 * @des Tinh so linh chet ung voi moi loai quan (am binh)
 */
function getAmBinhDie($arrTrp, $sum, $sumDie, &$hrInf){
	if($arrTrp){
		foreach($arrTrp as &$trp){
			if($sum==$sumDie){
				$trp->die=$trp->sum;
			}else{
				$pc=$trp->sum/$sum;
				$trp->die=round($pc*$sumDie, 0);
			}
			
			$hrInf['p2'] += $trp->die * $trp->keep_hour;
		}
	}
	return $arrTrp;
}

/**
 * @author Le Van Tu
 * @des Am binh danh ky dai
 */
function amBinhKata($vlg_id, $kt, $att, $df){
	global $lang;
	$result = "";
	$count	=0;
	$wndr=getWonder($vlg_id);
	if($wndr){
		if($df>0){
			$kt	*= min($att/$df, 1);
		}
		
		while(true){
			$hp=$wndr->level * 200;
			if($kt >= $hp){
				destroyByCata($wndr->id, 37, $wndr->level, $vlg_id);
				$kt -= $hp;
				$wndr->level --;
				$count ++;
			}else{
				break;
			}
		}
		
		if($count>0){
			if($wndr>0){
				$result=GetLangBuildingName($wndr->name)." ".$lang['giam']." ".$count." ".$lang['level'];	 
			}else{
				$result=GetLangBuildingName($wndr->name)." ".$lang['level']." ".$wndr->level." ".$lang['bi_pha_huy']; 
			}
		}
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des lay thong tin ky dai cua mot lang:
 */
function getWonder($vlg_id){
	global $db;
	$sql="SELECT id, name, level, type_id FROM wg_buildings	WHERE level>0 AND type_id = 37	AND vila_id=$vlg_id";
	$db->setQuery($sql);
	$db->loadObject($bld);
	return $bld;
}

/**
 * @author Le Van Tu
 * @des tao report cho danh am binh
 */
function reportAttckWonder($vlgDf, $arrAttTrp, $arrDfTrp, $arHrDf, $ktRslt, $time_end){
	include_once("function_allian.php");
	global $lang;
	$parse=$lang;
	includelang("troop");
	
	$amBinh=getAmBinh();
	
	for($i=0; $i<10; $i++){
		$sum = $arrAttTrp[$i]->sum;
		$die = 	$arrAttTrp[$i]->die;
				
		if($sum>0){
			$parse['st_'.($i+1)] = $sum ;
			$parse['class_'.($i+1)]="";
		}
		else{
			$parse['st_'.($i+1)] = 0;
			$parse['class_'.($i+1)]="c";
		}
		
		if($die>0){
			$parse['casualties_'.($i+1)] = $die ;
			$parse['class_ca_'.($i+1)]="";
		}
		else{
			$parse['casualties_'.($i+1)] = 0;
			$parse['class_ca_'.($i+1)]="c";
		}
								
		$parse['icon_'.($i+1)]=$arrAttTrp[$i]->icon;		
		$parse['title_'.($i+1)]=$lang[$arrAttTrp[$i]->name];
	}
	
	if($ktRslt){
		$parse['title'] = $lang['cong_trinh'];
		$parse['text']=$ktRslt;
		$parse['info_rows'].=parsetemplate(gettemplate("report_info_row"), $parse);	
	}else{
		$parse['info_rows'] ='';
	}
		
	$parse['class_side']="c2 b";
	$parse['side']=$lang['Attacker'];
	$parse['player_name']=GetPlayerName($villageAttack->id);
	$parse['user_id']=$villageAttack->user_id;
	$parse['x']=$villageAttack->x;
	$parse['y']=$villageAttack->y;
	$parse['village_name']=$villageAttack->name;
	$attackTable=parsetemplate(gettemplate("report_am_binh_table"), $parse);
	
	
	//tao cac bang quan phong thu:
	if(count($arrDfTrp)>0){
		foreach($arrDfTrp as $vlg_id=>$trpDf){
			if($vlg_id==$vlgDf->id){
				$parse['side']=$lang['Defender'];
				$village=$vlgDf;
				$lstTrpVlg=GetListTroopVilla($vlgDf);
			}else{
				$parse['side']=$lang['Sender'];
				$village=getVillage($vlg_id);
				$lstTrpVlg=GetListTroopVilla($vlgRnfrc);
			}
			
		
			for($i=0; $i<11; $i++){	
				$sum = $trpDf[$lstTrpVlg[$i]->id]['num'];
				$die = $trpDf[$lstTrpVlg[$i]->id]['die_num'];
						
				if($sum>0){
					$parse['st_'.($i+1)] = $sum ;
					$parse['class_'.($i+1)]="";
				}
				else{
					$parse['st_'.($i+1)] = 0;
					$parse['class_'.($i+1)]="c";
				}
				
				if($die>0){
					$parse['casualties_'.($i+1)] = $die ;
					$parse['class_ca_'.($i+1)]="";
				}
				else{
					$parse['casualties_'.($i+1)] = 0;
					$parse['class_ca_'.($i+1)]="c";
				}
										
				$parse['icon_'.($i+1)]=$lstTrpVlg[$i]->icon;		
				$parse['title_'.($i+1)]=$lang[$lstTrpVlg[$i]->name];		
			}
			
			$hrDf=$arHrDf[$village->id];
			if($hrDf){
				$parse['st_12']=1;
				$parse['class_12']="";
				if($hrDf->die_num>0){
					$parse['casualties_12']=1;
					$parse['class_ca_12']="";
				}else{
					$numAttSoldier = $numAttSoldier + 1;
					$parse['casualties_12']=0;
					$parse['class_ca_12']="c";
				}
				$arHrDf[$village->id]=null;
			}else{
				$parse['st_12']=0;
				$parse['class_12']="c";
				$parse['casualties_12']=0;
				$parse['class_ca_12']="c";
			}
			$parse['icon_12']="images/icon/hero4.ico";
			$parse['title_12']=$lang['hero'];
			
			$parse['info_rows'] ='';
			
			$parse['class_side']="c1 b";
			$parse['info_rows']='';
			
			$parse['player_name']=GetPlayerName($village->id);
			$parse['village_name']=$village->name;
			$parse['user_id']=$village->user_id;
			$parse['x']=$village->x;
			$parse['y']=$village->y;
			$dfTable=parsetemplate(gettemplate("report_village_troop_table"), $parse);
			$dfTables.=$dfTable;
			if($vlg_id != $vlgDf->id){
				$reinforceReportList[$village->user_id]=$dfTable;
			}
		}
	}
	
	//Tao bang nhung hero di mot minh:
	if(count($arHrDf)>0){
		foreach($arHrDf as $vlg_id=>$hrDf){
			if($hrDf){
				if($vlg_id==$vlgDf->id){
					$parse['side']=$lang['Defender'];
					$village=$vlgDf;
					$lstTrpVlg=GetListTroopVilla($vlgDf);
				}else{
					$parse['side']=$lang['Sender'];
					$village=getVillage($vlg_id);
					$lstTrpVlg=GetListTroopVilla($vlgRnfrc);
				}
							
				for($i=0; $i<11; $i++){
					$parse['st_'.($i+1)] = 0;
					$parse['class_'.($i+1)]="c";
					
					$parse['casualties_'.($i+1)] = 0;
					$parse['class_ca_'.($i+1)]="c";
											
					$parse['icon_'.($i+1)]=$lstTrpVlg[$i]->icon;		
					$parse['title_'.($i+1)]=$lang[$lstTrpVlg[$i]->name];		
				}
				
				$parse['st_12']=1;
				$parse['class_12']="";
				if($heroDefend->die_num>0){
					$parse['casualties_12']=1;
					$parse['class_ca_12']="";
				}else{
					$numAttSoldier = $numAttSoldier + 1;
					$parse['casualties_12']=0;
					$parse['class_ca_12']="c";
				}
				$parse['icon_12']="images/icon/hero4.ico";
				$parse['title_12']=$lang['hero'];
				
				$parse['info_rows'] ='';
				
				$parse['class_side']="c1 b";
				
				$parse['info_rows']='';
				
				$parse['player_name']=GetPlayerName($village->id);
				$parse['village_name']=$village->name;
				$parse['user_id']=$village->user_id;
				$parse['x']=$village->x;
				$parse['y']=$village->y;
				$dfTable=parsetemplate(gettemplate("report_village_troop_table"), $parse);
				$dfTables.=$dfTable;
				if($vlg_id != $vlgDf->id){
					$reinforceReportList[$village->user_id]=$dfTable;
				}
			}				
		}
	}
	
	$content=$attackTable."<br>".$dfTables;	
	
	$reportTitle=$lang['am_binh'].' '.$lang['tu_chien'].' '.$vlgDf->name;
	
	$adminId=getUserIdByUserName("Admin");
		
	InsertReport($adminId, $reportTitle, $time_end, $content, REPORT_ATTACK);
	InsertReport($vlgDf->user_id, $reportTitle, $time_end, $content, REPORT_ATTACK);
	
	if(count($reinforceReportList)>0){
		$reportTitle=$lang['reinforce_title_1'].' '.$vlgDf->name.' '.$lang['giup_do2'];
		foreach($reinforceReportList as $user_id=>$content){
			InsertReport($user_id, $reportTitle, $time_end, $content, REPORT_ATTACK);
		}
	}
}


/**
 * @author Le Van Tu
 * @des Tinh toan cac thong so cua tuong ben thu sau khi danh
 */
function getHeroDefendAfter(&$hrDfLst, $hrInf){
	if($hrDfLst){
		$sumHr=count($hrDfLst);
		$hpD=round($hrInf['hp2']/$sumHr, 0);	//Luong mau bi mat
		$p=round($hrInf['p2']/$sumHr, 0);
		foreach($hrDfLst as &$hr){
			$hr->kinh_nghiem += $p;
			if($hr->hitpoint > $hpD){
				$hr->hitpoint -= $hpD;				
			}else{
				$hr->status = -1;
				$hr->die_num =1;
			}
		}
	}
	return $hrDfLst;	
}

/**
 * @author Le Van Tu
 * @todo khoi tao lai thong tin linh cho thanh bi chiem
 */
function resetTroopInfo($vl_id, $nation_id){
	global $db;
	include_once("function_active.php");
	//xoa tat ca thong tin linh trong bang wg_troop_villa:
	$sql = "DELETE FROM wg_troop_villa WHERE village_id=$vl_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
	
	//xoa linh dang duoc dao tao:
	$sql = "UPDATE wg_troop_train SET wg_troop_train.status=1 WHERE wg_troop_train.status=0 AND village_id=$vl_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
	
	$sql = "UPDATE wg_status SET wg_status.status=1 WHERE (wg_status.type= 4 OR wg_status.type=12 OR wg_status.type=13 OR wg_status.type=20) AND wg_status.status=0 AND wg_status.village_id=$vl_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
	
	//xoa linh dang hanh quan:
	$sql = "SELECT wg_status.object_id FROM wg_status WHERE (wg_status.type= 7 OR wg_status.type=10 OR wg_status.type=11 OR wg_status.type=15) AND wg_status.status=0 AND wg_status.village_id=$vl_id";
	$db->setQuery($sql);
	$sts = $db->loadObjectList();
	if($sts){
		foreach($sts as $st){
			$sql = "UPDATE wg_attack SET wg_attack.status=1 WHERE id=$st->object_id";
			$db->setQuery($sql);
			if(!$db->query()){
				globalError2($sql);
			}
			
			$sql = "UPDATE wg_attack_troop SET wg_attack_troop.status=1 WHERE attack_id=$st->object_id";
			$db->setQuery($sql);
			if(!$db->query()){
				globalError2($sql);
			}
			
			//giet tuong:
			$sql = "SELECT hero_id FROM wg_attack_hero WHERE wg_attack_hero.status=1 AND attack_id=$st->object_id";
			$db->setQuery($sql);
			$db->loadObject($hr);
			if($hr){
				SetHeroStatus($hr->hero_id, -1);
			}
		}
		
		$sql = "UPDATE wg_status SET wg_status.status=1 WHERE (wg_status.type= 7 OR wg_status.type=10 OR wg_status.type=11 OR wg_status.type=15) AND wg_status.status=0 AND wg_status.village_id=$vl_id";
		$db->setQuery($sql);
		if(!$db->query()){
			globalError2($sql);
		}
	}
	
	//xoa linh dang o thanh khac:
	$sql = "SELECT id FROM wg_attack WHERE wg_attack.status=0 AND wg_attack.type=1  AND wg_attack.village_attack_id=$vl_id";
	$db->setQuery($sql);
	$ats = $db->loadObjectList();
	if($ats){
		$sql = "UPDATE wg_attack SET wg_attack.status=1 WHERE wg_attack.status=0 AND wg_attack.type=1  AND wg_attack.village_attack_id=$vl_id";
		$db->setQuery($sql);
		if(!$db->query()){
			globalError2($sql);
		}
		
		foreach($ats as $at){
			$sql = "UPDATE wg_attack_troop SET wg_attack_troop.status=1 WHERE attack_id=$at->id";
			$db->setQuery($sql);
			if(!$db->query()){
				globalError2($sql);
			}
			
			//giet tuong:
			$sql = "SELECT hero_id FROM wg_attack_hero WHERE wg_attack_hero.status=1 AND attack_id=$at->id";
			$db->setQuery($sql);
			$db->loadObject($hr);
			if($hr){
				SetHeroStatus($hr->hero_id, -1);
			}
		}
	}
	
	//xoa thong tin ve giap:
	$sql = "DELETE FROM wg_troop_armour WHERE village_id=$vl_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
	
	//xoa thong tin ve vu khi:
	$sql = "DELETE FROM wg_troop_items WHERE village_id=$vl_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
	
	//xoa thong tin nghien cuu linh:
	$sql = "DELETE FROM wg_troop_researched WHERE village_id=$vl_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
	
	//khoi tao lai troop_research:
	KhoiTaoTroopResearch($vl_id, $nation_id);
}

/**
 * @author Le Van Tu
 * @todo Tinh so linh chet moi ben
 * @param int $satl so linh chet ben cong
 * @param int $sdtl so linh chet ben thu
 */
function calculateCasualtie($sa, $sd, $sat, $sdt, &$satl, &$sdtl, $type){
	$satl = 0;
	$sdtl = 0;
	if($sa>0 && $sd>0){
		if($sa>$sd){
			$y		= $sd/$sa;
			$swt 	= $sat;
			$slt	= $sdt;
		}else{
			$y		= $sa/$sd;
			$swt	= $sdt;
			$slt 	= $sat;
		}
		
		$x = pow($y, 1.5);
		
		if($type==3 || $type==10){//truong hop dot kich:			
			$z = $x/($x+0.8);
			$swl = $swt*$z;
			$sll = $slt*(1-$z);
		}else{//Truong hop tu chien
			$swl = $swt*$x;
			$sll = $slt;
		}
		
		if($sa>$sd){
			$satl = $swl;
			$sdtl = $sll;
		}else{
			$satl = $sll;
			$sdtl = $swl;
		}
	}
}
?>
