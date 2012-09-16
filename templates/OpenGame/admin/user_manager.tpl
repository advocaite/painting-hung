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
	<fieldset><div style="color:#FF0000; text-align:center; font-size:16px; font-weight:bolder;">{content}</div></fieldset>
	<span style="color:#FF0000"><strong>{error}</strong></span>
	<table width="100%" cellspacing="1" cellpadding="2" class="tbg">
                <tr class="rbg">
                    <th>{No}</th>
                    <th>{Username}</th>
                    <th>{Email}</th>     
					<th>{Authlevel}</th>          
                </tr>
                {view_users_list}
      </table>
            <table width="100%">
              <tr>
                <td colspan="3"><input type="submit" class="fm fm110" onclick="Add();" value="{Add}" /></td>
                  <td align="right" >{pagenumber}</td>
              </tr>
			  <tr style="display:none;" id="Add">
			   <form name="frm_user" method="POST" action="user_manager.php">			   
                  <td width="130">{Name}:</td>
				  <td colspan="3"><input name="username" type="text" class="fm fm110"/>&nbsp;&nbsp;<select name="authlevel"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>&nbsp;&nbsp;<input type="submit" class="fm fm110" onclick="Add_New();" value="{Add}" /></td>
           </form>
		   </tr>
			</table>  
	 
    </div>
</div>