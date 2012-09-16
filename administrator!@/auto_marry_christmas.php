<?php 
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/func_security.php');
if ($_SERVER ['REMOTE_ADDR'] != '127.0.0.1') {die('hacking');}

	$sql="UPDATE  wg_users SET anounment='merry_christmas' WHERE anounment=''";
	$db->setQuery($sql);
	if($db->query()){
		echo 'Tao event giang sinh.';
	}else{
		echo 'loi event giang sinh.'.$sql;
	}

ob_end_flush();

?>

