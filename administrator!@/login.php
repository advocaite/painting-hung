<?php
session_start();

ob_start();
define('INSIDE', true);
$ugamela_root_path = '../';
include($ugamela_root_path . 'extension.inc');
include('includes/db_connect.' . $phpEx);
include('includes/func_security.' . $phpEx);
include('includes/common.' . $phpEx);

if (check_user())
{
    header("Location: index.php");
}

includeLang('admin_login');
global $db, $lang, $game_config;
$parse            = $lang;
$parse['slogan']  = $game_config['game_name'];
$parse['message'] = '';

if ($_GET['error'] == 1)
{
    $parse['message'] = $lang['error4'];
}

if (count($_POST) <> 0 && $_POST['check'] == $_SESSION['security_adminlogin'])
{
    $validate_code = $_POST['validate_code'];

    if (!isset($_SESSION['kode_validate_code']) || $validate_code != $_SESSION['kode_validate_code'])
    {
        returnMsg('Error:  Sai mã xác thực, vui lòng thử lại.');
    }

    if (empty($_POST['username']) || empty($_POST['password']))
    {
        returnMsg("Error:  Tài khoản và mật khẩu trường yêu cầu nhập.");
    }
    else
    {
        if (!get_magic_quotes_gpc())
        {
            $username = str_replace('$', '\$', addslashes($_POST["username"]));
            $password = str_replace('$', '\$', addslashes($_POST["password"]));
        }
        else
        {
            $username = str_replace('$', '\$', $_POST["username"]);
            $password = str_replace('$', '\$', $_POST["password"]);
        }

        $username = $db->getEscaped($username);
        $password = $db->getEscaped($password);
        $sql      = "SELECT wg_users.id,wg_admin.authlevel FROM wg_admin LEFT JOIN wg_users ON wg_users.username='" . $username . "'
        WHERE wg_admin.username ='" . $username . "' AND wg_admin.pass='" . md5($password) . "'";
        $db->setQuery($sql);
        $wg_admin = NULL;
        $db->loadObject($wg_admin);

        if ($wg_admin) // dang nhap thanh cong
        {
            @include('config.php');

            if ($wg_admin->authlevel == 0)
            {
                header("Location: login.php?error=1");
                exit();
            }

            $rememberme = $expiretime = 0;
            $password   = md5($password . "--" . $dbsettings["secretword"]);
            $cookie     = $wg_admin->id . " " . $username . " " . $password . " " . $rememberme;
            setcookie('ADMIN_COOKIE_NAME', $cookie, $expiretime, "/", "", 0);
            $_SESSION['password']       = $password;
            $_SESSION['viewuser']       = 1;
            $_SESSION['username_admin'] = $username;

            if (!insertFeature($username, "Login Admin"))
            {
                die('Error!!!');
            }

            unset($dbsettings);
            $st_forward = $_REQUEST['redirect'];

            if ($st_forward != '')
            {
                returnMsg('{"msg":"Đăng nhập thành công. Đang chuyển đến trang.","referer":"' . $st_forward . '"}');
                header("Location: $st_forward");
            }
            else
            {
                returnMsg('{"msg":"Đăng nhập thành công. Đang chuyển đến trang.","referer":"' . 'index.php' . '"}');
                header("Location: index.php");
            }
        }
        else
        {
            returnMsg("Error:  Tài khoản và mật khẩu không tìm thấy.");
        }
    }
}

$parse['code'] = $_SESSION['security_adminlogin'] = md5(time());
$page          = parsetemplate(gettemplate('/admin/nlogin'), $parse);
displayAdminLogin($page, $lang['Login']);
ob_end_flush();

function returnMsg($msg)
{
    exit($msg);
}

function curPageURL()
{
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on")
    {
        $pageURL .= "s";
    }

    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"]; // . $_SERVER["REQUEST_URI"];
    }
    else
    {
        $pageURL .= $_SERVER["SERVER_NAME"]; // . $_SERVER["REQUEST_URI"];
    }

    return $pageURL;
}
?>