<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="Tranh Hung, Game việt, game viet, trí tuệ việt, tri tue viet, game, webgame, web game, tranh hung, game chien thuat, game danh tran" name="keywords">
<meta content="Tranh Hung, Game việt, game viet, trí tuệ việt, tri tue viet, game, webgame, web game, tranh hung, game chien thuat, game danh tran" name="description">
<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
<meta name="vs_defaultClientScript" content="JavaScript">
<link rel="stylesheet" href="css/games.css" type="text/css" />
<script src="js/unx.js" type="text/javascript"></script>
<script src="js/pngfix.js" type="text/javascript"></script>
<title></title>
</head>
<body onload="loadForm();">
<script type="text/javascript">
function loadForm(){
	an_change(); 
	dn_change(); 
	
	
	switch_hero('d', document.getElementById('dhu').value, document.getElementById('dh_tid').value); 
	
	switch_hero('a', document.getElementById('ahu').value, document.getElementById('ah_tid').value);
	
}

function an_change(){
	xform = document.forms[0];
	
	for(i=0; i<11; i++){
		elt_show(document.getElementById('ai_0_'+i), xform.ans0.checked);
		elt_show(document.getElementById('ai_1_'+i), xform.ans1.checked);
		elt_show(document.getElementById('ai_2_'+i), xform.ans2.checked);
	}
	
	change_ant(xform.ans0.checked, "Arabia");
	change_ant(xform.ans1.checked, "Mongo");
	change_ant(xform.ans2.checked, "Sunda");

	select_ahr();
}

function dn_change(){
	xform = document.forms[0];
	
	for(i=0; i<11; i++){
		elt_show(document.getElementById('di_0_'+i), xform.dns0.checked);
		elt_show(document.getElementById('di_1_'+i), xform.dns1.checked);
		elt_show(document.getElementById('di_2_'+i), xform.dns2.checked);
	}
	
	change_dnt(xform.dns0.checked, "Arabia");
	change_dnt(xform.dns1.checked, "Mongo");
	change_dnt(xform.dns2.checked, "Sunda");
	
	select_dhr();
}

function select_ahr(){
	xform = document.forms[0];
	elt_show(document.getElementById('ahr'), xform.ah.checked);	
	
	elt_show(document.getElementById('a_hero_0'), xform.ans0.checked);	
	elt_show(document.getElementById('a_hero_1'), xform.ans1.checked);
	elt_show(document.getElementById('a_hero_2'), xform.ans2.checked);
	
	
}

function select_dhr(){
	xform = document.forms[0];
	elt_show(document.getElementById('dhr'), xform.dh.checked);	
	
	elt_show(document.getElementById('d_hero_0'), xform.dns0.checked);	
	elt_show(document.getElementById('d_hero_1'), xform.dns1.checked);
	elt_show(document.getElementById('d_hero_2'), xform.dns2.checked);
}

function elt_show(elt, show) {	
	elt.style.display = show ? '' : 'none';
}

function change_ant(show, title) {	

	if(show) {
		document.getElementById('ant').innerHTML=title;
	}
}

function change_dnt(show, title) {	

	if(show) {
		document.getElementById('dnt').innerHTML=title;
	}
}

function switch_hero(side, idx, tid) {
	if (idx !== undefined) {
		her_input = document.getElementById(side+'hu');
		
		for(i = 0; i < 3; i++) {
			var xtable = document.getElementById(side+'_hero_'+i)
			xtable.rows[0].cells[her_input.value].style.backgroundColor = 'white';
			xtable.rows[0].cells[idx].style.backgroundColor = '#ccc';
		}
		
		her_input.value = idx;

//		battle[side+'hu'] = idx;
		
		document.getElementById(side+'h_tid').value = tid;
//alert(document.getElementById('dhu').value);
//		input_changed({target:her_input});
		
	}
	
	document.getElementById(side+'hr').value = idx;
}
</script>

<form id="form1" name="form1" method="post" action="ws.php">
<input type='hidden' name='ahu' id='ahu' value='{ahu}'/>
<input type='hidden' name='dhu' id='dhu' value='{dhu}'/>

<input type='hidden' name='ah_tid' id='ah_tid' value='{ah_tid}'/>
<input type='hidden' name='dh_tid' id='dh_tid' value='{dh_tid}'/>

