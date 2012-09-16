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
function checkall()
{
	if (document.frm_banned.checkbox.length>0)
	{
		for(i=0;i < document.frm_banned.checkbox.length;i++)
		{
			document.frm_banned.checkbox[i].checked=true;
		}			
	}
	else
	{
		document.frm_banned.checkbox.checked=true;
	}	
	count_unban();
}
function uncheckall()
{
	if (document.frm_banned.checkbox.length>0)
	{
		for(i=0;i < document.frm_banned.checkbox.length;i++)
		{
			document.frm_banned.checkbox[i].checked=false;
		}			
	}
	else
	{
		document.frm_banned.checkbox.checked=false;
	}
	document.getElementById('unban').innerHTML='<input name="unban" type="submit" value="{Unban user}" class="fm" onclick="return checkUnBan();"/>';	
}

function checkUnBan()
{
	var isChecked=false;	
	if (document.frm_banned.checkbox.length>0)
	{
		for(var i=0;i< document.frm_banned.checkbox.length;i++)
		{
			if(document.frm_banned.checkbox[i].checked==true)
			{
				isChecked=true;
			}
		}				
	}
	else
	{
		if(document.frm_banned.checkbox.checked==true)
		{
			isChecked=true;
		}
	}
	if (!isChecked)
	{											
		alert("{Please select user to unban}");
		return false;
	}
	else
	{
		return true;
	}
	return false;
}  
function SearchKey()
{
	keyword = document.getElementById('txtUsername').value;
	if(keyword !='')
	{
		document.location.href="banned.php?keyword="+escape(keyword)+"";
	}
	else
	{
		document.location.href="banned.php";
	}
	return false;
}
function count_unban()
{
	var sum=0;
	if (document.frm_banned.checkbox.length>0)
	{
		for(var i=0;i<document.frm_banned.checkbox.length;i++)
		{
			if(document.frm_banned.checkbox[i].checked==true)
			{
				sum++;
			}
		}				
	}
	else
	{
		if(document.frm_banned.checkbox.checked==true)
		{
			sum=1;
		}
	}
	if(sum>0)
	{
		document.getElementById('unban').innerHTML='<input name="unban" type="submit" value="{Unban user} ['+sum+']" class="fm" onclick="return checkUnBan();"/>';		
	}
	else
	{
		document.getElementById('unban').innerHTML='<input name="unban" type="submit" value="{Unban user}" class="fm" onclick="return checkUnBan();"/>';
	}
	return true;
}
</script>
<div id="column-content">
    <div id="content"><p>        
         <form method="POST" name="frm_banned" action="">
		 <fieldset>
           	<div><center><b style="color:#FF0000">{USER LIST BANED}</b></center></div>
            <table width="100%">
                <tr>
                    <td width="4%"><input type="hidden" name="username" value="{username}" id="username"  /></td>
                  <td width="4%"><input type="hidden" name="order_type" value="{order_type}" id="order_type"  /></td>
                  <td width="18%"><input type="hidden" name="text" value="{text}" id="text"  /></td>
                  <td width="16%"> {Keywords}: </td>
                  <td width="20%"><input type="text" name="txtUsername" id="txtUsername" value="{value_ch}"/>
                    </td>
                  <td width="38%"><input type="submit" class="fm" name="find" value="{Search}" onclick="return SearchKey();"/>
                    </td>
              </tr>
            </table>
			</fieldset>
            <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
                <tr class="rbg">
                    <th width="2%"><!--<input type="hidden" id="checkbox" />--></th>
                  <th width="5%">{No}</th>                  
                  <th width="19%" align="center">{Username}</th>
                  <th width="18%" align="center">{Ban day}</th>
                  <th width="9%" align="center">{Ban time}</th>
				  <th width="18%" align="center">{Day End}</th>
                  <th width="29%" align="center">{Reason}</th>
              </tr>
                {list}
            </table>
            <table width="100%">
                <tr>
                    <td width="80"><input name="check_all" type="button" value="{Select ALl}" onClick="checkall();" class="fm">
                    </td>
                  <td width="274"><input name="un_check_all" type="button" value="{Un Select ALl}" onClick="uncheckall();" class="fm">
                    </td>
                  <td width="182"><span id="unban"><input name="unban" type="submit" value="{Unban user}" class="fm" onclick="return checkUnBan();"/></span>
                    </td>
                  <td width="229"><b>{Total record}: {total_record}</b></td>
                  <td width="186">{pagenumber}</td>
              </tr>
            </table>
        </form>
    </div>
</div>
