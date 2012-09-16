<script language="javascript">
function SearchKey()
{
	keyword = document.getElementById('txtUsername').value;
	if(keyword!='')
	{
		if(document.frm_security.today.checked)
		{
			document.location.href="security.php?keyword="+keyword+"&Time=ToDay";			
		}
		else if(document.getElementById("day").value >0 && document.getElementById("month").value >0 && document.getElementById("year").value >0)
		{
			document.location.href="security.php?keyword="+keyword+"&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="security.php?keyword="+keyword;			
		}
	}
	else
	{
		if(document.frm_security.today.checked)
		{
			document.location.href="security.php?Time=ToDay";			
		}
		else if(document.getElementById("day").value>0)
		{
			document.location.href="security.php?SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
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
</script>
<div id="column-content">
    <div id="content">
        <form name="frm_security"  method="post" onsubmit="return SearchKey();">  
		<fieldset>
		<div style="color:#FF0000; font-size:16px; font-weight:bolder;"><center>Danh sách truy cập của users</center></div>
            <table align="center" width="100%" class="">
                <tr>
					<td><strong>Tìm kiếm theo</strong>&nbsp;{Keywords}: <input type="text" name="txtUsername" id="txtUsername" value="{value_ch}" class="fm"/>&nbsp; <strong>Thời gian</strong>: <input type="checkbox" onclick="clickCheckbox()" name="today" id="today" {checked}/> hôm nay &nbsp;hoặc&nbsp;&nbsp;
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
						<input type="submit" name="find" value="{find}" class="fm"/>
</td>		 
                </tr>
            </table>
			</fieldset>
            <table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbg">
                <tr class="rbg">
                    <th>No</th>
                    <!--<th>{id}</th>-->
                    <th>{username}</th>
                    <th>{ip}</th>
                    <th>{feature}</th>
                    <th>{time}</th>
                </tr>
                {list}
            </table>
            <table width="100%">
                <tr>
                    <td width="84%"> {total_record} </td>
                    <td width="16%"> {pagenumber}</td>
                </tr>
            </table>
        </form>
    </div>
</div>
