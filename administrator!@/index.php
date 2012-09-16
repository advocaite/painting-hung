<?php 
ob_start(); 

define ( 'INSIDE', true );

$ugamela_root_path = '../';

include ($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include ('includes/common.' . $phpEx);
include ('includes/func_security.' . $phpEx);

if (! check_user ()) {header("Location: login.php");}


/* xoa bot du lieu old trong bang chat */
$sql="DELETE FROM wg_wtagshoutbox WHERE date_post!=".date('d')."";
$db->setQuery($sql);
$db->query();
/* Hien them vao */	
$parse=$lang;
$page = parsetemplate(gettemplate('admin/index_body'), $parse);
displayAdmin($page,$lang['security']);
ob_end_flush();
?>