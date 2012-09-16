<?php
/**
	Plugin Name: administrator
	Plugin URI: http://asuwa.net/administrator/auto_cpupdate.php
	Description: Admin update cp for wg_villages
	Version: 1.0.0
	Author: ManhHX
	Author URI: 
*/

define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include ($ugamela_root_path . 'includes/common.' . $phpEx);
include ($ugamela_root_path . 'includes/func_security.php');
if ($_SERVER ['REMOTE_ADDR'] != '127.0.0.1') {die('hacking');}
global $db;

$sql="SELECT id, cp, cpupdate_time FROM wg_villages";
$db->setQuery($sql);
$arrRes = $db->loadObjectList();
$pointTotal = 0;

$curr_date = date("Y-m-d H:i:s");
$curr_time = strtotime($curr_date);	
	
foreach($arrRes as $k =>$v)
{	
	$singlePoint = getCulturePointOfVillagePerDay($v->id);
	//$pointTotal = $pointTotal + $singlePoint;	
	$old_time = strtotime($v->cpupdate_time);				
	$time_distance = $curr_time - $old_time;
	
	$deltaCP = 0;
	$deltaCP = ceil(($time_distance*$singlePoint)/86400);
	
	$cpNew = 0;
	$cpNew = $v->cp + $deltaCP;	
	
	$updSql="UPDATE wg_villages SET cpupdate_time='$curr_date', cp= $cpNew WHERE id=$v->id";		
	$db->setQuery($updSql);		
	$db->query();
}

/**
 * Production of this village
 * @param $village:int
 * @return total:int
 * @access private
 */	  
function getCulturePointOfVillagePerDay($village)
{
	global $db;
	$sql="SELECT SUM(cp) AS total FROM wg_buildings WHERE vila_id=$village";
	$db->setQuery($sql);	
	$objRes = $db->loadObjectList();	
	return $objRes[0]->total;
}