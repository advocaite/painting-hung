<div id="column-content">
  <div id="content">
       <table width="100%" cellspacing="1" cellpadding="0" class="tbg">
       <tbody>
          <tr  class="rbg" height="30">
            <th align="center">STT</th>
            <th align="center">Intro News</th>
            <th align="center">Url</th>
            <th align="center">Create Date</th>
          </tr>
        {list_news_login}
        </tbody>
      </table>
  <hr>
  <p>Add new news</p>
      <form name="form_add_news" method="post" action="">
        <table width="100%">
            <tr align="left">
                <td>Intro Text News: <input type="text" name="txtIntro_news" value="" size="100" /></td>
            </tr>
            <tr align="left">
                <td>Url Full News: <input type="text" name="txtUrl_news" value="http://" size="100" /></td>
            </tr>
            <tr align="center">
                <td><input type="submit" Value="Add News" name="add_news" class="fm"/></td>
            </tr>
        </table>
      </form>
  </div>
</div>