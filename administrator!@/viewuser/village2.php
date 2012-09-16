<?php
ob_start();
define('INSIDE', true);
$ugamela_root_path = './';
$image_path="images/village/";
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.php'); 
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/func_convert.php');
include($ugamela_root_path . 'includes/function_resource.php');
include($ugamela_root_path . 'includes/function_trade.php');
include($ugamela_root_path . 'includes/function_status.php');
include($ugamela_root_path . 'includes/function_attack.php');
include($ugamela_root_path . 'includes/function_troop.php');
include($ugamela_root_path . 'includes/func_security.php');

if(!check_user()){ header("Location: login.php"); }
includeLang('village2');
global $db,$village,$game_config,$wg_village_kinds,$wg_village,$wg_buildings,$user,$wg_status;	
doAnounment();		

$parse=$lang;
function showImagesEmptyInside($flag,$j,$index)
{
	if($flag==0)
	{
		$parse['number']=$j;
		switch($index)
		{
			case 37:
				$parse['name_img']='iso1';				
				break;
			default:
				$parse['name_img']='iso';				
				break;						
		}
	}
	else
	{
		$parse['number']=$j;
		if($index==37)
		{
			$parse['name_img']='iso1';			
		}
		if($index==36)
		{
			$parse['number']=21;
			$parse['name_img']='kydai0';			
		}
		elseif($index<33)
		{
			$parse['name_img']='iso';
		}	
	}
	return parsetemplate(gettemplate('village2_images'),$parse);
}
function showImagesFullInside($flag,$j,$index,$img)
{
	if($flag==0)
	{
		switch($index)
		{
			case 38:
				$parse['number']=20;
				break;
			default:
				$parse['number']=$j;				
				break;
		}
	}
	else
	{
		if($index==38)
		{
			$parse['number']=20;			
		}
		elseif($index==36)
		{
			$parse['number']=21;			
		}
		else
		{
			$parse['number']=$j;			
		}
	}
	$parse['link_img']=$img;
	return parsetemplate(gettemplate('village2_images1'),$parse);
}
/*---- kiem tra tinh bao mat cho lang-----------------------------------------------------------------------------------*/
if(!empty($_GET['id']) && is_numeric($_GET['id']))
{
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE id=".$_GET['id']." AND user_id=".$user["id"]." LIMIT 1";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	if($count==1)
	{
		setcookie('villa_id_cookie',$_GET['id'],time()+31536000, "/", "", 0);
		$village=$_GET['id'];
	}else{
		$village=$_COOKIE['villa_id_cookie'];
	}
}else{
	if(isset($_COOKIE['villa_id_cookie'])){
		$village=$_COOKIE['villa_id_cookie'];
	}else{
		$village=$user['villages_id'];
	}
}
/* 
Pha huy cac cong trinh ben trong la`ng de lay dien tich xay ky dao`
*/
if(!isset($_SESSION['password_viewuser']) && !empty($_GET['create_wonder']) && $_GET['create_wonder']==$_SESSION['create_wonder'])
{
	destroyAllBuildingOutSide($village);
	unset($_SESSION['create_wonder']);
}
$wg_village=null;
$wg_status=null;
$wg_village=getVillage($village);
$wg_status=getStatusProcessing();

