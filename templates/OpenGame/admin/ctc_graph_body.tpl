<style>
.ctc_selected{
	font-size:14px;
	font-weight:bold;
	
}
</style>
<div id="column-content">
<div id="content">
<div>
  <p>
  <span {mn_class_1}><a href="ctc_graph.php?id=1">Chi Lăng</a></span> | 
  <span {mn_class_2}><a href="ctc_graph.php?id=2">Bạch Đằng</a></span> | 
  <span {mn_class_3}><a href="ctc_graph.php?id=3">Bồ Đằng</a></span> | 
  <span {mn_class_4}><a href="ctc_graph.php?id=4">Trà Lân</a></span> | 
  <span {mn_class_5}><a href="ctc_graph.php?id=5">Như Nguyệt</a></span>
  </p>
  <p>{message}</p>
</div>
<div>
<!--
<h2 class="firstHeading">Default:</h2>
-->
<div id="form-zonedef">
<img src="../images/ctc/bmap.jpg" height="250" /></center>
<form id="zonedef" name="zonedef" action="#" method="post">
<table cellpadding="1" cellspacing="0" width="800" border="0">
<tr>
<td width="15%">Điểm công -&gt; Kim</td>
<td><input type="text" id="s_1_2" name="s_1_2" value="{s_1_2}" maxlength="4" size="1" /></td>
<td width="5%">&nbsp;</td>
<td  width="15%">Kim -&gt; điểm công</td>
<td><input type="text" id="s_2_1" name="s_2_1" value="{s_2_1}" maxlength="4" size="1" /></td>
<td  width="5%">&nbsp;</td>
<td width="15%">Thủy -&gt; Mộc</td>
<td><input type="text" id="s_3_4" name="s_3_4" value="{s_3_4}" maxlength="4" size="1" /></td>
<td width="5%">&nbsp;</td>
<td width="15%">Mộc -&gt; Thủy</td>
<td><input type="text" id="s__" name="s_4_3" value="{s_4_3}" maxlength="4" size="1" /></td>
</tr>
<tr>
<td>Điểm công -&gt; Thủy</td>
<td><input type="text" id="s_1_3" name="s_1_3" value="{s_1_3}" maxlength="4" size="1" /></td>
<td>&nbsp;</td>
<td> Thủy -&gt; điểm công</td>
<td><input type="text" id="s_3_1" name="s_3_1" value="{s_3_1}" maxlength="4" size="1" /></td>
<td>&nbsp;</td>
<td>Thủy -&gt; Hỏa</td>
<td><input type="text" id="s_3_5" name="s_3_5" value="{s_3_5}" maxlength="4" size="1" /></td>
<td>&nbsp;</td>
<td>Hỏa -&gt; Thủy</td>
<td><input type="text" id="s_5_3" name="s_5_3" value="{s_5_3}" maxlength="4" size="1" /></td>
</tr>
<tr>
<td>Điểm công -&gt; Mộc</td>
<td><input type="text" id="s_1_4" name="s_1_4" value="{s_1_4}" maxlength="4" size="1" /></td>
<td>&nbsp;</td>
<td> Mộc -&gt; điểm công</td>
<td><input type="text" id="s_4_1" name="s_4_1" value="{s_4_1}" maxlength="4" size="1" /></td>
<td>&nbsp;</td>
<td>Thủy -&gt; Thổ</td>
<td><input type="text" id="s_3_6" name="s_3_6" value="{s_3_6}" maxlength="4" size="1" /></td>
<td>&nbsp;</td>
<td>Thổ -&gt; Thủy</td>
<td><input type="text" id="s_6_3" name="s_6_3" value="{s_6_3}" maxlength="4" size="1" /></td>
</tr>
<tr>
<td>Điểm công -&gt; Hỏa</td>
<td><input type="text" id="s_1_5" name="s_1_5" value="{s_1_5}" maxlength="4" size="1" /></td>
<td>&nbsp;</td>
<td> Hỏa -&gt; điểm công</td>
<td><input type="text" id="s_5_1" name="s_5_1" value="{s_5_1}" maxlength="4" size="1" /></td>
<td>&nbsp;</td>
<td>Thủy -&gt; Điểm Thủ</td>
<td><input type="text" id="s_3_7" name="s_3_7" value="{s_3_7}" maxlength="4" size="1" /></td>
<td>&nbsp;</td>
<td>Điểm Thủ -&gt; Thủy</td>
<td><input type="text" id="s_7_3" name="s_7_3" value="{s_7_3}" maxlength="4" size="1" /></td>
</tr>
<tr>
  <td>Điểm công -&gt; Thổ</td>
  <td><input type="text" id="s_1_6" name="s_1_6" value="{s_1_6}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td> Thổ -&gt; điểm công</td>
  <td><input type="text" id="s_6_1" name="s_6_1" value="{s_6_1}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>Kim -&gt; Thủy</td>
  <td><input type="text" id="s_2_3" name="s_2_3" value="{s_2_3}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td> Thủy -&gt; Kim</td>
  <td><input type="text" id="s_3_2" name="s_3_2" value="{s_3_2}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Mộc -&gt; Hỏa</td>
  <td><input type="text" id="s_4_5" name="s_4_5" value="{s_4_5}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Hỏa -&gt; Mộc</td>
  <td><input type="text" id="s_5_4" name="s_5_4" value="{s_5_4}" maxlength="4" size="1" /></td>
