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
//require_once($ugamela_root_path . 'includes/function_resource.php');
require_once($ugamela_root_path . 'includes/function_plus.php');
require_once($ugamela_root_path . 'includes/function_ctc.php');
include_once($ugamela_root_path . 'soap/call.'.$phpEx);

checkRequestTime();
if(!check_user()){ header("Location: login.php");}

global $db,$user,$wg_village,$lang;
includeLang('plus');
$parse = $lang;

//START: dung chung
$village =$_SESSION['villa_id_cookie'];

//$wg_buildings = NULL;
$wg_village = NULL;
$wg_village=getVillage($village);
$wg_buildings = getBuildings($village);
//getSumCapacity($wg_village, $wg_buildings);
//UpdateRS($wg_village, $wg_buildings, time());

$gold_deposit=get_gold_remote($user['username']); //lay ASU tu buildling systems
$currentGold = showGold($user['id']);

$allAsu=$gold_deposit+$currentGold;
//END: dung chung

$sql = "SELECT * FROM wg_config_plus";
$db->setQuery($sql);
$info = NULL;
$info=$db->loadObjectList();
if($info)
{
	foreach($info as $v)
	{
		$info_1[$v->name] = $v->logs;
		$info_2[$v->name] = $v->asu;
		$info_3[$v->name] = $v->duration;
	}
}

$sql = "SELECT * FROM wg_item_user WHERE user_id = ".$user['id'];
$db->setQuery($sql);
$wg_item_user = NULL;
$wg_item_user=$db->loadObjectList();
$item_user=array();
if($wg_item_user)
{
	foreach($wg_item_user as $v)
	{
		$item_user[$v->item_name] = $v->quantity;
	}
}
//Kiem tra ctc da bat dau hay chua
$canBuyCard = checkStarted_ctc();
//Lay thong tin the bai cua user nay
$theBai = getTheBai_ctc($user['id']);
$cothe = 0;

$pl_sms = getSMSAttack_ctc($user['id']);


$timeEnd=getTimeEndAll($user['id']);

//END: show thong bao khi thuc hien complete

