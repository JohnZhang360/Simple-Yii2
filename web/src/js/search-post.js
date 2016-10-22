/**
 * Created by root on 16-10-21.
 */
jQuery(function () {
    $.validator.addMethod("filetype", function (value, element, param) {
        var fileType = value.substring(value.lastIndexOf(".") + 1).toLowerCase();
        return this.optional(element) || $.inArray(fileType, param) != -1;
    }, $.validator.format("invalid file type"));

    $("#formSearch").validate({
        errorLabelContainer: $("div.error-container"),
        ignore: "",
        errorElement: "p",
        rules: {
            "title": {
                required: true
            },
            "sort": {
                required: true,
                digits: true
            }
        },
        messages: {
            "title": "Please enter title",
            "sort": {
                required: "Please enter sort",
                digits: "sort must enter digits"
            }
        },
        submitHandler: function (form) {
            jQuery(form).ajaxSubmit({
                type: "post",
                dataType: "json",
                success: function (data) {
                    if (data.flag) {
                        window.location.href = $("#formSearch").attr("data-url");
                    } else {
                        alert(data.msg);
                    }
                }
            });
        }
    });

    if ($("#isAdd").val() == 1) {
        $("input[name='pic']").rules("add", {
            required: true,
            filetype: ["jpg", "jpeg", "png", "ico", "gif"],
            messages: {
                required: "Please upload image",
                filetype: "Please upload image"
            }
        });
    }
});