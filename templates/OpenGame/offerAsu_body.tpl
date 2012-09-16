<head>
<script src="js/selectAsuConfig.js"></script>
</head>

<form method="post" action="build.php?id={id}&t=5">
<p>
  <input name="id" value="25" type="hidden">
  <input name="t" value="5" type="hidden">
  <input name="a" value="4" type="hidden">
</p>
<p>{contentOfferAsu}</p>
<table class="f10">
<tbody><tr>
<td>{Searching}</td>
<td>
<select name="rid_rs" size="" class="fm" onchange="showCostAsu(this.value)">
<option value="0" selected="selected">Loai</option>
<option value="1">{Lumber}</option>
<option value="2">{Clay}</option>
<option value="3">{Iron}</option>
<option value="4">{Crop}</option>
</select>
</td>
<td width="50px"></td>
<td><div id="txtCostAsu"></div>
</tr>

<tr>
<td>{lot}</td>
<td>
<select name="rid_lot" size="" class="fm" onchange="showInfo(this.value,rid_rs.value)">
<option value="0" selected="selected">-------</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
</select>
</td>

<td>Asu</td>
<td><div id="txtInfo"></div>
</tr>
</tbody></table>
<p class="f10">{Merchants}: {merchant_available}/{sum_merchant}</p>
<p class="f10">{error_message}</p>
<p><input value="ok" name="s1" src="images/en/b/ok1.gif" onmousedown="btm1('s1','','images/en/b/ok2.gif',1)" onmouseover="btm1('s1','','images/en/b/ok3.gif',1)" onmouseup="btm0()" onmouseout="btm0()" border="0" type="image" width="50" height="20"></p><img src="images/un/a/x.gif"></form>
{offerAsu_status}
