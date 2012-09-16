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
include_once ($ugamela_root_path . 'includes/usersOnline.class.php');
checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
$visitors_online = new usersOnline();
includeLang('village2');
includeLang('lang_Wap');
global $db,$village,$game_config,$wg_village_kinds,$wg_village,$wg_buildings,$user,$wg_status,$timeAgain;
$timeAgain=$visitors_online->deltaTimeSubtract;
//checkWorldFinished();	-> chi su dung khi co ky dai level >90 tro len			
doAnounment();		
doAllStatus();
$parse=$lang;
$_SESSION['url']='village2.php';
function showImagesEmptyInside($flag,$j,$index)
{
	if($flag==0)
	{
		$parse['number']=$j;
		switch($index)
		{
			case 37:
				$image="<img src='images/un/g/iso1.png' alt=''></img>";
				break;
			default:
				$image="<img src='images/un/g/iso.png' alt=''></img>";
				break;						
		}
	}
	else
	{
		if($index==37)
		{
			$image="<img src='images/un/g/iso1.png' alt=''></img>";
		}
		if($index==36)
		{
			$image="<img src='images/un/g/kydai0.png' alt=''></img>";
		}
		elseif($index<33)
		{
			$image="<img src='images/un/g/iso.png' alt=''></img>";
		}	
	}
	return $image;
}
function showImagesFullInside($flag,$j,$index,$img)
{
	if($flag==0)
	{
		switch($index)
		{
			case 38:
				$image="<img src='".$img."' alt=''></img>";
				break;
			default:
				$image="<img src='".$img."' alt=''></img>";
				break;
		}
	}
	else
	{
		if($index==38)
		{
			$image="<img src='".$img."' alt=''></img>";
		}
		elseif($index==36)
		{
			$image="<img src='".$img."' alt=''></img>";
		}
		else
		{
			$image="<img src='".$img."' alt=''></img>";
		}
	}
	return $image;
}
/*---- kiem tra tinh bao mat cho lang----*/
if(!empty($_GET['vid']) && is_numeric($_GET['vid']))
{
	$get_id=$_GET['vid'];
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE id=".$get_id." AND user_id=".$user["id"]." LIMIT 1";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	if($count==1)
	{
		$_SESSION['villa_id_cookie']=$get_id;
		$village=$get_id;
	}else{
		$village=$_SESSION['villa_id_cookie'];
	}
}else{
	if(isset($_SESSION['villa_id_cookie']))
	{
		$village=$_SESSION['villa_id_cookie'];
	}else{
		$village=$user['villages_id'];
	}
}
/* 
Pha huy cac cong trinh ben trong la`ng de lay dien tich xay ky dao`
*/
if(!empty($_GET['create_wonder']) && $_GET['create_wonder']==$_SESSION['create_wonder'])
{
	destroyAllBuildingOutSide($village);
	unset($_SESSION['create_wonder']);
}
$wg_village=NULL;
$wg_status=NULL;
$wg_village=getVillage($village);
$wg_status=getStatusProcessing();

