<tr>
    <td width="50" align="center"><input type="checkbox" id="checkbox" name="checkbox[]" value="{id}" onclick="count_unban();"></td>
    <td align="center">{no}</td>	
    <td align="center"><a title="Xem thông tin chi tiết tài khoản {username}" href="#{username}" onclick="return PopUpSong('{username}||TranhHung');">{username}</a></td>	
	<td align="center">{ban_date}</td>
	<td align="center"><select name="date" onchange="javscript:window.open('banned.php?{page}id={id}&update_day='+this.options[this.selectedIndex].value,'_top')">{ban_time}</select></td>
	<td align="center">{ban_end}</td>
	<td align="center" title="{reasons}">{reason}</td>		
</tr>

