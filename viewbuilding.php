<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php'); 
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/games.css" type="text/css" /> 
<title>Thông tin chi tiết lính</title>
</head>
<body style="background:none; top:0; left:0;">
<?php
if(isset($_GET['id']) && is_numeric($_GET['id']))
{
	$path="xml/building0.xml";
	if($_GET['id'] > 0 && $_GET['id'] <= 26)
	{
		echo '<div><center><img src ="images/un/a/navi.gif" width="86" height="16" style="cursor:pointer;" usemap ="#planetmap"/><map id ="planetmap" name="planetmap">
  <area shape="circle" coords ="12,9,10" href="viewbuilding.php?id='.($_GET['id']-1).'"/><area shape="circle" coords ="43,6,10" href="viewbuilding.php?id=0"/>
  <area shape="circle" coords ="73,8,10" href="viewbuilding.php?id='.($_GET['id']+1).'"/></map></center></div>';
		$path="xml/building".$_GET['id'].".xml";
	}
	$xmlDoc = new DOMDocument();	
	$xmlDoc->load($path);				
	$x = $xmlDoc->documentElement;
	foreach ($x->childNodes as $item)
	{
		if($item->nodeName == 'content')
		{
			echo $item->nodeValue;	
		}			
	}				
}
?>
</html>
