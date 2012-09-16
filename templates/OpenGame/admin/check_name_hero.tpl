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
function Edit(id)
{
	value=document.getElementById('aly_'+id).value;
	document.getElementById('div_'+id).innerHTML='<input type="text" size="30" id="txt_'+id+'" value="'+value+'" class="fm"  onchange="Update('+id+');" />';	
}
function Update(id)
{
	value=document.getElementById('txt_'+id).value;
	if(value!='')
	{
		window.open('view_error.php?tab=1&fix_name='+value+'&id='+id,'_top');
	}
}
function SearchKey()
{
	keyword = document.getElementById('txt_name').value;
	if(keyword !='')
	{
		document.location.href="view_error.php?tab=1&keyword="+keyword;	
		return false;		
	}
	document.location.href="view_error.php?tab=1";
	return false;				
}
</script>
<div id="column-content">
  <div id="content">
    <p><span style="background:#0099FF"><a href="view_error.php?tab=1">{Name Hero}</a></span> | <a href="view_error.php?tab=2">{Name Allies}</a> | <a href="view_error.php?tab=3">{Name Village}</a></p>
    <form name="frm_same_pc" method="post" onsubmit="return SearchKey();">
	<fieldset>
      <table align="center" width="100%">
        <tr>
          <td colspan="7" align="center"><b style="font-size:18px; color:#FF0000">{List name troop}</b><br />{Search by name} : <input type="Text" id="txt_name" name="txt_name" value="{value_name}" size="20" maxlength="20" class="fm fm110">&nbsp;&nbsp;<input type="submit" name="submit_player" class="fm" value="{Search}"/></td>
        </tr>       
      </table>
	  </fieldset>	   
      <table width="100%" cellspacing="1" cellpadding="2" class="tbg">
        <tr class="rbg">
		  <th>{No}</th>
          <th>{Name Hero}</th>
		  <th>{Level}</th>
		  <th>{Point experience}</th>
		  <th>{Village}</th>
		  <th>{UserName}</th>
		  <th></th>	 
        </tr>
        {list}
		<tr>
			<td colspan="9" align="right">{page}&nbsp;&nbsp;</td>
        </tr>
      </table>      
    </form>
  </div>
</div>
