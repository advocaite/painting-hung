<?php 
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php'); 

global $db;
$count=0;
$sql="SELECT COUNT(DISTINCT(id)) FROM wg_users";
$db->setQuery($sql);
$count=(int)$db->loadResult();
header('Content-type: text/xml');
header('Pragma: public');        
header('Cache-control: private');
header('Expires: -1');
echo '<?xml version="1.0" encoding="utf-8" ?>
<check>
	<value>'.$count.'</value>
</check>
';
ob_end_flush();
?>

