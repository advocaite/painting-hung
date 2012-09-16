<?php
define('INSIDE', true);
ob_start(); 
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
//checkRequestTime();
if(!check_user()){ header("Location: login.php"); }

    global $user,$db,$lang;
    includeLang('plus');
    includeLang('trade');
    $parse = $lang;
    
    $link=array("lumber"=>2,"iron"=>4,"clay"=>3,"crop"=>5,"attack"=>6,"defence"=>7,"complete"=>8,"build"=>9,"the_bai_1"=>10,"the_bai_2"=>11,"the_bai_3"=>12,"sms_attack"=>13,"dinh_chien"=>14,"map_large"=>15,"all_resource"=>16,"speedup_15"=>17,"speedup_30"=>18,"speedup_2h"=>19);
    $sql = "SELECT wg_config_plus.*, wg_item_user.quantity 
    FROM wg_config_plus left join wg_item_user on wg_config_plus.name = wg_item_user.item_name 
    WHERE wg_item_user.user_id = ".$user['id'];
    $db->setQuery($sql);
    $info = NULL;
    $info=$db->loadObjectList();
    if($info)
    {
        $info_item = array();
        foreach($info as $v)
        {
            if ($v->quantity > 0)
            {
                $info_item[$link[$v->name]][0] = $v->quantity;
                $info_item[$link[$v->name]][1] = $v->asu;
                $info_item[$link[$v->name]][2] = $v->name;
            }
        }
    }
    
    if($_GET['id_item'])
    {
        $id_item = intval($_GET['id_item']);
        echo $info_item[$id_item][0];
    }
ob_end_flush();    
?>
