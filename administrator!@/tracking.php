<?php
/*
	Plugin Name: tracking.php
	Plugin URI: http://asuwa.net/administrator/tracking.php
	Description: 
	+ Theo doi nguoi choi co vi pham noi quy game hay khong
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ('includes/db_connect.'. $phpEx);
include ('includes/common.'. $phpEx);
include ('includes/function_badlist.'.$phpEx);
include ('includes/function_ban.'.$phpEx);
include ('includes/function_paging.'.$phpEx);
include ('includes/func_security.'.$phpEx);

global $db, $lang, $base_url, $user;
if (! check_user ()) {header ( "Location: login.php" );}
if($user['authlevel']<1)
{
	header("Location:index.php");
	exit();
}
else
{

	includeLang ( 'tracking' );
	
	$parse = $lang;
	$parse ['base_url'] = $base_url;
	
	
	if (isset ( $_GET ['s'] )) {
		$task = $_GET ['s'];
	}
	/* danh sach thanh vien dua vao bad list */
	if(isset($_POST['reason']))
	{
		$arrs=$_POST['checkbox'];
		if(isset($arrs))
		{
			foreach($arrs as $id)
			{
				$sql=" SELECT username FROM wg_users WHERE id=".$id;
				$db->setQuery($sql);
				$username=$db->loadResult();
				$sql ="INSERT INTO `wg_bad_list`(`username`,`reason`,`time`) VALUES ('".$username."','".$db->getEscaped($_POST['reason'])."','".date("Y-m-d H:i:s")."')";
				$db->setQuery($sql);
				$db->query();		
			}	
		}	
	}
	/*
	//cac hang cho type report
	define("REPORT_ATTACK", 1);
	define("REPORT_DEFEND", 2);
	define("REPORT_TRADE", 3);
	define("REPORT_TRADE_RECEIVE", 4);
	define("REPORT_SEND_RARE", 5);
	define("REPORT_MISSION_7", 6);
	*/
	
	switch ($task)
	{
			case "1":// tracking with user
			define ('MAXROW',1);	
			$dk=$link='';
			$parse['value_ch']='';		
			if (isset($_GET['username']))
			{
				$dk="WHERE username LIKE '%".$_GET['username']."%'";
				$parse['value_ch']=$_GET['username'];
				$link='&username='.$_GET['username'];
			}
			//START: get for paging
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
				$x=0;
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");
			}
	
			$sqlSum = "SELECT COUNT(DISTINCT(username)) FROM wg_securities ".$dk;
			$db->setQuery($sqlSum);
			$totalRecord = $db->loadResult();
		
			$parse['total_record']= "<b>".$lang['Total record'].": ".$totalRecord."</b>";
			$totalPage = ceil($totalRecord / constant("MAXROW"));
			$parse['total_page'] = $totalPage;
			
			//END: total record		
			$sql=" SELECT username,COUNT(DISTINCT(ip)) AS sum FROM wg_securities ".$dk." 
			GROUP BY username ORDER BY sum DESC LIMIT ".$x.",".constant("MAXROW")."";
			$db->setQuery($sql);
			$elements=NULL;
			$db->loadObject($elements);		
			//START: parse list of security
			if($elements)
			{	
				$sql = "SELECT ip,count(id) as sum FROM wg_securities WHERE username='".$elements->username."' 
				GROUP BY ip ORDER BY sum DESC";				
				$db->setQuery($sql);
				$infos = NULL;
				$infos = $db->loadObjectList();	
				if($infos)
				{
					$no=1;		
					$parse1 = array();				
					$parse1['id'] = $val['id'];
					$parse1['username_row'] ="<a title=\"Xem tất cả user xài chung IP với tài khoản ".$elements->username."\" href=\"tracking.php?s=4&username=".$elements->username."\"  target=\"_blank\">".$elements->username."</a>";
					$parse1['user_id'] = getUserIdByUserName($elements->username);	
					if(checkBadList($elements->username))
					{
						$parse1['bad_list'] = "<img src=\"../images/un/a/att3.gif\"/>";
					}
					else
					{
						$parse1['bad_list'] = "";
					}		
					foreach($infos as $info)
					{	
						$parse1['no'].=$no.'<br/><hr>';
						$no++;											
						$parse1['ip_row'] .="<a href=#".$info->ip." onclick=\"return PopUpIp('".$info->ip."');\">".$info->ip."</a><br/><hr>";
						$parse1['amount'] .= $info->sum."<br/><hr>";	
						$parse1['detail'] .="<a href=\"#".$elements->username."\" onclick=\"return PopUpUserDetail('".$info->ip."','".$elements->username."');\"><strong>&Phi;</strong></a><br/><hr>";	
					
					}			
					$list .= parsetemplate(gettemplate('admin/tracking_same_pc_user_row'), $parse1);	
				}
				$parse['list'] = $list;
				$parse['pagenumber']=paging('tracking.php?s=1'.$link, $totalRecord,constant("MAXROW"));			
			}
			else
			{
				$parse['list'] ='';
				$parse['pagenumber']='';
			}		
			$page = parsetemplate(gettemplate('/admin/tracking_same_pc_user'), $parse);
			displayAdmin($page,$lang['tracking']);		
		break;
		case "2":
			define ('MAXROW',20);	
			$template='/admin/tracking_report_statistic';	
			$template_row='/admin/tracking_report_statistic_row';
			if(isset($_GET['tab']))
			{				
				if($_GET['tab']==3)
				{
					$template='/admin/tracking_report_statistic1';
					$template_row='/admin/tracking_report_statistic_row1';
				}
				$parse['url']='tracking.php?s=2&tab='.$_GET['tab'];
				if($_GET['tab']==5)
				{
					$dk="AND tb1.type=5";
				}
				else
				{
					$array=array(1=>'tấn công',2=>'tăng viện',3=>'vận chuyển');
					$dk="AND tb1.title LIKE '%".$array[$_GET['tab']]."%'";
				}
				$parse['color'.$_GET['tab'].'']='#0099FF';
				$link='tracking.php?s=2&tab='.$_GET['tab'];
			}
			else
			{
				$parse['url']='tracking.php?s=2';
				$dk='';
				$parse['color']='#0099FF';
				$link='tracking.php?s=2';
			}
			if(isset($_POST['del_report']))
			{
				$arrs=$_POST['checkbox'];
				if (isset($arrs))
				{
					foreach($arrs as $arr)
					{
						$sql = "DELETE FROM wg_reports_bk WHERE id=".$arr;
						$db->setQuery($sql);
						$db->query();			
					}			
				}	
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
			$parse['checked']='';		
			$parse['value_ch']=$parse['checked']='';
			$sqlsum="SELECT COUNT(DISTINCT(tb1.id)) FROM wg_reports_bk AS tb1 WHERE tb1.id >0 ".$dk;
			$sql="SELECT tb1.user_id_2,tb1.id,tb1.user_id,tb1.title,tb1.time,wg_users.username FROM wg_reports_bk AS tb1 
			LEFT JOIN wg_users ON wg_users.id=tb1.user_id 
			WHERE tb1.id >0 ".$dk." ORDER BY tb1.time DESC LIMIT ".$x.",".constant("MAXROW")."";
			if(isset($_GET['keyword']) && isset($_GET['SearchTime']))
			{
				if($_GET['SearchTime']=='ToDay')
				{
					$sqlsum="SELECT count(DISTINCT(tb1.id)) FROM `wg_reports_bk` AS tb1, wg_users AS tb2 
					WHERE tb2.username LIKE '%".$_GET['keyword']."%' AND tb1.user_id = tb2.id 
					AND tb1.time LIKE '%".date("Y-m-d")."%' ".$dk." ORDER BY tb1.time DESC ";
					
					$sql="SELECT tb1.user_id_2,tb1.id,tb1.user_id,tb1.title,tb1.time,tb2.username 
					FROM `wg_reports_bk` AS tb1, wg_users AS tb2 
					WHERE tb2.username LIKE '%".$_GET['keyword']."%' AND tb1.user_id = tb2.id 
					AND tb1.time LIKE '%".date("Y-m-d")."%' ".$dk." ORDER BY tb1.time DESC LIMIT ".$x.",".constant("MAXROW")."";
					
					$parse['checked']='checked="checked"';		
					$link.='&keyword='.$_GET['keyword'].'&SearchTime=ToDay';	
				}
				else
				{
					$day = explode("-",$_GET['SearchTime']);
					$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
					
					$sqlsum="SELECT count(DISTINCT(tb1.id)) FROM `wg_reports_bk` AS tb1, wg_users AS tb2 
					WHERE tb2.username LIKE '%".$_GET['keyword']."%' AND tb1.user_id = tb2.id 
					AND tb1.time LIKE '%".$char."%' ".$dk." ORDER BY tb1.time DESC ";
					
					$sql="SELECT tb1.user_id_2,tb1.id,tb1.user_id,tb1.title,tb1.time,tb2.username 
					FROM `wg_reports_bk` AS tb1, wg_users AS tb2 
					WHERE tb2.username LIKE '%".$_GET['keyword']."%' AND tb1.user_id = tb2.id 
					AND tb1.time LIKE '%".$char."%' ".$dk." ORDER BY tb1.time DESC LIMIT ".$x.",".constant("MAXROW")."";
					$link.='&keyword='.$_GET['keyword'].'&SearchTime='.$_GET['SearchTime'];	
				}
				$parse['value_ch']=$_GET['keyword'];	
			}
			elseif(isset($_GET['SearchTime']))
			{
				if($_GET['SearchTime']=='ToDay')
				{
					$sqlsum="SELECT count(DISTINCT(tb1.id)) FROM wg_reports_bk AS tb1 
					WHERE tb1.time LIKE '%".date("Y-m-d")."%' ".$dk." ORDER BY tb1.time DESC ";
					
					$sql="SELECT tb1.user_id_2,tb1.id,tb1.user_id,tb1.title,tb1.time,wg_users.username FROM wg_reports_bk AS tb1 
					LEFT JOIN wg_users ON wg_users.id=tb1.user_id  
					WHERE tb1.time LIKE '%".date("Y-m-d")."%' ".$dk." ORDER BY tb1.time DESC LIMIT ".$x.",".constant("MAXROW")."";
					
					$parse['checked']='checked="checked"';		
					$link.='&SearchTime=ToDay';	
				}
				else
				{
					$day = explode("-",$_GET['SearchTime']);
					$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
					$sqlsum="SELECT count(DISTINCT(tb1.id)) FROM wg_reports_bk AS tb1 
					WHERE tb1.time LIKE '%".$char."%' ".$dk." ORDER BY tb1.time DESC ";
					
					$sql="SELECT tb1.user_id_2,tb1.id,tb1.user_id,tb1.title,tb1.time,wg_users.username FROM wg_reports_bk AS tb1 
					LEFT JOIN wg_users ON wg_users.id=tb1.user_id  
					WHERE tb1.time LIKE '%".$char."%' ".$dk." ORDER BY tb1.time DESC LIMIT ".$x.",".constant("MAXROW")."";
					$link.='&SearchTime='.$_GET['SearchTime'];	
				}
			}		
			elseif(isset($_GET['keyword']))
			{			
				$sqlsum="SELECT count(DISTINCT(tb1.id)) FROM `wg_reports_bk` AS tb1, wg_users AS tb2 
				WHERE tb2.username LIKE '%".$_GET['keyword']."%' AND tb1.user_id = tb2.id ".$dk." ORDER BY tb1.time DESC ";
				
				$sql="SELECT tb1.user_id_2,tb1.id,tb1.user_id,tb1.title,tb1.time,tb2.username 
				FROM `wg_reports_bk` AS tb1, wg_users AS tb2 
				WHERE tb2.username LIKE '%".$_GET['keyword']."%' AND tb1.user_id = tb2.id ".$dk." 
				ORDER BY tb1.time DESC LIMIT ".$x.",".constant("MAXROW")."";
				
				$parse['value_ch']=$_GET['keyword'];
				$link.='&keyword='.$_GET['keyword'];	
			}	
			
			//END: get for paging	
				
			$db->setQuery($sqlsum);
			$totalRecord=(int)$db->loadResult();
			$parse['total_record']=$totalRecord;
			$parse['total_page']=ceil($totalRecord/constant("MAXROW"));		
			$parse['option_day']=returnInfoDaySecurityAdmin(1,31,$day[0]);
			$parse['option_month']=returnInfoDaySecurityAdmin(1,12,$day[1]);
			$parse['option_year']=returnInfoDaySecurityAdmin(2009,2010,$day[2]);

			$db->setQuery($sql);
			$$reports=NULL;
			$reports = $db->loadObjectList();
			if($reports)
			{
				$list='';
				$no=$x+1;
				foreach ($reports as $report)
				{
					$parse['no']=$no;		
					$parse['id']=$report->id;
					$parse['title']=$report->title;
					if($_GET['tab']==3)
					{
						$sql='SELECT username FROM wg_users WHERE id='.$report->user_id_2;
						$db->setQuery($sql);
						$user_give=$db->loadResult();					
						$parse['user give']=$user_give;
						$sql="SELECT ip FROM `wg_securities` WHERE `username`='".$user_give."' 
						 AND feature='Login' AND time LIKE '%".substr($report->time,0,10)."%' GROUP BY ip ORDER BY `time` ASC";						 
						$db->setQuery($sql);	
						$ip = $db->loadObjectList();
						$ip_give='';
						if($ip)
						{	
							foreach($ip as $v)
							{	
								$temp=substr($report->time,8,2).'-'.substr($report->time,5,2).'-'.substr($report->time,0,4);							
								$ip_give.='<div><a href="tracking.php?s=detail_ip&ip='.$v->ip.'&username='.$user_give.'&SearchTime='.$temp.'" target="_blank">['.$v->ip.']</a></div>';
							}							
						}
						$parse['ip give']=$ip_give;
						$sql="SELECT ip FROM `wg_securities` WHERE `username`='".$report->username."' 
						 AND feature='Login' AND time LIKE '%".substr($report->time,0,10)."%' GROUP BY ip";						 
						$db->setQuery($sql);	
						$ip = $db->loadObjectList();
						$ip_recive='';
						if($ip)
						{	
							foreach($ip as $v)
							{								
								$temp=substr($report->time,8,2).'-'.substr($report->time,5,2).'-'.substr($report->time,0,4);	
								$ip_recive.='<div><a href="tracking.php?s=detail_ip&ip='.$v->ip.'&username='.$report->username.'&SearchTime='.$temp.'" target="_blank">['.$v->ip.']</a></div>';
							}							
						}
						$parse['ip recive']=$ip_recive;
					}
					$parse['user recive']=$report->username;					
					//$parse['times']=substr($report->time,10).' '.substr($report->time,8,2).'-'.substr($report->time,5,2).'-'.substr($report->time,2,2);
					$parse['times']=$report->time;
					$list.=parsetemplate(gettemplate($template_row),$parse); 
					$no++;
				}
				$parse['list']=$list;
				$parse['pagenumber']=paging($link,$totalRecord,constant("MAXROW"));				
			}
			else
			{
				$parse['pagenumber']='';
				$parse['list']=parsetemplate(gettemplate('/admin/tracking_report_statistic_null'),$parse); 
			}
			if($_GET['tab']>0){
				$parse['class'.$_GET['tab'].'']='class="selected"';
			}else{
				$parse['class']='class="selected"';
			}		
			$page = parsetemplate(gettemplate($template), $parse);
			displayAdmin($page,$lang['tracking']);		
		break;
		case "detail_ip":// xem thong tin chi tiet cua IP va User
			define ( 'MAXROW',15);		
			//START: get for paging
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
				$x=0;
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");
			}
			$parse['checked']='';
			if(isset($_GET['ip']) && isset($_GET['username']))
			{
				$ip = $db->getEscaped($_GET['ip']);
				$username = $db->getEscaped($_GET['username']);
				$dk="WHERE username = '".$username."' AND ip = '".$ip."'";
				$link_page=$link='tracking.php?s=detail_ip&ip='.$_GET['ip'].'&username='.$_GET['username'];
				if(isset($_GET['SearchTime']))
				{
					if($_GET['SearchTime']=='ToDay')
					{
						$dk.="AND time LIKE '%".date("Y-m-d")."%'";
						$link_page=$link.'&SearchTime=ToDay';
						$parse['checked']='checked="checked"';
					}
					else
					{			
						$day = explode("-",$_GET['SearchTime']);
						$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
						$dk.="AND time LIKE '%".$char."%'";
						$link_page=$link.'&SearchTime='.$_GET['SearchTime'];
					}
				}			
				$parse['get_username']=$username;
				$parse['get_ip']=$ip;
			}
			$parse['link']=	$link;
			//END: get ip & user
			$sql="SELECT COUNT(DISTINCT(id)) FROM  wg_securities ".$dk;	
			$db->setQuery($sql);
			$totalRecord = $db->loadResult();
			$parse['total_record']= "<b>".$lang['Total record'].": ".$totalRecord."</b>";
			$totalPage = ceil($totalRecord / constant("MAXROW"));
			$parse['total_page'] = $totalPage;
			
			$sql="SELECT feature, time FROM  wg_securities ".$dk." ORDER BY time DESC LIMIT ".$x.",".constant("MAXROW")."";	
			$db->setQuery($sql);
			$elements = $db->loadObjectList();	
			//START: parse list of security
			$parse['option_day']=returnInfoDaySecurityAdmin(1,31,$day[0]);
			$parse['option_month']=returnInfoDaySecurityAdmin(1,12,$day[1]);
			$parse['option_year']=returnInfoDaySecurityAdmin(2009,2010,$day[2]);
			if($elements)
			{
				$no =$x+1;	
				$parse['ip_row'] = $ip;
				$parse['username_row'] = $username;									
				foreach ($elements as $element)
				{
					$parse['no'] = $no;				
					$parse['feature_row'] = $element->feature;					
					$parse['time_row']=substr($element->time,10).' '.substr($element->time,8,2).'-'.substr($element->time,5,2).'-'.substr($element->time,0,4);	
					$list .= parsetemplate(gettemplate('admin/tracking_same_pc_ip_row_detail'), $parse);				
					$no ++;
				}			
				$parse['list'] = $list;			
				$parse['pagenumber']=paging($link_page,$totalRecord,constant("MAXROW"));			
			}
			else
			{
				$parse['list']='';
				$parse['pagenumber']=0;
			}		
			//END: parse list of security		
			
			$page = parsetemplate(gettemplate('/admin/tracking_same_pc_ip_detail'), $parse);
			display2($page,$lang['tracking']);
		break;
		case "3":
			define ( 'MAXROW',20);		
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"]))
			{
				$x=0;
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");
			}
			$parse['value_ch']=$parse['checked']=$dk='';
			$sqlsum="SELECT COUNT(DISTINCT(id_user)) FROM  wg_messages_backup";
			$sql="SELECT id_user, wg_users.username	FROM wg_messages_backup
					LEFT JOIN wg_users ON wg_users.id = wg_messages_backup.id_user
					GROUP BY id_user
					ORDER BY id_user ASC LIMIT ".$x.",".constant("MAXROW")."";
			$link_page='tracking.php?s=3';
			if(isset($_GET['keyword']) && isset($_GET['SearchTime']))
			{
				if($_GET['SearchTime']=='ToDay')
				{
					$sqlsum="SELECT COUNT(DISTINCT(tb1.id_user)) 
					FROM `wg_messages_backup` AS tb1, wg_users AS tb2 
					WHERE tb2.username LIKE '%".$_GET['keyword']."%' AND tb1.times LIKE '%".date("Y-m-d")."%'
					AND tb1.id_user = tb2.id ";
					
					$sql="SELECT id_user, wg_users.username	FROM wg_messages_backup
					LEFT JOIN wg_users ON wg_users.id = wg_messages_backup.id_user
					WHERE username LIKE '%".$_GET['keyword']."%' AND times LIKE '%".date("Y-m-d")."%'
					GROUP BY id_user ORDER BY id_user ASC LIMIT ".$x.",".constant("MAXROW")."";
					
					$dk="AND times LIKE '%".date("Y-m-d")."%'";
					$parse['checked']='checked="checked"';		
					$link_page.='&keyword='.$_GET['keyword'].'&SearchTime=ToDay';				
				}
				else
				{
					$day = explode("-",$_GET['SearchTime']);
					$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
					$sqlsum="SELECT COUNT(DISTINCT(tb1.id_user)) 
					FROM `wg_messages_backup` AS tb1, wg_users AS tb2 
					WHERE tb2.username LIKE '%".$_GET['keyword']."%' AND tb1.times LIKE '%".$char."%'
					AND tb1.id_user = tb2.id ";
					
					$sql="SELECT id_user, wg_users.username	FROM wg_messages_backup
					LEFT JOIN wg_users ON wg_users.id = wg_messages_backup.id_user
					WHERE username LIKE '%".$_GET['keyword']."%' AND times LIKE '%".$char."%'
					GROUP BY id_user ORDER BY id_user ASC LIMIT ".$x.",".constant("MAXROW")."";
					
					$link_page.='&keyword='.$_GET['keyword'].'&SearchTime='.$_GET['SearchTime'];	
					$dk="AND times LIKE '%".$char."%'";
				}
				$parse['value_ch']=$_GET['keyword'];
	
			}
			elseif(isset($_GET['SearchTime']))
			{
				if($_GET['SearchTime']=='ToDay')
				{
					$sqlsum="SELECT COUNT(DISTINCT(id_user)) 
					FROM  wg_messages_backup  WHERE times LIKE '%".date("Y-m-d")."%'";
					
					$sql="SELECT id_user, wg_users.username	FROM wg_messages_backup
					LEFT JOIN wg_users ON wg_users.id = wg_messages_backup.id_user
					WHERE times LIKE '%".date("Y-m-d")."%'
					GROUP BY id_user
					ORDER BY id_user ASC LIMIT ".$x.",".constant("MAXROW")."";
	
					$link_page.='&SearchTime=ToDay';	
					$dk="AND times LIKE '%".date("Y-m-d")."%'";
				}
				else
				{
					$day = explode("-",$_GET['SearchTime']);
					$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
					$sqlsum="SELECT COUNT(DISTINCT(id_user)) 
					FROM  wg_messages_backup  WHERE times LIKE '%".$char."%'";
					
					$sql="SELECT id_user, wg_users.username	FROM wg_messages_backup
					LEFT JOIN wg_users ON wg_users.id = wg_messages_backup.id_user
					WHERE times LIKE '%".$char."%'
					GROUP BY id_user
					ORDER BY id_user ASC LIMIT ".$x.",".constant("MAXROW")."";
	
					$dk="AND times LIKE '%".$char."%' ";				
					
					$link_page.='&SearchTime='.$_GET['SearchTime'];	
				}
			}		
			elseif(isset($_GET['keyword']))
			{			
				$sqlsum="SELECT COUNT(DISTINCT(tb1.id_user)) 
				FROM `wg_messages_backup` AS tb1, wg_users AS tb2 
				WHERE tb2.username LIKE '%".$_GET['keyword']."%' 
				AND tb1.id_user = tb2.id ";
				
				$sql="SELECT tb1.id_user,tb2.username 
				FROM `wg_messages_backup` AS tb1, wg_users AS tb2 
				WHERE tb2.username LIKE '%".$_GET['keyword']."%' 
				AND tb1.id_user = tb2.id GROUP BY tb2.username 
				ORDER BY tb2.username ASC LIMIT ".$x.",".constant("MAXROW")."";
				$parse['value_ch']=$_GET['keyword'];
				$link_page.='&keyword='.$_GET['keyword'];	
			}
			//END: get ip & user
			
			$db->setQuery($sqlsum);
			$totalRecord = $db->loadResult();
			$parse['total_record']= "<b>".$lang['Total record'].": ".$totalRecord."</b>";
			$totalPage = ceil($totalRecord / constant("MAXROW"));
			$parse['total_page'] = $totalPage;
			
			
			$db->setQuery($sql);
			$elements = $db->loadObjectList();
			
			$parse['option_day']=returnInfoDaySecurityAdmin(1,31,$day[0]);
			$parse['option_month']=returnInfoDaySecurityAdmin(1,12,$day[1]);
			$parse['option_year']=returnInfoDaySecurityAdmin(2009,2010,$day[2]);
			if($elements)
			{
				$no =$x+1;											
				foreach ($elements as $element)
				{
					$parse['no'] =$no;
					$parse['username'] ="<a href=\"message.php?tab=1&keyword=".$element->username."\"  target=\"_blank\">".$element->username."</a>";
					if($element->username==NULL)
					{
						$parse['username']="<span style=\"color:#FF0000; font-weight:bolder\">account not exist</span>";
					}
					$sql_inbox="SELECT COUNT(DISTINCT(id)) 
					FROM wg_messages_backup WHERE id_user=".$element->id_user." AND from_id >0 ".$dk;
					$db->setQuery($sql_inbox);
					$parse['inbox']=$db->loadResult();
					$sql_outbox="SELECT COUNT(DISTINCT(id)) 
					FROM wg_messages_backup WHERE id_user=".$element->id_user." AND to_id >0 ".$dk;
					$db->setQuery($sql_outbox);						
					$parse['outbox']=$db->loadResult();						
					$list .= parsetemplate(gettemplate('admin/tracking_message_statistic_row'), $parse);				
					$no ++;
				}			
				$parse['list'] = $list;
				$parse['pagenumber']=paging($link_page,$totalRecord,constant("MAXROW"));			
			}
			else
			{
				$parse['list']='';
				$parse['pagenumber']=0;
			}		
			$page = parsetemplate(gettemplate('/admin/tracking_message_statistic'), $parse);
			displayAdmin($page,$lang['tracking']);
			break;
		case "4":
			define ('MAXROW',1);
			if($_POST['reason_bad']!='')
			{
				$sql ="INSERT INTO `wg_bad_list`(`username`,`reason`,`time`) VALUES ('".$_GET['username']."','".$db->getEscaped($_POST['reason'])."','".date("Y-m-d H:i:s")."')";
				$db->setQuery($sql);
				$db->query();
			}	
			if($_POST['reason']!='')
			{
				$sql="SELECT id FROM wg_users WHERE username='".$_GET['username']."'";
				$db->setQuery($sql);
				$arr=$db->loadResult();
				$endDate =date("Y-m-d H:i:s",time()+($_POST['date']*24*60*60));		
				$sql = "INSERT INTO wg_user_bans(username, user_id, ban_date, ban_time, end_date, reason) 
				VALUES('".$_GET['username']."',".$arr.",'".date("Y-m-d H:i:s")."',
				".$_POST['date'].",'".$endDate."','".$db->getEscaped($_POST['reason'])."')";
				$db->setQuery($sql);
				$db->query();
				
				//send message to user bi ban		
				$sql ="INSERT INTO wg_messages(id_user, from_id, to_id, times, status, subject, content)
				 VALUES(".$arr.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'Tài khoản của bạn đã bị khóa','".$db->getEscaped($_POST['reason'])."')";
				$db->setQuery($sql);
				$db->query();
			}
			$dk=$link='';
			$parse['value_ch']=$parse['button1']=$parse['button2']='';	
			$sql="SELECT username FROM wg_bad_list WHERE username='".$_GET['username']."'";
			$db->setQuery($sql);
			$db->loadObject($wg_bad_list);
			if(!$wg_bad_list)
			{
				$parse['button1']='<input type="button" class="fm" value="Khóa" onclick="popUpReason();"/>';
			}
			$sql="SELECT username FROM wg_user_bans WHERE username='".$_GET['username']."'";
			$db->setQuery($sql);
			$db->loadObject($wg_user_bans);
			if(!$wg_user_bans)
			{
				$parse['button2']='<input type="button" class="fm" value="Theo dõi" onclick="popUpReason1();"/>';
			}	
			if (isset($_GET['username']))
			{
				$dk="WHERE username='".$_GET['username']."'";
				$parse['username']=$parse['value_ch']=$_GET['username'];
				$link='&username='.$_GET['username'];
			}
			$sql="SELECT `ip` , COUNT( ip ) AS sum
					FROM wg_securities
					".$dk."
					GROUP BY ip
					ORDER BY `sum` DESC";
			$db->setQuery($sql);
			$elements=NULL;
			$elements=$db->loadObjectList();		
			//START: parse list of security
			if($elements)
			{	
				$no=1;				
				foreach($elements as $info)
				{	
					$parse['no']=$no;															
					$parse['ip_row']=$info->ip;				
					$sql = "SELECT tb1.username ,COUNT(tb1.username) AS sum,wg_bad_list.username as bad_list,
							(SELECT username FROM wg_user_bans WHERE username=tb1.username) AS ban
							FROM wg_securities AS tb1
							LEFT JOIN wg_bad_list ON wg_bad_list.username=tb1.username
							WHERE tb1.ip LIKE '%".$info->ip."%'
							GROUP BY tb1.username
							ORDER BY sum DESC";
					$db->setQuery($sql);
					$value=$list_user=$amount=$ban=$bad_list=$detail=NULL;
					$value=$db->loadObjectList();
					if($value)
					{					
						foreach($value as $v)
						{
							if($v->bad_list=='')
							{
								$bad_list.='<br><hr>';
							}else{
								$bad_list.='<b style="color: red;">V</b><hr>';
							}
							if($v->ban=='')
							{
								$ban.='<br><hr>';
							}else{
								$ban.='<img src="../images/un/a/att3.gif"><hr>';
							}
							if($v->username==$_GET['username'])
							{
								$list_user.='<b style="color:rgb(0, 153, 255);">'.$v->username.'</b><hr>';
							}else
							{
								$list_user.="<a title=\"Xem tất cả user xài chung IP với tài khoản ".$v->username."\" href=\"tracking.php?s=4&username=".$v->username."\"  target=\"_blank\">".$v->username."</a><hr>";
							}
							$amount.=$v->sum.'<hr>';
							$detail.="<a href=\"#".$v->username."\" onclick=\"return PopUpUserDetail('".$info->ip."','".$v->username."');\">".$lang['Detail']."</a><hr>";						
							
						}
					}
					$parse['username_row']=substr($list_user,0,-4);				
					$parse['amount']=substr($amount,0,-4);	
					$parse['bad_list']=substr($bad_list,0,-4);	
					$parse['ban']=substr($ban,0,-4);	
					$parse['detail']=substr($detail,0,-4);	
					$list.=parsetemplate(gettemplate('admin/tracking_same_pc_user_row1'),$parse);
					$no++;					
				}					
				$parse['list'] = $list;					
			}
			else
			{
				$parse['list'] ='';			
			}		
			$page = parsetemplate(gettemplate('/admin/tracking_same_pc_user1'), $parse);
			display2($page,'');		
		break;			
		default://same pc by ip	
			define ( 'MAXROW',1);
			// xoa bot thong tin usernam=NULL va time cach day nua thang
			if(!isset($_SESSION['delete_old_security']))
			{
				$check=date("Y-m-d H:i:s",time()-1296000);
				$sql="DELETE FROM wg_securities WHERE `username`='' OR time < '$check'";
				$db->setQuery($sql);
				$db->query();
				$_SESSION['delete_old_security']=1;
			}	
			$parse['txt_ip']=$parse['list']=$dk='';
			$parse['class_2'] = "<span style=\"background:#FFCC66\">";	
			$link='tracking.php?';
			if (isset($_GET['ip']))
			{
				$dk="WHERE ip LIKE '%".$_GET['ip']."%'";
				$parse['txt_ip'] =$_GET['ip'];
				$link.='ip='.$_GET['ip'];
			}	
			//START: get for paging
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
				$x=0;
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");
			}
			//END: get for paging
			
			//START: total record
			$sql="SELECT COUNT(DISTINCT(ip)) FROM wg_securities ".$dk;
			$db->setQuery($sql);
			$totalRecord = $db->loadResult();
	
			$parse['total_record']= "<b>".$lang['Total record'].": ".$totalRecord."</b>";
			$totalPage =$totalRecord;
			$parse['total_page'] = $totalPage;
			//END: total record
	
			$sql=" SELECT `ip`,COUNT(DISTINCT(username)) AS sum	FROM `wg_securities` ".$dk." GROUP BY ip ORDER BY sum DESC LIMIT $x,".constant("MAXROW")."";	
			$db->setQuery($sql);
			$wg_securities=NULL;
			$db->loadObject($wg_securities);
			//START: dua ip vao mang
			if($wg_securities)
			{	
				//-> dung cho nhung tai khoan con su dung 
				$sql = "SELECT tb2.id,tb1.username,count(tb1.id) as num FROM wg_securities AS tb1,wg_users AS tb2 WHERE tb1.ip = '".$wg_securities->ip."' AND tb1.username=tb2.username GROUP BY tb1.username ORDER BY num  DESC"; 
				$db->setQuery($sql);
				$infos = null;
				$infos = $db->loadObjectList();		
				if($infos)
				{
					$parse1 = array();				
					$parse1['ip_row'] = $wg_securities->ip;	
					$no=1;										
					foreach($infos as $info)
					{		
						$parse1['no'] .=$no."<br/><hr>";		
						$parse1['user_id'] = $info->id;
						$parse1['checkbox'] .= "<input type=\"checkbox\" id=\"checkbox\" 
												name=\"checkbox[]\" value=\"".$info->id."\" onclick=\"checkban();\"><br/><hr>";						
						$parse1['punish'] .= "<a href=\"#".$info->username."\" onclick=\"PopPunish(".$info->id.");\"><strong>&Phi;</strong></a><br/><hr>";						
						if($info->username == $userName)
						{							
							$parse1['username_row'] .= "<span style='background:#66CC99'>".$userName."</span><br/><hr>";																
						}
						else
						{
							$parse1['username_row'] .='<a href="tracking.php?s=4&username='.$info->username.'" target="_blank">'.$info->username.'</a><br/><hr>';		
							$parse1['username'] .= $info->username;										
						}
						$parse1['amount'] .= $info->num."<br/><hr>";	
						
						//START: for bad list
						if(checkBadList($info->username))
						{
							$parse1['bad_list'] .="<b style = \"color:red\">v</b><br/><hr>";
						}
						else
						{
							$parse1['bad_list'] .= "<br/><hr>";
						}
						//END: for bad list
						
						//START: check banned of user
						if(checkUserBanned($info->id))
						{
							$parse1['ban'] .="<b style = \"color:red\">x</b><br/><hr>";
						}
						else
						{
							$parse1['ban'] .= "<br/><hr>";
						}
						//END: check banned of user
						$parse1['detail'] .= "<a href=\"#".$info->username."\" onclick=\"return PopUpUserDetail('".$wg_securities->ip."','".$info->username."');\"><strong>&curren;</strong></a><br/><hr>";	
						$no ++;										
					}			
					$list .= parsetemplate(gettemplate('admin/tracking_same_pc_ip_row'), $parse1);
									
				}
				$parse['list'] = $list;
				$parse['pagenumber']=paging($link,$totalRecord,constant("MAXROW"));			
			}		
			//END: parse list of security				
			
			$page = parsetemplate(gettemplate('/admin/tracking_same_pc_ip'), $parse);
			displayAdmin($page,$lang['tracking']);
		break;
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

//END: switch case
?>