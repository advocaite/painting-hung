<?php
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.'.$phpEx);
global $lang,$user;
//Time_Wait(time());
includeLang('home');
$parse=$lang;
global $game_config;
$parse['title']=$game_config['game_name'];
$page = parsetemplate(gettemplate('intro1'),$parse); 
display2($page,'');
ob_end_flush();

?>

