<script type="text/javascript">
function SearchKey()
{
	keyword = document.getElementById('txtUsername').value;
	if(keyword!='')
	{
		if(document.from_report.today.checked)
		{
			document.location.href="tracking.php?s=3&keyword="+keyword+"&SearchTime=ToDay";			
		}
		else if(document.getElementById("day").value >0 && document.getElementById("month").value >0 && document.getElementById("year").value >0)
		{
			document.location.href="tracking.php?s=3&keyword="+keyword+"&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="tracking.php?s=3&keyword="+keyword;			
		}
	}
	else
	{
		if(document.from_report.today.checked)
		{
			document.location.href="tracking.php?s=3&SearchTime=ToDay";			
		}
		else if(document.getElementById("day").value>0)
		{
			document.location.href="tracking.php?s=3&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="tracking.php?s=3";		
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
    <p><a href="tracking.php">{Same PC}</a> | <a href="tracking.php?s=2">{Report Statistic}</a> | <span style="background:#0099FF"><a href="tracking.php?s=3">{Report Message}</a></span></p>
        <form method="POST" name="from_report">
          	<fieldset>
      <table align="center" width="100%">
        <tr>
          <td colspan="8" align="center"><b style="font-size:14px; color:#FF0000">{string4}</b></td>
        </tr>
        <tr align="center">         
         <td><strong>{Search by Username} :</strong>&nbsp;<input type="text" name="txtUsername" id="txtUsername" value="{value_ch}" class="fm"/>&nbsp; <strong>{Times}</strong>: <input type="checkbox" onclick="clickCheckbox()" name="today" id="today" {checked}/> 
         {today} &nbsp;&nbsp;&nbsp;{or}&nbsp;&nbsp;
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
	           <table cellspacing="1" cellpadding="2" class="tbg" width="100%" >
                <tr class="rbg">                    
                    <th width="5%">{No}</th>
                    <th width="31%">{Username}</th>
                    <th width="31%">{Inbox}</th>    
					<th width="33%">{Outbox}</th>               
                </tr>
                {list}
            </table>
            <table width="100%">
                <tr>
                    <td width="91">&nbsp;</td>
                  <td width="291">&nbsp;</td>
                  <td width="116">&nbsp;</td>
                  <td width="247"><b>{total_record}</b></td>
                  <td width="206">{pagenumber}</td>
              </tr>
            </table>
        </form>
    </div>
</div>
