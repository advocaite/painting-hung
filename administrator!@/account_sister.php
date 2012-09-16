<?php
/*
	Plugin Name: account_sister.php
	Plugin URI: http://asuwa.net/administrator/account_sister.php
	Description: 
	+ Hien thi danh sach nhung user duoc admin dung chung
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define("INSIDE", true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include ('includes/function_paging.' . $phpEx);
include ('includes/function_users.' . $phpEx);
include('includes/func_security.php');
global $db, $lang, $user;
if(!check_user()){ header("Location: login.php"); }
if($user['authlevel']<1)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('admin_account_sister');
	$parse = $lang;
	define('MAXROW',15); // 10 record per page
	
	if($_POST['del']){
		$task = "del";
	}
	
	switch ($task){
		case "del":
			$arrs=$_POST['checkbox'];
			if (isset($arrs))
			{
				foreach($arrs as $arr){
					// delete from wg_user_bans when user is unbaned
					$sql="DELETE FROM wg_sister WHERE user_id = ".$arr;	
					$db->setQuery($sql);
					$db->query();									
				}				
			}
			header("Location: account_sister.php");
			exit();			
		break;
		
		default:		
			$parse['value_username'] = "";
			$sql="SELECT tb2.username,tb1.* FROM wg_sister AS tb1, wg_users AS tb2 WHERE tb1.user_id=tb2.id";
			$db->setQuery($sql);
			$wg_sister=NULL;
			$elements=NULL;
			$wg_sister=$db->loadObjectList();
			$sum=count($wg_sister);							
			if (isset($_GET['keyword']))
			{			
				$array_research = admin_getUserIdByUserName($_GET['keyword']);			
				$elements=NULL;
				$i=0;
				foreach($array_research as $key=>$v)
				{	
					foreach($wg_sister as $key1=>$v1)
					{
						if($v->id == $v1->user_id)
						{
							$elements[$i]=$wg_sister[$key1];
							$i++;
						}
					}				
				}	
				$sum=count($elements);
				$parse['value_username'] = $_GET['keyword'];
			}
			else
			{
				$elements=$wg_sister;
			}
			$parse['sum']=$sum;
			//START: get for paging
			if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"]))
			{
				$x=0;
			}else{
				$x=($_GET["page"]-1)*constant("MAXROW");
			}
			//END: get for paging
			$parse['total_record']=$sum;
			$parse['total_page']=ceil($sum/constant("MAXROW"));		
			$parse['list'] = "";
			$parse['pagenumber'] = "";		
			
			//START: parse list of user sister
			if($elements)
			{
				$no =$x+1;
				foreach ($elements  as $key=>$v)
				{	
					if($key >=$x  && $key <  $x+constant("MAXROW"))
					{
						$parse['user_id'] = $v->user_id;			
						$parse['username'] =$v->username;			
						$parse['admin'] = $v->sister1;
						$parse['time']=substr($v->time,10).' '.substr($v->time,8,2).'-'.substr($v->time,5,2).'-'.substr($v->time,0,4);									
						$parse['no'] = $no;
						$list .= parsetemplate(gettemplate('/admin/account_sister_row'), $parse);
						$no++;
					}
				}
				$parse['list'] = $list;
				//phan trang
				if (isset($_GET['keyword']))
				{	
					$parse['pagenumber'] = paging('account_sister.php?keyword='.$_GET['keyword'].'', $sum, constant("MAXROW")) ;
				}
				else
				{
					$parse['pagenumber'] = paging('account_sister.php?', $sum, constant("MAXROW")) ;
				}
			}else{
				$parse['pagenumber']='';
				$parse['list']=parsetemplate(gettemplate('/admin/account_sister_null'),$parse); 
			}			  
			//END: parse list of user baned
			$page = parsetemplate(gettemplate('/admin/account_sister'), $parse);
			displayAdmin($page,$lang['banned']);
		break;
	}
}
?>