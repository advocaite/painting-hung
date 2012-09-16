<?php
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/func_security.php');
require_once($ugamela_root_path . 'includes/function_resource.php');

checkRequestTime();
if(!check_user()){ header("Location: login.php");}
includeLang('messages');
global $wg_village,$db,$wg_buildings,$timeAgain,$user;
$timeAgain=$user['amount_time']-(time()-$_SESSION['last_login']);
$village=$_COOKIE['villa_id_cookie'];

$wg_buildings=$wg_village=NULL;
$wg_village=getVillage($village);
$wg_buildings=getBuildings($village);

getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());

$parse=$lang;
$tpl_header='messages_title';
define('MAXROW',20);
$sql=" SELECT data1.num1, data2.num2
FROM (
SELECT count( `from_id` ) AS num1
FROM `wg_messages`
WHERE id_user =".$user["id"]."
AND from_id >0
) AS data1, (
SELECT count( to_id ) AS num2
FROM `wg_messages`
WHERE id_user =".$user["id"]."
AND to_id >0
) AS data2";
$db->setQuery($sql);
$query_sum=null;
$db->loadObject($query_sum);
$sum_inbox=$query_sum->num1;
$parse['sum1']=$query_sum->num1;
$sum_sent=$query_sum->num2;
$parse['sum2']=$query_sum->num2;
/*---------------------------------------------------Hộp thư ---------------------------------------------------------------*/
if(empty($_GET['tab']) ||$_GET['tab']!=1)
{
	/*------------------Phan trang --------------------------------------*/
	if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"]))
	{
		$x=0;
	}
	else
	{
		$x=($_GET["page"]-1)*constant("MAXROW");
	}
	/*------------------Phan trang --------------------------------------*/
	if(empty($_GET['tab']))
	{
		$tpl='messages_inbox';
		$sql="SELECT * FROM wg_messages WHERE id_user=".$user["id"]." AND from_id>0 ORDER BY id DESC LIMIT ".$x.",".constant("MAXROW")."";
		
	}
	elseif($_GET['tab']==2)
	{
		$tpl='messages_sent';
		$sql="SELECT * FROM wg_messages WHERE id_user=".$user["id"]." AND to_id>0 ORDER BY id DESC LIMIT ".$x.",".constant("MAXROW")."";
	}
	$db->setQuery($sql);
	$wg_messages=null;
	$db->loadObject($wg_messages);	
	if(empty($wg_messages))
	{
		$row = gettemplate('messages_row_null');
		$list= parsetemplate($row, $parse);	
		$parse['list']=$list;
		$parse['pagenumber']='';
	}
	else
	{
		if(empty($_GET['tab']))
		{
			$row=gettemplate('messages_inbox_row');			
		}
		elseif($_GET['tab']==2)
		{
			$row=gettemplate('messages_sent_row');
		}		
		$list_messages=$db->loadObjectList();
		foreach ($list_messages as $ptu)
		{
			$parse['subject']=$ptu->subject;
			if($ptu->subject=='')
			{
				$parse['subject']=$lang['No_Subject'];
			}
			if(empty($_GET['tab']))
			{
				
				$parse['sender']=GetNameByID($ptu->from_id);
				$parse['id1']=$ptu->id;
				$parse['id2']=$ptu->from_id;
				if($ptu->status==0)
				{
					$parse['type_inbox']='('.$lang['32'].')';
				}
				else
				{
					$parse['type_inbox']='';
				}				
			}
			elseif($_GET['tab']==2)
			{	
				$parse['sender']=GetNameByID($ptu->to_id);
				$parse['id1']=$ptu->id;
				$parse['id2']=$ptu->to_id;	
				$parse['type_inbox']='';
			}
			$parse['time']=substr($ptu->times,10).' '.substr($ptu->times,8,2).'-'.substr($ptu->times,5,2).'-'.substr($ptu->times,2,2);
			$list .= parsetemplate($row, $parse);	
		}
		$parse['list']=$list;
		/*------------------Phan trang --------------------------------------*/	
		if(empty($_GET['tab']))
		{
			$temp=$sum_inbox;
			$a="'messages.php?page='+this.options[this.selectedIndex].value";
		}
		elseif($_GET['tab']==2)
		{
			$temp=$sum_sent;
			$a="'messages.php?tab=2&page='+this.options[this.selectedIndex].value";
		}	
		if($temp<=constant("MAXROW"))
		{
			$parse['pagenumber']='';
						
		}
		else
		{	
			
			$b="'_top'";
			$string='onchange="javscript:window.open('.$a.','.$b.')"';
			$parse['pagenumber']=''.$lang['40'].' <select name="page" style="width:40px;height=40px;" '.$string.'>';
			for($i=1;$i<=ceil($temp/constant("MAXROW"));$i++)
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
		}
		
	}

	if(!empty($_GET['id']) && is_numeric($_GET['id']))
	{
		$id=$_GET['id'];
		//kiem tra tinh chinh xac cua ID nhap vao la thu cua chinh user login
		$sql="SELECT * FROM wg_messages WHERE id=".$id." AND id_user=".$user['id']."";	
		$db->setQuery($sql);
		$query=null;
		$db->loadObject($query);
		if($query)
		{			
			if(count($_POST)<>0)
			{
				
				if($_GET['act']=='answer')
				{
					if($_POST['value']==$_SESSION['view_message'])
					{	
						$to_id=$db->getEscaped($_POST['id']);
						$topic=$db->getEscaped(strip_tags($_POST['topic']));
						$content=substr($db->getEscaped(strip_tags($_POST['content'])),5000);
						if(!strlen(trim($_POST['topic'])))
						{
							$topic=$lang['No_Subject'];
						}					
						$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES
		(".$user['id'].",0,".$to_id.",'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."'),(".$to_id.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."')";
						$db->setQuery($sql);
						$db->query();	
						$sql="INSERT INTO `wg_messages_backup` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES	(".$user['id'].",0,".$to_id.",'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."'),(".$to_id.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."')";				
						$db->setQuery($sql);
						$db->query();	
						unset($_SESSION['view_message']);
						header("Location:messages.php");
						exit();	
					}	
				}
				$parse['link']=$_SERVER['REQUEST_URI'].'&act=answer';
				if($query->from_id>0)
				{
					$parse['from_id']=GetNameByID($query->from_id);
					$parse['to_id']=$query->from_id;			
	
				}
				else
				{
					$parse['from_id']=GetNameByID($query->to_id);
					$parse['to_id']=$query->to_id;				
				}
				$parse['topic']=''.$lang['33'].' '.$query->subject.'';
				$parse['content']="\r\n\r\n___________________\r\n".GetNameByID($query->from_id)." ".$lang['wrote'].": \r\n".substr($query->content,3000)."";
				if($query->from_id==0)
				{
					$parse['content']='';
					$parse['topic']='';
				}
				$parse['code']=$_SESSION['view_message'];
				$tpl='messages_inbox_answer';
				$parse['date']=date("d",time()).'-'.date("m",time()).'-'.date("y",time());
				$parse['time']=date("H:i:s",time());
				
			}
			else
			{
				$tpl='messages_inbox_view';
				$_SESSION['view_message']=md5($id);				
				$parse['topic']=$query->subject;
				$parse['content']=$query->content;
				
				if($query->from_id>0)
				{
					$parse['personal']=$lang['Sender'];					
					$parse['from_id']=GetNameByID($query->from_id);
					$parse['images']=$lang['38'];
				}
				else
				{
					$parse['personal']=$lang['Recipient'];
					$parse['from_id']=GetNameByID($query->to_id);
					$parse['images']=$lang['39'];
				}				
				$parse['date']=substr($query->times,8,2).'-'.substr($query->times,5,2).'-'.substr($query->times,2,2);            
				$parse['time']=substr($ptu->times,10);
			}
		}
		else
		{
			header("Location:messages.php");
			exit();
		}
	}		
}
elseif($_GET['tab']==1)
{
	$parse['display']='none';
	$tpl='messages_write';
	$parse['error']='';		
	$parse['topic']='';
	$parse['content']='';
	$parse['to']='';
	if(!empty($_GET['username']))
	{
		$parse['to']=$_GET['username'];
	}	
	if($user['alliance_id']!=0)
	{
		//lay quyen cua user do co duoc gui thu den toan bo thanh vien trong lien minh hay khong
		$sql = "SELECT privilege, ally_id FROM wg_ally_members WHERE user_id =".$user['id']." AND right_ =1";
		$db->setQuery($sql);
		$userRight = null;
		$db->loadObject($userRight);	
		if($userRight)
		{
			$parse['display']='block';			
			$parse['customize']='top:175px; left:410px;';
		}
		$privilegeArray = str_split($userRight->privilege);//tach chuoi privilege		
	}	
	if(count($_POST)<>0 && $_SESSION['write_message']==$_POST['value'])
	{
	//START: gui thu den tat ca thanh vien trong lien minh cua minh				
		if($_POST['to_name'] == "(*)")
		{
			if(!empty($privilegeArray) && $privilegeArray[6] == 1) //user co quyen gui den tat ca thanh vien
			{				
				$topic=$db->getEscaped(strip_tags($_POST['topic']));
				$content=substr($db->getEscaped(strip_tags($_POST['content'])),5000);
				
				$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`)
				 VALUES(".$user['id'].",0,".$user['id'].",'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."')";
				$db->setQuery($sql);
				$db->query();
				
				$sql="INSERT INTO `wg_messages_backup` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) 
				VALUES(".$user['id'].",0,".$user['id'].",'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."')";
				$db->setQuery($sql);
				$db->query();
								
				//lay danh sach thanh vien cua lien minh
				$sql = "SELECT user_id FROM wg_ally_members 
				WHERE ally_id = ".$userRight->ally_id." AND user_id != ".$user['id']." AND right_ = 1";
				$db->setQuery($sql);
				$userList = null;
				$userList = $db->loadObjectList();			
				if($userList)
				{
					foreach($userList as $row)
					{
						$sql ="INSERT INTO `wg_messages`(`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES(".$row->user_id.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."')";
						$db->setQuery($sql);
						$db->query();
						$sql="INSERT INTO `wg_messages_backup`(`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES(".$row->user_id.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."')";
						$db->setQuery($sql);
						$db->query();					
					}
					unset($_SESSION['write_message']);
					header("Location:messages.php?tab=2");	
					exit();				
				}	
			}
			else //user ko co quyen gui den tat ca thanh vien
			{
				$parse['error']= $lang['41'];		
				$parse['customize']='top:217px; left:410px;';
			}		
		}
		else
		{	
			$GetIDByName=GetIDByName($db->getEscaped(strip_tags($_POST['to_name'])));		
			if($GetIDByName==0)
			{
				$parse['error']=$lang['37'];
				$parse['customize']='top:217px; left:410px;';
				$parse['topic']=$db->getEscaped(strip_tags($_POST['topic']));
				$parse['content']=$db->getEscaped(strip_tags($_POST['content']));
			}
			else
			{
				$topic=$db->getEscaped(strip_tags($_POST['topic']));
				$content=$db->getEscaped(strip_tags($_POST['content']));				
				if($_POST['topic']=='')
				{
					$topic=$lang['No_Subject'];
				}
				
				$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES
		(".$user['id'].",0,".$GetIDByName.",'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."'),(".$GetIDByName.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."');";
				$db->setQuery($sql);
				$db->query();
				
				$sql="INSERT INTO `wg_messages_backup` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES
		(".$user['id'].",0,".$GetIDByName.",'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."'),(".$GetIDByName.",".$user['id'].",0,'".date("Y-m-d H:i:s")."',0,'".$topic."','".$content."')";
				$db->setQuery($sql);
				$db->query();
				
				unset($_SESSION['write_message']);	
				header("Location:messages.php?tab=2");	
				exit();
			}			
		}	
	}
	$parse['code']=$_SESSION['write_message']=md5($user['id']);	
}
if($_GET['tab']>0)
{
	$parse['class'.$_GET['tab'].'']='class="selected"';
}
else
{
	$parse['class']='class="selected"';
}
$page = parsetemplate(gettemplate($tpl_header),$parse);
$page.= parsetemplate(gettemplate($tpl), $parse);
display($page,$lang['messages']);
ob_end_flush();

function GetNameByID($id)
{
	global $db;
	$sql="SELECT username FROM wg_users WHERE id=$id LIMIT 1";
	$db->setQuery($sql);
	return $db->loadResult();
}
function GetIDByName($username)
{
	global $db;
	$sql="SELECT id FROM wg_users WHERE username='$username' LIMIT 1";
	$db->setQuery($sql);
	return $db->loadResult();
}
?>