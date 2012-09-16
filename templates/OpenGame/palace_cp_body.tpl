{text_culture_point}
<table width="100%" cellspacing="4" cellpadding="0" class="f10">
<tr>
<td width="250">{Production of this village}:</td>
<td><b>{village_point}</b> {point_per_day}</td>
</tr>

<tr>
<td width="250">{Production of all villages}:</td>
<td><b>{villages_point}</b> {point_per_day}</td>
</tr>
</table>
<p>{villages_have_produced} <b>{total_point}</b> {points_to_new_village} <b>{next_village_point}</b> {points}.
<br />
<form action="build.php?id={id}&t=1" method="post">
<p>
	<p>
		&raquo;&raquo;Góp điểm danh vọng cho liên minh:
	</p>
	<p>
		Số điểm: <input name="input_diemDV" type="text" value="{total_point}" class="fm" onkeyup="CheckNumberInput(this);" /> <input type="submit" value="Đồng ý" />
	</p>
</p>
</form>
<p style="color:#FF0000">{error}</p>