<?php
ob_start();
define('INSIDE', true);
date_default_timezone_set("Asia/Saigon");
$ugamela_root_path = './';
$image_path="images/village/";
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');

include($ugamela_root_path . 'includes/func_building.php');
include($ugamela_root_path . 'includes/function_status.php');
include($ugamela_root_path . 'includes/function_resource.php');
include($ugamela_root_path . 'includes/function_troop.php');
includeLang('train_trop');

//Tao linh trong Barracks. doi voi Trai ngua va xuong chi can doi building_type_name
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$village_id=1;
global $db;
$parse=$lang;
$sql="SELECT * FROM wg_villages WHERE id = $village_id";
$db->setQuery($sql);
$village=null;
$db->loadObject($village);
if($village){
	if($_POST){
		$parse["training"]=Training($_POST, $village->id, "Barracks");
	}
	$parse['train_troop_status']=TrainTroopStatus($village->id, "Barracks");
	$parse["list_troops"]=ShowListTroops($village->id, "Barracks");
}
else{
}
//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
$page = parsetemplate(gettemplate('train_troop_body'), $parse);
display($page,$lang['Train Trop']);
?>