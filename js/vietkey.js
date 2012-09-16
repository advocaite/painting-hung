/*vietuni8.js - R.19.10.01 @JOTREQFA@P*Veni*Vidi*Vici*
* by Tran Anh Tuan [tuan@physik.hu-berlin.de] 
* Copyright (c) 2001, 2002 AVYS e.V.. All Rights Reserved.
*
* Originally published and documented at http://www.avys.de/
* You may use this code without fee on noncommercial web sites. 
* You may NOT alter the code and then call it another name and/or resell it.
* The copyright notice must remain intact on srcipts.
*/

// interface for HTML:
//
         
var supported = (document.all || document.getElementById);
var disabled = false;
var charmapid = 1;
var keymodeid = 0;
var linebreak = 0;
var theTyper = null;

reset = function(){}
telexingVietUC = initTyper;

function setTypingMode(mode) {
  keymodeid = mode;
  if (theTyper) theTyper.keymode= initKeys();
  if (!supported && !disabled) {
    alert("Xin loi, trinh duyet web cua ban khong cho phep dung VietTyping.\n");
    disabled = true;  
  }
}

initCharMap = function() { return new CVietUniCodeMap(); }

initKeys = function() {
  switch (keymodeid) {
    case 1: return new CTelexKeys();
    case 2: return new CVniKeys();
    case 3: return new CViqrKeys();
    case 4: return new CAllKeys();
    default: return new CVKOff();
  }
}

function initTyper(txtarea) {
  txtarea.vietarea= true;
  txtarea.onkeyup= null;
  if (!supported) return;
  txtarea.onkeypress= vietTyping;
  txtarea.getCurrentWord= getCurrentWord;
  txtarea.replaceWord= replaceWord;
  txtarea.onkeydown= onKeyDown;
  txtarea.onmousedown= onMouseDown;
}

function getEvt(evt) {
  return document.all? event.keyCode: (evt && evt.which)? evt.which: 0;
}

function onKeyDown(evt) {
  var c= getEvt(evt);
  if ((c==10) || (c==13)) { reset(1); linebreak= 1; }
  else if ((c<49) && (c!=16) && (c!=20)) { linebreak= 0; reset(c==32); }
  return true;
}

function onMouseDown(evt) { reset(0); linebreak= 0; return true; }

function vietTyping(evt) {
  var c= getEvt(evt);
  if(theTyper) theTyper.value= this.getCurrentWord();
  else theTyper= new CVietString(this.getCurrentWord());
  var changed= (c>32) && theTyper.typing(c);
  if (changed) this.replaceWord(theTyper.value);
  return !changed; 
}

function getCurrentWord() {
  if(!document.all) return this.value;
  var caret = this.document.selection.createRange();
  var backward = -10;
  do {
    var caret2 = caret.duplicate();
    caret2.moveStart("character", backward++);
  } while (caret2.parentElement() != this && backward <0);
  this.curword = caret2.duplicate();
  return caret2.text;
}

function replaceWord(newword) {
  if(!document.all) { this.value= newword; return; }
  this.curword.text = newword;
  this.curword.collapse(false);
}
// end interface


// "class": CVietString
//
function CVietString(str) {
  this.value= str;
  this.keymode= initKeys();
  this.charmap= initCharMap();
  this.ctrlchar= '-';
  this.changed= 0;

  this.typing= typing;
  this.Compose= Compose;
  this.Correct= Correct;
  this.findCharToChange= findCharToChange;
  return this;
}

function typing(ctrl) {
  this.changed = 0;
  this.ctrlchar = String.fromCharCode(ctrl);
  if (linebreak) linebreak= 0; else this.keymode.getAction(this);
  this.Correct();
  return this.changed;
}

function Compose(type) {
  var info = this.findCharToChange(type);
  if (!info) return;
  var telex;
  if (info[0]=='\\') telex= [1,this.ctrlchar,1];
  else if (type>6) telex= this.charmap.getAEOWD(info[0], type, info[3]);
  else telex= this.charmap.getDau(info[0], type);
  if (!(this.changed = telex[0])) return;
  this.value = this.value.replaceAt(info[1],telex[1],info[2]);
  if (!telex[2]) { spellerror= 1; this.value+= this.ctrlchar; }
}

