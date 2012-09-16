<html>
<head>
<link rel=stylesheet type="text/css" href="unx.css">
<link rel="stylesheet" href="css/games.css" type="text/css" />
<link rel="stylesheet" href="css/new.css" type="text/css" />
<script src="js/unx.js" type="text/javascript"></script>
<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="js/map_large.js"></script>

<script type="text/javascript">
	var image_path = "images/un/m/";
	var arr_image = new Array();
	var center_x = {mapx}; var center_y = {mapy}; var size = 13;
	loadMap(center_x,center_y);
</script>
<meta name="content-language" content="vn">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body bgcolor="#F0F6E9" style="background:#F0F6E9"><div class="map_insert_xy_xxl">
<table align="center" cellspacing="0" cellpadding="3">
<form method="POST" action="#" onSubmit="gotoXY(this.mcx.value,this.mcx.value); return false;">
<tr>
<td><b>x</b></td>

<td><input id="mcx" name="xp" value="{mapx}" size="2" maxlength="4"></td>
<td><b>y</b></td>
<td><input id="mcx" name="yp" value="{mapy}" size="2" maxlength="4"></td>
<td></td>
<td><input type="image" value="ok" border="0" name="s1" src="images/en/b/ok1.gif" width="50" height="20" onMousedown="btm1('s1','','images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()"></input></td>
</tr></form></table></div><div class="map_show_xy_xxl">
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="40%"><h1>{Map}</h1></td>
<td width="33%" align="right"><h1><nobr>(<span id="x">{mapx}</span></nobr></h1></td>
<td width="4%" align="center"><h1>|</h1></td>
<td width="33%" align="left"><h1><nobr><span id="y">{mapy}</span></span>)</h1></td>

