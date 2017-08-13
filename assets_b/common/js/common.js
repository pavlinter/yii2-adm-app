$(function(){
    common();
});

var common = function() {
    var loadCommonTimer;
    var xhr;
    var $count = $(".now-online b");
    var secondsUpdate = 10000;


    var loadCommon = function(){
        if(xhr && xhr.readyState != 4){
            xhr.abort();
        }
        clearTimeout(loadCommonTimer);

        var data = {};
        var csrfParam = yii.getCsrfParam();
        var csrfToken = yii.getCsrfToken();
        data[csrfParam] = csrfToken;

        xhr = $.ajax({
            url: global.url.common,
            type: "POST",
            dataType: "json",
            data: data,
        }).done(function (d) {
            $count.text(d.userOnline);
            loadCommonTimer = setTimeout(function(){
                loadCommon();
            }, secondsUpdate);
        });
    };
    loadCommon();
    return {};
};