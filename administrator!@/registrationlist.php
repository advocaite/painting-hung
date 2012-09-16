<?php 
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
$village_reg = array();
$village_zone = array();

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel'] <5)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('admin_registrationlist');
	global $lang;	
	$parse = $lang;
	if($_POST){
		set_time_limit ( 10000 );
		$error=0;
		if(!isset($_POST['x1']) || !is_numeric($_POST['x1']) || $_POST['x1']>400 || $_POST['x1']<-400){
			$error=1;
		}
		if(!isset($_POST['x2']) || !is_numeric($_POST['x2']) || $_POST['x2']>400 || $_POST['x2']<-400){
			$error=1;
		}
		if(!isset($_POST['x3']) || !is_numeric($_POST['x3']) || $_POST['x3']>400 || $_POST['x3']<-400){
			$error=1;
		}
		if(!isset($_POST['x4']) || !is_numeric($_POST['x4']) || $_POST['x4']>400 || $_POST['x4']<-400){
			$error=1;
		}
		if(!isset($_POST['x5']) || !is_numeric($_POST['x5']) || $_POST['x5']>400 || $_POST['x5']<-400){
			$error=1;
		}
	
		if(!isset($_POST['y1']) || !is_numeric($_POST['y1']) || $_POST['y1']>400 || $_POST['y1']<-400){
			$error=1;
		}
		if(!isset($_POST['y2']) || !is_numeric($_POST['y2']) || $_POST['y2']>400 || $_POST['y2']<-400){
			$error=1;
		}
		if(!isset($_POST['y3']) || !is_numeric($_POST['y3']) || $_POST['y3']>400 || $_POST['y3']<-400){
			$error=1;
		}
		if(!isset($_POST['y4']) || !is_numeric($_POST['y4']) || $_POST['y4']>400 || $_POST['y4']<-400){
			$error=1;
		}
		if(!isset($_POST['y5']) || !is_numeric($_POST['y5']) || $_POST['y5']>400 || $_POST['y5']<-400){
			$error=1;
		}
	
		if(!isset($_POST['r1']) || !is_numeric($_POST['r1']) || $_POST['r1']>400 || $_POST['r1']<1){
			$error=1;
		}
		if(!isset($_POST['r2']) || !is_numeric($_POST['r2']) || $_POST['r2']>400 || $_POST['r2']<1){
			$error=1;
		}
		if(!isset($_POST['r3']) || !is_numeric($_POST['r3']) || $_POST['r3']>400 || $_POST['r3']<1){
			$error=1;
		}
		if(!isset($_POST['r4']) || !is_numeric($_POST['r4']) || $_POST['r4']>400 || $_POST['r4']<1){
			$error=1;
		}
		if(!isset($_POST['r5']) || !is_numeric($_POST['r5']) || $_POST['r5']>400 || $_POST['r5']<1){
			$error=1;
		}
		
		if($error!=1){
			EmptyTable();
			//zone1:
			$sum1=CreateVillageZoneList($_POST['x1'], $_POST['y1'], $_POST['r1'], 1);
			//zone2:
			$sum2=CreateVillageZoneList($_POST['x2'], $_POST['y2'], $_POST['r2'], 2);
			//zone3:
			$sum3=CreateVillageZoneList($_POST['x3'], $_POST['y3'], $_POST['r3'], 3);
			//zone4:
			$sum4=CreateVillageZoneList($_POST['x4'], $_POST['y4'], $_POST['r4'], 4);
			//zone5:
			$sum5=CreateVillageZoneList($_POST['x5'], $_POST['y5'], $_POST['r5'], 5);
			
			$sum=$sum1+$sum2+$sum3+$sum4+$sum5;
			
			$parse['error_message']="<br><strong><font color=\"#009933\">".$lang['Successful']."!</font></strong><br>";
			$parse['error_message'].="<br><font color=\"#009933\">".$lang['Zone']." 1: $sum1 villages.</font><br>";
			$parse['error_message'].="<br><font color=\"#009933\">".$lang['Zone']." 2: $sum2 villages.</font><br>";
			$parse['error_message'].="<br><font color=\"#009933\">".$lang['Zone']." 3: $sum3 villages.</font><br>";
			$parse['error_message'].="<br><font color=\"#009933\">".$lang['Zone']." 4: $sum4 villages.</font><br>";
			$parse['error_message'].="<br><font color=\"#009933\">".$lang['Zone']." 5: $sum5 villages.</font><br>";
			$parse['error_message'].="<br><font color=\"#009933\">".$lang['Total']." : $sum villages.</font><br><br>";
					
			$parse['x1']=$_POST['x1'];
			$parse['x2']=$_POST['x2'];
			$parse['x3']=$_POST['x3'];
			$parse['x4']=$_POST['x4'];
			$parse['x5']=$_POST['x5'];
			
			$parse['y1']=$_POST['y1'];
			$parse['y2']=$_POST['y2'];
			$parse['y3']=$_POST['y3'];
			$parse['y4']=$_POST['y4'];
			$parse['y5']=$_POST['y5'];
			
			$parse['r1']=$_POST['r1'];
			$parse['r2']=$_POST['r2'];
			$parse['r3']=$_POST['r3'];
			$parse['r4']=$_POST['r4'];
			$parse['r5']=$_POST['r5'];
		}
	}
	
	if(isset($error) && $error==1){
		$parse['error_message']="<br><strong><font color=\"#FF0000\">incorect input!</font></strong>";
		$parse['x1']=$_POST['x1'];
		$parse['x2']=$_POST['x2'];
		$parse['x3']=$_POST['x3'];
		$parse['x4']=$_POST['x4'];
		$parse['x5']=$_POST['x5'];
		
		$parse['y1']=$_POST['y1'];
		$parse['y2']=$_POST['y2'];
		$parse['y3']=$_POST['y3'];
		$parse['y4']=$_POST['y4'];
		$parse['y5']=$_POST['y5'];
		
		$parse['r1']=$_POST['r1'];
		$parse['r2']=$_POST['r2'];
		$parse['r3']=$_POST['r3'];
		$parse['r4']=$_POST['r4'];
		$parse['r5']=$_POST['r5'];	
	}
	if(!isset($error)){
		$parse['error_message']="";
		$parse['x1']='';
		$parse['x2']='';
		$parse['x3']='';
		$parse['x4']='';
		$parse['x5']='';
		
		$parse['y1']='';
		$parse['y2']='';
		$parse['y3']='';
		$parse['y4']='';
		$parse['y5']='';
		
		$parse['r1']='';
		$parse['r2']='';
		$parse['r3']='';
		$parse['r4']='';
		$parse['r5']='';
	}
	
	$page = parsetemplate(gettemplate('admin/registrationlist_body'), $parse);
	displayAdmin($page,$lang['Registration List']);
}

