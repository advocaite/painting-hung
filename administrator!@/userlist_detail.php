<?php // ----> by Justus

define('INSIDE', true);

$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');

if(!check_user()){ header("Location: login.php"); }

includelang('userlist');

$sql="Select * from wg_users where id='".$_GET["id"]."'";
$db->setQuery($sql);
$element=null;
$db->loadObject($element);

$parse = $lang;

$parse['id']=$element->id;
$parse['username']=$element->username;
$parse['password']=$element->password;
$parse['name']=$element->name;
$parse['email']=$element->email; 
$parse['lang']=$element->lang;
$parse['authlevel']=$element->authlevel;
$parse['sex']=$element->sex;
$parse['avatar']=$element->avatar;
$parse['rank']=$element->rank;
$parse['description']=$element->description;
$parse['population']=$element->population;
$parse['gold']=$element->gold;
$parse['sign']=$element->sign;
$parse['baned']=$element->baned;
$parse['user_lastip']=$element->user_lastip;
$parse['register_time']=$element->register_time;
$parse['actived']=$element->actived;

if (isset($_POST["Delete"]))
		{
			$sql = "delete from wg_users where id='".$_GET["id"]."'";
			$db->setQuery($sql);
			$db->query();
			message($lang['deleteUser'],$lang['Delete'],"userlist.".$phpEx);//redirect
		}

$parse['list'] = $list;
$page = parsetemplate(gettemplate('/admin/userlist_detail'), $parse);
display($page,$lang['userlist']);

?>