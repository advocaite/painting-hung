<?php
session_start();

ob_start();
define('INSIDE', true);
$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include($ugamela_root_path . 'includes/common.' . $phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_active.php');
include($ugamela_root_path . 'includes/func_convert.php');
include($ugamela_root_path . 'soap/call.php');
Time_Wait(time());

if (check_user())
{
    header("Location:village1.php");
}

includeLang('active_user');
$parse = $lang;
global $db, $game_config;

function countPosition($id)
{
    global $db;
    $sql = "SELECT COUNT(DISTINCT(id)) AS sum, (
        SELECT COUNT(DISTINCT(id)) FROM `wg_registration_village_list`
        WHERE `zone_id` =" . $id . "
        AND `registed` =1
        ) AS reg
        FROM `wg_registration_village_list` WHERE `zone_id` =" . $id;
    $db->setQuery($sql);
    $query = NULL;
    $db->loadObject($query);

    if ($query->sum > 0)
    {
        return round(($query->reg / $query->sum) * 100, 2);
    }
    else
    {
        header("Location:login.php");
        exit();
    }
}

$error         = '';
$parse['vid1'] = $parse['kid0'] = 'checked="checked"';

if ($_POST['active'])
{
    $check = 0;

    if ($_POST['active'] != $_SESSION['acitve_time'])
    {
        header("Location:http://id.xgo.vn/register.html");
        exit();
    }

    if (is_numeric($_POST['kid']) && $_POST['kid'] >= 0 && $_POST['kid'] <= 5)
    {
        $position = $_POST['kid'];
        $check++;
    }

    if (is_numeric($_POST['vid']) && $_POST['vid'] >= 1 && $_POST['vid'] <= 3)
    {
        $nation = $_POST['vid'];
        $check++;
    }
}

if ($check == 2)
{
    $username = $db->getEscaped(str_replace('$', '\$', addslashes($_POST["username"])));
    $code     = $db->getEscaped(str_replace('$', '\$', addslashes($_POST["codes"])));
    $sql      = "SELECT id FROM wg_users WHERE username='" . $username . "' LIMIT 1";
    $db->setQuery($sql);
    $actived = NULL;
    $db->loadObject($actived);

    if (!$actived)
    {
        if (active_remote($username, $code))
        {
            insertFeature($username, "Active_user");

            if ($position == 0)
            {
                $position = rand(1, 5);
            }

            $customerid = get_customerid($username);
            
            capPhat($username, $nation, $position, $customerid);
            unset($_SESSION['username_email']);
            $parse['title']       = $game_config['game_name'];
            $parse['copyright']   = $game_config['copyright'];
            $parse['time_server'] = date("H:i:s", time());
            $parse['show_date']   = showDate();
            $page                 = parsetemplate(gettemplate('active_user_success'), $parse);
            display2($page, $lang['active_title']);
        }
        else
        {
            $error = $lang['error'];
        }
    }
    else
    {
        $error = $lang['error1'];
    }

    $parse['vid' . $nation]   = 'checked="checked"';
    $parse['kid' . $position] = 'checked="checked"';
}

$parse['error']          = $error;
$parse['active']         = $_SESSION['acitve_time'] = md5(time());
$parse['value_code']     = $db->getEscaped($_GET['code']);
$parse['value_username'] = $db->getEscaped($_GET['username']);

for ($i = 1; $i <= 5; $i++)
{
    $parse['postiton' . $i . ''] = countPosition($i);
}

$page = parsetemplate(gettemplate('active_user'), $parse);
display1($page, $lang['active_title']);
?>