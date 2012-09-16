<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<script type="text/javascript" language="javascript1.1">
function returnLink()
{
	document.location.href="{link}";		
	return false;
}
function SearchKey()
{
	if(document.form_ip.today.checked)
	{
		document.location.href="{link}&SearchTime=ToDay";			
	}
	else if(document.getElementById("day").value>0)
	{
		document.location.href="{link}&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
	}
	else
	{
		document.location.href="{link}";		
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
    <form method="post" name="form_ip">
      <table align="center" width="100%">
        <tr >
          <td colspan="3" align="center">IP:<b style="font-size:14px; color:#FF0000">[{get_ip}]</b>&nbsp;&nbsp;Username:<b style="font-size:14px; color:#FF0000">[{get_username}]</b></td>
		</tr>
		<tr>
		  <td align="center">Thời gian</strong>: <input type="checkbox" onclick="clickCheckbox()" name="today" id="today" {checked}/> hôm nay &nbsp;&nbsp;&nbsp;hoặc&nbsp;&nbsp;
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
						<input type="submit" name="find" value="{find}" class="fm" onclick="return SearchKey();"/>&nbsp;&nbsp;&nbsp;<input type="button" name="find" value="View all" class="fm" onclick="return returnLink();"/>
</td>
        </tr>       
      </table>
      <table width="100%" cellspacing="1" cellpadding="2" class="tbg">
        <tr class="rbg">
          <th>{No}</th>         
          <th>{ip}</th>
          <th>{Username}</th>
          <th>{feature}</th>
          <th>{time}</th>
        </tr>
        {list}
      </table>
      <table width="100%">
        <tr>
          <td> {total_record} </td>
          <td> {pagenumber}</td>
        </tr>
      </table>
    </form>
  </div>
</div>
