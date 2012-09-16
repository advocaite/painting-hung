<form method="POST" action="#">
<table  cellspacing="0" cellpadding="0" width="100%" align="center">

<tr>
<td><b>&nbsp;{village_name}</b></td>
<td colspan="12"><b>{dieu_quan} {to} {target}</b></td>
</tr>
<tr>
<td>&nbsp;</td><td><img src="{icon1}" title="{title1}"></td><td><img src="{icon2}" title="{title2}"></td><td><img src="{icon3}" title="{title3}"></td><td><img src="{icon4}" title="{title4}"></td><td><img src="{icon5}" title="{title5}"></td><td><img src="{icon6}" title="{title6}"></td><td><img src="{icon7}" title="{title7}"></td>
<td><img src="../images/icon/hero4.ico" title="{title12}" /></td>
</tr><tr>
<td>{Troops}</td>
<td {class1}>{t1}</td>
  <td {class2}>{t2}</td>
  <td {class3}>{t3}</td>
  <td {class4}>{t4}</td>
  <td {class5}>{t5}</td>
  <td {class6}>{t6}</td>
  <td {class7}>{t7}</td>
  <td {class12}>{t12}</td>
</tr>

<tr><td>{Arrival}</td><td colspan="12" style="text-align:left;">
{in} {duration} {hrs} {at} <span id=tp1>{time_at}</span><span> {o_clock}</span> <span class="c">{date_at}</span>
</td></tr>
</table>
<br />
<input type="hidden" name="id" value="{id}">
<input type="hidden" name="st" value="{st}">
<input type="hidden" name="vl" value="{vl}">
<input type="hidden" name="tg" id="tg" value="{tg}">

<input type="hidden" name="cf" value="1">

<input type="hidden" name="t1" id="t1" value="{t1}">
<input type="hidden" name="t2" id="t2" value="{t2}">
<input type="hidden" name="t3" id="t3" value="{t3}">
<input type="hidden" name="t4" id="t4" value="{t4}">
<input type="hidden" name="t5" id="t5" value="{t5}">
<input type="hidden" name="t6" id="t6" value="{t6}">
<input type="hidden" name="t7" id="t7" value="{t7}">
<input type="hidden" name="t8" id="t8" value="{t8}">
<input type="hidden" name="t9" id="t9" value="{t9}">
<input type="hidden" name="t10" id="t10" value="{t10}">
<input type="hidden" name="t11" id="t11" value="{t11}">
<input type="hidden" name="t12" id="t12" value="{t12}">

<input type="image" value="ok" border="0" name="s1" src="../images/en/b/ok1.gif" width="50" height="20" onMousedown="btm1('s1','','../images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','../images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()" onclick="confirmSendTroop('popup_div', {id}, {st}, {vl}); return false;"></input></form>