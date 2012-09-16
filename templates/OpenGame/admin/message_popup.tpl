<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<title>User Bad List</title>
</head>
<script>
function SetBadList()
{
	var frm = document.form_alert;		
	if(frm.txt_reason.value.length > 250 || frm.txt_reason.value.length <1)
	{			
		alert("Nhập lý do từ 1 đến 250 ký tự !") 
		frm.txt_reason.focus();
		return false;			
	}
	return true;
}
</script>
<style>
body{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
}
.fm {border:#009bdf solid; border-width:1px; font-size:10pt; padding-left:3px; padding-top:2px; padding-bottom:2px;}
</style>
<body>
<form action="message.php" name="form_alert" method="post">
<input type="hidden" name="username" value="{username}" />
    <table align="center" style="border:solid 1px #999999" width="300" height="100" class="fm">
        <tr>
            <td align="center" colspan="4"><b style="color:#FF0000">Lý do đưa vào danh sách bad list</b> </td>
        </tr>
        <tr>
            <td colspan="4" align="center">
            <textarea style="width:350px; height:120px;" name="txt_reason" id="txt_reason" cols="45" rows="3" tabindex="4" class="fm"></textarea>
            </td>
        </tr>
		<tr>            
            <td width="50%" align="right"><input type="submit" class="fm" name="go_ban" value="Đồng ý" onclick="return SetBadList();" tabindex="6">&nbsp;&nbsp;</td>
            <td align="left">&nbsp;&nbsp;<input  class="fm" type="button" name="cancel" value="Hủy" tabindex="7" onClick="window.opener.location.href; window.close();">
        </tr>
    </table>
</form>
</body>
</html>
