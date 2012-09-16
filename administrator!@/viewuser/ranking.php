<?
ob_start(); 
define('INSIDE', true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.'.$phpEx);
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/func_build.php');
require_once($ugamela_root_path . 'includes/func_security.php');
require_once($ugamela_root_path . 'includes/function_troop.php');
require_once($ugamela_root_path . 'includes/function_status.php');
require_once($ugamela_root_path . 'includes/function_attack.php');
require_once($ugamela_root_path . 'includes/function_resource.php');
require_once($ugamela_root_path . 'includes/function_trade.php');

if(!check_user()){ header("Location: login.php");}
includeLang('ranking');
global $wg_village,$db,$wg_buildings,$user;
$village=$_COOKIE['villa_id_cookie'];

$wg_buildings=NULL;
$wg_village=NULL;

$wg_village=getVillage($village);
$wg_buildings=getBuildings($village);

getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());

$parse=$lang;
$tpl_header='ranking_title';

define('MAXROW',15);
/*-------------------------------------------------------------------------------------------------------------------------------
* @Author: duc hien
* @Des:	
* @param:
* @return: 
*/
function getInforForWonder($user_id)
{
	global $db;	
	$sql="SELECT id,username,alliance_id FROM wg_users WHERE id=".$user_id."";
	$db->setQuery($sql);
	$db->loadObject($wg_users);
	$sql="SELECT name FROM wg_allies WHERE id=".$wg_users->alliance_id."";
	$db->setQuery($sql);
	$wg_users->name_village=$wg_villages->name;
	$wg_users->ally_name=$db->loadResult();
	return $wg_users;
}
function GetName_UerID($id)
{
	global $db;
	$sql="SELECT username FROM wg_users WHERE wg_users.id=$id";
	$db->setQuery($sql);
	return $db->loadResult();
}
function checkExitWoderOfUser($village)
{
	global $db,$user;
	$sql="SELECT COUNT(DISTINCT(id)) FROM wg_villages WHERE id=".$village." AND user_id=".$user['id']."";
	$db->setQuery($sql);
	$count=(int)$db->loadResult();
	if($count>0)
	{
		return 1;
	}
	return 0;
}
/*------------------Phan trang --------------------------------------*/
function getPages($get_page,$sum,$p)
{	
	for($i=1;$i<=ceil($sum/constant("MAXROW"));$i++)
	{
		$page.='<option value="'.$i.'"';
		if(empty($get_page) && $i==$p+1)
		{
			$page.=' selected="selected">';
		}
		elseif($get_page==$i)
		{
			$page.=' selected="selected">';
		}
		else
		{
			$page.='>';
		}						
		$page.=''.$i.'</option>';
	}
	$page.='</select>';
	return $page;
}
function getRankAllie($allie_id,$array)
{
	foreach($array as $key=>$v)
	{
		if($allie_id==$v->allyid)
		{
			return $key+1;
		}
	}
}
function getTemplateByTab($tab)
{		
	switch($tab)
	{	
		case 2:
			$row='ranking_attack_point';
			break;
		case 3:
			$row='ranking_defend_point';
			break;
		default :
			$row='ranking_player';
			break;
	}	
	return $row;
}
function getDkSql($tab)
{		
	switch($tab)
	{	
		case 2:
			$string='attack_point';
			break;
		case 3:
			$string='defend_point';
			break;
		default :
			$string='population';
			break;
	}	
	return $string;
}
function getHeader($tab)
{		
	switch($tab)
	{	
		case 2:
			$string='ranking.php?tab=2';
			break;
		case 3:
			$string='ranking.php?tab=3';
			break;
		default :
			$string='ranking.php';
			break;
	}	
	return header("Location:$string");
}
function returnRowTop10($name,$userid)
{
	global $db;
	$week_nd=date("W");
	$sql="SELECT tb1.user_id,tb2.username,tb2.alliance_id,tb1.$name FROM wg_top10 AS tb1,wg_users AS tb2 WHERE  tb1.$name AND tb1.week_nd=$week_nd AND tb2.id=tb1.user_id ORDER BY tb1.$name DESC LIMIT 10";
	$db->setQuery($sql);
	$wg_top10=NULL;
	$row=NULL;
	$wg_top10=$db->loadObjectList();	
	foreach($wg_top10 as $key=>$result)
	{
		$parse['stt']=$key+1;
		$parse['point']=$result->$name;
		$parse['name_player']=$result->username;
		$parse['uid']=$result->user_id;		
		$parse['class1']='';
		$parse['class2']='';
		$parse['class3']='';
		if($userid==$result->user_id)
		{
			$parse['class1']='class="li ou nbr"';
			$parse['class2']='class="ou"';
			$parse['class3']='class="re ou"';
		}		
		$row.=parsetemplate(gettemplate('ranking_top10_rows'),$parse);
	}
	return $row;
}
/*----------------------------------------------XEP HANG NGUOI CHOI----------------------------------------------------------*/
if(empty($_GET['tab']) || $_GET['tab']==2 || $_GET['tab']==3)
{
	$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_users";
	$db->setQuery($sql);
	$sum_player=(int)$db->loadResult();
	$rank_userid=$x=$p=0;
	$getDkSql=getDkSql($_GET['tab']);
	$tpl=getTemplateByTab($_GET['tab']);
	$row=gettemplate(''.getTemplateByTab($_GET['tab']).'_row');
	if(count($_POST)<>0)
	{
		if($_POST['rank']=="" && $_POST['name']=="")
		{
			getHeader($_GET['tab']);
			exit();			
		}		
		elseif($_POST['rank']!="")
		{
			if(!is_numeric($_POST['rank']) || $_POST['rank']<1 )
			{
				getHeader($_GET['tab']);
				exit();
			}
			else
			{
				if($_POST['rank']>$sum_player)
				{
					$parse['rank_user']=$sum_player;
					$rank_userid=$sum_player;
				}
				else
				{
					$parse['rank_user']=$_POST['rank'];
					$rank_userid=$_POST['rank'];
				}
				if($rank_userid % constant("MAXROW")==0)
				{
					$x=(intval($rank_userid/constant("MAXROW"))-1)*constant("MAXROW");
					$p=intval($rank_userid/constant("MAXROW"))-1;
				}
				else
				{
					$x=(intval($rank_userid/constant("MAXROW")))*constant("MAXROW");
					$p=intval($rank_userid/constant("MAXROW"));
				}
				$parse['value_name']=$parse['error']='';
				$sql_rank="SELECT * FROM wg_users ORDER BY ".$getDkSql." DESC,id ASC LIMIT ".$x.",".constant("MAXROW")."";								
			}			
		}
		elseif($_POST['name']!="")
		{
			$_POST_name=$db->getEscaped($_POST['name']);
			$parse['rank_user']=$parse['value_name']='';
			$x=$check=0;
			$sql = "SELECT ".$getDkSql.",id FROM wg_users 
			WHERE username ='".$_POST_name."' ORDER BY ".$getDkSql." DESC,id ASC";
			$db->setQuery($sql);
			$query=null;
			$db->loadObject($query);
			if($query)
			{
				$query_dk=$query->$getDkSql;
				$query_id=$query->id;
				$check=1;
			}
			else
			{
				$sql="SELECT ".$getDkSql.",id FROM wg_users  
				WHERE username LIKE '%".$_POST_name."%' ORDER BY ".$getDkSql." DESC,id ASC LIMIT 1";
				$db->setQuery($sql);
				$query=null;
				$db->loadObject($query);
				if($query)
				{
					$query_dk=$query->$getDkSql;
					$query_id=$query->id;
					$check=1;
				}
			}
			if($check>0)
			{
				$sql="SELECT COUNT(DISTINCT(id)) FROM wg_users 
				WHERE ((".$getDkSql.">".$query_dk.")or((".$getDkSql."=".$query_dk.") and (id<".$query_id.")))";
				$db->setQuery($sql);
				$rank=intval($db->loadResult())+1;
				if($rank % constant("MAXROW")==0)
				{
					$x=(intval($rank/constant("MAXROW"))-1)*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"))-1;
					$rank_userid=$rank;
				}
				else
				{
					$x=(intval($rank/constant("MAXROW")))*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"));
					$rank_userid=$rank;
				}
				$parse['rank_user']=$rank;
				$parse['value_name']=$_POST_name;	
				$parse['error']='';
				if($x<0)
				{
					$x=0;
				}
				$sql_rank="SELECT * FROM wg_users ORDER BY ".$getDkSql." DESC,id ASC LIMIT ".$x.",".constant("MAXROW")."";
			}
			else
			{
				$parse['error']=''.$lang['error1'].' <strong>'.$_POST_name.'</strong> '.$lang['error2'].'';
				$sql_rank="SELECT * FROM wg_users ORDER BY ".$getDkSql." DESC,id ASC LIMIT ".$x.",".constant("MAXROW")."";
				$parse['rank_user']=$parse['value_name']='';
			}		
		}		
	}
	else
	{
		/*------------------Phan trang --------------------------------------*/
		$sql="SELECT COUNT(DISTINCT(id)) FROM wg_users 
		WHERE ((".$getDkSql.">".$user["".$getDkSql.""].") 
		or ((".$getDkSql."=".$user["".$getDkSql.""].") and (id<".$user["id"].")))";
		$db->setQuery($sql);
		$rank_userid=intval($db->loadResult())+1;

		if($user['quest']==6.5)
		{
			$_SESSION['rank_quest_7']=$rank_userid;
		}
		if(empty($_GET["page"]) || !is_numeric($_GET["page"]) )
		{			
			if($rank_userid%constant("MAXROW")==0)
			{
				$x=(intval($rank_userid/constant("MAXROW"))-1)*constant("MAXROW");
				$p=intval($rank_userid/constant("MAXROW"))-1;
			}
			else
			{
				$x=(intval($rank_userid/constant("MAXROW")))*constant("MAXROW");
				$p=intval($rank_userid/constant("MAXROW"));
			}		
		}
		elseif( $_GET["page"]<1 || ($_GET["page"]-1)*constant("MAXROW")>$sum_player)
		{
			getHeader($_GET['tab']);
			exit();
		}
		else
		{
			$x=($_GET["page"]-1)*constant("MAXROW");
			if($_GET["page"]==1)
			{
				$rank_userid=1;
			}
			else
			{
				$rank_userid=($_GET["page"]-1)*constant("MAXROW")+1;
			}
			if($rank_userid%constant("MAXROW")==0)
			{
				$p=intval($rank_userid/constant("MAXROW"))-1;
			}
			else
			{
				$p=intval($rank_userid/constant("MAXROW"));
			}	
		}
		if($x<0)
		{
			$x=0;
		}
		$sql_rank="SELECT * FROM wg_users ORDER BY ".$getDkSql." DESC,id ASC LIMIT ".$x.",".constant("MAXROW")."";
		$parse['rank_user']=$rank_userid;
		$parse['value_name']=$parse['error']='';
	}	
	$db->setQuery($sql_rank);	
	$list_rank=$db->loadObjectList();
	$rank_=$x+1;
	foreach ($list_rank as $ptu)
	{
		$sql = "SELECT name FROM wg_allies WHERE id =".$ptu->alliance_id;
		$db->setQuery($sql);	
		$ally_name=$db->loadResult();
		if(empty($ally_name))
		{
			$parse['alies']= "";
		}else{
			$parse['alies']= $ally_name;
		}
		$parse['rank']=$rank_;
		$parse['player']=$ptu->username;		
		if(empty($_GET['tab']))
		{
			$parse['sum_workers']=$ptu->population;
			$a="'ranking.php?page='+this.options[this.selectedIndex].value";
		}
		elseif($_GET['tab']==2)
		{
			$parse['sum_attack_point']=$ptu->attack_point;
			$a="'ranking.php?tab=2&page='+this.options[this.selectedIndex].value";
		}
		elseif($_GET['tab']==3)
		{
			$parse['sum_defend_point']=$ptu->defend_point;
			$a="'ranking.php?tab=3&page='+this.options[this.selectedIndex].value";
		}		
		$parse['sum_villages']=$ptu->sum_villages;
		$parse['id1']=$ptu->id;
		$parse['id2']=$ptu->alliance_id;
		if($rank_==$rank_userid)
		{
			if(empty($_GET['tab'])  && count($_POST)==0 && !isset($_GET['page']))
			{		
				if($user['rank']>$rank_userid)
				{
					$parse['message']=$lang['up_rank'].' '.abs($user['rank']-$rank_userid).' '.$lang['unit'];
					$parse['username']=$ptu->username;	
					$parse['images']='images/up.png';
					$char=parsetemplate(gettemplate('ranking1'),$parse);
					$parse['player']=$char;
				}
				if($user['rank']<$rank_userid)
				{
					$parse['message']=$lang['down_rank'].' '.abs($user['rank']-$rank_userid).' '.$lang['unit'];
					$parse['username']=$ptu->username;	
					$parse['images']='images/down.png';
					$char=parsetemplate(gettemplate('ranking1'),$parse);
					$parse['player']=$char;
				}
			}
			$parse['class1']='class="li ou nbr"';
			$parse['class2']='class="ou"';
			$parse['class3']='class="re ou"';
			$parse['class4']='class="s7 ou"';
		}
		else
		{
			$parse['class1']='class="nbr"';
			$parse['class2']='';
			$parse['class3']='';
			$parse['class4']='class="s7"';
		}
		$list.= parsetemplate($row,$parse);
		$rank_++;
	}	
	$parse['list']=$list;
	$parse['sum_player']=$sum_player;
	$b="'_top'";
	$string='onchange="javscript:window.open('.$a.','.$b.')"';
	$parse['page']=''.$lang['40'].' <select name="page" class="fm" style="width:40px;height=40px;" '.$string.'>';
	$parse['page'].=getPages($_GET["page"],$sum_player,$p);
}
elseif($_GET['tab']==1)
{
	$sql="SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE kind_id<7";
	$db->setQuery($sql);
	$sum_village=(int)$db->loadResult();
	$parse['sum_villages']=$sum_village;
	$tpl='ranking_villages';
	$x=$p=$rank_village=0;
	$row = gettemplate('ranking_villages_row');
	if(count($_POST)<>0)
	{
		if($_POST['rank']=="" && $_POST['name']=="")
		{
			header("Location:ranking.php?tab=1");
			exit();
		}
		elseif($_POST['rank']!="")
		{	
			if(!is_numeric($_POST['rank']) || $_POST['rank']<1 )
			{
				header("Location:ranking.php?tab=1");
				exit();
			}
			else
			{
				if($_POST['rank']>$sum_village)
				{
					$rank=$sum_village;
					$parse['rank_village']=$sum_village;					
				}
				else
				{
					$rank=$_POST['rank'];
					$parse['rank_village']=$_POST['rank'];
				}			
				if($rank % constant("MAXROW")==0)
				{
					$x=(intval($rank/constant("MAXROW"))-1)*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"))-1;
				}
				else
				{
					$x=(intval($rank/constant("MAXROW")))*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"));
				}
				$rank_village=$rank;			
				$parse['value_village']=$parse['error']='';
				$sql_rank="SELECT id,workers,name,x,y,user_id FROM wg_villages 
				WHERE kind_id<=6 ORDER BY workers DESC,id ASC LIMIT ".$x.",".constant("MAXROW")."";
			}			
		}
		elseif($_POST['name']!="")
		{
			$_POST_name=$db->getEscaped($_POST['name']);
			$parse['rank_village']=$parse['value_village']='';
			$x=$check=0;
			$sql="SELECT workers,id FROM wg_villages 
			WHERE name ='".$_POST_name."' ORDER BY workers DESC,id ASC";
			$db->setQuery($sql);
			$query=null;
			$db->loadObject($query);
			if($query)
			{
				$query_workers=$query->workers;
				$query_id=$query->id;
				$check=1;
			}
			else
			{
				$sql="SELECT name,workers,id FROM wg_villages 
				WHERE name LIKE '%".$_POST_name."%' ORDER BY workers DESC,id ASC LIMIT 1";
				$db->setQuery($sql);
				$query=null;
				$db->loadObject($query);
				if($query)
				{
					$query_workers=$query->workers;
					$query_id=$query->id;
					$check=1;
				}
			}
			if($check>0)
			{
				$sql="SELECT COUNT(DISTINCT(id)) FROM wg_villages 
				WHERE (workers>".$query_workers.")or((workers=".$query_workers.")and(id<".$query_id."))";
				$db->setQuery($sql);
				$rank=intval($db->loadResult())+1; 
				if($rank%constant("MAXROW")==0)
				{
					$x=(intval($rank/constant("MAXROW"))-1)*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"))-1;
					$rank_village=$rank;
				}
				else
				{
					$x=(intval($rank/constant("MAXROW")))*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"));
					$rank_village=$rank;
				}
				$parse['rank_village']=$rank;
				$parse['value_village']=$_POST_name;	
				$parse['error']='';
				$sql_rank="SELECT id,workers,name,x,y,user_id  
				FROM wg_villages ORDER BY workers DESC,id ASC LIMIT ".$x.",".constant("MAXROW")."";
			}
			else
			{
				$parse['error']=''.$lang['error1a'].' <strong>'.$_POST_name.'</strong> '.$lang['error2'].'';
				$sql_rank="SELECT id,workers,name,x,y,user_id FROM wg_villages 
				ORDER BY workers DESC,id ASC LIMIT ".$x.",".constant("MAXROW")."";
				$parse['rank_village']=$parse['value_village']='';
			}		
		}		
	}
	else
	{
		$sql="SELECT COUNT(DISTINCT(id)) FROM wg_villages 
		WHERE (workers>".$wg_village->workers.")or((workers=".$wg_village->workers.")and(id<".$village."))";
		$db->setQuery($sql);		
		$rank_village=intval($db->loadResult())+1; 
	
		if(empty($_GET["page"]) || !is_numeric($_GET["page"]) )
		{
			if($rank_village % constant("MAXROW")==0)
			{
				$x=(intval($rank_village/constant("MAXROW"))-1)*constant("MAXROW");
				$p=intval($rank_village/constant("MAXROW"))-1;
			}
			else
			{
				$x=(intval($rank_village/constant("MAXROW")))*constant("MAXROW");
				$p=intval($rank_village/constant("MAXROW"));
			}		
		}
		elseif($_GET["page"]<1 || ($_GET["page"]-1)*constant("MAXROW")>$sum_village)
		{
			header("Location:ranking.php?tab=1");
			exit();
		}
		else
		{
			$x=($_GET["page"]-1)*constant("MAXROW");
			if($_GET["page"]==1)
			{
				$rank_village=1;
			}
			else
			{
				$rank_village=($_GET["page"]-1)*constant("MAXROW")+1;
			}
			if($rank_village%constant("MAXROW")==0)
			{
				$p=intval($rank_village/constant("MAXROW"))-1;
			}
			else
			{
				$p=intval($rank_village/constant("MAXROW"));
			}
		}

		$sql_rank="SELECT id,workers,name,x,y,user_id FROM wg_villages 
		WHERE kind_id<=6 ORDER BY workers DESC,id ASC LIMIT ".$x.",".constant("MAXROW")."";
		$parse['error']='';
		$parse['value_village']='';
	}
	$db->setQuery($sql_rank);	
	$list_rank=$db->loadObjectList();
	$rank_=$x+1;
	foreach ($list_rank as $ptu)
	{
		$parse['rank']=$rank_;
		$parse['sum_workers']=$ptu->workers;
		$parse['name_village']=$ptu->name;
		if($ptu->name=='NewName')
		{
			$parse['name_village']=$lang['NewName'];
		}
		$parse['x']=$ptu->x;
		$parse['y']=$ptu->y;
		$parse['player']=GetName_UerID($ptu->user_id);
		$parse['id']=$ptu->user_id;	
		if($rank_==$rank_village)
		{
			$parse['class1']='class="li ou nbr"';
			$parse['class2']='class="ou"';
			$parse['class3']='class="re ou"';
			$parse['class4']='class="s7 ou"';
			$parse['rank_village']=$rank_;
		}
		else
		{
			$parse['class1']='class="nbr"';
			$parse['class2']='';
			$parse['class3']='';
			$parse['class4']='class="s7"';
		}		
		$list.= parsetemplate($row,$parse);
		$rank_++;
	}
	$a="'ranking.php?tab=1&page='+this.options[this.selectedIndex].value";
	$b="'_top'";
	$string='onchange="javscript:window.open('.$a.','.$b.')"';
	$parse['page']=''.$lang['40'].' <select name="page" class="fm" style="width:40px;height=40px;" '.$string.'>';
	$parse['page'].=getPages($_GET["page"],$sum_village,$p);
	$parse['list']=$list;
}
/*---------------------------------------------------------------------------------------------------------------------------*/
elseif($_GET['tab']==4)
{
	$sql="SELECT  COUNT(DISTINCT(id)) FROM wg_allies";
	$db->setQuery($sql);
	$sum_allies=(int)$db->loadResult();
	$x=$p=$rank_allies=0;
	$parse['sum_allies']=$sum_allies;	
	$sql="SELECT SUM( population ) AS workers, res1. * , 
				ROUND(SUM( population )/res1.member) AS medium_mem
				FROM wg_ally_members AS t2
				INNER JOIN wg_users AS t3 ON t3.id = t2.user_id
				INNER JOIN (
				
				SELECT data1.id, data1.name, data1.point, data2. * 
				FROM wg_allies AS data1, (
				
				SELECT DISTINCT (
				t1.ally_id
				) AS allyid, COUNT( t1.id ) AS member
				FROM `wg_ally_members` AS t1
				WHERE t1.right_=1
				GROUP BY allyid
				ORDER BY member DESC 
				) AS data2
				WHERE data1.id = data2.allyid
				) AS res1 ON res1.id = t2.ally_id
				WHERE t2.right_=1
				GROUP BY res1.id
				ORDER BY res1.member DESC , medium_mem DESC , point DESC";
	if($sum_allies>0)
	{	
		$tpl='ranking_allies';	
		$row = gettemplate('ranking_alliances_row');
		$parse['value']=$parse['error']='';
		$db->setQuery($sql);	
		$array_allies=$db->loadObjectList();
		if(count($_POST)<>0)
		{
			if($_POST['rank']=="" && $_POST['name']=="")
			{
				header("Location:ranking.php?tab=4");
				exit();
			}			
			elseif($_POST['rank']!="")
			{	
				if(!is_numeric($_POST['rank']) || $_POST['rank']<1 )
				{
					header("Location:ranking.php?tab=4");
					exit();
				}
				else
				{
					if($_POST['rank']>$sum_allies)
					{
						$rank=$sum_allies;
						$parse['rank']=$sum_allies;						
					}
					else
					{
						$rank=$_POST['rank'];
						$parse['rank']=$_POST['rank'];
					}
					foreach($array_allies as $key=>$v)
					{
						if($_POST['rank']==($key+1))
						{
							$user_alliance_id=$v->allyid;
							break;						
						}
					}	
					if($rank % constant("MAXROW")==0)
					{
						$x=(intval($rank/constant("MAXROW"))-1)*constant("MAXROW");
						$p=intval($rank/constant("MAXROW"))-1;
					}
					else
					{
						$x=(intval($rank/constant("MAXROW")))*constant("MAXROW");
						$p=intval($rank/constant("MAXROW"));
					}
					$rank_allies=$rank;	
					$parse['value']=$parse['error']='';					
				}			
			}
			elseif($_POST['name']!="")	
			{
				$_POST_name=$db->getEscaped($_POST['name']);
				$parse['error']=''.$lang['error1b'].' <strong>'.$_POST_name.'</strong> '.$lang['error2'].'';
				$parse['value']=$parse['rank']='';
				$rank_allies=1;
				foreach($array_allies as $key=>$v)
				{
					if($_POST_name == $v->name)
					{
						$user_alliance_id=$v->allyid;
						$rank_allies=$key+1;
						$parse['error']='';
						$parse['value']=$_POST_name;
						$parse['rank']=$rank_allies;				
						break;						
					}
					else
					{
						$sql_="SELECT id FROM wg_allies WHERE name LIKE '%".$_POST_name."%'";
						$db->setQuery($sql_);
						if($db->loadResult()== $v->id)
						{
							$user_alliance_id=$v->allyid;
							$rank_allies=$key+1;
							$parse['error']='';
							$parse['value']=$_POST_name;
							$parse['rank']=$rank_allies;				
							break;	
						}
					}
				}
				if($rank_allies % constant("MAXROW")==0)
				{
					$x=(intval($rank_allies/constant("MAXROW"))-1)*constant("MAXROW");
					$p=intval($rank_allies/constant("MAXROW"))-1;					
				}
				else
				{
					$x=(intval($rank_allies/constant("MAXROW")))*constant("MAXROW");
					$p=intval($rank_allies/constant("MAXROW"));					
				}				
			}
		}
		else
		{
			$rank_allies=getRankAllie($user['alliance_id'],$array_allies);
			$parse['rank']=$rank_allies;
			$user_alliance_id=$user['alliance_id'];
			if($user['alliance_id']==0)
			{
				$x=$p=$rank_allies=0;
				$parse['rank']='';
			}
			else
			{
				if(empty($_GET["page"]) || !is_numeric($_GET["page"]) )
				{
					if($rank_allies % constant("MAXROW")==0)
					{
						$x=(intval($rank_allies/constant("MAXROW"))-1)*constant("MAXROW");
						$p=intval($rank_allies/constant("MAXROW"))-1;
					}
					else
					{
						$x=(intval($rank_allies/constant("MAXROW")))*constant("MAXROW");
						$p=intval($rank_allies/constant("MAXROW"));
					}		
				}
				elseif($_GET["page"]<1 || ($_GET["page"]-1)*constant("MAXROW")>$sum_allies)
				{
					header("Location:ranking.php?tab=4");
					exit();
				}
				else
				{
					$x=($_GET["page"]-1)*constant("MAXROW");
					if($_GET["page"]==1)
					{
						$rank_allies=1;
					}
					else
					{
						$rank_allies=($_GET["page"]-1)*constant("MAXROW")+1;
					}
					if($rank_allies % constant("MAXROW")==0)
					{
						$p=intval($rank_allies/constant("MAXROW"))-1;
					}
					else
					{
						$p=intval($rank_allies/constant("MAXROW"));
					}					
				}
			}
		}
		$sql_rank=$sql.' LIMIT '.$x.','.constant("MAXROW").';';
		$db->setQuery($sql_rank);	
		$list_allies=$db->loadObjectList();
		$count=$x+1;	
		foreach ($list_allies as $key =>$ptu)
		{
			$parse['stt']=$count;
			$parse['aid']=$ptu->allyid;
			$parse['point_allie']=$ptu->point;
			$parse['sum_member']=$ptu->member;
			$parse['worker_tb']=$ptu->medium_mem;
			$parse['name_allie']=$ptu->name;
			$count++;			
			if($user_alliance_id==$ptu->allyid) 
			{
				$parse['class1']='class="li ou nbr"';
				$parse['class2']='class="ou"';
				$parse['class3']='class="re ou"';
				$parse['class4']='class="s7 ou"';					
			}
			else
			{				
				$parse['class1']='class="nbr"';
				$parse['class2']='';
				$parse['class3']='';
				$parse['class4']='class="s7"';
			}		
			$list.= parsetemplate($row,$parse);
			$rank_++;
		}
		$a="'ranking.php?tab=4&page='+this.options[this.selectedIndex].value";
		$b="'_top'";
		$string='onchange="javscript:window.open('.$a.','.$b.')"';
		$parse['page']=''.$lang['40'].' <select name="page" class="fm" style="width:40px;height=40px;" '.$string.'>';
		$parse['page'].=getPages($_GET["page"],$sum_allies,$p);
		$parse['list']=$list;
	}
	else
	{
		$tpl='ranking_allies_null';
		$row = gettemplate('ranking_allies_row');
		$parse['error']='';
		$parse['list']='';
	}
}
elseif($_GET['tab']==5)
{
	$sql="SELECT t1.level,t1.vila_id,t2.name AS name_village,t2.x,t2.y,t2.user_id FROM wg_buildings AS t1, wg_villages AS t2 ";
	$sql.=" WHERE t1.type_id=37 AND t2.id=t1.vila_id ORDER BY t1.level DESC";
	$db->setQuery($sql);
	$array_list=$db->loadObjectList();
	$count_worldwonder=count($array_list);
	$parse['sum_world_wonder']=$count_worldwonder;
	if($array_list)
	{	
		$tpl='ranking_world_wonder';	
		$row = gettemplate('ranking_world_wonder_row');
		$parse['value']=$parse['error']='';
		if(count($_POST)<>0)
		{
			if($_POST['rank']=="" && $_POST['name']=="")
			{
				header("Location:ranking.php?tab=5");
				exit();
			}		
			elseif($_POST['rank']!="")
			{	
				if(!is_numeric($_POST['rank']) || $_POST['rank']<1 )
				{
					header("Location:ranking.php?tab=5");
					exit();
				}
				else
				{
					if($_POST['rank']>$count_worldwonder)
					{
						$rank=$count_worldwonder;
						$parse['rank']=$count_worldwonder;						
					}
					else
					{	
						$rank=$_POST['rank'];
						$parse['rank']=$_POST['rank'];						
					}
					if($rank%constant("MAXROW")==0)
					{
						$x=(intval($rank/constant("MAXROW"))-1)*constant("MAXROW");
						$p=intval($rank/constant("MAXROW"))-1;
					}
					else
					{
						$x=(intval($rank/constant("MAXROW")))*constant("MAXROW");
						$p=intval($rank/constant("MAXROW"));
					}
					$y=$x+constant("MAXROW")-1;						
					$parse['value']='';
					$parse['error']='';					
				}			
			}
			elseif($_POST['name']!="")	
			{
				$_POST_name=$db->getEscaped($_POST['name']);
				$sql="SELECT t1.vila_id,t2.name AS name_village FROM wg_buildings AS t1, wg_villages AS t2 ";
				$sql.=" WHERE t1.type_id=37 AND t2.id=t1.vila_id AND t2.name LIKE '%".$_POST_name."%' 
				ORDER BY t1.level DESC LIMIT 1";
				$db->setQuery($sql);
				$query=null;
				$db->loadObject($query);				
				$parse['error']=''.$lang['error1c'].' <strong>'.$_POST_name.'</strong> '.$lang['error2'].'';
				$parse['value']='';
				$rank=1;
				foreach($array_list as $key=>$v)
				{
					if($query->vila_id==$v->vila_id)
					{
						$rank=$key+1;
						$parse['error']='';
						$parse['value']=$_POST_name;
						break;						
					}
				}
				if($rank % constant("MAXROW")==0)
				{
					$x=(intval($rank/constant("MAXROW"))-1)*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"))-1;					
				}
				else
				{
					$x=(intval($rank/constant("MAXROW")))*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"));					
				}
				$y=$x+constant("MAXROW")-1;	
				$parse['rank']=$rank;				
			}
		}
		else
		{ 		
			$parse['rank']='';
			$stt=$user['id'];
			$rank=1;
			foreach($array_list as $key=>$v)
			{
				if(checkExitWoderOfUser($v->vila_id)==1)
				{
					$rank=$key+1;
					break;					
				}
			}			
			if(empty($_GET["page"]) || !is_numeric($_GET["page"]) )
			{
				if($rank%constant("MAXROW")==0)
				{
					$x=(intval($rank/constant("MAXROW"))-1)*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"))-1;										
				}
				else
				{
					$x=(intval($rank/constant("MAXROW")))*constant("MAXROW");
					$p=intval($rank/constant("MAXROW"));							
				}
				$y=$x+constant("MAXROW")-1;					
			}
			elseif($_GET["page"]<1 || ($_GET["page"]-1)*constant("MAXROW")>$count_worldwonder)
			{
				header("Location:ranking.php?tab=5");
				exit();
			}
			else
			{
				$x=($_GET["page"]-1)*constant("MAXROW");
				if($_GET["page"]==1)
				{
					$rank=1;
				}
				else
				{
					$rank=($_GET["page"]-1)*constant("MAXROW")+1;
				}
				if($rank%constant("MAXROW")==0)
				{
					$p=intval($rank/constant("MAXROW"))-1;
				}
				else
				{
					$p=intval($rank/constant("MAXROW"));
				}
				$y=$x+constant("MAXROW")-1;					
			}			
		}
		$array=array();			
		foreach ($array_list as $key =>$v)
		{
			if($key >=$x && $key <=$y)
			{
				$array=getInforForWonder($v->user_id);			
				$parse['stt']=$key+1;
				$parse['level']=$v->level;	
				$parse['name_player']=$array->username;	
				$parse['name_village']=$v->name_village;	
				$parse['name_allies']=$array->ally_name;	
				$parse['uid']=$array->id;
				$parse['x']=$v->x;
				$parse['y']=$v->y;
				$parse['aid']=$array->alliance_id;
				if($key==$rank-1) 
				{
					$parse['rank']=$key+1;
					$parse['class1']='class="li ou nbr"';
					$parse['class2']='class="ou"';
					$parse['class3']='class="re ou"';
					$parse['class4']='class="s7 ou"';					
				}
				else
				{				
					$parse['class1']='class="nbr"';
					$parse['class2']='';
					$parse['class3']='';
					$parse['class4']='class="s7"';
				}		
				$list.= parsetemplate($row,$parse);
				$rank_++;
			}
		}
		$a="'ranking.php?tab=5&page='+this.options[this.selectedIndex].value";
		$b="'_top'";
		$string='onchange="javscript:window.open('.$a.','.$b.')"';
		$parse['page']=''.$lang['40'].' <select name="page" class="fm" style="width:40px;height=40px;" '.$string.'>';
		$parse['page'].=getPages($_GET["page"],$count_worldwonder,$p);
		$parse['list']=$list;
	}
	else
	{
		$tpl='ranking_world_wonder_null';
		$row = gettemplate('ranking_world_wonder_row');
		$parse['error']='';
		$parse['list']='';
	}
}
elseif($_GET['tab']==6)
{
	$tpl='ranking_top10';
	$parse['row_attack']=returnRowTop10('attack_point',$user['id']);	
	$parse['row_defend']=returnRowTop10('defend_point',$user['id']);		
	$parse['row_resource']=returnRowTop10('resource',$user['id']);	
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
display($page,$lang['title']);
ob_end_flush();
?>