<form name="frm_player_profile" action="" method="post" >
<table cellspacing="1" cellpadding="2" class="tbg">
	<tr>
		<td class="rbg" colspan="3">Player {sender}</td>
	</tr>
	<tr>
		<td width="50%" colspan="2">Details:</td>
		<td width="50%">Description:</td>
	</tr>	
	<tr>
		<td colspan="2"> </td><td></td>
	</tr>
	<tr>
		<td class="s7">Rank:</td><td class="s7">{rank}</td>
		<td rowspan="11" class="slr3">{description}<br /><img src="img/un/t/tnd.gif"/></td>
	
	</tr>
	<tr class="s7">
		<td>Tribe:</td>
		<td>{tribe}</td>
	</tr>
	<tr class="s7">
		<td>Alliance:</td>
		<td><a href="ally.php">{Lien ket den alliance}</a></td>
	</tr>
	<tr class="s7">
		<td>Villages:</td>
		<td>{villages_id}</td></tr>
	<tr class="s7">
		<td>Population:</td>
		<td>{population}</td>
	</tr>
	<tr class="s7">
		<td>Age:</td>
		<td>{age}</td>
	</tr>
	<tr class="s7">
		<td>Gender:</td>
		<td>{gender}</td>
	</tr>
	<tr class="s7">
		<td>Location:</td>
		<td>{location} </td>
	</tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
	<tr class="s7">
		<td colspan="2"> <a href="messages.php?id_tab=1&id={msg_player_profile}">&raquo; Write message</a></td>
	</tr>
	<tr>
		<td colspan="2" class="slr3">^^ ^^</td>
	</tr>
	</table></p><p>
	<table cellspacing="1" cellpadding="2" class="tbg">	
	<tr>
		<td class="rbg" colspan="3">Villages:</td>
	</tr>	
	<tr>
		<td width="50%" > <font color="" >{Name} </font></td>
		<td width="25%"><font color="" >{Inhabitants}</font></td>
		<td width="25%"><font color="" >{Coordinates}</font></td>
	</tr>
	<tr>
		<td class="s7" >
		<font color="#FFFFFF" >
		<a href="map.php?d={toa do x }&c={toa do y }" style="display:block;float:left;">{name}</a> 
		<span style="display:block;float:left;" class="c">&nbsp;(Capital)</span></td>
		<td><font color="#FFFFFF" >{inhabitants}</font></td>
		<td><font color="#FFFFFF" >( {toado_x} | {toado_y} ) </font></td>
		</font>
	</tr>
	</table>
</table>
</div>
</body>
</form>
