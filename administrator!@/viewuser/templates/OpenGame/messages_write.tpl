<script type="text/javascript" src="js/vietuni.js"></script>
<script language="javascript">
	function CheckInput()
	{
		var frm = document.formTwo;		
		if(frm.to_name.value.length > 250 || frm.to_name.value.length <1)
		{			
			var string=document.getElementById("answer1").value;
			alert(string); 
			frm.to_name.focus();
			return false;			
		}
		if(frm.content.value.length > 5000 || frm.content.value.length <1)
		{			
			var string=document.getElementById("answer3").value;
			alert(string);
			frm.content.focus(); 
			return false;			
		}
		
		return true;
	}	
</script>
<div style="position:absolute;{customize};display:{display}"; onclick="document.getElementById('to_name').value='(*)'"; onmouseover="ddrivetip('{sent_mess_allies}')"; onmouseout="hideddrivetip()";><img src="images/un/a/lien-minh.png" style="cursor:pointer;"/></div>
<form action="" method="post" name="SentMessage" onSubmit="return CheckInput()" >
<input type="hidden" name="value" value="{code}">
<input type="hidden" id="answer1" value="{34}" maxlength="100">
<input type="hidden" id="answer2" value="{35}" maxlength="100">
<input type="hidden" id="answer3" value="{36}" maxlength="100">
<div><input name="radiobutton" onclick="setTypingMode(4)" checked="checked" type="radio">Bật bộ gõ tiếng Việt&nbsp;<input name="radiobutton" onclick="setTypingMode(0)" type="radio"> Tắt</div>
<br />
{error}
<table style="width: 440px; height:439px; background:url(images/en/msg/message.jpg) top center no-repeat" cellspacing="0" cellpadding="0" class="f10">		
		<tr>
			<td colspan="4">&nbsp;</td>
	</tr>
		
		<tr>
		  <td width="19" rowspan="2"></td>
          <td width="94" class="message">{Recipient}</td>
          <td width="272">
        <input type="text" id="to_name" name="to_name" maxlength="20" value="{to}" style="width:268px; background: url(images/en/msg/underline.gif) top center; border:0px;" >			</td>
		  <td width="52" rowspan="2">&nbsp;</td>
	</tr>
		
		<tr>
        <td width="94" class="message">{Subject}</td>
		  <td><input type="text" name="topic" id="topic" maxlength="35" value="{topic}" style="width:268px;background: url(images/en/msg/underline.gif) top center; border:0px;" onkeyup="initTyper(this);"/></td>		
		</tr>
		
		<tr>
			<td colspan="4">&nbsp;</td>
	</tr>
		
		<tr>
			<td>
				<img src="images/un/a/x.gif" width="40" height="250" border="0"></td>
	  <td colspan="3">
                <textarea name="content" id="igm" rows="14" class="f10" style="background: url(images/en/msg/underline.gif) top center; width:360px;" onkeyup="initTyper(this);">{content}</textarea>			</td>
		</tr>
		
		<tr>
			<td colspan="4" align="center">
		  <input type="hidden" value="2" name="id_tab" id="id_tab"  />
		<input type="image" value="" border="0" name="send_w_msg" id="send_w_msg" src="images/en/b/snd1.gif" width="80" height="20" onMousedown="btm1('s1','','images/en/b/snd2.gif',1)" onMouseover="btm1('s1','','images/en/b/snd3.gif',1)"  onMouseUp="btm0()" onMouseOut="btm0()" onClick="return urlaub()"></input>			</td>
		</tr>
	  	<tr>
		  	<td colspan="4" style="background-color:white;">&nbsp;</td>
	</tr>
</table>
</form>
</div>
</div>
</div>