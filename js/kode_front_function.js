$(document).ready(function(){
    $('#LoginForm').submit(function() {
        var options = {
            beforeSubmit : ValidateLoginform,
            success: function(responseText) {
                $("#submit_btn").removeAttr("disabled");
                $('#kode_img_sign').attr('src','../includes/_validate.php?r=' + Math.round(Math.random() * 10000));
                if($.trim(responseText).indexOf('Error:') == -1){
                    var values = $.parseJSON(responseText);
                    kode_showTips($("#kode_result"), 'success', values.msg);                    
                    setTimeout(function() {
                        location.replace(values.referer);
                    }, 1500);
                }
                else{
                    kode_showTips($("#kode_result"), 'error', responseText);
                }
            }
        };
        $(this).ajaxSubmit(options);
        return false;
    });

    $('#unsubscribeForm').submit(function() {
        var options = {
            target: '#kode_result',
            beforeSubmit : ValidateUnsubscribeForm,
            success: function(responseText) {
                $("#submit_btn").removeAttr("disabled");
                $('#kode_img_sign').attr('src','../includes/_validate.php?r=' + Math.round(Math.random() * 10000));
                if($.trim(responseText).indexOf('Error:')!= 0){
                    kode_showTips($("#kode_result"), 'success');
                }
                else{
                    kode_showTips($("#kode_result"), 'error');
                }
            }
        };
        $(this).ajaxSubmit(options);
        return false;
    });
    $('#subscribeForm').submit(function() {

        var options = {
            target: '#kode_result',
            beforeSubmit : ValidateSubscribeForm,
            success: function(responseText) {
                $("#submit_btn").removeAttr("disabled");
                if($.trim(responseText).indexOf('Error:')!= 0){
                    kode_showTips($("#kode_result"), 'success');
                }
                else{
                    kode_showTips($("#kode_result"), 'error');
                }
            }
        };
        $(this).ajaxSubmit(options);
        return false;
    });
    
    $('#changeEmailForm').submit(function() {
        var options = {
            target: '#kode_result',
            beforeSubmit : ValidateChangeEmailForm,
            success: function(responseText) {
                $("#submit_btn").removeAttr("disabled");
                $('#kode_img_sign').attr('src','../includes/_validate.php?r=' + Math.round(Math.random() * 10000));
                if($.trim(responseText).indexOf('Error:')!= 0){
                    kode_showTips($("#kode_result"), 'success');
                }
                else{
                    kode_showTips($("#kode_result"), 'error');
                }
            }
        };
        $(this).ajaxSubmit(options);
        return false;
    });
    
    $('#retrievePasswordForm').submit(function() {
        var options = {
            target: '#kode_result',
            beforeSubmit : ValidateRetrievePasswordForm,
            success: function(responseText) {
                $("#submit_btn").removeAttr("disabled");
                $('#kode_img_sign').attr('src','../includes/_validate.php?r=' + Math.round(Math.random() * 10000));
                if($.trim(responseText).indexOf('Error:') == -1){
                    kode_showTips($("#kode_result"), 'success');
                }
                else{
                    kode_showTips($("#kode_result"), 'error');
                }
            }
        };
        $(this).ajaxSubmit(options);
        return false;
    });

    $('#changePasswordForm').submit(function() {
        var options = {
            target: '#kode_result',
            beforeSubmit : ValidateChangePasswordForm,
            success: function(responseText) {
                $("#submit_btn").removeAttr("disabled");
                $('#kode_img_sign').attr('src','../includes/_validate.php?r=' + Math.round(Math.random() * 10000));
                if($.trim(responseText).indexOf('Error:') == -1){
                    kode_showTips($("#kode_result"), 'success');
                }
                else{
                    kode_showTips($("#kode_result"), 'error');
                }
            }
        };
        $(this).ajaxSubmit(options);
        return false;
    });

    $('#forwardForm').submit(function() {
        var options = {
            target: '#kode_result',
            beforeSubmit : ValidateforwardForm,
            success: function(responseText) {
                $("#submit_btn").removeAttr("disabled");
                $('#kode_img_sign').attr('src','../includes/_validate.php?r=' + Math.round(Math.random() * 10000));
                if($.trim(responseText).indexOf('Error:') == -1){
                    kode_showTips($("#kode_result"), 'success');
                }
                else{
                    kode_showTips($("#kode_result"), 'error');
                }
            }
        };
        $(this).ajaxSubmit(options);
        return false;
    });
})

function ValidateLoginform(formData, jqForm, options){
    var form = jqForm[0];
    $('.error').remove();
    if (!form.username.value) {
        $(form.username).parent().append('<span class="error">Chưa nhập tài khoản.</span>');
    }
    if (!form.password.value) {
        $(form.password).parent().append('<span class="error">Chưa nhâp mật khẩu.</span>');
    }
    if (!form.validate_code.value) {
        $(form.validate_code).parent().append('<span class="error">Chưa nhập mã xác thực.</span>');
    }
    if($('.error').size()!=0){
        return false;
    }else{
        $("#submit_btn").attr({
            "disabled":"disabled"
        });
        return true;
    }
}

