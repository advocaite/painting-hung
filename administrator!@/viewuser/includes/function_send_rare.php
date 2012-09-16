<?php
/*
	Plugin Name: function_send_rare.php
	Plugin URI: 
	Description: Chuyen bau vat
	Version: 1.0.0
	Author: Manhhx
	Author URI: 
*/

$objRes = isHadRare($db, $wg_village);
$objSending = isSendingRare($db, $wg_village);
$parse2 = $lang;

$dataRq = array();
if($_POST["tab"]){
	$dataRq = $_POST;
}else{
	$dataRq = $_REQUEST;
}

//if($objRes || $objSending){ // Hien thi menu	
	$parseRare = array();		
	$parseRare['tab0'] = $lang['tab0'];
	$parseRare['tab1'] = $lang['tab1'];
	$parseRare['tab2'] = $lang['tab2'];
	$parseRare['kindId'] = $dataRq['id'];
	if($dataRq["tab"]){
		$parseRare['class'.$_GET['tab'].'']='class="selected"';
	}else{
		$parseRare['class0']='class="selected"';
	}
	$menu_rare = parsetemplate(gettemplate('menu_rare'),$parseRare);	
	$parse['menu_rare'] = $menu_rare;
	
//}
if(isset($dataRq["tab"]) && is_numeric($dataRq["tab"]))
{
	$tab =$dataRq["tab"];
}
switch ($tab){ // Voi moi tab
	/*case 1:
		$parse['data_rare'] = processTab1($objRes, $dataRq, $parse2);		
		break;*/
	case 1:	
		//Cancel
		if($dataRq['cancel_rare']){
			roolBackSendRare($db, $wg_village, $dataRq);	
			$objRes = isHadRare($db, $wg_village);
			$objSending = isSendingRare($db, $wg_village);			
		}
		
		if($resData = showRareSending($db, $wg_village, $lang)){ 		
		   $parse['data_rare'] = $resData;	
		   break;
		}
			
		$parse2['error_rare'] = '';
		if($dataRq['send_rare']){ 			
			$resVillageTo = checkVillageTo($db, $wg_village, $dataRq, $lang);
			if(!$resVillageTo){ //Co loi
				$parse2['error_rare'] = $lang['error_rare'];
			}else{ // Den trang confirm
				$_SESSION['agree'] = $resVillageTo->name;				
				$dataConfirm = getDataConfirm($db, $wg_village, $resVillageTo, $dataRq, $parse2, 'rare_confirm');
				$parse['data_rare'] = $dataConfirm;				
				break;
			}
		}
		
		if($dataRq['agree'] && $_SESSION['agree']){	//Chuyen bau vat		
			$dispTime = round(S($wg_village->x, $wg_village->y,$db->getEscaped($dataRq['_x']),$db->getEscaped($dataRq['_y'])));				
			$heroSpeed = getHeroSpeed($db);
			$dispTime=round(($dispTime/($heroSpeed*$game_config['k_speed']))*3600);
		
			$startTime = time();
			$endTime = $startTime + $dispTime ;
			$startDate=date("Y-m-d H:i:s",$startTime);
			$endDate=date("Y-m-d H:i:s",$endTime);	
			$dataRq['startDate'] = $startDate;
			$dataRq['endDate'] = $endDate;
			$dataRq['dispTime'] = $dispTime;
			
			insertRareData($db, $wg_village, $dataRq);
			
			$resData = showRareSending($db, $wg_village, $lang);			
			$parse['data_rare'] = $resData;		
			$_SESSION['agree'] ='';	
			header("Location: build.php?id=".$_REQUEST['id']."&tab=1");
			exit(0);
			break;				
		}
				
		$tpl ="rare_post";
		if(!$objRes){
			$tpl = "rare_empty";			
		}
		$parse2['tbl_list'] = processTab1($objRes, $dataRq, $parse2);
		$parse['data_rare'] = processTab2($objRes, $objSending, $dataRq, $parse2, $tpl);		
		$parse['dk_xaydung_kydai']=checkBuildWorldWonder($objRes);
		break;
	default:
		break;
} // End switch voi moi tab
/*
	Da du Dk xay dung ky dai moi'
	1. so thanh >=2
	2. du so bau vat
*/
function checkBuildWorldWonder($objRes)
{
	global $lang,$db,$wg_village,$wg_buildings,$user;
	$parse=$lang;
	$parse['vt_new_of_main']='';
	if($wg_buildings)
	{
		for($i=33;$i<=36;$i++)
		{
			if($wg_buildings[$i]->type_id==12)
			{
				$_SESSION['vt_new_of_main']=$wg_buildings[$i]->index;
				$parse['vt_new_of_main']=$lang['vt_new_Main'];
				break;		
			}
		}
	}	
	if($objRes)
	{
		if($objRes->kim >0 && $objRes->moc>0 && $objRes->thuy>0 && $objRes->hoa>0 && $objRes->tho>0)
		{
			//$sum=$wg_buildings[32]->level+$wg_buildings[33]->level+$wg_buildings[34]->level+$wg_buildings[35]->level;
			$sql = "SELECT  COUNT(DISTINCT(id)) FROM wg_villages WHERE user_id=".$user['id']."";
			$db->setQuery($sql);
			$count = (int)$db->loadResult();
			//if($sum>0 && $count >1 && $wg_buildings[35]->type_id!=37)
			if($count >1 && $wg_buildings[35]->type_id!=37)
			{
				$_SESSION['create_wonder']=md5(time());
				$parse['code']=$_SESSION['create_wonder'];
				return parsetemplate(gettemplate('rare_show_world'),$parse);	
			}				
		}				
	}
	return NULL;
}

