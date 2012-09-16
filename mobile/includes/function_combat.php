<?php
define('INSIDE',true);

function getATL_cb(){
	switch($_POST['ans']){
		case 0:
			$nt = getTroopsOfNation(1);
			break;
		case 1:
			$nt = getTroopsOfNation(2);
			break;
		case 2:
			$nt = getTroopsOfNation(3);
			break;
	}
	
	$i=0;
	foreach($nt as $t){
		$rs[1][$t->id]['num'] 		= $_POST['ast'][$i];
		$rs[1][$t->id]['attack'] 	= GetIncreaseAttack($t->attack, $_POST['b'][$i]);
		$rs[1][$t->id]['type'] 		= $t->type;
		$rs[1][$t->id]['carry'] 	= $t->carry;
		$rs[1][$t->id]['keep_hour'] = $t->keep_hour;
		$rs[1][$t->id]['die_num'] 	= 0;
		$i++;
	}
	return $rs;
}

function getDTL_cb(){
	switch($_POST['dns']){
		case 0:
			$nt = getTroopsOfNation(1);
			break;
		case 1:
			$nt = getTroopsOfNation(2);
			break;
		case 2:
			$nt = getTroopsOfNation(3);
			break;
	}
	
	//echo "<pre>"; print_r($nt); die();
	
	$i=0;
	foreach($nt as $t){
		$rs[1][$t->id]['num'] 				= $_POST['dst'][$i];
		$rs[1][$t->id]['die_num'] 			= 0;
		$rs[1][$t->id]['type'] 				= $t->type;
		$rs[1][$t->id]['melee_defense'] 	= getIncreaseDefend($t->melee_defense, $_POST['g'][$i]);
		$rs[1][$t->id]['ranger_defense'] = getIncreaseDefend($t->ranger_defense, $_POST['g'][$i]);
		$rs[1][$t->id]['magic_defense'] 	= getIncreaseDefend($t->magic_defense, $_POST['g'][$i]);
		$rs[1][$t->id]['keep_hour'] 		= $t->keep_hour;
		$i++;
	}
	return $rs;
}

function getAH_cb(){
	global $db;
	$rs = null;
	if($_POST['ah'] && $_POST['ah_tid'] && $_POST['ah_l']>0){
		$tid = $_POST['ah_tid'];
		$lv  = $_POST['ah_l'];
		
		$sql = "SELECT `type`, `attack` FROM wg_troops WHERE id = $tid";
		$db->setQuery($sql);
		$db->loadObject($rs);
		
		$rs->num				= 1;
		$rs->die_num			= 0;
		$rs->hitpoint		 	= $_POST['ah_hp'];
		$rs->tuong_sinh_cong 	= $_POST['tsc']/100;
		$rs->tuong_khac_cong	= $_POST['tkc']/100;
		$rs->kinh_nghiem		= 0;
		$rs->attack				= $rs->attack * $lv * (1 + $rs->tuong_khac_cong);
	}
	return $rs;	
}

function getDH_cb(){
	global $db;
	$rs = null;
	if($_POST['dh'] && $_POST['dh_tid'] && $_POST['dh_l']>0){
		$tid = $_POST['dh_tid'];
		$lv  = $_POST['dh_l'];
		
		$sql = "SELECT `type`, `melee_defense`, `ranger_defense`, `magic_defense` FROM wg_troops WHERE id = $tid";
		$db->setQuery($sql);
		$db->loadObject($rs);
		
		$rs->num				= 1;
		$rs->die_num			= 0;
		
		$rs->hitpoint 			= $_POST['dh_hp'];
		$rs->tuong_sinh_thu 	= $_POST['tst']/100;
		$rs->tuong_khac_thu		= $_POST['tkt']/100;
		$rs->kinh_nghiem		= 0;
		
		$rs->melee_defense		= $rs->melee_defense * $lv * (1 + $rs->tuong_khac_thu);
		$rs->ranger_defense		= $rs->ranger_defense * $lv * (1 + $rs->tuong_khac_thu);
		$rs->magic_defense		= $rs->magic_defense * $lv * (1 + $rs->tuong_khac_thu);
	}
	return array(1 =>$rs);	
}

