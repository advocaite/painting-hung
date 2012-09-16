
<script src="js/cntchar.js" type="text/javascript"></script>
<script src="js/win.js" type="text/javascript"></script>

<br />
<center>
<form action="messages.php?mode=write&id={id}" method="post">
 <table width="519">
   <tr>
   <td class="c" colspan="2">{Send_message2}</td>
  </tr>
  <tr>
   <th>{UserName1}</th>
   <th><input type="text" name="to" size="40" value="{to}" /></th>
  </tr>
  <tr>
   <th>{Subject2}</th>
   <th>
    <input type="text" name="subject" size="40" maxlength="40" value="{subject}" />
   </th>
  </tr>
  <tr>
   <th>
    {Message2}(<span id="cntChars">{02} </span> {5000 Charecters2}
   </th>
   <th>
    <textarea name="text" cols="40" rows="10" size="100" onkeyup="javascript:cntchar(500)">{text}</textarea>
   </th>
  </tr>
  <tr>
   <th colspan="2"><input type="submit" value="{SendBtn2}" /></th>
  </tr>
   </table>
</form>
</center>