/*
* @Author: Manhhx
* @Des: Kiem tra co bau vat chua
* @param: $db object database, $wg_village
* @return: $objRes or false
*/
function isHadRare($db, $wg_village){
	$query="SELECT * FROM wg_rare WHERE vila_id=".$wg_village->id;
	$db->setQuery($query);
	$objRes=null;	
	$db->loadObject($objRes);
	if(!$objRes->kim && !$objRes->moc && !$objRes->thuy && !$objRes->hoa && !$objRes->tho){
		return false;
	}
	return $objRes;
}

/*
* @Author: Manhhx
* @Des: Kiem tra co dang chuyen bau vat kohng
* @param: $db object database, $wg_village
* @return: $objRes
*/
function isSendingRare($db, $wg_village){
	$query="SELECT * FROM wg_rare_sends WHERE village_id_from=".$wg_village->id;
	$query=" AND status='0'";
	$db->setQuery($query);
	$objRes=null;	
	$db->loadObject($objRes);
	return $objRes;
}

/*
* @Author: Manhhx
* @Des: Du lieu khi click vao tab 1
* @param: $objRes, $dataPost, $lang
* @return: $resRare chuoi data
*/
function processTab1($objRes, $dataPost, $lang)
{
	global $db;		
	$parseRare = array();
	$parseRare['label_kim'] = $lang['kim'];
	$parseRare['label_thuy'] = $lang['thuy'];
	$parseRare['label_moc'] = $lang['moc'];
	$parseRare['label_hoa'] = $lang['hoa'];
	$parseRare['label_tho'] = $lang['tho'];
	$parseRare['danh_sach_bau_vat'] = $lang['danh_sach_bau_vat'];
	if($objRes){
		$parseRare['kim'] = $objRes->kim;
		$parseRare['thuy'] = $objRes->thuy;
		$parseRare['moc'] = $objRes->moc;
		$parseRare['hoa'] = $objRes->hoa;
		$parseRare['tho'] = $objRes->tho;
	}
	else{
		$parseRare['kim'] = 0;
		$parseRare['thuy'] = 0;
		$parseRare['moc'] = 0;
		$parseRare['hoa'] = 0;
		$parseRare['tho'] = 0;
	}	
	$parseRare['kindId'] = $db->getEscaped($dataPost['id']);	
	$resRare = parsetemplate(gettemplate('rare_list'),$parseRare);	
	return $resRare;
}

