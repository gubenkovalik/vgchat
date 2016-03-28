@extends('chat.lay')

@section('content')
    <h1>{{Lang::get('resetting.setting_passwor')}}</h1>

    <br/>


    <div class="col-md-6">
        @if(Session::has('error'))
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{Session::get('error')}}

            </div>
        @endif
        <form action="" method="POST">
            <div class="form-group">
                <input type="password" name="password" class="form-control" id="password" placeholder="{{Lang::get('resetting.enter_new')}}">
            </div>

            <button type="submit" class="btn btn-primary btn-raised">{{Lang::get('resetting.set')}}</button>
        </form>
    </div>

@endsection
