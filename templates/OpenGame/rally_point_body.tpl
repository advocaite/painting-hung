<script language="javascript1.1" type="text/javascript">
//Open popup
function openWindow(filename, winname, width, height, feature) {	
	var features, top, left;
	var reOpera = /opera/i ;
	var winnameRequired = ((navigator.appName == "Netscape" && parseInt(navigator.appVersion) == 4) || reOpera.test(navigator.userAgent));
	
	left = (window.screen.width - width) / 2;
	top = (window.screen.height - height) / 2;	
	if(feature == '')
		features = "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left + ",status=0,location=0,scrollbars=no";
	else
		features = "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left + "," + feature;
//	if(! winnameRequired)	winname = "";
	newwindow = window.open(filename, winname, features);
	newwindow.focus();
}
function PopUp(Info)
{
	if (!Info) {
		return true;
	}
	if (window.ActiveXObject) {
		openWindow('ctc/ctc.php', 'TranhHung', 800, 600, '');
	} else {
		openWindow('ctc/ctc.php', 'TranhHung', 800, 600, '');
	}
	return false;
}
</script>
<p class="title_menu">
<span class="{class_overview}"><a href="build.php?id={id}">{Overview}</a></span> |
<span class="{class_send_troop}"><a href="build.php?id={id}&t=1">{Send troops}</a></span> | 
<span class="{class_ws}"><a href="ws.php" target="_blank">Tập trận</a></span> |
<span class="{class_ctc}"><a href="javascript:void(0)" onclick="return PopUp('TranhHung');">Công thành chiến</a></span>
</p>
{task_content}

