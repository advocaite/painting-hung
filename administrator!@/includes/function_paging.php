<?php
/*
	Plugin Name: function_tracking.php
	Plugin URI: http://asuwa.net/administrator/function_tracking.php
	Description: cac ham cho viec tracking user
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);

function paging($fileName, $totalRecord, $numberRecordPerPage)
{
	global $lang;
	$parse = $lang;	
	$a="'".$fileName."&page='+this.options[this.selectedIndex].value";
	$b="'_top'";
	$string='onchange="javascript:window.open('.$a.','.$b.')"';
	$parse['pagenumber']=''.$lang['40'].' <select name="page" style="width:40px;height=40px;" '.$string.'>';
	for($i=1;$i<=ceil($totalRecord/$numberRecordPerPage);$i++)
	{
		$parse['pagenumber'].='<option value="'.$i.'"';
		if($_GET["page"]==$i){
			$parse['pagenumber'].=' selected="selected">';
		}else{
			$parse['pagenumber'].='>';
		}						
		$parse['pagenumber'].=''.$i.'</option>';
	}
	$parse['pagenumber'].='</select>';
	$parse['total_page']=ceil($totalRecord/$numberRecordPerPage);
	$page = parsetemplate(gettemplate('/admin/paging'), $parse);
	return $page;
}
?>