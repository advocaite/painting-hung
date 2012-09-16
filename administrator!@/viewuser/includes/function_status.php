<?php

/**
 * @author Le Van Tu
 * @des them bao vat cho lang thang tran khi quan ve den thanh
 * @param status object
 */
function executeAttackGetRare($status){
	global $db;
	$rareSend=getRareSend($status->object_id);
	if($rareSend){
		setStatusRareSend($status->object_id);
		updateRare($rareSend->village_id_to, $rareSend->kim, $rareSend->thuy, $rareSend->moc, $rareSend->hoa, $rareSend->tho);
	}else{
		globalError2("xu ly dua bau vat ve thanh 2 lan getRareSend($id)");
	}
}

/**
 * @author Le Van Tu
 * @des cap nhat bau vat cho mot lang
 */
function updateRare($village_id, $kim, $thuy, $moc, $hoa, $tho){
	global $db;
	$sql="SELECT COUNT(*) FROM wg_rare WHERE vila_id=$village_id";
	$db->setQuery($sql);
	if($db->loadResult()>0){
		$sql="UPDATE wg_rare SET kim=kim+$kim, moc=moc+$moc, thuy=thuy+$thuy, hoa=hoa+$hoa, tho=tho+$tho WHERE vila_id=$village_id";
	}else{
		$sql="INSERT INTO wg_rare (vila_id, kim, thuy, moc, hoa, tho) VALUES ($village_id, $kim, $thuy, $moc, $hoa, $tho)";
	}
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi cap nhat bau vat cho lang chiem! moveRare($village_from_id, $village_to_id, $kim, $thuy, $moc, $hoa, $tho)");
	}
}

/**
 * @author Le Van Tu
 * @des lay thong tin bau vat dang duoc van chuyen
 * @param id (wg_rare_sends)
 */
function getRareSend($id){
	global $db;
	$sql="SELECT 
				`village_id_from`, `village_id_to`, `kim`, `thuy`, `moc`, `hoa`, `tho` 
			FROM 
				wg_rare_sends 
			WHERE 
				`status`=0 AND 
				id=$id";
	$db->setQuery($sql);
	$db->loadObject($rareSend);
	return $rareSend;
}

/**
 * @author Le Van Tu
 * @des cap nhat status=1 trong bang wg_rare_sends truoc khi xu ly
 */
function setStatusRareSend($id){
	global $db;
	$sql="UPDATE wg_rare_sends SET `status`=1 WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("Loi cap nhat status trong bang wg_rare_sends setRareSend($id)");
	}
}

//hien thi trang thai research troop.
/*
- Kien tra da hoan thanh hay chua:
	+ neu hoan thanh roi thi:
		. update status
		. update status cho bang wg_troop_researched
	+ neu chua hoan thanh:
		. hien thi status.
*/
function ResearchTroopStatus($village_id){
	global $db, $lang;
	includeLang('troop');
	$parse=$lang;
	$sql="SELECT * FROM wg_status WHERE village_id=$village_id AND type=9 AND status=0";
	$db->setQuery($sql);
	$statusList=null;
	$statusList=$db->loadObjectList();
	if($statusList){
		$i=0;
		$row=gettemplate("research_status_row");
		foreach($statusList as $status){
			$timeLeft=GetTimeLeft(strtotime($status->time_end));
			if($timeLeft>0){
				$i++;
				//chua hoan thanh qua trinh research.
				$troopID=GetTroopIDFromObjectID($status->object_id, 2);
				$troop=GetTroopInfo($village_id, $troopID, 0, 0, 0, 0);
				$parse+=$troop;
				$parse['name']=$lang[$parse['name']];
				$parse['timer']=$i;
				$parse['time_left']=gmdate("H:i:s", $timeLeft);
				$parse['time_finished']=GetTime($status->time_end);
				$rows.=parsetemplate($row, $parse);
			}else{
				//da hoan thanh qua trinh research.
				if(SetStatusTroopResearch($status->object_id)){
					SetStatus($status->id);
				}
			}
		}
		if($i>0){
			$parse['rows']=$rows;
			return parsetemplate(gettemplate("research_status_table"), $parse);
		}else{
			return false;
		}
	}
	return false;
}

