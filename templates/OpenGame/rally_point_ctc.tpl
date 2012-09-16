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
<table class="tbg" width="100%" cellpadding="2" cellspacing="1">
  <tr>
   <td>Dieu Kien Tham Gia CTC</td>
   <td><a href="#" onclick="return PopUp('TranhHung');"><img src="images/anm1vn.gif" /></a></td>
  </tr>
</table>


