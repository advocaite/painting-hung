<script language="javascript">
function date_onkeypress() 
{
	if (window.event.keyCode < 48 || 57 < window.event.keyCode)
	{
		window.event.keyCode = 0 //nếu phím không phải là số thì bỏ đi
	}		
}
</script>

<div id="box1">
  <div id="box2">
    <h1>{Player profile}</h1>
    <p class="title_menu"> <a href="profile.php">{Overview}</a> | <span class="selected"><a href="profile.php?s=1">{Edit Profile}</a></span> | <a href="profile.php?s=3">{Option}</a></p>
    <form name="frm_profile_edit" method="POST" action="profile.php?s=1&act=1">
      <input type="hidden" name="d" id="d" value="{d}">
      <input type="hidden" name="mm" id="mm" value="{mm}" />
      <input type="hidden" name="y" id="y" value="{y}">
      <p>
      <table cellspacing="1" cellpadding="2" class="tbg">
        <tr>
          <td class="rbg" colspan="3">{Player} {player}</td>
        </tr>
        <tr>
          <td colspan="2">{Details}:</td>
          <td width="35%">{Description} I:</td>
        </tr>
        <tr>
          <td colspan="2"></td>
          <td></td>
        </tr>
        <tr>
          <td width="17%" class="s7">{Birthday}:</td>
          <td width="48%" class="s7"><input type="text" class="fm" name="date" id="date" maxlength="2" size="5" value="{date}" onkeypress="date_onkeypress();">
            <select name="month" size="" class="fm" id="month">
              <option value="0" {select_0}>Chọn tháng</option>
              <option value="1" {select_1}>{Jan}</option>
              <option value="2" {select_2}>{Feb}</option>
              <option value="3" {select_3}>{Mar}</option>
              <option value="4" {select_4}>{Apr}</option>
              <option value="5" {select_5}>{May}</option>
              <option value="6" {select_6}>{June}</option>
              <option value="7" {select_7}>{July}</option>
              <option value="8" {select_8}>{Aug}</option>
              <option value="9" {select_9}>{Sep}</option>
              <option value="10" {select_10}>{Oct}</option>
              <option value="11" {select_11}>{Nov}</option>
              <option value="12" {select_12}>{Dec}</option>
            </select>
            <input type="text" class="fm" name="year" id="year" maxlength="4" size="5" value="{year}" onkeypress="date_onkeypress();">
          </td>
          <td rowspan="11"><textarea name="description"   cols="27" rows="12" class="f10 fm_text">{description_edit}</textarea></td>
        </tr>
        <tr class="s7">
          <td>{Gender}:</td>
          <td><input type="Radio" name="sex" value="0" {check_male} />
            {m}
            <input type="Radio" name="sex" value="1" {check_female}>
            {f} </td>
        </tr>
        <tr class="s7">
          <td>{Position}:</td>
          <td><select name="city" id="city" class="fm">
              <option value="0" {select_0}>Không xác định</option>
              <option value="1" {select_1}>An Giang</option>
              <option value="2" {select_2}>Bà Rịa - Vũng Tàu</option>
              <option value="3" {select_3}>Bắc Giang</option>
              <option value="4" {select_4}>Bắc Kạn</option>
              <option value="5" {select_5}>Bạc Liêu</option>
              <option value="6" {select_6}>Bắc Ninh</option>
              <option value="7" {select_7}>Bến Tre</option>
              <option value="8" {select_8}>Bình Định</option>
              <option value="9" {select_9}>Bình Dương</option>
              <option value="10" {select_10}>Bình Phước</option>
              <option value="11" {select_11}>Bình Thuận</option>
              <option value="12" {select_12}>Cà Mau</option>
              <option value="13" {select_13}>Cần Thơ</option>
              <option value="14" {select_14}>Cao Bằng</option>
              <option value="15" {select_15}>Đà Nẵng</option>
              <option value="16" {select_16}>Đăk Lăk</option>
              <option value="17" {select_17}>Đăk Nông</option>
              <option value="18" {select_18}>Điện Biên</option>
              <option value="19" {select_19}>Đồng Nai</option>
              <option value="20" {select_20}>Đồng Tháp</option>
              <option value="21" {select_21}>Gia Lai</option>
              <option value="22" {select_22}>Hà Giang</option>
              <option value="23" {select_23}>Hà Nam</option>
              <option value="24" {select_24}>Hà Nội</option>
              <option value="25" {select_25}>Hà Tây</option>
              <option value="26" {select_26}>Hà Tĩnh</option>
              <option value="27" {select_27}>Hải Dương</option>
              <option value="28" {select_28}>Hải Phòng</option>
              <option value="29" {select_29}>Hậu Giang</option>
              <option value="30" {select_30}>Hoà Bình</option>
              <option value="31" {select_31}>Hưng Yên</option>
              <option value="32" {select_32}>Khánh Hoà</option>
              <option value="33" {select_33}>Kiên Giang</option>
              <option value="34" {select_34}>Kon Tum</option>
              <option value="35" {select_35}>Lai Châu</option>
              <option value="36" {select_36}>Lâm Đồng</option>
              <option value="37" {select_37}>Lạng Sơn</option>
              <option value="38" {select_38}>Lào Cai</option>
              <option value="39" {select_39}>Long An</option>
              <option value="40" {select_40}>Nam Định</option>
              <option value="41" {select_41}>Nghệ An</option>
              <option value="42" {select_42}>Ninh Bình</option>
              <option value="43" {select_43}>Ninh Thuận</option>
              <option value="44" {select_44}>Phú Thọ</option>
              <option value="45" {select_45}>Phú Yên</option>
              <option value="46" {select_46}>Quảng Bình</option>
              <option value="47" {select_47}>Quảng Nam</option>
              <option value="48" {select_48}>Quảng Ngãi</option>
              <option value="49" {select_49}>Quảng Ninh</option>
              <option value="50" {select_50}>Quảng Trị</option>
              <option value="51" {select_51}>Sóc Trăng</option>
              <option value="52" {select_52}>Sơn La</option>
              <option value="53" {select_53}>Tây Ninh</option>
              <option value="54" {select_54}>Thái Bình</option>
              <option value="55" {select_55}>Thái Nguyên</option>
              <option value="56" {select_56}>Thanh Hoá</option>
              <option value="57" {select_57}>Thừa Thiên - Huế</option>
              <option value="58" {select_58}>Tiền Giang</option>
              <option value="59" {select_59}>TP Hồ Chí Minh</option>
              <option value="60" {select_60}>Trà Vinh</option>
              <option value="61" {select_61}>Tuyên Quang</option>
              <option value="62" {select_62}>Vĩnh Long</option>
              <option value="63" {select_63}>Vĩnh Phúc</option>
              <option value="64" {select_64}>Yên Bái</option>
              <option value="65" {select_65}>Ngoài Việt Nam</option>
            </select>
          </td>
        </tr>
        <tr class="s7">
          <td colspan="2"></td>
        </tr>
        <tr class="s7">
          <td>{Village name}:</td>
          <td><select name="vilage_name_list_edit" class="fm">
              <option selected="selected">{Select village to edit}</option>
             {vilage_name_list_edit}
            </select>
            <input type="Text" name="village_name" id="village_name" size="15" maxlength="15" class="fm" value="{village_name_parse}" />
          </td>
        </tr>
		<tr class="s7">
          <td>{Phone}:</td>
          <td><input name="phone" type="text" maxlength="13" class="fm" value="{phone}" /></td>
        </tr>
        <tr>
          <td colspan="2">{Description} II:</td>
        </tr>		
        <tr>
          <td colspan="2"><textarea name="sign" cols="27" rows="4" class="f10">{sign_edit}</textarea></td>
        </tr>
      </table>
      <b style="color:#FF9900">{message}</b>
      <p align="center">
        <!--<input type="submit" name="ok_profile_edit" id="ok_profile_edit" value="{ok}">-->
        <input type="image" src="{images}" value="" tabindex="3">
      </p>
      <p align="center">
        </input>
      </p>
    </form>
  </div>
</div>
</div>
