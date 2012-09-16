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

if(!check_user()){ header("Location: login.php"); }

global $db,$village,$game_config,$wg_village_kinds,$wg_village,$wg_buildings,$user,$wg_status,$timeAgain;
doAnounment();
includeLang('lang_Wap');
doAllStatus();
$parse = $lang;

$sql = "SELECT  * FROM wg_villages WHERE user_id=".$user["id"];
$db->setQuery($sql);
$objVillages = $db->loadObjectList();
if(count($objVillages)==1){
	header("Location: village1.php?vid=".$objVillages[0]->id);
	exit(0);
}else{
	foreach($objVillages as $k=>$v){
		$parse["total_list"].='<p><a href="./village1.php?vid='.$v->id.'">'.$v->name.'</a></p>';
	}
	$page = parsetemplate(gettemplate('list_villages'), $parse);
	display1($page);
}
ob_end_flush();
?>