function Correct() {
  if (this.charmap.maxchrlen || !document.all) return 0;
  var tmp= this.value;
  if ('nNcC'.indexOf(this.ctrlchar)>=0) tmp+= this.ctrlchar;
  var er= /[^\x01-\x7f](hn|hc|gn)$/i.exec(tmp);
  if (er) {
    this.value= tmp.substring(0,tmp.length-2)+er[1].charAt(1)+er[1].charAt(0);
    this.changed= 1;
  }
  else if(!this.changed) return 0;
  er= /\w([^\x01-\x7f])(\w*)([^\x01-\x7f])\S*$/.exec(this.value);
  if (!er) return 0;
  var i= this.charmap.isVowel(er[1]);
  var ri= (i-1)%24 + 1, ci= (i-ri)/24;
  var i2= this.charmap.isVowel(er[3]);
  if (!ci || !i2) return 0;
  var ri2= (i2-1)%24 + 1, ci2= (i2-ri2)/24;
  var nc= this.charmap.charAt(ri)+ er[2]+ this.charmap.charAt(ci*24+ri2);
  this.value= this.value.replace(new RegExp(er[1]+er[2]+er[3],'g'), nc);
}

function findCharToChange(type) {
  var lastchars= this.charmap.lastCharsOf(this.value, 5);
  var i= 0, c=lastchars[0][0], chr=0;
  if (c=='\\') return [c,this.value.length-1,1];
  if (type==15) while (!(chr=this.charmap.isVD(c))) {
    if ((c < 'A') || (i>=4) || !(c=lastchars[++i][0])) return null;
  }
  else while( "cghmnptCGHMNPT".indexOf(c)>=0) {
    if ((c < 'A') || (i>=2) || !(c=lastchars[++i][0])) return null;
  }
  c = lastchars[0][0].toLowerCase();
  var pc = lastchars[1][0].toLowerCase();
  var ppc = lastchars[2][0].toLowerCase();
  if (i==0 && type!=15) {
    if ( (chr=this.charmap.isVowel(lastchars[1][0]))
      && ("uyoia".indexOf(c)>=0) && !this.charmap.isUO(pc,c)
      && !((pc=='o' && c=='a') || (pc=='u' && c=='y'))
      && !((ppc=='q' && pc=='u') || (ppc=='g' && pc=='i')) ) ++i;
    if (c=='a' && (type==9 || type==7)) i= 0;
  }
  c= lastchars[i][0];
  if ((i==0 || chr==0) && type!=15) chr= this.charmap.isVowel(c);
  if (!chr) return null;
  var clen= lastchars[i][1], isuo=0;
  if ((i>0) && (type==7 || type==8 || type==11)) {
    isuo=this.charmap.isUO(lastchars[i+1][0],c);
    if (isuo) { chr=isuo; clen+=lastchars[++i][1]; isuo=1; }
  }
  var pos= this.value.length;
  for (var j=0; j<= i; j++) pos -= lastchars[j][1];
  return [chr, pos, clen, isuo];
}
// end CVietString


// character-map template
//
function CVietCharMap(){
this.vietchars = null;
this.length = 149;
return this; 
}

CVietCharMap.prototype.charAt= function(ind){ 
  var chrcode = this.vietchars[ind];
  return chrcode ? String.fromCharCode(chrcode) : null;
}

CVietCharMap.prototype.isVowel= function(chr){
  var ind = this.length-5;
  while ((chr != this.charAt(ind)) && ind) --ind;
  return ind;
}

CVietCharMap.prototype.isVD= function (chr){
  var ind = this.length-5;
  while ((chr != this.charAt(ind)) && (ind < this.length)) ++ind;
  return (ind<this.length)? ind: 0;
}
                         
CVietCharMap.prototype.isCol= function (col, chr){
  var i=12, ind=col+1;
  while (i>=0 && (this.charAt(i*12+ind)!=chr)) --i;
  return (i>=0)? i*12+ind : 0;
}

CVietCharMap.prototype.isUO= function (c1, c2){
  if (!c1 || !c2) return 0;
  var ind1= this.isCol(9, c1);
  if (!ind1) ind1= this.isCol(10, c1);
  if (!ind1) return 0;
  var ind2= this.isCol(6, c2);
  if (!ind2) ind2= this.isCol(7, c2);
  if (!ind2) ind2= this.isCol(8, c2);
  if (!ind2) return 0;
  return [ind1,ind2];
}

CVietCharMap.prototype.getDau= function (ind, type){
  var accented= (ind < 25)? 0: 1;
  var ind_i= (ind-1) % 24 +1;
  var charset= (type == 6)? 0 : type;
  if ((type== 6) && !accented) return [0];
  var newind= charset*24 + ind_i;
  if (newind == ind) newind= ind_i;
  var chr= this.charAt(newind);
  if (!chr) chr= this.lowerCaseOf(0,newind);
  return [1, chr, newind>24 || type==6];
}

