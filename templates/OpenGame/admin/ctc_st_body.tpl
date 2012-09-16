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
  <span {mn_class_1}><a href="ctc_st.php?id=1">Chi Lăng</a></span> | 
  <span {mn_class_2}><a href="ctc_st.php?id=2">Bạch Đằng</a></span> | 
  <span {mn_class_3}><a href="ctc_st.php?id=3">Bồ Đằng</a></span> | 
  <span {mn_class_4}><a href="ctc_st.php?id=4">Trà Lân</a></span> | 
  <span {mn_class_5}><a href="ctc_st.php?id=5">Như Nguyệt</a></span>
  </p>
  <p>{message}</p>
</div>
<div>
<!--
<h2 class="firstHeading">Default:</h2>
-->
<div id="form-zonedef">
<form id="zonedef" name="zonedef" action="#" method="post">
  <p>&nbsp;
  <table border="0" width="100%">
  <tr>
  	<td>Start time:</td>
    <td><input type="text" name="st" id="st" value="{st}" /></td>
  </tr>
    <tr>
  	<td>Cost time</td>
    <td><input type="text" name="ct" id="ct" value="{ct}" /></td>
  </tr>
    <tr>
  	<td></td>
    <td></td>
  </tr>
  </table>
  
  </p>
  <p><input type="submit" id="submit" name="submit" value="{ok}" {ok_disabled} /></p>
</form>
</div>

</div>

</div>
</div>

<div id="p-cactions" class="portlet">
		<h5>Views</h5>
		<div class="pBody">
			<ul>	
				 <li id="ca-nstab-main" class="selected"><a href="#" title="View the content page [c]" accesskey="c">Start CTC</a></li>
              </ul>
		</div>
</div>