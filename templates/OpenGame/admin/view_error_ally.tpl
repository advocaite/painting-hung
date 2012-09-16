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
	document.getElementById('div_'+id).innerHTML='<input type="text" size="30" name="" id="txt_'+id+'" value="'+value+'" class="fm"  onchange="Update('+id+');" />';	
}
function Update(id)
{
	value=document.getElementById('txt_'+id).value;
	window.open('view_error.php?tab=2&fix_name='+value+'&id='+id,'_top');
}
function checkall()
{
	if (document.frm_same_pc.checkbox.length>0)
	{
		for(i=0;i < document.frm_same_pc.checkbox.length;i++)
		{
			document.frm_same_pc.checkbox[i].checked=true;
		}			
	}
	else
	{
		document.frm_same_pc.checkbox.checked=true;
	}	
	checkdelete();
}
function uncheckall()
{
	if (document.frm_same_pc.checkbox.length>0)
	{
		for(i=0;i < document.frm_same_pc.checkbox.length;i++)
		{
			document.frm_same_pc.checkbox[i].checked=false;
		}			
	}
	else
	{
		document.frm_same_pc.checkbox.checked=false;
	}	
	document.getElementById('delete').innerHTML='<input name="delete" type="submit" value="{Delete}" class="fm" onclick="return check_checkbox()"/>';
}	
function checkdelete()
{
	var sum=0;
	if (document.frm_same_pc.checkbox.length>0)
	{
		for(var i=0;i<document.frm_same_pc.checkbox.length;i++)
		{
			if(document.frm_same_pc.checkbox[i].checked==true)
			{
				sum++;
			}
		}				
	}
	else
	{
		if(document.frm_same_pc.checkbox.checked==true)
		{
			sum=1;
		}
	}
	if(sum>0)
	{
		document.getElementById('delete').innerHTML='<input name="delete" type="submit" value="{Delete} ['+sum+']" class="fm" onclick="return check_checkbox()"/>';
	}
	else
	{
		document.getElementById('delete').innerHTML='<input name="delete" type="submit" value="{Delete}" class="fm" onclick="return check_checkbox()"/>';
	}
	return true;
}
function check_checkbox()
{
	var del= confirm('Are you sure delete ?');
	if(del==true)
	{
		var isChecked=false;
		if (document.frm_same_pc.checkbox.length>0)
		{
			for(var i=0;i<document.frm_same_pc.checkbox.length;i++)
			{
				if(document.frm_same_pc.checkbox[i].checked==true)
				{
					isChecked=true;
				}
			}				
		}
		else
		{
			if(document.frm_same_pc.checkbox.checked==true)
			{
				isChecked=true;
			}
		}
		if (!isChecked)
		{											
			alert("Please select user to delete");
		}
		return isChecked;
	}
	else
	{
		return false;
	}
} 
function SearchKey()
{
	keyword = document.getElementById('txt_name').value;
	if(keyword !='')
	{
		document.location.href="view_error.php?tab=2&keyword="+keyword;	
		return false;		
	}
	document.location.href="view_error.php?tab=2";
	return false;				
}		
</script>
<div id="column-content">
  <div id="content">
    <p><a href="view_error.php?tab=1">{Name Hero}</a> | <span style="background:#0099FF"><a href="view_error.php?tab=2">{Name Allies}</a></span> | <a href="view_error.php?tab=3">{Name Village}</a></p>
    <form name="frm_same_pc" method="post" onsubmit="return SearchKey();">
<fieldset>
      <table align="center" width="100%">
        <tr>
          <td colspan="7" align="center"><b style="font-size:18px; color:#FF0000">{List allies}</b><br />{Search by name} : <input type="Text" id="txt_name" name="txt_name" value="{value_name}" size="20" maxlength="20" class="fm fm110">&nbsp;&nbsp;<input type="submit" name="submit_player" class="fm" value="{Search}"/></td>
        </tr>       
      </table>
	  </fieldset>
      <table width="100%" cellspacing="1" cellpadding="2" class="tbg">
        <tr class="rbg">
		  <th></th>
          <th>{No}</th>
          <th>{Name Allies}</th>
		  <th>{Master}</th>
          <th>{Total members}</th>
		  <th></th>
        </tr>
        {list}
		<tr>
			<td></td>
		  <td colspan="2"><input name="check_all" type="button" value="{Select ALl}" onClick="checkall()" class="fm">&nbsp;&nbsp;  <input name="un_check_all" type="button" value="Bỏ chọn tất cả" onClick="uncheckall()" class="fm">&nbsp;&nbsp;<span id="delete"><input name="delete" type="submit" value="{Delete}" class="fm" onclick="return check_checkbox()"/></span></td>	
          <td colspan="4" align="right">{page}&nbsp;&nbsp;</td>
        </tr>
      </table>      
    </form>
  </div>
</div>