var map=[
[7,7,7,8,8, 8,9,10,11,15],
[0,3,6,0,6, 9,0, 3, 6, 0],
[1,4,7,2,8,10,1, 4, 7, 1]
];
CVietCharMap.prototype.getAEOWD= function(ind, type, isuo){
  var c=0, i1=isuo? ind[0]: ind;
  var vc1= (type==15)? (i1-1)%2 : (i1-1)%12;
  if (isuo) {
    base= ind[1]-(ind[1]-1)%12;
    if (type==7 || type==11) c= this.charAt(i1-vc1+9)+this.charAt(base+7);
    else if (type==8) c= this.charAt(i1-vc1+10)+this.charAt(base+8);
    return [c!=0, c, 1];
  }
  var i= -1, shift= 0, del= 0;
  while (shift==0 && ++i<map[0].length) {
    if (map[0][i]==type) {
      if(map[1][i]==vc1) shift= map[2][i]-vc1;
      else if(map[2][i]==vc1) shift= map[1][i]-vc1;
    }
  }
  if (shift==0) {
    if (type==7 && (vc1==2 || vc1==8)) shift=-1;
    else if ((type==9 && vc1==2) || (type==11 && vc1==8)) shift=-1;
    else if (type==8 && (vc1==1 || vc1==7)) shift=1;
    del= 1;
  } else del=(shift>0);
  var chr= this.charAt(i1+shift);
  if (!chr) chr= this.lowerCaseOf(0,i1+shift);
  return [shift!=0, chr, del];
}

CVietCharMap.prototype.lastCharsOf= function(str, num){
  if (!num) return [str.charAt(str.length-1),1];
  var vchars = new Array(num);
  for (var i=0; i< num; i++) vchars[i]= [str.charAt(str.length-i-1),1];
  return vchars;
}
// end CVietCharMap prototype

String.prototype.replaceAt= function(i,newchr,clen){
  return this.substring(0,i)+ newchr + this.substring(i+clen);
}

// output map: class CVietUniCodeMap
// 
function CVietUniCodeMap(){ var map= new CVietCharMap();
map.vietchars = new Array(
"UNICODE",
97, 226, 259, 101, 234, 105, 111, 244, 417, 117, 432, 121,
65, 194, 258, 69, 202, 73, 79, 212, 416, 85, 431, 89,
225, 7845, 7855, 233, 7871, 237, 243, 7889, 7899, 250, 7913, 253,
193, 7844, 7854, 201, 7870, 205, 211, 7888, 7898, 218, 7912, 221,
224, 7847, 7857, 232, 7873, 236, 242, 7891, 7901, 249, 7915, 7923,
192, 7846, 7856, 200, 7872, 204, 210, 7890, 7900, 217, 7914, 7922,
7841, 7853, 7863, 7865, 7879, 7883, 7885, 7897, 7907, 7909, 7921, 7925,
7840, 7852, 7862, 7864, 7878, 7882, 7884, 7896, 7906, 7908, 7920, 7924,
7843, 7849, 7859, 7867, 7875, 7881, 7887, 7893, 7903, 7911, 7917, 7927,
7842, 7848, 7858, 7866, 7874, 7880, 7886, 7892, 7902, 7910, 7916, 7926,
227, 7851, 7861, 7869, 7877, 297, 245, 7895, 7905, 361, 7919, 7929,
195, 7850, 7860, 7868, 7876, 296, 213, 7894, 7904, 360, 7918, 7928,
100, 273, 68, 272);
return map;
}

// input methods: class C...Keys
function CVietKeys() {
  this.getAction= function(typer){
    var i= this.keys.indexOf(typer.ctrlchar.toLowerCase());
    if(i>=0) typer.Compose(this.actions[i]);
  }
  return this;
}

function CVKOff() {
  this.off = true;
  this.getAction= function(){};
  return this;
}

function CTelexKeys() {
  var k= new CVietKeys();
  k.keys= "sfjrxzaeowd";
  k.actions= [1,2,3,4,5,6,9,10,11,8,15];
  k.istelex= true;
  return k;
}

function CVniKeys() {
  var k= new CVietKeys();
  k.keys= "0123456789";
  k.actions= [6,1,2,4,5,3,7,8,8,15];
  return k;
}

function CViqrKeys() {
  var k= new CVietKeys();
  k.keys= "\xB4/'`.?~-^(*+d";
  k.actions= [1,1,1,2,3,4,5,6,7,8,8,8,15];
  return k;
}

function CAllKeys() {
  var k= new CVietKeys();
  k.keys= "sfjrxzaeowd0123456789\xB4/'`.?~-^(*+d";
  k.actions= [1,2,3,4,5,6,9,10,11,8,15,6,1,2,4,5,3,7,8,8,15,1,1,1,2,3,4,5,6,7,8,8,8,15];
  k.istelex= true;
  return k;
}

// end vietuni.js
