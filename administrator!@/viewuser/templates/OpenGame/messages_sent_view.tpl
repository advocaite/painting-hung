
<form action="" method="post" name="frm_sent_view" >
  <table width=519>
	<tr>	
	  <th>{owner}</th>
	  <th><input type="text" name="txtsender"  size="50" value="{msg_txt_owner}" readonly="{readonly}"></th>
	</tr>
	<tr>	
	  <th>{topic}</th>
	  <th><input type="text" name="txttopic"  size="50" value="{msg_txt_topic}" readonly="{readonly}"></th>
	</tr>
	<tr>
	  <th>{content} </th>
	  <th><textarea name="txtcontent" id="txtcontent" cols="100" rows="10" readonly="{readonly}">{msg_txt_content}</textarea></th>
	</tr>
	<tr>
	  <td class="c"><a href="?.php"></a></td>
	  <td class="c" align="center">
	  <input type="hidden" value="flag" name="flag" id="flag" >
	  <input type="submit" value="{answer_sent}"  name="answer_sent"></td>		
	</tr>
  </table>
</form>