/*
* @Author: Manhhx
* @Des: Du lieu khi click vao tab 2
* @param: $objRes object rare, $objSending object rare sending, 
* $dataPost post, $lang languages, $tpl template name
* @return: $rarePost chuoi data
*/
function processTab2($objRes, $objSending, $dataPost, $lang, $tpl)
{
	global $db;
	//Hien thi trang nhap data de chuyen bau vat		
	$optRareList='';		
	$parseRare = array();		
	$parseRare['opt_rare'] = $dataPost['opt_rare'];
	$parseRare['kindId'] = $dataPost['id'];
	$parseRare['village_name'] =$db->getEscaped($dataPost['village_name']);
	$parseRare['_x'] = $dataPost['_x'];
	$parseRare['_y'] = $dataPost['_y'];
	
	$parseRare['error_rare'] = $lang['error_rare'];
	
	if($objRes->kim){	
		$parseRare['rare_name_value'] = "kim";		
		$parseRare['rare_name_show'] = $lang['kim'];
		if($dataPost['opt_rare']=="kim"){				
			$parseRare['rare_select'] = "selected";
		}else{
			$parseRare['rare_select'] = "";
		}
		$optRareList.= parsetemplate(gettemplate("rare_combo"),$parseRare);		
	}
	if($objRes->thuy){		
		$parseRare['rare_name_value'] = "thuy";		
		$parseRare['rare_name_show'] = $lang['thuy'];
		if($dataPost['opt_rare']=="thuy"){				
			$parseRare['rare_select'] = "selected";
		}else{
			$parseRare['rare_select'] = "";
		}
		$optRareList.= parsetemplate(gettemplate("rare_combo"),$parseRare);		
	}
	if($objRes->moc){		
		$parseRare['rare_name_value'] = "moc";		
		$parseRare['rare_name_show'] = $lang['moc'];
		if($dataPost['opt_rare']=="moc"){				
			$parseRare['rare_select'] = "selected";
		}else{
			$parseRare['rare_select'] = "";
		}
		$optRareList.= parsetemplate(gettemplate("rare_combo"),$parseRare);		
	}
	if($objRes->hoa){
		$parseRare['rare_name_value'] = "hoa";		
		$parseRare['rare_name_show'] = $lang['hoa'];
		if($dataPost['opt_rare']=="hoa"){				
			$parseRare['rare_select'] = "selected";
		}else{
			$parseRare['rare_select'] = "";
		}
		$optRareList.= parsetemplate(gettemplate("rare_combo"),$parseRare);	
	}
	if($objRes->tho){
		$parseRare['rare_name_value'] = "tho";		
		$parseRare['rare_name_show'] = $lang['tho'];
		if($dataPost['opt_rare']=="tho"){				
			$parseRare['rare_select'] = "selected";
		}else{
			$parseRare['rare_select'] = "";
		}
		$optRareList.= parsetemplate(gettemplate("rare_combo"),$parseRare);	
	}
	$parseRare['rare_list'] = $optRareList;	
	$parseRare['rare_empty'] = $lang['rare_empty'];	
	$parseRare['ten_thanh'] = $lang['ten_thanh'];	
	$parseRare['hoac_toa_do'] = $lang['hoac_toa_do'];	
	$parseRare['bau_vat'] = $lang['bau_vat'];
	$parseRare['tbl_list'] = substr($lang['tbl_list'],3, strlen($lang['tbl_list']));
	$parseRare['chuyen_bau_vat'] = $lang['chuyen_bau_vat'];
	$rarePost = parsetemplate(gettemplate($tpl),$parseRare);	
	
	return $rarePost;
	
}


