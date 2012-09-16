<?php
defined( 'INSIDE' ) or die( 'Restricted access' );
	global $db;
	require($ugamela_root_path.'/config.php');
	require_once($ugamela_root_path."/libs/database.php");	
	
	$db = new og_database( $dbsettings["server"],$dbsettings["user"], $dbsettings["pass"], $dbsettings["name"], $dbsettings["prefix"] );
	if ($db->getErrorNum()) {
		echo "can not connect database";
		exit();
	}
	$db->debug( $conf->debug );

?>
