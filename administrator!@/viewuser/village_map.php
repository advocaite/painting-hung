<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php'); 
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/func_build.php'); 
require_once($ugamela_root_path . 'includes/function_resource.php');
require_once($ugamela_root_path . 'includes/function_foundvillage.php');
require_once($ugamela_root_path . 'includes/func_security.php');
require_once($ugamela_root_path . 'includes/wg_village_kinds.php');
require_once($ugamela_root_path . 'includes/wg_oasis_troop.php');
require_once($ugamela_root_path . 'includes/wg_troops.php');
require_once($ugamela_root_path . 'includes/nation_troop.php');
require_once($ugamela_root_path . 'includes/func_convert.php');
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
// ham lay ten chung toc
function GetAlieVillage($userid)
{
	global $db,$lang;
	$parse=$lang;
	$sql="SELECT id,name FROM wg_allies WHERE id=(SELECT alliance_id FROM wg_users WHERE id=".$userid.")";
	$db->setQuery($sql);
	$wg_allies=null;
	$db->loadObject($wg_allies);
	if($wg_allies)
	{
		$alliance[0]=$wg_allies->id;
		$alliance[1]='<a href="allianz.php?aid='.$wg_allies->id.'">'.$wg_allies->name.'</a>';
	}
	else
	{
		$alliance[0]=NULL;
		$alliance[1]=$lang['None'];
	}
	return $alliance;	
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
// ham lay ten chung toc
//kiem tra co phai phai capital hay khong
function GetCapital($village)
{
	global $lang,$user;
	if($village==$user['villages_id'])
	{
		$kq=$lang['Capital'];
	}
	else
	{
		$kq='';
	}
	return $kq;
}
function GetInfoTroop($id,$num)
{
	global $lang,$wg_troops;
	foreach($wg_troops as $sttBuild =>$objValue)
	{
		if($id == $objValue['id'])
		{
			if($num==1)//lay ten
				return $lang[$objValue['name']];
			elseif($num==2) //lay hinh anh
				return $objValue['icon'];
		}				
	}
}
//show thong tin tai nguyen theo the DIV
function ShowResource($kind_id)
{
	global $lang;
	$parse=$lang;
	$row = gettemplate('village_map1');
	switch($kind_id)
	{
		case 1:
		{
			$parse['a1']=3;
			$parse['a2']=3;
			$parse['a3']=3;
			$parse['a4']=9;
			break;
		}
		case 2:
		{
			$parse['a1']=3;
			$parse['a2']=4;
			$parse['a3']=5;
			$parse['a4']=6;
			break;
		}
		case 3:
		{
			$parse['a1']=4;
			$parse['a2']=4;
			$parse['a3']=4;
			$parse['a4']=6;
			break;
		}
		case 4:
		{
			$parse['a1']=4;
			$parse['a2']=5;
			$parse['a3']=3;
			$parse['a4']=6;
			break;
		}
		case 5:
		{
			$parse['a1']=5;
			$parse['a2']=3;
			$parse['a3']=4;
			$parse['a4']=6;
			break;
		}
		case 6:
		{
			$parse['a1']=1;
			$parse['a2']=1;
			$parse['a3']=1;
			$parse['a4']=15;
			break;
		}
	}
	return parsetemplate($row,$parse);
}

function showOasisTroopNew($village,$type,$time_now)
{
	global $db,$wg_oasis_troop;
	$sql="SELECT * FROM wg_oasis_troop_att WHERE village_id=".$village."";
	$db->setQuery($sql);
	$query=null;
	$db->loadObject($query);
	if($query)
	{
		return $result=getOasisTroopNew($query->troop_list,$type,$time_now,$query->att_time);
	}
	foreach($wg_oasis_troop as $k=>$value)
	{
		if($type==$value['kind_id'])
		{
			for($i=34;$i<=43;$i++)
			{	
				$troop_list_new[$i]=$value['troop'.$i.''];					
			}				
			return $troop_list_new;
		}
	}
}
// ham nay cap nhat nation_id cho oasis do SQl cu chua cap nhat
function updateNationOfOsiss()
{
	global $db;
	$sql="SELECT id,child_id FROM wg_villages WHERE kind_id>6";
	$db->setQuery($sql);
	$list=$db->loadObjectList();
	foreach ($list as $key=>$value)
	{
		$sql="SELECT nation_id FROM wg_villages WHERE id=".$value->child_id."";
		$db->setQuery($sql);
		$nation_id=$db->loadResult();
		$sql="UPDATE wg_villages SET nation_id=".$nation_id." WHERE id=".$value->id."";
		$db->setQuery($sql);
		$db->query();
	}
}
/*
Liet ke danh sach tin bao cua thanh vien trong lien minh
*/
function getListExchangeFire($alliance_id,$userid)
{
	global $db;
	$sql="SELECT user_id FROM wg_ally_members WHERE ally_id=".$alliance_id;
	$db->setQuery($sql);
	$wg_ally_members=NULL;
	$wg_ally_members=$db->loadObjectList();
	foreach ($wg_ally_members as $key=>$value)
	{
		if($value->user_id==$userid)
		{
			$array=NULL;
			$lis=NULL;
			$sql="SELECT id,title,type FROM wg_reports WHERE user_id=".$userid." AND type=2 OR type=4 ORDER BY time DESC LIMIT 5";
			$db->setQuery($sql);
			$wg_reports=NULL;
			$wg_reports=$db->loadObjectList();
			if($wg_reports)
			{
				$array[0]='block';
				foreach ($wg_reports as $key=>$v)
				{
					$parse['id']=$v->id;
					$parse['title']=$v->title;
					$list.=parsetemplate(gettemplate('village_map5'),$parse);
				}
				$array[1]=$list;				
			}			
			return $array;			
		}
	}
	return NULL;
}
/*---------------------------------------------------------------------------------------------------------------*/

if(!check_user()){ header("Location: login.php"); }
doAnounment();
includeLang('village_map');
global $db,$wg_village_kinds,$wg_oasis_troop,$wg_village,$user;
$village=$_COOKIE['villa_id_cookie'];
$wg_buildings=null;
$wg_village=null;

$wg_village=getVillage($village);
$wg_buildings=getBuildings($village);

getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());