</tr>
<tr>
  <td>Kim -&gt; Mộc</td>
  <td><input type="text" id="s_2_4" name="s_2_4" value="{s_2_4}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td> Mộc -&gt; Kim</td>
  <td><input type="text" id="s_4_2" name="s_4_2" value="{s_4_2}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Mộc -&gt; Thổ</td>
  <td><input type="text" id="s_4_6" name="s_4_6" value="{s_4_6}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Thổ -&gt; Mộc</td>
  <td><input type="text" id="s_6_4" name="s_6_4" value="{s_6_4}" maxlength="4" size="1" /></td>
</tr>
<tr>
  <td>Kim -&gt; Hỏa</td>
  <td><input type="text" id="s_2_5" name="s_2_5" value="{s_2_5}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td> Hỏa -&gt; Kim</td>
  <td><input type="text" id="s_5_2" name="s_5_2" value="{s_5_2}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Mộc -&gt; Điểm thủ</td>
  <td><input type="text" id="s_4_7" name="s_4_7" value="{s_4_7}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Điểm thủ -&gt; Mộc</td>
  <td><input type="text" id="s_7_4" name="s_7_4" value="{s_7_4}" maxlength="4" size="1" /></td>
</tr>
<tr>
  <td>Kim -&gt; Thổ</td>
  <td><input type="text" id="s_2_6" name="s_2_6" value="{s_2_6}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td> Thổ -&gt; Kim</td>
  <td><input type="text" id="s_6_2" name="s_6_2" value="{s_6_2}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>Kim -&gt; Điểm thủ</td>
  <td><input type="text" id="s_2_7" name="s_2_7" value="{s_2_7}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Điểm thủ -&gt; Kim</td>
  <td><input type="text" id="s_7_2" name="s_7_2" value="{s_7_2}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>Hỏa -&gt; Thổ</td>
  <td><input type="text" id="s_5_6" name="s_5_6" value="{s_5_6}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Thổ -&gt; Hỏa</td>
  <td><input type="text" id="s_6_5" name="s_6_5" value="{s_6_5}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Thổ -&gt; Điểm thủ</td>
  <td><input type="text" id="s_6_7" name="s_6_7" value="{s_6_7}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Điểm thủ -&gt; Thổ</td>
  <td><input type="text" id="s_7_6" name="s_7_6" value="{s_7_6}" maxlength="4" size="1" /></td>
</tr>
<tr>
  <td>Hỏa -&gt; Điểm thủ</td>
  <td><input type="text" id="s_5_7" name="s_5_7" value="{s_5_7}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>Điểm thủ -&gt; Hỏa</td>
  <td><input type="text" id="s_7_5" name="s_7_5" value="{s_7_5}" maxlength="4" size="1" /></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
</table>
<p><input type="submit" id="submit" name="submit" value="Update" /></p>
</form>
</div>

</div>

</div>
</div>

<div id="p-cactions" class="portlet">
		<h5>Views</h5>
		<div class="pBody">
			<ul>	
				 <li id="ca-nstab-main" class="selected"><a href="#" title="View the content page [c]" accesskey="c">Create Graph CT</a></li>
              </ul>
		</div>
</div>