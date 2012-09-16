<div id="box1">
  <div id="box2">
    <h1>{Player profile}</h1>
    <!--{profile_menu}-->
    <p class="txt_menue" {not_menu}> <span class="selected"> <a href="profile.php">{overview_menu}</a></span> | <a href="profile.php?s=1">{edit_profile_menu}</a> | <a href="profile.php?s=3">{option_menu}</a> </p>
    <table cellspacing="1" cellpadding="2" class="tbg">
      <tr>
        <td class="rbg" colspan="3">{Player} {player}</td>
      </tr>
      <tr>
        <td width="50%" colspan="2">{Details}:</td>
        <td width="50%">{Description}:</td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td></td>
      </tr>
      <tr>
        <td class="s7">{Rank}:</td>
        <td class="s7">{rank_user}</td>
        <td rowspan="50" class="slr3" align="center">{description} </td>
      </tr>
      <tr class="s7">
        <td>{Tribe}:</td>
        <td>{tribe}</td>
      </tr>
      <tr class="s7">
        <td>{Alliance}:</td>
        <td><a href="allianz.php?aid={ally_id}">{ally}</a></td>
      </tr>
      <tr class="s7">
        <td>{Birthday}:</td>
        <td>{birthday}</td>
      </tr>
      <tr class="s7">
        <td>{Sex}:</td>
        <td>{sex}</td>
      </tr>
      <tr class="s7">
        <td>{Villages}:</td>
        <td>{count_villages}</td>
      </tr>
      <tr class="s7">
        <td>{Population}:</td>
        <td>{population_player}</td>
      </tr>
      <tr class="s7">
        <td>{Location}:</td>
        <td>{location}</td>
      </tr>  
	  <tr class="s7">
        <td>{Phone}:</td>
        <td>{phone}</td>
      </tr>     
      <tr class="s7"> 
      	<td>{change_profile_or_write_message}</td>
		<td>{invite_ally}</td>
      </tr>
      <tr>
        <td colspan="2" class="slr3">{sign}</td>
      </tr>
    </table>
    </p>
	{profile_medal}	
    <p>
    <table cellspacing="1" cellpadding="2" class="tbg">
      <tr>
        <td class="rbg" colspan="3">{Villages} [{sum_village}]</td>
      </tr>
      <tr>
        <td >{Name}</td>
        <td >{Inhabitants}</td>
        <td >{Coordinates}</td>
      </tr>
      {view_village_list}
    </table>
    </p>
  </div>
</div>
</div>