function attack_cb(&$atl, &$dtl, &$ah, &$dh){	
	
	$wl	= $_POST['wl'];
	$wd = $wl>0?($wl*0.05+1):1;
	
	$kt = 0;
	phase($atl, $ah, $dtl, $dh, $wd, $kt, $_POST['st'], $_POST['a_asu'], $_POST['d_asu']);
}

function returnForm_cb($atl, $dtl, $ah, $dh){
	$i = 0;
	foreach($atl as $ats){
		foreach($ats as $at){			
			$parse['as_'.$i] = $at['num'];
			$parse['b_'.$i] = $_POST['b'][$i];
			
			if($at['die_num'] > 0){
				$parse['asd_'.$i] = $at['die_num'];			
				$parse['ac_0_'.$i] = "";
			}else{
				$parse['asd_'.$i] = 0;			
				$parse['ac_0_'.$i] = "class = 'c'";
			}
			$i++;
		}		
	}
	
	//hien thi bang defenner:
	$i = 0;
	foreach($dtl as $dts){
		foreach($dts as $dt){			
			$parse['ds_'.$i] = $dt['num'];
			$parse['g_'.$i] = $_POST['g'][$i];
			
			if($dt['die_num'] > 0){
				$parse['dsd_'.$i] = $dt['die_num'];			
				$parse['dc_0_'.$i] = "";
			}else{
				$parse['dsd_'.$i] = 0;			
				$parse['dc_0_'.$i] = "class = 'c'";
			}
			$i++;
		}		
	}
	
	if($_POST['st'] == 3){
		$parse['stc_0'] = 'checked="checked"';
		$parse['stc_1'] = '';
	}else{
		$parse['stc_0'] = '';
		$parse['stc_1'] = 'checked="checked"';
	}
	
	$parse['ans_c_0']	= '';
	$parse['ans_c_1']	= '';
	$parse['ans_c_2']	= '';
	$parse['ans_c_'.$_POST['ans']] = 'checked="checked"';
	
	$parse['dns_c_0']	= '';
	$parse['dns_c_1']	= '';
	$parse['dns_c_2']	= '';
	$parse['dns_c_'.$_POST['dns']] = 'checked="checked"';
	
	$parse['a_asu_c']	= $_POST['a_asu'] > 0 ? 'checked="checked"' : '';
	$parse['d_asu_c']	= $_POST['d_asu'] > 0 ? 'checked="checked"' : '';
	
	$parse['ah_c']		= $_POST['ah'] ? 'checked="checked"' : '';
	$parse['dh_c']		= $_POST['dh'] ? 'checked="checked"' : '';
	
	$parse['wl']		= $_POST['wl'];
	
	$parse['ahu']		= $_POST['ahu'];
	$parse['dhu']		= $_POST['dhu'];
	$parse['ah_tid']	= $_POST['ah_tid'];
	$parse['dh_tid']	= $_POST['dh_tid'];
	
	$parse['ah_l']		= $_POST['ah_l'];
	$parse['tsc']		= $_POST['tsc'];
	$parse['tkc']		= $_POST['tkc'];
	$parse['ah_hp']		= $_POST['ah_hp'];
	$parse['ah_shp']	= $ah->hitpoint < $_POST['ah_hp'] ? ' (- '.round($_POST['ah_hp'] - $ah->hitpoint, 2).')' : '';
	
	$parse['dh_l']		= $_POST['dh_l'];
	$parse['tst']		= $_POST['tst'];
	$parse['tkt']		= $_POST['tkt'];
	$parse['dh_hp']		= $_POST['dh_hp'];
	$parse['dh_shp']	= $dh->hitpoint < $_POST['dh_hp'] ? ' (- '.round($_POST['dh_hp'] - $dh->hitpoint, 2).')' : '';
	
	$parse['rs']		= getRS_cb($atl);
	
	$parse['as_11']		= $ah->num > 0 ? 1 : 0;
	$parse['ah_cl']		= $ah->num > 0 ? '' : 'class="c"';	
	$parse['asd_11']	= $ah->die_num > 0 ? 1 : '0';
	$parse['ac_0_11']	= $ah->die_num > 0 ? '' : 'class="c"';
	
	$parse['ds_11']		= $dh->num > 0 ? 1 : 0;
	$parse['dh_cl']		= $dh->num > 0 ? '' : 'class="c"';
	$parse['dsd_11']	= $dh->die_num > 0 ? 1 : '0';
	$parse['dc_0_11']	= $dh->die_num > 0 ? '' : 'class="c"';
	
	$parse['ah_kn']		= $_POST['ah_kn'];	
	$parse['ah_ikn']	= $ah->kinh_nghiem > $_POST['ah_kn'] ? '(+ '.($ah->kinh_nghiem - $_POST['ah_kn']).')' : '';
	$parse['dh_kn']		= $_POST['dh_kn'];
	$parse['dh_ikn']	= $dh->kinh_nghiem > $_POST['dh_kn'] ? '(+ '.($dh->kinh_nghiem - $_POST['dh_kn']).')' : '';
		
	return $parse;
}

