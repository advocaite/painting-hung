<?php
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
	includeLang('admin_error');
	global $db, $lang;
	$parse = $lang;
	define('MAXROW',15);
	if(isset($_POST['delete_all']))
	{
		$arrs=explode(',',$_POST['list_delete']);
		if(isset($arrs))
		{
			foreach($arrs as $arr)
			{
				$sql = "DELETE FROM wg_error WHERE id=".$arr;
				$db->setQuery ($sql);
				$db->query();	
			}			
		}		
	}
	if(isset($_POST['delete']))
	{
		$arrs=$_POST['checkbox'];
		if(isset($arrs))
		{
			foreach($arrs as $arr)
			{
				$sql = "DELETE FROM wg_error WHERE id=".$arr;
				$db->setQuery ($sql);
				$db->query();	
			}			
		}		
	}
	$sql="SELECT * FROM  wg_error ORDER BY `time` DESC ";
	$parse['value_ch']='';
	$parse['checked']='';
	//START: sort by
	if(isset($_GET['keyword']) && isset($_GET['Time']))
	{
		$sql="SELECT * FROM  wg_error WHERE content LIKE '%".$_GET['keyword']."%'  AND time LIKE '%".date("Y-m-d")."%' ORDER BY `time` DESC ";
		$keyword='keyword='.$_GET['keyword'].'&Time=ToDay';
		$parse['value_ch']=$_GET['keyword'];
		$parse['checked']='checked="checked"';
	}
	elseif(isset($_GET['keyword']) && isset($_GET['SearchTime']))
	{
		$day = explode("-",$_GET['SearchTime']);
		$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
		$sql="SELECT * FROM  wg_error WHERE content LIKE '%".$_GET['keyword']."%'  AND time LIKE '%".$char."%' ORDER BY `time` DESC ";	
		$keyword='keyword='.$_GET['keyword'].'&SearchTime='.$_GET['SearchTime'];
		$parse['value_ch']=$_GET['keyword'];
	}
	elseif(isset($_GET['SearchTime']))
	{
		$day = explode("-",$_GET['SearchTime']);
		$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
		$sql="SELECT * FROM  wg_error WHERE time LIKE '%".$char."%' ORDER BY `time` DESC ";
		$keyword='SearchTime='.$_GET['SearchTime'];
	}
	elseif(isset($_GET['Time']))
	{
		$sql="SELECT * FROM  wg_error WHERE time LIKE '%".date("Y-m-d")."%' ORDER BY `time` DESC ";
		$parse['checked']='checked="checked"';
		$keyword='Time=ToDay';
	}
	elseif(isset($_GET['keyword']))
	{
		
		$sql="SELECT * FROM  wg_error WHERE content LIKE '%".$_GET['keyword']."%' ORDER BY `time` DESC "; 
		$parse['value_ch']=$_GET['keyword'];
		$keyword='keyword='.$_GET['keyword'];
	}
	//START: get for paging
	if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"]))
	{
		$x=0;
	}
	else
	{
		$x=($_GET["page"]-1)*constant("MAXROW");
	}
	//END: get for paging
	
	//START: total record
	
	$db->setQuery($sql);
	$$elements=NULL;
	$elements = $db->loadObjectList();
	
	$totalRecord =count($elements);
	$parse['total_record']=$totalRecord;
	$totalPage = ceil($totalRecord / constant("MAXROW"));
	$parse['total_page'] = $totalPage;
	//END: total record
		
	//START: parse list of security
	$temp=NULL;
	if($elements)
	{
		$no =$x+ 1;
		foreach ($elements as $key=>$element)
		{
			if($key<($totalRecord-1))
			{
				$temp.=$element->id.',';
			}else{
				$temp.=$element->id;
			}
			if($key >=$x  && $key <  $x+constant("MAXROW"))
			{
				$parse['no'] = $no;		
				$parse['id_row'] = $element->id;
				$parse['content_row'] = $element->content;
				$parse['time_row'] =substr ( $element->time, 10 ) . '&nbsp;&nbsp;&nbsp;' . substr ( $element->time, 8, 2 ) . '-' . substr ( $element->time, 5, 2 ) . '-' . substr ( $element->time, 0, 4 );		
				$list .= parsetemplate(gettemplate('admin/error_body_row'), $parse);
				$no++;			
			}
		}
		$parse['list_delete']=$temp;	
		$parse['list'] = $list;	
		//phan trang	
		$parse['pagenumber']= paging('error.php?'.$keyword.'', $totalRecord, constant("MAXROW"));
		
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
	$page = parsetemplate(gettemplate('admin/error_body'), $parse);
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
