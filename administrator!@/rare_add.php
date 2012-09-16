<?php 
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.'.$phpEx);
include('includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/func_security.php');


$resCreate = executeCreateRare();

$parse["create_success"] = "Bạn đã tạo báu vật cho Bộ lạc thành công";
$page = parsetemplate(gettemplate('admin/rare_success'), $parse);
displayAdmin($page);


/**
* @Author: ManhHX
* @Des: Create random data for wg_rare table
* @param: $dataPost POST data
* @return: boolean
*/
function executeCreateRare(){
	global $db;
	$sql=" SELECT t1.id FROM wg_villages_map AS t1 ";
	$sql.=" WHERE t1.kind_id >=7 AND t1.kind_id <=14";
	$sql.=" AND t1.id NOT IN (";
	$sql.=" SELECT vila_id FROM wg_rare)";
		
	$db->setQuery($sql);
	$objVId=$db->loadObjectList();
		
	$rareType=1;
	$rareName='';
	$numRare = 0;

	while($numRare < count($objVId)){
		
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
		
		$sQuery="INSERT INTO wg_rare (vila_id, $rareName) VALUES (".$objVId[$numRare]->id.", 1)";	
		
		$db->setQuery($sQuery);
		$resInsert = $db->query();
		if(!$resInsert){
			globalError("execute wg_rare fail");
			exit(0);
		}	
			
		$numRare++;
	}
	return true;
}

?>