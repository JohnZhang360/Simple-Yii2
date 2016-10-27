/**
 * Created by ASUS on 2016/10/24.
 */
jQuery(function () {
    var content = $("#blogContent").prev().html();
    if(content) {
        editormd.markdownToHTML("blogContent", {
            markdown: content,//+ "\r\n" + $("#append-test").text(),
            htmlDecode: "style,script,iframe",  // you can filter tags decode
            tocm: true,    // Using [TOCM]
        });
        $(".editormd-html-preview").css("padding", "0");
    }
});