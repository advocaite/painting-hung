<?php
/*
	Plugin Name: function_allian.php
	Plugin URI: http://asuwa.net/includes/function_allian.php
	Description: 
	+ Cac ham dung cho lien minh
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/

ob_start(); 
if ( !defined('INSIDE') )
{
	die("Hacking attempt");
}
/*
* @Author: hien
* @Des: liet ke cac lang va dong minh cua user
* @param: + $userid: id cua user
		  + $village: id cua village	
* @return: danh sach cac lang va danh sach dong minh
*/
function listVillageAndAllyMember($userid,$village)
{
	global $db,$lang,$wg_village,$user;
	includeLang('village_allie');
	$parse=$lang;
	$page="";

	$sql="SELECT kind_id,id,x,y,name FROM wg_villages WHERE user_id=".$userid." AND kind_id < 7 ORDER BY id ASC";
	$db->setQuery($sql);
	$elements = $db->loadObjectList();
	if($elements)
	{
		$list='';
		foreach ($elements as $ptu)
		{
			if($ptu->kind_id<=6)
			{
				$name_village=$ptu->name;
				if($ptu->name=="NewName")
				{
					$name_village=$lang['NewName'];
				}						
				if(isset($_SESSION['url']))							
				{
					$char='?vid='.$ptu->id;
					if($_SESSION['url']=='build.php')
					{
						$char='build.php?vid='.$ptu->id.'&id='.$_GET['id'];
					}
					$parse['link']='<span >&#8226;</span>&nbsp;<a href="'.$char.'">'.$name_village.'</a>';
					if($ptu->id==$village)
					{						
						$parse['link']='<span class="c2">&#8226;</span>&nbsp;<a href="'.$char.'" class="active_vl">'.$name_village.'</a>';
					}
				}
				else
				{
					$parse['link']='<span >&#8226;</span>&nbsp;<a href="village1.php?vid='.$ptu->id.'">'.$name_village.'</a>';
					if($ptu->id==$village)
					{
						$parse['link']='<span class="c2">&#8226;</span>&nbsp;<a href="village1.php?vid='.$ptu->id.'" class="active_vl">'.$name_village.'</a>';
					}
				}
				$parse['x']=$ptu->x;
				$parse['y']=$ptu->y;
			}
			$row.=parsetemplate(gettemplate('list_village_row'), $parse);
		}
		unset($_SESSION['url']);
		$parse['row']=$row;
		$list=parsetemplate(gettemplate('list_village'), $parse);
	}	
	$parse['List my village']=$list;
	if($user['alliance_id']==0)
	{// user do khong co dong minh */
		$parse['List my alliance']=parsetemplate(gettemplate('list_allie_none'), $parse);
	}else{
		$allyId = $user['alliance_id'];			
		// truy van qua bang wg_ally_members lay cac userid member
		$sql="SELECT tb1.user_id,tb2.username FROM wg_ally_members as tb1,wg_users as tb2 WHERE tb1.ally_id = ".$allyId." AND tb1.right_ = 1  AND tb2.id=tb1.user_id ORDER BY tb1.user_id";
		$db->setQuery($sql);
		$elements = $db->loadObjectList();
		$string='<table class="f10">';			
		if($elements)
		{
			foreach ($elements as $ptu)
			{
				$parse['username']=$ptu->username;
				$parse['user_id']=$ptu->user_id;
				$string.=parsetemplate(gettemplate('list_allie_row'), $parse);
			}
		}
		$string.='</table>';
		$parse['List my alliance']=$string;
	}	
	$parse['images_nation']=imagesNation($village);
	$parse['chatbox']='';
	if($user['alliance_id'] >0)
	{
		$parse['chatbox']=parsetemplate(gettemplate('chatbox'), $parse);
	}
	$page = parsetemplate(gettemplate('village_allie'), $parse);
	return $page;
}

/*
* @Author: tdnquang
* @Des: lay username by user_id cua user
* @param: $userId: id cua user
* @return: username cua user
*/
function getUserNameByUserId($userId)
{
	global $db;
	$sql="SELECT username FROM wg_users WHERE id=$userId LIMIT 1";
	$db->setQuery($sql);
	$username=$db->loadResult();
	return $username;
}

/*
* @Author: tdnquang
* @Des: lay user_id by username cua user
* @param: $userName: username cua user
* @return: user_id cua user
*/
function getUserIdByUserName($userName)
{
	global $db;
	$sql="SELECT id FROM wg_users WHERE username='$userName' LIMIT 1";
	$db->setQuery($sql);
	$id=$db->loadResult();
	return $id;
}
/*
* @Author: tdnquang
* @Des: lay name of ally by id
* @param: $allyId: id cua ally
* @return: name of ally
*/
function getAllyNameByAllyId($allyId)
{
	global $db;
	$sql="SELECT name FROM wg_allies WHERE id = ".$allyId." LIMIT 1";
	$db->setQuery($sql);
	$query=null;
	$db->loadObject($query);
	return $query->name;
}

