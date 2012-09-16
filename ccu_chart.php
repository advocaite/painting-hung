<?php
/*
	Plugin Name: ccu_chart.php
	Plugin URI: http://asuwa.vn/includes/ccu_chart.php
	Description: 
	+ Ve bieu do user online doc tu file xml
	Version: 1.0.0
	Author: tdnquang
	Author URI: http://tdnquang.com
*/

define('INSIDE', true);

$ugamela_root_path = './';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'includes/db_connect.'.$phpEx);
include($ugamela_root_path . 'includes/common.'.$phpEx);
require_once($ugamela_root_path . 'includes/function_xml.php');

define(DURATION, 60);
$arrPoints = getXMLContent();
//$arrPointsReverse = array_reverse($arrPoints);
//echo "<pre/>"; print_r($arrPoints); die();
header ("Content-type: image/jpg");

$x_max = 670;
$y_max = 365;

$im = @imagecreate ($x_max, $y_max) or die ("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate ($im, 234, 234, 234);
$text_color = imagecolorallocate ($im, 233, 14, 91);
$graph_color = imagecolorallocate ($im,25,25,25);

$x1=30;
$y1=305;//chieu dai truc tung

//truc hoanh
imageline ($im, $x1, $y1, $y1+330, $y1, $text_color);
//truc tung 
imageline ($im, $x1, $x1, $x1, $y1, $text_color);
//goc toa do
imagestring($im, 2, $x1-5, $y1, "O", $graph_color);

//START: ve mui ten
//mui ten truc tung
imageline ($im, $x1, $x1, $x1 - 5, $x1 + 5, $text_color);//trai
imageline ($im, $x1, $x1, $x1 + 5, $x1 + 5, $text_color);//phai
imagestring($im, 2, $x1-25, $x1-15, "user online", $graph_color);//user online

//mui ten truc hoanh
imageline ($im, $y1 + 330, $y1, $y1 + 330 - 5, $y1 - 5, $text_color);//tren
imageline ($im, $y1 + 330, $y1, $y1 + 330 - 5, $y1 + 5, $text_color);//duoi
imagestring($im, 2, $y1 + 320, $y1, "time(h)", $graph_color);//time
//END: ve mui ten

//START: ve cac nac thoi gian (truc hoanh)
for($i = 1; $i <= 24; $i ++){
	imageline ($im, $x1 + 24*$i, $y1 - 3, $x1 + 24*$i, $y1 + 3, $text_color);
	imagestring($im, 2, $x1 + 24*$i - 3, $y1 + 3, $i, $graph_color);		
}
//END: ve cac nac thoi gian (truc hoanh)

//START: ve cac nac so user online (truc tung)

for($i = 1; $i <= 8; $i ++){
	imageline ($im, $x1 - 3, $y1 - 30*$i, $x1 + 3, $y1 - 30*$i, $text_color);
	imagestring($im, 2, $x1 - 30, $y1 - 30*$i - 8, 30*$i, $graph_color);	
}
//END: ve cac nac so user online (truc tung)


//START: line noi toa do voi diem dau tien
for($i=1; $i < 2; $i++){
	//imageline ($im, $x1, $y1, $x1 + 20, $arrPoints[$i]['amount'], $text_color);	
}
//END: line noi toa do voi diem dau tien

$first="yes";
for($i=1; $i <= count($arrPoints); $i++){
	if($arrPoints[$i+1]['time'] - $arrPoints[$i]['time'] >= 1){
		$x2 = $x1 + 24;		
	    $y2 = 305 - $arrPoints[$i]['amount'];    
	    //imagestring($im,2,$x2-5,$y2-10,$arrPoints[$i]['amount'],$graph_color);
	    if($first=="no"){
	        imageline ($im,$x1,$y1,$x2,$y2,$text_color);		
	    }	
	}	
    $x1 = $x2;
    $y1 = $y2;
    $first = "no";    		
}
imagejpeg($im);
?>