// JavaScript Document
var xmlHttpReport;
var loadstatustext = "<table style='position:absolute; filter: alpha(opacity=90)' height='100%' width='100%'><tr><td><div align='center'><img src='images/progress.gif'></div></td></tr><table>";
//////////////////////////////////////////////////
function Report(containerid,value)
{ 

xmlHttpReport=GetXmlHttpObject();
if (xmlHttpReport==null)
  {
  alert ("Trình Duyệt Web Bạn Đang Sử Dụng Không Hỗ Trợ AJAX !");
  return;
  } 
var url="report.php?id="+value;
document.getElementById(containerid).innerHTML=loadstatustext;
xmlHttpReport.onreadystatechange=function(){	Show(containerid,value)	}
xmlHttpReport.open("GET",url,true);
xmlHttpReport.send(null);	

var show_hidden = 'show_hidden' + value;
var curr_data = 'showhidden' + value; 
if(document.getElementById(show_hidden).value==1) //show
{
	document.getElementById(show_hidden).value = 0;	
	this.style =document.getElementById(curr_data).style;
	this.style.display="";
}else{//hidden	
	document.getElementById(show_hidden).value = 1;	
	this.style =document.getElementById(curr_data).style;
	this.style.display="none";
}
	
}
///////////////////////////////////////
function Show(containerid,value) 
{ 
	if (xmlHttpReport.readyState==4 || xmlHttpReport.readyState=="complete")
	{
		document.getElementById(containerid).innerHTML=xmlHttpReport.responseText ;
	}
	
	
	
}
//////////////////////////
function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}