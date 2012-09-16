<?php
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = '../';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php');
require_once($ugamela_root_path . 'includes/common.'.$phpEx);

require_once($ugamela_root_path . 'includes/function_troop.'.$phpEx);
require_once($ugamela_root_path . 'includes/func_security.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_ctc.'.$phpEx);

checkRequestTime();

if(!check_user()){ header("Location: ".$ugamela_root_path."login.php"); }

global $db, $user;

includeLang('ctc');
$parse 	= $lang;

//kiem tra xem mo cua cong thanh chien chua:
if(checkOpen_ctc()){
	if($_REQUEST['id'] && is_numeric($_REQUEST['id'])){
		$id = intval($_REQUEST['id']);
		$id = $db->getEscaped($id);
		$timer = 6;
		$sql = "SELECT * FROM wg_ctc_diem_tap_ket WHERE id = $id";
		$db->setQuery($sql);
		$db->loadObject($dtk);
		if($dtk){						
			$vls = getVillageOfUser_ctc($user['id']);
			if($_REQUEST['t']){
				switch($_REQUEST['t']){
					case 1: //hien thi quan doi
						//kiem tra xem lien minh cua user nay co tham gia chien truong nay khong:
						$side = getAlliePhe_ctc($user['alliance_id']);
						if($side){
							if($side->ct_id == $dtk->ct_id){
								
								$art = getArrayOfTroops();
								$parse['task_content']	.= showTroopInDTK_ctc($vls, $dtk);
								if(($dtk->cung==0 && $side->cong_thu==1) || ($dtk->cung==6 && $side->cong_thu==0)){
									$vlt = showTroopInVillage_ctc($vls, $dtk);
									if($vlt && $parse['task_content']){
										$parse['task_content'].="<br>";
									}
									$parse['task_content'] .= $vlt;
								}
								
								//neu la minh chu thi se thay quan doi cua taon lien minh
								if(checkMinhChu_ctc($user['id'])){
									$alit = showTroopAli_ctc($user['alliance_id'], $dtk->id, $art);
									if($alit && $parse['task_content']){
										$parse['task_content'] .= "<br>";
									}
									$parse['task_content'] .= $alit;
								}
								
								if(!$parse['task_content']){
									$parse['task_content'] .= $lang['khong_co_quan_trong_dtk'];
								}								
							}else{
								$parse['task_content'] = $lang['ms_ko_duoc_dieu_quan'];
							}
						}else{
							$parse['task_content'] = $lang['ms_chua_dang_ky'];
						}								
						break;
					case 2:	//Dieu quan
						$parse['task_content']	= sendTroop_ctc($dtk);
						break;
					case 3: //hien thi trang thai linh dang di chuyen
						$parse['task_content']	= showTroopOnTheWay_ctc($vls, $dtk, $timer);
						$parse['task_content']	.= showTroopIncoming_ctc($vls, $dtk, $timer);
						break;
					case 4: //xem thong tin quan doi cua mot user (chi danh cho minh chu)
						$uid = intval($_REQUEST['uid']);
						$vlos = getVillageOfUser_ctc($uid);
						if((checkMinhChu_ctc($user['id']) && checkSameSide_ctc($user['id'], $uid))|| $uid==$user['id']){
							$parse['task_content']	= showTroopOtherUser_ctc($vlos, $dtk, 1);
						}else{
							$parse['task_content']	= showTroopOtherUser_ctc($vlos, $dtk, 0);
						}
						break;
					case 5:
						$s = intval($_REQUEST['s']);
						$timer = 1;
						$side = getSideByCTId_ctc($dtk->ct_id);
						$tms = showTroopMoveStatus_ctc($dtk->id, $side, $timer);
						if($s){
							$parse['task_content']	= "<table>".$tms['attack_troop_move_status']."</table>";
						}else{
							$parse['task_content']	= "<table>".$tms['defend_troop_move_status']."</table>";
						}
						break;
				}
			}
		}		
			
	}else{
		echo "ha ha!";
	}
	
	echo parsetemplate(gettemplate('ctc/ctc_troop_body'), $parse);
}else{
	echo $lang['chua_mo_cua_ctc'];
}
	
ob_end_flush();
?>