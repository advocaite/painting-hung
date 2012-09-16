<form method="POST" action="build.php?id={id}&t={task}">
<p><table width="554">
<tr>
<td width="18%">{Target}:</td>
<td width="82%" class="s7"><a href="village_map.php?a={village_attack_x}&b={village_attack_y}"><b>{village_attack_name}</b> (<b>{village_attack_x}</b>|<b>{village_attack_y}</b>)</a></td>
</tr>
<tr>
<td width="18%">{Player}:</td>
<td class="s7"><a href="profile.php?uid={uid}"><b>{player_attack_name}</b></a></td>
</tr>
</table></p>

<p>
<table  cellspacing="1" cellpadding="1" class="tbg">

<tr class="cbg1">
<td width="21%"><b>&nbsp;{village_name}</b></td>
<td colspan="12"><b>{send_type_string} {village_defend_name}</b></td>
</tr>
<tr class="unit">
<td>&nbsp;</td><td><img src="{icon1}" title="{title1}"></td><td><img src="{icon2}" title="{title2}"></td><td><img src="{icon3}" title="{title3}"></td><td><img src="{icon4}" title="{title4}"></td><td><img src="{icon5}" title="{title5}"></td><td><img src="{icon6}" title="{title6}"></td><td><img src="{icon7}" title="{title7}"></td><td><img src="{icon8}" title="{title8}"></td><td><img src="{icon9}" title="{title9}"></td>
<td><img src="{icon10}" title="{title10}"></td>
<td><img src="{icon11}" title="{title11}"></td>
<td><img src="images/icon/hero4.ico" title="{title12}" /></td>
</tr><tr>
<td>{Troops}</td>
<td class="{class1}">{t1}</td>
  <td class="{class2}">{t2}</td>
  <td class="{class3}">{t3}</td>
  <td class="{class4}">{t4}</td>
  <td class="{class5}">{t5}</td>
  <td class="{class6}">{t6}</td>
  <td class="{class7}">{t7}</td>
  <td class="{class8}">{t8}</td>
  <td class="{class9}">{t9}</td>
  <td class="{class10}">{t10}</td>
  <td class="{class11}">{t11}</td>
  <td class="{class12}">{t12}</td>
</tr>

<tr class="cbg1"><td>{Arrival}</td><td colspan="12">
<table width="89%" cellpadding="0" cellspacing="0" class="tbg">
<tr>
<td width="50%">{in} {duration} {hrs}</td>
<td width="50%">{at} <span id=tp2>{time_at}</span><span> {o_clock}</span> <span class="c">{date_at}</span></td>
</tr></table></td></tr>
</table>

<input type="hidden" name="aid" value="{attack_id}">

<p><input type="image" value="ok" border="0" name="s1" src="images/en/b/ok1.gif" width="50" height="20" onMousedown="btm1('s1','','images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()"></input></form></p>