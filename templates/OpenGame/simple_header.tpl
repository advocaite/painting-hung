<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="{keywords}" name="keywords">
<meta content="{description}" name="description">
<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
<meta name="vs_defaultClientScript" content="JavaScript">
<link rel="stylesheet" href="css/games.css" type="text/css" />
<script src="js/unx.js" type="text/javascript"></script>
<!--[if lt IE 7]>
<script defer type="text/javascript" src="js/pngfix.js"></script>
<![endif]-->
<title>{title}</title>
</head>
<body onLoad="start();setTypingMode(0);">
<div id="dhtmltooltip"></div>
<div id="header">
	<div id="logo"><img src="images/en/a/logo.png"></div>
    <div id="topicon">
       <a href="village1.php"><img id="icon1" src="images/un/a/x.gif" ONMOUSEOVER="ddrivetip('{Village overview}')"; ONMOUSEOUT="hideddrivetip()"></a><a href="village2.php"><img id="icon2" src="images/un/a/x.gif" ONMOUSEOVER="ddrivetip('{Village center}')"; ONMOUSEOUT="hideddrivetip()"></a><a href="map.php"><img id="icon3" src="images/un/a/x.gif" ONMOUSEOVER="ddrivetip('{Map}')"; ONMOUSEOUT="hideddrivetip()"></a><a href="ranking.php"><img id="icon4" src="images/un/a/x.gif" ONMOUSEOVER="ddrivetip('{Statistics}')"; ONMOUSEOUT="hideddrivetip()"></a><a href="report.php"><img id="{n5}" src="images/un/a/x.gif" ONMOUSEOVER="ddrivetip('{Reports}')"; ONMOUSEOUT="hideddrivetip()"></a><a href="messages.php"><img id="{n6}" src="images/un/a/x.gif" ONMOUSEOVER="ddrivetip('{Messages}')"; ONMOUSEOUT="hideddrivetip()"></a><a href="shop.php"><img id="icon7" src="images/un/a/x.gif" ONMOUSEOVER="ddrivetip('{Account plus}')"; ONMOUSEOUT="hideddrivetip()"></a>
		</div>
	<div id="nav">
        <ul>           
           <!--<li><a href="#" onclick="return Npc();">{Npc}</a></li>-->
			<li><a href="http://tranhhung.xgo.vn" target="_blank">{Home}</a></li>		   
           <li><a href="#" onclick="return PopupList();">{Introduction}</a></li>
           <li><a href="http://diendan.xgo.vn/diendan/forumdisplay.php?f=166" target="_blank">{Forum}</a></li>
           <li><a href="profile.php">{My_account}</a></li>
           <li class="lastitem"><a href="{in_out}">{name}</a></li>
        </ul>
        <!--<ul><span id="EventServer"><center><strong>{event_server}</strong></center></span></ul>-->
        <ul style="padding-left:5px;">{Server_time}: <span id="tp1" class="b">{time_server}</span> <strong> (GMT+7)</strong>&nbsp;&nbsp;&nbsp;<strong><span id="date">{show_date}</span></strong><div style="padding-top:20px;">{show_time_gamer}</div></ul>
	</div>		
</div>
<div id="boxmain">        
		<div id="boxcontent">        
		<div id="boxleft"></div>      
        <!--end header-->
