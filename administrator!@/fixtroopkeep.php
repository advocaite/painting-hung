<?php
define('INSIDE',true);
$ugamela_root_path = '../';
require_once($ugamela_root_path . 'extension.inc');
require_once('includes/db_connect.php'); 
require_once('includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_resource.php');
require_once($ugamela_root_path . 'includes/func_build.php');
require_once($ugamela_root_path . 'includes/func_convert.php');
require_once($ugamela_root_path . 'includes/func_security.php');
require_once($ugamela_root_path . 'includes/function_trade.php');
require_once($ugamela_root_path . 'includes/function_status.php');
require_once($ugamela_root_path . 'includes/function_attack.php');
require_once($ugamela_root_path . 'includes/function_troop.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1){ header("Location: login.php"); }

global $db;
set_time_limit(10000);

if(isset($_GET['id']))
{
	if(!is_numeric($_GET['id']))
	{
		$sql="SELECT id, name, troop_keep FROM wg_villages WHERE name='".$_GET['id']."'";
	}
	else
	{
		$sql="SELECT id, name, troop_keep FROM wg_villages WHERE id=".$_GET['id'];
	}
}
else
{
	$sql="SELECT id, name, troop_keep FROM wg_villages";
}
$db->setQuery($sql);
$villageList=$db->loadObjectList();
if($villageList)
{
	$arrayTroop=getArrayOfTroops();
	foreach($villageList as $village)
	{
		$troopKeep=getTroopKeep($village->id,$arrayTroop);
		if($village->troop_keep != $troopKeep)
		{ 
			echo "<strong>".$village->name.": <span style=\"color:#FF00FF;\">".$village->troop_keep."</span> -> <span style=\"color:#FF0000;\">".$troopKeep."</span></strong><br>";
			updateTroopKeep($village->id, $troopKeep);	
		}
		else
		{
			echo "<strong>".$village->name.": ".$village->troop_keep." -> ".$troopKeep." </strong><br>";
		}					
	}
}

?>