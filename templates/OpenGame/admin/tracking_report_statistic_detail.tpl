<div id="column-content">
    <div id="content">
        <p><a href="tracking.php">{Same PC}</a> | <span style="background:#FFCC66"><a href="tracking.php?s=1">{Report Statistic}</a></span> | <a href="tracking.php?s=2">{Resource Statistic}</a> | <a href="tracking.php?s=3">{Attack Statistic}</a></p>
        <br/>
        <p><b style="color:#FF0000; text-align:center">REPORT STATISTIC</b></p>
        <p><br />
            <b><span style="color:#000000">{msg}</span><span style="color:#FF6600">{user_name}</span></b> <br />
        </p>
        <p class="txt_menue"> <span {class}><a href="tracking.php?s=1&uid={user_id}" >{All}</a></span> | <span {class1}><a href="tracking.php?s=1&tab={REPORT_ATTACK}&uid={user_id}">{Attacks}</a></span> | <span {class2}><a href="tracking.php?s=1&tab={REPORT_DEFEND}&uid={user_id}">{Reinforcement}</a></span> | <span {class3}><a href="tracking.php?s=1&tab={REPORT_TRADE}&uid={user_id}">{Trade}</a></span> </p>
        <p>
        <table cellspacing="1" cellpadding="2" class="tbg">
            <tr class="rbg">
                <td class="s7">{title}</td>
                <td class="s7">{title_de}</td>
            </tr>
            <tr>
                <td class="s7">{time}</td>
                <td class="s7">{time_de}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td colspan="4" valign="top">{_report_detail}</td>
            </tr>
        </table>
    </div>
</div>
