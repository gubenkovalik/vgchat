@extends('chat.lay')

@section('content')
    @if(Session::has('error')) <span class="text-danger">{{Session::get('error')}}</span>@endif
    <div class="col-md-12">
        <form ng-submit="submitComment()">
            <div class="form-group label-floating">
                <div class="input-group">
                    <label class="control-label" for="message">{{Lang::get('chat.message')}}..</label>
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
                <p class="text-center" style="font-size:9pt; color: #888;" id="indicator"></p>
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
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.10/css/perfect-scrollbar.min.css"/>
    

    <script data-no-instant>

        var _nickname = "{{$user->nickname}}";
        var _user_id = "{{$user->id}}";
        var _avatar = "{{$user->avatar}}";
        var _typingLang = "{{Lang::get('chat.typing')}}";
        var _sessid = "{{Session::get('sessid')}}";

        var currentPermission;
        Notification.requestPermission(perm);

        $(document).ready(function() {
            
            $('#chatContainer').perfectScrollbar();

        });

        function perm(permission) {
            if( permission != "granted" ) return false;
        }

        socket.on('chat bell', function(data){
            

                ion.sound.play("notification");
            
        });
        socket.on('activity', function(data){
            console.log(data);

            var filter = {
                '-webkit-filter': 'blur('+(data.active == true ? '0' : '2') +'px)',
                '-moz-filter': 'blur('+(data.active == true ? '0' : '2') +'px)',
                'filter': 'blur('+(data.active == true ? '0' : '2') +'px)',
            };
            console.log($('.dataUserOnline_'+data.id).parent().css(filter));

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


    <script data-no-instant src="/assets/js/controllers/chatCtrl.js"></script>
    <script data-no-instant src="/assets/js/services/chatService.js"></script>
    <script data-no-instant src="/assets/js/ion.sound.min.js"></script>
    <script data-no-instant src="/assets/js/app.js"></script>


    <script>


    </script>
@endsection
@section('head_scriptss')

 
@endsection
