<?php
/*
	Plugin Name: banned.php
	Plugin URI: http://asuwa.net/administrator/banned.php
	Description: 
	+ Hien thi danh sach nhung user bi baned
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
ob_start();
define ( "INSIDE", true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ('includes/db_connect.' . $phpEx);
include ('includes/common.' . $phpEx);
include ('includes/function_paging.' . $phpEx);
include('includes/func_security.php');
global  $db, $lang, $user;

if (! check_user ()) {header ( "Location: login.php" );}
if($user['authlevel'] <1)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('banned');
	$parse = $lang;
	$parse ['value_ch'] = "";
	define ( 'MAXROW', 15 );	
	if(isset($_GET['update_day']) && isset($_GET['id']))
	{
		$sql= "SELECT ban_date FROM wg_user_bans WHERE user_id=".$_GET['id'];
		$db->setQuery($sql);
		$end_date=date("Y-m-d H:i:s",strtotime($db->loadResult())+$_GET['update_day']*24*60*60);
		$sql = "UPDATE wg_user_bans SET ban_time=".$_GET['update_day'].",end_date='$end_date' WHERE user_id=".$_GET['id'];
		$db->setQuery($sql);
		$db->query();
	}
	if (isset($_POST['unban']))
	{
		$task = "unban";
	}	
	switch ($task)
	{
		case "unban":
			$arrs = $_POST ['checkbox'];
			if (isset ( $arrs ))
			{
				foreach ( $arrs as $arr ) {
					// delete from wg_user_bans when user is unbaned
					$sql = "DELETE FROM wg_user_bans WHERE user_id=" . $arr;
					$db->setQuery ( $sql );
					$db->query ();
					
					$sql = "UPDATE wg_users SET baned='0',anounment='' WHERE id=" . $arr;
					$db->setQuery ( $sql );
					$db->query ();
				}
				header("Location:banned.php");
				exit();
			}
			header("Location: banned.php");	
			exit();	
		break;	
		default:
			
			if (isset($_GET['keyword']))
			{
				$userName =$_GET['keyword'];
				$where_ = " WHERE username LIKE '%" . $userName . "%'";
				$parse ['value_ch'] = $userName;
			}
			else
			{
				$where_ = " WHERE 1";
			}
			$sqlSum = "SELECT COUNT(DISTINCT(id)) FROM wg_user_bans " . $where_;
			$db->setQuery ( $sqlSum );
			$sum = ( int ) $db->loadResult ();
			$parse ['sum'] = $sum;
			
			//START: get for paging
			if (empty ( $_GET ["page"] ) || $_GET ["page"] == 1 || ! is_numeric ( $_GET ["page"] )) {
				$x = 0;
			} else {
				$x = ($_GET ["page"] - 1) * constant ( "MAXROW" );
			}
			//END: get for paging
			$parse ['total_record'] = $sum;
			$parse ['total_page'] = ceil ( $sum / constant ( "MAXROW" ) );
			
			$parse ['list'] = "";
			$parse ['pagenumber'] = "";
				$sql = "SELECT * FROM wg_user_bans" . $where_ . " ORDER BY id DESC LIMIT " . $x . "," . constant ( "MAXROW" ) . "";
			$db->setQuery ( $sql );
			$elements = $db->loadObjectList ();
			//START: parse list of user baned
			if ($elements) {
				$no =$x+1;
				foreach ( $elements as $element ) {
					$permission = $element->baned;
					$parse ['permission'] = "Ban";
					$parse ['id'] = $element->user_id;
					$parse ['name'] = $element->name;
					$parse ['username'] = $element->username;
					$parse ['ban_date'] = substr ( $element->ban_date, 10 ) . ' ' . substr ( $element->ban_date, 8, 2 ) . '-' . substr ( $element->ban_date, 5, 2 ) . '-' . substr ( $element->ban_date, 0, 4 );
					$ban_time=NULL;
					$parse['page']='';
					if(isset($_GET['keyword']))
					{
						$parse['page']='keyword='.$_GET['keyword'].'&';
					}
					if(isset($_GET['page']))
					{
						$parse['page'].='page='.$_GET['page'].'&';
					}
					for($i=1;$i<=365;$i++)
					{
						if($i==$element->ban_time)
						{
							$ban_time.='<option value="'.$i.'" selected="selected" >'.$i.'</option>';
						}
						else
						{
							$ban_time.='<option value="'.$i.'">'.$i.'</option>';
						}					
					}
					$parse ['ban_time']=$ban_time;
					$parse ['ban_end']=substr ( $element->end_date, 10 ) . ' ' . substr ( $element->end_date, 8, 2 ) . '-' . substr ( $element->end_date, 5, 2 ) . '-' . substr ( $element->end_date, 0, 4 );
					$parse ['reason'] =substr ($element->reason,0,100).'...';
					$parse ['reasons'] =$element->reason;
					$parse ['no'] = $no;
					$list .= parsetemplate ( gettemplate ( '/admin/banned_row' ), $parse );
					$no ++;
				}
				$parse ['list'] = $list;
				//phan trang
				if (isset($_GET['keyword']))
				{
					$parse['pagenumber'] = paging('banned.php?keyword='.$_GET['keyword'].'', $sum, constant("MAXROW")) ;
				}
				else
				{
					$parse['pagenumber'] = paging('banned.php?', $sum, constant("MAXROW")) ;	
				}
				
			} else {
				$parse ['pagenumber'] = '';
				$parse ['list'] = parsetemplate ( gettemplate ( '/admin/banned_null' ), $parse );
			}
			//END: parse list of user baned
			
			$page = parsetemplate ( gettemplate ( '/admin/banned' ), $parse );
			displayAdmin ( $page, $lang ['banned'] );
		break;
	}
}
?>