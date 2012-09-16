<?php
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.php'); 
include('includes/common.'.$phpEx);
include($ugamela_root_path.'includes/func_security.'.$phpEx);


if(!check_user()){ header("Location: login.php"); }
global $db, $user;
$parse = $lang;
function Admin_update_rank()
{
	global $db;
	$sql="SELECT id,population FROM wg_users ORDER BY population DESC";
	$db->setQuery($sql);
	$wg_users=$db->loadObjectList();
	$count=0;
	if($wg_users)
	{
		foreach($wg_users as $key=>$value)
		{
			$sql="UPDATE wg_users SET rank=".($key+1)." WHERE id=".$value->id;
			$db->setQuery($sql);
			if($db->query())
			{
				$count++;
			}
			else
			{
				globalError2('Admin tool cap nhat hang error'.$sql);
			}
		}	
	}
	if($count==count($wg_users))
	{
		return true;
	}
	return false;
}
$parse['error']='';
if($_POST)
{
	$subject = $_POST['txt_subject'];
	$content = $_POST['txt_content'];
	$group = $_POST['group'];
	$allies=$_POST['allies'];
	$member=$_POST['member'];	
	/* goi thu theo nhom thanh vien */
	if($_POST['sent_to']==1)
	{
		if($group==0) //goi thu cho tat ca thanh vien
		{
			$sql = "SELECT id FROM wg_users ORDER BY id  ASC";
		}
		if($group==1) //100 user xếp hạng cao nhất
		{
			Admin_update_rank();			
			$sql = "SELECT id FROM wg_users ORDER BY rank  ASC LIMIT 0,100";			
		}
		if($group==2) //100 user xếp hạng thap nhat
		{
			Admin_update_rank();			
			$sql = "SELECT id FROM wg_users ORDER BY rank  DESC LIMIT 0,100";			
		}
		if($group==3)
		{
			$sql="SELECT id FROM `wg_users` WHERE `active_time` != '0000-00-00 00:00:00' ORDER BY `active_time` DESC LIMIT 0,100"; 
		}
	}
	elseif($_POST['sent_to']==2)
	{
		if($allies==0)  // goi cho tat ca lien minh
		{
			$sql = "SELECT id FROM wg_users WHERE alliance_id >0 ORDER BY id  ASC";			
		}
		else  // goi cho lien minh duoc chon
		{
			$sql = "SELECT id FROM wg_users WHERE alliance_id=".$allies." ORDER BY id  ASC";			
		}
	}
	elseif($_POST['sent_to']==3) // goi thu den cho 1 thanh vien bat ky
	{
		$sql = "SELECT id FROM wg_users WHERE username='".$member."'";		
	}	
	$db->setQuery($sql);
	$listUsers = NULL;
	$listUsers = $db->loadObjectList();
	if($listUsers)
	{
		foreach($listUsers as $key)
		{
			$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES(".$key->id.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'".$db->getEscaped($subject)."','".$db->getEscaped($content)."')";
			$db->setQuery($sql);
			if(!$db->query())
			{
				globalError2($user['username'].' dung Admin Tool goi thu error:'.$sql);
			}
		}
	}
	else
	{
		if($_POST['sent_to']==3)
		{
			$parse['error']='Tên thành viên không tồn tại';
		}
	}
}

$sql="SELECT id,name FROM wg_allies";
$db->setQuery($sql);
$wg_allies=$wg_allies_row=NULL;
$wg_allies=$db->loadObjectList();
foreach($wg_allies as $row)
{
		$wg_allies_row.='<option value="'.$row->id.'">'.$row->name.'</option>';	
}
$parse['row_alies']=$wg_allies_row;	
$page= parsetemplate(gettemplate('/admin/send_message'),$parse);
displayAdmin($page,$lang['send_mail']);
?>