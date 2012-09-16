<?php
/*
	Plugin Name: allianz.php
	Plugin URI: http://asuwa.net/allianz.php
	Description: 
	+ Hien thi nhung thong tin chi tiet ve lien minh
	+ Hien thi danh sach thanh vien cua lien minh
	+ Hien thi danh sach nhung cuoc tan cong cua lien minh: user nao tan cong user nao, thuoc lien minh nao, thoi 	  gian xay ra tan cong luc nao
	+ Hien thi tin tuc cua lien minh: moi thanh vien, tham gia lien minh, thoat khoai lien minh
	+ Menu chuc nang cua lien minh (phu thuoc vao quyen han cua user do)
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/
session_start();
define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/func_build.php');
include($ugamela_root_path . 'includes/function_allian.php');
require_once($ugamela_root_path . 'includes/function_attack.php');
require_once($ugamela_root_path . 'includes/function_status.php');
require_once($ugamela_root_path . 'includes/function_troop.php');
require_once($ugamela_root_path . 'includes/function_resource.php');
include($ugamela_root_path . 'includes/wordFilter.php');


if(!check_user()){ header("Location: login.$phpEx"); }


includeLang('allianz');
global $db,$user, $allyId, $wg_village,$wg_buildings, $lang;

$village = $_COOKIE['villa_id_cookie'];
$wg_buildings = null;
$wg_village = null;
$wg_village = getVillage($village);
$wg_buildings = getBuildings($village);
getSumCapacity($wg_village, $wg_buildings);
UpdateRS($wg_village, $wg_buildings, time());
//END: dung chung

$parse=$lang;
$userId = $user['id']; //id of user

$parse['allian_menu'] = "";
$parse['online_status_1'] = "";
$parse['online_status_2'] = "";
$parse['br'] = "";

//START: show menu move capital
$parse['move_capital']= "";
$sql="SELECT villages_id, sum_villages FROM wg_users WHERE id = ".$userId;
$db->setQuery($sql); 
$db->loadObject($vl_id);	
if($vl_id->sum_villages > 1){
	$parse['move_capital']= $lang['Move capital'];
}
//END: show menu move capital
		
// START: get ally_id from request
if(isset($_GET['aid'])){
	$allyId = $db->getEscaped($_GET['aid']);
	$parse['br'] = "<br/>";
	// Get ally id
	$getAllyId = getInfoFromAllyMember("ally_id", "user_id =".$userId." AND right_=1");	
	if($getAllyId->ally_id == $allyId){
		$parse['br'] = "";
		$parse['overview_menu'] = $lang['Overview'];
		$parse['giao_tranh_menu'] = $lang['giao_tranh'];
		$parse['news_menu'] = $lang['News'];
		$parse['options_menu'] = $lang['Options'];			
	}else{
		$parse['br'] = "";
		$parse['not_menu'] = "style=\"visibility:hidden; margin-bottom:-10px\"";		
	}
}else{
	// Get ally id
	$getAllyId = getInfoFromAllyMember("ally_id", "user_id =".$userId." AND right_=1");
	$allyId = $getAllyId->ally_id;
	if(empty($allyId)){
		header("Location: village1.php");
	}
	$parse['overview_menu'] = $lang['Overview'];
	$parse['giao_tranh_menu'] = $lang['giao_tranh'];
	$parse['news_menu'] = $lang['News'];
	$parse['options_menu'] = $lang['Options'];	
}
// END: get ally_id from request

$parse['ally_id'] = $allyId;

//get all ally info
$infoAllian = getInfoFromAlly("*", "id = ".$allyId."");
$parse['valu_allain'] = $infoAllian->name;
//get ally member info
$infoAllianMember = getInfoFromAllyMember("count(id) as mk", "ally_id = ".$allyId." AND right_ = 1");

// START: delete invitation
if(!isset($_SESSION['password_viewuser']) && $_GET['act']== "del_invite")
{
	$sql="DELETE FROM wg_ally_members WHERE id=".$db->getEscaped($_GET['id']);
	$db->setQuery($sql);
	$db->query();
	header("Location: allianz.php?event=6");
}
// END: delete invitation

// START: delete diplomacy
if(!isset($_SESSION['password_viewuser']) && $_GET['act']== "del_diplomacy")
{
	$id = $db->getEscaped($_GET['id']);
	$sql="DELETE FROM wg_ally_relation WHERE id=$id";
	$db->setQuery($sql);
	$db->query();	
	header("Location: allianz.php?event=5");
}
// END: delete diplomacy

// START: accept diplomacy
if(!isset($_SESSION['password_viewuser']) && $_GET['act']== "accept_diplomacy")
{
	$id = $db->getEscaped($_GET['id']);
	$sql="UPDATE wg_ally_relation SET status=1 WHERE id=$id";	
	$db->setQuery($sql);
	$db->query();	
	header("Location: allianz.php?event=5");
}
// END: accept diplomacy

// SWITCH CASE
	//case 1: phan quyen
	//case 2: doi ten ally
	//case 3: khai tru thanh vien
	//case 4: doi thong tin gioi thieu
	//case 5: lien ket lien minh
	//case 6: moi thanh vien
	//case 8: thoat khoi lien minh
	//case 10: tan cong
	//case 11: tin tuc
	//case 12: chuc nang
	//case 13: view report attack
//

if(isset($_GET['s']))
{
	$task=$_GET['s'];
}
if(isset($_GET['event']))
{
	$task=$_GET['event'];
}

switch ($task)
{
		
	// START: phan quyen
	case "1":
		$parse['message'] = "";
		$parse['position'] = "";
		$parse['to_username'] = "";
		if($_GET['act']==1){
			$userNameTo = $db->getEscaped($_POST['a_name']);
			$parse['user_name'] = $userNameTo;
			// get user_id by username
			$userIdTo = getUserIdByUserName($userNameTo);
			$sql = "SELECT user_id, privilege, position_name FROM wg_ally_members WHERE ally_id = ".$allyId." AND user_id = ".$userIdTo."";
			$db->setQuery($sql);
			$db->loadObject($allyMember);						
			
			if(empty($allyMember)){
				$parse['message'] = "".$userNameTo." ".$lang['User not in ally']."";
				$parse['to_username'] = $userNameTo;
			}else{
				$parse['position'] = $allyMember->position_name;
				$privilegeArray = str_split($allyMember->privilege);				
				if($privilegeArray[1]==1)
					$parse['assign_to_position_checked'] = "checked";
				if($privilegeArray[2]==1)
					$parse['change_name_checked'] = "checked";
				if($privilegeArray[3]==1)
					$parse['kick_player_checked'] = "checked";
				if($privilegeArray[4]==1)
					$parse['change_des_checked'] = "checked";
				if($privilegeArray[5]==1)
					$parse['diplomacy_checked'] = "checked";
				if($privilegeArray[6]==1)
					$parse['igm_to_member_checked'] = "checked";
				if($privilegeArray[7]==1)
					$parse['invite_player_checked'] = "checked";
				
				$parse['user_id'] = $userIdTo;
				$page = parsetemplate(gettemplate('allian_right'), $parse);
				display($page,$lang['allianz']);
			}
						
		}
		// START: right submit assign to position
		if($_GET['act']==2){
			$rightValue = 0;
			$privileg = "00000000";
			$arrayBit = str_split($privileg);
			$arrayBit[0] = 1;
			if($_POST['assign_to_position']){
				$rightValue += 2;//phan quyen
				$arrayBit[1] = 1;
			}
			if($_POST['change_name']){
				$rightValue += 2;//thay doi ten lien minh
				$arrayBit[2] = 1;
			}
			if($_POST['kick_player']){
				$rightValue += 4;//duoi khoi lien minh
				$arrayBit[3] = 1;
			}
			if($_POST['change_des']){
				$rightValue += 8;//thay doi mieu ta lien minh
				$arrayBit[4] = 1;
			}
			if($_POST['diplomacy'])	{
				$rightValue += 16;//ngoai giao
				$arrayBit[5] = 1;
			}
			if($_POST['igm_to_member'])	{
				$rightValue += 32;//nhan tin den cac thanh vien khac
				$arrayBit[6] = 1;
			}			
			if($_POST['invite_player'])	{
				$rightValue += 64;//moi nguoi khac tham gia
				$arrayBit[7] = 1;
			}
			$positionName = $db->getEscaped($_POST['position_name']);//position name
			//get user_id by username
			$userIdTo = getUserIdByUserName($db->getEscaped($_POST['user_name']));
		
			//update right in ally_option of user
			
			/*$sql="UPDATE wg_ally_members SET position_name = '".$positionName."', 
					privilege = '".implode("",$arrayBit)."'
					WHERE user_id =".$userIdTo;
			$db->setQuery($sql);
			$db->query();*/
			
			header("Location: allianz.php?event=1");
		}
		// END: right submit assign to position
		
		// START: option menu
		//Get right value of user
		$privilege = getUserRight($user['id']);		
		$privilegeArray = str_split($privilege->privilege);
		
		$allyOptionMenu = "";
		$parse['assign_to_position'] = "";
		$parse['change_name'] = "";
		$parse['kick_player'] = "";
		$parse['change_des'] = "";
		$parse['allian_diplomacy'] = "";
		$parse['invite_player']	= "";		
		if($privilegeArray[1] == 1){ //assign to position
			$parse['assign_to_position']= $lang['Assign to position'];
		}
		if($privilegeArray[2] == 1){ //change name
			$parse['change_name']= $lang['Change name'];
		}
		if($privilegeArray[3] == 1){ //kick player
			$parse['kick_player']= $lang['Kick player'];
		}
		if($privilegeArray[4] == 1){ //change des
			$parse['change_des']= $lang['Change alliance description'];
		}
		if($privilegeArray[5] == 1){ //alliance diplomacy
			$parse['allian_diplomacy']= $lang['Alliance diplomacy'];
		}	
		if($privilegeArray[7] == 1){ //invite a player into the alliance
			$parse['invite_player']	= $lang['Invite a player into the alliance'];
		}
		// END: option menu
		
		$page = parsetemplate(gettemplate('allian_assign_position'), $parse);
		display($page,$lang['allianz']);
	break;
	// END: phan quyen
	
	//START: change name of ally
	case "2":
		$parse['message'] = "";
		$parse['tag_allian']=$infoAllian->tag;
		$parse['name_allian']=$infoAllian->name;		
		
		if($_GET['act']==2)
		{
			$nameAllian = $db->getEscaped($_POST['name_allian']);
			$tagAllian = $db->getEscaped($_POST['tag_allian']);
			// get tag ally
			$sql = "SELECT id FROM wg_allies WHERE tag ='".$tagAllian."' AND id != ".$allyId;
			$db->setQuery($sql);
			$checkAllyTag = null;
			$db->loadObject($checkAllyTag);
			// get name ally
			$sql = "SELECT id FROM wg_allies WHERE name ='".$nameAllian."' AND id != ".$allyId;
			$db->setQuery($sql);
			$checkAllyName = null;
			$db->loadObject($checkAllyName);			
			if(!empty($checkAllyTag)){
				$parse['message'] = "".$lang['Key word']." ".$tagAllian." ".$lang['Existed']."";
			}elseif(!empty($checkAllyName))	{
				$parse['message'] = "".$lang['Alliance']." ".$nameAllian." ".$lang['Existed']."";
			}else{			
				if($wordFilters[$nameAllian] !=1 &&  $wordFilters[$tagAllian] !=1){
					/*$sql="UPDATE wg_allies SET name = '".$nameAllian."', tag = '".$tagAllian."' 
																	WHERE id =".$infoAllian->id;
					$db->setQuery ( $sql );
					$db->query ();*/
				}
				header("Location: allianz.php");
			}		
		}
		// START: option menu
		//Get right value of user
		$privilege = getUserRight($user['id']);		
		$privilegeArray = str_split($privilege->privilege);
		
		$allyOptionMenu = "";
		$parse['assign_to_position'] = "";
		$parse['change_name'] = "";
		$parse['kick_player'] = "";
		$parse['change_des'] = "";
		$parse['allian_diplomacy'] = "";
		$parse['invite_player']	= "";		
		if($privilegeArray[1] == 1){ //assign to position
			$parse['assign_to_position']= $lang['Assign to position'];
		}
		if($privilegeArray[2] == 1){ //change name
			$parse['change_name']= $lang['Change name'];
		}
		if($privilegeArray[3] == 1){ //kick player
			$parse['kick_player']= $lang['Kick player'];
		}
		if($privilegeArray[4] == 1){ //change des
			$parse['change_des']= $lang['Change alliance description'];
		}
		if($privilegeArray[5] == 1){ //alliance diplomacy
			$parse['allian_diplomacy']= $lang['Alliance diplomacy'];
		}	
		if($privilegeArray[7] == 1){ //invite a player into the alliance
			$parse['invite_player']	= $lang['Invite a player into the alliance'];
		}
		// END: option menu
		
		$page = parsetemplate(gettemplate('allian_change_name'), $parse);
		display($page,$lang['allianz']);		
	break;
	// END: change name of allys
	
	//START: kich player
	case "3":
		$parse['message'] = "";
		$parse['to_username'] = "";
		if($_GET['act']==1)	{
			$userName = $db->getEscaped($_POST['user_name']);			
			if($userName == $user['username']){
				$parse['message'] = $lang['Can not kick yourself'];
			}else{
				$userId = getUserIdByUserName($userName);
				$parse['kick_player_id'] = $userId;
				$sql = "SELECT COUNT(user_id) FROM wg_ally_members WHERE ally_id = ".$allyId." AND user_id = ".$userId."";
				$db->setQuery ($sql);
				$allianMembers =(int)$db->loadResult();
	
				if($allianMembers == 0)	{
					$parse['message'] = "".$userName." ".$lang['User not in ally']."";
					$parse['to_username'] = $userName;
				}else{
					// START: option menu
					//Get right value of user
					$privilege = getUserRight($user['id']);		
					$privilegeArray = str_split($privilege->privilege);
					
					$allyOptionMenu = "";
					$parse['assign_to_position'] = "";
					$parse['change_name'] = "";
					$parse['kick_player'] = "";
					$parse['change_des'] = "";
					$parse['allian_diplomacy'] = "";
					$parse['invite_player']	= "";		
					if($privilegeArray[1] == 1){ //assign to position
						$parse['assign_to_position']= $lang['Assign to position'];
					}
					if($privilegeArray[2] == 1){ //change name
						$parse['change_name']= $lang['Change name'];
					}
					if($privilegeArray[3] == 1){ //kick player
						$parse['kick_player']= $lang['Kick player'];
					}
					if($privilegeArray[4] == 1){ //change des
						$parse['change_des']= $lang['Change alliance description'];
					}
					if($privilegeArray[5] == 1){ //alliance diplomacy
						$parse['allian_diplomacy']= $lang['Alliance diplomacy'];
					}	
					if($privilegeArray[7] == 1){ //invite a player into the alliance
						$parse['invite_player']	= $lang['Invite a player into the alliance'];
					}
					// END: option menu				
					$page = parsetemplate(gettemplate('allian_kick_player_confirm'), $parse);
					display($page,$lang['allianz']);
				}
			}
		}
			
		//START: confirm pass when kick player
		if($_GET['act']==2)
		{
			if($_SESSION['password_viewuser'] != md5(md5($_POST['kick_player_confirm'])."--" .$dbsettings["secretword"]))
			{
				$parse['message']= $lang['Invalid password'];								
			}else{
				//xoa khoi table wg_ally_members
				/*$sql = "DELETE FROM wg_ally_members WHERE user_id = ".$db->getEscaped($_POST['kick_player_id']);
				$db->setQuery ( $sql );
				$db->query ();	
				//update table wg_users
				$sql="UPDATE wg_users SET alliance_id = 0 WHERE id = ".$db->getEscaped($_POST['kick_player_id']);
				$db->setQuery($sql);
				$db->query();*/	
				header("Location: allianz.php");
			}
		}
		//END: confirm pass when kick player
		
		//START: option menu
		//Get right value of user
		$privilege = getUserRight($user['id']);		
		$privilegeArray = str_split($privilege->privilege);
		
		$allyOptionMenu = "";
		$parse['assign_to_position'] = "";
		$parse['change_name'] = "";
		$parse['kick_player'] = "";
		$parse['change_des'] = "";
		$parse['allian_diplomacy'] = "";
		$parse['invite_player']	= "";		
		if($privilegeArray[1] == 1){ //assign to position
			$parse['assign_to_position']= $lang['Assign to position'];
		}
		if($privilegeArray[2] == 1){ //change name
			$parse['change_name']= $lang['Change name'];
		}
		if($privilegeArray[3] == 1){ //kick player
			$parse['kick_player']= $lang['Kick player'];
		}
		if($privilegeArray[4] == 1){ //change des
			$parse['change_des']= $lang['Change alliance description'];
		}
		if($privilegeArray[5] == 1){ //alliance diplomacy
			$parse['allian_diplomacy']= $lang['Alliance diplomacy'];
		}	
		if($privilegeArray[7] == 1){ //invite a player into the alliance
			$parse['invite_player']	= $lang['Invite a player into the alliance'];
		}
		//END: option menu
		$page = parsetemplate(gettemplate('allian_kick_player'), $parse);
		display($page,$lang['allianz']);
	break;
	//END: kick player
	
	//START: allian change description
	case "4": 
		$parse['valu_tag']=$infoAllian->tag;
		$parse['valu_rank']=$infoAllian->rank;
		$parse['valu_points']=$infoAllian->point;
		$parse['valu_name']=$infoAllian->name;
		$parse['valu_members']=$infoAllianMember->mk;
		
		$desAllianEdit = $infoAllian->description;// description of ally
		$sloganAllianEdit = $infoAllian->slogan; // slogan of ally		
		if($desAllianEdit == 'NULL') {
			$parse['valu_des_edit'] = "";			
		}else {
			$parse['valu_des_edit'] = str_replace("<br />", "\n", $desAllianEdit);						
		}
		if($sloganAllianEdit == 'NULL')	{
			$parse['valu_slogan_edit'] = "";
		}else {
			$parse['valu_slogan_edit'] = str_replace("<br />", "\n", $sloganAllianEdit);			
		}
		//START: Repair error xuong dong khi change description
		$parse['valu_des_edit'] = str_replace("<br />", "", $desAllianEdit);
		$parse['valu_slogan_edit'] = str_replace("<br />", "", $sloganAllianEdit);
		//update description & slogan of ally
		//updateDescriptionAlly($desAllianEdit, $sloganAllianEdit, $infoAllian->id);
		//END: Repair error xuong dong khi change description
		if($_GET['act']==1)
		{
			$des_allian = nl2br(strip_tags($_POST['des_allian']));
			$slogan_allian = nl2br(strip_tags($_POST['slogan_allian']));
			if($wordFilters[$des_allian] !=1 &&  $wordFilters[$slogan_allian] !=1) {
				//updateDescriptionAlly($des_allian, $slogan_allian, $infoAllian->id);
			}
			header("Location: allianz.php");		
		}
		$page = parsetemplate(gettemplate('allian_change_des'), $parse);
		display($page,$lang['allianz']);
	break;
	//END: allian change description
	
	//START: allian diplomacy
	case "5": 
		$mkthanh='allian_diplomacy';
		$parse['message']  = "";
		$parse['ally_name_to'] = "";
		$parse['view_own_offer_list'] = "";
		$parse['view_foreign_offer_list'] = "";
		$parse['view_exist_relation_list'] = "";

		// Own offers
		$parse['view_own_offer_list'] = getDiplomacyOwner($allyId);
		// Foreign offers
		$parse['view_foreign_offer_list'] = getDiplomacyForeign($allyId);
		// Exist offers
		$parse['view_exist_relation_list'] = getDiplomacyExist($allyId);
		
		if($_GET['act']==1)
		{
			//lien minh khac
			$allyNameTo = $db->getEscaped($_POST['allian_name']);
			if($allyNameTo == ""){
				$parse['message'] = $lang['Input ally to diplomacy'];
			}else{	
				//lien minh cua chinh minh
				$myAlly = getAllyNameByAllyId($allyId);
				if($allyNameTo == $myAlly) {
					$parse['message'] = $lang['Can not diplomacy with your ally'];
				}else{
					//kiem tra lien minh moi co ton tai hay khong
					$getAllyTo = getInfoFromAlly("id", "name ='".$allyNameTo."'");
					$allyId2 = $getAllyTo->id;	
					$isInvite = getInfoFromAllyRelation("id", "ally_id_2 = ".$allyId2." AND 
														ally_id_1 = ".$allyId." AND status = 0");
					$isRelation = getInfoFromAllyRelation("id", "ally_id_2 = ".$allyId2." AND 
														ally_id_1 = ".$allyId." AND status = 1");
					if(empty($getAllyTo)){//neu lien minh do khong ton tai
						$parse['message'] = "".$lang['Alliance']." ".$allyNameTo." ".$lang['Not existed']."";
					}elseif($_POST['diplomacy'] == 0){//neu lien minh do ton tai
						$parse['message'] = $lang['Select type of diplomacy'];
						$parse['ally_name_to'] = $allyNameTo;
					}elseif(!empty($isInvite)){
						$parse['message'] = "".$allyNameTo." ".$lang['Received invitation']."";
					}elseif(!empty($isRelation)){
						$parse['message'] = "".$lang['Had diplomacy with']." ".$allyNameTo."";
					}else{	
						// Type of diplomacy
						if($db->getEscaped($_POST['diplomacy'])==1)	{
							$type1 = 1;//doi dau
							$type2 = $lang['Diplomacy type 1'];//doi dau
						}
						if($db->getEscaped($_POST['diplomacy'])==2)	{
							$type1 = 2;//hoa hao
							$type2 = $lang['Diplomacy type 2'];//hoa hao
						}
						if($db->getEscaped($_POST['diplomacy'])==3)	{
							$type1 = 3;//dong minh
							$type2 = $lang['Diplomacy type 3'];//dong minh
						}
						// insert relation of 2 ally into wg_ally_relation
						//insertRelationAlly($allyId, $allyId2, $type1);						
						// insert event int wg_ally_news									
						$content1 = "<a href=\"allianz.php?aid=$allyId\">$myAlly</a> $type2 
									<a href=\"allianz.php?aid=$allyId2\">$allyNameTo</a>";
						$content2 = "<a href=\"allianz.php?aid=$allyId2\">$allyNameTo</a> $type2 
									<a href=\"allianz.php?aid=$allyId\">$myAlly</a>";
						//insertEvent($allyId, $content1, $allyId2, $content2);
					}							
				}				
			}
			$parse['view_own_offer_list'] = getDiplomacyOwner($allyId);// Own offers
			//$parse['view_foreign_offer_list'] = getDiplomacyForeign($allyId2);// Foreign offers
			$parse['view_exist_relation_list'] = getDiplomacyExist($allyId);// Exist offers

		}
		$page = parsetemplate(gettemplate($mkthanh), $parse);
		display($page,$lang['allianz']);
	break;
	//END: allian diplomacy
	
	//START: Invite a player into the alliance
	case "6":
		$mkthanh='allian_invite_player';
		// get list of invitation
		$parse['view_invitation_list'] = getInvitationList($allyId);

		$parse['message'] = "";
		$parse['to_user_name'] = "";
		if($_GET['act']==1)	{
			$toUserName = $db->getEscaped($_POST['to_user_name']);
			if($toUserName == ""){
				$parse['message'] = $lang['Input account to want inviteting'];
			}else{
				$toUserId = getUserIdByUserName($toUserName);
				$fromUserName = getUserNameByUserId($userId);
				// Get ally id
				$sql = "SELECT count(id) FROM wg_users WHERE username ='".$toUserName."'";
				$db->setQuery ( $sql );
				$users = null;
				$users = (int)$db->loadResult();
				if($users == 0)	{ // neu user nay khong ton tai
					$parse['message'] = $toUserName." ".$lang['Not existed']."";				
				}else { // neu user nay ton tai
					$sql = "SELECT COUNT(user_id) FROM wg_ally_members 
								WHERE user_id=".getUserIdByUserName($toUserName)." 
								AND ally_id = ".$allyId." AND right_ = 1";
					$db->setQuery ($sql);
					$allianMembers =(int)$db->loadResult();
					if($allianMembers == 1)	{// neu user nay da tham gia lien minh nay
						$parse['message'] = "".$toUserName." ".$lang['Joined ally']."";
						$parse['to_user_name'] = $toUserName;
					}else {// neu user nay chua tham gia lien minh nay
						$sql = "SELECT COUNT(user_id) FROM wg_ally_members 
									WHERE user_id=".getUserIdByUserName($toUserName)." 
									AND ally_id = ".$allyId." AND right_ = 0";
						$db->setQuery ($sql);
						$hadInvited =(int)$db->loadResult();
						if($hadInvited == 1){// neu da moi toi user nay roi
							$parse['message'] = "".$toUserName." ".$lang['Received invitation']."";
						}else {// neu chua moi toi user nay
							/*$sql ="INSERT INTO wg_ally_members(user_id,ally_id,position_name,right_,privilege)
							VALUES('".$toUserId."','".$allyId."','".$positionName."','0','".$privilege."')";
							$db->setQuery($sql);
							$db->query();*/		
							$parse['message'] = "".$lang['Invite']." ".$toUserName." ".$lang['Join ally']."";								
							// Insert event int wg_ally_news
							$content = "<a href=\"profile.php?uid=$userId\">$fromUserName</a> đã mời 
										<a href=\"profile.php?uid=$toUserId\">$toUserName</a> tham gia liên minh";
							/*$sql ="INSERT INTO wg_ally_news(ally_id,content, time) 
									VALUES(".$allyId.",'".$content."','".date("Y-m-d H:i:s")."')";
							$db->setQuery($sql);
							$db->query();*/				
							header("Location: allianz.php?event=6");
						}
					}
				}
				$parse['view_invitation_list'] = getInvitationList($allyId);	
			}						
		}		
		//START: option menu
		//Get right value of user
		$privilege = getUserRight($user['id']);		
		$privilegeArray = str_split($privilege->privilege);
		
		$allyOptionMenu = "";
		$parse['assign_to_position'] = "";
		$parse['change_name'] = "";
		$parse['kick_player'] = "";
		$parse['change_des'] = "";
		$parse['allian_diplomacy'] = "";
		$parse['invite_player']	= "";		
		if($privilegeArray[1] == 1){ //assign to position
			$parse['assign_to_position']= $lang['Assign to position'];
		}
		if($privilegeArray[2] == 1){ //change name
			$parse['change_name']= $lang['Change name'];
		}
		if($privilegeArray[3] == 1){ //kick player
			$parse['kick_player']= $lang['Kick player'];
		}
		if($privilegeArray[4] == 1){ //change des
			$parse['change_des']= $lang['Change alliance description'];
		}
		if($privilegeArray[5] == 1){ //alliance diplomacy
			$parse['allian_diplomacy']= $lang['Alliance diplomacy'];
		}	
		if($privilegeArray[7] == 1){ //invite a player into the alliance
			$parse['invite_player']	= $lang['Invite a player into the alliance'];
		}
		//END: option menu
		$page = parsetemplate(gettemplate($mkthanh), $parse);
		display($page,$lang['allianz']);
	break;
	//END: Invite a player into the alliance
	
	//START: move capital
	case "7":
		//START: option menu
		//Get right value of user
		$privilege = getUserRight($user['id']);		
		$privilegeArray = str_split($privilege->privilege);
		
		$allyOptionMenu = "";
		$parse['assign_to_position'] = "";
		$parse['change_name'] = "";
		$parse['kick_player'] = "";
		$parse['change_des'] = "";
		$parse['allian_diplomacy'] = "";
		$parse['invite_player']	= "";		
		if($privilegeArray[1] == 1){ //assign to position
			$parse['assign_to_position']= $lang['Assign to position'];
		}
		if($privilegeArray[2] == 1){ //change name
			$parse['change_name']= $lang['Change name'];
		}
		if($privilegeArray[3] == 1){ //kick player
			$parse['kick_player']= $lang['Kick player'];
		}
		if($privilegeArray[4] == 1){ //change des
			$parse['change_des']= $lang['Change alliance description'];
		}
		if($privilegeArray[5] == 1){ //alliance diplomacy
			$parse['allian_diplomacy']= $lang['Alliance diplomacy'];
		}	
		if($privilegeArray[7] == 1){ //invite a player into the alliance
			$parse['invite_player']	= $lang['Invite a player into the alliance'];
		}
		//END: option menu
		
		//START: list of village
		$sql="SELECT id,name,x,y,user_id,workers FROM wg_villages 
												 WHERE user_id=".$userId." AND kind_id < 7 ORDER BY workers DESC";
		$db->setQuery($sql); 
		$villagesInfo=$db->loadObjectList();
		$count_vilages=0;
		if(empty($villagesInfo)){
			header("Location: village1.php");
		}	
		$parse['move_capital'] = "";
		if($vl_id->sum_villages > 1){
			$parse['move_capital']= $lang['Move capital'];
		}		
		$parse['toado_x'] = "";
		$parse['toado_y'] = "";
		$parse['inhabitants'] = "";
		$parse['village_name'] = "";
		$parse['choise_capital'] = "";
		$count = 1;
		if($villagesInfo){
			foreach ($villagesInfo as $villageInfo){  
				$parse['toado_x'] = $villageInfo->x;
				$parse['toado_y'] = $villageInfo->y;
				$parse['inhabitants'] = $villageInfo->workers;
				$parse['village'] = $villageInfo->name; 
				$parse['village_id'] = $villageInfo->id;
						
				if($villageInfo->id == $vl_id->villages_id){//thu do
					$parse['village_name'] = $villageInfo->name." <span class=\"c\">(".$lang['Capital'].")</span>";
					$parse['choise_capital'] = "<input type=\"radio\" name=\"capital\" id=\"capital\" 
												value=\"{village_id}\" {checked} disabled=\"disabled\"/> ";									  
				}else{//nhung thanh khac
					if($villageInfo->name == 'NewName'){
						$parse['village_name'] = $lang['New name'];	
					}else{
						$parse['village_name'] = $villageInfo->name;	
					}
					$parse['choise_capital'] = "<input type=\"radio\" name=\"capital\" id=\"capital\" 
																value=\"{village_id}\" {checked}/> ";									  
				}
				$villageList .= parsetemplate(gettemplate('allian_move_capital_village_list' ), $parse );
				$count ++;
			}
		}
		$parse['view_village_list'] = $villageList;
		//END: list of village
		
		$parse['message'] = "";
		//START: move capital
		if($_GET['act']==1){ // ok
			if($_POST['capital'] == ""){
				$parse['message'] = $lang['Please select village to be capital'];
			}else{
				//get level of Embassy in capital village				
				$levelOld = getLevelEmbassy($vl_id->villages_id);
				if($levelOld > 0){
					$parse['message'] = $lang['Delete Embassy in old capital'];
				}	
				else
				{
					$villageIdNew = $db->getEscaped($_POST['capital']);
					//get level of Embassy in village to be will capital
					$levelNew = getLevelEmbassy($villageIdNew);	
					//die($levelNew);			
					if(empty($levelNew)){
						$parse['message'] = $lang['Build Embassy in new capital'];
					}else{
						if($levelNew < 5){
							$parse['message'] = $lang['Embassy level must be >= 5'];
						}else{
							//cap nhat thu do
							//updateCapital($villageIdNew, $userId);
							//cap nhat level tai nguyen cua thu do cu voi maxlevel la 10
							//updateLevelResource($vl_id->villages_id);
							header("Location: allianz.php?s=7");
						}
					}
				}
			}
		}
		//END: move capital
		$page = parsetemplate(gettemplate('allian_move_capital'), $parse);
		display($page,$lang['allianz']);
	break;
	//END: move capital

	//START: exit ally
	case "8":
		//START: option menu
		//Get right value of user
		$privilege = getUserRight($user['id']);		
		$privilegeArray = str_split($privilege->privilege);
		
		$allyOptionMenu = "";
		$parse['assign_to_position'] = "";
		$parse['change_name'] = "";
		$parse['kick_player'] = "";
		$parse['change_des'] = "";
		$parse['allian_diplomacy'] = "";
		$parse['invite_player']	= "";		
		if($privilegeArray[1] == 1){ //assign to position
			$parse['assign_to_position']= $lang['Assign to position'];
		}
		if($privilegeArray[2] == 1){ //change name
			$parse['change_name']= $lang['Change name'];
		}
		if($privilegeArray[3] == 1){ //kick player
			$parse['kick_player']= $lang['Kick player'];
		}
		if($privilegeArray[4] == 1){ //change des
			$parse['change_des']= $lang['Change alliance description'];
		}
		if($privilegeArray[5] == 1){ //alliance diplomacy
			$parse['allian_diplomacy']= $lang['Alliance diplomacy'];
		}	
		if($privilegeArray[7] == 1){ //invite a player into the alliance
			$parse['invite_player']	= $lang['Invite a player into the alliance'];
		}
		//END: option menu
		
		$parse['message'] = "";		
		if($_GET['act']==1)
		{	
			if($_SESSION['password_viewuser'] != md5(md5($_POST['password'])."--" .$dbsettings["secretword"]))
			{
			//if($user['password'] != md5($db->getEscaped($_POST['password']))){
				$parse['message']='Mật khẩu không đúng';
			}else{
				//kiem tra cac member con trong ally hay ko, neu ko con thi huy ally
				$sql = "SELECT count(id) FROM wg_ally_members WHERE ally_id = ".$allyId." AND right_ = 1";
				$db->setQuery($sql);
				$sumAlly=(int)$db->loadResult();		
				if($sumAlly > 1){//cac member con trong ally
					//kiem tra user login co phai la minh chu hay khong
					$sql = "SELECT user_id FROM wg_allies WHERE user_id = ".$userId;
					$db->setQuery($sql);
					$allyFounder = null;
					$db->loadObject($allyFounder);		
					if(!empty($allyFounder)){ //neu la minh chu
						// Chon minh chu moi trong ally member
						$sql = "SELECT user_id FROM wg_ally_members 
								WHERE ally_id =".$allyId." AND user_id != ".$allyFounder->user_id." 
								AND right_ = 1 ORDER BY RAND() LIMIT 1";
						$db->setQuery($sql);
						$newAllyFounder = null;
						$db->loadObject($newAllyFounder);						
						//cap nhat minh chu moi cho ally
						/*$sql = "UPDATE wg_ally_members
										SET right_=1, privilege = '11111111', position_name = 'Minh chủ'
										WHERE user_id = ".$newAllyFounder->user_id;
						$db->setQuery($sql);
						$db->query();
						
						$sql = "UPDATE wg_allies SET user_id = ".$newAllyFounder->user_id."
												 WHERE id =".$allyId;
						$db->setQuery($sql);
						$db->query();
						//delete user member
						$sql = "DELETE FROM wg_ally_members WHERE user_id=".$userId." AND right_ = 1";
						$db->setQuery($sql);
						$db->query();
						//update table wg_users
						$sql="UPDATE wg_users SET alliance_id = 0 WHERE id=".$userId;
						$db->setQuery($sql);
						$db->query();*/
					}else{//neu khong phai minh chu
						/*$sql = "DELETE FROM wg_ally_members WHERE user_id = ".$userId." AND right_ = 1";
						$db->setQuery($sql);
						$db->query();
						// update table wg_users
						$sql="UPDATE wg_users SET alliance_id = 0 WHERE id = ".$userId;
						$db->setQuery($sql);
						$db->query();*/
					}
				}elseif($sumAlly == 1){//cac member khong con trong ally
					//xoa user do
					/*$sql = "DELETE FROM wg_ally_members WHERE user_id = ".$userId." AND right_ = 1";
					$db->setQuery($sql);
					$db->query();
					//xoa tat ca nhung loi moi chua duoc chap nhan cua user do
					$sql = "DELETE FROM wg_ally_members WHERE ally_id = ".$allyId." AND right_ = 0";
					$db->setQuery($sql);
					$db->query();
					// update table wg_users
					$sql="UPDATE wg_users SET alliance_id = 0 WHERE id = ".$userId;
					$db->setQuery($sql);
					$db->query();
					// delete wg_allies
					$sql = "DELETE FROM wg_allies WHERE id = ".$allyId;
					$db->setQuery($sql);
					$db->query();
					// delete wg_ally_relation
					$sql = "DELETE FROM wg_ally_relation WHERE ally_id_1 = ".$allyId." OR ally_id_2 = ".$allyId;
					$db->setQuery($sql);
					$db->query();*/
				}
				//insert event int wg_ally_news
				/*$content = "<a href=\"profile.php?uid=$userId\">".$user['username']."</a> thoát khỏi liên minh";
				$sql ="INSERT INTO wg_ally_news(ally_id, content, time) 
						VALUES(".$allyId.",'".$content."','".date("Y-m-d H:i:s")."')";
				$db->setQuery($sql);
				$db->query();*/	
				unset($_SESSION['alliance_id']);			
				header("Location: village1.php");		
			}			
		}
		$page = parsetemplate(gettemplate('allian_op_quit'), $parse);
		display($page,$lang['allianz']);
	break;
	//END: exit ally
	
	//START: Attacks
	case "10": 
		define('MAXROW',20);
		$parse['paging'] = "";
		$parse['image'] = "";
		$parse['ally_attack'] = "";
		$parse['ally_defend'] = "";
		$parse['title'] = "";	
		$parse['time_attack'] = "";
		$parse['report_id'] = "";	
		//START: chi user thuoc lien minh thi moi xem duoc thong tin attack
		$sql = "SELECT user_id FROM wg_ally_members WHERE ally_id=".$allyId." AND right_ = 1";
		$db->setQuery($sql);
		$userViewAttack = null;
		$userViewAttack = $db->loadObjectList();		
		$arrayInfo = array(); // array		
		$i = 1;	
		if($userViewAttack){	
			foreach($userViewAttack as $uva){
				// user attack 
				$sql = "SELECT * FROM wg_reports WHERE (type = ".REPORT_ATTACK." OR type=".REPORT_DEFEND.") AND user_id =".$uva->user_id." 
						ORDER BY id DESC";
				$db->setQuery($sql);
				$usersAttack = null;
				$usersAttack = $db->loadObjectList();
				if($usersAttack){			
					foreach($usersAttack as $userAttack){
						$userAttack->user_id;				
						$arrayInfo[$i]['id'] = $userAttack->user_id;				
						$i++;										
					}	
				}
			}
		}
		//END: chi user thuoc lien minh thi moi xem duoc thong tin attack
		$str = "";	
		/*for($j=1; $j <= count($arrayInfo); $j++){
			$str = $str.$arrayInfo[$j]['id'].",";
			$str .= $arrayInfo[count($arrayInfo)]['id'];
		}	*/
		$totalAttackAlly=count($arrayInfo);
		for($j=1; $j <=($totalAttackAlly-1) ; $j++){
			$str.= $arrayInfo[$j]['id'].",";			
		}	
		$str.= $arrayInfo[$totalAttackAlly]['id'];
		
		
		//START: get for paging	
		if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
			$x = 0;
		}else{
			$x =($_GET["page"]-1)*constant("MAXROW");
		}
		//END: get for paging
		
		$sum = count($arrayInfo);	
		$parse['sum'] = $sum;
		$sql = "SELECT * FROM wg_reports WHERE (type = ".REPORT_ATTACK." OR type=".REPORT_DEFEND.") AND user_id IN (".$str.") 
											ORDER BY id DESC LIMIT ".$x.",".constant("MAXROW")."";
		$db->setQuery($sql);
		$allyAttacks = null;
		$allyAttacks = $db->loadObjectList();	
		if($allyAttacks){
			foreach($allyAttacks as $allyAttack){
				$parse['title'] = $allyAttack->title;
				//START: ally attack				
					$userIdAttack = $allyAttack->user_id;	
					//get ally_id attack
					$allyIdAttack = getAllyIdByUserId($userIdAttack);				
					//get ally name attack
					$allyNameAttack = getAllyNameByAllyId($allyIdAttack);
					if($allyNameAttack == ""){
						$parse['ally_attack'] = "?";
					}else{
						$parse['ally_attack'] = $allyNameAttack;	
					}			
				//END: ally attack
				
				include ("language/vi/attack.mo");	
				include ("language/vi/rally_point.mo");
								
				$attack = explode(" ",$lang['attack']);
				$tuchien = explode(" ",$lang['tu_chien']);				
				$dotham = explode(" ",$lang['do_tham']);
				$dotkich = explode(" ",$lang['dot_kich']);				
				$vien_tro = explode(" ",$lang['vien_tro']);
				
				if(substr_count($allyAttack->title, $lang['attack']) >= 1){
					$str1 = strstr($allyAttack->title, $attack[count($attack)-1]);
					$str2 = explode(" ", $str1);					
					$villageNameDefence = substr($str1, strlen($str2[0])+1);					
				}elseif(substr_count($allyAttack->title, $lang['tu_chien']) >= 1){
					$str1 = strstr($allyAttack->title, $tuchien[count($tuchien)-1]);
					$str2 = explode(" ", $str1);				
					$villageNameDefence = substr($str1, strlen($str2[0])+1);	
				}elseif(substr_count($allyAttack->title, $lang['do_tham']) >= 1){
					$str1 = strstr($allyAttack->title, $dotham[count($dotham)-1]);
					$str2 = explode(" ", $str1);					
					$villageNameDefence = substr($str1, strlen($str2[0])+1);						
				}elseif(substr_count($allyAttack->title, $lang['dot_kich']) >= 1){
					$str1 = strstr($allyAttack->title, $dotkich[count($dotkich)-1]);
					$str2 = explode(" ", $str1);					
					$villageNameDefence = substr($str1, strlen($str2[0])+1);	
				}elseif(substr_count($allyAttack->title, $lang['vien_tro']) >= 1){					
					$str1 = strstr($allyAttack->title, $vien_tro[count($vien_tro)-1]);
					$str2 = explode(" ", $str1);					
					$villageNameDefence = substr($str1, strlen($str2[0])+1);	
				}			
				//START: ally defend				
					//user_id defend
					$userIdDefend = getUserIdByVillageName($villageNameDefence);
					//get ally_id defence
					$allyIdDefence = getAllyIdByUserId($userIdDefend);
					// ally name defence
					$allyNameDefend = getAllyNameByAllyId($allyIdDefence);	
					if($allyNameDefend == ""){
						$parse['ally_defend'] = "?";
					}else{
						$parse['ally_defend'] = $allyNameDefend;	
					}				
				//END: ally defend
				if($allyAttack->type==REPORT_DEFEND){
					$found =strpos($allyAttack->title,$lang['giup_do2']);
					if(!$found){
						$villageNameAttack=trim(substr($allyAttack->title,0,strpos($allyAttack->title,$lang['attack'])-1));	
						$userIdAttack = getUserIdByVillageName($villageNameAttack);
						$allyIdAttack = getAllyIdByUserId($userIdAttack);
						$allyNameAttack = getAllyNameByAllyId($allyIdAttack);						
						if($allyNameAttack == ""){
							$parse['ally_attack'] = "?";
						}else{
							$parse['ally_attack'] = $allyNameAttack;	
						}
					}					
				}
				//START: kiem tra cung lien minh hay khong
				$parse['image'] = "";
				if($allyNameAttack == $allyNameDefend){
					$parse['image'] = "def1.gif";
				}else{
					$parse['image'] = "att1.gif";
				}
				//END: kiem tra cung lien minh hay khong
				
				$parse['time_attack'] = substr($allyAttack->time,10).' '.substr($allyAttack->time,8,2).'-'.
											substr($allyAttack->time,5,2).'-'.substr($allyAttack->time,0,4);
				$parse['report_id'] = $allyAttack->id;			
				$viewAttackList .= parsetemplate(gettemplate('allian_attack_list'),$parse);
			}
			$parse['view_attack_list'] = $viewAttackList;
			
			//START: Paging
			$a="'allianz.php?s=10&page='+this.options[this.selectedIndex].value";
			$b="'_top'";
			$string='onchange="javscript:window.open('.$a.','.$b.')"';
			$parse['paging']=''.$lang['40'].' <select name="page" style="width:40px;height=40px;" '.$string.'>';
			for($i=1;$i<=ceil($sum/constant("MAXROW"));$i++){
				$parse['paging'].='<option value="'.$i.'"';
				if($_GET["page"]==$i){
					$parse['paging'].=' selected="selected">';
				}else{
					$parse['paging'].='>';
				}						
				$parse['paging'].=''.$i.'</option>';
			}
			$parse['paging'].='</select>';
			//END: Paging							
		}else{
			$parse['paging']='';
			$parse['view_attack_list']=parsetemplate(gettemplate('allian_attack_null'),$parse); 
		}
		
		$page = parsetemplate(gettemplate('allian_attack'), $parse);
		display($page,$lang['allianz']);
	break;
	//END: Attacks
	
	//START: News
	case "11": 
		$parse['paging'] = "";
		define('MAXROW',20);
		if(empty($_GET["page"])||$_GET["page"]==1 || !is_numeric($_GET["page"])){
			$x = 0;
		}else{
			$x =($_GET["page"]-1)*constant("MAXROW");
		}
		$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_ally_news WHERE ally_id =".$allyId;
		$db->setQuery($sql);
		$sum = (int)$db->loadResult();
		$parse['sum'] = $sum;
		$sql = "SELECT * FROM wg_ally_news WHERE ally_id =".$allyId." 
											ORDER BY id DESC LIMIT ".$x.",".constant("MAXROW")."";
		$db->setQuery($sql);
		$allyNews = null;
		$allyNews = $db->loadObjectList();

		$parse['content'] = "";
		$parse['event_time'] = "";
		$i = 1;
		$allyNewsList = "";
		if($allyNews){
			foreach($allyNews as $allyNew){
				$parse['content'] = $allyNew->content;
				$parse['no'] = $i;
				//$parse['event_time'] = $allyNew->time;
				$parse['event_time']=substr($allyNew->time,10).' '.substr($allyNew->time,8,2).'-'.substr($allyNew->time,5,2).'-'.substr($allyNew->time,0,4);
	
				$allyNewsList .= parsetemplate(gettemplate('allian_news_list'),$parse);
				$i++;				
			}
			$parse['view_allian_news_list'] = $allyNewsList;
			//START: Paging
			$a="'allianz.php?s=11&page='+this.options[this.selectedIndex].value";
			$b="'_top'";
			$string='onchange="javscript:window.open('.$a.','.$b.')"';
			$parse['paging']=''.$lang['40'].' <select name="page" style="width:40px;height=40px;" '.$string.'>';
			for($i=1;$i<=ceil($sum/constant("MAXROW"));$i++){
				$parse['paging'].='<option value="'.$i.'"';
				if($_GET["page"]==$i){
					$parse['paging'].=' selected="selected">';
				}else{
					$parse['paging'].='>';
				}						
				$parse['paging'].=''.$i.'</option>';
			}
			$parse['paging'].='</select>';
			//END: Paging
		}else{
			$parse['paging']='';
			$parse['view_allian_news_list']=parsetemplate(gettemplate('allian_news_null'),$parse); 
		}			
		$page = parsetemplate(gettemplate('allian_news'), $parse);
		display($page,$lang['allianz']);
	break;
	//END: News
	
	//START: Ally Option Menu
	case "12":
		//START: option menu
		//Get right value of user
		$privilege = getUserRight($user['id']);		
		$privilegeArray = str_split($privilege->privilege);
		
		$allyOptionMenu = "";
		$parse['assign_to_position'] = "";
		$parse['change_name'] = "";
		$parse['kick_player'] = "";
		$parse['change_des'] = "";
		$parse['allian_diplomacy'] = "";
		$parse['invite_player']	= "";		
		if($privilegeArray[1] == 1){ //assign to position
			$parse['assign_to_position']= $lang['Assign to position'];
		}
		if($privilegeArray[2] == 1){ //change name
			$parse['change_name']= $lang['Change name'];
		}
		if($privilegeArray[3] == 1){ //kick player
			$parse['kick_player']= $lang['Kick player'];
		}
		if($privilegeArray[4] == 1){ //change des
			$parse['change_des']= $lang['Change alliance description'];
		}
		if($privilegeArray[5] == 1){ //alliance diplomacy
			$parse['allian_diplomacy']= $lang['Alliance diplomacy'];
		}	
		if($privilegeArray[7] == 1){ //invite a player into the alliance
			$parse['invite_player']	= $lang['Invite a player into the alliance'];
		}
		//END: option menu
		
		$page = parsetemplate(gettemplate('allian_option'), $parse);
		display($page,$lang['allianz']);
	break;
	//END: Ally Option Menu	
	
	//START: view report attack
	case "13":
		includeLang('report');		
		$parse = $lang;
		$parse['Reports']=$lang['Reports'];
		$parse['ally_name'] = $infoAllian->name;
		if(is_numeric($_GET['id']))	{
			$sql="SELECT title,time,report_text FROM wg_reports WHERE id = ".$_GET['id']."";
			$db->setQuery($sql);
			$row = null;
			$db->loadObject($row);
			if($row){
				$parse['title_de'] = $row->title;
				$timeShow = $row->time;
				$timeShow = strtotime($timeShow);
				$parse['time_de'] = date("H:i:s d-m-Y", $timeShow);	
				$parse['_report_detail'] = $row->report_text;										
			}else{
				header("Location: allianz.php?s=10");
				exit();	
			}			
		}		
		$page = parsetemplate(gettemplate('allian_attack_report_detail'), $parse);
		display($page,$lang['report_detail']);	
		
	break;
	//END: view report attack
	
	default:
		$mkthanh='allianz';
		$parse['valu_tag']=$infoAllian->tag;
		$parse['valu_name']=$infoAllian->name;
		$parse['valu_points']=$infoAllian->point;
		//$parse['valu_rank'] = $infoAllian->rank;		
		
		$parse['valu_slogan']=$infoAllian->slogan;
		$parse['valu_members']=$infoAllianMember->mk;
		
		//START: check description and slogan is null 
		if($infoAllian->description == 'NULL') {
			$parse['valu_des'] = "";
		}else {
			$parse['valu_des']=$infoAllian->description;
		}
		if($infoAllian->slogan == 'NULL'){
			$parse['valu_slogan'] = "";
		}else{
			$parse['valu_slogan']=$infoAllian->slogan;
		}
		//START: show ally relation in description
		$parse['ally_relation_1'] = "";		
		$parse['ally_relation_list_1'] = "";
		$parse['ally_relation_2'] = "";		
		$parse['ally_relation_list_2'] = "";
		$parse['ally_relation_3'] = "";		
		$parse['ally_relation_list_3'] = "";
		//quan he doi dau
		$viewRelationList_1 = showAllyRelation($allyId, "1");//type = 1
		if(!empty($viewRelationList_1)){
			$parse['ally_relation_1'] = $lang['Diplomacy type 11'];
			$parse['ally_relation_list_1'] = $viewRelationList_1;	
		}		
		//quan he hoa hao
		$viewRelationList_2 = showAllyRelation($allyId, "2");//type = 2
		if(!empty($viewRelationList_2)){
			$parse['ally_relation_2'] = $lang['Diplomacy type 21'];
			$parse['ally_relation_list_2'] = $viewRelationList_2;	
		}		
		//quan he dong minh
		$viewRelationList_3 = showAllyRelation($allyId, "3");//type = 3
		if(!empty($viewRelationList_3)){
			$parse['ally_relation_3'] = $lang['Diplomacy type 31'];
			$parse['ally_relation_list_3'] = $viewRelationList_3;	
		}		
	   //END: show ally relation in description
	   
		// Get list user
		$sql = "SELECT user_id,position_name FROM wg_ally_members 
											WHERE ally_id=".$infoAllian->id." AND right_ =1";
		$db->setQuery($sql);
		$infoAllianMemberLists = null;
		$infoAllianMemberLists = $db->loadObjectList();
						
		$i = 0;
		$usersInfoArray = array();
		if($infoAllianMemberLists){
			foreach ($infoAllianMemberLists as $infoAllianMemberList )
			{
				// Position name
				if($infoAllianMemberList->position_name != "")
				{
					$parse['position_name'] = $infoAllianMemberList->position_name;
					$parse['player'] = getUserNameByUserId($infoAllianMemberList->user_id);
					$parse['player_id'] = $infoAllianMemberList->user_id;
					$viewListPosition .= parsetemplate (gettemplate('allianz_position'), $parse );	
				}	
				//Lay ten cac user co trong member
				$sql = "SELECT id, population FROM wg_users WHERE id=".$infoAllianMemberList->user_id;
				$db->setQuery ( $sql );
				$infoAllianUser = null;
				$db->loadObject ($infoAllianUser);
				
				$usersInfoArray[$i]['population'] = $infoAllianUser->population;					
				$usersInfoArray[$i]['id']		  = $infoAllianUser->id;										
				$i++;
			}
		}
		$parse ['valu_position'] = $viewListPosition;
		
		$viewListPosition = "";
		$viewListView="";
		$countListPosition=1;
		$countListViews=1;	
		
		rsort($usersInfoArray);// sap xep mang giam dan theo dan so
		if($usersInfoArray){
			foreach($usersInfoArray as $userInfoArray)
			{
				$sql = "SELECT id,population,username FROM wg_users WHERE id=".$userInfoArray['id'];
				$db->setQuery ( $sql );
				$infoAllianUser = null;
				$db->loadObject ($infoAllianUser);
				
				$parse['valu_views_id']=$countListPosition;
				
				$parse['valu_views_name']=$infoAllianUser->username;
				$getAllyId = getInfoFromAllyMember("ally_id", "user_id =".$userId." AND right_=1");
				if($allyId == $getAllyId->ally_id) // chi xem duoc status online hay offline cua ally minh
				{
					// online						
					if(getUserOnLineOffLine($infoAllianUser->username)){
						$parse['online_status_1'] = "&nbsp;";
						$parse['online_status_2'] = "b2.gif";	
					}
					//offline
					else{							
						$parse['online_status_1'] = "&nbsp;";
						$parse['online_status_2'] = "b5.gif";	
					}
				}
				$parse['valu_views_population']=$infoAllianUser->population;
				$parse['valu_views_link']="profile.php?uid=".$userInfoArray['id'];	
				
				//Dem so village cua user nay trong bang wg_villages
				$sql = "SELECT count(id) as mk FROM wg_villages WHERE user_id=".$userInfoArray['id']." AND kind_id < 7";
				$db->setQuery ( $sql );
				$infoAllianUserVilla = null;
				$db->loadObject ($infoAllianUserVilla);
				
				if(!$infoAllianUserVilla->mk)
					$parse['valu_views_villa']=1;
				else
					$parse['valu_views_villa']=$infoAllianUserVilla->mk;
				$viewListView .= parsetemplate (gettemplate ( 'allian_views' ), $parse );
				$countListPosition++;
				$countListViews ++;				
			}	
		}			
		$parse ['valu_list']=$viewListView;
		$page = parsetemplate(gettemplate($mkthanh), $parse);
		display($page,$lang['allianz']);
	break;
}
?>