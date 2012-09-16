<?php
/*
	Plugin Name: map_tech_tree.php
	Plugin URI: http://asuwa.net/administrator/map_tech_tree.php
	Description: 
	+ Hien thi danh sach building
	Version: 1.0.0
	Author: 
	Author URI: http://tdnquang.com
*/
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ('includes/db_connect.' . $phpEx);
include ('includes/common.' . $phpEx);

if(!check_user()){header("Location: login.php");}
if($user['authlevel'] < 5)
{
	header("Location:index.php");
	exit();
}
else
{	includeLang ( 'map_tech_tree' );
	$parse = $lang;
	global $db;
	$template_tech = '/admin/map_tech_tree_views';
	if(isset($_GET['update']) && isset($_GET['id']))
	{
		if( is_numeric($_GET['update']) && is_numeric($_GET['id']))
		{
			$sql="UPDATE wg_tech_tree SET level=".$_GET['update']." WHERE id=".$_GET['id'];
			$db->setQuery($sql);
			$db->query();
		}
		header("Location:".$_SERVER['HTTP_REFERER']."");
		exit();
	}
	define('MAXROW',5);
	//START: get for paging
	if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"]))
	{
		$x=0;
	}
	else
	{
		$x=($_GET["page"]-1)*constant("MAXROW");
	}
	//END: get for paging
	
	if ($template_tech == '/admin/map_tech_tree_views') 
	{
		$sqlsum = "SELECT wg_tech_tree . * , wg_building_types.name
				FROM wg_tech_tree
				LEFT JOIN wg_building_types ON wg_building_types.id = wg_tech_tree.building_type_id
				WHERE wg_tech_tree.requirement != ''
				AND wg_building_types.name != 'NULL'
				ORDER BY `wg_tech_tree`.`building_type_id` ASC";		
		$db->setQuery($sqlsum);
		$query_sum = null;
		$query_sum = $db->loadObjectList();	
		$sum=count($query_sum);
		$parse['total_record']=$sum;
		$parse['total_page']=ceil($sum/constant("MAXROW"));	
		
		$sql=$sqlsum.' LIMIT '.$x.','.constant("MAXROW").'';
		$db->setQuery($sql);
		$emlement = null;
		$emlement = $db->loadObjectList();
		$list = "";
		$no =1+$x;
		foreach($emlement as $key=>$v ) 
		{		
			$parse ['name'] = $v->name;
			$parse ['id'] = $v->building_type_id;
			$parse['requirement']=showRequirement($v->building_type_id,$v->requirement);
			$parse['no'] = $no;
			$list .= parsetemplate(gettemplate('/admin/map_tech_tree_rows'), $parse);
			$no ++;		
		}
		$parse ['list'] = $list;
		//START: paging
		$a="'map_tech_tree.php?page='+this.options[this.selectedIndex].value";
		$b="'_top'";
		$string='onchange="javscript:window.open('.$a.','.$b.')"';
		$parse['pagenumber']=''.$lang['40'].' <select name="page" style="width:40px;height=40px;" '.$string.'>';
		for($i=1;$i<=ceil($sum/constant("MAXROW"));$i++)
		{
			$parse['pagenumber'].='<option value="'.$i.'"';
			if($_GET["page"]==$i)
			{
				$parse['pagenumber'].=' selected="selected">';
			}
			else
			{
				$parse['pagenumber'].='>';
			}						
			$parse['pagenumber'].=''.$i.'</option>';
		}
		$parse['pagenumber'].='</select>';
		//END: paging
	}
	
	$page = parsetemplate(gettemplate($template_tech), $parse );
	displayAdmin($page );
}
function showRequirement($id,$requirement)
{
	global $db;
	$sql = " SELECT wg_building_types.name ,tb1.id,tb1.level FROM `wg_tech_tree` as tb1 
	LEFT JOIN wg_building_types ON wg_building_types.id=tb1.building_type_id 
	WHERE tb1.id IN (".$requirement.")";
	$db->setQuery ( $sql );
	$query = null;
	$query = $db->loadObjectList ();
	$list=$list_='';
	foreach ( $query as $key=>$v )
	{
		$list.=($key+1).'.'.$v->name.' level ->&nbsp;&nbsp;<strong>['.$v->level.']</strong><hr>';
		$list_.=($key+1).'.'.$v->name.' level ->&nbsp;&nbsp;<input type="text" id="txt_'.$v->id.'"  value="'.$v->level.'" class="fm" size="2" maxlength="2" onchange="Update('.$v->id.');"/><hr>';
	}	
	return '<fieldset id="div_'.$id.'_a"  style="display:block">'.substr($list,0,-4).'</fieldset><fieldset id="div_'.$id.'"  style="display:none">'.substr($list_,0,-4).'</fieldset>';
}
?>