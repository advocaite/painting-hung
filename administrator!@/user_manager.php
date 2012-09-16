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
include($ugamela_root_path . 'includes/function_profile.php');
require_once($ugamela_root_path . 'soap/call.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel'] < 5)
{
	header("Location:index.php");
	exit();
}
else
{	
	includeLang('admin_user_manager');
	global $user, $lang, $db;
	$parse = $lang;	
	define('MAXROW',10); // 10 row per page	
	if(isset($_GET['username']) && isset($_GET['authlevel']))
	{
		$sql ="UPDATE  wg_admin SET authlevel=".$_GET['authlevel']." WHERE username='".$_GET['username']."'";
		$db->setQuery($sql);
		$db->query();
	}	
	if(count($_POST)<>0)
	{
		$sql ="INSERT INTO `wg_admin` (`username`, `pass`, `authlevel`)
		 VALUES ('".$_POST['username']."', '".md5($_POST['username'])."',".$_POST['authlevel'].")";
		$db->setQuery($sql);
		if(!$db->query())
		{
			header("Location:user_manager.php?error=".$_POST['username']."");
		}
	}
	$parse['error']='';
	if(isset($_GET['error']))
	{
		$parse['error']=$_GET['error'].' '.$lang['not_exist'];
	}
	
	//START: get for paging
	if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
		$x=0;			
	}else{
		$x=($_GET["page"]-1)*constant("MAXROW");			
	}
	//END: get for paging
	$sql="SELECT id,username,authlevel FROM wg_admin WHERE authlevel>=1 ORDER BY authlevel DESC";
	$db->setQuery($sql);
	$wg_users=NULL;
	$wg_users=$db->loadObjectList();			
	$total=count($wg_users);
	$parse['total_page']=ceil($total/constant("MAXROW"));
	if($wg_users)
	{
		$no =$x+1;			
		foreach ($wg_users as $key=>$element)
		{
			if($key >=$x  && $key <  $x+constant("MAXROW"))
			{
				$parse['no']=$no;
				$parse['username']=$element->username;	
				$parse['email']=get_email_remote($element->username);
				$authlevel_row=NULL;
				for($i=0;$i<=5;$i++)
				{
					if($element->authlevel==$i)
					{
						$authlevel_row.='<option value="'.$i.'" selected="selected">'.$i.'</option>';
					}
					else
					{
						$authlevel_row.='<option value="'.$i.'">'.$i.'</option>';
					}
				}
				$parse['authlevel']=$authlevel_row;
				$users_list .= parsetemplate (gettemplate('/admin/user_manager_row'), $parse );
				$no++;
			}
			$parse['view_users_list']=$users_list;				
			$parse['pagenumber']= paging('user_manager.php?', $total, constant("MAXROW"));		
		}
	}
	else
	{
		$parse['view_users_list']=parsetemplate (gettemplate('/admin/user_manager_null'), $parse );
		$parse['pagenumber']='';
	}		
	$page = parsetemplate(gettemplate("/admin/user_manager"), $parse);
	displayAdmin($page,$lang['userlist']);
}	
?>