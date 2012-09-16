var loading = false;
var img_last='',Id_last='',css='';
function areaMouseOver(area)
{
	if(img_last!='' && Id_last !='')
	{
		document.getElementById(Id_last).src=img_last;
	}	
	a = area.id.split("_");
	dx = parseInt(a[1]) - Math.floor(size/2);
	dy = parseInt(a[2]) - Math.floor(size/2);
	load_x=formatNum(center_x+dx,max_x);
	load_y=formatNum(center_y+dy,max_y);	
	loadTooltip(load_x,load_y);
	img_last=document.getElementById('Img_'+load_x+'_'+load_y).src;
	css=document.getElementById('Img_'+load_x+'_'+load_y).className;
	Id_last='Img_'+load_x+'_'+load_y;
	deleteNode('map_image');
	images_point='<img class="'+css+'a" src="images/point.png">';
	document.getElementById('map_image').innerHTML=images.concat(images_point);	
	document.getElementById('x').innerHTML = load_x;	
	document.getElementById('y').innerHTML = load_y;
};	
function loadTooltip(x,y){	
	var ajax = new sack();	
	ajax.requestFile = "includes/toolTip.php";	
	ajax.setVar('x',x);	
	ajax.setVar('y',y);	
	ajax.method = 'GET';
	ajax.onCompletion = function()
	{ 
		document.getElementById('tb').innerHTML = ajax.response; 	
	};	
	ajax.runAJAX();	
};	
function create()
{	
	deleteNode('map_image');
	document.getElementById('map_image').innerHTML=images;		
	if(isNaN(center_x) || isNaN(center_y))
	{
		center_x=center_y=0;	
	}
	document.getElementById('x').innerHTML =center_x;	
	document.getElementById('y').innerHTML =center_y;	
	document.getElementById('mcx').value = center_x;	
	document.getElementById('mcy').value = center_y;
	loading = false;
};	
function moveOneNorth()
{
	go_x=parseInt(document.getElementById('mcx').value);	
	go_y=parseInt(document.getElementById('mcy').value);
	gotoXY(go_x,go_y+1);		
};	
function moveOneSouth()
{	
	go_x=parseInt(document.getElementById('mcx').value);	
	go_y=parseInt(document.getElementById('mcy').value);
	gotoXY(go_x,go_y-1);	
};
function moveOneEast()
{	
	go_x=parseInt(document.getElementById('mcx').value);	
	go_y=parseInt(document.getElementById('mcy').value);
	gotoXY(go_x+1,go_y);	
};	
function moveOneWest()
{	
	go_x=parseInt(document.getElementById('mcx').value);	
	go_y=parseInt(document.getElementById('mcy').value);
	gotoXY(go_x-1,go_y);	
};	
function loadMap(x,y)
{
	center_x=parseInt(x);
	center_y=parseInt(y);	
	var ajax = new sack();
	ajax.requestFile = "includes/loadMap.php";	
	ajax.setVar('x',x);	
	ajax.setVar('y',y);	
	ajax.setVar('size',size);	
	ajax.method = 'GET';	
	ajax.onCompletion = function()
	{ 	
		images=ajax.response;		
		create();	
	};	
	ajax.runAJAX();	
};	
function gotoXY(x,y){	
	if(loading) return;
	loading = true;
	x=parseInt(x);	
	y=parseInt(y);	
	if(x>max_x) x=max_x;	
	else if(x<-max_x) x= -max_x;	
	if(y>max_x) y=max_x;	
	else if(y<-max_x) y= -max_x;	
	loadMap(x,y);	
};	
function formatNum(x,max_x){	
	x=parseInt(x);	
	if(x>max_x) x= -(max_x*2+1)+x;	
	else if(x<-max_x) x= (max_x*2+1)+x;	
	return x;	
};	
function viewVillage(area){
	a = area.id.split("_");	
	dx = parseInt(a[1]) - Math.floor(size/2);	
	dy = parseInt(a[2]) - Math.floor(size/2);load_x=formatNum(center_x+dx,max_x);load_y=formatNum(center_y+dy,max_y);	
	document.location.href = "village_map.php?a="+(load_x)+"&b="+(load_y);	
};	
function resetLableXY(){	
	document.getElementById('x').innerHTML = center_x;	
	document.getElementById('y').innerHTML = center_y;	
};
function deleteNode(elementId){  var label=document.getElementById(elementId);	
  while( label.hasChildNodes() ) { label.removeChild( label.lastChild ); }
};
