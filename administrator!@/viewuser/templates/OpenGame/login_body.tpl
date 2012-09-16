<script language="JavaScript" src="js/keyboard.js"></script>
<link href="css/keyboard.css" rel="stylesheet" type="text/css">
<div id="box1">
  <div id="box3">
  <div class="nb">
  	<img src="images/en/t2/newsbox2vn.gif" /><br /><br />
    <span class="f12 p4">{news1}<br /> &nbsp;&nbsp;<a target="_blank" href="http://14.0.18.37/diendan/showthread.php?t=305">{news2}</a></span>
  </div>
	<table width=100% cellpadding=0 cellspacing=0 class=topborder style="border-bottom:0px solid #FF9610;">
		<tr>
			<td><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="584" height="229">
                  <param name="movie" value="images/flash/banner.swf">
                  <param name="quality" value="high">
				  <param name="BGCOLOR" value="#FFFFFF">
				  <param name="wmode" value="transparent"/>
                  <embed wmode="transparent" src="images/flash/banner.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="584" height="229"></embed>
                </object></td>
        </tr>
    </table>
    <h5>{images1}</h5>
    <p class="text9">{content}</p>
    {active}{error3}
	<form name="formular" action="login.php" method="post">
	<input type="hidden" name="check" value="{code}" />
    <p>
    <table width="100%" cellspacing="1" cellpadding="0" class="p1">
    	<tr>
        	<td>
            	<table width="100%" cellspacing="1" cellpadding="0">
                <tr>
                  <td id=""><label for="username">{user_name}:</label><input name="username" id="username" type="text" value="{username}" class="fm fm110 keyboardInput" tabindex="1"/>&nbsp;&nbsp;{error1}
                  </td>
                </tr>
                <tr>
                  <td><label for="password">{pass}:</label><input name="password" type="password" id="password" value="{password}" class="fm fm110 keyboardInput" tabindex="2"/>&nbsp;&nbsp;{error2}<input type="hidden" name="redirect" value="index.php" />
                  </td>
                </tr>
            </table>
            </td>
        </tr>
    </table>
    </p>
    <input type="hidden" name="" id="" style="visibility:hidden;"/>
	<p style="padding-left:235px;"><input type="image" src="{images}" value="" tabindex="3"></p>
    </form>
  </div>
</div>
</div>
</div>