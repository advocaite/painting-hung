// JavaScript Document
var xmlHttp;

function showCostAsu(str)
{ 
xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request");
 return;
 }
var url="loadAsuconfig.php";
url=url+"?q="+str;
url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChangedRS;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);
}

function showInfo(str1,str2)
{ 
xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request");
 return;
 }
var url="loadAsuconfig.php";
url=url+"?p="+str1;
url=url+"&r="+str2;
url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChangedInfo;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);
}

function showFee_Price(obj1,obj2)
{ 
xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request");
 return;
 }
var url="loadAsuconfig.php";

obj1.value=obj1.value.replace(new RegExp("\\D|^0","g"),"");
obj2.value=obj2.value.replace(new RegExp("\\D|^0","g"),"");

url=url+"?f="+obj1.value;
url=url+"&pi="+obj2.value;
url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChangedFee_Price;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);
}
function stateChangedFee_Price() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 document.getElementById("txtFee_Price").innerHTML=xmlHttp.responseText;
 } 
}

function stateChangedRS() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 document.getElementById("txtCostAsu").innerHTML=xmlHttp.responseText;
 } 
}

function stateChangedInfo() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 document.getElementById("txtInfo").innerHTML=xmlHttp.responseText;
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