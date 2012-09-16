<?php
ob_start(); 
define('INSIDE', true);

$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
	
if(!insertFeature($_SESSION['username_admin'],"Logout Admin"))
{
	globalError2('Error!!!Logout');
}
setcookie($game_config['COOKIE_NAME'], NULL, time()-100000, "/", "", 0);

session_destroy();
//forward den trang yeu cau
$st_forward = $_REQUEST['forward'];
if($st_forward!='')
{
	header("Location: $st_forward"); 
}
else
{
	header("Location: login.php"); 
}
ob_end_flush();

?>