/**
 * @author Le Van Tu
 * @des Cap nhat trang thai reach linh. ham nay duoc goi moi khi trang duoc refresh.
 * @param object status
 */
function executeTroopResearch($status){
	if(SetStatusTroopResearch($status->object_id)){
		setStatus($status->id);
	}
}

//khi troop da reach xong -> cap nhat status.
function SetStatusTroopResearch($troop_research_id){
	global $db;
	$sql="UPDATE wg_troop_researched SET status=1 WHERE id=$troop_research_id";
	$db->setQuery($sql);
	return $db->query();
}

/**
 * Hien thi trang thai tao linh
 */
function TrainTroopStatus($building, $status_type){
	global $db, $lang, $wg_village;
	$parse=$lang;
	
	$sql="SELECT 
				wg_status.id AS 'status_id', 
				wg_status.time_begin, 
				wg_status.time_end, 
				wg_status.cost_time, 
				wg_troop_train.id AS 'troop_train_id', 
				wg_troop_train.num, 
				wg_troop_train.num_trained, 
				wg_troop_train.troop_id 
			FROM 
				wg_status, 
				wg_troop_train 
			WHERE 
				wg_status.object_id=wg_troop_train.id AND 
				wg_status.time_end > now() AND 
				wg_status.status = 0 AND
				wg_status.village_id=$wg_village->id AND 
				wg_status.type=$status_type 
			ORDER BY wg_status.time_end ASC";
	$db->setQuery($sql);
	$statusTrainList=null;
	$statusTrainList=$db->loadObjectList();
	if($statusTrainList){
		//lay thong tim cua linh de hien thi:
		$arrayTroop=GetArrayOfTroops();
		
		$row=gettemplate('train_status_row');
		$timer=2;
		foreach($statusTrainList as $statusTrain){
			//Kiem tra da hoan thanh hay chua
			$timeLeft=strtotime($statusTrain->time_end)-time();
			$sum=0;
			//tinh thoi gian tao mot linh
			$timePerTroop=$statusTrain->cost_time/$statusTrain->num;
			//tinh thoi gian ke tu luc bat dau toi thoi diem hien tai:
			$duration=time()-strtotime($statusTrain->time_begin);
			//hien thi trang thai nhung linh chua tao xong:
			$parse['timer']=$timer;
			$parse['icon']=$arrayTroop[$statusTrain->troop_id]['icon'];
			$parse['sum']=$statusTrain->num-$statusTrain->num_trained-$sum;
			$parse['name']=$lang[$arrayTroop[$statusTrain->troop_id]['name']];
			$parse['time_left_all']=TimeToString($timeLeft);
			$parse['time_finished']=date("H:i:s", strtotime($statusTrain->time_end));
			$parse['date_finished']=date("d-m-Y", strtotime($statusTrain->time_end));
			$rows.=parsetemplate($row, $parse);
			$timer++;
			//luu mot mang tam thoi gian con lai de lay thoi gian sap sinh ra mot linh moi 
			$arrTimeLeft[$statusTrain->troop_train_id]=$timeLeft-(($parse['sum']-1)*$timePerTroop);
		}
		if($timer>2){
			//thoi gian tao troop tiep theo hoan thanh:
			$parse['time_left']=TimeToString(min($arrTimeLeft));
			$parse['rows']=$rows;
			$result=parsetemplate(gettemplate('train_status_table'), $parse);	
		}else{
			$result="";
		}		
	}
	return $result;
}

/**
 * @author Le Van Tu
 * @des Cap nhat tao linh (ham nay duoc goi moi khi trang duoc refresh).
 * @param object status.
 */
