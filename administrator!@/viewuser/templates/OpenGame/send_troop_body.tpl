<form method="POST" name="snd" action="build.php?id={id}&t=1">
<input type="hidden" name="b" value="1">
<p><table class="p1" style="width:100%"cellspacing="1" cellpadding="0"><tr><td>
<table width="100%" class="f10">
<tr>
<td width="20"><img class="unit" src="{icon1}" title="{title1}" border="0" onClick="document.snd.t1.value=''; return false;"></td><td width="35"><input class="fm" type="Text" name="t1" value="{t1}" size="2" maxlength="6"></td>
<td class="{class1}">{sum1}</td><td width="20"><img class="unit" src="{icon4}" title="{title4}"></td>
<td width="35"><input class="fm" type="Text" name="t4" value="{t4}" size="2" maxlength="6"></td>
<td class="{class4}">{sum4}</b></td><td width="20"><img class="unit" src="{icon7}" title="{title7}" border="0" onClick="document.snd.t7.value=''; return false;"></td><td width="35"><input class="fm" type="Text" name="t7" value="{t7}" size="2" maxlength="6"></td><td class="{class7}">{sum7}</td><td width="20"><img class="unit" src="{icon10}" title="{title10}" border="0"onClick="document.snd.t9.value=''; return false;"></td><td width="35"><input class="fm" type="Text" name="t10" value="{t10}" size="2" maxlength="6"></td>
<td class="{class10}">{sum10}</td>
</tr>


<tr>
<td width="20"><img class="unit" src="{icon2}" title="{title2}"></td><td width="35"><input class="fm" type="Text" name="t2" value="{t2}" size="2" maxlength="6"></td>
<td class="{class2}">{sum2}</td><td width="20"><img class="unit" src="{icon5}" title="{title5}" border="0" onClick="document.snd.t5.value=''; return false;"></td><td width="35"><input class="fm" type="Text" name="t5" value="{t5}" size="2" maxlength="6"></td>
<td class="{class5}">{sum5}</td><td width="20"><img class="unit" src="{icon8}" title="{title8}" border="0" onClick="document.snd.t8.value=''; return false;"></td><td width="35"><input class="fm" type="Text" name="t8" value="{t8}" size="2" maxlength="6"></td><td class="{class8}">{sum8}</td><td width="20"><img class="unit" src="{icon11}" title="{title11}"></td><td width="35"><input class="fm" type="Text" name="t11" value="{t11}" size="2" maxlength="6"></td><td class="{class11}">{sum11}</td></tr>


<tr>
<td width="20"><img class="unit" src="{icon3}" title="{title3}" border="0" onClick="document.snd.t3.value=''; return false;"></td><td width="35"><input class="fm" type="Text" name="t3" value="{t3}" size="2" maxlength="6"></td>
<td class="{class3}">{sum3}</td><td width="20"><img class="unit" src="{icon6}" title="{title6}" border="0" onClick="document.snd.t6.value=''; return false;"></td><td width="35"><input class="fm" type="Text" name="t6" value="{t6}" size="2" maxlength="6"></td><td class="{class6}">{sum6}</td><td><img class="unit" src="{icon9}" title="{title9}" border="0" onclick="document.snd.t8.value=''; return false;" /></td>
<td><input class="fm" type="Text" name="t9" value="{t9}" size="2" maxlength="6" /></td>
<td><span class="{class9}">{sum9}</span></td>
<td><img src="images/icon/hero4.ico" title="{hero}"></td>
<td><input class="fm" type="Text" name="t12" value="{t12}" size="2" maxlength="6" /></td>
<td><span class="{class12}">{sum12}</span></td>
</tr></table></td></tr></table></p>

<p><table width="100%" class="f10">
<tr><td valign="top" width="33%">
<div class="f10"><input type="Radio" name="st" value="2" {checked_2}> {Reinforcement}</div>
<div class="f10"><input type="Radio" name="st" value="3" {checked_3}> {Attack}: {Raid}</div>
<div class="f10"><input type="Radio" name="st" value="4" {checked_4}> {Attack}: {Normal}</div>
</td>

<td valign="top">
<div class="b text135">{Village}: <input class="fm" type="Text" name="vn" value="{vn}" size="10" maxlength="20"></div><div><i>{or}</i></div>

<div class="b text135">
X: <input class="fm" type="Text" name="x" value="{x}" size="2" maxlength="4">
Y: <input class="fm" type="Text" name="y" value="{y}" size="2" maxlength="4">
</div>

</tr>
</table>
<p><input type="image" value="ok" border="0" name="s1" src="images/en/b/ok1.gif" width="50" height="20" onMousedown="btm1('s1','','images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()"></input></p>
</p>
{error_message}
</form>