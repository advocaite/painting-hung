<?php
/**
 * @author DiepLuan
 * @copyright 2010
 * @des file nay chua cac ham cap nhat cho thanh NPC (quan linh, cong trinh)
 */
function update_troop_NPC($villa_id, $nation, $level)
{
    global $db;
    //lay tat ca linh thuoc chung toc $nation (tru thuyet khach, tho)
    $sql = "SELECT id FROM `wg_troops` WHERE `nation_id` = $nation ORDER BY `id` ASC LIMIT 0,9";
    $db->setQuery($sql);
    $obj_troopId = null;
    $obj_troopId = $db->loadObjectList();
    
    //dua troop_id vao mot mang de lay random
    $i=count($obj_troopId);
    $j=0;
    $arr_troopId = array();
    while($j<$i){
        $arr_troopId[$j]=$obj_troopId[$j]->id;
        $j++;
    }
    
    //cap nhat random linh vao bang linh cua thanh $villa_id theo level
        //lay random troop ID
    srand((float) microtime() * 10000000);
    $input = $arr_troopId;
    $rand_keys = array_rand($input, 1);
    $troop_ID = $input[$rand_keys];
    
        //cap nhat linh vao bang linh, so linh phu thuoc vao level cua thanh NPC
            //kiem tra da co loai linh $troop_ID trong thanh $villa_id chua
    $sql_check = "SELECT id FROM `wg_troop_villa` WHERE `troop_id` = $troop_ID AND `village_id` = $villa_id";
    $db->setQuery($sql_check);
    $check_troopID = $db->loadResult();
            //neu ton tai thi update
    if($check_troopID)
    {
        $sql_update = "UPDATE `wg_troop_villa` SET `num` = `num` + $level*50 WHERE `wg_troop_villa`.`id` = $check_troopID";
        $db->setQuery($sql_update);
        $db->query();        
    }
            //khong ton tai thi insert
    else
    {
        $num = ($level == 1)?200:($level*50);
        $sql_insert = "INSERT INTO `wg_troop_villa` (troop_id, village_id, num) VALUE ($troop_ID, $villa_id, $num)";
        $db->setQuery($sql_insert);
        $db->query();
    }
}

function update_Building_NPC($villa_id, $level)
{
    global $db;
    $sql_select_building = "SELECT id FROM `wg_building_types` WHERE id != 37 AND id != 20 ORDER BY `id` ASC";
    $db->setQuery($sql_select_building);
    $objlist_building = null;
    $objlist_building = $db->loadObjectList();
    
    $i = count($objlist_building);
    $j = 0;
    $arr_building = array();
    while($j < $i)
    {
        $arr_building[$j] = $objlist_building[$j]->id;
        $j++;
    }
    
    srand((float) microtime() * 10000000);
    $input = $arr_building;
    $rand_keys = array_rand($input, 1);
    $buildings_ID = $input[$rand_keys];
    
    $sql_check = "SELECT id,level FROM `wg_buildings` WHERE `type_id` = $buildings_ID AND `vila_id` = $villa_id AND `level` < 10";
    $db->setQuery($sql_check);
    $db->loadObject($check_buildingID);
    
    if($check_buildingID)
    {
        $level = $check_buildingID->level + 1;
        $images=Get_Images2($buildings_ID,$level);
        $cp=getCpAll($buildings_ID,$level);
        $product_hour_new=getProductNew($buildings_ID,$level);
    
        $sql_update = "UPDATE wg_buildings SET img='$images',level= $level, product_hour=$product_hour_new,cp=$cp WHERE id= ".$check_buildingID->id;   
    }
    else
    {
        $images=Get_Images2($buildings_ID,$level);
        $cp=getCpAll($buildings_ID,$level);
        $product_hour_new=getProductNew($buildings_ID,$level);
        $sql_select_building_name = "SELECT `name` FROM `wg_building_types` WHERE `id` = $buildings_ID";
        $db->setQuery($sql_select_building_name);
        $name_building = $db->loadResult();
        
        if($buildings_ID == 12)
        {
            $index = 37;
        }
        elseif($buildings_ID == 27)
        {
            $index = 35;
        }
        else
        {
            $sql_select_index = " SELECT `index` FROM `wg_buildings` WHERE `type_id` = 0 AND `vila_id` = $villa_id LIMIT 0 , 1";
            $db->setQuery($sql_select_index);
            $index = $db->loadResult();
        }
    
        $sql_update = "UPDATE wg_buildings SET name = '$name_building', img='$images',level= $level,product_hour=$product_hour_new,type_id = $buildings_ID,cp=$cp WHERE `index` = $index AND `vila_id` = $villa_id";
    }
    $db->setQuery($sql_update);
    $db->query();
}
?>
