<?php
function check_user(){
	
	$row = checkcookies();	
	if($row != false){
		global $user;
		$user = $row;
		return true;
	}
	return false;
}

function is_email($email){
	
	return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|Num|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
	
}
//
function checkcookies(){
	global $db,$lang,$game_config,$ugamela_root_path,$phpEx;
	$row = false;	
	if (isset($_COOKIE['VIEW_COOKIE_NAME']))
	{
		$theuser = explode(" ",$_COOKIE['VIEW_COOKIE_NAME']);
		$query = "SELECT * FROM wg_users WHERE username='$theuser[1]'";
		$db->setQuery($query);
		$row = $db->loadAssocList();
		$row = $row[0];
		if(!$row){
			setcookie('VIEW_COOKIE_NAME', NULL, time()-100000, "/", "", 0);
			$row = false;			
		}
		if ($row["id"] != $theuser[0])
		{
			setcookie('VIEW_COOKIE_NAME', NULL, time()-100000, "/", "", 0);
			$row = false;			
		}
		if ($_SESSION['password_viewuser'] !== $theuser[2])
		{
			setcookie('VIEW_COOKIE_NAME', NULL, time()-100000, "/", "", 0);
			$row = false;			
		}
		$_SESSION['username'] = $theuser[1];
		$_SESSION['id'] = $theuser[0];
    }
	unset($dbsettings);
	return $row;
}

//
function parsetemplate($template, $array){

	foreach($array as $a => $b) {
		$template = str_replace("{{$a}}", $b, $template);
	}
	return $template;
}

function gettemplate($templatename)
{
	global $ugamela_root_path;
	$filename =  $ugamela_root_path . TEMPLATE_DIR . TEMPLATE_NAME . '/' . $templatename . ".tpl";
	return ReadFromFile($filename);	
}


function includeLang($filename,$ext='.mo')
{
	global $ugamela_root_path,$lang;

	include($ugamela_root_path."language/".DEFAULT_LANG.'/'.$filename.$ext);

}

function ReadFromFile($filename){
	$f = @fopen($filename,"r");
	$content = @fread($f,filesize($filename));
	@fclose($f);
	return $content;

}

function SaveToFile($filename,$content){

	$f = fopen($filename,"w");
	fputs($f,"$content");
	fclose($f);

}

function message($mes,$title='Error',$dest = "",$time = "3"){
	
	$parse['color'] = $color;
	$parse['title'] = $title;
	$parse['mes'] = $mes;

	$page .= parsetemplate(gettemplate('message_body'), $parse);

	display($page,$title,false,false,(($dest!='')?"<meta http-equiv=\"refresh\" content=\"$time;URL='$dest';\">":''));

}

function display($page, $title = '', $topnav = true, $rightnav=true, $metatags=''){
	global $db, $link,$game_config,$debug,$user,$wg_village;
	echo_head($title,$metatags);
	echo "$page";
	if($rightnav)
	{
		echo_rightnav();
	}
	if($topnav){ echo_topnav();}	
	echo '</div><script type="text/javascript" src="js/tipbox.js"></script></body>
	</html>';
	
	unsetVillageParam($wg_village);
	$db->updateObject("wg_villages", $wg_village, "id");
	
	if(isset($link)) mysql_close();
	die();
}
function display2($page,$title = '',$metatags=''){
	global $link;
	echo "$page";
	if(isset($link)) mysql_close();
	die();	
}

function display3($page,$title = '',$metatags=''){
	global $link,$game_config,$debug,$user;
	echo_head($title,$metatags);
	echo "$page";
	if(isset($link)) mysql_close();
	die();	
}

function display_ctc($page,$title = '',$metatags=''){
	global $link,$game_config,$debug,$user;
	echoHeader_ctc($title,$metatags);
	echo "$page";
	if(isset($link)) mysql_close();
	die();	
}

function display1($page,$title = '',$topnav = true,$metatags=''){
	global $link,$game_config,$debug,$user;
	echo_head($title,$metatags);
	echo "$page";

	echo echo_foot();
	if(isset($link)) mysql_close();
	die();
}
function echo_foot(){
	global $game_config;
	$parse['copyright'] =$game_config['copyright'];	
	echo parsetemplate(gettemplate('simple_footer'), $parse);
}

