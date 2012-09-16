<?php
define('INSIDE', true);
ob_start();
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/function_allian.'.$phpEx);

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']!=5){ header("Location: login.php"); }

includeLang('report');
$lang['PHP_SELF'] = 'attack_statistic_detail.'.$phpEx;
$parse = $lang;
$parse['Reports']=$lang['Reports'];
$userId = $_GET['uid'];
$parse['user_id'] = $userId;
$parse['msg'] = "Report statistic of user: ";
$parse['user_name'] = getUserNameByUserId($userId);	
if(is_numeric($_GET["id"]))
{
	$sql="SELECT title,time,report_text FROM wg_reports where id='".$_GET["id"]."' AND user_id= ".$userId."";
	$db->setQuery($sql);
	$row = null;
	$db->loadObject($row);
	if($row)
	{
			$parse['title_de'] = $row->title;
			$parse['time_de'] = $row->time;	
			$parse['_report_detail'] = $row->report_text;
			
			if (isset($_POST["Del"]))
			{
				$sql = "delete from wg_reports where id='".$_GET["id"]."' AND user_id= ".$userId."";
				$db->setQuery($sql);
				$db->query();
				header("Location:attack_statistic.php");			
			}
		}
	else
	{
		header("Location:attack_statistic.php");
	}
	if($_GET['tab']>0)
	{
		$parse['class'.$_GET['tab'].'']='class="selected"';
	}
	else
	{
		$parse['class']='class="selected"';
	}
	$parse['REPORT_ATTACK'] = REPORT_ATTACK;
	$parse['REPORT_DEFEND'] = REPORT_DEFEND;
	$parse['REPORT_TRADE'] = REPORT_TRADE;
	$page = parsetemplate(gettemplate('/admin/attack_statistic_detail'), $parse);
	displayAdmin($page,$lang['report_detail']);	
}
else
{
	header("Location:attack_statistic_detail.php");
}
ob_end_flush();
?>
