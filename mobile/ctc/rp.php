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
	$parse['id'] = $id;
	echo getReport_ctc($id);
}
?>