function CheckUserExist($user){
  global $lang,$link;
  
	if(!$user){
		if(isset($link)) mysql_close();
		error($lang['Please_Login'],$lang['Error']);
	}
}

function pretty_time($seconds){
	
	$day = floor($seconds / (24*3600));
	$hs = floor($seconds / 3600 % 24);
	$min = floor($seconds  / 60 % 60);
	$seg = floor($seconds / 1 % 60);
	
	$time = '';
	if($day != 0){ $time .= $day.'d ';}
	if($hs != 0){ $time .= $hs.'h ';}
	if($min != 0){ $time .= $min.'m ';}
	$time .= $seg.'s';
	
	return $time;
}

function pretty_time_hour($seconds){
	
	$min = floor($seconds  / 60 % 60);

	$time = '';
	
	if($min != 0){ $time .= $min.'min ';}
	return $time;
}

function echo_topnav(){
	global $user, $db,$village;
	include_once("func_security.php");
	if(!$village){
		$village_id = $user['villages_id'];
	}else{
		$village_id=$village;
	}
	include_once("function_resource.php");
	if($village_id){
		echo GetRSStatus();
	}
}
function echo_rightnav()
{
	global $user, $db,$village;
	include_once("func_security.php");
	include_once("function_allian.php");
	if(!isset($village))
	{
		$village = $user['villages_id'];
	}
	
	if($village)
	{
		echo listVillageAndAllyMember($user["id"],$village);
	}
}
function echo_head($title = '',$metatags=''){

	global $db,$user,$lang,$game_config;
	includeLang('simple_header');
	$parse=$lang;
	$parse['title'] =$game_config['game_name'];
	$parse['metatags'] = $metatags;
	$parse['time_server']=date("H:i:s",time());
	if(check_user())
	{
		$parse['in_out'] ="logout.php";
		$parse['name'] =$lang['Sign out'];
		/*------------Hien thi trang thai hinh anh Mess va Report-------------------------------*/
		$sql = "SELECT id FROM wg_messages WHERE id_user=".$user["id"]." AND from_id >0 AND status=0 LIMIT 1";
		$db->setQuery($sql);
		$wg_messages=NULL;
		$db->loadObject($wg_messages);
		$parse['n6']='icon6';
		if($wg_messages)
		{	
			$parse['n6']='icon6a';
		}
		$sql = "SELECT  id FROM wg_reports WHERE user_id=".$user["id"]." AND status=0 LIMIT 1";
		$db->setQuery($sql);
		$wg_reports=NULL;
		$db->loadObject($wg_reports);
		$parse['n5']='icon5';
		if($wg_reports)
		{	
			$parse['n5']='icon5a';
		}
		$parse['show_date']=showDate();
		
		$sql = "SELECT  time FROM wg_securities WHERE username='".$user["username"]."' ORDER BY id DESC LIMIT 1";
		$db->setQuery($sql);		
		$last_login=$db->loadResult();
		$parse['show_time_gamer']='<span style="float: right;padding-right:15px;"><strong>'.$lang['Not login'].'</strong></span>';
		if($last_login)
		{
			$parse['show_time_gamer']='<span style="float: right;padding-right:15px;">'.$lang['last login'].' : <strong>'.$last_login.'</strong></span>';	
		}			
		echo parsetemplate(gettemplate('simple_header'), $parse);
	}
	else
	{
		$parse['in_out'] ="login.php";
		$parse['name'] =$lang['Login'];
		$parse['show_date']=showDate();		
		echo parsetemplate(gettemplate('simple_header2'), $parse);
	}
	return false;
}

function echoHeader_ctc($title = '', $addTags = '')
{
	global $game_config;
	$parse['title'] =$game_config['game_name'];
	$parse['add_tags'] = $addTags;
	echo parsetemplate(gettemplate('ctc/ctc_header'), $parse);
}

function showDate()
{
	global $lang;
	includeLang('date');
	$date=array("Sun"=>$lang['Sun'],"Mon"=>$lang['Mon'],"Tue"=>$lang['Tue'],"Wed"=>$lang['Wed'],"Thu"=>$lang['Thu'],"Fri"=>$lang['Fri'],"Sat"=>$lang['Sat']);
	$day = date("D");
	$dayV = $date[$day];
	$result=$dayV.', '.$lang['day'].' '.date("d").'-'.date("m").'-'.date("Y");
	return $result;
}

