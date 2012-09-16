<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php'); 
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/func_build.'.$phpEx);
checkRequestTime();
if(!check_user()){ header("Location: login.php"); }
function saveFile($filename,$content)
{
	$f = fopen($filename, "w") or exit("Khong the mo file!");
	fputs($f,"$content");
	fclose($f);
	return true;
}
function returnId($id)
{
	if($id<=1)
		return 1;
	elseif($id>=33)
		return 33;
	return $id;
}
function createList($objResSts)
{
global $lang;
$parse=$lang;
$parent='<?xml version="1.0" encoding="utf-8"?>
<category>';
	foreach($objResSts as $key1=>$query)
	{	
		if($key1<=10)
		{	
			$list_sunda.='<div><img src="'.$query->icon.'" style="border: 0px none ; height: 16px; width: 16px;"><span class="style5">&nbsp;<a href="viewtroop.php?id='.($key1+1).'">'.$lang[$query->name].'</a></span></div>';
		}
		elseif($key1<=21)
		{
			$list_arabia.='<div><img src="'.$query->icon.'" style="border: 0px none ; height: 16px; width: 16px;"><span class="style5">&nbsp;<a href="viewtroop.php?id='.($key1+1).'">'.$lang[$query->name].'</a></span></div>';
		}
		else
		{
			$list_mongo.='<div><img src="'.$query->icon.'" style="border: 0px none ; height: 16px; width: 16px;"><span class="style5">&nbsp;<a href="viewtroop.php?id='.($key1+1).'">'.$lang[$query->name].'</a></span></div>';
		}		
	}
	$parse['list_sunda']=$list_sunda;
	$parse['list_arabia']=$list_arabia;
	$parse['list_mongo']=$list_mongo;
	$content=parsetemplate(gettemplate('helptrooplist'), $parse);		
	$parent='<node>
				<content><![CDATA['.$content.']]></content>
	    </node>';
	if(saveFile('../xml/troopvn0.xml',$parent))
	{
		echo '<h4>Ghi File troopvn.xml Thanh Cong</h4>';
	}
	else
	{
		echo '<h4>Ghi troopvn.xml That Bai</h4>';
	}
	return true;	
}	
global $db,$game_config;
includeLang('troopdetail');
$parse=$lang;
$sql='SELECT * FROM wg_troops WHERE id<34';
$db->setQuery($sql);
$objResSts = $db->loadObjectList();	
if($objResSts)
{
	createList($objResSts);
	foreach($objResSts as $key=>$query)
	{
$list='<?xml version="1.0" encoding="utf-8"?>
<note>';
		$parse['prew']=returnId($key);
		$parse['next']=returnId($key+2);
		$parse['nametroop']=$lang[$query->name];
		$parse['request']=$lang[$query->name.'a'];
		$parse['content']=$lang[$query->name.'b'];
		$parse['need_train']=$lang[$query->name.'c'];
		$parse['images']='images/troops/'.$query->name.'.png';
		$parse['melee_defense']=$query->melee_defense;
		$parse['attack']=$query->attack;
		$parse['magic_defense']=$query->magic_defense;
		$parse['ranger_defense']=$query->ranger_defense;
		$parse['rs1']=$query->rs1;
		$parse['rs2']=$query->rs2;
		$parse['rs3']=$query->rs3;
		$parse['rs4']=$query->rs4;
		$parse['rsrs1']=$query->rsrs1;
		$parse['rsrs2']=$query->rsrs2;
		$parse['rsrs3']=$query->rsrs3;
		$parse['rsrs4']=$query->rsrs4;
		$parse['time_research']=ReturnTime(round($query->time_research/$game_config['k_research']));
		$parse['time_train']=ReturnTime(round($query->time_train/$game_config['k_train']));
		$parse['keep_hour']=$query->keep_hour;
		$parse['carry']=$query->carry;
		$parse['speed']=$query->speed*$game_config['k_speed'];
		$content=parsetemplate(gettemplate('helptroop'), $parse);
$list.='<content><![CDATA['.$content.']]></content>
</note>';
		if(saveFile('../xml/troopvn'.($key+1).'.xml',$list))
		{
			echo '<h4>Ghi File troopvn'.($key+1).'.xml Thanh Cong</h4>';
		}
		else
		{
			echo '<h4>Ghi troopvn'.($key+1).'.xml That Bai</h4>';
		}
	}
}
ob_end_flush();
?>

