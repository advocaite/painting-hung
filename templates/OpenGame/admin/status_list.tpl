<style>
.title_menu {font-size:11pt;}
.selected a {color:#fbb010; font-weight:bold;}
</style>
<script language="javascript1.1" type="text/javascript">
function SearchKey()
{
	keyword = document.getElementById('txtVillage').value;
	if(keyword!='')
	{
		if(document.frm_status_list.today.checked)
		{
			document.location.href="status.php?keyword="+keyword+"&Time=ToDay";			
		}
		else if(document.getElementById("day").value >0 && document.getElementById("month").value >0 && document.getElementById("year").value >0)
		{
			document.location.href="status.php?keyword="+keyword+"&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="status.php?keyword="+keyword;			
		}
	}
	else
	{
		if(document.frm_status_list.today.checked)
		{
			document.location.href="status.php?Time=ToDay";			
		}
		else if(document.getElementById("day").value>0)
		{
			document.location.href="status.php?SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="status.php";
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
    <p class="title_menu"><span style="background:#0099FF"><a href="status.php">{Wg Status}</a></span> | <a href="status.php?s=sbk">{Wg Status backup}</a></p>
    <form name="frm_status_list" method="POST">
      <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
	  <tr height="35">
		<td><strong>{Keywords}</strong>&nbsp;: <input type="text" name="txtVillage" id="txtVillage" value="{value_ch}" class="fm"/>&nbsp; <strong>Thời gian</strong>: <input type="checkbox" onclick="clickCheckbox()" name="today" id="today" {checked}/> {today} &nbsp;&nbsp;&nbsp;{or}&nbsp;&nbsp;
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
	  <p>
      <table width="100%" class="tbg" cellpadding="2" cellspacing="1">
        <tr class="rbg">         
          <th width="6%">{No}</th>
		   <th width="8%">Object_Id</th>
          <th width="11%">{Village}</th>
          <th width="6%">{Type}</th>
          <th width="19%">{Time begin}</th>
          <th width="20%">{Time end}</th>
          <th width="10%">{Cost time}</th>
          <th width="6%">{Status}</th>
          <th width="6%">{Order}</th>
          <th width="8%">{Level}</th>
        </tr>
        {view_users_list}
      </table>
      <table width="100%">
        <tr>
          <td width="91"><!--<input name="check_all" type="button" value="Chọn tất cả" onClick="checkall()" class="fm">-->          </td>
          <td width="236"><!--<input name="un_check_all" type="button" value="Bỏ chọn tất cả" onClick="uncheckall()" class="fm">-->          </td>
          <td width="73"><!--<input name="delete_status" type="submit" value="Xóa" class="fm" onclick="return check_checkbox()"/>-->          </td>
          <td width="122">&nbsp;</td>
          <td width="214"><b>{Total} : {total_record}</b></td>
          <td width="211">{Page} {pagenumber} / {total_page}</td>
        </tr>
      </table>
      <table width="100%">
	  <tr>
	  	  <td>{Status=1} :<strong>[{status1}]</strong>&nbsp;&nbsp;&nbsp;{Status=0} :<strong>[{status2}]</strong></td>
          <td align="center"><input type="button" name="find" value="{Backup} {backup_status}" class="fm" onclick="javascript:window.open('{link}','_top');"/></td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td align="center"> </td>
        </tr>
      </table>
    </form>
  </div>
</div>
