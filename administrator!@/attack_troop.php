<?php
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
if(!check_user()){ header("Location: login.php"); }

if($user['authlevel'] <5)
{
	header("Location:index.php");
	exit();
}
else
{
	includeLang('status');
	$parse = $lang;		
	if($_POST['backup'])
	{
		$sql="SELECT * FROM wg_attack_troop WHERE status=1 ORDER BY id ASC LIMIT 0,3000";
		$db->setQuery($sql);
		$elements=NULL;
		$elements=$db->loadObjectList();		
		if($elements)
		{
			foreach ($elements as $element)
			{
				$sql="INSERT INTO `wg_attack_troop_backup` 
				(`id`,`troop_id`, `num`, `die_num`, `attack_id`, `hero_id`, `status`) 
				VALUES (".$element->id.",".$element->troop_id.",".$element->num.",".$element->die_num.",".$element->attack_id.",".$element->hero_id.",".$element->status.")";
				$db->setQuery($sql);
				if($db->query())
				{	
					$sql="DELETE FROM wg_attack_troop WHERE id=".$element->id;
					$db->setQuery($sql);
					$db->query();
					unset($element);
				}
			}
		}
	}
	define('MAXROW',15);
	if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
		$x=0;
	}
	else
	{
		$x=($_GET["page"]-1)*constant("MAXROW");
	}
	$char='attack_troop.php?page';
	$template='/admin/attack_troop_list';
	$table='wg_attack_troop';
	if(isset($_GET['s']))
	{
		$template='/admin/attack_troop_list_bk';
		$table='wg_attack_troop_backup';
		$char='attack_troop.php?s=1&page';
	}		
	$sqlSum = "SELECT COUNT(DISTINCT(id)) AS status1 ,
	(SELECT COUNT(DISTINCT(id)) FROM ".$table." WHERE status = 0) AS status0 
	FROM ".$table." WHERE status = 1";
	$db->setQuery($sqlSum);
	$db->loadObject($wg_attack_troop);
	$parse['status1']=$wg_attack_troop->status1;
	$parse['status0']=$wg_attack_troop->status0;
	$parse['total_page']=ceil(($wg_attack_troop->status1+$wg_attack_troop->status0)/constant("MAXROW"));
	
	$sql="SELECT * FROM ".$table." ORDER BY id DESC LIMIT ".$x.",".constant("MAXROW")."";
	$db->setQuery($sql);
	$elements=$db->loadObjectList();
	if($elements)
	{
		$no = 1;
		foreach ($elements as $element)
		{
			$parse['no']=$x+$no;
			$parse['status']=$element->status;
			$parse['troop_id']=$element->troop_id;
			$parse['num']=$element->num;
			$parse['die_num']=$element->die_num;
			$parse['attack_id']=$element->attack_id;
			$parse['hero_id']=$element->hero_id;			
			$attack_list .= parsetemplate (gettemplate('/admin/attack_troop_list_row'), $parse );
			$no++;
		}	
		$parse['view_attack_troop_list']=$attack_list;
		$a="'".$char."'+this.options[this.selectedIndex].value";
		$b="'_top'";
		$string='onchange="javascript:window.open('.$a.','.$b.')"';
		$parse['pagenumber']=''.$lang['40'].' <select name="page" style="width:40px;height=40px;" '.$string.'>';
		for($i=1;$i<=ceil(($wg_attack_troop->status1+$wg_attack_troop->status0)/constant("MAXROW"));$i++){
			$parse['pagenumber'].='<option value="'.$i.'"';
			if(empty($_GET["page"]) && $i==$p+1){
				$parse['pagenumber'].='selected="selected">';
			}elseif($_GET["page"]==$i){
				$parse['pagenumber'].='selected="selected">';
			}else{
				$parse['pagenumber'].='>';
			}						
			$parse['pagenumber'].=''.$i.'</option>';
		}
		$parse['pagenumber'].='</select>';	
	}
	else
	{
		$parse['pagenumber']='';
		$parse['view_attack_troop_list']=parsetemplate(gettemplate('/admin/attack_troop_null'),$parse);						
	}
	$page = parsetemplate(gettemplate($template), $parse);
	displayAdmin($page,$lang['status']);	
}
?>