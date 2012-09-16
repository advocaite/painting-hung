<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.php'); 
include('includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'ref/promo.php');
if(!check_user()){ header("Location:logout.php"); }
if($user['authlevel']<1){ header("Location:logout.php"); }
function saveFile($content,$file)
{
	
	$f = fopen($file,"w") or exit("Khong the mo file!");
	fputs($f,$content);
	fclose($f);
	return true;
}
global $db,$promo;
$sql="SELECT id,username,villages_id,population FROM wg_users ORDER BY population DESC";
$db->setQuery($sql);
$wg_users=NULL;
$wg_users=$db->loadObjectList();
$count=0;
$char='$';
$content.='<?php
if (!defined("INSIDE")){die("Hacking attempt");}';
$content.="".$char."rank=array();";
if($promo[0]["date"] != date("Y-m-d"))
{
	$string='<?php
if (!defined("INSIDE")){die("Hacking attempt");}
$promo=array();
$promo[0]["date"]="'.date("Y-m-d").'";';
}

if($wg_users)
{
	foreach($wg_users as $key=>$value)
	{
		$sql="UPDATE wg_users SET rank=".($key+1)." WHERE id=".$value->id;
		$db->setQuery($sql);
		if($db->query())
		{
			$content.="".$char."rank[".$key."]['id']=".$value->id.";";
			$content.="".$char."rank[".$key."]['username']='".$value->username."';";
			$content.="".$char."rank[".$key."]['rank']=".($key+1).";";
			$content.="".$char."rank[".$key."]['population']=".$value->population.";";
			$string.="".$char."promo[".$value->id."]['name']='".$value->username."';";
			$string.="".$char."promo[".$value->id."]['villages_id']='".$value->villages_id."';";
			$string.="".$char."promo[".$value->id."]['ip']='';";
			$count++;
		}
		else
		{
			globalError2('Admin tool cap nhat hang error'.$sql);
		}
	}	
}
$content.='?>';
$string.='?>';
saveFile($content,'../includes/rank.php');
if($promo[0]["date"] != date("Y-m-d"))
{
	saveFile($string,'../ref/promo.php');
}
if($count==count($wg_users))
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo "<h3>Cập Nhật Thành Công</h3>";
}
else
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	echo "<h3>Xảy ra lỗi trong quá trình cập nhật hạng</h3>";
}
ob_end_flush();
?>
 


