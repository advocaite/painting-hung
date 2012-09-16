<form method="post" action="build.php?id={id}&t=2">
<input name="id" value="25" type="hidden">
<input name="t" value="2" type="hidden">
<input name="a" value="4" type="hidden">
<table class="f10">
<tbody><tr>
<td>{Offering}</td>
<td><input class="fm" name="m1" value="" size="4" maxlength="6"></td>
<td>
<select name="rid1" size="" class="fm">
<option value="1" selected="selected">{Lumber}</option>
<option value="2">{Clay}</option>
<option value="3">{Iron}</option>
<option value="4">{Crop}</option>
</select>
</td>
<td>&nbsp;</td>
<td><input name="mt1" value="1" type="checkbox"> 
{Max time of transport}: 
  <input class="fm fm25" name="mt2" value="2" maxlength="2"> 
  {hours}</td>
</tr>

<tr>
<td>{Searching}</td>
<td><input class="fm" name="m2" value="" size="4" maxlength="6"></td>
<td>
<select name="rid2" size="" class="fm">
<option value="1">{Lumber}</option>
<option value="2" selected="selected">{Clay}</option>
<option value="3">{Iron}</option>
<option value="4">{Crop}</option>
</select>
</td>

<td>&nbsp;</td><td></td>
</tr>
</tbody></table>
<p class="f10">{Merchants}: {merchant_available}/{sum_merchant}</p>
<p class="f10">{error_message}</p>
<p><input value="ok" name="s1" src="images/en/b/ok1.gif" onmousedown="btm1('s1','','images/en/b/ok2.gif',1)" onmouseover="btm1('s1','','images/en/b/ok3.gif',1)" onmouseup="btm0()" onmouseout="btm0()" border="0" type="image" width="50" height="20"></p><img src="images/un/a/x.gif"></form>
{offer_status}
