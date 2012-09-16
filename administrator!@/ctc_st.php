<?php 
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_ctc.php');

//procedure
if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']!=5){ header("Location: login.php"); }

includeLang('ctc');

$id = $_GET['id'] ? $_GET['id'] : 1;

for($i=1; $i<=5; $i++){
	$parse['mn_class_'.$i]	= $i==$id ? 'class="ctc_selected"' : '';
}

$sts = getStatus_ctc($id);

$parse['message']	= '';


if($sts){
	$s = $sts[0];
	$ct = $s->cost_time;		
	if($s->order_ == 1){//status dau tien chua duoc xu ly -> co the thay doi thoi gian
		if($_POST){
			//xoa status cu:
			deleteStatus_ctc($id);
			
			$st = date("Y-m-d H:i:s", strtotime($_POST['st']));
			$parse['st']	= $_POST['st'];
			
			$ct = is_numeric($_POST['ct']) ? $_POST['ct'] : 6*60;
			
			for($i=1; $i<=10; $i++){
				insertStatus_ctc(0, $id, $st, $st, $ct, 27, $i);
				$st = date("Y-m-d H:i:s", strtotime($st) + $ct);
			}
		}else{
			$parse['st'] = date("H:i:s d-m-Y", strtotime($s->time_begin));
			$parse['ok_disabled'] = '';
		}				
	}else{
		$parse['st'] = date("H:i:s d-m-Y", strtotime($s->time_begin)-(($s->order_ - 1)*$ct));
		$parse['message']	= $lang['ctc_started_mess'];
		$parse['ok_disabled'] = 'disabled="disabled"';
	}
	$parse['ct']	= $ct;
	$parse['ok']	= 'Update';
}else{	
	if($_POST){	
		$st = date("Y-m-d H:i:s", strtotime($_POST['st']));
		$parse['st']	= $st;
		
		$ct = is_numeric($_POST['ct']) ? $_POST['ct'] : 6*60;
		
		//huy bo phe cua cac lien minh trong lan ctc truoc:
		if(!checkOpen_ctc()){
			destroyAliSide_ctc();
			destroyAllChuPhe_ctc();
		}		
		
		//
		
		for($i=1; $i<=10; $i++){
			insertStatus_ctc(0, $id, $st, $st, $ct, 27, $i);
			$st = date("Y-m-d H:i:s", strtotime($st) + $ct);
		}		
		
		$parse['ct']	= $ct;
		$parse['ok']	= 'Update';
	}else{
		$parse['st']	= "20:00:00 ".date("d-m-Y");
		$parse['ct']	= 6*60;
		$parse['ok']	= 'Insert';
	}	
}

$page = parsetemplate(gettemplate('admin/ctc_st_body'), $parse);
displayAdmin($page,$lang['Registration List']);

/**
 * @author Le Van Tu
 * @des kiem tra xem da chen status cong thanh chien cho mot chien truong hay chua
 */
function getStatus_ctc($ct_id){
	global $db;
	$sql = "SELECT * FROM wg_status WHERE wg_status.`status` =  '0' AND wg_status.`type` =  '27' AND wg_status.object_id =  '$ct_id' GROUP BY wg_status.order_ ORDER BY wg_status.order_ ASC";
	$db->setQuery($sql);
	return $db->loadObjectList();
} 

/**
 * them mot status.
 */
function insertStatus_ctc($village_id, $object_id, $time_begin, $time_end, $cost_time, $type, $order=0){
	global $db;
	$sql="INSERT INTO wg_status (`village_id`, `object_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`) VALUES ($village_id, $object_id, $type, '$time_begin', '$time_end', $cost_time, 0, $order)";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

/**
 * xoa status cau mot diem tap ket:
 */
function deleteStatus_ctc($object_id){
	global $db;
	$sql="DELETE FROM wg_status WHERE `object_id`=$object_id AND `type`=27";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}

?>