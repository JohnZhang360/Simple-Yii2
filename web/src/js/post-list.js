/**
 * Created by ASUS on 2016/10/24.
 */
jQuery(function () {
    $.each($(".blog-content"), function (index, obj) {
        var objId = $(obj).attr("id");
        var content = $(obj).prev().html();
        if(objId && content) {
            editormd.markdownToHTML(objId, {
                markdown: content,//+ "\r\n" + $("#append-test").text(),
                htmlDecode: "style,script,iframe",  // you can filter tags decode
                tocm: true,    // Using [TOCM]
            });
        }
    });
    $(".editormd-html-preview").css("padding", "0");
});