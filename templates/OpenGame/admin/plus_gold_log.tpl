<script language="javascript">
function checkall()
{
	if (document.form_give_gold.checkbox.length>0)
	{
		for(i=0;i < document.form_give_gold.checkbox.length;i++)
		{
			document.form_give_gold.checkbox[i].checked=true;
		}
	}
	else
	{
		document.form_give_gold.checkbox[i].checked=true;
	}
}
function uncheckall()
{
	if (document.form_give_gold.checkbox.length>0)
	{
		for(i=0;i < document.form_give_gold.checkbox.length;i++)
		{
			document.form_give_gold.checkbox[i].checked=false;
		}
	}
	else
	{
		document.form_give_gold.checkbox[i].checked=false;
	}
}
function check_checkbox()
{
	var del= confirm('Xóa ?');
	if(del==true)
	{
		var isChecked=false;
		if (document.form_give_gold.checkbox.length>0)
		{
			for(var i=0;i<document.form_give_gold.checkbox.length;i++)
			{
				if(document.form_give_gold.checkbox[i].checked==true)
				{
					isChecked=true;
				}
			}				
		}
		else
		{
			if(document.form_give_gold.checkbox.checked==true)
			{
				isChecked=true;
			}
		}
		if (!isChecked)
		{											
			alert("Chưa chọn tin để xóa");
		}
		return isChecked;
	}
	else
	{
		return false;
	}
}	 
function TimKiem()
{
	keyword = document.getElementById('txtUsername').value;
	if(keyword !='')
	{
		document.location.href="plus.php?keyword="+keyword;	
		return false;		
	}
	document.location.href="plus.php";
	return false;	
} 
</script>
<div id="column-content">
  <div id="content">
    <p class=\"txt_menue\"><a href="plus.php">{Give gold}</a> | <a href="plus.php?s=1">{Special}</a> | <span style="background:rgb(0, 153, 255);"><a href="plus.php?s=2">{Gold log}</a></span></p>
    <form method="POST" name="form_give_gold">
     <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
        <tbody>
			<!--<tr>
			<td colspan="4" align="center">Keywords&nbsp;&nbsp;:
			  <input class="fm f80" type="text" id="txtUsername" name="txtUsername" value="{value_name}" size="20" maxlength="20">&nbsp;&nbsp;<input type="button" name="submit_player" value="{Search}" class="fm" onClick="TimKiem()"/></td>
			</tr>-->
          <tr class="rbg" height="25">
		  	<th width="5%"></th>
            <th width="10%">{No}</th>
            <th width="24%">{Date}</th>
            <th width="50%">{Content}</th>  
            <th width="11%">So luong</th>            
          </tr>
        {view_gold_log_list}
		<tr>
			<td colspan="5"><p><input name="check_all" class="fm" type="button" value="{Select ALL}" onClick="return checkall();">&nbsp;&nbsp;<input name="un_check_all" class="fm" type="button" value="{Un Select ALL}" onClick="uncheckall()">&nbsp;&nbsp;<input name="delete" type="submit" value="{Delete}" class="fm" onclick="return check_checkbox()"/>&nbsp;&nbsp;<input name="Group_by" type="submit" value="Group" class="fm"/>
                    <b>{Total} : {total_record}</b>&nbsp;&nbsp;&nbsp;&nbsp;{Page} {pagenumber} / {total_page}</td>
		</tr>
        </tbody>        
      </table>
    </form>
  </div>
</div>