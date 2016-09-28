@extends('chat.lay')

@section('content')
    <h1>{{trans('resetting.setting_passwor')}}</h1>

    <br/>


    <div class="col-md-6">
        @if(session()->has('error'))
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{session()->get('error')}}

            </div>
        @endif
        <form action="" method="POST">
            <div class="form-group">
                <input type="password" name="password" class="form-control" id="password" placeholder="{{trans('resetting.enter_new')}}">
            </div>

            <button type="submit" class="btn btn-primary btn-raised">{{trans('resetting.set')}}</button>
        </form>
    </div>

@stop
