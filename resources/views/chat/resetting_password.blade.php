@extends('chat.lay')

@section('content')
    <h1>{{Lang::get('resetting.setting_passwor')}}</h1>
    @if(Session::has('error')) <span class="text-danger">{{Session::get('error')}}</span>@endif
    <br/>


    <div class="col-md-6">

        <form action="" method="POST">
            <div class="form-group">
                <input type="password" name="password" class="form-control" id="password" placeholder="{{Lang::get('resetting.enter_new')}}">
            </div>

            <button type="submit" class="btn btn-primary btn-raised">{{Lang::get('resetting.set')}}</button>
        </form>
    </div>

@endsection
@section('head_scriptss')

 <script src="//fast.eager.io/sS_AOD1HyY.js"></script>
@endsection