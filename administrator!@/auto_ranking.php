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

	$sql="SELECT id,population FROM wg_users ORDER BY population DESC";//giam dan
	$db->setQuery($sql);
	$list_population=$db->loadObjectList();
	$rank=1;
	foreach($list_population as $result3)
	{
		$sql="UPDATE  wg_users SET rank=".$rank." WHERE id=".$result3->id."";
		$db->setQuery($sql);
		$db->query();
		$rank++;	
	}
	echo '<br/>Rank users '.$rank;
	
	$sql="SELECT id FROM wg_villages ORDER BY workers DESC";//giam dan
	$db->setQuery($sql);
	$list_population=$db->loadObjectList();
	$rank=1;
	foreach($list_population as $result)
	{
		$sql="UPDATE  wg_villages SET rank=".$rank." WHERE id=".$result->id."";
		$db->setQuery($sql);
		$db->query();
		$rank++;	
	}
	echo '<br/>Rank users '.$rank;

ob_end_flush();

?>

