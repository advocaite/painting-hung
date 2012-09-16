var timer=new Object();
var ab=new Object();
/*var bbbb=new Object();
var cccc=new Object();
var dddd=new Object();
var eeee=new Object();
var ffff=new Object();*/
var cb=db();
var eb=0;
var auto_reload=1;
var fb=new Object();
var	is_opera=window.opera!==undefined;
var	is_ie=document.all!==undefined&&window.opera===undefined;
var is_ie6p=document.compatMode!==undefined&&document.all!==undefined&&window.opera===undefined;
var is_ie7=document.documentElement!==undefined&&document.documentElement.style.maxHeight!==undefined;
var is_ie6=is_ie6p&&!is_ie7;
var is_ff2p=window.Iterator!==undefined;
var is_ff3p=document.getElementsByClassName!==undefined;
var is_ff2=is_ff2p&&!is_ff3p;
function gb(){return hb('height');}
function ib(){return hb('width');}
function hb(jb){var kb=0,lb=0;
if(typeof(window.innerWidth)=='number'){kb=window.innerWidth;lb=window.innerHeight;}
else if(document.documentElement&&(document.documentElement.clientWidth||document.documentElement.clientHeight)){kb=document.documentElement.clientWidth;lb=document.documentElement.clientHeight;}
else if(document.body&&(document.body.clientWidth||document.body.clientHeight)){kb=document.body.clientWidth;lb=document.body.clientHeight;}
if(jb=='height')return lb;if(jb=='width')return kb;}
var gmwds=false;function start(){ShowOpenChatBox();mb("l1");mb("l2");mb("l3");mb("l4");initCounter();if(typeof init_local=='function'){init_local();}
if(quest.number===null){qst_handle();}
if(gmwds){gmwd();}
}
function nb(){return new Date().getTime();}
function db(){return Math.round(nb()/1000);}
function ob(pb){p=pb.innerHTML.split(":");qb=p[0]*3600+p[1]*60+p[2]*1;return qb;}
function rb(s){var sb,tb,ub;if(s>-2){sb=Math.floor(s/3600);tb=Math.floor(s/60)%60;ub=s%60;t=sb+":";if(tb<10){t+="0";}
t+=tb+":";if(ub<10){t+="0";}
t+=ub;}
else
{t="<a href=\"#\" onClick=\"return Popup(1);\"><span class=\"c0 t\">0:00:0</span>?</a>";}
return t;}
function initCounter()
{
	for(var i=1;;i++)
	{
		pb=document.getElementById("tp"+i);
		if(pb!=null)
		{
			ab[i]=new Object();
			ab[i].node=pb;
			ab[i].counter_time=ob(pb);
		}
		else
		{
			break;
		}
	}
	for(i=1;;i++)
	{
		pb=document.getElementById("timer"+i);
		if(pb!=null)
		{
			bbbb[i]=new Object();
			bbbb[i].node=pb;
			bbbb[i].counter_time=ob(pb);
		}
		else
		{
			break;
		}
	}
	for(i=1;;i++)
	{
		pb=document.getElementById("troop"+i);
		if(pb!=null)
		{
			cccc[i]=new Object();
			cccc[i].node=pb;
			cccc[i].counter_time=ob(pb);
		}
		else
		{
			break;
		}
	}
	for(i=1;;i++)
	{
		pb=document.getElementById("account"+i);
		if(pb!=null)
		{
			dddd[i]=new Object();
			dddd[i].node=pb;
			dddd[i].counter_time=ob(pb);
		}
		else
		{
			break;
		}
	}
	for(i=1;;i++)
	{
		pb=document.getElementById("rareTime"+i);
		if(pb!=null)
		{
			eeee[i]=new Object();
			eeee[i].node=pb;
			eeee[i].counter_time=ob(pb);
		}
		else
		{
			break;
		}
	}
	for(i=1;;i++)
	{
		pb=document.getElementById("limit"+i);
		if(pb!=null)
		{
			ffff[i]=new Object();
			ffff[i].node=pb;
			ffff[i].counter_time=ob(pb);
		}
		else
		{
			break;
		}
	}
	executeCounter();
}
function executeCounter()
{
	for(var i in ab)
	{
		vb=db()-cb;
		wb=rb(ab[i].counter_time+vb);
		ab[i].node.innerHTML=wb;
	}
	for(i in bbbb)
	{
		vb=db()-cb;
		xb=bbbb[i].counter_time-vb;
		if(eb==0&&xb<1)
		{
			eb=1;
			if(auto_reload==1)
			{
				setTimeout("document.location.reload()",1000);
			}
			else if(auto_reload==0)
			{
				setTimeout("mreload()",1000);
			}
		}
		else
		{}
		wb=rb(xb);bbbb[i].node.innerHTML=wb;
	}
	for(i in cccc)
	{
		vb=db()-cb;
		xb=cccc[i].counter_time-vb;
		if(eb==0&&xb<1)
		{
			eb=1;
			if(auto_reload==1)
			{
				setTimeout("document.location.reload()",1000);
			}
			else if(auto_reload==0)
			{
				setTimeout("mreload()",1000);
			}
		}
		else
		{}
		wb=rb(xb);cccc[i].node.innerHTML=wb;
	}
	for(i in dddd)
	{
		vb=db()-cb;
		xb=dddd[i].counter_time-vb;
		if(eb==0&&xb<1)
		{
			eb=1;
			if(auto_reload==1)
			{
				setTimeout("document.location.reload()",1000);
			}
			else if(auto_reload==0)
			{
				setTimeout("mreload()",1000);
			}
		}
		else
		{}
		wb=rb(xb);dddd[i].node.innerHTML=wb;
	}
	for(i in eeee)
	{
		vb=db()-cb;
		xb=eeee[i].counter_time-vb;
		if(eb==0&&xb<1)
		{
			eb=1;
			if(auto_reload==1)
			{
				setTimeout("document.location.reload()",1000);
			}
			else if(auto_reload==0)
			{
				setTimeout("mreload()",1000);
			}
		}
		else
		{}
		wb=rb(xb);eeee[i].node.innerHTML=wb;
	}
	for(i in ffff)
	{
		vb=db()-cb;
		xb=ffff[i].counter_time-vb;
		if(eb==0&&xb<1)
		{
			eb=1;
			if(auto_reload==1)
			{
				document.location.href='logout.php';
			}
			else if(auto_reload==0)
			{
				document.location.href='logout.php';				
			}
		}
		else
		{}
		wb=rb(xb);ffff[i].node.innerHTML=wb;
	}
	
	if(eb==0)
	{
		window.setTimeout("executeCounter()",1000);
	}
}
function mb(yb)
{
	pb=document.getElementById(yb);
	if(pb!=null)
	{
		fb[yb]=new Object();
		var zb=pb.innerHTML.match(/(\d+)\/(\d+)/);
		element=zb[0].split("/");
		$b=parseInt(element[0]);
		_b=parseInt(element[1]);
		ac=pb.title;if(ac!=0){bc=nb();
		timer[yb]=new Object();
		timer[yb].start=bc;
		timer[yb].production=ac;
		timer[yb].start_res=$b;
		timer[yb].max_res=_b;
		timer[yb].ms=3600000/ac;
		cc=100;
		if(timer[yb].ms<cc)
		{
			timer[yb].ms=cc;
		}
		timer[yb].node=pb;
		executeTimer(yb);
		}
		else
		{
			timer[yb]=new Object();
			fb[yb].value=$b;
		}
	}
}
function executeTimer(yb)
{
	vb=nb()-timer[yb].start;
	if(vb>=0)
	{
		dc=Math.round(timer[yb].start_res+vb*(timer[yb].production/3600000));
		if(dc>=timer[yb].max_res)
		{
			dc=timer[yb].max_res;
		}
		else
		{			
			window.setTimeout("executeTimer('"+yb+"')",timer[yb].ms);
		}
		fb[yb].value=dc;
		if(dc<0){
			dc=0;
		}
		timer[yb].node.innerHTML=dc+'/'+timer[yb].max_res;
	}
}
var ec=new Array(0,0,0,0,0);
function add_res(fc){gc=fb['l'+(5-fc)].value;hc=haendler*carry;ec[fc]=ic(ec[fc],gc,hc,carry);document.getElementById('r'+fc).value=ec[fc];
}
function upd_res(fc,jc){gc=fb['l'+(5-fc)].value;hc=haendler*carry;if(jc){kc=gc;}
else
{kc=parseInt(document.getElementById('r'+fc).value);}
if(isNaN(kc)){kc=0;}
ec[fc]=ic(parseInt(kc),gc,hc,0);document.getElementById('r'+fc).value=ec[fc];}
function ic(lc,mc,nc,oc){pc=lc+oc;if(pc>mc){pc=mc;}
if(pc>nc){pc=nc;}
if(pc==0){pc='';}
return pc;}
function qc(n,d){var p,i,x;if(!d)d=document;if((p=n.indexOf("?"))>0&&parent.frames.length){d=parent.frames[n.substring(p+1)].document;n=n.substring(0,p);}
if(!(x=d[n])&&d.all)x=d.all[n];for(var i=0;!x&&i<d.forms.length;i++)x=d.forms[i][n];for(var i=0;!x&&d.layers&&i<d.layers.length;i++)x=qc(n,d.layers[i].document);return x;}
function btm0(){var i,x,a=document.MM_sr;for(var i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++)x.src=x.oSrc;}
function btm1(){var i,j=0,x,a=btm1.arguments;document.MM_sr=new Array;for(var i=0;i<(a.length-2);i+=3)if((x=qc(a[i]))!=null){document.MM_sr[j++]=x;if(!x.oSrc)x.oSrc=x.src;x.src=a[i+2];}
}
function returnMinTroop(i){if(i<0){return 0}if(i>33){return 33}else return i;};
function returnMinBuilding(i){if(i<0){return 0}if(i>26){return 26}else return i;};
function Popup(i){pb=document.getElementById("ce");if(pb!=null){var rc="<div class=\"popup3\"><iframe allowTransparency=\"true\" frameborder=\"0\" id=\"Frame\" src=\"manual.php?id="+i+"\" width=\"360\" height=\"420\" border=\"0\" class=\"iframe\"></iframe></div><a href=\"#\" onClick=\"Close(); return false;\"><img src=\"images/un/a/x.gif\" border=\"1\" class=\"popup4\"></a>";pb.innerHTML=rc;}
if(gb()<700){sc=true;}
else{document.getElementById("ce").style.position='fixed';sc=false;}
if(!is_ie6&&!sc)return false;else return true;}
function PopupList(){pb=document.getElementById("ce");if(pb!=null){var rc="<div class=\"popup5\"><div style=\"padding-top:60px;\"></div><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"577\"><tr><td align=\"center\"><a href=\"#\" onclick=\"return PopupBuilding(0);\"><img src=\"images/un/a/congtrinh.png\" style=\"cursor:pointer;\"/></a></td><td width=\"30\"></td><td align=\"center\"><a href=\"#\" onclick=\"return PopupTroop(0);\"><img src=\"images/un/a/quandoi.png\" style=\"cursor:pointer;\"/></a></td></tr></table></div><a href=\"#\" onClick=\"Close(); return false;\"><img src=\"images/un/a/x.gif\" border=\"1\" class=\"popup5a\"></a>";pb.innerHTML=rc;}
if(gb()<700){sc=true;}
else{document.getElementById("ce").style.position='fixed';sc=false;}
if(!is_ie6&&!sc)return false;else return true;}
function PopupTroop(i){pb=document.getElementById("ce");if(pb!=null ){var rc="<div class=\"popup5\"><span style=\"position:absolute;z-index:1000; width:86px; height:16px; left:250px; top:30px;\"><img src =\"images/un/a/navi.gif\" width=\"86\" height=\"16\" style=\"cursor:pointer;\" usemap =\"#planetmap\"/><map id =\"planetmap\" name=\"planetmap\"><area shape=\"circle\" coords =\"12,9,10\" onClick=\"return PopupTroop("+returnMinTroop(i-1)+")\"/><area shape=\"circle\" coords =\"42,8,10\" onClick=\"return PopupTroop(0)\"/><area shape=\"circle\" coords =\"73,8,10\" onClick=\"return PopupTroop("+returnMinTroop(i+1)+")\"/></map></span><iframe allowTransparency=\"true\" frameborder=\"0\" scrolling=\"no\" id=\"Frame\" src=\"viewtroop.php?id="+i+"\" width=\"537\" height=\"280\" border=\"0\" class=\"iframe\"></iframe></div><a href=\"#\" onClick=\"Close1(); return false;\"><img src=\"images/un/a/x.gif\" border=\"1\" class=\"popup5a\"></a>";pb.innerHTML=rc;}
if(gb()<700){sc=true;}
else{document.getElementById("ce").style.position='fixed';sc=false;}
if(!is_ie6&&!sc)return false;else return true;}
function PopupBuilding(i){pb=document.getElementById("ce");pb.innerHTML='';if(pb!=null ){var rc="<div class=\"popup6\"><span style=\"position:absolute;z-index:1000; width:86px; height:16px; left:250px; top:30px;\"><img src =\"images/un/a/navi.gif\" width=\"86\" height=\"16\" style=\"cursor:pointer;\" usemap =\"#planetmap\"/><map id =\"planetmap\" name=\"planetmap\"><area shape=\"circle\" coords =\"12,9,10\" onClick=\"return PopupBuilding("+returnMinBuilding(i-1)+")\"/><area shape=\"circle\" coords =\"42,8,10\" onClick=\"return PopupBuilding(0)\"/><area shape=\"circle\" coords =\"73,8,10\" onClick=\"return PopupBuilding("+returnMinBuilding(i+1)+")\"/></map></span><iframe allowTransparency=\"true\" frameborder=\"0\" scrolling=\"auto\" id=\"Frame\" src=\"viewbuilding.php?id="+i+"\" width=\"527\" height=\"248\" border=\"0\" class=\"iframe\"></iframe></div><a href=\"#\" onClick=\"Close1(); return false;\"><img src=\"images/un/a/x.gif\" border=\"1\" class=\"popup5a\"></a>";pb.innerHTML=rc;}
if(gb()<700){sc=true;}
else{document.getElementById("ce").style.position='fixed';sc=false;}
if(!is_ie6&&!sc)return false;else return true;}

