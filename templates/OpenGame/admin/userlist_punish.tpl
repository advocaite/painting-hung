<script language="javascript">
function checkDelete()
{
	var del = confirm('Are you sure delete ?');
	if(del)
	{
		return true;
		//opener.location.reload();   
		//self.close(); 
	}
	else
	{
		return false;		
	}	

} 		
function getVillageId()
{
	var div1 = document.getElementById('village_id_population');//for sub population
	var div2 = document.getElementById('village_id_troop');//for sub troop
	
	var list = document.getElementById('vilage_name_listbox');	
	div1.value = list.options[list.selectedIndex].value;
	div2.value = list.options[list.selectedIndex].value;
} 

function popUpReason(url)
{
	window.open(url,"_blank","location=1,status=1,scrollbars=1,width=410,height=180");	
}  
</script>
<link rel="stylesheet" href="../css/games.css" type="text/css" />
<div id="column-content">
  <div id="content">    
  <fieldset><center><b style="color:#FF0000">Trừng Phạt</b></center></fieldset>
  <table>
      <tr>
        <td width="91%">
        	   <b style="color:#0000FF">Tài khoản: {user_name}</b>        </td>
      </tr>
    </table>     
    <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
      <tr class="rbg">
        <td width="17%" align="center">Xóa thành</td>
        <!--<td width="19%" align="center">Xóa liên minh</td>-->
        <td width="36%" align="center">Trừ dân số </td>
        <td width="28%" align="center">Trừ lính</td>
      </tr>
      <tr>
        <td><form name="frm_del_village" method="post">			
            <table width="100%">
              <tr>
                <td align="center">
                <select name="vilage_name_listbox" id="vilage_name_listbox" onchange="getVillageId();" class="fm">
                    <option selected="selected" value="0">-- Chọn thành --</option>                                      
	         		{vilage_name_list}
				</select>
               </td>
              </tr>
              <tr>
                <td align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="center">
                	<input type="submit" name="delete_village" value="OK" class="fm"/>
                </td>
              </tr>
            </table>
          </form></td>
        <td>
        	<form name="frm_del_population" method="post">
            <div><center><select name="del_population_vilage" class="fm">
        <option selected="selected" value="0">-- Chọn thành --</option>                                      
	    {vilage_name_list}
		</select></center></div>                 
                <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
                <tr>
                    <td align="center" colspan="2"><input type="submit" name="empty_rs" value="Làm rỗng Nhà Kho" class="fm"/></td>
                  </tr>
                  <tr>
                    <td align="center">Nội thành(level)</td>
                    <td align="center">Ngoại thành(level)</td>
                  </tr>
                  <tr>
                    <td align="center">
                      <input type="text" name="inside" size="5" maxlength="2" class="fm" /></td>
                    <td align="center"><input type="text" name="outside" size="5" maxlength="2" class="fm" />
                   </td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center">
                    <input type="submit" name="delete_building" value="OK" class="fm"/>
                    </td>
                  </tr>
                </table>
          </form>
        </td>
        <td><form name="frm_del_troop" method="post">			
          <div><center><select name="del_troop_vilage" class="fm">
        <option selected="selected" value="0">-- Chọn thành --</option>                                      
	    {vilage_name_list}
		</select></center></div>
            <table width="100%">
              <tr>
                <td align="center" colspan="2"><select name="percent_troop" class="fm">
                    <option value="0">- Select %-</option>
                    <option value="10">10%</option>
                    <option value="20">20%</option>
                    <option value="30">30%</option>
                    <option value="40">40%</option>
                    <option value="50">50%</option>
					<option value="60">60%</option>
					<option value="70">70%</option>
					<option value="80">80%</option>
					<option value="90">90%</option>
					<option value="100">100%</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center"></td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                	<input type="submit" name="delete_troop" value="OK" class="fm"/>
                </td>
              </tr>
            </table>
          </form></td>
      </tr>
      <tr>
        <td align="center"><span style="color:#FF0000">{msg_village}</span> </td>
       <!-- <td align="center"><span style="color:#FF0000">{msg_ally}</span></td>-->
        <td align="center"><span style="color:#FF0000">{msg_population}</span></td>
        <td align="center"><span style="color:#FF0000">{msg_troop}</span></td>
      </tr>
      <tr>
        <td ><b>Xóa thành:</b> ngoại trừ thủ đô </td>
        <td align="center"><b>Trừ dân số:</b> nhập level muốn trừ cho nội thành và ngoại thành </td>
        <td align="center"><b>Trừ lính:</b>  chọn % số lính muốn trừ  </td>         
        </tr>
    </table>   
  </div>
</div>
