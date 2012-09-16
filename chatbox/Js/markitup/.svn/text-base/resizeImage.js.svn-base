var rsImg = {
    ResizeThem: function(divContent)
    {
        var maxheight = 500;
        var maxwidth = 500;
        var imgs = divContent.find("img");
        for (var p = 0; p < imgs.length; p++)
        {
            //if (imgs[p].getAttribute("alt") == "")
            //{
            var w = parseInt(imgs[p].width);
            var h = parseInt(imgs[p].height);
            if (w > maxwidth)
            {
                rsImg.SetStyleImg(imgs[p]);
                imgs[p].onclick = function()
                {
                    var iw = window.open(this.src, 'ImageViewer', 'resizable=1');
                    iw.focus();
                };
                h = (maxwidth / w) * h;
                w = maxwidth;
                imgs[p].height = h;
                imgs[p].width = w;
            }
            if (h > maxheight)
            {
                rsImg.SetStyleImg(imgs[p]);
                imgs[p].onclick = function()
                {
                    var iw = window.open(this.src, 'ImageViewer', 'resizable=1');
                    iw.focus();
                };
                imgs[p].width = (maxheight / h) * w;
                imgs[p].height = maxheight;
            }
            //}
        }
    },
    SetStyleImg: function(img)
    {
        img.style.border = "1px solid #92AEC6";
        img.style.padding = "2px";
        img.style.cursor = "pointer";
    }
}