/*
* @Author: Manhhx
* @Des: Kiem tra thanh gui den co ton tai khong
* @param: $db, $wg_village, $dataPost, $lang
* @return: $objRes or false
*/
function checkVillageTo($db, $wg_village, $dataPost, $lang)
{
	global $db;
	$objRes = null;
	if($dataPost['_x']!='' && $dataPost['_y']!='')
	{
		if(is_numeric($dataPost['_x']) && is_numeric($dataPost['_y']))
		{
			$query="SELECT * FROM wg_villages 
			WHERE x=".$db->getEscaped($dataPost['_x'])." AND y=".$db->getEscaped($dataPost['_y']);	
			$db->setQuery($query);
			$db->loadObject($objRes);		
			if($objRes)
			{
				if($wg_village->x==$objRes->x && $wg_village->y==$objRes->y)
				{
					return false;
				}
				return $objRes;
			}
		}
	}
	else{
		if($dataPost['village_name']){
			$query="SELECT * FROM wg_villages WHERE name='".$db->getEscaped($dataPost['village_name'])."'";			
			$db->setQuery($query);
			$objRes = $db->loadObjectList();		
			if($objRes){
				$numCount = count($objRes)-1;
				$randomIndex = rand(0, $numCount);
				
				if($wg_village->x==$objRes[$randomIndex]->x && $wg_village->y==$objRes[$randomIndex]->y){
					return false;
				}
				return $objRes[$randomIndex];
			}
		}
	}	
	return false;
}

/*
* @Author: Manhhx
* @Des: insert data
* @param: $db, $wg_village, $dataPost
* @return: null
*/
function insertRareData($db, $wg_village, $dataPost){	
	$query="INSERT INTO wg_rare_sends(village_id_from, village_id_to, ".$db->getEscaped($dataPost['rare_kind']).")";
	$query.=" VALUES($wg_village->id, ".$db->getEscaped($dataPost['village_to_id']).", 1)";
	$db->setQuery($query);
	$db->query();
	
	$query = "SELECT id FROM wg_rare_sends WHERE village_id_from=$wg_village->id ";
	$query.= " AND village_id_to=".$db->getEscaped($dataPost['village_to_id'])." AND status=0";
	$db->setQuery($query);
	$objRes=null;
	$db->loadObject($objRes);
	
	$query="INSERT INTO wg_status(object_id, village_id, type, time_begin, time_end, cost_time, level)";
	$query.=" VALUES($objRes->id, $wg_village->id, 24, '".$db->getEscaped($dataPost['startDate'])."', '".$db->getEscaped($dataPost['endDate'])."', ";
	$query.= $dataPost['dispTime'].", '0')";	
	$db->setQuery($query);	
	$db->query();	
	
	$query = "UPDATE wg_rare SET ".$db->getEscaped($dataPost['rare_kind'])."=(".$db->getEscaped($dataPost['rare_kind'])."-1) WHERE vila_id=".$wg_village->id;
	$db->setQuery($query);	
	$db->query();
	
}

/*
* @Author: Manhhx
* @Des: Lay data de hien thi va cap nhat du lieu
* @param: $db, $wg_village, $lang
* @return: $resTpl chuoi data
*/
function showRareSending($db, $wg_village, $lang){	
	$resTpl='';	
	// Gui bau vat di thanh khac
	$query="SELECT tb1.*, tb2.village_id_from, tb2.village_id_to, tb2.kim, tb2.thuy, tb2.moc, tb2.hoa, tb2.tho ";
	$query.=" FROM wg_status AS tb1, wg_rare_sends AS tb2";
	$query.=" WHERE tb2.village_id_from=tb1.village_id AND tb2.status=0 ";
	$query.=" AND tb1.status=0 AND tb1.type=24 AND tb2.village_id_from=".$wg_village->id;	
	$db->setQuery($query);
	
	$objRes=null;	
	$db->loadObject($objRes);
	$rareTime_i = 1;
	if($objRes){
		$timeNow = time();
		$endTime = strtotime($objRes->time_end);
		$dispTime = $endTime-$timeNow;
		$arrName = getRareName($objRes, $lang);
		if($dispTime >0){			
			$villageTo = getVillage($objRes->village_id_to);			
			$lang['class_rare_time_sending'] = 'rareTime'.$rareTime_i;
			$lang['time_rare_time_sending'] = ReturnTime($dispTime);		
			$lang['id_rare_time_sending'] = $_GET['id'];
			$lang['cancel_rare_time_sending'] = $objRes->object_id;			
			$lang['title_img_rare_time_sending'] = $lang['cancel'];				
			$dataRq['opt_rare'] =  $arrName[1];			
			$dataSending = getDataConfirm($db, $wg_village, $villageTo, $dataRq, $lang, 'rare_sending');
			$resTpl = $dataSending;	
			$rareTime_i++;
			
		}
		else{ // Cap nhat lai bau vat cho thanh khac
			//Gui report
			writeRareReport($db, $objRes, $arrName, $lang);			
			updateSendRare($db, $objRes, $arrName[1]);
		}
	}
	
	
	return $resTpl;
}

