/************************************************************************************************************
Ajax dynamic content
Copyright (C) 2006  DTHMLGoodies.com, Alf Magne Kalleland

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

Dhtmlgoodies.com., hereby disclaims all copyright interest in this script
written by Alf Magne Kalleland.

Alf Magne Kalleland, 2006
Owner of DHTMLgoodies.com
	
************************************************************************************************************/	

var enableCache=true;
var jsCache = new Array();
var dynamicContent_ajaxObjects = new Array();
function ajax_showContent(divId,ajaxIndex,url)
{
	if(document.getElementById(divId))
		document.getElementById(divId).innerHTML = dynamicContent_ajaxObjects[ajaxIndex].response;

	if(enableCache){
		jsCache[url] = 	dynamicContent_ajaxObjects[ajaxIndex].response;
	};
	dynamicContent_ajaxObjects[ajaxIndex] = false;

};

function ajax_loadContent(divId,url,type,id,sid)
{
	/* if(enableCache && jsCache[url]){
		document.getElementById(divId).innerHTML = jsCache[url]; 
		return;
	} */

	//var ajaxIndex = dynamicContent_ajaxObjects.length;
	//document.getElementById(divId).innerHTML = '<div style="width: 80px; height: 80px; text-align: center; background-color: #FFFFFF;"><img style="display:none;" src="components/com_domino/templates/domino/default/images/ohlookitsgottabeajax.gif"></div>';
	// <img src="components/com_domino/templates/default/images/loadingAnimation.gif" border="0"/>
	var ajax = new sack();
	url = url+'?type='+type+'&id='+id+'&sid='+sid+'&domino_template='+domino_template+'&domino_lang_user='+domino_lang_user;
	ajax.requestFile = url;	// Specifying which file to get
	ajax.onCompletion = function(){ if(document.getElementById(divId))document.getElementById(divId).innerHTML = ajax.response; };	// Specify function that will be executed after file has been found
	ajax.runAJAX();		// Execute AJAX function		
	
};

function ajax_mutilload(divId,url,x,y)
{
	url = url+'?x='+x+'&y='+y;
	if(enableCache && jsCache[url]){
		var u_div = document.getElementById(divId);
		if((u_div.innerHTML=='')||(u_div.innerHTML==null))
			u_div.innerHTML = jsCache[url];
		return;
	}else
	{
		
		var ajaxIndex = dynamicContent_ajaxObjects.length;
		//document.getElementById(divId).innerHTML = 'Loading content - please wait';
		dynamicContent_ajaxObjects[ajaxIndex] = new sack(url);
		//dynamicContent_ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
		dynamicContent_ajaxObjects[ajaxIndex].onCompletion = function(){ 
									ajax_showContent(divId,ajaxIndex,url); 
								};	// Specify function that will be executed after file has been found
		dynamicContent_ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function	
	}
	
	
};

function disableLogin(formname, yn){
	formname.username.disabled = yn;
	formname.passwd.disabled = yn;
	formname.remember.disabled = yn;
};
function ajax_login(formname)
{		
	if(formname.username){
		disableLogin(formname, true);
	};
	// lock screen
	_el('domino_Loading').style.visibility = "";
	_el('popup_Overlap').style.cursor='wait';
	_el('popup_Overlap').removeAttribute('onClick');
	_el('popup_Overlap').style.visibility = "";

	//formname = document.login;
	var ajax = new sack();
	var u_name = formname.username.value;
	ajax.setVar("username", u_name); 	
	ajax.setVar("passwd", formname.passwd.value); 
	// ajax.setVar("remember", formname.remember.value); 
	if(formname.remember.checked == true){
		ajax.setVar("remember", '1'); 
	}else{ ajax.setVar("remember", '0');}
	ajax.setVar("option", formname.option.value);
	ajax.setVar("task", formname.task.value); 
	ajax.setVar("return", formname.return_var.value); 
	ajax.setVar("islogfrommapfun", true); 
	ajax.requestFile = "index.php";
	ajax.method = 'POST';
	if(document.getElementById('PM_login_id')) closePopupInlineOverlap('login_id'); 
	ajax.onCompletion = function(){			
							// enable screen
							_el('domino_Loading').style.visibility = "hidden";
							_el('popup_Overlap').style.visibility = "hidden";
							if(ajax.response.indexOf('loginsuccessful_')>=0)
							{								
								string_result = ajax.response.replace('loginsuccessful_','');	
								params = string_result.split('{-}');		
								//set language
								if(params[5]!='')
									domino_lang_user= params[5];

								document.getElementById('login_tab').style.visibility='hidden';
								document.getElementById('logout_tab').style.visibility='visible';
								document.getElementById('login').style.visibility='hidden';
								document.getElementById('logout').style.visibility='visible';
								go_xySlow(32+parseInt(params[2]),32+parseInt(params[3]));
								//loadAjaxScreen();
								//load user info
								sid.value = params[4];
								ajax_userInfo('user_loadInfo','components/com_domino/ajax/user_info.php',sid.value);
								if (params[0]=='admin')
								{
								//	ajax_load_right_menu(); //ham bi loi IE
								}	
								toyou = u_name;
								uid.value = params[1];
								//trung dang DEL
								//friendsStatus(uid.value);
								showMeStatus();
								setTimeout("getOfflineMessages("+uid.value+",'')", 1000);
								//messageTimer=setTimeout("getMessageTimer("+msg_receiver_every+")", msg_receiver_every);
								getMessageTimerSlow();
								// refreshSession_ =setInterval (refreshSession, timeOut);
								createPlayer("components/com_domino/modules/music/getsqllist.php?uid="+params[1],false,true);
								
								//ajaxLoadMenu();																
								//reload SB
								ajax_updateWallInput();
								
								//reload tab
								reload_tab();	
								//reload popup game
								if(document.getElementById('PM_gameflash')!=null)
								{									
									frames['gameflash_iframe'].location.reload(true);									
								}
							}else if(ajax.response.indexOf('user is not actived')>=0){
								alertPopup(ajax.response);
							}else{
								alertPopup(error_login);
							
							}	
							if(formname.username){// form is removed
								disableLogin(formname, false); 
							}
						};
	ajax.runAJAX();
};

