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
			openWindow('tracking.php?s=4&username=' + arrSong[0] + '', 'popupsong', 1001, 1000, '');			
		} else {
			openWindow('tracking.php?s=4&username=' + arrSong[0] + '', 'popupsong', 1000, 1000, '');
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
function PopUpIp(ip)
{
	if (!ip)
	{
		return true;
	}	
	if (window.ActiveXObject) {
		openWindow('tracking.php?ip='+ip, 'popupsong',1000,650, '');
	} else {
		openWindow('tracking.php?ip='+ip, 'popupsong',1000,650, '');
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
function SearchKey()
{
	keyword = document.getElementById('txt_username').value;
	if(keyword !='')
	{
		document.location.href="tracking.php?s=1&username="+keyword;	
		return false;		
	}
	document.location.href="tracking.php?s=1";
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
        <p><span style="background:rgb(0, 153, 255);"><a href="tracking.php">{Same PC}</a></span> | <a href="tracking.php?s=2">{Report Statistic}</a> | <a href="tracking.php?s=3">{Report Message}</a></p>
        <form name="frm_same_pc" method="post" onsubmit="return SearchKey();"> 
		<fieldset>  
            <table align="center" width="100%">
                <tr>
                    <td colspan="5" align="center"><b style="font-size:14px; color:#FF0000">{string2}</b> </td>
              </tr>
                <tr>
                    <td width="15%" align="center"><a href="tracking.php">&nbsp;{With IP}&nbsp;</a></td>
                  <td width="15%" align="center"><span style="background:rgb(0, 153, 255);"><a href="tracking.php?s=1">&nbsp;{With User}&nbsp;</a></span></td>
                  <td width="19%"> {Search by Username}: </td>
                  <td width="15%"><input type="text" name="txt_username" id="txt_username" value="{value_ch}" class="fm fm110"/>
                  </td>
                  <td width="36%"><input type="submit" name="find" value="{find}" class="fm"/>&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" class="fm" value="{Clear}"/>
                  </td>
              </tr>
            </table>
			</fieldset>
            <table width="100%" cellspacing="1" cellpadding="2" class="tbg">
                <tr class="rbg">
                   
                    <th>{No}</th>
                    <th>{Punish}</th>
                    <th>{Username}</th>
                    <th>{ip}</th>
                    <th>{Amount}</th>
                     <th>{Bad list}</th>
                    <th>{Detail}</th>
                </tr>
                {list}
            </table>
            <table width="100%">
                <tr>
                    <td>                                        </td>
                  <td width="204"><b>{total_record}</b></td>
                  <td width="174">{pagenumber}</td>
              </tr>
            </table>
      </form>
    </div>
</div>
