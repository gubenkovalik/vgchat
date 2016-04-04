@extends('chat.lay')
@section('content')

    <script>
        var downloadTrack = function(el) {
            var url = $(el).attr('data-secure');
            var name = $(el).attr('data-name');
            $.ajax({
                url:'/audio/getlink',
                type: 'PATCH',
                data: {link:url+"7Cy3Dh4%9)CjsdD"+name}
            }).done(function(b64){
                location.href='/audio/download/'+b64;
            });


        };
    </script>
    <style>


.player-controls .slider {
    display: inline-block;
    margin: 3px;
    position: relative;
    left: 9px;
    margin-right: 9px;
    top: 6px;
    width: 66%;
    height: 8px !important;
    border: 1px solid #ddd;
}

.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {

    background: #272fe9 !important;
    width: 5px !important;
    border: none !important;
    box-shadow: 0px 0px 3px black;
    -webkit-box-shadow: 0px 0px 3px black;
    -moz-box-shadow: 0px 0px 3px black;
    -webkit-border-radius:100%;
    -moz-border-radius:100%;
    border-radius:100%;

}
@media screen and (max-width: 1200px) {
    .player-controls .slider {
        width: 51%;
    }
}
@media screen and (max-width: 550px) {
    .player-controls .slider {
        width: 34%;
    }
}
@media screen and (max-width: 450px) {
    .player-controls .slider {
        width: 30%;
    }
}
    </style>
    <div class="col-md-8 well" style="margin-top:30px">


            <div class="form-group">
                <select id="src" class="form-control">
                    <option value="search" selected>Search</option>
                    <option value="playlist">Playlist</option>
                </select>
            </div>
        <form ng-submit="doSearch()" id="searchForm">
            <div class="form-group label-floating">
                <div class="input-group" style="width: 100%">
                    <label class="control-label" for="q">{{trans('audio.search')}}</label>
                    <input value="{{$q}}" type="text" class="form-control u-full-width" id="q"/>
                </div>
            </div>
</form>
            <div class="panel panel-default" id="">

                <div class="panel-body">
                    <div class="slider shor"></div>
                    <p class="text-center" id="loadingInd" ng-show="loading"><img src="/assets/images/preloader.gif"/></p>
                    <div class="list-group" id="allAudio" style="display:none">
                        <div class="wow slideInUp" data-wow-duration="0.5s" ng-hide="loading" ng-repeat="track in audios">
                            <div class="list-group-item" style="width: 100% !important;">

                                <div class="row-content" style="width: 100% !important;">

                                    <h4 class="list-group-item-heading">@{{ track.artist }}</h4>

                                    <p class="list-group-item-text">@{{ track.title }}

                                    </p>
                                    <div data-aid="@{{ track.aid }}" data-duration="@{{ track.duration_seconds }}" ng-src="@{{ trustSrc(track.url) }}" class="player-controls">
                                        <a href="javascript:void(0);" class="btn btn-fab btn-fab-mini btn-inverse" onclick="playTrack(this)"><i class="playbtnind material-icons">play_arrow</i> </a>

                                        <div class="slider progress">
                                            <div class="progress-bar progress-bar-inverse" style="width: 0%"></div>
                                        </div>

                                        <a class="btn btn-sm btn-inverse" onclick="downloadTrack(this)" data-name="@{{ track.artist }} - @{{ track.title }}" data-secure="@{{ trustSrc(track.url) }}"><i class="material-icons">file_download</i></a>
                                        <a class="btn btn-sm btn-primary btn-fab btn-fab-mini" onclick="addToPlaylist(this)" data-aid="@{{ track.aid }}" data-oid="@{{ track.owner_id }}"><i class="material-icons">@{{ track.added }}</i></a>
                                        <div style="color: #888; font-size: 9pt"><span style="color: #888; font-size: 9pt" class="currentTime">00:00</span> / @{{ track.duration }}</div>
                                    </div>

                                </div>
                            </div>
                            <div class="list-group-separator"></div>
                        </div>
                    </div>
                </div>
            </div>

    </div>


@endsection

@section('scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="/assets/css/drop.css"/>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/6.2.0/jquery.nouislider.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-sanitize.min.js"></script>
    <script src="/assets/js/audio.load.js"></script>
    <script src="/assets/js/audio.js"></script>
    <script src="/assets/js/libs/drop.js"></script>
    <script>
        $("#src").dropdown();
        $("#src").change(function(){
            switch (this.value) {
                case 'playlist':
                    loadPlaylist();
                    break;
                default:
                    loadSearch();
                    break;
            }
        });
    </script>

@endsection