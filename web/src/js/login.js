/**
 * Created by ASUS on 2016/10/20.
 */
jQuery(function () {
    jQuery("#formSignin").validate({
        rules: {
            username: "required",
            password: "required"
        },
        messages: {
            firstname: "Please enter your username",
            lastname: "Please enter your password"
        },
        submitHandler: function(form) {
            jQuery(form).ajaxSubmit({
                type: "post",
                dataType: "json",
                beforeSubmit:function (formData, jqForm, options) {
                    $("button[type='submit']").attr("disabled", "disabled");
                },
                success: function (data) {
                    console.log(data);
                    if(data.flag){
                        window.location.href = $("#formSignin").attr("data-url");
                    }else{
                        alert(data.msg);
                        $("button[type='submit']").removeAttr("disabled");
                    }
                }
            });
        }
    });
});
