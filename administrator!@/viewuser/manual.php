<?php
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.'.$phpEx);
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/func_security.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_allian.'.$phpEx);
require_once($ugamela_root_path . 'includes/wg_mission.'.$phpEx);
checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
includeLang('quest');
global $lang,$user,$wg_mission;
$parse=$lang;
/*
* @Author: duc hien
* @Des: cap nhat cho quest cua nguoi choi
* @param: $userid= id cua nguoi choi
* @return: update SQl
*/
function Mission2($village,$view,$quest,$userid)
{
	$a=$quest;
	$b=round($quest);
	if($a==$b && $quest<=19)
	{
		return UpdateMission2($village,$userid,$quest,0.5);
	}
	else
	{
		return UpdateMission1($view,$userid,0);
	}	
}
if(isset($_GET['act']))
{
	$page = parsetemplate(gettemplate('manual1'),$parse); 
	display2($page,'');
}
/*-----------------------------------------------------------------------------------------------------------------------------*/
/*$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_mission";
$db->setQuery($sql);
$count = (int)$db->loadResult();*/
$count=count($wg_mission);
if(!empty($_GET['id']) && is_numeric($_GET['id']))
{
	$tmpId=$_GET['id'];
	$id=round($_GET['id']);
	if($user['quest']<=$count)
	{
		if(count($_POST)<>0)
		{
			$view=1;
			if(isset($_POST['rank']))
			{
				if($_POST['rank']==$_SESSION['rank_quest_7'])
				{
					UpdateMission1(1,$userid,0);
				}
				else
				{
					$view=0;
				}
			}
			Mission2($user['villages_id'],$view,$user['quest'],$user['id']);
			header("Location:manual.php?act=1");
		}
		elseif($user['view']==0)
		{
			Mission2($user['villages_id'],0,$user['quest'],$user['id']);
		}
	}
	else
	{
		$id=1;
	}
	/*-----------------------------------------------------------------*/	
	if($_GET['id']!=1 && $tmpId==$id)
	{
		$parse['content']=$lang['reward'.$id.''];
	}
	else
	{
		if($id==1 ||$user['view']==1)
		{
			$parse['content']=$lang['mission'.$id.''];
		}
		elseif($user['quest']==6.5)
		{
			$parse['content']=$lang['mission7'];
		}
		else
		{
			$parse['content']='<!--<form action="" method="post" ondblclick="ClosePopUp();">-->
			'.$lang['mission'.$id.''].'<center>
<input type="hidden" name="" id="" style="visibility:hidden;"/>
<input type="image" src="images/en/b/ok1.gif" value="" tabindex="3"></center>
</form>';
		}
	}
	$page = parsetemplate(gettemplate('manual'),$parse); 
	display2($page,'');
}
ob_end_flush();

?>

