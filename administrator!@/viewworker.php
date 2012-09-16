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
}
$sql="SELECT id,username,population,villages_id FROM wg_users WHERE ".$dk." ORDER BY population DESC";
$db->setQuery($sql);
$wg_users=$db->loadObjectList();
$sql="SELECT id,user_id,workers,kind_id  FROM wg_villages ".$dk1;
$db->setQuery($sql);
$wg_villages=$db->loadObjectList();
$count=0;
if($wg_users)
{
	foreach ($wg_users as $key=>$result)
	{
		$count++;
		$array=returnWorkers($result->id);
		echo "<h3>".$count."&nbsp;&nbsp;".$result->username."</h3>&nbsp;&nbsp;Userid: <strong>".$result->id."</strong> 	Population: <strong>".$result->population."</strong></br>";
		echo ''.$array['list'].'';
		if($array['wg_village']!=$result->population || $array['wg_building']!=$array['wg_village'])
		{
			echo '<span style="color:#FF0000">&nbsp;&nbsp;<h2>Sai</h2></span><hr>';
		}
		else
		{
			echo '<hr>';
		}
	}
}
else
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<center><h1>Danh sách rỗng</h1></center>';
}
function returnWorkers($userid)
{
	global $wg_villages;
	$list='';
	$array=array();
	$wg_building=0;
	$wg_village=0;
	foreach ($wg_villages as $key=>$result)
	{	
		if($userid==$result->user_id)
		{
			$wg_village+=$result->workers;
			$workers=arrayBuilding($result->id);			
			$list.='&nbsp;&nbsp;<strong>+</strong>Village_id:='.$result->id.' (kind='.$result->kind_id.')&nbsp;&nbsp;[ workers (wg_village)=<strong>'.$result->workers.'</strong>]&nbsp;&nbsp;VS&nbsp;&nbsp;[workers (wg_building)=<strong>'.$workers.'</strong>]</br>';
			$wg_building+=$workers;			
		}	
	}
	$array['list']=$list;
	$array['wg_village']=$wg_village;
	$array['wg_building']=$wg_building;
	return $array;
}
function arrayBuilding($villages_id)
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
 


