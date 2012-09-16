<script language="JavaScript">
var overall;
function calculateRes() {
	resObj=document.getElementsByName("m2");
	overall=0;
	for (i=0; i<resObj.length; i++) {
		var tmp="";
		for (j=0; j<resObj[i].value.length; j++)
			if ((resObj[i].value.charAt(j)>="0") && (resObj[i].value.charAt(j)<="9")) tmp=tmp+resObj[i].value.charAt(j);
		resObj[i].value=tmp;
		if (tmp=="") tmp="0";
		newRes=Math.round(parseInt(tmp)*summe/100);
		if (((i<3) && (newRes<=max123)) || ((i==3) && (newRes<=max4)))
			newHTML=newRes;
		else
			newHTML="<span style='color:#FF4000'>"+newRes+"</span>";
		document.getElementById("new"+i).innerHTML=newHTML;
		overall+=parseInt(tmp);
	}
	document.getElementById("overall").innerHTML=overall+"%";
}
function normalize() {
	calculateRes();
	resObj=document.getElementsByName("m2");
	for (i=0; i<resObj.length; i++) {
		tmp=parseInt(resObj[i].value);
		tmp=tmp*(100/overall);
		resObj[i].value=Math.round(tmp);
	}
	calculateRes();
}


function calculateRest() {
	resObj=document.getElementsByName("m2[]");
	overall=0;
	for (i=0; i<resObj.length; i++) {
		var tmp="";
		for (j=0; j<resObj[i].value.length; j++)
			if ((resObj[i].value.charAt(j)>="0") && (resObj[i].value.charAt(j)<="9")) tmp=tmp+resObj[i].value.charAt(j);
		if (tmp=="") {
			tmp="0";
			newRes=0;
			resObj[i].value="";
		} else {
			newRes=parseInt(tmp);
			if ((i<3) && (newRes>max123)) newRes=max123;
			if ((i==3) && (newRes>max4)) newRes=max4;
			resObj[i].value=newRes;
		}
		dif=newRes-parseInt(document.getElementById("org"+i).innerHTML);
		newHTML=dif;
		if (dif>0) newHTML="+"+dif;
		document.getElementById("diff"+i).innerHTML=newHTML;
		overall+=newRes;
	}
	document.getElementById("newsum").innerHTML=overall;
	rest=parseInt(document.getElementById("org4").innerHTML)-overall;
	document.getElementById("remain").innerHTML=rest;
	testSum();
}

function fillup(nr) {
	resObj=document.getElementsByName("m2[]");
	if (nr<3) {
		resObj[nr].value=max123;
	} else {
		resObj[nr].value=max4;
	}
	calculateRest();
}
function portionOut() {
	restRes=parseInt(document.getElementById("remain").innerHTML);
	rest=restRes;
	resObj=document.getElementsByName("m2[]");
	nullCount=0;
	notNullCount=0;
	// Z�hlen
	for (j=0; j<resObj.length; j++) {
		if ((restRes>0) && (resObj[j].value=="")) nullCount++;
		if ((restRes<0) && (resObj[j].value!="")) notNullCount++;
	}
	// Verteilen
	nullCount2=0;
	if (restRes>0) {
		// In allen Feldern schon Zahlen?
		if (nullCount==0) {
			for (i=0; i<resObj.length; i++) {
				free=max123-parseInt(resObj[i].value);
				resObj[i].value=(parseInt(resObj[i].value)+Math.round(rest/(4-i)));
				rest=rest-Math.min(free,Math.round(rest/(4-i)));
				if ((i<3) && (parseInt(resObj[i].value)<max123)) nullCount2++;
			}
		} else {
			for (i=0; i<resObj.length; i++) {
				if (resObj[i].value=="") {
					resObj[i].value=Math.round(rest/nullCount);
					rest=rest-Math.round(rest/nullCount);
					nullCount--;
				}
				if ((i<3) && (parseInt(resObj[i].value)<max123)) nullCount2++;
			}
		}
	} else {
		for (j=0; j<resObj.length; j++) {
			if (parseInt(resObj[j].value)>0) {
				resObj[j].value=(parseInt(resObj[j].value)+Math.round(rest/notNullCount));
				rest=rest-Math.round(rest/notNullCount);
				notNullCount--;
			}
		}
	}
	calculateRest();
	// Noch irgendein Rest?
	if (rest>0) {
		if (max123>max4) {
			for (j=0; j<3; j++) {
				if (parseInt(resObj[j].value)<max123) {
					resObj[j].value=(parseInt(resObj[j].value)+Math.round(rest/nullCount2));
					rest=rest-Math.round(rest/nullCount2);
					nullCount2--;
				}
			}
		} else {
			resObj[3].value=(parseInt(resObj[j].value)+rest);
		}
	}
	calculateRest();
}