function ajax_logout()
{	
	_el('domino_Loading').style.visibility = "";
	_el('popup_Overlap').style.cursor='wait';
	_el('popup_Overlap').removeAttribute('onClick');
	_el('popup_Overlap').style.visibility = "";

	formname = document.logout;
	var ajax = new sack();
	ajax.setVar("option", formname.option.value); 
	ajax.setVar("task", formname.task.value); 
	ajax.setVar("return", formname.return_var.value); 
	ajax.requestFile = "index.php";
	ajax.method = 'POST';
	ajax.onCompletion = function(){
							//refreshSession();
							refreshLogout();
							
						};
	ajax.runAJAX();
};

function refreshLogout(){
	var ajax = new sack();
	ajax.setVar("sid", sid.value); 
	ajax.method = 'GET';
 	ajax.requestFile = "index.php?option=com_domino&task=refreshLogout";
//	ajax.requestFile = "components/com_domino/ajax/refresh_session.php&sid="+sid.value;
	ajax.onCompletion = function(){	
							if(ajax.response)
							{
								params = (ajax.response).split('{-}');
								sid.value = params[0];
								domino_lang_user = params[1];
								// hide load ding
								_el('domino_Loading').style.visibility = "hidden";
								_el('popup_Overlap').style.visibility = "hidden";

								if(messageTimer) clearInterval(messageTimer);
								//if(refreshSession_) clearInterval(refreshSession_);
								if(users_listsTimer) clearTimeout(users_listsTimer);
								document.getElementById('login').style.visibility='visible';
								document.getElementById('logout').style.visibility='hidden';
								document.getElementById('login_tab').style.visibility='visible';
								document.getElementById('logout_tab').style.visibility='hidden';
								ajax_userInfo('user_loadInfo','components/com_domino/ajax/user_info.php',sid.value);
								hideMeStatus();
								uid.value = ''; 
								toyou='';
								//ajaxLoadMenu();
								ajax_updateWallInput();										
								createPlayer("components/com_domino/modules/music/getsqllist.php",false,true);
								//reload tab
								//reload_tab();		
								//destroy thick-box
								TB_destroy();
								
								//reload popup game
								if(document.getElementById('PM_gameflash')!=null)
								{									
									frames['gameflash_iframe'].location.reload(true);									
								}
							}							
						};
	ajax.runAJAX();

};

function submit_logout(){
	formname = document.logout;
	formname.submit();
};

function ajax_userInfo(divId,url,sid)
{
	var ajax = new sack();
	//url = url+'?sid='+sid+'&y='+y;
	ajax.setVar("sid", sid); 
	ajax.setVar("domino_template", domino_template); 
	ajax.setVar("domino_lang_user", domino_lang_user); 
	ajax.requestFile = url;	// Specifying which file to get
	ajax.method = 'GET';
	ajax.onCompletion = function(){ 
				//alert(ajax.response);
				params = ajax.response.split('{TASK_BAR}');
				if(document.getElementById(divId)){
					document.getElementById(divId).innerHTML=params[0];
				};
				document.getElementById('domino_main_info').innerHTML=params[1];
				//TB_init();
				};	// Specify function that will be executed after file has been found
	ajax.runAJAX();		// Execute AJAX function	
		
};

function ajaxLoadMenu()
{
	var ajax = new sack();
	ajax.setVar("sid", sid.value); 
	ajax.setVar("domino_template", domino_template); 
	ajax.setVar("domino_lang_user", domino_lang_user); 
	ajax.requestFile = 'components/com_domino/ajax/load_menu.php';	//'components/com_domino/templates/'+domino_template+'/domino/tmpl/right_click_menu.php';	
	ajax.method = 'GET';
	ajax.onCompletion = function(){ 
				//alert(ajax.response);
				document.getElementById('rightClickMenu').innerHTML=ajax.response;
				};	// Specify function that will be executed after file has been found
	ajax.runAJAX();		// Execute AJAX function	
		
};

