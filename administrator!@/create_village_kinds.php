<?php
ob_start ();
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include ($ugamela_root_path . 'includes/common.' . $phpEx);
function saveFile($content)
{
	$f = fopen('../includes/wg_village_kinds.php',"w+")  or exit("Khong the mo file!");
	fputs($f,$content);
	fclose($f);
	return true;
}
global $db;
$sql = "SELECT  * FROM wg_village_kinds";
$db->setQuery($sql);
$elements = $db->loadObjectList();
$char='$';
$content.='<?php
if (!defined("INSIDE")){die("Hacking attempt");}';
$content.="".$char."wg_village_kinds=array();";
if($elements)
{
	$i=0;
	foreach ($elements as $ptu)
	{
		
		$content.="".$char."wg_village_kinds[$i]['id']=".$ptu->id.";";
		$content.="".$char."wg_village_kinds[$i]['image']='".$ptu->image."';";
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