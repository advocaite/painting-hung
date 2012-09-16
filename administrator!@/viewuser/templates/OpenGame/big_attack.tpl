<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Asuwa - Webgame</title>
</head>
<link rel="stylesheet" href="css/games.css" type="text/css" />
<style>
body{
background-color:#000000;
}
/*f of outside*/
#f1,#f2,#f3,#f4,#f5 {position:absolute; width:830px; height:620px; left:0px; top:0px; background-repeat:no-repeat;z-index:1;}
#f1 {background-image:url(images/congthanh/congthanh1.jpg);}
#f2 {background-image:url(images/congthanh/congthanh2.jpg);}
#f3 {background-image:url(images/congthanh/congthanh3.jpg);}
#f4 {background-image:url(images/congthanh/congthanh4.jpg);}
#f5 {background-image:url(images/congthanh/congthanh5.jpg);}
#oreslink1 {position:absolute; width:830px; height:620px; left:0; top:0px; z-index:3;}
#fixmetoo {
position:fixed; right: 0px; bottom: 0px; padding-top:2px;
background-image:url(../images/un/a/fixmetoo.jpg);
background-repeat:no-repeat;
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
font-weight:bolder;
text-align:center;
height:20px;
width:100px;
cursor:pointer;
 }
pre.fixit { overflow:auto;border-left:1px dashed #000;border-right:1px dashed #000;padding-left:2px;}
</style>
<body style="top:0px; left:0px;">
<div id="dhtmltooltip"></div>
<div id="f1"></div>
<img src="images/un/a/x.gif" name="resfeld" width="830" height="620" usemap="#rx" id="oreslink1">
<!--<img src="../../images/congthanh/congthanh1.jpg" name="resfeld" width="830" height="620" usemap="#rx" id="oreslink1">-->    
<map name="rx">
      <area href="build.php?id=3"  coords="282,169,60" shape="circle" ONMOUSEOVER="ddrivetip('Mộc')"; ONMOUSEOUT="hideddrivetip()">

	  <area href="build.php?id=8"  coords="550,178,60" shape="circle" ONMOUSEOVER="ddrivetip('Hỏa')"; ONMOUSEOUT="hideddrivetip()">
	  <area href="build.php?id=12" coords="246,421,60" shape="circle" ONMOUSEOVER="ddrivetip('Kim')"; ONMOUSEOUT="hideddrivetip()">
	  <area href="build.php?id=18"  coords="422,310,60" shape="circle" ONMOUSEOVER="ddrivetip('Thủy')"; ONMOUSEOUT="hideddrivetip()">
	  <area href="village2.php"  coords="550,444,60" shape="circle" ONMOUSEOVER="ddrivetip('Thổ')"; ONMOUSEOUT="hideddrivetip()">
      </map>

          <div>

<!--<div id="big_attack">
<img src="images/un/a/x.gif" name="resfeld" usemap="#rx" id="oreslink1">
      <map name="rx">
            <area href="build.php?id=1"  coords="554,177,40" shape="circle" ONMOUSEOVER="ddrivetip('Mỏ đá cấp 10')"; ONMOUSEOUT="hideddrivetip()">

			<area href="build.php?id=2"  coords="558,441,40" shape="circle" ONMOUSEOVER="ddrivetip('Mỏ đá cấp 11')"; ONMOUSEOUT="hideddrivetip()">
			<area href="build.php?id=6"  coords="424,311,40" shape="circle" ONMOUSEOVER="ddrivetip('Mỏ sắt cấp 11')"; ONMOUSEOUT="hideddrivetip()">
			<area href="build.php?id=8" coords="280,162,40" shape="circle" ONMOUSEOVER="ddrivetip('Lâm trường cấp 12')"; ONMOUSEOUT="hideddrivetip()">
			<area href="village2.php" coords="249,428,40" shape="circle" ONMOUSEOVER="ddrivetip('Nội thành')"; ONMOUSEOUT="hideddrivetip()">
  </map>
</div>-->
<script type="text/javascript" src="js/tipbox.js"></script>
</body>
</html>