function testSum() {
	if (document.getElementById("remain").innerHTML!=0) {
		document.getElementById("submitText").innerHTML="<a href='javascript:portionOut();'>{Distribute resources at} ({step} 1 {of} 2)</a>";
		document.getElementById("submitButton").style.display="none";
	} else {
		document.getElementById("submitText").innerHTML="";
		document.getElementById("submitButton").style.display="block";
	}
}
</script>
<script language="JavaScript">var summe={sumrs};var max123={capacity_123};var max4={capacity_4};</script>
<p>
<form method="POST" name="snd" action="build.php?id={id}&t=3">
<input type="hidden" name="id" value="29">
<input type="hidden" name="t" value="3">
<input type="hidden" name="a" value="6">
<table cellspacing="1" cellpadding="2" class="tbg">
<tr class="rbg">
<td colspan="5">{NPC trading}</td>
</tr><tr class="cbg1"><td width="20%"><a href="javascript:fillup(0);"><images src="images/un/r/1.gif" width="18" height="12" border="0"></a><span id="org0">{rs1}</span></td><td width="20%"><a href="javascript:fillup(1);"><images src="images/un/r/2.gif" width="18" height="12" border="0">
</a><span id="org1">{rs2}</span></td>
  <td width="20%"><a href="javascript:fillup(2);"><images src="images/un/r/3.gif" width="18" height="12" border="0">
  </a><span id="org2">{rs3}</span></td>
  <td width="20%"><a href="javascript:fillup(3);"><images src="images/un/r/4.gif" width="18" height="12" border="0">
  </a><span id="org3">{rs4}</span></td>
  <td width="20%" class="s7">{Sum}:&nbsp;<span id="org4">{sumrs}</span></td>
</tr><tr><td><input class="fm" onKeyUp="calculateRest();" name="m2[]" size="5" maxlength="6"><input type="hidden" name="m1[]" value={rs1}></td><td><input class="fm" onKeyUp="calculateRest();" name="m2[]" size="5" maxlength="6"><input type="hidden" name="m1[]" value={rs2}></td><td><input class="fm" onKeyUp="calculateRest();" name="m2[]" size="5" maxlength="6"><input type="hidden" name="m1[]" value={rs3}></td><td><input class="fm" onKeyUp="calculateRest();" name="m2[]" size="5" maxlength="6"><input type="hidden" name="m1[]" value={rs4}></td>
<td class="s7">{Sum}: <span id="newsum">0</span></td>
</tr><tr>
    <td><span id="diff0">-{rs1}</span></td>
    <td><span id="diff1">-{rs2}</span></td>
    <td><span id="diff2">-{rs3}</span></td>
    <td><span id="diff3">-{rs4}</span></td>
    <td class="s7">{Rest}: <span id="remain">{sumrs}</span></td>
  </tr></table></p><span id="submitButton">{check_gold_status}</span></span>
</form>
<span id="submitText"></span><script>testSum();</script>
<p>{intro}</p>
<images src="images/un/a/x.gif" />