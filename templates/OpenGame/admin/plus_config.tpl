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
	openWindow('create_plus.php', 'TranhHung',400,150, '');
	return false;
}
</script>
<div id="column-content">
  <div id="content">
    <form method="POST">
      <fieldset><center><b style="color:#FF0000">{PLUS CONFIGURATION}</b></center></fieldset>
      <table width="50%" cellpadding="2" cellspacing="1" class="tbg"  align="center">
        <tr class="rbg">
          <th>{No}</th>
          <th>{Name}</th>
          <th>{Duration}</th>
          <th>{Asu}</th>
          <th></th>
        </tr>
        {list_plus_config}
      </table>
    </form>
     <div><center><a href="#" onclick="return PopUp();"><strong>Cập nhật file</strong></a></center></div>
  </div> 
</div>
