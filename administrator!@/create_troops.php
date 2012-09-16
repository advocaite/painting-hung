<?php
ob_start ();
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include ($ugamela_root_path . 'includes/common.' . $phpEx);
function saveFile($content)
{
	$f = fopen('../includes/wg_troops.php',"w");
	fputs($f,$content);
	fclose($f);
	return true;
}
global $db;
$sql = "SELECT  * FROM wg_troops WHERE id";
$db->setQuery($sql);
$elements = $db->loadObjectList();
$char='$';
$content.='<?php
if (!defined("INSIDE")){die("Hacking attempt");}';
$content.="".$char."wg_troops=array();";
if($elements)
{
	$i=0;
	foreach ($elements as $ptu)
	{	
		$content.="".$char."wg_troops[$i]['id']=".$ptu->id.";";
		$content.="".$char."wg_troops[$i]['name']='".$ptu->name."';";
		$content.="".$char."wg_troops[$i]['image']='".$ptu->image."';";
		$content.="".$char."wg_troops[$i]['icon']='".$ptu->icon."';";
		$i++;
	}
}
$content.='?>';
if(saveFile($content))
{
	echo '<h3>Ghi noi dung moi vao file thanh cong</h3>';
}
else
{
	echo '<h3>Gap truc trac trong qua trinh ghi noi dung moi ra file</h3>';
}
?>