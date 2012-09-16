<tr>
  <td align="center">{no}</td>
  <td align="center"><a title="{view_info_user} {username}" href="#{username}" onclick="return PopUpSong('{username}||TranhHung');">{username}</a></td>
  <td align="center">{email}</td>
  <td align="center"><select name="authlevel"  onchange="javscript:window.open('user_manager.php?username={username}&authlevel='+this.options[this.selectedIndex].value,'_top')">{authlevel}</td>
</tr>

