<script type="text/javascript">

T_demo.example.DDApp = function() {
    var dd, dd3, logger;
    return {
        init: function() {
            dd = new T_demo.example.DDOnTop("dragDiv1");
            dd.setHandleElId("handle1");
            dd3 = new T_demo.util.DDTarget("dragDiv3");
        }
    };
} ();
    
T_demo.util.Event.addListener(window, "load", T_demo.example.DDApp.init);
    
</script>

<div id="main"><img src="../images/ctc/map1.jpg" usemap="#Map" /><img src="../images/ctc/map2.jpg" /><img src="../images/ctc/map3.jpg" /><img src="../images/ctc/map4.jpg" /><img src="../images/ctc/map5.jpg" /><img src="../images/ctc/map6.jpg" /><img src="../images/ctc/map7.jpg" /><img src="../images/ctc/map8.jpg" /><img src="../images/ctc/map9.jpg" />
<map name="Map" id="Map">
	<area shape="rect" coords="19,28,216,194" href="javascript:void(0);" onclick="showReg('popup_div', 1);document.getElementById('dragDiv1').style.display='block';document.getElementById('fade').style.display='block'" />
</map>
</div>

<!--    <div id="dragDiv1" style="color:white; width:498px;z-index:2; display:none; position:absolute; top:20%; left:19%;">
	<div id="handle1" style="cursor:move;float:left;width:498px;"><img class="fl" id="popup_title" src="../images/ctc/popup_title_cb.jpg" usemap="#Map3"/>
    	<map name="Map3" id="Map3"><area shape="rect" coords="483,5,509,42" href = "javascript:void(0)" onclick = "document.getElementById('dragDiv1').style.display='none';" /></map>
	</div><div class="white_content" ><div class="white_content1" id="popup_div"></div></div>
    <div><img src="../images/ctc/popup_bottom.jpg" /></div>
</div>
-->
<div id="popup">
    <div id="dragDiv1" style="color:white; width:498px;z-index:22; display:none; position:absolute; top:18%;">
	<div id="handle1" style="cursor:move;float:left;width:498px;"><img class="fl" id="popup_title" src="../images/ctc/popup_title_cb.jpg" usemap="#Map3"/>
    	<map name="Map3" id="Map3"><area shape="rect" coords="483,5,509,42" href = "javascript:void(0)" onclick = "document.getElementById('dragDiv1').style.display='none';document.getElementById('fade').style.display='none';" /></map>
	</div>
    <div class="white_content" ><div class="white_content1" id="popup_div"></div></div>
    <div><img src="../images/ctc/popup_bottom.jpg" /></div>
	</div>
</div>

<script language="javascript">
if({check_message}){
	alert("{message}");
}

</script>

</body>
</html>
