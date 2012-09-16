<script type="text/javascript">

T_demo.example.DDApp = function() {
    var dd, dd3, logger;
    return {
        init: function() {
            dd = new T_demo.example.DDOnTop("dragDiv1");
            dd.setHandleElId("handle1");
            //dd3 = new T_demo.util.DDTarget("dragDiv3");
        }
    };
} ();
    
T_demo.util.Event.addListener(window, "load", T_demo.example.DDApp.init);
    
</script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>

	<div id="{div_main_id}">
    	<div id="tc_boxlelt">
        	<div class="offname" id="side_attack_name" onclick="changeSideName('popup_div', {id}, {sid_1});document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block';"><a href="javascript:void(0)" >{side_attack_name}</a></div>
            <div class="offstatus" id="attack_troop_move_status">{attack_troop_move_status}</div>
        </div>
        <div id="tc_middle">
        	<div id="ct_title"><!--<img src="../images/ctc/{img_name}.png" />--><img src="../images/ctc/{img_name}.png" /></div>
            <div id="list_att">
            	<img src="../images/ctc/tc_box_title.jpg" /><div class="tc_list">
                	<script type="text/javascript">


iens61=document.all||document.getElementById
ns4=document.layers

//specify speed of scroll (greater=faster)
var speed=1

if (iens61){
document.write('<div id="container" style="position:relative;width:105px;height:77px;overflow:hidden;">')
document.write('<div id="content" style="position:absolute;width:150px;left:0;top:0">')
}
</script>
<ilayer name="nscontainer" width=150 height=160 clip="0,0,175,160">
<layer name="nscontent" width=250 height=160 visibility=hidden>

<!--INSERT CONTENT HERE-->
			{user_attack_list}
            
<!--END CONTENT-->

</layer>
</ilayer>

<script language="JavaScript1.2">
if (iens61)
document.write('</div></div>')
</script>
<script language="JavaScript1.2">
if (iens61){
var crossobj=document.getElementById? document.getElementById("content") : document.all.content
var contentheight=crossobj.offsetHeight
}
else if (ns4){
var crossobj=document.nscontainer.document.nscontent
var contentheight=crossobj.clip.height
}

function movedown(){
if (iens61&&parseInt(crossobj.style.top)>=(contentheight*(-1)+180))
crossobj.style.top=parseInt(crossobj.style.top)-speed+"px"
else if (ns4&&crossobj.top>=(contentheight*(-1)+100))
crossobj.top-=speed
movedownvar=setTimeout("movedown()",20)
}

function moveup(){
if (iens61&&parseInt(crossobj.style.top)<=0)
crossobj.style.top=parseInt(crossobj.style.top)+speed+"px"
else if (ns4&&crossobj.top<=0)
crossobj.top+=speed
moveupvar=setTimeout("moveup()",20)

}

function getcontent_height(){
if (iens61)
contentheight=crossobj.offsetHeight
else if (ns4)
document.nscontainer.document.nscontent.visibility="show"
}
window.onload=getcontent_height
</script>
                </div>
                <img class="fl" src="../images/ctc/tc_box_bottom.jpg" usemap="#Map" />
                <map name="Map" id="Map">
<area shape="rect" coords="34,1,55,14" href="#" onMouseover="moveup()" onMouseout="clearTimeout(moveupvar)" /><area shape="rect" coords="68,1,88,14" href="#" onMouseover="movedown()" onMouseout="clearTimeout(movedownvar)" />
</map>
            </div>
            <div id="list_def">
            	<img src="../images/ctc/tc_box_title.jpg" /><div class="tc_list">
<script type="text/javascript">

iens6=document.all||document.getElementById
ns4=document.layers

//specify speed of scroll (greater=faster)
//var speed=1

if (iens6){
document.write('<div id="container1" style="position:relative;width:105px;height:77px;overflow:hidden;">');
document.write('<div id="content1" style="position:absolute;width:150px;left:0;top:0">');
}
</script>
<ilayer name="nscontainer1" width=150 height=160 clip="0,0,175,160">
<layer name="nscontent1" width=150 height=160 visibility=hidden>

<!--INSERT CONTENT HERE-->
            {user_defend_list}
            
