var cached = {};
var loaded = false;

var tmt;

osize = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
function tpl(data){
    return '<div class="msg ng-scope wow lightSpeedIn"   data-wow-duration="0.5s"  style="background-color:rgba(255, 156, 13, 0.05);" ng-hide="loading" ng-repeat="message in messages">\
\
        <div ng-hide="loading" class="list-group-item " >\
        <div class="row-picture">\
           \
            <span style="display: inline" class="dataUserOnline_'+data.user_id+'">\
                <span style="font-size:14pt;color:green;vertical-align: middle; display:inline;">• </span>\
            </span>\
<img style="display: inline" height="80px" class="circle avatarImage" src="'+data.avatar+'" alt="'+data.nickname+'">\
        </div>\
        <div class="row-content">\
        <h4 class="list-group-item-heading">'+data.nickname+'</h4>\
\
    <p class="list-group-item-text" ng-bind-html="message.message | to_trusted">'+data.message+'</p>\
    <span style="float:right;font-size:8pt;color:#888" class="msg-right">'+data.date+'</span>\
    </div>\
    </div>\
\
<script>\
setTimeout(function(){\
    $(".msg").animate({backgroundColor: "white"}, 2200);\
}, 2000);\
</script>\
    <div class="list-group-separator"></div>\
        </div>';
}
function prepend(value, array) {
    var newArray = array.slice(0);
    newArray.unshift(value);
    return newArray;
}

function isCharacterKeyPress(evt) {
    if (typeof evt.which == "undefined") {
        // This is IE, which only fires keypress events for printable keys
        return true;
    } else if (typeof evt.which == "number" && evt.which > 0) {
        // In other browsers except old versions of WebKit, evt.which is
        // only greater than zero if the keypress is a printable key.
        // We need to filter out backspace and ctrl/alt/meta key combinations
        return !evt.ctrlKey && !evt.metaKey && !evt.altKey && evt.which != 8;
    }
    return false;
}
function afterLoad(){
    $('.msg').show(0);
    $("#loadingMoreDig").hide(0);
    $('.b-load-more').show(1000);
    var $avs = $('.avatarImage');
    if($avs.length == 0){
        setTimeout(afterLoad, 200);
        return;
    }
    $('.avatarImage').each(function(k, v){
        console.log("doing");
        $(this).attr('src', $(this).attr('data-src'));
        
    });
}
var loaderTask = function($scope, Chat){
    Chat.get()
        .success(function(data) {
            $scope.messages = data;
            $scope.loading = false;
            cached = data;
            setTimeout(function(){
                
                $('#chatContainer').perfectScrollbar('update');
                afterLoad();
            }, 1000);

            
            
            socket.on('chat message', function(msg){

                if(msg.nickname != _nickname){
                    ion.sound.play("notification");
                    try {
                        var mailNotification = new Notification(msg.nickname, {
                            tag: Math.random().toString(),
                            body: msg.message,
                            icon: msg.avatar
                        });

                    } catch(e){

                    }
                }
                console.log(msg);

                var newMessage = {
                    message: msg.message,
                    user_id: msg.user_id,
                    date: msg.date,
                    avatar: msg.avatar,
                    position:"left",
                    nickname: msg.nickname
                };
                cached = prepend(newMessage, cached);

                $("#messagesList").prepend(tpl(newMessage));
                $('#chatContainer').perfectScrollbar('update');
            });
            socket.on('chat typing', function(nicknames){

                var al = $("#indicator");
                if(osize(nicknames) == 0){
                    al.html("");
                    return;
                }
                var keys = [];
                for(var k in nicknames) keys.push(k);
                al.html(keys.join(", ")+" "+_typingLang);

                clearTimeout(tmt);
                tmt = setTimeout(function(){
                    socket.emit('chat notyping', {nickname: _nickname, sessid: _sessid});
                }, 3000);
            });
            $("#message").keyup(function(e){

                var keynum;

                if(window.event) { // IE
                    keynum = e.keyCode;
                } else if(e.which){ // Netscape/Firefox/Opera
                    keynum = e.which;
                }
                if(isCharacterKeyPress(e) || keynum != 13){
                    
                    socket.emit('chat typing', {nickname: _nickname, sessid: _sessid});
                } else if(keynum == 13){
                    socket.emit('chat notyping', {nickname: _nickname, sessid: _sessid});
                }
            });
        });
};
var loadMore;

angular.module('chatCtrl', [])
    .controller('chatCtrl', function($scope, $http, Chat) {
        $scope.chatData = {};
        $scope.loading = true;

        setTimeout(function(){
            console.log("loading");
            loaderTask($scope, Chat);
        }, 80);
        

        loadMore = function (){
            $("#loadingMoreDig").show(0);
             Chat.get(cached.length)
                .success(function(data) {
                    cached = $.merge(cached, data);
                    $scope = cached;

                    
                    setTimeout(function(){
                        afterLoad();
                    }, 1500);
            });
        }

        $scope.submitComment = function() {
            var inp = $("#message");
            if(inp.val().trim().length == 0) {
                return;
            }

            var now = new Date();
            now = now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();
            socket.emit('chat message', {user_id: _user_id, nickname: _nickname, avatar: _avatar, message: inp.val().trim(), date: now, sessid: _sessid});
            socket.emit('chat notyping', {sessid: _sessid, nickname: _nickname});
            inp.val("");

            Chat.save($scope.chatData)
                .success(function(data) {

                })
                .error(function(data) {
                    console.log(data);
                });
        };
    });