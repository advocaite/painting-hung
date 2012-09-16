<?php
/*
	Plugin Name: bad_list.php
	Plugin URI: http://asuwa.net/administrator/bad_list.php
	Description: 
	+ Hien thi danh sach tat ca user bi nghi van
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/function_profile.php');
include('includes/function_badlist.php');
include('includes/func_security.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1)
{
	header("Location:index.php");
	exit();
}
else
{
	global $db, $lang;
	includeLang('userlist');
	$parse = $lang;
	$parse['base_url'] = $base_url;
	
	$parse['value_name'] = "";
	if(isset($_POST['delete']))
	{
		$task = "del_record";
	}
	if($_POST['reason'] != ""){
		$task = "ban_user";
	}
	switch($task)
	{	
		case "del_record":
			$arrs=$_POST['checkbox'];
			if (isset($arrs))
			{
				foreach($arrs as $arr)
				{
					$sql = "DELETE FROM wg_bad_list WHERE username = '".$arr."'";
					$db->setQuery($sql);
					$db->query();				
				}			
			}	
			header("Location: bad_list.php");
			break;	
		case "ban_user":
			$arrs=$_POST['checkbox'];
			
			if(isset($arrs))
			{	
				require_once('includes/function_ban.php');	
				foreach($arrs as $arr)
				{
					$sql = "SELECT wg_users.id,wg_user_bans.username FROM wg_users lEFT JOIN wg_user_bans ON wg_user_bans.username='".$arr."' WHERE wg_users.username ='".$arr."'";
					$db->setQuery($sql);
					$query=NULL;
					$db->loadObject($query);
					if($query->username=='')
					{
						updateUserBanned($query->id);	
						$endDate =date("Y-m-d H:i:s",time()+($_POST['date']*24*60*60));		
						$sql = "INSERT INTO wg_user_bans(username, user_id, ban_date, ban_time, end_date, reason) VALUES('".$arr."',".$query->id.",'".date("Y-m-d H:i:s")."',".$_POST['date'].",'".$endDate."','".$_POST['reason']."')";
						$db->setQuery($sql);
						$db->query();
						
						//send message to user bi ban		
						$sql ="INSERT INTO wg_messages(id_user, from_id, to_id, times, status, subject, content)  				   VALUES(".$query->id.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'Tài khoản của bạn đã bị khóa','".$reason."')";
						$db->setQuery($sql);
						$db->query();	
						
						$sql = "DELETE FROM wg_bad_list WHERE username = '".$arr."'";
						$db->setQuery($sql);
						$db->query();									
					}
				}
				
				header("Location:bad_list.php");			
			}		
			break;	
		default:		
			define('MAXROW',20);
			//START: get for paging
			if(empty($_GET['page'])||$_GET['page']==1 || !is_numeric($_GET['page'])){
				$x=0;
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");
			}
			//END: get for paging
			$where='';
			$sqlSum = "SELECT COUNT(DISTINCT(id)) FROM wg_bad_list ".$where;
			$db->setQuery($sqlSum);
			$sumPlayer=(int)$db->loadResult();		
			$parse['total_record']= $sumPlayer;
			$parse['total_page']=ceil($sumPlayer/constant("MAXROW"));
			
			$sql="SELECT tb1.*,tb2.id as user_id FROM wg_bad_list as tb1,wg_users as tb2 WHERE tb1.username=tb2.username ORDER BY tb1.time DESC LIMIT ".$x.",".constant("MAXROW")."";
			$db->setQuery($sql);
			$elements=NULL;
			$elements=$db->loadObjectList();
			$no=0;
			//START: parse list of user baned
			if($elements)
			{
				$no=$x+1;												
				foreach($elements as $info)
				{
					$parse1['no']=$no;				
					$parse1['username']=$info->username;										
					$parse1['reason']=substr ($info->reason,0,100).'...';	
					$parse1['title']=$info->reason;					
					$parse1['time']=$info->time;	
					$no++;
					$users_list.= parsetemplate (gettemplate('/admin/bad_list_row'), $parse1 );						
				}
				$parse['view_users_list']=$users_list;					
				//START: paging
				$a="'bad_list.php?page='+this.options[this.selectedIndex].value";
				$b="'_top'";
				$string='onchange="javascript:window.open('.$a.','.$b.')"';
				$parse['pagenumber']=''.$lang['40'].' <select name="page" style="width:40px;height=40px;" '.$string.'>';
				for($i=1;$i<=ceil($sumPlayer/constant("MAXROW"));$i++)
				{
					$parse['pagenumber'].='<option value="'.$i.'"';
					if(empty($_GET["page"]) && $i==$p+1) {
						$parse['pagenumber'].=' selected="selected">';
					}elseif($_GET["page"]==$i)	{
						$parse['pagenumber'].=' selected="selected">';
					}else	{
						$parse['pagenumber'].='>';
					}						
					$parse['pagenumber'].=''.$i.'</option>';
				}
				$parse['pagenumber'].='</select>';	
				//END: paging
			}
			else {
				$parse['pagenumber']='';
				$parse['view_users_list']=parsetemplate(gettemplate('/admin/bad_list_null'),$parse);
			}
			$page = parsetemplate(gettemplate('/admin/bad_list'), $parse);
			displayAdmin($page,$lang['userlist']);
		break;
	}
}
?>