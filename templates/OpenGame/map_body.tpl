<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="js/map.js"></script>
<script type="text/javascript">
	var images;
	var center_x={mapx};
	var center_y={mapy};
	var size =11;
	var max_y = {max_y};
	var max_x = {max_x};
	loadMap(center_x,center_y);
</script>
<div id="box1"><div id="box2"><div id="tmpcover3"></div>
<div class="map_infobox" id="tb"></div>
<div class="mbg"></div><div id="map_content">{plus}<div style="position: absolute; z-index:10; background-color:#FFFFFF; width: 296px; height:33px; left: 138px; top:70px;"></div>
<div style="position: absolute; z-index:10; background-color:#FFFFFF; top: 384px; width: 300px; height: 30px; left: 145px;"></div><div style="position: absolute; z-index:10; background-color:#FFFFFF; top:135px; width:40px; height: 220px; left: 509px;"></div><div style="position: absolute; z-index:10; background-color:#FFFFFF; top:135px; width:40px; height:220px; left:29px;"></div>
<div  style="position:absolute; left:483px; top:97px; z-index:50;width:30px;height:25px;"><a href="javascript:moveOneNorth()"><img src="images/un/m/bac.JPG" ONMOUSEOVER="ddrivetip('{North}');resetLableXY();"; ONMOUSEOUT="hideddrivetip()"></a></div>
<div style="position: absolute; left: 68px; top: 97px; z-index: 50; width: 30px; height: 25px;"><a href="javascript:moveOneWest()"><img src="images/un/m/tay.JPG" onmouseover="ddrivetip('{West}');" ;="" onmouseout="hideddrivetip()"></a></div>
<div style="position: absolute; left: 68px; top: 367px; z-index: 50; width: 30px; height: 25px;"><a href="javascript:moveOneSouth()"><img src="images/un/m/nam.JPG" onmouseover="ddrivetip('{South}');resetLableXY();" ;="" onmouseout="hideddrivetip()"></a></div>
<div style="position: absolute; left: 483px; top: 367px; z-index: 50; width: 30px; height: 25px;"><a href="javascript:moveOneEast()"><img src="images/un/m/dong.JPG" onmouseover="ddrivetip('{East}');resetLableXY();" ;="" onmouseout="hideddrivetip()"></a></div>
<div class="mdiv" id="map_image" style="z-index:2;"><center><img src="images/Loading.gif" style="position:absolute; top:200px;"/></center></div>
<img class="mdiv" style="z-index:15;" src="images/un/a/x.gif" usemap="#karte" /><map id="karte" name="karte">
<area id="a_0_4" shape="poly" coords="62,134,99,154,62,174,25,154" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_0_5" shape="poly" coords="101,113,138,133,101,153,64,133" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_0_6" shape="poly" coords="136,93,173,113,136,133,99,113" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_0_7" shape="poly" coords="172,74,209,94,172,114,135,94" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_1_3" shape="poly" coords="61,174,98,194,61,214,24,194" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_1_4" shape="poly" coords="99,154,136,174,99,194,62,174" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_1_5" shape="poly" coords="136,133,173,153,136,173,99,153" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_1_6" shape="poly" coords="172,113,209,133,172,153,135,133" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_1_7" shape="poly" coords="209,93,246,113,209,133,172,113" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_1_8" shape="poly" coords="244,74,281,94,244,114,207,94" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_2_2" shape="poly" coords="64,214,101,234,64,254,27,234" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_2_3" shape="poly" coords="100,194,137,214,100,234,63,214" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_2_4" shape="poly" coords="137,174,174,194,137,214,100,194" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_2_5" shape="poly" coords="172,154,209,174,172,194,135,174" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_2_6" shape="poly" coords="208,134,245,154,208,174,171,154" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_2_7" shape="poly" coords="243,114,280,134,243,154,206,134" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_2_8" shape="poly" coords="280,93,317,113,280,133,243,113" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_2_9" shape="poly" coords="319,73,356,93,319,113,282,93" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_3_1" shape="poly" coords="65,254,102,274,65,294,28,274" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_3_2" shape="poly" coords="100,234,137,254,100,274,63,254" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_3_3" shape="poly" coords="139,213,176,233,139,253,102,233" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_3_4" shape="poly" coords="175,193,212,213,175,233,138,213" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_3_5" shape="poly" coords="210,173,247,193,210,213,173,193" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_3_6" shape="poly" coords="245,153,282,173,245,193,208,173" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_3_7" shape="poly" coords="284,133,321,153,284,173,247,153" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_3_8" shape="poly" coords="320,113,357,133,320,153,283,133" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_3_9" shape="poly" coords="356,93,393,113,356,133,319,113" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_3_10" shape="poly" coords="392,73,429,93,392,113,355,93" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_0" shape="poly" coords="65,293,102,313,65,333,28,313" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_1" shape="poly" coords="102,273,139,293,102,313,65,293" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_2" shape="poly" coords="140,253,177,273,140,293,103,273" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_3" shape="poly" coords="177,232,214,252,177,272,140,252" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_4" shape="poly" coords="213,212,250,232,213,252,176,232" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_5" shape="poly" coords="245,193,282,213,245,233,208,213" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_4_6" shape="poly" coords="282,173,319,193,282,213,245,193" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_7" shape="poly" coords="319,153,356,173,319,193,282,173" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_8" shape="poly" coords="356,132,393,152,356,172,319,152" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_9" shape="poly" coords="392,113,429,133,392,153,355,133" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_4_10" shape="poly" coords="429,92,466,112,429,132,392,112" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_0" shape="poly" coords="104,312,141,332,104,352,67,332" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_1" shape="poly" coords="142,292,179,312,142,332,105,312" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_2" shape="poly" coords="180,272,217,292,180,312,143,292" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_3" shape="poly" coords="215,252,252,272,215,292,178,272" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_5_4" shape="poly" coords="253,232,290,252,253,272,216,252" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_5" shape="poly" coords="289,212,326,232,289,252,252,232" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_6" shape="poly" coords="323,192,360,212,323,232,286,212" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_7" shape="poly" coords="360,173,397,193,360,213,323,193" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_8" shape="poly" coords="395,153,432,173,395,193,358,173" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_9" shape="poly" coords="432,132,469,152,432,172,395,152" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_5_10" shape="poly" coords="467,112,504,132,467,152,430,132" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_0" shape="poly" coords="142,333,179,353,142,373,105,353" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_1" shape="poly" coords="179,313,216,333,179,353,142,333" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_6_2" shape="poly" coords="214,293,251,313,214,333,177,313" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_3" shape="poly" coords="251,273,288,293,251,313,214,293" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_4" shape="poly" coords="287,252,324,272,287,292,250,272" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_5" shape="poly" coords="324,232,361,252,324,272,287,252" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_6" shape="poly" coords="359,213,396,233,359,253,322,233" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_7" shape="poly" coords="396,192,433,212,396,232,359,212" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_8" shape="poly" coords="431,172,468,192,431,212,394,192" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_9" shape="poly" coords="469,153,506,173,469,193,432,173" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_6_10" shape="poly" coords="504,132,541,152,504,172,467,152" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_7_0" shape="poly" coords="178,352,215,372,178,392,141,372" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_7_1" shape="poly" coords="214,333,251,353,214,373,177,353" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_7_2" shape="poly" coords="252,312,289,332,252,352,215,332" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_7_3" shape="poly" coords="290,292,327,312,290,332,253,312" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_7_4" shape="poly" coords="326,273,363,293,326,313,289,293" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_7_5" shape="poly" coords="362,252,399,272,362,292,325,272" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_7_6" shape="poly" coords="396,232,433,252,396,272,359,252" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_7_7" shape="poly" coords="433,212,470,232,433,252,396,232" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_7_8" shape="poly" coords="468,193,505,213,468,233,431,213" href="javascript:void(0)" onclick="viewVillage(this)" onmouseover="areaMouseOver(this)">
<area id="a_7_9" shape="poly" coords="506,173,543,193,506,213,469,193" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_8_1" shape="poly" coords="252,353,289,373,252,393,215,373" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_8_2" shape="poly" coords="288,332,325,352,288,372,251,352" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_8_3" shape="poly" coords="325,313,362,333,325,353,288,333" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_8_4" shape="poly" coords="361,293,398,313,361,333,324,313" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_8_5" shape="poly" coords="397,274,434,294,397,314,360,294" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_8_6" shape="poly" coords="431,254,468,274,431,294,394,274" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_8_7" shape="poly" coords="466,235,503,255,466,275,429,255" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_8_8" shape="poly" coords="504,214,541,234,504,254,467,234" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_9_2" shape="poly" coords="324,353,361,373,324,393,287,373" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_9_3" shape="poly" coords="361,334,398,354,361,374,324,354" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_9_4" shape="poly" coords="396,315,433,335,396,355,359,335" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_9_5" shape="poly" coords="434,294,471,314,434,334,397,314" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_9_6" shape="poly" coords="469,274,506,294,469,314,432,294" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_9_7" shape="poly" coords="505,253,542,273,505,293,468,273" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_10_3" shape="poly" coords="399,352,436,372,399,392,362,372" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_10_4" shape="poly" coords="435,332,472,352,435,372,398,352" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_10_5" shape="poly" coords="472,313,509,333,472,353,435,333" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
<area id="a_10_6" shape="poly" coords="508,293,545,313,508,333,471,313" href="javascript:void(0)" onClick="viewVillage(this)" onMouseOver="areaMouseOver(this)">
</map><!--<div id="mx0"></div><div id="my0"></div><div id="mx1"></div><div id="my1"></div><div id="mx2"></div><div id="my2"></div><div id="mx3"></div><div id="my3"></div><div id="mx4"></div><div id="my4"></div><div id="mx5"></div><div id="my5"></div><div id="mx6"></div><div id="my6"></div>-->
<div class="map_show_xy">

		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
		<td width="38%"><h1>{Map}</h1></td>
		<td width="33%" class="right nbr"><h1>(<span id="x">{mapx}</span></h1></td>
		<td width="4%" align="center"><h1>|</h1></td>
		<td width="33%" class="left nbr"><h1><span id="y">{mapy}</span>)</h1></td>

		</tr>
		</table>
		</div><div class="map_insert_xy"><form method="post" action="#" onsubmit="gotoXY(this.mcx.value,this.mcy.value); return false;">
			<table align="center" cellspacing="0" cellpadding="3">
			
			<tr>
			<td><b>x</b></td>
			<td><input id="mcx" class="fm fm25" name="xp" value="{mapx}" size="2" maxlength="4"/></td>
			<td><b>y</b></td>

			<td><input id="mcy" class="fm fm25" name="yp" value="{mapy}" size="2" maxlength="4"/></td>
			<td></td>
			<td><input type="image" value="ok" name="s1" src="images/en/b/ok1.gif" width="50" height="20" onMouseDown="btm1('s1','','images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()"/></td>
			</tr></table></form></div>
</div></div></div></div>
