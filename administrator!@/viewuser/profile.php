<?php
/*
	Plugin Name: profile.php
	Plugin URI: http://asuwa.net/profile.php
	Description: 
	+ Hien thi nhung thong tin chi tiet ve tai khoan do
	+ Chuc nang thay doi thong tin tai khoan
	+ Chuc nang thay doi mat khau, email, tai khoan dung chung, xoa tai khoan
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.php'); 
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_status.php');
include($ugamela_root_path . 'includes/function_allian.php');
include($ugamela_root_path . 'includes/function_profile.php');
require_once($ugamela_root_path . 'includes/function_attack.php');
require_once($ugamela_root_path . 'includes/function_status.php');
require_once($ugamela_root_path . 'includes/function_troop.php');
include($ugamela_root_path . 'includes/constant_profile.php');
require_once($ugamela_root_path . 'includes/function_resource.php');
include($ugamela_root_path . 'includes/wordFilter.php');
include_once ($ugamela_root_path . 'includes/usersOnline.class.php');

checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
$visitors_online = new usersOnline();

includeLang('profile');
global $db,$user, $lang, $allyId, $wg_village,$wg_buildings,$timeAgain, $game_config, $wordFilters;
$parse = $lang;
$timeAgain=$user['amount_time']-(time()-$_SESSION['last_login']);
$village =$_COOKIE['villa_id_cookie'];
$wg_buildings =$wg_village =NULL;
$wg_village = getVillage($village);
$wg_buildings = getBuildings($village);
getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());
deleteAccount($user['id']);

$parse['invite_ally']='';
if(isset($_GET['uid']) && is_numeric($_GET['uid']))
{
	$userId =$_GET['uid'];
	$parse['profile_menu'] = "";		
	if($userId == $user['id'])
	{
		$userInfo = getAllUserInfo($userId);
		$parse['overview_menu'] = $lang['Overview'];
		$parse['edit_profile_menu'] = $lang['Edit Profile'];
		$parse['option_menu'] = $lang['Option'];
		$parse['phone']=$userInfo->phone;		
		$parse['change_profile_or_write_message'] = "<a href=\"profile.php?s=1\">".$lang['Edit Profile']." </a>";
	}
	else
	{
		$userInfo = getAllUserInfo($userId);		
		$parse['not_menu'] = "style=\"visibility:hidden; margin-bottom:-20px\"";
		$parse['change_profile_or_write_message']='<a href="messages.php?tab=1&username='.$userInfo->username.'">'.$lang['Send message'].'</a>';
		$parse['phone']='';		
		if($user['alliance_id']>0)
		{
			$sql="SELECT privilege FROM wg_ally_members WHERE user_id=".$user['id']." AND ally_id=".$user['alliance_id'];
			$db->setQuery($sql);
			$privilege=$db->loadResult();					
			if($privilege)			
			{		
				$permission = str_split($privilege);
				if($permission[7] ==1)
				{
					$sql="SELECT id FROM wg_ally_members WHERE user_id=".$userId." AND ally_id=".$user['alliance_id'];
					$db->setQuery($sql);
					$db->loadObject($wg_ally_members);
					if(!$wg_ally_members)
					{				
					  $parse['invite_ally']='<a href="allianz.php?event=6&username='.$userInfo->username.'">'.$lang['Invite_ally'].'</a>';				
					}
				}
			}
		}		
	}
}else{
	$userId = $user['id'];
	$userInfo = getAllUserInfo($userId);
	if(empty($userInfo))
	{
		header("Location: village1.php");
		exit();
	}
	$parse['phone']=$userInfo->phone;
	$parse['overview_menu'] = $lang['Overview'];
	$parse['edit_profile_menu'] = $lang['Edit Profile'];
	$parse['option_menu'] = $lang['Option'];			
	$parse['change_profile_or_write_message']="<a href=\"profile.php?s=1\">".$lang['Edit Profile']."</a>";
}
//END: get user_id from request

//START: detail information of user	
$sql="SELECT  * FROM wg_history_top10 WHERE user_id=".$userId." ORDER BY `week_nd` ASC";
$db->setQuery($sql);
$wg_history_top10 = NULL;
$wg_history_top10=$db->loadObjectList();
if($wg_history_top10)
{	
	$parse['row_medal']=returnListMedal('',$wg_history_top10,0);
	$parse['profile_medal']=parsetemplate(gettemplate('profile_medal'), $parse);
}
else
{
	$parse['profile_medal']='';
}

$parse['uid']=$userInfo->id;
$parse['player']=$userInfo->username;
$parse['count_villages']=$userInfo->sum_villages;

//START: rank for user
$sql="SELECT COUNT(DISTINCT(id)) FROM wg_users 
			WHERE (population>".$userInfo->population.") OR ((population=".$userInfo->population.") 
			AND (id<".$userId."))";
$db->setQuery($sql);		
$parse['rank_user'] = intval($db->loadResult())+1;
//END: rank for user

$parse['population_player']=$userInfo->population;	

//get ally name of user
$allyName = getInfoFromAlly("name", "id = $userInfo->alliance_id");
$parse['ally'] = $allyName->name;
$parse['ally_id'] = $userInfo->alliance_id;

//START: get ten chung toc
$nation=array(1=>'Arabia',2=>'Mongo',3=>'Sunda');
$parse['tribe']=$nation[$userInfo->nation_id];

//START: list of village
$sql="SELECT id,name,x,y,user_id,workers FROM wg_villages WHERE user_id=".$userId." AND kind_id < 7 
		ORDER BY workers DESC";
$db->setQuery($sql); 
$villagesInfo=NULL;
$villagesInfo=$db->loadObjectList();//echo '<pre>';print_r($villagesInfo);die($userInfo->villages_id);
$count =0;
$parse['village_name'] = "";
$parse['village_name_edit'] = "";
$parse['village_name_parse'] = "";
if($villagesInfo)
{
	foreach ($villagesInfo as $villageInfo)
	{  
		$parse['toado_x'] = $villageInfo->x;
		$parse['toado_y'] = $villageInfo->y;
		$parse['inhabitants'] = $villageInfo->workers;	
		$parse['village_id'] = $villageInfo->id;			
		if($villageInfo->id == $userInfo->villages_id)
		{
			$parse['village_name'] = $villageInfo->name." <span class=\"c\">(".$lang['Capital'].")</span>";
			$parse['village_name_edit'] = $villageInfo->name;
		}
		else
		{
			$parse['village_name'] = $villageInfo->name;
			$parse['village_name_edit'] = $villageInfo->name;
			if($villageInfo->name == 'NewName')
			{
				$parse['village_name']=$parse['village_name_edit']=$lang['New name'];				
			}			
		}
		$villageList .= parsetemplate(gettemplate('profile_village_list'), $parse);
		$villageListEdit .= parsetemplate(gettemplate('profile_village_list_edit'), $parse);
		$count ++;
	}
}
$parse['sum_village'] = $count;
$parse['view_village_list'] = $villageList;
$parse['vilage_name_list_edit'] = $villageListEdit;
$parse['description'] = $userInfo->description;	
$parse['sign'] = $userInfo->sign;
$parse['old_email'] = $userInfo->email;	
$birthday = explode("/",$userInfo->birthday);
if($birthday[0] == "" || $birthday[1] == 0 || $birthday[2] == ""){
	$parse['birthday'] = "";
}else{
	$parse['birthday'] = $userInfo->birthday;
}
	
if($userInfo->sex == 0){
	$parse['sex'] =$lang['Male'];
	$parse['check_male'] = "checked";
}elseif($userInfo->sex == 1){
	$parse['sex'] =$lang['Female'];
	$parse['check_female'] = "checked";
}	
//END: get user profile info

//START: get city
$parse['location'] = "";
foreach($city as $city_row){
	if($userInfo->birthplace == $city_row['id']){
		$parse['location'] = $city_row['name'];
		$parse['select_'.$city_row['id']] = "selected";		
	}
}
//END: get city

if($_REQUEST['act'] == "del_acc"){
	$task = "del_acc";
}
if($_GET['act']=='del_account_sister'){
	$task = "del_account_sister";
}
if(isset($_GET['s'])){
	$task = $_GET['s'];
}
//START: switch case
switch($task)
{
	case "1": //edit Profile
		$parse['message'] = "";	
		//START: get month
		foreach($month as $month_row){
			if($birthday[1] == $month_row['id']){
				$parse['month'] = $month_row['name'];
				$parse['select_'.$month_row['id']] = "selected";
			}
		}
		//END: get month
		$birthday = explode("/",$userInfo->birthday);		
		if($birthday[0] == "" || $birthday[1] == 0 || $birthday[2] == ""){
			$parse['birthday'] = "";
		}else{
			$parse['birthday'] = $userInfo->birthday;
		}
		$parse['date'] = $birthday[0];
		$parse['year'] = $birthday[2];			
		
		$descriptionEdit = $userInfo->description;
		$parse['description_edit'] = "";
		$parse['description_edit'] = str_replace("<br />", "\n", $descriptionEdit);
		$signEdit = $userInfo->sign;
		$parse['sign_edit']  = "";
		$parse['sign_edit'] = str_replace("<br />", "\n", $signEdit);
		$parse['phone'] =$userInfo->phone;
		//START: Repair error xuong dong khi edit profile 
		$parse['description_edit'] = str_replace("<br />", "", $descriptionEdit);
		$parse['sign_edit'] = str_replace("<br />", "", $signEdit);			
		//END: Repair error xuong dong khi edit profile 
		$isCheck = true;
		if($_GET['act']==1)
		{
			$parse['date'] = "";	
			$parse['year'] = "";				
			$date = str_replace('.','',$db->getEscaped($_POST['date']));
			$month = $db->getEscaped($_POST['month']);
			$year = str_replace('.','',$db->getEscaped($_POST['year']));
			$birthday = $date."/".$month."/".$year;
			$sex = $db->getEscaped($_POST['sex']);
			$location = $db->getEscaped($_POST['city']);
			//get village from list box
			$villageIdListBox =substr($db->getEscaped($_POST['vilage_name_list_edit']),0,25);				
			//get village from text box
			$villageName =substr(strip_tags($db->getEscaped($_POST['village_name'])),0,25);
			$phone=$userInfo->phone;
			if(is_numeric($_POST['phone']))
			{
				$phone=substr($_POST['phone'],0,13);
			}			
			$description = nl2br(strip_tags($_POST['description']));
			$sign = nl2br(strip_tags($_POST['sign']));
			//START: check day & month & year
			$isCheck = true;
			if($date != "")
			{
				if(!is_numeric($date))
				{
					$parse['message'] = $lang['Date must be number'];	
					$parse['date'] = $date;
					$parse['year'] = $year;
					$isCheck = false;
				}
				elseif($date <= 0 || $date > 31)
				{
					$parse['message'] = $lang['Date must be between 0 and 31'];	
					$parse['date'] = $date;
					$parse['year'] = $year;
					$isCheck = false;
				}
				elseif(!is_numeric($month))
				{
					$parse['message'] =$lang['Month must be number'];
					$parse['date'] = $date;
					$parse['year'] = $year;
					$isCheck = false;				
				}
				elseif($month <= 0 || $month > 12)
				{
					$parse['message'] = $lang['Month must be between 1 and 12'];	
					$parse['date'] = $date;
					$parse['year'] = $year;
					$isCheck = false;
				}
				elseif($year != "")
				{
					if(!is_numeric($year))
					{
						$parse['message'] = $lang['Year must be number'];	
						$parse['year'] = $year;
						$parse['date'] = $date;
						$isCheck = false;
					}
					elseif($year <= 1930)
					{
						$parse['message'] = $lang['Year must be > 1930'];	
						$parse['year'] = $year;
						$parse['date'] = $date;
						$isCheck = false;
					}
					elseif($year > date("Y"))
					{
						$parse['message'] = $lang['Year must be < now'];	
						$parse['year'] = $year;
						$parse['date'] = $date;
						$isCheck = false;
					}
					elseif($month == 2)
					{
						if($year % 4 == 0)
						{//nam nhuan
							if($date > 29)
							{
								$parse['message'] = $lang['February must be <= 29'];
								$parse['date'] = $date;
								$parse['year'] = $year;
								$isCheck = false;
							}
						}
						if($year % 4 != 0) { // nam thuong
							if($date > 28) {
								$parse['message'] = $lang['February in normal year has day <=28'];
								$parse['date'] = $date;
								$parse['year'] = $year;
								$isCheck = false;
							}
						}																					
					}												
				}
				else
				{
					$parse['date'] = $date;
					$isCheck = true;
				}				
			}
			if(!is_numeric($sex) || $sex <0 || $sex >1)
			{
				$parse['message']=$lang['Error profile'];
				$isCheck = false;
			}			
			//END: check day & month & year		
			if($isCheck)
			{				
				$check = null;	
				if($villageName!='')
				{
					$sql="SELECT id FROM wg_villages WHERE name='".$villageName."' AND kind_id < 7";
					$db->setQuery($sql);
					$db->loadObject($check);
				}								
				if(empty($check))
				{
					if($wordFilters[$villageName] !=1 && $wordFilters[$description] !=1 
						&& $wordFilters[$sign] !=1 && $wordFilters[$date] !=1 && $wordFilters[$year] !=1)
					{
						if($villageName != "")
						{
							if($villageIdListBox == $village)
							{
								$wg_village->name = $villageName;
							}
							else
							{
								updateVillageName($villageName, $villageIdListBox);
							}
						}								
						//update profile
						updateProfile($birthday, $sex, $location, $description, $sign,$phone,$user['username']);		
						header("Location:profile.php");						
					}
				}else{
					$parse['message'] = $lang['Village']. " " .$villageName. " " .$lang['to be exist'];
					$parse['village_name_parse'] = $villageName;
					$birthday = explode("/",$userInfo->birthday);
					if($birthday[0] == "" || $birthday[1] == 0 || $birthday[2] == ""){
						$parse['birthday'] = "";
					}else{
						$parse['birthday'] = $userInfo->birthday;
					}
					$parse['date'] = $birthday[0];
					$parse['year'] = $birthday[2];							
				}
			}						
		}
		$page = parsetemplate(gettemplate('profile_edit'), $parse);
		display($page,$lang['profile']);
	break;
	
	case "3": // Account
			
		$parse['time'] = "";
		$parse['account_sister'] = "";
		$parse['hidden'] = "";	
		$parse['pw_invalid']='';
		$parse['acc_sis_id'] =$user['id'];		
		$accountSister=getAccountSisterInfoByID($user['id']);
		$parse['sister_id_1'] =$accountSister->id;
		if($accountSister)
		{
			$parse['account_sister'] =$accountSister->username;
			$parse['hidden_1'] = "style=\"display:none;\"";							
		}
		else
		{				
			$parse['hidden_2'] = "style=\"display:none;\"";	
		}

		$costtime = getTimeEndDeleteAccount($userId); 	
		$parse['id'] = $costtime->id;
		$second =strtotime($costtime->time_end) -time();	
		if($costtime)
		{
			$parse['time'] ='<span id="account1">'.ReturnTime($second).'</span>';					
			$page = parsetemplate(gettemplate('profile_delete_account'), $parse);
			display($page,$lang['profile']);					
		}
					
		if(empty($costtime)){		
			if($_GET['act'] == 3)
			{
				if($_POST['delete_account'] == 1)
				{
					if($_POST['pw_del_acc'] == "")
					{
						$parse['pw_invalid'] = $lang['Input password'];
					}
					else
					{
						if($_SESSION['password'] !=md5($_POST['pw_del_acc']."--" .$dbsettings["secretword"]))
						{
							$parse['pw_invalid'] = $lang['Invalid password'];					
						}
						else
						{
							insertStatusDeleteAccount($userId);
							header("Location:profile.php?s=3");	
						}
					}													
				}

				if($_POST['txt_sister'] != "")
				{
					$sister1 = $db->getEscaped($_POST['txt_sister']);
					if($user['username'] == $sister1)
					{
						$parse['pw_invalid'] = $lang['error_account_sis'];
					}
					else
					{					
						$sql = "SELECT count(id) FROM wg_users WHERE username ='".$sister1."'";
						$db->setQuery ($sql);
						$checkUser = null;
						$checkUser = (int)$db->loadResult();
						if($checkUser == 0){
							$parse['pw_invalid'] = $lang['Account']. " [" .$sister1. "] " .$lang['not exist'];
						}
						else
						{
							$checkAccSister = countAccountSister($user['id']);
							if($checkAccSister == 1){
								$parse['pw_invalid'] = $lang['Had exist account sister'];
							}elseif($checkAccSister == 0)
							{
								$cost_time = 30*24*60*60; // 30 days
								$time = laythoigian(time() + $cost_time);
								insertAccountSister($user['id'], $sister1, $time);																
								header("Location: profile.php?s=3");									
							}
						}
					}										
				}
						
			}				
			$page = parsetemplate(gettemplate('profile_account'), $parse);
			display($page,$lang['profile']);
		}
	break;
	
	case "del_acc":
		if(is_numeric($_GET['id']))
		{
			$sql = "DELETE FROM wg_status WHERE id = ".$_GET['id']." AND object_id = ".$user['id'];
			$db->setQuery ( $sql );
			if(!$db->query())
			{
				globalError2("profile.php".$sql);
			}			
			header("Location: profile.php?s=3");
		}
	break;
	
	case "del_account_sister":
		if(isset($_GET['acc_sis_id']) && is_numeric($_GET['acc_sis_id']) )
		{
			deleteAccountSister($_GET['acc_sis_id']);
		}
		header("Location: profile.php?s=3");
	break;
}

$page = parsetemplate(gettemplate('profile'), $parse);
display($page,$lang['profile']);
ob_end_flush();

function returnListMedal($string,$wg_history_top10,$key)
{
	global $lang;
	$parse=$lang;
	$week_begin=$wg_history_top10[$key]->week_nd;
	if(wg_history_top10)
	{
		foreach($wg_history_top10 as $key=>$v)
		{
			$img=array(1=>'cong',2=>'thu',3=>'tainguyen');
			switch ($v->type){
				case 1:
					$Attack_medal='images/huanchuong/'.$img[$v->type].'/'.$v->rank.'.png';		
					break;
				case 2:
					$Defend_medal='images/huanchuong/'.$img[$v->type].'/'.$v->rank.'.png';
					break;
				case 3:
					$Resource_medal='images/huanchuong/'.$img[$v->type].'/'.$v->rank.'.png';
					break;
				default:					
					break;
			}
			if($v->week_nd==$week_begin)
			{
				$parse['num_week']=$week_begin;
				$parse['titile_attack']='';
				$parse['titile_defend']='';
				$parse['titile_resource']='';
				$parse['Attack_medal']='images/un/a/x.gif';
				$parse['Defend_medal']='images/un/a/x.gif';
				$parse['Resource_medal']='images/un/a/x.gif';
				$char="'".$lang['Medal']." ".$v->rank."'";
				if($Attack_medal !=NULL)  // xoa cai nay thi IE se loi show hinh anh va title
				{
					$parse['titile_attack']='ONMOUSEOVER="ddrivetip('.$char.')"; ONMOUSEOUT="hideddrivetip()"';
					$parse['Attack_medal']= $Attack_medal;
				}
				if($Defend_medal !=NULL)
				{
					$parse['titile_defend']='ONMOUSEOVER="ddrivetip('.$char.')"; ONMOUSEOUT="hideddrivetip()"';
					$parse['Defend_medal']= $Defend_medal;
				}
				if($Resource_medal !=NULL)
				{
					$parse['titile_resource']='ONMOUSEOVER="ddrivetip('.$char.')"; ONMOUSEOUT="hideddrivetip()"';
					$parse['Resource_medal']= $Resource_medal;	
				}				
				$list=parsetemplate(gettemplate('profile_medal_row'),$parse);	
				unset($wg_history_top10[$key]);
				if(count($wg_history_top10)==0)
				{	
					$list_new=''.$string.''.$list.'';
					return $list_new;
				}
			}
			else
			{			
				$list_new=''.$string.''.$list.'';			
				return returnListMedal($list_new,$wg_history_top10,$key);				
			}
		}
	}	
	return $string;
}
?>
