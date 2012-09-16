<?php
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
if(!check_user()){ header("Location: index.php"); }
global $db;
setcookie('COOKIE_NAME', NULL, time()-100000, "/", "", 0);
setcookie('villa_id_cookie',"", time()-100000, "/", "", 0);
for($i=1;$i<38;$i++)
{
	setcookie('UpdateBuilding'.$i.'','',time()-100000, "/", "", 0);
}
$_SESSION['password_viewuser']=NULL;
$_SESSION['username'] =NULL;
$_SESSION['userid'] =NULL;
ob_end_flush();
?>
<html>
<body>
<script type="text/javascript">
window.close();
</script>
</body>
</html>

