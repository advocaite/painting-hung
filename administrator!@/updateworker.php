<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.php'); 
include('includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/func_security.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1){ header("Location: login.php"); }

global $db,$wg_villages;
$dk=$dk1='';
$sql="SELECT id,population,villages_id FROM wg_users ORDER BY id ASC";
if(isset($_GET['id']))
{
	if(!is_numeric($_GET['id']))
	{
		$sql="SELECT id FROM wg_users WHERE username='".$_GET['id']."'";
		$db->setQuery($sql);
		$id=$db->loadResult();
		if($id =='')
		{
			$id=0;	
		}
		$dk='id='.$id;
		$dk1='WHERE user_id='.$id;
		
	}
	else
	{
		$dk='id='.$_GET['id'];
		$dk1='WHERE user_id='.$_GET['id'];
	}
	$sql="SELECT id,population,villages_id FROM wg_users WHERE  ".$dk." ORDER BY id ASC";
}

$db->setQuery($sql);
$wg_users=NULL;
$wg_users=$db->loadObjectList();
if($wg_users)
{
	$sql="SELECT id,user_id,workers FROM wg_villages ".$dk1;
	$db->setQuery($sql);
	$wg_villages=$db->loadObjectList();
	$count=0;
	foreach ($wg_users as $key=>$result)
	{
		$sum=returnWorkers($result->id);
		$sql="UPDATE wg_users SET population=$sum WHERE id=".$result->id;
		$db->setQuery($sql);
		if($db->query())
		{
			$count++;
		}	
	}
	if($count==count($wg_users))
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo "<center><h1>Cập Nhật Thành Công</h1></center>";
	}
}
else
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<center><h1>Danh sách cập nhật rỗng</h1></center>';
}
function returnWorkers($userid)
{
	global $db,$wg_villages;
	$sum=0;
	foreach ($wg_villages as $key=>$result)
	{
		if($userid==$result->user_id)
		{
			$workers=Workers($result->id);	
			$sum+=$workers;
			$sql="UPDATE wg_villages SET workers=$workers WHERE id=".$result->id;
			$db->setQuery($sql);
			$db->query();				
		}	
	}			
	return $sum;
}
function Workers($villages_id)
{
	global $db;
	$sql="SELECT `index`,type_id,level FROM wg_buildings WHERE vila_id=".$villages_id." ORDER BY `index` ASC";
	$db->setQuery($sql);
	$wg_villages=$db->loadObjectList();
	$sum=0;
	foreach($wg_villages as $key=>$value)
	{
		if($value->level>0)
		{			
			for($i=1;$i<=$value->level;$i++)
			{
				$sum+=getWorker($value->type_id,$i);				
			}
		}
	}	
	return $sum;
}
ob_end_flush();
?>
 


