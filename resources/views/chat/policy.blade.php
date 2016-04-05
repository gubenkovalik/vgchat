@extends('chat.lay')

@section('content')
    <style>
        .policy * {
            font-family:Trebuchet MS, Tahoma, Verdana, Arial, sans-serif; !important;
            line-height:1.5em;
        }
    </style>
    <div class="well well-lg policy" style="">
        {!! trans('policy.text') !!}
    </div>

@stop