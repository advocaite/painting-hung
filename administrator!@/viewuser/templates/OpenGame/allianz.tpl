<div id="box1">
  <div id="box2">
    <h1>{valu_allain}</h1>
    <p class="txt_menue" {not_menu}>
			<span class="selected"><a href="allianz.php">{overview_menu}</a></span> | 
			<a href="allianz.php?s=10">{giao_tranh_menu}</a> | 
			<a href="allianz.php?s=11">{news_menu}</a> | 
			<a href="allianz.php?s=12">{options_menu}</a>
	</p>
    <form method="post">
      <input type="hidden" name="bit" id="bit" value="{bit}" />
      <input type="hidden" name="ally_id" id="ally_id" value="{ally_id}" />
      {br}
      <table class="tbg" cellpadding="2" cellspacing="1">
        <tbody>
          <tr>
            <td class="rbg" colspan="3">{Alliance}</td>
          </tr>
          <tr>
            <td colspan="2" width="50%">{Details}:</td>
            <td width="50%">{Description}</td>
          </tr>
          <tr>
            <td colspan="2"></td>
            <td></td>
          </tr>
          <tr>
            <td class="s7">{Tag}:</td>
            <td class="s7">{valu_tag}</td>
            <td rowspan="50" class="slr3" valign="bottom"> {valu_des} <br />
              <br/>{ally_relation_1}<br/>{ally_relation_list_1}
              {ally_relation_2}<br/>{ally_relation_list_2}
              {ally_relation_3}<br/>{ally_relation_list_3}
            </td>
          </tr>
          <tr class="s7">
            <td>{Name}:</td>
            <td>{valu_name}</td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>         
          <!--<tr class="s7">
            <td>{Points}:</td>
            <td>{valu_points}</td>
          </tr>-->
          <tr class="s7">
            <td>{Members}:</td>
            <td>{valu_members}</td>
          </tr>
        {valu_position}
        <tr>
          <td colspan="2" class="slr3">
          {valu_slogan}
          </td>
        </tr>
        </tbody>
        
      </table>
      <p></p>
      <table class="tbg" cellpadding="2" cellspacing="1">
        <tbody>
          <tr class="rbg">
            <td width="6%">&nbsp;</td>
            <td width="44%">{Player}</td>
            <td width="25%">{Population}</td>
            <td width="19%">{Villages}</td>
            <td width="6%">{online_status_1}</td></tr>
        {valu_list}
        </tbody>
        
      </table>
    </form>
  </div>
</div>
</div>
