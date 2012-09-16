<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php');
require_once($ugamela_root_path . 'includes/common.'.$phpEx);

require_once($ugamela_root_path . 'includes/function_troop.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_ctc.'.$phpEx);

checkRequestTime();

if(!check_user()){ header("Location: ".$ugamela_root_path."login.php"); }

global $db, $user;

//kiem tra xem mo cua ctc chua:
if(checkOpen_ctc()){
	if(!checkTheBai_ctc($user['id'])){header("Location: ctc.php");}
}	

includeLang('ctc');
$parse 	= $lang;


if($_REQUEST['id'] && is_numeric($_REQUEST['id'])){
	$id = $parse['id'] = intval($_REQUEST['id']);
	//getTroopAttackSide_ctc($id); die();
	
	$sql = "SELECT * FROM wg_ctc_diem_tap_ket WHERE id = $id";
	$db->setQuery($sql);
	$db->loadObject($dtk);
	
	$parse['ct_id'] = $dtk->ct_id;
	
	$parse += getDTKMenu($dtk->ct_id);
		
	$vls = getVillageOfUser_ctc($user['id']);
	
	//lay thong tin 2 ben cong thu
	$side = getSideByCTId_ctc($dtk->ct_id);
	$parse['side_attack_name'] = $side[1]->name;
	$parse['sid_1'] = $side[1]->id;
	$parse['side_defend_name'] = $side[0]->name;
	$parse['sid_0'] = $side[0]->id;
	
	//danh sach user ben cong:
	$uas = getUserSide_ctc($dtk->id, $side[1]->id);
	if($uas){
		$litag = gettemplate("ctc/ctc_li_a_tag");
		foreach($uas as $ua){
			$parse['user_attack_list'] .= parsetemplate($litag, array("onclick"=>"onclick=\"showTroopUserSameSide('popup_div', $id, $ua->id);document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block'\"", "string"=>$ua->username));
		}
	}else{
		$parse['user_attack_list'] = "";
	}
	
	//danh sach user ben thu:
	$uds = getUserSide_ctc($dtk->id, $side[0]->id);
	if($uds){
		$litag = gettemplate("ctc/ctc_li_a_tag");
		foreach($uds as $ud){
			$parse['user_defend_list'] .= parsetemplate($litag, array("onclick"=>"onclick=\"showTroopUserSameSide('popup_div', $id, $ud->id);document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block'\"", "string"=>$ud->username));
			
		}
	}else{
		$parse['user_defend_list'] = "";
	}
	$timer = 1;
	$parse += showTroopMoveStatus_ctc($dtk->id, $side, $timer);
	
	$parse['left_time'] = getLeftTime_ctc($dtk->ct_id);
	
	for($i=0; $i<=6; $i++){
		$parse['ortherlink_'.$i] = "";
	}
	
	switch($dtk->cung){
		case 0:
			$parse['img_name'] = 'dtk_cong';
			$parse['ortherlink_0'] = "ortherlink";
			break;
		case 1:
			$parse['img_name'] = 'kim';
			$parse['ortherlink_1'] = "ortherlink";
			break;
		case 2:
			$parse['img_name'] = 'thuy';
			$parse['ortherlink_2'] = "ortherlink";
			break;
		case 3:
			$parse['img_name'] = 'moc';
			$parse['ortherlink_3'] = "ortherlink";
			break;
		case 4:
			$parse['img_name'] = 'hoa';
			$parse['ortherlink_4'] = "ortherlink";
			break;
		case 5:
			$parse['img_name'] = 'tho';
			$parse['ortherlink_5'] = "ortherlink";
			break;
		case 6:
			$parse['img_name'] = 'dtk_thu';
			$parse['ortherlink_6'] = "ortherlink";
			break;
	}
	
	$parse += displayTroopInDTK_ctc($id);
	
	$page = parsetemplate(gettemplate('ctc/ctc_tc_body'), $parse);
}

//echo "<pre>"; print_r($dtk); die();

display_ctc($page);

ob_end_flush();
?>