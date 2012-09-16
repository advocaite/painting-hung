<?php
ob_start(); 
$ok = $_POST['merry_christmas'];

if($ok=='1'){
	$sql = "UPDATE `wg_users` SET `anounment` = '' WHERE `wg_users`.`id` =".$user['id'];
	$db->setQuery($sql);
	$db->query($sql);
	return true;
}else{	
	$parse = array();
	$page = parsetemplate(gettemplate('anounments/merry_christmas'),$parse);	
	display2($page,$lang['Title']);	
	return false;
}
ob_end_flush();
?>