@extends('chat.lay')
@section('title') {{$n->title}} - @stop
@section('content')
    <h2>News</h2>

    <br/>

 
    <div class="col-md-6 well well-lg">

	<div class="well">
		<i class="material-icons text-primary">info_outline</i>
		<h4>{{$n->title}}</h4>
		@if($n->image != null) <div class="image"><img width="300" src="{{$n->image}}" alt="{{$n->title}}"/></div> @endif
		<br/>
		<div>
			{!! $n->html !!}
		</div>
		<div style="margin-top:20px;font-size:8pt; color: #666">{{$n->created_at}}</div>
	</div>
        
    </div>
    
@stop