</tr>
</table>
</div><div class="map_infobox_xxl" id="tb"></div><div id="map_image" align="center" style="position:absolute; z-index:50; left:10px; top:0px;">
</div><map name="map2">
<area href="javascript:moveOneNorth()" onMouseOver="resetLableXY()" coords="762,115,30" shape="circle" title="{North}">
<area href="javascript:moveOneEast()" onMouseOver="resetLableXY()" coords="770,430,30" shape="circle" title="{East}">
<area href="javascript:moveOneSouth()" onMouseOver="resetLableXY()" coords="210,430,30" shape="circle" title="{South}">
<area href="javascript:moveOneWest()" onMouseOver="resetLableXY()" coords="200,115,30" shape="circle" title="{West}">
<area id="a_0_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="442,33,478,13,515,33,478,53" shape="poly" />
<area id="a_0_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="479,53,515,33,552,53,515,73" shape="poly" />
<area id="a_0_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="516,73,552,53,589,73,552,93" shape="poly" />
<area id="a_0_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="553,93,589,73,626,93,589,113" shape="poly"/>
<area id="a_0_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="590,113,626,93,663,113,626,133" shape="poly"/>
<area id="a_0_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="627,133,663,113,700,133,663,153" shape="poly"/>
<area id="a_0_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="664,153,700,133,737,153,700,173" shape="poly"/>
<area id="a_0_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="701,173,737,153,774,173,737,193" shape="poly"/>
<area id="a_0_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="738,193,774,173,811,193,774,213" shape="poly"/>
<area id="a_0_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="775,213,811,193,848,213,811,233" shape="poly"/>
<area  id="a_0_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="812,233,848,213,885,233,848,253" shape="poly"/>
<area  id="a_0_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="849,253,885,233,922,253,885,273" shape="poly"/>
<area  id="a_0_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="886,273,922,253,959,273,922,293" shape="poly"/>
<area  id="a_1_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="406,53,442,33,479,53,442,73" shape="poly" />
<area  id="a_1_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="443,73,479,53,516,73,479,93" shape="poly" />
<area  id="a_1_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="480,93,516,73,553,93,516,113" shape="poly" />
<area  id="a_1_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="517,113,553,93,590,113,553,133" shape="poly" />
<area  id="a_1_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="554,133,590,113,627,133,590,153" shape="poly"/>
<area  id="a_1_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="591,153,627,133,664,153,627,173" shape="poly"/>
<area  id="a_1_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="628,173,664,153,701,173,664,193" shape="poly"/>
<area  id="a_1_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="665,193,701,173,738,193,701,213" shape="poly"/>
<area  id="a_1_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="702,213,738,193,775,213,738,233" shape="poly"/>
<area  id="a_1_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="739,233,775,213,812,233,775,253" shape="poly"/>
<area  id="a_1_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="776,253,812,233,849,253,812,273" shape="poly"/>
<area  id="a_1_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="813,273,849,253,886,273,849,293" shape="poly"/>
<area  id="a_1_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="850,293,886,273,923,293,886,313" shape="poly"/>
<area  id="a_2_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="370,73,406,53,443,73,406,93" shape="poly"/>
<area  id="a_2_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="407,93,443,73,480,93,443,113" shape="poly" />
<area  id="a_2_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="444,113,480,93,517,113,480,133" shape="poly" />
<area  id="a_2_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="481,133,517,113,554,133,517,153" shape="poly"/>
<area  id="a_2_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="518,153,554,133,591,153,554,173" shape="poly"/>
<area  id="a_2_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="555,173,591,153,628,173,591,193" shape="poly"/>
<area  id="a_2_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="592,193,628,173,665,193,628,213" shape="poly"/>
<area  id="a_2_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="629,213,665,193,702,213,665,233" shape="poly"/>
<area  id="a_2_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="666,233,702,213,739,233,702,253" shape="poly"/>
<area  id="a_2_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="703,253,739,233,776,253,739,273" shape="poly"/>
<area  id="a_2_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="740,273,776,253,813,273,776,293" shape="poly"/>
<area  id="a_2_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="777,293,813,273,850,293,813,313" shape="poly"/>
<area  id="a_2_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="814,313,850,293,887,313,850,333" shape="poly"/>
<area  id="a_3_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="334,93,370,73,407,93,370,113" shape="poly" />
<area  id="a_3_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="371,113,407,93,444,113,407,133" shape="poly" />
<area  id="a_3_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="408,133,444,113,481,133,444,153" shape="poly"/>
<area  id="a_3_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="445,153,481,133,518,153,481,173" shape="poly"/>
<area  id="a_3_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="482,173,518,153,555,173,518,193" shape="poly"/>
<area  id="a_3_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="519,193,555,173,592,193,555,213" shape="poly"/>
<area  id="a_3_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="556,213,592,193,629,213,592,233" shape="poly"/>
<area  id="a_3_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="593,233,629,213,666,233,629,253" shape="poly"/>
<area  id="a_3_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="630,253,666,233,703,253,666,273" shape="poly"/>
<area  id="a_3_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="667,273,703,253,740,273,703,293" shape="poly"/>
<area  id="a_3_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="704,293,740,273,777,293,740,313" shape="poly"/>
<area  id="a_3_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="741,313,777,293,814,313,777,333" shape="poly"/>
<area  id="a_3_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="778,333,814,313,851,333,814,353" shape="poly"/>
<area  id="a_4_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="298,113,334,93,371,113,334,133" shape="poly"/>
<area  id="a_4_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="335,133,371,113,408,133,371,153" shape="poly"/>
<area  id="a_4_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="372,153,408,133,445,153,408,173" shape="poly"/>
<area  id="a_4_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="409,173,445,153,482,173,445,193" shape="poly"/>
<area  id="a_4_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="446,193,482,173,519,193,482,213" shape="poly"/>
<area  id="a_4_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="483,213,519,193,556,213,519,233" shape="poly"/>
<area  id="a_4_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="520,233,556,213,593,233,556,253" shape="poly"/>
<area  id="a_4_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="557,253,593,233,630,253,593,273" shape="poly"/>
<area  id="a_4_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="594,273,630,253,667,273,630,293" shape="poly"/>
<area  id="a_4_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="631,293,667,273,704,293,667,313" shape="poly"/>
<area  id="a_4_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="668,313,704,293,741,313,704,333" shape="poly"/>
<area  id="a_4_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="705,333,741,313,778,333,741,353" shape="poly"/>
<area  id="a_4_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="742,353,778,333,815,353,778,373" shape="poly"/>
<area  id="a_5_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="262,133,298,113,335,133,298,153" shape="poly"/>
<area  id="a_5_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="299,153,335,133,372,153,335,173" shape="poly"/>
<area  id="a_5_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="336,173,372,153,409,173,372,193" shape="poly"/>
<area  id="a_5_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="373,193,409,173,446,193,409,213" shape="poly"/>
<area  id="a_5_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="410,213,446,193,483,213,446,233" shape="poly"/>
<area  id="a_5_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="447,233,483,213,520,233,483,253" shape="poly"/>
<area  id="a_5_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="484,253,520,233,557,253,520,273" shape="poly"/>
<area  id="a_5_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="521,273,557,253,594,273,557,293" shape="poly"/>
<area  id="a_5_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="558,293,594,273,631,293,594,313" shape="poly"/>
<area  id="a_5_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="595,313,631,293,668,313,631,333" shape="poly"/>
<area  id="a_5_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="632,333,668,313,705,333,668,353" shape="poly"/>
<area  id="a_5_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="669,353,705,333,742,353,705,373" shape="poly"/>
<area  id="a_5_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="706,373,742,353,779,373,742,393" shape="poly"/>
<area  id="a_6_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="226,153,262,133,299,153,262,173" shape="poly"/>
<area  id="a_6_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="263,173,299,153,336,173,299,193" shape="poly"/>
<area  id="a_6_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="300,193,336,173,373,193,336,213" shape="poly"/>
<area  id="a_6_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="337,213,373,193,410,213,373,233" shape="poly"/>
<area  id="a_6_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="374,233,410,213,447,233,410,253" shape="poly"/>
<area  id="a_6_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="411,253,447,233,484,253,447,273" shape="poly"/>
<area  id="a_6_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="448,273,484,253,521,273,484,293" shape="poly"/>
<area  id="a_6_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="485,293,521,273,558,293,521,313" shape="poly"/>
<area  id="a_6_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="522,313,558,293,595,313,558,333" shape="poly"/>
<area  id="a_6_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="559,333,595,313,632,333,595,353" shape="poly"/>
<area  id="a_6_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="596,353,632,333,669,353,632,373" shape="poly"/>
<area  id="a_6_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="633,373,669,353,706,373,669,393" shape="poly"/>
<area  id="a_6_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="670,393,706,373,743,393,706,413" shape="poly"/>
<area  id="a_7_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="190,173,226,153,263,173,226,193" shape="poly"/>
<area  id="a_7_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="227,193,263,173,300,193,263,213" shape="poly"/>
<area  id="a_7_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="264,213,300,193,337,213,300,233" shape="poly"/>
<area  id="a_7_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="301,233,337,213,374,233,337,253" shape="poly"/>
<area  id="a_7_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="338,253,374,233,411,253,374,273" shape="poly"/>
<area  id="a_7_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="375,273,411,253,448,273,411,293" shape="poly"/>
<area  id="a_7_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="412,293,448,273,485,293,448,313" shape="poly"/>
<area  id="a_7_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="449,313,485,293,522,313,485,333" shape="poly"/>
<area  id="a_7_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="486,333,522,313,559,333,522,353" shape="poly"/>
<area  id="a_7_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="523,353,559,333,596,353,559,373" shape="poly"/>
<area  id="a_7_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="560,373,596,353,633,373,596,393" shape="poly"/>
<area  id="a_7_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="597,393,633,373,670,393,633,413" shape="poly"/>
<area  id="a_7_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="634,413,670,393,707,413,670,433" shape="poly"/>
<area  id="a_8_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="154,193,190,173,227,193,190,213" shape="poly"/>
<area  id="a_8_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="191,213,227,193,264,213,227,233" shape="poly"/>
<area  id="a_8_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="228,233,264,213,301,233,264,253" shape="poly"/>
<area  id="a_8_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="265,253,301,233,338,253,301,273" shape="poly"/>
<area  id="a_8_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="302,273,338,253,375,273,338,293" shape="poly"/>
<area  id="a_8_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="339,293,375,273,412,293,375,313" shape="poly"/>
<area  id="a_8_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="376,313,412,293,449,313,412,333" shape="poly"/>
<area  id="a_8_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="413,333,449,313,486,333,449,353" shape="poly"/>
<area  id="a_8_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="450,353,486,333,523,353,486,373" shape="poly"/>
<area  id="a_8_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="487,373,523,353,560,373,523,393" shape="poly"/>
<area  id="a_8_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="524,393,560,373,597,393,560,413" shape="poly"/>
<area  id="a_8_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="561,413,597,393,634,413,597,433" shape="poly"/>
<area  id="a_8_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="598,433,634,413,671,433,634,453" shape="poly"/>
<area  id="a_9_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="118,213,154,193,191,213,154,233" shape="poly"/>
<area  id="a_9_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="155,233,191,213,228,233,191,253" shape="poly"/>
<area  id="a_9_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="192,253,228,233,265,253,228,273" shape="poly"/>
<area  id="a_9_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="229,273,265,253,302,273,265,293" shape="poly"/>
<area  id="a_9_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="266,293,302,273,339,293,302,313" shape="poly"/>
<area  id="a_9_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="303,313,339,293,376,313,339,333" shape="poly"/>
<area  id="a_9_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="340,333,376,313,413,333,376,353" shape="poly"/>
<area  id="a_9_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="377,353,413,333,450,353,413,373" shape="poly"/>
<area  id="a_9_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="414,373,450,353,487,373,450,393" shape="poly"/>
<area  id="a_9_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="451,393,487,373,524,393,487,413" shape="poly"/>
<area  id="a_9_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="488,413,524,393,561,413,524,433" shape="poly"/>
<area  id="a_9_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="525,433,561,413,598,433,561,453" shape="poly"/>
<area  id="a_9_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="562,453,598,433,635,453,598,473" shape="poly"/>
<area  id="a_10_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="82,233,118,213,155,233,118,253" shape="poly" />
<area  id="a_10_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="119,253,155,233,192,253,155,273" shape="poly"/>
<area  id="a_10_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="156,273,192,253,229,273,192,293" shape="poly"/>
<area  id="a_10_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="193,293,229,273,266,293,229,313" shape="poly"/>
<area  id="a_10_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="230,313,266,293,303,313,266,333" shape="poly"/>
<area  id="a_10_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="267,333,303,313,340,333,303,353" shape="poly"/>
<area  id="a_10_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="304,353,340,333,377,353,340,373" shape="poly"/>
<area  id="a_10_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="341,373,377,353,414,373,377,393" shape="poly"/>
<area  id="a_10_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="378,393,414,373,451,393,414,413" shape="poly"/>
<area  id="a_10_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="415,413,451,393,488,413,451,433" shape="poly"/>
<area  id="a_10_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="452,433,488,413,525,433,488,453" shape="poly"/>
<area  id="a_10_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="489,453,525,433,562,453,525,473" shape="poly"/>
<area  id="a_10_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="526,473,562,453,599,473,562,493" shape="poly"/>
<area  id="a_11_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="46,253,82,233,119,253,82,273" shape="poly" />
<area  id="a_11_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="83,273,119,253,156,273,119,293" shape="poly"/>
<area  id="a_11_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="120,293,156,273,193,293,156,313" shape="poly"/>
<area  id="a_11_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="157,313,193,293,230,313,193,333" shape="poly"/>
<area  id="a_11_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="194,333,230,313,267,333,230,353" shape="poly"/>
<area  id="a_11_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="231,353,267,333,304,353,267,373" shape="poly"/>
<area  id="a_11_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="268,373,304,353,341,373,304,393" shape="poly"/>
<area  id="a_11_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="305,393,341,373,378,393,341,413" shape="poly"/>
<area  id="a_11_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="342,413,378,393,415,413,378,433" shape="poly"/>
<area  id="a_11_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="379,433,415,413,452,433,415,453" shape="poly"/>
<area  id="a_11_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="416,453,452,433,489,453,452,473" shape="poly"/>
<area  id="a_11_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="453,473,489,453,526,473,489,493" shape="poly"/>
<area  id="a_11_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="490,493,526,473,563,493,526,513" shape="poly"/>
<area  id="a_12_0" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="10,273,46,253,83,273,46,293" shape="poly"/>
<area  id="a_12_1" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="47,293,83,273,120,293,83,313" shape="poly" />
<area  id="a_12_2" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="84,313,120,293,157,313,120,333" shape="poly" />
<area  id="a_12_3" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="121,333,157,313,194,333,157,353" shape="poly"/>
<area  id="a_12_4" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="158,353,194,333,231,353,194,373" shape="poly"/>
<area  id="a_12_5" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="195,373,231,353,268,373,231,393" shape="poly"/>
<area  id="a_12_6" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="232,393,268,373,305,393,268,413" shape="poly"/>
<area  id="a_12_7" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="269,413,305,393,342,413,305,433" shape="poly"/>
<area  id="a_12_8" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="306,433,342,413,379,433,342,453" shape="poly"/>
<area  id="a_12_9" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="343,453,379,433,416,453,379,473" shape="poly"/>
<area  id="a_12_10" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="380,473,416,453,453,473,416,493" shape="poly"/>
<area  id="a_12_11" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="417,493,453,473,490,493,453,513" shape="poly"/>
<area  id="a_12_12" href="javascript:void(0)" onClick="returnVillage(this)" onMouseOver="areaMouseOver(this)" coords="454,513,490,493,527,513,490,533" shape="poly"/>

</map><img style="position:absolute; width:975px; height:550px; z-index:400; left:0px; top:0px;" usemap="#map2" src="images/un/m/bg_xxl.gif" border="0"></body>
</html>