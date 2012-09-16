<script type="text/javascript">
function SearchKey()
{
	keyword = document.getElementById('txtUsername').value;
	if(keyword!='')
	{
		if(document.from_report.today.checked)
		{
			document.location.href="message.php?tab={tab}&keyword="+keyword+"&SearchTime=ToDay";			
		}
		else if(document.getElementById("day").value >0 && document.getElementById("month").value >0 && document.getElementById("year").value >0)
		{
			document.location.href="message.php?tab={tab}&keyword="+keyword+"&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="message.php?tab={tab}&keyword="+keyword;			
		}
	}
	else
	{
		if(document.from_report.today.checked)
		{
			document.location.href="message.php?tab={tab}&keyword={username}&SearchTime=ToDay";			
		}
		else if(document.getElementById("day").value>0)
		{
			document.location.href="message.php?tab={tab}&keyword={username}&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="message.php?tab={tab}&keyword={username}";		
		}
	}
	return false;
}
function clickCheckbox()
{
	if(document.getElementById("today").checked==true)
	{
		document.getElementById("day").disabled=true;
		document.getElementById("month").disabled=true;
		document.getElementById("year").disabled=true;
	}
	else
	{
		document.getElementById("day").disabled=false;		
		document.getElementById("month").disabled=false;
		document.getElementById("year").disabled=false;
	}
}
function checkall()
{
	if (document.from_report.checkbox.length>0)
	{
		for(i=0;i < document.from_report.checkbox.length;i++)
		{
			document.from_report.checkbox[i].checked=true;
		}			
	}
	else
	{
		document.from_report.checkbox.checked=true;
	}	
	checkdelete();
}
function uncheckall()
{
	if (document.from_report.checkbox.length>0)
	{
		for(i=0;i < document.from_report.checkbox.length;i++)
		{
			document.from_report.checkbox[i].checked=false;
		}			
	}
	else
	{
		document.frm_user_list.checkbox.checked=false;
	}	
	document.getElementById('delete').innerHTML='<input type="submit" name="delete" value="{Delete}" class="fm" />';
}
function check_checkbox()
{
	var del= confirm('Are you sure delete ?');
	if(del==true)
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
			alert("Please select report to delete");
		}
		return isChecked;
	}
	else
	{
		return false;
	}
}
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
function PopUpDetail(id)
{
	if (!id) {
		return true;
	}	
	if (window.ActiveXObject) {
		openWindow('message.php?tab=view&keyword={username}&id='+id, 'popupsong',480,440, '');
	} else {
		openWindow('message.php?tab=view&keyword={username}&id='+id, 'popupsong',480,440, '');
	}
	return false;
} 
function checkdelete()
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
	else
	{
		if(document.from_report.checkbox.checked==true)
		{
			sum=1;
		}
	}
	if(sum>0)
	{
		document.getElementById('delete').innerHTML='<input type="submit" name="delete" value="{Delete} ['+sum+']" class="fm" />';
	}
	else
	{
		document.getElementById('delete').innerHTML='<input type="submit" name="delete" value="{Delete}" class="fm" />';
	}
	return true;
}
</script>

<div id="column-content">
    <div id="content">
    <p><a href="tracking.php">{Same PC}</a> | <a href="tracking.php?s=2">{Report Statistic}</a> | <span style="background:#0099FF"><a href="tracking.php?s=3">&nbsp;{Report Message}&nbsp;</a></span></p>
        <form method="POST" name="from_report" action="">
		<input type="hidden" name="reason" id="reason"/>
       <fieldset>
      <table align="center" width="100%">
        <tr>
          <td colspan="8" align="center"><b style="font-size:14px; color:#FF0000">{REPORT MESSAGES} [ {username} ]</b></td>
        </tr>
        <tr align="center">         
         <td><strong>{Search name} :</strong>&nbsp;<input type="text" name="txtUsername" id="txtUsername" value="{value_ch}" class="fm"/>&nbsp; <strong>{Times}</strong>: <input type="checkbox" onclick="clickCheckbox()" name="today" id="today" {checked}/> {today} &nbsp;&nbsp;&nbsp;{or}&nbsp;&nbsp;
					<select name="day" id="day" class="fm" tabindex="5" >
					<option value="0">--</option>
					{option_day}
					</select>&nbsp;
					<select name="month" id="month" class="fm" tabindex="6" >
					<option value="0"  selected="selected">--</option>
					{option_month}
				      </select>&nbsp; <select name="year" id="year" class="fm" tabindex="7" >
						<option value="0"  selected="selected">--</option>
						{option_year}
				        </select>&nbsp;&nbsp;&nbsp;
						<input type="submit" name="find" value="{find}" class="fm" onclick="return SearchKey();"/>
			</td>
        </tr>		
      </table>
	  </fieldset>
	       <p class="title_menu">
<span style="background:{color1}"><a href="message.php?tab=1&keyword={username}">{Inbox}</a></span> ({inbox}) |
<span style="background:{color2}"><a href="message.php?tab=2&keyword={username}">{Outbox}</a></span> ({outbox})
</p>
<table class="tbg" width="100%" cellpadding="2" cellspacing="1">
  <tbody>
    <tr class="rbg">
      <td title="Chọn tất cả" width="19"></td>
	  <td width="40">Stt</td>
      <td width="502">Chủ đề</td>
      <td width="171">{sent_or_receive}</td>
      <td width="215">Thời gian</td>
    </tr>
    {row}
    <tr>
      <td>&nbsp;</td>
      <td class="s7" colspan="3"><input name="check_all" type="button" value="{Select ALL}" onClick="checkall();" class="fm">&nbsp;&nbsp;<input name="un_check_all" type="button" value="{Un Select ALL}" onClick="uncheckall();" class="fm">&nbsp;&nbsp;<span id="delete"><input type="submit" name="delete" value="{Delete}" class="fm" onclick="return check_checkbox();" /></span></td>
      <td align="center"></td>
    </tr>
  </tbody>
</table>
            <table width="100%">
                <tr>
                   <td colspan="3">&nbsp;</td>
                  <td width="247"><b>{total_record}</b></td>
                  <td width="206">{Page}{pagenumber} / {total_page}</td>
              </tr>
            </table>
        </form>
    </div>
</div>