function building_time($time){
  global $lang;

  return "<br>{$lang['ConstructionTime']}: ".pretty_time($time);  
  
}


function rev_time($seconds){
	$days=floor($seconds/86400);
	$hours=(floor(($seconds%86400)/3600));
	$minutes=floor(($seconds%3600)/60);
	$secs=$seconds%60;
	$month_len=array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	$year=1970;
	$done=0;
	$month_id=1;
	while($days>$month_lenght)
	{
		$month_lenght=($month_id==2 ? ($year%4==0 ? 29 : $month_len[$month_id]):$month_len[$month_id]);
		$days-=$month_lenght;
		if ($month_id>12)
		{
			$month_id=1;
			$year++;
		}
		else
			$month_id++;
	}
	$days++;
	$days=($days<10 ? "0".$days : $days);
	$month=($month_id<10 ? "0".$month_id : $month_id);
	$hours=($hours<10 ? "0".$hours : $hours);
	$minutes=($minutes<10 ? "0".$minutes : $minutes);
	$secs=($secs<10 ? "0".$secs : $secs);
	$ret=($seconds>0 ? "$year-$month-$days<br>GMT $hours:$minutes:$secs" : "" );
	return $ret;
}

//Tinh khoang cach giua hai lang.
function S($x1, $y1, $x2, $y2){
	global $game_config;
	$s1=D($x1, $y1, $x2, $y2);
	if($x1>$x2){
		$xtemp=-2*$game_config['max_x']+$x1;
	}else{
		$xtemp=2*$game_config['max_x']+$x1;
	}
	$s2=D($xtemp, $y1, $x2, $y2);
	if($y1>$y2){
		$ytemp=-2*$game_config['max_y']+$y1;
	}else{
		$ytemp=2*$game_config['max_y']+$y1;
	}
	$s3=D($x1, $ytemp, $x2, $y2);
	$s4=D($xtemp, $ytemp, $x2, $y2);
	return min($s1, $s2, $s3, $s4);
}

function D($x1, $y1, $x2, $y2){
	return sqrt(pow(($x1-$x2), 2)+pow(($y1-$y2), 2));
}

/**
 * Tinh khoang thoi gian di chuyen (don vi: second)
 */
function GetDuration($s, $v){
	if($v!=0){
		return ($s/$v)*3600;	
	}
	return false;
}

function TimeToString($time){
	  $hours = intval($time / 3600);
	  $mins = intval(($time - $hours * 3600) / 60); 
	  $secs = intval($time - $hours * 3600 - $mins * 60);
	  if($hours<10){
		$hours='0'.$hours;
	  }
	  if($mins<10){
		$mins='0'.$mins;
	  }
	  if($secs<10){
		$secs='0'.$secs;
	  }
	  return $hours.':'.$mins.':'.$secs;
}

function InsertReport($uer_id, $title, $time, $report_text, $type=0, $uer_id_2=0)
{
	global $db;
	$report_text = $db->getEscaped ($report_text);
	$title = $db->getEscaped ($title);
	$sql="INSERT INTO wg_reports (`user_id`, title, time, report_text, type, status) VALUES ($uer_id, '$title', '$time', '$report_text', $type, 0)";
	$db->setQuery($sql);
	if(!$db->query())
	{
		globalError2('function InsertReport:'.$sql);
	}
	$sql_bk="INSERT INTO wg_reports_bk (user_id, `user_id_2`, title, time, report_text, type, status) VALUES ($uer_id, '$uer_id_2', '$title', '$time', '$report_text', $type, 0)";
	$db->setQuery($sql_bk);
	if(!$db->query())
	{
		globalError2('function InsertReport:'.$sql_bk);
	}
}

function doAnounment(){
	global $db,$village,$game_config,$wg_village_kinds,$wg_village,$wg_buildings,$user,$wg_status,$ugamela_root_path;
	
	if($user['anounment']!='') { 
		include($ugamela_root_path . 'anounments/'.$user['anounment'].'.php');
	}

	return true;
}