$parse = $lang;
if(!isset($_GET['a'])&& !isset($_GET['b']))
{
	//header("Location:map.php");
}
elseif(is_numeric($_GET['a']) && is_numeric($_GET['b']))
{
	$x=$_GET['a'];$y=$_GET['b'];
	$sql="SELECT x,y,id,kind_id,user_id FROM wg_villages_map WHERE x=".$x." AND y=".$y."";
	$db->setQuery($sql);
	$query=null;
	$db->loadObject($query);
	if($query)
	{
		$parse['x']=$query->x;
		$parse['y']=$query->y;
		$village_found_id=$query->id;
		$kind_id=$query->kind_id;
		/*------------------------------------------------------------------------------*/
		foreach($wg_village_kinds as $sttBuild =>$objValue)
		{
			if($kind_id == $objValue['id'])
			{
				if($kind_id<7)
				{
					$parse['id_div']=$objValue['image'];
					$parse['image']=parsetemplate(gettemplate('village_map_image1'),$parse);
				}
				else
				{
					$parse['link_image']=$objValue['image'];
					$parse['image']=parsetemplate(gettemplate('village_map_image2'),$parse);
				}
			}			
		}
		/*---------------------- Lang co nguoi choi quan ly ------------------------------*/
		if($query->user_id>0)
		{
			$sql="SELECT tb1.name,tb1.nation_id,tb1.workers,tb1.child_id,tb2.name AS name_tribe,tb3.username FROM wg_villages AS tb1,wg_nations AS tb2,wg_users AS tb3 WHERE tb1.id=".$query->id." AND tb2.id=tb1.nation_id AND tb3.id=".$query->user_id."";
			$db->setQuery($sql);
			$wg_villages=null;
			$db->loadObject($wg_villages);
			$parse['name_village']=$wg_villages->name;	
			if($wg_villages->name=="NewName")
			{			
				$parse['name_village']=$lang['No_Name'];
			}
			$parse['capital']=GetCapital($query->id);
			//xuat thong tin hinh anh cua tai nguyen
			$parse['new_village']=FoundVillageStatus($village_found_id);
			$parse['send_troop']=LinkSendTroop($village,$query->x,$query->y);
			$parse['send_merchant']=LinkSendMerchant($query->x,$query->y);
			$array_ally=GetAlieVillage($query->user_id);
			$parse['alliance']=$array_ally[1];	
			$parse['username']=$wg_villages->username;
			$parse['userid']=$query->user_id;
			$parse['xy']=''.$query->x.'|'.$query->y.'';	
			$parse['name_tribe']=$wg_villages->name_tribe;
			$parse['display']='none';
			$parse['list_exchange_fire']='';
			if($user['alliance_id']==$array_ally[0])
			{
				$new_list=getListExchangeFire($array_ally[0],$query->user_id);
				$parse['list_exchange_fire']=$new_list[1];
				$parse['display']=$new_list[0];
			}	
			if($kind_id<7)
			{							
				$parse['name_tribe']=$wg_villages->name_tribe;				
				$parse['population']=$wg_villages->workers;					
				$parse['contend_village']=parsetemplate(gettemplate('village_map2'), $parse);
				$parse['list_troop']='';				
			}
			else
			{
				$sql="SELECT x,y,name FROM wg_villages WHERE id=".$wg_villages->child_id."";
				$db->setQuery($sql);
				$query=null;
				$db->loadObject($query);
				$parse['name_village']=$lang['No_Name'];
				$parse['village_owner']=$query->name;
				$parse['x1']=$query->x;
				$parse['y1']=$query->y;
				$parse['contend_village']=parsetemplate(gettemplate('village_map4'), $parse);
				$parse['list_troop']='';
				$parse['new_village']='';
				$parse['send_merchant']='';
			}
		}
		/*-------------------------------------------- lang chua co so huu ------------------------*/
		else
		{	
			$parse['capital']='';
			$parse['name_village']=$lang['No_Name'];
			$village_id=$_COOKIE['villa_id_cookie'];				
			$parse['send_troop']="";
			$parse['send_merchant']="";
			if($kind_id<7)
			{
				$parse['list_resource']=ShowResource($kind_id);
				$parse['contend_village']=parsetemplate(gettemplate('village_map6'),$parse);

				$parse['new_village']=FoundVillageStatus($village_found_id);
				$parse['display']='none';
			}
			else
			{
				$list_oasis_troop=showOasisTroopNew($village_found_id,$kind_id,time());
				$count=0;				
				for($j=34; $j<=43;$j++)
				{					
					$count+=$list_oasis_troop[$j];					
				}
				//echo "<pre>";print_r($list_oasis_troop);die('KQ:'.$count);
				if($count>0)
				{			
					for($j=34; $j<=43; $j++)
					{						
						if($list_oasis_troop[$j]>0)
						{
							$parse['num']=$list_oasis_troop[$j];
							$parse['images']=GetInfoTroop($j,2);//lay hinh anh
							$parse['nametroop']=GetInfoTroop($j,1);//lay ten
							$list.=parsetemplate(gettemplate('row_troop_oasis'), $parse);	
						}												
					}
				}
				else
				{
					$parse['num']=$lang['die_all'];
					$parse['images']='';
					$parse['nametroop']='';
					$list=parsetemplate(gettemplate('row_troop_oasis'), $parse);	
				}							
				$parse['new_village']=LinkSendTroop($village_id, $x, $y);
				$parse['rows']=$list;
				$parse['contend_village']=parsetemplate(gettemplate('village_map3'), $parse);
				$parse['display']='';
			}
		}
	}
	else
	{
		header("Location:map.php");
		exit();
	}
	$parse['link']="map.php?a=".$parse['x']."&b=".$parse['y'];
	$page = parsetemplate(gettemplate('village_map'), $parse);
	display($page,$lang['Village']);
}
else
{
	header("Location:map.php");
	exit();
}
ob_end_flush();
?>

