<?php
/*
	Plugin Name: attack.php
	Plugin URI: http://asuwa.net/administrator/attack.php
	Description: 
	+ Hien thi danh sach nhung attack da thuc hien xong
	+ Chuyen sang table wg_attack_backup
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);

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
	if($_POST['move_attack'])
	{
		$sql="SELECT * FROM wg_attack WHERE status=1 ORDER BY id ASC LIMIT 0,3000";
		$db->setQuery($sql);
		$elements=NULL;
		$elements=$db->loadObjectList();		
		if($elements)
		{
			foreach ($elements as $element)
			{
				$sql="INSERT INTO `wg_attack_backup` 
				(`id`,`village_attack_id`,`village_defend_id`,`type`,`building_type_id`,`status`)
				 VALUES (".$element->id.",".$element->village_attack_id.",".$element->village_defend_id.",".$element->type.",'".$element->building_type_id."',".$element->status.")";
				$db->setQuery($sql);
				if($db->query())
				{	
					$sql="DELETE FROM wg_attack WHERE id=".$element->id;
					$db->setQuery($sql);
					$db->query();
					unset($element);
				}
			}
		}
	}
	define('MAXROW',20);			
	if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"]))
	{
		$x=0;
	}
	else
	{
		$x=($_GET["page"]-1)*constant("MAXROW");
	}
	
	$char='attack.php?page';
	$template='/admin/attack_list';
	$table='wg_attack';
	if(isset($_GET['s']))
	{
		$template='/admin/attack_bk_list';
		$table='wg_attack_backup';
		$char='attack.php?s=1&page';
	}	
	$sqlSum = "SELECT COUNT(DISTINCT(id)) AS status1,
	(SELECT COUNT(DISTINCT(id)) FROM ".$table." WHERE status=0) AS status0 FROM ".$table." WHERE status=1 ";
	$db->setQuery($sqlSum);
	$db->loadObject($wg_attack);
	$parse['status1']=$wg_attack->status1;
	$parse['status0']=$wg_attack->status0;
	$parse['total_page']=ceil(($wg_attack->status1+$wg_attack->status0)/constant("MAXROW"));	
	$sql="SELECT ".$table.".*,wg_villages.name AS village_attack,
	(SELECT name FROM wg_villages WHERE id=".$table.".village_defend_id) AS village_defend FROM ".$table." 
	LEFT JOIN wg_villages ON wg_villages.id=".$table.".village_attack_id
	 ORDER BY ".$table.".id DESC LIMIT ".$x.",".constant("MAXROW")."";
	$db->setQuery($sql);
	$elements=$db->loadObjectList();		
	if($elements)
	{
		$no = 1;
		foreach ($elements as $element)
		{
			$parse['id']=$element->id;
			$parse['no']=$x+$no;
			$parse['village_attack_id']=$element->village_attack_id;
			$parse['village_defend_id']=$element->village_defend_id;
			$parse['village_name_attack'] = $element->village_attack;
			$parse['village_name_defence'] = $element->village_defend;
			if($element->village_defend=='')
			{
				$sql = "SELECT x,y FROM wg_villages_map WHERE id = ".$element->village_defend_id;
				$db->setQuery($sql);
				$db->loadObject($wg_villages_map);						
				$parse['village_name_defence']='Bộ lạc ('.$wg_villages_map->x.'|'.$wg_villages_map->y.')';
			}
			$parse['type'] = $element->type;
			$parse['building'] = $element->building_type_id;
			$parse['status'] = $element->status;
			$attack_list .= parsetemplate (gettemplate('/admin/attack_list_row'), $parse );
			$no++;
		}	
		$parse['view_attack_list']=$attack_list;	
		
		$a="'".$char."='+this.options[this.selectedIndex].value";
		$b="'_top'";
		$string='onchange="javscript:window.open('.$a.','.$b.')"';
		$parse['pagenumber']=''.$lang['40'].' <select name="page" style="width:40px;height=40px;" '.$string.'>';
		for($i=1;$i<=ceil(($wg_attack->status1+$wg_attack->status0)/constant("MAXROW"));$i++){
			$parse['pagenumber'].='<option value="'.$i.'"';
			if(empty($_GET["page"]) && $i==$p+1){
				$parse['pagenumber'].='selected="selected">';
			}elseif($_GET["page"]==$i){
				$parse['pagenumber'].='selected="selected">';
			}else{
				$parse['pagenumber'].='>';
			}						
			$parse['pagenumber'].=''.$i.'</option>';
		}
		$parse['pagenumber'].='</select>';	
		
	}
	else
	{
		$parse['pagenumber']='';
		$parse['view_attack_list']=parsetemplate(gettemplate('/admin/attack_null'),$parse);						
	}
	$page = parsetemplate(gettemplate($template), $parse);
	displayAdmin($page,$lang['status']);	
}
?>