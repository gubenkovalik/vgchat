@extends('chat.lay')

@section('content')


    <div class="jumbotron wow bounceInDown" data-wow-duration="1.3s" data-wow-delay="1.5s">
      <h1>VG Chat</h1>

      <p>{{Lang::get('login.register_and_chat')}}! </p>

      <p>
        <a href="/extra/android/VG_Chat_latest.apk" class="btn btn-success btn-success btn-raised"><i class="material-icons">file_download</i> <b style="text-transform:none">VG_Chat_v_1.0.apk</b></a>
        <a href="/register" class="btn btn-inverse btn-lg">{{Lang::get('login.register')}}</a>
        
    </p>
    </div>

    <div class="">
        <h2>{{Lang::get('login.login')}}</h2>

        <br>
        <div class="col-md-4 well well-lg">
            @if(Session::has('error'))
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{Session::get('error')}}

                </div>
            @endif
            @if(Session::has('success'))
                    <div class="alert alert-dismissible alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{Session::get('success')}}

                    </div>@endif
            <form action="" id="loginForm" method="POST" data-parsley-validate>
                <div class="form-group">
                    <input type="email" data-parsley-trigger="change" required="required" name="email" class="form-control" id="email" placeholder="E-mail">
                </div>
                <div class="form-group">
                    <input type="password" required="required" name="password" class="form-control" id="nickname" placeholder="{{Lang::get('login.password')}}">
                </div>
                <div class="form-group">
                    <a class="btn" href="/remind">{{Lang::get('login.remind')}}</a>
                </div>
                <div class="form-group">
                    {!! app('captcha')->display() !!}
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <button type="submit" class="msg-right btn btn-inverse">{{Lang::get('login.login')}}</button>
            </form>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jkit/1.1.8/jquery.jkit.min.js"></script>

<script>
    function hideJumb(){
        $('.jumbotron').slideUp(400);
        $.ajax({
            url: '/jumb',
            type: 'POST'
        });

    }

    if(document.cookie.indexOf("jumb") != -1){
        $('.jumbotron').hide(0);
    }
    $(function () {
        $('body').jKit('parallax', { 'strength': '3', 'axis': 'both' });
        $('#loginForm').parsley();
    });
</script>

@endsection
