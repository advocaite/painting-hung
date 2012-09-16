<style>
#ce{
position: absolute; height:456px; width:500px; top:19px;left:272px;
border:#FF0000;
z-index:1;
}
.popup3 {position:absolute; background-image:url(../images/un/a/anl.png); background-repeat:no-repeat; display:inline; text-align:center; width:430px; height:456px; z-index:80; padding-top:30px; padding-bottom:0px; padding-left:20px; left: 85px; top:10px; font-family:Arial, Helvetica, sans-serif;}
.popup4 {position:absolute; width:30px; height:30px; z-index:81; left: 487px; top:19px}
</style>
<div id="ce"></div>
<script language="javascript">
function Popup(i)
{
	pb=document.getElementById("ce");
	if(pb!=null)
	{
		var rc="<div class=\"popup3\"><iframe allowTransparency=\"true\" frameborder=\"0\" id=\"Frame\" src=\"../manual.php?id="+i+"\" width=\"360\" height=\"420\" border=\"0\" class=\"iframe\"></iframe></div><a href=\"#\" onClick=\"Close(); return false;\"><img src=\"../images/un/a/x.gif\" border=\"1\" class=\"popup4\"></a>";
		pb.innerHTML=rc;
		document.getElementById("ce").style.position='fixed';		
	}
}
function Close()
{
	pb=document.getElementById("ce");
	if(pb!=null)
	{
		pb.innerHTML='';
	}
	if(quest.anmstep!==false)
	{
		quest.anmstep=false;
	}
	document.location.reload();
}
//Open popup
function openWindow(filename, winname, width, height, feature) {	
	var features, top, left;
	var reOpera = /opera/i ;
	var winnameRequired = ((navigator.appName == "Netscape" && parseInt(navigator.appVersion) == 4) || reOpera.test(navigator.userAgent));
	
	left = (window.screen.width - width) / 2;
	top = (window.screen.height - height) / 2;	
	if(feature == '')
		features = "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left + ",status=0,location=0,scrollbars=1";
	else
		features = "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left + "," + feature;
//	if(! winnameRequired)	winname = "";	
	newwindow = window.open(filename, winname, features);
	newwindow.focus();
}
//
function PopUpSong()
{
	if (window.ActiveXObject) {
		openWindow('create_mission.php', 'popupsong',300, 300, '');
	} else {
		openWindow('create_mission.php', 'popupsong',300, 300, '');
	}		
	return false;
}
</script>

<div id="column-content">
  <div id="content">
    <form name="form_building_type" method="post" action="">
	   <fieldset><center><b style="color:#FF0000; font-size:16px">WG_MISSION</b></center></fieldset>
       <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
       <tbody>
          <tr  class="rbg" height="30">
            <th align="center">Mission</th>
            <th align="center">Lumber</th>
            <th align="center">Clay</th>
            <th align="center">Iron</th>
            <th align="center">Crop</th>
            <th align="center">Asu</th>
			<th align="center">View</th>
            <th align="center"><font color="red">Edit</font></th>
          </tr>
        {mission_list}
      </table>
      <table width="100%">
        <tr align="center">
          <td><b>Chỉ khi nào có sự thay đổi ở trên thì mới click vào chữ <span class="style1">Thực hiện</span> bên dưới !</b></td>
        </tr>
        <tr align="center">
          <td><a href="#" onclick="PopUpSong();"><b style="font-size:16px; color:#FF0000"><u>Thực hiện</u></b></a> </td>
        </tr>
      </table>
    </form>
  </div>
</div>
