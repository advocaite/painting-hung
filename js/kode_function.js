$(document).ready(function(){
    $("#kode_img_sign").click(function(){
        $('#kode_img_sign').attr('src','../includes/_validate.php?r=' + Math.round(Math.random() * 10000));
    });

    $("body").bind('keyup',function(event) {
        if(event.keyCode==13){
            document.form.submit();
        }
    });

    if($ ("#hoverbody").length>0){
        hoverTagBgFromember_id("hoverbody","tr","#F1F1F1","#FFF","#FAFAFA");
    }

    if($ ("#hoverbody2").length>0){
        hoverTagBgFromember_id("hoverbody2","tr","#F1F1F1","#FFF","#FAFAFA");
    }
})

function kode_showTips( tipobj, status, data ){
    if(status =='success'){
        $(tipobj).addClass('box box-success').slideDown('slow');
    }
    else if(status =='error'){
        $(tipobj).addClass('box box_error').slideDown('slow');
    }
    else{
        $(tipobj).addClass('box box_info').slideDown('slow');
    }

    if(data != ''){
        $(tipobj).html(data);
    }

    setTimeout( function(){
        $(tipobj).slideUp("slow");
    }, ( 5000 ) );
    
    return false;
}

function popWaiting(){
    $('#screen').css({
        "display": "block",
        opacity: 0.7,
        "width":$(document).width(),
        "height":$(document).height()
    });
    $('#waiting-box').css({
        "display": "block"
    }).click(function(){
        $(this).css("display", "none");
        $('#screen').css("display", "none")
    });
}

function hideWaiting(){
    $('#waiting-box').css("display", "none");
    $('#screen').css("display", "none")
}

function hoverTagBgFromember_id(domember_id,hoverTag,color){
    var hoverElemt = document.getElementById(domember_id).getElementsByTagName(hoverTag);
    var formerlyColor = new Array();
    var len = hoverElemt.length;
    for(var i=1;i<len;i++){
        if(typeof(arguments[3]) != 'undefined' && i % 2 == 1) {
            hoverElemt[i].style.backgroundColor = arguments[3];
        }
        if(typeof(arguments[4]) != 'undefined' && i % 2 == 0) {
            hoverElemt[i].style.backgroundColor = arguments[4];
        }
        if(color != '') {
            hoverElemt[i].onmouseover = function(){
                formerlyColor[i] = this.style.backgroundColor;
                this.style.backgroundColor = color;
            }
            hoverElemt[i].onmouseout = function(){
                this.style.backgroundColor = formerlyColor[i];
            }
        }
    }
}

function logout(){
    $.ajax({
        type: "POST",
        url: "kode/kode_user_operation.php",
        dataType: 'text',
        data: "action=logout",
        success: function(data){
            location.replace("./");
        }
    })};
