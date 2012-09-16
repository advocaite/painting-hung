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
	var del= confirm('Tặng Asu cho gamer ?');
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
			alert("Chưa chọn gamer để tặng Asu");
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
      <p class=\"txt_menue\"><span style="background:#0099FF"><a href="plus.php">{Give gold}</a></span> | <a href="plus.php?s=1">{Special}</a> | <a href="plus.php?s=2">{Gold log}</a></p>
        <form method="POST" name="form_give_gold" action="">
            <table align="center">
                <tr>
                    <td>{Keywords}</td>
                    <td><input class="fm f80" type="text" id="txtUsername" name="txtUsername" value="{value_name}" size="20" maxlength="20">
                    </td>
                    <td><input type="button" name="submit_player" value="{Search}" class="fm" onClick="TimKiem()"/></td>
                </tr>
            </table>
            <table cellspacing="1" cellpadding="2" class="tbg" width="100%" >
            	 <tr class="rbg">
                    <th width="6%"></th>
                    <th width="6%">{No}</th>
                    <th >{Username}</th>                 
					<th width="10%">{Lumbers}</th>
					<th width="10%">{Clays}</th>
					<th width="10%">{Irons}</th>
					<th width="10%">{Crops}</th>
                    <th width="10%">{Attacks}</th>
					<th width="10%">{Defences}</th>
					<th width="10%">{Builds}</th>
					<th width="7%">{Tb1s}</th>
					<th width="7%">{Tb2s}</th>
					<th width="7%">{Tb3s}</th>
					<th width="7%">{Smss}</th>
                    <th width="5%">{Asus}</th>                    
                </tr>
                {view_users_list}                
            </table>
            <table width="100%">
                <tr>
                    <td width="97"><input name="check_all" class="fm" type="button" value="{Select ALL}" onClick="return checkall();">
                    </td>
                    <td width="161"><input name="un_check_all" class="fm" type="button" value="{Un Select ALL}" onClick="uncheckall()">
                    </td>
                    <td> {Gold} </td>
                    <td width="78"><input type="text" name="txt_gold" class="fm"  />
                    </td>
                    <td width="133"><input type="submit" name="give_gold" value="{give}" class="fm" onclick="return check_checkbox();" />
                    </td>
                    <td width="167"><b>{Total}: {total_record}</b></td>
                    <td width="134">{Page}{pagenumber} / {total_page}</td>
                </tr>
            </table>           
        </form>
    </div>
</div>
 