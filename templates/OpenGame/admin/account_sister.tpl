<script language="javascript">
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
	count();
}
function uncheckall()
{
	if (document.frm_banned.checkbox.length>0)
	{
		for (i = 0; i < document.frm_banned.checkbox.length; i++)
		{
			document.frm_banned.checkbox[i].checked = false ;
		}
	}
	else
	{
		document.frm_banned.checkbox.checked=false;
	}
	document.getElementById('delete').innerHTML='<input name="del" type="submit" value="{Delete}" class="fm" onclick="return checkDelAccSister();"/>';
}
function count()
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
		document.getElementById('delete').innerHTML='<input name="del" type="submit" value="{Delete} ['+sum+']" class="fm" onclick="return checkDelAccSister();"/>';
	}
	else
	{
		document.getElementById('delete').innerHTML='<input name="del" type="submit" value="{Delete}" class="fm" onclick="return checkDelAccSister();"/>';
	}
	return true;
}
function checkDelAccSister()
{
	var isChecked=false;
	if (document.frm_banned.checkbox.length>0)
	{
		for(var i=0;i<document.frm_banned.checkbox.length;i++)
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
		alert("{Please select user} !");
	}
	else
	{
		return true;
	}
	return false;	
}  
function SearchKey()
{
	keyword = document.getElementById('txt_username').value;
	if(keyword !='')
	{
		document.location.href="account_sister.php?keyword="+escape(keyword)+"";
	}
	else
	{
		document.location.href="account_sister.php";
	}
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

</script>

<div id="column-content">
    <div id="content">        
         <form method="POST" name="frm_banned">
		 <fieldset>
            <table width="100%">
                <tr>
                    <td align="center"><b style="color:#FF0000">{ACCOUNT SISTER}</b></td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="6%"><input type="hidden" name="username" value="{username}" id="username"  /></td>
                    <td width="6%"><input type="hidden" name="order_type" value="{order_type}" id="order_type"  /></td>
                    <td width="17%"><input type="hidden" name="text" value="{text}" id="text"  /></td>
                  <td width="14%"> {Username}: </td>
                  <td width="16%"><input type="text" name="txt_username" id="txt_username" value="{value_username}" class="fm"/>
                    </td>
                    <td width="41%"><input type="submit" name="find" class="fm" value="{Search}" onclick="return SearchKey();"/></td>
              </tr>
            </table>
            </fieldset>
            <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
                <tr class="rbg">
                    <th></th>
                    <th>{No}</th>                  
                    <th align="center">{Username} </th>
                    <th align="center">{Administrator}</th>
                    <th align="center">{Time}</th>                    
                </tr>
                {list}
            </table>
            <table width="100%">
                <tr>
                    <td width="81"><input name="check_all" type="button" value="Chọn tất cả" onClick="checkall();" class="fm">
                    </td>
                  <td width="273"><input name="un_check_all" type="button" value="Bỏ chọn tất cả" onClick="uncheckall();" class="fm">
                    </td>
                  <td width="181"><span id="delete"><input name="del" type="submit" value="{Delete}" class="fm" onclick="return checkDelAccSister();"/></span></td>
                  <td width="196"><b>{Total record}: {total_record}</b></td>
                  <td width="220">{pagenumber}</td>
              </tr>
            </table>
        </form>
    </div>
</div>
