<?php
ob_start ();
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include ($ugamela_root_path . 'includes/common.' . $phpEx);
function saveFile($content)
{
	$f = fopen('../includes/wg_tech_tree.php',"w") or exit("Khong the mo file!");
	fputs($f,$content);
	fclose($f);
	return true;
}
global $db;
$sql = "SELECT  * FROM wg_tech_tree";
$db->setQuery($sql);
$elements = $db->loadObjectList();
$char='$';
$content.='<?php
if (!defined("INSIDE")){die("Hacking attempt");}';
$content.="".$char."wg_tech_tree=array();";
if($elements)
{
	$i=0;
	foreach ($elements as $ptu)
	{		
		$content.="".$char."wg_tech_tree[$i]['id']=".$ptu->id.";";
		$content.="".$char."wg_tech_tree[$i]['building_type_id']=".$ptu->building_type_id.";";
		$content.="".$char."wg_tech_tree[$i]['level']=".$ptu->level.";";
		$content.="".$char."wg_tech_tree[$i]['requirement']='".$ptu->requirement."';";
		$i++;
	}
}
$content.='?>';
if(saveFile($content))
{
	echo 'Ghi noi dung moi vao file thanh cong';
}
else
{
	echo 'Gap truc trac trong qua trinh ghi noi dung moi ra file';
}
?>