<form action="build.php?id={id}&t=1" method="POST">
<table cellspacing="1" cellpadding="2" class="tbg">
	<tr>
	<td class="rbg" colspan="4">{thong_tin_cung_menh}</td>
	</tr>	
	<tr>
	<td width="50%" colspan="3">{chi_tiet}</td>
	<td width="50%">{ngu_hanh}</td>
	</tr>	
	<tr>
    <td colspan="3"></td><td></td></tr>
	<tr>
	<td class="s7">&nbsp;</td>
	<td class="s7"><input type="Radio" name="cung" value="1"  {checked_1}></td>
	<td class="s7">Kim</td>
	<td rowspan="10"><img src="{image}"></td>
	</tr>
	<tr class="s7"><td>&nbsp;</td>
	  <td><input type="Radio" name="cung" value="2" {checked_2}></td>
	  <td>Thủy</td>
	</tr>	
	<tr class="s7">
	  <td>&nbsp;</td>
	  <td><input type="Radio" name="cung" value="3" {checked_3}></td>
	  <td>Mộc</td>
	  </tr>
	<tr class="s7">
	  <td>&nbsp;</td>
	  <td><input type="Radio" name="cung" value="4"  {checked_4}></td>
	  <td>Hỏa</td>
	  </tr>
	<tr class="s7">
	  <td>&nbsp;</td>
	  <td><input type="Radio" name="cung" value="5"  {checked_5}></td>
	  <td>Thổ</td>
	  </tr>
	</table>
 <div>{asu_cung_menh} <strong>[{asu_need}]</strong> {Asu}</div>
<p align="center"><input type="image" value="" border="0" name="s1" src="images/vn/b/s1.gif" width="80" height="20" onMousedown="btm1('s1','','images/vn/b/s1.gif',1)" onMouseover="btm1('s1','','images/vn/b/s1.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()"></input>
</form>