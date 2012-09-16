<?php
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ('includes/db_connect.' . $phpEx);
include ('includes/common.' . $phpEx);
if(!check_user()) {header ( "Location: login.php" );}
function saveFile($filename,$content)
{
	$f = fopen($filename, "w") or exit("Khong the mo file!");
	fputs($f,"$content");
	fclose($f);
	return true;
}
if($user['authlevel']<5)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang ( 'manager_building_types' );
	$parse = $lang;
	switch($_GET['s'])
	{
		case 1:	
			$_GET_id=0;
			if(isset($_GET['id']) && is_numeric($_GET['id']))
			{
				$_GET_id=$_GET['id'];
			}
			if($_POST['save'])
			{
				$content='<?xml version="1.0" encoding="utf-8"?>
				<note>
				<content><![CDATA['.str_replace('\\','',$_POST['content']).']]></content>
				</note>';					
				saveFile('../xml/building'.$_GET_id.'.xml',$content);	
			}
			$sql="SELECT id,name FROM `wg_building_types` ORDER BY id ASC";
			$db->setQuery($sql);
			$wg_building_types = NULL;
			$wg_building_types = $db->loadObjectList ();
			$option='<option value="" selected="selected">Menu</option>';
			$array=array(1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",9=>"9",10=>"10",11=>"11",12=>"12",13=>"13",14=>"14",15=>"15",18=>"16",20=>"17",24=>"18",27=>"19",28=>"20",29=>"21",30=>"22",31=>"23",35=>"24",36=>"25",37=>"26");
			foreach($wg_building_types as $key=>$v)
			{
				if($_GET_id == $array[$v->id])
				{
					$option.='<option value="'.$array[$v->id].'" selected="selected">'.$lang[$v->name].'</option>';
				}
				else
				{
					$option.='<option value="'.$array[$v->id].'">'.$lang[$v->name].'</option>';
				}
			}
			$parse['option']=$option;
			$xmlDoc = new DOMDocument();
			$xmlDoc->load("../xml/building".$_GET_id.".xml");				
			$x = $xmlDoc->documentElement;
			$parse['content']='';
			foreach ($x->childNodes as $item)
			{
				if($item->nodeName == 'content')
				{
					$parse['content'] =$item->nodeValue;	
				}			
			}				
			$page =parsetemplate(gettemplate('/admin/help_building_view'), $parse);
			displayAdmin($page);
			break;			
		default:
			$parse ['title_info'] = 'style="display:none"';
			global $db;
			$template_tech = '/admin/manager_building_types_views';
			
			if ($_REQUEST ['new']) 
			{
				$template_tech = '/admin/manager_building_types_new';
			}
			if ($_REQUEST ['update_f']) 
			{
				//Lấy thông tin cần sửa---------------------------------------------------------------------------------
				$sql = "SELECT *
							FROM `wg_building_types`
							WHERE wg_building_types.id='" . $_REQUEST ['update_id'] . "';";
				$db->setQuery ( $sql );
				$map_tech_update = null;
				$db->loadObject ( $map_tech_update );
				//Hiển thị ra ngoài------------------------------------------------------------------------------------
				$parse ['name_tech_building_valu'] = $map_tech_update->name;
				$parse ['name_tech_rs1_valu'] = $map_tech_update->rs1;
				$parse ['name_tech_rs2_valu'] = $map_tech_update->rs2;
				$parse ['name_tech_rs3_valu'] = $map_tech_update->rs3;
				$parse ['name_tech_rs4_valu'] = $map_tech_update->rs4;
				$parse ['name_tech_max_lv_valu'] = $map_tech_update->max_level;
				$parse ['name_tech_des_valu'] = $map_tech_update->des;
				$parse ['update_ok_id_valu'] = $_REQUEST ['update_id'];	
				$template_tech = '/admin/manager_building_types_update';
			}
			
			if ($_REQUEST ['name_tech_res_check']) {
				//Dùng cho template map_tech_tree_select.tpl------------------------------------------------------
				$rows_select = gettemplate ( '/admin/map_tech_tree_select_rows' );
				//Liệt danh sách các tech tree
				$sql = "SELECT *,wg_tech_tree.id as mkid
									FROM `wg_building_types` , `wg_tech_tree`
									WHERE wg_building_types.id = wg_tech_tree.building_type_id;";
				$db->setQuery ( $sql );
				$map_tech_saves = null;
				$map_tech_saves = $db->loadObjectList ();
				$list_select = "";
				foreach ( $map_tech_saves as $map_tech_save ) {
					$parse ['name_tech_id'] = $map_tech_save->mkid;
					$parse ['name_tech_building_r'] = $map_tech_save->name;
					$parse ['name_tech_level_r'] = $map_tech_save->level;
					$parse ['name_tech_res_r'] = $map_tech_save->requirement;
					$list_select .= parsetemplate ( $rows_select, $parse );
				}
				;
				$parse ['list_select'] = $list_select;
				
				$template_tech = '/admin/map_tech_tree_select';
			}
			//Sửa một công trình------------------------------------------------------------------------------
			if ($_REQUEST ['update']) {
				//Sửa wg_building_types
				$sql = "UPDATE `wg_building_types` 
					SET 
					`rs1` = '" . $_REQUEST ['name_tech_rs1'] . "',
					`rs2` = '" . $_REQUEST ['name_tech_rs2'] . "',
					`rs3` = '" . $_REQUEST ['name_tech_rs3'] . "',
					`rs4` = '" . $_REQUEST ['name_tech_rs4'] . "',
					`max_level` = '" . $_REQUEST ['name_tech_max_lv'] . "'
					WHERE `id` ='" . $_REQUEST ['update_ok_id'] . "'";
				$db->setQuery ( $sql );
				$db->query ();	
			}
			
			//START: insert new building
			if ($_REQUEST ['save']) 
			{
				//Kiểm tra xem tên làng mới này có trong 
				$sql = "SELECT * FROM `wg_building_types` WHERE name='" . $_REQUEST ['name_tech_building'] . "';";
				$db->setQuery ($sql);
				$map_tech_save = null;
				$db->loadObject ( $map_tech_save );
				$map_tech_save_id = $map_tech_save->id;
				if (! $map_tech_save->name) 
				{
					//Nếu tên đã có thì lấy id trong wg_building_types lưu vào building_type_id trong wg_tech_tree
					$sql = "INSERT INTO `wg_building_types` (`name`,`des`,`rs1`,`rs2`,`rs3`,`rs4`,`max_level`)
						VALUES ('" . $_REQUEST ['name'] . "',
						 '" . $_REQUEST ['name']."_des',			  
						   '" . $_REQUEST ['rs1'] . "',
						   '" . $_REQUEST ['rs2'] . "',
						   '" . $_REQUEST ['rs3'] . "',
						   '" . $_REQUEST ['rs4'] . "',
						   '" . $_REQUEST ['max_lv'] . "');";
					$db->setQuery ( $sql );
					$db->query ();		
				} 
				else 
				{
					$parse ['title_info'] = '';
					$parse ['info_'] = 'Đã có rồi!';
				}	
				//HIện lại trang thêm dữ liệu
				$template_tech = '/admin/manager_building_types_new';
			}
			//END: insert new building
			
			//Xoá 1 or nhiều công trình------------------------------------------------------------------------
			if ($_REQUEST ['delete']) {
				$arrs = $_REQUEST ['checkbox'];
				if (isset ( $arrs )) {
					foreach ( $arrs as $arr ) {
						//echo "abc";
						$sql = "delete from wg_building_types where id='" . $arr . "'";
						$db->setQuery ( $sql );
						$db->query ();
					}
					message ( $lang ['title_delete_tech'], $lang ['info_delete_tech'], "manager_building_types." . $phpEx );
				}
				message ( $lang ['value_id'], $lang ['title_delete'], "manager_building_types." . $phpEx );
			}
			;
			//Trỏ đến template map_tech_tree_rows
			$rows = gettemplate ( '/admin/manager_building_types_rows' );
			//kết nối đến template trong templates/OpenGame/admin/manager_map.tpl
			
			define('MAXROW',15);
			$sqlSum = "SELECT COUNT(DISTINCT(id)) FROM wg_building_types";
			$db->setQuery($sqlSum);
			$sum=(int)$db->loadResult();
			$parse['sum']=$sum;
			
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
			$parse['total_record']=$sum;
			$parse['total_page']=ceil($sum/constant("MAXROW"));
			
			
			$sql = "SELECT * FROM wg_building_types ORDER BY id ASC LIMIT ".$x.",".constant("MAXROW")."";
			$db->setQuery ( $sql );
			$map_tech_saves = null;
			$map_tech_saves = $db->loadObjectList ();
			$list = "";
			if($map_tech_saves)
			{
				$no = 1;
				foreach ($map_tech_saves as $map_tech_save ) 
				{
					$parse ['id'] = $map_tech_save->id;
					$parse ['name'] = $map_tech_save->name;
					$parse ['rs1'] = $map_tech_save->rs1;
					$parse ['rs2'] = $map_tech_save->rs2;
					$parse ['rs3'] = $map_tech_save->rs3;
					$parse ['rs4'] = $map_tech_save->rs4;
					$parse ['max_lv'] = $map_tech_save->max_level;
					$list .= parsetemplate ( $rows, $parse );
					$no ++;
				}
				$parse ['list'] = $list;
				//START: paging
				$a="'manager_building_types.php?page='+this.options[this.selectedIndex].value";
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