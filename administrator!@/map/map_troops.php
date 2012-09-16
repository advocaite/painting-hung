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
includeLang ( 'map_troops' );

//Chuyển ngôn ngữ đó ra ngoài
$parse = $lang;
global $db;
//Trang mặc định là trang Views--------------------------------------------------------------------
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
$parse ['valu_mana'] = '';
$parse ['valu_speed'] = '';
$parse ['valu_carry'] = '';
$parse ['valu_nation_id'] = '';
$parse ['valu_rs1'] = '';
$parse ['valu_rs2'] = '';
$parse ['valu_rs3'] = '';
$parse ['valu_rs4'] = '';
$parse ['valu_time_train']='';
$parse ['valu_keep_hour'] = '';
$parse ['valu_image']='';
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

//Xoá 1 or nhiều troops---------------------------------------------------------------------------------
if ($_REQUEST ['delete']) {
	$arrs = $_REQUEST ['checkbox'];
	if (isset ( $arrs )) {
		foreach ( $arrs as $arr ) {
			//echo "abc";
			$sql = "delete from wg_troops where id='" . $arr . "'";
			$db->setQuery ( $sql );
			$db->query ();
		}
	}
	message ( $lang ['info_delete'], $lang ['title_delete'], "map_troops." . $phpEx );
}
// Nếu là thêm mới-----------------------------------------------------------------------------
if ($_REQUEST ['new'] || $_REQUEST ['reset']) {
	$parse ['valu_name'] = '';
	$parse ['valu_attack'] = '';
	$parse ['valu_melee_defense'] = '';
	$parse ['valu_ranger_defense'] = '';
	$parse ['valu_magic_defense'] = '';
	$parse ['valu_hitpoint'] = '';
	$parse ['valu_mana'] = '';
	$parse ['valu_speed'] = '';
	$parse ['valu_carry'] = '';
	$parse ['valu_nation_id'] = '';
	$parse ['valu_rs1'] = '';
	$parse ['valu_rs2'] = '';
	$parse ['valu_rs3'] = '';
	$parse ['valu_rs4'] = '';
	$parse ['valu_keep_hour'] = '';
	$parse ['valu_requirement'] = '';
	$template_tech = '/admin/map_troops_save';
}
//Nếu là chọn requirement
if ($_REQUEST ['save_check']) {
	//Nạp những giá trị đã nhập vào 1 dãy biến tạm
	$parse ['valu_name'] = $_REQUEST ['valu_name'];
	$parse ['valu_attack'] = $_REQUEST ['valu_attack'];
	$parse ['valu_melee_defense'] = $_REQUEST ['valu_melee_defense'];
	$parse ['valu_ranger_defense'] = $_REQUEST ['valu_ranger_defense'];
	$parse ['valu_magic_defense'] = $_REQUEST ['valu_magic_defense'];
	$parse ['valu_hitpoint'] = $_REQUEST ['valu_hitpoint'];
	$parse ['valu_mana'] = $_REQUEST ['valu_mana'];
	$parse ['valu_speed'] = $_REQUEST ['valu_speed'];
	$parse ['valu_carry'] = $_REQUEST ['valu_carry'];
	$parse ['valu_nation_id'] = $_REQUEST ['valu_nation_id'];
	$parse ['valu_rs1'] = $_REQUEST ['valu_rs1'];
	$parse ['valu_rs2'] = $_REQUEST ['valu_rs2'];
	$parse ['valu_rs3'] = $_REQUEST ['valu_rs3'];
	$parse ['valu_rs4'] = $_REQUEST ['valu_rs4'];
	$parse ['valu_time_train'] = $_REQUEST['valu_time_train'];
	$parse ['valu_keep_hour'] = $_REQUEST ['valu_keep_hour'];
	//Trỏ đến template map_troops_save_check_rows.tpl
	$views_rows = gettemplate ( '/admin/map_troops_save_check_rows' );
	$sql = "SELECT * FROM `wg_building_types`";
	$db->setQuery ( $sql );
	$map_troop_views = null;
	$map_troop_views = $db->loadObjectList ();
	$views_list = "";
	foreach ( $map_troop_views as $map_troop_view ) {
		$parse ['name_tech_id'] = $map_troop_view->id;
		$parse ['valu_building_r'] = $map_troop_view->name;
		$parse ['valu_building_level_r'] = combox_level_building_type ( $map_troop_view->id, $map_troop_view->id . "_" . count_level_building_type ( $map_troop_view->id ) ); 	 	
		$views_list .= parsetemplate ( $views_rows, $parse );
	}
	;
	$parse ['views_list'] = $views_list;
	$template_tech = '/admin/map_troops_save_check';
}
//Sau khi chọn Building Type ok_check_save
if ($_REQUEST ['ok_check_save']) {
	//Nạp lại các giá trị đã lưu
	$parse ['valu_name'] = $_REQUEST ['valu_name'];
	$parse ['valu_attack'] = $_REQUEST ['valu_attack'];
	$parse ['valu_melee_defense'] = $_REQUEST ['valu_melee_defense'];
	$parse ['valu_ranger_defense'] = $_REQUEST ['valu_ranger_defense'];
	$parse ['valu_magic_defense'] = $_REQUEST ['valu_magic_defense'];
	$parse ['valu_hitpoint'] = $_REQUEST ['valu_hitpoint'];
	$parse ['valu_mana'] = $_REQUEST ['valu_mana'];
	$parse ['valu_speed'] = $_REQUEST ['valu_speed'];
	$parse ['valu_carry'] = $_REQUEST ['valu_carry'];
	$parse ['valu_nation_id'] = $_REQUEST ['valu_nation_id'];
	$parse ['valu_rs1'] = $_REQUEST ['valu_rs1'];
	$parse ['valu_rs2'] = $_REQUEST ['valu_rs2'];
	$parse ['valu_rs3'] = $_REQUEST ['valu_rs3'];
	$parse ['valu_rs4'] = $_REQUEST ['valu_rs4'];
	$parse ['valu_time_train'] = $_REQUEST['valu_time_train'];
	$parse ['valu_keep_hour'] = $_REQUEST ['valu_keep_hour'];
	$arrs = $_REQUEST ['checkbox'];
	$parse ['valu_requirement'] = "";
	if (isset ( $arrs )) {
		foreach ( $arrs as $arr ) {
			$parse ['valu_requirement'] .= $arr . ',' . $_REQUEST [$arr . "_" . count_level_building_type ( $arr )] . ';';
		}
	}
	$template_tech = '/admin/map_troops_save';
}
//Nếu là save
if ($_REQUEST ['save']) {
	//Kiểm tra dữ liệu thêm vào đã có chưa
	$sql = "SELECT * FROM wg_troops where name='" . $_REQUEST ['valu_name'] . "';";
	$db->setQuery ( $sql );
	$map_tech_save = null;
	$db->loadObject ( $map_tech_save );
	if (! $map_tech_save->id) {
		$sql = "
				INSERT INTO `wg_troops` (`id`,`name`,`type`,`attack`,`melee_defense`,`ranger_defense`,`magic_defense`,`hitpoint`,`mana`,`speed`,`carry`,`nation_id`,`rs1`,`rs2`,`rs3`,`rs4`,`time_train`,`keep_hour`,`requirement`,`image`,`icon`,`building_type_id`)
				VALUES (
						'',
						'" . $_REQUEST ['valu_name'] . "', 	
						'" . $_REQUEST ['valu_type'] . "',  	 	 	 	 	 
						'" . $_REQUEST ['valu_attack'] . "',	 	
						'" . $_REQUEST ['valu_melee_defense'] . "',	 	
						'" . $_REQUEST ['valu_ranger_defense'] . "',
						'" . $_REQUEST ['valu_magic_defense'] . "',
						'" . $_REQUEST ['valu_hitpoint'] . "',
						'" . $_REQUEST ['valu_mana'] . "',
						'" . $_REQUEST ['valu_speed'] . "',
						'" . $_REQUEST ['valu_carry'] . "',
						'" . $_REQUEST ['valu_nation'] . "',
						'" . $_REQUEST ['valu_rs1'] . "',
						'" . $_REQUEST ['valu_rs2'] . "',
						'" . $_REQUEST ['valu_rs3'] . "',
						'" . $_REQUEST ['valu_rs4'] . "',
						'" . $_REQUEST ['valu_time_train'] . "',
						'" . $_REQUEST ['valu_keep_hour'] . "',
						'" . $_REQUEST ['valu_requirement'] . "',
						'" . $HTTP_POST_FILES['valu_image']['name'] . "',
						'" . $HTTP_POST_FILES['valu_icon']['name'] . "',
						'" . $_REQUEST ['valu_building_type'] . "'
				);
					
				";
		$db->setQuery ( $sql );
		$db->query ();
		//move_uploaded_file()
		$des_troops='';
		if($_REQUEST['valu_type']==1)
			$des_troops='troops_1/';
		if($_REQUEST['valu_type']==2)
			$des_troops='troops_2/';
		if($_REQUEST['valu_type']==3)
			$des_troops='troops_3/';
		move_uploaded_file($HTTP_POST_FILES['valu_icon']['tmp_name'],"../../images/troops/".$des_troops.$HTTP_POST_FILES['valu_icon']['name']);
		move_uploaded_file($HTTP_POST_FILES['valu_image']['tmp_name'],"../../images/troops/".$des_troops.$HTTP_POST_FILES['valu_image']['name']);
		$parse ['info_'] = "Thêm thành công";
		$parse ['valu_name'] = '';
		$parse ['valu_attack'] = '';
		$parse ['valu_melee_defense'] = '';
		$parse ['valu_ranger_defense'] = '';
		$parse ['valu_magic_defense'] = '';
		$parse ['valu_hitpoint'] = '';
		$parse ['valu_mana'] = '';
		$parse ['valu_speed'] = '';
		$parse ['valu_carry'] = '';
		$parse ['valu_nation_id'] = '';
		$parse ['valu_rs1'] = '';
		$parse ['valu_rs2'] = '';
		$parse ['valu_rs3'] = '';
		$parse ['valu_rs4'] = '';
		$parse ['valu_keep_hour'] = '';
		$parse ['valu_requirement'] = '';
	} else {
		$parse ['title_info'] = '';
		$parse ['info_'] = 'Đã có rồi!';
	}
	;
	//Hiện lại trang thêm dữ liệu
	$template_tech = '/admin/map_troops_save';
}
//Hiện form update
if ($_REQUEST ['update_f']) {
	$sql = "SELECT * FROM `wg_troops` where id='" . $_REQUEST ['update_id'] . "';";
	$db->setQuery ( $sql );
	$map_troop_views = null;
	$db->loadObject ( $map_troop_views );
	//Nạp dữ liệu cần sữa
	$parse ['id_update_valu'] = $_REQUEST ['update_id'];
	$parse ['valu_name'] = $map_troop_views->name;
	$parse ['valu_attack'] = $map_troop_views->attack;
	$parse ['valu_melee_defense'] = $map_troop_views->melee_defense;
	$parse ['valu_ranger_defense'] = $map_troop_views->ranger_defense;
	$parse ['valu_magic_defense'] = $map_troop_views->magic_defense;
	$parse ['valu_hitpoint'] = $map_troop_views->hitpoint;
	$parse ['valu_mana'] = $map_troop_views->mana;
	$parse ['valu_speed'] = $map_troop_views->speed;
	$parse ['valu_carry'] = $map_troop_views->carry;
	$parse ['valu_nation_id'] = $map_troop_views->nation_id;
	$parse ['valu_rs1'] = $map_troop_views->rs1;
	$parse ['valu_rs2'] = $map_troop_views->rs2;
	$parse ['valu_rs3'] = $map_troop_views->rs3;
	$parse ['valu_rs4'] = $map_troop_views->rs4;
	$parse ['valu_time_train']=$map_troop_views->time_train;
	$parse ['valu_keep_hour'] = $map_troop_views->keep_hour;		
	$parse ['show_combo_type']='<select id="valu_type" name="valu_type">';
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
	$parse ['show_combo_nation']='<select id="valu_nation" name="valu_nation">';
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
	$parse ['show_combo_building_type']='<select id="valu_building_type" name="valu_building_type">';
	$selected_type='';
	foreach ($map_troop_types as $map_troop_type)
	{
		if($map_troop_views->building_type_id==$map_troop_type->id)
			$selected_type='selected="selected"';
		$parse ['show_combo_building_type'].='<option value="'.$map_troop_type->id.'" '.$selected_type.'>'.$map_troop_type->name.'</option>';
		$selected_type='';
	}
	$parse ['show_combo_building_type'].='</select>';
	//Hiện lại trang thêm dữ liệu
	$template_tech = '/admin/map_troops_update';

}
//Khi đồng ý update
if ($_REQUEST ['update_data']) {
	$des_troops='';
		if($_REQUEST['valu_type']==1)
			$des_troops='troops_1/';
		if($_REQUEST['valu_type']==2)
			$des_troops='troops_2/';
		if($_REQUEST['valu_type']==3)
			$des_troops='troops_3/';
		move_uploaded_file($HTTP_POST_FILES['valu_icon']['tmp_name'],"../../images/troops/".$des_troops.$HTTP_POST_FILES['valu_icon']['name']);
		move_uploaded_file($HTTP_POST_FILES['valu_image']['tmp_name'],"../../images/troops/".$des_troops.$HTTP_POST_FILES['valu_image']['name']);
	$sql = "UPDATE `wg_troops` SET 
		`name` = '" . $_REQUEST ['valu_name'] . "',
		`attack` = '" . $_REQUEST ['valu_attack'] . "',
		`melee_defense` = '" . $_REQUEST ['valu_melee_defense'] . "',
		`ranger_defense` = '" . $_REQUEST ['valu_ranger_defense'] . "',
		`magic_defense` = '" . $_REQUEST ['valu_magic_defense'] . "',
		`hitpoint` = '" . $_REQUEST ['valu_hitpoint'] . "',
		`mana` = '" . $_REQUEST ['valu_mana'] . "',
		`speed` = '" . $_REQUEST ['valu_speed'] . "',
		`carry` = '" . $_REQUEST ['valu_carry'] . "',
		`nation_id` = '" . $_REQUEST ['valu_nation_id'] . "',
		`rs1` = '" . $_REQUEST ['valu_rs1'] . "',
		`rs2` = '" . $_REQUEST ['valu_rs2'] . "',
		`rs3` = '" . $_REQUEST ['valu_rs3'] . "',
		`rs4` = '" . $_REQUEST ['valu_rs4'] . "',
		`time_train` = '" . $_REQUEST ['valu_time_train'] . "',
		`building_type_id` = '" . $_REQUEST ['valu_building_type']. "',
		`nation_id` = '" . $_REQUEST ['valu_nation']. "',
		`type` = '" . $_REQUEST ['valu_type']. "',
		`keep_hour` = '" . $_REQUEST ['valu_keep_hour'] . "',
		`requirement` = '" . $_REQUEST ['valu_requirement'] . "' WHERE `wg_troops`.`id` ='" . $_REQUEST ['id_update_valu'] . "'";
	$db->setQuery ( $sql );
	$db->query ();
}
//Nếu chọn thay đổi requirement cho update
if ($_REQUEST ['update_check']) {
	//Nạp những giá trị đã nhập vào 1 dãy biến tạm
	$parse ['id_update_valu'] = $_REQUEST ['id_update_valu'];
	$parse ['valu_name'] = $_REQUEST ['valu_name'];
	$parse ['valu_attack'] = $_REQUEST ['valu_attack'];
	$parse ['valu_melee_defense'] = $_REQUEST ['valu_melee_defense'];
	$parse ['valu_ranger_defense'] = $_REQUEST ['valu_ranger_defense'];
	$parse ['valu_magic_defense'] = $_REQUEST ['valu_magic_defense'];
	$parse ['valu_hitpoint'] = $_REQUEST ['valu_hitpoint'];
	$parse ['valu_mana'] = $_REQUEST ['valu_mana'];
	$parse ['valu_speed'] = $_REQUEST ['valu_speed'];
	$parse ['valu_carry'] = $_REQUEST ['valu_carry'];
	$parse ['valu_nation_id'] = $_REQUEST ['valu_nation_id'];
	$parse ['valu_rs1'] = $_REQUEST ['valu_rs1'];
	$parse ['valu_rs2'] = $_REQUEST ['valu_rs2'];
	$parse ['valu_rs3'] = $_REQUEST ['valu_rs3'];
	$parse ['valu_rs4'] = $_REQUEST ['valu_rs4'];
	$parse ['valu_keep_hour'] = $_REQUEST ['valu_keep_hour'];
	$parse ['valu_requirement'] = $_REQUEST ['valu_requirement'];
	//Trỏ đến template map_troops_save_check_rows.tpl
	$views_rows = gettemplate ( '/admin/map_troops_update_check_rows' );
	$sql = "SELECT * FROM `wg_building_types`";
	$db->setQuery ( $sql );
	$map_troop_views = null;
	$map_troop_views = $db->loadObjectList ();
	$views_list = "";
	foreach ( $map_troop_views as $map_troop_view ) {
		$parse ['name_tech_id'] = $map_troop_view->id;
		$parse ['valu_building_r'] = $map_troop_view->name;
		$parse ['valu_building_level_r'] = combox_level_building_type ( $map_troop_view->id, $map_troop_view->id . "_" . count_level_building_type ( $map_troop_view->id ) );
		//'<input id=valu_"'.$map_troop_view->id.'" name="valu_'.$map_troop_view->id.'" type="text"/>';	 	 	 	 	
		$views_list .= parsetemplate ( $views_rows, $parse );
	}
	;
	$parse ['views_list'] = $views_list;
	$template_tech = '/admin/map_troops_update_check';
}
//Nếu đồng ý với requirement đã chọn thì quay lại form Update
if ($_REQUEST ['ok_check_update']) {
	//Nạp dữ liệu vào form
	$parse ['id_update_valu'] = $_REQUEST ['id_update_valu'];
	$parse ['valu_name'] = $_REQUEST ['valu_name'];
	$parse ['valu_attack'] = $_REQUEST ['valu_attack'];
	$parse ['valu_melee_defense'] = $_REQUEST ['valu_melee_defense'];
	$parse ['valu_ranger_defense'] = $_REQUEST ['valu_ranger_defense'];
	$parse ['valu_magic_defense'] = $_REQUEST ['valu_magic_defense'];
	$parse ['valu_hitpoint'] = $_REQUEST ['valu_hitpoint'];
	$parse ['valu_mana'] = $_REQUEST ['valu_mana'];
	$parse ['valu_speed'] = $_REQUEST ['valu_speed'];
	$parse ['valu_carry'] = $_REQUEST ['valu_carry'];
	$parse ['valu_nation_id'] = $_REQUEST ['valu_nation_id'];
	$parse ['valu_rs1'] = $_REQUEST ['valu_rs1'];
	$parse ['valu_rs2'] = $_REQUEST ['valu_rs2'];
	$parse ['valu_rs3'] = $_REQUEST ['valu_rs3'];
	$parse ['valu_rs4'] = $_REQUEST ['valu_rs4'];
	$parse ['valu_keep_hour'] = $_REQUEST ['valu_keep_hour'];
	$arrs = $_REQUEST ['checkbox'];
	$parse ['valu_requirement'] = "";
	if (isset ( $arrs )) //Nếu c1o chọn giái trị trong requirement
{
		foreach ( $arrs as $arr ) {
			$parse ['valu_requirement'] .= $arr . ',' . $_REQUEST [$arr . "_" . count_level_building_type ( $arr )] . ';';
		}
	} else //Ngược lại nếu không có chọn giá trị tức lấy lại requirement cũ
{
		$parse ['valu_requirement'] = $_REQUEST ['valu_requirement'];
	}
	$template_tech = '/admin/map_troops_update';
}
//Hiển thị danh sách --------------------------------------------------------------------------
if ($template_tech == '/admin/map_troops_views') {
	//Trỏ đến template map_tech_tree_rows
	$views_rows = gettemplate ( '/admin/map_troops_views_rows' );
	//kết nối đến template trong templates/OpenGame/admin/manager_map.tpl
	$sql = "SELECT * FROM `wg_troops`";
	$db->setQuery ( $sql );
	$map_troop_views = null;
	$map_troop_views = $db->loadObjectList ();
	$views_list = "";
	foreach ( $map_troop_views as $map_troop_view ) {
		$parse ['name_tech_id'] = $map_troop_view->id;
		$parse ['valu_name'] = $map_troop_view->name;
		$parse ['valu_attack'] = $map_troop_view->attack;
		$parse ['valu_melee_defense'] = $map_troop_view->melee_defense;
		$parse ['valu_ranger_defense'] = $map_troop_view->ranger_defense;
		$parse ['valu_magic_defense'] = $map_troop_view->magic_defense;
		$parse ['valu_hitpoint'] = $map_troop_view->hitpoint;
		$parse ['valu_mana'] = $map_troop_view->mana;
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
		$views_list .= parsetemplate ( $views_rows, $parse );
	}
	;
	$parse ['views_list'] = $views_list;
}
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