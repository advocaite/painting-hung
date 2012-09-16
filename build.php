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
require_once($ugamela_root_path . 'soap/call.'.$phpEx);
require_once($ugamela_root_path . 'includes/wordFilter.php');
require_once($ugamela_root_path . 'includes/wg_config_plus.php');

checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
doAnounment();
doAllStatus();
$_SESSION['url']='build.php';
global $db,$user,$wg_village,$wg_buildings,$village,$Main_time,$wg_buildings,$timeAgain,$wordFilters,$wg_config_plus;
includeLang('build');
$parse = $lang;	
$timeAgain=$user['amount_time']-(time()-$_SESSION['last_login']);
// kiem tra tinh bao mat cho lang -> da loai bo loi SQL injection (is_numeric & $db->getEscaped)

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

$wg_village=$wg_status=$wg_buildings=NULL;
$wg_village=getVillage($village);
$wg_status=getStatusProcessing();
$wg_buildings=getBuildings($village);
$Main_time=1;
foreach($wg_buildings as $key=>$value)
{
	if($value->type_id==12 && $value->level >0)
	{
		$Main_time=$value->product_hour*0.01;
		break;
	}
}
getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());
updateTrainTroopStatus($wg_village, time());

// kiem tra ID theo vi tri tai nguyen va village theo ID cua lang -> da loai bo SQL injection
if(isset($_GET['id']) && is_numeric($_GET['id']))
{
	$_GET_ID=$_GET['id'];	
	if($wg_buildings[$_GET_ID-1])
	{
		if($_GET_ID <=18)
		{
			$parse['content_update']=Update_Resource_Buidling(3,$_GET_ID,$user["id"],$_GET_ID-1,$village,$Main_time);
			$page = parsetemplate(gettemplate('build_body'), $parse);
			display($page,'');
		}
		elseif($_GET_ID >18 && $_GET_ID <=38)
		{
			$a=$wg_village->rs1;
			$b=$wg_village->rs2;
			$c=$wg_village->rs3;
			$d=$wg_village->rs4;
			if($_GET_ID >=33 && $_GET_ID <=36 && checkShowWorldWonder($wg_buildings,$village)==1 && $wg_buildings[$_GET_ID-1]->level==0)			
			{							
				if($_GET_ID <36)
				{
					header("Location:village2.php");
					exit();
				}			
				else
				{			
					$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],37,$_GET_ID,$village,$a,$b,$c,$d,$Main_time);
					$parse['show1']='';
					$parse['middle_content']="";
					$page = parsetemplate(gettemplate('build_body1'), $parse);
					display($page,'');
				}
			}			
			// xet cho truong hop xay moi
			elseif($_GET_ID ==38 && $wg_buildings[$_GET_ID-1]->level ==0) //'City_Wall'
			{
				$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],36,$_GET_ID,$village,$a,$b,$c,$d,$Main_time);
				$parse['show1']='';
				$parse['middle_content']="";
				$page = parsetemplate(gettemplate('build_body1'), $parse);
				display($page,'');
			}
			elseif($wg_buildings[$_GET_ID-1]->type_id == 0 )
			{
				$parse['show1']=$lang['73'];
				$parse['list building'].="";
				if($_GET_ID ==37) //'Rally_Point'
				{
					$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],27,$_GET_ID,$village,$a,$b,$c,$d,$Main_time);
				}
				else
				{
					$array=GetLevel($wg_buildings);				
					if($array[0] >= GetMaxLevel(10))
					{					
						$parse['list building'].=Buil_Infrastructure_More(1,$user["id"],10,$_GET_ID,$village,$a,$b,$c,$d,$Main_time);
					}
					if($array[1] >= GetMaxLevel(11))
					{
						$parse['list building'].=Buil_Infrastructure_More(1,$user["id"],11,$_GET_ID,$village,$a,$b,$c,$d,$Main_time);
					}
					if($array[2] == GetMaxLevel(15))					
					{
						$parse['list building'].=Buil_Infrastructure_More(1,$user["id"],15,$_GET_ID,$village,$a,$b,$c,$d,$Main_time);
					}
					// -> ket thuc xay them
					
					// bat dau xay moi 
					if($user['embassy']==0)// Embassy -> chi cho phep 1 user xay 1 dai su quan duy nhat
					{
						$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],14,$_GET_ID,$village,$a,$b,$c,$d,$Main_time);
					}
					if(!checkExitBuidling($wg_buildings,12)) //'Main_Building'
					{
						$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],12,$_GET_ID,$village,$a,$b,$c,$d,$Main_time);
					}
					if(!checkExitBuidling($wg_buildings,15)) //Cranny
					{
						$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],15,$_GET['id'],$village,$a,$b,$c,$d,$Main_time);
					}
					$array=array(0=>10,1=>11,2=>13,3=>28,4=>31,5=>24,6=>35,7=>5,8=>9,9=>18,10=>29,11=>6,12=>7,13=>8,14=>30,15=>20);
					for($i=0;$i<=count($array)-1;$i++)
					{
						if(!checkExitBuidling($wg_buildings,$array[$i]))
						{
							if(test_tech_tree($array[$i],$village,$wg_buildings)==1)
							{	
								$parse['list building'].=Buil_Infrastructure_New(1,$user["id"],$array[$i],$_GET_ID,$village,$a,$b,$c,$d,$Main_time);
							}
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
				$parse['content_update']=Update_Resource_Buidling(2,$_GET_ID,$user["id"],$_GET_ID-1,$village,$Main_time);
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