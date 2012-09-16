<div id="column-content">
  <div id="content">
    <p class=\"txt_menue\"><a href="plus.php">{Give gold}</a> | <span style="background:#0099FF"><a href="plus.php?s=1">{Special}</a></span> | <a href="plus.php?s=2">{Gold log}</a></p>
    <form method="POST" name="form_give_gold">
      <table> 
	  <tr>
          <td><span style="color:#FF0000; font-size:12px;">{msg}</span></td>          
        </tr>      
        <tr>
          <td><select name="option" id="opt" onchange="OnchangeOpt();">
              <option value="0" selected="selected" class="fm">-- {Select} --</option>
              <option value="1">Tất cả gamer</option>
              <option value="2">Top 100 xếp hạng cao nhất</option>              
              <option value="3">Top 100 xếp hạng thấp nhất</option>
			  <option value="4">100 gamer mới tham gia</option>
              <option value="5">Liên minh</option>
            </select>          </td>
          <td> {Gold}:
            <input type="text" name="txt_gold"  maxlength="5" class="fm"  />
            Tên liên minh: 
            <input style="display:none;" type="text" name="txtAllianceName"  maxlength="50" class="fm" disabled="disabled" id="txt_AlliName"/>
            </td>
        </tr>
        <tr>
          <td><input type="submit" name="submit_player" id="" value="{give}" class="fm" /> </td>          
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript">
function OnchangeOpt()
{
    if(document.getElementById('opt').value == 5){
        document.getElementById('txt_AlliName').disabled="";
        document.getElementById('txt_AlliName').style.display="";
    }
    else document.getElementById('txt_AlliName').disabled="disabled";
}
</script>