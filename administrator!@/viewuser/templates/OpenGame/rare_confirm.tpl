<form method="post">
<table cellspacing="1" cellpadding="2" class="tbg">
<input type="hidden" name="agree" value="sent" />
<input type="hidden" name="village_to_id" value="{village_to_id}" />
<input type="hidden" name="rare_kind" value="{rare_kind}" />
<input type="hidden" name="_x" value="{_x}" />
<input type="hidden" name="_y" value="{_y}" />
<input type="hidden" name="village_name" value="{village_name}" />
<tr class="cbg1">
<td width="30%" class="c1 b">{ben_gui_bau_vat}</td>
<td colspan="12">
<a href="profile.php?uid={id_user_sent_rare}">{name_user_sent_rare}</a> {tu_thanh}
<a href="village_map.php?a={x_user_sent_rare}&b={y_user_sent_rare}">{vname_user_sent_rare}</a>
</td>
</tr>
<tr class="cbg1">
<td width="30%" class="c1 b">{ben_nhan_bau_vat}</td>
<td colspan="12">
<a href="profile.php?uid={id_user_receive_rare}">{name_user_receive_rare}</a> {tu_thanh}
<a href="village_map.php?a={x_user_receive_rare}&b={y_user_receive_rare}">{vname_user_receive_rare}</a>
</td>
</tr>
<tr class="cbg1">
<td width="30%" class="c1 b">{thong_tin_bau_vat}</td>
<td colspan="12">
<img src="images/bauvat/{noi_dung_bau_vat_img}.jpg" title="{noi_dung_bau_vat_title}" />
</td>
</tr>
<tr class="cbg1">
<td width="30%" class="c1 b">{thoi_gian_thuc_hien}</td>
<td colspan="12">
 <span id="{class_rare_time_sending}">{total_rare_time}</span>   
 <a href="build.php?id=$_GET['id']&tab=1&cancel_rare={cancel_rare_time_sending}
 &nbsp;&nbsp;<img src="images/un/a/del.gif" width="12" height="12" title="{title_img_rare_time_sending}" boder="0"/>
</td>
</tr>
</table>

<table border="0">
<tr><td height="50px">
<input type="image" name="imgAgree" src="images/en/b/ok1.gif" />
</td>
</tr>
</table>
</form>