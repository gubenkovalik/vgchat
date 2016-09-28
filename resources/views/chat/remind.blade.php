@extends('chat.lay')
@section('title') {{trans('resetting.remind')}} - @stop
@section('content')
    <h2>{{trans('resetting.remind')}}</h2>

    <br/>

    @if(session()->has('email'))
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>{{trans('resetting.link_sent')}} <strong>{{session()->get('email')}}</strong>
        </div>
    @else
    <div class="col-md-6 well well-lg">
        @if(session()->has('error'))
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{session()->get('error')}}

            </div>
        @endif
        <form action="" method="POST">
            <div class="form-group">
                <input type="email" name="email" class="form-control" id="email" placeholder="E-mail">
            </div>

            <button type="submit" class="btn btn-primary btn-raised">{{trans('remind')}}</button>
        </form>
    </div>
    @endif
@stop
