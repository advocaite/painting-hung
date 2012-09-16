<?php 
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
function saveFile($content)
{
	$f = fopen('../language/vi/event_server.mo',"w") or exit("Khong the mo file!");
	fputs($f,$content);
	fclose($f);
	return true;
}
if(!check_user()){ header("Location: login.php"); }
//START: danh sach plus
if($_POST)
{
$char='$';
$content.='<?php
if (!defined("INSIDE")){die("Hacking attempt");}
'.$char.'lang[\'event_server\']=\''.$_POST['event_server'].'\';
'.$char.'lang[\'begin\']=\''.strtotime($_POST['begin']).'\';
'.$char.'lang[\'end\']=\''.strtotime($_POST['end']).'\';
?>';
saveFile($content);
}
includeLang('admin_config');
includeLang('event_server');
global $db,$lang;	
$parse = $lang;
$parse['begin'] =date("Y-m-d H:i:s",$lang['begin']);
$parse['end'] = date("Y-m-d H:i:s",$lang['end']);
$sql = "SELECT * FROM wg_config";
$db->setQuery($sql);
$wg_config = NULL;
$wg_config = $db->loadObjectList();

foreach($wg_config as $row)
{
	$parse['config_name'] = $row->config_name;
	$parse['config_value'] = $row->config_value;
	$list .=parsetemplate(gettemplate('/admin/wg_config_row'),$parse);
}
$parse['view_list'] = $list;
$page = parsetemplate(gettemplate('/admin/wg_config'), $parse);
displayAdmin($page,'');
?>