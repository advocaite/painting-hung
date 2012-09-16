<head>
<script src="js/selectAsuConfig.js"></script>
<style type="text/css">
	.buysell_container {width:200px; float:left;}
	.header_title {width:200px; float:left}
	.header_title li { list-style:none; display:inline; margin-right:10px;}
	.tbl_buysell {width:200px; float:left;}
	.buysell_detail {width:350px; float:right;}
</style>
</head>

<body>
<table><tr><td>
<form action="build.php?id={id}&t=9&l={l}" method="post">
<div class="buysell_container">
<div class="header_title">
	<li><a href="build.php?id={id}&t=9&l=1">{Lumber}</a></li>
    <li><a href="build.php?id={id}&t=9&l=2">{Clay}</a></li>
    <li><a href="build.php?id={id}&t=9&l=3">{Iron}</a></li>
    <li><a href="build.php?id={id}&t=9&l=4">{Crop}</a></li>
</div>

<div class="tbl_buysell">
	{showSell}
</div>

</div>
<div class="buysell_detail">
	<table width="350" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right"><b>{RS}:</b></td>
    <td>{maxRS}</td>
  </tr>
  <tr>
    <td align="right">{Asu}: </td>
    <td>{total_gold}</td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td align="right">{Amount}: </td>
    <td><input name="txt_amout" type="text" value="{amout}" onKeyUp="showFee_Price(this,txt_unitPrice);"/></td>
  </tr>
  <tr>
    <td align="right">{Unit Price}: </td>
    <td><input name="txt_unitPrice" type="text" value="{minUnit_price}" onKeyUp="showFee_Price(txt_amout,this);"/></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
   <td colspan="2">
        <div id="txtFee_Price">
            <table>
                <tr>
                    <td align="right">{Fee}: </td>
                    <td align="left"></td>
                </tr>
                <tr>
                    <td align="right">{Total Price}: </td>
                    <td align="left"></td>
                 </tr>
            </table>
        </div> 
    </td>
  </tr>
  <tr>
    <td colspan="2">{0.5% of total price will be charged for every Post as Trading.}</td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><input value="ok" name="s1" src="images/en/b/ok1.gif" onMouseDown="btm1('s1','','images/en/b/ok2.gif',1)" onMouseOver="btm1('s1','','images/en/b/ok3.gif',1)" onMouseUp="btm0()" onMouseOut="btm0()" border="0" type="image" width="50" height="20"></p><img src="images/un/a/x.gif"></td>
  </tr>
</table>
</div>
</form>
</td></tr></table>
<p class="f10">{Merchants}: {merchant_available}/{sum_merchant}</p>
{error_message}
{buyAsu_status}
</body>