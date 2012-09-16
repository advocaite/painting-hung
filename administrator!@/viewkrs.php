<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.php');
include('includes/common.'.$phpEx); 
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_plus.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1){ header("Location: login.php"); }

includeLang('village1');
updateAllPlus();
global $db,$wg_buildings,$wg_villages,$wg_plus,$wg_plus,$color,$wg_oasis,$percent_oasis;
// tang he so % cho thanh tuy theo type oasis
$percent_oasis=array(
    7 => Array(
			'lumber' =>0.25,
           	'clay' =>0,
			'iron' =>0,
			'crop' =>0
        ), 
	 8 => Array(
			'lumber' =>0.25,
           	'clay' =>0,
			'iron' =>0,
			'crop' =>0.25
        ),  
	9 => Array(
			'lumber' =>0,
           	'clay' =>0,
			'iron' =>0.25,
			'crop' =>0
        ), 
	10 => Array(
			'lumber' =>0,
           	'clay' =>0,
			'iron' =>0.25,
			'crop' =>0.25
        ),
	11 => Array(
			'lumber' =>0,
           	'clay' =>0.25,
			'iron' =>0,
			'crop' =>0
        ),
	12 => Array(
			'lumber' =>0,
           	'clay' =>0.25,
			'iron' =>0,
			'crop' =>0.25
        ),
	13 => Array(
			'lumber' =>0,
           	'clay' =>0,
			'iron' =>0,
			'crop' =>0.25
        ),
	14 => Array(
			'lumber' =>0,
           	'clay' =>0,
			'iron' =>0,
			'crop' =>0.5
        )
);

if(isset($_GET['id']))
{
	if(!is_numeric($_GET['id']))
	{
		$sql="SELECT tb1.kind_id,tb1.name,tb1.user_id,tb1.id as villages_id,tb2.username FROM wg_villages as tb1,wg_users as tb2 WHERE tb2.username='".$_GET['id']."' AND tb1.user_id=tb2.id AND tb1.kind_id <7 ORDER BY `tb1`.`id` ASC";
	}
	else
	{
		$sql="SELECT tb1.kind_id,tb1.name,tb1.user_id,tb1.id as villages_id,tb2.username FROM wg_villages as tb1,wg_users as tb2 WHERE tb1.user_id=".$_GET['id']." AND tb2.id=".$_GET['id']." AND tb1.kind_id <7 ORDER BY tb1.id ASC";
	}
}
else
{
	$sql="SELECT tb1.kind_id,tb1.name,tb1.user_id,tb1.id as villages_id,tb2.username FROM wg_villages as tb1,wg_users as tb2 WHERE tb1.kind_id <7 AND tb1.user_id = tb2.id ORDER BY `tb1`.`user_id` ASC";
}
$db->setQuery($sql);
$wg_users=NULL;
$wg_users=$db->loadObjectList();