function executeCreateTroop($status){
	global $db; 
	
	//$village=getVillage($status->village_id, "id, troop_keep");
	
	$sql="SELECT 
				wg_troop_train.id,  
				wg_troop_train.num, 
				wg_troop_train.num_trained, 
				wg_troops.keep_hour, 
				wg_troop_train.troop_id 
			FROM 
				wg_troop_train, 
				wg_troops
			WHERE 
				wg_troop_train.troop_id =  wg_troops.id AND
				wg_troop_train.id=$status->object_id";
	$db->setQuery($sql);
	$db->loadObject($troopTrain);
	if($troopTrain){
		deleteTroopTrain($troopTrain->id);
		//Cap nhat linh cho lang:
		addTroopVilla($status->village_id, $troopTrain->troop_id, $troopTrain->num-$troopTrain->num_trained);
		
		//Tang so luong thuc ma linh tieu thu:
		ChangeTroopKeepVillage($status->village_id, $troopTrain->keep_hour * ($troopTrain->num-$troopTrain->num_trained));
	}
}

/**
 * @author Le Van Tu
 * @des Cap nhat tao linh (tung linh).
 * @param
 */
function updateTrainTroopStatus(&$village, $time){
	global $db;
	$time_end=date("Y-m-d H:i:s", $time);
	//sap xep theo thoi gian de lay thoi gian tap troop cuoi cung.
	$sql="SELECT 
				wg_status.id AS 'status_id', 
				wg_status.time_begin, 
				wg_status.time_end, 
				wg_status.cost_time, 
				wg_troop_train.id AS 'troop_train_id', 
				wg_troop_train.num, 
				wg_troop_train.num_trained, 
				wg_troop_train.troop_id,  
				wg_troops.keep_hour
			FROM 
				wg_status, wg_troop_train, wg_troops 
			WHERE 
				wg_troop_train.troop_id =  wg_troops.id AND
				wg_status.object_id=wg_troop_train.id AND 
				wg_status.village_id=$village->id AND 
				wg_status.time_end > '$time_end' AND
				wg_status.status = 0 ";
	//Khong phan biet loai nha (khac voi show ra trong tung loai nha)
	$sql.="AND (wg_status.type=4 OR wg_status.type=12 OR wg_status.type=13 OR wg_status.type=20)";
	$db->setQuery($sql);
	$statusTrainList=null;
	$statusTrainList=$db->loadObjectList();
	if($statusTrainList){
		foreach($statusTrainList as $statusTrain){
			//Kiem tra da hoan thanh hay chua
			$timeLeft=strtotime($statusTrain->time_end)-$time;
			//chua hoan thanh status.
			$sum=0;
			//tinh thoi gian tao mot linh
			$timePerTroop=$statusTrain->cost_time/$statusTrain->num;
			//tinh thoi gian ke tu luc bat dau toi thoi diem hien tai:
			$duration=$time-strtotime($statusTrain->time_begin);
			
			//kiem tra co linh nao du thoi gian hoan thanh hay chua:
			if($duration >= ($timePerTroop*($statusTrain->num_trained+1))){
				//du thoi gian tao it nhat mot linh -> tinh toan, cap nhat
				//tinh thoi gian du:
				$t=$duration-$timePerTroop*$statusTrain->num_trained;
				
				//tinh so linh co the tao ung voi thoi gian du:
				$sum=intval($t/$timePerTroop);
				$sum=$sum<=($statusTrain->num-$statusTrain->num_trained)?$sum:($statusTrain->num-$statusTrain->num_trained);
				
				//Tang so linh da duoc tao:
				$sql="UPDATE wg_troop_train SET num_trained=num_trained+$sum WHERE id=$statusTrain->troop_train_id";
				$db->setQuery($sql);
				if(!$db->query()){
					globalError2("Loi cap nhat so linh da dao tao xong trong bang wg_troop_train");
				}
				//Tang so linh cua lang
				addTroopVilla($village->id, $statusTrain->troop_id, $sum);
				
				ChangeTroopKeepVillage($village->id, $statusTrain->keep_hour*$sum);
				
			}
		}
		return true;
	}
	return false;
}

