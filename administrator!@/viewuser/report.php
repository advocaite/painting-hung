<?php
define('INSIDE', true);
ob_start(); 
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/function_trade.php');
include($ugamela_root_path . 'includes/function_status.php');
require_once($ugamela_root_path . 'includes/function_resource.php');
checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
includeLang('report');

global $wg_village,$db,$wg_buildings,$user;


$village=$_COOKIE['villa_id_cookie'];
$wg_buildings=null;
$wg_village=null;

$wg_village=getVillage($village);
$wg_buildings=getBuildings($village);

getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());

$parse=$lang;
define('MAXROW',20);
if(is_numeric($_GET["id"]))
{
	$sql="SELECT title,time,report_text FROM wg_reports where id='".$_GET["id"]."' AND user_id= ".$user['id']."";
	$db->setQuery($sql);
	$row = null;
	$db->loadObject($row);
	if($row)
	{
			$parse['title_de'] = $row->title;
			$parse['time_de'] = $row->time;	
			$parse['_report_detail'] = $row->report_text;
			$sql="UPDATE wg_reports SET status=1 WHERE id=".$_GET['id']." AND user_id= ".$user['id']."";
			$db->setQuery($sql);
			$db->query();	
			if (isset($_POST["Del"]))
			{
				$sql = "delete from wg_reports where id='".$_GET["id"]."' AND user_id= ".$user['id']."";
				$db->setQuery($sql);
				$db->query();
				header("Location:report.php");			
			}
		}
	else
	{
		header("Location:report.php");
	}
	if($_GET['tab']>0)
	{
		$parse['class'.$_GET['tab'].'']='class="selected"';
	}
	else
	{
		$parse['class']='class="selected"';
	}
	$page = parsetemplate(gettemplate('report_detail'), $parse);
	display($page,$lang['report_detail']);	
}
/*------------------ Xoa Tin --------------------------------------*/
if(!isset($_SESSION['password_viewuser']) && isset($_POST['delete']))
{
	$arrs=$_POST['checkbox'];
	if (isset($arrs))
	{
		foreach($arrs as $arr)
		{
			if(is_numeric($arr))
			{
				$sql = "delete from wg_reports where id='".$arr."' and user_id=".$user["id"]."";
				$db->setQuery($sql);
				$db->query();
			}								
		}			
	}	
}
/*------------------Phan trang --------------------------------------*/
if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"]))
{
	$x=0;
}
else
{
	$x=($_GET["page"]-1)*constant("MAXROW");
}
/*-------------------------------------------------------------------*/
$parse['typeTab']='';
if(empty($_GET['tab']))
{
	$sql="SELECT id,title,status,time FROM wg_reports WHERE  user_id=".$user["id"]." ORDER BY time DESC LIMIT ".$x.",".constant("MAXROW")."";
	$char='?page';
	$sql_sum="SELECT  COUNT(DISTINCT(id)) FROM wg_reports WHERE user_id=".$user["id"]."";
}else //ho tro linh
{
	$type =$_GET['tab'];
	$sql="SELECT id,title,status,time FROM wg_reports WHERE  user_id=".$user["id"]." AND type=$type ORDER BY wg_reports.time DESC LIMIT ".$x.",".constant("MAXROW")."";
	$char="?tab=$type&page";
	$sql_sum="SELECT  COUNT(DISTINCT(id)) FROM wg_reports WHERE user_id=".$user["id"]." AND type=$type";
	$parse['typeTab']='&tab='.$type;
}
$db->setQuery($sql_sum);
$sum=(int)$db->loadResult();
$parse['sum']=$sum;
$parse['REPORT_ATTACK'] = REPORT_ATTACK;
$parse['REPORT_DEFEND'] = REPORT_DEFEND;
$parse['REPORT_TRADE'] = REPORT_TRADE;
/*--------------------------------------------------------------------*/
$db->setQuery($sql);
$elements = $db->loadObjectList();
	if($elements)
	{
		$list='';
		foreach ($elements as $ptu)
		{
			$parse['id']=$ptu->id;
			$parse['title']=$ptu->title;
			$parse['status']='';
			if($ptu->status==0)
			{
				$parse['status']='('.$lang['new'].')';
			}
			$parse['times']=substr($ptu->time,10).' '.substr($ptu->time,8,2).'-'.substr($ptu->time,5,2).'-'.substr($ptu->time,2,2);
			$list.=parsetemplate(gettemplate('report_row'),$parse); 
		}
		$parse['list']=$list;
		/*--------------Phan trang --------------------------------*/
		$a="'report.php".$char."='+this.options[this.selectedIndex].value";
		$b="'_top'";
		$string='onchange="javscript:window.open('.$a.','.$b.')"';
		$parse['pagenumber']=''.$lang['40'].' <select name="page" style="width:40px;height=40px;" '.$string.'>';
		for($i=1;$i<=ceil($sum/constant("MAXROW"));$i++)
		{
			$parse['pagenumber'].='<option value="'.$i.'"';
			if($_GET["page"]==$i)
			{
				$parse['pagenumber'].=' selected="selected">';
			}
			else
			{
				$parse['pagenumber'].='>';
			}						
			$parse['pagenumber'].=''.$i.'</option>';
		}
		$parse['pagenumber'].='</select>';
		/*---------------------------------------------------------------------*/
	}
	else
	{
		$parse['pagenumber']='';
		$parse['list']=parsetemplate(gettemplate('report_null'),$parse); 
	}
if($_GET['tab']>0)
{
	$parse['class'.$_GET['tab'].'']='class="selected"';
}
else
{
	$parse['class']='class="selected"';
}
$page = parsetemplate(gettemplate('report_body'), $parse); 
display($page,$lang['report']);
ob_end_flush();
?>