<!--END CONTENT-->

</layer>
</ilayer>

<script language="JavaScript1.2">
if (iens6)
document.write('</div></div>')
</script>
<script language="JavaScript1.2">
if (iens6){

var crossobj1=document.getElementById? document.getElementById("content1") : document.all.content1
var contentheight1=crossobj1.offsetHeight
}
else if (ns4){
var crossobj1=document.nscontainer.document.nscontent1
var contentheight1=crossobj1.clip.height
}

function movedown1(){
if (iens6&&parseInt(crossobj1.style.top)>=(contentheight1*(-1)+180))
crossobj1.style.top=parseInt(crossobj1.style.top)-speed+"px"
else if (ns4&&crossobj1.top>=(contentheight1*(-1)+100))
crossobj1.top-=speed
movedownvar=setTimeout("movedown1()",20)
}

function moveup1(){
if (iens6&&parseInt(crossobj1.style.top)<=0)
crossobj1.style.top=parseInt(crossobj1.style.top)+speed+"px"
else if (ns4&&crossobj1.top<=0)
crossobj1.top+=speed
moveupvar=setTimeout("moveup1()",20)

}

function getcontent_height1(){
if (iens6)
contentheight1=crossobj1.offsetHeight
else if (ns4)
document.nscontainer1.document.nscontent1.visibility="show"
}

window.onload=getcontent_height1
</script>                </div>
                <img src="../images/ctc/tc_box_bottom.jpg" usemap="#Map1" />
                                <map name="Map1" id="Map1">
