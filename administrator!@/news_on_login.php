<?php
define ( 'INSIDE', true );
$ugamela_root_path = '../';
include ($ugamela_root_path . 'extension.inc');
include ('includes/db_connect.' . $phpEx);
include ('includes/common.' . $phpEx);

if(!check_user()){header("Location: login.php");}
if($user['authlevel'] <5)
{
    header("Location:index.php");
    exit();
}
else
{
    global $db;
    //them tin tuc vao database
    if(isset($_POST['add_news']))
    {
        $sql = "INSERT INTO `wg_news` (`intro_text`, `url`, `date_create`) VALUES ('".$_POST['txtIntro_news']."', '".$_POST['txtUrl_news']."', '".date('Y-m-d H:i:s',time())."')";
        $db->setQuery($sql);
        $db->query();
    }
    //lay news tu database
    $sql = " SELECT intro_text, url, date_create FROM `wg_news` ORDER BY date_create DESC";
    $db->setQuery($sql);
    $array_news = null;
    $array_news = $db->loadObjectList();
    if($array_news == null) $listnews = '<tr><th>No News</th></tr>';
    $i = 0;
    foreach ($array_news as $news)
    {
        
        $parse['Stt'] = ++$i;
        $parse['news'] = $news->intro_text;
        $parse['url'] = $news->url;
        $parse['date_create'] = $news->date_create;
        $listnews .= parsetemplate(gettemplate('/admin/news_login'), $parse);
    }
    $parse['list_news_login'] = $listnews;
    $page = parsetemplate(gettemplate('/admin/news_on_login'), $parse);
    displayAdmin($page);
}  
?>
