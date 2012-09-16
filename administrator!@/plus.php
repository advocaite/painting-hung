<?php
/*
	Plugin Name: plus.php
	Plugin URI: http://asuwa.net/administrator/plus.php
	Description: 
	+ Tang gold cho user
	+ Theo doi gold log
	+ Tao card
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ('includes/db_connect.' . $phpEx);
include ('includes/common.' . $phpEx);
include ($ugamela_root_path . 'includes/function_allian.php');
require_once($ugamela_root_path.'soap/call.php');

if (! check_user ()) {
	header ( "Location: login.php" );
}
if ($user ['authlevel'] <1){
	header ( "Location:index.php" );
}

includeLang ( 'admin_plus' );
global $db, $lang;
$parse = $lang;
define ( 'NUMBER_USER', 100 );
define ( 'MAXROW',20); // 15 row per page
if (isset ( $_GET ['s'] )) {
	$task = $_GET ['s'];
}
//START: switch case
switch ($task)
{	
	case "1" : //top
		$parse ['msg'] = "";
		if($_POST && $user ['authlevel']==5)
		{
			if($_POST['option'] >0 && is_numeric($_POST['txt_gold']))
			{
				$gold=$_POST['txt_gold'];
				$value=array(1=>"Tất cả gamer",2=>"Top 100 xếp hạng cao nhất",3=>"Top 100 xếp hạng thấp nhất",4=>"100 gamer mới tham gia");
				if($_POST['option']==1)
				{
					$sql = "UPDATE wg_plus SET gold = gold + " . $gold ."";
					$db->setQuery ( $sql );
					$db->query ();							
				}
				if($_POST['option']==2)
				{
					$sql_rank="SELECT id FROM wg_users ORDER BY population DESC LIMIT 0,100";
					$db->setQuery($sql_rank);	
					$list_rank=NULL;
					$list_rank=$db->loadObjectList();
					if($list_rank)
					{
						foreach($list_rank as $v)
						{
							$sql = "UPDATE wg_plus SET gold = gold + ".$gold ." WHERE user_id=".$v->id;
							$db->setQuery($sql);
							$db->query();
						}	
					}						
				}
				if($_POST['option']==3)
				{
					$sql_rank="SELECT id FROM wg_users ORDER BY population ASC LIMIT 0,100";
					$db->setQuery($sql_rank);	
					$list_rank=NULL;
					$list_rank=$db->loadObjectList();
					if($list_rank)
					{
						foreach($list_rank as $v)
						{
							$sql = "UPDATE wg_plus SET gold = gold + ".$gold ." WHERE user_id=".$v->id;
							$db->setQuery($sql);
							$db->query();
						}	
					}						
				}
				if($_POST['option']==4)
				{
					$sql_rank="SELECT id FROM wg_users ORDER BY id DESC LIMIT 0,100";
					$db->setQuery($sql_rank);	
					$list_rank=NULL;
					$list_rank=$db->loadObjectList();
					if($list_rank)
					{
						foreach($list_rank as $v)
						{
							$sql = "UPDATE wg_plus SET gold = gold + ".$gold ." WHERE user_id=".$v->id;
							$db->setQuery($sql);
							$db->query();
						}	
					}						
				}
				$description=$_SESSION['username_admin']." tặng cho ".$value[$_POST['option']]." ".$gold." Asu";
				$sql = "INSERT INTO `wg_gold_logs` (`datetime`,`description`) 
				VALUES ('".date ("Y-m-d H:i:s")."','".$description."')";
				$db->setQuery ( $sql );
				$db->query ();	
				$parse ['msg'] = "Tặng Asu thành công cho <strong>[".$value[$_POST['option']]."]</strong>";
			}
			
		}		
		$page = parsetemplate ( gettemplate ( '/admin/plus_give_gold_special' ), $parse );
		displayAdmin ( $page, $lang ['plus'] );
		break;
	case "2" :
		if(isset($_POST['delete']) && $user ['authlevel']==5)
		{
			$arrs=$_POST['checkbox'];
			if (isset($arrs))
			{
				foreach($arrs as $arr)
				{
					$sql = "DELETE FROM wg_gold_logs WHERE id=".$arr;
					$db->setQuery($sql);
					$db->query();			
				}			
			}	
		}	
		if (empty ( $_GET ["page"] ) || $_GET ["page"] == 1 || ! is_numeric ( $_GET ["page"] ))
		{
			$x = 0;
		} else {
			$x = ($_GET ["page"] - 1) * constant ( "MAXROW" );
		}
		$fileGroup = '';
		$dk_Group = '';
		$dk_Order = 'ORDER BY datetime DESC ';
		if(isset($_POST['Group_by']))
		{
			$fileGroup = ', count(description) as soluong ';
			$dk_Group = 'GROUP BY description ';
			$dk_Order = 'ORDER BY soluong DESC ';
		}
		$parse ['value_name']='';
		$sqlSum = "SELECT COUNT(DISTINCT(id)) FROM wg_gold_logs";
		$sql = "SELECT wg_gold_logs.*, wg_pack_plus.name $fileGroup FROM wg_gold_logs LEFT JOIN wg_pack_plus ON wg_gold_logs.description = wg_pack_plus.id $dk_Group $dk_Order LIMIT ".$x.",".constant("MAXROW")."";
		
		$db->setQuery ( $sqlSum );
		$sumPlayer = ( int ) $db->loadResult ();
		$parse ['total_record'] = $sumPlayer;
		$parse ['total_page'] = ceil ( $sumPlayer / constant ( "MAXROW" ) );

		$db->setQuery($sql);
		$goldLogsList = null;
		$goldLogsList = $db->loadObjectList ();

		$parse ['create_time'] = '';
		if ($goldLogsList)
		{
			$count = 1;
			$list=NULL;
			foreach ( $goldLogsList as $goldLogRow )
			{
				$parse ['no'] =$x+$count;
				if(!isset($_POST['Group_by']))
				{
					$parse ['create_time'] = $goldLogRow->datetime;
				}
				$parse ['description'] = $goldLogRow->name;
				$parse ['id'] = $goldLogRow->id;
				$parse['soluong'] = $goldLogRow->soluong;
				$list .= parsetemplate ( gettemplate ( '/admin/plus_gold_log_list' ), $parse );
				$count ++;
			}
			//START: paging
			$a = "'plus.php?s=2&page='+this.options[this.selectedIndex].value";
			$b = "'_top'";
			$string = 'onchange="javscript:window.open(' . $a . ',' . $b . ')"';
			$parse ['pagenumber'] = '' . $lang ['40'] . '<select name="page" style="width:40px;height=40px;" ' . $string . '>';
			for($i = 1; $i <= ceil ( $sumPlayer / constant ( "MAXROW" ) ); $i ++)
			{
				$parse ['pagenumber'] .= '<option value="' . $i . '"';
				if (empty ( $_GET ["page"] ) && $i == $p + 1) {
					$parse ['pagenumber'] .= ' selected="selected">';
				} elseif ($_GET ["page"] == $i) {
					$parse ['pagenumber'] .= ' selected="selected">';
				} else {
					$parse ['pagenumber'] .= '>';
				}
				$parse ['pagenumber'] .= '' . $i . '</option>';
			}
			$parse ['pagenumber'] .= '</select>';
			$parse ['view_gold_log_list'] = $list;
		}
		else
		{
			$parse ['view_gold_log_list'] ='';
			$parse ['pagenumber']='';
		}
		$page = parsetemplate ( gettemplate ( '/admin/plus_gold_log' ), $parse );
		displayAdmin ( $page,'');
		break;	
	case 3:
		$value=split(',',$_GET['id']);
		$parse['username']=$value[1];
		$parse['row']='';
		$parse['sum']='';		
		$sql="SELECT logs,gold FROM wg_plus WHERE  user_id='".$value[0]."'";
		$db->setQuery($sql);
		$wg_plus=NULL;	
		$db->loadObject($wg_plus);
		$parse['sever_asu']=$wg_plus->gold;
		$parse['bill_asu']=get_gold_remote($value[1]);
		if($wg_plus)
		{
			$sql = "SELECT * FROM wg_pack_plus";
			$db->setQuery($sql);
			$wg_pack_plus = NULL;
			$wg_pack_plus = $db->loadObjectList ();
			foreach($wg_pack_plus as $k)
			{
				$pack_plus[$k->id]=$k->name;
			}
			
			$logs=substr($wg_plus->logs,0,-1);
			$logs=split(';',$logs);	
			$stt=$count=0;
			foreach ($logs as $v)
			{
				$temp=NULL;
				$temp=split(',',$v);
				$parse['stt']=$stt+1;
				$parse['time']=$temp[0];	
				$parse['info']=$pack_plus[$temp[2]];		
				$parse['asu']='-'.$temp[3];
				$count+=$temp[3];
				if($temp[1]==1) // tai server
				{
					$parse['asu_sever']='<strong>&Phi;</strong>';
					$parse['asu_bill']='';
				}
				else
				{
					$parse['asu_sever']='';
					$parse['asu_bill']='<strong>&Phi;</strong>';
				}
				$row.=parsetemplate ( gettemplate ( '/admin/log_plus_row' ), $parse );
				$stt++;
			}		
			$parse['row']=$row;
			$parse['sum']=$count*(-1);
		}
		$page = parsetemplate ( gettemplate ( '/admin/log_plus' ), $parse );
		display2 ( $page,'');
		break;
	default :
		//START: update gold cho user
		if($_POST['give_gold'] && $user ['authlevel']==5)
		{
			$arrs = $_POST ['checkbox'];
			$gold = $_POST ['txt_gold'];
			if (is_numeric ( $gold ))
			{
				if (isset ( $arrs ))
				{
					foreach ( $arrs as $arr )
					{
						$value=split(",",$arr);						
						$sql = "UPDATE wg_plus SET gold = gold + " . $gold . " WHERE user_id=".$value[0];
						$db->setQuery ( $sql );
						$db->query ();	
						
						$description=$_SESSION['username_admin']." tặng cho gamer ".$value[1]." ".$gold." Asu";
						$sql = "INSERT INTO `wg_gold_logs` (`datetime`,`description`) VALUES ('".date ("Y-m-d H:i:s")."','".$description."')";
						$db->setQuery ( $sql );
						$db->query ();				
					}
					header ( "Location: plus.php" );
					exit();
				}
			}
		}
		//END: update gold cho user
		define ( 'MAXROW', 15 ); // 15 row per page
		$parse ['value_name'] = "";
		//START: get for paging
		if (empty ( $_GET ["page"] ) || $_GET ["page"] == 1 || ! is_numeric ( $_GET ["page"] )) {
			$x = 0;
		} else {
			$x = ($_GET ["page"] - 1) * constant ( "MAXROW" );
		}
		//END: get for paging
		$sqlSum = "SELECT COUNT(DISTINCT(id)) FROM wg_users";
		$sql = "SELECT wg_plus.*,wg_users.username FROM wg_plus LEFT JOIN wg_users 
		ON wg_users.id=wg_plus.user_id ORDER BY wg_plus.gold DESC LIMIT " . $x . "," . constant ( "MAXROW" ) . "";
		$a = "'plus.php?page='+this.options[this.selectedIndex].value";
		if(isset($_GET['keyword']))
		{
			$sqlSum = "SELECT COUNT(DISTINCT(wg_plus.user_id)) FROM wg_plus,wg_users 
			WHERE wg_users.id=wg_plus.user_id AND wg_users.username LIKE '%".$_GET['keyword']."%'";
			$sql = "SELECT wg_plus.*,wg_users.username FROM wg_plus,wg_users WHERE wg_users.id=wg_plus.user_id AND wg_users.username LIKE '%".$_GET['keyword']."%' ORDER BY  wg_plus.gold DESC LIMIT " . $x . "," . constant ( "MAXROW" ) . "";
			$a = "'plus.php?keyword=".$_GET['keyword']."&page='+this.options[this.selectedIndex].value";
		}		
		$db->setQuery ( $sqlSum );
		$sumPlayer = ( int ) $db->loadResult ();
		$parse ['total_record'] = $sumPlayer;
		$parse ['total_page'] = ceil ( $sumPlayer / constant ( "MAXROW" ) );
		
		
		$db->setQuery ( $sql );
		$elements = $db->loadObjectList ();
		//START: parse list of user baned
		if ($elements)
		{
			$no = 1;
			foreach ( $elements as $element )
			{
				$parse ['no'] =$x+$no;
				$parse ['username'] = $element->username;
				$parse ['Lumber']='';
				if(strtotime($element->lumber) > time())
				{
					$parse ['Lumber']='<strong>X</strong>';
				}
				$parse ['Clay']='';
				if(strtotime($element->clay) > time())
				{
					$parse ['Clay']='<strong>X</strong>';
				}
				$parse ['Iron']='';
				if(strtotime($element->iron) > time())
				{
					$parse ['Iron']='<strong>X</strong>';
				}
				$parse ['Crop']='';
				if(strtotime($element->crop) > time())
				{
					$parse ['Crop']='<strong>X</strong>';
				}
				$parse ['Attack']='';
				if(strtotime($element->attack) > time())
				{
					$parse ['Attack']='<strong>X</strong>';
				}
				$parse ['Defence']='';
				if(strtotime($element->defence) > time())
				{
					$parse ['Defence']='<strong>X</strong>';
				}
				$parse ['Complete']='';
				if(strtotime($element->complete) > time())
				{
					$parse ['Complete']='<strong>X</strong>';
				}
				$parse ['Trade']='';
				if(strtotime($element->trade) > time())
				{
					$parse ['Trade']='<strong>X</strong>';
				}
				$parse ['Build']='';
				if(strtotime($element->build) > time())
				{
					$parse ['Build']='<strong>X</strong>';
				}
				$parse ['Tb1']='';
				if($element->the_bai_1 >0)
				{
					$parse ['Attack']='<strong>X</strong>';
				}
				$parse ['Tb2']= '';
				if($element->the_bai_2 > 0)
				{
					$parse ['Tb2']='<strong>X</strong>';
				}
				$parse ['Tb3']='';
				if($element->the_bai_3 >0)
				{
					$parse ['Tb3']='<strong>X</strong>';
				}
				$parse ['Sms']='';
				if(strtotime($element->sms_attack) > time())
				{
					$parse ['Sms']='<strong>X</strong>';
				}
				$parse ['Asu'] 	='';
				if(strtotime($element->gold) > time())
				{
					$parse ['Asu']='<strong>X</strong>';
				}			
				$parse ['id'] = $element->user_id.','.$element->username;
				$parse ['Asu'] = $element->gold;
				$usersList .= parsetemplate ( gettemplate ( '/admin/plus_give_gold_user_list' ), $parse );
				$no ++;
			}
			$parse ['view_users_list'] = $usersList;
			//START: paging
			$b = "'_top'";
			$string = 'onchange="javscript:window.open(' . $a . ',' . $b . ')"';
			$parse ['pagenumber'] = '' . $lang ['40'] . '<select name="page" style="width:40px;height=40px;" ' . $string . '>';
			for($i = 1; $i <= ceil ( $sumPlayer / constant ( "MAXROW" ) ); $i ++)
			{
				$parse ['pagenumber'] .= '<option value="' . $i . '"';
				if (empty ( $_GET ["page"] ) && $i == $p + 1) {
					$parse ['pagenumber'] .= ' selected="selected">';
				} elseif ($_GET ["page"] == $i) {
					$parse ['pagenumber'] .= ' selected="selected">';
				} else {
					$parse ['pagenumber'] .= '>';
				}
				$parse ['pagenumber'] .= '' . $i . '</option>';
			}
			$parse ['pagenumber'] .= '</select>';
			//END: paging
		} else {
			$parse ['pagenumber'] = '';
			$parse ['value_name'] ='';
			$parse ['view_users_list'] = parsetemplate ( gettemplate ( '/admin/plus_give_gold_null' ), $parse );
		}
		$page = parsetemplate ( gettemplate ( '/admin/plus_give_gold' ), $parse );
		displayAdmin ( $page, $lang ['plus'] );
		break;
}
//END: switch case
$page = parsetemplate ( gettemplate ( '/admin/plus' ), $parse );
displayAdmin ( $page, $lang ['plus'] );
?>