/* Xay dung va nang cap cong trinh ben trong thanh  */
if(!isset($_SESSION['password_viewuser']) && !empty($_GET['a']) && is_numeric($_GET['a']) && isset($_COOKIE["UpdateBuilding".$_GET['a'].""]))
{
/*
$_COOKIE['UpdateBuilding']=''.$id.','.$Time_Cost.','.$level.','.$type_id.','.$Lumber.','.$Clay.','.$Iron.','.$Crop.',,'.md5(time()).'';*/
	$string=GiaiMa($_COOKIE['UpdateBuilding'.$_GET['a'].'']);
	$array=string_to_array($string);
	if($_GET['a']==$array[3])
	{
		$count=0;
		foreach($wg_status as $k =>$v)
		{
			if($v->type==1 || $v->type==2)
			{
				$count=1;
				for($i=1;$i<38;$i++)
				{
					setcookie('UpdateBuilding'.$i.'','',time()-100000, "/", "", 0);
				}
				break;
			}
		}
		if($count==0 && isset($_COOKIE["UpdateBuilding".$_GET['a'].""]))
		{
			$object_id=$array[0];
			$village_id=$village;
			$time_begin=laythoigian(time());
			$time_end=laythoigian(time()+$array[1]);
			$cost_time=$array[1];
			$level=$array[2];
			$Lumber1=$wg_village->rs1;
			$Clay1=$wg_village->rs2;
			$Iron1=$wg_village->rs3;
			$Crop1=$wg_village->rs4;
			if($array[4] >$Lumber1 || $array[5] >$Clay1 || $array[6] >$Iron1 || $array[7] >$Crop1)
			{
				
			}
			elseif($array[2]==0 && $array[3]==14 && $user['embassy']==1)
			{
				
			}
			elseif($array[2]==0 && $array[3]==37 && checkShowWorldWonder($wg_buildings,$village)==0)
			{
				
			}
			else
			{
				if($array[2]>0)
				{
					$sql="INSERT INTO `wg_status` (`object_id`, `village_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`,`level`) VALUES ($object_id, $village_id, 2, '$time_begin', '$time_end','$cost_time', 0,".$user['id'].",".$array[2].");";
				}
				else
				{
					if($array[3]==14)
					{
						$sql="UPDATE wg_users  SET embassy=1 WHERE id=".$user['id']."";
						$db->setQuery($sql);
						$db->query();
					}	
					if($array[3]==37)
					{
						$sql="UPDATE wg_rare SET kim=kim-1,thuy=thuy-1,moc=moc-1,hoa=hoa-1,tho=tho-1 WHERE vila_id=".$village."";
						$db->setQuery($sql);
						$db->query();
					}				
					$sql="INSERT INTO `wg_status` (`object_id`, `village_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`,`level`) VALUES ($object_id,$village_id,1,'$time_begin','$time_end','$cost_time',0,".$user['id'].",0)";
				}
				$db->setQuery($sql);
				$db->query();
				/*--------------------------------------------------------------------------------------------------------*/
				$wg_village->rs1=$wg_village->rs1-$array[4];
				$wg_village->rs2=$wg_village->rs2-$array[5];
				$wg_village->rs3=$wg_village->rs3-$array[6];
				$wg_village->rs4=$wg_village->rs4-$array[7];
				if($array[2]==0)
				{
					// chen du lieu vao bang buidings
					$name=$array[8];	
					$images=Get_Images1($array[3],$array[2]);
					if($object_id==38)
					{
						$sql="UPDATE wg_buildings SET name='$name',level=0,type_id=".$array[3].",product_hour=0 WHERE wg_buildings.index=$object_id AND vila_id=$village_id";
					}
					else
					{
						$sql="UPDATE wg_buildings SET name='$name',img='$images',level=0,type_id=".$array[3].",product_hour=0 WHERE wg_buildings.index=$object_id AND vila_id=$village_id";
					}
					$db->setQuery($sql);
					$db->query();
				}
				for($i=1;$i<38;$i++)
				{
					setcookie('UpdateBuilding'.$i.'','',time()-100000, "/", "", 0);
				}					
			}				
		}
	}	
}
/*----------------------------------------------------------------------------------------------------------------------------*/
// huy bo lenh nang cap
if(!isset($_SESSION['password_viewuser']) && !empty($_GET['cancel']) && is_numeric($_GET['cancel']))
{
	foreach($wg_status as $sttBuild =>$objValue)
	{
		if($_GET['cancel'] == $objValue->id)
		{
			DeleteStatus($_GET['cancel'],$village,$objValue->type,$objValue->object_id);
			break;
		}
	}
}
$wg_buildings=getBuildings($village);
getSumCapacity($wg_village,$wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());
updateTrainTroopStatus($wg_village, time());

$parse['Name_Village']=$wg_village->name;
if($wg_village->name=="NewName" || $wg_village->name=="")
{
	$parse['Name_Village']=$lang['NewName'];
}
$parse['capital']='';
if($user['villages_id']==$village)
{
	$parse['capital']='<span class="c">['.$lang['capital'].']</span>';
}
// xuat thong tin update va xay dung tai nguyen va cong trinh
$level_up_status=ShowBuild($village,$wg_status);
$parse["building_level_up_status"]=$level_up_status['building_level_up_status'];
$parse['image']="";
$parse['bg_village2']='d2_x d2_'.$user['nation_id'].'';
if($wg_buildings[37]->level>0)
{
	$parse['bg_village2']='d2_x d2_'.$user['nation_id'].'a';
}
$tempalte='village2_body';
$flag=0;
$flag=checkShowWorldWonder($wg_buildings,$village);

if($flag==1)
{
	$tempalte='village2_body_rare';
}
if($wg_buildings)
{
	$i=19;
	$j=1;
	foreach ($wg_buildings as $key => $ptu)
	{
		if($ptu->index>18)
		{
			if($i<38)
			{
				if($ptu->img=="")
				{
					$parse['image'].=showImagesEmptyInside($flag,$j,$ptu->index);				
				}
				else
				{
					$parse['image'].=showImagesFullInside($flag,$j,$ptu->index,$ptu->img);				
				}
			}			
			$j++;
			$parse['build_'.$i.'']="build.php?id=".$ptu->index."";
			if($ptu->level>0)
			{
				$parse['title_'.$i.'']="".$lang[$ptu->name]." ".$lang['level']." ".$ptu->level."";
			}
			else
			{
				switch($ptu->index)
				{
					case 37:
						$parse['title_'.$i.'']=$lang['36'];
						break;
					case 38:
						$parse['title_'.$i.'']=$lang['34'];
						break;
					default:
						$parse['title_'.$i.'']=$lang['Building_site'];
						break;					
				}				
			}			
			$i++;			
		}
	}
}
$page = parsetemplate(gettemplate($tempalte), $parse);
display($page,$lang['Village center']);
ob_end_flush();
?>



