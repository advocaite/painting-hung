<?php
define ( 'INSIDE', true );


if($_GET['id']==1)
{
	include("../templates/OpenGame/admin/badlist_popup.html");
}
elseif($_GET['id']==2)
{
	$ugamela_root_path = '../';
	include ($ugamela_root_path . 'extension.inc');
	include ('includes/db_connect.'. $phpEx);
	include ('includes/common.'. $phpEx);
	$parse['username']=$_GET['username'];
	$page = parsetemplate(gettemplate('/admin/message_popup'), $parse);
	display2($page,$lang['tracking']);
}
elseif($_GET['id']==3)
{
	include("../templates/OpenGame/admin/badlist_popup1.html");
}
else
{
	include("../templates/OpenGame/admin/userlist_popup.html");
}
?>