/* Xay dung va nang cap cong trinh ben trong thanh  */
if(!empty($_GET['a']) && is_numeric($_GET['a']) && isset($_SESSION['UpdateBuilding'.$_GET['a']]))
{
	/*$_SESSION['UpdateBuilding'.$_GET['a']]=$id,$Time_Cost,$level,$type_id,$Lumber,$Clay,$Iron,$Crop,$name;*/
	$array=array();
	$get_a=$_GET['a'];
	$array=split(",",$_SESSION['UpdateBuilding'.$get_a]);
	
	if($get_a == $array[3])
	{
		$count=checkUpdateVillage($wg_status,$array[0],$user["id"],'village2');
		if($count==0)
		{
			$type=1;
			if($array[2]>0)
			{
				$type=2;
			}
			//tranh TH user click chuot nhieu lan ->insert nhieu record vao bang ..voi thoi gian va level trung nhau &&  xet TH cong trinh nay dang trong qua trinh pha huy = Main Building
			$sql="SELECT id FROM wg_status WHERE (object_id=".$array[0]." 
			AND village_id=".$village." AND type=".$type." AND cost_time=".$array[1]." AND status=0) 
			OR (object_id=".$array[0]." AND village_id=".$village." AND type=17 AND status=0)";
			$db->setQuery($sql);	
			$query=NULL;
			$db->loadObject($query);
			if($query)
			{
				$count=1; 
			}
		}	
		if($count==0 && isset($_SESSION['UpdateBuilding'.$get_a]))
		{
			$object_id=$array[0];
			$time_begin=date("Y-m-d H:i:s",time());
			$time_end=date("Y-m-d H:i:s",time()+$array[1]);
			$cost_time=$array[1];
			if(isset($_SESSION['checkPlusForUser']))
			{
				$sql="SELECT time_end FROM wg_status WHERE 	village_id=".$village." 
				AND type <=2 AND status=0 ORDER BY id DESC LIMIT 1";
				$db->setQuery($sql);	
				$temp=$db->loadResult();
				if($temp)	
				{
					$time_begin=$temp;
					$time_end=date("Y-m-d H:i:s",strtotime($temp)+$array[1]);
					unset($_SESSION['checkPlusForUser']);
				}
			}
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
					$sql="INSERT INTO `wg_status` (`object_id`, `village_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`,`level`) VALUES ($object_id, $village, 2, '$time_begin', '$time_end','$cost_time', 0,".$user['id'].",".$array[2].");";
				}
				else
				{
					if($array[3]==14)
					{
						$sql="UPDATE wg_users  SET embassy=1 WHERE id=".$user['id']."";
						$db->setQuery($sql);
						$db->query();
						if($db->getAffectedRows()==0)
						{
							globalError2($sql);
						}
					}	
					if($array[3]==37)
					{
						$sql="UPDATE wg_rare SET kim=kim-1,thuy=thuy-1,moc=moc-1,hoa=hoa-1,tho=tho-1 WHERE vila_id=".$village."";
						$db->setQuery($sql);
						$db->query();
						if($db->getAffectedRows()==0)
						{
							globalError2('village2.php:'.$sql);
						}
					}				
					$sql="INSERT INTO `wg_status` (`object_id`, `village_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`,`level`) VALUES ($object_id,$village,1,'$time_begin','$time_end','$cost_time',0,".$user['id'].",0)";
				}
				$db->setQuery($sql);
				if(!$db->query())
				{
					globalError2('village2.php:'.$sql);
				}
				/*--------------------------------------------------------------------------------------------------------*/
				$wg_village->rs1=$wg_village->rs1-$array[4];
				$wg_village->rs2=$wg_village->rs2-$array[5];
				$wg_village->rs3=$wg_village->rs3-$array[6];
				$wg_village->rs4=$wg_village->rs4-$array[7];
				if($array[2]==0)
				{
					// chen du lieu vao bang buidings
					if($object_id != 38)
					{
						$images=Get_Images1($array[3],$array[2]);
						$sql="UPDATE wg_buildings SET 
						name='".$array[8]."',img='".$images."',level=0,type_id=".$array[3].",product_hour=0 
						WHERE `index`=$object_id AND vila_id=".$village;										
						$db->setQuery($sql);
						$db->query();
						if($db->getAffectedRows()==0)
						{		
							globalError2('village2.php: '.$sql);
						}
					}
				}
				for($i=1;$i<38;$i++)
				{
					unset($_SESSION['UpdateBuilding'.$i]);
				}					
			}				
		}
	}	
}

// huy bo lenh nang cap
if(!empty($_GET['cancel']) && is_numeric($_GET['cancel']))
{
	if($wg_status)
	{
		$get_cancel=$_GET['cancel'];
		foreach($wg_status as $sttBuild =>$objValue)
		{
			if($get_cancel == $objValue->id)
			{
				DeleteStatus($get_cancel,$village,$objValue->type,$objValue->object_id);
				break;
			}
		}
	}
}
$wg_buildings=NULL;
$wg_buildings=getBuildings($village);
getSumCapacity($wg_village,$wg_buildings);
$time_now=time();
UpdateRS($wg_village, $wg_buildings,$time_now);
updateTrainTroopStatus($wg_village,$time_now);
$parse['resource_info_value']= GetRSStatus();
$parse['top_menu'] = parsetemplate(gettemplate('top_menu'), $parse);
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
					//$parse['image'].=showImagesEmptyInside($flag,$j,$ptu->index);	
					$emptyLand="<p><a href='build.php?id=".$ptu->index."'>".$lang['Building_site']."</a></p>";				
				}
				else
				{
					//$parse['image'].=showImagesFullInside($flag,$j,$ptu->index,$ptu->img);
					$parse['image'].="<p><a href='build.php?id=".$ptu->index."'>".$lang[$ptu->name]."</a> ("./*$lang['level'].*/"$ptu->level)</p>";				
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
$parse['image'].=$emptyLand;
$parse['village_id'] = $wg_village->id;
$page = parsetemplate(gettemplate($tempalte), $parse);
display($page,$lang['Village center']);
ob_end_flush();
?>



