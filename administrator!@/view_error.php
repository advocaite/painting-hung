<?php
define('INSIDE', true);
ob_start();
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include('includes/function_paging.php');
include($ugamela_root_path . 'includes/wg_building_types.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('admin_error');
	global $db, $lang,$wg_building_types;
	$parse = $lang;
	if(isset($_GET['tab']))
	{
		$task=$_GET['tab'];
	}
	
	switch ($task)
	{
		case 1:
			if(isset($_GET['fix_name']) && isset($_GET['id']) )
			{
				$sql="UPDATE wg_heros SET name='".$_GET['fix_name']."' WHERE id=".$_GET['id'];
				$db->setQuery($sql);	
				$db->query();
				header("Location:view_error.php?tab=1");
				exit();				
			}
			define('MAXROW',20);
			$parse['value_name']=$dk='';
			$link='view_error.php?tab=1';
			if(isset($_GET['keyword']))
			{
				$parse['value_name']=$_GET['keyword'];
				$dk=" WHERE wg_heros.name LIKE '%".$_GET['keyword']."%' ";
				$link='view_error.php?tab=1&keyword='.$_GET['keyword'];
			}
			$sqlsum=" SELECT count(DISTINCT(wg_heros.id) ) FROM wg_heros ".$dk;
			$db->setQuery($sqlsum);		
			$sum=(int)$db->loadResult();
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
				$x=0;			
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");			
			}
			$list=NULL;		
			$parse['level']=$parse['village']=$parse['username']='';
			$parse['level_limit']=$value['max_level'];
			$sql = "SELECT wg_heros.id, wg_heros.name, wg_heros.level,wg_users.username,
					wg_heros.kinh_nghiem,(SELECT name FROM wg_villages WHERE id=wg_heros.village_id) AS villa_name
					FROM wg_heros LEFT JOIN wg_users ON wg_users.id=wg_heros.user_id ".$dk."
					ORDER BY wg_heros.level DESC LIMIT ".$x.",".constant("MAXROW")."";
			$db->setQuery($sql);
			$infos = NULL;
			$infos = $db->loadObjectList();
			if($infos)
			{
				$no=$x+1;
				foreach ($infos as $v)
				{
					$parse['no']=$no;
					$parse['id']=$v->id;	
					$parse['level']=$v->level;	
					$parse['name']=$v->name;
					$parse['point_kn']=$v->kinh_nghiem;	
					$parse['village']=$v->villa_name;
					$parse['username']="<a href=\"#".$v->username."\" onclick=\"return PopUpSong('".$v->username."||Asuwa');\">".$v->username."</a>";
					if($v->username=='')
					{
						$parse['username']="<span style=\"color:#FF0000; font-weight:bolder\">account not exist</span>";
					}								
					$list .= parsetemplate(gettemplate('admin/check_name_hero_row'),$parse);
					$no++;
				}			
				$parse['list']= $list;
				$parse['page']= paging($link,$sum, constant("MAXROW"));
			}
			else
			{
				$parse['list']=$parse['page']='';
			}		
			$page = parsetemplate(gettemplate('admin/check_name_hero'), $parse);
			displayAdmin($page,'');
			break;
		case 2:
			if(isset($_POST['delete']))
			{
				$arrs=$_POST['checkbox'];
				if (isset($arrs))
				{
					foreach($arrs as $arr)
					{				
						$sql="DELETE FROM wg_allies WHERE id=".$arr;
						$db->setQuery($sql);	
						$db->query();
						
						$sql="DELETE FROM ally_id WHERE ally_id =".$arr;
						$db->setQuery($sql);	
						$db->query();
						
						$sql="UPDATE wg_users SET alliance_id=0 WHERE alliance_id=".$arr;
						$db->setQuery($sql);	
						$db->query();						
					}				
				}
			}
			if(isset($_GET['fix_name']) && isset($_GET['id']) )
			{
				$sql="UPDATE wg_allies SET name='".$_GET['fix_name']."' WHERE id=".$_GET['id'];
				$db->setQuery($sql);	
				$db->query();
				header("Location:view_error.php?tab=2");
				exit();				
			}
			define('MAXROW',20);
			$parse['value_name']=$dk='';
			$link='view_error.php?tab=2';
			if(isset($_GET['keyword']))
			{
				$parse['value_name']=$_GET['keyword'];
				$dk=" WHERE wg_allies.name LIKE '%".$_GET['keyword']."%' ";
				$link='view_error.php?tab=2&keyword='.$_GET['keyword'];
			}
			$sqlsum=" SELECT count( DISTINCT (wg_allies.id) ) FROM wg_allies ".$dk;
			$db->setQuery($sqlsum);		
			$sum=(int)$db->loadResult();		
			//START: get for paging
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
				$x=0;			
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");			
			}
			$sql = "SELECT wg_allies.id,wg_allies.name,wg_users.username,
			(SELECT COUNT(user_id) FROM wg_ally_members WHERE right_=1 AND ally_id=wg_allies.id) sum_mem
			FROM wg_allies LEFT JOIN wg_users ON wg_users.id= wg_allies.user_id ".$dk."
			ORDER BY sum_mem ASC LIMIT ".$x.",".constant("MAXROW")."";	
			$db->setQuery($sql);
			$infos=$list=NULL;
			$infos = $db->loadObjectList();
			if($infos)
			{
				$no=$x+1;
				foreach ($infos as $v)
				{
					$parse['no']=$no;
					$parse['id']=$v->id;
					$parse['name_ally']=$v->name;
					$parse['username']=$v->username;
					$parse['sum_mem']=$v->sum_mem;
					$list.= parsetemplate(gettemplate('admin/view_error_ally_row'),$parse);
					$no++;
				}
				$parse['list']= $list;
				$parse['page']= paging($link,$sum, constant("MAXROW"));
			}
			else
			{
				$parse['list']=$parse['page']='';
			}
			$page = parsetemplate(gettemplate('admin/view_error_ally'), $parse);
			displayAdmin($page,'');
			break;
		case 3:
			
			if(isset($_GET['fix_name']) && isset($_GET['id']) )
			{
				$sql="UPDATE wg_villages SET name='".$_GET['fix_name']."' WHERE id=".$_GET['id'];
				$db->setQuery($sql);	
				$db->query();
				header("Location:view_error.php?tab=3");
				exit();				
			}
			define('MAXROW',20);
			$parse['value_name']=$dk='';
			$link='view_error.php?tab=3';
			if(isset($_GET['keyword']))
			{
				$parse['value_name']=$_GET['keyword'];
				$dk=" WHERE wg_villages.name LIKE '%".$_GET['keyword']."%' ";
				$link='view_error.php?tab=3&keyword='.$_GET['keyword'];
			}
			$sqlsum=" SELECT count(DISTINCT(wg_villages.id)) FROM wg_villages ".$dk;
			$db->setQuery($sqlsum);		
			$sum=(int)$db->loadResult();		
			//START: get for paging
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
				$x=0;			
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");			
			}
			$sql = "SELECT wg_villages.id, wg_villages.name, wg_villages.workers,wg_villages.kind_id, wg_users.username
					FROM wg_villages LEFT JOIN wg_users ON wg_users.id = wg_villages.user_id ".$dk."
					ORDER BY wg_villages.workers DESC
					LIMIT ".$x.",".constant("MAXROW")."";
			$db->setQuery($sql);
			$infos=$list=NULL;
			$infos = $db->loadObjectList();
			if($infos)
			{
				$no=$x+1;
				foreach ($infos as $v)
				{
					$parse['no']=$no;
					$parse['id']=$v->id;
					$parse['name']=$v->name;
					$parse['kind']=$v->kind_id;
					$parse['username']=$v->username;
					$parse['sum_mem']=$v->workers;
					$list.= parsetemplate(gettemplate('admin/view_error_village_row'),$parse);
					$no++;
				}
				$parse['list']= $list;
				$parse['page']= paging($link,$sum, constant("MAXROW"));
			}
			else
			{
				$parse['list']=$parse['page']='';
			}
			$page = parsetemplate(gettemplate('admin/view_error_village'), $parse);
			displayAdmin($page,'');
			break;
		default:
			header("Location:view_error.php?tab=1");
			exit();
			break;
	}
	ob_end_flush();
}
?>
