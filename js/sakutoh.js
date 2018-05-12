$(function(){

    //クリックイベント
    $('button').click(function() {
        $('.pulldown').toggle(500);
    })

    //ページトップへ移動
    
    $('.pagetop a').click(function () {
            $("html,body").animate({scrollTop:0},"300");
    });

    
});