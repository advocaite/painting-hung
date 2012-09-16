<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
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

if(!check_user()){ header("Location: login.php"); }
global $db,$village,$game_config,$wg_village_kinds,$wg_village,$wg_buildings,$user,$wg_status;
doAnounment();
includeLang('village1');

$parse = $lang;
/*
* @Author: duc hien
* @Des: hien thi thong tin linh cua lang
* @param: $village :  id cua lang
* @return: template thong tin linh
*/
// kiem tra tinh bao mat cho lang
if(!empty($_GET['vid']) && is_numeric($_GET['vid']))
{
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE id=".$_GET['vid']." AND user_id=".$user["id"]." LIMIT 1";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	if($count==1)
	{
		setcookie('villa_id_cookie',$_GET['vid'],time()+31536000, "/", "", 0);
		$village=$_GET['vid'];
	}else{
		$village=$_COOKIE['villa_id_cookie'];
	}
}else{
	if(isset($_COOKIE['villa_id_cookie'])){
		$village=$_COOKIE['villa_id_cookie'];
	}else{
		$village=$user['villages_id'];
	}
}

$wg_village=null;
$wg_status=null;
$wg_village=getVillage($village);

$wg_status=getStatusProcessing();

if(!isset($_SESSION['password_viewuser']) && !empty($_GET['a']) && is_numeric($_GET['a']) && isset($_COOKIE["UpdateBuilding".$_GET['a'].""]))
{
/*
$_COOKIE['UpdateBuilding']=$value=''.$id.','.$Time_Cost.','.$level.','.$type_id.','.$Lumber.','.$Clay.','.$Iron.','.$Crop.',,'.md5(time()).'';*/
	$string=GiaiMa($_COOKIE['UpdateBuilding'.$_GET['a'].'']);
	$array=string_to_array($string);
	//if($_GET['a']==$array[3] && $user['amount_time']>0) -> su dung cho gioi han gio choi
	if($_GET['a']==$array[3])
	{	
			$count=0;
			/* chuc nang nay han che xay mo 2 cua so hoac cac trinh duyet khac nhau */
			foreach($wg_status as $k =>$v)
			{
				if($v->type==3)
				{
					$count=1;
					for($i=1;$i<38;$i++)
					{
						setcookie('UpdateBuilding'.$i.'','',time()-100000, "/", "", 0);
					}
					break;
				}
			}
			/* ----------------------------------------------------------------------- */
			if($count==0 && isset($_COOKIE["UpdateBuilding".$_GET['a'].""]))
			{
				$object_id=$array[0];
				$village_id=$village;
				$time_begin=laythoigian(time());
				$time_end=laythoigian(time()+$array[1]);
				$cost_time=$array[1];
				$level=$array[2];
				$Lumber1=$wg_village->rs1;
				$Clay1=$wg_village->rs2;
				$Iron1=$wg_village->rs3;
				$Crop1=$wg_village->rs4;
				if($array[4] >$Lumber1 || $array[5] >$Clay1 || $array[6] >$Iron1 || $array[7] >$Crop1)
				{
					
				}
				else
				{
					$wg_village->rs1=$wg_village->rs1-$array[4];
					$wg_village->rs2=$wg_village->rs2-$array[5];
					$wg_village->rs3=$wg_village->rs3-$array[6];
					$wg_village->rs4=$wg_village->rs4-$array[7];
					/*--------------------------------------------------------------*/
					// insert du lieu vao bang wg_status
					$sql="INSERT INTO `wg_status` (`object_id`, `village_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`,`level`) VALUES ($object_id, $village_id, 3,'$time_begin','$time_end','$cost_time',0,".$user['id'].",$level);";
					$db->setQuery($sql);
					$db->query();
					for($i=1;$i<38;$i++)
					{
						setcookie('UpdateBuilding'.$i.'','',time()-100000, "/", "", 0);
					}
				}
				$level='';
			}
	}
}
/* Huy bo lenh nang cap */
if(!isset($_SESSION['password_viewuser']) && !empty($_GET['cancel']) && is_numeric($_GET['cancel']))
{
	foreach($wg_status as $sttBuild =>$objValue)
	{
		if($_GET['cancel'] == $objValue->id)
		{
			DeleteStatus($_GET['cancel'],$village,$objValue->type,$objValue->object_id);
			break;
		}
	}
}

$wg_buildings=getBuildings($village);
getSumCapacity($wg_village, $wg_buildings);

UpdateRS($wg_village, $wg_buildings, time());
updateTrainTroopStatus($wg_village, time());

/*----------------------------------------------------------------------------------------------------------------------*/
if($wg_village->name=="NewName")
{
	$parse['Name Village']=$lang['NewName'];
}
else
{
	$parse['Name Village']=$wg_village->name;
}
// xuat thong tin update va xay dung tai nguyen va cong trinh
$level_up_status=ShowBuild($village,$wg_status);
$parse["building_level_up_status"]=$level_up_status['building_level_up_status'];
$parse['Speed_Hour_Crop']=$wg_village->speedIncreaseRS4Real;
$parse['Speed_Hour_Lumber']=$wg_village->speedIncreaseRS1;
$parse['Speed_Hour_Clay']=$wg_village->speedIncreaseRS2;
$parse['Speed_Hour_Iron']=$wg_village->speedIncreaseRS3;
$wg_village->kind_id;
//truy van lay hinh nen village
foreach($wg_village_kinds as $sttBuild =>$objValue)
{
	if($wg_village->kind_id == $objValue['id'])
	{
		$image_kind=$objValue['image'];
		break;
	}			
}
$parse['id village']=$image_kind;
if($wg_buildings)
{
	$i=1;
	foreach ($wg_buildings as $ptu)
	{
		if($ptu->index<19)
		{
			if($ptu->level)
			{
				$parse['ptu_level']=$ptu->level;
				$parse['ptu_index']=$ptu->index;
				$level.=parsetemplate(gettemplate('village1_images'),$parse);
			}
			$parse['build_'.$i.'']='build.php?id='.$i.'';
			$parse['title_'.$i.'']=''.$lang[$ptu->name].' '.$lang['level'].' '.$ptu->level.'';
			$parse['level_'.$i.'']=$ptu->level;
			$i++;
		}
		if($i==19){break;}					
	}
}
$parse['images']=$level;
$parse['list_troop']=showTroopInVillage();
$parse['troop_movement']=GetTroopMoveStatus($village, $level_up_status['i']);
$page = parsetemplate(gettemplate('village1'), $parse);
display($page,$lang['Village']);
ob_end_flush();
?>

