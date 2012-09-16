<?php
ob_start();

define('INSIDE', true);
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ($ugamela_root_path . 'includes/common.' . $phpEx);
include ($ugamela_root_path . 'includes/db_connect.' . $phpEx);

////////////procedure
if (!check_user())
{
    header ("Location: login.php");
}

if ($user['authlevel'] != 5)
{
    header ("Location: login.php");
}

EmptyTable();
echo 'OK';
//$page = parsetemplate(gettemplate('admin/createmap_body'), $parse);
//displayAdmin($page,$lang['Registration List']);

function EmptyTable()
{
    global $db;
    $sql = "TRUNCATE TABLE `wg_allies`";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_allies");
    }

    $sql = "TRUNCATE TABLE wg_ally_members";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_ally_members");
    }

    $sql = "TRUNCATE TABLE wg_ally_news";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_ally_news");
    }

    $sql = "TRUNCATE TABLE wg_ally_relation";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_ally_relation");
    }

    $sql = "TRUNCATE TABLE wg_armys";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_armys");
    }

    $sql = "TRUNCATE TABLE wg_att2mobile_log";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_att2mobile_log");
    }

    $sql = "TRUNCATE TABLE wg_att2mobile_queue";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_att2mobile_queue");
    }

    $sql = "TRUNCATE TABLE wg_attack";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_attack");
    }

    $sql = "TRUNCATE TABLE wg_attack_backup";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_attack_backup");
    }

    $sql = "TRUNCATE TABLE `wg_attack_hero`";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_attack_hero");
    }

    $sql = "TRUNCATE TABLE `wg_attack_troop`";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_attack_troop");
    }

    $sql = "TRUNCATE TABLE `wg_bad_list`";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_bad_list");
    }

    $sql = "TRUNCATE TABLE wg_buildings";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_buildings");
    }

    $sql = "TRUNCATE TABLE wg_enforce";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_enforce");
    }

    $sql = "TRUNCATE TABLE wg_error";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_error");
    }

    $sql = "TRUNCATE TABLE wg_gold_logs";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_gold_logs");
    }

    $sql = "TRUNCATE TABLE wg_heros";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_heros");
    }

    $sql = "TRUNCATE TABLE wg_history_top10";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_history_top10");
    }

    $sql = "TRUNCATE TABLE wg_item_user";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_item_user");
    }

    $sql = "TRUNCATE TABLE wg_log_xgo";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_log_xgo");
    }

    $sql = "TRUNCATE TABLE wg_merchant_villa";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_merchant_villa");
    }

    $sql = "TRUNCATE TABLE wg_messages";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_messages");
    }

    $sql = "TRUNCATE TABLE wg_messages_backup";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_messages_backup");
    }

    $sql = "TRUNCATE TABLE wg_news";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_news");
    }

    $sql = "TRUNCATE TABLE wg_oasis_troop_att";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_oasis_troop_att");
    }

    $sql = "TRUNCATE TABLE wg_plus";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_plus");
    }

    $sql = "TRUNCATE TABLE wg_plus_xgo";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_plus_xgo");
    }

    $sql = "TRUNCATE TABLE wg_profiles";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_profiles");
    }

    $sql = "TRUNCATE TABLE wg_rare";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_rare");
    }

    $sql = "TRUNCATE TABLE wg_rare_sends";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_rare_sends");
    }

    $sql = "TRUNCATE TABLE wg_registration_village_list";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_registration_village_list");
    }

    $sql = "TRUNCATE TABLE wg_reports";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_reports");
    }

    $sql = "TRUNCATE TABLE wg_reports_bk";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_reports_bk");
    }

    $sql = "TRUNCATE TABLE wg_resource_orders";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_resource_orders");
    }

    $sql = "TRUNCATE TABLE wg_resource_sends";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_resource_sends");
    }

    $sql = "TRUNCATE TABLE wg_securities";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_securities");
    }

    $sql = "TRUNCATE TABLE wg_sessions";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_sessions");
    }

    $sql = "TRUNCATE TABLE wg_sister";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_sister");
    }

    $sql = "TRUNCATE TABLE wg_status";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_status");
    }

    $sql = "TRUNCATE TABLE wg_status_backup";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_status_backup");
    }

    $sql = "TRUNCATE TABLE wg_top10";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_top10");
    }

    $sql = "TRUNCATE TABLE wg_troop_armour";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_troop_armour");
    }

    $sql = "TRUNCATE TABLE `wg_troop_items`";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_troop_items");
    }

    $sql = "TRUNCATE TABLE `wg_troop_researched`";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_troop_researched");
    }

    $sql = "TRUNCATE TABLE `wg_troop_train`";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_troop_train");
    }

    $sql = "TRUNCATE TABLE wg_troop_villa";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_troop_villa");
    }

    $sql = "TRUNCATE TABLE wg_user_bans";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_user_bans");
    }

    $sql = "TRUNCATE TABLE wg_villages";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_villages");
    }

    $sql = "TRUNCATE TABLE wg_villages_map";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_villages_map");
    }

    $sql = "TRUNCATE TABLE wg_users";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_users");
    }

    $sql = "TRUNCATE TABLE wg_wtagshoutbox";
    $db->setQuery($sql); //die($sql);

    if (!$db->query())
    {
        die ("error1!!!EmptyTable wg_wtagshoutbox");
    }
}
?>