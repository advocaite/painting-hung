<?php
/*
	Plugin Name: bad_list_detail.php
	Plugin URI: http://asuwa.net/administrator/bad_list_detail.php
	Description: 
	+ Hien thi danh sach tat ca user cung su dung chung mot dia chi IP
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_profile.php');
include($ugamela_root_path . 'includes/function_badlist.php');

if(!check_user()){ header("Location: login.php"); }
global $user, $lang;
if($user['authlevel']!=5){ header("Location: login.php"); }
includeLang('userlist');
$parse = $lang;
define('MAXROW',12);

//START: get for paging
if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
	$x=0;
}else{
	$x=($_GET["page"]-1)*constant("MAXROW");
}
//END: get for paging

//START: get ip
if(isset($_GET['ip'])){
	$ip = $_GET['ip'];
}	
//END: get ip

$sql = "SELECT username,count(id) as num FROM wg_securities WHERE ip = '".$ip."' 
		AND username != '' AND username != 'admin' AND username != 'Admin' AND username != 'GameMaster1'
		AND username != 'GameMaster2' AND username != 'GameMaster3' AND username != 'GameMaster4' 
		GROUP BY username LIMIT ".$x.",".constant("MAXROW")."";	
$db->setQuery($sql);
$elements = $db->loadObjectList();
//START: parse list of security
$parse['ip_row'] = $ip;
if($elements)
{
	$arraySameIP = array();
	$no = 1;	
	$totalRecord = 0;		
	foreach ($elements as $element)
	{
		$parse['no'] = $no;		
		$parse['username_row'] = $element->username."<br/>";
		$parse['username'] = $element->username;
		$parse['amount'] = $element->num."<br/>";				
		$list .= parsetemplate(gettemplate('admin/bad_list_detail_row'), $parse);
		$totalRecord +=1;		
		$no ++;
	}	
	$parse['list'] = $list;
	//START: total record
	$parse['total_record']= "<b>".$lang['Total record'].": ".$totalRecord."</b>";
	$totalPage = ceil($totalRecord / constant("MAXROW"));
	$parse['total_page'] = $totalPage;
	//END: total record	
	
	//START: paging
	$a="'bad_list_detail.php?page='+this.options[this.selectedIndex].value";
	$b="'_top'";
	$string='onchange="javscript:window.open('.$a.','.$b.')"';
	$parse['pagenumber']=''.$lang['40'].'<select name="page" style="width:40px;height=40px;" '.$string.'>';
	for($i=1;$i<=ceil($totalRecord/constant("MAXROW"));$i++){
		$parse['pagenumber'].='<option value="'.$i.'"';
		if($_GET["page"]==$i){
			$parse['pagenumber'].=' selected="selected">';
		}else{
			$parse['pagenumber'].='>';
		}						
		$parse['pagenumber'].=''.$i.'</option>';
	}
	$parse['pagenumber'].='</select>';
	//END: paging
}		
//END: parse list of security

$parse['value_ch'] = $ip;

$page = parsetemplate(gettemplate('/admin/bad_list_detail'), $parse);
displayAdmin($page,$lang['userlist']);
?>