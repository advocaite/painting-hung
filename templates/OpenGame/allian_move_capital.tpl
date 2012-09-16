<div id="box1">
  <div id="box2">
    <h1>{valu_allain}</h1>
    <p class="title_menu"><a href="allianz.php">{Overview}</a> | <a href="allianz.php?s=10">{giao_tranh}</a> | <a href="allianz.php?s=11">{News}</a> | <span class="selected"><a href="allianz.php?s=12">{Options}</a></span></p>
    <form method="post" action="allianz.php?event=7&act=1">
      <table class="tbg" style="width: 50%;" cellpadding="2" cellspacing="1">
        <tbody>
          <tr class="rbg">
            <td colspan="2">{Options}</td>
          </tr>
          <tr>
            <td colspan="2" class="s7"><a href="allianz.php?event=1">{assign_to_position}</a></td>
          </tr>
          <tr>
            <td colspan="2" class="s7"><a href="allianz.php?event=2">{change_name}</a></td>
          </tr>
          <tr>
            <td colspan="2" class="s7"><a href="allianz.php?event=3">{kick_player}</a></td>
          </tr>
          <tr>
            <td colspan="2" class="s7"><a href="allianz.php?event=4">{change_des}</a></td>
          </tr>
          <tr>
            <td colspan="2" class="s7"><a href="allianz.php?event=5">{allian_diplomacy}</a></td>
          </tr>
          <tr>
            <td colspan="2" class="s7"><a href="allianz.php?event=6">{invite_player}</a></td>
          </tr>
          <tr>
            <td colspan="2" class="s7 selected"><a href="allianz.php?event=7">{move_capital} </a> </td>
          </tr>
        <input type="hidden" name="aid" id="aid" value="{ally_id}" />
        <td colspan="2" class="s7"><a href="allianz.php?event=8">{Quit alliance}</a></td>
        </tr>
        </tbody>
        
      </table>
      <br />
      <b style="color:#FF6600">{message}</b>
      <table cellspacing="1" cellpadding="2" class="tbg" style="margin-top:5px">
        <tr>
          <td class="rbg" colspan="4">{Villages}</td>
        </tr>
        <tr>
          <td >{Name}</td>
          <td >{Inhabitants}</td>
          <td >{Coordinates}</td>
          <td >{Choise capital}</td>
        </tr>
        {view_village_list}
      </table>
      <p align="center" style="width:100%">
        <input type="image" src="{images}" value="" tabindex="3">
      </p>
    </form>
  </div>
</div>
</div>
