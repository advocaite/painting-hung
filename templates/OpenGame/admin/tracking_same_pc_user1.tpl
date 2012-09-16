<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
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
			//openWindow('tracking.php?s=4?username=' + arrSong[0] + '', 'popupsong', 1001, 1000, '');
			openWindow('tracking.php?s=4', 'popupsong', 1001, 1000, '');
		} else {
			openWindow('tracking.php?s=4', 'popupsong', 1001, 1000, '');
			//openWindow('tracking.php?s=4?username=' + arrSong[0] + '', 'popupsong', 1000, 1000, '');
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
		document.location.href="tracking.php?s=4&username="+keyword;	
		return false;		
	}
	document.location.href="tracking.php?s=4";
	return false;	
} 
function popUpReason()
{
	window.open("userlist_popup.php","_blank","location=1,status=1,scrollbars=1,width=420,height=250,left=100,top=100");
} 
function popUpReason1()
{
	window.open("userlist_popup.php?id=3","_blank","location=1,status=1,scrollbars=1,width=420,height=250,left=100,top=100");
} 
</script>
<style>
/*tables*/
.tbg {background-color: #C0C0C0; width:100%; text-align:center; font-size:12pt;}

table.tbg tr {background-color: #FFFFFF;}

.rbg {background-color: #FFFFFF; font-weight:bold; background-image: url(../images/un/a/c2.gif);}

table.tbg tr.cbg1 td, td.cbg1 {background-color:#F5F5F5}
table.tbg td.cbg2 {background-color:#71D000}

table.tbg tr.s7 td, td.s7 {padding-left:7px; text-align:left}
table.tbg tr.r7 td, td.r7 {padding-right:7px; text-align:right}
.fm {border:#009bdf solid; border-width:1px; font-size:8pt; padding-left:3px; padding-top:2px; padding-bottom:2px;}
</style>

<div id="column-content">
    <div id="content">
        <form name="frm_user_list" method="post" onsubmit="return SearchKey();"> 
		<input type="hidden" name="reason_bad" id="reason_bad" />
		<input type="hidden" name="reason" id="reason" />
		<input type="hidden" name="date" id="date" />
		<fieldset>  
            <table align="center" width="100%">
                <tr>
                    <td colspan="5" align="center"><b style="font-size:14px; color:#FF0000">DANH SÁCH CÁC USER DÙNG CHUNG IP VỚI [<span style="color:rgb(0, 153, 255);">{username}</span>] TRONG NỬA THÁNG GẦN ĐÂY</b> </td>
              </tr>
                <tr>                
                  <td width="407" align="right"> {Search name}: <input type="text" name="txt_username" id="txt_username" value="{value_ch}" class="fm fm110"/>
                  </td>
                  <td width="554"><input type="submit" name="find" value="{find}" class="fm"/>&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" class="fm" value="{Clear}"/>&nbsp;&nbsp;&nbsp;{button1}&nbsp;&nbsp;&nbsp;{button2}
                  </td>
              </tr>
            </table>
			</fieldset>
            <table width="100%" cellspacing="1" cellpadding="2" class="tbg">
                <tr class="rbg">                   
                    <th>{No}</th>
                    <!--<th>Punish</th>-->
                    <th>{Ip}</th>
					<th>{Username}</th>
                    <th>{Amount}</th>
                    <th>{Bad list}</th>
					<th>{Ban}</th>
                    <th>{Detail}</th>
                </tr>
                {list}
            </table>
      </form>
    </div>
</div>
