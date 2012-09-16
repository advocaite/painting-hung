<script language="javascript">
function checkall()
{
	for(i=0;i < document.frm_user_list.checkbox.length;i++)
	{
		document.frm_user_list.checkbox[i].checked=true;
	}
}
function uncheckall()
{
	for (i = 0; i < document.frm_user_list.checkbox.length; i++)
	{
		document.frm_user_list.checkbox[i].checked = false ;
	}
}
function check_checkbox()
{
	var del= confirm('Are you sure delete ?');
	if(del==true)
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
			alert("Please select record to delete");
		}
		return isChecked;
	}
	else
	{
		return false;
	}
} 		  
function backup()
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
		alert("Please select record to backup");
	}
	return isChecked;
	
} 		  
</script>

<div id="column-content">
  <div id="content">
    <p class=\"txt_menue\"><span style="background:#FFCC66"><a href="roll_backup.php">Fix troop keep</a></span> | <a href="roll_back.php?s=2">Status backup</a></p>
    <form name="frm_user_list" method="POST">
      <input type="hidden" name="reason" id="reason" />
      <table width="100%">
        <tr>
          <td align="center"><b style="color:#FF0000">STATUS LIST</b> </td>
        </tr>
      </table>
      <table width="100%" cellpadding="2" cellspacing="1" border="1">
        <tr>
          <th width="6%"></th>
          <th width="6%">{No}</th>
          <th width="6%">{Village}</th>
          <th width="20%">{Type}</th>
          <th width="20%">{Time begin}</th>
          <th width="15%">{Time end}</th>
          <th width="6%">{Cost time}</th>
          <th width="6%">{Status}</th>
          <th width="6%">{Order}</th>
          <th width="6%">{Level}</th>
        </tr>
        {view_users_list}
      </table>
      <table width="100%">
        <tr>
          <td width="91"><input name="check_all" type="button" value="Chọn tất cả" onClick="checkall()" class="std">          </td>
          <td width="236"><input name="un_check_all" type="button" value="Bỏ chọn tất cả" onClick="uncheckall()" class="std">          </td>
          <td width="73"><input name="delete_status" type="submit" value="Xóa" class="std" onclick="return check_checkbox()"/>          </td>
          <td width="122"><input name="move_status" type="submit" value="Backup" class="std" onclick="return backup()"/></td>
          <td width="214"><b>Total record: {total_record}</b></td>
          <td width="211">Page{pagenumber} Of {total_page}</td>
        </tr>
      </table>
      <br/> <br/> <br/>
      <table width="100%">       
        <tr>
          <td align="center">Chuyển tất cả record có status = 1 ở table wg_status sang table wg_status_backup </td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td align="center"><a href="status.php?s=move_all_status"><b style=" font-size:16px">Move All</b></a> </td>
        </tr>
      </table>
    </form>
  </div>
</div>
