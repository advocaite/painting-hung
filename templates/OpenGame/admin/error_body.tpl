<script language="javascript">
function SearchKey()
{
	keyword = document.getElementById('txtUsername').value;
	if(keyword!='')
	{
		if(document.frm_error.today.checked)
		{
			document.location.href="error.php?keyword="+keyword+"&Time=ToDay";			
		}
		else if(document.getElementById("day").value >0 && document.getElementById("month").value >0 && document.getElementById("year").value >0)
		{
			document.location.href="error.php?keyword="+keyword+"&SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="error.php?keyword="+keyword;			
		}
	}
	else
	{
		if(document.frm_error.today.checked)
		{
			document.location.href="error.php?Time=ToDay";			
		}
		else if(document.getElementById("day").value>0)
		{
			document.location.href="error.php?SearchTime="+document.getElementById("day").value+"-"+document.getElementById("month").value+"-"+document.getElementById("year").value;
		}
		else
		{
			document.location.href="error.php";		
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
	for(i=0;i < document.frm_error.checkbox.length;i++)
	{
		document.frm_error.checkbox[i].checked=true;
	}
	checkdelete();
}
function uncheckall()
{
	for (i = 0; i < document.frm_error.checkbox.length; i++)
	{
		document.frm_error.checkbox[i].checked = false ;
	}
	document.getElementById('delete').innerHTML='<input name="delete" type="submit" value="Xóa lỗi" class="fm" onclick="return check_checkbox()"/>';
}
function check_checkbox()
{
	var del= confirm('Are you sure delete ?');
	if(del==true)
	{
		var isChecked=false;
		if (document.frm_error.checkbox.length>0)
		{
			for(var i=0;i<document.frm_error.checkbox.length;i++)
			{
				if(document.frm_error.checkbox[i].checked==true)
				{
					isChecked=true;
				}
			}				
		}
		else
		{
			if(document.frm_error.checkbox.checked==true)
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
function checkdelete()
{
	var sum=0;
	if (document.frm_error.checkbox.length>0)
	{
		for(var i=0;i<document.frm_error.checkbox.length;i++)
		{
			if(document.frm_error.checkbox[i].checked==true)
			{
				sum++;
			}
		}				
	}
	if(sum>0)
	{
		document.getElementById('delete').innerHTML='<input name="delete" type="submit" value="Xóa '+sum+' lỗi" class="fm" onclick="return check_checkbox()"/>';
	}
	else
	{
		document.getElementById('delete').innerHTML='<input name="delete" type="submit" value="Xóa lỗi" class="fm" onclick="return check_checkbox()"/>';
	}
	return true;
}		  
</script>
<div id="column-content">
    <div id="content">
	<p>
        <form name="frm_error"  method="post"> 
		<fieldset>  
            <table align="center" width="100%" class="">
                <tr align="center">
					<td><strong>{Keywords}</strong>&nbsp;: <input type="text" name="txtUsername" id="txtUsername" value="{value_ch}" class="fm"/>&nbsp; <strong>Thời gian</strong>: <input type="checkbox" onclick="clickCheckbox()" name="today" id="today" {checked}/> {today} &nbsp;&nbsp;&nbsp;{or}&nbsp;&nbsp;
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
            <table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbg">
                <tr>
					<td width="5%"></td>
                    <th width="8%">{sort}</th>
                    <th width="62%">{content}</th> 
					<th>{time}</th>
                </tr>
                {list}
            </table>
            <table width="100%">
                <tr>
                    <td width="91"><input name="check_all" type="button" value="Chọn tất cả" onClick="checkall()" class="fm">                    </td>
                  <td colspan="2"><input name="un_check_all" type="button" value="Bỏ chọn tất cả" onClick="uncheckall()" class="fm">                    </td>
                  <td width="531"><span id="delete"><input name="delete" type="submit" value="Xóa lỗi" class="fm" onclick="return check_checkbox()"/></span>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{error_current} <strong>{total_record}</strong>&nbsp;&nbsp;<input type="hidden" name="list_delete" value="{list_delete}" /><input name="delete_all" type="submit" value="Xóa {total_record} lỗi" class="fm"/></td>
                  <td width="173">{pagenumber}</td>
              </tr>
            </table>
        </form>
    </div>
</div>
