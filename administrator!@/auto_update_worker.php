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

	$sql="SELECT id,workers FROM wg_villages";
	$db->setQuery($sql);
	$list_population=$db->loadObjectList();
	$count=1;
	foreach($list_population as $result)
	{
		$sql="UPDATE  wg_villages_map SET workers=".$result->workers." WHERE id=".$result->id."";
		$db->setQuery($sql);
		$db->query();
		$count++;	
	}
	echo '<br/>Count villages '.$count;

ob_end_flush();

?>

