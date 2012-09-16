<?php
/*
	Plugin Name: status.php
	Plugin URI: http://asuwa.net/administrator/status.php
	Description: 
	+ Hien thi danh sach nhung status da thuc hien xong
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('status');
	$parse = $lang;
	
	if(isset($_GET['s']))
	{
		$task = $_GET['s'];
	}
	switch($task){
		
		//START: hien thi danh sach trong table wg_status_backup
		case "sbk":
			define('MAXROW',15); // 10 row per page		
			//START: get for paging
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
				$x=0;
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");
			}
			//END: get for paging
			$dk='';$dk1='';$key='';$parse['value_ch']='';$parse['checked']='';$parse['link']='status.php?s=move_status';
			if(isset($_GET['keyword']) && isset($_GET['Time']))
			{
				$dk="WHERE village_id=".$_GET['keyword']." AND time_begin LIKE '%".date("Y-m-d")."%'";
				$dk1="WHERE village_id=".$_GET['keyword']." AND time_begin LIKE '%".date("Y-m-d")."%'";			
				$key='keyword='.$_GET['keyword'].'&Time=ToDay&';
				$parse['value_ch']=$_GET['keyword'];
				$parse['checked']='checked="checked"';
				$parse['link']='status.php?s=delete_backup&keyword='.$_GET['keyword'].'&Time=ToDay';
			}
			elseif(isset($_GET['keyword']) && isset($_GET['SearchTime']))
			{
				$day = explode("-",$_GET['SearchTime']);
				$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);			
				$dk="WHERE village_id=".$_GET['keyword']." AND time_begin LIKE '%".$char."%'";
				$dk1="village_id=".$_GET['keyword']." AND time_begin LIKE '%".$char."%' AND";			
				$key='keyword='.$_GET['keyword'].'&SearchTime='.$_GET['SearchTime'].'&';
				$parse['value_ch']=$_GET['keyword'];
				$parse['link']='status.php?s=delete_backup&keyword='.$_GET['keyword'].'&SearchTime='.$_GET['SearchTime'];
			}
			elseif(isset($_GET['SearchTime']))
			{
				$day = explode("-",$_GET['SearchTime']);
				$key='SearchTime='.$_GET['SearchTime'].'&';
				$dk="WHERE time_begin LIKE '%".sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0])."%'";
				$dk1=$dk;
				$parse['link']='status.php?s=delete_backup&SearchTime='.$_GET['SearchTime'];	
			}
			elseif(isset($_GET['Time']))
			{			
				$parse['checked']='checked="checked"';
				$dk="WHERE time_begin LIKE '%".date("Y-m-d")."%'";
				$dk1=$dk;
				$key='Time=ToDay&';
				$parse['link']='status.php?s=delete_backup&Time=ToDay';
			}
			elseif(isset($_GET['keyword']))
			{
				$dk="WHERE village_id=".$_GET['keyword']."";
				$dk1=$dk;			
				$parse['value_ch']=$_GET['keyword'];
				$key='keyword='.$_GET['keyword'].'&';
				$parse['link']='status.php?s=delete_backup&keyword='.$_GET['keyword'];
			}			
			$sqlSum = "SELECT COUNT(DISTINCT(id)) FROM wg_status_backup ".$dk1;
			$db->setQuery($sqlSum);		
			$sum_status=(int)$db->loadResult();		
			$parse['total_record']=$sum_status;		
			$parse['total_page']=ceil($sum_status/constant("MAXROW"));
			$sql="SELECT * FROM wg_status_backup ".$dk." ORDER BY time_begin DESC LIMIT ".$x.",".constant("MAXROW")."";
			$db->setQuery($sql);
			$elements=$db->loadObjectList();
			//START: parse list of status
			if($elements)
			{
				$no =$x+1;
				foreach ($elements as $element)
				{
					$parse['no']=$no;
					$parse['id']=$element->village_id;
					$parse['object_id'] =$element->object_id;
					$parse['village_name'] =$element->village_id;
					$parse['type'] = $element->type;
					$parse['time_begin']=$element->time_begin;						
					$parse['time_end']=$element->time_end;
					$parse['cost_time']=$element->cost_time;						
					$parse['status']=$element->status;
					$parse['order']=$element->order_;											
					$parse['level']=$element->level;
					$users_list .= parsetemplate (gettemplate('/admin/status_list_row'), $parse );
					$no++;
				}	
				$parse['view_users_list']=$users_list;	
				//START: paging
				$a="'status.php?s=sbk&{$key}page='+this.options[this.selectedIndex].value";
				$b="'_top'";
				$string='onchange="javscript:window.open('.$a.','.$b.')"';
				$parse['pagenumber']=''.$lang['40'].' <select name="page" style="width:80px;height=40px;" '.$string.'>';
				for($i=1;$i<=ceil($sum_status/constant("MAXROW"));$i++){
					$parse['pagenumber'].='<option value="'.$i.'"';
					if(empty($_GET["page"]) && $i==$p+1){
						$parse['pagenumber'].='selected="selected">';
					}elseif($_GET["page"]==$i){
						$parse['pagenumber'].='selected="selected">';
					}else{
						$parse['pagenumber'].='>';
					}						
					$parse['pagenumber'].=''.$i.'</option>';
				}
				$parse['pagenumber'].='</select>';	
				//END: paging
			}else{
				$parse['pagenumber']='';
				$parse['view_users_list']=parsetemplate(gettemplate('/admin/status_null'),$parse);						
			}
			$parse['option_day']=returnInfoDaySecurityAdmin(1,31,$day[0]);
			$parse['option_month']=returnInfoDaySecurityAdmin(1,12,$day[1]);
			$parse['option_year']=returnInfoDaySecurityAdmin(2009,2010,$day[2]);
			$page = parsetemplate(gettemplate("/admin/status_backup_list"), $parse);
			displayAdmin($page,$lang['status']);		
		
		break;
		//START: move record in table wg_status to table wg_status_backup
		case "move_status":
			$dk="WHERE status=1";
			$key='status.php';	
			if(isset($_GET['keyword']) && isset($_GET['Time']))
			{
				$dk="WHERE village_id=".$_GET['keyword']." AND time_begin LIKE '%".date("Y-m-d")."%' AND status=1";
				$key='status.php?keyword='.$_GET['keyword'].'&Time=ToDay';
			}
			elseif(isset($_GET['keyword']) && isset($_GET['SearchTime']))
			{
				$day = explode("-",$_GET['SearchTime']);
				$dk="WHERE village_id=".$_GET['keyword']." AND time_begin LIKE '%".sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0])."%' AND status=1";	
				$key='status.php?keyword='.$_GET['keyword'].'&SearchTime='.$_GET['SearchTime'];	
			}
			elseif(isset($_GET['SearchTime']))
			{
				$day = explode("-",$_GET['SearchTime']);
				$dk="WHERE time_begin LIKE '%".sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0])."%' AND status=1";
				$key='status.php?SearchTime='.$_GET['SearchTime'];
			}
			elseif(isset($_GET['Time']))
			{			
				$dk="WHERE time_begin LIKE '%".date("Y-m-d")."%' AND status=1";
				$key='status.php?Time=ToDay';
			}
			elseif(isset($_GET['keyword']))
			{
				$dk="WHERE village_id=".$_GET['keyword']." AND status=1";		
				$key='status.php?keyword='.$_GET['keyword'];	
			}	
			$sql = "SELECT * FROM wg_status ".$dk." ORDER BY id ASC LIMIT 0,3000";
			$db->setQuery($sql);
			$listStatus = null;
			$listStatus = $db->loadObjectList();
			if ($listStatus)
			{
				foreach($listStatus as $row)
				{
					//insert into wg_status_backup
					$sql = "INSERT INTO wg_status_backup(`id`,`object_id`, `village_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`, `level`) VALUES(".$row->id.",".$row->object_id.", ".$row->village_id.", ".$row->type.", '".$row->time_begin."', '".$row->time_end."', ".$row->cost_time.", ".$row->status.", ".$row->order_.", ".$row->level.")";		
					$db->setQuery($sql);
					if($db->query())
					{
						$sql = "DELETE FROM wg_status WHERE id = ".$row->id;
						$db->setQuery($sql);
						$db->query();
					}				
				}				
			}
			header("Location:$key");	
			exit();		
		break;
		//END: move record in table wg_status to table wg_status_backup
		
		//START: hien thi danh sach trong table wg_status voi status = 1
		default:
			define('MAXROW',15); // 10 row per page		
			//START: get for paging
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
				$x=0;
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");
			}
			//END: get for paging
			$dk='';$dk1='';$dk2='';$key='';$parse['value_ch']='';$parse['checked']='';$parse['link']='status.php?s=move_status';
			if(isset($_GET['keyword']) && isset($_GET['Time']))
			{
				$dk="WHERE village_id=".$_GET['keyword']." AND time_begin LIKE '%".date("Y-m-d")."%'";
				$dk1="village_id=".$_GET['keyword']." AND time_begin LIKE '%".date("Y-m-d")."%' AND";
				$dk2=$dk1;
				$key='keyword='.$_GET['keyword'].'&Time=ToDay&';
				$parse['value_ch']=$_GET['keyword'];
				$parse['checked']='checked="checked"';
				$parse['link']='status.php?s=move_status&keyword='.$_GET['keyword'].'&Time=ToDay';
			}
			elseif(isset($_GET['keyword']) && isset($_GET['SearchTime']))
			{
				$day = explode("-",$_GET['SearchTime']);
				$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);			
				$dk="WHERE village_id=".$_GET['keyword']." AND time_begin LIKE '%".$char."%'";
				$dk1="village_id=".$_GET['keyword']." AND time_begin LIKE '%".$char."%' AND";
				$dk2=$dk1;
				$key='keyword='.$_GET['keyword'].'&SearchTime='.$_GET['SearchTime'].'&';
				$parse['value_ch']=$_GET['keyword'];
				$parse['link']='status.php?s=move_status&keyword='.$_GET['keyword'].'&SearchTime='.$_GET['SearchTime'];
			}
			elseif(isset($_GET['SearchTime']))
			{
				$day = explode("-",$_GET['SearchTime']);
				$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
				$key='SearchTime='.$_GET['SearchTime'].'&';
				$dk="WHERE time_begin LIKE '%".$char."%'";
				$dk1="time_begin LIKE '%".$char."%' AND";
				$dk2=$dk1;
				$parse['link']='status.php?s=move_status&SearchTime='.$_GET['SearchTime'];
			}
			elseif(isset($_GET['Time']))
			{			
				$parse['checked']='checked="checked"';
				$dk="WHERE time_begin LIKE '%".date("Y-m-d")."%'";
				$dk1="time_begin LIKE '%".date("Y-m-d")."%' AND";
				$dk2=$dk1;
				$key='Time=ToDay&';
				$parse['link']='status.php?s=move_status&Time=ToDay';
			}
			elseif(isset($_GET['keyword']))
			{
				$dk="WHERE village_id=".$_GET['keyword']."";
				$dk1="village_id=".$_GET['keyword']." AND";
				$dk2=$dk1;
				$parse['value_ch']=$_GET['keyword'];
				$key='keyword='.$_GET['keyword'].'&';
				$parse['link']='status.php?s=move_status&keyword='.$_GET['keyword'];
			}	
			$sqlSum = "SELECT COUNT(DISTINCT(id)) FROM wg_status WHERE ".$dk1."  status=1";
			$db->setQuery($sqlSum);
			$status1=(int)$db->loadResult();
			$parse['status1']=$status1;
			$parse['backup_status']=$status1;
			if($status1>3000)
			{
				$parse['backup_status']=3000;
			}		
			$sqlSum = "SELECT COUNT(DISTINCT(id)) FROM wg_status WHERE ".$dk2."  status=0";
			$db->setQuery($sqlSum);
			$status2=(int)$db->loadResult();
			$parse['status2']=$status2;
			
			$sum_status=$status1+$status2;
			$parse['total_record']=$sum_status;	
			$parse['total_page']=ceil($sum_status/constant("MAXROW"));
			$sql="SELECT * FROM wg_status ".$dk." ORDER BY time_begin DESC LIMIT ".$x.",".constant("MAXROW")."";
			$db->setQuery($sql);
			$elements=$db->loadObjectList();
			//START: parse list of status
			if($elements)
			{
				$no =$x+1;
				foreach ($elements as $element)
				{
					$parse['no']=$no;
					$parse['id']=$element->village_id;
					$parse['object_id'] =$element->object_id;
					$parse['village_name'] =$element->village_id;
					$parse['type'] = $element->type;
					$parse['time_begin']=$element->time_begin;						
					$parse['time_end']=$element->time_end;
					$parse['cost_time']=$element->cost_time;						
					$parse['status']=$element->status;
					$parse['order']=$element->order_;											
					$parse['level']=$element->level;
					$users_list .= parsetemplate (gettemplate('/admin/status_list_row'), $parse );
					$no++;
				}	
				$parse['view_users_list']=$users_list;	
				//START: paging
				$a="'status.php?{$key}page='+this.options[this.selectedIndex].value";
				$b="'_top'";
				$string='onchange="javscript:window.open('.$a.','.$b.')"';
				$parse['pagenumber']=''.$lang['40'].' <select name="page" style="width:80px;height=40px;" '.$string.'>';
				for($i=1;$i<=ceil($sum_status/constant("MAXROW"));$i++){
					$parse['pagenumber'].='<option value="'.$i.'"';
					if(empty($_GET["page"]) && $i==$p+1){
						$parse['pagenumber'].='selected="selected">';
					}elseif($_GET["page"]==$i){
						$parse['pagenumber'].='selected="selected">';
					}else{
						$parse['pagenumber'].='>';
					}						
					$parse['pagenumber'].=''.$i.'</option>';
				}
				$parse['pagenumber'].='</select>';	
				//END: paging
			}else{
				$parse['pagenumber']='';
				$parse['view_users_list']=parsetemplate(gettemplate('/admin/status_null'),$parse);						
			}
			$parse['option_day']=returnInfoDaySecurityAdmin(1,31,$day[0]);
			$parse['option_month']=returnInfoDaySecurityAdmin(1,12,$day[1]);
			$parse['option_year']=returnInfoDaySecurityAdmin(2009,2010,$day[2]);
			$page = parsetemplate(gettemplate("/admin/status_list"), $parse);
			displayAdmin($page,$lang['status']);		
		break;		
		//END: hien thi danh sach trong table wg_status voi status = 1
	}
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