var loading = false;
function areaMouseOver(area){	
	a = area.id.split("_");	
	dx = parseInt(a[1]) - Math.ceil(size/3);	
	dy = parseInt(a[2]) - Math.ceil(size/3);load_x=formatNum(center_x+dx,max_x);load_y=formatNum(center_y+dy,max_y);	
	loadTooltip(load_x,load_y);	
	document.getElementById('x').innerHTML = load_x;	
	document.getElementById('y').innerHTML = load_y;	
};	
function loadTooltip(x,y){	
	var ajax = new sack();	
	ajax.requestFile = "includes/toolTip.php";	
	ajax.setVar('x',x);	
	ajax.setVar('y',y);	
		
	ajax.onCompletion = function(){ 	
			document.getElementById('tb').innerHTML = ajax.response; 	
			};	
	ajax.runAJAX();	
};	
function create(){	
	deleteNode('map_image');	
	size2 = size*size;	
	for(i=1;i<=size2;i++){	
		document.getElementById('map_image').innerHTML += '<img class="mt'+i+'" src="'+image_path+arr_image[i]+'.gif" />';	
	};	
	document.getElementById('mcx').value = center_x;	
	document.getElementById('mcy').value = center_y;	
	document.getElementById('x').innerHTML = center_x;	
	document.getElementById('y').innerHTML = center_y;	
	for(i=0;i<size;i++){	
		document.getElementById('mx'+i).innerHTML = formatNum(center_x-Math.ceil(size/3)+i,max_x);	
		document.getElementById('my'+i).innerHTML = formatNum(center_y-Math.ceil(size/3)+i,max_y);	
	};	
	loading = false;
};	
function moveOneNorth(){	
	if(loading) return;
	loading = true;
	center_y++;	
	size2_ = size*size;	
	for(i=size2_;i>size;i--){	
		arr_image[i] = arr_image[i-7];	
	};	
	loadMapOne(center_x,center_y,'north');	
};	
function moveOneEast(){	
	if(loading) return;
	loading = true;
	center_x++;	
	size2_ = size*size;	
	for(i=1;i<size2_;i++){	
		arr_image[i] = arr_image[i+1];	
	};	
	loadMapOne(center_x,center_y,'east');	
};	
function moveOneSouth(){	
	if(loading) return;
	loading = true;
	center_y--;	
	size2_ = size*size - size;	
	for(i=1;i<=size2_;i++){	
		arr_image[i] = arr_image[i+size];	
	};	
	loadMapOne(center_x,center_y,'south');	
};	
function moveOneWest(){	
	if(loading) return;
	loading = true;
	center_x--;	
	size2_ = size*size;	
	for(i=size2_;i>0;i--){	
		arr_image[i] = arr_image[i-1];	
	};	
	loadMapOne(center_x,center_y,'west');	
};	
function loadMapOne(x,y,site){	
	var ajax = new sack();	
	ajax.requestFile = "includes/loadMap.php";	
	ajax.setVar('x',x);	
	ajax.setVar('y',y);	
	ajax.setVar('task',site);	
	ajax.setVar('size',size);	
	ajax.method = 'GET';	
	ajax.onCompletion = function(){ 	
			info = ajax.response.split(";"); 	
			if(site=='west'){	
				for(i=1;i<size+1;i++){	
					arr_image[size*i-size+1] = info[i];	
				}	
			}else if(site=='south'){	
				for(i=1;i<=size;i++){	
					arr_image[size2_+i] = info[i];	
				}	
			}else if(site=='east'){	
				for(i=1;i<=size;i++){	
					arr_image[size*i] = info[i];	
				}	
			}else if(site=='north'){	
				for(i=1;i<=size;i++){	
					arr_image[i] = info[i];	
			}	
		};	
		create();	
	};	
	ajax.runAJAX();	
};	
function moveNorth(){	
	if(loading) return;
	loading = true;
	center_y+=size;	
	loadMap(center_x,center_y);	
};	
function moveEast(){	
	if(loading) return;
	loading = true;
	center_x+=size;	
	loadMap(center_x,center_y);	
};	
function moveSouth(){	
	if(loading) return;
	loading = true;
	center_y-=size;	
	loadMap(center_x,center_y);	
};	
function moveWest(){	
	if(loading) return;
	loading = true;
	center_x-=size;	
	loadMap(center_x,center_y);	
};	
function loadMap(x,y){
	formatNum(x,max_x);	
	formatNum(y,max_y);	
	center_x=x;center_y=y;	
	var ajax = new sack();	
	ajax.requestFile = "includes/loadMap.php";	
	ajax.setVar('x',x);	
	ajax.setVar('y',y);	
	ajax.setVar('size',size);	
	ajax.method = 'GET';	
	ajax.onCompletion = function(){ 	
		info = ajax.response; 	//alert(info);
		arr_image = info.split(";");	
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
	dx = parseInt(a[1]) - Math.ceil(size/3);	
	dy = parseInt(a[2]) - Math.ceil(size/3);load_x=formatNum(center_x+dx,max_x);load_y=formatNum(center_y+dy,max_y);	
	document.location = "village_map.php?a="+(load_x)+"&b="+(load_y);	
};	
function resetLableXY(){	
	document.getElementById('x').innerHTML = center_x;	
	document.getElementById('y').innerHTML = center_y;	
};
function deleteNode(elementId){  var label=document.getElementById(elementId);	
  while( label.hasChildNodes() ) { label.removeChild( label.lastChild ); }
};
