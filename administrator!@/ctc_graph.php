<?php 
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
$village_reg = array();
$village_zone = array();

////////////procedure
if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']!=5){ header("Location: login.php"); }

$id = $_GET['id'] ? $_GET['id'] : 1;

for($i=1; $i<=5; $i++){
	$parse['mn_class_'.$i]	= $i==$id ? 'class="ctc_selected"' : '';
}

$ds = getGraph_admin_ctc($id);

if($_POST){
	
	for($i=1; $i<=7; $i++){
		for($j=1; $j<=7; $j++){
	
			if(is_numeric($_POST['s_'.$i.'_'.$j]) && $ds[$i][$j]['s'] != $_POST['s_'.$i.'_'.$j]){
				changeS_ctc($ds[$i][$j]['id'], $_POST['s_'.$i.'_'.$j]);
			}
			
			$parse['s_'.$i.'_'.$j] = $_POST['s_'.$i.'_'.$j];
		}
	}
}else{	
	for($i=1; $i<=7; $i++){
		for($j=1; $j<=7; $j++){
			$parse['s_'.$i.'_'.$j] = $ds[$i][$j]['s'];
		}
	}
}

$parse['message']	= '';

$page = parsetemplate(gettemplate('admin/ctc_graph_body'), $parse);
displayAdmin($page,$lang['Registration List']);


	/**
 * @author Le Van Tu
 * @des lay thong tin do thi duong di cua mot chien truong
 */
function getGraph_admin_ctc($ct_id){
	global $db;
	
	$rs = null;
	
	$sql = "SELECT wg_ctc_graph.* FROM wg_ctc_graph	WHERE wg_ctc_graph.ct_id = $ct_id GROUP BY wg_ctc_graph.id";
	$db->setQuery($sql);
	$ds = $db->loadObjectList();
	
	if($ds){
		foreach($ds as $d){
			$rs[$d->p_begin][$d->p_end]['s']	= $d->s;
			$rs[$d->p_begin][$d->p_end]['id'] 	= $d->id;
		}
	}
		
	return $rs;
} 

/**
 * @author Le Van Tu
 * @des Thay doi do dai quan duong di giua hai diem trong chien truong
 */
function changeS_ctc($id, $s){
	global $db;
	$sql = "UPDATE wg_ctc_graph SET s = $s WHERE id=$id";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError2($sql);
	}
}
?>