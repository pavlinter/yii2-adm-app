$(function(){

});


var Notification = function() {
    var loadMessagesTimer;
    var xhr;
    var lastId = 0;
    var firstId = 0;
    var $content = $(".notification-menu .notifications-start");
    var secondsUpdate = 10000;
    var $prevLinkContent  = $(".notifications-more");
    var $prevLink  = $(".action-prev-notification");
    var $count = $(".notifications-count");
    var $emptyMessage = $(".notifications-empty");


    var loadMessages = function(mode){
        if(xhr && xhr.readyState != 4){
            xhr.abort();
        }
        clearTimeout(loadMessagesTimer);
        if(mode == 'prev'){
            $prevLink.hide();
        }

        var data = {mode: mode, lastId: lastId, firstId: firstId};
        var csrfParam = yii.getCsrfParam();
        var csrfToken = yii.getCsrfToken();
        data[csrfParam] = csrfToken;

        xhr = $.ajax({
            url: global.url.notification,
            type: "POST",
            dataType: "json",
            data: data,
        }).done(function (d) {
            for (var i in d.list) {
                var data = d.list[i];
                var tmpl = doT.template($("#tmpl_" + data.template).text());
                var $row = $(tmpl(data));
                $("#" + data.unique).remove(); //удалить похожие
                if (d.mode == 'prev'){
                    $prevLinkContent.before($row);
                } else {
                    $content.after($row);
                }
            }

            if(d.mode == 'prev') {
                firstId = d.firstId;
                if (d.prevLinkHidden) {
                    $prevLink.hide();
                } else {
                    $prevLink.show();
                }
            } else if(d.mode == 'next') {
                lastId = d.lastId;
            } else {
                lastId = d.lastId;
                firstId = d.firstId;
                if (d.prevLinkHidden) {
                    $prevLink.hide();
                } else {
                    $prevLink.show();
                }
                if(!d.list.length){
                    $emptyMessage.show();
                }
            }
            if(d.count > 0){
                $count.text(d.count).show();
            } else {
                $count.text('').hide();
            }

            loadMessagesTimer = setTimeout(function(){
                loadMessages('next');
            }, secondsUpdate);
        });
    };
    loadMessages('start');

    $prevLink.on("click", function(){
        loadMessages('prev');
        return false;
    });

    $("body").on("click", ".action-viewed", function(){
        var $el = $(this);
        var $i = $el.find("i");
        var $li = $el.closest("li");
        var id = $el.attr("data-id");

        var data = {id: id};
        var csrfParam = yii.getCsrfParam();
        var csrfToken = yii.getCsrfToken();
        data[csrfParam] = csrfToken;

        $el.hide();
        $.ajax({
            url: global.url.notificationViewed,
            type: "POST",
            dataType: "json",
            data: data,
        }).done(function (d) {
            if(d.r){
                $i.removeClass("fa-circle-o fa-circle");
                if(d.viewed){
                    $i.addClass("fa-circle-o");
                    $li.removeClass("active");
                } else {
                    $i.addClass("fa-circle");
                    $li.addClass("active");
                }
            }
        }).always(function (jqXHR, textStatus) {
            $el.show();
        });

        return false;
    });

    return {};
    /*return {

     publicProperty: '',

     publicMethod: function(args) {

     },

     privilegedMethod: function(args) {
     return privateMethod(args);
     }
     };*/
};

var Common = function() {
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