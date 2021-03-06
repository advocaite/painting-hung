<?php 
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');

if(!check_user()){ header("Location: login.php"); }
if($user['authlevel'] <5)
{
	header("Location:index.php");
	exit();
}
else
{
	set_time_limit ( 10000 );
	$dataPost = $_POST;
	$parse["error_message"]='';
	if($dataPost){
		if(!is_numeric($dataPost["num_rare"])){
			$parse["error_message"] = "Hãy nhập số bộ báu vật bằng ký tự số";
			$parse["num_rare"] = $dataPost["num_rare"];
			$page = parsetemplate(gettemplate('admin/rare_create'), $parse);	
		}
		elseif($dataPost["num_rare"] == 0)//tat ca cac bo lac deu co bau vat
		{
				$resCreate = executeCreateRareALL();
				if($resCreate){
					$parse["create_success"] = "Bạn đã tạo báu vật cho tất cả Bộ lạc thành công";
					$page = parsetemplate(gettemplate('admin/rare_success'), $parse);
				}
		}
		else
		{
			if(emptyRareTable()){
				$resCreate = executeCreateRare($dataPost);
				if($resCreate){
					$parse["create_success"] = "Bạn đã tạo bộ báu vật cho Bộ lạc thành công";
					$page = parsetemplate(gettemplate('admin/rare_success'), $parse);
				}
			}
		}
	}
	else{
		$parse["num_rare"] = $dataPost["num_rare"];
		$page = parsetemplate(gettemplate('admin/rare_create'), $parse);	
	}
	
	displayAdmin($page);
}
function executeCreateRareALL(){
	global $db;
	$sql="SELECT id FROM wg_villages_map WHERE kind_id>=7 AND kind_id<=14";
	$db->setQuery($sql);
	$objVId=$db->loadObjectList();
	
	$i=count($objVId);
	$j=0;
	$arrRes=array();
	while($j<$i){
		$arrRes[$j]=$objVId[$j]->id;
		$j++;
	}
	
	$Get_rare = "SELECT vila_id FROM `wg_rare`";
	$db->setQuery($Get_rare);
	$objVId_rare = $db->loadObjectList();
	
	$i=count($objVId_rare);
	$j=0;
	$arrRare=array();
	while($j<$i){
		$arrRare[$j]=$objVId_rare[$j]->vila_id;
		$j++;
	}

	$arrRes = array_diff($arrRes, $arrRare);

	for($i = 0;$i <= count($objVId);$i++)
	{
		if($arrRes[$i] != '')
		{
			srand((float) microtime() * 10000000);
			$input = array("kim", "thuy", "hoa", "moc", "tho");
			$rand_keys = array_rand($input, 1);
			$rareName = $input[$rand_keys];
			$sQuery="INSERT INTO wg_rare (vila_id, $rareName) VALUES ($arrRes[$i], 1)";
			$db->setQuery($sQuery);
			$resInsert = $db->query();
			if(!$resInsert){
				globalError("execute wg_rare fail");
				exit(0);
			}
		}
	}
	return true;
}
/**
* @Author: ManhHX
* @Des: Create random data for wg_rare table
* @param: $dataPost POST data
* @return: boolean
*/
function executeCreateRare($dataPost){
	global $db;
	$sql="SELECT id FROM wg_villages_map WHERE kind_id>=7 AND kind_id<=14";
	$db->setQuery($sql);
	$objVId=$db->loadObjectList();
	
	$i=count($objVId);
	$j=0;
	$arrRes=array();
	while($j<$i){
		$arrRes[$j]=$objVId[$j]->id;
		$j++;
	}
	$numRare = $dataPost["num_rare"]*5;	
	
	$rareType=1;
	$rareName='';
	
	while($numRare>0){
		srand((float) microtime() * 10000000);
		$rand_keys = array_rand($arrRes, 1);	
		$arrGet = array();
		$arrGet[$rand_keys] = $arrRes[$rand_keys];		
		$arrRes = array_diff($arrRes, $arrGet);
		
		switch($rareType){
			case 1:
				$rareName = 'kim';
				$rareType++;
				break;
			case 2:
				$rareName = 'moc';
				$rareType++;
				break;
			case 3:
				$rareName = 'thuy';
				$rareType++;
				break;
			case 4:
				$rareName = 'hoa';
				$rareType++;
				break;
			case 5:
				$rareName = 'tho';
				$rareType = 1;
				break;
		}
		
		$sQuery="INSERT INTO wg_rare (vila_id, $rareName) VALUES ($arrGet[$rand_keys], 1)";		
		$db->setQuery($sQuery);
		$resInsert = $db->query();
		if(!$resInsert){
			globalError("execute wg_rare fail");
			exit(0);
		}	
			
		$numRare--;
	}
	return true;
}

/**
* @Author: ManhHX
* @Des: Empty wg_rare table before create
* @param: null
* @return: boolean
*/
function emptyRareTable(){
	global $db;
	$sql="TRUNCATE TABLE `wg_rare`";
	$db->setQuery($sql);
	if(!$db->query()){
		globalError("execute empty wg_rare fail");
		exit(0);
	}
	return true;
}

?>