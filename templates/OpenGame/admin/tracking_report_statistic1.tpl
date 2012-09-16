<script type="text/javascript">
function SearchKey()
{
	keyword = document.getElementById('txtUsername').value;
	if(keyword!='')
	{
		if(document.from_report.today.checked)
		{
			document.location.href="{url}&keyword="+keyword+"&SearchTime=ToDay";			
		}
		else if(document.getElementById("day").value >0 && document.getElementById("month").value >0 && document.getElementById("year").value >0)
		{
			document.location.href="{url}&keyword="+keyword+"&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="{url}&keyword="+keyword;			
		}
	}
	else
	{
		if(document.from_report.today.checked)
		{
			document.location.href="{url}&SearchTime=ToDay";			
		}
		else if(document.getElementById("day").value>0)
		{
			document.location.href="{url}&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="{url}";		
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
	for(i=0;i < document.from_report.checkbox.length;i++)
	{
		document.from_report.checkbox[i].checked=true;
	}
}
function uncheckall()
{
	for (i = 0; i < document.from_report.checkbox.length; i++)
	{
		document.from_report.checkbox[i].checked = false ;
	}
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
		openWindow('report_popup.php?rid='+id, 'popupsong',650,350, '');
	} else {
		openWindow('report_popup.php?rid='+id, 'popupsong',650,350, '');
	}
	return false;
} 
</script>

<div id="column-content">
    <div id="content">
    <p><a href="tracking.php">{Same PC}</a> | <span style="background:#0099FF"><a href="tracking.php?s=2">{Report Statistic}</a></span> | <a href="tracking.php?s=3">{Report Message}</a></p>
        <form method="POST" name="from_report">
          	<fieldset>
      <table align="center" width="100%">
        <tr>
          <td colspan="8" align="center"><b style="font-size:14px; color:#FF0000">{string3}</b></td>
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
	  <div><span style="background:{color}"><a href="tracking.php?s=2">&nbsp;{All}&nbsp;</a></span>&nbsp;&nbsp;&nbsp;<span style="background:{color1}"><a href="tracking.php?s=2&tab=1">&nbsp;{ATTACK}&nbsp;</a></span>&nbsp;&nbsp;&nbsp;<span style="background:{color2}"><a href="tracking.php?s=2&tab=2">{DEFEND}</a></span>&nbsp;&nbsp;&nbsp;<span style="background:{color3}"><a href="tracking.php?s=2&tab=3">&nbsp;{TRADE}&nbsp;</a></span>&nbsp;&nbsp;&nbsp;<span style="background:{color5}"><a href="tracking.php?s=2&tab=5">&nbsp;{RARE}&nbsp;</a></span></div>          <table cellspacing="1" cellpadding="2" class="tbg" width="100%" >
                <tr class="rbg">
                    <th width="3%" align="center"><input id="checkbox" type="hidden"></th>
                  <th width="6%">{No}</th>
                  <th >{Title}</th>
                  <th width="18%">{Username Give}</th>
                  <th width="18%">{Username Recive}</th>
                    <th width="12%" >{Times}</th>
        </tr>
                {list}
            </table>
            <table width="100%">
                <tr>
                    <td width="91"><input name="CheckAll" type="button" value="{Select ALL}" onClick="checkall()" class="fm"></td>
                    <td width="291"><input name="UnCheckAll" type="button" value="{Un Select ALL}" onClick="uncheckall()" class="fm">
                    </td>
                  <td width="116"><input name="del_report" type="submit" value="{Delete}" class="fm" onclick="return check_checkbox()"/>
                    </td>
                  <td width="247"><b>{Total record}: {total_record}</b></td>
                  <td width="206">{pagenumber}</td>
              </tr>
            </table>
        </form>
    </div>
</div>
