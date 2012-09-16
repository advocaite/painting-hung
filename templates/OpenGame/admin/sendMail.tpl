
<script language="javascript">
	function CheckInput()
	{
		// document.ten_form.ten_textbox.value.length
		var frm = document.formTwo;		
		if(frm.txttitle.value.length > 250 || frm.txttitle.value.length <1)
		{			
			alert("Title tu 1 den 250 ky tu !") 
			frm.txttitle.focus();
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
<br>
<form action="" method="post" name="formTwo" onSubmit="return CheckInput()">
  <table width="70%" align="center" border="2">
	<tr>
	  <td class="c" colspan=2 align="center">{Send_circular_mail}</td>
	</tr>
	<tr>
	  
	  <th width="20%">{To}</th>	  
	  <th>
		<select name="r" id="r">
		  {r_list}
		</select>
	  </th>
	</tr>
	<tr width="20%">
	  <th>{Title} (<span id="cntChars">0</span> / 250 {characters})</th>
	  <th>

		<input type="text" name="txttitle"  size="50" value="" >
	  </th>
	</tr>
	<tr>
	  <th width="20%">{Text_mail} (<span id="cntChars">0</span> / 5000 {characters})</th>
	  <th>
	    <textarea name="txtcontent" id="txtcontent" cols="100" rows="10"></textarea>
	  </th>
	</tr>
	<tr>
	  <td class="c"><a href="sendMail.php"></a></td>
	  <td class="c" align="center">
		<input type="button" onClick="reset_value()" value="{Clear} ">		
		<input type="submit" value="{Send}"  name="send">
		<input type="hidden" id="send_mail" name="send_mail" value="2" />
		
	  </td>
	</tr>
  </table>
</form>
</div></div>


<div id="p-cactions" class="portlet">
		<h5>Views</h5>
		<div class="pBody">
			<ul>	
				 <li id="ca-nstab-main" class="selected"><a href="/wiki/Quicksort" title="View the content page [c]" accesskey="c">Send Mail</a></li>
              </ul>
		</div>
</div>