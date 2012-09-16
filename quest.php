<?php
ob_start();

define('INSIDE', true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.' . $phpEx);
require_once($ugamela_root_path . 'includes/common.' . $phpEx);
require_once($ugamela_root_path . 'includes/func_security.' . $phpEx);
require_once($ugamela_root_path . 'includes/function_allian.' . $phpEx);
require_once($ugamela_root_path . 'includes/wg_mission.' . $phpEx);
checkRequestTime();

if (!check_user())
{
    header("Location: login.php");
}

includeLang('quest');
global $lang, $user, $wg_mission, $db;
$parse     = $lang;

$sql_quest = "Select * From wg_quest";
$db->setQuery($sql_quest);
$objListQuest = $db->loadObjectList();

if ($objListQuest)
{
    $list_quest = '';

    foreach ($objListQuest as $objQuest)
    {
        $parse['href'] = 'href="quest.php?quest_id=' . $objQuest->id . '"';
        $parse['name'] = $objQuest->quest_name;
        $list_quest .= parsetemplate(gettemplate('quest_row'), $parse);
    }
}

$parse['row_quest'] = $list_quest;

if (!empty($_GET['quest_id']) && is_numeric($_GET['quest_id']))
{
    $sql = "Select * From wg_mission where quest_id = " . intval($_GET['quest_id']);
    $db->setQuery($sql);
    $objListMission = $db->loadObjectList();

    if ($objListMission)
    {
        $row_quest = '';

        foreach ($objListMission as $objMission)
        {
            if ($objMission->id == 1 || $objMission->id == 42)
            {
                continue;
            }

            $parse['href']
                           = 'href="quest.php?quest_id=' . intval($_GET['quest_id']) . '&id=' . ($objMission->id - 1)
                . '"';
            $parse['name'] = $objMission->name;
            $row_quest .= parsetemplate(gettemplate('quest_row'), $parse);
        }
    }
}

$parse['list_quest'] = $row_quest;

if (empty($_GET['id']))
{
    if (empty($_GET['quest_id']))
    {
        $parse['content'] = $lang['mission1'];
    }
    else
    {
        $parse['content'] = $lang['mission' . $objListMission[0]->id];
    }
}
else
{
    $id = intval($_GET['id']) + 1;

    //insert button
    //if don't quest yet then show button "Accept" 
    //elseif don't reward yet then show button "reward" 
    //else don't show button
    if (check_quest('accept', $id - 2) && !check_quest('reward', $id - 2))
    {
        $char = '';

        if ($wg_mission[$id - 1]['rs1'] > 0)
        {
            $char .= '<img src="images/un/r/1.gif"> ' . $wg_mission[$id - 1]['rs1'] . '&nbsp;&nbsp;';
        }

        if ($wg_mission[$id - 1]['rs2'] > 0)
        {
            $char .= '<img src="images/un/r/2.gif"> ' . $wg_mission[$id - 1]['rs2'] . '&nbsp;&nbsp;';
        }

        if ($wg_mission[$id - 1]['rs3'] > 0)
        {
            $char .= '<img src="images/un/r/3.gif"> ' . $wg_mission[$id - 1]['rs3'] . '&nbsp;&nbsp;';
        }

        if ($wg_mission[$id - 1]['rs4'] > 0)
        {
            $char .= '<img src="images/un/r/4.gif"> ' . $wg_mission[$id - 1]['rs4'] . ' &nbsp;&nbsp;';
        }

        if ($wg_mission[$id - 1]['gold'] > 0)
        {
            $char .= '<img src="images/un/r/8.gif"> ' . $wg_mission[$id - 1]['gold'] . ' Vàng';
        }

        $parse['mission']  = $lang['reward' . $id . ''] . '' . $char;
        $parse['quest_id'] = $_GET['quest_id'];
        $parse['id']       = $_GET['id'];

        //kiem tra nhiem vu da hoan thanh chua
        //chua hien nut chua hoan thanh
        if (finish_quest($id - 1, $user['villages_id']))
        {
            //hoan thanh thi hien nut nhan thuong
            $parse['input'] = '<br /><input name="reward" type="submit" value="Reward" tabindex="3">';
        }
        else
        {
            $parse['input'] = '<br /><span>Not Finish</span>';
        }

        $parse['content'] = parsetemplate(gettemplate('quest2b'), $parse);
    }
    elseif (!check_quest('accept', $id - 2) && !check_quest('reward', $id - 2) && $id != 7)
    {
        $parse['mission']  = $lang['mission' . $id];
        $parse['quest_id'] = $_GET['quest_id'];
        $parse['id']       = $_GET['id'];
        $parse['inpName']  = "accept";
        $parse['inpValue'] = "Accept";
        $parse['input']    = '<input name="accept" type="image" src="images/en/b/ok1.gif" value="Accept" tabindex="3">';
        $parse['content']  = parsetemplate(gettemplate('quest2b'), $parse);
    }
    else
    {
        $parse['mission']  = $lang['mission' . $id];
        $parse['quest_id'] = $_GET['quest_id'];
        $parse['id']       = $_GET['id'];
        $parse['input']    = '';
        $parse['content']  = parsetemplate(gettemplate('quest2b'), $parse);
    }
}

if (count($_POST) > 0)
{
    if ($_POST['accept'] == 'Accept')
    {
        if (!check_quest('accept', $id - 2))
        {
            if ($id == 8)
            {
                InsertReportQuest7($user['id']); // goi bao cao cho nguoi choi
            }

            if ($id == 9)
            {
                InsertMessageQuest8($user['id']); // goi tin nhan cho nguoi choi
            }

            Setbit('accept', $id - 2);
            $parse['content'] = parsetemplate(gettemplate('quest2a'), $parse);
        }
    }

    if ($_POST['reward'] == 'Reward')
    {
        if (!check_quest('reward', $id - 2))
        {
            Setbit('reward', $id - 2);
            //cong phan thuong
            Update_Glod_RS_Mission($user['villages_id'], $user['id'], $id);
            $parse['content'] = parsetemplate(gettemplate('quest2a'), $parse);
        }
    }

    if (isset($_POST['rank']))
    {
        if ($_POST['rank'] == $_SESSION['rank_quest_7'] && !check_quest('accept', $id - 2))
        {
            Setbit('accept', $id - 2);
        }
    }
}

$page = parsetemplate(gettemplate('quest2'), $parse);
display2($page, '');

ob_end_flush();

function check_quest($field, $mission_id)
{
    global $db, $user;
    $sql = "Select $field From wg_users Where id = " . $user['id'];
    $db->setQuery($sql);
    $objField = NULL;
    $db->loadObject($objField);

    if ($objField)
    {
        $check = str_split($objField->$field);

        if ($check[$mission_id] == 1)
        {
            return true;
        }
    }

    return false;
}

function Setbit($field, $mission_id)
{
    global $db, $user;
    $sql = "Select $field From wg_users Where id = " . $user['id'];
    $db->setQuery($sql);
    $objField = NULL;
    $db->loadObject($objField);

    if ($objField)
    {
        $check              = str_split($objField->$field);
        $check[$mission_id] = 1;
        $sql                = "UPDATE wg_users SET $field = '" . implode("", $check) . "'    WHERE id =" . $user['id'];
        $db->setQuery($sql);
        $db->query();
    }
}

function finish_quest($mission_id, $village_id)
{
    global $user, $db;
    $userid = $user['id'];

    switch ($mission_id)
    {
        case 1: // Mission 1 :Lâm tr??ng
            //neu da xem qua nhiem vu thi moi update len
            if (check_level_building(1, $village_id) > 0)
            {
                return true;
            }

            break;

        case 2: // Mission 2 :M? ?á
            if (check_level_building(4, $village_id) > 0)
            {
                return true;
            }

            break;

        case 3: // Mission 3 :M? s?t
            if (check_level_building(3, $village_id) > 0)
            {
                return true;
            }

            break;

        case 4: // Mission 4 :??ng lúa
            if (check_level_building(2, $village_id) > 0)
            {
                return true;
            }

            break;

        case 5: //doi ten lang
            if (CheckNameVillage() == 0)
            {
                return true;
            }

            break;

        case 6: // xem xep hang nguoi choi
            return true;

            break;

        case 7:
            if (checkReadQuest7($userid) > 0)
            {
                return true;
            }

            break;

        case 8:
            if (checkReadQuest8($userid) > 0)
            {
                return true;
            }

            break;

        case 9:
            if (check_level_All_resoucre($village_id, 1) >= 18)
            {
                return true;
            }

            break;

        case 10:
            if (check_level_building(15, $village_id) > 0)
            {
                return true;
            }

            break;

        case 11:
            if (check_level_building(12, $village_id) >= 3)
            {
                return true;
            }

            break;

        case 12:
            if (check_level_building(10, $village_id) > 0)
            {
                return true;
            }

            break;

        case 13:
            if (check_level_building(11, $village_id) > 0)
            {
                return true;
            }

            break;

        case 14:
            if (check_level_building(13, $village_id) > 0)
            {
                return true;
            }

            break;

        case 15:
            if (check_level_building(28, $village_id) > 0)
            {
                return true;
            }

            break;

        case 16:
            if (check_level_building(14, $village_id) > 0)
            {
                return true;
            }

            break;

        case 17:
            if (check_level_All_resoucre($village_id, 2) >= 18)
            {
                return true;
            }

            break;

        case 18:
            if (Check_SumALLTroop_Village($village_id) >= 2)
            {
                return true;
            }

            break;

        case 19: //gia nhap lien minh
            //kiem tra user da co lien minh chua
            $checkAli_id = "SELECT `ally_id` FROM `wg_ally_members` WHERE `user_id`=" . $user['id'] . " and right_ = 1";

            $db->setQuery($checkAli_id);
            $check = null;
            $db->loadObject($check);

            if ($check)
            {
                return true;
            }

            break;

        case 20: //Lo ren
            if (check_level_building(24, $village_id) > 0)
            {
                return true;
            }

            break;

        case 21: //trai ngua
            if (check_level_building(29, $village_id) > 0)
            {
                return true;
            }

            break;

        case 22: //hoc vien
            if (check_level_building(31, $village_id) > 0)
            {
                return true;
            }

            break;

        case 23: //gop diem danh vong
            $get_info = "SELECT * FROM `wg_ally_news` WHERE `content` LIKE '%" . $user['username']
                . "%' AND `content` LIKE '%điểm danh vọng%' AND `content` LIKE '%100%'";

            $db->setQuery($sql);
            $check = null;
            $db->loadObject($check);

            if ($check)
            {
                return true;
            }

            break;

        case 24: //linh do tham
            include_once('includes/function_troop.php');

            if ($user['nation_id'] == 1)
            {
                $troop_id = 19;
            }
            elseif ($user['nation_id'] == 2)
            {
                $troop_id = 30;
            }
            else
            {
                $troop_id = 7;
            }

            if (GetSumPresentTroop($troop_id) >= 1)
            {
                return true;
            }

            break;

        case 25: //trinh sat
            if (check_attack(7, $village_id) || check_attack(8, $village_id))
            {
                return true;
            }

            break;

        case 26: //coi xay
            if (check_level_building(5, $village_id) > 0)
            {
                return true;
            }

            break;

        case 27: //xuong tac da
            if (check_level_building(7, $village_id) > 0)
            {
                return true;
            }

            break;

        case 28: //xuong cua
            if (check_level_building(6, $village_id) > 0)
            {
                return true;
            }

            break;

        case 29: //lo luyen thep
            if (check_level_building(8, $village_id) > 0)
            {
                return true;
            }

            break;

        case 30: //lo banh bao
            if (check_level_building(9, $village_id) > 0)
            {
                return true;
            }

            break;

        case 31: //danh dot kich
            if (check_attack(3, $village_id))
            {
                return true;
            }

            break;

        case 32: // danh tu chien
            if (check_attack(4, $village_id))
            {
                return true;
            }

            break;

        case 33: //chieu mo tuong
            include_once('includes/function_troop.php');

            if (CheckHeroVillage($village_id) >= 1)
            {
                return true;
            }

            break;

        case 34: //huyen luuyen 500 linh
            if (Check_SumALLTroop_Village($village_id) >= 500)
            {
                return true;
            }

            break;

        case 35: //danh chiem bo lac
            if (checkTribe_user($userid))
            {
                return true;
            }

            break;

        case 36: //tuong phu
            if (check_level_building(35, $village_id) > 0)
            {
                return true;
            }

            break;

        case 37: //cung dien
            if (check_level_building(18, $village_id) > 0)
            {
                return true;
            }

            break;

        case 38: //van chuyen
            if (check_transport(23, $village_id))
            {
                return true;
            }

            break;

        case 39: //mua ban tai nguyen
            if (check_transport(6, $village_id) && check_transport_asu($village_id))
            {
                return true;
            }

            break;

        case 40: //trao doi tai nguyen
            if (check_transport(6, $village_id))
            {
                return true;
            }

            break;

        case 41: //hoan doi
        break;

        case 42: //cong xuong
            if (check_level_building(30, $village_id) > 0)
            {
                return true;
            }

            break;

        case 43: //xe phao
            include_once('includes/function_troop.php');

            if ($user['nation_id'] == 1)
            {
                $troop_id = 20;
            }
            elseif ($user['nation_id'] == 2)
            {
                $troop_id = 31;
            }
            else
            {
                $troop_id = 9;
            }

            if (GetSumPresentTroop($troop_id) >= 2)
            {
                return true;
            }

            break;
    }

    return false;
}

function check_level_building($type_building, $village_id)
{
    global $db;
    $sql = "Select level from wg_buildings where type_id = " . $type_building . " and vila_id = " . $village_id;
    $db->setQuery($sql);
    $level_building = NULL;
    $level_building = $db->loadObjectList();

    if ($level_building)
    {
        $check_level = 0;

        foreach ($level_building as $level)
        {
            if ($level->level < 1)
            {
                continue;
            }

            $check_level = $level->level;
        }

        return $check_level;
    }
}

function check_level_All_resoucre($id_village, $level_in)
{
    global $db;
    $sql = "Select level from wg_buildings where type_id in (1,2,3,4) and vila_id = " . $id_village;
    $db->setQuery($sql);
    $level_building = NULL;
    $level_building = $db->loadObjectList();

    if ($level_building)
    {
        $count = 0;

        foreach ($level_building as $level)
        {
            if ($level->level >= $level_in)
            {
                $count += 1;
            }
        }

        return $count;
    }
}

function Check_SumALLTroop_Village($village_id)
{
    global $db;
    include_once('includes/function_troop.php');
    $sql = "SELECT  DISTINCT(troop_id) FROM wg_troop_villa  WHERE village_id=$village_id";
    $db->setQuery($sql);
    $query       = NULL;
    $query       = $db->loadObjectlist();
    $sumAllTroop = 0;

    if ($query)
    {
        foreach ($query as $objtroop)
        {
            $sumAllTroop += GetSumPresentTroop($objtroop->troop_id); //goi ham tu file function_troop
        }
    }

    return $sumAllTroop;
}

function check_attack($type, $village_id)
{
    global $user, $db;
    $sql_attack
        = "SELECT count(*) FROM `wg_attack` WHERE `type` =$type AND `village_attack_id` =$village_id AND `status` =1";
    $db->setQuery($sql_attack);
    //echo $sql_attack;
    $check = $db->loadResult();

    if ($check >= 1)
    {
        return true;
    }

    return false;
}

function checkTribe_user($userid)
{
    global $db;
    $get_trbe = "SELECT count(*) FROM `wg_villages` WHERE `kind_id` >=7 AND `user_id` = $userid";
    $db->setQuery($get_trbe);
    $check = $db->loadResult();

    if ($check >= 1)
    {
        return true;
    }

    return false;
}

function check_transport($type, $village_id)
{
    global $db;
    $get_transport = " SELECT count( * ) FROM `wg_status` WHERE `type` = $type AND `village_id` = $village_id ";
    $db->setQuery($get_transport);
    $check = $db->loadResult();

    if ($check >= 1)
    {
        return true;
    }

    return false;
}

function check_transport_asu($village_id)
{
    global $db;
    $get_transport_asu =
        " SELECT count(*) FROM `wg_resource_sends` WHERE (`village_id_from` = $village_id or `village_id_to` = $village_id) and `asu` > 0 ";
    $db->setQuery($get_transport_asu);
    $check = $db->loadResult();

    if ($check >= 1)
    {
        return true;
    }

    return false;
}
?>