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
            "link": {
                required: true
            },
            "sort": {
                required: true,
                digits: true
            }
        },
        messages: {
            "title": "Please enter title",
            "link": "Please enter link",
            "sort": {
                required: "Please enter sort",
                digits: "sort must enter digits"
            }
        },
        submitHandler: function (form) {
            jQuery(form).ajaxSubmit({
                type: "post",
                dataType: "json",
                beforeSubmit:function (formData, jqForm, options) {
                    $("button[type='submit']").attr("disabled", "disabled");
                },
                success: function (data) {
                    if (data.flag) {
                        window.location.href = $("#formSearch").attr("data-url");
                    } else {
                        alert(data.msg);
                        $("button[type='submit']").removeAttr("disabled");
                    }
                }
            });
        }
    });

    if ($("#isAdd").val() == 1) {
        $("input[name='pic']").rules("add", {
            required: true,
            messages: {
                required: "Please enter image key"
            }
        });
    }
});