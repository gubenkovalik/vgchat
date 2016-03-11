@extends('chat.lay')

@section('content')


    <div class="jumbotron wow bounceInDown" data-wow-duration="1.3s" data-wow-delay="1.5s">
      <h1>VG Chat</h1>

      <p>{{Lang::get('login.register_and_chat')}}! </p>

      <p><a href="/register" class="btn btn-inverse btn-lg">{{Lang::get('login.register')}}</a> <a href="javascript:void(0);" class="btn btn-lg" onclick="hideJumb()">{{Lang::get('login.close')}}</a></p>
    </div>

    <div class="">
        <h2>{{Lang::get('login.login')}}</h2>
        @if(Session::has('error')) <span class="text-danger">{{Session::get('error')}}</span>@endif
        @if(Session::has('success')) <span class="text-success">{{Session::get('success')}}</span>@endif
        <br>
        <div class="col-md-4 well well-lg">
            <div class="bs-callout bs-callout-warning hidden">
                <h4>Oh snap!</h4>
                <p>{{trans('register.all_fields')}}</p>
            </div>
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
