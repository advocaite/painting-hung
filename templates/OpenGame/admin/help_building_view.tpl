<div id="column-content">
    <div id="content">
	<div><a href="map_troops.php">&nbsp;Wg_building_type&nbsp;</a> | <a href="manager_building_types.php?s=1"><span style="background: rgb(0, 153, 255) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;">&nbsp;Help building&nbsp;</span></a></div>
     <script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>	 
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "elm2",
		theme : "advanced",
		skin : "o2k7",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});	
</script>
<form method="post" action="">
<fieldset><center><input name="radiobutton" onclick="setTypingMode(4)" checked="checked" type="radio">Bật bộ gõ tiếng Việt&nbsp;<input name="radiobutton" onclick="setTypingMode(0)" type="radio"> Tắt&nbsp;&nbsp;&nbsp;<select name="select_name" class="fm" style="width:180px;height=40px;" onchange="javascript:window.open('manager_building_types.php?s=1&id='+this.options[this.selectedIndex].value,'_top')">
{option}
</select></center></fieldset>
<textarea id="elm2" name="content" rows="30" cols="80" style="width: 100%" onkeyup="initTyper(this);">{content}
</textarea>	
<input type="submit" name="save" value="Lưu lại" class="fm" />
<input type="reset" name="reset" value="Làm lại" class="fm" />
</form>   
    </div>
</div>
