<script language="javascript">
//Open popup
function openWindow(filename, winname, width, height, feature) {	
	var features, top, left;
	var reOpera = /opera/i ;
	var winnameRequired = ((navigator.appName == "Netscape" && parseInt(navigator.appVersion) == 4) || reOpera.test(navigator.userAgent));
	
	left = (window.screen.width - width) / 2;
	top = (window.screen.height - height) / 2;	
	features = "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left + ",status=0,location=0,scrollbars=yes";
	newwindow = window.open(filename, winname, features);
	newwindow.focus();
}
function PopUp()
{
	if (window.ActiveXObject) {
		openWindow('fixmerchant.php', 'TranhHung', 300, 500, '');
	} else {
		openWindow('fixmerchant.php', 'TranhHung', 300, 500, '');
	}
	return false;
}
</script>
<div id="column-content">
  <div id="content">
    <p class=\"txt_menue\"><a href="roll_back.php">{Fix troop keep}</a> | <a href="roll_back.php?s=vw">{View worker}</a> | <a href="roll_back.php?s=uw">{Update worker}</a> | <a href="roll_back.php?s=vkrs">{View krs}</a> | <a href="roll_back.php?s=ukrs">{Update krs}</a> | <span style="background:#0099FF"><a href="roll_back.php?s=fmc">{Fix merchant}</a></span> | <a href="roll_back.php?s=update_rank">{Update Rank}</a> | <a href="roll_back.php?s=maxlevel">{Over MaxLevel}</a></p>
    <form name="frm_user_list" method="POST">
      <input type="hidden" name="reason" id="reason" />
      <table width="100%">
        <tr>
          <td align="center"><b style="color:#FF0000">Cập Nhật Thương Nhân</b> </td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td align="center"><a href="#" onclick="PopUp();"><b style=" font-size:16px">Thực hiện</b></a> </td>
        </tr>
      </table>
    </form>
  </div>
</div>
