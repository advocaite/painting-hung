<div id="box1">
  <div id="box2">
    <h1>{valu_allain}</h1>
    <p class=\"title_menu\"><a href="allianz.php?aid={ally_id}">{Overview}</a> | <a href="allianz.php?s=10&aid={ally_id}">{giao_tranh}</a> | <a href="allianz.php?s=11&aid={ally_id}">{News}</a> | <span class="selected"><a href="allianz.php?s=12&aid={ally_id}">{Options}</a></span></p>
   <form method="POST" action="allianz.php?event=1&act=2">
   		 <table class="tbg" style="width: 50%;" cellpadding="2" cellspacing="1">
        <tbody>
          <tr class="rbg">
            <td colspan="2">{Assign to position}</td>
          </tr>
		  <input type="hidden" name="user_id" value="{user_id}" />
		  <input type="hidden" name="user_name" value="{user_name}" />
		  <tr>
            <td>{Name}:</td>
			<td class="s7">{user_name}</td>
          </tr>
  		  <tr>
            <td>{Position}:</td>
			<td class="s7"><input type="text" name="position_name" value="{position}" ></td>
          </tr>

		  <tr class="rbg">
            <td colspan="2">{Assign rights}</td>
          </tr>
          <tr>
            <td align="center"><input name="assign_to_position" value="1" type="checkbox" {assign_to_position_checked}></td>
            <td class="s7">{Assign to position}</td>
          </tr>         
		  <tr>
            <td align="center"><input name="change_name" value="2" type="checkbox" {change_name_checked}></td>
            <td class="s7">{Change name}</td>
          </tr>         
          <tr>
            <td align="center"><input name="kick_player" value="3" type="checkbox" {kick_player_checked}></td>
            <td class="s7">{Kick player}</td>
          </tr>
		   <tr>
            <td align="center"><input name="change_des" value="4" type="checkbox" {change_des_checked}></td>
            <td class="s7">{Change alliance description}</td>
          </tr>
          <tr>
            <td align="center"><input name="diplomacy" value="5" type="checkbox" {diplomacy_checked}></td>
            <td class="s7">{Alliance diplomacy}</td>
          </tr>		
           <tr>
            <td align="center"><input name="igm_to_member" value="7" type="checkbox" {igm_to_member_checked}></td>
            <td class="s7">{IGMs to every alliance member}</td>
          </tr> 
          <tr>
            <td align="center"><input name="invite_player" value="6" type="checkbox" {invite_player_checked}></td>
            <td class="s7">{Invite a player into the alliance}</td>
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
