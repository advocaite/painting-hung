<?php 
ob_start ();
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ('includes/db_connect.' . $phpEx);
include ('includes/common.' . $phpEx);


if(!check_user()){ header("Location: login.php"); }
if($user['authlevel'] <5)
{
	header("Location:index.php");
	exit();
}
else
{

	if($_POST)
	{	
		EmptyTable();
		$parse['message']=RandMap();
	}
	else{
		$parse['message']="";
	}
	
	$page = parsetemplate(gettemplate('admin/createmap_body'), $parse);
	displayAdmin($page,$lang['Registration List']);
}	
function EmptyTable()
{
	global $db;
	$sql="TRUNCATE TABLE `wg_villages_map`";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_attack";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_top10";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_attack_troop";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_attack_hero";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_buildings";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_heros";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_messages ";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_reports";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_resource_orders";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_status";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_troop_armour";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_troop_items";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_troop_researched";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_troop_train";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_troop_villa";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_villages";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_plus";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
	
	$sql="TRUNCATE TABLE wg_users";
	$db->setQuery($sql);//die($sql);
	if(!$db->query()){
		die("error1!!!EmptyTable");
	}
}

function RandMap(){	
	global $db,$game_config;
	$max_x =loadConfig('max_x');
	$max_y =loadConfig('max_y');	
	$kind_ = loadConfigKind($max_x,$max_y);
	$kind_result = array();
	for($i=1;$i<=14;$i++){
		$kind_result[$i] = 0;
	}

	$result.= '<div style="float:left">Requirement values: <br/><table border="0">';
	for($i=1;$i<=14;$i++){
		$result.= "<tr><td>Kind $i:</td><td align='right'>".$kind_[$i]."</td></tr>";
	}
	$result.= '</table></div>';
	for($i=14;$i>1;$i--){
		for($j=$i-1;$j>=1;$j--){
			$kind_[$i] +=  $kind_[$j];
		}
	}
	set_time_limit(10000);
	$sql = "TRUNCATE TABLE `wg_villages_map`";
	$db->setQuery($sql);
	$db->query();
	$num_ = (2*$max_x+1)*(2*$max_y+1);
	for($i=(0-$max_x);$i<=$max_x;$i++){
	  for($j=(0-$max_y);$j<=$max_y;$j++){
		$temp = rand(1, $num_); 
		$kind = 3;
		if($temp<$kind_['1']){			$kind = 1; $kind_result['1'] += 1; }
		else if($temp<$kind_['2']){		$kind = 2; $kind_result['2'] += 1; }
		else if($temp<$kind_['3']){		$kind = 3; $kind_result['3'] += 1; }
		else if($temp<$kind_['4']){		$kind = 4; $kind_result['4'] += 1; }
		else if($temp<$kind_['5']){		$kind = 5; $kind_result['5'] += 1; }
		else if($temp<$kind_['6']){		$kind = 6; $kind_result['6'] += 1; }
		else if($temp<$kind_['7']){		$kind = 7; $kind_result['7'] += 1; }
		else if($temp<$kind_['8']){		$kind = 8; $kind_result['8'] += 1; }
		else if($temp<$kind_['9']){		$kind = 9; $kind_result['9'] += 1; }
		else if($temp<$kind_['10']){	$kind = 10; $kind_result['10'] += 1; }
		else if($temp<$kind_['11']){	$kind = 11; $kind_result['11'] += 1; }
		else if($temp<$kind_['12']){	$kind = 12; $kind_result['12'] += 1; }
		else if($temp<$kind_['13']){	$kind = 13; $kind_result['13'] += 1; }
		else if($temp<$kind_['14']){	$kind = 14; $kind_result['14'] += 1; }
		else {		$kind = 3; $kind_result['3'] += 1; }
		$v_id = ($i+($max_x+6))*($max_x*2+13)+($j+($max_x+7));
		$sql = "INSERT INTO `wg_villages_map` (`id`,`x`, `y`, `kind_id`) VALUES ('$v_id','$i', '$j', '$kind')";
		$db->setQuery($sql);
		$db->query();
	  }
	}
	
	$result.= '<div style="float:left; padding-left:50px">Result values: <br/><table border="0">';
	for($i=1;$i<=14;$i++){
		$result.= "<tr><td>Kind $i:</td><td align='right'>".$kind_result[$i]."</td></tr>";
	}
	$result.= '</table></div>';
	$result.= '<div style="float:left; padding-left:50px"><a href="append_map.php">Append Map</a> </div><br/>';
	return $result;
}
function loadConfigKind($max_x,$max_y){
	global $db;
	$kind_ = array();
	$sql="SELECT name, value FROM wg_game_configs WHERE name like 'villa_%'";
	$db->setQuery($sql);
	$rows = $db->loadObjectList();
	$i = 1;
	$num_ = (2*$max_x+1)*(2*$max_y+1);
	foreach($rows as $row){
		if($row->name == "villa_".$i){	$kind_[$i] = floor($row->value*$num_/100); $i++; }
	}
	return $kind_;
}
function loadConfig($name)
{
	global $db;
	$sql="SELECT config_value FROM wg_config WHERE config_name = '$name'";
	$db->setQuery($sql);
	return $db->loadResult();
}
?>