function EmptyTable(){
	global $db;
	$sql="TRUNCATE TABLE `wg_registration_village_list`";
	$db->setQuery($sql);
	if(!$db->query()){
		die("error1!!!");
	}
	return true;
}

//Tao danh sach lang cho mot zone.
function CreateVillageZoneList($xc, $yc, $R, $zone_id){
	global $db, $village_zone;

	//lay tat ca cac lang nam trong pham vi cua zone.
	$sql="SELECT id, x, y FROM wg_villages_map WHERE POW(x-$xc, 2)+POW(y-$yc, 2) <= POW($R, 2) AND kind_id=3 AND user_id=0";
	$db->setQuery($sql);
	$villageList=$db->loadObjectList();
	if($villageList){
		$sumVillage=0;//count($villageList);
		//tao mang moi de thao tac (mang nay gom nhung lang co the cap phat.
		// bo 2 lay 1
		$i = 0;
		foreach($villageList as $village){
			if(($i % 3)==0){ // bo 2 lay 1
				$village_zone[$village->x][$village->y]=$village->id;
			}
			$i++;
		}
		
		//Bat dau tu tam cua zone.
		$village_id=CheckVillageAvailable($xc, $yc);
		if($village_id){
			InsertRegVillageList($village_id, $zone_id);
			$sumVillage++;
		}
		
		//mo rong dan ra theo duong tron.		
		for($r=1; $r<=$R; $r++){
			//Ung voi moi vong tron.
			for($x=0; $x<=$r; $x++){
				for($y=0; $y<=$r; $y++){
					if(pow($x, 2)+pow($y, 2)>pow($r-1, 2) && pow($x, 2)+pow($y, 2)<=pow($r, 2)){
						IsertVillageZone($x, $y, $xc, $yc, $zone_id, &$sumVillage);
					}
				}
			}
		}
		return $sumVillage;
	}
	return false;
}

//Kiem tra va chen vao bang wg_registration_village_id ung voi 8 toa do.
function IsertVillageZone($x, $y, $xc, $yc, $zone_id, $sumVillage){
	$village_id=CheckVillageAvailable($x+$xc, $y+$yc);
	if($village_id){
		InsertRegVillageList($village_id, $zone_id);
		$sumVillage++;
	}

	$village_id=CheckVillageAvailable($x+$xc, -$y+$yc);
	if($village_id){
		InsertRegVillageList($village_id, $zone_id);
		$sumVillage++;
	}

	$village_id=CheckVillageAvailable(-$x+$xc, -$y+$yc);
	if($village_id){
		InsertRegVillageList($village_id, $zone_id);
		$sumVillage++;
	}
	
	$village_id=CheckVillageAvailable(-$x+$xc, $y+$yc);
	if($village_id){
		InsertRegVillageList($village_id, $zone_id);
		$sumVillage++;
	}
}

//Kiem tra mot lang o toa do x, y co the cap phat cho user moi duoc khong?
function CheckVillageAvailable($x, $y){
	global $db, $village_zone, $village_reg;

	//kiem tra lang co kind_id=3.
	if(isset($village_zone[$x][$y])){
		$village_id=$village_zone[$x][$y];
		
		//Kiem tra lang co trong danh sach cap phat chua.
		if(!isset($village_reg[$village_id])){
			$village_reg[$village_id] = 1;
			return $village_id;
		}
	}
	return false;
}

function InsertRegVillageList($village_id, $zone_id){
	global $db;
	$sql="INSERT INTO wg_registration_village_list (village_id, zone_id, registed) VALUES ($village_id, $zone_id, 0)";
	$db->setQuery($sql);
	if(!$db->query()){
		die("error2!!!");
	}
	return true;
}
?>