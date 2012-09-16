<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php');
require_once($ugamela_root_path . 'includes/common.'.$phpEx);

require_once($ugamela_root_path . 'includes/function_ctc.'.$phpEx);

checkRequestTime();
if(!check_user()){ header("Location: ".$ugamela_root_path."login.php"); }

global $db, $user;

//kiem tra xem mo cong thanh chien chua:
if(checkOpen_ctc()){
	if(!checkTheBai_ctc($user['id'])){header("Location: ctc.php");}
}	
//echo "<pre>"; print_r($user); die();

includeLang('ctc');
$parse 	= $lang;

$id = 1;

if(is_numeric($_GET['id']) && $_GET['id']){
	$id = $_GET['id'];
	
	//lay id cua 2 phe trong chien truong nay:
	$side = getSideByCTId_ctc($id);
	
	//Lay danh sach ali ben cong:
	$alas = getAliOfSide_ctc($side[1]->id);
	if($alas){
		$litag = gettemplate("ctc/ctc_li_a_tag");
		//echo $litag; die();
		foreach($alas as $al){
			$parse['ali_attack_list'] .= parsetemplate($litag, array("href"=>"javascript:void(0)", "string"=>$al->name));
		}
	}else{
		$parse['ali_attack_list'] = "";
	}
	
	//Lay danh sach ali ben thu:
	$alds = getAliOfSide_ctc($side[0]->id);
	if($alds){
		$litag = gettemplate("ctc/ctc_li_a_tag");
		//echo $litag; die();
		foreach($alds as $al){
			$parse['ali_defend_list'] .= parsetemplate($litag, array("href"=>"javascript:void(0)", "string"=>$al->name));
		}
	}else{
		$parse['ali_defend_list'] = "";
	}
	
	$parse['ct_id'] = $ct_id = $_GET['id'];
	$parse['image_name'] = $ct_id = $_GET['id'];
	
	$listDTK = getListDTK($ct_id);
	$i = 1;
	foreach($listDTK as $dtk){
		$parse['dtk_'.$i] = $dtk->id;
		$i++;
	}
	
}else{
	header("Location: ctc.php");
}

$parse['id'] = $id;

//echo "<pre>"; print_r($alas); die();
for($i=1; $i<=5; $i++){
	$parse['ctn_class_'.$i] = 'mn';
}

$parse['ctn_class_'.$id] = 'selected';

$parse += getPointTable_ctc($id);

$page	= parsetemplate(gettemplate('ctc/ctc_ct_body'), $parse);

display_ctc($page);

ob_end_flush();
?>