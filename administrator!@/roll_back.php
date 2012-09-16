<?php
/*
	Plugin Name: roll_back.php
	Plugin URI: http://asuwa.net/administrator/roll_back.php
	Description: 
	+ thuc hien nhung roll back cho user
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.php');
include('includes/common.php'); 

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('status');
	$parse = $lang;
	
	if(isset($_GET['s'])){
		$task = $_GET['s'];
	}
	switch($task){	
		
		//START: are u sure ?
		case "vw":			
			$page = parsetemplate(gettemplate('/admin/roll_back_view_worker'), $parse);
			displayAdmin($page,$lang['userlist_punish']);		
		break;
		
		case "uw":		
			$page = parsetemplate(gettemplate('/admin/roll_back_update_worker'), $parse);
			displayAdmin($page,$lang['userlist_punish']);		
		break;
		
		case "vkrs":		
			$page = parsetemplate(gettemplate('/admin/roll_back_view_krs'), $parse);
			displayAdmin($page,$lang['userlist_punish']);		
		break;
		
		case "ukrs":		
			$page = parsetemplate(gettemplate('/admin/roll_back_update_krs'), $parse);
			displayAdmin($page,$lang['userlist_punish']);		
		break;	
		
		case "fmc":		
			$page = parsetemplate(gettemplate('/admin/roll_back_fix_merchant'), $parse);
			displayAdmin($page,$lang['userlist_punish']);		
		break;	
		case "update_rank":		
			$page = parsetemplate(gettemplate('/admin/roll_back_update_rank'), $parse);
			displayAdmin($page,$lang['userlist_punish']);		
		break;
		case "maxlevel":		
			includeLang('admin_error');
			include($ugamela_root_path . 'includes/wg_building_types.php');
			global $db, $lang,$wg_building_types;
			$parse = $lang;
			if(isset($_GET['type']) && isset($_GET['fix_level']) && isset($_GET['id']) )
			{
				$level=min($wg_building_types[$_GET['type']]['max_level'],$_GET['fix_level']);			
				$sql="UPDATE wg_buildings SET level=".$level." WHERE id=".$_GET['id'];
				$db->setQuery($sql);	
				$db->query();
				header("Location:roll_back.php?s=maxlevel");
				exit();		
			}
			$list=NULL;
			foreach ($wg_building_types as $key=>$value)
			{
				$parse['level']=$parse['village']=$parse['username']='';
				$parse['level_limit']=$value['max_level'];
				$sql = "SELECT wg_buildings.id,wg_buildings.type_id,wg_buildings.level,wg_buildings.vila_id,(SELECT username FROM wg_users WHERE id=wg_villages.user_id) AS username FROM wg_buildings INNER JOIN wg_villages ON wg_villages.id=wg_buildings.vila_id WHERE wg_buildings.type_id=".$value['id']." AND wg_buildings.level > ".$value['max_level']." GROUP BY wg_buildings.vila_id";
				$db->setQuery($sql);
				$infos = NULL;
				$infos = $db->loadObjectList();
				if($infos)
				{
					$list_level=$list_village=$list_username=NULL;
					foreach ($infos as $v)
					{
						$list_village.=$v->vila_id.'<hr>';
						$list_username.=$v->username.'<hr>';
						if($v->type_id <= 4 || $v->type_id==10 || $v->type_id==11 )
						{
							$sql="SELECT id FROM wg_buildings WHERE vila_id=".$v->vila_id." AND type_id=37";
							$db->setQuery($sql);
							$db->loadObject($wg_buildings);
							if($wg_buildings)
							{
								$list_level.='<input type="text" id="'.$v->id.'" value="'.$v->level.'" size="2" class="fm" onchange="UpdateLevel('.$key.','.$v->id.');"> (Kỳ đài)<hr>';	
							}
							else
							{
								$list_level.='<input type="text" id="'.$v->id.'" value="'.$v->level.'" size="2" class="fm" onchange="UpdateLevel('.$key.','.$v->id.');"><hr>';	
							}
						}
						else
						{
							$list_level.='<input type="text" id="'.$v->id.'" value="'.$v->level.'" size="2" class="fm" onchange="UpdateLevel('.$key.','.$v->id.');"><hr>';							
						}
					}				
					$parse['level']=substr($list_level,0,-4);
					$parse['village']=substr($list_village,0,-4);
					$parse['username']=substr($list_username,0,-4);
				}
				$parse['no']=$key+1;
				$parse['name_building']=$value['name'];
				$list .= parsetemplate(gettemplate('admin/view_error_building_row'),$parse);
			}		
			$parse['list']= $list;
			$page = parsetemplate(gettemplate('admin/view_error_building'), $parse);
			displayAdmin($page,'');		
		break;
		default:		
			$page = parsetemplate(gettemplate('/admin/roll_back_fix_troop_keep'), $parse);
			displayAdmin($page,$lang['userlist_punish']);		
		break;	
		//END: are u sure ?
	}
}
?>