/**
 * @author Le Van Tu
 * @des xu ly khi hero da duoc chieu mo hoac hoi sinh
 * @param $village_id id cua lang hien tai
 * @return void
 */
function updateTrainHeroStatus($village_id, $time){
	global $db;
	$time=date("Y-m-d H:i:s", $time);
	$sql="SELECT
				wg_heros.id AS id,
				wg_heros.keep_hour,
				wg_status.id AS status_id, 
				wg_status.`type` AS type, 
				wg_status.village_id AS village_id
			FROM
				wg_status ,
				wg_heros
			WHERE
				wg_status.object_id =  wg_heros.id AND
				wg_status.`status` =  '0' AND
				(wg_status.`type` =  '19' OR wg_status.`type` =  '21') AND
				wg_status.village_id = $village_id AND
				wg_status.time_end < '$time' 
			GROUP BY
				wg_status.id";
	$db->setQuery($sql);
	$db->loadObject($hero);
	if($hero){
		if($hero->type==21){
			HoiSinhTuong($hero->id);
		}else{
			SetHeroStatus($hero->id);
		}
		//Tang so linh cua lang trong lang (coi hero nhu mot linh binh thuong)
		addTroopVilla($hero->village_id, 0, 1, $hero->id);
		//Tang so luong thuc ma troop tieu thu:
		changeTroopKeepVillage($hero->village_id, $hero->keep_hour);					
		setStatus($hero->status_id);
	}
}

