@extends('chat.lay')
@section('title') {{Lang::get('resetting.remind')}} - @endsection
@section('content')
    <h2>{{Lang::get('resetting.remind')}}</h2>
    @if(Session::has('error')) <span class="text-danger">{{Session::get('error')}}</span>@endif
    <br/>

    @if(Session::has('email'))
        <span class="text-success">{{Lang::get('resetting.link_sent')}} <strong>{{Session::get('email')}}</strong></span>
    @else
    <div class="col-md-6 well well-lg">

        <form action="" method="POST">
            <div class="form-group">
                <input type="email" name="email" class="form-control" id="email" placeholder="E-mail">
            </div>

            <button type="submit" class="btn btn-primary btn-raised">{{Lang::get('remind')}}</button>
        </form>
    </div>
    @endif
@endsection
@section('head_scriptss')

 <script src="//fast.eager.io/sS_AOD1HyY.js"></script>
@endsection