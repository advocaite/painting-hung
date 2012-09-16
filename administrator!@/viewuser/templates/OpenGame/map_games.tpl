<!--<link rel="stylesheet" type="text/css" href="bangdo/new.css">
--><link rel="stylesheet" type="text/css" href="bangdo/unx.css">
<script type="text/javascript" src="bangdo/jquery.js"></script>
<script type="text/javascript">
window.onload = function(){
	$.ajax({url: "server__.php",data: {link_villa},success: function(html){$("#mk").html(html);}});
}

$(document).ready(function(){
	
});
</script>
<div id="mk"></div></div>