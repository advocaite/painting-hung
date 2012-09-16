<script language="javascript">
function OpenWindowsNew()
{
		return window.open('helptroop.php','Help troop','toolbar=yes,location=yes,directories=yes,status=no,menubar=no,scrollbars=yes,resizable=yes,width=350,height=300,top=200,left=150');
}
</script>

<div id="column-content">
  <div id="content">    
    <form name="frm_user_list" method="POST">
      <input type="hidden" name="reason" id="reason" />
      <table width="100%">
        <tr>
          <td align="center"><b style="color:#FF0000">HELP TROOP</b> </td>
        </tr>
      </table>      
      <table width="100%">       
        <tr>
          <td align="center">Are you sure ?</td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td align="center"><a href="#" onclick="OpenWindowsNew();"><b style=" font-size:16px">Done</b></a> </td>
        </tr>
      </table>
    </form>
  </div>
</div>