function TransportRSStatus(){
	global $db, $lang, $wg_village;
	include_once("function_trade.php");
	includeLang('trade');
	$parse=$lang;
	
	//Lay thong tin rs den va di:
	$sql="SELECT
				wg_resource_sends.id AS id,  
				wg_resource_sends.rs1,
				wg_resource_sends.rs2,
				wg_resource_sends.rs3,
				wg_resource_sends.rs4,
				wg_resource_sends.asu,
				wg_resource_sends.village_id_from, 
				wg_resource_sends.village_id_to,
				wg_status.id AS status_id, 
				wg_status.time_end,  
				wg_status.cost_time 
			FROM
				wg_status ,
				wg_resource_sends
			WHERE
				wg_status.object_id =  wg_resource_sends.id AND
				(wg_status.`type` =  '6' OR wg_status.`type` =  '23') AND 
				wg_status.`status` =  '0' AND 
				wg_resource_sends.`status` =  '0' AND 
				wg_status.time_end >  now() AND 
				(wg_resource_sends.village_id_from =  '$wg_village->id' OR  
				wg_resource_sends.village_id_to =  '$wg_village->id') 
			GROUP BY
				wg_status.id";
	$db->setQuery($sql);
	$transportList=null;
	$transportList=$db->loadObjectList();
	if($transportList){
		$i=0;
		$table=gettemplate("transport_rs_status_table");
		foreach($transportList as $transport){
			$i++;
			$parse['i']=$i;
			$parse['time_left']=TimeToString(strtotime($transport->time_end)-time());
			$parse['time_arrival']=date("H:i:s", strtotime($transport->time_end));
			$parse['date_arrival']=date("d-m-Y", strtotime($transport->time_end));
			$parse['r1']=$transport->rs1;
			$parse['r2']=$transport->rs2;
			$parse['r3']=$transport->rs3;
			$parse['r4']=$transport->rs4;
			$parse['r5']=$transport->asu;						
			if($wg_village->id==$transport->village_id_from){
				//lang nay la lang gui:
				$villageTo=getVillage($transport->village_id_to);
				$parse['x']=$villageTo->x;
				$parse['y']=$villageTo->y;
				$parse['uid']=$wg_village->user_id;
				$parse['player_arriving']=GetPlayerName($transport->village_id_from);				
				$parse['village_name']=GetVillageName($transport->village_id_to);
				$parse['to_from']=$lang['to'];				
				$underwayTables.=parsetemplate($table, $parse);
			}else{
				//lang nay la lang nhan
				$villageArriving=getVillage($transport->village_id_from);
				$parse['x']=$villageArriving->x;
				$parse['y']=$villageArriving->y;
				$parse['uid']=$villageArriving->user_id;
				$parse['player_arriving']=GetPlayerName($transport->village_id_from);
				$parse['village_name']=GetVillageName($transport->village_id_from);
				$parse['to_from']=$lang['from'];
				$arrivingTables.=parsetemplate($table, $parse);
			}
		}
		
	}
	
	//Lay trang thai thuong nhan tro ve:
	$sql="SELECT
				wg_resource_sends.id AS id,  
				wg_resource_sends.rs1,
				wg_resource_sends.rs2,
				wg_resource_sends.rs3,
				wg_resource_sends.rs4,
				wg_resource_sends.asu,
				wg_resource_sends.village_id_from, 
				wg_resource_sends.village_id_to,
				wg_status.id AS status_id, 
				wg_status.time_end,  
				wg_status.cost_time 
			FROM
				wg_status ,
				wg_resource_sends
			WHERE
				wg_status.object_id =  wg_resource_sends.id AND
				wg_status.`type` =  '22' AND 
				wg_status.`status` =  '0' AND 
				wg_resource_sends.`status` =  '0' AND 
				wg_status.time_end >  now() AND 
				wg_status.village_id = '$wg_village->id' 
			GROUP BY
				wg_status.id";
	$db->setQuery($sql);
	$transportList=null;
	$transportList=$db->loadObjectList();
	if($transportList){
		$tableReturn=gettemplate("transport_rs_status_return_table");
		foreach($transportList as $transport){
			$i++;
			$villageTo=getVillage($transport->village_id_to);
			$parse['i']=$i;
			$parse['time_left']=TimeToString(strtotime($transport->time_end)-time());
			$parse['player_arriving']=GetPlayerName($transport->village_id_from);
			$parse['village_name']=$villageTo->name;
			$parse['x']=$villageTo->x;
			$parse['y']=$villageTo->y;
			$parse['time_arrival']=date("H:i:s", strtotime($transport->time_end));
			$parse['date_arrival']=date("d-m-Y", strtotime($transport->time_end));
			$parse['r1']=$transport->rs1;
			$parse['r2']=$transport->rs2;
			$parse['r3']=$transport->rs3;
			$parse['r4']=$transport->rs4;
			$parse['r5']=$transport->asu;
			$parse['uid']=$wg_village->user_id;
			$parse['to_from']=$lang['from'];				
			$underwayTables.=parsetemplate($tableReturn, $parse);
		}
	}
	
	if($arrivingTables){
		//co it nhat mot RS chua toi noi -> hien thi trang thai.
		$parse['transport_rs_status_title']=$lang['arriving_merchants'];
		$parse['tables']=$arrivingTables;
		$arriving=parsetemplate(gettemplate("transport_rs_status_title"), $parse);
		$table=null;
	}
	
	if($underwayTables){
		$parse['transport_rs_status_title']=$lang['own_merchants_underway'];
		$parse['tables']=$underwayTables;
		$underway=parsetemplate(gettemplate("transport_rs_status_title"), $parse);
	}
	return $arriving.$underway;
}

