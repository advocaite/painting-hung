<?php
session_start();
$token = md5(uniqid(rand(), true));
$_SESSION['token'] = $token;
if($_SESSION['alliance_id']>0)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="Js/markitup/jquery-1.5.1.js"></script>
    <script type="text/javascript" src="Js/markitup/jquery.markitup.js"></script>
    <script type="text/javascript" src="Js/markitup/sets/bbcode/set.js"></script>
    <script type="text/javascript" src="Js/markitup/ConvertBBcode2HTML.js"></script>
    <script type="text/javascript" src="Js/markitup/resizeImage.js"></script>
    <script type="text/javascript" src="Js/markitup/boxComment_ajax.js"></script>
    <link rel="stylesheet" type="text/css" href="Js/markitup/skins/markitup/style.css" />
    <link rel="stylesheet" type="text/css" href="Js/markitup/sets/bbcode/style.css" />
    <link rel="stylesheet" type="text/css" href="Styles/boxcomment.css" />
</head>
<body>
    <!--<div id="scrollArea">
        <div id="scroller">
        </div>
    </div>-->
    <table class="tborder" width="100%" cellspacing="2" cellpadding="6" border="0" align="center">
    <tbody>
        <tr>
            <td class="alt1" valign="top" id="container">
                <iframe frameborder="0" style="width: 100%; height: 100%;" src="iframeBoxComment.php"
                    name="fcb_frame" id="ifm_boxComment"></iframe>
                <!--<div id="divList" style="font-size: 13px; height: 100%; width: 100%; text-align: left;">
                    <span style='font-weight: bold; color: Green; margin-left: 2px;'>Hiện chưa có bình luận nào</span>
                </div>-->
            </td>
        </tr>
    </tbody>
    <!--<tbody id="notLogin" runat="server">
        <tr>
            <td class="alt2">
                Đăng nhập để tham gia hỏi đáp
            </td>
        </tr>
    </tbody>-->
    <tbody id="postcomment">
        <tr>
            <td class="alt2">
                <textarea id="txtContent" rows="3" cols="40" runat="server" class="NoEditor" onkeydown="return captureReturn(event);"
                    onblur="return captureReturn(event);"></textarea>
            </td>
        </tr>
        <tr>
            <td class="alt2">
                <div id="tdMsg">
                </div>
            </td>
        </tr>
        <tr>
            <td align="left" class="alt2">
                <div class="divLoginButton1" style="float: left;">
                    <input type="button" id="btnSend" value="Trả lời" class="loginButton" onclick="btnSend_Click();return false;"/>
                </div>
            </td>
        </tr>
    </tbody>
</table>
</body>
<script language="javascript" type="text/javascript">
    var div_content = $('#divList');
    /*ncm.showComment(div_content);
    autor = setInterval("ncm.showComment(div_content)", 1000 * 10);
    window.onload = function()
    {
        rsImg.ResizeThem(div_content);
    }*/

    $(document).ready(function()
    {
        // Add markItUp! to your textarea in one line
        // $('textarea').markItUp( { Settings }, { OptionalExtraSettings } );
        $('#txtContent').markItUp(mySettings);

        // You can add content from anywhere in your page
        // $.markItUp( { Settings } );    
        $('.add').click(function()
        {
            $.markItUp({ openWith: '<opening tag>',
                closeWith: '<\/closing tag>',
                placeHolder: "New content"
            }
                );
            return false;
        });

        // And you can add/remove markItUp! whenever you want
        // $(textarea).markItUpRemove();
        $('.toggle').click(function()
        {
            if ($("#txtContent.markItUpEditor").length === 1)
            {
                $("#txtContent").markItUpRemove();
                $("span", this).text("get markItUp! back");
            } 
            else
            {
                $('#txtContent').markItUp(mySettings);
                $("span", this).text("remove markItUp!");
            }
            return false;
        });
    });
    
    function captureReturn(event)
    {
        if (event.which || event.keyCode)
        {
            if ((event.which == 13) || (event.keyCode == 13))
            {
                btnSend_Click();
                return false;
            }
            else
            {
                var _content = $('#txtContent').val();
                if (_content.length > 100)
                {
                    alert("Nội dung không quá 100 ký tự!");
                    $('#txtContent').val(_content.substr(0, 100));
                }
                return true;
            }
        }
    }

    function btnSend_Click()
    {
        var urlInsertComment = encodeURI("./ShowBoxComment.php?action=insertcm&ct=" + $('#txtContent').val());
        var requestInsertComment = null;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            requestInsertComment = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            requestInsertComment = new ActiveXObject("Microsoft.XMLHTTP");
        }
        requestInsertComment.open('GET', urlInsertComment, true);
        requestInsertComment.send(null);
        requestInsertComment.onreadystatechange = function()
        {
            if (requestInsertComment != null && requestInsertComment.readyState == 4)
            {
                if (requestInsertComment.status == 200)
                {   
                    if (requestInsertComment.responseText != "Success")
                    {
                        kode_showTips($("#tdMsg"), 'error', requestInsertComment.responseText);
                    } else
                    {
                        $('#txtContent').val("");
                        ncm.showComment(div_content);
                        //$('#ifm_boxComment')[0].contentWindow.location.reload(true);
                    }
                }
            }
        };
    }

    function kode_showTips(tipobj, status, data)
    {
        if (status == 'error')
        {
            $(tipobj).addClass('divError').slideDown('slow');
        }

        if (data != '')
        {
            $(tipobj).html(data);
        }

        setTimeout(function()
        {
            $(tipobj).slideUp('slow');
        }, (3000));
        return false;
    }
</script>
<!--<script type="text/javascript" src="js/dom-drag.js"></script>
<script type="text/javascript" src="Js/scroll.js"></script>-->
</html>
<?php
}
?>