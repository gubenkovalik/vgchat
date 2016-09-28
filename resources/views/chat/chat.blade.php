@extends('chat.lay')

@section('content')
    @if(session()->has('error')) <span class="text-danger">{{session()->get('error')}}</span>@endif
    <div ng-app="chatApp" class="col-md-6" style="margin-top:30px;margin: 0 auto;" ng-controller="chatCtrl">
        <form ng-submit="submitComment()">
            <div class="form-group label-floating">
                <div class="input-group">
                    <label class="control-label" for="message">{{trans('chat.message')}}..</label>
                    <input autocomplete="off" ng-model="chatData.message" name="message" type="text" id="message" class="form-control">
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-inverse btn-fab btn-fab-mini">
                          <i class="material-icons">send</i>
                      </button>
                        &nbsp;

                    </span>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-warning btn-fab btn-fab-mini" title="Ring the bell" onclick="bell()">
                        <i class="material-icons">settings_remote</i>
                        </button>
                    </span>
                </div>
            </div>
        </form>

        <div class="panel panel-default chat-container" id="chatContainer">

            <div class="panel-body">
                <p class="text-center" ng-show="loading"><img src="/assets/images/preloader.gif"/></p>
                <p class="" style="background: url('/extra/anim/typing.gif') 0 2px no-repeat; margin: 0 144px 0px 30px; padding: 1px 0 0 20px;font-size:9pt; color: #888;opacity:0;" id="indicator">Text</p>
                <div class="list-group" id="messagesList">
                    <div class="msg wow slideInRight" data-wow-duration="0.5s" style="display:none" ng-hide="loading" ng-repeat="message in messages">

                        <div ng-hide="loading" class="list-group-item ">
                            <div class="row-picture">

                                <span style="display: none" class="dataUserOnline_@{{ message.user_id }}">
                                    <span style="color:green;vertical-align: middle; display:inline;">• </span>
                                </span>
                                <img style="display:inline" height="80px" class="circle avatarImage" data-src="@{{ message.avatar }}" alt="@{{ message.nickname }}">
                            </div>
                            <div class="row-content">
                                <h4 class="list-group-item-heading">@{{ message.nickname }}</h4>

                                <p class="list-group-item-text" ng-bind-html="message.message | to_trusted">@{{ message.message }}</p>
                                <span style="float:right;font-size:8pt;color:#888" class="msg-right">@{{ message.date }}</span>
                            </div>
                        </div>

                        <div class="list-group-separator"></div>
                    </div>
                    <a ng-hide="loading" style="display: none" href="javascript:void(0);" class="btn b-load-more" onclick="loadMore()">Load more &hellip;</a>
                    <p class="text-center" style="display: none" id="loadingMoreDig"><img src="/assets/images/preloader.gif"/></p>
                </div>

            </div>
        </div>

    </div>

    <script data-no-instant>

        var _nickname = "{{$user->nickname}}";
        var _user_id = "{{$user->id}}";
        var _avatar = "{{$user->avatar}}";
        var _typingLang = "{{trans('chat.typing')}}";
        var _sessid = "{{session()->get('sessid')}}";

        var currentPermission;
        Notification.requestPermission(perm);


        function perm(permission) {
            if( permission != "granted" ) return false;
        }
        $('#chatContainer').perfectScrollbar();
        socket.on('chat bell', function(data){


            ion.sound.play("notification");

        });
        socket.on('activity', function(data){
            var filter = {
                '-webkit-filter': 'blur('+(data.active == true ? '0' : '2') +'px)',
                '-moz-filter': 'blur('+(data.active == true ? '0' : '2') +'px)',
                'filter': 'blur('+(data.active == true ? '0' : '2') +'px)',
            };
            $('.dataUserOnline_'+data.id).parent().css(filter);

        });
        socket.on('users online', function(data){
            $.each(data, function(index, value) {

                $('.dataUserOnline_'+index).css({display: (value == true ? 'inline' : 'none')})

            });

        });



        function bell(){
            socket.emit('chat bell', {sessid: _sessid, nickname: _nickname});
        }

        var vis = (function(){
            var stateKey, eventKey, keys = {
                hidden: "visibilitychange",
                webkitHidden: "webkitvisibilitychange",
                mozHidden: "mozvisibilitychange",
                msHidden: "msvisibilitychange"
            };
            for (stateKey in keys) {
                if (stateKey in document) {
                    eventKey = keys[stateKey];
                    break;
                }
            }
            return function(c) {
                if (c) document.addEventListener(eventKey, c);
                return !document[stateKey];
            }
        })();

        vis(function(){
            console.log(vis());
            socket.emit('activity', {sessid: _sessid, id: _user_id, active: vis()});
        });
    </script>

    <script data-no-instant src="/assets/js/ion.sound.min.js"></script>

    <script>
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
        function afterLoad() {
            $('.msg').show(0);
            $("#loadingMoreDig").hide(0);
            $('.b-load-more').show(1000);
            var $avs = $('.avatarImage');
            if ($avs.length == 0) {
                setTimeout(afterLoad, 200);
                return;
            }
            $('.avatarImage').each(function (k, v) {
                console.log("doing");
                $(this).attr('src', $(this).attr('data-src'));

            });
        }
        var loaderTask = function ($scope, Chat) {
            Chat.get()
                    .success(function (data) {
                        $scope.messages = data;
                        $scope.loading = false;
                        cached = data;
                        setTimeout(function () {

                            $('#chatContainer').perfectScrollbar('update');
                            afterLoad();
                        }, 1000);

                        if(GLOBAL_CHAT_LOADED == false) {
                            socket.on('chat message', function (msg) {

                                if (msg.nickname != _nickname) {
                                    ion.sound.play("notification");
                                    try {
                                        var mailNotification = new Notification(msg.nickname, {
                                            tag: Math.random().toString(),
                                            body: msg.message,
                                            icon: msg.avatar
                                        });

                                    } catch (e) {

                                    }
                                }
                                console.log(msg);

                                var newMessage = {
                                    message: msg.message,
                                    user_id: msg.user_id,
                                    date: msg.date,
                                    avatar: msg.avatar,
                                    position: "left",
                                    nickname: msg.nickname
                                };
                                cached = prepend(newMessage, cached);

                                $("#messagesList").prepend(tpl(newMessage));
                                $('#chatContainer').perfectScrollbar('update');
                            });
                            socket.on('chat typing', function (nicknames) {

                                var al = $("#indicator");

                                if (osize(nicknames) == 0) {
                                    requestAnimationFrame(function(){

                                        al.animate({opacity: 0}, 500);
                                    });

                                    return;
                                }
                                var keys = [];
                                for (var k in nicknames) keys.push(k);
                                al.html(keys.join(", ") + " " + _typingLang);
                                al.animate({opacity: 1}, 500);

                                clearTimeout(tmt);
                                tmt = setTimeout(function () {
                                    socket.emit('chat notyping', {nickname: _nickname, sessid: _sessid});
                                }, 3000);
                            });
                            $("#message").keyup(function (e) {

                                var keynum;

                                if (window.event) { // IE
                                    keynum = e.keyCode;
                                } else if (e.which) { // Netscape/Firefox/Opera
                                    keynum = e.which;
                                }
                                if (isCharacterKeyPress(e) || keynum != 13) {

                                    socket.emit('chat typing', {nickname: _nickname, sessid: _sessid});
                                } else if (keynum == 13) {
                                    socket.emit('chat notyping', {nickname: _nickname, sessid: _sessid});
                                }
                            });
                            GLOBAL_CHAT_LOADED = true;
                        }
                    });
        };
        var loadMore;
        (function () {

        })();
        angular.module('chatCtrl', [])
                .controller('chatCtrl', function ($scope, $http, Chat) {

                    $scope.chatData = {};
                    $scope.loading = true;

                    setTimeout(function () {

                        loaderTask($scope, Chat);
                    }, 80);


                    loadMore = function () {
                        $("#loadingMoreDig").show(0);
                        Chat.get(cached.length)
                                .success(function (data) {
                                    cached = $.merge(cached, data);
                                    $scope = cached;


                                    setTimeout(function () {
                                        afterLoad();
                                    }, 1500);
                                });
                    };

                    $scope.submitComment = function () {
                        var inp = $("#message");
                        if (inp.val().trim().length == 0) {
                            return;
                        }

                        var now = new Date();
                        now = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
                        socket.emit('chat message', {
                            user_id: _user_id,
                            nickname: _nickname,
                            avatar: _avatar,
                            message: inp.val().trim(),
                            date: now,
                            sessid: _sessid
                        });
                        socket.emit('chat notyping', {sessid: _sessid, nickname: _nickname});
                        inp.val("");

                        Chat.save($scope.chatData)
                                .success(function (data) {

                                })
                                .error(function (data) {

                                });
                    };
                });

        angular.module('chatService', [])
                .factory('Chat', function ($http) {
                    $http.defaults.withCredentials = true;
                    return {
                        get: function (skip) {
                            return $http.get('/api/get?skip=' + skip);
                        },

                        save: function (chatData) {


                            return $http({
                                method: 'POST',
                                url: '/api/send',
                                withCredentials: true,
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                data: $.param(chatData)
                            });
                        }
                    }

                });

        if (typeof chatApp != "undefined") {
            delete chatApp;
        }

        var chatApp = angular.module('chatApp', ['chatCtrl', 'chatService', 'ngPJAX'])
                .filter('to_trusted', ['$sce', function ($sce) {
                    return function (text) {
                        return $sce.trustAsHtml(text);
                    };
                }]);


        $('*').keypress(function () {
            $("#message").focus();
        });

        ion.sound({
            sounds: [
                {name: "notification"}
            ],

            // main config
            path: "/assets/sounds/",
            preload: true,
            multiplay: true,
            volume: 0.9
        });
        rebootstrap();

    </script>
@stop
