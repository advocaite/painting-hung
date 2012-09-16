<?php
/*
	Plugin Name: top10.php
	Plugin URI: http://th1.ingame.vn/truongson/top10.php
	Description: 
	+ Hien thi danh sach 10 thanh vien xuat sac nhat game cho trang chu
*/
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.php'); 
include($ugamela_root_path . 'includes/common.'.$phpEx);

global $db;

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
echo "<style type=\"text/css\">
	body{ background-color:transparent}	
</style>";

$stSQL ="SELECT username FROM wg_users WHERE username NOT LIKE 'GameMaster%' ORDER BY population DESC LIMIT 0,10";
$db->setQuery($stSQL);
$top10List = null;
$top10List = $db->loadObjectList();
if($top10List){
	$i = 1;
	foreach ($top10List as $row)
	{  
		echo "<li style=\"font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; list-style-type:none; line-height:20px\">".$i.". ".$row->username."</li>";
		$i++;
	}
}
ob_end_flush();
?>
