<form method="POST" name="snd" action="javascript:void(0)">
<input type="hidden" name="id" id="id" value="{id}">
<input type="hidden" name="st" id="st" value="{st}">
<input type="hidden" name="vl" id="vl" value="{vl}">

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td><img class="unit" src="{icon1}" title="{title1}" border="0" onClick="document.snd.t1.value=''; return false;"></td><td><input class="fm" type="Text" name="t1" id="t1" value="{t1}" size="2" maxlength="6"></td>
<td {class1}>{sum1}</td><td><img class="unit" src="{icon4}" title="{title4}"></td>
<td><input class="fm" type="Text" name="t4" id="t4" value="{t4}" size="2" maxlength="6"></td>
<td {class4}>{sum4}</b></td>
<td><img class="unit" src="{icon7}" title="{title7}" border="0" onClick="document.snd.t7.value=''; return false;"></td>
<td><input class="fm" type="Text" name="t7" id="t7" value="{t7}" size="2" maxlength="6"></td>
<td {class7}>{sum7}</td>
</tr>


<tr>
<td><img class="unit" src="{icon2}" title="{title2}"></td><td><input class="fm" type="Text" id="t2" value="{t2}" size="2" maxlength="6"></td>
<td {class2}>{sum2}</td>
<td><img class="unit" src="{icon5}" title="{title5}" border="0" onClick="document.snd.t5.value=''; return false;"></td>
<td><input class="fm" type="Text" name="t5" id="t5" value="{t5}" size="2" maxlength="6"></td>
<td {class5}>{sum5}</td>
<td><img src="../images/icon/hero4.ico" alt="" title="Tu?ng" /></td>
<td><input class="fm" type="text" name="t12" id="t12" value="{t12}" size="2" maxlength="6" /></td>
<td {class12}>{sum12}</td>
</tr>


<tr>
<td><img class="unit" src="{icon3}" title="{title3}" border="0" onClick="document.snd.t3.value=''; return false;"></td>
<td><input class="fm" type="Text" id="t3" value="{t3}" size="2" maxlength="6"></td>
<td {class3}>{sum3}</td>
<td><img class="unit" src="{icon6}" title="{title6}" border="0" onClick="document.snd.t6.value=''; return false;"></td>
<td><input class="fm" type="Text" name="t6" id="t6" value="{t6}" size="2" maxlength="6"></td>
<td {class6}>{sum6}</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
<br />
<input type="image" value="ok" border="0" name="s1" src="../images/en/b/ok1.gif" width="50" height="20" onMousedown="btm1('s1','','../images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','../images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()" onclick="submitSendTroop('popup_div', {id}, 1, {vl}); return false;" ></input>
{error_message}
</form>