<?php
ob_start();
  define('INSIDE', true);
  
  $ugamela_root_path = '../';
  include($ugamela_root_path . 'extension.inc');
  include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
  include($ugamela_root_path . 'includes/common.'.$phpEx);
  include($ugamela_root_path . 'includes/func_build.'.$phpEx);
  include("func_update_NPC.php");
  global $db;
  //lay doi tuong la cac thanh NPC
  $sql_select_NpcVila = "SELECT wg_villages.id,name, wg_villages.nation_id, dateCreate_vila FROM wg_villages LEFT JOIN wg_users ON wg_villages.user_id = wg_users.id WHERE wg_users.villages_id = 0";
  $db->setQuery($sql_select_NpcVila);
  $objList_NpcVila = null;
  $objList_NpcVila = $db->loadObjectList();
  
  //so sanh voi ngay hien tai
  foreach($objList_NpcVila as $obj_NpcVila)
  {
      $date_chenhlech = time() - strtotime($obj_NpcVila->dateCreate_vila);
      //neu so ngay chenh lech > 1 ngay thi tang level cho thanh NPC
      if($date_chenhlech >= 86400)
      {
          //lay level cua thanh NPC
          $level = substr(trim($obj_NpcVila->name), -1);
          // cap nhat linh trong thanh
          update_troop_NPC($obj_NpcVila->id, $obj_NpcVila->nation_id, $level);
          
          //cap nhat cong trinh
          update_Building_NPC($obj_NpcVila->id, $level);
          //cap nhat dan so cho thanh va user NPC
          include_once("includes/function_badlist.php");
          returnWorkersLogin(getUserIdByVillageId($obj_NpcVila->id));
          
          //thay doi ten thanh tuc la thay doi level cua thanh & cap nhat lai ngay tao thanh
          $levelup = $level + 1;
          if($levelup <= 10)
          {
              $sql_update = "UPDATE wg_villages SET name = 'NPC Level $levelup', dateCreate_vila = '".date('Y-m-d H:i:s')."' WHERE id = $obj_NpcVila->id";
              $db->setQuery($sql_update);
              $db->query();
              echo 'Co update';
          }
      }
  }
  echo 'Thuc hien xong';
ob_end_flush();
?>
