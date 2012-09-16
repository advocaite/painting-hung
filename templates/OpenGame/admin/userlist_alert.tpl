<script>
function check()
{
	var frm = document.form_alert;		
		if(frm.reason_cont.value.length > 250 || frm.reason_cont.value.length <1)
		{			
			alert("Nhap ly do tu 1 den 250 ky tu !") 
			frm.reason_cont.focus();
			return false;			
		}
	return true;
}
</script>
<form action="" name="form_alert" method="post" onSubmit="return check()">
<th>  Nh&#7853;p l&yacute; do  !</th>
<input type="text" name="reason_cont" id="reason_cont" size="60">
<!--<textarea name="reason_cont"  cols="30" rows="2" id="reason_cont">

</textarea>
--><br><br>
<input type="submit" name="submit" value="{Ban}"> 

<input type="button" name="cancel" value="{Cancel}" onClick="window.history.go(-1);">

</form>