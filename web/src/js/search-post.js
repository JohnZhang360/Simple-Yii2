/**
 * Created by root on 16-10-21.
 */
jQuery(function () {
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
        $("#formSearch").rules("add", {
            "pic": {
                required:true
            },
            messages: {
                "pic": "请上传图片"
            }
        });
    }
});