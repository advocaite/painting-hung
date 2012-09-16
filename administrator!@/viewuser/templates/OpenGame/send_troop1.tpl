<script src="js/unx.js" type="text/javascript"></script>
<div id="box1">
<div id="box2"><h1>{type} {name_target}</h1>
 <form method="post">
<p><table>
<tr>
  <td width="33%">{Target}:</td>
  <td width="67%" class="s7"><a href="village_map.php?a={x}&b={y}">{name_target} ({x_target}|{y_target})</a></td>
</tr>
<tr>
  <td width="33%">{Player}:</td>
  <td class="s7"><a href="profile.php?uid={uid}">{username_target}</a></td>
</tr>
</table>
</p>
<p><table class="tbg" cellpadding="1" cellspacing="1">
<tr class="cbg1">
<td width="21%"><b>&nbsp;{name}</b></td>
<td colspan="11"><b>{type}  {name_target}</b></td>
</tr>
<tr class="unit">
<td>&nbsp;</td>
<td><img src="{img_troop1}" title="{name_troop1}"></td>
<td><img src="{img_troop2}" title="{name_troop2}"></td>
<td><img src="{img_troop3}" title="{name_troop3}"></td>
<td><img src="{img_troop4}" title="{name_troop4}"></td>
<td><img src="{img_troop5}" title="{name_troop5}"></td>
<td><img src="{img_troop6}" title="{name_troop6}"></td>
<td><img src="{img_troop7}" title="{name_troop7}"></td>
<td><img src="{img_troop8}" title="{name_troop8}"></td>
<td><img src="{img_troop9}" title="{name_troop9}"></td>
<td><img src="{img_troop10}" title="{name_troop10}"></td>
<td><img src="{img_troop11}" title="{name_troop11}"></td>

</tr><tr>
<td>{Troops}<br /></td>
<td>{1_}</td>
<td>{2_}</td>
<td>{3_}</td>
<td>{4_}</td>
<td><span>{5_}</span></td>
<td><span>{6_}</span></td>
<td>{7_}</td>
<td>{8_}</td>
<td>{9_}</td>
<td>{10_}</td>
<td>{11_}</td>
</tr>

<tr class="cbg1">
  <td>{Arrival}</td>
  <td colspan="11">
<table class="tbg" cellpadding="0" cellspacing="0">
<tr>
<td width="50%">{in} {cost_time} {hrs}</td>
<!--<td width="50%">at {end_time}<span>o'clock</span></td>
<td width="50%">at <span id="tp2">59:05:55</span><span> o'clock</span></td>-->
<td width="50%">{at} <span id="tp2">{end_time}</span><span> {clock}</span></td>
</tr></table></td></tr>
</table>


<input name="id" value="{id}" type="hidden">
<input name="a" value="" type="hidden">

<input name="c_" value="{c_}" type="hidden">
<input name="duration" value="{duration}" type="hidden">
<input name="kid" value="426711" type="hidden">

<input name="t1" value="{1_}" type="hidden">
<input name="t2" value="{2_}" type="hidden">
<input name="t3" value="{3_}" type="hidden">
<input name="t4" value="{4_}" type="hidden">
<input name="t5" value="{5_}" type="hidden">
<input name="t6" value="{6_}" type="hidden">
<input name="t7" value="{7_}" type="hidden">
<input name="t8" value="{8_}" type="hidden">
<input name="t9" value="{9_}" type="hidden">
<input name="t10" value="{10_}" type="hidden">
<input name="t11" value="{11_}" type="hidden">
</p>
<p><input value="ok" name="s1" src="images/en/b/ok1.gif" onMouseDown="btm1('s1','','images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()" border="0" type="image" width="50" height="20"></p></form>
</div>
</div></div>