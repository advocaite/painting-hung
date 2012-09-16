<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php'); 
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_attack.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_troop.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_combat.'.$phpEx);

includeLang('combat');
includeLang('building_name');
$parse = $lang;
if($_POST){
	$atl = getATL_cb();
	$dtl = getDTL_cb();
	$ah	 = getAH_cb();
	$dh	 = getDH_cb();
	
	attack_cb($atl, $dtl, $ah, $dh);
	
	//hien thi bang attacker
	$parse += returnForm_cb($atl, $dtl, $ah, $dh[1]);
	
}else{
	$parse += createForm_cb();
}

$page = parsetemplate(gettemplate('combat_body'), $parse);
echo $page;

ob_end_flush();
?>