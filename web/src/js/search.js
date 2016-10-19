/**
 * Created by ASUS on 2016/10/19.
 */
$(document).ready(function () {
    $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active');
        var glyphiconObj = $(this).children(".glyphicon");
        if(glyphiconObj.hasClass("glyphicon-chevron-left")){
            glyphiconObj.removeClass("glyphicon-chevron-left").addClass("glyphicon-chevron-right");
        }else{
            glyphiconObj.removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-left");
        }
    });

    $("#sidebar-nav .list-group-item").click(function () {
        var data_href= $(this).attr("data-href");
        if(data_href != "") {
            $("#sidebar-nav .list-group-item").removeClass("active");
            $(this).addClass("active");
            $("#nav-iframe").attr("src", data_href);
        }
    });
});