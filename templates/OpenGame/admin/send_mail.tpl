<script language="javascript">
	function CheckInput()
	{
		// document.ten_form.ten_textbox.value.length
		var frm = document.formTwo;		
		if(frm.txttitle.value.length > 250 || frm.txttitle.value.length <1)
		{			
			alert("Title từ 1 đến 250 ký tự !") 
			frm.txttitle.focus();
			return false;			
		}
		if(frm.txtcontent.value.length > 5000 || frm.txtcontent.value.length <1)
		{			
			alert("Content từ 1 đến 5000 ký tự !") 
			frm.txtcontent.focus();
			return false;			
		}
		
		return true;
	}
	function reset_value()
	{		
	document.formTwo.txttitle.value="";
	document.formTwo.txtcontent.value="";
	document.formTwo.txttitle.focus();
	return true;
	}
</script>

<div id="column-content">
    <div id="content">
        <form action="" method="post" name="formTwo" onSubmit="return CheckInput()">
            <table width="70%" align="center" border="1" style="margin-top:5px">
                <tr>
                    <td class="c" colspan=2 align="center"><b style="color:#FF0000">{Send_circular_mail}</b></td>
                </tr>
                <tr>
                    <th width="20%">{To}</th>
                    <th> <select name="r" id="r">                    
		 					 {r_list}
                        </select>
                    </th>
                </tr>
                <tr width="20%">
                    <th>{Title}
                        <p>(<span id="cntChars">0</span> / 250 {characters})</p></th>
                    <th> <input type="text" name="txttitle"  size="50" value="" >
                    </th>
                </tr>
                <tr>
                    <th width="20%"><p>{Text_mail}</p>
                        <p> (<span id="cntChars">0</span> / 5000 {characters})</p></th>
                    <th> <textarea name="txtcontent" id="txtcontent" cols="100" rows="10"></textarea>
                    </th>
                </tr>
                <tr>
                    <td class="c"></td>
                    <td class="c" align="center"><input type="button" onClick="reset_value()" value="{Clear} ">
                        <input type="submit" value="{Send}"  name="send">
                        <input type="hidden" id="send_mail" name="send_mail" value="2" />
                    </td>
                </tr>
            </table>
            <table align="center">
            	<tr>
                	<td align="center">
               	         <b style="color:#FF6600">{message}</b>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
