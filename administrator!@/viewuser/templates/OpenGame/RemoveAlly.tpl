<html>
<head>
<meta http-equiv="content-type" content="text/html; charset={ENCODING}">
<link rel="stylesheet" type="text/css" href="{dpath}formate.css">
<script language="JavaScript">
	function CheckInput()
	{
		var frm = document.removeallyForm;
		if (frm.ally_name.value == '')
		{
			alert("Please enter ally's name that you want to kick out your alliance");
			frm.ally_name.focus();
			return false;
		}
		return true;
	}

</script> 
</head>
<body>
<center>
<br>
<form name="removeallyForm"  method="POST" action="" onSubmit="return CheckInput();" >
<table width="209">
	<tr>	
		<td width="201" colspan="5" align="center" class="c"> <b>{Kick player}</b></td>
  	</tr>
	<tr><td></td></tr>  
</table>
<table width="215">
  	<tr><td width="207" class="table_input"><div id="uni_infos_link">
             <table width="207">
				<tr>
					<td align="center">{Name}</td>
					<td><input type="text" name="ally_name" size="20" maxlength="20"></td>
				</tr>
				<tr height="99">
					<td width="79">
				  	  <div align="center">
					  	
							<input name="submit" type="submit" style="width:65px" value="{OK}" />
			  	  </div></td>
						<td width="162"></td>
				</tr>
		</table>
                        <p>&nbsp;</p>
						</td>
	</tr>
</table>
</form>
</div></div></div>
</body></html>