function OpenChatBox(){
	setCookie('showchatbox',1,365);
	document.getElementById("fixmetoo").style.display='none';
	pb=document.getElementById("ce");
	if(pb!=null){
		var rc="<div class=\"popup7\"><iframe allowTransparency=\"true\" frameborder=\"0\" scrolling=\"no\" id=\"Frame\" src=\"chatbox/minichat.php\" width=\"338\" height=\"310\" border=\"0\" class=\"iframe\"></iframe></div><a href=\"#\" onClick=\"Close1(); return false;\"><img src=\"images/un/a/x.gif\" border=\"1\" class=\"popup7a\"></a>";
		pb.innerHTML=rc;
	}
	
	if(gb()<700){
		sc=true;
	}else{
		document.getElementById("ce").style.position='fixed';sc=false;
	}
	
	if(!is_ie6&&!sc)return false;else return true;
}

function ShowOpenChatBox()
{
	if(getCookie('showchatbox')==1)
	{
		return OpenChatBox();
	}
}

function getCookie(c_name)
{
if (document.cookie.length>0)
  {
  c_start=document.cookie.indexOf(c_name + "=");
  if (c_start!=-1)
    { 
    c_start=c_start + c_name.length+1; 
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    } 
  }
return "";
}
function setCookie(c_name,value,expiredays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate()+expiredays);
document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : "; expires="+exdate.toGMTString());
}
function Close()
{
	pb=document.getElementById("ce");
	if(pb!=null)
	{
		pb.innerHTML='';
	}
	if(quest.anmstep!==false)
	{
		quest.anmstep=false;
	}
	document.location.reload();
}
function Close1()
{
	setCookie('showchatbox',0,365);	
	document.getElementById("fixmetoo").style.display='block';
	pb=document.getElementById("ce");
	if(pb!=null)
	{
		pb.innerHTML='';
	}
	if(quest.anmstep!==false)
	{
		quest.anmstep=false;
	}	
}
function Allmsg(){for(var x=0;x<document.msg.elements.length;x++){var y=document.msg.elements[x];if(y.name!='s10')y.checked=document.msg.s10.checked;}
}
function xy(){tc=screen.width+":"+screen.height;document.snd.w.value=tc;}
function my_village(){var uc=Math.round(0);var vc;var e=document.snd.dname.value;for(var i=0;i<dorfnamen.length;i++){if(dorfnamen[i].indexOf(e)>-1){uc++;vc=dorfnamen[i];}
}
if(uc==1){document.snd.dname.value=vc;}
}
function map(wc,xc,yc,zc,x,y){document.getElementById('x').firstChild.nodeValue=x;document.getElementById('y').firstChild.nodeValue=y;pb=document.getElementById("tb");if(pb!=null){if(zc==''){zc='-';}
var $c="<table cellspacing='1' cellpadding='2' class='tbg f8'><tr><td class='rbg f8' colspan='2'></a>"+wc+"</td></tr><tr><td width='45%' class='s7 f8'>"+text_spieler+"</td><td class='s7 f8'>"+xc+"</td></tr><tr><td class='s7 f8'>"+text_einwohner+"</td><td class='s7 f8' id='ew'>"+yc+"</td></tr><tr><td class='s7 f8'>"+text_allianz+"</td><td class='s7 f8'>"+zc+"</td></tr></table>";var _c="<table class='f8 map_infobox_grey' cellspacing='1' cellpadding='2'><tr><td class='c b' colspan='2' align='center'></a>"+text_details+"</td></tr><tr><td width='45%' class='c s7'>"+text_spieler+"</td><td class='c s7'>-</td></tr><tr><td class='c s7'>"+text_einwohner+"</td><td class='c s7'>-</td></tr><tr><td class='c s7'>"+text_allianz+"</td><td class='c s7'>-</td></tr></table>";if(xc!=''){pb.innerHTML=$c;}
else{pb.innerHTML=_c;}
}
}
function x_y(x,y){document.getElementById('x').firstChild.nodeValue=x;document.getElementById('y').firstChild.nodeValue=y;}
function popLarge(){bd=window.open("map_large.php?a="+center_x+"&b="+center_x,"map","top=100,left=25,width=975,height=550");bd.focus();return false;}
var cd=document.getElementById?1:0;var dd=document.all?1:0;var ed=(navigator.userAgent.indexOf("Mac")>-1)?1:0;var fd=(dd&&(!ed)&&(typeof(window.offscreenBuffering)!='undefined'))?1:0;var gd=fd;var hd=fd&&(window.navigator.userAgent.indexOf("SV1")!=-1);function changeOpacity(id,opacity){if(fd){id.style.filter='progid:DXImageTransform.Microsoft.Alpha(opacity='+(opacity*100)+')';}
else if(cd){id.style.MozOpacity=opacity;}
}
function jd(url,kd,ld,md){if(ld===undefined){ld='GET';}
var nd;if(window.XMLHttpRequest){nd=new XMLHttpRequest();}
else if(window.ActiveXObject){try{nd=new ActiveXObject("Msxml2.XMLHTTP");}
catch(e){try{nd=new ActiveXObject("Microsoft.XMLHTTP");}
catch(e){}
}
}
else{throw'Can not create XMLHTTP-instance';}
nd.onreadystatechange=function(){if(nd.readyState==4){if(nd.status==200){var od=nd.getResponseHeader('Content-Type');od=od.substr(0,od.indexOf(';'));switch(od){case'application/json':kd((nd.responseText==''?null:eval('('+nd.responseText+')')));break;case'text/plain':kd(nd.responseText);break;default:throw'Error';}
}
else{throw'Error';}
}
}
;nd.open(ld,url,true);if(ld=='POST'){nd.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=utf-8');var pd=qd(md);}
else{var pd=null;}
nd.send(pd);}
function qd(rd){var sd='';var td=true;for(var ud in rd){sd+=(td?'':'&')+ud+'='+window.encodeURI(rd[ud]);if(td){td=false;}
}
return sd;}
function mreload(){param='reload=auto';url=window.location.href;if(url.indexOf(param)==-1){if(url.indexOf('?')==-1){url+='?'+param;}
else
{url+='&'+param;}
}
document.location.href=url;}
function vd(){var wd=1*this.id.substring(4,5);var xd=1*(this.id.substring(5,7)=='p7'?7:1);yd(wd,xd);return false;}
function zd(wd,xd,$d){if($d==null){$d=0;}
if(m_c.size==null){throw'Error';}
var _d,ae;if(null===xd||1===xd){ae=m_c.size-1;}
else if(7==xd){_d=7;ae=-6;}
else{throw'Parameter steps muss 1 oder 7 sein.';}
var x,y,be,ce,z;z=m_c.z;switch(wd){case 1:x=z.x+3;y=z.y+3+$d;be=z.x-3;ce=y+ae;break;case 2:x=z.x+3+$d;y=z.y-3;be=x+ae;ce=z.y+3;break;case 3:x=z.x+3;y=z.y-3-$d;be=z.x-3;ce=y-ae;break;case 4:x=z.x-3-$d;y=z.y-3;be=x-ae;ce=z.y+3;break;}
return{'x':x,'y':y,'xx':be,'yy':ce}
;}
function de(x,y){if(x===null||y===null){throw('Error');}
if(y>400){y-=801;}
if(x>400){x-=801;}
if(y<-400){y+=801;}
if(x<-400){x+=801;}
return{'x':x,'y':y}
;}
function ee(wd,xd){var z={}
;z.x=m_c.z.x*1;z.y=m_c.z.y*1;switch(wd){case 1:z.y+=xd;break;case 2:z.x+=xd;break;case 3:z.y-=xd;break;case 4:z.x-=xd;break;}
m_c.z=(de(z.x,z.y));}
function fe(ge){return'ajax.php?f=k7&x='+ge.x+'&y='+ge.y+'&xx='+ge.xx+'&yy='+ge.yy;}
function yd(wd,xd){var ge,he;if(ie){return false;}
if(je()){if(ke){return false;}
ie=true;le();m_c.usealternate=false;m_c.cindex=0;ee(wd,xd);ge=zd(wd,xd);me=fe(ge);jd(me,ne);}
else{if(oe()){if(ke){return false;}
ke=true;ee(wd,xd);ge=zd(wd,xd,2);me=fe(ge);jd(me,ne);}
else if(pe()){ee(wd,xd);qe();le();}
else{ee(wd,xd);}
re(wd,xd);}
function ne(se){var te;if(oe()){te=ue(m_c.cindex);m_c.usealternate=false;ke=false;}
else{te=m_c.cindex;}
m_c.fields[te]=se;if(je()){re(wd,xd);ve(wd);ie=false;}
}
function oe(){return m_c.usealternate;}
function je(){return(wd!=m_c.dir||xd==7||(xd==1&&xd!=m_c.steps));}
function pe(){return(m_c.index==m_c.size);}
}
function we(wd,xd){m_c.dir=wd;m_c.steps=xd;}
function le(){m_c.index=0;}
function xe(){m_c.index++;if(m_c.index==m_c.size-2){m_c.usealternate=true;}
}
function qe(){m_c.cindex=ue(m_c.cindex);}
function re(wd,xd){if(1==xd){ye(wd);ze(m_c.fields[m_c.cindex],wd,xd);ve(wd);xe();$e();}
else if(7==xd){_e(m_c.fields[m_c.cindex]);$e();}
we(wd,xd);}
function ue(te){return(te==0?1:0);}
function _e(se){for(var i=0;i<7;i++){for(var j=0;j<7;j++){af(i,j,se[i][j]);}
}
}
function bf(cf,df){if(cf==''){if(df.href!=''){df.removeAttribute('href');df.style.cursor='default';}
}
else{df.href=cf;}
}
function af(ef,ff,gf){if(hf){return true;}
var jf,area;var kf;jf=lf(ef,ff,'i');area=lf(ef,ff,'a');jf.src=gf.src;bf(gf.href,area);area.details=[];if(null==gf.name){kf=['x','y'];}
else{kf=['dname','name','ew','ally','x','y'];}
for(var i in kf){area.details[kf[i]]=gf[kf[i]];}
}
function mf(e){if(hf){return true;}
var ud=(window.event)?event.keyCode:e.keyCode;var wd=nf(ud);if(wd!=0){return false;}
}
function map_init(){if(null==m_c.az){throw'Error';}
for(var p in m_c.az){document.getElementById('ma_'+p).onclick=vd;}
var of=['mcx','mcy'];for(var i in of){document.getElementById(of[i]).onfocus=function(){hf=true;}
;document.getElementById(of[i]).onblur=function(){hf=false;}
;}
document.onkeyup=pf;document.onkeydown=qf;document.onkeypress=mf;for(var i=0;i<7;i++){for(var j=0;j<7;j++){area=lf(i,j,'a');area.onmouseover=rf;area.onmouseout=sf;area.details=m_c.ad[i][j];}
}
}
function rf(){if(null==this.details.name){x_y(this.details.x,this.details.y);}
else{map(this.details.dname,this.details.name,this.details.ew,this.details.ally,this.details.x,this.details.y);}
}
function sf(){if(null==this.details.name){tf();}
else{$e();}
}
var m_c={'index':0,'dir':0,'size':null,'fields':[],'cindex':0,'usealternate':false}
;var ie=false;var ke=false;var uf=false;var hf=false;function ze(se,wd,xd){var vf,wf;for(var i=0;i<7;i++){switch(wd){case 1:vf=i;wf=6;gf=se[i][m_c.index];break;case 2:vf=6;wf=i;gf=se[m_c.index][i];break;case 3:vf=i;wf=0;gf=se[i][m_c.size-m_c.index-1];break;case 4:vf=0;wf=i;gf=se[m_c.size-m_c.index-1][i];break;}
af(vf,wf,gf);}
}
function xf(x,y,be,ce){lf(be,ce,'i')['src']=lf(x,y,'i')['src'];var yf=['onclick','onmouseover','onmouseout','details'];var gf=lf(x,y,'a');var zf=lf(be,ce,'a');for(var f in yf){zf[yf[f]]=gf[yf[f]];}
bf(gf.href,zf);}
function ye(wd){for(var i=0;i<7;i++){for(var j=1;j<7;j++){switch(wd){case 1:xf(i,j,i,j-1);break;case 2:xf(j,i,j-1,i);break;case 3:xf(i,6-j,i,7-j);break;case 4:xf(6-j,i,7-j,i);break;}
}
}
}
var $f=[];$f[38]=1;$f[39]=2;$f[40]=3;$f[37]=4;function nf(ud){if($f[ud]!==undefined){return $f[ud];}
return 0;}
var _f=0;function pf(e){if(hf){return true;}
var ud=((window.event)?event.keyCode:e.keyCode);if(16==ud){uf=false;}
var wd=nf(ud);if(wd==_f){_f=0;}
}
function m_r(wd,ag){if(_f==wd&&ag==bg){window.setTimeout('m_r('+wd+', '+ag+');',100);yd(wd,1);}
}
function ve(wd){if(1==wd||3==wd){var jb='y';}
else if(2==wd||4==wd){var jb='x';}
else{throw'Nur Richtungen 1-4 sind erlaubt';}
var cg,dg,eg;for(var i=0;i<7;i++){if(jb=='x'){dg=i;eg=0;}
else{dg=0;eg=i;}
cg=lf(dg,eg,'a').details[jb];document.getElementById('m'+jb+i).innerHTML=cg;}
}
var bg=0;function qf(e){if(hf){return true;}
var ud=(window.event)?event.keyCode:e.keyCode;if(ud==16){uf=true;}
var wd=nf(ud);if(wd!=0&&wd!=_f){var xd=(uf?7:1);yd(wd,xd);var ag=new Date().getTime();if(xd==1){window.setTimeout('m_r('+wd+', '+ag+');',500);}
bg=ag;_f=wd;}
if(wd!=0){return false;}
}
function lf(ef,ff,fg){c=gg(ef,ff,fg);return document.getElementById(gg(ef,ff,fg));}
function gg(ef,ff,fg){return fg+'_'+ef+'_'+ff;}
function tf(){var z=m_c.z;x_y(z.x,z.y);}
function $e(){var z=m_c.z;map('','','','',z.x,z.y);}
var quest={'anmstep':false}
;function hg(length,ig){if(length===undefined){length=8;}
if(ig===undefined){ig=0.5;}
var jg='0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';var hg='';for(var i=0;i<length;i++){var kg=Math.floor((Math.random()+ig)*0.5*jg.length);hg+=jg.substring(kg,kg+1);}
return hg;}
function lg(){var mg='ajax.php?f=qst';var ig=(Math.abs(quest.number)+1)/(Math.abs(quest.last)+1);return mg+'&cr='+hg(4,ig);}
function ng(){document.getElementById('ce').innerHTML='';var step;if(quest.anmstep===false){step={'step':{}
,'source':{}
,'current':{}
,'target':{'width':448,'height':482,'top':-1}
,'fps':50,'n':10,'i':0,'anm':{}
}
;step.target[quest.rtl?'right':'left']='-502';}
else{step=quest.anmstep;og(false);}
step.anm=document.getElementById('anm');for(var pg in step.target){step.source[pg]=Number(step.anm.style[pg].substr(0,step.anm.style[pg].length-2));step.current[pg]=step.source[pg];step.step[pg]=Math.round((step.target[pg]-step.source[pg])/step.n);}
step.timeout=1000/step.fps;quest.cstep=step;quest.anmlock=true;window.setTimeout('anm_step()',step.timeout);}
function qg(step){for(var pg in step.target){step.anm.style[pg]=step.current[pg]+'px';}
}
function rg(step){step.i++;if(step.i==2){step.anm.style.visibility='visible';}
for(var pg in step.target){step.current[pg]+=step.step[pg];}
return step;}
function og(sg){if(sg===undefined){sg==false;}
var tg=document.getElementById('ce');if(sg){var ug='<div id="popup3" class="popup3"></div><a href="#" onClick=\"Close(); return false;\"><img src="images/un/a/x.gif" border="1" class="popup4" alt="Close"></a>';tg.innerHTML=ug;vg();qst_wfm();}
else{tg.innerHTML='';}
}
function anm_step(){step=rg(quest.cstep);qg(step);if(step.i<step.n){window.setTimeout('anm_step()',step.timeout);}
else{step.anm.style.visibility='hidden';quest.anmlock=false;quest.cstep=false;if(quest.anmstep===false){step.current=step.target;step.target=step.source;step.source=step.current;qg(step);step.i=0;og(true);quest.anmstep=step;}
else{quest.anmstep=false;if(quest.number>=quest.last){document.getElementById('qge').innerHTML='';}
}
}
}
function qst_fhandle(){md={'val':1}
;jd(lg(),function(wg){}
,'POST',md
);qst_handle();}
function qst_handle(){if(quest.anmlock){return false;}
quest.markup=false;if(quest.anmstep===false){jd(lg(),function(wg){for(var ud in wg){quest[ud]=wg[ud];}
}
);}
ng();if(quest.ar){auto_reload=quest.ar;quest.ar=undefined;}
}
function qst_wfm(){var xg=document.getElementById('popup3');if(!quest.markup||!xg){if(!quest.anmlock){window.setTimeout('qst_wfm(true)',50);}
}
else{yg(quest);xg.innerHTML=quest.markup;zg=false;if(quest.reward.finish&&window.bld){var $g=document.getElementById('lbau1');if(bld.length<2&&bld[0].stufe==1&&bld[0].gid==1){document.getElementById('lbau1').innerHTML='';zg=0;}
else{for(var i in bld){if(bld[i].stufe==1&&bld[i].gid==1){document.getElementById('lbau1').getElementsByTagName('table')[0].deleteRow(i);zg=i;break;}
}
}
if(zg!==false){var _g=document.getElementById('rf'+bld[zg].aid);if(_g){_g.src='images/un/g/s/s'+(bld[zg].stufe)+'.gif';}
else{document.getElementById('f3').innerHTML+='<img src="images/un/g/s/s'+(bld[zg].stufe)+'.gif" class="rf'+bld[zg].aid+'">';}
}
quest.ar=auto_reload;auto_reload=-1;}
if(quest.reward.plus){var jf=document.getElementById('lleft').getElementsByTagName('img')[0];jf.src=jf.src.replace('0.gif','1.gif');}
quest.markup=false;quest.msg=false;}
}
function qst_weiter(){vg();jd(lg(),function(wg){document.getElementById('popup3').innerHTML=wg.markup;var ah=document.getElementById('qgei');ah.src=wg.qgsrc;yg(wg);}
);}
function vg(){document.getElementById('popup3').innerHTML='<iframe allowTransparency=\"true\" frameborder=\"0\" id=\"Frame\" src=\"manual.php?id=1\" width=\"360\" height=\"420\" border=\"0\"></iframe><a href=\"#\" onClick=\"Close(); return false;\"><img src=\"images/un/a/x.gif\" border=\"1\" class=\"popup4\" alt=\"Close\"></a>';}
function qst_enter(bh){if(bh===undefined){bh=false;}
var md;if(bh){md={'x':document.getElementById('qst_val_x').value,'y':document.getElementById('qst_val_y').value}
;}
else{md={'val':document.getElementById('qst_val').value}
;}
vg();jd(lg(),function(wg){for(var ud in wg){quest[ud]=wg[ud];}
}
,'POST',md
);qst_wfm();}
function qst_enter_coords(){qst_enter(true);}
function yg(ch){var ah=document.getElementById('qgei');if(ah&&ch.qgsrc){ah.src=ch.qgsrc;}
var dh=document.getElementById('n5');if(dh&&ch.msrc){dh.src=ch.msrc;}
if(ch.cookie){var date=new Date();date.setTime(date.getTime()+300000);document.cookie='t3fw=1; expires='+date.toUTCString()+';';}
if(ch.fest&&document.getElementById('lmid2').firstChild.nextSibling.className=='d1'){document.getElementById('lmid1').innerHTML+=ch.fest;}
}
function gmwd(){if(is_ff2&&document.getElementById("gmwi").offsetWidth<50){document.cookie="a3=2; expires=Wed, 1 Jan 2020 00:00:00 GMT";}
else{document.cookie="a3=1; expires=Wed, 1 Jan 2020 00:00:00 GMT";}
}
function gmc(){document.getElementById("gmw").style.display="none";document.cookie="a3=3; expires=Wed, 1 Jan 2020 00:00:00 GMT";}

