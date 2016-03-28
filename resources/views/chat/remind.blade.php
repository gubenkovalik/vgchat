@extends('chat.lay')
@section('title') {{Lang::get('resetting.remind')}} - @endsection
@section('content')
    <h2>{{Lang::get('resetting.remind')}}</h2>

    <br/>

    @if(Session::has('email'))
        <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>{{Lang::get('resetting.link_sent')}} <strong>{{Session::get('email')}}</strong>
        </div>
    @else
    <div class="col-md-6 well well-lg">
        @if(Session::has('error'))
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{Session::get('error')}}

            </div>
        @endif
        <form action="" method="POST">
            <div class="form-group">
                <input type="email" name="email" class="form-control" id="email" placeholder="E-mail">
            </div>

            <button type="submit" class="btn btn-primary btn-raised">{{Lang::get('remind')}}</button>
        </form>
    </div>
    @endif
@endsection
