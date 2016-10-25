/**
 * Created by ASUS on 2016/10/21.
 */
$(function () {
    $("#formTags").validate({
        errorLabelContainer: $("div.error-container"),
        ignore: "",
        errorElement: "p",
        rules: {
            "tag_name": {
                required: true
            }
        },
        messages: {
            "tag_name": "Please enter tag name",
        },
        submitHandler: function (form) {
            jQuery(form).ajaxSubmit({
                type: "post",
                dataType: "json",
                success: function (data) {
                    if (data.flag) {
                        window.location.href = $("#formTags").attr("data-url");
                    } else {
                        alert(data.msg);
                    }
                }
            });
        }
    });
});