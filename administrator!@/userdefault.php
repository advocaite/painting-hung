<?php

/**
 * @author vantu
 * @copyright 2008
 * @des file nay chua cac ham tao cac user mac dinh
 */

define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_active.php');
include($ugamela_root_path . 'includes/function_troop.php');
include($ugamela_root_path . 'includes/func_build.php');
include("func_update_NPC.php");

if(!check_user()){ header("Location: login.php"); }
global $db,$game_config;
if($user['authlevel'] <5)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('user_default');
	global $lang;	
	$parse = $lang;
    
    $parse['error_message']='';
    
	if(isset($_POST['default']))
	{
		createUser("Admin",1, 0, 0);
		createUser("GameMaster1",2, 0, 1);
		createUser("GameMaster2",3, 1, 0);
		createUser("GameMaster3",1, 0, -1);
		createUser("GameMaster4",2, -1, 0);		
		$parse['error_message']=$lang['Successful'];
	}
	elseif(isset($_POST['custom']))
	{
		$acc_name = trim($_POST['txt_name']);
        $nation = intval($_POST['txt_nation']);
        $x = intval($_POST['txt_x']);
        $y = intval($_POST['txt_y']);
        $level = intval($_POST['txt_level']);
        //tao thanh NPC khong phai la thu do
        $NPC = true;
        createUser($acc_name,$nation, $x, $y, $NPC);
        //Cap nhat linh cho thanh NPC vua tao theo Level
        $villa_id = getVillageDefault($x, $y);
        update_troop_NPC($villa_id, $nation, $level);
        //cap nhat troop keep
        $arrTroop = getArrayOfTroops();
        $totalTroopKeep = getTroopKeep($villa_id,$arrTroop);
        updateTroopKeep($villa_id,$totalTroopKeep);
        
        //Cap nhat cong trinh cho thanh NPC theo Level
        update_Building_NPC($villa_id, $level);
        //cap nhat dan so
        include_once("includes/function_badlist.php");
        returnWorkersLogin(getUserIdByVillageId($villa_id));
     }	
	$page = parsetemplate(gettemplate('admin/user_default'), $parse);
	displayAdmin($page,"User default");
}
/**
 * @author Le Van Tu
 * @edit by Diep Luan
 * @des Tao mot user theo toa do (co phan biet la tai khoan NPC hay tai khoan default)
 * @param $x, $y toa do lang cua user
 * @return void
 */
function createUser($name,$nation_id, $x, $y, $check_NPC = false)
{
	global $db,$game_config;
	$sql="SELECT id FROM wg_users WHERE username='".$name."'";
	$db->setQuery($sql);
	if($db->loadResult()=='')
	{
		$village_id=getVillageDefault($x, $y);	
		$md5newpass = md5($name);
        //neu thanh nay la NPC thi tinh trang bi chien tranh duoc thiet lap
        $iswar = $check_NPC?1:0;
        
		$sql = "INSERT INTO wg_users ";
		$sql .= " (username,nation_id,active_time,iswar) ";
		$sql .= " VALUES ('$name',$nation_id,'".date("Y-m-d H:i:s")."',".$iswar.")";
		$db->setQuery($sql);
		if($db->query()){
			$user_id=$db->insertid();
		}else{
			die($sql);
		}		
		changeKindId($x, $y);
		setRegVillageStatus($village_id);	
		insertPlus($user_id);
		insertWg_top10($user_id);
        if($check_NPC)
        {//khoi tao user NPC khong co thu do
            KhoiTaoUser($user_id, 0, date("Y-m-d H:i:s"), 1);    
        }
        else
        {
		    KhoiTaoUser($user_id, $village_id, date("Y-m-d H:i:s"), 1);	
        }
		KhoiTaoVillage($village_id, $user_id, $name, $nation_id, $name);	
		InsertDataBuilding_New($village_id, $user_id, 3);	
		KhoiTaoTroopResearch($village_id, $nation_id);
		UpdateRegVillageList($village_id);
		Insert_Wg_Profiles($name);
	}
}

function Insert_Wg_Profiles($username)
{
	global $db;
	$sql = "INSERT INTO `wg_profiles` (`username`) VALUES ('".$username."')";
	$db->setQuery($sql);
	return $db->query();		
}
/**
 * @author Le Van Tu
 * @des Doi kind_id = 3 cho mot lang trong bang wg_villages_map
 * @param $x, $y toa do lang
 * @return true or flase
 */
function changeKindId($x, $y){
	global $db;
	$sql="UPDATE wg_villages_map SET kind_id=3 WHERE x=$x AND y=$y";
	$db->setQuery($sql);
	return $db->query();
}


/**
 * @author Le Van Tu
 * @des Doi registed=1 cho mot recode trong bang wg_registration_village_list
 * @param $village_id id cua lang
 * @return true or flase
 */
function setRegVillageStatus($village_id){
	global $db;
	$sql="UPDATE wg_registration_village_list SET registed=1 WHERE village_id=$village_id";
	$db->setQuery($sql);
	return $db->query();	
}

/**
 * @author Le Van Tu
 * @des Lay id cua mot lang theo toa do
 * @param $x, $y toa do lang
 * @return true or flase
 */
function getVillageDefault($x, $y){
	global $db;
	$sql="SELECT id FROM wg_villages_map WHERE x=$x AND y=$y";
	$db->setQuery($sql);
	return $db->loadResult();
}
?>