function checkRequestTime()
{
	global $game_config;
	session_start();
	$deltaTime = microtime(true)*1000-$_SESSION['request_time'];
	if($deltaTime<1500){ 
		$_SESSION['request_n'] = 1 + $_SESSION['request_n'];
		if($_SESSION['request_n']>10){ 
			setcookie($game_config['COOKIE_NAME'], NULL, time()-100000, "/", "", 0);
			die("Hacking attempt. Too many requests!"); 
		}
	}else{
		$_SESSION['request_n'] = 0;
	}
	$_SESSION['request_time'] = microtime(true)*1000;
}

/**
 * them mot status.
 */
function InsertStatus($village_id, $object_id, $time_begin, $time_end, $cost_time, $type, $order=0)
{
	global $db;
	$sql="INSERT INTO wg_status (`village_id`, `object_id`, `type`, `time_begin`, `time_end`, `cost_time`, `status`, `order_`) VALUES ($village_id, $object_id, $type, '$time_begin', '$time_end', $cost_time, 0, $order)";
	$db->setQuery($sql);
	if(!$db->query())
	{
		globalError2("function InsertStatus(:".$sql);
	}
}

/**
 * Lay thong tin cua mot user theo id cua lang
 */
function GetUserInfo($id, $str=''){
	global $db;
	if($str==''){
		$sql="SELECT * ";
	}else{
		$sql="SELECT $str ";
	}	
	$sql.="FROM wg_users ";
	$sql.="WHERE id=$id";
	$db->setQuery($sql);
	$db->loadObject($user);
	if($user){
		return $user;
	}else{
		return false;
	}
}

/**
*	Lay thong tin bo chi huy cua mot lang.
*/
function GetRallyPoint($village_id){
	global $db;
	include_once("function_troop.php");
	if(!$village_id){
		$village_id=$_SESSION['villa_id_cookie'];
	}
	$buildingTypeID=27;
	$sql="SELECT id, wg_buildings.index FROM wg_buildings WHERE vila_id=$village_id AND type_id=$buildingTypeID";
	$db->setQuery($sql);
	$building=null;
	$db->loadObject($building);
	if($building){
		return $building;
	}else{
		return false;
	}	
}


/**
 * Tao link tu một lang toi rally point de send troop
 */
function LinkSendTroop($village_id, $village_defend_x, $village_defend_y){
	global $lang;
	includelang("troop");
	$rally=GetRallyPoint($village_id);
	if($rally){
		return "<div class=\"nbr\"><a href=\"build.php?id=$rally->index&t=1&vdx=$village_defend_x&vdy=$village_defend_y\">&raquo; ".$lang['send_troop_link']."</a></div>";
	}else{
		return "";
	}	
}


/**
 * Tao link tu một lang toi rally point de send troop
 */
function LinkSendMerchant($village_to_x, $village_to_y){
	global $lang, $wg_village, $wg_buildings;
	includeLang("trade");
	$market=null;
	foreach($wg_buildings as $building){
		if($building->type_id==13){
			$market=$building;
			break;
		}
	}
	
	if($market){
		return "<div class=\"nbr\"><a href=\"build.php?id=$market->index&vtx=$village_to_x&vty=$village_to_y\">&raquo; ".$lang['send_merchant_link']."</a></div>";
	}else{
		return "";
	}	
}


/**
* @Author: ManhHX
* @Des: Cap nhat status len 1
* @param: null
* @return: null
*/
function executeUpdateStatus($stsId){
	global $db; 
	//$sSql = "UPDATE wg_status SET status=1 WHERE UNIX_TIMESTAMP(time_end) <= UNIX_TIMESTAMP('$ymdhis')";
	$sSql = "UPDATE wg_status SET status=1 WHERE id=$stsId";	
	$db->setQuery($sSql);
	$resUdp = $db->query();
	//if(!$resUdp){
		//globalError2("Update status on executeUpdateStatus func");
	//}
}
	
/**
* @Author: ManhHX
* @Des: Lay thong tin status mot lan
* @param: $vId: village id
* @return: $objResSts object of status
*/
function doAllStatus()
{
	return true;
}


/**
 * @author Le Van Tu
 * @des Lay tat ca thong tin ve mot lang theo id
 * @param id
 * @param $str danh sach truong can select (VD: $str="id, name")
 */
