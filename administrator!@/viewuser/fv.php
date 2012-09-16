<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.php');
include($ugamela_root_path . 'includes/common.'.$phpEx);

include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/function_foundvillage.php');
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_troop.php');
include($ugamela_root_path . 'includes/function_resource.php');
include($ugamela_root_path . 'includes/nation_troop.php');


if(!check_user()){ header("Location: login.php"); }

$village_id=$_COOKIE['villa_id_cookie'];
$village_found_id=$_GET['vfi'];

$wg_village=getVillage($village_id);
$wg_buildings=getBuildings($village_id);

getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());

includelang("found_village");
$parse=$lang;

/*
*	Kiem tra lang con trong hay khong.
*/
$village_found=getVillageFound($village_found_id);

if($village_found->user_id!=0){
	header("Location: village1.php"); 
}

//Kiem tra du tho hay chua
if(!CheckSettlers()){ 
	header("Location: village1.php");
}

//Kiem tra so lang con toi da
if($sumChild=GetSumChildOfVillage($wg_village)>=2){
	header("Location: village1.php");
}

//Kiem tra RS va diem danh vong.
$error="";
//Diem danh vong.
if(!CheckCulturePoint($wg_village)){
	$parse['message']=$lang['thieu_cp'];
	$error.=parsetemplate(gettemplate("error"), $parse);
}

//Kiem tra RS.
if($wg_village->rs1<750 || $wg_village->rs2<750 || $wg_village->rs3<750 || $wg_village->rs4<750){
	$parse['message']=$lang['thieu_rs'];
	$error.=parsetemplate(gettemplate("error"), $parse);
}

$speed=GetSettlerSpeed();
$s=S($wg_village->x, $wg_village->y, $village_found->x, $village_found->y);
$duration=($s/$speed)*3600;

if($_POST && $error==""){
	//Tru rs:
	$wg_village->rs1-=750;
	$wg_village->rs2-=750;
	$wg_village->rs3-=750;
	$wg_village->rs4-=750;
	
	$troop_id=$nationTroopList[$wg_village->nation_id]['type_name10'];
	
	//tru tho trong lang:
	changeTroopVillage($wg_village->id, $troop_id, -5);
	
	//Them vao bang wg_attack
	$object_id=InsertAttack($wg_village->id, $village_found_id, 6);
	
	//Them danh sach linh:
	InsertAttackTroop($troop_id, $object_id, 5);
	
	//Them trang thai
	InsertStatus($wg_village->id, $object_id, date("Y-m-d H:i:s"), date("Y-m-d H:i:s", time()+$duration), $duration, 15);
	
	unsetVillageParam($wg_village);
	$db->updateObject("wg_villages", $wg_village, "id");
	header("Location: build.php?id=37");
}

$listTroop=GetListTroopVilla($wg_village);

for($i=0; $i<11; $i++){
	$parse['icon_'.($i+1)]=$listTroop[$i]->icon;
	$parse['title_'.($i+1)]=$lang[$listTroop[$i]->name];
}

$parse['x']=$village_found->x;
$parse['y']=$village_found->y;
$parse['x1']=$wg_village->x;
$parse['y1']=$wg_village->y;
$parse["duration"]=TimeToString($duration);
$parse['village_name']=$wg_village->name;

if(!$error){
	$parse['error_message']="";
	$page = parsetemplate(gettemplate('foundvillage_body'),$parse);
}else{
	//Khong du dieu kien de tao lang moi -> hien thi trang thong bao.
	$parse['error_message']=$error;
	$page = parsetemplate(gettemplate('foundvillage_body'),$parse);
}

display($page,$lang['Found New Village']);
ob_end_flush();
?>