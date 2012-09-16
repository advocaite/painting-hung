<script type="text/javascript">
function OpenWindowsNew($link)
{
		return window.open($link,'ViewTroop','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=700,height=450,top=200,left=150');
}
</script>
<form method="post" name="snd" action="build.php?id={id}">
<input name="id" value="30" type="hidden">
<input name="z" value="1011" type="hidden">
<input name="a" value="2" type="hidden">
<p></p><table class="tbg" cellpadding="2" cellspacing="1">
<tbody><tr class="cbg1">
<td>{title_column_name}</td><td>{title_column_number}</td><td>{title_column_max}</td>
</tr>
{rows}
</tbody></table>
<p><input value="ok" name="s1" src="images/en/b/b1.gif" onMouseDown="btm1('s1','','images/en/b/b2.gif',1)" onMouseOver="btm1('s1','','images/en/b/b3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()" border="0" type="image" width="80" height="20"></p>
</form>