<div id="box1"><div id="box3">
<div class="nb">{images1}
<div id="text" align="justify">{div_content}</div>
</div>
{images2}
<p class="text9">{content}</p>
<script language="javascript">
function Text(id2)
{
	document.getElementById("text").innerHTML =document.getElementById("div_"+id2).value;	
}
function checkForm()
{
	var from = document.formular;
	var string1=document.getElementById("content1").value;
	var string2=document.getElementById("content2").value;
	if(from.username.value=="")
	{
		alert(string1);
		from.username.focus();
		return false;
	}	
	if(from.codes.value=="")
	{
		alert(string2);
		from.codes.focus();
		return false;
	}
	from.submit();
	return true;
}
</script>
<form name="formular" action="active_user.php" method="post" onsubmit="checkForm(); return false;">
<input type="hidden" name="active" value="{active}" />
<fieldset>
<table width="100%" cellpadding="1" cellspacing="2" class="">
            <tr>
					<td colspan="2">{images3}</td>
					<td colspan="4">{images4}</td>
            </tr>
<tr>
					<td width="25"><input type="Radio" name="vid" id="vid1" value="1" onFocus="Text('vid1');" {vid1} tabindex="0" ></td>
					<td width="150" class="f8">{Arabia}</td>
		  <td width="25"><input type="Radio" name="kid" id="kid" value="0" onFocus="Text('kid');" {kid0} tabindex="4"></td>
					<td width="80" class="f8">{Random}</td>					
					<td width="25"><input type="Radio" name="kid" id="kid1" value="1" onFocus="Text('kid1');" {kid1} tabindex="5"></td>
					<td class="f8">{Zone 1}</td>
		</tr>
	
<tr>
					<td> <input type="Radio" name="vid" id="vid2" value="2" onFocus="Text('vid2');" {vid2} tabindex="2"></td>
					<td class="f8">{Mongo}</td>
		  <td> <input type="Radio" name="kid" id="kid2" value="2" onFocus="Text('kid2');" {kid2} tabindex="6"></td>
					<td class="f8">{Zone 2}</td>
					<td> <input type="radio" name="kid" id="kid3" value="3" onFocus="Text('kid3');" {kid3} tabindex="7"/></td>
					<td class="f8">{Zone 3}</td>
		</tr>
		<tr>
		  <td>   <input type="Radio" name="vid" id="vid3" value="3" onFocus="Text('vid3');" {vid3} tabindex="3"></td>
					<td class="f8">{Sunda}</td>
		  <td>   <input type="radio" name="kid" id="kid4" value="4" onFocus="Text('kid4');" {kid4} tabindex="8"/></td>
					<td class="f8">{Zone 4}</td>
					<td>   <input type="radio" name="kid" id="kid5" value="5" onFocus="Text('kid5');" {kid5} tabindex="9"/></td>
					<td class="f8">{Zone 5}</td>
		</tr>
	
</table>
</fieldset>
<p class="f10">{content3}</p>
<fieldset>
<input type="hidden" name="div_vid1" id="div_vid1" value="{div_vid1}" /> 
<input type="hidden" name="div_vid2" id="div_vid2" value="{div_vid2}" />
<input type="hidden" name="div_vid3" id="div_vid3" value="{div_vid3}" />
<input type="hidden" name="div_kid" id="div_kid" value="{div_kid}" />
<input type="hidden" name="div_kid1" id="div_kid1" value="{div_kida}<strong>{postiton1} %</strong>{div_kid1}" />
<input type="hidden" name="div_kid2" id="div_kid2" value="{div_kida}<strong>{postiton2} %</strong>{div_kid2}" />
<input type="hidden" name="div_kid3" id="div_kid3" value="{div_kida}<strong>{postiton3} %</strong>{div_kid3}" />
<input type="hidden" name="div_kid4" id="div_kid4" value="{div_kida}<strong>{postiton4} %</strong>{div_kid4}" />
<input type="hidden" name="div_kid5" id="div_kid5" value="{div_kida}<strong>{postiton5} %</strong>{div_kid5}" />
<input type="hidden" id="content1" value="{input1}">
<input type="hidden" id="content2" value="{input2}">
<table width="100%" class="f10" cellpadding="0" cellspacing="0">
<tbody>
<tr>
	<td colspan="2" align="left"><p class="e text9">{error}</p></td>
</tr>
<tr valign="top">
	<td width="21%" height="30"><span class="b">{username}:</span></td>
	<td width="79%"><input class="fm f110" name="username" maxlength="20" type="text" onmousemove="style.background='yellow';" onmouseout="style.background='white';" value="{value_username}" tabindex="10"></td>
</tr>
<tr>
	<td><span class="b">{code}:</span></td>
	<td><input class="fm f110" name="codes" maxlength="50" type="text" onmousemove="style.background='yellow';" onmouseout="style.background='white';" value="{value_code}" tabindex="11"></td>
</tr>
<tr>
	<td height="30"></td>
	<td><input value="ok" name="s1" src="images/en/b/ok1.gif" onmousedown="btm1('s1','','images/en/b/ok2.gif',1)" onmouseover="btm1('s1','','images/en/b/ok3.gif',1)" onmouseup="btm0()" onmouseout="btm0()" type="image" width="50" border="0" height="20" />
	</td>
</tr>
</tbody></table>
</form>
</fieldset>
</div></div></div></div>