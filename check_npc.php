<?php 
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php'); 

global $db;
$position=$check=0;
$code=$db->getEscaped(str_replace('$', '\$',addslashes($_GET["code"])));
$sql="SELECT id,position,code FROM `wg_npc_reg`";
$db->setQuery($sql);
$wg_npc_reg=NULL;
$wg_npc_reg=$db->loadObjectList();
$round=$wg_npc_reg[0]->id;
if($wg_npc_reg)
{	
	$array=array();
	foreach ($wg_npc_reg as $v)
	{
		if($check ==0 && $v->code !='')
		{
			$array=split(';',$v->code);		
			foreach($array as $k)
			{
				if($k==$code)
				{
					$position=$v->position;
					$check=1;
				}
			}	
		}		
	}	
}
header('Content-type: text/xml');
header('Pragma: public');        
header('Cache-control: private');
header('Expires: -1');
echo '<?xml version="1.0" encoding="utf-8" ?>
<node>
	<position>'.$position.'</position>
	<round>'.$round.'</round>
</node>
';
ob_end_flush();
?>

