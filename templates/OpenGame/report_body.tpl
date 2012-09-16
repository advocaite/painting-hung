<div id="box1"><div id="box2"><h1>{Reports}</h1>
<p class="title_menu">
	<span {class}><a href="report.php" >{All}</a></span> |	
	<span {class1}><a href="report.php?tab={REPORT_ATTACK}">{Attacks}</a></span> |
	<span {class2}><a href="report.php?tab={REPORT_DEFEND}">{Reinforcement}</a></span> |
    <span {class3}><a href="report.php?tab={REPORT_TRADE}">{Trade}</a></span>
</p>
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
			{
				if(document.from.checkbox[i].checked==true)
				{
					isChecked=true;
				}
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
<form name="from" method="post" action="" style="display: inline;">
<input type="hidden" id="content1" value="{content1}">
<input type="hidden" id="content2" value="{content2}">
<table cellspacing="1" cellpadding="2" class="tbg" width="800"> 
<tr class="rbg"><td width="50" align="center">{sum}<input id="checkbox" type="hidden"></td>
<td width="510">{Title}</td>
<td width="290" >{Time}</td>
</tr>
{list}
<tr>
<td class="s7" colspan="2">
	<img name="CheckAll" src="images/en/b/s_all.gif" type="image" onClick="checkall()" style="cursor:pointer;">
	<img name="UnCheckAll" src="images/en/b/d_all.gif" type="image" onClick="uncheckall()" style="cursor:pointer;">
	<input type="hidden" name="delete" style="visibility:hidden;"/>
	<input type="image" name="delete" src="images/en/b/del.gif" style="cursor:pointer;" ondblclick="return check_checkbox()">
</td>
<td class="r7">{pagenumber}</td>
</tr>
<tr><td colspan="3"></td></tr>
</table>
</form>
</div>
</div>
</div>