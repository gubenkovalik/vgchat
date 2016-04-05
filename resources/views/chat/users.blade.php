@extends('chat.lay')

@section('content')
    <div class="well" style="margin-top:30px;width:auto;max-width: 400px;margin: 0 auto;">
    <div class="list-group" >
        <h3 id="status"></h3>
    @foreach($users as $user)

            <div class="list-group-item">

                <div class="row-picture">
                     @if($user->online == true)
                    <span>
                        <span style="color:#409a4c;vertical-align: middle; display:inline;">• </span>
                    </span>
                    @endif
                    <img style="display:inline" height="80px" class="circle" src="{{$user->avatar}}" alt="{{$user->nickname}}">
                </div>
                <div class="row-content">
                    <h4 @if($user->online == true)style="float: left;position: relative; top: 46px;"@endif class="list-group-item-heading msg-left">{{$user->nickname}}</h4>

                    @if($user->online == true)<span style="float: right;position: relative;top:35px">
                        <a onclick="$('#notifyModal').modal('show');$('#uid').val('{{$user->id}}');return;" data-toggle="tooltip" data-placement="bottom" title="{{Lang::get('users.notify')}}" data-original-title="{{Lang::get('users.notify')}}" href="javascript:void(0)" class="btn btn-warning btn-fab btn-fab-mini"><i class="material-icons">settings_input_antenna</i></a>
                    </span>
                        @else
                        <p class="list-group-item-text" style="font-size: 8pt; color:#777">{{Lang::get('users.last_seen')}} {{\App\Http\Helper::getLastSeen($user->last_seen)}}</p>
                    @endif
                </div>

            </div>

            <div class="list-group-separator"></div>
    @endforeach
    </div>
    </div>
    <div class="modal fade" id="notifyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">{{Lang::get('users.notify')}}</h4>
                </div>
                <div class="modal-body">
                    <p>{{Lang::get('users.ntext')}}</p>
                    <form action="" id="notifyForm" onsubmit="event.preventDefault();notifyUser();">
                        <div class="form-group label-floating">
                            <label class="control-label" for="text">{{Lang::get('users.text')}}</label>
                            <input autocomplete="off" aria-autocomplete="off" class="form-control" id="text" type="text">
                        </div>
                        <input type="hidden" id="uid">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="notifyUser()" class="btn btn-primary">{{Lang::get('users.btn')}}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{Lang::get('users.cancel')}}</button>
                </div>
            </div>
        </div>
    </div>


    <script data-no-instant>
        var _sessid = "{{Session::get('sessid')}}";

        function notifyUser(){
            var uid = $('#uid').val();
            var msg = $('#text').val();
            if(uid.length < 1){
                return;
            }

            $('#notifyModal').modal('hide');
            socket.emit("user notify", {uid: uid, sessid: _sessid, msg: msg,nickname: '{{App\Http\User::find(Session::get('uid'))->nickname}}', avatar: '{{App\Http\User::find(Session::get('uid'))->avatar}}'});
        }


    </script>
@stop
