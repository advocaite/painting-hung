<?php
defined( 'INSIDE' ) or die( 'Restricted access' );

function executeSendTroop_ctc($status){
	global $db, $game_config;
		
	$sql="SELECT
				* 
			FROM
				wg_ctc_send_troop  
			WHERE
				wg_ctc_send_troop.id=$status->object_id AND 
				wg_ctc_send_troop.`status` =  '0' ";
	$db->setQuery($sql);
	$db->loadObject($st);
	if($st){
		setStatusSendTroop_ctc($st->id);
		$ts = getTroopOnWay_ctc($st->id);
		if($ts){
			
			foreach($ts as $t){
				changeTroop_ctc($status->village_id, $t->troop_id, $t->num, $st->dtk_to_id, getVillageSide($status->village_id));
				setStatusTroopOnWay_ctc($t->id);
			}
		}
		
		$hr = getHeroOnWay_ctc($st->id);
		if($hr){
			setHeroOnWayStatus($hr->id);
			insertHeros_ctc($status->village_id, $hr->hero_id, $st->dtk_to_id);
		}
	}
}

/**
 * @author Le Van Tu
 * @des xu ly dieu quan
 */
function sendTroop_ctc($dtk){
	if($_GET['st'] && $_GET['stp']){
		switch($_GET['st']){
			case 1:
				switch($_GET['stp']){
					case 1:
						$rs = showSend1_ctc();
						break;
					case 2:
						$rs = showConfirm1_ctc();
						break;
					case 3:
						$rs = confirmSendTroop1_ctc($dtk);
						break;
				}
				break;
			case 2:
				switch($_GET['stp']){
					case 1:
						$rs = showSend2_ctc($dtk);
						break;
					case 2:
						$rs = showConfirm2_ctc($dtk);
						break;
					case 3:
						$rs = confirmSendTroop2_ctc($dtk);
						break;
				}
				break;
		}
	}
	
	return $rs;
}


function getListDTK($ct_id){
	global $db;
	$sql = "SELECT * FROM wg_ctc_diem_tap_ket WHERE ct_id = $ct_id";
	$db->setQuery($sql);
	return $db->loadObjectList();
}

/**
 * @author Le Van Tu
 * @des Tao menu link toi diem tap ket va cac thap canh
 */
function getDTKMenu($ct_id){
	global $db;
	$rs = array();
	$listDTK = getListDTK($ct_id);
	
	for($i=0; $i<7; $i++){
		$dtk = $listDTK[$i];
		$rs['id_'.$i] = $dtk->id;
	}
	
	return $rs;	
}

/**
 * @author Le Van Tu
 * @des Hien thi linh trong cac lang cua mot user.
 */
function showTroopInVillage_ctc($vls, $dtk){
	global $db, $user, $lang;
	$rs = '';
	includeLang('rally_point');
	$troopCtc = troops_ctc();
	
	$parse = $lang;
	if($vls){
		$row = gettemplate('ctc/ctc_list_troop_row');
		foreach($vls as $vl){
			
			$ts = GetListTroopVilla($vl);
			
			if($ts){
				
				$colinh = false;
				for($i=0; $i<11; $i++){
					$t = $ts[$i];
					if($troopCtc[$t->id]){
						$parse['icon'.($i+1)]	= CTC_ROOT_PATH.$t->icon;
						$parse['title'.($i+1)]	= $lang[$t->name];
						
						if($t->sum > 0){
							$parse['t'.($i+1)] 		= $t->sum;
							$parse['class'.($i+1)]	= '';
							$colinh					= true;
						}else{
							$parse['t'.($i+1)] 		= 0;
							$parse['class'.($i+1)]	= ' class="c"';
						}
						
						$sk += $t->sum * $t->keep_hour;
					}
				}
				
				$parse['title12']	= $lang['hero'];
				
				$hr = GetHeroVillage($vl->id);
				if($hr){
					$parse['t12']		= 1;
					$parse['class12']	= '';
					$sk					+= $hr->keep_hour;
					$colinh				= true;
				}else{
					$parse['t12']		= 0;
					$parse['class12']	= 'class="c"';
				}
				
				if($colinh){
					$parse['list_troop_title'] = $lang['quan_trong_thanh'];
					$parse['vln'] 		= $vl->name;
					$parse['upkeep']	= $sk;
					
					$parse['x']			= $vl->x;
					$parse['y']			= $vl->y;
					
					$parse['action']	= '<a href = "javascript:void(0)" onclick = "showSendTroop(\'popup_div\', '.$dtk->id.', 1,'.$vl->id.');">'.$lang['dieu_quan'].'</a>';
					
					if($rows){
						$rows .= gettemplate("ctc/ctc_space_row");
					}
					
					$rows .= parsetemplate($row, $parse);
				}
			}
		}
		
		if($rows){
			$parse['rows'] = $rows;
			$rs = parsetemplate(gettemplate("ctc/ctc_list_troop"), $parse);
		}
	}

	return $rs;
} 

/**
 * @author Le Van Tu
 * @des hien thi linh cua mot user o diem tap ket
 */
