<div id="main_1">
    <div id="boxleft">
   	  <img class="fl" src="../images/ctc/title.png" />
      <div class="list_user">
<script type="text/javascript">

/******************************************
* Scrollable content script II- © Dynamic Drive (www.dynamicdrive.com)
* Visit http://www.dynamicdrive.com/ for full source code
* This notice must stay intact for use
******************************************/

iens6=document.all||document.getElementById
ns4=document.layers

//specify speed of scroll (greater=faster)
var speed=4

if (iens6){
document.write('<div id="container" style="position:relative;width:145px;height:235px;overflow:hidden;">')
document.write('<div id="content" style="position:absolute;width:150px;left:0;top:0">')
}
</script>

<ilayer name="nscontainer" width=150 height=160 clip="0,0,175,160">
<layer name="nscontent" width=150 height=160 visibility=hidden>

<!--INSERT CONTENT HERE-->
{ali_attack_list}
           
<!--END CONTENT-->

</layer>
</ilayer>

<script language="JavaScript1.2">
if (iens6)
document.write('</div></div>')
</script>
<script language="JavaScript1.2">
if (iens6){
var crossobj=document.getElementById? document.getElementById("content") : document.all.content
var contentheight=crossobj.offsetHeight
}
else if (ns4){
var crossobj=document.nscontainer.document.nscontent
var contentheight=crossobj.clip.height
}

function movedown(){
if (iens6&&parseInt(crossobj.style.top)>=(contentheight*(-1)+230))
crossobj.style.top=parseInt(crossobj.style.top)-speed+"px"
else if (ns4&&crossobj.top>=(contentheight*(-1)+100))
crossobj.top-=speed
movedownvar=setTimeout("movedown()",20)
}

function moveup(){
if (iens6&&parseInt(crossobj.style.top)<=0)
crossobj.style.top=parseInt(crossobj.style.top)+speed+"px"
else if (ns4&&crossobj.top<=0)
crossobj.top+=speed
moveupvar=setTimeout("moveup()",20)

}

function getcontent_height(){
if (iens6)
contentheight=crossobj.offsetHeight
else if (ns4)
document.nscontainer.document.nscontent.visibility="show"
}
window.onload=getcontent_height
</script>
<div class="updown"><a href="#" onMouseover="moveup()" onMouseout="clearTimeout(moveupvar)"><img src="../images/ctc/up.png" /></a><a href="#" onMouseover="movedown()" onMouseout="clearTimeout(movedownvar)"><img class="pd" src="../images/ctc/down.png" /></a></div>
		</div>
        <img class="fl pd" src="../images/ctc/box_bottom.png" />
    </div>
    <div class="imgtitle">
    <div align="center" class="imgtitle"><img src="../images/ctc/{image_name}.png" /></div>
    <div class="fl">
    <img src="../images/ctc/x.gif" width="404" height="363" border="0" usemap="#Map" />
        <map name="Map" id="Map">
          <area shape="circle" coords="375,150,30" href="tc.php?id={dtk_7}" />
          <area shape="circle" coords="109,105,30" href="tc.php?id={dtk_4}" />
          <area shape="circle" coords="302,107,30" href="tc.php?id={dtk_5}" />
          <area shape="circle" coords="210,186,30" href="tc.php?id={dtk_3}" />
          <area shape="circle" coords="129,283,30" href="tc.php?id={dtk_2}" />
          <area shape="circle" coords="286,286,30" href="tc.php?id={dtk_6}" />
          <area shape="circle" coords="34,211,30" href="tc.php?id={dtk_1}" />
        </map>
    </div>
    </div>
    <div id="boxright">
       	<img class="fl" src="../images/ctc/title_def.png" />
      <div class="list_user">
<script type="text/javascript">

/******************************************
* Scrollable content script II- © Dynamic Drive (www.dynamicdrive.com)
* Visit http://www.dynamicdrive.com/ for full source code
* This notice must stay intact for use
******************************************/

iens6=document.all||document.getElementById
ns4=document.layers

//specify speed of scroll (greater=faster)
var speed=4

if (iens6){
document.write('<div id="container1" style="position:relative;width:145px;height:235px;overflow:hidden">')
document.write('<div id="content1" style="position:absolute;width:150px;left:0;top:0">')
}
</script>

<ilayer name="nscontainer1" width=150 height=160 clip="0,0,175,160">
<layer name="nscontent1" width=150 height=160 visibility=hidden>

<!--INSERT CONTENT HERE-->
			{ali_defend_list}
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
if (iens6&&parseInt(crossobj1.style.top)>=(contentheight1*(-1)+230))
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
</script>

<div class="updown"><a href="#" onMouseover="moveup1()" onMouseout="clearTimeout(moveupvar)"><img src="../images/ctc/up.png" /></a><a href="#" onMouseover="movedown1()" onMouseout="clearTimeout(movedownvar)"><img class="pd" src="../images/ctc/down.png" /></a></div>
		</div>
        <img class="fl pd" src="../images/ctc/box_bottom.png" />

    </div>
    <div id="bangdiem">
    	<table width="764" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <th colspan="11" height="38" style="background:url(../images/ctc/bangdiem_title.png) no-repeat; padding:0">BẢNG ĐIỂM</th>
    </tr>
    <tr>
      <td>Lượt</td>
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td>4</td>
      <td>5</td>
      <td>6</td>
      <td>7</td>
      <td>8</td>
      <td>9</td>
      <td>10</td>
    </tr>
    <tr><td>Thời gian</td>
    <td>{t_1}</td>
    <td>{t_2}</td>
    <td>{t_3}</td>
    <td>{t_4}</td>
    <td>{t_5}</td>
    <td>{t_6}</td>
    <td>{t_7}</td>
    <td>{t_8}</td>
    <td>{t_9}</td>
    <td>{t_10}</td>
    </tr>
  
  <tr>
    <td>Công</td>
    <td>{at_p_1}</td>
    <td>{at_p_2}</td>
    <td>{at_p_3}</td>
    <td>{at_p_4}</td>
    <td>{at_p_5}</td>
    <td>{at_p_6}</td>
    <td>{at_p_7}</td>
    <td>{at_p_8}</td>
    <td>{at_p_9}</td>
    <td>{at_p_10}</td>
    </tr>
  <tr>
    <td>Thủ</td>
    <td>{df_p_1}</td>
    <td>{df_p_2}</td>
    <td>{df_p_3}</td>
    <td>{df_p_4}</td>
    <td>{df_p_5}</td>
    <td>{df_p_6}</td>
    <td>{df_p_7}</td>
    <td>{df_p_8}</td>
    <td>{df_p_9}</td>
    <td>{df_p_10}</td>
    </tr>
</table>

  </div>
      <div id="menu_ct">
    	<div class="{ctn_class_1}"><a href="ct.php?id=1">Chi Lăng</a></div>
        <div class="{ctn_class_2}"><a href="ct.php?id=2">Bạch Đằng</a></div>
        <div class="{ctn_class_3}"><a href="ct.php?id=3">Bồ Đằng</a></div>
        <div class="{ctn_class_4}"><a href="ct.php?id=4">Trà Lân</a></div>
        <div class="{ctn_class_5}"><a href="ct.php?id=5">Như Nguyệt</a></div>
        <div class="mn"><a href="ctc.php">Thoát</a></div>
    </div>

</div>
</body>
</html>