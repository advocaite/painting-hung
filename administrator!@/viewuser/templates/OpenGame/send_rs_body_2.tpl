<form method="POST" name="snd" action="build.php?id={id}">

<input type="hidden" name="id" value="25">
<input type="hidden" name="a" value="97001">
<input type="hidden" name="sz" value="131">
<input type="hidden" name="vtid" value="{village_to_id}">
<table cellspacing="0" cellpadding="0" width="100%" valign="top">
<tr valign="top">

<td width="45%">
<table class="f10">
<tr>
<td><img class="res" src="images/un/r/1.gif"></td>
<td>{Lumber}:</td><td align="right"><input class="fm" type="Text" name="r1" value="{r1}" size="4" readonly></td>
<td class="s7 f8 c b">(<a href="#" onmouseup="add_res(1);" onclick="return false;">{merchant_capacity}</a>)</td>
</tr>
<tr>
<td><img class="res" src="images/un/r/2.gif"></td>

<td>{Clay}:</td><td align="right"><input class="fm" type="Text" name="r2" value="{r2}" size="4" readonly></td><td class="s7 f8 c b">(<a href="#" onmouseup="add_res(1);" onclick="return false;">{merchant_capacity}</a>)</td>
</tr>
<tr>
<td><img class="res" src="images/un/r/3.gif"></td>
<td>{Iron}:</td><td align="right"><input class="fm" type="Text" name="r3" value="{r3}" size="4" readonly></td><td class="s7 f8 c b">(<a href="#" onmouseup="add_res(1);" onclick="return false;">{merchant_capacity}</a>)</td>
</tr>
<tr>
<td><img class="res" src="images/un/r/4.gif"></td>
<td>{Crop}:</td><td align="right"><input class="fm" type="Text" name="r4" value="{r4}" size="4" readonly></td><td class="s7 f8 c b">(<a href="#" onmouseup="add_res(1);" onclick="return false;">{merchant_capacity}</a>)</td>

</tr>

</table>
</td><td width="55%" valign="top">
<p class="text135">{village_name} ({x}|{y})</p>
<table>
<tr class="left">
<td>{Player}:</td>
<td><a href="profile.php?uid={uid}">{player_name}</a></td>
</tr>
<tr class="left">
<td>{Duration}:</td>
<td>{duration}</td>
</tr>
<tr class="left">
<td>{Merchants}:</td>
<td>{sum_merchant_require}</td>
</tr>
</table>

</td></tr></table><p><input type="image" value="ok" border="0" name="s1" src="images/en/b/ok1.gif" width="50" height="20" onMousedown="btm1('s1','','images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()"></input></p>
<p>{send_rs_status}</p>