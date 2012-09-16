<?php
define('INSIDE', true);
ob_start(); 
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/func_security.php');
require_once($ugamela_root_path . 'includes/function_attack.php');
require_once($ugamela_root_path . 'includes/function_status.php');
require_once($ugamela_root_path . 'includes/function_troop.php');
require_once($ugamela_root_path . 'includes/function_resource.php');
require_once($ugamela_root_path . 'includes/function_plus.php');
require_once($ugamela_root_path . 'includes/function_ctc.php');
include_once($ugamela_root_path . '../../soap/call.'.$phpEx);

if(!check_user()){ header("Location: login.php");}

global $db,$user,$wg_buildings,$wg_village,$timeAgain,$lang;
includeLang('plus');
$parse = $lang;
$timeAgain=$user['amount_time']-(time()-$_SESSION['last_login']);
//START: dung chung
$village =$_COOKIE['villa_id_cookie'];

$wg_buildings = NULL;
$wg_village = NULL;
$wg_village=getVillage($village);
$wg_buildings = getBuildings($village);
getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());

$gold_deposit=get_gold_remote($user['username']); //lay ASU tu buildling systems
$currentGold = showGold($user['id']);
//END: dung chung
$parse['msg'] ="";

$sql = "SELECT * FROM wg_config_plus";
$db->setQuery($sql);
$info = NULL;
$info=$db->loadObjectList();
if($info)
{
	foreach($info as $v)
	{
		$parse[$v->name.'_duration'] = $v->duration;
		$parse[$v->name.'_asu'] = $v->asu;
		$info_2[$v->name] = $v->asu;
		$info_3[$v->name] = $v->duration;
	}
}

//Lay thong tin the bai cua user nay
$theBai = getTheBai_ctc($user['id']);
$cothe = 0;

$pl_sms = getSMSAttack_ctc($user['id']);

//START: show thong bao khi thuc hien complete
if($_GET['b'] > 0 && is_numeric($_GET['b']) )
{
	$parse['msg'] .= ">> ".$_GET['b']." ".$lang['Complete build']." ";
}
if($_GET['u'] > 0 && is_numeric($_GET['u']) )
{
	$parse['msg'] .= ">> ".$_GET['u']." ".$lang['Complete update']." ";					
}
if($_GET['d'] > 0 && is_numeric($_GET['d']) )
{
	$parse['msg'] .= ">> ".$_GET['d']." ".$lang['Complete delete']." ";
}


//START: lumber

