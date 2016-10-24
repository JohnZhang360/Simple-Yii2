/**
 * Created by ASUS on 2016/10/24.
 */
jQuery(function () {
    $.validator.addMethod("filetype", function (value, element, param) {
        var fileType = value.substring(value.lastIndexOf(".") + 1).toLowerCase();
        return this.optional(element) || $.inArray(fileType, param) != -1;
    }, $.validator.format("invalid file type"));

    $("#formQiniu").validate({
        errorLabelContainer: $("div.error-container"),
        rules: {
            "pic": {
                required: true,
                filetype: ["jpg", "jpeg", "png", "ico", "gif"]
            }
        },
        messages: {
            "pic": {
                required: "Please upload image",
                filetype: "Please upload image"
            }
        },
        submitHandler: function (form) {
            jQuery(form).ajaxSubmit({
                type: "post",
                dataType: "json",
                beforeSubmit: function (formData, jqForm, options) {
                    $("button[type='submit']").attr("disabled", "disabled");
                },
                success: function (data) {
                    if (data.flag) {
                        window.location.href = $("#formQiniu").attr("data-url");
                    } else {
                        alert(data.msg);
                        $("button[type='submit']").removeAttr("disabled");
                    }
                }
            });
        }
    });
});