@extends('chat.lay')

@section('title') {{Lang::get('register.register')}} - @endsection
@section('content')
    <h2>{{Lang::get('register.register')}}</h2>
    @if(Session::has('error')) <span class="text-danger">{{Session::get('error')}}</span>@endif
    <br/>
    <div class="col-md-6 well well-lg">

        <form action="" method="POST">
            <div class="form-group">
                <input required type="text" name="nickname" class="form-control" id="nickname" placeholder="{{Lang::get('register.nickname')}}">
            </div>
            <div class="form-group">
                <input required type="email" name="email" class="form-control" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input required type="password" name="password" class="form-control" id="password" placeholder="{{Lang::get('register.password')}}">
            </div>
            <div class="form-group">
                {!! app('captcha')->display() !!}
            </div>
            
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <button type="submit" class="btn btn-inverse btn-raised">{{Lang::get('register.create_account')}}</button>
        </form>
    </div>

@endsection