<area shape="rect" coords="34,1,55,14" href="#" onMouseover="moveup1()" onMouseout="clearTimeout(moveupvar)" /><area shape="rect" coords="68,1,88,14" href="#" onMouseover="movedown1()" onMouseout="clearTimeout(movedownvar)" />
</map>

            </div>
        </div>
        <div id="tc_boxright">
        	<div class="offname" id="side_defend_name" onclick="changeSideName('popup_div', {id}, {sid_0});document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block';"><a href="javascript:void(0)" >{side_defend_name}</a></div>
            <div class="offstatus" id="defend_troop_move_status" ><table>{defend_troop_move_status}</table></div>
      </div>
        <div id="countdown"><span id="timer1">{left_time}</span></div>
        <div id="listtroops">
            	<img title="{troop_name_c1}" class="a1" src="{img_c1}" />
                <img title="{troop_name_c2}" class="a2" src="{img_c2}" />
                <img title="{troop_name_c3}" class="a3" src="{img_c3}" />
                <img title="{troop_name_c4}" class="a4" src="{img_c4}" />
                <img title="{troop_name_c5}" class="a5" src="{img_c5}" />
                <img title="{troop_name_c6}" class="a6" src="{img_c6}" />
                <img title="{troop_name_c7}" class="a7" src="{img_c7}" />
                
            	<img title="{troop_name_c8}" class="s1" src="{img_c8}" />
                <img title="{troop_name_c9}" class="s2" src="{img_c9}" />
                <img title="{troop_name_c10}" class="s3" src="{img_c10}" />
                <img title="{troop_name_c11}" class="s4" src="{img_c11}" />
                <img title="{troop_name_c12}" class="s5" src="{img_c12}" />
                <img title="{troop_name_c13}" class="s6" src="{img_c13}" />
                <img title="{troop_name_c14}" class="s7" src="{img_c14}" />
                
            	<img title="{troop_name_c15}" class="m1" src="{img_c15}" />
                <img title="{troop_name_c16}" class="m2" src="{img_c16}" />
                <img title="{troop_name_c17}" class="m3" src="{img_c17}" />
                <img title="{troop_name_c18}" class="m4" src="{img_c18}" />
                <img title="{troop_name_c19}" class="m5" src="{img_c19}" />
                <img title="{troop_name_c20}" class="m6" src="{img_c20}" />
                <img title="{troop_name_c21}" class="m7" src="{img_c21}" />
      </div>
      
            <div id="listtroops1">
            	<img title="{troop_name_t1}" class="a21" src="{img_t1}" />
                <img title="{troop_name_t2}" class="a22" src="{img_t2}" />
                <img title="{troop_name_t3}" class="a23" src="{img_t3}" />
                <img title="{troop_name_t4}" class="a24" src="{img_t4}" />
                <img title="{troop_name_t5}" class="a25" src="{img_t5}" />
                <img title="{troop_name_t6}" class="a26" src="{img_t6}" />
                <img title="{troop_name_t7}" class="a27" src="{img_t7}" />
                
            	<img title="{troop_name_t8}" class="s21" src="{img_t8}" />
                <img title="{troop_name_t9}" class="s22" src="{img_t9}" />
                <img title="{troop_name_t10}" class="s23" src="{img_t10}" />
                <img title="{troop_name_t11}" class="s24" src="{img_t11}" />
                <img title="{troop_name_t12}" class="s25" src="{img_t12}" />
                <img title="{troop_name_t13}" class="s26" src="{img_t13}" />
                <img title="{troop_name_t14}" class="s27" src="{img_t14}" />

            	<img title="{troop_name_t15}" class="m21" src="{img_t15}" />
                <img title="{troop_name_t16}" class="m22" src="{img_t16}" />
                <img title="{troop_name_t17}" class="m23" src="{img_t17}" />
                <img title="{troop_name_t18}" class="m24" src="{img_t18}" />
                <img title="{troop_name_t19}" class="m25" src="{img_t19}" />
                <img title="{troop_name_t20}" class="m26" src="{img_t20}" />
                <img title="{troop_name_t21}" class="m27" src="{img_t21}" />
      </div>
      
      <div id="tc_button">
           <div class="b_contain">
           <img src="../images/ctc/tc_btn_army.png" border="0" usemap="#Map10" /><img src="../images/ctc/tc_btn_report.png" usemap="#Map11" /><div id="smap"><img class="abc" src="../images/ctc/tc_smap.jpg" usemap="#sMap" /><div class="flag1" {flag_a_display_1} ><img src="../images/ctc/codo.gif" /></div><div class="flag2" {flag_d_display_1}><img src="../images/ctc/coxanh.gif" /></div><div class="flag3" {flag_a_display_4}><img src="../images/ctc/codo.gif" /></div><div class="flag4" {flag_d_display_4}><img src="../images/ctc/coxanh.gif" /></div><div class="flag5" {flag_a_display_2}><img src="../images/ctc/codo.gif" /></div><div class="flag6" {flag_d_display_2}><img src="../images/ctc/coxanh.gif" /></div><div class="flag7" {flag_a_display_3}><img src="../images/ctc/codo.gif" /></div><div class="flag8" {flag_d_display_3}><img src="../images/ctc/coxanh.gif" /></div><div class="flag9" {flag_a_display_5}><img src="../images/ctc/codo.gif" /></div><div class="flag10" {flag_d_display_5}><img src="../images/ctc/coxanh.gif" /></div></div><img src="../images/ctc/tc_btn_rank.png" usemap="#Map12" /><img src="../images/ctc/tc_btn_quit.png" usemap="#Map13" />
            <map name="Map10" id="Map10"><area shape="rect" coords="33,70,74,112" href = "javascript:void(0)" onclick = "overview('popup_div', {id});document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block'" /></map>
            <map name="Map11" id="Map11"><area shape="rect" coords="2,71,42,113" href = "javascript:void(0)" onclick = "showReport('popup_div', {id}, 0);document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block'" /></map>
            <map name="Map12" id="Map12"><area shape="rect" coords="7,72,45,111" href="javascript:void(0)"  onclick = "showPointTable('popup_div', {ct_id});document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block'" /></map>
            <map name="Map13" id="Map13"><area shape="rect" coords="4,72,45,113" href="ctc.php" /></map>

