<script type="text/javascript">
function checkall()
{
	for(i=0;i < document.form_attack.checkbox.length;i++)
	{
		document.form_attack.checkbox[i].checked=true;
	}
}
function uncheckall()
{
	for (i = 0; i < document.form_attack.checkbox.length; i++)
	{
		document.form_attack.checkbox[i].checked = false ;
	}
}
function check_checkbox()
{
	var del= confirm('Are you sure delete ?');
	if(del==true)
	{
		var isChecked=false;
		if (document.form_attack.checkbox.length>0)
		{
			for(var i=0;i<document.form_attack.checkbox.length;i++)
			{
				if(document.form_attack.checkbox[i].checked==true)
				{
					isChecked=true;
				}
			}				
		}
		else
		{
			if(document.form_attack.checkbox.checked==true)
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
</script>

<div id="column-content">
    <div id="content">
      <p><a href="tracking.php">{Same PC}</a> | <a href="tracking.php?s=1">{Report Statistic}</a> | <a href="tracking.php?s=2">{Resource Statistic}</a> | <a href="tracking.php?s=3">{Attack Statistic}</a> | <span style="background:#FFCC66"><a href="tracking.php?s=7">{Message}</a></span></p>
        <form method="POST" name="form_attack" action="tracking.php?s=7">
            <table>
                <tr>
                    <td colspan="7" align="center"><b style="color:#FF0000">TRACKING MESSAGE</b></td>
                </tr>
                <tr>
                    <td> From user: </td>
                    <td><input type="text" name="txt_from_user" value="{from_username}"	 />
                    </td>
                    <td> To user: </td>
                    <td><input type="text" name="txt_to_user" value="{to_username}" />
                    </td>
                    <td><select name="time">
                            <option value="1" {select_all}>All</option>
                            <option value="2" {select_1day}>cách đây 1 ngày</option>
                            <option value="3" {select_1week}>cách đây 1 tuần</option>
                            <option value="4" {select_1month}>cách đây 1 tháng</option>
                        </select>
                    </td>
                    <td><input type="submit" name="submit" value="{Search}" />
                    </td>
                    <td> | <a href="tracking.php?s=7&t=reset_message"> <b>Reset </b> </a> </td>
                </tr>
            </table>            
            <b style="color:#FF6600">{msg}</b>
            <table cellspacing="1" cellpadding="2" width="100%" border="1">
                <tr>
                    <th width="6%" align="center">
                  <input id="checkbox" type="hidden"></th>
                  <th width="6%">No</th>
                  <th width="19%">{Title}</th>
                  <th width="36%">Content</th>
                    <th width="19%">User send - User receive</th>
                  <th width="14%">{Time}</th>
              </tr>
                {list}
            </table>
            <table width="100%">
                <tr>
                    <td width="96">
                    <input name="check_all" type="button" value="Chọn tất cả" onClick="checkall()" class="std">
                    </td>
                  <td width="286">
                    <input name="un_check_all" type="button" value="Bỏ chọn tất cả" onClick="uncheckall()" class="std">
                    </td>
                  <td width="116"><input name="del_attack_report" type="submit" value="Xóa" class="std" onclick="return check_checkbox();"/>
                    </td>
                  <td width="228"><b>{Total record}: {total_record}</b></td>
                  <td width="225">{pagenumber}</td>
              </tr>
            </table>
        </form>
    </div>
</div>
