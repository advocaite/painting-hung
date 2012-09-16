<div id="box1">
  <div id="box2">
  <table border="0" cellpadding="2" cellspacing="1">
  <tr>
    <td colspan="5">{current_gold}</td>
  </tr>
  <tr>
    <td width="200" align="right">{Reward}</td>
    <td align="left" width="200">:&nbsp;<span style="color:#fbaf03"><b>{total_gold}&nbsp;{Gold}</b></span></td>
    <td width="150" align="right">{Deposit}</td>
    <td align="left" width="200">:&nbsp;<span style="color:#fbaf03"><b>{total_gold_deposit}&nbsp;{Gold}</b></span></td>
    <td width="200" align="center"><a href="http://asu.ingame.vn/" target="_blank">{deposit asu}</a></td>
  </tr>
  <tr>
   
  </tr>
  </table>
  <table cellspacing="1" cellpadding="2" class="tbg">
      <tr class="rbg">
        <td colspan="4">{Plus function}</td>
      </tr>
      <tr class="cbg1">
        <td width="48%">{Description}</td>
        <td width="15%">{Duration}</td>
        <td width="14%">{Costs}</td>
        <td width="17%">{Action}</td>
      </tr>     
      <tr>
        <td colspan="4"></td>
      </tr>
      <tr style="margin-top:2px; margin-bottom:2px">
        <td class="s7"> +<b>25</b>% <img class="res" src="images/un/r/1.gif"> {Production: Lumber}
        <div class="f8">{lumber_time}</div></td>
        <td>{lumber_duration} {Days}</td>
        <td><b>{lumber_asu}</b> {Gold}</td>
        <td><div align="center"><span>{lumber_action}</span></div></td>
      </tr>
      <tr>
        <td class="s7">+<b>25</b>% <img class="res" src="images/un/r/2.gif"> {Production: Clay}          
          <div class="f8">{clay_time}</div></td>
        <td>{clay_duration} {Days}</td>
        <td><b>{clay_asu} </b>{Gold}</td>
        <td><div align="center"><span>{clay_action}</span></div></td>
      </tr>
      <tr>
        <td class="s7">+<b>25</b>% <img class="res" src="images/un/r/3.gif"> {Production: Iron}
        <div class="f8">{iron_time}</div></td>
        <td>{iron_duration} {Days}</td>
        <td><b>{iron_asu}</b> {Gold}</td>
        <td><div align="center">{iron_action}</div></td>
      </tr>
      <tr>
        <td class="s7">+<b>25</b>% <img class="res" src="images/un/r/4.gif"> {Production: Crop}
        <div class="f8">{crop_time}</div></td>
        <td>{crop_duration} {Days}</td>
        <td><b>{crop_asu}</b> {Gold}</td>
        <td><div align="center">{crop_action}</div></td>
      </tr>
      <tr>
        <td colspan="4"></td>
      </tr>
      <tr>
        <td class="s7">+<b>10</b>% {Attack value}
        <div class="f8">{attack_time}</div></td>
        <td>{attack_duration} {Days}</td>
        <td><b>{attack_asu} </b>{Gold}</td>
        <td><div align="center">{attack_action}</div></td>
      </tr>
      <tr>
        <td class="s7">+<b>10</b>% {Defense value}
        <div class="f8">{defence_time}</div></td>
        <td>{defence_duration} {Days}</td>
        <td><b>{defence_asu}</b> {Gold}</td>
        <td><div align="center">{defence_action}</div></td>
      </tr>
       <tr>
        <td class="s7">{dinh_chien}
       		 <div class="f8">{dinh_chien_time}</div></td>
        <td>{dinh_chien_duration} {Days}</td>
        <td><b>{dinh_chien_asu}</b> {Gold}</td>
        <td><div align="center">{dinh_chien_action}</div></td>
      </tr>
      <tr>
        <td class="s7">{sms_attack}{sms_attack_ms}</td>
        <td>{immediate}</td>
        <td><b>{sms_attack_asu}</b> {Gold}/1SMS</td>
        <td><div align="center" ONMOUSEOVER="ddrivetip('{sms_attack_title}');" ONMOUSEOUT="hideddrivetip();">{sms_action}</div></td>
      </tr>
      <tr>
        <td colspan="4"></td>
      </tr>
	  <tr>
        <td class="s7">{Build value}<div class="f8">{build_time}</div></td>
        <td>{build_duration} {Days}</td>
        <td><b>{build_asu}</b> {Gold}</td>
        <td><div align="center">{build_action}</div></td>
      </tr>
      <tr>
        <td class="s7">{Complete construction}</td>
        <td>{immediately}</td>
        <td><b>{complete_asu}</b> {Gold}</td>
        <td><div align="center">{complete_action}</div></td>
      </tr>
   
      <tr>
        <td colspan="4"></td>
      </tr>
	  <tr>
        <td class="s7">{the_bai_xem}</td>
        <td>{mot_lan}</td>
        <td><b>{the_bai_1_asu}</b> {Gold}</td>
        <td><div align="center">{buy_action_1}</div></td>
      </tr>
      <tr>
        <td class="s7">{the_bai_user}</td>
        <td>{mot_lan}</td>
        <td><b>{the_bai_2_asu}</b> {Gold}</td>
        <td><div align="center">{buy_action_2}</div></td>
      </tr>
      <tr>
        <td class="s7">{the_bai_lien_minh}</td>
        <td>{mot_lan}</td>
        <td><b>{the_bai_3_asu}</b> {Gold}</td>
        <td><div align="center">{buy_action_3}</div></td>
      </tr>
		
    </table>
    </p>
    <p><b style='text-align:center; font-size:12px'>
    {msg}
    </b></p>
    </div>
</div>
</div>