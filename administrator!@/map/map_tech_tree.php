<?php // --- by mkthanh
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
includeLang ( 'map_tech_tree' );

//Chuyển ngôn ngữ đó ra ngoài
$parse = $lang;
$parse ['title_info'] = 'style="display:none"';
global $db;
//Trang mặc định là trang Views--------------------------------------------------------------------
$template_tech = '/admin/map_tech_tree_views';
//Nhận các công trình cho res----------------------------------------------------------------------
if ($_REQUEST ['select_tech']) {
	$arrs = $_REQUEST ['checkbox'];
	$_REQUEST ['name_tech_res'] = "";
	if (isset ( $arrs )) {
		foreach ( $arrs as $arr ) {
			$sql = "SELECT id from wg_tech_tree where building_type_id='" . $arr . "' and level='" . $_REQUEST [$arr . "_" . count_level_building_type ( $arr )] . "';";
			$db->setQuery ( $sql );
			$map_tech_res_new = null;
			$db->loadObject ( $map_tech_res_new );
			$_REQUEST ['name_tech_res'] .= $map_tech_res_new->id . ',';
		}
	}
	$template_tech = '/admin/map_tech_tree_save';
}
$parse ['name_tech_building_valu'] = '<select id="name_tech_building" name="name_tech_building">';
$sql = "SELECT * FROM `wg_building_types`";
$db->setQuery ( $sql );
$map_tech_saves = null;
$map_tech_saves = $db->loadObjectList ();
foreach ( $map_tech_saves as $map_tech_save ) {
	if ($map_tech_save->id == $_REQUEST ['name_tech_building'])
		$parse ['name_tech_building_valu'] .= '<option value="' . $map_tech_save->id . '" selected="selected">' . $map_tech_save->name . '</option>';
	else
		$parse ['name_tech_building_valu'] .= '<option value="' . $map_tech_save->id . '">' . $map_tech_save->name . '</option>';
}
;
$parse ['name_tech_building_valu'] .= '</select >';

