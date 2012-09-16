<?php 
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php'); 
//require_once($ugamela_root_path . 'includes/common.'.$phpEx);

global $db;
if(isset($_GET['username']))
{
	$char='';
	$sql="SELECT username FROM wg_users WHERE username='".$db->getEscaped($_GET['username'])."'";
	$db->setQuery($sql);
	$wg_users=NULL;
	$char=$db->loadResult();	
}
header('Content-type: text/xml');
header('Pragma: public');        
header('Cache-control: private');
header('Expires: -1');
echo '<?xml version="1.0" encoding="utf-8" ?>
<value>'.$char.'</value>';
ob_end_flush();
?>

