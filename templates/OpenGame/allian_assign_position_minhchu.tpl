<div id="box1">
  <div id="box2">
    <h1>{valu_allain}</h1>
    <p class="title_menu"><a href="allianz.php">{Overview}</a> | <a href="allianz.php?s=10">{giao_tranh}</a> | <a href="allianz.php?s=11">{News}</a> | <span class="selected"><a href="allianz.php?s=12">{Options}</a></span></p>
    <form method="POST" action="allianz.php?event=9&act=1">
      <table cellspacing="1" cellpadding="2" class="tbg" style="width:50%;">
        <input type="hidden" name="o" value="1">
        <input type="hidden" name="s" value="5">
        <tr class="rbg">
          <td colspan="2">{Assign to position_MinhChu}</td>
        </tr>
        <tr>
          <td colspan="2" class="jus">{Assign to position description}</td>
        </tr>
        <tr class="s7">
          <td>{Name}:</td>
          <td><input class="fm" type="text" name="a_name" size="25" maxlength="20" value="{to_username}"></td>
        </tr>
      </table>
      <p align="center" style="width:50%">
        <input type="image" src="{images}" value="" tabindex="3">
      </p>
      <b style="color:#FF6600">{message}</b>
    </form>
  </div>
</div>
</div>
