<script language="javascript">
function OpenWindowsNew(linksite)
{
	return window.open(linksite,'Troops','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=300,height=200,top=200,left=150');
}
function checkall()
{
	for(i=0;i < document.form_map_troop.checkbox.length;i++)
	{
		document.form_map_troop.checkbox[i].checked=true;
	}
}
function uncheckall()
{
	for (i = 0; i < document.form_map_troop.checkbox.length; i++)
	{
		document.form_map_troop.checkbox[i].checked = false ;
	}
}
</script>
<div id="column-content">
    <div id="content">
	<div><span style="background: rgb(0, 153, 255) none repeat scroll 0% 0%;"><a href="map_troops.php">&nbsp;Wg_troops&nbsp;</a></span> | <a href="map_troops.php?s=1">Help troops</a></div>
        <form name="form_map_troop" action="">		
       <fieldset><center><b style="color:#FF0000">TROOPS</b></center></fieldset>
            <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
                <tr class="rbg" height="30">
                    <th align="center">{title_name}</th>
                    <th align="center">{title_attack}</th>
                    <th align="center">{title_melee_defense}</th>
                    <th align="center">{title_ranger_defense}</th>
                    <th align="center">{title_magic_defense}</th>
                    <th align="center">{title_hitpoint}</th>
                    <th align="center">{title_speed}</th>
                    <th align="center">{title_carry}</th>
                    <th align="center">{title_nation_id}</th>
                    <th align="center">{title_rs1}</th>
                    <th align="center">{title_rs2}</th>
                    <th align="center">{title_rs3}</th>
                    <th align="center">{title_rs4}</th>
                    <th align="center">{title_keep_hour}</th>
                    <th align="center">{title_requirement}</th>
                    <th align="center"><font color="red">{update_button}</font></th>
                </tr>
                {views_list}
            </table>
            <table width="100%" cellspacing="1" cellpadding="0" >
                <tr >
                    <td width="605"><!--<input name="check_all" type="button" value="Select all" onClick="checkall();" class="std">
                   <input name="un_check_all" type="button" value="Un select all" onClick="uncheckall();" class="std">
                  <input id="delete" name="delete" type="submit" value="{delete}">
                    <input id="new" name="new" type="submit" value="{new}">-->
                  </td>
                    <td width="163"><b>Total record: {total_record}</b></td>
                    <td width="201">Page {pagenumber} of {total_page}</td>
                </tr>
            </table>
            <br/>
            <table width="100%">
            	<tr align="center">
                	<td><b>Chỉ khi nào có sự thay đổi ở trên thì mới click vào chữ <span style="color:#FF0000">Thực hiện</span> bên dưới !</b></td>
                </tr>
                <tr align="center">
                	<td>
                    	<a href="#" onclick="OpenWindowsNew('create_troops.php');"><b><u style="font-size:16px; color:#FF0000">Cập Nhật SQL Troop</u></b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="#" onclick="OpenWindowsNew('helptroop.php');"><b><u style="font-size:16px; color:#FF0000">Cập Nhật Trợ Giúp Troop</u></b></a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
