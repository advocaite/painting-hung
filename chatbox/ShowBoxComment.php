<?php
session_start();
/*header("Expires: Sat, 05 Nov 2005 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/xml; charset=UTF-8"); */

    define('INSIDE', true);
    global $db;
    $ugamela_root_path = '../';
    include($ugamela_root_path . 'extension.inc');
    include($ugamela_root_path . 'includes/db_connect.' . $phpEx);
    $html = "";
    if(!isset($_REQUEST['action']))
    {
        if(isset($_SESSION['alliance_id'])&& is_numeric($_SESSION['alliance_id']) > 0)
        {
            $sql = "SELECT date, name, url, message FROM wg_wtagshoutbox WHERE alliance_id=".$_SESSION['alliance_id']." ORDER BY messageid DESC LIMIT 20";
            $db->setQuery($sql);
            $lstChatdata = null;
            $lstChatdata = $db->loadObjectList();
            //print_r($lstChatdata);
            if($lstChatdata)
            {
                foreach($lstChatdata as $chatdata)
                {
                    $_date =  new DateTime($chatdata->date);
                    $html .=
                        "<div style='margin: 3px 0px 3px 0px;'>" .
                            "<span class='smallfont'>" .
                                "<span class='time'>[" . $_date->format('d-m H:i') . "]</span>" .
                            "</span>" .
                            "<b><font color='darkred'>" . $chatdata->name . "</font></b>: " .
                            "<font  color='black'><b>" . $chatdata->message . "</b></font>" .
                        "</div>";
                }
            }
        }
    }
    else{
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == "insertcm")
        {
            // Get a sender IP (it will be in use in the next wTag version)
            $remote = $_SERVER["REMOTE_ADDR"];
            $converted_address=ip2long($remote);// Store it converted
            $name = $_SESSION['username'];
            $url = "";
            $msg = $_REQUEST['ct'];
            
            if (get_magic_quotes_gpc()) 
            {
                $name = mysql_real_escape_string(stripslashes($name));
                $url = mysql_real_escape_string(stripslashes($url));
                $msg = mysql_real_escape_string(stripslashes($msg));
            }
            else 
            {
                $name = mysql_real_escape_string($name);
                $url = mysql_real_escape_string($url);
                $msg = mysql_real_escape_string($msg);
            }
            if(strlen($msg) > 100)
            {
                $html = "Nhập quá số ký tự cho phép";
            }
            elseif(strlen($msg) == 0)
            {
                $html = "Chưa nhập nội dung";
            }
            else
            {
                $sql = "INSERT INTO wg_wtagshoutbox SET name= '$name',date_post=".date('d').", url='$url', message= '$msg', ip='$converted_address', date=now(),alliance_id=".$_SESSION['alliance_id']."";
                $db->setQuery($sql);
                if($db->query()) $html = "Success";
            }
        }
    }
    echo $html;
?>
