
// JavaScript Document

var loadstatustext = "<div align='center' ><img src='../images/progress.gif'></div>";

function showTroopUserSameSide(containerid, id, uid){
	var url = "troop.php?id="+id+"&t=4"+"&uid="+uid;
	$("#popup_title").attr('src', '../images/ctc/popup_title_qd.jpg');
	loadPage(containerid, url);
}

function showPointTable(containerid, id){
	var url = "pt.php?id="+id+"";
	$("#popup_title").attr('src', '../images/ctc/popup_title_bd.jpg');
	loadPage(containerid, url);
}

function showReport(containerid, id, p){
	var url = "rp.php?id="+id+"&p="+p;
	$("#popup_title").attr('src', '../images/ctc/popup_title_cb.jpg');
	loadPage(containerid, url);
}

function changeSideName(containerid, id, s_id){
	var url = "rn.php?id="+id+"&sid="+s_id;
	$("#popup_title").attr('src', '../images/ctc/popup_title_tdm.jpg');
	loadPage(containerid, url);
}

function reloadSideName(containerid, id, s_id){
	var url = "rn.php?id="+id+"&sid="+s_id+"&t=2";
	loadPage(containerid, url);
}

function submitRename(containerid, id, s_id, s){
	var url = "rn.php?id="+id+"&sid="+s_id+"&nn="+$("#nn").val();
	$("#popup_title").attr('src', '../images/ctc/popup_title_tdm.jpg');
	loadPage(containerid, url);
	if(s==1){
		reloadSideName("side_attack_name", id, s_id);
	}else{
		reloadSideName("side_defend_name", id, s_id);
	}
}

function showTroopMoveStatus(containerid, id){
	$("#popup_title").attr('src', '../images/ctc/popup_title_qd.jpg');
	var url = "troop.php?id="+id+"&t=3";
	loadPage(containerid, url);
}

function reloadTroopMoveStatus(id){
	var url = "troop.php?id="+id+"&t=5&s=1";
	loadPage("attack_troop_move_status", url);
	var url = "troop.php?id="+id+"&t=5&s=0";
	loadPage("defend_troop_move_status", url);
}

function showReg(containerid, id){
	var url="reg.php?id="+id;
	$("#popup_title").attr('src', '../images/ctc/popup_title_dk.jpg');
	loadPage(containerid, url);
}

function submitReg(containerid, id){
	$("#popup_title").attr('src', '../images/ctc/popup_title_dk.jpg');
	var url="reg.php";
	url += "?ct_id="+id;
	url += "&p="+$("input[name=p]:radio:checked").val();
	loadPage(containerid, url);
}

function showSendTroop(containerid, id, st, vl){	
	$("#popup_title").attr('src', '../images/ctc/popup_title_dq.jpg');
	var url="troop.php?id="+id+"&t=2&st="+st+"&vl="+vl+"&stp=1";
	loadPage(containerid, url);
}

function submitSendTroop(containerid, id, st, vl){
	var frm = document.snd;
	var s = 0;
	var url="troop.php?id="+id+"&t=2&st="+st+"&vl="+vl+"&stp=2";	
	for(i=1; i<=12; i++){
		s = $("#t"+i).val()>0 ? $("#t"+i).val():0;
		url +="&t"+i+"="+s;
	}
	
	if(st==2){
		url += "&tg="+$("input[type=radio]:radio:checked").val();
	}
	loadPage(containerid, url);
}

function confirmSendTroop(containerid, id, st, vl){
	var frm = document.snd;
	var s = 0;
	var url="troop.php?id="+id+"&t=2&st="+st+"&vl="+vl+"&stp=3";
	for(i=1; i<=12; i++){
		s = $("#t"+i).val()>0 ? $("#t"+i).val():0;
		url +="&t"+i+"="+s;		
	}
	
	if(st==2){
		url += "&tg="+$("#tg").val();
	}
	
	loadPage(containerid, url);
	reloadTroopMoveStatus(id);
}


function overview(containerid, id)
{
	$("#popup_title").attr('src', '../images/ctc/popup_title_qd.jpg');
	var url="troop.php?id="+id+"&t=1";
	loadPage(containerid, url);
}

function ShowTroopOnTheWay(containerid, id)
{	
	var url="troop.php?id="+id+"&t=3";
	loadPage(containerid, url);
}

function loadPage(containerid, url) 
{
	var xmlHttpReport;
	xmlHttpReport=GetXmlHttpObject();
	if (xmlHttpReport==null){
  		alert ("Trình Duy?t Web B?n Ðang S? D?ng Không H? Tr? AJAX !");
  		return;
  	}
	document.getElementById(containerid).innerHTML=loadstatustext;
	xmlHttpReport.onreadystatechange=function(){	
		if (xmlHttpReport.readyState==4 || xmlHttpReport.readyState=="complete"){
			document.getElementById(containerid).innerHTML=xmlHttpReport.responseText ;
			start();
		}
	}	
	xmlHttpReport.open("GET",url,true);
	xmlHttpReport.send(null);		
}

function GetXmlHttpObject()
{
	var xmlHttp=null;
	try{
	 // Firefox, Opera 8.0+, Safari
	 xmlHttp=new XMLHttpRequest();
	}catch (e){
		// Internet Explorer
		try{
	  		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
	  	}catch (e){
	  		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	  	}
	}
	return xmlHttp;
}