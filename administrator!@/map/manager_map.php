
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
includeLang ( 'manager_map' );

//Chuyển ngôn ngữ đó ra ngoài
$parse = $lang;
global $db;
$sql = "select * from wg_game_configs where name='max_y';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );

$parse ["value_max_y"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='max_x';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );

$parse ["value_max_x"] = $map_manager->value;

$sql = "select * from wg_game_configs where name='max_villa';";
$db->setQuery ( $sql );
$map_manager = null;
$db->loadObject ( $map_manager );

$parse ["value_max_villa"] = $map_manager->value;
if ($_REQUEST ["send"]) {
	if ($_REQUEST ['max_x'] != $parse ["value_max_x"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['max_x'] . "' where name='max_x';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa giới hạng Bảng đồ theo x<br>";
	}
	if ($_REQUEST ['max_y'] != $parse ["value_max_y"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['max_y'] . "' where name='max_y';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa giới hạng Bảng đồ theo y<br>";
	}
	if ($_REQUEST ['max_villa'] != $parse ["value_max_villa"]) {
		$sql = "update wg_game_configs set value='" . $_REQUEST ['max_villa'] . "' where name='max_villa';";
		$db->setQuery ( $sql );
		$db->query ();
		$lang ['value_id'] .= "Sửa giới hạng các Làng<br>";
	}
	if (! $lang ['value_id'])
		$lang ['value_id'] = "Bạn chưa thay đổi gì!";
	message ( $lang ['value_id'], $lang ['title_update'], "manager_map." . $phpEx );
}
//$parse["value_max_y"]=$map_manager->map_max_y;
//kết nối đến template trong templates/OpenGame/admin/manager_map.tpl
$page = parsetemplate ( gettemplate ( '/admin/manager_map' ), $parse );
displayAdmin ( $page );
?>