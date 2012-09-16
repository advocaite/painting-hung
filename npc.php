<?php 
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php');
require_once($ugamela_root_path . 'includes/func_security.php');
require_once($ugamela_root_path . 'includes/common.php');
include_once($ugamela_root_path . 'soap/call.php');
checkRequestTime();
if($_GET['do'] == reg_success && isset($_GET['value']))
{
	$string=base64_decode($_GET['value']);	
	if(substr($string,0,3) == 'IG_')
	{		
		includeLang('npc');
		global $db,$lang;
		list($username,$name_npc) = split(';',substr($string,3));
		$sql="SELECT id,(SELECT id FROM wg_users WHERE username='".$username."') as ID FROM wg_users WHERE username='Admin'";
		$db->setQuery($sql);	
		$query=NULL;
		$db->loadObject($query);				
		
		$message=$lang['reg_success'].' '.$name_npc; 				
		$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES(".$query->id.",0,".$query->ID.",'".date("Y-m-d H:i:s")."',0,'".$lang['Subject1']."','".$message."')";
		$db->setQuery($sql);
		
		$db->query();			
		$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES(".$query->ID.",".$query->id.",0,'".date("Y-m-d H:i:s")."',0,'".$lang['Subject1']."','".$message."')";
		$db->setQuery($sql);
		$db->query();	
		header('Content-type: text/xml');
		header('Pragma: public');        
		header('Cache-control: private');
		header('Expires: -1');				
		echo '<?xml version="1.0" encoding="utf-8" ?>
		<value>Successfull</value>';
		exit();
	}					
}
if(!check_user()){ header("Location: login.php"); }
includeLang('npc');
global $user;
$sql="SELECT * FROM wg_npc";
$db->setQuery($sql);	
$query=NULL;
$query=$db->loadObjectList();
$task=$_GET['do'];
$base_url = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'/',0));			
if($query)
{
	$xmlDoc = new DOMDocument();
	switch ($task)
	{
		case 'reg':
				$asu=0;
				foreach ($query as $v)
				{	
					if($v->id == $_GET['id'] && ( $_GET['position'] >=2 || $_GET['position'] <=5))
					{
						$xmlDoc = new DOMDocument();
						$xmlDoc->load($v->links.'/npc.php?do=asu');							
						$asu=$xmlDoc->getElementsByTagName("value")->item(0)->nodeValue;						
						if(get_gold_remote($user['username']) >= $asu)
						{
							$xmlDoc->load($v->links."/npc.php?do=reg&us=".$_GET['us']."&position=".$_GET['position']);							
							$content1 = $xmlDoc->getElementsByTagName("value")->item(0)->nodeValue;	
							$content2 = $xmlDoc->getElementsByTagName("message")->item(0)->nodeValue;
							if($content1 !='' && $content2 !='')
							{
								withdraw_gold_remote($user['username'],$asu,21);
								InsertLogPlus($user['id'],21,$asu);
								$sql="SELECT id FROM wg_users WHERE username='Admin'";
								$db->setQuery($sql);	
								$userId=(int)$db->loadResult();
								
								$message=$lang['link'].' '.$v->links.'/active_user.php?value='.base64_encode('IG_'.$base_url).' \r\r'; 
								$message.=$lang['position'].$xmlDoc->getElementsByTagName("position")->item(0)->nodeValue.'\r\r';            
								$message.=$lang['code'].'\r\r';
								$message.=$content2;
								$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES(".$userId.",0,".$user['id'].",'".date("Y-m-d H:i:s")."',0,'".$lang['Subject']." ".$v->name."','".$message."')";
								$db->setQuery($sql);
								$db->query();			
								$sql ="INSERT INTO `wg_messages` (`id_user`,`from_id`,`to_id`,`times`,`status`,`subject`,`content`) VALUES(".$user['id'].",".$userId.",0,'".date("Y-m-d H:i:s")."',0,'".$lang['Subject']." ".$v->name."','".$message."')";
								$db->setQuery($sql);
								$db->query();					
								echo $content1;
								exit();
							}					
						}
						header("Location:npc.php?do=detail&id=".$_GET['id']);
						exit();
					}
				}				
			break;
		case 'detail':
				$content='';				
				foreach ($query as $v)
				{	
					if($v->id == $_GET['id'])
					{
						$xmlDoc->load($v->links.'/npc.php?do=detail');
						$value=base64_encode('IG_'.$user['username'].';'.$base_url);
						echo str_replace("{username}",$value,$xmlDoc->getElementsByTagName("value")->item(0)->nodeValue);								
						exit();
					}				
				}		
			break;		
		default:		
				$row='';
				$stt=1;			
				foreach ($query as $v)
				{	
					$xmlDoc->load($v->links.'/npc.php?stt='.$stt.'&us='.$user['username']);	
					$row.=str_replace("{value}",base64_encode('IG_'.$base_url),$xmlDoc->getElementsByTagName("value")->item(0)->nodeValue);		
					$stt++;				
				}
				$parse['row']=$row;
				$page = parsetemplate(gettemplate('npc'),$parse); 
				display2($page,'');
			break;
	}
}
ob_end_flush();
?>
	