function goHome(username){
	var ajax = new sack();
	ajax.setVar("username", username); 	
	ajax.requestFile = "components/com_domino/ajax/load_xy_user.php";
	ajax.method = 'GET';
	ajax.onCompletion = function(){							
							if(ajax.response!='')
							{								
								string_result = ajax.response;	
								params = string_result.split(',');								
								go_xySlow(32+parseInt(params[0]),32+parseInt(params[1]));
								//loadAjaxScreen();
								var f="showYouStatus("+parseInt(params[2])+",'"+username+"')";
								setTimeout(f,5000);
							}
							else
								alertPopup(username+' chua có nhà trên Xgoon.');
						};
	ajax.runAJAX();
};

function ajax_check_money(msg)
{
	var ajax = new sack();
	//ajax.setVar("uid", uid);
	ajax.setVar("ide", currentId);
	ajax.setVar("task", 'checkmoney');
	ajax.setVar("domino_lang_user", domino_lang_user);
	ajax.requestFile = "components/com_domino/modules/register/ajax/register.php";
	ajax.method = 'GET';
	ajax.onCompletion = function()
	{
		hide_popup_menu();
		if(ajax.response=='0')
		{
			showPopupLogin();
		}else if(ajax.response=='1')
		{
			/* if(confirm(msg))
				create_newUser(600,500);
			else
				return false; */
			confirmPopup(msg,'create_newUser(600,500);','');
		}
		else
		{
			alertPopup(ajax.response);
		}
	};
	ajax.runAJAX();
};
function ajax_reload_user(lasttime)
{
	var ajax = new sack();
	ajax.setVar("lastupdate", lasttime); 	
	ajax.requestFile = "components/com_domino/ajax/reload_user.php";
	ajax.method = 'GET';
	ajax.onCompletion = function(){							
							if(ajax.response!='')
							{								
								var text=ajax.response;
								var myarr=text.split("///");
								for(i=0;i<myarr.length-1;i++)
								{
									if(myarr[i])
									{
										var divarr=myarr[i].split("***");
										if(divarr.length>0)
										{
											
											exe_change_user_icon(divarr[0],divarr[1],divarr[2],divarr[3],divarr[4],divarr[5],divarr[6],divarr[7],divarr[8],divarr[9],divarr[10],divarr[11],divarr[12],divarr[13],divarr[14],divarr[15]);
											
										}
									}
							
								};
								
							}
							
						};
	ajax.runAJAX();
};


function exe_change_user_icon(_id,_left,_top,_width, _height, _background, _mousedown,_kindmenu, _kindtooltip, _dbclick , _mouseover,_option,_mouseout,_action,_divuser,_zindex)
{
	if(_action =='3')
	{
		obj = document.getElementById(_id);
		if(!obj)
			return false;
		document.getElementById(_id).style.display='none';
				
	}
	else 
	{
		if(_action=='2')
		{
			obj = document.getElementById(_id);
			if(!obj)
				return false;
		};
			
		if(_action=='1')
		{
			var tmp=_divuser;
			tmp=tmp.replace("user_","");
			tmp=tmp.replace("x","");
			tmp=tmp.replace("y","");
			tmp=tmp.replace("_","-");
			if(!user_load_array[''+tmp])
				return false;
			obj = document.createElement('div');
			obj.setAttribute('id', _id);
		};
		//obj.innerHTML="hi";
		obj.style.left = _left;
		obj.style.top = _top;
		obj.style.width = _width;
		obj.style.height = _height;
		obj.style.zIndex = _zindex;
		obj.style.backgroundImage = _background;
		obj.setAttribute('kindmenu', _kindmenu);
		obj.setAttribute('kindtooltip', _kindtooltip);
		obj.setAttribute('option', _option);	
		if(_mouseover!=null&&_mouseover!='')
			obj.onmouseover= function (evt){ajax_showTooltip('components/com_domino/modules/domino/ajax/tooltip.php',this,''+_mouseover);return false; };
		else
			obj.removeAttribute('onmouseover');		
		
		if(_mousedown!=null&&_mousedown!='')
			
			if( _mousedown=='1')
				obj.onmousedown=function (evt){rightMenu(this,_divuser);ajax_hideTooltip();};
			else if(_mousedown == '2')
				obj.onmousedown=function (evt){rightMenu(this,_divuser);set_option(this);ajax_hideTooltip();};
			else
				obj.onmousedown=function (evt){popup_url=_mousedown;rightMenu(this,_divuser);set_option(this);ajax_hideTooltip();};
			
		else
			obj.removeAttribute('onmousedown');
			
		if(_mouseout!=null&&_mouseout!='')
			obj.onmouseout=function (evt){ajax_hideTooltip();};
		else
			obj.removeAttribute('onmouseout');	

		if(_dbclick!=null&&_dbclick!='')
		{
		//	alert('add');
			obj.setAttribute("ondblclick", "javascript: window.open('"+_dbclick+"')");
		}
		else
		{
		//	alert('remove');
			obj.removeAttribute('ondblclick');
		} ;
		if(_action=='1')
		{
			_divuser.appendChild(obj);
		}
	}
};
