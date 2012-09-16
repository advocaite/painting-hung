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
function UpdateLevel(type,id)
{
	level=document.getElementById(id).value;
	window.open('roll_back.php?s=maxlevel&type='+type+'&fix_level='+level+'&id='+id,'_top');
}	
</script>
<div id="column-content">
  <div id="content">
    <p class=\"txt_menue\"><a href="roll_back.php">Fix troop keep</a> | <a href="roll_back.php?s=vw">View worker</a> | <a href="roll_back.php?s=uw">Update worker</a> | <a href="roll_back.php?s=vkrs">View krs</a> | <a href="roll_back.php?s=ukrs">Update krs</a> | <a href="roll_back.php?s=fmc">Fix merchant</a> | <a href="roll_back.php?s=update_rank">Update Rank</a> | <span style="background:#0099FF"><a href="roll_back.php?s=maxlevel">Over MaxLevel</a></span></p>
    <form name="frm_same_pc" method="post" onsubmit="return SearchKey();">
<fieldset>
      <table align="center" width="100%">
        <tr>
          <td colspan="7" align="center"><b style="font-size:14px; color:#FF0000">Danh sách village có level vượt qua maxlevel</b></td>
        </tr>       
      </table>
	  </fieldset>
      <table width="100%" cellspacing="1" cellpadding="2" class="tbg">
        <tr class="rbg">
          <th>No</th>
          <th>Name Building</th>
		  <th>Level Limit</th>
          <th>Level Current</th>
          <th>Village</th>
          <th>UserName</th>   
        </tr>
        {list}
      </table>      
    </form>
  </div>
</div>
