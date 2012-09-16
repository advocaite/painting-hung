<div id="box1">
  <div id="box2">
    <h1>{Player profile}</h1>
     <p class="title_menu"> <a href="profile.php">{Overview}</a> | <a href="profile.php?s=1">{Edit Profile}</a> | <span class="selected"><a href="profile.php?s=3">{Option}</a></span> </p>
    <form method="POST" action="profile.php?s=3&act=3" onsubmit="return checkinput();">
      <input type="hidden" name="e" value="3">
      <input type="hidden" name="s" value="3">
      <input type="hidden" name="a" value="b63">
      <p>
      {pw_invalid}
      <table cellspacing="1" cellpadding="2" class="tbg">
        <tr>
          <td class="rbg" colspan="2">{Account sitters}</td>
        </tr>
        <tr class="f8">
          <td colspan="2">{Account sitters description 1}</td>
        </tr>
        <tr class="s7" {hidden_1}>
          <td width="50%">{Name of the sitter}</td>
          <td width="50%"><input class="fm f110" type="text" name="txt_sister" maxlength="20">
          </td>
        </tr>
        <tr {hidden_2}>
          <td colspan="2"><a href="profile.php?act=del_account_sister&acc_sis_id={acc_sis_id}"> <img src="images/un/a/del.gif" width="12" height="12" border="0" title="Xa"> </a> &nbsp; <a href="profile.php?uid={sister_id_1}">{account_sister} </a> </td>
        </tr>
      </table><p>
      <table cellspacing="1" cellpadding="2" class="tbg">
        <tr>
          <td class="rbg" colspan="2">{Delete account 1}</td>
        </tr>
        <tr class="f8">
          <td colspan="2">{Delete account description}</td>
        </tr>
        <tr class="f10 b">
          <td colspan="2"><a href="profile.php?act=del_acc&id={id}"> <img src="images/un/a/del.gif" width="12" height="12" border="0" title="cancel"/> </a>{Delete account}<b style="color:#FF9900"> [{time}]</b></td>
        </tr>
      </table>
      </p>
      <!--<input type="submit" name="ok_account" id="ok_account" value="{ok}">-->
      <!--<p align="center" style="margin-top:3px">
        <input type="image" src="{images}" value="" tabindex="3">
      </p>-->
    </form>
  </div>
</div>
</div>
<script language="javascript">
function emailCheck (emailStr)
{
	var string=document.getElementById("email").value;
	var emailPat=/^(.+)@(.+)$/
	var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
	var validChars="\[^\\s" + specialChars + "\]"
	var quotedUser="(\"[^\"]*\")"
	var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
	var atom=validChars + '+'
	var word="(" + atom + "|" + quotedUser + ")"
	var userPat=new RegExp("^" + word + "(\\." + word + ")*$")
	var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$")
	var matchArray=emailStr.match(emailPat)
	if (matchArray==null) 
	{
		alert(string);
		return false;
	}
	var user=matchArray[1]
	var domain=matchArray[2]
	if (user.match(userPat)==null) 
	{
		alert(string);
		return false;
	}
	var IPArray=domain.match(ipDomainPat)
	if (IPArray!=null) 
	{
		// this is an IP address
		for (var i=1;i<=4;i++) 
		{
			if (IPArray[i]>255)
			{
				alert(string);
				return false
			}
		}
		return true
	}
	var domainArray=domain.match(domainPat)
	if (domainArray==null) 
	{
		alert(string);
		return true
	}
	var atomPat=new RegExp(atom,"g")
	var domArr=domain.match(atomPat)
	var len=domArr.length
	if (domArr[domArr.length-1].length<2 || domArr[domArr.length-1].length>4) 
	{
		alert(string);
	   	return false
	}
	if (len<2) 
	{
	   alert(string);
	   return false
	}
  	return true;
}
	
function checkinput()
{
	if(document.getElementById("new_email").value!="")
	{
	  if(emailCheck(document.getElementById("new_email").value)==false){
  	  document.getElementById("new_email").focus();
  	  return false;
    }
}

	var string1=document.getElementById("pass1").value;
	var string2=document.getElementById("pass2").value;
	var string3=document.getElementById("pass3").value;
	var string4=document.getElementById("delete_confirm_password").value;
	
	if(document.getElementById("pw2").value!="")
	{
		if(document.getElementById("pw2").value.length<=3)
		{
			alert(string2);
			document.getElementById("pw2").focus();
			return false;
		}
		if(document.getElementById("pw2").value.length!=document.getElementById("pw3").value.length)
		{
			alert(string3);
			document.getElementById("pw3").focus();
			return false;
		}
	}	
	return true;
}
</script>
