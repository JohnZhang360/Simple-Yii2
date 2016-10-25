jQuery(function(){
    $(".list-archives").click(function () {
        var text = $(this).html();
        if(text) {
            text = text.replace("年", "-").replace("月", "");
            addUrlPara("archive", text, "/");
        }
    });

    $(".label-default").click(function () {
        var text = $(this).text();
        if(text) {
            addUrlPara("tags", text, "/");
        }
    });
});