<table cellpadding="2" cellspacing="1" class="tbg">
  <tr class="rbg">
    <td align="center" ><strong>Tập trận</strong></td>
  </tr>   
    <tr>
      <td class="s7">&nbsp;</td>
    </tr>
    <tr>
     <td class="s7"><table cellpadding="2" cellspacing="1" style="width:200px;"  align="center">
       <tr>
         <td align="left"><input type="radio" name="st" id="st0" value="3" {stc_0} />
           Đột kích</td>
         <td align="left"><input type="radio" name="st" id="st1" value="4" {stc_1} />
           Tử chiến</td>
       </tr>
     </table></td>
    </tr>
    
    <tr><td></td></tr>
    <tr>

    <td height="192" valign="top">    

	<table cellpadding="2" cellspacing="1" class="tbg">
		<tr class="cbg1">
        <td colspan="2" class="c2 b">Bên tấn công </td>
   	    </tr>
		<tr>
		  <td class="c2 b">
          	<input type="radio" name="ans" id="ans0" value="0" onclick="an_change()" {ans_c_0} />
    	    <a href="">Arabia</a>
            <input type="radio" name="ans" id="ans1" value="1" onclick="an_change()" {ans_c_1}/>
            <a href="">Mongo</a>
            <input type="radio" name="ans" id="ans2" value="2" onclick="an_change()" {ans_c_2}/>
            <a href="">Sunda</a></td>
	      <td class="b"><img src="images/icon/hero4.ico" alt="" />&nbsp; &nbsp;{hero}
    <input type="checkbox" name="ah" id="ah" value='1' onclick='select_ahr()' {ah_c}/></td>
		</tr>
		<tr>
        <td valign="top">
        
        <div id="as_0">
          <table cellpadding="2" cellspacing="1" class="tbg">  
  <tr class="unit">
  <td width="15%"><a href="#"><span id="ant"></span></a></td>
  <td><img src="images/icon/arabia/nguyetkiembinh.ico" title="Tiền binh" id="ai_0_0" ><img src="images/icon/mongo/tienbinh.ico" title="Tiền binh" id="ai_1_0" style="display:none;" /><img src="images/icon/sunda/xatienbinh.ico" title="Tiền binh" id="ai_2_0" style="display:none;" /></td>
  <td><img src="images/icon/arabia/tramtaubinh.ico" title="Hổ tinh binh" id="ai_0_1" ><img src="images/icon/mongo/hotinhbinh.ico" title="Hổ tinh binh" id="ai_1_1" style="display:none;" /><img src="images/icon/sunda/thietgiapbinh.ico" title="Hổ tinh binh" id="ai_2_1" style="display:none;" /></td>
  <td ><img src="images/icon/arabia/thiettinhbinh.ico" title="Cung thủ" id="ai_0_2" ><img src="images/icon/mongo/cungthu.ico" title="Cung thủ" id="ai_1_2" style="display:none;" /><img src="images/icon/sunda/mocbinh.ico" title="Cung thủ" id="ai_2_2" style="display:none;" /></td>
  <td><img src="images/icon/arabia/cungthu.ico" title="Cung tiễn binh" id="ai_0_3" ><img src="images/icon/mongo/cungtienbinh.ico" title="Cung tiễn binh" id="ai_1_3" style="display:none;" /><img src="images/icon/sunda/nobinh.ico" title="Cung tiễn binh" id="ai_2_3" style="display:none;" /></td>
  <td><img src="images/icon/arabia/daicungthu.ico" title="Hỏa thân tiễn" id="ai_0_4" ><img src="images/icon/mongo/hoathantien.ico" title="Hỏa thân tiễn" id="ai_1_4" style="display:none;" /><img src="images/icon/sunda/hoatienbinh.ico" title="Hỏa thân tiễn" id="ai_2_4" style="display:none;" /></td>
  <td><img src="images/icon/arabia/daoky.ico" title="Long đao kỵ" id="ai_0_5" ><img src="images/icon/mongo/longdaoky.ico" title="Long đao kỵ" id="ai_1_5" style="display:none;" /><img src="images/icon/sunda/loidaoky.ico" title="Long đao kỵ" id="ai_2_5" style="display:none;" /></td>
  <td><img src="images/icon/arabia/truongthuongky.ico" title="Đoạn hồn kỵ" id="ai_0_6" ><img src="images/icon/mongo/doanhonky.ico" title="Đoạn hồn kỵ" id="ai_1_6" style="display:none;" /><img src="images/icon/sunda/truongsonma.ico" title="Đoạn hồn kỵ" id="ai_2_6" style="display:none;" /></td>
  <td><img src="images/icon/arabia/thambinh.ico" title="Thám quân" id="ai_0_7" > <img src="images/icon/mongo/thamquan.ico" title="Thám quân" id="ai_1_7" style="display:none;" /><img src="images/icon/sunda/truyenquan.ico" title="Thám quân" id="ai_2_7" style="display:none;" /></td>
  <td><img src="images/icon/arabia/hoaphaoxa.ico" title="Hoả thạch xa" id="ai_0_8" ><img src="images/icon/mongo/hoathachxa.ico" title="Hoả thạch xa" id="ai_1_8" style="display:none;" /><img src="images/icon/sunda/chienphaoxa.ico" title="Hoả thạch xa" id="ai_2_8" style="display:none;" /></td>
  <td><img src="images/icon/arabia/tho.ico" title="Thợ" id="ai_0_9" ><img src="images/icon/mongo/tho.ico" title="Thợ" id="ai_1_9" style="display:none;" /><img src="images/icon/sunda/tho.ico" title="Thợ" id="ai_2_9" style="display:none;" /></td>
  <td><img src="images/icon/arabia/thuyetkhach.ico" title="Thuyết khách" id="ai_0_10" ><img src="images/icon/mongo/thuyetkhach.ico" title="Thuyết khách" id="ai_1_10"  style="display:none;" /><img src="images/icon/sunda/thuyetkhach.ico" title="Thuyết khách" id="ai_2_10" style="display:none;" /></td>
  <td><img src="images/icon/hero4.ico" title="Tướng" /></td>
  </tr>
  <tr>
    
    <td width="15%">Quân đội</td>
    <td><input class="fm" type="text" name="ast[0]" value="{as_0}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="ast[1]" value="{as_1}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="ast[2]" value="{as_2}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="ast[3]" value="{as_3}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="ast[4]" value="{as_4}" size="4" maxlength="10"></td>
  
    <td><input class="fm" type="text" name="ast[5]" value="{as_5}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="ast[6]" value="{as_6}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="ast[7]" value="{as_7}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="ast[8]" value="{as_8}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="ast[9]" value="{as_9}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="ast[10]" value="{as_10}" size="4" maxlength="10"></td>
    <td {ah_cl}>{as_11}</td>
    </tr>
  <tr>
    <td width="15%">Binh khí</td>
    <td class="c"><input class="fm" type="text" name="b[0]" value="{b_0}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[1]" value="{b_1}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[2]" value="{b_2}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[3]" value="{b_3}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[4]" value="{b_4}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[5]" value="{b_5}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[6]" value="{b_6}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[7]" value="{b_7}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[8]" value="{b_8}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[9]" value="{b_9}" size="1" maxlength="5" /></td>
    <td class="c"><input class="fm" type="text" name="b[10]" value="{b_10}" size="1" maxlength="5" /></td>
    <td >&nbsp;</td>
    </tr>
  <tr>
    <td width="15%">Hy sinh</td>
    <td {ac_0_0}>{asd_0}</td>
    <td {ac_0_1}>{asd_1}</td>
    <td {ac_0_2}>{asd_2}</td>
    <td {ac_0_3}>{asd_3}</td>
  
	<td {ac_0_4}>{asd_4}</td>
    <td {ac_0_5}>{asd_5}</td>
    <td {ac_0_6}>{asd_6}</td>
    <td {ac_0_7}>{asd_7}</td>
    <td {ac_0_8}>{asd_8}</td>
    <td {ac_0_9}>{asd_9}</td>
  
	<td {ac_0_10}>{asd_10}</td>
    <td {ac_0_11}>{asd_11}</td>
    </tr>
            
  <tr>
    <td width="15%">ASU (+10%)</td>
    <td class="s7" colspan="12"><input type="checkbox" name="a_asu" id="a_asu" value="1" {a_asu_c}/></td>
  </tr>
          </table>
          </div>          
          <!--<table cellpadding="2" cellspacing="1" style="width:250px;">
        	<tr> <td>ASU (+10%)</td><td><input type="checkbox" name="a_asu" id="a_asu" value="1" {a_asu_c}/></tr>
        </table>-->           </td>
    	<td valign="top">
        <table cellpadding="2" cellspacing="1" class="tbg" id="ahr" style="display:none; text-align:left;">
        	<tr>
            	<td colspan="2">
        <table class="carcass" cellpadding="3" id="a_hero_0" style="display:"><tr>
		<td onclick="switch_hero('a', 0, 12)"><img src="images/icon/arabia/nguyetkiembinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 1, 13)"><img src="images/icon/arabia/tramtaubinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 2, 14)"><img src="images/icon/arabia/thiettinhbinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 3, 15)"><img src="images/icon/arabia/cungthu.ico" alt=""/></td>
        <td onclick="switch_hero('a', 4, 16)"><img src="images/icon/arabia/daicungthu.ico" alt=""/></td>
        <td onclick="switch_hero('a', 5, 17)"><img src="images/icon/arabia/daoky.ico" alt=""/></td>
        <td onclick="switch_hero('a', 6, 18)"><img src="images/icon/arabia/truongthuongky.ico" alt=""/></td>
        		</tr></table>

			<table class="carcass" cellpadding="3" id="a_hero_1" style="display:"><tr>
		<td onclick="switch_hero('a', 0, 23)"><img src="images/icon/mongo/tienbinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 1, 24)"><img src="images/icon/mongo/hotinhbinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 2, 25)"><img src="images/icon/mongo/cungthu.ico" alt=""/></td>
        <td onclick="switch_hero('a', 3, 26)"><img src="images/icon/mongo/cungtienbinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 4, 27)"><img src="images/icon/mongo/hoathantien.ico" alt=""/></td>
        <td onclick="switch_hero('a', 5, 28)"><img src="images/icon/mongo/longdaoky.ico" alt=""/></td>
        <td onclick="switch_hero('a', 6, 29)"><img src="images/icon/mongo/doanhonky.ico" alt=""/></td>
        		</tr></table>
			<table class="carcass" cellpadding="3" id="a_hero_2" style="display:"><tr>
		<td onclick="switch_hero('a', 0, 1)"><img src="images/icon/sunda/xatienbinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 1, 2)"><img src="images/icon/sunda/thietgiapbinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 2, 3)"><img src="images/icon/sunda/mocbinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 3, 4)"><img src="images/icon/sunda/nobinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 4, 5)"><img src="images/icon/sunda/hoatienbinh.ico" alt=""/></td>
        <td onclick="switch_hero('a', 5, 6)"><img src="images/icon/sunda/loidaoky.ico" alt=""/></td>
        <td onclick="switch_hero('a', 6, 7)"><img src="images/icon/sunda/truongsonma.ico" alt=""/></td>
        		</tr></table>         </td>
              	</tr>
                <tr>
                	<td>Cấp</td>
                    <td><input type="text" name="ah_l" id="ah_l" size="2" class="fm" value="{ah_l}"  /></td>
                </tr>
                <tr>
                  <td>Tương sinh công</td>
                  <td><input type="text" name="tsc" id="tsc" size="2" class="fm" value="{tsc}" /> %</td>
                </tr>
                <tr>
                  <td>Tương khắc công</td>
                  <td><input type="text" name="tkc" id="tkc" size="2" class="fm" value="{tkc}"  /> %</td>
                </tr>
                <tr>
                	<td>Sinh lực</td>
                    <td><input type="text" name="ah_hp" id="ah_hp" size="2" class="fm" value="{ah_hp}"  /><span class="c">{ah_shp}</span> %</td>
                </tr>
                <!--
                <tr>
                	<td>Kinh nghiệm</td>
                    <td><input type="text" name="ah_kn" id="ah_kn" size="2" class="fm" value="{ah_kn}"  /> <span class="c">{ah_ikn}</span></td>
                </tr>
                -->
               </table>        </td>
		</tr>
