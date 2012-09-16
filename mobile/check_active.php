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
	$char='false';
	$sql="SELECT actived FROM wg_users WHERE username='".$db->getEscaped($_GET['username'])."'";
	$db->setQuery($sql);
	$wg_users=NULL;
	$db->loadObject($wg_users);
	if($wg_users)
	{
		$char='true';			
	}	
}
header('Content-type: text/xml');
header('Pragma: public');        
header('Cache-control: private');
header('Expires: -1');
echo '<?xml version="1.0" encoding="utf-8" ?>
<check>
	<value>'.$char.'</value>
</check>
';
ob_end_flush();
?>

