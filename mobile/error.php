<?php
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
includeLang('home');
global $lang;
$parse=$lang;
$page = parsetemplate(gettemplate('body_error'),$parse); 
display2($page,'');
ob_end_flush();

?>

