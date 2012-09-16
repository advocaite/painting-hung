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
function PopUp(id)
{
	from=document.frm_user_list;
	if(id=='personal' && from.personal.value !='')
	{
		char='?id='+from.personal.value;
		openWindow('viewworker.php'+char+'', 'TranhHung', 750, 600, '');
		return false;
	}
	else if(id=='all' && from.all.checked)
	{
		openWindow('viewworker.php', 'TranhHung', 750, 600, '');
		return false;
	}	
	return false;
}
</script>

<div id="column-content">
  <div id="content">
    <p class=\"txt_menue\"><a href="roll_back.php">{Fix troop keep}</a> | <span style="background:#0099FF"><a href="roll_back.php?s=vw">{View worker}</a></span> | <a href="roll_back.php?s=uw">{Update worker}</a> | <a href="roll_back.php?s=vkrs">View krs</a> | <a href="roll_back.php?s=ukrs">{Update krs}</a> | <a href="roll_back.php?s=fmc">{Fix merchant}</a> | <a href="roll_back.php?s=update_rank">{Update Rank}</a> | <a href="roll_back.php?s=maxlevel">{Over MaxLevel}</a></p>
    <form name="frm_user_list" method="POST">
      <input type="hidden" name="reason" id="reason" />
      <table width="100%">
        <tr>
          <td align="center"><b style="color:#FF0000">{string2}</b></td>
        </tr>
      </table>      
      <table width="100%">       
        <tr>
          <td width="25%"></td>
          <td width="32%" align="left"><strong>{Personal} </strong>:
          <input  type="text" name="personal" class="fm f80" value="" /></td>
		  <td width="43%"><input type="submit" class="fm fm110" value="{View}" onclick="return PopUp('personal');"  /></td>
        </tr>
		 <tr>
           <td></td>
		   <td align="left"><strong>{All}</strong>:<input  type="checkbox" name="all" /></td>
		   <td><input type="submit" class="fm fm110"  value="{View}" onclick="return PopUp('all');" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