/*
* @Author: Manhhx
* @Des: Cap nhat lai khi da gui thanh cong
* @param: $db, $objRes, $fieldName ten loai bau vat
* @return: null
*/
function updateSendRare($db, $objRes, $fieldName){
	$query = "UPDATE wg_rare_sends SET status=1 WHERE id=".$objRes->object_id;
	$db->setQuery($query);	
	$db->query();
	
	$query = "UPDATE wg_status SET status=1 WHERE id=".$objRes->id." AND type=24";
	$db->setQuery($query);
	$db->query();
	
	$query = "SELECT * FROM wg_rare WHERE vila_id=".$objRes->village_id_to;
	$db->setQuery($query);
	$objRare =null;
	$db->loadObject($objRare);
	$query="";
	if($objRare){ //update
		$query = "UPDATE wg_rare SET $fieldName=($fieldName +1) WHERE vila_id=".$objRes->village_id_to;		
	}else{
		$query = "INSERT INTO  wg_rare(vila_id, $fieldName) VALUES($objRes->village_id_to, 1)";
	}
	$db->setQuery($query);
	$db->query();
}

/*
* @Author: Manhhx
* @Des: viet report khi gui bau vat xong
* @param: $db, $objRes, $arrName
* @return: null
*/
function writeRareReport($db, $objRes, $arrName, $lang){		
	$arrName = getRareName($objRes, $lang);		
	$villageTo = getVillage($objRes->village_id_to);	
	$villageFrom = getVillage($objRes->village_id_from);
	
	$dataRq['opt_rare'] =  $arrName[1];	
	$time = $objRes->time_end;
	$report_text = getDataConfirm($db, $villageFrom, $villageTo, $dataRq, $lang, 'rare_report_sent');	
	$title= $lang['rare_report_send'];
	InsertReport($villageFrom->user_id, $title, $time, $report_text, REPORT_SEND_RARE);
	InsertReport($villageTo->user_id, $title, $time, $report_text, REPORT_SEND_RARE);
}

