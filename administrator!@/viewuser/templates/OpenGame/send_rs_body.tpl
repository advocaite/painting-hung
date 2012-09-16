<script language="JavaScript">
<!--
var haendler = {merchant_available};
var carry = {merchant_capacity};
//-->
</script><p>

<form method="POST" name="snd" action="build.php?id={id}">
<input type="hidden" name="id" value="29">
<table cellspacing="0" cellpadding="0" width="100%" valign="top">
<tr valign="top">

<td width="45%">
<table class="f10">
<tr>
<td><a href="#" onClick="upd_res(1,1); return false;"><img class="res" src="images/un/r/1.gif"></a></td>
<td>{Lumber}:</td><td align="right"><input class="fm" type="Text" name="r1" id="r1" value="{r1}" size="4" maxlength="5" onKeyUp="upd_res(1)" tabindex="1"></td>
<td class="s7 f8"><a href="#" onMouseUp="add_res(1);" onClick="return false;">({merchant_capacity})</a></td>
</tr>
<tr>
<td><a href="#" onClick="upd_res(2,1); return false;"><img class="res" src="images/un/r/2.gif"></a></td>
<td>{Clay}:</td><td align="right"><input class="fm" type="Text" name="r2" id="r2" value="{r2}" size="4" maxlength="5" onKeyUp="upd_res(2)" tabindex="2"></td>
<td class="s7 f8"><a href="#" onMouseUp="add_res(2);" onClick="return false;">({merchant_capacity})</a></td>

</tr>
<tr>
<td><a href="#" onClick="upd_res(3,1); return false;"><img class="res" src="images/un/r/3.gif"></a></td>
<td>{Iron}:</td><td align="right"><input class="fm" type="Text" name="r3" id="r3" value="{r3}" size="4" maxlength="5" onKeyUp="upd_res(3)" tabindex="3"></td><td class="s7 f8"><a href="#" onMouseUp="add_res(3);" onClick="return false;">(</a><a href="#" onMouseUp="add_res(3);" onClick="return false;">{merchant_capacity}</a><a href="#" onMouseUp="add_res(3);" onClick="return false;">)</a></td>
</tr>
<tr>
<td><a href="#" onClick="upd_res(4,1); return false;"><img class="res" src="images/un/r/4.gif"></a></td>
<td>{Crop}:</td><td align="right"><input class="fm" type="Text" name="r4" id="r4" value="{r4}" size="4" maxlength="5" onKeyUp="upd_res(4)" tabindex="4"></td>
<td class="s7 f8"><a href="#" onMouseUp="add_res(4);" onClick="return false;">(</a><a href="#" onMouseUp="add_res(4);" onClick="return false;">{merchant_capacity}</a><a href="#" onMouseUp="add_res(4);" onClick="return false;">)</a></td>

</tr>
</table>
</td><td width="55%" valign="top">

<table class="f10">
<tr>
  <td colspan="2">{Merchants} {merchant_available}/{sum_merchant}<br>
    <br></td></tr>

<tr>
  <td colspan="2"><span class="text135 b">{Village}:</span>

    <input class="fm" type="Text" name="dname" value="{village_name}" size="10" maxlength="20" tabindex="5"></td>
</tr>


<tr>
  <td colspan="2"><i>{or}</i></td>
</tr>

<tr>
<td colspan="2">
<span class="text135 b">

X:
<input class="fm" type="Text" name="x" value="{x}" size="2" maxlength="4" tabindex="6">
Y:
<input class="fm" type="Text" name="y" value="{y}" size="2" maxlength="4" tabindex="7">
</span></td>
</tr>

</table>

</td></tr>
</table><p><input type="image" value="ok" border="0" name="s1" src="images/en/b/ok1.gif" width="50" height="20" onMousedown="btm1('s1','','images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()" tabindex="8"></input></form></p><script language="JavaScript" type="text/javascript">
//<!--
document.snd.r1.focus();
//-->
</script>
{error}
<p>{Each of your merchants can carry} <b>{merchant_capacity} </b> {resources}.</p>
<p>{send_rs_status}</p>