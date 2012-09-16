<?php define('INSIDE', true);
$ugamela_root_path = './../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
$theuser = explode(" ",$_COOKIE[$game_config['COOKIE_NAME']]);
global $user_id,$ally_id;
($user_id = $theuser[0])or die('hack');
$x =  $_POST['x'];$y =  $_POST['y'];$size =  $_POST['size'];
$task = $_POST['task'];$x =  $_GET['x'];$y =  $_GET['y'];$size =  $_GET['size'];
$task = $_GET['task'];

$sql = "SELECT ally_id FROM wg_ally_members WHERE (user_id=$user_id)";
$db->setQuery($sql);
$ally_id = $db->loadResult();
$kind_villas = array(    '1'      => 'd04','o11',				'o6',					'o12',				'o3',				'o4',				'o1',				'o3',				'o4',				'o6',				'o7',				'o9',				'o10',				'o12',					);
$MAX_X=$game_config['max_x'];
$MAX_Y=$game_config['max_y'];
switch($task){		
	case 'north':		
		$dx = ceil($size/2);
		$dy = floor($size/2);
		$j=$y+$dy;		
		$where_ = " id = ".((($x-$dx+1)+($MAX_X+6))*($MAX_X*2+13)+($j+($MAX_Y+7)));		
		for($i=$x-$dx+2;$i<$x+$dx;$i++){			
			$v_id = ($i+$MAX_X+6)*($MAX_X*2+13)+($j+($MAX_Y+7));			
			$where_ .= " or id = ".$v_id;		
		}
		$sql = "SELECT user_id,kind_id,workers FROM wg_villages_map WHERE (".$where_.") order by x ASC";
		$db->setQuery($sql);		$villas = $db->loadObjectList();		
		foreach($villas as $villa){
			echo ";".getImage($villa->user_id,$villa->kind_id,$villa->workers);		
		}		break;	
	case 'east':
		$dx = floor($size/2);
		$dy = ceil($size/2);
		$i=$x+$dx;
		$where_ = " id = ".(($i+($MAX_X+6))*($MAX_X*2+13)+($y-$dy+($MAX_Y+8)));
		for($j=$y-$dy+2;$j<$y+$dy;$j++){
			$v_id = ($i+($MAX_X+6))*($MAX_X*2+13)+($j+($MAX_Y+7));
			$where_ .= " or id = ".$v_id;
		}
		$sql = "SELECT user_id,kind_id,workers FROM wg_villages_map WHERE (".$where_.") order by y DESC";
		$db->setQuery($sql);
		$villas = $db->loadObjectList();
		foreach($villas as $villa){
			echo ";".getImage($villa->user_id,$villa->kind_id,$villa->workers);
		}	break;	
	case 'south':
		$dx = ceil($size/2);
		$dy = floor($size/2);
		$j=$y-$dy;
		$where_ = " id = ".((($x-$dx+1)+($MAX_X+6))*($MAX_X*2+13)+($j+($MAX_Y+7)));
		for($i=$x-$dx+2;$i<$x+$dx;$i++){
			$v_id = ($i+($MAX_X+6))*($MAX_X*2+13)+($j+($MAX_Y+7));
			$where_ .= " or id = ".$v_id;
		}
		$sql = "SELECT user_id,kind_id,workers FROM wg_villages_map WHERE (".$where_.") order by x ASC";
		$db->setQuery($sql);
		$villas = $db->loadObjectList();
		foreach($villas as $villa){
			echo ";".getImage($villa->user_id,$villa->kind_id,$villa->workers);
		}		break;	
	case 'west':
		$dx = floor($size/2);
		$dy = ceil($size/2);
		$i=$x-$dx;
		$where_ = " id = ".(($i+($MAX_X+6))*($MAX_X*2+13)+($y-$dy+($MAX_Y+8)));
		for($j=$y-$dy+2;$j<$y+$dy;$j++){
			$v_id = ($i+($MAX_X+6))*($MAX_X*2+13)+($j+($MAX_Y+7));
			$where_ .= " or id = ".$v_id;
		}
		$sql = "SELECT user_id,kind_id,workers FROM wg_villages_map WHERE (".$where_.") order by y DESC";
		$db->setQuery($sql);
		$villas = $db->loadObjectList();
		foreach($villas as $villa){
			echo ";".getImage($villa->user_id,$villa->kind_id,$villa->workers);
		}		break;	
	default:
		$dx = ceil($size/2);
		$dy = ceil($size/2);		
		for($i=$x-$dx+1;$i<$x+$dx;$i++){		
			for($j=$y-$dy+1;$j<$y+$dy;$j++){			
				$v_id = ($i+($MAX_X+6))*($MAX_X*2+13)+($j+($MAX_Y+7));				
				$where_ .= " or id = ".$v_id;			
			}		
		}		
		$where_ = substr($where_,4);		
		$sql = "SELECT user_id,kind_id,workers FROM wg_villages_map WHERE (".$where_.") order by y DESC,x ASC";		
		$db->setQuery($sql);		$villas = $db->loadObjectList();	
		foreach($villas as $villa){		
			echo ";".getImage($villa->user_id,$villa->kind_id,$villa->workers);		
		}
	}
function getImage($u_id,$kind_id,$workers){
		global $user_id,$ally_id,$kind_villas,$db;
		if($kind_id>6){
			return $kind_villas[$kind_id];
		}else if($user_id==$u_id){
			$d=floor($workers/250);$d=($d>3)?3:$d;
			return "d".$d."0";
		}else if(!$u_id){
			return "t".($kind_id%9);
		}else{
		 	$d=floor($workers/250);
			$d=($d>3)?3:$d;
			$temp = "d".$d;
			if($ally_id){
				$sql = "SELECT ally_id FROM wg_ally_members WHERE (user_id=$u_id) AND (right_=1)";
				$db->setQuery($sql);
				$u_ally = null;
				$db->loadObject($u_ally);
				if($ally_id == $u_ally->ally_id){
					$temp .= "4";
				}else{
					$temp .= "1";
				}
			}else{
				$temp .= "1";
			}
			return $temp;
		}
	}
function getTooltip(){
}
?>