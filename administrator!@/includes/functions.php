<?php 
function check_user()
{	
	$row = checkcookies();	
	if($row != false){
		global $user;
		$user = $row;
		return true;
	}
	return false;
}
function checkcookies(){
	global $db,$lang,$game_config,$ugamela_root_path,$phpEx;
	$row = false;
	if (isset($_COOKIE['ADMIN_COOKIE_NAME']))
	{
		$theuser = explode(" ",$_COOKIE['ADMIN_COOKIE_NAME']);
		$query = "SELECT wg_users.id,wg_admin.username,wg_admin.pass,wg_admin.authlevel FROM wg_admin
		 LEFT JOIN wg_users ON wg_users.username='".$theuser[1]."'
		WHERE wg_admin.username='$theuser[1]'";
		$db->setQuery($query);
		$row = $db->loadAssocList();
		$row = $row[0];
		if(!$row){
			setcookie('ADMIN_COOKIE_NAME', NULL, time()-100000, "/", "", 0);
			$row = false;			
		}
		if ($row['id'] != $theuser[0])
		{
			setcookie('ADMIN_COOKIE_NAME', NULL, time()-100000, "/", "", 0);
			$row = false;			
		}
		if ($_SESSION["password"] !== $theuser[2])
		{
			setcookie('ADMIN_COOKIE_NAME', NULL, time()-100000, "/", "", 0);
			$row = false;			
		}
		$_SESSION['username'] = $theuser[1];
		$_SESSION['id'] = $theuser[0];
    }
	unset($dbsettings);
	return $row;
}


function parsetemplate($template, $array)
{

	foreach($array as $a => $b) {
		$template = str_replace("{{$a}}", $b, $template);
	}
	return $template;
}

function gettemplate($templatename){ 
	global $ugamela_root_path;
	$filename =  $ugamela_root_path . TEMPLATE_DIR . TEMPLATE_NAME . '/' . $templatename . ".tpl";

	return ReadFromFile($filename);
	
}


function includeLang($filename,$ext='.mo'){
	global $ugamela_root_path,$lang;

	include($ugamela_root_path."language/".DEFAULT_LANG.'/'.$filename.$ext);

}

function ReadFromFile($filename){
	$f = @fopen($filename,"r");
	$content = @fread($f,filesize($filename));
	@fclose($f);
	return $content;

}

function SaveToFile($filename,$content){

	$f = fopen($filename,"w");
	fputs($f,"$content");
	fclose($f);

}
function displayAdmin($page,$title = '')
{
	global $link,$game_config,$debug,$user;
	echo_head_admin($title,$metatags);
	echo "$page";
	echo_leftmenu_admin();
	echo echo_foot_admin();	
	if(isset($link)) mysql_close();
	die();	
}
function displayAdminLogin($page,$title = ''){
	global $link;	
	echo "$page";	
	if(isset($link)) mysql_close();
	die();	
}
function echo_foot_admin(){
	global $game_config,$lang;
	$parse['copyright'] = $game_config['copyright'];
	$parse['TranslationBy'] = $lang['TranslationBy'];
	echo parsetemplate(gettemplate('admin/simple_footer'), $parse);
}

function echo_head_admin($title = '',$metatags='')
{
	global $lang,$game_config,$ugamela_root_path;
	$parse['slogan']=$game_config['game_name'];
	$parse['root_path'] =$ugamela_root_path;
	echo parsetemplate(gettemplate('admin/simple_header'), $parse);
}

function echo_leftmenu_admin()
{
	includeLang('admin');
	global $user,$ugamela_root_path,$lang;
	
	$parse=$lang;
	$parse['username']=$user['username'];	
	echo parsetemplate(gettemplate('admin/leftmenu_body'), $parse);	
}

function display2($page,$title = '',$metatags='')
{
	global $link,$game_config,$debug,$user;	
	echo "$page";
	if(isset($link)) mysql_close();
	die();	
}
?>
