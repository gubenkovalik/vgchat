@extends('chat.lay')

@section('content')


    <div class="jumbotron wow fadeIn" data-wow-duration="0.5s">
      <h1>Чат Джен Кота</h1>

      <p>{{trans('login.register_and_chat')}}! </p>

      <p>
        <a href="/extra/android/VG_Chat_1.4.apk" class="btn btn-success btn-success btn-raised"><i class="material-icons">file_download</i> <b style="text-transform:none">VG_Chat_1.4.apk</b></a>
        <a href="/register" class="btn btn-inverse btn-lg">{{trans('login.register')}}</a>
        
    </p>
    </div>

    <div class="">
        <h2>{{trans('login.login')}}</h2>

        <br>
        <div class="col-md-4 well well-lg">
            @if(session()->has('error'))
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{session()->get('error')}}

                </div>
            @endif
            @if(session()->has('success'))
                    <div class="alert alert-dismissible alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{session()->get('success')}}

                    </div>@endif
            <form action="" id="loginForm" method="POST" data-parsley-validate>
                <div class="form-group">
                    <input type="email" data-parsley-trigger="change" required="required" name="email" class="form-control" id="email" placeholder="E-mail">
                </div>
                <div class="form-group">
                    <input type="password" required="required" name="password" class="form-control" id="nickname" placeholder="{{trans('login.password')}}">
                </div>
                <div class="form-group">
                    <a class="btn" href="/remind">{{trans('login.remind')}}</a>
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <button type="submit" class="msg-right btn btn-inverse">{{trans('login.login')}}</button>
            </form>

        </div>
    </div>


<script>
    $(function () {

        $('#loginForm').parsley();
    });
</script>

@stop
