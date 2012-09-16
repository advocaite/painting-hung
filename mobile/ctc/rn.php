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

if(checkOpen_ctc()){
		if(is_numeric($_GET['id']) && is_numeric($_GET['sid'])){
		//kiem tra user nay co phai la minh chu hay ko
		if(checkMinhChu_ctc($user['id'])){
			//kiem tra lien minh nay co phai la chu cong hoac chu thu hay ko:
			$sid = $db->getEscaped($_GET['sid']);
			$sql = "SELECT * FROM wg_ctc_phe WHERE id=$sid";
			$db->setQuery($sql);
			$db->loadObject($side);
			if($side && $user['alliance_id']==$side->chu){
				if($_REQUEST['t']==2){
					echo '<a href="javascript:void(0)" >'.$side->name.'</a>';
				}else{
					$nn = $db->getEscaped($_GET['nn']);
					if($nn && $nn!=$side->name){				
						updateSideName_ctc($side->id, $nn);
						$side->name = $nn;
					}else{
						
					}
					$parse['on'] = $side->name;
					$parse['id'] = $_GET['id'];
					$parse['sid'] = $sid;
					$parse['s']  = $side->cong_thu;
					$page = parsetemplate(gettemplate("ctc/ctc_rn"), $parse);
					echo $page;
				}					
			}else{
				echo $lang['ko_duoc_doi_ten'];
			}
		}else{
			echo $lang['ko_duoc_doi_ten'];
		}
	}
}else{
	echo $lang['chua_mo_cua_ctc'];
}

//echo "<pre>"; print_r($_GET); die();
//echo "AAAAAAAAAAAAAAAAAAAAAAA";
?>