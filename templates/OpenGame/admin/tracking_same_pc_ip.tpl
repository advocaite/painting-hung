<script language="javascript">
function checkall()
{
	for(i=0;i < document.from_report.checkbox.length;i++){
		document.from_report.checkbox[i].checked=true;
	}
	checkban();
}
function uncheckall()
{
	for (i = 0; i < document.from_report.checkbox.length; i++){
		document.from_report.checkbox[i].checked = false ;
	}
	document.getElementById('bad_list').innerHTML='<input type="submit" name="bad_list" value="{Bad list}" onclick="popupReason(); return false;" class="fm" />';
}
function SearchKey()
{
	keyword = document.getElementById('txt_ip').value;
	if(keyword !='')
	{
		document.location.href="tracking.php?ip="+keyword;	
		return false;		
	}
	document.location.href="tracking.php";
	return false;	
}  
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
function PopUpUserDetail(ip,user)
{
	if (!ip && !user) {
		return true;
	}	
	if (window.ActiveXObject) {
		openWindow('tracking.php?s=detail_ip&ip='+ip+'&username='+user, 'popupsong',650, 520, '');
	} else {
		openWindow('tracking.php?s=detail_ip&ip='+ip+'&username='+user, 'popupsong',650, 520, '');
	}
	return false;
}
function popup()
{
	if (window.ActiveXObject) {
		openWindow('userlist_popup.php?id=1', 'popupsong',380, 230, '');
	} else {
		openWindow('userlist_popup.php?id=1', 'popupsong',380, 230, '');
	}
	return false;
}
function checkban()
{
	var sum=0;
	if (document.from_report.checkbox.length>0)
	{
		for(var i=0;i<document.from_report.checkbox.length;i++)
		{
			if(document.from_report.checkbox[i].checked==true)
			{
				sum++;
			}
		}				
	}
	if(sum>0)
	{
		document.getElementById('bad_list').innerHTML='<input type="submit" name="bad_list" value="{Bad list} ['+sum+']" onclick="popupReason(); return false;" class="fm" />';	
	}
	else
	{
		document.getElementById('bad_list').innerHTML='<input type="submit" name="bad_list" value="{Bad list}" onclick="popupReason(); return false;" class="fm" />';		
	}
	return true;
}
function popupReason()
{
	var isChecked=false;
	if (document.from_report.checkbox.length>0)
	{
		for(var i=0;i<document.from_report.checkbox.length;i++)
		{
			if(document.from_report.checkbox[i].checked==true)
			{
				isChecked=true;
			}
		}				
	}
	else
	{
		if(document.from_report.checkbox.checked==true)
		{
			isChecked=true;
		}
	}
	if (!isChecked)
	{											
		alert("Please select user to get bad list");
	}
	else
	{
		popup();
	}
	return false;	
}  
function PopPunish(id)
{
	if (window.ActiveXObject){
		openWindow('userlist_punish.php?uid='+id, 'popupsong',800,220, '');
	} else {
		openWindow('userlist_punish.php?uid='+id, 'popupsong',800,220, '');
	}		
	return false;
}
</script>
<div id="column-content">
  <div id="content">
    <p><span style="background:#0099FF"><a href="tracking.php">&nbsp;{Same PC}&nbsp;</a></span> | <a href="tracking.php?s=2">{Report Statistic}</a> | <a href="tracking.php?s=3">{Report Message}</a></p>
    <form name="from_report" method="post" onsubmit="return SearchKey();">
	 <input type="hidden" name="reason" id="reason" />
	<fieldset>
      <table align="center" width="100%">
        <tr>
          <td colspan="7" align="center"><b style="font-size:14px; color:#FF0000">{string1}</b></td>
        </tr>
        <tr align="center">
          <td width="13%"><span style="background:#0099FF"><a href="tracking.php">{With IP}</a></span></td>
          <td width="16%"><a href="tracking.php?s=1">{With User}</a></td>
          <td width="15%">{Search by IP}</td>         
          <td width="16%"><input type="text" name="txt_ip" id="txt_ip" value="{txt_ip}" class="fm fm110"/>          </td>
          <td width="11%"><input type="submit" name="find" class="fm" value="{Search}"/>          </td>
          <td width="29%" align="left"><input type="reset" name="reset" class="fm" value="{Clear}"/></td>
        </tr>
      </table>
	  </fieldset>
      <table width="100%" cellspacing="1" cellpadding="2" class="tbg">
        <tr class="rbg">
          <th></th>
          <th>{No}</th>
          <th>{Punish}</th>
          <th>{ip}</th>
          <th>{Username}</th>
          <th>{Amount}</th>
          <th>{Bad list}</th>
          <th>{Banned}</th>
          <th>{Detail}</th>
        </tr>
        {list}
      </table>
      <table width="100%">
        <tr>
          <td width="91"><input name="check_all" type="button" value="Chọn tất cả" onClick="checkall();" class="fm">
          </td>
          <td width="205"><input name="un_check_all" type="button" value="Bỏ chọn tất cả" onClick="uncheckall();" class="fm">
          </td>
          <td width="70"><span id="bad_list"><input type="submit" name="bad_list" value="{Bad list}" onclick="popupReason(); return false;" class="fm" /></span>
          </td>
          <td width="204">
          </td>
          <td width="205"><b>{total_record}</b></td>
          <td width="172">{pagenumber}</td>
        </tr>        
      </table>
    </form>
  </div>
</div>
