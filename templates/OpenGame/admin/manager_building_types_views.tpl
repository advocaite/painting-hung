<script language="javascript">
function OpenWindowsNew()
{
		return window.open('create_building_types.php','Building Type','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=300,height=200,top=200,left=150');
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
		openWindow('create_building_types.php', 'popupsong',300, 300, '');
	} else {
		openWindow('create_building_types.php', 'popupsong',300, 300, '');
	}		
	return false;
}
</script>
<div id="column-content">
<div id="content">
<form name="form_building_type" method="post" action="">
<div><a href="map_troops.php"><span style="background: rgb(0, 153, 255) none repeat scroll 0% 0%;">&nbsp;Wg_building_type&nbsp;</span></a> | <a href="manager_building_types.php?s=1">Help building</a></div>
<fieldset>
<center><b style="color:#FF0000; font-size:16px">BUILDING TYPE</b></center>
</fieldset>
<table width="100%" cellspacing="1" cellpadding="0" class="tbg">
<tbody>
	<tr class="rbg" height="30">
		 <th align="center">Id</th>
		<th align="center">Name</th>
		<th align="center">Rs1</th>
		<th align="center">Rs2</th>
		<th align="center">Rs3</th>
		<th align="center">Rs4</th>
		<th align="center">Max level</th>
		<th align="center"><font color="red">{update_button}</font></th>
	</tr>
	{list}	
</table>
 <table width="100%">
    <tr>
        <!--<td width="97"><input name="check_all" type="button" value="Select all" onClick="checkall();" class="std">
        </td>
        <td width="161"><input name="un_check_all" type="button" value="Un select all" onClick="uncheckall();" class="std">
        </td>
        <td>
        	<input id="delete" name="delete" type="submit" value="{delete}">
        </td>-->
        <td width="133"><input id="new" name="new" type="submit" value="Add new" class="fm">
        </td>
        <td width="167"><b>Total record: {total_record}</b></td>
        <td width="134">Page {pagenumber} of {total_page}</td>
    </tr>
</table>
 <table width="100%">
	<tr align="center">
		<td><b>Chỉ khi nào có sự thay đổi ở trên thì mới click vào chữ <span class="style1">Thực hiện</span> bên dưới !</b></td>
	</tr>
	<tr align="center">
		<td><a href="#" onclick="return PopUpSong();"><b style="font-size:16px; color:#FF0000"><u>Thực hiện</u></b></a>
		</td>
	</tr>
</table>
</form>
</div>
</div>