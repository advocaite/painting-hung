<?php
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ('includes/db_connect.' . $phpEx);
include ('includes/common.' . $phpEx);
include ($ugamela_root_path . 'extension.inc');
if(!check_user()){header("Location: login.php");}
function saveFile($filename,$content)
{
	$f = fopen($filename, "w") or exit("Khong the mo file!");
	fputs($f,"$content");
	fclose($f);
	return true;
}
if($user['authlevel'] <5)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang ('map_troops');
	$parse = $lang;
	global $db;
	switch($_GET['s'])
	{
		case 1:
			if(isset($_GET['id']) && is_numeric($_GET['id']))
			{
				if($_POST['save'])
				{
					$content='<?xml version="1.0" encoding="utf-8"?>
					<note>
					<content><![CDATA['.str_replace('\\','',$_POST['content']).']]></content>
					</note>';					
					saveFile('../xml/troopvn'.$_GET['id'].'.xml',$content);	
				}
				$sql="SELECT id,name FROM `wg_troops` WHERE id <= 33";
				$db->setQuery($sql);
				$wg_troops = NULL;
				$wg_troops = $db->loadObjectList ();
				$option='';
				foreach($wg_troops as $v)
				{
					if($_GET['id'] == $v->id)
					{
						$option.='<option value="'.$v->id.'" selected="selected">'.$lang[$v->name].'</option>';
					}
					else
					{
						$option.='<option value="'.$v->id.'">'.$lang[$v->name].'</option>';
					}
					$list_name.='<input type="hidden" name="name_troop'.$v->id.'" value="'.$lang[$v->name].'">';
				}
				$parse['option']=$option;
				$xmlDoc = new DOMDocument();
				$xmlDoc->load("../xml/troopvn".$_GET['id'].".xml");				
				$x = $xmlDoc->documentElement;
				foreach ($x->childNodes as $item)
				{
					if($item->nodeName == 'content')
					{
						$parse['content'] =$item->nodeValue;	
					}			
				}				
				$page =parsetemplate(gettemplate('/admin/help_troops_views'), $parse);
				displayAdmin($page);
			}
			else
			{
				header("Location:map_troops.php?s=1&id=1");
				exit();
			}			
			break;
		default:			
			define('MAXROW',15);	
			$template_tech = '/admin/map_troops_views';
			$parse ['info_'] = '';
			$parse ['valu_name'] = '';
			$parse ['show_combo_type']='<select id="valu_type" name="valu_type">';
			$parse ['show_combo_type'].='<option value="1" >Bộ</option>';
			$parse ['show_combo_type'].='<option value="2" >Ngựa</option>';
			$parse ['show_combo_type'].='<option value="3" >Cung</option>';
			$parse ['show_combo_type'].='</select>';
			//Lấy 3 chủng tộc đã được lưu sẳn trong wg_nations
			$sql="SELECT * FROM `wg_nations`";
			$db->setQuery($sql);
			$map_troop_types = null;
			$map_troop_types = $db->loadObjectList ();
			$parse ['show_combo_nation']='<select id="valu_nation" name="valu_nation">';
			$selected_type='';
			foreach ($map_troop_types as $map_troop_type)
			{
				if($_REQUEST['value_nation']==$map_troop_type->id)
					$selected_type='selected="selected"';
				$parse ['show_combo_nation'].='<option value="'.$map_troop_type->id.'" '.$selected_type.'>'.$map_troop_type->name.'</option>';
				$selected_type='';
			}
			$parse ['show_combo_nation'].='</select>';
			$parse ['valu_attack'] = '';
			$parse ['valu_melee_defense'] = '';
			$parse ['valu_ranger_defense'] = '';
			$parse ['valu_magic_defense'] = '';
			$parse ['valu_hitpoint'] = '';
			$parse ['valu_speed'] = '';
			$parse ['valu_carry'] = '';
			$parse ['valu_nation_id'] = '';
			$parse ['valu_rs1'] = '';
			$parse ['valu_rs2'] = '';
			$parse ['valu_rs3'] = '';
			$parse ['valu_rs4'] = '';
			$parse ['valu_time_train']='';
			$parse ['valu_keep_hour'] = '';
			$parse ['valu_icon']='';
			$parse ['valu_requirement'] = '';
			
			$sql="SELECT id,name FROM `wg_building_types`";
			$db->setQuery($sql);
			$map_troop_types = null;
			$map_troop_types = $db->loadObjectList ();
			$parse ['show_combo_building_type']='<select id="valu_building_type" name="valu_building_type">';
			$selected_type='';
			foreach ($map_troop_types as $map_troop_type)
			{
				if($_REQUEST['value_type']==$map_troop_type->id)
					$selected_type='selected="selected"';
				$parse ['show_combo_building_type'].='<option value="'.$map_troop_type->id.'" '.$selected_type.'>'.$map_troop_type->name.'</option>';
				$selected_type='';
			}
			$parse ['show_combo_building_type'].='</select>';
			//Hiện form update
			if (isset($_GET['update_id']) && is_numeric($_GET['update_id']))
			{
				$sql = "SELECT * FROM `wg_troops` where id=".$_GET['update_id'];
				$db->setQuery ( $sql );
				$map_troop_views = null;
				$db->loadObject ( $map_troop_views );
				//Nạp dữ liệu cần sữa
				$parse ['id'] =$_GET['update_id'];
				$parse ['valu_name'] = $map_troop_views->name;
				$parse ['valu_attack'] = $map_troop_views->attack;
				$parse ['valu_melee_defense'] = $map_troop_views->melee_defense;
				$parse ['valu_ranger_defense'] = $map_troop_views->ranger_defense;
				$parse ['valu_magic_defense'] = $map_troop_views->magic_defense;
				$parse ['valu_hitpoint'] = $map_troop_views->hitpoint;
				$parse ['valu_speed'] = $map_troop_views->speed;
				$parse ['valu_carry'] = $map_troop_views->carry;
				$parse ['valu_icon'] = $map_troop_views->icon;
				$parse ['valu_nation_id'] = $map_troop_views->nation_id;
				$parse ['valu_rs1'] = $map_troop_views->rs1;
				$parse ['valu_rs2'] = $map_troop_views->rs2;
				$parse ['valu_rs3'] = $map_troop_views->rs3;
				$parse ['valu_rs4'] = $map_troop_views->rs4;
				$parse ['valu_time_train']=$map_troop_views->time_train;
				$parse ['valu_keep_hour'] = $map_troop_views->keep_hour;		
				$parse ['show_combo_type']='<select id="valu_type" name="valu_type" disabled="disabled" class="fm">';
				if($map_troop_views->type==1)
					$parse ['show_combo_type'].='<option value="1" selected="selected">Bộ</option>';
				else 
					$parse ['show_combo_type'].='<option value="1" >Bộ</option>';
				if($map_troop_views->type==2)
					$parse ['show_combo_type'].='<option value="2" selected="selected">Ngựa</option>';
				else 
					$parse ['show_combo_type'].='<option value="2" >Ngựa</option>';
				if($map_troop_views->type==3)
					$parse ['show_combo_type'].='<option value="3" selected="selected">Cung</option>';
				else 
					$parse ['show_combo_type'].='<option value="3" >Cung</option>';
				$parse ['show_combo_type'].='</select>';
				$parse ['valu_requirement'] = $map_troop_views->requirement;
				$sql="SELECT * FROM `wg_nations`";
				$db->setQuery($sql);
				$map_troop_types = null;
				$map_troop_types = $db->loadObjectList ();
				$parse ['show_combo_nation']='<select id="valu_nation" name="valu_nation" disabled="disabled" class="fm">';
				$selected_type='';
				foreach ($map_troop_types as $map_troop_type)
				{
					if($map_troop_type->id==$map_troop_views->nation_id)
						$selected_type='selected="selected"';
					$parse ['show_combo_nation'].='<option value="'.$map_troop_type->id.'" '.$selected_type.'>'.$map_troop_type->name.'</option>';
					$selected_type='';
				}
				$parse ['show_combo_nation'].='</select>';
				
				$sql="SELECT id,name FROM `wg_building_types`";
				$db->setQuery($sql);
				$map_troop_types = null;
				$map_troop_types = $db->loadObjectList ();
				$parse ['show_combo_building_type']='<select id="valu_building_type" name="valu_building_type" disabled="disabled" class="fm">';
				$selected_type='';
				foreach ($map_troop_types as $map_troop_type)
				{
					if($map_troop_views->building_type_id==$map_troop_type->id)
						$selected_type='selected="selected"';
					$parse ['show_combo_building_type'].='<option value="'.$map_troop_type->id.'" '.$selected_type.'>'.$map_troop_type->name.'</option>';
					$selected_type='';
				}
				$parse['link']=$_SERVER['HTTP_REFERER'];
				$parse ['show_combo_building_type'].='</select>';
				//Hiện lại trang thêm dữ liệu
				$template_tech = '/admin/map_troops_update';
			
			}
			//Khi đồng ý update
			if ($_POST['update'])
			{
				$sql = "UPDATE `wg_troops` SET 
					`attack` = " . $_POST ['valu_attack'] . ",
					`melee_defense` = " . $_POST ['valu_melee_defense'] . ",
					`ranger_defense` = " . $_POST ['valu_ranger_defense'] . ",
					`magic_defense` = " . $_POST ['valu_magic_defense'] . ",
					`hitpoint` = " . $_POST ['valu_hitpoint'] . ",
					`speed` = " . $_POST ['valu_speed'] . ",
					`carry` = " . $_POST ['valu_carry'] . ",		
					`rs1` = " . $_POST ['valu_rs1'] . ",
					`rs2` = " . $_POST ['valu_rs2'] . ",
					`rs3` = " . $_POST ['valu_rs3'] . ",
					`rs4` = " . $_POST ['valu_rs4'] . ",
					`time_train` = " . $_POST ['valu_time_train'] . ",		
					`keep_hour` = " . $_POST ['valu_keep_hour'] . "
					 WHERE `id` =" . $_POST ['id_update'];
				$db->setQuery ($sql);
				$db->query ();	
			}
			//Hiển thị danh sách --------------------------------------------------------------------------
			if ($template_tech == '/admin/map_troops_views')
			{
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
				$sqlSum = "SELECT COUNT(DISTINCT(id)) FROM wg_troops";
				$db->setQuery($sqlSum);
				$sum=(int)$db->loadResult();
				$parse['sum']=$sum;	
				$parse['total_record']=$sum;
				$parse['total_page']=ceil($sum/constant("MAXROW"));
				
				$sql = "SELECT * FROM wg_troops ORDER BY id ASC LIMIT ".$x.", ".constant('MAXROW')."";
				$db->setQuery ( $sql );
				$map_troop_views = null;
				$map_troop_views = $db->loadObjectList ();
				$views_list = "";
				$no = 1;
				foreach ( $map_troop_views as $map_troop_view ) 
				{
					$parse ['id'] = $map_troop_view->id;
					$parse ['valu_name'] = $map_troop_view->name;
					$parse ['valu_attack'] = $map_troop_view->attack;
					$parse ['valu_melee_defense'] = $map_troop_view->melee_defense;
					$parse ['valu_ranger_defense'] = $map_troop_view->ranger_defense;
					$parse ['valu_magic_defense'] = $map_troop_view->magic_defense;
					$parse ['valu_hitpoint'] = $map_troop_view->hitpoint;
					$parse ['valu_speed'] = $map_troop_view->speed;
					$parse ['valu_carry'] = $map_troop_view->carry;
					$parse ['valu_nation_id'] = $map_troop_view->nation_id;
					$parse ['valu_rs1'] = $map_troop_view->rs1;
					$parse ['valu_rs2'] = $map_troop_view->rs2;
					$parse ['valu_rs3'] = $map_troop_view->rs3;
					$parse ['valu_rs4'] = $map_troop_view->rs4;
					$parse ['valu_keep_hour'] = $map_troop_view->keep_hour;
					$parse ['valu_requirement'] = $map_troop_view->requirement;
					$parse ['update_id_valu'] = $map_troop_view->id;
					$parse['no'] = $no;
					$views_list .= parsetemplate(gettemplate('/admin/map_troops_views_rows'), $parse);
					$no ++;
				}
				$parse ['views_list'] = $views_list;
				//START: paging
				$a="'map_troops.php?page='+this.options[this.selectedIndex].value";
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
			$page = parsetemplate ( gettemplate ( $template_tech ), $parse );
			displayAdmin ( $page );
	}			
}
?>