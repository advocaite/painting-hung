<?php
ob_start();
define('INSIDE',true);
$ugamela_root_path = '../';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php');
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/func_security.'.$phpEx);

require_once($ugamela_root_path . 'includes/function_ctc.'.$phpEx);

checkRequestTime();
if(!check_user()){ header("Location: ".$ugamela_root_path."login.php"); }

includeLang('ctc');
global $game_config;
$parse 	= $lang;
$parse['message'] = "";

//kiem tra xem mo cong thanh chien chua:
if(checkOpen_ctc()){
	//Kiem tra xem da co luot danh nao chua:
	if(checkStarted_ctc()){
		$tb=checkTheBai_ctc($user['id']);
		if($tb){
			$parse['check_message'] = 0;
		}else{
			$parse['check_message'] = 1;
			$parse['message'] = $lang['ctc_card_error'];
		}
		
		//doi voi minh chu:
		if(!getAlliePhe_ctc($user['alliance_id']) && $parse['check_message'] ==0 && checkMinhChu_ctc($user['id'])){
			if($tb==3){
				$parse['check_reg'] = 1;
			}else{
				$parse['check_reg'] = 0;
				$parse['check_message'] = 1;
				$parse['message'] = $lang['ctc_card_error_ali'];
			}
				
		}else{
			$parse['check_reg'] = 0;
		}
	}else{
		if(!getAlliePhe_ctc($user['alliance_id'])){
			$parse['message'] = $lang['ctc_started'];
			$parse['check_message'] = 1;
		}			
		$parse['check_reg'] = 0;
	}	
}else{
	$parse['check_message'] = 0;
	$parse['check_reg'] = 0;
}


$page	= parsetemplate(gettemplate('ctc/ctc_body'), $parse);

display_ctc($page);

ob_end_flush();
?>