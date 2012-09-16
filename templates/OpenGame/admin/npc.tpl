<script language="javascript">
//Open popup
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
//
function PopUpSong(songInfo, obj)
{
	if (!songInfo) {
		return true;
	}
	var arrSong = songInfo.split('||');
	if (arrSong.length == 2) {
		if (window.ActiveXObject) {
			openWindow('viewuser/login.php?username=' + arrSong[0] + '', 'popupsong', 1001, 1000, '');
		} else {
			openWindow('viewuser/login.php?username=' + arrSong[0] + '', 'popupsong', 1000, 1000, '');
		}
		if (obj) {
			obj.name = 'zing';
			obj.href = location.href.replace('#' + obj.name, '') + '#' + obj.name;
		} else {
			return false;
		}
	}
	return false;
}
function Add()
{
	document.getElementById("Add").style.display="block";
}
</script>
<div id="column-content" onmouseup="click();">
    <div id="content">
	<p>
	<fieldset><div style="color:#FF0000; text-align:center; font-size:16px; font-weight:bolder;">Danh Sách Đấu Trường</div></fieldset>
   <table width="100%" cellspacing="1" cellpadding="2" class="tbg">
                <tr class="rbg">
                    <th>Stt</th>
                    <th>Tên</th>                     
					<th>Đường dẫn</th>                         
                </tr>
                {row}
                <tr id="Add" style="display:none;">
                	<td colspan="4">
                    <form method="post" action="npc.php">
                     <table width="100%" cellspacing="0" cellpadding="0">
                        <tr >
                          <td align="center"><input type="text" value="" name="id" class="fm" size="1" maxlength="1" /></td>
                          <td align="center"><input type="text" value="" name="name" class="fm" /></td>
                          <td align="center"><input type="text" value="" name="link" class="fm" size="50" /></td>
                          <td align="center"><input type="submit" name="add" value="Thêm" onclick="" class="fm" /></td>
                        </tr>
                       </table>
                        </form>
                      </td>
                </tr>
                <tr>
                	<td colspan="4"><input type="submit" value="Thêm đấu trường" onclick="Javascript:document.getElementById('Add').style.display='block';" class="fm" /></td>
                </tr>
      </table>     	 
    </div>
</div>
