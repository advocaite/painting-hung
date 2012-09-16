<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php');
require_once($ugamela_root_path . 'includes/common.'.$phpEx);

require_once($ugamela_root_path . 'includes/function_troop.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_ctc.'.$phpEx);

checkRequestTime();

if(!check_user()){ header("Location: ".$ugamela_root_path."login.php"); }

global $db, $user;
includeLang('ctc');

//kiem tra xem mo cua cong thanh chien chua:
if(checkOpen_ctc()){
	if(!checkTheBai_ctc($user['id'])){header("Location: ctc.php");}
}

$parse 	= $lang;
$id = getRequest("id", 1);
if($id && is_numeric($id)){
	$sql = "SELECT * FROM wg_ctc_diem_tap_ket WHERE id = $id";
	$db->setQuery($sql);
	$db->loadObject($dtk);
	if($dtk){
		$dtks = getOtherTC_ctc($dtk->ct_id);
		if($dtks){
			$i=1;
			foreach($dtks as $tc){
				if($tc->id == $id){
					$parse['class_'.$i] = 'class="focus"';
				}else{
					$parse['class_'.$i] = "";
				}
				$parse['tc_id_'.$i] = $tc->id;
				$i++;
			}
		}
	}
	if(is_numeric($_REQUEST['p'])){
		$p = $_REQUEST['p'];
	}else{
		$p = 0;
	}
	
	$sql = "SELECT * FROM wg_ctc_report WHERE dtk_id='$id' ORDER BY wg_ctc_report.id DESC";
	$db->setQuery($sql);
	$rps = $db->loadObjectList();
	if($rps){
		if($p>0){
			$parse['c_f'] = "";
			$parse['c_p'] = "";
			$parse['p_p'] = $p-1;
		}else{
			$parse['c_f'] = "class=c";
			$parse['c_p'] = "class=c";
			$parse['p_p'] = "";
		}
		
		if($p<count($rps)-1){
			$parse['c_l'] = "";
			$parse['c_n'] = "";
			$parse['p_l'] = count($rps)-1;
			$parse['p_n'] = $p+1;
		}else{
			$parse['c_l'] = "class=c";
			$parse['c_n'] = "class=c";
			$parse['p_l'] = "";
			$parse['p_n'] = "";
		}
		
		$parse['id'] = $id;
		$parse['content'] = $rps[$p]->content;
		echo parsetemplate(gettemplate("ctc/ctc_report_body"), $parse);
	}else{
		$parse['content'] = $lang['chua_co_cb'];
		echo parsetemplate(gettemplate("ctc/ctc_report_body"), $parse);
	}	
}

?>