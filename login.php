<?php
ob_start();

define('INSIDE', true);
global $db, $game_config;
$ugamela_root_path = './';
include($ugamela_root_path . 'bootstrap.php');
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.' . $phpEx);
include($ugamela_root_path . 'includes/common.' . $phpEx);
include($ugamela_root_path . 'includes/func_security.php');
include($ugamela_root_path . 'includes/function_plus.php');
include($ugamela_root_path . 'includes/function_profile.php');
include($ugamela_root_path . 'includes/function_xml.php');
include_once($ugamela_root_path . 'includes/usersOnline.class.php');
include_once($ugamela_root_path . 'includes/func_build.php');
include_once($ugamela_root_path . 'soap/call.' . $phpEx);
Time_Wait(time());
checkRequestTime();

if (check_user())
{
    header("Location: village1.php");
}
$parse['title'] = "Tranh Hùng - Game việt - trí tuệ việt";
if (isset($_GET['language']))
{
    $language             = $db->getEscaped($_GET['language']);
    $_SESSION['language'] = $language;
    $language             = '?language=' . $language;
}

includeLang('login');
$parse          = $lang;
$parse['lang_'] = $language;

if (count($_POST) <> 0 && $_POST['check'] == $_SESSION['security_login'])
{
    if (empty($_POST["username"]) || empty($_POST["password"]))
    {
        $parse['error1']   = $lang['error1']; //loi username
        $parse['error2']   = $lang['error2']; //loi mat khau
        $parse['username'] = '';
        $parse['password'] = '';

        if (!empty($_POST["username"]))
        {
            $parse['username'] = $_POST["username"];
            $parse['error1']   = '';
        }

        if (!empty($_POST["password"]))
        {
            $parse['password'] = $_POST["password"];
            $parse['error2']   = '';
        }

        $parse['active']          = '';
        $parse['error3']          = '';
        //load news o trang login
        $parse['list_news_login'] = load_list_news();
        //End
        $page                     = parsetemplate(gettemplate('login_body'), $parse);
        display_newlogin($page, $lang['Login']);
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
        $password_md5 = md5($password);
        //echo $password;die();
        $login    = false;

        if (login_remote($username, $password_md5)) // dang nhap thanh cong
        {
            $sql =
                "SELECT id,username,villages_id,alliance_id,actived,nation_id FROM wg_users WHERE username='$username'";
            $db->setQuery($sql);
            $wg_users = NULL;
            $db->loadObject($wg_users);

            if ($wg_users && $wg_users->actived == 1)
            {
                $login = true;
            }
            else // chua kich hoat
            {
                //thong bao tai khoan chua kich hoat; tra ve trang kich hoat tren billing
                $parse['error3'] = 'Tài khoản chưa kích hoạt!';
                $code            = md5(get_customerid($username));
                header("Location:active_user.php?username=$username&code=$code");
                exit();
            }
        }
        else // tai khoan xai chung
        {
            $accountSister = getAccountSisterInfo($username);

            if ($accountSister)
            {
                if (login_remote($accountSister->sister1, $password))
                {
                    $sql =
                        "SELECT id,username,villages_id,alliance_id,nation_id FROM wg_users WHERE username='$username'";
                    $db->setQuery($sql);
                    $wg_users = NULL;
                    $db->loadObject($wg_users);

                    if ($wg_users)
                    {
                        $login = true;
                    }
                }
            }
        }

        if ($login)
        {
            //update status username on xgo.vn
            xGo_Update_Status($wg_users->username);

            $expiretime = 0;
            $rememberme = 0;
            @include('config.php');
            $password_new = md5($password . "--" . $dbsettings["secretword"]);
            $cookie       = $wg_users->id . " " . $wg_users->username . " " . $password_new . " " . $rememberme;

            setcookie($game_config['COOKIE_NAME'], $cookie, $expiretime, "/", "", 0);

            $_SESSION['villa_id_cookie']   = $wg_users->villages_id;
            $_SESSION['password']          = $password_new;
            $_SESSION['alliance_id']       = $wg_users->alliance_id;
            $_SESSION['nation_id']         = $wg_users->nation_id;
            $_SESSION['username']          = $wg_users->username;
            $_SESSION['userid']            = $wg_users->id;
            $_SESSION['time_check_online'] = time() - 1000;
            $_SESSION['last_login']        = time();

            //$numUserOnline = new usersOnline();
            //executeXML($numUserOnline->count_users());
            updateAllPlus();
            //cap nhat dan so cho user
            returnWorkersLogin($wg_users->id);
            //cap he so RS
            returnKrsForUser($wg_users->id);
            /* -> chi su dung khi co ky dai level >90 tro len */
            //checkWorldFinished();

            if (!insertFeature($_POST['username'], "Login"))
            {
                globalError('Error!!!Login');
            }

            unset($dbsettings);
            $st_forward = $_REQUEST['redirect'];

            if ($st_forward != '')
            {
                header("Location:$st_forward");
                exit();
            }
            else
            {
                header("Location: index.php");
                exit();
            }
        }
        else
        {
            //sai thong tin truy cap; hoac tai khoan chua kich hoat tai game nay
            $parse['error1']          = '';
            $parse['error2']          = '';
            $parse['error3']          = $lang['error3']
                . ' hoặc tài khoản chưa đăng ký.<a href="http://id.xgo.vn/register.html"> Đăng ký tại đây.</a>';
            $parse['username']        = '';
            $parse['password']        = '';
            $parse['active']          = '';
            $parse['code']            = $_SESSION['security_login'] = md5(time());
            //load news o trang login
            $parse['list_news_login'] = load_list_news();
            //End
            $page                     = parsetemplate(gettemplate('login_body'), $parse);
            display_newlogin($page, $lang['title']);
        }
    }
}
else
{
    $parse['active']          = '';
    $parse['password']        = '';
    $parse['username']        = '';
    $parse['error1']          = '';
    $parse['error2']          = '';
    $parse['error3']          = '';
    $parse['code']            = $_SESSION['security_login'] = md5(time());
    //load news o trang login
    $parse['list_news_login'] = load_list_news();
    //End
    $page                     = parsetemplate(gettemplate('login_body'), $parse);
    display_newlogin($page, $lang['title']);
}

function load_list_news()
{
    global $db, $game_config, $lang;
    includeLang('login');

    //lay news tu database
    $array_news = null;
    $array_news = $db->select('intro_text, url')->from('wg_news')->order('date_create DESC')->limit('0,5')->toList();

    if ($array_news == null)
        return $listnews = 'No News';

    $i = 0;

    foreach ($array_news as $news)
    {
        $parse['Stt']    = ++$i;
        $parse['news']   = $news['intro_text'];
        $parse['url']    = $news['url'];
        $parse['detail'] = $lang['news2'];
        $listnews .= parsetemplate(gettemplate('news_login'), $parse);
    }

    return $listnews;
}

ob_end_flush();
?>