/*
* @Author: tdnquang
* @Des: lay right (quyen han cua user trong ally)
* @param: $userId: id cua user
* @return: string of right
*/
function getUserRight($userId)
{
	global $db;
	//Get right value of user
	$sql = "SELECT privilege FROM wg_ally_members WHERE user_id=".$userId." AND right_ =1";
	$db->setQuery($sql);
	$privilege = null;
	$db->loadObject($privilege);	
	return $privilege;
}

/*
* @Author: tdnquang
* @Des: kiem tra user dang online hay offline
* @param: $userName: username cua user
* @return: true: online; false: offline
*/
function getUserOnLineOffLine($userName)
{
	global $db;
	$sql = "SELECT username FROM wg_sessions WHERE username='".$userName."'";
	$db->setQuery($sql);
	$result=$db->loadResult();	
	if(!empty($result))
	{
		return true; // online
	}
	else
	{
		return false; // offline
	}
}

/*
* @Author: tdnquang
* @Des: + thong bao so thanh vien trong lien minh da du		
* @param: + $allyName: ten cua lien minh		  	
* @return:
*/
function showMsgJoinAlly($allyName)
{
	global $lang;
	includeLang('allianz');
	$parse=$lang;	
	$parse['message']= "".$lang['Number member in ally is full']." ".$allyName." ".$lang['Enough']."";
	$parse=parsetemplate(gettemplate('join_allies_msg'),$parse);	
	return $parse;
}

