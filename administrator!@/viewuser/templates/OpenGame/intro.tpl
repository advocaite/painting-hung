<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="Asuwa - Game việt - trí tuệ việt" name="keywords">
<meta content="Asuwa - Game việt - trí tuệ việt" name="description">
<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
<meta name="vs_defaultClientScript" content="JavaScript">
<link rel="stylesheet" href="css/intro.css" type="text/css" />
<script language="javascript" type="text/javascript">
	function correctPNG()
    {
       for(var i=0; i<document.images.length; i++)
       {
          var img = document.images[i]
          var imgName = img.src.toUpperCase()
          if (imgName.substring(imgName.length - 3, imgName.length) == "PNG")
          {
             var imgID = (img.id) ? "id='" + img.id + "' " : ""
             var imgClass = (img.className) ? "class='" + img.className + "' " : ""
             var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
             var imgStyle = "display:inline-block;" + img.style.cssText
             if (img.align == "left") imgStyle = "float:left;" + imgStyle
             if (img.align == "right") imgStyle = "float:right;" + imgStyle
             if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle
             var strNewHTML = "<span " + imgID + imgClass + imgTitle
             + " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
             + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
             + "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>"
             img.outerHTML = strNewHTML
             i = i-1
          }
       }
    }

    window.attachEvent("onload", correctPNG);
</script>
<title>AsuwA - Webgame</title>
</head>

<body>
<div class="main">
<div class="text1">
	<span>Phiên bản thử nghiệm </span><br /><span class="text2">với nhiều quà tặng hấp dẫn đang chờ đón bạn...</span>
</div>
<div class="reg">
	<img src="images/en/intro/reg.gif" border="0" usemap="#Map" />
<map name="Map" id="Map">
<!--<area shape="rect" coords="26,21,132,104" href="reg.php" />
<area shape="rect" coords="112,106,198,167" href="http://asuwa.net/forum/" />
-->
  <area shape="rect" coords="33,56,139,139" href="reg.php" />
  <area shape="rect" coords="19,147,111,199" href="login.php" />
<area shape="rect" coords="112,160,198,221" href="http://asuwa.net/forum/" />
</map>
</div>
<div class="award">
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="133"><img src="images/en/intro/tv.png" /></td>
    <td width="73"><img src="images/en/intro/asu.png" /></td>
    <td><img src="images/en/intro/troop.png" /></td>
  </tr>
</table>
</div>
<div class="event">
    <div class="event1">
      <a href="http://asuwa.net/forum/viewtopic.php?f=4&t=24">Đặt tên Việt cho Webgame Việt!</a>
    </div>
    <div class="event2">
      <a href="http://asuwa.net/forum/viewtopic.php?f=4&t=23">Góp ý hay nhận ngay phần thưởng!</a>
    </div>
</div>
<div class="boxright">
    <div class="info">
    <a href="http://asuwa.net/forum/viewforum.php?f=9">Giới thiệu</a>
    </div>
    <div class="guide">
        <a href="http://asuwa.net/forum/viewforum.php?f=10">Hướng dẫn</a>
    </div>
    <div class="privacy">
        <a href="http://asuwa.net/forum/viewforum.php?f=11">Luật chơi</a>
    </div>
</div>
</div>
</body>
</html>
