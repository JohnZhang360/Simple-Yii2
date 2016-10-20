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
                success: function (data) {
                    debugger;
                }
            });
        }
    });
});
