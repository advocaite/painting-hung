<div id="box1">
  <div id="box2">
    <h1>{valu_allain}</h1>
    <p class=\"title_menu\"><a href="allianz.php">{Overview}</a> | <a href="allianz.php?s=10">{giao_tranh}</a> | <a href="allianz.php?s=11">{News}</a> | <span class="selected"><a href="allianz.php?s=12">{Options}</a></span></p>
    <form method="post" action="allianz.php?event=5&act=1">		
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td width="60%" valign="top"><input type="hidden" name="a" value="6">
            <input type="hidden" name="o" value="6">
            <input type="hidden" name="s" value="5">
            <table cellspacing="1" cellpadding="2" class="tbg" style="width:100%;">
              <tr class="rbg">
                <td colspan="2">{Alliance diplomacy}</td>
              </tr>
              <tr class="s7">
                <td>{Alliance}</td>
                <td>
                	<input class="fm" type="text" name="allian_name" size="18" maxlength="18" value="{ally_name_to}">
                </td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr class="s7">
                <td colspan="2">
                <input type="Radio" name="diplomacy" value="1">
                  {Offer a confederation}</td>
              </tr>
              <tr class="s7">
                <td colspan="2"><input type="Radio" name="diplomacy" value="2">
                  {Offer non-aggression pact}</td>
              <tr class="s7">
                <td colspan="2"><input type="Radio" name="diplomacy" value="3">
                  {Declare war}</td>
              </tr>
            </table>
            <!--<input name="ok_submit_diplomacy" id="ok_submit_diplomacy" value="{ok}" type="submit">-->
            <p align="center" style="width:100%">
              <input type="image" src="{images}" value="" tabindex="3" /></p>
              <b style="color:#FF6600">{message}</b></td>
          <td width="40%" align="right" valign="top"><table cellspacing="1" cellpadding="2" class="tbg" style="width:90%;">
              <tr class="rbg">
                <td colspan="2">{Hint}:</td>
              </tr>
              <tr>
                <td colspan="2" class="jus">{Hint description}</td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td width="60%" valign="top"><table cellspacing="1" cellpadding="2" class="tbg">
              <tr class="rbg">
                <td colspan="3">{Own offers}</td>
              </tr>
              {view_own_offer_list}
            </table>
            <p>
            <table cellspacing="1" cellpadding="2" class="tbg">
              <tr class="rbg">
                <td colspan="3">{Foreign offers}</td>
              </tr>
              {view_foreign_offer_list}
            </table>
            <p>
            <table cellspacing="1" cellpadding="2" class="tbg">
              <tr class="rbg">
                <td colspan="2">{Existing relationships}</td>
              </tr>
              {view_exist_relation_list}
            </table></td>
          <td width="40%" align="right" valign="top"></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</div>
