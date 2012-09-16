<head>
<script src="js/GetPresentAmount_item.js"></script>
</head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>

<form method="post" action="build.php?id={id}&t=10">	
	<table>
		<tr>
			<th width="300px" align="left">{Item}: 
				<select name="cbx_item" onchange="GetPresentAmount_item(this.value);">
					<option value="0" selected="selected">{select_item}</option>
					{option}
				</select>
			</th>
			<th>{present amount}: </th><th><div id="present_amount"></div></th>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<th align="left">
				{Offer}:
			</th>
		</tr>
		<tr>
			<th>
				{Amount}: <input type="textbox" name="txtAmount" value="1" size="3" onkeyup="CheckNumberInput(this);"/>
			</th>
			<th>
				{price}:<input type="textbox" name="txtPrice" value="" size="3" onkeyup="CheckNumberInput(this);"/>
			</th>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				<input value="ok" name="s1" src="images/en/b/ok1.gif" onmousedown="btm1('s1','','images/en/b/ok2.gif',1)" onmouseover="btm1('s1','','images/en/b/ok3.gif',1)" onmouseup="btm0()" onmouseout="btm0()" border="0" type="image" width="50" height="20" />
			</td>
		</tr>
	</table>
</form>
<span style="color:red">{tb_error}</span>
&nbsp;<br />
{sell_item_status}