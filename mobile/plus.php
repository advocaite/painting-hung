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
include_once($ugamela_root_path . 'soap/call.'.$phpEx);

checkRequestTime();
if(!check_user()){ header("Location: login.php");}

global $db,$user,$wg_buildings,$wg_village,$timeAgain,$lang;
includeLang('plus');
includeLang('lang_Wap');
$parse = $lang;
$timeAgain=$user['amount_time']-(time()-$_SESSION['last_login']);
//START: dung chung
$village =$_SESSION['villa_id_cookie'];

$wg_buildings = NULL;
$wg_village = NULL;
$wg_village=getVillage($village);
$wg_buildings = getBuildings($village);
getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());

if($wg_village->name=="NewName")
{
	$parse['Name_Village']=$lang['NewName'];
}
else
{
	$parse['Name_Village']=$wg_village->name;
}
$parse['top_menu'] = parsetemplate(gettemplate('top_menu'), $parse);
$parse['village_id'] = $wg_village->id;

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

//END: show thong bao khi thuc hien complete

if(isset($_GET['type']) && is_numeric($_GET['type']))
{
	$type = $_GET['type'];
	//$action = $_GET['act'];	

	switch($type)
	{
		case "2": // lumber	
			$timeEnd = getTimeEnd('lumber', $user['id']);
			$second = strtotime($timeEnd->lumber) - strtotime(date("Y-m-d H:i:s",time()));	
			if($second < 0){
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[0]->asu,$user['id'],$gold_deposit,1);
				if($currentGold>=0){
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;					
					// Update time
					updateTime('lumber', $info[0]->duration, $user['id']);					
					// update he so lumber: krs1
					updateCoefficient('krs1', $user['id']);						
					//$wg_village->krs1 = $wg_village->krs1 * 1.25;						
				}
			}else{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[0]->asu, $user['id'],$gold_deposit,1);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;					
					// cong them thoi gian 
					addMoreTime('lumber', $info[0]->duration, $user['id']);									
				}
			}	
			header("Location: plus.php");			
			exit(0);
		break;
		
		case "3": // clay	
			$timeEnd = getTimeEnd('clay', $user['id']);
			$second = strtotime($timeEnd->clay) - strtotime(date("Y-m-d H:i:s",time()));	
			if($second < 0)	{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[2]->asu, $user['id'],$gold_deposit,2);				
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;					
					// Update time
					updateTime('clay', $info[2]->duration, $user['id']);					
					// update he so clay: krs2
					updateCoefficient('krs2', $user['id']);
					//$wg_village->krs2 = $wg_village->krs2 * 1.25;
					
				}
			}else{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[2]->asu, $user['id'],$gold_deposit,2);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;	
					$parse['total_gold_deposit']=$gold_deposit;				
					// cong them thoi gian 
					addMoreTime('clay', $info[2]->duration, $user['id']);		
				}
			}		
			header("Location: plus.php");	
			exit(0);		
		break;
		
		case "4": // iron
			$timeEnd = getTimeEnd('iron', $user['id']);
			$second = strtotime($timeEnd->iron) - strtotime(date("Y-m-d H:i:s",time()));	
			if($second < 0)	{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[1]->asu, $user['id'],$gold_deposit,3);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;
					// Update time
					updateTime('iron', $info[1]->duration, $user['id']);
					// update he so iron: krs3
					updateCoefficient('krs3', $user['id']);
					//$wg_village->krs3 = $wg_village->krs3 * 1.25;						
				}
			}else{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[1]->asu, $user['id'],$gold_deposit,3);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;					
					// cong them thoi gian 
					addMoreTime('iron', $info[1]->duration, $user['id']);
				}
			}			
			header("Location: plus.php");
			exit(0);		
		break;
		
		case "5": // crop
			$timeEnd = getTimeEnd('crop', $user['id']);
			$second = strtotime($timeEnd->crop) - strtotime(date("Y-m-d H:i:s",time()));	
			if($second < 0)	{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[3]->asu, $user['id'],$gold_deposit,4);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;					
					// Update time
					updateTime('crop', $info[3]->duration, $user['id']);					
					// update he so crop: krs4
					updateCoefficient('krs4', $user['id']);
					//$wg_village->krs4 = $wg_village->krs4 * 1.25;													
				}
			}else{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[3]->asu, $user['id'],$gold_deposit,4);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;					
					// cong them thoi gian 
					addMoreTime('crop', $info[3]->duration, $user['id']);					
				}
			}	
			header("Location: plus.php");
			exit(0);				
		break;
		
		case "6": // attack
			$timeEnd = getTimeEnd('attack', $user['id']);
			$second = strtotime($timeEnd->attack) - strtotime(date("Y-m-d H:i:s",time()));	
			if($second < 0)	{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[4]->asu, $user['id'],$gold_deposit,5);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;	
					$parse['total_gold_deposit']=$gold_deposit;				
					// Update time
					updateTime('attack', $info[4]->duration, $user['id']);					
				}
			}else{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[4]->asu, $user['id'],$gold_deposit,5);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;	
					$parse['total_gold_deposit']=$gold_deposit;				
					// cong them thoi gian 
					addMoreTime('attack', $info[4]->duration, $user['id']);					
				}
			}		
			header("Location: plus.php");	
			exit(0);		
		break;
		
		case "7": // defence
			$timeEnd = getTimeEnd('defence', $user['id']);
			$second = strtotime($timeEnd->defence) - strtotime(date("Y-m-d H:i:s",time()));	
			if($second < 0)	{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[5]->asu, $user['id'],$gold_deposit,6);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;					
					// Update time
					updateTime('defence', $info[5]->duration, $user['id']);					
				}
			}else{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[5]->asu, $user['id'],$gold_deposit,6);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;	
					$parse['total_gold_deposit']=$gold_deposit;				
					// cong them thoi gian 
					addMoreTime('defence', $info[5]->duration, $user['id']);					
				}
			}	
			header("Location: plus.php");
			exit(0);				
		break;
		
		//START: complete
		case "8":
			$listStatus = getStatusForActiveNow($village);			
			if($listStatus)
			{
				$currentGold = withdrawGold($info[6]->asu, $user['id'],$gold_deposit,7);
				if($currentGold>=0)
				{
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;									
					$build = 0;
					$update = 0;
					$delete = 0;
					$time_new=date("Y-m-d H:i:s",time()-5);
					foreach($listStatus as $row)
					{
						$sql="UPDATE wg_status SET time_end ='".$time_new."' WHERE id = ".$row->id."";
						$db->setQuery($sql);
						$db->query();					
								
						if($row->type == 1){
							$build ++;
						}
						if($row->type == 2 or $row->type == 3){
							$update ++;
						}
						if($row->type == 17){
							$delete ++;
						}
					}
					doAllStatus();						
					header("Location: plus.php?b=$build&u=$update&d=$delete");	
					exit(0);
				}
			}			
		break;
		//END: complete
		
		case "9": // Build
			$timeEnd = getTimeEnd('build', $user['id']);
			$second = strtotime($timeEnd->build) - strtotime(date("Y-m-d H:i:s",time()));	
			if($second < 0)	{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[8]->asu, $user['id'],$gold_deposit,12);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;
					$parse['total_gold_deposit']=$gold_deposit;					
					// Update time
					updateTime('build', $info[8]->duration, $user['id']);					
				}
			}else{
				// tru (-) so gold cua user
				$currentGold = withdrawGold($info[8]->asu, $user['id'],$gold_deposit,12);
				if($currentGold>=0)	{
					$parse['total_gold'] = $currentGold;	
					$parse['total_gold_deposit']=$gold_deposit;				
					// cong them thoi gian 
					addMoreTime('build', $info[8]->duration, $user['id']);					
				}
			}	
			header("Location: plus.php");
			exit(0);				
		break;
		
		case 10: //mua the bai xem cong thanh chien
			//kiem tra xem du asu khong:
			if($theBai->the_bai_1!=1 && ($currentGold+$gold_deposit)>=$info_2['the_bai_1']){
				//tru asu:
				withdrawGold($info_2['the_bai_1'], $user['id'],$gold_deposit,13);
				if($theBai){
					updateTheBai_ctc($user['id'], 1, 0, 0);
				}else{
					insertTheBai_ctc($user['id'], 1, 0, 0);
				}					
				$theBai->the_bai_1=1;
				$currentGold = $currentGold>$info_2['the_bai_1']?($currentGold-$info_2['the_bai_1']):0;
				$gold_deposit = $gold_deposit+$currentGold-$info_2['the_bai_1'];
			}
			break;
		case 11:	//mua the bai tham gia Cong Thanh Chien
			//kiem tra xem du asu khong:
			if($theBai->the_bai_2!=1 && ($currentGold+$gold_deposit)>=$info_2['the_bai_2']){
				//tru asu:
				withdrawGold($info_2['the_bai_2'], $user['id'],$gold_deposit,14);
				if($theBai){
					updateTheBai_ctc($user['id'], 0, 1, 0);
				}else{
					insertTheBai_ctc($user['id'], 0, 1, 0);
				}					
				$theBai->the_bai_2=1;
				$currentGold = $currentGold>$info_2['the_bai_2']?($currentGold-$info_2['the_bai_2']):0;
				$gold_deposit = $gold_deposit+$currentGold-$info_2['the_bai_2'];
			}
			break;
		case 12:	//mua the bai tham gia Cong Thanh Chien cho lien minh
			//kiem tra xem du asu khong:
			if($theBai->the_bai_3!=1 && ($currentGold+$gold_deposit)>=$info_2['the_bai_3']){
				//tru asu:
				withdrawGold($info_2['the_bai_3'], $user['id'],$gold_deposit,15);
				if($theBai){
					updateTheBai_ctc($user['id'], 0, 0, 1);
				}else{
					insertTheBai_ctc($user['id'], 0, 0, 1);
				}					
				$theBai->the_bai_3=1;
				$currentGold = $currentGold>$info_2['the_bai_3']?($currentGold-$info_2['the_bai_3']):0;
				$gold_deposit = $gold_deposit+$currentGold-$info_2['the_bai_3'];
			}
			break;
		case 13:	//SMS bao attack
			//Kiem tra xem la huy bo hay kich hoat
			if($pl_sms){//huy bo			
				if(updateSMSAttack_ctc($user['id'], 0)){
					$pl_sms = 0;
				}					
			}else{//kich hoat
				if(($currentGold+$gold_deposit)>=$info_2['sms_attack']){
					if(updateSMSAttack_ctc($user['id'], 1)){
						$pl_sms = 1;
					}					
				}
			}								
			break;
	}
	
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
		$parse['lumber_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']."</span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[0]->asu)	{		
		$parse['lumber_action'] = "<a href=\"plus.php?type=2\">".$lang['Active']."</a>";
		$parse['lumber_act'] = "active";		
	}else{
		$parse['lumber_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
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
		$parse['clay_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[2]->asu){		
		$parse['clay_action'] = "<a href=\"plus.php?type=3\">".$lang['Active']."</a>";
		$parse['clay_act'] = "active";		
	}else{
		$parse['clay_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
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
		$parse['iron_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[1]->asu){		
		$parse['iron_action'] = "<a href=\"plus.php?type=4\">".$lang['Active']."</a>";
		$parse['iron_act'] = "active";		
	}else{
		$parse['iron_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
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
		$parse['crop_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[3]->asu){		
		$parse['crop_action'] = "<a href=\"plus.php?type=5\">".$lang['Active']."</a>";
		$parse['crop_act'] = "active";		
	}else{
		$parse['crop_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
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
		$parse['attack_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[4]->asu){		
		$parse['attack_action'] = "<a href=\"plus.php?type=6\">".$lang['Active']."</a>";
		$parse['attack_act'] = "active";		
	}else{
		$parse['attack_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
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
		$parse['defence_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[5]->asu){		
		$parse['defence_action'] = "<a href=\"plus.php?type=7\">".$lang['Active']."</a>";
		$parse['defence_act'] = "active";		
	}else{
		$parse['defence_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}
//END: defence


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
		$parse['build_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}else{	
	if(($currentGold+$gold_deposit) >= $info[8]->asu){		
		$parse['build_action'] = "<a href=\"plus.php?type=9\">".$lang['Active']."</a>";
		$parse['build_act'] = "active";		
	}else{
		$parse['build_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']." </span>";	
	}		
}

//START: complete

$parse['total_gold'] = $currentGold;
$parse['total_gold_deposit']=$gold_deposit;
$total =  checkBuildOrUpdateOrDeleteBuilding($db->getEscaped($_SESSION['villa_id_cookie']));
if($total == 0){
	if(($currentGold+$gold_deposit) >= $info[6]->asu){
		$parse['complete_action'] = '';//"<span class=\"c t\">".$lang['Active complete']."</span>";	
	}else{
		$parse['complete_action'] = '';// "<span class=\"c t\">".$lang['Not enough Asu']."</span>";
	}		
}elseif($total > 0){
	if(($currentGold+$gold_deposit) >= $info[6]->asu){
		$parse['complete_action'] = "<a href=\"plus.php?type=8\">".$lang['Active complete']."</a>";		
		$parse['complete_act'] = "active";		
	}else{
		$parse['complete_action'] = '';//"<span class=\"c t\">".$lang['Not enough Asu']."</span>";	
	}	
}
//END: complete

//BEGIN: cong thanh chien (cua Tu)
//Kiem tra da mua the bai cho lien minh hay chua
if($theBai->the_bai_3!=1 && !$cothe){
	//Kiem tra user nay co phai la minh chu hay khong
	if(checkMinhChu_ctc($user['id'])){
		//kiem tra xem du asu de mua the bai cho lien minh hay khong:
		if(($currentGold+$gold_deposit)>=$info_2['the_bai_3']){
			$parse['buy_action_3']	= "<a href=\"plus.php?type=12\">".$lang['buy']."</a>";
		}else{
			$parse['buy_action_3']	= '';//"<span class=\"c t\">".$lang['Not enough Asu']."</span>";
		}			
	}else{
		$parse['buy_action_3']	= '';//"<span class=\"c t\">".$lang['buy']."</span>";
	}
}else{
	$cothe = 1;	
	$parse['buy_action_3']	= '';//"<span class=\"c t\">".$lang['buy']."</span>";
}

//Kiem tra da mua the bai tham gia hay chua
if($theBai->the_bai_2 != 1 && !$cothe){	
	if(($currentGold+$gold_deposit)>=$info_2['the_bai_2']){
		$parse['buy_action_2']	= "<a href=\"plus.php?type=11\">".$lang['buy']."</a>";
	}else{
		$parse['buy_action_2']	= '';//"<span class=\"c t\">".$lang['Not enough Asu']."</span>";
	}			
}else{
	$cothe = 1;
	$parse['buy_action_2']	= '';//"<span class=\"c t\">".$lang['buy']."</span>";
}

//Kiem tra the bai xem hay chua
if($theBai->the_bai_1 != 1 && !$cothe){
	if(($currentGold+$gold_deposit)>=$info_2['the_bai_1']){
		$parse['buy_action_1']	= "<a href=\"plus.php?type=10\">".$lang['buy']."</a>";
	}else{
		$parse['buy_action_1']	= '';//"<span class=\"c t\">".$lang['Not enough Asu']."</span>";
	}			
}else{
	$parse['buy_action_1']	= '';//"<span class=\"c t\">".$lang['buy']."</span>";
}

//END: cong thanh chien (cua Tu)

//BEGIN: SMS attack

if($pl_sms){
	$parse['sms_action']	= "<a href=\"plus.php?type=13\">".$lang['cancel']."</a>";	
}else{
	//kiem tra asu:
	if(($currentGold+$gold_deposit)>=$info_2['sms_attack']){
		$parse['sms_action']	= "<a href=\"plus.php?type=13\">".$lang['Active']."</a>";
	}else{
		$parse['sms_action']	= '';//"<span class=\"c t\" >".$lang['Not enough Asu']."</span>";
	}
}
//END: SMS attack

//echo "<pre>"; print_r($info); die();

$page = parsetemplate(gettemplate('plus'), $parse);
display($page,$lang['plus']);

ob_end_flush();
?>