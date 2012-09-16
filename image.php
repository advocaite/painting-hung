<?php 
ob_start(); 
define('INSIDE',true);
$ugamela_root_path = './';
require_once($ugamela_root_path . 'extension.inc');
require_once($ugamela_root_path . 'includes/db_connect.php'); 
require_once($ugamela_root_path . 'includes/common.'.$phpEx);
include($ugamela_root_path . 'includes/rank.' . $phpEx);

global $db,$rank;
if(isset($_GET['id']) && is_numeric($_GET['id']))
{
	if($rank)
	{
		foreach($rank as $v)
		{
			if($v['id'] == $_GET['id'])
			{
				$string1 =$v['username'];
				$string2 =$v['rank'];	
				$string3 =$v['population'];				
				break;
			}	
		}
	}
}
header("Content-type: image/jpg; charset=utf-8");
$im     = imagecreatefrompng("images/invite2.png");
$orange = imagecolorallocate($im,52,51,51);
imagestring($im,5,250,55,$string1,$orange);
imagestring($im,5,250,77,$string2,$orange);
imagestring($im,5,250,99,$string3,$orange);
imagepng($im);
imagedestroy($im);
?>