function showTroopInDTK_ctc($vls, $dtk){
	global $lang;
	
	includeLang('rally_point');
	includelang("troop");
	
	$parse = $lang;
	
	$troopCtc = troops_ctc();
	$rs = '';
	
	if($vls){
		$tb = gettemplate("ctc/ctc_list_troop");
		$row = gettemplate('ctc/ctc_list_troop_row');
		foreach($vls as $vl){
			$coLinh = 0;
			$lt = getTroopInDTK_ctc($vl, $_REQUEST['id']);
			
			if($lt){
				$sk = 0;
				
				for($i=0; $i<11; $i++){
					$t = $lt[$i];
					if($troopCtc[$t->id]){
						$parse['icon'.($i+1)] 	= CTC_ROOT_PATH.$t->icon;
						$parse['title'.($i+1)]	= $lang[$t->name];
						if($t->sum>0){
							$coLinh = 1;
							$parse['t'.($i+1)]		= $t->sum;
							$parse['class'.($i+1)]	= '';
							$sk += $t->sum*$t->keep_hour;
						}else{
							$parse['t'.($i+1)]		= 0;
							$parse['class'.($i+1)]	= ' class="c"';
						}
					}
				}
				
				$hr = getHero_ctc($vl->id, $dtk->id);
				if($hr){
					$coLinh = 1;
					$parse['t12']		= 1;
					$parse['class12']	= '';
					$sk += $hr->keep_hour;
				}else{
					$parse['t12']		= 0;
					$parse['class12']	= ' class="c"';
				}
				
				$parse['list_troop_title'] 	= $lang['quan_tg_ctc'];
				$parse['vln'] 			= $vl->name;
				$parse['x'] 			= $vl->x;
				$parse['y'] 			= $vl->y;
				$parse['upkeep'] 		= $sk;
				
				$parse['action']	= '<a href = "javascript:void(0)" onclick = "showSendTroop(\'popup_div\', '.$dtk->id.', 2,'.$vl->id.');">'.$lang['dieu_quan'].'</a>';
				
				if($coLinh){
					if($rows){
						$rows .= gettemplate("ctc/ctc_space_row");
					}
					$rows .= parsetemplate($row, $parse);
				}
					
			}
		}
		
		if($rows){
			$parse['rows'] = $rows;
			$rs = parsetemplate($tb, $parse);
		}
	}
	
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Hien thi trang thai linh dang den va di toi mot diem tap ket
 */
function showTroopOnTheWay_ctc($vls, $dtk, &$timer){
	global $db, $lang;
		
	includeLang('troop');
	includeLang('ctc');
	
	$parse = $lang;
	
	if($vls){
		//Lay thong tin quan doi dang den:
		$where = '(wg_status.village_id = '.$vls[0]->id;
		$vls2[$vls[0]->id] = $vls[0];
		for($i=1; $i<count($vls); $i++){
			$vls2[$vls[$i]->id] = $vls[$i];
			$where .= " OR wg_status.village_id =  ".$vls[$i]->id;
		}
		$where .= ')';
		
		$sql = "SELECT
						wg_ctc_send_troop.id AS id,
						wg_ctc_send_troop.dtk_from_id,
						wg_ctc_send_troop.dtk_to_id,
						wg_status.village_id,
						wg_status.time_begin,
						wg_status.time_end,
						wg_status.cost_time
					FROM
						wg_status ,
						wg_ctc_send_troop
					WHERE
						wg_status.object_id =  wg_ctc_send_troop.id AND
						wg_ctc_send_troop.`status` =  '0' AND 
						wg_status.`type` =  '26' AND
						wg_ctc_send_troop.dtk_from_id =  $dtk->id  AND 
						$where
					GROUP BY
						wg_ctc_send_troop.id
					ORDER BY
						wg_status.time_end ASC";
		$db->setQuery($sql);
		$sts = $db->loadObjectList();
		if($sts){
			
			//$rs	= '<b>'.$lang['quan_dang_di'].'</b><br>';
			
			$table = gettemplate('ctc/ctc_troop_onway_table');
			
			foreach($sts as $st){
				$vl = $vls2[$st->village_id];
				$nts = getTroopsOfNation($vl->nation_id);
				
				$ts = getTroopOnWay_ctc($st->id);
				$temp = array();
				foreach($ts as $t){
					$temp[$t->troop_id] = $t->num;
				}
				
				for($i=0; $i<11; $i++){
					$t = $nts[$i];
					
					$s = $temp[$t->id];
					
					if($s>0){
						$parse['sum'.($i+1)] = $s;
						$parse['class'.($i+1)] = '';
					}else{
						$parse['sum'.($i+1)] = 0;
						$parse['class'.($i+1)] = 'class="c"';
					}
					
					$parse['icon'.($i+1)] = CTC_ROOT_PATH.$t->icon;
					$parse['title'.($i+1)] = $lang[$t->name];
				}
				
				$hr = getHeroOnWay_ctc($st->id);
				if($hr){
					$parse['sum12'] = 1;
					$parse['class12'] = '';
				}else{
					$parse['sum12'] = 0;
					$parse['class12'] = 'class="c"';
				}
				$parse['title12'] = $lang['hero'];
				
				$to = getDTKByID_ctc($st->dtk_to_id);
				$cung = getCung_ctc($to->index);
				$parse['title'] = $lang['quan_di_toi']." ".$cung;
				
				$parse['timer'] = $timer;
				$timer	++;
				
				$parse['duration']	= TimeToString(strtotime($st->time_end)-time());
				$parse['time_at']	= date("H:i:s", strtotime($st->time_end));
				$parse['date_at']	= date("d-m-Y", strtotime($st->time_end));
				
				$parse['cancel_button'] = '';
				
				$parse['village_name']	= $vl->name;
				$parse['x']	= $vl->x;
				$parse['y']	= $vl->y;
				if($rs){
					$rs .= "<br>";
				}
				
				$rs .= parsetemplate($table, $parse);
			}
		}
	}
	
	return $rs;	
}

/**
 * @author Le Van Tu
 * @des Hien thi trang thai linh dang den va di toi mot diem tap ket
 */
function showTroopIncoming_ctc($vls, $dtk, &$timer){
	global $db, $lang;
	
	includeLang('troop');
	includeLang('ctc');
	
	$parse = $lang;
	
	if($vls){		
		
		//Lay thong tin quan doi dang den:
		$where = '(wg_status.village_id = '.$vls[0]->id;
		$vls2[$vls[0]->id] = $vls[0];
		for($i=1; $i<count($vls); $i++){
			$vls2[$vls[$i]->id] = $vls[$i];
			$where .= " OR wg_status.village_id =  ".$vls[$i]->id;
		}
		$where .= ')';
		
		$sql = "SELECT
						wg_ctc_send_troop.id AS id,
						wg_ctc_send_troop.dtk_from_id,
						wg_ctc_send_troop.dtk_to_id,
						wg_status.village_id,
						wg_status.time_begin,
						wg_status.time_end,
						wg_status.cost_time
					FROM
						wg_status ,
						wg_ctc_send_troop
					WHERE
						wg_status.object_id =  wg_ctc_send_troop.id AND
						wg_ctc_send_troop.`status` =  '0' AND 
						wg_status.`type` =  '26' AND
						wg_ctc_send_troop.dtk_to_id =  $dtk->id  AND 
						$where
					GROUP BY
						wg_ctc_send_troop.id
					ORDER BY
						wg_status.time_end ASC";
		$db->setQuery($sql);
		$sts = $db->loadObjectList();
		if($sts){
			
			//$rs	= '<b>'.$lang['quan_dang_den'].'</b><br>';
			
			$table = gettemplate('ctc/ctc_troop_onway_table');
			
			foreach($sts as $st){
				$vl = $vls2[$st->village_id];
				$nts = getTroopsOfNation($vl->nation_id);
				
				$ts = getTroopOnWay_ctc($st->id);
				$temp = array();
				foreach($ts as $t){
					$temp[$t->troop_id] = $t->num;
				}
				
				for($i=0; $i<11; $i++){
					$t = $nts[$i];
					
					$s = $temp[$t->id];
					
					if($s>0){
						$parse['sum'.($i+1)] = $s;
						$parse['class'.($i+1)] = '';
					}else{
						$parse['sum'.($i+1)] = 0;
						$parse['class'.($i+1)] = 'class="c"';
					}
					
					$parse['icon'.($i+1)] = CTC_ROOT_PATH.$t->icon;
					$parse['title'.($i+1)] = $lang[$t->name];
				}
				
				$hr = getHeroOnWay_ctc($st->id);
				if($hr){
					$parse['sum12'] = 1;
					$parse['class12'] = '';
				}else{
					$parse['sum12'] = 0;
					$parse['class12'] = 'class="c"';
				}
				$parse['title12'] = $lang['hero'];
				
				$from = getDTKByID_ctc($st->dtk_from_id);
				$cung = getCung_ctc($from->index);
				$parse['title'] = $lang['quan_den_tu']." ".$cung;
				
				$parse['timer'] = $timer;
				$timer	++;
				
				$parse['duration']	= TimeToString(strtotime($st->time_end)-time());
				$parse['time_at']	= date("H:i:s", strtotime($st->time_end));
				$parse['date_at']	= date("d-m-Y", strtotime($st->time_end));
				
				$parse['cancel_button'] = '';
				
				$parse['village_name']	= $vl->name;
				$parse['x']	= $vl->x;
				$parse['y']	= $vl->y;
				
				if($rs){
					$rs .= "<br>";
				}
				$rs .= parsetemplate($table, $parse);
				//echo $tbs."<pre>"; print_r($temp); die();
			}
		}
	}
	
	return $rs;	
}

/**
 * @author Le Van Tu
 * @des Lay thong tin tat ca cac lang cua mot user
 */
function getVillageOfUser_ctc($user_id){
	global $db;	
	$sql = "SELECT id, name, x, y, nation_id FROM wg_villages WHERE user_id = $user_id";
	$db->setQuery($sql);
	return $db->loadObjectList();
}


/**
 * @author Le Van Tu
 * @des Hien thi form gui linh trong thanh toi diem tap ket
 */
function showSend1_ctc(){
	global $user;
	
	$rs = '';
	
	$vl = checkVillageOfUser_ctc($_GET['vl'], $user['id']);
	
	if($vl){
		$ts = GetListTroopVilla($vl);
		
		if($ts){
			$colinh = false;
			for($i=0; $i<11; $i++){
				$t = $ts[$i];
				$parse['icon'.($i+1)]	= CTC_ROOT_PATH.$t->icon;
				$parse['title'.($i+1)]	= $lang[$t->name];
				
				if($t->sum > 0){
					$parse['t'.($i+1)]		= '';
					$parse['sum'.($i+1)] 	= "<a href=\"#\" onClick=\"document.snd.t".($i+1).".value=$t->sum; return false;\">($t->sum)</a>";
					$parse['class'.($i+1)]	= ' class="f8"';
					$colinh					= true;
				}else{
					$parse['t'.($i+1)]		= '';
					$parse['sum'.($i+1)]="<b>(0)</b>";
					$parse['class'.($i+1)]=' class="f8 c b"';
				}
				
				$sk += $t->sum * $t->keep_hour;
			}
		
			$parse['title12']	= $lang['hero'];
			
			$hr = GetHeroVillage($vl->id);
			if($hr){				
				$parse['t12']		= '';
				$parse['sum12']		= "<a href=\"#\" onClick=\"document.snd.t12.value=1; return false;\">(1)</a>";
				$parse['class12']	= '';
				$sk					+= $hr->keep_hour;
				$colinh				= true;
			}else{
				$parse['t12']	= "";
				$parse['sum12']		= "<b>(0)</b>";
				$parse['class12']	= 'class="c"';
			}
			
			if($colinh){
				$parse['upkeep']	= $sk;
				
				$parse['village_name']	= $vl->name;
				$parse['x']	= $vl->x;
				$parse['y']	= $vl->y;
				
				$parse['action']	= parsetemplate(gettemplate('a'), array('href'=>'tc.php?id='.$_GET['id'].'&st=1&vl='.$vl->id, 'string'=>$lang['dieu_quan']));
				$parse['id'] = $_GET['id'];
				$parse['st'] = $_GET['st'];
				$parse['vl'] = $_GET['vl'];
				
				$parse['tg0_style']	='';
				$parse['error_message']	= '';
				
				$rs = parsetemplate(gettemplate('ctc/ctc_st_form1'), $parse);
			}					 
		}
	}		
	
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Hien thi form di chuyen linh giua cac diem
 */
function showSend2_ctc($dtk){
	global $user;
	
	$rs = '';
	
	$vl = checkVillageOfUser_ctc($_GET['vl'], $user['id']);
	
	if($vl){
		
		//Lay ma tran duong di:
		$ds = getGraph_ctc($dtk->ct_id);
		
		$checked = false;
		
		if($ds[$dtk->index][1]['s'] || $ds[$dtk->index][7]['s']){
			$parse['c_1']	= '';
			$parse['disabled_1']	= '';
			$parse['checked_1']	= 'checked="checked"';
			$checked = true;
		}else{
			$parse['c_1']	= 'c';
			$parse['disabled_1']	= 'disabled';
			$parse['checked_1']	= '';
		}
		
		for($i=2; $i<=6; $i++){
			//echo $ds[$dtk->index][$i]['s'].'-'.$i."<br>";
			if($ds[$dtk->index][$i]['s']){
				$parse['c_'.$i]	= '';
				$parse['disabled_'.$i]	= '';
				if($checked){
					$parse['checked_'.$i]	= '';
				}else{
					$parse['checked_'.$i]	= 'checked="checked"';
					$checked = true;
				}								
			}else{
				$parse['c_'.$i]	= 'c';
				$parse['disabled_'.$i]	= 'disabled';
				$parse['checked_'.$i]	= '';
			}			
		}
		//echo "<pre>"; print_r($ds); die();
		
		//lay danh sach linh cua lang trong diem tap ket.
		$ts = getTroopInDTK_ctc($vl, $_GET['id']);
		
		if($ts){
			$colinh = false;
			for($i=0; $i<11; $i++){
				$t = $ts[$i];
				$parse['icon'.($i+1)]	= CTC_ROOT_PATH.$t->icon;
				$parse['title'.($i+1)]	= $lang[$t->name];
				
				if($t->sum > 0){
					$parse['t'.($i+1)]		= '';
					$parse['sum'.($i+1)] 	= "<a href=\"#\" onClick=\"document.snd.t".($i+1).".value=$t->sum; return false;\">($t->sum)</a>";
					$parse['class'.($i+1)]	= ' class="f8"';
					$colinh					= true;
				}else{
					$parse['t'.($i+1)]		= '';
					$parse['sum'.($i+1)]="<b>(0)</b>";
					$parse['class'.($i+1)]=' class="f8 c b"';
				}
				
				$sk += $t->sum * $t->keep_hour;
			}
		
			$parse['title12']	= $lang['hero'];
			
			$hr = getHero_ctc($vl->id, $dtk->id);
			if($hr){				
				$parse['t12']		= "";
				$parse['sum12']		= "<a href=\"#\" onClick=\"document.snd.t12.value=1; return false;\">(1)</a>";
				$parse['class12']	= '';
				$sk					+= $hr->keep_hour;
				$colinh				= true;
			}else{
				$parse['t12']		= '';
				$parse['sum12']		= "<b>(0)</b>";
				$parse['class12']	= 'class="c"';
			}
			
			if($colinh){
				$parse['upkeep']	= $sk;
				
				$parse['village_name']	= $vl->name;
				$parse['x']	= $vl->x;
				$parse['y']	= $vl->y;
				
				$parse['action']	= parsetemplate(gettemplate('a'), array('href'=>'tc.php?id='.$_GET['id'].'&st=1&vl='.$vl->id, 'string'=>$lang['dieu_quan']));
				$parse['id'] = $_GET['id'];
				$parse['st'] = $_GET['st'];
				$parse['vl'] = $_GET['vl'];
				
				if($dtk->index==1 || $dtk->index==7){
					$parse['tg0_style']	='';
					$parse['tg1_style']	='style="display:none"';
				}else{
					$parse['tg0_style']	='style="display:none"';
					$parse['tg1_style']	='';
				}
				
				$parse['error_message'] = '';
				
				//echo "<pre>"; print_r($_Get); die();
				$rs = parsetemplate(gettemplate('ctc/ctc_st_form2'), $parse);
			}					 
		}
	}		
	
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Kiem tra mot lang co phai cua mot user hay khong
 * @return Thong tin cua lang
 */
function checkVillageOfUser_ctc($vl_id, $u_id){
	global $db;
	$vl_id = $db->getEscaped($vl_id);
	$sql = "SELECT id, name, x, y, nation_id FROM wg_villages WHERE id=$vl_id AND user_id=$u_id";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}

/**
 * @author Le Van Tu
 * @des hien thi form xac nhan gui linh tu lang vao diem tap ket
 * 
 */
function showConfirm1_ctc(){
	global $user, $lang;
	//die("AAAAAAAAAAAAAAAAAAAAAAAAAAAA");
	$rs = '';
	
	includeLang('rally_point');
	$parse = $lang;
	
	$vl = checkVillageOfUser_ctc($_GET['vl'], $user['id']);
	
	//echo "<pre>"; print_r($_GET); die();
	
	if($vl){
		
		$ts = GetListTroopVilla($vl);
		
		if($ts){
			
			$colinh = false;
			
			for($i=0; $i<11; $i++){
				$t = $ts[$i];
				$parse['icon'.($i+1)]	= CTC_ROOT_PATH.$t->icon;
				$parse['title'.($i+1)]	= $lang[$t->name];
				
				if($_GET['t'.($i+1)]>0 && $t->sum > 0){
					$parse['t'.($i+1)]		= min($t->sum, round($_GET['t'.($i+1)], 0));
					$parse['class'.($i+1)]	= '';
					$colinh					= true;
				}else{
					$parse['t'.($i+1)]		= 0;
					$parse['class'.($i+1)]	= ' class="c"';
				}
				
			}
		
			$parse['title12']	= $lang['hero'];
			
			$hr = GetHeroVillage($vl->id);
			if($hr){
				$parse['t12']		= 1;
				$parse['class12']	= '';
				$colinh				= true;
			}else{
				$parse['t12']	= 0;
				$parse['class12']	= 'class="c"';
			}
			
			if($colinh){
				$parse['upkeep']	= $sk;
				
				$parse['village_name']	= $vl->name;
				$parse['x']	= $vl->x;
				$parse['y']	= $vl->y;
				
				$parse['action']	= parsetemplate(gettemplate('a'), array('href'=>'tc.php?id='.$_GET['id'].'&st=1&vl='.$vl->id, 'string'=>$lang['dieu_quan']));
				$parse['id'] = $_REQUEST['id'];
				$parse['st'] = $_REQUEST['st'];
				$parse['vl'] = $_REQUEST['vl'];
				
				$parse['duration']	= "00:00:00";
				$parse['time_at']	= date("H:i:s");
				$parse['date_at']	= date("d:m:Y");
				
				$parse['target']	= $lang['diem_tap_ket'];
				
				//echo "<pre>"; print_r($_POST); die();
				$rs .= parsetemplate(gettemplate('ctc/ctc_cf_form1'), $parse);
			}					 
		}
	}		
	
	return $rs;
}

/**
 * @author Le Van Tu
 * @des hien thi form xac nhan di chuyen linh giua cac diem tap ket
 */
function showConfirm2_ctc($dtk){
	global $user, $lang;
	
	$rs = '';
	
	includeLang('rally_point');
	$parse = $lang;
	
	$vl = checkVillageOfUser_ctc($_GET['vl'], $user['id']);
	
	//echo "<pre>"; print_r($_GET); die();
	
	if($vl){
		
		$ts = getTroopInDTK_ctc($vl, $dtk->id);
		
		if($ts){
			
			//echo "<pre>"; print_r($ts); die();
			
			$colinh = false;
			
			for($i=0; $i<11; $i++){
				$t = $ts[$i];
				$parse['icon'.($i+1)]	= CTC_ROOT_PATH.$t->icon;
				$parse['title'.($i+1)]	= $lang[$t->name];
				
				if($_GET['t'.($i+1)]>0 && $t->sum > 0){
					$parse['t'.($i+1)]		= min($t->sum, round($_GET['t'.($i+1)], 0));
					$parse['class'.($i+1)]	= '';
					$sps[] 					= $t->speed;
					$colinh					= true;
				}else{
					$parse['t'.($i+1)]		= 0;
					$parse['class'.($i+1)]	= ' class="c"';
				}				
			}
		
			$parse['title12']	= $lang['hero'];
			
			$hr = getHero_ctc($vl->id, $dtk->id);
			if($hr){
				$parse['t12']		= '1';
				$parse['class12']	= '';
				$sps[] 				= $hr->speed;
				$colinh				= true;
			}else{
				$parse['t12']		= 0;
				$parse['class12']	= 'class="c"';
			}
			
			if($colinh){
				if($_GET['tg']==0){//Ve thanh
					$dr = 0;					
					$parse['to'] = $lang['ve'];
				}else{
					$s = getS_ctc($dtk->index, $_GET['tg'], $dtk->ct_id);
					if(!$s){
						header("Location: tc.php?id=".$dtk->id);exit();
					}
					$sp = min($sps);
					$dr	= ($s/$sp)*3600;
				}
				
				$parse['upkeep']	= $sk;
				
				$parse['village_name']	= $vl->name;
				$parse['x']	= $vl->x;
				$parse['y']	= $vl->y;
				
				$parse['id'] = $_REQUEST['id'];
				$parse['st'] = $_REQUEST['st'];
				$parse['vl'] = $_REQUEST['vl'];
				$parse['tg'] = $_REQUEST['tg'];
				
				$parse['duration']	= TimeToString($dr);
				$parse['time_at']	= date("H:i:s", $dr+time());
				$parse['date_at']	= date("d:m:Y", $dr+time());
								
				$parse['target']	= getCung_ctc($_GET['tg']);
				
				$rs .= parsetemplate(gettemplate('ctc/ctc_cf_form1'), $parse);
			}					 
		}
	}		
	
	return $rs;
}


/**
 * @author Le Van Tu
 * @des Xac nhan buoc cuoi cung va cap nhat co so du lieu
 */
function confirmSendTroop1_ctc($dtk){
	global $user;
	
	$vl = checkVillageOfUser_ctc($_REQUEST['vl'], $user['id']);
	
	//echo "<pre>"; print_r($_REQUEST); die();
	
	if($vl){
		$lt = GetListTroopVilla($vl);
		if($lt){
			for($i=0; $i<11; $i++){
				$t = $lt[$i];
				if(is_numeric($_REQUEST['t'.($i+1)]) && $_REQUEST['t'.($i+1)]>0 && $t->sum>0){
					
					$sum = min($t->sum, round($_REQUEST['t'.($i+1)], 0));
					
					changeTroop_ctc($vl->id, $t->id, $sum, $_REQUEST['id'], getVillageSide($vl->id));					
					changeTroopVillage($vl->id, $t->id, -$sum);					
				}
			}
		}
		
	
		
		$hr = GetHeroVillage($vl->id);
		if($hr && $_REQUEST['t12']==1){
			
			changeVillageOfHero($hr->id, "0");
			
			insertHeros_ctc($vl->id, $hr->id, $_REQUEST['id']);
			
			
		}
		
		//echo "<pre>"; print_r($hr); die();
		
	}
	
	$vls = getVillageOfUser_ctc($user['id']);
	$rs = showTroopInDTK_ctc($vls, $dtk);
	$vlt = showTroopInVillage_ctc($vls, $dtk);
	if($vlt && $rs){
		$rs .= "<br>";
	}
	$rs .= $vlt;
	if(checkMinhChu_ctc($user['id'])){
		$alt = showTroopAli_ctc($user['alliance_id'], $dtk->id, getArrayOfTroops());
		if($alt && $rs){
			$rs .= "<br>";
		}
		$rs .= $alt;
	}
	return $rs;
}

/**
 * @author Le Van Tu
 * @des xu ly di chuyen linh giua cac diem trong chien truong.
 * 		Xac nhan buoc cuoi cung va cap nhat co so du lieu
 */
function confirmSendTroop2_ctc($dtk){
	global $user;
	
	$vl = checkVillageOfUser_ctc($_GET['vl'], $user['id']);
	
	if($vl){
		
		$lt = getTroopInDTK_ctc($vl, $dtk->id);
		
		//echo "<pre>"; print_r($_GET); die();
		
		if($lt){
			
			if($_GET['tg']==0){
				for($i=0; $i<12; $i++){
					$t = $lt[$i];
					if(is_numeric($_GET['t'.($i+1)]) && $_GET['t'.($i+1)]>0 && $t->sum>0){
						$sum = min($t->sum, round($_GET['t'.($i+1)], 0));
						
						changeTroopVillage($vl->id, $t->id, $sum);
						
						changeTroop_ctc($vl->id, $t->id, -$sum, $dtk->id, getVillageSide($vl->id));
					}
				}
				
				$hr = getHero_ctc($vl->id, $dtk->id);
				
				if($hr && $_GET['t12']==1){
					
					changeVillageOfHero($hr->id, $vl->id);
					
					deleteHeros_ctc($hr->ctc_hr_id);
				}
				
				$vls = getVillageOfUser_ctc($user['id']);
				$rs = showTroopInDTK_ctc($vls, $dtk);
				$rs .= showTroopInVillage_ctc($vls, $dtk);
				if(checkMinhChu_ctc($user['id'])){
					$rs .= showTroopAli_ctc($user['alliance_id'], $dtk->id, getArrayOfTroops());
				}
				return $rs;
				
			}else{

				$sps = array();
				
				$dtk_to = getDTKByIndex_ctc($_GET['tg'], $dtk->ct_id);
				
				if(!$dtk_to){ header("Location: tc.php?id=".$dtk->id); exit(); }
				
				$sid = insertSendTroop_ctc($dtk->id, $dtk_to->id);
				
				for($i=0; $i<12; $i++){
					$t = $lt[$i];
					if(is_numeric($_GET['t'.($i+1)]) && $_GET['t'.($i+1)]>0 && $t->sum>0){
						
						$sum = min($t->sum, round($_GET['t'.($i+1)], 0));
						
						insertTroopOnWay_ctc($t->id, $sid, $sum);
						
						$sps[]	= $t->speed;					
						
						changeTroop_ctc($vl->id, $t->id, -$sum, $dtk->id);
											
					}
				}
				
				$hr = getHero_ctc($vl->id, $dtk->id);
				
				if($hr && $_GET['t12']==1){
					
					insertHeroOnWay_ctc($hr->id, $sid);
					
					deleteHeros_ctc($hr->ctc_hr_id);
					
					$sps[]	= $hr->speed;	
					
				}
				
				if(count($sps)>0){
					$sp = min($sps);
					$s 	= getS_ctc($dtk->index, $dtk_to->index, $dtk->ct_id);
					$ct = ($s/$sp)*3600;
					 
					InsertStatus($vl->id, $sid, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$ct), $ct, 26);
					//header("Location: tc.php?id=".$dtk_to->id);exit();
				}else{
					deleteSendTroop_ctc($sid);
				}
			}
			
		}
		
	}
	
	$timer = 6;
	$vls = getVillageOfUser_ctc($user['id']);
	$rs = showTroopOnTheWay_ctc($vls, $dtk, $timer);
	$icmt = showTroopIncoming_ctc($vls, $dtk, $timer);
	if($icmt && $rs){
		$rs .= "<br>";
	}
	$rs .= $icmt;
	
	return $rs;
	//header("Location: tc.php?id=".$_REQUEST['id']);exit();
}

/**
 * @author Le Van Tu
 * @des thay doi so linh cua mot lang o mot diem tap ket khong.
 * @param 
 */
function changeTroop_ctc($vl_id, $t_id, $sum, $dtk_id, $cong_thu='0'){
	global $db;
	if($sum!=0){
		if(checkTroop_ctc($vl_id, $t_id, $dtk_id)){
			$sql = "UPDATE wg_ctc_troops SET num = num+$sum WHERE village_id=$vl_id AND troop_id=$t_id AND dtk_id=$dtk_id";
			$db->setQuery($sql);
			if(!$db->query()){
				globalError2($sql);
			}
		}else{
			insertTroop_ctc($vl_id, $t_id, $sum, $cong_thu, $dtk_id);
		}
	}		
}

/**
 * @author Le Van Tu
 * @des chen mot record vao bang wg_ctc_troops.
 * @param 
 */
function insertTroop_ctc($vl_id, $t_id, $sum, $cong_thu, $dtk_id){
	global $db;
	$sql = "INSERT INTO wg_ctc_troops (village_id, troop_id, num, cong_thu, dtk_id) VALUES ($vl_id, $t_id, $sum, $cong_thu, $dtk_id)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @des Kiem tra xem co ton tai 1 loai linh cua mot lang o mot diem tap ket khong.
 * @param 
 */
function checkTroop_ctc($vl_id, $t_id, $dtk_id){
	global $db;
	$sql = "SELECT COUNT(*) FROM wg_ctc_troops WHERE village_id=$vl_id AND troop_id=$t_id AND dtk_id=$dtk_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

function getTroopInDTK_ctc($vl, $dtk_id){
	global $db, $game_config;
	
	$sql = "SELECT * FROM wg_ctc_troops WHERE village_id=$vl->id AND dtk_id=$dtk_id";
	$db->setQuery($sql);
	$lt = $db->loadObjectList();
	//echo "<pre>"; print_r($lt); die();
	if($lt){
		//luu danh sach linh theo dinh dang moi de thao tac.
		foreach($lt as $t){
			$temp[$t->troop_id]=$t->num;
		}	
		
		$nts = getTroopsOfNation($vl->nation_id);
		if($nts){
			foreach($nts as &$nt){
				$nt->sum 	= $temp[$nt->id]>0 ? $temp[$nt->id] : 0;
				$nt->speed 	= $nt->speed*$game_config['k_speed'];
			}
		}	
	}		
	
	return $nts;
}


/**
 * @author Le Van Tu
 * @des lay thong tin do thi duong di cua mot chien truong
 */
function getGraph_ctc($ct_id){
	global $db;
	
	$rs = null;
	
	$sql = "SELECT wg_ctc_graph.* FROM wg_ctc_graph	WHERE wg_ctc_graph.s>0 AND wg_ctc_graph.ct_id = $ct_id";
	$db->setQuery($sql);
	$ds = $db->loadObjectList();
	
	if($ds){
		foreach($ds as $d){
			$rs[$d->p_begin][$d->p_end]['s']	= $d->s;
			$rs[$d->p_begin][$d->p_end]['id'] 	= $d->id;
		}
	}
	
	//secho "<pre>"; print_r($rs); die();
		
	return $rs;	
}

/**
 * @author Le Van Tu
 * @des lay khoang cach giua 2 diem
 * @param index cua 2 diem
 */
function getS_ctc($p_begin, $p_end, $ct_id){
	global $db;
	$sql = "SELECT s FROM wg_ctc_graph WHERE p_begin=$p_begin AND p_end=$p_end AND ct_id=$ct_id";
	$db->setQuery($sql);
	return $db->loadResult()/4;
}

/**
 * @author Le Van Tu
 * @des doi chi so (tu 1->5) thanh "cung"
 */
function getCung_ctc($index){
	global $lang;
	switch($index){
		case 0:
			return $lang['thanh'];
			break;
		case 1:
			return $lang['diem_tap_ket'];
			break;
		case 2:
			return $lang['kim'];
			break;
		case 3:
			return $lang['thuy'];
			break;
		case 4:
			return $lang['moc'];
			break;
		case 5:
			return $lang['hoa'];
			break;
		case 6:
			return $lang['tho'];
			break;
		case 7:
			return $lang['diem_tap_ket'];
			break;
	}
}

/**
 * @author Le Van Tu
 * @des Chen mot record vao bang wg_ctc_send_troop
 */
function insertSendTroop_ctc($dtk_from_id, $dtk_to_id){
	global $db;
	$sql = "INSERT INTO wg_ctc_send_troop (dtk_from_id, dtk_to_id, status) VALUES ($dtk_from_id, $dtk_to_id, 0)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}else{
		return $db->insertid();
	}
}


/**
 * @author Le Van Tu
 * @des set status cho mot record vao bang wg_ctc_send_troop
 */
function setStatusSendTroop_ctc($id){
	global $db;
	$sql = "UPDATE wg_ctc_send_troop SET `status`=1 WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @des Xoa mot record trong bang wg_ctc_send_troop
 */
function deleteSendTroop_ctc($id){
	global $db;
	$sql = "DELETE FROM wg_ctc_send_troop WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
} 

/**
 * @author Le Van Tu
 * @des Chen mot record vao bang wg_ctc_troop_onway
 */
function insertTroopOnWay_ctc($troop_id, $st_id, $num){
	global $db;
	$sql = "INSERT INTO wg_ctc_troop_onway (troop_id, st_id, num, status) VALUES ($troop_id, $st_id, $num, 0)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @des lay danh sach ly dan di chuyen
 */
function getTroopOnWay_ctc($st_id){
	global $db;
	$sql = "SELECT * FROM wg_ctc_troop_onway WHERE st_id=$st_id";
	$db->setQuery($sql);
	return $db->loadObjectList();
}

/**
 * @author Le Van Tu
 * @des set status cho mot record vao bang wg_ctc_troop_onway
 */
function setStatusTroopOnWay_ctc($id){
	global $db;
	$sql = "UPDATE wg_ctc_troop_onway SET `status`=1 WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @des lay diem tap ket o mot chien truong khi bit chi so.
 */
function getDTKByIndex_ctc($index, $ct_id){
	global $db;
	$sql = "SELECT * FROM wg_ctc_diem_tap_ket WHERE ct_id=$ct_id AND `index`=$index";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}


/**
 * @author Le Van Tu
 * @des lay diem tap ket o mot chien truong khi biet id.
 */
function getDTKByID_ctc($id){
	global $db;
	$sql = "SELECT * FROM wg_ctc_diem_tap_ket WHERE id=$id";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Chen mot record vao bang wg_ctc_heros
 */
function insertHeros_ctc($village_id, $hero_id, $dtk_id){
	global $db;
	$sql = "INSERT INTO wg_ctc_heros (village_id, hero_id, dtk_id) VALUES ($village_id, $hero_id, $dtk_id)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @des xoa mot record vao bang wg_ctc_heros
 */
function deleteHeros_ctc($id){
	global $db;
	$sql = "DELETE FROM wg_ctc_heros WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @des lay thong tin tuong tai mot diem tap ket
 */
function getHero_ctc($vl_id, $dtk_id){
	global $db;
	$sql = "SELECT wg_heros.* , wg_ctc_heros.id AS ctc_hr_id
				FROM
					wg_heros ,
					wg_ctc_heros
				WHERE
					wg_ctc_heros.hero_id =  wg_heros.id AND
					wg_ctc_heros.village_id =  '$vl_id' AND
					wg_ctc_heros.dtk_id =  '$dtk_id'
				GROUP BY
					wg_heros.id";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}


/**
 * @author Le Van Tu
 * @des them mot record vao bang wg_ctc_hero_onway
 */
function insertHeroOnWay_ctc($hero_id, $st_id){
	global $db;
	$sql = "INSERT INTO wg_ctc_hero_onway (hero_id, st_id, status) VALUES ($hero_id, $st_id, 0)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @des lay thong tin hero dang di chuyen
 */
function getHeroOnWay_ctc($st_id){
	global $db;
	$sql = "SELECT * FROM wg_ctc_hero_onway WHERE st_id=$st_id AND wg_ctc_hero_onway.`status`=0";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}

/**
 * @author Le Van Tu
 * @des set status trong bang wg_ctc_hero_onway
 */
function setHeroOnWayStatus($id){
	global $db;
	$sql = "UPDATE wg_ctc_hero_onway SET wg_ctc_hero_onway.status = 1 WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}


/**
 * @author Le Van Tu
 * @des kiem tra xem mot lang la ben cong hay thu
 */
function getVillageSide($village_id){
	global $db;
	$sql = "SELECT
					wg_ctc_phe.cong_thu
				FROM
					wg_ctc_phe ,
					wg_allies ,
					wg_users ,
					wg_villages
				WHERE
					wg_ctc_phe.id =  wg_allies.phe_id AND
					wg_allies.id =  wg_users.alliance_id AND
					wg_users.id =  wg_villages.user_id AND
					wg_villages.id =  '$village_id' 
				GROUP BY
					wg_villages.id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @des Lay thong tin vu khi cua mot lang.
 */
function getListItem_ctc($village_id){
	global $db;
	$rs = null;
	//Lay danh sach Item cua linh lang nay
	$sql="SELECT * FROM wg_troop_items WHERE village_id=$village_id";
	$db->setQuery($sql);
	$its=$db->loadObjectList();
	if($its){
		//luu thanh mot mang de thao tac
		foreach($its as $it){
			//$rs[$it->troop_id]['id']=$it->id;
			if($it->status){
				$rs[$it->troop_id]=$it->level;
			}else{
				$rs[$it->troop_id]=$it->level-1;
			}			
		}
	}
	//echo "<pre>"; print_r($rs); die();
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Lay thong tin vu khi cua mot lang.
 */
function getListArmour_ctc($village_id){
	global $db;
	$rs = null;
	//Lay danh sach Item cua linh lang nay
	$sql="SELECT * FROM wg_troop_armour WHERE village_id=$village_id";
	$db->setQuery($sql);
	$its=$db->loadObjectList();
	if($its){
		//luu thanh mot mang de thao tac
		foreach($its as $it){
			//$rs[$it->troop_id]['id']=$it->id;
			if($it->status){
				$rs[$it->troop_id]=$it->level;
			}else{
				$rs[$it->troop_id]=$it->level-1;
			}
		}
	}
	//echo "<pre>"; print_r($rs); die();
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Kiem tra mot user da tham gia ctc hay chua
 */
function getAlliePhe_ctc($alli_id){
	global $db;
	$rs = null;
	$sql = "SELECT
						wg_ctc_phe.id,
						wg_ctc_phe.name,
						wg_ctc_phe.ct_id,
						wg_ctc_phe.cong_thu
					FROM
						wg_ctc_phe ,
						wg_allies
					WHERE
						wg_ctc_phe.id =  wg_allies.phe_id AND
						wg_allies.id =  '$alli_id' 
					GROUP BY
						wg_ctc_phe.id";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}

function checkMinhChu_ctc($user_id){
	global $db;
	$sql = "SELECT COUNT(*) FROM wg_allies WHERE user_id = $user_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @todo kiem tra 2 user co cung phe hay khong
 */
function checkSameSide_ctc($u_id_1, $u_id_2){
	return (getSideOfUser_ctc($u_id_1)==getSideOfUser_ctc($u_id_2));
}

/**
 * @author Le Van Tu
 * @todo lay phe cua mot user
 */
function getSideOfUser_ctc($uid){
	global $db;
	$sql = "SELECT wg_allies.phe_id FROM wg_users , wg_allies WHERE wg_users.alliance_id =  wg_allies.id AND wg_users.id=$uid";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @des xu ly su kien user dang ky ctc
 */
function regUser_ctc(){
	global $user;
	
	$alli = getAllie_ctc($user['alliance_id']);
	//Kiem tra xem alli da dang ky CTC chua
	if($alli->phe_id){
		//Kiem tra asu
		//echo "<pre>"; print_r($alli);die();
		
		//Tru ausu
		
		
		//cap nhat phe cho user
		changeUserPhe_ctc($user['id'], $alli->phe_id);
	}	
}

/**
 * @author Le Van Tu
 * @des xy ly su kien allies dang ky ctc
 */
function regAllie_ctc($chienTruongId, $congThu){
	global $user, $lang;
	$alli = getAllie_ctc($user['alliance_id']);
	//kiem tra xem user nay co phai la minh chu hay khong
	if($alli->user_id == $user['id']){
				
		$phe = getPostPhe_ctc($chienTruongId, $congThu);
		
		$ct = getCT_ctc($phe->ct_id);
		
		//cap nhat phe cho allie:
		changeAlliPhe_ctc($alli->id, $phe->id);		
				
		//cap nhat phe cho user:
		changeUserPhe_ctc($user['id'], $phe->id);
		
		//cap nhat chu dong minh:
		if(!$phe->chu){
			updateChuPhe_ctc($phe->id, $alli->id);
			$rs = $lang['dang_ky_thanh_cong'].$ct->name.". ".$lang['dang_ky_thanh_cong_chu_phe_1'];
			if($phe->cong_thu){
				$rs .= $lang['cong'];
			}else{
				$rs .= $lang['thu'];
			}
			
			$rs .= $lang['dang_ky_thanh_cong_chu_phe_2'];
			return $rs;
		}
		return $lang['dang_ky_thanh_cong'].$ct->name;
	}
	return 0;
}


/**
 * @author Le Van Tu
 * @des lay thong tin cua mot allie
 */
function getAllie_ctc($id){
	global $db;
	$sql = "SELECT * FROM wg_allies WHERE id = $id";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}

/**
 * @author Le Van Tu
 * @des cap nhat phe cho alli khi dang ky ctc
 */
function changeAlliPhe_ctc($id, $phe_id){
	global $db;
	$sql = "UPDATE wg_allies SET phe_id = $phe_id WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo cap nhat chu dong minh
 */
function updateChuPhe_ctc($sid, $alid){
	global $db;
	$sql = "UPDATE wg_ctc_phe SET chu=$alid WHERE id=$sid";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo cap nhat ten dong minh
 */
function updateSideName_ctc($id, $name){
	global $db;
	$sql = "UPDATE wg_ctc_phe SET name='$name' WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @des cap nhat phe cho alli khi dang ky ctc
 */
function changeUserPhe_ctc($id, $phe_id){
	global $db;
	$sql = "UPDATE wg_users SET phe_id = $phe_id WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @des lay id cua phe ma user chon
 */
function getPostPhe_ctc($chienTruongId, $congThu){
	global $db;	
	$sql = "SELECT * FROM wg_ctc_phe WHERE ct_id='$chienTruongId' AND cong_thu='$congThu'";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}


//===================Cac ham lien quan den attack=============================

/**
 * @author Le Van Tu
 * @des xu ly luot attack tai mot chien truong
 */
function executeAttack_ctc($status){
	global $db;
	//lay danh sach thap canh cua chien truong nay:
	$sql = "SELECT * FROM wg_ctc_diem_tap_ket WHERE cung!=0 AND cung!=6 AND ct_id=$status->object_id";
	$db->setQuery($sql);
	$dtks = $db->loadObjectList();
	if($dtks){
		foreach($dtks as $dtk){
			attack_ctc($dtk, $status->order_, $status->time_end);
		}
	}
	
	
	//Kiem tra va ket thuc cong thanh chien:
	if(checkEnd_ctc($status->object_id)){
		end_ctc($status->object_id);
	}
}

/**
 * @author Le Van Tu
 * @des Tinh toan attack tai mot thap canh
 * @return void
 */
function attack_ctc($dtk, $luot, $time){
	$art = getArrayOfTroops();
	
	//lay danh sach tuong va linh hai ben:
	$ats = getTroopAttackSide_ctc($dtk->id, $art);
	
	$ahrs = getHeroAttackSide_ctc($dtk->id);
	
	$dts = getTroopDefendSide_ctc($dtk->id, $art);
	
	$dhrs = getHeroDefendSide_ctc($dtk->id);
	
	//tinh tong cong, tong thu:
	$sa = getSumAttack_ctc($ats, $ahrs, $sat);
	$sd = getSumDefend_ctc($ats, $dts, $dhrs, $sdt);

	//Tinh so linh chet moi ben:
	//getSumTroopDie_ctc($sa, $sd, $sat, $sdt, $satd, $sdtd);
	calculateCasualtie($sa, $sd, $sat, $sdt, $satd, $sdtd, 3);
	
	//cap nhat thong so linh va hero:
	updateAttackTroopSide_ctc($sat, $satd, $ats, $sdkn, $sahp);	
	updateDefendTroopSide_ctc($sdt, $sdtd, $dts, $sakn, $sdhp);
	updateHerosAttack_ctc($ahrs, $sakn, $sahp);
	updateHerosAttack_ctc($dhrs, $sdkn, $sdhp);
		
	//kiem tra ben nao thang va cap nhat bang diem:
	updatePoint($sa, $sd, $dtk->id, $luot);

	//backup thong tin quan doi cua 2 ben:
	backUpTroop_ctc($ats, $dtk->id, $luot, 1);
	backUpTroop_ctc($dts, $dtk->id, $luot, 0);
	backUpHero_ctc($ahrs, $dtk->id, $luot);
	backUpHero_ctc($dhrs, $dtk->id, $luot);
	
	//Tao report:
	report_ctc($dtk->id, $luot, $ats, $dts, $ahrs, $dhrs, $art, $sa, $sd, $time);
}

/**
 * @author Le Van Tu
 * @des lay thong tin linh ben cong
 */
function getTroopAttackSide_ctc($dtk_id, $art){
	global $db;
	$rs = null;
//	$sql = "SELECT * FROM wg_ctc_troops WHERE wg_ctc_troops.cong_thu =  '1' AND wg_ctc_troops.num>0 AND wg_ctc_troops.dtk_id = '$dtk_id' ORDER BY wg_ctc_troops.village_id ASC";
	$sql = "SELECT * FROM wg_ctc_troops WHERE wg_ctc_troops.cong_thu =  '1' AND wg_ctc_troops.dtk_id = '$dtk_id' ORDER BY wg_ctc_troops.village_id ASC";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){
		$vl_id = 0;
				
		foreach($ts as $t){
			if($vl_id!=$t->village_id){
				$its = getListItem_ctc($t->village_id);
			}
										
			$rs[$t->village_id][$t->troop_id]	= $art[$t->troop_id];
			$rs[$t->village_id][$t->troop_id]['num']	= $t->num;
			$rs[$t->village_id][$t->troop_id]['attack']	= GetIncreaseAttack($art[$t->troop_id]['attack'], $its[$t->troop_id]);
			$rs[$t->village_id][$t->troop_id]['ctc_t_id']	= $t->id;
			
		}
		//echo "<pre>"; print_r($rs); die();
	}
	return $rs;
}

/**
 * @author Le Van Tu
 * @des lay thong tin linh ben thu
 */
function getTroopDefendSide_ctc($dtk_id, $art){
	global $db;
	$rs = null;
	$sql = "SELECT * FROM wg_ctc_troops WHERE wg_ctc_troops.cong_thu =  '0' AND wg_ctc_troops.num>0 AND wg_ctc_troops.dtk_id = '$dtk_id' ORDER BY wg_ctc_troops.village_id ASC";
//	$sql = "SELECT * FROM wg_ctc_troops WHERE wg_ctc_troops.cong_thu =  '0' AND wg_ctc_troops.dtk_id = '$dtk_id' ORDER BY wg_ctc_troops.village_id ASC";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){
		$vl_id = 0;
		foreach($ts as $t){
			if($vl_id!=$t->village_id){
				$its = getListArmour_ctc($t->village_id);
			}
			
			$rs[$t->village_id][$t->troop_id]	= $art[$t->troop_id];
			$rs[$t->village_id][$t->troop_id]['num']	= $t->num;
			$rs[$t->village_id][$t->troop_id]['attack']	= getIncreaseDefend($art[$t->troop_id]['attack'], $its[$t->troop_id]);
			
			$rs[$t->village_id][$t->troop_id]['ctc_t_id']	= $t->id;
		}
		//echo "<pre>"; print_r($rs); die();
	}
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Lay thong tin hero ben cong
 */
function getHeroAttackSide_ctc($dtk_id){
	global $db;
	
	$rs = null;
	
	$sql = "SELECT wg_heros.id, wg_heros.attack, wg_heros.keep_hour, wg_heros.hitpoint, wg_heros.`type`, wg_heros.speed, wg_heros.troop_id, wg_heros.user_id, wg_heros.level, wg_heros.cung_menh, wg_heros.kim, wg_heros.thuy, wg_heros.moc, wg_heros.hoa, wg_heros.tho, wg_heros.kinh_nghiem, wg_ctc_heros.village_id AS 'village_id', wg_ctc_heros.id AS 'ctc_h_id'
				FROM
					wg_ctc_heros ,
					wg_heros
				WHERE
					wg_ctc_heros.hero_id =  wg_heros.id AND
					wg_ctc_heros.cong_thu =  '1' AND
					wg_ctc_heros.dtk_id =  '$dtk_id'";
	$db->setQuery($sql);
	$hrs = $db->loadObjectList();
	if($hrs){
		foreach($hrs as $hr){
			$rs[$hr->village_id] = GetHeroInfoAttack($hr);
		}		
	}
	
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Lay thong tin hero ben thu
 */
function getHeroDefendSide_ctc($dtk_id){
	global $db;
	
	$rs = null;
	
	$sql = "SELECT wg_heros.id, wg_heros.attack, wg_heros.melee_defense, wg_heros.ranger_defense, wg_heros.magic_defense, wg_heros.keep_hour, wg_heros.hitpoint, wg_heros.`type`, wg_heros.speed, wg_heros.user_id, wg_heros.level, wg_heros.cung_menh, wg_heros.kim, wg_heros.thuy, wg_heros.moc, wg_heros.hoa, wg_heros.tho, wg_heros.kinh_nghiem, wg_ctc_heros.village_id AS 'village_id', wg_ctc_heros.id AS 'ctc_h_id'
				FROM
					wg_ctc_heros ,
					wg_heros
				WHERE
					wg_ctc_heros.hero_id =  wg_heros.id AND
					wg_ctc_heros.cong_thu =  '0' AND
					wg_ctc_heros.dtk_id =  '$dtk_id'";
	$db->setQuery($sql);	//die($sql);
	$hrs = $db->loadObjectList();
	if($hrs){
		foreach($hrs as $hr){
			$rs[$hr->village_id] = GetHeroInfoAttack($hr);
		}		
	}
	
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Tinh tong cong
 */
function getSumAttack_ctc($ats, $hrs, &$sat){
	$sat = 0;
	$rs = getSumAttackHeros_ctc($hrs);
	if($ats){
		foreach($ats as $vl_id=>$ts){
			if($hrs[$vl_id]){
				$tsc = $hrs[$vl_id]->tuong_sinh_cong;
			}else{
				$tsc = 0;
			}
			
			foreach($ts as $t){
				$rs += $t['attack']*$t['num']*(1+$tsc);
				$sat += $t['num'];
			}			
		}
	}
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Tinh tong thu
 */
function getSumDefend_ctc($ats, $dts, $hrs, &$sdt){
	getPerCentAttach_ctc($ats, $pml, $pmg, $pr);
	
	$rs = getSumDefendHeros_ctc($hrs, $pml, $pmg, $pr);
	
	if($dts){
		foreach($dts as $vl_id=>$ts){
			if($hrs[$vl_id]){
				$tst = $hrs[$vl_id]->tuong_sinh_thu;
			}else{
				$tst = 0;
			}
			
			foreach($ts as $t){
				switch($t['type']){
					case 1:
						$sml_ml	+= (1+$tst)*$t['num']*$t['melee_defense'];
						$sml_r	+= (1+$tst)*$t['num']*$t['ranger_defense'];
						$sml_mg	+= (1+$tst)*$t['num']*$t['magic_defense'];
						break;
					case 2:
						$smg_ml	+= (1+$tst)*$t['num']*$t['melee_defense'];
						$smg_r	+= (1+$tst)*$t['num']*$t['ranger_defense'];
						$smg_mg	+= (1+$tst)*$t['num']*$t['magic_defense'];
						break;
					case 3:
						$sr_ml	+= (1+$tst)*$t['num']*$t['melee_defense'];
						$sr_r		+= (1+$tst)*$t['num']*$t['ranger_defense'];
						$sr_mg	+= (1+$tst)*$t['num']*$t['magic_defense'];
						break;
				}
				
				$sdt += $t['num'];
			}			
		}
		
		$sml 	= $sml_ml + $smg_ml + $sr_ml;
		$smg	= $sml_mg + $smg_mg + $sr_mg;
		$sr	= $sml_r  + $smg_r  + $sr_r;
		
		$rs 	+= $sml*$pml + $smg*$pmg + $sr*$pr;
	}
	return $rs;
}


/**
 * @author Le Van Tu
 * @des Tinh tong su thu cua hero
 */
function getSumDefendHeros_ctc($hrs, $pml, $pmg, $pr){
	$rs = 0;
	if($hrs){
		foreach($hrs as $hr){
			$rs += $hr->melee_defense*(1+$hr->tuong_khac_cong)*$pml;
			$rs += $hr->magic_defense*(1+$hr->tuong_khac_cong)*$pmg;
			$rs += $hr->ranger_defense*(1+$hr->tuong_khac_cong)*$pr;
		}
	}
	return $rs;
}

/**
 * @author Le Van Tu
 * @des Lay phan tram suc cong moi loai
 */
function getPerCentAttach_ctc($ats, &$pml, &$pmg, &$pr){
	$pml	= 0;
	$pmg	= 0;
	$pr	= 0;
	$ml 	= 0;
	$mg	= 0;
	$r 	= 0;
	
	if($ats){		
		foreach($ats as $ts){
			foreach($ts as $t){
				switch($t['type']){
					case 1:
						$ml += $t['num']*$t['attack'];
						break;
					case 2:
						$mg += $t['num']*$t['attack'];
						break;
					case 3:
						$r += $t['num']*$t['attack'];
						break;
				}
			}
		}
		
		$s = $ml + $mg + $r;
		if($s>0){
			$pml	= $ml/$s;
			$pmg	= $mg/$s;
			$pr	= $r/$s;
		}
	}
}

/**
 * @author Le Van Tu
 * @des Tinh tong su cong cua hero
 */
function getSumAttackHeros_ctc($hrs){
	$rs = 0;
	if($hrs){
		foreach($hrs as $hr){
			$rs += $hr->attack*(1+$hr->tuong_khac_cong);
		}
	}
	return $rs;
}


/**
 * @author Le Van Tu
 * @des Tinh so linh chet moi ben
 */
function getSumTroopDie_ctc($sa, $sd, $sat, $sdt, &$satd, &$sdtd){
	if($sa>0 && $sd>0){
		if($sa>$sd){
			$y=$sd/$sa;
			$sumWinTroop	= $sat;
			$sumLoseTroop	= $sdt;
			$rs = 1;
		}else{
			$y=$sa/$sd;
			$sumWinTroop	= $sdt;
			$sumLoseTroop	= $sat;
			$rs = 0;
		}
		
		if($y<0.03){
			$x=round($sumLoseTroop*(1-$y)/2, 0);
		}else{
			if($y>0.5){
				$x=round(($sumWinTroop+$sumLoseTroop)/2, 0);
			}else{
				$x=round(($sumWinTroop+$sumLoseTroop)*$y, 0);
			}
		}
		
		if($x==0){
			$x=1;
		}
		
		if($rs){
			$sdtd	= round($x/(1+$y), 0);
			$satd	= $x-$sdtd;
		}else{
			$satd	= round($x/(1+$y), 0);
			$sdtd	= $x-$satd;
		}
	}else{
		//so linh chet ben tan cong
		$satd	= 0;
		//so linh chet ben phong thu.
		$sdtd = 0;
	}
	return $rs;
}

/**
 * @author Le Van Tu
 * @todo cap nhat so linh chet moi loai cho ben cong (cap nhat mang va backup)
 * @param	$st: 		so linh ban dau
 * 			$std: 	so linh chet
 * 			&$ats:	mang luu thong tinh linh cua phe cong
 * 			&$skh:	so bat com cua linh da chet (dung de tinh diem kinh nghiem)
 * 			&$hp:		mau ma tuong mat 
 */
function updateAttackTroopSide_ctc($st, $std, &$ats, &$skh, &$hp){	
	$skh 	= 0;
	$skhd = 0;
	$hp 	= 0;
	if($ats && $std){
		$p = $std/$st;
		$p = min($p, 1);
		foreach($ats as $vl_id=>&$ts){
			foreach($ts as $t_id=>&$t){
				$t['num_die'] = round($t['num']*$p, 0);
				$skh 	+= $t['num']*$t['keep_hour'];
				$skhd += $t['num_die']*$t['keep_hour'];
			}
		}
		$hp = 100*$skhd/$skh;
	}		
}

/**
 * @author Le Van Tu
 * @todo cap nhat so linh chet moi loai cho ben thu (cap nhat mang va backup)
 */
function updateDefendTroopSide_ctc($st, $std, &$dts, &$skh, &$hp){
	$skh 	= 0;
	$skhd = 0;
	$hp	= 0;
	if($dts && $std){
		$p = $std/$st;
		$p = min($p, 1);
		foreach($dts as &$ts){
			foreach($ts as &$t){
				$t['num_die'] = round($t['num']*$p, 0);
				$skh 	+= $t['num']*$t['keep_hour'];
				$skhd += $t['num_die']*$t['keep_hour'];
			}
		}
		if($skh>0){
			$hp =  100*$skhd/$skh;
		}			
	}	
}

/**
 * @author Le Van Tu
 * @todo cap nhat hero sau attack
 */
function updateHerosAttack_ctc(&$hrs, $kn, $hp){	
	if($hrs){
		$s 	= count($hrs);
		$kn 	= round($kn/$s, 0);
		$hp	= $hp/$s; 
		foreach($hrs as $vl=>&$hr){
			if($hp>=$hr->hitpoint){
				$hr->die = 1;
			}else{
				$hr->die = 0;
			}
			$hr->kinh_nghiem += $kn;
			$hr->hitpoint -= $hp;
		}
	}		
}


/**
 * @author Le Van Tu
 * @todo xoa mot record trong bang wg_ctc_heros va chen 1 record vao bang wg_ctc_heros_bk
 */
function backUpHero_ctc($hrs, $dtk_id, $luot){
	global $db;	
	if($hrs){
		foreach($hrs as $vl_id=>$hr){
			if($hr->die==1){
				changeHeroStatus($hr->id, -1);
				ChangeTroopKeepVillage($vl, -$hr->keep_hour);
				
				//xoa trong bang wg_ctc_heros:
				$sql = "DELETE FROM wg_ctc_heros WHERE hero_id = ".$hr->id;
				$db->setQuery($sql);
				if(!$db->query()){
					globalError2($sql);
				}
			}
			
			//Cap nhat kinh nghiem va mau cho hero:
			UpdateHero($hr->id, $hr->kinh_nghiem, $hr->hitpoint);
			
			//Luu vao bang wg_ctc_hero_bk:
			$d = date("Y:m:d");
			$sql = "INSERT INTO wg_ctc_hero_bk (`village_id`, `hero_id`, `dtk_id`, `die`, `luot`, `date_attack`) VALUES ($vl_id, $h_id, $dtk_id, $hr->die, $luot, '$d')";
			$db->setQuery($sql);
			if(!$db->query()){
				globalError2($sql);
			}
		}
	}	
}

/**
 * @author Le Van Tu
 * @todo cap nhat so luong linh va backup sau khi attack
 * @param 
 * 		$vts:		mang linh cua mot ben
 * 		$dtk_id:	id cua diem tap ket
 * 		$luot:	luot danh
 */
function backUpTroop_ctc($vts, $dtk_id, $luot, $side){
	global $db;
	if($vts){
		foreach($vts as $vl_id=>$ts){
			foreach($ts as $id=>$t){
				if($t['num_die']>0){
					//cap nhat so linh:
					$n = $t['num']-$t['num_die'];
					$sql = "UPDATE wg_ctc_troops SET num=$n WHERE id=".$t['ctc_t_id'];
					$db->setQuery($sql);
					if(!$db->query()){
						globalError2($sql);
					}
					
					changeTroopKeep($vl_id, $id, -$t['num_die']);
				}else{
					$t['num_die'] = 0;
				}
				
				//chen thong tin backup vao bang wg_ctc_troops_bk
				$d = date("Y:m:d");
				$sql = "INSERT INTO wg_ctc_troops_bk (`village_id`, `troop_id`, `num`, `die_num`, `dtk_id`, `luot`, `date_attack`) VALUES ('$vl_id', '$id', '".$t['num']."', '".$t['num_die']."', '$dtk_id', '$luot', '$d')";
				$db->setQuery($sql);
				if(!$db->query()){
					globalError2($sql);
				}
			}
		}
	}
}

/**
 * @author Le Van Tu
 * @todo kiem tra ben thang va cap nhat bang diem
 */
function updatePoint($sa, $sd, $dtk_id, $luot){
	global $db;
	if($sa>$sd){
		$p = 1;
	}else{
		$p = 0;
	}
	$d = date("Y:m:d");
	$sql = "INSERT INTO wg_ctc_tran_dau (`dtk_id`, `luot`, `phe_thang`, `date_attack`) VALUES ($dtk_id, $luot, $p, '$d')";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo tao report cho moi diem danh
 */
function report_ctc($dtk_id, $luot, $vats, $vdts, $ahs, $dhs, $arrayTroop, $sat, $sdf, $time){
	global $db, $lang;
	includeLang("ctc");
	$parse = array();
	$parse = $lang;
	for($i=1; $i<=33; $i++){
		$parse['a_'.$i] = 0;
		$parse['c_a_'.$i] = 0;
		$parse['d_'.$i] = 0;
		$parse['c_d_'.$i] = 0;
		$parse['a_c_'.$i] = "c";
		$parse['d_c_'.$i] = "c";
		$parse['c_a_c_'.$i] = "c";
		$parse['c_d_c_'.$i] = "c";
	}
	
	for($i=1; $i<=3; $i++){
		$parse['a_h_'.$i] = 0;
		$parse['c_a_h_'.$i] = 0;
		$parse['d_h_'.$i] = 0;
		$parse['c_d_h_'.$i] = 0;
		$parse['a_h_c_'.$i] = "c";
		$parse['d_h_c_'.$i] = "c";
		$parse['c_a_h_c_'.$i] = "c";
		$parse['c_d_h_c_'.$i] = "c";
	}
	
	if($ahs){
		foreach($ahs as $ah){
			switch($arrayTroop[$ah->troop_id]['nation_id']){
				case 1:
					$parse['a_h_2']++;
					$parse['a_h_c_2'] = "";
					if($ah->die==1){
						$parse['c_a_h_2']++;
						$parse['c_a_h_c_2'] = "";
					}
					break;
				case 2:
					$parse['a_h_3']++;
					$parse['a_h_c_3'] = "";
					if($ah->die==1){
						$parse['c_a_h_3']++;
						$parse['c_a_h_c_3'] = "";
					}
					break;
				case 3:
					$parse['a_h_1']++;
					$parse['a_h_c_1'] = "";
					if($ah->die==1){
						$parse['c_a_h_1']++;
						$parse['c_a_h_c_1'] = "";
					}
					break;	
			}
		}
	}
	
	if($dhs){
		foreach($dhs as $dh){
			switch($arrayTroop[$dh->troop_id]['nation_id']){
				case 1:
					$parse['d_h_2']++;
					$parse['d_h_c_2'] = "";
					if($dh->die==1){
						$parse['c_d_h_2']++;
						$parse['c_d_h_c_2'] = "";
					}
					break;
				case 2:
					$parse['d_h_3']++;
					$parse['d_h_c_3'] = "";
					if($dh->die==1){
						$parse['c_d_h_3']++;
						$parse['c_d_h_c_3'] = "";
					}
					break;
				case 3:
					$parse['d_h_1']++;
					$parse['d_h_c_1'] = "";
					if($dh->die==1){
						$parse['c_d_h_1']++;
						$parse['c_d_h_c_1'] = "";
					}
					break;	
			}
		}
	}
	
	if($vats){
		foreach($vats as $vl_id=>$ts){
			foreach($ts as $id=>$t){
				if($t['num']>0){
					$parse['a_'.$id] += $t['num'];
					$parse['a_c_'.$id] = "";
				}
				if($t['num_die']){
					$parse['c_a_'.$id] += $t['num_die'];
					$parse['c_a_c_'.$id] = "";
				}
			}
		}
	}
	
	if($vdts){
		foreach($vdts as $vl_id=>$ts){
			foreach($ts as $id=>$t){
				if($t['num']>0){
					$parse['d_'.$id] += $t['num'];
					$parse['d_c_'.$id] = "";
				}
				if($t['num_die']){
					$parse['c_d_'.$id] += $t['num_die'];
					$parse['c_d_c_'.$id] = "";
				}
			}
		}
	}
	
	if($sat>$sdf){
		$parse['side_win'] = $lang['attack_win'];
	}else{
		$parse['side_win'] = $lang['defend_win'];
	}
	
	$parse['time'] = date("H:i:s d-m-Y", strtotime($time));
	
	$content = parsetemplate(gettemplate("ctc/ctc_report"), $parse);
		
	$d = date("Y-m-d");
	$sql = "INSERT INTO wg_ctc_report (`title`, `content`, `dtk_id`, `luot`, `date_attack`) VALUES ('', '$content', '$dtk_id', '$luot', '$d')";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
	
	//echo "<pre>"; print_r($ahs); die();
	
	
}

//=================End cac ham lien quan den attack===========================

//--------------------Cac ham lien quan den the Cong Thanh Chien---------->
/**
 * @author Le Van Tu
 * @todo lay thong tin the cong thanh chien chua user
 */
function getTheBai_ctc($user_id){
	global $db;
	$sql = "SELECT the_bai_1, the_bai_2, the_bai_3 FROM wg_plus WHERE user_id=$user_id";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}


/**
 * @author Le Van Tu
 * @todo lay thong tin the cong thanh chien chua user
 */
function getSMSAttack_ctc($user_id){
	global $db;
	$sql = "SELECT sms_attack FROM wg_plus WHERE user_id=$user_id";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @todo cap nhat thong tin the bai cho user
 */
function updateTheBai_ctc($user_id, $v1, $v2, $v3){
	global $db;
	$sql = "UPDATE wg_plus SET the_bai_1=$v1, the_bai_2=$v2, the_bai_3=$v3 WHERE user_id=$user_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo cap nhat thong tin sms_attack cho user
 */
function updateSMSAttack_ctc($user_id, $v){
	global $db;
	$sql = "UPDATE wg_plus SET sms_attack=$v WHERE user_id=$user_id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError($sql);
		return false;
	}else{
		return true;
	}
}

/**
 * @author Le Van Tu
 * @todo them thong tin the bai cho user
 */
function insertTheBai_ctc($user_id, $v1, $v2, $v3){
	global $db;
	$sql = "INSERT INTO wg_plus (user_id, the_bai_1, the_bai_2, the_bai_3) VALUES ($user_id, $v1, $v2, $v3)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo kiem tra quyen tham gia ctc tuong ung voi the bai
 */
function checkTheBai_ctc($user_id){
	$thebai = getTheBai_ctc($user_id);
	if($thebai->the_bai_3 == 1){
		return 3;
	}
	
	if($thebai->the_bai_2 == 1){
		return 2;
	}
	
	if($thebai->the_bai_1 == 1){
		return 1;
	}
	
	return 0;
}
//------------------END Cac ham lien quan den the Cong Thanh Chien---------->

/**
 * @author Le Van Tu@todo
 * @todo Lay danh sach user chua mot phe
 */
function getUserOfSide_ctc($sid){
	global $db;
	$sql = "SELECT
						wg_users.username,
						wg_users.id
					FROM
						wg_users ,
						wg_villages ,
						wg_ctc_troops
					WHERE
						wg_users.id =  wg_villages.user_id AND
						wg_villages.id =  wg_ctc_troops.village_id AND
						wg_users.phe_id =  '$sid'
					GROUP BY
						wg_users.id";
	$db->setQuery($sql);//die($sql);
	return $db->loadObjectList();
}
 
/**
 * @author Le Van Tu@todo
 * @todo Lay danh sach ali cua mot phe
 */
function getAliOfSide_ctc($sid){
	global $db;
	$sql = "SELECT
					wg_allies.id,
					wg_allies.name,
					wg_allies.user_id,
					wg_allies.slogan,
					wg_allies.tag,
					wg_allies.point,
					wg_allies.rank,
					wg_allies.icon,
					wg_allies.description,
					wg_allies.phe_id
				FROM
					wg_allies
				WHERE
					wg_allies.phe_id =  '$sid'";
	$db->setQuery($sql);//die($sql);
	return $db->loadObjectList();
}

/**
 * @author Le Van Tu
 * @todo lay thong tin cua mot phe
 */
function getSideByCTId_ctc($ct_id){
	global $db;
	$sql = "SELECT
					wg_ctc_phe.ct_id,
					wg_ctc_phe.id,
					wg_ctc_phe.name,
					wg_ctc_phe.cong_thu
				FROM
					wg_ctc_phe
				WHERE
					wg_ctc_phe.ct_id =  '$ct_id'
				ORDER BY
					wg_ctc_phe.cong_thu ASC";
	$db->setQuery($sql);
	return $db->loadObjectList();
}

/**
 * @author Le Van Tu
 * @todo Lay danh sach user cua mot phe o diem tap ket
 */
function getUserSideDTK_ctc($dtk_id, $sid){
	global $db;
	$sql = "SELECT
					wg_users.id,
					wg_users.username
				FROM
					wg_ctc_troops ,
					wg_villages ,
					wg_users,
					wg_allies
				WHERE
					wg_villages.user_id =  wg_users.id AND
					wg_villages.id =  wg_ctc_troops.village_id AND
					wg_users.alliance_id =  wg_allies.id AND
					wg_ctc_troops.dtk_id =  '$dtk_id' AND
					wg_ctc_troops.num >0 AND
					wg_allies.phe_id = '$sid'
				GROUP BY
					wg_users.id";
	$db->setQuery($sql);//die($sql);
	return $db->loadObjectList();
}

/**
 * @author Le Van Tu
 * @todo hien thi trang thai linh den va di trong diem tap ket
 */
function showTroopMoveStatus_ctc($dtk_id, $side, &$timer){
	global $db, $lang;
	$parse = $lang;
	$rs = array();
	$row = gettemplate("ctc/ctc_troop_move_status");
	$parse['id'] = $dtk_id;
	//lay status linh dang di (ben cong):
	$sql = "SELECT
					wg_status.time_end
				FROM
					wg_status ,
					wg_ctc_send_troop ,
					wg_users ,
					wg_allies ,
					wg_villages
				WHERE
					wg_status.object_id =  wg_ctc_send_troop.id AND
					wg_status.`status` =  '0' AND
					wg_ctc_send_troop.`status` =  '0' AND
					wg_status.village_id =  wg_villages.id AND
					wg_villages.user_id =  wg_users.id AND
					wg_users.alliance_id =  wg_allies.id AND
					wg_status.`type` =  '26' AND
					wg_ctc_send_troop.dtk_from_id =  '$dtk_id' AND
					wg_allies.phe_id =  '".$side[1]->id."'
				GROUP BY
					wg_ctc_send_troop.id
				ORDER BY
					wg_status.time_end ASC";
	$db->setQuery($sql);//die($sql);
	$sts = $db->loadObjectList();
	if($sts){
		$timeEnd = strtotime($sts[0]->time_end);
		$timer++;
		$parse['duration']=TimeToString($timeEnd - time());
		$parse['mui_ten']='&laquo;';
		$parse['sum']=count($sts);
		$parse['timer']=$timer;
		$parse['class']='c4 f10';
		$parse['title']=$lang['ref'];
		$parse['image']="../images/un/a/def1.gif";
		$rs['attack_troop_move_status']=parsetemplate($row, $parse);		
	}else{
		$rs['attack_troop_move_status'] = "";
	}
	
	//lay status linh dang den (ben cong):
	$sql = "SELECT
					wg_status.time_end
				FROM
					wg_status ,
					wg_ctc_send_troop ,
					wg_users ,
					wg_allies ,
					wg_villages
				WHERE
					wg_status.object_id =  wg_ctc_send_troop.id AND
					wg_status.`status` =  '0' AND
					wg_ctc_send_troop.`status` =  '0' AND
					wg_status.village_id =  wg_villages.id AND
					wg_villages.user_id =  wg_users.id AND
					wg_users.alliance_id =  wg_allies.id AND
					wg_status.`type` =  '26' AND
					wg_ctc_send_troop.dtk_to_id =  '$dtk_id' AND
					wg_allies.phe_id =  '".$side[1]->id."'
				GROUP BY
					wg_ctc_send_troop.id
				ORDER BY
					wg_status.time_end ASC";
	$db->setQuery($sql);//die($sql);
	$sts = $db->loadObjectList();
	if($sts){
		$timeEnd = strtotime($sts[0]->time_end);
		$timer++;
		$parse['duration']=TimeToString($timeEnd - time());
		$parse['mui_ten']='&raquo;';
		$parse['sum']=count($sts);
		$parse['timer']=$timer;
		$parse['class']='c4 f10';
		$parse['title']=$lang['ref'];
		$parse['image']="../images/un/a/def2.gif";
		$rs['attack_troop_move_status'].=parsetemplate($row, $parse);		
	}else{
		$rs['attack_troop_move_status'] .= "";
	}
	
	//lay status linh dang di (ben thu):
	$sql = "SELECT
					wg_status.time_end
				FROM
					wg_status ,
					wg_ctc_send_troop ,
					wg_users ,
					wg_allies ,
					wg_villages
				WHERE
					wg_status.object_id =  wg_ctc_send_troop.id AND
					wg_status.`status` =  '0' AND
					wg_ctc_send_troop.`status` =  '0' AND
					wg_status.village_id =  wg_villages.id AND
					wg_villages.user_id =  wg_users.id AND
					wg_users.alliance_id =  wg_allies.id AND
					wg_status.`type` =  '26' AND
					wg_ctc_send_troop.dtk_from_id =  '$dtk_id' AND
					wg_allies.phe_id =  '".$side[0]->id."'
				GROUP BY
					wg_ctc_send_troop.id
				ORDER BY
					wg_status.time_end DESC";
	$db->setQuery($sql);//die($sql);
	$sts = $db->loadObjectList();
	if($sts){
		$timeEnd = strtotime($sts[0]->time_end);
		$timer++;
		$parse['duration']=TimeToString($timeEnd - time());
		$parse['mui_ten']='&raquo;';
		$parse['sum']=count($sts);
		$parse['timer']=$timer;
		$parse['class']='c4 f10';
		$parse['title']=$lang['ref'];
		$parse['image']="../images/un/a/def2.gif";
		$rs['defend_troop_move_status']=parsetemplate($row, $parse);		
	}else{
		$rs['defend_troop_move_status'] = "";
	}
	
	//lay status linh dang den (ben thu):
	$sql = "SELECT
					wg_status.time_end
				FROM
					wg_status ,
					wg_ctc_send_troop ,
					wg_users ,
					wg_allies ,
					wg_villages
				WHERE
					wg_status.object_id =  wg_ctc_send_troop.id AND
					wg_status.`status` =  '0' AND
					wg_ctc_send_troop.`status` =  '0' AND
					wg_status.village_id =  wg_villages.id AND
					wg_villages.user_id =  wg_users.id AND
					wg_users.alliance_id =  wg_allies.id AND
					wg_status.`type` =  '26' AND
					wg_ctc_send_troop.dtk_to_id =  '$dtk_id' AND
					wg_allies.phe_id =  '".$side[0]->id."'
				GROUP BY
					wg_ctc_send_troop.id
				ORDER BY
					wg_status.time_end DESC";
	$db->setQuery($sql);//die($sql);
	$sts = $db->loadObjectList();
	if($sts){
		$timeEnd = strtotime($sts[0]->time_end);
		$timer++;
		$parse['duration']=TimeToString($timeEnd - time());
		$parse['mui_ten']='&laquo;';
		$parse['sum']=count($sts);
		$parse['timer']=$timer;
		$parse['class']='c4 f10';
		$parse['title']=$lang['ref'];
		$parse['image']="../images/un/a/def1.gif";
		$rs['defend_troop_move_status'].=parsetemplate($row, $parse);		
	}else{
		$rs['defend_troop_move_status'] .= "";
	}
	
	return $rs;
}


//=================cac ham lien quan den hien thi quan doi==================>
/**
 * @author Le Van Tu
 * @todo lay thong tin quan doi cua toan lien minh
 */
function showTroopAli_ctc($ali_id, $dtk_id, $art){
	global $db, $lang;
	$parse = $lang;
	
	for($i=1; $i<=3; $i++){
		$parse['a_h_'.$i] = 0;
		$parse['a_h_c_'.$i] = "c";		
	}
	
	for($i=1; $i<=33; $i++){
		$parse['a_'.$i] = 0;
		$parse['a_c_'.$i] = "c";
	}
	
	//Lay thong tin hero
	$sql = "SELECT
					wg_heros.troop_id
				FROM
					wg_heros ,
					wg_ctc_heros ,
					wg_villages ,
					wg_users
				WHERE
					wg_heros.id =  wg_ctc_heros.hero_id AND
					wg_ctc_heros.village_id =  wg_villages.id AND
					wg_villages.user_id =  wg_users.id AND
					wg_users.alliance_id =  '$ali_id' AND
					wg_ctc_heros.dtk_id =  '$dtk_id'
				GROUP BY
					wg_heros.id";
	$db->setQuery($sql);
	$hs = $db->loadObjectList();
	if($hs){
		foreach($hs as $h){
			switch($art[$h->troop_id]['nation_id']){
				case 1:
					$parse['a_h_2']++;
					if($h->die==1){
						$parse['c_a_h_2']++;
						$parse['a_h_c_2'] = "";
					}
					break;
				case 2:
					$parse['a_h_3']++;
					if($h->die==1){
						$parse['c_a_h_3']++;
						$parse['a_h_c_3'] = "";
					}
					break;
				case 3:
					$parse['a_h_1']++;
					if($h->die==1){
						$parse['c_a_h_1']++;
						$parse['a_h_c_1'] = "";
					}
					break;	
			}
		}
	}
	
	//Lay thong tin linh
	$sql = "SELECT
					wg_ctc_troops.troop_id,
					wg_ctc_troops.num
				FROM
					wg_ctc_troops ,
					wg_villages ,
					wg_users
				WHERE
					wg_ctc_troops.village_id =  wg_villages.id AND
					wg_villages.user_id =  wg_users.id AND
					wg_users.alliance_id =  '$ali_id' AND
					wg_ctc_troops.dtk_id =  '$dtk_id'
				GROUP BY
					wg_ctc_troops.id";
	$db->setQuery($sql);//die($sql);
	$ts = $db->loadObjectList();
	if($ts){
		foreach($ts as $t){
			if($t->num>0){
				$parse['a_'.$t->troop_id] += $t->num;
				$parse['a_c_'.$t->troop_id] = "";
			}
		}
	}
	
	//echo "<pre>"; print_r($hs); die();
	
	return parsetemplate(gettemplate("ctc/ctc_troop_ali"), $parse);
}

/**
 * @author Le Van Tu
 * @des hien thi linh cua mot user o diem tap ket
 */
function showTroopOtherUser_ctc($vls, $dtk, $show){
	global $lang;
	
	includeLang('rally_point');
	includelang("troop");
	
	$parse = $lang;
	
	$rs = '';
	
	if($vls){
		
		$row = gettemplate("ctc/ctc_list_troop_row_2");
				
		foreach($vls as $vl){
			
			
			$lt = getTroopInDTK_ctc($vl, $_REQUEST['id']);
			//echo "<pre>"; print_r($lt); die();
			if($lt){
				$sk = 0;
				
				for($i=0; $i<11; $i++){
					$t = $lt[$i];
					
					$parse['icon'.($i+1)] 	= CTC_ROOT_PATH.$t->icon;
					$parse['title'.($i+1)]	= $lang[$t->name];
					if($t->sum>0 && $show){						
						$parse['t'.($i+1)]		= $t->sum;
						$parse['class'.($i+1)]	= '';
						$sk += $t->sum*$t->keep_hour;
					}else{
						if($show){
							$parse['t'.($i+1)]		= 0;
						}else{
							$parse['t'.($i+1)]		= "?";
						}							
						$parse['class'.($i+1)]	= ' class="c"';
					}
				}
				
				$hr = getHero_ctc($vl->id, $dtk->id);
				if($hr && $show){
					$parse['t12']		= 1;
					$parse['class12']	= '';
					$sk += $hr->keep_hour;
				}else{
					if($show){
						$parse['t12']		= 0;
					}else{
						$parse['t12']		= "?";
					}					
					$parse['class12']	= ' class="c"';
				}
				
				$parse['vln'] 	= $vl->name;
				if($rows){
					$rows .= gettemplate("ctc/ctc_space_row");
				}
				
				$rows .= parsetemplate($row, $parse);
			}
		}
		
		if($rows){
			$parse['title'] 	= $lang['quan_doi_cua'].GetPlayerName($vl->id); 
			$parse['rows'] 	= $rows;
			$rs = parsetemplate(gettemplate("ctc/ctc_list_troop_2"), $parse);
		}
	}
	
	return $rs;
}
//<<<<<<<<<<<<<<<<<<<<<<END: cac ham lien quan den hien thi quan doi<<<<<<<<<<<<<<<<<<<<<<<<

//===============Cac ham lien quan den ket thuc cong thanh chien=============>
/**
 * @author Le Van TU
 * @todo kiem tra xem ket thuc ctc chua
 */
function checkEnd_ctc($ct_id){
	global $db;
	$sql = "SELECT COUNT(*) FROM wg_status WHERE wg_status.`status`='0' AND wg_status.`type`='27' AND `object_id`='$ct_id'";
	$db->setQuery($sql);
	if($db->loadResult()>0){
		return false;
	}else{
		return true;
	}
}

/**
 * @author Le Van Tu
 * @todo xu ly khi ket thuc ctc
 */
function end_ctc($ct_id){
	global $db;
	//tim phe thang:
	$wonderSide = getWonderSide_ctc($ct_id);
	
	$loseSide = getLoseSide_ctc($ct_id, $wonderSide->cong_thu>0?0:1);
	
	//lay danh sach user ben thang:
	$wus = getUserSide_ctc($wonderSide->id);
	
	//lay danh sach user ben thua:
	$lus = getUserSide_ctc($loseSide->id);
	
	//chia tien thuong:
	reward_ctc($ct_id, $wonderSide->cong_thu, $wus);

	//tra tat cac quan trong ctc ve lang:
	returnTroop_ctc($ct_id);
	
	//Huy the bai:
	if($wus){
		foreach($wus as $wu){
			destroyCard_ctc($wu->id);
		}
	}
	
	if($lus){
		foreach($lus as $lu){
			destroyCard_ctc($lu->id);
		}
	}
	
	//neu khong con chien truong nao mo thi xoa the_bai_1
	$sql = "SELECT COUNT(*) FROM #__status WHERE `type`='27' AND `status`=0";
	$db->setQuery($sql);
	if($db->loadResult()){
		destroyAllCard1_ctc();
	}
}

/**
 * @author Le Van Tu
 * @todo Chia tien thuong cho user
 */
function reward_ctc($ct_id, $cong_thu, $us){
	
	if($us){
		$art = getArrayOfTroops(); //thong tin cac loai linh
		
		//Tinh tong so bat com:
		$stk = getSumTroopKeep_ctc($ct_id, $cong_thu, $art);
		
		//Tinh so asu thuong:
		$sumAsu = getSumAsu_ctc($us);
		
		//chia thuong cho tung user:		
		foreach($us as $u){
			rewardUser_ctc($u->id, $sumAsu, $stk, $art);			
		}
	}		
}


/**
 * @author Le Van Tu
 * @todo tra tat ca quan trong ctc ve thanh
 */
function returnTroop_ctc($ct_id){
	global $db;
	//lay danh sach diem tap ket cua chien truong nay:
	$sql = "SELECT wg_ctc_diem_tap_ket.id FROM wg_ctc_diem_tap_ket WHERE wg_ctc_diem_tap_ket.ct_id =  '$ct_id'";
	$db->setQuery($sql);
	$dtks = $db->loadObjectList();
	if($dtks){
		foreach($dtks as $dtk){
			
			//Tra quan dang di chuyen:
			$sql = "SELECT * FROM #__ctc_send_troop WHERE (`dtk_from_id`='$dtk->id' OR `dtk_to_id`='$dtk->id') AND ``status`=0";
			$db->setQuery($sql);
			$sts = $db->loadObjectList();
			if($sts){
				foreach($sts as $st){
					//cap nhat status = 1 cho bang #__status
					$sql = "UPDATE #__status SET `status` WHERE `type`='26' AND `object_id`='$st->id'";
					$db->setQuery($sql);
					if($db->query()){
						//Tra tuong:
						$sql = "SELECT * FROM #__ctc_hero_onway WHERE `st_id`='$st->id' AND `status`='0'";
						$db->setQuery($sql);
						$hr = null;
						$db->loadObject($hr);
						if($hr){
							changeVillageOfHero($hr->hero_id, $st->village_id);
							//Cap nhat status:
							$sql = "UPDATE #__ctc_hero_onway SET `status`='1' WHERE `id`='$hr->id'";
							$db->setQuery($sql);
							if(!$db->query()){
								globalError2($sql);
							}
						}
						
						//Tra linh:
						$sql = "SELECT * FROM #__ctc_troop_onway WHERE `st_id`='$st->id' AND `status`='0'";
						$db->setQuery($sql);
						$tos = $db->loadObjectList();
						if($tos){
							foreach($tos as $to){
								//cap nhat status:
								$sql = "UPDATE #__ctc_troop_onway SET `status`='1' WHERE `id`='$to->id'";
								$db->setQuery($sql);
								if(!$db->query()){
									globalError2($sql);
								}
								
								//cap nhat linh trong lang:
								changeTroopVillage($st->village_id, $to->troop_id, $to->num);
							}
						}
					}
				}
			}
			
			//Tra tuong trong diem tap ket:
			$sql = "SELECT
				wg_ctc_heros.*
			FROM
				wg_ctc_heros
			WHERE
				wg_ctc_heros.dtk_id =  '$dtk->id'";
			$db->setQuery($sql);
			$hs = $db->loadObjectList();
			if($hs){
				foreach($hs as $h){
					changeVillageOfHero($h->hero_id, $h->village_id);
					
					//xoa du lieu trong bang ctc_heros
					$sql = " DELETE FROM `wg_ctc_heros` WHERE `id`='$h->id'";
					$db->setQuery($sql);
					if(!$db->query()){
						globalError2($sql);
					}
				}
			}
			
			//tra linh trong diem tap ket
			$sql = "SELECT
							wg_ctc_troops.*
						FROM
							wg_ctc_troops
						WHERE
							wg_ctc_troops.dtk_id =  '$dtk->id'";
			$db->setQuery($sql);
			$ts = $db->loadObjectList();
			if($ts){
				foreach($ts as $t){
					if($t->num>0){
						changeTroopVillage($t->village_id, $t->troop_id, $t->num);
												
						//xoa du lieu trong bang wg_ctc_troops:
						$sql = " DELETE FROM `wg_ctc_troops` WHERE `id`='$t->id'";
						$db->setQuery($sql);
						if(!$db->query()){
							globalError2($sql);
						}
					}				
				}
			}
		}
	}
}

/**
 * @author Le Van Tu
 * @todo vo hieu hoa the bai
 */
function destroyCard_ctc($uid){
	global $db;
	$sql = "UPDATE wg_plus SET the_bai_1=0, the_bai_2=0, the_bai_3=0 WHERE `user_id`='$uid'";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo vo hieu hoa the bai
 */
function destroyAllCard1_ctc(){
	global $db;
	$sql = "UPDATE wg_plus SET the_bai_1=0";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo bo phe cua cac lien minh
 */
function destroyAliSide_ctc(){
	global $db;
	$sql = "UPDATE wg_allies SET phe_id=0 WHERE phe_id!=0";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * @author Le Van Tu
 * @todo bo phe cua cac lien minh
 */
function destroyAllChuPhe_ctc(){
	global $db;
	$sql = "UPDATE wg_ctc_phe SET chu=null";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}


/**
 * @author Le Van Tu
 * @todo Tinh tong so bat com cua linh tham gia CTC
 */
function getSumTroopKeep_ctc($ct_id, $side, $art){
	global $db;
	$rs = 0;
	//Tinh tong so bat com cua linh con song:
	$sql = "SELECT
					wg_ctc_troops.troop_id,
					wg_ctc_troops.num
				FROM
					wg_ctc_troops ,
					wg_ctc_diem_tap_ket
				WHERE
					wg_ctc_troops.dtk_id =  wg_ctc_diem_tap_ket.id AND
					wg_ctc_troops.cong_thu =  '$side' AND
					wg_ctc_diem_tap_ket.ct_id =  '$ct_id'
				GROUP BY
					wg_ctc_troops.id";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){
		foreach($ts as $t){
			if($t->num>0){
				$rs += $t->num*$art[$t->troop_id]['keep_hour'];
			}
		}
	}
	
	//Tinh tong so bat com cua linh da chet:
	$d = date("Y:m:d");
	$sql = "SELECT
					wg_ctc_troops_bk.troop_id,
					wg_ctc_troops_bk.die_num
				FROM
					wg_ctc_troops_bk ,
					wg_ctc_diem_tap_ket
				WHERE
					wg_ctc_troops_bk.dtk_id =  wg_ctc_diem_tap_ket.id AND
					wg_ctc_troops_bk.date_attack =  '$d' AND
					wg_ctc_troops_bk.cong_thu =  '$side' AND
					wg_ctc_diem_tap_ket.ct_id =  '$ct_id'
				GROUP BY
					wg_ctc_troops_bk.id";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){
		foreach($ts as $t){
			if($t->die_num>0){
				$rs += $t->die_num*$art[$t->troop_id]['keep_hour'];
			}
		}
	}
	
	return $rs;
}

/**
 * @author Le Van Tu
 * @todo Tinh so bat com do linh cua mot user tieu thu
 */
function getSumTroopKeepUser_ctc($u_id, $art){
	global $db;
	$rs = 0;
	//tinh troopkeep cua linh con song:
	$sql = "SELECT
						wg_ctc_troops.troop_id,
						wg_ctc_troops.num
					FROM
						wg_ctc_troops ,
						wg_villages
					WHERE
						wg_ctc_troops.village_id =  wg_villages.id AND
						wg_villages.user_id =  '$u_id'
					GROUP BY
						wg_ctc_troops.id";
	$db->setQuery($sql);//die($sql);
	$ts = $db->loadObjectList();
	if($ts){
		foreach($ts as $t){
			if($t->num>0){
				$rs += $t->num*$art[$t->troop_id]['keep_hour'];
			}
		}
	}
	
	//tinh troopkeep cua linh da chet:
	$d = date("Y:m:d");
	$sql = "SELECT
						wg_ctc_troops_bk.troop_id,
						wg_ctc_troops_bk.die_num
					FROM
						wg_ctc_troops_bk ,
						wg_villages
					WHERE
						wg_ctc_troops_bk.village_id = wg_villages.id AND
						wg_ctc_troops_bk.date_attack = '$d' AND 
						wg_villages.user_id =  '$u_id'
					GROUP BY
						wg_ctc_troops_bk.id";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){
		foreach($ts as $t){
			if($t->die_num>0){
				$rs += $t->die_num*$art[$t->troop_id]['keep_hour'];
			}
		}
	}
	return $rs;
}

/**
 * @author Le Van Tu
 * @todo lay danh sach user tai mot chien truong
 */
function getSumUserCT_ctc($ct_id){
	global $db;
	$sql = "SELECT
						wg_villages.user_id as id
					FROM
						wg_ctc_troops ,
						wg_villages ,
						wg_ctc_diem_tap_ket
					WHERE
						wg_ctc_troops.village_id =  wg_villages.id AND
						wg_ctc_troops.dtk_id =  wg_ctc_diem_tap_ket.id AND
						wg_ctc_troops.num > 0 AND
						wg_ctc_diem_tap_ket.ct_id =  '$ct_id' 
					GROUP BY
						wg_villages.user_id";
	$db->setQuery($sql);
	return $db->loadObjectList();	
}

/**
 * @author Le Van Tu
 * @todo Lay thong tin phe thua
 */
function getLoseSide_ctc($ct_id, $cong_thu){
	global $db;
	$sql = "SELECT
				wg_ctc_phe.* 
			FROM
				wg_ctc_phe
			WHERE
				wg_ctc_phe.ct_id =  '$ct_id' AND
				wg_ctc_phe.cong_thu =  '$cong_thu'";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}

/**
 * @author Le Van Tu
 * @todo tim phe thang tai mot chien truong
 * @return 0 or 1
 */
function getWonderSide_ctc($ct_id){
	global $db;
	$cong_thu = 0;
	$d = date("Y:m:d");
	$sql = "SELECT
						wg_ctc_tran_dau.dtk_id,
						wg_ctc_tran_dau.luot,
						wg_ctc_tran_dau.phe_thang,
						wg_ctc_tran_dau.date_attack
					FROM
						wg_ctc_tran_dau ,
						wg_ctc_diem_tap_ket
					WHERE
						wg_ctc_tran_dau.dtk_id =  wg_ctc_diem_tap_ket.id AND
						wg_ctc_diem_tap_ket.ct_id =  '$ct_id' AND
						wg_ctc_tran_dau.date_attack =  '$d'
					GROUP BY
						wg_ctc_tran_dau.id";
	$db->setQuery($sql);
	$tds = $db->loadObjectList();
	if($tds){
		$cong = 0;
		$thu = 0;
		for($i=1; $i<=10; $i++){
			$temp['cong_'.$i] = 0;
			$temp['thu_'.$i] = 0;
		}
		foreach($tds as $td){
			if($td->phe_thang){
				$temp['cong_'.$td->luot]++;
			}else{
				$temp['thu_'.$td->luot]++;
			}				
		}
		
		for($i=1; $i<=10; $i++){
			if($temp['cong_'.$i]>$temp['thu_'.$i]){
				$cong ++;
			}else{
				$thu ++;
			}
		}
		
		
		if($cong>$thu){
			$cong_thu = 1;
		}
	}
	
	
	$sql = "SELECT
				wg_ctc_phe.*
			FROM
				wg_ctc_phe
			WHERE
				wg_ctc_phe.ct_id =  '$ct_id' AND
				wg_ctc_phe.cong_thu =  '$cong_thu'";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}


function getUserSide_ctc($sideId){
	global $db;
	$rs = 0;
	$sql = "SELECT
					wg_users.id
				FROM
					wg_allies ,
					wg_users ,
					wg_plus
				WHERE
					wg_allies.id =  wg_users.alliance_id AND
					wg_users.id =  wg_plus.user_id AND
					(wg_plus.the_bai_2 =  '1' OR wg_plus.the_bai_3 = '1') AND
					wg_allies.phe_id =  '$sideId' 
				GROUP BY
					wg_users.id";
	$db->setQuery($sql);
	return $db->loadObjectList();
}

/**
 * @author Le Van Tu
 * @todo tinh luong asu se thuong cho phe thang
 */
function getSumAsu_ctc($us){
	global $db;
	$rs = 0;
	
	//lay gia tri cua the bai:
	$sql = "SELECT wg_config_plus.asu FROM wg_config_plus WHERE wg_config_plus.name = 'the_bai_3' OR wg_config_plus.name = 'the_bai_2' ORDER BY wg_config_plus.name ASC";
	$db->setQuery($sql);
	$asuConfig = $db->loadObjectList();
		
	if($us){
		foreach($us as $u){
			$sql = "SELECT
						wg_plus.the_bai_2,
						wg_plus.the_bai_3
					FROM
						wg_plus
					WHERE
						wg_plus.user_id =  '$u->id'";
			$db->setQuery($sql);
			$db->loadObject($plus);
			
			if($plus->the_bai_2){
				$rs += $asuConfig[0]->asu;
			}
			
			if($plus->the_bai_3){
				$rs += $asuConfig[1]->asu;
			}
		}
	}
	
	return $rs * 0.8;
}

/**
 * @author Le Van Tu
 * @todo chia tien thuong cho tung user
 */
function rewardUser_ctc($u_id, $sumasu, $stk, $art){
	//tinh troop keep:
	$tk = getSumTroopKeepUser_ctc($u_id, $art);
	$asu = intval($sumasu*($tk/$stk));
	
	//tang asu:
	addAsu($u_id, $asu);
	
	//gui report:
	reportAsu_ctc($u_id, $asu);
}

function addAsu($userId, $asu){
	global $db;
	$sql = "UPDATE wg_plus SET `gold` = `gold` + $asu WHERE `user_id` = '$userId'";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2("$ql");
	}
}

/**
 * @author Le Van Tu
 * @todo gui report thuong asu
 */
function reportAsu_ctc($u_id, $asu){
	global $db, $lang;
	includeLang('ctc');
	$title = $lang['asu_rp_title'];
	$content = parsetemplate(gettemplate("ctc/ctc_report_asu"), array("asu"=>$asu));	
	InsertReport($u_id, $title, date("Y:m:d H:i:s"), $content);
}

//<=============Cac ham lien quan den ket thuc cong thanh chien==============|

/**
 * @author Le Van Tu
 * @todo lay thong tin thoi gian attack
 */
function getPointTable_ctc($ct_id){
	global $db, $lang;
	$rs = array();
	for($i=1; $i<=10; $i++){
		$rs['t_'.$i] = "--:--:--";
		$tmp['at_p_'.$i] = "";
		$tmp['df_p_'.$i] = "";
		for($j=1; $j<8; $j++){
			$rs["img_".$i."_".$j] = "";
		}
	}
	
	//Lay thong tin thoi gian:
	$sql = "SELECT wg_status.time_end, wg_status.order_ FROM wg_status WHERE wg_status.`type`  = '27' AND wg_status.object_id = '$ct_id' ORDER BY wg_status.time_end ASC";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){
		foreach($ts as $t){			
			$te = split(" ", $t->time_end);
			$rs['t_'.$t->order_] = $te[1];			
		}	
	}
	
	//lay thong tin diem:
	$d = $te[0];
	
	$sql = "SELECT
					wg_ctc_tran_dau.luot,
					wg_ctc_tran_dau.phe_thang,
					wg_ctc_diem_tap_ket.cung 
				FROM
					wg_ctc_tran_dau ,
					wg_ctc_diem_tap_ket
				WHERE
					wg_ctc_tran_dau.dtk_id =  wg_ctc_diem_tap_ket.id AND
					wg_ctc_tran_dau.date_attack =  '$d' AND
					wg_ctc_diem_tap_ket.ct_id = '$ct_id'
				GROUP BY
					wg_ctc_tran_dau.id
				ORDER BY
					wg_ctc_tran_dau.dtk_id ASC";
	$db->setQuery($sql);
	$ps = $db->loadObjectList();
	if($ps){
		foreach($ps as $p){
			if($tmp['at_p_'.$p->luot]=="" && $tmp['df_p_'.$p->luot]==""){
				$tmp['at_p_'.$p->luot] = 0;
				$tmp['df_p_'.$p->luot] = 0;
			}
			
			if($p->phe_thang){
				$rs['img_'.$p->luot."_".$p->cung] = '<img title="'.$lang['ben cong'].'" src="../images/ctc/cong.gif" />';
				$tmp['at_p_'.$p->luot] ++;
			}else{
				$rs['img_'.$p->luot."_".$p->cung] = '<img title="'.$lang['ben thu'].'" src="../images/ctc/thu.gif" />';
				$tmp['df_p_'.$p->luot] ++;
			}
		}
	}
	
	for($i = 1; $i<=10; $i++){
		if($tmp['at_p_'.$i]>0 || $tmp['df_p_'.$i]>0){
			if($tmp['at_p_'.$i] > $tmp['df_p_'.$i]){
				$rs["img_".$i."_7"] = '<img title="'.$lang['ben cong'].'" src="../images/ctc/cong.gif" />';
			}else{
				$rs["img_".$i."_7"] = '<img title="'.$lang['ben thu'].'" src="../images/ctc/thu.gif" />';
			}
		}else{
			$rs["img_".$i."_7"] = "";
		}		
	}
	
	return $rs;
}

/**
 * @author Le Van TU
 * @todo kiem tra xem mo cua ctc chua
 */
function checkOpen_ctc(){
	global $db;
	$sql = "SELECT COUNT(*) FROM wg_status WHERE wg_status.`status`='0' AND wg_status.`type`='27'";
	$db->setQuery($sql);
	if($db->loadResult()>0){
		return true;
	}else{
		return false;
	}
}

/**
 * @author Le Van Tu
 * @todo Lay thong tin cua mot chien truong
 */
function getCT_ctc($id){
	global $db;
	$sql = "SELECT * FROM wg_ctc_ct WHERE id = $id";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}

/**
 * @author Le Van Tu
 * @todo Lay khoang thoi gian dem nguoc
 */
function getLeftTime_ctc($ct_id){
	global $db;
	$sql = "SELECT wg_status.time_end FROM wg_status WHERE wg_status.`type` =  '27' AND wg_status.`status` =  '0' AND wg_status.object_id = '$ct_id' ORDER BY wg_status.time_end ASC";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){
		$rs = TimeToString(strtotime($ts[0]->time_end)-time());
	}else{
		$rs = "--:--:--";
	}
	return $rs;
}

/**
 * @todo Hien thi hinh anh quan doi trong diem tap ket
 */
function displayTroopInDTK_ctc($dtk_id){
	global $db, $lang;
	includeLang("troop");
	$rs = array();
	$art = getArrayOfTroops();
	
	for($i=1; $i<=21; $i++){
		$rs['img_c'.$i] = "../images/ctc/x.gif";
		$rs['img_t'.$i] = "../images/ctc/x.gif";
	}
	
	$sql = "SELECT
					wg_ctc_troops.troop_id
				FROM
					wg_ctc_troops 
				WHERE 
					wg_ctc_troops.cong_thu =  '1' AND 
					wg_ctc_troops.num > '0' AND 
					wg_ctc_troops.dtk_id =  '$dtk_id' 
				GROUP BY
					wg_ctc_troops.troop_id";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){//echo "<pre>"; print_r($ts); die();
		$i = 1;
		foreach($ts as $t){
			$rs['img_c'.$i] = "../images/ctc/troops/c_".$t->troop_id.".png";
			$rs['troop_name_c'.$i] = $lang[$art[$t->troop_id]["name"]];
			$i ++;
		}
	}
	
	$sql = "SELECT
					wg_ctc_troops.troop_id
				FROM
					wg_ctc_troops
				WHERE
					wg_ctc_troops.cong_thu =  '0' AND
					wg_ctc_troops.num > '0' AND
					wg_ctc_troops.dtk_id = '$dtk_id'
				GROUP BY
					wg_ctc_troops.troop_id";
	$db->setQuery($sql);
	$ts = $db->loadObjectList();
	if($ts){
		$i = 1;
		foreach($ts as $t){
			$rs['img_t'.$i] = "../images/ctc/troops/t_".$t->troop_id.".png";
			$rs['troop_name_t'.$i] = $lang[$art[$t->troop_id]["name"]];
			$i ++;
		}
	}
	
	return $rs;
}

/**
 * @author Le Van Tu
 * @todo Kiem tra xem co chien truong nao chua danh luot nao khong
 */
function checkStarted_ctc(){
	global $db;
	$sql = "SELECT COUNT(*) 
				FROM wg_status 
				WHERE 
					wg_status.`status` =  '0' AND 
					wg_status.`type` =  '27' AND 
					wg_status.order_ =  '1' 
				GROUP BY wg_status.order_";
	$db->setQuery($sql);
	return $db->loadResult();
}

/**
 * @author Le Van Tu
 * @todo Kiem tra xem co chien truong nao chua danh luot nao khong
 */
function getStarted_ctc(){
	global $db;
	$sql = "SELECT wg_status.object_id  
				FROM wg_status 
				WHERE 
					wg_status.`status` =  '0' AND 
					wg_status.`type` =  '27' AND 
					wg_status.order_ =  '1' 
				GROUP BY wg_status.order_";
	$db->setQuery($sql);
	return $db->loadObjectList();
}

/**
 * @author Le Van Tu
 * @todo Kiem tra mot chien truong co the dang ky hay khong
 */
function checkCtAvaiable_ctc($id){
	global $db;
	$sql = "SELECT COUNT(*) FROM wg_status WHERE wg_status.`status` =  '0' AND wg_status.`type` =  '27' AND wg_status.order_ = '1' AND wg_status.object_id='$id'";
	$db->setQuery($sql);
	return $db->loadResult();
}

function getOtherTC_ctc($ct_id){
	global $db;
	$sql = "SELECT wg_ctc_diem_tap_ket.id FROM wg_ctc_diem_tap_ket WHERE wg_ctc_diem_tap_ket.ct_id =  '$ct_id' ORDER BY wg_ctc_diem_tap_ket.id ASC";
	$db->setQuery($sql);
	return $db->loadObjectList();
}

function getUserSideByUserId_ctc($userId){
	global $db;
	$sql = "SELECT
					wg_ctc_phe.*
				FROM
					wg_allies ,
					wg_ctc_phe ,
					wg_users
				WHERE
					wg_users.alliance_id =  wg_allies.id AND 
					wg_allies.phe_id =  wg_ctc_phe.id AND 
					wg_users.id =  '$userId' 
				GROUP BY
					wg_allies.id";
	$db->setQuery($sql);
	$db->loadObject($rs);
	return $rs;
}

function getDTKOfCt_ctc($ctId){
	global $db;
	$sql = "SELECT *
				FROM 
					wg_ctc_diem_tap_ket 
				WHERE
					wg_ctc_diem_tap_ket.ct_id =  '$ctId' 
				ORDER BY 
					wg_ctc_diem_tap_ket.`index` ASC";
	$db->setQuery($sql);
	return $db->loadObjectList();
}

/**
 * @author Le Van Tu
 * @todo kiem tra xem co quan ben cong hoac bên thu tai mot diem hay khong
 */
function checkTroopSideInDtk_ctc($id, $cong_thu){
	global $db;
	$sql = "SELECT
				wg_ctc_troops.*
			FROM 
				wg_ctc_troops
			WHERE
				wg_ctc_troops.num >  '0' AND
				wg_ctc_troops.dtk_id =  '$id' AND
				wg_ctc_troops.cong_thu =  '$cong_thu'";
	$db->setQuery($sql);//die($sql);
	return $db->loadResult();
}

function troops_ctc()
{
	$rs = array();
	$rs[1] = 1;
	$rs[2] = 1;
	$rs[3] = 1;
	$rs[4] = 1;
	$rs[5] = 1;
	$rs[6] = 1;
	$rs[7] = 1;
	
	$rs[12] = 1;
	$rs[13] = 1;
	$rs[14] = 1;
	$rs[15] = 1;
	$rs[16] = 1;
	$rs[17] = 1;
	$rs[18] = 1;
	
	$rs[23] = 1;
	$rs[24] = 1;
	$rs[25] = 1;
	$rs[26] = 1;
	$rs[27] = 1;
	$rs[28] = 1;
	$rs[29] = 1;
	return $rs;
}

?>