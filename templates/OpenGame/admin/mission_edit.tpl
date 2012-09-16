<style>
#ce{
position: absolute; height:456px; width:500px; top:76px;left:271px;
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
       <table width="100%">
        <tr>
          <td width="21%" align="center"> Mission: {mission_number} </td>
          <td width="17%" align="center"><input type="text" name="lumber_edit" value="{lumber_edit}" size="5" maxlength="3" class="fm" />
          </td>
          <td width="13%" align="center"><input type="text" name="clay_edit" value="{clay_edit}" size="5" maxlength="3"  class="fm"/>
          </td>
          <td width="12%" align="center"><input type="text" name="iron_edit" value="{iron_edit}" size="5" maxlength="3" class="fm" />
          </td>
          <td width="13%" align="center"><input type="text" name="crop_edit" value="{crop_edit}" size="5" maxlength="3" class="fm" />
          </td>
          <td width="14%" align="center"><input type="text" name="asu_edit" value="{asu_edit}" size="5" maxlength="3" class="fm" />
          </td>
          <td width="10%" colspan="3" align="center"><input type="submit" name="update" value="Update" class="fm" /></td>
        </tr>
		<tr>
		<td colspan="8">{content}</td>
		</tr>
      </table>
       <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
        <tbody>
          <tr class="rbg" height="30">
            <th align="center">No</th>
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
    </form>
  </div>
</div>
