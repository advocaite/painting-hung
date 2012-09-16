<script language="javascript">
	function CheckInput()
	{
		// document.ten_form.ten_textbox.value.length
		var frm = document.formTwo;		
		if(frm.txtowner.value.length > 250 || frm.txtowner.value.length <1)
		{			
			alert("Recipcient tu 1 den 250 ky tu !") 
			frm.txtowner.focus();
			return false;			
		}
		if(frm.txtcontent.value.length > 5000 || frm.txtcontent.value.length <1)
		{			
			alert("Content tu 1 den 5000 ky tu !") 
			frm.txtcontent.focus();
			return false;			
		}
		
		return true;
	}
	
</script>
</head>
<form action="" method="post" name="formTwo" onSubmit="return CheckInput()" >
  <table width=519>
	<tr>	
	  <th>{owner}</th>	  
	  <th><input type="text" name="txtowner"  size="50" value="{msg_txt_owner}" ></th>
	</tr>
	<tr>	
	  <th>{topic}</th>
	  <th><input type="text" name="txttopic"  size="50" value="{msg_txt_topic}" ></th>
	</tr>
	<tr>
	  <th>{content} </th>
	  <th><textarea name="txtcontent" id="txtcontent" cols="100" rows="10" >{msg_txt_content}</textarea></th>
	</tr>
	<tr>
	  <td class="c"><a href="?.php"></a></td>
	  <td class="c" align="center">
	  <input type="hidden" id="id_msg_sent" name="id_msg_sent" value="0" >
	  <input type="hidden" id="id_tab" name="id_tab" value="2" >
	  <input type="submit" value="{send_sent}"  name="send_sent"></td>		
	</tr>
  </table>
</form>