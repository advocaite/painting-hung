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
function PopPunish(id)
{
	if (window.ActiveXObject){
		openWindow('userlist_punish.php?uid='+id, 'popupsong',800,220, '');
	} else {
		openWindow('userlist_punish.php?uid='+id, 'popupsong',800,220, '');
	}		
	return false;
}
function checkall()
{
	if (document.frm_user_list.checkbox.length>0)
	{
		for(i=0;i < document.frm_user_list.checkbox.length;i++)
		{
			document.frm_user_list.checkbox[i].checked=true;
		}			
	}
	else
	{
		document.frm_user_list.checkbox.checked=true;
	}	
	checkdelete();
}
function uncheckall()
{
	if (document.frm_user_list.checkbox.length>0)
	{
		for(i=0;i < document.frm_user_list.checkbox.length;i++)
		{
			document.frm_user_list.checkbox[i].checked=false;
		}			
	}
	else
	{
		document.frm_user_list.checkbox.checked=false;
	}	
	document.getElementById('ban').innerHTML='<input name="ban" type="submit" value="{Ban user}" class="fm" onclick="popUpReason(); return false;"/>';
}
		  

function popUpReason()
{
	var isChecked=false;
	if (document.frm_user_list.checkbox.length>0)
	{
		for(var i=0;i<document.frm_user_list.checkbox.length;i++)
		{
			if(document.frm_user_list.checkbox[i].checked==true)
			{
				isChecked=true;
			}
		}				
	}
	else
	{
		if(document.frm_user_list.checkbox.checked==true)
		{
			isChecked=true;
		}
	}
	if (!isChecked)
	{											
		alert("{Please select user to ban}");
	}
	else
	{
		window.open("userlist_popup.php","_blank","location=1,status=1,scrollbars=1,width=420,height=250,left=100,top=100");
	}	
}  
function accountSister()
{
	var isChecked=false;
	if (document.frm_user_list.checkbox.length>0)
	{
		for(var i=0;i<document.frm_user_list.checkbox.length;i++)
		{
			if(document.frm_user_list.checkbox[i].checked==true)
			{
				isChecked=true;
			}
		}				
	}
	else
	{
		if(document.frm_user_list.checkbox.checked==true)
		{
			isChecked=true;
		}
	}
	if (!isChecked)
	{											
		alert("Please select user to sister");
	}
	else{
		return false;
	}	
}  
function SearchKey()
{
	keyword = document.getElementById('txtUsername').value;
	if(keyword !='')
	{
		document.location.href="userlist.php?keyword="+keyword;	
		return false;		
	}
	document.location.href="userlist.php";
	return false;				
}
function checkdelete()
{
	var sum=0;
	if (document.frm_user_list.checkbox.length>0)
	{
		for(var i=0;i<document.frm_user_list.checkbox.length;i++)
		{
			if(document.frm_user_list.checkbox[i].checked==true)
			{
				sum++;
			}
		}				
	}
	else
	{
		if(document.frm_user_list.checkbox.checked==true)
		{
			sum=1;
		}
	}
	if(sum>0)
	{
		document.getElementById('ban').innerHTML='<input name="ban" type="submit" value="{Ban user} ['+sum+']" class="fm" onclick="popUpReason(); return false;"/>';			
	}
	else
	{
		document.getElementById('ban').innerHTML='<input name="ban" type="submit" value={Ban user}" class="fm" onclick="popUpReason(); return false;"/>';
	}
	return true;
}
</script>
<div id="column-content" onmouseup="click();">
    <div id="content">
	<p>
        <form name="frm_user_list" method="POST" onsubmit="return SearchKey();">
            <input type="hidden" name="reason" id="reason" />
			<input type="hidden" name="date" id="date" />
            <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
                <tr height="35">
                    <td width="35%" >{Sum User}:<strong>{sum_user}</strong></td>
					<td width="31%" align="center" ><input type="Text" id="txtUsername" name="txtUsername" value="{value_name}" size="20" maxlength="20" class="fm fm110">&nbsp;&nbsp;<input type="submit" name="submit_player" class="fm" value="{Search}"/></td>
					<td width="34%" align="center" >{result_search}<strong>{result}</strong></td>
                </tr>
				<tr class="rbg"><td colspan="3"><div id="searchByChar">{Begin name} 
				<a href="userlist.php?BeginKeyword=A">A</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=B">B</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=C">C</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=D">D</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=E">E</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=F">F</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=G">G</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=H">H</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=I">I</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=J">J</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=K">K</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=L">L</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=M">M</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=N">N</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=O">O</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=P">P</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=Q">Q</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=R">R</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=S">S</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=T">T</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=U">U</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=V">V</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=W">W</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=X">X</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=Y">Y</a>
				&nbsp;&nbsp;<a href="userlist.php?BeginKeyword=Z">Z</a></div></td></tr>
            </table>
			<p>
            <table width="100%" cellspacing="1" cellpadding="2" class="tbg">
                <tr class="rbg">
                    <th></th>
                    <th>{Rank}</th>                    
                    <th>{Punish}</th>
                    <th>{Username}</th>
                    <th>{Email}</th>
                    <th>{Population}</th>
                    <th>{Sum village}</th>                    
                    <th>{Bad list}</th>
                    <th>{Baned}</th>
					<th>{Delete}</th>
                </tr>
                {view_users_list}
            </table>
            <table width="100%">
                <tr>
                    <td width="91"><input name="check_all" type="button" value="{Select ALl}" onClick="checkall()" class="fm">                    </td>
                  <td width="214"><input name="un_check_all" type="button" value="{Un Select ALl}" onClick="uncheckall()" class="fm">                    </td>
                  <td width="80"><span id="ban"><input name="ban" type="submit" value="{Ban user}" class="fm" onclick="popUpReason(); return false;"/></span></td><td width="102"></td>
                  <td width="173">{pagenumber}</td>
              </tr>
            </table>               
      </form>
    </div>
</div>