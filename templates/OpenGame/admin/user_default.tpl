<div id="column-content">
    <div id="content">
        <div>{error_message}</div>
        <div>
            <h2 class="firstHeading">Account {Default}:</h2>
            <div>
                <form id="zonedef" name="zonedef" action="#" method="post">
                <table cellpadding="1" cellspacing="0" width="300" border="0">
                <tr>
                <td>Admin:</td>
                <td> x: </td>
                <td>0<input type="hidden" id="x1" name="x1" value="0" maxlength="4" size="1" /></td>
                <td>y: </td>
                <td>0<input type="hidden" id="y1" name="y1" value="0" maxlength="4" size="1" /></td>
                </tr>
                <tr>
                <td>GameMaster1:</td>
                <td> x: </td>
                <td>0<input type="hidden" id="x2" name="x2" value="0" maxlength="4" size="1" /></td>
                <td>y: </td>
                <td>1
                  <input type="hidden" id="y2" name="y2" value="1" maxlength="4" size="1" /></td>
                </tr>
                <tr>
                <td>GameMaster2:</td>
                <td>x: </td>
                <td>1
                  <input type="hidden" id="x3" name="x3" value="1" maxlength="4" size="1" /></td>
                <td>y: </td>
                <td>0
                  <input type="hidden" id="y3" name="y3" value="0" maxlength="4" size="1" /></td>
                </tr>
                <tr>
                <td>GameMaster3:</td>
                <td>x: </td>
                <td>0
                  <input type="hidden" id="x4" name="x4" value="0" maxlength="4" size="1" /></td>
                <td>y: </td>
                <td>-1
                  <input type="hidden" id="y4" name="y4" value="-1" maxlength="4" size="1" /></td>
                </tr>
                <tr>
                <td>GameMaster4:</td>
                <td>x: </td>
                <td>-1
                  <input type="hidden" id="x5" name="x5" value="-1" maxlength="4" size="1" /></td>
                <td>y: </td>
                <td>0
                  <input type="hidden" id="y5" name="y5" value="0" maxlength="4" size="1" /></td>
                </tr>
                </table>
                <p><input type="submit" id="submit" name="default" value="{Create}" class="fm" /></p>
                </form>
            </div>
        </div>
        <hr>
        <form name="form_acc_custom" method="post" action="">
        <table width="100%">
            <tr>
                <td>Account tuỳ chọn</td>
            </tr>
            <tr>
                <td colspan="2">Name: <input type="text" name="txt_name" value="" size="31" /></td>                
                <td>Nation: <input type="text" name="txt_nation" value="" size="3" /></td>
            </tr>
            <tr>
                <td width="150px">Toạ độ x: <input type="text" name="txt_x" value="" size="3" /></td>
                <td width="150px">Toạ độ y: <input type="text" name="txt_y" value="" size="3" /></td>                
                <td>Level: <input type="text" name="txt_level" value="" size="4" /></td>
            </tr>
            <tr align="center">
                <td colspan="2"><input type="submit" id="sub_custom" name="custom" value="{Create}" class="fm" /><td>
            </tr>
        </table>
        </form>
    </div>
</div>
