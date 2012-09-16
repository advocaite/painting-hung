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
	count();
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
	document.getElementById('ban').innerHTML='<input name="ban" type="submit" value="{Ban user}" class="fm" onclick="popUpReason(); return false;"/>';
	document.getElementById('delete').innerHTML='<input name="delete" type="submit" value="{Delete record}" class="fm" onclick="return check_checkbox();"/>';
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
		else
		{
			return true;
		}
	}
	return false;
} 	
function popUpReason()
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
		alert("Please select user to ban");
	}
	else
	{
		window.open("{base_url}/userlist_popup.php","_blank","location=1,status=1,scrollbars=1,width=450,height=250");
	}	
}  
function count()
{
	var sum=0;
	if (document.frm_user_list.checkbox.length>0)
	{
		for(var i=0;i<document.frm_user_list.checkbox.length;i++)
		{
			if(document.frm_user_list.checkbox[i].checked==true)
			{
				sum++;
			}
		}				
	}
	else
	{
		if(document.frm_user_list.checkbox.checked==true)
		{
			sum=1;
		}
	}
	if(sum>0)
	{
		document.getElementById('ban').innerHTML='<input name="ban" type="submit" value="{Ban user} ['+sum+']" class="fm" onclick="popUpReason(); return false;"/>';	
		document.getElementById('delete').innerHTML='<input name="delete" type="submit" value="{Delete record} ['+sum+']" class="fm" onclick="return check_checkbox();"/>';
	}
	else
	{
		document.getElementById('ban').innerHTML='<input name="ban" type="submit" value="{Ban user}" class="fm" onclick="popUpReason(); return false;"/>';
		document.getElementById('delete').innerHTML='<input name="delete" type="submit" value="{Delete record}" class="fm" onclick="return check_checkbox();"/>';
	}
	return true;
}	
function SearchKey()
{
	keyword = document.getElementById('txtUsername').value;
	if(keyword !='')
	{
		document.location.href="bad_list.php?username="+keyword;	
		return false;		
	}
	document.location.href="bad_list.php?username";
	return false;	
}    
</script>

<div id="column-content">
 <div id="content">
	<p>
     	<form name="frm_user_list" method="post" action="">
            <input type="hidden" name="reason" id="reason" />
			<input type="hidden" name="date" id="date" />
			<fieldset>
            <div><center><b style="color:#FF0000">{USER BAD LIST}</b></center></div>           
            <table align="center" width="100%">
                <tr>
                    <td width="28%"></td>
                    <td width="10%">{Username}:</td>
                  <td width="17%"><input class="fm f80" type="Text" id="txtUsername" name="txtUsername" value="{value_name}" size="20" maxlength="20">                    </td>
                    <td width="45%"><input type="submit" name="submit_player" id="" value="{Search}"  class="fm"/>                    </td>
                </tr>
            </table>
             </fieldset>
            <table class="tbg" width="100%" cellpadding="0" cellspacing="1">
                <tr class="rbg">
                  <th></th>
                  <th>{No}</th>
                  <th>{Punish}</th>
                  <th>{Username}</th>
                  <th>{Reason}</th>
              </tr>
                {view_users_list}
            </table>
            <table width="100%">
                <tr>
                    <td width="88"><input name="check_all" type="button" value="Chọn tất cả" onClick="checkall();"class="fm">
                  </td>
                  <td width="117"><input name="un_check_all" type="button" value="Bỏ chọn tất cả" onClick="uncheckall();" class="fm">
                  </td>
                  <td width="88"><span id="ban"><input name="ban" type="submit" value="{Ban user}" class="fm" onclick="popUpReason(); return false;"/></span></td>
                  <td width="114"><span id="delete"><input name="delete" type="submit" value="{Delete record}" class="fm" onclick="return check_checkbox();"/></span></td>
                  <td width="268"><b>{Total record}: {total_record}</b></td>
                  <td width="255">{Page}{pagenumber} / {total_page}</td>
              </tr>
            </table>
        </form>
    </div>
</div>