</table></td>
    </tr>
    
    <tr>
      <td valign="top">
      <table cellspacing="1" cellpadding="2" class="tbg">
		<tr class="cbg1">
			<td colspan="2" class="c1 b">Bên phòng thủ</td>
          </tr>
		<tr>
		  <td class="c1 b"><span class="c2 b">
		    <input type="radio" name="dns" id="dns0" value="0" onchange="dn_change()" {dns_c_0} />
            <a href="">Arabia</a>
            <input type="radio" name="dns" id="dns1" value="1" onchange="dn_change()" {dns_c_1}/>
            <a href="">Mongo</a>
            <input type="radio" name="dns" id="dns2" value="2" onchange="dn_change()" {dns_c_2}/>
            <a href="">Sunda</a></span></td>
		  <td class="b"><img src="images/icon/hero4.ico" alt="" />&nbsp; &nbsp;{hero}
    <input type="checkbox" name="dh" id="dh" value='1' onclick='select_dhr()' {dh_c}/></td>
		</tr>
        <tr>
      	<td valign="top">
      
      <div id="ds_0" >
      <table cellpadding="2" cellspacing="1" class="tbg">        
        <tr class="unit">
          <td width="15%"><a href=""><span id="dnt"></span></a></td>
          <td><img src="images/icon/arabia/nguyetkiembinh.ico" title="Tiền binh" id="di_0_0" /><img src="images/icon/mongo/tienbinh.ico" title="Tiền binh" id="di_1_0" style="display:none;" /><img src="images/icon/sunda/xatienbinh.ico" title="Xạ tiễn binh" id="di_2_0" style="display:none;" /></td>
          <td><img src="images/icon/arabia/tramtaubinh.ico" title="Hổ tinh binh" id="di_0_1" /><img src="images/icon/mongo/hotinhbinh.ico" title="Hổ tinh binh" id="di_1_1" style="display:none;" /><img src="images/icon/sunda/thietgiapbinh.ico" title="Thiết giáp binh" id="di_2_1" style="display:none;" /></td>
          <td><img src="images/icon/arabia/thiettinhbinh.ico" title="Cung thủ" id="di_0_2" /><img src="images/icon/mongo/cungthu.ico" title="Cung thủ" id="di_1_2" style="display:none;" /><img src="images/icon/sunda/mocbinh.ico" title="Mộc binh" id="di_2_2" style="display:none;" /></td>
          <td><img src="images/icon/arabia/cungthu.ico" title="Cung tiễn binh" id="di_0_3" /><img src="images/icon/mongo/cungtienbinh.ico" title="Cung tiễn binh" id="di_1_3" style="display:none;" /><img src="images/icon/sunda/nobinh.ico" title="Nỏ binh" id="di_2_3" style="display:none;" /></td>
          <td><img src="images/icon/arabia/daicungthu.ico" title="Hỏa thân tiễn" id="di_0_4" /><img src="images/icon/mongo/hoathantien.ico" title="Hỏa thân tiễn" id="di_1_4" style="display:none;" /><img src="images/icon/sunda/hoatienbinh.ico" title="Hỏa tiễn binh" id="di_2_4" style="display:none;" /></td>
          <td><img src="images/icon/arabia/daoky.ico" title="Long đao kỵ" id="di_0_5" /><img src="images/icon/mongo/longdaoky.ico" title="Long đao kỵ" id="di_1_5" style="display:none;" /><img src="images/icon/sunda/loidaoky.ico" title="Lôi đao kỵ" id="di_2_5" style="display:none;" /></td>
          <td><img src="images/icon/arabia/truongthuongky.ico" title="Đoạn hồn kỵ" id="di_0_6" /><img src="images/icon/mongo/doanhonky.ico" title="Đoạn hồn kỵ" id="di_1_6" style="display:none;" /><img src="images/icon/sunda/truongsonma.ico" title="Trường sơn mã" id="di_2_6" style="display:none;" /></td>
          <td><img src="images/icon/arabia/thambinh.ico" title="Thám quân" id="di_0_7" /><img src="images/icon/mongo/thamquan.ico" title="Thám quân" id="di_1_7" style="display:none;" /><img src="images/icon/sunda/truyenquan.ico" title="Truyền quân" id="di_2_7" style="display:none;" /></td>
          <td><img src="images/icon/arabia/hoaphaoxa.ico" title="Hoả thạch xa" id="di_0_8" /><img src="images/icon/mongo/hoathachxa.ico" title="Hoả thạch xa" id="di_1_8" style="display:none;" /><img src="images/icon/sunda/chienphaoxa.ico" title="Chiến pháo xa" id="di_2_8" style="display:none;" /></td>
          <td><img src="images/icon/arabia/tho.ico" title="Thợ" id="di_0_9" /><img src="images/icon/mongo/tho.ico" title="Thợ" id="di_1_9" style="display:none;" /><img src="images/icon/sunda/tho.ico" title="Thợ" id="di_2_9" style="display:none;" /></td>
          <td><img src="images/icon/arabia/thuyetkhach.ico" title="Thuyết khách" id="di_0_10" /><img src="images/icon/mongo/thuyetkhach.ico" title="Thuyết khách" id="di_1_10" style="display:none;" /><img src="images/icon/sunda/thuyetkhach.ico" title="Thuyết khách" id="di_2_10" style="display:none;" /></td>
          <td><img src="images/icon/hero4.ico" alt="Tướng" title="Tướng" /></td>
        </tr>
        <tr>
          <td width="15%">&nbsp;&nbsp; Quân đội &nbsp;&nbsp;</td>
    <td><input class="fm" type="text" name="dst[0]" value="{ds_0}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="dst[1]" value="{ds_1}" size="4" maxlength="50"></td>
    <td><input class="fm" type="text" name="dst[2]" value="{ds_2}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="dst[3]" value="{ds_3}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="dst[4]" value="{ds_4}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="dst[5]" value="{ds_5}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="dst[6]" value="{ds_6}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="dst[7]" value="{ds_7}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="dst[8]" value="{ds_8}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="dst[9]" value="{ds_9}" size="4" maxlength="10"></td>
    <td><input class="fm" type="text" name="dst[10]" value="{ds_10}" size="4" maxlength="10"></td>
    <td {dh_cl}>{ds_11}</td>
        </tr>
        <tr>
          <td width="15%">Giáp</td>
          <td class=""><input class="fm" type="text" name="g[0]" value="{g_0}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[1]" value="{g_1}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[2]" value="{g_2}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[3]" value="{g_3}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[4]" value="{g_4}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[5]" value="{g_5}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[6]" value="{g_6}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[7]" value="{g_7}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[8]" value="{g_8}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[9]" value="{g_9}" size="1" maxlength="5" /></td>
          <td class="c"><input class="fm" type="text" name="g[10]" value="{g_10}" size="1" maxlength="5" /></td>
          <td class="c">&nbsp;</td>
        </tr>
        <tr>
          <td width="15%">Hy sinh</td>
          <td {dc_0_0}>{dsd_0}</td>
          <td {dc_0_1}>{dsd_1}</td>
          <td {dc_0_2}>{dsd_2}</td>
          <td {dc_0_3}>{dsd_3}</td>
          <td {dc_0_4}>{dsd_4}</td>
          
          <td {dc_0_5}>{dsd_5}</td>
          <td {dc_0_6}>{dsd_6}</td>
          <td {dc_0_7}>{dsd_7}</td>
          <td {dc_0_8}>{dsd_8}</td>
          <td {dc_0_9}>{dsd_9}</td>
          
          <td {dc_0_10}>{dsd_10}</td>
          <td {dc_0_11}>{dsd_11}</td>
        </tr>
        <tr>
          <td width="15%">ASU (+10%)</td>
          <td {dc_0_0}><input type="checkbox" name="d_asu" id="d_asu" value="2" {d_asu_c}/></td>
          <td colspan="11" {dc_0_1}>&nbsp;</td>
          </tr>
        <tr>
          <td width="15%">Tường thành cấp:</td>
          <td {dc_0_0}><input type="text" name="wl" id="wl" value="{wl}" size="1" maxlength="2" class="fm" /></td>
          <td colspan="11" {dc_0_1}>&nbsp;</td>
          </tr>
      </table>
      </div>
      <br />
		<!--<table cellpadding="2" cellspacing="1" class="tbg">
        	<tr> <td>ASU (+10%)</td><td><input type="checkbox" name="d_asu" id="d_asu" value="1" {d_asu_c}/></tr>
        	<tr> <td>Cấp của tường thành</td><td><input type="text" name="wl" id="wl" value="{wl}" size="1" maxlength="2" class="fm" /></tr>
        </table>-->      </td>
      	<td valign="top">
        
        <table cellpadding="2" cellspacing="1" class="tbg" id="dhr" style="display:none; text-align:left;">
        	<tr>
            	<td colspan="2">
        <table class="carcass" cellpadding="3" id="d_hero_0" style="display:"><tr>
		<td onclick="switch_hero('d', 0, 12)"><img src="images/icon/arabia/nguyetkiembinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 1, 13)"><img src="images/icon/arabia/tramtaubinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 2, 14)"><img src="images/icon/arabia/thiettinhbinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 3, 15)"><img src="images/icon/arabia/cungthu.ico" alt=""/></td>
        <td onclick="switch_hero('d', 4, 16)"><img src="images/icon/arabia/daicungthu.ico" alt=""/></td>
        <td onclick="switch_hero('d', 5, 17)"><img src="images/icon/arabia/daoky.ico" alt=""/></td>
        <td onclick="switch_hero('d', 6, 18)"><img src="images/icon/arabia/truongthuongky.ico" alt=""/></td>
        		</tr></table>

			<table class="carcass" cellpadding="3" id="d_hero_1" style="display:"><tr>
		<td onclick="switch_hero('d', 0, 23)"><img src="images/icon/mongo/tienbinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 1, 24)"><img src="images/icon/mongo/hotinhbinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 2, 25)"><img src="images/icon/mongo/cungthu.ico" alt=""/></td>
        <td onclick="switch_hero('d', 3, 26)"><img src="images/icon/mongo/cungtienbinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 4, 27)"><img src="images/icon/mongo/hoathantien.ico" alt=""/></td>
        <td onclick="switch_hero('d', 5, 28)"><img src="images/icon/mongo/longdaoky.ico" alt=""/></td>
        <td onclick="switch_hero('d', 6, 29)"><img src="images/icon/mongo/doanhonky.ico" alt=""/></td>
        		</tr></table>
			<table class="carcass" cellpadding="3" id="d_hero_2" style="display:"><tr>
		<td onclick="switch_hero('d', 0, 1)"><img src="images/icon/sunda/xatienbinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 1, 2)"><img src="images/icon/sunda/thietgiapbinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 2, 3)"><img src="images/icon/sunda/mocbinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 3, 4)"><img src="images/icon/sunda/nobinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 4, 5)"><img src="images/icon/sunda/hoatienbinh.ico" alt=""/></td>
        <td onclick="switch_hero('d', 5, 6)"><img src="images/icon/sunda/loidaoky.ico" alt=""/></td>
        <td onclick="switch_hero('d', 6, 7)"><img src="images/icon/sunda/truongsonma.ico" alt=""/></td>
        		</tr></table>         </td>
              	</tr>
                <tr>
                	<td>Cấp</td>
                    <td><input type="text" name="dh_l" id="dh_l" size="2" class="fm" value="{dh_l}" /></td>
                </tr>
                <tr>
                  <td>Tương sinh thủ</td>
                  <td><input type="text" name="tst" id="tst" size="2" class="fm" value="{tst}" /> %</td>
                </tr>
                <tr>
                  <td>Tương khắc thủ</td>
                  <td><input type="text" name="tkt" id="tkt" size="2" class="fm" value="{tkt}" /> %</td>
                </tr>
                <tr>
                	<td>Sinh lực</td>
                    <td><input type="text" name="dh_hp" id="dh_hp" size="2" class="fm" value="{dh_hp}"  /><span class="c"> {dh_shp}</span> %</td>
                </tr>
                <!--
                <tr>
                	<td>Kinh nghiệm</td>
                    <td><input type="text" name="dh_kn" id="dh_hp" size="2" class="fm" value="{dh_kn}"  /> <span class="c">  {dh_ikn}</td>
                </tr>
                -->
               </table>        </td>
        </tr></table>      </td>
    </tr>   
	
	<tr><td height="40">	
	<input type="image" value="ok" border="0" name="s1" src="images/en/b/ok1.gif" height="20" onMousedown="btm1('s1','','images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()"></input>
	</td></tr>
</table>
</form>
</body>
	</html>