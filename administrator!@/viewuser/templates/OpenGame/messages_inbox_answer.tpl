<script type="text/javascript" src="js/vietuni.js"></script>
<script language="javascript">
	function CheckInput()
	{
		var frm = document.formTwo;		
		if(frm.to_id.value.length > 250 || frm.to_id.value.length <1)
		{			
			var string=document.getElementById("answer1").value;
			alert(string); 
			frm.to_id.focus();
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
<div><input name="radiobutton" onclick="setTypingMode(4)" checked="checked" type="radio">Bật bộ gõ tiếng Việt&nbsp;<input name="radiobutton" onclick="setTypingMode(0)" type="radio"> Tắt</div>
<form action="{link}" method="post" name="formTwo" accept-charset="UTF-8" >
<input type="hidden" name="value" value="{code}" />
<input type="hidden" id="answer1" value="{34}" maxlength="100">
<input type="hidden" id="answer2" value="{35}" maxlength="100">
<input type="hidden" id="answer3" value="{36}" maxlength="100">
<table style="width: 440px; height:439px; background:url(images/en/msg/message.jpg) top center no-repeat" cellspacing="0" cellpadding="0" class="f10">		
<tr>
		  <td width="40" rowspan="2"></td>
	  <td width="94" class="message">{Recipient}</td>
<td width="238" class="col">
			<input type="hidden" name="id" id="id" value="{to_id}" maxlength="100">
                <input type="text" name="to_id" id="to_id" maxlength="20"  style="width:238px; background: url(images/en/msg/underline.gif) top center; border:0px;" value="{from_id}" readonly="1">
			</td>
			<td width="85" class="datetime">{date}</td>
		</tr>		
		<tr>
			<td width="94" class="message">{Subject}</td>
			<td class="col">
                <input type="text" name="topic" id="topic" maxlength="35" value="{topic}" style="width:238px; background: url(images/en/msg/underline.gif) top center; border:0px;" onkeyup="initTyper(this);">
			</td>
            <td width="85" class="datetime">{time}</td>			
		</tr>		
	
		<tr>
			<td>
				<img src="images/un/a/x.gif" width="40" height="250" border="0">
			</td>
			<td colspan="3">
                <textarea name="content" id="igm" rows="15" class="f10" style="background: url(images/en/msg/underline.gif) top center; width:360px; color:#5c4507" onkeyup="initTyper(this);">{content}</textarea>
			</td>
		</tr>		
		<tr>
			<td colspan="4" align="center">
			<input type="image" value="" border="0" name="answer"  id="answer" src="images/en/b/snd1.gif" width="80" height="20" onMousedown="btm1('s1','','images/en/b/snd2.gif',1)" onMouseover="btm1('s1','','images/en/b/snd3.gif',1)"  onMouseUp="btm0()" onMouseOut="btm0()" onClick="return CheckInput();"></input>
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