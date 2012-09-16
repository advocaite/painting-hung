<div id="box1">
  <div id="box2">
    <h1>{valu_allain}</h1>
    <p class="title_menu"><a href="allianz.php">{Overview}</a> | <span class="selected"><a href="allianz.php?s=10">{giao_tranh}</a></span> | <a href="allianz.php?s=11">{News}</a> | <a href="allianz.php?s=12">{Options}</a></p>
    <form method="post" >
      <table cellspacing="1" cellpadding="2" class="tbg">
        <tr class="rbg">
          <td colspan="4">{Military events}</td>
        </tr>
        <tr class="cbg1">
          <td>{sum}</td>
          <td>{Player}</td>
          <td>{Ally name}</td>
          <td>{Date}</td>
        </tr>
        {view_attack_list}
        <tr>
        	<td colspan="4" align="right">{paging}</td>
        </tr>
       </table>
    </form>
  </div>
</div>
</div>