function getVillage($id, $str=""){
	global $db, $user;
	$id = $db->getEscaped($id);
	if($str==""){
		$sql="SELECT * FROM wg_villages WHERE id=$id";
	}else{
		$sql="SELECT ".$str." FROM wg_villages WHERE id=$id";
	}
	$db->setQuery($sql);
	$db->loadObject($village);
	return $village;	
}

/**
 * @author Le Van Tu
 * @des unset cac bien khong co trong bang wg_village cua doi tuong $wg_village
 * @return $wg_village doi duong da duoc xu ly
 */
function unsetVillageParam(&$wg_village){
	unset($wg_village->speedIncreaseRS1);
	unset($wg_village->speedIncreaseRS2);
	unset($wg_village->speedIncreaseRS3);
	unset($wg_village->speedIncreaseRS4);
	
	unset($wg_village->speedIncreaseRS4Real);
	
	unset($wg_village->capa123);
	unset($wg_village->capa4);
	
	unset($wg_village->troop_keep);
	unset($wg_village->workers);

	unset($wg_village->merchant_underaway);
}

/**
 * @author Le Van Tu
 * @des lay thong tin building cua mot lang
 */
function getBuildings($village_id, $str=""){
	global $db;
	if($str==""){
		$sql="SELECT * FROM wg_buildings WHERE vila_id=$village_id ORDER BY wg_buildings.index ASC";
	}else{
		$sql="SELECT ".$str." FROM wg_buildings WHERE vila_id=$village_id ORDER BY wg_buildings.index ASC";
	}	
	$db->setQuery($sql);
	return $db->loadObjectList();
}


/**
* @Author: ManhHX
* @Des: Lay thong tin status dang duoc xu li
* @param: null
* @return: $objResSts object of status
*/
function getStatusProcessing(){
	global $db, $village; 
	$sSql = "SELECT * FROM wg_status WHERE village_id=$village ";
	$sSql.= "AND status=0 AND (type<=3 OR type=17) ";
	$db->setQuery($sSql);	
	$objResSts = $db->loadObjectList();
	return 	$objResSts;
}

/*
* @Author: Manhhx
* @Des: Cap nhat lai khi da gui thanh cong
* @param: $objSts, $objSentRare, $fieldName ten loai bau vat
* @return: null
*/
function updateSendRareOff($objSts, $objSentRare, $fieldName){
	global $db;
	$query = "UPDATE wg_rare_sends SET status=1 WHERE id=".$objSts->object_id;
	$db->setQuery($query);	
	$db->query();	
	
	$query = "SELECT * FROM wg_rare WHERE vila_id=".$objSentRare->village_id_to;
	$db->setQuery($query);
	$objRare =null;
	$db->loadObject($objRare);
	$query="";
	if($objRare){ //update
		$query = "UPDATE wg_rare SET $fieldName=($fieldName +1) WHERE vila_id=".$objSentRare->village_id_to;		
	}else{
		$query = "INSERT INTO  wg_rare(vila_id, $fieldName) VALUES($objSentRare->village_id_to, 1)";
	}
	$db->setQuery($query);
	$db->query();
}

/**
* @Author: ManhHX
* @Des: Xuat report va cap nhat data cho nhung bau vat
*  da gui thanh cong
* @param: $objSts status
* @return: $objResSts object of status
*/
function executeSentRareOff($objSts){
	global $db, $village, $lang; 	
	includeLang("build");
	//$parse=$lang;	
	
	$query="SELECT tb1.* ";
	$query.=" FROM wg_rare_sends AS tb1";
	$query.=" WHERE tb1.id=$objSts->object_id AND tb1.status=0 ";		
	$db->setQuery($query);	
	$objSentRare=null;	
	$db->loadObject($objSentRare);
	
	//Xuat report
	$villageFrom = getVillage($objSentRare->village_id_from);
	$villageTo = getVillage($objSentRare->village_id_to);
	$time_end = $objSts->time_end;
	$arrName = getRareNameOff($objSentRare, $lang);	
	$dataRq['opt_rare'] = $arrName[1];
	$title= $lang['rare_report_send'];
	$report_text = prepareRareDataReportOff($villageFrom, $villageTo, $dataRq, $lang, 'rare_report_sent');	
	
	InsertReport($villageFrom->user_id, $title, $time_end, $report_text, REPORT_SEND_RARE);
	InsertReport($villageTo->user_id, $title, $time_end, $report_text, REPORT_SEND_RARE);
	//Ket thuc xuat report	
	
	//Cap nhat lai bau vat cho thanh khac
	updateSendRareOff($objSts, $objSentRare, $arrName[1]);
}