function createForm_cb(){
	for($i=0; $i<12; $i++){
		$parse['as_'.$i] 	= 0;
		$parse['b_'.$i] 	= 0;
		$parse['asd_'.$i] 	= 0;			
		$parse['ac_0_'.$i] 	= "class = 'c'";
		
		$parse['ds_'.$i]	= 0;
		$parse['g_'.$i]		= 0;
		$parse['dsd_'.$i]	= 0;
		$parse['dc_0_'.$i] 	= "class = 'c'";
	}
	
	$parse['stc_0'] 	= 'checked="checked"';
	$parse['stc_1'] 	= '';
	
	$parse['ans_c_0']	= 'checked="checked"';
	$parse['ans_c_1']	= '';
	$parse['ans_c_2']	= '';
	
	$parse['dns_c_0']	= 'checked="checked"';
	$parse['dns_c_1']	= '';
	$parse['dns_c_2']	= '';
	
	$parse['a_asu_c']	= '';
	$parse['d_asu_c']	= '';
	
	$parse['ah_c']		= '';
	$parse['dh_c']		= '';
	
	$parse['rs'] 		= 0;	
	$parse['wl']		= 0;
	
	$parse['ahu']		= 0;
	$parse['dhu']		= 0;
	$parse['ah_tid']	= 12;
	$parse['dh_tid']	= 12;
	
	$parse['ah_l']		= 1;
	$parse['tsc']		= 0;
	$parse['tkc']		= 0;
	$parse['ah_hp']		= 100;
	
	$parse['dh_l']		= 1;
	$parse['tst']		= 0;
	$parse['tkt']		= 0;
	$parse['dh_hp']		= 100;
	
	$parse['as_11']		= 0;
	$parse['ah_c']		= 'class="c"';
	$parse['ac_0_11']	= 'class="c"';
	$parse['ds_11']		= 0;
	$parse['dh_c']		= 'class="c"';
	$parse['dc_0_11']	= 'class="c"';
	
	$parse['ah_kn']		= 0;
	$parse['ah_shp']	= '';
	$parse['ah_ikn']	= '';
	$parse['dh_kn']		= 0;
	$parse['dh_shp']	= '';
	$parse['dh_ikn']	= '';
	
	return $parse;
}

function getRS_cb($atl){
	$rs = 0;
	foreach($atl as $ats){
		foreach($ats as $at){
			$rs += ($at['num'] - $at['die_num']) * $at['carry'];
		}
	}
	return $rs;
}

?>