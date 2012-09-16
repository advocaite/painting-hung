<script type="text/javascript">
function checkall()
{
	for(i=0;i <=document.from.length;i++)
	{
		document.from.checkbox[i].checked=true;
	}
}
function uncheckall()
{
	for (i = 0; i < document.from.checkbox.length; i++)
	{
		document.from.checkbox[i].checked = false ;
	}
}
function check_checkbox()
{
		var string1=document.getElementById("content1").value;
		var string2=document.getElementById("content2").value;
		//var del=false;// confirm(string1);
		if(del==true)
		{
			var isChecked=false;
			if (document.from.checkbox.length>0)
			{
				for(var i=0;i<document.from.checkbox.length;i++)
					if(document.from.checkbox[i].checked==true)
					{
						isChecked=true;
					}
					
			}
			else
			{
				if(document.from.checkbox.checked==true)
			{
			isChecked=true;
		}
	}
	if (!isChecked)
	{											
		alert(string2);
	}
	return isChecked;
	}
	else
	{
		return false;
	}
} 		  
</script>
<form name="from" method="post" action="" >
<input type="hidden" id="content1" value="{content1}">
<input type="hidden" id="content2" value="{content2}">
<table width="100%" cellspacing="1" cellpadding="2" class="tbg">
<tr class="rbg">
	<td width="29" title="{30}"><input type="hidden" id="checkbox" /></td>
	<td width="390">{Subject}</td>
	<td width="233">{Sender}</td>
	<td width="300">{Time}</td>
</tr>
	{list}
<tr>
	<td>&nbsp;</td>	
	<td class="s7" colspan="2">
	<img name="CheckAll" src="images/en/b/s_all.gif" type="image" onClick="checkall()" style="cursor:pointer;">
	<img name="UnCheckAll" src="images/en/b/d_all.gif" type="image" onClick="uncheckall()" style="cursor:pointer;">
	<input type="hidden" name="delete" style="visibility:hidden;"/>
	<input type="image" name="delete" src="images/en/b/del.gif" style="cursor:pointer;" ondblclick="return check_checkbox()">
	</td>
	<td align="center">{pagenumber}</td>
</tr>
</table>
</form>
</div>
</div>
</div>