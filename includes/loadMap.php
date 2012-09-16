<?php
define('INSIDE', true);
if(!isset($_GET['x']) || !isset($_GET['y']) || !isset($_GET['size']))
{
	echo '';
}else{
	$ugamela_root_path = './../';
	include($ugamela_root_path . 'extension.inc');
	include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
	include($ugamela_root_path . 'includes/common.'.$phpEx);
	
	$theuser = explode(" ",$_COOKIE[$game_config['COOKIE_NAME']]);
	global $user_id,$ally_id,$game_config;
	($user_id = $theuser[0])or die('Hacking attempt');
	$x =  $_GET['x'];$y =  $_GET['y'];$size =  $_GET['size'];
	
	$sql = "SELECT `user_id`,`ally_id` FROM wg_ally_members WHERE right_=1";
	$db->setQuery($sql);
	$u_ally = $u_allys= NULL;
	$u_ally = $db->loadObjectList();
	foreach($u_ally as $v)
	{
		$u_allys[$v->user_id] = $v->ally_id;
	}
	$ally_id =$u_allys[$user_id];
	
	$kind_villas = array(    '1'      => 'd04','o11',				'o6',					'o12',				'o3',				'o4',				'o1',				'o3',				'o4',				'o6',				'o7',				'o9',				'o10',				'o12',					);
	
	if($size ==13){ $class = '<img class="bmt';}
	else{  $class = '<img class="mt';}	
	$MAX_X=$game_config['max_x'];
	$MAX_Y=$game_config['max_y'];
	$dx = ceil($size/2);
	$dy = ceil($size/2);		
	for($i=$x-$dx+1;$i<$x+$dx;$i++)
	{		
		for($j=$y-$dy+1;$j<$y+$dy;$j++)
		{			
			$v_id = ($i+($MAX_X+6))*($MAX_X*2+13)+($j+($MAX_Y+7));				
			$where_ .=$v_id.",";		
		}		
	}		
	$where_ = substr($where_,0,-1);
	$sql = "SELECT x,y,id,user_id,kind_id,workers FROM wg_villages_map 
	WHERE id IN(".$where_.") order by y DESC,x ASC";
	$db->setQuery($sql);
	$villas = NULL;
	$villas = $db->loadObjectList();
	$i=1;
	foreach($villas as $villa)
	{		
		echo $class.$i.'" id="Img_'.$villa->x.'_'.$villa->y.'" src="images/un/m/'.getImage($villa->user_id,$villa->kind_id,$villa->workers,$u_allys).'.gif"/>';
		$i++;	
	}			
}
function getImage($u_id,$kind_id,$workers,$u_allys)
{
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
		if($ally_id)
		{				
			if($ally_id == $u_allys[$u_id])
			{
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
?>