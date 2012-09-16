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
require_once($ugamela_root_path . 'includes/usersOnline.class.php');
checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
$visitors_online = new usersOnline();
global $db,$village,$game_config,$wg_village_kinds,$wg_village,$wg_buildings,$user,$wg_status,$timeAgain;
$timeAgain=$visitors_online->deltaTimeSubtract;
//checkWorldFinished();	 -> chi su dung khi co ky dai level >90 tro len		
doAnounment();
includeLang('village1');
includeLang('lang_Wap');
doAllStatus();
$parse = $lang;
$_SESSION['url']='village1.php';
/*
* @Author: duc hien
* @Des: hien thi thong tin linh cua lang
* @param: $village :  id cua lang
* @return: template thong tin linh
*/
// kiem tra tinh bao mat cho lang -> da loai bo loi SQL injection (is_numeric & $db->getEscaped)

if(!empty($_GET['vid']) && is_numeric($_GET['vid']))
{
	$get_id=$_GET['vid'];
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE id=".$get_id." AND user_id=".$user["id"]." LIMIT 1";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	if($count==1)
	{
		$_SESSION['villa_id_cookie']=$get_id;
		$village=$get_id;
	}else{
		$village=$_SESSION['villa_id_cookie'];
	}
}else{
	if(isset($_SESSION['villa_id_cookie']))
	{
		$village=$_SESSION['villa_id_cookie'];
	}else{
		$village=$user['villages_id'];
	}
}

$wg_village=NULL;
$wg_status=NULL;
$wg_village=getVillage($village);
//updateTroopKeep($village, getTroopKeep($village, getArrayOfTroops()));
$wg_status=getStatusProcessing();

if(!empty($_GET['a']) && is_numeric($_GET['a']) && isset($_SESSION['UpdateBuilding'.$_GET['a']]) )
{
	/*$_SESSION['UpdateBuilding'.$_GET['a']]=$id,$Time_Cost,$level,$type_id,$Lumber,$Clay,$Iron,$Crop,$name;*/
	$array=array();
	$get_a=$_GET['a'];
	$array=split(",",$_SESSION['UpdateBuilding'.$get_a]);
	
	if($get_a == $array[3])
	{	
		/* chuc nang nay han che xay mo 2 cua so hoac cac trinh duyet khac nhau */
		$count=checkUpdateVillage($wg_status,$array[0],$user["id"],'village1');		
		if($count==0)
		{
			/* tranh TH user click chuot nhieu lan ->insert nhieu record vao bang wg_status..voi cost_time,object_id  trung nhau */
			$sql="SELECT id FROM wg_status WHERE object_id=".$array[0]." 
			AND village_id=".$village." AND type=3 AND cost_time=".$array[1]."  AND status=0";
			$db->setQuery($sql);	
			$query=NULL;
			$db->loadObject($query);
			if($query)
			{
				$count=1; 
			}
		}
		if($count==0 && isset($_SESSION['UpdateBuilding'.$get_a]))
		{
			$object_id=$array[0];
			$time_begin=date("Y-m-d H:i:s",time());
			$time_end=date("Y-m-d H:i:s",time()+$array[1]);
			$cost_time=$array[1];
			if(isset($_SESSION['checkPlusForUser']))
			{
				$sql="SELECT time_end FROM wg_status WHERE 	village_id=".$village." 
				AND type=3 AND status=0 ORDER BY id DESC LIMIT 1";
				$db->setQuery($sql);	
				$temp=$db->loadResult();
				if($temp)	
				{
					$time_begin=$temp;
					$time_end=date("Y-m-d H:i:s",strtotime($temp)+$array[1]);
					unset($_SESSION['checkPlusForUser']);
				}
			}
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
				$sql="INSERT INTO `wg_status` (`object_id`, `village_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`,`level`) VALUES ($object_id, $village, 3,'$time_begin','$time_end','$cost_time',0,".$user['id'].",$level);";
				$db->setQuery($sql);
				if(!$db->query())
				{
					globalError2($sql);
				}					
				for($i=1;$i<38;$i++)
				{
					unset($_SESSION['UpdateBuilding'.$i]);
				}					
			}
			$level='';
		}
	}
}
/* Huy bo lenh nang cap */
if(!empty($_GET['cancel']) && is_numeric($_GET['cancel']))
{
	if($wg_status)
	{
		$get_cancel=$_GET['cancel'];
		foreach($wg_status as $sttBuild =>$objValue)
		{
			if($get_cancel == $objValue->id)
			{
				DeleteStatus($get_cancel,$village,$objValue->type,$objValue->object_id);
				break;
			}
		}
	}
}
$wg_buildings=NULL;
$wg_buildings=getBuildings($village);
getSumCapacity($wg_village, $wg_buildings);
$time_now=time();
UpdateRS($wg_village, $wg_buildings,$time_now);
updateTrainTroopStatus($wg_village,$time_now);
$parse['resource_info_value']= GetRSStatus();
$parse['top_menu'] = parsetemplate(gettemplate('top_menu'), $parse);
$parse['Name_Village']=$wg_village->name;
if($wg_village->name=="NewName" || $wg_village->name=="")
{
	$parse['Name_Village']=$lang['NewName'];
	
}
$parse['capital']='';
if($user['villages_id']==$village)
{
	$parse['capital']='<span class="c">['.$lang['capital'].']</span>';
}
// xuat thong tin update va xay dung tai nguyen va cong trinh
$level_up_status=ShowBuild($village,$wg_status);
$parse["building_level_up_status"]=$level_up_status['building_level_up_status'];
$parse['Speed_Hour_Crop']=$wg_village->speedIncreaseRS4Real;
$parse['Speed_Hour_Lumber']=$wg_village->speedIncreaseRS1;
$parse['Speed_Hour_Clay']=$wg_village->speedIncreaseRS2;
$parse['Speed_Hour_Iron']=$wg_village->speedIncreaseRS3;

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
	$arrUplevel = array();
	$arrUplevel[1]['level']=30;
	$arrUplevel[2]['level']=30;
	$arrUplevel[3]['level']=30;
	$arrUplevel[4]['level']=30;
	foreach ($wg_buildings as $ptu)
	{
		if($ptu->index<19)
		{					
			$arrUplevel[$ptu->type_id]['level']=min($arrUplevel[$ptu->type_id]['level'],$ptu->level);
			if($arrUplevel[$ptu->type_id]['level'] == $ptu->level){				
				$arrUplevel[$ptu->type_id]['name'] = $lang[$ptu->name];
				$arrUplevel[$ptu->type_id]['id'] = $i;		
			}		
			$i++;
		}
		if($i==19){break;}					
	}
	foreach ($arrUplevel as $ptu){
		$level.='<p><a href="build.php?id='.$ptu['id'].'">';
		$level.=$ptu['name'].'</a>';	
		$level.=' ('/*.$lang['level'].''*/.$ptu['level'].')</p>';
	}
}
$parse['images']=$level;
$parse['list_troop']=showTroopInVillage();
$parse['troop_movement']=GetTroopMoveStatus($village, $level_up_status['i']);
$parse['village_id'] = $wg_village->id;

$page = parsetemplate(gettemplate('village1'), $parse);
display($page,$lang['Village']);
ob_end_flush();
?>

