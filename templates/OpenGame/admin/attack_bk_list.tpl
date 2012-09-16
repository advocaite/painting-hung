<script language="javascript">
function checkall()
{
	if (document.frm_user_list.checkbox.length>0)
	{
		for(i=0;i < document.frm_user_list.checkbox.length;i++)
		{
			document.frm_user_list.checkbox[i].checked=true;
		}			
	}
	else
	{
		document.frm_user_list.checkbox.checked=true;
	}	
}
function uncheckall()
{
	if (document.frm_user_list.checkbox.length>0)
	{
		for(i=0;i < document.frm_user_list.checkbox.length;i++)
		{
			document.frm_user_list.checkbox[i].checked=false;
		}			
	}
	else
	{
		document.frm_user_list.checkbox.checked=false;
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
    <p class="txt_menue"><a href="attack.php">&nbsp;Attack&nbsp;</a> | <span style="background:#0099FF"><a href="attack.php?s=1">&nbsp;Attack backup&nbsp;</a></span></p>
	<fieldset><center><b style="color:#FF0000">ATTACK BACKUP LIST</b></center></fieldset>
    <form name="frm_user_list" method="POST">
      <input type="hidden" name="reason" id="reason" />
      <table class="tbg" width="100%" cellpadding="0" cellspacing="1">
        <tr class="rbg">          
          <th width="6%">{No}</th>
          <th width="20%">Village Attack</th>
          <th width="20%">Village Defence</th>
          <th width="6%">{Type}</th>
          <th width="10%">Building</th>
		   <th width="10%">Status</th>
        </tr>
        {view_attack_list}
      </table>
      <table width="100%">
        <tr align="right">
          <td>Page{pagenumber} of {total_page}</td>
        </tr>
      </table>     
    </form>
  </div>
</div>
