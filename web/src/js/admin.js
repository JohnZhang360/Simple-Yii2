/**
 * Created by root on 16-10-20.
 */
$(function(){
    $("#logout-btn").click(function () {
        var post_url = $(this).attr("data-url");
        var turn_url = $(this).attr("data-turn");
        if(post_url && turn_url) {
            $.post(post_url, function (data) {
                if(data.flag) {
                    window.location.href = turn_url;
                }else{
                    alert("操作失败,请刷新后再试");
                }
            });
        }
    });
});