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
	openWindow('create_tech_tree.php', 'TranhHung', 400, 300, '');		
	return false;
}
function Edit(id)
{
	value=document.getElementById('div_'+id).value;
	document.getElementById('div_'+id).innerHTML='<input type="text" size="30" name="" id="txt_'+id+'" value="'+value+'" class="fm"  onchange="Update('+id+');" />';	
}
function Update(id)
{
	value=document.getElementById('txt_'+id).value;
	window.open('map_tech_tree.php?update='+value+'&id='+id,'_top');
}
function Edit(id)
{
	document.getElementById('div_'+id).style.display='block';
	document.getElementById('div_'+id+'_a').style.display='none';
	document.getElementById('href_'+id).style.display='none';
}
</script>
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>


<div id="column-content">
    <div id="content">
        <form name="form_tech_tree" action="">
         <fieldset><center><b style="color:#FF0000; font-size:16px">TECH TREE</b></center></fieldset>
            <table width="100%" cellspacing="1" cellpadding="2" class="tbg">              
                <tr class="rbg" height="30">
                    <!--<th width="6%" align="center"></th>-->
                    <th width="8%" align="center">No</th>
                    <th width="30%" align="center">{name_tech_building}</th>                    
                    <th width="34%" align="center">{name_tech_res}</th>
                    <th width="22%" align="center"><font color="red">{update_button}</font></th>
                </tr>
                {list}
            </table>
            <table width="100%" cellspacing="1" cellpadding="2" >
                <tr>
                    <td width="567"><!--<input name="check_all" type="button" value="Chọn tất cả" onClick="checkall();" class="fm">
                   &nbsp;&nbsp;&nbsp;<input name="un_check_all" type="button" value="Bỏ chọn tất cả" onClick="uncheckall();" class="fm">&nbsp;&nbsp;&nbsp;<input id="new" name="new" type="submit" value="{new}" class="fm">&nbsp;&nbsp;&nbsp;<input id="delete" name="delete" type="submit" value="{delete}" class="fm">--></td>
                    <td width="203"><b>Total record: {total_record}</b></td>
                    <td width="187">Page {pagenumber} of {total_page}</td>
                </tr>
            </table>
            <br/>
            <table width="100%">
            	<tr align="center">
                	<td><b>Chỉ khi nào có sự thay đổi ở trên thì mới click vào chữ <span class="style1">Thực hiện</span> bên dưới !</b></td>
                </tr>
                <tr align="center">
                	<td>
                    	<a href="#" onclick="PopUp();"><b style="font-size:16px; color:#FF0000"><u>Thực hiện</u></b></a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
