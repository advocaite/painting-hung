<div id="box1">
  <div id="box2">
    <h1>{valu_allain}</h1>
    <p class=\"title_menu\"><a href="allianz.php">{Overview}</a> | <a href="allianz.php?s=10">{giao_tranh}</a> | <a href="allianz.php?s=11">{News}</a> | <span class="selected"><a href="allianz.php?s=12">{Options}</a></span></p>
    <form method="POST" action="allianz.php?event=4&act=1">
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
            <td rowspan="50" class="slr3">
            	<textarea name="des_allian" cols="27" rows="16" class="f10 fm_text">{valu_des_edit}</textarea>
            </td>
          </tr>
          <tr class="s7">
            <td>{Name}:</td>
            <td>{valu_name}</td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
         <!-- <tr class="s7">
            <td>{Rank}:</td>
            <td width="25%">{valu_rank}</td>
          </tr>
          <tr class="s7">
            <td>{Points}:</td>
            <td>{valu_points}</td>
          </tr>-->
          <tr class="s7">
            <td>{Members}:</td>
            <td>{valu_members}</td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2" class="slr3">
            <textarea name="slogan_allian" cols="27" rows="6" class="f10 fm_text">{valu_slogan_edit}</textarea>
            </td>
          </tr>
        </tbody>
      </table>      
         	<!--<input name="ok_change_des" id="ok_change_des" value="{ok}" type="submit">-->
       <p align="center" style="width:100%">
       	<input type="image" src="{images}" value="" tabindex="3" />      
      </p>
    </form>
  </div>
</div>
</div>
