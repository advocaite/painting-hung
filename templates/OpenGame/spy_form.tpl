<form method="POST" action="build.php?id={id}&t=1">
<p><table>
<tr><td width="11%">Target:</td>
<td class="s7"><a href="village_map.php?a={x}&b={y}">{village_defend_name} ({x}|{y})</a></td></tr>
<tr><td width="11%">Player:</td><td class="s7"><a href="profile.php?uid={uid}">{player_defend_name}</a></td></tr>
</table></p>

<p>
<table  cellspacing="1" cellpadding="1" class="tbg">

<tr class="cbg1">
<td width="21%"><b>&nbsp;{village_attack_name}</b></td>
<td colspan="12"><b>{Scouting} {village_defend_name}</b></td>
</tr>
<tr class="unit">
<td>&nbsp;</td><td><img src="{icon1}" title="{title1}"></td><td><img src="{icon2}" title="{title2}"></td><td><img src="{icon3}" title="{title3}"></td><td><img src="{icon4}" title="{title4}"></td><td><img src="{icon5}" title="{title5}"></td><td><img src="{icon6}" title="{title6}"></td><td><img src="{icon7}" title="{title7}"></td><td><img src="{icon8}" title="{title8}"></td><td><img src="{icon9}" title="{title9}"></td><td><img src="{icon10}" title="{title10}"></td>

<td><img src="{icon11}" title="{title11}"></td>
<td><img src="{icon12}" title="{title12}"></td>
</tr><tr>
  <td>{Troops}</td>
  <td class="c">0</td><td class="c">0</td><td class="c">0</td><td class="c">0</td><td class="{class5}">{t5}</td><td class="{class6}">{t6}</td><td class="{class7}">{t7}</td><td class="{class8}">{t8}</td><td class="{class9}">{t9}</td><td class="{class10}">{t10}</td>
  <td class="c">0</td>
  <td class="c">0</td>
</tr><tr>
    <td>{Options}</td>

    <td colspan="12">
<table cellspacing="0" cellpadding="0" class="tbg">
<tr class="s7">
<td width="5%"><input type="Radio" name="spy" value="1" checked></td>
<td width="95%">{Scout resources and troops}</td>
</tr><tr class="s7">
<td width="5%"><input type="Radio" name="spy" value="2"></td>
<td width="95%">{Scout defenses and troops}</td>
</tr></table></td></tr>
<tr class="cbg1">
  <td>{Arrival}</td>
  <td colspan="12">
<table cellspacing="0" cellpadding="0" class="tbg">
<tr>
<td width="50%">{in} {duration} {hrs}</td>

<td width="50%">{at} <span id=tp2>{time_at} </span><span> {o_clock} </span><span class="c">{date_at}</span></td>
</tr></table></td></tr>
</table>
</p>

<input type="hidden" name="st" value="{send_type}">
<input type="hidden" name="vdid" value="{village_defend_id}">

<input type="hidden" name="t1" value="{t1}">
<input type="hidden" name="t2" value="{t2}">
<input type="hidden" name="t3" value="{t3}">
<input type="hidden" name="t4" value="{t4}">
<input type="hidden" name="t5" value="{t5}">
<input type="hidden" name="t6" value="{t6}">
<input type="hidden" name="t7" value="{t7}">
<input type="hidden" name="t8" value="{t8}">
<input type="hidden" name="t9" value="{t9">
<input type="hidden" name="t10" value="{t10}">
<input type="hidden" name="t11" value="{t11}">

<p><input type="image" value="ok" border="0" name="s1" src="images/en/b/ok1.gif" width="50" height="20" onMousedown="btm1('s1','','img/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','img/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()"></input></form>