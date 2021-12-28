<?php
defined ('INSIDE') or die('Restricted access');
use eftec\PdoOne;
global $db;
require ($ugamela_root_path . '/config.php');
// mysql
$db=new PdoOne("mysql",$dbsettings["server"],$dbsettings["user"],$dbsettings["pass"],$dbsettings["name"],"");
//$db->logLevel=3; // it is for debug purpose and it works to find problems.
$db->connect();
?>
