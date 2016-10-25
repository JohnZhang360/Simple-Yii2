/**
 * Created by ASUS on 2016/10/21.
 */
$(function () {
    var mdLib = $("#formPost").attr("data-md-lib");
    editormd({
        id: "editor-md-summary",
        height: 320,
        path: mdLib
    });

    editormd({
        id: "editor-md",
        height: 640,
        path: mdLib
    });

    $("#formPost").validate({
        errorLabelContainer: $("div.error-container"),
        ignore: "",
        errorElement: "p",
        rules: {
            "title": {
                required: true
            },
            "summary": {
                required: true
            },
            "content": {
                required: true
            },
            "sort": {
                required: true,
                digits: true
            }
        },
        messages: {
            "title": "Please enter title",
            "summary": "Please enter summary",
            "content": "Please enter content",
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
                        window.location.href = $("#formPost").attr("data-url");
                    } else {
                        alert(data.msg);
                    }
                }
            });
        }
    });
});