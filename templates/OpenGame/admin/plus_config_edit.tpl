<div id="column-content">
  <div id="content">
    <form method="POST">
      <fieldset><center><b style="color:#FF0000">PLUS CONFIGURATION</b></center></fieldset>
      <table  width="50%" cellpadding="2" cellspacing="1" class="tbg"  align="center">
        <tr class="rbg">
          <th>No</th>
          <th>Name</th>
          <th>Duration</th>
          <th>Asu</th>
          <th>Edit</th>
        </tr>
        {list_plus_config}
      </table>
      <table align="center" width="50%" border="0">
      	<tr>
        	<td width="50%" align="center">
           	  <input type="text" name="name_edit" value="{name_edit}" readonly="readonly" size="20" class="fm" />
          </td>
          <td width="21%" align="center">
            <input type="text" name="duration_edit" value="{duration_edit}" size="5" maxlength="2" class="fm" />
          </td>
          <td width="29%" align="center">
            <input type="text" name="asu_edit" value="{asu_edit}" size="5" maxlength="2" class="fm" />
          </td>
        </tr>
        <tr>
        	<td colspan="3" align="center"><input type="submit" name="update" value="{Update}" class="fm" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