/*
* @Author: tdnquang
* @Des: + kiem tra user do co loi moi tham gia lien minh hay ko
		+ tao lien minh moi
* @param: + $userid: id cua user
		  + $level: level cua toa dai su	
* @return: loi moi tham gia lien minh, tao lien minh moi
*/
function createAlly($userid,$level)
{
	global $db,$lang;
	$parse=$lang;
	$curent_product='';	
	if($level-1<3) // toa dai su co level < 3
	{
		// chua co loi moi gia nhap allie + chua tao allie cho rieng minh
		if(Check_Allies_Invite($userid,0)==0 && Check_Allies_Invite($userid,1)==0 && Check_Allies_Exist($userid)==0)
		{
			$curent_product = parsetemplate(gettemplate('no_invite'),$parse);
		}
		// neu user co loi moi tu cac allies khac (right_=0)
		elseif(Check_Allies_Invite($userid,1)==0 &&Check_Allies_Invite($userid,0)>0)
		{
			$curent_product= List_Invite($userid);
			if(isset($_GET['del_invite']) && is_numeric($_GET['del_invite']))
			{
				$sql="DELETE FROM wg_ally_members WHERE id=".$_GET['del_invite']." AND right_=0";				
				$db->setQuery($sql);
				$db->query();
				header("Location:build.php?id=".$_GET['id']."");
				exit();
			}
			if(is_numeric($_GET['aid']) && is_numeric($_GET['accept']))
			{
				if(countMemberAlly($_GET['aid']) < maxMemberAllyHave($_GET['aid']))
				{
				
					$sql="UPDATE wg_ally_members SET right_=1 WHERE id =".$_GET['accept']." AND right_=0";
					$db->setQuery($sql);
					$db->query();
					
					$sql="UPDATE wg_users SET alliance_id=(SELECT ally_id FROM wg_ally_members WHERE user_id =".$userid." AND right_=1) WHERE id=".$userid."";
					$db->setQuery($sql);
					$db->query();	
					
					$_SESSION['alliance_id']=$allyId;							
					header("Location:build.php?id=".$_GET['id']."");
					exit();	
				}
				else //thong bao thanh vien lien minh da du
				{
					$curent_product .= showMsgJoinAlly(getAllyNameByAllyId($allyId));																							
				}
			}											
		}
		//da gia nhap lam member cua lien minh khac
		elseif(Check_Allies_Invite($userid,1)>0)
		{
			$sql="SELECT name,tag FROM wg_allies 
			WHERE id=(SELECT ally_id FROM wg_ally_members WHERE user_id=$userid AND right_=1)";
			$db->setQuery($sql);
			$wg_allies=null;
			$db->loadObject($wg_allies);
			$parse['link_tag']=$wg_allies->tag;
			$parse['link_name']=$wg_allies->name;
			$curent_product=parsetemplate(gettemplate('join_allies'),$parse);
		}			
	}
	else
	{
		// chua co loi moi gia nhap allie + chua tao allie cho rieng minh
		if(Check_Allies_Invite($userid,0)==0 && Check_Allies_Exist($userid)==0 && Check_Allies_Invite($userid,1)==0){
			// tao lien minh
			$curent_product = Creat_Allies($userid);
			$curent_product.=parsetemplate(gettemplate('no_invite'),$parse);
		}		
		// neu user co loi moi tu cac allies khac (right_=0)
		elseif(Check_Allies_Invite($userid,1)==0 &&Check_Allies_Invite($userid,0)>0)
		{
			$curent_product = Creat_Allies($userid);
			$curent_product .= List_Invite($userid);
			if(isset($_GET['del_invite']) && is_numeric($_GET['del_invite']))
			{
				$sql="DELETE FROM wg_ally_members WHERE id=".$_GET['del_invite']." AND right_=0";				
				$db->setQuery($sql);
				$db->query();
				header("Location:build.php?id=".$_GET['id']."");
				exit();
			}
			if(is_numeric($_GET['aid']) && is_numeric($_GET['accept']))
			{
				if(countMemberAlly($_GET['aid']) < maxMemberAllyHave($_GET['aid']))
				{				
					$sql="UPDATE wg_ally_members SET right_=1 WHERE id =".$_GET['accept']." AND right_=0";
					$db->setQuery($sql);
					$db->query();
					
					$sql="UPDATE wg_users SET alliance_id=(SELECT ally_id FROM wg_ally_members WHERE user_id =".$userid." AND right_=1) WHERE id=".$userid."";
					$db->setQuery($sql);
					$db->query();
					
					$_SESSION['alliance_id']=$_GET['aid'];
					header("Location:build.php?id=".$_GET['id']."");
					exit();	
				}else{//thong bao thanh vien trong lien minh da du		
					// khung tao lien minh
					$curent_product = Creat_Allies($userid);
					// danh sach loi moi tham gia lien minh
					$curent_product .= List_Invite($userid);
					//thong bao thanh vien lien minh da du
					$curent_product .= showMsgJoinAlly(getAllyNameByAllyId($allyId));																							
				}
			}				
		}
		//da gia nhap lam member cua lien minh khac
		elseif(Check_Allies_Invite($userid,1)>0 || Check_Allies_Exist($userid)==1){
			if(Check_Allies_Invite($userid,1)>0){
				$sql="SELECT name,tag FROM wg_allies WHERE id=(SELECT ally_id FROM wg_ally_members WHERE user_id=$userid AND right_=1)";
			}elseif(Check_Allies_Exist($userid)==1)	{
				$sql="SELECT name,tag FROM wg_allies WHERE user_id=$userid";				
			}
			$db->setQuery($sql);
			$wg_allies=null;
			$db->loadObject($wg_allies);
			$parse['link_tag']=$wg_allies->tag;
			$parse['link_name']=$wg_allies->name;
			$curent_product=parsetemplate(gettemplate('join_allies'),$parse);
		}
	}	
	return $curent_product;
}
/*
* @Author: duc hien
* @Des: cap nhat cho quest cua nguoi choi
* @param: $userid= id cua nguoi choi
* @return: update SQl
*/
function UpdateMission1($view,$userid,$num)
{
	global $db;
	$sql="UPDATE wg_users SET view=$view,quest=quest+$num WHERE id=$userid";
	$db->setQuery($sql);
	return $db->query();
}
function UpdateMission2($village,$userid,$quest,$num)
{
	global $db;
	Update_Glod_RS_Mission($village,$userid,$quest);
	$sql="UPDATE wg_users SET quest=quest+$num WHERE id=$userid";
	$db->setQuery($sql);
	return $db->query();
}
/*
* @Author: duc hien
* @Des: cap nhat cho quest cua nguoi choi
* @param: $userid= id cua nguoi choi
* @return: update SQl
*/
function CheckNameVillage()
{
	global $user,$wg_village;
	if($wg_village->name==$user['username'])
	{
		return 1;
	}
	return 0;
}
/*
* @Author: duc hien
* @Des: 
* @param:
* @return: so thu tu cua nhiem vu
*/
function Check_Level_Village($type)
{
	global $wg_buildings;
	foreach($wg_buildings as $key)
	{
		if($key->type_id==$type && $key->level>0)
		{
			return 1;
		}
	}
	return 0;
}
/*
* @Author: duc hien
* @Des: 
* @param:
* @return: so thu tu cua nhiem vu
*/
function Check_AllLevel_Village($villa_id,$check)
{
	global $db,$user,$wg_buildings;
	$count=0;
	if($wg_buildings){
		foreach($wg_buildings as $key)
		{
			if($key->type_id>=1 && $key->type_id<=4 && $key->level >=$check)
			{
				$count=$count+1;
			}
		}
	}
	return $count;
}
/*
* @Author: duc hien
* @Des: cap nhat cho quest cua nguoi choi
* @param: $userid= id cua nguoi choi
* @return: update SQl
*/
function Update_Glod_RS_Mission($village,$userid,$id)
{
	global $db;
	$sql="SELECT * FROM wg_mission WHERE id=$id LIMIT 1";
	$db->setQuery($sql);
	$wg_mission = null;
	$db->loadObject($wg_mission);
	if($wg_mission->gold >0)
	{
		$sql="UPDATE wg_plus SET gold=gold+".$wg_mission->gold." WHERE user_id=$userid";
		$db->setQuery($sql);
		$db->query();
	}
	elseif($wg_mission->rs1 >0 && $wg_mission->rs2 >0 && $wg_mission->rs3 >0 && $wg_mission->rs4 >0)
	{
		$sql="UPDATE wg_villages SET rs1=rs1+".$wg_mission->rs1.",rs2=rs2+".$wg_mission->rs2.",rs3=rs3+".$wg_mission->rs3.",rs4=rs4+".$wg_mission->rs4." WHERE id=$village AND kind_id < 7";
		$db->setQuery($sql);
		$db->query();
	}
	return false;
}
/*
* @Author: duc hien
* @Des: cap nhat cho quest cua nguoi choi
* @param: $userid= id cua nguoi choi
* @return: update SQl
*/
function InsertReportQuest7($userid)
{
	global $db,$lang;
	includeLang('quest');
	$parse=$lang;
	$sql="SELECT rs1,rs2,rs3,rs4 FROM wg_mission WHERE id=8 LIMIT 1";
	$db->setQuery($sql);
	$wg_mission = null;
	$db->loadObject($wg_mission);
	$parse['rs1']=$wg_mission->rs1;
	$parse['rs2']=$wg_mission->rs2;
	$parse['rs3']=$wg_mission->rs3;
	$parse['rs4']=$wg_mission->rs4;
	$type=REPORT_MISSION_7;
	$content=parsetemplate(gettemplate('report_quest8'),$parse);
	$sql="INSERT INTO `wg_reports` (`user_id`, `title`, `time`, `report_text`, `status`, `type`) VALUES($userid,'".$lang['report_title']."', '".date('Y-m-d H:i:s')."','".$content."',0,$type)";
	$db->setQuery($sql);
	if(!$db->query())
	{
		globalError2($sql);
	}
	$sql_bk="INSERT INTO `wg_reports_bk` (`user_id`, `title`, `time`, `report_text`, `status`, `type`) VALUES($userid,'".$lang['report_title']."', '".date('Y-m-d H:i:s')."','".$content."',0,$type)";
	$db->setQuery($sql_bk);
	if(!$db->query())
	{
		globalError2($sql_bk);
	}
	return NULL;
}
function InsertMessageQuest8($userid)
{
	global $db,$lang;
	includeLang('quest');
	$sql="SELECT gold FROM wg_mission WHERE id=9 LIMIT 1";
	$db->setQuery($sql);
	$gold= $db->loadResult();
	$topic=$lang['content_mess1'].' '.$gold.' Asu';
	$content=$lang['content_mess1'].' '.$gold.' '.$lang['content_mess2'];
	$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES
	($userid,1,0,'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."')";
	$db->setQuery($sql);
	return $db->query();
}
function Check_SumTroop_Village($village_id)
{
	global $db;
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_troop_villa  WHERE village_id=$village_id";
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	if($count>0)
	{	
		$sql="SELECT MAX(num) as Live,MAX(die_num) as Die FROM wg_troop_villa WHERE village_id=$village_id";
		$db->setQuery($sql);
		$query = null;
		$db->loadObject($query);
		if(($query->Live - $query->Die) >=2)
		{
			return 1;
		}
	}
	else
	{
		return 0;
	}
}
function checkReadQuest7($userid)
{
	global $db;
	$sql='SELECT COUNT(DISTINCT(id)) FROM wg_reports WHERE user_id='.$userid.' AND status=1';
	$db->setQuery($sql);
	$count = (int)$db->loadResult();
	return $count;	
}
function checkReadQuest8($userid)
{
	global $db;
	$sql='SELECT COUNT(DISTINCT(id)) FROM wg_messages WHERE id_user='.$userid.' AND from_id>0 AND status=1';
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
// lay level cua main building -> truy van theo ID lang
function MainBuilding_level() //duoc su dung trong fuc_allie
{
	global $wg_buildings;
	foreach($wg_buildings as $key)
	{
		$count=0;
		if($key->type_id==12)
		{
			return $key->level;	
		}
	}
	return 0;
}
/*
* @Author: duc hien
* @Des: cap nhat cho quest cua nguoi choi
* @param: $userid= id cua nguoi choi
* @return: update SQl
*/
function Mission1($view,$quest,$userid,$villa_id)
{
	global $user;
	switch($quest)
	{
		case 1:
			UpdateMission1(0,$userid,0.5);
			break;
		case 1.5:// Mission 1 :Lâm trường
			//neu da xem qua nhiem vu thi moi update len
			if(Check_Level_Village(1)>0 && $view==1)
			{		
				UpdateMission1(0,$userid,0.5);
				$user['quest']=2;
				break;
			}
			break;
		case 2.5:// Mission 2 :Mỏ đá
			if(Check_Level_Village(4)>0 && $view==1)
			{
				UpdateMission1(0,$userid,0.5);
				$user['quest']=3;
				break;
			}
			break;
		case 3.5:// Mission 3 :Mỏ sắt
			if(Check_Level_Village(3)>0 && $view==1)
			{
				UpdateMission1(0,$userid,0.5);
				$user['quest']=4;
				break;
			}
			break;
		case 4.5:// Mission 4 :Đồng lúa
			if(Check_Level_Village(2)>0 && $view==1)
			{
				UpdateMission1(0,$userid,0.5);
				$user['quest']=5;
				break;
			}
			break;
		case 5.5://doi ten lang
			if(CheckNameVillage()==0 && $view==1)
			{
				UpdateMission1(0,$userid,0.5);
				$user['quest']=6;
				break;
			}
			break;
		case 6.5:// xem xep hang nguoi choi
			if($view==1)
			{
				InsertReportQuest7($userid);// goi bao cao cho nguoi choi
				UpdateMission1(0,$userid,0.5);
				$user['quest']=7;
				break;
			}
			break;
		case 7.5:
			if($view==1 && checkReadQuest7($userid)>0)			
			{
				InsertMessageQuest8($userid);// goi tin nhan cho nguoi choi
				UpdateMission1(0,$userid,0.5);
				$user['quest']=8;
				break;
			}
			break;
		case 8.5:
			if($view==1 && checkReadQuest8($userid)>0)			
			{
				UpdateMission1(0,$userid,0.5);
				$user['quest']=9;
				break;
			}
			break;
		case 9.5:
			if(Check_AllLevel_Village($villa_id,1)==18 && $view==1)
			{
				UpdateMission1(0,$userid,0.5);
				$user['quest']=10;
				break;
			}
			break;	
		case 10.5:
			if(Check_Level_Village(15)>0 && $view==1)
			{
				 UpdateMission1(0,$userid,0.5);
				 $user['quest']=11;
				 break;
			}
			break;
		case 11.5:
			if(MainBuilding_level()>=3 && $view==1)
			{
				 UpdateMission1(0,$userid,0.5);
				 $user['quest']=12;
				 break;
			}
			break;
		case 12.5:
			if(Check_Level_Village(10)>0 && $view==1)
			{
				 UpdateMission1(0,$userid,0.5);
				 $user['quest']=13;
				 break;
			}
			break;
		case 13.5:
			if(Check_Level_Village(11)>0 && $view==1)
			{
				 UpdateMission1(0,$userid,0.5);
				 $user['quest']=14;
				 break;
			}
			break;
		case 14.5:
			if(Check_Level_Village(13)>0 && $view==1)
			{
				 UpdateMission1(0,$userid,0.5);
				 $user['quest']=15;
				 break;
			}
			break;
		case 15.5:
			if(Check_Level_Village(28)>0 && $view==1)
			{
				 UpdateMission1(0,$userid,0.5);
				 $user['quest']=16;
				 break;
			}
			break;
		case 16.5:
			if(Check_Level_Village(14)>0 && $view==1)
			{
				 UpdateMission1(0,$userid,0.5);
				 $user['quest']=17;
				 break;
			}
			break;
		case 17.5:
			if(Check_AllLevel_Village($villa_id,2)==18 && $view==1)
			{
			 	UpdateMission1(0,$userid,0.5);
				$user['quest']=18;
			 	break;
			 }
			break;
		case 18.5:
			if(Check_SumTroop_Village($villa_id)==1 && $view==1)
			{
				 UpdateMission1(0,$userid,0.5);
				 $user['quest']=19;
				 break;
			}
			break;
	}
}
/*
* @Author: duchien
* @Des: hinh anh quoc gia cua user 
* @param: + $villa_id: id cua village	
* @return: hinh anh quoc gia
*/
function imagesNation($villa_id)
{
	global $db,$lang,$user;
	Mission1($user['view'],$user['quest'],$user['id'],$villa_id);	
	includeLang('village_allie');
	$parse=$lang;
	$img=array(1=>'images/un/q/l1.jpg',2=>'images/un/q/l2.jpg',3=>'images/un/q/l3.jpg');
	$img1=array(1=>'images/un/q/l1a.jpg',2=>'images/un/q/l2a.jpg',3=>'images/un/q/l3a.jpg');
	$temp1=$user['quest'];
	$temp2=(int)($temp1);	
	if($temp1==1)
	{
		$parse['images']=$img[$user['nation_id']];
		$page = parsetemplate(gettemplate('quest'), $parse);
		return $page;
	}
	else
	{	
		$parse['id']=$temp1;		
		if($temp1==$temp2)
		{
			$parse['guide']=$lang['reward'];
			$parse['images']=$img1[$user['nation_id']];
		}
		else
		{
			$parse['guide']=$lang['mission'].' '.$temp2;
			$parse['images']=$img[$user['nation_id']];
			if($user['quest']>19)
			{
				$parse['images']=$img[$user['nation_id']];
				$page = parsetemplate(gettemplate('quest1a'), $parse);
				return $page;
			}			
		}
		$page = parsetemplate(gettemplate('quest1'), $parse);
		return $page;
	}
}
/*
* @Author: tdnquang
* @Des: lay user_id thong qua ten lang
* @param: + $villageName: ten lang
* @return: + user_id
*/
function getUserIdByVillageName($villageName)
{
	global $db;
	$sql = "SELECT user_id FROM wg_villages WHERE name = '".$villageName."' AND kind_id < 7";	
	$db->setQuery($sql);
	$userId=$db->loadResult();	
	return $userId;
}

/*
* @Author: tdnquang
* @Des: lay level cua toa dai su o lang muon chuyen thanh thu do
* @param: + $villageId: id cua lang muon chuyen thanh thu do
* @return: + level toa dai su o lang do
*/
function getLevelEmbassy($villageId)
{
	global $db;
	//type_id = 14: toa dai su
	$sql = "SELECT level FROM wg_buildings WHERE vila_id = ".$villageId." AND type_id = 14";
	$db->setQuery($sql);
	$checkEmbassy = null;
	$db->loadObject($checkEmbassy);
	return $checkEmbassy->level;	
}
/*
function getLevelEmbassy_bk($villageId)
{
	global $db,$wg_buildings;
	foreach($wg_buildings as $key)
	{
		if($key->type_id==14)
		{
			return $key->level;
		}
	}
	return 0;
}
*/
/*
* @Author: tdnquang
* @Des: cap nhat thu do moi
* @param: + $villageIdNew: id cua lang moi chuyen thanh thu do
		  + $userId: id cua user
* @return: 
*/
function updateCapital($villageIdNew, $userId)
{
	global $db;
	$sql = "UPDATE wg_users SET villages_id = ".$villageIdNew." WHERE id = ".$userId;
	$db->setQuery($sql);
	$db->query($sql);	
}

/*
* @Author: tdnquang
* @Des: cap nhat level resource
* @param: + $villageIdOld: id cua lang thu do cu
* @return: 
*/
function updateLevelResource($villageIdOld)
{
	global $db;
	$sql = "UPDATE wg_buildings SET level = 10 WHERE vila_id = ".$villageIdOld." AND type_id <= 4 AND level > 10";
	$db->setQuery($sql);
	$db->query($sql);	
}
/*
* @Author: tdnquang
* @Des: cap nhat description cua ally
* @param: + $description: thong tin mieu ta
* 		  + $slogan: slogan
* 		  +	$allyId: id cua ally	
* @return: 
*/
function updateDescriptionAlly($description, $slogan, $allyId)
{
	global $db;
	$sql="UPDATE wg_allies SET description='".$description."', slogan='".$slogan."' WHERE id=".$allyId;
	$db->setQuery($sql);
	$db->query($sql);	
}

/*
* @Author: tdnquang
* @Des: lay thong tin trong ally memeber
* @param: + $field: lay thong tin tu nhung field nao
* 		  + $whereField: dieu kien 		  	
* @return: + thong tin trong table wg_ally_members
*/
function getInfoFromAllyMember($field, $whereField)
{
	global $db;
	$sql = "SELECT $field FROM wg_ally_members WHERE $whereField";
	$db->setQuery($sql);
	$db->loadObject($allyMemberInfo);
	return $allyMemberInfo;	
}

/*
* @Author: tdnquang
* @Des: lay thong tin trong wg_allies
* @param: + $field: lay thong tin tu nhung field nao
* 		  + $whereField: dieu kien 		  	
* @return: + thong tin trong table wg_allies
*/
function getInfoFromAlly($field, $whereField)
{
	global $db;
	$sql = "SELECT $field FROM wg_allies WHERE $whereField";
	$db->setQuery($sql);
	$db->loadObject($allyInfo);
	return $allyInfo;	
}

/*
* @Author: tdnquang
* @Des: insert thong tin lien kiet lien minh giua cac lien minh
* @param: + $allyId1: id cua lien minh moi
* 		  + $allyId2: id cua lien minh duoc moi 	
* 		  + $type: kieu lien ket		  	
* @return: 
*/
function insertRelationAlly($allyId1, $allyId2, $type)
{
	global $db;
	$sql = "INSERT INTO wg_ally_relation(ally_id_1, ally_id_2, type, status, time)
				   VALUE('".$allyId1."','".$allyId2."','".$type."','0','".date("Y-m-d H:i:s")."')";
	$db->setQuery($sql);
	$db->query();
}

/*
* @Author: tdnquang
* @Des: insert su kien vao table wg_ally_news
* @param: + $allyId1: id cua lien minh moi
* 		  + $allyId2: id cua lien minh duoc moi
* 		  + $content1: noi dung
* 		  + $content2: noi dung 	
* @return: 
*/
function insertEvent($allyId1, $content1, $allyId2, $content2)
{
	global $db;
	$sql ="INSERT INTO wg_ally_news(ally_id,content, time) 
				  VALUES(".$allyId1.",'".$content1."','".date("Y-m-d H:i:s")."'),
						(".$allyId2.",'".$content2."','".date("Y-m-d H:i:s")."')";
	$db->setQuery($sql);
	$db->query();
}

/*
* @Author: tdnquang
* @Des: lay thong tin trong wg_ally_relation
* @param: + $field: lay thong tin tu nhung field nao
* 		  + $whereField: dieu kien 		  	
* @return: + thong tin trong table wg_ally_relation
*/
function getInfoFromAllyRelation($field, $whereField)
{
	global $db;
	$sql = "SELECT $field FROM wg_ally_relation WHERE $whereField";
	$db->setQuery($sql);
	$allyInfoRelation = null;
	$db->loadObject($allyInfoRelation);
	return $allyInfoRelation;	
}
/*
* @Author: tdnquang
* @Des: dem tong so thanh vien cua lien minh
* @param: + $allyId: id cua lien minh	
* @return: + tong so thanh vien trong lien minh
*/
function countMemberAlly($allyId)
{	
	global $db;
	$numMember = 0;
	$sql = "SELECT COUNT(DISTINCT(id)) FROM wg_ally_members WHERE ally_id = ".$allyId." AND right_ = 1";
	$db->setQuery($sql);	
	$numMember = $db->loadResult();
	return $numMember;
}

/*
* @Author: tdnquang
* @Des: dem tong so thanh vien cua lien minh
* @param: + $allyId: id cua lien minh	
* @return: + so thanh vien toi da ma lien minh do co the co
*/
function maxMemberAllyHave($allyId)
{
	global $db;
	$sql = "SELECT id FROM wg_villages WHERE user_id =(SELECT user_id FROM wg_allies WHERE id = ".$allyId.") AND kind_id < 7";
	$db->setQuery($sql);
	$$villages_id=NULL;	
	$villages_id=$db->loadObjectList();	
	if($villages_id)
	{
		$list='';;
		foreach($villages_id as $key=>$v)
		{
			$list.=$v->id.',';
		}
		$sql = "SELECT product_hour FROM wg_buildings WHERE vila_id IN (".substr($list,0,-1).") AND type_id = 14";
		$db->setQuery($sql);
		$maxMember = null;
		$db->loadObject($maxMember);
		return $maxMember->product_hour;
	}
	return 0;	
}

/*
* @Author: tdnquang
* @Des: lay ally_id boi user_id
* @param: $userId: id cua user thuoc lien minh
* @return: ally_id: id cua lien minh
*/
function getAllyIdByUserId($userId){
	global $db;
	$sql = "SELECT ally_id FROM wg_ally_members WHERE user_id = ".$userId." AND right_ = 1";
	$db->setQuery($sql);
	$allyId = null;
	$db->loadObject($allyId);
	return $allyId->ally_id;
}

/*
* @Author: tdnquang
* @Des: Hien thi danh sach loi moi cua lien minh minh den lien minh khac
* @param: $allyId: id cua lien minh
* @return: danh sach nhung loi moi
*/
function getDiplomacyOwner($allyId1)
{
	/************** Own offers *******************/
	global $db, $lang;
	includeLang('allianz');
	$parse = $lang;
	$sql = "SELECT * FROM wg_ally_relation WHERE ally_id_1 = '".$allyId1."' AND status=0 ORDER BY id DESC";
	$db->setQuery($sql);	
	$ownOffers = $db->loadObjectList();	
	$countOwnOffer = 0;
	$ownOfferList = "";
	if($ownOffers){
		foreach($ownOffers as $ownOffer)
		{
			$type = $ownOffer->type; // relation
			if($type == 1)
				$typeName = $lang['Diplomacy type 11'];//"Đối đầu";
			if($type == 2)
				$typeName = $lang['Diplomacy type 21'];//"Hòa hảo";
			if($type == 3)
				$typeName = $lang['Diplomacy type 31'];//"Đồng minh";
	
			$allyId2 = $ownOffer->ally_id_2;
			$parse['ally_id_2'] = $allyId2;
			//Get ally_name_2
			$sql = "SELECT name FROM wg_allies WHERE id='".$allyId2."'";
			$db->setQuery($sql);
			$allyTo = null;
			$db->loadObject($allyTo);
			$allyNameTo = $allyTo->name;
	
			$parse['relation_des_own'] = $typeName." với ".$allyNameTo;
			$parse['status'] = "Chưa phản hồi";
			$parse['relation_id'] = $ownOffer->id;
			$ownOfferList .= parsetemplate(gettemplate('allian_diplomacy_own_offer'),$parse);
			$countOwnOffer++;
		}
	}
	return $ownOfferList;
}

/*
* @Author: tdnquang
* @Des: Hien thi danh sach loi moi cua lien minh khac den lien minh minh
* @param: $allyId: id cua lien minh
* @return: danh sach nhung loi moi
*/

function getDiplomacyForeign($allyId2)
{
		global $db, $lang;
		includeLang('allianz');
		$parse = $lang;
		$sql = "SELECT * FROM wg_ally_relation WHERE ally_id_2 = '".$allyId2."' AND status=0 ORDER BY id DESC";
		$db->setQuery($sql);
		$foreignOffers = $db->loadObjectList();
		$countForeignOffer = 0;
		$foreignOfferList = "";
		if($foreignOffers){
			foreach($foreignOffers as $foreignOffer)
			{
				$type = $foreignOffer->type; // relation
				if($type == 1)
					$typeName = $lang['Diplomacy type 11'];//"Đối đầu";
				if($type == 2)
					$typeName = $lang['Diplomacy type 21'];//"Hữu hảo";
				if($type == 3)
					$typeName = $lang['Diplomacy type 31'];//"Đồng minh";
	
				$allyId1 = $foreignOffer->ally_id_1;
				$parse['ally_id_1'] = $allyId1;
				//Get ally_name_1
				$sql = "SELECT name FROM wg_allies WHERE id='".$allyId1."'";
				$db->setQuery($sql);
				$getAlly1 = null;
				$db->loadObject($getAlly1);
				$allyName1 = $getAlly1->name;
	
				$parse['relation_des_foreign'] = $typeName." với ".$allyName1;
				$parse['relation_id'] = $foreignOffer->id;
				$foreignOfferList .= parsetemplate(gettemplate('allian_diplomacy_foreign_offer'),$parse);
				$countForeignOffer++;
			}
		}
		return $foreignOfferList;
}

/*
* @Author: tdnquang
* @Des: Hien thi danh sach nhung moi lien he giua lien minh minh voi nhung lien minh khac
* @param: $allyId: id cua lien minh
* @return: danh sach nhung moi lien he
*/
function getDiplomacyExist($allyId)
{
	global $db, $lang;
	includeLang('allianz');
	$parse = $lang;
	$sql = "SELECT * FROM wg_ally_relation WHERE (ally_id_1='".$allyId."' OR ally_id_2='".$allyId."') 
												 AND status=1 ORDER BY id DESC"; //status = 1
	$db->setQuery($sql);	
	$exists = $db->loadObjectList();
	
	$countExistRelation = 0;
	$existRelationList = "";
	if($exists){
		foreach($exists as $exist)
		{
			$type = $exist->type; // relation
			if($type == 1)
				$typeName = $lang['Diplomacy type 11'];//"Đối đầu";
			if($type == 2)
				$typeName = $lang['Diplomacy type 21'];//"Hữu hảo";
			if($type == 3)
				$typeName = $lang['Diplomacy type 31'];//"Đồng minh";
			//Get ally_name_2
			$allyId2 = $exist->ally_id_2;
			$parse['ally_id_1'] = $allyId2;
			$allyName2 = getAllyNameByAllyId($allyId2);
			//Get ally_name_1
			$allyId1 = $exist->ally_id_1;
			$parse['ally_id_1'] = $allyId2;
			$allyName1 = getAllyNameByAllyId($allyId1);
			
			if($allyId1 == $allyId){
				$parse['exist_relation'] = $typeName." ".$lang['With']." ".$allyName2;
				$parse['ally_id_to'] = $allyId2;			
			}
			if($allyId2 == $allyId){
				$parse['exist_relation'] = $typeName." ".$lang['With']." ".$allyName1;
				$parse['ally_id_to'] = $allyId1;				
			}			
			$parse['relation_id'] = $exist->id;
			$existRelationList .= parsetemplate(gettemplate('allian_diplomacy_exist_relation'),$parse);
			$countExistRelation++;
		}
	}
	return $existRelationList;
}
/*
* @Author: tdnquang
* @Des: Hien thi danh sach nhung loi moi tham gia lien minh
* @param: $allyId: id cua lien minh
* @return: danh sach nhung loi moi
*/
function getInvitationList($allyId)
{
	global $db, $lang;
	includeLang('allianz');
	$parse = $lang;
	$sql = "SELECT tb.id,tb.user_id,wg_users.username FROM wg_ally_members AS tb 
	LEFT JOIN wg_users ON wg_users.id=tb.user_id
	WHERE tb.right_ = 0 AND tb.ally_id = ".$allyId." ORDER BY tb.id DESC";
	$db->setQuery($sql);	
	$invitations = $db->loadObjectList();
	$viewInvitationList[0]=0;
	$viewInvitationList[1]="";
	if($invitations)
	{
		foreach($invitations as $invitation)
		{
			if(empty($invitation->username))
			{
				$viewInvitationList[1].='';
			}
			else
			{			
				$parse['userId']=$invitation->user_id;
				$parse['id'] =$invitation->id;
				$parse['userName'] =$invitation->username;
				$viewInvitationList[1].= parsetemplate(gettemplate('allian_invite_player_list'),$parse);
				$viewInvitationList[0]++;
			}						
		}
	}
	return $viewInvitationList;
}
/*
* @Author: tdnquang
* @Des: hien thi moi quan he voi nhung lien minh khac
* @param: + $allyId: id cua lien minh
* 		  +	$type: kieu quan he
* @return: 
*/
function showAllyRelation($allyId, $type)
{
	global $db, $lang;
	includeLang('allianz');
	$parse = $lang;	
	$sql = "SELECT * FROM wg_ally_relation WHERE (ally_id_1 = ".$allyId." OR ally_id_2 = ".$allyId.") 
			AND status = 1 AND type = ".$type." ORDER BY type ASC";	
	$db->setQuery($sql);	
	$allyRelations = $db->loadObjectList();	
	if($allyRelations){			
		$viewRelationList = "";						
		foreach($allyRelations as $allyRelation)
		{
			//Get ally_name_2
			$allyId2 = $allyRelation->ally_id_2;
			$parse['ally_id_1'] = $allyId2;
			$allyName2 = getAllyNameByAllyId($allyId2);
			//Get ally_name_1
			$allyId1 = $allyRelation->ally_id_1;
			$parse['ally_id_1'] = $allyId2;
			$allyName1 = getAllyNameByAllyId($allyId1);
			$parse['ally_id_to'] = "";
			if($allyId1 == $allyId){
				$parse['ally_name'] = $allyName2;
				$parse['ally_id_to'] = $allyId2;	 			
			}
			if($allyId2 == $allyId){
				$parse['ally_name'] = $allyName1;	
				$parse['ally_id_to'] = $allyId1;			
			}			
			$parse['relation_id'] = $allyRelation->id;
			$viewRelationList .= parsetemplate(gettemplate('allianz_relation'),$parse);			
		}		
	}
	return $viewRelationList;
}
?>