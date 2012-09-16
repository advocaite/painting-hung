<tr><td>
	<table class="f10" cellpadding="0" cellspacing="2" width="100%"><tbody>
		<tr>
			<td rowspan="2" class="s7" valign="top" width="6%">
				<img class="unit" src="{icon}"></td>
			<td class="s7">
				<div><a href="#" onClick="return PopupTroop({link});" title="Xem thông tin chi tiết">{name}</a>
				<span class="c f75">({Present}: {present_troop})</span></div>
			</td></tr>
		<tr><td class="s7">
					<img src="images/un/r/x.gif" width="1" height="15">
					<img class="res" src="images/un/r/1.gif">{rs1}
					|<img class="res" src="images/un/r/2.gif">{rs2}
					|<img class="res" src="images/un/r/3.gif">{rs3}
					|<img class="res" src="images/un/r/4.gif">{rs4}
					|<img class="res" src="images/un/r/5.gif">{keep_hour}
					|<img class="clock" src="images/un/a/clock.gif">{time_train}
			</td></tr>
	</tbody></table></td>
<td><input name="t{i}" value="0" size="2" maxlength="4" type="text"></td>
<td><div class="f75"><a href="#" onclick="document.snd.t{i}.value={sum}; return false;">({sum})</a></div></td>
</tr>