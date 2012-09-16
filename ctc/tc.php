<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php');
require_once($ugamela_root_path . 'includes/common.'.$phpEx);

require_once($ugamela_root_path . 'includes/function_resource.php');
require_once($ugamela_root_path . 'includes/func_build.php');
require_once($ugamela_root_path . 'includes/func_convert.php');
require_once($ugamela_root_path . 'includes/func_security.php');
require_once($ugamela_root_path . 'includes/function_trade.php');
require_once($ugamela_root_path . 'includes/function_status.php');
require_once($ugamela_root_path . 'includes/function_attack.php');
require_once($ugamela_root_path . 'includes/function_troop.php');
require_once($ugamela_root_path . 'includes/wg_village_kinds.php');
require_once($ugamela_root_path . 'includes/usersOnline.class.php');

require_once($ugamela_root_path . 'includes/function_ctc.'.$phpEx);

checkRequestTime();
doAllStatus();

if(!check_user()){ header("Location: ".$ugamela_root_path."login.php"); }

global $db, $user;

//kiem tra xem mo cua ctc chua:
if(checkOpen_ctc()){
	if(!checkTheBai_ctc($user['id'])){header("Location: ctc.php");}
}

includeLang('ctc');
$parse 	= $lang;


if($_REQUEST['id'] && is_numeric($_REQUEST['id'])){
	$id = intval($_REQUEST['id']);
	$sql = "SELECT * FROM wg_ctc_diem_tap_ket WHERE id = '$id'";
	$db->setQuery($sql);
	$db->loadObject($dtk);
	
	$dtks = getDTKOfCt_ctc($dtk->ct_id);
}else{
	$ct_id = $_REQUEST['ct_id'];
	
	$userSide = getUserSideByUserId_ctc($user['id']);
	
	$dtks = getDTKOfCt_ctc($ct_id);
	
	if($userSide->cong_thu){
		$id = $dtks[0]->id;
		$dtk = $dtks[0];
	}else{
		$id = $dtks[6]->id;
		$dtk = $dtks[6];
	}
}

$parse['id'] = $id;

for($i = 1; $i<6; $i++)
{
	if(checkTroopSideInDtk_ctc($dtks[$i]->id, 1)){
		$parse['flag_a_display_'.$dtks[$i]->cung] = "";
	}		
	else
		$parse['flag_a_display_'.$dtks[$i]->cung] = "style=\"display:none\"";
	
	if(checkTroopSideInDtk_ctc($dtks[$i]->id, 0))
	{
		$parse['flag_d_display_'.$dtks[$i]->cung] = "";
	}	
	else
		$parse['flag_d_display_'.$dtks[$i]->cung] = "style=\"display:none\"";
}


$parse['ct_id'] = $dtk->ct_id;
switch($dtk->ct_id){
	case 1:
		$parse['img_name'] = 'chilang';
		break;
	case 2:
		$parse['img_name'] = 'bachdang';
		break;
	case 3:
		$parse['img_name'] = 'bodang';
		break;
	case 4:
		$parse['img_name'] = 'tralan';
		break;
	case 5:
		$parse['img_name'] = 'nhunguyet';
		break;
}

$parse += getDTKMenu($dtk->ct_id);

$vls = getVillageOfUser_ctc($user['id']);

//lay thong tin 2 ben cong thu
$side = getSideByCTId_ctc($dtk->ct_id);
$parse['side_attack_name'] = $side[1]->name;
$parse['sid_1'] = $side[1]->id;
$parse['side_defend_name'] = $side[0]->name;
$parse['sid_0'] = $side[0]->id;

//danh sach user ben cong:
$uas = getUserSideDTK_ctc($dtk->id, $side[1]->id);
if($uas){
	$litag = gettemplate("ctc/ctc_li_a_tag");
	foreach($uas as $ua){
		$parse['user_attack_list'] .= parsetemplate($litag, array("onclick"=>"onclick=\"showTroopUserSameSide('popup_div', $id, $ua->id);document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block'\"", "string"=>$ua->username));
	}
}else{
	$parse['user_attack_list'] = "";
}

//danh sach user ben thu:
$uds = getUserSideDTK_ctc($dtk->id, $side[0]->id);
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
		$parse['div_main_id'] = "main_cong";
		$parse['ortherlink_0'] = "ortherlink";
		break;
	case 1:
		$parse['div_main_id'] = "main_".($dtk->cung+1);
		$parse['ortherlink_1'] = "ortherlink";
		break;
	case 2:
		$parse['div_main_id'] = "main_".($dtk->cung+1);
		$parse['ortherlink_2'] = "ortherlink";
		break;
	case 3:
		$parse['div_main_id'] = "main_".($dtk->cung+1);
		$parse['ortherlink_3'] = "ortherlink";
		break;
	case 4:
		$parse['div_main_id'] = "main_".($dtk->cung+1);
		$parse['ortherlink_4'] = "ortherlink";
		break;
	case 5:
		$parse['div_main_id'] = "main_".($dtk->cung+1);
		$parse['ortherlink_5'] = "ortherlink";
		break;
	case 6:
		$parse['div_main_id'] = "main_thu";
		$parse['ortherlink_6'] = "ortherlink";
		break;
}

$parse += displayTroopInDTK_ctc($id);

$page = parsetemplate(gettemplate('ctc/ctc_tc_body'), $parse);

display_ctc($page);

ob_end_flush();
?>