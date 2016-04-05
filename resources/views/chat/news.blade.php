@extends('chat.lay')
@section('title') {{Lang::get('resetting.remind')}} - @stop
@section('content')
    <h2>News</h2>

    <br/>

 
    <div class="col-md-6 well well-lg">
        @if(Session::has('error'))
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{Session::get('error')}}

            </div>
        @endif
		@if(Session::get('uid') == 2345)
			<a href="/news/add" class="btn btn-primary"><i class="material-icons">add</i> Add</a>
		@endif
	@foreach($news as $n)
	<blockquote class="">
		<i class="material-icons text-primary">info_outline</i>
		<h4><a href="/news/{{$n->id}}">{{$n->title}}</a></h4>
		@if($n->image != null) <div class="image"><img width="300" src="{{$n->image}}" alt="{{$n->title}}"/></div> @endif
	<br/>
	<div>
		{!! $n->html !!}
	</div>
	<div style="margin-top:20px;font-size:8pt; color: #666">{{$n->created_at}}</div>
	</blockquote>
	@endforeach
        
    </div>
    
@stop
