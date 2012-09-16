<?php
define ( 'INSIDE', true );

$ugamela_root_path = '../../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/common.' . $phpEx);
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include ($ugamela_root_path . 'includes/func_security.php');

if (! check_user ()) {
	header ( "Location: login.php" );
}

//kết nối đến ngôn ngữ trong thư mục language/en/manager_map.mo
includeLang ( 'manager_building_types' );

//Chuyển ngôn ngữ đó ra ngoài
$parse = $lang;
$parse ['title_info'] = 'style="display:none"';
global $db;
//Trang mặc định là trang Views--------------------------------------------------------------------
$template_tech = '/admin/manager_building_types_views';
//Nhận các công trình cho res----------------------------------------------------------------------
if ($_REQUEST ['select_tech']) {
	$arrs = $_REQUEST ['checkbox'];
	$_REQUEST ['name_tech_res'] = "";
	if (isset ( $arrs )) {
		foreach ( $arrs as $arr ) {
			$_REQUEST ['name_tech_res'] .= $arr . ',';
		}
	}
	$template_tech = '/admin/map_tech_tree_save';
}
$parse ['name_tech_building_valu'] = $_REQUEST ['name_tech_building'];
$parse ['name_tech_rs1_valu'] = $_REQUEST ['name_tech_rs1'];
$parse ['name_tech_rs2_valu'] = $_REQUEST ['name_tech_rs2'];
$parse ['name_tech_rs3_valu'] = $_REQUEST ['name_tech_rs3'];
$parse ['name_tech_rs4_valu'] = $_REQUEST ['name_tech_rs4'];
$parse ['name_tech_rs5_valu'] = $_REQUEST ['name_tech_rs5'];
$parse ['name_tech_type_valu'] = $_REQUEST ['name_tech_type'];
$parse ['name_tech_des_valu'] = $_REQUEST ['name_tech_des'];
$parse ['name_tech_cp_valu'] = $_REQUEST['name_tech_cp'];
$parse ['name_tech_max_lv_valu'] = $_REQUEST['name_tech_max_lv'];
$parse ['name_tech_product_hour_valu'] = $_REQUEST ['name_tech_product_hour'];
$parse ['list'] = "";
//Các hàm liên kết trang---------------------------------------------------------------------------
if ($_REQUEST ['new']) {
	$template_tech = '/admin/manager_building_types_new';
}
;
if ($_REQUEST ['update_f']) {
	//Lấy thông tin cần sửa---------------------------------------------------------------------------------
	$sql = "SELECT *
				FROM `wg_building_types`
				WHERE wg_building_types.id='" . $_REQUEST ['update_id'] . "';";
	$db->setQuery ( $sql );
	$map_tech_update = null;
	$db->loadObject ( $map_tech_update );
	//Hiển thị ra ngoài----------------------------------------------------------------------------------------------------
	$parse ['name_tech_building_valu'] = $map_tech_update->name;
	$parse ['name_tech_rs1_valu'] = $map_tech_update->rs1;
	$parse ['name_tech_rs2_valu'] = $map_tech_update->rs2;
	$parse ['name_tech_rs3_valu'] = $map_tech_update->rs3;
	$parse ['name_tech_rs4_valu'] = $map_tech_update->rs4;
	$parse ['name_tech_rs5_valu'] = $map_tech_update->workers;
	$parse ['name_tech_type_valu'] = $map_tech_update->type;
	$parse ['name_tech_cp_valu'] = $map_tech_update->cp;
	$parse ['name_tech_max_lv_valu'] = $map_tech_update->max_level;
	$parse ['name_tech_des_valu'] = $map_tech_update->des;
	$parse ['name_tech_product_hour_valu'] = $map_tech_update->product_hour;
	$parse ['update_ok_id_valu'] = $_REQUEST ['update_id'];
	
	$template_tech = '/admin/manager_building_types_update';
}
;
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
;
//Sửa một công trình------------------------------------------------------------------------------
if ($_REQUEST ['update']) {
	//Sửa wg_building_types
	$sql = "UPDATE `wg_building_types` 
		SET 
		`name` = '" . $_REQUEST ['name_tech_building'] . "',
		`des` = '" . $_REQUEST ['name_tech_des'] . "',
		`rs1` = '" . $_REQUEST ['name_tech_rs1'] . "',
		`rs2` = '" . $_REQUEST ['name_tech_rs2'] . "',
		`rs3` = '" . $_REQUEST ['name_tech_rs3'] . "',
		`rs4` = '" . $_REQUEST ['name_tech_rs4'] . "',
		`cp` = '" . $_REQUEST ['name_tech_cp'] . "',
		`max_level` = '" . $_REQUEST ['name_tech_max_lv'] . "',
		`workers` = '" . $_REQUEST ['name_tech_rs5'] . "',
		`product_hour` = '" . $_REQUEST ['name_tech_product_hour'] . "', 
		`type` = '" . $_REQUEST ['name_tech_type'] . "'   
		WHERE `wg_building_types`.`id` ='" . $_REQUEST ['update_ok_id'] . "'";
	echo $sql;
	$db->setQuery ( $sql );
	$db->query ();
}
;
//Thêm mới 1 công trình----------------------------------------------------------------------------
if ($_REQUEST ['save']) {
	//Kiểm tra xem tên làng mới này có trong 
	$sql = "SELECT * FROM `wg_building_types` where name='" . $_REQUEST ['name_tech_building'] . "';";
	$db->setQuery ( $sql );
	$map_tech_save = null;
	$db->loadObject ( $map_tech_save );
	$map_tech_save_id = $map_tech_save->id;
	if (! $map_tech_save->name) {
		//Nếu tên đã có thì lấy id trong wg_building_types lưu vào building_type_id trong wg_tech_tree
		$sql = "INSERT INTO `wg_building_types` (`id`,`name`,`des`,`product_hour`,`type`,`rs1`,`rs2`,`rs3`,`rs4`,`cp`,`max_level`,`workers`)
			VALUES (
			NULL , '" . $_REQUEST ['name_tech_building'] . "',
			 '" . $_REQUEST ['name_tech_des'] . "',
			  '" . $_REQUEST ['name_tech_product_hour'] . "',
			   '" . $_REQUEST ['name_tech_type'] . "',
			   '" . $_REQUEST ['name_tech_rs1'] . "',
			   '" . $_REQUEST ['name_tech_rs2'] . "',
			   '" . $_REQUEST ['name_tech_rs3'] . "',
			   '" . $_REQUEST ['name_tech_rs4'] . "',
			   '" . $_REQUEST ['name_tech_cp'] . "',
			   '" . $_REQUEST ['name_tech_max_lv'] . "',
			   '" . $_REQUEST ['name_tech_rs5'] . "');";
		$db->setQuery ( $sql );
		$db->query ();
		//Lấy id mới vừa được lưu vào
		$sql = "SELECT * FROM `wg_building_types` where name='" . $_REQUEST ['name_tech_building'] . "';";
		$db->setQuery ( $sql );
		$map_tech_save = null;
		$db->loadObject ( $map_tech_save );
		$map_tech_save_id = $map_tech_save->id;
	} else {
		$parse ['title_info'] = '';
		$parse ['info_'] = 'Đã có rồi!';
	}
	;
	//HIện lại trang thêm dữ liệu
	$template_tech = '/admin/manager_building_types_new';
}
;
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
$sql = "SELECT * FROM `wg_building_types`";
$db->setQuery ( $sql );
$map_tech_saves = null;
$map_tech_saves = $db->loadObjectList ();
$list = "";
foreach ( $map_tech_saves as $map_tech_save ) {
	$parse ['name_tech_id'] = $map_tech_save->id;
	$parse ['name_tech_building_r'] = $map_tech_save->name;
	$parse ['name_tech_rs1_r'] = $map_tech_save->rs1;
	$parse ['name_tech_rs2_r'] = $map_tech_save->rs2;
	$parse ['name_tech_rs3_r'] = $map_tech_save->rs3;
	$parse ['name_tech_rs4_r'] = $map_tech_save->rs4;
	$parse ['name_tech_rs5_r'] = $map_tech_save->workers;
	$parse ['name_tech_product_hour_r'] = $map_tech_save->product_hour;
	$parse ['name_tech_type_r'] = $map_tech_save->type;
	$parse ['name_tech_cp_r'] = $map_tech_save->cp;
	$parse ['name_tech_max_lv_r'] = $map_tech_save->max_level;
	$parse ['name_tech_des_r'] = $map_tech_save->des;
	$parse ['update_id_valu'] = $map_tech_save->id;
	$list .= parsetemplate ( $rows, $parse );
}
;
$parse ['list'] = $list;
$page = parsetemplate ( gettemplate ( $template_tech ), $parse );
displayAdmin ( $page );
?>