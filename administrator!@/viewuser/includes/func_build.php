<?php
if ( !defined('INSIDE') )
{
	die("Hacking attempt");
}
define('Time_Delete',0.8); // thoi gian pha huy 1 cong trinh =80% thoi gian xay cong trinh do' o level do'
require_once('wg_village_kinds.php');
require_once('wg_building_types.php');
require_once('wg_tech_tree.php');
require_once('wg_oasis_troop.php');

/*-------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des: tinh so worker theo tung level
* @param: $level
* @return: so cong nhan tuong ung voi level truyen vao
*/
/* Lumber	iron	Clay	heromain	Brickyard	Ironfroundry	sawmill	grainmill	bakery	warehouse	granary	 */
function Worker_1($level)
{
	return round(($level/6)+1);
}
// academy	barrack		stable	trapper	workshop	blacksmith
function Worker_2($level)
{
	return round(($level/5)+1);
}
// tradeoffice	mainbuilding	market	embrassy	tresury	
function Worker_3($level)
{
	return round(($level/5)+2);
}
// crop		palace	
function Worker_4($level)
{
	return round(($level/10)+1);
}
//	rally	 wall   world_wonder
function Worker_5($level)
{
	return round(($level/11)+1);
}
// Cranny
function Worker_6($level)
{
	return round($level/10);
}
//iron mine
function Worker_7($level)
{
	return round(($level/4)+1);
}
/*--------------------------------------------------------------------------------------------------------------------------*/
/*
* @Author: duc hien
* @Des: cong thuc tinh Diem Danh Vong cua cac tai nguyen va cong trinh
* @param: $level
* @return: diem danh vong tung ung voi level do'
*/
// rally point	 hero mainsion	lumber	clay	iron	crop	brickyard	sawmill	iron froundry	grainmill	bakery	warehouse	granary	wall	barrack	
function CPn_1($level)
{
	if($level==0)
	{
		return 0;
	}
	return round(1+pow($level/6,3));
}
// workshop	market trade office
function CPn_2($level)
{
	if($level==0)
	{
		return 0;
	}
	return round(3+pow($level/4,3));
}
// Academy	palace	embrassy	
function CPn_3($level)
{
	if($level==0)
	{
		return 0;
	}
	return round(5+3*pow($level/5,3));
}
// treasury
function CPn_4($level)
{
	if($level==0)
	{
		return 0;
	}
	return round(10+pow($level/6,4));
}
/*------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des: suc chua cua 1 so loai cong trinh dac biet
* @param: $level	
* @return:	suc chua tuong ung voi level do'
*/
function SCn_Trade_Office($level)
{
	return 100+($level*10);
}
function SCn_Brickyard($level)
{
	return $level*5;
}
function SCn_Embassy($level)
{
	if($level<=2)
		return 0;
	if($level==3)
		return 9;
	return 3+SCn_Embassy($level-1);
}
function SCn_Barracks($level)
{
	if($level<=1)
		return 100;
	else
		return round(SCn_Barracks($level-1)/1.11);
}
function SCn_City_Wall($level)
{
	if($level<=1)
		return 4;
	else
		return SCn_City_Wall($level-1)+4;
}
//kinh te suc chua Cranry
function SCn_eco_cra($level)
{
	return $level*100;
}
// suc chua nha kho ( xai cho wood  iron clay cropland )
function SCn_eco_oth($level)
{
	//return(800+400*$level+4*pow(2*$level,2)+pow(2*$level,3));
	$value=array("0"=>800,"1"=>1224,"2"=>1728,"3"=>2360,"4"=>3168,"5"=>4200,"6"=>5504,"7"=>7128,"8"=>9120,"9"=>11528,"10"=>14400,"11"=>17784,"12"=>21728,"13"=>26280,"14"=>31488,"15"=>37400,"16"=>44064,"17"=>51528,"18"=>59840,"19"=>69048,"20"=>79200,"21"=>90344,"22"=>102528,"23"=>115800,"24"=>130208,"25"=>145800,"26"=>162624,"27"=>180728,"28"=>200160,"29"=>220968,"30"=>243200);
	return $value[$level];
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des: thoi gian nang cap tai nguyen va cong trinh
* @param: $level
* @return: thoi gian tung uong tung level
*/

//cong nghe thoi gian nang cap workshop
function Tn_tec_wor($level,$MBn) // ct sai
{
	return round((3000*pow(2-($level-1)/10,$level-1))*$MBn);
}
//cong nghe thoi gian nang cap chung  
function Tn_tec($level,$MBn)  // ->tang dung
{
	return round((2000+350*($level-1)+pow(2*($level-1),3))*$MBn);
}

//kinh te thoi gian nang cap xuong kim loai
function Tn_eco_iro($level,$MBn)  // -> CT sai neu test level >=7
{
	return round(6000*pow(2-($level-1)/10,$level-1)*$MBn);
}
//kinh te thoi gian nang cap xuong da va coi xay
function Tn_eco_bri($level,$MBn) // -> CT sai neu test level >=7
{
	return round(2000*pow(2-($level-1)/10,$level-1)*$MBn);
}
//kinh te thoi gian nang cap cho
function Tn_eco_mar($level,$MBn) // CT dung
{
	return round((1800+450*($level-1)+pow(2*($level-1),3))*$MBn);
}
//kinh te thoi gian nang cap sawmill
function Tn_eco($level,$MBn)  // CT sai neu level >=7
{
	return round(3000*pow(2-($level-1)/10,$level-1)*$MBn);
}
//kinh te thoi gian nang cap Cranry
function Tn_eco_cra($level,$MBn) // CT dung
{
	return round((2000+350*($level-1)+pow(2*($level-1),3))*$MBn/2);
}
//kinh te thoi gian nang cap Ganary va Warehouse
function Tn_eco_oth($level,$MBn) // CT dung
{
	return round((2000+350*($level-1)+pow(2*($level-1),3))*$MBn);
}
// Main Building thoi gian nang cap
function Tn($level) // CT dung
{
	return round(2400+10*ceil(0.09*pow(9*$level,2)));
}
//quan su thoi gian nang cap hoi anh hung
function Tn_tro_her($level,$MBn) // Ct dung
{
	return round((2300+450*($level-1)+pow(1.6*($level-1),3))*$MBn);
}
//quan su : thoi gian nang cap chung -> su dung trong ham Buil_Infrastructure_New()
function Tn_tro($level,$MBn) // Ct dung
{
	return	round((2000+350*($level-1)+pow(2*($level-1),3))*$MBn);
}
//quan su thoi gian nang cap trai ngua
function Tn_tro_sta($level,$MBn) // CT dung
{
	return round((2000+500*($level-1)+pow(2*($level-1),3))*$MBn);
}
//thoi gian nang cap tai nguyen
function Tn_Crop($level,$MBn) // CT dung
{
	//return round((99+pow($level*7,2)+pow($level*1.25,4)+pow($level*0.225,10))*$MBn);
	$value=array("0"=>99,"1"=>150,"2"=>334,"3"=>738,"4"=>1508,"5"=>2853,"6"=>5047,"7"=>8456,"8"=>13592,"9"=>21246,"10"=>32738,"11"=>50397,"12"=>78369,"13"=>123950,"14"=>199677,"15"=>326471,"16"=>538259,"17"=>888538,"18"=>1459536,"19"=>2374691,"20"=>3815387,"21"=>6043004,"22"=>9427585,"23"=>14484604,"24"=>21921575,"25"=>32696518,"26"=>48090571,"27"=>69797371,"28"=>100032169,"29"=>141664024,"30"=>198374823);
	return round($value[$level]*$MBn);
}
function Tn_Lumber($level,$MBn) // CT dung
{
	//return round((209+pow($level*7,2)+pow($level*1.3,4)+pow($level*0.23,10))*$MBn);
	$value=array("0"=>209,"1"=>261,"2"=>451,"3"=>881,"4"=>1725,"5"=>3223,"6"=>5700,"7"=>9585,"8"=>15488,"9"=>24361,"10"=>37813,"11"=>58699,"12"=>92139,"13"=>147173,"14"=>239361,"15"=>394710,"16"=>655420,"17"=>1088070,"18"=>1795027,"19"=>2929995,"20"=>4718860,"21"=>7487168,"22"=>11695833,"23"=>17986942,"24"=>27241827,"25"=>40653898,"26"=>59819105,"27"=>86847292,"28"=>124498131,"29"=>176345812,"30"=>246977156);
	return round($value[$level]*$MBn);	
}
function Tn_Iron($level,$MBn) // CT dung
{
	//return round((383+pow($level*8.5,2)+pow($level*1.35,4)+pow($level*0.2375,10))*$MBn);
	$value=array("0"=>383,"1"=>459,"2"=>725,"3"=>1302,"4"=>2390,"5"=>4271,"6"=>7323,"7"=>12059,"8"=>19225,"9"=>30019,"10"=>46533,"11"=>72566,"12"=>115017,"13"=>186176,"14"=>307307,"15"=>514058,"16"=>864378,"17"=>1449811,"18"=>2411207,"19"=>3960166,"20"=>6407764,"21"=>10202428,"22"=>15979152,"23"=>24622636,"24"=>37347328,"25"=>55797810,"26"=>82173490,"27"=>119382083,"28"=>171226977,"29"=>242634231,"30"=>339925642);
	return round($value[$level]*$MBn);	
}
function Tn_Clay($level,$MBn) // CT dung
{
	//return round((168+pow($level*7,2)+pow($level*1.3,4)+pow($level*0.23,10))*$MBn);
	$value=array("0"=>168,"1"=>220,"2"=>410,"3"=>840,"4"=>1684,"5"=>3182,"6"=>5659,"7"=>9544,"8"=>15447,"9"=>24320,"10"=>37772,"11"=>58658,"12"=>92098,"13"=>147132,"14"=>239320,"15"=>394669,"16"=>655379,"17"=>1088029,"18"=>1794986,"19"=>2929954,"20"=>4718819,"21"=>7487127,"22"=>11695792,"23"=>17986901,"24"=>27241786,"25"=>40653857,"26"=>59819064,"27"=>86847251,"28"=>124498090,"29"=>176345771,"30"=>246977115);
	return round($value[$level]*$MBn);	
}
function Tn_embassy($level,$MBn)  // CT dung
{
	return round((1800+100*$level+65*pow($level,2)+pow(1.55*$level,3))*$MBn);
}
function Tn_world_wonder($level,$MBn)
{
	return round((6374+26*pow($level,2))*$MBn);
}
function timeUpdateAll($type,$level,$MBn)
{
	switch($type)
	{
		case 1: //lumber		
			$result=Tn_Lumber($level,$MBn);
			break;
		case 2: // Cropland		
			$result=Tn_Crop($level,$MBn);
			break;
		case 3: // Iron_Mine
			$result=Tn_Iron($level,$MBn);
			break;
		case 4: // Clay_Pit
			$result=Tn_Clay($level,$MBn);
			break;
		case 5:
			$result=Tn_eco_bri($level,$MBn);
			break;
		case 6:
			$result=Tn_eco($level,$MBn);
			break;
		case 7:
			$result=Tn_eco_bri($level,$MBn);
			break;
		case 8:
			$result=Tn_eco_iro($level,$MBn);
			break;
		case 9:
			$result=Tn_eco_bri($level,$MBn);
			break;
		case 10:
			$result=Tn_eco_oth($level,$MBn);
			break;
		case 11:
			$result=Tn_eco_oth($level,$MBn);
			break;
		case 12:
			$result=Tn($level,$MBn);
			break;	
		case 13:
			$result=Tn_eco_mar($level,$MBn);
			break;
		case 14:
			$result=Tn_embassy($level,$MBn);
			break;
		case 15:
			$result=Tn_eco_cra($level,$MBn);
			break;
		case 18:
			$result=Tn_tro($level,$MBn);
			break;	
		case 20:
			$result=Tn_tro($level,$MBn);
			break;	
		case 24:
			$result=Tn_tro($level,$MBn);
			break;	
		case 27:
			$result=Tn_tro($level,$MBn);
			break;	
		case 28:
			$result=Tn_tro($level,$MBn);
			break;
		case 29:
			$result=Tn_tro_sta($level,$MBn);
			break;
		case 30:
			$result=Tn_tro($level,$MBn);
			break;
		case 31:
			$result=Tn_tro($level,$MBn);
			break;	
		case 35:
			$result=Tn_tro_her($level,$MBn);
			break;	
		case 36:
			$result=Tn_tro($level,$MBn);
			break;	
		case 37:
			$result=Tn_world_wonder($level,$MBn);
			break;													
	}
	return $result;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	He so % cua main building
* @param: $level
* @return: % tuong ung level do'
*/
function MBn($level)
{
	return ((100-($level-1)*3)/100);
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	ham tinh suc tang tai nguyen theo tung level nhap vao
* @param: $level
* @return: suc tang cua tai nguyen tuong ung voi level do'
*/
//tai nguyen toc do khai thac
function KTn_res($level)
{
	//return round(3*$level+0.03*pow($level,3)+0.014*pow($level,4),0)+2;
	$value=array("0"=>2,"1"=>5,"2"=>8,"3"=>13,"4"=>20,"5"=>30,"6"=>45,"7"=>67,"8"=>99,"9"=>143,"10"=>202,"11"=>280,"12"=>380,"13"=>507,"14"=>664,"15"=>857,"16"=>1090,"17"=>1370,"18"=>1701,"19"=>2089,"20"=>2542,"21"=>3066,"22"=>3667,"23"=>4354,"24"=>5134,"25"=>6015);
	return $value[$level];
	
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	tai nguyen can thiet de nang cap cong trinh ( tai nguyen + building + quan su) level>=2
* @param:	$type,$level,$rs
* @return: gia tri can thiet de nang cap tuong ung voi level do'
*/
function RSn($type,$level,$rs)
{
	if($type<=4) // tai nguyen ngoai thanh
	{
		return RSn1($level,$rs);
	}	
	elseif($type<=9) //5 nha giup tang % 4 loai tai nguyen
	{
		return RSn3($level,$rs);
	}
	elseif($type==35) // nha hero_mainsion
	{
		return RSn4($level,$rs);
	}
	elseif($type==37) // Ky dai
	{
		return RSn5($level,$rs);
	}
	else
	{
		return RSn2($level,$rs);
	}			
}
function RSn1($level,$rs)
{
	if($level<=1)
		return $rs;
	else
		return round(RSn1($level-1,$rs)*1.68);
}
function RSn2($level,$rs)
{
	if($level<=1)
		return $rs;
	else
		return round(RSn2($level-1,$rs)*1.28);
}
function RSn3($level,$rs)
{
	if($level<=1)
		return $rs;

	else
		return round(RSn3($level-1,$rs)*1.8);
}
function RSn4($level,$rs)
{
	if($level<=1)
		return $rs;
	else
		return round(RSn4($level-1,$rs)*1.33);
}
function RSn5($level,$rs)
{
	if($level<=1)
		return $rs;
	else
		return round(RSn5($level-1,$rs)*1.027);
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	ham lay thoi gian =giay tra ve aa:bb:cc
* @param:
* @return: AA:BB:CC
*/
function ReturnTime($number_seconds)
{
	$time="";
	$hours=0;
	$mins=0;
	$secs=0;
	while($number_seconds>=3600)
	{
		$number_seconds = $number_seconds-3600;
		$hours++;
	}
	while($number_seconds>=60)
	{
		$number_seconds=$number_seconds-60;
		$mins++;
	}
	if($mins<10)
	{
		$mins='0'.$mins;
	}
	$secs = $number_seconds;
	if($secs<10)
	{
		$secs='0'.$secs;
	}
	return $time= $hours . ':' . $mins . ':' . $secs;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	ham lay thoi gian =giay tra ve aa:bb:cc
* @param: $seconds
* @return: aa:bb:cc
*/
function laythoigian($seconds)
{
	$out_date=date("Y-m-d H:i:s",$seconds);
	return $out_date;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
/*----------------------------------------------------------------------------------------------------------------------*/
function getWorker($type_id,$level)
{
	if($type_id==6 ||$type_id==7 ||$type_id==8||$type_id==5||$type_id==9||$type_id==35||$type_id==10 || $type_id==11||$type_id==1 || $type_id==4)
	{
		return Worker_1($level);
	}
	else if($type_id==29||$type_id==31||$type_id==30||$type_id==24||$type_id==28)
	{
		return Worker_2($level);
	}
	elseif($type_id==14||$type_id==12||$type_id==13||$type_id==20)
	{
		return Worker_3($level);
	}
	elseif($type_id==2||$type_id==18)
	{
		return Worker_4($level);
	}
	else if($type_id==36 ||$type_id==27 || $type_id==37)
	{
		return Worker_5($level);	
	}
	elseif($type_id==15)
	{
		return Worker_6($level);
	}
	elseif($type_id==3)
	{
		return Worker_7($level);
	}
}
function getCpAll($type_id,$level)
{
	if($type_id==1 || $type_id==2  || $type_id==3 || $type_id==4 || $type_id==12 || $type_id==15 || $type_id==28 || $type_id==24 || $type_id==36 || $type_id==35 || $type_id==27 || $type_id==29 || $type_id==5 || $type_id==6 || $type_id==7 || $type_id==8 || $type_id==9 || $type_id==10 || $type_id==11)
	{
		 return CPn_1($level);
	}
	elseif($type_id==30 || $type_id==13  || $type_id==20)
	{
		return CPn_2($level);
	}
	elseif($type_id==31 || $type_id==18  || $type_id==14)
	{
		return CPn_3($level);
	}
	elseif($type_id==37)
	{
		return 0;
	}
}
function getProductNew($type_id,$level)
{
	global $game_config;
	$Hs_K=$game_config['k_game'];
	if($type_id<=4)
	{
		return KTn_res($level)*$Hs_K;
	}
	elseif($type_id==14 || $type_id==31 || $type_id==24 || $type_id==30 ||$type_id==29 || $type_id==27 || $type_id==35)
	{
		return SCn_Embassy($level);
	}
	elseif($type_id==12)
	{
		return MBn($level)*100;
	}
	elseif($type_id==15)//"Cranny")
	{
		return SCn_eco_cra($level);
	}
	elseif($type_id==13)//"Marketplace"
	{
		return SCn_Marketplace($level);
	}
	elseif($type_id==20)//"Trade_Office")
	{
		return SCn_Trade_Office($level);
	}
	elseif($type_id==28)//"Barracks")
	{
		return SCn_Barracks($level);
	}
	elseif($type_id==36)//"City_Wall")
	{
		return SCn_City_Wall($level);
	}
	elseif($type_id==5 ||$type_id==6 || $type_id==7 ||$type_id==8 || $type_id==9)// su dung cho 5 loai nha tang % RS ben ngoai
	{
		return SCn_Brickyard($level);
	}
	elseif($type_id==10 ||$type_id==11)//"Warehouse"  "Granary"
	{
		return SCn_eco_oth($level);
	}
	return 0;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
function destroyByCata($id,$type_id,$level,$village)
{
	global $db;	
	if($level-1 <=0)
	{
		if($type_id <=4)
		{
			$sql="UPDATE wg_buildings SET level=0,product_hour=0,cp=0 WHERE id=".$id;
			$db->setQuery($sql);
			$db->query();
			if($db->getAffectedRows()==0)
			{
				globalError2('function destroyByCata:'.$sql);
			}		
		}
		else
		{
			if($type_id ==14) //toa dai su ->cap nhat trang thai da chua  ton tai embassy trong wg_user 
			{				
				$sql="UPDATE wg_users  SET embassy=0 WHERE id=(SELECT user_id FROM wg_villages WHERE id=".$village.")";
				$db->setQuery($sql);
				$db->query();
				if($db->getAffectedRows()==0)
				{
					globalError2('function destroyByCata:'.$sql);
				}
			}
			$sql="UPDATE wg_buildings SET name='',img='',type_id=0,level=0,product_hour=0,cp=0 WHERE id=".$id;
			if($type_id==36)
			{
				$sql="UPDATE wg_buildings SET name='City_Wall',img='',type_id=36,level=0,product_hour=0,cp=0 
				WHERE id=".$id;
			}
			$db->setQuery($sql);
			$db->query();
			if($db->getAffectedRows()==0)
			{
				globalError2('function destroyByCata:'.$sql);
			}	
		}	
	}
	else
	{
		$sql="UPDATE wg_buildings SET level=level-1 WHERE id=".$id;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function destroyByCata:'.$sql);
		}
	}	
	return true;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	hoan thien qua trinh xay moi cong trinh 
* @param:$index= vi tri thu tu cua cong trinh trong map
* @return: 
*/

function ConstructBuilding($index,$village,$type_status,$userid)
{
	global $db;
	$sql="SELECT id,type_id FROM wg_buildings WHERE `index`=$index AND vila_id=$village";
	$db->setQuery($sql);	
	$query=NULL;
	$db->loadObject($query);
	$type_id=$query->type_id;
	$images=Get_Images2($type_id,1);
	$cp=getCpAll($type_id,1);
	$product_hour_new=getProductNew($type_id,1);
	$string='';
	$sql="UPDATE wg_buildings SET img='$images',level=1,product_hour=$product_hour_new,cp=$cp WHERE id=".$query->id;
	$db->setQuery($sql);
	$db->query();
	if($db->getAffectedRows()==0)
	{
		globalError2('function ConstructBuilding:'.$sql);
	}
	if($type_id >=5 && $type_id <=9)
	{
		$time_check=strtotime(date("Y-m-d H:i:s",time()));
		$array=returnKrsForVillage($userid,$village,$time_check);
		$string=$array['char'];
	}
		
	$Workers_new=getWorker($type_id,1);	
	if($Workers_new>0)
	{
		$sql="UPDATE wg_villages SET  workers=workers+".$Workers_new." ".$string." WHERE id=".$village;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function ConstructBuilding:'.$sql);
		}
		
		$sql="UPDATE wg_users SET population=population+".$Workers_new." WHERE id=".$userid;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function ConstructBuilding:'.$sql);
		}
	}
	return true;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
// wood,clay,iron,cropland
function updateResourceOutSide($index,$village,$level,$userid)
{
	global $db,$game_config;
	$Hs_K=$game_config['k_game'];
	$sql="SELECT id,type_id FROM wg_buildings WHERE `index`=".$index." AND vila_id=".$village;
	$db->setQuery($sql);	
	$query=NULL;
	$db->loadObject($query);
	$type_id=$query->type_id;
	$cp=CPn_1($level);
	$product_hour_new=KTn_res($level)*$Hs_K;
	$sql="UPDATE wg_buildings SET level=level+1,product_hour=".$product_hour_new.",cp=".$cp." WHERE id=".$query->id;
	$db->setQuery($sql);
	$db->query();
	if($db->getAffectedRows()==0)
	{
		globalError2('function updateResourceOutSide:'.$sql);
	}
	
	$Workers_new=getWorker($type_id,$level);
	if($Workers_new>0)
	{
		$sql="UPDATE wg_villages SET workers=workers+".$Workers_new." WHERE id=".$village;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function updateResourceOutSide:'.$sql);
		}
		
		$sql="UPDATE wg_users SET population=population+".$Workers_new." WHERE id=".$userid;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function updateResourceOutSide:'.$sql);
		}
	}
	return true;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	su dung cho nang cap va pha huy cong trinh trong main building 
* @param: $value=type cua status ,$index,$village,$level
* @return: giam level, krs,xoa huy hoan toan tuy theo muc do giam cua level
*/
function updateBuilding($index,$village,$level,$userid)
{
	global $db;
	$sql="SELECT id,type_id FROM wg_buildings WHERE `index`=".$index." AND vila_id=".$village;
	$db->setQuery($sql);	
	$query=NULL;
	$db->loadObject($query);
	$type_id=$query->type_id;
	$cp=getCpAll($type_id,$level);
	$images=Get_Images2($type_id,$level);
	$product_hour_new=getProductNew($type_id,$level);
	$string='';
	$sql="UPDATE wg_buildings SET img='$images',level=level+1,product_hour=$product_hour_new,cp=$cp WHERE id=".$query->id;
	$db->setQuery($sql);
	$db->query();
	if($db->getAffectedRows()==0)
	{
		globalError2('function updateBuilding:'.$sql);
	}		
	if($type_id >=5 && $type_id <=9)
	{
		$time_check=strtotime(date("Y-m-d H:i:s",time()));
		$array=returnKrsForVillage($userid,$village,$time_check);
		$string=$array['char'];
	}	
	if($type_id==37 && $level>10) // tao linh am binh
	{
		include_once("function_attack.php");
		sendAttackWonder($village,time());
	}
	if($type_id >=5 && $type_id !=10 && $type_id !=11)
	{
		if($level>GetMaxLevel($type_id))
		{
			return false;
		}
	}
	$Workers_new=getWorker($type_id,$level);
	if($Workers_new>0)
	{	
		$sql="UPDATE wg_villages SET workers=workers+".$Workers_new." ".$string." WHERE id=".$village;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function updateBuilding:'.$sql);
		}
		
		$sql="UPDATE wg_users SET population=population+".$Workers_new." WHERE id=".$userid;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function updateBuilding:'.$sql);
		}
	}
	return true;
}
function destroyBuilding($index,$village,$level,$userid)
{
	global $db;	
	$sql="SELECT id,type_id FROM wg_buildings WHERE `index`=$index AND vila_id=$village";
	$db->setQuery($sql);	
	$query=NULL;
	$db->loadObject($query);
	$type_id=$query->type_id;
	$cp=getCpAll($type_id,$level);
	$product_hour_new=getProductNew($type_id,$level);
	if($level ==0)
	{
		if($type_id ==14 ) //embassy
		{
			/*--cap nhat trang thai da ton tai embassy trong wg_user --*/
			$sql="UPDATE wg_users  SET embassy=0 WHERE id=".$userid;
			$db->setQuery($sql);
			$db->query();
			if($db->getAffectedRows()==0)
			{
				globalError2('function destroyBuilding:'.$sql);
			}
		}
		$sql="UPDATE wg_buildings SET name='',img='',type_id=0,level=0,product_hour=0,cp=0 WHERE id=".$query->id;
		if($type_id==36)
		{
			$sql="UPDATE wg_buildings SET name='City_Wall',img='',type_id=36,level=0,product_hour=0,cp=0 WHERE id=".$query->id;
		}		
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function destroyBuilding:'.$sql);
		}	
	}
	else
	{
		$sql="UPDATE wg_buildings SET level=$level,product_hour=$product_hour_new,cp=$cp WHERE id=".$query->id;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function destroyBuilding:'.$sql);
		}
	}
	if($type_id >=5 && $type_id <=9)
	{
		$time_check=strtotime(date("Y-m-d H:i:s",time()));
		$array=returnKrsForVillage($userid,$village,$time_check);
		$string=$array['char'];
	}
	$Workers_new=getWorker($type_id,$level+1);	
	if($Workers_new >0)
	{	
		$sql="UPDATE wg_villages SET workers=workers-".$Workers_new." ".$string." WHERE id=".$village;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function destroyBuilding:'.$sql);
		}
		
		$sql="UPDATE wg_users SET population=population-".$Workers_new." WHERE id=".$userid;
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function destroyBuilding:'.$sql);
		}
	}
	return true;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	kiem tra thong tin tu bang wg_status va xuat thong tin build
* @param: $id
* @return: trang thai cua tai nguyen dang xay dung
*/
function ShowBuild($id,$wg_status)
{
	global $db,$lang;
	$parse=$lang;
	$kq='';
	$sql="SELECT * FROM wg_status WHERE village_id=$id AND type<4 AND status =0 ORDER BY time_end";
	$db->setQuery($sql);
	$statusList=null;
	$statusList=$db->loadObjectList();
	if($statusList)
	{
		$i=0;
		$list='';
		$row=gettemplate('show_build_row');
		foreach($statusList as $status)
		{
			$i++;
			$temp=strtotime($status->time_end)-time();
			$timeEnd=substr($status->time_end,11);
			$parse['id']=$status->id;
			$level=$status->level;
			if($status->level==0)
			{
				$level=1;
			}
			$parse['content']="".$lang[GetNameRS($status->object_id)]." (".$lang['level']." ".$level.")";
			$parse['i']=$i;
			$parse['times']=ReturnTime($temp);
			$parse['timeEnd']=$timeEnd;
			$list.= parsetemplate($row,$parse);			
		}
		$parse['row']=$list;
		$kq=parsetemplate(gettemplate('show_build'), $parse);
	}	
	$result['building_level_up_status']=$kq;
	$result['i']=$i;
	return $result;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
// lay ten tai nguyen dung de cho update level
function GetNameRS($index)
{
	global $wg_buildings;
	foreach ($wg_buildings as $key => $ptu)
	{
		if($index==$ptu->index)
		{
			return $ptu->name;
		}
	}
	return false;	
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	 huy bo lenh update va xay dung
* @param: $id->so thu tu trong status
* @return: 
*/
function DeleteStatus($id,$village,$type_status,$object_id)
{
	global $db,$user,$wg_village,$village,$wg_building_types;	
	$sql="DELETE FROM wg_status WHERE id=$id";
	$db->setQuery($sql);
	$db->query();
	if($db->getAffectedRows()==0)
	{
		globalError2('function DeleteStatus(:'.$sql);
	}	
	$sql="SELECT type_id,level FROM wg_buildings WHERE `index`=$object_id AND vila_id=$village";
	$db->setQuery($sql);
	$query=null;
	$db->loadObject($query);
	$type_id = $query->type_id;
	$level=$query->level;
	foreach($wg_building_types as $sttBuild =>$objValue)
	{
		if($type_id == $objValue['id'])
		{
			$Lumber=RSn($type_id,$level+1,$objValue['rs1']);
			$Clay=RSn($type_id,$level+1,$objValue['rs2']);
			$Iron=RSn($type_id,$level+1,$objValue['rs3']);
			$Crop=RSn($type_id,$level+1,$objValue['rs4']);
			break;
		}			
	}
	$wg_village->rs1=$wg_village->rs1+$Lumber;
	$wg_village->rs2=$wg_village->rs2+$Clay;
	$wg_village->rs3=$wg_village->rs3+$Iron;
	$wg_village->rs4=$wg_village->rs4+$Crop;
	if($type_status==1)
	{
		if($type_id==14)
		{
			$sql="UPDATE wg_users  SET embassy=0 WHERE id=".$user['id']."";
			$db->setQuery($sql);
			$db->query();
			if($db->getAffectedRows()==0)
			{
				globalError2('function DeleteStatus:'.$sql);
			}
		}
		elseif($type_id==37)
		{
			$sql="UPDATE wg_rare SET kim=kim+1,thuy=thuy+1,moc=moc+1,hoa=hoa+1,tho=tho+1 WHERE vila_id=".$village."";
			$db->setQuery($sql);
			$db->query();
			if($db->getAffectedRows()==0)
			{
				globalError2('function DeleteStatus:'.$sql);
			}
		}
		// neu la xay moi cong trinh thi update cho ID tai cong trinh do rong
		$name='';
		if($type_id==36)
		{
			$name='City_Wall';
		}
		$sql="UPDATE wg_buildings SET name='$name',img='',level=0,type_id=0,product_hour=0 
		WHERE `index`=$object_id AND vila_id=".$wg_village->id."";
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function DeleteStatus:'.$sql);
		}
	}
	return true;
}
/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
* @Author: Le Van Tu
* @Des:	show phan noi dung o giua cua cac building lien quan den troop va giao thuong 
* @param: index cua building hien tai
* @return: 
*/
function GetMiddleContent($building)
{
	global $db, $lang, $wg_village;
	switch($building->type_id){
		case 31://Academy
			include_once("function_status.php");
			include_once("function_troop.php");
			return Research($building);
			break;
		case 13://Marketplace
			//doi voi cho.
			include_once("function_status.php");
			include_once("function_trade.php");
			return Trade($building);
			break;
		case 28://Barracks
			include_once("function_status.php");
			include_once("function_troop.php");
			return Train($building, 4);
			break;
		case 29://Stable
			include_once("function_status.php");
			include_once("function_troop.php");
			return Train($building, 12);
			break;
		case 30://Workshop
			include_once("function_status.php");
			include_once("function_troop.php");
			return Train($building, 20);
			break;
		case 18://Palace
			include_once("function_status.php");
			include_once("function_troop.php");
			return ShowPalace($building);
			break;
		case 27://Rally_Point
			include_once("function_status.php");
			include_once("function_troop.php");
			return ShowRallyPoint($building);
			break;
		case 24: //Blacksmith
			include_once("function_status.php");
			include_once("function_troop.php");
			return ShowImprovement($building);
			break;
		case 35: //Hero_Mansion
			include_once("function_status.php");
			include_once("function_troop.php");
			return ShowHeroMansion($building);
			break;
	}
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
// ham kiem tra user da co allie hay chua
function Check_Allies_Exist($userid)
{
	global $db;
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_allies WHERE user_id=$userid LIMIT 1";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	return $count;	
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
//ham kiem tra user da co loi moi hay chua va chua gia nhap
function Check_Allies_Invite($userid,$check)
{
	global $db;
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_ally_members WHERE user_id=$userid AND right_=$check";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	return $count;	
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
//liet ke danh sach cac loi moi gia nhap cua tung user
function List_Invite($userid)
{
	global $db,$lang;
	$parse=$lang;
	$sql="SELECT id,ally_id FROM wg_ally_members WHERE user_id=$userid  ORDER BY id";
	$db->setQuery($sql);
	$elements = $db->loadObjectList();
	$count=0;
	if($elements)
	{	
		$row='';
		foreach($elements as $result)
		{
			$sql="SELECT id,name FROM wg_allies WHERE id=".$result->ally_id."";
			$db->setQuery($sql);
			$query=null;
			$db->loadObject($query);
			$parse['id1']=$result->id;
			$parse['id2']=$query->id;
			$parse['tag']=$query->name;
			$parse['link']=$_SERVER['REQUEST_URI'];
			$row.=parsetemplate(gettemplate('list_invite_row'),$parse);
			$count++;
		}
		$parse['count']=$count;
		$parse3=parsetemplate(gettemplate('list_invite_title'),$parse);
		$parse['row']=$row;
		$parse2=parsetemplate(gettemplate('list_invite'),$parse);
	}
	$parse="".$parse3."".$parse2."";
	return $parse;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	ham nay kiem tra trong status xem co cong trinh nao dang trong qua trinh pha huy -> neu co thi khi vao cong trinh do nang cap level se xuat hien thong tin cong  trinh nay dang bi pha , khong cho update duoc
* @param:
* @return: 0 hoac 1
*/
function Check_Status_Destroy($index,$wg_status)
{
	if($wg_status)
	{
		foreach($wg_status as $ptu)
		{
			if($ptu->type==17 && $ptu->object_id==$index)
			{
				return 1;
			}
		}
	}
	return 0;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	Phan Pha huy cong trinh cua MainBuiding
* @param:$village= so id cua village,$MBn = He so phan tram tung uong voi level cua nha chinh,$Hs_K = he so speed cua world,
* @param:$userid= so ID cua user
* @return: template cua noi dung pha huy 
*/
function Demolish_main_building($village,$MBn,$Hs_K,$userid,$wg_buildings)
{
	global $db, $lang,$wg_status;
	includeLang('build');
	if(count($_POST)<>0 && !isset($_GET["tab"]))
	{
		if(Check_Status_Destroy($_POST['index'],$wg_status)==0)
		{
			foreach($wg_buildings as $ptu)
			{
				if($ptu->index==$_POST['index'])
				{
					$type_id=$ptu->type_id;
					$level=$ptu->level;
					$index=$ptu->index;
					break;
				}
			}
			$time_begin=laythoigian(time());
			$Time_Cost=round((timeUpdateAll($type_id,$level,$MBn)/$Hs_K)*constant("Time_Delete"));		
			$time_end=laythoigian(time()+$Time_Cost);	
			$level_new=$level-1;	
			$sql="INSERT INTO `wg_status` (`object_id`, `village_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`,`level`) VALUES (".$index.", $village, 17, '$time_begin', '$time_end','$Time_Cost', 0,".$userid.",".$level_new.");";
			$db->setQuery($sql);
			if(!$db->query())
			{
				globalError2('function Demolish_main_building:'.$sql);
			}
			header("Location:build.php?id=".$_GET['id']."");
			exit();			
		}
	}	
	$parse=$lang;
	$row = gettemplate('build_demolish_building');
	foreach($wg_buildings as $ptu)
	{
		if($ptu->level>0 && $ptu->type_id>=5 && $ptu->type_id!=12 && $ptu->type_id!=37 )
		{
			$list.='<option value="'.$ptu->index.'">'.$ptu->index.'. '.$lang[''.$ptu->name.''].' ('.$lang['level'].' '.$ptu->level.')</option>';
		}	
	}
	$parse['list']=$list;	
	$sql = "SELECT id,object_id,level,time_end FROM wg_status WHERE village_id=$village AND type=17 AND status=0";
	$db->setQuery($sql);
	$query=null;
	$db->loadObject($query);
	if($query)
	{
		$parse['link']='build.php?'.$_SERVER['QUERY_STRING'].'&cancel='.$query->id;
		$parse['time']=ReturnTime(strtotime($query->time_end)-time());
		$parse['timeend']=substr($query->time_end,11);
		$parse['name']=$lang[GetNameRS($query->object_id)].' ('.$lang['fix_level'].' '.$query->level.')';
		$parse['destroy']=parsetemplate(gettemplate('build_destroy_main'),$parse);
	}
	else
	{
		$parse['destroy']='';
	}
	return parsetemplate($row,$parse);
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	show thong tin cong trinh dang trong qua trinh pha huy
* @param:$village= so id cua village,$MBn = He so phan tram tung uong voi level cua nha chinh,$Hs_K = he so speed cua world,
* @param:$userid= so ID cua user
* @return: show thong tin cong trinh dang trong qua trinh pha huy
*/
function Demolish_destroy($village,$MBn,$Hs_K,$userid,$wg_status)
{
	global $lang;
	$parse=$lang;
	foreach($wg_status as $ptu)
	{
		if($ptu->type==17)
		{
			$parse['link']='build.php?id='.$_GET['id'].'&cancel='.$ptu->id;
			$parse['time']=ReturnTime(strtotime($ptu->time_end)-time());
			$parse['timeend']=substr($ptu->time_end,11);
			$parse['name']=$lang[GetNameRS($ptu->object_id)].' ('.$lang['fix_level'].' '.$ptu->level.')';
			$row = gettemplate('build_destroy');
			return parsetemplate($row,$parse);
		}
	}
	return NULL;	
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: quang
* @Des:	
* @param:
* @return: 
*/
function Creat_Allies($userid)
{
	global $db,$lang;
	$parse=$lang;
	$check=0;
	if(!empty($_GET['create']))
	{
		if(strip_tags($_REQUEST['ally1'])=="")
		{
			$check++;
			$error1=$lang['error1'];
		}
		if(strip_tags($_REQUEST['ally2'])=="")
		{
			$check++;
			$error2=$lang['error2'];
		}
		if($check==0)
		{
			include('wordFilter.php');
			$value1=strip_tags($_REQUEST['ally1']);
			$value2=strip_tags($_REQUEST['ally2']);			
			$sql="SELECT name,(SELECT tag FROM wg_allies WHERE tag='".$value1."') AS tag 
			FROM wg_allies WHERE name ='".$value2."'";
			$db->setQuery($sql);
			$check_wg_allies = NULL;
			$db->loadObject($check_wg_allies);			
			if($check_wg_allies->tag !='')
			{
				$check++;
				$error1 = $lang['error3'];		
			}
			if($check_wg_allies->name !='')
			{
				$check++;
				$error2 = $lang['error4'];
			}
		}
		if($check==0)
		{
			if($wordFilters[$value1] !=1 &&  $wordFilters[$value2] !=1)
			{
				$sql="INSERT INTO `wg_allies` (`name`, `user_id`,`tag`) 
				VALUES ('".substr($value2,0,25)."',".$userid.",'".substr($value1,0,25)."');";
				$db->setQuery($sql);
				if(!$db->query())
				{
					globalError2('function Creat_Allies:'.$sql);
				}
				
				$ally_id=$db->insertid();	
				$_SESSION['alliance_id']=$ally_id;			
				$sql="UPDATE wg_users SET alliance_id=".$ally_id." WHERE id=".$userid."";
				$db->setQuery($sql);
				$db->query();
				if($db->getAffectedRows()==0)
				{
					globalError2('function Creat_Allies:'.$sql);
				}
											
				$sql="INSERT INTO wg_ally_members (user_id, ally_id, position_name, right_, privilege) 
				VALUES ('".$userid."','".$ally_id."','".$lang['Master']."','1','11111111')";
				$db->setQuery($sql);
				if(!$db->query())
				{
					globalError2('function Creat_Allies:'.$sql);
				}					
				header("Location:build.php?id=".$_GET['id']."");
				exit();
			}
		}
	}
	else
	{
		$error=$value1="";
	}
	$parse['link']="build.php?id=".$_GET['id']."&create=allie";
	$parse['value1']=$value1;
	$parse['value2']=$value2;
	$parse['error1']=$error1;
	$parse['error2']=$error2;
	$curent_product=parsetemplate(gettemplate('create_allies'),$parse);
	return $curent_product;
}
/*
Ham cancel cong trinh trong muc update
*/
function updateCancelInMain($village)
{
	global $db;
	if(!empty($_GET['cancel']) && is_numeric($_GET['cancel']))
	{
		$sql="DELETE FROM wg_status WHERE id=".$_GET['cancel']." AND village_id=$village AND type=17 AND status=0";
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{
			globalError2('function updateCancelInMain:'.$sql);
		}
		header("Location:build.php?id=".$_GET['id']."");
		exit();
	}
	return NULL;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	ham dung de nang cap tai nguyen trong va ngoai theo level
* @param: $type= trang thai type trong status
$id=so thu tu trong bang wg_buidling
* @return: 
*/
/*----------------------------------------------------------------------------------------------------------------------*/
function Update_Resource_Buidling($type,$id,$userid,$stt,$village,$Main_time)
{
	global $db,$user,$lang,$game_config,$wg_buildings,$wg_village,$wg_building_types,$wg_status;
	$parse = $lang;
	$Hs_K=$game_config['k_game'];
	require_once('function_allian.php');
	UpdateRS($wg_village,$wg_buildings,time());
	updateCancelInMain($village);
	$Name=$lang[$wg_buildings[$stt]->name];
	$level_1=$wg_buildings[$stt]->level;
	$level=$wg_buildings[$stt]->level+1;
	$temp1=$wg_buildings[$stt]->product_hour;	
	$type_id=$wg_buildings[$stt]->type_id;
	$need_worker=getWorker($type_id,$level);
	/*---------------------------------------------------------------------------------------------------------------------*/
	foreach($wg_building_types as $sttBuild =>$objValue)
	{
		if($type_id == $objValue['id'])
		{
			$Lumber=RSn($wg_buildings[$stt]->type_id,$wg_buildings[$stt]->level+1,$objValue['rs1']);
			$parse['Lumber']=$Lumber;
			$Clay=RSn($wg_buildings[$stt]->type_id,$wg_buildings[$stt]->level+1,$objValue['rs2']);
			$parse['Clay']=$Clay;
			$Iron=RSn($wg_buildings[$stt]->type_id,$wg_buildings[$stt]->level+1,$objValue['rs3']);
			$parse['Iron']=$Iron;
			$Crop=RSn($wg_buildings[$stt]->type_id,$wg_buildings[$stt]->level+1,$objValue['rs4']);
			$parse['Crop']=$Crop;	
			$max_level=$objValue['max_level'];
			if($type_id<=4 && $user['villages_id']!=$village) // Lang phu max level cong trinh ngoai =10
			{
				$max_level=10;
			}
			elseif($type_id==10 || $type_id==11)
			{
				$array=GetLevel($wg_buildings);
				if($array[3]>0)
				{			
					$max_level=30;	
				}
			}
			$content=$lang[$objValue['des']];
			$name=$objValue['name'];
			break;
		}			
	}
	$Time_Cost=round(timeUpdateAll($type_id,$level,$Main_time)/$Hs_K);
	$Time=ReturnTime($Time_Cost);
	$parse['data_rare']='';
	$parse['menu_rare']='';
	/*----------------------------------------------------------------------------------------------------------------------*/
	if($type_id==12)//"Main_Building"
	{
		include_once('function_send_rare.php');		
		$temp2=MBn($level)*100;
		$parse['lang1']=$lang['104'];$parse['lang2']=$lang['106'];$parse['lang3']=$lang['108'];$parse['lang4']=$lang['106'];
		$parse['temp_level']=$level;$parse['temp1']=$temp1;$parse['temp2']=$temp2;
		if(!$_GET['tab']){
			$curent_product=parsetemplate(gettemplate('curent_product2'),$parse);
		}
		// xet truong hop main building >=10 thi duoc xoa nhung can nha trong lang`
		if($level_1>=10 && $_GET['tab']=='')
		{
			$curent_product.= Demolish_main_building($village,$Main_time,$Hs_K,$userid,$wg_buildings);
		}		
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==15)//"Cranny"
	{
		$temp2=SCn_eco_cra($level);
		$parse['lang1']=$lang['93'];$parse['lang2']=$lang['units'];$parse['lang3']=$lang['95'];$parse['lang4']=$lang['units'];
		$parse['temp_level']=$level;$parse['temp1']=$temp1;$parse['temp2']=$temp2;
		$curent_product=parsetemplate(gettemplate('curent_product2'),$parse);
	}
	/*-------------------- ----------------------- -------------------------------------------------------------------------*/
	elseif($type_id==14)//"Embassy"
	{
		if($level_1>0)
		{
			$curent_product=createAlly($userid,$level);
		}		
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==13)//"Marketplace"
	{
		$parse['GetMiddleContent']='';
		if($level_1>0)
		{
			$parse['GetMiddleContent']=GetMiddleContent($wg_buildings[$stt]);
		}
		$curent_product=parsetemplate(gettemplate('curent_product1'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==20)//"Trade_Office"
	{
		$curent_product="";
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==31)//"Academy"
	{
		$parse['GetMiddleContent']='';
		if($level_1>0)
		{
			$parse['GetMiddleContent']=GetMiddleContent($wg_buildings[$stt]);
		}		
		$curent_product=parsetemplate(gettemplate('curent_product1'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==28)//"Barracks"
	{
		$parse['GetMiddleContent']='';
		if($level_1>0)
		{
			$parse['GetMiddleContent']=GetMiddleContent($wg_buildings[$stt]);
		}
		$curent_product=parsetemplate(gettemplate('curent_product1'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==24)//"Blacksmith"
	{
		$parse['GetMiddleContent']='';
		if($level_1>0)
		{
			$parse['GetMiddleContent']=GetMiddleContent($wg_buildings[$stt]);
		}
		$curent_product=parsetemplate(gettemplate('curent_product1'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==36)//"City_Wall"
	{	
		$parse['num_percent']=SCn_City_Wall($level_1);
		$curent_product=parsetemplate(gettemplate('build_curent_citiwall'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==35)//"Hero_Mansion"
	{
		$parse['GetMiddleContent']='';
		if($level_1>0)
		{
			$parse['GetMiddleContent']=GetMiddleContent($wg_buildings[$stt]);
		}
		$curent_product=parsetemplate(gettemplate('curent_product1'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==27)//"Rally_Point"
	{
		$parse['GetMiddleContent']='';
		if($level_1>0)
		{
			$parse['GetMiddleContent']=GetMiddleContent($wg_buildings[$stt]);
		}		
		$curent_product=parsetemplate(gettemplate('curent_product1'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/	
	elseif($type_id==29)//"Stable"
	{
		$parse['GetMiddleContent']='';
		if($level_1>0)
		{
			$parse['GetMiddleContent']=GetMiddleContent($wg_buildings[$stt]);
		}
		$curent_product=parsetemplate(gettemplate('curent_product1'),$parse);		
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==30)//"Workshop"
	{		
		$parse['GetMiddleContent']='';
		if($level_1>0)
		{
			$parse['GetMiddleContent']=GetMiddleContent($wg_buildings[$stt]);
		}
		$curent_product=parsetemplate(gettemplate('curent_product1'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==6)//"Sawmill"
	{
		$parse['num_percent']=SCn_Brickyard($level-1);		
		$curent_product=parsetemplate(gettemplate('build_curent_product'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==7)//"Brickyard"
	{
		$parse['num_percent']=SCn_Brickyard($level-1);
		$curent_product=parsetemplate(gettemplate('build_curent_product'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==8)//"Iron_Foundry"
	{
		$parse['num_percent']=SCn_Brickyard($level-1);
		$curent_product=parsetemplate(gettemplate('build_curent_product'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==5||$type_id==9) //"Grain_Mill"||"Bakery"
	{
		$parse['num_percent']=SCn_Brickyard($level-1);
		$curent_product=parsetemplate(gettemplate('build_curent_product'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==18)//"Palace"
	{
		$parse['GetMiddleContent']='';
		if($level_1>0)
		{
			$parse['GetMiddleContent']=GetMiddleContent($wg_buildings[$stt]);
		}
		$curent_product=parsetemplate(gettemplate('curent_product1'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==1 || $type_id==2  || $type_id==3 || $type_id==4) // 4 tai nguyen ngoai thanh
	{
		$temp2=KTn_res($level)*$Hs_K;			
		$parse['lang1']=$lang['Current_production'];$parse['lang2']=$lang['per_hour'];$parse['lang3']=$lang['Production_at_level'];$parse['lang4']=$lang['per_hour'];
		$parse['temp1']=$temp1;$parse['temp2']=$temp2;$parse['temp_level']=$level;
		$curent_product=parsetemplate(gettemplate('curent_product2'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==10 || $type_id==11)//"Warehouse"  "Granary"
	{
		$temp2=SCn_eco_oth($level);		
		if($type_id==10)//"Warehouse"
		{
			$parse['lang1']=$lang['97'];$parse['lang2']=$lang['99'];$parse['lang3']=$lang['101'];$parse['lang4']=$lang['99'];
		}
		elseif($type_id==11)//"Granary"
		{
			$parse['lang1']=$lang['97'];$parse['lang2']=$lang['110'];$parse['lang3']=$lang['112'];$parse['lang4']=$lang['110'];
		}
		$parse['temp_level']=$level;$parse['temp1']=$temp1;$parse['temp2']=$temp2;
		$curent_product=parsetemplate(gettemplate('curent_product2'),$parse);
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	elseif($type_id==37)//"Ky dai
	{
		$curent_product="";
	}
	/*----------------------------------------------------------------------------------------------------------------------*/
	$Lumber1=$wg_village->rs1;
	$Clay1=$wg_village->rs2;
	$Iron1=$wg_village->rs3;
	$Crop1=$wg_village->rs4;	
	/*----------------------------------// KT level cua building = max ---------------------------------------------------*/
	if($level_1>=$max_level) 
	{
		$parse['Name']=$parse['Names']=$Name;
		$parse['level_1']=$level_1;	
		$parse['content']=$content;	
		if($type_id==1 || $type_id==2  || $type_id==3 || $type_id==4)//"Woodcutter" "Cropland" "Iron_Mine" "Clay_Pit"
		{
			$parse['value']=KTn_res($level_1)*$Hs_K;
			$content_update= parsetemplate(gettemplate('build_body_max'),$parse);			
		}
		elseif($type_id==10 || $type_id==11 || $type_id==15)//"Warehouse" "Granary" "Cannay "
		{			
			$parse['value']=SCn_eco_oth($level_1);
			if($type_id==15)
			{
				$parse['value']=SCn_eco_cra($level_1);
			}
			$content_update= parsetemplate(gettemplate('build_body_max'),$parse);			
		}
	 	elseif($type_id==12)//"Main_Building"
		{	
			if($_GET['tab']!='')
			{
				$content_update= parsetemplate(gettemplate('build_main_max1'),$parse);
			}
			else
			{		
				$parse['delete']=Demolish_main_building($village,$Main_time,$Hs_K,$userid,$wg_buildings);
				$parse['value']=MBn($level_1)*100;					
				$content_update= parsetemplate(gettemplate('build_main_max'),$parse);	
			}
		}
		elseif($type_id==14)//"Embassy"
		{
			$parse['content1']=createAlly($userid,$level);
			$content_update= parsetemplate(gettemplate('build_body_max3'),$parse);
		}
		elseif($type_id==31 || $type_id==13||$type_id==28 ||$type_id==29 ||$type_id==30 ||$type_id==18 || $type_id==27 ||$type_id==24|| $type_id==35)
		{
			$parse['curent_product']=$curent_product;
			$content_update= parsetemplate(gettemplate('build_body_max1'),$parse);	
		}
		elseif($type_id==5 || $type_id==6||$type_id==7 ||$type_id==9 || $type_id==8 || $type_id==36)
		{
			$parse['curent_product']=$curent_product;
			$content_update= parsetemplate(gettemplate('build_body_max1'),$parse);
		}
		else
		{
			$content_update= parsetemplate(gettemplate('build_body_max2'),$parse);
		}
	}
	/*------------------Level < Max thi update-----------------------------------------------------------------------*/
	else
	{
		// kiem tra cong nay dang trong qua trinh pha huy ?
		if(Check_Status_Destroy($_GET['id'],$wg_status)>0)
		{
			$dieu_kien='<br>'.Demolish_destroy($village,$Main_time,$Hs_K,$userid,$wg_status).'';
		}
		else
		{
			if(checkUpdate_Resource_Buidling($wg_status,$userid,$id,$type))
			{
				$dieu_kien=$lang['dieu_kien1'];	
			}
			else
			{
				// 2. so sanh tai nguyen can nang cap va tai nguyen hien co
					if($Lumber >$Lumber1 || $Clay >$Clay1 || $Iron >$Iron1 || $Crop >$Crop1)
					{
						$dieu_kien=$lang['dieu_kien2'];	
					}
					else
					{	
						$char='village1.php';					
						if($type_id >4 )
						{
							$char='village2.php';	
						}						
						$_SESSION['UpdateBuilding'.$type_id]=$id.','.$Time_Cost.','.$level.','.$type_id.','.$Lumber.','.$Clay.','.$Iron.','.$Crop.','.$name;												
						$dieu_kien='<a href="'.$char.'?a='.$type_id.'">'.$lang['Upgrade_to_level'].' '.$level.'</a>';
					}
			}
		}
		$parse['Name']=$Name;
		$parse['content']=$content;
		$parse['level_1']=$level_1;
		$parse['level_new']=$level;
		$parse['curent_product']=$curent_product;
		$parse['Time']=$Time;
		$parse['dieu_kien']=$dieu_kien;
		$parse['need_worker']=$need_worker;
		$content_update=parsetemplate(gettemplate('build_update'),$parse);	
	}
	return $content_update;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
/*----------------------------------------------------------------------------------------------------------------------*/
function Check_Exist($type_id,$wg_buildings)
{
	foreach($wg_buildings as $ptu)
	{
		if($ptu->type_id==$type_id)
		{
			return 1;
		}	
	}
	return 0;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
/*----------------------------------------------------------------------------------------------------------------------*/
// ham tinh Construction times cua Main Buidling
function SCn_Marketplace($level)
{
	if($level==0)
		return 0;
	if($level==1)
		return 1;
	return (1+SCn_Marketplace($level-1));
}

function InsertDataBuilding_New($village_id,$userid,$kind_id)
{
	global $db,$game_config,$user;
	$Hs_K=$game_config['k_game'];
	$ProductHour=KTn_res(0)*$Hs_K;
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_buildings WHERE vila_id=".$village_id;
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	if($count==0)
	{
		$sql="SELECT image FROM wg_village_kinds WHERE id=".$kind_id;
		$db->setQuery($sql);
		$image=$db->loadResult();
		if($image)
		{
			$sql="INSERT INTO `wg_buildings` (`index`, `name`, `img`, `level`, `vila_id`, `type_id`,`product_hour`,`cp`) VALUES";
			if($image =="f1" || $image =="f2" || $image =="f3" || $image =="f4" ||$image =="f5")
			{
				$sql.="(1, 'Clay_Pit', '', 0, $village_id, 4,  ".$ProductHour.",0),";			
				$sql.="(2, 'Clay_Pit', '', 0, $village_id, 4,  ".$ProductHour.",0),";
				if($image=="f4")
				{
					$sql.="(3, 'Clay_Pit', '', 0, $village_id, 4,  ".$ProductHour.",0),";
				}
				else
				{
					$sql.="(3, 'Iron_Mine', '', 0, $village_id, 3,  ".$ProductHour.",0),";
				}
				$sql.="(4, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				if($image=="f1")
				{
					$sql.="(5, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
					$sql.="(6, 'Iron_Mine', '', 0, $village_id, 3,  ".$ProductHour.",0),";
					$sql.="(7, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
					$sql.="(8, 'Woodcutter','', 0, $village_id, 1,  ".$ProductHour.",0),";
					$sql.="(9, 'Woodcutter','', 0, $village_id, 1,  ".$ProductHour.",0),";
					$sql.="(10, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
					$sql.="(11, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				}
				elseif($image=="f2" ||$image=="f3" || $image=="f4" ||$image=="f5")
				{
					$sql.="(5, 'Woodcutter', '',0, $village_id, 1,  ".$ProductHour.",0),";
					$sql.="(6, 'Iron_Mine', '', 0, $village_id, 3,  ".$ProductHour.",0),";	
					$sql.="(7, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
					if($image=="f2")
					{
						$sql.="(8, 'Iron_Mine', '',0, $village_id, 3,  ".$ProductHour.",0),";	
					}
					else
					{				
						$sql.="(8,'Woodcutter','',0,$village_id,1,".$ProductHour.",0),";					
					}		
					$sql.="(9, 'Woodcutter', '',0, $village_id, 1,  ".$ProductHour.",0),";
					$sql.="(10, 'Clay_Pit', '', 0, $village_id, 4,  ".$ProductHour.",0),";
					$sql.="(11, 'Iron_Mine', '',0, $village_id, 3,  ".$ProductHour.",0),";						
				}
				$sql.="(12, 'Iron_Mine', '', 0, $village_id, 3,  ".$ProductHour.",0),";
				$sql.="(13, 'Cropland', '',0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(14, 'Cropland', '',0, $village_id, 2,  ".$ProductHour.",0),";	
				if($image=="f5")
				{
					$sql.="(15, 'Woodcutter','',0, $village_id, 1,  ".$ProductHour.",0),";
				}
				else
				{		
					$sql.="(15, 'Clay_Pit', '',0, $village_id, 4,  ".$ProductHour.",0),";	
				}		
				$sql.="(16, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(17, 'Woodcutter','', 0, $village_id, 1,  ".$ProductHour.",0),";
				$sql.="(18, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
			}		
			if($image=="f6")
			{
				$sql.="(1, 'Clay_Pit', '',0, $village_id, 4,  ".$ProductHour.",0),";
				$sql.="(2, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(3, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(4, 'Cropland', '',0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(5, 'Woodcutter','',0, $village_id, 1,  ".$ProductHour.",0),";
				$sql.="(6, 'Cropland', '',0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(7, 'Cropland', '',0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(8, 'Cropland', '',0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(9, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(10, 'Cropland', '',0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(11, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(12, 'Iron_Mine', '',0, $village_id, 3,  ".$ProductHour.",0),";
				$sql.="(13, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(14, 'Cropland', '',0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(15, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(16, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(17, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
				$sql.="(18, 'Cropland', '', 0, $village_id, 2,  ".$ProductHour.",0),";
			}
			$sql.="(19, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(20, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(21, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(22, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(23, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(24, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(25, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(26, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(27, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(28, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(29, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(30, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(31, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(32, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(33, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(34, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(35,'Main_Building','images/un/g/g15.png',1,$village_id, 12,100,".CPn_1(1)."),";	
			$sql.="(36, '', '', 0, $village_id, 0, 0,0),";
			$sql.="(37, '', '', 0, $village_id, 0, 0,0),";	
			$sql.="(38, 'City_Wall', '', 0,$village_id, 36,0,0);";
			$db->setQuery($sql);
			if(!$db->query())
			{
				globalError2('function InsertDataBuilding_New:'.$sql);
			}
		}
		else
		{
			die('wg_village_kinds not exits');
		}
	}
	return false;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
// xay dung cac cong trinh moi
function Buil_Infrastructure_New($type,$userid,$building_types,$id,$village,$a,$b,$c,$d,$Main_time)
{
	global $db,$lang,$game_config,$wg_building_types,$wg_buildings,$wg_village,$wg_status;
	$Hs_K=$game_config['k_game'];	
	$parse = $lang;
	UpdateRS($wg_village,$wg_buildings,time());
	$parse['list building'].="";
	$count=0;		
	foreach($wg_buildings as $sttBuild =>$objValue1)
	{
		if($building_types == $objValue1->type_id)
		{ 
			$count=1;
			break;		
		}				
	}
	if($count==0 || $building_types==36)
	{
		foreach($wg_building_types as $sttBuild =>$objValue)
		{
			if($building_types == $objValue['id'])
			{
				$rs1=$objValue['rs1'];
				$rs2=$objValue['rs2'];
				$rs3=$objValue['rs3'];
				$rs4=$objValue['rs4'];
				$name=$objValue['name'];
				$des=$objValue['des'];
				break;		
			}				
		}
		$level=1;
		$need_worker=getWorker($building_types,1);
		$images='<img src="'.Get_Images2($building_types,1).'" border="0" />'; // khong duoc dich chuyen cai nay mang xuong duoi
		if($building_types==36)// Tuong thanh
		{
			$images="";
		}
		elseif($building_types==37) // Ky dai`
		{
			$images='<img src="images/un/g/kydai10.png" border="0">';
		}
		$cost_time1=round(timeUpdateAll($building_types,$level,$Main_time)/$Hs_K);
		$cost_time=ReturnTime($cost_time1);
		$parse['name']=$lang[$name];
		$parse['content']=$lang[$des];
		$parse['rs1']=RSn($building_types,1,$rs1); 
		$parse['rs2']=RSn($building_types,1,$rs2);
		$parse['rs3']=RSn($building_types,1,$rs3);
		$parse['rs4']=RSn($building_types,1,$rs4);
		$parse['need_worker']=$need_worker; 
		$parse['images']=$images;
		$parse['cost_time']=$cost_time;
		
		if(checkUpdate_Resource_Buidling($wg_status,$userid,$id,$type))
		{
			$parse['link']=$lang['dieu_kien1'];//'The workers are already at work';
		}
		else
		{
			// 2. so sanh tai nguyen can nang cap va tai nguyen hien co
				if($rs1>$a || $rs2>$b || $rs3>$c || $rs4>$d)
				{
					$parse['link']=$lang['dieu_kien2'];// $lang['dieu_kien2']='Too few resources';
				
				}
				else
				{
					$_SESSION['UpdateBuilding'.$building_types]=$id.','.$cost_time1.',0,'.$building_types.','.$rs1.','.$rs2.','.$rs3.','.$rs4.','.$name;		
					$parse['link']='<a href="village2.php?a='.$building_types.'">'.$lang['86'].'</a>';						
				}

		}
		$list=parsetemplate(gettemplate('build_new'),$parse);
	}
	return $list;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
function Buil_Infrastructure_More($type,$userid,$building_types,$id,$village,$a,$b,$c,$d,$Main_time)
{
	global $db,$lang,$game_config,$wg_village,$wg_buildings,$wg_building_types,$wg_status;
	$Hs_K=$game_config['k_game'];
	$parse = $lang;
	UpdateRS($wg_village,$wg_buildings,time());
	$parse['list building'].="";
	$level=1;
	$need_worker=getWorker($building_types,1);
	foreach($wg_building_types as $sttBuild =>$objValue)
	{
		if($building_types == $objValue['id'])
		{
			$parse['rs1']=RSn($type,1,$objValue['rs1']);
			$parse['rs2']=RSn($type,1,$objValue['rs2']);
			$parse['rs3']=RSn($type,1,$objValue['rs3']);
			$parse['rs4']=RSn($type,1,$objValue['rs4']);
			$rs1=$objValue['rs1'];
			$rs2=$objValue['rs2'];
			$rs3=$objValue['rs3'];
			$rs4=$objValue['rs4'];
			$name=$objValue['name'];
			$des=$objValue['des'];
			break;		
		}				
	}
	$cost_time1=round(timeUpdateAll($building_types,$level,$Main_time)/$Hs_K);
	$cost_time=ReturnTime($cost_time1);	
	$parse['name']=$lang[$name];
	$parse['content']=$lang[$des];
	$parse['worker']=$need_worker;
	$parse['time']=$cost_time;
	
	if(checkUpdate_Resource_Buidling($wg_status,$userid,$id,$type))
	{
		$parse['link']=$lang['dieu_kien1'];
	}
	else
	{
		// 2. so sanh tai nguyen can nang cap va tai nguyen hien co
			if($rs1>$a || $rs2>$b || $rs3>$c || $rs4>$d)
			{
				$parse['link']=$lang['dieu_kien2'];// $lang['dieu_kien2']='Too few resources';
			}
			else
			{
				$_SESSION['UpdateBuilding'.$building_types]=$id.','.$cost_time1.',0,'.$building_types.','.$rs1.','.$rs2.','.$rs3.','.$rs4.','.$name;
				$parse['link']='<a href="village2.php?a='.$building_types.'">'.$lang['86'].'</a>';											
			}
	}
	$parse['images']=Get_Images2($building_types,1);
	return $parse['list building']= parsetemplate(gettemplate('build_more'), $parse);
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	lay level cua cong trinh
* @param:	$vila_id,$type_id
* @return: $level
*/
function GetLevel($wg_buildings)
{
	$array=array();
	if($wg_buildings)
	{
		foreach($wg_buildings as $objValue)
		{
			if($objValue->type_id==10 && $objValue->level>$array[0])
			{
				$array[0]=$objValue->level;			
			}
			elseif($objValue->type_id==11 && $objValue->level>$array[1])
			{
				$array[1]=$objValue->level;			
			}
			elseif($objValue->type_id==15 && $objValue->level>$array[2])
			{
				$array[2]=$objValue->level;			
			}
			elseif($objValue->type_id==37)
			{
				$array[3]=$objValue->level;	
			}			
		}
		return $array;
	}
	return NULL;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	lay level cua cong trinh
* @param:	$vila_id,$type_id
* @return: $level
*/
function checkExitBuidling($wg_buildings,$type_id)
{
	if($wg_buildings)
	{
		foreach($wg_buildings as $v)
		{
			if($v->type_id==$type_id)
			{
				return true;		
			}
		}
	}
	return false;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
// xuat thong tin xay dung cac cong trinh co DK ( phu thuoc vao level va exist cua cong trinh khac)
function Show_Building_Available($village,$wg_buildings)
{
	global $db,$lang,$wg_building_types;
	$parse=$lang;
	$list='';
	foreach ($wg_building_types as $key=>$ptu)
	{
		if($ptu['id'] >4 && $ptu['id']!=12)
		{
			if(Check_Exist($ptu['id'],$wg_buildings)==0 && test_tech_tree($ptu['id'],$village,$wg_buildings)==0)
			{
				$parse['name']=$lang[$ptu['name']];
				$parse['content']=$lang[$ptu['des']];
				$parse['image']=Get_Images2($ptu['id'],$ptu['max_level']);
				$parse['dk']=test_tech_tree_name($ptu['id'],$village);
				$list.=parsetemplate(gettemplate('build_available'),$parse);
			}
		}
	}
	return $parse['soon list building']=$list;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
function test_tech_tree_name($id,$vila_id)
{
	global $db,$lang, $wg_tech_tree;
	$list='';
	$numTechRecord = count($wg_tech_tree);
	$indexTechCheck = -1;
	for($i=0; $i<$numTechRecord; $i++){
		$level = $wg_tech_tree[$i]["level"];		
		if(($wg_tech_tree[$i]["building_type_id"] == $id) && ($level == 0)){
			$indexTechCheck = $i;
			break;
		}		
	}
	if($indexTechCheck>=0){
		$array=string_to_array($wg_tech_tree[$indexTechCheck]["requirement"]);
		foreach($array as $techId)
		{
			$sql="SELECT name FROM wg_building_types WHERE id=".$wg_tech_tree[$techId-1]["building_type_id"]; 
			$db->setQuery($sql);
			$query=null;
			$db->loadObject($query);
			$list.=$lang[$query->name].'&nbsp;'.$lang['level'].'&nbsp;'.$wg_tech_tree[$techId-1]["level"].',&nbsp;';
		}
	}
	return $list;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
/*------------------------------------------------------------------------------------------------------------------------------*/
function test_tech_tree($id,$vila_id,$wg_buildings)
{
	global $db,$wg_tech_tree;
	// kiem tra da ton tai trong building 
	$count=0;
	foreach($wg_buildings as $keyBuild =>$vBuild){
		if(($vBuild->vila_id==$vila_id) && ($vBuild->type_id==$id)){
			$count=1;
			break;
		}
	}	
	if($count==1){
		return 0;
	}
	else{	
		$numTechRecord = count($wg_tech_tree);
		$indexTechCheck = -1;
		for($i=0; $i<$numTechRecord; $i++){
			$hasReq = strlen(trim($wg_tech_tree[$i]["requirement"]));		
			if(($wg_tech_tree[$i]["building_type_id"] == $id) && ($hasReq > 0)){
				$indexTechCheck = $i;
				break;
			}		
		}
		
		if($indexTechCheck < 0){
			return 1;
		}
		$array_re=string_to_array($wg_tech_tree[$indexTechCheck]["requirement"]);
		$boo=true;				
		foreach ($array_re as $re)
		{						
			$sql_level="SELECT * FROM wg_buildings WHERE type_id='".$wg_tech_tree[$re-1]["building_type_id"]."'";
			$sql_level.=" AND (vila_id='".$vila_id."')ORDER BY level desc"; 
			
			$db->setQuery($sql_level);
			$check=null;
			$db->loadObject($check);
			if ($check)
			{			
				if (isset($check) && ($check->level < $wg_tech_tree[$re-1]["level"]))
				{
					$boo=false;
					break;
				}
			}
			else
			{
				return 0;
			}
		}
		if ($boo==true)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}				
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
function Get_Images1($id,$level) // hinh anh cac cong trinh chua xay xong
{
	if($id==5) //grian mill
	{
		return $images='images/un/g/g8b.png';
	}
	elseif($id==6) //Sawmill
	{
		return $images='images/un/g/g5b.png';
	}
	elseif($id==7) //Brickyard
	{
		return $images='images/un/g/g6b.png';
	}
	elseif($id==8) //Iron_Foundry
	{
		return $images='images/un/g/g7b.png';
	}
	elseif($id==9) //Bakery
	{
		return $images='images/un/g/g9b.png';
	}
	elseif($id==10) //Warehouse
	{
		return $images='images/un/g/g10b.png';
	}
	elseif($id==11) //Granary
	{
		return $images='images/un/g/g11b.png';
	}
	elseif($id==12) //Main buidling
	{
		return $images='images/un/g/g15b.png';
	}
	elseif($id==13) //Marketplace
	{
		return $images='images/un/g/g17b.png';
	}
	elseif($id==14) //Embassy
	{
		return $images='images/un/g/g18b.png';
	}
	elseif($id==15) //Cranny
	{
		return $images='images/un/g/g23b.png';
	}
	elseif($id==18) //Palace
	{
		return $images='images/un/g/g26b.png';
	}
	elseif($id==19) //Treasury
	{
		return $images='images/un/g/g27b.gif';
	}
	elseif($id==20) //Trade_Office
	{
		return $images='images/un/g/g28b.png';
	}
	elseif($id==24) //Blacksmith
	{
		return $images='images/un/g/g12b.png';
	}
	elseif($id==27) //Rally_Poin
	{
		return $images='images/un/g/g16b.png';
	}
	elseif($id==28) //Barracks
	{
		return $images='images/un/g/g19b.png';
	}
	elseif($id==29) //Stable
	{
		return $images='images/un/g/g20b.png';
	}
	elseif($id==30) //Workshop
	{
		return $images='images/un/g/g21b.png';
	}
	elseif($id==31) //Academy
	{
		return $images='images/un/g/g22b.png';
	}
	elseif($id==35) //Hero_Mansion
	{
		return $images='images/un/g/g37b.png';
	}
	elseif($id==37) //World_Wonder  	
	{
		return $images='images/un/g/kydai1.png';
	}
	return NULL;
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
function Get_Images2($id,$level) // hinh anh cac cong trinh sau khi hoan thien
{
    if($id==5) //grian mill
	{
		return $images='images/un/g/g8.png';
	}
	elseif($id==6) //Sawmill
	{
		return $images='images/un/g/g5.png';
	}
	elseif($id==7) //Brickyard
	{
		return $images='images/un/g/g6.png';
	}
	elseif($id==8) //Iron_Foundry
	{
		return $images='images/un/g/g7.png';
	}
	elseif($id==9) //Bakery
	{
		return $images='images/un/g/g9.png';
	}
	elseif($id==10) //Warehouse
	{
		return $images='images/un/g/g10.png';
	}
	elseif($id==11) //Granary
	{
		return $images='images/un/g/g11.png';
	}
	elseif($id==12) //Main buidling
	{
		return $images='images/un/g/g15.png';
	}
	elseif($id==13) //Marketplace
	{
		return $images='images/un/g/g17.png';
	}
	elseif($id==14) //Embassy
	{
		return $images='images/un/g/g18.png';
	}
	elseif($id==15) //Cranny
	{
		return $images='images/un/g/g23.png';
	}
	elseif($id==18) //Palace
	{
		return $images='images/un/g/g26.png';
	}
	elseif($id==19) //Treasury
	{
		return $images='images/un/g/g27.gif';
	}
	elseif($id==20) //Trade_Office
	{
		return $images='images/un/g/g28.png';
	}
	elseif($id==24) //Blacksmith
	{
		return $images='images/un/g/g12.png';
	}
	elseif($id==27) //Rally_Poin
	{
		return $images='images/un/g/g16.png';
	}
	elseif($id==28) //Barracks
	{
		return $images='images/un/g/g19.png';
	}
	elseif($id==29) //Stable
	{
		return $images='images/un/g/g20.png';
	}
	elseif($id==30) //Workshop
	{
		return $images='images/un/g/g21.png';
	}
	elseif($id==31) //Academy
	{
		return $images='images/un/g/g22.png';
	}
	elseif($id==35) //Hero_Mansion
	{
		return $images='images/un/g/g37.png';
	}
	elseif($id==36) //city wall
	{
		return $images='';
	}
	elseif($id==37) //World_Wonder  	
	{
		return getImagesWonder($level);
	}
}
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
function GetMaxLevel($id)
{
	global $wg_building_types;
	foreach($wg_building_types as $sttBuild =>$objValue)
	{
		if($id == $objValue['id'])
		{
			return $max_level=$objValue['max_level'];
		}				
	}
}
function getImagesWonder($level)
{
	if($level<=10)
		return $images='images/un/g/kydai1.png';
	elseif($level<=20)
		return $images='images/un/g/kydai2.png'; 
	elseif($level<=30)
		return $images='images/un/g/kydai3.png'; 
	elseif($level<=40)
		return $images='images/un/g/kydai4.png'; 
	elseif($level<=50)
		return $images='images/un/g/kydai5.png'; 
	elseif($level<=60)
		return $images='images/un/g/kydai6.png'; 
	elseif($level<=70)
		return $images='images/un/g/kydai7.png'; 
	elseif($level<=80)
		return $images='images/un/g/kydai8.png'; 
	elseif($level<=90)
		return $images='images/un/g/kydai9.png'; 
	elseif($level<=100)
		return $images='images/un/g/kydai10.png'; 
}
function checkRareExits($id)
{
	global $db;
	$sql='SELECT * FROM wg_rare WHERE vila_id='.$id.'';
	$db->setQuery($sql);
	$wg_rare=null;
	$db->loadObject($wg_rare);
	if($wg_rare->kim >0 && $wg_rare->moc >0 && $wg_rare->thuy >0 && $wg_rare->hoa >0 && $wg_rare->tho >0)
	{
		return 1;
	}
	return 0;
}
function destroyAllBuildingOutSide($village)
{
	global $db,$user;
	if(isset($_SESSION['vt_new_of_main'])) // cap nhat cho MainBuilding o vi tri moi 
	{
		if(is_numeric($_POST['vt_new']) && $_POST['vt_new'] >=19 && $_POST['vt_new'] <=32)
		{
			$sql="SELECT name,img,level,product_hour,cp FROM wg_buildings WHERE vila_id=$village AND type_id=12 LIMIT 1";;
			$db->setQuery($sql);
			$query_main=NULL;
			$db->loadObject($query_main);
			
			$sql="UPDATE wg_buildings SET name='".$query_main->name."',img='".$query_main->img."',level=".$query_main->level.",type_id=12,product_hour=".$query_main->product_hour.",cp=".$query_main->cp."	WHERE vila_id=".$village." AND `index`=".$db->getEscaped($_POST['vt_new']);
			$db->setQuery($sql);
			$db->query();
			if($db->getAffectedRows()==0)
			{
				globalError2('function destroyAllBuildingOutSide:'.$sql);
			}
			unset($_SESSION['vt_new_of_main']);	
		}
		else
		{
			header("Location:build.php?id=".$_SESSION['vt_new_of_main']."&tab=1");
			exit();
		}
	}
	$sql="UPDATE wg_buildings SET name='',img='',level=0,type_id=0,product_hour=0,cp=0
	WHERE vila_id=".$village." AND `index` IN (33,34,35,36)";
	$db->setQuery($sql);
	$db->query();
	if($db->getAffectedRows()==0)
	{
		globalError2('function destroyAllBuildingOutSide:'.$sql);
	}
	
	$sql="DELETE FROM wg_status	WHERE object_id IN (33,34,35,36) AND village_id=".$village." AND status=0";
	$db->setQuery($sql);
	$db->query();
	if($db->getAffectedRows()==0)
	{
		globalError2('function destroyAllBuildingOutSide:'.$sql);
	}
	
	returnWorkersLogin($user['id']);
	return true;
}
/*
* kiem tra Dk xay dung ky dai`
*
*
*/
function checkShowWorldWonder($wg_buildings,$village)
{
	global $db,$user;
	$world_wonder=0;
	$array=array();
	if(empty($wg_buildings))
	{
		$wg_buildings=getBuildings($village);
	}
	foreach($wg_buildings as $key=>$value)
	{
		if($value->type_id==37)
		{
			$world_wonder=1;		
			break;
		}
	}
	/*
	Da du Dk xay dung ky dai moi'
	1. so thanh >=2
	2. du so bau vat
	4. vi tri index 33->36 rong
	*/
	if($world_wonder==0)
	{
		$check=0;
		$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE user_id=".$user['id']."";
		$db->setQuery($sql);
		$count = (int)$db->loadResult();
		foreach($wg_buildings as $key=>$value)
		{
			if($value->type_id==12 && $value->level >0)
			{
				$check++;	
			}
			if($value->index>=33 && $value->index<=36)
			{
				if($value->level==0)
				{
					$check++;
				}
			}
		}
		if($count>1 && $check==5 && checkRareExits($village)==1)
		{
			return 1;
		}	
		return 0;
	}
	return 1;
}
/*

*/
//0,10,0,0,10,10,15,15,0,0
function getOasisTroopNew($troop_list,$type,$time_now,$attrack_time)
{
	require_once('func_convert.php');
	global $wg_oasis_troop;		
	$array_troop_list=string_to_array($troop_list);
	$delta_time=$time_now-strtotime($attrack_time);
	foreach($wg_oasis_troop as $k=>$value)
	{
		if($type==$value['kind_id'])
		{
			if($troop_list!=NULL && $delta_time>0)
			{
				for($i=34,$j=0;$i<=43;$i++,$j++)
				{	
					$troop_new=$array_troop_list[$j]+round(($delta_time*$value['troop'.$i.''])/(TIME_OASISS*3600));
					$troop_list_new[$i]=min($value['troop'.$i.''],$troop_new);					
				}				
				return $troop_list_new;
			}
			else
			{
				for($i=34;$i<=43;$i++)
				{	
					$troop_list_new[$i]=$value['troop'.$i.''];					
				}				
				return $troop_list_new;
			}			
		}		
	}
}
/*
-> chi su dung khi co ky dai level >90 tro len	
*/
function checkWorldFinished()
{
	global $db;
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_buildings WHERE type_id=37 AND level =100";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	if($count>0)
	{
		$sql="UPDATE wg_users SET anounment='world_finished'";
		$db->setQuery($sql);
		$db->query();
		if($db->getAffectedRows()==0)
		{	
			globalError2('function checkWorldFinished:'.$sql);
		}	
	}
	return true;
}
/*
kiem tra user co su dung goi PLus cho xay dung con trinh hay khong
*/
function checkPlusForUser($user_id)
{
	global $db;
	$check=0;
	$sql = "SELECT build FROM wg_plus WHERE user_id=".$user_id;
	$db->setQuery($sql);
	$check=strtotime($db->loadResult()) - strtotime(date("Y-m-d H:i:s",time()));
	if($check>0)
	{
		return true;
	}
	return false;	
}
function checkUpdate_Resource_Buidling($wg_status,$userid,$id,$type)
{
	if($wg_status)
	{
		if(checkPlusForUser($userid))
		{
			$sum_update=0;			
			foreach($wg_status as $sttBuild =>$objValue)
			{
				if($objValue->type <=3)
				{
					if($objValue->object_id==$id)
					{
						$index=$objValue->object_id;
						break;
					}
					$sum_update++;
				}
			}				
			if($sum_update >= MAX_BUILDING || $index==$id)
			{
				return true;
			}	
		}
		else
		{
			foreach($wg_status as $sttBuild =>$objValue)
			{
				if(($type <=2 && $objValue->type <=2) || ($type==3 && $objValue->type==3))
				{
					return true;					
				}
			}
		}		
	}
	return false;
}
/*
kiem tra user co su dung goi Plus cho viec xay nhieu cong trinh hay khong
*/
function checkUpdateVillage($wg_status,$object_id,$user_id,$char)
{
	if($wg_status)
	{
		if(checkPlusForUser($user_id)) // co dung PLus cho Build
		{
			$check=0;
			foreach($wg_status as $k =>$v)
			{
				if($v->type <=3)
				{
					$index=$v->object_id;
					$check++;
				}
			}
			if($check < MAX_BUILDING && $object_id != $index)
			{
				$_SESSION['checkPlusForUser']=1;
				$value=0;		
			}
			else
			{
				$value=1;		
			}
		}
		else
		{			
			foreach($wg_status as $k =>$v)
			{
				if($char=='village1')
				{
					if($v->type==3)
					{					
						$value=1;						
					}
				}
				else
				{
					if($v->type <=2)
					{					
						$value=1;			
					}
				}
			}			
		}	
	}
	if($value==1)
	{
		for($i=1;$i<38;$i++)
		{
			unset($_SESSION['UpdateBuilding'.$i]);
		}
		return 1;
	}		
	return 0;
}
function returnWorkersLogin($userid)
{
	global $db;
	$sql="SELECT id FROM wg_villages WHERE  user_id=".$userid;
	$db->setQuery($sql);
	$wg_villages=NULL;
	$wg_villages=$db->loadObjectList();
	if($wg_villages)
	{
		$sum=0;
		$time_check=strtotime(date("Y-m-d H:i:s",time()));
		foreach ($wg_villages as $key=>$result)
		{
			$sum+=returnWorkersVillage($result->id,$userid,$time_check);							
		}
		$sql="UPDATE wg_users SET population=$sum WHERE id=".$userid;
		$db->setQuery($sql);
		$db->query();
	}
	return true;
}
function returnWorkersVillage($villages_id,$userid,$time_check)
{
	global $db,$game_config;
	$sql="SELECT id,type_id,level FROM wg_buildings WHERE vila_id=".$villages_id." ORDER BY `index` ASC";
	$db->setQuery($sql);
	$wg_buildings=NULL;
	$wg_buildings=$db->loadObjectList();
	$sum=0;
	if($wg_buildings)
	{
		foreach($wg_buildings as $value)
		{
			$cp=0;
			$product_hour=getProductNew($value->type_id,$value->level);	
			if($value->level >0)
			{			
				for($i=1;$i <= $value->level; $i++)
				{
					$sum+=getWorker($value->type_id,$i);				
				}	
				$cp=getCpAll($value->type_id,$value->level);		
											
			}						
			$sql="UPDATE wg_buildings SET product_hour=".$product_hour.",cp=".$cp." WHERE id=".$value->id;
			$db->setQuery($sql);
			$db->query();			
		}
	}
	$array=returnKrsForVillage($userid,$villages_id,$time_check);
	$string=$array['char'];	
	$sql="UPDATE wg_villages SET workers=".$sum." ".$string." WHERE id=".$villages_id;
	$db->setQuery($sql);
	$db->query();
	return $sum;
}
/*
cap nhat lai HsK
*/
function returnWgPlus($userId,$time_check)
{
	global $db;
	$sql="SELECT lumber,clay,iron,crop FROM wg_plus WHERE user_id=".$userId;
	$db->setQuery($sql);
	$db->loadObject($wg_plus);
	$array=array();
	$array['lumber']=1;
	$array['clay']=1;
	$array['iron']=1;
	$array['crop']=1;
	if($wg_plus)
	{
		if(strtotime($wg_plus->lumber) > $time_check )	
		{
			$array['lumber']=1.25;
		}
		if(strtotime($wg_plus->clay) > $time_check )	
		{
			$array['clay']=1.25;
		}
		if(strtotime($wg_plus->iron) > $time_check )	
		{
			$array['iron']=1.25;
		}
		if(strtotime($wg_plus->crop) > $time_check )	
		{
			$array['crop']=1.25;
		}		
	}
	return $array;
}
function returnWgOasis($userId,$villages_id)
{
	global $db;
	$sql="SELECT kind_id,child_id FROM wg_villages WHERE kind_id >6 AND user_id=".$userId;
	$db->setQuery($sql);
	$wg_oasis=$db->loadObjectList();
	$array=array();
	$array['lumber']=0;
	$array['clay']=0;
	$array['iron']=0;
	$array['crop']=0;	
	if($wg_oasis)
	{	
		$percent_oasis=array(7 => Array(
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
							));
		foreach ($wg_oasis as $result)
		{		
			if($villages_id == $result->child_id)
			{
				$array['lumber']+=$percent_oasis[$result->kind_id]['lumber'];
				$array['clay']+=$percent_oasis[$result->kind_id]['clay'];
				$array['iron']+=$percent_oasis[$result->kind_id]['iron'];
				$array['crop']+=$percent_oasis[$result->kind_id]['crop'];
			}	
		}		
	}
	return $array;
}
function returnWgBuilding($villages_id)
{
	global $db;
	$sql="SELECT level,type_id FROM wg_buildings WHERE vila_id=".$villages_id." AND type_id IN(5,6,7,8,9)";
	$db->setQuery($sql);
	$wg_buildings=$db->loadObjectList();
	$array=array();
	$array['lumber']=1;
	$array['clay']=1;
	$array['iron']=1;
	$array['crop']=1;
	if($wg_buildings)
	{		
		$hsk=array(0=>1,1=>1.05,2=>1.1,3=>1.15,4=>1.2,5=>1.25,6=>1.3125,7=>1.375,8=>1.4375,9=>1.5,10=>1.5625);
		$name=array(6=>'lumber',7=>'clay',8=>'iron',9=>'crop',5=>'crop');
		$level=0;
		foreach ($wg_buildings as $result)
		{			
			if($result->type_id ==5 || $result->type_id ==9)
			{
				$level+=$result->level;	
			}
			else
			{
				$array[''.$name[$result->type_id].'']=$hsk[$result->level];
			}
		}		
		$array['crop']=$hsk[$level];
		$array['lumber']=$array['lumber'];
		$array['clay']=$array['clay'];
		$array['iron']=$array['iron'];		
	}
	return $array;
}

function returnKrsForVillage($userId,$villages_id,$time_check)
{
	global $db;
	$plus=returnWgPlus($userId,$time_check);
	$string=array();
	if($plus)
	{
		$building=returnWgBuilding($villages_id);
		$oasis=returnWgOasis($userId,$villages_id);
		$string['krs1']=$plus['lumber']*($building['lumber']+$oasis['lumber']);
		$string['krs2']=$plus['clay']*($building['clay']+$oasis['clay']);
		$string['krs3']=$plus['iron']*($building['iron']+$oasis['iron']);
		$string['krs4']=$plus['crop']*($building['crop']+$oasis['crop']);
		$string['char']=",krs1=ROUND(".$string['krs1'].",6),
		krs2=ROUND(".$string['krs2'].",6),
		krs3=ROUND(".$string['krs3'].",6),
		krs4=ROUND(".$string['krs4'].",6)";
	}
	return $string;
}
?>
