<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<title>View message</title>
<style>
.fm {border:#009bdf solid; border-width:1px; font-size:8pt; padding-left:3px; padding-top:2px; padding-bottom:2px;}
.fm1 {border:#009bdf solid; color:#000000; border-width:1px; font-size:8pt; padding-left:3px; padding-top:2px; padding-bottom:2px;}
textarea {border:#009bdf solid; border-width: 1px;}
.fm121 { width:265px;}
.fm120 { width:120px;}
.fm111 { width:200px;}
.fm110 { width:110px;}
.fm170 { width:170px;}
.fm60 { width:60px;}
.fm40 { width:40px;}
.fm25 { width:25px;}
.fm20 { width:20px;}
</style>
</head>
<body>
<script type="text/javascript" language="javascript1.1">
function openWindow(filename, winname, width, height, feature) {	
	var features, top, left;
	var reOpera = /opera/i ;
	var winnameRequired = ((navigator.appName == "Netscape" && parseInt(navigator.appVersion) == 4) || reOpera.test(navigator.userAgent));
	
	left = (window.screen.width - width) / 2;
	top = (window.screen.height - height) / 2;	
	if(feature == '')
		features = "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left + ",status=0,location=0,scrollbars=1";
	else
		features = "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left + "," + feature;
//	if(! winnameRequired)	winname = "";	
	newwindow = window.open(filename, winname, features);
	newwindow.focus();
}
function popup(username)
{
	if (window.ActiveXObject) {
		openWindow('userlist_popup.php?id=2&username='+username, 'popupsong',380,130, '');		
	} else {
		openWindow('userlist_popup.php?id=2&username='+username, 'popupsong',380,130, '');		
	}
	return false;
}
</script>
<div>
<center>
<table style="background: transparent url(images/msg/message.jpg) no-repeat scroll center top; width: 440px; height: 439px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" class="f10" cellpadding="0" cellspacing="0">		
		<tbody><tr>
			<td colspan="4">&nbsp;</td>

		</tr>
<tr>
			<td rowspan="2" width="40"></td>
	  <td class="message" width="75">{sent_or_receive}</td>
<td class="col" width="238">
<input name="txtowner" maxlength="20" style="border: 0px none ; background: transparent url(images/msg/underline.gif) repeat scroll center top; width: 238px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" value="{username}" readonly="readonly" type="text">
			</td>
			<td class="datetime" width="85">{show_date}</td>
	</tr>

<tr>
        <td class="message" width="75">Chủ đề</td>
			<td class="col">
                <input name="txttopic" id="txttopic" maxlength="35" value="{subject}" style="border: 0px none ; background: transparent url(images/en/msg/underline.gif) repeat scroll center top; width: 238px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" onkeyup="telexingVietUC(this,event);" readonly="readonly" type="text">
	  </td>	
        <td class="datetime" width="85">{show_time}</td>	
	</tr>
		
		<tr>
			<td colspan="4">&nbsp;</td>

	</tr>
		
		<tr>
			<td>
				<img src="images/un/a/x.gif" border="0" width="40" height="250">
			</td>
			<td colspan="3">
                <textarea name="txtcontent" readonly="readonly" id="igm" rows="14" class="f10" style="background: transparent url(images/en/msg/underline.gif) repeat scroll center top; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; width: 360px;" onkeyup="telexingVietUC(this,event);">{content}</textarea>

			</td>

		</tr>
		
		<tr>
			<td colspan="4" align="center"><span id="bad_list"><input type="submit" name="bad_list" value="Bad list" onclick="popup('{username}'); return false;" class="fm" /></span></td>
		</tr>
		
	  	<tr>

		  	<td colspan="4" style="background-color: white;">&nbsp;</td>
		</tr>

</tbody></table>
</center></div>
</body>
</html>
