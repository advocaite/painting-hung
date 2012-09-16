<script language="javascript">
function CheckInput()
{
	var frm = document.form_send_message;		
	if(frm.group.value==-1 && frm.group.disabled==false)
	{			
		alert("Chọn nhóm cần gửi");
		frm.group.focus();
		return false;			
	}
	if(frm.allies.disabled==false && frm.allies.value==-1)
	{			
		alert("Chọn liên minh cần gửi"); 
		frm.allies.focus();
		return false;			
	}
	if(frm.member.value =='' && frm.member.disabled==false)
	{			
		alert("Chọn thành viên cần gửi"); 
		frm.member.focus();
		return false;			
	}
	if(frm.txt_subject.value.length > 250 || frm.txt_subject.value.length <1)
	{			
		alert("Tiêu đề từ 1 đến 250 ký tự") 
		frm.txt_subject.focus();
		return false;			
	}
	if(frm.txt_content.value.length > 5000 || frm.txt_content.value.length <1)
	{			
		alert("Nội dung từ 1 đến 5000 ký tự") 
		frm.txt_content.focus();
		return false;			
	}
	return true;
}
function Disiable(id2,id3)
{
	for(var i=1;i<=3;i++)
	{
		if( id2==i || id3 ==i)
		{
			var char="'"+i+"'";
			document.getElementById(i).disabled=true;
			document.getElementById(i+'_enable').innerHTML='<input type="checkbox" onfocus="Enable('+char+');"/>';
		}	
		else
		{
			document.form_send_message.sent_to.value=i;
		}	
	}
}
function Enable(id)
{
	for(var i=1;i<=3;i++)
	{
		if(id==i)
		{
			document.getElementById(id).disabled=false;			
		}
		else
		{
			var char="'"+i+"'";
			document.getElementById(i).disabled=true;
			document.getElementById(i+'_enable').innerHTML='<input type="checkbox" onfocus="Enable('+char+');"/>';
		}
	}
}
</script>
<script type="text/javascript" src="../js/vietkey.js"></script> 
<div id="column-content">
  <div id="content">
    <form action="" method="post" name="form_send_message" onSubmit="return CheckInput();">
	  <table width="100%" align="center">
        <tr align="left">
          <td class="c" colspan=5 align="center"><b style="color:#FF0000">Gởi Thư Theo Nhóm</b></td>
        </tr>
		<tr align="left">
          <td class="c" colspan=5 align="left">{error}</td>
        </tr>
		<tr style="display:none;">
		<td colspan="5"><select name="sent_to">
              <option value="1" >1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              </select></td>
		</tr>
		<tr><td colspan="5">
<div><input onfocus="setTypingMode(1)" value="TELEX" name="switcher" type="radio" tabindex="0" class="fm"><b>Telex</b>
<input  onfocus="setTypingMode(2)" value="vnVni11" name="switcher" type="radio" tabindex="1"><b>VNI</b> 
<input onfocus="setTypingMode(3)" value="vnVni12" name="switcher" type="radio" tabindex="2"><b>VIQR</b> 
<input onfocus="setTypingMode(0)" value="OFF" name="switcher" type="radio" tabindex="3" checked="checked"><b>Tắt&nbsp;&nbsp;</b></div> </td>
		</tr>
        <tr align="left">
          <td width="21%"><strong>Nhóm</strong> :</td>
          <td > <select name="group" id="1" onchange="Disiable('2','3');" class="fm">
              <option value="-1" selected="selected">-- Select --</option>
              <option value="0">Tất cả user</option>
              <option value="1">100 user xếp hạng cao nhất</option>
              <option value="2">100 user xếp hạng thấp nhất</option>
              <option value="3">100 user mới tham gia</option>
            </select>&nbsp;&nbsp;<span id="1_enable"></span>
          </td>
        </tr>  
		<tr>
          <td width="21%"><strong>Liên Minh</strong>:</td>
          <td align="left"> <select id="2" name="allies" onchange="Disiable('1','3');" class="fm">
		          <option value="-1" selected="selected">-- Select --</option>
			  <option value="0">Tất cả</option>
              {row_alies}
            </select>&nbsp;&nbsp;<span id="2_enable"></span>
          </td>
        </tr> 
		<tr>
          <td width="21%"><strong>Thành viên </strong>:</td>
          <td align="left"><input type="text" name="member" id="3" maxlength="255" size="25" class="fm" onclick="Disiable('1','2');"/>&nbsp;&nbsp;<span id="3_enable"></span>
          </td>
        </tr> 
        <tr align="left" >
          <th>Tiêu đề (0 - 250 ký tự)</th>
          <th colspan="4"> <input type="text" name="txt_subject" id="txt_subject"  size="50" class="fm" onkeyup="telexingVietUC(this,event);">
          </th>
        </tr>
        <tr>
          <th width="21%"><p>Nội dung</p>
          <p> (0 - 5000 ký tự)</p></th>
          <th colspan="4" align="left"> <textarea name="txt_content" id="txt_content" cols="100" rows="10" onkeyup="telexingVietUC(this,event);"></textarea>
          </th>
        </tr>
        <tr>
          <td colspan="3" class="c" align="right">
          	<input type="reset" value="Xóa" class="fm"/>
          </td>
          <td width="48%" colspan="2" align="left" class="c">
          <input type="submit" value="Gởi"  name="send" class="fm"/></td>
        </tr>
      </table>     
    </form>
  </div>
</div>
