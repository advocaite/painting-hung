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
include ('includes/func_security.'.$phpEx);

global $db,$lang;
if (! check_user ()) {header ( "Location: login.php" );}
if ($user ['authlevel'] <1) {
	header ( "Location: login.php" );
}

includeLang ( 'tracking' );

$parse = $lang;
if($_POST['go_ban'])
{
	$sql ="INSERT INTO `wg_bad_list`(`username`,`reason`,`time`) VALUES ('".$_POST['username']."','".$db->getEscaped($_POST['txt_reason'])."','".date("Y-m-d H:i:s")."')";
	$db->setQuery($sql);
	$db->query();
	echo '<script>window.close();</script>';
}
if ($_GET ['keyword']=='')
{
	header("Location:tracking.php?s=3");
}
if($_POST['delete'])
{
	$arrs=$_POST['checkbox'];
	if (isset($arrs))
	{
		foreach($arrs as $arr)
		{
			$sql='DELETE FROM wg_messages_backup WHERE id='.$arr;
			$db->setQuery($sql);
			$db->query();
		}
	}	
}
$task = $_GET ['tab'];
switch ($task)
{
	case "view":
		$sql="SELECT * FROM wg_messages_backup WHERE id=".$_GET['id'];
		$db->setQuery($sql);
		$content=NULL;
		$db->loadObject($content);
		if($content->from_id>0)
		{
			$parse['sent_or_receive']='Người gởi';
			$check=$content->from_id;
			
		}
		else
		{
			$parse['sent_or_receive']='Người nhận';
			$check=$content->to_id;
		}
		$parse['username']="account not exist";
		$sql="SELECT username FROM wg_users WHERE id=".$check;
		$db->setQuery($sql);
		if($db->loadResult() !='')
		{
			$parse['username']=$db->loadResult();			
		}
		$parse['show_date']=substr($content->times,8,2).'-'.substr($content->times,5,2).'-'.substr($content->times,2,2);           
		$parse['show_time']=substr($content->times,10);
		$parse['content']=$content->content;
		$parse['subject']=$content->subject;
		$page = parsetemplate(gettemplate('/admin/message_view'), $parse);
		display2($page,'');
		break;
	default:
		define ( 'MAXROW',20);		
		if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"]))
		{
			$x=0;
		}else{
			$x=($_GET["page"]-1)*constant("MAXROW");
		}
		$parse['value_ch']=$parse['username']=$_GET['keyword'];
		$parse['checked']='';
		
		$sql="SELECT id FROM wg_users WHERE username ='".$_GET['keyword']."'";
		$db->setQuery($sql);
		$userT=$db->loadResult();
		$a='from_id >0';
		$b='tb1.from_id >0';
		$c='tb1.from_id';
		$parse['color'.$_GET['tab'].'']='#0099FF';
		$parse['sent_or_receive']='Người gởi';
		$parse['tab']=$_GET['tab'];
		if($_GET['tab']==2)
		{
			$a='to_id >0';
			$b='tb1.to_id >0';
			$c='tb1.to_id';	
			$parse['sent_or_receive']='Người nhận';
		}
		$sqlsum="SELECT COUNT(DISTINCT(id)) FROM wg_messages_backup	WHERE id_user=".$userT." AND ".$a;
			
		$sql="SELECT wg_users.username,tb1.id,tb1.times,tb1.subject,tb1.content 
		FROM `wg_messages_backup` AS tb1 LEFT JOIN wg_users ON wg_users.id=".$c." 
		WHERE tb1.id_user=".$userT." AND ".$b." 
		ORDER BY tb1.times DESC LIMIT ".$x.",".constant("MAXROW")."";
		
		$link_page="message.php?tab=".$_GET['tab']."&keyword=".$_GET['keyword']."&";
		$parse['checked']='';
		if(isset($_GET['SearchTime']))
		{
			if($_GET['SearchTime']=='ToDay')
			{
				$sqlsum="SELECT COUNT(DISTINCT(id)) FROM wg_messages_backup	
				WHERE id_user=".$userT." AND ".$a." AND times LIKE '%".date("Y-m-d")."%'";
			
				$sql="SELECT wg_users.username,tb1.id,tb1.times,tb1.subject,tb1.content 
				FROM `wg_messages_backup` AS tb1 LEFT JOIN wg_users ON wg_users.id=".$c." 
				WHERE tb1.id_user=".$userT." AND ".$b." AND times LIKE '%".date("Y-m-d")."%'
				ORDER BY tb1.times DESC LIMIT ".$x.",".constant("MAXROW")."";			
				
				$parse['checked']='checked="checked"';
				$link_page="message.php?tab=".$_GET['tab']."&keyword=".$_GET['keyword']."&SearchTime=ToDay&";	
				
			}
			else
			{
				$day = explode("-",$_GET['SearchTime']);
				$char=sprintf("%04d-%02d-%02d",$day[2],$day[1],$day[0]);
				
				$sqlsum="SELECT COUNT(DISTINCT(id)) FROM wg_messages_backup	
				WHERE id_user=".$userT." AND ".$a." AND times LIKE '%".$char."%'";
			
				$sql="SELECT wg_users.username,tb1.id,tb1.times,tb1.subject,tb1.content 
				FROM `wg_messages_backup` AS tb1 LEFT JOIN wg_users ON wg_users.id=".$c." 
				WHERE tb1.id_user=".$userT." AND ".$b." AND times LIKE '%".$char."%'
				ORDER BY tb1.times DESC LIMIT ".$x.",".constant("MAXROW")."";
				
				$link_page="message.php?tab=".$_GET['tab']."&keyword=".$_GET['keyword']."&SearchTime=".$_GET['SearchTime']."&";	
			}
		}
		
		$db->setQuery($sqlsum);
		$totalRecord = $db->loadResult();
		$parse['total_record']= "<b>".$lang['Total record'].": ".$totalRecord."</b>";
		$totalPage = ceil($totalRecord / constant("MAXROW"));
		$parse['total_page'] = $totalPage;
		
		$db->setQuery($sql);
		$elements = $db->loadObjectList();
		
		$sql_inbox="SELECT COUNT(DISTINCT(id)) FROM wg_messages_backup WHERE id_user=".$userT." AND from_id >0";
		$db->setQuery($sql_inbox);						
		$parse['inbox']=$db->loadResult();	
		
		$sql_outbox="SELECT COUNT(DISTINCT(id)) FROM wg_messages_backup WHERE id_user=".$userT." AND to_id >0";
		$db->setQuery($sql_outbox);						
		$parse['outbox']=$db->loadResult();	
		
		$parse['option_day']=returnInfoDaySecurityAdmin(1,31,$day[0]);
		$parse['option_month']=returnInfoDaySecurityAdmin(1,12,$day[1]);
		$parse['option_year']=returnInfoDaySecurityAdmin(2009,2010,$day[2]);
		if($elements)
		{
			$no =$x+1;												
			foreach ($elements as $element)
			{
				$parse['id']=$element->id;
				$parse['from_user']="<a href=\"message.php?tab=1&keyword=".$element->username."\">".$element->username."</a>";	
				$parse['no']=$no;	
				if($element->username==NULL)
				{
					$parse['from_user']="<span style=\"color:#FF0000; font-weight:bolder\">account not exist</span>";
				}
				$parse['title']=$element->subject;
				if($element->subject=='')
				{
					$parse['title']='Không có chủ đề';
				}
				$parse['date']=substr($element->times,10).' '.substr($element->times,8,2).'-'.substr($element->times,5,2).'-'.substr($element->times,2,2);		
				$list.= parsetemplate(gettemplate('admin/tracking_message_statistic_detail_row'), $parse);				
				$no ++;
			}			
			$parse['row'] =$list;
			$a="'".$link_page."page='+this.options[this.selectedIndex].value";
			$b="'_top'";
			$string='onchange="javascript:window.open('.$a.','.$b.')"';
			$parse['pagenumber']=''.$lang['40'].'<select name="page" style="width:40px;height=40px;" '.$string.'>';
			for($i=1;$i<=ceil($totalRecord/constant("MAXROW"));$i++)
			{
				$parse['pagenumber'].='<option value="'.$i.'"';
				if($_GET["page"]==$i){
					$parse['pagenumber'].=' selected="selected">';
				}else{
					$parse['pagenumber'].='>';
				}						
				$parse['pagenumber'].=''.$i.'</option>';
			}
			$parse['pagenumber'].='</select>';
		}
		else
		{
			$parse['row']='';
			$parse['pagenumber']=0;
		}		
		$page = parsetemplate(gettemplate('/admin/tracking_message_statistic_detail'), $parse);
		displayAdmin($page,$lang['tracking']);
	break;
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