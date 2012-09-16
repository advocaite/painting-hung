<?php
ob_start ();
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include ($ugamela_root_path . 'includes/common.' . $phpEx);
function saveFile($content)
{
	$f = fopen('../includes/wg_building_types.php',"w") or exit("Khong the mo file!");
	fputs($f,$content);
	fclose($f);
	return true;
}
global $db;
$sql = "SELECT  * FROM wg_building_types";
$db->setQuery($sql);
$elements = $db->loadObjectList();
$char='$';
$content.='<?php
if (!defined("INSIDE")){die("Hacking attempt");}';
$content.="".$char."wg_building_types=array();";
if($elements)
{
	$i=0;
	foreach ($elements as $ptu)
	{
		
		$content.="".$char."wg_building_types[$i]['id']=".$ptu->id.";";
		$content.="".$char."wg_building_types[$i]['name']='".$ptu->name."';";
		$content.="".$char."wg_building_types[$i]['des']='".$ptu->des."';";
		$content.="".$char."wg_building_types[$i]['rs1']=".$ptu->rs1.";";
		$content.="".$char."wg_building_types[$i]['rs2']=".$ptu->rs2.";";
		$content.="".$char."wg_building_types[$i]['rs3']=".$ptu->rs3.";";
		$content.="".$char."wg_building_types[$i]['rs4']=".$ptu->rs4.";";
		$content.="".$char."wg_building_types[$i]['max_level']=".$ptu->max_level.";";
		$i++;
	}
}
$content.='?>';
if(saveFile($content))
{
	echo '<h2>Ghi noi dung moi vao file thanh cong</h2>';
}
else
{
	echo '<h2>Gap truc trac trong qua trinh ghi noi dung moi ra file</h2>';
}
?>