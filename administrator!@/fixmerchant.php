<?php
ob_start(); 
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
if($user['authlevel'] <1)
{
	header("Location:index.php");
	exit();
}
else
{
	global $db;	
	$sql="SELECT id, name, merchant_underaway, nation_id FROM wg_villages";
	$db->setQuery($sql);
	$villageList=$db->loadObjectList();
	if($villageList){
		foreach($villageList as $village){
			$marketLevel=GetBuildingLevel($village->id, 13);
			if(!$marketLevel){
				$marketLevel=0;
			}
			$sum=getSumMerchantUnderaway($village->id);
			$sum=$sum<=$marketLevel?$sum:$marketLevel;
			echo $village->name.": ".$village->merchant_underaway." -> $sum <br>";
			if($village->merchant_underaway!=$sum){
				updateMerchant($village->id, $sum);
			}			
		}
	}
}
function getSumMerchantUnderaway($village_id){
	global $db;
	$result=0;
	//Lay so thuong nhan dang rao ban:
	$sql="SELECT
				wg_resource_orders.merchants
			FROM
				wg_resource_orders
			WHERE
				wg_resource_orders.village_id =  '$village_id'";
	$db->setQuery($sql);
	$orderList=$db->loadObjectList();
	if($orderList){
		foreach($orderList as $order){
			$result+=$order->merchants;
		}
	}
	
	//Lay so thuong nhan dang di giao dich:
	$sql="SELECT
				wg_resource_sends.rs4,
				wg_resource_sends.rs3,
				wg_resource_sends.rs2,
				wg_resource_sends.rs1
			FROM
				wg_resource_sends ,
				wg_status
			WHERE
				wg_resource_sends.id =  wg_status.object_id AND
				wg_status.`status` =  '0' AND
				(wg_status.`type` =  '6' OR wg_status.`type` =  '22' OR wg_status.`type` =  '23') AND
				wg_resource_sends.village_id_from =  '$village_id'				
			GROUP BY
				wg_status.id";
	$db->setQuery($sql);
	$orderList=$db->loadObjectList();
	if($orderList){
		foreach($orderList as $order){
			$result+=GetMerchantTransport($village_id, $order->rs1 + $order->rs2 + $order->rs3 + $order->rs4);
		}
	}
	
	return $result;
}

function updateMerchant($village_id, $sum){
	global $db;
	$sql="UPDATE wg_villages SET merchant_underaway=$sum WHERE id=$village_id";
	$db->setQuery($sql);
	if(!$db->query()){
		die("loi");
	}
}
?>