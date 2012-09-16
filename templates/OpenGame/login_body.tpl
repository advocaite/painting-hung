<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<title>Tranh Hùng - Game việt - trí tuệ việt</title>
	<script language="JavaScript" src="js/keyboard.js"></script>
	<link href="css/keyboard.css" rel="stylesheet" type="text/css">
	<link href="css/base.css" rel="stylesheet" type="text/css" />
	<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body>
	<div id="container" align="center">
	  <div class="login_but">
	       <div class="login_but1">
	         <a href="http://tranhhung.xgo.vn" title="Trang chủ" class="readon2">Trang chủ</a>
	    </div>
	        <div class="login_but1">
	            <a href="http://diendan.xgo.vn/diendan/forumdisplay.php?f=166" title="Trang chủ" class="readon2">Diễn đàn</a>
	        </div>
	        <div class="login_but1">
	            <a href="https://id.xgo.vn/topup.html" title="Trang chủ" class="readon2">Nạp thẻ</a>
	        </div>
	  </div>
	    <div class="content">
	        <div class="left">
	            <div class="logo"><img src="images/logo.gif"  /></div>
	            <div class="waiting">
	              <script type="text/javascript">
	AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','250','height','80','src','images/waiting','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','images/waiting' ); //end AC code
	</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="250" height="80">
	                <param name="movie" value="images/waiting.swf" />
	                <param name="quality" value="high" />
	                <embed src="images/waiting.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="250" height="80"></embed>
	              </object>
	            </noscript></div>
	        </div>
	        <div class="right">
	        {active}{error3}
            <!--<strong style="color:#F00">Server hiện đang bận hoặc bảo trì,vui lòng xem thông tin chi tiết tại trang chủ!</strong>-->
	        <form name="formular" action="login.php{lang_}" method="post">
	            <input type="hidden" name="check" value="{code}" />
	            <label for="username">{user_name}:</label>
	            <input name="username" id="username" type="text" value="{username}" class="input fm fm110 keyboardInput" tabindex="1"/>&nbsp;&nbsp;{error1}<br />
	            &nbsp;<label for="password">{pass}:</label>
	            <input name="password" type="password" id="password" value="{password}" class="input fm fm110 keyboardInput" tabindex="2"/>&nbsp;&nbsp;{error2}<input type="hidden" name="redirect" value="index.php" /><br />
	            <input type="hidden" name="" id="" style="visibility:hidden;"/>
	            <input type="submit" class="dk_but" value="Đăng nhập" tabindex="3"/>
                <!--<input type="button" class="dk_but" value="Đăng nhập" tabindex="3"/>-->
                <bR />
	        </form>
	            <a href="https://id.xgo.vn/register.php">Đăng kí</a> | <a href="https://id.xgo.vn/getpassword.html">Quên mật khẩu</a>
	        </div>
	        <div class="footer">
	            <div class="footer_text">Phát triển & thiết kế bởi SunGroup. Phiên bản Tranh Hùng (05/2011)</div>
	        </div>
	    </div>
	</div>
</body>
</html>