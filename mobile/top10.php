<?php
/*
	Plugin Name: top10.php
	Plugin URI: http://asuwa.net/top10.php
	Description: 
	+ Hien thi danh sach 10 thanh vien xuat sac nhat game cho trang chu 
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.php'); 
include($ugamela_root_path . 'includes/common.'.$phpEx);

global $db;
/*body{ background-color:#F7E3AD} thay doi body  body{margin-left:100px}
body {background-image:url(/homepage/templates/asuwa_template/images/hover.gif);}*/
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
echo "<style type=\"text/css\">
	body{ background-color:transparent}	
</style>";
$sql = "SELECT username FROM wg_users ORDER BY population DESC LIMIT 0,10";
$db->setQuery($sql);
$top10List = null;
$top10List = $db->loadObjectList();
if($top10List){
	$i = 1;
	foreach ($top10List as $row)
	{  
		echo "<li style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; list-style-type:none; line-height:18px\">".$i.". ".$row->username."</li>";
		$i++;
	}
}
ob_end_flush();
?>
