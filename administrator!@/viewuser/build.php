<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php');
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/nation_troop.php');
require_once($ugamela_root_path . 'includes/func_build.php');
require_once($ugamela_root_path . 'includes/func_convert.php');
require_once($ugamela_root_path . 'includes/function_resource.php');
require_once($ugamela_root_path . 'includes/function_status.php');
require_once($ugamela_root_path . 'includes/function_trade.php');
require_once($ugamela_root_path . 'includes/function_troop.php');
require_once($ugamela_root_path . 'includes/function_attack.php');
require_once($ugamela_root_path . 'includes/func_security.php');
include_once('../../soap/call.'.$phpEx);


if(!check_user()){ header("Location: login.php"); }
doAnounment();

global $db,$user,$wg_village,$wg_buildings,$village,$Main_time,$wg_buildings;
includeLang('build');
$parse = $lang;	

$village=$_COOKIE['villa_id_cookie'];
$wg_village=null;
$wg_status=null;
$wg_buildings=null;
$wg_village=getVillage($village);
$wg_status=getStatusProcessing();

$wg_buildings=getBuildings($village);
$Main_time=1;
foreach($wg_buildings as $key=>$value)
{
	if($value->type_id==12)
	{
		$Main_time=$value->product_hour*0.01;
		break;
	}
}
getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());
updateTrainTroopStatus($wg_village, time());
/*-------------------------------------------------------------------------------------------------------------------*/
// kiem tra ID theo vi tri tai nguyen va village theo ID cua lang
if(!empty($_GET['id']) && is_numeric($_GET['id']))
{
	if($wg_buildings[$_GET['id']-1])
	{
		/* nang cap tai nguyen ngoai lang */
		if($_GET['id']<=18)
		{
			$parse['content_update']=Update_Resource_Buidling(3,$_GET['id'],$user["id"],$_GET['id']-1,$village,$Main_time);
			$page = parsetemplate(gettemplate('build_body'), $parse);
			display($page,$lang['Title']);
		}
		/* xay dung cac cong trinh ben trong lang */
		elseif($_GET['id']>18 && $_GET['id']<=38)
		{
			$a=$wg_village->rs1;
			$b=$wg_village->rs2;
			$c=$wg_village->rs3;
			$d=$wg_village->rs4;
			if($_GET['id']>=33 && $_GET['id']<=36 && checkShowWorldWonder($wg_buildings,$village)==1 && $wg_buildings[$_GET['id']-1]->level==0)			
			{							
				if($_GET['id']<36)
				{
					header("Location:village2.php");
					exit();
				}			
				else
				{			
					$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],37,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
					$parse['show1']='';
					$parse['middle_content']="";
					$page = parsetemplate(gettemplate('build_body1'), $parse);
					display($page,$lang['Title']);
				}
			}			
			// xet cho truong hop xay moi
			elseif($_GET['id']==38 && $wg_buildings[$_GET['id']-1]->level==0) //'City_Wall'
			{
				$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],36,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
				$parse['show1']='';
				$parse['middle_content']="";
				$page = parsetemplate(gettemplate('build_body1'), $parse);
				display($page,$lang['Title']);
			}
			elseif($wg_buildings[$_GET['id']-1]->type_id==0)
			{
				$parse['show1']=$lang['73'];
				$parse['list building'].="";
				if($_GET['id']==37)
				{
					$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],27,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);//'Rally_Point'
				}
				else
				{
					$array=GetLevel($wg_buildings);				
					if($array[0]>=GetMaxLevel(10))
					{					
						$parse['list building'].=Buil_Infrastructure_More(1,$user["id"],10,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
					}
					if($array[1]>=GetMaxLevel(11))
					{
						$parse['list building'].=Buil_Infrastructure_More(1,$user["id"],11,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
					}
					if($array[2]==GetMaxLevel(15))					
					{
						$parse['list building'].=Buil_Infrastructure_More(1,$user["id"],15,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
					}
					// -> ket thuc xay them
					// bat dau xay moi 
					if(!checkExitBuidling($wg_buildings,10))
					{
						if(test_tech_tree(10,$village,$wg_buildings)==1)
						{	
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],10,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,11))
					{
						if(test_tech_tree(11,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],11,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,12))
					{
						$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],12,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);//'Main_Building'
					}					
					if(!checkExitBuidling($wg_buildings,15))
					{
						$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],15,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
					}		
					/*---------------------------------------------------------------------------------------------------*/
					if($user['embassy']==0)// Embassy -> chi cho phep 1 user xay 1 dai su quan duy nhat
					{
						$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],14,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
					}
					if(!checkExitBuidling($wg_buildings,13)) // Marketplace					
					{
						if(test_tech_tree(13,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],13,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,28)) //Barracks.
					{
						if(test_tech_tree(28,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],28,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,31))// xay nha Academy
					{					
						if(test_tech_tree(31,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],31,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,24))// xay nha Blacksmith
					{
						if(test_tech_tree(24,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],24,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,35))// xay nha Hero`s Mansion
					{
						if(test_tech_tree(35,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],35,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,5))// xay nha Grain_Mill
					{
						if(test_tech_tree(5,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],5,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,9))// xay nha Bakery
					{
						if(test_tech_tree(9,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],9,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,18))// xay nha Palace
					{
						if(test_tech_tree(18,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],18,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,29))// xay nha Stable
					{
						if(test_tech_tree(29,$village,$wg_buildings)==1)
						
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],29,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,6))//xay nha Sawmill
					{
						if(test_tech_tree(6,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],6,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,7))// xay nha Brickyard
					{
						if(test_tech_tree(7,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],7,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,8))// xay nha Iron Foundry
					{
						if(test_tech_tree(8,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],8,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,30))//xay nha Workshop
					{
						if(test_tech_tree(30,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],30,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					if(!checkExitBuidling($wg_buildings,20))// xay nha Trade Office
					{
						if(test_tech_tree(20,$village,$wg_buildings)==1)
						{
							$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],20,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
						}
					}
					// -> ket thuc xay moi
				}
				$parse['content_update']=$parse['list building'];
				$parse['soon list building1']=Show_Building_Available($village,$wg_buildings);
				$page = parsetemplate(gettemplate('build_body1'), $parse);
				display($page,$lang['Title']);
			}
			// update level cho cong trinh trong
			else
			{
				$parse['content_update']=Update_Resource_Buidling(2,$_GET['id'],$user["id"],$_GET['id']-1,$village,$Main_time);
				$page = parsetemplate(gettemplate('build_body'),$parse);
				display($page,$lang['Title']);
			} 	
		}
	}
	else
	{
		header("Location:build.php");
		exit();
	}	
}
/*-------------------------------------------------------------------------------------------------------------------*/
else 
{
	header("Location:index.php");
	exit();
}
ob_end_flush();
?>