<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{slogan}</title>
<link rel="Shortcut Icon" href="../favicon.ico" type="image/x-icon" />
<link href="../css/style_admin.css" type="text/css" rel="stylesheet">
<script language="JavaScript" src="../js/keyboard.js"></script>
<link href="../css/keyboard.css" rel="stylesheet" type="text/css">
</head><body>
<form name="formular" action="login.php" method="post">
<input type="hidden" name="check" value="{code}" />
<div id="modal" class="modalFrame" style="display: block; top: 160px; left: 324.5px;">
   <div style="display: block;" id="loginDialog">
      <p><span class="dialog_modal_title lang-signOn">{Administrator}</span></p>

      <p><span id="login_error_msg" class="dialog_error" style=""></span></p>

      <p><span class="dialog_label lang-username langinsert-pre">{Username}:&nbsp;</span><span class="dialog_input">
      <input id="username" name="username"  onfoucs="this.select();" type="text"></span></p>
      <div style="clear: both;"></div>
      <p><span class="dialog_label lang-password langinsert-pre">{Password}:&nbsp;</span><span class="dialog_input">
        <input id="password" name="password" type="password"></span></p>
      <div style="clear: both; color:#FF0000;">{message}</div>
      <p style="text-align:center; padding-left:150px;"><input type="submit" name="submit" class="stdButton" value="{Sign On}"/></p>
      <div style="clear: both;"></div>
   </div>   
</div>
</form>
</body></html>