/**
* @Author: LeVanTu
* @Des: Cap nhat cac su kien giao thuong.
* @param: $villageId id cua lang hien tai
* @return: void
*/
function updateTransportRSStatus($village_id){
	global $db;
	include_once("function_trade.php");
	//Cap nhat chien loi pham linh mang ve:
//	upDateBounty($village_id);
	//Lay thong tin cac chuyen van chuyen da toi noi: (time_end<=now())
	$sql="SELECT
				wg_resource_sends.id AS id,  
				wg_resource_sends.rs1,
				wg_resource_sends.rs2,
				wg_resource_sends.rs3,
				wg_resource_sends.rs4,
				wg_resource_sends.asu,
				wg_resource_sends.village_id_from, 
				wg_resource_sends.village_id_to,
				wg_status.id AS status_id, 
				wg_status.time_end,  
				wg_status.cost_time 
			FROM
				wg_status ,
				wg_resource_sends
			WHERE
				wg_status.object_id =  wg_resource_sends.id AND
				(wg_status.`type` =  '6' OR wg_status.`type` =  '23') AND 
				wg_status.`status` =  '0' AND 
				wg_resource_sends.`status` =  '0' AND 
				wg_status.time_end <=  now() AND 
				(wg_resource_sends.village_id_from =  '$village_id' OR  
				wg_resource_sends.village_id_to =  '$village_id') 
			GROUP BY
				wg_status.id";
	$db->setQuery($sql);
	$transportList=$db->loadObjectList();
	if($transportList){
		
		foreach($transportList as $transport){
			//Set status:
			SetStatus($transport->status_id);
			//Cong rs cho lang nhan:
			changeRSVillage($transport->village_id_to, $transport->rs1, $transport->rs2, $transport->rs3, $transport->rs4, $transport->asu);
			//luu trang thai thuong nhan ve lang:
			InsertStatus($transport->village_id_from, $transport->id, $transport->time_end, date("Y-m-d H:i:s", (strtotime($transport->time_end)+$transport->cost_time)), $transport->cost_time, 22);
			//Gui report cho cac lang:
			reportTransport($transport);
		}
	}
	
	//Cap nhat khi thuong nhan ve toi lang:
	$sql="SELECT
				wg_resource_sends.id AS id,  
				wg_resource_sends.rs1,
				wg_resource_sends.rs2,
				wg_resource_sends.rs3,
				wg_resource_sends.rs4,
				wg_resource_sends.asu,
				wg_resource_sends.village_id_from, 
				wg_resource_sends.village_id_to,
				wg_status.id AS status_id, 
				wg_status.time_end,  
				wg_status.cost_time 
			FROM
				wg_status ,
				wg_resource_sends
			WHERE
				wg_status.object_id =  wg_resource_sends.id AND
				wg_status.`type` =  '22' AND 
				wg_status.`status` =  '0' AND 
				wg_resource_sends.`status` =  '0' AND 
				wg_status.time_end <=  now() AND 
				wg_status.village_id = '$village_id' 
			GROUP BY
				wg_status.id";
	$db->setQuery($sql);
	$transportList=null;
	$transportList=$db->loadObjectList();
	if($transportList){
		foreach($transportList as $transport){
			SetStatus($transport->status_id);
			setSendResourceStatus($transport->id);
			//tinh so thuong nhan di van chuyen:
			$merchants=GetMerchantTransport($village_id, $transport->rs1 + $transport->rs2 + $transport->rs3 + $transport->rs4);
			//cap nha so thuong nhan cua lang
			SubMerchantUnderaway($village_id, $merchants);
		}
	}
}

/**
 * @author Le Van Tu
 * @des cap nhat cac cuoc van chuyen
 * @param object status
 */
function executeResourceBuySale($status){
	global $db;
	$sql="SELECT
				wg_resource_sends.id AS id,  
				wg_resource_sends.rs1,
				wg_resource_sends.rs2,
				wg_resource_sends.rs3,
				wg_resource_sends.rs4,
				wg_resource_sends.asu,
				wg_resource_sends.village_id_from, 
				wg_resource_sends.village_id_to 
			FROM
				wg_resource_sends
			WHERE
				wg_resource_sends.id=$status->object_id AND 
				wg_resource_sends.`status` =  '0'";
	$db->setQuery($sql);
	$db->loadObject($rsSend);
	if($rsSend){
		//kiem tra lang nhan co bi xoa hay khong:
		if(checkVillageID($rsSend->village_id_to)){
			//Cong rs cho lang nhan:
			changeRSVillage($rsSend->village_id_to, $rsSend->rs1, $rsSend->rs2, $rsSend->rs3, $rsSend->rs4, $rsSend->asu);
		}else{
			require_once("function_troop.php");
			//tra rs ve lang gui:
			$oid = InsertSendRS($rsSend->village_id_from, $rsSend->village_id_to, $rsSend->rs1, $rsSend->rs2, $rsSend->rs3, $rsSend->rs4, $rsSend->asu);
			InsertBountyStatus($rsSend->village_id_from, $oid, $status->time_end, date("Y-m-d H:i:s", (strtotime($status->time_end)+$status->cost_time)), $status->cost_time);
		}
		
		//luu trang thai thuong nhan ve lang:
		InsertStatus($rsSend->village_id_from, $rsSend->id, $status->time_end, date("Y-m-d H:i:s", (strtotime($status->time_end)+$status->cost_time)), $status->cost_time, 22);
		//Gui report cho cac lang:
		reportTransport($status, $rsSend);
	}
}

