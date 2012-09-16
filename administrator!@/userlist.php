<?php
/*
	Plugin Name: userlist.php
	Plugin URI: http://asuwa.net/administrator/userlist.php
	Description: 
	+ Hien thi danh sach tat ca user
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include('includes/function_badlist.php');
include('includes/function_ban.php');
include('includes/function_users.php');
include('includes/function_paging.php');
include('includes/func_security.php');
include('includes/function_profile.php');
require_once($ugamela_root_path.'soap/call.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel'] < 1)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('userlist');
	global $user, $lang, $db;
	$parse = $lang;
	$parse['base_url'] = $base_url;
	
	if(is_numeric($_GET['delete']) && $user['authlevel'] ==5)
	{	
		$timeBegin =date("Y-m-d H:i:s",time());
		$timeEnd =date("Y-m-d H:i:s",time()-5);		
		$sql="INSERT INTO wg_status(object_id, type, time_begin, time_end, cost_time) 
			VALUES('".$_GET['delete']."','18', '".$timeBegin."','".$timeEnd ."', '0')"; 
		$db->setQuery($sql); 
		$db->query();
		
		deleteAccount($_GET['delete']);			
		adminUpdateRank();	
	}		
	if($_POST['reason'] != ""){
		$task = "ban_user";
	}
	if(isset($_POST['account_sister'])){
		$task = "account_sister";
	}
	
	switch ($task)
	{
		
		//START: ban user
		case "ban_user":		
			$arrs=$_POST['checkbox'];
			if (isset($arrs))
			{
				foreach($arrs as $arr)
				{
					if(!checkUserInTablewg_user_bans($arr))
					{
						updateUserBanned($arr);	
						$userName = admin_getUserNameByUserId($arr);
						$endDate =date("Y-m-d H:i:s",time()+($_POST['date']*24*60*60));		
						$sql = "INSERT INTO wg_user_bans(username, user_id, ban_date, ban_time, end_date, reason) VALUES('".$userName."',".$arr.",'".date("Y-m-d H:i:s")."',".$_POST['date'].",'".$endDate."','".$_POST['reason']."')";
						$db->setQuery($sql);
						$db->query();
						
						//send message to user bi ban		
						$sql ="INSERT INTO wg_messages(id_user, from_id, to_id, times, status, subject, content)  				   VALUES(".$arr.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'Tài khoản của bạn đã bị khóa','".$reason."')";
						$db->setQuery($sql);
						$db->query();								
					}
				}
				header("Location:".$_SERVER['REQUEST_URI']."");
				exit();			
			}		
			break;
		//END: ban user
		
		//START: account sister
		case "account_sister":
			$arrs=$_POST['checkbox'];
			if (isset($arrs)){
				foreach($arrs as $arr){
					$cost_time = 30*24*60*60; // 30 days
					$time = date("Y-m-d H:i:s",time() + $cost_time);				
					$sql = "INSERT INTO wg_sister(user_id, sister1, time) 
							VALUES(".$arr.", '".$user['username']."', '".$time."')";
					$db->setQuery ($sql);
					$db->query();	
				}						
			}	
			header("Location: userlist.php");	
		break;
		//END: account sister
		
		default:		
			define('MAXROW',20); // 10 row per page		
			$parse['value_name']= "";
			//START: get for paging
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
				$x=0;			
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");			
			}
			//END: get for paging
			$sqlsum="SELECT COUNT(DISTINCT(id)) FROM wg_users";
			$sql="SELECT wg_users.id,wg_users.username,wg_users.population,wg_users.sum_villages,
			wg_users.anounment,wg_bad_list.username as ban FROM wg_users 
			LEFT JOIN wg_bad_list ON wg_users.username=wg_bad_list.username 
			ORDER BY wg_users.population DESC LIMIT ".$x.",".constant("MAXROW")."";	
			if(isset($_GET['keyword']) || isset($_GET['BeginKeyword']))
			{
				$keyword='?keyword='.$_GET['keyword'];
				$parse['value_name'] =$_GET['keyword'];
				$compare="wg_users.username LIKE '%".$_GET['keyword']."%'";
				if(isset($_GET['BeginKeyword']))
				{
					$keyword='?BeginKeyword='.$_GET['BeginKeyword'];
					$parse['value_name'] =$_GET['BeginKeyword'];
					$compare="wg_users.username LIKE '".$_GET['BeginKeyword']."%' OR wg_users.username LIKE '".strtolower($_GET['BeginKeyword'])."%'";
				}
				if(isset($_GET["keyword"]))
				{
					$keyword='?keyword='.$_GET["keyword"];
					$parse['value_name'] =$_GET["keyword"];
					$compare="wg_users.username LIKE '%".$_GET["keyword"]."%'";
				}
				$sqlsum="SELECT COUNT(DISTINCT(wg_users.id)) FROM wg_users WHERE ".$compare;
				$sql="SELECT wg_users.id,wg_users.username,wg_users.population,wg_users.sum_villages,
				wg_users.anounment,wg_bad_list.username as ban FROM wg_users 
				LEFT JOIN wg_bad_list ON wg_users.username=wg_bad_list.username WHERE ".$compare." 
				ORDER BY wg_users.population DESC LIMIT ".$x.",".constant("MAXROW")."";			
				$db->setQuery($sql);
				$wg_users=NULL;
				$wg_users=$db->loadObjectList();	
				$total=count($wg_users);	
				$parse['result']=$total;		
			}	
			$db->setQuery($sqlsum);		
			$sumPlayer=(int)$db->loadResult();						
			$parse['sum_user']=$parse['result']=$sumPlayer;
			$parse['total_page']=ceil($sumPlayer/constant("MAXROW"));			
			$db->setQuery($sql);
			$wg_users=NULL;
			$wg_users=$db->loadObjectList();			
			if($wg_users)
			{
				$no =$x+1;			
				foreach ($wg_users as $key=>$element)
				{
					$parse['no']=$parse['rank']=$no;
					$parse['username']=$element->username;
					$parse['email']=get_email_remote($element->username);														
					$parse['population']=$element->population;
					if ($element->anounment == "ban") {
						$parse['ban'] = "<img src=\"../images/un/a/att3.gif\" alt=\"Baned\" />";
					}else {
						$parse['ban'] = "";
					}
					$parse['id']=$element->id;
					if($element->ban !='')
					{
						$parse['bad_list'] = "<b style=\"color:red\">v</b>";
					}else{
						$parse['bad_list'] = "";
					}
					$parse['sum_village']=$element->sum_villages;								
					
					$users_list .= parsetemplate (gettemplate('/admin/userlist_row'), $parse );
					$no++;
			
					$parse['view_users_list']=$users_list;				
					$parse['pagenumber']= paging('userlist.php?', $sumPlayer, constant("MAXROW"));	
					if($keyword)
					{
						$parse['pagenumber']= paging('userlist.php'.$keyword.'', $sumPlayer, constant("MAXROW"));	
					}
				}
			}
			else
			{
				$parse['view_users_list']='';
				$parse['pagenumber']='';
			}		
			$page = parsetemplate(gettemplate("/admin/userlist"), $parse);
			displayAdmin($page,$lang['userlist']);		
		break;
	}
}
function adminUpdateRank()
{
	global $db;
	$sql="SELECT id,population FROM wg_users ORDER BY population DESC";
	$db->setQuery($sql);
	$wg_users=$db->loadObjectList();
	if($wg_users)
	{
		foreach($wg_users as $key=>$value)
		{
			$sql="UPDATE wg_users SET rank=".($key+1)." WHERE id=".$value->id;
			$db->setQuery($sql);
			$db->query();
		}	
	}
	return true;
}
?>