$parse['total_gold'] = $currentGold;
$parse['total_gold_deposit']=$gold_deposit;	
$parse['lumber_time'] = "";
$timeEnd = getTimeEnd('lumber', $user['id']);
$second = strtotime($timeEnd->lumber) - time();	
$i = 1;
if($second >0){
	$parse['lumber_time'] ="<span class='c' style='font-size:11px'>".$lang['Remainder time']." 
						    <span id='account".$i."'>".ReturnTime($second)."</span></span>";
	$i++;
	if(($currentGold+$gold_deposit) >= $info[0]->asu)	{		
		$parse['lumber_action'] = "<a href=\"plus.php?type=2\">".$lang['Extend']."</a>";
		$parse['lumber_act'] = "extend";		
	}else{
		$parse['lumber_action'] = "<span class=\"c t\">".$lang['Not enough Asu']."</span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[0]->asu)	{		
		$parse['lumber_action'] = "<a href=\"plus.php?type=2\">".$lang['Active']."</a>";
		$parse['lumber_act'] = "active";		
	}else{
		$parse['lumber_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}
//END: lumber

//START: clay

$parse['total_gold'] = $currentGold;
$parse['total_gold_deposit']=$gold_deposit;
$parse['clay_time'] = "";
$timeEnd = getTimeEnd('clay', $user['id']);
$second = strtotime($timeEnd->clay) - time();	

if($second >0){
	$parse['clay_time'] ="<span class='c' style='font-size:11px'>".$lang['Remainder time']." 
	                     <span id='account".$i."'>".ReturnTime($second)."</span></span>";
	$i++;					
	if(($currentGold+$gold_deposit) >= $info[2]->asu){		
		$parse['clay_action'] = "<a href=\"plus.php?type=3\">".$lang['Extend']."</a>";
		$parse['clay_act'] = "extend";		
	}else{
		$parse['clay_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[2]->asu){		
		$parse['clay_action'] = "<a href=\"plus.php?type=3\">".$lang['Active']."</a>";
		$parse['clay_act'] = "active";		
	}else{
		$parse['clay_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}
//END: clay

//START: iron

$parse['total_gold'] = $currentGold;
$parse['total_gold_deposit']=$gold_deposit;
$parse['iron_time'] = "";
$timeEnd = getTimeEnd('iron', $user['id']);
$second = strtotime($timeEnd->iron) - time();	

if($second >0){
	
	$parse['iron_time'] ="<span class='c' style='font-size:11px'>".$lang['Remainder time']." 
	<span id='account".$i."'>".ReturnTime($second)."</span></span>";
	$i++;								
	if(($currentGold+$gold_deposit) >= $info[1]->asu){		
		$parse['iron_action'] = "<a href=\"plus.php?type=4\">".$lang['Extend']."</a>";
		$parse['iron_act'] = "extend";		
	}else{
		$parse['iron_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[1]->asu){		
		$parse['iron_action'] = "<a href=\"plus.php?type=4\">".$lang['Active']."</a>";
		$parse['iron_act'] = "active";		
	}else{
		$parse['iron_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}
//END: iron

//START: crop

$parse['total_gold'] = $currentGold;
$parse['total_gold_deposit']=$gold_deposit;
$parse['crop_time'] = "";
$timeEnd = getTimeEnd('crop', $user['id']);
$second = strtotime($timeEnd->crop) - time();	

if($second >0){
	$parse['crop_time'] ="<span class='c' style='font-size:11px'>".$lang['Remainder time']." 
	<span id='account".$i."'>".ReturnTime($second)."</span></span>";
	$i++;						
	if(($currentGold+$gold_deposit) >= $info[3]->asu){		
		$parse['crop_action'] = "<a href=\"plus.php?type=5\">".$lang['Extend']."</a>";
		$parse['crop_act'] = "extend";		
	}else{
		$parse['crop_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[3]->asu){		
		$parse['crop_action'] = "<a href=\"plus.php?type=5\">".$lang['Active']."</a>";
		$parse['crop_act'] = "active";		
	}else{
		$parse['crop_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}
//END: crop

//START: attack
$parse['total_gold_deposit']=$gold_deposit;
$parse['attack_time'] = "";
$timeEnd = getTimeEnd('attack', $user['id']);
$second = strtotime($timeEnd->attack) - time();	

if($second >0){
	
	$parse['attack_time'] ="<span class='c' style='font-size:11px'>".$lang['Remainder time']." 
	<span id='account".$i."'>".ReturnTime($second)."</span></span>";
	$i++;		
	if(($currentGold+$gold_deposit) >= $info[4]->asu){		
		$parse['attack_action'] = "<a href=\"plus.php?type=6\">".$lang['Extend']."</a>";
		$parse['attack_act'] = "extend";		
	}else{
		$parse['attack_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[4]->asu){		
		$parse['attack_action'] = "<a href=\"plus.php?type=6\">".$lang['Active']."</a>";
		$parse['attack_act'] = "active";		
	}else{
		$parse['attack_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}
//END: attack

//START: defence
$parse['total_gold'] = $currentGold;
$parse['total_gold_deposit']=$gold_deposit;
$parse['defence_time'] = "";
$timeEnd = getTimeEnd('defence', $user['id']);
$second = strtotime($timeEnd->defence) - time();	

if($second >0)
{
	$parse['defence_time'] ="<span class='c' style='font-size:11px'>".$lang['Remainder time']." 
	<span id='account".$i."'>".ReturnTime($second)."</span></span>";
	$i++;				
	if(($currentGold+$gold_deposit) >= $info[5]->asu){		
		$parse['defence_action'] = "<a href=\"plus.php?type=7\">".$lang['Extend']."</a>";
		$parse['defence_act'] = "extend";		
	}else{
		$parse['defence_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[5]->asu){		
		$parse['defence_action'] = "<a href=\"plus.php?type=7\">".$lang['Active']."</a>";
		$parse['defence_act'] = "active";		
	}else{
		$parse['defence_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}
//END: defence
//START: dinh chien

$parse['dinh_chien_time'] = "";
$timeEnd = getTimeEnd('dinh_chien', $user['id']);
$second = strtotime($timeEnd->dinh_chien) - time();	
if($second >0)
{
	$parse['dinh_chien_time'] ="<span class='c' style='font-size:11px'>".$lang['Remainder time']." 
	<span id='account".$i."'>".ReturnTime($second)."</span></span>";
	$i++;				
	if(($currentGold+$gold_deposit) >= $info_2['dinh_chien']){		
		$parse['dinh_chien_action'] = "<a href=\"plus.php?type=14\">".$lang['Extend']."</a>";
	}else{
		$parse['dinh_chien_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{
	if(($currentGold+$gold_deposit) >= $info_2['dinh_chien']){		
		$parse['dinh_chien_action'] = "<a href=\"plus.php?type=14\">".$lang['Active']."</a>";	
	}else{
		$parse['dinh_chien_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}
//END: dinh chien

//START: Build

$parse['total_gold'] =$currentGold;
$parse['total_gold_deposit']=$gold_deposit;
$parse['build_time'] = "";
$timeEnd = getTimeEnd('build', $user['id']);
$second = strtotime($timeEnd->build) - time();	

if($second >0){
	
	$parse['build_time'] ="<span class='c' style='font-size:11px'>".$lang['Remainder time']." 
	<span id='account".$i."'>".ReturnTime($second)."</span></span>";
	$i++;				
	if(($currentGold+$gold_deposit) >= $info[8]->asu){		
		$parse['build_action'] = "<a href=\"plus.php?type=9\">".$lang['Extend']."</a>";
		$parse['build_act'] = "extend";		
	}else{
		$parse['build_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[8]->asu){		
		$parse['build_action'] = "<a href=\"plus.php?type=9\">".$lang['Active']."</a>";
		$parse['build_act'] = "active";		
	}else{
		$parse['build_action'] = "<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}

//START: complete

$parse['total_gold'] = $currentGold;
$parse['total_gold_deposit']=$gold_deposit;
$total =  checkBuildOrUpdateOrDeleteBuilding($db->getEscaped($_SESSION['villa_id_cookie']));
if($total == 0){
	if(($currentGold+$gold_deposit) >= $info[6]->asu){
		$parse['complete_action'] = "<span class=\"c t\">".$lang['Active complete']."</span>";	
	}else{
		$parse['complete_action'] = "<span class=\"c t\">".$lang['Not enough Asu']."</span>";
	}		
}elseif($total > 0){
	if(($currentGold+$gold_deposit) >= $info[6]->asu){
		$parse['complete_action'] = "<a href=\"plus.php?type=8\">".$lang['Active complete']."</a>";		
		$parse['complete_act'] = "active";		
	}else{
		$parse['complete_action'] = "<span class=\"c t\">".$lang['Not enough Asu']."</span>";	
	}	
}
//END: complete

//BEGIN: cong thanh chien (cua Tu)
//Kiem tra da mua the bai cho lien minh hay chua
if($theBai->the_bai_3!=1 && !$cothe){
	//Kiem tra user nay co phai la minh chu hay khong
	if(checkMinhChu_ctc($user['id'])){
		//kiem tra xem du asu de mua the bai cho lien minh hay khong:
		if($currentGold>=$info_2['the_bai_3']){
			$parse['buy_action_3']	= "<a href=\"plus.php?type=12\">".$lang['buy']."</a>";
		}else{
			$parse['buy_action_3']	= "<span class=\"c t\">".$lang['Not enough Asu']."</span>";
		}			
	}else{
		$parse['buy_action_3']	= "<span class=\"c t\">".$lang['buy']."</span>";
	}
}else{
	$cothe = 1;	
	$parse['buy_action_3']	=  "<span class=\"c t\">".$lang['buy']."</span>";
}

//Kiem tra da mua the bai tham gia hay chua
if($theBai->the_bai_2 != 1 && !$cothe){	
	if($currentGold>=$info_2['the_bai_2']){
		$parse['buy_action_2']	= "<a href=\"plus.php?type=11\">".$lang['buy']."</a>";
	}else{
		$parse['buy_action_2']	= "<span class=\"c t\">".$lang['Not enough Asu']."</span>";
	}			
}else{
	$cothe = 1;
	$parse['buy_action_2']	= "<span class=\"c t\">".$lang['buy']."</span>";
}

//Kiem tra the bai xem hay chua
if($theBai->the_bai_1 != 1 && !$cothe){
	if($currentGold>=$info_2['the_bai_1']){
		$parse['buy_action_1']	= "<a href=\"plus.php?type=10\">".$lang['buy']."</a>";
	}else{
		$parse['buy_action_1']	= "<span class=\"c t\">".$lang['Not enough Asu']."</span>";
	}			
}else{
	$parse['buy_action_1']	= "<span class=\"c t\">".$lang['buy']."</span>";
}

//END: cong thanh chien (cua Tu)

//BEGIN: SMS attack

if($pl_sms){
	$parse['sms_action']	= "<a href=\"plus.php?type=13\">".$lang['cancel']."</a>";	
}else{
	//kiem tra asu:
	if($currentGold>=$info_2['sms_attack']){
		$parse['sms_action']	= "<a href=\"plus.php?type=13\">".$lang['Active']."</a>";
	}else{
		$parse['sms_action']	= "<span class=\"c t\">".$lang['Not enough Asu']."</span>";
	}
}
//END: SMS attack

//echo "<pre>"; print_r($info); die();

$page = parsetemplate(gettemplate('plus'), $parse);
display($page,$lang['plus']);

ob_end_flush();
?>