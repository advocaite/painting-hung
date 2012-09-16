<?php
ob_start ();
define ( 'INSIDE', true );
$ugamela_root_path = '../';
require_once ($ugamela_root_path . 'extension.inc');
require_once ('includes/db_connect.php');
require_once ('includes/common.php');

if (! check_user ()) {
	header ( "Location: login.php" );
}
if ($user ['authlevel'] != 5) {
	header ( "Location: login.php" );
}

global $db, $lang;

$reportAttackId = $_GET ['raid'];

$sql = "SELECT title,time,report_text FROM wg_reports WHERE id = ".$reportAttackId. " AND type = 1";
$db->setQuery ( $sql );
$row = null;
$db->loadObject ( $row );
if ($row) {
	$parse ['title_de'] = $row->title;
	$parse ['time_de'] = $row->time;
	$parse ['_report_detail'] = $row->report_text;
}
$page = parsetemplate ( gettemplate ( '/admin/report_attack_popup_detail' ), $parse );
displayAdminReportPopup ( $page, $lang ['tracking'] );
?>