$parse ['name_tech_level_valu'] = $_REQUEST ['name_tech_level'];
$parse ['name_tech_res_valu'] = $_REQUEST ['name_tech_res'];
$parse ['name_tech_rs1_valu'] = $_REQUEST ['name_tech_rs1'];
$parse ['name_tech_rs2_valu'] = $_REQUEST ['name_tech_rs2'];
$parse ['name_tech_rs3_valu'] = $_REQUEST ['name_tech_rs3'];
$parse ['name_tech_rs4_valu'] = $_REQUEST ['name_tech_rs4'];
$parse ['name_tech_rs5_valu'] = $_REQUEST ['name_tech_rs5'];
$parse ['name_tech_type_valu'] = $_REQUEST ['name_tech_type'];
$parse ['name_tech_des_valu'] = $_REQUEST ['name_tech_des'];
$parse ['name_tech_product_hour_valu'] = $_REQUEST ['name_tech_product_hour'];
$parse ['update_ok_id_valu'] = $_REQUEST ['update_ok_id'];
$parse ['list'] = "";
//Nhận các công trình cho res UPDATE----------------------------------------------------------------------
if ($_REQUEST ['select_tech_update']) {
	$sql = "SELECT name from wg_building_types where id='" . $_REQUEST ['name_tech_building'] . "';";
	$db->setQuery ( $sql );
	$map_tech_res_new = null;
	$db->loadObject ( $map_tech_res_new );
	
	$parse ['name_tech_building_valu'] = $map_tech_res_new->name;
	
	$arrs = $_REQUEST ['checkbox'];
	$parse ['name_tech_res_valu'] = "";
	if (isset ( $arrs )) {
		foreach ( $arrs as $arr ) {
			$sql = "SELECT id from wg_tech_tree where building_type_id='" . $arr . "' and level='" . $_REQUEST [$arr . "_" . count_level_building_type ( $arr )] . "';";
			$db->setQuery ( $sql );
			$map_tech_res_new = null;
			$db->loadObject ( $map_tech_res_new );
			$parse ['name_tech_res_valu'] .= $map_tech_res_new->id . ',';
		}
	}
	$template_tech = '/admin/map_tech_tree_update';
}
//Các hàm liên kết trang---------------------------------------------------------------------------
if ($_REQUEST ['new']) {
	$parse ['name_tech_building_valu'] = '<select id="name_tech_building" name="name_tech_building">';
	$sql = "SELECT * FROM `wg_building_types`";
	$db->setQuery ( $sql );
	$map_tech_saves = null;
	$map_tech_saves = $db->loadObjectList ();
	foreach ( $map_tech_saves as $map_tech_save ) {
		$parse ['name_tech_building_valu'] .= '<option value="' . $map_tech_save->id . '">' . $map_tech_save->name . '</option>';
	}
	;
	$parse ['name_tech_building_valu'] .= '</select >';
	$template_tech = '/admin/map_tech_tree_save';
}
;
if ($_REQUEST ['update_f']) {
	//Lấy thông tin cần sửa---------------------------------------------------------------------------------
	$sql = "SELECT *,wg_tech_tree.id as mkid
				FROM `wg_building_types` , `wg_tech_tree`
				WHERE wg_building_types.id = wg_tech_tree.building_type_id and wg_tech_tree.id='" . $_REQUEST ['update_id'] . "';";
	$db->setQuery ( $sql );
	$map_tech_update = null;
	$db->loadObject ( $map_tech_update );
	//Hiển thị ra ngoài----------------------------------------------------------------------------------------------------
	$parse ['name_tech_building_valu'] = $map_tech_update->name;
	$parse ['name_tech_level_valu'] = $map_tech_update->level;
	$parse ['name_tech_res_valu'] = $map_tech_update->requirement;
	$parse ['name_tech_rs1_valu'] = $map_tech_update->rs1;
	$parse ['name_tech_rs2_valu'] = $map_tech_update->rs2;
	$parse ['name_tech_rs3_valu'] = $map_tech_update->rs3;
	$parse ['name_tech_rs4_valu'] = $map_tech_update->rs4;
	$parse ['name_tech_rs5_valu'] = $map_tech_update->workers;
	$parse ['name_tech_type_valu'] = $map_tech_update->type;
	$parse ['name_tech_des_valu'] = $map_tech_update->des;
	$parse ['name_tech_product_hour_valu'] = $map_tech_update->product_hour;
	$parse ['update_ok_id_valu'] = $_REQUEST ['update_id'];
	
	$template_tech = '/admin/map_tech_tree_update';
}
;
//Trong New chọn requirement
if ($_REQUEST ['name_tech_res_check']) {
	//Dùng cho template map_tech_tree_select.tpl------------------------------------------------------
	$rows_select = gettemplate ( '/admin/map_tech_tree_select_rows' );
	//Liệt danh sách các tech tree
	$sql = "SELECT *,wg_tech_tree.id as mkid,wg_building_types.id as building_id
FROM `wg_building_types` , `wg_tech_tree`
WHERE wg_building_types.id = wg_tech_tree.building_type_id group by building_type_id;";
	$db->setQuery ( $sql );
	$map_tech_saves = null;
	$map_tech_saves = $db->loadObjectList ();
	$list_select = "";
	foreach ( $map_tech_saves as $map_tech_save ) {
		$parse ['name_tech_id'] = $map_tech_save->building_id;
		$parse ['name_tech_building_r'] = $map_tech_save->name;
		$parse ['name_tech_level_r'] = combox_level_building_type ( $map_tech_save->building_id, $map_tech_save->building_id . "_" . count_level_building_type ( $map_tech_save->building_id ) );
		$parse ['name_tech_res_r'] = $map_tech_save->requirement;
		$list_select .= parsetemplate ( $rows_select, $parse );
	}
	;
	$parse ['list_select'] = $list_select;
	
	$template_tech = '/admin/map_tech_tree_select';
}
;
//Chọn requirement cho Update
if ($_REQUEST ['name_tech_res_check_update']) {
	//Dùng cho template map_tech_tree_select.tpl------------------------------------------------------
	$rows_select = gettemplate ( '/admin/map_tech_tree_select_update_rows' );
	//Liệt danh sách các tech tree
	$sql = "SELECT *,wg_tech_tree.id as mkid,wg_building_types.id as building_id
		FROM `wg_building_types` , `wg_tech_tree`
		WHERE wg_building_types.id = wg_tech_tree.building_type_id group by building_type_id;";
	$db->setQuery ( $sql );
	$map_tech_saves = null;
	$map_tech_saves = $db->loadObjectList ();
	$list_select = "";
	foreach ( $map_tech_saves as $map_tech_save ) {
		$parse ['name_tech_id'] = $map_tech_save->building_id;
		$parse ['name_tech_building_r'] = $map_tech_save->name;
		$parse ['name_tech_level_r'] = combox_level_building_type ( $map_tech_save->building_id, $map_tech_save->building_id . "_" . count_level_building_type ( $map_tech_save->building_id ) );
		$parse ['name_tech_res_r'] = $map_tech_save->requirement;
		$list_select .= parsetemplate ( $rows_select, $parse );
	}
	;
	$parse ['list_select'] = $list_select;
	
	$template_tech = '/admin/map_tech_tree_select_update';
}
;
//Sửa một công trình------------------------------------------------------------------------------
if ($_REQUEST ['update']) {
	//Sửa wg_tech_tree ------------------------------------------------------------------
	$sql = "UPDATE `wg_tech_tree` 
		SET 
		`level` = '" . $_REQUEST ['name_tech_level'] . "',
		`requirement` = '" . $_REQUEST ['name_tech_res'] . "' 
		WHERE `wg_tech_tree`.`id` ='" . $_REQUEST ['update_ok_id'] . "'";
	$db->setQuery ( $sql );
	$db->query ();
}
;
//Thêm mới 1 công trình----------------------------------------------------------------------------
if ($_REQUEST ['save']) {
	//Thêm thông tin mới vào bảng wg_tech_tree------------------------------------------------
	//Kiểm tra dữ liệu thêm vào đã có chưa
	$sql = "SELECT * FROM `wg_tech_tree` 
			where building_type_id='" . $_REQUEST ['name_tech_building'] . "'
			and level='" . $_REQUEST ['name_tech_level'] . "';";
	$db->setQuery ( $sql );
	$map_tech_save = null;
	$db->loadObject ( $map_tech_save );
	if (! $map_tech_save->id) {
		$sql = "
				INSERT INTO `wg_tech_tree` (`id`,`building_type_id`,`level`,`requirement`)
				VALUES (
				NULL , '" . $_REQUEST ['name_tech_building'] . "', '" . $_REQUEST ['name_tech_level'] . "', '" . $_REQUEST ['name_tech_res'] . "'
						);";
		$db->setQuery ( $sql );
		$db->query ();
		$parse ['info_'] = "Thêm thành công";
	} else {
		$parse ['title_info'] = '';
		$parse ['info_'] = 'Đã có rồi!';
	}
	;
	//HIện lại trang thêm dữ liệu
	$template_tech = '/admin/map_tech_tree_save';
}
;
//Thêm mới 9,14,19 công trình----------------------------------------------------------------------------
if ($_REQUEST['save_10']) {
	echo $_REQUEST ['name_tech_building']."mkthanh";
	$sql = "SELECT * FROM `wg_building_types` where id='" . $_REQUEST ['name_tech_building'] . "';";
	$db->setQuery ( $sql );
	$map_tech_save = null;
	$db->loadObject ( $map_tech_save );
	$flash_save_building=$map_tech_save->max_level;
	for($i = 2; $i <= $flash_save_building; $i ++) {
		//Kiểm tra xem tên làng mới này có trong 
		$sql = "SELECT * FROM `wg_building_types` where id='" . $_REQUEST ['name_tech_building'] . "';";
		$db->setQuery ( $sql );
		$map_tech_save = null;
		$db->loadObject ( $map_tech_save );
		$map_tech_save_id = $map_tech_save->id;
		if (! $map_tech_save->name) {
			//Nếu tên đã có thì lấy id trong wg_building_types lưu vào building_type_id trong wg_tech_tree
			$sql = "INSERT INTO `wg_building_types` (`id`,`name`,`des`,`product_hour`,`type`,`rs1`,`rs2`,`rs3`,`rs4`,`workers`)
						VALUES (
						NULL , '" . $_REQUEST ['name_tech_building'] . "',
						 '" . $_REQUEST ['name_tech_des'] . "',
						  '" . $_REQUEST ['name_tech_product_hour'] . "',
						   '" . $_REQUEST ['name_tech_type'] . "',
						   '" . $_REQUEST ['name_tech_rs1'] . "',
						   '" . $_REQUEST ['name_tech_rs2'] . "',
						   '" . $_REQUEST ['name_tech_rs3'] . "',
						   '" . $_REQUEST ['name_tech_rs4'] . "',
						   '" . $_REQUEST ['name_tech_rs5'] . "');";
			$db->setQuery ( $sql );
			$db->query ();
			//Lấy id mới vừa được lưu vào
			$sql = "SELECT * FROM `wg_building_types` where name='" . $_REQUEST ['name_tech_building'] . "';";
			$db->setQuery ( $sql );
			$map_tech_save = null;
			$db->loadObject ( $map_tech_save );
			$map_tech_save_id = $map_tech_save->id;
		}
		;
		//Thêm thông tin mới vào bảng wg_tech_tree------------------------------------------------
		//Kiểm tra dữ liệu thêm vào đã có chưa
		$sql = "SELECT * FROM `wg_tech_tree` 
						where building_type_id='" . $_REQUEST ['name_tech_building'] . "'
						and level='" . $i . "';";
		$db->setQuery ( $sql );
		$map_tech_save = null;
		$db->loadObject ( $map_tech_save );
		if (! $map_tech_save->id) {
			$sql = "
							INSERT INTO `wg_tech_tree` (`id`,`building_type_id`,`level`,`requirement`)
							VALUES (
							NULL , '" . $_REQUEST ['name_tech_building'] . "', '" . $i . "', '" . $_REQUEST ['name_tech_res'] . "');";
			$db->setQuery ( $sql );
			$db->query ();
		} else {
			$parse ['title_info'] = '';
			$parse ['info_'] = 'Đã có rồi!';
		}
		;
	}
}
;
//Xoá 1 or nhiều công trình------------------------------------------------------------------------
if ($_REQUEST ['delete']) {
	$arrs = $_REQUEST ['checkbox'];
	if (isset ( $arrs )) {
		foreach ( $arrs as $arr ) {
			//echo "abc";
			$sql = "delete from wg_tech_tree where building_type_id='" . $arr . "' and level='" . $_REQUEST [$arr . "_" . count_level_building_type ( $arr )] . "'";
			$db->setQuery ( $sql );
			$db->query ();
		}
		//message($lang['title_delete_tech'],$lang['info_delete_tech'],"map_tech_tree.".$phpEx);
	}
	//message($lang['value_id'],$lang['title_delete'],"map_tech_tree.".$phpEx);
}
;
if ($template_tech == '/admin/map_tech_tree_views') {
	//Trỏ đến template map_tech_tree_rows
	$rows = gettemplate ( '/admin/map_tech_tree_rows' );
	//kết nối đến template trong templates/OpenGame/admin/manager_map.tpl
	$sql = "SELECT *,wg_tech_tree.id as mkid,wg_building_types.id as building_id
		FROM `wg_building_types` , `wg_tech_tree`
		WHERE wg_building_types.id = wg_tech_tree.building_type_id group by building_type_id;";
	$db->setQuery ( $sql );
	$map_tech_saves = null;
	$map_tech_saves = $db->loadObjectList ();
	$list = "";
	foreach ( $map_tech_saves as $map_tech_save ) {
		$parse ['name_tech_id'] = $map_tech_save->building_id;
		$parse ['name_tech_building_r'] = $map_tech_save->name;
		$parse ['name_tech_level_r'] = combox_level_building_type ( $map_tech_save->building_id, $map_tech_save->building_id . "_" . count_level_building_type ( $map_tech_save->building_id ) );
		$parse ['name_tech_res_r'] = $map_tech_save->requirement;
		$parse ['name_tech_rs1_r'] = $map_tech_save->rs1;
		$parse ['name_tech_rs2_r'] = $map_tech_save->rs2;
		$parse ['name_tech_rs3_r'] = $map_tech_save->rs3;
		$parse ['name_tech_rs4_r'] = $map_tech_save->rs4;
		$parse ['name_tech_rs5_r'] = $map_tech_save->workers;
		$parse ['update_id_valu'] = $map_tech_save->mkid;
		$list .= parsetemplate ( $rows, $parse );
	}
	;
	$parse ['list'] = $list;
}
;
$page = parsetemplate ( gettemplate ( $template_tech ), $parse );
displayAdmin ( $page );
function count_level_building_type($id_building) //Vào tech tree group by toàn bộ những id này và điếm level
{
	global $db;
	$sql = " SELECT count( building_type_id ) as id FROM `wg_tech_tree` WHERE `building_type_id` = '" . $id_building . "' ;";
	$db->setQuery ( $sql );
	$map_tech_saves = null;
	$db->loadObject ( $map_tech_saves );
	return $map_tech_saves->id;
}
function combox_level_building_type($id_building, $id_combobox) //Vào tech tree group by toàn bộ những id này và điếm level chuyển thành combobox
{
	global $db;
	$sql = " SELECT level FROM `wg_tech_tree` WHERE `building_type_id` = '" . $id_building . "' order by level;";
	$db->setQuery ( $sql );
	$map_tech_saves = null;
	$map_tech_saves = $db->loadObjectList ();
	$xuat = '<select id="' . $id_combobox . '" name="' . $id_combobox . '" >';
	foreach ( $map_tech_saves as $map_tech_save ) {
		$xuat .= '<option>' . $map_tech_save->level . '</option>';
	}
	;
	$xuat .= '</select>';
	return $xuat;
}
?>