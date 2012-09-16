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

global $db;

includeLang('ctc');

$parse 	= $lang;

//echo "<pre>"; print_r($user); die();

//Kiem tra user nay co lien minh hay khong:
if($user['alliance_id']){
	$phe = getAlliePhe_ctc($user['alliance_id']);
	if(!$phe){//linh minh nay chua dang ky vao phe nao
		if(checkMinhChu_ctc($user['id'])){//user nay la minh chu
			$page	= parsetemplate(gettemplate('ctc/ctc_reg_form'), $parse);
		}
		
		if($_GET){
			$ms = regAllie_ctc();
			echo $ms;die();
		}
	}

	echo $page;
}
ob_end_flush();
?>