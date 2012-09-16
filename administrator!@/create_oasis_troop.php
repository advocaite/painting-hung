<?php
ob_start ();
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include ($ugamela_root_path . 'includes/common.' . $phpEx);
function saveFile($content)
{
	$f = fopen('../includes/wg_oasis_troop.php',"w")  or exit("Khong the mo file!");
	fputs($f,$content);
	fclose($f);
	return true;
}
global $db;
$sql = "SELECT  * FROM wg_oasis_troop";
$db->setQuery($sql);
$elements = $db->loadObjectList();
$char='$';
$content.='<?php
if (!defined("INSIDE")){die("Hacking attempt");}';
$content.="".$char."wg_oasis_troop=array();";
if($elements)
{
	$i=0;
	foreach ($elements as $ptu)
	{		
		$content.="".$char."wg_oasis_troop[$i]['id']=".$ptu->id.";";
		$content.="".$char."wg_oasis_troop[$i]['kind_id']=".$ptu->kind_id.";";
		$content.="".$char."wg_oasis_troop[$i]['troop34']=".$ptu->troop34.";";
		$content.="".$char."wg_oasis_troop[$i]['troop35']=".$ptu->troop35.";";
		$content.="".$char."wg_oasis_troop[$i]['troop36']=".$ptu->troop36.";";
		$content.="".$char."wg_oasis_troop[$i]['troop37']=".$ptu->troop37.";";
		$content.="".$char."wg_oasis_troop[$i]['troop38']=".$ptu->troop38.";";
		$content.="".$char."wg_oasis_troop[$i]['troop39']=".$ptu->troop39.";";
		$content.="".$char."wg_oasis_troop[$i]['troop40']=".$ptu->troop40.";";
		$content.="".$char."wg_oasis_troop[$i]['troop41']=".$ptu->troop41.";";
		$content.="".$char."wg_oasis_troop[$i]['troop42']=".$ptu->troop42.";";
		$content.="".$char."wg_oasis_troop[$i]['troop43']=".$ptu->troop43.";";
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