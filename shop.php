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

global $db,$user,$wg_buildings,$wg_village,$timeAgain,$lang,$parse,$info;
includeLang('plus');
$parse = $lang;

$village =$_SESSION['villa_id_cookie'];

$wg_buildings = NULL;
$wg_village = NULL;
$wg_village=getVillage($village);
$wg_buildings = getBuildings($village);
getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());

$timeEnd=getTimeEndAll($user['id']);

$asu_bill=get_gold_remote($user['username']); //lay ASU tu buildling systems
$currentGold = showGold($user['id']);
$allGold = $asu_bill + $currentGold;
$msg='';

$get_Tab = intval($_GET['tab'],2);
switch($get_Tab)
{
	case 1:
		$link=array("lumber"=>2,"iron"=>4,"clay"=>3,"crop"=>5,"attack"=>6,"defence"=>7,"complete"=>8,"build"=>9,"the_bai_1"=>10,"the_bai_2"=>11,"the_bai_3"=>12,"sms_attack"=>13,"dinh_chien"=>14,"map_large"=>15,"all_resource"=>16,"speedup_15"=>17,"speedup_30"=>18,"speedup_2h"=>19);
		$parse['class_shop']="";
		$parse['class_MyItem']="class=selected";		
		$sql = "SELECT wg_config_plus.*, wg_item_user.quantity 
		FROM wg_config_plus left join wg_item_user on wg_config_plus.name = wg_item_user.item_name 
		WHERE wg_item_user.user_id = ".$user['id'];
		$db->setQuery($sql);
		$info = NULL;
		$info=$db->loadObjectList();
		if($info)
		{	
			$i = 1;
			$row_myitem = '';	
			foreach($info as $v)
			{
					$parse['image']=$v->images;
					$parse['description']=$lang['Des_'.$v->name];
					$parse['quantity_']=$v->quantity;
					$parse['duration']=$v->duration;
					$name=$v->name;
					$parse['slogan']='';
					$parse['id']='s'.$link[$name];
					if ($v->quantity >= 1)
					{
						switch($v->name)
						{
							case 'the_bai_1':
							case 'the_bai_2':
								if(!checkStarted_ctc())
								{
									$use = "Công thành chiến chưa bắt đầu";
									$parse['slogan']=$use;
								}
								break;
							case 'the_bai_3':
								if(!checkMinhChu_ctc($user['id']))
								{
									$use = "Bạn không phải là minh chủ";
									$parse['slogan']=$use;
								}elseif(!checkStarted_ctc())
								{
									$use = "Công thành chiến chưa bắt đầu";
									$parse['slogan']=$use;
								}
								break;
							default:
								$use = $lang['Use'];
								$parse['slogan']='<a href="plus.php?type='.$link[$name].'">'.$use.'</a>';
								break;
						}
					}
					$parse['time']='';
					$second = strtotime($timeEnd->$name) - time();	
					if($second >0)
					{
						$parse['time']="<b class=\"ten\"><span id='item_".$i."'>".ReturnTime($second)."</span></b><br />";
						$i++;
					}								
					$row_myitem.= parsetemplate(gettemplate('myitem_row'),$parse);

			}
		}
		
		$parse['total_gold']=$currentGold;
		$parse['total_asu_bill']=$asu_bill;
		$parse['row_myitem'] =$row_myitem;
		$template=gettemplate('myItem');
		break;
	default:
		$parse['class_shops']="class=selected";
		$parse['display1']=$parse['display2']=$parse['display3']=$parse['display4']='style="display:none;"';
		if(!isset($_GET['s'])){$parse['class_shop1']="selected";$parse['display1']='style:"display:none;"';}
		else{
			$parse['class_shop'.$_GET['s']]="selected";
			$parse['display'.$_GET['s']]='style="display:block;"';
		}
		$sql = "SELECT * FROM wg_config_plus";
		$db->setQuery($sql);
		$info = NULL;
		$info=$db->loadObjectList();
		if($info)
		{
			$ArrItemAsu = array();
			foreach($info as $v)
			{
				$parse[$v->name.'_image']=$v->images;
				$parse[$v->name.'_duration']=$v->duration;
				if($v->duration == 0)
				{
					$parse[$v->name.'_duration']=$lang['immediately'];
				}
				$parse[$v->name.'_asu']=$v->asu;
				$ArrItemAsu[$v->name] = $v->asu;
				if ($allGold >= $v->asu)
				{
					$parse[$v->name.'_action']='';
				}
				else
				{
					$parse[$v->name.'_action']='disabled="disabled"';
				}
			}
		}
		$parse['total_gold']=$currentGold;
		$parse['total_asu_bill']=$asu_bill;
		$template=gettemplate('shop');
		break;
}
if(count($_POST)<>0 && isset($_GET['type']) && is_numeric($_GET['type']))
{
	$user_id = $user['id'];
	$type_old = 0;
	$type = intval($_GET['type']);
	
	$msg=$lang['msg_success'];
	
	if($type == 19)
	{
		$type_old = 19;
		// item ngau nhien
		$item_name = 'random';
		$item_asu = $ArrItemAsu['random'];
		$name_log = 24;
		$quantity = 1;
		if(($asu_bill+$currentGold) >= ($quantity*$item_asu))
		{
			$temp=withdrawGold($quantity*$item_asu,$user_id,$asu_bill,$name_log,$timeEnd_logs,$currentGold);
			srand((float) microtime() * 10000000);
			$input = array("1", "2", "3", "4", "5","6","7","9","11","12","13","14","17","18","1","2", "3", "4","20","21","22");
			$rand_keys = array_rand($input, 1);
			$type = $input[$rand_keys];
			InsertLogPlus($user_id,$name_log,$item_asu);
		}
		else
		{
			$msg=$lang['msg_error'];
		}
	}
	switch($type)
	{
		case 1://lumber
			$item_name = 'lumber';
			$item_asu = $ArrItemAsu['lumber'];
			$name_log = 1;
			break;
		case 2://iron
			$item_name = 'iron';
			$item_asu = $ArrItemAsu['iron'];
			$name_log = 3;
			break;
		case 3://clay
			$item_name = 'clay';
			$item_asu = $ArrItemAsu['clay'];
			$name_log = 2;
			break;
		case 4://crop
			$item_name = 'crop';
			$item_asu = $ArrItemAsu['crop'];
			$name_log = 4;
			break;
		case 5://attack
			$item_name = 'attack';
			$item_asu = $ArrItemAsu['attack'];
			$name_log = 5;
			break;
		case 6://defence
			$item_name = 'defence';
			$item_asu = $ArrItemAsu['defence'];
			$name_log = 6;
			break;
		case 7://complete
			$item_name = 'complete';
			$item_asu = $ArrItemAsu['complete'];
			$name_log = 7;
			break;
		case 8://npctrade
			$item_name = 'npctrade';
			$item_asu = $ArrItemAsu['npctrade'];
			$name_log = 8;
			break;
		case 9://build
			$item_name = 'build';
			$item_asu = $ArrItemAsu['build'];
			$name_log = 12;
			break;
		case 10://sms
			$item_name = 'sms_attack';
			$item_asu = $ArrItemAsu['sms_attack'];
			$name_log = 2;
			break;
		case 11://the bai 1 xem
			$item_name = 'the_bai_1';
			$item_asu = $ArrItemAsu['the_bai_1'];
			$name_log = 13;
			break;
		case 12://the bai 2 tham gia
			$item_name = 'the_bai_2';
			$item_asu = $ArrItemAsu['the_bai_2'];
			$name_log = 14;
			break;
		case 13://the bai 3 lien minh
			$item_name = 'the_bai_3';
			$item_asu = $ArrItemAsu['the_bai_3'];
			$name_log = 15;
			break;
		case 14://dinh_chien
			$item_name = 'dinh_chien';
			$item_asu = $ArrItemAsu['dinh_chien'];
			$name_log = 16;
			break;		
		case 15://cung_menh
			$item_name = 'cung_menh';
			$item_asu = $ArrItemAsu['cung_menh'];
			$name_log = 17;
			break;
		case 16://speech_troop
			$item_name = 'speech_troop';
			$item_asu = $ArrItemAsu['speech_troop'];
			break;
		case 17://large map
			$item_name = 'map_large';
			$item_asu = $ArrItemAsu['map_large'];
			$name_log = 22;
			break;
		case 18:// all_resource
			$item_name = 'all_resource';
			$item_asu = $ArrItemAsu['all_resource'];
			$name_log = 23;
			break;
			
//da co		case 19://item ngau nhien

		case 20://xay nhanh 15phut; 5ASU
			$item_name = 'speedup_15';
			$item_asu = $ArrItemAsu['speedup_15'];
			$name_log = 24;
			break;
		case 21://xay nhanh 30phut; 10ASU
			$item_name = 'speedup_30';
			$item_asu = $ArrItemAsu['speedup_30'];
			$name_log = 25;
			break;
		case 22://xay nhanh 2h; 15ASU
			$item_name = 'speedup_2h';
			$item_asu = $ArrItemAsu['speedup_2h'];
			$name_log = 26;
			break;			
	}
	if($type!=19)
	{
		if(!updateQuantilyItem($user_id,$item_name,$item_asu,$asu_bill,$currentGold,$name_log,$timeEnd->logs,$type_old))
		{
			$msg=$lang['msg_error'];		
		}
		if($type_old!=19)
		{
			InsertLogPlus($user_id,$name_log,$item_asu);
		}else
		{
			$msg.='&nbsp;'.$lang['Des_'.$item_name];
		}
	}
}
$parse['msg']=$msg;
$page = parsetemplate($template,$parse);
display($page,$lang['plus']);
ob_end_flush();

