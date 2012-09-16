<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="Js/markitup/jquery-1.5.1.js"></script>
    <script type="text/javascript" src="Js/markitup/ConvertBBcode2HTML.js"></script>
    <script type="text/javascript" src="Js/markitup/resizeImage.js"></script>
    <script type="text/javascript" src="Js/markitup/boxComment_ajax.js"></script>
</head>
<body>
    <div id="divList" style="font-size: 13px; height: 100%; width: 100%; text-align: left;">
        <span style='font-weight: bold; color: Green; margin-left: 2px;'>Hiện chưa có bình luận nào</span>
    </div>
</body>

<script language="javascript" type="text/javascript">
    var div_content = $('#divList');
    ncm.showComment(div_content);
    autor = setInterval("ncm.showComment(div_content)", 1000 * 10);
    window.onload = function()
    {
        rsImg.ResizeThem(div_content);
    }
</script>

</html>