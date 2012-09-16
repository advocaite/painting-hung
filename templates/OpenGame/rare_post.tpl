<table class='f10' border="0" cellpadding="0" cellspacing="0">
<tr valign="top">
<td>
{tbl_list}
</td>

<td style="padding-left:30px" valign="top">

<table class='f10' cellpadding="2" cellspacing="1" align="left">
<tr>
<td colspan="2" class="b">{chuyen_bau_vat}</td>
</tr>
<form method="post">
<input type="hidden" name="send_rare" value="confirm" />
<tr>
<td>{bau_vat}</td>
<td> <select class="fm" name="opt_rare">   
 	{rare_list} 
 	</select> </td>
</tr>

<tr>
<td> {ten_thanh}</td>
<td> <input type="text" class="fm" name="village_name" size="10" value="{village_name}"/> </td>
</tr>

<tr>
<td> {hoac_toa_do}</td>
<td><span class="text135 b"> X: <input type="text" name="_x" class="fm" maxlength="4"  size="4" value="{_x}"/>   Y: <input class="fm" type="text" maxlength="4" name="_y"  size="4" value="{_y}"/> </span></td>
</tr>
<tr><td colspan="2" class="c5 b">{error_rare}</td></tr>
	
<tr>
<td>&nbsp;</td>
<td> 
<input type="image" name="imgConfirm" src="images/en/b/snd1.gif" />
</td>
</tr>
</form>
</table>
</td>
</tr>
</table>
<p>{dk_xaydung_kydai}</p>