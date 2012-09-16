<?php 
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);

if(!check_user()){ header("Location: login.php"); }
global $user;
if($user['authlevel'] <5)
{
	header("Location:index.php");
	exit();
}
else
{
	global $db,$max_x,$max_y;
	$max_x = loadConfig('max_x');
	$max_y = loadConfig('max_y');
	set_time_limit(10000);
	genTop();
	genRight();
	genBottom();
	genLeft();

	genTopRight();
	genRightBottom();
	genBottomLeft();
	genLeftTop();	
	header("location: registrationlist.php");
}
function genTop(){
	global $db,$max_x,$max_y;
	for($i=0-$max_x;$i<=$max_x;$i++){
		for($j=0-$max_y;$j<(6-$max_y);$j++){
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql="SELECT kind_id FROM wg_villages_map WHERE id=$v_id";
			$db->setQuery($sql);
			$kind = $db->loadResult();
			$j_ = $j + 2*$max_y +1;
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j_+$max_y+7);
			$sql = "INSERT INTO `wg_villages_map` (`id`,`x`, `y`, `kind_id`) VALUES ('$v_id','$i', '$j_', '$kind')";
			$db->setQuery($sql);
			$db->query();
		}
	}
}
function genRight(){
	global $db,$max_x,$max_y;
	for($i=0-$max_x;$i<6-$max_x;$i++){
		for($j=0-$max_y;$j<=$max_y;$j++){
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql="SELECT kind_id FROM wg_villages_map WHERE id=$v_id";
			$db->setQuery($sql);
			$kind = $db->loadResult();
			
			$i_ = $i + 2*$max_x +1;
			$v_id = ($i_+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql = "INSERT INTO `wg_villages_map` (`id`,`x`, `y`, `kind_id`) VALUES ('$v_id','$i_', '$j', '$kind')";
			$db->setQuery($sql);
			$db->query();
		}
	}
}
function genBottom(){
	global $db,$max_x,$max_y;
	for($i=0-$max_x;$i<=$max_x;$i++){
		for($j=$max_y;$j>$max_y-6;$j--){
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql="SELECT kind_id FROM wg_villages_map WHERE id=$v_id";
			$db->setQuery($sql);
			$kind = $db->loadResult();
			
			$j_ = $j - 2*$max_y -1;
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j_+$max_y+7);
			$sql = "INSERT INTO `wg_villages_map` (`id`,`x`, `y`, `kind_id`) VALUES ('$v_id','$i', '$j_', '$kind')";
			$db->setQuery($sql);
			$db->query();
		}
	}
}
function genLeft(){
	global $db,$max_x,$max_y;
	for($i=$max_x;$i>$max_x-6;$i--){
		for($j=0-$max_y;$j<=$max_y;$j++){
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql="SELECT kind_id FROM wg_villages_map WHERE id=$v_id";
			$db->setQuery($sql);
			$kind = $db->loadResult();
			
			$i_ = $i - 2*$max_x -1;
			$v_id = ($i_+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql = "INSERT INTO `wg_villages_map` (`id`,`x`, `y`, `kind_id`) VALUES ('$v_id','$i_', '$j', '$kind')";
			$db->setQuery($sql);
			$db->query();
		}
	}
}

function genTopRight(){
	global $db,$max_x,$max_y;
	for($i=$max_x;$i>$max_x-6;$i--){
		for($j=$max_y;$j>$max_y-6;$j--){
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql="SELECT kind_id FROM wg_villages_map WHERE id=$v_id";
			$db->setQuery($sql);
			$kind = $db->loadResult();
			
			$i_ = $i - 2*$max_x -1;
			$j_ = $j - 2*$max_y -1;
			$v_id = ($i_+$max_x+6)*(2*$max_x+13)+($j_+$max_y+7);
			$sql = "INSERT INTO `wg_villages_map` (`id`,`x`, `y`, `kind_id`) VALUES ('$v_id','$i_', '$j_', '$kind')";
			$db->setQuery($sql);
			$db->query();
		}
	}
}
function genRightBottom(){
	global $db,$max_x,$max_y;
	for($i=$max_x;$i>$max_x-6;$i--){
		for($j=0-$max_y;$j<6-$max_y;$j++){
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql="SELECT kind_id FROM wg_villages_map WHERE id=$v_id";
			$db->setQuery($sql);
			$kind = $db->loadResult();
			
			$i_ = $i - 2*$max_x -1;
			$j_ = $j + 2*$max_y +1;
			$v_id = ($i_+$max_x+6)*(2*$max_x+13)+($j_+$max_y+7);
			$sql = "INSERT INTO `wg_villages_map` (`id`,`x`, `y`, `kind_id`) VALUES ('$v_id','$i_', '$j_', '$kind')";
			$db->setQuery($sql);
			$db->query();
		}
	}
}
function genBottomLeft(){
	global $db,$max_x,$max_y;
	for($i=0-$max_x;$i<6-$max_x;$i++){
		for($j=0-$max_y;$j<6-$max_y;$j++){
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql="SELECT kind_id FROM wg_villages_map WHERE id=$v_id";
			$db->setQuery($sql);
			$kind = $db->loadResult();
			
			$i_ = $i + 2*$max_x +1;
			$j_ = $j + 2*$max_y +1;
			$v_id = ($i_+$max_x+6)*(2*$max_x+13)+($j_+$max_y+7);
			$sql = "INSERT INTO `wg_villages_map` (`id`,`x`, `y`, `kind_id`) VALUES ('$v_id','$i_', '$j_', '$kind')";
			$db->setQuery($sql);
			$db->query();
		}
	}
}
function genLeftTop(){
	global $db,$max_x,$max_y;
	for($i=0-$max_x;$i<6-$max_x;$i++){
		for($j=$max_y;$j>$max_y-6;$j--){
			$v_id = ($i+$max_x+6)*(2*$max_x+13)+($j+$max_y+7);
			$sql="SELECT kind_id FROM wg_villages_map WHERE id=$v_id";
			$db->setQuery($sql);
			$kind = $db->loadResult();
			
			$i_ = $i + 2*$max_x +1;
			$j_ = $j - 2*$max_y -1;
			$v_id = ($i_+$max_x+6)*(2*$max_x+13)+($j_+$max_y+7);
			$sql = "INSERT INTO `wg_villages_map` (`id`,`x`, `y`, `kind_id`) VALUES ('$v_id','$i_', '$j_', '$kind')";
			$db->setQuery($sql);
			$db->query();
		}
	}
}
function loadConfig($name)
{
	global $db;
	$sql="SELECT config_value FROM wg_config WHERE config_name ='$name'";
	$db->setQuery($sql);
	return $db->loadResult();
}
?>
