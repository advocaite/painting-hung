<div id="column-content">
    <div id="content">
        <p><span style="background:#FFCC66"><a href="tracking.php">{Same PC}</a></span> | <a href="tracking.php?s=1">{Report Statistic}</a> | <a href="tracking.php?s=2">{Resource Statistic}</a> | <a href="tracking.php?s=3">{Attack Statistic}</a></p>
        <form name="frm_security" action="" method="post">
            <input type="hidden" name="username" value="{username}" id="username"  />
            <input type="hidden" name="order_type" value="{order_type}" id="order_type"  />
            <input type="hidden" name="text" value="{text}" id="text"  />
            <table align="center" width="100%">
                <tr>
                    <td colspan="3" align="center"><b style="font-size:14px; color:#FF0000">CHI TI&#7870;T USER  D&Ugrave;NG NHI&#7872;U &#272;&#7882;A CH&#7880; IP</b> </td>
              </tr>
                <tr>
                    <td width="5%"><a href="tracking.php">By IP</a></td>
                  <td width="31%"><span style="background:#FFCC66"><a href="tracking.php?s=1">By User</a></span></td>
                  <td width="64%"></td>
              </tr>
            </table>
            <table width="100%" border="1" cellpadding="3" style="margin-top:10px">
                <tr>
                    <th>No</th>
                    <!--<th>{id}</th>-->
                    <th>{username}</th>
                    <th>{ip}</th>
                    <th>{feature}</th>
                    <th>{time}</th>
                </tr>
                {list}
            </table>
            <table width="100%">
                <tr>
                    <td> {total_record} </td>
                    <td> {Page} {pagenumber} {Of} {total_page} </td>
                </tr>
            </table>
        </form>
    </div>
</div>