/**
 * @author Le Van Tu
 * @todo kiem tra id cua mot lang co ton tai hay khong
 */
function checkVillageID($id){
	global $db;
	$sql = "SELECT COUNT(*) FROM wg_villages WHERE user_id!=0 AND id=$id";
	$db->setQuery($sql);
	return $db->loadResult();
} 

/**
 * @author Le Van Tu
 * @Des Tao report sau moi lan van chuyen rs
 * @param $transport thong tin ve lan van chuyen
 */
function reportTransport($status, $rsSend){
	global $lang;
	includeLang('trade');
	$parse=$lang;
	$parse['rs1']=$rsSend->rs1;
	$parse['rs2']=$rsSend->rs2;
	$parse['rs3']=$rsSend->rs3;
	$parse['rs4']=$rsSend->rs4;
	$parse['asu']=$rsSend->asu;
	$parse['village_from_name']=GetVillageName($rsSend->village_id_from);
	$parse['village_to_name']=GetVillageName($rsSend->village_id_to);
	$parse['player_from_name']=GetPlayerName($rsSend->village_id_from);
	$parse['date_on']=date("d-m-Y", strtotime($status->time_end));
	$parse['time_at']=date('H:i:s', strtotime($status->time_end));
	
	$villageFrom=getVillage($rsSend->village_id_from, "user_id, x, y");
	$villageTo=getVillage($rsSend->village_id_to, "user_id");
	
	$parse['uid']=$villageFrom->user_id;
	$parse['x']=$villageFrom->x;
	$parse['y']=$villageFrom->y;
	$content=parsetemplate(gettemplate("send_rs_report"), $parse);	
	$title=$parse['village_from_name']." ".$parse['supplies']." ".$parse['village_to_name'];
		
	InsertReport($villageFrom->user_id, $title, date("Y-m-d H:i:s"), $content, REPORT_TRADE, $villageTo->user_id);
	if($villageFrom->user_id!=$villageTo->user_id){
		InsertReport($villageTo->user_id, $title, date("Y-m-d H:i:s"), $content, REPORT_TRADE_RECEIVE, $villageFrom->user_id);
	}		
}

/**
 * @author Le Van Tu
 * @Des doi status=1 cho wg_resource_sends
 * @param $id id cua wg_resource_sends
 * @return void
 */
function setSendResourceStatus($id){
	global $db;
	$sql="UPDATE wg_resource_sends SET status=1 where id=$id";
	$db->setQuery($sql);
	return $db->query();
}

function upDateBounty($village_id){
	global $db;
	$sql="SELECT * FROM wg_status WHERE village_id=$village_id AND type=16 AND status=0";
	$db->setQuery($sql);
	$statusList=$db->loadObjectList();
	if($statusList){
		foreach($statusList as $status){
			if(strtotime($status->time_end)<time()){
				//bounty da ve toi noi -> cong rs cho lang.
				$sql="SELECT * FROM wg_resource_sends WHERE id=$status->object_id";
				$db->setQuery($sql);
				$db->loadObject($rsSend);
				changeRSVillage($village_id, $rsSend->rs1, $rsSend->rs2, $rsSend->rs3, $rsSend->rs4);
				SetStatus($status->id);
			}
		}
		return true;
	}
	return false;
}

function GetTimeArrival($timeBegin, $costTime){
	return $timeBegin + $costTime/2;
}

