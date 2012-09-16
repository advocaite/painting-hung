<?php
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.'.$phpEx);
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/func_security.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_plus.'.$phpEx);
include_once($ugamela_root_path . 'soap/call.'.$phpEx);
checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
includeLang('plus');
global $lang,$user;
$parse=$lang;

$link=array("complete"=>8,"speedup_15"=>17,"speedup_30"=>18,"speedup_2h"=>19);

$sql = "SELECT wg_config_plus.*, wg_item_user.quantity 
FROM wg_config_plus left join wg_item_user on wg_config_plus.name = wg_item_user.item_name 
WHERE wg_config_plus.name in ('complete','speedup_15','speedup_30','speedup_2h') and wg_item_user.user_id = ".$user['id'];
$db->setQuery($sql);
$info = NULL;
$info=$db->loadObjectList();
if($info)
{	
	$row_speedup = '';	
	foreach($info as $v)
	{
		$parse['image']=$v->images;
		$parse['description']=$lang['Des_'.$v->name];
		$parse['quantity_']=$v->quantity;
		$parse['duration']=$v->duration;
		$name=$v->name;
		$parse['slogan']='';
		$parse['id']='s'.$link[$name];
		if ($v->quantity >= 1)
		{
			$use = $lang['Use'];
			$parse['slogan']='<a href="plus.php?type='.$link[$name].'">'.$use.'</a>';
		}	
		$row_speedup.= parsetemplate(gettemplate('speedup_row'),$parse);
	}
}
$parse['row_speedup'] =$row_speedup;
$parse['total_gold'] = showgold($user['id']);
$parse['total_asu_bill'] = get_gold_remote($user['username']);
$page = parsetemplate(gettemplate('speedup'),$parse); 
display2($page,'');
ob_end_flush();
?>

