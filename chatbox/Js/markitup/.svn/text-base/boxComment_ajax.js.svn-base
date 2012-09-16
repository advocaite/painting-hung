var ncm = {
    //__prodId: "",
    showComment: function(divContent)
    {
        //divContent.innerHTML = cm.LoadingImage;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //ncm.GetNewsId();
        xmlhttp.open("GET", encodeURI("./ShowBoxComment.php"), true);
        xmlhttp.send(null);
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                if (xmlhttp.responseText != "")
                {
                    $(divContent).html(ncm.ReplaceEmotion(BBC2HTML(xmlhttp.responseText)));
                    rsImg.ResizeThem($(divContent));
                }
                else
                    $(divContent).html("");
            }
        };
    },
   /* GetNewsId: function()
    {
        var query = window.location.search.substring(1);
        var parms = query.split('&');
        for (var i = 0; i < parms.length; i++)
        {
            var pos = parms[i].indexOf('=');
            if (pos > 0)
            {
                var key = parms[i].substring(0, pos).toLowerCase();
                var val = parms[i].substring(pos + 1);
                if (key == 'pid') break;
            }
        }
        if (val != null)
        {
            ncm.__prodId = val;
        }
    },*/
    ReplaceEmotion: function(_content)
    {
        var arrEmotion = Array(":d", ":)", ":hh", ":((", ":x", ":-*", ":+)", "X-(", ":-S", ":-(", ":-@", ":-&", ":-/", ":-o", "I-)", "[tea]", "[-(", "[pig]", "[shuai]", "[bad]", "[smoke]", "[stupid]", "[ok]", "[thumbdown]", "[kill]", "[dog]", "[zan]", "[cool]");
        var arrEmotImg = Array(
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/grin.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/smile.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/hamarneh.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/cry.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/envy.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/love.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/shy.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/anger.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/fear.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/unhappy.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/uplook.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/puke.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/question.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/shock.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/sleepy.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/coffee.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/sweat.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/pig.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/shuai.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/bad.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/smoke.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/stupid.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/ok.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/thumbdown.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/kill.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/dog.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/zan.gif'>",
                    "<img src='Js/markitup/sets/bbcode/images/emoticon/thumbnail/cool.gif'>");
        for (var i = 0; i < arrEmotion.length; i++)
        {
            var index = _content.indexOf(arrEmotion[i]);
            while (index != -1)
            {
                _content = _content.replace(arrEmotion[i], arrEmotImg[i]);
                index = _content.indexOf(arrEmotion[i]);
            }
        }
        return _content;
    }
};