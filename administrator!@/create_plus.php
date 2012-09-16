<?php
ob_start ();
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include ($ugamela_root_path . 'includes/common.' . $phpEx);
function saveFile($content)
{
	$f = fopen('../includes/wg_config_plus.php',"w") or exit("Khong the mo file!");
	fputs($f,$content);
	fclose($f);
	return true;
}

global $db;
$sql = "SELECT  * FROM wg_config_plus";
$db->setQuery($sql);
$elements =NULL;
$elements = $db->loadObjectList();
$char='$';
$content.='<?php
if (!defined("INSIDE")){die("Hacking attempt");}';
$content.="".$char."wg_config_plus=array();";
if($elements)
{	
	foreach ($elements as $ptu)
	{	
		$content.="".$char."wg_config_plus['".$ptu->name."_duration']=".$ptu->duration.";";	
		$content.="".$char."wg_config_plus['".$ptu->name."_asu']=".$ptu->asu.";";				
	}
}
$content.='?>';
if(saveFile($content))
{
	echo '<strong>Ghi noi dung moi vao file thanh cong</strong>';
}
else
{
	echo '<strong>Gap truc trac trong qua trinh ghi noi dung moi ra file</strong>';
}
?>