/**
 * @author Le Van Tu
 * @des set status=1 trong bang wg_status 
 * @param1 id cua lang
 * @param2 status muon set
 * @return true or false
 */
function setStatus($id, $status=1){
	global $db;
	$sql="UPDATE wg_status SET status=$status WHERE id=$id";
	$db->setQuery($sql);
	return $db->query();
}

function UpdateStatusSendRS($id){
	global $db;
	$sql="UPDATE wg_resource_sends SET status=1 WHERE id=$id";
	$db->setQuery($sql);
	return $db->query();
}

function TrainTroopFinished($village_id, $object_id){
	global $db;
	$sql="SELECT * FROM wg_troop_train WHERE id=$object_id";
	$db->setQuery($sql);
	$troopTrain=null;
	$db->loadObject($troopTrain);	
	//cap nhat bang wg_troop_train:
	if($troopTrain){
		if($troopTrain->num>1){
			$sql="UPDATE wg_troop_train SET num=".($troopTrain->num - 1)." WHERE id=$object_id";
			$db->setQuery($sql);
			$result=$db->query();
		}else{
			$sql="DELETE FROM wg_troop_train WHERE id=$object_id";
			$db->setQuery($sql);
			$result=$db->query();
		}
	}else{
		return false;
	}	
	//cap nhat bang wg_troop_villa:
	if($result){
		$sql="SELECT * FROM wg_troop_villa WHERE village_id=$village_id AND troop_id=$troopTrain->troop_id";
		$db->setQuery($sql);
		$troopVilla=null;
		$db->loadObject($troopVilla);
		if($troopVilla){
			$sql="UPDATE wg_troop_villa SET num=".($troopVilla->num + 1)." WHERE id=$troopVilla->id";
			$db->setQuery($sql);
			$result=$db->query();
		}else{
			$sql="INSERT INTO wg_troop_villa (troop_id, village_id, num) VALUES ($troopTrain->troop_id, $village_id, 1)";
			$db->setQuery($sql);
			$result=$db->query();
		}
	}
	return $result;
}


/**
 * @author Le Van Tu
 * @des xoa mot record trong bang wg_troop_train.
 * @param id cua record
 */
function deleteTroopTrain($id){
	global $db;
	$sql="DELETE FROM wg_troop_train WHERE id=$id";
	$db->setQuery($sql);
	return $db->query();
}

function GetTimeLeft($timeEnd){
	$timenow = time();
	if($timenow < $timeEnd){
		return $timeEnd - $timenow;
	}	
	return 0;
}

function GetTime($datetime){
	//Split Time & Date
	$splitdatetime = explode(" ", $datetime);
	$time = $splitdatetime[1];
	return $time;
}

function GetVillageName($village_id){
	global $db;
	$sql="SELECT * FROM wg_villages WHERE id=$village_id";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village){
		return $village->name;
	}
	return false;
}

//lay troop->id trong bang wg_troop_train.
function GetTroopIDFromObjectID($object_id, $type){
	global $db;
	switch($type){
		case 1:	//doi voi object la troop_train.
			$sql="SELECT troop_id FROM wg_troop_train WHERE id=$object_id";
			$db->setQuery($sql);
			$troopTrain=null;
			$db->loadObject($troopTrain);
			if($troopTrain){
				return $troopTrain->troop_id;
			}
			break;
		case 2:	//doi voi object la troop_research.
			$sql="SELECT troop_id FROM wg_troop_researched WHERE id=$object_id";
			$db->setQuery($sql);
			$troopResearch=null;
			$db->loadObject($troopResearch);
			if($troopResearch){
				return $troopResearch->troop_id;
			}
			break;
	}
	return false;
}

function CheckBuildingTrain($building_type_id, $troop_train_id){
	global $db;
	$sql="SELECT building_type_id FROM wg_troop_train WHERE id=$troop_train_id";
	$db->setQuery($sql);
	$troopTrain=null;
	$db->loadObject($troopTrain);
	if($troopTrain && $troopTrain->building_type_id==$building_type_id){
		return true;
	}
	return false;
}


?>