if($wg_users)
{
	$sql="SELECT user_id,lumber,clay,iron,crop FROM wg_plus";
	if(isset($_GET['id']))
	{
		$sql="SELECT user_id,lumber,clay,iron,crop FROM wg_plus WHERE user_id=".$wg_users[0]->user_id;
	}
	$db->setQuery($sql);
	$wg_plus=$db->loadObjectList();
	
	$sql="SELECT id,krs1,krs2,krs3,krs4 FROM wg_villages WHERE kind_id<7";
	if(isset($_GET['id']))
	{
		$sql="SELECT id,krs1,krs2,krs3,krs4 FROM wg_villages WHERE kind_id<7 AND user_id=".$wg_users[0]->user_id;
	}
	$db->setQuery($sql);
	$wg_villages=$db->loadObjectList();
	
	$sql="SELECT id,x,y,kind_id,child_id FROM wg_villages WHERE kind_id>6";
	if(isset($_GET['id']))
	{
		$sql="SELECT id,x,y,kind_id,child_id FROM wg_villages WHERE kind_id>6 AND user_id=".$wg_users[0]->user_id;
	}
	$db->setQuery($sql);
	$wg_oasis=$db->loadObjectList();
	
	$sql="SELECT name,level,type_id,vila_id FROM wg_buildings WHERE (type_id BETWEEN 5 AND 9) ORDER BY type_id ASC ";
	$db->setQuery($sql);
	$wg_buildings=$db->loadObjectList();
	
	$color=array(5=>"#000033",6=>"#FF0000",7=>"#00FFFF",8=>"#993399");
	$hsk=array(0=>1,1=>1.05,2=>1.1,3=>1.15,4=>1.2,5=>1.25,6=>1.3125,7=>1.375,8=>1.4375,9=>1.5,10=>1.5625);
	//$name=array('Sawmill'=>'lumber','Brickyard'=>'clay','Iron_Foundry'=>'iron','Bakery'=>'crop','Grain_Mill'=>'crop');
	$name=array(6=>'lumber',7=>'clay',8=>'iron',9=>'crop',5=>'crop');
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	$count=$check=0;
	$time_check=strtotime(date("Y-m-d H:i:s",time()));
	foreach ($wg_users as $key=>$result)
	{	
		echo '<h2>Hệ số % Wg_Plus của '.$result->username.' ['.$result->user_id.']</h2>';
		$array2=returnPlus($result->user_id,$time_check);
		$count++;
		$array1=returnKrs($result->villages_id);
		echo "<h2>".$count."&nbsp;&nbsp;&nbsp;Thành:".$result->name."</h2>Village_id: <strong>[".$result->villages_id."][kind_id=".$result->kind_id."]&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:".$color[6]."'>".$array1[0]."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:".$color[7]."'>".$array1[1]."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:".$color[8]."'>".$array1[2]."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:".$color[5]."'>".$array1[3]."</span></strong></br>";
		
		$array3=returnContructBuilding($result->villages_id);
		echo'<strong>Hệ số tăng % của công trình</strong>:';echo "<pre>";print_r($array3);
		$krs_oasis=returnOasis($result->villages_id);
		$array4=array();
		$array4[0]=$array2['lumber']*($array3['lumber']+$krs_oasis['lumber']);
		$array4[1]=$array2['clay']*($array3['clay']+$krs_oasis['clay']);
		$array4[2]=$array2['iron']*($array3['iron']+$krs_oasis['iron']);
		$array4[3]=$array2['crop']*($array3['crop']+$krs_oasis['crop']);
		
		echo "<p><h3>Kết Quả Thực Tế:<span style='color:".$color[6]."'>".$array4[0]."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:".$color[7]."'>".$array4[1]."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:".$color[8]."'>".$array4[2]."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:".$color[5]."'>".$array4[3]."</span></h3>";
		if($array1[0]!=$array4[0] || $array1[1]!=$array4[1] || $array1[2]!=$array4[2] || $array1[3]!=$array4[3])
		{
			echo '<h2><span style="color: rgb(255, 0, 0);">SAI</span></h2>';
		}
		echo '<hr>';
	}
}
else
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<center><h1>Danh sách rỗng</h1></center>';
}
function returnContructBuilding($villages_id)
{
	global $wg_buildings,$lang,$color,$hsk,$name;
	$array=array();
	$list='';
	$array['lumber']=1;
	$array['clay']=1;
	$array['iron']=1;
	$array['crop']=1;
	$level=0;
	foreach ($wg_buildings as $key=>$result)
	{
		if($villages_id==$result->vila_id)
		{
			$list.='&nbsp;&nbsp;<strong>+<span style="color:'.$color[$result->type_id].'">'.$lang[$result->name].'</span></strong>('.$villages_id.')&nbsp;&nbsp;='.$result->level.'</br>';
			if($result->type_id==5 || $result->type_id==9)
			{
				$level+=$result->level;	
			}
			else
			{
				$array[''.$name[$result->type_id].'']=$hsk[$result->level];
			}
		}
	}
	$array['crop']=$hsk[$level];	
	$array['lumber']=$array['lumber'];
	$array['clay']=$array['clay'];
	$array['iron']=$array['iron'];
	echo $list.'</p>';
	return $array;
}
function returnKrs($villages_id)
{
	global $wg_villages;
	$array=array();
	foreach ($wg_villages as $key=>$result)
	{
		if($villages_id==$result->id)
		{
			$array[0]=$result->krs1;
			$array[1]=$result->krs2;
			$array[2]=$result->krs3;
			$array[3]=$result->krs4;	
			break;		
		}	
	}
	return $array;
}
function returnOasis($villages_id)
{
	global $wg_oasis,$percent_oasis;
	$array=array();
	$array['lumber']=0;
	$array['clay']=0;
	$array['iron']=0;
	$array['crop']=0;
	foreach ($wg_oasis as $key=>$result)
	{		
		if($villages_id==$result->child_id)
		{
			echo "<b>Bộ lạc (".$result->x."|".$result->y.") (kind_id=".$result->kind_id."):&nbsp;&nbsp;".$percent_oasis[$result->kind_id]['lumber']."&nbsp;&nbsp;".$percent_oasis[$result->kind_id]['clay']."&nbsp;&nbsp;".$percent_oasis[$result->kind_id]['iron']."&nbsp;&nbsp;".$percent_oasis[$result->kind_id]['crop']."</b></br>";
			$array['lumber']+=$percent_oasis[$result->kind_id]['lumber'];
			$array['clay']+=$percent_oasis[$result->kind_id]['clay'];
			$array['iron']+=$percent_oasis[$result->kind_id]['iron'];
			$array['crop']+=$percent_oasis[$result->kind_id]['crop'];
		}	
	}
	return $array;
}
function returnPlus($userid,$time_check)
{
	global $wg_plus,$color;
	$array=array();
	$array['lumber']=1;
	$array['clay']=1;
	$array['iron']=1;
	$array['crop']=1;
	foreach ($wg_plus as $key=>$result)
	{
		if($userid==$result->user_id)
		{
			echo '&nbsp;&nbsp;<strong>+<span style="color:'.$color[6].'">Lumber</span></strong>:'.$result->lumber.'</br>';	
			echo '&nbsp;&nbsp;<strong>+<span style="color:'.$color[7].'">Clay</span></strong>:'.$result->clay.'</br>';	
			echo '&nbsp;&nbsp;<strong>+<span style="color:'.$color[8].'">Iron</span></strong>:'.$result->iron.'</br>';	
			echo '&nbsp;&nbsp;<strong>+<span style="color:'.$color[5].'">Crop</span></strong>:'.$result->crop.'</p>';
			if(strtotime($result->lumber) > $time_check )	
			{
				$array['lumber']=1.25;
			}
			if(strtotime($result->clay) > $time_check )	
			{
				$array['clay']=1.25;
			}
			if(strtotime($result->iron) > $time_check )	
			{
				$array['iron']=1.25;
			}
			if(strtotime($result->crop) > $time_check )	
			{
				$array['crop']=1.25;
			}
			break;
		}	
	}
	return $array;
}
ob_end_flush();
?>
 


