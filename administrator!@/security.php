<?php
/*
	Plugin Name: security.php
	Plugin URI: http://asuwa.net/administrator/security.php
	Description: 
	+ Hien thi danh sach tat ca thong tin trong table wg_security
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);
ob_start();
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include('includes/function_paging.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('security');
	global $db, $lang;
	$parse = $lang;
	define('MAXROW',15);
	$sql_="SELECT COUNT(DISTINCT(id)) FROM  wg_securities";
	
	$parse['value_ch']='';
	$parse['checked']='';
	//START: get for paging
	if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"]))
	{
		$x=0;
	}
	else
	{
		$x=($_GET["page"]-1)*constant("MAXROW");
	}
	$sql="SELECT * FROM  wg_securities ORDER BY `time` DESC LIMIT " . $x . "," . constant ( "MAXROW" ) . "";
	//START: sort by
	if(isset($_GET['keyword']) && isset($_GET['Time']))
	{
		$sql_="SELECT COUNT(DISTINCT(id)) FROM  wg_securities WHERE username LIKE '%".$_GET['keyword']."%'  AND time LIKE '%".date("Y-m-d")."%'";
		$sql="SELECT * FROM  wg_securities WHERE username LIKE '%".$_GET['keyword']."%'  AND time LIKE '%".date("Y-m-d")."%' ORDER BY `time` DESC LIMIT " . $x . "," . constant ( "MAXROW" ) . "";
		$keyword='keyword='.$_GET['keyword'].'&Time=ToDay';
		$parse['value_ch']=$_GET['keyword'];
		$parse['checked']='checked="checked"';
	}
	elseif(isset($_GET['keyword']) && isset($_GET['SearchTime']))
	{
		$day = explode("-",$_GET['SearchTime']);
		$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
		$sql_="SELECT COUNT(DISTINCT(id)) FROM  wg_securities WHERE username LIKE '%".$_GET['keyword']."%'  AND time LIKE '%".$char."%'";
		$sql="SELECT * FROM  wg_securities WHERE username LIKE '%".$_GET['keyword']."%'  AND time LIKE '%".$char."%' ORDER BY `time` DESC LIMIT " . $x . "," . constant ( "MAXROW" ) . "";
		$keyword='keyword='.$_GET['keyword'].'&SearchTime='.$_GET['SearchTime'];
		$parse['value_ch']=$_GET['keyword'];
	}
	elseif(isset($_GET['SearchTime']))
	{
		$day = explode("-",$_GET['SearchTime']);
		$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
		$sql_="SELECT COUNT(DISTINCT(id)) FROM  wg_securities WHERE time LIKE '%".$char."%'";
		$sql="SELECT * FROM  wg_securities WHERE time LIKE '%".$char."%' ORDER BY `time` DESC LIMIT " . $x . "," . constant ( "MAXROW" ) . "";
		$keyword='SearchTime='.$_GET['SearchTime'];
	}
	elseif(isset($_GET['Time']))
	{
		$sql_="SELECT COUNT(DISTINCT(id)) FROM  wg_securities WHERE time LIKE '%".date("Y-m-d")."%'";
		$sql="SELECT * FROM  wg_securities WHERE time LIKE '%".date("Y-m-d")."%' ORDER BY `time` DESC LIMIT " . $x . "," . constant ( "MAXROW" ) . "";
		$parse['checked']='checked="checked"';
		$keyword='Time=ToDay';
	}
	elseif(isset($_GET['keyword']))
	{	
		$sql_="SELECT COUNT(DISTINCT(id)) FROM  wg_securities WHERE username LIKE '%".$_GET['keyword']."%'";
		$sql="SELECT * FROM  wg_securities WHERE username LIKE '%".$_GET['keyword']."%' ORDER BY `time` DESC LIMIT " . $x . "," . constant ( "MAXROW" ) . "";
		$parse['value_ch']=$_GET['keyword'];
		$keyword='keyword='.$_GET['keyword'];
	}
	$db->setQuery($sql_);
	$totalRecord =(int)$db->LoadResult();
	
	//END: get for paging
	
	//START: total record
	
	$db->setQuery($sql);
	$elements=NULL;
	$elements = $db->loadObjectList();
	
	
	$parse['total_record']= "<b>".$lang['Total record'].": ".$totalRecord."</b>";
	$totalPage = ceil($totalRecord / constant("MAXROW"));
	$parse['total_page'] = $totalPage;
	//END: total record
		
	
	//START: parse list of security
	if($elements)
	{
		$no =$x+ 1;
		foreach ($elements as $key=>$element)
		{		
			$parse['no'] = $no;		
			$parse['id_row'] = $element->id;
			$parse['username_row'] = $element->username;
			$parse['ip_row'] = $element->ip;
			$parse['feature_row'] = $element->feature;
			$parse['time_row'] =substr ( $element->time, 10 ) . '&nbsp;&nbsp;&nbsp;' . substr ( $element->time, 8, 2 ) . '-' . substr ( $element->time, 5, 2 ) . '-' . substr ( $element->time, 0, 4 );		
			$list .= parsetemplate(gettemplate('admin/security_body_row'), $parse);
			$no++;		
		}	
		$parse['list'] = $list;	
		//phan trang	
		$parse['pagenumber']= paging('security.php?'.$keyword.'', $totalRecord, constant("MAXROW"));
		
	}	
	else
	{
		$parse['list'] ='';
		$parse['pagenumber']='';
	}
	$parse['option_day']=returnInfoDaySecurityAdmin(1,31,$day[0]);
	$parse['option_month']=returnInfoDaySecurityAdmin(1,12,$day[1]);
	$parse['option_year']=returnInfoDaySecurityAdmin(2009,2010,$day[2]);	
	//END: parse list of security
	$page = parsetemplate(gettemplate('admin/security_body'), $parse);
	displayAdmin($page,$lang['security']);
	ob_end_flush();
}
function returnInfoDaySecurityAdmin($min,$max,$temp)
{
	$string=NULL;
	for($i=$min;$i<=$max;$i++)
	{
		if($temp==$i)
		{
			$string.='<option value="'.$i.'" selected="selected">'.$i.'</option>';
		}
		else
		{
			$string.='<option value="'.$i.'" >'.$i.'</option>';
		}
	}
	return $string;
}
?>
