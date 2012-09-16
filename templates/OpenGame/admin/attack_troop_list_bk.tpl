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
    <p class=\"txt_menue\"><a href="attack_troop.php">&nbsp;Attack troop&nbsp;</a> | <span style="background:#0099FF"><a href="attack_troop.php?s=1">Attack troop backup</a></span></p>
	<fieldset><center>
	  ATTACK TROOP BACKUP LIST
	</center></fieldset>
    <form name="frm_user_list" method="POST">
      <input type="hidden" name="reason" id="reason" />
      <table class="tbg" width="100%" cellpadding="0" cellspacing="1">
        <tr class="rbg">
          <th width="9%">{No}</th>
          <th width="22%">Troop id</th>
          <th width="14%">Num</th>
          <th width="16%">Die num</th>
          <th width="13%">Attack id</th>
          <th width="13%">Hero id</th>
		   <th width="13%">Status</th>
        </tr>
        {view_attack_troop_list}
      </table>
      <table width="100%">
        <tr>
          <td width="393"></td>
          <td width="123">Page{pagenumber} of {total_page}</td>
        </tr>
      </table>
    </form>
  </div>
</div>
