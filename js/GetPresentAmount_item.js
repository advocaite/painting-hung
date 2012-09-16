// JavaScript Document
var xmlHttp;

function GetPresentAmount_item(str)
{ 
xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request");
 return;
 }
var url="GetAmount_item.php";
url=url+"?id_item="+str;
url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChangedRS;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);
}

function stateChangedRS() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 document.getElementById("present_amount").innerHTML=xmlHttp.responseText;
 } 
}

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
 //Internet Explorer
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