<div id="box1">
  <div id="box2">
    <h1>{valu_allain}</h1>
    <p class=\"title_menu\"><a href="allianz.php">{Overview}</a> | <a href="allianz.php?s=10">{giao_tranh}</a> | <span class="selected"><a href="allianz.php?s=11">{News}</a></span> | <a href="allianz.php?s=12">{Options}</a></p>
    <form method="post" >
      <input name="name_allian_hid"  id="name_allian_hid" value="{name_allian_hid}" type="hidden">
      <input name="tag_allian_hid"  id="tag_allian_hid" value="{tag_allian_hid}" type="hidden">
      <table cellspacing="1" cellpadding="2" class="tbg">
        <tr class="rbg">
          <td colspan="4">{Alliance events}</td>
        </tr>
        <tr class="cbg1">
          <td>{sum}</td>
          <td>{Event}</td>
          <td>{Date}</td>
        </tr>
        {view_allian_news_list}
        <tr>
          <td colspan="3" align="right">{paging}</td>
        </tr>
      </table>
    </form>
  </div>
</div>
</div>
