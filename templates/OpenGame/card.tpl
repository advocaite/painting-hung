<div id="box1">
  <div id="box2">
    <form method="post" action="card.php">
      <table>
        <tr>
          <td>Mã thẻ</td>
          <td><input type="text" name="txt_code" value="{code}"></td>
        </tr>
        <tr>
          <td>Mã pin</td>
          <td><input type="text" name="txt_pin" value="{pin}"></td>
        </tr>
        <tr>
          <td><img src="includes/captcha_security_images.php?width=100&height=40&characters=5" /> </td>
          <td></td>
        </tr>
        <tr>
          <td> Mã bảo vệ: </td>
          <td><input id="security_code" name="security_code" type="text" value="{security_code}" />
          </td>
        </tr>
      </table>
      <input type="submit" name="ok_card" value="{ok}" />
    </form>
    <br />
    <span style="color:#FF9900">{message}</span>
  </div>
</div>
</div>
