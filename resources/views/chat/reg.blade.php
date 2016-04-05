@extends('chat.lay')

@section('title') {{Lang::get('register.register')}} - @stop
@section('content')
    <h2>{{Lang::get('register.register')}}</h2>

    <br/>
    <div class="col-md-6 well well-lg">
        @if(Session::has('error'))
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{Session::get('error')}}

            </div>
        @endif
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

@stop
