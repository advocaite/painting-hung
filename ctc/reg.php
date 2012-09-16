<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php');
require_once($ugamela_root_path . 'includes/common.'.$phpEx);

require_once($ugamela_root_path . 'includes/func_security.'.$phpEx);

require_once($ugamela_root_path . 'includes/function_ctc.'.$phpEx);

checkRequestTime();
if(!check_user()){ header("Location: ".$ugamela_root_path."login.php"); }

global $db;

includeLang('ctc');

$parse 	= $lang;

$id = $_REQUEST['id'] ? $_REQUEST['id'] : 1;

$parse["id"] = $id;
$parse['list_ali'] = "";
$parse['reg_form'] = "";
$parse['message'] = "";

if(checkOpen_ctc()){
	
	$tb=checkTheBai_ctc($user['id']);
	if($tb){
		$parse['check_message'] = 0;
	}else{
		echo "<center>".$lang['ctc_card_error']."</center>"; die();
	}
	//Hi?n th? danh sách lien minh 2 bên:
	$sides = getSideByCTId_ctc($id);
	$attackAlis = getAliOfSide_ctc($sides[1]->id);
	$defendAlis = getAliOfSide_ctc($sides[0]->id);
	if($attackAlis || $defendAlis){
		$i=0;
		while($attackAlis[$i] || $defendAlis[$i]){
			$parse['rows'] .= "<tr>";
			$parse['rows'] .="<td>".$attackAlis[$i]->name."</td>";
			$parse['rows'] .="<td>".$defendAlis[$i]->name."</td>";
			$parse['rows'] .= "</tr>";
			$i++;
		}
		
		$chienTruong = getCT_ctc($id);
		$parse['ten_chien_truong'] = $chienTruong->name;
		if($attackAlis){
			
			$sumAliAttack = count($attackAlis);
		}else{
			
			$sumAliAttack = 0;
		}
		
		if($defendAlis){
			
			$sumAliDefend = count($defendAlis);
		}else{
			
			$sumAliDefend = 0;
		}
		
		$parse['list_ali'] = parsetemplate(gettemplate("ctc/ctc_ali_side_table"), $parse);
	}
	
	
	//echo $sumAliAttack." ".$sumAliDefend." "."<pre>"; print_r($attackAlis); die();
	
	
	//-----------------Hien thi form dang ky---------------------->
	//kiem tra dieu kien dang ky:
	if(checkCtAvaiable_ctc($id) && $user['alliance_id'] && !getAlliePhe_ctc($user['alliance_id']) && checkMinhChu_ctc($user['id'])){		
		if($tb==3){
			//buoc 2:
			if($_REQUEST['ct_id']){
				$chienTruongId = $_REQUEST['ct_id'];
				$congThu = $_REQUEST['p'];
				//Kiem tra ti le giua so lien minh cong va thu:				
				$sub = $congThu ? abs($sumAliAttack-$sumAliDefend+1) : abs($sumAliAttack-$sumAliDefend-1);
				if($sub<=1){
					$parse['message'] = regAllie_ctc($chienTruongId, $congThu)."<br />";
				}else{					
					$parse['message'] =  "<center>".$lang['lua_chon_khong_phu_hop']."</center>"."<br />";
					$parse['reg_form']	= parsetemplate(gettemplate('ctc/ctc_reg_form'), $parse);
				}				
			}else{
				$parse['reg_form']	= parsetemplate(gettemplate('ctc/ctc_reg_form'), $parse);
			}				
		}else{
			$parse['message'] =  "<center>".$lang['ctc_card_error_ali']."</center>"."<br />";
		}
	}
}else{
	$parse['message'] = $lang['chua_mo_cua_ctc']."<br />";
}



$page	= parsetemplate(gettemplate('ctc/ctc_ct_ifo_body'), $parse);

echo $page;

ob_end_flush();
?>