function ValidateSubscribeForm(formData, jqForm, options){
    var form = jqForm[0];
    $('.error').remove();
    if (!form.first_name.value) {
        $(form.first_name).parent().append('<br /><span class="error">First name field is required</span>');
    }
    if (!form.email.value || !/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test($(form.email).val()) ){
        $(form.email).parent().append('<br /><span class="error">The format of the e-mail address is incorrect.</span>');
    }
    
    var checkeds = $("input[name='category_id[]']:checked").length;
    if (checkeds == 0) {
        $("#select_category").append('<br /><span class="error">You need to choose at least one category.</span>');
    }
    if (!form.validate_code.value) {
        $(form.validate_code).parent().append('<br /><span class="error">Validate Code field is required</span>');
    }
    if($('.error').size()!=0){
        return false;
    }else{
        $("#submit_btn").attr({
            "disabled":"disabled"
        });
        return true;
    }

}
function ValidateUnsubscribeForm(formData, jqForm, options){
    var form = jqForm[0];
    $('.error').remove();
    if (!form.email.value || !/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test($(form.email).val()) ){
        $(form.email).parent().append('<span class="error">The format of the e-mail address is incorrect.</span>');
    }
    if (!form.validate_code.value) {
        $(form.validate_code).parent().append('<br><span class="error">Validate Code field is required</span>');
    }
    var str='';
    $("input[type=checkbox]").each(function(){
        if ($(this).attr("checked"))
            str+= $(this).val()+',';
    })
    if (!str) {
        $(form.category_id).parent().append('<span class="error">Please select a category name.</span>');
    }
    if($('.error').size()!=0){
        return false;
    }else{
        $("#submit_btn").attr({
            "disabled":"disabled"
        });
        return true;
    }
}
function ValidateChangeEmailForm(formData, jqForm, options){
    var form = jqForm[0];
    $('.error').remove();
    if (!form.email.value || !/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test($(form.email).val()) ){
        $(form.email).parent().append('<span class="error">The format of the e-mail address is incorrect.</span>');
    }
    if (!form.new_email.value || !/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test($(form.new_email).val()) ){
        $(form.new_email).parent().append('<span class="error">The format of the e-mail address is incorrect.</span>');
    }
    if (!form.rnew_email.value || !/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test($(form.rnew_email).val()) ){
        $(form.rnew_email).parent().append('<span class="error">The format of the e-mail address is incorrect.</span>');
    }
    if (!form.validate_code.value) {
        $(form.validate_code).parent().append('<span class="error">Validate Code field is required.</span>');
    }
    if($('.error').size()!=0){
        return false;
    }else{
        $("#submit_btn").attr({
            "disabled":"disabled"
        });
        return true;
    }
}

function ValidateRetrievePasswordForm(formData, jqForm, options){
    var form = jqForm[0];
    $('.error').remove();
    if (!form.username.value) {
        $(form.username).parent().append('<span class="error">Username field is required.</span>');
    }
    if (!form.validate_code.value) {
        $(form.validate_code).parent().append('<span class="error">Validate Code field is required.</span>');
    }
    if($('.error').size()!=0){
        return false;
    }else{
        $("#submit_btn").attr({
            "disabled":"disabled"
        });
        return true;
    }
}
    
function  ValidateChangePasswordForm(formData, jqForm, options){
    var form = jqForm[0];
    $('.error').remove();
    if (!form.key.value) {
        $(form.key).parent().append('<span class="error">Validate Key field is required</span>');
    }
    if (!form.password.value) {
        $(form.password).parent().append('<span class="error">Password field is required</span>');
    }
    if (!form.repeat_password.value) {
        $(form.repeat_password).parent().append('<span class="error">Pepeat password field is required</span>');
    }
    if (!form.validate_code.value) {
        $(form.validate_code).parent().append('<span class="error">Validate Code field is required</span>');
    }
    if($('.error').size()!=0){
        return false;
    }else{
        $("#submit_btn").attr({
            "disabled":"disabled"
        });
        return true;
    }
}


function  ValidateforwardForm(formData, jqForm, options){
    var form = jqForm[0];
    $('.error').remove();
    if (!form.name.value) {
        $(form.name).parent().append('<span class="error">Name field is required</span>');
    }
    if (!form.email.value) {
        $(form.email).parent().append('<span class="error">Email field is required</span>');
    }
    
    if($('.error').size()!=0){
        return false;
    }else{
        $("#submit_btn").attr({
            "disabled":"disabled"
        });
        return true;
    }
}

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
        $(tipobj).slideUp('slow');
    }, ( 5000 ) );

    return false;
}
