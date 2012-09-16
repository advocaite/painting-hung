<div id="box1">
  <div id="box2">
    <h1>{valu_allain}</h1>
    <p class=\"title_menu\"><a href="allianz.php?aid={ally_id}">{Overview}</a> | <a href="allianz.php?s=10&aid={ally_id}">{giao_tranh}</a> | <a href="allianz.php?s=11&aid={ally_id}">{News}</a> | <span class="selected"><a href="allianz.php?s=12&aid={ally_id}">{Options}</a></span></p>
   <form method="POST" action="allianz.php?event=9&act=2">
   		 <table class="tbg" style="width: 50%;" cellpadding="2" cellspacing="1">
        <tbody>
          <tr class="rbg">
            <td colspan="2">{Assign to position_MinhChu}</td>
          </tr>
		  <input type="hidden" name="user_id" value="{user_id}" />
		  <input type="hidden" name="user_name" value="{user_name}" />
		  <tr>
            <td>{Name}:</td>
			<td class="s7">{user_name}</td>
          </tr>

		  <tr class="rbg">
            <td colspan="2">{Xác nhận mật khẩu}</td>
          </tr>
          <tr class="s7">
            <td>{Password}:</td>
            <td><input class="fm" name="password" id="password" size="15" maxlength="20" type="password"></td>
          </tr>
          <input name="quit" value="8" type="hidden">
          <tr>
           <td align="center" class="s7" colspan="2">
             <input type="image" src="{images}" value="" tabindex="3">
           </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
</div>
