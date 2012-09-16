<?php
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include('includes/function_badlist.php');
include('includes/function_ban.php');
include('includes/function_users.php');
include('includes/function_paging.php');
include('includes/func_security.php');
include($ugamela_root_path . 'includes/function_profile.php');
require_once($ugamela_root_path . 'soap/call.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel'] < 5)
{
	header("Location:index.php");
	exit();
}
else
{	
	includeLang('admin_npc');
	global $user, $lang, $db;
	$parse = $lang;	
	if(count($_POST)<>0)
	{
		if(isset($_POST['update']))
		{
			$sql = "UPDATE wg_npc SET name='".$_POST['name']."',links='".$_POST['link']."' WHERE id=".$_GET['id'];
			$db->setQuery($sql);
			$db->query($sql);
		}
		if(isset($_POST['delete']))
		{
			$sql = "DELETE FROM wg_npc WHERE id=".$_GET['id'];
			$db->setQuery($sql);
			$db->query($sql);
		}
		if(isset($_POST['add']))
		{
			$sql = "INSERT INTO wg_npc (`id`, `name`, `links`) VALUES ('".$_POST['id']."', '".$_POST['name']."', '".$_POST['link']."');";
			$db->setQuery($sql);
			$db->query($sql);
		}
	}	
	//END: get for paging
	$sql="SELECT * FROM wg_npc ORDER BY id ASC";
	$db->setQuery($sql);
	$wg_npc=NULL;
	$wg_npc=$db->loadObjectList();			
	if($wg_npc)
	{
		$no =$x+1;
		$row='';		
		foreach ($wg_npc as $element)
		{
			$parse['no']=$no;
			$parse['id']=$element->id;	
			$parse['name']=$element->name;	
			$parse['link']=$element->links;
			$row.= parsetemplate (gettemplate('/admin/npc_row'), $parse );
			$no++;
		}
		$parse['row']=$row;
	}
	else
	{
		$parse['row']=parsetemplate (gettemplate('/admin/user_manager_null'), $parse );
	}		
	$page = parsetemplate(gettemplate("/admin/npc"), $parse);
	displayAdmin($page,'');
}	
?>