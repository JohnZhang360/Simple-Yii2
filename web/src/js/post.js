/**
 * Created by ASUS on 2016/10/21.
 */
$(function () {
    editormd({
        id: "editor-md",
        height: 640,
        path: $("#editor-md").attr("data-lib")
    });

    $("#formPost").validate({
        errorLabelContainer: $("div.error-container"),
        ignore: "",
        errorElement: "p",
        rules: {
            "title": {
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
                    console.log(data);
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