<map name="sMap" id="sMap">
<area shape="circle" title="Thủ" coords="251,63,15" href="tc.php?id={id_6}" />
<area shape="circle" title="Công" coords="43,60,15" href="tc.php?id={id_0}" />
<area shape="circle" title="Thủy" coords="148,63,15" href="tc.php?id={id_2}" />
<area shape="circle" title="Kim" coords="100,23,15" href="tc.php?id={id_1}" />
<area shape="circle" title="Thổ" coords="199,98,15" href="tc.php?id={id_5}" />
<area shape="circle" title="Hỏa" coords="191,24,15" href="tc.php?id={id_4}" />
<area shape="circle" title="Mộc" coords="100,97,15" href="tc.php?id={id_3}" />
<area shape="rect" title="Bản đồ lớn" coords="243,8,272,33" href="javascript:void(0)" onclick = "document.getElementById('bigmap').style.display='block';document.getElementById('fade').style.display='block'" />
</map>

<!--            	
				<div class="bt1 {ortherlink_0}"><a href="tc.php?id={id_0}">Công</a></div>
                <div class="bt2 {ortherlink_1}"><a href="tc.php?id={id_1}">Kim</a></div>
                <div class="bt2 {ortherlink_2}"><a href="tc.php?id={id_2}">Thủy</a></div>
                <div class="bt2 {ortherlink_3}"><a href="tc.php?id={id_3}">Mộc</a></div>
                <div class="bt2 {ortherlink_4}"><a href="tc.php?id={id_4}">Hỏa</a></div>
                <div class="bt2 {ortherlink_5}"><a href="tc.php?id={id_5}">Thổ</a></div>
                <div class="bt1 {ortherlink_6} bt_final"><a href="tc.php?id={id_6}">Thủ</a></div> 
                <div class="bt3"><a href = "javascript:void(0)" onclick = "overview('popup_div', {id});document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block'">Quân đội</a></div>
                <div class="bt4"><a href = "javascript:void(0)" onclick = "showReport('popup_div', {id}, 0);document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block'">Chiến báo</a></div>
                <div class="bt5"><a href="ct.php?id={ct_id}">Chiến trường</a></div>
                <div class="bt3"><a href="ctc.php">Thoát</a></div>
-->            
			</div>
      </div>   
</div>
<!--the end-->
<div id="popup">
    <div id="dragDiv1" style="color:white; width:498px;z-index:22; display:none; position:absolute; top:18%;">
	<div id="handle1" style="cursor:move;float:left;width:498px;"><img class="fl" id="popup_title" src="../images/ctc/popup_title_cb.jpg" usemap="#Map3"/>
    	<map name="Map3" id="Map3"><area shape="rect" coords="483,5,509,42" href = "javascript:void(0)" onclick = "document.getElementById('dragDiv1').style.display='none';document.getElementById('fade').style.display='none';" /></map>
	</div><div class="white_content" ><div class="white_content1" id="popup_div"></div></div>
    <div><img src="../images/ctc/popup_bottom.jpg" /></div>
</div>
<div id="bigmap">
	<img src="../images/ctc/bmap.jpg" usemap="#bMap" />
    <map name="bMap" id="bMap"><area shape="rect" coords="440,10,481,46" href = "javascript:void(0)" onclick = "document.getElementById('bigmap').style.display='none';document.getElementById('fade').style.display='none';" /></map>
    <div class="bflag1" {flag_a_display_1} ><img src="../images/ctc/bcodo.gif" /></div>
    <div class="bflag2" {flag_d_display_1}><img src="../images/ctc/bcoxanh.gif" /></div>
    <div class="bflag3" {flag_a_display_4}><img src="../images/ctc/bcodo.gif" /></div>
    <div class="bflag4" {flag_d_display_4}><img src="../images/ctc/bcoxanh.gif" /></div>
    <div class="bflag5" {flag_a_display_2}><img src="../images/ctc/bcodo.gif" /></div>
    <div class="bflag6" {flag_d_display_2}><img src="../images/ctc/bcoxanh.gif" /></div>
    <div class="bflag7" {flag_a_display_3}><img src="../images/ctc/bcodo.gif" /></div>
    <div class="bflag8" {flag_d_display_3}><img src="../images/ctc/bcoxanh.gif" /></div>
    <div class="bflag9" {flag_a_display_5}><img src="../images/ctc/bcodo.gif" /></div>
    <div class="bflag10" {flag_d_display_5}><img src="../images/ctc/bcoxanh.gif" /></div>
</div>

</div>
<div id="fade" class="black_overlay"></div>
<script language="javascript">start();</script>
</body>
</html>
