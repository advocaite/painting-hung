<script language="javascript">
function funcOrder()
{
 var form = document.frm_security;   
 if(form.order_type.value == '1')
 {
 	form.order_type.value = '2';
 }
 else
 {
 	form.order_type.value = '1';
 }
 	
 form.submit(); // submit 1 form dung cho tat ca cac truong .
}
</script>

<div id="column-content">
    <div id="content">
        <form name="frm_security" action="" method="post">
            <input type="hidden" name="username" value="{username}" id="username"  />
            <input type="hidden" name="order_type" value="{order_type}" id="order_type"  />
            <input type="hidden" name="text" value="{text}" id="text"  />
            <table align="center" width="30%">
                <tr>
                    <td align="center"><b style="font-size:14px; color:#FF0000">BAD LIST DETAIL</b> </td>
                </tr>
            </table>
            <table width="100%" border="1" cellpadding="3" style="margin-top:10px">
                <tr>
                    <th>No</th>
                    <!--<th>{id}</th>-->
                    <th>IP</th>
                    <th>Username</th>
                    <th>Amout</th>
                    <th>Detail</th>
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
