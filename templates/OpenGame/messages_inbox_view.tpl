<form action="" method="post" name="formTwo">
<table style="width: 440px; height:439px; background:url(images/en/msg/message.jpg) top center no-repeat" cellspacing="0" cellpadding="0" class="f10">		
<tr>
			<td width="40" rowspan="2"></td>
	  <td width="75" class="message">{personal}</td>
<td width="238" class="col">
<input type="text" name="txtowner" maxlength="20"  style="width:238px; background: url(images/en/msg/underline.gif) top center; border:0px;" value="{from_id}" readonly="1">
			</td>
			<td width="85" class="datetime">{date}</td>
	</tr>
<tr>
        <td width="75" class="message">{Subject}</td>
			<td class="col">
                <input type="text" name="txttopic" id="txttopic" maxlength="35" value="{topic}" style="width:238px;background: url(images/en/msg/underline.gif) top center; border:0px;" readonly="1">
	  </td>	
        <td width="85" class="datetime">{time}</td>	
	</tr>
		
		<tr>
			<td colspan="4">&nbsp;</td>
	</tr>
		
		<tr>
			<td>
				<img src="images/un/a/x.gif" width="40" height="250" border="0">
			</td>
			<td colspan="3">
                <textarea name="txtcontent" readonly="readonly" id="igm" rows="14" class="f10" style="background: url(images/en/msg/underline.gif) top center; width:360px;" >{content}</textarea>

			</td>
		</tr>
		
		<tr>
			<td colspan="4" align="center">
		<input type="image" value="" border="0" name="send_w_msg" id="send_w_msg" src="images/en/b/ant1.gif" width="80" height="20" onMousedown="btm1('s1','','images/en/b/ant2.gif',1)" onMouseover="btm1('s1','','images/en/b/ant3.gif',1)"  onMouseUp="btm0()" onMouseOut="btm0()" onClick="return urlaub()"></input>
			</td>
		</tr>
		
	  	<tr>

		  	<td colspan="4" style="background-color:white;">&nbsp;</td>
		</tr>
</table>
</form>

</div>
</div>
</div>