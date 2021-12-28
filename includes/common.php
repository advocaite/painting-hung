<?php

if (!defined('INSIDE'))
{
    die("Hacking attempt");
}

session_start();

define('VERSION', '8.1a');

error_reporting(E_ERROR | E_WARNING | E_PARSE);



extract($_POST, EXTR_SKIP);

extract($_GET, EXTR_SKIP);

extract($_COOKIE, EXTR_SKIP);

$game_config = array ();

$user = array ();

$theme = array ();

$images = array ();

$lang = array ();

$base_url
    = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/', 0));

//

// Constantes

//

define('TEMPLATE_DIR', "templates/");

define('TEMPLATE_NAME', "OpenGame");

date_default_timezone_set("Asia/Saigon");

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
{
    $HTTP_ACCEPT_LANGUAGE = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}
else
{
    $HTTP_ACCEPT_LANGUAGE = "en";
} //default language}

$HTTP_ACCEPT_LANGUAGE = "vi";

if (is_dir($ugamela_root_path . "language/" . $HTTP_ACCEPT_LANGUAGE . '/'))
{
    define('DEFAULT_LANG', $HTTP_ACCEPT_LANGUAGE);
}
else
{
    define('DEFAULT_LANG', 'en');
}

include($ugamela_root_path . 'includes/debug.class.' . $phpEx);

$debug = new debug;

include($ugamela_root_path . 'includes/constants.' . $phpEx);

include($ugamela_root_path . 'includes/functions.' . $phpEx);

//include($ugamela_root_path . 'includes/db.' . $phpEx);

include($ugamela_root_path . 'includes/strings.' . $phpEx);

global $db;
$time       = time();
$time_end   = strtotime('2011-06-23 14:00:00');
$time_start = strtotime('2011-06-21 14:00:00');

if (!isset($_SESSION['game_config']))
{
    $game_config = array ();

    $sql = "SELECT * FROM wg_config";
    $configs = null;
    $configs=$db->select('*')->from('wg_config')->toList();
    if( is_array( $configs ) )
	{
		foreach ($configs as $config)
		{
			$game_config[$config['config_name']] = $config['config_value'];
		}
	}

    if ($time < $time_end && $time >= $time_start)
    {
        $game_config['k_game']     = 5;
        $game_config['k_train']    = 3;
        $game_config['k_speed']    = 3;
        $game_config['k_research'] = 5;
    }

    $_SESSION[$game_config['COOKIE_NAME']] = $game_config;
}

$game_config = $_SESSION[$game_config['COOKIE_NAME']];

if ($time < $time_end && $time >= $time_start)
{
    $game_config['k_game']     = 5;
    $game_config['k_train']    = 3;
    $game_config['k_speed']    = 3;
    $game_config['k_research'] = 5;
}
else
{
    $game_config['k_game']     = 3;
    $game_config['k_train']    = 2;
    $game_config['k_speed']    = 2;
    $game_config['k_research'] = 3;
}

include($ugamela_root_path . "language/" . DEFAULT_LANG . "/lang_info.cfg");
include($ugamela_root_path . 'mobileuseragent/detect_mobile_client.' . $phpEx);
?>