/*
* @Author: Manhhx
* @Des: Xu li thong tin de tao report
* @param: $villageFrom, $villageTo $dataRq, $parse2, 
* $tpl
* @return: $dataRare
*/
function prepareRareDataReportOff($villageFrom, $villageTo, $dataRq, $parse2, $tpl){
	global $game_config;
	$dispTime = round(S($villageFrom->x, $villageFrom->y, $villageTo->x, $villageTo->y));
	$heroSpeed = getHeroSpeedOff($villageFrom);
	$dispTime=round(($dispTime/($heroSpeed*$game_config['k_speed']))*3600);

	if($villageFrom->name =="NewName"){
		$villageNameFrom = $parse2[$villageFrom->name];
	}else{
		$villageNameFrom = $villageFrom->name;
	}
	
	if($villageTo->name =="NewName"){
		$villageNameTo = $parse2[$villageTo->name];
	}else{
		$villageNameTo = $villageTo->name;
	}
	
	$parse2['total_rare_time'] = ReturnTime($dispTime);
	$parse2['village_to_id'] = $villageTo->id;
	$parse2['rare_kind'] = $dataRq['opt_rare'];
	$parse2['village_name'] = $villageNameTo;
	$parse2['_x'] = $villageTo->x;
	$parse2['_y'] = $villageTo->y;

	$fromUser = GetUserInfo($villageFrom->user_id);				
	$parse2['id_user_sent_rare'] = $fromUser->id; 
	$parse2['name_user_sent_rare'] = $fromUser->username;	
	$parse2['x_user_sent_rare'] = $villageFrom->x;
	$parse2['y_user_sent_rare'] = $villageFrom->y;
	$parse2['vname_user_sent_rare']= $villageNameFrom;
	
	$toUser = GetUserInfo($villageTo->user_id);	
	$parse2['id_user_receive_rare'] = $toUser->id; 
	$parse2['name_user_receive_rare'] = $toUser->username;	
	$parse2['x_user_receive_rare'] = $villageTo->x;
	$parse2['y_user_receive_rare'] = $villageTo->y;
	$parse2['vname_user_receive_rare']= $villageNameTo;
	
	//$parse2['noi_dung_bau_vat'] = $parse2[$dataRq['opt_rare']];
	$parse2['noi_dung_bau_vat_img'] = $dataRq['opt_rare'];
	$parse2['noi_dung_bau_vat_title'] = $parse2[$dataRq['opt_rare']];
	
	$parse2['wall_to'] = $villageTo->name."(".$villageTo->x.",".$villageTo->y.")";
	$dataRare = parsetemplate(gettemplate($tpl),$parse2);
	return 	$dataRare;
}

/*
* @Author: Manhhx
* @Des: lay van toc hero
* @param: $db, $wg_village, $dataPost
* @return: null
*/
function getHeroSpeedOff($villageFrom){
	global $db;		
	$query = "SELECT speed FROM wg_heros WHERE user_id=".$villageFrom->user_id;
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
* @Des: Lay ten tieng viet de hien thi va ten field
* @param: $objRare, $lang
* @return: $arrName array
*/
function getRareNameOff($objRare, $lang){
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

/**
 * @author Le Van Tu
 * @todo loc ma sql khi lay gia tri request
 */
function getRequest($name, $checkInt = 0){
	global $db;
	$rs = $_REQUEST[$name];
	if($checkInt){
		$rs = intval($rs);
	}
	return $db->getEscaped($rs);
}

function GetPlayerName($village_id){
	global $db;
	$sql="SELECT * FROM wg_villages WHERE id=$village_id";
	$db->setQuery($sql);
	$village=null;
	$db->loadObject($village);
	if($village){
		$sql="SELECT username FROM wg_users WHERE id=$village->user_id";
		$db->setQuery($sql);
		$user=null;
		$db->loadObject($user);
		if($user){
			return $user->username;
		}
	}
	return false;
}

?>
