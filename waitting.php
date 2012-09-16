<?php
define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/func_build.php');
global $db;
$sql="SELECT config_value FROM wg_config WHERE config_name='time_waiting'";
$db->setQuery($sql);
$query = null;
$db->loadObject($query);
$time_end=strtotime($query->config_value);
$time_wait=$time_end-time();
if($time_wait>0)
{		
	$date=0;
	while($time_wait>86400)
	{
		$time_wait=$time_wait-86400;
		$date++;
	}
	$parse['date']=$date;
	$parse['time']=ReturnTime($time_wait);
	$parse['times']=date("H:i:s",time());
	$page = parsetemplate(gettemplate('waitting'),$parse);
	display2($page,'');
}
else
{
	header("Location:index.php");
}
?>