/*
* @Author: Manhhx
* @Des: Xu li thong tin de hien thi
* @param: $db, $objRes, $arrName$db, $wg_village, 
* $resVillageTo, $dataRq, $parse2, $tpl
* @return: $dataRare
*/
function getDataConfirm($db, $wg_village, $resVillageTo, $dataRq, $parse2, $tpl){
	global $game_config;
	$dispS = round(S($wg_village->x, $wg_village->y, $resVillageTo->x, $resVillageTo->y));
	$heroSpeed = getHeroSpeed($db);
	$duration=round(($dispS/($heroSpeed*$game_config['k_speed']))*3600);
	
	if($wg_village->name =="NewName"){
		$villageNameFrom = $parse2[$wg_village->name];
	}else{
		$villageNameFrom = $wg_village->name;
	}
		
	if($resVillageTo->name =="NewName"){
		$villageNameTo = $parse2[$resVillageTo->name];
	}else{
		$villageNameTo = $resVillageTo->name;
	}
		
	$parse2['total_rare_time'] = ReturnTime($duration);
	$parse2['village_to_id'] = $resVillageTo->id;
	$parse2['rare_kind'] = $db->getEscaped($dataRq['opt_rare']);
	$parse2['village_name'] = $villageNameTo;
	$parse2['_x'] = $resVillageTo->x;
	$parse2['_y'] = $resVillageTo->y;

	$fromUser = GetUserInfo($wg_village->user_id);	
	$parse2['id_user_sent_rare'] = $fromUser->id; 
	$parse2['name_user_sent_rare'] = $fromUser->username;	
	$parse2['x_user_sent_rare'] = $wg_village->x;
	$parse2['y_user_sent_rare'] = $wg_village->y;
	$parse2['vname_user_sent_rare']= $villageNameFrom;
	
	
	$toUser = GetUserInfo($resVillageTo->user_id);	
	$parse2['id_user_receive_rare'] = $toUser->id; 
	$parse2['name_user_receive_rare'] = $toUser->username;	
	$parse2['x_user_receive_rare'] = $resVillageTo->x;
	$parse2['y_user_receive_rare'] = $resVillageTo->y;
	$parse2['vname_user_receive_rare']= $villageNameTo;
	
	
	$parse2['noi_dung_bau_vat_img'] = $db->getEscaped($dataRq['opt_rare']);
	$parse2['noi_dung_bau_vat_title'] = $parse2[$db->getEscaped($dataRq['opt_rare'])];
	
	$parse2['wall_to'] = $resVillageTo->name."(".$resVillageTo->x.",".$resVillageTo->y.")";
	$dataRare = parsetemplate(gettemplate($tpl),$parse2);
	return 	$dataRare;
}

/*
* @Author: Manhhx
* @Des: lay van toc hero
* @param: $db, $wg_village, $dataPost
* @return: null
*/
function getHeroSpeed($db){
	global $user;		
	$query = "SELECT speed FROM wg_heros WHERE user_id=".$user['id'];
	$db->setQuery($query);
	$objRes=null;
	$db->loadObject($objRes);
	if($objRes){
		return $objRes->speed;
	}else{
		return HERRO_SPEED;
	}
}

/*
* @Author: Manhhx
* @Des: tra lai nhu cu khi click cancel
* @param: $db, $wg_village, $dataPost
* @return: null
*/
function roolBackSendRare($db, $wg_village, $dataPost){	
	$query = "SELECT * FROM wg_rare_sends WHERE id=".$db->getEscaped($dataPost['cancel_rare'])." AND village_id_from=$wg_village->id AND status=0";
	$db->setQuery($query);
	$objRes=null;
	$db->loadObject($objRes);
	
	if($objRes){
		$arrName = getRareName($objRes, $lang);	
			
		$query="DELETE FROM wg_rare_sends WHERE id=".$db->getEscaped($dataPost['cancel_rare']);
		$db->setQuery($query);
		$db->query();		
		
		$query="DELETE FROM wg_status WHERE object_id=".$db->getEscaped($dataPost['cancel_rare']);
		$query.=" AND village_id=$wg_village->id AND type=24 AND status=0";
		$db->setQuery($query);
		$db->query();		
		
		$query = "UPDATE wg_rare SET ".$arrName[1]."=(".$arrName[1]."+1) WHERE vila_id=".$wg_village->id;
		$db->setQuery($query);	
		$db->query();
	}	
}

/*
* @Author: Manhhx
* @Des: Lay ten tieng viet de hien thi va ten field
* @param: $objRare, $lang
* @return: $arrName array
*/
function getRareName($objRare, $lang){
	$arrName =  array();
	if($objRare->kim){		
		$arrName[0] = $lang["kim"];
		$arrName[1] = "kim";
	}
	if($objRare->thuy){		
		$arrName[0] = $lang["thuy"];
		$arrName[1] = "thuy";
	}
	if($objRare->moc){		
		$arrName[0] = $lang["moc"];
		$arrName[1] = "moc";
	}
	if($objRare->hoa){		
		$arrName[0] = $lang["hoa"];
		$arrName[1] = "hoa";
	}
	if($objRare->tho){		
		$arrName[0] = $lang["tho"];
		$arrName[1] = "tho";
	}
	return 	$arrName;
}

?>
