
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
includeLang ( 'manager_type_villa' );

//Chuyển ngôn ngữ đó ra ngoài
$parse = $lang;
global $db;
$sql = "select * from wg_game_configs where name='villa_1';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_1_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_2';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_2_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_3';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_3_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_4';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_4_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_5';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_5_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_6';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_6_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_7';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_7_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_8';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_8_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_9';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_9_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_10';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_10_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_11';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_11_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_12';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_12_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_13';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_13_valu"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='villa_14';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );
$parse ["value_14_valu"] = $map_manager->value;
if ($_REQUEST ["send"]) {
	if ($_REQUEST ['villa_1'] != $parse ["villa_1_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_1'] . "' where name='villa_1';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 1<br>";
	}
	if ($_REQUEST ['villa_2'] != $parse ["villa_2_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_2'] . "' where name='villa_2';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 2<br>";
	}
	if ($_REQUEST ['villa_3'] != $parse ["villa_3_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_3'] . "' where name='villa_3';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 3<br>";
	}
	if ($_REQUEST ['villa_4'] != $parse ["villa_4_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_4'] . "' where name='villa_4';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 4<br>";
	}
	if ($_REQUEST ['villa_5'] != $parse ["villa_5_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_5'] . "' where name='villa_5';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 5<br>";
	}
	if ($_REQUEST ['villa_6'] != $parse ["villa_6_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_6'] . "' where name='villa_6';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 6<br>";
	}
	if ($_REQUEST ['villa_7'] != $parse ["villa_7_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_7'] . "' where name='villa_7';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 7<br>";
	}
	if ($_REQUEST ['villa_8'] != $parse ["villa_8_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_8'] . "' where name='villa_8';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 8<br>";
	}
	if ($_REQUEST ['villa_9'] != $parse ["villa_9_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_9'] . "' where name='villa_9';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 9<br>";
	}
	if ($_REQUEST ['villa_10'] != $parse ["villa_10_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_10'] . "' where name='villa_10';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 10<br>";
	}
	if ($_REQUEST ['villa_11'] != $parse ["villa_11_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_11'] . "' where name='villa_11';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 11<br>";
	}
	if ($_REQUEST ['villa_1'] != $parse ["villa_12_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_12'] . "' where name='villa_12';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 12<br>";
	}
	if ($_REQUEST ['villa_13'] != $parse ["villa_13_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_13'] . "' where name='villa_13';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 13<br>";
	}
	if ($_REQUEST ['villa_14'] != $parse ["villa_14_valu"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['villa_14'] . "' where name='villa_14';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa Làng loại 14<br>";
	}
	if (! $lang ['value_id'])
		$lang ['value_id'] = "Bạn chưa thay đổi gì!";
	message ( $lang ['value_id'], $lang ['title_update'], "manager_type_villa." . $phpEx );
}
//$parse["value_max_y"]=$map_manager->map_max_y;
//kết nối đến template trong templates/OpenGame/admin/manager_map.tpl
$page = parsetemplate ( gettemplate ( '/admin/manager_type_villa' ), $parse );
displayAdmin ( $page );
?>