<?php  //includes/common.php :: Contain common functions

if ( !defined('INSIDE') )

{
	die("Hacking attempt");

}  

session_start();	
//header("Powered-By: azrail");

define('VERSION','8.1a');

error_reporting  (E_ERROR | E_WARNING | E_PARSE); // Para evitar que se reporten las variables no iniciadas

set_magic_quotes_runtime(0); // Deshabilita magic_quotes_runtime



extract($_POST,EXTR_SKIP);

extract($_GET,EXTR_SKIP);

extract($_COOKIE,EXTR_SKIP);

//

// Definimos algunas arrays de configuracion.

//

$game_config = array();

$user = array();

$theme = array();

$images = array();

$lang = array();

$base_url = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'/',0));

//

// Constantes

//

define('TEMPLATE_DIR',"templates/");

define('TEMPLATE_NAME',"OpenGame");


date_default_timezone_set("Asia/Saigon");

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){

	$HTTP_ACCEPT_LANGUAGE = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);

}else{

	$HTTP_ACCEPT_LANGUAGE = "en";

}//default language}
$HTTP_ACCEPT_LANGUAGE = "vi";
if(is_dir($ugamela_root_path."language/".$HTTP_ACCEPT_LANGUAGE.'/')){

	define('DEFAULT_LANG',$HTTP_ACCEPT_LANGUAGE);

}else{

	define('DEFAULT_LANG','en');

}

define('DEFAULT_LANG','vi');


//--[ DEPURAR ]-------------------------------------------------------------

//Objeto debug

include($ugamela_root_path . 'includes/debug.class.'.$phpEx);

$debug = new debug;

//--[ /DEPURAR ]------------------------------------------------------------



include('includes/constants.'.$phpEx);

include('includes/functions.'.$phpEx);

include('includes/db.'.$phpEx);

include('includes/strings.'.$phpEx);

global $db;
if(!isset($_SESSION['game_config'])){
	$game_config = array();
	$sql = "SELECT * FROM wg_config";
	$db->setQuery($sql);
	$configs = null;
	$configs = $db->loadObjectList();
	foreach($configs as $config)
	{
		$game_config[$config->config_name] = $config->config_value;
	}
	$_SESSION['game_config'] = $game_config;
}
$game_config = $_SESSION['game_config'];
include($ugamela_root_path."language/".DEFAULT_LANG."/lang_info.cfg");

?>