function updateQuantilyItem($user_id,$item_name,$item_asu,$asu_bill,$currentGold,$name_log,$timeEnd_logs,$type_old)
{
	global $db,$parse,$info,$user;
	$quantity=intval($_POST['input_'.$item_name]);
	if($type_old ==19)
	{
		$quantity=1;
	}
	if(($asu_bill+$currentGold) >= ($quantity*$item_asu))
	{
		if($type_old !=19)
		{
			$temp=withdrawGold($quantity*$item_asu,$user_id,$asu_bill,$name_log,$timeEnd_logs,$currentGold);
		}
		
		$sql = 	"SELECT * FROM wg_item_user WHERE user_id = $user_id and item_name = '$item_name'";
		$db->setQuery($sql);
		$check=null;
		$db->loadObject($check);
		if($check)
		{
			$sql_update = "UPDATE `wg_item_user` SET `quantity` = (`quantity` + $quantity) WHERE `user_id` = $user_id AND `item_name` = '$item_name'";
			$db->setQuery($sql_update);
			$db->Query();
		}
		else
		{
			$sql_insert = "INSERT INTO `wg_item_user` (`user_id` ,`item_name` ,`quantity`) VALUES ($user_id, '$item_name', '$quantity')";
			$db->setQuery($sql_insert);
			$db->Query();
		}
		if($currentGold >= ($quantity*$item_asu))
		{
			$currentGold=$currentGold - ($quantity*$item_asu);
			$parse['total_gold']=showGold($user['id']);
		}
		else
		{
			$parse['total_gold']=0;
			$asu_bill=$asu_bill-(($quantity*$item_asu)-$currentGold);
			$parse['total_asu_bill']=get_gold_remote($user['username']);
		}
		if($info)
		{
			foreach($info as $v)
			{
				if (($asu_bill + $currentGold) >= $v->asu)
				{
					$parse[$v->name.'_action']='';
				}
				else
				{
					$parse[$v->name.'_action']='disabled="disabled"';
				}
			}
		}		
		return true;
	}	
	return false;
}
?>