if(isset($_GET['type']) && is_numeric($_GET['type']))
{
	$type = $_GET['type'];
	switch($type)
	{
		case "2": // lumber	
			if($item_user['lumber'] >=1)
			{
				$second=strtotime($timeEnd->lumber) - time();				
				updateItemUser($user['id'],'lumber');
				if($second <1)
				{					
					updateTime('lumber',$info_3['lumber'], $user['id']);					
					updateCoefficient('krs1', $user['id']);				
				}
				else
				{					
					addMoreTime('lumber',$info_3['lumber'], $user['id']);									
				}
			}				
		break;		
		case "3": // clay	
			if($item_user['clay'] >=1)
			{
				$second = strtotime($timeEnd->clay) - time();				
				updateItemUser($user['id'],'clay');
				if($second <1)
				{
					updateTime('clay',$info_3['clay'], $user['id']);					
					updateCoefficient('krs2', $user['id']);
				}
				else
				{
					addMoreTime('clay', $info_3['clay'], $user['id']);		
				}
			}		
		break;		
		case "4": // iron
			if($item_user['iron'] >=1)
			{
				$second = strtotime($timeEnd->iron) - time();					
				updateItemUser($user['id'],'iron');
				if($second <1)
				{
					updateTime('iron', $info_3['iron'] , $user['id']);
					updateCoefficient('krs3', $user['id']);				
				}
				else
				{
					addMoreTime('iron', $info_3['iron'], $user['id']);
				}	
			}		
		break;		
		case "5": // crop
			if($item_user['crop'] >=1)
			{
				$second = strtotime($timeEnd->crop) - time();				
				updateItemUser($user['id'],'crop');
				if($second <1)
				{
					updateTime('crop',$info_3['crop'],$user['id']);					
					updateCoefficient('krs4', $user['id']);								
				}
				else				
				{
					addMoreTime('crop',$info_3['crop'], $user['id']);					
				}	
			}				
		break;
		
		case "6": // attack
			if($item_user['attack'] >=1)
			{
				$second = strtotime($timeEnd->attack) - time();	
				updateItemUser($user['id'],'attack');
				if($second <1)
				{
					updateTime('attack',$info_3['attack'], $user['id']);					
				}
				else
				{					
					addMoreTime('attack',$info_3['attack'], $user['id']);					
				}
			}		
		break;
		
		case "7": // defence
			if($item_user['defence'] >=1)
			{
				$second = strtotime($timeEnd->defence) - time();	
				updateItemUser($user['id'],'defence');
				if($second <1)
				{
					updateTime('defence',$info_3['defence'], $user['id']);					
				}
				else
				{
					addMoreTime('defence', $info_3['defence'], $user['id']);
				}
			}	
		break;
		
		//START: complete
		case "8":
			if($item_user['complete'] >=1)
			{
				$listStatus = getStatusForActiveNow($village);			
				if($listStatus)
				{
					updateItemUser($user['id'],'complete');
					$time_new=date("Y-m-d H:i:s",time()-5);
					foreach($listStatus as $row)
					{
						$sql="UPDATE wg_status SET time_end ='".$time_new."' WHERE id = ".$row->id."";
						$db->setQuery($sql);
						$db->query();
					}
					doAllStatus();										
				}
			}
			header("Location:".$_SERVER['HTTP_REFERER']);	
			exit(0);
		break;
		//END: complete
		
		case "9": // Build
			if($item_user['build'] >=1)
			{
				$second = strtotime($timeEnd->build) - time();	
				updateItemUser($user['id'],'build');
				if($second <1)
				{
					updateTime('build',$info_3['build'], $user['id']);					
				}
				else
				{
					addMoreTime('build',$info_3['build'], $user['id']);					
				}
			}						
		break;
		
		case 10: //the bai xem cong thanh chien
			if($theBai->the_bai_1!=1 && $item_user['the_bai_1'] >=1 && checkStarted_ctc())
			{
				updateItemUser($user['id'],'the_bai_1');				
				if($theBai){
					updateTheBai_ctc($user['id'], 1, 0, 0);
				}else{
					insertTheBai_ctc($user['id'], 1, 0, 0);
				}
				//kiem tra xem du asu khong:				
				/*$theBai->the_bai_1=1;
				if($currentGold>=$info_2['the_bai_1']){
					$currentGold = $currentGold-$info_2['the_bai_1'];
				}else{
					$gold_deposit = $gold_deposit+$currentGold-$info_2['the_bai_1'];
					$currentGold 	= 0;
				}*/
			}
			break;
		case 11:	//the bai tham gia Cong Thanh Chien
			if($theBai->the_bai_2!=1 && $item_user['the_bai_2'] >=1 && checkStarted_ctc())
			{
				updateItemUser($user['id'],'the_bai_2');
				if($theBai){
					updateTheBai_ctc($user['id'], 0, 1, 0);
				}else{
					insertTheBai_ctc($user['id'], 0, 1, 0);
				}
				//kiem tra xem du asu khong:				
				/*$theBai->the_bai_2=1;
				if($currentGold>=$info_2['the_bai_2']){
					$currentGold = $currentGold-$info_2['the_bai_2'];
				}else{
					$gold_deposit = $gold_deposit+$currentGold-$info_2['the_bai_2'];
					$currentGold  = 0;
				}*/
			}
			break;
		case 12:	//the bai tham gia Cong Thanh Chien cho lien minh
			if($theBai->the_bai_3!=1 && $item_user['the_bai_3'] >=1 && checkStarted_ctc())
			{
				if(checkMinhChu_ctc($user['id']))
				{
					updateItemUser($user['id'],'the_bai_3');
					if($theBai){
						updateTheBai_ctc($user['id'], 0, 0, 1);
					}else{
						insertTheBai_ctc($user['id'], 0, 0, 1);
					}
					//kiem tra xem du asu khong:			
					/*$theBai->the_bai_3=1;
					if($currentGold>=$info_2['the_bai_3']){
						$currentGold = $currentGold-$info_2['the_bai_3'];
					}else{
						$gold_deposit = $gold_deposit+$currentGold-$info_2['the_bai_3'];
						$currentGold  = 0;
					}*/
				}
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
		case 14: // dinh chien			
			if($item_user['dinh_chien'] >=1)
			{
				$second = strtotime($timeEnd->dinh_chien) - time();	
				updateItemUser($user['id'],'dinh_chien');				
				if($second < 0)
				{					
					updateTime('dinh_chien', $info_3["dinh_chien"], $user['id']);					
				}
				else
				{
					addMoreTime('dinh_chien', $info_3["dinh_chien"], $user['id']);
				}
			}					
		break;
		case 15: // map_large
			if($item_user['map_large'] >=1)
			{
				$second = strtotime($timeEnd->map_large) - time();	
				updateItemUser($user['id'],'map_large');								
				if($second < 0)
				{					
					updateTime('map_large', $info_3["map_large"], $user['id']);					
				}
				else
				{
					addMoreTime('map_large', $info_3["map_large"], $user['id']);
				}
			}							
		break;
		case "16": // all resource
			if($item_user['all_resource'] >=1)
			{
				updateItemUser($user['id'],'all_resource');
				////////////////////////////////////////////////////
				$second_all_resource=strtotime($timeEnd->all_resource) - time();				
				if($second_all_resource <1)
				{					
					updateTime('all_resource',$info_3['all_resource'], $user['id']);		
				}
				else
				{					
					addMoreTime('all_resource',$info_3['all_resource'], $user['id']);									
				}
				////////////////////////////////////////////////////
				$second_lumber=strtotime($timeEnd->lumber) - time();				
				if($second_lumber <1)
				{					
					updateTime('lumber',$info_3['lumber'], $user['id']);					
					updateCoefficient('krs1', $user['id']);				
				}
				else
				{					
					addMoreTime('lumber',$info_3['lumber'], $user['id']);									
				}
				////////////////////////////////////////////////////
				$second_clay = strtotime($timeEnd->clay) - time();				
				if($second_clay <1)
				{
					updateTime('clay',$info_3['clay'], $user['id']);					
					updateCoefficient('krs2', $user['id']);
				}
				else
				{
					addMoreTime('clay', $info_3['clay'], $user['id']);		
				}
				/////////////////////////////////////////////////////
				$second_iron = strtotime($timeEnd->iron) - time();					
				if($second_iron <1)
				{
					updateTime('iron', $info_3['iron'] , $user['id']);
					updateCoefficient('krs3', $user['id']);				
				}
				else
				{
					addMoreTime('iron', $info_3['iron'], $user['id']);
				}
				///////////////////////////////////////////////////
				$second_crop = strtotime($timeEnd->crop) - time();				
				if($second_crop <1)
				{
					updateTime('crop',$info_3['crop'],$user['id']);					
					updateCoefficient('krs4', $user['id']);								
				}
				else				
				{
					addMoreTime('crop',$info_3['crop'], $user['id']);					
				}	
			}				
		break;
		case "17"://giam thoi gian xay 30
			if($item_user['speedup_15'] >=1)
			{
				$listStatus = getStatusForActiveNow($village);			
				if($listStatus)
				{
					updateItemUser($user['id'],'speedup_15');
					foreach($listStatus as $row)
					{
						$time_new = date("Y-m-d H:i:s",strtotime($row->time_end)-1800);
						$time_now=date("Y-m-d H:i:s",time()-5);
						if($time_new >= $time_now)
						{
							$sql="UPDATE wg_status SET time_end ='".$time_new."' WHERE id = ".$row->id."";
						}
						else
						{
							$sql="UPDATE wg_status SET time_end ='".$time_now."' WHERE id = ".$row->id."";
						}
						$db->setQuery($sql);
						$db->query();
					}
					doAllStatus();										
				}
			}
			header("Location:".$_SERVER['HTTP_REFERER']);
			exit(0);
		break;
		case "18"://giam thoi gian xay 1 gio
			if($item_user['speedup_30'] >=1)
			{
				$listStatus = getStatusForActiveNow($village);			
				if($listStatus)
				{
					updateItemUser($user['id'],'speedup_30');
					foreach($listStatus as $row)
					{
						$time_new = date("Y-m-d H:i:s",strtotime($row->time_end)-3600);					
						$time_now=date("Y-m-d H:i:s",time()-5);
						if($time_new >= $time_now)
						{
							$sql="UPDATE wg_status SET time_end ='".$time_new."' WHERE id = ".$row->id."";
						}
						else
						{
							$sql="UPDATE wg_status SET time_end ='".$time_now."' WHERE id = ".$row->id."";
						}
						$db->setQuery($sql);
						$db->query();
					}
					doAllStatus();										
				}
			}
			header("Location:".$_SERVER['HTTP_REFERER']);	
			exit(0);
		break;
		case "19"://giam thoi gian xay 4h
			if($item_user['speedup_2h'] >=1)
			{
				$listStatus = getStatusForActiveNow($village);			
				if($listStatus)
				{
					updateItemUser($user['id'],'speedup_2h');
					foreach($listStatus as $row)
					{
						$time_new = date("Y-m-d H:i:s",strtotime($row->time_end)-14400);
						$time_now=date("Y-m-d H:i:s",time()-5);
						if($time_new >= $time_now)
						{
							$sql="UPDATE wg_status SET time_end ='".$time_new."' WHERE id = ".$row->id."";
						}
						else
						{
							$sql="UPDATE wg_status SET time_end ='".$time_now."' WHERE id = ".$row->id."";
						}
						$db->setQuery($sql);
						$db->query();
					}
					doAllStatus();										
				}
			}
			header("Location:".$_SERVER['HTTP_REFERER']);	
			exit(0);
		break;
	}
	
}
header("Location:shop.php?tab=1#s".$type);			
exit(0);
ob_end_flush();
?>